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

use App\Facades\Utils;

use App\User;

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
     * Model event to change data before saving to database
     */
    public static function boot()
    {
    }

    /**
     * Get activity logs
     *
     * @return string
     */
    public static function getActivityStream()
    {
        $logs = ActivityStream::orderBy('created_at', 'DESC')->take(50)->get();

        $results = '';

        if (count($logs) > 0) {
            foreach($logs as $log) {
               $createdAt = Utils::timeDifference($log->created_at);

                $activityComments = ActivityStream::find($log->id)->comments()->get();
                $comments = array();

                foreach($activityComments as $eachComment) {
                    $comments[$eachComment->id] = array(
                        'comment_id'  => $eachComment->id,
                        'commentator' => User::getUserFirstName($eachComment->user_id),
                        'comment'     => $eachComment->comment
                    );
                }

                $results[$log->id] = array(
                    'id'         => $log->id,
                    'activity'   => $log->activity,
                    'comments'   => $comments,
                    'created_at' => $createdAt
                );
            }
        }

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
        $assigneeName = User::getUserFirstName($plan['creator_id']);
        $userId       = $plan['creator_id'];

        if ($type != 'plan') {
            $assigneeName = $plan['assignee'];
            $userId       = $plan['tester_id'];
        }

        $planLink = link_to_route('dashboard.plan.respond', $plan['description'], [$plan['id']]);
        $message  = '';

        switch($type) {
            case 'plan':
                $message = 'created a new plan:';
            break;

            case 'ticket-response':
                if ($status == 'incomplete') {
                    $message = 'has updated tickets in';
                } else if ($status == 'complete') {
                    $message = 'resolved';
                }
            break;
        }

        if ($status != 'new') {
            $activity = $assigneeName . ' ' . $message . ' ' . $planLink;

            $comment = ActivityStream::create([
                'plan_id'  => $plan['id'],
                'user_id'  => $userId,
                'activity' => $activity
            ]);
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