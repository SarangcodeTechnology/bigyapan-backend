<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'municipals';
    protected $guarded = [];

    public function district(){
        return $this->belongsTo(District::class,'dristict_id');
    }
}
