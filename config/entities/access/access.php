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
        'id' => 150,

        // Название в меню
        'name' => 'Доступы',

        // Уникальный ключ модуля
        'shortname' => 'access.accesses',

        // Родительский раздел
        'parent_id' => 149,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/access/access',

        // API endpoint
        'api' => 'https://access.our24.ru/api/access',

        // Eloquent модель
        'model' => 'Modules\\Access\\Models\\Access',

        // Иконка меню
        'icon' => 'uil uil-key-skeleton',

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

        'group_id' => [
            'name' => 'Группа',
            'field_model' => '/Modules/Access/Models/Group',
            'field_items' => 'groups',
            'field_prop' => 'group',
            'field_mode' => 'index,create,edit,show',
            'lookup_api' => 'https://access.our24.ru/api/group',
            'lookup_name' => 'name',
            'validation' => [
                'rules' => [
                    'create' => 'nullable|integer',
                    'update' => 'nullable|integer',
                ],
                'messages' => [
                    'integer' => 'Значение должно быть integer',
                ],
            ],
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'host' => [
            'name' => 'Хост',
            'modifier' => 'link',
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

        'login' => [
            'name' => 'Имя пользователя',
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
            'modifier' => 'copy',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'pass' => [
            'name' => 'Пароль',
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
            'modifier' => 'copy',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'token' => [
            'name' => 'Токен',
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

        'descr' => [
            'name' => 'Описание',
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

        'server_id' => [
            'name' => 'Сервер',
            'field_model' => '/Modules/Access/Models/Server',
            'field_items' => 'servers',
            'field_prop' => 'server',
            'field_mode' => 'index,create,edit,show',
            'validation' => [
                'rules' => [
                    'create' => 'integer',
                    'update' => 'integer',
                ],
                'messages' => [
                    'integer' => 'Значение должно быть integer',
                ],
            ],
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://access.our24.ru/api/server',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'src' => [
            'name' => 'Api',
            'field_mode' => 'index,create,edit,show',
            'validation' => [
                'rules' => [
                    'create' => 'nullable|json',
                    'update' => 'nullable|json',
                ],
                'messages' => [
                    'json' => 'Значение должно быть json',
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
