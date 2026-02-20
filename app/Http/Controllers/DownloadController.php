<?php
// app/Http/Controllers/DownloadController.php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    /**
     * /download/image/{entity}/{id}/{field}/{variant}
     *
     * Например (если value берём из записи по id):
     * /download/image/clicks/123/country/flag
     */
    public function image(Request $request, string $entity, int $id, string $field, string $variant): BinaryFileResponse
    {
        $entity  = $this->sanitizeSegment($entity);
        $field   = $this->sanitizeSegment($field);
        $variant = $this->sanitizeSegment($variant);

        $config = config('entities.' . $entity);
        if (empty($config['common']['api'])) {
            abort(404, 'Entity api is not configured');
        }

        // 1) Получаем запись из API (через Guzzle)
        $record = $this->fetchEntityRecordFromApi($entity, $id, $config, $request);

        //dd($record);

        // 2) Определяем "value" для картинки:
        // - если в конфиге у поля есть modifier_field — берём его (например country_code)
        // - иначе берём значение самого $field
        $configField = $config['fields'][$field] ?? null;

        $fieldName = $configField['modifier_field'] ?? $field;
        $val = $record[$fieldName] ?? null;

        if ($val === null || $val === '') {
            // нет значения — сразу fallback-картинку
            return $this->serveFallback($field, $variant);
        }

        $val = $this->sanitizeValue((string)$val);

        // 3) Ищем файл и отдаём (логика как у тебя была)
        $exts = config('download.image.extensions', ['png', 'svg', 'webp', 'jpg', 'jpeg']);

        $baseDir = trim(config('download.image.base_dir', 'public/assets/images'), '/');
        $pathNoExt = "{$baseDir}/{$variant}/{$val}";

        //dd($pathNoExt);

        $found = $this->findFirstExisting('', $pathNoExt, $exts);

        //dd($found);

        if ($found === null) {
            return $this->serveFallback($field, $variant);
        }

        return $this->serveFile('', $found);
    }

    private function fetchEntityRecordFromApi(string $entity, int $id, array $config, Request $request): array
    {
        $apiBase = rtrim((string)config('app.api_url', ''), '/');
        $endpoint = rtrim((string)$config['common']['api'], '/');


        // Вариант URL:
        // 1) {API_URL}{endpoint}/{id}
        // Если у тебя по-другому (например /show?id=) — поменяешь здесь.
        $url = $apiBase . $endpoint . '/' . $id;

        // Кешируем, чтобы не бомбить API на каждый <img>
        $cacheTtl = (int)config('download.api.cache_ttl', 300);
        $cacheKey = "download:image:entity_record:{$entity}:{$id}";

        //dd($cacheTtl);

        // return Cache::remember($cacheKey, $cacheTtl, function () use ($url, $request) {
            $token=$this->buildAuthorizationHeader($request);
            //dd($token);
            $client = new Client([
                'timeout' => (float)config('download.api.timeout', 3.0),
                'http_errors' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    // если API требует авторизацию — пробросим bearer из текущего запроса
                    // (или настрой отдельный токен в config/download.php)
                    'Authorization' => $token,
                ],
            ]);

            try {
                $res = $client->get($url);
                //dd($res);
            } catch (GuzzleException $e) {
                // API недоступно — считаем как "нет значения" -> fallback
                return [];
            }

            if ($res->getStatusCode() < 200 || $res->getStatusCode() >= 300) {
                return [];
            }

            //dd($res->getBody()->getContents());

            $json = json_decode($res->getBody()->getContents(), true);

            //dd($json['data']);

            // Поддержим два популярных формата:
            // 1) { data: {...} }
            // 2) сразу объект {...}
            if (is_array($json) && array_key_exists('data', $json) && is_array($json['data'])) {
                return $json['data'];
            }



            return is_array($json) ? $json : [];
        //});
    }

    private function buildAuthorizationHeader(Request $request): string
    {
        // 1) отдельный токен для сервисных запросов (предпочтительнее)
        $token = (string)config('download.api.token', '');
        if ($token !== '') {
            return 'Bearer ' . $token;
        }

        // 2) проброс текущего Bearer
        $bearer = $request->bearerToken();
        if (!empty($bearer)) {
            return 'Bearer ' . $bearer;
        }

        return '';
    }

    private function serveFallback(string $field, string $variant): BinaryFileResponse
    {
        $disk = config('download.disk', 'local');
        $exts = config('download.image.extensions', ['png', 'svg', 'webp', 'jpg', 'jpeg']);
        $defaultsDir = trim(config('download.image.defaults_dir', 'download/defaults'), '/');

        $fallbackCandidates = [
            "{$defaultsDir}/{$field}/{$variant}/default",
            "{$defaultsDir}/_any/{$variant}/default",
            "{$defaultsDir}/_any/_any/default",
        ];

        foreach ($fallbackCandidates as $candidateNoExt) {
            $found = $this->findFirstExisting($disk, $candidateNoExt, $exts);
            if ($found !== null) {
                return $this->serveFile($disk, $found);
            }
        }

        abort(404);
    }

    private function serveFile(string $disk, string $relativePath): BinaryFileResponse
    {
        //$absolutePath = Storage::disk($disk)->path($relativePath);
        $absolutePath=base_path($relativePath);

        return response()->file($absolutePath, [
            'Cache-Control' => 'public, max-age=' . (int)config('download.cache.max_age', 604800) . ', immutable',
        ]);
    }

    private function findFirstExisting(string $disk, string $pathNoExt, array $exts): ?string
    {
        foreach ($exts as $ext) {
            $p = "/{$pathNoExt}.{$ext}";
            //if (Storage::disk($disk)->exists($p))
            if (file_exists(base_path($p)))
            {
                return $p;
            }
        }
        return null;
    }

    private function sanitizeSegment(string $s): string
    {
        $s = str_replace(['/', '\\'], '', $s);
        return preg_replace('~[^A-Za-z0-9_\-]~', '', $s) ?: '_';
    }

    private function sanitizeValue(string $s): string
    {
        $s = str_replace(['/', '\\'], '', $s);
        return preg_replace('~[^A-Za-z0-9_\-\.]~', '_', $s) ?: '_';
    }
}
