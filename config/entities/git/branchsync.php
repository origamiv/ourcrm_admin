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
        'id' => 289,

        // Название в меню
        'name' => 'Синхронизация веток',

        // Уникальный ключ модуля
        'shortname' => 'git.branchsync',

        // Родительский раздел
        'parent_id' => 287,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/git/branchsync',

        // API endpoint
        'api' => 'https://git.our24.ru/api/branchsync',

        // Eloquent модель
        'model' => 'App\\Models\\BranchSync',

        // Иконка меню
        'icon' => 'uil uil-exchange',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 4,

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

        'project_id' => [
            'name' => 'Проект',
            'field_model' => '/App/Models/Sync',
            'field_items' => 'syncs',
            'field_prop' => 'sync',
            'lookup_api' => '/api/git/sync',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'branch_our' => [
            'name' => 'Ветка наша',
            'field_mode' => 'index,create,edit',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'direction' => [
            'name' => 'Направление',
            'field_mode' => 'index,create,edit',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'branch_client' => [
            'name' => 'Ветка клиента',
            'field_mode' => 'index,create,edit',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'commit_name' => [
            'name' => 'Название коммита',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'type' => [
            'name' => 'Тип синхронизации',
            'field_mode' => 'index,create,edit,show',
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
