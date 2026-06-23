<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//models
use App\Model\OqcLotappNew;
use App\Model\OQCVIRNew;

use App\Model\PackingInspectionNew;
use App\Model\PackingInspectionLotapps;

use App\Model\PackingCode;

use App\User;

use DataTables;
use Carbon\Carbon;

class PackingInspectionNewController extends Controller
{	
	public function load_new_packing_confirmation_lots(Request $request)
	{
		if(isset($request->array_lots))
		{
			$array_lots = $request->array_lots;
		}
		else
		{
			$array_lots = [];
		}

		return DataTables::of($array_lots)
		->addColumn('action',function($lot){

			$result = '<button type="button" class="btn btn-sm btn-danger btn-remove-packing-confirmation" lotapp-id='.$lot['lotapp_id'].' title="Remove from List"><i class="fa fa-times"></i></button>';

			return $result;

		})
		->addColumn('lotapp_id',function($lot){
				
			$result = $lot['lotapp_no'];

			return $result;

		})
		->addColumn('lot_qty',function($lot){
				
			$result = $lot['lot_qty'];

			return $result;
		})
		->rawColumns(['action'])
		->make(true);
	}

	public function load_new_packinginspection_table(Request $request)
	{
		$packing_lots = PackingInspectionNew::with(['packing_lotapps_details','packing_lotapps_details.oqc_lotapp_details',
			'packing_lotapps_details.oqc_lotapp_details',
			'packing_lotapps_details.oqc_lotapp_details.oqclotapp_runcard_details',
			'packing_lotapps_details.oqc_lotapp_details.oqclotapp_runcard_details.runcard_details',
			'packing_lotapps_details.oqc_lotapp_details.oqclotapp_runcard_details.runcard_details.prod_runcard_station_many_details' => function($query){

             $query->where('status',1)->orderBy('step_num','desc');

            },
            'packing_lotapps_details.oqc_lotapp_details.oqclotapp_runcard_details.rework_details',
            'packing_lotapps_details.oqc_lotapp_details.oqclotapp_runcard_details.rework_details.defect_escalation_station_many_details' => function($query){

             $query->where('status',1)->orderBy('step_num','desc');

            }

	])->where('po_num',$request->po_num)->where('logdel',0)->get();

		return DataTables::of($packing_lots)
		->addColumn('action',function($packing_lot){

			switch($packing_lot->status)
			{
				case 1:
				{	
					$result = '<button type="button" class="btn btn-sm btn-info btn-preliminary-inspection" data-toggle="modal" data-target="#modalPreliminaryInspection" title="Add Preliminary Inspection" packing-id="'.$packing_lot->id.'"><i class="fa fa-microscope"></i></button>';

					break;
				}
				case 2:
				{	
					$result = '<button type="button" class="btn btn-sm btn-primary btn-final-inspection"  data-toggle="modal" data-target="#modalFinalInspection" title="Add Final Inspection" packing-id="'.$packing_lot->id.'"><i class="fa fa-edit"></i></button>';

					break;
				}
				case 3:
				{	
					$result = "";

					break;
				}
				case 4:
				{	
					$result = "";

					break;
				}
				default:
				{
					$result = "";

					break;
				}
			}

			return $result;
		})
		->addColumn('device_code',function($packing_lot){
				
			$result = $packing_lot->packing_code;

			return $result;

		})
		->addColumn('total_lot_qty',function($packing_lot){
			
			$result = 0;


			for($i = 0; $i < count($packing_lot->packing_lotapps_details); $i++)
			{
				for($x = 0; $x < count($packing_lot->packing_lotapps_details[$i]->oqc_lotapp_details); $x++)
				{
					for($y = 0; $y < count($packing_lot->packing_lotapps_details[$i]->oqc_lotapp_details[$x]->oqclotapp_runcard_details); $y++)
					{
						switch($packing_lot->packing_lotapps_details[$i]->oqc_lotapp_details[$x]->oqclotapp_runcard_details[$y]->item_type)
						{
							case 1:
							{
								$result += $packing_lot->packing_lotapps_details[$i]->oqc_lotapp_details[$x]->oqclotapp_runcard_details[$y]->runcard_details->prod_runcard_station_many_details[0]->qty_output;

								break;
							}
							case 2:
							{
								$result += $packing_lot->packing_lotapps_details[$i]->oqc_lotapp_details[$x]->oqclotapp_runcard_details[$y]->rework_details->defect_escalation_station_many_details[0]->qty_good;

								break;
							}
							default:
							{
								$result = 0;

								break;
							}

						}
					}
				}
			}

			/*for($i = 0; $i < count($packing_lot->packing_lotapps_details->oqc_lotapp_details->oqc_lotapp_runcard_details); $i++)
	          {
	              switch($packing_lot->packing_lotapps_details->oqc_lotapp_details->oqc_lotapp_runcard_details[$i]->item_type)
	              {
	                  case 1:
	                  {
	                     $result += $packing_lot->packing_lotapps_details->oqc_lotapp_details->oqc_lotapp_runcard_details[$i]->runcard_details->prod_runcard_station_many_details[0]->qty_output;
	                      break;
	                  }
	                  case 2:
	                  {	
	                  	$result += $packing_lot->packing_lotapps_details->oqc_lotapp_details->oqc_lotapp_runcard_details[$i]->rework_details->rework_station_many_details[0]->result_qty_ok;
	                      break;
	                  }
	                  default:
	                  {
	                      $result = 0;
	                      break;
	                  }
	              }               
	          }*/

			/*for($i = 0; $i < count($packing_lot->packing_lotapps_details); $i++)
			{
				for($x = 0; $x < count($packing_lot->packing_lotapps_details[$i]->vir_lotapp_details->lotapp_details->oqclotapp_runcard_details); $x++)
				{
					$result += $packing_lot->packing_lotapps_details[$i]->vir_lotapp_details->lotapp_details->oqclotapp_runcard_details[$x]->runcard_details->prod_runcard_station_many_details[0]->qty_output;
				}
			}*/

			return $result;

		})
		->addColumn('lotapps',function($packing_lot){
			
			$result = "";
/*
			for($i = 0; $i < count($packing_lot->packing_lotapps_details); $i++)
			{
				$result .= '<span class="badge badge-pill badge-primary">' . $packing_lot->packing_lotapps_details[$i]->vir_lotapp_details->oqc_lotapp_id . '</span>&nbsp;';
			}*/

			for($i = 0; $i < count($packing_lot->packing_lotapps_details); $i++)
			{
				for($x = 0; $x < count($packing_lot->packing_lotapps_details[$i]->oqc_lotapp_details); $x++)
				{
					$result .= '<span class="badge badge-pill badge-primary">' . $packing_lot->packing_lotapps_details[$i]->oqc_lotapp_details[$x]->oqc_lotapp_id . '</span>&nbsp;';
				}
			}

			return $result;
		})
		->addColumn('status',function($packing_lot){
			
			switch($packing_lot->status)
			{
				case 1:
				{	
					$result = '<span class="badge badge-pill badge-info">For Preliminary Inspection</span>';

					break;
				}
				case 2:
				{	
					$result = '<span class="badge badge-pill badge-primary">For Final QC Inspection</span>';

					break;
				}
				case 3:
				{	
					$result = '<span class="badge badge-pill badge-success">Inspection OK Result</span>';

					break;
				}
				case 4:
				{	
					$result = '<span class="badge badge-pill badge-danger">Inspection NG Result</span>';

					break;
				}
				default:
				{
					$result = "---";

					break;
				}
			}

			return $result;

		})
		->rawColumns(['action','lotapps','status'])
		->make(true);
	}

