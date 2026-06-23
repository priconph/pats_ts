<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Material;

class DEMaterials extends Model
{
    //
    protected $table = 'de_materials';

    public function material_info(){
        return $this->hasOne(Material::class, 'id', 'pats_material_id');
    }
}
