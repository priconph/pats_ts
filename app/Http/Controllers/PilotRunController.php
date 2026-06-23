<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Device;
use App\Model\AssemblyLine;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\APilotRun;
use App\Model\BPilotRun;
use App\Model\CPilotRun;
use App\Model\DPilotRun;
use App\Model\EPilotRun;
use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use QrCode;


class PilotRunController extends Controller
{
    public function get_data_by_po_no(Request $request){

        $data = APilotRun::where('po_no', $request->po_no)->orderBy('id')->limit(1)->get();
        if( isset($data->device_name) ){
            $data->device_details = Device::where('name', $data[$i]->device_name)->get();
        }

        return response()->json(['data' => $data]);
    }

    public function get_pilotrun_by_po_orig(Request $request){

        // $data = APilotRun::with('wbs_kitting','d_details')->where('po_no', $request->po_no)->get();
        // $data = APilotRun::with('wbs_kitting','d_details')->has('d_details', '>', 0)->where('po_no', $request->po_no)->get();
        $data = APilotRun::with('wbs_kitting','b_details','d_details','e_details')
                            ->where('po_no', $request->po_no)
                            // ->whereIn('status', $request->status)
                            ->get();
        // return $data;
        return DataTables::of($data)
        ->addColumn('raw_action', function($data){
            $disabled = '';

            $b_details_id = '';
            if($data->b_details != null){
                $b_details_id = $data->b_details->id;
            }else{
                $b_details_id = '';
            }

            $d_details_id = '';
            if($data->d_details != null){
                $d_details_id = $data->d_details->id;
            }else{
                $d_details_id = '';
            }

            $e_details_id = '';
            if($data->e_details != null){
                $e_details_id = $data->e_details->id;
            }else{
                $e_details_id = '';
            }

            if($data->status == 0){
                $disabled = '';
            }else{
                $disabled = 'disabled';
            }

            $result='<button style="width:30px;" pilotrun-id="' . $data->id . '" pilot-run-b-id="' . $b_details_id . '" pilot-run-d-id="' . $d_details_id . '" pilotrun-status="'.$data->status.'" pilot-run-e-id="' . $e_details_id . '"title="Open details" class="btnOpenPilotRunDetails btn btn-info btn-sm py-0"><i class="fa fa-edit fa-sm"></i></button>
                <button '. $disabled .' style="width:30px;" pilotrun-id="' . $data->id . '" title="Print QR code" class="btnPrintPilotRun btn btn-warning btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

            return $result;
        })
        ->addColumn('status_lbl', function($row){
            $result = "";



            if($row->status == '0'){
                $result .= '<span class="badge badge-pill bg-warning">(B) For QC Update</span>';
            }
            else if($row->status == '1'){
                $result .= '<span class="badge badge-pill bg-primary">(C) For Production Update</span>';
            }
            else if($row->status == '2'){
                $result .= '<span class="badge badge-pill bg-info">(D) For Production Update</span>';
            }
            else if($row->status == '3'){
                $result .= '<span class="badge badge-pill bg-warning">(C) For Engineering Update</span>';
            }
            else if($row->status == '4'){
                $result .= '<span class="badge badge-pill bg-primary">(D) For Engineering Update</span>';
            }
            else if($row->status == '5'){
                $result .= '<span class="badge badge-pill bg-info">(E) For Engineering Update</span>';
            }
            else if($row->status == '6'){
                $result .= '<span class="badge badge-pill bg-warning">(C) For QC Update</span>';
            }
            else if($row->status == '7'){
                $result .= '<span class="badge badge-pill bg-info">(D) For QC Update</span>';
            }
            else if($row->status == '8'){
                $result .= '<span class="badge badge-pill bg-warning">(E) For QC Update</span>';
            }
            else if($row->status == '9'){
                $result .= '<span class="badge badge-pill bg-success">Completed</span>';
            }else{
                $result .= '<span class="badge badge-pill">Unknown Status</span>';
            }
            return $result;
        })
        ->addColumn('control_type', function($row){
            $result = "";

            if($row->control_type == 1){
                $result .= '<span class="badge badge-pill bg-primary">Special Control</span>';
            }
            else if($row->control_type == 2){
                $result .= '<span class="badge badge-pill bg-info">Regular Control</span>';
            }

            return $result;
        })

        ->rawColumns(['raw_action', 'control_type', 'status_lbl'])
        ->make(true);
    }

    public function get_pilotrun_by_po(Request $request){ //nmodify

        // $data = APilotRun::with('wbs_kitting','d_details')->where('po_no', $request->po_no)->get();
        // $data = APilotRun::with('wbs_kitting','d_details')->has('d_details', '>', 0)->where('po_no', $request->po_no)->get();
        $data = APilotRun::with('wbs_kitting','b_details','d_details','e_details')
                            ->where('po_no', $request->po_no)
                            // ->whereIn('status', $request->status)
                            ->get();
        // return $data;
        return DataTables::of($data)
        ->addColumn('raw_action', function($data){
            $disabled = '';

            $b_details_id = '';
            if($data->b_details != null){
                $b_details_id = $data->b_details->id;
            }else{
                $b_details_id = '';
            }

            $d_details_id = '';
            if($data->d_details != null){
                $d_details_id = $data->d_details->id;
            }else{
                $d_details_id = '';
            }

            $e_details_id = '';
            if($data->e_details != null){
                $e_details_id = $data->e_details->id;
            }else{
                $e_details_id = '';
            }

            if($data->status == 0){
                $disabled = '';
            }else{
                $disabled = 'disabled';
            }

            $result='<button style="width:30px;" pilotrun-id="' . $data->id . '" pilot-run-b-id="' . $b_details_id . '" pilot-run-d-id="' . $d_details_id . '" pilotrun-status="'.$data->status.'" pilot-run-e-id="' . $e_details_id . '"title="Open details" class="btnOpenPilotRunDetails btn btn-info btn-sm py-0"><i class="fa fa-edit fa-sm"></i></button>
                <button '. $disabled .' style="width:30px;" pilotrun-id="' . $data->id . '" title="Print QR code" class="btnPrintPilotRun btn btn-warning btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

            return $result;
        })
        ->addColumn('status_lbl', function($row){
            $result = "";



            if($row->status == '0'){
                $result .= '<span class="badge badge-pill bg-warning">(B) For QC Update</span>';
            }
            else if($row->status == '1'){
                $result .= '<span class="badge badge-pill bg-primary">(C) For Production Update</span>';
            }
            else if($row->status == '2'){
                $result .= '<span class="badge badge-pill bg-info">(D) For Production Update</span>';
            }
            else if($row->status == '3'){
                $result .= '<span class="badge badge-pill bg-warning">(C) For Engineering Update</span>';
            }
            else if($row->status == '4'){
                $result .= '<span class="badge badge-pill bg-primary">(D) For Engineering Update</span>';
            }
            else if($row->status == '5'){
                $result .= '<span class="badge badge-pill bg-info">(E) For Engineering Update</span>';
            }
            else if($row->status == '6'){
                $result .= '<span class="badge badge-pill bg-warning">(C) For QC Update</span>';
            }
            else if($row->status == '7'){
                $result .= '<span class="badge badge-pill bg-info">(D) For QC Update</span>';
            }
            else if($row->status == '8'){
                $result .= '<span class="badge badge-pill bg-warning">(E) For QC Update</span>';
            }
            else if($row->status == '9'){
                $result .= '<span class="badge badge-pill bg-success">Completed</span>';
            }else{
                $result .= '<span class="badge badge-pill">Unknown Status</span>';
            }
            $result .= CommonController::getInstance()->getStatusById($row->id);
            return $result;
        })
        ->addColumn('control_type', function($row){
            $result = "";

            if($row->control_type == 1){
                $result .= '<span class="badge badge-pill bg-primary">Special Control</span>';
            }
            else if($row->control_type == 2){
                $result .= '<span class="badge badge-pill bg-info">Regular Control</span>';
            }
            return $result;
        })

        ->rawColumns(['raw_action', 'control_type', 'status_lbl'])
        ->make(true);
    }

    public function get_pilotrun_toprint_by_id(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = APilotRun::where('id', $request->pilotrun_id)->get();

        $device_name_print = 'not found';
        if(isset($data[0]->series_name)){
            $device_name_print = $data[0]->series_name;
            if( strpos( $device_name_print, "Burn-in" ) !== false) {
                $temp = explode('-', $device_name_print);
                unset($temp[count($temp) - 1]);
                unset($temp[count($temp) - 1]);
                $device_name_print = implode('-', $temp);
                $device_name_print = trim($device_name_print);
            }
            if( strpos( $device_name_print, "Test" ) !== false) {
                $temp = explode('-', $device_name_print);
                unset($temp[count($temp) - 1]);
                $device_name_print = implode('-', $temp);
                $device_name_print = trim($device_name_print);
            }
            if( strpos( $device_name_print, "Burn-in Others" ) !== false) {
                $temp = explode('-', $device_name_print);
                unset($temp[count($temp) - 1]);
                $device_name_print = implode('-', $temp);
                $device_name_print = trim($device_name_print);
            }
            if( strpos( $device_name_print, "Burn-in Memory" ) !== false) {
                $temp = explode('-', $device_name_print);
                unset($temp[count($temp) - 1]);
                $device_name_print = implode('-', $temp);
                $device_name_print = trim($device_name_print);
            }
        }


        $po_qrcode = "";

        if($data->count() > 0){
                $po_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($data[0]->po_no);

            $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);
        }

