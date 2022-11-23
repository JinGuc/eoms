<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::create([
            'name' => '运维管理员',
            'email' => 'J_eoms',
            'password' => bcrypt('J_eoms_p'),
        ]);
    }
}
