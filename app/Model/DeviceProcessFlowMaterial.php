<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\SubStation;

class DeviceProcessFlowMaterial extends Model
{
    public function substation(){ 
    	return $this->hasOne(SubStation::class, 'id', 'sub_station_id');
    }
}
