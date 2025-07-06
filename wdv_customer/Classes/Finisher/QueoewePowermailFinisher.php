<?php

namespace WDV\WdvCustomer\Finisher;
/**
 *
 * @author    Harald Schäfer <h.schaefer@wdv.de>
 * @author    Christian Hillebrand <typo3@webxass.de>
 * @package    TYPO3
 * @subpackage    tx_wdvcustomer
 */

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;
use In2code\Powermail\Finisher\AbstractFinisher;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WDV\WdvCustomer\Export\PdfExport;
use WDV\WdvCustomer\Export\XmlExport;
use WDV\WdvCustomer\Service\QueoeweService;

class QueoewePowermailFinisher extends AbstractFinisher
{
    public bool $formSubmitted;
    private ?Logger $logger = null;

    /**
     * The main method called by the controller
     *
     * @return void
     * @throws DBALException
     * @throws Exception
     */
    public function myFinisher()
    {

        # log needs switch
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(self::class);
        # log needs switch
        $this->logger->log(LogLevel::INFO, "EWE finisher");

        // Settings and configurations
        $settings = $this->getSettings();

        // if EWE is enabled in Backend
        if (isset($settings['main']['ewecheck']) && $this->formSubmitted) {

            # log needs switch
            $this->logger->log(LogLevel::INFO, "EWE finisher activated");

            // if ewe check in backend is enabled and if ewe checkbox is checked we start validating
            if (!isset($this->getMail()->getAnswersByFieldMarker()['ewe'])) {
                # log needs switch
                $this->logger->log(LogLevel::INFO, "EWE Fehler im Formularaufbau: EWE Feld fehlt");
                return;
            }

            $ewe = strtolower((string)($this->getMail()->getAnswersByFieldMarker()['ewe']->getValue()[0] ?? '')) == "ja";

            // get relevant data from form
            $extractedData = $this->extractFormData($this->getMail(), $ewe);

            // campaign aus backend setzen, sofern vorhanden
            if (isset($settings['main']['campaigncheck']) && !empty($settings['main']['campaigncheck'])) {
                $extractedData['campaign'] = $settings['main']['campaigncheck'];
            }

            if ($ewe) $validation = $this->validateFormData($extractedData);


            if ($ewe && $validation["status"]) {
                $this->logger->log(LogLevel::INFO, "EWE Service calling");
                $queoService = new QueoeweService();
                $queoService->setData($extractedData);

                $this->logger->log(LogLevel::INFO, "EWE EXPORT starting");
                $baseDirectory = dirname(Environment::getPublicPath()) . '/ewe-exports/';
                $fileName = strtoupper(substr(md5(time() . '_' . $extractedData['nachname'] ?? 'unknown'), 0, 16));

                $xmlExport = new XmlExport();
                $xmlExport->saveToFile(
                    $baseDirectory . 'xml_exports',
                    $xmlExport->generateXmlContent($extractedData),
                    $xmlExport->generateFileName($fileName, 'XML')
                );
                $this->logger->log(LogLevel::INFO, "EWE EXPORT " . $fileName . ".XML done");

                $pdfExport = new PdfExport();
                $pdfExport->saveToFile(
                    $baseDirectory . 'pdf_exports',
                    $pdfExport->generatePdfContent($extractedData),
                    $pdfExport->generateFileName($fileName, 'PDF')
                );
                $this->logger->log(LogLevel::INFO, "EWE EXPORT " . $fileName . ".PDF done");

                $this->logger->log(LogLevel::INFO, "EWE finisher done");

            }else if ($ewe && !$validation["status"]) {
                $this->logger->log(LogLevel::INFO, "EWE Finisher canceled - validation error");
                $this->logger->log(LogLevel::INFO, implode("\r\n", $validation["errors"]));
			}else{
                $this->logger->log(LogLevel::INFO, "EWE Finisher canceled - not accepted by customer");
            }
        }
    }


