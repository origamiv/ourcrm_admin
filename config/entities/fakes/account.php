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
        'id' => 178,

        // Название в меню
        'name' => 'Аккаунты',

        // Уникальный ключ модуля
        'shortname' => 'fakes.account',

        // Родительский раздел
        'parent_id' => 175,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/fakes/account',

        // API endpoint
        'api' => 'https://fakes.our24.ru/api/account',

        // Eloquent модель
        'model' => 'Modules\\Fakes\\Models\\Account',

        // Иконка меню
        'icon' => 'uil uil-user',

        // ACL / permissions resource
        'resource' => null,

        // Активен
        'status' => 1,

        // Порядок в меню
        'nom' => null,

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
        'id' => [
            'name' => 'ID',
            'field_mode' => 'index,show',
            'control' => 'integer',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

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

        'service_id' => [
            'name' => 'Сервис',
            'field_model' => '/Modules/Fakes/Models/Service',
            'field_items' => 'services',
            'field_prop' => 'service',
            'field_mode' => 'index,create,edit,show',
            'field_api' => '/api/fakes/service',
            'field_name' => 'name',
            'field_id' => 'id',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => '/api/fakes/service',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'fake_user_id' => [
            'name' => 'Персонаж',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'host' => [
            'name' => 'Хост',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'login' => [
            'name' => 'Логин',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'password' => [
            'name' => 'Пароль',
            'field_mode' => 'create,edit,show',
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

        'token' => [
            'name' => 'Токен',
            'field_mode' => 'create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'options' => [
            'name' => 'Настройки',
            'field_mode' => 'create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'comment' => [
            'name' => 'Комментарий',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'cnt' => [
            'name' => 'Кол-во использований',
            'field_mode' => 'index,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'dat_last_run' => [
            'name' => 'Время последнего использования',
            'field_mode' => 'index,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'tags' => [
            'name' => 'Тэги',
            'field_mode' => 'create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
            'field_items' => [
                [
                    'id' => '0',
                    'name' => 'Неизвестно',
                ],
                [
                    'id' => '1',
                    'name' => 'Активен',
                ],
                [
                    'id' => '2',
                    'name' => 'Блокирован',
                ],
                [
                    'id' => '3',
                    'name' => 'Используется',
                ],
                [
                    'id' => '4',
                    'name' => 'Забанен',
                ],
            ],
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
