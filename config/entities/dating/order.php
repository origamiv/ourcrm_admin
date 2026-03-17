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
        'id' => 2027,

        // Название в меню
        'name' => 'Заказы',

        // Уникальный ключ модуля
        'shortname' => 'dating.order',

        // Родительский раздел
        'parent_id' => 2025,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 1,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/dating/order',

        // API endpoint
        'api' => 'https://dating.our24.ru/api/order',

        // Eloquent модель
        'model' => 'Modules\\Dating\\Models\\Order',

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

        'date_order' => [
            'name' => 'Дата заказа',
            'control' => 'text',
            'db_type' => 'date',
            'is_lookup' => false,
        ],

        'time_from' => [
            'name' => 'Время начала',
            'control' => 'text',
            'db_type' => 'time',
            'is_lookup' => false,
        ],

        'time_to' => [
            'name' => 'Время окончания',
            'control' => 'text',
            'db_type' => 'time',
            'is_lookup' => false,
        ],

        'worker_id' => [
            'name' => 'Исполнитель',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'tariff_id' => [
            'name' => 'Тариф',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'place_id' => [
            'name' => 'Место',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'client_id' => [
            'name' => 'Клиент',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'plan_sum' => [
            'name' => 'Плановая сумма',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'fact_sum' => [
            'name' => 'Фактическая сумма',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'comission' => [
            'name' => 'Комиссия',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_payed_comission' => [
            'name' => 'Комиссия оплачена',
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
