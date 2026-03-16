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
        'id' => 12,

        // Название в меню
        'name' => 'Завершенные оплаты',

        // Уникальный ключ модуля
        'shortname' => 'pay.paid',

        // Родительский раздел
        'parent_id' => 11,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/pay/paid',

        // API endpoint
        'api' => 'https://pay.our24.ru/api/payment',

        // Eloquent модель
        'model' => 'Modules\\Pay\\Models\\Payment',

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
        'task_id' => [
            'name' => 'ID задачи',
            'field_mode' => 'index,create,edit,show',
            'control' => 'integer',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'name' => [
            'name' => 'Наименование задачи',
            'modifier' => 'link',
            'modifier_field' => 'url',
            'field_mode' => 'index,create,edit,show',
            'style' => [
                'maxWidth' => '100px',
            ],
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'worker_id' => [
            'name' => 'Пользователь',
            'field_model' => '/Modules/Tasks/Models/Worker',
            'field_items' => 'workers',
            'field_prop' => 'worker',
            'lookup_api' => '/api/worker/worker',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'date_change' => [
            'name' => 'Дата изменения',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'iteration' => [
            'name' => 'Итерация',
            'field_mode' => 'index',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_nalog' => [
            'name' => 'Учет налога',
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
            'field_mode' => 'index',
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_iter' => [
            'name' => 'Учет итераций',
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
            'field_mode' => 'index',
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'sum' => [
            'name' => 'Сумма оплаты',
            'field_mode' => 'index,create,edit,show,itog',
            'itog' => 'sum',
            'control' => 'text',
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
                    'name' => 'Оплачено',
                ],
                [
                    'id' => 2,
                    'name' => 'Не оплачено',
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
