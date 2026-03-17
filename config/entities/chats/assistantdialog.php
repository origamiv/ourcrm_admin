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
        'id' => 2011,

        // Название в меню
        'name' => 'Диалоги AI ассистентов',

        // Уникальный ключ модуля
        'shortname' => 'messenger.assistant_dialogs',

        // Родительский раздел
        'parent_id' => 1550,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 3,

        // Web-страница
        'page' => '/web/chats/assistantdialog',

        // API endpoint
        'api' => 'https://chats.our24.ru/api/assistantdialog',

        // Eloquent модель
        'model' => 'Modules\\Messenger\\Models\\AssistantDialog',

        // Иконка меню
        'icon' => 'uil uil-exchange',

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

        'query' => [
            'name' => 'Запрос',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'answer' => [
            'name' => 'Ответ',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'direction' => [
            'name' => 'Направление',
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
