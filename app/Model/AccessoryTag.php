<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AccessoryTag extends Model
{
    //
    protected $table = 'accessory_tag';
    protected $connection = 'mysql';

    public function user_created_by(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function user_deleted_by(){
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }
}
