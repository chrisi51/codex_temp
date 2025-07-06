<?php
declare(strict_types = 1);

namespace WDV\WdvCustomer\Controller;

use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestFactoryInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

use TYPO3\CMS\Core\Utility\DebugUtility;


/*
 * Sessionformat:
 *
 *  smsdoi = [
        123456789 = [
            123456 = [
                sent = 123456789
                message = tralalala
            ],
            234567 = [
                sent = 123456789
                message = tralalala
            ],
            345689 = [
                sent = 123456789
                message = tralalala
            ],
            678901 = [
                sent = 123456789
                message = tralalala
                confirmed = 123456789
            ]
        ],
        234567890 = [
            678901 = [
                sent = 123456789
                message = tralalala
            ]
        ]
    ]
 *
 */

class SmsDoiController extends ActionController
{
    /** @var ResponseFactoryInterface */
    protected $responseFactory;

    private ?Logger $logger = null;

    private $smsdoi;

    private int $validDuration = 60*15; // 15 minutes

    private string $apiKey = 'mFrrLq.qp&c_!btC';

    private string $apiBase = 'https://legzdsms.live.colt1.zd.aok.de/web/api/';

    private string $apiURL = 'https://legzdsms.live.colt1.zd.aok.de/web/api/v1/sms/send.json';

    private string $staticSalt = 'STATIC_HASH_SALT_AOK-2022-07-30';

    private string $aokSmsKey = 'AOK RH';


    public function __construct(private readonly RequestFactoryInterface $requestFactory, ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function sendAction(): ResponseInterface
    {
        $params = $this->init(['number', 'message']);
        if($params["param_validation"]>0){
            return $this->buildResponse(403);
        }

        $code = $this->generateCode();       

        $message = str_replace('_CODE_', $code, (string) $params['message']);
        $response = $this->sendSMSApi($message, $params['number'], $code);

        $feedback = [];
        if($response){
            $this->smsdoi[$params['number']][$code]["sent"] = time();
            $this->smsdoi[$params['number']][$code]["message"] = $message;
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'smsdoi', $this->smsdoi);

            $feedback["status"] = "code sent";
        }else{
            $feedback["status"] = "code NOT sent";
        }

        if(Environment::getContext()->isDevelopment()) {
            $feedback["code"] = $code;
        }

# log needs switch
        $this->logger->log(LogLevel::INFO, "EWE SMSDOI", $feedback);
        return $this->buildResponse(200, $feedback);
    }


