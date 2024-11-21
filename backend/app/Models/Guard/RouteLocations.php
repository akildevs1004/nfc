<?php

namespace App\Models\Guard;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteLocations extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo(Device::class, "device_id", "id");
    }
}
