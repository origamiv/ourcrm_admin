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
        'id' => 134,

        // Название в меню
        'name' => 'Задачи',

        // Уникальный ключ модуля
        'shortname' => 'tasks.task',

        // Родительский раздел
        'parent_id' => 122,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/tasks/task',

        // API endpoint
        'api' => 'https://tasks.our24.ru/api/task',

        // Eloquent модель
        'model' => 'Modules\\Tasks\\Models\\Task',

        // Иконка меню
        'icon' => 'uil uil-user',

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
        'source_id' => [
            'name' => 'Источник',
            'field_model' => '/Modules/Tasks/Models/Source',
            'field_items' => 'sources',
            'field_prop' => 'source',
            'lookup_api' => 'https://tasks.itstaffer.ru/api/source',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'lookup_icon' => 'icon',
            'modifier' => 'icon',
            'title' => 'icon',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'project_id' => [
            'name' => 'Проект',
            'field_model' => '/Modules/Tasks/Models/Project',
            'field_items' => 'projects',
            'field_prop' => 'project',
            'lookup_api' => 'https://tasks.itstaffer.ru/api/project',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'lookup_icon' => 'icon',
            'title' => 'icon',
            'modifier' => 'icon',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'priority_id' => [
            'name' => 'Приоритет',
            'field_model' => '/Modules/Tasks/Models/Priority',
            'field_items' => 'priorities',
            'field_prop' => 'priority',
            'lookup_api' => 'https://tasks.itstaffer.ru/api/priority',
            'lookup_id' => 'id',
            'lookup_name' => 'icon',
            'modifier' => 'icon',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'ext_task_nom' => [
            'name' => 'Номер задачи внешний',
            'field_mode' => 'create,edit,show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_urgent' => [
            'name' => 'Срочная',
            'field_items' => [
                [
                    'id' => '0',
                    'name' => 'Не срочно',
                    'style' => [
                        'borderColor' => '#9A9A9A',
                        'color' => '#9A9A9A',
                    ],
                ],
                [
                    'id' => '1',
                    'name' => '🔥Срочная',
                    'style' => [
                        'backgroundColor' => '#FCE0AD',
                        'color' => '#FF7700',
                    ],
                ],
            ],
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_important' => [
            'name' => 'Важная',
            'field_items' => [
                [
                    'id' => '0',
                    'name' => 'Не важно',
                    'style' => [
                        'borderColor' => '#9A9A9A',
                        'color' => '#9A9A9A',
                    ],
                ],
                [
                    'id' => '1',
                    'name' => 'Важно',
                    'style' => [
                        'backgroundColor' => '#FFC8BA',
                        'color' => '#FF3300',
                    ],
                ],
            ],
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'progress' => [
            'name' => 'Прогресс',
            'field_mode' => 'index,edit,show',
            'is_filter_need' => false,
            'control' => 'progress',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_our' => [
            'name' => 'Наша',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'name' => [
            'name' => 'Наименование задачи',
            'modifier' => 'link',
            'template' => '/web/tasks/task/{id}',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'url' => [
            'name' => 'URL',
            'modifier' => 'link',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
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
            'is_filter_need' => true,
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'workers' => [
            'name' => 'Сотрудники',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'users',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'activity' => [
            'name' => 'активность',
            'field_mode' => 'index',
            'is_filter_need' => false,
            'control' => 'week_table',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'descr' => [
            'name' => 'Описание задачи',
            'field_mode' => 'create,edit,show',
            'is_filter_need' => false,
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
            'is_filter_need' => true,
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'last_ext_updated' => [
            'name' => 'Последнее обновление',
            'modifier' => 'time_ago',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
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
            'is_filter_need' => false,
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'iteration' => [
            'name' => 'Итерация',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'plan_time' => [
            'name' => 'Запланированное время',
            'title' => 'icon',
            'icon' => 'icon1.jpg',
            'modifier' => 'time',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'fact_time' => [
            'name' => 'Фактическое время',
            'title' => 'icon',
            'icon' => 'icon1.jpg',
            'modifier' => 'time',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'sum' => [
            'name' => 'Оплата',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
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
