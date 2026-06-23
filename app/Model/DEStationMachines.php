<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Machine;

class DEStationMachines extends Model
{
    //
    protected $table = 'de_station_machines';
    protected $connection = 'mysql';

    public function machine_info(){
        return $this->hasOne(Machine::class, 'id', 'machine_id');
    }
}
