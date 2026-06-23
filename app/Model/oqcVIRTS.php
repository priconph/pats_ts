<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\oqcLotApp;

class oqcVIRTS extends Model
{
     protected $table = 'oqcvir_ts';
    protected $connection = 'mysql';

	public function oqclotapp_details(){
	    return $this->hasOne(oqcLotApp::class, 'lot_batch_no', 'lot_batch_no');
	}

	public function user_details(){
	    return $this->hasOne(User::class, 'id', 'insp_name');
	}

	public function user_details_ts(){
        return $this->hasOne(User::class, 'employee_id', 'insp_name'); //for ts
    }
}
