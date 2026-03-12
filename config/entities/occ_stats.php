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
        'api' => '/api/v1/occ_stats',
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
    | Fields — поля согласно Google Таблице
    |--------------------------------------------------------------------------
    | Столбцы:
    | Дата, Баер, Оффер, Гео, Лиды, Валид, Брак, % Валида, Чек-лист (шт), Чек-лист (%),
    | Оценка, Комментарий, Ссылка на запись
    */
    'fields' => [
        'date' => [
            'name' => 'Дата',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'date',
            'is_lookup' => false,
        ],
        'buyer' => [
            'name' => 'Баер',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
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
        'leads_count' => [
            'name' => 'Лиды',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
        'valid_count' => [
            'name' => 'Валид',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
        'trash_count' => [
            'name' => 'Брак',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
        'valid_percent' => [
            'name' => '% Валида',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'checklist_count' => [
            'name' => 'Чек-лист (шт)',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
        'checklist_percent' => [
            'name' => 'Чек-лист (%)',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'score' => [
            'name' => 'Оценка',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'comment' => [
            'name' => 'Комментарий',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'record_link' => [
            'name' => 'Ссылка на запись',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'modifier' => 'link',
            'template' => '{value}',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
    ],

    'order' => [
        'date' => 'desc',
    ],
];