	public function load_packing_inspection_details(Request $request)
	{
		$packing_details = PackingInspectionNew::where('id',$request->packing_id)->where('logdel',0)->get();

		if(count($packing_details) > 0)
		{
			return response()->json(['result' => 1, 'packing_details' => $packing_details]);
		}
		else
		{
			return response()->json(['result' => 2]);
		}
	}

	public function submit_prelim_inspection2(Request $request)
	{
		date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        $prelim_inspector = User::where('employee_id',$request->txt_search_prelim_inspector)->whereIn('position',[0,1,2,5])->where('status',1)->get();

        DB::beginTransaction();

        if(count($prelim_inspector) > 0)
        {
        	 try
	        {
	        	PackingInspectionNew::where('id',$request->packing_code_id)->update([

	        		'packing_type' => $request->packing_type,
	        		'unit_condition' => $request->unit_condition,
	        		'packing_inspection_datetime' => $request->packing_inspection_datetime,
	        		'prelim_inspector_id' => $prelim_inspector[0]->id,
	        		'updated_at' => date('Y-m-d H:i:s'),
	        		'status' => 2

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

    public function search_confirmation_lotapp(Request $request)
    {
    	$distinct = OqcLotappNew::orderBy('submission','desc')->get();
        $distinct = $distinct->unique('oqc_lotapp_id')->pluck('id');

    	$lotapp_details = OqcLotappNew::with(['applied_by_details','created_by_details','oqclotapp_runcard_details'  => function($query){
            
                $query->where('logdel',0);

            },'oqclotapp_runcard_details.runcard_details',
            'oqclotapp_runcard_details.runcard_details.prod_runcard_station_many_details' => function($query){

             $query->where('status',1)->orderBy('step_num','desc');

            },
            'oqclotapp_runcard_details.rework_details',
            'oqclotapp_runcard_details.rework_details.defect_escalation_station_many_details' => function($query){

           	$query->where('status',1)->orderBy('step_num','desc');

            }

            ,'oqcvir_details','oqcvir_details.inspector_details'])->where('po_num',$request->po_num)->where('oqc_lotapp_id',$request->lot_app)->where('logdel',0)->whereIn('id',$distinct)->orderBy('created_at','desc')->where('status',5)->get();

    	if(count($lotapp_details) > 0)
    	{
    		if($lotapp_details[0]->oqcvir_details[0]->judgement == 2)
    		{
    			return response()->json(['result' => 2]);
    		}
    		else
    		{
    			return response()->json(['result' => 1, 'lotapp_details' => $lotapp_details]);
    		}
    	}
    	else
    	{
    		return response()->json(['result' => 3]);
    	}
    }

    public function submit_confirmation_lots(Request $request)
    {
    	date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        //number to start autogeneration
        $autogenNum = 1;

        $series_name = explode("-",$request->add_packing_confirmation_device_name)[0];

        $series_id = PackingCode::where('series_name',$series_name)->first();

        if($series_id != null)
        {
        	$device = str_pad($series_id->series_id,3,"0",STR_PAD_LEFT);
        }
        else
        {
        	$device = "***";
        }

        $month = date('m');

        $packing_lots = PackingInspectionNew::whereMonth('created_at',$month)->distinct('packing_code')->count('packing_code');

        if($packing_lots != null)
        {
        	$autogenNum = $packing_lots + 1;
        }
        else
        {
        	$autogenNum = 1;
        }

        //------------------DEVICE CODE GENERATION-----------------------
        $packing_code = $device . $month . "-" . str_pad($autogenNum, 3, "0", STR_PAD_LEFT);

        //--------LOTS TO BE SAVED IN PACKING INSPECTION NEW--------------
        $array_lots = json_decode($request->array_lots, TRUE);

        //-----------------TOTAL LOT QUANTITY----------------------------
        $total_lot_qty = 0;

        for($i = 0; $i < count($array_lots); $i++)
        {
        	$total_lot_qty += $array_lots[$i]['lot_qty'];
        }

        //---------------------USER ID-----------------------------------
        $packop_confirmation = User::where('employee_id',$request->txt_search_packing_operator)->where('status',1)->get();

        if(count($packop_confirmation) > 0)
        {
        	DB::beginTransaction();

	        try
	        {
	        	PackingInspectionNew::insert([

	        		'po_num' => $request->add_packing_confirmation_po_num,
	        		'packing_code' => $packing_code,
	        		'total_lot_qty' => $total_lot_qty,
	        		'anti_rust_inclusion' => $request->anti_rust_inclusion,
	        		'packop_confirmation' => $packop_confirmation[0]->id,
	        		'created_at' => date('Y-m-d H:i:s'),
	        		'updated_at' => date('Y-m-d H:i:s'),
	        		'status' => 1,
	        		'logdel' => 0

	        	]);

	        	if(isset($array_lots))
	        	{
	        		for($x = 0; $x < count($array_lots); $x++)
	        		{	
	        			PackingInspectionLotapps::insert([

		        			'packing_code' => $packing_code,
		        			'oqclotapp_id' => $array_lots[$x]['lotapp_id'],
		        			'inserted_by' => $packop_confirmation[0]->id,
		        			'created_at' => date('Y-m-d H:i:s'),
			        		'updated_at' => date('Y-m-d H:i:s'),
			        		'logdel' => 0

	        			]);
	        		}
	        	}

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

    public function load_packing_operator_details(Request $request)
    {
    	$packop_details = User::where('employee_id',$request->operator_id)->where('status', 1)->get();

    	if(count($packop_details) > 0)
    	{
    		return response()->json(['result' => 1, 'packop_details' => $packop_details]);
    	}
    	else
    	{
    		return response()->json(['result' => 2]);
    	}
    }

    public function submit_final_packing_inspection2(Request $request)
    {
    	date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        $final_inspector = User::where('employee_id',$request->txt_search_final_inspection)->whereIn('position',[0,1,2,5])->where('status',1)->get();

        $status = 0;

        if($request->final_result == "1")
        {
        	$status = 3;
        }
        else
        {
        	$status = 4;
        }

        DB::beginTransaction();

        if(count($final_inspector) > 0)
        {
        	 try
	        {
	        	PackingInspectionNew::where('id',$request->final_packing_code_id)->update([

	        		'final_packop_conformance' => $request->packop_conformance_id,
	        		'final_packop_datetime' => $request->final_inspection_datetime,
	        		'final_packop_inspector_id' => $final_inspector[0]->id,
	        		'coc_attachment' => $request->coc_attachment,
	        		'result' => $request->final_result,
	        		'status' => $status,
	        		'updated_at' => date('Y-m-d H:i:s')

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
