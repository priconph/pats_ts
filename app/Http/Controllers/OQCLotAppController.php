<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\oqcLotApp;
use App\Model\OQCDBM;
use App\Model\AssemblyLine;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\MaterialIssuanceDetails;
use App\Model\ProductionRuncard;
use App\Model\ProductionRuncardStation;
use App\Model\RapidActiveDocs;
use App\Model\Device;
use App\Model\Series;

use App\User;
use QrCode;


use DataTables;

use Excel;
use App\Exports\PartsLotReportExport;

class OQCLotAppController extends Controller
{

    // Get added data
    public function get_oqc_lot_app_data(Request $request){
        $oqc_inspections = ProductionRuncard::with([
            'oqc_details' => function($query){
                $query->orderBy('submission', 'DESC');},
            'oqc_details.user_details',
            'oqc_details.supervisor_prod_info',
            'oqc_details.supervisor_qc_info',
            'prod_runcard_station_many_details' => function($query){
                $query
                ->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'));
            },
            'wbs_kitting',
            'wbs_kitting.device_info'
        ])

        ->where('po_no',$request['po_no'])
        ->where(function($query){
            $query
                ->where('status', 7)
                ->orWhere('status', 8);
        })
        ->get();

        return DataTables::of($oqc_inspections)

        ->addColumn('output_qty_raw', function($oqc_inspection){
            $result = "";

            if( $oqc_inspection->require_oqc_before_emboss == 1){ //old

                $temp_prod_runcard_station_many_details = array();
                foreach ($oqc_inspection['prod_runcard_station_many_details'] as $key => $value) {
                    if( $value['has_emboss'] == 0 ){
                        $temp_prod_runcard_station_many_details[] = $value;
                    }
                    // else{
                    //     $temp_prod_runcard_station_many_details[] = $value;
                    // }
                }

                $ix = count($temp_prod_runcard_station_many_details);
                $result = $temp_prod_runcard_station_many_details[$ix-1]->qty_output;

                if ( $oqc_inspection['oqc_details'] ){
                    if ( $oqc_inspection['oqc_details']->output_qty != null ){
                        $result = $oqc_inspection['oqc_details']->output_qty;
                    }
                }


            }else{

                $ix = count($oqc_inspection['prod_runcard_station_many_details']);
                $result = $oqc_inspection['prod_runcard_station_many_details'][$ix-1]->qty_output;

                if ( $oqc_inspection['oqc_details'] ){
                    if ( $oqc_inspection['oqc_details']->output_qty != null ){
                        $result = $oqc_inspection['oqc_details']->output_qty;
                    }
                }
            }

            return $result;
        })

        ->addColumn('fvo_raw', function($oqc_inspection){
            $result = null;

            if ( $oqc_inspection['oqc_details'] ){
                $empno_arr = array();
                $empno_arr = $oqc_inspection['oqc_details']->FVO_empid;
                $empno_arr = explode(',', $empno_arr);
                $user_details = array();
                if(count( $empno_arr )){
                    for ($i=0; $i < count($empno_arr) ; $i++) {
                        $user_details_temp = array();
                        $user_details_temp = User::where('id',$empno_arr[$i])->get();
                        array_push($user_details, $user_details_temp);
                    }
                }

                if( count($user_details) ){
                    foreach ($user_details as $key => $value) {
                        $result .= '<span class="badge badge-pill badge-info"> '.$value[0]['name'].'</span> ';
                    }
                }
            }

            return $result;
        })

        ->addColumn('status_raw', function($oqc_inspection){
            $result = "";

            if ($oqc_inspection->has_emboss == 1){
                if ($oqc_inspection->require_oqc_before_emboss == 1){
                    if($oqc_inspection->status == 7) {
                        $result ='<span class="badge badge-pill badge-info">For Lot Application</span>';
                        if ($oqc_inspection->oqc_details != null){
                            switch ($oqc_inspection->oqc_details->status) {
                                case 1:
                                    $result ='<span class="badge badge-pill badge-warning">For OQC Supv. Approval</span>';
                                    break;
                                case 2:
                                    $result ='<span class="badge badge-pill badge-success">Done; Closed Lot</span>';
                                    break;
                                default:
                                    $result ='<span class="badge badge-pill badge-primary">For Prod`n Supv. Approval</span>';
                                    break;
                            }
                        } else{
                            $result ='<span class="badge badge-pill badge-info">For Lot Application</span>';
                        }
                    }
                    else if($oqc_inspection->status == 8) {
                        $result ='<span class="badge badge-pill badge-info">Emboss Done</span>';
                        if ($oqc_inspection->oqc_details != null){
                            switch ($oqc_inspection->oqc_details->status) {
                                case 1:
                                    $result ='<span class="badge badge-pill badge-warning">For OQC Supv. Approval</span>';
                                    break;
                                case 2:
                                    $result ='<span class="badge badge-pill badge-success">Done; Closed Lot</span>';
                                    break;
                                default:
                                    $result ='<span class="badge badge-pill badge-primary">For Prod`n Supv. Approval</span>';
                                    break;
                            }

                        } else {
                            $result ='<span class="badge badge-pill badge-info">Emboss Done | For Lot Application</span>';
                        }

                    }
                    else{
                        $result ='<span class="badge badge-pill badge-info">Pending in Runcard</span>';
                    }
                }
                else{
                    if($oqc_inspection->status == 8) {
                        $result ='<span class="badge badge-pill badge-info">Emboss Done | For Lot Application</span>';
                        if ($oqc_inspection->oqc_details != null){
                            switch ($oqc_inspection->oqc_details->status) {
                                case 1:
                                    $result ='<span class="badge badge-pill badge-warning">For OQC Supv. Approval</span>';
                                    break;
                                case 2:
                                    $result ='<span class="badge badge-pill badge-success">Done; Closed Lot</span>';
                                    break;
                                default:
                                    $result ='<span class="badge badge-pill badge-primary">For Prod`n Supv. Approval</span>';
                                    break;
                            }

                        }
                    }
                    else if($oqc_inspection->status == 7) {
                        $result ='<span class="badge badge-pill badge-info">Done Runcard | Pending Emboss</span>';
                    }
                    else{
                        $result ='<span class="badge badge-pill badge-info">Pending in Runcard</span>';
                    }
                }
            }

            // if ($oqc_inspection->status == 8){
            //     if( $oqc_inspection->require_oqc_before_emboss == 1){
            //         $result ='<span class="badge badge-pill badge-info">For Lot Application</span>';
            //     }else{
            //         $result ='<span class="badge badge-pill badge-info">OQC Not required before emboss</span>';
            //     }
            // }else{

            //     if( $oqc_inspection->require_oqc_before_emboss == 1){
            //         $result ='<span class="badge badge-pill badge-info">For Lot Application</span>';
            //     }else{
            //         $result ='<span class="badge badge-pill badge-info">OQC Not required before emboss</span>';
            //     }

            // }


            // if( $oqc_inspection->require_oqc_before_emboss == 1){
            //     if ($oqc_inspection->oqc_details != null){
            //         switch ($oqc_inspection->oqc_details->status) {
            //             case 1:
            //                 $result ='<span class="badge badge-pill badge-warning">For OQC Supv. Approval</span>';
            //                 break;
            //             case 2:
            //                 $result ='<span class="badge badge-pill badge-success">Done; Closed Lot</span>';
            //                 break;
            //             default:
            //                 $result ='<span class="badge badge-pill badge-primary">For Prod`n Supv. Approval</span>';
            //                 break;
            //         }

            //     }else{

            //         $result ='<span class="badge badge-pill badge-info">For Lot Application</span>';
            //     }

            // }else{

            //         $result ='<span class="badge badge-pill badge-info">OQC Not required before emboss</span>';
            // }



            return $result;
        })

        ->addColumn('subm_raw', function($oqc_inspection){
            $result = "";

            if ($oqc_inspection->oqc_details != null){
                switch ($oqc_inspection->oqc_details->submission) {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                        break;
                    case 3:
                        $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                        break;
                }

            }else{
                $result ='---';
            }
            return $result;
        })

        ->addColumn('sub_lot_raw', function($oqc_inspection)use($oqc_inspections){
            $result = "";

            $runcard_no = ($oqc_inspection->runcard_no);
            $newstring = substr($runcard_no, -3);

            $device_name = ($oqc_inspection->wbs_kitting->device_name);
            $devices = Device::where('name', $device_name)->get()->take(1);

            $ctr = 1;
            if ( $devices ){

                $i_total = 1000;
                $q = $devices[0]->ship_boxing / $devices[0]->boxing;

                for($i=$q;$i<$i_total; $i=$i+$q){
                    if( $newstring <= $i ){
                        break;
                    }
                    $ctr++;
                }

            }

            return $ctr;
        })

        // ->addColumn('packing_code_raw', function($oqc_inspection)use($oqc_inspections){
        //     $result = "";
        //     $series_name = "";

        //     $runcard_no = ($oqc_inspection->runcard_no);
        //     $newstring = substr($runcard_no, -3);

        //     $device_name = ($oqc_inspection->wbs_kitting->device_name);
        //     $devices     = Device::where('name', $device_name)->get()->take(1);
        //     $exp_series  = explode("-", $devices[0]->name)[0];
        //     $series_code = Series::where('series_name', 'like','%'.$exp_series.'%')->get()->take(1);
        //     $month       = date('m');

        //     $ctr = 1;
        //     if ( $devices ){

        //         $i_total = 1000;
        //         $q = $devices[0]->ship_boxing / $devices[0]->boxing;

        //         for($i=$q;$i<$i_total; $i=$i+$q){
        //             if( $newstring <= $i ){
        //                 break;
        //             }
        //             $ctr++;
        //         }

        //     }
        //     $counter = sprintf('%03d', $ctr);

        //     $packing_code = $series_code[0]->series_code.''.$month.'-'.$counter;
        //     return $packing_code;

        // })

        ->addColumn('action', function($oqc_inspection){
            $result = "";

            $result.='<button type="button" class="btn btn-sm btn-success btn_update_lot" id="btn_update" data-toggle="modal" value="'.$oqc_inspection['id'].'" title="View/Update Details"><i class="fa fa-pencil-alt fa-sm"></i></button>';

            if ($oqc_inspection->oqc_details != null){
              $result.=' <button type="button" class="btn btn-sm btn-primary btn_print_lot" id="btn_print" data-toggle="modal" value="'.$oqc_inspection['id'].'" title="Print barcode"><i class="fa fa-print fa-sm"></i></button>';
            }else{
              $result.=' <button type="button" class="btn btn-sm btn-secondary btn_print_lot" id="btn_print" data-toggle="modal" title="No Lot App Details" disabled><i class="fa fa-print fa-sm"></i></button>';

            }


            return $result;
        })

        ->rawColumns(['action','status_raw','fvo_raw','output_qty_raw','subm_raw','sub_lot_raw'])
        // ->rawColumns(['action','status_raw','fvo_raw','output_qty_raw','subm_raw','sub_lot_raw','packing_code_raw'])
        ->make(true);
    }

