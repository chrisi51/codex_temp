<?php

namespace WDV\WdvCustomer\Export;

use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractExport
{
    protected function mappingFormData(array $extractedData): array
    {
        $formData = [
            'kassennummer' => $extractedData['kassennummer'],
            'anrede' => $extractedData['anrede'],
            'vorname' => $extractedData['vorname'],
            'nachname' => $extractedData['nachname'],
            'strasse' => $extractedData['strasse'],
            'hausnummer' => $extractedData['hausnummer'],
            'plz' => $extractedData['plz'],
            'ort' => $extractedData['ort'],
            'geburtsdatum' => $extractedData['tag'] . '.' . $extractedData['monat'] . '.' . $extractedData['jahr'],
            'email' => $extractedData['email'] ?? '-',
            'phone' => $extractedData['phone'] ?? '-',
            'mobile' => $extractedData['mobile'] ?? '-',
            'aokversichert' => $extractedData['aokversichert'],
            'zeitpunkt' => date('d.m.Y H:i:s'),
            'dokumentklasse' => 'RH-02-018_mit_SMS_DOI',
            'datennutzungstext' => trim(strip_tags($extractedData['datennutzungstext'])),
            'datenschutzhinweis' => trim(strip_tags($extractedData['datenschutzhinweis'])),
        ];

        if(!empty($extractedData['ewe'])){
            $formData['sms'] = 'sms';
        }

        if(!empty($extractedData['smsdoicode'])){
            $formData['smsdoi'] = 'Ja';
            $formData['smsDoiSend'] = date("Y-m-d H:i:s", $extractedData['smsdoisent']);
            $formData['smsDoiConfirmed'] = date("Y-m-d H:i:s", $extractedData['smsdoiconfirmed']);
        }

        if (!empty($extractedData['email'])) {
            // E-Mail-Permission darf nur bei E-Mail-OptIn erteilt werden ... wir machen aber ausschließlich SMS DOI
            // $bodyParams['permissions']['viaEmail'] = $ewe_permission;
        }

        $permissions = [];
        if (!empty($extractedData['phone'])) {
            $permissions[] = 'Telefon';
        }
        if (!empty($extractedData['mobile'])) {
            $permissions[] = 'SMS';
        }
        $formData['permissions'] = implode(', ', $permissions);

        return $formData;
    }

    public function generateFileName(string $filename, string $fileExtension): string
    {
        $sanitizedFilename = $this->sanitizeFileName($filename);
        return ($sanitizedFilename . '.' . $fileExtension);
    }

    public function saveToFile(string $directory, string $content, string $fileName): void
    {
        GeneralUtility::mkdir_deep($directory);
        file_put_contents($directory . '/' . $fileName, $content);
    }

    protected function sanitizeFileName(string $fileName): string
    {
        $localDriver = new LocalDriver();
        try {
            return $localDriver->sanitizeFileName($fileName);
        } catch (InvalidFileNameException $e) {
            return '';
        }
    }
}