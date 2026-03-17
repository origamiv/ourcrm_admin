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
        'id' => 180,

        // Название в меню
        'name' => 'Действия ботов',

        // Уникальный ключ модуля
        'shortname' => 'fakes.botaction',

        // Родительский раздел
        'parent_id' => 179,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 3,

        // Web-страница
        'page' => '/web/fakes/botaction',

        // API endpoint
        'api' => 'https://fakes.our24.ru/api/botaction',

        // Eloquent модель
        'model' => 'Modules\\Fakes\\Models\\BotAction',

        // Иконка меню
        'icon' => 'uil uil-user',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => null,

        // Не справочник
        'is_list' => 1,
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

        'shortname' => [
            'name' => 'Короткое название',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'descr' => [
            'name' => 'Описание',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'cnt' => [
            'name' => 'Кол-во использований',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'bot_id' => [
            'name' => 'Бот',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'lastrun' => [
            'name' => 'Время последнего запуска',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'options' => [
            'name' => 'Настройки',
            'field_mode' => 'index,create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
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
