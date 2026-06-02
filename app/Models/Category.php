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
        'is_active',
        'max_nominees',
        'requires_proof',
        'proof_type',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'requires_proof' => 'boolean',
            'max_nominees' => 'integer',
        ];
    }

    /**
     * Génère automatiquement le slug à partir du nom.
     */
    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (blank($category->slug)) {
                $category->slug = static::uniqueSlug($category->name, $category->id);
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
        return $this->image ? Storage::url($this->image) : null;
    }

    public function isOpen(): bool
    {
        return $this->is_active;
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
            'eleve' => 'Élèves',
            'professeur' => 'Professeurs',
            'both' => 'Élèves & Professeurs',
            default => $this->voter_type,
        };
    }
}
