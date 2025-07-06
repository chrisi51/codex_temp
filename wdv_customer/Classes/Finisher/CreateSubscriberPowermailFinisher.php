<?php

namespace WDV\WdvCustomer\Finisher;
/**
 *
 * @author    Harald Schäfer <h.schaefer@wdv.de>
 * @package    TYPO3
 * @subpackage    tx_wdvxqueue
 */

use In2code\Powermail\Finisher\AbstractFinisher;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class CreateSubscriberPowermailFinisher extends AbstractFinisher
{
    public bool $formSubmitted;
    private string $extKey = 'wdv_xqueue';

    /**
     * The main method called by the controller
     *
     * @return array The probably modified GET/POST parameters
     */
    public function myFinisher(): void
    {
        // Settings and configurations
        $settings = $this->getSettings();
        $configuration = $this->getConfiguration();

        $mailformConfig = false;

        // reguläre Newsletteranmeldung
        if (isset($settings['main']['maileon_newsletter']) && $this->formSubmitted) {
            $mailformConfig = true;
            $newsletter = 1;
        }

        // Kursanmeldung mit Mailversandprozess
        if (isset($this->getMail()->getAnswersByFieldMarker()['kurs']) && $settings['main']['maileon_kursanmeldung'] && $this->formSubmitted) {
            $mailformConfig = true;
            $kurs = $this->getMail()->getAnswersByFieldMarker()['kurs']->getValue();
        }


        // Wenn keine Konfiguration vorhanden, dann Abbruch!
        if (!$mailformConfig) return;

        $debugmode = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_' . $this->extKey . '.']['maileon.']['debug'];
        $apiKey = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_' . $this->extKey . '.']['maileon.']['apiKey'];
        $doiKey = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_' . $this->extKey . '.']['maileon.']['doiKey'];

        // Include the Maileon API Client classloader
        require_once(ExtensionManagementUtility::extPath($this->extKey) . 'Resources/Library/maileonApi/MaileonApiClient.php');

        // Wenn ein Proxy auf der ZSA-Maschine vorhanden ist, dann aus den Environment-Variablen holen und benutzen.
        if (static::getProxy() !== false) {
            $proxy = static::getProxy();
            $proxy = str_replace('http://', '', (string)$proxy);
            $proxy = str_replace('https://', '', $proxy);
            $proxyHost = $proxy;
            $proxyPort = 8080;
            if (str_contains($proxy, ':')) {
                $proxyParts = explode(':', $proxy);
                if (count($proxyParts) == 2) {
                    $proxyHost = $proxyParts[0];
                    $proxyPort = $proxyParts[1];
                }
            }
        }

        // Set the global configuration for accessing the REST-API
        $config = [
            "BASE_URI" => "https://api.maileon.com/1.0",
            "API_KEY" => $apiKey,
            // -> constants.txt
            "PROXY_HOST" => $proxyHost ?? '',
            "PROXY_PORT" => $proxyPort ?? 0,
            "THROW_EXCEPTION" => true,
            "TIMEOUT" => 5,
            // 5 seconds
            "DEBUG" => $debugmode,
        ];

        $anrede = '';
        $nachname = '';
        $vorname = '';
        $geschlecht = '';

        if (isset($this->getMail()->getAnswersByFieldMarker()['anrede'])) {
            $anrede = $this->getMail()->getAnswersByFieldMarker()['anrede']->getValue();
        }

        if (isset($this->getMail()->getAnswersByFieldMarker()['nachname'])) {
            $nachname = $this->getMail()->getAnswersByFieldMarker()['nachname']->getValue();
        }

        if (isset($this->getMail()->getAnswersByFieldMarker()['vorname'])) {
            $vorname = $this->getMail()->getAnswersByFieldMarker()['vorname']->getValue();
        }

        if (isset($this->getMail()->getAnswersByFieldMarker()['geschlecht'])) {
            $geschlecht = $this->getMail()->getAnswersByFieldMarker()['geschlecht']->getValue();
        }

        $birthday = '';
        if (isset($this->getMail()->getAnswersByFieldMarker()['geburtsdatum'])) {
            $birthday = $this->getMail()->getAnswersByFieldMarker()['geburtsdatum']->getValue();
            if (!empty($birthday)) {
                $birthday = date('Y-m-d', strtotime($birthday));
            }
        }

        $rubriken = [];
        if (isset($this->getMail()->getAnswersByFieldMarker()['rubriken'])) {
            $rubriken = $this->getMail()->getAnswersByFieldMarker()['rubriken']->getValue();
        }

        $aokVersichert = null;
        if (isset($this->getMail()->getAnswersByFieldMarker()['aokversichert'])) {
            $aokVersichert = $this->getMail()->getAnswersByFieldMarker()['aokversichert']->getValue();
            if ($aokVersichert === 'Ja') {
                $aokVersichert = 1;
            }
            if ($aokVersichert === 'Nein') {
                $aokVersichert = 0;
            }
        }

        $contactsService = GeneralUtility::makeInstance('com_maileon_api_contacts_ContactsService', $config);
        $contactsService->setDebug($debugmode);

        $newContact = GeneralUtility::makeInstance('com_maileon_api_contacts_Contact');
        $newContact->anonymous = FALSE;

        $newContact->email = $this->getMail()->getAnswersByFieldMarker()['email']->getValue() ?? '';
        $newContact->standard_fields['SALUTATION'] = $anrede ?? '';
        $newContact->standard_fields['LASTNAME'] = $nachname ?? '';
        $newContact->standard_fields['FIRSTNAME'] = $vorname ?? '';
        $newContact->standard_fields['GENDER'] = $geschlecht ?? '';
        if (!empty($birthday)) {
            $newContact->standard_fields['BIRTHDAY'] = $birthday ?? '';
        }
        if (!is_null($aokVersichert)) {
            $newContact->custom_fields['AOK-versichert'] = $aokVersichert ?? 0;
        }

        if (!empty($rubriken)) {
            foreach ($rubriken as $rubrikValue) {
                $newContact->custom_fields[$rubrikValue] = 1;
            }
        }

        $newContact->permission = new \com_maileon_api_contacts_Permission(1, "none");
        //$newContact->custom_fields["Widerruf zum pseudonymisierten Tracking"] = 0;

        $doi = false;
        if (isset($newsletter) && $newsletter === 1) {
            //Feld vermutlich obsolete da Newsletterempfänger per "DOI bestätigt" gefiltert werden
            //$newContact->custom_fields['Newsletter'] = 1;
            $doi = true;
        }

        if (isset($kurs)) {
            $newContact->custom_fields[$kurs] = date("Y-m-d");
            if ($doi === false)
                $newContact->permission = new \com_maileon_api_contacts_Permission(6, "other");
        }

        $syncMode = GeneralUtility::makeInstance('com_maileon_api_contacts_SynchronizationMode', 1);
        $result = $contactsService->createContact($newContact, $syncMode, "vigo_online", "", $doi, $doi); //, $doiKey
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
