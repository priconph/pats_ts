<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Device;
use App\Model\oqcLotApp;
use App\Model\Machine;
use App\Model\MaterialProcess;
use App\Model\StationSubStation;
use App\Model\Station;
use App\Model\SubStation;
use Illuminate\Http\Request;
use App\Model\MaterialIssuance;
use App\Model\MaterialIssuanceSubSystem;//tbl_wbs_material_kitting
use App\Model\WBSKitIssuance;
use App\Model\ProductionRuncard;
use App\Model\ProductionRuncardStation;
use App\Model\PartsPrep;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Model\WBSSakidashiIssuance;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\MaterialIssuanceDetails;
use App\Model\ProdRuncardMaterialList;
use App\Model\RapidActiveDocs;
use App\Model\ProductionRuncardStationMOD;
use App\Model\ProductionRuncardStationMaterial;
use App\Model\ProductionRuncardStationOperator;
use App\Model\ProductionRuncardStationMachine;
use App\Model\ProductionRuncardMaterial;
use Auth;
use QrCode;
use DataTables;
use Carbon\Carbon;

class ProductionRuncardController extends Controller
{
    //
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
        $devices = Device::where('barcode',$request->device_code)->get();

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

        $prod_runcardstations = ProductionRuncardStation::with([
                'station' => function($query){
                    $query->where('status', 1);
                },
                'sub_station' => function($query){
                    $query->where('status', 1);
                },
                // 'operator_info',
                // 'machine_info',
                // 'machine_details',
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
                // 'prod_runcard_station_material_details',
            ])
            ->where('production_runcard_id', $request->prod_runcard_id_query )
            ->get();

