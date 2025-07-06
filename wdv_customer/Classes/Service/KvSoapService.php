<?php
namespace WDV\WdvCustomer\Service;

/**
 * @extensionScannerIgnoreFile
 */
/**
 * Api for SOAP connection to t-system kv-Nummer validator
 */
class KvSoapService extends \SoapClient
{

    private $_kassenNr;

    private $_versichertenNr;

    private ?array $_lastResult = null;


    /**
     * Der Constructor instantiert den Aok_Soap_Client es wird das
     * übergebene Soap-Handle ausgelesen und die Uri für Client und
     * Server werdem an den Parent-Constructor übergeben.
     *
     * @param string $wsdl    wsdl-Konfiguration
     * @param mixed[]|null $options die Optionen mit Handle
     *
     * @return void
     */
    public function __construct ($wsdl = null, array $options = null)
    {
        $wsdl = 'https://zgp.aok.de/sap/bc/srt/wsdl/bndg_E13D3BFCE65BB7F18D6500215A9B004E/wsdl11/allinone/standard/document?sap-client=333';
        $options['login'] = 'VS_WDV';
        $options['password'] = '%NKFHVK6';

        // Parent Daten übergeben
        if (isset($wsdl) && isset($options['login']) && isset($options['password'])) {
            $wsdl = str_replace("://", "://" . $options['login'] . ":" . $options['password'] . "@", $wsdl);
        }

        // Wenn ein Proxy auf der ZSA-Maschine vorhanden ist, dann aus den Environment-Variablen holen und benutzen.
        if (static::getProxy() != false) {
            $proxy = static::getProxy();
            $proxy = str_replace('http://', '', (string) $proxy);
            $proxy = str_replace('https://', '', $proxy);
            $proxyHost = $proxy;
            $proxyPort = 8080;
            if (strstr($proxy, ':')) {
                $proxyParts = explode(':', $proxy);
                if (count($proxyParts) == 2) {
                    $proxyHost = $proxyParts[0];
                    $proxyPort = $proxyParts[1];
                }
            }

            $options['proxy_host'] = $proxyHost;
            $options['proxy_port'] = $proxyPort;
        }

        parent::__construct($wsdl, $options);
    }

    /**
     * Erweitert die SoapClient Funktion __doRequest indem sie das generierte Request mit dem Abfrage Request überschreibt.
     *
     * @param string $request  Das Request
     * @param string $location Die URL
     * @param string $action   Auszuführende Action
     * @param string $version  Soap Version
     * @param int $one_way [optional]
     *
     * @return string
     */
    #[TentativeType]
    public function __doRequest(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $request,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $location,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $action,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $version,
        #[LanguageLevelTypeAware(["8.0" => 'bool'], default: 'int')] $oneWay = false
    ): ?string
    {
        $one_way = null;
        $requestMod = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:sap-com:document:sap:soap:functions:mc-style">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <urn:_-aok_-ip03GetVstatus9>
                                    <IKassenNr>' . $this->_kassenNr . '</IKassenNr>
                                    <IKvNr>' . $this->_versichertenNr . '</IKvNr>
                                    <!--Optional:-->
                                    <IUserOption>?</IUserOption>
                                </urn:_-aok_-ip03GetVstatus9>
                            </soapenv:Body>
                        </soapenv:Envelope>';
        return parent::__doRequest($requestMod, $location, $action, $version, $one_way);
    }

    /**
     * Validiert die Kassen und Versichertennummer, gegen den Datenbestand der Aok
     *
     * @param string $kassenNr
     * @param string $versichertenNr
     * @param bool   $debug
     *
     * @return array
     *
     */
    public function validateInputWithResult ($kassenNr = 12345, $versichertenNr = 5678, $debug = false)
    {
        $this->_kassenNr = $kassenNr;
        $this->_versichertenNr = $versichertenNr;

        try {
            $resultObject = $this->__soapCall("_-aok_-ip03GetVstatus9", []);
        } catch (Exception) {
            return ['IsSapServiceError' => true];
        }

        $resultArray = $this->objectToArray($resultObject);
//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($resultArray);
//        die();
        $this->_lastResult = $resultArray;
        return $resultArray;
    }

    /**
     * Validiert die Kassen und Versichertennummer, gegen den Datenbestand der Aok
     *
     * @param string $kassenNr
     * @param string $versichertenNr
     * @param bool   $debug
     *
     * @return boolean|int
     *
     */
    public function validateInput ($kassenNr = 12345, $versichertenNr = 5678, $debug = false)
    {
        $this->_kassenNr = $kassenNr;
        $this->_versichertenNr = $versichertenNr;

        $resultArray = $this->validateInputWithResult($kassenNr, $versichertenNr, $debug);
        if (isset($resultArray['EResult']) && $resultArray['EResult'] == "000") {
            return true;
        } elseif (isset($resultArray['IsSapServiceError'])) {
            return -1;
        } else {
            return false;
        }
    }

    /**
     * Wandelt ein Objekt in einen Array um
     * dies ist notwendig da die Rückgabe Attribute
     * von Webservices nicht immer den Coding Conventions
     * entsprechen.
     *
     *
     * @return array
     */
    protected function objectToArray (mixed $object)
    {

        if (is_object($object)) $object = (array) $object;

        if (is_array($object)) {

            $new = [];
            foreach ($object as $key => $value) {

                $new[$key] = $this->objectToArray(($value));
            }
        } else {

            $new = $object;
        }

        return $new;
    }

    /**
     * Holt den Array mit dem letzten Result
     *
     * @return array
     */
    public function getResult()
    {
        return $this->_lastResult;
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