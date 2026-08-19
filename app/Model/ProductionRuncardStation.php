<?php

namespace App\Model;
use App\Model\Station;
use App\Model\SubStation;
use App\Model\Machine;
use App\User;
use App\Model\MaterialProcess;
use App\Model\ProductionRuncard;
use App\Model\ProductionRuncardStationMOD;
use App\Model\ProductionRuncardStationMaterial;
use App\Model\ProductionRuncardStationMachine;
use App\Model\ProductionRuncardStationOperator;
use App\Model\ProcessFlow;
use App\Model\DefectEscalationV2;


use Illuminate\Database\Eloquent\Model;

class ProductionRuncardStation extends Model
{
    //
    protected $table = 'production_runcard_stations2';
    protected $connection = 'mysql';

    public function process_flow(){
        return $this->hasOne(ProcessFlow::class, 'id', 'process_flow_id');
    }

    public function station(){
        return $this->hasOne(Station::class, 'id', 'station_id');
    }

    public function sub_station(){
        return $this->hasOne(SubStation::class, 'id', 'sub_station_id');
    }

    public function operator_info(){
        return $this->hasOne(User::class, 'id', 'operator');
    }

    public function machine_info(){
        return $this->hasOne(Machine::class, 'id', 'machine_id');
    }

    public function prod_runcard_station_machine_details(){
        return $this->hasMany(ProductionRuncardStationMachine::class, 'production_runcard_station_id', 'id');
    }

    public function material_process_info(){
        return $this->hasOne(MaterialProcess::class, 'id', 'mat_proc_id');
    }

    public function prod_runcard_station_mod_details(){
        return $this->hasMany(ProductionRuncardStationMOD::class, 'production_runcard_station_id', 'id');
    }

    public function prod_runcard_station_material_details(){
        return $this->hasMany(ProductionRuncardStationMaterial::class, 'production_runcard_station_id', 'id');
    }

    public function prod_runcard_station_operator_details(){
        return $this->hasMany(ProductionRuncardStationOperator::class, 'production_runcard_station_id', 'id');
    }

    public function production_runcard_info(){
        return $this->hasOne(ProductionRuncard::class, 'id', 'production_runcard_id');
    }

    // public function prod_runcard_station_defect(){
    //     return $this->hasOne(DefectEscalationV2::class, 'prod_runcard_station_id', 'id');
    // }




}
