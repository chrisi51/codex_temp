<?php
declare(strict_types=1);
namespace WX\WxVideoListing\Command;

use Doctrine\DBAL\Exception;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Routing\PageRouter;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class VideoListingCommand extends Command
{
    protected static $defaultName = 'video:listing';

	// Build arrays for CSV output and console output.
	protected array $csvRows = [];
	protected array $outputRows = [];

	protected SiteFinder $siteFinder;

    protected function configure(): void
    {
        $this->setDescription(
            'Lists all videos (e.g. MP4, MPEG, MOV, youtube, vimeo) and their correct frontend URLs.
            For news articles, the detail page is determined via categories and/or news plugin configuration.
            Then the article slug (path_segment) is used as a URL suffix.
            Also, the language (sys_language_uid) is taken into account.'
        )
        ->addOption(
            'exportCsv',
            null,
            InputOption::VALUE_NONE,
            'Exports the output to a CSV file'
        )
        ->addOption(
            'csvFile',
            null,
            InputOption::VALUE_REQUIRED,
            'Path to the CSV output file',
            'video_listing.csv'
        );
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        // Get the site finder instance for later use.
        $this->siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

		// CSV Header
        $this->csvRows[] = ['Pagetype', 'Pagetree ID', 'News ID', 'Element ID', 'Language', 'Video Player', 'Filename', 'Filepath', 'Filelink', 'Website'];

        // Find media elements (video files attached to tt_content via sys_file_reference).
        $findmedia = $this->findMedia();
        // Find video playlists in tt_content (mask_playlist__abspielelemente)
        $findMaskPlaylist = $this->findMaskPlaylist();

        // sorting records with normal video ressources
        $records = [...$findmedia, ...$findMaskPlaylist];
        $sort_col = array_column($records, 'identifier');
        array_multisort($sort_col, SORT_ASC, $records);

        // Find Kaltura players in tt_content (bodytext contains "kaltura_player")
        $findKalturaPlayer = $this->findKalturaPlayer();

        // Appending Kaltura Records to the records list, as they got little other structure and multisort is not able to handle it
        $records = [...$records, ...$findKalturaPlayer];

       $this->processRecords($records);

		// Output to console
        foreach ($this->outputRows as $line) {
            $output->writeln($line);
        }

        // If CSV export is enabled, write CSV data to file.
        if ($input->getOption('exportCsv')) {
            $csvFile = $input->getOption('csvFile');
            if (false === ($fh = fopen($csvFile, 'w'))) {
                $output->writeln('Error: CSV file could not be opened.');
                return Command::FAILURE;
            }
            foreach ($this->csvRows as $csvRow) {
                // Use semicolon delimiter and double quotes as enclosures.
                fputcsv($fh, $csvRow, ';', '"');
            }
            fclose($fh);
            $output->writeln("CSV exported to: " . $csvFile);
        }

        return Command::SUCCESS;
    }


    /**
     * Finds references of given elements in tt_content shortcuts.
     *
     * @param array $records The records.
     * @return array The media records array.
     * @throws Exception
     */
    protected function findReferences(array $records): array
    {
        $newRecords = [];
        foreach ($records as $record) {

            $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
            // Select relevant fields from tt_content where bodytext contains "kaltura_player"
            $result = $qb->select('uid', 'pid', 'sys_language_uid', 'CType', 'bodytext', 'header', 'tx_news_related_news')
            ->from('tt_content')
            ->where(
                $qb->expr()->like('records', $qb->createNamedParameter('%tt_content_'.$record["uid"].'%', PDO::PARAM_STR))
            )
            ->andWhere(
                $qb->expr()->eq('deleted', $qb->createNamedParameter(0, PDO::PARAM_INT))
            )
            ->andWhere(
                $qb->expr()->eq('hidden', $qb->createNamedParameter(0, PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchAllAssociative();

            foreach($result as $newRecord){

                $record["uid"] = $newRecord["uid"];
                $record["pid"] = $newRecord["pid"];
                $record["tx_news_related_news"] = $newRecord["tx_news_related_news"];
                $record["sys_language_uid"] = $newRecord["sys_language_uid"];
                $record["is_reference"] = 1;

                $newRecords[] = $record;
            }
        }

        return array_merge($records, $newRecords);
    }

    /**
     * Finds media entries (video files) via sys_file_reference joined to tt_content.
     *
     * @return array The media records array.
     * @throws Exception
     */
    protected function findMedia(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference');
        $result = $queryBuilder
            ->select(
                'ttc.uid',
                'sf.identifier',
                'ttc.pid',
                'sf.name',
                'ttc.tx_news_related_news',
                'ttc.sys_language_uid',
            )
            ->addSelectLiteral('"TYPO3 Media" AS videotype') // Fügt den statischen Text als Literal hinzu
            ->from('sys_file_reference', 'sfr')
            ->join('sfr', 'sys_file', 'sf', 'sf.uid = sfr.uid_local')
            ->join('sfr', 'tt_content', 'ttc', 'ttc.uid = sfr.uid_foreign')
            ->where(
                $queryBuilder->expr()->eq('sfr.tablenames', $queryBuilder->createNamedParameter('tt_content'))
            )
            ->andWhere(
                $queryBuilder->expr()->eq('ttc.deleted', $queryBuilder->createNamedParameter(0))
            )
            ->andWhere(
                $queryBuilder->expr()->eq('ttc.hidden', $queryBuilder->createNamedParameter(0))
            )
            ->andWhere(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->like('sf.name', $queryBuilder->createNamedParameter('%.mp4', PDO::PARAM_STR)),
                    $queryBuilder->expr()->like('sf.name', $queryBuilder->createNamedParameter('%.mpeg', PDO::PARAM_STR)),
                    $queryBuilder->expr()->like('sf.name', $queryBuilder->createNamedParameter('%.mov', PDO::PARAM_STR)),
                    $queryBuilder->expr()->like('sf.name', $queryBuilder->createNamedParameter('%.youtube', PDO::PARAM_STR)),
                    $queryBuilder->expr()->like('sf.name', $queryBuilder->createNamedParameter('%.vimeo', PDO::PARAM_STR))
                )
            )
            ->executeQuery();
        return $this->findReferences($result->fetchAllAssociative());
    }

    /**
     * Finds tt_content elements whose bodytext contains the string "kaltura_player".
     * It returns an array of content element records.
     *
     * If the CType is "mask_kaltura_player", it is considered up-to-date.
     * Otherwise, the record will be flagged as needing an update.
     *
     * @return array The Kaltura player records.
     * @throws Exception
     */
    protected function findKalturaPlayer(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        // Select relevant fields from tt_content where bodytext contains "kaltura_player"
        $result = $qb->select('uid', 'pid', 'sys_language_uid', 'CType', 'bodytext', 'header', 'tx_news_related_news')
            ->addSelectLiteral('"Mask Kaltura Player" AS videotype') // Fügt den statischen Text als Literal hinzu
            ->from('tt_content')
            ->where(
                $qb->expr()->like('bodytext', $qb->createNamedParameter('%kaltura_player%', PDO::PARAM_STR))
            )
            ->andWhere(
                $qb->expr()->eq('deleted', $qb->createNamedParameter(0, PDO::PARAM_INT))
            )
            ->andWhere(
                $qb->expr()->eq('hidden', $qb->createNamedParameter(0, PDO::PARAM_INT))
            )
            ->executeQuery();

        return $this->findReferences($result->fetchAllAssociative());
    }

    /**
     * Finds mask playlist elements in tt_content and then extracts each video
     * from the corresponding tx_mask_medium records.
     *
     * For each mask_playlist__abspielelemente record (in tt_content), this function
     * queries tx_mask_medium where parenttable = 'tt_content' and parentid equals the
     * UID of the mask_playlist element. Then, for each found record it retrieves the
     * file reference (tx_mask_media) from sys_file and builds a media record for further processing.
     *
     * @return array The list of media records extracted from the mask playlists.
     * @throws Exception
     */
    protected function findMaskPlaylist(): array
    {
       $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference');

       $result = $queryBuilder
            ->select(
                'ttc.uid',
                'sf.identifier',
                'ttc.pid',
                'sf.name',
                'ttc.tx_news_related_news',
                'ttc.sys_language_uid'
            )
            ->addSelectLiteral('"Mask Playlist" AS videotype') // Fügt den statischen Text als Literal hinzu
            ->from('sys_file_reference', 'sfr')
            ->join('sfr', 'sys_file', 'sf', 'sf.uid = sfr.uid_local')
            ->join('sfr', 'tx_mask_medium', 'txmm', 'txmm.uid = sfr.uid_foreign')
            ->join('sfr', 'tt_content', 'ttc', 'ttc.uid = txmm.parentid')
            ->where(
                $queryBuilder->expr()->eq('sfr.tablenames', $queryBuilder->createNamedParameter('tx_mask_medium'))
            )
            ->andWhere(
                $queryBuilder->expr()->eq('ttc.deleted', $queryBuilder->createNamedParameter(0))
            )
            ->andWhere(
                $queryBuilder->expr()->eq('ttc.hidden', $queryBuilder->createNamedParameter(0))
            )
            ->executeQuery();
        return $this->findReferences($result->fetchAllAssociative());
    }


	/**
	 * Processes the found sources and creates output for terminal and csv
	 * It passes the value directly to the global arrays
	 *
	 * If the type is "mask_kaltura_player", it is considered up-to-date.
	 * Otherwise, the record will be flagged as needing an update.
	 *
	 * @param array $records The records.
	 * @return void
	 * @throws Exception
	 */
    protected function processRecords(array $records): void
	{
		foreach ($records as $record) {

            if (empty($record['tx_news_related_news'])){
                $news_id = "-";
                $pid =  (int)$record['pid'];
                $detailPageUid = $pid;
    			$type = 'Page';
            }else{
                $news = $this->getNewsDetails($record);
                $news_id = (int)$record['tx_news_related_news'];
                $pid =  $news["pid"];
                $detailPageUid =  $news["detailPageUid"];
                $type = empty($news["hidden"]) ? 'News Article' : 'News Article (Hidden)';
            }

            $uid = (int)$record['uid'];

			$langUid = (int)$record['sys_language_uid'];
            $languageStr = $this->getLanguageCode($langUid, $detailPageUid, $this->siteFinder);
            $link = $this->generateLink($record, $detailPageUid, $this->siteFinder);

            switch($record["videotype"]){
                case "Mask Kaltura Player":
					$filename = ($record['CType'] !== 'mask_kaltura_player') ? $record['CType'].' (Needs Update)' : '';
					$filepath = $record['bodytext'];
					$filelink = "n/a";
					break;

                case "Mask Playlist":
                    $filename = $record['name'] ?? 'n/a';
                    $filepath = $record['identifier'];
                    $filelink = "https://www.vigo.de/fileadmin" . $record['identifier'];
                    break;

                default:
                    $filename = $record['header'] ?? $record['name'] ?? 'n/a';
                    $filepath = $record['identifier'];
                    $filelink = "https://www.vigo.de/fileadmin" . $record['identifier'];
			}

            $this->outputRows[] = sprintf(
				'%s (%s/%s): %s (%s) -> %s',
                $type,
                $pid,
                $languageStr,
                $filename,
                $filepath,
                $link
            );
            $this->csvRows[] = [$type, $pid, $news_id, $uid, $languageStr, (empty($record["is_reference"]) ? $record["videotype"] : $record["videotype"].' (Reference)'), $filename, $filepath, $filelink, $link];
		}
	}


/**
     * Generates a URL for a media record that belongs to a news article.
     * Uses categories (and falls back to news plugin configuration) to determine the detail page,
     * then uses the site's router to generate a "speaking" URL including news detail parameters.
     *
     * @param array $record The media record.
     * @return array The news record.
     * @throws Exception
     */
    protected function getNewsDetails(array $record): array
    {
        $newsUid = (int)$record['tx_news_related_news'];
        $detailPageUid = 0;

        // Retrieve categories assigned via MM table
        $catQB = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category_record_mm');
        $mmRecords = $catQB
            ->select('uid_local')
            ->from('sys_category_record_mm')
            ->where(
                $catQB->expr()->eq('uid_foreign', $catQB->createNamedParameter($newsUid, PDO::PARAM_INT))
            )
            ->andWhere(
                $catQB->expr()->eq('tablenames', $catQB->createNamedParameter('tx_news_domain_model_news'))
            )
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($mmRecords as $mmRecord) {
            $catUid = (int)$mmRecord['uid_local'];
            $catQB2 = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('sys_category');
            $catRecord = $catQB2
                ->select('single_pid')
                ->from('sys_category')
                ->where(
                    $catQB2->expr()->eq('uid', $catQB2->createNamedParameter($catUid, PDO::PARAM_INT))
                )
                ->executeQuery()
                ->fetchAssociative();
            if ($catRecord && !empty($catRecord['single_pid'])) {
                $detailPageUid = (int)$catRecord['single_pid'];
                break;
            }
        }

        // Fallback: Get detail page from news plugin configuration if not found in any category
        $newsQB = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');
        $newsQB->getRestrictions()
            ->removeByType(HiddenRestriction::class);
        $newsRecord = $newsQB
            ->select('*')
            ->from('tx_news_domain_model_news')
            ->where(
                $newsQB->expr()->eq('uid', $newsQB->createNamedParameter($newsUid, PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($newsRecord) {
            if ($detailPageUid === 0) {
                $pluginDetailPid = $this->findNewsPluginDetailPid($newsRecord);
                if ($pluginDetailPid !== null) {
                    $detailPageUid = $pluginDetailPid;
                }
//                if ($detailPageUid === 0) {
//                    return "No detail page found (via category or plugin)";
//                }
            }
            $newsRecord["detailPageUid"] = $detailPageUid;
            return $newsRecord;
        }
        return ["detailPageUid" => $detailPageUid];
    }

	/**
	 * Generates the URL for a media record.
	 * If the record belongs to a news article (tx_news_related_news is set),
	 * generateNewsLink() is used, otherwise generatePageLink() is used.
	 *
	 * @param array $record The media record.
	 * @param int $pid
	 * @param SiteFinder $siteFinder
	 * @return string The generated URL.
	 */
    protected function generateLink(array $record, int $pid, SiteFinder $siteFinder): string
    {
        if (!empty($record['tx_news_related_news'])) {
            return $this->generateNewsLink($record, $pid, $siteFinder);
        }
        return $this->generatePageLink($record, $siteFinder);
    }

	/**
	 * Generates a URL for a media record that belongs to a news article.
	 * Uses categories (and falls back to news plugin configuration) to determine the detail page,
	 * then uses the site's router to generate a "speaking" URL including news detail parameters.
	 *
	 * @param array $record The media record.
	 * @param int $pid
	 * @param SiteFinder $siteFinder
	 * @return string The generated news link.
	 */
    protected function generateNewsLink(array $record, int $pid, SiteFinder $siteFinder): string
    {
        $newsUid = $record["tx_news_related_news"];
        // Generate the URL via the site router with news detail parameters.
        try {
            $site = $siteFinder->getSiteByPageId($pid);
            $additionalParams = [
                'tx_news_pi1' => [
                    'action'     => 'detail',
                    'controller' => 'News',
                    'news'       => $newsUid,
                ]
            ];
            $langUid = (int)$record['sys_language_uid'];
            if ($langUid > 0) {
                $additionalParams['_language'] = $langUid;
            }
            return (string)$site->getRouter()->generateUri((string)$pid, $additionalParams);
        } catch (\Exception $e) {
            return 'URL could not be generated (Detail page UID: ' . $pid . ')';
        }
    }

    /**
     * Generates a URL for a media record that is attached to a normal page.
     *
     * @param array $record The media record.
     * @param SiteFinder $siteFinder
     * @return string The generated page link.
     */
    protected function generatePageLink(array $record, SiteFinder $siteFinder): string
    {
        $pageUid = (int)$record['pid'];
        $langUid = (int)$record['sys_language_uid'];
        try {
            $site = $siteFinder->getSiteByPageId($pageUid);
            $additionalParams = [];
            if ($langUid > 0) {
                $additionalParams['_language'] = $langUid;
            }
            $pageRouter = GeneralUtility::makeInstance(PageRouter::class, $site);
            return (string)$pageRouter->generateUri($pageUid, $additionalParams);
        } catch (\Exception $e) {
            return 'URL could not be generated (Page UID: ' . $pageUid . ')';
        }
    }


    /**
     * Retrieves the 2-letter language code from the site configuration based on sys_language_uid.
     * If the site language is not found or fails, it returns the language UID as a string.
     *
     * @param int $langUid
     * @param int $pageId A record that might be used to determine the appropriate page ID.
     * @param SiteFinder $siteFinder
     * @return string
     */
    protected function getLanguageCode(int $langUid, int $pageId, SiteFinder $siteFinder): string
    {
        try {
            $site = $siteFinder->getSiteByPageId($pageId);
            $siteLanguage = $site->getLanguageById($langUid);
            return $siteLanguage->getLocale()->getLanguageCode();
        } catch (\Exception $e) {
            return (string)$langUid;
        }
    }

    /**
     * Searches for a news plugin (CType = news_pi1) in tt_content that covers the news record.
     * It extracts FlexForm data to determine settings.startingpoint and settings.detailPid.
     * Recursively, it checks whether the news record's sysfolder (or one of its parents) is contained in the starting points.
     *
     * @param array $newsRecord
     * @return int|null Returns the detail PID if found, or null otherwise.
     * @throws Exception
     */
    protected function findNewsPluginDetailPid(array $newsRecord): ?int
    {
        $newsFolder = (int)$newsRecord['pid'];
        $rootlineUtility = GeneralUtility::makeInstance(RootlineUtility::class, $newsFolder);
        $rootline = $rootlineUtility->get();
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        $plugins = $qb
            ->select('uid', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $qb->expr()->eq('CType', $qb->createNamedParameter('news_pi1'))
            )
            ->andWhere(
                $qb->expr()->eq('deleted', $qb->createNamedParameter(0, PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchAllAssociative();
        if (empty($plugins)) {
            return null;
        }
        foreach ($plugins as $plugin) {
            $flexArray = GeneralUtility::xml2array($plugin['pi_flexform']);
            if (!is_array($flexArray)) {
                continue;
            }
            $startingpoint = $flexArray['data']['sDEF']['lDEF']['settings.startingpoint']['vDEF'] ?? '';
            $detailPid = $flexArray['data']["additional"]['lDEF']['settings.detailPid']['vDEF'] ?? '';
            if (empty($startingpoint) || empty($detailPid)) {
                continue;
            }
            $startingpoints = GeneralUtility::trimExplode(',', $startingpoint, true);
            foreach ($rootline as $pageRecord) {
                if (in_array((int)$pageRecord['uid'], $startingpoints)) {
                    return (int)$detailPid;
                }
            }
        }
        return null;
    }
}
