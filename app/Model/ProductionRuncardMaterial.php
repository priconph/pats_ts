<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Material;

class ProductionRuncardMaterial extends Model
{
    //
    protected $table = 'production_runcard_materials';

    public function material_info(){
        return $this->hasOne(Material::class, 'id', 'pats_material_id');
    }
}
