<?php

namespace App\Model;

use App\Model\AssemblyLine;
use App\Model\ModeOfDefect;
use App\Model\ProductionRuncard;
use App\User;
use Illuminate\Database\Eloquent\Model;

class DefectLevelTwoVerification extends Model
{
    protected $table = 'de_v2_level_two_verification';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'de_id',
        'prod_lvl_two_verification',
        'prod_lvl_two_user_id',
        'prod_lvl_two_date',
        'prod_lvl_two_remarks',
        'engr_lvl_two_verification',
        'engr_lvl_two_user_id',
        'engr_lvl_two_date',
        'engr_lvl_two_remarks',
        'qc_lvl_two_verification',
        'qc_lvl_two_user_id',
        'qc_lvl_two_date',
        'qc_lvl_two_remarks',
        'ppc_lvl_two_verification',
        'ppc_lvl_two_user_id',
        'ppc_lvl_two_date',
        'ppc_lvl_two_remarks',
        'ta_lvl_two_verification',
        'ta_lvl_two_user_id',
        'ta_lvl_two_date',
        'ta_lvl_two_remarks',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public function prod_lvl_two_user(){
        return $this->hasOne(User::class, 'id', 'prod_lvl_two_user_id');
    }

    public function engr_lvl_two_user(){
        return $this->hasOne(User::class, 'id', 'engr_lvl_two_user_id');
    }

    public function qc_lvl_two_user(){
        return $this->hasOne(User::class, 'id', 'qc_lvl_two_user_id');
    }

    public function ppc_lvl_two_user(){
        return $this->hasOne(User::class, 'id', 'ppc_lvl_two_user_id');
    }

    public function ta_lvl_two_user(){
        return $this->hasOne(User::class, 'id', 'ta_lvl_two_user_id');
    }

    // public function engr_verification_user(){
    //     return $this->hasOne(User::class, 'id', 'vr_engr_user');
    // }

    // public function qc_verification_user(){
    //     return $this->hasOne(User::class, 'id', 'vr_qc_user');
    // }

    // public function d_prod_user(){
    //     return $this->hasOne(User::class, 'id', 'd_prod_user');
    // }

    // public function d_engr_user(){
    //     return $this->hasOne(User::class, 'id', 'd_engr_user');
    // }

    // public function d_qc_user(){
    //     return $this->hasOne(User::class, 'id', 'd_qc_user');
    // }
}
