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
        'id' => 1424,

        // Название в меню
        'name' => 'Сотрудники',

        // Уникальный ключ модуля
        'shortname' => 'staff.staff',

        // Родительский раздел
        'parent_id' => 1410,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/staff/staff',

        // API endpoint
        'api' => 'https://staff.our24.ru/api/staff',

        // Eloquent модель
        'model' => 'Modules\\Staff\\Models\\Staff',

        // Иконка меню
        'icon' => 'uil uil-user-md',

        // ACL / permissions resource
        'resource' => null,

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
        'name' => [
            'name' => 'Название',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'user_id' => [
            'name' => 'Пользователь',
            'field_model' => '/Modules/Main/Models/User',
            'field_items' => 'users',
            'field_prop' => 'user',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://main.our24.ru/api/user',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'company_id' => [
            'name' => 'Компания',
            'field_model' => '/Modules/Main/Models/Company',
            'field_items' => 'companies',
            'field_prop' => 'company',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://main.our24.ru/api/company',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'speciality_id' => [
            'name' => 'Специальность',
            'field_model' => '/Modules/Staff/Models/Speciality',
            'field_items' => 'specialities',
            'field_prop' => 'speciality',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://staff.our24.ru/api/speciality',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'grade_id' => [
            'name' => 'Грейд',
            'field_model' => '/Modules/Staff/Models/Grade',
            'field_items' => 'grades',
            'field_prop' => 'grade',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
            'lookup_api' => 'https://staff.our24.ru/api/grade',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
        ],

        'price_month' => [
            'name' => 'Цена',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'price_h' => [
            'name' => 'Цена в час',
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
