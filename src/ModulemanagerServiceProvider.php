<?php

namespace ExtensionsValley\Modulemanager;

use Illuminate\Support\ServiceProvider;

class ModulemanagerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
   public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'Modulemanager');

        $this->publishes([
            __DIR__ . '/Database/migrations' => $this->app->databasePath() . '/migrations',
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/Database/seeds' => $this->app->databasePath() . '/seeds',
        ], 'seeds');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
         // Load all routes
        foreach (new \DirectoryIterator(__DIR__ . '/Routes/') as $fileInfo) {
            if (!$fileInfo->isDot()) {
                include __DIR__ . '/Routes/' . $fileInfo->getFilename();
            }
        }
          // Load all events
        foreach (new \DirectoryIterator(__DIR__ . '/Events/') as $fileInfo) {
            if (!$fileInfo->isDot()) {
                include __DIR__ . '/Events/' . $fileInfo->getFilename();
            }
        }
    }
}
