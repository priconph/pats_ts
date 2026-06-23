<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Device;
use App\Model\MaterialProcess;
use Illuminate\Http\Request;
use App\Model\PartsPrep;
use App\Model\MaterialIssuance;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\MaterialIssuanceDetails;
use App\Model\WBSKitIssuance;
use App\Model\WBSSakidashiIssuance;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\WBSWarehouseMatIssuance;
use App\Model\WBSWarehouseMatIssuanceDetails;
use App\Model\RequestSummary;
use App\Model\Kitting;
use App\Model\SubKitting;
use App\Model\PartsPrepSubKitting;

use QrCode;


// use App\Model\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use DataTables;

define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);

class MaterialIssuanceController extends Controller
{
    public function return_result($icon_i,$title_i,$body_i,$i){
        //result 1 = ok, result 0 = error, result 2 = invalid user
        $title = array();
        $title[0] = 'Not saved';
        $title[1] = 'Saved';
        $title[2] = 'Invalid Employee No.';


        $body = array();
        $body[0] = 'An error occured. Record not saved.';
        $body[1] = 'Record has been saved.';
        $body[2] = 'Record not saved.';

        $icon = array();
        $icon[0] = '<i class="fa fa-times-circle text-danger"></i>';
        $icon[1] = '<i class="fa fa-check-circle text-success"></i>';
        $icon[2] = '<i class="fa fa-exclamation-triangle text-warning"></i>';

        $return = array();
        $return['icon'] = $icon[$icon_i];
        $return['title'] = $title[$title_i];
        $return['body'] = $body[$body_i];
        $return['i'] = $i;
        return $return;
    }

