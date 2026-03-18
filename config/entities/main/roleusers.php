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
        'id' => 2007,

        // Название в меню
        'name' => 'Роли пользователей',

        // Уникальный ключ модуля
        'shortname' => 'main.roleusers',

        // Родительский раздел
        'parent_id' => 2000,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/main/roleusers',

        // API endpoint
        'api' => 'https://main.our24.ru/api/roleuser',

        // Eloquent модель
        'model' => 'App\\Models\\RoleUser',

        // Иконка меню
        'icon' => 'uil uil-user-check',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 4,

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

        'role_id' => [
            'name' => 'Роль',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://main.our24.ru/api/role',
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
