<?php namespace App;

/**
 * Class System
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Filesystem\Filesystem;

class System
{
    /**
     * Get all configurations from testplanner config file
     *
     * @return mixed
     */
    public static function getConfigs()
    {
        return config('testplanner');
    }

    /**
     * Add new configuration value to testplanner config file
     * @param $data
     */
    public static function setConfig($data)
    {
        self::writeConfig();
    }

    /**
     * Update testplanner config file
     *
     * @param $data
     * @return array
     */
    public static function updateConfig($data)
    {
        $configs  = self::getConfigs();
        $redirect = false;
        $errorMsg = '';

        foreach($data as $key => $val) {
            list($arrKeys, $attr) = explode(':', $key);
            list($section, $type) = explode('_', $arrKeys);

            if (isset($configs[$section][$type][$attr])) {
                $configs[$section][$type][$attr] = $val;
                $results[] = $attr;
            }
        }

        self::writeConfig($configs);

        return $results;
    }

    /**
     * Write to testplanner config file
     *
     * @param $data
     */
    public static function writeConfig($data)
    {
        $data     = var_export($data, 1);
        $redirect = false;
        $errorMsg = '';

        try {
            $fs = new Filesystem();
            $fs->put(base_path() . '/config/testplanner.php', "<?php\n return $data ;");
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return true;
    }
}