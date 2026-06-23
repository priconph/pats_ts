<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\oqcVIR;
use App\Model\oqcVIRTS;
use App\Model\AssemblyLine;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\ProductionRuncard;
use App\Model\PackingInspector;

use App\User;

class oqcLotApp extends Model
{
    protected $table = 'oqclotapp_ts';
    protected $connection = 'mysql';

	public function oqcvir_details(){
	    return $this->hasOne(oqcVIR::class, 'fkid_oqclotapp', 'id');
	}

	public function user_details(){
	    return $this->hasOne(User::class, 'id', 'FVO_empid');
	}

    public function user_details_ts(){
        return $this->hasOne(User::class, 'employee_id', 'empid'); //for ts
    }

    public function oqcvir_details_ts(){
        return $this->hasOne(oqcVIRTS::class, 'lot_batch_no', 'lot_batch_no');
    }

    public function supervisor_prod_info(){
    	return $this->hasOne(User::class, 'id', 'prodn_supervisor');
    }

    public function supervisor_qc_info(){
    	return $this->hasOne(User::class, 'id', 'oqc_supervisor');
    }

    public function assy_details(){
        return $this->hasOne(AssemblyLine::class, 'id', 'assy_line');
    }

    public function wbs_kitting(){
        return $this->hasOne(MaterialIssuanceSubSystem::class, 'po_no', 'po_num');
    }

    public function production_runcard_info(){
        return $this->hasOne(ProductionRuncard::class, 'runcard_no', 'lot_batch_no');
    }

    public function oqcvir_details_for_emboss(){
        return $this->hasOne(oqcVIR::class, 'fkid_oqclotapp', 'id');
    }

    public function batch_details_packop()
    {
            return $this->hasOne(PackingOperator::class, 'batch', 'lot_batch_no');
    }

    public function packing_inspector()
    {
            return $this->hasOne(PackingInspector::class, 'batch', 'lot_batch_no');
    }
}
