<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\oqcLotApp;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;


use App\Model\AccessoryTag;
use App\Model\AccessoryTagItems;
use App\Model\AccessoryTagPrintHistory;

use App\Model\MaterialIssuanceSubSystem;
use App\Model\Device;
//----
use App\Model\MaterialIssuance;
use App\Model\WBSKitIssuance;
// use App\Model\MaterialIssuanceSubSystem;
use App\Model\PartsPrep;


use GuzzleHttp\Client;


use DataTables;

define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);


class AccessoryTagPrintingController extends Controller
{
    public function fn_view_c3labelprinting(Request $request)
    {
        return view('c3labelprinting');
    }

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

	public function fn_select_datatable_accessory_recent_po(Request $request){
        $accessory_tag = AccessoryTag::with('user_created_by','user_deleted_by')
            ->where('deleted_at','=',null)
            ->orderBy('created_at','DESC')
            ->get();

        return DataTables::of($accessory_tag)
            ->addColumn('raw_data', function($accessory_tag){
            	$raw_action = '';

                $raw_action.='<button title="Open details" class="td_btn_action td_btn_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                $raw_action.=' <input type="hidden" class="td_id" value="'.$accessory_tag->id.'">';


                $raw_details = '';
                $raw_details .= 'Created '.$accessory_tag['created_at'].' by '.$accessory_tag->user_created_by['name'];
                $raw_details .= $accessory_tag['updated_at']?'<br>Updated '.$accessory_tag['updated_at']:'';
                $raw_details .= $accessory_tag['remarks']?'<br>Remarks: '.$accessory_tag['remarks']:'';
                $raw_details .= $accessory_tag['deleted_at']?'<br>Deleted '.$accessory_tag['deleted_at'].' by '.$accessory_tag->user_deleted_by['name'].': '.$accessory_tag['delete_remarks']:'';

                $raw_data = array();
                $raw_data['action']     = $raw_action;
                $raw_data['details']    = $raw_details;

                return $raw_data;
            })
            ->rawColumns(['raw_data.action','raw_data.details'])
            ->make(true);
	}

    public $ctr = 0;
    public function fn_select_datatable_accessory(Request $request){
        $this->ctr = 0;
        $accessory_tag_items = AccessoryTagItems::with('user_created_by','user_deleted_by','user_counted_by','accessory_tag')
            ->where('accessory_tag_id','=',$request->td_id)
            // ->where('deleted_at','=',null)
            ->orderBy('deleted_at','ASC')
            ->orderBy('created_at','DESC')
            ->get();
        return DataTables::of($accessory_tag_items)
            ->addColumn('raw_data', function($accessory_tag_items){
                $this->ctr = $this->ctr+1;
                $result = '';
                $disabled = '';
                $raw_action = '';
                $is_deleted = '';

                if($accessory_tag_items['deleted_at']){
                    $is_deleted     = 'is_deleted';
                    $disabled       = 'disabled';
                }

                $box_device_name = $accessory_tag_items['accessory_tag']->device_name;
                $box_device_name = chunk_split($box_device_name,33,"^");
                $box_device_name = explode('^', $box_device_name);
                $box_device_name = implode('<br>', $box_device_name);
                //---
                $box_accessory_name = $accessory_tag_items->item_desc;
                $box_accessory_name = chunk_split($box_accessory_name,30,"^");
                $box_accessory_name = explode('^', $box_accessory_name);
                $box_accessory_name = implode('<br>', $box_accessory_name);

                //---
                $html_box_item = '
                  <tr>
                    <td></td>
                    <td colspan="2" class="border-bottom">'.$this->ctr.'. '.$box_accessory_name.'</td>
                    <td></td>
                    <td class="border-bottom text-center bold">'.$accessory_tag_items->qty.'</td>
                    <td></td>
                  </tr>
                ';
                //---
                $html_plastic_item = '
                  <tr>
                    <td colspan="6">Product Name: <u class="bold">'.$box_device_name.'</u></td>
                  </tr>
                  <tr>
                    <td colspan="6">Accessory Name: <u class="bold">'.$box_accessory_name.'</u></td>
                  </tr>
                  <tr>
                    <td colspan="3">Quantity: <u class="bold">'.$accessory_tag_items->qty.'</u></td>
                    <td colspan="3">Usage per Socket: <u class="bold">'.$accessory_tag_items->usage_qty.'</u></td>
                  </tr>
                  <tr>
                    <td colspan="6"></td>
                  </tr>
                  <tr>
                    <td colspan="6">Counted by/Date: <u class="bold">'.$accessory_tag_items->user_counted_by->name.' / '. date('Y-m-d',strtotime($accessory_tag_items->counted_at)) .'</u></td>
                  </tr>
                ';

                $raw_action .= '<button '.$disabled.' class="btn btn-info btn-sm btn_td_print"><i class="fa fa-print fa-sm"></i></button>';
                $raw_action .= ' <button '.$disabled.' type="button" title="Remove" class="td_btn_delete btn btn-sm btn-danger"><i class="fa fa-trash-alt fa-sm"></i></button>';
                // $raw_action .= '<input '.$disabled.' type="checkbox" class="td_ckb mr-1">';
                $raw_action .=' <input type="hidden" class="td_id '.$is_deleted.'" value="'.$accessory_tag_items->id.'">';
                $raw_action .=' <textarea hidden class="html_box_item">'.$html_box_item.'</textarea>';
                $raw_action .=' <textarea hidden class="html_plastic_item">'.$html_plastic_item.'</textarea>';
                // $raw_action .= '<input type="hidden" class="td_box_device_name" value="'.$box_device_name.'">';
                // $raw_action .= '<input type="hidden" class="td_box_accessory_name" value="'.$html_box_item.'">';

                $raw_details = '';
                $raw_details .= 'Created '.$accessory_tag_items['created_at'].' by '.$accessory_tag_items->user_created_by['name'];
                $raw_details .= $accessory_tag_items['updated_at']?'<br>Updated '.$accessory_tag_items['updated_at']:'';
                $raw_details .= $accessory_tag_items['remarks']?'<br>Remarks: '.$accessory_tag_items['remarks']:'';
                $raw_details .= $accessory_tag_items['deleted_at']?'<br>Deleted '.$accessory_tag_items['deleted_at'].' by '.$accessory_tag_items->user_deleted_by['name'].': '.$accessory_tag_items['delete_remarks']:'';

                $raw_counted_by = $accessory_tag_items->user_counted_by['name']?$accessory_tag_items->user_counted_by['name']:'';

                $raw_data = array();
                $raw_data['action']         = $raw_action;
                $raw_data['details']        = $raw_details;
                $raw_data['raw_counted_by'] = $raw_counted_by;

                return $raw_data;
            })
            ->rawColumns(['raw_data.action','raw_data.details','raw_data.raw_counted_by'])
            ->make(true);
    }

