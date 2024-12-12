<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\Package;

class EventPackage extends Model
{
    use HasFactory;

    protected $table = 'event_packages';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'package_id',
        'price',
    ];

    public function package() 
    {
        return $this->belongsTo(Package::class);
    }
}
