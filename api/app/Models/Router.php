<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;

    protected $fillable = [
        'ssid',
        'ip_address',
        'mac_address',
        'authorization'
    ];

    public function user() {
        return $this->belongsToMany(User::class, 'router_user', 'router_id', 'user_id')
            ->withPivot('router_id', 'user_id');
    }
}
