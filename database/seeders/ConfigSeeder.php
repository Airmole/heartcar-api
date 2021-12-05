<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert([
            'distance_start' => '3',
            'distance_end' => '10',
            'pooling_price' => '1.39',
            'inpooling_price' => '1.85',

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('configs')->insert([
            'distance_start' => '10',
            'distance_end' => '35',
            'pooling_price' => '1.16',
            'inpooling_price' => '1.55',

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('configs')->insert([
            'distance_start' => '35',
            'distance_end' => '50',
            'pooling_price' => '0.86',
            'inpooling_price' => '1.40',

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('configs')->insert([
            'distance_start' => '50',
            'distance_end' => '150',
            'pooling_price' => '0.95',
            'inpooling_price' => '1.25',

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
