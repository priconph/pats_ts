<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\C3Label;
use App\Model\C3LabelHistoryDetails;

class C3LabelHistory extends Model
{
    //
    protected $table = 'c3_label_history';
    protected $connection = 'mysql';

    public function c3_label(){
        return $this->hasOne(C3Label::class, 'id', 'c3_label_id');
    }
    public function c3_label_history_details(){
        return $this->hasMany(C3LabelHistoryDetails::class, 'c3_label_history_id', 'id');
    }
}
