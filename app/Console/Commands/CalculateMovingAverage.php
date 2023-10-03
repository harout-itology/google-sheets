<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsService;
use Illuminate\Console\Command;

class CalculateMovingAverage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:average {sheetId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate moving average of daily visitors from a Google Sheet';

    public function __construct(protected GoogleSheetsService $sheetsService)
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $sheetId = $this->argument('sheetId');

        // Fetch and validate the header row
        if (!($header = $this->getHeaderRow($sheetId))) return;

        // Fetch and validate the required column
        if (!($range = $this->getRequiredColumns($header))) return;

        // Fetch and validate data
        if (!($data = $this->getData($sheetId, $range))) return;

        // Calculate the moving average of daily visitors
        $movingAverage = $this->getMovingAverage($data);

        // add the Moving Average values to the sheet
        $this->setMovingAverage($sheetId, $header, $movingAverage);


        $this->info('Moving average updated successfully.');
    }

    protected function getHeaderRow(string $sheetId): ?array
    {
        $header = $this->sheetsService->getValues($sheetId, 'A1:Z1');
        if (!$header) {
            $this->error('Header row not found.');
            return null;
        }
        return $header[0];
    }

    protected function getRequiredColumns(array $header): ?string
    {
        $visitorsColumnIndex = array_search('Visitors', $header);
        if (!$visitorsColumnIndex) {
            $this->error('Required columns not found.');
            return null;
        }

        // Convert column index to alphabetic representation using ASCII
        $visitorsColumn = chr(65 + $visitorsColumnIndex);

        return "{$visitorsColumn}2:{$visitorsColumn}";
    }

    protected function getData(string $sheetId, string $range): ?array
    {
        $visitorValues = $this->sheetsService->getValues($sheetId, $range);
        if (!$visitorValues) {
            $this->error('No data found.');
            return null;
        }

        return $visitorValues;
    }

    protected function getMovingAverage(array $data): array
    {
        $movingAverage = [['Moving Average'], [0]];
        for ($i=1; $i<count($data); $i++) {
            $movingAverage[] = [($data[$i][0] + $data[$i-1][0]) / 2];
        }
        return $movingAverage;
    }

    protected function setMovingAverage(string $sheetId, array $header, array $movingAverage): void
    {
        $movingAverageColumnIndex = array_search('Moving Average', $header);

        // Convert column index to alphabetic representation using ASCII
        $movingAverageColumn = chr(65 + ($movingAverageColumnIndex ?: count($header)));

        // update the Moving Average column
        $this->sheetsService->updateValues($sheetId,"{$movingAverageColumn}:{$movingAverageColumn}", $movingAverage);
    }
}
