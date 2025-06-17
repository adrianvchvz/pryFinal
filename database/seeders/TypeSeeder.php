<?php

namespace Database\Seeders;

use App\Models\Vehicletype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $t1 = new Vehicletype();
        $t1->name = 'CAMIONETA';
        $t1->save();

        $t2 = new Vehicletype();
        $t2->name = 'CARGADOR FRONTAL';
        $t2->save();

        $t3 = new Vehicletype();
        $t3->name = 'COMPACTADORA';
        $t3->save();

        $t4 = new Vehicletype();
        $t4->name = 'MINICARGADOR';
        $t4->save();

        $t5 = new Vehicletype();
        $t5->name = 'MOTOCARGUERA';
        $t5->save();

        $t6 = new Vehicletype();
        $t6->name = 'RETROEXCAVADORA';
        $t6->save();

        $t7 = new Vehicletype();
        $t7->name = 'SEMITRAILER';
        $t7->save();
    }
}
