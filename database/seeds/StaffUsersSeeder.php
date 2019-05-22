<?php

use App\Models\StaffUser;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StaffUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StaffUser::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('d0asnas08s43'),
        ]);
    }
}
