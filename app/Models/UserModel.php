<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    //
    protected $table='tbl_users';
    protected $fillable = [
        'username',
        'email',
        'password',
        'other_details',
        'answer_qa',
        'visible',
    ];

    // Optional: Add casts for specific fields
    protected $casts = [
        'other_details' => 'boolean',
        'answer_qa' => 'boolean',
        'visible' => 'boolean',
    ];
}
