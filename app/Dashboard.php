<?php namespace App;

/**
 * Class Dashboard
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Plans;
use App\Tickets;
use App\TicketsResponses;
use App\Testers;
use App\UserRole;

use Auth;

class Dashboard extends Model
{
}