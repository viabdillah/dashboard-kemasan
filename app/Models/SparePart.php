<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
  protected $fillable = [
    'name', 'part_number', 'description', 'quantity', 'location', 'low_stock_threshold'
];
}
