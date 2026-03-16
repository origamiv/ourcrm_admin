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
        'id' => 2009,

        // Название в меню
        'name' => 'Ассистенты',

        // Уникальный ключ модуля
        'shortname' => 'messenger.assistants',

        // Родительский раздел
        'parent_id' => 1548,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/chats/assistant',

        // API endpoint
        'api' => 'https://chats.our24.ru/api/assistant',

        // Eloquent модель
        'model' => 'Modules\\Messenger\\Models\\Assistant',

        // Иконка меню
        'icon' => 'uil uil-robot',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 3,

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

        'prompt' => [
            'name' => 'Инструкция',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'data' => [
            'name' => 'Данные',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'func' => [
            'name' => 'Функция',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'ext_id' => [
            'name' => 'ID ассистента',
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
