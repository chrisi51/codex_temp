<?php

namespace WDV\WdvCustomer\ViewHelpers\Powermail;

use In2code\Powermail\Domain\Model\Field;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class PrepareFormFieldArraysViewHelper
 */
class PrepareFormFieldArraysViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('fields', 'object', 'fields Object', true);
    }

    public function render(): string
    {
        $fields = $this->arguments['fields'];

        $lastFieldType = '';
        $returnArray = [];
        $arrayCounter = 0;
        $i = 1;

        foreach ($fields as $key => $field) {
            $currentRowSet = $this->getFieldInfo($field);
            $currentFieldType = $currentRowSet != '' ? $currentRowSet : 'items_2';

            if ($currentFieldType != $lastFieldType) {
                ++$arrayCounter;
                $returnArray[$arrayCounter]['rowItems'] = (int)substr($currentFieldType, -1);
            }

            $returnArray[$arrayCounter]['fields'][$key] = $field;

            $lastFieldType = $currentFieldType;
            ++$i;
        }

        $this->templateVariableContainer->add('returnArray', $returnArray);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove('returnArray');

        return $output;
    }

    protected function getFieldInfo(Field $field): string
    {
        return $field->getTxWdvcustomerPowermailRowCols();
    }
}
