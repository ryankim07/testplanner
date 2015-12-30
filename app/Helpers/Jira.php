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

use App\Facades\Utils;

class Jira
{
    private $_username;
    private $_password;
    protected $_jira_app_url;

    public function __construct()
    {
        $this->_username = env('JIRA_LOGIN');
        $this->_password = env('JIRA_PASS');
        $this->_jira_app_url = config('testplanner.jira_app_url');
    }

    /**
     * Connect to JIRA API
     *
     * @param $data
     * @return mixed
     */
    public function connect($data)
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
                Utils::log('cURL server response error: ', $chError);
            } else {
                return json_decode($result);
            }
        } catch(\Exception $e) {
            Utils::log('cURL errors: ' . $e->getErrors(), $data);
        }
    }

    /**
     * Get all issues from JIRA
     */
    public function getIssues()
    {
        // Query type
        $data['query_url'] = $this->_jira_app_url . '/rest/api/2/search?jql=project=ECOM';

        // Connect to api and get results
        $data = $this->connect($data);

        foreach($data->issues as $issue) {
            $key = $issue->key;
            $results[] = [
                'key'        => $key,
                'summary'    => $key . ': ' . $issue->fields->summary,
                'browse_url' => $this->_jira_app_url . '/browse/' . $key,
            ];
        }

        return $results;
    }
}