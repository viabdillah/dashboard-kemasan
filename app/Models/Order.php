<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_name',
        'product_name',
        'packaging_type',
        'packaging_label',
        'size',
        'net_weight', 
        'price_per_piece',
        'quantity',
        'pirt_number',
        'halal_number',
        'has_design',
        'status',
        'paid_at',
        'design_file_path',
    ];
    protected $casts = [
        'paid_at' => 'datetime',
    ];
}