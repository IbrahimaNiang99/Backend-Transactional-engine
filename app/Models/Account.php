<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'account_number',
        'balance'
    ];
}
public function user()
{
    return $this->belongsTo(User::class);
}

public function transferts()
{
    return $this->hasMany(Transfert::class);
}

public function transactionsEmises()
{
    return $this->hasMany(DetailsTransaction::class, 'compte_emetteur');
}

public function transactionsRecues()
{
    return $this->hasMany(DetailsTransaction::class, 'compte_recepteur');
}