    /**
     * Extract needed data from form
     *
     * @param $mail
     * @param $ewe
     * @return array $result
     * @throws Exception
     */
    public function extractFormData($mail, $ewe): array
    {

        $formData = ['ewe' => '', 'campaign' => '', 'anrede' => '', 'vorname' => '', 'nachname' => '', 'strasse' => '', 'hausnummer' => '', 'plz' => '', 'ort' => '', 'tag' => '', 'monat' => '', 'jahr' => '', 'email' => '', 'phone' => '', 'mobile' => '', 'aokversichert' => '', 'versichertennummer' => '', 'kassennummer' => '', 'versichertbei' => '', 'datennutzungstext' => '', 'datenschutzhinweis' => ''];

        foreach ($mail->getForm()->getFields() as $singleField) {
            $value = "";
            switch (strtolower((string)$singleField->getMarker())) {
                case "hausnr":
                    $key = "hausnummer";
                    break;

                case "telefon":
                case "festnetz":
                    $key = "phone";
                    break;

                case "mobile":
                case "handynummer":
                case "mobilfunknummer":
                    $key = "mobile";
                    break;

                default:
                    if (stristr((string)$singleField->getMarker(), "geburtsdatum") && $singleField->getMarker() != "geburtsdatum") {
                        $key = "geburtsdatum";
                    } else {
                        $key = strtolower((string)$singleField->getMarker());
                    }
            }


            if ($singleField->getText()) $value = $singleField->getText();

            if (isset($mail->getAnswersByFieldMarker()[$singleField->getMarker()])) {
                $value = $mail->getAnswersByFieldMarker()[$singleField->getMarker()]->getValue();
            }

            if ($singleField->getContentElement() != 0) {
                $uid = $singleField->getContentElement();

                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tt_content');
                $queryBuilder->select('bodytext')
                    ->from('tt_content')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                        ),
                    )
                    ->setMaxResults(1);
                $statement = $queryBuilder->executeQuery();
                $value = $statement->fetchOne();
            }

            if ($key == "geburtsdatum") {
                $birthday = explode('.', (string)$value);
                if(is_array($birthday) && count($birthday) == 3)
                {
                    $formData['jahr'] = $birthday[2];
                    $formData['monat'] = $birthday[1];
                    $formData['tag'] = $birthday[0];
                }
            } elseif (isset($formData[$key])) {
                // nur nötige Felder übernehmen
                $formData[$key] = $value;
            }

//          \TYPO3\CMS\Core\Utility\DebugUtility::debug($singleField->getMarker());
//            if ($singleField->getMarker() == 'einwilligungserklaerung_text') $datennutzungstext = $singleField->getText();
//            if ($singleField->getMarker() == 'datenschutzhinweis') $datenschutzhinweis = $singleField->getText();
        }

        $formData['ewe'] = ($ewe) ? 'ja' : 'nein';
        if ($ewe) {
            $formData['smsdoinumber'] = $_POST['tx_powermail_pi1']['smsdoinumber'];
            $smsdoi = $GLOBALS['TSFE']->fe_user->getKey('ses', 'smsdoi')[$formData['smsdoinumber']] ?? [];

            if (is_array($smsdoi)) {
                $valid = time() - (15 * 60);
                foreach ($smsdoi as $code => $data) {
                    if (!empty($data["confirmed"]) && $data["confirmed"] > $valid) {
                        $formData['smsdoicode'] = $code;
                        $formData['smsdoinumber'] = $code;
                        $formData['smsdoisent'] = $data["sent"];
                        $formData['smsdoiconfirmed'] = $data["confirmed"];
                        $formData['smsdoimessage'] = $data["message"];
                        break;
                    }
                }
            }
        }

        return $formData;
    }

    /**
     * checks if all needed fields are set
     *
     * @param $data
     * @return array $result
     */
    public function validateFormData($data): array
    {

        /*
         * email, phone, mobile is not checked cause only one of them would be needed
         */
        $mandatorys = ['ewe' => '', 'anrede' => '', 'vorname' => '', 'nachname' => '', 'strasse' => '', 'hausnummer' => '', 'plz' => '', 'ort' => '', 'tag' => '', 'monat' => '', 'jahr' => '', 'datennutzungstext' => '', 'datenschutzhinweis' => ''];
        if ($data["ewe"]) {
            $mandatorys["smsdoicode"] = '';
            $mandatorys["smsdoimessage"] = '';
            $mandatorys["smsdoinumber"] = '';
            $mandatorys["smsdoisent"] = '';
            $mandatorys["smsdoiconfirmed"] = '';
        }

        foreach (array_keys($mandatorys) as $key) {
            if (!isset($data[$key]) || $data[$key] == "")
                $status[] = 'Marker ' . $key . ' fehlt';
        }

        if (isset($status)) {
            return ["status" => false, "errors" => $status];
        }

        return ["status" => true];
    }

}
