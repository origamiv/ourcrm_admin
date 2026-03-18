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
        'id' => 2008,

        // Название в меню
        'name' => 'Права пользователей',

        // Уникальный ключ модуля
        'shortname' => 'main.permissionuser',

        // Родительский раздел
        'parent_id' => 2000,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/main/permissionuser',

        // API endpoint
        'api' => 'https://main.our24.ru/api/permissionuser',

        // Eloquent модель
        'model' => 'App\\Models\\PermissionUser',

        // Иконка меню
        'icon' => 'uil uil-user-square',

        // ACL / permissions resource
        'resource' => null,

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
    | Fields — бизнес-поля сущности
    |--------------------------------------------------------------------------
    */
    'fields' => [

        'user_id' => [
            'name' => 'Пользователь',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://main.our24.ru/api/user',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'permission_id' => [
            'name' => 'Право',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://main.our24.ru/api/permission',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
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
