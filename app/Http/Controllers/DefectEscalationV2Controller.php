<?php

namespace App\Http\Controllers;

use Auth;
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
use Illuminate\Support\Facades\DB;
use App\Model\WBSSakidashiIssuance;
use App\Model\DefectImmediateAction;

use App\Model\DefectContainmentAction;
use App\Model\MaterialIssuanceDetails;
use App\Model\ProdRuncardMaterialList;
use App\Model\ProductionRuncardStation;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\DefectProblemVerification;
use App\Model\ProductionRuncardMaterial;
use Illuminate\Support\Facades\Validator;
use App\Model\ProductionRuncardStationMOD;
use App\Model\ProductionRuncardStationMachine;
use App\Model\ProductionRuncardStationMaterial;
use App\Model\ProductionRuncardStationOperator;
use App\Model\MaterialIssuanceSubSystem;//tbl_wbs_material_kitting




class DefectEscalationV2Controller extends Controller{

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

    public function get_runcard_with_defects_v2(Request $request){
            $runcard_no = DB::select("SELECT 
            TRIM(UPPER(a.runcard_no)) as runcard_no,
            a.id as runcard_id,
            a.pair_no,
            a.die_no,
            a.assembly_line_id,
            b.id as runcard_station_id,
            c.id as mod_station_id,
            c.mod_qty,
            d.name as defect_name
            FROM production_runcards a
            INNER JOIN production_runcard_stations2 b 
                ON a.id = b.production_runcard_id
            INNER JOIN prod_runcard_station_mods c 
                ON b.id = c.production_runcard_station_id
            INNER JOIN mode_of_defects d 
                ON c.mod_id = d.id
            WHERE a.po_no = ?
            AND b.qty_ng != 0  
            AND NOT EXISTS (
                SELECT 1
                FROM defect_escalation_v2 e
                WHERE e.prod_runcard_station_id = b.id
            )
            ORDER BY runcard_no ASC, b.id ASC, c.id ASC
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

        // return $defectRuncardDetails;

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
                else if($defectRuncardDetails->defect_classification == 3){
                    $result .= "SMPO Defect";
                }
                else if($defectRuncardDetails->defect_classification == 4){
                    $result .= "Set-up / Adjustment / Repair Defect";
                }
                else if($defectRuncardDetails->defect_classification == 5){
                    $result .= "Auto Inserter Defect";
                }else{
                    $result .= "Material Defect";
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

        if( $existingDefect == NULL) {
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
            
        }else{
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
                // 'line_id'               => 'required',
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
                // return $DEproblemverificationDetails;

                if (!$defectEscalation) {
                    return response()->json(['result' => "0", 'message' => 'Defect escalation not found.'], 404);
                }

                $DEproblemverificationDetails = DefectProblemVerification::where('de_id', $request->defect_id)->first();
                $DEimmediateActionDetails     = DefectImmediateAction::where('de_id', $request->defect_id)->first();
                $DEcontainmentActionDetails   = DefectContainmentAction::where('de_id', $request->defect_id)->first();

                $modeOfDefect = is_array($request->defect) ? implode(",", $request->defect) : $request->defect;
    
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
                    'de_position'             => $request->position,
                    'area1'                   => $request->area1,
                    'area2'                   => $request->area2,
                    'area3'                   => $request->area3,
                    'area4'                   => $request->area4,
                    'area5'                   => $request->area5,
                    'updated_by'              => Auth::user()->id,
                    'updated_at'              => now()
                ]);

                
                DefectProblemVerification::updateOrCreate(
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

                DefectImmediateAction::updateOrCreate(
                    ['de_id' => $request->defect_id], // lookup condition
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

                DefectContainmentAction::updateOrCreate(
                    ['de_id' => $request->defect_id], // lookup condition
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
                        'area1'                   => $request->area1,
                        'area2'                   => $request->area2,
                        'area3'                   => $request->area3,
                        'area4'                   => $request->area4,
                        'area5'                   => $request->area5,
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

    public function view_defect_details(Request $request){
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
    
        if($defectDetails){
            return response()->json([
                'data' => $defectDetails, 
                'status' => 'success', 
                'manUserDetails' => $manUserDetails,
                'machineUserDetails' => $machineUserDetails,
                'materialUserDetails' => $materialUserDetails,
                'methodUserDetails' => $methodUserDetails,
                'environmentUserDetails' => $environmentUserDetails
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

    
}



