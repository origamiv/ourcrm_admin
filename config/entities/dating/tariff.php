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
        'id' => 2029,

        // Название в меню
        'name' => 'Тарифы',

        // Уникальный ключ модуля
        'shortname' => 'dating.tariff',

        // Родительский раздел
        'parent_id' => 2025,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 1,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/dating/tariff',

        // API endpoint
        'api' => 'https://dating.our24.ru/api/tariff',

        // Eloquent модель
        'model' => 'Modules\\Dating\\Models\\Tariff',

        // Иконка меню
        'icon' => 'uil uil-telegram-alt',

        // ACL / permissions resource
        'resource' => 'dating_tariff',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => null,

        // Справочник
        'is_list' => 1,
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
        'worker_id' => [
            'name' => 'Исполнитель',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

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

        'fakename' => [
            'name' => 'Псевдоним',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'duration' => [
            'name' => 'Длительность',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'sum' => [
            'name' => 'Сумма',
            'control' => 'text',
            'db_type' => 'integer',
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
