<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteModuleServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function map()
    {
        $this->mapRoutes();
    }

    /**
     * Define the "api" routes for the application.
     * These routes are typically stateless.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    protected function mapRoutes($modulesPath = null)
    {
        $moduleDirectoryPath = (!$modulesPath || is_null($modulesPath)) ? app_path('Modules') : $modulesPath;
        $dirCollections = File::directories($moduleDirectoryPath);


        foreach ($dirCollections as $dirPath) {
            $appPath = app_path();
            $namespaceId = substr($dirPath, strpos($dirPath, $appPath) + strlen($appPath) + 1);

            $namespaceId = strtr('App\\:moduleName\\ControllerAPI', array(':moduleName' => $namespaceId));
            $namespaceId = str_replace('/', '\\', $namespaceId);

            $namespaceId = $this->loadPath($namespaceId, $dirPath);
            $childModulePath = $dirPath . '/Modules';

            if (!file_exists($childModulePath) || !is_dir($childModulePath)) {
                continue;
            }

            $this->mapRoutes($childModulePath);
        }

        return true;
    }

    /**
     * Load 3 file routes
     *
     * @param string $namespace
     * @param string $modulePath
     * @return void
     * @author
     */
    protected function loadPath($namespace, $modulePath)
    {
        $apiRoutePath = rtrim($modulePath, '/') . '/routes/api.php';
        if (!file_exists($apiRoutePath) || !is_file($apiRoutePath)) {
            return;
        }

        Route::prefix('api')
            ->as('api.')
            ->middleware('api')
            ->namespace($namespace)
            ->group($apiRoutePath);
    }

}
