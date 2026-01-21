<?php

return [
    'common' => [
        'id' => 4020,
        'name' => 'GEO',
        'shortname' => 'geo',
        'parent_id' => 0,
        'is_root' => 1,
        'is_api' => 2,
        'level' => 1,
        'page' => '/geo',
        'api' => '/api/v1/geo',
        'model' => 'App\\Models\\Geo',
        'icon' => 'uil uil-map',
        'resource' => 'geo',
        'status' => 1,
        'nom' => 30,
        'is_list' => 1,
    ],

    'fields' => [
        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
        'name' => [
            'name' => 'Страна',
            'field_mode' => 'index,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'shortname' => [
            'name' => 'ISO',
            'field_mode' => 'index,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
    ],

    'order' => [
        'name' => 'asc',
    ],
];
