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
        'id' => 4020,

        // Название в меню
        'name' => 'Трекеры',

        // Уникальный ключ модуля
        'shortname' => 'trackers',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/trackers',

        // API endpoint
        'api' => '/api/v1/trackers',

        // Eloquent модель
        'model' => 'App\\Models\\Tracker',

        // Иконка меню
        'icon' => 'uil uil-server-network',

        // ACL / permissions resource
        'resource' => 'trackers',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 20,

        // Справочник
        'is_list' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */
    'layout' => [
        'filter_view' => 'title',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fields — бизнес-поля сущности Tracker
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

        'host' => [
            'name' => 'Хост',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'login' => [
            'name' => 'Логин',
            'field_mode' => 'create,edit,show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'password' => [
            'name' => 'Пароль',
            'field_mode' => 'create,edit',
            'is_filter_need' => false,
            'control' => 'password',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'token' => [
            'name' => 'API Token',
            'field_mode' => 'create,edit,show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => 'truncate',
            'formatter_options' => [
                'length' => 40,
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'created_at' => [
            'name' => 'Создан',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'datetime',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'updated_at' => [
            'name' => 'Обновлён',
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
        'id' => 'asc',
    ],

];
