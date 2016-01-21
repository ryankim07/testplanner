<?php namespace App\Helpers;

/**
 * Class Tools
 *
 * Helper
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Facades\Jira;

use Lang;
use Log;

class Tools
{
    /**
     * Generate random numbers according to length
     *
     * @param int $max
     * @return string
     */
    public function generateSalt($max = 32)
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
    public function dateConverter($date)
    {
        return date('m/d/Y', strtotime($date));
    }

    /**
     * Converts datetime to mm/dd/YYY format
     *
     * @param $date
     * @return bool|string
     */
    public function dateAndTimeConverter($date)
    {
        return date('m/d/Y h:i:s', strtotime($date));
    }

    /**
     * Converts datetime to db format
     *
     * @param $date
     * @return bool|string
     */
    public function dbDateConverter($date, $time)
    {
        return date('Y-m-d' . ' ' . $time, strtotime($date));
    }

    /**
     * Get days, hours or minutes difference
     *
     * @param $date
     * @return string
     */
    public function timeDifference($date)
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
     * Use Jira API
     *
     * @return array
     */
    public function jiraVersions()
    {
        // Get JIRA project versions
        $results  = Jira::getAllProjectVersions('ECOM');
        $versions = [];

        if (isset($results)) {
            foreach($results as $version) {
                $versions[] = 'Test Plan for build v' . $version['name'];
            }
        }

        return $versions;
    }

    /**
     * Use Jira API
     *
     * @return array
     */
    public function jiraIssues()
    {
        // Get JIRA issues
        $results = Jira::getAllIssues('ECOM');
        $issues  = [];

        if (isset($results)) {
            foreach ($results as $issue) {
                $issues[] = $issue['key'] . ': ' . $issue['summary'];
            }
        }

        return $issues;
    }

    /**
     * Log to the system
     *
     * @param $errorMsg
     * @param $data
     */
    public function log($errorMsg, $data)
    {
        $header = "\n\n" . "The following error occurred: " . "\n\n";
        $msg    = $header . $errorMsg . "\n\n" . print_r($data, true);

        Log::notice($msg);
    }

    public function dropDownOptionsHtml($testers)
    {
        foreach ($testers as $tester) {
            $results[$tester->id] = $tester->user_first_name . ' - ' . $tester->browser;
        }

        return $results;
    }
}