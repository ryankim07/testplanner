<?php
 return array (
  'general' => 
  array (
    'info' => 
    array (
      'admin_name' => 'Ryan Kim',
      'admin_email' => 'ryan.kim@mophie.com',
    ),
  ),
  'jira' => 
  array (
    'info' => 
    array (
      'domain' => 'https://mophie.atlassian.net',
      'rest_url' => 'https://mophie.atlassian.net/rest/api/2',
      'project' => 'ECOM',
      'login' => 'ryan.kim',
      'password' => 'Sk1ncare',
      'version_description' => 'Test Plan for build v',
    ),
  ),
  'tables' => 
  array (
    'pagination' => 
    array (
      'lists' => 30,
      'activity_stream' => 10,
      'dashboard' => 5,
    ),
  ),
  'messages' => 
  array (
    'users' => 
    array (
      'new' => 'New user successfully registered.',
      'new_error' => 'There seems to be a problem processing your request. Please contact the administrator.',
      'account_inactive' => 'Your account is inactive.  Please contact the administrator.',
      'credentials_error' => 'The credentials you entered did not match our records.',
      'unauthorized' => 'You are not authorized to access this resource.',
      'identical_user' => 'The user you are trying to register already exists.',
      'identical_role' => 'The role for the user you are trying to register already exists.',
      'update' => 'User information updated successfully.',
      'update_error' => 'There seems to be a problem processing your request. Please contact the administrator.',
    ),
    'plan' => 
    array (
      'new' => 'created a new plan,',
      'update' => 'updated plan,',
      'new_build' => 'New plan successfully created.',
      'build_error' => 'There seems to be a problem while building the plan.  Please contact the administrator.',
      'build_update' => 'details has been updated successfully.',
      'build_update_error' => 'There seems to be a problem while building the plan.  Please contact the administrator.',
      'session_error' => 'There seems to be a problem processing your plan build. Make sure if plan was created.  If not, please contact the administrator for this error.',
      'users_non_responses' => 'User has not submitted responses yet.',
      'response_success' => 'Your plan has been posted successfully.',
      'response_error' => 'There seems to be a problem processing your response. Please contact the administrator.',
      'response_updated' => 'has updated',
      'response_resolved' => 'resolved',
      'no_users_found' => 'No users found.',
      'no_plans_found' => 'No plans found.',
      'no_responses_found' => 'No responses found.',
      'no_activities_found' => 'No activities found.',
    ),
    'system' => 
    array (
      'update_success' => 'Fields successfully updated',
      'update_error' => 'Fields need to be modified for update.',
      'file_update_error' => 'There seems to be a problem processing your update.  Please contact the administrator.',
      'email_error' => 'Operation executed successfully.  However, there was a problem while sending email.',
      'activity_stream_error' => 'Operation executed successfully.  However, there was a problem logging to the stream.',
    ),
  ),
  'mail' => 
  array (
    'subjects' => 
    array (
      'admin_system' => 'Test Planner System Error.',
      'plan_created' => 'New test plan has been assigned to you.',
      'plan_updated' => 'Existing plan has been updated.',
      'ticket_response' => 'Response from',
      'email_error' => 'Confirmation email error.',
    ),
  ),
) ;