<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Device;
use App\Model\MaterialProcess;
use App\Model\StationSubStation;
use App\Model\Station;
use App\Model\SubStation;
use Illuminate\Http\Request;
use App\Model\MaterialIssuance;
use App\Model\MaterialIssuanceSubSystem;//tbl_wbs_material_kitting
use App\Model\MaterialIssuanceDetails;
use App\Model\WBSKitIssuance;
use App\Model\PartsPrep;
use App\Model\PartsPrepStation;
use App\Model\Machine;
use App\Model\WBSSakidashiIssuance;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\WBSWarehouseMatIssuanceDetails;
use App\Model\ModeOfDefect;
use App\Model\RapidActiveDocs;


use App\Model\ScrapVerificationRuncard;
use App\Model\ScrapVerificationRuncardItems;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

use DataTables;

define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);

class ScrapVerificationRuncardController extends Controller
{
    public function return_result($icon_i,$title_i,$body_i,$i){
        //result 1 = ok, result 0 = error, result 2 = invalid user
        $icon = array();
        $icon[0] = '<i class="fa fa-times-circle text-danger"></i>';
        $icon[1] = '<i class="fa fa-check-circle text-success"></i>';
        $icon[2] = '<i class="fa fa-exclamation-triangle text-warning"></i>';

        $title = array();
        $title[0] = 'Not saved';
        $title[1] = 'Saved';
        $title[2] = 'Invalid Employee No.';

        $body = array();
        $body[0] = 'An error occured. Record not saved.';
        $body[1] = 'Record has been saved.';
        $body[2] = 'Record not saved.';

        $body_str = is_numeric($body_i)?$body[$body_i]:$body_i;

        $return = array();
        $return['icon'] = $icon[$icon_i];
        $return['title'] = $title[$title_i];
        $return['body'] = $body_str;
        $return['i'] = $i;
        return $return;
    }

	public function fn_view_scrapverificationruncard(){
		return view('scrapverificationruncard');
	}


    public function fn_select_svr_po_details(Request $request){
        $lot_numbers = array();
        switch ($request['wbs_table']) {
            case 1:
                $lot_numbers = WBSKitIssuance::
                    with('kit_issuance')
                    ->where('po','=',$request['po_number'])
                    ->get()->take(1);
                break;
            case 2:
                $lot_numbers = WBSSakidashiIssuance::
                    where('po_no','=',$request['po_number'])
                    ->get()->take(1);
                break;
            
            default:
                # code...
                break;
        }
        return $lot_numbers;
    }

