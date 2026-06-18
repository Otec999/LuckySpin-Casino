<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfitWithdraw extends Model
{
    protected $table = 'profit_withdraws';
    
    protected $fillable = [
        'amount',
        'wallet_type',
        'wallet_address',
        'status',
        'txid',
    ];
}
