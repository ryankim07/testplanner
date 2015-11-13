<?php namespace App\Facades;

/**
 * Class Utils
 *
 * Facade
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\Facades\Facade;

class Utils extends Facade
{
    /**
     * Region Facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'utils';
    }
}