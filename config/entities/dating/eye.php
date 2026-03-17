<?php

return [

    'common' => [
        'id' => 2033,
        'name' => 'Глаза',
        'shortname' => 'dating.eye',
        'parent_id' => 2031,
        'is_root' => 1,
        'is_api' => 1,
        'level' => 3,
        'page' => '/web/dating/eye',
        'api' => 'https://dating.our24.ru/api/eye',
        'model' => 'Modules\\Dating\\Models\\Eye',
        'icon' => 'uil uil-list-ul',
        'resource' => 'dating_tariff',
        'status' => 1,
        'nom' => null,
        'is_list' => 1,
    ],

    'layout' => [
        'filter_view' => 'advanced',
    ],

    'fields' => [
        'name' => [
            'name' => 'Название',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'shortname' => [
            'name' => 'Короткое',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'status' => [
            'name' => 'Статус',
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
    ],

    'order' => [
        'id' => 'asc',
    ],

];
