<?php

namespace Database\Seeders;

use App\Models\LawyerAvailability;
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
            'name'      => 'Platform Admin',
            'email'     => 'admin@ainsheba.test',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Lawyers
        $lawyer1 = User::create([
            'name'             => 'Advocate Rahim Chowdhury',
            'email'            => 'lawyer1@ainsheba.test',
            'password'         => Hash::make('password'),
            'role'             => 'lawyer',
            'is_active'        => true,
            'bio'              => 'Constitutional and labor law expert with 12 years of experience in the High Court.',
            'consultation_fee' => 500,
        ]);

        $lawyer2 = User::create([
            'name'             => 'Advocate Nasrin Sultana',
            'email'            => 'lawyer2@ainsheba.test',
            'password'         => Hash::make('password'),
            'role'             => 'lawyer',
            'is_active'        => true,
            'bio'              => 'Specialist in women\'s rights and tenant law with over 8 years of active practice.',
            'consultation_fee' => 300,
        ]);

        // Lawyer availability
        LawyerAvailability::create(['lawyer_id' => $lawyer1->id, 'day_of_week' => 'Monday',    'start_time' => '10:00', 'end_time' => '13:00', 'is_active' => true]);
        LawyerAvailability::create(['lawyer_id' => $lawyer1->id, 'day_of_week' => 'Wednesday', 'start_time' => '10:00', 'end_time' => '13:00', 'is_active' => true]);
        LawyerAvailability::create(['lawyer_id' => $lawyer1->id, 'day_of_week' => 'Friday',    'start_time' => '14:00', 'end_time' => '17:00', 'is_active' => true]);
        LawyerAvailability::create(['lawyer_id' => $lawyer2->id, 'day_of_week' => 'Sunday',    'start_time' => '09:00', 'end_time' => '12:00', 'is_active' => true]);
        LawyerAvailability::create(['lawyer_id' => $lawyer2->id, 'day_of_week' => 'Tuesday',   'start_time' => '14:00', 'end_time' => '17:00', 'is_active' => true]);
        LawyerAvailability::create(['lawyer_id' => $lawyer2->id, 'day_of_week' => 'Thursday',  'start_time' => '14:00', 'end_time' => '17:00', 'is_active' => true]);

        // Citizens
        $citizens = [
            ['name' => 'Karim Hossain',  'email' => 'citizen1@ainsheba.test'],
            ['name' => 'Fatema Begum',   'email' => 'citizen2@ainsheba.test'],
            ['name' => 'Rafiqul Islam',  'email' => 'citizen3@ainsheba.test'],
            ['name' => 'Sumaiya Akter',  'email' => 'citizen4@ainsheba.test'],
            ['name' => 'Jahangir Alam',  'email' => 'citizen5@ainsheba.test'],
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
