<?php namespace App;

/**
 * Class Plans
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Tasks;
use App\SubTasks;

class Plans extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "plans";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'creator_id'
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
     * Render plan tickets to be responded
     */
    public static function renderPlan($id)
    {
        $plan       = Plans::findOrFail($id);
        $allTickets = Tickets::where('plan_id', $plan->id)->get();

        foreach($allTickets as $ticket) {
            $tickets[] = [
                'id'          => $ticket->id,
                'description' => $ticket->description,
                'objective'   => $ticket->objective,
                'test_steps'  => $ticket->test_steps
            ];
        }

        return [
            'id'          => $id,
            'description' => $plan->description,
            'tickets'     => $tickets
        ];
    }

    /**
     * One plan could have multiple tickets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Tickets');
    }
}