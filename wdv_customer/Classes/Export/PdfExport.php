<?php
namespace WDV\WdvCustomer\Export;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class PdfExport extends AbstractExport
{
    public function generatePdfContent(array $extractedData): string
    {
        $templateVariables = $this->mappingFormData($extractedData);

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $templatePath = GeneralUtility::getFileAbsFileName( 'EXT:wdv_customer/Resources/Private/Templates/Pdf/Ewe.html');
        $view->setTemplatePathAndFilename($templatePath);
        if (!file_exists($templatePath)) {
            throw new \RuntimeException('Template-Datei konnte nicht gefunden werden: ' . $templatePath, 1730503617);
        }
        $view->assignMultiple($templateVariables);
        return $view->render();
    }
}
