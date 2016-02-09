<?php namespace App\Helpers;

/**
 * Class Tools
 *
 * Helper
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Models\User,
    App\Models\Role;

use Lang;
use Log;
use Session;

class Tools
{
    /**
     * Generate random numbers according to length
     *
     * @param int $max
     * @return string
     */
    public static function generateSalt($max = 32)
    {
        $baseStr = time() . rand(0, 1000000) . rand(0, 1000000);
        $md5Hash = md5($baseStr);

        if($max < 32) {
            $md5Hash = substr($md5Hash, 0, $max);
        }

        return $md5Hash;
    }

    /**
     * Converts datetime to mm/dd/YYY format
     *
     * @param $date
     * @return bool|string
     */
    public static function dateConverter($date)
    {
        return date('m/d/Y', strtotime($date));
    }

    /**
     * Converts datetime to mm/dd/YYY format
     *
     * @param $date
     * @return bool|string
     */
    public static function dateAndTimeConverter($date)
    {
        return date('m/d/Y h:i:s', strtotime($date));
    }

    /**
     * Converts datetime to db format
     *
     * @param $date
     * @return bool|string
     */
    public static function dbDateConverter($date, $time)
    {
        return date('Y-m-d' . ' ' . $time, strtotime($date));
    }

    /**
     * Get days, hours or minutes difference
     *
     * @param $date
     * @return string
     */
    public static function timeDifference($date)
    {
        $interval = date_diff(date_create($date), date_create(date('Y-m-d H:i:s', time())));
        $years    = $interval->format('%y');
        $months   = $interval->format('%m');
        $days     = $interval->format('%d');
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');
        $curMonth = date('m', time());
        $curYear  = date('Y', time());

        if ($years != 0) {
            $results = $years . ' ' . ($years > 1 ? 'years' : 'year') . ' ' . $ago;
        } else if ($years == 0 && $months != 0) {
            if ($months == 12) {
                $results = '1 year ago';
            } else {
                $results = $months . ' months ago' ;
            }
        } else if ($years == 0 && $months == 0 && $days != 0) {
            if ($days == 1) {
                $results = 'Yesterday';
            } else if ($days > 1 && $days < 7) {
                $results = $days . ' days ago';
            } else if ($days == 7) {
                $results = 'Last week';
            } else if ($days > 7 && $days <= 14) {
                $results = '2 weeks ago';
            } else if ($days > 14 && $days <= 21) {
                $results = '3 weeks ago';
            } else if ($days > 21 && $days <= cal_days_in_month(CAL_GREGORIAN, $curMonth, $curYear)) {
                $results = '1 month ago';
            }

            if ($days != 1 && $days != 7) {
                $results = $days . ' days ago';
            }
        } else if ($years == 0 && $months == 0 && $days == 0 && $hours != 0) {
            if ($hours == 24) {
                $results = '1 day ago';
            } else {
                $results = $hours . ' ' . ($hours > 1 ? 'hours' : 'hour') . ' ago';
            }
        } else if ($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes != 0) {
            if ($minutes == 60) {
                $results = '1 hour ago';
            } else {
                $results = $minutes . ' ' . ($minutes > 1 ? 'minutes' : 'minute') . ' ago';
            }
        } else {
            $results = '1 minute ago';
        }

        return $results;
    }

    /**
     * Log to the system
     *
     * @param $errorMsg
     * @param $data
     */
    public static function log($errorMsg, $data)
    {
        $header = "\n\n" . "The following error occurred: " . "\n\n";
        $msg    = $header . $errorMsg . "\n\n" . print_r($data, true);

        Log::notice($msg);
    }

    /**
     * Convert double quote in text
     *
     * @param $text
     * @return string
     */
    public function convertDoubleQuotes($text)
    {
        return htmlentities($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Restructure users dropdown options
     *
     * @param $list
     * @param $type
     * @return mixed
     */
    public static function getUsersDropdrownOptions($list, $type)
    {
        // Set up dropdown list of all admins
        $results[0] = 'All';

        foreach($list as $each) {
            if ($type == 'admin') {
                $results[$each->id] = $each->first_name;
            } elseif ($type == 'testers') {
                $results[$each->user_id] = $each->user_first_name . ' - ' . $each->user_browsers;
            } else {
                $results[$each->user_id] = $each->first_name;
            }
        }

        return $results;
    }

    /**
     * Get dropdown of all roles available for user
     *
     * @return mixed
     */
    public static function getRolesDropdownOptions()
    {
        $allRoles = Role::all();

        foreach($allRoles as $eachRole) {
            $results[$eachRole->id] = $eachRole->custom_role_name;
        }

        return $results;
    }

    /**
     * Capitalize comma separated browser names
     *
     * @param $browser
     * @return string
     */
    public static function capitalizeBrowserNames($browser)
    {
        return implode(', ', array_map('ucfirst', explode(',', $browser)));
    }

    /**
     * Return tester browser images
     *
     * @param $browsers
     * @return string
     */
    public static function getTesterBrowserImg($browsers)
    {
        $browsers= explode(',', $browsers);
        $results = '';

        foreach($browsers as $browser) {
            $results .= asset('images/' . $browser . '.png', ['class' => 'browser-img']);
        }

        return $results;
    }

    /**
     * Get user's info
     *
     * @param $userId
     * @return mixed
     */
    public static function getUserFirstName($userId)
    {
        $user = User::find($userId);

        return $user->first_name;
    }

    /**
     * Get user's email
     *
     * @param $userId
     * @return mixed
     */
    public static function getUserEmail($userId)
    {
        $info = User::find($userId);

        return $info->email;
    }

    /**
     * Determine if certain roles are allowed to resources
     *
     * @param $userRoles
     * @param $allowedRoles
     * @return bool
     */
    public static function checkUserRole($userRoles, $allowedRoles)
    {
        $found = 0;

        // Check which section they belond to
        foreach($allowedRoles as $allowedRole) {
            foreach($userRoles as $userRole) {
                if ($userRole == $allowedRole) {
                    $found++;
                }
            }
        }

        if ($found == count($allowedRoles))
        {
            return true;
        }

        return false;
    }

    /**
     * Get a total status of plans or tickets
     *
     * @param $allStatus
     * @param $totalCount
     * @return string
     */
    public static function getOverallStatus($allStatus, $totalCount)
    {
        // Determine plan status
        $allComplete = (reset($allStatus) == 'complete' && count(array_unique($allStatus)) == 1);
        $new         = in_array('new', $allStatus);
        $complete    = in_array('complete', $allStatus);
        $progress    = in_array('progress', $allStatus);
        $update      = in_array('update', $allStatus);

        if ($complete && $update) {
            $overallStatus = 'update';
        } elseif ($update) {
            $overallStatus = 'update';
        } elseif ($allComplete && ($totalCount == count($allStatus))) {
            $overallStatus = 'complete';
        } else {
            $overallStatus = 'progress';
        }

        return $overallStatus;
    }

    /**
     * Get status text message
     *
     * @param $section
     * @param $type
     * @param $status
     * @return mixed
     */
    public static function getStatusText($section, $type, $status)
    {
        // Activity status
        switch($section) {
            case 'activity':
            case 'email':
                if ($type == 'plan') {
                    if ($status == 'new') {
                        $message = config('testplanner.messages.plan.new');
                    } elseif ($status == 'update') {
                        $message = config('testplanner.messages.plan.update');
                    }
                } elseif ($type == 'ticket-response') {
                    if ($status == 'progress') {
                        $message = config('testplanner.messages.tickets.response_progress');
                    } elseif ($status == 'update') {
                        $message = config('testplanner.messages.tickets.response_updated');
                    } elseif ($status == 'complete') {
                        $message = config('testplanner.messages.tickets.response_resolved');
                    }
                }
            break;
        }

        return $message;
    }
}