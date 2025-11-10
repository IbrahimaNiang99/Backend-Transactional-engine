<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_number',
        'balance',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transferts()
    {
        return $this->hasMany(Transfer::class);
    }

    public function transactionsEmises()
    {
        return $this->hasMany(TransactionDetail::class, 'compte_emetteur');
    }

    public function transactionsRecues()
    {
        return $this->hasMany(TransactionDetail::class, 'compte_recepteur');
    }
}
