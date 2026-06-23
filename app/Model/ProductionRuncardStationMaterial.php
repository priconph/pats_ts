<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\WBSSakidashiIssuanceItem;

class ProductionRuncardStationMaterial extends Model
{
    //
    protected $table = 'prod_runcard_station_materials';
    protected $connection = 'mysql';
    
    public function sakidashi_issuance_item_details(){
        return $this->hasMany(WBSSakidashiIssuanceItem::class, 'lot_no', 'lot_no');
    }
}
