<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\ProductionRuncardStation;
use App\Model\Device;
use App\Model\ProcessFlow;

class ProductionRuncardStationController extends Controller
{
    public function getNextStepNumber(Request $request)
    {
    	$prodRuncardStation = ProductionRuncardStation::where("production_runcard_id", $request->runcardId)
    		->where("status", 1)
    		// ->orderBy("id", "desc") // commented on 05-16-2023 - JD
    		->orderBy("process_flow_id", "desc") // added on 05-16-2023 added -JD
    		->limit(1)
    		->get();
            // return $prodRuncardStation;
        $message = "No ProcessFlow Set.";
        $device = Device::where("name", $request->deviceName)->get();
        if( count($device) > 0 ) {
            $processFlows = ProcessFlow::with("processFlow")
            	->where("device_id", $device[0]->id)
            	->where("status", 1)
            	->orderBy("step")
            	->get();
            // return response()->json(['processFlows' => $processFlows]);

            if( count($processFlows) == 0 )
            	return response()->json(['id' => 0, 'value' => "No ProcessFlow Set."]);

            if( count($prodRuncardStation) == 0 )
            	return response()->json(['id' => $processFlows[0]->id, 'value' => $processFlows[0]->step]);

            for ($i=0; $i < count($processFlows); $i++) { 
                $message = "All Process already set.";
            	if( $i <  count($processFlows)-1){
            		if( $processFlows[$i]->id == $prodRuncardStation[0]->process_flow_id ) {
                        // insert condition for #CV Assembly
                        $prodRuncardStationList = ProductionRuncardStation::where("production_runcard_id", $request->runcardId)
                            ->where("status", 1)
                            ->limit(1)
                            ->get();
                        if( $processFlows[$i+1]->process == 13 && $prodRuncardStationList[0]->qty_input == 0 )
                            return response()->json(['id' => 0, 'value' => "Next step is #CV Assembly, but step 1 is not set. Please set it first"]);
                        // end
            			return response()->json(['id' => $processFlows[$i+1]->id, 'value' => $processFlows[$i+1]->step]);
                    }
            	}
            }
        }
        return response()->json(['id' => 0, 'value' => $message]);
    }
}
