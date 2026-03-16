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
        'id' => 4005,

        // Название в меню
        'name' => 'Домены',

        // Уникальный ключ модуля
        'shortname' => 'domains',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/domains',

        // API endpoint
        'api' => '/api/domains',

        // Eloquent модель
        'model' => 'App\\Models\\Domain',

        // Иконка меню
        'icon' => 'uil uil-globe',

        // ACL / permissions resource
        'resource' => 'domains',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 14,

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
    | Fields — бизнес-поля сущности Domain
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
            'name' => 'Домен',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_ssl' => [
            'name' => 'SSL',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
//            'formatter' => 'badge',
//            'formatter_options' => [
//                false  => 'badge-outline-warning',
//                true => 'badge-outline-success',
//            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'state' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'badge',
            'formatter_options' => [
                'active'   => 'badge-outline-success',
                'inactive' => 'badge-outline-danger',
            ],
            'db_type' => 'string',
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
