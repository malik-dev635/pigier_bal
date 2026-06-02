<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ---------- ÉLÈVES ----------
            ['name' => 'Meilleur Badeur', 'voter_type' => 'eleve'],
            ['name' => 'Meilleur Entrepreneur Junior', 'voter_type' => 'eleve', 'requires_proof' => true, 'proof_type' => 'file'],
            ['name' => "Artiste de l'Année", 'voter_type' => 'eleve', 'requires_proof' => true, 'proof_type' => 'both'],
            ['name' => "Meilleur Club de l'Année", 'voter_type' => 'eleve', 'requires_proof' => true, 'proof_type' => 'file'],
            ['name' => 'Meilleur Photographe / Vidéaste', 'voter_type' => 'eleve', 'requires_proof' => true, 'proof_type' => 'both'],
            ['name' => 'Prix Innovation Digitale', 'voter_type' => 'eleve', 'requires_proof' => true, 'proof_type' => 'both'],
            ['name' => 'Prix Engagement Solidaire', 'voter_type' => 'eleve', 'requires_proof' => true, 'proof_type' => 'file'],
            ['name' => "Alumni de l'Année", 'voter_type' => 'eleve'],
            ['name' => 'Meilleur(e) Orateur / Oratrice', 'voter_type' => 'eleve'],

            // ---------- PROFESSEURS ----------
            ['name' => "Événement Académique de l'Année", 'voter_type' => 'professeur'],
            ['name' => "Professeur le Plus Marquant de l'Année", 'voter_type' => 'professeur'],
            ['name' => 'Major de Promotion', 'voter_type' => 'professeur'],

            // ---------- LES DEUX ----------
            ['name' => 'Meilleur Leadership', 'voter_type' => 'both'],
            ['name' => 'Personnalité la Plus Inspirante', 'voter_type' => 'both'],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                array_merge([
                    'description' => null,
                    'is_active' => true,
                    'max_nominees' => 5,
                    'requires_proof' => false,
                    'proof_type' => null,
                ], $data)
            );
        }
    }
}
