<?php

namespace App\Model;

use App\User;
use App\Model\DefectCapa;
use App\Model\AssemblyLine;
use App\Model\ModeOfDefect;
use App\Model\ProductionRuncard;
use App\Model\DefectReworkDetails;
use App\Model\DefectImmediateAction;
use App\Model\DefectContainmentAction;
use Illuminate\Database\Eloquent\Model;
use App\Model\DefectProblemVerification;
use App\Model\DefectLevelTwoVerification;
use App\Model\DefectFinalDispositionDetails;
use App\Model\DefectVerifyCauseAndEscapeeCause;
use App\Model\DEVerificationAndConfirmationResult;

class DefectEscalationV2Rev extends Model
{
    protected $table = 'defect_escalation_v2_rev';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'po_number',
        'defect_ctrl_number',
        'de_group',
        'prod_runcard_id',
        'prod_runcard_station_id',
        'prod_runcard_station_mod_id',
        'defect_classification',
        'detailed_description',
        'mod_id',
        'de_date',
        'de_time',
        'de_shift',
        'line_id',
        'de_dm_no',
        'de_lot_no',
        'de_pair_no',
        'de_occurence',
        'de_station',
        'de_user',
        'de_position',
        'area1',
        'area2',
        'area3',
        'area4',
        'area5',
        'defect_status',
        'de_verification_status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function operator_info(){
        return $this->hasOne(User::class, 'id', 'de_user');
    }

    // public function defect_description(){
    //     return $this->hasOne(ModeOfDefect::class, 'id', 'mod_id');
    // }

    public function defect_description(){
        return $this->belongsTo(ModeOfDefect::class, 'mod_id', 'id');
    }

    public function assembly_line(){
        return $this->hasOne(AssemblyLine::class, 'id', 'line_id');
    }

    public function production_runcard(){
        return $this->hasOne(ProductionRuncard::class, 'id', 'prod_runcard_id');
    }

    public function problem_verificaiton_details(){
        return $this->hasOne(DefectProblemVerification::class, 'de_id', 'id');
    }

    public function immediate_action_details(){
        return $this->hasOne(DefectImmediateAction::class, 'de_id', 'id');
    }

    public function containment_action_details(){
        return $this->hasOne(DefectContainmentAction::class, 'de_id', 'id');
    }

    public function rework_details(){
        return $this->hasOne(DefectReworkDetails::class, 'de_id', 'id');
    }

    public function de_level_two_verification_details(){
        return $this->hasOne(DefectLevelTwoVerification::class, 'de_id', 'id');
    }

    public function final_disposition_details(){
        return $this->hasOne(DefectFinalDispositionDetails::class, 'de_id', 'id');
    }

    public function root_escapee_cause_details(){
        return $this->hasOne(DefectVerifyCauseAndEscapeeCause::class, 'de_id', 'id');
    }

    public function capa_details(){
        return $this->hasOne(DefectCapa::class, 'de_id', 'id');
    }
    
    public function verification_and_confirmation_details(){
        return $this->hasOne(DEVerificationAndConfirmationResult::class, 'de_id', 'id');
    }
        public function mode_of_defect()
        {
            return $this->belongsTo(ModeOfDefect::class, 'mod_id', 'id');
        }
}
