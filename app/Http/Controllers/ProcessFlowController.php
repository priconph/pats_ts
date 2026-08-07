<?php

namespace App\Http\Controllers;

use App\Model\Device;
use App\Model\ProcessFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVProcessFlowImport;
use App\Imports\MultiSheetProcessFlowImport;

use App\Model\MaterialIssuanceDetails;
use App\Model\WBSKitIssuance;
use App\Model\Material;
use App\Model\Machine;
use App\Model\SubStation;
use App\Model\DeviceProcessFlowMaterial;
use App\User;

class ProcessFlowController extends Controller
{
    public function generateStepNumber(Request $request)
    {
        return response()->json([
            'count' => ProcessFlow::where('device_id', $request->deviceId)->get()->count() + 1
        ]);
    }

    /*
    [
        {"id":1,"process_flow_id":12,"device_id":4,"sub_station_id":11,"material_id":253422,"material_name":"NP613-180-001#IN-B (YUTAKA)","lot_no":"A191211","material_type":"1","status":1,"is_deleted":0,"created_by":1,"last_updated_by":1,"created_at":"2022-08-11 10:24:19","updated_at":"2022-08-11 10:24:19"},

        {"id":2,"process_flow_id":12,"device_id":4,"sub_station_id":11,"material_id":253445,"material_name":"NP613-180-001#MO (YUTAKA)","lot_no":"A191204","material_type":"1","status":1,"is_deleted":0,"created_by":1,"last_updated_by":1,"created_at":"2022-08-11 10:24:19","updated_at":"2022-08-11 10:24:19"},

        {"id":3,"process_flow_id":12,"device_id":4,"sub_station_id":11,"material_id":294465,"material_name":"NP613-180-001#IN-A","lot_no":"200219","material_type":"1","status":1,"is_deleted":0,"created_by":1,"last_updated_by":1,"created_at":"2022-08-11 10:24:19","updated_at":"2022-08-11 10:24:19"},

        {"id":4,"process_flow_id":12,"device_id":4,"sub_station_id":11,"material_id":521270,"material_name":"NP613-001#CV-2 (YUTAKA)","lot_no":"B200908","material_type":"1","status":1,"is_deleted":0,"created_by":1,"last_updated_by":1,"created_at":"2022-08-11 10:24:19","updated_at":"2022-08-11 10:24:19"}
    ]
    */

    /**
     * Old
     * 01-09-2023
     */
    // public function view_process_flow_by_device_id(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     $data = ProcessFlow::with(['process_flow_substation_details'])
    //         ->where('device_id', $request->device_id)
    //         ->where('status', $request->status)
    //         ->get();

    //     return DataTables::of($data)
    //         // Start 2022 06 15
    //         ->addColumn('processInfo', function($row){
    //             $result = "---";
    //             $subStation = SubStation::where('id', $row->process)->get();
    //             if( count($subStation) > 0 )
    //                 $result = $subStation[0]->name;
    //             return $result;
    //         })
    //         ->addColumn('materials', function($row){
    //             $result = "";
    //             if ( count($row->process_flow_substation_details) > 0){
    //                 // $materialArray = [];
    //                 for ($i=0; $i < count($row->process_flow_substation_details); $i++) {
    //                     // array_push($materialArray, $row->process_flow_substation_details[$i]->material_name);
    //                     $result .= '<span class="badge badge-pill badge-success">' . $row->process_flow_substation_details[$i]->material_name . '</span>';
    //                     if($i < count($row->process_flow_substation_details)-1){
    //                         $result .= '<br>';
    //                     }
    //                 }
    //                 // $result = '<span class="badge badge-pill badge-success">' . $row->process_flow_substation_details[0]->material_name . '</span>';

    //             }else{
    //                 $result = '<span class="badge badge-pill badge-secondary">N/A</span>';
    //             }
    //             return $result;


    //             // Old 08-11-2022
    //             // // $result = "";
    //             // // if ( $row->material_name == 0){
    //             // //     $result .= '<span class="badge badge-pill badge-warning">N/A</span>';
    //             // // }else{
    //             //     $result = '<span class="badge badge-pill badge-success">' . $row->material_name . '</span>';
    //             // // }

    //             // // $material_ids = explode(',', $row->material_id);
    //             // // // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             // // $materials = Material::whereIn('id', $material_ids)->get();
    //             // // for ($i=0; $i < count($materials); $i++){
    //             // //     $result .= '<span class="badge badge-pill badge-success">' . $materials[$i]->name . '</span>';
    //             // //     if($i < count($materials)-1)
    //             // //         $result .= '<br>';
    //             // // }
    //             return $result;
    //         })
    //         ->addColumn('machines', function($row){
    //             $result = "";
    //             $machine_ids = explode(',', $row->machine_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             // $machines = Machine::whereIn('id', $machine_ids)->get();
    //             $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
    //                 ->whereIn('id', $machine_ids)
    //                 ->get();
    //             for ($i=0; $i < count($machines); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $machines[$i]->machine_name . '</span>';
    //                 if($i < count($machines)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         ->addColumn('a_shift', function($row){
    //             $result = "";
    //             $user_ids = explode(',', $row->a_shift_user_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             $users = User::whereIn('id', $user_ids)->get();
    //             for ($i=0; $i < count($users); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $users[$i]->name . '</span>';
    //                 if($i < count($users)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         ->addColumn('b_shift', function($row){
    //             $result = "";
    //             $user_ids = explode(',', $row->b_shift_user_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             $users = User::whereIn('id', $user_ids)->get();
    //             for ($i=0; $i < count($users); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $users[$i]->name . '</span>';
    //                 if($i < count($users)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         // End 2022 06 15
    //         ->addColumn('label1', function($row){
    //             $result = "";

    //             if($row->status == 1){
    //                 $result .= '<span class="badge badge-pill badge-success">Active</span>';
    //             }
    //             else{
    //                 $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('action1', function($row){
    //             $btn_color = 'btn-primary';
    //             if($row->status == 2){
    //                 $btn_color = 'btn-danger';
    //             }

    //             $result = '<center><div class="btn-group">
    //                       <button type="button" class="btn ' . $btn_color . ' dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
    //                         <i class="fa fa-cog"></i>
    //                       </button>
    //                       <div class="dropdown-menu dropdown-menu-right">';
    //             if($row->status == 1){
    //                 $result .= '<button class="dropdown-item aEditMatProc" type="button" material-process-id="' . $row->id . '" style="padding: 1px 1px; text-align: center;" data-keyboard="false">Edit</button>';

    //                 $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" material-process-id="' . $row->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Deactivate</button>';
    //             }
    //             else{
    //                 $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" style="padding: 1px 1px; text-align: center;" material-process-id="' . $row->id . '" status="1" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Activate</button>';
    //             }

    //             $result .= '</div>
    //                     </div></center>';

    //             return $result;
    //         })
    //         ->addColumn('chk_for_prod', function($row){
    //             $result = "";

    //             // if($row->for_prod == 1){
    //             //     $result .= '<center><input type="checkbox" value"1" process-flow-id="' . $row->id . '" class="chkProcessFlowForProd" checked></center>';
    //             // }
    //             // else{
    //             //     $result .= '<center><input type="checkbox" value"1" process-flow-id="' . $row->id . '" class="chkProcessFlowForProd"></center>';
    //             // }

    //             if($row->for_prod == 1){
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1" selected="true"> Production </option>';
	//                   $result .= '<option value="2"> Parts Preparatory </option>';
	//                   $result .= '<option value="0"> Others </option>';
	//                 $result .= '</select>';
	//             }
	//             else if($row->for_prod == 2){
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1"> Production </option>';
	//                   $result .= '<option value="2" selected="true"> Parts Preparatory </option>';
	//                   $result .= '<option value="0"> Others </option>';
	//                 $result .= '</select>';
	//             }
	//             else{
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1"> Production </option>';
	//                   $result .= '<option value="2"> Parts Preparatory </option>';
	//                   $result .= '<option value="0" selected="true"> Others </option>';
	//                 $result .= '</select>';
	//             }



    //             return $result;
    //         })
    //         ->rawColumns(['label1', 'action1', 'chk_for_prod', 'materials', 'machines', 'a_shift', 'b_shift'])
    //         ->make(true);
    // }

    /**
     * Old Code
     * 01-16-2023
     * -JD
     */
    // public function view_process_flow_by_device_id(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();

    //     $data = ProcessFlow::with(['process_flow_substation_details'])
    //         ->where('device_id', $request->device_id)
    //         ->where('status', $request->status)
    //         ->get();

    //     return DataTables::of($data)
    //         // Start 2022 06 15
    //         ->addColumn('processInfo', function($row){
    //             $result = "---";
    //             $subStation = SubStation::where('id', $row->process)->get();
    //             if( count($subStation) > 0 )
    //                 $result = $subStation[0]->name;
    //             return $result;
    //         })
    //         ->addColumn('materials', function($row){
    //             $result = "";
    //             if ( count($row->process_flow_substation_details) > 0){
    //                 // $materialArray = [];
    //                 for ($i=0; $i < count($row->process_flow_substation_details); $i++) {
    //                     // array_push($materialArray, $row->process_flow_substation_details[$i]->material_name);
    //                     $result .= '<span class="badge badge-pill badge-success">' . $row->process_flow_substation_details[$i]->material_name . '</span>';
    //                     if($i < count($row->process_flow_substation_details)-1){
    //                         $result .= '<br>';
    //                     }
    //                 }
    //                 // $result = '<span class="badge badge-pill badge-success">' . $row->process_flow_substation_details[0]->material_name . '</span>';

    //             }else{
    //                 $result = '<span class="badge badge-pill badge-secondary">N/A</span>';
    //             }
    //             return $result;


    //             // Old 08-11-2022
    //             // // $result = "";
    //             // // if ( $row->material_name == 0){
    //             // //     $result .= '<span class="badge badge-pill badge-warning">N/A</span>';
    //             // // }else{
    //             //     $result = '<span class="badge badge-pill badge-success">' . $row->material_name . '</span>';
    //             // // }

