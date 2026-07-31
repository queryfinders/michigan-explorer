<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait Exportable
{
    /**
     * Export data to CSV.
     *
     * @param Builder|null $query The query builder instance, or null if passing raw data
     * @param string $format 'csv' (xlsx not supported natively without heavy packages, exporting as CSV which opens in Excel)
     * @param string $filename Base filename without extension
     * @param callable|null $mapCallback function($item) returns array
     * @param iterable|null $data Raw data iterable if not using Eloquent query
     * @return StreamedResponse
     */
    public function exportData($query, $format, $filename, callable $mapCallback = null, $data = null)
    {
        // Force CSV format
        $format = 'csv';
        $filename = $filename . '_' . date('Y_m_d_H_i_s') . '.' . $format;
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return new StreamedResponse(function () use ($query, $mapCallback, $data) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 Excel support
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $isHeaderWritten = false;

            if ($query) {
                $query->chunk(500, function ($items) use ($handle, $mapCallback, &$isHeaderWritten) {
                    foreach ($items as $item) {
                        $row = $mapCallback ? $mapCallback($item) : $item->toArray();
                        if (!$isHeaderWritten) {
                            fputcsv($handle, array_keys($row));
                            $isHeaderWritten = true;
                        }
                        fputcsv($handle, array_values($row));
                    }
                });
            } elseif ($data) {
                foreach ($data as $item) {
                    $row = $mapCallback ? $mapCallback($item) : (is_array($item) ? $item : (array)$item);
                    if (!$isHeaderWritten) {
                        fputcsv($handle, array_keys($row));
                        $isHeaderWritten = true;
                    }
                    fputcsv($handle, array_values($row));
                }
            }

            fclose($handle);
        }, 200, $headers);
    }
}
