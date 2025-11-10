<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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