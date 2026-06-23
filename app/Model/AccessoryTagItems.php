<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\AccessoryTag;

class AccessoryTagItems extends Model
{
    //
    protected $table = 'accessory_tag_items';
    protected $connection = 'mysql';

    public function user_created_by(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function user_deleted_by(){
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }
    public function user_counted_by(){
        return $this->hasOne(User::class, 'id', 'counted_by');
    }
    public function accessory_tag(){
        return $this->hasOne(AccessoryTag::class, 'id', 'accessory_tag_id');
    }
}
