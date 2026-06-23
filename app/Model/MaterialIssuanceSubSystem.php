<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\MaterialIssuanceDetails;
use App\Model\MaterialIssuance;
use App\Model\RapidActiveDocs;
use App\Model\Device;
use App\Model\WBSKitIssuance;

class MaterialIssuanceSubSystem extends Model
{
    //
    protected $table = 'tbl_wbs_material_kitting';
    protected $connection = 'mysql_subsystem';

    public function material_issuance_details(){
    	return $this->hasMany(MaterialIssuanceDetails::class, 'issue_no', 'issuance_no');
    //
    }

    public function device_info(){
    	// return $this->hasOne(Device::class, 'barcode', 'device_code');
    	return $this->hasOne(Device::class, 'po_no', 'po_no');
    }

    public function material_issuance_info(){
    	return $this->hasOne(MaterialIssuance::class, 'tbl_wbs_material_kitting_id', 'id');
    }

    public function documents_details(){
        return $this->hasMany(RapidActiveDocs::class, 'doc_title', 'device_name');
    }

    public function kit_issuance_info(){
        return $this->hasOne(WBSKitIssuance::class, 'issue_no', 'issuance_no');
    }
}
