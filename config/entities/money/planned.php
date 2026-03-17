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
        'id' => 40,

        // Название в меню
        'name' => 'Операции',

        // Уникальный ключ модуля
        'shortname' => 'money.planned',

        // Родительский раздел
        'parent_id' => 31,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/money/planned',

        // API endpoint
        'api' => 'https://money.our24.ru/api/money',

        // Eloquent модель
        'model' => 'Modules\\Money\\Models\\Money',

        // Иконка меню
        'icon' => 'uil uil-money-bill',

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
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'direction' => [
            'name' => 'Направление',
            'field_model' => '/Modules/Money/Models/Direction',
            'field_items' => 'directions',
            'field_prop' => 'direction',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://money.our24.ru/api/direction',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'is_template' => [
            'name' => 'Является шаблоном',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'operation_group_id' => [
            'name' => 'Группа операций',
            'field_model' => '/Modules/Money/Models/OperationGroup',
            'field_items' => 'opgroups',
            'field_prop' => 'opgroup',
            'lookup_api' => 'https://money.our24.ru/api/operationsgroup',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'user_id' => [
            'name' => 'Пользователь',
            'field_model' => '/Modules/Main/Models/User',
            'field_items' => 'users',
            'field_prop' => 'user',
            'lookup_api' => 'https://main.our24.ru/api/user',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'planned_date' => [
            'name' => 'Плановая дата',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'sum' => [
            'name' => 'Сумма',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'sum_planned' => [
            'name' => 'Планируемая сумма',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'schet_id' => [
            'name' => 'Счет',
            'field_model' => '/Modules/Money/Models/Schet',
            'field_items' => 'schets',
            'field_prop' => 'schet',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://money.our24.ru/api/schet',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'client_id' => [
            'name' => 'Клиент',
            'field_model' => '/Modules/Clients/Models/Client',
            'field_items' => 'clients',
            'field_prop' => 'client',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://clients.our24.ru/api/client',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'pay_date' => [
            'name' => 'Фактическая дата',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'notification' => [
            'name' => 'Уведомления',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Неизвестно',
                ],
                [
                    'id' => 1,
                    'name' => 'Да',
                ],
                [
                    'id' => 2,
                    'name' => 'Нет',
                ],
            ],
            'field_mode' => 'create,edit',
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Запланировано',
                ],
                [
                    'id' => 1,
                    'name' => 'Исполнено',
                ],
                [
                    'id' => 2,
                    'name' => 'Не исполнено',
                ],
                [
                    'id' => 3,
                    'name' => 'В процессе',
                ],
                [
                    'id' => 4,
                    'name' => 'Фактическая оплата',
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
