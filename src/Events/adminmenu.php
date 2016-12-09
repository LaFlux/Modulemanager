<?php
namespace ExtensionsValley\Modulemanager\Events;


\Event::listen('admin.menu.groups', function ($collection) {

    $collection->put('extensionsvalley.modulemanager', [
        'menu_text' => 'Module Manager'
        , 'menu_icon' => '<i class="fa fa-puzzle-piece"></i>'
        , 'acl_key' => 'extensionsvalley.modulemanager.modulepanel'
        , 'sub_menu' => [
            '0' => [
                'link' => '/admin/extensionsValley/modulemanager/positionmanager'
                , 'menu_text' => 'Manage Modules'
                , 'acl_key' => 'extensionsvalley.modulemanager.modulemanager'
            ],

        ],
    ]);


});
