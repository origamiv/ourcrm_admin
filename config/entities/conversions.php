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
        'id' => 4013,

        // Название в меню
        'name' => 'Конверсии',

        // Уникальный ключ модуля
        'shortname' => 'conversions',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/conversions',

        // API endpoint
        'api' => '/api/v1/conversions',

        // Eloquent модель
        'model' => 'App\\Models\\Conversion',

        // Иконка меню
        'icon' => 'uil uil-exchange',

        // ACL / permissions resource
        'resource' => 'conversions',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 50,

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
    | Fields — бизнес-поля сущности Conversion
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

        'conversion_ext_uuid' => [
            'name' => 'Conversion UUID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'click_id' => [
            'name' => 'Клик',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup' => [
                'entity' => 'clicks',
                'value'  => 'id',
                'label'  => 'click_ext_id',
            ],
        ],

        'lead_id' => [
            'name' => 'Лид',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup' => [
                'entity' => 'leads',
                'value'  => 'id',
                'label'  => 'id',
            ],
        ],

        'campaign_id' => [
            'name' => 'Кампания',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup' => [
                'entity' => 'campaigns',
                'value'  => 'id',
                'label'  => 'name',
            ],
        ],

        'advertiser_id' => [
            'name' => 'Рекламодатель',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup' => [
                'entity' => 'advertisers',
                'value'  => 'id',
                'label'  => 'name',
            ],
        ],

        'offer_id' => [
            'name' => 'Оффер',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup' => [
                'entity' => 'offers',
                'value'  => 'id',
                'label'  => 'name',
            ],
        ],

        'partner_id' => [
            'name' => 'Партнёр',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup' => [
                'entity' => 'partners',
                'value'  => 'id',
                'label'  => 'name',
            ],
        ],

        'product_id' => [
            'name' => 'Продукт',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup' => [
                'entity' => 'products',
                'value'  => 'id',
                'label'  => 'name',
            ],
        ],

        'geo_id' => [
            'name' => 'GEO',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup' => [
                'entity' => 'geo',
                'value'  => 'id',
                'label'  => 'code',
            ],
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'status',
            'formatter' => 'badge',
            'formatter_options' => [
                'install'      => 'badge-outline-secondary',
                'registration' => 'badge-outline-info',
                'deposit'      => 'badge-outline-success',
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'revenue' => [
            'name' => 'Доход',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'money',
            'db_type' => 'float',
            'is_lookup' => false,
        ],

        'cost' => [
            'name' => 'Расход',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'money',
            'db_type' => 'float',
            'is_lookup' => false,
        ],

        'profit' => [
            'name' => 'Профит',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => 'money',
            'db_type' => 'float',
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

        'postback_datetime' => [
            'name' => 'Postback',
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
        'postback_datetime' => 'desc',
    ],

];
