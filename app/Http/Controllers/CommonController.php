<?php

namespace App\Http\Controllers;

use App\Model\APilotRun;
use App\Model\DefectEscalationV2Rev;
use App\Model\Device;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use QrCode;

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
    /**
     * Lookup Device record(s) used across the app (matrix/device_info/mat_proc_materials).
     *
     * $params supports:
     *   - po_no | po_number : (optional) PO number to filter by. Either key works and
     *                          filtering is only applied `when()` a non-empty value is given.
     *   - name               : (optional) Device name to filter by (takes priority over barcode).
     *   - barcode            : (optional) Device barcode to filter by (used when name is empty).
     *   - get_or_first       : 'first' returns a single model, anything else returns a collection.
     *
     * $with allows the caller to pass dynamic relationship closures/arrays (e.g.
     * 'material_process', 'material_process.manpowers_details', 'material_process.material_details')
     * so complex eager loads can reuse this helper instead of duplicating Device::with(...) queries.
     *
     * The returned model(s) always carry a `po_no` attribute (falling back to the
     * filter value when the column itself is empty) so Blade/JS payloads can safely
     * read it even when it was not part of the request payload.
     */
    public function getMatrixByPoNo($params, $with = []){
        try {
            $poNo = $params['po_no'] ?? $params['po_number'] ?? null;
            $name = $params['name'] ?? null;
            $barcode = $params['barcode'] ?? null;
            $getOrFirst = $params['get_or_first'] ?? '';

            $query = Device::query()
                ->when(!empty($with), function($query) use ($with){
                    $query->with($with);
                })
                ->when(filled($name), function($query) use ($name){
                    $query->where('name', $name);
                })
                ->when(!filled($name) && filled($barcode), function($query) use ($barcode){
                    $query->where('barcode', $barcode);
                })
                ->when(filled($poNo), function($query) use ($poNo){
                    $query->where('po_no', $poNo);
                })
                ->where('status', 1);

            $result = $getOrFirst === 'first' ? $query->first() : $query->get();

            if($result instanceof \Illuminate\Database\Eloquent\Model){
                $this->ensurePoNoAttribute($result, $poNo);
            } elseif($result instanceof \Illuminate\Support\Collection){
                $result->each(function($item) use ($poNo){
                    $this->ensurePoNoAttribute($item, $poNo);
                });
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Ensure the given model always exposes a `po_no` attribute, falling back
     * to the PO number used for filtering when the column itself is empty.
     */
    private function ensurePoNoAttribute($model, $fallbackPoNo){
        if($model && blank($model->po_no)){
            $model->setAttribute('po_no', $fallbackPoNo);
        }

        return $model;
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
