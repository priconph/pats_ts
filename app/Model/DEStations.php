<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Station;
use App\Model\SubStation;
use App\Model\Machine;
use App\User;
use App\Model\MaterialProcess;
use App\Model\ProductionRuncard;
use App\Model\ProductionRuncardStationMOD;
use App\Model\ProductionRuncardStationMaterial;
use App\Model\DEStationMachines;
use App\Model\DEStationOperators;

class DEStations extends Model
{
    //
    protected $table = 'de_stations';
    protected $connection = 'mysql';

    public function sub_station(){
        return $this->hasOne(SubStation::class, 'id', 'sub_station_id');
    }

    public function operator_info(){
        return $this->hasOne(User::class, 'id', 'operator');
    }

    public function machine_info(){
        return $this->hasOne(Machine::class, 'id', 'machine_id');
    }

    public function defect_escalation_station_machine_details(){
        return $this->hasMany(DeStationMachines::class, 'defect_escalation_station_id', 'id');
    }

    public function material_process_info(){
        return $this->hasOne(MaterialProcess::class, 'id', 'mat_proc_id');
    }

    public function defect_escalation_station_mod_details(){
        return $this->hasMany(ProductionRuncardStationMOD::class, 'production_runcard_station_id', 'id');
    }

    public function defect_escalation_station_material_details(){
        return $this->hasMany(ProductionRuncardStationMaterial::class, 'production_runcard_station_id', 'id');
    }

    public function defect_escalation_station_operator_details(){
        return $this->hasMany(DeStationOperators::class, 'defect_escalation_station_id', 'id');
    }

    public function production_runcard_info(){
        return $this->hasOne(ProductionRuncard::class, 'id', 'production_runcard_id');
    }
}
