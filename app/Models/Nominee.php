<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Nominee extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'first_name',
        'last_name',
        'photo',
        'class',
        'description',
        'proof_url',
        'proof_file',
        'is_active',
        'is_approved',
        'is_votable',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'is_votable' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
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
     * Nominés éligibles au vote : actifs, et si la catégorie exige une preuve,
     * la preuve attendue doit être présente.
     */
    public function scopeVotable(Builder $query, Category $category): Builder
    {
        $query->where('is_active', true)->where('is_approved', true)->where('is_votable', true);

        if (! $category->requires_proof) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($category) {
            if ($category->proof_type === 'url') {
                $q->whereNotNull('proof_url');
            } elseif ($category->proof_type === 'file') {
                $q->whereNotNull('proof_file');
            } else { // both : au moins une preuve fournie
                $q->whereNotNull('proof_url')->orWhereNotNull('proof_file');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        // url() préfixe avec le bon dossier (ex: /bal) en sous-dossier, et reste
        // correct en local. Storage::url() seul renvoie un chemin relatif à la racine.
        return $this->photo ? url(Storage::url($this->photo)) : null;
    }

    public function getProofFileUrlAttribute(): ?string
    {
        return $this->proof_file ? url(Storage::url($this->proof_file)) : null;
    }

    /**
     * La preuve requise est-elle fournie pour cette catégorie ?
     */
    public function hasRequiredProof(Category $category): bool
    {
        if (! $category->requires_proof) {
            return true;
        }

        return match ($category->proof_type) {
            'url' => filled($this->proof_url),
            'file' => filled($this->proof_file),
            'both' => filled($this->proof_url) || filled($this->proof_file),
            default => true,
        };
    }
}
