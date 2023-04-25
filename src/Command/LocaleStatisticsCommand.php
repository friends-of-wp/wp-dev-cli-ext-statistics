<?php

namespace FriendsOfWp\WordPressStatsDevCliExtension\Command;

use FriendsOfWp\WordPressStatsDevCliExtension\WordPressEndpoints;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LocaleStatisticsCommand extends StatisticsCommand
{
    protected static $defaultName = 'wordpress:statistics:locale';
    protected static $defaultDescription = 'Show the WordPress usage statistics based on the locale.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->writeInfo($output, 'Show the share of locales in the WordPress universe.');

        $versions = $this->requestData(WordPressEndpoints::LOCALES);

        $tableRows = [];

        foreach ($versions as $version => $share) {
            $tableRows[] = [$version, $this->formatShare($share)];
        }

        $this->renderTable($output, ["Locale", 'Share'], $tableRows);

        return SymfonyCommand::SUCCESS;
    }
}