        return response()->json(['data' => $data, 'po_qrcode' => $po_qrcode, 'device_name_print' => $device_name_print]);
    }

    public function get_pilotrun_by_id(Request $request){
        date_default_timezone_set('Asia/Manila');
         $pilotrun = APilotRun::with(['checked_byA1',
                                    'checked_byA2',
                                    'checked_byA3',
                                    'checked_byA4',
                                    'checked_byA5',
                                    'checked_byA6',
                                    'checked_byA7',
                                    'checked_byA8',
                                    'checked_byA9',
                                    'checked_byA10',
                                    'checked_byA11',
                                    'checked_byA12',
                                    'checked_byA13',
                                    'checked_byA14',
                                    'checked_byA15',
                                    'checked_byA16',
                                    'checked_byA17',
                                    'checked_by_prodn_2_4',
                                    'checked_by_engg_2_4',
                                    'checked_by_qc_2_4',


                                    ])->where('id', $request->pilotrun_id)->first();

        $checked_by_a17 = '';

        if ( isset($pilotrun) ){
            if($pilotrun->need_planarity_checkedby != null){
                $checked_by_a17 = $pilotrun->checked_byA17->name;
            }else{
                $checked_by_a17 = '';
            }
        }

        $multiple_sot = [];
        $ctr =0;
        $special_other_type = $pilotrun->special_other_type;
        $multiple_special_other_type = explode(',', $special_other_type);

        for($i = 0; $i < count($multiple_special_other_type); $i++){
            $multiple_sot[$ctr] = $multiple_special_other_type[$i];
            $ctr++;
        }

        return response()->json(['pilotrun' => $pilotrun, 'checked_by_a17' => $checked_by_a17, 'multiple_sot' => $multiple_sot, 'multiple_special_other_type' => $multiple_special_other_type]);
    }

    public function get_pilotrun_b_details(Request $request){
        date_default_timezone_set('Asia/Manila');
        $pilotrun_b = BPilotRun::with(['term_length_checkedby',
                                        'measurement_result_checkedby'])
                                ->where('fkid_a', $request->pilot_run_b_id)->first();
        // return $pilotrun_b;
        return response()->json(['pilotrun_b' => $pilotrun_b]);
    }

    public function view_pilotrun_c_details(Request $request){
        date_default_timezone_set('Asia/Manila');
        $pilotrun_c = CPilotRun::with([
            'user_info',
            'prod_user_info',
            'eng_user_info',
            'qc_user_info'
            ])->where('fkid_a', $request->id)->get();
        // return $pilotrun_c;
        return DataTables::of($pilotrun_c)
        ->addColumn('raw_action', function($data){

            $result='<button style="width:30px;" pilot-run-c-id="' . $data->id . '" title="Edit" class="btnEditAssemblyAnalysis btn btn-info btn-sm py-0"><i class="fa fa-retweet fa-sm"></i></button>';
            return $result;
        })
        ->addColumn('prdn_potential_failure', function($data){
            $result = "";
            if($data->prdn_potential_failure == 1){
                $result .="Wrong Orientation/location of parts";
            }else if($data->prdn_potential_failure == 2){
                $result .="Wrong pin pattern/population";
            }
            else if($data->prdn_potential_failure == 3){
                $result .="Wrong pin orientation";
            }
            else if($data->prdn_potential_failure == 4){
                $result .="Missing pin (with erratic pattern & pin at the center)";
            }
            else if($data->prdn_potential_failure == 5){
                $result .="Missing parts";
            }
            else if($data->prdn_potential_failure == 6){
                $result .="Easily broken";
            }
            else if($data->prdn_potential_failure == 7){
                $result .="Stuck-up parts";
            }
            else if($data->prdn_potential_failure == 8){
                $result .="Loose parts";
            }
            else if($data->prdn_potential_failure == 9){
                $result .="Hard to install parts";
            }
            else if($data->prdn_potential_failure == 10){
                $result .="Hard to install parts";
            }
            else if($data->prdn_potential_failure == 11){
                $result .="Dislocated parts";
            }
            else if($data->prdn_potential_failure == 12){
                $result .="Other";
            }
            return $result;
        })

        ->rawColumns(['raw_action', 'prdn_potential_failure'])
        ->make(true);

        // return response()->json(['pilotrun_c' => $pilotrun_c]);
    }

    public function get_pilotrun_c_details(Request $request){
        date_default_timezone_set('Asia/Manila');
        $pilot_run_c_details = CPilotRun::with([
            'user_info',
            'prod_user_info',
            'eng_user_info',
            'qc_user_info'
            ])->where('id', $request->pilot_run_c_id)->first();

        return response()->json(['pilot_run_c_details' => $pilot_run_c_details]);
    }

    public function get_pilotrun_d_details(Request $request){
        date_default_timezone_set('Asia/Manila');

        $pilotrun_d = DPilotRun::with(['user_info',
                                    'prod_user_info',
                                    'eng_user_info',
                                    'qc_user_info'
                                    ])->where('fkid_a', $request->pilot_run_d_id)->first();
        $prod_user_info = '';
        $eng_user_info = '';
        $qc_user_info = '';

        if ( isset($pilotrun_d) ){
            if($pilotrun_d->prodn_checked_by != null){
                $prod_user_info = $pilotrun_d->prod_user_info->name;
            }else{
                $prod_user_info = '';
            }

            if($pilotrun_d->eng_checked_by != null){
                $eng_user_info = $pilotrun_d->eng_user_info->name;
            }else{
                $eng_user_info = '';
            }

            if($pilotrun_d->qc_checked_by != null){
                $qc_user_info = $pilotrun_d->qc_user_info->name;
            }else{
                $qc_user_info = '';
            }
        }

        // return response()->json(['pilotrun_d' => $pilotrun_d]);
        return response()->json(['pilotrun_d' => $pilotrun_d, 'prod_user_info' => $prod_user_info, 'eng_user_info' => $eng_user_info, 'qc_user_info' => $qc_user_info]);
    }

    public function get_pilotrun_e_details(Request $request){
        date_default_timezone_set('Asia/Manila');

        $pilotrun_e = EPilotRun::with(['user_info',
                                    'eng_user_info',
                                    'qc_user_info'
                                    ])->where('fkid_a', $request->pilot_run_e_id)->first();
        $eng_user_info = '';
        $qc_user_info = '';

        if ( isset($pilotrun_e) ){

            if($pilotrun_e->final_by_engr != null){
                $eng_user_info = $pilotrun_e->eng_user_info->name;
            }else{
                $eng_user_info = '';
            }

            if($pilotrun_e->final_by_qc != null){
                $qc_user_info = $pilotrun_e->qc_user_info->name;
            }else{
                $qc_user_info = '';
            }
        }

        return response()->json(['pilotrun_e' => $pilotrun_e, 'eng_user_info' => $eng_user_info, 'qc_user_info' => $qc_user_info]);
    }



    /* ADD */
    public function add_pilotrun_Adetails_orig(Request $request){
        date_default_timezone_set('Asia/Manila');
        // return 'true';
        // For update
        if(isset($request->global_pilotrun_id)){
            $data = $request->all();
            $validator = Validator::make($data, [
                // 'need_planarity' => ['required'],
                // 'hidden_need_planarity_pn' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();
                try{
                    APilotRun::where('id',$request->global_pilotrun_id)->update([
                        '3_need_planarity' => $request->need_planarity,
                        'need_planarity_checkedby' => $request->hidden_need_planarity_pn,
                        '3_need_planarity_remarks' => $request->txt_need_planarity_remarks,

                        'created_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    DB::commit();
                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }

        }else{
            $data = $request->all();
            // return $data;
            $validator = Validator::make($data, [
                'po_no' => ['required'],
                'po_qty' => ['required'],
                'series_name' => ['required'],
                'pair_no' => ['required'],
                'control_type' => ['required'],
                // 'special_control_type' => ['required'],
                'txt_AKLdrawing' => ['required'],
                'txt_AKLdrawing_rev' => ['required'],
                'txt_AKLdrawing_page' => ['required'],
                'hidden_AKLdrawing_pn' => ['required'],
                'txt_GAGdrawing' => ['required'],
                'txt_GAGdrawing_rev' => ['required'],
                'txt_GAGdrawing_page' => ['required'],
                // 'hidden_GAGdrawing_pn' => ['required'],
                'txt_PackDocdrawing' => ['required'],
                'txt_PackDocdrawing_rev' => ['required'],
                'txt_PackDocdrawing_page' => ['required'],
                // 'hidden_PackDocdrawing_pn' => ['required'],
                'txt_PackSpecsdrawing' => ['required'],
                'txt_PackSpecsdrawing_rev' => ['required'],
                'txt_PackSpecsdrawing_page' => ['required'],
                // 'hidden_PackSpecsdrawing_pn' => ['required'],
                'txt_OTUDdrawing' => ['required'],
                'txt_OTUDdrawing_rev' => ['required'],
                'txt_OTUDdrawing_page' => ['required'],
                // 'hidden_OTUDdrawing_pn' => ['required'],
                'txt_WIDdrawing' => ['required'],
                'txt_WIDdrawing_rev' => ['required'],
                'txt_WIDdrawing_page' => ['required'],
                // 'hidden_WIDdrawing_pn' => ['required'],

                'provi_req' => ['required'],
                'hidden_provi_req_pn' => ['required'],
                'assmbly_req' => ['required'],
                // 'hidden_assmbly_req_pn' => ['required'],
                'with_accessory_req' => ['required'],
                'txt_with_accessory_req_no' => ['required'],
                // 'hidden_with_accessory_req_pn' => ['required'],
                /*
                    'parts_app_design_prodn' => ['required'],
                    'txt_parts_app_design_prodn_pn' => ['required'],
                    'parts_app_design_prodn_remarks' => ['required'],
                    'parts_app_design_engg' => ['required'],
                    'txt_parts_app_design_engg_pn' => ['required'],
                    'parts_app_design_engg_remarks' => ['required'],
                    'parts_app_design_qc' => ['required'],
                    'txt_parts_app_design_qc_pn' => ['required'],
                    'parts_app_design_qc_remarks' => ['required'],

                */
                'txt_assembly_type' => ['required'],
                'hidden_assembly_type_pn' => ['required'],
                'txt_parts_printing' => ['required'],
                'txt_printing_req' => ['required'],
                // 'hidden_parts_printing_pn' => ['required'],
                'txt_char_printed' => ['required'],
                'txt_page_ref_AKLdrawing' => ['required'],
                'txt_page_ref_GAGdrawing' => ['required'],
                'gluing_req' => ['required'],
                // 'hidden_gluing_req_pn' => ['required'],
                'loctite_req' => ['required'],
                // 'hidden_loctite_req_pn' => ['required'],
                'hvt_req' => ['required'],
                // 'hidden_hvt_req_pn' => ['required'],
                'etest_req' => ['required'],
                // 'hidden_etest_req_pn' => ['required'],
                'gauge_req' => ['required'],
                // 'hidden_gauge_req_pn' => ['required'],
                'txt_gauge_control_no' => ['required'],
                // 'need_planarity' => ['required'],
                // 'hidden_need_planarity_pn' => ['required'],

                'txt_engr_confirmation' => ['required'],
                'txt_engr_confirmation_due_date' => ['required'],


            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();

                try{
                    APilotRun::insert([
                        'po_no' => $request->po_no,
                        'po_qty' => $request->po_qty,
                        'series_name' => $request->series_name,
                        'status' => 0,
                        'pair_no' => $request->pair_no,
                        'control_type' => $request->control_type,
                        'special_control_type' => $request->special_control_type,
                        'special_other_type' => implode(',', (array) $request->special_other_type),
                        '1_akl_drawing' => $request->txt_AKLdrawing,
                        '1_akl_drawing_rev' => $request->txt_AKLdrawing_rev,
                        '1_akl_drawing_page' => $request->txt_AKLdrawing_page,
                        '1_akl_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_akl_drawing_remarks' => $request->txt_AKLdrawing_remarks,
                        '1_gag_drawing' => $request->txt_GAGdrawing,
                        '1_gag_drawing_rev' => $request->txt_GAGdrawing_rev,
                        '1_gag_drawing_page' => $request->txt_GAGdrawing_page,
                        '1_gag_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_gag_drawing_remarks' => $request->txt_GAGdrawing_remarks,
                        '1_packdoc_drawing' => $request->txt_PackDocdrawing,
                        '1_packdoc_drawing_rev' => $request->txt_PackDocdrawing_rev,
                        '1_packdoc_drawing_page' => $request->txt_PackDocdrawing_page,
                        '1_packdoc_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_packdoc_drawing_remarks' => $request->txt_PackDocdrawing_remarks,
                        '1_packspecs_drawing' => $request->txt_PackSpecsdrawing,
                        '1_packspecs_drawing_rev' => $request->txt_PackSpecsdrawing_rev,
                        '1_packspecs_drawing_page' => $request->txt_PackSpecsdrawing_page,
                        '1_packspecs_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_packspecs_drawing_remarks' => $request->txt_PackSpecsdrawing_remarks,
                        '1_otud_drawing' => $request->txt_OTUDdrawing,
                        '1_otud_drawing_rev' => $request->txt_OTUDdrawing_rev,
                        '1_otud_drawing_page' => $request->txt_OTUDdrawing_page,
                        '1_otud_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_otud_drawing_remarks' => $request->txt_OTUDdrawing_remarks,
                        '1_wi_drawing' => $request->txt_WIDdrawing,
                        '1_wi_drawing_rev' => $request->txt_WIDdrawing_rev,
                        '1_wi_drawing_page' => $request->txt_WIDdrawing_page,
                        '1_wi_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_wi_drawing_remarks' => $request->txt_WIDdrawing_remarks,

                        '1_engr_confirmation' => $request->txt_engr_confirmation,
                        '1_engr_confirmation_due_date' => $request->txt_engr_confirmation_due_date,

                        '2_prov_parts_req' => $request->provi_req,
                        '2_prov_parts_req_checkedby' => $request->hidden_provi_req_pn,
                        '2_prov_parts_req_remarks' => $request->txt_provi_req_remarks,
                        '2_assbly_parts_req' => $request->assmbly_req,
                        '2_assbly_parts_checkedby' => $request->hidden_provi_req_pn,
                        '2_assbly_parts_remarks' => $request->txt_assmbly_req_remarks,
                        '2_withacc_parts_req' => $request->with_accessory_req,
                        '2_withacc_parts_req_no' => $request->txt_with_accessory_req_no,
                        '2_withacc_parts_req_checkedby' => $request->hidden_provi_req_pn,
                        '2_withacc_parts_req_remarks' => $request->txt_with_accessory_req_remarks,
                        /*
                            2_parts_app_design_prodn => $request->parts_app_design_prodn,
                            2_parts_app_design_prodn_checkedby => $request->txt_parts_app_design_prodn_pn,
                            2_parts_app_design_prodn_remarks => $request->parts_app_design_prodn_remarks,
                            2_parts_app_design_engg =>  $request->parts_app_design_engg,
                            2_parts_app_design_engg_checkedby => $request->txt_parts_app_design_engg_pn,
                            2_parts_app_design_engg_remarks => $request->parts_app_design_engg_remarks,
                            2_parts_app_design_qc =>  $request->parts_app_design_qc,
                            2_parts_app_design_qc_checkedby =>  $request->txt_parts_app_design_qc_pn,
                            2_parts_app_design_qc_remarks =>  $request->parts_app_design_qc_remarks,
                        */

                        '3_assblt_type' => $request->txt_assembly_type,
                        '3_assblt_type_checkedby' => $request->hidden_assembly_type_pn,
                        '3_assblt_type_remarks' => $request->txt_assembly_type_remarks,
                        '3_parts_printing' => $request->txt_parts_printing,
                        '3_parts_printing_no' => $request->txt_printing_req,
                        '3_parts_printing_checkedby' => $request->hidden_assembly_type_pn,
                        '3_parts_printing_remarks' => $request->txt_assembly_type_remarks,
                        '3_char_printed' => $request->txt_char_printed,
                        '3_ref_doc_akl_drawing' => $request->txt_page_ref_AKLdrawing,
                        '3_ref_doc_gag_drawing' => $request->txt_page_ref_GAGdrawing,
                        '3_gluing_req' => $request->gluing_req,
                        '3_gluing_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_gluing_req_remarks' => $request->txt_gluing_req_remarks,
                        '3_loctite_req' => $request->loctite_req,
                        '3_loctite_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_loctite_req_remarks' => $request->txt_loctite_req_remarks,
                        '3_hvt_req' => $request->hvt_req,
                        '3_hvt_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_hvt_req_remarks' => $request->txt_hvt_req_remarks,
                        '3_etest_req' => $request->etest_req,
                        '3_etest_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_etest_req_remarks' => $request->txt_etest_req_remarks,
                        '3_gauge_req' => $request->gauge_req,
                        '3_gauge_ctl_no' => $request->txt_gauge_control_no,
                        '3_gauge_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_gauge_req_remarks' => $request->txt_gauge_req_remarks,

                        '3_need_planarity' => $request->need_planarity,
                        'need_planarity_checkedby' => $request->hidden_need_planarity_pn,
                        '3_need_planarity_remarks' => $request->txt_need_planarity_remarks,

                        'created_by' => Auth::user()->id,
                        'logdel' => 0,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }
    }

    public function add_pilotrun_Adetails(Request $request){ //nmodify
        date_default_timezone_set('Asia/Manila');
        // For update
        $global_pilotrun_id = $request->global_pilotrun_id;
        if(isset($global_pilotrun_id)){ //Edit
            DB::beginTransaction();
            try{
                $a_pilot_run = APilotRun::where('id',$global_pilotrun_id)->get(['status']);
                $arr_a_pilot_run = [
                    //2.4
                    '2_parts_app_design_prodn' => $request->parts_app_design_prodn,
                    '2_parts_app_design_prodn_checkedby' => $request->hidden_parts_app_design_prodn_pn,
                    '2_parts_app_design_prodn_remarks' => $request->parts_app_design_prodn_remarks,
                    '2_parts_app_design_engg' =>  $request->parts_app_design_engg,
                    '2_parts_app_design_engg_checkedby' => $request->hidden_parts_app_design_engg_pn,
                    '2_parts_app_design_engg_remarks' => $request->parts_app_design_engg_remarks,
                    '2_parts_app_design_qc' =>  $request->parts_app_design_qc,
                    '2_parts_app_design_qc_checkedby' =>  $request->hidden_parts_app_design_qc_pn,
                    '2_parts_app_design_qc_remarks' =>  $request->parts_app_design_qc_remarks,
                    '3_need_planarity' => $request->need_planarity,
                    'need_planarity_checkedby' => $request->hidden_need_planarity_pn,
                    '3_need_planarity_remarks' => $request->txt_need_planarity_remarks,
                    'created_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                APilotRun::where('id',$global_pilotrun_id)->update($arr_a_pilot_run);
                DB::commit();
                return response()->json(['result' => "1"]);
            }catch(\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => "0"]);
            }
        }else{ //ADD
            $data = $request->all();
            $validator = Validator::make($data, [
                'po_no' => ['required'],
                'po_qty' => ['required'],
                'series_name' => ['required'],
                'pair_no' => ['required'],
                'control_type' => ['required'],
                'txt_AKLdrawing' => ['required'],
                'txt_AKLdrawing_rev' => ['required'],
                'txt_AKLdrawing_page' => ['required'],
                'hidden_AKLdrawing_pn' => ['required'],
                'txt_GAGdrawing' => ['required'],
                'txt_GAGdrawing_rev' => ['required'],
                'txt_GAGdrawing_page' => ['required'],
                'txt_PackDocdrawing' => ['required'],
                'txt_PackDocdrawing_rev' => ['required'],
                'txt_PackDocdrawing_page' => ['required'],
                'txt_PackSpecsdrawing' => ['required'],
                'txt_PackSpecsdrawing_rev' => ['required'],
                'txt_PackSpecsdrawing_page' => ['required'],
                'txt_OTUDdrawing' => ['required'],
                'txt_OTUDdrawing_rev' => ['required'],
                'txt_OTUDdrawing_page' => ['required'],
                'txt_WIDdrawing' => ['required'],
                'txt_WIDdrawing_rev' => ['required'],
                'txt_WIDdrawing_page' => ['required'],

                'provi_req' => ['required'],
                'hidden_provi_req_pn' => ['required'],
                'assmbly_req' => ['required'],
                'with_accessory_req' => ['required'],
                'txt_with_accessory_req_no' => ['required'],

                'txt_assembly_type' => ['required'],
                'hidden_assembly_type_pn' => ['required'],
                'txt_parts_printing' => ['required'],
                'txt_printing_req' => ['required'],
                'txt_char_printed' => ['required'],
                'txt_page_ref_AKLdrawing' => ['required'],
                'txt_page_ref_GAGdrawing' => ['required'],
                'gluing_req' => ['required'],
                'loctite_req' => ['required'],
                'hvt_req' => ['required'],
                'etest_req' => ['required'],
                'gauge_req' => ['required'],
                'txt_gauge_control_no' => ['required'],
                'txt_engr_confirmation' => ['required'],
                'txt_engr_confirmation_due_date' => ['required'],


            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();

                try{
                    APilotRun::insert([
                        'po_no' => $request->po_no,
                        'po_qty' => $request->po_qty,
                        'series_name' => $request->series_name,
                        'status' => 0,
                        'pair_no' => $request->pair_no,
                        'control_type' => $request->control_type,
                        'special_control_type' => $request->special_control_type,
                        'special_other_type' => implode(',', (array) $request->special_other_type),
                        '1_akl_drawing' => $request->txt_AKLdrawing,
                        '1_akl_drawing_rev' => $request->txt_AKLdrawing_rev,
                        '1_akl_drawing_page' => $request->txt_AKLdrawing_page,
                        '1_akl_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_akl_drawing_remarks' => $request->txt_AKLdrawing_remarks,
                        '1_gag_drawing' => $request->txt_GAGdrawing,
                        '1_gag_drawing_rev' => $request->txt_GAGdrawing_rev,
                        '1_gag_drawing_page' => $request->txt_GAGdrawing_page,
                        '1_gag_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_gag_drawing_remarks' => $request->txt_GAGdrawing_remarks,
                        '1_packdoc_drawing' => $request->txt_PackDocdrawing,
                        '1_packdoc_drawing_rev' => $request->txt_PackDocdrawing_rev,
                        '1_packdoc_drawing_page' => $request->txt_PackDocdrawing_page,
                        '1_packdoc_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_packdoc_drawing_remarks' => $request->txt_PackDocdrawing_remarks,
                        '1_packspecs_drawing' => $request->txt_PackSpecsdrawing,
                        '1_packspecs_drawing_rev' => $request->txt_PackSpecsdrawing_rev,
                        '1_packspecs_drawing_page' => $request->txt_PackSpecsdrawing_page,
                        '1_packspecs_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_packspecs_drawing_remarks' => $request->txt_PackSpecsdrawing_remarks,
                        '1_otud_drawing' => $request->txt_OTUDdrawing,
                        '1_otud_drawing_rev' => $request->txt_OTUDdrawing_rev,
                        '1_otud_drawing_page' => $request->txt_OTUDdrawing_page,
                        '1_otud_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_otud_drawing_remarks' => $request->txt_OTUDdrawing_remarks,
                        '1_wi_drawing' => $request->txt_WIDdrawing,
                        '1_wi_drawing_rev' => $request->txt_WIDdrawing_rev,
                        '1_wi_drawing_page' => $request->txt_WIDdrawing_page,
                        '1_wi_drawing_checkedby' => $request->hidden_AKLdrawing_pn,
                        '1_wi_drawing_remarks' => $request->txt_WIDdrawing_remarks,

                        '1_engr_confirmation' => $request->txt_engr_confirmation,
                        '1_engr_confirmation_due_date' => $request->txt_engr_confirmation_due_date,

                        '2_prov_parts_req' => $request->provi_req,
                        '2_prov_parts_req_checkedby' => $request->hidden_provi_req_pn,
                        '2_prov_parts_req_remarks' => $request->txt_provi_req_remarks,
                        '2_assbly_parts_req' => $request->assmbly_req,
                        '2_assbly_parts_checkedby' => $request->hidden_provi_req_pn,
                        '2_assbly_parts_remarks' => $request->txt_assmbly_req_remarks,
                        '2_withacc_parts_req' => $request->with_accessory_req,
                        '2_withacc_parts_req_no' => $request->txt_with_accessory_req_no,
                        '2_withacc_parts_req_checkedby' => $request->hidden_provi_req_pn,
                        '2_withacc_parts_req_remarks' => $request->txt_with_accessory_req_remarks,


                        '3_assblt_type' => $request->txt_assembly_type,
                        '3_assblt_type_checkedby' => $request->hidden_assembly_type_pn,
                        '3_assblt_type_remarks' => $request->txt_assembly_type_remarks,
                        '3_parts_printing' => $request->txt_parts_printing,
                        '3_parts_printing_no' => $request->txt_printing_req,
                        '3_parts_printing_checkedby' => $request->hidden_assembly_type_pn,
                        '3_parts_printing_remarks' => $request->txt_assembly_type_remarks,
                        '3_char_printed' => $request->txt_char_printed,
                        '3_ref_doc_akl_drawing' => $request->txt_page_ref_AKLdrawing,
                        '3_ref_doc_gag_drawing' => $request->txt_page_ref_GAGdrawing,
                        '3_gluing_req' => $request->gluing_req,
                        '3_gluing_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_gluing_req_remarks' => $request->txt_gluing_req_remarks,
                        '3_loctite_req' => $request->loctite_req,
                        '3_loctite_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_loctite_req_remarks' => $request->txt_loctite_req_remarks,
                        '3_hvt_req' => $request->hvt_req,
                        '3_hvt_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_hvt_req_remarks' => $request->txt_hvt_req_remarks,
                        '3_etest_req' => $request->etest_req,
                        '3_etest_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_etest_req_remarks' => $request->txt_etest_req_remarks,
                        '3_gauge_req' => $request->gauge_req,
                        '3_gauge_ctl_no' => $request->txt_gauge_control_no,
                        '3_gauge_req_checkedby' => $request->hidden_assembly_type_pn,
                        '3_gauge_req_remarks' => $request->txt_gauge_req_remarks,

                        '3_need_planarity' => $request->need_planarity,
                        'need_planarity_checkedby' => $request->hidden_need_planarity_pn,
                        '3_need_planarity_remarks' => $request->txt_need_planarity_remarks,

                        'created_by' => Auth::user()->id,
                        'logdel' => 0,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }
    }
    /**
     * Summary of add_pilotrun_Bdetails
     * @param \Illuminate\Http\Request $request
     */

    public function add_pilotrun_Bdetails(Request $request){
        date_default_timezone_set('Asia/Manila');

        // For update
        if(isset($request->pilotrun_b_id)){
            $data = $request->all();
            $rules = [
                'txt_highlights' => ['required'],
            ];
            // APilotRun::where('id',$request->txt_id)
            // ->update([
            //     'status' => 1,
            // ]);
            /**
             * 09-21-2022
             * Additional validation for CHECKPOINTS and FEEDBACK REPORT ISSUANCE
             * -JD
             */
            if($request->term_length != 1 || $request->measure_result != 1 || $request->vir != 1){
                $rules['feedback_report'] = 'required';

                if($request->feedback_report == 1){
                    $rules['txt_ptis'] = 'required';
                }

                if($request->feedback_report == 2){
                    $rules['txt_ng_report'] = 'required';
                }

                if($request->feedback_report == 3){
                    $rules['txt_sar'] = 'required';
                }

                if($request->feedback_report == 4){
                    $rules['txt_others'] = 'required';
                }
            }

            if($request->term_length == 1 && $request->measure_result == 1 && $request->vir == 1){
                $rules['feedback_report'] = '';
                $rules['txt_ptis'] = '';
                $rules['txt_ng_report'] = '';
                $rules['txt_sar'] = '';
                $rules['txt_others'] = '';
            }
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();
                try{
                    /* Triggered the change of status during Edit */
                    // APilotRun::where('id',$request->txt_id)
                    // ->update([
                    //     'status' => 1,
                    // ]);
                    BPilotRun::where('id',$request->pilotrun_b_id)->update([
                        'highlights' => $request->txt_highlights,
                        'feedback_report' => $request->feedback_report,
                        'ptis_ctlno' => $request->txt_ptis,
                        'ng_ctlno' => $request->txt_ng_report,
                        'sar_ctlno' => $request->txt_sar,
                        'other_ctlno' => $request->txt_others,
                        'created_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::commit();
                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }else{

            $data = $request->all();
            // return $data;
            $validator = Validator::make($data, [
                'term_length' => ['required'],
                'hidden_term_length_pn' => ['required'],
                // 'measurement_result' => ['required'],
                // 'hidden_measure_result_pn' => ['required'],
                // 'dimension' => ['required'],
                // 'vir' => ['required'],

            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();

                try{
                    /* Triggered the change of status during Add status*/

                    APilotRun::where('id',$request->txt_id)
                    ->update([
                        'status' => 1,
                    ]);

                    BPilotRun::insert([
                        'fkid_a' => $request->txt_id,
                        'po_no' => $request->txt_po_num,
                        'term_length' => $request->term_length,
                        'term_length_checkedby' => $request->hidden_term_length_pn,
                        'term_length_datetime' => date('Y-m-d H:i:s'),
                        'measurement_result' => $request->measure_result,
                        'measurement_result_checkedby' => $request->hidden_term_length_pn,
                        'measurement_result_datetime' => date('Y-m-d H:i:s'),
                        'dimension' => $request->dimension_type,
                        'dimension_with_specialReq' => $request->txt_special_req,
                        'vir' => $request->vir,
                        'vir_remarks' => $request->txt_vir_remarks,
                        'highlights' => $request->txt_highlights,
                        'feedback_report' => $request->feedback_report,
                        'ptis_ctlno' => $request->txt_ptis,
                        'ng_ctlno' => $request->txt_ng_report,
                        'sar_ctlno' => $request->txt_sar,
                        'other_ctlno' => $request->txt_others,
                        'created_by' => Auth::user()->id,
                        'logdel' => 0,
                        // 'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }
    }

    public function add_pilotrun_Cdetails(Request $request){
        date_default_timezone_set('Asia/Manila');

        // For Update
        if(isset($request->pilot_run_c_id)){
            $data = $request->all();
            $validator = Validator::make($data, [
                // 'txt_pot_fail' => ['required'],
                // 'hidden_term_length_pn' => ['required'],
                // 'measurement_result' => ['required'],
                // 'hidden_measure_result_pn' => ['required'],
                // 'dimension' => ['required'],
                // 'vir' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();
                try{
                    // 2-status = Prod, 4-status = Engr, 7-status = QC
                    $status_value = 1;
                    if(isset($request->operator_id)){
                        // $status_value +=1;
                        $status_value =2;
                    }

                    if(isset($request->engineer_id)){
                        $status_value =4;
                    }

                    if(isset($request->qc_id)){
                        $status_value =7;
                    }

                    APilotRun::where('id',$request->global_pilotrun_id)
                    ->update([
                        'status' => $status_value,
                    ]);
                    $update = [
                        // 'fkid_a' => $request->global_pilotrun_id,
                        // 'po_no' => $request->global_pilotrun_po,
                        'prdn_potential_failure' => $request->txt_pot_fail,
                        'prdn_affected_parts' => $request->txt_aff_parts,
                        'prdn_action_taken' => $request->txt_action_taken,
                        'prdn_orcto' => $request->txt_orcto,
                        'prdn_due_date' => $request->txt_prod_due_date,
                        'prdn_tools_jigs' => $request->txt_tools_jigs,
                        'prdn_docs' => $request->txt_docs,
                        'eng_due_date' => $request->txt_eng_due_date,
                        'prdn_validated_by' => $request->operator_id,
                        'prdn_datetime' => date('Y-m-d H:i:s'),
                        'eng_validated_by' => $request->engineer_id,
                        'eng_datetime' => date('Y-m-d H:i:s'),
                        // 'qc_validated_by' => $request->qc_id,
                        // 'qc_datetime' => date('Y-m-d H:i:s'),
                        'highlights' => $request->txt_critical_highlights,
                        'created_by' => Auth::user()->id,
                        'logdel' => 0,
                        'updated_at' => date('Y-m-d H:i:s')
                        // 'created_at' => date('Y-m-d H:i:s')
                        // 'chk_ctl_no' => $request->pr_ctl_checkbox,
                        // 'pr_ctl_no' => $request->txt_pr_control_no

                    ];

                    if(isset($request->qc_id)){
                        $update['qc_validated_by'] = $request->qc_id;
                        $update['qc_datetime'] = date('Y-m-d H:i:s');
                    }

                    CPilotRun::where('id',$request->pilot_run_c_id)->update($update);
                    DB::commit();
                    return response()->json(['result' => "1", 'status_value' => $status_value]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }
        else{ // For Insert
            $data = $request->all();
            $validator = Validator::make($data, [
                // 'txt_pot_fail' => ['required'],
                // 'hidden_term_length_pn' => ['required'],
                // 'measurement_result' => ['required'],
                // 'hidden_measure_result_pn' => ['required'],
                // 'dimension' => ['required'],
                // 'vir' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();
                try{
                    // 2-status = Prod, 4-status = Engr, 7-status = QC
                    $status_value = 0;
                    if(isset($request->operator_id)){
                        $status_value =2;
                    }

                    if(isset($request->engineer_id)){
                        $status_value =4;
                    }

                    if(isset($request->qc_id)){
                        $status_value =7;
                    }

                    APilotRun::where('id',$request->global_pilotrun_id)
                    ->update([
                        'status' => $status_value,
                    ]);

                    $insert_data = [
                        'fkid_a' => $request->global_pilotrun_id,
                        'po_no' => $request->global_pilotrun_po,
                        'prdn_potential_failure' => $request->txt_pot_fail,
                        // 'prdn_potential_failure_others' => $request->xxx,
                        'prdn_affected_parts' => $request->txt_aff_parts,
                        'prdn_action_taken' => $request->txt_action_taken,
                        'prdn_orcto' => $request->txt_orcto,
                        'prdn_due_date' => $request->txt_prod_due_date,
                        'prdn_tools_jigs' => $request->txt_tools_jigs,
                        'prdn_docs' => $request->txt_docs,
                        'eng_due_date' => $request->txt_eng_due_date,
                        'prdn_validated_by' => $request->operator_id,
                        'prdn_datetime' => date('Y-m-d H:i:s'),
                        'eng_validated_by' => $request->engineer_id,
                        'eng_datetime' => date('Y-m-d H:i:s'),
                        // 'qc_validated_by' => $request->qc_id,
                        // 'qc_datetime' => date('Y-m-d H:i:s'),
                        'highlights' => $request->txt_critical_highlights,
                        'created_by' => Auth::user()->id,
                        'logdel' => 0,
                        // 'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'chk_ctl_no' => $request->pr_ctl_checkbox,
                        'pr_ctl_no' => $request->txt_pr_control_no
                    ];

                    if(isset($request->qc_id)){
                        $insert_data['qc_validated_by'] = $request->qc_id;
                        $insert_data['qc_datetime'] = date('Y-m-d H:i:s');
                    }

                    CPilotRun::insert([
                        $insert_data
                    ]);
                    DB::commit();

                    return response()->json(['result' => "1", 'status_value' => $status_value]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }
    }

    public function add_pilotrun_Ddetails(Request $request){ //2_parts_app_design_prodn
        date_default_timezone_set('Asia/Manila');
        $a_pilot_run_query = APilotRun::query()->where('id',$request->global_pilotrun_id);

        $data_engg = [
            '1e_ini_check' => $request->txt_1e_ini_check,
            '1e_fin_check' => $request->txt_1e_fin_check,
            '1_remarks' => $request->txt_1_remarks,
            '2e_ini_check' => $request->txt_2e_ini_check,
            '2e_fin_check' => $request->txt_2e_fin_check,
            '2_remarks' => $request->txt_2_remarks,
            '3e_ini_check' => $request->txt_3e_ini_check,
            '3e_fin_check' => $request->txt_3e_fin_check,
            '3_remarks' => $request->txt_3_remarks,
            '4e_ini_check' => $request->txt_4e_ini_check,
            '4e_fin_check' => $request->txt_4e_fin_check,
            '4e_actual_char' => $request->txt_4_e_actual_char,
            '4_remarks' => $request->txt_4_remarks,
            '4_actual_char_rem' => $request->txt_4_actual_char_rem,
            '5e_ini_check' => $request->txt_5e_ini_check,
            '5e_fin_check' => $request->txt_5e_fin_check,
            '5_remarks' => $request->txt_5_remarks,
            '6e_ini_check' => $request->txt_6e_ini_check,
            '6e_fin_check' => $request->txt_6e_fin_check,
            '6_remarks' => $request->txt_6_remarks,
            '7e_ini_check' => $request->txt_7e_ini_check,
            '7e_fin_check' => $request->txt_7e_fin_check,
            '7_remarks' => $request->txt_7_remarks,
            '8e_ini_check' => $request->txt_8e_ini_check,
            '8e_fin_check' => $request->txt_8e_fin_check,
            '8_remarks' => $request->txt_8_remarks,
            'eng_checked_by' => $request->txt_echeckedby_id,
            'eng_datetime' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $column_3_8 = [
            '3_need_planarity',
            'need_planarity_checkedby',
        ];
        collect($column_3_8)->each(function ($required_column_3_8) use ($a_pilot_run_query) {
            $a_pilot_run_query->whereNotNull($required_column_3_8);
        });
        $results_column_3_8 = $a_pilot_run_query->count();
        if($results_column_3_8 === 0){
            return response()->json(['result' => '2', 'error' => 'Warning Please Update the Pilot Run A 3.8']);
        }
        // For Update
        if(isset($request->pilot_run_d_id)){
           $a_pilot = APilotRun::where('id',$request->global_pilotrun_id)
            ->where('status',2)
            ->count();

            $d_pilot_run_query = DPilotRun::query()->where('fkid_a',$request->global_pilotrun_id);
            $column_prodn = [
                '1p_ini_check',
                '1p_fin_check',
                '2p_ini_check',
                '2p_fin_check',
                '3p_ini_check',
                '3p_fin_check',
            ];
            collect($column_prodn)->each(function ($required_column_prodn) use ($d_pilot_run_query) {
                $d_pilot_run_query->whereNotNull($required_column_prodn);
            });

            if($d_pilot_run_query->count() === 1 && $a_pilot === 1){
                APilotRun::where('id',$request->global_pilotrun_id)
                ->update([
                    'status' => 3, // C For Engr
                ]);
            }

            if ($request->pilot_run_d_status == 4){ // D - Done Update
                $column_2_4_engg = [
                    '2_parts_app_design_engg',
                    '2_parts_app_design_engg_checkedby',
                ];

                collect($column_2_4_engg)->each(function ($required_column_2_4) use ($a_pilot_run_query) {
                    $a_pilot_run_query->whereNotNull($required_column_2_4);
                });
                $results_column_2_4 = $a_pilot_run_query->count();
                if($results_column_2_4 === 0){
                    return response()->json(['result' => '2', 'error' => 'Warning Please Update the Pilot Run A 2.4 Engg']);
                }
                $data = $request->all();
                $validator = Validator::make($data, [
                    'txt_1e_ini_check' => ['required'],
                    'txt_1e_fin_check' => ['required'],
                    'txt_2e_ini_check' => ['required'],
                    'txt_2e_fin_check' => ['required'],
                    'txt_3e_ini_check' => ['required'],
                    'txt_3e_fin_check' => ['required'],
                    'txt_4e_ini_check' => ['required'],
                    'txt_4e_fin_check' => ['required'],
                    'txt_4_e_actual_char' => ['required'],
                    'txt_5e_ini_check' => ['required'],
                    'txt_5e_fin_check' => ['required'],
                    'txt_6e_ini_check' => ['required'],
                    'txt_6e_fin_check' => ['required'],
                    'txt_7e_ini_check' => ['required'],
                    'txt_7e_fin_check' => ['required'],
                    'txt_8e_ini_check' => ['required'],
                    'txt_8e_fin_check' => ['required'],
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => '0', 'error' => $validator->messages()]);
                }
                else{
                    DB::beginTransaction();
                    try{
                        DPilotRun::where('id',$request->pilot_run_d_id)->update([
                            '1e_ini_check' => $request->txt_1e_ini_check,
                            '1e_fin_check' => $request->txt_1e_fin_check,
                            '1_remarks' => $request->txt_1_remarks,
                            '2e_ini_check' => $request->txt_2e_ini_check,
                            '2e_fin_check' => $request->txt_2e_fin_check,
                            '2_remarks' => $request->txt_2_remarks,
                            '3e_ini_check' => $request->txt_3e_ini_check,
                            '3e_fin_check' => $request->txt_3e_fin_check,
                            '3_remarks' => $request->txt_3_remarks,
                            '4e_ini_check' => $request->txt_4e_ini_check,
                            '4e_fin_check' => $request->txt_4e_fin_check,
                            '4e_actual_char' => $request->txt_4_e_actual_char,
                            '4_remarks' => $request->txt_4_remarks,
                            '4_actual_char_rem' => $request->txt_4_actual_char_rem,
                            '5e_ini_check' => $request->txt_5e_ini_check,
                            '5e_fin_check' => $request->txt_5e_fin_check,
                            '5_remarks' => $request->txt_5_remarks,
                            '6e_ini_check' => $request->txt_6e_ini_check,
                            '6e_fin_check' => $request->txt_6e_fin_check,
                            '6_remarks' => $request->txt_6_remarks,
                            '7e_ini_check' => $request->txt_7e_ini_check,
                            '7e_fin_check' => $request->txt_7e_fin_check,
                            '7_remarks' => $request->txt_7_remarks,
                            '8e_ini_check' => $request->txt_8e_ini_check,
                            '8e_fin_check' => $request->txt_8e_fin_check,
                            '8_remarks' => $request->txt_8_remarks,
                            'eng_checked_by' => $request->txt_echeckedby_id,
                            'eng_datetime' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        APilotRun::where('id',$request->global_pilotrun_id)
                        ->update([
                            'status' => 5, // E Eng Update
                        ]);
                        DB::commit();
                        return response()->json(['result' => "1"]);
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        throw $e;
                        return response()->json(['result' => "0"]);
                    }
                }

            }else{
                $data = $request->all();
                $validator = Validator::make($data, [
                    'txt_1q_ini_check'  => ['required'],
                    'txt_1q_fin_check'  => ['required'],
                    'txt_2q_ini_check'  => ['required'],
                    'txt_2q_fin_check'  => ['required'],
                    'txt_3q_ini_check'  => ['required'],
                    'txt_3q_fin_check'  => ['required'],
                    'txt_4q_ini_check'  => ['required'],
                    'txt_4q_fin_check'  => ['required'],
                    'txt_4_q_actual_char'  => ['required'],
                    'txt_5q_ini_check'  => ['required'],
                    'txt_5q_fin_check'  => ['required'],
                    'txt_6q_ini_check'  => ['required'],
                    'txt_6q_fin_check'  => ['required'],
                    'txt_7q_ini_check'  => ['required'],
                    'txt_7q_fin_check'  => ['required'],
                    'txt_8q_ini_check'  => ['required'],
                    'txt_8q_fin_check'  => ['required'],
                ]);

                $column_2_4_qc = [
                    '2_parts_app_design_qc',
                    '2_parts_app_design_qc_checkedby',
                ];

                collect($column_2_4_qc)->each(function ($required_column_2_4) use ($a_pilot_run_query) {
                    $a_pilot_run_query->whereNotNull($required_column_2_4);
                });
                $results_column_2_4 = $a_pilot_run_query->count();
                if($results_column_2_4 === 0){
                    return response()->json(['result' => '2', 'error' => 'Warning Please Update the Pilot Run A 2.4 QC']);
                }

                if ($validator->fails()) {
                    return response()->json(['result' => '0', 'error' => $validator->messages()]);
                }
                else{
                    DB::beginTransaction();
                    try{
                        APilotRun::where('id',$request->global_pilotrun_id)
                        ->update([
                            'status' => 8, // For Engr Dispostion
                        ]);

                        DPilotRun::where('id',$request->pilot_run_d_id)->update([
                            '1q_ini_check' => $request->txt_1q_ini_check,
                            '1q_fin_check' => $request->txt_1q_fin_check,
                            '1_remarks' => $request->txt_1_remarks,
                            '2q_ini_check' => $request->txt_2q_ini_check,
                            '2q_fin_check' => $request->txt_2q_fin_check,
                            '2_remarks' => $request->txt_2_remarks,
                            '3q_ini_check' => $request->txt_3q_ini_check,
                            '3q_fin_check' => $request->txt_3q_fin_check,
                            '3_remarks' => $request->txt_3_remarks,
                            '4q_ini_check' => $request->txt_4q_ini_check,
                            '4q_fin_check' => $request->txt_4q_fin_check,
                            '4q_actual_char' => $request->txt_4_q_actual_char,
                            '4_remarks' => $request->txt_4_remarks,
                            '4_actual_char_rem' => $request->txt_4_actual_char_rem,
                            '5q_ini_check' => $request->txt_5q_ini_check,
                            '5q_fin_check' => $request->txt_5q_fin_check,
                            '5_remarks' => $request->txt_5_remarks,
                            '6q_ini_check' => $request->txt_6q_ini_check,
                            '6q_fin_check' => $request->txt_6q_fin_check,
                            '6_remarks' => $request->txt_6_remarks,
                            '7q_ini_check' => $request->txt_7q_ini_check,
                            '7q_fin_check' => $request->txt_7q_fin_check,
                            '7_remarks' => $request->txt_7_remarks,
                            '8q_ini_check' => $request->txt_8q_ini_check,
                            '8q_fin_check' => $request->txt_8q_fin_check,
                            '8_remarks' => $request->txt_8_remarks,

                            'qc_checked_by' => $request->txt_qcheckedby_id,
                            'qc_datetime' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        DB::commit();
                        return response()->json(['result' => "1"]);
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        throw $e;
                        return response()->json(['result' => "0"]);
                    }
                }

            }

        }else{ // For Insert

            $data = $request->all();
            $validator = Validator::make($data, [
                'txt_1p_ini_check' => ['required'],
                'txt_1p_fin_check' => ['required'],
                'txt_2p_ini_check' => ['required'],
                'txt_2p_fin_check' => ['required'],
                'txt_3p_ini_check' => ['required'],
                'txt_3p_fin_check' => ['required'],
                'txt_4p_ini_check' => ['required'],
                'txt_4p_fin_check' => ['required'],
                'txt_5p_ini_check' => ['required'],
                'txt_5p_fin_check' => ['required'],
                'txt_6p_ini_check' => ['required'],
                'txt_6p_fin_check' => ['required'],
                'txt_7p_ini_check' => ['required'],
                'txt_7p_fin_check' => ['required'],
                'txt_8p_ini_check' => ['required'],
                'txt_8p_fin_check' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();
                try{
                    $column_2_4_prodn = [
                        '2_parts_app_design_prodn',
                        '2_parts_app_design_prodn_checkedby',
                    ];
                    collect($column_2_4_prodn)->each(function ($required_column_2_4) use ($a_pilot_run_query) {
                        $a_pilot_run_query->whereNotNull($required_column_2_4);
                    });
                    $results_column_2_4 = $a_pilot_run_query->count();
                    if($results_column_2_4 === 0){
                        return response()->json(['result' => '2', 'error' => 'Warning Please Update the Pilot Run A 2.4 Prodn']);
                    }
                    APilotRun::where('id',$request->global_pilotrun_id)
                    ->update([
                        'status' => 3, // C For Engr
                    ]);

                    DPilotRun::insert([
                        'fkid_a' => $request->global_pilotrun_id,
                        'po_no' => $request->txt_po_num,
                        // 'po_no' => $request->global_pilotrun_po,
                        '1p_ini_check' => $request->txt_1p_ini_check,
                        '1p_fin_check' => $request->txt_1p_fin_check,
                        // '1e_ini_check' => $request->txt_1e_ini_check,
                        // '1e_fin_check' => $request->txt_1e_fin_check,
                        // '1q_ini_check' => $request->txt_1q_ini_check,
                        // '1q_fin_check' => $request->txt_1q_fin_check,
                        '1_remarks' => $request->txt_1_remarks,
                        '2p_ini_check' => $request->txt_2p_ini_check,
                        '2p_fin_check' => $request->txt_2p_fin_check,
                        // '2e_ini_check' => $request->txt_2e_ini_check,
                        // '2e_fin_check' => $request->txt_2e_fin_check,
                        // '2q_ini_check' => $request->txt_2q_ini_check,
                        // '2q_fin_check' => $request->txt_2q_fin_check,
                        '2_remarks' => $request->txt_2_remarks,
                        '3p_ini_check' => $request->txt_3p_ini_check,
                        '3p_fin_check' => $request->txt_3p_fin_check,
                        // '3e_ini_check' => $request->txt_3e_ini_check,
                        // '3e_fin_check' => $request->txt_3e_fin_check,
                        // '3q_ini_check' => $request->txt_3q_ini_check,
                        // '3q_fin_check' => $request->txt_3q_fin_check,
                        '3_remarks' => $request->txt_3_remarks,
                        '4p_ini_check' => $request->txt_4p_ini_check,
                        '4p_fin_check' => $request->txt_4p_fin_check,
                        // '4e_ini_check' => $request->txt_4e_ini_check,
                        // '4e_fin_check' => $request->txt_4e_fin_check,
                        // '4q_ini_check' => $request->txt_4q_ini_check,
                        // '4q_fin_check' => $request->txt_4q_fin_check,
                        '4p_actual_char' => $request->txt_4_p_actual_char,
                        '4_remarks' => $request->txt_4_remarks,
                        '5p_ini_check' => $request->txt_5p_ini_check,
                        '5p_fin_check' => $request->txt_5p_fin_check,
                        // '5e_ini_check' => $request->txt_5e_ini_check,
                        // '5e_fin_check' => $request->txt_5e_fin_check,
                        // '5q_ini_check' => $request->txt_5q_ini_check,
                        // '5q_fin_check' => $request->txt_5q_fin_check,
                        '5_remarks' => $request->txt_5_remarks,
                        '6p_ini_check' => $request->txt_6p_ini_check,
                        '6p_fin_check' => $request->txt_6p_fin_check,
                        // '6e_ini_check' => $request->txt_6e_ini_check,
                        // '6e_fin_check' => $request->txt_6e_fin_check,
                        // '6q_ini_check' => $request->txt_6q_ini_check,
                        // '6q_fin_check' => $request->txt_6q_fin_check,
                        '6_remarks' => $request->txt_6_remarks,
                        '7p_ini_check' => $request->txt_7p_ini_check,
                        '7p_fin_check' => $request->txt_7p_fin_check,
                        // '7e_ini_check' => $request->txt_7e_ini_check,
                        // '7e_fin_check' => $request->txt_7e_fin_check,
                        // '7q_ini_check' => $request->txt_7q_ini_check,
                        // '7q_fin_check' => $request->txt_7q_fin_check,
                        '7_remarks' => $request->txt_7_remarks,
                        '8p_ini_check' => $request->txt_8p_ini_check,
                        '8p_fin_check' => $request->txt_8p_fin_check,
                        // '8e_ini_check' => $request->txt_8e_ini_check,
                        // '8e_fin_check' => $request->txt_8e_fin_check,
                        // '8q_ini_check' => $request->txt_8q_ini_check,
                        // '8q_fin_check' => $request->txt_8q_fin_check,
                        '8_remarks' => $request->txt_8_remarks,
                        'prodn_checked_by' => $request->txt_pcheckedby_id,
                        'prdn_datetime' => date('Y-m-d H:i:s'),
                        // 'eng_checked_by' => $request->txt_echeckedby_id,
                        // 'eng_datetime' => date('Y-m-d H:i:s'),
                        // 'qc_checked_by' => $request->txt_qcheckedby_id,
                        // 'qc_datetime' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id,
                        'logdel' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                        // 'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }
    }
    public function add_pilotrun_Ddetails_orig(Request $request){
        date_default_timezone_set('Asia/Manila');

        // For Update
        if(isset($request->pilot_run_d_id)){
            // return $request->pilot_run_d_status;
            if ($request->pilot_run_d_status == 4){ // D - Done Engr
                $data = $request->all();
                $validator = Validator::make($data, [
                    // 'txt_pot_fail' => ['required'],
                    // 'hidden_term_length_pn' => ['required'],
                    // 'measurement_result' => ['required'],
                    // 'hidden_measure_result_pn' => ['required'],
                    // 'dimension' => ['required'],
                    // 'vir' => ['required'],
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => '0', 'error' => $validator->messages()]);
                }
                else{
                    DB::beginTransaction();
                    try{
                        APilotRun::where('id',$request->global_pilotrun_id)
                        ->update([
                            'status' => 5, // E Eng DONE
                        ]);

                        DPilotRun::where('id',$request->pilot_run_d_id)->update([
                            // 'fkid_a' => $request->global_pilotrun_id,
                            // 'po_no' => $request->global_pilotrun_po,
                            // '1p_ini_check' => $request->txt_1p_ini_check,
                            // '1p_fin_check' => $request->txt_1p_fin_check,
                            '1e_ini_check' => $request->txt_1e_ini_check,
                            '1e_fin_check' => $request->txt_1e_fin_check,
                            // '1q_ini_check' => $request->txt_1q_ini_check,
                            // '1q_fin_check' => $request->txt_1q_fin_check,
                            '1_remarks' => $request->txt_1_remarks,
                            // '2p_ini_check' => $request->txt_2p_ini_check,
                            // '2p_fin_check' => $request->txt_2p_fin_check,
                            '2e_ini_check' => $request->txt_2e_ini_check,
                            '2e_fin_check' => $request->txt_2e_fin_check,
                            // '2q_ini_check' => $request->txt_2q_ini_check,
                            // '2q_fin_check' => $request->txt_2q_fin_check,
                            '2_remarks' => $request->txt_2_remarks,
                            // '3p_ini_check' => $request->txt_3p_ini_check,
                            // '3p_fin_check' => $request->txt_3p_fin_check,
                            '3e_ini_check' => $request->txt_3e_ini_check,
                            '3e_fin_check' => $request->txt_3e_fin_check,
                            // '3q_ini_check' => $request->txt_3q_ini_check,
                            // '3q_fin_check' => $request->txt_3q_fin_check,
                            '3_remarks' => $request->txt_3_remarks,
                            // '4p_ini_check' => $request->txt_4p_ini_check,
                            // '4p_fin_check' => $request->txt_4p_fin_check,
                            '4e_ini_check' => $request->txt_4e_ini_check,
                            '4e_fin_check' => $request->txt_4e_fin_check,
                            // '4q_ini_check' => $request->txt_4q_ini_check,
                            // '4q_fin_check' => $request->txt_4q_fin_check,
                            '4e_actual_char' => $request->txt_4_e_actual_char,
                            '4_remarks' => $request->txt_4_remarks,
                            '4_actual_char_rem' => $request->txt_4_actual_char_rem,
                            // '5p_ini_check' => $request->txt_5p_ini_check,
                            // '5p_fin_check' => $request->txt_5p_fin_check,
                            '5e_ini_check' => $request->txt_5e_ini_check,
                            '5e_fin_check' => $request->txt_5e_fin_check,
                            // '5q_ini_check' => $request->txt_5q_ini_check,
                            // '5q_fin_check' => $request->txt_5q_fin_check,
                            '5_remarks' => $request->txt_5_remarks,
                            // '6p_ini_check' => $request->txt_6p_ini_check,
                            // '6p_fin_check' => $request->txt_6p_fin_check,
                            '6e_ini_check' => $request->txt_6e_ini_check,
                            '6e_fin_check' => $request->txt_6e_fin_check,
                            // '6q_ini_check' => $request->txt_6q_ini_check,
                            // '6q_fin_check' => $request->txt_6q_fin_check,
                            '6_remarks' => $request->txt_6_remarks,
                            // '7p_ini_check' => $request->txt_7p_ini_check,
                            // '7p_fin_check' => $request->txt_7p_fin_check,
                            '7e_ini_check' => $request->txt_7e_ini_check,
                            '7e_fin_check' => $request->txt_7e_fin_check,
                            // '7q_ini_check' => $request->txt_7q_ini_check,
                            // '7q_fin_check' => $request->txt_7q_fin_check,
                            '7_remarks' => $request->txt_7_remarks,
                            // '8p_ini_check' => $request->txt_8p_ini_check,
                            // '8p_fin_check' => $request->txt_8p_fin_check,
                            '8e_ini_check' => $request->txt_8e_ini_check,
                            '8e_fin_check' => $request->txt_8e_fin_check,
                            // '8q_ini_check' => $request->txt_8q_ini_check,
                            // '8q_fin_check' => $request->txt_8q_fin_check,
                            '8_remarks' => $request->txt_8_remarks,
                            // 'prodn_checked_by' => $request->txt_pcheckedby_id,
                            // 'prdn_datetime' => date('Y-m-d H:i:s'),
                            'eng_checked_by' => $request->txt_echeckedby_id,
                            'eng_datetime' => date('Y-m-d H:i:s'),
                            // 'qc_checked_by' => $request->txt_qcheckedby_id,
                            // 'qc_datetime' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        DB::commit();
                        return response()->json(['result' => "1"]);
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        throw $e;
                        return response()->json(['result' => "0"]);
                    }
                }

            }else{
                $data = $request->all();
                $validator = Validator::make($data, [
                    // 'txt_pot_fail' => ['required'],
                    // 'hidden_term_length_pn' => ['required'],
                    // 'measurement_result' => ['required'],
                    // 'hidden_measure_result_pn' => ['required'],
                    // 'dimension' => ['required'],
                    // 'vir' => ['required'],
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => '0', 'error' => $validator->messages()]);
                }
                else{
                    DB::beginTransaction();
                    try{
                        APilotRun::where('id',$request->global_pilotrun_id)
                        ->update([
                            'status' => 8, // For Engr Dispostion
                        ]);

                        DPilotRun::where('id',$request->pilot_run_d_id)->update([
                            '1q_ini_check' => $request->txt_1q_ini_check,
                            '1q_fin_check' => $request->txt_1q_fin_check,
                            '1_remarks' => $request->txt_1_remarks,
                            '2q_ini_check' => $request->txt_2q_ini_check,
                            '2q_fin_check' => $request->txt_2q_fin_check,
                            '2_remarks' => $request->txt_2_remarks,
                            '3q_ini_check' => $request->txt_3q_ini_check,
                            '3q_fin_check' => $request->txt_3q_fin_check,
                            '3_remarks' => $request->txt_3_remarks,
                            '4q_ini_check' => $request->txt_4q_ini_check,
                            '4q_fin_check' => $request->txt_4q_fin_check,
                            '4q_actual_char' => $request->txt_4_q_actual_char,
                            '4_remarks' => $request->txt_4_remarks,
                            '4_actual_char_rem' => $request->txt_4_actual_char_rem,
                            '5q_ini_check' => $request->txt_5q_ini_check,
                            '5q_fin_check' => $request->txt_5q_fin_check,
                            '5_remarks' => $request->txt_5_remarks,
                            '6q_ini_check' => $request->txt_6q_ini_check,
                            '6q_fin_check' => $request->txt_6q_fin_check,
                            '6_remarks' => $request->txt_6_remarks,
                            '7q_ini_check' => $request->txt_7q_ini_check,
                            '7q_fin_check' => $request->txt_7q_fin_check,
                            '7_remarks' => $request->txt_7_remarks,
                            '8q_ini_check' => $request->txt_8q_ini_check,
                            '8q_fin_check' => $request->txt_8q_fin_check,
                            '8_remarks' => $request->txt_8_remarks,

                            'qc_checked_by' => $request->txt_qcheckedby_id,
                            'qc_datetime' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        DB::commit();
                        return response()->json(['result' => "1"]);
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        throw $e;
                        return response()->json(['result' => "0"]);
                    }
                }

            }

        }else{ // For Insert

            $data = $request->all();
            $validator = Validator::make($data, [
                // 'txt_1p_fin_check' => ['required'],
                // 'hidden_term_length_pn' => ['required'],
                // 'measurement_result' => ['required'],
                // 'hidden_measure_result_pn' => ['required'],
                // 'dimension' => ['required'],
                // 'vir' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();
                try{
                    APilotRun::where('id',$request->global_pilotrun_id)
                    ->update([
                        'status' => 3, // C For Engr
                    ]);

                    DPilotRun::insert([
                        'fkid_a' => $request->global_pilotrun_id,
                        'po_no' => $request->txt_po_num,
                        // 'po_no' => $request->global_pilotrun_po,
                        '1p_ini_check' => $request->txt_1p_ini_check,
                        '1p_fin_check' => $request->txt_1p_fin_check,
                        // '1e_ini_check' => $request->txt_1e_ini_check,
                        // '1e_fin_check' => $request->txt_1e_fin_check,
                        // '1q_ini_check' => $request->txt_1q_ini_check,
                        // '1q_fin_check' => $request->txt_1q_fin_check,
                        '1_remarks' => $request->txt_1_remarks,
                        '2p_ini_check' => $request->txt_2p_ini_check,
                        '2p_fin_check' => $request->txt_2p_fin_check,
                        // '2e_ini_check' => $request->txt_2e_ini_check,
                        // '2e_fin_check' => $request->txt_2e_fin_check,
                        // '2q_ini_check' => $request->txt_2q_ini_check,
                        // '2q_fin_check' => $request->txt_2q_fin_check,
                        '2_remarks' => $request->txt_2_remarks,
                        '3p_ini_check' => $request->txt_3p_ini_check,
                        '3p_fin_check' => $request->txt_3p_fin_check,
                        // '3e_ini_check' => $request->txt_3e_ini_check,
                        // '3e_fin_check' => $request->txt_3e_fin_check,
                        // '3q_ini_check' => $request->txt_3q_ini_check,
                        // '3q_fin_check' => $request->txt_3q_fin_check,
                        '3_remarks' => $request->txt_3_remarks,
                        '4p_ini_check' => $request->txt_4p_ini_check,
                        '4p_fin_check' => $request->txt_4p_fin_check,
                        // '4e_ini_check' => $request->txt_4e_ini_check,
                        // '4e_fin_check' => $request->txt_4e_fin_check,
                        // '4q_ini_check' => $request->txt_4q_ini_check,
                        // '4q_fin_check' => $request->txt_4q_fin_check,
                        '4p_actual_char' => $request->txt_4_p_actual_char,
                        '4_remarks' => $request->txt_4_remarks,
                        '5p_ini_check' => $request->txt_5p_ini_check,
                        '5p_fin_check' => $request->txt_5p_fin_check,
                        // '5e_ini_check' => $request->txt_5e_ini_check,
                        // '5e_fin_check' => $request->txt_5e_fin_check,
                        // '5q_ini_check' => $request->txt_5q_ini_check,
                        // '5q_fin_check' => $request->txt_5q_fin_check,
                        '5_remarks' => $request->txt_5_remarks,
                        '6p_ini_check' => $request->txt_6p_ini_check,
                        '6p_fin_check' => $request->txt_6p_fin_check,
                        // '6e_ini_check' => $request->txt_6e_ini_check,
                        // '6e_fin_check' => $request->txt_6e_fin_check,
                        // '6q_ini_check' => $request->txt_6q_ini_check,
                        // '6q_fin_check' => $request->txt_6q_fin_check,
                        '6_remarks' => $request->txt_6_remarks,
                        '7p_ini_check' => $request->txt_7p_ini_check,
                        '7p_fin_check' => $request->txt_7p_fin_check,
                        // '7e_ini_check' => $request->txt_7e_ini_check,
                        // '7e_fin_check' => $request->txt_7e_fin_check,
                        // '7q_ini_check' => $request->txt_7q_ini_check,
                        // '7q_fin_check' => $request->txt_7q_fin_check,
                        '7_remarks' => $request->txt_7_remarks,
                        '8p_ini_check' => $request->txt_8p_ini_check,
                        '8p_fin_check' => $request->txt_8p_fin_check,
                        // '8e_ini_check' => $request->txt_8e_ini_check,
                        // '8e_fin_check' => $request->txt_8e_fin_check,
                        // '8q_ini_check' => $request->txt_8q_ini_check,
                        // '8q_fin_check' => $request->txt_8q_fin_check,
                        '8_remarks' => $request->txt_8_remarks,
                        'prodn_checked_by' => $request->txt_pcheckedby_id,
                        'prdn_datetime' => date('Y-m-d H:i:s'),
                        // 'eng_checked_by' => $request->txt_echeckedby_id,
                        // 'eng_datetime' => date('Y-m-d H:i:s'),
                        // 'qc_checked_by' => $request->txt_qcheckedby_id,
                        // 'qc_datetime' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id,
                        'logdel' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                        // 'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }
    }

    public function add_pilotrun_Edetails(Request $request){
        date_default_timezone_set('Asia/Manila');

         // For Update
        if(isset($request->pilot_run_e_id)){

            $data = $request->all();
            $validator = Validator::make($data, [
                'rdo_qfinal_dispo' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();
                try{
                    APilotRun::where('id',$request->global_pilotrun_id)
                    ->update([
                        'status' => 9, // Conformed by qc
                    ]);

                    EPilotRun::where('id',$request->pilot_run_e_id)->update([
                    'qfinal_dispo' => $request->rdo_qfinal_dispo,
                    'final_by_qc' => $request->txt_qfinal_dispo_pn_id,
                    'qconf_date' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    DB::commit();
                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }



        }else{ // For Insert

            $data = $request->all();
            $validator = Validator::make($data, [
                'rdo_efinal_dispo' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }
            else{
                DB::beginTransaction();
                try{
                    APilotRun::where('id',$request->global_pilotrun_id)
                    ->update([
                        'status' => 6, // E Engr - DONE
                    ]);

                    EPilotRun::insert([
                    'fkid_a' => $request->global_pilotrun_id,
                    'po_no' => $request->txt_po_num,
                    'efinal_dispo' => $request->rdo_efinal_dispo,
                    'final_by_engr' => $request->txt_efinal_dispo_pn_id,
                    'econf_date' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'logdel' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                    return response()->json(['result' => "0"]);
                }
            }
        }
    }

    public function load_user_details(Request $request){
        $user_details = User::where('employee_id', $request->employee_id)->where('status', 1)->where('status', 1)->get();

        if(count($user_details) > 0)
        {
            return response()->json(['result' => 1, 'user_details' => $user_details]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

}
