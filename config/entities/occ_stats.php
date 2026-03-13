<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Common — описание сущности / модуля / пункта меню
    |--------------------------------------------------------------------------
    */
    'common' => [
        'id' => 5001, // Произвольный ID для меню
        'name' => 'Статистика ОКК',
        'shortname' => 'occ_stats',
        'parent_id' => 5000, // Будет ID для "Отчеты"
        'is_root' => 2,
        'is_api' => 2,
        'level' => 2,
        'page' => '/occ_stats',
        'api' => '/api/v1/stat-okk',
        'model' => 'App\\Models\\OccStat',
        'icon' => 'uil uil-chart-bar',
        'resource' => 'occ_stats',
        'status' => 1,
        'nom' => 10,
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
    | Fields — поля согласно новым требованиям
    |--------------------------------------------------------------------------
    */
    'column_groups' => [
        ['id' => 1, 'name' => 'Продажа', 'headerHozAlign' => 'center'],
        ['id' => 2, 'name' => 'Регистрация', 'headerHozAlign' => 'center'],
        ['id' => 3, 'name' => '', 'headerHozAlign' => 'center'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fields — поля согласно новым требованиям
    |--------------------------------------------------------------------------
    */
    'fields' => [
        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
        ],
        'report_date' => [
            'name' => 'Дата отчета',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'date',
            'formatter' => 'datetime',
            'formatter_options' => [
                'outputFormat' => 'DD.MM.YY',
            ],
            'is_lookup' => false,
        ],
        'sale_date' => [
            'name' => 'Дата',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'date',
            'formatter' => 'datetime',
            'formatter_options' => [
                'outputFormat' => 'DD.MM.YY',
            ],
            'is_lookup' => false,
            'column_group' => 1,
        ],
        'sale_time' => [
            'name' => 'Время',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'time',
            'formatter' => 'datetime',
            'formatter_options' => [
                'outputFormat' => 'HH:mm',
            ],
            'is_lookup' => false,
            'column_group' => 1,
        ],
        'registration_date' => [
            'name' => 'Дата',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'date',
            'formatter' => 'datetime',
            'formatter_options' => [
                'outputFormat' => 'DD.MM.YY',
            ],
            'is_lookup' => false,
            'column_group' => 2,
        ],
        'registration_time' => [
            'name' => 'Время',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'time',
            'formatter' => 'datetime',
            'formatter_options' => [
                'outputFormat' => 'HH:mm',
            ],
            'is_lookup' => false,
            'column_group' => 2,
        ],
        'offer' => [
            'name' => 'Оффер',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'geo' => [
            'name' => 'Гео',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'sale_amount' => [
            'name' => 'Сумма продажи',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => false,
            'control' => 'number',
            'db_type' => 'numeric',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'sub_id' => [
            'name' => 'Sub ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'fd' => [
            'name' => 'FD',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'rd' => [
            'name' => 'RD',
            'field_mode' => 'index,show,edit',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'user_id' => [
            'name' => 'User ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'click_id' => [
            'name' => 'Click ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'lead_id' => [
            'name' => 'Lead ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'conversion_id' => [
            'name' => 'Conversion ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'created_at' => [
            'name' => 'Создано',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'timestamp',
            'formatter' => 'datetime',
            'formatter_options' => [
                'outputFormat' => 'DD.MM.YY HH:mm',
            ],
            'is_lookup' => false,
            'column_group' => 3,
        ],
        'updated_at' => [
            'name' => 'Обновлено',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'timestamp',
            'formatter' => 'datetime',
            'formatter_options' => [
                'outputFormat' => 'DD.MM.YY HH:mm',
            ],
            'is_lookup' => false,
            'column_group' => 3,
        ],
    ],

    'order' => [
        'report_date' => 'desc',
    ],

    'group' => [
        'field' => 'report_date',
    ],

    /*
    |--------------------------------------------------------------------------
    | Aggregation — верхняя таблица для отчета ОКК
    |--------------------------------------------------------------------------
    */
    'aggregation' => [
        'title' => 'Агрегация по офферам',
        'api' => '/api/v1/stat-okk/agg',
        'columns' => [
            ['title' => 'Оффер', 'field' => 'offer', 'width' => 250],
            ['title' => 'Сумма продажи', 'field' => 'sale_amount', 'hozAlign' => 'right'],
            ['title' => 'Кол-во депозитов', 'field' => 'deposits_count', 'hozAlign' => 'right'],
            ['title' => 'Сумма выплаты', 'field' => 'payout_amount', 'hozAlign' => 'right'],
        ],
    ],
];
