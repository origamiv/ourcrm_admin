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
        'id' => 2002,

        // Название в меню
        'name' => 'Права доступа',

        // Уникальный ключ модуля
        'shortname' => 'main.permission',

        // Родительский раздел
        'parent_id' => 2009,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/main/permission',

        // API endpoint
        'api' => 'https://main.our24.ru/api/permission',

        // Eloquent модель
        'model' => 'App\\Models\\Permission',

        // Иконка меню
        'icon' => 'uil uil-lock',

        // ACL / permissions resource
        'resource' => 'permissions',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 2,

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

        'module_id' => [
            'name' => 'Модуль',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://main.our24.ru/api/installer/module',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'feature_id' => [
            'name' => 'Возможность',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://main.our24.ru/api/installer/feature',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'name' => [
            'name' => 'Название',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'slug' => [
            'name' => 'Служебное',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'resource' => [
            'name' => 'Ресурс',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
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
