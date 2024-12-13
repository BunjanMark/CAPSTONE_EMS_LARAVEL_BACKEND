<?php

// app/Models/Package.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Service;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'packageName',
        'eventType',
        'packageType',
        'services',
        'totalPrice',
        'pax',
        'coverPhoto',
    ];

    protected $casts = [
        'services' => 'array',
    ];
    // public function services()
    // {
    //     return $this->belongsToMany(Service::class);
    // }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'package_services', 'package_id', 'service_id');
    }
}
