<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'account_types';
    protected $guarded = [];
}
