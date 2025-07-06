<?php

namespace WDV\WdvCustomer\Routing\Enhancer;

use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\Routing\Enhancer\PageTypeDecorator;
use TYPO3\CMS\Core\Routing\RouteCollection;

/**
 * Class CustomPageTypeDecorator
 */
class CustomPageTypeDecorator extends PageTypeDecorator
{
    /**
     * @var string[]
     */
    final public const IGNORE_INDEX = [
        '/index.html',
        '/index/',
    ];

    /**
     * @var string[]
     */
    final public const ROUTE_PATH_DELIMITERS = ['.', '-', '_', '/'];

    /**
     * @param RouteCollection $collection
     * @param array $parameters
     */
    public function decorateForGeneration(RouteCollection $collection, array $parameters): void
    {
        parent::decorateForGeneration($collection, $parameters);

        /**
         * @var string $routeName
         * @var Route $route
         */
        foreach ($collection->all() as $routeName => $route) {
            $path = $route->getPath();

            if (\in_array($path, self::IGNORE_INDEX, true)) {
                $route->setPath('/');
            }
        }
    }
}