    //             // // $material_ids = explode(',', $row->material_id);
    //             // // // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             // // $materials = Material::whereIn('id', $material_ids)->get();
    //             // // for ($i=0; $i < count($materials); $i++){
    //             // //     $result .= '<span class="badge badge-pill badge-success">' . $materials[$i]->name . '</span>';
    //             // //     if($i < count($materials)-1)
    //             // //         $result .= '<br>';
    //             // // }
    //             return $result;
    //         })
    //         ->addColumn('approval_status', function($row){
    //             $result = "";
    //             // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
    //             if($row->approval_status == 1){
    //                 if(in_array($_SESSION["position"], [1,12])){ // 1-Prod Supervisor, 2-QC Supervisor, 9-Engineer, 12-Admin
    //                     $result .=  '<center>';
    //                     $result .=      '<div class="btn-group">';
    //                     $result .=            '<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
    //                     $result .=                '<i class="fa fa-cog"></i>';
    //                     $result .=            '</button>';
    //                     $result .=          '<div class="dropdown-menu dropdown-menu-right">';
    //                     $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Approved</button>';
    //                     $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="4" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Disapproved</button>';
    //                     $result .=          '</div>';
    //                     $result .=      '</div>';
    //                     $result .=  '</center>';
    //                 }
    //                 $result .= '<span class="badge badge-pill badge-info">For Prod Supervisor Approval</span>';
    //             }else if($row->approval_status == 2){
    //                 if(in_array($_SESSION["position"], [2,12])){ // 1-Prod Supervisor, 2-QC Supervisor, 9-Engineer, 12-Admin
    //                     $result .=  '<center>';
    //                     $result .=      '<div class="btn-group">';
    //                     $result .=            '<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
    //                     $result .=                '<i class="fa fa-cog"></i>';
    //                     $result .=            '</button>';
    //                     $result .=          '<div class="dropdown-menu dropdown-menu-right">';
    //                     $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="3" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Approved</button>';
    //                     $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="5" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Disapproved</button>';
    //                     $result .=          '</div>';
    //                     $result .=      '</div>';
    //                     $result .=  '</center>';
    //                 }
    //                 $result .= '<span class="badge badge-pill badge-info">For QC Approval</span>';
    //             }else if($row->approval_status == 3){
    //                 $result .= '<span class="badge badge-pill badge-success">Approved by QC</span>';
    //             }else if($row->approval_status == 4){
    //                 $result .= '<span class="badge badge-pill badge-danger">Disapproved by Production Supervisor</span>';
    //             }else if($row->approval_status == 5){
    //                 $result .= '<span class="badge badge-pill badge-danger">Disapproved by QC Supervisor</span>';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('machines', function($row){
    //             $result = "";
    //             $machine_ids = explode(',', $row->machine_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             // $machines = Machine::whereIn('id', $machine_ids)->get();
    //             $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
    //                 ->whereIn('id', $machine_ids)
    //                 ->get();
    //             for ($i=0; $i < count($machines); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $machines[$i]->machine_name . '</span>';
    //                 if($i < count($machines)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         ->addColumn('a_shift', function($row){
    //             $result = "";
    //             $user_ids = explode(',', $row->a_shift_user_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             $users = User::whereIn('id', $user_ids)->get();
    //             for ($i=0; $i < count($users); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $users[$i]->name . '</span>';
    //                 if($i < count($users)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         ->addColumn('b_shift', function($row){
    //             $result = "";
    //             $user_ids = explode(',', $row->b_shift_user_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             $users = User::whereIn('id', $user_ids)->get();
    //             for ($i=0; $i < count($users); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $users[$i]->name . '</span>';
    //                 if($i < count($users)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         // End 2022 06 15
    //         ->addColumn('label1', function($row){
    //             $result = "";

    //             if($row->status == 1){
    //                 $result .= '<span class="badge badge-pill badge-success">Active</span>';
    //             }
    //             else{
    //                 $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('action1', function($row){
    //             $btn_color = 'btn-primary';
    //             if($row->status == 2){
    //                 $btn_color = 'btn-danger';
    //             }

    //             $result = '<center><div class="btn-group">
    //                       <button type="button" class="btn ' . $btn_color . ' dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
    //                         <i class="fa fa-cog"></i>
    //                       </button>
    //                       <div class="dropdown-menu dropdown-menu-right">';
    //             if($row->status == 1){
    //                 $result .= '<button class="dropdown-item aEditMatProc" type="button" material-process-id="' . $row->id . '" style="padding: 1px 1px; text-align: center;" data-keyboard="false">Edit</button>';

    //                 $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" material-process-id="' . $row->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Deactivate</button>';
    //             }
    //             else{
    //                 $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" style="padding: 1px 1px; text-align: center;" material-process-id="' . $row->id . '" status="1" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Activate</button>';
    //             }

    //             $result .= '</div>
    //                     </div></center>';

    //             return $result;
    //         })
    //         ->addColumn('chk_for_prod', function($row){
    //             $result = "";

    //             // if($row->for_prod == 1){
    //             //     $result .= '<center><input type="checkbox" value"1" process-flow-id="' . $row->id . '" class="chkProcessFlowForProd" checked></center>';
    //             // }
    //             // else{
    //             //     $result .= '<center><input type="checkbox" value"1" process-flow-id="' . $row->id . '" class="chkProcessFlowForProd"></center>';
    //             // }

    //             if($row->for_prod == 1){
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1" selected="true"> Production </option>';
	//                   $result .= '<option value="2"> Parts Preparatory </option>';
	//                   $result .= '<option value="0"> Others </option>';
	//                 $result .= '</select>';
	//             }
	//             else if($row->for_prod == 2){
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1"> Production </option>';
	//                   $result .= '<option value="2" selected="true"> Parts Preparatory </option>';
	//                   $result .= '<option value="0"> Others </option>';
	//                 $result .= '</select>';
	//             }
	//             else{
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1"> Production </option>';
	//                   $result .= '<option value="2"> Parts Preparatory </option>';
	//                   $result .= '<option value="0" selected="true"> Others </option>';
	//                 $result .= '</select>';
	//             }



    //             return $result;
    //         })
    //         ->rawColumns(['label1', 'action1', 'chk_for_prod', 'materials', 'machines', 'a_shift', 'b_shift', 'approval_status'])
    //         ->make(true);
    // }

    // public function view_process_flow_by_device_id(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();

    //     $data = ProcessFlow::with(['process_flow_substation_details'])
    //         ->where('device_id', $request->device_id)
    //         ->where('status', $request->status)
    //         ->get();

    //     return DataTables::of($data)
    //         // Start 2022 06 15
    //         ->addColumn('processInfo', function($row){
    //             $result = "---";
    //             $subStation = SubStation::where('id', $row->process)->get();
    //             if( count($subStation) > 0 )
    //                 $result = $subStation[0]->name;
    //             return $result;
    //         })
    //         ->addColumn('materials', function($row){
    //             $result = "";
    //             if ( count($row->process_flow_substation_details) > 0){
    //                 // $materialArray = [];
    //                 for ($i=0; $i < count($row->process_flow_substation_details); $i++) {
    //                     // array_push($materialArray, $row->process_flow_substation_details[$i]->material_name);
    //                     $result .= '<span class="badge badge-pill badge-success">' . $row->process_flow_substation_details[$i]->material_name . '</span>';
    //                     if($i < count($row->process_flow_substation_details)-1){
    //                         $result .= '<br>';
    //                     }
    //                 }
    //                 // $result = '<span class="badge badge-pill badge-success">' . $row->process_flow_substation_details[0]->material_name . '</span>';

    //             }else{
    //                 $result = '<span class="badge badge-pill badge-secondary">N/A</span>';
    //             }
    //             return $result;


    //             // Old 08-11-2022
    //             // // $result = "";
    //             // // if ( $row->material_name == 0){
    //             // //     $result .= '<span class="badge badge-pill badge-warning">N/A</span>';
    //             // // }else{
    //             //     $result = '<span class="badge badge-pill badge-success">' . $row->material_name . '</span>';
    //             // // }

    //             // // $material_ids = explode(',', $row->material_id);
    //             // // // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             // // $materials = Material::whereIn('id', $material_ids)->get();
    //             // // for ($i=0; $i < count($materials); $i++){
    //             // //     $result .= '<span class="badge badge-pill badge-success">' . $materials[$i]->name . '</span>';
    //             // //     if($i < count($materials)-1)
    //             // //         $result .= '<br>';
    //             // // }
    //             return $result;
    //         })
    //         ->addColumn('approval_status', function($row){
    //             $result = "";
    //             // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
    //             if($row->approval_status == 1){
    //                 if(in_array($_SESSION["position"], [1,12])){ // 1-Prod Supervisor, 2-QC Supervisor, 9-Engineer, 12-Admin
    //                     $result .=  '<center>';
    //                     $result .=      '<div class="btn-group">';
    //                     $result .=            '<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
    //                     $result .=                '<i class="fa fa-cog"></i>';
    //                     $result .=            '</button>';
    //                     $result .=          '<div class="dropdown-menu dropdown-menu-right">';
    //                     $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Approved</button>';
    //                     $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="4" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Disapproved</button>';
    //                     $result .=          '</div>';
    //                     $result .=      '</div>';
    //                     $result .=  '</center>';
    //                 }
    //                 $result .= '<span class="badge badge-pill badge-info">For Prod Supervisor Approval</span>';
    //             }else if($row->approval_status == 2){
    //                 if(in_array($_SESSION["position"], [2,12])){ // 1-Prod Supervisor, 2-QC Supervisor, 9-Engineer, 12-Admin
    //                     $result .=  '<center>';
    //                     $result .=      '<div class="btn-group">';
    //                     $result .=            '<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
    //                     $result .=                '<i class="fa fa-cog"></i>';
    //                     $result .=            '</button>';
    //                     $result .=          '<div class="dropdown-menu dropdown-menu-right">';
    //                     $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="3" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Approved</button>';
    //                     $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="5" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Disapproved</button>';
    //                     $result .=          '</div>';
    //                     $result .=      '</div>';
    //                     $result .=  '</center>';
    //                 }
    //                 $result .= '<span class="badge badge-pill badge-info">For QC Approval</span>';
    //             }else if($row->approval_status == 3){
    //                 $result .= '<span class="badge badge-pill badge-success">Approved by QC</span>';
    //             }else if($row->approval_status == 4){
    //                 $result .= '<span class="badge badge-pill badge-danger">Disapproved by Production Supervisor</span>';
    //             }else if($row->approval_status == 5){
    //                 $result .= '<span class="badge badge-pill badge-danger">Disapproved by QC Supervisor</span>';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('machines', function($row){
    //             $result = "";
    //             $machine_ids = explode(',', $row->machine_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             // $machines = Machine::whereIn('id', $machine_ids)->get();
    //             $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
    //                 ->whereIn('id', $machine_ids)
    //                 ->get();
    //             for ($i=0; $i < count($machines); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $machines[$i]->machine_name . '</span>';
    //                 if($i < count($machines)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         ->addColumn('a_shift', function($row){
    //             $result = "";
    //             $user_ids = explode(',', $row->a_shift_user_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             $users = User::whereIn('id', $user_ids)->get();
    //             for ($i=0; $i < count($users); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $users[$i]->name . '</span>';
    //                 if($i < count($users)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         ->addColumn('b_shift', function($row){
    //             $result = "";
    //             $user_ids = explode(',', $row->b_shift_user_id);
    //             // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
    //             $users = User::whereIn('id', $user_ids)->get();
    //             for ($i=0; $i < count($users); $i++){
    //                 $result .= '<span class="badge badge-pill badge-success">' . $users[$i]->name . '</span>';
    //                 if($i < count($users)-1)
    //                     $result .= '<br>';
    //             }
    //             return $result;
    //         })
    //         // End 2022 06 15
    //         ->addColumn('label1', function($row){
    //             $result = "";

    //             if($row->status == 1){
    //                 $result .= '<span class="badge badge-pill badge-success">Active</span>';
    //             }
    //             else{
    //                 $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('action1', function($row){
    //             $btn_color = 'btn-primary';
    //             if($row->status == 2){
    //                 $btn_color = 'btn-danger';
    //             }

    //             $result = '<center><div class="btn-group">
    //                       <button type="button" class="btn ' . $btn_color . ' dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
    //                         <i class="fa fa-cog"></i>
    //                       </button>
    //                       <div class="dropdown-menu dropdown-menu-right">';
    //             if($row->status == 1){
    //                 $result .= '<button class="dropdown-item aEditMatProc" type="button" material-process-id="' . $row->id . '" style="padding: 1px 1px; text-align: center;" data-keyboard="false">Edit Process</button>';
    //                 $result .= '<button class="dropdown-item actionEditMaterialProcessFlow" type="button" material-process-id="' . $row->id . '" style="padding: 1px 1px; text-align: center;" data-keyboard="false">Edit Machine</button>';
    //                 $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" material-process-id="' . $row->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Deactivate</button>';
    //             }
    //             else{
    //                 $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" style="padding: 1px 1px; text-align: center;" material-process-id="' . $row->id . '" status="1" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Activate</button>';
    //             }

