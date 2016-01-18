<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ACCOUNTS
        DB::table('roles')->insert([
            'id'          => 1,
            'name'        => 'root',
            'description' => 'Use this account with extreme caution. When using this account it is possible to cause irreversible damage to the system.'
        ]);

        DB::table('roles')->insert([
            'id'          => 2,
            'name'        => 'administrator',
            'description' => 'Full access to create, edit, and update companies, and orders.'
        ]);

        DB::table('roles')->insert([
            'id'          => 3,
            'name'        => 'user',
            'description' => 'A standard user that can have a licence assigned to them. No administrative features.'
        ]);

        // Set initial temp password, don't forget to change
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name'  => 'Admin',
            'email'      => 'admin@mophie.com',
            'password'   => bcrypt('123456'),
            'active'     => 1,
            'created_at' => date('Y-m-d H:i:s', time())
        ]);

        // Root account for admin
        DB::table('user_role')->insert([
            'user_id'    => 1,
            'role_id'    => 1,
            'created_at' => date('Y-m-d H:i:s', time())
        ]);

        // Admin account for admin
        DB::table('user_role')->insert([
            'user_id'    => 1,
            'role_id'    => 2,
            'created_at' => date('Y-m-d H:i:s', time())
        ]);

        // User account for admin
        DB::table('user_role')->insert([
            'user_id'    => 1,
            'role_id'    => 3,
            'created_at' => date('Y-m-d H:i:s', time())
        ]);


        // SYSTEM CONFIGURATION
        DB::table('system')->insert([
            'section' => 'admin',
            'type'    => 'name',
            'value'   => 'Test Planner Admin'
        ]);

        DB::table('system')->insert([
            'section' => 'admin',
            'type'    => 'email',
            'value'   => 'tp.admin@mophie.com'
        ]);

        DB::table('system')->insert([
            'section' => 'jira',
            'type'    => 'domain',
            'value'   => 'https://mophie.atlassian.net'
        ]);

        DB::table('system')->insert([
            'section' => 'jira',
            'type'    => 'rest_url',
            'value'   => 'https://mophie.atlassian.net/rest/api/2'
        ]);

        DB::table('system')->insert([
            'section' => 'pagination',
            'type'    => 'tables',
            'value'   => '50'
        ]);

        DB::table('system')->insert([
            'section' => 'pagination',
            'type'    => 'activity_streams',
            'value'   => '10'
        ]);

        DB::table('system')->insert([
            'section' => 'pagination',
            'type'    => 'dashboard',
            'value'   => '5'
        ]);

        DB::table('system')->insert([
            'section' => 'user',
            'type'    => 'new',
            'value'   => 'New user successfully registered.'
        ]);

        DB::table('system')->insert([
            'section' => 'user',
            'type'    => 'update',
            'value'   => 'Existing user successfully updated.'
        ]);

        DB::table('system')->insert([
            'section' => 'user',
            'type'    => 'inactive',
            'value'   => 'Your account is inactive.  Please contact the administrator.'
        ]);

        DB::table('system')->insert([
            'section' => 'user',
            'type'    => 'credentials_problem',
            'value'   => 'The credentials you entered did not match our records.'
        ]);

        DB::table('system')->insert([
            'section' => 'user',
            'type'    => 'unauthorized',
            'value'   => 'You are not authorized to access this section.'
        ]);

        DB::table('system')->insert([
            'section' => 'user',
            'type'    => 'identical_role',
            'value'   => 'The role for the user you are trying to register already exists.'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'new',
            'value'   => 'created a new plan,'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'update',
            'value'   => 'updated plan,'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'new_build',
            'value'   => 'New plan successfully created.'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'build_update',
            'value'   => 'details has been updated successfully.'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'build_error',
            'value'   => 'Plan building cannot be done at the current moment.  Please try again later.'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'session',
            'value'   => 'Session data is missing.'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'non_responses',
            'value'   => 'Users have not submitted responses yet.',
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'response_success',
            'value'   => 'Your plan has been posted successfully',
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'response_update',
            'value'   => 'has updated'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'response_error',
            'value'   => 'There seems to be a problem processing your response. Please try again later.'
        ]);

        DB::table('system')->insert([
            'section' => 'plan',
            'type'    => 'response_resolve',
            'value'   => 'resolved'
        ]);
    }
}