<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PendingUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'lastname', 
        'username', 
        'email', 
        'password', 
        'phone_number', 
        'date_of_birth', 
        'gender', 
        'terms_accepted', 
        'role',
    ];

    // Relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role');
    }
}
