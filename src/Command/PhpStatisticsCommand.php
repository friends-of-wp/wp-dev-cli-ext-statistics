<?php

namespace FriendsOfWp\WordPressStatsDevCliExtension\Command;

use FriendsOfWp\WordPressStatsDevCliExtension\WordPressEndpoints;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PhpStatisticsCommand extends StatisticsCommand
{
    private array $versionSupport = [
        "5.2" => [
            "activeSupport" => "2011-01-06",
            "securitySupport" => "2011-01-06"
        ],
        "5.3" => [
            "activeSupport" => "2014-08-14",
            "securitySupport" => "2014-08-14"
        ],
        "5.4" => [
            "activeSupport" => "2015-11-03",
            "securitySupport" => "2015-11-03"
        ],
        "5.5" => [
            "activeSupport" => "2016-07-21",
            "securitySupport" => "2016-07-21"
        ],
        "5.6" => [
            "activeSupport" => "2018-11-31",
            "securitySupport" => "2018-11-31"
        ],
        "7.0" => [
            "activeSupport" => "2019-01-10",
            "securitySupport" => "2019-01-10"
        ],
        "7.1" => [
            "activeSupport" => "2019-12-01",
            "securitySupport" => "2019-12-01"
        ],
        "7.2" => [
            "activeSupport" => "2020-11-30",
            "securitySupport" => "2020-11-30"
        ],
        "7.3" => [
            "activeSupport" => "2021-12-06",
            "securitySupport" => "2021-12-06"
        ],
        "7.4" => [
            "activeSupport" => "2022-11-28",
            "securitySupport" => "2022-11-28"
        ],
        "8.0" => [
            "activeSupport" => "2022-11-26",
            "securitySupport" => "2023-11-26"
        ],
        "8.1" => [
            "activeSupport" => "2023-11-25",
            "securitySupport" => "2024-11-25"
        ],
        "8.2" => [
            "activeSupport" => "2024-12-08",
            "securitySupport" => "2025-12-08"
        ]
    ];

    protected static $defaultName = 'wordpress:statistics:php';
    protected static $defaultDescription = 'Show the WordPress usage statistics based on the PHP version.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->writeInfo($output, 'Show the share of PHP Versions in the WordPress universe.                 ');

        $versions = $this->requestData(WordPressEndpoints::PHP_VERSION);

        $totalShare = 100;
        $tableRows = [];
        $versionToUse = false;

        foreach ($versions as $version => $share) {
            $versionString = $version;
            if (array_key_exists($version, $this->versionSupport)) {
                $activeSupport = $this->versionSupport[$version]['activeSupport'];
                $securitySupport = $this->versionSupport[$version]['securitySupport'];
                if ($securitySupport < date('Y-m-d')) {
                    $versionString = '<fg=red>' . $version . '</>';
                }
            } else {
                $activeSupport = "";
                $securitySupport = "";
            }

            $tableRows[] = [
                $versionString,
                $this->formatShare($share),
                $this->formatShare($totalShare),
                $activeSupport,
                $securitySupport
            ];

            $totalShare -= $share;
            if ($totalShare < 50 && !$versionToUse) {
                $versionToUse = $version;
            }
        }

        $this->renderTable($output, ['PHP Version', 'Share', 'Total Share', 'Active Support', 'Security Support'], $tableRows);

        $this->writeInfo($output, 'A plugin with more than 50 % market share must to compatible with PHP ' . $versionToUse . '.');

        return SymfonyCommand::SUCCESS;
    }
}
