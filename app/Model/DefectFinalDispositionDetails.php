<?php

namespace App\Model;

use App\Model\AssemblyLine;
use App\Model\ModeOfDefect;
use App\Model\ProductionRuncard;
use App\User;
use Illuminate\Database\Eloquent\Model;

class DefectFinalDispositionDetails extends Model
{
    protected $table = 'de_v2_final_disposition';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'de_id',
        'f_disposition',
        'engr_manager_id',
        'engr_manager_date',
        'process_engr_id',
        'process_engr_date',
        'f_disposition_engr_remarks',
        'f_disposition_qc',
        'qc_manager_id',
        'qc_manager_date',
        'qc_supervisor_id',
        'qc_supervisor_date',
        'f_disposition_qc_remarks',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public function final_disposition_e_manager(){
        return $this->hasOne(User::class, 'id', 'engr_manager_id');
    }

    public function final_disposition_e_process(){
        return $this->hasOne(User::class, 'id', 'process_engr_id');
    }

    public function final_disposition_qc_manager(){
        return $this->hasOne(User::class, 'id', 'qc_manager_id');
    }

    public function final_disposition_qc_supervisor(){
        return $this->hasOne(User::class, 'id', 'qc_supervisor_id');
    }


}
