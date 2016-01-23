<?php namespace App;

/**
 * Class ActivityStream
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

use App\Facades\Tools;

use App\User;

use Auth;

class ActivityStream extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "activity_stream";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'user_id',
        'activity'
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
    protected $appends = array('custom_activity');

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
    public function getCustomActivityAttribute()
    {
        return Auth::user()->id == $this->user_id ? $this->activity : strip_tags($this->activity);
    }


    /**
     * Calculate and convert to a human readable format
     *
     * @param $value
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return Tools::timeDifference($value);
    }

    /**
     * Get activity logs
     *
     * @return string
     */
    public static function getActivityStream()
    {
        $query = ActivityStream::orderBy('created_at', 'DESC')
            ->paginate(config('testplanner.tables.pagination.activity_stream'));

        $results['query']       = $query;
        $results['total_count'] = self::count();

        return $results;
    }

    /**
     * Save activity stream
     *
     * @param $plan
     * @param $type
     * @param null $status
     * @return bool
     */
    public static function saveActivityStream($plan, $type, $status = null)
    {
        try {
            $assigneeName = User::getUserFirstName($plan['creator_id']);
            $userId       = $plan['creator_id'];

            if ($type != 'plan') {
                $assigneeName = $plan['assignee'];
                $userId       = $plan['tester_id'];
            }

            $planLink = link_to_route('plan.view.response', $plan['description'], [$plan['plan_id'], $userId]);
            $message  = '';

            switch($type) {
                case 'plan':
                    if ($status == 'new') {
                        $message = config('testplanner.messages.plan.new');
                    } elseif ($status == 'update') {
                        $message = config('testplanner.messages.plan.update');
                    }
                break;

                case 'ticket-response':
                    if ($status == 'progress' || $status == 'update') {
                        $message = config('testplanner.messages.plan.response_updated');
                    } else if ($status == 'complete') {
                        $message = config('testplanner.messages.plan.response_resolved');
                    }
                break;
            }

            if ($status == 'new') {
                $activity = $assigneeName . ' ' . $message . ' ' . $planLink;

                $comment = self::create([
                    'plan_id'  => $plan['plan_id'],
                    'user_id'  => $userId,
                    'activity' => $activity
                ]);
            }
        } catch(\Exception $e) {
            Tools::log($e->getMessage() . ' activity stream', $plan);
            Session::flash('flash_error', config('testplanner.messages.plan.system.activity_stream_error'));

            return true;
        }

        return true;
    }

    /**
     * One activity stream could have multiple comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\ActivityComments', 'as_id', 'id');
    }
}