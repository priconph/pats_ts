<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\OqcLotappNew;

class OQCVIRNew extends Model
{
    protected $table = 'oqcvir_new';
    protected $connection = 'mysql';

    public function inspector_details()
    {
        return $this->hasOne(User::class, 'id', 'empid');
    }

    public function inspector2_details()
    {
        return $this->hasOne(User::class, 'id', 'insp_name');
    }

    public function lotapp_details()
    {
    	return $this->hasOne(OqcLotappNew::class, 'oqc_lotapp_id','oqc_lotapp_id');
    }
}
