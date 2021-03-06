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
    }
}