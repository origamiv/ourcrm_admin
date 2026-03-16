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
        'id' => 1557,

        // Название в меню
        'name' => 'Каналы',

        // Уникальный ключ модуля
        'shortname' => 'messenger.channels',

        // Родительский раздел
        'parent_id' => 1548,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/chats/channels',

        // API endpoint
        'api' => 'https://chats.our24.ru/api/channel',

        // Eloquent модель
        'model' => 'Modules\\Messenger\\Models\\Channel',

        // Иконка меню
        'icon' => 'uil uil-channel',

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
        'account_id' => [
            'name' => 'Аккаунт',
            'field_model' => '/Modules/Messenger/Models/Account',
            'field_items' => 'accounts',
            'field_prop' => 'account',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://chats.itstaffer.ru/api/account',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

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

        'channel' => [
            'name' => 'Канал',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'username' => [
            'name' => 'Юзернейм',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'phone' => [
            'name' => 'Телефон',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'type_channel' => [
            'name' => 'Тип канала',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'cnt' => [
            'name' => 'Кол-во сообщений',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'cnt_people' => [
            'name' => 'Кол-во участников',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'src' => [
            'name' => 'Все данные',
            'field_mode' => 'create,edit,show',
            'control' => 'json',
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
