<?php namespace App\Helpers;

/**
 * Class Utils
 *
 * Helper
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use Lang;
use Log;

class Utils
{
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
     * Converts datetime to db format
     *
     * @param $date
     * @return bool|string
     */
    public function dbDateConverter($date, $time)
    {
        return date('Y-m-d ' . $time, strtotime($date));
    }

    /**
     * Return phone number with parantheses and dash
     *
     * Output: (000) 000-0000
     *
     * @param $phone
     * @return mixed
     */
    public function getFormattedPhone($phone)
    {
        return preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $phone);
    }

    /**
     * Get all states by abbreviation
     *
     * @return array
     */
    public function getStatesAbbrList()
    {
        return $stateList = [
            "AL" => "AL",
            "AK" => "AK",
            "AZ" => "AZ",
            "AR" => "AR",
            "CA" => "CA",
            "CO" => "CO",
            "CT" => "CT",
            "DE" => "DE",
            "DC" => "DC",
            "FL" => "FL",
            "GA" => "GA",
            "HI" => "HI",
            "ID" => "ID",
            "IL" => "IL",
            "IN" => "IN",
            "IA" => "IA",
            "KS" => "KS",
            "KY" => "KY",
            "LA" => "LA",
            "ME" => "ME",
            "MD" => "MD",
            "MA" => "MA",
            "MI" => "MI",
            "MN" => "MN",
            "MS" => "MS",
            "MO" => "MO",
            "MT" => "MT",
            "NE" => "NE",
            "NV" => "NV",
            "NH" => "NH",
            "NJ" => "NJ",
            "NM" => "NM",
            "NY" => "NY",
            "NC" => "NC",
            "ND" => "ND",
            "OH" => "OH",
            "OK" => "OK",
            "OR" => "OR",
            "PA" => "PA",
            "RI" => "RI",
            "SC" => "SC",
            "SD" => "SD",
            "TN" => "TN",
            "TX" => "TX",
            "UT" => "UT",
            "VT" => "VT",
            "VA" => "VA",
            "WA" => "WA",
            "WV" => "WV",
            "WI" => "WI",
            "WY" => "WY"
        ];
    }

    /**
     * Get all states by name
     *
     * @return array
     */
    public function getStatesNameList()
    {
        return $stateList = [
            "AL" => "Alabama",
            "AK" => "Alaska",
            "AZ" => "Arizona",
            "AR" => "Arkansas",
            "CA" => "California",
            "CO" => "Colorado",
            "CT" => "Connecticut",
            "DE" => "Delaware",
            "DC" => "District Of Columbia",
            "FL" => "Florida",
            "GA" => "Georgia",
            "HI" => "Hawaii",
            "ID" => "Idaho",
            "IL" => "Illinois",
            "IN" => "Indiana",
            "IA" => "Iowa",
            "KS" => "Kansas",
            "KY" => "Kentucky",
            "LA" => "Louisiana",
            "ME" => "Maine",
            "MD" => "Maryland",
            "MA" => "Massachusetts",
            "MI" => "Michigan",
            "MN" => "Minnesota",
            "MS" => "Mississippi",
            "MO" => "Missouri",
            "MT" => "Montana",
            "NE" => "Nebraska",
            "NV" => "Nevada",
            "NH" => "New Hampshire",
            "NJ" => "New Jersey",
            "NM" => "New Mexico",
            "NY" => "New York",
            "NC" => "North Carolina",
            "ND" => "North Dakota",
            "OH" => "Ohio",
            "OK" => "Oklahoma",
            "OR" => "Oregon",
            "PA" => "Pennsylvania",
            "RI" => "Rhode Island",
            "SC" => "South Carolina",
            "SD" => "South Dakota",
            "TN" => "Tennessee",
            "TX" => "Texas",
            "UT" => "Utah",
            "VT" => "Vermont",
            "VA" => "Virginia",
            "WA" => "Washington",
            "WV" => "West Virginia",
            "WI" => "Wisconsin",
            "WY" => "Wyoming"
        ];
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
}