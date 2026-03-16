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
        'id' => 33,

        // Название в меню
        'name' => 'Баланс',

        // Уникальный ключ модуля
        'shortname' => 'money.balance',

        // Родительский раздел
        'parent_id' => 31,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/money/balance',

        // API endpoint
        'api' => 'https://money.our24.ru/api/balance',

        // Eloquent модель
        'model' => 'Modules\\Money\\Models\\Balance',

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
        'name' => [
            'name' => 'Наименование',
            'field_mode' => 'show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Краткое наименование',
            'field_mode' => 'show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'money_at' => [
            'name' => 'Дата',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'schet_id' => [
            'name' => 'Счет',
            'field_model' => '/Modules/Money/Models/Schet',
            'field_items' => 'schets',
            'field_prop' => 'schet',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/money/schet',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'balance_start' => [
            'name' => 'Баланс на начало дня',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'balance_end' => [
            'name' => 'Баланс на конец дня',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'balance_delta' => [
            'name' => 'Изменение баланса',
            'field_mode' => 'index,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Запланировано',
                ],
                [
                    'id' => 1,
                    'name' => 'Исполнено',
                ],
                [
                    'id' => 2,
                    'name' => 'Не исполнено',
                ],
                [
                    'id' => 3,
                    'name' => 'В процессе',
                ],
            ],
            'field_mode' => 'index,create,edit,show',
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
