<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $connection = 'bigyapan_data_db';
    protected $table = 'user_details';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function account_type(){
        return $this->belongsTo(AccountType::class,'account_type_id');
    }

    public function address_municipality(){
        return $this->belongsTo(Municipality::class,'address_municipality_id');
    }

    public function address_district(){
        return $this->belongsTo(District::class,'address_district_id');
    }

    public function address_province(){
        return $this->belongsTo(Province::class,'address_province_id');
    }

    public function address_country(){
        return $this->belongsTo(Country::class,'address_country_id');
    }
}
