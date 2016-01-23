<?php namespace App;

/**
 * Class Testers
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

class Testers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "testers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'user_id',
        'browsers'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Custom attribute to be included in model
     *
     * @var array
     */
    protected $appends = array('user_first_name');

    /**
     * Model event to change data before saving to database
     */
    public static function boot()
    {
    }

    /**
     * Retrieve custom accessor
     *
     * @return mixed
     */
    public function getUserFirstNameAttribute()
    {
        return User::getUserFirstName($this->user_id);
    }

    /**
     * Update tester from built plan
     *
     * @param $planId
     * @param $testers
     * @return array
     */
    public static function updateBuiltTesters($planId, $testers)
    {
        foreach($testers as $eachTester) {
            list($testerId, $name, $browsers) = explode(',', $eachTester);

            $id = '';
            $query = Testers::where('plan_id', '=', $planId)
                ->where('user_id', '=', $testerId)
                ->first();

            if (isset($query->id)) {
                $id = $query->id;
            }

            self::updateOrCreate([
                'id' => $id], [
                    'plan_id'  => $planId,
                    'user_id'  => $testerId,
                    'browsers' => $browsers
            ]);

            $testersWithEmail[] = [
                'first_name' => $name,
                'browsers'   => $browsers,
                'email'      => User::getUserEmail($testerId)
            ];
        }

        return $testersWithEmail;
    }

    /**
     * Only one task belongs to a case
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo('App\Plans');
    }
}