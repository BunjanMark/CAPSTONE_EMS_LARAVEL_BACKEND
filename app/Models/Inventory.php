<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category',
        'inventory_status',
        'quantity',
        'quantity_sorted',
        'equipment_status',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
