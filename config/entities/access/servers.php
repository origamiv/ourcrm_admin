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
        'id' => 153,

        // Название в меню
        'name' => 'Серверы',

        // Уникальный ключ модуля
        'shortname' => 'access.servers',

        // Родительский раздел
        'parent_id' => 149,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/access/server',

        // API endpoint
        'api' => 'https://access.our24.ru/api/server',

        // Eloquent модель
        'model' => 'Modules\\Access\\Models\\Server',

        // Иконка меню
        'icon' => 'uil uil-servers',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => null,

        // Не справочник
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
            'field_mode' => 'index,create,edit,show',
            'validation' => [
                'rules' => [
                    'create' => 'nullable|string',
                    'update' => 'nullable|string',
                ],
                'messages' => [
                    'string' => 'Значение должно быть строкой',
                ],
            ],
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Короткое',
            'field_mode' => 'create,edit,show',
            'validation' => [
                'rules' => [
                    'create' => 'nullable|string',
                    'update' => 'nullable|string',
                ],
                'messages' => [
                    'string' => 'Значение должно быть строкой',
                ],
            ],
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'validation' => [
                'rules' => [
                    'create' => 'nullable|integer',
                    'update' => 'nullable|integer',
                ],
                'messages' => [
                    'integer' => 'Значение должно быть integer',
                ],
            ],
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
