<?php

namespace Database\Seeders;

use App\Models\Employeetype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $et1 = new Employeetype();
        $et1->name = 'CONDUCTOR';
        $et1->save();

        $et1 = new Employeetype();
        $et1->name = 'AYUDANTE';
        $et1->save();
    }
}
