<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductionRuncardStationTsPpts extends Model
{
    protected $table = 'production_runcard_stations';
    protected $connection = 'mysql_pats_ts_ppts';
}
