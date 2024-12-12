<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guest;
use App\Models\Package;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Service;
use Illuminate\Database\Eloquent\SoftDeletes;
class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name', 
        'type', 
        'pax',
        'status',
        'date', 
        'totalPrice',
        'time', 
        'location', 
        'description', 
        'coverPhoto',
        'packages',
        'user_id',
        'archived',
        'payment_status',
    ];
    protected $dates = ['deleted_at']; // To track soft delete timestamps
    // Relationship to guest
    public function guest()
{
    return $this->hasMany(Guest::class, 'event_id');
}

    public function package()
{
    return $this->belongsTo(Package::class);
}
 
 
    // Relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

        public function inventories()
    {
        return $this->belongsToMany(Inventory::class);
    }


    public function guests()
    {
        return $this->hasMany(Guest::class, 'event_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'event_package', 'event_id', 'package_id');
    }

// Function to retrieve all services for a specific event


}
