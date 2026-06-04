<?php

namespace Tests\Feature;

use App\Livewire\Admin\NomineeManager;
use App\Livewire\Public\CandidacyForm;
use App\Models\Category;
use App\Models\Nominee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CandidacyTest extends TestCase
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

    public function test_candidature_creee_en_attente_puis_visible_apres_approbation(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $cat = Category::create(['name' => 'Cat Cand', 'voter_type' => 'eleve', 'is_active' => true, 'candidacy_open' => true]);

        Livewire::test(CandidacyForm::class, ['token' => $cat->candidacy_token])
            ->set('first_name', 'Awa')
            ->set('last_name', 'Koné')
            ->set('photo', \Illuminate\Http\UploadedFile::fake()->image('moi.jpg'))
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $nominee = Nominee::where('first_name', 'Awa')->first();
        $this->assertNotNull($nominee);
        $this->assertFalse($nominee->is_approved, 'La candidature doit être en attente.');

        // Pas encore visible au vote.
        $this->assertFalse($cat->votableNominees()->whereKey($nominee->id)->exists());

        // L'admin approuve.
        Livewire::actingAs($this->admin())
            ->test(NomineeManager::class)
            ->call('approve', $nominee->id);

        $this->assertTrue($nominee->fresh()->is_approved);
        $this->assertTrue($cat->votableNominees()->whereKey($nominee->id)->exists());
    }

    public function test_photo_lien_et_fichier_enregistres(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $cat = Category::create([
            'name' => 'Cat Preuve', 'voter_type' => 'eleve', 'is_active' => true,
            'candidacy_open' => true, 'requires_proof' => true, 'proof_type' => 'both',
        ]);

        Livewire::test(CandidacyForm::class, ['token' => $cat->candidacy_token])
            ->set('first_name', 'Sara')
            ->set('last_name', 'Diallo')
            ->set('proof_url', 'https://youtube.com/watch?v=abc')
            ->set('photo', \Illuminate\Http\UploadedFile::fake()->image('p.jpg'))
            ->set('proofFile', \Illuminate\Http\UploadedFile::fake()->create('preuve.pdf', 100))
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $n = Nominee::where('first_name', 'Sara')->first();
        $this->assertNotNull($n);
        $this->assertEquals('https://youtube.com/watch?v=abc', $n->proof_url, 'Le lien doit être enregistré.');
        $this->assertNotNull($n->photo, 'La photo doit être enregistrée.');
        $this->assertNotNull($n->proof_file, 'Le fichier de preuve doit être enregistré.');
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($n->photo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($n->proof_file);
    }

    public function test_soumission_bloquee_si_candidatures_fermees(): void
    {
        $cat = Category::create(['name' => 'Cat Fermee', 'voter_type' => 'eleve', 'is_active' => true, 'candidacy_open' => false]);

        Livewire::test(CandidacyForm::class, ['token' => $cat->candidacy_token])
            ->set('first_name', 'X')
            ->set('last_name', 'Y')
            ->call('submit')
            ->assertForbidden();

        $this->assertEquals(0, Nominee::count());
    }

    public function test_jeton_genere_automatiquement_a_la_creation(): void
    {
        $cat = Category::create(['name' => 'Cat Token', 'voter_type' => 'eleve']);
        $this->assertNotEmpty($cat->candidacy_token);
        $this->assertEquals(40, strlen($cat->candidacy_token));
    }
}
