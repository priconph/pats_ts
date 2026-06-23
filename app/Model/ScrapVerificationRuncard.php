<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ScrapVerificationRuncardItems;
use App\User;

class ScrapVerificationRuncard extends Model
{
    //
    protected $table = 'scrap_verification_runcard';
    protected $connection = 'mysql';

    public function scrap_verification_runcard_items(){
    	return $this->haMany(ScrapVerificationRuncardItems::class, 'scrap_verification_runcard_id', 'id')->where('deleted_at',null);
    }
    public function user_created_by(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function user_updated_by(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
    public function user_verified_by(){
        return $this->hasOne(User::class, 'id', 'verified_by');
    }
}
