<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//models
use App\Model\ProductionRuncard;
use App\Model\oqcLotApp;
use App\Model\oqcVIRTS;
use App\Model\PackingCode;
use App\Model\PackingInspectionTS;

use App\User;

use DataTables;
use Carbon\Carbon;


class OQCVIRTSController extends Controller
{
    public function load_oqcvir_table(Request $request)
    {
		$lotapps = oqcLotApp::with(['oqcvir_details_ts','oqcvir_details_ts.user_details_ts'])->where('po_num', $request->po_no)->get();

		return DataTables::of($lotapps)
			->addColumn('action',function($lotapp){

				$result = '';

				if($lotapp->oqcvir_details_ts != null)
				{
					switch($lotapp->oqcvir_details_ts->status)
					{
						case 0:
						{
							$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_checkpoints" data-toggle="modal" data-target="#modalOQCVIR" title="Add Visual Inspection Result" po-num="'.$lotapp->po_num.'" lot-batch-no="'.$lotapp->lot_batch_no.'"><i class="fa fa-check-square fa-sm"></i></button>';
							break;
						}
						case 1:
						{
							$result = " ";
							break;
						}
						case 2:
						{
							$result = " ";
							break;
						}
						default:
						{	
							$result = "---";
							break;
						}
					}
				}
				else
				{
					 $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-success btn_start_inspection" data-toggle="modal" data-target="#modalStartInspection" title="Start Visual Inspection" po-num="'.$lotapp->po_num.'" lot-batch-no="'.$lotapp->lot_batch_no.'"><i class="fa fa-check-square fa-sm"></i></button>';
				}

				return $result;	


			})
			->addColumn('status',function($lotapp){

				if($lotapp->oqcvir_details_ts != null)
				{
					switch($lotapp->oqcvir_details_ts->status)
					{
						case 0:
						{
							$result = "On-going Inspection";
							break;
						}
						case 1:
						{
							$result = "Done, Lot OK";
							break;
						}
						case 2:
						{
							$result = "LOT NG";
							break;
						}
						default:
						{	
							$result = "---";
							break;
						}
					}
				}
				else
				{
					$result = 'For Inspection';
				}

				return $result;	
				
			})
			->addColumn('submission',function($lotapp){

				$result = $lotapp->submission;

				return $result;
				
			})
			->addColumn('lot_batch_no',function($lotapp){

				$result = $lotapp->lot_batch_no;

				return $result;
				
			})
			->addColumn('output_qty',function($lotapp){

				$result = $lotapp->lot_qty;

				return $result;
				
			})
			->addColumn('inspected_by',function($lotapp){

				if($lotapp->oqcvir_details_ts != null)
				{
					if($lotapp->oqcvir_details_ts->insp_name != null)
					{
						$result = $lotapp->oqcvir_details_ts->user_details_ts->name;
					}
					else
					{
						$result = '---';
					}
					
				}
				else
				{
					$result = '---';
				}

				return $result;	
				
			})
			->addColumn('packing_code',function($lotapp){

				if($lotapp->oqcvir_details_ts != null)
				{
					if($lotapp->oqcvir_details_ts->packing_code != null)
					{
						$result = $lotapp->oqcvir_details_ts->packing_code;
					}
					else
					{
						$result = '---';
					}
					
				}
				else
				{
					$result = '---';
				}

				return $result;

			})
		->make(true);
    }

    public function view_start_inspection(Request $request)
    {
    	$oqclotapp = ProductionRuncard::where('runcard_no',$request->batch)->get();

    	$oqcvir = oqcVIRTS::where('lot_batch_no',$request->batch)->get();

		//return $oqcvir;

    	return response()->json(['oqclotapp' => $oqclotapp, 'oqcvir' => $oqcvir]);
    }

    public function add_start_visual_inspection(Request $request)
    {
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		try
		{
			//insert record
			oqcVIRTS::insert([
				'po_num' => $request->name_start_po,
				'lot_batch_no' => $request->name_start_lotbatch,
				'status' => 0,

				'empid' => $request->employee_number_scanner_start,
				'insp_stime' => date('Y-m-d H:i:s'),
	            'created_at' => date('Y-m-d H:i:s'),
	            'updated_at' => date('Y-m-d H:i:s')
			]);

			DB::commit();

	        return response()->json(['result' => "1"]);
		}
		catch(\Exception $e)
		{
            DB::rollback();
            return response()->json(['result' => $e]);
        }
	}

