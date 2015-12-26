<?php namespace App\Facades;

/**
 * Class Grid
 *
 * Facade
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    TestPlanner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\Facades\Facade;

class Grid extends Facade
{
    /**
     * Region Facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'grid';
    }
}