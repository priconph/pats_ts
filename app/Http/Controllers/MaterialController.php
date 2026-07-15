<?php

namespace App\Http\Controllers;

use App\Model\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVMaterialImport;
use QrCode;

use App\Model\WBSKitIssuance;
use App\Model\WBSSakidashiIssuance;
use App\Model\PartsPrep;
use App\Model\Device;
use App\Model\ProcessFlow;
use App\User;
use App\Model\DeviceProcessFlowMaterial;

class MaterialController extends Controller
{

    public function getMaterialListByProcess(Request $request)
    {
        $device = DeviceProcessFlowMaterial::where("process_flow_id", $request->process_flow_id)->get();
        // ->unique('process_flow_id')->flatten(1);
        $materialList = [];
        // if( count($device) > 0 ) {
        //     $process_flows = $device[0]->process_flow;
            for ($i=0; $i < count($device); $i++) {
                $materialList[] = $device[$i]->material_name;
            }
        // }

        return response()->json([
            "device" => $device,
            "materialList" => $materialList,
        ]);
    }

    /* Orig Code */
    public function getMaterialListByDeviceName(Request $request)
    {

        return 'TODO getMaterialListByDeviceName';
         $device = CommonController::getInstance()->getMatrixByPoNo([
            'po_no'=> $po_number,
            'name'=>  $request->deviceName,
            'barcode'=> '',
            'get_or_first'=> '',
        ]);
        // $device = Device::with('process_flow')->where("name", $request->deviceName)->get();
        $materialList = [];
        $process_ids = [];
        // if( count($device) > 0 ) {
        //     $process_flows = $device[0]->process_flow;
        //     for ($i=0; $i < count($process_flows); $i++) {
        //         $materialList[] = $process_flows[$i]->material_name;
        //     }
        // }
        if( count($device) > 0 ) {
            $process_flows = $device[0]->process_flow;
            for ($i=0; $i < count($process_flows); $i++) {
                $process_ids[] = $process_flows[$i]->id;
            }
        }
        $device_material_list = DeviceProcessFlowMaterial::whereIn("process_flow_id", $process_ids)->get();
        for ($i=0; $i < count($device_material_list); $i++) {
            $materialList[] = $device_material_list[$i]->material_name;
        }

        return response()->json([
            "materialList" => $materialList,
            "device" => $device,
        ]);
    }


