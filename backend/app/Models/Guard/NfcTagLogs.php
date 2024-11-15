<?php

namespace App\Models\Guard;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NfcTagLogs extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function device()
    {
        return $this->belongsTo(Device::class, "serial_number", "serial_number");
    }
    public function guarduser()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
