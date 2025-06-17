<?php

namespace Database\Seeders;

use App\Models\Contracttype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ct1 = new Contracttype();
        $ct1->name = 'NOMBRADO';
        $ct1->save();

        $ct2 = new Contracttype();
        $ct2->name = 'CONTRATO PERMANENTE';
        $ct2->save();

        $ct3 = new Contracttype();
        $ct3->name = 'CONTRATO EVENTUAL';
        $ct3->save();
    }
}
