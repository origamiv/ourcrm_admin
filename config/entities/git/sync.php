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
        'id' => 291,

        // Название в меню
        'name' => 'Синхронизации',

        // Уникальный ключ модуля
        'shortname' => 'git.sync',

        // Родительский раздел
        'parent_id' => 287,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/git/sync',

        // API endpoint
        'api' => 'https://git.our24.ru/api/sync',

        // Eloquent модель
        'model' => 'App\\Models\\Sync',

        // Иконка меню
        'icon' => 'uil uil-sync',

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
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Короткое',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'repo_client_id' => [
            'name' => 'Репо клиента',
            'field_model' => '/App/Models/Repo',
            'field_items' => 'repo',
            'field_prop' => 'repo',
            'lookup_api' => 'https://git.our24.ru/api/repo',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'repo_our_id' => [
            'name' => 'Репо наш',
            'field_model' => '/Modules/Tools/Models/Repo',
            'field_items' => 'repo',
            'field_prop' => 'repo',
            'lookup_api' => 'https://git.our24.ru/api/repo',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'user_name' => [
            'name' => 'Имя пользователя',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'user_email' => [
            'name' => 'E-mail пользователя',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Новая',
                ],
                [
                    'id' => 1,
                    'name' => 'Включена',
                ],
                [
                    'id' => 2,
                    'name' => 'Выключена',
                ],
                [
                    'id' => 3,
                    'name' => 'В процессе',
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
