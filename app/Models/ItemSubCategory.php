<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSubCategory extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'item_sub_categories';
    protected $guarded = [];

    public function item_category(){
        return $this->belongsTo(ItemCategory::class,'item_category_id');
    }
}
