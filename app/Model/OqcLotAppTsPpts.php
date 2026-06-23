<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\PPTSFinalPackingInspectionQC;

class OqcLotAppTsPpts extends Model
{
    protected $table = 'oqclotapp';
    protected $connection = 'mysql_pats_ts_ppts';

 
    public function ppts_final_packing_inspection_qc()
    {
        return $this->hasOne(PPTSFinalPackingInspectionQC::class, 'id', 'lotapp_id')->where('logdel',0);
    }
}