    // Get lot app summary
    public function get_oqc_lot_app_data_summary(Request $request){
        $oqc_inspections = oqcLotApp::where('lot_batch_no',$request['lot_batch_no'])
        // ->where('status', 2)
        ->orderBy('submission','asc')->get();
        return DataTables::of($oqc_inspections)
        ->addColumn('sub_raw', function($oqc_inspection){
            $result = "";

                switch ($oqc_inspection->submission) {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                        break;
                    case 3:
                        $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                        break;
                }

            return $result;
        })

        // ->addColumn('devcat_raw', function($oqc_inspection){
        //     $result = "";

        //         switch ($oqc_inspection->device_cat) {
        //             case 1:
        //                 $result ='Automotive';
        //                 break;
        //             case 2:
        //                 $result ='Non-Automotive';
        //                 break;
        //         }

        //     return $result;
        // })

        // ->addColumn('certlot_raw', function($oqc_inspection){
        //     $result = "";

        //         switch ($oqc_inspection->cert_lot) {
        //             case 1:
        //                 $result ='New Operator';
        //                 break;
        //             case 2:
        //                 $result ='New product/model';
        //                 break;
        //             case 3:
        //                 $result ='Evaluation lot';
        //                 break;
        //             case 4:
        //                 $result ='Re-inspection';
        //                 break;
        //             case 5:
        //                 $result ='Flexibility';
        //                 break;
        //             case 6:
        //                 $result ='N/A';
        //                 break;
        //         }

        //     return $result;
        // })

        // ->addColumn('assy_raw', function($oqc_inspection){
        // $result = "";

        // $assy_line = AssemblyLine::where('id',$oqc_inspection['assy_line'])->get();
        //     if( $assy_line ){
        //         $result = $assy_line[0]->name;
        //     }

        // return $result;

        // })
            //nmodify guaranteed_lot 
        ->addColumn('guar_lot_raw', function($oqc_inspection){
            $result = "";

                switch ($oqc_inspection->guaranteed_lot) {
                    case 1:
                        $result = '<font color="red">With</font>';
                        break;
                    case 2:
                        $result = '<font color="black">Without</font>';
                        break;
                }

            return $result;
        })

        ->addColumn('fvo_raw', function($oqc_inspection){
            $result = null;

            if ( $oqc_inspection ){
                $empno_arr = array();
                $empno_arr = $oqc_inspection->FVO_empid;
                $empno_arr = explode(',', $empno_arr);
                $user_details = array();
                if(count( $empno_arr )){
                    for ($i=0; $i < count($empno_arr) ; $i++) {
                        $user_details_temp = array();
                        $user_details_temp = User::where('id',$empno_arr[$i])->get();
                        array_push($user_details, $user_details_temp);
                    }
                }

                if( count($user_details) ){
                    foreach ($user_details as $key => $value) {
                        $result .= '<span class="badge badge-pill badge-info"> '.$value[0]['name'].'</span> ';
                    }
                }
            }

            return $result;
        })

        ->addColumn('app_date_raw', function($oqc_inspection){
            $result = "";
                $result = date('F j, Y',strtotime($oqc_inspection->app_date));
            return $result;
        })

        ->addColumn('app_time_raw', function($oqc_inspection){
            $result = "";
                $result .= date('h:i a',strtotime('2001-01-01'.$oqc_inspection->app_time));
            return $result;
        })

        ->addColumn('action', function($oqc_inspection){
            $result = "";
            return $result;
        })

        ->rawColumns(['action','guar_lot_raw','sub_raw','fvo_raw','app_date_raw','app_time_raw'])
        ->make(true);
    }

