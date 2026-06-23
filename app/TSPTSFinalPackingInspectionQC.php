<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TSPTSFinalPackingInspectionQC extends Model
{
    protected $table = 'tspts_finalpackinginspection_trafficqc_qc';
    protected $connection = 'mysql_pats_ts_ppts';

//    public function qc_info(){
//         return $this->hasOne(User::class, 'id', 'qc_id');
//     }

//     public function traffic_info(){
//         return $this->hasOne(User::class, 'id', 'trffic_id');
//     }
}
