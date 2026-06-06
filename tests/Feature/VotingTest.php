<?php

namespace Tests\Feature;

use App\Livewire\Vote\CategoryList;
use App\Livewire\Vote\CategoryVote;
use App\Models\Category;
use App\Models\Nominee;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VotingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    private function eleve(): User
    {
        $u = User::factory()->create(['role' => 'eleve']);
        $u->assignRole('eleve');
        return $u;
    }

    private function prof(): User
    {
        $u = User::factory()->create(['role' => 'professeur']);
        $u->assignRole('professeur');
        return $u;
    }

    public function test_eleve_peut_voter_dans_une_categorie_ouverte(): void
    {
        $eleve = $this->eleve();
        $cat = Category::create(['name' => 'Cat A', 'voter_type' => 'eleve', 'is_active' => true]);
        $nominee = Nominee::create(['category_id' => $cat->id, 'first_name' => 'Jean', 'last_name' => 'Test', 'is_active' => true]);
        Nominee::create(['category_id' => $cat->id, 'first_name' => 'Autre', 'last_name' => 'Candidat', 'is_active' => true]);

        Livewire::actingAs($eleve)
            ->test(CategoryVote::class, ['category' => $cat])
            ->call('vote', $nominee->id)
            ->assertSet('votedNomineeId', $nominee->id);

        $this->assertDatabaseHas('votes', [
            'user_id' => $eleve->id, 'category_id' => $cat->id, 'nominee_id' => $nominee->id,
        ]);
    }

    public function test_recompense_visible_seulement_avec_2_candidats(): void
    {
        $eleve = $this->eleve();

        $avecDeux = Category::create(['name' => 'Avec deux candidats', 'voter_type' => 'eleve', 'is_active' => true]);
        Nominee::create(['category_id' => $avecDeux->id, 'first_name' => 'A', 'last_name' => 'A', 'is_active' => true]);
        Nominee::create(['category_id' => $avecDeux->id, 'first_name' => 'B', 'last_name' => 'B', 'is_active' => true]);

        $avecUn = Category::create(['name' => 'Avec un candidat', 'voter_type' => 'eleve', 'is_active' => true]);
        Nominee::create(['category_id' => $avecUn->id, 'first_name' => 'C', 'last_name' => 'C', 'is_active' => true]);

        Livewire::actingAs($eleve)
            ->test(CategoryList::class)
            ->assertSee('Avec deux candidats')
            ->assertDontSee('Avec un candidat');
    }

    public function test_un_seul_vote_par_categorie(): void
    {
        $eleve = $this->eleve();
        $cat = Category::create(['name' => 'Cat B', 'voter_type' => 'eleve', 'is_active' => true]);
        $n1 = Nominee::create(['category_id' => $cat->id, 'first_name' => 'A', 'last_name' => 'A', 'is_active' => true]);
        $n2 = Nominee::create(['category_id' => $cat->id, 'first_name' => 'B', 'last_name' => 'B', 'is_active' => true]);

        $comp = Livewire::actingAs($eleve)->test(CategoryVote::class, ['category' => $cat]);
        $comp->call('vote', $n1->id);
        $comp->call('vote', $n2->id); // doit être refusé

        $this->assertEquals(1, Vote::where('user_id', $eleve->id)->where('category_id', $cat->id)->count());
        $this->assertDatabaseHas('votes', ['user_id' => $eleve->id, 'nominee_id' => $n1->id]);
    }

    public function test_prof_ne_peut_pas_voter_dans_categorie_eleve(): void
    {
        $prof = $this->prof();
        $cat = Category::create(['name' => 'Cat C', 'voter_type' => 'eleve', 'is_active' => true]);

        Livewire::actingAs($prof)
            ->test(CategoryVote::class, ['category' => $cat])
            ->assertForbidden();
    }

    public function test_vote_impossible_si_categorie_fermee(): void
    {
        $eleve = $this->eleve();
        $cat = Category::create(['name' => 'Cat D', 'voter_type' => 'eleve', 'is_active' => false]);
        $n = Nominee::create(['category_id' => $cat->id, 'first_name' => 'X', 'last_name' => 'Y', 'is_active' => true]);
        Nominee::create(['category_id' => $cat->id, 'first_name' => 'Z', 'last_name' => 'W', 'is_active' => true]);

        Livewire::actingAs($eleve)
            ->test(CategoryVote::class, ['category' => $cat])
            ->call('vote', $n->id);

        $this->assertDatabaseCount('votes', 0);
    }

    public function test_nominee_sans_preuve_non_eligible_si_preuve_requise(): void
    {
        $cat = Category::create([
            'name' => 'Cat E', 'voter_type' => 'eleve', 'is_active' => true,
            'requires_proof' => true, 'proof_type' => 'file',
        ]);
        $sansPreuve = Nominee::create(['category_id' => $cat->id, 'first_name' => 'No', 'last_name' => 'Proof', 'is_active' => true]);
        $avecPreuve = Nominee::create(['category_id' => $cat->id, 'first_name' => 'With', 'last_name' => 'Proof', 'is_active' => true, 'proof_file' => 'proofs/x.pdf']);

        $votables = $cat->votableNominees()->pluck('id');

        $this->assertFalse($votables->contains($sansPreuve->id));
        $this->assertTrue($votables->contains($avecPreuve->id));
    }
}
