<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\PackingInspectionLotapps;

class PackingInspectionNew extends Model
{
    protected $table = 'packinginspection_new';
	protected $connection  = 'mysql';

	public function packing_lotapps_details()
	{
		 return $this->hasMany(PackingInspectionLotapps::class, 'packing_code', 'packing_code');
	}

}
