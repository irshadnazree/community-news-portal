<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'description' => 'Latest technology news and updates'],
            ['name' => 'Politics', 'description' => 'Political news and analysis'],
            ['name' => 'Sports', 'description' => 'Sports news and updates'],
            ['name' => 'Entertainment', 'description' => 'Entertainment and celebrity news'],
            ['name' => 'Business', 'description' => 'Business and finance news'],
            ['name' => 'Health', 'description' => 'Health and wellness news'],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($categoryData['name'])],
                [
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                ]
            );
        }
    }
}
