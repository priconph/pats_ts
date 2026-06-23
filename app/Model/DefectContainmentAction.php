<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class DefectContainmentAction extends Model
{
    protected $table = 'de_v2_containment_action';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'de_id',
        'wip_total_qty',
        'wip_ok_qty',
        'wip_ng_qty',
        'wip_mod',
        'wip_remarks',
        'pf_total_qty',
        'pf_ok_qty',
        'pf_ng_qty',
        'pf_mod',
        'pf_remarks',
        'qc_total_qty',
        'qc_ok_qty',
        'qc_ng_qty',
        'qc_mod',
        'qc_remarks',
        'p_total_qty',
        'p_ok_qty',
        'p_ng_qty',
        'p_mod',
        'p_remarks',
        's_total_qty',
        's_ok_qty',
        's_ng_qty',
        's_mod',
        's_remarks',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];


}
