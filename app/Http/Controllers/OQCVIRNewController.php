<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//models
use App\Model\OqcLotappNew;
use App\Model\OQCVIRNew;
use App\Model\OqcLotappRuncards;


use App\User;

use DataTables;
use Carbon\Carbon;

class OQCVIRNewController extends Controller
{
    public function load_new_oqcvir_table(Request $request)
    {
        $distinct = OqcLotappNew::orderBy('submission','desc')->get();
        $distinct = $distinct->unique('oqc_lotapp_id')->pluck('id');

    	$lotapps = OqcLotappNew::with(['applied_by_details','created_by_details','oqclotapp_runcard_details'  => function($query){
            
                $query->where('logdel',0);

            },'oqclotapp_runcard_details.runcard_details',
            'oqclotapp_runcard_details.runcard_details.prod_runcard_station_many_details' => function($query){

             $query->where('status',1)->orderBy('step_num','desc');

            },
            'oqclotapp_runcard_details.rework_details',
            'oqclotapp_runcard_details.rework_details.defect_escalation_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');
            }

            ,'oqcvir_details','oqcvir_details.inspector_details'])->where('po_num',$request->po_num)->whereIn('status',[2,3,4,5,6,7])->where('logdel',0)->whereIn('id',$distinct)->orderBy('created_at','desc')->get();

    	return DataTables::of($lotapps)
    	->addColumn('action',function($lotapp){

    		$result = "";

    		if(count($lotapp->oqcvir_details) == 0)
    		{
    			$result .= ' <button type="button" class="btn btn-sm btn-success btn-start-inspection" data-toggle="modal" data-target="#modalStartInspection" oqc-lotapp-id='.$lotapp->id.' title="Initialize Inspection Time"><i class="fa fa-microscope"></i></button>';
    		}
    		else
    		{
    			switch($lotapp->oqcvir_details[0]->result)
    			{
    				case 1:
    				{	
    					$result = "";

    					break;
    				}
    				case 2:
    				{
    					$result = "";

    					break;
    				}
    				default:
    				{
    					$result .= ' <button type="button" class="btn btn-sm btn-primary btn-oqc-vir" data-toggle="modal" data-target="#modalOQCVIR" oqc-lotapp-id='.$lotapp->id.' title="Start Inspection"><i class="fa fa-check-circle"></i></button>';

    					break;
    				}
    			}

    		}

    		/*$result .= ' <button type="button" class="btn btn-sm btn-info btn-start-inspection" oqc-lotapp-id='.$lotapp->oqc_lotapp_id.' data-toggle="modal" data-target="modalStartInspection" title="View Inspection Record"><i class="fa fa-list"></i></button>';*/

            $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-info view-lot-application" title="View OQC Lot Application" data-toggle="modal" data-target="#modalViewApplication" oqclotapp-id='.$lotapp->id.'><i class="fa fa-eye fa-sm"></i></button>';

    		return $result;
    	})
    	->addColumn('status',function($lotapp){
    		
    		if(count($lotapp->oqcvir_details) == 0)
    		{
    			$result = '<span class="badge badge-pill badge-info">For Inspection</span>';
    		}
    		else
    		{
    			switch($lotapp->oqcvir_details[0]->result)
    			{
    				case 1:
    				{	
    					$result = '<span class="badge badge-pill badge-success">OK Inspection Result</span>';

    					break;
    				}
    				case 2:
    				{
    					$result = '<span class="badge badge-pill badge-danger">NG Inspection Result</span>';

    					break;
    				}
    				default:
    				{
    					$result = '<span class="badge badge-pill badge-primary">On-Going Inspection</span>';

    					break;
    				}
    			}
    		}

    		return $result;

    	})
    	->addColumn('lot_application',function($lotapp){
    		
    		$result = $lotapp->oqc_lotapp_id;

    		return $result;

    	})	
    	->addColumn('lot_batch_no',function($lotapp){
    		
    		$result = '';

    		for($i = 0; $i < count($lotapp->oqclotapp_runcard_details); $i++)
    		{
                switch($lotapp->oqclotapp_runcard_details[$i]->item_type)
                {
                    case 1:
                    {
                        $result .= '<span class="badge badge-pill badge-info"  title="Runcard">'.$lotapp->oqclotapp_runcard_details[$i]->runcard_details->runcard_no .'</span>&nbsp;';

                        break;
                    }
                    case 2:
                    {
                        $result .= '<span class="badge badge-pill badge-primary" title="Rework/DE">'.$lotapp->oqclotapp_runcard_details[$i]->rework_details->defect_escalation_no .'</span>&nbsp;';

                        break;
                    }
                    default:
                    {
                        $result = 'Error Loading Runcards!';
                        break;
                    }
                }    			
    		}

    		return $result;

    	})
    	->addColumn('output_qty',function($lotapp){
    		
    		$result = 0;

    		for($i = 0; $i < count($lotapp->oqclotapp_runcard_details); $i++)
    		{
                switch($lotapp->oqclotapp_runcard_details[$i]->item_type)
                {
                    case 1:
                    {
                       $result += $lotapp->oqclotapp_runcard_details[$i]->runcard_details->prod_runcard_station_many_details[0]->qty_output;
                        break;
                    }
                    case 2:
                    {
                        $result += $lotapp->oqclotapp_runcard_details[$i]->rework_details->defect_escalation_station_many_details[0]->qty_good;

                        break;
                    }
                    default:
                    {
                        $result = 0;
                        break;
                    }
                }               
    		}

    		return $result;

    	})
    	->addColumn('inspected_by',function($lotapp){
    		
    		if(count($lotapp->oqcvir_details) == 0)
    		{
    			$result = "---";
    		}
    		else
    		{
    			$result = $lotapp->oqcvir_details[0]->inspector_details->name;
    		}

    		return $result;

    	})
    	->rawColumns(['action','status','lot_batch_no'])
    	->make(true);
    }

    public function load_new_lotapp_details(Request $request)
    {   
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

            ,'oqcvir_details' => function($query)
            {
                $query->where('logdel',0)->orderBy('created_at','desc');

            },'oqcvir_details.inspector_details'])->where('id',$request->oqc_lotapp_id)->where('logdel',0)->get();


    	/*$lotapp_details = OqcLotappNew::with(['applied_by_details','created_by_details','oqclotapp_runcard_details','oqclotapp_runcard_details.runcard_details','oqclotapp_runcard_details.runcard_details.prod_runcard_station_many_details' => function($query){

             $query->where('status',1)->orderBy('step_num','desc');

            },'oqcvir_details','oqcvir_details.inspector_details'])->where('oqc_lotapp_id',$request->oqc_lotapp_id)->where('logdel',0)->get();*/

    	if(count($lotapp_details) > 0)
    	{
    		return response()->json(['result' => 1, 'lotapp_details' => $lotapp_details]);
    	}
    	else
    	{
    		return response()->json(['result' => 2]);
    	}
    }

    public function submit_start_inspection(Request $request)
    {
    	 date_default_timezone_set('Asia/Manila');
         $data = $request->all();
         DB::beginTransaction();

         $user_details = User::where('employee_id',$request->employee_number_scanner_start)->where('status',1)->get();

         if(count($user_details) > 0)
         {
         	if($user_details[0]->position == 5 || $user_details[0]->position == 0 || $user_details[0]->position == 1 || $user_details[0]->position == 2)
         	{
         		OQCVIRNew::insert([

         			'po_num' => $request->name_start_po,
         			'oqc_lotapp_id' => $request->name_start_hidden_id,
         			'status' => 0,
         			'empid' => $user_details[0]->id,
         			'created_at' => date('Y-m-d H:i:s'),
         			'updated_at' => date('Y-m-d H:i:s'),
         			'logdel' => 0
 
         		]);


         		DB::commit();

         		return response()->json(['result' => 1]);
         	}
         	else
         	{
         		return response()->json(['result' => 3]);
         	}
         }
         else
         {
         	return response()->json(['result' => 2]);
         }
    }

    public function search_inspector(Request $request)
    {
    	$inspector_details = User::where('employee_id',$request->employee_id)->where('status',1)->get();

    	if(count($inspector_details) > 0)
    	{
    		if($inspector_details[0]->position == 5 || $inspector_details[0]->position == 0 || $inspector_details[0]->position == 1 || $inspector_details[0]->position == 2)
         	{
         		return response()->json(['result' => 1, 'inspector_details' => $inspector_details]);
         	}
         	else
         	{
         		return response()->json(['result' => 3]);
         	}
    	}
    	else
    	{
    		return response()->json(['result' => 2]);
    	}
    }

    public function submit_oqc_vir(Request $request)
    {
    	date_default_timezone_set('Asia/Manila');
         $data = $request->all();
         DB::beginTransaction();

           $user_details = User::where('employee_id',$request->employee_number_scanner)->where('status',1)->get();
           $oqclotapp_status = 0;

            if($request->name_result == "1")
            {
                $oqclotapp_status = 5;
            }
            else
            {
                $oqclotapp_status = 3;
            }


         if(count($user_details) > 0)
         {
         	if($user_details[0]->position == 5 || $user_details[0]->position == 0 || $user_details[0]->position == 1 || $user_details[0]->position == 2)
         	{
         		OQCVIRNew::where('oqc_lotapp_id',$request->name_lotapp_hidden_id)->update([

         			'oqc_sample' => $request->name_oqc_sample_size,
         			'ok_qty' => $request->name_ok_qty, 
         			'ng_qty' => $request->name_ng_qty, 
         			'insp_etime' => $request->name_end_datetime,
         			'use_template' => $request->name_terminal,
         			'yd_requirement' => $request->name_yd_label,
         			'csh_coating' => $request->name_csh_coating,
         			'acc_req' => $request->name_accessory_requirement,
         			'coc_req' => $request->name_coc_requirement,
         			'insp_name' => $request->name_oqc_inspector_id,
         			'result' => $request->name_result, 
         			'judgement' => $request->name_result,
         			'remarks' => $request->name_remarks,  
         			'updated_at' => date('Y-m-d H:i:s'),
         			'logdel' => 0 

         			]);

         		OqcLotappNew::where('id',$request->name_lotapp_hidden_id)->update([

         			'status' => $oqclotapp_status,
         			'updated_at' => date('Y-m-d H:i:s'),

         		]);


         		DB::commit();

         		return response()->json(['result' => 1]);
         	}
         	else
         	{
         		return response()->json(['result' => 3]);
         	}
         }
         else
         {
         	return response()->json(['result' => 2]);
         }
    }

    public function load_single_oqcvir_table(Request $request)
    {
        $oqcvirs = OQCVIRNew::with(['inspector2_details'])->where('oqc_lotapp_id', $request->application_id)->where('logdel',0)->orderBy('created_at','desc')->get();

        return DataTables::of($oqcvirs)
        ->addColumn('insp_stime', function($oqcvir){

            $result = $oqcvir->created_at;

            return $result;
        })
        ->addColumn('sample_size', function($oqcvir){

            $result = $oqcvir->oqc_sample;

            return $result;
        })
        ->addColumn('ok_ng', function($oqcvir){

            $result = $oqcvir->ok_qty . " / " . $oqcvir->ng_qty;

            return $result;
        })
        ->addColumn('use_template', function($oqcvir){

           switch($oqcvir->use_template)
           {
            case 1:
            {
                $result = '<span class="badge badge-pill badge-success">YES</span>';

                break;
            }
            case 2:
            {
                $result = '<span class="badge badge-pill badge-warning">NO</span>';

                break;
            }
            case 3:
            {
                $result = '<span class="badge badge-pill badge-info">N/A</span>';

                break;
            }

           }

            return $result;
        })
        ->addColumn('yd_requirement', function($oqcvir){

           switch($oqcvir->yd_requirement)
           {
            case 1:
            {
                $result = '<span class="badge badge-pill badge-success">WITH</span>';

                break;
            }
            case 2:
            {
                $result = '<span class="badge badge-pill badge-warning">WITHOUT</span>';

                break;
            }
            case 3:
            {
                $result = '<span class="badge badge-pill badge-info">N/A</span>';

                break;
            }

           }

            return $result;
        })
        ->addColumn('csh_coating', function($oqcvir){

           switch($oqcvir->csh_coating)
           {
            case 1:
            {
                $result = '<span class="badge badge-pill badge-success">YES</span>';

                break;
            }
            case 2:
            {
                $result = '<span class="badge badge-pill badge-warning">NO</span>';

                break;
            }
            case 3:
            {
                $result = '<span class="badge badge-pill badge-info">N/A</span>';

                break;
            }

           }

            return $result;
        })
        ->addColumn('acc_req', function($oqcvir){

           switch($oqcvir->acc_req)
           {
            case 1:
            {
                $result = '<span class="badge badge-pill badge-success">YES</span>';

                break;
            }
            case 2:
            {
                $result = '<span class="badge badge-pill badge-warning">NO</span>';

                break;
            }
            case 3:
            {
                $result = '<span class="badge badge-pill badge-info">N/A</span>';

                break;
            }

           }

            return $result;
        })
        ->addColumn('coc_req', function($oqcvir){

           switch($oqcvir->coc_req)
           {
            case 1:
            {
                $result = '<span class="badge badge-pill badge-success">YES</span>';

                break;
            }
            case 2:
            {
                $result = '<span class="badge badge-pill badge-warning">NO</span>';

                break;
            }
            case 3:
            {
                $result = '<span class="badge badge-pill badge-info">N/A</span>';

                break;
            }

           }

            return $result;
        })
        ->addColumn('insp_name', function($oqcvir){

            $result = '<span class="badge badge-pill badge-info">'.$oqcvir->inspector2_details->name.'</span>';

            return $result;
        })
        ->addColumn('remarks', function($oqcvir){

            $result = $oqcvir->remarks;

            return $result;
        })
         ->addColumn('judgement', function($oqcvir){

           switch($oqcvir->judgement)
           {
            case 1:
            {
                $result = '<span class="badge badge-pill badge-success">ACCEPTED</span>';

                break;
            }
            case 2:
            {
                $result = '<span class="badge badge-pill badge-warning">REJECTED</span>';

                break;
            }
            case 3:
            {
                $result = '<span class="badge badge-pill badge-info">---</span>';

                break;
            }

           }

            return $result;
        })
        ->addColumn('insp_etime', function($oqcvir){

            $result = $oqcvir->insp_etime;

            return $result;
        })
        ->rawColumns(['use_template','yd_requirement','csh_coating','acc_req','coc_req','judgement','insp_name'])
        ->make(true);
    }
}
