<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $c1 = new Color();
        $c1->name = 'BLANCO';
        $c1->code = 'rgb(255,255,255)';
        $c1->save();

        $c2 = new Color();
        $c2->name = 'ROJO';
        $c2->code = 'rgb(255,0,0)';
        $c2->save();

        $c3 = new Color();
        $c3->name = 'NEGRO';
        $c3->code = 'rgb(0,0,0)';
        $c3->save();
    }
}
