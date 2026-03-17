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
        'id' => 41,

        // Название в меню
        'name' => 'Счета',

        // Уникальный ключ модуля
        'shortname' => 'money.schet',

        // Родительский раздел
        'parent_id' => 31,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/money/schet',

        // API endpoint
        'api' => 'https://money.our24.ru/api/schet',

        // Eloquent модель
        'model' => 'Modules\\Money\\Models\\Schet',

        // Иконка меню
        'icon' => 'uil uil-invoice',

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
        'name' => [
            'name' => 'Название',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Короткое',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'service_id' => [
            'name' => 'Сервис',
            'field_model' => '/Modules/Pay/Models/Service',
            'field_items' => 'services',
            'field_prop' => 'service',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://pay.our24.ru/api/service',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'start_balance' => [
            'name' => 'Начальный остаток',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'balance' => [
            'name' => 'Баланс',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'valute_id' => [
            'name' => 'Валюта',
            'field_model' => '/Modules/Money/Models/Valute',
            'field_items' => 'valutes',
            'field_prop' => 'valute',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://money.our24.ru/api/valute',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'balance_in_rub' => [
            'name' => 'Баланс в рублях',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'checked_at' => [
            'name' => 'Время проверки',
            'field_mode' => 'index,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'src' => [
            'name' => 'Реквизиты',
            'field_mode' => 'show',
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
