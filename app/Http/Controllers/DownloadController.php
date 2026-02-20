<?php
// app/Http/Controllers/DownloadController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    /**
     * /download/image/{section}/{field}/{variant}/{value}
     *
     * Пример: /download/image/clicks/country/flag/MY
     */
    public function image(Request $request, string $entity, int $id, string $field, string $variant): BinaryFileResponse
    {
        // 1) Нормализуем вход (на всякий случай)
        $entity = $this->sanitizeSegment($entity);
        $field   = $this->sanitizeSegment($field);
        $variant = $this->sanitizeSegment($variant);

        //dd($entity, $field, $variant, $value);
        $config=config('entities.'.$entity);
        //$config=config('entities.'.$entity.'.fields.'.$field);



        dd($config);

        // 2) Ищем реальный ресурс в нескольких расширениях
        $disk = config('download.disk', 'local'); // обычно local
        $exts = config('download.image.extensions', ['png', 'svg', 'webp', 'jpg', 'jpeg']);

        $baseDir = trim(config('download.image.base_dir', 'download/image'), '/');
        $pathNoExt = "{$baseDir}/{$entity}/{$field}/{$variant}/{$value}";

        $found = $this->findFirstExisting($disk, $pathNoExt, $exts);

        // 3) Если не нашли — подставляем дефолт (по полю+варианту, затем по варианту, затем общий)
        if ($found === null) {
            $defaultsDir = trim(config('download.image.defaults_dir', 'download/defaults'), '/');

            $fallbackCandidates = [
                "{$defaultsDir}/{$field}/{$variant}/default",
                "{$defaultsDir}/_any/{$variant}/default",
                "{$defaultsDir}/_any/_any/default",
            ];

            foreach ($fallbackCandidates as $candidateNoExt) {
                $found = $this->findFirstExisting($disk, $candidateNoExt, $exts);
                if ($found !== null) {
                    break;
                }
            }
        }

        // 4) Совсем нет — отдадим 404 (или можете генерить 1x1)
        if ($found === null) {
            abort(404);
        }

        // 5) Отдаём файл с кешированием
        $absolutePath = Storage::disk($disk)->path($found);

        $response = response()->file($absolutePath, [
            'Cache-Control' => 'public, max-age=' . (int) config('download.cache.max_age', 604800) . ', immutable',
        ]);

        // ETag/Last-Modified Laravel выставит на уровне Symfony BinaryFileResponse автоматически по файлу,
        // но можно добавить и свои заголовки при желании.

        return $response;
    }

    private function findFirstExisting(string $disk, string $pathNoExt, array $exts): ?string
    {
        foreach ($exts as $ext) {
            $p = "{$pathNoExt}.{$ext}";
            if (Storage::disk($disk)->exists($p)) {
                return $p;
            }
        }
        return null;
    }

    private function sanitizeSegment(string $s): string
    {
        // запрещаем слэши и прочую экзотику, чтобы не было path traversal
        $s = str_replace(['/', '\\'], '', $s);
        return preg_replace('~[^A-Za-z0-9_\-]~', '', $s) ?: '_';
    }

    private function sanitizeValue(string $s): string
    {
        // value может быть вроде "MY" или "windows-11" или "OS X" (если нужно — лучше хранить как OS_X)
        $s = str_replace(['/', '\\'], '', $s);
        return preg_replace('~[^A-Za-z0-9_\-\.]~', '_', $s) ?: '_';
    }
}
