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
        'id' => 125,

        // Название в меню
        'name' => 'Внешние статусы',

        // Уникальный ключ модуля
        'shortname' => 'tasks.extstatus',

        // Родительский раздел
        'parent_id' => 124,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 3,

        // Web-страница
        'page' => '/web/tasks/extstatus',

        // API endpoint
        'api' => 'https://tasks.our24.ru/api/extstatus',

        // Eloquent модель
        'model' => 'Modules\\Tasks\\Models\\ExtStatus',

        // Иконка меню
        'icon' => 'uil uil-user',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 12,

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
        'source_id' => [
            'name' => 'Источник статуса',
            'field_model' => '/Modules/Tasks/Models/Source',
            'field_items' => 'sources',
            'field_prop' => 'source',
            'lookup_api' => 'https://tasks.our24.ru/api/source',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'status_name' => [
            'name' => 'Наименование группы',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'name' => [
            'name' => 'Наименование статуса',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status_our' => [
            'name' => 'Статус наш',
            'field_model' => '/Modules/Tasks/Models/TaskStatus',
            'field_items' => 'taskstatuses',
            'field_prop' => 'taskstatuses',
            'lookup_api' => 'https://tasks.our24.ru/api/taskstatus',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
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
