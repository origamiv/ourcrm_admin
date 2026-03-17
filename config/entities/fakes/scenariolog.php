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
        'id' => 188,

        // Название в меню
        'name' => 'Логи сценариев',

        // Уникальный ключ модуля
        'shortname' => 'fakes.scenariolog',

        // Родительский раздел
        'parent_id' => 176,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 3,

        // Web-страница
        'page' => '/web/fakes/scenariolog',

        // API endpoint
        'api' => 'https://fakes.our24.ru/api/scenariolog',

        // Eloquent модель
        'model' => 'Modules\\Fakes\\Models\\ScenarioLog',

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
        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'control' => 'integer',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'name' => [
            'name' => 'Сообщение',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'shortname' => [
            'name' => 'Короткое сообщение',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'scenario_id' => [
            'name' => 'Сценарий',
            'field_model' => '/Modules/Fakes/Models/Scenario',
            'field_items' => 'scenarios',
            'field_prop' => 'scenario',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/fakes/scenario',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'run_id' => [
            'name' => 'Запуск',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'bot_id' => [
            'name' => 'Бот',
            'field_model' => '/Modules/Fakes/Models/Bot',
            'field_items' => 'bots',
            'field_prop' => 'bot',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/fakes/bot',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'action' => [
            'name' => 'Действие',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'session' => [
            'name' => 'Сессия',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'field_items' => [
                [
                    'id' => '0',
                    'name' => 'Неизвестно',
                ],
                [
                    'id' => '1',
                    'name' => 'Успешно',
                ],
                [
                    'id' => '2',
                    'name' => 'Ошибка',
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
