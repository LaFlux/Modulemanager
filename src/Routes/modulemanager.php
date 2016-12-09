<?php

Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'auth:admin']], function () {
    Route::get('/extensionsValley/modulemanager/positionmanager', [
        'middleware' => 'acl',
        'name' => 'view modulemanager',
        'as' => 'extensionsvalley.admin.viewmodulemanager',
        'uses' => 'ExtensionsValley\Modulemanager\ModuleController@viewModuleManager',
    ]);

    Route::get('/extensionsValley/modulemanager/addmodules/{position}', [
        'name' => 'add modules',
        'as' => 'extensionsvalley.admin.addmodules',
        'uses' => 'ExtensionsValley\Modulemanager\ModuleController@addModules',
    ]);

    Route::get('/extensionsValley/modulemanager/getmoduleparam', [
        'name' => 'module param',
        'as' => 'extensionsvalley.admin.getmoduleparam',
        'uses' => 'ExtensionsValley\Modulemanager\ModuleController@getModuleParam',
    ]);

    Route::get('/extensionsValley/modulemanager/removemodules', [
        'middleware' => 'acl:trash',
        'name' => 'removemodules',
        'as' => 'extensionsvalley.admin.removemodules',
        'uses' => 'ExtensionsValley\Modulemanager\ModuleController@removeModules',
    ]);

    Route::post('/extensionsValley/modulemanager/savemodules/', [
        'middleware' => 'acl:add',
        'name' => 'save modules',
        'as' => 'extensionsvalley.admin.savemodules',
        'uses' => 'ExtensionsValley\Modulemanager\ModuleController@saveModules',
    ]);
});