    public function getMaterialsFromRecieving(Request $request)
    {
        $deviceName = $request->deviceName;

        $arrayListSakidashi = [];
        $arrayListMatterial = [];
        $materials = [];
         $subkitting_collect_obj = DB::select('
            select sum( case when (c.status = 1) then b.sub_kit_qty else 0 end ) as sub_kit_qty, a.kit_issuance_id
            from kittings a

            inner join sub_kittings b
            on a.id = b.pats_kitting_id

            inner join parts_preps_sub_kitting c
            on b.id = c.sub_kittings_id

            where a.status = 1 and b.status = 1
            group by a.kit_issuance_id
            ');

        // return $subkitting_collect_obj;

        $lot_ids = "";
        $subkitting_collect = array();
        foreach ($subkitting_collect_obj as $key => $value) {
            $subkitting_collect[$value->kit_issuance_id] = $value->sub_kit_qty;
            $lot_ids .= $value->kit_issuance_id . ",";
        }
        $lot_ids = substr($lot_ids, 0, -1);

        // Don't remove 08-08-2022
        $collectIssuanceNoByDeviceName = DB::connection('mysql_subsystem')->table('tbl_wbs_material_kitting')
            ->where('device_name', $deviceName)
            ->get()
            ->flatten(1);

        $issuance_numbers = [];
        for ($i=0; $i < count($collectIssuanceNoByDeviceName); $i++) {
            array_push($issuance_numbers, $collectIssuanceNoByDeviceName[$i]->issuance_no);
        }
        // Recommended query
        // return $issuance_numbers;
        $lot_numbers = DB::connection('mysql_subsystem')->table('tbl_wbs_kit_issuance')
            ->whereIn('issue_no', $issuance_numbers)
            // ->get();
            // ->get()->unique('item_desc')->flatten(1);
            ->get()->unique('item')->flatten(1); // added by nesa 01-26-2023

        for ($i=0; $i < count($lot_numbers); $i++) {
            $lot_number = $lot_numbers[$i];
            $status = 0;

            $parts_preps = PartsPrep::where([
                [ 'wbs_kit_issuance_id', '=', $lot_number->id ],
                [ 'wbs_table', '=', 1 ],
                [ 'deleted_at', '=', null ],
            ])
            ->get();
                $materialHoilder = array(
                    "isKitting" => 1,
                    "po" => $lot_number->po,
                    "id" => $lot_number->id,
                    "materialCode" => $lot_number->item,
                    "material" => $lot_number->item_desc,
                    "lotNo" => $lot_number->lot_no,
                    // "wbs_kit_issuance" => 'true',
                );
                $materials[] = $materialHoilder;
                $arrayListMatterial[] = $lot_number;
        }

        $tbl_wbs_sakidashi_issuances = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')->distinct()
            ->where('device_name', $deviceName)
            ->get();


        $temp_item_description_array = [];
        for ($i=0; $i < count($tbl_wbs_sakidashi_issuances); $i++) {
            $tbl_wbs_sakidashi_issuance = $tbl_wbs_sakidashi_issuances[$i];
            $status = 0;

            $parts_preps = PartsPrep::where([
                [ 'wbs_kit_issuance_id', '=', $tbl_wbs_sakidashi_issuance->id ],
                [ 'wbs_table', '=', 2 ],
                [ 'deleted_at', '=', null ],
            ])
            ->get();



            if( count($parts_preps) > 0 ){
                if( $parts_preps[0]->status == 1 )
                    $status = 1;
            }

            if( $status == 1 ){
                if( isset($tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item) ){
                    $materialHoilder = $tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item;

                    // if(!in_array($materialHoilder->item_desc, $temp_item_description_array)){
                    if(!in_array($materialHoilder->item, $temp_item_description_array)){ // 09302023 item code
                        // array_push($temp_item_description_array, $materialHoilder->item_desc);
                        array_push($temp_item_description_array, $materialHoilder->item); // 09302023 item code
                        $materialHoilder = array(
                            "isKitting" => 0,
                            "id" => $materialHoilder->id,
                            "materialCode" => $materialHoilder->item,
                            "material" => $materialHoilder->item_desc,
                            "lotNo" => $materialHoilder->lot_no,
                        );
                        $materials[] = $materialHoilder;
                        $arrayListSakidashi[] = $tbl_wbs_sakidashi_issuance;
                    }
                }
            }
        }

        return response()->json([
            'count' => count($materials),
            'materials' => $materials,
        ]);
    }

	public function get_material_by_stat(Request $request){
        $materials = Material::where('status', $request->status)->get();
        return response()->json(['materials' => $materials]);
    }

    //View Materials
	public function view_materials(){
    	$materials = Material::all();

        return DataTables::of($materials)
            ->addColumn('label1', function($material){
                $result = "";

                if($material->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($material){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($material->status == 1){
                	$result .= '<button class="dropdown-item aEditMaterial" type="button" material-id="' . $material->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditMaterial" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeMaterialStat" type="button" material-id="' . $material->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMaterialStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenMaterialBarcode" material-id="' . $material->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    // $result .= '<button class="dropdown-item aGenerateBarcode" material-id="' . $material->id . '" barcode="' . $material->barcode . '" title="Material: " name="' . $material->name . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeMaterialStat" type="button" style="padding: 1px 1px; text-align: center;" material-id="' . $material->id . '" status="1" data-toggle="modal" data-target="#modalChangeMaterialStat" data-keyboard="false">Activate</button>';
                }

                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('checkbox', function($material){
                return '<center><input type="checkbox" class="chkMaterial" material-id="' . $material->id . '"></center>';
            })
            ->rawColumns(['label1', 'action1', 'checkbox'])
            ->make(true);
    }

    // Get Material By Batch
    public function get_material_by_batch(Request $request){
        $materials;

        if($request->material_id == 0){
            $materials = Material::all();
        }
        else{
            $materials = Material::whereIn('id', $request->material_id)->get();
        }
        $qrcode = [];

        // if($materials->count() > 0){
        //     for($index = 0; $index < $materials->count(); $index++){
        //         $qrcode[] = "data:image/png;base64," . base64_encode(QrCode::format('png')
        //                             ->size(200)->errorCorrection('H')
        //                             ->generate($materials[$index]->barcode));
        //     }
        // }

        return response()->json(['materials' => $materials]);
    }

    // Add Material
    public function add_material(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:materials'],
            // 'barcode' => ['required', 'string', 'max:255', 'unique:materials'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Material::insert([
                    'name' => $request->name,
                    'status' => 1,
                    // 'barcode' => $request->barcode,
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

    // Get Material By Id
    public function get_material_by_id(Request $request){
        $material = Material::where('id', $request->material_id)->get();
        return response()->json(['material' => $material]);
    }

    // Get Materials
    public function get_materials(Request $request){
        $materials = Material::all();
        return response()->json(['materials' => $materials]);
    }

    public function get_materials_by_id(Request $request){
        $materials = WBSKitIssuance::all();
        return response()->json(['materials' => $materials]);
    }

    // Edit Material
    public function edit_material(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:materials,name,'. $request->material_id,
            // 'barcode' => 'required|string|max:255|unique:materials,barcode,'. $request->material_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Material::where('id', $request->material_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    // 'barcode' => $request->barcode,
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

    // Change Material Status
    public function change_material_stat(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'material_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Material::where('id', $request->material_id)
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

    public function import_material(Request $request)
    {

        $collections = Excel::toCollection(new CSVMaterialImport, request()->file('import_file'));

        DB::beginTransaction();
        try{
            for($index = 0; $index < count($collections[0]); $index++){
                Material::insert([
                    'name' => $collections[0][$index][0],
                    // 'barcode' => $collections[0][$index][1],
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

    public function save_process_flow_material(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        if($request->ajax()){
            if(Auth::check()){
                if(isset($request->process_flow_id)){
                    $data = $request->all();
                    $rules = [
                        'process_flow_id' => 'required',
                    ];

                    // $user_info = User::where('employee_id', $_SESSION['employee_id']) // Old 03-15-2024
                    $user_info = User::where('employee_id', Auth::user()->employee_id)
                                ->where('status', 1)
                                ->whereIn('position', [1, 12]) // 1-Prodn, 12-Admin
                                ->get();
                    // return $user_info[0];

                    if(count($user_info) > 0){
                        $validator = Validator::make($data, $rules);
                        try {
                            if($validator->passes()){


                                // return $request->process_flow_id;
                                ProcessFlow::where('id', $request->process_flow_id)
                                    ->where('status', 1)
                                    ->update([
                                        'material_approval_status' => 1, // 1-Prod Supervisor, 2-Conformed by Engineer, 3-Approved by QC, 4-Disapproved by Engineer, 5-Disapproved by QC
                                        'material_approval_date' => date('Y-m-d H:i:s'),
                                        'material_approved_by' => Auth::user()->employee_id,
                                    ]);
                                // return $processFlowDetails;

                                /**
                                 * Insertion of Material in Process Flow(Edit Material)
                                 * 01-24-2023
                                 */
                                DeviceProcessFlowMaterial::where('process_flow_id', $request->process_flow_id)
                                    ->delete();
                                if(isset($request->materialArray)){
                                    for($index = 0; $index < count($request->materialArray); $index++){
                                        DeviceProcessFlowMaterial::insert([
                                            'process_flow_id' => $request->process_flow_id,
                                            'device_id' => $request->device_id,
                                            'sub_station_id' => $request->sub_station_id,
                                            'material_id' => $request->materialArray[$index][0],
                                            'lot_no' => $request->materialArray[$index][1],
                                            'material_part_no' => $request->materialArray[$index][2],
                                            'material_name' => $request->materialArray[$index][3],
                                            'material_type' => $request->materialArray[$index][4],
                                            'status' => 1,
                                            'created_by' => Auth::user()->id,
                                            'last_updated_by' => Auth::user()->id,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }

                                // if(count($processFlowDetails) > 0){
                                //     ProcessFlow::where('id', $request->process_flow_id)
                                //         ->where('status', 1)
                                //         ->update([
                                //             'material_id' => $material_id,
                                //             'last_updated_by' => Auth::user()->id,
                                //             'updated_at' => date('Y-m-d H:i:s'),
                                //         ]
                                //     );

                                //     // for($index = 0; $index < count($request->materialArray); $index++){
                                //     // ProcessFlow::where('id', $request->process_flow_id)
                                //     //     ->where('status', 1)
                                //     //     ->update([
                                //     //         'material_id' => $request->materialArray[$index][0],
                                //     //         'lot_no' => $request->materialArray[$index][1],
                                //     //         'material_no' => $request->materialArray[$index][2],
                                //     //         'material_name' => $request->materialArray[$index][3],
                                //     //         'material_type' => $request->materialArray[$index][4],
                                //     //         'last_updated_by' => Auth::user()->id,
                                //     //         'updated_at' => date('Y-m-d H:i:s'),
                                //     //     ]);
                                //     // }

                                // }else{
                                //     return response()->json(['auth' => 1, 'result' => 0, 'count'=>'0']);
                                // }

                                return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
                            }
                            else{
                                return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
                            }
                        }
                        catch(\Exception $e) {
                            // echo "test";
                            // echo "\r\n";
                            echo $e;
                            return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
                        }
                    }else{
                        return response()->json(['auth' => 1, 'result' => 0, 'error' => 'notProdn']);
                    }
                }
            }
            else{
                return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
            }
        }
        else{
            abort(403);
        }
    }
}
