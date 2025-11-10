<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'date',
    ];
    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }
    
    public function detailsTransaction()
    {
        return $this->hasOne(TransactionDetail::class);
    }
}
