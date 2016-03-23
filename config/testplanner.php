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
            'project'  => 'ECOM',
            'login'    => '',
            'password' => '',
            'version_description' => 'Test Plan for build v',
        ],
        'cache' => [
            'versions_lifetime' => 2880,
            'issues_lifetime'   => 2880,
        ]
    ],

    'tables' => [
        'pagination' => [
            'lists'           => 30,
            'activity_stream' => 10,
            'dashboard'       => 5,
        ]
    ],

    'messages' => [
        'users' => [
            'new'               => 'New user successfully registered.',
            'new_error'         => 'There seems to be a problem processing your request. Please contact the administrator.',
            'account_inactive'  => 'Your account is inactive.  Please contact the administrator.',
            'credentials_error' => 'The credentials you entered did not match our records.',
            'unauthorized'      => 'You are not authorized to access this resource.',
            'identical_user'    => 'The user you are trying to register already exists.',
            'identical_role'    => 'The role for the user you are trying to register already exists.',
            'update'            => 'User information updated successfully.',
            'update_error'      => 'There seems to be a problem while processing your request. Please contact the administrator.'
        ],
        'plan' => [
            'new'                 => 'created a new plan,',
            'update'              => 'updated plan,',
            'build_exists'        => 'The test plan your are tyring to create, already exists.',
            'new_build'           => 'New plan successfully created.',
            'build_error'         => 'There seems to be a problem while building the plan.  Please contact the administrator.',
            'build_update'        => 'details has been updated successfully.',
            'build_update_error'  => 'There seems to be a problem while updating the plan.  Please contact the administrator.',
            'session_error'       => 'There seems to be a problem processing your plan build. Make sure if plan was created.  If not, please contact the administrator for this error.',
            'no_users_found'      => 'There are no users at the current moment.',
            'no_plans_assigned'   => 'There are no plans assigned.',
            'no_plans_created'    => 'You have not created any plans',
            'no_plans_found'      => 'There are no plans at the current moment.',
            'no_responses_found'  => 'There are no responses at the current moment.',
            'no_activities_found' => 'There are no activities at the current moment.',
        ],

        'tickets' => [
            'users_non_responses' => 'User has not submitted responses yet.',
            'response_success'    => 'Your response has been posted successfully.',
            'response_error'      => 'There seems to be a problem processing your response. Please contact the administrator.',
            'response_updated'    => 'has updated one of the tickets in the plan.',
            'response_progress'   => 'has answered one of the tickets in the plan.',
            'response_resolved'   => 'resolved his plan.',
        ],

        'browsers' => [
            'removed' => 'Browsers that were assigned to test, have been removed from',
            'added'   => 'More browsers have been assigned for testing on ',
        ],

        'system' => [
            'update_success'        => 'Fields successfully updated',
            'update_error'          => 'Fields need to be modified for update.',
            'file_update_error'     => 'There seems to be a problem processing your update.  Please contact the administrator.',
            'email_error'           => 'Operation executed successfully.  However, there was a problem while sending email.',
            'activity_stream_error' => 'Operation executed successfully.  However, there was a problem logging to the stream.'
            ],
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
