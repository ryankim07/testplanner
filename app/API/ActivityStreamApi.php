<?php namespace App\Api;

/**
 * Class ActivityStreamApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.mophie.us)
 */

use App\Facades\Tools;

use App\Models\Streams,
    App\Models\Comments;

use Session;

class ActivityStreamApi
{
    /**
     * @var Activity Stream
     */
    protected $model;

    /**
     * @var Comments
     */
    protected $commentsModel;

    /**
     * @var TablesApi
     */
    protected $tablesApi;

    /**
     * ActivityStreamApi constructor
     *
     * @param Streams $streams
     * @param Comments $comments
     * @param TablesApi $tablesApi
     */
    public function __construct(Streams $streams, Comments $comments, TablesApi $tablesApi)
    {
        $this->model         = $streams;
        $this->commentsModel = $comments;
        $this->tablesApi     = $tablesApi;
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
        $query = $this->model
            ->orderBy('created_at', 'DESC')
            ->paginate(config('testplanner.tables.pagination.activity_stream'));

        return $query;
    }

    /**
     * Save activity stream
     *
     * @param $plan
     * @return bool
     */
    public function saveActivityStream($plan)
    {
        try {
            $assigneeName = Tools::getUserFirstName($plan['creator_id']);
            $userId = $plan['creator_id'];
            $type   = $plan['type'];
            $status = $plan['status'];

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

            $activity = $assigneeName . ' ' . $message . ' ' . $planLink;

            $this->model->create([
                'plan_id'  => $plan['plan_id'],
                'user_id'  => $userId,
                'activity' => $activity
            ]);
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
     * @param $streamId
     * @param $userId
     * @param $comment
     * @return bool
     */
    public function saveActivityComment($streamId, $userId, $comment)
    {
        $results = $this->commentsModel->create([
            'stream_id' => $streamId,
            'user_id'   => $userId,
            'comment'   => $comment
        ]);

        return $results;
    }
}