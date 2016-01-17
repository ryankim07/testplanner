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
     * Model event to change data before saving to database
     */
    public static function boot()
    {
    }

    /**
     * Get activity logs
     *
     * @param $userId
     * @return string
     */
    public static function getActivityStream($userId)
    {
        $streams = self::orderBy('created_at', 'DESC')->take(50)->get();

        $results = '';
        if (count($streams) > 0) {
            foreach($streams as $stream) {
                $createdAt = Utils::timeDifference($stream->created_at);
                $activity  = (Auth::user()->hasRole(['root'])) || (Auth::user()->id == $stream->user_id) ? $stream->activity :
                    '<strong>' . strip_tags($stream->activity) . '</strong>';

                $activityComments = self::find($stream->id)->comments()->get();
                $comments = array();

                foreach($activityComments as $eachComment) {
                    $comments[$eachComment->id] = array(
                        'comment_id'  => $eachComment->id,
                        'commentator' => User::getUserFirstName($eachComment->user_id),
                        'comment'     => $eachComment->comment,
                        'created_at'  => date('m/d/Y', strtotime($eachComment->created_at))
                    );
                }

                $results[$stream->id] = array(
                    'id'         => $stream->id,
                    'activity'   => $activity,
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
        try {
            $assigneeName = User::getUserFirstName($plan['creator_id']);
            $userId       = $plan['creator_id'];

            if ($type != 'plan') {
                $assigneeName = $plan['assignee'];
                $userId       = $plan['tester_id'];
            }

            $planLink = link_to_route('plan.view.response', $plan['description'], [$plan['id'], $userId]);
            $message  = '';

            switch($type) {
                case 'plan':
                    $message = config('testplanner.new_plan_msg');
                    break;

                case 'ticket-response':
                    if ($status == 'progress' || $status == 'update') {
                        $message = config('testplanner.plan_response_updated_msg');
                    } else if ($status == 'complete') {
                        $message = config('testplanner.plan_response_resolved');
                    }
                    break;
            }

            if ($status != 'new') {
                $activity = $assigneeName . ' ' . $message . ' ' . $planLink;

                $comment = self::create([
                    'plan_id'  => $plan['id'],
                    'user_id'  => $userId,
                    'activity' => $activity
                ]);
            }
        } catch(\Exception $e) {
            // Log to system
            Utils::log($e->getMessage(), $plan);
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