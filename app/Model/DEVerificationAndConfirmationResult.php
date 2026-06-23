<?php

namespace App\Model;

use App\Model\AssemblyLine;
use App\Model\ModeOfDefect;
use App\Model\ProductionRuncard;
use App\User;
use Illuminate\Database\Eloquent\Model;

class DEVerificationAndConfirmationResult extends Model
{
    protected $table = 'de_verification_confirmation_result';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'de_id',
        'action_stated',
        'prodblem_reoccur',
        'actions_documented',
        'confirmation_result',
        'remarks',
        'qc_inspector_id',
        'qc_inspector_date',
        'qc_supervisor_id',
        'qc_supervisor_date',
        'qc_manager_id',
        'qc_manager_date',
        'created_at',
        'updated_at',
    ];

    public function qc_inspector(){
        return $this->hasOne(User::class, 'id', 'qc_inspector_id');
    }

    public function qc_supervisor(){
        return $this->hasOne(User::class, 'id', 'qc_supervisor_id');
    }

    public function qc_manager(){
        return $this->hasOne(User::class, 'id', 'qc_manager_id');
    }


}
