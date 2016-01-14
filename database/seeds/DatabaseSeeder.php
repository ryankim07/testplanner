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
    }
}