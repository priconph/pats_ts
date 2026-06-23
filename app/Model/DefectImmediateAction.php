<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class DefectImmediateAction extends Model
{
    protected $table = 'de_v2_immediate_action';
    protected $connection = 'mysql';

    // ✅ Allow mass assignment for these fields
    protected $fillable = [
        'de_id',
        'at_man_action_details',
        'at_machine_action_details',
        'at_material_action_details',
        'at_method_action_details',
        'at_environment_action_details',
        'at_man_date',
        'at_machine_date',
        'at_material_date',
        'at_method_date',
        'at_environment_date',
        'at_man_user',
        'at_machine_user',
        'at_material_user',
        'at_method_user',
        'at_environment_user',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];


}
