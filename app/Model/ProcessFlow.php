<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\SubStation;
use App\Model\DeviceProcessFlowMaterial;

class ProcessFlow extends Model
{
    public function processFlow(){
    	return $this->hasOne(SubStation::class, 'id', 'process');
    }

     public function process_name(){
    	return $this->hasOne(SubStation::class, 'id', 'process');
    }

    public function process_flow_substation_details(){
    	return $this->hasMany(DeviceProcessFlowMaterial::class, 'process_flow_id', 'id')->where('status',1);
    }
}
