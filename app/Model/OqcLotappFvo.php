<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class OqcLotappFvo extends Model
{
    protected $table = 'oqc_lotapp_fvo';
    protected $connection = 'mysql';

    public function applied_by_details()
    {
        return $this->hasOne(User::class, 'id', 'applied_by');
    }

    public function fvo_details()
    {
        return $this->hasOne(User::class, 'id', 'fvo_user_id');
    }
}
