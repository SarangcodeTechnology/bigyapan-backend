<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'provinces';
    protected $guarded = [];

    public function districts(){
        return $this->hasMany(District::class,'province_id')->orderBy('title');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
