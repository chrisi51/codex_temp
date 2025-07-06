<?php

namespace WDV\WdvCustomer\ViewHelpers\Format;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2023 WACON Internet GmbH, https://www.wacon.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Class UrlencodeViewHelper
 * @package WDV\WdvCustomer\ViewHelpers
 */
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class UrlencodeViewHelper extends AbstractViewHelper
{
	/**
  * Initialize arguments.
  *
  * @throws Exception
  */
 public function initializeArguments(): void
	{
		parent::initializeArguments();
		$this->registerArgument('value', 'string', 'String to be urlencoded()', '', false);
	}

	/**
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return string
	 */
	public static function renderStatic(
		array $arguments,
		\Closure $renderChildrenClosure,
		RenderingContextInterface $renderingContext
	) {
		$value = $arguments['value'] ?: $renderChildrenClosure();

		return str_replace("+", "%20", \urlencode((string) $value));
	}
}
