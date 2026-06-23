<?php

namespace App\Model;

use App\Model\AssemblyLine;
use App\Model\ModeOfDefect;
use App\Model\ProductionRuncard;
use App\User;
use Illuminate\Database\Eloquent\Model;

class DefectReworkDetails extends Model
{
    protected $table = 'de_v2_rework_details';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'de_id',
        'reworker_user_id1',
        'reworker_user_id2',
        'rework_date',
        'unit1_result',
        'unit2_result',
        'unit3_result',
        'unit4_result',
        'unit5_result',
        'rework_activity',
        'rework_act_others',
        'rework_mpc_checking',
        'activity_after_rework',
        'rework_replace_parts',
        'rework_lot_no',
        'updated_by',
        'updated_at',
    ];

    public function reworker_operator1(){
        return $this->hasOne(User::class, 'id', 'reworker_user_id1');
    }

    public function reworker_operator2(){
        return $this->hasOne(User::class, 'id', 'reworker_user_id2');
    }


}
