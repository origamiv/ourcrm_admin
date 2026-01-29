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
        'id' => 4001,

        // Название в меню
        'name' => 'Рекламодатели',

        // Уникальный ключ модуля
        'shortname' => 'advertisers',

        // Родительский раздел (например "Маркетинг")
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/advertisers',

        // API endpoint
        'api' => '/api/v1/advertisers',

        // Eloquent / Domain модель
        'model' => 'App\\Models\\Advertiser',

        // Иконка меню
        'icon' => 'uil uil-megaphone',

        // ACL / permissions resource
        'resource' => 'advertisers',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 10,

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
    | Fields — бизнес-поля сущности Advertiser
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

        'state' => [
            'name' => 'Статус',
            'field_mode' => 'index,edit,show',
            'is_filter_need' => true,
            'control' => 'status',
            'formatter' => 'badge',
            'formatter_options' => [
                'active'   => 'badge-outline-success',
                'inactive' => 'badge-outline-danger',
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'offers' => [
            'name' => 'Оффер',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            "is_lookup"=> true,
            "lookup_api"=> "/api/v1/offers",
            "lookup_id"=> "id",
            "lookup_name"=>"name"
        ],

        'postback_url' => [
            'name' => 'Postback URL',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => 'truncate',
            'formatter_options' => [
                'length' => 60,
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'offer_param' => [
            'name' => 'Параметр оффера',
            'field_mode' => 'create,edit,show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'template_name' => [
            'name' => 'Шаблон',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'ext_id' => [
            'name' => 'Ext ID',
            'field_mode' => 'index,edit,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'notes' => [
            'name' => 'Заметки',
            'field_mode' => 'create,edit,show',
            'is_filter_need' => false,
            'control' => 'textarea',
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
        'id' => 'asc',
    ],

];
