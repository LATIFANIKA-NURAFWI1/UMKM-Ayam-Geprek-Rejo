<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Ayam Geprek',    'slug' => 'ayam-geprek',    'icon' => '🍗', 'sort_order' => 1],
            ['name' => 'Ayam Goreng',    'slug' => 'ayam-goreng',    'icon' => '🍖', 'sort_order' => 2],
            ['name' => 'Nasi & Lauk',    'slug' => 'nasi-lauk',      'icon' => '🍚', 'sort_order' => 3],
            ['name' => 'Minuman',        'slug' => 'minuman',        'icon' => '🥤', 'sort_order' => 4],
            ['name' => 'Snack & Extra',  'slug' => 'snack-extra',    'icon' => '🍟', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], array_merge($cat, ['is_active' => true]));
        }

        $this->command->info('✅ Categories seeded: ' . count($categories) . ' kategori');
    }
}
