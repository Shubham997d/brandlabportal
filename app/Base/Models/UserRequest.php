<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{

    protected $fillable = [
        'user_id', 'resource', 'resource_id', 'token','request_type', 'request_approval', 'request_data'
    ];

    protected $casts = [
        'request_data' => 'array'
    ];

        
}
