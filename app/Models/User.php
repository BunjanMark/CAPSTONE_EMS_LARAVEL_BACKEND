<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\AccountRole;
use App\Models\Equipment;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ExpoToken;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $fillable = [
        'name', 
        'role_id',
        'username',
        'lastname', 
        'username', 
        'email', 
        'password', 
        'phone_number', 
        'date_of_birth', 
        'gender', 
        'role',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsTo(Role::class);
    }
  
  
     /**
      * Get all of the comments for the User
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
     public function accountRoles(): HasMany
     {
         return $this->hasMany(AccountRole::class);
     }
     public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Create a corresponding AccountRole record when a new user is created
            $accountRole = new AccountRole();
            $accountRole->user_id = $user->id;
            $accountRole->role_id = $user->role_id; // Assuming the user has a role_id attribute
            $accountRole->service_provider_name = $user->name;
            $accountRole->description = 'Default description';

            $accountRole->save();
        });
    }
    //  function to be use for service controller when submitting service and validate userROles iD
    private function getUserIdAndRole()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
    
        $accountRole = $user->accountRoles()->first(); // Get the first role, if any
    
        if ($accountRole) {
            return [
                'user_id' => $user->id,
                'role_id' => $accountRole->role_id,
            ];
        }
        
        // If the user does not have any role
        return [
            'user_id' => $user->id,
            'role_id' => null,
        ];
    }
     public function setPasswordAttribute($value)
     {
         $this->attributes['password'] = Hash::make($value);
     }

     public function equipments()
    {
        return $this->belongsToMany(Equipment::class);
    }

    public function expoTokens()
    {
        return $this->hasMany(ExpoToken::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    // public function routeNotificationForExpo($notification)
    // {
    //     return $this->expo_token;
    // }

    // public function routeNotificationForDatabase($notification)
    // {
    //     return [
    //         'user_id' => $this->id,
    //         'notification_type' => get_class($notification),
    //         'data' => $notification->toArray($this),
    //     ];
    // }

    public function routeNotificationForVonage()
    {
        // Ensure phone numbers are in the correct international format
        return '+63' . ltrim($this->phone_number, '0');
    }

    public function routeNotificationForTwilio()
    {
        // Ensure phone numbers are in the correct international format
        return '+63' . ltrim($this->phone_number, '0');
    }
}
