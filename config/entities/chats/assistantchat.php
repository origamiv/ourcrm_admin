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
        'id' => 2010,

        // Название в меню
        'name' => 'Чаты AI ассистентов',

        // Уникальный ключ модуля
        'shortname' => 'messenger.assistant_chats',

        // Родительский раздел
        'parent_id' => 1548,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/chats/assistantchat',

        // API endpoint
        'api' => 'https://chats.our24.ru/api/assistantchat',

        // Eloquent модель
        'model' => 'Modules\\Messenger\\Models\\AssistantChat',

        // Иконка меню
        'icon' => 'uil uil-comment-alt-message',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => 4,

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

        'assistant_id' => [
            'name' => 'Ассистент',
            'field_model' => '/Modules/Messenger/Models/assistant',
            'field_items' => 'assistants',
            'field_prop' => 'assistant',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://chats.our24.ru/api/assistant',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

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

        'ext_thread_id' => [
            'name' => 'Тред ассистента',
            'control' => 'string',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'cnt' => [
            'name' => 'Количество',
            'control' => 'string',
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
