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
        'id' => 1413,

        // Название в меню
        'name' => 'Кандидаты',

        // Уникальный ключ модуля
        'shortname' => 'staff.candidat',

        // Родительский раздел
        'parent_id' => 1410,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => 2,

        // Уровень вложенности
        'level' => 2,

        // Web-страница
        'page' => '/web/staff/candidat',

        // API endpoint
        'api' => 'https://staff.our24.ru/api/candidate',

        // Eloquent модель
        'model' => 'Modules\\Staff\\Models\\Candidat',

        // Иконка меню
        'icon' => 'uil uil-graduation-cap',

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
        'vacancy_id' => [
            'name' => 'Вакансия',
            'field_model' => '/Modules/Staff/Models/Vacancy',
            'field_items' => 'vacancies',
            'field_prop' => 'vacancy',
            'lookup_api' => 'https://staff.itstaffer.ru/api/vacancies',
            'lookup_id' => 'id',
            'lookup_name' => 'name',
            'field_mode' => 'index,create,edit,show',
            'control' => 'select',
            'db_type' => 'integer',
            'is_lookup' => true,
        ],

        'name' => [
            'name' => 'ФИО кандидата',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'resume_name' => [
            'name' => 'Наименование резюме',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'gender' => [
            'name' => 'Пол',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Не указан',
                ],
                [
                    'id' => 1,
                    'name' => 'Мужчина',
                ],
                [
                    'id' => 2,
                    'name' => 'Женщина',
                ],
            ],
            'field_mode' => 'index,create,edit,show',
            'control' => 'status',
            'db_type' => 'integer',
            'is_lookup' => false,
        ],

        'salary' => [
            'name' => 'Желаемая ЗП',
            'field_mode' => 'index,create,edit,show',
            'control' => 'text',
            'db_type' => 'string',
            'is_lookup' => false,
        ],

        'status' => [
            'name' => 'Статус',
            'field_items' => [
                [
                    'id' => 0,
                    'name' => 'Не разобран',
                ],
                [
                    'id' => 1,
                    'name' => 'Успешное прохождение',
                ],
                [
                    'id' => 2,
                    'name' => 'Отказ по разным причинам',
                ],
                [
                    'id' => 3,
                    'name' => 'Первичный контакт',
                ],
                [
                    'id' => 4,
                    'name' => 'Тестовое задание',
                ],
                [
                    'id' => 5,
                    'name' => 'Собеседование',
                ],
                [
                    'id' => 6,
                    'name' => 'Предложение работы',
                ],
                [
                    'id' => 7,
                    'name' => 'Выход на работу',
                ],
                [
                    'id' => 8,
                    'name' => 'Подумать',
                ],
                [
                    'id' => 9,
                    'name' => 'Не подходит',
                ],
                [
                    'id' => 10,
                    'name' => 'Отказ',
                ],
                [
                    'id' => 11,
                    'name' => 'Не выходит на связь',
                ],
                [
                    'id' => 12,
                    'name' => 'Перевод на другую вакансию',
                ],
                [
                    'id' => 13,
                    'name' => 'Вакансия закрыта',
                ],
            ],
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
