<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'item_categories';
    protected $guarded = [];

    public function provinces(){
        return $this->hasMany(ItemSubCategory::class,'item_category_id')->orderBy('title');
    }
}
