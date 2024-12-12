<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'item_name', 'total_items', 'sorted_items', 'status'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
