<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Apģērbs', 'Tehnika', 'Grāmatas', 'Skaistums', 'Ceļojumi', 'Cits'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
