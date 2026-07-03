<?php

namespace App\Http\Controllers;

// use Auth;
use Illuminate\Support\Facades\Auth;
use Mail;
use QrCode;
use App\User;
use DataTables;
use Carbon\Carbon;
use App\Model\Device;
use App\Model\Machine;
use App\Model\Station;
use App\Model\DERework;
use App\Model\oqcLotApp;
use App\Model\PartsPrep;
use App\Model\DEAnalysis;
use App\Model\DefectCapa;
use App\Model\DEStations;
use App\Model\SubStation;
use App\Model\DEMaterials;
use App\Model\AssemblyLine;
use App\Model\ModeOfDefect;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\WBSKitIssuance;
use App\Model\MaterialProcess;
use App\Model\RapidActiveDocs;
use App\Model\DefectEscalation;
use App\Model\MaterialIssuance;
use App\Model\DEStationMachines;
use App\Model\ProductionRuncard;
use App\Model\StationSubStation;
use App\Model\DefectEscalationV2;
use App\Model\DEStationOperators;
use App\Model\DefectReworkDetails;
use Illuminate\Support\Facades\DB;

use App\Model\WBSSakidashiIssuance;
use Illuminate\Support\Facades\Log;
use App\Model\DefectEscalationV2Rev;
use App\Model\DefectImmediateAction;
use App\Model\DefectImmediateActionV2;
use App\Model\DefectContainmentAction;
use App\Model\DefectContainmentActionV2;
use App\Model\MaterialIssuanceDetails;
use App\Model\ProdRuncardMaterialList;
use App\Model\ProductionRuncardStation;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\DefectProblemVerification;
use App\Model\DefectProblemVerificationV2;
use App\Model\ProductionRuncardMaterial;
use App\Model\DefectLevelTwoVerification;
use Illuminate\Support\Facades\Validator;
use App\Model\ProductionRuncardStationMOD;
use App\Model\DefectFinalDispositionDetails;
use App\Model\ProductionRuncardStationMachine;
use App\Model\DefectVerifyCauseAndEscapeeCause;
use App\Model\ProductionRuncardStationMaterial;
use App\Model\ProductionRuncardStationOperator;
use App\Model\DEVerificationAndConfirmationResult;
use App\Model\MaterialIssuanceSubSystem;//tbl_wbs_material_kitting




class DefectEscalationDarrenController extends Controller{

    public function get_assembly_lines_for_defect_escalation(Request $request){
        // return 'true';
        $assembly_lines = AssemblyLine::where('status', 1)
        ->whereRaw('LOWER(name) NOT LIKE ?', ['%probe pin%'])
        ->get();
        return response()->json(['assembly_lines' => $assembly_lines]);
    }

    public function get_defect_po_details(Request $request){
        // return $request->po_number;
        $po_details = MaterialIssuanceSubSystem::
        with(['material_issuance_details' => function($query){
            $query->limit(1);
        },
        ])
        // ->where('po_no', $request->po_number)
        ->where('po_no', $request->po_number)
        ->orderBy('id', 'desc')
        ->first();


        return response()->json(['data' => $po_details, 'status' => 'success']);
    }

