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
        'id' => 135,

        // Название в меню
        'name' => 'Сотрудники',

        // Уникальный ключ модуля
        'shortname' => 'tasks.worker',

        // Родительский раздел
        'parent_id' => 122,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/tasks/worker',

        // API endpoint
        'api' => 'https://tasks.our24.ru/api/worker',

        // Eloquent модель
        'model' => 'Modules\\Tasks\\Models\\Worker',

        // Иконка меню
        'icon' => 'uil uil-user',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 1,

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
            'name' => 'ФИО',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'user_id' => [
            'name' => 'Пользователь',
            'field_model' => '/Modules/Main/Models/User',
            'field_items' => 'users',
            'field_prop' => 'user',
            'lookup_api' => '/api/main/user',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'ext_id' => [
            'name' => 'Внешний ID пользователя',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'oformlenie_id' => [
            'name' => 'Тип оформления',
            'field_model' => '/Modules/Money/Models/Oformlenie',
            'field_items' => 'oformlenies',
            'field_prop' => 'oformlenie',
            'lookup_api' => '/api/money/oformlenie',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'phone' => [
            'name' => 'Номер телефона',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'inn' => [
            'name' => 'ИНН',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_moderate' => [
            'name' => 'Модерация QUGO',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Неизвестно',
                ],
                [
                    'id' => 1,
                    'name' => 'Да',
                ],
                [
                    'id' => 2,
                    'name' => 'Нет',
                ],
            ],
            'field_mode' => 'index,create,edit,show',
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'rate' => [
            'name' => 'Ставка руб.час',
            'field_mode' => 'create,edit,show',
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

        'is_koef_nalog' => [
            'name' => 'Учет налога',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Неизвестно',
                ],
                [
                    'id' => 1,
                    'name' => 'Да',
                ],
                [
                    'id' => 2,
                    'name' => 'Нет',
                ],
            ],
            'field_mode' => 'index,create,edit,show',
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_koef_iter' => [
            'name' => 'Учет 1-ой итерации',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Неизвестно',
                ],
                [
                    'id' => 1,
                    'name' => 'Да',
                ],
                [
                    'id' => 2,
                    'name' => 'Нет',
                ],
            ],
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