        return DataTables::of($prod_runcardstations)
            ->setRowId('id')
            ->addColumn('raw_action', function($prod_runcardstation) use ($prod_runcard_status, $prod_runcard_no){
                $disabled = '';
                $prod_runcard_id = $prod_runcardstation->production_runcard_id;

                if(count($prod_runcardstation->prod_runcard_station_operator_details) > 0){
                    $disabled = '';
                    // if($has_emboss == 1){
                        // $disabled = 'disabled';

                        // if($prod_runcardstation->has_emboss == 0){
                        //     $disabled = 'disabled';
                        // }
                        // else{

                            if($prod_runcard_status == 7 || $prod_runcard_status == 3 || $prod_runcard_status == 4){
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
                            }
                            else{
                                $disabled = 'disabled';   
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
                            if($prod_runcard_status == 2){
                                if($prod_runcardstation->status == 1){
                                    $disabled = 'disabled';
                                }
                            }
                            else{
                                if($prod_runcardstation->status == 1){
                                    $disabled = 'disabled';
                                }
                                else{
                                    $disabled = '';
                                }
                            }
                        // }
                    // }
                }
                else{
                    $disabled = 'disabled';
                }

                $disabled = '';

                if($prod_runcardstation->status != 0){
                    $disabled = 'disabled';
                }


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

                // if($has_emboss == 1){
                //     // $disabled = 'disabled';

                //     if($prod_runcardstation->has_emboss == 0){
                //         $disabled = 'disabled';
                //     }
                //     else{

                //         if($prod_runcard_status == 7){
                //             // Add Validation for Emboss
                            
                //             // $disabled = '';
                //             if($prod_runcardstation->require_oqc_before_emboss == 1){
                //                 $oqc_lot_app = oqcLotApp::with([
                //                                             'oqcvir_details_for_emboss' => function($query){
                //                                                 // $query->orderBy('submission','desc');
                //                                                 $query->where('judgement', 1);
                //                                                 // $query->limit(1);
                //                                             },
                //                                         ])
                //                                         ->where('lot_batch_no', $prod_runcard_no)
                //                                         ->get();
                //                 // return $oqc_lot_app;
                //                 if(count($oqc_lot_app) > 0){
                //                     if($oqc_lot_app[0]->oqcvir_details_for_emboss != null){
                //                         $disabled = '';

                //                         if($prod_runcardstation->status == 1){
                //                             $disabled = 'disabled';
                //                         }
                //                     }
                //                     else{
                //                         $disabled = 'disabled';
                //                     }
                //                 }
                //                 else{
                //                     $disabled = 'disabled';
                //                 }
                //             }
                //             else{
                //                 $disabled = '';
                //             }
                //         }
                //         else{
                //             $disabled = 'disabled';   
                //         }   
                //             // return $prod_runcard_no;
                //             // return $oqc_lot_app;

                //     }
                //     // $oqc_lot_app = oqcLotApp::with([
                //     //     'oqcvir_details' => function($query){
                //     //         // $query->orderBy('submission','desc');
                //     //     },
                //     //     'production_runcard_info' => function($query) use ($prod_runcard_id){
                //     //         $query->where('status', '7');
                //     //         $query->where('id', $prod_runcard_id);
                //     //     }])
                //     //     // ->where('production_runcard_id', $prod_runcard_id)
                //     //     ->orderBy('submission','desc')
                //     //     ->get();

                //     // return $oqc_lot_app;

                //     // $oqc_vir_result = ProductionRuncard::with([
                //     //                                 'oqc_details',
                //     //                                 'oqc_details.oqcvir_details'
                //     //                         ])
                //     //                     // ->where('id', 1)
                //     //                     ->where('id', $prod_runcardstation->production_runcard_id)
                //     //                     ->get();

                //     // return $oqc_vir_result;

                // }
                // else{
                //     if($prod_runcardstation->has_emboss == 1){
                //         if($has_emboss == 1){
                //             if($prod_runcardstation->status == 1){
                //                 $disabled = 'disabled';
                //             }
                //             else if($prod_runcardstation->status == 0){
                //                 $disabled = '';
                //             }
                //         }
                //         else{
                //             $disabled = 'disabled';
                //         }
                //     }
                //     else{
                //         if($prod_runcard_status == 2){
                //             if($prod_runcardstation->status == 1){
                //                 $disabled = 'disabled';
                //             }
                //         }
                //         else{
                //             if($prod_runcardstation->status == 1){
                //                 $disabled = 'disabled';
                //             }
                //             else{
                //                 $disabled = '';
                //             }
                //         }
                //     }
                // }

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

            $device = Device::where('barcode', $request->txt_device_code)->first();


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
        $prod_runcard_stations = ProductionRuncardStation::
            with([
                'station',
                'sub_station',
                'operator_info',
            ])
            ->where(
                [
                    [
                        'deleted_at','=',
                        null
                    ],
                    [
                        'id','=',$request['col_prod_runcard_station_id']
                    ]
                ]
            )
            ->get();
        if($prod_runcard_stations->isEmpty()){
            $prod_runcard_stations = array();
        }

        $device = Device::where('barcode', $request->device_code)->first();

        $material_process =  MaterialProcess::with([
                'manpowers_details' => function($query){
                    $query->where('status', 1);
                },
                'machine_details' => function($query){
                    $query->where('status', 1);
                },
                'material_details' => function($query){
                    $query->where('status', 1);
                },
            ])
            ->where('id', $request->material_process_id)
            // ->where('status', 1)
            ->first();

        return response()->json(['prod_runcard_stations' => $prod_runcard_stations, 'material_process' => $material_process]);
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

        if($tbl_sakidashi_kitting->count() > 0){
            $po_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($tbl_sakidashi_kitting[0]->po_no);

            $ctrl_no_qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($tbl_sakidashi_kitting[0]->tbl_wbs_sakidashi_issuance_item->lot_no);

            $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);
            $ctrl_no_qrcode = "data:image/png;base64," . base64_encode($ctrl_no_qrcode);
        }

        return response()->json(['tbl_sakidashi_kitting' => $tbl_sakidashi_kitting, 'po_qrcode' => $po_qrcode, 'ctrl_no_qrcode' => $ctrl_no_qrcode]);
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

    public function get_prod_runcard_by_po(Request $request){
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
                                $query->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'));
                                $query->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'));
                                // $query->limit(1);
                            }
                        ])
                        ->where('po_no', $request->po_number)
                        ->get();
        
