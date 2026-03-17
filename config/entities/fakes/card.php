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
        'id' => 184,

        // Название в меню
        'name' => 'Карточки',

        // Уникальный ключ модуля
        'shortname' => 'fakes.card',

        // Родительский раздел
        'parent_id' => 175,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/fakes/card',

        // API endpoint
        'api' => 'https://fakes.our24.ru/api/card',

        // Eloquent модель
        'model' => 'Modules\\Fakes\\Models\\Card',

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

        'type_card_id' => [
            'name' => 'Тип карточки',
            'field_mode' => 'index,create,edit,show',
            'field_model' => '/Modules/Fakes/Models/TypeCard',
            'field_items' => 'typecards',
            'field_prop' => 'typecard',
            'field_api' => '/api/fakes/typecard',
            'field_name' => 'name',
            'field_id' => 'id',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'src' => [
            'name' => 'Исходник',
            'field_mode' => 'create,edit,show',
            'control' => 'json',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_mode' => 'index,create,edit,show',
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
