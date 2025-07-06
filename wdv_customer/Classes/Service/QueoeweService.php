<?php

namespace WDV\WdvCustomer\Service;

use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @extensionScannerIgnoreFile
 */

/**
 * Api for queo ewe service
 */
class QueoeweService
{
    private readonly Logger $logger;

    /**
     *
     * @var String
     */
    public $bodyParams;

    /**
     *
     * @var string
     */
    public $url = 'https://nutzer.zd.aok.de/web/api/v1/ewe/agreement.json';

    /**
     *
     * @var String
     */
    public $headers = [
        'Api-Authorisation-Key' => 'tyviMi4b8QScnd8B',
        'Accept' => 'application/json',
        'Content-type' => 'application/json'
    ];

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        # log needs switch
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(self::class);
# log needs switch
#        $this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, "EWE construction");

    }

    /**
     * Rendert die Dankeseite
     *
     * @param array $formData array
     * @return void
     */
    public function setData($formData): void
    {

        $bodyParams = [];

        $ewe_permission = $formData["ewe"] == "ja";

        $httpHost = $_SERVER['HTTP_HOST'];

        switch (true){
            case stristr((string) $httpHost,'achtsamkeit'):
                $bodyParams['campaign'] = "Kampagne vigo Gib8";
                $bodyParams['server'] = 'achtsamkeit.vigo.de';
            break;
            case stristr((string) $httpHost,'soundtrack'):
                $bodyParams['campaign'] = "Kampagne Soundtrack";
                $bodyParams['server'] = 'soundtrack.vigo.de';
            break;
            default:
                $bodyParams['campaign'] = "vigo.de";
                $bodyParams['server'] = 'www.vigo.de';
            break;
        }

        if (!empty($formData['campaign'])) {
            $bodyParams['campaign'] =  $formData['campaign'];
        }

        $bodyParams['client'] = "5";

        $uriParts = explode('?', (string) $_SERVER['REQUEST_URI'], 2);
        $bodyParams['url'] = $httpHost . $uriParts[0];
        $cid = GeneralUtility::_GP('cid');
        preg_match_all('/^[0-9a-zA-Z_-]+$/', (string) $cid, $output);
        if(!empty($output[0][0])) {
            $bodyParams['url'] .= '?cid=' . $output[0][0];
            $bodyParams['trackingCode'][0]['key'] = 'cid';
            $bodyParams['trackingCode'][0]['value'] = $output[0][0];
        }

        $bodyParams['isActive'] = $ewe_permission;
        $bodyParams['individual']['firstName'] = $formData['vorname'] ?? 'N.N.';
        $bodyParams['individual']['lastName'] = $formData['nachname'];

        $bodyParams['individual']['dob'] = $formData['jahr'].'-'.$formData['monat'].'-'.$formData['tag'];

        $bodyParams['individual']['gender'] = match ($formData['anrede']) {
            "Herr", "m", "male" => 'male',
            "Frau", "f", "female" => 'female',
            "Divers", "d", "diverse" => 'diverse',
            "Unbestimmt", "u", "undetermined" => 'undetermined',
            default => 'unspecified',
        };


        if (!empty($formData['email'])) {
            $bodyParams['individual']['contacts'][0]['type'] = 'email';
            $bodyParams['individual']['contacts'][0]['data'] = $formData['email'];
            // E-Mail-Permission darf nur bei E-Mail-OptIn erteilt werden ... wir machen aber ausschließlich SMS DOI
            // $bodyParams['permissions']['viaEmail'] = $ewe_permission;
        }

        if (!empty($formData['phone'])) {
            $bodyParams['individual']['contacts'][1]['type'] = 'phone';
            $bodyParams['individual']['contacts'][1]['data'] = $formData['phone'];
            $bodyParams['permissions']['viaPhone'] = $ewe_permission;
        }

        if (!empty($formData['mobile'])) {
            $bodyParams['individual']['contacts'][2]['type'] = 'mobile';
            $bodyParams['individual']['contacts'][2]['data'] = $formData['mobile'];
            $bodyParams['permissions']['viaSms'] = $ewe_permission;
        }


        $formData['datennutzungstext'] = str_replace('<h4>Einwilligungserklärung</h4>', '', (string) $formData['datennutzungstext']);
        $formData['datenschutzhinweis'] = str_replace('<h4>Datenschutzhinweis</h4>', '', (string) $formData['datenschutzhinweis']);
        $bodyParams['terms']['dataProtectionText'] = trim($formData['datennutzungstext']);
        $bodyParams['terms']['dataProtectionHint'] = trim($formData['datenschutzhinweis']);

        if(!empty($formData['smsdoicode'])){
            $bodyParams['doiTime']['smsDoiSend'] = date("Y-m-d H:i:s", $formData['smsdoisent']);
            $bodyParams['doiTime']['smsDoiConfirmed'] = date("Y-m-d H:i:s", $formData['smsdoiconfirmed']);
        }

        $bodyParams['formEntryCreatedAt'] = date("Y-m-d H:i:s");

        // nicht in der doku zu finden
        // $bodyParams['region'] = FALSE;

        $bodyParams['individual']['address']['zipId'] = $formData['plz'];
        $bodyParams['individual']['address']['number'] = $formData['hausnummer'];
        $bodyParams['individual']['address']['street']['name'] = $formData['strasse'];
        $bodyParams['individual']['address']['city']['name'] = $formData['ort'];

        if (!empty($formData['versichertbei'])) {
            $bodyParams['individual']['insurance'] = $formData['versichertbei'];
        }

        if ($formData['aokversichert'] == 'Ja') {
            $bodyParams['individual']['isAokMember'] = TRUE;
            $bodyParams['additionalInformation'] = 'Versichertennummer: '.$formData['versichertennummer'].', Kassennummer: '.$formData['kassennummer'];
        } elseif (!empty($formData['versichertenNummer'])) {
            // Wenn die Felder direkt (ohne Checkbox AOK versichert angezeigt werden)
            $bodyParams['additionalInformation'] = 'Versichertennummer: '.$formData['versichertennummer'].', Kassennummer: '.$formData['kassennummer'];
        }

        $this->bodyParams = $bodyParams;

        if(!Environment::getContext()->isDevelopment()) {
            $this->logger->log(LogLevel::INFO, "EWE sending:", [$bodyParams["url"], $bodyParams["campaign"]]);
            $feedback = $this->send();
            $this->logger->log(LogLevel::INFO, "EWE versandt:", [$feedback->{'http_status'}]);
            if(Environment::getContext()->__toString() === "Production/ZSA/Vorschau") {
                $this->logger->log(LogLevel::INFO, "EWE versandt:", [$feedback->result]);
            }
        }else{
            $this->logger->log(LogLevel::INFO, "EWE fakesending:", [$bodyParams["url"], $bodyParams["campaign"]]);
            $this->logger->log(LogLevel::INFO, "EWE fakesending:", [$bodyParams]);
        }
    }

    /**
     * Sendet den Request
     *
     * @return void
     */

    public function send(): \stdClass
    {

        $handle = curl_init($this->url);
        curl_setopt_array($handle, [
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [

                'Api-Authorisation-Key: tyviMi4b8QScnd8B',
                'Accept: application/json',
                'Content-type: application/json'
            ]
        ]);

        // Wenn ein Proxy auf der ZSA-Maschine vorhanden ist, dann aus den Environment-Variablen holen und benutzen.
        if (static::getProxy() != false) {
            $proxy = static::getProxy();
            curl_setopt($handle, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($handle, CURLOPT_PROXY, $proxy);
        }

        curl_setopt($handle, CURLOPT_POST, true);


        if (strlen(json_encode($this->bodyParams, JSON_THROW_ON_ERROR)) > 0) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($this->bodyParams, JSON_THROW_ON_ERROR));
        }

        $result = curl_exec($handle);
# log needs switch
#        $this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, "EWE versandt:", array(curl_getinfo($handle, CURLINFO_HTTP_CODE)));

        $return = new \stdClass();
        $return->{'http_status'} = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $return->result = $result;
        return $return;
    }

    /**
     * Gibt den aktuellen Proxy-Server zurück. Dieser wird
     * für Verbindungen nach Aussen benötigt.
     *
     * @return mixed
     */
    public static function getProxy()
    {
        $httpsProxy = false;
        $httpProxy = false;

        if (!empty($_SERVER['HTTPS'])) {
            if ($httpsProxy === false) {
                if (!empty($_SERVER['HTTPS_PROXY'])) {
                    return $_SERVER['HTTPS_PROXY'];
                }

                return false;
            }

            return $httpsProxy;
        } else {
            if ($httpProxy === false) {
                if (!empty($_SERVER['HTTP_PROXY'])) {
                    return $_SERVER['HTTP_PROXY'];
                }

                return false;
            }

            return $httpProxy;
        }
    }

}
