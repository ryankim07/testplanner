<?php

return [
    /*
	|--------------------------------------------------------------------------
	| General
	|--------------------------------------------------------------------------
	|
	| Specify appropriate values for testing email locally
	|
    */

    'admin_name'              => 'Ryan Kim',

    'admin_email'             => 'ryan.kim@mophie.com',

    'jira_domain'             => 'https://mophie.atlassian.net',

    'jira_rest_url'           => 'https://mophie.atlassian.net/rest/api/2',

    'pagination_count'        => 50,

    'credentials_problem_msg' => 'The credentials you entered did not match our records. Try again?',

    'unauthorized_msg'        => 'You are not authorized to access this resource.',

    'identical_role_msg'      => 'The role for the user you are trying to register already exists.',

    'plan_build_error'        => 'Plan building cannot be done at the current moment.  Please try again later.',

    'plan_session_error'      => 'Session data is missing.'
];