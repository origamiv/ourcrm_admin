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
        'id' => 4012,

        // Название в меню
        'name' => 'Клики',

        // Уникальный ключ модуля
        'shortname' => 'clicks',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => '/clicks',

        // API endpoint
        'api' => '/api/v1/clicks',

        // Eloquent модель
        'model' => 'App\\Models\\Click',

        // Иконка меню
        'icon' => 'uil uil-mouse-alt',

        // ACL / permissions resource
        'resource' => 'clicks',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 40,

        // Не справочник (лог событий)
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
    | Fields — бизнес-поля сущности Click
    |--------------------------------------------------------------------------
    */
    'fields' => [

        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'click_id' => [
            'name' => 'Click ID (ext)',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        /*
        |--------------------------------------------------------------------------
        | Остальные поля
        |--------------------------------------------------------------------------
        */
        'visitor_code' => [
            'name' => 'visitor_code',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
//
//        'campaign_id' => [
//            'name' => 'Кампания',
//            'field_mode' => 'index,create,edit,show',
//            'is_filter_need' => true,
//            'control' => 'lookup',
//            'formatter' => null,
//            'db_type' => 'integer',
//            'is_lookup' => true,
//            'lookup_api' => '/api/v1/campaigns',
//            'lookup_id' => 'id',
//            'lookup_name' => 'name',
//        ],

        'campaign' => [
            'name' => 'campaign',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'ext_campaign_id' => [
            'name' => 'ext_campaign_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],


//        'campaign_group_id' => [
//            'name' => 'campaign_group_id',
//            'field_mode' => 'show',
//            'is_filter_need' => false,
//            'control' => 'number',
//            'formatter' => null,
//            'db_type' => 'integer',
//            'is_lookup' => false,
//        ],

        'campaign_group' => [
            'name' => 'campaign_group',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

//        'stream_id' => [
//            'name' => 'Стрим',
//            'field_mode' => 'index,create,edit,show',
//            'is_filter_need' => true,
//            'control' => 'lookup',
//            'formatter' => null,
//            'db_type' => 'integer',
//            'is_lookup' => true,
//            'lookup_api' => '/api/v1/streams',
//            'lookup_id' => 'id',
//            'lookup_name' => 'name',
//        ],

        'stream' => [
            'name' => 'stream',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

//        'offer_id' => [
//            'name' => 'Оффер',
//            'field_mode' => 'index,create,edit,show',
//            'is_filter_need' => true,
//            'control' => 'lookup',
//            'formatter' => null,
//            'db_type' => 'integer',
//            'is_lookup' => true,
//            'lookup_api' => '/api/v1/offers',
//            'lookup_id' => 'id',
//            'lookup_name' => 'name',
//        ],

        'offer' => [
            'name' => 'offer',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'offer_ext_id' => [
            'name' => 'offer_ext_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],


//        'offer_group_id' => [
//            'name' => 'offer_group_id',
//            'field_mode' => 'show',
//            'is_filter_need' => false,
//            'control' => 'number',
//            'formatter' => null,
//            'db_type' => 'integer',
//            'is_lookup' => false,
//        ],

        'offer_group' => [
            'name' => 'offer_group',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],


        'landing' => [
            'name' => 'landing',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'landing_id' => [
            'name' => 'landing_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'landing_group' => [
            'name' => 'landing_group',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'landing_group_id' => [
            'name' => 'landing_group_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],
        'source' => [
            'name' => 'source',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'referrer' => [
            'name' => 'referrer',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'search_engine' => [
            'name' => 'search_engine',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'keyword' => [
            'name' => 'keyword',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

//        'geo_id' => [
//            'name' => 'GEO',
//            'field_mode' => 'index,create,edit,show',
//            'is_filter_need' => true,
//            'control' => 'lookup',
//            'formatter' => null,
//            'db_type' => 'integer',
//            'is_lookup' => true,
//            'lookup_api' => '/api/v1/geo',
//            'lookup_id' => 'id',
//            'lookup_name' => 'name',
//        ],

        'country' => [
            'name' => 'country',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'country_code' => [
            'name' => 'country_code',
            'field_mode' => 'index,show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'region' => [
            'name' => 'region',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'city' => [
            'name' => 'city',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'isp' => [
            'name' => 'isp',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'operator' => [
            'name' => 'operator',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'connection_type' => [
            'name' => 'connection_type',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'device_type' => [
            'name' => 'device_type',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'device_model' => [
            'name' => 'device_model',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'os' => [
            'name' => 'os',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'os_version' => [
            'name' => 'os_version',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'browser' => [
            'name' => 'browser',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],
        'browser_version' => [
            'name' => 'browser_version',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'user_agent' => [
            'name' => 'User Agent',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],

        'ip' => [
            'name' => 'IP',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'ip_mask1' => [
            'name' => 'ip_mask1',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'ip_mask2' => [
            'name' => 'ip_mask2',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_unique_stream' => [
            'name' => 'is_unique_stream',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],
        'is_unique_campaign' => [
            'name' => 'is_unique_campaign',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],
        'is_unique_global' => [
            'name' => 'is_unique_global',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],
        'is_bot' => [
            'name' => 'is_bot',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],
        'is_using_proxy' => [
            'name' => 'is_using_proxy',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'landing_clicked' => [
            'name' => 'landing_clicked',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'landing_clicked_at' => [
            'name' => 'landing_clicked_at',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'datetime',
            'formatter' => null,
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'is_reg' => [
            'name' => 'is_reg',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],
        'is_lead' => [
            'name' => 'is_lead',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],
        'is_sale' => [
            'name' => 'is_sale',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],
        'is_rejected' => [
            'name' => 'is_rejected',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'cost' => [
            'name' => 'cost',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'decimal',
            'is_lookup' => false,
        ],
        'revenue' => [
            'name' => 'revenue',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'decimal',
            'is_lookup' => false,
        ],
        'profit' => [
            'name' => 'profit',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'decimal',
            'is_lookup' => false,
        ],

        'deposit_revenue' => [
            'name' => 'deposit_revenue',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'decimal',
            'is_lookup' => false,
        ],
        'lead_revenue' => [
            'name' => 'lead_revenue',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'decimal',
            'is_lookup' => false,
        ],
        'sale_revenue' => [
            'name' => 'sale_revenue',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'decimal',
            'is_lookup' => false,
        ],
        'rejected_revenue' => [
            'name' => 'rejected_revenue',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'decimal',
            'is_lookup' => false,
        ],

        'params' => [
            'name' => 'Параметры',
            'field_mode' => 'show',
            'is_filter_need' => true,
            'control' => 'json',
            'formatter' => null,
            'db_type' => 'json',
            'is_lookup' => false,
        ],

        'destination' => [
            'name' => 'destination',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],

        'advertiser_id' => [
            'name' => 'advertiser_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'product_id' => [
            'name' => 'product_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'user_id' => [
            'name' => 'user_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'partner_id' => [
            'name' => 'partner_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'account_id' => [
            'name' => 'account_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'number',
            'formatter' => null,
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'ext_id' => [
            'name' => 'ext_id',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'clicked_at' => [
            'name' => 'Дата клика',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'datetime',
            'formatter' => null,
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        /*
  |--------------------------------------------------------------------------
  | SUB IDs (сгруппированы рядом)
  |--------------------------------------------------------------------------
  */
        'sub_id' => [
            'name' => 'sub_id',
            'field_mode' => 'index,show',
            'is_filter_need' => true,
            'control' => 'text',
            'formatter' => null,
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'sub_id_1' => [
            'name' => 'sub_id_1',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_2' => [
            'name' => 'sub_id_2',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_3' => [
            'name' => 'sub_id_3',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_4' => [
            'name' => 'sub_id_4',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_5' => [
            'name' => 'sub_id_5',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_6' => [
            'name' => 'sub_id_6',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_7' => [
            'name' => 'sub_id_7',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_8' => [
            'name' => 'sub_id_8',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_9' => [
            'name' => 'sub_id_9',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],
        'sub_id_10' => [
            'name' => 'sub_id_10',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'textarea',
            'formatter' => null,
            'db_type' => 'text',
            'is_lookup' => false,
        ],

        'sub_id_11' => ['name' => 'sub_id_11', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_12' => ['name' => 'sub_id_12', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_13' => ['name' => 'sub_id_13', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_14' => ['name' => 'sub_id_14', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_15' => ['name' => 'sub_id_15', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_16' => ['name' => 'sub_id_16', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_17' => ['name' => 'sub_id_17', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_18' => ['name' => 'sub_id_18', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_19' => ['name' => 'sub_id_19', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_20' => ['name' => 'sub_id_20', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_21' => ['name' => 'sub_id_21', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_22' => ['name' => 'sub_id_22', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_23' => ['name' => 'sub_id_23', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_24' => ['name' => 'sub_id_24', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_25' => ['name' => 'sub_id_25', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_26' => ['name' => 'sub_id_26', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_27' => ['name' => 'sub_id_27', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_28' => ['name' => 'sub_id_28', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_29' => ['name' => 'sub_id_29', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],
        'sub_id_30' => ['name' => 'sub_id_30', 'field_mode' => 'show', 'is_filter_need' => false, 'control' => 'textarea', 'formatter' => null, 'db_type' => 'text', 'is_lookup' => false],

        'sub_ids_extra' => [
            'name' => 'sub_ids_extra',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'json',
            'formatter' => null,
            'db_type' => 'json',
            'is_lookup' => false,
        ],


        'created_at' => [
            'name' => 'Создано',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'datetime',
            'formatter' => null,
            'db_type' => 'datetime',
            'is_lookup' => false,
        ],

        'updated_at' => [
            'name' => 'Обновлено',
            'field_mode' => 'show',
            'is_filter_need' => false,
            'control' => 'datetime',
            'formatter' => null,
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
        'clicked_at' => 'desc',
    ],

];
