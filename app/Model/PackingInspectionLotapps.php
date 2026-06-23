<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\OQCVIRNew;
use App\Model\OqcLotappNew;

class PackingInspectionLotapps extends Model
{
    protected $table = 'packinginspection_new_lotapps';
	protected $connection  = 'mysql';

	public function vir_lotapp_details()
    {
    	return $this->hasOne(OQCVIRNew::class, 'id','oqclotapp_id');
    }

    public function oqc_lotapp_details()
    {
    	return $this->hasMany(OqcLotappNew::class, 'id','oqclotapp_id');
    }
}
