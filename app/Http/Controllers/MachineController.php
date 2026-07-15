<?php

namespace App\Http\Controllers;

// Models
use App\Model\Machine;
use App\Model\EEDMSGeneralLogistics;
use App\Model\DeviceProcessFlowMaterial;
use App\Model\Device;
use App\Model\ProcessFlow;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVMachineImport;
use QrCode;

class MachineController extends Controller
{
    //View Machines
	public function view_machines(){
        // Old 07-08-2022
    	// $machines = Machine::all();

        // $machines = EEDMSGeneralLogistics::where('g_logdel', 0)->get();
        // or this
        // $machines = DB::connection('mysql_rapid_eedms_v2')->select('select * from generallogistics where g_logdel = 0 and machine_status = "Available"');
        // or this
        $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
            ->where('g_logdel', 0)
            ->where('machine_section', 'TS')
            ->get();

        return DataTables::of($machines)
            ->addColumn('machine_status', function($machine){
                $result = "";

                if($machine->machine_status == "Available"){
                    $result .= '<span class="badge badge-pill badge-warning">Available</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-success">Deployed</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($machine){
                $disabled = "";
                $result = '';
                $result .= '<center>';
                $result .=   '<div class="btn-group">';

                if($machine->machine_status == "Available" || $machine->machine_status == "Not Available"){
                    $disabled = "";
                    $result .=  '<button type="button" '.$disabled.' class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
                    $result .=      '<i class="fa fa-cog"></i>';
                    $result .=  '</button>';
                    $result .=  '<div class="dropdown-menu dropdown-menu-right">';
                    // $result .=      '<button class="dropdown-item aEditMachine" type="button" machine-id="' . $machine->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditMachine" data-keyboard="false">Edit</button>';
                    // $result .=      '<button class="dropdown-item aChangeMachineStat" type="button" machine-id="' . $machine->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMachineStat" data-keyboard="false">Deactivate</button>';
                    $result .=      '<button class="dropdown-item aGenerateBarcode" machine-id="' . $machine->id . '" barcode="' . $machine->machine_code_number . '" title="Machine: " name="' . $machine->machine_name . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }else{
                    $disabled = "disabled";
                    $result .=  '<button type="button" '.$disabled.' class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">';
                    $result .=      '<i class="fa fa-cog"></i>';
                    $result .=  '</button>';
                    $result .=  '<div class="dropdown-menu dropdown-menu-right">';
                    $result .=      '<button class="dropdown-item aChangeMachineStat" type="button" style="padding: 1px 1px; text-align: center;" machine-id="' . $machine->id . '" status="1" data-toggle="modal" data-target="#modalChangeMachineStat" data-keyboard="false">Activate</button>';
                }
                $result .=      '</div>';
                $result .=  '</div>';
                $result .='</center>';

                return $result;
            })
            ->addColumn('checkbox', function($machine){
                return '<center><input type="checkbox" class="chkMachine" machine-id="' . $machine->id . '"></center>';
            })
            ->rawColumns(['machine_status', 'action1', 'checkbox'])
            ->make(true);
    }

    // Get Machine By Batch
    public function get_machine_by_batch(Request $request){
        $machines;

        if($request->machine_id == 0){
            $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
            ->where('g_logdel', 0)
            // ->where('machine_status', 'Available')
            ->get();
        }
        else{
            // $machines = Machine::whereIn('id', $request->machine_id)->get();
            $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
            ->where('g_logdel', 0)
            // ->where('machine_status', 'Available')
            ->whereIn('id', $request->machine_id)
            ->get();
        }
        $qrcode = [];

        if($machines->count() > 0){
            for($index = 0; $index < $machines->count(); $index++){
                $qrcode[] = "data:image/png;base64," . base64_encode(QrCode::format('png')
                                    ->size(200)->errorCorrection('H')
                                    ->generate($machines[$index]->machine_code_number));
            }
        }

        return response()->json(['machines' => $machines, 'qrcode' => $qrcode]);
    }

    // Add Machine
    public function add_machine(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:machines'],
            'barcode' => ['required', 'string', 'max:255', 'unique:machines'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Machine::insert([
                    'name' => $request->name,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }
        }
    }

    // Get Machine By Id
    public function get_machine_by_id(Request $request){
        $machine = Machine::where('id', $request->machine_id)->get();
        return response()->json(['machine' => $machine]);
    }

    // Get Machines
    /** Orig Code
     * 09-23-2022
     */
    // public function get_machines(Request $request){
    //     $machines_all = Machine::all();
    //     // $arr_machine_code = ['DIESET', 'JIGS'];
    //     // $arr_machine_code = ['MACHINE','OTHERS', 'JIGS'];
    //     $arr_machine_code = ['MACHINE','DIESET'];

    //      $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
    //         ->where('g_logdel', 0)
    //         ->where('machine_section', 'TS')
    //         ->whereIn('machine_code', $arr_machine_code)
    //         // ->orderBy('id', 'desc')
    //         // ->limit(5000)
    //         ->get();

    //     return response()->json(['machines' => $machines]);
    // }

    public function get_machines(Request $request){
        // $machines_all = Machine::all();
        // $arr_machine_code = ['DIESET', 'JIGS'];
        // $arr_machine_code = ['MACHINE','OTHERS', 'JIGS'];

        // $machines = \DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')->select('*')
        //     ->whereRaw("machine_section='TS' AND machine_code IN ('MACHINE','DIESET', 'JIGS')")
        //     ->get();

        $machines = \DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')->select('*')
            // ->whereRaw("g_logdel = 0 AND machine_section='TS' AND machine_code IN ('JIGS')")
            // ->whereRaw("g_logdel = 0 AND machine_section='TS' AND machine_code IN ('MACHINE','DIESET','JIGS')")
            ->whereRaw("g_logdel = 0 AND machine_section='TS' AND machine_code IN ('MACHINE','JIGS','OTHERS')")
            // ->whereRaw("g_logdel = 0 AND machine_section='TS' AND machine_code IN ('MACHINE')")
            ->get('id','machine_code_number','machine_name');
            // return $machines;
            // return $machines_array;
        return response()->json(['machines' => $machines]);
        // return response()->json(['machines_array' => $machines_array]);
    }

    /**
     * New code 09-24-202
     * Collect Process Flow Material Id based on Device Id
     * to match in EEDMS Material generallogistics(table)
     */
    public function get_machines_for_runcard_station(Request $request){
        $process_flow_array = [];
        return 'TODO get_machines_for_runcard_station';

        $device = CommonController::getInstance()->getMatrixByPoNo([
            'po_no'=> $po_number,
            'name'=>  $request->txt_device_name_lbl,
            'barcode'=> '',
            'get_or_first'=> '',
        ]);
        // $device = Device::where("name", $request->txt_device_name_lbl)->get();
        // return $device;
        if( count($device) > 0 ){
            $processFlowDetails = ProcessFlow::with(['processFlow'])->where("device_id", $device[0]->id)
            ->where("status", 1)
            ->get();
            for ($i=0; $i < count($processFlowDetails); $i++) {
                if($processFlowDetails[$i]->machine_id != 'N/A'){
                    if (str_contains($processFlowDetails[$i]->machine_id, ',')) {
                        $exploded_array = explode(',',$processFlowDetails[$i]->machine_id);
                        for ($j=0; $j < count($exploded_array); $j++) {
                            $process_flow_array[] = $exploded_array[$j];
                        }
                    }else{
                        $process_flow_array[] = $processFlowDetails[$i]->machine_id;
                    }

                }
            }
            // return $process_flow_array;

            $machine_array = [];
            for ($i=0; $i < count($process_flow_array) ; $i++) {
                $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
                ->where('id', $process_flow_array[$i])
                ->get()->flatten(1);
                $machine_array[] = $machines;
            }

            // ->unique('process_flow_id')->flatten(1);
            return response()->json([ 'machines' => array_flatten($machine_array), 'processFlowDetails' => $processFlowDetails, 'process_flow_array' => $process_flow_array]);

        }
    }

    // public function get_machines_test(Request $request){
    //     // $machines = \DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')->select('id', 'machine_code_number','machine_name')
    //     //     ->whereRaw("g_logdel = 0 AND machine_section='TS' AND machine_code IN ('MACHINE','DIESET')")
    //     //     ->get('id','machine_code_number','machine_name');
    //     // return response()->json(['machines' => $results]);

    //     $arr_machine_code = ['MACHINE','DIESET'];
    //     $results = DB::connection('mysql_rapid_eedms_v2')->select( DB::raw("SELECT id, machine_code_number, machine_name FROM generallogistics WHERE machine_code IN ('MACHINE','DIESET')"));

    //     return response()->json(['machines' => $results]);
    //     // return response()->json(['machines_array' => $machines_array]);
    // }

    public function get_machines_test(Request $request){
        $process_flow_material_ids_array = [];
        return 'TODO get_machines_test';
         $device = CommonController::getInstance()->getMatrixByPoNo([
            'po_no'=> $po_number,
            'name'=>  $request->txt_device_name_lbl,
            'barcode'=> '',
            'get_or_first'=> '',
        ]);
        // $device = Device::where("name", $request->txt_device_name_lbl)->get();
        // return $device;
        if( count($device) > 0 ){
            $processFlowDetails = ProcessFlow::with(['processFlow'])->where("device_id", $device[0]->id)
            ->where("status", 1)
            ->get();
            for ($i=0; $i < count($processFlowDetails); $i++) {
                if($processFlowDetails[$i]->machine_id != 'N/A'){
                    if (str_contains($processFlowDetails[$i]->machine_id, ',')) {
                        $exploded_array = explode(',',$processFlowDetails[$i]->machine_id);
                        for ($j=0; $j < count($exploded_array); $j++) {
                            $process_flow_material_ids_array[] = $exploded_array[$j];
                        }
                    }else{
                        $process_flow_material_ids_array[] = $processFlowDetails[$i]->machine_id;
                    }

                }
            }

            $machine_array = [];
            for ($i=0; $i < count($process_flow_material_ids_array) ; $i++) {
                $machines = DB::connection('mysql_rapid_eedms_v2')->table('generallogistics')
                ->where('id', $process_flow_material_ids_array[$i])
                ->get()->flatten(1);
                $machine_array[] = $machines;
            }

            // ->unique('process_flow_id')->flatten(1);
            return response()->json([ 'machines' => array_flatten($machine_array), 'processFlowDetails' => $processFlowDetails, 'process_flow_material_ids_array' => $process_flow_material_ids_array]);

        }
    }

    // Edit Machine
    public function edit_machine(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:machines,name,'. $request->machine_id,
            'barcode' => 'required|string|max:255|unique:machines,barcode,'. $request->machine_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Machine::where('id', $request->machine_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    'barcode' => $request->barcode,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }
        }
    }

    // Change Machine Status
    public function change_machine_stat(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'machine_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Machine::where('id', $request->machine_id)
                    ->increment('update_version', 1,
                        [
                            'status' => $request->status,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                DB::commit();
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }

            return response()->json(['result' => 1]);
        }
        else{
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    public function import_machine(Request $request)
    {

        $collections = Excel::toCollection(new CSVMachineImport, request()->file('import_file'));

        DB::beginTransaction();
        try{
            for($index = 2; $index < count($collections[0]); $index++){
                Machine::insert([
                    'name' => $collections[0][$index][0],
                    'barcode' => $collections[0][$index][1],
                    'status' => 1,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::commit();

            return response()->json(['result' => "1"]);
        }
        catch(\Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }
    }
}
