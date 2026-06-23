<?php

namespace App\Model;

use App\Model\AssemblyLine;
use App\Model\ModeOfDefect;
use App\Model\ProductionRuncard;
use App\User;
use Illuminate\Database\Eloquent\Model;

class DefectVerifyCauseAndEscapeeCause extends Model
{
    protected $table = 'de_v2_rc_ec';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'de_id',
        'rc_ec',
        'created_at',
        'updated_by',
        'updated_at',
    ];

}
