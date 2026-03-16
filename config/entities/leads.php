<?php

return [
    'common' => [
        'id' => 4100,
        'name' => 'Лиды',
        'shortname' => 'leads',
        'parent_id' => 0,
        'is_root' => 1,
        'is_api' => 2,
        'level' => 1,
        'page' => '/leads',
        'api' => '/api/leads',
        'model' => 'App\\Models\\Lead',
        'icon' => 'uil uil-user-plus',
        'resource' => 'leads',
        'status' => 1,
        'nom' => 40,
        'is_list' => 2,
    ],

    'fields' => [
        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
        'click_id' => [
            'name' => 'Click ID',
            'field_mode' => 'index,show',
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/clicks',
            'lookup_id' => 'id',
            'lookup_name' => 'click_id',
        ],
        'status_id' => [
            'name' => 'Статус',
            'field_mode' => 'index,show',
            'control' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/lead_statuses',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],
        'created_at' => [
            'name' => 'Создан',
            'field_mode' => 'index,show',
            'control' => 'text',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],
    ],

    'order' => [
        'id' => 'desc',
    ],
];
