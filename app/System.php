<?php namespace App;

/**
 * Class System
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\User;

class System extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "system";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section',
        'type',
        'value'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Model event to change data before saving to database
     */
    public static function boot()
    {
    }

    public static function getSections()
    {
        $sections = DB::table('system')
            ->distinct()
            ->groupBy('section')
            ->get();

        return $sections;
    }
}