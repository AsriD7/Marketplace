<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'store_id',
        'alamat_kirim',
        'subtotal',
        'ongkir',
        'total',
        'status',
        'payment_status',
        'admin_validated_by',
        'admin_validated_at',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'ongkir' => 'integer',
        'total' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function adminValidator()
    {
        return $this->belongsTo(User::class, 'admin_validated_by');
    }
    public function productRatings()
    {
        return $this->hasMany(\App\Models\ProductRating::class);
    }
}
