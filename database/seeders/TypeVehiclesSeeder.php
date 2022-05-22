<?php

namespace Database\Seeders;

use App\Models\TypeVehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TypeVehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TypeVehicle::firstOrCreate([
            'name' => "official",
            'fee' => "0.05",
        ]);
        TypeVehicle::firstOrCreate([
            'name' => "not official",
            'fee' => "0.5",
        ]);
    }
}
