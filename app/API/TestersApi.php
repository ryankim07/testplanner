<?php namespace App\Api;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

class TestersApi
{
    /**
     * @var Plans
     */
    protected $testersModel;

    /**
     * TestersApi constructor
     *
     * @param Testers $testers
     */
    public function __construct(Testers $testers)
    {
        $this->testersModel = $testers;
    }

    /**
     * Update tester from built plan
     *
     * @param $planId
     * @param $testersData
     * @return array|bool
     */
    public function updateBuiltTesters($planId, $testersData)
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
                $query = $this->testersModel->where('plan_id', '=', $planId)
                    ->where('user_id', '=', $tester['id'])
                    ->first();

                if (isset($query->id)) {
                    $id = $query->id;
                }

                // Create new or update
                $this->testersModel->updateOrCreate([
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
}