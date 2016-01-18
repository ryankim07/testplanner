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
        'browser'
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

    /**
     * Get testers by plan ID
     *
     * @param $planId
     * @return mixed
     */
    public static function getTestersByPlanId($planId)
    {
        $allTesters = DB::table('testers AS t')
            ->join('users AS u', 'u.id', '=', 't.user_id')
            ->select('u.id', 'u.first_name', 't.browser')
            ->where('t.plan_id', '=', $planId)
            ->get();

        return $allTesters;
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
            list($testerId, $name, $browser) = explode(',', $eachTester);

            $id = '';
            $query = Testers::where('plan_id', '=', $planId)
                ->where('user_id', '=', $testerId)
                ->first();

            if (isset($query->id)) {
                $id = $query->id;
            }

            self::updateOrCreate([
                'id' => $id], [
                    'plan_id' => $planId,
                    'user_id' => $testerId,
                    'browser' => $browser
            ]);

            $testersWithEmail[] = [
                'first_name' => $name,
                'browser'    => $browser,
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