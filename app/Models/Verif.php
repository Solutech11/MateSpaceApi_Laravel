<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verif extends Model
{
    //
    use HasFactory;

    protected $table = 'tbl_user_verif';

    protected $fillable = [
        'user_id',
        'otp',
    ];
}
