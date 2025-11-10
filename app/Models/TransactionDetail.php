<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transfer_id',
        'sender_account_id',
        'receiver_account_id',
    ];
        // 🔗 Compte émetteur
}
public function compteEmetteur()
{
    return $this->belongsTo(Compte::class, 'compte_emetteur');
}
 // 🔗 Compte récepteur
public function compteRecepteur()
{
    return $this->belongsTo(Compte::class, 'compte_recepteur');
}
// 🔗 Détail lié à un transfert
    public function transfert()
    {
        return $this->belongsTo(Transfert::class);
    }
