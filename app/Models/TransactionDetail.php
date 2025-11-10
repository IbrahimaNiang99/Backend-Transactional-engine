<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $transfer_id
 * @property int $sender_account_id
 * @property int $receiver_account_id
 */
class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transfer_id',
        'sender_account_id',
        'receiver_account_id',
    ];

    /**
     * Get the sender account for the transaction detail.
     */
    public function senderAccount()
    {
        return $this->belongsTo(Compte::class, 'sender_account_id');
    }
    
    /**
     * Get the receiver account for the transaction detail.
     */
    public function receiverAccount()
    {
        return $this->belongsTo(Compte::class, 'receiver_account_id');
    }

    /**
     * Get the transfer associated with the transaction detail.
     */
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}