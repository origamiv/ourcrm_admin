<?php

return [

    'common' => [
        'id' => 2032,
        'name' => 'Телосложения',
        'shortname' => 'dating.body',
        'parent_id' => 2031,
        'is_root' => 1,
        'is_api' => 1,
        'level' => 3,
        'page' => '/web/dating/body',
        'api' => 'https://dating.our24.ru/api/body',
        'model' => 'Modules\\Dating\\Models\\Body',
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
