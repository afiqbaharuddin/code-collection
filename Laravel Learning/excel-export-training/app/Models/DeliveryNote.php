<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    // use HasFactory;
    public function dnProducts(){
      return $this->hasMany(DnProduct::class,'dn_id');
    }
}
