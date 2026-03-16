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
        'id' => 2005,

        // Название в меню
        'name' => 'Иконки',

        // Уникальный ключ модуля
        'shortname' => 'main.file',

        // Родительский раздел
        'parent_id' => 2000,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/main/file',

        // API endpoint
        'api' => 'https://main.our24.ru/api/file',

        // Eloquent модель
        'model' => 'App\\Models\\File',

        // Иконка меню
        'icon' => 'uil uil-image',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 5,

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

        'path' => [
            'name' => 'Путь',
            'field_mode' => 'index,create,edit,show',
            'control' => 'file',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'category' => [
            'name' => 'Категория',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'size' => [
            'name' => 'Размер',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'ext' => [
            'name' => 'Расширение',
            'field_mode' => 'index,create,edit,show',
            'control' => 'dropzone',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'user_id' => [
            'name' => 'Пользователь',
            'field_mode' => 'index,create,edit,show',
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
