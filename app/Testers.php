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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

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
     * @param $testersData
     * @return array|bool
     */
    public static function updateBuiltTesters($planId, $testersData)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Start testers update
        try {
            $testersData = json_decode($testersData, true);

            foreach($testersData as $tester) {
                // Get primary key of testers table
                $id = '';
                $query = Testers::where('plan_id', '=', $planId)
                    ->where('user_id', '=', $tester['id'])
                    ->first();

                if (isset($query->id)) {
                    $id = $query->id;
                }

                // Create new or update
                self::updateOrCreate([
                    'id' => $id], [
                        'plan_id'  => $planId,
                        'user_id'  => $tester['id'],
                        'browsers' => $tester['browsers']
                ]);
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $redirect = true;
        } catch (QueryException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        } catch (ModelNotFoundException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        }

        // Redirect if errors
        if ($redirect) {
            // Rollback
            DB::rollback();

            // Log to system
            Tools::log($errorMsg, $testerData);

            return false;
        }

        // Commit all changes
        DB::commit();

        return true;
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