    // Get lot app by ID
    public function get_lot_app_by_id(Request $request){
        // $lot_app_by_id = oqcLotApp::with(['wbs_kitting'])->where('id',$request['id'])->get(); //02072020
        $lot_app_by_id = oqcLotApp::with(['wbs_kitting'])->where('fkid_runcard',$request['id'])->get();
        $lot_app_code = '';
        $po_no = '';

        if($lot_app_by_id->count() > 0){
            $lot_app_code = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($lot_app_by_id[0]->lot_batch_no);

            $lot_app_code = "data:image/png;base64," . base64_encode($lot_app_code);
        }

        if($lot_app_by_id->count() > 0){
            $po_no = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($lot_app_by_id[0]->po_no);

            $po_no = "data:image/png;base64," . base64_encode($po_no);
        }

        $inspection_standard = [];
        $rdrawing   = [];
        $IS_drawing = '';
        $R_drawing  = '';
        $A_drawing  = '';

        if ( count($lot_app_by_id) > 0 ){
            $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $lot_app_by_id[0]->wbs_kitting->device_name . '%')->get();

            if(count($doc_details_query) > 0){
                $inspection_standard = collect($doc_details_query)->where('doc_type', 'Inspection Standard')->flatten(1);
                $rdrawing = collect($doc_details_query)->where('doc_type', 'R Drawing')->flatten(1);
                $adrawing = collect($doc_details_query)->where('doc_type', 'A Drawing')->flatten(1);
            }

            if($inspection_standard->count() > 0){
                $IS_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($inspection_standard[0]->doc_no);
                                // ->generate($inspection_standard[0]->doc_no.'-'.$inspection_standard[0]->rev_no); //02072020

                $IS_drawing = "data:image/png;base64," . base64_encode($IS_drawing);
            }

            if($rdrawing->count() > 0){
                $R_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($rdrawing[0]->doc_no);
                                // ->generate($rdrawing[0]->doc_no.'-'.$rdrawing[0]->rev_no); //02072020

                $R_drawing = "data:image/png;base64," . base64_encode($R_drawing);
            }

            if($adrawing->count() > 0){
                $A_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($adrawing[0]->doc_no);
                                // ->generate($rdrawing[0]->doc_no.'-'.$rdrawing[0]->rev_no); //02072020

                $A_drawing = "data:image/png;base64," . base64_encode($A_drawing);
            }


        }

