<?php namespace App\Providers;

/**
 * Class GridServiceProvider
 *
 * Service Provider
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Mophie H2Pro
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\ServiceProvider;

class GridServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('grid',function() {
            return new \App\Helpers\Grid;
        });

        $this->app->booting(function() {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Grid', 'App\Facades\Grid');
        });
    }
}