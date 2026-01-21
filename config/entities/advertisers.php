<?php

return [

    'layout' => [
        'filter_view' => 'title',
    ],

    'fields' => [

        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'name' => [
            'name' => 'Название рекламодателя',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'state' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'status',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'postback_url' => [
            'name' => 'Postback URL',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'offer_param' => [
            'name' => 'Параметр оффера',
            'field_mode' => 'create,edit,show',
            'is_filter_need' => false,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'template_name' => [
            'name' => 'Шаблон',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'ext_id' => [
            'name' => 'Внешний ID',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'offers' => [
            'name' => 'Кол-во офферов',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'notes' => [
            'name' => 'Заметки',
            'field_mode' => 'create,edit,show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'created_at' => [
            'name' => 'Дата создания',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'datetime',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'updated_at' => [
            'name' => 'Дата обновления',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'datetime',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'products' => [
            'name' => 'Продукты',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'relation',
            'db_type' => 'array',
            'is_lookup' => false,
        ],
    ],

    'order' => [
        'id' => 'asc',
    ],

];
