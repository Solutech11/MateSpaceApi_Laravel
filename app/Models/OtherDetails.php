<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDetails extends Model
{
    //
    use HasFactory;

    protected $table = 'tbl_user_otherDetails';

    protected $fillable = [
        'user_id',
        'fullname',
        'gender',
        'state',
        'DOB',
        'PhoneNo',
        'Country',
        'Address',
        'EmploymentStatus',
    ];
}
