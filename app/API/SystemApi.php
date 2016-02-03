<?php namespace App\Api;

/**
 * Class SystemApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.mophie.us)
 */

use Illuminate\Filesystem\Filesystem;
use \App\Api\Interfaces\SystemInterface;

class SystemApi implements SystemInterface
{
    /**
     * Get all configurations from testplanner config file
     *
     * @return mixed
     */
    public function getConfigs()
    {
        return config('testplanner');
    }

    /**
     * Update testplanner config file
     *
     * @param $data
     * @return array
     */
    public function updateConfigs($data)
    {
        $configs = $this->getConfigs();

        foreach($data as $key => $val) {
            list($arrKeys, $attr) = explode(':', $key);
            list($section, $type) = explode('_', $arrKeys);

            if (isset($configs[$section][$type][$attr])) {
                $configs[$section][$type][$attr] = $val;
            }
        }

        $results = $this->writeConfigs($configs);

        return $results;
    }

    /**
     * Write to testplanner config file
     *
     * @param $data
     * @return bool
     */
    public function writeConfigs($data)
    {
        $data = var_export($data, 1);

        try {
            $fs = new Filesystem();
            $fs->put(base_path() . '/config/testplanner.php', "<?php\n return $data ;");
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}