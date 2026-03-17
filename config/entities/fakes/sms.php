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
        'id' => 192,

        // Название в меню
        'name' => 'SMS коды',

        // Уникальный ключ модуля
        'shortname' => 'fakes.sms',

        // Родительский раздел
        'parent_id' => 175,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/fakes/sms',

        // API endpoint
        'api' => 'https://fakes.our24.ru/api/sms',

        // Eloquent модель
        'model' => 'Modules\\Fakes\\Models\\Sms',

        // Иконка меню
        'icon' => 'uil uil-user',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => null,

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
        'account_id' => [
            'name' => 'Аккаунт',
            'field_model' => '/Modules/Fakes/Models/Account',
            'field_items' => 'accounts',
            'field_prop' => 'account',
            'field_mode' => 'index,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/fakes/account',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'name' => [
            'name' => 'Сообщение',
            'field_mode' => 'show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Короткое сообщение',
            'field_mode' => 'show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'date_message' => [
            'name' => 'Время сообщение',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'phone' => [
            'name' => 'Телефон',
            'field_mode' => 'show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'from' => [
            'name' => 'От кого',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'text' => [
            'name' => 'Сообщение',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'mess_id' => [
            'name' => 'ID сообщения',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'code' => [
            'name' => 'код',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
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
