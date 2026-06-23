<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EEDMSTrouble extends Model
{
    // protected $connection = 'mysql_db_eedms_v2_test_nessa';
    protected $connection = 'mysql_rapid_eedms_v2';
    protected $table = 'trouble';
}