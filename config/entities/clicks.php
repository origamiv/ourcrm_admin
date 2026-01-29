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
        'id' => 4012,

        // Название в меню
        'name' => 'Клики',

        // Уникальный ключ модуля
        'shortname' => 'clicks',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/clicks',

        // API endpoint
        'api' => '/api/v1/clicks',

        // Eloquent модель
        'model' => 'App\\Models\\Click',

        // Иконка меню
        'icon' => 'uil uil-mouse-alt',

        // ACL / permissions resource
        'resource' => 'clicks',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 40,

        // Не справочник (лог событий)
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
    | Fields — бизнес-поля сущности Click
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

        'click_id' => [
            'name' => 'Click ID (ext)',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
//            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'campaign_id' => [
            'name' => 'Кампания',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/v1/campaigns',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'stream_id' => [
            'name' => 'Стрим',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/v1/streams',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'offer_id' => [
            'name' => 'Оффер',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/v1/offers',
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

        'ip' => [
            'name' => 'IP',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'user_agent' => [
            'name' => 'User Agent',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => 'truncate',
            'formatter_options' => [
                'length' => 120,
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'params' => [
            'name' => 'Параметры',
            'field_mode' => 'show',
            'is_filter_need' => true,
            'control' => 'json',
            'formatter' => 'json',
            'db_type' => 'json',
            'is_lookup' => false,
        ],

        'clicked_at' => [
            'name' => 'Дата клика',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'datetime',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'created_at' => [
            'name' => 'Создано',
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
        'clicked_at' => 'desc',
    ],

];
