<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//models
use App\Model\ProductionRuncard;
use App\Model\oqcLotApp;
use App\Model\MaterialIssuanceSubSystem;

use QrCode;

use DataTables;

class OQCLotApplicationTSController extends Controller
{
	public function load_oqclotapp_table(Request $request)
	{
		$lotapps = ProductionRuncard::with(['oqc_details','oqc_details.user_details_ts'])->where('po_no', $request->po_no)->where('status',3)->distinct('runcard_no')->orderBy('runcard_no','desc')->get();

		//return $lotapps;

		return DataTables::of($lotapps)
		->addColumn('action',function($lotapp){

			$result = '';

			 $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_checkpoints" data-toggle="modal" data-target="#modalAddApplication" title="Add OQC Lot Application" po-num="'.$lotapp->po_no.'" lot-batch-no="'.$lotapp->runcard_no.'"><i class="fa fa-check-square fa-sm"></i></button>';

			 $checker = oqcLotApp::where('lot_batch_no',$lotapp->runcard_no)->get();

			 if(count($checker) > 0)
			{
				 $result .= ' <button type="button" class="btn btn-sm btn-primary btn_print_lot" id="btn_print" data-toggle="modal" value="'.$lotapp->runcard_no.'" title="Print barcode"><i class="fa fa-print fa-sm"></i></button>';
			}

			 

			return $result;

		})
		->addColumn('status',function($lotapp){

			$status = $lotapp->oqc_details;

			if($status != null)
			{
				switch($lotapp->oqc_details->status)
				{
					case 1:
					{
						$result = '<span class="badge badge-pill badge-success">Lot Applied</span>';
						break;
					}
					case 2:
					{
						$result = '<span class="badge badge-pill badge-warning">For Prod Supervisor Approval</span>';
						break;
					}
					case 3:
					{
						$result = '<span class="badge badge-pill badge-warning">For OQC Supervisor Approval</span>';
						break;
					}
					case 4:
					{
						$result = '<span class="badge badge-pill badge-success">Lot Applied</span>';
						break;
					}
					default:
					{
						break;
					}
				}
			}
			else
			{
				$result = 'For Application';
			}

			return $result;
			
		})
		->addColumn('submission',function($lotapp){

			if($lotapp->oqc_details != null)
			{
				$result = $lotapp->oqc_details->submission;

				switch($result)
				{
					case 1:
					{
						$result2 = '<span class="badge badge-pill badge-success">1st Submission</span>';
						break;
					}
					case 2:
					{
						$result2 = '<span class="badge badge-pill badge-success">2nd Submission</span>';
						break;
					}
					case 3:
					{
						$result2 = '<span class="badge badge-pill badge-success">3rd Submission</span>';
						break;
					}
					default:
					{
						$result2 = '<span class="badge badge-pill badge-warning">No Submissions Yet</span>';
						break;
					}
				}
			}
			else
			{
				$result2 = '<span class="badge badge-pill badge-warning">No Submissions Yet</span>';
			}

			return $result2;
			
		})
		->addColumn('lot_batch_no',function($lotapp){

			$result = $lotapp->runcard_no;

			return $result;
			
		})
		->addColumn('output_qty',function($lotapp){

			$result = $lotapp->lot_qty;

			return $result;
			
		})
		->addColumn('lot_applied_by',function($lotapp){

			if($lotapp->oqc_details != null)
			{
				if($lotapp->oqc_details->empid != null)
				{
					$result = $lotapp->oqc_details->user_details_ts->name;
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
		->addColumn('prod_supervisor',function($lotapp){

			if($lotapp->oqc_details != null)
			{
				if($lotapp->oqc_details->prod_supervisor != null)
				{
					$result = $lotapp->oqc_details->prod_supervisor;
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
		->addColumn('oqc_supervisor',function($lotapp){

			if($lotapp->oqc_details != null)
			{
				if($lotapp->oqc_details->oqc_supervisor != null)
				{
					$result = $lotapp->oqc_details->oqc_supervisor;
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
		->rawColumns(['action','status','submission'])
		->make(true);
	}

	public function load_oqclotapp_history_table(Request $request)
	{
		$lotapp_histories = oqcLotApp::where('lot_batch_no',$request->lot_batch_no)->orderBy('submission','asc')->get();

		return DataTables::of($lotapp_histories)
		->addColumn('submission',function($lotapp_history){
			$result = $lotapp_history->submission;

			switch($result)
				{
					case 1:
					{
						$result2 = '<span class="badge badge-pill badge-success">1st Submission</span>';
						break;
					}
					case 2:
					{
						$result2 = '<span class="badge badge-pill badge-success">2nd Submission</span>';
						break;
					}
					case 3:
					{
						$result2 = '<span class="badge badge-pill badge-success">3rd Submission</span>';
						break;
					}
					default:
					{
						$result2 = '<span class="badge badge-pill badge-warning">Table Error. Please refresh or contact ISS local 205</span>';
						break;
					}
				}

			return $result2;
		})
		->addColumn('app_datetime',function($lotapp_history){
			$result = $lotapp_history->created_at;

			return $result;
		})
		->addColumn('lot_applied_by',function($lotapp_history){
			$result = $lotapp_history->empid;

			return $result;
		})
		->addColumn('remarks',function($lotapp_history){
			$result = $lotapp_history->remarks;

			return $result;
		})
		->rawColumns(['submission'])
		->make(true);
	}

	public function view_oqclotapp_ts(Request $request)
	{
		$series = MaterialIssuanceSubSystem::where('po_no', $request->po_num)->get();

		$oqclotapp = ProductionRuncard::where('runcard_no',$request->batch)->get();

		$sub_count = oqcLotApp::where('lot_batch_no', $request->batch)->count();

		if($sub_count < 0)
		{
			$counter = 1;
		}
		else
		{
			$counter = $sub_count + 1;
		}


		return response()->json(['oqclotapp' => $oqclotapp, 'series' => $series, 'sub_count' => $counter]);
	}

	public function add_oqc_lotapplication_ts(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		try
		{
			//insert record
			oqcLotApp::insert([
				'po_num' => $request->name_po_no,
				'lot_batch_no' => $request->name_lotbatch_no,
				'status' => 1,
				'submission' => $request->name_submission,

				'assy_line' => 1,
				'device_cat' => $request->name_device_classification,

				'lot_qty' => $request->name_lot_quantity,
				'remarks' => $request->name_remarks,
				'empid' => $request->employee_number_scanner,
	            'created_at' => $request->name_application_datetime,
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

	    public function get_lot_app_by_id(Request $request){
        // $lot_app_by_id = oqcLotApp::with(['wbs_kitting'])->where('id',$request['id'])->get(); //02072020
        $lot_app_by_id = oqcLotApp::with(['wbs_kitting'])->where('lot_batch_no',$request['id'])->get();

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
                            ->generate($lot_app_by_id[0]->po_num);

            $po_no = "data:image/png;base64," . base64_encode($po_no);
        }

/*        $inspection_standard = [];
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
            }*/

           /* if($inspection_standard->count() > 0){
                $IS_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($inspection_standard[0]->doc_no);
                                // ->generate($inspection_standard[0]->doc_no.'-'.$inspection_standard[0]->rev_no); //02072020

                $IS_drawing = "data:image/png;base64," . base64_encode($IS_drawing);
            }  */

            /*if($rdrawing->count() > 0){
                $R_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($rdrawing[0]->doc_no);
                                // ->generate($rdrawing[0]->doc_no.'-'.$rdrawing[0]->rev_no); //02072020

                $R_drawing = "data:image/png;base64," . base64_encode($R_drawing);
            }*/

           /* if($adrawing->count() > 0){
                $A_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($adrawing[0]->doc_no);
                                // ->generate($rdrawing[0]->doc_no.'-'.$rdrawing[0]->rev_no); //02072020

                $A_drawing = "data:image/png;base64," . base64_encode($A_drawing);
            }*/


        return response()->json(['lot_app_by_id' => $lot_app_by_id, 'lot_app_code' => $lot_app_code, 'po_no' => $po_no]); 
    }
}
