<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\APilotRun;
use App\Model\BPilotRun;
use App\Model\CPilotRun;
use App\User;
use App\Model\MaterialIssuanceSubSystem;

class DPilotRun extends Model
{
   protected $table = 'd_pilot_runs';
    protected $connection = 'mysql';

    public function user_info(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function prod_user_info(){
        return $this->hasOne(User::class, 'id', 'prodn_checked_by');
    }
    public function eng_user_info(){
        return $this->hasOne(User::class, 'id', 'eng_checked_by');
    }
    public function qc_user_info(){
        return $this->hasOne(User::class, 'id', 'qc_checked_by');
    }

}
