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
        'id' => 4003,

        // Название в меню
        'name' => 'Офферы',

        // Уникальный ключ модуля
        'shortname' => 'keitaro.offers',

        // Родительский раздел
        'parent_id' => 6000,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/keitaro.offers',

        // API endpoint
        'api' => 'https://keitaro.our24.ru/api/offers',

        // Eloquent модель
        'model' => 'App\\Models\\Offer',

        // Иконка меню
        'icon' => 'uil uil-gift',

        // ACL / permissions resource
        'resource' => 'offers',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 12,

        // Не справочник
        'is_list' => 2,
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
    | Fields — бизнес-поля сущности Offer
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

        'ext_id' => [
            'name' => 'Ext ID',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'advertiser_id' => [
            'name' => 'Рекламодатель',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/advertisers',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'partner_id' => [
            'name' => 'Партнёр',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/partners',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'product_id' => [
            'name' => 'Продукт',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/products',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'geo_id' => [
            'name' => 'GEO',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/geo',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'state' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'badge',
            'formatter_options' => [
                'active'   => 'badge-outline-success',
                'paused'   => 'badge-outline-warning',
                'disabled' => 'badge-outline-danger',
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'payout' => [
            'name' => 'Выплата',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'currency',
            'db_type' => 'decimal',
            'is_lookup' => false,
        ],

        'currency' => [
            'name' => 'Валюта',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'created_at' => [
            'name' => 'Создан',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'updated_at' => [
            'name' => 'Обновлён',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
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
