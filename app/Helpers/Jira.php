<?php namespace App\Helpers;

/**
 * Class Jira
 *
 * Helper
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Facades\Tools;

class Jira
{
    private $_username;
    private $_password;
    private $_jira_rest_url;

    public function __construct()
    {
        $this->_username      = config('testplanner.jira.info.login');
        $this->_password      = config('testplanner.jira.info.password');
        $this->_jira_rest_url = config('testplanner.jira.info.rest_url');
    }

    /**
     * Connect to JIRA API
     *
     * @param $data
     * @return mixed
     */
    private function _connect($data)
    {
        try {
            $ch = curl_init();

            // Configure CURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_URL, $data['query_url']);
            curl_setopt($ch, CURLOPT_USERPWD, "{$this->_username}:{$this->_password}");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Content-Type: application/json'
            ]);

            $result  = curl_exec($ch);
            $chError = curl_error($ch);

            curl_close($ch);

            if ($chError) {
                Tools::log('cURL server response error: ', $chError);
            } else {
                return json_decode($result);
            }
        } catch(\Exception $e) {
            Tools::log('cURL errors: ' . $e->getErrors(), $data);
        }
    }

    /**
     * Get all issues from JIRA
     *
     * @param $project
     * @return array
     */
    public function getAllIssues($project)
    {
        // Query type
        $data['query_url'] = $this->_jira_rest_url . '/search?jql=project=' . $project;

        // Connect to api and get results
        $data    = $this->_connect($data);
        $results = [];

        // Return on a certain array structure
        if (isset($data)) {
            foreach ($data->issues as $issue) {
                $key = $issue->key;
                $results[] = [
                    'key' => $key,
                    'summary' => $issue->fields->summary
                ];
            }

            ksort($results, SORT_NUMERIC);
        }

        return $results;
    }

    /**
     * Get all versions of certain project
     *
     * @param $project
     * @return mixed
     */
    public function getAllProjectVersions($project)
    {
        // Query type
        $data['query_url'] = $this->_jira_rest_url . '/project/' . $project . '/versions';

        // Connect to api and get results
        $data    = $this->_connect($data);
        $results = [];

        // Return on a certain array structure
        if (isset($data)) {
            foreach ($data as $version) {
                $results[] = ['name' => $version->name];
            }

            krsort($results, SORT_NUMERIC);
        }

        return $results;
    }
}