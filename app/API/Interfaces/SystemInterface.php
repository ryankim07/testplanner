<?php namespace App\Api\Interfaces;

/**
 * Class SystemInterface
 *
 * Interface
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.mophie.us)
 */

interface SystemInterface
{
    /**
     * Source where to pull the configs, file base system or db
     *
     * @return mixed
     */
    public function getConfigs();

    /**
     * Updating method
     *
     * @return mixed
     */
    public function updateConfigs($data);

    /**
     * Write configuration method, file base system or db
     *
     * @return mixed
     */
    public function writeConfigs($data);
}