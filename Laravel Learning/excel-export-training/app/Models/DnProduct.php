<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnProduct extends Model
{
    // use HasFactory;
    public function deliveryNote(){
      return $this->belongsTo(DeliveryNote::class,'dn_id');
    }

    public function product(){
      return $this->belongsTo(Product::class,'product_id');
    }
}
