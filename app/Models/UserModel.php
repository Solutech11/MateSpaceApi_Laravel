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
        'verified'
    ];

    // Optional: Add casts for specific fields
    protected $casts = [
        'other_details' => 'boolean',
        'answer_qa' => 'boolean',
        'visible' => 'boolean',
        'verified'=>'boolean'
    ];

    public function userDetails(){
        return $this->hasOne(OtherDetails::class,"user_id");
    }

    public function userPhoto(){
        return $this->hasOne(UserPhoto::class,"user_id");
    }

    public function userAuth(){
        return $this->hasOne(AuthModel::class,"user_id");
    }

    public function userQuestions(){
        return $this->hasOne(UserQuestions::class,"user_id");
    }

    public function userVerif(){
        return $this->hasOne(Verif::class,"user_id");
    }
}