    //             $result .= '</div>
    //                     </div></center>';

    //             return $result;
    //         })
    //         ->addColumn('chk_for_prod', function($row){
    //             $result = "";

    //             // if($row->for_prod == 1){
    //             //     $result .= '<center><input type="checkbox" value"1" process-flow-id="' . $row->id . '" class="chkProcessFlowForProd" checked></center>';
    //             // }
    //             // else{
    //             //     $result .= '<center><input type="checkbox" value"1" process-flow-id="' . $row->id . '" class="chkProcessFlowForProd"></center>';
    //             // }

    //             if($row->for_prod == 1){
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1" selected="true"> Production </option>';
	//                   $result .= '<option value="2"> Parts Preparatory </option>';
	//                   $result .= '<option value="0"> Others </option>';
	//                 $result .= '</select>';
	//             }
	//             else if($row->for_prod == 2){
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1"> Production </option>';
	//                   $result .= '<option value="2" selected="true"> Parts Preparatory </option>';
	//                   $result .= '<option value="0"> Others </option>';
	//                 $result .= '</select>';
	//             }
	//             else{
	//                 $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
	//                   $result .= '<option value="1"> Production </option>';
	//                   $result .= '<option value="2"> Parts Preparatory </option>';
	//                   $result .= '<option value="0" selected="true"> Others </option>';
	//                 $result .= '</select>';
	//             }



    //             return $result;
    //         })
    //         ->rawColumns(['label1', 'action1', 'chk_for_prod', 'materials', 'machines', 'a_shift', 'b_shift', 'approval_status'])
    //         ->make(true);
    // }

    public function view_process_flow_by_device_id(Request $request){ // 01-19-2023 added by Nessa
        date_default_timezone_set('Asia/Manila');
        session_start();

        // return $request->device_id;
        // return 'qwe';/

        // return $_SESSION["position"];

        $data = ProcessFlow::with(['process_flow_substation_details'])
            ->where('device_id', $request->device_id)
            ->where('status', $request->status)
            ->get();

            // return $data;

        return DataTables::of($data)
            // Start 2022 06 15
            ->addColumn('processInfo', function($row){
                $result = "---";
                $subStation = SubStation::where('id', $row->process)->get();
                if( count($subStation) > 0 ){
                    $result = $subStation[0]->name;
                }else{
                    $result = 'none';
                }
                // return $subStation;
                return $result;

            }) //ok
            // ->addColumn('materials', function($row){
            //     $result = "";
            //     $material_ids = explode(',', $row->material_id);
            //     $materials = WBSKitIssuance::whereIn('id', $material_ids)->get();
            //     for ($i=0; $i < count($materials); $i++){
            //         $result .= '<span class="badge badge-pill badge-success">' . $materials[$i]->item . ' - ' . $materials[$i]->item_desc . '</span>';
            //         if($i < count($materials)-1)
            //             $result .= '<br>';
            //     }
            //     return $result;
            // })
            ->addColumn('approval_status', function($row){
                $result = "";
                // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
                if($row->approval_status == 1){
                    if(in_array($_SESSION["position"], [1,12])){ // 1-Prod Supervisor, 2-QC Supervisor, 9-Engineer, 12-Admin
                        $result .=  '<center>';
                        $result .=      '<div class="btn-group">';
                        $result .=            '<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
                        $result .=                '<i class="fa fa-cog"></i>';
                        $result .=            '</button>';
                        $result .=          '<div class="dropdown-menu dropdown-menu-right">';
                        $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Approved</button>';
                        $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="4" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Disapproved</button>';
                        $result .=          '</div>';
                        $result .=      '</div>';
                        $result .=  '</center>';
                    }
                    $result .= '<span class="badge badge-pill badge-info">For Prod Supervisor Approval</span>';
                }else if($row->approval_status == 2){
                    if(in_array($_SESSION["position"], [2,12])){ // 1-Prod Supervisor, 2-QC Supervisor, 9-Engineer, 12-Admin
                        $result .=  '<center>';
                        $result .=      '<div class="btn-group">';
                        $result .=            '<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
                        $result .=                '<i class="fa fa-cog"></i>';
                        $result .=            '</button>';
                        $result .=          '<div class="dropdown-menu dropdown-menu-right">';
                        $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="3" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Approved</button>';
                        $result .=              '<button class="dropdown-item actionChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" approval-status="5" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeApprovalStatus" data-keyboard="false">Disapproved</button>';
                        $result .=          '</div>';
                        $result .=      '</div>';
                        $result .=  '</center>';
                    }
                    $result .= '<span class="badge badge-pill badge-info">For QC Approval</span>';
                }else if($row->approval_status == 3){
                    $result .= '<span class="badge badge-pill badge-success">Approved by QC</span>';
                }else if($row->approval_status == 4){
                    $result .= '<span class="badge badge-pill badge-danger">Disapproved by Production Supervisor</span>';
                }else if($row->approval_status == 5){
                    $result .= '<span class="badge badge-pill badge-danger">Disapproved by QC Supervisor</span>';
                }

                return $result;
            }) //ok
            ->addColumn('material_approval_status', function($row){
                $result = "";
                // 1-Prod Supervisor, 2-Conformed by Engineer, 3-Approved by QC, 4-Disapproved by Engineer, 5-Disapproved by QC
                if($row->material_approval_status == 1){
                    if(in_array($_SESSION["position"], [9,12])){ // 1-Prod Supervisor, 2-QC Supervisor, 9-Engineer, 12-Admin
                        $result .=  '<center>';
                        $result .=      '<div class="btn-group">';
                        $result .=            '<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
                        $result .=                '<i class="fa fa-cog"></i>';
                        $result .=            '</button>';
                        $result .=          '<div class="dropdown-menu dropdown-menu-right">';
                        $result .=              '<button class="dropdown-item actionMaterialChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" material-approval-status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalMaterialChangeApprovalStatus" data-keyboard="false">Approved</button>';
                        $result .=              '<button class="dropdown-item actionMaterialChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" material-approval-status="4" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalMaterialChangeApprovalStatus" data-keyboard="false">Disapproved</button>';
                        $result .=          '</div>';
                        $result .=      '</div>';
                        $result .=      '<div><span class="badge badge-pill badge-info">For Engineer Approval</span></div>';
                        $result .=  '</center>';
                    }
                }else if($row->material_approval_status == 2){
                    if(in_array($_SESSION["position"], [2,12])){ // 1-Prod Supervisor, 2-QC Supervisor, 9-Engineer, 12-Admin
                        $result .=  '<center>';
                        $result .=      '<div class="btn-group">';
                        $result .=            '<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
                        $result .=                '<i class="fa fa-cog"></i>';
                        $result .=            '</button>';
                        $result .=          '<div class="dropdown-menu dropdown-menu-right">';
                        $result .=              '<button class="dropdown-item actionMaterialChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" material-approval-status="3" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalMaterialChangeApprovalStatus" data-keyboard="false">Approved</button>';
                        $result .=              '<button class="dropdown-item actionMaterialChangeApprovalStatus" type="button" material-process-id="' . $row->id . '" material-approval-status="5" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalMaterialChangeApprovalStatus" data-keyboard="false">Disapproved</button>';
                        $result .=          '</div>';
                        $result .=      '</div>';
                        $result .=      '<div><span class="badge badge-pill badge-info">For QC Approval</span></div>';
                        $result .=  '</center>';
                    }
                }else if($row->material_approval_status == 3){
                    $result .= '<center><span class="badge badge-pill badge-success">Approved by QC</span></center>';
                }else if($row->material_approval_status == 4){
                    $result .= '<center><span class="badge badge-pill badge-danger">Disapproved by Engineer</span></center>';
                }else if($row->material_approval_status == 5){
                    $result .= '<center><span class="badge badge-pill badge-danger">Disapproved by QC Supervisor</span></center>';
                }

                return $result;
            }) //ok
            ->addColumn('machines', function($row){
                $result = "";
                $machine_ids = explode(',', $row->machine_id);
                // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
                // $machines = Machine::whereIn('id', $machine_ids)->get();
                $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
                    ->whereIn('id', $machine_ids)
                    ->get();
                for ($i=0; $i < count($machines); $i++){
                    $result .= '<span class="badge badge-pill badge-success">' . $machines[$i]->machine_name . '</span>';
                    if($i < count($machines)-1)
                        $result .= '<br>';
                }
                return $result;
            })//ok
            ->addColumn('a_shift', function($row){
                $result = "";
                $user_ids = explode(',', $row->a_shift_user_id);
                // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
                $users = User::whereIn('id', $user_ids)->get();
                for ($i=0; $i < count($users); $i++){
                    $result .= '<span class="badge badge-pill badge-success">' . $users[$i]->name . '</span>';
                    if($i < count($users)-1)
                        $result .= '<br>';
                }
                return $result;
            })//ok
            ->addColumn('b_shift', function($row){
                $result = "";
                $user_ids = explode(',', $row->b_shift_user_id);
                // $kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->whereIn('item', $materials)->get();
                $users = User::whereIn('id', $user_ids)->get();
                for ($i=0; $i < count($users); $i++){
                    $result .= '<span class="badge badge-pill badge-success">' . $users[$i]->name . '</span>';
                    if($i < count($users)-1)
                        $result .= '<br>';
                }
                return $result;
            })//ok
            // ->addColumn('label1', function($row){
            //     $result = "";

            //     if($row->status == 1){
            //         $result .= '<span class="badge badge-pill badge-success">Active</span>';
            //     }
            //     else{
            //         $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
            //     }

            //     return $result;
            // })
            ->addColumn('action1', function($row){
                $btn_color = 'btn-primary';
                if($row->status == 2){
                    $btn_color = 'btn-danger';
                }

                $result = '<center><div class="btn-group">
                          <button type="button" class="btn ' . $btn_color . ' dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($row->status == 1){
                    $result .= '<button class="dropdown-item aEditMatProc" type="button" material-process-id="' . $row->id . '" style="padding: 1px 1px; text-align: center;" data-keyboard="false">Edit Process</button>';

                    $result .= '<button class="dropdown-item actionEditMachineProcessFlow" type="button" material-process-id="' . $row->id . '" style="padding: 1px 1px; text-align: center;" data-keyboard="false">Edit Machine</button>';

                    //01-19-2023 added by Nessa
                    $result .= '<button class="dropdown-item actionEditMaterialProcessFlow" type="button" material-process-id="' . $row->id . '" device-id="'.$row->device_id.'" sub-station-id="' . $row->process . '"  style="padding: 1px 1px; text-align: center;" data-keyboard="false">Edit Material</button>';

                    // $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" material-process-id="' . $row->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Deactivate</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" style="padding: 1px 1px; text-align: center;" material-process-id="' . $row->id . '" status="1" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Activate</button>';
                }

                $result .= '</div>
                        </div></center>';

                return $result;
            })//ok
            ->addColumn('chk_for_prod', function($row){
                $result = "";

                // if($row->for_prod == 1){
                //     $result .= '<center><input type="checkbox" value"1" process-flow-id="' . $row->id . '" class="chkProcessFlowForProd" checked></center>';
                // }
                // else{
                //     $result .= '<center><input type="checkbox" value"1" process-flow-id="' . $row->id . '" class="chkProcessFlowForProd"></center>';
                // }

                if($row->for_prod == 1){
                    $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
                      $result .= '<option value="1" selected="true"> Production </option>';
                      $result .= '<option value="2"> Parts Preparatory </option>';
                      $result .= '<option value="0"> Others </option>';
                    $result .= '</select>';
                }
                else if($row->for_prod == 2){
                    $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
                      $result .= '<option value="1"> Production </option>';
                      $result .= '<option value="2" selected="true"> Parts Preparatory </option>';
                      $result .= '<option value="0"> Others </option>';
                    $result .= '</select>';
                }
                else{
                    $result .= '<select class="form-control form-control-sm selProcessFlowForProd" process-flow-id="' . $row->id . '">';
                      $result .= '<option value="1"> Production </option>';
                      $result .= '<option value="2"> Parts Preparatory </option>';
                      $result .= '<option value="0" selected="true"> Others </option>';
                    $result .= '</select>';
                }
                return $result;
            })//ok
            ->addColumn('materials', function($row){ // -JD
                $result = "";
                for ($i=0; $i < count($row->process_flow_substation_details); $i++){
                    $result .= '<span class="badge badge-pill badge-success">' . $row->process_flow_substation_details[$i]->material_part_no . ' - '. $row->process_flow_substation_details[$i]->material_name . '</span>';
                    if($i < count($row->process_flow_substation_details)-1)
                        $result .= '<br>';
                }
                return $result;
            })//ok

