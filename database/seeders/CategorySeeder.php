<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Matematyka', 'Fizyka', 'Chemia', 'Biologia', 'Historia', 'Geografia', 'Język polski', 'Języki obce',
            'Wiedza ogólna', 'Technologia', 'Muzyka', 'Film i TV',
            'Gry komputerowe', 'Gry planszowe',
            'Piłka nożna', 'Koszykówka', 'Tenis', 'Siatkówka'
        ];

        foreach ($names as $name) {
            $slug = mb_strtolower(preg_replace('/[^\p{L}0-9]+/u', '-', $name));
            Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'description' => null]
            );
        }
    }
}
