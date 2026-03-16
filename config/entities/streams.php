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
        'id' => 4014,

        // Название в меню
        'name' => 'Потоки',

        // Уникальный ключ модуля
        'shortname' => 'streams',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/streams',

        // API endpoint
        'api' => '/api/v1/streams',

        // Eloquent модель
        'model' => 'App\\Models\\Stream',

        // Иконка меню
        'icon' => 'uil uil-random',

        // ACL / permissions resource
        'resource' => 'streams',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 55,

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
    | Fields — бизнес-поля сущности Stream
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

        'ext_id' => [
            'name' => 'Ext ID',
            'field_mode' => 'index,create,edit,show',
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

        'type' => [
            'name' => 'Тип',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'badge',
            'formatter_options' => [
                'forced'  => 'badge-outline-warning',
                'regular' => 'badge-outline-primary',
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'campaign_ext_id' => [
            'name' => 'Кампания',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

//        'campaign_id' => [
//            'name' => 'Кампания',
//            'field_mode' => 'index,create,edit,show',
//            'is_filter_need' => true,
//            'control' => 'lookup',
//            'formatter' => 'lookup',
//            'db_type' => 'integer',
//            'is_lookup' => true,
//            'lookup_api' => '/api/v1/campaigns',
//            'lookup_id' => 'id',
//            'lookup_name' => 'name',
//        ],

        'state' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'badge',
            'formatter_options' => [
                'active'   => 'badge-outline-success',
                'disabled' => 'badge-outline-danger',
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'schema' => [
            'name' => 'Схема',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'action_type' => [
            'name' => 'Тип действия',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'action_payload' => [
            'name' => 'Action payload',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => 'truncate',
            'formatter_options' => [
                'length' => 80,
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'position' => [
            'name' => 'Позиция',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'weight' => [
            'name' => 'Вес',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'collect_clicks' => [
            'name' => 'Собирать клики',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'boolean',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'filter_or' => [
            'name' => 'Filter OR',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'boolean',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'offer_selection' => [
            'name' => 'Выбор оффера',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'filters' => [
            'name' => 'Фильтры',
            'field_mode' => 'show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'json',
            'db_type' => 'json',
            'is_lookup' => false,
        ],

        'created_at' => [
            'name' => 'Создан',
            'field_mode' => 'show',
            'is_filter_need' => false,
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
        'position' => 'asc',
    ],

];