	public function fn_select_svr_datatable(Request $request){
        $datatable_svr = ScrapVerificationRuncard::with('user_created_by','user_updated_by','user_verified_by')
            ->where([
                [
                    'wbs_table','=',
                    1
                ],
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();

        return DataTables::of($datatable_svr)
            ->addColumn('raw_action', function($datatable_svr){
                $result='<button style="width:30px;" title="Open details" class="btn_dt_open btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                if( Auth::user()->position==1 && $datatable_svr->status !=1 ){
                    $result.=' <button style="width:30px;" title="Delete" class="btn_dt_delete btn btn-danger btn-sm py-0"><i class="fa fa-trash-alt fa-sm"></i></button>';
                }
                $result.=' <input type="hidden" class="col_dt_id" value="'.$datatable_svr->id.'">';
                return $result;
            })
            ->addColumn('raw_status', function($datatable_svr){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">For approval</span>';

                if ( $datatable_svr->count() ) {

                    switch ( $datatable_svr->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Verified</span>';
                            break;                        
                        default:
                            # code...
                            break;
                    }
                }

                return $result;
            })
            ->addColumn('raw_record_info', function($datatable_svr){
                $result = '';

                if ( $datatable_svr->count() ) {
                    if( $datatable_svr['user_created_by'] ){
                        $result.='Created '.date('M j, y',strtotime($datatable_svr->created_at)).' by '.$datatable_svr['user_created_by']->name.'<br>';
                    }
                    if( $datatable_svr['user_updated_by'] ){
                        $result.='Updated '.date('M j, y',strtotime($datatable_svr->updated_at)).' by '.$datatable_svr['user_updated_by']->name.'<br>';
                    }
                    if( $datatable_svr['user_verified_by'] ){
                        $result.='Verified '.date('M j, y',strtotime($datatable_svr->verified_at)).' by '.$datatable_svr['user_verified_by']->name.'<br>';
                    }
                }

                return '<small>'.$result.'</small>';
            })
            ->addColumn('raw_pl', function($datatable_svr){
                $result = '---';
                $table_machine = Station::where('id', $datatable_svr->product_line )->take(1)->get();
                if ( $table_machine->count() ) {
                    $result = $table_machine[0]->name;
                }
                return $result;
            })
            ->addColumn('raw_wbs_details', function($datatable_svr){
                // $result = '---';
                $result = array();
                $result['po'] = '';
                $result['device_name'] = '';

                $table_kit_issuance = WBSKitIssuance::
                    with('kit_issuance')
                    ->where('id','=',$datatable_svr->wbs_kit_issuance_id)
                    ->get();

                if ( $table_kit_issuance->count() ) {
                    $result['po'] = $table_kit_issuance[0]->po;
                    $result['device_name'] = $table_kit_issuance[0]['kit_issuance']->device_name;
                }
                return $result;
            })
            ->rawColumns(['raw_action','raw_status','raw_record_info'])
            ->make(true);
	}

    public function fn_select_svr_datatable_sakidashi(Request $request){
        $datatable_svr = ScrapVerificationRuncard::with('user_created_by','user_updated_by','user_verified_by')
            ->where([
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

        return DataTables::of($datatable_svr)
            ->addColumn('raw_action', function($datatable_svr){
                $result='<button style="width:30px;" title="Open details" class="btn_dt_open btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                if( Auth::user()->position==1 && $datatable_svr->status !=1 ){
                    $result.=' <button style="width:30px;" title="Delete" class="btn_dt_delete btn btn-danger btn-sm py-0"><i class="fa fa-trash-alt fa-sm"></i></button>';
                }
                $result.=' <input type="hidden" class="col_dt_id" value="'.$datatable_svr->id.'">';
                return $result;
            })
            ->addColumn('raw_status', function($datatable_svr){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">For approval</span>';

                if ( $datatable_svr->count() ) {

                    switch ( $datatable_svr->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Verified</span>';
                            break;                        
                        default:
                            # code...
                            break;
                    }
                }

                return $result;
            })
            ->addColumn('raw_record_info', function($datatable_svr){
                $result = '';

                if ( $datatable_svr->count() ) {
                    if( $datatable_svr['user_created_by'] ){
                        $result.='Created '.date('M j, y',strtotime($datatable_svr->created_at)).' by '.$datatable_svr['user_created_by']->name.'<br>';
                    }
                    if( $datatable_svr['user_updated_by'] ){
                        $result.='Updated '.date('M j, y',strtotime($datatable_svr->updated_at)).' by '.$datatable_svr['user_updated_by']->name.'<br>';
                    }
                    if( $datatable_svr['user_verified_by'] ){
                        $result.='Verified '.date('M j, y',strtotime($datatable_svr->verified_at)).' by '.$datatable_svr['user_verified_by']->name.'<br>';
                    }
                }

                return '<small>'.$result.'</small>';
            })
            ->addColumn('raw_pl', function($datatable_svr){
                $result = '---';
                $table_machine = Station::where('id', $datatable_svr->product_line )->take(1)->get();
                if ( $table_machine->count() ) {
                    $result = $table_machine[0]->name;
                }
                return $result;
            })
            ->addColumn('raw_wbs_details', function($datatable_svr){
                // $result = '---';
                $result = array();
                $result['po'] = '';
                $result['device_name'] = '';

                $tbl_wbs_sakidashi_issuance = WBSSakidashiIssuance::
                    where('id','=',$datatable_svr->wbs_kit_issuance_id)
                    ->get();

                if ( $tbl_wbs_sakidashi_issuance->count() ) {
                    $result['po'] = $tbl_wbs_sakidashi_issuance[0]->po_no;
                    $result['device_name'] = $tbl_wbs_sakidashi_issuance[0]->device_name;
                }
                return $result;
            })
            ->rawColumns(['raw_action','raw_status','raw_record_info'])
            ->make(true);
    }

    public function fn_select_datatable_svr_parts(Request $request){
        //------
        $datatable_svr_items = ScrapVerificationRuncardItems::with('scrap_verification_runcard','user_created_by','user_updated_by')
            ->where([
                [
                    'scrap_verification_runcard_id','=',
                    $request->svr_id
                ],
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();

        return DataTables::of($datatable_svr_items)
            ->addColumn('raw_action', function($datatable_svr_items){
                $result='';
                if( Auth::user()->position==1 && $datatable_svr_items['scrap_verification_runcard']->status !=1 ){
                    $result.=' <button style="width:30px;" title="Delete" class="btn_dt_delete btn btn-danger btn-sm py-0"><i class="fa fa-trash-alt fa-sm"></i></button>';
                }
                $result.=' <input type="hidden" class="col_dt_id" value="'.$datatable_svr_items->id.'">';
                return $result;
            })
            ->addColumn('raw_record_info', function($datatable_svr_items){
                $result = '';

                if ( $datatable_svr_items->count() ) {
                    if( $datatable_svr_items['user_created_by'] ){
                        $result.='Created '.date('M j, y',strtotime($datatable_svr_items->created_at)).' by '.$datatable_svr_items['user_created_by']->name.'<br>';
                    }
                    if( $datatable_svr_items['user_updated_by'] ){
                        $result.='Updated '.date('M j, y',strtotime($datatable_svr_items->updated_at)).' by '.$datatable_svr_items['user_updated_by']->name.'<br>';
                    }
                }

                return '<small>'.$result.'</small>';
            })
            ->addColumn('raw_mod', function($datatable_svr_items){
                $result = '---';
                $table_mod = ModeOfDefect::where('id', $datatable_svr_items->mod )->take(1)->get();
                if ( $table_mod->count() ) {
                    $result = $table_mod[0]->name;
                }
                return $result;
            })
            ->rawColumns(['raw_action','raw_record_info'])
            ->make(true);
    }
    public function fn_view_warehouse_parts_prep(Request $request){
        $parts_preps_collect = PartsPrep::where([
                [
                    'wbs_table','=',
                    3
                ],
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();

        $tbl_wbs_warehouse_mat_issuance_details = WBSWarehouseMatIssuanceDetails::with(
            'tbl_request_summary',
            'tbl_request_detail')
        ->whereHas('tbl_request_summary',function($query) use ($request){
            $query->where('pono',$request['po_number']);
        })
        ->get();

        return DataTables::of($tbl_wbs_warehouse_mat_issuance_details)
            ->addColumn('status', function($tbl_wbs_warehouse_mat_issuance_details) use ($parts_preps_collect){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';
                $parts_preps = collect($parts_preps_collect)->where('wbs_kit_issuance_id', $tbl_wbs_warehouse_mat_issuance_details->id)->flatten(1);

                if ( $parts_preps->count() ) {
                    switch ( $parts_preps[0]->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Done: Received</span>';
                            if( $parts_preps[0]->with_partsprep ){
                                $result = '<span data-sorter="2" class="badge badge-secondary font-weight-normal">Received: For MH Fill-in</span>';
                            }
                            break;
                        case 2:
                            $result = '<span data-sorter="1" class="badge badge-warning font-weight-normal">Returned to WHS</span>';
                            break;
                        case 3:
                            $result = '<span data-sorter="3" class="badge badge-secondary font-weight-normal">For Parts Prep Fill-in</span>';
                            break;
                        case 4:
                            $result = '<span data-sorter="4" class="badge badge-secondary font-weight-normal">Ongoing in stations/for verification</span>';
                            break;
                        case 5:
                            $result = '<span data-sorter="7" class="badge badge-success font-weight-normal">Done: Verified</span>';
                            break;
                        case 6:
                            $result = '<span data-sorter="5" class="badge badge-secondary font-weight-normal">For checking</span>';
                            break;
                        case 7:
                            $result = '<span data-sorter="8" class="badge badge-success font-weight-normal">Done: Approved</span>';
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }

                return $result;
            })
            ->addColumn('action', function($tbl_wbs_warehouse_mat_issuance_details) use ($request, $parts_preps_collect){
                $disabled = 'disabled';
                $title = '';
                $icon_partsprep = '';

                //---
                $devices = Device::where('barcode',$request->device_code)->get();
                if(!$devices->count()){
                    $devices = array();
                    $devices[0]['id'] = 0;
                }

                $stations = MaterialProcess::with([
                    'station_sub_station',
                    'station_sub_station.station',
                    'station_sub_station.sub_station',
                    'material_details'
                    ])
                    ->where('status', 1)
                    ->where('device_id', $devices[0]['id'])
                    ->whereHas('station_sub_station.station',
                       function($query){
                            $query->where('status', 1);
                            $query->where('station_type', 1);
                        },
                    )
                    ->whereHas('station_sub_station.sub_station',
                       function($query){
                            $query->where('status', 1);
                        },
                    )
                    ->whereHas('material_details',
                       function($query) use ($tbl_wbs_warehouse_mat_issuance_details){
                            $query->where('item', $tbl_wbs_warehouse_mat_issuance_details->item)
                            ->where('item_desc', $tbl_wbs_warehouse_mat_issuance_details->item_desc)
                            ->where('status', 1);
                        },
                    )
                    ->get();

                if ( $stations->count() ) {
                    $icon_partsprep = '<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>';
                }
                //---
                $parts_preps = collect($parts_preps_collect)->where('wbs_kit_issuance_id', $tbl_wbs_warehouse_mat_issuance_details->id)->flatten(1);
                if ( $parts_preps->count() ) {
                    // $icon_partsprep = ($parts_preps[0]->with_partsprep)?'<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2"></i>':'';
                    if( $parts_preps[0]->status == 1 || $parts_preps[0]->status > 2 ){
                        $disabled = '';
                    }
                }

                $result='<button '.$disabled.' style="width:30px;" title="Open details" class="btn_material_action btn_warehouse_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                $result.=$icon_partsprep;
                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_warehouse_mat_issuance_details->id.'">';
                // $result.=' <input type="hidden" class="col_material_code" value="'.$tbl_wbs_sakidashi_issuance->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$request->device_code.'">';

                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

	public function fn_view_setup_stations(Request $request){
        $parts_prep_id_query    = $request->parts_prep_id_query;
        $item                   = $request->item;
        $item_desc              = $request->item_desc;


        // $devices = Device::where('barcode',$request->device_code)->get();

        // if(!$devices->count()){
        //     $devices = array();
        //     $devices[0]['id'] = 0;
        // }

        // $stations = MaterialProcess::with([
        //     'station_sub_station',
        //     'station_sub_station.station',
        //     'station_sub_station.sub_station'
        //     ])
        //     ->where('status', 1)
        //     ->where('device_id', $devices[0]['id'])
        //     ->whereHas('station_sub_station.station',
        //        function($query){
        //             $query->where('status', 1);
        //             $query->where('station_type', 1);
        //         },
        //     )
        //     ->whereHas('station_sub_station.sub_station',
        //        function($query){
        //             $query->where('status', 1);
        //         },
        //     )
        //     ->get();


        //---
        $devices = Device::where('barcode',$request->device_code)->get();
        if(!$devices->count()){
            $devices = array();
            $devices[0]['id'] = 0;
        }

        $stations = MaterialProcess::with([
            'station_sub_station',
            'station_sub_station.station',
            'station_sub_station.sub_station',
            'material_details'
            ])
            ->where('status', 1)
            ->where('device_id', $devices[0]['id'])
            ->whereHas('station_sub_station.station',
               function($query){
                    $query->where('status', 1);
                    $query->where('station_type', 1);
                },
            )
            ->whereHas('station_sub_station.sub_station',
               function($query){
                    $query->where('status', 1);
                },
            )
            ->whereHas('material_details',
               function($query) use ($item,$item_desc){
                    $query->where('item', $item)
                    ->where('item_desc', $item_desc)
                    ->where('status', 1);
                },
            )
            ->get();
        //---
        return DataTables::of($stations)
            ->addColumn('raw_action', function($station) use ($parts_prep_id_query){
            	$disabled = '';
                $result = '';

                $station_id = ( $station['station_sub_station']['station'] )?$station['station_sub_station']['station']->id:'';
                $sub_station_id = ( $station['station_sub_station']['sub_station'] )?$station['station_sub_station']['sub_station']->id:'';

                $partsprepstation = PartsPrepStation::
                    where('parts_prep_id', $parts_prep_id_query )
                    ->where('station_id', $station_id )
                    ->where('sub_station_id', $sub_station_id )
                    ->get();

                if ( $partsprepstation->count() ) {
                    $disabled = 'disabled';
                }

                // $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_station btn btn-info btn-sm py-0"><i class="fa fa-edit"></i></button>';
                $result='<input type="checkbox" '.$disabled.' class="ckb_station">';
                $result.='<input type="hidden" class="col_station_step" value="'.$station->step.'">';
                $result.='<input type="hidden" class="col_station_id" value="'.$station_id.'">';
                $result.='<input type="hidden" class="col_sub_station_id" value="'.$sub_station_id.'">';
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

    public function fn_view_partspreparatory_stations(Request $request){
        $enable_edit = $request->enable_edit;

        $partsprepstations = PartsPrepStation::with([
                'station' =>
                function($query){
                    $query->where('status',1);
                },
                'sub_station' =>
                function($query){
                    $query->where('status',1);
                },
            ])
            ->where('parts_prep_id', $request->parts_prep_id_query )
            ->get();

        return DataTables::of($partsprepstations)
            ->addColumn('raw_action', function($partsprepstation) use ($enable_edit){
                $result='';
                return $result;
            })
            ->addColumn('raw_machine', function($partsprepstation){
                $result = '';
                $machines = Machine::where('id', $partsprepstation->machine_id )->get();
                if ( $machines->count() ) {
                    $result = $machines[0]->name;
                }
                return $result;
            })
            ->addColumn('raw_created_by', function($partsprepstation){
                $result = '';
                $users = User::where('id', $partsprepstation->created_by )->get();
                if ( $users->count() ) {
                    $result = $users[0]->name;
                }
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
    }

    public function fn_select_list_svr_parts(Request $request){
        $lot_numbers = array();
        switch ($request['wbs_table']) {
            case 1:
                $lot_numbers = WBSKitIssuance::
                    with('kit_issuance')
                    ->where('po','=',$request['po'])
                    ->get();
                    // ->groupBy('item_desc')->toArray();
                break;
            case 2:
                $lot_numbers = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')
                    ->where('po_no',$request['po'])
                    ->get();
                    // ->groupBy('tbl_wbs_sakidashi_issuance_item.item_desc')->toArray();
                break;
            
            default:
                # code...
                break;
        }
        $lot_numbers_arr = array();
        foreach ($lot_numbers as $key => $value) {
            $lot_numbers_arr[] = $value;
            # code...
        }
        return $lot_numbers_arr;
    }

    public function fn_select_svr_details(Request $request){
        $scrap_verification_runcard = ScrapVerificationRuncard::
            with(['user_created_by','user_updated_by','user_verified_by'])
            ->where([
                [
                    'id','=',
                    $request->svr_id
                ],
                [
                    'deleted_at','=',
                    null
                ]
            ])
            ->get();

        if(!$scrap_verification_runcard->isEmpty()){
            switch ($scrap_verification_runcard[0]->wbs_table) {
                case 1:
                    $lot_number = WBSKitIssuance::with(['kit_issuance'])->where('id','=',$scrap_verification_runcard[0]->wbs_kit_issuance_id)
                        ->get();
                    $scrap_verification_runcard[0]['device_name'] = $lot_number[0]['kit_issuance']->device_name;
                    $scrap_verification_runcard[0]['po'] = $lot_number[0]->po;
                    break;

                case 2:
                    $lot_number = WBSSakidashiIssuance::where('id','=',$scrap_verification_runcard[0]->wbs_kit_issuance_id)
                        ->get();
                    $scrap_verification_runcard[0]['device_name'] = $lot_number[0]->device_name;
                    $scrap_verification_runcard[0]['po'] = $lot_number[0]->po_no;
                    break;
                
                default:
                    # code...
                    break;
            }

            $scrap_verification_runcard[0]['pl'] = '';
            $table_machine = Station::where('id', $scrap_verification_runcard[0]->product_line )->take(1)->get();
            if ( $table_machine->count() ) {
                $scrap_verification_runcard[0]['pl'] = $table_machine[0]->name;;
            }
        }

        return $scrap_verification_runcard;
    }

    public function fn_generate_parts_prep_runcard_arr($po){
        $runcard_number = 1;
        $runcard_po = substr($po, 0, 10);
        $parts_preps = PartsPrep::where([
                [
                    'runcard_po','=',
                    $runcard_po
                ],
                [
                    'deleted_at','=',
                    null
                ]
            ])
            ->orderBy('runcard_number','desc')
            ->get();
            // return $parts_preps;
        if($parts_preps->count() > 0){
            $runcard_number = $parts_preps[0]->runcard_number+1;
        }
        
        $runcard_number_arr = array();
        $runcard_number_arr['runcard_number_str'] = $runcard_po.str_pad($runcard_number,3,'0',STR_PAD_LEFT);
        $runcard_number_arr['runcard_po'] = $runcard_po;
        $runcard_number_arr['runcard_number'] = $runcard_number;

        return $runcard_number_arr;
    }

    public function fn_insert_lot_pass_fail(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->col_parts_preps_id ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->col_parts_preps_id)
                        ->update(
                            [
                                'with_partsprep'=>$request->with_partsprep,
                                'status'=>$request->status,
                                'received_passed_by'=>$user[0]->id,
                                'updated_by'=>$user[0]->id,
                                'received_passed_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                //insert
                DB::beginTransaction();
                try {
                    // $runcard_po         = substr($request->txt_runcard_number, 0, 10);
                    // $runcard_number     = substr($request->txt_runcard_number, -3);

                    // $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->txt_runcard_number);

                    // $runcard_number_changed = false;
                    // if( (int)$runcard_number < $runcard_number_arr['runcard_number'] ){
                    //     $runcard_number     = $runcard_number_arr['runcard_number'];
                    //     $runcard_number_changed = true;
                    // }


                    $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->col_material_po);
                    $runcard_po         = $runcard_number_arr['runcard_po'];
                    $runcard_number     = $runcard_number_arr['runcard_number'];

                    PartsPrep::insert(
                        [
                            'wbs_kit_issuance_id'=>$request->col_material_id,
                            'wbs_table'=>1,
                            'runcard_po'=>$runcard_po,
                            'runcard_number'=>$runcard_number,
                            'with_partsprep'=>($request->with_partsprep)?$request->with_partsprep:0,
                            'status'=>$request->status,
                            'received_by'=>$user[0]->id,
                            'received_passed_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'received_at'=>date('Y-m-d H:i:s'),
                            'received_passed_at'=>date('Y-m-d H:i:s'),
                        ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    public function fn_insert_sakidashi_lot_pass_fail(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->col_parts_preps_id ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->col_parts_preps_id)
                        ->update(
                            [
                                'with_partsprep'=>$request->with_partsprep,
                                'status'=>$request->status,
                                'received_passed_by'=>$user[0]->id,
                                'updated_by'=>$user[0]->id,
                                'received_passed_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                //insert
                DB::beginTransaction();
                try {
                    // $runcard_po         = substr($request->txt_runcard_number, 0, 10);
                    // $runcard_number     = substr($request->txt_runcard_number, -3);

                    // $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->txt_runcard_number);

                    // $runcard_number_changed = false;
                    // if( (int)$runcard_number < $runcard_number_arr['runcard_number'] ){
                    //     $runcard_number     = $runcard_number_arr['runcard_number'];
                    //     $runcard_number_changed = true;
                    // }


                    $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->col_material_po);
                    $runcard_po         = $runcard_number_arr['runcard_po'];
                    $runcard_number     = $runcard_number_arr['runcard_number'];

                    PartsPrep::insert(
                        [
                            'wbs_kit_issuance_id'=>$request->col_material_id,
                            'wbs_table'=>2,
                            'runcard_po'=>$runcard_po,
                            'runcard_number'=>$runcard_number,
                            'with_partsprep'=>($request->with_partsprep)?$request->with_partsprep:0,
                            'status'=>$request->status,
                            'received_by'=>$user[0]->id,
                            'received_passed_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'received_at'=>date('Y-m-d H:i:s'),
                            'received_passed_at'=>date('Y-m-d H:i:s'),
                        ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }


    public function fn_insert_warehouse_lot_pass_fail(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->col_parts_preps_id ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->col_parts_preps_id)
                        ->update(
                            [
                                'with_partsprep'=>$request->with_partsprep,
                                'status'=>$request->status,
                                'received_passed_by'=>$user[0]->id,
                                'updated_by'=>$user[0]->id,
                                'received_passed_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                //insert
                DB::beginTransaction();
                try {
                    // $runcard_po         = substr($request->txt_runcard_number, 0, 10);
                    // $runcard_number     = substr($request->txt_runcard_number, -3);

                    // $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->txt_runcard_number);

                    // $runcard_number_changed = false;
                    // if( (int)$runcard_number < $runcard_number_arr['runcard_number'] ){
                    //     $runcard_number     = $runcard_number_arr['runcard_number'];
                    //     $runcard_number_changed = true;
                    // }


                    $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->col_material_po);
                    $runcard_po         = $runcard_number_arr['runcard_po'];
                    $runcard_number     = $runcard_number_arr['runcard_number'];

                    PartsPrep::insert(
                        [
                            'wbs_kit_issuance_id'=>$request->col_material_id,
                            'wbs_table'=>3,
                            'runcard_po'=>$runcard_po,
                            'runcard_number'=>$runcard_number,
                            'with_partsprep'=>($request->with_partsprep)?$request->with_partsprep:0,
                            'status'=>$request->status,
                            'received_by'=>$user[0]->id,
                            'received_passed_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'received_at'=>date('Y-m-d H:i:s'),
                            'received_passed_at'=>date('Y-m-d H:i:s'),
                        ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }


    public function fn_delete_svr(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',1)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->txt_parts_preps_id_query ){
                //update
                DB::beginTransaction();
                try {
                    ScrapVerificationRuncard::where('id',$request->txt_parts_preps_id_query)
                        ->update(
                            [
                                'updated_by'=>$user[0]->id,
                                'deleted_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                $result = $this->return_result(2,2,2,2);
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }


    public function fn_delete_svr_item(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',1)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->rec_id ){
                //update
                DB::beginTransaction();
                try {
                    ScrapVerificationRuncardItems::where('id',$request->rec_id)
                        ->update(
                            [
                                'updated_by'=>$user[0]->id,
                                'deleted_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                $result = $this->return_result(2,2,2,2);
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }
    public function fn_update_svr_verified(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->txt_parts_preps_id_query ){
                //update
                DB::beginTransaction();
                try {
                    ScrapVerificationRuncard::where('id',$request->txt_parts_preps_id_query)
                        ->update(
                            [
                                'status'=>1,
                                'verified_by'=>$user[0]->id,
                                'verified_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                $result = $this->return_result(2,2,2,2);
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    public function fn_insert_svr_details(Request $request){
        $result = $this->return_result(0,0,0,0);
        // 0-N/A,1-Prod Supervisor,2-QC Supervisor,3-Material Handler,4-Operator,5-Inspector
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->txt_parts_preps_id_query ){
                //update
                DB::beginTransaction();
                try {
                    ScrapVerificationRuncard::where('id',$request->txt_parts_preps_id_query)
                        ->update(
                            [
                                'remarks'=>$request->txt_material_remarks,
                                'product_line'=>$request->txt_svr_pl,
                                'updated_by'=>$user[0]->id,
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                //insert
                DB::beginTransaction();
                try {
                    $last_id = ScrapVerificationRuncard::insertGetId(
                        [
                            'wbs_kit_issuance_id'=>$request->txt_wbs_kit_issuance_id_query,
                            'wbs_table'=>$request->txt_wbs_table,
                            'status'=>0,
                            'remarks'=>$request->txt_material_remarks,
                            'product_line'=>$request->txt_svr_pl,
                            'created_by'=>$user[0]->id,
                            'created_at'=>date('Y-m-d H:i:s'),
                        ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                    $result['last_id'] = $last_id;
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }


    public function fn_insert_svr_items_details(Request $request){
        $result = $this->return_result(0,0,0,0);
        // 0-N/A,1-Prod Supervisor,2-QC Supervisor,3-Material Handler,4-Operator,5-Inspector
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            //insert
            DB::beginTransaction();
            try {
                $last_id = ScrapVerificationRuncardItems::insertGetId(
                    [
                        'scrap_verification_runcard_id'=>$request->txt_parts_preps_id_query,
                        'item_desc'=>$request->txt_svr_items_partname,
                        'mod'=>$request->txt_svr_items_mod,
                        'remarks'=>$request->txt_svr_items_remarks,
                        'ng_qty_setup'=>$request->txt_svr_items_setup_ng?:0,
                        'ng_qty_prod'=>$request->txt_svr_items_prod_ng?:0,
                        'ng_qty_mat'=>$request->txt_svr_items_mat_ng?:0,
                        'created_by'=>$user[0]->id,
                        'created_at'=>date('Y-m-d H:i:s'),
                    ]
                    );
                DB::commit();
                $result = $this->return_result(1,1,1,1);
            } catch (Exception $e) {
                DB::rollback();
                $result = $this->return_result(0,0,0,0);
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }



    public function fn_select_partspreparatory_station_details(Request $request){
        $partsprep_stations = PartsPrepStation::
            with([
                'station',
                'sub_station',
            ])
            ->where(
                [
                    [
                        'deleted_at','=',
                        null
                    ],
                    [
                        'id','=',$request['col_partsprep_station_id']
                    ]
                ]
            )
            ->get();
        if($partsprep_stations->isEmpty()){
            $partsprep_stations = array();
        }
        else{
            $partsprep_stations[0]['created_by_name'] = '';
            $user = User::where('id', $partsprep_stations[0]->created_by)->get();
            if(!$user->isEmpty()){
                $partsprep_stations[0]['created_by_name'] = $user[0]['name'];
            }
        }
        return $partsprep_stations;
    }


    public function fn_update_partsprep_station_details(Request $request){
        $result = $this->return_result(0,0,0,0);
        //insert
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            DB::beginTransaction();
            try {
                PartsPrepStation::insert(
                    [
                        'step_num'=>0,
                        'parts_prep_id'=>$request->txt_parts_preps_id_query,
                        'station_id'=>$request->txt_edit_partsprep_station_station,
                        'sub_station_id'=>$request->txt_edit_partsprep_station_station,
                        'qty_input'=>$request->txt_edit_partsprep_station_input,
                        'qty_output'=>$request->txt_edit_partsprep_station_output,
                        'machine_id'=>$request->txt_edit_partsprep_station_machine,
                        'remarks'=>$request->txt_edit_partsprep_station_remarks,
                        'created_by'=>$user[0]->id,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_by'=>$user[0]->id,
                    ]
                    );
                DB::commit();
                $result = $this->return_result(1,1,1,1);
            } catch (Exception $e) {
                DB::rollback();
                $result = $this->return_result(0,0,0,0);
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    // public function fn_update_partsprep_station_details(Request $request){
    //     $result = $this->return_result(0,0,0,0);
    //     $user = User::where('employee_id', $request->txt_employee_number_scanner)
    //         ->where(function($q){
    //             $q->where('position',4)
    //                 ->orWhere('position',1);
    //         })
    //         ->get();
    //     if($user->count() > 0){
    //         if( $request->txt_partsprep_station_id_query ){
    //             //update
    //             DB::beginTransaction();
    //             try {
    //                 PartsPrepStation::where('id',$request->txt_partsprep_station_id_query)
    //                     ->update(
    //                         [
    //                             // 'status'=>$request->0,
    //                             'machine_id'=>$request->txt_edit_partsprep_station_machine,
    //                             'qty_input'=>($request->txt_edit_partsprep_station_input)?$request->txt_edit_partsprep_station_input:0,
    //                             'qty_output'=>($request->txt_edit_partsprep_station_output)?$request->txt_edit_partsprep_station_output:0,
    //                             'qty_ng'=>$request->txt_edit_partsprep_station_ng,
    //                             'mod'=>$request->txt_edit_partsprep_station_mod,
    //                             'print_code'=>$request->txt_edit_partsprep_station_print_code,
    //                             'created_by'=>$user[0]->id,
    //                             'updated_by'=>$user[0]->id,
    //                             'created_at'=>date('Y-m-d H:i:s'),
    //                             // 'updated_by'=>$request->txt_material_remarks,
    //                         ]
    //                     );
    //                 DB::commit();
    //                 $result = $this->return_result(1,1,1,1);
                    
    //             } catch (Exception $e) {
    //                 DB::rollback();
    //                 $result = $this->return_result(0,0,0,0);
    //             }
    //         }
    //     }
    //     else{
    //         $result = $this->return_result(2,2,2,2);
    //     }

    //     if( isset($request->txt_edit_partsprep_station_ng) && $request->txt_edit_partsprep_station_ng != '' ){//update current and prev in and out qty
    //         $ng_total = 0;
    //         $txt_edit_partsprep_station_ng_arr = explode(',', $request->txt_edit_partsprep_station_ng);
    //         foreach ($txt_edit_partsprep_station_ng_arr as $key => $value) {
    //             $ng_total+=$value;
    //         }
    //         $result = $this->update_station_in_out($request->txt_partsprep_station_id_query, $ng_total);
    //     }

    //     return $result;
    // }

    public function fn_select_list_partsprep_station_machines(Request $request){
        $machines = Machine::
            where(
                [
                    [
                        'status','=',
                        1
                    ],
                ]
            )
            ->get();
        if($machines->isEmpty()){
            $machines = array();
        }
        return $machines;
    }

    public function fn_select_list_svr_pl(Request $request){
        $stations = Station::
            where('status', 1)
            ->get();
        if($stations->isEmpty()){
            $stations = array();
        }
        return $stations;
    }

    public function fn_select_list_runcard_numbers(Request $request){
        $parts_preps = PartsPrep::
            where(
                [
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->get();
        if($parts_preps->isEmpty()){
            $parts_preps = array();
        }
        return $parts_preps;
    }

    public function fn_select_list_partsprep_station_mod(Request $request){
        $mode_of_defects = ModeOfDefect::
            where(
                [
                    [
                        'status','=',
                        1
                    ],
                ]
            )
            ->get();
        if($mode_of_defects->isEmpty()){
            $mode_of_defects = array();
        }
        return $mode_of_defects;
    }





































    public function fn_view_stationsxx(Request $request){
        // $material_process = MaterialProcess::with(['sub_station', 'sub_station.station'])->where('device_id', $request->device_id)->get();
        $material_process = MaterialProcess::with(['sub_station', 'sub_station.station'])->where('device_id', $request->device_id)->get();

        return DataTables::of($material_process)
            ->addColumn('raw_action', function($station){
                $disabled = '';
                $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_material btn btn-info btn-sm py-0"><i class="fa fa-edit"></i></button>';
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

    public function testonlyxxx(){
        $device = Device::with([
            'material_process' => 
            function($query){
                $query->where('status', 1);
            },
            'material_process.station_sub_station' =>
            function($query){
                $query->where('status', 1);
            },
            'material_process.station_sub_station.station' =>
            function($query){
                $query->where('status', 1);
            },
            'material_process.station_sub_station.sub_station' =>
            function($query){
                $query->where('status', 1);
            },
            ])
            ->where('status', 1)
            ->where('barcode', '104184001')
            ->get();



        // $material_process = MaterialProcess::with([
        //                     'device' => function($query){
        //                         $query->where('barcode', 'CN121P-104-0003');
        //                     },
        //                     'station_sub_station'
        //                 ])->get();
        return $device;


        // $material_process = MaterialProcess::with(['device', 'device.barcode'])->where('fdf', $request->device_id)->get();
    }


    public function testonly(Request $request){

        // $devices = Device::where('barcode',$request->device_code);
        // return $devices;

        $stations = MaterialProcess::with([
            'station_sub_station' => 
            function($query){
                $query->where('status', 1);
            },
            'station_sub_station.station' =>
            function($query){
                $query->where('status', 1);
                $query->where('station_type', 1);
            },
            'station_sub_station.sub_station' =>
            function($query){
                $query->where('status', 1);
            },
            // 'material_process.station_sub_station.station' =>
            // function($query){
            //     $query->where('status', 1);
            // },
            // 'material_process.station_sub_station.sub_station' =>
            // function($query){
            //     $query->where('status', 1);
            // },
            ])
            ->withCount([
                'station_sub_station.station' =>
                    function($query){
                        $query->where('status', 1);
                        $query->where('station_type', 1);
                    }
            ])
            ->where('status', 1)
            ->where('device_id', '1')
            // ->having('station_sub_station.station_count' > 0)
            ->get();

        return $stations;

    }

}
