<?php

namespace Database\Seeders;

use App\Models\LegalArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LegalAreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Constitutional Rights', 'icon' => 'bi-shield-check'],
            ['name' => 'Labor Law',              'icon' => 'bi-briefcase'],
            ['name' => 'Tenant Rights',          'icon' => 'bi-house-door'],
            ['name' => 'Consumer Rights',        'icon' => 'bi-cart-check'],
            ['name' => 'Women\'s Rights',        'icon' => 'bi-person-hearts'],
            ['name' => 'Environmental Law',      'icon' => 'bi-tree'],
        ];

        foreach ($areas as $area) {
            LegalArea::create([
                'name' => $area['name'],
                'slug' => Str::slug($area['name']),
                'icon' => $area['icon'],
            ]);
        }
    }
}
