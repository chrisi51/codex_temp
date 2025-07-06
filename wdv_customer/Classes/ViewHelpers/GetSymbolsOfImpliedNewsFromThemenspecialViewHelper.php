<?php

namespace WDV\WdvCustomer\ViewHelpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2019 Christopher Wirsing, https://www.cwms.de
 *  (c) 2019 Christopher Wirsing, https://www.wdv.de
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
 * Class GetSymbolsOfImpliedNewsFromThemenspecialViewHelper
 * @package WDV\WdvCustomer\ViewHelpers
 */
use WDV\WdvCustomer\Domain\Model\NewsThemenSpecial;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class GetSymbolsOfImpliedNewsFromThemenspecialViewHelper extends AbstractViewHelper
{

	/**
  * Initialize arguments.
  *
  * @throws Exception
  */
 public function initializeArguments(): void
	{
		parent::initializeArguments();
		$this->registerArgument('news', NewsThemenSpecial::class, 'news item');
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
		$news = $arguments['news'];

		$return = [];

		if($news) {

			foreach($news->getTxWdvcustomerThemenspecialImpliedNews() as $impliedNews) {

				$newsType = $impliedNews->getType();

				// Overwrite icon in multipage context
				if ($impliedNews->getTxWdvcustomerNewsMultipagetype() != 0) {
					$newsType = $impliedNews->getTxWdvcustomerNewsMultipagetype();
				}

				switch ($newsType) {

					// Nachrichten
					case '0':

						$cssClass = 'article';
						$typeTitle = 'Artikel';

						break;

					// Interview
					case '4':

						$cssClass = 'interview';
						$typeTitle = 'Interview';

						break;

					// Frage der Woche
					case '5':

						$cssClass = 'question';
						$typeTitle = 'Frage der Woche';

						break;

					// Video
					case '6':

						$cssClass = 'video';
						$typeTitle = 'Video';

						break;

					// Rezept
					case '7':

						$cssClass = 'recipe';
						$typeTitle = 'Rezept';

						break;

					// Gewinnspiel
					case '10':

						$cssClass = 'gewinnspiel';
						$typeTitle = 'Gewinnspiel';

						break;

					// Gewinnspiel
					case '13':

						$cssClass = 'podcast';
						$typeTitle = 'Podcast';

						break;

					// Default / Fallback
					default:

						$cssClass = 'article';
						$typeTitle = 'Artikel';

						break;
				}

				$return[] = ['cssClass' => $cssClass, 'typeTitle' => $typeTitle];
			}
		}

		// $return = array_unique($symbols, SORT_STRING);
//         DebuggerUtility::var_dump(array_unique($return, SORT_REGULAR));
		$result = array_unique($return, SORT_REGULAR);

		return $result;


	}
}