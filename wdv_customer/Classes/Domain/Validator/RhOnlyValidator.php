<?php

namespace WDV\WdvCustomer\Domain\Validator;

use In2code\Powermail\Domain\Validator\AbstractValidator;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Error\Error;

/**
 * Class MinAgeValidator
 *
 * @package WDV\WdvCustomer\Validator
 */
class RhOnlyValidator extends AbstractValidator
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
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('wdv_customer');
        $rh_customer_only = $this->flexForm['settings']['flexform']['main']['rh_customer_only'] ?? 0;

        if ($rh_customer_only) {

            $errorText = $this->flexForm['settings']['flexform']['main']['rh_customer_only_text'] ?? "Sie müssen im Einzugsgebiet der AOK RH wohnen, um teilnehmen zu können.";
            $errorTextMissing = 'Bitte füllen Sie dieses Feld aus.';

            $plz = $mail->getAnswersByFieldMarker()['plz']->getValue();
            if (empty($plz)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'plz']));
            else {

#                $test_plz ="20144 20146 20148 20149 20249 20251 20253 20255 20257 20259 20354 20355 20357 20359 20457 20459 20535";
                $test_plz = $extensionConfiguration["rh_customer_only_plz"];

                if (! str_contains($test_plz, $plz)) {
                    $result->addError(new Error($errorText, 0, ['marker' => 'plz']));
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
