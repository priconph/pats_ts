<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\ModeOfDefect;
use App\Model\DefectEscalation;
use App\Model\DEMaterials;

class DERework extends Model
{
    //
    protected $table = 'de_reworks';

    public function operator_info(){
        return $this->hasOne(User::class, 'id', 'operator');
    }

    public function operator_info2(){
        return $this->hasOne(User::class, 'id', 'operator2');
    }

     public function def_opt_info(){
        return $this->hasOne(User::class, 'id', 'last_updated_by');
    }

    public function prod_info(){
        return $this->hasOne(User::class, 'id', 'prod_id');
    }

    public function engg_info(){
        return $this->hasOne(User::class, 'id', 'engg_id');
    }

    public function qc_info(){
        return $this->hasOne(User::class, 'id', 'qc_id');
    }

    public function mode_of_defect_info(){
        return $this->hasOne(ModeOfDefect::class, 'id', 'mode_of_defect_id');
    }

    public function defect_escalation(){
        return $this->hasOne(DefectEscalation::class, 'id', 'defect_escalation_id');
    }

    public function defect_materials(){
        return $this->hasMany(DEMaterials::class, 'defect_escalation_id', 'id');
    }

}
