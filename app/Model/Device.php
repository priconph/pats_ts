<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\MaterialProcess;
use App\Model\ProcessFlow;

class Device extends Model
{
    //
    protected $table = 'devices';
    protected $connection = 'mysql';

    public function material_process(){
    	return $this->hasMany(MaterialProcess::class, 'device_id', 'id');
    }

    public function process_flow(){
    	return $this->hasMany(ProcessFlow::class, 'device_id', 'id');
    }
}