    public function verifyAction(): ResponseInterface
    {       
        $params = $this->init(['number', 'code']);

        // cancel action, if no number is given
        if(empty($params["number"])){
            return $this->buildResponse(403);
        }


        // re-verify: if no code is sent, try to find an already confirmed code with the given number
        if(empty($params["code"])){
            // if no code was sent to the given number (or it is already invalidated)
            if(empty($this->smsdoi[$params["number"]]))
            {
                $this->logger->log(LogLevel::INFO, "EWE SMSDOI", ["status" => "empty"]);
                return $this->buildResponse(200, ["status" => "empty"]);
            }

            // confirmation is only valid for 15 minutes 
            $valid =  time() - $this->validDuration;
            foreach($this->smsdoi[$params["number"]] as $code){
                if(!empty($code["confirmed"]) && $code["confirmed"] > $valid){
                    $this->logger->log(LogLevel::INFO, "EWE SMSDOI", ["status" => "re-verified"]);
                    return $this->buildResponse(200, ["status" => "verified"]);
                }
            }

            // if there was no match in the session, return "unverified"
            $feedback = [
                "status" => "unverified"
            ];
            $this->logger->log(LogLevel::INFO, "EWE SMSDOI", ["status" => "re-unverified"]);
            if(Environment::getContext()->isDevelopment()) {
                $feedback['session'] = $this->smsdoi;
                $feedback['session_relation'] = $this->smsdoi[$params['number']];
            }

            return $this->buildResponse(403, $feedback);
        }


        // confirmation is only valid for sms sendings max 15 minutes ago
        if(!empty($this->smsdoi[$params['number']][$params['code']]["sent"]) && $this->smsdoi[$params['number']][$params['code']]["sent"] > (time() - $this->validDuration) ){
            $this->smsdoi[$params['number']][$params['code']]["confirmed"] = time();
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'smsdoi', $this->smsdoi);

# log needs switch
            $this->logger->log(LogLevel::INFO, "EWE SMSDOI", ["status" => "verified"]);
            return $this->buildResponse(200, ["status" => "verified"]);
        }else{
            $feedback = [
                "status" => "unverified"
            ];
            if(Environment::getContext()->isDevelopment()) {
                $feedback['session'] = $this->smsdoi;
                $feedback['session_relation'] = $this->smsdoi[$params['number']][$params['code']];
            }

# log needs switch
            $this->logger->log(LogLevel::INFO, "EWE SMSDOI", $feedback);
            return $this->buildResponse(403, $feedback);
        }
    }

    protected function init($fields = []): array {
# log needs switch
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(self::class);
        // init session
        $this->smsdoi = $GLOBALS['TSFE']->fe_user->getKey('ses','smsdoi');

        $this->cleanUpSession();
/*
        DebugUtility::debug($this->$smsdoi, 'Session');
        DebugUtility::debug($this->request->getArguments(), 'getArguments');
        DebugUtility::debug($this->request->getInternalArguments(), 'getInternalArguments');
        DebugUtility::debug($this->request->getMethod(), 'getMethod');
*/        
        if(!Environment::getContext()->isDevelopment() && $this->request->getMethod() !== 'POST') {

            return false;
        }

        // check POST/GET for given fields; if a field is empty(), increment $params["param_validation"]
        $params = $this->request->getArguments();
        $params["param_validation"] = 0;
        foreach($fields as $key => $value){
            if(empty($params[$value])) {
                ++$params["param_validation"];
            }
        }

        return $params;
    }

    protected function generateCode(): string {
        $seed = str_split('0123456789');
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];

        return $rand;
    }    

    protected function sendSMSApi($message, $to, $code): bool {

        // SMS-API sendet immer 403 von Integration aus
        if(!Environment::getContext()->isDevelopment()) {
            $requestData = [
                // Additional headers for this specific request
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'api-authorisation-key' => $this->apiKey,
                    'Content-type' => 'application/x-www-form-urlencoded'
                ],
                'allow_redirects' => false,
                'timeout'  => 2.0,
                'verify' => false,
                'form_params' => [
                    'receiver' => $to,
                    'sender' => $this->aokSmsKey,
                    'message' => $message,
                    'data' => [
                        "regionId" => 5, // Client-ID, Pflichtangabe
                        "projectId" => 'vigo.de'
                    ]
                ]
            ];

            // Return a PSR-7 compliant response object
            $response = $this->requestFactory->request($this->apiURL, 'POST', $requestData);
            // Get the content as a string on a successful request
            return $response->getStatusCode() === 200;
        }

        return true;
    }    


    // removes all invalidated codes and numbers if there are no codes on them anymore
    protected function cleanUpSession(){
        unset($this->smsdoi[""]);
        if(is_array($this->smsdoi)){
            foreach($this->smsdoi as $number => $number_value){
                foreach($number_value as $code => $code_value){
                    if (!empty($code_value["confirmed"]) && $code_value["confirmed"] < (time() - $this->validDuration)) {
                        unset($this->smsdoi[$number][$code]);
                    } elseif (empty($code_value["confirmed"]) && $code_value["sent"] < (time() - $this->validDuration)) {
                        unset($this->smsdoi[$number][$code]);
                    }
                }

                if(empty($number_value)) unset($this->smsdoi[$number]);
            }
        }

        $GLOBALS['TSFE']->fe_user->setKey('ses', 'smsdoi', $this->smsdoi);
    }


    protected function buildResponse(int $status, array $data = []) {
        $feedback = json_encode($data, JSON_THROW_ON_ERROR);
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($status);
        $response->getBody()->write($feedback);
        return $response;
    }
    
}