<?php

namespace App\Models\Guard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function routeLocations()
    {
        return $this->hasMany(RouteLocations::class,   "route_id");
    }
}
