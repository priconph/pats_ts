<?php

namespace App\Http\Controllers;

use App\Model\Device;
use App\Model\ProcessFlow;
use App\Model\DeviceProcessFlowMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVPackingMatrixImport;

class DeviceController extends Controller
{
    public function getProcessFlowByDeviceName(Request $request)
    {
        $device = Device::where("name", $request->deviceName)->get();
        // return $device;
        if( count($device) > 0 ){
            $processFlowDetails = ProcessFlow::with(['processFlow'])->where("device_id", $device[0]->id)
            ->where("status", 1)
            ->get();
            // ->unique('process_flow_id')->flatten(1);
            return response()->json(['data' => $processFlowDetails, 'device' =>$device]);
        }

        // return response()->json(['data' => []]);
    }

    // Copy
    // public function getProcessFlowByDeviceName(Request $request)
    // {
    //     $device = Device::where("name", $request->deviceName)->get();
    //     if( count($device) > 0 )
    //         return response()->json(['data' => ProcessFlow::with("processFlow")->where("device_id", $device[0]->id)->where("status", 1)->get(), 'device' =>$device]);
    //     return response()->json(['data' => []]);
    // }

    public function checkProcessFlowIsSet(Request $request)
    {
        $device = Device::where("name", $request->deviceName)->get();
        if( count($device) > 0 ){
            $processFlow = ProcessFlow::where("device_id", $device[0]->id)->where("status", 1)->get();
            if( count($processFlow) > 0 )
                return response()->json(['result' => "yes"]);
        }
        return response()->json(['result' => "no"]);
    }

    public function getAllDevices()
    {
        return response()->json(['data' => Device::where('status', 1)->get()]);
    }

    /**
     * Old Code
     * 01-13-2023
     * -JD
     */
	// public function view_devices(){
    // 	$devices = Device::all();

    //     return DataTables::of($devices)
    //         ->addColumn('label1', function($device){
    //             $result = "";

    //             if($device->status == 1){
    //                 $result .= '<span class="badge badge-pill badge-success">Active</span>';
    //             }
    //             else{
    //                 $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('action1', function($device){
    //             $result = '<center><div class="btn-group">
    //                       <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
    //                         <i class="fa fa-cog"></i>
    //                       </button>
    //                       <div class="dropdown-menu dropdown-menu-right">';
    //             if($device->status == 1){
    //             	$result .= '<button class="dropdown-item aEditDevice" type="button" device-id="' . $device->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditDevice" data-keyboard="false">Edit</button>';

    //                 $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" device-id="' . $device->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Deactivate</button>';

    //                 // $result .= '<button class="dropdown-item aShowDeviceDevProc" device-id="' . $device->id . '" device-name="' . $device->name . '" type="button" style="padding: 1px 1px; text-align: center;">Show Device Process</button>';

    //                 // $result .= '<button class="dropdown-item aGenDeviceBarcode" device-id="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

    //                 // Removed temporarily - undefined value from barcode
    //                 // $result .= '<button class="dropdown-item aGenerateBarcode" device-id="' . $device->id . '" barcode="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
    //             }
    //             else{
    //                 $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" style="padding: 1px 1px; text-align: center;" device-id="' . $device->id . '" status="1" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Activate</button>';
    //             }

    //             $result .= '<input type="hidden" value="' . $device->id . '" class="form-control td_device_id">';
    //             $result .= '<input type="hidden" value="' . $device->name . '" class="form-control td_device_name">';

    //             $result .= '</div>
    //                     </div></center>';

    //             return $result;
    //         })
    //         ->addColumn('process_lbl', function($device){
    //             $result = "";

    //             if($device->process == 0){
    //                 $result .= '<span class="badge badge-pill badge-danger">Without</span>';
    //             }
    //             else{
    //                 $result .= '<span class="badge badge-pill badge-primary">With</span>';
    //             }

    //             return $result;
    //         })
    //         ->addColumn('type_lbl', function($device){
    //             $result = "";

