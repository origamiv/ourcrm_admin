<?php

return [
    'common' => [
        'id' => 4010,
        'name' => 'Продукты',
        'shortname' => 'products',
        'parent_id' => 0,
        'is_root' => 1,
        'is_api' => 2,
        'level' => 1,
        'page' => '/products',
        'api' => '/api/products',
        'model' => 'App\\Models\\Product',
        'icon' => 'uil uil-box',
        'resource' => 'products',
        'status' => 1,
        'nom' => 20,
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
            'name' => 'Название',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'shortname' => [
            'name' => 'Код',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,edit,show',
            'control' => 'text',
            'formatter' => 'badge',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
    ],

    'order' => [
        'id' => 'asc',
    ],
];
