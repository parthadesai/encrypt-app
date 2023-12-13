<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::create([
            'data_key' => 'encryption_key',
            'data_value' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456',
        ]);
    }
}
