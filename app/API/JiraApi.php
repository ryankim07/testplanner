<?php namespace App\Api;

/**
 * Class Jira
 *
 * Helper
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Facades\Tools;

class JiraApi
{
    private $_username;
    private $_password;
    private $_issueQueryUrl;
    private $_projectQueryUrl;

    public function __construct()
    {
        $project                = config('testplanner.jira.info.project');
        $jiraRestUrl            = config('testplanner.jira.info.rest_url');
        $this->_username        = config('testplanner.jira.info.login');
        $this->_password        = config('testplanner.jira.info.password');
        $this->_issueQueryUrl   = $jiraRestUrl . '/search?jql=project=' . $project;
        $this->_projectQueryUrl = $jiraRestUrl . '/project/' . $project . '/versions';
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

            $results = curl_exec($ch);
            $chError = curl_error($ch);

            curl_close($ch);

            if ($chError) {
                Tools::log('cURL server response error: ', $chError);
            } else {
                return json_decode($results);
            }
        } catch(\Exception $e) {
            Tools::log('cURL errors: ' . $e->getErrors(), $data);
        }
    }

    /**
     * Use Jira API
     *
     * @return array
     */
    public function jiraVersions()
    {
        // Get JIRA project versions
        $data['query_url'] = $this->_projectQueryUrl;
        $responseData  = $this->_connect($data);
        $versions      = [];

        // Return on a certain array structure
        if (isset($responseData)) {
            krsort($responseData);

            foreach ($responseData as $version) {
                $versions[] = [
                    'label' => config('testplanner.jira.info.version_description') . $version->name,
                    'value' => $version->id
                ];
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
        $data['query_url'] = $this->_issueQueryUrl;
        $responseData = $this->_connect($data);
        $issues  = [];

        // Return on a certain array structure
        if (isset($responseData)) {
            foreach ($responseData->issues as $issue) {
                $issues[] = $issue->key . ': ' . $issue->fields->summary;
            }
        }

        return $issues;
    }

    public function jiraIssuesByVersion($buildVersionId)
    {
        try {
            // Query type
            $data['query_url'] = $this->_issueQueryUrl;
            $responseData      = $this->_connect($data);
            $allIssues         = [];
            $specificIssues    = [];

            // Grab only specific issues to be auto filled
            if (isset($responseData)) {
                foreach($responseData->issues as $issue) {
                    $issueId     = $issue->id;
                    $key         = $issue->key;
                    $fixVersions = $issue->fields->fixVersions;
                    $summary     = $issue->fields->summary;

                    foreach($fixVersions as $fixVersion) {
                        if ($fixVersion->id == $buildVersionId) {
                            $specificIssues[$issueId] = $key . ': ' . $summary;
                        }
                    }

                    // Grab all issues to be shown as dropdown
                    $allIssues[] = $issue->key . ': ' . $issue->fields->summary;
                }
            }
        } catch(\Exception $e) {
            Tools::log('cURL errors: ' . $e->getErrors(), $data);
        }

        // Set default array, therefore it shows blank ticket block
        if (count($specificIssues) == 0) {
            $specificIssues[0] = '';
        }

        $results = [
            'allIssues'      => $allIssues,
            'specificIssues' => $specificIssues
        ];

        return $results;
    }
}