    public function get_runcard_with_defects(Request $request){
        $runcard_no = DB::select("SELECT
        TRIM(UPPER(a.runcard_no)) as runcard_no,
        MIN(a.id) as runcard_id,
        MIN(a.pair_no) as pair_no,
        MIN(a.die_no) as die_no,
        MIN(a.assembly_line_id) as assembly_line_id,
        MIN(b.id) as runcard_station_id,
        MIN(c.id) as mod_station_id,
        MIN(c.mod_qty) as mod_qty,
        MIN(d.name) as name

        FROM production_runcards as a
        INNER JOIN production_runcard_stations2 as b ON a.id = b.production_runcard_id
        INNER JOIN prod_runcard_station_mods as c ON b.id = c.production_runcard_station_id
        INNER JOIN mode_of_defects as d ON c.mod_id = d.id

        WHERE a.po_no = ?
        AND b.qty_ng != 0
        AND NOT EXISTS (
            SELECT 1
            FROM defect_escalation_v2 e
            WHERE e.prod_runcard_station_id = b.id
        )

        GROUP BY TRIM(UPPER(a.runcard_no))
        ORDER BY runcard_no ASC
    ", [$request->po_number]);

        return response()->json(['data' => $runcard_no, 'status' => 'success']);
    }

    public function get_assembly_line_with_defect(Request $request){
        $assembly_line = AssemblyLine::where('id', $request->assembly_line_id)
        ->first();

        return $assembly_line;
    }

    public function get_defect_runcard_details(Request $request){
        // return $request->po_num;
        $defectRuncardDetails = DefectEscalationV2Rev::with(['operator_info','defect_description','production_runcard'])
        ->where('po_number', $request->po_num)
        ->get();

        // return $defectRuncardDetails; data-type

        return DataTables::of($defectRuncardDetails)
            ->addColumn('action', function($defectRuncardDetails){
                $defectCtrlNo = $defectRuncardDetails->defect_ctrl_txt ."-". $defectRuncardDetails->defect_ctrl_number;
                $result = "";
                $result .= "<center>";
                $result .= '<button style="width:30px; margin-top: 3px;" title="View" class="btn_view_defect_details btn btn-info btn-sm" data-defect-id="' . $defectRuncardDetails->id . '"><i class="fa fa-eye"></i></button>';
                $result .= '<button style="width:30px; margin-top: 3px; margin-left: 3px;" title="Edit" class="btn_edit_defect_details btn btn-warning btn-sm" data-defect-id="' . $defectRuncardDetails->id . '"><i class="fa fa-edit"></i></button>';
                $result .= ' <button style="width:30px;" defect-id="' . $defectRuncardDetails->id . '" defect-no="' . $defectCtrlNo . '" title="Print QR Code" class="btnPrintDefectCtrlNo btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

                $result .= "</center>";
                return $result;
            })
            ->addColumn('status', function($defectRuncardDetails){
                $result = "";
                // if ($defectRuncardDetails->defect_status == 0) {
                //     $result .= '<span class="badge bg-info text-dark">For Problem Verification</span>';
                // }
                // else if ($defectRuncardDetails->defect_status == 1) {
                //     $result .= '<span class="badge bg-info text-dark">For Immediate Action</span>';
                // }
                // else if ($defectRuncardDetails->defect_status == 2) {
                //     $result .= '<span class="badge bg-info text-dark">For Containment Action</span>';
                // }
                // else if ($defectRuncardDetails->defect_status == 3) {
                //     $result .= '<span class="badge bg-info text-dark">For Containment Action</span>';
                // }


                return $result;
            })
            ->addColumn('defect_ctrl', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->defect_ctrl_txt != null && $defectRuncardDetails->defect_ctrl_number != null){
                    $result .= $defectRuncardDetails->defect_ctrl_txt ."-". $defectRuncardDetails->defect_ctrl_number;
                }
                return $result;
            })
            ->addColumn('defect_classification', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->defect_classification == 1){
                    $result .= "Critical Defect";
                }
                else if($defectRuncardDetails->defect_classification == 2){
                    $result .= "Non-Critical Defect";
                }
                // else if($defectRuncardDetails->defect_classification == 3){
                //     $result .= "SMPO Defect";
                // }
                else if($defectRuncardDetails->defect_classification == 3){
                    $result .= "Set-up / Adjustment / Repair Defect";
                }
                else if($defectRuncardDetails->defect_classification == 4){
                    $result .= "Auto Inserter Defect";
                }else{
                    $result .= "NG Samples";
                }
                return $result;
            })
            ->addColumn('type_of_defect', function($defectRuncardDetails){
                $result = "";
                if ($defectRuncardDetails->mod_id) {
                    $ids = explode(',', $defectRuncardDetails->mod_id);
                    $names = ModeOfDefect::whereIn('id', $ids)->pluck('name')->toArray();
                    return implode(', ', $names);
                }else{
                    $result .= "N/A";
                }
                return $result;
            })
            ->addColumn('in_charge', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->de_user != null){
                    $user = User::where('id', $defectRuncardDetails->de_user)->first();
                    if($user != null){
                        $result .= $user->name;
                    }else{
                        $result .= "N/A";
                    }
                }else{
                    $result .= "N/A";
                }
                return $result;
            })
            ->addColumn('position', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->de_position == 1){
                    $result .= "Operator";
                }else if($defectRuncardDetails->de_position == 2){
                    $result .= "Material Handler";
                }
                else if($defectRuncardDetails->de_position == 3){
                    $result .= "QC inspector";
                }
                else if($defectRuncardDetails->de_position == 4){
                    $result .= "Technician";
                }
                return $result;
            })
            ->addColumn('shift', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->de_shift == 1){
                    $result .= "A";
                }else if($defectRuncardDetails->de_shift == 2){
                    $result .= "B";
                }
                return $result;
            })
            ->addColumn('station', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->de_station == 1){
                    $result .= 'Parts Preparation';
                }else if($defectRuncardDetails->de_station == 2){
                    $result .= 'Machine';
                }else if($defectRuncardDetails->de_station == 3){
                    $result .= 'Assembly';
                }else if($defectRuncardDetails->de_station == 4){
                    $result .= 'Visual Inspection';
                }else if($defectRuncardDetails->de_station == 5){
                    $result .= 'Setup / Adj.';
                }else if($defectRuncardDetails->de_station == 6){
                    $result .= 'Qualification';
                }else if($defectRuncardDetails->de_station == 7){
                    $result .= 'Others';
                }else{
                    $result .= 'N/A';
                }
                return $result;
            })
            ->addColumn('runcard_no', function($defectRuncardDetails){
                $result = "";
                $defectRuncardDetails->production_runcard->runcard_no != null ? $result .= $defectRuncardDetails->production_runcard->runcard_no : $result .= "N/A";
                return $result;
            })
            ->addColumn('datetime', function($defectRuncardDetails){
                // $defectRuncardDetails->created_at = Carbon::parse($defectRuncardDetails->created_at)->format('m/d/Y h:i A');
                $de_date = $defectRuncardDetails->de_date;
                $de_time = $defectRuncardDetails->de_time;
                $result = "";
                $result = $de_date . " / " . $de_time;

                return $result;

            })

            ->rawColumns(['action','status','defect_ctrl','defect_classification','type_of_defect','in_charge','position','shift','station','runcard_no','datetime'])
            ->make(true);
    }

    public function get_defect_runcard_details_new(Request $request){
        // return $request->po_num;
        $defectRuncardDetails = DefectEscalationV2Rev::with(['operator_info','defect_description','production_runcard','mode_of_defect'])
        ->where('po_number', $request->po_num)
        ->get();

        // return $defectRuncardDetails;

        return DataTables::of($defectRuncardDetails)
            ->addColumn('action', function($defectRuncardDetails){
                $defectCtrlNo = $defectRuncardDetails->defect_ctrl_txt ."-". $defectRuncardDetails->defect_ctrl_number;
                $result = "";
                $result .= "<center>";
                $result .= '<button style="width:30px; margin-top: 3px;" title="View" class="btn_view_defect_details btn btn-info btn-sm" data-defect-id="' . $defectRuncardDetails->id . '"
                data-status="' . $defectRuncardDetails->defect_status . '"
                data-verification="' . $defectRuncardDetails->de_verification_status . '"
                data-description="' . $defectRuncardDetails->detailed_description . '"
                data-occurence="' . $defectRuncardDetails->de_occurence . '"
                data-type="' . $defectRuncardDetails->defect_classification . '"
                ><i class="fa fa-eye"></i></button>';
                $result .= '<button style="width:30px; margin-top: 3px; margin-left: 3px;" title="Edit" class="btn_edit_defect_details btn btn-warning btn-sm"
                data-defect-id="' . $defectRuncardDetails->id . '"
                data-status="' . $defectRuncardDetails->defect_status . '"
                data-verification="' . $defectRuncardDetails->de_verification_status . '"
                data-description="' . $defectRuncardDetails->detailed_description . '"
                data-occurence="' . $defectRuncardDetails->de_occurence . '"
                data-type="' . $defectRuncardDetails->defect_classification . '"
                ><i class="fa fa-edit"></i></button>';
                $result .= ' <button style="width:30px;" defect-id="' . $defectRuncardDetails->id . '" defect-no="' . $defectCtrlNo . '" title="Print QR Code" class="btnPrintDefectCtrlNo btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

                $result .= "</center>";
                return $result;
            })
            ->addColumn('status', function($defectRuncardDetails){
                $result = "";
                // FAYE EDIT HERE
                if ($defectRuncardDetails->defect_status == 0) {
                    if($defectRuncardDetails->detailed_description == null || $defectRuncardDetails->detailed_description == ''){
                        $result .= '<span class="badge bg-warning text-dark">Incomplete Details</span>'.'</br>';
                        $result .= '<span class="badge bg-warning text-dark">For Update</span>';
                    }else{

                        $result .= '<span class="badge bg-info text-info">For Problem Verification</span>';
                    }
                }
                else if ($defectRuncardDetails->defect_status == 1) {
                    $result .= '<span class="badge bg-info text-info">For Immediate Action</span>';
                }
                else if ($defectRuncardDetails->defect_status == 2) {
                    $result .= '<span class="badge bg-info text-info">For Containment Action</span>';
                }
                else if ($defectRuncardDetails->defect_status == 3) {
                    $result .= '<span class="badge bg-info text-info">For Rework</span>';
                }else if ($defectRuncardDetails->defect_status == 4) {
                    $result .= '<span class="badge bg-info text-info">For Final Disposition</span>';
                }else if ($defectRuncardDetails->defect_status == 5) {
                    $result .= '<span class="badge bg-info text-info">For Root and Escapee Cause</span>';
                }else if ($defectRuncardDetails->defect_status == 6) {
                    $result .= '<span class="badge bg-info text-info">For Verification of Corrective/Preventive Action</span>';
                }else if ($defectRuncardDetails->defect_status == 7) {
                    $result .= '<span class="badge bg-info text-info">For Verification and Confirmation</span>';
                }else if ($defectRuncardDetails->defect_status == 8) {
                    $result .= '<span class="badge bg-success text-info">Completed</span>';
                }


                return $result;
            })
            ->addColumn('verification_status', function($defectRuncardDetails){
                $result = "";
                // FAYE EDIT HERE
                if ($defectRuncardDetails->de_verification_status == 'VERIFIED' && $defectRuncardDetails->defect_status == 0) {
                    $result .= '<span class="badge bg-warning text-white">Ongoing Verification</span>';

                }
                else if(
                    $defectRuncardDetails->defect_status == 1
                    || $defectRuncardDetails->defect_status == 2
                    || $defectRuncardDetails->defect_status == 3
                    || $defectRuncardDetails->defect_status == 4
                    || $defectRuncardDetails->defect_status == 5
                    || $defectRuncardDetails->defect_status == 6
                    || $defectRuncardDetails->defect_status == 7
                    || $defectRuncardDetails->defect_status == 8)
                {
                    $result .= '<span class="badge bg-success text-white">Verified</span>';
                }

                return $result;
            })
            ->addColumn('defect_ctrl', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->defect_ctrl_txt != null && $defectRuncardDetails->defect_ctrl_number != null){
                    $result .= $defectRuncardDetails->defect_ctrl_txt ."-". $defectRuncardDetails->defect_ctrl_number;
                }
                return $result;
            })
            ->addColumn('defect_classification', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->defect_classification == 1){
                    $result .= "Critical Defect";
                }
                else if($defectRuncardDetails->defect_classification == 2){
                    $result .= "Non-Critical Defect";
                }
                else if($defectRuncardDetails->defect_classification == 3){
                    $result .= "Set-up / Adjustment / Repair Defect";
                }
                else if($defectRuncardDetails->defect_classification == 4){
                    $result .= "Auto Inserter Defect";
                }else{
                    $result .= "NG Samples";
                }
                return $result;
            })
            ->addColumn('type_of_defect', function($defectRuncardDetails){
                $result = "";
                if ($defectRuncardDetails->mod_id) {
                    $ids = explode(',', $defectRuncardDetails->mod_id);
                    $names = ModeOfDefect::whereIn('id', $ids)->pluck('name')->toArray();
                    return implode(', ', $names);
                }else{
                    $result .= "N/A";
                }
                return $result;
            })
            ->addColumn('in_charge', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->de_user != null){
                    $user = User::where('id', $defectRuncardDetails->de_user)->first();
                    if($user != null){
                        $result .= $user->name;
                    }else{
                        $result .= "N/A";
                    }
                }else{
                    $result .= "N/A";
                }
                return $result;
            })
            ->addColumn('position', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->de_position == 1 || $defectRuncardDetails->de_position == 4){
                    $result .= "Operator";
                }else if($defectRuncardDetails->de_position == 3 || $defectRuncardDetails->de_position == 2){
                    $result .= "Material Handler";
                }
                else if($defectRuncardDetails->de_position == 10 || $defectRuncardDetails->de_position == 3){
                    $result .= "QC inspector";
                }
                else if($defectRuncardDetails->de_position == 11){
                    $result .= "Technician";
                }
                return $result;
            })
            ->addColumn('shift', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->de_shift == 1){
                    $result .= "A";
                }else if($defectRuncardDetails->de_shift == 2){
                    $result .= "B";
                }
                return $result;
            })
            ->addColumn('station', function($defectRuncardDetails){
                $result = "";
                if($defectRuncardDetails->de_station == 1){
                    $result .= 'Parts Preparation';
                }else if($defectRuncardDetails->de_station == 2){
                    $result .= 'Machine';
                }else if($defectRuncardDetails->de_station == 3){
                    $result .= 'Assembly';
                }else if($defectRuncardDetails->de_station == 4){
                    $result .= 'Visual Inspection';
                }else if($defectRuncardDetails->de_station == 5){
                    $result .= 'Setup / Adj.';
                }else if($defectRuncardDetails->de_station == 6){
                    $result .= 'Qualification';
                }else if($defectRuncardDetails->de_station == 7){
                    $result .= 'Others';
                }else{
                    $result .= 'N/A';
                }
                return $result;
            })
            ->addColumn('runcard_no', function($defectRuncardDetails){
                $result = "";
                $defectRuncardDetails->production_runcard->runcard_no != null ? $result .= $defectRuncardDetails->production_runcard->runcard_no : $result .= "N/A";
                return $result;
            })
            ->addColumn('datetime', function($defectRuncardDetails){
                // $defectRuncardDetails->created_at = Carbon::parse($defectRuncardDetails->created_at)->format('m/d/Y h:i A');
                $de_date = $defectRuncardDetails->de_date;
                $de_time = $defectRuncardDetails->de_time;
                $result = "";
                $result = $de_date . " / " . $de_time;

                return $result;

            })
            // ->addColumn('defect_count', function($defectRuncardDetails) {
            //     // 1. Get all mod_id strings from the relationship
            //     // This returns an array/collection like ["6", "6", "6,10"]
            //     $rawModIds = $defectRuncardDetails->pluck('mod_id');

            //     $tally = [];

            //     // 2. Loop through each string and split them manually
            //     foreach ($rawModIds as $idString) {
            //         $ids = explode(',', $idString); // Split "6,10" into ["6", "10"]

            //         foreach ($ids as $id) {
            //             $trimmedId = trim($id);
            //             if ($trimmedId !== '') {
            //                 // If ID already in tally, increment it; otherwise set to 1
            //                 if (isset($tally[$trimmedId])) {
            //                     $tally[$trimmedId]++;
            //                 } else {
            //                     $tally[$trimmedId] = 1;
            //                 }
            //             }
            //         }
            //     }

            //     // $tally is now ['6' => 3, '10' => 1]

            //     if (empty($tally)) {
            //         return "";
            //     }

            //     // 3. Fetch names for the IDs found
            //     $names = ModeOfDefect::whereIn('id', array_keys($tally))
            //         ->pluck('name', 'id');

            //     // 4. Build the final output string
            //     $display = [];
            //     foreach ($tally as $id => $qty) {
            //         $name = isset($names[$id]) ? $names[$id] : "Unknown (#$id)";
            //         $display[] = $qty . ' ' . $name;
            //     }

            //     return implode(', ', $display); // Result: "3 Scratched, 1 Dented"
            // })
            ->rawColumns(['action','status','verification_status','defect_ctrl','defect_classification','type_of_defect','in_charge','position','shift','station','runcard_no','datetime'])
            ->make(true);
    }

    public function add_defect_escalation(Request $request)
    {

        date_default_timezone_set('Asia/Manila');

        $existingDefect = DefectEscalationV2Rev::orderBy('id', 'desc')->first();

        // return  $existingDefect;

        $ts = "TSF1";
        $line = $request->line_t;
        preg_match('/\(([^)]+)\)/', $line, $matches);
        $defectLine = $matches[1] ?? '';


        // return $defectLine;
        // return 'return';

        if( $existingDefect == NULL) {
            // $defectClassification = $request->
            // if($request->mod_type == 1){
            //     $defectCtrltxt = $ts.'-'."EM"."-". date('m') . date('y');
            //     $defectCtrlNumber = '0001';
            // }
            // else{
                if($request->d_classification == 1){
                    $defect_text = "A1";
                }
                else if($request->d_classification == 2){
                    $defect_text = "A2";
                }
                else if($request->d_classification == 3){
                    $defect_text = "A3";
                }
                else if($request->d_classification == 4){
                    $defect_text = "A4";
                }
                else if($request->d_classification == 5){
                    $defect_text = "A5";
                }
                $defectCtrltxt = $ts.'-'.$defectLine.'-'.$defect_text ."-". date('m') . date('y');
            // }
            $defectCtrlNumber = '0001';

        }
        else{
            // if($request->mod_type == 1){
            //     $defectCtrltxt = $ts.'-'."EM"."-". date('m') . date('y');
            //     // $defectCtrlNumber = '0001';
            // }else{
                if($request->d_classification == 1){
                    $defect_text = "A1";
                }
                else if($request->d_classification == 2){
                    $defect_text = "A2";
                }
                else if($request->d_classification == 3){
                    $defect_text = "A3";
                }
                else if($request->d_classification == 4){
                    $defect_text = "A4";
                }
                else if($request->d_classification == 5){
                    $defect_text = "A5";
                }
                $defectCtrltxt = $ts.'-'.$defectLine.'-'.$defect_text ."-". date('m') . date('y');
            // }
            $lastCtrlNumber = $existingDefect->defect_ctrl_number;
            $lastDefectCtrlNumber = explode('-', $lastCtrlNumber);
            $lastDefectCtrlNumberSuffix = end($lastDefectCtrlNumber);
            $nextCtrlNumberSuffix = str_pad((int)$lastDefectCtrlNumberSuffix + 1, 4, '0', STR_PAD_LEFT);
            $defectCtrlNumber = $nextCtrlNumberSuffix;
        }

        // return $defectCtrltxt."-".$defectCtrlNumber;

        $data = $request->all();
        // return $request->area1;
        // return $data;
            $validator = Validator::make($data, [
                'po_number'             => 'required',
                // 'mod_type'              => 'required',
                'runcard_id'            => 'required',
                'runcard_station_id'    => 'required',
                'runcard_station_mod_id'=> 'required',
                'd_classification'      => 'required',
                'detailed_description'  => 'required',
                'defect'                => 'required',
                'date'                  => 'required|date',
                'shift'                 => 'required|in:1,2',
                'line'                  => 'required',
                'd_mold_no'             => 'nullable|string|max:255',
                'lot_no'                => 'nullable|string|max:255',
                'pair_no'               => 'nullable|string|max:255',
                'occurrence'            => 'nullable|string|max:255',
                'station'               => 'nullable|string|max:255',
                'select_user'           => 'required|exists:users,id',
                'position'              => 'required|in:1,2,3,4',
            ]);
        // }


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            if ($request->defect_id) {
                // return 'update';
                // UPDATE
                $defectEscalation = DefectEscalationV2Rev::find($request->defect_id);

                if (!$defectEscalation) {
                    return response()->json(['result' => "0", 'message' => 'Defect escalation not found.'], 404);
                }

                $modeOfDefect = is_array($request->defect) ? implode(",", $request->defect) : $request->defect;
                $area1 = is_array($request->area1) ? implode(",", $request->area1) : $request->area1;
                $area2 = is_array($request->area2) ? implode(",", $request->area2) : $request->area2;
                $area3 = is_array($request->area3) ? implode(",", $request->area3) : $request->area3;
                $area4 = is_array($request->area4) ? implode(",", $request->area4) : $request->area4;
                $area5 = is_array($request->area5) ? implode(",", $request->area5) : $request->area5;

                $defectEscalation->update([
                    'po_number'               => $request->po_number,
                    'defect_classification'   => $request->d_classification,
                    'detailed_description'    => $request->detailed_description,
                    'defect_qty'              => $request->defect_qty,
                    'mod_id'                  => $modeOfDefect,
                    'de_date'                 => $request->date,
                    'de_shift'                => $request->shift,
                    // 'line_id'                 => $request->line_id,
                    'line_id'                    => $request->line,
                    'line'                    => $request->line_t,
                    'de_dm_no'                => $request->d_mold_no,
                    'de_lot_no'               => $request->lot_no,
                    'de_pair_no'              => $request->pair_no,
                    'de_occurence'            => $request->occurrence,
                    'de_station'              => $request->station,
                        'de_other_station'        => $request->other_station,
                    'de_user'                 => $request->select_user,
                    // 'de_position'             => $request->position,
                    'area1'                   => $area1,
                    'area2'                   => $area2,
                    'area3'                   => $area3,
                    'area4'                   => $area4,
                    'area5'                   => $area5,
                    'updated_by'              => Auth::user()->id,
                    'updated_at'              => now()
                ]);


                DefectProblemVerificationV2::updateOrCreate(
                    // 🔎 Search condition
                    ['de_id' => $request->defect_id],

                    // 📝 Values to update (or insert if not found)
                    [
                        'vr_prod_result'        => $request->p_verification_result,
                        'vr_prod_user'          => $request->p_verification_user_id,
                        'vr_prod_date'          => $request->p_verification_date,
                        'vr_prod_remarks'       => $request->p_verification_remarks,
                        'vr_engr_result'        => $request->e_verification_result,
                        'vr_engr_user'          => $request->e_verification_user_id,
                        'vr_engr_date'          => $request->e_verification_date,
                        'vr_engr_remarks'       => $request->e_verification_remarks,
                        'vr_qc_result'          => $request->q_verification_result,
                        'vr_qc_user'            => $request->q_verification_user_id,
                        'vr_qc_date'            => $request->q_verification_date,
                        'vr_qc_remarks'         => $request->q_verification_remarks,
                        'd_prod_result'         => $request->p_disposition,
                        'd_prod_user'           => $request->p_disposition_user_id,
                        'd_prod_date'           => $request->p_disposition_date,
                        'd_prod_remarks'        => $request->p_disposition_remarks,
                        'd_engr_result'         => $request->e_disposition,
                        'd_engr_user'           => $request->e_disposition_user_id,
                        'd_engr_date'           => $request->e_disposition_date,
                        'd_engr_remarks'        => $request->e_disposition_remarks,
                        'd_qc_result'           => $request->q_disposition,
                        'd_qc_user'             => $request->q_disposition_user_id,
                        'd_qc_date'             => $request->q_disposition_date,
                        'd_qc_remarks'          => $request->q_disposition_remarks,
                        'updated_by'            => Auth::user()->id,
                        'updated_at'            => now()
                    ]
                );

                $user_man = is_array($request->select_user_man) ? implode(",", $request->select_user_man) : $request->select_user_man;
                $user_machine = is_array($request->select_user_machine) ? implode(",", $request->select_user_machine) : $request->select_user_machine;
                $user_material = is_array($request->select_user_material) ? implode(",", $request->select_user_material) : $request->select_user_material;
                $user_method = is_array($request->select_user_method) ? implode(",", $request->select_user_method) : $request->select_user_method;
                $user_environment = is_array($request->select_user_environment) ? implode(",", $request->select_user_environment) : $request->select_user_environment;

                // return $user_man;

                DefectImmediateActionV2::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'at_man_action_details'         => $request->action_details_man,
                        'at_machine_action_details'     => $request->action_details_machine,
                        'at_material_action_details'    => $request->action_details_material,
                        'at_method_action_details'      => $request->action_details_method,
                        'at_environment_action_details' => $request->action_details_environment,
                        'at_man_date'                   => $request->when_man,
                        'at_machine_date'               => $request->when_machine,
                        'at_material_date'              => $request->when_material,
                        'at_method_date'                => $request->when_method,
                        'at_environment_date'           => $request->when_environment,
                        'at_man_user'                   => $user_man,
                        'at_machine_user'               => $user_machine,
                        'at_material_user'              => $user_material,
                        'at_method_user'                => $user_method,
                        'at_environment_user'           => $user_environment,
                        'updated_by'                    => Auth::id(),
                        'updated_at'                    => now(),
                    ]
                );

                DefectContainmentActionV2::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'wip_total_qty' => $request->wip_total_qty,
                        'wip_ok_qty'    => $request->wip_ok_qty,
                        'wip_ng_qty'    => $request->wip_ng_qty,
                        'wip_mod'       => $request->wip_mod,
                        'wip_remarks'   => $request->wip_remarks,

                        'pf_total_qty'  => $request->pf_total_qty,
                        'pf_ok_qty'     => $request->pf_ok_qty,
                        'pf_ng_qty'     => $request->pf_ng_qty,
                        'pf_mod'        => $request->pf_mod,
                        'pf_remarks'    => $request->pf_remarks,

                        'qc_total_qty'  => $request->oqc_total_qty,
                        'qc_ok_qty'     => $request->oqc_ok_qty,
                        'qc_ng_qty'     => $request->oqc_ng_qty,
                        'qc_mod'        => $request->oqc_mod,
                        'qc_remarks'    => $request->oqc_remarks,

                        'p_total_qty'   => $request->packing_total_qty,
                        'p_ok_qty'      => $request->packing_ok_qty,
                        'p_ng_qty'      => $request->packing_ng_qty,
                        'p_mod'         => $request->packing_mod,
                        'p_remarks'     => $request->packing_remarks,

                        's_total_qty'   => $request->shipped_total_qty,
                        's_ok_qty'      => $request->shipped_ok_qty,
                        's_ng_qty'      => $request->shipped_ng_qty,
                        's_mod'         => $request->shipped_mod,
                        's_remarks'     => $request->shipped_remarks,

                        'updated_by'    => Auth::id(),
                        'updated_at'    => now(),
                    ]
                );

            }

            else {

                $modeOfDefect = is_array($request->defect) ? implode(",", $request->defect) : $request->defect;
                $area1 = is_array($request->area1) ? implode(",", $request->area1) : $request->area1;
                $area2 = is_array($request->area2) ? implode(",", $request->area2) : $request->area2;
                $area3 = is_array($request->area3) ? implode(",", $request->area3) : $request->area3;
                $area4 = is_array($request->area4) ? implode(",", $request->area4) : $request->area4;
                $area5 = is_array($request->area5) ? implode(",", $request->area5) : $request->area5;

                    DefectEscalationV2Rev::insert([
                        'po_number'               => $request->po_number,
                        'mod_type'                => $request->mod_type,
                        'defect_ctrl_txt'         => $defectCtrltxt,
                        'defect_ctrl_number'      => $defectCtrlNumber,
                        'prod_runcard_id'         => $request->runcard_id,
                        'prod_runcard_station_id' => $request->runcard_station_id,
                        'prod_runcard_station_mod_id' => $request->runcard_station_mod_id,
                        'defect_classification'   => $request->d_classification,
                        'detailed_description'    => $request->detailed_description,
                        'defect_qty'              => $request->defect_qty,
                        'mod_id'                  => $modeOfDefect,
                        'de_date'                 => $request->date,
                        'de_time'                 => date('H:i:s'),
                        'de_shift'                => $request->shift,
                        // 'line_id'                 => $request->line_id,
                        'line_id'                 => $request->line,
                        'line'                    => $request->line_t,
                        'de_dm_no'                => $request->d_mold_no,
                        'de_lot_no'               => $request->lot_no,
                        'de_pair_no'              => $request->pair_no,
                        'de_occurence'            => $request->occurrence,
                        'de_station'              => $request->station,
                        'de_other_station'        => $request->other_station,
                        'de_user'                 => $request->select_user,
                        'de_position'             => $request->position,
                        'area1'                   => $area1,
                        'area2'                   => $area2,
                        'area3'                   => $area3,
                        'area4'                   => $area4,
                        'area5'                   => $area5,
                        'defect_status'               => 0, // For Problem Verification
                        'created_by'              => Auth::user()->id,
                        'created_at'              => now()
                    ]);
                // }

            }

            DB::commit();
            return response()->json(['result' => "1"]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['result' => "0", 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function add_defect_escalation_test(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        // return $data;
        DB::beginTransaction();

        try {
            if ($request->defect_id) {
                // return 'update';
                $defectEscalation = DefectEscalationV2Rev::findOrFail($request->defect_id);

                // return $defectEscalation;

                if (!$defectEscalation) {
                    return response()->json(['result' => "0", 'message' => 'Defect escalation not found.'], 404);
                }


                $modeOfDefect = is_array($request->defect) ? implode(",", $request->defect) : $request->defect;

                $area1 = is_array($request->area1) ? implode(",", $request->area1) : $request->area1;
                $area2 = is_array($request->area2) ? implode(",", $request->area2) : $request->area2;
                $area3 = is_array($request->area3) ? implode(",", $request->area3) : $request->area3;
                $area4 = is_array($request->area4) ? implode(",", $request->area4) : $request->area4;
                $area5 = is_array($request->area5) ? implode(",", $request->area5) : $request->area5;

                $reworkActivities = is_array($request->rework_activity) ? implode(",", $request->rework_activity) : $request->rework_activity;
                $replacedPartname = is_array($request->r_part_name) ? implode(",", $request->r_part_name) : $request->r_part_name;
                $replacedPartLotNo = is_array($request->r_lot_no) ? implode(",", $request->r_lot_no) : $request->r_lot_no;

                // return $request->other_station;

                $defectEscalation->update([
                    'po_number'               => $request->po_number,
                    'defect_classification'   => $request->d_classification,
                    'detailed_description'    => $request->detailed_description,
                    'defect_qty'              => $request->defect_qty,
                    'mod_id'                  => $modeOfDefect,
                    'de_date'                 => $request->date,
                    'de_shift'                => $request->shift,
                    // 'line_id'              => $request->line_id,
                    'line_id'                 => $request->line,
                    'line'                    => $request->line_t,
                    'de_dm_no'                => $request->d_mold_no,
                    'de_lot_no'               => $request->lot_no,
                    'de_pair_no'              => $request->pair_no,
                    'de_occurence'            => $request->occurrence,
                    'de_station'              => $request->station,
                    'de_other_station'        => $request->other_station,
                    'de_user'                 => $request->select_user,
                    'de_position'             => $request->position,
                    'area1'                   => $area1,
                    'area2'                   => $area2,
                    'area3'                   => $area3,
                    'area4'                   => $area4,
                    'area5'                   => $area5,
                    'updated_by'              => Auth::user()->id,
                    'updated_at'              => now()
                ]);

                // return $defectEscalation->id;

                    $dpv = DefectProblemVerificationV2::updateOrCreate(
                        ['de_id' => $request->defect_id],
                        [
                            // PRODUCTION Verification
                            'vr_prod_result'  => $request->p_verification_result,
                            'vr_prod_user'    => $request->p_verification_user_id,
                            'vr_prod_date'    => $request->p_verification_date,
                            'vr_prod_remarks' => $request->p_verification_remarks,

                            // ENGINEERING Verification
                            'vr_engr_result'  => $request->e_verification_result,
                            'vr_engr_user'    => $request->e_verification_user_id,
                            'vr_engr_date'    => $request->e_verification_date,
                            'vr_engr_remarks' => $request->e_verification_remarks,

                            // QC Verification
                            'vr_qc_result'    => $request->q_verification_result,
                            'vr_qc_user'      => $request->q_verification_user_id,
                            'vr_qc_date'      => $request->q_verification_date,
                            'vr_qc_remarks'   => $request->q_verification_remarks,

                            // PRODUCTION Disposition
                            'd_prod_result'   => $request->p_disposition,
                            'd_prod_user'     => $request->p_disposition_user_id,
                            'd_prod_date'     => $request->p_disposition_date,
                            'd_prod_remarks'  => $request->p_disposition_remarks,

                            // ENGINEERING Disposition
                            'd_engr_result'   => $request->e_disposition,
                            'd_engr_user'     => $request->e_disposition_user_id,
                            'd_engr_date'     => $request->e_disposition_date,
                            'd_engr_remarks'  => $request->e_disposition_remarks,

                            // QC Disposition
                            'd_qc_result'     => $request->q_disposition,
                            'd_qc_user'       => $request->q_disposition_user_id,
                            'd_qc_date'       => $request->q_disposition_date,
                            'd_qc_remarks'    => $request->q_disposition_remarks,

                            'updated_by'      => Auth::user()->id,
                        ]
                    );

//                     dd([
//     'id' => $dpv->id,
//     'de_id' => $dpv->de_id,
//     'wasRecentlyCreated' => $dpv->wasRecentlyCreated,
// ]);

                $dpv = DefectProblemVerificationV2::where('de_id', $request->defect_id)->first();

                $dpvRequiredFields = [
                    'vr_prod_result','vr_prod_user','vr_prod_date',
                    'vr_engr_result','vr_engr_user','vr_engr_date',
                    'vr_qc_result','vr_qc_user','vr_qc_date',
                    'd_prod_result','d_prod_user','d_prod_date',
                    'd_engr_result','d_engr_user','d_engr_date',
                    'd_qc_result','d_qc_user','d_qc_date',
                ];

                $allComplete = true;
                foreach ($dpvRequiredFields as $field) {
                    if (is_null($dpv->$field) || $dpv->$field === '') {
                        $allComplete = false;
                        break;
                    }
                }

                if ($allComplete && $defectEscalation->defect_status == 0) {
                    $defectEscalation->update([
                        'defect_status' => 1, // For Immediate Action
                        'updated_by'    => Auth::id(),
                    ]);
                    $defectEscalation->refresh();
                }


                $user_man = is_array($request->select_user_man) ? implode(",", $request->select_user_man) : $request->select_user_man;
                $user_machine = is_array($request->select_user_machine) ? implode(",", $request->select_user_machine) : $request->select_user_machine;
                $user_material = is_array($request->select_user_material) ? implode(",", $request->select_user_material) : $request->select_user_material;
                $user_method = is_array($request->select_user_method) ? implode(",", $request->select_user_method) : $request->select_user_method;
                $user_environment = is_array($request->select_user_environment) ? implode(",", $request->select_user_environment) : $request->select_user_environment;

                // return $user_man;

                DefectImmediateActionV2::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'at_man_action_details'         => $request->action_details_man,
                        'at_machine_action_details'     => $request->action_details_machine,
                        'at_material_action_details'    => $request->action_details_material,
                        'at_method_action_details'      => $request->action_details_method,
                        'at_environment_action_details' => $request->action_details_environment,
                        'at_man_date'                   => $request->when_man,
                        'at_machine_date'               => $request->when_machine,
                        'at_material_date'              => $request->when_material,
                        'at_method_date'                => $request->when_method,
                        'at_environment_date'           => $request->when_environment,
                        'at_man_user'                   => $user_man,
                        'at_machine_user'               => $user_machine,
                        'at_material_user'              => $user_material,
                        'at_method_user'                => $user_method,
                        'at_environment_user'           => $user_environment,
                        'updated_by'                    => Auth::id(),
                        'updated_at'                    => now(),
                    ]
                );

                $dia = DefectImmediateActionV2::where('de_id', $request->defect_id)->first();

                $diaRequiredFields = [
                    'at_man_action_details','at_machine_action_details','at_material_action_details',
                    'at_method_action_details','at_environment_action_details',
                    'at_man_date','at_machine_date','at_material_date','at_method_date','at_environment_date',
                    'at_man_user','at_machine_user','at_material_user','at_method_user','at_environment_user',
                ];

                $allComplete = true;
                foreach ($diaRequiredFields as $field) {
                    if (is_null($dia->$field) || $dia->$field === '') {
                        $allComplete = false;
                        break;
                    }
                }

                if ($allComplete && $defectEscalation->defect_status == 1) {
                    $defectEscalation->update([
                        'defect_status' => 2, // For Containment Action
                        'updated_by'    => Auth::id(),
                    ]);
                    $defectEscalation->refresh();
                }

                  DefectContainmentActionV2::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'wip_total_qty' => $request->wip_total_qty,
                        'wip_ok_qty'    => $request->wip_ok_qty,
                        'wip_ng_qty'    => $request->wip_ng_qty,
                        'wip_mod'       => $request->wip_mod,
                        'wip_remarks'   => $request->wip_remarks,

                        'pf_total_qty'  => $request->pf_total_qty,
                        'pf_ok_qty'     => $request->pf_ok_qty,
                        'pf_ng_qty'     => $request->pf_ng_qty,
                        'pf_mod'        => $request->pf_mod,
                        'pf_remarks'    => $request->pf_remarks,

                        'qc_total_qty'  => $request->oqc_total_qty,
                        'qc_ok_qty'     => $request->oqc_ok_qty,
                        'qc_ng_qty'     => $request->oqc_ng_qty,
                        'qc_mod'        => $request->oqc_mod,
                        'qc_remarks'    => $request->oqc_remarks,

                        'p_total_qty'   => $request->packing_total_qty,
                        'p_ok_qty'      => $request->packing_ok_qty,
                        'p_ng_qty'      => $request->packing_ng_qty,
                        'p_mod'         => $request->packing_mod,
                        'p_remarks'     => $request->packing_remarks,

                        's_total_qty'   => $request->shipped_total_qty,
                        's_ok_qty'      => $request->shipped_ok_qty,
                        's_ng_qty'      => $request->shipped_ng_qty,
                        's_mod'         => $request->shipped_mod,
                        's_remarks'     => $request->shipped_remarks,

                        'updated_by'    => Auth::id(),
                        'updated_at'    => now(),
                    ]
                );

                $dca = DefectContainmentActionV2::where('de_id', $request->defect_id)->first();

                $dcaRequiredFields = [
                    'wip_total_qty','wip_ok_qty','wip_ng_qty','wip_mod','wip_remarks',
                    'pf_total_qty','pf_ok_qty','pf_ng_qty','pf_mod','pf_remarks',
                    'qc_total_qty','qc_ok_qty','qc_ng_qty','qc_mod','qc_remarks',
                    'p_total_qty','p_ok_qty','p_ng_qty','p_mod','p_remarks',
                    's_total_qty','s_ok_qty','s_ng_qty','s_mod','s_remarks',
                ];

                $allComplete = true;
                foreach ($dcaRequiredFields as $field) {
                    if (is_null($dca->$field) || $dca->$field === '') {
                        $allComplete = false;
                        break;
                    }
                }

                if ($allComplete && $defectEscalation->defect_status == 2) {
                    $defectEscalation->update([
                        'defect_status' => 3, // For Rework
                        'updated_by'    => Auth::id(),
                    ]);
                    $defectEscalation->refresh();
                }

                // return $defectEscalation->defect_status;

                DefectReworkDetails::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'reworker_user_id1'         => $request->reworker_operator_id1,
                        'reworker_user_id2'         => $request->reworker_operator_id2,
                        'rework_date'               => $request->rework_date,
                        'unit1_result'              => $request->rework1,
                        'unit2_result'              => $request->rework2,
                        'unit3_result'              => $request->rework3,
                        'unit4_result'              => $request->rework4,
                        'unit5_result'              => $request->rework5,
                        'rework_activity'           => $reworkActivities,
                        'rework_act_others'         => $request->rework_activity_others,
                        'rework_mpc_checking'       => $request->mpc_check,
                        'activity_after_rework'     => $request->activity_after_rework,
                        'rework_replace_parts'      => $replacedPartname,
                        'rework_lot_no'             => $replacedPartLotNo,
                        'updated_by'                => Auth::id(),
                        'updated_at'                => now(),
                    ]
                );


                $rework = DefectReworkDetails::where('de_id', $request->defect_id)->first();

                $reworkRequiredFields = [
                    'reworker_user_id1','reworker_user_id2','rework_date',
                    'rework_activity','rework_act_others','rework_mpc_checking',
                    'activity_after_rework','rework_replace_parts','rework_lot_no',
                ];

                $allComplete = true;
                foreach ($reworkRequiredFields as $field) {
                    if (is_null($rework->$field) || $rework->$field === '') {
                        $allComplete = false;
                        break;
                    }
                }

                if ($allComplete && $defectEscalation->defect_status == 3) {
                    $defectEscalation->update([
                        'defect_status' => 4, // For Final Disposition
                        'updated_by'    => Auth::id(),
                    ]);
                    $defectEscalation->refresh();
                }

                DefectFinalDispositionDetails::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'f_disposition'                 => $request->f_dispostion_result,
                        'engr_manager_id'               => $request->d_engr_manager_id,
                        'engr_manager_date'             => $request->d_engr_manager_date,
                        'process_engr_id'               => $request->d_p_engr_id,
                        'process_engr_date'             => $request->d_p_engr_date,
                        'f_disposition_engr_remarks'    => $request->f_engr_dispostion_remarks,
                        'f_disposition_qc'              => $request->f_qc_dispostion_result,
                        'qc_manager_id'                 => $request->d_qc_manager_id,
                        'qc_manager_date'               => $request->d_qc_manager_date,
                        'qc_supervisor_id'              => $request->d_qc_id,
                        'qc_supervisor_date'            => $request->d_qc_date,
                        'f_disposition_qc_remarks'      => $request->f_qc_dispostion_remarks,
                        'updated_by'                    => Auth::id(),
                        'updated_at'                    => now(),
                    ]
                );

                $finalDisposition = DefectFinalDispositionDetails::where('de_id', $request->defect_id)->first();

                $finalRequiredFields = [
                    'f_disposition','engr_manager_id','engr_manager_date',
                    'process_engr_id','process_engr_date','f_disposition_engr_remarks',
                    'f_disposition_qc','qc_manager_id','qc_manager_date',
                    'qc_supervisor_id','qc_supervisor_date','f_disposition_qc_remarks',
                ];

                $allComplete = true;
                foreach ($finalRequiredFields as $field) {
                    if (is_null($finalDisposition->$field) || $finalDisposition->$field === '') {
                        $allComplete = false;
                        break;
                    }
                }

                if ($allComplete && $defectEscalation->defect_status == 4) {
                    $defectEscalation->update([
                        'defect_status' => 5, // For root and escapee cause
                        'updated_by'    => Auth::id(),
                    ]);
                    $defectEscalation->refresh();
                }

                DefectVerifyCauseAndEscapeeCause::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'rc_ec'                         => $request->root_cause_escapee,
                        'updated_by'                    => Auth::id(),
                        'updated_at'                    => now(),
                    ]
                );

                $rootAndEscapeeCause = DefectVerifyCauseAndEscapeeCause::where('de_id', $request->defect_id)->first();

                // return $rootAndEscapeeCause;

                $rootAndEscapeeCauseRequiredFields = [
                    'rc_ec',
                ];

                $allComplete = true;
                foreach ($rootAndEscapeeCauseRequiredFields as $field) {
                    if (is_null($rootAndEscapeeCause->$field) || $rootAndEscapeeCause->$field === '') {
                        $allComplete = false;
                        break;
                    }
                }

                if ($allComplete && $defectEscalation->defect_status == 5) {
                    $defectEscalation->update([
                        'defect_status' => 6, // CHOOSE & VERIFY PERMANENT CORRECTIVE/PREVENT ACTION FOR ROOT CAUSE & ESCAPEE CAUSE
                        'updated_by'    => Auth::id(),
                    ]);
                    $defectEscalation->refresh();
                }

                // return $data;

                $capa_user = is_array($request->capa_user) ? implode(",", $request->capa_user) : $request->capa_user;
                $capa_action = is_array($request->capa_action) ? implode(",", $request->capa_action) : $request->capa_action;
                $capa_due_date = is_array($request->capa_due_date) ? implode(",", $request->capa_due_date) : $request->capa_due_date;

                DefectCapa::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'de_capa'            => $capa_action,
                        'de_pic'             => $capa_user,
                        'de_due_date'        => $capa_due_date,
                        'updated_by'        => Auth::id(),
                        'updated_at'        => now(),
                    ]
                );

                $capa = DefectVerifyCauseAndEscapeeCause::where('de_id', $request->defect_id)->first();

                // return $capa;

                $capaRequiredFields = [
                    'de_capa','de_pic','de_due_date',
                ];

                $allComplete = true;
                foreach ($capaRequiredFields as $field) {
                    if (is_null($capa->$field) || $capa->$field === '') {
                        $allComplete = false;
                        break;
                    }
                }

                if ($allComplete && $defectEscalation->defect_status == 6) {
                    $defectEscalation->update([
                        'defect_status' => 7, // CHOOSE & VERIFY PERMANENT CORRECTIVE/PREVENT ACTION FOR ROOT CAUSE & ESCAPEE CAUSE
                        'updated_by'    => Auth::id(),
                    ]);
                    $defectEscalation->refresh();
                }

                DEVerificationAndConfirmationResult::updateOrCreate(
                    ['de_id' => $request->defect_id],
                    [
                        'action_stated'                  => $request->action_stated,
                        'prodblem_reoccur'               => $request->problem_reoccur,
                        'actions_documented'             => $request->derived_actions_documented,
                        'confirmation_result'            => $request->result_confirmation,
                        'remarks'                        => $request->verification_remarks,
                        'qc_inspector_id'                => $request->vc_inspector_user_id,
                        'qc_inspector_date'              => $request->vc_inspector_date,
                        'qc_supervisor_id'               => $request->vc_supervisor_user_id,
                        'qc_supervisor_date'             => $request->vc_supervisor_date,
                        'qc_manager_id'                  => $request->vc_manager_user_id,
                        'qc_manager_date'                => $request->vc_manager_date,
                        'updated_by'                     => Auth::id(),
                        // 'created_at'                     => now(),
                        'updated_at'                     => now(),
                    ]
                );

                $verificationAndConfirmation = DEVerificationAndConfirmationResult::where('de_id', $request->defect_id)->first();

                // return $verificationAndConfirmation;

                $verificationAndConfirmationRequiredFields = [
                    'action_stated','prodblem_reoccur','actions_documented',
                    'confirmation_result','remarks','qc_inspector_id',
                    'qc_inspector_date','qc_supervisor_id','qc_supervisor_date',
                    'qc_manager_id','qc_manager_date',
                ];

                $allComplete = true;
                foreach ($verificationAndConfirmationRequiredFields as $field) {
                    if (is_null($verificationAndConfirmation->$field) || $verificationAndConfirmation->$field === '') {
                        $allComplete = false;
                        break;
                    }
                }

                if ($allComplete && $defectEscalation->defect_status == 7) {
                    $defectEscalation->update([
                        'defect_status' => 8, // CHOOSE & VERIFY PERMANENT CORRECTIVE/PREVENT ACTION FOR ROOT CAUSE & ESCAPEE CAUSE
                        'updated_by'    => Auth::id(),
                    ]);
                    $defectEscalation->refresh();
                }


            }else {
            // return 'true';
                $modeOfDefect = is_array($request->defect) ? implode(",", $request->defect) : $request->defect;
                $area1 = is_array($request->area1) ? implode(",", $request->area1) : $request->area1;
                $area2 = is_array($request->area2) ? implode(",", $request->area2) : $request->area2;
                $area3 = is_array($request->area3) ? implode(",", $request->area3) : $request->area3;
                $area4 = is_array($request->area4) ? implode(",", $request->area4) : $request->area4;
                $area5 = is_array($request->area5) ? implode(",", $request->area5) : $request->area5;

                    DefectEscalationV2Rev::insert([
                        'po_number'               => $request->po_number,
                        'mod_type'                => $request->mod_type,
                        'defect_ctrl_txt'         => $defectCtrltxt,
                        'defect_ctrl_number'      => $defectCtrlNumber,
                        'prod_runcard_id'         => $request->runcard_id,
                        'prod_runcard_station_id' => $request->runcard_station_id,
                        'prod_runcard_station_mod_id' => $request->runcard_station_mod_id,
                        'defect_classification'   => $request->d_classification,
                        'detailed_description'    => $request->detailed_description,
                        'defect_qty'              => $request->defect_qty,
                        'mod_id'                  => $modeOfDefect,
                        'de_date'                 => $request->date,
                        'de_time'                 => date('H:i:s'),
                        'de_shift'                => $request->shift,
                        'line_id'                 => $request->line,
                        'line'                    => $request->line_t,
                        'de_dm_no'                => $request->d_mold_no,
                        'de_lot_no'               => $request->lot_no,
                        'de_pair_no'              => $request->pair_no,
                        'de_occurence'            => $request->occurrence,
                        'de_station'              => $request->station,
                        'de_other_station'        => $request->other_station,
                        'de_user'                 => $request->select_user,
                        'de_position'             => $request->position,
                        'area1'                   => $area1,
                        'area2'                   => $area2,
                        'area3'                   => $area3,
                        'area4'                   => $area4,
                        'area5'                   => $area5,
                        'defect_status'               => 'FOR PROBLEM VERIFICATION', // For Problem Verification
                        'created_by'              => Auth::user()->id,
                        'created_at'              => now()
                    ]);
                // }

            }

            // return 'commit';
            DB::commit();
            return response()->json(['result' => "1"]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['result' => "0", 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function view_defect_details(Request $request){
        return $request->defect_id;
        $defectDetails = DefectEscalationV2Rev::with([
            'operator_info',
            'defect_description',
            'assembly_line',
            'problem_verificaiton_details',
            'problem_verificaiton_details.prod_verification_user',
            'problem_verificaiton_details.engr_verification_user',
            'problem_verificaiton_details.qc_verification_user',
            'problem_verificaiton_details.d_prod_user',
            'problem_verificaiton_details.d_engr_user',
            'problem_verificaiton_details.d_qc_user',
            'immediate_action_details',
            'containment_action_details',
            'rework_details',
            'rework_details.reworker_operator1',
            'rework_details.reworker_operator2',
            'de_level_two_verification_details',
            'de_level_two_verification_details.prod_lvl_two_user',
            'de_level_two_verification_details.engr_lvl_two_user',
            'de_level_two_verification_details.qc_lvl_two_user',
            'de_level_two_verification_details.ppc_lvl_two_user',
            'de_level_two_verification_details.ta_lvl_two_user',
            'final_disposition_details',
            'final_disposition_details.final_disposition_e_manager',
            'final_disposition_details.final_disposition_e_process',
            'final_disposition_details.final_disposition_qc_manager',
            'final_disposition_details.final_disposition_qc_supervisor',
            'root_escapee_cause_details',
            // 'immediate_action_details.get_user_man',
            'production_runcard' => function($query) {
                $query->select('id', 'runcard_no', 'assembly_line_id');
            },

            ])
        ->where('id', $request->defect_id)
        ->first();

        $manUserDetails = [];
        $machineUserDetails = [];
        $materialUserDetails = [];
        $methodUserDetails = [];
        $environmentUserDetails = [];
        $capaUserDetails = [];
        $getCapaDetails = [];

        $defectImmediateActionDetails = DefectImmediateAction::where('de_id', $request->defect_id)->first();

        if ($defectImmediateActionDetails) {
            $manUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_man_user))
                ->pluck('name', 'id')
                ->toArray();

            $machineUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_machine_user))
                ->pluck('name', 'id')
                ->toArray();

            $materialUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_material_user))
                ->pluck('name', 'id')
                ->toArray();

            $methodUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_method_user))
                ->pluck('name', 'id')
                ->toArray();

            $environmentUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_environment_user))
                ->pluck('name', 'id')
                ->toArray();
        }

        $defectCapaDetails = DefectCapa::where('de_id', $request->defect_id)->first();

        if ($defectCapaDetails) {
            $de_capa = explode(',', $defectCapaDetails->de_capa);
            $de_due_date = explode(',', $defectCapaDetails->de_due_date);
            $de_pic_groups = explode(',', $defectCapaDetails->de_pic);

            // return gettype($de_pic_groups);

            $capaUserDetails = User::whereIn('id', explode(',', $defectCapaDetails->de_pic))
                ->pluck('name', 'id')
                ->toArray();

                // return $capaUserDetails;

            foreach ($de_capa as $index => $capa) {
                $picIdsRaw = $de_pic_groups[$index] ?? [];

                if (is_string($picIdsRaw)) {
                    $decoded = json_decode($picIdsRaw, true);
                    if (is_array($decoded)) {
                        $picIds = $decoded;
                    } else {
                        $picIds = array_map('trim', explode(',', $picIdsRaw));
                    }
                } elseif (is_array($picIdsRaw)) {
                    $picIds = $picIdsRaw;
                } else {
                    $picIds = [];
                }

                $picUsers = User::whereIn('id', $picIds)->pluck('name', 'id')->toArray();

                // return $picUsers;

                $getCapaDetails[] = [
                    'de_capa' => trim($capa),
                    'de_due_date' => isset($de_due_date[$index]) ? trim($de_due_date[$index]) : '',
                    'de_pic_ids' => $picIds,
                    'de_pic_users' => $picUsers,
                ];
            }
        }

        // return $getCapaDetails;

        if($defectDetails){
            return response()->json([
                'data' => $defectDetails,
                'status' => 'success',
                'manUserDetails' => $manUserDetails,
                'machineUserDetails' => $machineUserDetails,
                'materialUserDetails' => $materialUserDetails,
                'methodUserDetails' => $methodUserDetails,
                'environmentUserDetails' => $environmentUserDetails,
                'capaUserDetails' => $capaUserDetails,
                'capaDetails' => $getCapaDetails,
            ]);
        }else{
            return response()->json(['data' => null, 'status' => 'error']);
        }
    }

    public function view_defect_details_new(Request $request){
        // return $request->defect_id;
        $defectDetails = DefectEscalationV2Rev::with([
            'operator_info',
            'defect_description',
            'assembly_line',
            'problem_verificaiton_details',
            'problem_verificaiton_details.prod_verification_user',
            'problem_verificaiton_details.engr_verification_user',
            'problem_verificaiton_details.qc_verification_user',
            'problem_verificaiton_details.d_prod_user',
            'problem_verificaiton_details.d_engr_user',
            'problem_verificaiton_details.d_qc_user',
            'immediate_action_details',
            'containment_action_details',
            'rework_details',
            'rework_details.reworker_operator1',
            'rework_details.reworker_operator2',
            'final_disposition_details',
            'final_disposition_details.final_disposition_e_manager',
            'final_disposition_details.final_disposition_e_process',
            'final_disposition_details.final_disposition_qc_manager',
            'final_disposition_details.final_disposition_qc_supervisor',
            'root_escapee_cause_details',
            'verification_and_confirmation_details',
            'verification_and_confirmation_details.qc_inspector',
            'verification_and_confirmation_details.qc_supervisor',
            'verification_and_confirmation_details.qc_manager',
            // 'immediate_action_details.get_user_man',
            'production_runcard' => function($query) {
                $query->select('id', 'runcard_no', 'assembly_line_id');
            },

            ])
        ->where('id', $request->defect_id)
        ->first();
        


        $manUserDetails = [];
        $machineUserDetails = [];
        $materialUserDetails = [];
        $methodUserDetails = [];
        $environmentUserDetails = [];
        $capaUserDetails = [];
        $getCapaDetails = [];

        $defectImmediateActionDetails = DefectImmediateActionV2::where('de_id', $request->defect_id)->first();

        // return $defectImmediateActionDetails;

        if ($defectImmediateActionDetails) {
            $manUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_man_user))
                ->pluck('name', 'id')
                ->toArray();

            $machineUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_machine_user))
                ->pluck('name', 'id')
                ->toArray();

            $materialUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_material_user))
                ->pluck('name', 'id')
                ->toArray();

            $methodUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_method_user))
                ->pluck('name', 'id')
                ->toArray();

            $environmentUserDetails = User::whereIn('id', explode(',', $defectImmediateActionDetails->at_environment_user))
                ->pluck('name', 'id')
                ->toArray();
        }

        $defectCapaDetails = DefectCapa::where('de_id', $request->defect_id)->first();

        if ($defectCapaDetails) {
            $de_capa = explode(',', $defectCapaDetails->de_capa);
            $de_due_date = explode(',', $defectCapaDetails->de_due_date);
            $de_pic_groups = explode(',', $defectCapaDetails->de_pic);


             $capaUserDetails = User::whereIn('id', explode(',', $defectCapaDetails->de_pic))
                ->pluck('name', 'id')
                ->toArray();
            // return gettype($de_pic_groups);

            foreach ($de_capa as $index => $capa) {
                $picIdsRaw = $de_pic_groups[$index] ?? [];

                if (is_string($picIdsRaw)) {
                    $decoded = json_decode($picIdsRaw, true);
                    if (is_array($decoded)) {
                        $picIds = $decoded;
                    } else {
                        $picIds = array_map('trim', explode(',', $picIdsRaw));
                    }
                } elseif (is_array($picIdsRaw)) {
                    $picIds = $picIdsRaw;
                } else {
                    $picIds = [];
                }

                $picUsers = User::whereIn('id', $picIds)->pluck('name', 'id')->toArray();

                // return $picUsers;

                $getCapaDetails[] = [
                    'de_capa' => trim($capa),
                    'de_due_date' => isset($de_due_date[$index]) ? trim($de_due_date[$index]) : '',
                    'de_pic_ids' => $picIds,
                    'de_pic_users' => $picUsers,
                ];
            }
        }

        if($defectDetails){
            return response()->json([
                'data' => $defectDetails,
                'manUserDetails' => $manUserDetails,
                'machineUserDetails' => $machineUserDetails,
                'materialUserDetails' => $materialUserDetails,
                'methodUserDetails' => $methodUserDetails,
                'environmentUserDetails' => $environmentUserDetails,
                'status' => 'success',
                'capaUserDetails' => $capaUserDetails,
                'capaDetails' => $getCapaDetails,
            ]);
        }else{
            return response()->json(['data' => null, 'status' => 'error']);
        }

    }

    public function generate_defect_qrcode(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = DefectEscalationV2Rev::with(['defect_description'])->where('id', $request->defect_id)->first();

        $defectCtrlNo = $data->defect_ctrl_txt.'-'.$data->defect_ctrl_number;

        // return $data->po_number;

        $po_qrcode = "";

        if($data->count() > 0){
                $po_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($data->po_number);

                $defect_ctrl_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            // ->generate($data->defect_escalation_no .' | '.$data->sub_station_id);
                            ->generate($defectCtrlNo);

            $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);

            $defect_ctrl_qrcode = "data:image/png;base64," . base64_encode($defect_ctrl_qrcode);
        }

        // return $defect_ctrl_qrcode;

        $product_name = MaterialIssuanceSubSystem::where('po_no', $data->po_number)->get();

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

        // return $device_name_print;

        // return response()->json(['data' => $data, 'po_qrcode' => $po_qrcode, 'defect_ctrl_qrcode' => $defect_ctrl_qrcode]);
        return response()->json(['data' => $data, 'po_qrcode' => $po_qrcode, 'defect_ctrl_qrcode' => $defect_ctrl_qrcode, 'device_name_print' => $device_name_print]);
        // return response()->json(['data' => $data]);
    }

    public function get_verification_user_ongoing(Request $request){
        // return $request->defect_id;
        $user = User::where('employee_id', $request->scanned_verification_user)->first();


        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        DefectEscalationV2Rev::where('id', $request->defect_id)
            ->update([
                'de_verification_status' => 'VERIFIED',
                'updated_by'             => auth()->id(),
                'updated_at'             => now(),
            ]);

        return response()->json([
            'success' => true,
            'user'    => $user
        ]);
    }

    public function get_defect_count(Request $request) {
        $groups = DefectEscalationV2Rev::where('po_number', $request->po_number)
            ->where('defect_classification', 2)
            ->select('de_shift', 'line_id')
            ->groupBy('de_shift', 'line_id')
            ->get();

        $finalResult = [];

        foreach ($groups as $groupRow) {
            // 2. Fetch all mod_id strings for this specific Shift and Line
            $rawModIds = DefectEscalationV2Rev::where('po_number', $request->po_number)
                ->where('de_shift', $groupRow->de_shift)
                ->where('line_id', $groupRow->line_id)
                ->pluck('mod_id');

            $tally = [];

            // 3. Split and Tally IDs for THIS Shift/Line only
            foreach ($rawModIds as $idString) {
                if (empty($idString)) continue;

                $ids = explode(',', $idString);
                foreach ($ids as $id) {
                    $trimmedId = trim($id);
                    if ($trimmedId !== '') {
                        $tally[$trimmedId] = isset($tally[$trimmedId]) ? $tally[$trimmedId] + 1 : 1;
                    }
                }
            }

            // 4. Map IDs to Names for this specific Group
            if (!empty($tally)) {
                $names = ModeOfDefect::whereIn('id', array_keys($tally))->pluck('name', 'id');

                $formattedDefects = [];
                foreach ($tally as $id => $qty) {
                    $name = isset($names[$id]) ? $names[$id] : "Unknown (#$id)";

                    $formattedDefects[] = [
                        'mod_details_count' => $name.'-'.$qty,
                    ];
                }

                // 5. Push the result with the Line and Shift info
                $finalResult[] = [
                    'line_id'  => $groupRow->line_id,
                    'de_shift' => $groupRow->de_shift,
                    'defects'  => $formattedDefects
                ];
            }
        }

        return response()->json(['data' => $finalResult]);
    }


}



