<?php

namespace App\Model;

use App\Model\AssemblyLine;
use App\Model\ModeOfDefect;
use App\Model\ProductionRuncard;
use App\User;
use Illuminate\Database\Eloquent\Model;

class DefectProblemVerification extends Model
{
    protected $table = 'de_v2_problem_verification';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'de_id',
        'vr_prod_result',
        'vr_prod_user',
        'vr_prod_date',
        'vr_prod_remarks',
        'vr_engr_result',
        'vr_engr_user',
        'vr_engr_date',
        'vr_engr_remarks',
        'vr_qc_result',
        'vr_qc_user',
        'vr_qc_date',
        'vr_qc_remarks',
        'd_prod_result',
        'd_prod_user',
        'd_prod_date',
        'd_prod_remarks',
        'd_engr_result',
        'd_engr_user',
        'd_engr_date',
        'd_engr_remarks',
        'd_qc_result',
        'd_qc_user',
        'd_qc_date',
        'd_qc_remarks',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public function prod_verification_user(){
        return $this->hasOne(User::class, 'id', 'vr_prod_user');
    }

    public function engr_verification_user(){
        return $this->hasOne(User::class, 'id', 'vr_engr_user');
    }

    public function qc_verification_user(){
        return $this->hasOne(User::class, 'id', 'vr_qc_user');
    }

    public function d_prod_user(){
        return $this->hasOne(User::class, 'id', 'd_prod_user');
    }

    public function d_engr_user(){
        return $this->hasOne(User::class, 'id', 'd_engr_user');
    }

    public function d_qc_user(){
        return $this->hasOne(User::class, 'id', 'd_qc_user');
    }

    // public function defect_description(){
    //     return $this->hasOne(ModeOfDefect::class, 'id', 'mod_id');
    // }

    // public function assembly_line(){
    //     return $this->hasOne(AssemblyLine::class, 'id', 'line_id');
    // }

    // public function production_runcard(){
    //     return $this->hasOne(ProductionRuncard::class, 'id', 'prod_runcard_id');
    // }
}
