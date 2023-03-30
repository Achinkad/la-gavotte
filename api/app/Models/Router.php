<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;

    protected $table = 'routers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'router_ssid',
        'ip_address',
        'mac_address'
    ];

    public function user() {
        return $this->belongsToMany(User::class, 'user_id')->withTrashed();
    }
}
