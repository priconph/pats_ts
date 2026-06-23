<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\APilotRun;
use App\User;
use App\Model\MaterialIssuanceSubSystem;

class BPilotRun extends Model
{
	protected $table = 'b_pilot_runs';
    protected $connection = 'mysql';

    public function APilotRun_details(){
        return $this->hasOne(APilotRun::class, 'id', 'fkid_a');
    }

    public function term_length_checkedby(){
        return $this->hasOne(User::class, 'id', 'term_length_checkedby');
    }

    public function measurement_result_checkedby(){
        return $this->hasOne(User::class, 'id', 'measurement_result_checkedby');
    }

}
