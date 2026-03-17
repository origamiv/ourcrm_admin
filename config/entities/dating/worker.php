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
        'id' => 2030,

        // Название в меню
        'name' => 'Исполнители',

        // Уникальный ключ модуля
        'shortname' => 'dating.worker',

        // Родительский раздел
        'parent_id' => 2025,

        // Корневая сущность
        'is_root' => 1,

        // Создавать API
        'is_api' => 1,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/dating/worker',

        // API endpoint
        'api' => 'https://dating.our24.ru/api/worker',

        // Eloquent модель
        'model' => 'Modules\\Dating\\Models\\Worker',

        // Иконка меню
        'icon' => 'uil uil-telegram-alt',

        // ACL / permissions resource
        'resource' => 'dating_worker',

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => null,

        // Справочник
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
            'name' => 'Имя',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'photo' => [
            'name' => 'Фото',
            'field_mode' => 'index,create,edit,show',
            'control' => 'image',
            'db_type' => 'jsonb',
            'is_lookup' => false,
        ],

        'pol' => [
            'name' => 'Пол',
            'field_mode' => 'create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => false,
            'field_items' => ['1' => 'Женский', '2' => 'Мужской'],
            'field_default' => 1,
        ],

        'age' => [
            'name' => 'Возраст',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'rost' => [
            'name' => 'Рост',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'ves' => [
            'name' => 'Вес',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'city_id' => [
            'name' => 'Город',
            'control' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://address.our24.ru/api/city',
            'lookup_name' => 'name',
        ],

        'bust_size' => [
            'name' => 'Размер груди',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'bust_id' => [
            'name' => 'Грудь',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'eye_id' => [
            'name' => 'Глаза',
            'field_mode' => 'create,edit,show',
            'control' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://dating.our24.ru/api/eye',
            'lookup_name' => 'name',
        ],

        'body_id' => [
            'name' => 'Телосложение',
            'field_mode' => 'create,edit,show',
            'control' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://dating.our24.ru/api/body',
            'lookup_name' => 'name',
        ],

        'look_id' => [
            'name' => 'Внешность',
            'field_mode' => 'create,edit,show',
            'control' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://dating.our24.ru/api/look',
            'lookup_name' => 'name',
        ],

        'hair_id' => [
            'name' => 'Волосы',
            'field_mode' => 'create,edit,show',
            'control' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://dating.our24.ru/api/hair',
            'lookup_name' => 'name',
        ],

        'orientation_id' => [
            'name' => 'Ориентация',
            'field_mode' => 'create,edit,show',
            'control' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://dating.our24.ru/api/orientation',
            'lookup_name' => 'name',
        ],

        'photos' => [
            'name' => 'Фотографии',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'jsonb',
            'is_lookup' => false,
        ],

        'cnt' => [
            'name' => 'Счётчик',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'telegram_peer_id' => [
            'name' => 'Telegram Peer ID',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'fake_telegram_peer_id' => [
            'name' => 'Фейк Telegram Peer ID',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'bot_chat_id' => [
            'name' => 'Chat ID бота',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'card_tinkoff' => [
            'name' => 'Карта Тинькофф',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'phone_tinkoff' => [
            'name' => 'Телефон Тинькофф',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'number_fix' => [
            'name' => 'Фиксированный номер',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'manager_id' => [
            'name' => 'Менеджер',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'user_id' => [
            'name' => 'Пользователь',
            'field_mode' => 'create,edit,show',
            'control' => 'lookup',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://main.our24.ru/api/user',
            'lookup_name' => 'name',
        ],

        'yandexdisk' => [
            'name' => 'Яндекс диск',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'worktime' => [
            'name' => 'Рабочее время',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_tatu' => [
            'name' => 'Татуировки',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_zagran' => [
            'name' => 'Загранпаспорт',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_viezd' => [
            'name' => 'Выезд',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_apart' => [
            'name' => 'Апартаменты',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_sng' => [
            'name' => 'СНГ',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_closed_party' => [
            'name' => 'Закрытые вечеринки',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_living' => [
            'name' => 'Проживание',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_work_today' => [
            'name' => 'Работает сегодня',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_work_tomorrow' => [
            'name' => 'Работает завтра',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'dat_check_work' => [
            'name' => 'Дата проверки работы',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'timestamp',
            'is_lookup' => false,
        ],

        'photos_for_reklam' => [
            'name' => 'Фото для рекламы',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_calls' => [
            'name' => 'Звонки',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_datingsites' => [
            'name' => 'Сайты знакомств',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
            'is_lookup' => false,
        ],

        'is_intim' => [
            'name' => 'Интим',
            'field_mode' => 'create,edit,show',
            'control' => 'checkbox',
            'db_type' => 'boolean',
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