	public function submit_visual_inspection(Request $request)
    {
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		try
		{
			//insert record
			oqcVIRTS::insert([
				'po_num' => $request->name_start_po,
				'lot_batch_no' => $request->name_start_lotbatch,
				'status' => 0,

				'empid' => $request->employee_number_scanner_start,
				'insp_stime' => date('Y-m-d H:i:s'),
	            'created_at' => date('Y-m-d H:i:s'),
	            'updated_at' => date('Y-m-d H:i:s')
			]);

			DB::commit();

	        return response()->json(['result' => "1"]);
		}
		catch(\Exception $e)
		{
            DB::rollback();
            return response()->json(['result' => $e]);
        }
	}

	public function submit_inspection_result(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		try
		{
			//insert record
			oqcVIRTS::where('po_num',$request->name_po_no)->where('lot_batch_no', $request->name_lotbatch_no)->update([
				
				'status' => $request->name_result,
				'oqc_sample' => $request->name_oqc_sample_size,
				'ok_qty' => $request->name_ok_qty,
				'ng_qty' => $request->name_ng_qty,
				'insp_etime' => $request->name_end_datetime,
				'use_template' => $request->name_terminal,
				'yd_requirement' => $request->name_yd_label,
				'csh_coating' => $request->name_csh_coating,
				'acc_req' => $request->name_accessory_requirement,
				'coc_req' => $request->name_coc_requirement,
				'insp_name' => $request->name_oqc_inspector_name,
				'result' => $request->name_result,
				'remarks' => $request->name_remarks,

	            'updated_at' => date('Y-m-d H:i:s')
			]);

			DB::commit();

	        return response()->json(['result' => "1"]);
		}
		catch(\Exception $e)
		{
            DB::rollback();
            return response()->json(['result' => $e]);
        }
	}

	public function load_oqc_inspector_details(Request $request)
	{
		$oqc_inspector = User::with(['oqc_stamps'])->where('employee_id',$request->emp_id)->where('oqc_stamps.')->get();

		if(count($oqc_inspector) > 0)
		{
			return response()->json(['result' => 1, 'oqc_inspector' => $oqc_inspector]);
		}
		else
		{
			return response()->json(['result' => 2]);
		}
	}

	//DELODER

	//----------------PATS TS FUNCTIONS-----------------
	public function search_packing_confirmation_lot(Request $request)
	{
		$lot_details = oqcVIRTS::with(['oqclotapp_details'])->where('po_num',$request->po_num)->where('lot_batch_no',$request->lot_number)->where('status',1)->orderBy('updated_at','desc')->get();

		if(count($lot_details) > 0)
		{
			return response()->json(['result' => 1, 'lot_details' => $lot_details]);
		}
		else
		{
			return response()->json(['result' => 2]);
		}
	}

	public function load_packing_confirmation_lots(Request $request)
	{
		if($request->packing_lots == null)
		{
			$confirmation_lots = [];
		}
		else
		{
			$confirmation_lots = $request->packing_lots;
		}

		return DataTables::of($confirmation_lots)
		->addColumn('action',function($confirmation_lot)
		{
			$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-danger btn-remove-confirmation-lot" title="Remove item from list" arr-ctr='.$confirmation_lot['arr_ctr'].'><i class="fa fa-times fa-sm"></i></button>';

			return $result;
		})
		->addColumn('lot_num',function($confirmation_lot)
		{
			$result = $confirmation_lot['lot_num'];

			return $result;
		})
		->addColumn('lot_qty',function($confirmation_lot)
		{
			$result = $confirmation_lot['lot_qty'];

			return $result;
		})
		->rawColumns(['action'])
		->make(true);
	}

