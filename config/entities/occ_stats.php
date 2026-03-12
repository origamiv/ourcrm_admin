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
    'fields' => [
        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
        ],
        'report_date' => [
            'name' => 'Дата отчета',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'date',
            'is_lookup' => false,
        ],
        'sale_date' => [
            'name' => 'Дата продажи',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'date',
            'is_lookup' => false,
        ],
        'sale_time' => [
            'name' => 'Время продажи',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'time',
            'is_lookup' => false,
        ],
        'registration_date' => [
            'name' => 'Дата регистрации',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'date',
            'is_lookup' => false,
        ],
        'registration_time' => [
            'name' => 'Время регистрации',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'time',
            'is_lookup' => false,
        ],
        'offer' => [
            'name' => 'Оффер',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'geo' => [
            'name' => 'Гео',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'sale_amount' => [
            'name' => 'Сумма продажи',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'number',
            'db_type' => 'numeric',
            'is_lookup' => false,
        ],
        'sub_id' => [
            'name' => 'Sub ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'fd' => [
            'name' => 'FD',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'rd' => [
            'name' => 'RD',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'user_id' => [
            'name' => 'User ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
        ],
        'click_id' => [
            'name' => 'Click ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
        ],
        'lead_id' => [
            'name' => 'Lead ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
        ],
        'conversion_id' => [
            'name' => 'Conversion ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'bigint',
            'is_lookup' => false,
        ],
        'created_at' => [
            'name' => 'Создано',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'timestamp',
            'is_lookup' => false,
        ],
        'updated_at' => [
            'name' => 'Обновлено',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'timestamp',
            'is_lookup' => false,
        ],
    ],

    'order' => [
        'report_date' => 'desc',
    ],
];
