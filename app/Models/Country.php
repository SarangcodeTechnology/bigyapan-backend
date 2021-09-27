<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'countries';
    protected $guarded = [];

    public function provinces(){
        return $this->hasMany(Province::class,'country_id')->orderBy('title');
    }
}
