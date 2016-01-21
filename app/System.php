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
     * Update testplanner config file
     *
     * @param $data
     * @return array
     */
    public static function updateConfig($data)
    {
        $configs  = self::getConfigs();

        foreach($data as $key => $val) {
            list($arrKeys, $attr) = explode(':', $key);
            list($section, $type) = explode('_', $arrKeys);

            if (isset($configs[$section][$type][$attr])) {
                $configs[$section][$type][$attr] = $val;
            }
        }

        $update = self::writeConfig($configs);

        $results = [
            'status' => !$update ? 'error' : 'success',
            'msg'    => !$update ? config('testplanner.messages.system.file_update_error') :
                config('testplanner.messages.system.update_success')
        ];

        return $results;
    }

    /**
     * Write to testplanner config file
     *
     * @param $data
     * @return bool
     */
    public static function writeConfig($data)
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