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
        'id' => 1427,

        // Название в меню
        'name' => 'Все вакансии',

        // Уникальный ключ модуля
        'shortname' => 'staff.workplace',

        // Родительский раздел
        'parent_id' => 1410,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/staff/workplace',

        // API endpoint
        'api' => 'https://staff.our24.ru/api/workplace',

        // Eloquent модель
        'model' => 'App\\Models\\Workplace',

        // Иконка меню
        'icon' => 'uil uil-briefcase',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 2,

        // Не справочник
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
    | Fields — бизнес-поля сущности
    |--------------------------------------------------------------------------
    */
    'fields' => [
        'name' => [
            'name' => 'Название',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'code' => [
            'name' => 'Код',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'link' => [
            'name' => 'Ссылка на вакансию',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
            'field_mode' => 'create,edit,show',
        ],

        'descr' => [
            'name' => 'Описание',
            'field_mode' => 'create,edit,show',
            'modifier' => 'pre',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default order
    |--------------------------------------------------------------------------
    */
    'order' => [
        'id' => 'asc',
    ],

];
