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
        'id' => 4015,

        // Название в меню
        'name' => 'Капы',

        // Уникальный ключ модуля
        'shortname' => 'caps',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/caps',

        // API endpoint
        'api' => '/api/v1/caps',

        // Eloquent модель
        'model' => 'App\\Models\\Cap',

        // Иконка меню
        'icon' => 'uil uil-chart-line',

        // ACL / permissions resource
        'resource' => 'caps',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 60,

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
    | Fields — бизнес-поля сущности Cap
    |--------------------------------------------------------------------------
    */
    'fields' => [

        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'name' => [
            'name' => 'Название',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Код',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'product_id' => [
            'name' => 'Продукт',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/v1/products',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'geo_id' => [
            'name' => 'GEO',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/v1/geo',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'user_id' => [
            'name' => 'Пользователь',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/v1/users',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'interval_id' => [
            'name' => 'Интервал',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/v1/intervals',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'val' => [
            'name' => 'Лимит',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'current_val' => [
            'name' => 'Текущее значение',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'finish_at' => [
            'name' => 'Дата окончания',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'datetime',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'status',
            'formatter' => 'badge',
            'formatter_options' => [
                1 => 'badge-outline-success',
                0 => 'badge-outline-danger',
            ],
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'created_at' => [
            'name' => 'Создана',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'datetime',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'updated_at' => [
            'name' => 'Обновлена',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'datetime',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default order
    |--------------------------------------------------------------------------
    */
    'order' => [
        'id' => 'desc',
    ],

];
