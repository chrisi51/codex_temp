<?php

namespace WDV\WdvCustomer\Export;

class XmlExport extends AbstractExport
{
    /**
     * Generiert den XML-String basierend auf den extrahierten Daten.
     *
     * @param array $extractedData
     * @return string
     */
    public function generateXmlContent(array $extractedData): string
    {
        $formData = $this->mappingFormData($extractedData);
        $xml = new \SimpleXMLElement('<data></data>');

        // Fülle die Hauptdaten
        foreach ($formData as $key => $value) {
            if ($key !== 'TAB_EWE') {
                $xml->addChild($key, htmlspecialchars((string)$value));
            }
        }

        // Füge den TAB_EWE Knoten mit festen Werten hinzu
        $tabEwe = $xml->addChild('TAB_EWE');
        $this->addFixedTabEweNodes($tabEwe, $extractedData);

        // Wir wollen die XML sauber formatieren
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;

        return $dom->saveXML();
    }

    protected function mappingFormData(array $extractedData): array
    {
        $sex = match ($extractedData['anrede']) {
            "Herr", "m", "male" => 'Männlich',
            "Frau", "f", "female" => 'Weiblich',
            "Divers", "d", "diverse" => 'Divers',
            default => 'Unbekannt',
        };

        $anrede = match ($extractedData['anrede']) {
            "Herr", "m", "male" => '0001',
            "Frau", "f", "female" => '0002',
            default => '0099',
        };

        $formData = [
            'EINGANGSKANAL' => 94,
            'FORMULAR' => '1ALLGEMEIN',
            'GENERATE_DOCUMENT' => 'X',
            'SEX' => $sex,
            'ANREDE' => $anrede,
            'NAME_FIRST' => $extractedData['vorname'],
            'NAME_LAST' => $extractedData['nachname'],
            'STREET' => $extractedData['strasse'],
            'HOUSE_NO' => $extractedData['hausnummer'],
            'CITY1' => $extractedData['ort'],
            'POSTL_COD1' => $extractedData['plz'],
            'COUNTRY' => '',
            'BIRTHDATE' => $extractedData['tag'] . '.' . $extractedData['monat'] . '.' . $extractedData['jahr'],
            'FKKNR' => '',
            'FKVON' => '',
            'FKBIS' => '',
            'TELEPHONE' => $extractedData['phone'] ?? '-',
            'MOBIL' => $extractedData['mobile'] ?? '-',
            'E_MAIL' => $extractedData['email'] ?? '-',
            'AOK-VERSICHERT' => '',
            'VERSICHERTENNUMMER' => '',
            'DOKUMENTKLASSE' => 'RM_ISS_ALL',
            'KASSENINSTITUTIONSKENNZEICHEN' => '104212505',
            'ADRQUELLE' => 60,
            'ADRQSPEZ' => '0003',
            'ADRLIEF' => '2800001787',
            'PURPOSE' => '',
        ];

        if ($extractedData['aokversichert'] == 'Ja') {
            $formData['AOK-VERSICHERT'] = 'Ja';
            $formData['VERSICHERTENNUMMER'] = $extractedData['versichertennummer'];
        }

        return $formData;
    }

    /**
     * Fügt die festen STR_EWE Knoten zum TAB_EWE Knoten hinzu.
     *
     * @param \SimpleXMLElement $tabEwe
     * @param array $extractedData
     * @return void
     */
    protected function addFixedTabEweNodes(\SimpleXMLElement $tabEwe, array $extractedData): void
    {
        $fixedStrEweEntries = [
            [
                'CHANNEL' => 'ZSMS',
                'PURPOSE' => 'ZMEFO',
                'EWE_TEXT_NAME' => 'ZZRMEWE',
                'PERMISSION' => 'G',
                'VALID_FROM' => '',
            ],
            [
                'CHANNEL' => 'ZSMS',
                'PURPOSE' => 'ZVERTRIEB',
                'EWE_TEXT_NAME' => 'ZZRMEWE',
                'PERMISSION' => 'G',
                'VALID_FROM' => '',
            ],
            [
                'CHANNEL' => 'ZSMS',
                'PURPOSE' => 'ZZUSA',
                'EWE_TEXT_NAME' => 'ZZRMEWE',
                'PERMISSION' => 'G',
                'VALID_FROM' => '',
            ],
        ];

        foreach ($fixedStrEweEntries as $strEweData) {
            $strEwe = $tabEwe->addChild('STR_EWE');
            foreach ($strEweData as $subKey => $subValue) {
                $strEwe->addChild($subKey, htmlspecialchars((string)$subValue));
            }
        }
    }
}
