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
        'id' => 288,

        // Название в меню
        'name' => 'Ветки',

        // Уникальный ключ модуля
        'shortname' => 'git.branch',

        // Родительский раздел
        'parent_id' => 287,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/git/branch',

        // API endpoint
        'api' => 'https://git.our24.ru/api/branch',

        // Eloquent модель
        'model' => 'App\\Models\\Branch',

        // Иконка меню
        'icon' => 'uil uil-sitemap',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 3,

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
        'repo_id' => [
            'name' => 'Репо',
            'field_model' => '/App/Models/Repo',
            'field_items' => 'repo',
            'field_prop' => 'repo',
            'lookup_api' => '/api/git/repo',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'name' => [
            'name' => 'Название',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'isOur' => [
            'name' => 'Чей репо',
            'field_items' => [
                [
                    'id' => '0',
                    'name' => 'Не указано',
                    'style' => [
                        'borderColor' => '#9A9A9A',
                        'color' => '#9A9A9A',
                    ],
                ],
                [
                    'id' => '1',
                    'name' => 'Наш',
                    'style' => [
                        'backgroundColor' => '#FCE0AD',
                        'color' => 'black',
                    ],
                ],
                [
                    'id' => '2',
                    'name' => 'Клиентский',
                    'style' => [
                        'backgroundColor' => '#3498db',
                        'color' => 'white',
                    ],
                ],
            ],
            'field_mode' => 'index,show',
            'control' => 'status',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'dat_last_commit' => [
            'name' => 'Время коммита',
            'field_mode' => 'index,create,edit',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'comment' => [
            'name' => 'Комментарий',
            'field_mode' => 'index,create,edit',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'author' => [
            'name' => 'Автор коммита',
            'field_mode' => 'index,create,edit',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'last_commit' => [
            'name' => 'Хеш коммита',
            'field_mode' => 'index,create,edit',
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
