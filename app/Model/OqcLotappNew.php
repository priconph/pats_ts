<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\OqcLotappFvo;
use App\Model\OqcLotappRuncards;
use App\Model\OQCVIRNew;
use App\Model\AssemblyLine;


class OqcLotappNew extends Model
{
    protected $table = 'oqc_lotapp_new';
    protected $connection = 'mysql';

    public function self_details()
    {
        return $this->hasMany(OqcLotappNew::class, 'oqc_lotapp_id', 'oqc_lotapp_id');
    }

    public function applied_by_details()
    {
        return $this->hasOne(User::class, 'id', 'applied_by');
    }

    public function created_by_details()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function fvo_details()
    {
        return $this->hasMany(OqcLotappFvo::class, 'oqc_lotapp_id','oqc_lotapp_id');
    }

    public function oqclotapp_runcard_details()
    {
        return $this->hasMany(OqcLotappRuncards::class, 'oqc_lotapp_id', 'oqc_lotapp_id');
    }

    public function oqcvir_details()
    {
        return $this->hasMany(OQCVIRNew::class, 'oqc_lotapp_id','id');
    }

    public function assembly_line_details()
    {
        return $this->hasOne(AssemblyLine::class, 'id', 'assembly_line_id');
    }

   
}
