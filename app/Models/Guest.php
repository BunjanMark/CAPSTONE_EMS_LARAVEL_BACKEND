<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
class Guest extends Model
{
    use HasFactory;
    protected $table = 'guest'; // Explicitly set the table name if necessary
    protected $fillable = [
        'GuestName', 'email', 'phone', 'event_id', 'role', 'status',
    ];

    // Relationship to event
    public function event()
{
    return $this->belongsTo(Event::class, 'event_id');
}
 

}
