<?php namespace App\Providers;

/**
 * Class JiraServiceProvider
 *
 * Service Provider
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\ServiceProvider;

class JiraServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('jira',function() {
            return new \App\Helpers\Jira;
        });

        $this->app->booting(function() {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Jira', 'App\Facades\Jira');
        });
    }
}