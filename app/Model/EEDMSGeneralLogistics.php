<?php

namespace App\Model;
use App\Model\EEDMSTrouble;

use Illuminate\Database\Eloquent\Model;

class EEDMSGeneralLogistics extends Model
{
    protected $table = 'generallogistics';
    protected $connection = 'mysql_rapid_eedms_v2';

}