        return DataTables::of($prod_runcards)
            ->addColumn('raw_action', function($prod_runcard){

                $result='<button style="width:30px;" runcard-id="' . $prod_runcard->id . '" title="Open details" class="btnOpenRuncardDetails btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';

                $result .= ' <button style="width:30px;" runcard-id="' . $prod_runcard->id . '" runcard-no="' . $prod_runcard->runcard_no . '" title="Print QR Code" class="btnPrintRuncardNo btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';

                // $result .= ' <button style="width:30px;" runcard-id="' . $prod_runcard->id . '" title="Print C3 Label Sticker" class="btnPrintRuncardC3Label btn btn-success btn-sm py-0"><i class="fa fa-print fa-sm"></i></button>';
                return $result;
            })
            ->addColumn('raw_status', function($prod_runcard){
                $result = '<i title="Ongoing" class="far fa-clock text-secondary px-2"></i>';

                if($prod_runcard->status == 3){
                    $result = '<i title="Submitted to OQC Lot App" class="fas fa-check text-success px-2"></i>';
                }

                // if($prod_runcard->status == 3){
                //     $result = '<i title="For Setup Stations" class="far fa-clock text-secondary px-2""></i>';
                // }
                // else if($prod_runcard->status == 4){
                //     $result = '<i title="For Verification" class="far fa-clock text-secondary px-2""></i>';
                // }
                // else if($prod_runcard->status == 5){
                //     $result = '<i title="For Prod\'n Approval" class="far fa-clock text-secondary px-2""></i>';
                // }
                // else if($prod_runcard->status == 6){
                //     $result = '<i title="For QC Approval" class="far fa-clock text-secondary px-2""></i>';
                // }
                // else if($prod_runcard->status == 7 || $prod_runcard->status == 8){
                //     $result = '<i title="Done" class="fas fa-check text-success px-2"></i>';
                // }

                // if($prod_runcard->has_emboss == 1){
                //     if($prod_runcard->status == 8){
                //         $result .= '<i title="Emboss - Done" class="fas fa-toilet-paper text-success px-2"></i>';
                //     }
                //     else{
                //         $result .= '<i title="Emboss - Pending" class="fas fa-toilet-paper text-secondary px-2"></i>';
                //     }
                // }

                return $result;
            })
            ->addColumn('total_no_of_ng', function($prod_runcard){
                $total_no_of_ng = null;

                if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                    $total_no_of_ng = 0;
                    for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
                        // if($prod_runcard->prod_runcard_station_many_details[$index]->has_emboss == 0){
                            $total_no_of_ng += $prod_runcard->prod_runcard_station_many_details[$index]->qty_ng;   
                        // }
                    }
                }

