<?php

namespace App\Model;

use App\User;
use App\Model\oqcVIR;
use App\Model\oqcLotApp;
use App\Model\MaterialProcess;
use App\Model\WBSSakidashiIssuance;
use App\Model\ProdRuncardMaterialList;
use App\Model\ProductionRuncardStation;
use Illuminate\Database\Eloquent\Model;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\ProductionRuncardStationMOD;
use App\Model\ProductionRuncardStationMaterial;

class ProductionRuncard extends Model
{
    protected $table = 'production_runcards';
    protected $connection = 'mysql';

    public function supervisor_prod_info(){
        return $this->hasOne(User::class, 'id', 'supervisor_prod');
    }

    public function supervisor_qc_info(){
        return $this->hasOne(User::class, 'id', 'supervisor_qc');
    }

    public function qc_info(){
        return $this->hasOne(User::class, 'id', 'qc_id');
    }
    public function opt_info(){
        return $this->hasOne(User::class, 'id', 'operator_id');
    }
    public function mpc_opt_info(){
        return $this->hasOne(User::class, 'id', 'mpc_operator_id');
    }
    public function oqc_details(){
        return $this->hasOne(oqcLotApp::class, 'lot_batch_no', 'runcard_no'); //02072020
        // return $this->hasOne(oqcLotApp::class, 'lot_batch_no', 'runcard_no');
    }
    public function wbs_kitting(){
        return $this->hasOne(MaterialIssuanceSubSystem::class, 'po_no', 'po_no');
    }

    public function prod_runcard_material_list(){
        return $this->hasMany(ProdRuncardMaterialList::class, 'prod_runcard_id', 'id');
    }

    public function prod_runcard_station_details(){
        return $this->hasOne(ProductionRuncardStation::class, 'production_runcard_id', 'id');
    }

    public function prod_runcard_station_many_details(){
        return $this->hasMany(ProductionRuncardStation::class, 'production_runcard_id', 'id');
    }

    public function material_process_info(){
        return $this->hasOne(MaterialProcess::class, 'id', 'mat_proc_id');
    }

    public function wbs_kitting_has_many(){
        return $this->hasMany(MaterialIssuanceSubSystem::class, 'po_no', 'po_no');
    }

    public function eng_qualification_info(){
        return $this->hasOne(User::class, 'id', 'eng_qualification_id');
    }

    public function qc_stamp_qualification_info(){
        return $this->hasOne(User::class, 'id', 'qc_stamp_id');
    }

    public function wbs_sakidashi_issuance_details(){
        return $this->hasMany(WBSSakidashiIssuance::class, 'po_no', 'po_no');
    }
    public function prod_runcard_to_mode_of_defects(){
        return $this->hasOne(ProductionRuncardStationMOD::class, 'production_runcard_id', 'id');
    }
    public function defect_escalation_v2_rev(){
        return $this->hasMany(DefectEscalationV2Rev::class, 'prod_runcard_id', 'id');
    }

    public function prod_runcard_station_materials(){
        return $this->hasMany(ProductionRuncardStationMaterial::class, 'production_runcard_id', 'id');
    }

     // mdr
    public function assembly_line(){
        return $this->belongsTo(AssemblyLine::class, 'assembly_line_id', 'id');
    }

}
