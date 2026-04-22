<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courier;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $couriers = [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'jnt', 'name' => 'J&T Express'],
            ['code' => 'sicepat', 'name' => 'SiCepat'],
            ['code' => 'anteraja', 'name' => 'Anteraja'],
            ['code' => 'tiki', 'name' => 'TIKI'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'lion', 'name' => 'Lion Parcel'],
            ['code' => 'ninja', 'name' => 'Ninja Xpress'],
            ['code' => 'idexpress', 'name' => 'ID Express'],
        ];

        foreach ($couriers as $courier) {
            Courier::updateOrCreate(['code' => $courier['code']], $courier);
        }
    }
}
