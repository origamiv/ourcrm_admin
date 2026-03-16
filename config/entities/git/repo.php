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
        'id' => 290,

        // Название в меню
        'name' => 'Репозитории',

        // Уникальный ключ модуля
        'shortname' => 'git.repo',

        // Родительский раздел
        'parent_id' => 287,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/git/repo',

        // API endpoint
        'api' => 'https://git.our24.ru/api/repo',

        // Eloquent модель
        'model' => 'App\\Models\\Repo',

        // Иконка меню
        'icon' => 'uil uil-server',

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

        'url' => [
            'name' => 'URL',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_our' => [
            'name' => 'Наш',
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
            'field_mode' => 'index,create,edit,show',
            'control' => 'status',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Новый',
                ],
                [
                    'id' => 1,
                    'name' => 'Включен',
                ],
                [
                    'id' => 2,
                    'name' => 'Выключен',
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
