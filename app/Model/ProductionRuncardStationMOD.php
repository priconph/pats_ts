<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProductionRuncard;
use App\Model\ProductionRuncardStation;
use App\Model\ModeOfDefect;

class ProductionRuncardStationMOD extends Model
{
    //
	protected $table = 'prod_runcard_station_mods';
    protected $connection = 'mysql';

    public function production_runcard_details(){
    	return $this->hasOne(ProductionRuncard::class, 'id', 'production_runcard_id');
    }

    public function mod_details(){
    	return $this->hasOne(ModeOfDefect::class, 'id', 'mod_id');
    }

    public function production_runcard_station_details(){
    	return $this->hasOne(ProductionRuncardStation::class, 'id', 'production_runcard_station_id');
    }

    public function mod_details_list() {
    // Change to hasMany to get all defects for the station
    return $this->hasMany(ModeOfDefect::class, 'id', 'mod_id');
}
}
