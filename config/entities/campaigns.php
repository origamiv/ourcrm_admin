<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Common — описание сущности / модуля / пункта меню
    |--------------------------------------------------------------------------
    */
    'common' => [

        'id' => 4002,
        'name' => 'Кампании',
        'shortname' => 'campaigns',
        'parent_id' => 0,
        'is_root' => 1,
        'is_api' => 2,
        'level' => 1,
        'page' => '/campaigns',
        'api' => '/api/v1/campaigns',
        'model' => 'App\\Models\\Campaign',
        'icon' => 'uil uil-bullhorn',
        'resource' => 'campaigns',
        'status' => 1,
        'nom' => 11,
        'is_list' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */
    'layout' => [
        'filter_view' => 'title',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fields — бизнес-поля сущности Campaign
    |--------------------------------------------------------------------------
    */
    'fields' => [

        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'name' => [
            'name' => 'Название',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'alias' => [
            'name' => 'Алиас',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'state' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'badge',
            'formatter_options' => [
                'active'   => 'badge-outline-success',
                'paused'   => 'badge-outline-warning',
                'disabled' => 'badge-outline-danger',
            ],
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'type' => [
            'name' => 'Тип',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

//        'group_id' => [
//            'name' => 'Группа',
//            'field_mode' => 'index,create,edit,show',
//            'is_filter_need' => true,
//            'control' => 'lookup',
//            'formatter' => null,
//            'db_type' => 'integer',
//            'is_lookup' => true,
//            'lookup_api' => '/api/v1/groups',
//            'lookup_id' => 'id',
//            'lookup_name' => 'name',
//        ],

//        'domain_id' => [
//            'name' => 'Домен',
//            'field_mode' => 'index,create,edit,show',
//            'is_filter_need' => true,
//            'control' => 'lookup',
//            'formatter' => null,
//            'db_type' => 'integer',
//            'is_lookup' => true,
//            'lookup_api' => '/api/v1/domains',
//            'lookup_id' => 'id',
//            'lookup_name' => 'name',
//        ],
        'domain' => [
            'name' => 'Домен',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
//            'lookup_api' => '/api/v1/domains',
//            'lookup_id' => 'id',
//            'lookup_name' => 'name',
        ],

        'traffic_source_id' => [
            'name' => 'Источник трафика',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

//        'position' => [
//            'name' => 'Позиция',
//            'field_mode' => 'index,edit,show',
//            'is_filter_need' => false,
//            'control' => 'number',
//            'formatter' => 'number',
//            'db_type' => 'integer',
//            'is_lookup' => false,
//        ],

        'notes' => [
            'name' => 'Заметки',
            'field_mode' => 'edit,show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'created_at' => [
            'name' => 'Создана',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'updated_at' => [
            'name' => 'Обновлена',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default order
    |--------------------------------------------------------------------------
    */
    'order' => [
        'id' => 'desc',
    ],

];
