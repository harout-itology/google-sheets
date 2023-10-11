<?php

namespace App\Services;

use Google_Client;
use Google_Service_Exception;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    protected Google_Service_Sheets $service;

    public function __construct(protected Google_Client $client)
    {
        $this->service = new Google_Service_Sheets($client);
    }

    public function getValues(string $spreadsheetId, string $range): ?array
    {
        try {
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues();
        } catch (Google_Service_Exception $e) {
            Log::error('Error: GoogleSheetsService', json_decode($e->getMessage(), true));
            return null;
        }
    }

    public function updateValues(string $spreadsheetId, string $range, array $values): void
    {
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];
        $this->service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    }
}
