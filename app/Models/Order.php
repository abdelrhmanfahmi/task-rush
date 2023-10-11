<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['name' , 'phone' , 'address' , 'payment_type' , 'user_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class , 'order_products' , 'order_id' , 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
