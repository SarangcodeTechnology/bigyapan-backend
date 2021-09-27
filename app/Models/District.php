<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'districts';
    protected $guarded = [];

    public function municipals(){
        return $this->hasMany(Municipality::class,'district_id')->orderBy('title');
    }

    public function province(){
        return $this->belongsTo(Province::class,'province_id');
    }
}
