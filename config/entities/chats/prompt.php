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
        'id' => 2014,

        // Название в меню
        'name' => 'Промпты',

        // Уникальный ключ модуля
        'shortname' => 'messenger.prompts',

        // Родительский раздел
        'parent_id' => 1550,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 3,

        // Web-страница
        'page' => '/web/chats/prompt',

        // API endpoint
        'api' => 'https://chats.our24.ru/api/prompt',

        // Eloquent модель
        'model' => 'Modules\\Messenger\\Models\\Prompt',

        // Иконка меню
        'icon' => 'uil uil-file-alt',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 8,

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

        'tag' => [
            'name' => 'Тег',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'category' => [
            'name' => 'Категория',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'message' => [
            'name' => 'Промпт',
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

    /*
    |--------------------------------------------------------------------------
    | Default order
    |--------------------------------------------------------------------------
    */
    'order' => [
        'id' => 'asc',
    ],

];
