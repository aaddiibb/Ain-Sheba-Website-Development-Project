<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(LegalAreaSeeder::class);

        // Admin
        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@ainsheba.test',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Lawyers
        User::create([
            'name'      => 'Lawyer One',
            'email'     => 'lawyer1@ainsheba.test',
            'password'  => Hash::make('password'),
            'role'      => 'lawyer',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Lawyer Two',
            'email'     => 'lawyer2@ainsheba.test',
            'password'  => Hash::make('password'),
            'role'      => 'lawyer',
            'is_active' => true,
        ]);

        // Citizens — Bangladeshi names
        $citizens = [
            ['name' => 'Rahim Uddin',   'email' => 'rahim@ainsheba.test'],
            ['name' => 'Fatema Begum',  'email' => 'fatema@ainsheba.test'],
            ['name' => 'Karim Hossain', 'email' => 'karim@ainsheba.test'],
            ['name' => 'Nasrin Akter',  'email' => 'nasrin@ainsheba.test'],
            ['name' => 'Jamal Uddin',   'email' => 'jamal@ainsheba.test'],
        ];

        foreach ($citizens as $citizen) {
            User::create([
                'name'      => $citizen['name'],
                'email'     => $citizen['email'],
                'password'  => Hash::make('password'),
                'role'      => 'citizen',
                'is_active' => true,
            ]);
        }
    }
}
