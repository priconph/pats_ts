<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\APilotRun;
use App\Model\BPilotRun;
use App\User;
use App\Model\MaterialIssuanceSubSystem;

class CPilotRun extends Model
{
    protected $table = 'c_pilot_runs';
    protected $connection = 'mysql';

    public function user_info(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function prod_user_info(){
        return $this->hasOne(User::class, 'id', 'prdn_validated_by');
    }
    public function eng_user_info(){
        return $this->hasOne(User::class, 'id', 'eng_validated_by');
    }
    public function qc_user_info(){
        return $this->hasOne(User::class, 'id', 'qc_validated_by');
    }

}