            // ->rawColumns(['label1', 'action1', 'chk_for_prod', 'materials', 'machines', 'a_shift', 'b_shift', 'approval_status', 'material_approval_status'])
            ->rawColumns(['action1', 'chk_for_prod', 'materials', 'machines', 'a_shift', 'b_shift', 'approval_status', 'material_approval_status'])
            ->make(true);
    }

    /**
     * Old
     * 01-16-2022
     * -JD
     */
    // public function save_process_flow(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();

    //     /* For debugging only */
    //     // if(isset($request->materialArray)){
    //     //     echo 'true';
    //     //     echo "\r\n";
    //     //     echo count($request->materialArray);
    //     //     echo "\r\n";
    //     //     echo $request->materialArray[1][1];
    //     //     echo "\r\n";
    //     //     echo $request->device_name;
    //     //     echo "\r\n";
    //     //     echo json_encode($request->all());
    //     // }else{
    //     //     echo 'false';
    //     //     echo "\r\n";
    //     // }
    //     // return;
    //     // exit(0);

    //     if($request->ajax()){
    //         if(Auth::check()){
    //             // Add Process Flow
    //             if(!isset($request->process_flow_id)){
    //                 $data = $request->all();

    //                 $rules = [
    //                     'device_id' => 'required',
    //                     'step' => 'required',
    //                     'process' => 'required',
    //                 ];

    //                 $user_info = User::where('employee_id', $_SESSION['employee_id'])
    //                             ->where('status', 1)
    //                             ->whereIn('position', [9, 12]) // 9-Engineer, 12-Admin
    //                             ->get();
    //                 // return $user_info;

    //                 if(count($user_info) > 0){
    //                     $validator = Validator::make($data, $rules);
    //                     if($validator->passes()){
    //                         $duplicateStep = ProcessFlow::where('device_id', $request->device_id)
    //                             ->where('step', $request->step)->get()->count();
    //                         if( $duplicateStep >= 1 )
    //                             return response()->json(['duplicateError' => "Step number already set."]);

    //                         $duplicateProcess = ProcessFlow::where('device_id', $request->device_id)
    //                             ->where('process', $request->process)->get()->count();
    //                             // return $duplicateProcess;
    //                         if( $duplicateProcess >= 1 )
    //                             return response()->json(['duplicateError' => "Process already set."]);

    //                         $material_kitting_item = "";
    //                         // for ($i=0; $i < count($request->material_kitting_item); $i++)
    //                         //     $material_kitting_item .= $request->material_kitting_item[$i] . "<|>";
    //                         //     // $material_kitting_item .= explode('--', $request->material_kitting_item[$i])[0] . "<|>";
    //                         // $material_kitting_item = substr($material_kitting_item, 0, -1);

    //                         $sakidashi_item = "";
    //                         // for ($i=0; $i < count($request->sakidashi_item); $i++)
    //                         //     $sakidashi_item .= $request->sakidashi_item[$i] . "<|>";
    //                         //     // $sakidashi_item .= explode('--', $request->sakidashi_item[$i])[0] . "<|>";
    //                         // $sakidashi_item = substr($sakidashi_item, 0, -1);

    //                         $machine_id = "";
    //                         if(isset($request->machine_id)){
    //                             for ($i=0; $i < count($request->machine_id); $i++)
    //                                 $machine_id .= $request->machine_id[$i] . ",";
    //                             $machine_id = substr($machine_id, 0, -1);
    //                         }

    //                         $a_shift_user_id = "";
    //                         if(isset($request->machine_id)){
    //                             for ($i=0; $i < count($request->a_shift_user_id); $i++)
    //                                 $a_shift_user_id .= $request->a_shift_user_id[$i] . ",";
    //                             $a_shift_user_id = substr($a_shift_user_id, 0, -1);
    //                         }

    //                         $b_shift_user_id = "";
    //                         if(isset($request->machine_id)){
    //                             for ($i=0; $i < count($request->b_shift_user_id); $i++)
    //                                 $b_shift_user_id .= $request->b_shift_user_id[$i] . ",";
    //                             $b_shift_user_id = substr($b_shift_user_id, 0, -1);
    //                         }

    //                         /* Mode 1 - for update */
    //                         if( $request->mode == 1 ){
    //                             ProcessFlow::where('id', $request->process_flow_id)
    //                                 ->where('status', 1)
    //                                 ->update([
    //                                     'step' => $request->step,
    //                                     'process' => $request->process,

    //                                     'lot_no' => $request->lot_no,
    //                                     'material_id' => $request->material_id,
    //                                     'material_name' => $request->material_name,
    //                                     'material_type' => $request->material_type,

    //                                     // 'material_kitting_item' => $material_kitting_item,
    //                                     // 'sakidashi_item' => $sakidashi_item,
    //                                     'machine_id' => $machine_id,
    //                                     'a_shift_user_id' => $a_shift_user_id,
    //                                     'b_shift_user_id' => $b_shift_user_id,
    //                                     'last_updated_by' => Auth::user()->id,
    //                                     'updated_at' => date('Y-m-d H:i:s'),
    //                                 ]
    //                             );
    //                         }

    //                         /* Mode 0 - for insert */
    //                         if( $request->mode == 0 ){
    //                             $process_flow_id = ProcessFlow::insertGetId([
    //                                 'device_id' => $request->device_id,
    //                                 'step' => $request->step,
    //                                 'process' => $request->process,
    //                                 'material_kitting_item' => $material_kitting_item,
    //                                 'sakidashi_item' => $sakidashi_item,
    //                                 'machine_id' => $machine_id,

    //                                 'lot_no' => $request->lot_no,
    //                                 'material_id' => $request->material_id,
    //                                 'material_name' => $request->material_name,
    //                                 'material_type' => $request->material_type,

    //                                 'a_shift_user_id' => $a_shift_user_id,
    //                                 'b_shift_user_id' => $b_shift_user_id,
    //                                 'status' => 1,
    //                                 'update_version' => 1,
    //                                 'created_by' => Auth::user()->id,
    //                                 'last_updated_by' => Auth::user()->id,
    //                                 'created_at' => date('Y-m-d H:i:s'),
    //                                 'updated_at' => date('Y-m-d H:i:s'),

    //                                 'approval_status' => 1, // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
    //                                 'approval_date' => date('Y-m-d H:i:s'),
    //                             ]);

    //                             /**
    //                              * Multiple insert for Device Process Flow
    //                              * 08-11-2022
    //                              * -JD
    //                              */
    //                             // if(isset($request->materialArray)){
    //                             //     for($index = 0; $index < count($request->materialArray); $index++){
    //                             //         DeviceProcessFlowMaterial::insert([
    //                             //             'process_flow_id' => $process_flow_id,
    //                             //             'device_id' => $request->device_id,
    //                             //             'sub_station_id' => $request->process,
    //                             //             // 'material_id' => $request->materialArray[$index][0],
    //                             //             // 'material_name' => $request->materialArray[$index][1],
    //                             //             // 'lot_no' => $request->materialArray[$index][2],
    //                             //             // 'material_type' => $request->materialArray[$index][5],
    //                             //             'material_id' => $request->materialArray[$index][0],
    //                             //             'material_name' => $request->materialArray[$index][1],
    //                             //             'lot_no' => $request->materialArray[$index][2],
    //                             //             'material_type' => $request->materialArray[$index][3],
    //                             //             'status' => 1,
    //                             //             'created_by' => Auth::user()->id,
    //                             //             'last_updated_by' => Auth::user()->id,
    //                             //             'updated_at' => date('Y-m-d H:i:s'),
    //                             //             'created_at' => date('Y-m-d H:i:s')
    //                             //         ]);
    //                             //     }
    //                             // }
    //                         }

    //                         return response()->json(['auth' => 1, 'result' => 1, 'error' => null, "request" => $request->all()]);
    //                     }
    //                     else{
    //                         return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
    //                     }
    //                 }else{
    //                     return response()->json(['auth' => 1, 'result' => 0, 'error' => 'notEngineer']);
    //                 }

    //             }
    //             // Edit Process Flow
    //             else{
    //                 $data = $request->all();
    //                 $rules = [
    //                     'device_id' => 'required',
    //                     'step' => 'required',
    //                     'process' => 'required',

    //                     // 'lot_no' => 'required',
    //                     // 'material_id' => 'required',
    //                     // 'material_name' => 'required',
    //                     // 'material_type' => 'required',

    //                     // 'material_kitting_item' => 'required',
    //                     // 'sakidashi_item' => 'required',
    //                     // 'machine_id' => 'required',
    //                     // 'a_shift_user_id' => 'required',
    //                     // 'b_shift_user_id' => 'required',
    //                 ];

    //                 $user_info = User::where('employee_id', $_SESSION['employee_id'])
    //                             ->where('status', 1)
    //                             ->whereIn('position', [9, 12]) // 9-Engineer, 12-Admin
    //                             ->get();
    //                 // return $user_info;

    //                 if(count($user_info) > 0){
    //                     // return $user_info;
    //                     $validator = Validator::make($data, $rules);
    //                     try {
    //                         if($validator->passes()){

    //                             $duplicateProcess = ProcessFlow::where('device_id', $request->device_id)
    //                                 ->where('process', $request->process)
    //                                 ->where('id', '!=', $request->process_flow_id)
    //                                 ->get()
    //                                 ->count();
    //                             if( $duplicateProcess >= 1 )
    //                                 return response()->json(['duplicateError' => "Process already set."]);

    //                             // ProcessFlow::where('id', $request->process_flow_id)
    //                             //     ->where('status', 1)
    //                             //     ->update([
    //                             //         'step' => $request->step,
    //                             //         'process' => $request->process,
    //                             // 		'last_updated_by' => Auth::user()->id,
    //                             //         'updated_at' => date('Y-m-d H:i:s'),
    //                             //     ]);

    //                             $material_kitting_item = "";
    //                             // for ($i=0; $i < count($request->material_kitting_item); $i++)
    //                             //     $material_kitting_item .= $request->material_kitting_item[$i] . "<|>";
    //                             //     // $material_kitting_item .= explode('--', $request->material_kitting_item[$i])[0] . "<|>";
    //                             // $material_kitting_item = substr($material_kitting_item, 0, -1);

    //                             $sakidashi_item = "";
    //                             // for ($i=0; $i < count($request->sakidashi_item); $i++)
    //                             //     $sakidashi_item .= $request->sakidashi_item[$i] . "<|>";
    //                             //     // $sakidashi_item .= explode('--', $request->sakidashi_item[$i])[0] . "<|>";
    //                             // $sakidashi_item = substr($sakidashi_item, 0, -1);

    //                             $machine_id = "";
    //                             if(isset($request->machine_id)){
    //                                 for ($i=0; $i < count($request->machine_id); $i++)
    //                                     $machine_id .= $request->machine_id[$i] . ",";
    //                                 $machine_id = substr($machine_id, 0, -1);
    //                             }


    //                             $a_shift_user_id = "";
    //                             if(isset($request->machine_id)){
    //                                 for ($i=0; $i < count($request->a_shift_user_id); $i++)
    //                                     $a_shift_user_id .= $request->a_shift_user_id[$i] . ",";
    //                                 $a_shift_user_id = substr($a_shift_user_id, 0, -1);
    //                             }

    //                             $b_shift_user_id = "";
    //                             if(isset($request->machine_id)){
    //                                 for ($i=0; $i < count($request->b_shift_user_id); $i++)
    //                                     $b_shift_user_id .= $request->b_shift_user_id[$i] . ",";
    //                                 $b_shift_user_id = substr($b_shift_user_id, 0, -1);
    //                             }

    //                             // return $request->process_flow_id;
    //                             $processFlowDetails = ProcessFlow::where('id', $request->process_flow_id)
    //                                 ->where('status', 1)
    //                                 // ->whereIn('approval_status', [3,4,5])
    //                                 ->get();
    //                             // return $processFlowDetails;

    //                             // if($processFlowDetails[0]->approval_status  == 4 || $processFlowDetails[0]->approval_status == 5 || $processFlowDetails[0]->approval_status == 3){
    //                             if(count($processFlowDetails) > 0){
    //                                 ProcessFlow::where('id', $request->process_flow_id)
    //                                     ->where('status', 1)
    //                                     ->update([
    //                                         // 'step' => $request->step,
    //                                         'process' => $request->process,

    //                                         'lot_no' => $request->lot_no,
    //                                         'material_id' => $request->material_id,
    //                                         'material_name' => $request->material_name,
    //                                         'material_type' => $request->material_type,

    //                                         // 'material_kitting_item' => $material_kitting_item,
    //                                         // 'sakidashi_item' => $sakidashi_item,
    //                                         'machine_id' => $machine_id,
    //                                         'a_shift_user_id' => $a_shift_user_id,
    //                                         'b_shift_user_id' => $b_shift_user_id,
    //                                         'last_updated_by' => Auth::user()->id,
    //                                         'updated_at' => date('Y-m-d H:i:s'),

    //                                         /**
    //                                          * Change approval status
    //                                          * 01-06-2023
    //                                          * JD
    //                                          */
    //                                         'approval_status' => 1, // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
    //                                         'approval_date' => date('Y-m-d H:i:s'),
    //                                     ]
    //                                 );
    //                             }else{
    //                                 ProcessFlow::where('id', $request->process_flow_id)
    //                                     ->where('status', 1)
    //                                     ->update([
    //                                         // 'step' => $request->step,
    //                                         'process' => $request->process,

    //                                         'lot_no' => $request->lot_no,
    //                                         'material_id' => $request->material_id,
    //                                         'material_name' => $request->material_name,
    //                                         'material_type' => $request->material_type,

    //                                         // 'material_kitting_item' => $material_kitting_item,
    //                                         // 'sakidashi_item' => $sakidashi_item,
    //                                         'machine_id' => $machine_id,
    //                                         'a_shift_user_id' => $a_shift_user_id,
    //                                         'b_shift_user_id' => $b_shift_user_id,
    //                                         'last_updated_by' => Auth::user()->id,
    //                                         'updated_at' => date('Y-m-d H:i:s'),
    //                                     ]
    //                                 );
    //                             }



    //                                 /**
    //                                  * Multiple insert for Device Process Flow
    //                                  * 08-11-2022
    //                                  * -JD
    //                                  */
    //                                 // DeviceProcessFlowMaterial::where('process_flow_id', $request->process_flow_id)
    //                                 //     ->delete();
    //                                 // if(isset($request->materialArray)){
    //                                 //     for($index = 0; $index < count($request->materialArray); $index++){
    //                                 //         DeviceProcessFlowMaterial::insert([
    //                                 //             'process_flow_id' => $request->process_flow_id,
    //                                 //             'device_id' => $request->device_id,
    //                                 //             'sub_station_id' => $request->process,
    //                                 //             // 'material_id' => $request->materialArray[$index][0],
    //                                 //             // 'material_name' => $request->materialArray[$index][1],
    //                                 //             // 'lot_no' => $request->materialArray[$index][2],
    //                                 //             // 'material_type' => $request->materialArray[$index][5],
    //                                 //             'material_id' => $request->materialArray[$index][0],
    //                                 //             'material_name' => $request->materialArray[$index][1],
    //                                 //             'lot_no' => $request->materialArray[$index][2],
    //                                 //             'material_type' => $request->materialArray[$index][3],
    //                                 //             'status' => 1,
    //                                 //             'created_by' => Auth::user()->id,
    //                                 //             'last_updated_by' => Auth::user()->id,
    //                                 //             'updated_at' => date('Y-m-d H:i:s'),
    //                                 //             'created_at' => date('Y-m-d H:i:s')
    //                                 //         ]);
    //                                 //     }
    //                                 // }

    //                             return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
    //                         }
    //                         else{
    //                             return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
    //                         }
    //                     }
    //                     catch(\Exception $e) {
    //                         // echo "test";
    //                         // echo "\r\n";
    //                         echo $e;
    //                         return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
    //                     }
    //                 }else{
    //                     return response()->json(['auth' => 1, 'result' => 0, 'error' => 'notEngineer']);
    //                 }

    //             }
    //         }
    //         else{
    //             return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
    //         }
    //     }
    //     else{
    //         abort(403);
    //     }
    // }

    // public function save_process_flow(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();

    //     /* For debugging only */
    //     // if(isset($request->materialArray)){
    //     //     echo 'true';
    //     //     echo "\r\n";
    //     //     echo count($request->materialArray);
    //     //     echo "\r\n";
    //     //     echo $request->materialArray[1][1];
    //     //     echo "\r\n";
    //     //     echo $request->device_name;
    //     //     echo "\r\n";
    //     //     echo json_encode($request->all());
    //     // }else{
    //     //     echo 'false';
    //     //     echo "\r\n";
    //     // }
    //     // return;
    //     // exit(0);

    //     if($request->ajax()){
    //         if(Auth::check()){
    //             // Add Process Flow
    //             if(!isset($request->process_flow_id)){
    //                 $data = $request->all();

    //                 $rules = [
    //                     'device_id' => 'required',
    //                     'step' => 'required',
    //                     'process' => 'required',
    //                 ];

    //                 $user_info = User::where('employee_id', $_SESSION['employee_id'])
    //                             ->where('status', 1)
    //                             ->whereIn('position', [9, 12]) // 9-Engineer, 12-Admin
    //                             ->get();
    //                 // return $user_info;

    //                 if(count($user_info) > 0){
    //                     $validator = Validator::make($data, $rules);
    //                     if($validator->passes()){
    //                         $duplicateStep = ProcessFlow::where('device_id', $request->device_id)
    //                             ->where('step', $request->step)->get()->count();
    //                         if( $duplicateStep >= 1 )
    //                             return response()->json(['duplicateError' => "Step number already set."]);

    //                         $duplicateProcess = ProcessFlow::where('device_id', $request->device_id)
    //                             ->where('process', $request->process)->get()->count();
    //                             // return $duplicateProcess;
    //                         if( $duplicateProcess >= 1 )
    //                             return response()->json(['duplicateError' => "Process already set."]);

    //                         $material_kitting_item = "";
    //                         // for ($i=0; $i < count($request->material_kitting_item); $i++)
    //                         //     $material_kitting_item .= $request->material_kitting_item[$i] . "<|>";
    //                         //     // $material_kitting_item .= explode('--', $request->material_kitting_item[$i])[0] . "<|>";
    //                         // $material_kitting_item = substr($material_kitting_item, 0, -1);

    //                         $sakidashi_item = "";
    //                         // for ($i=0; $i < count($request->sakidashi_item); $i++)
    //                         //     $sakidashi_item .= $request->sakidashi_item[$i] . "<|>";
    //                         //     // $sakidashi_item .= explode('--', $request->sakidashi_item[$i])[0] . "<|>";
    //                         // $sakidashi_item = substr($sakidashi_item, 0, -1);

    //                         $machine_id = "";
    //                         if(isset($request->machine_id)){
    //                             for ($i=0; $i < count($request->machine_id); $i++)
    //                                 $machine_id .= $request->machine_id[$i] . ",";
    //                             $machine_id = substr($machine_id, 0, -1);
    //                         }

    //                         $a_shift_user_id = "";
    //                         if(isset($request->machine_id)){
    //                             for ($i=0; $i < count($request->a_shift_user_id); $i++)
    //                                 $a_shift_user_id .= $request->a_shift_user_id[$i] . ",";
    //                             $a_shift_user_id = substr($a_shift_user_id, 0, -1);
    //                         }

    //                         $b_shift_user_id = "";
    //                         if(isset($request->machine_id)){
    //                             for ($i=0; $i < count($request->b_shift_user_id); $i++)
    //                                 $b_shift_user_id .= $request->b_shift_user_id[$i] . ",";
    //                             $b_shift_user_id = substr($b_shift_user_id, 0, -1);
    //                         }

    //                         /* Mode 1 - for update */
    //                         if( $request->mode == 1 ){
    //                             ProcessFlow::where('id', $request->process_flow_id)
    //                                 ->where('status', 1)
    //                                 ->update([
    //                                     'step' => $request->step,
    //                                     'process' => $request->process,

    //                                     'lot_no' => $request->lot_no,
    //                                     'material_id' => $request->material_id,
    //                                     'material_name' => $request->material_name,
    //                                     'material_type' => $request->material_type,

    //                                     // 'material_kitting_item' => $material_kitting_item,
    //                                     // 'sakidashi_item' => $sakidashi_item,
    //                                     'machine_id' => $machine_id,
    //                                     'a_shift_user_id' => $a_shift_user_id,
    //                                     'b_shift_user_id' => $b_shift_user_id,
    //                                     'last_updated_by' => Auth::user()->id,
    //                                     'updated_at' => date('Y-m-d H:i:s'),
    //                                 ]
    //                             );
    //                         }

    //                         /* Mode 0 - for insert */
    //                         if( $request->mode == 0 ){
    //                             $process_flow_id = ProcessFlow::insertGetId([
    //                                 'device_id' => $request->device_id,
    //                                 'step' => $request->step,
    //                                 'process' => $request->process,
    //                                 'material_kitting_item' => $material_kitting_item,
    //                                 'sakidashi_item' => $sakidashi_item,
    //                                 'machine_id' => $machine_id,

    //                                 'lot_no' => $request->lot_no,
    //                                 'material_id' => $request->material_id,
    //                                 'material_name' => $request->material_name,
    //                                 'material_type' => $request->material_type,

    //                                 'a_shift_user_id' => $a_shift_user_id,
    //                                 'b_shift_user_id' => $b_shift_user_id,
    //                                 'status' => 1,
    //                                 'update_version' => 1,
    //                                 'created_by' => Auth::user()->id,
    //                                 'last_updated_by' => Auth::user()->id,
    //                                 'created_at' => date('Y-m-d H:i:s'),
    //                                 'updated_at' => date('Y-m-d H:i:s'),

    //                                 'approval_status' => 1, // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
    //                                 'approval_date' => date('Y-m-d H:i:s'),
    //                             ]);

    //                             /**
    //                              * Multiple insert for Device Process Flow
    //                              * 08-11-2022
    //                              * -JD
    //                              */
    //                             // if(isset($request->materialArray)){
    //                             //     for($index = 0; $index < count($request->materialArray); $index++){
    //                             //         DeviceProcessFlowMaterial::insert([
    //                             //             'process_flow_id' => $process_flow_id,
    //                             //             'device_id' => $request->device_id,
    //                             //             'sub_station_id' => $request->process,
    //                             //             // 'material_id' => $request->materialArray[$index][0],
    //                             //             // 'material_name' => $request->materialArray[$index][1],
    //                             //             // 'lot_no' => $request->materialArray[$index][2],
    //                             //             // 'material_type' => $request->materialArray[$index][5],
    //                             //             'material_id' => $request->materialArray[$index][0],
    //                             //             'material_name' => $request->materialArray[$index][1],
    //                             //             'lot_no' => $request->materialArray[$index][2],
    //                             //             'material_type' => $request->materialArray[$index][3],
    //                             //             'status' => 1,
    //                             //             'created_by' => Auth::user()->id,
    //                             //             'last_updated_by' => Auth::user()->id,
    //                             //             'updated_at' => date('Y-m-d H:i:s'),
    //                             //             'created_at' => date('Y-m-d H:i:s')
    //                             //         ]);
    //                             //     }
    //                             // }
    //                         }

    //                         return response()->json(['auth' => 1, 'result' => 1, 'error' => null, "request" => $request->all()]);
    //                     }
    //                     else{
    //                         return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
    //                     }
    //                 }else{
    //                     return response()->json(['auth' => 1, 'result' => 0, 'error' => 'notEngineer']);
    //                 }

    //             }
    //             // Edit Process Flow
    //             else{
    //                 $data = $request->all();
    //                 $rules = [
    //                     'device_id' => 'required',
    //                     'step' => 'required',
    //                     'process' => 'required',

    //                     // 'lot_no' => 'required',
    //                     // 'material_id' => 'required',
    //                     // 'material_name' => 'required',
    //                     // 'material_type' => 'required',

    //                     // 'material_kitting_item' => 'required',
    //                     // 'sakidashi_item' => 'required',
    //                     // 'machine_id' => 'required',
    //                     // 'a_shift_user_id' => 'required',
    //                     // 'b_shift_user_id' => 'required',
    //                 ];

    //                 $user_info = User::where('employee_id', $_SESSION['employee_id'])
    //                             ->where('status', 1)
    //                             ->whereIn('position', [9, 12]) // 9-Engineer, 12-Admin
    //                             ->get();
    //                 // return $user_info;

    //                 if(count($user_info) > 0){
    //                     // return $user_info;
    //                     $validator = Validator::make($data, $rules);
    //                     try {
    //                         if($validator->passes()){

    //                             $duplicateProcess = ProcessFlow::where('device_id', $request->device_id)
    //                                 ->where('process', $request->process)
    //                                 ->where('id', '!=', $request->process_flow_id)
    //                                 ->get()
    //                                 ->count();
    //                             if( $duplicateProcess >= 1 )
    //                                 return response()->json(['duplicateError' => "Process already set."]);

    //                             // ProcessFlow::where('id', $request->process_flow_id)
    //                             //     ->where('status', 1)
    //                             //     ->update([
    //                             //         'step' => $request->step,
    //                             //         'process' => $request->process,
    //                             // 		'last_updated_by' => Auth::user()->id,
    //                             //         'updated_at' => date('Y-m-d H:i:s'),
    //                             //     ]);

    //                             $material_kitting_item = "";
    //                             // for ($i=0; $i < count($request->material_kitting_item); $i++)
    //                             //     $material_kitting_item .= $request->material_kitting_item[$i] . "<|>";
    //                             //     // $material_kitting_item .= explode('--', $request->material_kitting_item[$i])[0] . "<|>";
    //                             // $material_kitting_item = substr($material_kitting_item, 0, -1);

    //                             $sakidashi_item = "";
    //                             // for ($i=0; $i < count($request->sakidashi_item); $i++)
    //                             //     $sakidashi_item .= $request->sakidashi_item[$i] . "<|>";
    //                             //     // $sakidashi_item .= explode('--', $request->sakidashi_item[$i])[0] . "<|>";
    //                             // $sakidashi_item = substr($sakidashi_item, 0, -1);

    //                             $machine_id = "";
    //                             if(isset($request->machine_id)){
    //                                 for ($i=0; $i < count($request->machine_id); $i++)
    //                                     $machine_id .= $request->machine_id[$i] . ",";
    //                                 $machine_id = substr($machine_id, 0, -1);
    //                             }


    //                             $a_shift_user_id = "";
    //                             if(isset($request->machine_id)){
    //                                 for ($i=0; $i < count($request->a_shift_user_id); $i++)
    //                                     $a_shift_user_id .= $request->a_shift_user_id[$i] . ",";
    //                                 $a_shift_user_id = substr($a_shift_user_id, 0, -1);
    //                             }

    //                             $b_shift_user_id = "";
    //                             if(isset($request->machine_id)){
    //                                 for ($i=0; $i < count($request->b_shift_user_id); $i++)
    //                                     $b_shift_user_id .= $request->b_shift_user_id[$i] . ",";
    //                                 $b_shift_user_id = substr($b_shift_user_id, 0, -1);
    //                             }

    //                             // return $request->process_flow_id;
    //                             $processFlowDetails = ProcessFlow::where('id', $request->process_flow_id)
    //                                 ->where('status', 1)
    //                                 // ->whereIn('approval_status', [3,4,5])
    //                                 ->get();
    //                             // return $processFlowDetails;

    //                             // if($processFlowDetails[0]->approval_status  == 4 || $processFlowDetails[0]->approval_status == 5 || $processFlowDetails[0]->approval_status == 3){
    //                             if(count($processFlowDetails) > 0){
    //                                 ProcessFlow::where('id', $request->process_flow_id)
    //                                     ->where('status', 1)
    //                                     ->update([
    //                                         // 'step' => $request->step,
    //                                         'process' => $request->process,

    //                                         'lot_no' => $request->lot_no,
    //                                         'material_id' => $request->material_id,
    //                                         'material_name' => $request->material_name,
    //                                         'material_type' => $request->material_type,

    //                                         // 'material_kitting_item' => $material_kitting_item,
    //                                         // 'sakidashi_item' => $sakidashi_item,
    //                                         // 'machine_id' => $machine_id, // Separate function for machine only
    //                                         'a_shift_user_id' => $a_shift_user_id,
    //                                         'b_shift_user_id' => $b_shift_user_id,
    //                                         'last_updated_by' => Auth::user()->id,
    //                                         'updated_at' => date('Y-m-d H:i:s'),

    //                                         /**
    //                                          * Change approval status
    //                                          * 01-06-2023
    //                                          * JD
    //                                          */
    //                                         'approval_status' => 1, // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
    //                                         'approval_date' => date('Y-m-d H:i:s'),
    //                                     ]
    //                                 );
    //                             }else{
    //                                 ProcessFlow::where('id', $request->process_flow_id)
    //                                     ->where('status', 1)
    //                                     ->update([
    //                                         // 'step' => $request->step,
    //                                         'process' => $request->process,

    //                                         'lot_no' => $request->lot_no,
    //                                         'material_id' => $request->material_id,
    //                                         'material_name' => $request->material_name,
    //                                         'material_type' => $request->material_type,

    //                                         // 'material_kitting_item' => $material_kitting_item,
    //                                         // 'sakidashi_item' => $sakidashi_item,
    //                                         // 'machine_id' => $machine_id, // Separate function for machine only
    //                                         'a_shift_user_id' => $a_shift_user_id,
    //                                         'b_shift_user_id' => $b_shift_user_id,
    //                                         'last_updated_by' => Auth::user()->id,
    //                                         'updated_at' => date('Y-m-d H:i:s'),
    //                                     ]
    //                                 );
    //                             }



    //                                 /**
    //                                  * Multiple insert for Device Process Flow
    //                                  * 08-11-2022
    //                                  * -JD
    //                                  */
    //                                 // DeviceProcessFlowMaterial::where('process_flow_id', $request->process_flow_id)
    //                                 //     ->delete();
    //                                 // if(isset($request->materialArray)){
    //                                 //     for($index = 0; $index < count($request->materialArray); $index++){
    //                                 //         DeviceProcessFlowMaterial::insert([
    //                                 //             'process_flow_id' => $request->process_flow_id,
    //                                 //             'device_id' => $request->device_id,
    //                                 //             'sub_station_id' => $request->process,
    //                                 //             // 'material_id' => $request->materialArray[$index][0],
    //                                 //             // 'material_name' => $request->materialArray[$index][1],
    //                                 //             // 'lot_no' => $request->materialArray[$index][2],
    //                                 //             // 'material_type' => $request->materialArray[$index][5],
    //                                 //             'material_id' => $request->materialArray[$index][0],
    //                                 //             'material_name' => $request->materialArray[$index][1],
    //                                 //             'lot_no' => $request->materialArray[$index][2],
    //                                 //             'material_type' => $request->materialArray[$index][3],
    //                                 //             'status' => 1,
    //                                 //             'created_by' => Auth::user()->id,
    //                                 //             'last_updated_by' => Auth::user()->id,
    //                                 //             'updated_at' => date('Y-m-d H:i:s'),
    //                                 //             'created_at' => date('Y-m-d H:i:s')
    //                                 //         ]);
    //                                 //     }
    //                                 // }

    //                             return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
    //                         }
    //                         else{
    //                             return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
    //                         }
    //                     }
    //                     catch(\Exception $e) {
    //                         // echo "test";
    //                         // echo "\r\n";
    //                         echo $e;
    //                         return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
    //                     }
    //                 }else{
    //                     return response()->json(['auth' => 1, 'result' => 0, 'error' => 'notEngineer']);
    //                 }

    //             }
    //         }
    //         else{
    //             return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
    //         }
    //     }
    //     else{
    //         abort(403);
    //     }
    // }

    public function save_process_flow(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        /* For debugging only */
        // if(isset($request->materialArray)){
        //     echo 'true';
        //     echo "\r\n";
        //     echo count($request->materialArray);
        //     echo "\r\n";
        //     echo $request->materialArray[1][1];
        //     echo "\r\n";
        //     echo $request->device_name;
        //     echo "\r\n";
        //     echo json_encode($request->all());
        // }else{
        //     echo 'false';
        //     echo "\r\n";
        // }
        // return;
        // exit(0);

        if($request->ajax()){
            if(Auth::check()){
                // Add Process Flow
                if(!isset($request->process_flow_id)){
                    $data = $request->all();

                    $rules = [
                        'device_id' => 'required',
                        'step' => 'required',
                        'process' => 'required',
                    ];

                    $user_info = User::where('employee_id', $_SESSION['employee_id'])
                                ->where('status', 1)
                                ->whereIn('position', [9, 12]) // 9-Engineer, 12-Admin
                                ->get();
                    // return $user_info;

                    if(count($user_info) > 0){
                        $validator = Validator::make($data, $rules);
                        if($validator->passes()){
                            $duplicateStep = ProcessFlow::where('device_id', $request->device_id)
                                ->where('step', $request->step)->get()->count();
                            if( $duplicateStep >= 1 )
                                return response()->json(['duplicateError' => "Step number already set."]);

                            $duplicateProcess = ProcessFlow::where('device_id', $request->device_id)
                                ->where('process', $request->process)->get()->count();
                                // return $duplicateProcess;
                            if( $duplicateProcess >= 1 )
                                return response()->json(['duplicateError' => "Process already set."]);

                            $material_kitting_item = "";
                            // for ($i=0; $i < count($request->material_kitting_item); $i++)
                            //     $material_kitting_item .= $request->material_kitting_item[$i] . "<|>";
                            //     // $material_kitting_item .= explode('--', $request->material_kitting_item[$i])[0] . "<|>";
                            // $material_kitting_item = substr($material_kitting_item, 0, -1);

                            $sakidashi_item = "";
                            // for ($i=0; $i < count($request->sakidashi_item); $i++)
                            //     $sakidashi_item .= $request->sakidashi_item[$i] . "<|>";
                            //     // $sakidashi_item .= explode('--', $request->sakidashi_item[$i])[0] . "<|>";
                            // $sakidashi_item = substr($sakidashi_item, 0, -1);

                            $machine_id = "";
                            if(isset($request->machine_id)){
                                for ($i=0; $i < count($request->machine_id); $i++)
                                    $machine_id .= $request->machine_id[$i] . ",";
                                $machine_id = substr($machine_id, 0, -1);
                            }

                            $a_shift_user_id = "";
                            if(isset($request->machine_id)){
                                for ($i=0; $i < count($request->a_shift_user_id); $i++)
                                    $a_shift_user_id .= $request->a_shift_user_id[$i] . ",";
                                $a_shift_user_id = substr($a_shift_user_id, 0, -1);
                            }

                            $b_shift_user_id = "";
                            if(isset($request->machine_id)){
                                for ($i=0; $i < count($request->b_shift_user_id); $i++)
                                    $b_shift_user_id .= $request->b_shift_user_id[$i] . ",";
                                $b_shift_user_id = substr($b_shift_user_id, 0, -1);
                            }

                            /* Mode 1 - for update */
                            if( $request->mode == 1 ){
                                ProcessFlow::where('id', $request->process_flow_id)
                                    ->where('status', 1)
                                    ->update([
                                        'step' => $request->step,
                                        'process' => $request->process,

                                        'lot_no' => $request->lot_no,
                                        'material_id' => $request->material_id,
                                        'material_name' => $request->material_name,
                                        'material_type' => $request->material_type,

                                        // 'material_kitting_item' => $material_kitting_item,
                                        // 'sakidashi_item' => $sakidashi_item,
                                        'machine_id' => $machine_id,
                                        'a_shift_user_id' => $a_shift_user_id,
                                        'b_shift_user_id' => $b_shift_user_id,
                                        'last_updated_by' => Auth::user()->id,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ]
                                );
                            }

                            /* Mode 0 - for insert */
                            if( $request->mode == 0 ){
                                $process_flow_id = ProcessFlow::insertGetId([
                                    'device_id' => $request->device_id,
                                    'step' => $request->step,
                                    'process' => $request->process,
                                    'material_kitting_item' => $material_kitting_item,
                                    'sakidashi_item' => $sakidashi_item,
                                    'machine_id' => $machine_id,

                                    'lot_no' => $request->lot_no,
                                    'material_id' => $request->material_id,
                                    'material_name' => $request->material_name,
                                    'material_type' => $request->material_type,

                                    'a_shift_user_id' => $a_shift_user_id,
                                    'b_shift_user_id' => $b_shift_user_id,
                                    'status' => 1,
                                    'update_version' => 1,
                                    'created_by' => Auth::user()->id,
                                    'last_updated_by' => Auth::user()->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),

                                    'approval_status' => 1, // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
                                    'approval_date' => date('Y-m-d H:i:s'),
                                ]);

                                /**
                                 * Multiple insert for Device Process Flow
                                 * 08-11-2022
                                 * -JD
                                 */
                                // if(isset($request->materialArray)){
                                //     for($index = 0; $index < count($request->materialArray); $index++){
                                //         DeviceProcessFlowMaterial::insert([
                                //             'process_flow_id' => $process_flow_id,
                                //             'device_id' => $request->device_id,
                                //             'sub_station_id' => $request->process,
                                //             // 'material_id' => $request->materialArray[$index][0],
                                //             // 'material_name' => $request->materialArray[$index][1],
                                //             // 'lot_no' => $request->materialArray[$index][2],
                                //             // 'material_type' => $request->materialArray[$index][5],
                                //             'material_id' => $request->materialArray[$index][0],
                                //             'material_name' => $request->materialArray[$index][1],
                                //             'lot_no' => $request->materialArray[$index][2],
                                //             'material_type' => $request->materialArray[$index][3],
                                //             'status' => 1,
                                //             'created_by' => Auth::user()->id,
                                //             'last_updated_by' => Auth::user()->id,
                                //             'updated_at' => date('Y-m-d H:i:s'),
                                //             'created_at' => date('Y-m-d H:i:s')
                                //         ]);
                                //     }
                                // }
                            }

                            return response()->json(['auth' => 1, 'result' => 1, 'error' => null, "request" => $request->all()]);
                        }
                        else{
                            return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                        }
                    }else{
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => 'notEngineer']);
                    }

                }
                // Edit Process Flow
                else{
                    $data = $request->all();
                    $rules = [
                        'device_id' => 'required',
                        'step' => 'required',
                        'process' => 'required',

                        // 'lot_no' => 'required',
                        // 'material_id' => 'required',
                        // 'material_name' => 'required',
                        // 'material_type' => 'required',

                        // 'material_kitting_item' => 'required',
                        // 'sakidashi_item' => 'required',
                        // 'machine_id' => 'required',
                        // 'a_shift_user_id' => 'required',
                        // 'b_shift_user_id' => 'required',
                    ];

                    $user_info = User::where('employee_id', $_SESSION['employee_id'])
                                ->where('status', 1)
                                ->whereIn('position', [9, 12]) // 9-Engineer, 12-Admin
                                ->get();
                    // return $user_info;

                    if(count($user_info) > 0){
                        // return $user_info;
                        $validator = Validator::make($data, $rules);
                        try {
                            if($validator->passes()){

                                $duplicateProcess = ProcessFlow::where('device_id', $request->device_id)
                                    ->where('process', $request->process)
                                    ->where('id', '!=', $request->process_flow_id)
                                    ->get()
                                    ->count();
                                if( $duplicateProcess >= 1 )
                                    return response()->json(['duplicateError' => "Process already set."]);

                                // ProcessFlow::where('id', $request->process_flow_id)
                                //     ->where('status', 1)
                                //     ->update([
                                //         'step' => $request->step,
                                //         'process' => $request->process,
                                //      'last_updated_by' => Auth::user()->id,
                                //         'updated_at' => date('Y-m-d H:i:s'),
                                //     ]);

                                $material_kitting_item = "";
                                // for ($i=0; $i < count($request->material_kitting_item); $i++)
                                //     $material_kitting_item .= $request->material_kitting_item[$i] . "<|>";
                                //     // $material_kitting_item .= explode('--', $request->material_kitting_item[$i])[0] . "<|>";
                                // $material_kitting_item = substr($material_kitting_item, 0, -1);

                                $sakidashi_item = "";
                                // for ($i=0; $i < count($request->sakidashi_item); $i++)
                                //     $sakidashi_item .= $request->sakidashi_item[$i] . "<|>";
                                //     // $sakidashi_item .= explode('--', $request->sakidashi_item[$i])[0] . "<|>";
                                // $sakidashi_item = substr($sakidashi_item, 0, -1);

                                $machine_id = "";
                                if(isset($request->machine_id)){
                                    for ($i=0; $i < count($request->machine_id); $i++)
                                        $machine_id .= $request->machine_id[$i] . ",";
                                    $machine_id = substr($machine_id, 0, -1);
                                }


                                $a_shift_user_id = "";
                                if(isset($request->machine_id)){
                                    for ($i=0; $i < count($request->a_shift_user_id); $i++)
                                        $a_shift_user_id .= $request->a_shift_user_id[$i] . ",";
                                    $a_shift_user_id = substr($a_shift_user_id, 0, -1);
                                }

                                $b_shift_user_id = "";
                                if(isset($request->machine_id)){
                                    for ($i=0; $i < count($request->b_shift_user_id); $i++)
                                        $b_shift_user_id .= $request->b_shift_user_id[$i] . ",";
                                    $b_shift_user_id = substr($b_shift_user_id, 0, -1);
                                }

                                // return $request->process_flow_id;
                                $processFlowDetails = ProcessFlow::where('id', $request->process_flow_id)
                                    ->where('status', 1)
                                    // ->whereIn('approval_status', [3,4,5])
                                    ->get();
                                // return $processFlowDetails;

                                // if($processFlowDetails[0]->approval_status  == 4 || $processFlowDetails[0]->approval_status == 5 || $processFlowDetails[0]->approval_status == 3){
                                if(count($processFlowDetails) > 0){
                                    ProcessFlow::where('id', $request->process_flow_id)
                                        ->where('status', 1)
                                        ->update([
                                            // 'step' => $request->step,
                                            'process' => $request->process,

                                            'lot_no' => $request->lot_no,
                                            'material_id' => $request->material_id,
                                            'material_name' => $request->material_name,
                                            'material_type' => $request->material_type,

                                            // 'material_kitting_item' => $material_kitting_item,
                                            // 'sakidashi_item' => $sakidashi_item,
                                            // 'machine_id' => $machine_id, // Separate function for machine only
                                            'a_shift_user_id' => $a_shift_user_id,
                                            'b_shift_user_id' => $b_shift_user_id,
                                            'last_updated_by' => Auth::user()->id,
                                            'updated_at' => date('Y-m-d H:i:s'),

                                            /**
                                             * Change approval status
                                             * 01-06-2023
                                             * JD
                                             */
                                            'approval_status' => 1, // 1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC
                                            'approval_date' => date('Y-m-d H:i:s'),
                                        ]
                                    );
                                }else{
                                    ProcessFlow::where('id', $request->process_flow_id)
                                        ->where('status', 1)
                                        ->update([
                                            // 'step' => $request->step,
                                            'process' => $request->process,

                                            'lot_no' => $request->lot_no,
                                            'material_id' => $request->material_id,
                                            'material_name' => $request->material_name,
                                            'material_type' => $request->material_type,

                                            // 'material_kitting_item' => $material_kitting_item,
                                            // 'sakidashi_item' => $sakidashi_item,
                                            // 'machine_id' => $machine_id, // Separate function for machine only
                                            'a_shift_user_id' => $a_shift_user_id,
                                            'b_shift_user_id' => $b_shift_user_id,
                                            'last_updated_by' => Auth::user()->id,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]
                                    );
                                }



                                    /**
                                     * Multiple insert for Device Process Flow
                                     * 08-11-2022
                                     * -JD
                                     */
                                    // DeviceProcessFlowMaterial::where('process_flow_id', $request->process_flow_id)
                                    //     ->delete();
                                    // if(isset($request->materialArray)){
                                    //     for($index = 0; $index < count($request->materialArray); $index++){
                                    //         DeviceProcessFlowMaterial::insert([
                                    //             'process_flow_id' => $request->process_flow_id,
                                    //             'device_id' => $request->device_id,
                                    //             'sub_station_id' => $request->process,
                                    //             // 'material_id' => $request->materialArray[$index][0],
                                    //             // 'material_name' => $request->materialArray[$index][1],
                                    //             // 'lot_no' => $request->materialArray[$index][2],
                                    //             // 'material_type' => $request->materialArray[$index][5],
                                    //             'material_id' => $request->materialArray[$index][0],
                                    //             'material_name' => $request->materialArray[$index][1],
                                    //             'lot_no' => $request->materialArray[$index][2],
                                    //             'material_type' => $request->materialArray[$index][3],
                                    //             'status' => 1,
                                    //             'created_by' => Auth::user()->id,
                                    //             'last_updated_by' => Auth::user()->id,
                                    //             'updated_at' => date('Y-m-d H:i:s'),
                                    //             'created_at' => date('Y-m-d H:i:s')
                                    //         ]);
                                    //     }
                                    // }

                                return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
                            }
                            else{
                                return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                            }
                        }
                        catch(\Exception $e) {
                            // echo "test";
                            // echo "\r\n";
                            echo $e;
                            return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                        }
                    }else{
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => 'notEngineer']);
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

    /**
     * New action for machine only
     * 01-16-2023
     * -JD
     */
    public function save_process_flow_machine(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        if($request->ajax()){
            if(Auth::check()){
                if(isset($request->process_flow_id)){
                    $data = $request->all();
                    $rules = [
                        'process_flow_id' => 'required',
                        'machine_id' => 'required',
                    ];

                    $user_info = User::where('employee_id', $_SESSION['employee_id'])
                                ->where('status', 1)
                                ->whereIn('position', [9, 12]) // 9-Engineer, 12-Admin
                                ->get();
                    // return $user_info;

                    if(count($user_info) > 0){
                        // return $user_info;
                        $validator = Validator::make($data, $rules);
                        try {
                            if($validator->passes()){
                                $machine_id = "";
                                if(isset($request->machine_id)){
                                    for ($i=0; $i < count($request->machine_id); $i++)
                                        $machine_id .= $request->machine_id[$i] . ",";
                                    $machine_id = substr($machine_id, 0, -1);
                                }

                                // return $request->process_flow_id;
                                $processFlowDetails = ProcessFlow::where('id', $request->process_flow_id)
                                    ->where('status', 1)
                                    ->get();
                                // return $processFlowDetails;

                                // if($processFlowDetails[0]->approval_status  == 4 || $processFlowDetails[0]->approval_status == 5 || $processFlowDetails[0]->approval_status == 3){
                                if(count($processFlowDetails) > 0){
                                    ProcessFlow::where('id', $request->process_flow_id)
                                        ->where('status', 1)
                                        ->update([
                                            'machine_id' => $machine_id,
                                            'last_updated_by' => Auth::user()->id,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]
                                    );
                                }else{
                                    return response()->json(['auth' => 1, 'result' => 0, 'count'=>'0']);
                                }

                                return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
                            }
                            else{
                                return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                            }
                        }
                        catch(\Exception $e) {
                            // echo "test";
                            // echo "\r\n";
                            echo $e;
                            return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                        }
                    }else{
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => 'notEngineer']);
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

    public function get_process_flow_by_id(Request $request){
        // date_default_timezone_set('Asia/Manila');
        session_start();
        if($request->ajax()){
            if(Auth::check()){
                $data = [
                    'process_flow_id' => $request->process_flow_id,
                ];

                $rules = [
                    'process_flow_id' => 'required',
                ];

                $validator = Validator::make($data, $rules);

                if($validator->passes()){
                    $process_flow_info = ProcessFlow::with(['process_flow_substation_details'])
                        ->where('id', $request->process_flow_id)
                        ->first();

                    return response()->json(['auth' => 1, 'process_flow_info' => $process_flow_info, 'result' => 1]);
                }
                else{
                    return response()->json(['auth' => 1, 'process_flow_info' => null, 'result' => 0]);
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

    public function import_process_flow(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        if($request->ajax()){
            if(Auth::check()){
                // Import Process Flow
                $data = $request->all();

                $rules = [
                    'device_id' => 'required',
                    'file' => 'required',
                ];

                $validator = Validator::make($data, $rules);

                if($validator->passes()){
	                $result = array();
			        $collections = Excel::toCollection(new CSVProcessFlowImport, request()->file('file'));
			        $tbody = '';

			        DB::beginTransaction();
			        try{
			        	$data = [];
			        	$length = count($collections[2]);

			        	ProcessFlow::where('device_id', $request->device_id)
		            		// ->where('step', $step)
		            		->increment('update_version', 1,
		            			[
	                                // 'step' => $step,
	                                // 'process' => $data[$index],
	                            	'status' => 2,
	                                'last_updated_by' => Auth::user()->id,
	                                'updated_at' => date('Y-m-d H:i:s'),
	                        	]
	                    	);

			            for($index = 0; $index < 120; $index++){
			            	if($collections[2][$index][2] == null || $collections[2][$index][2] == 'null'){
			            	}
			            	else{
			            		$data[] = str_replace('↵', ' ', $collections[2][$index][2]);
			            	}
			            	if($collections[2][$index][6] == null || $collections[2][$index][6] == 'null'){
			            	}
			            	else{
			            		if($collections[2][$index][6] != 'Document No.'){
			            			$data[] = str_replace('↵', ' ', $collections[2][$index][6]);
			            		}
			            	}
			            }

			            // $steps = [];

			            for ($index = 0; $index < count($data); $index++) {
			            	$step =  str_replace('P', '', explode(' - ', $data[$index])[0]);

			            	$process_flow_info = ProcessFlow::where('device_id', $request->device_id)
			            		->where('step', $step)
			            		->first();

			            	if($process_flow_info != null){
			            		ProcessFlow::where('device_id', $request->device_id)
			            		->where('step', $step)
			            		->increment('update_version', 1,
			            			[
		                                'step' => $step,
		                                'process' => $data[$index],
	                                	'status' => 1,
		                                'last_updated_by' => Auth::user()->id,
		                                'updated_at' => date('Y-m-d H:i:s'),
	                            	]
	                        	);
			            	}
			            	else{
				            	ProcessFlow::insert([
	                                'device_id' => $request->device_id,
	                                'step' => $step,
	                                'process' => $data[$index],
	                                'status' => 1,
	                                'update_version' => 1,
	                                'created_by' => Auth::user()->id,
	                                'last_updated_by' => Auth::user()->id,
	                                'created_at' => date('Y-m-d H:i:s'),
	                                'updated_at' => date('Y-m-d H:i:s'),
	                            ]);
			            	}

			            }
			            // $result = $this->return_result(1,1,$tbody,1);
			            // $collection = collect($data)->flatten(1);

			            // $sorted = $collection->sort();
			            // $sorted = $sorted->values()->all();
			            // // return $sorted;
			            DB::commit();
			            return response()->json(['auth' => 1, 'result' => 1, 'error' => null, 'data' => $data]);
			        }
			        catch(\Exception $e) {
			        	DB::rollback();
		            	return response()->json(['auth' => 1, 'result' => 0, 'error' => null, 'data' => []]);
			        }
					// return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
                }
				else{
                    return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                }
            }
            else{
            	return response()->json(['auth' => 0, 'result' => 0, 'error' => null, 'data' => []]);
            }
        }
        else{
            abort(403);
        }
    }

    public function process_flow_action(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        if($request->ajax()){
            if(Auth::check()){
                // Add Process Flow
                $data = $request->all();

                $rules = [
                    'process_flow_id' => 'required',
                ];

                $validator = Validator::make($data, $rules);

                try {
                    if($validator->passes()){

                        ProcessFlow::where('id', $request->process_flow_id)
                                ->update([
                                    'status' => $request->status,
                            		'last_updated_by' => Auth::user()->id,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);
                        return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
                    }
                    else{
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                    }
                }
                catch(\Exception $e) {
                    return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                }
            }
        }
        else{
            abort(403);
        }
    }

    public function process_flow_for_prod(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        if($request->ajax()){
            if(Auth::check()){
                // Add Process Flow
                $data = $request->all();

                $rules = [
                    'process_flow_id' => 'required',
                    'status' => 'required',
                ];

                $validator = Validator::make($data, $rules);

                try {
                    if($validator->passes()){

                        ProcessFlow::where('id', $request->process_flow_id)
                                ->update([
                                    'for_prod' => $request->status,
                            		'last_updated_by' => Auth::user()->id,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);
                        return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
                    }
                    else{
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                    }
                }
                catch(\Exception $e) {
                    return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                }
            }
        }
        else{
            abort(403);
        }
    }

    public function get_process_flows(Request $request){
        // date_default_timezone_set('Asia/Manila');
        session_start();
        if($request->ajax()){
            if(Auth::check()){
            	$device_info = CommonController::getInstance()->getMatrixByPoNo(['po_no' => '', 'name' => $request->device_name, 'barcode' => '', 'get_or_first' => 'first']);
            	// $device_info = Device::where('name', $request->device_name)->first();

            	$data = [];

            	if($device_info != null){
                	$data = ProcessFlow::where('device_id', $device_info->id)
                	// ->orderBy('step', 'asc')
                	->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                	->where('for_prod', 1)
                	->where('status', 1)
                	->get();
            	}

                return response()->json(['auth' => 1, 'data' => $data, 'result' => 1]);
            }
            else{
                return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
            }
        }
        else{
            abort(403);
        }
    }

    public function change_process_flow_stat(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        // return $data;

        $validator = Validator::make($data, [
            'material_process_id' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
                DB::beginTransaction();

                try{
                    ProcessFlow::where('id', $request->material_process_id)
                    ->increment('update_version', 1,
                        [
                            'status' => $request->status,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    // throw $e;
                    return response()->json(['result' => $e]);
                }
        }
    }


    /**
     * Change approval status
     * 01-06-2023
     * JD
     */
    public function change_approval_status(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $data = $request->all();
        $rules = [
            'material_process_id' => ['required', 'numeric'],
            'approval_status' => ['required', 'numeric'],
        ];

        if($request->approval_status == 4 || $request->approval_status == 5){
            $rules['approval_remarks'] = 'required';
        }else{
            $rules['approval_remarks'] = '';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();
            try{

                if(isset($request->approval_remarks)){
                    ProcessFlow::where('id', $request->material_process_id)
                    ->update([
                                'approval_status' => $request->approval_status,
                                'approval_date' => date('Y-m-d H:i:s'),
                                'approval_remarks' => $request->approval_remarks,
                                'approved_by' => $_SESSION["employee_id"],
                            ]);
                }else{
                    ProcessFlow::where('id', $request->material_process_id)
                    ->update([
                                'approval_status' => $request->approval_status,
                                'approval_date' => date('Y-m-d H:i:s'),
                                'approved_by' => $_SESSION["employee_id"],
                            ]);
                }

                DB::commit();
                return response()->json(['result' => "1", 'approval_status' => $request->approval_status]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
        }
    }

    public function material_change_approval_status(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $data = $request->all();
        $rules = [
            'material_process_id' => ['required', 'numeric'],
            'material_approval_status' => ['required', 'numeric'],
        ];

        if($request->material_approval_status == 4 || $request->material_approval_status == 5){
            $rules['material_approval_remarks'] = 'required';
        }else{
            $rules['material_approval_remarks'] = '';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();
            try{

                if(isset($request->material_approval_remarks)){
                    ProcessFlow::where('id', $request->material_process_id)
                    ->update([
                                'material_approval_status' => $request->material_approval_status,
                                'material_approval_date' => date('Y-m-d H:i:s'),
                                'material_approval_remarks' => $request->material_approval_remarks,
                                'material_approved_by' => Auth::user()->employee_id,
                            ]);
                }else{
                    ProcessFlow::where('id', $request->material_process_id)
                    ->update([
                                'material_approval_status' => $request->material_approval_status,
                                'material_approval_date' => date('Y-m-d H:i:s'),
                                'material_approved_by' => Auth::user()->employee_id,
                            ]);
                }

                DB::commit();
                return response()->json(['result' => "1", 'material_approval_status' => $request->material_approval_status]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
        }
    }
}
