<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGames extends Model
{
    protected $fillable = [
        'account_name',
        'account_email',
        'account_password',
        'account_games_id',
        'account_gold',
        'created_by',
    ];


}

