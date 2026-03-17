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
        'id' => 128,

        // Название в меню
        'name' => 'Логи',

        // Уникальный ключ модуля
        'shortname' => 'tasks.log',

        // Родительский раздел
        'parent_id' => 122,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/tasks/log',

        // API endpoint
        'api' => 'https://tasks.our24.ru/api/log',

        // Eloquent модель
        'model' => 'Modules\\Tasks\\Models\\Log',

        // Иконка меню
        'icon' => 'uil uil-user',

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
            'name' => 'Задача',
            'modifier' => 'link',
            'modifier_field' => 'url',
            'field_mode' => 'index,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'worker_id' => [
            'name' => 'Исполнитель',
            'field_model' => '/Modules/Tasks/Models/Worker',
            'field_items' => 'workers',
            'field_prop' => 'worker',
            'lookup_api' => 'https://tasks.our24.ru/api/worker',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'created_at' => [
            'name' => 'Дата и время',
            'modifier' => 'time_ago',
            'field_mode' => 'index,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'src_from' => [
            'name' => 'Исходный JSON',
            'field_mode' => 'edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'src_to' => [
            'name' => 'Конечный JSON',
            'field_mode' => 'edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'src' => [
            'name' => 'Только измененные поля',
            'field_mode' => 'edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'changes' => [
            'name' => 'Изменения задачи',
            'field_model' => '/Modules/Tasks/Models/LogStatus',
            'field_items' => 'logstatuses',
            'field_prop' => 'logstatus',
            'lookup_api' => 'https://tasks.our24.ru/api/logstatuses',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
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
