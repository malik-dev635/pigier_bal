<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'voter_type',
        'nominee_type',
        'is_active',
        'max_nominees',
        'requires_proof',
        'proof_type',
        'candidacy_token',
        'candidacy_open',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'requires_proof' => 'boolean',
            'max_nominees' => 'integer',
            'candidacy_open' => 'boolean',
        ];
    }

    /**
     * Génère automatiquement le slug et le jeton de candidature.
     */
    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (blank($category->slug)) {
                $category->slug = static::uniqueSlug($category->name, $category->id);
            }
            if (blank($category->candidacy_token)) {
                $category->candidacy_token = Str::random(40);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function nominees(): HasMany
    {
        return $this->hasMany(Nominee::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Nominés réellement éligibles au vote (actifs + preuve fournie si requise).
     */
    public function votableNominees(): HasMany
    {
        return $this->nominees()->votable($this);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Catégories visibles pour un type de votant ('eleve'|'professeur').
     */
    public function scopeForVoterTypes(Builder $query, array $types): Builder
    {
        return $query->whereIn('voter_type', $types);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function getImageUrlAttribute(): ?string
    {
        // url() préfixe avec le bon dossier (ex: /bal) en sous-dossier.
        return $this->image ? url(Storage::url($this->image)) : null;
    }

    public function isOpen(): bool
    {
        return $this->is_active;
    }

    public function candidacyUrl(): string
    {
        return route('candidacy.show', $this->candidacy_token);
    }

    /** Les nominés sont-ils des entités (association, club, événement) ? */
    public function isEntity(): bool
    {
        return $this->nominee_type === 'entity';
    }

    /** Libellé du champ « nom » selon le type de nominé. */
    public function nameLabel(): string
    {
        return $this->isEntity() ? "Nom de l'association / du groupe" : 'Nom';
    }

    public function needsUrl(): bool
    {
        return $this->requires_proof && in_array($this->proof_type, ['url', 'both'], true);
    }

    public function needsFile(): bool
    {
        return $this->requires_proof && in_array($this->proof_type, ['file', 'both'], true);
    }

    public function voterTypeLabel(): string
    {
        return match ($this->voter_type) {
            'eleve' => 'Étudiants',
            'professeur' => 'Professeurs',
            'both' => 'Étudiants & Professeurs',
            default => $this->voter_type,
        };
    }
}