        return response()->json(['lot_app_by_id' => $lot_app_by_id, 'lot_app_code' => $lot_app_code, 'po_no' => $po_no, 'inspection_standard' => $inspection_standard, 'rdrawing' => $rdrawing, 'adrawing' => $adrawing, 'IS_drawing' => $IS_drawing, 'R_drawing' => $R_drawing, 'A_drawing' => $A_drawing]);
    }

    // Get runcard data
    public function get_runcard_details(Request $request){
        $runcard_details = ProductionRuncard::with([
            'oqc_details' => function($query){
                $query->orderBy('submission', 'DESC');},
            'oqc_details.oqcvir_details',
            'oqc_details.user_details',
            'oqc_details.supervisor_prod_info',
            'oqc_details.supervisor_qc_info',
            'prod_runcard_station_many_details' => function($query){
                $query
                // $query->where('has_emboss', '!=', 1)
                ->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'));
            },
            'wbs_kitting',
            'wbs_kitting.device_info'

        ])
        ->where('id',$request['id'])
        ->where(function($query){
            $query
                ->where('status', 7)
                ->orWhere('status', 8);
        })
        ->get();

        //- sub lot no.
        $sub_lot_no = 0;
        if( $runcard_details ){
            $runcard_no = ($runcard_details[0]->runcard_no);
            $newstring = substr($runcard_no, -3);

            $device_name = ($runcard_details[0]->wbs_kitting->device_name);
            $devices = Device::where('name', $device_name)->get()->take(1);

            $ctr = 1;
            if ( $devices ){

                $i_total = 1000;
                $q = $devices[0]->ship_boxing / $devices[0]->boxing;

                for($i=$q;$i<$i_total; $i=$i+$q){
                    if( $newstring <= $i ){
                        break;
                    }
                    $ctr++;
                }
            }
            $sub_lot_no = $ctr;
        }
        $runcard_details[0]->{'sub_lot_no'} = $sub_lot_no;
        return $runcard_details;
    }

    // Get PO Details from WBS kitting
    public function get_po_details(Request $request){
        $po_details = ProductionRuncard::with(['wbs_kitting','wbs_kitting.device_info'])->where('po_no', $request->po)->get();
        return response()->json(['po_details' => $po_details]);
    }

    // Get User details
    public function get_user_details_lotapp(Request $request){
        $user_details = User::where('employee_id','like','%'. $request->employee_id.'%')
        ->orWhere('name','like','%'. $request->employee_id.'%')
        ->orWhere('id','like','%'. $request->employee_id.'%')->get();
        return response()->json(['user_details' => $user_details]);
    }

    // Get User details - fullname arr
    public function get_user_details_lotapp_arr(Request $request){
        $empno_arr = array();
        $empno_arr = $request->empno_arr;
        $empno_arr = explode(',', $empno_arr);
        $user_details = array();
        if(count( $empno_arr )){
            for ($i=0; $i < count($empno_arr) ; $i++) {
                $user_details_temp = array();
                $user_details_temp = User::where('id',$empno_arr[$i])->get();
                array_push($user_details, $user_details_temp);
            }
        }
        return response()->json(['user_details' =>  $user_details ]);
    }

    // Get Production User details
    public function get_prod_user_details(Request $request){
        $user_details = User::where('employee_id', $request->employee_id)->where('position', 1)->get();
        return response()->json(['user_details' => $user_details]);
    }

    // Get OQC User details
    public function get_oqc_user_details(Request $request){
        $user_details = User::where('employee_id', $request->employee_id)->where('position', 2)->get();
        return response()->json(['user_details' => $user_details]);
    }

    // Add
    public function add_oqc_lot_app(Request $request){
        date_default_timezone_set('Asia/Manila');

        // return 'as';

        $user_exist = User::where('id', $request->txt_arr_names_fvo)->get();

        if($user_exist->count() == 0){
            return response()->json(['result' => "2"]); //invalid employee no.
        }

        if ( $request->hidden_sub > 3 ){
            return response()->json(['result' => "3"]); // more than 3rd sub
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'hidden_runcard_id' => ['required', 'string', 'max:255'],
            'name_po_no' => ['required', 'string', 'max:255'],
            'name_select_Device' => ['required', 'string', 'max:255'],
            'name_CertLot' => ['required', 'string', 'max:255'],
            'name_AssyLine' => ['required', 'string', 'max:255'],
            'name_LotBatch' => ['required', 'string', 'max:255'],
            'txt_arr_names_fvo' => ['required', 'string', 'max:255'],
            'name_ReelNo' => ['string', 'max:255'],
            'name_PrintLotNo' => ['required', 'string', 'max:255'],
            'name_LotQty' => ['required', 'string', 'max:255'],
            'name_OutputQty' => ['required', 'string', 'max:255'],
            'name_UrgentDirection' => ['max:255'],
            'name_ADrawing' => ['required', 'string', 'max:255'],
            'name_GDrawing' => ['required', 'string', 'max:255'],
            'name_TtlNoReels' => ['max:255'],
            'name_AppDate' => ['max:255'],
            'name_AppTime' => ['max:255'],
            'name_GuaranteedLot' => ['required', 'max:255'],
            'name_Problem' => ['max:255'],
            'name_DocNo' => ['max:255'],
            'name_prodn_supv' => ['max:255'],
            'name_oqc_supv' => ['max:255'],
            'name_OQCRemarks' => ['max:255']

        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{

            DB::beginTransaction();

            if ( $request->name_OutputQty == $request->name_LotQty ){

                    try{
                    OQCLotApp::insert([
                        'fkid_runcard' => $request->hidden_runcard_id,
                        'po_no' => $request->name_po_no,
                        'status' => 2,
                        'submission' => $request->hidden_sub,
                        'device_cat' => $request->name_select_Device,
                        'cert_lot' => $request->name_CertLot,
                        'assy_line' => $request->name_AssyLine,
                        'lot_batch_no' => $request->name_LotBatch,
                        'sub_lot_no' => $request->hidden_sub_lot,
                        'FVO_empid' => $request->txt_arr_names_fvo,
                        'reel_lot' => $request->name_ReelNo,
                        'print_lot' => $request->name_PrintLotNo,
                        'lot_qty' => $request->name_LotQty,
                        'output_qty' => $request->name_OutputQty,
                        'direction' => ($request->name_UrgentDirection)?$request->name_UrgentDirection:'N/A',
                        'Adrawing' => $request->name_ADrawing,
                        'Gdrawing' => $request->name_GDrawing,
                        'ttl_reel' => ($request->name_TtlNoReels)?$request->name_TtlNoReels:'N/A',
                        'app_date' => $request->name_AppDate,
                        'app_time' => date('H:i:s'),
                        'guaranteed_lot' => $request->name_GuaranteedLot,
                        'problem' => ($request->name_Problem)?$request->name_Problem:'N/A',
                        'doc_no' => ($request->name_DocNo)?$request->name_DocNo:'N/A',
                        'remarks' => ($request->name_OQCRemarks)?$request->name_OQCRemarks:'N/A',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')

                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]); // equal to lot quantity
                }
                catch(\Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => $e]);
                }

            } else {

                try{
                    OQCLotApp::insert([
                        'fkid_runcard' => $request->hidden_runcard_id,
                        'po_no' => $request->name_po_no,
                        'status' => 0,
                        'submission' => $request->hidden_sub,
                        'device_cat' => $request->name_select_Device,
                        'cert_lot' => $request->name_CertLot,
                        'assy_line' => $request->name_AssyLine,
                        'lot_batch_no' => $request->name_LotBatch,
                        'sub_lot_no' => $request->hidden_sub_lot,
                        'FVO_empid' => $request->txt_arr_names_fvo,
                        'reel_lot' => $request->name_ReelNo,
                        'print_lot' => $request->name_PrintLotNo,
                        'lot_qty' => $request->name_LotQty,
                        'output_qty' => $request->name_OutputQty,
                        'direction' => ($request->name_UrgentDirection)?$request->name_UrgentDirection:'N/A',
                        'Adrawing' => $request->name_ADrawing,
                        'Gdrawing' => $request->name_GDrawing,
                        'ttl_reel' => ($request->name_TtlNoReels)?$request->name_TtlNoReels:'N/A',
                        'app_date' => $request->name_AppDate,
                        'app_time' => date('H:i:s'),
                        'guaranteed_lot' => $request->name_GuaranteedLot,
                        'problem' => ($request->name_Problem)?$request->name_Problem:'N/A',
                        'doc_no' => ($request->name_DocNo)?$request->name_DocNo:'N/A',
                        'remarks' => ($request->name_OQCRemarks)?$request->name_OQCRemarks:'N/A',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')

                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]); // not equal to lot quantity
                }
                catch(\Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => $e]);
                }



                // return response()->json(['result' => "4"]); // not equal to lot qty

            }

        }
    }

    // Get OQC lot app data
    public function get_oqc_lot_app_details(Request $request){
        $oqc_details = oqcLotApp::with(['user_details'])->where('id',$request['id'])->get();
        return $oqc_details;
    }

    // Update approved Prodn
    public function update_approved_prod(Request $request){
        date_default_timezone_set('Asia/Manila');

        // $user_exist = User::where('id', $request->name_prodn_supv_id)->get();
        $user_exist = User::where('employee_id', $request->name_prodn_supv_id)->get();
        if($user_exist->count() == 0){
            return response()->json(['result' => "2"]); //invalid employee no.
        }

        //update
        DB::beginTransaction();
        try {
            oqcLotApp::where('id',$request->hidden_OQCLotApp_id_query)
                ->update(
                    [
                        'prodn_supervisor'=>$user_exist[0]->id,
                        'status'=>1,
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ]
                );
            DB::commit();
            return response()->json(['result' => '1']);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }

        return $result;
    }

    // Update approved OQC
    public function update_approved_oqc(Request $request){
        date_default_timezone_set('Asia/Manila');

        // $user_exist = User::where('id', $request->name_oqc_supv_id)->get();
        $user_exist = User::where('employee_id', $request->name_oqc_supv_id)->get();
        if($user_exist->count() == 0){
            return response()->json(['result' => "2"]); //invalid employee no.
        }

        //update
        DB::beginTransaction();
        try {
            oqcLotApp::where('id',$request->hidden_OQCLotApp_id_query_oqc)
                ->update(
                    [
                        'oqc_supervisor'=>$user_exist[0]->id,
                        'status'=>2,
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ]
                );
            DB::commit();
            return response()->json(['result' => '1']);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }

        return $result;
    }

    //EXPORT EXCEL
    public function export_parts_lot_record(Request $request){

        $myStartDate    = $request->start_date;
        $myEndDate      = $request->end_date;
        $device_name    = $request->series_name;

        $partsLot_records = ProductionRuncard::with([
            'prod_runcard_material_list',
            'prod_runcard_material_list.wbs_material_kitting' => function($query){
                $query->distinct('item_desc');
            },
            'prod_runcard_material_list.wbs_sakidashi_issuance',
            'prod_runcard_material_list.wbs_sakidashi_issuance.tbl_wbs_sakidashi_issuance_item',
            'prod_runcard_material_list.wbs_material_kitting.parts_prep',
            'prod_runcard_material_list.wbs_material_kitting.parts_prep.user_details',
            'wbs_kitting',
            'wbs_kitting.device_info' => function($query)use($myStartDate,$myEndDate,$request){
                $query->where('name', $request->series_name);
            }
        ])
        ->whereDate('updated_at', '>=', $myStartDate)
        ->whereDate('updated_at', '<=', $myEndDate)
        ->orderBy('updated_at', 'ASC')
        ->get();

        return Excel::download(new PartsLotReportExport($partsLot_records, $device_name), $request->series_name . ' - PATS generated.xlsx');

        if(count($partsLot_records) > 0)
        {
            return view('exports.partslot')->with(compact('partsLot_records','device_name'));
        }
        else
        {
            echo "<script>";
            echo "alert('No data found for " . $device_name . " from " . $request->start_date . " to " . $request->end_date."');";
            echo "window.close();";
            echo "</script>";
        }

    }

}
