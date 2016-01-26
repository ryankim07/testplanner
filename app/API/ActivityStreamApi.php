<?php namespace App\Api;

/**
 * Class ActivityStreamApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Helpers\Tools;

use App\Models\Stream,
    App\Models\Comments;

use Session;

class ActivityStreamApi
{
    /**
     * @var Activity Stream
     */
    protected $streamModel;
    protected $commentModel;
    protected $tablesApi;

    /**
     * ActivityStream constructor
     *
     * @param Stream $streams
     * @param Comments $comments
     */
    public function __construct(Stream $streams, Comments $comments, TablesApi $tables)
    {
        $this->streamModel  = $streams;
        $this->commentModel = $comments;
        $this->tablesApi    = $tables;
    }

    public function displayActivityStream()
    {
        $table = $this->tablesApi->prepare('order', [
            'activity',
            'created_at'
        ], 'ActivityStreamController@index');

        $results = [
            'activities'  => $this->getActivityStream(),
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
        ];

        return $results;
    }


    /**
     * Get activity logs
     *
     * @return string
     */
    public function getActivityStream()
    {
        $query = $this->streamModel->orderBy('created_at', 'DESC')
            ->paginate(config('testplanner.tables.pagination.activity_stream'));

        return $query;
    }

    /**
     * Save activity stream
     *
     * @param $plan
     * @param $type
     * @param null $status
     * @return bool
     */
    public function saveActivityStream($plan, $type, $status = null)
    {
        try {
            $assigneeName = Tools::getUserFirstName($plan['creator_id']);
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

                $comment = $this->commentModel->create([
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
     * Create comment in activity stream
     *
     * @param $asId
     * @param $userId
     * @param $comment
     * @return bool
     */
    public function saveActivityComment($asId, $userId, $comment)
    {
        $results = $this->streamModel->create([
            'as_id'   => $asId,
            'user_id' => $userId,
            'comment' => $comment
        ]);

        return $results;
    }
}