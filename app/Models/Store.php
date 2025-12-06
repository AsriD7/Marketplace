<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id','nama_toko','slug','deskripsi','alamat_toko','jam_operasional','gambar','is_active'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