                return $total_no_of_ng;
            })
            // ->addColumn('total_no_of_emboss_ng', function($prod_runcard){
            //     $total_no_of_emboss_ng = null;

            //     if(count($prod_runcard->prod_runcard_station_many_details) > 0){
            //         $total_no_of_emboss_ng = 0;
            //         for($index = 0; $index < count($prod_runcard->prod_runcard_station_many_details); $index++){
            //             if($prod_runcard->prod_runcard_station_many_details[$index]->has_emboss == 1){
            //                 $total_no_of_emboss_ng += $prod_runcard->prod_runcard_station_many_details[$index]->qty_ng;   
            //             }
            //         }
            //     }

            //     return $total_no_of_emboss_ng;
            // })
            ->addColumn('last_runcard_po_qty', function($prod_runcard){
                $col_prod_runcard = null;

                // if($prod_runcard->require_oqc_before_emboss == 1){
                //     if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                //         $total_no_of_emboss_ng = 0;
                        
                //         $col_prod_runcard = collect($prod_runcard->prod_runcard_station_many_details)->where('has_emboss', 0)->sortByDesc('step_num')->slice(0, 1)->flatten(1);
                //     }
                // }
                // else{
                $qty_output = 0;
                    if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                        $total_no_of_emboss_ng = 0;
                        
                        $col_prod_runcard = collect($prod_runcard->prod_runcard_station_many_details)->sortByDesc('step_num')->slice(0, 1)->flatten(1);
                        $qty_output = $col_prod_runcard->pluck('qty_output')[0];
                    }
                // }

                // return $total_no_of_emboss_ng;


                // if($col_prod_runcard > 0){
                // }
                return $qty_output;
            })
            ->rawColumns(['raw_action', 'raw_status', 'total_no_of_ng', 'last_runcard_po_qty'])
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
                                        ->where('po_no', $request->po_number)
                                        ->first();                              
        $doc_details_query = [];
        $doc_a_drawing_query = [];
        $doc_g_drawing_query = [];

        if($material_kitting != null){
            $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $material_kitting->device_name . '%')
                ->get();
            $doc_a_drawing_query = collect($doc_details_query)->where('doc_type', 'A Drawing')->flatten(1);
            $doc_g_drawing_query = collect($doc_details_query)->where('doc_type', 'G Drawing')->flatten(1);
        }

        return response()->json(['material_kitting' => $material_kitting, 'doc_a_drawing_query' => $doc_a_drawing_query, 'doc_g_drawing_query' => $doc_g_drawing_query]);
    }

    public function get_wbs_sakidashi_issuance(Request $request){
        date_default_timezone_set('Asia/Manila');
        $sakidashi_issuance = WBSSakidashiIssuance::where('po_no', $request->po_number)
                                        ->first();
        
        return response()->json(['sakidashi_issuance' => $sakidashi_issuance]);
    }

    public function get_prod_runcard_by_id(Request $request){
        date_default_timezone_set('Asia/Manila');
        $prod_runcard = ProductionRuncard::with([
                                'prod_runcard_material_list' => function($query){
                                    $query->where('status', 1);
                                },
                                'supervisor_prod_info',
                                'supervisor_qc_info',
                            ])
                            ->where('id', $request->prod_runcard_id)
                            ->first();
        
        return response()->json(['prod_runcard' => $prod_runcard]);
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


                            $mat_proc_materials = Device::with([
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

                                $mat_proc_materials = Device::with([
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
                                        ])
                                        ->where('name', $request->device_name)
                                        ->first();

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

                $mat_proc_materials = Device::with([
                            'material_process' => function($query){
                                $query->where('status', 1);
                            },
                            'material_process.manpowers_details' => function($query) use($user){
                                $query->where('manpower_id', $user->id);
                            },
                        ])
                        ->where('name', $request->device_name)
                        ->first();

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

                // if(count($prod_runcard_station_detail_ids) > 0){
                //     $final_prod_runcard_station_details = $prod_runcard_station_details->whereIn('id', $prod_runcard_station_detail_ids);

                //     DB::beginTransaction();
                //     try {
                //         $certified_counter = 0;
                //         for($index = 0; $index < count($final_prod_runcard_station_details); $index++){
                //             if($final_prod_runcard_station_details[$index]->operator == null){
                //                 ProductionRuncardStation::where('id', $final_prod_runcard_station_details[$index]->id)->update([
                //                     'operator' => $user->id
                //                 ]);
                //                 $certified_counter++;
                //             }
                //             else{
                //                 $operators = explode(',', $final_prod_runcard_station_details[$index]->operator);

                //                 $searched_operator = array_search($user->id, $operators);

                //                 if($searched_operator == "" || $searched_operator == null){
                //                     ProductionRuncardStation::where('id', $final_prod_runcard_station_details[$index]->id)->update([
                //                         'operator' => DB::raw('operator') . ',' . $user->id,
                //                     ]);
                //                 }
                //                 else{
                //                     $final_result = 0;
                //                 }

                //                 // return $operators;
                //                 // for($index2 = 0; $index2 < count($operators); $index2++){
                //                 //     if($operators[$index2] == $user->id){

                //                 //     }
                //                 // }

                //             }
                //         }

                //         if($certified_counter > 0){
                //             DB::commit();
                //             $final_result = true;
                //             $certified_status = true;
                //             $certified_status_label = 'Operator Saved';
                //         }
                //         else{
                //             $final_result = false;
                //             $certified_status = false;
                //             $certified_status_label = 'Already Exist';
                //         }
                //         // return $certified_counter;
                //     } catch (Exception $e) {
                //         $final_result = 0;
                //     }
                //     // return $final_prod_runcard_station_details;
                // }
                // else{
                //     $certified_status = false;
                //     $certified_status_label = 'Not Certified';
                // }

                // $device_mat_proc = Device::with([
                //                         'material_process' => function($query){
                //                             $query->where('status', 1);
                //                         },
                //                         'material_process.manpowers_details' => function($query) use($user){
                //                             $query->where('manpower_id', $user->id);
                //                         },
                //                     ])
                //                     ->where('name', $request->device_name)
                //                     ->first();

                // if($device_mat_proc != null){
                //     $col_mat_proc_materials = collect($device_mat_proc)->flatten(1)->where('material_details', '!=', null);
                //     $certified_mat_proc_id = $col_mat_proc_materials->pluck('id');
                // }
                // $material_process = collect($device_mat_proc)->where('material_process.manpowers_details', '!=', null)->flatten(1);

                // return $device_mat_proc;
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
                                    // Increment all above station input
                                    // $step_num = $request->txt_edit_prod_runcard_station_step;

                                    // $step_num = explode('-', $step_num)[0];

                                    // $prod_runcard_stations = ProductionRuncardStation::orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                                    //                         ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'))
                                    //                         ->where('production_runcard_id', $request->txt_prod_runcard_id_query)
                                    //                         ->get();

                                    // $prod_runcard_station_step_num = [];
                                    // $prod_runcard_station_id = [];

                                    // if(count($prod_runcard_stations) > 0){
                                    //     for($index = 0; $index < count($prod_runcard_stations); $index++){
                                    //         $prod_runcard_station_step_num[] = explode('-', $prod_runcard_stations[$index]->step_num)[0];
                                    //         $prod_runcard_station_id[] = $prod_runcard_stations[$index]->id;
                                    //     }
                                    // }

                                    // $prod_runcard_station_step_num = array_unique($prod_runcard_station_step_num);

                                    // $station_unique = [];
                                    // $station_unique_key = [];

                                    // foreach ($prod_runcard_station_step_num as $prod_runcard_station_step_num_unique => $value) {
                                    //     if($step_num > $value){
                                    //         $station_unique_key[] = $prod_runcard_station_step_num_unique;
                                    //         $station_unique[] = $value;
                                    //     }
                                    // }

                                    // $final_station_id = [];

                                    // if(count($prod_runcard_station_id) > 0){
                                    //     for($index = 0; $index < count($prod_runcard_station_id); $index++){
                                    //         for($index2 = 0; $index2 < count($station_unique_key); $index2++){
                                    //             if($station_unique_key[$index2] == $index){
                                    //                 $final_station_id[] = $prod_runcard_station_id[$index];
                                    //             }
                                    //         }
                                    //     }
                                    // }

                                    // ProductionRuncardStation::whereIn('id', $final_station_id)
                                    //     ->where('qty_output', '!=', null)
                                    //     // ->increment('qty_output', $request->txt_edit_prod_runcard_station_ng);
                                    //     // ->increment('qty_input', $request->txt_edit_prod_runcard_station_ng);
                                    //     ->increment('qty_input', $request->txt_edit_prod_runcard_station_ng,
                                    //         [
                                    //             'qty_output' => DB::raw('qty_output + ' . $request->txt_edit_prod_runcard_station_ng),
                                    //         ]);
                                    // // end of increment

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
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $data = $request->all();

        if(isset($txt_prod_runcard_station_id_query)){
            $validator = Validator::make($data, [
                'txt_prod_runcard_id_query' => ['required'],
                'txt_prod_runcard_station_id_query' => ['required'],
                'qty_input' => ['required'],
                'qty_output' => ['required'],
                'qty_ng' => ['required'],
                'txt_employee_number_scanner' => ['required'],
                // 'step_num' => ['required'],
                'sub_station_id' => ['required'],
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
                                                'qty_input'=>$request->qty_input,
                                                'qty_output'=>$request->qty_output,
                                                'qty_ng'=>$request->qty_ng,
                                                // 'status' => 1,
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
        else{
            $validator = Validator::make($data, [
                'txt_prod_runcard_id_query' => ['required'],
                'qty_input' => ['required'],
                'qty_output' => ['required'],
                'qty_ng' => ['required'],
                'txt_employee_number_scanner' => ['required'],
                // 'step_num' => ['required'],
                'sub_station_id' => ['required'],
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
                        // if( $request->txt_prod_runcard_station_id_query ){

                            // if($validator->passes()){
                                DB::beginTransaction();
                                $last_prod_runcard_station = ProductionRuncardStation::where('production_runcard_id', $request->txt_prod_runcard_id_query)->orderBy('id', 'desc')->first();

                                $step_num = 1;

                                if($last_prod_runcard_station != null){
                                    $step_num = $last_prod_runcard_station->step_num + 1;
                                }

                                try {
                                    $prod_runcard_station_id = ProductionRuncardStation::insertGetId([
                                        'production_runcard_id'=> $request->txt_prod_runcard_id_query,
                                        // 'mat_proc_id'=> $material_process[$index]->id,
                                        // 'station_id'=> $material_process[$index]->station_sub_station->station_id,
                                        'sub_station_id'=> $request->sub_station_id,
                                        'step_num'=> $step_num,
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
                        // }

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
    }
    // ../USED IN AUTOMATIC PRODUCTION RUNCARD



    // USED IN MANUAL PRODUCTION RUNCARD
    public function save_prod_runcard_details(Request $request){ // FOR MANUAL AND AUTOMATIC RUNCARD
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
            'setup_qualification' => ['required'],
            // 'txt_assessment_no' => ['required'],
            // 'txt_a_drawing_no' => ['required'],
            // 'txt_a_drawing_rev' => ['required'],
            // 'txt_g_drawing_no' => ['required'],
            // 'txt_g_drawing_rev' => ['required'],
            // 'txt_other_docs_no' => ['required'],
            // 'txt_other_docs_no' => ['required'],
            // 'txt_other_docs_rev' => ['required'],
            // 'txt_device_code' => ['required'],
        ]);

        $material_process = [];

        if($validator->passes()){

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
                                    'setup_qualification'=> $request->setup_qualification,
                                    'mold'=> $request->txt_mold,
                                    'ct_supplier'=> $request->txt_ct_supplier,
                                    'die_no'=> $request->txt_die_no,
                                    'pair_no'=> $request->txt_pair_no,
                                    'remarks'=> $request->txt_remarks,
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


                        $setup_qualification = 0;
                        $eng_qualification_name = '';
                        $eng_qualification_stamp = '';
                        $eng_qualification_id = '';
                        $setup_qualified = 0;
                        $qc_qualification = 0;
                        $qc_stamp_name = '';
                        $qc_stamp_stamp = '';
                        $qc_stamp_id = '';
                        $qc_qualified = 0;



                        $prod_runcard_id = ProductionRuncard::insertGetId([
                            'po_no'=> $request->txt_po_number,
                            'status'=> 1,
                            'runcard_no' => $final_runcard_no,
                            // 'reel_lot_no' => $final_reel_lot_no,
                            'lot_qty' => $request->txt_lot_qty,
                            // 'assessment_no'=> $request->txt_assessment_no,
                            'mold'=> $request->txt_mold,
                            'ct_supplier'=> $request->txt_ct_supplier,
                            'die_no'=> $request->txt_die_no,
                            'pair_no'=> $request->txt_pair_no,
                            'remarks'=> $request->txt_remarks,

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
                            'updated_at' => date('Y-m-d H:i:s'),
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
                $return = array('title'=>$return_title,'body'=>$return_body, 'prod_runcard_id' => $prod_runcard_id, 'result' => $result);
                return $return;
            }
            else{

            }
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = 'Employee not found!';
            $return = array('title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
            return $return;
        }
        else{
            $return_title = '<i class="fa fa-exclamation-triangle text-warning"></i> Not saved';
            $return_body = 'Saving Failed!';
            $return = array('title'=>$return_title,'body'=>$return_body, 'error' => $validator->messages());
            return $return;
        }
    }

    public function submit_to_oqc_lot_app(Request $request){ // FOR MANUAL AND AUTOMATIC RUNCARD
        date_default_timezone_set('Asia/Manila');
        $return_title = '<i class="fa fa-check-circle text-success"></i> Saved';
        $return_body = 'Record has been saved.';
        $result = false;

        $data = $request->all();

        $validator = Validator::make($data, [
            'txt_prod_runcard_id_query' => ['required'],
        ]);

        $material_process = [];


        if($validator->passes()){

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
                                    'status'=> 3,
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
        $materials = ProductionRuncardMaterial::where('runcard_id', $request->runcard_id)->with(['material_info'])
        ->get();

        return DataTables::of($materials) 
        ->addColumn('raw_type', function($row){
            if($row->type == 1){
                return 'Material Issuance';
            }
            else{
                return 'Sakidashi';
            }
        })
        ->rawColumns(['status', 'btn_save_material'])
        ->make(true);
    }

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
                // ->where('lot_no', $request->lot_no)
                // ->whereNotIn('id', $arr_issuance_id)
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

    public function check_material_no(Request $request){
        date_default_timezone_set('Asia/Manila');
        // $data = WBSKitIssuance::with([
        //                     'kit_issuance',
        //                     'parts_prep_info' => function($query){
        //                         $query->where('wbs_table', 1);
        //                         $query->where('deleted_at', null);
        //                     },
        //                 ])
        //             ->where('po','=',$request['po_number'])
        //             // ->whereIn('id', $arr_issuance_id)
        //             ->get();

        $data = [];
        $type = 1;

        $data = WBSKitIssuance::
            with(['kit_issuance'])
            ->where('po','=',$request->po_number)
            // ->where('issue_no','=',$request['issue_no'])
            ->where('lot_no', $request->lot_no)
            ->get();

        if(count($data) <= 0){
            $data = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')
            ->where('po_no',$request['po_number'])
            ->whereHas('tbl_wbs_sakidashi_issuance_item',
               function($query) use ($request){
                    $query->where('lot_no', $request->txt_scan_sakidashi_lot);
                },
            )
            ->get();    

            if(count($data) > 0){
                $type = 2;
            }        
        }

        return response()->json(['data' => $data, 'type' => $type]);
    }

    public function generate_prod_runcard_qrcode(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = ProductionRuncard::where('id', $request->runcard_id)->first();
        
        $po_qrcode = "";
        $whs_slip_qrcode = "";

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

        return response()->json(['data' => $data, 'po_qrcode' => $po_qrcode, 'runcard_qrcode' => $runcard_qrcode]);
    }
}