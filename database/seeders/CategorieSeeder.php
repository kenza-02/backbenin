<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'libelle' => 'Tech'],
            ['id' => 2, 'libelle' => 'Formation'],
            ['id' => 3, 'libelle' => 'Civic Tech'],
            ['id' => 4, 'libelle' => 'Innovation'],
            ['id' => 5, 'libelle' => 'Sensibilisation'],
            ['id' => 6, 'libelle' => 'Citoyennete'],
            ['id' => 7, 'libelle' => 'Evenement'],
            ['id' => 8, 'libelle' => 'Activites'],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }
    }
}
