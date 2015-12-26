<?php namespace App\Facades;

/**
 * Class Email
 *
 * Facade
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\Facades\Facade;

class Email extends Facade
{
    /**
     * Email Facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'email';
    }
}