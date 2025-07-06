<?php

namespace WDV\WdvCustomer\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use TYPO3\CMS\Core\Core\Environment;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class SmsDoiMiddleware implements MiddlewareInterface
{
    private string $apiKey = 'mFrrLq.qp&c_!btC';

    private string $apiBase = 'https://legzdsms.live.colt1.zd.aok.de/web/api/';

    private string $staticSalt = 'STATIC_HASH_SALT_AOK-2022-07-30';

    private string $aokSmsKey = 'AOK RH';

    private $logger;

    public function __construct(protected ResponseFactoryInterface $responseFactory)
    {
        # log needs switch
#        $this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            if ($request->getRequestTarget() === '/sms/send') {
                [$hash, $message] = $this->sendSms($request);
                return $this->buildResponse(200, ['status' => 'send', 'hash' => $hash, 'message' => $message]);
            }

            if ($request->getRequestTarget() === '/sms/verify') {
                if (!$hash = $this->verifyResponse($request)) {
# log needs switch
#                    $this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, "SMS NICHT verifiziert:", array($hash));
                    return $this->buildResponse(403, ['status' => 'unverified']);
                }

# log needs switch
#                $this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, "SMS verifiziert:", array($hash));
                return $this->buildResponse(200, ['status' => 'verified']);
            }
        }catch (\Exception $exception) {
            return $this->buildResponse(500, ['status' => 'Request invalid', 'error' => $exception->getMessage()]);
        }

        return $handler->handle($request);
    }

    public function verifyResponse(ServerRequestInterface $request): bool
    {
        try {
            if($request->getMethod() !== 'POST') {
                throw new \Exception('No POST Request');
            }

            $body = json_decode((string) $request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            if(!isset($body['code']) || !isset($body['hash'])) {
                throw new \Exception('At least one field is missing');
            }

            return ($body['hash'] === $this->generateHash(strtoupper((string) $body['code'])));
        }catch (\Exception) {
            return false;
        }

        return true;
    }

    protected function buildResponse(int $status, array $data = []) {
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($status);
        $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));
        return $response;
    }

    protected function generateCode(): string {
        $seed = str_split('ABCDEFGHJKMNPQRSTUVWXYZ23456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];

        return $rand;
    }

    protected function generateHash(string $code): string {
        return md5($code . $this->staticSalt);
    }

    protected function sendSms(ServerRequestInterface $request): array {
        $code = $this->generateCode();
        $hash = $this->generateHash($code);
        if($request->getMethod() !== 'POST') {
            throw new \Exception('No POST Request');
        }

        $body = json_decode((string) $request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        if(!isset($body['number']) || !isset($body['message'])) {
            throw new \Exception('At least one field is missing');
        }

        $message = str_replace('_CODE_', $code, (string) $body['message']);
        if(!Environment::getContext()->isDevelopment()) {
            $this->sendSMSApi($message, $body['number'], $this->aokSmsKey);
# log needs switch
#            $this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, "SMS verschickt:", array($hash));
        }

        return [$hash, $message];
    }

    protected function sendSMSApi($message, $to, $from): void {
        // $this->apiKey
        $client = new Client([
            'base_uri' => $this->apiBase,
            'timeout'  => 2.0,
            'headers' => [
            /*    'content-type:' => 'application/json', 'api-authorisation-key' => $this->apiKey*/
            'api-authorisation-key' => $this->apiKey
            ],
            'verify' => false
        ]);
        $client->post('v1/sms/send.json', [
            'query' => [
                'receiver' => $to,
                'sender' => $from,
                'message' => $message,
                //'service' => 'message_mobile',
                'data' => [
                    "regionId" => 5, // Client-ID, Pflichtangabe
                    "projectId" => 'vigo.de'
                ]
            ]
        ]);
    }
}
