<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProductionRuncardStationTsPpts;
use App\Model\OqcLotAppTsPpts;

class ProductionRuncardTsPpts extends Model
{
    protected $table = 'production_runcards';
    protected $connection = 'mysql_pats_ts_ppts';

    public function prod_runcard_station_details(){
        return $this->hasMany(ProductionRuncardStationTsPpts::class, 'production_runcard_id', 'id');
    }

    public function oqc_lot_app_details(){
        return $this->hasMany(OqcLotAppTsPpts::class, 'fkid_runcard', 'id');
    }

}
