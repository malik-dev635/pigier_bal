<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'class',
        'phone',
        'registration_ip',
        'device_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers de rôle (basés sur la colonne `role`)
    |--------------------------------------------------------------------------
    */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProfesseur(): bool
    {
        return $this->role === 'professeur';
    }

    public function isEleve(): bool
    {
        return $this->role === 'eleve';
    }

    /**
     * Types de catégories sur lesquelles l'utilisateur peut voter.
     *
     * @return array<int, string>
     */
    public function votableCategoryTypes(): array
    {
        return match ($this->role) {
            'professeur' => ['professeur', 'both'],
            'eleve' => ['eleve', 'both'],
            default => ['eleve', 'professeur', 'both'],
        };
    }

    /**
     * A-t-il déjà voté dans cette catégorie ?
     */
    public function hasVotedIn(int $categoryId): bool
    {
        return $this->votes()->where('category_id', $categoryId)->exists();
    }
}
