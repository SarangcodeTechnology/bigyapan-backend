<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $connection = 'bigyapan_data_db';
    protected $table = 'items';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item_category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function item_sub_category()
    {
        return $this->belongsTo(ItemSubCategory::class, 'item_sub_category_id');
    }

    public function item_images()
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }
}
