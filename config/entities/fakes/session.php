<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Common — описание сущности / модуля / пункта меню
    |--------------------------------------------------------------------------
    | Это отражение записи из таблицы menus
    */
    'common' => [

        // ID записи в menus
        'id' => 191,

        // Название в меню
        'name' => 'Сессии',

        // Уникальный ключ модуля
        'shortname' => 'fakes.session',

        // Родительский раздел
        'parent_id' => 176,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 3,

        // Web-страница
        'page' => '/web/fakes/session',

        // API endpoint
        'api' => '/api/fakes/session',

        // Eloquent модель
        'model' => 'Modules\\Fakes\\Models\\Session',

        // Иконка меню
        'icon' => 'uil uil-user',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => null,

        // Не справочник
        'is_list' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */
    'layout' => [
        'filter_view' => 'advanced',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fields — бизнес-поля сущности
    |--------------------------------------------------------------------------
    */
    'fields' => [
        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'control' => 'integer',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'bot_id' => [
            'name' => 'Бот',
            'field_model' => '/Modules/Fakes/Models/Bot',
            'field_items' => 'bots',
            'field_prop' => 'bot',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/fakes/bot',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'data' => [
            'name' => 'Входные данные',
            'field_mode' => 'create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'src' => [
            'name' => 'Данные от бота',
            'field_mode' => 'create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'result' => [
            'name' => 'Результат',
            'field_mode' => 'create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'update' => [
            'name' => 'Обновление аккаунтов',
            'field_mode' => 'create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'need' => [
            'name' => 'Необходимые параметры',
            'field_mode' => 'index,create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'dat_last_run' => [
            'name' => 'Время последнего использования',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'field_items' => [
                [
                    'id' => '0',
                    'name' => 'Неизвестно',
                ],
                [
                    'id' => '1',
                    'name' => 'Успешно',
                ],
                [
                    'id' => '2',
                    'name' => 'Ошибка',
                ],
                [
                    'id' => '3',
                    'name' => 'В работе',
                ],
                [
                    'id' => '4',
                    'name' => 'Ожидание данных',
                ],
                [
                    'id' => '5',
                    'name' => 'Подготовка данных',
                ],
            ],
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default order
    |--------------------------------------------------------------------------
    */
    'order' => [
        'id' => 'asc',
    ],

];
