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
    'general' => [
        'info' => [
            'admin_name'  => 'Ryan Kim',
            'admin_email' => 'ryan.kim@mophie.com',
        ],
    ],

    'jira' => [
        'info' => [
            'domain'   => 'https://mophie.atlassian.net',
            'rest_url' => 'https://mophie.atlassian.net/rest/api/2',
            'login'    => 'ryan.kim',
            'password' => 'Sk1ncare'
        ]
    ],

    'tables' => [
        'pagination' => [
            'lists'   => 30,
            'activity_stream'  => 10,
            'dashboard' => 5,
        ]
    ],

    'messages' => [
        'users' => [
            'new_user_added'      => 'New user successfully registered.',
            'account_inactive'    => 'Your account is inactive.  Please contact the administrator.',
            'credentials_error'   => 'The credentials you entered did not match our records.',
            'unauthorized'        => 'You are not authorized to access this resource.',
            'identical_role'      => 'The role for the user you are trying to register already exists.',
            'user_update'         => 'User info updated successfully.',
        ],
        'plan' => [
            'new'               => 'created a new plan,',
            'update'            => 'updated plan,',
            'new_build'         => 'New plan successfully created.',
            'build_error'       => 'Plan building cannot be done at the current moment.  Please try again later.',
            'build_update'      => 'details has been updated successfully.',
            'session_error'     => 'Session data is missing.',
            'users_non_responses' => 'Users have not submitted responses yet.',
            'response_success'  => 'Your plan has been posted successfully',
            'response_error'    => 'There seems to be a problem processing your response. Please try again later.',
            'response_updated'  => 'has updated',
            'response_resolved' => 'resolved'
        ]
    ],

    'mail' => [
        'subjects' => [
            'admin_system'    => 'Test Planner System Error.',
            'plan_created'    => 'New test plan has been assigned to you.',
            'plan_updated'    => 'Existing plan has been updated.',
            'ticket_response' => 'Response from',
            'email_error'     => 'Confirmation email error.'
        ]
    ]
];