    public function fn_select_accessory_details(Request $request){
        $accessory_tag = AccessoryTag::
        	where('id',$request->td_id)
            ->where('deleted_at','=',null)
            ->get()->take(1);
        return $accessory_tag;
    }

    public function fn_select_accessory_name_list(Request $request){
        $position_arrs = explode(',', $request->position_arr);
        $accessory_tag = User::
            whereIn('position',$position_arrs)
            ->where('status','=',1)
            ->get();
        return $accessory_tag;
    }

    public function fn_insert_accessory_tag(Request $request){
        $result = $this->return_result(0,0,0,0);
        // 0-N/A,1-Prod Supervisor,2-QC Supervisor,3-Material Handler,4-Operator,5-Inspector
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1)
                    ->orWhere('position',2);
            })
            ->get();
        if($user->count() > 0){
            //insert
            DB::beginTransaction();
            try {
                $last_id = AccessoryTag::insertGetId(
                    [
                        'po_no'=>$request->txt_print_po_no,
                        'device_name'=>$request->txt_print_device_name,
                        'remarks'=>$request->txt_print_po_remarks,
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
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    public function fn_select_accessory_wbs_item_list(Request $request){
        $lot_numbers = WBSKitIssuance::
            with('kit_issuance')
            ->where('item_desc','like','%'.$request['item_desc'].'%')
            ->whereHas('kit_issuance',function($query) use ($request){
                $query->where('device_name',$request['device_name']);
            })
            ->skip(0)->take(10)
            ->get();
        return $lot_numbers;
    }

    public function fn_insert_accessory_tag_item(Request $request){
        $result = $this->return_result(0,0,0,0);
        // 0-N/A,1-Prod Supervisor,2-QC Supervisor,3-Material Handler,4-Operator,5-Inspector
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1)
                    ->orWhere('position',2);
            })
            ->get();
        if($user->count() > 0){
            //insert
            DB::beginTransaction();
            try {
                $last_id = AccessoryTagItems::insertGetId(
                    [
                        'accessory_tag_id'=>$request->data_id,
                        'item_desc'=>$request->txt_add_accessory_item_name,
                        'qty'=>$request->txt_add_accessory_qty,
                        'usage_qty'=>$request->txt_add_accessory_usage,
                        'counted_by'=>$request->txt_add_accessory_counted_by,
                        'counted_at'=>$request->txt_add_accessory_counted_date,
                        'remarks'=>$request->txt_add_accessory_remarks,
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
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    public function fn_delete_accessory_item(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1)
                    ->orWhere('position',2);
            })
            ->get();
        if($user->count() > 0){
            if( $request->data_id ){
                //update
                DB::beginTransaction();
                try {
                    AccessoryTagItems::where('id',$request->data_id)
                        ->update(
                            [
                                'deleted_at'=>date('Y-m-d H:i:s'),
                                'delete_remarks'=>$request->txt_confirm_remarks,
                                'deleted_by'=>$user[0]->id,
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{//no rec
                $result = $this->return_result(0,0,0,0);
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }














}
