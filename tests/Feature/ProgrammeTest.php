<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgrammeTest extends TestCase
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

    public function test_ajout_avec_nouvelle_recompense_saisie(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.programme.nominee'), [
                'new_category' => 'Prix spécial du jury',
                'last_name' => 'Invité Mystère',
            ])
            ->assertRedirect(route('admin.programme'));

        $cat = Category::where('name', 'Prix spécial du jury')->first();
        $this->assertNotNull($cat, 'La nouvelle récompense doit être créée.');
        $this->assertFalse($cat->is_active, 'La récompense de programme ne doit pas être ouverte au vote.');
        $this->assertEquals(1, $cat->nominees()->count());
        $this->assertFalse($cat->nominees()->first()->is_votable, 'Hors vote par défaut.');
    }

    public function test_ajout_sur_recompense_existante(): void
    {
        $cat = Category::create(['name' => 'Récompense Existante', 'voter_type' => 'eleve']);

        $this->actingAs($this->admin())
            ->post(route('admin.programme.nominee'), [
                'category_id' => $cat->id,
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'is_votable' => '1',
            ])
            ->assertRedirect(route('admin.programme'));

        $this->assertEquals(1, $cat->nominees()->count());
        $this->assertTrue($cat->nominees()->first()->is_votable);
    }

    public function test_erreur_si_aucune_recompense(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.programme.nominee'), [
                'last_name' => 'Sans Récompense',
            ])
            ->assertSessionHasErrors('category_id');

        $this->assertEquals(0, \App\Models\Nominee::count());
    }
}
