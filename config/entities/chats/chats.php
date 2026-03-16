<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Common — описание модуля / пункта меню
    |--------------------------------------------------------------------------
    */
    'common' => [

        // ID записи в menus
        'id' => 1548,

        // Название в меню
        'name' => 'Чаты',

        // Уникальный ключ модуля
        'shortname' => 'messenger_module',

        // URL-ключ модуля (из /web/{url_module}/...)
        'url_module' => 'chats',

        // Родительский раздел
        'parent_id' => 0,

        // Корневая сущность
        'is_root' => null,

        // Создавать API
        'is_api' => null,

        // Иконка меню
        'icon' => 'uil uil-bag',

        // ACL / permissions resource
        'resource' => 'messenger_module',

        // Активен
        'status' => null,

        // Порядок в меню
        'nom' => null,

        // Не справочник
        'is_list' => null,
    ],

];
