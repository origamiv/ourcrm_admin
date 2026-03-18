<?php

namespace App\Http\Controllers;

use App\Jobs\ExportExcelJob;
use App\Models\ExcelExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    /**
     * POST /api/export/excel
     * Создаёт задачу экспорта и ставит её в очередь.
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->validate([
            'api_url' => 'required|string',
            'token'   => 'required|string',
            'entity'  => 'required|string',
            'fields'  => 'required|array',
            'filter'  => 'nullable|array',
            'order'   => 'nullable|array',
        ]);

        $export = ExcelExport::create([
            'entity' => $data['entity'],
            'status' => 'pending',
            'params' => [
                'fields' => $data['fields'],
                'filter' => $data['filter'] ?? [],
                'order'  => $data['order'] ?? [],
            ],
        ]);

        ExportExcelJob::dispatch(
            $export->id,
            $data['api_url'],
            $data['token'],
            $data['fields'],
            $data['filter'] ?? [],
            $data['order'] ?? [],
        )->onQueue('exports');

        return response()->json(['id' => $export->id]);
    }

    /**
     * GET /api/export/status/{id}
     * Возвращает статус задачи экспорта.
     */
    public function status(string $id): JsonResponse
    {
        $export = ExcelExport::find($id);

        if (!$export) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $result = [
            'id'     => $export->id,
            'status' => $export->status,
        ];

        if ($export->status === 'done' && $export->file_path) {
            $result['download_url'] = url('/api/export/download/' . $export->id);
        }

        if ($export->status === 'failed') {
            $result['error'] = $export->error_message;
        }

        return response()->json($result);
    }

    /**
     * GET /api/export/download/{id}
     * Скачивает сформированный Excel-файл.
     */
    public function download(string $id)
    {
        $export = ExcelExport::find($id);

        if (!$export || $export->status !== 'done' || !$export->file_path) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($export->file_path)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($export->file_path);
        $filename = $export->entity . '_export_' . date('Y-m-d') . '.xlsx';

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
