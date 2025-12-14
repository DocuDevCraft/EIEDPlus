<?php

namespace Modules\Users\Database\Seeders;

use Hatamiarash7\PFaker\PFaker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Admin',
                'last_name' => 'EIED',
                'email' => 'admin@eied.com',
                'password' => bcrypt('12345678'),
                'phone' => '09901932636',
                'role' => 'admin',
                'status' => 'active',
            ],
        ]);


//        DB::table('users')->insert([
//            'first_name' => PFaker::firstName(),
//            'last_name' => PFaker::firstName(),
//            'email' => PFaker::email(),
//            'phone' => PFaker::mobile(),
//            'biography' => PFaker::paragraph(),
//            'role' => 'user',
//            'password' => Hash::make('12345678'),
//        ]);

//        Model::unguard();



        // $this->call("OthersTableSeeder");
    }
}