	public function load_packing_inspection_table(Request $request)
	{
		$packing_inspections = PackingInspectionTS::where('po_num',$request->po_num)->where('logdel',1)->get();
		
		return DataTables::of($packing_inspections)
		->addColumn('action',function($packing_inspection){

			$status = $packing_inspection->status;

			switch($status)
			{
				case 1:
				{
					$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn-add-packing-inspection" data-toggle="modal" data-target="#modalPackingInspection" title="Inspect Items" device-code="'.$packing_inspection->device_code.'"><i class="fa fa-search fa-sm"></i></button>';

					break;
				}
				case 2:
				{
					$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-success btn-check-inspection-history" title="View Items for Inspection" device-code="'.$packing_inspection->device_code.'" data-toggle="modal" data-target="#modalViewInspectionHistory"><i class="fa fa-history fa-sm"></i></button>';

					$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-primary btn-final-qc-packing-inspection" data-toggle="modal" data-target="#modalFinalQCPackingInspection" title="Final QC Packing Inspection" device-code="'.$packing_inspection->device_code.'"><i class="fa fa-archive fa-sm"></i></button>';

					break;
				}
				case 3:
				{

					$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-success btn-check-inspection-history" title="View Items for Inspection" device-code="'.$packing_inspection->device_code.'" data-toggle="modal" data-target="#modalViewInspectionHistory"><i class="fa fa-history fa-sm"></i></button>';

					break;
				}
				case 4:
				{
					break;
				}
				default:
				{
					$result = '<span class="badge badge-pill badge-warning">Data Error</span>';
					break;
				}
			}			

			return $result;

		})
		->addColumn('device_code',function($packing_inspection){
			
			$result = $packing_inspection->device_code;

			return $result;
		})
		->addColumn('total_lot_qty',function($packing_inspection){
			
			$result = $packing_inspection->total_lot_qty;

			return $result;

		})
		->addColumn('status',function($packing_inspection){
			
			$status = $packing_inspection->status;

			switch($status)
			{
				case 1:
				{
					$result = '<span class="badge badge-pill badge-info">For Preliminary QC Packing Inspection</span>';
					break;
				}
				case 2:
				{
					$result = '<span class="badge badge-pill badge-success">Preliminary QC Packing Inspection Done</span>';

					$result .= '<br><span class="badge badge-pill badge-primary">For Final QC Packing Inspection</span>';
					break;
				}
				case 3:
				{	
					$result = '<span class="badge badge-pill badge-success">Final QC Packing Inspection Done</span>';

					break;
				}
				case 4:
				{
					break;
				}
				default:
				{
					$result = '<span class="badge badge-pill badge-warning">Data Error</span>';
					break;
				}
			}

			return $result;

		})
		->rawColumns(['action','status'])
		->make(true);
	}

	public function load_packing_inspection_lots_table(Request $request)
	{
		$inspection_lots = oqcVIRTS::with(['oqclotapp_details'])->where('packing_code',$request->packing_code)->get();

		return DataTables::of($inspection_lots)
		->addColumn('lot_batch_no',function($inspection_lot){

			$result = $inspection_lot->lot_batch_no;

			return $result;

		})
		->addColumn('quantity',function($inspection_lot){

			$result = $inspection_lot->oqclotapp_details->lot_qty;

			return $result;

		})
		->addColumn('result',function($inspection_lot){

			$result = $inspection_lot->result;

			switch($result)
			{
				case 1:
				{
					$result2 = '<span class="badge badge-pill badge-success">OK</span>';
					break;
				}
				case 2:
				{	
					$result2 = '<span class="badge badge-pill badge-danger">NG</span>';
					break;
				}
				default:
				{	
					$result2 = '<span class="badge badge-pill badge-warning">Data Error</span>';
					break;
				}
			}

			return $result2;
		})
		->rawColumns(['result'])
		->make(true);
	}

	public function submit_packing_confirmation(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		//numbers for autogen
		$autogenNum = 1;


		$series_name = explode("-",$request->add_packing_confirmation_device_name)[0];
		$series_id = PackingCode::where('series_name',$series_name)->first();

		//device code to be gotten in a table

		if($series_id != null)
		{
			$device = str_pad($series_id->series_id,3,"0",STR_PAD_LEFT);
		}
		else
		{
			$device = "***";
		}


		//month now
		$month = date('m');

		//box count for month
		$packing_lots = PackingInspectionTS::whereMonth('created_at',$month)->distinct('device_code')->count('device_code');

		if($packing_lots != null)
		{
			$autogenNum = $packing_lots + 1;
		}
		else
		{
			$autogenNum = 1;
		}

		//this is the device code
		$device_code = $device . $month . "-" . str_pad($autogenNum, 3, "0", STR_PAD_LEFT);


		$total_lot_qty = 80;

		$arrayLots = json_decode($request->arrLots, TRUE);

		DB::beginTransaction();

		try
		{
			PackingInspectionTS::insert([
				'po_num' => $request->add_packing_confirmation_po_num,
				'device_code' => $device_code,
				'total_lot_qty' => $total_lot_qty,
				'anti_rust_inclusion' => $request->anti_rust_inclusion,
				'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1
			]);

			if(count($arrayLots) > 0)
			{
					for($i = 0; $i < count($arrayLots); $i++)
					{
							oqcVIRTS::where('lot_batch_no', $arrayLots[$i]['lot_num'])->
							update([
								'packing_code' => $device_code
							]);
					}

			}

			DB::commit();

			return response()->json(['result' => "1"]);
		}
		catch(\Exception $e)
		{
	        DB::rollback();
	        return response()->json(['result' => $e]);
	    }
	}

