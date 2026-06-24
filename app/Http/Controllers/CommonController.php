<?php

namespace App\Http\Controllers;

use Auth;
use QrCode;
use DataTables;
use App\Model\APilotRun;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\DefectEscalationV2Rev;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    //
    private static $instance = null;

	public static function getInstance() {
		if(!self::$instance instanceof self)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.',E_USER_ERROR);
	}

	public function __wakeup()
	{
		trigger_error('Deserializing is not allowed.',E_USER_ERROR);
	}

    public function generate_qrcode(Request $request){
        try{
            if(isset($request->qrcode)){
                $qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate($request->qrcode);

                return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode)]);
            }
            else{
                return response()->json(['result' => "0"]);
            }
        }
        catch(\Exception $e){
            return response()->json(['result' => "0"]);
        }
    }
    public function getStatusById($global_pilotrun_id){
        //TODO: Modify the code to avoid if else ladder hell
        //Check 2.4 and 3.8 is not null to proceed to Pilotrun E
        $result = "";
        $column_2_4_prodn = [
            '2_parts_app_design_prodn',
            '2_parts_app_design_prodn_checkedby',
        ];
        $column_2_4_engg = [
            '2_parts_app_design_engg',
            '2_parts_app_design_engg_checkedby',
        ];
        $column_2_4_qc = [
            '2_parts_app_design_qc',
            '2_parts_app_design_qc_checkedby',
        ];
        $column_3_8 = [
            '3_need_planarity',
            'need_planarity_checkedby',
        ];

        $a_pilot_run_query = APilotRun::query()->where('id',$global_pilotrun_id);

        collect($column_2_4_prodn)->each(function ($required_column_2_4) use ($a_pilot_run_query) {
            $a_pilot_run_query->whereNotNull($required_column_2_4);
        });
        $results_column_2_4_prodn = $a_pilot_run_query->count();

        if($results_column_2_4_prodn === 0){
            $result = '<span class="badge badge-pill bg-danger">(A) - 2.4 For Production Update </span>';
        }else{
            collect($column_2_4_engg)->each(function ($required_column_2_4) use ($a_pilot_run_query) {
                $a_pilot_run_query->whereNotNull($required_column_2_4);
            });
            $results_column_2_4_engg = $a_pilot_run_query->count();
            if($results_column_2_4_engg === 0){
                $result = '<span class="badge badge-pill bg-danger">(A) - 2.4 For Engg Update </span>';
            }else{
                collect($column_2_4_qc)->each(function ($required_column_2_4) use ($a_pilot_run_query) {
                    $a_pilot_run_query->whereNotNull($required_column_2_4);
                });
                $results_column_2_4_qc = $a_pilot_run_query->count();
                if($results_column_2_4_qc === 0){
                    $result = '<span class="badge badge-pill bg-danger">(A) - 2.4 For QC Update </span>';
                }else{
                    collect($column_3_8)->each(function ($required_column_3_8) use ($a_pilot_run_query) {
                        $a_pilot_run_query->whereNotNull($required_column_3_8);
                    });
                    $results_column_3_8 = $a_pilot_run_query->count();
                    if($results_column_3_8 === 0){
                        $result .= '<span class="badge badge-pill bg-danger">(A) - 3.8 For Production Update </span>';
                    }
                }
            }
        }

        return $result;
    }

    public function get_defect_notification(){
        $non_crit_notif = array(
            'result' => false,
            'defects' => '',
            'body' => ''
        );
        $critical_notif = array(
            'result' => false,
            'body' => ''
        );
        $non_critical = DefectEscalationV2Rev::with(['defect_description'])
        ->where('defect_status','<>', 'CLOSED')
        ->where('defect_classification', 2)
        ->get()
        ->groupBy('de_group');

        $critical = DefectEscalationV2Rev::with(['defect_description'])
        ->where('defect_status','<>', 'CLOSED')
        ->where('defect_classification', 1)
        ->get();

        foreach($non_critical AS $defec_group => $val){
            if(count($val) > 1){
                $result = collect($val)
                ->groupBy('line')
                ->map(function ($items, $line) {
                    $defects = $items
                        ->pluck('defect_description.name')
                        ->filter()
                        ->unique()
                        ->implode(', ');

                    return "Line {$line} has defects: {$defects}";
                })
                ->implode('. ');
                // return $result;
                $non_crit_notif['result'] = true;
                $non_crit_notif['defects'] = $result;
                $non_crit_notif['body'] = "A defect has been escalated and requires your attention.";
            }
        }

        if(count($critical) > 0){
            $critical_notif['result'] = true;
            $critical_notif['body'] = "Urgent: A critical defect requires your immediate response.";
        }
        return response()->json([
            'non_crit'       => $non_critical,
            'critical'       => $critical,
            'critical_notif' => $critical_notif,
            'non_crit_notif' => $non_crit_notif
        ]);
       
        
    }
}
