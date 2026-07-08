<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = '19aKNashZo2pea0keVbm6O_fJiy-bjaaHjfYF7Uqsapk';

        $this->client = new Client();
        $this->client->setApplicationName('Laravel Google Sheets');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAuthConfig(storage_path('app/google/credentials.json'));
        $this->client->setAccessType('offline');

        $this->service = new Sheets($this->client);
    }

    public function updateSheet($tabName, $data)
    {
        $body = new Sheets\ValueRange([
            'values' => $data
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];
        $range = $tabName . '!A1';
        return $this->service->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);
    }

    public function appendRow($tabName, $row)
    {
        $body = new Sheets\ValueRange([
            'values' => [$row]
        ]);
        $params = [
            'valueInputOption' => 'RAW',
            'insertDataOption' => 'INSERT_ROWS',
        ];
        $range = $tabName;
        return $this->service->spreadsheets_values->append($this->spreadsheetId, $range, $body, $params);
    }

    public function isSheetEmpty($tabName)
    {
        $range = $tabName . '!A1:Z1'; // Check full row
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues();
        return empty($values) || empty(array_filter($values[0]));
    }
}
