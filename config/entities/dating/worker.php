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

        'pol' => [
            'name' => 'Пол',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'age' => [
            'name' => 'Возраст',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'rost' => [
            'name' => 'Рост',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'ves' => [
            'name' => 'Вес',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'city_id' => [
            'name' => 'Город',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'bust_size' => [
            'name' => 'Размер груди',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'bust_id' => [
            'name' => 'Грудь',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'eye_id' => [
            'name' => 'Глаза',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'body_id' => [
            'name' => 'Телосложение',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'look_id' => [
            'name' => 'Внешность',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'hair_id' => [
            'name' => 'Волосы',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'orientation_id' => [
            'name' => 'Ориентация',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'photo' => [
            'name' => 'Фото',
            'control' => 'text',
            'db_type' => 'jsonb',
            'is_lookup' => false,
        ],

        'photos' => [
            'name' => 'Фотографии',
            'control' => 'text',
            'db_type' => 'jsonb',
            'is_lookup' => false,
        ],

        'cnt' => [
            'name' => 'Счётчик',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'telegram_peer_id' => [
            'name' => 'Telegram Peer ID',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'fake_telegram_peer_id' => [
            'name' => 'Фейк Telegram Peer ID',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'bot_chat_id' => [
            'name' => 'Chat ID бота',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'card_tinkoff' => [
            'name' => 'Карта Тинькофф',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'phone_tinkoff' => [
            'name' => 'Телефон Тинькофф',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'number_fix' => [
            'name' => 'Фиксированный номер',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'manager_id' => [
            'name' => 'Менеджер',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'user_id' => [
            'name' => 'Пользователь',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'yandexdisk' => [
            'name' => 'Яндекс диск',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'worktime' => [
            'name' => 'Рабочее время',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'is_tatu' => [
            'name' => 'Татуировки',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_zagran' => [
            'name' => 'Загранпаспорт',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_viezd' => [
            'name' => 'Выезд',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_apart' => [
            'name' => 'Апартаменты',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_sng' => [
            'name' => 'СНГ',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_closed_party' => [
            'name' => 'Закрытые вечеринки',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_living' => [
            'name' => 'Проживание',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_work_today' => [
            'name' => 'Работает сегодня',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_work_tomorrow' => [
            'name' => 'Работает завтра',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'dat_check_work' => [
            'name' => 'Дата проверки работы',
            'control' => 'text',
            'db_type' => 'timestamp',
            'is_lookup' => false,
        ],

        'photos_for_reklam' => [
            'name' => 'Фото для рекламы',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_calls' => [
            'name' => 'Звонки',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_datingsites' => [
            'name' => 'Сайты знакомств',
            'control' => 'text',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'is_intim' => [
            'name' => 'Интим',
            'control' => 'text',
            'db_type' => 'integer',
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
