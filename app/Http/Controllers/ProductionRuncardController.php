<?php

namespace App\Http\Controllers;

use Auth;
use QrCode;
use App\User;
use DataTables;
use Carbon\Carbon;
use App\Model\Device;
use App\Model\Machine;
use App\Model\Station;
use App\Model\BPilotRun;
use App\Model\oqcLotApp;
use App\Model\PartsPrep;
use App\Model\SubStation;
use App\Model\ProcessFlow;
use App\Model\SampleTable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\WBSKitIssuance;
use App\Model\MaterialProcess;
use App\Model\RapidActiveDocs;
use App\Model\MaterialIssuance;
use App\Model\ProductionRuncard;
use App\Model\StationSubStation;
use Illuminate\Support\Facades\DB;
use App\Model\WBSSakidashiIssuance;
use App\Model\DefectEscalationV2Rev;
use Illuminate\Support\Facades\Cache;
use App\Model\MaterialIssuanceDetails;
use App\Model\ProdRuncardMaterialList;
use App\Model\ProductionRuncardStation;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\DeviceProcessFlowMaterial;

// use App\Model\DefectEscalation;
use App\Model\ProductionRuncardMaterial;

use Illuminate\Support\Facades\Validator;
use App\Model\ProductionRuncardStationMOD;
use App\Model\ProductionRuncardStationMachine;
use App\Model\ProductionRuncardStationMaterial;
use App\Model\ProductionRuncardStationOperator;
use App\Model\MaterialIssuanceSubSystem;//tbl_wbs_material_kitting

class ProductionRuncardController extends Controller
{

    public function checkAllProcessIsDone(Request $request)
    { //ggmodify
        $runcards = ProductionRuncardStation::where("production_runcard_id", $request->id)->get();
        $device = CommonController::getInstance()->getMatrixByPoNo(['po_no' => '', 'name' => $request->deviceName, 'barcode' => '', 'get_or_first' => '']);
        // $device = Device::where("name", $request->deviceName)->get();
        $is_final_visual_exist = ProductionRuncardStation::with('process_flow.processFlow')->where("production_runcard_id", $request->id)->orderBy('id',"DESC")->limit(1)->get();
        $processFlow = [];
        if( count($device) > 0 ) {
            $processFlow = ProcessFlow::where("device_id", $device[0]->id)->where("status", 1)->get();
        //     if( count($processFlow) > 0 ) {
        //         $processFlow = ProcessFlow::where("device_id", $processFlow[0]->device_id)->where("status", 1)->get();
        //     }
        }
        $isComplete = false;
        $processFlowCountDone = 0;
        for ($i=0; $i < count($processFlow); $i++) {
            for ($x=0; $x < count($runcards); $x++) {
                if( $processFlow[$i]->id == $runcards[$x]->process_flow_id )
                    $processFlowCountDone++;
            }
        }
        if( $processFlowCountDone == count($processFlow) && count($processFlow) > 0 )
            $isComplete = true;

        return response()->json([
            // 'runcards' => $runcards,
            'isFinalVisualExist' => (in_array($is_final_visual_exist[0]->process_flow['process'], [20,76,85,87,102,90])), // 08202024 - Add condition if Final Visual is true and txt_from_prod_runcard_num is N/A, it means NO REWORK/NG
            // 'processFlow' => $processFlow,
            'isComplete' => $isComplete,
        ]);
    }


    /**
     * Orig code 09-21-2022
     * -JD
     */
    // public function checkPITSPilotRunByPONo(Request $request)
    // {
    //     $havePTIS = false;
    //     $pilotRunB = BPilotRun::where('po_no', $request->po_no)->get();
    //     return $pilotRunB;
    //     for ($i=0; $i < count($pilotRunB); $i++) {
    //         if( $pilotRunB[$i]->ptis_ctlno != null && trim($pilotRunB[$i]->ptis_ctlno) != "" )
    //             $havePTIS = true;
    //     }
    //     $runcardCount = count( ProductionRuncard::where('po_no', $request->po_no)->get() );
    //     $isAbleToAdd = false;
    //     if( $runcardCount == 0 )
    //         $isAbleToAdd = true;
    //     else if ( $runcardCount >= 1 && $havePTIS )
    //         $isAbleToAdd = true;


    //     return response()->json([
    //         'havePTIS' => $havePTIS,
    //         'runcardCount' => $runcardCount,
    //         'isAbleToAdd' => $isAbleToAdd,
    //     ]);
    // }

    /* Nessa 0923 */
    // public function checkPITSPilotRunByPONo(Request $request)
    // {
    //     $havePTIS = false;
    //     $pilotRunB = BPilotRun::where('po_no', $request->po_no)->get();
    //     for ($i=0; $i < count($pilotRunB); $i++) {

    //         if( $pilotRunB[$i]->term_length == 1 && $pilotRunB[$i]->measurement_result == 1 && $pilotRunB[$i]->vir == 1 ){
    //             $havePTIS = true;
    //         }else{
    //             if($pilotRunB[$i]->feedback_report != null){
    //                 $havePTIS = true;
    //             }else{
    //                 $havePTIS = false;
    //             }
    //         }
    //     }

    //     $runcardCount = count( ProductionRuncard::where('po_no', $request->po_no)->get() );
    //     $isAbleToAdd = false;
    //     if( $runcardCount == 0 )
    //         $isAbleToAdd = true;
    //     else if ( $runcardCount >= 1 && $havePTIS )
    //         $isAbleToAdd = true;


    //     return response()->json([
    //         'havePTIS' => $havePTIS,
    //         'runcardCount' => $runcardCount,
    //         'isAbleToAdd' => $isAbleToAdd,
    //     ]);
    // }

    public function checkPITSPilotRunByPONo(Request $request)
    {
        $havePTIS = false;
        $pilotRunB = BPilotRun::where('po_no', $request->po_no)->get();
        for ($i=0; $i < count($pilotRunB); $i++) {

            if( $pilotRunB[$i]->term_length == 1 && $pilotRunB[$i]->measurement_result == 1 && $pilotRunB[$i]->vir == 1 ){
                $havePTIS = true;
            }else{
                if($pilotRunB[$i]->feedback_report != null){
                    $havePTIS = true;
                }else{
                    $havePTIS = false;
                }
            }
        }

        $runcardCount = count( ProductionRuncard::where('po_no', $request->po_no)->get() );
        $isAbleToAdd = false;
        if( $runcardCount == 0 )
            $isAbleToAdd = true;
        // else if ( $runcardCount >= 1 && $havePTIS )
        else if ( $runcardCount >= 1 ) // nessa
            $isAbleToAdd = true;


        return response()->json([
            'havePTIS' => $havePTIS,
            'runcardCount' => $runcardCount,
            'isAbleToAdd' => $isAbleToAdd,
        ]);
    }

    public function get_mat_kit_issuance_by_lot_no(Request $request){
        $lot_numbers = WBSKitIssuance::where('po', $request->po)->where('lot_no', $request->lot_no)->get();

        // return response()->json(['data' => $lot_numbers]);

        return DataTables::of($lot_numbers)
        ->make(true);
    }

    public function fn_view_materials(Request $request){
        date_default_timezone_set('Asia/Manila');
        // $lot_numbers = MaterialIssuanceSubSystem::all();
        $lot_numbers = [];

        $runcard_status = "";

        $po_no = $request->po_number;
        $chkDisable = false;

        $prod_runcard_mat_list = [];
        $selected_issuances = [];

        $selected_material_kit_list = [];
        $selected_sakidashi_list = [];

        // return $request->all();

        if(isset($request->material_kit_list) && count($request->material_kit_list) > 0){
            $selected_material_kit_list = $request->material_kit_list;
        }

        if(isset($request->sakidashi_list) && count($request->sakidashi_list) > 0){
            $selected_sakidashi_list = $request->sakidashi_list;
        }

        $selected_issuances = array_merge($selected_material_kit_list, $selected_sakidashi_list);

        if(isset($request->prod_runcard_id_query)){
            // return $request->prod_runcard_id_query;

            $prod_runcard_mat_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
                    // ->where('tbl_wbs', 1)
                    ->where('status', 1)
                    ->get();

            // return $prod_runcard_mat_list;
            // $selected_issuances = array_merge($request->sakidashi_list, $request->material_kit_list);

        }
        // return $request->prod_runcard_id_query;

        if(count($prod_runcard_mat_list) > 0){
        // if($request->runcard_status >= 2){
            // $runcard_status = $request->runcard_status;
            $lot_numbers = WBSKitIssuance::with([
                        'kit_issuance',
                        'kit_issuance.device_info',
                        'kit_issuance.device_info.material_process',
                        'kit_issuance.device_info.material_process.station_sub_station',
                        'kit_issuance.device_info.material_process.station_sub_station.station',
                        'parts_prep_info' => function($query){
                            $query->where('wbs_table', 1);
                            $query->where('deleted_at', null);
                        },
                        'prod_runcard_material_list' => function($query){
                            $query->where('status', 1);
                            $query->where('tbl_wbs', 1);
                        },
                        'prod_runcard_material_list.prod_runcard_details',
                        'prod_runcard_material_list.prod_runcard_details.prod_runcard_station_many_details' => function($query){
                            $query->orderBy('step_num', 'desc');
                            // $query->first();
                            // $query->limit(1);
                        }
                    ])
                ->where('po','=',$request['po_number']);

                // if($runcard_status == 1){
                    // $lot_numbers->whereIn('id', $request->material_kit_list);
                // }

                // if(isset($selected_issuances) && count($selected_issuances) > 0){
                    $lot_numbers->whereIn('id', $selected_material_kit_list);
                    $chkDisable = true;
                // }

                $lot_numbers->get();

                $chkDisable = true;
        }
        else{
            $lot_numbers = WBSKitIssuance::with([
                        'kit_issuance',
                        'parts_prep_info' => function($query){
                            $query->where('wbs_table', 1);
                            $query->where('deleted_at', null);
                        },
                        'prod_runcard_material_list' => function($query){
                            $query->where('status', 1);
                            $query->where('tbl_wbs', 1);
                        },
                        'prod_runcard_material_list.prod_runcard_details',
                        'prod_runcard_material_list.prod_runcard_details.prod_runcard_station_many_details' => function($query){
                                $query->orderBy('step_num', 'desc');
                                // $query->first();
                                // $query->limit(1);
                        }
                    ])
                ->where('po','=',$request['po_number'])
                // Removed temporarily
                // ->where('lot_no', $request->lot_no)
                ->where('issue_no', $request->transfer_slip)
                ->get();
        }
        return DataTables::of($lot_numbers)
            ->addColumn('raw_action', function($lot_number) use ($runcard_status, $chkDisable){
                $disabled = '';
                $result = "";
                $title = "";

                // $parts_preps = PartsPrep::where([
                //         [
                //             'wbs_kit_issuance_id','=',
                //             $lot_number->id
                //         ],
                //         [
                //             'wbs_table','=',
                //             1
                //         ],
                //         [
                //             'deleted_at','=',
                //             null
                //         ],
                //     ])
                //     ->get();

                if ( $lot_number->parts_prep_info != null ) {
                    if( $lot_number->parts_prep_info->with_partsprep == 1){
                        if( $lot_number->parts_prep_info->status == 5 || $lot_number->parts_prep_info->status == 7 ){ //Not approved in parts prep
                            $disabled = '';
                        }
                        else{
                            $disabled = 'disabled';
                        }
                    }
                    else{
                        $disabled = '';
                    }
                }
                else{
                    $disabled = 'disabled';
                }

                if($runcard_status == 1){
                    $disabled = 'disabled';
                }

                if($chkDisable){
                    $disabled = 'disabled';
                }

                $result='<center><input material-kit-issue-id="' . $lot_number->id . '" type="checkbox" '. $disabled .' title="Check to select" class="py-0 chkSelMatKitIssue" style="display: block;"></center>';

                $result.=' <input type="hidden" class="col_material_id" value="'.$lot_number->id.'">';
                $result.=' <input type="hidden" class="col_material_code" value="'.$lot_number->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$lot_number['kit_issuance']->device_code.'">';

                // if($disabled == ''){
                //     $result.=' <input type="text" class="col_lot_id" value="'.$lot_number->id.'">';
                // }

                return $result;
            })
            ->addColumn('raw_status', function($lot_number){
                $disabled = 'disabled';
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
                $title = "";
                $parts_prep_done = false;

                if ( $lot_number->parts_prep_info != null ) {
                    if( $lot_number->parts_prep_info->with_partsprep == 1){
                        switch ( $lot_number->parts_prep_info->status ) {
                            case 1:
                                $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 2:
                                $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
                                break;
                            case 3:
                                $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 4:
                                $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 5:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;
                            case 6:
                                $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 7:
                                // $result = '<i title="Parts Prep. Done verification" class="fa fa-check text-success px-2"></i>';
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                    else{
                        $result = '<i title="Ready for Prod\'n" class="fas fa-cogs text-success px-2"></i>';
                        $parts_prep_done = true;
                    }

                    // if( $parts_preps[0]->status == 5 || $parts_preps[0]->status == 7 ){ //approved in parts prep
                    //     $disabled = '';
                    // }
                }
                else{
                    $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
                }

                // $result='<button '.$disabled.' style="width:30px;" title="Open details" class="btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                // $result='<button style="width:30px;" title="Open details" class="btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                $result.=' <input type="hidden" class="col_material_id" value="'.$lot_number->id.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$lot_number->device_code.'">';

                if($parts_prep_done == true){
                    $result.=' <input type="hidden" class="col_lot_id" value="'.$lot_number->id.'">';
                }

                return $result;
            })
            // ->addColumn('runcard_used_qty', function($lot_number){
            //     $result = 0;
            //     if(count($lot_number->prod_runcard_material_list) > 0){
            //         for($index = 0; $index < count($lot_number->prod_runcard_material_list); $index++){
            //             if($lot_number->prod_runcard_material_list[$index]->prod_runcard_details != null){
            //                 if(count($lot_number->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details)){
            //                     $result += $lot_number->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details[0]->qty_output;
            //                 }
            //             }
            //         }
            //     }
            //     return $result;
            // })
            // ->addColumn('lot_qty_to_complete', function($lot_number){
            //     $result = 0;
            //     if(count($lot_number->prod_runcard_material_list) > 0){
            //         // for($index = 0; $index < count($lot_number->prod_runcard_material_list); $index++){
            //         //     if($lot_number->prod_runcard_material_list[$index]->prod_runcard_details != null){
            //         //
            //         //     }
            //         // }
            //         if($lot_number->prod_runcard_material_list[0]->prod_runcard_details != null){
            //             $result = $lot_number->prod_runcard_material_list[0]->prod_runcard_details->lot_qty;
            //         }
            //     }
            //     return $result;
            // })
            ->rawColumns(['raw_action', 'raw_status'])
            ->make(true);
    }

 //    public function fn_view_materials(Request $request){
    // date_default_timezone_set('Asia/Manila');
    //  // $lot_numbers = MaterialIssuanceSubSystem::all();
    //  $lot_numbers = WBSKitIssuance::
 //            with([
 //                'kit_issuance',
 //                'kit_issuance.material_issuance_info',
 //                'parts_prep'
 //            ])
 //            ->where('po','=',$request['po_number'])
    //      ->get();
    //      // ->where('issue_no','=',$request['issue_no'])->get();

    //  // $lot_numbers = WBSKitIssuance::all();
 //        return DataTables::of($lot_numbers)
 //            ->addColumn('raw_action', function($lot_number){
 //                $disabled = '';
 //                $title = '';

 //                if($lot_number->parts_prep != null){
 //                    if($lot_number->parts_prep->checked_by_qc != null || $lot_number->parts_prep->checked_by_qc != "") {
 //                        $disabled = "";
 //                    }
 //                    else{
 //                        // $disabled = 'disabled';
 //                    }
 //                }
 //                else{
 //                    // $disabled = 'disabled';
 //                }
 //                // return $disabled;

 //                $result='<button '.$disabled.' style="width:30px;" title="'.$title.'" class="btn_material_action btn_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
 //                $result.=' <input type="hidden" class="col_material_id" value="'.$lot_number->id.'">';
 //                $result.=' <input type="hidden" class="col_material_code" value="'.$lot_number->item.'">';
 //                $result.=' <input type="hidden" class="col_device_code" value="'.$lot_number['kit_issuance']->device_code.'">';

 //                return $result;
 //            })
 //            ->addColumn('raw_status', function($lot_number){
 //                $proceed = false;
 //                // $result = '<i title="Material Issuance - Pending" class="far fa-clock text-secondary px-2"></i>';
 //                $result = 'Not Ok';

 //                if($lot_number->kit_issuance->material_issuance_info != null) {
 //                    $proceed = true;
 //                }
 //                else{
 //                    $proceed = false;
 //                }

 //                if($proceed){
 //                    $result = 'Ok';
 //                }

 //                return $result;
 //            })
 //            ->rawColumns(['raw_action', 'raw_status'])
 //            ->make(true);
    // }

    public function fn_view_setup_stations(Request $request){
        // date_default_timezone_set('Asia/Manila');
        $prod_runcard_id_query = $request->prod_runcard_id_query;
        $devices = CommonController::getInstance()->getMatrixByPoNo(['po_no' => '', 'name' => '', 'barcode' => $request->device_code, 'get_or_first' => '']);
        // $devices = Device::where('barcode',$request->device_code)->get();

        if(!$devices->count()){
            $devices = array();
            $devices[0]['id'] = 0;
            // return $devices;
        }

        $stations = MaterialProcess::with([
            'station_sub_station' =>
            function($query){
                $query->where('status', 1);
            },
            'station_sub_station.station' =>
            function($query){
                $query->where('status', 1);
            },
            'station_sub_station.sub_station' =>
            function($query){
                $query->where('status', 1);
            },
            ])
            ->where('status', 1)
            ->where('device_id', $devices[0]['id'])
            ->whereHas('station_sub_station.station',
                function($query){
                    $query->where('status', 1);
                    $query->where('station_type', 2);
                },
            )
            ->get();

        return DataTables::of($stations)
            ->addColumn('raw_action', function($station) use ($prod_runcard_id_query){
                $disabled = '';

                $prod_runcardstation = ProductionRuncardStation::
                    where('production_runcard_id', $prod_runcard_id_query )
                    ->where('station_id', $station['station_sub_station']['station']->id )
                    ->where('sub_station_id', $station['station_sub_station']['sub_station']->id )
                    ->get();

                if ( $prod_runcardstation->count() ) {
                    $disabled = 'disabled';
                }

                // $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_station btn btn-info btn-sm py-0"><i class="fa fa-edit"></i></button>';
                $result='<input type="checkbox" '.$disabled.' class="ckb_station">';
                $result.='<input type="hidden" class="col_station_step" value="'.$station->step.'">';
                $result.='<input type="hidden" class="col_station_id" value="'.$station['station_sub_station']['station']->id.'">';
                $result.='<input type="hidden" class="col_sub_station_id" value="'.$station['station_sub_station']['sub_station']->id.'">';
                return $result;
            })
            ->addColumn('raw_status', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_date', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_operator', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_input', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_output', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_ng_qty', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_mod', function($station){
                $result = "---";
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
    }

    public function view_prod_runcard_stations(Request $request){
        date_default_timezone_set('Asia/Manila');
        // $has_emboss = $request->has_emboss;
        $prod_runcard_no = "";
        $prod_runcard_status = "";
        if(isset($request->prod_runcard_status)){
            $prod_runcard_status = $request->prod_runcard_status;
        }
        if(isset($request->prod_runcard_no)){
            $prod_runcard_no = $request->prod_runcard_no;
        }

        $prod_runcardstations = ProductionRuncardStation::with([ //ggmodify
                'station' => function($query){
                    $query->where('status', 1);
                },
                'sub_station' => function($query){
                    $query->where('status', 1);
                },
                // 'operator_info',
                'machine_info',
                // 'machine_details',
                'process_flow',
                'process_flow.processFlow',
                'prod_runcard_station_machine_details',
                'prod_runcard_station_machine_details.machine_info',
                'prod_runcard_station_operator_details',
                'prod_runcard_station_operator_details.operator_info',
                // 'prod_runcard_station_defect', // mdr
                // 'material_process_info.machine_info'
                'prod_runcard_station_mod_details' => function($query) use($request){
                    $query->where('status', 1);
                },
                'prod_runcard_station_mod_details.mod_details',
                'prod_runcard_station_mod_details.mod_details_list',
                'prod_runcard_station_material_details', // Uncomment -JD
            ])
            ->where('status', 1)
            ->where('production_runcard_id', $request->prod_runcard_id_query )
            ->get();

        return DataTables::of($prod_runcardstations)
            ->setRowId('id')
            // ->addColumn('process_name', function($row){
            //     if( !isset($row->process_flow->process_flow->name) )
            //         return 'Name Not Found';

            //     return $row->process_flow->process_flow->name;
            // })
            ->addColumn('raw_action', function($prod_runcardstation) use ($prod_runcard_status, $prod_runcard_no, $request){
                $result = "";
                $result .= "<center>";
                $disabled = '';
                $prod_runcard_id = $prod_runcardstation->production_runcard_id;

                // if(count($prod_runcardstation->prod_runcard_station_operator_details) > 0){
                    $disabled = '';
                    // if($has_emboss == 1){
                        // $disabled = 'disabled';

                        // if($prod_runcardstation->has_emboss == 0){
                        //     $disabled = 'disabled';
                        // }
                        // else{

                            if($prod_runcard_status == 3){
                                // Add Validation for Emboss

                                // $disabled = '';
                                // if($prod_runcardstation->require_oqc_before_emboss == 1){
                                //     $oqc_lot_app = oqcLotApp::with([
                                //                                 'oqcvir_details_for_emboss' => function($query){
                                //                                     // $query->orderBy('submission','desc');
                                //                                     $query->where('judgement', 1);
                                //                                     // $query->limit(1);
                                //                                 },
                                //                             ])
                                //                             ->where('lot_batch_no', $prod_runcard_no)
                                //                             ->get();
                                //     // return $oqc_lot_app;
                                //     if(count($oqc_lot_app) > 0){
                                //         if($oqc_lot_app[0]->oqcvir_details_for_emboss != null){
                                //             $disabled = '';

                                //             if($prod_runcardstation->status == 1){
                                //                 $disabled = 'disabled';
                                //             }
                                //         }
                                //         else{
                                //             $disabled = 'disabled';
                                //         }
                                //     }
                                //     else{
                                //         $disabled = 'disabled';
                                //     }
                                // }
                                // else{
                                //     $disabled = '';
                                // }
                                $disabled = 'disabled';
                            }
                            else{
                                $disabled = '';
                            }
                                // return $prod_runcard_no;
                                // return $oqc_lot_app;

                        // }
                        // $oqc_lot_app = oqcLotApp::with([
                        //     'oqcvir_details' => function($query){
                        //         // $query->orderBy('submission','desc');
                        //     },
                        //     'production_runcard_info' => function($query) use ($prod_runcard_id){
                        //         $query->where('status', '7');
                        //         $query->where('id', $prod_runcard_id);
                        //     }])
                        //     // ->where('production_runcard_id', $prod_runcard_id)
                        //     ->orderBy('submission','desc')
                        //     ->get();

                        // return $oqc_lot_app;

                        // $oqc_vir_result = ProductionRuncard::with([
                        //                                 'oqc_details',
                        //                                 'oqc_details.oqcvir_details'
                        //                         ])
                        //                     // ->where('id', 1)
                        //                     ->where('id', $prod_runcardstation->production_runcard_id)
                        //                     ->get();

                        // return $oqc_vir_result;

                    // }
                    // else{
                        // if($prod_runcardstation->has_emboss == 1){
                        //     if($has_emboss == 1){
                        //         if($prod_runcardstation->status == 1){
                        //             $disabled = 'disabled';
                        //         }
                        //         else if($prod_runcardstation->status == 0){
                        //             $disabled = '';
                        //         }
                        //     }
                        //     else{
                        //         $disabled = 'disabled';
                        //     }
                        // }
                        // else{
                            // if($prod_runcard_status == 2){
                            //     if($prod_runcardstation->status == 1){
                            //         $disabled = 'disabled';
                            //     }
                            // }
                            // else{
                            //     if($prod_runcardstation->status == 1){
                            //         $disabled = 'disabled';
                            //     }
                            //     else{
                            //         $disabled = '';
                            //     }
                            // }
                        // }
                    // }
                // }
                // else{
                //     $disabled = 'disabled';
                // }

                // $disabled = '';

                // if($prod_runcardstation->status != 0){
                //     $disabled = 'disabled';
                // }

                $disabled = 'disabled';
                // if(Auth::user()->position == 1 || Auth::user()->position == 4 || Auth::user()->position == 12){
                if(Auth::user()->position == 1 || Auth::user()->position == 2 || Auth::user()->position == 12 ){
                    $disabled = '';
                }

                if ($prod_runcardstation->process_flow->step == 1){
                    $disabled = '';
                }




                $result='<button '.$disabled.' style="width:30px; margin-top: 3px;" title="Edit" class="btn_material_action btn_edit_prod_runcard_station btn btn-info btn-sm py-0" mat-proc-id="' . $prod_runcardstation->mat_proc_id . '"><i class="fa fa-edit"></i></button>';
                $result.='<input type="hidden" class="col_prod_runcard_station_id" value="'.$prod_runcardstation->id.'">';


                /**
                 * For ISS delete only
                 * dahil tosa katangahan ni maricris
                 * 07-07-2023
                 * -JD
                 */
                if(Auth::user()->position == 1 || Auth::user()->position == 2 || Auth::user()->position == 12){
                    $result.='<button style="width:30px; margin-top: 3px;" title="Delete" class="btn_delete_prod_runcard_station btn btn-danger btn-sm py-0" prod-runcard-station-id="' . $prod_runcardstation->id . '"><i class="fa fa-times"></i></button>';
                }

                /**
                 * For Defect Escalation
                 * 06/17/25
                 * -mdr
                 */
                if($request->page_source != 'prod_runcard_manual_live'){
                    // return Auth::user();
                    if($prod_runcardstation->qty_ng != 0){
                        if(Auth::user()->username == 'bgafp'){
                            $disabled ='';
                            $result .='<button '.$disabled.' style="width:30px; margin-top: 3px;" title="Go to Defect" class=" btn_go_to_defect_escalation btn btn-warning btn-sm py-0"><i class="fa fa-arrow-right"></i></button>';
                        }else{
                            $result .='<button '.$disabled.' style="width:30px; margin-top: 3px;" title="Go to Defect" class=" btn_go_to_defect_escalation btn btn-warning btn-sm py-0"><i class="fa fa-arrow-right"></i></button>';
                        }
                    }
                }


                //03012023
                if ($prod_runcardstation->process_flow->process == 7 || $prod_runcardstation->process_flow->process == 28 || $prod_runcardstation->process_flow->process == 32 || $prod_runcardstation->process_flow->process == 38){
                    $disabled = '';
                    $result.=' <button style="width:30px; margin-top: 3px;" title="Print Auto Sticker" class=" btn_print_auto_sticker btn btn-warning btn-sm py-0" runcard-station-id="' . $prod_runcardstation->id.'"><i class="fa fa-print"></i></button>';
                }

                // $result.=' <button '.$disabled.' style="width:30px; margin-top: 3px;" title="Delete" class=" btn_scan_delete_process btn btn-danger btn-sm py-0" mat-proc-id="' . $prod_runcardstation->mat_proc_id . '" runcard-station-id="'.$prod_runcardstation->id.'"><i class="fa fa-times"></i></button>';




                $result.='<input type="hidden" class="col_prod_runcard_station_id" value="'.$prod_runcardstation->id.'">';

                $result .= "</center>";

                return $result;
            })
            ->addColumn('operators_info', function($prod_runcardstation){
                $operatorsName = "";

                if($prod_runcardstation->operator != null){
                    $operators = explode(",", $prod_runcardstation->operator);
                    $users = User::whereIn('id', $operators)->get();

                    // $operatorsName = implode(", ", $users->pluck('name'));
                    // $operatorsName = implode(", ", $users->pluck('name'));
                    $operatorsName = implode(" / ", $users->pluck('name')->toArray());
                }
                return $operatorsName;
            })
            ->addColumn('txt_qty_ng', function($prod_runcardstation){
                $result = '';
                if($prod_runcardstation->status == 0){
                }
                else{
                    $result = $prod_runcardstation->qty_ng;
                }
                    $result = '<center><input type="text" class="form-control form-control-sm txtEditProcessQtyNG" name="txt_qty_ng" style="max-width: 50px; margin: 1px 1px; padding: 1px 1px;"></center>';
                return $result;
            })
            ->addColumn('machines_info', function($prod_runcardstation){
                $machinesName = null;

                if($prod_runcardstation->machines != null){
                    $machines = explode(",", $prod_runcardstation->machines);
                    $machines = Machine::whereIn('id', $machines)->get();
                    $machinesName = implode(" / ", $machines->pluck('name')->toArray());
                }else{
                    $machinesName = 'N/A';
                }
                return $machinesName;
            })
            ->addColumn('material_name', function($prod_runcardstation){
                return '<span class="badge badge-pill badge-secondary">' . $prod_runcardstation['process_flow']['material_name'] . '</span>';
            })
            // ->addColumn('defect_count', function($prod_runcardstation){
            //     $result = '';

            // })
            ->rawColumns(['raw_action', 'operators_info', 'machines_info', 'txt_qty_ng', 'material_name'])
            ->make(true);
    }

    public function fn_view_prod_runcard_stations(Request $request){
        date_default_timezone_set('Asia/Manila');
        // $has_emboss = $request->has_emboss;
        $prod_runcard_no = "";
        $prod_runcard_status = "";
        if(isset($request->prod_runcard_status)){
            $prod_runcard_status = $request->prod_runcard_status;
        }
        if(isset($request->prod_runcard_no)){
            $prod_runcard_no = $request->prod_runcard_no;
        }

        $prod_runcardstations = ProductionRuncardStation::with([
                'station' => function($query){
                    $query->where('status', 1);
                },
                'sub_station' => function($query){
                    $query->where('status', 1);
                },
                // 'operator_info',
                'machine_info',
                // 'material_process_info',
                // 'material_process_info.machine_info'
                'prod_runcard_station_mod_details' => function($query) use($request){
                    $query->where('status', 1);
                },
                'prod_runcard_station_mod_details.mod_details',
            ])
            ->where('production_runcard_id', $request->prod_runcard_id_query )
            ->get();

        return DataTables::of($prod_runcardstations)
            ->setRowId('id')
            ->addColumn('raw_action', function($prod_runcardstation) use ($prod_runcard_status, $prod_runcard_no){
                $disabled = '';
                $prod_runcard_id = $prod_runcardstation->production_runcard_id;

                $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_prod_runcard_station btn btn-info btn-sm py-0" mat-proc-id="' . $prod_runcardstation->mat_proc_id . '"><i class="fa fa-edit"></i></button>';
                $result.='<input type="hidden" class="col_prod_runcard_station_id" value="'.$prod_runcardstation->id.'">';
                return $result;
            })
            ->addColumn('operators_info', function($prod_runcardstation){
                $operatorsName = "";

                if($prod_runcardstation->operator != null){
                    $operators = explode(",", $prod_runcardstation->operator);
                    $users = User::whereIn('id', $operators)->get();

                    // $operatorsName = implode(", ", $users->pluck('name'));
                    // $operatorsName = implode(", ", $users->pluck('name'));
                    $operatorsName = implode(" / ", $users->pluck('name')->toArray());
                }
                return $operatorsName;
            })
            ->addColumn('txt_qty_ng', function($prod_runcardstation){
                $result = '';
                if($prod_runcardstation->status == 0){
                }
                else{
                    $result = $prod_runcardstation->qty_ng;
                }

                    $result = '<center><input type="text" class="form-control form-control-sm txtEditProcessQtyNG" name="txt_qty_ng" style="max-width: 50px; margin: 1px 1px; padding: 1px 1px;"></center>';
                return $result;
            })
            ->addColumn('machines_info', function($prod_runcardstation){
                $machinesName = null;

                if($prod_runcardstation->machines != null){
                    $machines = explode(",", $prod_runcardstation->machines);
                    $machines = Machine::whereIn('id', $machines)->get();
                    $machinesName = implode(" / ", $machines->pluck('name')->toArray());
                }
                return $machinesName;
            })
            ->rawColumns(['raw_action', 'operators_info', 'machines_info', 'txt_qty_ng'])
            ->make(true);
    }

    public function fn_select_prod_runcard_material_details(Request $request){
        date_default_timezone_set('Asia/Manila');

        $lot_number = [];

        if($request->txt_wbs_table == 1){
            $lot_number = WBSKitIssuance::with(['kit_issuance', 'kit_issuance.device_info', 'prod_runcards', 'prod_runcards.supervisor_prod_info', 'prod_runcards.supervisor_qc_info'])->where('id','=',$request['material_id'])
                ->get();
        }
        else{
            $lot_number = WBSSakidashiIssuance::with([
                        'tbl_wbs_sakidashi_issuance_item',
                        'device_info', 'prod_runcards',
                        'prod_runcards.supervisor_prod_info',
                        'prod_runcards.supervisor_qc_info'
                    ])
                    ->where('id','=',$request['material_id'])
                    ->get();
        }

        // if($lot_number->isEmpty()){
        //     $lot_number = array();
        // }
        // else{

        //     $prod_runcards = ProductionRuncard::where([
        //             [
        //                 'wbs_kit_issuance_id','=',
        //                 $lot_number[0]->id
        //             ],
        //             [
        //                 'deleted_at','=',
        //                 null
        //             ]
        //         ])
        //         ->get();
        //         // return $prod_runcards;
        //     if($prod_runcards->isEmpty()){
        //         $lot_number[0]['prod_runcards'] = array();
        //     }
        //     else{

        //         $lot_number[0]['prod_runcards'] = $prod_runcards;
        //     }
        // }
        // return $lot_number;
        return response()->json(['lot_number' => $lot_number, 'txt_wbs_table' => $request->txt_wbs_table]);
    }

    public function fn_insert_prod_runcard(Request $request){
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $data = $request->all();

        $validator = Validator::make($data, [
            // 'txt_prod_runcard_id_query' => ['required'],
            'txt_employee_number_scanner' => ['required'],
            'txt_po_number' => ['required'],
            'txt_lot_qty' => ['required'],
            'txt_assessment_no' => ['required'],
            'txt_a_drawing_no' => ['required'],
            'txt_a_drawing_rev' => ['required'],
            'txt_g_drawing_no' => ['required'],
            'txt_g_drawing_rev' => ['required'],
            'txt_other_docs_no' => ['required'],
            'txt_other_docs_no' => ['required'],
            'txt_other_docs_rev' => ['required'],
        ]);

        $material_process = [];


        if($validator->passes()){

            $device = CommonController::getInstance()->getMatrixByPoNo(['po_no' => $request->txt_po_number, 'name' => '', 'barcode' => $request->txt_device_code, 'get_or_first' => 'first']);
            // $device = Device::where('barcode', $request->txt_device_code)->first();


            if($device != null){
                $material_process = MaterialProcess::with([
                    'station_sub_station' => function($query){
                        $query->where('status', 1);
                    },
                    'station_sub_station.station' => function($query){
                        $query->where('status', 1);
                        $query->where('station_type', 2);
                    },
                    'station_sub_station.sub_station' => function($query){
                        $query->where('status', 1);
                    },
                    'material_details' => function($query){
                        $query->where('status', 1);
                    },
                ])
                ->where('device_id', $device->id)
                ->where('status', 1)
                ->orderBy('step', 'asc')
                // ->having('station_sub_station', '!=', null)
                // ->having('station_sub_station.station', '!=', null)
                ->get();

                // return $material_process;

                if(count($material_process) > 0){
                    // $require_oqc_before_emboss = 0;
                    $collect_material_process = collect($material_process)->flatten(1);
                    // $collect_material_process =  $collect_material_process->where('require_oqc_before_emboss', 1);

                    // if(count($collect_material_process) > 0){
                    //     $require_oqc_before_emboss = 1;
                    // }
                    // return $collect_material_process;
                    // return $collect_material_process->where('require_oqc_before_emboss', 1);

                    $runcard_no = substr($request->txt_po_number, 0, -5);

                    $prod_runcard_id = "";

                    $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

                    if($user->count() > 0){
                        if( isset($request->txt_prod_runcard_id_query) ){
                            //update
                            DB::beginTransaction();
                            try {
                                $prod_runcard_id = $request->txt_prod_runcard_id_query;
                                ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                                    ->update(
                                        [
                                            // 'lot_qty' => $request->txt_lot_qty,
                                            // 'assessment_no'=> $request->txt_assessment_no,
                                            // 'a_drawing_no'=> $request->txt_a_drawing_no,
                                            // 'a_drawing_rev'=> $request->txt_a_drawing_rev,
                                            // 'g_drawing_no'=> $request->txt_g_drawing_no,
                                            // 'g_drawing_rev'=> $request->txt_g_drawing_rev,
                                            'other_docs_no'=> $request->txt_other_docs_no,
                                            'other_docs_rev'=> $request->txt_other_docs_rev,
                                            'last_updated_by' => $user[0]->id,
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ]
                                    );
                                DB::commit();
                                $result = true;

                            } catch (Exception $e) {
                                DB::rollback();
                                $result = false;
                            }
                        }
                        else{
                            //insert
                            DB::beginTransaction();
                            try {
                                // Generate Reel Lot No
                                // $arr_packing_months = [1,2,3,4,5,6,7,8,9,'X','Y','Z'];
                                // $final_reel_lot_no = "";

                                // $last_no_of_the_year = date('Y')[3];
                                // $packing_month = $arr_packing_months[(integer)date('m') - 1];
                                // $packing_date = date('d');
                                // $serial_no = str_pad(1,2,"0", STR_PAD_LEFT);
                                // $lot_no_machine_code = '0';

                                // $device_info = Device::where('name', $request->device_name)->first();

                                // if($device_info != null){
                                //     if($device_info->lot_no_machine_code != null){
                                //         $lot_no_machine_code = $device_info->lot_no_machine_code;
                                //     }
                                // }

                                // $final_reel_lot_no = $last_no_of_the_year . $packing_month . $packing_date . '-'. $serial_no . $lot_no_machine_code;
                                // $explode_reel_lot_no = explode('-', $final_reel_lot_no)[0];

                                // $prod_runcard_reel_lots = [];
                                // $prod_runcard_reel_lots = ProductionRuncard::with([
                                //                                 'wbs_kitting_has_many' => function($query) use ($request){
                                //                                     $query->where('device_name', $request->device_name);
                                //                                 }
                                //                             ])
                                //                             ->where('reel_lot_no', 'LIKE', $explode_reel_lot_no . "%")->orderBy('id', 'desc')
                                //                             ->first();

                                // if($prod_runcard_reel_lots != null){
                                //     if($prod_runcard_reel_lots->wbs_kitting_has_many != null){
                                //         if($prod_runcard_reel_lots != null){
                                //             $exploded_prod_runcard_reel_lot_no = explode('-', $prod_runcard_reel_lots->reel_lot_no);

                                //             $serial_no = (integer)($exploded_prod_runcard_reel_lot_no[1][0] . $exploded_prod_runcard_reel_lot_no[1][1]) + 1;
                                //             $serial_no = str_pad($serial_no, 2, "0", STR_PAD_LEFT);

                                //             $final_reel_lot_no = $exploded_prod_runcard_reel_lot_no[0] . '-' . $serial_no . $lot_no_machine_code;
                                //         }
                                //     }
                                // }

                                $arr_packing_months = [1,2,3,4,5,6,7,8,9,'X','Y','Z'];
                                $final_reel_lot_no = "";

                                $last_no_of_the_year = date('Y')[3];
                                $packing_month = $arr_packing_months[(integer)date('m') - 1];
                                $packing_date = date('d');
                                $serial_no = str_pad(1,2,"0", STR_PAD_LEFT);
                                $lot_no_machine_code = '0';

                                $device_info = CommonController::getInstance()->getMatrixByPoNo(['po_no' => $request->txt_po_number, 'name' => $request->device_name, 'barcode' => '', 'get_or_first' => 'first']);
                                // $device_info = Device::where('name', $request->device_name)->first();

                                if($device_info != null){
                                    if($device_info->lot_no_machine_code != null){
                                        $lot_no_machine_code = $device_info->lot_no_machine_code;
                                    }
                                }

                                $final_reel_lot_no = $last_no_of_the_year . $packing_month . $packing_date . '-'. $serial_no . $lot_no_machine_code;
                                $explode_reel_lot_no = explode('-', $final_reel_lot_no)[0];

                                $prod_runcard_reel_lots = [];
                                $prod_runcard_reel_lots = ProductionRuncard::with([
                                                                'wbs_kitting_has_many' => function($query) use ($request){
                                                                    $query->where('device_name', $request->device_name);
                                                                    $query->orderBy('id', 'desc');
                                                                    $query->limit(1);
                                                                }
                                                            ])
                                                            ->where('po_no', $request->txt_po_number)
                                                            ->where('reel_lot_no', 'LIKE', $explode_reel_lot_no . "%")
                                                            ->orderBy('id', 'desc')
                                                            ->first();

                                // return $prod_runcard_reel_lots;

                                if($prod_runcard_reel_lots != null){
                                    if($prod_runcard_reel_lots->wbs_kitting_has_many != null){
                                        if($prod_runcard_reel_lots != null){
                                            $exploded_prod_runcard_reel_lot_no = explode('-', $prod_runcard_reel_lots->reel_lot_no);

                                            $serial_no = (integer)($exploded_prod_runcard_reel_lot_no[1][0] . $exploded_prod_runcard_reel_lot_no[1][1]) + 1;
                                            $serial_no = str_pad($serial_no, 2, "0", STR_PAD_LEFT);

                                            $final_reel_lot_no = $exploded_prod_runcard_reel_lot_no[0] . '-' . $serial_no . $lot_no_machine_code;

                                            // return $final_reel_lot_no;
                                        }
                                    }
                                }

                                // return $final_reel_lot_no;

                                // Generate Runcard No
                                $prod_runcards = ProductionRuncard::where('runcard_no', 'LIKE', $runcard_no . "%")->orderBy('id', 'desc')->limit(1)->get();

                                $final_runcard_no = "";
                                $explode_runcard_no = "";

                                if($prod_runcards->count() > 0){
                                    $explode_runcard_no = explode('-', $prod_runcards[0]->runcard_no);

                                    $explode_runcard_no[1] = str_pad($explode_runcard_no[1] + 1,3,"0", STR_PAD_LEFT);

                                    $final_runcard_no = implode('-', $explode_runcard_no);
                                }
                                else{
                                    $final_runcard_no = substr($request->txt_po_number, 0, -5) . '-001';
                                }

                                // return $runcard_no;

                                // if(isset($request->has_emboss)){
                                //     $has_emboss = $request->has_emboss;
                                // }
                                // else{
                                //     $has_emboss = 0;
                                // }

                                $prod_runcard_id = ProductionRuncard::insertGetId([
                                    'po_no'=> $request->txt_po_number,
                                    'status'=> 1,
                                    'runcard_no' => $final_runcard_no,
                                    'reel_lot_no' => $final_reel_lot_no,
                                    'lot_qty' => $request->txt_lot_qty,
                                    'assessment_no'=> $request->txt_assessment_no,
                                    'a_drawing_no'=> $request->txt_a_drawing_no,
                                    'a_drawing_rev'=> $request->txt_a_drawing_rev,
                                    'g_drawing_no'=> $request->txt_g_drawing_no,
                                    'g_drawing_rev'=> $request->txt_g_drawing_rev,
                                    'other_docs_no'=> $request->txt_other_docs_no,
                                    'other_docs_no'=> $request->txt_other_docs_no,
                                    'other_docs_rev'=> $request->txt_other_docs_rev,
                                    // 'has_emboss'=> $has_emboss,
                                    // 'require_oqc_before_emboss' => $require_oqc_before_emboss,
                                    'comp_under_runcard_no'=> 0,
                                    'created_by' => $user[0]->id,
                                    'last_updated_by' => $user[0]->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);

                                // for($index = 0; $index < count($material_process); $index++){
                                //     if($material_process[$index]->station_sub_station->station != null){
                                //         if($material_process[$index]->has_emboss == 1){
                                //             $has_emboss = 1;
                                //         }
                                //     }
                                // }

                                for($index = 0; $index < count($material_process); $index++){
                                    if($material_process[$index]->station_sub_station->station != null){
                                        ProductionRuncardStation::insert([
                                            'production_runcard_id'=> $prod_runcard_id,
                                            'mat_proc_id'=> $material_process[$index]->id,
                                            'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                            'sub_station_id'=> $material_process[$index]->station_sub_station->sub_station_id,
                                            'step_num'=> $material_process[$index]->step,
                                            // 'has_emboss'=> $material_process[$index]->has_emboss,
                                            'created_by'=> Auth::user()->id,
                                            'last_updated_by'=> Auth::user()->id
                                        ]);

                                        // if($material_process[$index]->has_emboss == 1){
                                        //     $has_emboss = 1;
                                        // }
                                    }
                                }

                                // for($index = 0; $index < count($request->material_issuance); $index++){
                                //     ProdRuncardMaterialList::insert([
                                //         'prod_runcard_id' => 1,
                                //         'issuance_id' => $request->material_issuance[$index],
                                //         'tbl_wbs' => 1,
                                //         'status' => 1,
                                //     ]);
                                // }

                                // for($index = 0; $index < count($request->sakidashi_issuance); $index++){
                                //     ProdRuncardMaterialList::insert([
                                //         'prod_runcard_id' => 1,
                                //         'issuance_id' => $request->sakidashi_issuance[$index],
                                //         'tbl_wbs' => 2,
                                //         'status' => 1,
                                //     ]);
                                // }

                                DB::commit();

                                // ProductionRuncard::where('id', $prod_runcard_id)
                                //     ->update([
                                //         'has_emboss'=> $has_emboss,
                                //     ]);

                                $result = true;
                            } catch (Exception $e) {
                                DB::rollback();
                                $result = false;
                            }
                        }

                        if( !$result ){
                            $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                            $return_body = 'An error occured. The record not saved.';
                        }
                        $return = array('title'=>$return_title,'body'=>$return_body, 'prod_runcard_id' => $prod_runcard_id, 'result' => $result);
                        return $return;
                    }
                    else{
                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                        $return_body = ' Invalid User.';
                        $return = array('title'=>$return_title,'body'=>$return_body);
                        return $return;
                    }
                }
                else{
                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                    $return_body = ' No Material Process.';
                    $return = array('title'=>$return_title,'body'=>$return_body);
                    return $return;
                }
            }
            else{
                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                $return_body = ' Device not found.';
                $return = array('title'=>$return_title,'body'=>$return_body);
                return $return;
            }

        }
        else{
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = 'Saving Failed!';
            $return = array('title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
            return $return;
        }
    }

    public function fn_update_prod_runcard_secondary(Request $request){
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $discrepant_qty = null;

        if(isset($request->txt_discrepant_qty)){
            $discrepant_qty = $request->txt_discrepant_qty;

            if($request->txt_discrepant_qty_sign == '-'){
                $discrepant_qty = $discrepant_qty * -1;
            }
        }

        $status = 0;

        $data = $request->all();

        $validator = Validator::make($data, [
            // 'txt_discrepant_qty' => ['required'],
            // 'txt_discrepant_qty_sign' => ['required'],
            // 'txt_recount_ok' => ['required'],
            // 'txt_recount_ng' => ['required'],
        ]);

        if($validator->passes()){
            $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

            if($user->count() > 0){
                if($user[0]->position == 4){
                    // if(isset($request->txt_discrepant_qty) && $request->txt_discrepant_qty == 0){
                    //     $status = 1;
                    // }
                    // else{
                    //     $status = 0;
                    // }

                    DB::beginTransaction();
                    try {
                        $status = 4;
                        if($request->txt_recount_ng <= 0){
                            $status = 7;
                        }

                        ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                            ->update(
                                [
                                    // 'status' => $status,
                                    'supervisor_prod' => null,
                                    'supervisor_qc' => null,
                                    'discrepant_qty'=> $discrepant_qty,
                                    'analysis'=> $request->txt_analysis,
                                    'status'=> $status,
                                    'recount_ok'=> $request->txt_recount_ok,
                                    'recount_ng'=> $request->txt_recount_ng,
                                    'comp_under_runcard_no'=> $request->sel_comp_under_runcard_no,
                                    'last_updated_by' => Auth::user()->id,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]
                            );
                        DB::commit();
                        $result = true;

                    } catch (Exception $e) {
                        DB::rollback();
                        $result = false;
                    }

                    if( !$result ){
                        $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                        $return_body = 'An error occured. The record not saved.';
                    }
                    $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                    return $return;
                }
                else{
                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                    $return_body = ' Scanned QR Code is not Operator.';
                    $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                    return $return;
                }
            }
            else{
                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                $return_body = ' Invalid Employee No.';
                $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                return $return;
            }
        }
        else{
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = 'Please complete required fields.';
            $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
            return $return;
        }
    }

    public function fn_update_prod_runcard_approval_prod(Request $request){
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $employee_name = '';
        $result = false;

        $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

        if($user->count() > 0){
            if($user[0]->position == 1){
                if( $request->txt_prod_runcard_id_query ){
                    //update
                    DB::beginTransaction();
                    try {
                        ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                            ->update(
                                [
                                    // 'status'=>$request->0,
                                    'status' => 5,
                                    'supervisor_prod'=> $user[0]->id,
                                    // 'checked_at_prod'=>date('Y-m-d H:i:s'),
                                ]
                            );
                        DB::commit();
                        $result = true;
                        $employee_name = $user[0]->name;
                    } catch (Exception $e) {
                        DB::rollback();
                        $result = false;
                        $employee_name = '';
                    }
                }

                if( !$result ){
                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                    $return_body = 'An error occured. The record not saved.';
                    $employee_name = '';
                }
                $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body, 'employee_name' => $employee_name);
                return $return;
            }
            else{
                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                $return_body = 'For Prod\'n Supervisor Only.';
                $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                $employee_name = '';
                return $return;
            }
        }
        else{
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = ' Invalid Employee No.';
            $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
            $employee_name = '';
            return $return;
            // return '0';
        }
    }

    public function fn_update_prod_runcard_approval_qc(Request $request){
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

        if($user->count() > 0){
            if($user[0]->position == 2){
                if( $request->txt_prod_runcard_id_query ){
                    //update
                    DB::beginTransaction();
                    try {
                        ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                            ->update(
                                [
                                    // 'status'=>$request->0,
                                    'status' => 7,
                                    'status'=> 1,
                                    'supervisor_qc'=> $user[0]->id,
                                    // 'checked_at_prod'=>date('Y-m-d H:i:s'),
                                ]
                            );
                        DB::commit();
                        $result = true;
                    } catch (Exception $e) {
                        DB::rollback();
                        $result = false;
                    }
                }

                if( !$result ){
                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                    $return_body = 'An error occured. The record not saved.';
                }
                $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                return $return;
            }
            else{
                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                $return_body = 'For QC Supervisor Only.';
                $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                $employee_name = '';
                return $return;
            }
        }
        else{
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = ' Invalid Employee No.';
            $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
            return $return;
            // return '0';
        }
    }

    public function fn_insert_setup_stations(Request $request){
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';

        $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

        if($user->count() > 0){
            //insert
            foreach ($request->arr_substations as $key => $value) {
                DB::beginTransaction();
                try {
                    ProductionRuncardStation::insert([
                            'production_runcard_id'=>$request->txt_prod_runcard_id_query,
                            'station_id'=>$value['station'],
                            'sub_station_id'=>$value['substation'],
                            'step_num'=>$value['step'],
                            'created_by'=> Auth::user()->id,
                            'last_updated_by'=> Auth::user()->id
                        ]);
                    DB::commit();
                    $result = true;
                } catch (Exception $e) {
                    DB::rollback();
                    $result = false;
                }
            }
            if( !$result ){
                $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                $return_body = 'An error occured. The record not saved.';
            }
            $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
            return $return;
        }
        else{
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = ' Invalid Employee No.';
            $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
            return $return;
            // return '0';
        }
    }

    public function fn_select_prod_runcard_station_details(Request $request){
        date_default_timezone_set('Asia/Manila');

        $prod_runcard_stations = ProductionRuncardStation::with([
                'station' => function($query){
                    $query->where('status', 1);
                },
                'sub_station' => function($query){
                    $query->where('status', 1);
                },
                // 'operator_info',
                // 'machine_info',
                // 'machine_details',
                'process_flow',
                'process_flow.processFlow',
                'prod_runcard_station_machine_details',
                'prod_runcard_station_machine_details.machine_info',
                'prod_runcard_station_operator_details',
                'prod_runcard_station_operator_details.operator_info',
                // 'material_process_info',
                // 'material_process_info.machine_info'
                'prod_runcard_station_mod_details' => function($query) use($request){
                    $query->where('status', 1);
                },
                'prod_runcard_station_mod_details.mod_details',
                'prod_runcard_station_material_details', // Uncomment -JD
            ])
            // ->where('production_runcard_id', $request->prod_runcard_id_query )
            ->where('id', $request['col_prod_runcard_station_id'])
            ->first();

            $imploded_mod = $prod_runcard_stations['prod_runcard_station_mod_details']->groupBy('mod_index');

            // return $imploded_mod;

        return response()->json(['prod_runcard_stations' => $prod_runcard_stations, 'mode_of_defect' => $imploded_mod]);

        // $prod_runcard_stations = ProductionRuncardStation::
        //     with([
        //         'station',
        //         'sub_station',
        //         'operator_info',
        //     ])
        //     ->where(
        //         [
        //             [
        //                 'deleted_at','=',
        //                 null
        //             ],
        //             [
        //                 'id','=',$request['col_prod_runcard_station_id']
        //             ]
        //         ]
        //     )
        //     ->get();
        // if($prod_runcard_stations->isEmpty()){
        //     $prod_runcard_stations = array();
        // }

        // $device = Device::where('barcode', $request->device_code)->first();

        // $material_process =  MaterialProcess::with([
        //         'manpowers_details' => function($query){
        //             $query->where('status', 1);
        //         },
        //         'machine_details' => function($query){
        //             $query->where('status', 1);
        //         },
        //         'material_details' => function($query){
        //             $query->where('status', 1);
        //         },
        //     ])
        //     ->where('id', $request->material_process_id)
        //     // ->where('status', 1)
        //     ->first();

        // return response()->json(['prod_runcard_stations' => $prod_runcard_stations, 'material_process' => $material_process]);
    }

    public function fn_update_prod_runcard_station_details(Request $request){
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $data = $request->all();

        // //Uncomment this to increment the NG Qty of above stations if there has an input that has not been incremented.
        // if($request->txt_edit_prod_runcard_station_ng > 0){
        //     // Increment all above station input
        //     $step_num = $request->txt_edit_prod_runcard_station_step;

        //     $step_num = explode('-', $step_num)[0];

        //     $prod_runcard_stations = ProductionRuncardStation::orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
        //                             ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'))
        //                             ->where('production_runcard_id', $request->txt_prod_runcard_id_query)
        //                             ->get();

        //     $prod_runcard_station_step_num = [];
        //     $prod_runcard_station_id = [];

        //     if(count($prod_runcard_stations) > 0){
        //         for($index = 0; $index < count($prod_runcard_stations); $index++){
        //             $prod_runcard_station_step_num[] = explode('-', $prod_runcard_stations[$index]->step_num)[0];
        //             $prod_runcard_station_id[] = $prod_runcard_stations[$index]->id;
        //         }
        //     }

        //     $prod_runcard_station_step_num = array_unique($prod_runcard_station_step_num);

        //     $station_unique = [];
        //     $station_unique_key = [];

        //     foreach ($prod_runcard_station_step_num as $prod_runcard_station_step_num_unique => $value) {
        //         if($step_num > $value){
        //             $station_unique_key[] = $prod_runcard_station_step_num_unique;
        //             $station_unique[] = $value;
        //         }
        //     }

        //     $final_station_id = [];

        //     if(count($prod_runcard_station_id) > 0){
        //         for($index = 0; $index < count($prod_runcard_station_id); $index++){
        //             // if(array_search($index, $station_unique_key)){
        //             //     $final_station_id[] = $prod_runcard_station_id[$index];
        //             // }

        //             for($index2 = 0; $index2 < count($station_unique_key); $index2++){
        //                 if($station_unique_key[$index2] == $index){
        //                     $final_station_id[] = $prod_runcard_station_id[$index];
        //                 }
        //             }
        //         }
        //     }

        //     ProductionRuncardStation::whereIn('id', $final_station_id)
        //                                 ->where('qty_output', '!=', null)
        //                                 ->increment('qty_input', $request->txt_edit_prod_runcard_station_ng);

        //     return $final_station_id;
        // }
        // else{
        //     return 'no';
        // }

        // return $data;

        $validator = Validator::make($data, [
            'txt_edit_prod_runcard_station_input' => ['required'],
            'txt_edit_prod_runcard_station_output' => ['required'],
            'txt_edit_prod_runcard_station_ng' => ['required'],
            'txt_edit_prod_runcard_operator' => ['required'],
            'txt_prod_runcard_station_id_query' => ['required'],
            'txt_employee_number_scanner' => ['required'],
            'txt_prod_runcard_id_query' => ['required'],
            // 'txt_edit_prod_runcard_station_materials' => ['required'],
        ]);

        if ($validator->fails()) {
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = 'Please complete required fields.';
            $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
            return $return;
        }
        else{


            $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

            if($user->count() > 0){
                if($user[0]->position == 4){
                    if( $request->txt_prod_runcard_station_id_query ){
                        //update
                        // $data = $request->all();
                        // $validator = Validator::make($data, [
                        //     'txt_edit_prod_runcard_station_input' => ['required'],
                        //     'txt_edit_prod_runcard_station_output' => ['required'],
                        //     'txt_edit_prod_runcard_station_ng' => ['required'],
                        //     'txt_edit_prod_runcard_operator' => ['required'],
                        // ]);

                        $operators = implode(",",$request->txt_edit_prod_runcard_operator);
                        $machines = "";

                        if(isset($request->txt_edit_prod_runcard_station_machine)){
                            $machines = implode(",",$request->txt_edit_prod_runcard_station_machine);
                        }

                        // if($validator->passes()){
                            DB::beginTransaction();
                            try {
                                ProductionRuncardStation::where('id',$request->txt_prod_runcard_station_id_query)
                                    ->update(
                                        [
                                            // 'machine_id' => $request->txt_edit_prod_runcard_station_machine,
                                            // 'qty_input'=>$request->txt_edit_prod_runcard_station_input + $request->txt_edit_prod_runcard_station_ng,
                                            // 'qty_output'=>$request->txt_edit_prod_runcard_station_output + $request->txt_edit_prod_runcard_station_ng,
                                            'qty_input'=>$request->txt_edit_prod_runcard_station_input + $request->txt_edit_prod_runcard_station_ng,
                                            'qty_output'=>$request->txt_edit_prod_runcard_station_output + $request->txt_edit_prod_runcard_station_ng,
                                            'qty_ng'=>$request->txt_edit_prod_runcard_station_ng,
                                            'mod'=>$request->txt_edit_prod_runcard_station_mod,
                                            'operator' => $operators,
                                            'machines' => $machines,
                                            'material' => $request->txt_edit_prod_runcard_station_materials,
                                            'status' => 1,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]
                                    );

                                // Removed temporarily as per Ma'am Berna
                                // if($request->txt_edit_prod_runcard_station_ng > 0){
                                //     ProductionRuncardStation::where('id', '<', $request->txt_prod_runcard_station_id_query)
                                //         ->where('qty_output', '!=', null)
                                //         ->increment('qty_input', $request->txt_edit_prod_runcard_station_ng);
                                // }

                                // if has NG only
                                if($request->txt_edit_prod_runcard_station_ng > 0){
                                    // Increment all above station input
                                    $step_num = $request->txt_edit_prod_runcard_station_step;

                                    $step_num = explode('-', $step_num)[0];

                                    $prod_runcard_stations = ProductionRuncardStation::orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                                                            ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'))
                                                            ->where('production_runcard_id', $request->txt_prod_runcard_id_query)
                                                            ->get();

                                    $prod_runcard_station_step_num = [];
                                    $prod_runcard_station_id = [];

                                    if(count($prod_runcard_stations) > 0){
                                        for($index = 0; $index < count($prod_runcard_stations); $index++){
                                            $prod_runcard_station_step_num[] = explode('-', $prod_runcard_stations[$index]->step_num)[0];
                                            $prod_runcard_station_id[] = $prod_runcard_stations[$index]->id;
                                        }
                                    }

                                    $prod_runcard_station_step_num = array_unique($prod_runcard_station_step_num);

                                    $station_unique = [];
                                    $station_unique_key = [];

                                    foreach ($prod_runcard_station_step_num as $prod_runcard_station_step_num_unique => $value) {
                                        if($step_num > $value){
                                            $station_unique_key[] = $prod_runcard_station_step_num_unique;
                                            $station_unique[] = $value;
                                        }
                                    }

                                    $final_station_id = [];

                                    if(count($prod_runcard_station_id) > 0){
                                        for($index = 0; $index < count($prod_runcard_station_id); $index++){
                                            // if(array_search($index, $station_unique_key)){
                                            //     $final_station_id[] = $prod_runcard_station_id[$index];
                                            // }

                                            for($index2 = 0; $index2 < count($station_unique_key); $index2++){
                                                if($station_unique_key[$index2] == $index){
                                                    $final_station_id[] = $prod_runcard_station_id[$index];
                                                }
                                            }
                                        }
                                    }

                                    ProductionRuncardStation::whereIn('id', $final_station_id)
                                        ->where('qty_output', '!=', null)
                                        // ->increment('qty_output', $request->txt_edit_prod_runcard_station_ng);
                                        // ->increment('qty_input', $request->txt_edit_prod_runcard_station_ng);
                                        ->increment('qty_input', $request->txt_edit_prod_runcard_station_ng,
                                            [
                                                'qty_output' => DB::raw('qty_output + ' . $request->txt_edit_prod_runcard_station_ng),
                                            ]);
                                    // end of increment


                                    // Required to save MOD if has NG only
                                    if(isset($request->mod)){
                                        for($index = 0; $index < count($request->mod); $index++){
                                            ProductionRuncardStationMOD::insert([
                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                'mod_id' => $request->mod[$index],
                                                'mod_qty' => $request->mod_qty[$index],
                                                'created_by' => Auth::user()->id,
                                                'last_updated_by' => Auth::user()->id,
                                                'update_version' => 1,
                                                'status' => 1,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s'),
                                            ]);
                                        }
                                    }
                                    else{
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Please Fill-up MOD!';
                                        $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }
                                }
                                else{
                                    // Don't save the MOD
                                }


                                // Insert materials per sub station
                                if(isset($request->txt_edit_prod_runcard_station_material_kitting)){
                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_station_material_kitting); $index++){
                                        ProductionRuncardStationMaterial::insert([
                                            'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                            'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                            'item' => explode('--', $request->txt_edit_prod_runcard_station_material_kitting[$index])[0],
                                            'item_desc' => explode('--', $request->txt_edit_prod_runcard_station_material_kitting[$index])[1],
                                            'tbl_wbs' => 1,
                                            // 'has_emboss' => 0,
                                            'status' => 1,
                                            'created_by' => Auth::user()->id,
                                            'last_updated_by' => Auth::user()->id,
                                            'update_version' => 1,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }

                                if(isset($request->txt_edit_prod_runcard_station_sakidashi)){
                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_station_sakidashi); $index++){
                                        ProductionRuncardStationMaterial::insert([
                                            'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                            'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                            'item' => explode('--', $request->txt_edit_prod_runcard_station_sakidashi[$index])[0],
                                            'item_desc' => explode('--', $request->txt_edit_prod_runcard_station_sakidashi[$index])[1],
                                            'tbl_wbs' => 2,
                                            // 'has_emboss' => 0,
                                            'status' => 1,
                                            'created_by' => Auth::user()->id,
                                            'last_updated_by' => Auth::user()->id,
                                            'update_version' => 1,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }

                                if(isset($request->txt_edit_prod_runcard_station_emboss)){
                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_station_emboss); $index++){
                                        ProductionRuncardStationMaterial::insert([
                                            'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                            'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                            'item' => explode('--', $request->txt_edit_prod_runcard_station_emboss[$index])[0],
                                            'item_desc' => explode('--', $request->txt_edit_prod_runcard_station_emboss[$index])[1],
                                            'tbl_wbs' => 2,
                                            // 'has_emboss' => 1,
                                            'status' => 1,
                                            'created_by' => Auth::user()->id,
                                            'last_updated_by' => Auth::user()->id,
                                            'update_version' => 1,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }


                                DB::commit();
                                $result = true;

                                // $status = 3;

                                // $prod_runcard_details = ProductionRuncardStation::where('production_runcard_id', $request->txt_prod_runcard_id_query)
                                //                             ->where('qty_output', null)->get();

                                // if(count($prod_runcard_details) <= 0){
                                //     $status = 4;
                                // }

                                // if($request->has_emboss == 1){
                                //     $status = 8;
                                // }

                                // ProductionRuncard::where('id',$request->txt_prod_runcard_station_id_query)
                                //     ->update(
                                //         [
                                //             'status' => $status,
                                //             'last_updated_by' => Auth::user()->id,
                                //             'updated_at' => date('Y-m-d H:i:s')
                                //         ]
                                //     );

                                // $result = true;

                            } catch (Exception $e) {
                                DB::rollback();
                                $result = false;
                            }
                        // }
                        // else{
                        //     $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                        //     $return_body = 'Please complete required fields.';
                        //     $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                        //     return $return;
                        // }
                    }

                    try{
                        $status = 3;

                        $prod_runcard_details = ProductionRuncardStation::where('production_runcard_id', $request->txt_prod_runcard_id_query)
                                                    ->where('qty_output', null)->get();

                        // if(count($prod_runcard_details) <= 0){
                        //     $status = 4;
                        //     if($request->require_oqc_before_emboss == 1){
                        //         $status = 7;
                        //     }
                        // }


                        // if($request->has_emboss == 1){
                        //     $status = 8;
                        // }

                        ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                            ->update(
                                [
                                    'status' => $status,
                                    'last_updated_by' => Auth::user()->id,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]
                            );
                        DB::commit();
                        $result = true;
                    }

                    catch (Exception $e) {
                        DB::rollback();
                        $result = false;
                    }

                    if( !$result ){
                        $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                        $return_body = 'An error occured. The record not saved.';
                    }
                    $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                    return $return;
                }
                else{
                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                    $return_body = ' Scanned QR Code is not Operator.';
                    $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                    return $return;
                }
            }
            else{
                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                $return_body = ' Invalid Employee No.';
                $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                return $return;
                // return '0';
            }
        }
    }

    public function fn_get_prod_runcards(Request $request){
        date_default_timezone_set('Asia/Manila');
        $prod_runcards = ProductionRuncard::all();
        return response()->json(['prod_runcards' => $prod_runcards]);
    }


    // FOR WAREHOUSE
    public function fn_warehouse_view_batches(Request $request){
        date_default_timezone_set('Asia/Manila');
        $tbl_wbs_material_kitting = MaterialIssuanceSubSystem::with([
                    'kit_issuance_info'
                ])
                ->where('po_no',$request['po_number'])->get();

        return DataTables::of($tbl_wbs_material_kitting)
            ->addColumn('issued_qty', function(){
                $result = "---";
                return $result;
            })
            ->addColumn('action', function($tbl_wbs_material_kitting){
                $material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();

                $result = "";

                 // $result.='<button style="width:30px;" title="Print PO No." po_no="' . $tbl_wbs_material_kitting->po_no . '" material-issuance-id="' . $tbl_wbs_material_kitting->id . '" class="btnPrintWHPONo btn btn-success btn-sm  py-0"><i class="fa fa-print"></i></button>';

                $result.=' <button style="width:30px;" title="Print WHS Slip" po_no="' . $tbl_wbs_material_kitting->po_no . '" material-issuance-id="' . $tbl_wbs_material_kitting->id . '" class="btnPrintWHMatIssu btn btn-info btn-sm  py-0"><i class="fa fa-print"></i></button>';

                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    // public function fn_fn_get_warehouse_view_batches_by_id(Request $request){
    // date_default_timezone_set('Asia/Manila');
    //     $tbl_wbs_material_kitting = MaterialIssuanceSubSystem::where('id', $request->material_issuance_id)->get();

    //     $po_qrcode = "";

    //     if($tbl_wbs_material_kitting->count() > 0){
    //         if($request->qr_code_type == 1){
    //             $po_qrcode = QrCode::format('png')
    //                         ->size(200)->errorCorrection('H')
    //                         ->generate($tbl_wbs_material_kitting[0]->issuance_no);
    //         }
    //         else{
    //             $po_qrcode = QrCode::format('png')
    //                         ->size(200)->errorCorrection('H')
    //                         ->generate($tbl_wbs_material_kitting[0]->po_no);

    //         }

    //         $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);
    //     }

    //     return response()->json(['tbl_wbs_material_kitting' => $tbl_wbs_material_kitting, 'po_qrcode' => $po_qrcode]);
    // }

    // public function fn_get_warehouse_sakidashi_view_batches_by_id(Request $request){
    // date_default_timezone_set('Asia/Manila');
    //     $tbl_sakidashi_kitting = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')->where('id', $request->sakidashi_issuance_id)->get();

    //     $po_qrcode = "";

    //     if($tbl_sakidashi_kitting->count() > 0){
    //         $po_qrcode = QrCode::format('png')
    //                         ->size(200)->errorCorrection('H')
    //                         ->generate($tbl_sakidashi_kitting[0]->tbl_wbs_sakidashi_issuance_item->lot_no);

    //         $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);
    //     }

    //     return response()->json(['tbl_sakidashi_kitting' => $tbl_sakidashi_kitting, 'po_qrcode' => $po_qrcode]);
    // }

    public function fn_fn_get_warehouse_view_batches_by_id(Request $request){
        date_default_timezone_set('Asia/Manila');
        $tbl_wbs_material_kitting = MaterialIssuanceSubSystem::where('id', $request->material_issuance_id)->get();

        $po_qrcode = "";
        $whs_slip_qrcode = "";

        if($tbl_wbs_material_kitting->count() > 0){
                $po_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($tbl_wbs_material_kitting[0]->po_no);

                $whs_slip_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($tbl_wbs_material_kitting[0]->issuance_no);

            $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);

            $whs_slip_qrcode = "data:image/png;base64," . base64_encode($whs_slip_qrcode);
        }

        return response()->json(['tbl_wbs_material_kitting' => $tbl_wbs_material_kitting, 'po_qrcode' => $po_qrcode, 'whs_slip_qrcode' => $whs_slip_qrcode]);
    }

    public function fn_get_warehouse_sakidashi_view_batches_by_id(Request $request){
        date_default_timezone_set('Asia/Manila');
        $tbl_sakidashi_kitting = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')->where('id', $request->sakidashi_issuance_id)->get();

        $po_qrcode = "";
        $ctrl_no_qrcode = "";
        $lotname_qrcode = "";

        if($tbl_sakidashi_kitting->count() > 0){
            $po_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($tbl_sakidashi_kitting[0]->po_no);

            $ctrl_no_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($tbl_sakidashi_kitting[0]->tbl_wbs_sakidashi_issuance_item->lot_no . ' | ' . $tbl_sakidashi_kitting[0]->tbl_wbs_sakidashi_issuance_item->item);

            $lotname_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            // ->generate($tbl_sakidashi_kitting[0]->tbl_wbs_sakidashi_issuance_item->item_desc);
                            ->generate($tbl_sakidashi_kitting[0]->tbl_wbs_sakidashi_issuance_item->item);

            $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);
            $ctrl_no_qrcode = "data:image/png;base64," . base64_encode($ctrl_no_qrcode);
            $lotname_qrcode = "data:image/png;base64," . base64_encode($lotname_qrcode);
        }

        return response()->json(['tbl_sakidashi_kitting' => $tbl_sakidashi_kitting, 'po_qrcode' => $po_qrcode, 'ctrl_no_qrcode' => $ctrl_no_qrcode, 'lotname_qrcode' => $lotname_qrcode]);
    }

    public function fn_view_warehouse_sakidashi_issuance(Request $request){
        date_default_timezone_set('Asia/Manila');
        $tbl_wbs_sakidashi_issuance = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')->where('po_no',$request['po_number'])->get();

        return DataTables::of($tbl_wbs_sakidashi_issuance)
            ->addColumn('received_dt', function($tbl_wbs_sakidashi_issuance){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_sakidashi_issuance->id
                        ],
                        [
                            'wbs_table','=',
                            2
                        ],
                        [
                            'deleted_at','=',
                            null
                        ],
                    ])
                    ->get();

                $result = "---";
                if ( $parts_preps->count() ) {
                    $result = $parts_preps[0]->received_at;
                }
                return $result;
            })
            ->addColumn('received_by', function($tbl_wbs_sakidashi_issuance){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_sakidashi_issuance->id
                        ],
                        [
                            'wbs_table','=',
                            2
                        ],
                        [
                            'deleted_at','=',
                            null
                        ],
                    ])
                    ->get();
                $result = "---";
                if ( $parts_preps->count() ) {
                    $users = User::where('id', $parts_preps[0]->received_by )->get();
                    if ( $users->count() ) {
                        $result = $users[0]->name;
                    }
                }
                return $result;
            })
            ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';

                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_sakidashi_issuance->id
                        ],
                        [
                            'wbs_table','=',
                            2
                        ],
                        [
                            'deleted_at','=',
                            null
                        ],
                    ])
                    ->get();

                if ( $parts_preps->count() ) {
                    switch ( $parts_preps[0]->status ) {
                        case 1:
                            $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
                            break;
                        case 2:
                            $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
                            break;
                        case 3:
                            $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
                            break;
                        case 4:
                            $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
                            break;
                        case 5:
                            $result = '<i title="Done verification" class="fa fa-check text-success px-2"></i>';
                            break;
                        case 6:
                            $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
                            break;
                        case 7:
                            $result = '<i title="Done verification" class="fa fa-check text-success px-2"></i>';
                            break;

                        default:
                            # code...
                            break;
                    }
                }

                return $result;
            })
            ->addColumn('action', function($tbl_wbs_sakidashi_issuance){
                $parts_preps_id = 0;
                $disabled = 'disabled';
                $disabled_passed_btn = 'disabled';
                $disabled_failed_btn = 'disabled';
                $title = '';


                $disabled_passed_btn = '';
                $disabled_failed_btn = '';

                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_sakidashi_issuance->id
                        ],
                        [
                            'wbs_table','=',
                            2
                        ],
                        [
                            'deleted_at','=',
                            null
                        ],
                    ])
                    ->get();

                if ( $parts_preps->count() ) {
                    $parts_preps_id = $parts_preps[0]->id;
                    $disabled_passed_btn = 'disabled';
                    $disabled_failed_btn = 'disabled';
                    if( $parts_preps[0]->status == 2 ){
                        $disabled_passed_btn = '';
                    }
                    if( $parts_preps[0]->status == 1 || $parts_preps[0]->status > 2 ){
                        $disabled = '';
                    }
                }


                //-----
                $hidden = ' hidden ';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----
                $result = "";

                // $result='<button '.$disabled.' style="width:30px;" title="Open details" class="mb-1 btn_sakidashi_material_action btn_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                // $result.='<br><button '.$disabled_passed_btn.' '.$hidden.' style="width:30px;" title="Passed" class="mb-1 btn_material_action btn_sakidashi_material_pass_details btn btn-success btn-sm py-0"><i class="fa fa-check-circle fa-sm"></i></button>';
                // $result.='<br><button '.$disabled_failed_btn.' '.$hidden.' style="width:30px;" title="Failed" class="btn_material_action btn_sakidashi_material_fail_details btn btn-warning btn-sm py-0"><i class="fa fa-exclamation-triangle fa-sm"></i></button>';
                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                // $result.=' <input type="hidden" class="col_material_code" value="'.$tbl_wbs_sakidashi_issuance->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';
                $result.=' <input type="hidden" class="col_material_po" value="'.$tbl_wbs_sakidashi_issuance->po_no.'">';
                $result.=' <input type="hidden" class="col_parts_preps_id" value="'.$parts_preps_id.'">';

                $result.='<button style="width:30px;" title="Print" po_no="' . $tbl_wbs_sakidashi_issuance->po_no . '" sakidashi-issuance-id="' . $tbl_wbs_sakidashi_issuance->id . '" class="btnPrintWHSakIssu btn btn-info btn-sm  py-0"><i class="fa fa-print"></i></button>';

                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }
    // ../FOR WAREHOUSE

    public function fn_view_sakidashi_prod(Request $request){
        date_default_timezone_set('Asia/Manila');
        $tbl_wbs_sakidashi_issuance = [];

        $runcard_status = "";
        $po_no = $request->po_number;
        $chkDisable = false;

        $prod_runcard_sak_list = [];

        $selected_issuances = [];

        $selected_material_kit_list = [];
        $selected_sakidashi_list = [];

        if(isset($request->material_kit_list) && count($request->material_kit_list) > 0){
            $selected_material_kit_list = $request->material_kit_list;
        }

        if(isset($request->sakidashi_list) && count($request->sakidashi_list) > 0){
            $selected_sakidashi_list = $request->sakidashi_list;
        }

        $selected_issuances = array_merge($selected_material_kit_list, $selected_sakidashi_list);

        if(isset($request->prod_runcard_id_query)){
            $prod_runcard_sak_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
                    // ->where('tbl_wbs', 2)
                    ->where('status', 1)
                    // ->where('for_emboss', 0)
                    ->get();
            // $selected_issuances = array_merge($request->sakidashi_list, $request->material_kit_list);
        }

        // return $prod_runcard_sak_list;

        if(count($prod_runcard_sak_list) > 0){
            // $runcard_status = $request->runcard_status;
            $tbl_wbs_sakidashi_issuance = WBSSakidashiIssuance::with([
                    'tbl_wbs_sakidashi_issuance_item',
                    'device_info',
                    'device_info.material_process',
                    'device_info.material_process.station_sub_station',
                    'device_info.material_process.station_sub_station.station',
                    'parts_prep_info' => function($query){
                        $query->where('wbs_table', 2);
                        $query->where('deleted_at', null);
                    },
                    'prod_runcard_material_list' => function($query){
                        $query->where('status', 1);
                        $query->where('tbl_wbs', 2);
                    },
                    'prod_runcard_material_list.prod_runcard_details',
                    'prod_runcard_material_list.prod_runcard_details.prod_runcard_station_many_details' => function($query){
                        $query->orderBy('step_num', 'desc');
                        // $query->first();
                        // $query->limit(1);
                    }
                    // 'material_issuance_info'
                ])
                ->where('po_no', $request['po_number']);

                // if($runcard_status == 1){
                    // $tbl_wbs_sakidashi_issuance->whereIn('id', $request->sakidashi_list);
                // }

                // if(isset($selected_issuances) && count($selected_issuances) > 0){
                    $tbl_wbs_sakidashi_issuance->whereIn('id', $selected_sakidashi_list);
                    $chkDisable = true;
                // }

                $tbl_wbs_sakidashi_issuance->get();
                $chkDisable = true;
        }
        else{

            $result = WBSSakidashiIssuance::with([
                    'tbl_wbs_sakidashi_issuance_item' => function($query) use($request){
                        // Removed temporarily
                        $query->where('lot_no', $request->lot_no);
                    },
                    'parts_prep_info' => function($query){
                        $query->where('wbs_table', 2);
                        $query->where('deleted_at', null);
                    },
                    'prod_runcard_material_list' => function($query){
                        $query->where('status', 1);
                        $query->where('tbl_wbs', 2);
                    },
                    'prod_runcard_material_list.prod_runcard_details',
                    'prod_runcard_material_list.prod_runcard_details.prod_runcard_station_many_details' => function($query){
                        $query->orderBy('step_num', 'desc');
                        // $query->first();
                        // $query->limit(1);
                    }
                    // 'material_issuance_info'
                ])
                // ->whereNotIn('id', $arr_sakidashi_issuance_id)
                ->where('po_no', $request['po_number'])
                // ->where('issuance_no', $request->control_no)
                ->get();

            $tbl_wbs_sakidashi_issuance = collect($result)->where('tbl_wbs_sakidashi_issuance_item', '!=', null)->flatten(1);

            $chkDisable = false;

            // return $tbl_wbs_sakidashi_issuance;

        }

        return DataTables::of($tbl_wbs_sakidashi_issuance)
            ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
                $disabled = 'disabled';
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
                $title = "";
                $parts_prep_done = false;



                                if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
                                    if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
                                        switch ( $tbl_wbs_sakidashi_issuance->parts_prep_info->status ) {
                                            case 1:
                                                $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                                break;
                                            case 2:
                                                $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
                                                break;
                                            case 3:
                                                $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                                break;
                                            case 4:
                                                $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
                                                break;
                                            case 5:
                                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                                $parts_prep_done = true;
                                                break;
                                            case 6:
                                                $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
                                                break;
                                            case 7:
                                                // $result = '<i title="Parts Prep. Done verification" class="fa fa-check text-success px-2"></i>';
                                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                                $parts_prep_done = true;
                                                break;

                                            default:
                                                # code...
                                                break;
                                        }
                                    }

                                    // if( $parts_preps[0]->status == 5 || $parts_preps[0]->status == 7 ){ //approved in parts prep
                                    //     $disabled = '';
                                    // }
                                    else{
                                        $result = '<i title="Ready for Prod\'n" class="fas fa-cogs text-success px-2"></i>';
                                        $parts_prep_done = true;
                                    }
                                }
                                else{
                                    $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
                                }

                // if($disabled == ''){
                //     if(!$has_prod_process){
                //         $disabled = 'disabled';
                //     }
                // }

                // if(!$has_parts_prep_process){
                //     if($has_prod_process){
                //         $disabled = '';
                //     }
                //     else{
                //         $disabled = 'disabled';
                //     }
                // }

                // $result='<button '.$disabled.' style="width:30px;" title="Open details" class="btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                // $result='<button style="width:30px;" title="Open details" class="btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

                if($parts_prep_done == true){
                    $result.=' <input type="hidden" class="col_lot_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                }

                return $result;
            })
            ->addColumn('action', function($tbl_wbs_sakidashi_issuance) use ($runcard_status, $chkDisable){
                $disabled = 'disabled';
                $result = "";
                $title = "";
                $has_parts_prep = false;
                $has_prod = false;

                // if($tbl_wbs_sakidashi_issuance->device_info != null){

                //     if(count($tbl_wbs_sakidashi_issuance->device_info->material_process) > 0){
                //         for($index = 0; $index < count($tbl_wbs_sakidashi_issuance->device_info->material_process); $index++){
                //             if($tbl_wbs_sakidashi_issuance->device_info->material_process[$index]->station_sub_station->station->station_type == 1 && $tbl_wbs_sakidashi_issuance->device_info->material_process[$index]->status == 1){
                //                 $has_parts_prep = true;
                //             }
                //             if($tbl_wbs_sakidashi_issuance->device_info->material_process[$index]->station_sub_station->station->station_type == 2 && $tbl_wbs_sakidashi_issuance->device_info->material_process[$index]->status == 1){
                //                 $has_prod = true;
                //             }
                //         }
                //     }
                //     else{
                //         $disabled = 'disabled';
                //     }
                // }

                if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
                    if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
                        if( $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 5 || $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 7 ){ //Not approved in parts prep
                            $disabled = '';
                        }
                        else{
                            $disabled = 'disabled';
                        }
                    }
                    else{
                        $disabled = '';
                    }
                }
                else{
                    $disabled = 'disabled';
                }


                // if($has_parts_prep){
                //     $parts_preps = PartsPrep::where([
                //             [
                //                 'wbs_kit_issuance_id','=',
                //                 $tbl_wbs_sakidashi_issuance->id
                //             ],
                //             [
                //                 'wbs_table','=',
                //                 2
                //             ],
                //             [
                //                 'deleted_at','=',
                //                 null
                //             ],
                //         ])
                //         ->get();

                //     if ( $parts_preps->count() ) {
                //         if( $parts_preps[0]->status == 5 || $parts_preps[0]->status == 7 ){ //Not approved in parts prep
                //             $disabled = '';
                //         }
                //     }

                // }

                // if($disabled == ''){
                //     if(!$has_prod){
                //         $disabled = 'disabled';
                //     }
                // }

                // if(!$has_parts_prep){
                //     if($has_prod){
                //         $disabled = '';
                //     }
                //     else{
                //         $disabled = 'disabled';
                //     }
                // }

                // if(Auth::user()->user_level_id == 1){
                //     $disabled = "";
                // }

                // $result='<button '.$disabled.' style="width:30px;" title="Open details" class="btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';

                // $result='<button style="width:30px;" title="Open details" class="btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';

                if($runcard_status == 1){
                    $disabled = 'disabled';
                }

                if($chkDisable){
                    $disabled = 'disabled';
                }

                $result='<center><input sakidashi-issue-id="' . $tbl_wbs_sakidashi_issuance->id . '" type="checkbox" '. $disabled .' title="Check to select" class="py-0 chkSelSakidashiIssue" style="display: block;"></center>';

                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

                return $result;
            })
            ->addColumn('runcard_used_qty', function($tbl_wbs_sakidashi_issuance){
                $result = 0;
                if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
                    for($index = 0; $index < count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list); $index++){
                        if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details != null){
                            if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details)){
                                $result += $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details[0]->qty_output;
                            }
                        }
                    }
                }
                return $result;
            })
            ->addColumn('lot_qty_to_complete', function($tbl_wbs_sakidashi_issuance){
                $result = 0;
                if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
                    // for($index = 0; $index < count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list); $index++){
                    //     if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details != null){
                    //
                    //     }
                    // }
                    if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details != null){
                        $result = $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details->lot_qty;
                    }
                }
                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    // public function fn_view_emboss_prod(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     $tbl_wbs_sakidashi_issuance = [];

    //     $runcard_status = "";
    //     if(isset($request->runcard_status)){
    //         $runcard_status = $request->runcard_status;
    //     }
    //     $po_no = $request->po_number;
    //     $chkDisable = false;

    //     $prod_runcard_sak_list = [];
    //     $selected_emboss_list = [];

    //     if(isset($request->emboss_kit_list) && count($request->emboss_kit_list) > 0){
    //         $selected_emboss_list = $request->emboss_kit_list;
    //     }

    //     if(isset($request->prod_runcard_id_query)){
    //         $prod_runcard_sak_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
    //                 // ->where('tbl_wbs', 2)
    //                 ->where('status', 1)
    //                 ->where('for_emboss', 1)
    //                 ->get();
    //         // $selected_issuances = array_merge($request->sakidashi_list, $request->emboss_kit_list);
    //     }

    //     // return $prod_runcard_sak_list;

    //     if(count($prod_runcard_sak_list) > 0){
    //         // $runcard_status = $request->runcard_status;
    //         $tbl_wbs_sakidashi_issuance = WBSSakidashiIssuance::with([
    //                 'tbl_wbs_sakidashi_issuance_item',
    //                 'device_info',
    //                 'device_info.material_process',
    //                 'device_info.material_process.station_sub_station',
    //                 'device_info.material_process.station_sub_station.station',
    //                 'parts_prep_info' => function($query){
    //                     $query->where('wbs_table', 2);
    //                     $query->where('deleted_at', null);
    //                 },
    //                 'prod_runcard_material_list' => function($query){
    //                     $query->where('status', 1);
    //                     $query->where('tbl_wbs', 2);
    //                 },
    //                 'prod_runcard_material_list.prod_runcard_details',
    //                 'prod_runcard_material_list.prod_runcard_details.prod_runcard_station_many_details' => function($query){
    //                     $query->orderBy('step_num', 'desc');
    //                     // $query->first();
    //                     // $query->limit(1);
    //                 }
    //                 // 'material_issuance_info'
    //             ])
    //             ->where('po_no', $request['po_number']);

    //             // if($runcard_status == 1){
    //                 // $tbl_wbs_sakidashi_issuance->whereIn('id', $request->sakidashi_list);
    //             // }

    //             // if(isset($selected_issuances) && count($selected_issuances) > 0){
    //                 $tbl_wbs_sakidashi_issuance->whereIn('id', $selected_emboss_list);
    //                 $chkDisable = true;
    //             // }

    //             $tbl_wbs_sakidashi_issuance->get();
    //             $chkDisable = true;
    //     }
    //     else{

    //         $result = WBSSakidashiIssuance::with([
    //                 'tbl_wbs_sakidashi_issuance_item' => function($query) use($request){
    //                     $query->where('lot_no', $request->lot_no);
    //                 },
    //                 'parts_prep_info' => function($query){
    //                     $query->where('wbs_table', 2);
    //                     $query->where('deleted_at', null);
    //                 },
    //                 'prod_runcard_material_list' => function($query){
    //                     $query->where('status', 1);
    //                     $query->where('tbl_wbs', 2);
    //                 },
    //                 'prod_runcard_material_list.prod_runcard_details',
    //                 'prod_runcard_material_list.prod_runcard_details.prod_runcard_station_many_details' => function($query){
    //                     $query->orderBy('step_num', 'desc');
    //                 }
    //             ])
    //             ->where('po_no', $request['po_number'])
    //             ->get();

    //         $tbl_wbs_sakidashi_issuance = collect($result)->where('tbl_wbs_sakidashi_issuance_item', '!=', null)->flatten(1);

    //         $chkDisable = false;
    //     }

    //     return DataTables::of($tbl_wbs_sakidashi_issuance)
    //         ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
    //             $disabled = 'disabled';
    //             $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
    //             $title = "";
    //             $parts_prep_done = false;



    //                             if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
    //                                 if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
    //                                     switch ( $tbl_wbs_sakidashi_issuance->parts_prep_info->status ) {
    //                                         case 1:
    //                                             $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
    //                                             break;
    //                                         case 2:
    //                                             $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
    //                                             break;
    //                                         case 3:
    //                                             $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
    //                                             break;
    //                                         case 4:
    //                                             $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
    //                                             break;
    //                                         case 5:
    //                                             $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
    //                                             $parts_prep_done = true;
    //                                             break;
    //                                         case 6:
    //                                             $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
    //                                             break;
    //                                         case 7:
    //                                             $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
    //                                             $parts_prep_done = true;
    //                                             break;

    //                                         default:
    //                                             # code...
    //                                             break;
    //                                     }
    //                                 }
    //                                 else{
    //                                     $result = '<i title="Ready for Prod\'n" class="fas fa-cogs text-success px-2"></i>';
    //                                     $parts_prep_done = true;
    //                                 }
    //                             }
    //                             else{
    //                                 $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
    //                             }

    //             $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
    //             $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

    //             if($parts_prep_done == true){
    //                 $result.=' <input type="hidden" class="col_lot_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('action', function($tbl_wbs_sakidashi_issuance) use ($runcard_status, $chkDisable, $request){
    //             $disabled = 'disabled';
    //             $result = "";
    //             $title = "";
    //             $has_parts_prep = false;
    //             $has_prod = false;

    //             if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
    //                 if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
    //                     if( $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 5 || $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 7 ){ //Not approved in parts prep
    //                         $disabled = '';
    //                     }
    //                     else{
    //                         $disabled = 'disabled';
    //                     }
    //                 }
    //                 else{
    //                     $disabled = '';
    //                 }
    //             }
    //             else{
    //                 $disabled = 'disabled';
    //             }

    //             if($request->require_oqc_before_emboss == 1){
    //                 if($runcard_status != 7){
    //                     $disabled = 'disabled';
    //                 }
    //             }
    //             else{
    //                 $disabled = '';
    //             }

    //             if($chkDisable){
    //                 $disabled = 'disabled';
    //             }

    //             $result='<center><input emboss-issue-id="' . $tbl_wbs_sakidashi_issuance->id . '" type="checkbox" '. $disabled .' title="Check to select" class="py-0 chkSelEmbossIssue" style="display: block;"></center>';

    //             $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
    //             $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

    //             return $result;
    //         })
    //         ->addColumn('runcard_used_qty', function($tbl_wbs_sakidashi_issuance){
    //             $result = 0;
    //             if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
    //                 for($index = 0; $index < count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list); $index++){
    //                     if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details != null){
    //                         if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details)){
    //                             $result += $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details[0]->qty_output;
    //                         }
    //                     }
    //                 }
    //             }
    //             return $result;
    //         })
    //         ->addColumn('lot_qty_to_complete', function($tbl_wbs_sakidashi_issuance){
    //             $result = 0;
    //             if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
    //                 if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details != null){
    //                     $result = $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details->lot_qty;
    //                 }
    //             }
    //             return $result;
    //         })
    //         ->rawColumns(['status','action'])
    //         ->make(true);
    // }

    /**
     * Old code
     * Due to modification of Production Runcard and Prod Runcard Station
     * Commented on 10-24-2022
     * -JD
     */
    // public function get_prod_runcard_by_po(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     // $prod_runcards = ProductionRuncard::with([
    //     //                     'prod_runcard_station_many_details' => function($query){
    //     //                         $query->orderBy('step_num', 'desc');
    //     //                         // $query->first();
    //     //                     }
    //     //                 ])
    //     //                 ->where('po_no', $request->po_number)
    //     //                 ->get();

    //     // return $prod_runcards;

    //     $prod_runcards = ProductionRuncard::with([
    //                         'wbs_kitting',
    //                         // 'prod_runcard_station_many_details' => function($query){
    //                         //     $query->orderBy('step_num', 'desc');
    //                         //     $query->where('status', 1);
    //                         //     // $query->first();
    //                         //     // $query->limit(1);
    //                         // },
    //                         'prod_runcard_station_many_details' => function($query){
    //                             // $query->where('has_emboss', '!=', 1);
    //                             $query->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(process_flow_id,"-", 1), UNSIGNED INTEGER)', 'ASC'));
    //                             $query->orderBy(\DB::raw('right(process_flow_id,LOCATE("-",process_flow_id) - 1)', 'ASC'));
    //                             // $query->limit(1);
    //                         },
    //                         'eng_qualification_info',
    //                         'qc_stamp_qualification_info',
    //                     ])
    //                     ->where('po_no', $request->po_number)
    //                     ->where('deleted_at', null)
    //                     ->get();

    //     return DataTables::of($prod_runcards)
    //         ->addColumn('raw_action', function($prod_runcard){

    //             $result='<button style="width:30px;" runcard-id="' . $prod_runcard->id . '" title="Open details" class="btnOpenRuncardDetails btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';

    //             $result .= ' <button style="width:30px;" runcard-id="' . $prod_runcard->id . '" runcard-no="' . $prod_runcard->runcard_no . '" title="Print QR Code" class="btnPrintRuncardNo btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

    //             // $result .= ' <button style="width:30px;" runcard-id="' . $prod_runcard->id . '" title="Print C3 Label Sticker" class="btnPrintRuncardC3Label btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';
    //             return $result;
    //         })
    //         ->addColumn('raw_status', function($prod_runcard){
    //             $result = '<i title="Ongoing" class="far fa-clock text-secondary px-2"></i>';

    //             if($prod_runcard->status == 3){
    //                 $result = '<i title="Submitted to OQC Lot App" class="fas fa-check text-success px-2"></i>';
    //             }

    //             // if($prod_runcard->status == 3){
    //             //     $result = '<i title="For Setup Stations" class="far fa-clock text-secondary px-2""></i>';
    //             // }
    //             // else if($prod_runcard->status == 4){
    //             //     $result = '<i title="For Verification" class="far fa-clock text-secondary px-2""></i>';
    //             // }
    //             // else if($prod_runcard->status == 5){
    //             //     $result = '<i title="For Prod\'n Approval" class="far fa-clock text-secondary px-2""></i>';
    //             // }
    //             // else if($prod_runcard->status == 6){
    //             //     $result = '<i title="For QC Approval" class="far fa-clock text-secondary px-2""></i>';
    //             // }
    //             // else if($prod_runcard->status == 7 || $prod_runcard->status == 8){
    //             //     $result = '<i title="Done" class="fas fa-check text-success px-2"></i>';
    //             // }

    //             // if($prod_runcard->has_emboss == 1){
    //             //     if($prod_runcard->status == 8){
    //             //         $result .= '<i title="Emboss - Done" class="fas fa-toilet-paper text-success px-2"></i>';
    //             //     }
    //             //     else{
    //             //         $result .= '<i title="Emboss - Pending" class="fas fa-toilet-paper text-secondary px-2"></i>';
    //             //     }
    //             // }

    //             return $result;
    //         })
    //         ->addColumn('total_no_of_ng', function($prod_runcard){
    //             $total_no_of_ng = 0;

    //             if(count($prod_runcard->prod_runcard_station_many_details) > 0){
    //                 $total_no_of_ng = 0;
    //                 for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
    //                     $total_no_of_ng += $prod_runcard->prod_runcard_station_many_details[$index]->qty_ng;
    //                 }
    //             }

    //             return $total_no_of_ng;
    //         })
    //         ->addColumn('last_runcard_po_qty', function($prod_runcard){
    //            $last_runcard_po_qty = 0;

    //             if(count($prod_runcard->prod_runcard_station_many_details) > 0){
    //                 $last_runcard_po_qty = 0;
    //                 for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
    //                     $last_runcard_po_qty += $prod_runcard->prod_runcard_station_many_details[$index]->qty_output;
    //                 }
    //             }

    //             $last_runcard_po_qty += $prod_runcard->qty_output;
    //             return $last_runcard_po_qty;
    //         })
    //         ->addColumn('raw_setup_qualification', function($prod_runcard){
    //             $result = '';

    //             if($prod_runcard->setup_qualification == 1){
    //                 $result = 'Yes';
    //             }
    //             else{
    //                 $result = 'No';
    //             }

    //             return $result;
    //         })
    //         // ->addColumn('raw_setup_qualified', function($prod_runcard){
    //         //     $result = '';

    //         //     if($prod_runcard->setup_qualified == 1){
    //         //         $result = 'Yes';
    //         //     }
    //         //     else{
    //         //         $result = 'No';
    //         //     }

    //         //     return $result;
    //         // })
    //         ->addColumn('raw_eng_qualification_id', function($prod_runcard){
    //             $result = '';

    //             if($prod_runcard->eng_qualification_info != null){
    //                 $result = $prod_runcard->eng_qualification_info->name;
    //             }
    //             else{
    //                 $result = 'N/A';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('raw_qc_qualification', function($prod_runcard){
    //             $result = '';

    //             if($prod_runcard->qc_qualification == 1){
    //                 $result = 'Yes';
    //             }
    //             else{
    //                 $result = 'No';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('raw_qc_qualified', function($prod_runcard){
    //             $result = '';

    //             if($prod_runcard->qc_qualified == 1){
    //                 $result = 'Yes';
    //             }
    //             else{
    //                 $result = 'No';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('raw_qc_stamp_qualification_info', function($prod_runcard){
    //             $result = '';

    //             if($prod_runcard->qc_stamp_qualification_info != null){
    //                 $result = $prod_runcard->qc_stamp_qualification_info->name;
    //             }
    //             else{
    //                 $result = 'N/A';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('raw_checkbox', function($prod_runcard){
    //             $result = '';
    //             $disabled = '';
    //             if($prod_runcard->status == 3){
    //                 $disabled = 'disabled';
    //             }
    //             $result = '<center><input type="checkbox" production-runcard-id="' . $prod_runcard->id . '" class="chkSelProdRuncard" ' . $disabled . '></center>';

    //             return $result;
    //         })
    //         ->rawColumns(['raw_action', 'raw_status', 'total_no_of_ng', 'last_runcard_po_qty', 'raw_checkbox'])
    //         ->make(true);
    // }

    /**
     * No use as of 10-24-2022
     */
    public function get_prod_runcard_by_po_test(Request $request){
        date_default_timezone_set('Asia/Manila');
        // $prod_runcards = ProductionRuncard::with([
        //                     'prod_runcard_station_many_details' => function($query){
        //                         $query->orderBy('step_num', 'desc');
        //                         // $query->first();
        //                     }
        //                 ])
        //                 ->where('po_no', $request->po_number)
        //                 ->get();

        // return $prod_runcards;

        $prod_runcards = ProductionRuncard::with([
                            'wbs_kitting',
                            // 'prod_runcard_station_many_details' => function($query){
                            //     $query->orderBy('step_num', 'desc');
                            //     $query->where('status', 1);
                            //     // $query->first();
                            //     // $query->limit(1);
                            // },
                            'prod_runcard_station_many_details' => function($query){
                                // $query->where('has_emboss', '!=', 1);
                                $query->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(process_flow_id,"-", 1), UNSIGNED INTEGER)', 'ASC'));
                                $query->orderBy(\DB::raw('right(process_flow_id,LOCATE("-",process_flow_id) - 1)', 'ASC'));
                                // $query->limit(1);
                            },
                            'eng_qualification_info',
                            'qc_stamp_qualification_info',
                        ])
                        ->where('po_no', $request->po_number)
                        ->where('deleted_at', null)
                        // ->orderBy(\DB::raw('right(runcard_no,LOCATE("-",runcard_no) - 2)', 'ASC'))
                        ->get();
        // return $prod_runcards;
        return DataTables::of($prod_runcards)
            ->addColumn('raw_action', function($prod_runcard){

                $result='<button style="width:30px;" runcard-id="' . $prod_runcard->id . '" title="Open details" class="btnOpenRuncardDetails btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';

                $result .= ' <button style="width:30px;" runcard-id="' . $prod_runcard->id . '" runcard-no="' . $prod_runcard->runcard_no . '" runcard-kit="' . $prod_runcard->kit_number . '" title="Print QR Code" class="btnPrintRuncardNo btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

                // $result .= ' <button style="width:30px;" runcard-id="' . $prod_runcard->id . '" title="Print C3 Label Sticker" class="btnPrintRuncardC3Label btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';
                return $result;
            })
            ->addColumn('raw_status', function($prod_runcard){
                $result = '<i title="On-going" class="far fa-clock text-secondary px-2"></i>';

                if($prod_runcard->status == 3){
                    $result = '<i title="Submitted" class="fas fa-check text-success px-2"></i>';
                }

                return $result;
            })
            ->addColumn('raw_process', function($prod_runcard){
                $result = '---';

                if($prod_runcard->wbs_kitting->device_name == 'NP568-078-016-P-1 - (Burn-in)'){
                     $result = '---';
                }

                return $result;
            })

            ->addColumn('total_no_of_ng', function($prod_runcard){
                $total_no_of_ng = 0;

                if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                    $total_no_of_ng = 0;
                    for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                        $total_no_of_ng += $prod_runcard->prod_runcard_station_many_details[$index]->qty_ng;
                    }
                }

                return $total_no_of_ng;
            })
            ->addColumn('last_runcard_po_qty', function($prod_runcard){
               $last_runcard_po_qty = 0;

                if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                    $last_runcard_po_qty = 0;
                    for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                        $last_runcard_po_qty += $prod_runcard->prod_runcard_station_many_details[$index]->qty_output;
                    }
                }
                $last_runcard_po_qty += $prod_runcard->qty_output;
                return $last_runcard_po_qty;
            })
            ->addColumn('raw_setup_qualification', function($prod_runcard){
                $result = '';

                if($prod_runcard->setup_qualification == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_eng_qualification_id', function($prod_runcard){
                $result = '';

                if($prod_runcard->eng_qualification_info != null){
                    $result = $prod_runcard->eng_qualification_info->name;
                }
                else{
                    $result = 'N/A';
                }

                return $result;
            })
            ->addColumn('raw_qc_qualification', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_qualification == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_qc_qualified', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_qualified == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_qc_stamp_qualification_info', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_stamp_qualification_info != null){
                    $result = $prod_runcard->qc_stamp_qualification_info->name;
                }
                else{
                    $result = 'N/A';
                }

                return $result;
            })
            ->addColumn('raw_checkbox', function($prod_runcard){
                $result = '';
                $disabled = '';
                if($prod_runcard->status == 3){
                    $disabled = 'disabled';
                }
                $result = '<center><input type="checkbox" production-runcard-id="' . $prod_runcard->id . '" class="chkSelProdRuncard" ' . $disabled . '></center>';

                return $result;
            })
            ->rawColumns(['raw_action', 'raw_status', 'total_no_of_ng', 'last_runcard_po_qty', 'raw_checkbox'])
            // ->rawColumns(['raw_action', 'raw_status', 'raw_process', 'total_no_of_ng', 'last_runcard_po_qty', 'raw_checkbox'])
            ->make(true);
    }

    /**
     * New code
     * Due to modification of Production Runcard, Prod Runcard Station and Material Process(Matrix)
     * Modified on 10-24-2022
     */
    public function get_prod_runcard_by_po(Request $request){
        // return 'working function1'; //save_prod_runcard_details
        date_default_timezone_set('Asia/Manila');

        $prod_runcards = ProductionRuncard::with([
            'wbs_kitting',

            'prod_runcard_station_many_details.process_flow.process_name',

            'prod_runcard_station_many_details.process_flow' => function ($query) {
                $query->orderByRaw('CONVERT(SUBSTRING_INDEX(step, "-", 1), UNSIGNED INTEGER) DESC')
                    ->orderByRaw('RIGHT(step, LOCATE("-", step) - 1) DESC');
            },

            'prod_runcard_station_many_details' => function ($query) {
                $query->orderByRaw('CONVERT(SUBSTRING_INDEX(process_flow_id, "-", 1), UNSIGNED INTEGER) ASC')
                    ->orderByRaw('RIGHT(process_flow_id, LOCATE("-", process_flow_id) - 1) ASC');
            },

            'eng_qualification_info',
            'qc_stamp_qualification_info',
        ])
        ->where('po_no', $request->po_number)
        ->whereNull('deleted_at')
        ->get();
        return DataTables::of($prod_runcards)
            ->addColumn('raw_action', function($prod_runcard){

                $result='<button style="width:30px;" runcard-id="' . $prod_runcard->id . '" title="Open details" class="btnOpenRuncardDetails btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';

                $result .= ' <button style="width:30px;" runcard-id="' . $prod_runcard->id . '" runcard-no="' . $prod_runcard->runcard_no . '" title="Print QR Code" class="btnPrintRuncardNo btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

                return $result;
            })
            ->addColumn('raw_status', function($prod_runcard){
                $result = '<i title="On-going" class="far fa-clock text-secondary px-2"></i>';

                if($prod_runcard->status == 3){
                    $result = '<i title="Submitted" class="fas fa-check text-success px-2"></i>';
                }

                return $result;
            })
            ->addColumn('raw_process', function($prod_runcard){
                $last_step_lbl = '---';
                // if($prod_runcard->wbs_kitting->device_name == 'NP568-078-016-P-1 - (Burn-in)'){
                if($prod_runcard->wbs_kitting->device_name){

                    if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                        $last_step = 0;
                        $counter = 1;
                        $last_step_array = [];
                        $last_step_arrayxx = [];
                        for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                            $last_step += $prod_runcard->prod_runcard_station_many_details[$index]->process_flow->step;
                            $last_step_array[] = $prod_runcard->prod_runcard_station_many_details[$index]->process_flow->step;
                            $last_step_arrayxx[] = $prod_runcard->prod_runcard_station_many_details[$index]->process_flow->process_name->name;
                            $counter++;
                        }
                    }else{
                        return $last_step_lbl;
                    }


                    $last_step_array = array_pop($last_step_array);
                    $last_step_arrayxx = array_pop($last_step_arrayxx);

                    if ( $last_step_array == 1){
                        // $last_step_lbl = 'For #ME Installation / #ME Pressing';
                        $last_step_lbl = '(Pending) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 2){
                        // $last_step_lbl = 'For Insulator Assembly';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 3){
                        // $last_step_lbl = 'For CS Installation';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 4){
                        // $last_step_lbl = 'For CS Check';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 5){
                        // $last_step_lbl = 'For Auto Insertion / #MO Installation';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 6){
                        // $last_step_lbl = 'For #MO Press';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 7){
                        // $last_step_lbl = 'For First Visual Inspection';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 8){
                        // $last_step_lbl = 'For Full Assembly and Printing / Curing';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 9){
                        // $last_step_lbl = 'For Air Blowing';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 10){
                        // $last_step_lbl = 'For MPC';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 11){
                        // $last_step_lbl = 'For Final Visual Inspection';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 12){
                        // $last_step_lbl = 'For Over-all Inspection';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 13){
                        // $last_step_lbl = 'Overall Inspection Completed';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                }
                else{
                    $last_step_lbl = '---';
                }

                return $last_step_lbl;
            })

            ->addColumn('total_no_of_ng', function($prod_runcard){
                $total_no_of_ng = 0;

                if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                    $total_no_of_ng = 0;
                    for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                        $total_no_of_ng += $prod_runcard->prod_runcard_station_many_details[$index]->qty_ng;
                    }
                }

                return $total_no_of_ng;
            })
            ->addColumn('last_runcard_po_qty', function($prod_runcard){
               $last_runcard_po_qty = 0;

                if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                    $last_runcard_po_qty = 0;
                    for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                        $last_runcard_po_qty += $prod_runcard->prod_runcard_station_many_details[$index]->qty_output;
                    }
                }
                $last_runcard_po_qty += $prod_runcard->qty_output;
                return $last_runcard_po_qty;
            })
            ->addColumn('raw_setup_qualification', function($prod_runcard){
                $result = '';

                if($prod_runcard->setup_qualification == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_eng_qualification_id', function($prod_runcard){
                $result = '';

                if($prod_runcard->eng_qualification_info != null){
                    $result = $prod_runcard->eng_qualification_info->name;
                }
                else{
                    $result = 'N/A';
                }

                return $result;
            })
            ->addColumn('raw_qc_qualification', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_qualification == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_qc_qualified', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_qualified == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_qc_stamp_qualification_info', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_stamp_qualification_info != null){
                    $result = $prod_runcard->qc_stamp_qualification_info->name;
                }
                else{
                    $result = 'N/A';
                }

                return $result;
            })
            ->addColumn('raw_checkbox', function($prod_runcard){
                $result = '';
                $disabled = '';
                if($prod_runcard->status == 3){
                    $disabled = 'disabled';
                }
                $result = '<center><input type="checkbox" production-runcard-id="' . $prod_runcard->id . '" class="chkSelProdRuncard" ' . $disabled . '></center>';

                return $result;
            })
            ->addColumn('defect_rework_number', function($prod_runcard){
                $result = '';

                if($prod_runcard->defect_rework_number == ''){
                    $result = 'N/A';
                }
                else{
                    $result = $prod_runcard->defect_rework_number;
                }

                return $result;
            })
            // ->rawColumns(['raw_action', 'raw_status', 'total_no_of_ng', 'last_runcard_po_qty', 'raw_checkbox'])
            ->rawColumns(['raw_action', 'raw_status', 'raw_process', 'total_no_of_ng', 'last_runcard_po_qty', 'raw_checkbox']) //10072022
            ->make(true);
    }
    public function get_prod_runcard_by_po_per_runcard(Request $request){
        date_default_timezone_set('Asia/Manila');

        $prod_runcards = ProductionRuncard::with([
            'wbs_kitting',

            'prod_runcard_station_many_details.process_flow.process_name',

            'prod_runcard_station_many_details.process_flow' => function ($query) {
                $query->orderByRaw('CONVERT(SUBSTRING_INDEX(step, "-", 1), UNSIGNED INTEGER) DESC')
                    ->orderByRaw('RIGHT(step, LOCATE("-", step) - 1) DESC');
            },

            'prod_runcard_station_many_details' => function ($query) {
                $query->orderByRaw('CONVERT(SUBSTRING_INDEX(process_flow_id, "-", 1), UNSIGNED INTEGER) ASC')
                    ->orderByRaw('RIGHT(process_flow_id, LOCATE("-", process_flow_id) - 1) ASC');
            },

            'eng_qualification_info',
            'qc_stamp_qualification_info',
        ])
        ->where('po_no', $request->po_number)
        ->orderBy('id','DESC')
        ->whereNull('deleted_at');


        if( $request->selectionSearch == ''){
            $prodRuncardsFiltered = $prod_runcards->limit(50);
        }
        if( $request->selectionSearch == 'pending'){
            $prodRuncardsFiltered = $prod_runcards->where('status','<=',1);
        }
        if( $request->selectionSearch == 'done'){
            $prodRuncardsFiltered = $prod_runcards->where('status','>',1);
        }
        if( filled($request->runcardNo) ){
            $prodRuncardsFiltered = $prod_runcards->where('runcard_no',$request->runcardNo);
            // $prodRuncardsFiltered = $prod_runcards->where('runcard_no','ES25107-001');
        }
        $prodRuncardsFiltered = $prod_runcards->get();
        return DataTables::of($prodRuncardsFiltered)
            ->addColumn('raw_action', function($prod_runcard){

                $result='<button style="width:30px;" runcard-id="' . $prod_runcard->id . '" title="Open details" class="btnOpenRuncardDetails btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';

                $result .= ' <button style="width:30px;" runcard-id="' . $prod_runcard->id . '" runcard-no="' . $prod_runcard->runcard_no . '" title="Print QR Code" class="btnPrintRuncardNo btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

                return $result;
            })
            ->addColumn('raw_status', function($prod_runcard){
                $result = '<i title="On-going" class="far fa-clock text-secondary px-2"></i>';

                if($prod_runcard->status == 3){
                    $result = '<i title="Submitted" class="fas fa-check text-success px-2"></i>';
                }

                return $result;
            })
            ->addColumn('raw_process', function($prod_runcard){
                $last_step_lbl = '---';
                // if($prod_runcard->wbs_kitting->device_name == 'NP568-078-016-P-1 - (Burn-in)'){
                if($prod_runcard->wbs_kitting->device_name){

                    if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                        $last_step = 0;
                        $counter = 1;
                        $last_step_array = [];
                        $last_step_arrayxx = [];
                        for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                            $last_step += $prod_runcard->prod_runcard_station_many_details[$index]->process_flow->step;
                            $last_step_array[] = $prod_runcard->prod_runcard_station_many_details[$index]->process_flow->step;
                            $last_step_arrayxx[] = $prod_runcard->prod_runcard_station_many_details[$index]->process_flow->process_name->name;
                            $counter++;
                        }
                    }else{
                        return $last_step_lbl;
                    }


                    $last_step_array = array_pop($last_step_array);
                    $last_step_arrayxx = array_pop($last_step_arrayxx);

                    if ( $last_step_array == 1){
                        // $last_step_lbl = 'For #ME Installation / #ME Pressing';
                        $last_step_lbl = '(Pending) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 2){
                        // $last_step_lbl = 'For Insulator Assembly';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 3){
                        // $last_step_lbl = 'For CS Installation';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 4){
                        // $last_step_lbl = 'For CS Check';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 5){
                        // $last_step_lbl = 'For Auto Insertion / #MO Installation';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 6){
                        // $last_step_lbl = 'For #MO Press';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 7){
                        // $last_step_lbl = 'For First Visual Inspection';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 8){
                        // $last_step_lbl = 'For Full Assembly and Printing / Curing';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 9){
                        // $last_step_lbl = 'For Air Blowing';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 10){
                        // $last_step_lbl = 'For MPC';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 11){
                        // $last_step_lbl = 'For Final Visual Inspection';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 12){
                        // $last_step_lbl = 'For Over-all Inspection';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                    else if ( $last_step_array == 13){
                        // $last_step_lbl = 'Overall Inspection Completed';
                        $last_step_lbl = '(DONE) - '.$last_step_arrayxx;
                    }
                }
                else{
                    $last_step_lbl = '---';
                }

                return $last_step_lbl;
            })

            ->addColumn('total_no_of_ng', function($prod_runcard){
                $total_no_of_ng = 0;

                if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                    $total_no_of_ng = 0;
                    for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                        $total_no_of_ng += $prod_runcard->prod_runcard_station_many_details[$index]->qty_ng;
                    }
                }

                return $total_no_of_ng;
            })
            ->addColumn('last_runcard_po_qty', function($prod_runcard){
               $last_runcard_po_qty = 0;

                // if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                //     $last_runcard_po_qty = 0;
                //     for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                //         $last_runcard_po_qty += $prod_runcard->prod_runcard_station_many_details[$index]->qty_output;
                //     }
                // }
                // $last_runcard_po_qty += $prod_runcard->qty_output;

                /* 01222026 by Nessa */
                if ($prod_runcard->prod_runcard_station_many_details->isNotEmpty()) {
                    $last_runcard_po_qty =
                        $prod_runcard->prod_runcard_station_many_details->last()->qty_output;
                }

                return $last_runcard_po_qty;
            })
            ->addColumn('raw_setup_qualification', function($prod_runcard){
                $result = '';

                if($prod_runcard->setup_qualification == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_eng_qualification_id', function($prod_runcard){
                $result = '';

                if($prod_runcard->eng_qualification_info != null){
                    $result = $prod_runcard->eng_qualification_info->name;
                }
                else{
                    $result = 'N/A';
                }

                return $result;
            })
            ->addColumn('raw_qc_qualification', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_qualification == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_qc_qualified', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_qualified == 1){
                    $result = 'Yes';
                }
                else{
                    $result = 'No';
                }

                return $result;
            })
            ->addColumn('raw_qc_stamp_qualification_info', function($prod_runcard){
                $result = '';

                if($prod_runcard->qc_stamp_qualification_info != null){
                    $result = $prod_runcard->qc_stamp_qualification_info->name;
                }
                else{
                    $result = 'N/A';
                }

                return $result;
            })
            ->addColumn('raw_checkbox', function($prod_runcard){
                $result = '';
                $disabled = '';
                if($prod_runcard->status == 3){
                    $disabled = 'disabled';
                }
                $result = '<center><input type="checkbox" production-runcard-id="' . $prod_runcard->id . '" class="chkSelProdRuncard" ' . $disabled . '></center>';

                return $result;
            })
            ->addColumn('defect_rework_number', function($prod_runcard){
                $result = '';

                if($prod_runcard->defect_rework_number == ''){
                    $result = 'N/A';
                }
                else{
                    $result = $prod_runcard->defect_rework_number;
                }

                return $result;
            })
            // ->rawColumns(['raw_action', 'raw_status', 'total_no_of_ng', 'last_runcard_po_qty', 'raw_checkbox'])
            ->rawColumns(['raw_action', 'raw_status', 'raw_process', 'total_no_of_ng', 'last_runcard_po_qty', 'raw_checkbox']) //10072022
            ->make(true);
    }

    public function save_prod_material_list(Request $request){
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $user = User::where('employee_id', $request->employee_id)->get();

        if($user->count() > 0){
            if($user[0]->position == 4){
                DB::beginTransaction();
                try {

                    ProductionRuncard::where('id',$request->prod_runcard_id)
                            ->update(
                                [
                                    'status' => 2,
                                    'last_updated_by' => Auth::user()->id,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]
                            );

                    if(isset($request->material_issuance)){
                        for($index = 0; $index < count($request->material_issuance); $index++){
                            ProdRuncardMaterialList::insert([
                                'prod_runcard_id' => $request->prod_runcard_id,
                                'status' => 1,
                                'issuance_id' => $request->material_issuance[$index],
                                'tbl_wbs' => 1,
                                'status' => 1,
                            ]);
                        }
                    }

                    if(isset($request->sakidashi_issuance)){
                        for($index = 0; $index < count($request->sakidashi_issuance); $index++){
                            ProdRuncardMaterialList::insert([
                                'prod_runcard_id' => $request->prod_runcard_id,
                                'status' => 1,
                                'issuance_id' => $request->sakidashi_issuance[$index],
                                'tbl_wbs' => 2,
                                'status' => 1,
                            ]);
                        }
                    }

                    DB::commit();
                    $result = true;

                } catch (Exception $e) {
                    DB::rollback();
                    $result = false;
                }

                if( !$result ){
                    $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                    $return_body = 'An error occured. The record not saved.';
                }
                $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                return $return;
            }
            else{
                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                $return_body = ' Scanned QR Code is not Operator.';
                $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                return $return;
            }
        }
        else{
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = ' Invalid Employee No.';
            $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
            return $return;
        }
    }

    // public function save_prod_emboss_material_list(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
    //     $return_body = 'Record has been saved.';
    //     $result = false;

    //     $user = User::where('employee_id', $request->employee_id)->get();

    //     if($user->count() > 0){
    //         if($user[0]->position == 4){
    //             DB::beginTransaction();
    //             try {

    //                 // ProductionRuncard::where('id',$request->prod_runcard_id)
    //                 //         ->update(
    //                 //             [
    //                 //                 'status' => 2,
    //                 //                 'last_updated_by' => Auth::user()->id,
    //                 //                 'updated_at' => date('Y-m-d H:i:s')
    //                 //             ]
    //                 //         );

    //                 if(isset($request->emboss_issuance)){
    //                     for($index = 0; $index < count($request->emboss_issuance); $index++){
    //                         ProdRuncardMaterialList::insert([
    //                             'prod_runcard_id' => $request->prod_runcard_id,
    //                             'status' => 1,
    //                             'issuance_id' => $request->emboss_issuance[$index],
    //                             'tbl_wbs' => 2,
    //                             'for_emboss' => 1,
    //                             'status' => 1,
    //                         ]);
    //                     }
    //                 }

    //                 DB::commit();
    //                 $result = true;

    //             } catch (Exception $e) {
    //                 DB::rollback();
    //                 $result = false;
    //             }

    //             if( !$result ){
    //                 $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
    //                 $return_body = 'An error occured. The record not saved.';
    //             }
    //             $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
    //             return $return;
    //         }
    //         else{
    //             $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
    //             $return_body = ' Scanned QR Code is not Operator.';
    //             $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
    //             return $return;
    //         }
    //     }
    //     else{
    //         $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
    //         $return_body = ' Invalid Employee No.';
    //         $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
    //         return $return;
    //     }
    // }

    // public function get_wbs_material_kitting(Request $request){
    // date_default_timezone_set('Asia/Manila');
    //     $material_kitting = MaterialIssuanceSubSystem::with([
    //                                         'device_info',
    //                                         // 'documents_details',
    //                                     ])
    //                                     ->where('po_no', $request->po_number)
    //                                     ->first();

    //     $doc_details_query = [];
    //     $doc_a_drawing_query = [];
    //     $doc_g_drawing_query = [];

    //     // if($material_kitting != null){
    //     //     $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $material_kitting->device_name . '%')
    //     //         ->get();
    //     //     $doc_a_drawing_query = collect($doc_details_query)->where('doc_type', 'A Drawing')->flatten(1);
    //     //     $doc_g_drawing_query = collect($doc_details_query)->where('doc_type', 'G Drawing')->flatten(1);
    //     // }

    //     return response()->json(['material_kitting' => $material_kitting, 'doc_a_drawing_query' => $doc_a_drawing_query, 'doc_g_drawing_query' => $doc_g_drawing_query]);
    // }

    public function get_wbs_material_kitting(Request $request){
        date_default_timezone_set('Asia/Manila');
        $material_kitting = MaterialIssuanceSubSystem::with([
                                            'device_info',
                                            'documents_details',
                                            'material_issuance_details' => function($query){
                                                $query->limit(1);
                                            },
                                        ])
                                        ->orderby('id', 'desc')
                                        ->where('po_no', $request->po_number)
                                        ->first();
        $doc_details_query = [];
        $doc_a_drawing_query = [];
        $doc_g_drawing_query = [];
        $matrix = [];

        if($material_kitting != null){
            $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $material_kitting->device_name . '%')
                ->get();
            $doc_a_drawing_query = collect($doc_details_query)->where('doc_type', 'A Drawing')->flatten(1);
            $doc_g_drawing_query = collect($doc_details_query)->where('doc_type', 'G Drawing')->flatten(1);

            $matrix = CommonController::getInstance()->getMatrixByPoNo([
                'po_no'=> $request->po_number,
                'name'=> $material_kitting->device_name,
                'get_or_first'=> '',
            ]);
            // $matrix = Device::where('name', $material_kitting->device_name)->get();

        }
        if(blank($request->po_number)){
             return response()->json(['material_kitting' => null, 'doc_a_drawing_query' => [], 'doc_g_drawing_query' => [], 'matrix' => [1]]);
        }

        return response()->json(['material_kitting' => $material_kitting, 'doc_a_drawing_query' => $doc_a_drawing_query, 'doc_g_drawing_query' => $doc_g_drawing_query, 'matrix' => $matrix]);
        // return response()->json(['material_kitting' => $material_kitting, 'doc_a_drawing_query' => $doc_a_drawing_query, 'doc_g_drawing_query' => $doc_g_drawing_query]);
    }

    public function get_wbs_sakidashi_issuance(Request $request){
        date_default_timezone_set('Asia/Manila');
        $sakidashi_issuance = WBSSakidashiIssuance::where('po_no', $request->po_number)
                                        ->first();

        return response()->json(['sakidashi_issuance' => $sakidashi_issuance]);
    }

    public function get_prod_runcard_by_id1(Request $request){
        date_default_timezone_set('Asia/Manila');
        // return DefectEscalationV2Rev::where('prod_runcard_id',)
        $prod_runcard = ProductionRuncard::with([
                            'prod_runcard_material_list' => function($query){
                                $query->where('status', 1);
                            },
                            'supervisor_prod_info',
                            'supervisor_qc_info',
                            'eng_qualification_info',
                            'qc_stamp_qualification_info',
                            'qc_info',
                            'opt_info',
                            'mpc_opt_info',
                            'wbs_kitting',
                            'assembly_line', // mdr
                            'defect_escalation_v2_rev',
                        ])
                        ->where('id', $request->prod_runcard_id)
                        ->first();

        return response()->json(['prod_runcard' => $prod_runcard,'defects' => $prod_runcard]);
    }
    public function get_prod_runcard_by_id(Request $request){
        date_default_timezone_set('Asia/Manila');
        // return DefectEscalationV2Rev::where('prod_runcard_id',)
        $prod_runcard = ProductionRuncard::with([
                                'prod_runcard_material_list' => function($query){
                                    $query->where('status', 1);
                                },
                                'supervisor_prod_info',
                                'supervisor_qc_info',
                                'eng_qualification_info',
                                'qc_stamp_qualification_info',
                                'qc_info',
                                'opt_info',
                                'mpc_opt_info',
                                'wbs_kitting',
                                'assembly_line', // mdr
                                'defect_escalation_v2_rev',
                            ])
                            ->where('id', $request->prod_runcard_id)
                            ->first();

        $collect = collect($prod_runcard->defect_escalation_v2_rev)->map(function($row){
            return $row->defect_ctrl_txt.'-'.$row->defect_ctrl_number;
        })->toArray();
        if(count( $collect) !=0){
            $collectDefects = implode(' | ',$collect);
        }else{
            $collectDefects = '';
        }
        return response()->json(['prod_runcard' => $prod_runcard,'defects' => $collectDefects]);
    }

    public function view_ng_summary(Request $request){
        date_default_timezone_set('Asia/Manila');
        $ng_summaries = ProductionRuncardStationMOD::with([
                        'production_runcard_details' => function($query) use($request){
                            $query->where('po_no', $request->po_no);
                        },
                        'mod_details' => function($query) use($request){
                            $query->where('status', 1);
                        },
                    ])
                    // ->having('production_runcard_details', '=', null)
                    ->where('status', 1)
                    ->get();

        $collected_ng_summary = collect($ng_summaries)
                ->where('production_runcard_details', '!=', null)
                ->flatten(1);

        return DataTables::of($collected_ng_summary)
            ->addColumn('label1', function($ng_summary){
                $result = "";

                return $result;
            })
            ->rawColumns(['label1'])
            ->make(true);
    }

    public function test_prod_runcard_station_step_num(Request $request){
        date_default_timezone_set('Asia/Manila');
        // $prod_runcards = ProductionRuncard::with([
        //                     'prod_runcard_station_many_details' => function($query){
        //                         // $query->where('has_emboss', '!=', 1);
        //                         $query->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'));
        //                         $query->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'));
        //                     }
        //                 ])
        //                 ->where('po_no', $request->po_number)
        //                 ->get();

        $step_num = '14';

        $step_num = explode('-', $step_num)[0];

        $prod_runcard_stations = ProductionRuncardStation::orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                                ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'))
                                ->where('production_runcard_id', 53)
                                ->get();

        $prod_runcard_station_step_num = [];
        $prod_runcard_station_id = [];

        if(count($prod_runcard_stations) > 0){
            for($index = 0; $index < count($prod_runcard_stations); $index++){
                $prod_runcard_station_step_num[] = explode('-', $prod_runcard_stations[$index]->step_num)[0];
                $prod_runcard_station_id[] = $prod_runcard_stations[$index]->id;
            }
        }

        $prod_runcard_station_step_num = array_unique($prod_runcard_station_step_num);

        $station_unique = [];
        $station_unique_key = [];

        foreach ($prod_runcard_station_step_num as $prod_runcard_station_step_num_unique => $value) {
            if($step_num > $value){
                $station_unique_key[] = $prod_runcard_station_step_num_unique;
                $station_unique[] = $value;
            }
        }

        $final_station_id = [];

        if(count($prod_runcard_station_id) > 0){
            for($index = 0; $index < count($prod_runcard_station_id); $index++){
                // if(array_search($index, $station_unique_key)){
                //     $final_station_id[] = $prod_runcard_station_id[$index];
                // }

                for($index2 = 0; $index2 < count($station_unique_key); $index2++){
                    if($station_unique_key[$index2] == $index){
                        $final_station_id[] = $prod_runcard_station_id[$index];
                    }
                }
            }
        }

        return response()->json(['station_unique_key' => $station_unique_key, 'station_unique' => $station_unique, 'station_id' => $prod_runcard_station_id, 'final_station_id' => $final_station_id]);
    }

    public function generate_reel_lot_no(Request $request){
        date_default_timezone_set('Asia/Manila');

        // Ex of Lot no : 9Z20-01X

        // 9= Last number of the year
        // Z= Packing Month (1,2,3,4,5,6,7,8,9,X,Y,Z)
        // 20=Packing date(01～31)
        // 01= Serial number of tray bundles in 1 box
        // X = Lot # Machine code from Packing Matrix; if tray use 0

        // $request->device_name = 'CA009-S101-001(01)';
        // $request->device_name = 'CN109S-022-0001(H1)';

        $arr_packing_months = [1,2,3,4,5,6,7,8,9,'X','Y','Z'];
        $final_reel_lot_no = "";

        $last_no_of_the_year = date('Y')[3];
        $packing_month = $arr_packing_months[(integer)date('m') - 1];
        $packing_date = date('d');
        $serial_no = str_pad(1,2,"0", STR_PAD_LEFT);
        $lot_no_machine_code = '0';

        $device_info = Device::where('name', $request->device_name)->first();

        if($device_info != null){
            if($device_info->lot_no_machine_code != null){
                $lot_no_machine_code = $device_info->lot_no_machine_code;
            }
        }

        $final_reel_lot_no = $last_no_of_the_year . $packing_month . $packing_date . '-'. $serial_no . $lot_no_machine_code;
        $explode_reel_lot_no = explode('-', $final_reel_lot_no)[0];

        $prod_runcard_reel_lots = [];
        $prod_runcard_reel_lots = ProductionRuncard::with([
                                        'wbs_kitting_has_many' => function($query) use ($request){
                                            $query->where('device_name', $request->device_name);
                                            $query->orderBy('id', 'desc');
                                            $query->limit(1);
                                        }
                                    ])
                                    ->where('po_no', $request->txt_po_number)
                                    ->where('reel_lot_no', 'LIKE', $explode_reel_lot_no . "%")
                                    ->orderBy('id', 'desc')
                                    ->first();

        // return $prod_runcard_reel_lots;

        if($prod_runcard_reel_lots != null){
            if($prod_runcard_reel_lots->wbs_kitting_has_many != null){
                if($prod_runcard_reel_lots != null){
                    $exploded_prod_runcard_reel_lot_no = explode('-', $prod_runcard_reel_lots->reel_lot_no);

                    $serial_no = (integer)($exploded_prod_runcard_reel_lot_no[1][0] . $exploded_prod_runcard_reel_lot_no[1][1]) + 1;
                    $serial_no = str_pad($serial_no, 2, "0", STR_PAD_LEFT);

                    $final_reel_lot_no = $exploded_prod_runcard_reel_lot_no[0] . '-' . $serial_no . $lot_no_machine_code;

                    return $final_reel_lot_no;
                }
            }
        }


        return $final_reel_lot_no;
    }

    // --------------------- NEW FUNCTIONS --------------------------
    // USED IN AUTOMATIC PRODUCTION RUNCARD
    public function view_material_kitting_by_runcard(Request $request){
        $material_lists = [];

        if(isset($request->prod_runcard_id_query)){
            $prod_runcard_mat_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
                    ->where('tbl_wbs', 1)
                    ->where('status', 1)
                    ->get();

            $arr_issuance_id = $prod_runcard_mat_list->pluck('issuance_id');

            if(count($prod_runcard_mat_list) > 0){
                $material_lists = WBSKitIssuance::with([
                        'kit_issuance',
                        'kit_issuance.device_info',
                        'kit_issuance.device_info.material_process',
                        'kit_issuance.device_info.material_process.station_sub_station',
                        'kit_issuance.device_info.material_process.station_sub_station.station',
                        'parts_prep_info' => function($query){
                            $query->where('wbs_table', 1);
                            $query->where('deleted_at', null);
                        },
                    ])
                ->where('po','=',$request['po_number'])
                ->whereIn('id', $arr_issuance_id)
                ->get();
            }
        }


        return DataTables::of($material_lists)
            ->addColumn('raw_status', function($material_list){
                $disabled = 'disabled';
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
                $title = "";
                $parts_prep_done = false;

                if ( $material_list->parts_prep_info != null ) {
                    if( $material_list->parts_prep_info->with_partsprep == 1){
                        switch ( $material_list->parts_prep_info->status ) {
                            case 1:
                                $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 2:
                                $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
                                break;
                            case 3:
                                $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 4:
                                $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 5:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;
                            case 6:
                                $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 7:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                    else{
                        $result = '<i title="Saved" class="fas fa-cogs text-success px-2"></i>';
                        $parts_prep_done = true;
                    }
                }
                else{
                    $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
                }
                $result.=' <input type="hidden" class="col_material_id" value="'.$material_list->id.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$material_list->device_code.'">';

                if($parts_prep_done == true){
                    $result.=' <input type="hidden" class="col_lot_id" value="'.$material_list->id.'">';
                }

                return $result;
            })
            ->rawColumns(['raw_action', 'raw_status'])
            ->make(true);
    }

    public function view_sakidashi_by_runcard(Request $request){
        date_default_timezone_set('Asia/Manila');
        $sakidashi_list = [];

        if(isset($request->prod_runcard_id_query)){
            $prod_runcard_sak_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
                    ->where('tbl_wbs', 2)
                    // ->where('for_emboss', 0)
                    ->where('status', 1)
                    ->get();

            $arr_issuance_id = $prod_runcard_sak_list->pluck('issuance_id');

            if(count($prod_runcard_sak_list) > 0){
                $sakidashi_list = WBSSakidashiIssuance::with([
                        'tbl_wbs_sakidashi_issuance_item',
                        'device_info',
                        'device_info.material_process',
                        'device_info.material_process.station_sub_station',
                        'device_info.material_process.station_sub_station.station',
                        'parts_prep_info' => function($query){
                            $query->where('wbs_table', 2);
                            $query->where('deleted_at', null);
                        },
                    ])
                ->where('po_no','=',$request['po_number'])
                ->whereIn('id', $arr_issuance_id)
                ->get();
            }
        }

        return DataTables::of($sakidashi_list)
            ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
                $disabled = 'disabled';
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
                $title = "";
                $parts_prep_done = false;

                if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
                    if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
                        switch ( $tbl_wbs_sakidashi_issuance->parts_prep_info->status ) {
                            case 1:
                                $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 2:
                                $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
                                break;
                            case 3:
                                $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 4:
                                $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 5:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;
                            case 6:
                                $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 7:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;

                            default:
                                # code...
                                break;
                        }
                    }                                    else{
                        $result = '<i title="Ready for Prod\'n" class="fas fa-cogs text-success px-2"></i>';
                        $parts_prep_done = true;
                    }
                }
                else{
                    $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
                }

                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

                if($parts_prep_done == true){
                    $result.=' <input type="hidden" class="col_lot_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                }

                return $result;
            })
            // ->addColumn('action', function($tbl_wbs_sakidashi_issuance) use ($runcard_status, $chkDisable){
            //     $disabled = 'disabled';
            //     $result = "";
            //     $title = "";
            //     $has_parts_prep = false;
            //     $has_prod = false;

            //     if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
            //         if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
            //             if( $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 5 || $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 7 ){ //Not approved in parts prep
            //                 $disabled = '';
            //             }
            //             else{
            //                 $disabled = 'disabled';
            //             }
            //         }
            //         else{
            //             $disabled = '';
            //         }
            //     }
            //     else{
            //         $disabled = 'disabled';
            //     }

            //     if($runcard_status == 1){
            //         $disabled = 'disabled';
            //     }

            //     if($chkDisable){
            //         $disabled = 'disabled';
            //     }

            //     $result='<center><input sakidashi-issue-id="' . $tbl_wbs_sakidashi_issuance->id . '" type="checkbox" '. $disabled .' title="Check to select" class="py-0 chkSelSakidashiIssue" style="display: block;"></center>';

            //     $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
            //     $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

            //     return $result;
            // })
            ->addColumn('runcard_used_qty', function($tbl_wbs_sakidashi_issuance){
                $result = 0;
                if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
                    for($index = 0; $index < count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list); $index++){
                        if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details != null){
                            if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details)){
                                $result += $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details[0]->qty_output;
                            }
                        }
                    }
                }
                return $result;
            })
            ->addColumn('lot_qty_to_complete', function($tbl_wbs_sakidashi_issuance){
                $result = 0;
                if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
                    if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details != null){
                        $result = $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details->lot_qty;
                    }
                }
                return $result;
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    // public function view_emboss_by_runcard(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     $sakidashi_list = [];

    //     if(isset($request->prod_runcard_id_query)){
    //         $prod_runcard_sak_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
    //                 ->where('tbl_wbs', 2)
    //                 ->where('for_emboss', 1)
    //                 ->where('status', 1)
    //                 ->get();

    //         $arr_issuance_id = $prod_runcard_sak_list->pluck('issuance_id');

    //         if(count($prod_runcard_sak_list) > 0){
    //             $sakidashi_list = WBSSakidashiIssuance::with([
    //                     'tbl_wbs_sakidashi_issuance_item',
    //                     'device_info',
    //                     'device_info.material_process',
    //                     'device_info.material_process.station_sub_station',
    //                     'device_info.material_process.station_sub_station.station',
    //                     'parts_prep_info' => function($query){
    //                         $query->where('wbs_table', 2);
    //                         $query->where('deleted_at', null);
    //                     },
    //                 ])
    //             ->where('po_no','=',$request['po_number'])
    //             ->whereIn('id', $arr_issuance_id)
    //             ->get();
    //         }
    //     }

    //     return DataTables::of($sakidashi_list)
    //         ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
    //             $disabled = 'disabled';
    //             $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
    //             $title = "";
    //             $parts_prep_done = false;

    //             if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
    //                 if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
    //                     switch ( $tbl_wbs_sakidashi_issuance->parts_prep_info->status ) {
    //                         case 1:
    //                             $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
    //                             break;
    //                         case 2:
    //                             $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
    //                             break;
    //                         case 3:
    //                             $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
    //                             break;
    //                         case 4:
    //                             $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
    //                             break;
    //                         case 5:
    //                             $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
    //                             $parts_prep_done = true;
    //                             break;
    //                         case 6:
    //                             $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
    //                             break;
    //                         case 7:
    //                             $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
    //                             $parts_prep_done = true;
    //                             break;

    //                         default:
    //                             # code...
    //                             break;
    //                     }
    //                 }                                    else{
    //                     $result = '<i title="Ready for Prod\'n" class="fas fa-cogs text-success px-2"></i>';
    //                     $parts_prep_done = true;
    //                 }
    //             }
    //             else{
    //                 $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
    //             }

    //             $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
    //             $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

    //             if($parts_prep_done == true){
    //                 $result.=' <input type="hidden" class="col_lot_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('runcard_used_qty', function($tbl_wbs_sakidashi_issuance){
    //             $result = 0;
    //             if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
    //                 for($index = 0; $index < count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list); $index++){
    //                     if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details != null){
    //                         if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details)){
    //                             $result += $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details[0]->qty_output;
    //                         }
    //                     }
    //                 }
    //             }
    //             return $result;
    //         })
    //         ->addColumn('lot_qty_to_complete', function($tbl_wbs_sakidashi_issuance){
    //             $result = 0;
    //             if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
    //                 if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details != null){
    //                     $result = $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details->lot_qty;
    //                 }
    //             }
    //             return $result;
    //         })
    //         ->rawColumns(['status'])
    //         ->make(true);
    // }

    public function scan_material_kitting_lot_no(Request $request){
        date_default_timezone_set('Asia/Manila');

        $prep_status_label = 'Not Received For this PO #';
        $runcard_material_status_label = 'Not Received';
        $prep_status = false;
        $runcard_material_status = false;
        $material_certified = false;
        $material_certified_label = 'Not Certified';
        $arr_insert_issuance_id = [];
        $mat_proc_device = [];
        $final_result = 0;
        $validator_error = [];
        $assessment_no = "";
        $material_process = [];

        $runcard_id = null;
        if(isset($request->runcard_id)){
            $runcard_id = $request->runcard_id;
        }

        $parts_prep_kit_issuance = WBSKitIssuance::with([
                        'parts_prep_info',
                        'parts_prep_info.prod_runcard_material_list' => function($query) use($runcard_id){
                            $query->where('prod_runcard_id', $runcard_id);
                            $query->where('tbl_wbs', 1);
                            // $query->where('for_emboss', 0);
                        },
                        'kit_issuance' => function($query) use($request){
                            // $query->where('issuance_no', $request->whs_slip_no);
                        },
                    ])
                    ->where('lot_no', $request->lot_no)
                    ->where('po', $request->po_number)
                    // ->where('issue_no', $request->whs_slip_no)
                    ->get();

        $col_parts_prep_kit_issuance = collect($parts_prep_kit_issuance)->where('parts_prep_info', '!=', null);

        if(count($col_parts_prep_kit_issuance) > 0){
            for($index = 0; $index < count($col_parts_prep_kit_issuance); $index++){
                $prep_status = false;
                $runcard_material_status = false;
                if($col_parts_prep_kit_issuance[$index]->parts_prep_info->wbs_table == 1){
                    if($col_parts_prep_kit_issuance[$index]->parts_prep_info->with_partsprep == 0){
                        // if($col_parts_prep_kit_issuance[$index]->parts_prep_info->status == 1){
                            $prep_status_label = 'Parts Prep Done';
                            $prep_status = true;
                        // }
                    }
                    else{
                        if($col_parts_prep_kit_issuance[$index]->parts_prep_info->status == 5 || $col_parts_prep_kit_issuance[$index]->parts_prep_info->status == 7){
                            $prep_status_label = 'Parts Prep Done';
                            $prep_status = true;
                        }
                        else{
                            $prep_status_label = 'Parts Prep Ongoing';
                            $prep_status = false;
                        }
                    }

                    if($prep_status){
                        if(count($col_parts_prep_kit_issuance[$index]->parts_prep_info->prod_runcard_material_list) > 0){
                            $runcard_material_status_label = 'Already Exist';
                            $runcard_material_status = false;
                        }
                        else{
                            $runcard_material_status_label = 'Ready to Insert';
                            $runcard_material_status = true;
                        }
                    }
                    else{
                        $runcard_material_status_label = 'Not Received';
                    }
                }

                $item = $col_parts_prep_kit_issuance[$index]->item;
                $item_desc = $col_parts_prep_kit_issuance[$index]->item_desc;
                $assessment_no = $col_parts_prep_kit_issuance[$index]->kit_issuance->assessment;

                $mat_proc_device = Device::with([
                                    'material_process' => function($query){
                                            $query->where('status', 1);
                                    },
                                    'material_process.material_details' => function($query) use($item, $item_desc){
                                        $query->where('status', 1);
                                        $query->where('tbl_wbs', 1);
                                        $query->where('item', $item);
                                        $query->where('item_desc', $item_desc);
                                    },
                                ])
                                ->where('name', $request->device_name)
                                ->first();

                if($mat_proc_device != null){
                    if(count($mat_proc_device->material_process) > 0){
                        $material_certified = true;
                        $material_certified_label = 'Certified';

                        if($runcard_id != null){
                            if($prep_status && $runcard_material_status){
                                $arr_insert_issuance_id[] = $col_parts_prep_kit_issuance[$index]->id;
                            }
                        }
                        else{
                            if($prep_status){
                                $arr_insert_issuance_id[] = $col_parts_prep_kit_issuance[$index]->id;
                            }
                        }
                    }
                    else{
                        $material_certified = false;
                        $material_certified_label = 'Not Certified';
                    }
                }
                else{
                    $material_certified = false;
                    $material_certified_label = 'Not Certified';
                }

            }

            if(count($arr_insert_issuance_id) > 0){
                // Insert Runcard & Material
                if($runcard_id == null){
                    $data = $request->all();

                    $validator = Validator::make($data, [
                        'po_number' => ['required'],
                        'lot_qty' => ['required'],
                        'a_drawing' => ['required'],
                        'a_drawing_rev' => ['required'],
                        'g_drawing' => ['required'],
                        'g_drawing_rev' => ['required'],
                    ]);

                    $material_process = [];

                    if($validator->passes()){
                         $device = Device::where('name', $request->device_name)->first();

                         if($device != null){
                            $material_process = MaterialProcess::with([
                                'station_sub_station' => function($query){
                                    $query->where('status', 1);
                                },
                                'station_sub_station.station' => function($query){
                                    $query->where('status', 1);
                                    $query->where('station_type', 2);
                                },
                                'station_sub_station.sub_station' => function($query){
                                    $query->where('status', 1);
                                },
                                'material_details' => function($query){
                                    $query->where('status', 1);
                                },
                                'machine_details' => function($query){
                                    $query->where('status', 1);
                                },
                            ])
                            ->where('device_id', $device->id)
                            ->where('status', 1)
                            ->orderBy('step', 'asc')
                            ->get();

                            if(count($material_process) > 0){
                                // $require_oqc_before_emboss = 0;
                                $collect_material_process = collect($material_process)->flatten(1);
                                // $collect_material_process =  $collect_material_process->where('require_oqc_before_emboss', 1);

                                // if(count($collect_material_process) > 0){
                                //     $require_oqc_before_emboss = 1;
                                // }

                                $runcard_no = substr($request->po_number, 0, -5);
                                $prod_runcard_id = "";

                                if($runcard_id == null){
                                    //insert
                                    DB::beginTransaction();
                                    try {
                                        $arr_packing_months = [1,2,3,4,5,6,7,8,9,'X','Y','Z'];
                                        $final_reel_lot_no = "";

                                        $last_no_of_the_year = date('Y')[3];
                                        $packing_month = $arr_packing_months[(integer)date('m') - 1];
                                        $packing_date = date('d');
                                        $serial_no = str_pad(1,2,"0", STR_PAD_LEFT);
                                        $lot_no_machine_code = '0';

                                        $device_info = Device::where('name', $request->device_name)->first();

                                        if($device_info != null){
                                            if($device_info->lot_no_machine_code != null){
                                                $lot_no_machine_code = $device_info->lot_no_machine_code;
                                            }
                                        }
                                        $final_reel_lot_no = $last_no_of_the_year . $packing_month . $packing_date . '-'. $serial_no . $lot_no_machine_code;
                                        $explode_reel_lot_no = explode('-', $final_reel_lot_no)[0];

                                        $prod_runcard_reel_lots = [];
                                        $prod_runcard_reel_lots = ProductionRuncard::with([
                                                                        'wbs_kitting_has_many' => function($query) use ($request){
                                                                            $query->where('device_name', $request->device_name);
                                                                            $query->orderBy('id', 'desc');
                                                                            $query->limit(1);
                                                                        }
                                                                    ])
                                                                    ->where('po_no', $request->po_number)
                                                                    ->where('reel_lot_no', 'LIKE', $explode_reel_lot_no . "%")
                                                                    ->orderBy('id', 'desc')
                                                                    ->first();

                                        if($prod_runcard_reel_lots != null){
                                            if($prod_runcard_reel_lots->wbs_kitting_has_many != null){
                                                if($prod_runcard_reel_lots != null){
                                                    $exploded_prod_runcard_reel_lot_no = explode('-', $prod_runcard_reel_lots->reel_lot_no);

                                                    $serial_no = (integer)($exploded_prod_runcard_reel_lot_no[1][0] . $exploded_prod_runcard_reel_lot_no[1][1]) + 1;
                                                    $serial_no = str_pad($serial_no, 2, "0", STR_PAD_LEFT);

                                                    $final_reel_lot_no = $exploded_prod_runcard_reel_lot_no[0] . '-' . $serial_no . $lot_no_machine_code;
                                                }
                                            }
                                        }

                                        // Generate Runcard No
                                        $prod_runcards = ProductionRuncard::where('runcard_no', 'LIKE', $runcard_no . "%")->orderBy('id', 'desc')->limit(1)->get();

                                        $final_runcard_no = "";
                                        $explode_runcard_no = "";

                                        if($prod_runcards->count() > 0){
                                            $explode_runcard_no = explode('-', $prod_runcards[0]->runcard_no);

                                            $explode_runcard_no[1] = str_pad($explode_runcard_no[1] + 1,3,"0", STR_PAD_LEFT);

                                            $final_runcard_no = implode('-', $explode_runcard_no);
                                        }
                                        else{
                                            $final_runcard_no = substr($request->po_number, 0, -5) . '-001';
                                        }

                                        // if(isset($request->has_emboss)){
                                        //     $has_emboss = $request->has_emboss;
                                        // }
                                        // else{
                                        //     $has_emboss = 0;
                                        // }

                                        $prod_runcard_id = ProductionRuncard::insertGetId([
                                            'po_no'=> $request->po_number,
                                            'status'=> 2,
                                            'runcard_no' => $final_runcard_no,
                                            'reel_lot_no' => $final_reel_lot_no,
                                            'lot_qty' => $request->lot_qty,
                                            'assessment_no'=> $assessment_no,
                                            'a_drawing_no'=> $request->a_drawing,
                                            'a_drawing_rev'=> $request->a_drawing_rev,
                                            'g_drawing_no'=> $request->g_drawing,
                                            'g_drawing_rev'=> $request->g_drawing_rev,
                                            // 'has_emboss'=> $has_emboss,
                                            // 'require_oqc_before_emboss' => $require_oqc_before_emboss,
                                            'comp_under_runcard_no'=> 0,
                                            'created_by' => Auth::user()->id,
                                            'last_updated_by' => Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ]);

                                        $runcard_id = $prod_runcard_id;

                                        for($index = 0; $index < count($material_process); $index++){
                                            if($material_process[$index]->station_sub_station->station != null){

                                                $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                                    'production_runcard_id'=> $prod_runcard_id,
                                                    'mat_proc_id'=> $material_process[$index]->id,
                                                    'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                                    'sub_station_id'=> $material_process[$index]->station_sub_station->sub_station_id,
                                                    'step_num'=> $material_process[$index]->step,
                                                    // 'has_emboss'=> $material_process[$index]->has_emboss,
                                                    'created_by'=> Auth::user()->id,
                                                    'last_updated_by'=> Auth::user()->id
                                                ]);

                                                // Insert Machine Per Process
                                                if(count($material_process[$index]->machine_details) > 0){
                                                    for($index2 = 0; $index2 < count($material_process[$index]->machine_details); $index2++){
                                                        // if($material_process[$index]->machine_details[$index2]-> == ){
                                                            ProductionRuncardStationMachine::insert([
                                                                'production_runcard_id' => $runcard_id,
                                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                                'machine_id' => $material_process[$index]->machine_details[$index2]->machine_id,
                                                            ]);
                                                        // }
                                                    }
                                                }

                                                // Insert Material Per Process
                                                $issuance_id = null;

                                                for($index2 = 0; $index2 < count($col_parts_prep_kit_issuance); $index2++){
                                                    $issuance_id = $col_parts_prep_kit_issuance[$index2]->id;

                                                    if(count($material_process[$index]->material_details) > 0){
                                                        for($index3 = 0; $index3 < count($material_process[$index]->material_details); $index3++){
                                                            if(($col_parts_prep_kit_issuance[$index2]->item == $material_process[$index]->material_details[$index3]->item) && ($col_parts_prep_kit_issuance[$index2]->item_desc == $material_process[$index]->material_details[$index3]->item_desc)){

                                                                $station_materials = ProductionRuncardStationMaterial::where('production_runcard_station_id', $prod_runcard_station_id)
                                                                    ->where('issuance_id', $issuance_id)
                                                                    ->get();

                                                                if(count($station_materials) <= 0){
                                                                    ProductionRuncardStationMaterial::insert([
                                                                        'production_runcard_id' => $runcard_id,
                                                                        'production_runcard_station_id' => $prod_runcard_station_id,
                                                                        'issuance_id' => $issuance_id,
                                                                        'item' => $col_parts_prep_kit_issuance[$index2]->item,
                                                                        'item_desc' => $col_parts_prep_kit_issuance[$index2]->item_desc,
                                                                        'tbl_wbs' => 1,
                                                                        // 'has_emboss' => 0,
                                                                        'status' => 1,
                                                                        'created_by' => Auth::user()->id,
                                                                        'last_updated_by' => Auth::user()->id,
                                                                        'update_version' => 1,
                                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                                        'created_at' => date('Y-m-d H:i:s')
                                                                    ]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }


                                                // if($material_process[$index]->has_emboss == 1){
                                                //     $has_emboss = 1;
                                                // }
                                            }
                                        }

                                        for($index = 0; $index < count($arr_insert_issuance_id); $index++){
                                            ProdRuncardMaterialList::insert([
                                                'prod_runcard_id' => $prod_runcard_id,
                                                'status' => 1,
                                                'issuance_id' => $arr_insert_issuance_id[$index],
                                                'tbl_wbs' => 1,
                                                'status' => 1,
                                            ]);
                                        }

                                        DB::commit();

                                        // ProductionRuncard::where('id', $prod_runcard_id)
                                        //     ->update([
                                        //         'has_emboss'=> $has_emboss,
                                        //     ]);

                                        $final_result = true;
                                    }
                                    catch (Exception $e) {
                                        DB::rollback();
                                        $final_result = 0;
                                        $validator_error = $e->messages();
                                    }
                                }
                            }

                        }
                    }
                    else{
                        $final_result = 0;
                        $validator_error = $validator->messages();
                    }

                }
                else{
                    // INSERT MATERIAL ONLY
                    DB::beginTransaction();
                    try {
                        // for($index = 0; $index < count($arr_insert_issuance_id); $index++){
                        //     ProdRuncardMaterialList::insert([
                        //         'prod_runcard_id' => $runcard_id,
                        //         'status' => 1,
                        //         'issuance_id' => $arr_insert_issuance_id[$index],
                        //         'tbl_wbs' => 1,
                        //         'status' => 1,
                        //     ]);

                        // }

                        $certified_mat_proc_id = [];

                        $arr_station_material_issuance_id = [];
                        for($index = 0; $index < count($col_parts_prep_kit_issuance); $index++){
                            $issuance_id = $col_parts_prep_kit_issuance[$index]->id;
                            $item = $col_parts_prep_kit_issuance[$index]->item;
                            $item_desc = $col_parts_prep_kit_issuance[$index]->item_desc;


                            $mat_proc_materials = CommonController::getInstance()->getMatrixByPoNo([
                                    'po_no' => $request->po_no,
                                    'po_number' => $request->po_number,
                                    'name' => $request->device_name,
                                    'get_or_first' => 'first',
                                ], [
                                    'material_process' => function($query){
                                        $query->where('status', 1);
                                    },
                                    'material_process.material_details' => function($query) use($item, $item_desc){
                                        $query->where('status', 1);
                                        $query->where('tbl_wbs', 1);
                                        $query->where('item', $item);
                                        $query->where('item_desc', $item_desc);
                                    },
                                ]);

                            if($mat_proc_materials != null){
                                $col_mat_proc_materials = collect($mat_proc_materials)->flatten(1)->where('material_details', '!=', null);
                                $certified_mat_proc_id = $col_mat_proc_materials->pluck('id');
                            }
                        }

                        $prod_runcard_station_details = ProductionRuncardStation::where('production_runcard_id', $runcard_id)
                            ->whereIn('mat_proc_id', $certified_mat_proc_id)
                            ->get();

                        $prod_runcard_station_detail_ids = $prod_runcard_station_details->pluck('id');

                        if(count($prod_runcard_station_detail_ids) > 0){
                            for($index = 0; $index < count($arr_insert_issuance_id); $index++){
                                ProdRuncardMaterialList::insert([
                                    'prod_runcard_id' => $runcard_id,
                                    'status' => 1,
                                    'issuance_id' => $arr_insert_issuance_id[$index],
                                    'tbl_wbs' => 1,
                                    'status' => 1,
                                ]);
                            }

                            for($index = 0; $index < count($col_parts_prep_kit_issuance); $index++){
                                $issuance_id = $col_parts_prep_kit_issuance[$index]->id;
                                $item = $col_parts_prep_kit_issuance[$index]->item;
                                $item_desc = $col_parts_prep_kit_issuance[$index]->item_desc;

                                for($index2 = 0; $index2 < count($prod_runcard_station_detail_ids); $index2++){
                                    ProductionRuncardStationMaterial::insert([
                                        'production_runcard_id' => $runcard_id,
                                        'production_runcard_station_id' => $prod_runcard_station_detail_ids[$index2],
                                        'issuance_id' => $issuance_id,
                                        'item' => $item,
                                        'item_desc' => $item_desc,
                                        'tbl_wbs' => 1,
                                        // 'has_emboss' => 0,
                                        'status' => 1,
                                        'created_by' => Auth::user()->id,
                                        'last_updated_by' => Auth::user()->id,
                                        'update_version' => 1,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                        'created_at' => date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                        }
                        else{
                            $material_certified = false;
                            $material_certified_label = 'Not Certified';
                        }

                        DB::commit();
                        $final_result = 1;
                    }
                    catch (Exception $e) {
                        DB::rollback();
                        $final_result = 0;
                        $validator_error = $e->messages();
                    }

                }
            }
            else{
                $material_certified_label = 'No selected material.';
            }

        }
        else{
            $prep_status_label = 'Not Received For this PO #';
            $runcard_material_status_label = 'Not Received';
            $prep_status = false;
            $runcard_material_status = false;
        }

        return response()->json([
            // 'remarks' => 'Material Lot # not Found.',
            'result' => 0,
            'runcard_id' => $runcard_id,
            'prep_status' => $prep_status,
            'runcard_material_status' => $runcard_material_status,
            'prep_status_label' => $prep_status_label,
            'runcard_material_status_label' => $runcard_material_status_label,
            'material_certified' => $material_certified,
            'material_certified_label' => $material_certified_label,
            'arr_insert_issuance_id' => $arr_insert_issuance_id,
            'col_parts_prep_kit_issuance' => $col_parts_prep_kit_issuance,
            'mat_proc_device' => $mat_proc_device,
            'assessment_no' => $assessment_no,
            'final_result' => $final_result,
            'validator_error' => $validator_error,
            'material_process' => $material_process,
        ]);
    }

    public function scan_sakidashi_issuance_lot_no(Request $request){
        date_default_timezone_set('Asia/Manila');

        $prep_status_label = 'Not Received For this PO #';
        $runcard_material_status_label = 'Not Received';
        $prep_status = false;
        $runcard_material_status = false;
        $material_certified = false;
        $material_certified_label = 'Not Certified';
        $arr_insert_issuance_id = [];
        $mat_proc_device = [];
        $final_result = 0;
        $validator_error = [];
        // $assessment_no = "";
        $material_process = [];

        $runcard_id = null;
        if(isset($request->runcard_id)){
            $runcard_id = $request->runcard_id;
        }

        if($runcard_id != null){
            $parts_prep_kit_issuance = WBSSakidashiIssuance::with([
                            'parts_prep_info',
                            'parts_prep_info.prod_runcard_material_list' => function($query) use($runcard_id){
                                $query->where('prod_runcard_id', $runcard_id);
                                $query->where('tbl_wbs', 2);
                                // $query->where('for_emboss', 0);
                            },
                            'tbl_wbs_sakidashi_issuance_item' => function($query) use($request){
                                // $query->where('issuance_no', $request->whs_slip_no);
                                $query->where('lot_no', $request->lot_no);
                            },
                        ])
                        // ->where('lot_no', $request->lot_no)
                        ->where('po_no', $request->po_number)
                        ->where('device_name', $request->device_name)
                        ->get();

            $parts_prep_kit_issuance = collect($parts_prep_kit_issuance)->where('tbl_wbs_sakidashi_issuance_item', '!=', null)->flatten(1);

            // return $parts_prep_kit_issuance;

            $col_parts_prep_kit_issuance = collect($parts_prep_kit_issuance)->where('parts_prep_info', '!=', null)->where('tbl_wbs_sakidashi_issuance_item', '!=', null)->flatten(1);

            if(count($col_parts_prep_kit_issuance) > 0){
                for($index = 0; $index < count($col_parts_prep_kit_issuance); $index++){
                    $prep_status = false;
                    $runcard_material_status = false;
                    if($col_parts_prep_kit_issuance[$index]->parts_prep_info->wbs_table == 2){
                        if($col_parts_prep_kit_issuance[$index]->parts_prep_info->with_partsprep == 0){
                            // if($col_parts_prep_kit_issuance[$index]->parts_prep_info->status == 1){
                                $prep_status_label = 'Parts Prep Done';
                                $prep_status = true;
                            // }
                        }
                        else{
                            if($col_parts_prep_kit_issuance[$index]->parts_prep_info->status == 5 || $col_parts_prep_kit_issuance[$index]->parts_prep_info->status == 7){
                                $prep_status_label = 'Parts Prep Done';
                                $prep_status = true;
                            }
                            else{
                                $prep_status_label = 'Parts Prep Ongoing';
                                $prep_status = false;
                            }
                        }

                        if($prep_status){
                            if(count($col_parts_prep_kit_issuance[$index]->parts_prep_info->prod_runcard_material_list) > 0){
                                $runcard_material_status_label = 'Already Exist';
                                $runcard_material_status = false;
                            }
                            else{
                                $runcard_material_status_label = 'Ready to Insert';
                                $runcard_material_status = true;
                            }
                        }
                        else{
                            $runcard_material_status_label = 'Not Received';
                        }
                    }

                    $item = $col_parts_prep_kit_issuance[$index]->tbl_wbs_sakidashi_issuance_item->item;
                    $item_desc = $col_parts_prep_kit_issuance[$index]->tbl_wbs_sakidashi_issuance_item->item_desc;
                    // $assessment_no = $col_parts_prep_kit_issuance[$index]->kit_issuance->assessment;

                    $mat_proc_device = Device::with([
                                        'material_process'  => function($query){
                                            $query->where('status', 1);
                                        },
                                        'material_process.material_details' => function($query) use($item, $item_desc){
                                            $query->where('status', 1);
                                            $query->where('tbl_wbs', 2);
                                            $query->where('item', $item);
                                            // $query->where('item_desc', $item_desc);
                                        },
                                    ])
                                    ->where('name', $request->device_name)
                                    ->first();

                    if($mat_proc_device != null){
                        if(count($mat_proc_device->material_process) > 0){
                            $material_certified = true;
                            $material_certified_label = 'Certified';

                            if($runcard_id != null){
                                if($prep_status && $runcard_material_status){
                                    $arr_insert_issuance_id[] = $col_parts_prep_kit_issuance[$index]->id;
                                }
                            }
                            else{
                                if($prep_status){
                                    $arr_insert_issuance_id[] = $col_parts_prep_kit_issuance[$index]->id;
                                }
                            }
                        }
                        else{
                            $material_certified = false;
                            $material_certified_label = 'Not Certified';
                        }
                    }
                    else{
                        $material_certified = false;
                        $material_certified_label = 'Not Certified';
                    }

                }

                if(count($arr_insert_issuance_id) > 0){
                    // Insert Runcard & Material
                    if($runcard_id != null){
                        // INSERT MATERIAL ONLY
                        DB::beginTransaction();
                        try {
                            // for($index = 0; $index < count($arr_insert_issuance_id); $index++){
                            //     ProdRuncardMaterialList::insert([
                            //         'prod_runcard_id' => $runcard_id,
                            //         'status' => 1,
                            //         'issuance_id' => $arr_insert_issuance_id[$index],
                            //         'tbl_wbs' => 2,
                            //         'for_emboss' => 0,
                            //         'status' => 1,
                            //     ]);

                            // }

                            $certified_mat_proc_id = [];

                            $arr_station_material_issuance_id = [];
                            for($index = 0; $index < count($col_parts_prep_kit_issuance); $index++){
                                $issuance_id = $col_parts_prep_kit_issuance[$index]->id;
                                $item = $col_parts_prep_kit_issuance[$index]->tbl_wbs_sakidashi_issuance_item->item;
                                $item_desc = $col_parts_prep_kit_issuance[$index]->tbl_wbs_sakidashi_issuance_item->item_desc;

                                $mat_proc_materials = CommonController::getInstance()->getMatrixByPoNo([
                                        'po_no' => $request->po_no,
                                        'po_number' => $request->po_number,
                                        'name' => $request->device_name,
                                        'get_or_first' => 'first',
                                    ], [
                                        'material_process' => function($query){
                                            $query->where('status', 1);
                                        },
                                        'material_process.material_details' => function($query) use($item, $item_desc){
                                            $query->where('status', 1);
                                            $query->where('tbl_wbs', 2);
                                            // $query->where('has_emboss', 0);
                                            $query->where('item', $item);
                                            // $query->where('item_desc', $item_desc);
                                        },
                                    ]);

                                if($mat_proc_materials != null){
                                    $col_mat_proc_materials = collect($mat_proc_materials)->flatten(1)->where('material_details', '!=', null);
                                    $certified_mat_proc_id = $col_mat_proc_materials->pluck('id');
                                }
                            }

                            $prod_runcard_station_details = ProductionRuncardStation::where('production_runcard_id', $runcard_id)
                                ->whereIn('mat_proc_id', $certified_mat_proc_id)
                                ->get();

                            $prod_runcard_station_detail_ids = $prod_runcard_station_details->pluck('id');

                            // return count($col_parts_prep_kit_issuance);
                            // return $col_parts_prep_kit_issuance[0]->tbl_wbs_sakidashi_issuance_item->item;

                            // return $prod_runcard_station_detail_ids;


                            if(count($prod_runcard_station_detail_ids) > 0){
                                for($index = 0; $index < count($arr_insert_issuance_id); $index++){
                                    ProdRuncardMaterialList::insert([
                                        'prod_runcard_id' => $runcard_id,
                                        'status' => 1,
                                        'issuance_id' => $arr_insert_issuance_id[$index],
                                        'tbl_wbs' => 2,
                                        // 'for_emboss' => 0,
                                        'status' => 1,
                                    ]);
                                }

                                for($index = 0; $index < count($col_parts_prep_kit_issuance); $index++){
                                    $issuance_id = $col_parts_prep_kit_issuance[$index]->id;
                                    $item = $col_parts_prep_kit_issuance[$index]->tbl_wbs_sakidashi_issuance_item->item;
                                    $item_desc = $col_parts_prep_kit_issuance[$index]->tbl_wbs_sakidashi_issuance_item->item_desc;

                                    for($index2 = 0; $index2 < count($prod_runcard_station_detail_ids); $index2++){
                                        ProductionRuncardStationMaterial::insert([
                                            'production_runcard_id' => $runcard_id,
                                            'production_runcard_station_id' => $prod_runcard_station_detail_ids[$index2],
                                            'issuance_id' => $issuance_id,
                                            'item' => $item,
                                            'item_desc' => $item_desc,
                                            'tbl_wbs' => 2,
                                            // 'has_emboss' => 0,
                                            'status' => 1,
                                            'created_by' => Auth::user()->id,
                                            'last_updated_by' => Auth::user()->id,
                                            'update_version' => 1,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }
                            }
                            else{
                                $material_certified = false;
                                $material_certified_label = 'Not Certified';
                            }

                            // Icommit mo dine
                            DB::commit();
                            $final_result = 1;
                        }
                        catch (Exception $e) {
                            DB::rollback();
                            $final_result = 0;
                            $validator_error = $e->messages();
                        }

                    }
                }
                else{
                    $material_certified_label = 'No selected material.';
                }

            }
            else{
                $prep_status_label = 'Not Received For this PO #';
                $runcard_material_status_label = 'Not Received';
                $prep_status = false;
                $runcard_material_status = false;
            }

            return response()->json([
                'result' => 0,
                'runcard_id' => $runcard_id,
                'prep_status' => $prep_status,
                'runcard_material_status' => $runcard_material_status,
                'prep_status_label' => $prep_status_label,
                'runcard_material_status_label' => $runcard_material_status_label,
                'material_certified' => $material_certified,
                'material_certified_label' => $material_certified_label,
                'arr_insert_issuance_id' => $arr_insert_issuance_id,
                'col_parts_prep_kit_issuance' => $col_parts_prep_kit_issuance,
                'mat_proc_device' => $mat_proc_device,
                // 'assessment_no' => $assessment_no,
                'final_result' => $final_result,
                'validator_error' => $validator_error,
                'material_process' => $material_process,
            ]);
        }
        else{
            return response()->json([
                'final_result' => 2,
                'remarks' => 'No Runcard Details',
            ]);
        }
    }

    public function scan_employee_no(Request $request){
        date_default_timezone_set('Asia/Manila');

        $user_status = false;
        $user_status_label = 'Invalid Operator';
        $certified_status = false;
        $certified_status_label = 'Not Certified';
        $arr_insert_issuance_id = [];
        $mat_proc_device = [];
        $final_result = 0;
        $validator_error = [];
        // $assessment_no = "";
        $material_process = [];

        $runcard_id = null;
        if(isset($request->runcard_id)){
            $runcard_id = $request->runcard_id;
        }

        if($runcard_id != null){
            $user = User::where('employee_id', $request->employee_no)
                    ->where('status', 1)
                    ->where('position', 4)
                    ->first();

            // return $user;

            if($user != null){
                $user_status = true;
                $user_status_label = 'Valid Operator';

                $mat_proc_materials = CommonController::getInstance()->getMatrixByPoNo([
                            'po_no' => $request->po_no,
                            'po_number' => $request->po_number,
                            'name' => $request->device_name,
                            'get_or_first' => 'first',
                        ], [
                            'material_process' => function($query){
                                $query->where('status', 1);
                            },
                            'material_process.manpowers_details' => function($query) use($user){
                                $query->where('manpower_id', $user->id);
                            },
                        ]);

                if($mat_proc_materials != null){
                    $col_mat_proc_materials = collect($mat_proc_materials)->flatten(1)->where('manpowers_details', '!=', null);
                    $certified_mat_proc_id = $col_mat_proc_materials->pluck('id');
                }

                $prod_runcard_station_details = ProductionRuncardStation::where('production_runcard_id', $runcard_id)
                                ->whereIn('mat_proc_id', $certified_mat_proc_id)
                                ->get();

                $prod_runcard_station_detail_ids = $prod_runcard_station_details->pluck('id');

                // return $prod_runcard_station_detail_ids;

                if(count($prod_runcard_station_detail_ids) > 0){
                    // $final_prod_runcard_station_details = $prod_runcard_station_details->whereIn('id', $prod_runcard_station_detail_ids);

                    // return $final_prod_runcard_station_details;

                    // $prod_runcard_station_operators = ProductionRuncardStationOperator::where('production_runcard_id', $runcard_id)
                    //                                     ->whereIn('production_runcard_station_id', $prod_runcard_station_detail_ids)
                    //                                     ->where('operator_id', $user->id)
                    //                                     ->get();
                    // return $prod_runcard_station_operators;
                    // if(count($prod_runcard_station_operators) > 0){

                    // }
                    // else{

                    // }

                    DB::beginTransaction();
                    try {
                        $valid_counter = 0;
                        for($index = 0; $index < count($prod_runcard_station_detail_ids); $index++){

                            $prod_runcard_station_operators = ProductionRuncardStationOperator::where('production_runcard_id', $runcard_id)
                                                                ->where('production_runcard_station_id', $prod_runcard_station_detail_ids[$index])
                                                                ->where('operator_id', $user->id)
                                                                ->get();

                            if(count($prod_runcard_station_operators) <= 0){
                                $valid_counter++;
                                ProductionRuncardStationOperator::insert([
                                    'production_runcard_id' => $runcard_id,
                                    'production_runcard_station_id' => $prod_runcard_station_detail_ids[$index],
                                    'operator_id' => $user->id,
                                ]);
                            }
                        }

                        if($valid_counter > 0){
                            $certified_status = true;
                            $certified_status_label = 'Certified';
                            $final_result = 1;
                            DB::commit();
                        }
                        else{
                            $certified_status = false;
                            $certified_status_label = 'Already Exist!';
                            $final_result = 0;
                        }

                    } catch (Exception $e) {
                        DB::rollback();
                        $certified_status = false;
                        $certified_status_label = 'Not Certified';
                        $final_result = 0;
                    }
                }
                else{
                    $certified_status = false;
                    $certified_status_label = 'Not Certified';
                }
            }
            else{
                $user_status_label = 'Invalid Operator';
                $certified_status_label = 'Not Certified';
                $user_status = false;
                $certified_status = false;
            }

            return response()->json([
                'user_status' => $user_status,
                'user_status_label' => $user_status_label,
                'certified_status' => $certified_status,
                'certified_status_label' => $certified_status_label,
                'final_result' => $final_result,
                'validator_error' => $validator_error,
                'material_process' => $material_process,
                'runcard_id' => $runcard_id,
            ]);
        }
        else{
            $user_status_label = 'No Runcard Details';
            $certified_status_label = 'No Runcard Details';
            return response()->json([
                'user_status' => $user_status,
                'user_status_label' => $user_status_label,
                'certified_status' => $certified_status,
                'certified_status_label' => $certified_status_label,
                'final_result' => $final_result,
                'validator_error' => $validator_error,
                'material_process' => $material_process,
            ]);
        }

    }

    public function edit_prod_runcard_station1(Request $request){
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $data = $request->all();

        $validator = Validator::make($data, [
            'txt_edit_prod_runcard_station_input' => ['required'],
            'txt_edit_prod_runcard_station_output' => ['required'],
            'txt_edit_prod_runcard_station_ng' => ['required'],
            // 'txt_edit_prod_runcard_operator' => ['required'],
            // 'txt_prod_runcard_station_id_query' => ['required'],
            'txt_employee_number_scanner' => ['required'],
            // 'txt_prod_runcard_id_query' => ['required'],
            // 'txt_edit_prod_runcard_station_materials' => ['required'],
        ]);

        if ($validator->fails()) {
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = 'Please complete required fields.';
            $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
            return $return;
        }
        else{

            $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

            if($user->count() > 0){
                if($user[0]->position == 4){
                    if( $request->txt_prod_runcard_station_id_query ){

                        // if($validator->passes()){
                            DB::beginTransaction();
                            try {
                                ProductionRuncardStation::where('id',$request->txt_prod_runcard_station_id_query)
                                    ->update(
                                        [
                                            // 'qty_input'=>$request->txt_edit_prod_runcard_station_input + $request->txt_edit_prod_runcard_station_ng,
                                            // 'qty_output'=>$request->txt_edit_prod_runcard_station_output + $request->txt_edit_prod_runcard_station_ng,
                                            'qty_input'=>$request->txt_edit_prod_runcard_station_input,
                                            'qty_output'=>$request->txt_edit_prod_runcard_station_output,
                                            'qty_ng'=>$request->txt_edit_prod_runcard_station_ng,
                                            // 'mod'=>$request->txt_edit_prod_runcard_station_mod,
                                            // 'operator' => $operators,
                                            // 'machines' => $machines,
                                            // 'material' => $request->txt_edit_prod_runcard_station_materials,
                                            'status' => 1,
                                        ]
                                    );

                                // Save Operators
                                if(isset($request->txt_edit_prod_runcard_operator)){
                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                        ProductionRuncardStationOperator::insert([
                                            'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                            'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                            'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                        ]);
                                    }
                                }

                                // Save Machines
                                if(isset($request->txt_edit_prod_runcard_station_machine)){
                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                        ProductionRuncardStationMachine::insert([
                                            'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                            'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                            'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                        ]);
                                    }
                                }

                                // if has NG only
                                if($request->txt_edit_prod_runcard_station_ng > 0){
                                    // Required to save MOD if has NG only
                                    if(isset($request->mod)){
                                        for($index = 0; $index < count($request->mod); $index++){
                                            ProductionRuncardStationMOD::insert([
                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                'mod_id' => $request->mod[$index],
                                                'type_of_ng' => $request->type_of_ng[$index],
                                                'mod_qty' => $request->mod_qty[$index],
                                                'created_by' => Auth::user()->id,
                                                'last_updated_by' => Auth::user()->id,
                                                'update_version' => 1,
                                                'status' => 1,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s'),
                                            ]);
                                        }
                                    }
                                    else{
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Please Fill-up MOD!';
                                        $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }
                                }
                                else{
                                    // Don't save the MOD
                                }


                                DB::commit();
                                $result = true;

                            } catch (Exception $e) {
                                DB::rollback();
                                $result = false;
                            }
                    }

                    try{
                        $status = 3;

                        $prod_runcard_details = [];
                            $prod_runcard_details = ProductionRuncardStation::where('production_runcard_id', $request->txt_prod_runcard_id_query)
                                                    ->where('qty_output', null)
                                                    ->get();

                        if(count($prod_runcard_details) <= 0){
                            $status = 4;
                        }

                        ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                            ->update(
                                [
                                    'status' => $status,
                                    'last_updated_by' => Auth::user()->id,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]
                            );
                        DB::commit();
                        $result = true;

                    }

                    catch (Exception $e) {
                        DB::rollback();
                        $result = false;
                    }

                    if( !$result ){
                        $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                        $return_body = 'An error occured. The record not saved.';
                    }
                    $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                    return $return;
                }
                else{
                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                    $return_body = ' Scanned QR Code is not Operator.';
                    $return = array('result' => '1', 'title'=>$return_title,'body'=>$return_body);
                    return $return;
                }
            }
            else{
                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                $return_body = ' Invalid Employee No.';
                $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                return $return;
                // return '0';
            }
        }
    }


    public function edit_prod_runcard_station(Request $request){
        return 'true'; DB::commit();
        date_default_timezone_set('Asia/Manila');
        $timeNow = date('H:i');
        $time = explode(':', $timeNow);
        $hour = (int)$time[0];
        $min = (int)$time[1];

        // 7:30AM to 19:29PM A shift (Morning) F-PMI-950
        // 19:30PM to 7:29AM B shift (Evening)
        $isMorning = false;
        if( $hour >= 7 && $hour <= 19 ) {
            $isMorning = true;
            if( $hour == 7 && $min < 30 )
                $isMorning = false;
            if( $hour == 19 && $min >= 30 )
                $isMorning = false;
        }
        $processFlow = ProcessFlow::find($request->process_flow_id);
        // return response()->json(['processFlow' => $processFlow]);
        // return response()->json([
        //     'request' => $request->all(),
        //     'processFlow' => $processFlow,
        //     'timeNow' => $timeNow,
        //     'hour' => $hour,
        //     'min' => $min,
        //     'isMorning' => $isMorning,
        // ]);

        $return_title = '<i class="fa fa-check-circle text-success"></i> SAVED';
        $return_body = '<center><p style="color:green;">Record has been saved.</p></center>';
        $result = 1;

        /**
         * For checking of process if "Auto Insertion / #MO Installation"
         * then add validation for Mold, C/T Supplier, Die No & Pair #
         * -JD
         */
        $runcardRequiredFieldsArray = [
            'mold'                  =>null,
            'ct_supplier'           =>null,
            'die_no'                =>null,
            'pair_no'               =>null,
            'is_auto_insertion'     =>false,
            'is_full_assembly'      =>false,
            'work_week'             =>null,
            'qty_input'             =>null,
            'qty_output'            =>null,
        ];
        $runcardRequiredFieldsArrayHasNull = true;
        $fullAssemblyRequiredFieldsArrayHasNull = true;

        $data = $request->all();

         // For Edit of Station
        if((isset($request->txt_prod_runcard_id_query) || $request->txt_prod_runcard_id_query != null || $request->txt_prod_runcard_id_query != '')){
            // For Edit Prod Station, runcard station should be set on this state
            if(isset($request->txt_prod_runcard_station_id_query)){
                // return isset($request->runcard_defect) ? $request->runcard_defect : 'not set';
                // return "pining bading";
                $validator = Validator::make($data, [
                    'txt_prod_runcard_id_query' => ['required'],
                    'txt_prod_runcard_station_id_query' => ['required'],
                    'qty_input' => ['required'],
                    'qty_output' => ['required'],
                    'qty_ng' => ['required'],
                    'txt_employee_number_scanner' => ['required'],
                    // 'step_num' => ['required'],

                    // 'sub_station_id' => ['required'], // 08-16-2022 -JD
                    // 'lot_no' => ['required'], // 08-16-2022 -JD
                    // 'material_id' => ['required'], // 08-11-2022 -JD
                    // 'material_type' => ['required'], // 08-16-2022 -JD
                    'process_flow_id' => ['required'],
                ]);
                if ($validator->fails()) {
                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                    $return_body = 'Please complete required fields.';
                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
                    return $return;
                }
                else{
                    $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

                    if($user->count() > 0){
                        if($user[0]->position == 4){
                            if( $request->txt_prod_runcard_station_id_query ){
                                $processFlow = ProcessFlow::find($request->process_flow_id);
                                if( isset($processFlow->id) ){
                                    // if( $processFlow->id != (int)$request->sub_station_id ) { // Old 09-15-2022 -JD
                                    if( $processFlow->id != (int)$request->process_flow_id ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Invalid Process.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                        return $return;
                                    }

                                    /**
                                     * Operator is required to scan their id
                                     * 01-20-2023
                                     * -JD
                                     */
                                    if(!isset($request->txt_edit_prod_runcard_operator)){
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = " Operator/s is required";
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    /**
                                     * Additional rules for Process Flow Machine(Matrix) vs Machine(Runcard Station Machine Selected)
                                     * 09-15-2022
                                     * -JD
                                     */
                                    $machineCountDetected = 0;
                                    $machinesNotIncludedInMatrixArray = []; // -JD
                                    $hasOneMachine = false; // -JD
                                    $hasDefectEscalation = false; // -JD 11-28-2022
                                    $machinesIDSToCheck = explode(',', $processFlow->machine_id); // From Process Flow(Matrix)
                                    // echo 'Process flow machines and count ' . count($machinesIDSToCheck)  . " ";
                                    // print_r($machinesIDSToCheck);
                                    // echo "\r\n";

                                    $machinesIDSToCompare = $request->txt_edit_prod_runcard_station_machine; // Runcard Station Machines
                                    // echo 'Runcard station machines and count ' . count($machinesIDSToCompare) . " ";
                                    // print_r($machinesIDSToCompare);
                                    // echo "\r\n";
                                    // return count($machinesIDSToCheck);
                                    if(count($machinesIDSToCheck) > 0){
                                        if(isset($machinesIDSToCompare)){
                                            for ($i=0; $i < count($machinesIDSToCompare); $i++) {
                                                if( in_array($machinesIDSToCompare[$i], $machinesIDSToCheck) ){
                                                    $machineCountDetected++;
                                                    $hasOneMachine = true;
                                                }elseif(isset($request->runcard_for_defect)){
                                                    $hasDefectEscalation = true;
                                                }else{
                                                    $machinesNotIncludedInMatrixArray[] = $machinesIDSToCompare[$i];
                                                }
                                            }
                                            // return "has def = $hasDefectEscalation";
                                            if($hasOneMachine || $hasDefectEscalation){
                                                // echo 'hasOneMachine true';
                                                // echo "\r\n";
                                                if(count($machinesIDSToCompare) <= count($machinesIDSToCheck)){
                                                    // echo "machinesIDSToCompare ". count($machinesIDSToCompare) ." is not more than machinesIDSToCheck " . count($machinesIDSToCheck);
                                                    // echo "\r\n";
                                                    if(count($machinesNotIncludedInMatrixArray) > 0){
                                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                                        $return_body = "Some Selected Machine is not registered based on Matrix.";
                                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                        return $return;
                                                    }
                                                }else{
                                                    // echo "machinesIDSToCompare ". count($machinesIDSToCompare) ." is more than machinesIDSToCheck " . count($machinesIDSToCheck);
                                                    // echo "\r\n";
                                                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                                    $return_body = "Please check the Selected Machine registered based on Matrix.";
                                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                    return $return;
                                                }

                                            }else{
                                                // echo 'hasOneMachine false'; Material
                                                // echo "\r\n";
                                                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                                $return_body = " No Machine/s found on the Matrix.";
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                return $return;
                                            }
                                            // echo 'machinesNotIncludedInMatrixArray ';
                                            // return $machinesNotIncludedInMatrixArray;

                                        }else{
                                            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                            $return_body = "Please select one or more Machine based on Matrix.";
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                            return $return;
                                        }
                                    }
                                } else {
                                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                    $return_body = ' Process Flow not found.';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    return $return;
                                }

                                /**
                                 * Count of Scanned Materials vs Process Matrix(Materials) should be equal
                                 * 04-04-2023
                                 * -JD
                                 */
                                if(!isset($request->runcard_defect)){
                                    $device = Device::with('process_flow')->where("name", $request->deviceName)->get();
                                    $deviceProcessFlowMaterialsArray = [];
                                    $process_flow_ids = [];
                                    $hasWrongMaterialsAdded = false;
                                    if( count($device) > 0 ) {
                                        $process_flows = $device[0]->process_flow;
                                        for ($i=0; $i < count($process_flows); $i++) {
                                            $process_flow_ids[] = $process_flows[$i]->id;
                                        }
                                    }


                                    $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::whereIn("process_flow_id", $process_flow_ids)->where('is_deleted',0)->get();
                                    /* Get the same Materials based on Process flow(Matrix) and Process flow($request) */
                                    for ($i=0; $i < count($deviceProcessFlowMaterials); $i++) {
                                        if($deviceProcessFlowMaterials[$i]->process_flow_id == $request->process_flow_id){
                                            $deviceProcessFlowMaterialsArray[] = $deviceProcessFlowMaterials[$i]->material_name;
                                        }
                                    } //nmodify

                                    if(count($deviceProcessFlowMaterialsArray) > 0){
                                        /* If no materials added then execute below code */
                                        if(!isset($request->materialArray)){
                                            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                            $return_body = ' Please add materials based on the Process Matrix.';
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                            return $return;
                                        }

                                        /* If count of Scanned Materials is not equal based on the Process Matrix(Materials) */
                                        if(count($request->materialArray) != count($deviceProcessFlowMaterialsArray)){
                                            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                            $return_body = ' Count of added materials is incorrect based on the Process Matrix.';
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                            return $return;
                                        }

                                        /* If on or more Scanned Materials is incorrect based on the Process Matrix(Materials) */
                                        // return $request->materialArray[0][1];
                                        for ($i=0; $i < count($deviceProcessFlowMaterialsArray); $i++) {
                                            // return $deviceProcessFlowMaterialsArray[$i];
                                            for ($j=0; $j < count($request->materialArray); $j++) {
                                                // if($request->materialArray[$j][1] != $deviceProcessFlowMaterialsArray[$i]){
                                                if(!in_array($request->materialArray[$j][1], $deviceProcessFlowMaterialsArray)){ /* If the loop item is not included in the array */
                                                    $hasWrongMaterialsAdded = 'true';
                                                }
                                            }
                                        }
                                        if($hasWrongMaterialsAdded == 'true'){
                                            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                            $return_body = ' Some added materials is incorrect based on the Process Matrix.';
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                            return $return;
                                        }
                                    }
                                    // return count($deviceProcessFlowMaterialsArray);
                                }


                                /**
                                 * Check the process if "Auto Insertion / #MO Installation"
                                 * then add validation for Mold, C/T Supplier, Die No & Pair #
                                 * -JD
                                 */
                                $process = ProductionRuncardStation::with('process_flow.processFlow')
                                ->where('id',$request->txt_prod_runcard_station_id_query)
                                ->where('process_flow_id', $processFlow->id)
                                ->get();
                                // echo "prod runcard station id ". $request->txt_prod_runcard_station_id_query;
                                // echo "\r\n";
                                // echo "process_flow_id ". $processFlow->id;
                                // echo "\r\n";
                                // return $process[0]->process_flow->processFlow->name;

                                // if($process[0]->process_flow->processFlow->name == "Auto Insertion / #MO Installation"){
                                // updated by nessa 02072023
                                if($process[0]->process_flow->processFlow->name == "Auto Insertion / #MO Installation" || $process[0]->process_flow->processFlow->name == "Contact Insertion - Auto Insertion" || $process[0]->process_flow->processFlow->name == "Auto Insertion"){
                                    $runcardRequiredFieldsArray['is_auto_insertion'] = true;

                                    $prodRuncard = ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                                    ->get();
                                    if($prodRuncard[0]->mold != null || $prodRuncard[0]->mold != ''){
                                        $runcardRequiredFieldsArray['mold'] = $prodRuncard[0]->mold;
                                    }

                                    if($prodRuncard[0]->ct_supplier != null || $prodRuncard[0]->ct_supplier != ''){
                                        $runcardRequiredFieldsArray['ct_supplier'] = $prodRuncard[0]->ct_supplier;
                                    }

                                    if($prodRuncard[0]->die_no != null || $prodRuncard[0]->die_no != ''){
                                        $runcardRequiredFieldsArray['die_no'] = $prodRuncard[0]->die_no;
                                    }

                                    if($prodRuncard[0]->pair_no != null || $prodRuncard[0]->pair_no != ''){
                                        $runcardRequiredFieldsArray['pair_no'] = $prodRuncard[0]->pair_no;
                                    }
                                    /*nmodify if the pair number*/
                                    // return $runcardRequiredFieldsArray;
                                }

                                /**
                                 * If "Auto Insertion / #MO Installation" is true and has no null values on the required fields (Mold, C/T Supplier, etc.)
                                 * then set the $runcardRequiredFieldsArrayHasNull to false
                                 */
                                if($runcardRequiredFieldsArray['is_auto_insertion'] == true){
                                    // return 'asd';
                                    if($runcardRequiredFieldsArray['mold'] != null && $runcardRequiredFieldsArray['ct_supplier'] != null && $runcardRequiredFieldsArray['die_no'] != null && $runcardRequiredFieldsArray['pair_no'] != null){
                                        DB::beginTransaction();
                                        try {
                                            ProductionRuncardStation::where('id',$request->txt_prod_runcard_station_id_query)
                                                ->update(
                                                    [
                                                        'qty_input'=>$request->qty_input,
                                                        'qty_output'=>$request->qty_output,
                                                        'qty_ng'=>$request->qty_ng,
                                                        'partial'=>$request->qty_partial,
                                                        'runcard_defect'=>$request->runcard_defect,
                                                        'remarks'=>$request->remarks,
                                                        'magni'=>$request->magni,
                                                        // 'status' => 1,
                                                        // 'sub_station_id' => $request->sub_station_id,
                                                    ]
                                                );

                                            // Save Operators
                                            ProductionRuncardStationOperator::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                                ->delete();

                                            if(isset($request->txt_edit_prod_runcard_operator)){
                                                for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                    ProductionRuncardStationOperator::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                        'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                    ]);
                                                }
                                            }

                                            // angelo
                                            // Save Machines
                                            ProductionRuncardStationMachine::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                                ->delete();

                                            if(isset($request->txt_edit_prod_runcard_station_machine)){
                                                for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                    ProductionRuncardStationMachine::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                        'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                    ]);
                                                }
                                            }

                                            ProductionRuncardStationMOD::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                                ->delete();
                                            // if has NG only // mdr 11/07/25

                                            return 'otids';
                                            if($request->qty_ng > 0){
                                                // Required to save MOD if has NG only
                                                if(isset($request->mod)){
                                                    for($index = 0; $index < count($request->mod); $index++){
                                                        ProductionRuncardStationMOD::insert([
                                                            'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                            'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                            'mod_id' => $request->mod[$index],
                                                            'type_of_ng' => $request->type_of_ng[$index],
                                                            'mod_qty' => $request->mod_qty[$index],
                                                            'created_by' => Auth::user()->id,
                                                            'last_updated_by' => Auth::user()->id,
                                                            'update_version' => 1,
                                                            'status' => 1,
                                                            'created_at' => date('Y-m-d H:i:s'),
                                                            'updated_at' => date('Y-m-d H:i:s'),
                                                        ]);
                                                        //mdr checking
                                                        // return 1;
                                                        // DefectEscalationV2
                                                        DefectEscalationV2Rev::insert([
                                                            'po_number'                    => $request->po_number_with_defect,
                                                            'defect_ctrl_txt'              => $defectCtrltxt,       // Now unique per loop
                                                            'defect_ctrl_number'           => $defectCtrlNumber,    // Now unique per loop
                                                            'prod_runcard_id'              => $request->txt_prod_runcard_id_query,
                                                            'prod_runcard_station_id'      => $prod_runcard_station_id,
                                                            // 'prod_runcard_station_mod_id'  => $prod_runcard_station_mod_id,
                                                            'defect_classification'        => $current_class,
                                                            'defect_qty'                   => $request->mod_qty[$index],
                                                            'mod_type'                     => $request->type_of_ng[$index],
                                                            'mod_id'                       => $mod[$index],
                                                            'de_occurence'                 => $de_occurence,
                                                            'de_group'                     => 1,
                                                            'defect_status'                => 0,
                                                            'de_user'                      => $user[0]->id,
                                                            'de_position'                  => $user[0]->position,
                                                            'created_by'                   => Auth::user()->id,
                                                            'created_at'                   => now(),
                                                            'de_shift'                     => $shift,
                                                            'de_date'                      => date('Y-m-d'),
                                                            'de_time'                      => date('h:i:s A'),
                                                            'line'                         => $lineWithDefect,
                                                            'line_id'                      => $request->line_with_defect_id,
                                                        ]);
                                                    }
                                                }
                                                else{
                                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                    $return_body = ' Please Fill-up MOD!';
                                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                    return $return;
                                                }
                                            }
                                            else{
                                                // Don't save the MOD
                                            }

                                            /**
                                            * Multiple insert for Prod Runcard Station Materials
                                            * 08-16-2022
                                            * -JD
                                            */
                                            ProductionRuncardStationMaterial::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                            ->delete();
                                            if(isset($request->materialArray)){
                                                for($index = 0; $index < count($request->materialArray); $index++){
                                                    ProductionRuncardStationMaterial::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                        'sub_station_id' => $request->sub_station_id,
                                                        'material_id' => $request->materialArray[$index][0],
                                                        'material_name' => $request->materialArray[$index][1],
                                                        'lot_no' => $request->materialArray[$index][2],
                                                        'material_type' => $request->materialArray[$index][3],
                                                        'status' => 1,
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                        'created_at' => date('Y-m-d H:i:s')
                                                    ]);
                                                }
                                            }

                                            // DB::commit();
                                            $result = 1;

                                        } catch (Exception $e) {
                                            DB::rollback();
                                            $result = 0;
                                        }
                                    }else{
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Please Fill-up required fields';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'runcardRequiredFieldsArray'=>$runcardRequiredFieldsArray);
                                        return $return;
                                    }
                                }else{
                                    // return 'aaaa';
                                    DB::beginTransaction();
                                    try {
                                        ProductionRuncardStation::where('id',$request->txt_prod_runcard_station_id_query)
                                            ->update(
                                                [
                                                    'qty_input'=>$request->qty_input,
                                                    'qty_output'=>$request->qty_output,
                                                    'qty_ng'=>$request->qty_ng,
                                                    'partial'=>$request->qty_partial,
                                                    'runcard_defect'=>$request->runcard_defect,
                                                    'remarks'=>$request->remarks,
                                                    'magni'=>$request->magni,
                                                    // 'status' => 1,
                                                    // 'sub_station_id' => $request->sub_station_id,
                                                ]
                                            );

                                        // angelo
                                        // Save Operators
                                        ProductionRuncardStationOperator::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                            ->delete();

                                        if(isset($request->txt_edit_prod_runcard_operator)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                ProductionRuncardStationOperator::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                    'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                ]);
                                            }
                                        }

                                        // angelo
                                        // Save Machines
                                        ProductionRuncardStationMachine::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                            ->delete();

                                        if(isset($request->txt_edit_prod_runcard_station_machine)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                ProductionRuncardStationMachine::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                    'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                ]);
                                            }
                                        }

                                        ProductionRuncardStationMOD::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                            ->delete();
                                        // if has NG only
                                        if($request->qty_ng > 0){
                                            // Required to save MOD if has NG only
                                            if(isset($request->mod)){
                                                for($index = 0; $index < count($request->mod); $index++){
                                                    ProductionRuncardStationMOD::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                        'mod_id' => $request->mod[$index],
                                                        'type_of_ng' => $request->type_of_ng[$index],
                                                        'mod_qty' => $request->mod_qty[$index],
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'update_version' => 1,
                                                        'status' => 1,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                    ]);
                                                }
                                            }
                                            else{
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = ' Please Fill-up MOD!';
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                return $return;
                                            }
                                        }
                                        else{
                                            // Don't save the MOD
                                        }

                                        /**
                                        * Multiple insert for Prod Runcard Station Materials
                                        * 08-16-2022
                                        * -JD
                                        */
                                        ProductionRuncardStationMaterial::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                        ->delete();

                                        if(isset($request->materialArray)){
                                            for($index = 0; $index < count($request->materialArray); $index++){
                                                ProductionRuncardStationMaterial::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                    'sub_station_id' => $request->sub_station_id,
                                                    'material_id' => $request->materialArray[$index][0],
                                                    'material_name' => $request->materialArray[$index][1],
                                                    'lot_no' => $request->materialArray[$index][2],
                                                    'material_type' => $request->materialArray[$index][3],
                                                    'status' => 1,
                                                    'created_by' => Auth::user()->id,
                                                    'last_updated_by' => Auth::user()->id,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'created_at' => date('Y-m-d H:i:s')
                                                ]);
                                            }
                                        }

                                        DB::commit();
                                        $result = 1;

                                    } catch (Exception $e) {
                                        DB::rollback();
                                        $result = 0;
                                    }
                                }
                            }

                            if( $result == 0 ){
                                $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                                $return_body = 'An error occured. The record not saved.';
                            }

                            /* Main Return */
                            $return = array('result' => $result, 'title'=>$return_title,'body'=>$return_body, 'runcardRequiredFieldsArray'=>$runcardRequiredFieldsArray);
                            return $return;
                        }
                        else{
                            $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                            $return_body = ' Scanned QR Code is not Operator.';
                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                            return $return;
                        }
                    }
                    else{
                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> NOT SAVED!';
                        $return_body = ' <center> Invalid Employee No. </center>';
                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                        return $return;
                        // return '0';
                    }
                }

            }
            else{ // For Add Process, runcard station is not set on this state
                // return "add process";
                $validator = Validator::make($data, [
                    'txt_prod_runcard_id_query' => ['required'],
                    'qty_input' => ['required'],
                    'qty_output' => ['required'],
                    'qty_ng' => ['required'],
                    'txt_employee_number_scanner' => ['required'],
                    // 'step_num' => ['required'],
                    'sub_station_id' => ['required'],
                    // 'lot_no' => ['required's], // 08-16-2022 -JD
                    // 'material_id' => ['required'], // 08-11-2022 -JD
                    // 'material_type' => ['required'], // 08-16-2022 -JD
                    'process_flow_id' => ['required'], // 08-16-2022 -JD
                ]);

                if ($validator->fails()) {
                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                    $return_body = 'Please complete required fields.';
                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
                    return $return;
                }
                else{
                    // return 'proceed to add process';
                    $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

                    if($user->count() > 0){
                        if($user[0]->position == 4){ // 4-Operator
                            $processFlow = ProcessFlow::find($request->process_flow_id);
                            if( isset($processFlow->id) ){

                                // if( $processFlow->id != (int)$request->sub_station_id ) { // Old 09-14-2022 -JD
                                if( $processFlow->id != (int)$request->process_flow_id ) { // Process field
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = ' Invalid Process.';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                    return $return;
                                }

                                /**
                                 * Operator is required to scan their id
                                 * 01-20-2023
                                 * -JD
                                 */
                                if(!isset($request->txt_edit_prod_runcard_operator)){
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = " Operator/s is required";
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    return $return;
                                }

                                /**
                                 * Additional rules for Process Flow Machine(Matrix) vs Machine(Runcard Station Machine Selected)
                                 * 09-15-2022
                                 * -JD
                                 */
                                $machineCountDetected = 0;
                                $machinesNotIncludedInMatrixArray = []; // -JD
                                $hasOneMachine = false; // -JD
                                $hasDefectEscalation = false; // -JD 11-28-22
                                $machineIsNullAndNAIsSelected = false; // -JD 02-14-23
                                $machinesIDSToCheck = explode(',', $processFlow->machine_id); // From Process Flow(Matrix)
                                // echo 'Process flow machines and count ' . count($machinesIDSToCheck)  . " ";
                                // echo "\r\n";
                                // print_r($machinesIDSToCheck);
                                // echo "\r\n";

                                $machinesIDSToCompare = $request->txt_edit_prod_runcard_station_machine; // Runcard Station Machines
                                // echo 'Runcard station machines and count ' . count($machinesIDSToCompare) . " ";
                                // print_r($machinesIDSToCompare);
                                // echo "\r\n";
                                // return count($machinesIDSToCheck);

                                /**
                                 * To check if process flow is null vs selected machine values(n/a) on prod runcard station
                                 */
                                $machinesIDSToCheckIsNull = false;
                                if($processFlow->machine_id == ''){
                                    $machinesIDSToCheckIsNull = true;
                                }
                                // echo $machinesIDSToCheckIsNull;
                                // echo "\r\n";
                                // echo $machinesIDSToCompare[0];
                                // return;

                                if(count($machinesIDSToCheck) > 0){
                                    if(isset($machinesIDSToCompare)){
                                        /**
                                         * To check if process flow is null vs selected machine values(n/a) on prod runcard station
                                         */
                                        // if($machinesIDSToCheckIsNull = true && $machinesIDSToCompare[0] != "N/A"){
                                        //     $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        //     $return_body = "Please only select N/A if no machine registered based on Matrix.";
                                        //     $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                        //     return $return;
                                        // }
                                        // // else{
                                        // //     echo "id: " .$machinesIDSToCheckIsNull . " machineId: ". $machinesIDSToCompare[0];
                                        // // }
                                        for ($i=0; $i < count($machinesIDSToCompare); $i++) {
                                            if( in_array($machinesIDSToCompare[$i], $machinesIDSToCheck) ){
                                                $machineCountDetected++;
                                                $hasOneMachine = true;
                                            }
                                            elseif($machinesIDSToCheckIsNull && $machinesIDSToCompare[0] == "N/A"){
                                                $machineIsNullAndNAIsSelected = true;
                                            }
                                            elseif(isset($request->runcard_for_defect)){
                                                $hasDefectEscalation = true;
                                            }
                                            else{
                                                $machinesNotIncludedInMatrixArray[] = $machinesIDSToCompare[$i];
                                            }
                                        }

                                        if(!$machineIsNullAndNAIsSelected){
                                            if($hasOneMachine || $hasDefectEscalation){
                                                // echo 'hasOneMachine true';
                                                // echo "\r\n";
                                                if(count($machinesIDSToCompare) <= count($machinesIDSToCheck)){
                                                    // echo "machinesIDSToCompare ". count($machinesIDSToCompare) ." is not more than machinesIDSToCheck " . count($machinesIDSToCheck);
                                                    // echo "\r\n";
                                                    if(count($machinesNotIncludedInMatrixArray) > 0){
                                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                        $return_body = "Some Selected Machine is not registered based on Matrix.";
                                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                        return $return;
                                                    }
                                                }else{
                                                    // echo "machinesIDSToCompare ". count($machinesIDSToCompare) ." is more than machinesIDSToCheck " . count($machinesIDSToCheck);
                                                    // echo "\r\n";
                                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                    $return_body = "Please check the Selected Machine registered based on Matrix.";
                                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                    return $return;
                                                }
                                            }else{
                                                // echo 'hasOneMachine false';
                                                // echo "\r\n";
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = " No Machine/s found on the Matrix.";
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                return $return;
                                            }
                                            // echo 'machinesNotIncludedInMatrixArray ';
                                            // return $machinesNotIncludedInMatrixArray;
                                        }
                                        // return "hasDefectEscalation: $hasDefectEscalation";


                                    }else{
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = "Please select one or more Machine based on Matrix.";
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                        return $return;
                                    }
                                }
                            } else {
                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                $return_body = ' Process Flow not found.';
                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                return $return;
                            }

                            $processFlowApprovalStatus = ProcessFlow::where('id',$request->process_flow_id)->get();
                            // return $processFlowApprovalStatus;
                            if($processFlowApprovalStatus[0]->approval_status == 1 || $processFlowApprovalStatus[0]->approval_status == 2){ // added by nessa 01/22/2023
                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                $return_body = ' Process Step is not yet approved. <br> Please check the matrix.';
                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                return $return;
                            }

                            // /**
                            //  * Scanning of material is required based on the registered materials in Process Flow(Matrix)
                            //  * 01-26-2023
                            //  * -JD
                            //  */
                            // if(isset($request->materialArray)){
                            //     $materialDetails = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                            //     ->get();
                            //     // return $materialDetails;

                            //     /* Total count of scanned materials should be equal to registered materials in Process Flow(Matrix)*/
                            //     if( count($materialDetails) != count($request->materialArray) ) {
                            //         $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                            //         $return_body = ' Materials is not equal based on the Matrix';
                            //         $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                            //         return $return;
                            //     }
                            // }

                            /**
                             * Count of Scanned Materials vs Process Matrix(Materials) should be equal
                             * 04-04-2023
                             * -JD
                             */
                            if(!isset($request->runcard_defect)){
                                $device = Device::with('process_flow')->where("name", $request->deviceName)->get();
                                // return $device;
                                $deviceProcessFlowMaterialsArray = [];
                                $process_flow_ids = [];
                                $hasWrongMaterialsAdded = 'false';
                                // return $device;
                                if( count($device) > 0 ) {
                                    $process_flows = $device[0]->process_flow;
                                    for ($i=0; $i < count($process_flows); $i++) {
                                        $process_flow_ids[] = $process_flows[$i]->id;
                                    }
                                }
                                // return $processFlow->id;
                                // return $process_flow_ids;

                                $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::whereIn("process_flow_id", $process_flow_ids)->where('is_deleted',0)->get();
                                /* Get the same Materials based on Process flow(Matrix) and Process flow($request) */
                                for ($i=0; $i < count($deviceProcessFlowMaterials); $i++) {
                                    if($deviceProcessFlowMaterials[$i]->process_flow_id == $processFlow->id){
                                        $deviceProcessFlowMaterialsArray[] = $deviceProcessFlowMaterials[$i]->material_name;
                                    }
                                }
                                if(count($deviceProcessFlowMaterialsArray) > 0){
                                    // return $deviceProcessFlowMaterialsArray;
                                    /* If no materials added then execute below code */
                                    if(!isset($request->materialArray)){
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Please add materials based on the Process Matrix.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    /* If count of Scanned Materials is not equal based on the Process Matrix(Materials) */
                                    if(count($request->materialArray) != count($deviceProcessFlowMaterialsArray)){
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Count of added materials is incorrect based on the Process Matrix.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    /* If one or more Scanned Materials is incorrect based on the Process Matrix(Materials) */
                                    // return $request->materialArray;
                                    // $tempoArray = [];
                                    for ($i=0; $i < count($deviceProcessFlowMaterialsArray); $i++) {
                                        // return $deviceProcessFlowMaterialsArray;
                                        for ($j=0; $j < count($request->materialArray); $j++) {
                                            // if($request->materialArray[$j][1] != $deviceProcessFlowMaterialsArray[$i]){
                                            if(!in_array($request->materialArray[$j][1], $deviceProcessFlowMaterialsArray)){
                                                $hasWrongMaterialsAdded = 'true';
                                            }
                                            // else{
                                            //     $tempoArray[] = $deviceProcessFlowMaterialsArray[$i];
                                            // }
                                        }
                                        // return $tempoArray;
                                    }
                                    // return $hasWrongMaterialsAdded;
                                    if($hasWrongMaterialsAdded == 'true'){
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Some added materials is incorrect based on the Process Matrix.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }
                                }
                                // return count($deviceProcessFlowMaterialsArray);
                            }


                            /**
                             * Check the process if "Auto Insertion / #MO Installation"
                             * then add validation for Mold, C/T Supplier, Die No & Pair #
                             * -JD
                             */
                            $process = SubStation::where('id',$request->sub_station_id)->get();
                            // return $process[0]->name;
                            // echo $process[0]->name;
                            // echo "\r\n";

                            // if($process[0]->name == "Auto Insertion / #MO Installation"){
                            //updated by nesa 02072023
                            // $runcardRequiredFieldsArray['is_auto_insertion'] = false;
                            //nmodify
                            if($process[0]->name == "Auto Insertion / #MO Installation" || $process[0]->name == "Contact Insertion - Auto Insertion" || $process[0]->name == "Auto Insertion"){

                                $runcardRequiredFieldsArray['is_auto_insertion'] = true;

                                $prodRuncard = ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                                ->get();
                                if($prodRuncard[0]->mold != null || $prodRuncard[0]->mold != ''){
                                    $runcardRequiredFieldsArray['mold'] = $prodRuncard[0]->mold;
                                }

                                if($prodRuncard[0]->ct_supplier != null || $prodRuncard[0]->ct_supplier != ''){
                                    $runcardRequiredFieldsArray['ct_supplier'] = $prodRuncard[0]->ct_supplier;
                                }

                                if($prodRuncard[0]->die_no != null || $prodRuncard[0]->die_no != ''){
                                    $runcardRequiredFieldsArray['die_no'] = $prodRuncard[0]->die_no;
                                }

                                if($prodRuncard[0]->pair_no != null || $prodRuncard[0]->pair_no != ''){
                                    $runcardRequiredFieldsArray['pair_no'] = $prodRuncard[0]->pair_no;
                                }
                                // return $runcardRequiredFieldsArray;
                            }

                            // return $process[0]->name;


                            /**
                             * Check the process if "Full Assembly"
                             * then add validation for Workweek, Input, Output in Print / Curing(Process)
                             * -JD
                             * 10-17-2022
                             */
                            if($process[0]->name == "Full Assembly"){
                                $runcardRequiredFieldsArray['is_full_assembly'] = true;

                                $prodRuncard = ProductionRuncard::with('prod_runcard_station_many_details')->where('id',$request->txt_prod_runcard_id_query)
                                ->get();
                                // return $prodRuncard[0]->prod_runcard_station_many_details[0]->qty_output;
                                if($prodRuncard[0]->work_week != null || $prodRuncard[0]->work_week != ''){
                                    $runcardRequiredFieldsArray['work_week'] = $prodRuncard[0]->work_week;
                                }

                                $qty_input = $prodRuncard[0]->prod_runcard_station_many_details[0]->qty_input;
                                $qty_output = $prodRuncard[0]->prod_runcard_station_many_details[0]->qty_output;
                                if($qty_input != 0 || $qty_input != null){
                                    $runcardRequiredFieldsArray['qty_input'] = $qty_input;
                                }

                                if($qty_output != 0 || $qty_output != null){
                                    $runcardRequiredFieldsArray['qty_output'] = $qty_output;
                                }
                                // return $runcardRequiredFieldsArray;
                            }

                            if($runcardRequiredFieldsArray['is_auto_insertion'] == true){
                                if($runcardRequiredFieldsArray['mold'] != null && $runcardRequiredFieldsArray['ct_supplier'] != null && $runcardRequiredFieldsArray['die_no'] != null && $runcardRequiredFieldsArray['pair_no'] != null){
                                    // $runcardRequiredFieldsArrayHasNull = false;
                                    DB::beginTransaction();
                                    try {
                                        // return 'Success';
                                        $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                            'production_runcard_id'=> $request->txt_prod_runcard_id_query,
                                            // 'mat_proc_id'=> $material_process[$index]->id,
                                            // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                            // 'sub_station_id'=> $request->sub_station_id,
                                            // 'step_num'=> $step_num,
                                            'process_flow_id'=> $request->process_flow_id,
                                            'qty_input'=>$request->qty_input,
                                            'qty_output'=>$request->qty_output,
                                            'qty_ng'=>$request->qty_ng,
                                            'partial'=>$request->qty_partial,
                                            'runcard_defect'=>$request->runcard_defect,
                                            'remarks'=>$request->remarks,
                                            'magni'=>$request->magni,
                                            'status' => 1,
                                            'created_by'=> Auth::user()->id,
                                            'last_updated_by'=> Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]);

                                        // Save Operators
                                        if(isset($request->txt_edit_prod_runcard_operator)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                ProductionRuncardStationOperator::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                ]);
                                            }
                                        }

                                        // Save Machines
                                        if(isset($request->txt_edit_prod_runcard_station_machine)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                ProductionRuncardStationMachine::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                ]);
                                            }
                                        }

                                        // if has NG only
                                        if($request->qty_ng > 0){
                                            // Required to save MOD if has NG only
                                            if(isset($request->mod)){
                                                for($index = 0; $index < count($request->mod); $index++){
                                                    ProductionRuncardStationMOD::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $prod_runcard_station_id,
                                                        'mod_id' => $request->mod[$index],
                                                        'type_of_ng' => $request->type_of_ng[$index],
                                                        'mod_qty' => $request->mod_qty[$index],
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'update_version' => 1,
                                                        'status' => 1,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                    ]);
                                                }
                                            }
                                            else{
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = ' Please Fill-up MOD!';
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                return $return;
                                            }
                                        }
                                        else{
                                            // Don't save the MOD
                                        }


                                        /**
                                        * Multiple insert for Prod Runcard Station Materials
                                        * 08-16-2022
                                        * -JD
                                        */
                                        if(isset($request->materialArray)){
                                            for($index = 0; $index < count($request->materialArray); $index++){
                                                ProductionRuncardStationMaterial::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'sub_station_id' => $request->sub_station_id,
                                                    'material_id' => $request->materialArray[$index][0],
                                                    'material_name' => $request->materialArray[$index][1],
                                                    'lot_no' => $request->materialArray[$index][2],
                                                    'material_type' => $request->materialArray[$index][3],
                                                    'status' => 1,
                                                    'created_by' => Auth::user()->id,
                                                    'last_updated_by' => Auth::user()->id,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'created_at' => date('Y-m-d H:i:s')
                                                ]);
                                            }
                                        }
                                        DB::commit();
                                        $result = 1;

                                    } catch (Exception $e) {
                                        DB::rollback();
                                        $result = 0;
                                    }
                                }else{
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = ' Please Fill-up required fields(For Auto Insertion)';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'runcardRequiredFieldsArray'=>$runcardRequiredFieldsArray);
                                    return $return;
                                }
                            }
                            else if($runcardRequiredFieldsArray['is_full_assembly'] == true){
                                if($runcardRequiredFieldsArray['work_week'] != null && ($runcardRequiredFieldsArray['qty_input'] != 0 || $runcardRequiredFieldsArray['qty_input'] !=  null) && ($runcardRequiredFieldsArray['qty_output'] != 0 || $runcardRequiredFieldsArray['qty_output'] != null)){
                                    DB::beginTransaction();
                                    try {
                                        // return 'Success';
                                        $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                            'production_runcard_id'=> $request->txt_prod_runcard_id_query,
                                            // 'mat_proc_id'=> $material_process[$index]->id,
                                            // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                            // 'sub_station_id'=> $request->sub_station_id,
                                            // 'step_num'=> $step_num,
                                            'process_flow_id'=> $request->process_flow_id,
                                            'qty_input'=>$request->qty_input,
                                            'qty_output'=>$request->qty_output,
                                            'qty_ng'=>$request->qty_ng,
                                            'partial'=>$request->qty_partial,
                                            'runcard_defect'=>$request->runcard_defect,
                                            'remarks'=>$request->remarks,
                                            'magni'=>$request->magni,
                                            'status' => 1,
                                            'created_by'=> Auth::user()->id,
                                            'last_updated_by'=> Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]);

                                        // Save Operators
                                        if(isset($request->txt_edit_prod_runcard_operator)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                ProductionRuncardStationOperator::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                ]);
                                            }
                                        }

                                        // Save Machines
                                        if(isset($request->txt_edit_prod_runcard_station_machine)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                ProductionRuncardStationMachine::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                ]);
                                            }
                                        }

                                        // if has NG only
                                        if($request->qty_ng > 0){
                                            // Required to save MOD if has NG only
                                            if(isset($request->mod)){
                                                for($index = 0; $index < count($request->mod); $index++){
                                                    ProductionRuncardStationMOD::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $prod_runcard_station_id,
                                                        'mod_id' => $request->mod[$index],
                                                        'type_of_ng' => $request->type_of_ng[$index],
                                                        'mod_qty' => $request->mod_qty[$index],
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'update_version' => 1,
                                                        'status' => 1,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                    ]);
                                                }
                                            }
                                            else{
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = ' Please Fill-up MOD!';
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                return $return;
                                            }
                                        }
                                        else{
                                            // Don't save the MOD
                                        }


                                        /**
                                        * Multiple insert for Prod Runcard Station Materials
                                        * 08-16-2022
                                        * -JD
                                        */
                                        if(isset($request->materialArray)){
                                            for($index = 0; $index < count($request->materialArray); $index++){
                                                ProductionRuncardStationMaterial::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'sub_station_id' => $request->sub_station_id,
                                                    'material_id' => $request->materialArray[$index][0],
                                                    'material_name' => $request->materialArray[$index][1],
                                                    'lot_no' => $request->materialArray[$index][2],
                                                    'material_type' => $request->materialArray[$index][3],
                                                    'status' => 1,
                                                    'created_by' => Auth::user()->id,
                                                    'last_updated_by' => Auth::user()->id,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'created_at' => date('Y-m-d H:i:s')
                                                ]);
                                            }
                                        }
                                        DB::commit();
                                        $result = 1;
                                    } catch (Exception $e) {
                                        DB::rollback();
                                        $result = 0;
                                    }
                                }else{
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = ' Please Fill-up required fields(For Full Assembly)';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'runcardRe uiredFieldsArray'=>$runcardRequiredFieldsArray);
                                    return $return;
                                }
                            }
                            else{
                                DB::beginTransaction();
                                try {
                                    // return 'Success';
                                    $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                        'production_runcard_id'=> $request->txt_prod_runcard_id_query,
                                        // 'mat_proc_id'=> $material_process[$index]->id,
                                        // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                        // 'sub_station_id'=> $request->sub_station_id,
                                        // 'step_num'=> $step_num,
                                        'process_flow_id'=> $request->process_flow_id,
                                        'qty_input'=>$request->qty_input,
                                        'qty_output'=>$request->qty_output,
                                        'qty_ng'=>$request->qty_ng,
                                        'partial'=>$request->qty_partial,
                                        'runcard_defect'=>$request->runcard_defect,
                                        'remarks'=>$request->remarks,
                                        'magni'=>$request->magni,
                                        'status' => 1,
                                        'created_by'=> Auth::user()->id,
                                        'last_updated_by'=> Auth::user()->id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ]);

                                    // Save Operators
                                    if(isset($request->txt_edit_prod_runcard_operator)){
                                        for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                            ProductionRuncardStationOperator::insert([
                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                            ]);
                                        }
                                    }

                                    // Save Machines
                                    if(isset($request->txt_edit_prod_runcard_station_machine)){
                                        for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                            ProductionRuncardStationMachine::insert([
                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                            ]);
                                        }
                                    }

                                    // if has NG only
                                    if($request->qty_ng > 0){
                                        // Required to save MOD if has NG only
                                        if(isset($request->mod)){
                                            for($index = 0; $index < count($request->mod); $index++){
                                                ProductionRuncardStationMOD::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'mod_id' => $request->mod[$index],
                                                    'type_of_ng' => $request->type_of_ng[$index],
                                                    'mod_qty' => $request->mod_qty[$index],
                                                    'created_by' => Auth::user()->id,
                                                    'last_updated_by' => Auth::user()->id,
                                                    'update_version' => 1,
                                                    'status' => 1,
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                ]);
                                            }
                                        }
                                        else{
                                            $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                            $return_body = ' Please Fill-up MOD!';
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                            return $return;
                                        }
                                    }
                                    else{
                                        // Don't save the MOD
                                    }


                                    /**
                                    * Multiple insert for Prod Runcard Station Materials
                                    * 08-16-2022
                                    * -JD
                                    */
                                    if(isset($request->materialArray)){
                                        for($index = 0; $index < count($request->materialArray); $index++){
                                            ProductionRuncardStationMaterial::insert([
                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                'sub_station_id' => $request->sub_station_id,
                                                'material_id' => $request->materialArray[$index][0],
                                                'material_name' => $request->materialArray[$index][1],
                                                'lot_no' => $request->materialArray[$index][2],
                                                'material_type' => $request->materialArray[$index][3],
                                                'status' => 1,
                                                'created_by' => Auth::user()->id,
                                                'last_updated_by' => Auth::user()->id,
                                                'updated_at' => date('Y-m-d H:i:s'),
                                                'created_at' => date('Y-m-d H:i:s')
                                            ]);
                                        }
                                    }
                                    DB::commit();
                                    $result = 1;
                                } catch (Exception $e) {
                                    DB::rollback();
                                    $result = 0;
                                }
                            }

                            if( $result == 0 ){
                                $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                                $return_body = 'An error occured. The record not saved.';
                            }

                            $prodRuncard = ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)->get();

                            /* Maint Return */
                            $return = array('result' => $result, 'title'=>$return_title,'body'=>$return_body, 'runcardRequiredFieldsArray'=>$runcardRequiredFieldsArray, 'prodRuncard' => $prodRuncard);
                            return $return;
                        }
                        else{
                            $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                            $return_body = ' Scanned QR Code is not Operator.';
                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                            return $return;
                        }
                    }
                    else{
                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                        $return_body = ' Invalid Employee No.';
                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                        return $return;
                        // return '0';
                    }
                }

            }
        }

        // For Insert of Station
        else{ // For Multiple Runcard
            $validator = Validator::make($data, [
                // 'txt_prod_runcard_id_query' => ['required'],
                'selected_prod_runcard' => ['required'],
                'qty_input' => ['required'],
                'qty_output' => ['required'],
                'qty_ng' => ['required'],
                'txt_employee_number_scanner' => ['required'],
                // 'step_num' => ['required'],
                'sub_station_id' => ['required'],
                // 'lot_no' => ['required'], // 08-16-2022 -JD
                // 'material_id' => ['required'], // 08-11-2022 -JD
                // 'material_type' => ['required'], // 08-16-2022 -JD
                'process_flow_id' => ['required'], // 08-16-2022 -JD
            ]);

            if ($validator->fails()) {
                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                $return_body = 'Please complete required fields.';
                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
                return $return;
            }
            else{

                $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

                if($user->count() > 0){
                    if($user[0]->position == 4){
                        // if( $request->txt_prod_runcard_station_id_query ){

                                $processFlow = ProcessFlow::find($request->process_flow_id);
                                if( isset($processFlow->id) ){

                                    if( $processFlow->id != (int)$request->sub_station_id ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Invalid Process.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                        return $return;
                                    }

                                    // $operatorCountDetected = 0;
                                    // $operatorCountDetectedNotSet = 0;
                                    // $operatorsIDSToCheck = [];
                                    // if( $isMorning )
                                    //     $operatorsIDSToCheck = explode(',', $processFlow->a_shift_user_id);
                                    // else
                                    //     $operatorsIDSToCheck = explode(',', $processFlow->b_shift_user_id);

                                    // $operatorsIDSToCompare = $request->txt_edit_prod_runcard_operator;

                                    // for ($i=0; $i < count($operatorsIDSToCompare); $i++) {
                                    //     if( in_array($operatorsIDSToCompare[$i], $operatorsIDSToCheck) )
                                    //         $operatorCountDetected++;
                                    //     else
                                    //         $operatorCountDetectedNotSet++;
                                    // }

                                    // if( ($operatorCountDetected != count($operatorsIDSToCheck) || count($operatorsIDSToCheck) != count($operatorsIDSToCompare)) || !($operatorCountDetected > 0 && $operatorCountDetectedNotSet == 0) ) {
                                    //     $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                    //     $return_body = " Operator/s is not certified.";
                                    //     $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    //     return $return;
                                    // }

                                    $machineCountDetected = 0;
                                    $machinesIDSToCheck = explode(',', $processFlow->machine_id);

                                    $machinesIDSToCompare = $request->txt_edit_prod_runcard_station_machine;

                                    for ($i=0; $i < count($machinesIDSToCompare); $i++) {
                                        if( in_array($machinesIDSToCompare[$i], $machinesIDSToCheck) )
                                            $machineCountDetected++;
                                    }

                                    if( $machineCountDetected != count($machinesIDSToCheck) || count($machinesIDSToCheck) != count($machinesIDSToCompare) ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = " Invalid Selected Machine's.";
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    // if( $processFlow->material_name != $request->material_id ) {
                                    //     $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                    //     $return_body = ' Invalid Material.';
                                    //     $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    //     return $return;
                                    // }

                                    // if( $processFlow->lot_no != $request->lot_no ) {
                                    //     $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                    //     $return_body = ' Invalid Lot No. 3.';
                                    //     $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    //     return $return;
                                    // }

                                    $material = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                                        ->where("material_name", $request->material_id)
                                        ->get();
                                    if( count($material) == 0 ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Invalid Material.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    $material = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                                        ->where("lot_no", $request->lot_no)
                                        ->get();
                                    if( count($material) == 0 ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Invalid Lot No. 1.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    $material = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                                        ->where("material_name", $request->material_id)
                                        ->where("lot_no", $request->lot_no)
                                        ->get();
                                    if( count($material) == 0 ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Invalid Material Name and Lot No. is not match.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                } else {
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = ' Process Flow not found.';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    return $return;
                                }

                            // if($validator->passes()){
                                DB::beginTransaction();

                                $selected_prod_runcard = explode(',', $request->selected_prod_runcard);
                                // return count($selected_prod_runcard);
                                if(count($selected_prod_runcard) > 0){
                                    // return $selected_prod_runcard;
                                    $counter = 0;
                                    try {
                                        ProductionRuncard::whereIn('id', $selected_prod_runcard)
                                            ->update(
                                                [
                                                    'status'=> 3,
                                                    'last_updated_by' => $user[0]->id,
                                                    'updated_at' => date('Y-m-d H:i:s')
                                                ]
                                            );


                                        // return $selected_prod_runcard;
                                        for ($outer_index = 0; $outer_index < count($selected_prod_runcard); $outer_index++) {
                                            $counter++;

                                                $last_prod_runcard_station = ProductionRuncardStation::where('production_runcard_id', $selected_prod_runcard[$outer_index])->orderBy('id', 'desc')->first();

                                                $step_num = 1;

                                                if($last_prod_runcard_station != null){
                                                    $step_num = $last_prod_runcard_station->step_num + 1;
                                                }


                                                $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                                    'production_runcard_id'=> $selected_prod_runcard[$outer_index],
                                                    // 'mat_proc_id'=> $material_process[$index]->id,
                                                    // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                                    // 'sub_station_id'=> $request->sub_station_id,
                                                    // 'step_num'=> $step_num,
                                                    'process_flow_id'=> $request->process_flow_id,
                                                    'qty_input'=>$request->qty_input,
                                                    'qty_output'=>$request->qty_output,
                                                    'qty_ng'=>$request->qty_ng,
                                                    'status' => 1,
                                                    'created_by'=> Auth::user()->id,
                                                    'last_updated_by'=> Auth::user()->id,
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                ]);

                                                // Save Operators
                                                if(isset($request->txt_edit_prod_runcard_operator)){
                                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                        ProductionRuncardStationOperator::insert([
                                                            'production_runcard_id' => $selected_prod_runcard[$outer_index],
                                                            'production_runcard_station_id' => $prod_runcard_station_id,
                                                            'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                        ]);
                                                    }
                                                }

                                                // Save Machines
                                                if(isset($request->txt_edit_prod_runcard_station_machine)){
                                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                        ProductionRuncardStationMachine::insert([
                                                            'production_runcard_id' => $selected_prod_runcard[$outer_index],
                                                            'production_runcard_station_id' => $prod_runcard_station_id,
                                                            'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                        ]);
                                                    }
                                                }

                                                // if has NG only
                                                if($request->qty_ng > 0){
                                                    // Required to save MOD if has NG only
                                                    if(isset($request->mod)){
                                                        for($index = 0; $index < count($request->mod); $index++){
                                                            ProductionRuncardStationMOD::insert([
                                                                'production_runcard_id' => $selected_prod_runcard[$outer_index],
                                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                                'mod_id' => $request->mod[$index],
                                                                'type_of_ng' => $request->type_of_ng[$index],
                                                                'mod_qty' => $request->mod_qty[$index],
                                                                'created_by' => Auth::user()->id,
                                                                'last_updated_by' => Auth::user()->id,
                                                                'update_version' => 1,
                                                                'status' => 1,
                                                                'created_at' => date('Y-m-d H:i:s'),
                                                                'updated_at' => date('Y-m-d H:i:s'),
                                                            ]);
                                                        }
                                                    }
                                                    else{
                                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                        $return_body = ' Please Fill-up MOD!';
                                                        $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                                                        return $return;
                                                    }
                                                }
                                                else{
                                                    // Don't save the MOD
                                                }


                                                /**
                                                 * Multiple insert for Prod Runcard Station Materials(INSERT)
                                                 * 08-16-2022
                                                 * -JD
                                                 */
                                                if(isset($request->materialArray)){
                                                    for($index = 0; $index < count($request->materialArray); $index++){
                                                        ProductionRuncardStationMaterial::insert([
                                                            'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                            'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                            'sub_station_id' => $request->process,
                                                            'material_id' => $request->materialArray[$index][0],
                                                            'material_name' => $request->materialArray[$index][1],
                                                            'lot_no' => $request->materialArray[$index][2],
                                                            'material_type' => $request->materialArray[$index][3],
                                                            'status' => 1,
                                                            'created_by' => Auth::user()->id,
                                                            'last_updated_by' => Auth::user()->id,
                                                            'updated_at' => date('Y-m-d H:i:s'),
                                                            'created_at' => date('Y-m-d H:i:s')
                                                        ]);
                                                    }
                                                }

                                                // $result = true;

                                        }

                                        // return $counter;
                                        DB::commit();
                                        // ProductionRuncard::whereIn('id', $selected_prod_runcard)
                                        //     ->update(
                                        //         [
                                        //             'status'=> 3,
                                        //             'last_updated_by' => $user[0]->id,
                                        //             'updated_at' => date('Y-m-d H:i:s')
                                        //         ]
                                        //     );
                                        $result = 1;
                                    } catch (Exception $e) {
                                        DB::rollback();
                                        $result = 0;
                                    }

                                }
                                else{
                                    DB::rollback();
                                    $result = 0;
                                }
                        // }

                        if( $result == 0 ){
                            $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                            $return_body = 'An error occured. The record not saved.';
                        }
                        $return = array('result' => $result, 'title'=>$return_title,'body'=>$return_body);
                        return $return;
                    }
                    else{
                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                        $return_body = ' Scanned QR Code is not Operator.';
                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                        return $return;
                    }
                }
                else{
                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                    $return_body = ' Invalid Employee No.';
                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                    return $return;
                    // return '0';
                }

            }
        }

    }

    public function edit_prod_runcard_station_with_defect(Request $request){
        // return 'working function';
        // //DB::commit(); mdr checking
        date_default_timezone_set('Asia/Manila');
        $timeNow = date('H:i');
        $time = explode(':', $timeNow);
        $hour = (int)$time[0];
        $min = (int)$time[1];

        // 7:30AM to 19:29PM A shift (Morning)
        // 19:30PM to 7:29AM B shift (Evening)
        $isMorning = false;
        if( $hour >= 7 && $hour <= 19 ) {
            $isMorning = true;
            if( $hour == 7 && $min < 30 )
                $isMorning = false;
            if( $hour == 19 && $min >= 30 )
                $isMorning = false;
        }
        $processFlow = ProcessFlow::find($request->process_flow_id);
        // return response()->json(['processFlow' => $processFlow]);
        // return response()->json([
        //     'request' => $request->all(),
        //     'processFlow' => $processFlow,
        //     'timeNow' => $timeNow,
        //     'hour' => $hour,
        //     'min' => $min,
        //     'isMorning' => $isMorning,
        // ]);

        $return_title = '<i class="fa fa-check-circle text-success"></i> SAVED';
        $return_body = '<center><p style="color:green;">Record has been saved.</p></center>';
        $result = 1;

        /**
         * For checking of process if "Auto Insertion / #MO Installation"
         * then add validation for Mold, C/T Supplier, Die No & Pair #
         * -JD
         */
        $runcardRequiredFieldsArray = [
            'mold'                  =>null,
            'ct_supplier'           =>null,
            'die_no'                =>null,
            'pair_no'               =>null,
            'is_auto_insertion'     =>false,
            'is_full_assembly'      =>false,
            'work_week'             =>null,
            'qty_input'             =>null,
            'qty_output'            =>null,
        ];
        $runcardRequiredFieldsArrayHasNull = true;
        $fullAssemblyRequiredFieldsArrayHasNull = true;

        $data = $request->all();

        // return $request->txt_prod_runcard_id_query;

         // For Edit of Station
        if((isset($request->txt_prod_runcard_id_query) || $request->txt_prod_runcard_id_query != null || $request->txt_prod_runcard_id_query != '')){
            // return 'qwe';
            // For Edit Prod Station, runcard station should be set on this state
            if(isset($request->txt_prod_runcard_station_id_query)){
                // return 'edit process';
                // return isset($request->runcard_defect) ? $request->runcard_defect : 'not set';
                // return "pining bading";
                $validator = Validator::make($data, [
                    'txt_prod_runcard_id_query' => ['required'],
                    'txt_prod_runcard_station_id_query' => ['required'],
                    'qty_input' => ['required'],
                    'qty_output' => ['required'],
                    'qty_ng' => ['required'],
                    'txt_employee_number_scanner' => ['required'],
                    // 'step_num' => ['required'],

                    // 'sub_station_id' => ['required'], // 08-16-2022 -JD
                    // 'lot_no' => ['required'], // 08-16-2022 -JD
                    // 'material_id' => ['required'], // 08-11-2022 -JD
                    // 'material_type' => ['required'], // 08-16-2022 -JD
                    'process_flow_id' => ['required'],
                ]);
                if ($validator->fails()) {
                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                    $return_body = 'Please complete required fields.';
                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
                    return $return;
                }
                else{
                    $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

                    if($user->count() > 0){
                        if($user[0]->position == 4){
                            if( $request->txt_prod_runcard_station_id_query ){
                                $processFlow = ProcessFlow::find($request->process_flow_id);
                                if( isset($processFlow->id) ){
                                    // if( $processFlow->id != (int)$request->sub_station_id ) { // Old 09-15-2022 -JD
                                    if( $processFlow->id != (int)$request->process_flow_id ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Invalid Process.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                        return $return;
                                    }

                                    /**
                                     * Operator is required to scan their id
                                     * 01-20-2023
                                     * -JD
                                     */
                                    if(!isset($request->txt_edit_prod_runcard_operator)){
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = " Operator/s is required";
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    /**
                                     * Additional rules for Process Flow Machine(Matrix) vs Machine(Runcard Station Machine Selected)
                                     * 09-15-2022
                                     * -JD
                                     */
                                    $machineCountDetected = 0;
                                    $machinesNotIncludedInMatrixArray = []; // -JD
                                    $hasOneMachine = false; // -JD
                                    $hasDefectEscalation = false; // -JD 11-28-2022
                                    $machinesIDSToCheck = explode(',', $processFlow->machine_id); // From Process Flow(Matrix)
                                    // echo 'Process flow machines and count ' . count($machinesIDSToCheck)  . " ";
                                    // print_r($machinesIDSToCheck);
                                    // echo "\r\n";

                                    $machinesIDSToCompare = $request->txt_edit_prod_runcard_station_machine; // Runcard Station Machines
                                    // echo 'Runcard station machines and count ' . count($machinesIDSToCompare) . " ";
                                    // print_r($machinesIDSToCompare);
                                    // echo "\r\n";
                                    // return count($machinesIDSToCheck);
                                    if(count($machinesIDSToCheck) > 0){
                                        if(isset($machinesIDSToCompare)){
                                            for ($i=0; $i < count($machinesIDSToCompare); $i++) {
                                                if( in_array($machinesIDSToCompare[$i], $machinesIDSToCheck) ){
                                                    $machineCountDetected++;
                                                    $hasOneMachine = true;
                                                }elseif(isset($request->runcard_for_defect)){
                                                    $hasDefectEscalation = true;
                                                }else{
                                                    $machinesNotIncludedInMatrixArray[] = $machinesIDSToCompare[$i];
                                                }
                                            }
                                            // return "has def = $hasDefectEscalation";
                                            if($hasOneMachine || $hasDefectEscalation){
                                                // echo 'hasOneMachine true';
                                                // echo "\r\n";
                                                if(count($machinesIDSToCompare) <= count($machinesIDSToCheck)){
                                                    // echo "machinesIDSToCompare ". count($machinesIDSToCompare) ." is not more than machinesIDSToCheck " . count($machinesIDSToCheck);
                                                    // echo "\r\n";
                                                    if(count($machinesNotIncludedInMatrixArray) > 0){
                                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                                        $return_body = "Some Selected Machine is not registered based on Matrix.";
                                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                        return $return;
                                                    }
                                                }else{
                                                    // echo "machinesIDSToCompare ". count($machinesIDSToCompare) ." is more than machinesIDSToCheck " . count($machinesIDSToCheck);
                                                    // echo "\r\n";
                                                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                                    $return_body = "Please check the Selected Machine registered based on Matrix.";
                                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                    return $return;
                                                }

                                            }else{
                                                // echo 'hasOneMachine false';
                                                // echo "\r\n";
                                                $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                                $return_body = " No Machine/s found on the Matrix.";
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                return $return;
                                            }
                                            // echo 'machinesNotIncludedInMatrixArray ';
                                            // return $machinesNotIncludedInMatrixArray;

                                        }else{
                                            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                            $return_body = "Please select one or more Machine based on Matrix.";
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                            return $return;
                                        }
                                    }
                                    // return "tama lahat";
                                } else {
                                    $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                    $return_body = ' Process Flow not found.';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    return $return;
                                }

                                /**
                                 * Scanning of material is required based on the registered materials in Process Flow(Matrix)
                                 * 01-26-2023
                                 * -JD
                                 */
                                // if(isset($request->materialArray)){
                                //     $materialDetails = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                                //     ->get();
                                //     // return $materialDetails;

                                //     /* Total count of scanned materials should be equal to registered materials in Process Flow(Matrix)*/
                                //     if( count($materialDetails) != count($request->materialArray) ) {
                                //         $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> NOT SAVED!';
                                //         $return_body = ' <center>Material/s is not equal based on the Matrix! <br> Please call your immediate Superior!</center>';
                                //         $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                //         return $return;
                                //     }
                                // }


                                /**
                                 * Count of Scanned Materials vs Process Matrix(Materials) should be equal
                                 * 04-04-2023
                                 * -JD
                                 */
                                if(!isset($request->runcard_defect)){
                                    $device = Device::with('process_flow')->where("name", $request->deviceName)->get();
                                    $deviceProcessFlowMaterialsArray = [];
                                    $process_flow_ids = [];
                                    $hasWrongMaterialsAdded = false;
                                    if( count($device) > 0 ) {
                                        $process_flows = $device[0]->process_flow;
                                        for ($i=0; $i < count($process_flows); $i++) {
                                            $process_flow_ids[] = $process_flows[$i]->id;
                                        }
                                    }
                                    // return "count " . count($device[0]->process_flow);
                                    // return $request->process_flow_id;
                                    // return $process_flow_ids;

                                    $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::whereIn("process_flow_id", $process_flow_ids)->where('is_deleted',0)->where('is_deleted',0)
                                    ->get();
                                    /* Get the same Materials based on Process flow(Matrix) and Process flow($request) */
                                    for ($i=0; $i < count($deviceProcessFlowMaterials); $i++) {
                                        if($deviceProcessFlowMaterials[$i]->process_flow_id == $request->process_flow_id){
                                            $deviceProcessFlowMaterialsArray[] = $deviceProcessFlowMaterials[$i]->material_name;
                                        }
                                    }
                                    // return isset($request->materialArray) ? count($request->materialArray) : 'null';
                                    // return $deviceProcessFlowMaterialsArray;

                                    if(count($deviceProcessFlowMaterialsArray) > 0){
                                        /* If no materials added then execute below code */
                                        if(!isset($request->materialArray)){
                                            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                            $return_body = ' Please add materials based on the Process Matrix.';
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                            return $return;
                                        }

                                        /* If count of Scanned Materials is not equal based on the Process Matrix(Materials) */
                                        if(count($request->materialArray) != count($deviceProcessFlowMaterialsArray)){
                                            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                            $return_body = ' Count of added materials is incorrect based on the Process Matrix.';
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                            return $return;
                                        }

                                        /* If on or more Scanned Materials is incorrect based on the Process Matrix(Materials) */
                                        // return $request->materialArray[0][1];
                                        for ($i=0; $i < count($deviceProcessFlowMaterialsArray); $i++) {
                                            // return $deviceProcessFlowMaterialsArray[$i];
                                            for ($j=0; $j < count($request->materialArray); $j++) {
                                                // if($request->materialArray[$j][1] != $deviceProcessFlowMaterialsArray[$i]){
                                                if(!in_array($request->materialArray[$j][1], $deviceProcessFlowMaterialsArray)){ /* If the loop item is not included in the array */
                                                    $hasWrongMaterialsAdded = 'true';
                                                }
                                            }
                                        }
                                        if($hasWrongMaterialsAdded == 'true'){
                                            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                            $return_body = ' Some added materials is incorrect based on the Process Matrix.';
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                            return $return;
                                        }
                                    }
                                    // return count($deviceProcessFlowMaterialsArray);
                                }


                                /**
                                 * Check the process if "Auto Insertion / #MO Installation"
                                 * then add validation for Mold, C/T Supplier, Die No & Pair #
                                 * -JD
                                 */
                                $process = ProductionRuncardStation::with('process_flow.processFlow')
                                ->where('id',$request->txt_prod_runcard_station_id_query)
                                ->where('process_flow_id', $processFlow->id)
                                ->get();
                                // echo "prod runcard station id ". $request->txt_prod_runcard_station_id_query;
                                // echo "\r\n";
                                // echo "process_flow_id ". $processFlow->id;
                                // echo "\r\n";
                                // return $process[0]->process_flow->processFlow->name;

                                // if($process[0]->process_flow->processFlow->name == "Auto Insertion / #MO Installation"){
                                // updated by nessa 02072023
                                if($process[0]->process_flow->processFlow->name == "Auto Insertion / #MO Installation" || $process[0]->process_flow->processFlow->name == "Contact Insertion - Auto Insertion" || $process[0]->process_flow->processFlow->name == "Auto Insertion"){
                                    $runcardRequiredFieldsArray['is_auto_insertion'] = true;

                                    $prodRuncard = ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                                    ->get();
                                    if($prodRuncard[0]->mold != null || $prodRuncard[0]->mold != ''){
                                        $runcardRequiredFieldsArray['mold'] = $prodRuncard[0]->mold;
                                    }

                                    if($prodRuncard[0]->ct_supplier != null || $prodRuncard[0]->ct_supplier != ''){
                                        $runcardRequiredFieldsArray['ct_supplier'] = $prodRuncard[0]->ct_supplier;
                                    }

                                    if($prodRuncard[0]->die_no != null || $prodRuncard[0]->die_no != ''){
                                        $runcardRequiredFieldsArray['die_no'] = $prodRuncard[0]->die_no;
                                    }

                                    if($prodRuncard[0]->pair_no != null || $prodRuncard[0]->pair_no != ''){
                                        $runcardRequiredFieldsArray['pair_no'] = $prodRuncard[0]->pair_no;
                                    }
                                    /* if the pair number*/
                                    // return $runcardRequiredFieldsArray;
                                }

                                /**
                                 * If "Auto Insertion / #MO Installation" is true and has no null values on the required fields (Mold, C/T Supplier, etc.)
                                 * then set the $runcardRequiredFieldsArrayHasNull to false
                                 */
                                if($runcardRequiredFieldsArray['is_auto_insertion'] == true){
                                    // return 'here';
                                    if($runcardRequiredFieldsArray['mold'] != null && $runcardRequiredFieldsArray['ct_supplier'] != null && $runcardRequiredFieldsArray['die_no'] != null && $runcardRequiredFieldsArray['pair_no'] != null){
                                        DB::beginTransaction();
                                        try {
                                            ProductionRuncardStation::where('id',$request->txt_prod_runcard_station_id_query)
                                                ->update(
                                                    [
                                                        'qty_input'=>$request->qty_input,
                                                        'qty_output'=>$request->qty_output,
                                                        'qty_ng'=>$request->qty_ng,
                                                        'partial'=>$request->qty_partial,
                                                        'runcard_defect'=>$request->runcard_defect,
                                                        'remarks'=>$request->remarks,
                                                        'magni'=>$request->magni,
                                                        // 'status' => 1,
                                                        // 'sub_station_id' => $request->sub_station_id,
                                                    ]
                                                );

                                            // Save Operators
                                            ProductionRuncardStationOperator::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                                ->delete();

                                            if(isset($request->txt_edit_prod_runcard_operator)){
                                                for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                    ProductionRuncardStationOperator::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                        'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                    ]);
                                                }
                                            }

                                            // angelo
                                            // Save Machines
                                            ProductionRuncardStationMachine::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                                ->delete();

                                            if(isset($request->txt_edit_prod_runcard_station_machine)){
                                                for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                    ProductionRuncardStationMachine::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                        'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                    ]);
                                                }
                                            }

                                            ProductionRuncardStationMOD::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                                ->delete();


                                        $existingDefect = DefectEscalationV2Rev::orderBy('id', 'desc')
                                            ->where('prod_runcard_id',$request->txt_prod_runcard_station_id_query)
                                            ->first();

                                            // return $request->txt_prod_runcard_station_id_query;
                                            // return 'asd';
                                            // return $existingDefect;450259238800010

                                            $ts = "TSF1";
                                            $lineWithDefect = $request->line_with_defect;
                                            $de_occurence = 1;

                                            // return $lineWithDefect;

                                            $time = Carbon::now();
                                            $todayStartA = $time->copy()->setTime(7, 30); // 7:30 AM
                                            $todayEndA   = $time->copy()->setTime(19, 29); // 7:29 PM

                                            $isMorning = $time->between($todayStartA, $todayEndA);

                                            $shift = $isMorning ? 1 : 2;
                                            $mod = $request->mode_of_defect;

                                            if($request->qty_ng > 0){
                                                // return 'here';
                                                // Required to save MOD if has NG only
                                               $selectedMod = $request->mod ?? [];
                                                if(count($selectedMod)){
                                                    // return 'asdasdasd';
                                                    try{
                                                          for($index = 0; $index < count($selectedMod); $index++){
                                                            ProductionRuncardStationMOD::insert([
                                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                                'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                                'mod_id' => $request->mod[$index],
                                                                'type_of_ng' => $request->type_of_ng[$index],
                                                                'mod_qty' => $request->mod_qty[$index],
                                                                'created_by' => Auth::user()->id,
                                                                'last_updated_by' => Auth::user()->id,
                                                                'update_version' => 1,
                                                                'status' => 1,
                                                                'created_at' => date('Y-m-d H:i:s'),
                                                                'updated_at' => date('Y-m-d H:i:s'),
                                                            ]);

                                                            $current_class = $request->mod_classification[$index];

                                                            if(str_contains($current_class,'1')){
                                                                $defect_text = 'A1';
                                                            }if(str_contains($current_class,'2')){
                                                                $defect_text = 'A2';
                                                            }else if(str_contains($current_class,'3')){
                                                                $defect_text = 'A3';
                                                            }else if(str_contains($current_class, '4')){
                                                                $defect_text = 'A4';
                                                            }else if(str_contains($current_class, '5')){
                                                                $defect_text = 'A5';
                                                            }

                                                            $defectCtrltxt = $ts . '-' . $lineWithDefect . '-' . $defect_text . '-' . date('m') . date('y');


                                                            // return $defectCtrltxt;

                                                            // Logic to increment $defectCtrlNumber (example: finding the next sequence)
                                                            // $lastRecord = DefectEscalationV2Rev::where('defect_ctrl_txt', $defectCtrltxt)->latest()->first();
                                                            $lastRecord = DefectEscalationV2Rev::orderBy('id', 'desc')->first();

                                                            // return 'asd';
                                                            // return $request->mod_classification; // MALAG

                                                            $mod_classification = $request->mod_classification;

                                                            // 1. Flatten the data: Convert ["1,2", "2"] into [1, 2, 2]
                                                            $flattened = [];
                                                            foreach ($mod_classification as $item) {
                                                                // split by comma and merge into the flattened array
                                                                $flattened = array_merge($flattened, explode(',', $item));
                                                            }
                                                            // 2. Remove duplicates (optional, but cleaner)
                                                            $unique_classes = array_unique($flattened);

                                                            // edit da
                                                            // 3. Perform your Validation
                                                            // if (in_array('1', $unique_classes) && count($unique_classes) > 1) {
                                                            //     $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                            //     $return_body = ' CRITICAL DEFECT cannot be combined with other Defect Classifications!';
                                                            //     return array('result' => 3, 'title' => $return_title, 'body' => $return_body);
                                                            // }

                                                            // return $lastRecord;

                                                            // return $lastRecord;
                                                            if($lastRecord == null || ''){
                                                                // return 'asd';
                                                                $defectCtrlNumber = '0001';
                                                            }else{
                                                                // return 'qwe';
                                                                $nextSequence = $lastRecord ? (intval($lastRecord->defect_ctrl_number) + 1) : 1;
                                                                $defectCtrlNumber = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
                                                            }

                                                            // return [
                                                            //     'po_number'                    => $request->po_number_with_defect,  //shadow  beast
                                                            //     'defect_ctrl_txt'              => $defectCtrltxt,       // Now unique per loop
                                                            //     'defect_ctrl_number'           => $defectCtrlNumber,    // Now unique per loop
                                                            //     'prod_runcard_id'              => $request->txt_prod_runcard_id_query,
                                                            //     'prod_runcard_station_id'      => $request->txt_prod_runcard_station_id_query,
                                                            //     'prod_runcard_station_mod_id'  => $request->mod[$index],
                                                            //     'defect_classification'        => $current_class,
                                                            //     'defect_qty'                   => $request->mod_qty[$index],
                                                            //     'mod_type'                     => $request->type_of_ng[$index],
                                                            //     'mod_id'                       => $mod[$index],
                                                            //     'de_occurence'                 => $de_occurence,
                                                            //     'de_group'                     => 1,
                                                            //     'defect_status'                => 0,
                                                            //     'de_user'                      => $user[0]->id,
                                                            //     'de_position'                  => $user[0]->position,
                                                            //     'created_by'                   => Auth::user()->id,
                                                            //     'created_at'                   => now(),
                                                            //     'de_shift'                     => $shift,
                                                            //     'de_date'                      => date('Y-m-d'),
                                                            //     'de_time'                      => date('h:i:s A'),
                                                            //     'line'                         => $lineWithDefect,
                                                            //     'line_id'                      => $request->line_with_defect_id,
                                                            // ];

                                                            // return 'qweqweqwe';

                                                            // edit da
                                                            $inserted = DefectEscalationV2Rev::insert([
                                                                'po_number'                    => $request->po_number_with_defect,
                                                                'defect_ctrl_txt'              => $defectCtrltxt,       // Now unique per loop
                                                                'defect_ctrl_number'           => $defectCtrlNumber,    // Now unique per loop
                                                                'prod_runcard_id'              => $request->txt_prod_runcard_id_query,
                                                                'prod_runcard_station_id'      => $request->txt_prod_runcard_station_id_query,
                                                                // 'prod_runcard_station_mod_id'  => $request->mod[$index],
                                                                'defect_classification'        => $current_class,
                                                                'defect_qty'                   => $request->mod_qty[$index],
                                                                'mod_type'                     => $request->type_of_ng[$index],
                                                                'mod_id'                       => $mod[$index],
                                                                'de_occurence'                 => $de_occurence,
                                                                'de_group'                     => 1,
                                                                'defect_status'                => 0,
                                                                'de_user'                      => $user[0]->id,
                                                                'de_position'                  => $user[0]->position,
                                                                'created_by'                   => Auth::user()->id,
                                                                'created_at'                   => now(),
                                                                'de_shift'                     => $shift,
                                                                'de_date'                      => date('Y-m-d'),
                                                                'de_time'                      => date('h:i:s A'),
                                                                'line'                         => $lineWithDefect,
                                                                'line_id'                      => $request->line_with_defect_id,
                                                            ]);

                                                        // dd([
                                                        //     'inserted' => $inserted,
                                                        //     'queries' => DB::getQueryLog(),
                                                        // ]);

                                                        // $record = DefectEscalationV2Rev::where('defect_ctrl_number', $defectCtrlNumber)
                                                        //     ->where('defect_ctrl_txt', $defectCtrltxt)
                                                        //     ->first();

                                                        // dd($record);

                                                        }
                                                    } catch (\Exception $e){
                                                        DB::rollback();
                                                        // return '1';
                                                        // dd($e->getMessage());
                                                        throw $e;
                                                    }


                                                        // DefectEscalationV2
                                                }else{
                                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                    $return_body = ' Please Fill-up MOD!';
                                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                    return $return;
                                                }


                                                    if(blank($existingDefect)){

                                                        if($request->qty_ng > 0){
                                                            if(isset($mod)){
                                                                for ($index=0; $index < count($mod) ; $index++) {


                                                                }


                                                            $mod_classification = $request->mod_classification;
                                                            // return '> 0';
                                                            // If it’s an array with a single string like "1,2", explode it
                                                            if (is_array($mod_classification) && count($mod_classification) === 1) {

                                                                $mod_classification = explode(',', $mod_classification[0]);

                                                                if (in_array('1', $mod_classification) && count($mod_classification) > 1) {

                                                                    $return_title = '<i class="fa fa-exclamation-triangle te+xt-danger"></i> Not saved';
                                                                    $return_body = ' CRITICAL DEFECT cannot be combined with other Defect Classifications!';
                                                                    $return = array('result' => 3, 'title'=>$return_title,'body'=>$return_body);
                                                                    return $return;
                                                                }


                                                            }
                                                            // IF TWO OR MORE TOTAL NO of NG COUNT
                                                            else{
                                                                // return 'dito5';

                                                                // return 'TWO OR MORE TOTAL NO OF NG COUNT';
                                                            }
                                                            // Generate control text
                                                            $defectCtrltxt = $ts . '-' . $lineWithDefect . '-' . $defect_text . '-' . date('m') . date('y');

                                                            $defectCtrlNumber = '0001';


                                                        }
                                                    // FOR EXISTING DEFECT
                                                    }else{
                                                        // return 'not';
                                                        $nonCriticalDefects = DefectEscalationV2Rev::where('defect_classification', 2)
                                                            ->orWhere('defect_classification', 3)
                                                            ->orWhere('defect_classification', 4)
                                                            ->orWhere('defect_classification', 5)
                                                            ->where('de_shift', $shift)
                                                            ->get();

                                                        if (count($nonCriticalDefects) >= 5) {
                                                                $de_occurence = 2;
                                                                // $nonCriticalDefects = $nonCriticalDefects->de_group +1;
                                                        } else {
                                                            $de_occurence = 1;
                                                            // $nonCriticalDefects = $nonCriticalDefects->de_group;
                                                        }

                                                        // return $de_occurence;

                                                        $mod_classification = $request->mod_classification;

                                                        if (is_array($mod_classification) && count($mod_classification) === 1) {
                                                            $mod_classification = explode(',', $mod_classification[0]);

                                                            // return $mod_classification;

                                                            if (in_array('1', $mod_classification) && count($mod_classification) > 1) {

                                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                                $return_body = ' CRITICAL DEFECT cannot be combined with other Defect Classifications!';
                                                                $return = array('result' => 3, 'title'=>$return_title,'body'=>$return_body);
                                                                // return $return; //edit da
                                                            }else if(in_array('1', $mod_classification)){
                                                                $defect_text = "A1";
                                                                $defect_classification = 1;
                                                            }else if (in_array('2', $mod_classification)){
                                                                $defect_text = "A2";
                                                                $defect_classification = 2;
                                                            }else if (in_array('3', $mod_classification)){
                                                                $defect_text = "A3";
                                                                $defect_classification = 3;
                                                            }else if (in_array('4', $mod_classification)){
                                                                $defect_text = "A4";
                                                                $defect_classification = 4;
                                                            }

                                                            // return $defect_text; qty_ng

                                                        }
                                                        // IF TWO OR MORE TOTAL NO of NG COUNT
                                                        else{
                                                            // return 'dito2';

                                                            // return 'TWO OR MORE TOTAL NO OF NG COUNT';
                                                        }

                                                        // Generate control text
                                                        $defectCtrltxt = $existingDefect->defect_ctrl_txt;

                                                        // Increment the defect control number by 1
                                                        $lastNumber = (int)$existingDefect->defect_ctrl_number;
                                                        $newNumber = $lastNumber + 1;
                                                        // Pad the number with leading zeros to ensure it is 4 digits
                                                        $defectCtrlNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
                                                    }

                                                    // return $defectCtrltxt;

                                                }
                                                else{
                                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                    $return_body = ' Please Fill-up MOD!';
                                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                    return $return;
                                                }
                                            }
                                            else{
                                                // Don't save the MOD
                                                // return 'adsasdasd';
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                    $return_body = ' Please Fill-up MOD!';
                                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                    return $return;
                                            }

                                            /**
                                            * Multiple insert for Prod Runcard Station Materials
                                            * 08-16-2022
                                            * -JD
                                            */
                                            ProductionRuncardStationMaterial::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                            ->delete();
                                            if(isset($request->materialArray)){
                                                for($index = 0; $index < count($request->materialArray); $index++){
                                                    ProductionRuncardStationMaterial::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                        'sub_station_id' => $request->sub_station_id,
                                                        'material_id' => $request->materialArray[$index][0],
                                                        'material_name' => $request->materialArray[$index][1],
                                                        'lot_no' => $request->materialArray[$index][2],
                                                        'material_type' => $request->materialArray[$index][3],
                                                        'status' => 1,
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                        'created_at' => date('Y-m-d H:i:s')
                                                    ]);
                                                }
                                            }

                                            DB::commit();
                                            $result = 1;

                                        } catch (Exception $e) {
                                            DB::rollback();
                                            $result = 0;
                                        }
                                    }else{
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Please Fill-up required fields';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'runcardRequiredFieldsArray'=>$runcardRequiredFieldsArray);
                                        return $return;
                                    }
                                }
                                else{

                                    DB::beginTransaction();
                                    try {
                                        ProductionRuncardStation::where('id',$request->txt_prod_runcard_station_id_query)
                                            ->update(
                                                [
                                                    'qty_input'=>$request->qty_input,
                                                    'qty_output'=>$request->qty_output,
                                                    'qty_ng'=>$request->qty_ng,
                                                    'partial'=>$request->qty_partial,
                                                    'runcard_defect'=>$request->runcard_defect,
                                                    'remarks'=>$request->remarks,
                                                    'magni'=>$request->magni,
                                                    // 'status' => 1,
                                                    // 'sub_station_id' => $request->sub_station_id,
                                                ]
                                            );

                                        // angelo
                                        // Save Operators
                                        ProductionRuncardStationOperator::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                            ->delete();

                                        if(isset($request->txt_edit_prod_runcard_operator)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                ProductionRuncardStationOperator::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                    'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                ]);
                                            }
                                        }

                                        // angelo
                                        // Save Machines
                                        ProductionRuncardStationMachine::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                            ->delete();

                                        if(isset($request->txt_edit_prod_runcard_station_machine)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                ProductionRuncardStationMachine::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                    'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                ]);
                                            }
                                        }
                                        $existingDefect = DefectEscalationV2Rev::orderBy('id', 'desc')
                                            ->where('prod_runcard_id',$request->txt_prod_runcard_station_id_query)
                                            ->first();

                                            // return $request->txt_prod_runcard_station_id_query;
                                            // return 'asd';
                                            // return $existingDefect;450259238800010

                                            $ts = "TSF1";
                                            $lineWithDefect = $request->line_with_defect;
                                            $de_occurence = 1;

                                            // return $lineWithDefect;

                                            $time = Carbon::now();
                                            $todayStartA = $time->copy()->setTime(7, 30); // 7:30 AM
                                            $todayEndA   = $time->copy()->setTime(19, 29); // 7:29 PM

                                            $isMorning = $time->between($todayStartA, $todayEndA);

                                            $shift = $isMorning ? 1 : 2;
                                            $mod = $request->mode_of_defect;

                                    if(blank($existingDefect)){
                                        // return 'aaaa';
                                        if($request->qty_ng > 0){ //MIGZ - considered no defect or zero NG QTY - kada save kasi matik nanghihingi ng MOD even 0 NG QTY
                                        // return 'ng_qty';
                                            // $mod_classification = $request->mod_classification;
                                            // // return 'asdd';
                                            // // If it’s an array with a single string like "1,2", explode it
                                            // if (is_array($mod_classification) && count($mod_classification) === 1) {

                                            //     $mod_classification = explode(',', $mod_classification[0]);

                                            //     if (in_array('1', $mod_classification) && count($mod_classification) > 1) {

                                            //         $return_title = '<i class="fa fa-exclamation-triangle te+xt-danger"></i> Not saved';
                                            //         $return_body = ' CRITICAL DEFECT cannot be combined with other Defect Classifications!';
                                            //         $return = array('result' => 3, 'title'=>$return_title,'body'=>$return_body);
                                            //         return $return;
                                            //     }else if(in_array('1', $mod_classification)){
                                            //         $defect_text = "A1";
                                            //         $defect_classification = 1;
                                            //     }else if (in_array('2', $mod_classification)){
                                            //         $defect_text = "A2";
                                            //         $defect_classification = 2;
                                            //     }else if (in_array('3', $mod_classification)){
                                            //         $defect_text = "A3";
                                            //         $defect_classification = 3;
                                            //     }else if (in_array('4', $mod_classification)){
                                            //         $defect_text = "A4";
                                            //         $defect_classification = 4;
                                            //     }


                                            // }
                                            // // IF TWO OR MORE TOTAL NO of NG COUNT
                                            // else{


                                            //     return 'TWO OR MORE TOTAL NO OF NG COUNT';
                                            // }
                                            // // Generate control text
                                            // $defectCtrltxt = $ts . '-' . $lineWithDefect . '-' . $defect_text . '-' . date('m') . date('y');

                                            // $defectCtrlNumber = '0001';

                                            try{
                                                for($index = 0; $index < count($request->mod); $index++){
                                                ProductionRuncardStationMOD::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                    'mod_id' => $request->mod[$index],
                                                    'type_of_ng' => $request->type_of_ng[$index],
                                                    'mod_qty' => $request->mod_qty[$index],
                                                    'created_by' => Auth::user()->id,
                                                    'last_updated_by' => Auth::user()->id,
                                                    'update_version' => 1,
                                                    'status' => 1,
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                ]);

                                                $current_class = $request->mod_classification[$index];

                                                if(str_contains($current_class,'1')){
                                                    $defect_text = 'A1';
                                                }if(str_contains($current_class,'2')){
                                                    $defect_text = 'A2';
                                                }else if(str_contains($current_class,'3')){
                                                    $defect_text = 'A3';
                                                }else if(str_contains($current_class, '4')){
                                                    $defect_text = 'A4';
                                                }else if(str_contains($current_class, '5')){
                                                    $defect_text = 'A5';
                                                }

                                                $defectCtrltxt = $ts . '-' . $lineWithDefect . '-' . $defect_text . '-' . date('m') . date('y');


                                                // return $defectCtrltxt;

                                                // Logic to increment $defectCtrlNumber (example: finding the next sequence)
                                                // $lastRecord = DefectEscalationV2Rev::where('defect_ctrl_txt', $defectCtrltxt)->latest()->first();
                                                $lastRecord = DefectEscalationV2Rev::orderBy('id', 'desc')->first();

                                                // return 'asd';
                                                // return $request->mod_classification; // MALAG

                                                $mod_classification = $request->mod_classification;

                                                // 1. Flatten the data: Convert ["1,2", "2"] into [1, 2, 2]
                                                $flattened = [];
                                                foreach ($mod_classification as $item) {
                                                    // split by comma and merge into the flattened array
                                                    $flattened = array_merge($flattened, explode(',', $item));
                                                }
                                                // 2. Remove duplicates (optional, but cleaner)
                                                $unique_classes = array_unique($flattened);

                                                // 3. Perform your Validation
                                                // if (in_array('1', $unique_classes) && count($unique_classes) > 1) {
                                                //     $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                //     $return_body = ' CRITICAL DEFECT cannot be combined with other Defect Classifications!';
                                                //     return array('result' => 3, 'title' => $return_title, 'body' => $return_body);
                                                // }
                                                // return $lastRecord;

                                                // return $lastRecord;
                                                if($lastRecord == null || ''){
                                                    $defectCtrlNumber = '0001';
                                                }else{
                                                    $nextSequence = $lastRecord ? (intval($lastRecord->defect_ctrl_number) + 1) : 1;
                                                    $defectCtrlNumber = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
                                                }

                                                // return [
                                                //     'po_number'                    => $request->po_number_with_defect, //shadow  beast
                                                //     'defect_ctrl_txt'              => $defectCtrltxt,       // Now unique per loop
                                                //     'defect_ctrl_number'           => $defectCtrlNumber,    // Now unique per loop
                                                //     'prod_runcard_id'              => $request->txt_prod_runcard_id_query,
                                                //     'prod_runcard_station_id'      => $request->txt_prod_runcard_station_id_query,
                                                //     // 'prod_runcard_station_mod_id'  => $prod_runcard_station_mod_id,
                                                //     'defect_classification'        => $current_class,
                                                //     'defect_qty'                   => $request->mod_qty[$index],
                                                //     'mod_type'                     => $request->type_of_ng[$index],
                                                //     'mod_id'                       => $mod[$index],
                                                //     'de_occurence'                 => $de_occurence,
                                                //     'de_group'                     => 1,
                                                //     'defect_status'                => 0,
                                                //     'de_user'                      => $user[0]->id,
                                                //     'de_position'                  => $user[0]->position,
                                                //     'created_by'                   => Auth::user()->id,
                                                //     'created_at'                   => now(),
                                                //     'de_shift'                     => $shift,
                                                //     'de_date'                      => date('Y-m-d'),
                                                //     'de_time'                      => date('h:i:s A'),
                                                //     'line'                         => $lineWithDefect,
                                                //     'line_id'                      => $request->line_with_defect_id,
                                                // ];


                                                $inserted = DefectEscalationV2Rev::insert([
                                                    'po_number'                    => $request->po_number_with_defect,
                                                    'defect_ctrl_txt'              => $defectCtrltxt,       // Now unique per loop
                                                    'defect_ctrl_number'           => $defectCtrlNumber,    // Now unique per loop
                                                    'prod_runcard_id'              => $request->txt_prod_runcard_id_query,
                                                    'prod_runcard_station_id'      => $request->txt_prod_runcard_station_id_query,
                                                    // 'prod_runcard_station_mod_id'  => $request->mod[$index],
                                                    'defect_classification'        => $current_class,
                                                    'defect_qty'                   => $request->mod_qty[$index],
                                                    'mod_type'                     => $request->type_of_ng[$index],
                                                    'mod_id'                       => $mod[$index],
                                                    'de_occurence'                 => $de_occurence,
                                                    'de_group'                     => 1,
                                                    'defect_status'                => 0,
                                                    'de_user'                      => $user[0]->id,
                                                    'de_position'                  => $user[0]->position,
                                                    'created_by'                   => Auth::user()->id,
                                                    'created_at'                   => now(),
                                                    'de_shift'                     => $shift,
                                                    'de_date'                      => date('Y-m-d'),
                                                    'de_time'                      => date('h:i:s A'),
                                                    'line'                         => $lineWithDefect,
                                                    'line_id'                      => $request->line_with_defect_id,
                                                ]);

                                                //                                                             dd([
                                                //     'inserted' => $inserted,
                                                //     'queries' => DB::getQueryLog(),
                                                // ]);

                                                // $record = DefectEscalationV2Rev::where('defect_ctrl_number', $defectCtrlNumber)
                                                //     ->where('defect_ctrl_txt', $defectCtrltxt)
                                                //     ->first();

                                                // dd($record);

                                                }
                                            } catch (\Exception $e){
                                                DB::rollback();
                                                throw $e;
                                                // dd($e->getMessage());
                                            }
                                        }

                                    }else{
                                        // return 'qweqwe';
                                        $nonCriticalDefects = DefectEscalationV2Rev::where('defect_classification', 2)
                                            ->orWhere('defect_classification', 3)
                                            ->orWhere('defect_classification', 4)
                                            ->orWhere('defect_classification', 5)
                                            ->where('de_shift', $shift)
                                            ->get();

                                        if (count($nonCriticalDefects) >= 5) {
                                                $de_occurence = 2;
                                                // $nonCriticalDefects = $nonCriticalDefects->de_group +1;
                                        } else {
                                            $de_occurence = 1;
                                            // $nonCriticalDefects = $nonCriticalDefects->de_group;
                                        }

                                        // return $de_occurence;

                                        $mod_classification = $request->mod_classification;

                                        if (is_array($mod_classification) && count($mod_classification) === 1) {
                                            $mod_classification = explode(',', $mod_classification[0]);

                                            // return $mod_classification;

                                            if (in_array('1', $mod_classification) && count($mod_classification) > 1) {

                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = ' CRITICAL DEFECT cannot be combined with other Defect Classifications!';
                                                $return = array('result' => 3, 'title'=>$return_title,'body'=>$return_body);
                                                return $return;
                                            }else if(in_array('1', $mod_classification)){
                                                $defect_text = "A1";
                                                $defect_classification = 1;
                                            }else if (in_array('2', $mod_classification)){
                                                $defect_text = "A2";
                                                $defect_classification = 2;
                                            }else if (in_array('3', $mod_classification)){
                                                $defect_text = "A3";
                                                $defect_classification = 3;
                                            }else if (in_array('4', $mod_classification)){
                                                $defect_text = "A4";
                                                $defect_classification = 4;
                                            }

                                            // return $defect_text; qty_ng

                                        }
                                        // IF TWO OR MORE TOTAL NO of NG COUNT
                                        else{
                                            // return 'dito4';

                                            return 'TWO OR MORE TOTAL NO OF NG COUNT';
                                        }

                                        // Generate control text
                                        $defectCtrltxt = $existingDefect->defect_ctrl_txt;

                                        // Increment the defect control number by 1
                                        $lastNumber = (int)$existingDefect->defect_ctrl_number;
                                        $newNumber = $lastNumber + 1;
                                        // Pad the number with leading zeros to ensure it is 4 digits
                                        $defectCtrlNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
                                    }

                                        // return $request->qty_ng;

                                        ProductionRuncardStationMOD::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                            ->delete();
                                        // if has NG only
                                        if($request->qty_ng > 0){
                                            // Required to save MOD if has NG only
                                            // return 'asd';
                                            if(isset($request->mod)){
                                                for($index = 0; $index < count($request->mod); $index++){
                                                    ProductionRuncardStationMOD::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                        'mod_id' => $request->mod[$index],
                                                        'type_of_ng' => $request->type_of_ng[$index],
                                                        'mod_qty' => $request->mod_qty[$index],
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'update_version' => 1,
                                                        'status' => 1,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                    ]);


                                                    // Dito dapat insert



                                                }

                                                // return count($request->mod);
                                            }
                                            else{
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = ' Please Fill-up MOD!';
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                return $return;
                                            }
                                        }
                                        else{
                                            // Don't save the MOD
                                        }

                                        /**
                                        * Multiple insert for Prod Runcard Station Materials
                                        * 08-16-2022
                                        * -JD
                                        */

                                        ProductionRuncardStationMaterial::where('production_runcard_station_id', $request->txt_prod_runcard_station_id_query)
                                        ->delete();

                                        if(isset($request->materialArray)){
                                            for($index = 0; $index < count($request->materialArray); $index++){
                                                ProductionRuncardStationMaterial::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                    'sub_station_id' => $request->sub_station_id,
                                                    'material_id' => $request->materialArray[$index][0],
                                                    'material_name' => $request->materialArray[$index][1],
                                                    'lot_no' => $request->materialArray[$index][2],
                                                    'material_type' => $request->materialArray[$index][3],
                                                    'status' => 1,
                                                    'created_by' => Auth::user()->id,
                                                    'last_updated_by' => Auth::user()->id,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'created_at' => date('Y-m-d H:i:s')
                                                ]);
                                            }
                                        }

                                        DB::commit();
                                        $result = 1;

                                    } catch (Exception $e) {
                                        DB::rollback();
                                        $result = 0;
                                    }
                                }
                            }

                            if( $result == 0 ){
                                $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                                $return_body = 'An error occured. The record not saved.';
                            }

                            /* Main Return */
                            $return = array('result' => $result, 'title'=>$return_title,'body'=>$return_body, 'runcardRequiredFieldsArray'=>$runcardRequiredFieldsArray);
                            return $return;
                        }
                        else{
                            $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                            $return_body = ' Scanned QR Code is not Operator.';
                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                            return $return;
                        }
                    }
                    else{
                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> NOT SAVED!';
                        $return_body = ' <center> Invalid Employee No. </center>';
                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                        return $return;
                        // return '0';
                    }
                }

            }
            else{ // For Add Process, runcard station is not set on this state
                // return "add process";
                //add da
                $validator = Validator::make($data, [
                    'txt_prod_runcard_id_query' => ['required'],
                    'qty_input' => ['required'],
                    'qty_output' => ['required'],
                    'qty_ng' => ['required'],
                    'txt_employee_number_scanner' => ['required'],
                    // 'step_num' => ['required'],
                    'sub_station_id' => ['required'],
                    // 'lot_no' => ['required's], // 08-16-2022 -JD
                    // 'material_id' => ['required'], // 08-11-2022 -JD
                    // 'material_type' => ['required'], // 08-16-2022 -JD
                    'process_flow_id' => ['required'], // 08-16-2022 -JD
                ]);

                if ($validator->fails()) {
                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                    $return_body = 'Please complete required fields.';
                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
                    return $return;
                }
                else{
                    // return 'as';
                    $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

                    if($user->count() > 0){
                        if($user[0]->position == 4){ // 4-Operator
                            $processFlow = ProcessFlow::find($request->process_flow_id);
                            if( isset($processFlow->id) ){
                                // return 'asd';

                                // if( $processFlow->id != (int)$request->sub_station_id ) { // Old 09-14-2022 -JD
                                if( $processFlow->id != (int)$request->process_flow_id ) { // Process field
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = ' Invalid Process.';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                    return $return;
                                }

                                /**
                                 * Operator is required to scan their id
                                 * 01-20-2023
                                 * -JD
                                 */
                                if(!isset($request->txt_edit_prod_runcard_operator)){
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = " Operator/s is required";
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    return $return;
                                }

                                /**
                                 * Additional rules for Process Flow Machine(Matrix) vs Machine(Runcard Station Machine Selected)
                                 * 09-15-2022
                                 * -JD
                                 */
                                $machineCountDetected = 0;
                                $machinesNotIncludedInMatrixArray = []; // -JD
                                $hasOneMachine = false; // -JD
                                $hasDefectEscalation = false; // -JD 11-28-22
                                $machineIsNullAndNAIsSelected = false; // -JD 02-14-23
                                $machinesIDSToCheck = explode(',', $processFlow->machine_id); // From Process Flow(Matrix)
                                // echo 'Process flow machines and count ' . count($machinesIDSToCheck)  . " ";
                                // echo "\r\n";
                                // print_r($machinesIDSToCheck);
                                // echo "\r\n";

                                $machinesIDSToCompare = $request->txt_edit_prod_runcard_station_machine; // Runcard Station Machines
                                // echo 'Runcard station machines and count ' . count($machinesIDSToCompare) . " ";
                                // print_r($machinesIDSToCompare);
                                // echo "\r\n";
                                // return count($machinesIDSToCheck);

                                /**
                                 * To check if process flow is null vs selected machine values(n/a) on prod runcard station
                                 */
                                $machinesIDSToCheckIsNull = false;
                                if($processFlow->machine_id == ''){
                                    $machinesIDSToCheckIsNull = true;
                                }
                                // echo $machinesIDSToCheckIsNull;
                                // echo "\r\n";
                                // echo $machinesIDSToCompare[0];
                                // return;

                                if(count($machinesIDSToCheck) > 0){
                                    if(isset($machinesIDSToCompare)){
                                        /**
                                         * To check if process flow is null vs selected machine values(n/a) on prod runcard station
                                         */
                                        // if($machinesIDSToCheckIsNull = true && $machinesIDSToCompare[0] != "N/A"){
                                        //     $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        //     $return_body = "Please only select N/A if no machine registered based on Matrix.";
                                        //     $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                        //     return $return;
                                        // }
                                        // // else{
                                        // //     echo "id: " .$machinesIDSToCheckIsNull . " machineId: ". $machinesIDSToCompare[0];
                                        // // }
                                        for ($i=0; $i < count($machinesIDSToCompare); $i++) {
                                            if( in_array($machinesIDSToCompare[$i], $machinesIDSToCheck) ){
                                                $machineCountDetected++;
                                                $hasOneMachine = true;
                                            }
                                            elseif($machinesIDSToCheckIsNull && $machinesIDSToCompare[0] == "N/A"){
                                                $machineIsNullAndNAIsSelected = true;
                                            }
                                            elseif(isset($request->runcard_for_defect)){
                                                $hasDefectEscalation = true;
                                            }
                                            else{
                                                $machinesNotIncludedInMatrixArray[] = $machinesIDSToCompare[$i];
                                            }
                                        }

                                        if(!$machineIsNullAndNAIsSelected){
                                            if($hasOneMachine || $hasDefectEscalation){
                                                // echo 'hasOneMachine true';
                                                // echo "\r\n";
                                                if(count($machinesIDSToCompare) <= count($machinesIDSToCheck)){
                                                    // echo "machinesIDSToCompare ". count($machinesIDSToCompare) ." is not more than machinesIDSToCheck " . count($machinesIDSToCheck);
                                                    // echo "\r\n";
                                                    if(count($machinesNotIncludedInMatrixArray) > 0){
                                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                        $return_body = "Some Selected Machine is not registered based on Matrix.";
                                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                        return $return;
                                                    }
                                                }else{
                                                    // echo "machinesIDSToCompare ". count($machinesIDSToCompare) ." is more than machinesIDSToCheck " . count($machinesIDSToCheck);
                                                    // echo "\r\n";
                                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                    $return_body = "Please check the Selected Machine registered based on Matrix.";
                                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                    return $return;
                                                }
                                            }else{
                                                // echo 'hasOneMachine false';
                                                // echo "\r\n";
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = " No Machine/s found on the Matrix.";
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                                return $return;
                                            }
                                            // echo 'machinesNotIncludedInMatrixArray ';
                                            // return $machinesNotIncludedInMatrixArray;
                                        }
                                        // return "hasDefectEscalation: $hasDefectEscalation";


                                    }else{
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = "Please select one or more Machine based on Matrix.";
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                        return $return;
                                    }
                                }
                            } else {
                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                $return_body = ' Process Flow not found.';
                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                return $return;
                            }

                            $processFlowApprovalStatus = ProcessFlow::where('id',$request->process_flow_id)->get();
                            // return $processFlowApprovalStatus;
                            if($processFlowApprovalStatus[0]->approval_status == 1 || $processFlowApprovalStatus[0]->approval_status == 2){ // added by nessa 01/22/2023
                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                $return_body = ' Process Step is not yet approved. <br> Please check the matrix.';
                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                return $return;
                            }

                            // /**
                            //  * Scanning of material is required based on the registered materials in Process Flow(Matrix)
                            //  * 01-26-2023
                            //  * -JD
                            //  */
                            // if(isset($request->materialArray)){
                            //     $materialDetails = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                            //     ->get();
                            //     // return $materialDetails;

                            //     /* Total count of scanned materials should be equal to registered materials in Process Flow(Matrix)*/
                            //     if( count($materialDetails) != count($request->materialArray) ) {
                            //         $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                            //         $return_body = ' Materials is not equal based on the Matrix';
                            //         $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                            //         return $return;
                            //     }
                            // }

                            /**
                             * Count of Scanned Materials vs Process Matrix(Materials) should be equal
                             * 04-04-2023
                             * -JD
                             */
                            if(!isset($request->runcard_defect)){
                                // return 'asdasd';
                                $device = Device::with('process_flow')->where("name", $request->deviceName)->get();
                                // return $device;
                                $deviceProcessFlowMaterialsArray = [];
                                $process_flow_ids = [];
                                $hasWrongMaterialsAdded = 'false';
                                // return $device;
                                if( count($device) > 0 ) {
                                    $process_flows = $device[0]->process_flow;
                                    for ($i=0; $i < count($process_flows); $i++) {
                                        $process_flow_ids[] = $process_flows[$i]->id;
                                    }
                                }
                                // return $processFlow->id;
                                // return $process_flow_ids;

                                $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::whereIn("process_flow_id", $process_flow_ids)->where('is_deleted',0)->where('is_deleted',0)
                                    ->get();
                                /* Get the same Materials based on Process flow(Matrix) and Process flow($request) */
                                for ($i=0; $i < count($deviceProcessFlowMaterials); $i++) {
                                    if($deviceProcessFlowMaterials[$i]->process_flow_id == $processFlow->id){
                                        $deviceProcessFlowMaterialsArray[] = $deviceProcessFlowMaterials[$i]->material_name;
                                    }
                                }
                                // return isset($request->materialArray) ? count($request->materialArray) : 'null';
                                // return $deviceProcessFlowMaterialsArray;

                                if(count($deviceProcessFlowMaterialsArray) > 0){
                                    // return $deviceProcessFlowMaterialsArray;
                                    /* If no materials added then execute below code */
                                    if(!isset($request->materialArray)){
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Please add materials based on the Process Matrix.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    /* If count of Scanned Materials is not equal based on the Process Matrix(Materials) */
                                    if(count($request->materialArray) != count($deviceProcessFlowMaterialsArray)){
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Count of added materials is incorrect based on the Process Matrix.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    /* If one or more Scanned Materials is incorrect based on the Process Matrix(Materials) */
                                    // return $request->materialArray;
                                    // $tempoArray = [];
                                    for ($i=0; $i < count($deviceProcessFlowMaterialsArray); $i++) {
                                        // return $deviceProcessFlowMaterialsArray;
                                        for ($j=0; $j < count($request->materialArray); $j++) {
                                            // if($request->materialArray[$j][1] != $deviceProcessFlowMaterialsArray[$i]){
                                            if(!in_array($request->materialArray[$j][1], $deviceProcessFlowMaterialsArray)){
                                                $hasWrongMaterialsAdded = 'true';
                                            }
                                            // else{
                                            //     $tempoArray[] = $deviceProcessFlowMaterialsArray[$i];
                                            // }
                                        }
                                        // return $tempoArray;
                                    }
                                    // return $hasWrongMaterialsAdded;
                                    if($hasWrongMaterialsAdded == 'true'){
                                        $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                        $return_body = ' Some added materials is incorrect based on the Process Matrix.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }
                                }
                                // return count($deviceProcessFlowMaterialsArray);
                            }


                            /**
                             * Check the process if "Auto Insertion / #MO Installation"
                             * then add validation for Mold, C/T Supplier, Die No & Pair #
                             * -JD
                             */
                            $process = SubStation::where('id',$request->sub_station_id)->get();
                            // return $process[0]->name;
                            // echo $process[0]->name;
                            // echo "\r\n";

                            // if($process[0]->name == "Auto Insertion / #MO Installation"){
                            //updated by nesa 02072023
                            // $runcardRequiredFieldsArray['is_auto_insertion'] = false;
                            //nmodify
                            if($process[0]->name == "Auto Insertion / #MO Installation" || $process[0]->name == "Contact Insertion - Auto Insertion" || $process[0]->name == "Auto Insertion"){

                                $runcardRequiredFieldsArray['is_auto_insertion'] = true;

                                $prodRuncard = ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                                ->get();
                                if($prodRuncard[0]->mold != null || $prodRuncard[0]->mold != ''){
                                    $runcardRequiredFieldsArray['mold'] = $prodRuncard[0]->mold;
                                }

                                if($prodRuncard[0]->ct_supplier != null || $prodRuncard[0]->ct_supplier != ''){
                                    $runcardRequiredFieldsArray['ct_supplier'] = $prodRuncard[0]->ct_supplier;
                                }

                                if($prodRuncard[0]->die_no != null || $prodRuncard[0]->die_no != ''){
                                    $runcardRequiredFieldsArray['die_no'] = $prodRuncard[0]->die_no;
                                }

                                if($prodRuncard[0]->pair_no != null || $prodRuncard[0]->pair_no != ''){
                                    $runcardRequiredFieldsArray['pair_no'] = $prodRuncard[0]->pair_no;
                                }
                                // return $runcardRequiredFieldsArray;
                            }


                            /**
                             * Check the process if "Full Assembly"
                             * then add validation for Workweek, Input, Output in Print / Curing(Process)
                             * -JD
                             * 10-17-2022
                             */
                            if($process[0]->name == "Full Assembly"){
                                $runcardRequiredFieldsArray['is_full_assembly'] = true;

                                $prodRuncard = ProductionRuncard::with('prod_runcard_station_many_details')->where('id',$request->txt_prod_runcard_id_query)
                                ->get();
                                // return $prodRuncard[0]->prod_runcard_station_many_details[0]->qty_output;
                                if($prodRuncard[0]->work_week != null || $prodRuncard[0]->work_week != ''){
                                    $runcardRequiredFieldsArray['work_week'] = $prodRuncard[0]->work_week;
                                }

                                $qty_input = $prodRuncard[0]->prod_runcard_station_many_details[0]->qty_input;
                                $qty_output = $prodRuncard[0]->prod_runcard_station_many_details[0]->qty_output;
                                if($qty_input != 0 || $qty_input != null){
                                    $runcardRequiredFieldsArray['qty_input'] = $qty_input;
                                }

                                if($qty_output != 0 || $qty_output != null){
                                    $runcardRequiredFieldsArray['qty_output'] = $qty_output;
                                }
                                // return $runcardRequiredFieldsArray;
                            }

                            if($runcardRequiredFieldsArray['is_auto_insertion'] == true){
                                // return 'auto_insertion';
                                if($runcardRequiredFieldsArray['mold'] != null && $runcardRequiredFieldsArray['ct_supplier'] != null && $runcardRequiredFieldsArray['die_no'] != null && $runcardRequiredFieldsArray['pair_no'] != null){
                                    // $runcardRequiredFieldsArrayHasNull = false;
                                    DB::beginTransaction();
                                    try {
                                        // return 'Success';
                                        $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                            'production_runcard_id'=> $request->txt_prod_runcard_id_query,
                                            // 'mat_proc_id'=> $material_process[$index]->id,
                                            // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                            // 'sub_station_id'=> $request->sub_station_id,
                                            // 'step_num'=> $step_num,
                                            'process_flow_id'=> $request->process_flow_id,
                                            'qty_input'=>$request->qty_input,
                                            'qty_output'=>$request->qty_output,
                                            'qty_ng'=>$request->qty_ng,
                                            'partial'=>$request->qty_partial,
                                            'runcard_defect'=>$request->runcard_defect,
                                            'remarks'=>$request->remarks,
                                            'magni'=>$request->magni,
                                            'status' => 1,
                                            'created_by'=> Auth::user()->id,
                                            'last_updated_by'=> Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]);

                                        // Save Operators
                                        if(isset($request->txt_edit_prod_runcard_operator)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                ProductionRuncardStationOperator::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                ]);
                                            }
                                        }

                                        // Save Machines
                                        if(isset($request->txt_edit_prod_runcard_station_machine)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                ProductionRuncardStationMachine::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                ]);
                                            }
                                        }

                                        // if has NG only
                                        if($request->qty_ng > 0){
                                            // Required to save MOD if has NG only
                                            if(isset($request->mod)){
                                                for($index = 0; $index < count($request->mod); $index++){
                                                    ProductionRuncardStationMOD::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $prod_runcard_station_id,
                                                        'mod_id' => $request->mod[$index],
                                                        'type_of_ng' => $request->type_of_ng[$index],
                                                        'mod_qty' => $request->mod_qty[$index],
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'update_version' => 1,
                                                        'status' => 1,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                    ]);
                                                }
                                            }
                                            else{
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = ' Please Fill-up MOD!';
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                return $return;
                                            }
                                        }
                                        else{
                                            // Don't save the MOD
                                        }


                                        /**
                                        * Multiple insert for Prod Runcard Station Materials
                                        * 08-16-2022
                                        * -JD
                                        */
                                        if(isset($request->materialArray)){
                                            for($index = 0; $index < count($request->materialArray); $index++){
                                                ProductionRuncardStationMaterial::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'sub_station_id' => $request->sub_station_id,
                                                    'material_id' => $request->materialArray[$index][0],
                                                    'material_name' => $request->materialArray[$index][1],
                                                    'lot_no' => $request->materialArray[$index][2],
                                                    'material_type' => $request->materialArray[$index][3],
                                                    'status' => 1,
                                                    'created_by' => Auth::user()->id,
                                                    'last_updated_by' => Auth::user()->id,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'created_at' => date('Y-m-d H:i:s')
                                                ]);
                                            }
                                        }
                                        DB::commit();
                                        $result = 1;

                                    } catch (Exception $e) {
                                        DB::rollback();
                                        $result = 0;
                                    }
                                }else{
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = ' Please Fill-up required fields(For Auto Insertion)';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'runcardRequiredFieldsArray'=>$runcardRequiredFieldsArray);
                                    return $return;
                                }
                                // return 'here';
                            }
                            else if($runcardRequiredFieldsArray['is_full_assembly'] == true){

                                if($runcardRequiredFieldsArray['work_week'] != null && ($runcardRequiredFieldsArray['qty_input'] != 0 || $runcardRequiredFieldsArray['qty_input'] !=  null) && ($runcardRequiredFieldsArray['qty_output'] != 0 || $runcardRequiredFieldsArray['qty_output'] != null)){
                                    DB::beginTransaction();
                                    try {
                                        // return 'Success';
                                        $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                            'production_runcard_id'=> $request->txt_prod_runcard_id_query,
                                            // 'mat_proc_id'=> $material_process[$index]->id,
                                            // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                            // 'sub_station_id'=> $request->sub_station_id,
                                            // 'step_num'=> $step_num,
                                            'process_flow_id'=> $request->process_flow_id,
                                            'qty_input'=>$request->qty_input,
                                            'qty_output'=>$request->qty_output,
                                            'qty_ng'=>$request->qty_ng,
                                            'partial'=>$request->qty_partial,
                                            'runcard_defect'=>$request->runcard_defect,
                                            'remarks'=>$request->remarks,
                                            'magni'=>$request->magni,
                                            'status' => 1,
                                            'created_by'=> Auth::user()->id,
                                            'last_updated_by'=> Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]);

                                        // Save Operators
                                        if(isset($request->txt_edit_prod_runcard_operator)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                ProductionRuncardStationOperator::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                ]);
                                            }
                                        }

                                        // Save Machines
                                        if(isset($request->txt_edit_prod_runcard_station_machine)){
                                            for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                ProductionRuncardStationMachine::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                ]);
                                            }
                                        }

                                        // if has NG only
                                        if($request->qty_ng > 0){
                                            // Required to save MOD if has NG only
                                            if(isset($request->mod)){
                                                for($index = 0; $index < count($request->mod); $index++){
                                                    ProductionRuncardStationMOD::insert([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $prod_runcard_station_id,
                                                        'mod_id' => $request->mod[$index],
                                                        'type_of_ng' => $request->type_of_ng[$index],
                                                        'mod_qty' => $request->mod_qty[$index],
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'update_version' => 1,
                                                        'status' => 1,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),
                                                    ]);
                                                }
                                            }
                                            else{
                                                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                $return_body = ' Please Fill-up MOD!';
                                                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                                return $return;
                                            }
                                        }
                                        else{
                                            // Don't save the MOD
                                        }


                                        /**
                                        * Multiple insert for Prod Runcard Station Materials
                                        * 08-16-2022
                                        * -JD
                                        */
                                        if(isset($request->materialArray)){
                                            for($index = 0; $index < count($request->materialArray); $index++){
                                                ProductionRuncardStationMaterial::insert([
                                                    'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    'production_runcard_station_id' => $prod_runcard_station_id,
                                                    'sub_station_id' => $request->sub_station_id,
                                                    'material_id' => $request->materialArray[$index][0],
                                                    'material_name' => $request->materialArray[$index][1],
                                                    'lot_no' => $request->materialArray[$index][2],
                                                    'material_type' => $request->materialArray[$index][3],
                                                    'status' => 1,
                                                    'created_by' => Auth::user()->id,
                                                    'last_updated_by' => Auth::user()->id,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'created_at' => date('Y-m-d H:i:s')
                                                ]);
                                            }
                                        }
                                        DB::commit();
                                        $result = 1;
                                    } catch (Exception $e) {
                                        DB::rollback();
                                        $result = 0;
                                    }
                                }else{
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = ' Please Fill-up required fields(For Full Assembly)';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'runcardRe uiredFieldsArray'=>$runcardRequiredFieldsArray);
                                    return $return;
                                }
                            }
                            else{
                                // return 'here';
                                DB::beginTransaction();
                                try {
                                    // return 'Success';
                                    $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                        'production_runcard_id'=> $request->txt_prod_runcard_id_query,
                                        // 'mat_proc_id'=> $material_process[$index]->id,
                                        // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                        // 'sub_station_id'=> $request->sub_station_id,
                                        // 'step_num'=> $step_num,
                                        'process_flow_id'=> $request->process_flow_id,
                                        'qty_input'=>$request->qty_input,
                                        'qty_output'=>$request->qty_output,
                                        'qty_ng'=>$request->qty_ng,
                                        'partial'=>$request->qty_partial,
                                        'runcard_defect'=>$request->runcard_defect,
                                        'remarks'=>$request->remarks,
                                        'magni'=>$request->magni,
                                        'status' => 1,
                                        'created_by'=> Auth::user()->id,
                                        'last_updated_by'=> Auth::user()->id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ]);

                                    // Save Operators
                                    if(isset($request->txt_edit_prod_runcard_operator)){
                                        for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                            ProductionRuncardStationOperator::insert([
                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                            ]);
                                        }
                                    }

                                    // Save Machines
                                    if(isset($request->txt_edit_prod_runcard_station_machine)){
                                        for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                            ProductionRuncardStationMachine::insert([
                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                            ]);
                                        }
                                    }

                                    // $existingDefect = DefectEscalationV2Rev::orderBy('id', 'desc')->first();

                                    // return 'asd';

                                    $ts = "TSF1";
                                    $lineWithDefect = $request->line_with_defect;
                                    $de_occurence = 1;

                                    $time = Carbon::now();
                                    $todayStartA = $time->copy()->setTime(7, 30); // 7:30 AM
                                    $todayEndA   = $time->copy()->setTime(19, 29); // 7:29 PM

                                    $isMorning = $time->between($todayStartA, $todayEndA);

                                    $shift = $isMorning ? 1 : 2;
                                    $defect_classification = 0;
                                    $defect_text = '';
                                    $defectCtrlNumber = '';

                                    // return $request->mod_classification;
                                    $mod = $request->mode_of_defect;

                                    // return $mod;

                                    $classifications = $request->mod_classification;

                                    // return $classifications;


                                    if($request->qty_ng > 0){
                                        if(isset($mod)){
                                            for ($index=0; $index < count($mod) ; $index++) {

                                                $current_class = $request->mod_classification[$index];

                                                if(str_contains($current_class,'2')){
                                                    $defect_text = 'A2';
                                                }else if(str_contains($current_class,'3')){
                                                    $defect_text = 'A3';
                                                }else if(str_contains($current_class, '4')){
                                                    $defect_text = 'A4';
                                                }

                                                // return $defect_text;

                                                // 3. Generate UNIQUE control number for THIS iteration
                                                // Fetch the last number from DB here if you want to increment (e.g., 0001, 0002)
                                                // For now, using your logic:
                                                $defectCtrltxt = $ts . '-' . $lineWithDefect . '-' . $defect_text . '-' . date('m') . date('y');


                                                // return $defectCtrltxt;

                                                // Logic to increment $defectCtrlNumber (example: finding the next sequence)
                                                // $lastRecord = DefectEscalationV2Rev::where('defect_ctrl_txt', $defectCtrltxt)->latest()->first();
                                                $lastRecord = DefectEscalationV2Rev::orderBy('id', 'desc')->first();

                                                // return 'asd';
                                                // return $request->mod_classification; // MALAG

                                                $mod_classification = $request->mod_classification;

                                                // 1. Flatten the data: Convert ["1,2", "2"] into [1, 2, 2]
                                                $flattened = [];
                                                foreach ($mod_classification as $item) {
                                                    // split by comma and merge into the flattened array
                                                    $flattened = array_merge($flattened, explode(',', $item));
                                                }
                                                // 2. Remove duplicates (optional, but cleaner)
                                                $unique_classes = array_unique($flattened);

                                                // 3. Perform your Validation
                                                // if (in_array('1', $unique_classes) && count($unique_classes) > 1) {
                                                //     $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                //     $return_body = ' CRITICAL DEFECT cannot be combined with other Defect Classifications!';
                                                //     return array('result' => 3, 'title' => $return_title, 'body' => $return_body);
                                                // }
                                                // return $lastRecord;

                                                // return $lastRecord;
                                                if($lastRecord == null || ''){
                                                    // return 'asd';
                                                    $defectCtrlNumber = '0001';
                                                }else{
                                                    // return 'qwe';
                                                    $nextSequence = $lastRecord ? (intval($lastRecord->defect_ctrl_number) + 1) : 1;
                                                    $defectCtrlNumber = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
                                                }

                                                // return $defectCtrlNumber;

                                                    DefectEscalationV2Rev::insert([
                                                        'po_number'                    => $request->po_number_with_defect,
                                                        'defect_ctrl_txt'              => $defectCtrltxt,       // Now unique per loop
                                                        'defect_ctrl_number'           => $defectCtrlNumber,    // Now unique per loop
                                                        'prod_runcard_id'              => $request->txt_prod_runcard_id_query,
                                                        'prod_runcard_station_id'      => $prod_runcard_station_id,
                                                        // 'prod_runcard_station_mod_id'  => $request->mod[$index],
                                                        'defect_classification'        => $current_class,
                                                        'defect_qty'                   => $request->mod_qty[$index],
                                                        'mod_type'                     => $request->type_of_ng[$index],
                                                        'mod_id'                       => $mod[$index],
                                                        'de_occurence'                 => $de_occurence,
                                                        'de_group'                     => 1,
                                                        'defect_status'                => 0,
                                                        'de_user'                      => $user[0]->id,
                                                        'de_position'                  => $user[0]->position,
                                                        'created_by'                   => Auth::user()->id,
                                                        'created_at'                   => now(),
                                                        'de_shift'                     => $shift,
                                                        'de_date'                      => date('Y-m-d'),
                                                        'de_time'                      => date('h:i:s A'),
                                                        'line'                         => $lineWithDefect,
                                                        'line_id'                      => $request->line_with_defect_id,
                                                    ]);
                                            }
                                        }
                                    } // BINI MLAG


                                    if($request->qty_ng > 0){
                                        // Required to save MOD if has NG only
                                        if(isset($request->mode_of_defect)){
                                            // return count($request->mod);
                                            for($index = 0; $index < count($request->mode_of_defect); $index++){
                                                $explode = explode(',',$request->mode_of_defect[$index]);
                                                    for($j = 0; $j < count($explode); $j++){
                                                    //   return  $explode;
                                                    //   return $index;

                                                    //  $stationMod = DB::table('prod_runcard_station_mods')->insertGetId([
                                                    //     'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    //     'production_runcard_station_id' => $prod_runcard_station_id,
                                                    //     'mod_id' => $request->mod[$i],
                                                    //     'type_of_ng' => $request->type_of_ng[$index],
                                                    //     'mod_qty' => $request->mod_qty[$index],
                                                    //     'mod_index' => $index,
                                                    //     'created_by' => Auth::user()->id,
                                                    //     'last_updated_by' => Auth::user()->id,
                                                    //     'update_version' => 1,
                                                    //     'status' => 1,
                                                    //     'created_at' => now(),
                                                    //     'updated_at' => now()
                                                    // ]);

                                                    // return [
                                                    //     'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                    //     'production_runcard_station_id' => $prod_runcard_station_id,
                                                    //     'mod_id' => $explode[$j],
                                                    //     'type_of_ng' => $request->type_of_ng[$j],
                                                    //     'mod_qty' => $request->mod_qty[$j],
                                                    //     'mod_index' => $index,
                                                    //     'created_by' => Auth::user()->id,
                                                    //     'last_updated_by' => Auth::user()->id,
                                                    //     'update_version' => 1,
                                                    //     'status' => 1,
                                                    //     'created_at' => now(),
                                                    //     'updated_at' => now()
                                                    // ];

                                                    $stationMod = DB::table('prod_runcard_station_mods')->insertGetId([
                                                        'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                        'production_runcard_station_id' => $prod_runcard_station_id,
                                                        'mod_id' => $explode[$j],
                                                        'type_of_ng' => $request->type_of_ng[$j],
                                                        'mod_qty' => $request->mod_qty[$j],
                                                        'mod_index' => $index,
                                                        'created_by' => Auth::user()->id,
                                                        'last_updated_by' => Auth::user()->id,
                                                        'update_version' => 1,
                                                        'status' => 1,
                                                        'created_at' => now(),
                                                        'updated_at' => now()
                                                    ]);
                                                }

                                            }


                                        }
                                        else{
                                            $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                            $return_body = ' Please Fill-up MOD!';
                                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                            return $return;
                                        }
                                    }
                                    else{
                                        // Don't save the MOD
                                    }


                                    /**
                                    * Multiple insert for Prod Runcard Station Materials
                                    * 08-16-2022
                                    * -JD
                                    */
                                    if(isset($request->materialArray)){
                                        for($index = 0; $index < count($request->materialArray); $index++){
                                            ProductionRuncardStationMaterial::insert([
                                                'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                'sub_station_id' => $request->sub_station_id,
                                                'material_id' => $request->materialArray[$index][0],
                                                'material_name' => $request->materialArray[$index][1],
                                                'lot_no' => $request->materialArray[$index][2],
                                                'material_type' => $request->materialArray[$index][3],
                                                'status' => 1,
                                                'created_by' => Auth::user()->id,
                                                'last_updated_by' => Auth::user()->id,
                                                'updated_at' => date('Y-m-d H:i:s'),
                                                'created_at' => date('Y-m-d H:i:s')
                                            ]);
                                        }
                                    }
                                    DB::commit();
                                    $result = 1;
                                } catch (Exception $e) {
                                    DB::rollback();
                                    $result = 0;
                                }
                            }

                            if( $result == 0 ){
                                $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                                $return_body = 'An error occured. The record not saved.';
                            }

                            $prodRuncard = ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)->get();

                            /* Maint Return */
                            $return = array('result' => $result, 'title'=>$return_title,'body'=>$return_body, 'runcardRequiredFieldsArray'=>$runcardRequiredFieldsArray, 'prodRuncard' => $prodRuncard);
                            return $return;
                        }
                        else{
                            $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                            $return_body = ' Scanned QR Code is not Operator.';
                            $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                            return $return;
                        }
                    }
                    else{
                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                        $return_body = ' Invalid Employee No.';
                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                        return $return;
                        // return '0';
                    }
                }

            }
        }

        // For Insert of Station
        else{ // For Multiple Runcard
            $validator = Validator::make($data, [
                // 'txt_prod_runcard_id_query' => ['required'],
                'selected_prod_runcard' => ['required'],
                'qty_input' => ['required'],
                'qty_output' => ['required'],
                'qty_ng' => ['required'],
                'txt_employee_number_scanner' => ['required'],
                // 'step_num' => ['required'],
                'sub_station_id' => ['required'],
                // 'lot_no' => ['required'], // 08-16-2022 -JD
                // 'material_id' => ['required'], // 08-11-2022 -JD
                // 'material_type' => ['required'], // 08-16-2022 -JD
                'process_flow_id' => ['required'], // 08-16-2022 -JD
            ]);

            if ($validator->fails()) {
                $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                $return_body = 'Please complete required fields.';
                $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
                return $return;
            }
            else{

                $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

                if($user->count() > 0){
                    if($user[0]->position == 4){
                        // if( $request->txt_prod_runcard_station_id_query ){

                                $processFlow = ProcessFlow::find($request->process_flow_id);
                                if( isset($processFlow->id) ){

                                    if( $processFlow->id != (int)$request->sub_station_id ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Invalid Process.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body, "processFlow" => $processFlow, "request" => $request->all());
                                        return $return;
                                    }

                                    // $operatorCountDetected = 0;
                                    // $operatorCountDetectedNotSet = 0;
                                    // $operatorsIDSToCheck = [];
                                    // if( $isMorning )
                                    //     $operatorsIDSToCheck = explode(',', $processFlow->a_shift_user_id);
                                    // else
                                    //     $operatorsIDSToCheck = explode(',', $processFlow->b_shift_user_id);

                                    // $operatorsIDSToCompare = $request->txt_edit_prod_runcard_operator;

                                    // for ($i=0; $i < count($operatorsIDSToCompare); $i++) {
                                    //     if( in_array($operatorsIDSToCompare[$i], $operatorsIDSToCheck) )
                                    //         $operatorCountDetected++;
                                    //     else
                                    //         $operatorCountDetectedNotSet++;
                                    // }

                                    // if( ($operatorCountDetected != count($operatorsIDSToCheck) || count($operatorsIDSToCheck) != count($operatorsIDSToCompare)) || !($operatorCountDetected > 0 && $operatorCountDetectedNotSet == 0) ) {
                                    //     $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                    //     $return_body = " Operator/s is not certified.";
                                    //     $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    //     return $return;
                                    // }

                                    $machineCountDetected = 0;
                                    $machinesIDSToCheck = explode(',', $processFlow->machine_id);

                                    $machinesIDSToCompare = $request->txt_edit_prod_runcard_station_machine;

                                    for ($i=0; $i < count($machinesIDSToCompare); $i++) {
                                        if( in_array($machinesIDSToCompare[$i], $machinesIDSToCheck) )
                                            $machineCountDetected++;
                                    }

                                    if( $machineCountDetected != count($machinesIDSToCheck) || count($machinesIDSToCheck) != count($machinesIDSToCompare) ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = " Invalid Selected Machine's.";
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    // if( $processFlow->material_name != $request->material_id ) {
                                    //     $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                    //     $return_body = ' Invalid Material.';
                                    //     $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    //     return $return;
                                    // }

                                    // if( $processFlow->lot_no != $request->lot_no ) {
                                    //     $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
                                    //     $return_body = ' Invalid Lot No. 3.';
                                    //     $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    //     return $return;
                                    // }

                                    $material = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                                        ->where("material_name", $request->material_id)
                                        ->get();
                                    if( count($material) == 0 ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Invalid Material.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    $material = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                                        ->where("lot_no", $request->lot_no)
                                        ->get();
                                    if( count($material) == 0 ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Invalid Lot No. 1.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                    $material = DeviceProcessFlowMaterial::where("process_flow_id", $processFlow->id)
                                        ->where("material_name", $request->material_id)
                                        ->where("lot_no", $request->lot_no)
                                        ->get();
                                    if( count($material) == 0 ) {
                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                        $return_body = ' Invalid Material Name and Lot No. is not match.';
                                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                        return $return;
                                    }

                                } else {
                                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                    $return_body = ' Process Flow not found.';
                                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                                    return $return;
                                }

                            // if($validator->passes()){
                                DB::beginTransaction();

                                $selected_prod_runcard = explode(',', $request->selected_prod_runcard);
                                // return count($selected_prod_runcard);
                                if(count($selected_prod_runcard) > 0){
                                    // return $selected_prod_runcard;
                                    $counter = 0;
                                    try {
                                        ProductionRuncard::whereIn('id', $selected_prod_runcard)
                                            ->update(
                                                [
                                                    'status'=> 3,
                                                    'last_updated_by' => $user[0]->id,
                                                    'updated_at' => date('Y-m-d H:i:s')
                                                ]
                                            );


                                        // return $selected_prod_runcard;
                                        for ($outer_index = 0; $outer_index < count($selected_prod_runcard); $outer_index++) {
                                            $counter++;

                                                $last_prod_runcard_station = ProductionRuncardStation::where('production_runcard_id', $selected_prod_runcard[$outer_index])->orderBy('id', 'desc')->first();

                                                $step_num = 1;

                                                if($last_prod_runcard_station != null){
                                                    $step_num = $last_prod_runcard_station->step_num + 1;
                                                }


                                                $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                                    'production_runcard_id'=> $selected_prod_runcard[$outer_index],
                                                    // 'mat_proc_id'=> $material_process[$index]->id,
                                                    // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                                    // 'sub_station_id'=> $request->sub_station_id,
                                                    // 'step_num'=> $step_num,
                                                    'process_flow_id'=> $request->process_flow_id,
                                                    'qty_input'=>$request->qty_input,
                                                    'qty_output'=>$request->qty_output,
                                                    'qty_ng'=>$request->qty_ng,
                                                    'status' => 1,
                                                    'created_by'=> Auth::user()->id,
                                                    'last_updated_by'=> Auth::user()->id,
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                ]);

                                                // Save Operators
                                                if(isset($request->txt_edit_prod_runcard_operator)){
                                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_operator); $index++){
                                                        ProductionRuncardStationOperator::insert([
                                                            'production_runcard_id' => $selected_prod_runcard[$outer_index],
                                                            'production_runcard_station_id' => $prod_runcard_station_id,
                                                            'operator_id' => $request->txt_edit_prod_runcard_operator[$index],
                                                        ]);
                                                    }
                                                }

                                                // Save Machines
                                                if(isset($request->txt_edit_prod_runcard_station_machine)){
                                                    for($index = 0; $index < count($request->txt_edit_prod_runcard_station_machine); $index++){
                                                        ProductionRuncardStationMachine::insert([
                                                            'production_runcard_id' => $selected_prod_runcard[$outer_index],
                                                            'production_runcard_station_id' => $prod_runcard_station_id,
                                                            'machine_id' => $request->txt_edit_prod_runcard_station_machine[$index],
                                                        ]);
                                                    }
                                                }

                                                // if has NG only
                                                if($request->qty_ng > 0){
                                                    // Required to save MOD if has NG only
                                                    if(isset($request->mod)){
                                                        for($index = 0; $index < count($request->mod); $index++){
                                                            ProductionRuncardStationMOD::insert([
                                                                'production_runcard_id' => $selected_prod_runcard[$outer_index],
                                                                'production_runcard_station_id' => $prod_runcard_station_id,
                                                                'mod_id' => $request->mod[$index],
                                                                'type_of_ng' => $request->type_of_ng[$index],
                                                                'mod_qty' => $request->mod_qty[$index],
                                                                'created_by' => Auth::user()->id,
                                                                'last_updated_by' => Auth::user()->id,
                                                                'update_version' => 1,
                                                                'status' => 1,
                                                                'created_at' => date('Y-m-d H:i:s'),
                                                                'updated_at' => date('Y-m-d H:i:s'),
                                                            ]);
                                                        }
                                                    }
                                                    else{
                                                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                                                        $return_body = ' Please Fill-up MOD!';
                                                        $return = array('result' => '0', 'title'=>$return_title,'body'=>$return_body);
                                                        return $return;
                                                    }
                                                }
                                                else{
                                                    // Don't save the MOD
                                                }


                                                /**
                                                 * Multiple insert for Prod Runcard Station Materials(INSERT)
                                                 * 08-16-2022
                                                 * -JD
                                                 */
                                                if(isset($request->materialArray)){
                                                    for($index = 0; $index < count($request->materialArray); $index++){
                                                        ProductionRuncardStationMaterial::insert([
                                                            'production_runcard_id' => $request->txt_prod_runcard_id_query,
                                                            'production_runcard_station_id' => $request->txt_prod_runcard_station_id_query,
                                                            'sub_station_id' => $request->process,
                                                            'material_id' => $request->materialArray[$index][0],
                                                            'material_name' => $request->materialArray[$index][1],
                                                            'lot_no' => $request->materialArray[$index][2],
                                                            'material_type' => $request->materialArray[$index][3],
                                                            'status' => 1,
                                                            'created_by' => Auth::user()->id,
                                                            'last_updated_by' => Auth::user()->id,
                                                            'updated_at' => date('Y-m-d H:i:s'),
                                                            'created_at' => date('Y-m-d H:i:s')
                                                        ]);
                                                    }
                                                }
                                        }

                                        // return $counter;
                                        DB::commit();
                                        // ProductionRuncard::whereIn('id', $selected_prod_runcard)
                                        //     ->update(
                                        //         [
                                        //             'status'=> 3,
                                        //             'last_updated_by' => $user[0]->id,
                                        //             'updated_at' => date('Y-m-d H:i:s')
                                        //         ]
                                        //     );
                                        $result = 1;
                                    } catch (Exception $e) {
                                        DB::rollback();
                                        $result = 0;
                                    }

                                }
                                else{
                                    DB::rollback();
                                    $result = 0;
                                }
                        // }

                        if( $result == 0 ){
                            $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                            $return_body = 'An error occured. The record not saved.';
                        }
                        $return = array('result' => $result, 'title'=>$return_title,'body'=>$return_body);
                        return $return;
                    }
                    else{
                        $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                        $return_body = ' Scanned QR Code is not Operator.';
                        $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                        return $return;
                    }
                }
                else{
                    $return_title = '<i class="fa fa-exclamation-triangle text-danger"></i> Not saved';
                    $return_body = ' Invalid Employee No.';
                    $return = array('result' => 0, 'title'=>$return_title,'body'=>$return_body);
                    return $return;
                    // return '0';
                }

            }
        }

    }

    public function delete_station(Request $request){
        if(isset($request->prod_runcard_station_id)){
            ProductionRuncardStationOperator::where('production_runcard_station_id', $request->prod_runcard_station_id)
                ->delete();
            ProductionRuncardStationMachine::where('production_runcard_station_id', $request->prod_runcard_station_id)
                ->delete();
            ProductionRuncardStationMOD::where('production_runcard_station_id', $request->prod_runcard_station_id)
                ->delete();
            ProductionRuncardStationMaterial::where('production_runcard_station_id', $request->prod_runcard_station_id)
                ->delete();
            ProductionRuncardStation::where('id', $request->prod_runcard_station_id)
                ->delete();
            return response()->json(['result' => 1, 'error' => null]);
        }
        return response()->json(['result' => 0]); // Invalid Operator
    }

    // USED IN MANUAL PRODUCTION RUNCARD
    public function save_prod_runcard_details_with_defect(Request $request){ // FOR MANUAL AND AUTOMATIC RUNCARD
        date_default_timezone_set('Asia/Manila');
        // return 'working funtion ';
        // return $request->judgement;
        if($request->ajax()){
            if(Auth::check()){
                  $device = CommonController::getInstance()->getMatrixByPoNo(['po_no' => $request->txt_po_number, 'name' => $request->device_name, 'barcode' => '', 'get_or_first' => '']);
                  // $device = Device::where("name", $request->device_name)->get();
                  $PFlow = ProcessFlow::where("device_id", $device[0]->id)->get();

                // Update Runcard
                if(isset($request->txt_prod_runcard_id_query)){
                    // return 'if';
                    $data = $request->all();

                    // return $data;

                    $rules = [
                        'txt_employee_number_scanner' => 'required',
                        'txt_po_number' => 'required',
                        'txt_lot_qty' => 'required',
                        // 'setup_qualification' => 'required',
                        // 'txt_pair_no' =>  'required',
                        'txt_kit_number' =>  'required',
                        // 'txt_defect_rework_no' =>  'required',

                    ];

                    // return $request->judgement;

                    /**
                     * Condition for radio buttons
                     * -JD
                     */
                    if($request->judgement == 3 || $request->judgement == null ){
                        // return '1';
                        $rules['input'] = '';
                        $rules['output'] = '';
                        $rules['qty_ng'] = '';
                        $rules['disposition'] = '';
                        if($request->qty_ng > 0){
                            $rules['mod'] = '';
                        }

                        $rules['qc_id'] = '';
                        $rules['judgement'] = '';
                        $rules['mpc_operator_id'] = ''; // JD 05-08-2023
                    }
                    else{
                        // return '2';
                        $rules['input'] = 'required';
                        $rules['output'] = 'required';
                        $rules['qty_ng'] = 'required';
                        // $rules['disposition'] = 'required';
                        $rules['disposition'] = ''; // Old -JD
                         if($request->qty_ng > 0){
                            $rules['mod'] = '';
                        }
                        // $rules['qc_id'] = 'required'; // Old -JD
                        $rules['qc_id'] = '';
                        $rules['judgement'] = 'required';
                        $rules['mpc_operator_id'] = 'required'; // JD 05-08-2023
                    }

                    $messages = [
                        'txt_employee_number_scanner.required' => 'Employee No. field is required.',
                        'txt_po_number.required' => 'PO No. field is required.',
                        'txt_lot_qty.required' => 'Lot Quantity field is required.',
                        // 'setup_qualification.required' => 'Setup Qualification field is required.',
                        // 'txt_pair_no.required' => 'Pair No. field is required.',
                        'txt_kit_number.required' => 'Kit No. field is required.',
                        'txt_defect_rework_no.required' => 'Defect/Rework No. field is required.',

                    ];

                    if(isset($request->judgement)){
                        $messages['input.required'] = 'Input is required';
                        $messages['output.required'] = 'Output is required';
                        $messages['qty_ng.required'] = 'NG is required';
                        $messages['disposition.required'] = 'Disposition is required';
                        $messages['mod.required'] = 'Mode of Defect is required';
                        // $messages['prod_quali_remarks.required'] = 'Prod Quali Remarks is required';
                        $messages['qc_id.required'] = 'QC is required';
                        $messages['judgement.required'] = 'Judgement is required';
                        $messages['assembly_line.required'] = 'Assembly Line is required';
                    }

                    /**
                     * 12-12-2022
                     */
                    if($request->judgement == 1 || $request->judgement == 2){
                        $rules['operator_id'] = 'required';
                        $rules['mpc_operator_id'] = 'required';
                    }else{
                        $rules['operator_id'] = '';
                        $rules['mpc_operator_id'] = '';
                    }

                    $validator = Validator::make($data, $rules, $messages);

                    DB::beginTransaction();


                    try {
                        if($validator->passes()){

                            $runcard_no = substr($request->txt_po_number, 0, -5);

                            $user_info = User::where('employee_id', $request->txt_employee_number_scanner)
                                ->where('status', 1)
                                ->first();

                            if($user_info != null){

                                $setup_qualification = 0;
                                $eng_qualification_id = null;
                                $setup_qualified = 0;
                                $qc_qualification = 0;
                                $qc_stamp_id = null;
                                $qc_qualified = 0;


                                if(isset($request->setup_qualification)){
                                    $setup_qualification = $request->setup_qualification;
                                }
                                if(isset($request->eng_qualification_id)){
                                    $eng_qualification_id = $request->eng_qualification_id;
                                }
                                // if(isset($request->setup_qualified)){
                                //     $setup_qualified = $request->setup_qualified;
                                // }
                                if(isset($request->qc_qualification)){
                                    $qc_qualification = $request->qc_qualification;
                                }
                                if(isset($request->qc_stamp_id)){
                                    $qc_stamp_id = $request->qc_stamp_id;
                                }
                                if(isset($request->qc_qualified)){
                                    $qc_qualified = $request->qc_qualified;
                                }

                                ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                                ->update(
                                    [
                                        // 'lot_qty' => $request->txt_lot_qty,
                                        // 'assessment_no'=> $request->txt_assessment_no,

                                        'setup_qualification' => $setup_qualification,
                                        'eng_qualification_id' => $eng_qualification_id,
                                        // 'setup_qualified' => $setup_qualified,
                                        'qc_qualification' => $qc_qualification,
                                        'qc_stamp_id' => $qc_stamp_id,
                                        'qc_qualified' => $qc_qualified,
                                        'qc_remarks' => $request->qc_remarks,

                                        'setup_qualification'=> $request->setup_qualification,
                                        'mold'=> $request->txt_mold,
                                        'ct_supplier'=> $request->txt_ct_supplier,
                                        'die_no'=> $request->txt_die_no,
                                        'pair_no'=> $request->txt_pair_no,
                                        'remarks'=> $request->txt_remarks,
                                        'other_docs_no'=> $request->txt_other_docs_no,
                                        'other_docs_rev'=> $request->txt_other_docs_rev,
                                        'last_updated_by' => $user_info->id,
                                        'updated_at' => date('Y-m-d H:i:s'),

                                        /**
                                         * Additionals
                                         * -JD
                                        */
                                        'qty_input'=> $request->input,
                                        'qty_output'=> $request->output,
                                        'operator_id'=> $request->operator_id, // 12-12-2022
                                        'mpc_operator_id'=> $request->mpc_operator_id, // 05-07-2023
                                        'qty_ng'=> $request->qty_ng,
                                        'disposition'=> $request->disposition,
                                        'mod'=> $request->mod,
                                        'prod_quali_remarks'=> $request->prod_quali_remarks,
                                        'qc_id'=> $request->qc_id,
                                        'judgement'=> $request->judgement,
                                        'work_week'=> $request->txt_work_week,
                                        'kit_number'=> $request->txt_kit_number,
                                        // 'defect_rework_number'=> $request->txt_defect_rework_no,
                                        'assembly_line_id'=> $request->assembly_line,
                                    ]
                                );

                                DB::commit();
                                return response()->json(['auth' => 1, 'result' => 1, 'error' => null, 'prod_runcard_id' => $request->txt_prod_runcard_id_query]); // Success
                            }
                            else{
                                return response()->json(['auth' => 1, 'result' => 2, 'error' => null]); // Invalid Operator
                            }
                        }
                        else{
                            return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                        }
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                    }
                }
                // Add Runcard
                else{
                    // return 'elsesss';
                    $data = $request->all();
                    // return $request->judgement;

                    $rules = [
                        'txt_employee_number_scanner' => 'required',
                        'txt_po_number' => 'required',
                        'txt_lot_qty' => 'required',
                        // 'setup_qualification' => 'required',
                        // 'txt_pair_no' =>  'required',
                        'txt_kit_number' =>  'required',
                        // 'txt_defect_rework_no' =>  'required',

                    ];

                    /**
                     * Condition for radio buttons
                     */
                    if($request->judgement == 3 || $request->judgement == null){
                        $rules['input'] = '';
                        $rules['output'] = '';
                        $rules['qty_ng'] = '';
                        $rules['disposition'] = '';
                        if($request->qty_ng > 0){
                            $rules['mod'] = '';
                        }
                        $rules['qc_id'] = '';
                        $rules['judgement'] = '';
                    }else{
                        $rules['input'] = 'required';
                        $rules['output'] = 'required';
                        $rules['qty_ng'] = 'required';
                        // $rules['disposition'] = 'required'; // Old -JD
                        $rules['disposition'] = '';
                        if($request->qty_ng > 0){
                            $rules['mod'] = '';
                        }
                        // $rules['qc_id'] = 'required'; // Old -JD
                        $rules['qc_id'] = '';
                        $rules['judgement'] = 'required';
                    }


                    $setup_qualification = 0;
                    $eng_qualification_id = null;
                    // $setup_qualified = 0;
                    $qc_qualification = 0;
                    $qc_stamp_id = null;
                    $qc_qualified = 0;


                    if(isset($request->setup_qualification)){
                        $setup_qualification = $request->setup_qualification;
                    }
                    if(isset($request->eng_qualification_id)){
                        $eng_qualification_id = $request->eng_qualification_id;
                    }
                    // if(isset($request->setup_qualified)){
                    //     $setup_qualified = $request->setup_qualified;
                    // }
                    if(isset($request->qc_qualification)){
                        $qc_qualification = $request->qc_qualification;
                    }
                    if(isset($request->qc_stamp_id)){
                        $qc_stamp_id = $request->qc_stamp_id;
                    }
                    if(isset($request->qc_qualified)){
                        $qc_qualified = $request->qc_qualified;
                    }

                    $messages = [
                        'txt_employee_number_scanner.required' => 'Employee No. field is required.',
                        'txt_po_number.required' => 'PO No. field is required.',
                        'txt_lot_qty.required' => 'Lot Quantity field is required.',
                        'setup_qualification.required' => 'Setup Qualification field is required.',
                        // 'setup_qualification.required' => 'Setup Qualification field is required.',
                        // 'txt_pair_no.required' => 'Pair No. field is required.',
                    ];

                    // return $request->judgement;

                    if(isset($request->judgement)){
                        $messages['input.required'] = 'Input is required';
                        $messages['output.required'] = 'Output is required';
                        $messages['qty_ng.required'] = 'NG is required';
                        // $messages['disposition.required'] = 'Disposition is required';
                        $messages['mod.required'] = 'Mode of Defect is required';
                        $messages['qc_id.required'] = 'QC is required';
                        $messages['judgement.required'] = 'Judgement is required';
                        $messages['assembly_line.required'] = 'Assembly Line is required';
                    }

                    /**
                     * 12-12-2022
                     */
                    if($request->judgement == 1 || $request->judgement == 2){
                        $rules['operator_id'] = 'required';
                        $rules['mpc_operator_id'] = 'required';
                    }else{
                        $rules['operator_id'] = '';
                        $rules['mpc_operator_id'] = '';
                    }

                    $validator = Validator::make($data, $rules, $messages);

                    DB::beginTransaction();
                    try {
                        if($validator->passes()){
                            // return 'passes';

                            $user_info = User::where('employee_id', $request->txt_employee_number_scanner)
                                ->where('status', 1)
                                ->first();

                                // return $user_info;

                            if($user_info != null){
                                /* 03112025 by Nessa */
                                $es_po = substr($request->txt_po_number, 0, 2);
                                if ($es_po == 'ES'){
                                    // $runcard_no = substr($request->txt_po_number, 0, -4);
                                    $runcard_no = str_replace('-', '', $request->txt_po_number); //07-09-26 Sample Output: ES26095
                                }else{
                                    $runcard_no = substr($request->txt_po_number, 0, -5);
                                }
                                $prod_runcard_id = "";

                                $arr_packing_months = [1,2,3,4,5,6,7,8,9,'X','Y','Z'];
                                $final_reel_lot_no = "";

                                $last_no_of_the_year = date('Y')[3];
                                $packing_month = $arr_packing_months[(integer)date('m') - 1];
                                $packing_date = date('d');
                                $serial_no = str_pad(1,2,"0", STR_PAD_LEFT);
                                $lot_no_machine_code = '0';

                                $device_info = CommonController::getInstance()->getMatrixByPoNo(['po_no' => $request->txt_po_number, 'name' => $request->device_name, 'barcode' => '', 'get_or_first' => 'first']);
                                // $device_info = Device::where('name', $request->device_name)->where('status', 1)->first();

                                // return $device_info;

                                if($device_info != null){
                                    if($device_info->lot_no_machine_code != null){
                                        $lot_no_machine_code = $device_info->lot_no_machine_code;
                                    }
                                }

                                // Generate Runcard No
                                $prod_runcards = ProductionRuncard::where('runcard_no', 'LIKE', $runcard_no . "%")->orderBy('id', 'desc')->limit(1)->get();

                                // return $prod_runcards;

                                $final_runcard_no = "";
                                $explode_runcard_no = "";

                                if($prod_runcards->count() > 0){
                                    // return 'if';
                                    /* 03112025 by Nessa */
                                    $es_po = substr($prod_runcards[0]->runcard_no, 0, 2);

                                    $explode_runcard_no = explode('-', $prod_runcards[0]->runcard_no);
                                    // return $explode_runcard_no;

                                    $explode_runcard_no[1] = str_pad($explode_runcard_no[1] + 1,3,"0", STR_PAD_LEFT);

                                    $final_runcard_no = implode('-', $explode_runcard_no);

                                    // return $final_runcard_no;
                                }
                                else{
                                    // return 'eles';
                                    /* 03112025 by Nessa */
                                    $es_po = substr($request->txt_po_number, 0, 2);
                                    if ($es_po == 'ES'){
                                        // $final_runcard_no = substr($request->txt_po_number, 0, -4) . '-001';
                                        $final_runcard_no = str_replace('-', '', $request->txt_po_number). '-001'; //07-09-26 Sample Output: ES26095
                                    }else{
                                        $final_runcard_no = substr($request->txt_po_number, 0, -5) . '-001';

                                    }


                                    // $final_runcard_no = substr($request->txt_po_number, 0, -5) . '-001';
                                }

                                // return $final_runcard_no;

                                $prod_runcard_id = ProductionRuncard::insertGetId([
                                    'po_no'=> $request->txt_po_number,
                                    'status'=> 1,
                                    'runcard_no' => $final_runcard_no,
                                    'lot_qty' => $request->txt_lot_qty,
                                    'mold'=> $request->txt_mold,
                                    'ct_supplier'=> $request->txt_ct_supplier,
                                    'die_no'=> $request->txt_die_no,
                                    'pair_no'=> $request->txt_pair_no,
                                    'remarks'=> $request->txt_remarks,

                                    'setup_qualification' => $setup_qualification,
                                    'eng_qualification_id' => $eng_qualification_id,
                                    // 'setup_qualified' => $setup_qualified,
                                    'qc_qualification' => $qc_qualification,
                                    'qc_stamp_id' => $qc_stamp_id,
                                    'qc_qualified' => $qc_qualified,
                                    'qc_remarks' => $request->qc_remarks,

                                    'a_drawing_no'=> $request->txt_a_drawing_no,
                                    'a_drawing_rev'=> $request->txt_a_drawing_rev,
                                    'g_drawing_no'=> $request->txt_g_drawing_no,
                                    'g_drawing_rev'=> $request->txt_g_drawing_rev,
                                    'other_docs_no'=> $request->txt_other_docs_no,
                                    'other_docs_no'=> $request->txt_other_docs_no,
                                    'other_docs_rev'=> $request->txt_other_docs_rev,
                                    'comp_under_runcard_no'=> 0,
                                    'created_by' => $user_info->id,
                                    'last_updated_by' => $user_info->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),

                                    /**
                                     * Additionals
                                     * -JD
                                     */
                                    'qty_input'=> $request->input,
                                    'qty_output'=> $request->output,
                                    'qty_ng'=> $request->qty_ng,
                                    'operator_id'=> $request->operator_id, // 12-12-2022
                                    'mpc_operator_id'=> $request->mpc_operator_id, // 05-07-2023
                                    'disposition'=> $request->disposition,
                                    'mod'=> $request->mod,
                                    'prod_quali_remarks'=> $request->prod_quali_remarks,
                                    'qc_id'=> $request->qc_id,
                                    'judgement'=> $request->judgement,
                                    'work_week'=> $request->txt_work_week,
                                    'kit_number'=> $request->txt_kit_number,
                                    // 'defect_rework_number'=> $request->txt_defect_rework_no,
                                    'assembly_line_id'=> $request->assembly_line,
                                ]);

                                // insert data for process flow

                                if( count($device) > 0 ) {
                                    // if( $device[0]->type == 2) {
                                    if( $device[0]->type == 2 || $PFlow[0]->process == '1' || $PFlow[0]->process == '27') { //01132023 nessa
                                        $processFlow = ProcessFlow::where("device_id", $device[0]->id)->get();
                                        if( count($processFlow) > 0 ) {
                                            $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                                'production_runcard_id'=> $prod_runcard_id,
                                                'process_flow_id'=> $processFlow[0]->id,
                                                'qty_input'=>0,
                                                'qty_output'=>0,
                                                'qty_ng'=>0,
                                                'status' => 1,
                                                'created_by'=> Auth::user()->id,
                                                'last_updated_by'=> Auth::user()->id,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s'),
                                            ]);
                                        }
                                    }
                                }
                                // return 'if';

                                DB::commit();
                                return response()->json(['auth' => 1, 'result' => 1, 'PFlow' => $PFlow, 'error' => null, 'prod_runcard_id' => $prod_runcard_id]); // Success
                            }
                            else{
                                // return 'else';
                                return response()->json(['auth' => 1, 'result' => 2, 'error' => null]); // Invalid Operator
                            }
                        }
                        else{
                            return response()->json(['auth' => 1, 'result' => 0, 'PFlow' => $PFlow, 'error' => $validator->messages()]); // Failed
                        }
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        return response()->json(['auth' => 1, 'result' => 0, 'PFlow' => $PFlow, 'error' => $e]);
                    }
                }
            }
            else{
                return response()->json(['auth' => 0, 'result' => 0, 'PFlow' => $PFlow, 'error' => null]);
            }
        }
        else{
            abort(403);
        }
    }

    /*
        A1_issue
        Addditional Column to DB: runcard Column: from_prod_runcard_num
    */
    public function save_prod_runcard_details(Request $request){ // WORKING FUNCTION
        date_default_timezone_set('Asia/Manila');
        // return 'WORKING';
        if($request->ajax()){
            if(Auth::check()){

                // Update Runcard
                if(isset($request->txt_prod_runcard_id_query)){
                    $data = $request->all();
                    $rules = [
                        'txt_employee_number_scanner' => 'required',
                        'txt_po_number' => 'required',
                        'txt_lot_qty' => 'required',
                        // 'setup_qualification' => 'required',
                        // 'txt_pair_no' =>  'required',
                        'txt_kit_number' =>  'required',
                        // 'txt_defect_rework_no' =>  'required',
                    ];
                    //txt_from_prod_runcard_num
                    /**
                     * Condition for radio buttons
                     * -JD
                     */
                    if($request->judgement == 3 || $request->judgement == null){
                        $rules['input'] = '';
                        $rules['output'] = '';
                        $rules['qty_ng'] = '';
                        $rules['disposition'] = '';
                        $rules['mod'] = '';
                        $rules['qc_id'] = '';
                        $rules['judgement'] = '';
                        $rules['mpc_operator_id'] = ''; // JD 05-08-2023
                    }
                    else{
                        $rules['input'] = 'required';
                        $rules['output'] = 'required';
                        $rules['qty_ng'] = 'required';
                        // $rules['disposition'] = 'required';
                        $rules['disposition'] = ''; // Old -JD
                        $rules['mod'] = 'required';
                        // $rules['qc_id'] = 'required'; // Old -JD
                        $rules['qc_id'] = '';
                        $rules['judgement'] = 'required';
                        $rules['mpc_operator_id'] = 'required'; // JD 05-08-2023
                    }

                    $messages = [
                        'txt_employee_number_scanner.required' => 'Employee No. field is required.',
                        'txt_po_number.required' => 'PO No. field is required.',
                        'txt_lot_qty.required' => 'Lot Quantity field is required.',
                        // 'setup_qualification.required' => 'Setup Qualification field is required.',
                        // 'txt_pair_no.required' => 'Pair No. field is required.',
                        'txt_kit_number.required' => 'Kit No. field is required.',
                        // 'txt_defect_rework_no.required' => 'Defect/Rework No. field is required.',

                    ];

                    if(isset($request->judgement)){
                        $messages['input.required'] = 'Input is required';
                        $messages['output.required'] = 'Output is required';
                        $messages['qty_ng.required'] = 'NG is required';
                        $messages['disposition.required'] = 'Disposition is required';
                        $messages['mod.required'] = 'Mode of Defect is required';
                        // $messages['prod_quali_remarks.required'] = 'Prod Quali Remarks is required';
                        $messages['qc_id.required'] = 'QC is required';
                        $messages['judgement.required'] = 'Judgement is required';
                        $messages['assembly_line.required'] = 'Assembly Line is required';
                    }

                    /**
                     * 12-12-2022
                     */
                    if($request->judgement == 1 || $request->judgement == 2){
                        $rules['operator_id'] = 'required';
                        $rules['mpc_operator_id'] = 'required';
                    }else{
                        $rules['operator_id'] = '';
                        $rules['mpc_operator_id'] = '';
                    }

                    $validator = Validator::make($data, $rules, $messages);

                    DB::beginTransaction();
                    try {
                        if($validator->passes()){
                            $runcard_no = substr($request->txt_po_number, 0, -5);

                            $user_info = User::where('employee_id', $request->txt_employee_number_scanner)
                                ->where('status', 1)
                                ->first();

                            if($user_info != null){

                                $setup_qualification = 0;
                                $eng_qualification_id = null;
                                $setup_qualified = 0;
                                $qc_qualification = 0;
                                $qc_stamp_id = null;
                                $qc_qualified = 0;


                                if(isset($request->setup_qualification)){
                                    $setup_qualification = $request->setup_qualification;
                                }
                                if(isset($request->eng_qualification_id)){
                                    $eng_qualification_id = $request->eng_qualification_id;
                                }
                                // if(isset($request->setup_qualified)){
                                //     $setup_qualified = $request->setup_qualified;
                                // }
                                if(isset($request->qc_qualification)){
                                    $qc_qualification = $request->qc_qualification;
                                }
                                if(isset($request->qc_stamp_id)){
                                    $qc_stamp_id = $request->qc_stamp_id;
                                }
                                if(isset($request->qc_qualified)){
                                    $qc_qualified = $request->qc_qualified;
                                }

                                ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                                ->update(
                                    [
                                        // 'lot_qty' => $request->txt_lot_qty,
                                        // 'assessment_no'=> $request->txt_assessment_no,

                                        'setup_qualification' => $setup_qualification,
                                        'eng_qualification_id' => $eng_qualification_id,
                                        // 'setup_qualified' => $setup_qualified,
                                        'qc_qualification' => $qc_qualification,
                                        'qc_stamp_id' => $qc_stamp_id,
                                        'qc_qualified' => $qc_qualified,
                                        'qc_remarks' => $request->qc_remarks,

                                        'setup_qualification'=> $request->setup_qualification,
                                        'mold'=> $request->txt_mold,
                                        'ct_supplier'=> $request->txt_ct_supplier,
                                        'die_no'=> $request->txt_die_no,
                                        'pair_no'=> $request->txt_pair_no,
                                        'remarks'=> $request->txt_remarks,
                                        'other_docs_no'=> $request->txt_other_docs_no,
                                        'other_docs_rev'=> $request->txt_other_docs_rev,
                                        'last_updated_by' => $user_info->id,
                                        'updated_at' => date('Y-m-d H:i:s'),

                                        /**
                                         * Additionals
                                         * -JD
                                        */
                                        'qty_input'=> $request->input,
                                        'assembly_line_id'=> $request->assembly_line, // mdr 06-16-25 -> 11/05/2025 - updated
                                        'qty_output'=> $request->output,
                                        'operator_id'=> $request->operator_id, // 12-12-2022
                                        'mpc_operator_id'=> $request->mpc_operator_id, // 05-07-2023
                                        'qty_ng'=> $request->qty_ng,
                                        'disposition'=> $request->disposition,
                                        'mod'=> $request->mod,
                                        'prod_quali_remarks'=> $request->prod_quali_remarks,
                                        'qc_id'=> $request->qc_id,
                                        'judgement'=> $request->judgement,
                                        'work_week'=> $request->txt_work_week,
                                        'kit_number'=> $request->txt_kit_number,
                                        // 'defect_rework_number'=> $request->txt_defect_rework_no,
                                        // 'from_prod_runcard_num'=> $request->txt_from_prod_runcard_num,
                                    ]
                                );

                                DB::commit();
                                return response()->json(['auth' => 1, 'result' => 1, 'error' => null, 'prod_runcard_id' => $request->txt_prod_runcard_id_query]); // Success
                            }
                            else{
                                return response()->json(['auth' => 1, 'result' => 2, 'error' => null]); // Invalid Operator
                            }
                        }
                        else{
                            return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                        }
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                    }
                }
                // Add Runcard
                else{
                    // return 'asdasd';

                    $data = $request->all();

                    $rules = [
                        'txt_employee_number_scanner' => 'required',
                        'txt_po_number' => 'required',
                        'txt_lot_qty' => 'required',
                        // 'setup_qualification' => 'required',
                        // 'txt_pair_no' =>  'required',
                        'txt_kit_number' =>  'required',
                        // 'txt_defect_rework_no' =>  'required',

                    ];

                    /**
                     * Condition for radio buttons
                     */
                    if($request->judgement == 3 || $request->judgement == null){
                        $rules['input'] = '';
                        $rules['output'] = '';
                        $rules['qty_ng'] = '';
                        $rules['disposition'] = '';
                        $rules['mod'] = '';
                        $rules['qc_id'] = '';
                        $rules['judgement'] = '';
                    }else{
                        $rules['input'] = 'required';
                        $rules['output'] = 'required';
                        $rules['qty_ng'] = 'required';
                        // $rules['disposition'] = 'required'; // Old -JD
                        $rules['disposition'] = '';
                        $rules['mod'] = 'required';
                        // $rules['qc_id'] = 'required'; // Old -JD
                        $rules['qc_id'] = '';
                        $rules['judgement'] = 'required';
                    }

                    $setup_qualification = 0;
                    $eng_qualification_id = null;
                    // $setup_qualified = 0;
                    $qc_qualification = 0;
                    $qc_stamp_id = null;
                    $qc_qualified = 0;


                    if(isset($request->setup_qualification)){
                        $setup_qualification = $request->setup_qualification;
                    }
                    if(isset($request->eng_qualification_id)){
                        $eng_qualification_id = $request->eng_qualification_id;
                    }
                    // if(isset($request->setup_qualified)){
                    //     $setup_qualified = $request->setup_qualified;
                    // }
                    if(isset($request->qc_qualification)){
                        $qc_qualification = $request->qc_qualification;
                    }
                    if(isset($request->qc_stamp_id)){
                        $qc_stamp_id = $request->qc_stamp_id;
                    }
                    if(isset($request->qc_qualified)){
                        $qc_qualified = $request->qc_qualified;
                    }

                    $messages = [
                        'txt_employee_number_scanner.required' => 'Employee No. field is required.',
                        'txt_po_number.required' => 'PO No. field is required.',
                        'txt_lot_qty.required' => 'Lot Quantity field is required.',
                        'setup_qualification.required' => 'Setup Qualification field is required.',
                        // 'setup_qualification.required' => 'Setup Qualification field is required.',
                        // 'txt_pair_no.required' => 'Pair No. field is required.',
                    ];

                    if(isset($request->judgement)){
                        $messages['input.required'] = 'Input is required';
                        $messages['output.required'] = 'Output is required';
                        $messages['qty_ng.required'] = 'NG is required';
                        $messages['disposition.required'] = 'Disposition is required';
                        $messages['mod.required'] = 'Mode of Defect is required';
                        $messages['qc_id.required'] = 'QC is required';
                        $messages['judgement.required'] = 'Judgement is required';
                        $messages['assembly_line.required'] = 'Assembly Line is required';
                    }

                    /**
                     * 12-12-2022
                     */
                    if($request->judgement == 1 || $request->judgement == 2){
                        $rules['operator_id'] = 'required';
                        $rules['mpc_operator_id'] = 'required';
                    }else{
                        $rules['operator_id'] = '';
                        $rules['mpc_operator_id'] = '';
                    }

                    $validator = Validator::make($data, $rules, $messages);

                    DB::beginTransaction();
                    try {
                        if($validator->passes()){
                            $user_info = User::where('employee_id', $request->txt_employee_number_scanner)
                                ->where('status', 1)
                                ->first();

                            if($user_info != null){
                                /*nmodify MIGZ 2023 if the PO has -A1
                                 1. Add str contains
                                 2. Add status 1 to devices
                                */

                                // if (str_contains(  $request->txt_po_number, '-A1'  ) ){
                                //     $runcard_no = substr($request->txt_po_number, 0, -6);
                                // }
                                // else{
                                //     $runcard_no = substr($request->txt_po_number, 0, -5);
                                // }

                                /* 03112025 by Nessa */
                                $es_po = substr($request->txt_po_number, 0, 2);
                                // return $es_po;
                                // if ($es_po == 'ES'){
                                if ( str_contains(  $request->txt_po_number, 'ES'  ) ){
                                    $runcard_no = substr($request->txt_po_number, 0, -4);
                                }else if( str_contains(  $request->txt_po_number, '-A1'  ) ){
                                    $runcard_no = substr($request->txt_po_number, 0, -6);
                                }else{
                                    $runcard_no = substr($request->txt_po_number, 0, -5);
                                }

                                // return $runcard_no;


                                $prod_runcard_id = "";
                                $arr_packing_months = [1,2,3,4,5,6,7,8,9,'X','Y','Z'];
                                $final_reel_lot_no = "";

                                $last_no_of_the_year = date('Y')[3];
                                $packing_month = $arr_packing_months[(integer)date('m') - 1];
                                $packing_date = date('d');
                                $serial_no = str_pad(1,2,"0", STR_PAD_LEFT);
                                $lot_no_machine_code = '0';

                                $device_info = CommonController::getInstance()->getMatrixByPoNo(['po_no' => $request->txt_po_number, 'name' => $request->device_name, 'barcode' => '', 'get_or_first' => 'first']);
                                // $device_info = Device::where('name', $request->device_name)->where('status', 1)->first();
                                // return $device_info;

                                if($device_info != null){
                                    if($device_info->lot_no_machine_code != null){
                                        $lot_no_machine_code = $device_info->lot_no_machine_code;
                                    }
                                }

                                // return 'asd';
                                // Generate Runcard No
                                // return $runcard_no;
                                $prod_runcards = ProductionRuncard::
                                // where('runcard_no', 'LIKE', $runcard_no . "%")
                                where('po_no', $request->txt_po_number)
                                ->orderBy('id', 'desc')->limit(1)->get();

                                // return $prod_runcards;

                                $final_runcard_no = "";
                                $explode_runcard_no = "";

                                if($prod_runcards->count() > 0){
                                     $explode_runcard_no = explode('-', $prod_runcards[0]->runcard_no);

                                    $explode_runcard_no[1] = str_pad($explode_runcard_no[1] + 1,3,"0", STR_PAD_LEFT);

                                    $final_runcard_no = implode('-', $explode_runcard_no);
                                }
                                else{
                                    /* 03112025 by Nessa */
                                    // $es_po = substr($request->txt_po_number, 0, 2);
                                    if ( str_contains(  $request->txt_po_number, 'ES') ){
                                        // $final_runcard_no = substr($request->txt_po_number, 0, -4) . '-001';
                                        $final_runcard_no = str_replace('-','',$request->txt_po_number) . '-001'; //06282025 Updated by Darren&Nessa
                                    }else{
                                        $final_runcard_no = substr($request->txt_po_number, 0, -5) . '-001';

                                    }

                                    // $final_runcard_no = substr($request->txt_po_number, 0, -5) . '-001';
                                }

                                // return $final_runcard_no;

                                $prod_runcard_id = ProductionRuncard::insertGetId([
                                    'po_no'=> $request->txt_po_number,
                                    'status'=> 1,
                                    'runcard_no' => $final_runcard_no,
                                    'lot_qty' => $request->txt_lot_qty,
                                    'mold'=> $request->txt_mold,
                                    'ct_supplier'=> $request->txt_ct_supplier,
                                    'die_no'=> $request->txt_die_no,
                                    'pair_no'=> $request->txt_pair_no,
                                    'remarks'=> $request->txt_remarks,

                                    'setup_qualification' => $setup_qualification,
                                    'eng_qualification_id' => $eng_qualification_id,
                                    // 'setup_qualified' => $setup_qualified,
                                    'qc_qualification' => $qc_qualification,
                                    'qc_stamp_id' => $qc_stamp_id,
                                    'qc_qualified' => $qc_qualified,
                                    'qc_remarks' => $request->qc_remarks,

                                    'a_drawing_no'=> $request->txt_a_drawing_no,
                                    'a_drawing_rev'=> $request->txt_a_drawing_rev,
                                    'g_drawing_no'=> $request->txt_g_drawing_no,
                                    'g_drawing_rev'=> $request->txt_g_drawing_rev,
                                    'other_docs_no'=> $request->txt_other_docs_no,
                                    'other_docs_no'=> $request->txt_other_docs_no,
                                    'other_docs_rev'=> $request->txt_other_docs_rev,
                                    'comp_under_runcard_no'=> 0,
                                    'created_by' => $user_info->id,
                                    'last_updated_by' => $user_info->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),

                                    /**
                                     * Additionals
                                     * -JD
                                     */
                                    'qty_input'=> $request->input,
                                    'assembly_line'=> $request->assembly_line, // mdr 06-16-25
                                    'qty_output'=> $request->output,
                                    'qty_ng'=> $request->qty_ng,
                                    'operator_id'=> $request->operator_id, // 12-12-2022
                                    'mpc_operator_id'=> $request->mpc_operator_id, // 05-07-2023
                                    'disposition'=> $request->disposition,
                                    'mod'=> $request->mod,
                                    'prod_quali_remarks'=> $request->prod_quali_remarks,
                                    'qc_id'=> $request->qc_id,
                                    'judgement'=> $request->judgement,
                                    'work_week'=> $request->txt_work_week,
                                    'kit_number'=> $request->txt_kit_number,
                                    // 'defect_rework_number'=> $request->txt_defect_rework_no,
                                    // 'from_prod_runcard_num'=> $request->txt_from_prod_runcard_num,
                                ]);
                                // return 'asda';
                                // insert data for process flow
                                // return $request->device_name;
                                $device = CommonController::getInstance()->getMatrixByPoNo(['po_no' => $request->txt_po_number, 'name' => $request->device_name, 'barcode' => '', 'get_or_first' => '']);
                                // $device = Device::where("name", $request->device_name)->get();
                                // return $device;

                                $PFlow = ProcessFlow::where("device_id", $device[0]->id)->get();


                                if( count($device) > 0 ) {

                                    // if( $device[0]->type == 2) {
                                    if( $device[0]->type == 2 || $PFlow[0]->process == '1' || $PFlow[0]->process == '27') { //01132023 nessa
                                        $processFlow = ProcessFlow::where("device_id", $device[0]->id)->get();
                                        if( count($processFlow) > 0 ) {
                                            $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                                'production_runcard_id'=> $prod_runcard_id,
                                                'process_flow_id'=> $processFlow[0]->id,
                                                'qty_input'=>0,
                                                'qty_output'=>0,
                                                'qty_ng'=>0,
                                                'status' => 1,
                                                'created_by'=> Auth::user()->id,
                                                'last_updated_by'=> Auth::user()->id,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s'),
                                            ]);
                                        }
                                    }
                                }
                        // DB::rollback();
                        // return response()->json(['auth' => 1, 'result' => 0, 'error' => "haha", "device" => $device, "processFlow" => $processFlow, "prod_runcard_station_id" => $prod_runcard_station_id]);

                                DB::commit();
                                return response()->json(['auth' => 1, 'result' => 1, 'PFlow' => $PFlow, 'error' => null, 'prod_runcard_id' => $prod_runcard_id]); // Success
                            }
                            else{
                                return response()->json(['auth' => 1, 'result' => 2, 'error' => null]); // Invalid Operator
                            }
                        }
                        else{
                            return response()->json(['auth' => 1, 'result' => 0,'error' => $validator->messages()]); // Failed
                        }
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        throw $e;

                    }
                }
            }
            else{
                return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
            }
        }
        else{
            abort(403);
        }
    }




    public function submit_to_oqc_lot_app(Request $request){ // FOR MANUAL AND AUTOMATIC RUNCARD
        date_default_timezone_set('Asia/Manila');
        if($request->ajax()){
            if(Auth::check()){
                // Update Runcard

                $data = $request->all();

                $rules = [
                    'txt_employee_number_scanner' => 'required',
                    // 'txt_po_number' => 'required',
                    // 'txt_lot_qty' => 'required',
                    // 'setup_qualification' => 'required',
                    // 'txt_pair_no' =>  'required',
                    'txt_prod_runcard_id_query' => 'required'
                ];

                $messages = [
                    'txt_employee_number_scanner.required' => 'Employee No. field is required.',
                    // 'txt_po_number.required' => 'PO No. field is required.',
                    // 'txt_lot_qty.required' => 'Lot Quantity field is required.',
                    // 'setup_qualification.required' => 'Setup Qualification field is required.',
                    // 'txt_pair_no.required' => 'Pair No. field is required.',
                    'txt_prod_runcard_id_query.txt_prod_runcard_id_query' => 'Production Runcard ID field is required.',
                ];

                $validator = Validator::make($data, $rules, $messages);

                DB::beginTransaction();
                try {
                    if($validator->passes()){

                        $result = 0;
                        $runcard_no = substr($request->txt_po_number, 0, -5);

                        $user_info = User::where('employee_id', $request->txt_employee_number_scanner)
                            ->where('status', 1)
                            ->first();

                        if($user_info != null){

                            $prod_runcard_info = ProductionRuncard::where('id', $request->txt_prod_runcard_id_query)->first();

                            if($prod_runcard_info != null){
                                //10022022
                               /* $error_counter = 0;
                                if($prod_runcard_info->setup_qualification == 1){
                                    // if($prod_runcard_info->setup_qualified == 0){
                                    //     $error_counter++;
                                    // }
                                    if($prod_runcard_info->eng_qualification_id == null){
                                        // $error_counter++; // Uncomment this to enable checking in technician
                                    }
                                }

                                if($prod_runcard_info->qc_qualification == 1){
                                    // if($prod_runcard_info->qc_qualification == 0){
                                    //     $error_counter++;
                                    // }
                                    if($prod_runcard_info->qc_stamp_id == null){
                                        $error_counter++;
                                    }
                                }
                                else{

                                }*/


                               /* if($prod_runcard_info->setup_qualification == 0){
                                    $check_runcard = ProductionRuncard::where('pair_no', $prod_runcard_info->pair_no)
                                    ->where('po_no', $prod_runcard_info->po_no)
                                    ->where('setup_qualification', 1)
                                    ->where('eng_qualification_id', '!=', null)
                                    ->orderBy('id', 'desc')
                                    ->first();

                                    if($check_runcard == null){
                                        // $error_counter++; // Uncomment this to enable checking in technician
                                    }

                                    // return $check_runcard;
                                }

                                if($prod_runcard_info->qc_qualification == 0){
                                    $check_runcard = ProductionRuncard::where('pair_no', $prod_runcard_info->pair_no)
                                    ->where('po_no', $prod_runcard_info->po_no)
                                    ->where('qc_qualification', 1)
                                    ->where('qc_qualified', 1)
                                    ->where('qc_stamp_id', '!=', null)
                                    ->orderBy('id', 'desc')
                                    ->first();

                                    if($check_runcard == null){
                                        $error_counter++;
                                    }
                                    // return $check_runcard;
                                }*/

                                // if($error_counter <= 0){
                                    ProductionRuncard::where('id',$request->txt_prod_runcard_id_query)
                                    ->update(
                                        [
                                            'status'=> 3,
                                            'last_updated_by' => $user_info->id,
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ]
                                    );
                                    $result = 1;
                                // }
                                // else{
                                //     $result = 2;
                                // }

                            }
                            else{
                                $result = 3; // Runcard not found
                            }


                            DB::commit();
                            return response()->json(['auth' => 1, 'result' => $result, 'error' => null, 'prod_runcard_id' => $request->txt_prod_runcard_id_query]); // Success
                        }
                        else{
                            return response()->json(['auth' => 1, 'result' => 2, 'error' => null]); // Invalid Operator
                        }
                    }
                    else{
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                    }
                }
                catch(\Exception $e) {
                    DB::rollback();
                    return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                }

            }
        }
    }

    public function save_material(Request $request){ // FOR MANUAL AND AUTOMATIC RUNCARD
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $data = $request->all();

        $validator = Validator::make($data, [
            'txt_prod_runcard_id_query' => ['required'],
            'txt_po_number' => ['required'],
            'lot_no' => ['required'],
            'material_id' => ['required'],
            'type' => ['required'],
        ]);

        $material_process = [];


        if($validator->passes()){

            // $runcard_no = substr($request->txt_po_number, 0, -5);

            $prod_runcard_id = "";

            $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();

            if($user->count() > 0){
                // if( isset($request->txt_prod_runcard_id_query) ){
                    //update
                    DB::beginTransaction();
                    try {
                        // $prod_runcard_id = $request->txt_prod_runcard_id_query;
                        ProductionRuncardMaterial::insert([
                            'po_no' => $request->txt_po_number,
                            'lot_no' => $request->lot_no,
                            'type' => $request->type,
                            'status' => 1,
                            'runcard_id' => $request->txt_prod_runcard_id_query,
                            'pats_material_id' => $request->material_id,
                            'created_by' => Auth::user()->id,
                            'last_updated_by' => Auth::user()->id,
                        ]);
                        DB::commit();
                        $result = true;

                    } catch (Exception $e) {
                        DB::rollback();
                        $result = false;
                    }
                // }

                if( !$result ){
                    $return_title = '<i class="fa fa-times-circle text-danger"></i> Not saved';
                    $return_body = 'An error occured. The record not saved.';
                }
                $return = array('title'=>$return_title,'body'=>$return_body, 'prod_runcard_id' => $prod_runcard_id, 'result' => $result);
                return $return;
            }

        }
        else{
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = 'Saving Failed!';
            $return = array('title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
            return $return;
        }
    }

    public function view_materials_by_runcard_id(Request $request){
        // $materials = ProductionRuncardMaterial::where('runcard_id', $request->runcard_id)->with(['material_info'])
        // ->get();

        $prod_runcardstations = ProductionRuncardStation::with([
                'process_flow',
                'process_flow.processFlow',
                'prod_runcard_station_material_details', // Added -JD
            ])
            ->where('status', 1)
            ->where('production_runcard_id', $request->runcard_id )
            ->get();

        return DataTables::of($prod_runcardstations)
        ->addColumn('raw_type', function($row){
            if( !isset($row->prod_runcard_station_material_details->material_type) )
                return 'Type Not Found';

            if($row->prod_runcard_station_material_details->material_type == 1){
                return 'Material Issuance';
            }
            else{
                return 'Sakidashi';
            }
        })
        ->addColumn('process_name', function($row){
            if( !isset($row->prod_runcard_station_material_details->material_name) )
                return 'Name Not Found';

            return $row->prod_runcard_station_material_details->material_name;
        })
        ->rawColumns(['status'])
        ->make(true);
    }


    /* Old code, commented -JD */
    // public function view_materials_by_runcard_id(Request $request){
    //     // $materials = ProductionRuncardMaterial::where('runcard_id', $request->runcard_id)->with(['material_info'])
    //     // ->get();

    //     $prod_runcardstations = ProductionRuncardStation::with([
    //             'process_flow',
    //             'process_flow.processFlow',
    //         ])
    //         ->where('status', 1)
    //         ->where('production_runcard_id', $request->runcard_id )
    //         ->get();

    //     return DataTables::of($prod_runcardstations)
    //     ->addColumn('raw_type', function($row){
    //         if( !isset($row->process_flow->material_type) )
    //             return 'Type Not Found';

    //         if($row->process_flow->material_type == 1){
    //             return 'Material Issuance';
    //         }
    //         else{
    //             return 'Sakidashi';
    //         }
    //     })
    //     ->addColumn('process_name', function($row){
    //         if( !isset($row->process_flow->process_flow->name) )
    //             return 'Name Not Found';

    //         return $row->process_flow->process_flow->name;
    //     })
    //     ->rawColumns(['status', 'btn_save_material'])
    //     ->make(true);
    // }


    public function view_manual_material_kitting_by_runcard(Request $request){
        $material_lists = [];
        $arr_issuance_id = [];

        if(isset($request->prod_runcard_id_query)){
            $prod_runcard_mat_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
                    ->where('tbl_wbs', 1)
                    ->where('status', 1)
                    ->get();

            $arr_issuance_id = $prod_runcard_mat_list->pluck('issuance_id');

            if($request->action == 1){

                if(count($prod_runcard_mat_list) > 0){
                    $material_lists = WBSKitIssuance::with([
                            'kit_issuance',
                            'kit_issuance.device_info',
                            'kit_issuance.device_info.material_process',
                            'kit_issuance.device_info.material_process.station_sub_station',
                            'kit_issuance.device_info.material_process.station_sub_station.station',
                            'parts_prep_info' => function($query){
                                $query->where('wbs_table', 1);
                                $query->where('deleted_at', null);
                            },
                        ])
                    ->where('po','=',$request['po_number'])
                    ->whereIn('id', $arr_issuance_id)
                    ->get();
                }
            }
            else{
                $material_lists = WBSKitIssuance::with([
                        'kit_issuance',
                        'kit_issuance.device_info',
                        'kit_issuance.device_info.material_process',
                        'kit_issuance.device_info.material_process.station_sub_station',
                        'kit_issuance.device_info.material_process.station_sub_station.station',
                        'parts_prep_info' => function($query){
                            $query->where('wbs_table', 1);
                            $query->where('deleted_at', null);
                        },
                    ])
                ->where('po','=',$request['po_number'])
                ->where('lot_no', $request->lot_no)
                ->whereNotIn('id', $arr_issuance_id)
                ->get();
            }
        }
        else{
            if($request->action == 2){
                            $material_lists = WBSKitIssuance::with([
                        'kit_issuance',
                        'kit_issuance.device_info',
                        'kit_issuance.device_info.material_process',
                        'kit_issuance.device_info.material_process.station_sub_station',
                        'kit_issuance.device_info.material_process.station_sub_station.station',
                        'parts_prep_info' => function($query){
                            $query->where('wbs_table', 1);
                            $query->where('deleted_at', null);
                        },
                    ])
                ->where('po','=',$request['po_number'])
                ->where('lot_no', $request->lot_no)
                ->get();
            }
        }


        return DataTables::of($material_lists)
            ->addColumn('raw_status', function($material_list){
                $disabled = 'disabled';
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
                $title = "";
                $parts_prep_done = false;

                if ( $material_list->parts_prep_info != null ) {
                    if( $material_list->parts_prep_info->with_partsprep == 1){
                        switch ( $material_list->parts_prep_info->status ) {
                            case 1:
                                $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 2:
                                $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
                                break;
                            case 3:
                                $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 4:
                                $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 5:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;
                            case 6:
                                $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 7:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                    else{
                        $result = '<i title="Saved" class="fas fa-cogs text-success px-2"></i>';
                        $parts_prep_done = true;
                    }
                }
                else{
                    $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
                }
                $result.=' <input type="hidden" class="col_material_id" value="'.$material_list->id.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$material_list->device_code.'">';

                if($parts_prep_done == true){
                    $result.=' <input type="hidden" class="col_lot_id" value="'.$material_list->id.'">';
                }

                return $result;
            })
            ->rawColumns(['raw_action', 'raw_status'])
            ->make(true);
    }

    public function view_manual_sakidashi_by_runcard(Request $request){
        date_default_timezone_set('Asia/Manila');
        $sakidashi_list = [];
        $arr_issuance_id = [];

        if(isset($request->prod_runcard_id_query)){
            $prod_runcard_sak_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
                    ->where('tbl_wbs', 2)
                    // ->where('for_emboss', 0)
                    ->where('status', 1)
                    ->get();

            $arr_issuance_id = $prod_runcard_sak_list->pluck('issuance_id');

            if($request->action == 1){
                if(count($prod_runcard_sak_list) > 0){
                    $sakidashi_list = WBSSakidashiIssuance::with([
                            'tbl_wbs_sakidashi_issuance_item',
                            'device_info',
                            'device_info.material_process',
                            'device_info.material_process.station_sub_station',
                            'device_info.material_process.station_sub_station.station',
                            'parts_prep_info' => function($query){
                                $query->where('wbs_table', 2);
                                $query->where('deleted_at', null);
                            },
                        ])
                    ->where('po_no','=',$request['po_number'])
                    ->whereIn('id', $arr_issuance_id)
                    ->get();
                }
            }
            else{
                $sakidashi_list = WBSSakidashiIssuance::with([
                            'tbl_wbs_sakidashi_issuance_item' => function($query) use($request){
                                $query->where('lot_no', $request->lot_no);
                            },
                            'device_info',
                            'device_info.material_process',
                            'device_info.material_process.station_sub_station',
                            'device_info.material_process.station_sub_station.station',
                            'parts_prep_info' => function($query){
                                $query->where('wbs_table', 2);
                                $query->where('deleted_at', null);
                            },
                        ])
                    ->where('po_no','=',$request['po_number'])
                    // ->where('lot_no', $request->lot_no)
                    ->whereNotIn('id', $arr_issuance_id)
                    ->get();
                $sakidashi_list = collect($sakidashi_list)->where('tbl_wbs_sakidashi_issuance_item', '!=', null)->flatten(1);
            }
        }
        else{
            $sakidashi_list = WBSSakidashiIssuance::with([
                        'tbl_wbs_sakidashi_issuance_item' => function($query) use($request){
                            $query->where('lot_no', $request->lot_no);
                        },
                        'device_info',
                        'device_info.material_process',
                        'device_info.material_process.station_sub_station',
                        'device_info.material_process.station_sub_station.station',
                        'parts_prep_info' => function($query){
                            $query->where('wbs_table', 2);
                            $query->where('deleted_at', null);
                        },
                    ])
                ->where('po_no','=',$request['po_number'])
                ->get();
            $sakidashi_list = collect($sakidashi_list)->where('tbl_wbs_sakidashi_issuance_item', '!=', null)->flatten(1);
        }

        return DataTables::of($sakidashi_list)
            ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
                $disabled = 'disabled';
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
                $title = "";
                $parts_prep_done = false;

                if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
                    if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
                        switch ( $tbl_wbs_sakidashi_issuance->parts_prep_info->status ) {
                            case 1:
                                $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 2:
                                $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
                                break;
                            case 3:
                                $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 4:
                                $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 5:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;
                            case 6:
                                $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
                                break;
                            case 7:
                                $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
                                $parts_prep_done = true;
                                break;

                            default:
                                # code...
                                break;
                        }
                    }                                    else{
                        $result = '<i title="Ready for Prod\'n" class="fas fa-cogs text-success px-2"></i>';
                        $parts_prep_done = true;
                    }
                }
                else{
                    $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
                }

                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

                if($parts_prep_done == true){
                    $result.=' <input type="hidden" class="col_lot_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                }

                return $result;
            })
            // ->addColumn('action', function($tbl_wbs_sakidashi_issuance) use ($runcard_status, $chkDisable){
            //     $disabled = 'disabled';
            //     $result = "";
            //     $title = "";
            //     $has_parts_prep = false;
            //     $has_prod = false;

            //     if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
            //         if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
            //             if( $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 5 || $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 7 ){ //Not approved in parts prep
            //                 $disabled = '';
            //             }
            //             else{
            //                 $disabled = 'disabled';
            //             }
            //         }
            //         else{
            //             $disabled = '';
            //         }
            //     }
            //     else{
            //         $disabled = 'disabled';
            //     }

            //     if($runcard_status == 1){
            //         $disabled = 'disabled';
            //     }

            //     if($chkDisable){
            //         $disabled = 'disabled';
            //     }

            //     $result='<center><input sakidashi-issue-id="' . $tbl_wbs_sakidashi_issuance->id . '" type="checkbox" '. $disabled .' title="Check to select" class="py-0 chkSelSakidashiIssue" style="display: block;"></center>';

            //     $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
            //     $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

            //     return $result;
            // })
            ->addColumn('btn_save_material', function($tbl_wbs_sakidashi_issuance) use($request){
                $disabled = 'disabled';
                $result = "";
                $title = "";
                $has_parts_prep = false;
                $has_prod = false;

                if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
                    if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
                        if( $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 5 || $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 7 ){ //Not approved in parts prep
                            $disabled = '';
                        }
                        else{
                            $disabled = 'disabled';
                        }
                    }
                    else{
                        $disabled = '';
                    }
                }
                else{
                    $disabled = 'disabled';
                }

                // if($runcard_status == 1){
                //     $disabled = 'disabled';
                // }

                // if($chkDisable){
                //     $disabled = 'disabled';
                // }

                // $result='<center><input sakidashi-issue-id="' . $tbl_wbs_sakidashi_issuance->id . '" type="checkbox" '. $disabled .' title="Check to select" class="py-0 btnSaveSakidashiLot" style="display: block;"></center>';

                if($request->action == 2){
                    $result='<center><button style="width: 22px; padding: 1px 1px;" class="btn btn-info btn-sm py-0 btnSaveSakidashiLot" ' . $disabled . ' issuance-id="' . $tbl_wbs_sakidashi_issuance->id . '" item="' . $tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item . '" item-desc="' . $tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item_desc . '"><i class="fa fa-check" title="Click to save material"></i></button></center>';
                }
                else{
                    $result = '<center><i title="Saved" class="fas fa-check text-success px-2"></i></center>';
                }

                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

                return $result;
            })
            ->addColumn('runcard_used_qty', function($tbl_wbs_sakidashi_issuance){
                $result = 0;
                if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
                    for($index = 0; $index < count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list); $index++){
                        if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details != null){
                            if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details)){
                                $result += $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details[0]->qty_output;
                            }
                        }
                    }
                }
                return $result;
            })
            ->addColumn('lot_qty_to_complete', function($tbl_wbs_sakidashi_issuance){
                $result = 0;
                if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
                    if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details != null){
                        $result = $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details->lot_qty;
                    }
                }
                return $result;
            })
            ->rawColumns(['status', 'btn_save_material'])
            ->make(true);
    }

    // public function view_manual_emboss_by_runcard(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     $emboss_list = [];
    //     $arr_issuance_id = [];

    //     if(isset($request->prod_runcard_id_query)){
    //         $prod_runcard_sak_list = ProdRuncardMaterialList::where('prod_runcard_id', $request->prod_runcard_id_query)
    //                 ->where('tbl_wbs', 2)
    //                 ->where('for_emboss', 1)
    //                 ->where('status', 1)
    //                 ->get();

    //         $arr_issuance_id = $prod_runcard_sak_list->pluck('issuance_id');

    //         if($request->action == 1){
    //             if(count($prod_runcard_sak_list) > 0){
    //                 $emboss_list = WBSSakidashiIssuance::with([
    //                         'tbl_wbs_sakidashi_issuance_item',
    //                         'device_info',
    //                         'device_info.material_process',
    //                         'device_info.material_process.station_sub_station',
    //                         'device_info.material_process.station_sub_station.station',
    //                         'parts_prep_info' => function($query){
    //                             $query->where('wbs_table', 2);
    //                             $query->where('deleted_at', null);
    //                         },
    //                     ])
    //                 ->where('po_no','=',$request['po_number'])
    //                 ->whereIn('id', $arr_issuance_id)
    //                 ->get();
    //             }
    //         }
    //         else{
    //             $emboss_list = WBSSakidashiIssuance::with([
    //                         'tbl_wbs_sakidashi_issuance_item' => function($query) use($request){
    //                             $query->where('lot_no', $request->lot_no);
    //                         },
    //                         'device_info',
    //                         'device_info.material_process',
    //                         'device_info.material_process.station_sub_station',
    //                         'device_info.material_process.station_sub_station.station',
    //                         'parts_prep_info' => function($query){
    //                             $query->where('wbs_table', 2);
    //                             $query->where('deleted_at', null);
    //                         },
    //                     ])
    //                 ->where('po_no','=',$request['po_number'])
    //                 // ->where('lot_no', $request->lot_no)
    //                 ->whereNotIn('id', $arr_issuance_id)
    //                 ->get();

    //             $emboss_list = collect($emboss_list)->where('tbl_wbs_sakidashi_issuance_item', '!=', null)->flatten(1);
    //         }
    //     }
    //     else{
    //         $emboss_list = WBSSakidashiIssuance::with([
    //                     'tbl_wbs_sakidashi_issuance_item' => function($query) use($request){
    //                         $query->where('lot_no', $request->lot_no);
    //                     },
    //                     'device_info',
    //                     'device_info.material_process',
    //                     'device_info.material_process.station_sub_station',
    //                     'device_info.material_process.station_sub_station.station',
    //                     'parts_prep_info' => function($query){
    //                         $query->where('wbs_table', 2);
    //                         $query->where('deleted_at', null);
    //                     },
    //                 ])
    //             ->where('po_no','=',$request['po_number'])
    //             // ->where('lot_no', $request->lot_no)
    //             // ->whereNotIn('id', $arr_issuance_id)
    //             ->get();

    //         $emboss_list = collect($emboss_list)->where('tbl_wbs_sakidashi_issuance_item', '!=', null)->flatten(1);
    //     }

    //     return DataTables::of($emboss_list)
    //         ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
    //             $disabled = 'disabled';
    //             $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
    //             $title = "";
    //             $parts_prep_done = false;

    //             if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
    //                 if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
    //                     switch ( $tbl_wbs_sakidashi_issuance->parts_prep_info->status ) {
    //                         case 1:
    //                             $result = '<i title="For MH Fill-in" class="far fa-clock text-secondary px-2"></i>';
    //                             break;
    //                         case 2:
    //                             $result = '<i title="Returned to WHS" class="fa fa-exclamation-triangle text-warning px-2"></i>';
    //                             break;
    //                         case 3:
    //                             $result = '<i title="For Parts Prep Fill-in" class="far fa-clock text-secondary px-2"></i>';
    //                             break;
    //                         case 4:
    //                             $result = '<i title="Ongoing in stations/for verification" class="far fa-clock text-secondary px-2"></i>';
    //                             break;
    //                         case 5:
    //                             $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
    //                             $parts_prep_done = true;
    //                             break;
    //                         case 6:
    //                             $result = '<i title="For checking" class="far fa-clock text-secondary px-2"></i>';
    //                             break;
    //                         case 7:
    //                             $result = '<i title="Parts Prep. Done verification" class="fas fa-cogs text-success px-2"></i>';
    //                             $parts_prep_done = true;
    //                             break;

    //                         default:
    //                             # code...
    //                             break;
    //                     }
    //                 }                                    else{
    //                     $result = '<i title="Ready for Prod\'n" class="fas fa-cogs text-success px-2"></i>';
    //                     $parts_prep_done = true;
    //                 }
    //             }
    //             else{
    //                 $result = '<i title="To Receive" class="far fa-clock text-secondary px-2"></i>';
    //             }

    //             $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
    //             $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

    //             if($parts_prep_done == true){
    //                 $result.=' <input type="hidden" class="col_lot_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('btn_save_material', function($tbl_wbs_sakidashi_issuance) use($request){
    //             $disabled = 'disabled';
    //             $result = "";
    //             $title = "";
    //             $has_parts_prep = false;
    //             $has_prod = false;

    //             if ( $tbl_wbs_sakidashi_issuance->parts_prep_info != null ) {
    //                 if( $tbl_wbs_sakidashi_issuance->parts_prep_info->with_partsprep == 1){
    //                     if( $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 5 || $tbl_wbs_sakidashi_issuance->parts_prep_info->status == 7 ){ //Not approved in parts prep
    //                         $disabled = '';
    //                     }
    //                     else{
    //                         $disabled = 'disabled';
    //                     }
    //                 }
    //                 else{
    //                     $disabled = '';
    //                 }
    //             }
    //             else{
    //                 $disabled = 'disabled';
    //             }

    //             // if($runcard_status == 1){
    //             //     $disabled = 'disabled';
    //             // }

    //             // if($chkDisable){
    //             //     $disabled = 'disabled';
    //             // }

    //             // $result='<center><input sakidashi-issue-id="' . $tbl_wbs_sakidashi_issuance->id . '" type="checkbox" '. $disabled .' title="Check to select" class="py-0 btnSaveSakidashiLot" style="display: block;"></center>';

    //             if($request->action == 2){
    //                 $result='<center><button style="width: 22px; padding: 1px 1px;" class="btn btn-info btn-sm py-0 btnSaveEmbossLot" ' . $disabled . ' issuance-id="' . $tbl_wbs_sakidashi_issuance->id . '" item="' . $tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item . '" item-desc="' . $tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item_desc . '"><i class="fa fa-check" title="Click to save material"></i></button></center>';
    //             }
    //             else{
    //                 $result = '<center><i title="Saved" class="fas fa-check text-success px-2"></i></center>';
    //             }

    //             $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
    //             $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

    //             return $result;
    //         })
    //         ->addColumn('runcard_used_qty', function($tbl_wbs_sakidashi_issuance){
    //             $result = 0;
    //             if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
    //                 for($index = 0; $index < count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list); $index++){
    //                     if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details != null){
    //                         if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details)){
    //                             $result += $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[$index]->prod_runcard_details->prod_runcard_station_many_details[0]->qty_output;
    //                         }
    //                     }
    //                 }
    //             }
    //             return $result;
    //         })
    //         ->addColumn('lot_qty_to_complete', function($tbl_wbs_sakidashi_issuance){
    //             $result = 0;
    //             if(count($tbl_wbs_sakidashi_issuance->prod_runcard_material_list) > 0){
    //                 if($tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details != null){
    //                     $result = $tbl_wbs_sakidashi_issuance->prod_runcard_material_list[0]->prod_runcard_details->lot_qty;
    //                 }
    //             }
    //             return $result;
    //         })
    //         ->rawColumns(['status', 'btn_save_material'])
    //         ->make(true);
    // }

    public function save_sakidashi_lot_issuance(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $final_result = 0;
        $remarks = "";
        $error = [];

        $validator = Validator::make($data, [
            // 'txt_prod_runcard_id_query' => ['required'],
            'issuance_id' => ['required'],
            'item' => ['required'],
            'item_desc' => ['required'],
            'operator' => ['required'],
            'prod_runcard_id' => ['required'],
            'device_name' => ['required'],
        ]);

        $mat_proc_materials = [];


        if($validator->passes()){
            $user = User::where('employee_id', $request->operator)->first();

            if($user != null){
                if($user->position == 4){
                    $mat_proc_materials = Device::with([
                        'material_process'  => function($query){
                            $query->where('status', 1);
                        },
                        'material_process.material_details' => function($query) use($request){
                            $query->where('status', 1);
                            $query->where('tbl_wbs', 2);
                            $query->where('item', $request->item);
                        },
                    ])
                    ->where('name', $request->device_name)
                    ->first();

                    // return $mat_proc_materials;
                    if($mat_proc_materials != null){
                        if(count($mat_proc_materials->material_process) > 0){
                            if($mat_proc_materials != null){
                                $col_mat_proc_materials = collect($mat_proc_materials)->flatten(1)->where('material_details', '!=', null);
                                $certified_mat_proc_id = $col_mat_proc_materials->pluck('id');
                            }

                            // return $certified_mat_proc_id;

                            if(count($certified_mat_proc_id) > 0){
                                $prod_runcard_station_details = ProductionRuncardStation::where('production_runcard_id', $request->prod_runcard_id)
                                    ->whereIn('mat_proc_id', $certified_mat_proc_id)
                                    ->get();

                                $prod_runcard_station_detail_ids = $prod_runcard_station_details->pluck('id');

                                // Save Material
                                if(count($prod_runcard_station_detail_ids) > 0){
                                    DB::beginTransaction();
                                    try {
                                        ProdRuncardMaterialList::insert([
                                            'prod_runcard_id' => $request->prod_runcard_id,
                                            'status' => 1,
                                            'issuance_id' => $request->issuance_id,
                                            'tbl_wbs' => 2,
                                            // 'for_emboss' => 0,
                                            'status' => 1,
                                        ]);

                                        for($index = 0; $index < count($prod_runcard_station_detail_ids); $index++){
                                            ProductionRuncardStationMaterial::insert([
                                                'production_runcard_id' => $request->prod_runcard_id,
                                                'production_runcard_station_id' => $prod_runcard_station_detail_ids[$index],
                                                'issuance_id' => $request->issuance_id,
                                                'item' => $request->item,
                                                'item_desc' => $request->item_desc,
                                                'tbl_wbs' => 2,
                                                // 'has_emboss' => 0,
                                                'status' => 1,
                                                'created_by' => $user->id,
                                                'last_updated_by' => $user->id,
                                                'update_version' => 1,
                                                'updated_at' => date('Y-m-d H:i:s'),
                                                'created_at' => date('Y-m-d H:i:s')
                                            ]);
                                        }

                                        DB::commit();
                                        $final_result = 1;
                                        $remarks = "Material Saved";
                                    }
                                    catch (Exception $e) {
                                        DB::rollback();
                                        $final_result = 0;
                                        $remarks = "Saving Failed";
                                        $error = $e->messages();
                                    }
                                }
                                else{
                                    $final_result = 0;
                                    $remarks = 'Not Certified';
                                }

                            }
                            else{
                                $final_result = 0; //Not Certified
                                $remarks = "Not Certified";
                            }

                            $final_result = 1; //Material Saved
                            $remarks = "Material Saved";
                        }
                        else{
                            $final_result = 0; //No device
                            $remarks = "No Device Found.";
                        }
                    }
                    else{
                        $final_result = 3; //No device
                        $remarks = "No Device Found.";
                    }
                }
                else{
                    $final_result = 0;
                    $remarks = 'Invalid Operator.';
                }

                // $final_result = 1;
            }
            else{
                $final_result = 0; //Invalid Operator
                $remarks = "Invalid Operator.";
            }

            // return response()->json(['final_result' => $final_result, 'mat_proc_materials' => $mat_proc_materials]);
        }
        else{
            $final_result = 0;
            $remarks = "Details not complete.";
        }

        return response()->json(['final_result' => $final_result, 'validator' => $validator->messages(), 'mat_proc_materials' => $mat_proc_materials, 'error' => $error, 'remarks' => $remarks, 'runcard_id' => $request->prod_runcard_id]);
    }

    // public function save_emboss_lot_issuance(Request $request){
    //     date_default_timezone_set('Asia/Manila');

    //     $data = $request->all();

    //     $final_result = 0;
    //     $remarks = "";
    //     $error = [];

    //     $validator = Validator::make($data, [
    //         // 'txt_prod_runcard_id_query' => ['required'],
    //         'issuance_id' => ['required'],
    //         'item' => ['required'],
    //         'item_desc' => ['required'],
    //         'operator' => ['required'],
    //         'prod_runcard_id' => ['required'],
    //         'device_name' => ['required'],
    //     ]);

    //     $mat_proc_materials = [];


    //     if($validator->passes()){
    //         $user = User::where('employee_id', $request->operator)->first();

    //         if($user != null){
    //             if($user->position == 4){
    //                 $mat_proc_materials = Device::with([
    //                     'material_process'  => function($query){
    //                         $query->where('status', 1);
    //                     },
    //                     'material_process.material_details' => function($query) use($request){
    //                         $query->where('status', 1);
    //                         $query->where('tbl_wbs', 2);
    //                         $query->where('item', $request->item);
    //                     },
    //                 ])
    //                 ->where('name', $request->device_name)
    //                 ->first();

    //                 // return $mat_proc_materials;
    //                 if($mat_proc_materials != null){
    //                     if(count($mat_proc_materials->material_process) > 0){
    //                         if($mat_proc_materials != null){
    //                             $col_mat_proc_materials = collect($mat_proc_materials)->flatten(1)->where('material_details', '!=', null);
    //                             $certified_mat_proc_id = $col_mat_proc_materials->pluck('id');
    //                         }

    //                         // return $certified_mat_proc_id;

    //                         if(count($certified_mat_proc_id) > 0){
    //                             $prod_runcard_station_details = ProductionRuncardStation::where('production_runcard_id', $request->prod_runcard_id)
    //                                 ->whereIn('mat_proc_id', $certified_mat_proc_id)
    //                                 ->get();

    //                             $prod_runcard_station_detail_ids = $prod_runcard_station_details->pluck('id');

    //                             // Save Material
    //                             if(count($prod_runcard_station_detail_ids) > 0){
    //                                 DB::beginTransaction();
    //                                 try {
    //                                     ProdRuncardMaterialList::insert([
    //                                         'prod_runcard_id' => $request->prod_runcard_id,
    //                                         'status' => 1,
    //                                         'issuance_id' => $request->issuance_id,
    //                                         'tbl_wbs' => 2,
    //                                         'for_emboss' => 1,
    //                                         'status' => 1,
    //                                     ]);

    //                                     for($index = 0; $index < count($prod_runcard_station_detail_ids); $index++){
    //                                         ProductionRuncardStationMaterial::insert([
    //                                             'production_runcard_id' => $request->prod_runcard_id,
    //                                             'production_runcard_station_id' => $prod_runcard_station_detail_ids[$index],
    //                                             'issuance_id' => $request->issuance_id,
    //                                             'item' => $request->item,
    //                                             'item_desc' => $request->item_desc,
    //                                             'tbl_wbs' => 2,
    //                                             'has_emboss' => 1,
    //                                             'status' => 1,
    //                                             'created_by' => $user->id,
    //                                             'last_updated_by' => $user->id,
    //                                             'update_version' => 1,
    //                                             'updated_at' => date('Y-m-d H:i:s'),
    //                                             'created_at' => date('Y-m-d H:i:s')
    //                                         ]);
    //                                     }

    //                                     DB::commit();
    //                                     $final_result = 1;
    //                                     $remarks = "Material Saved";
    //                                 }
    //                                 catch (Exception $e) {
    //                                     DB::rollback();
    //                                     $final_result = 0;
    //                                     $remarks = "Saving Failed";
    //                                     $error = $e->messages();
    //                                 }
    //                             }
    //                             else{
    //                                 $final_result = 0;
    //                                 $remarks = 'Not Certified';
    //                             }

    //                         }
    //                         else{
    //                             $final_result = 0; //Not Certified
    //                             $remarks = "Not Certified";
    //                         }

    //                         $final_result = 1; //Material Saved
    //                         $remarks = "Material Saved";
    //                     }
    //                     else{
    //                         $final_result = 0; //No device
    //                         $remarks = "No Device Found.";
    //                     }
    //                 }
    //                 else{
    //                     $final_result = 3; //No device
    //                     $remarks = "No Device Found.";
    //                 }
    //             }
    //             else{
    //                 $final_result = 0;
    //                 $remarks = 'Invalid Operator.';
    //             }

    //             // $final_result = 1;
    //         }
    //         else{
    //             $final_result = 0; //Invalid Operator
    //             $remarks = "Invalid Operator.";
    //         }

    //         // return response()->json(['final_result' => $final_result, 'mat_proc_materials' => $mat_proc_materials]);
    //     }
    //     else{
    //         $final_result = 0;
    //         $remarks = "Details not complete.";
    //     }

    //     return response()->json(['final_result' => $final_result, 'validator' => $validator->messages(), 'mat_proc_materials' => $mat_proc_materials, 'error' => $error, 'remarks' => $remarks, 'runcard_id' => $request->prod_runcard_id]);
    // }

    // ../USED IN MANUAL PRODUCTION RUNCARD

    public function view_ddrs(Request $request){
        date_default_timezone_set('Asia/Manila');

        $ddr = [];
        // $request->po_no = '450215777100010';
        // $request->user_id = '1';

        $now = Carbon::now();
        $timeNow = $now->format('H:i:s');

        $date_from = $request->date . ' 07:30:01';
        $date_to = $request->date . ' 19:30:00';

        if($request->shift == 1){ // Get Shift B
            $date_from = $request->date . ' 07:30:01';
            $date_to = $request->date . ' 19:30:00';
        }
        else{
            $date_from = Carbon::parse($request->date . ' 19:30:01');
            $date_to = Carbon::parse($request->date . ' 07:30:00')->addDays(1);
        }

        $ddr = ProductionRuncardStationMOD::with([
                    'production_runcard_station_details' => function($query) use($request, $date_from, $date_to){
                        $query->whereBetween('updated_at', [$date_from, $date_to]);
                    },
                    'production_runcard_station_details.sub_station' => function($query) use($request){
                        $query->where('id', $request->station);
                    },
                    'production_runcard_station_details.production_runcard_info' => function($query) use($request){
                        $query->where('po_no', $request->po_no);
                    },
                    'production_runcard_station_details.prod_runcard_station_operator_details' => function($query) use($request){
                        $query->where('operator_id', $request->user_id);
                    },
                    'mod_details'
                ])
                ->whereDate('created_at', $request->date)
                ->get();

        $col_ddr = collect($ddr)
                    ->where('production_runcard_station_details.sub_station', '!=', null)
                    ->where('production_runcard_station_details', '!=', '[]')
                    ->where('production_runcard_station_details.prod_runcard_station_operator_details', '!=', '[]')
                    ->where('production_runcard_station_details.production_runcard_info', '!=', null);

        return DataTables::of($col_ddr)
            ->addColumn('mat_ng_qty', function($row){
                $result = 0;

                if($row->type_of_ng == 1){
                    $result = $row->mod_qty;
                }

                return $result;
            })
            ->addColumn('prod_ng_qty', function($row){
                $result = 0;

                if($row->type_of_ng == 2){
                    $result = $row->mod_qty;
                }

                return $result;
            })
            // ->addColumn('raw_total_input', function($row) use($col_ddr){
            //     $result = 0;

            //     for($index = 0; $index < count($col_ddr); $index++){
            //         $result += $col_ddr[$index]->production_runcard_station_details->qty_input;
            //     }

            //     return $result;
            // })
            ->addColumn('raw_total_output', function($row) use($col_ddr){
                $total_input = 0;

                for($index = 0; $index < count($col_ddr); $index++){
                    $total_input += $col_ddr[$index]->production_runcard_station_details->qty_input;
                }

                return $total_input;
            })
            ->rawColumns([])
            ->make(true);
    }

    public function view_ophrs(Request $request){
        date_default_timezone_set('Asia/Manila');
        $now = Carbon::now();
        $timeNow = $now->format('H:i:s');

        // return $timeNow;
        $date_from = $request->date . ' 07:30:01';
        $date_to = $request->date . ' 19:30:00';

        if($request->shift == 1){ // Get Shift B
            $date_from = $request->date . ' 07:30:01';
            $date_to = $request->date . ' 19:30:00';
        }
        else{
            $date_from = Carbon::parse($request->date . ' 19:30:01');
            $date_to = Carbon::parse($request->date . ' 07:30:00')->addDays(1);
        }

        // return $date_from . ' - ' . $date_to;

        $ophrs = ProductionRuncardStation::where('qty_output', '!=', null)
                        ->with([
                            'prod_runcard_station_operator_details' => function($query) use ($request){
                                $query->where('operator_id', $request->user_id);
                            },
                            'sub_station',
                            'production_runcard_info',
                            'production_runcard_info.wbs_kitting',
                            'production_runcard_info.wbs_kitting',
                        ])
                        // ->whereDate('updated_at', $request->date)
                        // ->where('updated_at', '>=', $date_from)
                        // ->where('updated_at', '<=', $date_to)
                        ->whereBetween('updated_at', [$date_from, $date_to])
                        ->get();

        $col_ophrs = collect($ophrs)->flatten(1)->where('prod_runcard_station_operator_details', '!=', '[]');

        return DataTables::of($col_ophrs)
            ->addColumn('total_input', function($row) use ($col_ophrs){
                return $col_ophrs->sum('qty_input');
            })
            ->addColumn('raw_time', function($row) use ($col_ophrs){
                return Carbon::parse($row->updated_at)->isoFormat('LT');
            })
            ->rawColumns([])
            ->make(true);
    }

    public function check_material_no(Request $request){ // Change for live -JD
        date_default_timezone_set('Asia/Manila');

        /**
         * For the validation of the Scanned Material vs Matrix Material
         * -JD
         */
        $device = Device::with('process_flow')->where("name", $request->device_name)->get();
        $materialList = [];
        $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::where("process_flow_id", $request->process_flow_id)->get();
        // return $deviceProcessFlowMaterials;
        for ($i=0; $i < count($deviceProcessFlowMaterials); $i++) {
            if($deviceProcessFlowMaterials[$i]->material_part_no == $request->item_code){
                $materialList[] = $deviceProcessFlowMaterials[$i];
            }
        }
        // return $materialList;
        return response()->json(['data' => $materialList]);
    }

    public function check_material_no_test(Request $request){ // Change for live -JD
        date_default_timezone_set('Asia/Manila');
        $materialTypeChecking = "";
        $lotNumber="";

        /**
         * Material Issuance
         * Tables:
         * - tbl_wbs_material_kitting
         * - tbl_wbs_kit_issuance
         */
        $collectIssuanceNoByDeviceName = DB::connection('mysql_subsystem')->table('tbl_wbs_material_kitting')
            ->where('device_name', $request->device_name)
            ->get()
            ->flatten(1);
            // return $collectIssuanceNoByDeviceName;

        $issuance_numbers = [];
        for ($i=0; $i < count($collectIssuanceNoByDeviceName); $i++) {
            array_push($issuance_numbers, $collectIssuanceNoByDeviceName[$i]->issuance_no);
        }
        // return $issuance_numbers;

        $materialIssuanceList = DB::connection('mysql_subsystem')->table('tbl_wbs_kit_issuance')
            ->whereIn('issue_no', $issuance_numbers)
            ->where('po', $request->po_number)
            ->where('lot_no', $request->lot_no)
            ->where('item', $request->item_code) // added by nessa 01-28-2023
            ->get();
            // return $materialIssuanceList;

        /**
         * Sakidashi
         * Tables:
         * - tbl_wbs_sakidashi_issuance
         * - tbl_wbs_sakidashi_issuance_item
         */
        $sakidashiList = [];
        $tbl_wbs_sakidashi_issuances = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')->distinct()
        ->where('device_name', $request->device_name)
        ->where('po_no', $request->po_number)
        ->get();
        // return $tbl_wbs_sakidashi_issuances;

        for ($i=0; $i < count($tbl_wbs_sakidashi_issuances); $i++) {
            if($tbl_wbs_sakidashi_issuances[$i]->tbl_wbs_sakidashi_issuance_item->lot_no == $request->lot_no){
                $sakidashiList[] = $tbl_wbs_sakidashi_issuances[$i];
            }
        }

        if(count($materialIssuanceList) > 0){
            $materialTypeChecking = $materialIssuanceList[0]->item;
            $lotNumber = $materialIssuanceList[0]->lot_no;
        }else{
            $materialTypeChecking = $sakidashiList[0]->tbl_wbs_sakidashi_issuance_item->item;
            $lotNumber = $sakidashiList[0]->tbl_wbs_sakidashi_issuance_item->lot_no;
        }

        /**
         * For the validation of the Filtered Material on WBS vs Matrix Material
         * -JD
         */
        $materialList = [];
        $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::where("process_flow_id", $request->process_flow_id)->get();
        // return $deviceProcessFlowMaterials;
        for ($i=0; $i < count($deviceProcessFlowMaterials); $i++) {
            if($deviceProcessFlowMaterials[$i]->material_part_no == $materialTypeChecking){
                $materialList[] = $deviceProcessFlowMaterials[$i];
            }
        }
        // return $materialList;

        return response()->json(['data' => $materialList, "lot_no" => $lotNumber]);
    }

    public function check_material_no_LIVE(Request $request){ // LIVE
        date_default_timezone_set('Asia/Manila');
        $materialTypeChecking = "";
        $lotNumber="";

        /**
         * Material Issuance
         * Tables:
         * - tbl_wbs_material_kitting
         * - tbl_wbs_kit_issuance
         */
        $collectIssuanceNoByDeviceName = DB::connection('mysql_subsystem')->table('tbl_wbs_material_kitting')
            ->where('device_name', $request->device_name)
            ->get()
            ->flatten(1);
            // return $collectIssuanceNoByDeviceName;

        $issuance_numbers = [];
        for ($i=0; $i < count($collectIssuanceNoByDeviceName); $i++) {
            array_push($issuance_numbers, $collectIssuanceNoByDeviceName[$i]->issuance_no);
        }
        // return $issuance_numbers;

        $materialIssuanceList = DB::connection('mysql_subsystem')->table('tbl_wbs_kit_issuance')
            ->whereIn('issue_no', $issuance_numbers)
            ->where('po', $request->po_number)
            ->where('lot_no', $request->lot_no)
            ->where('item', $request->item_code) // added by nessa 01-28-2023
            ->get();
            // return $materialIssuanceList;

        /**
         * Sakidashi
         * Tables:
         * - tbl_wbs_sakidashi_issuance
         * - tbl_wbs_sakidashi_issuance_item
         */
        $sakidashiList = [];
        $tbl_wbs_sakidashi_issuances = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')->distinct()
        ->where('device_name', $request->device_name)
        ->where('po_no', $request->po_number)
        ->get();
        // echo 'tbl_wbs_sakidashi_issuances ';
        // echo "\r\n";
        // echo $request->po_number;
        // echo "\r\n";
        // echo $request->device_name;
        // echo "\r\n";
        // return $tbl_wbs_sakidashi_issuances;
        // return $request->lot_no;

        for ($i=0; $i < count($tbl_wbs_sakidashi_issuances); $i++) {


            $id_from_tbl_wbs_sakidashikit_issuance = $tbl_wbs_sakidashi_issuances[$i];
            // return $id_from_tbl_wbs_sakidashikit_issuance->id;
            $parts_preps = PartsPrep::where([
                [ 'wbs_kit_issuance_id', '=', $id_from_tbl_wbs_sakidashikit_issuance->id ],
                // [ 'wbs_table', '=', 1 ],
                [ 'status', '=', 1 ], // 1-received
                [ 'deleted_at', '=', null ],
            ])
            ->get();
            // return $parts_preps;
            if(count($parts_preps) > 0){
                if($tbl_wbs_sakidashi_issuances[$i]->tbl_wbs_sakidashi_issuance_item->lot_no == $request->lot_no){
                    array_push($sakidashiList, $tbl_wbs_sakidashi_issuances[$i]);
                }
            }
        }
        // return $sakidashiList;

        if(count($materialIssuanceList) > 0){
            $materialTypeChecking = $materialIssuanceList[0]->item;
            $lotNumber = $materialIssuanceList[0]->lot_no;
        }

        if(count($sakidashiList) > 0){
            $materialTypeChecking = $sakidashiList[0]->tbl_wbs_sakidashi_issuance_item->item;
            $lotNumber = $sakidashiList[0]->tbl_wbs_sakidashi_issuance_item->lot_no;
        }

        /**
         * For the validation of the Filtered Material on WBS vs Matrix Material
         * -JD
         */
        // return $materialTypeChecking; //MP260624 | 1351-12064 | 4/4 A10000002
        //  return $lotNumber;
        $materialList = [];
        $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::where("process_flow_id", $request->process_flow_id)->get();

        // return $deviceProcessFlowMaterials;
        for ($i=0; $i < count($deviceProcessFlowMaterials); $i++) {
            if($deviceProcessFlowMaterials[$i]->material_part_no == $materialTypeChecking){ // NEED TO HAVE SAME Material Number
                $materialList[] = $deviceProcessFlowMaterials[$i];
            }
        }
        // return $deviceProcessFlowMaterials[]->material_part_no;

        return response()->json(['data' => $materialList, "lot_no" => $lotNumber]);
    }

    public function check_material_no_defect(Request $request){ // LIVE For defect escalation
        date_default_timezone_set('Asia/Manila');
        $materialTypeChecking = "";
        $lotNumber="";

        /**
         * Material Issuance
         * Tables:
         * - tbl_wbs_material_kitting
         * - tbl_wbs_kit_issuance
         */
        $collectIssuanceNoByDeviceName = DB::connection('mysql_subsystem')->table('tbl_wbs_material_kitting')
            ->where('device_name', $request->device_name)
            ->get()
            ->flatten(1);
            // return $collectIssuanceNoByDeviceName;

        $issuance_numbers = [];
        for ($i=0; $i < count($collectIssuanceNoByDeviceName); $i++) {
            array_push($issuance_numbers, $collectIssuanceNoByDeviceName[$i]->issuance_no);
        }
        // return $issuance_numbers;

        $materialIssuanceList = DB::connection('mysql_subsystem')->table('tbl_wbs_kit_issuance')
            ->whereIn('issue_no', $issuance_numbers)
            ->where('po', $request->po_number)
            ->where('lot_no', $request->lot_no)
            ->where('item', $request->item_code) // added by nessa 01-28-2023
            ->get();
            // return $materialIssuanceList;

        /**
         * Sakidashi
         * Tables:
         * - tbl_wbs_sakidashi_issuance
         * - tbl_wbs_sakidashi_issuance_item
         */
        $sakidashiList = [];
        $tbl_wbs_sakidashi_issuances = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')->distinct()
        ->where('device_name', $request->device_name)
        ->where('po_no', $request->po_number)
        ->get();
        // echo 'tbl_wbs_sakidashi_issuances ';
        // echo "\r\n";
        // echo $request->po_number;
        // echo "\r\n";
        // echo $request->device_name;
        // echo "\r\n";
        // return $tbl_wbs_sakidashi_issuances;
        // return $request->lot_no;

        for ($i=0; $i < count($tbl_wbs_sakidashi_issuances); $i++) {


            $id_from_tbl_wbs_sakidashikit_issuance = $tbl_wbs_sakidashi_issuances[$i];
            // return $id_from_tbl_wbs_sakidashikit_issuance->id;
            $parts_preps = PartsPrep::where([
                [ 'wbs_kit_issuance_id', '=', $id_from_tbl_wbs_sakidashikit_issuance->id ],
                // [ 'wbs_table', '=', 1 ],
                [ 'status', '=', 1 ], // 1-received
                [ 'deleted_at', '=', null ],
            ])
            ->get();
            // return $parts_preps;
            if(count($parts_preps) > 0){
                if($tbl_wbs_sakidashi_issuances[$i]->tbl_wbs_sakidashi_issuance_item->lot_no == $request->lot_no){
                    array_push($sakidashiList, $tbl_wbs_sakidashi_issuances[$i]);
                }
            }
        }
        // return $sakidashiList;

        if(count($materialIssuanceList) > 0){
            $materialTypeChecking = $materialIssuanceList[0]->item;
            $lotNumber = $materialIssuanceList[0]->lot_no;
        }

        if(count($sakidashiList) > 0){
            $materialTypeChecking = $sakidashiList[0]->tbl_wbs_sakidashi_issuance_item->item;
            $lotNumber = $sakidashiList[0]->tbl_wbs_sakidashi_issuance_item->lot_no;
        }

        /**
         * For the validation of the Filtered Material on WBS vs Matrix Material
         * -JD
         */
        // return $materialTypeChecking;
        // return $lotNumber;
        $materialList = [];
        $devices = CommonController::getInstance()->getMatrixByPoNo(['po_no' => '', 'name' => $request->device_name, 'barcode' => '', 'get_or_first' => '']);
        // $devices = Device::where("name", $request->device_name)->get();
        // return $devices;
        $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::where("device_id", $devices[0]->id)->get();
        // $deviceProcessFlowMaterials = DeviceProcessFlowMaterial::where("status", 1)->get();
        // return $deviceProcessFlowMaterials;
        for ($i=0; $i < count($deviceProcessFlowMaterials); $i++) {
            if($deviceProcessFlowMaterials[$i]->material_part_no == $materialTypeChecking){
                $materialList[] = $deviceProcessFlowMaterials[$i];
            }
        }
        // return $materialList;

        return response()->json(['data' => $materialList, "lot_no" => $lotNumber]);
    }

    public function delete_runcard_station(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        if($request->ajax()){
            if(Auth::check()){
                $data = $request->all();

                $rules = [
                    'employee_no' => 'required',
                    'runcard_station_id' => 'required',
                ];

                $validator = Validator::make($data, $rules);

                try {
                    if($validator->passes()){
                        DB::beginTransaction();
                        try {

                            $user = User::where('employee_id', $request->employee_no)->where('status', 1)->get();

                            if($user->count() > 0){
                                ProductionRuncardStation::where('id', $request->runcard_station_id)
                                    ->where('status', 1)
                                    ->update([
                                        'status' => 2,
                                        'last_updated_by' => Auth::user()->id,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ]);

                                $runcard_station_info = ProductionRuncardStation::where('id', $request->runcard_station_id)->first();

                                // return response()->json(['auth' => 1, 'result' => 0, 'error' => null, 'runcard_station_info' => $runcard_station_info]);

                                if($runcard_station_info != null){
                                    $runcard_stations = ProductionRuncardStation::where('step_num', '>', $runcard_station_info->step_num)
                                    ->where('status', 1)
                                    ->where('production_runcard_id', $runcard_station_info->production_runcard_id)
                                    ->get();

                                    // return response()->json(['auth' => 1, 'result' => 0, 'error' => null, 'runcard_stations' => $runcard_stations]);

                                    for($index = 0; $index < count($runcard_stations); $index++){
                                        ProductionRuncardStation::where('id', $runcard_stations[$index]->id)
                                            ->where('status', 1)
                                            ->decrement('step_num', 1);
                                    }
                                }
                                else{
                                    DB::rollback();
                                    return response()->json(['auth' => 1, 'result' => 3, 'error' => null]);
                                }


                                DB::commit();
                                return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
                            }
                            else{
                                DB::rollback();
                                return response()->json(['auth' => 1, 'result' => 2, 'error' => null]);
                            }

                        } catch (Exception $e) {
                            DB::rollback();
                            return response()->json(['auth' => 1, 'result' => 0, 'error' => null]);
                        }
                    }
                    else{
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                    }
                }
                catch(\Exception $e) {
                    return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                }
            }
            else{
                return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
            }
        }
        else{
            abort(403);
        }
    }

    public function generate_prod_runcard_qrcode(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = ProductionRuncard::where('id', $request->runcard_id)->first();


        $po_qrcode = "";
        $whs_slip_qrcode = "";
        $product_name = "";

        if($data->count() > 0){
                $po_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($data->po_no);

                $runcard_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($data->runcard_no);

            $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);

            $runcard_qrcode = "data:image/png;base64," . base64_encode($runcard_qrcode);


        }
        $product_name = MaterialIssuanceSubSystem::where('po_no', $data->po_no)->get();

        $device_name_print = 'not found';
        if(isset($product_name[0])){
            $device_name_print = $product_name[0]->device_name;
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
        }

        return response()->json(['data' => $data, 'po_qrcode' => $po_qrcode, 'runcard_qrcode' => $runcard_qrcode, 'device_name_print' => $device_name_print]);
    }

    public function generate_auto_sticker(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = ProductionRuncardStation::with(['production_runcard_info','prod_runcard_station_material_details','prod_runcard_station_machine_details.machine_info'])->where('id', $request->runcardStationId)->first();

        $product_name = "";
        $product_name = MaterialIssuanceSubSystem::where('po_no', $data['production_runcard_info']->po_no)->get();

        $device_name_print = 'not found';
        if(isset($product_name[0])){
            $device_name_print = $product_name[0]->device_name;
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
        }

        $lbl = 'Series Name: <i><b>' . $device_name_print. '</i></b><br>PO Number: <i><b>' . $data->production_runcard_info->po_no . '</i></b><br>CT Name: <i><b>' . $data->prod_runcard_station_material_details[0]->material_name . '</i></b><br>CT Lot Number: <i><b>' . $data->prod_runcard_station_material_details[0]->lot_no . '</i></b><br>Pair Number: <i><b>' . $data->production_runcard_info->pair_no . '</i></b><br>Machine Number: <i><b>' . $data->prod_runcard_station_machine_details[0]['machine_info']->machine_name.'</i></b>';


        return response()->json(['data' => $data, 'device_name_print' => $device_name_print, 'lbl' => $lbl]);
    }
    public function get_submitted_prod_runcard_details(Request $request){

        $is_device_with_overall =  Device::where('name',$request->txt_use_for_device)->where('process',1)->limit(1)->count();

        if($is_device_with_overall == 1){
            return response(['is_prod_runcard' => 'true']);
        }else{
            $count_prod_runcard = ProductionRuncard::where('runcard_no', $request->txt_defect_rework_number_scanner)
                                                    ->whereIn('status',[3,4])->limit(1)->count();
            $is_prod_runcard = ($count_prod_runcard === 1) ? 'true' : 'false';
            return response(['is_prod_runcard' => $is_prod_runcard]);
        }

    }
}

