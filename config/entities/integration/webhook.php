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
        'id' => 1319,

        // Название в меню
        'name' => 'Вебхуки',

        // Уникальный ключ модуля
        'shortname' => 'integration.webhook',

        // Родительский раздел
        'parent_id' => 1313,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/integration/webhook',

        // API endpoint
        'api' => 'https://integration.our24.ru/api/webhook',

        // Eloquent модель
        'model' => 'Modules\\Integration\\Models\\Webhook',

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
        'name' => [
            'name' => 'Название',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Короткое название',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'service_id' => [
            'name' => 'Сервис',
            'field_model' => '/Modules/Integration/Models/Service',
            'field_items' => 'services',
            'field_prop' => 'service',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://integration.our24.ru/api/service',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'type_hook_id' => [
            'db_type' => 'integer',
            'name' => 'Тип хука',
            'control' => 'select',
            'is_lookup' => true,
            'lookup_api' => 'https://integration.our24.ru/api/typehook',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'url' => [
            'name' => 'URL',
            'field_mode' => 'index,create,edit,show',
            'control' => 'string',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'params' => [
            'name' => 'Параметры',
            'field_mode' => 'index,create,edit,show',
            'control' => 'json',
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
