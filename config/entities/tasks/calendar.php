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
        'id' => 123,

        // Название в меню
        'name' => 'Календарь',

        // Уникальный ключ модуля
        'shortname' => 'tasks.calendar',

        // Родительский раздел
        'parent_id' => 122,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/tasks/calendar',

        // API endpoint
        'api' => 'https://tasks.our24.ru/api/task',

        // Eloquent модель
        'model' => 'Modules\\Tasks\\Models\\Task',

        // Иконка меню
        'icon' => 'uil uil-money-bill',

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
        'source_id' => [
            'name' => 'Источник',
            'field_model' => '/Modules/Tasks/Models/Source',
            'field_items' => 'sources',
            'field_prop' => 'source',
            'lookup_api' => 'https://tasks.itstaffer.ru/api/source',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'ext_task_nom' => [
            'name' => 'Номер задачи внешний',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'name' => [
            'name' => 'Наименование задачи',
            'modifier' => 'link',
            'template' => 'https://app.moo.team/WSHq5qy6Ei/projects/{projectId}/tasks/{ext_task_nom}',
            'modifier_field' => 'url',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'worker_id' => [
            'name' => 'Сотрудник',
            'field_model' => '/Modules/Tasks/Models/Worker',
            'field_items' => 'workers',
            'field_prop' => 'worker',
            'lookup_api' => 'https://tasks.itstaffer.ru/api/worker',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'descr' => [
            'name' => 'Описание задачи',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_model' => '/Modules/Tasks/Models/TaskStatus',
            'field_items' => 'workers',
            'field_prop' => 'worker',
            'lookup_api' => 'https://tasks.itstaffer.ru/api/taskstatus',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'last_ext_updated' => [
            'name' => 'Последнее обновление',
            'modifier' => 'time_ago',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status_ext' => [
            'name' => 'Внешний статус',
            'field_model' => '/Modules/Tasks/Models/ExtStatus',
            'field_items' => 'extstatuss',
            'field_prop' => 'extstatus',
            'lookup_api' => 'https://tasks.itstaffer.ru/api/extstatus',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'iteration' => [
            'name' => 'Итерация',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'plan_time' => [
            'name' => 'Запланированное время',
            'modifier' => 'time',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'fact_time' => [
            'name' => 'Фактическое время',
            'modifier' => 'time',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'src' => [
            'name' => 'JSON',
            'field_mode' => 'show',
            'control' => 'json',
            'db_type' => 'string',
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
