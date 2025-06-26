<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ct1 = new Brand();
        $ct1->name = 'TOYOTA';
        $ct1->save();

        $ct2 = new Brand();
        $ct2->name = 'HYUNDAI';
        $ct2->save();

        $ct3 = new Brand();
        $ct3->name = 'KIA';
        $ct3->save();
    }
}
