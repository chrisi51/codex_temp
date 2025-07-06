<?php

namespace WDV\WdvCustomer\DataProcessing;

use In2code\Powermail\DataProcessor\AbstractDataProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * Class DurchstarterWorkshopDataProcessor
 *
 * @package WDV\WdvCustomer\DataProcessing
 */
class DurchstarterWorkshopDataProcessor extends AbstractDataProcessor
{

    /**
     * Check user form data against former winners
     * and set own flag for current dataset in powermail db for this form.
     */
    public function durchstarterWorkshopDataProcessor(): bool
    {

        $flexformSettings = $this->getSettings();
        $fieldArray = [];

        if (isset($flexformSettings['main']['durchstarterworkshopcheck'])) {

            // extract all form field data
            foreach ($this->getMail()->getForm()->getFields() as $singleField) {

                $fieldArray[$singleField->getMarker()]['text'] = $singleField->getText();

                if (isset($this->getMail()->getAnswersByFieldMarker()[$singleField->getMarker()])) {

                    $fieldArray[$singleField->getMarker()]['value'] = $this->getMail()->getAnswersByFieldMarker()[$singleField->getMarker()]->getValue();
                } else {

                    $fieldArray[$singleField->getMarker()]['value'] = '';
                }
            }

            $duplicateEntry = $this->checkDuplicateEntry($fieldArray);

            if ($duplicateEntry) {

                $this->getMail()->getAnswersByFieldMarker()['schonteilgenommen']->setValue('Ja');
            }
        }

        return true;
    }

    public function checkDuplicateEntry($fieldArray): bool
    {
        // Check if user has already a record
        $birthdayExplode = explode('.', (string)$fieldArray['geburtsdatum']['value']);
        $birthday = $birthdayExplode[2] . '-' . $birthdayExplode[1] . '-' . $birthdayExplode[0];
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('winner_workshops_durchstarter');
        $count = $queryBuilder
            ->count('birthday')
            ->from('winner_workshops_durchstarter')
            ->where($queryBuilder->expr()->eq('firstname', $queryBuilder->createNamedParameter($fieldArray['vorname']['value'], \PDO::PARAM_STR)))
            ->andWhere($queryBuilder->expr()->eq('lastname', $queryBuilder->createNamedParameter($fieldArray['nachname']['value'], \PDO::PARAM_STR)))->andWhere($queryBuilder->expr()->eq('birthday', $queryBuilder->createNamedParameter($birthday, \PDO::PARAM_STR)))->executeQuery()
            ->fetchOne();

        if ($count) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param $fieldArray
     */
    public function insertInOldDb($fieldArray): void
    {

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('winner_workshops_durchstarter');

        $gender = ($fieldArray['anrede']['value'] == 'Herr') ? 1 : 0;
        $aokVersichertCheck = isset($fieldArray['aokversichert']) ? $fieldArray['aokversichert']['value'] : 0;

        $aokVersichert = match ($aokVersichertCheck) {
            'Ja' => 1,
            default => 0,
        };

        $ewe = isset($fieldArray['ewe']['value'][0]) && $fieldArray['ewe']['value'][0] == 'Ja' ? 1 : 0;

        $tba = isset($fieldArray['datenschutz']['value'][0]) && $fieldArray['datenschutz']['value'][0] == 'Ja' ? 1 : 0;

        $queryBuilder
            ->insert('winner_workshops_durchstarter')
            ->values([
                'gender' => $gender,
                'firstname' => $fieldArray['vorname']['value'],
                'lastname' => $fieldArray['nachname']['value'],
                'zip' => $fieldArray['plz']['value'],
                'city' => $fieldArray['ort']['value'],
                'street' => $fieldArray['strasse']['value'],
                'streetnr' => $fieldArray['hausnummer']['value'],
                'phone' => $fieldArray['telefon']['value'],
                'birthday' => $fieldArray['geburtsdatum']['value'],
                'email' => $fieldArray['email']['value'],
                'krankenkasse' => $fieldArray['versichertbei']['value'],
                'aokmember' => $aokVersichert,
                'already_wins' => 0,
                'workshop' => $fieldArray['workshoport']['value'],
                'description' => $fieldArray['statement']['value'],
                'aok' => null,
                'ewe_text' => strip_tags((string)$fieldArray['datennutzungstext']['text']),
                'ewe' => $ewe,
                'ewe_status' => 'success',
                'tba_text' => strip_tags((string)$fieldArray['datenschutzhinweis']['text']),
                'tba' => $tba,
                'visible' => 0,
                'aok_kommentar' => '',
                'cparam' => '',
                'token' => '',
                'timestamp' => date("Y-m-d H:i:s"),
            ]);
        debug($queryBuilder->getSQL());
        $statement = $queryBuilder->executeQuery();
    }
}