<?php namespace App;

/**
 * Class ActivityComments
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

class ActivityComments extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "activity_comments";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'as_id',
        'user_id',
        'comment'
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
     * Create comment in activity stream
     *
     * @param $asId
     * @param $userId
     * @param $comment
     * @return bool
     */
    public static function saveActivityComment($asId, $userId, $comment)
    {
        $results = self::create([
            'as_id'   => $asId,
            'user_id' => $userId,
            'comment' => $comment
        ]);

        return $results;
    }

    /**
     * Only one comment belongs to an activity stream
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stream()
    {
        return $this->belongsTo('App\ActivityStream');
    }
}