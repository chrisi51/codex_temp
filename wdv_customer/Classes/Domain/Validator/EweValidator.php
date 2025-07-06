<?php

namespace WDV\WdvCustomer\Domain\Validator;

use In2code\Powermail\Domain\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Error\Error;

/**
 * Class EweValidator
 *
 * @package WDV\WdvCustomer\Validator
 */
class EweValidator extends AbstractValidator
{

    public array $flexForm;

    /**
     * Validator configuration
     *
     * @var array
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
        $ewe = '';
        $errorText = 'Bitte füllen Sie alle Felder aus. Wenn Sie der Einwilligungserklärung zustimmen, benötigen wir Ihre volle Anschrift, sowie Ihr Geburtsdatum.';
        $errorTextMissing = 'Bitte füllen Sie dieses Feld aus.';

        $flexformSettings = $this->flexForm['settings']['flexform']['main'];

        if ($flexformSettings['ewecheck']) {
            foreach ($mail->getAnswers() as $answer) {

                if ($answer->getField()->getMarker() === 'ewe') $ewe = $answer->getValue();

                if ($answer->getField()->getMarker() === 'anrede') $anrede = $answer->getValue();

                if ($answer->getField()->getMarker() === 'vorname') $vorname = $answer->getValue();

                if ($answer->getField()->getMarker() === 'nachname') $nachname = $answer->getValue();

                if ($answer->getField()->getMarker() === 'strasse') $strasse = $answer->getValue();

                if ($answer->getField()->getMarker() === 'hausnummer') $hausnummer = $answer->getValue();

                if ($answer->getField()->getMarker() === 'plz') $plz = $answer->getValue();

                if ($answer->getField()->getMarker() === 'ort') $ort = $answer->getValue();

                if ($answer->getField()->getMarker() === 'mobilfunknummer') $mobilfunknummer = $answer->getValue();

                if (stristr((string)$answer->getField()->getMarker(), 'geburtsdatum')) $gebdatum = $answer->getValue();
            }

            if ($ewe) {
                // Main error message first
                if (empty($anrede) ||
                    empty($vorname) ||
                    empty($nachname) ||
                    empty($strasse) ||
                    empty($hausnummer) ||
                    empty($plz) ||
                    empty($ort) ||
                    empty($mobilfunknummer) ||
                    empty($gebdatum)) $result->addError(new Error($errorText, 0, ['marker' => 'ewe']));

                // single field error messages after
                if (empty($anrede)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'anrede']));

                if (empty($vorname)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'vorname']));

                if (empty($nachname)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'nachname']));

                if (empty($strasse)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'strasse']));

                if (empty($hausnummer)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'hausnummer']));

                if (empty($plz)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'plz']));

                if (empty($mobilfunknummer)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'mobilfunknummer']));

                if (empty($ort)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'ort']));

                if (empty($gebdatum)) $result->addError(new Error($errorTextMissing, 0, ['marker' => 'geburtsdatum']));
            }
        }

        return $result;
    }

    protected function isValid(mixed $value): void
    {
        $this->isValid($value);
    }
}
