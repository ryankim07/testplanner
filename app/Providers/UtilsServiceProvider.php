<?php namespace App\Providers;

/**
 * Class UtilsServiceProvider
 *
 * Service Provider
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\ServiceProvider;

class UtilsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('utils',function() {
            return new \App\Helpers\Utils;
        });

        $this->app->booting(function() {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Utils', 'App\Facades\Utils');
        });
    }
}