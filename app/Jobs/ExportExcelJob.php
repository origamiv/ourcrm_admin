<?php

namespace App\Jobs;

use App\Models\ExcelExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries = 1;

    public function __construct(
        private string $exportId,
        private string $apiUrl,
        private string $token,
        private array  $fields,
        private array  $filter,
        private array  $order,
    ) {}

    public function handle(): void
    {
        $export = ExcelExport::find($this->exportId);
        if (!$export) return;

        $export->update(['status' => 'processing']);

        try {
            $rows = $this->fetchAllData();
            $filePath = $this->buildExcel($rows);

            $export->update([
                'status'    => 'done',
                'file_path' => $filePath,
            ]);
        } catch (\Throwable $e) {
            $export->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    private function fetchAllData(): array
    {
        $payload = [
            'page'    => 1,
            'perpage' => -1,
            'order'   => $this->order,
            'filter'  => $this->filter,
        ];

        $response = Http::withToken($this->token)
            ->timeout(120)
            ->post($this->apiUrl . '/list', $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('API error: ' . $response->status());
        }

        $json = $response->json();
        return $json['data'] ?? [];
    }

    private function buildExcel(array $rows): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header row styles
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // Write headers
        $col = 1;
        foreach ($this->fields as $field) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->setCellValue($colLetter . '1', $field['title'] ?? $field['key']);
            $col++;
        }

        if ($col > 1) {
            $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col - 1);
            $sheet->getStyle("A1:{$lastColLetter}1")->applyFromArray($headerStyle);
        }

        // Write data rows
        $rowNum = 2;
        foreach ($rows as $row) {
            $col = 1;
            foreach ($this->fields as $field) {
                $key = $field['key'];
                $value = $row[$key] ?? null;

                // Flatten complex values (arrays/objects → JSON string)
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }

                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $sheet->setCellValue($colLetter . $rowNum, $value);
                $col++;
            }
            $rowNum++;
        }

        // Auto-size columns
        $colCount = count($this->fields);
        for ($c = 1; $c <= $colCount; $c++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Save to storage/app/public/exports/
        $filename = 'exports/' . $this->exportId . '.xlsx';
        Storage::disk('public')->makeDirectory('exports');

        $tmpPath = sys_get_temp_dir() . '/' . $this->exportId . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tmpPath);

        Storage::disk('public')->put($filename, file_get_contents($tmpPath));
        @unlink($tmpPath);

        return $filename;
    }
}