	public function fn_view_material_issuance_page(){
		return view('materialissuance');
	}
	public function fn_view_batches(Request $request){

		$tbl_wbs_material_kitting = MaterialIssuanceSubSystem::
        where('po_no',$request['po_number'])
        ->where('issuance_no',$request['txt_scan_transfer_slip'])
        ->get();

        return DataTables::of($tbl_wbs_material_kitting)
            ->addColumn('issued_qty', function(){
                $result = "---";
                return $result;
            })
            ->addColumn('received_dt', function($tbl_wbs_material_kitting){
				$material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $result = "---";
				if ( $material_issuances->count() ) {
	                $result = $material_issuances[0]->created_at;
				}
                return $result;
            })
            ->addColumn('received_by', function($tbl_wbs_material_kitting){
				$material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $result = "---";
				if ( $material_issuances->count() ) {
					$users = User::where('id', $material_issuances[0]->created_by )->get();
					if ( $users->count() ) {
		                $result = $users[0]->name;
		            }
    	        }
                return $result;
            })
            ->addColumn('status', function($tbl_wbs_material_kitting){
				$material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
				if ( $material_issuances->count() ) {
					$status = $material_issuances[0]->status;
					if($status==1){
		                $result = '<i title="Received" class="fa fa-check text-success px-2"></i>';
					}
					if($status==2){
		                $result = '<i title="Returned" class="fa fa-exclamation-triangle text-warning px-2"></i>';
					}
				}
                return $result;
            })
            ->addColumn('action', function($tbl_wbs_material_kitting){
				$material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $disabled = '';
				if ( $material_issuances->count() ) {
					$disabled = 'disabled';
				}

                //-----
                $hidden = ' hidden ';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----

                $result = "";
                $result.='<button '.$disabled.' '.$hidden.' style="width:30px;" title="Checked" class="btn_batch_action btn btn-info btn-sm  py-0 btn_batch_passed"><i class="fa fa-clipboard-check"></i></button>';
                $result.=' <input type="hidden" class="tbl_wbs_material_kitting_id" value="'.$tbl_wbs_material_kitting->id.'">';
                $result.=' <input type="hidden" class="tbl_wbs_material_kitting_issuance_no" value="'.$tbl_wbs_material_kitting->issuance_no.'">';
                return $result;
            })
            ->addColumn('kit_raw', function($tbl_wbs_material_kitting){
                $material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $disabled = '';
                if ( $material_issuances->count() ) {
                    $disabled = 'disabled';
                }

                //-----
                $hidden = ' hidden ';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----

                $result = "";
                $result = $tbl_wbs_material_kitting->kit_no;
                // $result.='<button '.$disabled.' '.$hidden.' style="width:30px;" title="Checked" class="btn_batch_action btn btn-info btn-sm  py-0 btn_batch_passed"><i class="fa fa-clipboard-check"></i></button>';
                $result.=' <input type="hidden" class="tbl_wbs_material_kitting_id" value="'.$tbl_wbs_material_kitting->id.'">';
                $result.=' <input type="hidden" class="tbl_wbs_material_kitting_issuance_no" value="'.$tbl_wbs_material_kitting->issuance_no.'">';
                return $result;
            })
            ->rawColumns(['status','action','kit_raw'])
            ->make(true);
	}
	public function fn_view_lot_numbers(Request $request){
        // 1. Fetch main data first
    $lot_numbers = WBSKitIssuance::with([
                'kit_issuance',
                'parts_prep' => function($q) {
                    $q->where('wbs_table', 1)->whereNull('deleted_at')->with('user_details');
                }
            ])
            ->where('po', $request->po)
            ->where('issue_no', $request->issue_no)
            ->get();

        // 2. Extract IDs to limit the subkitting query (Big Performance Gain)
        $issuance_ids = $lot_numbers->pluck('id')->toArray();

        $subkitting_collect = [];
        if (!empty($issuance_ids)) {
            $subkitting_collect = DB::table('kittings as a')
                ->join('sub_kittings as b', function($join) {
                    $join->on('a.id', '=', 'b.pats_kitting_id')->where('b.status', 1);
                })
                ->leftJoin('parts_preps_sub_kitting as c', 'b.id', '=', 'c.sub_kittings_id')
                ->whereIn('a.kit_issuance_id', $issuance_ids) // Filtered by the IDs we just found
                ->where('a.status', 1)
                ->groupBy('a.kit_issuance_id')
                ->select('a.kit_issuance_id', DB::raw('SUM(IF(c.status = 1, b.sub_kit_qty, 0)) AS sub_kit_qty'))
                ->pluck('sub_kit_qty', 'kit_issuance_id');
        }

        // 3. Batch fetch Material Details (N+1 fix)
        $items = $lot_numbers->pluck('item')->unique();
        $material_details = MaterialIssuanceDetails::whereIn('item', $items)
            ->where('issue_no', $request->issue_no)
            ->get()
            ->groupBy('item');

        return DataTables::of($lot_numbers)
        ->addColumn('received_dt', function($row) {
            $prep = ($row->parts_prep) ? $row->parts_prep->first() : null;
            return $prep ? $prep->received_at : "---";
        })
        ->addColumn('received_by', function($row) {
            // return $row->parts_prep ?? '---';
            $prep = ($row->parts_prep) ? $row->parts_prep : null;
            return ($prep && $prep->user_details) ? $prep->user_details->name : "---";
        })
        ->addColumn('raw_action', function($row) use ($subkitting_collect) {
            $prep = ($row->parts_prep) ? $row->parts_prep->first() : null;
            $parts_preps_id = $prep ? $prep->id : 0;

            $disabled_passed_btn = '';
            $disabled_subkit_btn = 'disabled';
            $hidden_subkit_btn = 'hidden';
            $icon_subkit = '';
            $hidden = (Auth::user()->position == 3 || Auth::user()->position == 9) ? '' : 'hidden';

            if ($prep) {
                $disabled_passed_btn = 'disabled';
                $disabled_subkit_btn = '';
                if ($prep->status == 2) $disabled_passed_btn = '';
            }

            $sub_kit_qty = $subkitting_collect[$row->id] ?? 'x';
            if ($sub_kit_qty !== 'x') {
                $hidden_subkit_btn = '';
                $icon_subkit = '<i title="Has subkit" class="fa fa-puzzle-piece text-secondary pl-2 float-right"></i>';
            }

            $result = ' ' . $icon_subkit;
            $result .= '<div class="dropdown">';
            $result .= '  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>';
            $result .= '  <div class="dropdown-menu">';
            $result .= '    <button ' . $disabled_subkit_btn . ' ' . $hidden . ' ' . $hidden_subkit_btn . ' title="Show Subkitting" class="dropdown-item btn_material_action btn_material_subkitting_details btn btn-success btn-sm py-0"><i class="fa fa-puzzle-piece fa-sm"></i> Subkitting</button>';
            $result .= '    <button ' . $disabled_passed_btn . ' ' . $hidden . ' title="Receive" class="dropdown-item btn_material_action btn_material_pass_details btn btn-success btn-sm py-0"><i class="fa fa-check-circle fa-sm"></i> Receive</button>';
            $result .= '  </div>';
            $result .= '</div>';

            $result .= ' <input type="hidden" class="col_material_id" value="' . $row->id . '">';
            $result .= ' <input type="hidden" class="col_material_code" value="' . $row->item . '">';
            $result .= ' <input type="hidden" class="col_device_code" value="' . ($row->kit_issuance->device_code ?? '') . '">';
            $result .= ' <input type="hidden" class="col_material_po" value="' . $row->po . '">';
            $result .= ' <input type="hidden" class="col_parts_preps_id" value="' . $parts_preps_id . '">';
            $result .= ' <input type="hidden" class="col_with_partsprep" value="0">';
            $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning btn-generate-sticker" item-desc="' . $row->item_desc . '" lot-no="' . $row->lot_no . '" title="Print QR Code" data-toggle="modal" data-target="#modal_LotNo_QRcode"><i class="fa fa-print fa-sm"></i></button>';

            return $result;
        })
        ->addColumn('raw_status', function($row) use ($subkitting_collect) {
            $prep = ($row->parts_prep) ? $row->parts_prep->first() : null;
            if (!$prep) return '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';

            switch ($prep->status) {
                case 1:
                    if (isset($subkitting_collect[$row->id]) && ($row->issued_qty > $subkitting_collect[$row->id])) {
                        return '<span data-sorter="2" class="badge badge-secondary font-weight-normal">Incomplete</span>';
                    }
                    return '<span data-sorter="6" class="badge badge-success font-weight-normal">Received</span>';
                case 2: return '<span data-sorter="1" class="badge badge-warning font-weight-normal">Returned to WHS</span>';
                case 3: return '<span data-sorter="3" class="badge badge-secondary font-weight-normal">For Parts Prep Fill-in</span>';
                case 4: return '<span data-sorter="4" class="badge badge-secondary font-weight-normal">Ongoing in stations/for verification</span>';
                case 5: return '<span data-sorter="7" class="badge badge-success font-weight-normal">Done: Verified</span>';
                case 6: return '<span data-sorter="5" class="badge badge-secondary font-weight-normal">For checking</span>';
                case 7: return '<span data-sorter="8" class="badge badge-success font-weight-normal">Done: Approved</span>';
                default: return '<span class="badge badge-secondary">Unknown</span>';
            }
        })
        ->addColumn('raw_usage', function($row) use ($material_details)  {
            $detail = $material_details->get($row->item);
            return $detail ? $detail->first()->usage : '---';
        })
        ->addColumn('raw_rqd_qty', function($row) use ($material_details) {
            $detail = $material_details->get($row->item);
            $usage = $detail ? $detail->first()->usage : 0;
            $po_qty = $row->kit_issuance->po_qty ?? 0;
            return $po_qty * $usage;
        })
        ->addColumn('action', function($row) {
            return ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning btn-generate-sticker" item-desc="' . $row->item_desc . '" lot-no="' . $row->lot_no . '" title="Print QR Code" data-toggle="modal" data-target="#modal_LotNo_QRcode"><i class="fa fa-print fa-sm"></i></button>';
        })
        ->rawColumns(['raw_action', 'raw_status', 'action', 'received_by','received_dt'])
        ->make(true);
	}
	public function fn_view_lot_numbers_orig(Request $request){

        //------
        $subkitting_collect_obj = DB::select('
            SELECT
            a.kit_issuance_id,
            SUM(IF(c.status = 1, b.sub_kit_qty, 0)) AS sub_kit_qty
            FROM kittings AS a
            INNER JOIN sub_kittings AS b
                ON a.id = b.pats_kitting_id
                AND b.status = 1
            LEFT JOIN parts_preps_sub_kitting AS c
                ON b.id = c.sub_kittings_id
            WHERE
                a.status = 1
                -- SARGable date filter (Allows Index usage)
                AND a.created_at >= "2024-01-01 00:00:00"
            GROUP BY
                a.kit_issuance_id;
            ');
        // $subkitting_collect_obj = DB::select('
        //     select sum( case when (c.status = 1) then b.sub_kit_qty else 0 end ) as sub_kit_qty,
        //     a.kit_issuance_id
        //     from kittings a
        //     left join sub_kittings b
        //     on a.id = b.pats_kitting_id
        //     left join parts_preps_sub_kitting c
        //     on b.id = c.sub_kittings_id
        //     where a.status = 1 and b.status = 1
        //     AND a.created_at >= 2024-01-01 00:00:00
        //     group by a.kit_issuance_id
        //     ');

        // $subkitting_collect_obj = DB::select('
        //     select sum( case when (c.status = 1) then b.sub_kit_qty else 0 end ) as sub_kit_qty,
        //     a.kit_issuance_id
        //     from kittings a
        //     left join sub_kittings b
        //     on a.id = b.pats_kitting_id
        //     left join parts_preps_sub_kitting c
        //     on b.id = c.sub_kittings_id
        //     where a.status = 1 and b.status = 1
        //     group by a.kit_issuance_id
        //     ');
        $subkitting_collect = array();
        foreach ($subkitting_collect_obj as $key => $value) {
            $subkitting_collect[$value->kit_issuance_id] = $value->sub_kit_qty;
        }
        //------

		$lot_numbers = WBSKitIssuance::
            with('kit_issuance')
            ->where('po','=',$request['po'])
            ->where('issue_no','=',$request['issue_no'])
			->get();



        return DataTables::of($lot_numbers)
            ->addColumn('received_dt', function($lot_numbers){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $lot_numbers->id
                        ],
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

                $result = "---";
                if ( $parts_preps->count() ) {
                    $result = $parts_preps[0]->received_at;
                }
                return $result;
            })
            ->addColumn('received_by', function($lot_numbers){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $lot_numbers->id
                        ],
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
                $result = "---";
                if ( $parts_preps->count() ) {
                    $users = User::where('id', $parts_preps[0]->received_passed_by )->get();
                    if ( $users->count() ) {
                        $result = $users[0]->name;
                    }
                }
                return $result;
            })
            ->addColumn('raw_action', function($lot_number) use($subkitting_collect){
                $parts_preps_id = 0;
                $icon_partsprep = '';
                $icon_subkit = '';

            	$title = '';

                // $material_kitting = MaterialIssuanceSubSystem::where('issuance_no', $lot_number->issue_no )->get();
                // if ( $material_kitting->count() ) {
                    // $material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $material_kitting[0]['id'] )->get();
                    // if ( $material_issuances->count() ) {
                        // switch ($material_issuances[0]->status) {
                        //     case 1:
                $disabled_passed_btn = '';
                $disabled_failed_btn = '';
                $disabled_subkit_btn = 'disabled';
                $hidden_subkit_btn = 'hidden';

                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $lot_number->id
                        ],
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

                if ( $parts_preps->count() ) {
                    $parts_preps_id = $parts_preps[0]->id;
                    $disabled_passed_btn = 'disabled';
                    $disabled_failed_btn = 'disabled';
                    $disabled_subkit_btn = '';
                    if( $parts_preps[0]->status == 2 ){
                        $disabled_passed_btn = '';
                    }
                    if( $parts_preps[0]->status == 1 ){
                        $disabled = '';
                    }
                }
                        //         break;

                        //     default:
                        //         $title = '';//'Pending in Material Input';
                        //         break;
                        // }
                    // }
                    // else{
                    //     $title = '';//'Pending in Material Input';
                    // }
                // }
                // else{
                //     $title = '';//'Not found in WBS';
                // }
                //-----
                $hidden = 'hidden';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----
                $issued_qty = $lot_number->issued_qty;
                $sub_kit_qty = isset($subkitting_collect[$lot_number->id])?$subkitting_collect[$lot_number->id]:'x';
                if($sub_kit_qty!=='x'){
                    $hidden_subkit_btn = '';
                    $icon_subkit = '<i title="Has subkit" class="fa fa-puzzle-piece text-secondary pl-2 float-right"></i>';
                    if($issued_qty > $subkitting_collect[$lot_number->id]){
                    }
                }

                $result ='';
                $result.=' '.$icon_subkit;
                $result.='<div class="dropdown">';
                  $result.='<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                  // $result.='<i class="fas fa-ellipsis-v fa-sm"></i>';
                  $result.='</button>';
                  $result.='<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                    // $result.='<button '.$disabled.' title="Open details" class="dropdown-item btn_material_action btn_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i> Details</button>';
                    $result.=' <button '.$disabled_subkit_btn.' '.$hidden.' '.$hidden_subkit_btn.' title="Show Subkitting" class="dropdown-item btn_material_action btn_material_subkitting_details btn btn-success btn-sm py-0"><i class="fa fa-puzzle-piece fa-sm"></i> Subkitting</button>';
                    $result.=' <button '.$disabled_passed_btn.' '.$hidden.' title="Receive" class="dropdown-item btn_material_action btn_material_pass_details btn btn-success btn-sm py-0"><i class="fa fa-check-circle fa-sm"></i> Receive</button>';
                    // $result.=' <button '.$disabled_failed_btn.' '.$hidden.' title="Failed" class="dropdown-item btn_material_action btn_material_fail_details btn btn-warning btn-sm py-0"><i class="fa fa-exclamation-triangle fa-sm"></i> Failed</button>';

                  $result.='</div>';
                $result.='</div>';



                $result.=' <input type="hidden" class="col_material_id" value="'.$lot_number->id.'">';
                $result.=' <input type="hidden" class="col_material_code" value="'.$lot_number->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$lot_number['kit_issuance']->device_code.'">';
                $result.=' <input type="hidden" class="col_material_po" value="'.$lot_number->po.'">';
                $result.=' <input type="hidden" class="col_parts_preps_id" value="'.$parts_preps_id.'">';
                $result.=' <input type="hidden" class="col_with_partsprep" value="0">';

                  $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" item-desc='.$lot_number->item_desc.' lot-no='.$lot_number->lot_no.' title="Print QR Code" data-toggle="modal" data-target="#modal_LotNo_QRcode"><i class="fa fa-print fa-sm"></i></button>';



                return $result;
            })
            ->addColumn('raw_status', function($lot_number) use ($subkitting_collect){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';

				$parts_preps = PartsPrep::where([
	                    [
	                        'wbs_kit_issuance_id','=',
	                    	$lot_number->id
	                    ],
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

                if ( $parts_preps->count() ) {

                    switch ( $parts_preps[0]->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Received</span>';

                            //-----
                            if(isset($subkitting_collect[$lot_number->id])){
                                $issued_qty = $lot_number->issued_qty;
                                $sub_kit_qty = $subkitting_collect[$lot_number->id];

                                if($issued_qty > $subkitting_collect[$lot_number->id]){
                                    $result = '<span data-sorter="2" class="badge badge-secondary font-weight-normal">Incomplete</span>';
                                }
                            }

                            //-----
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
            ->addColumn('raw_usage', function($lot_number){
                $result = '---';
                $material_kitting_details = MaterialIssuanceDetails::where('issue_no', $lot_number->issue_no )->where('item', $lot_number->item )->get();
                if ( $material_kitting_details->count() ) {
                	$result = $material_kitting_details[0]->usage;
                }

                return $result;
            })
            ->addColumn('raw_rqd_qty', function($lot_number){
                $result = 0;
                $material_kitting_details = MaterialIssuanceDetails::where('issue_no', $lot_number->issue_no )->where('item', $lot_number->item )->get();
                if ( $material_kitting_details->count() ) {
                    $result = $material_kitting_details[0]->usage;
                }
                $result = $lot_number['kit_issuance']->po_qty*$result;
                return $result;
            })
            ->addColumn('action',function($lot_number){

                 $result = ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" item-desc='.$lot_number->item_desc.' lot-no='.$lot_number->lot_no.' title="Print QR Code" data-toggle="modal" data-target="#modal_LotNo_QRcode"><i class="fa fa-print fa-sm"></i></button>';

                return $result;

            })
            ->rawColumns(['raw_action','raw_status','action'])
            ->make(true);
	}
	public function fn_view_lot_numberstest(Request $request){

        //------
        $subkitting_collect_obj = DB::select('
            select sum( case when (c.status = 1) then b.sub_kit_qty else 0 end ) as sub_kit_qty,
            a.kit_issuance_id
            from kittings a
            left join sub_kittings b
            on a.id = b.pats_kitting_id
            left join parts_preps_sub_kitting c
            on b.id = c.sub_kittings_id
            where a.status = 1 and b.status = 1
            group by a.kit_issuance_id
            ');

        // $subkitting_collect_obj = DB::select('
        //     select sum( case when (c.status = 1) then b.sub_kit_qty else 0 end ) as sub_kit_qty,
        //     a.kit_issuance_id
        //     from kittings a
        //     left join sub_kittings b
        //     on a.id = b.pats_kitting_id
        //     left join parts_preps_sub_kitting c
        //     on b.id = c.sub_kittings_id
        //     where a.status = 1 and b.status = 1
        //     group by a.kit_issuance_id
        //     ');
        $subkitting_collect = array();
        foreach ($subkitting_collect_obj as $key => $value) {
            $subkitting_collect[$value->kit_issuance_id] = $value->sub_kit_qty;
        }
        //------

		$lot_numbers = WBSKitIssuance::
            with('kit_issuance')
            ->where('po','=',$request['po'])
            ->where('issue_no','=',$request['issue_no'])
			->get();



        return DataTables::of($lot_numbers)
            ->addColumn('received_dt', function($lot_numbers){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $lot_numbers->id
                        ],
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

                $result = "---";
                if ( $parts_preps->count() ) {
                    $result = $parts_preps[0]->received_at;
                }
                return $result;
            })
            ->addColumn('received_by', function($lot_numbers){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $lot_numbers->id
                        ],
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
                $result = "---";
                if ( $parts_preps->count() ) {
                    $users = User::where('id', $parts_preps[0]->received_passed_by )->get();
                    if ( $users->count() ) {
                        $result = $users[0]->name;
                    }
                }
                return $result;
            })
            ->addColumn('raw_action', function($lot_number) use($subkitting_collect){
                $parts_preps_id = 0;
                $icon_partsprep = '';
                $icon_subkit = '';

            	$title = '';

                // $material_kitting = MaterialIssuanceSubSystem::where('issuance_no', $lot_number->issue_no )->get();
                // if ( $material_kitting->count() ) {
                    // $material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $material_kitting[0]['id'] )->get();
                    // if ( $material_issuances->count() ) {
                        // switch ($material_issuances[0]->status) {
                        //     case 1:
                $disabled_passed_btn = '';
                $disabled_failed_btn = '';
                $disabled_subkit_btn = 'disabled';
                $hidden_subkit_btn = 'hidden';

                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $lot_number->id
                        ],
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

                if ( $parts_preps->count() ) {
                    $parts_preps_id = $parts_preps[0]->id;
                    $disabled_passed_btn = 'disabled';
                    $disabled_failed_btn = 'disabled';
                    $disabled_subkit_btn = '';
                    if( $parts_preps[0]->status == 2 ){
                        $disabled_passed_btn = '';
                    }
                    if( $parts_preps[0]->status == 1 ){
                        $disabled = '';
                    }
                }
                        //         break;

                        //     default:
                        //         $title = '';//'Pending in Material Input';
                        //         break;
                        // }
                    // }
                    // else{
                    //     $title = '';//'Pending in Material Input';
                    // }
                // }
                // else{
                //     $title = '';//'Not found in WBS';
                // }
                //-----
                $hidden = 'hidden';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----
                $issued_qty = $lot_number->issued_qty;
                $sub_kit_qty = isset($subkitting_collect[$lot_number->id])?$subkitting_collect[$lot_number->id]:'x';
                if($sub_kit_qty!=='x'){
                    $hidden_subkit_btn = '';
                    $icon_subkit = '<i title="Has subkit" class="fa fa-puzzle-piece text-secondary pl-2 float-right"></i>';
                    if($issued_qty > $subkitting_collect[$lot_number->id]){
                    }
                }

                $result ='';
                $result.=' '.$icon_subkit;
                $result.='<div class="dropdown">';
                  $result.='<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                  // $result.='<i class="fas fa-ellipsis-v fa-sm"></i>';
                  $result.='</button>';
                  $result.='<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                    // $result.='<button '.$disabled.' title="Open details" class="dropdown-item btn_material_action btn_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i> Details</button>';
                    $result.=' <button '.$disabled_subkit_btn.' '.$hidden.' '.$hidden_subkit_btn.' title="Show Subkitting" class="dropdown-item btn_material_action btn_material_subkitting_details btn btn-success btn-sm py-0"><i class="fa fa-puzzle-piece fa-sm"></i> Subkitting</button>';
                    $result.=' <button '.$disabled_passed_btn.' '.$hidden.' title="Receive" class="dropdown-item btn_material_action btn_material_pass_details btn btn-success btn-sm py-0"><i class="fa fa-check-circle fa-sm"></i> Receive</button>';
                    // $result.=' <button '.$disabled_failed_btn.' '.$hidden.' title="Failed" class="dropdown-item btn_material_action btn_material_fail_details btn btn-warning btn-sm py-0"><i class="fa fa-exclamation-triangle fa-sm"></i> Failed</button>';

                  $result.='</div>';
                $result.='</div>';



                $result.=' <input type="hidden" class="col_material_id" value="'.$lot_number->id.'">';
                $result.=' <input type="hidden" class="col_material_code" value="'.$lot_number->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$lot_number['kit_issuance']->device_code.'">';
                $result.=' <input type="hidden" class="col_material_po" value="'.$lot_number->po.'">';
                $result.=' <input type="hidden" class="col_parts_preps_id" value="'.$parts_preps_id.'">';
                $result.=' <input type="hidden" class="col_with_partsprep" value="0">';

                  $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" item-desc='.$lot_number->item_desc.' lot-no='.$lot_number->lot_no.' title="Print QR Code" data-toggle="modal" data-target="#modal_LotNo_QRcode"><i class="fa fa-print fa-sm"></i></button>';



                return $result;
            })
            ->addColumn('raw_status', function($lot_number) use ($subkitting_collect){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';

				$parts_preps = PartsPrep::where([
	                    [
	                        'wbs_kit_issuance_id','=',
	                    	$lot_number->id
	                    ],
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

                if ( $parts_preps->count() ) {

                    switch ( $parts_preps[0]->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Received</span>';

                            //-----
                            if(isset($subkitting_collect[$lot_number->id])){
                                $issued_qty = $lot_number->issued_qty;
                                $sub_kit_qty = $subkitting_collect[$lot_number->id];

                                if($issued_qty > $subkitting_collect[$lot_number->id]){
                                    $result = '<span data-sorter="2" class="badge badge-secondary font-weight-normal">Incomplete</span>';
                                }
                            }

                            //-----
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
            ->addColumn('raw_usage', function($lot_number){
                $result = '---';
                $material_kitting_details = MaterialIssuanceDetails::where('issue_no', $lot_number->issue_no )->where('item', $lot_number->item )->get();
                if ( $material_kitting_details->count() ) {
                	$result = $material_kitting_details[0]->usage;
                }

                return $result;
            })
            ->addColumn('raw_rqd_qty', function($lot_number){
                $result = 0;
                $material_kitting_details = MaterialIssuanceDetails::where('issue_no', $lot_number->issue_no )->where('item', $lot_number->item )->get();
                if ( $material_kitting_details->count() ) {
                    $result = $material_kitting_details[0]->usage;
                }
                $result = $lot_number['kit_issuance']->po_qty*$result;
                return $result;
            })
            ->addColumn('action',function($lot_number){

                 $result = ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" item-desc='.$lot_number->item_desc.' lot-no='.$lot_number->lot_no.' title="Print QR Code" data-toggle="modal" data-target="#modal_LotNo_QRcode"><i class="fa fa-print fa-sm"></i></button>';

                return $result;

            })
            ->rawColumns(['raw_action','raw_status','raw_rqd_qty','raw_usage'])
            ->make(true);
	}
    public function fn_view_sakidashi_issuance(Request $request){
        $tbl_wbs_sakidashi_issuance = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')
            ->where('po_no',$request['po_number'])
            ->whereHas('tbl_wbs_sakidashi_issuance_item',function($query) use ($request){
                    $query->where('lot_no', $request->txt_scan_sakidashi_lot);
                },
            )
        ->get();
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
                    $users = User::where('id', $parts_preps[0]->received_passed_by )->get();
                    if ( $users->count() ) {
                        $result = $users[0]->name;
                    }
                }
                return $result;
            })
            ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';

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
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Received</span>';
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
                    // $icon_partsprep = ($parts_preps[0]->with_partsprep)?'<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>':'';
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
                $result ='';
                $result.='<div class="dropdown">';
                  $result.='<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                  // $result.='<i class="fas fa-ellipsis-v fa-sm"></i>';
                  $result.='</button>';
                  $result.='<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                    // $result.='<button '.$disabled.' title="Open details" class="dropdown-item btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i> Details</button>';
                    $result.=' <button '.$disabled_passed_btn.' '.$hidden.' title="Receive" class="dropdown-item btn_material_action btn_sakidashi_material_pass_details btn btn-success btn-sm py-0"><i class="fa fa-check-circle fa-sm"></i> Receive</button>';
                    // $result.=' <button '.$disabled_failed_btn.' '.$hidden.' title="Failed" class="dropdown-item btn_material_action btn_sakidashi_material_fail_details btn btn-warning btn-sm py-0"><i class="fa fa-exclamation-triangle fa-sm"></i> Failed</button>';

                  $result.='</div>';
                $result.='</div>';

                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                // $result.=' <input type="hidden" class="col_material_code" value="'.$tbl_wbs_sakidashi_issuance->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';
                $result.=' <input type="hidden" class="col_material_po" value="'.$tbl_wbs_sakidashi_issuance->po_no.'">';
                $result.=' <input type="hidden" class="col_parts_preps_id" value="'.$parts_preps_id.'">';
                $result.=' <input type="hidden" class="col_with_partsprep" value="0">';


                //addition of printing
                // $result = ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" item-desc='.$tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item[0]->item_desc.' lot-no='.$tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item[0]->lot_no.' title="Print QR Code" data-toggle="modal" data-target="#modal_LotNo_QRcode"><i class="fa fa-print fa-sm"></i></button>';

                //edited by nessa 0712022
                $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" item-desc='.$tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item_desc.' lot-no='.$tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->lot_no.' title="Print QR Code" data-toggle="modal" data-target="#modal_LotNo_QRcode"><i class="fa fa-print fa-sm"></i></button>';


                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }
    public function fn_view_subkitting(Request $request){

        $subkitting = SubKitting::with([
            'kitting',
            'partsprepsubkitting'
            ])
            ->where('status', 1)
            ->whereHas('kitting',
               function($query) use ($request){
                    $query->where('kit_issuance_id', $request->col_material_id);
                },
            )
            ->get();

        return DataTables::of($subkitting)
            ->addColumn('received_dt', function($subkitting){
                $temp_table = PartsPrepSubKitting::where('sub_kittings_id', $subkitting->id )->get();
                $result = "---";
                if ( $temp_table->count() ) {
                    $result = $temp_table[0]->created_at;
                }
                return $result;
            })
            ->addColumn('received_by', function($subkitting){
                $temp_table = PartsPrepSubKitting::where('sub_kittings_id', $subkitting->id )->get();
                $result = "---";
                if ( $temp_table->count() ) {
                    $users = User::where('id', $temp_table[0]->created_by )->get();
                    if ( $users->count() ) {
                        $result = $users[0]->name;
                    }
                }
                return $result;
            })
            ->addColumn('status', function($subkitting){
                $material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $subkitting->id )->get();
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';
                if ( $subkitting->partsprepsubkitting !=null ) {
                    $status = $subkitting->partsprepsubkitting->status;
                    if($status==1){
                        $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Received</span>';
                    }
                    if($status==2){
                        $result = '<span data-sorter="1" class="badge badge-warning font-weight-normal">Returned to WHS</span>';
                    }
                }
                return $result;
            })
            ->addColumn('action', function($subkitting){
                //-----
                $disabled = ' disabled ';
                if( Auth::user()->position == 3 ){
                    $disabled = '';
                }
                //-----
                // $material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $subkitting->id )->get();
                // if ( $material_issuances->count() ) {
                //     $disabled = 'disabled';
                // }
                if ( $subkitting->partsprepsubkitting !=null ) {
                    $status = $subkitting->partsprepsubkitting->status;
                    if($status==1){
                        $disabled = 'disabled';
                    }
                }

                $sub_kitting_desc_arr = explode('|', $subkitting->sub_kit_desc);
                $sub_kitting_code = trim($sub_kitting_desc_arr[4]).' | '.trim($sub_kitting_desc_arr[5]).' | '.trim($sub_kitting_desc_arr[9]);


                $result = "";
                $result.='<input type="checkbox" '.$disabled.' class="ckb_sub_kitting">';
                $result.=' <input type="hidden" class="tbl_sub_kitting_id" value="'.$subkitting->id.'">';
                $result.=' <input type="hidden" class="tbl_sub_kitting_code" value="'.$sub_kitting_code.'">';
                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }
	public function fn_insert_materialissuance(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            DB::beginTransaction();
            try {
				$table = new MaterialIssuance;

				$table->tbl_wbs_material_kitting_id = $request->tbl_wbs_material_kitting_id;
				$table->created_by = $user[0]->id;
				$table->updated_by = $user[0]->id;
				$table->status = 1;

				$table->save();
                $result = $this->return_result(1,1,1,1);
                DB::commit();
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
    public function insert_partsprep_subkitting(Request $request){
        $result = $this->return_result(0,0,0,0);
        //insert
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            foreach ($request->arr_subkitting as $key => $value) {
                DB::beginTransaction();
                try {
                    PartsPrepSubKitting::insert(
                        [
                            'sub_kittings_id'=>$value['sub_kitting_id_arr'],
                            'status'=>$request->status,
                            'received_by'=>$user[0]->id,
                            'received_passed_by'=>$user[0]->id,
                            'created_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'received_at'=>date('Y-m-d H:i:s'),
                            'received_passed_at'=>date('Y-m-d H:i:s'),
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
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }
    public function generate_lotno_qrcode(Request $request){

        try{
            if(isset($request->qrcode)){

                $qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate($request->qrcode);

                return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode), 'user' => $request->qrcode]);
            }
            else{
                return response()->json(['result' => "0"]);
            }
        }
        catch(\Exception $e){
            return response()->json(['result' => "0"]);
        }


    }


}
