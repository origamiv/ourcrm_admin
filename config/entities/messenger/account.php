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
        'id' => 1549,

        // Название в меню
        'name' => 'Аккаунты',

        // Уникальный ключ модуля
        'shortname' => 'messenger.account',

        // Родительский раздел
        'parent_id' => 1548,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/chats/accounts',

        // API endpoint
        'api' => 'https://chats.our24.ru/api/account',

        // Eloquent модель
        'model' => 'Modules\\Messenger\\Models\\Account',

        // Иконка меню
        'icon' => 'uil uil-users-alt',

        // ACL / permissions resource
        'resource' => null,

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
        'name' => [
            'name' => 'Название',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Короткое',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'login' => [
            'name' => 'Логин',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'code' => [
            'name' => 'Код',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'password' => [
            'name' => 'Пароль',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_2fa' => [
            'name' => 'Использовать 2FA',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'pass2fa' => [
            'name' => 'Пароль 2ФА',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'tas_port' => [
            'name' => 'TAS port',
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
