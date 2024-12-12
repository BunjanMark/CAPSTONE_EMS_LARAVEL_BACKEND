<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Event;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments'; // Specify the plural table name explicitly

    protected $fillable = [
        'event_id',
        'user_id',
        'service_id',
        'account_role_id',
        'item',
        'number_of_items',
        'number_of_sort_items',
        'status',
    ];
  
    // Relationship: Equipment belongs to an event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function users()
    {
        return $this->belongsToMany(User::class, 'equipment_user');
    }

   

        public function accountRole()
        {
            return $this->belongsTo(AccountRole::class, 'account_role_id', 'id');
        }
}

