<?php

namespace WDV\WdvCustomer\Domain\Validator;

use In2code\Powermail\Domain\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Error\Error;

/**
 * Class PrivacyNoticeValidator
 *
 * @package WDV\WdvCustomer\Validator
 */
class PrivacyNoticeValidator extends AbstractValidator
{
    public array $flexForm;

    /**
     * Validator configuration
     */
    protected array $configuration = [];

    /**
     * Check
     */
    public function validate(mixed $mail): Result
    {
        $result = new Result();
        $privacyNotice = '';
        $errorText = 'Bitte willigen Sie in die Verarbeitung der Daten ein, indem Sie bestätigen, dass Sie die Datenschutzhinweise gelesen haben.';

        $flexformSettings = $this->flexForm['settings']['flexform']['main'] ?? [];
        if ($flexformSettings['datenschutzcheck'] ?? false) {
            foreach ($mail->getAnswers() as $answer) {
                if ($answer->getField()->getMarker() === 'datenschutz') $ewe = $answer->getValue();
            }

            if ($privacyNotice !== '') {
                $result->addError(new Error($errorText, 0, ['marker' => 'datenschutz']));
            }
        }

        return $result;
    }

    protected function isValid(mixed $value): void
    {
        $this->isValid($value);
    }
}