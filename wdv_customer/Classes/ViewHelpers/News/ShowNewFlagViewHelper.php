<?php

declare(strict_types=1);

namespace WDV\WdvCustomer\ViewHelpers\News;

use GeorgRinger\News\Domain\Model\News;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class ShowNewFlagViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('settings', 'array', 'TypoScript Settings', true);
        $this->registerArgument('newsItem', News::class, 'NewsItem record', true);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext)
    {
        $settings = $arguments['settings'];
        if (!isset($settings['showNewFlag'])) {
            return false;
        }

        if (!$settings['showNewFlag']) {
            return false;
        }

        /** @var News $newsItem */
        $newsItem = $arguments['newsItem'];

        $now = new \DateTime();
        $newsItemDate = $newsItem->getDatetime();
        $interval = $now->diff($newsItemDate);

        $showNewFlagForDays = $settings['showNewFlagForDays'];
        $daysDiff = $interval->days;
        return $newsItemDate < $now && $daysDiff < $showNewFlagForDays;
    }
}
