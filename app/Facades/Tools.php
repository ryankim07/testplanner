<?php namespace App\Facades;

/**
 * Class Tools
 *
 * Facade
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\Facades\Facade;

class Tools extends Facade
{
    /**
     * Region Facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tools';
    }
}