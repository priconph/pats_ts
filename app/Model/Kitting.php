<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\SubKitting;
use App\Model\WBSKitIssuance;

class Kitting extends Model
{
    //
    protected $connection = 'mysql';

    public function subkitting(){
    	return $this->hasMany(SubKitting::class, 'pats_kitting_id', 'id');
    }

    public function kit_issuance_info(){
    	return $this->hasOne(WBSKitIssuance::class, 'id', 'kit_issuance_id');
    }
}
