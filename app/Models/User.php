<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     required={"first_name", "last_name", "phone", "cni", "password"},
 *     @OA\Property(property="id", type="integer", format="int64", description="ID de l'utilisateur"),
 *     @OA\Property(property="first_name", type="string", description="Prénom de l'utilisateur"),
 *     @OA\Property(property="last_name", type="string", description="Nom de l'utilisateur"),
 *     @OA\Property(property="phone", type="string", description="Numéro de téléphone de l'utilisateur"),
 *     @OA\Property(property="cni", type="string", description="Numéro de la carte d'identité nationale"),
 *     @OA\Property(property="has_verified_phone", type="boolean", description="Indique si le téléphone est vérifié"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de mise à jour")
 * )
 */


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'cni',
        'password',
        'phone_verification_code',
        'phone_verification_expires_at',
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
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
public function comptes()
{
    return $this->hasMany(Compte::class);
}
 // 🔗 Un compte appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Un compte peut avoir plusieurs transferts
    public function transferts()
    {
        return $this->hasMany(Transfert::class);
    }

    // 🔗 Un compte peut émettre plusieurs transactions
    public function transactionsEmises()
    {
        return $this->hasMany(DetailsTransaction::class, 'compte_emetteur');
    }

    // 🔗 Un compte peut recevoir plusieurs transactions
    public function transactionsRecues()
    {
        return $this->hasMany(DetailsTransaction::class, 'compte_recepteur');
    }
}