<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrateur',
                'email' => 'admin@pigier.test',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'name' => 'Professeur Démo',
                'email' => 'prof@pigier.test',
                'password' => 'password',
                'role' => 'professeur',
                'class' => 'Marketing',
            ],
            [
                'name' => 'Élève Démo',
                'email' => 'eleve@pigier.test',
                'password' => 'password',
                'role' => 'eleve',
                'class' => 'RGL3A',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'],
                    'class' => $data['class'] ?? null,
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}
