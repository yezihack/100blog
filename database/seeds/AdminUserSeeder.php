<?php

use Illuminate\Database\Seeder;
use \App\Models\Users;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::truncate();
        Users::create([
            'name' => 'admin',
            'pass' => '3ce4fd4f86bee07da12c68fcc5a43b34',
            'salt' => 'dNzCWPvHFnNHp7akqV2a7TIbmwBlfIk7',
        ]);
    }
}
