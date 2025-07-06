<?php

namespace WDV\WdvCustomer\Finisher;

use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermail\Finisher\AbstractFinisher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Core\Database\Connection;

/**
 * Class DoSomethingFinisher
 *
 * @package Vendor\Ext\Finisher
 */
class CheckEventRegistrationsFinisher extends AbstractFinisher
{
    public array $configuration;

    /**
     * MyFinisher
     *
     * @return void
     */
    public function myFinisher(): void
    {
        // get page UID
        $page_uid = (int) $GLOBALS['TSFE']->id;

        // get typoscript configuration
        $conf = $this->configuration[$page_uid] ?? [];
        if (isset($conf["field_uid"])) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable("tx_powermail_domain_model_answer");
            $recordList = $queryBuilder
                ->Select("value")
                ->AddSelectLiteral("COUNT(*) AS " . $queryBuilder->quoteIdentifier("count"))
                ->from("tx_powermail_domain_model_answer")
                ->where(
                    $queryBuilder->expr()->eq(
                        "pid",
                        $queryBuilder->createNamedParameter($page_uid, Connection::PARAM_INT)
                    ),
                    $queryBuilder->expr()->eq(
                        "field",
                        $queryBuilder->createNamedParameter($conf["field_uid"], Connection::PARAM_INT)
                    )
                )
                ->groupBy("value")
                ->executeQuery()
                ->fetchAllAssociative();
            $location = $this->getMail()->getAnswersByFieldMarker()['terminauswahl']->getValue();
            $locations = [];
            $message = "";
            foreach ((array)$recordList as $record) {
                $seats = (int) $conf["events"][$record["value"]];
                if ($seats > 0) {
                    if ($record["count"] > $seats) {
                        $message .= $record["value"] . " ist bereits um " . ($record["count"] - $seats) . " Plätze überfüllt (" . $record["count"] . "/" . $seats . " => " . number_format(($record["count"] / $seats * 100) - 100, 0) . "% überbucht\r\n";
                        $locations[] = $record["value"];
                    } elseif ($record["count"] == $seats) {
                        $message .= $record["value"] . " ist ab sofort voll (" . $record["count"] . "/" . $seats . ")\r\n";
                        $locations[] = $record["value"];
                    } elseif ($record["count"] > ($seats - 5)) {
                        $message .= $record["value"] . " hat nur noch " . ($seats - $record["count"]) . " freie Plätze (" . $record["count"] . "/" . $seats . ")\r\n";
                        $locations[] = $record["value"];
                    }
                }
            }

            if ($message !== '' && in_array($location, $locations)) {
                $mail = GeneralUtility::makeInstance(MailMessage::class);
                $mail->setSubject("Eventbenachrichtigung für UID " . $page_uid);
                $mail->setFrom(MailUtility::getSystemFrom());
                $mail->setTo($conf["receivers"]);
                $mail->text($message);
                $mail->send();
            }
        }
    }
}