	public function load_packing_inspection_details(Request $request)
	{
		$inspection_details = PackingInspectionTS::where('device_code',$request->device_code)->where('logdel',1)->get();

		if(count($inspection_details) > 0)
		{
			return response()->json(['result' => 1, 'inspection_details' => $inspection_details]);
		}
		else
		{
			return response()->json(['result' => 2]);
		}
	}

	public function submit_packing_inspection(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

			$oqc_inspector = User::with(['oqc_stamps'])->where('employee_id', $request->txt_search_inspector_id)->whereIn('position',[1,5])->get();

			if(count($oqc_inspector) > 0)
			{
				DB::beginTransaction();

				try
				{
					PackingInspectionTS::where('device_code',$request->packing_inspection_device_code)->update([

						'packing_type' => $request->add_packing_type,
						'packing_unit_condition' => $request->add_unit_condition,
						'packing_inspection_datetime' => $request->add_inspection_datetime,
						'prelim_oqc_inspector_id' => $oqc_inspector[0]->id,
						'status' => 2,
						'updated_at' => date('Y-m-d H:i:s'), 
					]);

					DB::commit();

					return response()->json(['result' => 1]);
				}
				catch(\Exception $e)
				{
		            DB::rollback();
		            return response()->json(['result' => $e]);
		        }
			}
			else
			{
				return response()->json(['result' => 2]);
			}		
	}

	//FINAL QC PACKING INSPECTION 
	public function load_final_packing_inspection_details(Request $request)
	{
		$inspection_details = PackingInspectionTS::where('device_code',$request->device_code)->where('logdel',1)->get();

		if(count($inspection_details) > 0)
		{
			return response()->json(['result' => 1, 'inspection_details' => $inspection_details]);
		}
		else
		{
			return response()->json(['result' => 2]);
		}
	}

	public function check_packing_operator(Request $request)
	{
		$packing_operator = User::where('employee_id',$request->emp_id)->where('status',1)->get();

		if(count($packing_operator) > 0)
		{
			return response()->json(['result' => 1, 'packing_operator' => $packing_operator]);
		}
		else
		{
			return response()->json(['result' => 2]);
		}
	}

	public function check_packing_inspector(Request $request)
	{
		$packing_inspector = User::where('employee_id',$request->emp_id)->where('position',5)->where('status',1)->get();

		if(count($packing_inspector) > 0)
		{
			return response()->json(['result' => 1, 'packing_inspector' => $packing_inspector]);
		}
		else
		{
			return response()->json(['result' => 2]);
		}
	}

	public function submit_final_packing_inspection(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

			$oqc_inspector = User::with(['oqc_stamps'])->where('employee_id', $request->txt_search_final_inspector_id)->whereIn('position',[1,5])->get();

			if(count($oqc_inspector) > 0)
			{
				DB::beginTransaction();

				try
				{
					PackingInspectionTS::where('device_code',$request->final_packing_inspection_device_code)->update([

						'final_packing_operator_id' => $request->final_packing_operator_id,
						'final_oqc_inspector_id' => $request->final_packing_inspector_id,
						'final_packing_inspection_datetime' => $request->final_inspection_datetime,
						'coc_attachment' => $request->final_coc_attachment,
						'final_result' => $request->final_result,
						'final_remarks' => $request->final_remarks,
						'updated_at' => date('Y-m-d H:i:s'), 
						'status' => 3
					]);

					DB::commit();

					return response()->json(['result' => 1]);
				}
				catch(\Exception $e)
				{
		            DB::rollback();
		            return response()->json(['result' => $e]);
		        }
			}
			else
			{
				return response()->json(['result' => 2]);
			}		
	}
}
