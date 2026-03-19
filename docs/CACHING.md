# Кэширование на фронтенде

Система кэширования реализована полностью на фронтенде через `localStorage`. Используются два независимых кэша: `LookupCache` для справочников и `MainTableCache` для основных таблиц.

---

## 1. LookupCache — кэш справочников

### Назначение

Кэширует данные **справочных таблиц** (справочники, классификаторы и прочие reference-таблицы). Используется для быстрого доступа к спискам выбора в формах и таблицах.

### Расположение

Объявлен глобально в `resources/views/components/layout/default.blade.php` как `window.LookupCache`.

### Технические характеристики

| Параметр | Значение |
|---|---|
| Хранилище | `localStorage` |
| Префикс ключей | `lookup:` |
| TTL | 30 минут |
| Формат записи | `{ data: [...], cached_at: <timestamp> }` |

### Автозагрузка при старте

При загрузке страницы (через `DOMContentLoaded`) выполняется автозагрузка всех справочников через `LookupCache.loadAll()`:

```javascript
document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('access_token');
    if (token) {
        const lookups = @json($lookupItems);
        setTimeout(() => window.LookupCache.loadAll(lookups, token), 1500);
    }
});
```

Список `$lookupItems` формируется в PHP: из меню выбираются все модули, у которых `shortname` оканчивается на `.references`, и извлекаются их дочерние пункты с `api`-эндпоинтами.

`loadAll` проходит по каждому справочнику:
- Если данные уже есть в кэше — пропускает.
- Если нет — делает POST-запрос с `{ page: 1, perpage: -1 }` (загружает все записи) и сохраняет результат.

### API объекта

| Метод | Описание |
|---|---|
| `get(api)` | Возвращает закэшированный массив или `null`, если кэш пустой или протух |
| `set(api, data)` | Сохраняет массив данных с текущим временем |
| `addItem(api, item)` | Добавляет одну запись в существующий кэш |
| `updateItem(api, id, item)` | Обновляет запись по `id`; если не найдена — добавляет |
| `removeItem(api, id)` | Удаляет запись по `id` |
| `loadAll(lookups, token)` | Батч-загрузка всех справочников при старте страницы |

### Синхронизация при мутациях

При создании/редактировании/удалении записи справочника кэш обновляется синхронно (без перезагрузки):

- **Создание** → `LookupCache.addItem(api, savedItem)`
- **Редактирование** → `LookupCache.updateItem(api, id, savedItem)`
- **Удаление** → `LookupCache.removeItem(api, id)`

---

## 2. MainTableCache — кэш основных таблиц

### Назначение

Кэширует данные **основных таблиц** (сущности с `is_list === 2`). Используется для двух целей:

1. Ускорение загрузки страниц — данные загружаются в фоне и при повторном открытии берутся из кэша.
2. Использование в качестве источника данных для полей-справочников, которые ссылаются на основные таблицы (fallback к `LookupCache`).

### Расположение

Объявлен глобально в `resources/views/components/layout/default.blade.php` как `window.MainTableCache`.

### Технические характеристики

| Параметр | Значение |
|---|---|
| Хранилище | `localStorage` |
| Префикс ключей | `main:` |
| TTL | 30 минут |
| Формат записи | `{ data: [...], cached_at: <timestamp> }` |

### Фоновая загрузка

При инициализации списка (`list.blade.php`) вызывается `_backgroundLoadAllRecords()`:

```javascript
async _backgroundLoadAllRecords() {
    if ((window.CONFIG.common?.is_list ?? 0) !== 2) return;

    const api = resolveUrl(window.CONFIG.common.api);
    if (window.MainTableCache?.get(api) !== null) return; // уже закэшировано

    // POST /list с perpage: -1 — загрузить всё
    const resp = await fetch(api + '/list', { ... body: { page: 1, perpage: -1 } });
    window.MainTableCache?.set(api, json.data);
}
```

Условие `is_list === 2` означает, что таблица помечена как "основная" и подлежит фоновому кэшированию. Загрузка происходит в фоне, не блокируя отображение текущей страницы.

### API объекта

| Метод | Описание |
|---|---|
| `get(api)` | Возвращает закэшированный массив или `null` |
| `set(api, data)` | Сохраняет массив данных с текущим временем |
| `addItem(api, item)` | Добавляет одну запись в существующий кэш |
| `updateItem(api, id, item)` | Обновляет запись по `id`; если не найдена — добавляет |
| `removeItem(api, id)` | Удаляет запись по `id` |

### Синхронизация при мутациях

Аналогично `LookupCache`, при изменении данных основной таблицы кэш обновляется сразу:

- **Создание** → `MainTableCache.addItem(api, savedItem)`
- **Редактирование** → `MainTableCache.updateItem(api, id, savedItem)`
- **Удаление** → `MainTableCache.removeItem(api, id)`

---

## 3. Взаимодействие двух кэшей

При загрузке поля-справочника в списке или форме логика следующая:

```javascript
const cached = window.LookupCache?.get(api) ?? window.MainTableCache?.get(api);
```

То есть сначала проверяется `LookupCache`, и только если там нет данных — проверяется `MainTableCache`. Это позволяет использовать данные основных таблиц в качестве справочника для других модулей, не дублируя запросы.

Если данных нет ни в одном кэше — выполняется запрос к API, после чего результат сохраняется в `LookupCache`.

---

## 4. Общая схема работы

```
Загрузка страницы
    │
    ├─ DOMContentLoaded (+1.5с)
    │       └─ LookupCache.loadAll()
    │               Для каждого справочника:
    │               └─ Если нет в кэше → POST /list?perpage=-1 → сохранить
    │
    └─ Инициализация списка (is_list=2)
            └─ _backgroundLoadAllRecords()
                    Если нет в кэше → POST /list?perpage=-1 → MainTableCache.set()

Отображение таблицы / формы
    └─ loadLookups()
            Для каждого поля-справочника:
            └─ LookupCache.get() ?? MainTableCache.get()
                    Если null → POST /list → LookupCache.set()

Создание / Редактирование / Удаление записи
    └─ addItem / updateItem / removeItem
            в LookupCache и MainTableCache одновременно
```

---

## 5. Инвалидация кэша

Явного механизма инвалидации нет. Кэш сбрасывается по TTL (30 минут). При мутациях кэш обновляется точечно через `addItem` / `updateItem` / `removeItem`.

Принудительно сбросить кэш можно из консоли браузера:

```javascript
// Очистить конкретный справочник
localStorage.removeItem('lookup:/api/v1/some-endpoint');

// Очистить конкретную основную таблицу
localStorage.removeItem('main:/api/v1/some-endpoint');

// Полная очистка всего localStorage
localStorage.clear();
```

---

## 6. Ключевые файлы

| Файл | Содержимое |
|---|---|
| `resources/views/components/layout/default.blade.php` | Объявление `LookupCache` и `MainTableCache`, автозагрузка справочников |
| `resources/views/apps/invoice/list.blade.php` | Фоновая загрузка `MainTableCache`, использование обоих кэшей в `loadLookups` |
| `resources/views/apps/invoice/form.blade.php` | Использование кэшей в форме, обновление при сохранении |
| `resources/views/apps/occ_stats/list.blade.php` | Альтернативный паттерн использования только `LookupCache` |
