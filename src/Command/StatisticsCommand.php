<?php

namespace FriendsOfWp\WordPressStatsDevCliExtension\Command;

use FriendsOfWp\DeveloperCli\Command\Command;
use GuzzleHttp\Client;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

abstract class StatisticsCommand extends Command
{
    /**
     * Format the share in a way that it looks good in the table.
     */
    protected function formatShare($share): string
    {
        $shareValue = number_format(round($share, 2), 2) . ' %';
        if ($share < 100) {
            $shareValue = ' ' . $shareValue;
        }
        if ($share < 10) {
            $shareValue = ' ' . $shareValue;
        }

        return "  " . $shareValue;
    }

    /**
     * Request the current share from the WordPress API.
     */
    protected function requestData(string $endpoint): array
    {
        $client = new Client();
        $response = $client->get($endpoint);
        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Render a table with the statistics.
     */
    protected function renderTable(OutputInterface $output, $headers, $rows): void
    {
        $table = new Table($output);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->render();
    }
}
