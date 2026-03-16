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
        'id' => 4021,

        // Название в меню
        'name' => 'Пользователи',

        // Уникальный ключ модуля
        'shortname' => 'users',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/users',

        // API endpoint
        'api' => '/api/users',

        // Eloquent модель
        'model' => 'App\\Models\\User',

        // Иконка меню
        'icon' => 'uil uil-user',

        // ACL / permissions resource
        'resource' => 'users',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 5,

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
    | Fields — бизнес-поля сущности User
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
            'name' => 'Имя',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'email' => [
            'name' => 'Email',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'email',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'telegram_id' => [
            'name' => 'Telegram ID',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'badge',
            'formatter_options' => [
                1 => 'badge-outline-success',
                0 => 'badge-outline-danger',
            ],
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'created_at' => [
            'name' => 'Создан',
            'field_mode' => 'index,show',
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
        'id' => 'asc',
    ],

];
