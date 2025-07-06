<?php

namespace WDV\WdvCustomer\Domain\Validator;

use WDV\WdvCustomer\Service\KvSoapService;
use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermail\Domain\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Error\Error;

/**
 * Class KvValidator
 *
 * @package WDV\WdvCustomer\Validator
 */
class KvValidator extends AbstractValidator
{
    public array $flexForm;

    /**
     * Field to check - select by {kassennummer}
     */
    protected string $kassennummer = 'kassennummer';

    /**
     * Field to check - select by {versichertennummer}
     */
    protected string $versichertennummer = 'versichertennummer';

    /**
     * Validator configuration
     */
    protected array $configuration = [];

    /**
     * Check if value in Firstname-Field is allowed
     *
     * @param mixed $mail
     * @return Result
     */
    public function validate(mixed $mail): Result
    {
        $result = new Result();
        $userKnr = '';
        $userVnr = '';
        $errorText = 'Bitte geben Sie Ihre Versicherten- und Kassennummer ein. 
                Um eine Bestellung aufgeben zu können, müssen Sie Versicherte/r der AOK Rheinland/Hamburg sein.';

        $flexformSettings = $this->flexForm['settings']['flexform']['main'];

        // if plugin settings contain the kvcheck flag perform validation of kassen and versicherungsnummer
        // against t-system webservice
        if ($flexformSettings['kvcheck']) {
            foreach ($mail->getAnswers() as $answer) {
                if ($answer->getField()->getMarker() === $this->kassennummer) $userKnr = $answer->getValue();
                if ($answer->getField()->getMarker() === $this->versichertennummer) $userVnr = $answer->getValue();
            }

            $webserviceCheck = $this->checkKvNumbers($userKnr, $userVnr);
            if (!$webserviceCheck) {
                $result->addError(new Error($errorText, 0, ['marker' => $this->versichertennummer]));
            }

        }

        return $result;
    }


    protected function isValid(mixed $value): void
    {
        $this->isValid($value);
    }

    /**
     * Check if kassennummer and versichertennummer are valid using t-systems webservice
     * Only if AOK RH Knr is used
     *
     * @param $userKnr
     * @param $userVnr
     * @return bool
     */
    protected function checkKvNumbers($userKnr, $userVnr): bool
    {
        if ($userKnr == "104212505" || $userKnr == "101519213") {
            $validateModel = new KvSoapService(null, null);

            $check = $validateModel->validateInput($userKnr, $userVnr);
            if (false === $check) {
                return false;
            } elseif (is_numeric($check) && $check < 0) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }
}