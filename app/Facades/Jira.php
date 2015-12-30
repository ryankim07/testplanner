<?php namespace App\Facades;

/**
 * Class Jira
 *
 * Facade
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\Facades\Facade;

class Jira extends Facade
{
    /**
     * Region Facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jira';
    }
}