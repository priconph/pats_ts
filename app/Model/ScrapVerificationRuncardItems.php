<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ScrapVerificationRuncard;
use App\User;

class ScrapVerificationRuncardItems extends Model
{
    //
    protected $table = 'scrap_verification_runcard_items';
    protected $connection = 'mysql';

    public function scrap_verification_runcard(){
        return $this->belongsTo(ScrapVerificationRuncard::class, 'scrap_verification_runcard_id', 'id')->where('deleted_at',null);
    }
    public function user_created_by(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function user_updated_by(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
