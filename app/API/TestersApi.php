<?php namespace App\Api;

/**
 * Class TestersApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.mophie.us)
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

use App\Models\Testers;

class TestersApi
{
    /**
     * @var Testers
     */
    protected $model;

    /**
     * TestersApi constructor
     *
     * @param Testers $testers
     */
    public function __construct(Testers $testers)
    {
        $this->model = $testers;
    }

    /**
     * Update tester from built plan
     *
     * @param $planId
     * @param $testersData
     * @return array|bool
     */
    public function updateBuiltTesters($planId, $testersData, $origData)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Start testers update
        try {
            $query = $this->model->where('plan_id', '=', $planId)->delete();

            foreach($testersData as $tester) {
                $testerId = $tester['id'];
                $browsers = $tester['browsers'];

                $newCount = isset($browsers) ? count(explode(',', $browsers)) : 0;

                // Formerly selected browsers
                $oldCount = isset($origData[$testerId]) ? count(explode(',', $origData[$testerId])) : 0;

                if ($oldCount == $newCount) {
                    $updateStatus = 0;
                } elseif ($newCount > $oldCount) {
                    $updateStatus = 1;
                } elseif ($oldCount < $newCount || $oldCount > $newCount) {
                    $updateStatus = -1;
                }

                $tester += [
                    'update_status'      => $updateStatus,
                    'update_status_text' => Tools::planTesterChanges($updateStatus)
                ];

                $results[] = $tester;

                // Create new or update
                if (count($tester['input-ids']) > 0 && !empty($browsers)) {
                    $this->model->create([
                        'plan_id'  => $planId,
                        'user_id'  => $testerId,
                        'browsers' => $browsers
                    ]);
                }
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
            Tools::log($errorMsg, $testersData);

            return false;
        }

        // Commit all changes
        DB::commit();

        return $results;
    }
}