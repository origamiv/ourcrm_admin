<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Common — описание модуля Keitaro
    |--------------------------------------------------------------------------
    */
    'common' => [

        // ID записи в menus
        'id' => 6000,

        // Название в меню
        'name' => 'Keitaro',

        // Уникальный ключ модуля
        'shortname' => 'keitaro',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => null,

        // Уровень вложенности
        'level' => 1,

        // Web-страница
        'page' => null,

        // API endpoint
        'api' => null,

        // Eloquent / Domain модель
        'model' => null,

        // Иконка меню
        'icon' => 'uil uil-chart-bar',

        // ACL / permissions resource
        'resource' => 'keitaro',

        // Активен
        'status' => null,

        // Порядок в меню
        'nom' => 12,

        // Не справочник
        'is_list' => null,
    ],

];
