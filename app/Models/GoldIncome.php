<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldIncome extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'account_id',
        'games_id',
        'server_id',
        'description',
        'created_by',
    ];

    public function games()
    {
        return $this->hasOne('App\Models\Games', 'id', 'games_id');
    }

    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }

    public function accountGames()
    {
        return $this->hasOne('App\Models\AccountGames', 'id', 'account_id');
    }

    public function farmer()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
