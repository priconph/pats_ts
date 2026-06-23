<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class EPilotRun extends Model
{
    protected $table = 'e_pilot_runs';
    protected $connection = 'mysql';

    public function user_info(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function eng_user_info(){
        return $this->hasOne(User::class, 'id', 'final_by_engr');
    }
    public function qc_user_info(){
        return $this->hasOne(User::class, 'id', 'final_by_qc');
    }
}
