<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'item_images';
    protected $guarded = [];

    public function item(){
        return $this->belongsTo(Item::class,'item_id');
    }
}
