<?php

namespace WDV\WdvCustomer\Domain\Validator;

use In2code\Powermail\Domain\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Error\Error;

/**
 * Class MinAgeValidator
 *
 * @package WDV\WdvCustomer\Validator
 */
class MinAgeValidator extends AbstractValidator
{
    public array $flexForm;

    /**
     * Validator configuration
     */
    protected array $configuration = [];

    /**
     * Check if value in Firstname-Field is allowed
     */
    public function validate(mixed $mail): Result
    {
        $result = new Result();
        $minAge = $this->flexForm['settings']['flexform']['main']['minage'] ?? 0;

        if ($minAge > 0) {

            $errorText = 'Sie müssen mindestens ' . $minAge . ' Jahre alt sein!';
            $errorTextMissing = 'Bitte füllen Sie dieses Feld aus.';

            $birthday = $mail->getAnswersByFieldMarker()['geburtsdatum']->getValue();
            if (empty($birthday)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'geburtsdatum']));
            else {
                $birthday = explode(".", (string)$birthday);
                //get age from date or birthday
                $age = (date("md", date("U", mktime(0, 0, 0, $birthday[1], $birthday[0], $birthday[2]))) > date("md") ? ((date("Y") - $birthday[2]) - 1) : (date("Y") - $birthday[2]));

                if ($age < $minAge) {
                    $result->addError(new Error($errorText, 0, ['marker' => 'geburtsdatum']));
                }
            }
        }

        return $result;
    }

    protected function isValid(mixed $value): void
    {
        $this->isValid($value);
    }
}
