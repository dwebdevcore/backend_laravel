<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('user')
        ]);
    }
}
