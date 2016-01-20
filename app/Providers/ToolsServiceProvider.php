<?php namespace App\Providers;

/**
 * Class ToolsServiceProvider
 *
 * Service Provider
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\ServiceProvider;

class ToolsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('tools',function() {
            return new \App\Helpers\Tools;
        });

        $this->app->booting(function() {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Tools', 'App\Facades\Tools');
        });
    }
}