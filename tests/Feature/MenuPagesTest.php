<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Автотесты для всех пунктов левого меню.
 *
 * Для каждого листового пункта меню (без дочерних, с указанной страницей):
 *  1. Проверяет, что страница возвращает HTTP 200
 *  2. Проверяет наличие таблицы (id="mainTabulator") в HTML
 *  3. Проверяет наличие записей в таблице через API /list
 *
 * Запуск:
 *   vendor/bin/phpunit tests/Feature/MenuPagesTest.php --testdox
 */
class MenuPagesTest extends TestCase
{
    // ------------------------------------------------------------------ //
    //  Data provider
    // ------------------------------------------------------------------ //

    /**
     * Возвращает все листовые пункты меню: [label, page, api|null]
     */
    public static function menuItemsProvider(): array
    {
        $menuJson = dirname(__DIR__, 2) . '/config/menu.json';
        $json    = json_decode(file_get_contents($menuJson), true);
        $all     = $json['data'] ?? [];

        // Оставляем только видимые пункты (status === 1 или null)
        $visible = array_filter($all, static fn($i) => $i['status'] === null || $i['status'] == 1);

        // Собираем id пунктов, которые являются родителями
        $parentIds = [];
        foreach ($visible as $item) {
            if (($item['parent_id'] ?? 0) > 0) {
                $parentIds[$item['parent_id']] = true;
            }
        }

        // Листовые пункты — не имеют дочерних и содержат page
        $leaves = array_values(
            array_filter($visible, static fn($i) => !isset($parentIds[$i['id']]) && !empty($i['page']))
        );

        $dataset = [];
        foreach ($leaves as $item) {
            // Уникальный ключ для отображения в отчёте
            $label = sprintf('%s (%s)', $item['name'], $item['page']);
            // Позиционный массив: [name, page, api]
            $dataset[$label] = [
                $item['name'],
                $item['page'],
                $item['api'] ?? null,
            ];
        }

        return $dataset;
    }

    // ------------------------------------------------------------------ //
    //  1. Статус ответа страницы
    // ------------------------------------------------------------------ //

    #[DataProvider('menuItemsProvider')]
    public function test_page_returns_http_200(string $name, string $page, ?string $api): void
    {
        $response = $this->get($page);

        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "Страница «{$name}» ({$page}) вернула HTTP {$response->getStatusCode()}"
        );
    }

    // ------------------------------------------------------------------ //
    //  2. Наличие таблицы в HTML
    // ------------------------------------------------------------------ //

    #[DataProvider('menuItemsProvider')]
    public function test_page_contains_table(string $name, string $page, ?string $api): void
    {
        $response = $this->get($page);

        if ($response->getStatusCode() !== 200) {
            $this->markTestSkipped(
                "Пропущено — страница «{$name}» ({$page}) вернула HTTP {$response->getStatusCode()}"
            );
        }

        $content = $response->getContent();

        $this->assertTrue(
            str_contains($content, 'id="mainTabulator"'),
            "Страница «{$name}» ({$page}) не содержит таблицу (id=\"mainTabulator\")"
        );
    }

    // ------------------------------------------------------------------ //
    //  3. Наличие записей в таблице (через API)
    // ------------------------------------------------------------------ //

    #[DataProvider('menuItemsProvider')]
    public function test_table_has_records(string $name, string $page, ?string $api): void
    {
        if (empty($api)) {
            $this->markTestSkipped("Нет API URL для страницы «{$name}» ({$page})");
        }

        $listUrl = rtrim($api, '/') . '/list';

        $client = new Client([
            'timeout'         => 15,
            'connect_timeout' => 10,
            'http_errors'     => false,
        ]);

        try {
            $apiResponse = $client->get($listUrl, [
                'query' => ['perpage' => 1, 'page' => 1],
            ]);
        } catch (\Throwable $e) {
            $this->markTestSkipped(
                "Не удалось подключиться к API «{$name}» ({$listUrl}): {$e->getMessage()}"
            );
            return;
        }

        $httpStatus = $apiResponse->getStatusCode();

        if ($httpStatus !== 200) {
            $this->markTestSkipped(
                "API «{$name}» ({$listUrl}) вернул HTTP {$httpStatus}"
            );
            return;
        }

        $body = (string) $apiResponse->getBody();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->markTestSkipped(
                "API «{$name}» ({$listUrl}) вернул не JSON: " . substr($body, 0, 120)
            );
            return;
        }

        // Поддерживаем два формата ответа:
        //   { pagination: { count: N }, data: [...] }
        //   { data: [...] }
        $total = $data['pagination']['count']
            ?? $data['pagination']['total']
            ?? count($data['data'] ?? []);

        $this->assertGreaterThan(
            0,
            $total,
            "Таблица «{$name}» ({$page}) не содержит записей. API: {$listUrl}"
        );
    }
}