    //             if($device->type == 1){
    //                 $result .= '<span class="badge badge-pill badge-info">Burn-in</span>';
    //             }
    //             else if($device->type == 2){
    //                 $result .= '<span class="badge badge-pill badge-primary">MICRON PATS</span>';
    //             }
    //             else if($device->type == 3){
    //                 $result .= '<span class="badge badge-pill badge-info">Test Sockets</span>';
    //             }else{
    //                 $result .= '--';
    //             }

    //             return $result;
    //         })

    //         ->rawColumns(['label1', 'action1','process_lbl','type_lbl'])
    //         ->make(true);
    // }

    public function view_devices(){
    	$devices = Device::with('process_flow')->get();

        return DataTables::of($devices)
            ->addColumn('label1', function($device){
                $result = "";

                if($device->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($device){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($device->status == 1){
                	$result .= '<button class="dropdown-item aEditDevice" type="button" device-id="' . $device->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditDevice" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" device-id="' . $device->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aShowDeviceDevProc" device-id="' . $device->id . '" device-name="' . $device->name . '" type="button" style="padding: 1px 1px; text-align: center;">Show Device Process</button>';

                    // $result .= '<button class="dropdown-item aGenDeviceBarcode" device-id="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    // Removed temporarily - undefined value from barcode
                    // $result .= '<button class="dropdown-item aGenerateBarcode" device-id="' . $device->id . '" barcode="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" style="padding: 1px 1px; text-align: center;" device-id="' . $device->id . '" status="1" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Activate</button>';
                }

                $result .= '<input type="hidden" value="' . $device->id . '" class="form-control td_device_id">';
                $result .= '<input type="hidden" value="' . $device->name . '" class="form-control td_device_name">';

                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('process_lbl', function($device){
                $result = "";

                if($device->process == 0){
                    $result .= '<span class="badge badge-pill badge-danger">Without</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-primary">With</span>';
                }

                return $result;
            })
            ->addColumn('type_lbl', function($device){
                $result = "";

                if($device->type == 1){
                    $result .= '<span class="badge badge-pill badge-info">Burn-in</span>';
                }
                else if($device->type == 2){
                    $result .= '<span class="badge badge-pill badge-primary">MICRON PATS</span>';
                }
                else if($device->type == 3){
                    $result .= '<span class="badge badge-pill badge-info">Test Sockets</span>';
                }else{
                    $result .= '--';
                }

                return $result;
            })
            ->addColumn('total_approved_status', function($device){
                $result = "";
                $totalProcessFlowPendingStatus = 0;
                if(count($device->process_flow) > 0){
                    for ($i=0; $i < count($device->process_flow); $i++) {
                        if($device->process_flow[$i]->status == 1 && $device->process_flow[$i]->approval_status == 1 || $device->process_flow[$i]->approval_status == 2){
                            $totalProcessFlowPendingStatus++;
                        }
                    }
                    if($totalProcessFlowPendingStatus > 0){
                        $result .= '<center><span class="badge badge-pill badge-danger" title="Total number of pending approval">'.$totalProcessFlowPendingStatus.' - Pending</span></center>';
                    }else{
                        $result .= '<center><span class="badge badge-pill badge-success">Approved</span></center>';
                    }

                }else{
                    $result .= '<center><span class="badge badge-pill badge-warning">No Process Flow</span></center>';
                }


                return $result;
            })

            ->rawColumns(['label1', 'action1','process_lbl','type_lbl', 'total_approved_status'])
            ->make(true);
    }

    public function view_devices_test(){ // Change for live -JD
    	$devices = Device::with('process_flow')->get();

        return DataTables::of($devices)
            ->addColumn('label1', function($device){
                $result = "";

                if($device->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($device){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($device->status == 1){
                	$result .= '<button class="dropdown-item aEditDevice" type="button" device-id="' . $device->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditDevice" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" device-id="' . $device->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aShowDeviceDevProc" device-id="' . $device->id . '" device-name="' . $device->name . '" type="button" style="padding: 1px 1px; text-align: center;">Show Device Process</button>';

                    // $result .= '<button class="dropdown-item aGenDeviceBarcode" device-id="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    // Removed temporarily - undefined value from barcode
                    // $result .= '<button class="dropdown-item aGenerateBarcode" device-id="' . $device->id . '" barcode="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" style="padding: 1px 1px; text-align: center;" device-id="' . $device->id . '" status="1" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Activate</button>';
                }

                $result .= '<input type="hidden" value="' . $device->id . '" class="form-control td_device_id">';
                $result .= '<input type="hidden" value="' . $device->name . '" class="form-control td_device_name">';

                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('process_lbl', function($device){
                $result = "";

                if($device->process == 0){
                    $result .= '<span class="badge badge-pill badge-danger">Without</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-primary">With</span>';
                }

                return $result;
            })
            ->addColumn('type_lbl', function($device){
                $result = "";

                if($device->type == 1){
                    $result .= '<span class="badge badge-pill badge-info">Burn-in</span>';
                }
                else if($device->type == 2){
                    $result .= '<span class="badge badge-pill badge-primary">MICRON PATS</span>';
                }
                else if($device->type == 3){
                    $result .= '<span class="badge badge-pill badge-info">Test Sockets</span>';
                }else{
                    $result .= '--';
                }

                return $result;
            })
            ->addColumn('total_approved_status', function($device){
                $result = "";
                $totalProcessFlowPendingStatus = 0;
                $totalProcessFlowApprovedStatus = 0;
                $totalProcessFlowActiveStatus = 0;
                // return count($device->process_flow);
                if(count($device->process_flow) > 0){
                    for ($i=0; $i < count($device->process_flow); $i++) {
                        if($device->process_flow[$i]->status == 1 && ($device->process_flow[$i]->approval_status == 1 || $device->process_flow[$i]->approval_status == 2 || $device->process_flow[$i]->approval_status == null)){
                            $totalProcessFlowPendingStatus++;
                        }

                        if($device->process_flow[$i]->status == 1 && $device->process_flow[$i]->approval_status == 3){
                            $totalProcessFlowApprovedStatus++;
                        }

                        if($device->process_flow[$i]->status == 1){
                            $totalProcessFlowActiveStatus++;
                        }
                    }
                    // return ' total approved: '. $totalProcessFlowApprovedStatus .' count: '. $totalProcessFlowActiveStatus;
                    if($totalProcessFlowPendingStatus > 0){
                        $result .= '<center><span class="badge badge-pill badge-danger" title="Total number of pending approval">'.$totalProcessFlowPendingStatus.' - Pending/s</span></center>';
                    }else if($totalProcessFlowApprovedStatus == $totalProcessFlowActiveStatus){
                        $result .= '<center><span class="badge badge-pill badge-success">Approved</span></center>';
                    }
                }else{
                    $result .= '<center><span class="badge badge-pill badge-warning">No Process Flow</span></center>';
                }


                return $result;
            })

            ->rawColumns(['label1', 'action1','process_lbl','type_lbl', 'total_approved_status'])
            ->make(true);
    }

    // Add Device
    public function add_device(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            // 'name' => ['required', 'string', 'max:255', 'unique:devices'],
            'name' => ['required', 'string', 'max:255'],
            'barcode' => ['required', 'string', 'max:255', 'unique:devices'],
            'process' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            // 'mrp_no' => ['required', 'string', 'max:255'],
            'boxing' => ['numeric', 'required'],
            'ship_boxing' => ['numeric', 'required'],
            'pin_popu' => ['numeric', 'required'],
        ]);
        // return response()->json(['data' => $request->copy_material_flow]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                $id = Device::insertGetId([
                    'name' => $request->name,
                    'process' => $request->process,
                    // 'huawei_p_n' => $request->huawei_p_n,
                    // 'lot_no_machine_code' => $request->lot_no_machine_code,
                    // 'mrp_no' => $request->mrp_no,
                    'boxing' => $request->boxing,
                    'ship_boxing' => $request->ship_boxing,
                    'pin_popu' => $request->pin_popu, // 03012023
                    'type' => $request->type,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // 2022 06 24

                if( $request->copy_material_flow != 0 ){

                    $processFlowToCopy = ProcessFlow::where("device_id", $request->copy_material_flow)->get();

                    for ($i=0; $i < count($processFlowToCopy); $i++) {
                        ProcessFlow::insert([
                            'device_id' => $id,
                            'step' => $processFlowToCopy[$i]->step,
                            'process' => $processFlowToCopy[$i]->process,
                            'material_id' => $processFlowToCopy[$i]->material_id,
                            'material_kitting_item' => $processFlowToCopy[$i]->material_kitting_item,
                            'sakidashi_item' => $processFlowToCopy[$i]->sakidashi_item,
                            'machine_id' => $processFlowToCopy[$i]->machine_id,
                            'a_shift_user_id' => "",
                            'b_shift_user_id' => "",
                            'status' => $processFlowToCopy[$i]->status,
                            'update_version' => 1,
                            'created_by' => Auth::user()->id,
                            'last_updated_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),

                            'lot_no' => $processFlowToCopy[$i]->lot_no,
                            'material_name' => $processFlowToCopy[$i]->material_name,
                            'material_type' => $processFlowToCopy[$i]->material_type,
                        ]);
                    }
                }

                // 2022 06 24

                DB::commit();

                return response()->json(['result' => "1", 'created_by' => Auth::user()->id]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0", "error" => $e]);
            }
        }
    }

    // Get Device By Id
    public function get_device_by_id(Request $request){
        $device = Device::where('id', $request->device_id)->get();
        return response()->json(['device' => $device]);
    }

    // Edit Device
    public function edit_device(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            // 'name' => 'required|string|max:255|unique:devices,name,'. $request->device_id,
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|max:255|unique:devices,barcode,'. $request->device_id,
            // 'mrp_no' => ['required', 'string', 'max:255'],
            'boxing' => ['numeric', 'required'],
            'ship_boxing' => ['numeric', 'required'],
            'pin_popu' => ['numeric', 'required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();
            try{
                Device::where('id', $request->device_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    'process' => $request->process,
                    'type' => $request->type,
                    // 'huawei_p_n' => $request->huawei_p_n,
                    // 'lot_no_machine_code' => $request->lot_no_machine_code,
                    'barcode' => $request->barcode,
                    // 'mrp_no' => $request->mrp_no,
                    'boxing' => $request->boxing,
                    'ship_boxing' => $request->ship_boxing,
                    'pin_popu' => $request->pin_popu,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);


                // 2022 06 24

                if( $request->copy_material_flow != 0 ){

                    ProcessFlow::where("device_id", $request->device_id)->delete();

                    $processFlowToCopy = ProcessFlow::where("device_id", $request->copy_material_flow)->get();

                    for ($i=0; $i < count($processFlowToCopy); $i++) {
                        ProcessFlow::insert([
                            'device_id' => $request->device_id,
                            'step' => $processFlowToCopy[$i]->step,
                            'process' => $processFlowToCopy[$i]->process,
                            'material_id' => $processFlowToCopy[$i]->material_id,
                            'material_kitting_item' => $processFlowToCopy[$i]->material_kitting_item,
                            'sakidashi_item' => $processFlowToCopy[$i]->sakidashi_item,
                            'machine_id' => $processFlowToCopy[$i]->machine_id,
                            'a_shift_user_id' => "",
                            'b_shift_user_id' => "",
                            'status' => $processFlowToCopy[$i]->status,
                            'update_version' => 1,
                            'created_by' => Auth::user()->id,
                            'last_updated_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'lot_no' => $processFlowToCopy[$i]->lot_no,
                            'material_name' => $processFlowToCopy[$i]->material_name,
                            'material_type' => $processFlowToCopy[$i]->material_type,
                        ]);
                    }
                }

                // 2022 06 24

                DB::commit();

                return response()->json(['result' => "1", 'last_updated_by' => Auth::user()->id, 'id' => $request->device_id]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }
        }
    }

    // Change Device Status
    public function change_device_stat(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'device_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Device::where('id', $request->device_id)
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

    public function import_packing_matrix(Request $request)
    {

        $collections = Excel::toCollection(new CSVPackingMatrixImport, request()->file('import_file'));
        DB::beginTransaction();
        try{
            for($index = 0; $index < count($collections[0]); $index++){
                Device::insert([
                    'name' => $collections[0][$index][1],
                    'mrp_no' => $collections[0][$index][2],
                    'boxing' => $collections[0][$index][3],
                    'ship_boxing' => $collections[0][$index][4],
                    'barcode' => $collections[0][$index][0],
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
