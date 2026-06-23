<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DefectCapa extends Model
{
    protected $table = 'de_capa';
    protected $connection = 'mysql';

    protected $fillable = [
        'de_id',
        'de_capa',
        'de_pic',
        'de_due_date',
        'created_at',
        'updated_by',
        'updated_at',
    ];
}
