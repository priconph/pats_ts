<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\DPilotRun;
use App\Model\EPilotRun;
use App\Model\MaterialIssuanceSubSystem;

class APilotRun extends Model
{
	protected $table = 'a_pilot_runs';
    protected $connection = 'mysql';

   	public function wbs_kitting(){
        return $this->hasOne(MaterialIssuanceSubSystem::class, 'po_no', 'po_no');
    }

    public function b_details(){
        return $this->hasOne(BPilotRun::class, 'fkid_a');
    }
    public function d_details(){
        return $this->hasOne(DPilotRun::class, 'fkid_a');
    }
    public function e_details(){
        return $this->hasOne(EPilotRun::class, 'fkid_a');
    }

    public function checked_byA1(){
        return $this->hasOne(User::class, 'id', '1_akl_drawing_checkedby');
    }
    public function checked_byA2(){
        return $this->hasOne(User::class, 'id', '1_gag_drawing_checkedby');
    }
    public function checked_byA3(){
        return $this->hasOne(User::class, 'id', '1_packdoc_drawing_checkedby');
    }
    public function checked_byA4(){
        return $this->hasOne(User::class, 'id', '1_packspecs_drawing_checkedby');
    }
    public function checked_byA5(){
        return $this->hasOne(User::class, 'id', '1_otud_drawing_checkedby');
    }
    public function checked_byA6(){
        return $this->hasOne(User::class, 'id', '1_wi_drawing_checkedby');
    }
    public function checked_byA7(){
        return $this->hasOne(User::class, 'id', '2_prov_parts_req_checkedby');
    }
	public function checked_byA8(){
        return $this->hasOne(User::class, 'id', '2_assbly_parts_checkedby');
    }
    public function checked_byA9(){
        return $this->hasOne(User::class, 'id', '2_withacc_parts_req_checkedby');
    }
    public function checked_byA10(){
        return $this->hasOne(User::class, 'id', '3_assblt_type_checkedby');
    }
    public function checked_byA11(){
        return $this->hasOne(User::class, 'id', '3_parts_printing_checkedby');
    }
    public function checked_byA12(){
        return $this->hasOne(User::class, 'id', '3_gluing_req_checkedby');
    }
    public function checked_byA13(){
        return $this->hasOne(User::class, 'id', '3_loctite_req_checkedby');
    }
    public function checked_byA14(){
        return $this->hasOne(User::class, 'id', '3_hvt_req_checkedby');
    }
    public function checked_byA15(){
        return $this->hasOne(User::class, 'id', '3_etest_req_checkedby');
    }
    public function checked_byA16(){
        return $this->hasOne(User::class, 'id', '3_gauge_req_checkedby');
    }
    public function checked_byA17(){
        return $this->hasOne(User::class, 'id', 'need_planarity_checkedby');
    }
    public function checked_by_prodn_2_4(){
        return $this->hasOne(User::class, 'id', '2_parts_app_design_prodn_checkedby');
    }
    public function checked_by_engg_2_4(){
        return $this->hasOne(User::class, 'id', '2_parts_app_design_engg_checkedby');
    }
    public function checked_by_qc_2_4(){
        return $this->hasOne(User::class, 'id', '2_parts_app_design_qc_checkedby');
    }
    /*
    checked_by_prodn_2_4
    checked_by_engg_2_4
    checked_by_qc_2_4
    */
}
