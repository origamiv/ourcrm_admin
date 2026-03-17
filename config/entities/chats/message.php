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
        'id' => 1560,

        // Название в меню
        'name' => 'Сообщения',

        // Уникальный ключ модуля
        'shortname' => 'messenger.message',

        // Родительский раздел
        'parent_id' => 1548,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 1,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/chats/message',

        // API endpoint
        'api' => 'https://chats.our24.ru/api/message',

        // Eloquent модель
        'model' => 'Modules\\Messenger\\Models\\Message',

        // Иконка меню
        'icon' => 'uil uil-comment-lines',

        // ACL / permissions resource
        'resource' => 'messenger.message',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 1,

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
            'lookup_api' => 'https://chats.our24.ru/api/account',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'bot_id' => [
            'name' => 'Бот',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'message_id' => [
            'name' => 'ИД сообщения',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'napr' => [
            'name' => 'Направление',
            'field_mode' => 'index',
            'field_items' => [
                [
                    'id' => '0',
                    'name' => 'Неизвестно',
                ],
                [
                    'id' => '1',
                    'name' => 'Исх.',
                ],
                [
                    'id' => '2',
                    'name' => 'Вх.',
                ],
            ],
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'message' => [
            'name' => 'Сообщение',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'channel_id' => [
            'name' => 'Канал',
            'field_model' => '/Modules/Messenger/Models/Channel',
            'field_items' => 'channels',
            'field_prop' => 'channel',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://chats.our24.ru/api/channel',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
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
