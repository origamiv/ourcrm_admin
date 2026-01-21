<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Common — описание сущности / модуля / пункта меню
    |--------------------------------------------------------------------------
    | Это отражение записи из таблицы menus
    */
    'common' => [

        // ID записи в menus
        'id' => 4002,

        // Название в меню
        'name' => 'Кампании',

        // Уникальный ключ модуля
        'shortname' => 'campaigns',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/campaigns',

        // API endpoint
        'api' => '/api/v1/campaigns',

        // Eloquent модель
        'model' => 'App\\Models\\Campaign',

        // Иконка меню
        'icon' => 'uil uil-bullhorn',

        // ACL / permissions resource
        'resource' => 'campaigns',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 11,

        // Не справочник
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
            'control' => 'status',
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

        'group_id' => [
            'name' => 'Группа',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'domain_id' => [
            'name' => 'Домен',
            'field_mode' => 'index,create,edit,show',
            'is_filter_need' => true,
            'control' => 'lookup',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => true,
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

        'position' => [
            'name' => 'Позиция',
            'field_mode' => 'index,edit,show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => 'number',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

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
            'control' => 'datetime',
            'formatter' => 'date',
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'updated_at' => [
            'name' => 'Обновлена',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'datetime',
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
