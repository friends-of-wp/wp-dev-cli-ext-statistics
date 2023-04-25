<?php

namespace FriendsOfWp\WordPressStatsDevCliExtension\Command;

use FriendsOfWp\WordPressStatsDevCliExtension\WordPressEndpoints;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MySQLStatisticsCommand extends StatisticsCommand
{
    protected static $defaultName = 'wordpress:statistics:mysql';
    protected static $defaultDescription = 'Show the WordPress usage statistics based on the MySQL version.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->writeInfo($output, 'Show the share of MySQL Versions in the WordPress universe.');

        $versions = $this->requestData(WordPressEndpoints::MYSQL_VERSION);

        $tableRows = [];

        foreach ($versions as $version => $share) {
            $tableRows[] = [$version, $this->formatShare($share)];
        }

        $this->renderTable($output, ["MySQL\nVersion", 'Share'], $tableRows);

        return SymfonyCommand::SUCCESS;
    }
}
