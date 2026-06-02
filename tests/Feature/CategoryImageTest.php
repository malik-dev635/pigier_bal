<?php

namespace Tests\Feature;

use App\Livewire\Admin\CategoryManager;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    private function admin(): User
    {
        $u = User::factory()->create(['role' => 'admin']);
        $u->assignRole('admin');
        return $u;
    }

    public function test_admin_peut_creer_une_categorie_avec_affiche(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->admin())
            ->test(CategoryManager::class)
            ->call('create')
            ->set('name', 'Catégorie Affiche')
            ->set('voter_type', 'eleve')
            ->set('image', UploadedFile::fake()->image('affiche.jpg', 1200, 675))
            ->call('save')
            ->assertHasNoErrors();

        $category = Category::where('name', 'Catégorie Affiche')->first();

        $this->assertNotNull($category->image, 'Le chemin de l\'affiche doit être enregistré.');
        Storage::disk('public')->assertExists($category->image);
        $this->assertStringContainsString('categories/images', $category->image);
    }

    public function test_affiche_conservee_si_aucun_nouveau_fichier_a_la_modification(): void
    {
        Storage::fake('public');
        $cat = Category::create(['name' => 'Garde Affiche', 'voter_type' => 'eleve', 'image' => 'categories/images/old.jpg']);

        Livewire::actingAs($this->admin())
            ->test(CategoryManager::class)
            ->call('edit', $cat->id)
            ->set('description', 'maj sans nouvelle image')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('categories/images/old.jpg', $cat->fresh()->image);
    }
}
