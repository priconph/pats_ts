<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVKittingImport;
use QrCode;

use App\Model\MaterialIssuanceSubSystem;
use App\Model\MaterialIssuanceDetails;
use App\Model\WBSKitIssuance;
use App\Model\Kitting;
use App\Model\SubKitting;


class KittingController extends Controller
{
    //View Kittings
	public function view_kittings(){
    	$kittings = Kitting::all();

        return DataTables::of($kittings)
            ->addColumn('label1', function($kitting){
                $result = "";

                if($kitting->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($kitting){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($kitting->status == 1){
                	$result .= '<button class="dropdown-item aEditKitting" type="button" kitting-id="' . $kitting->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditKitting" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeKittingStat" type="button" kitting-id="' . $kitting->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeKittingStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenKittingBarcode" kitting-id="' . $kitting->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    $result .= '<button class="dropdown-item aGenerateBarcode" kitting-id="' . $kitting->id . '" barcode="' . $kitting->barcode . '" title="Kitting: " name="' . $kitting->name . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeKittingStat" type="button" style="padding: 1px 1px; text-align: center;" kitting-id="' . $kitting->id . '" status="1" data-toggle="modal" data-target="#modalChangeKittingStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('checkbox', function($kitting){
                return '<center><input type="checkbox" class="chkKitting" kitting-id="' . $kitting->id . '"></center>';
            })
            ->rawColumns(['label1', 'action1', 'checkbox'])
            ->make(true);
    }

    // Get Kitting By Batch
    public function get_kitting_by_batch(Request $request){
        $kittings;

        if($request->kitting_id == 0){
            $kittings = Kitting::all();
        }
        else{
            $kittings = Kitting::whereIn('id', $request->kitting_id)->get();
        }
        $qrcode = [];

        if($kittings->count() > 0){
            for($index = 0; $index < $kittings->count(); $index++){
                $qrcode[] = "data:image/png;base64," . base64_encode(QrCode::format('png')
                                    ->size(200)->errorCorrection('H')
                                    ->generate($kittings[$index]->barcode));
            }
        }

        return response()->json(['kittings' => $kittings, 'qrcode' => $qrcode]);
    }

    // Add Kitting
    public function add_kitting(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:kittings'],
            'barcode' => ['required', 'string', 'max:255', 'unique:kittings'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Kitting::insert([
                    'name' => $request->name,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
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

    // Get Kitting By Id
    public function get_kitting_by_id(Request $request){
        $kitting = Kitting::where('id', $request->kitting_id)->get();
        return response()->json(['kitting' => $kitting]);
    }

    // Get Kittings
    public function get_kittings(Request $request){
        $kittings = Kitting::all();
        return response()->json(['kittings' => $kittings]);
    }

    // Edit Kitting
    public function edit_kitting(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:kittings,name,'. $request->kitting_id,
            'barcode' => 'required|string|max:255|unique:kittings,barcode,'. $request->kitting_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Kitting::where('id', $request->kitting_id)
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

    // Change Kitting Status
    public function change_kitting_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'kitting_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Kitting::where('id', $request->kitting_id)
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

    public function get_kitting_info_by_issuance_no(Request $request){
    	date_default_timezone_set('Asia/Manila');

    	$kitting_info = MaterialIssuanceSubSystem::where('issuance_no', $request->issuance_no)->first();

    	return response()->json(['kitting_info' => $kitting_info]);
    }

    public function get_kitting_details_by_issuance_no(Request $request){
    	date_default_timezone_set('Asia/Manila');

    	$kitting_details = MaterialIssuanceDetails::where('issue_no', $request->issuance_no)->get();

    	return DataTables::of($kitting_details)
            // ->addColumn('label1', function($kitting){
            //     $result = "";

            //     if($kitting->status == 1){
            //         $result .= '<span class="badge badge-pill badge-success">Active</span>';
            //     }
            //     else{
            //         $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
            //     }

            //     return $result;
            // })
			->rawColumns([])
            ->make(true);
    }

    public function get_issuance_details_by_issuance_no(Request $request){
        date_default_timezone_set('Asia/Manila');

        $issuance_details = WBSKitIssuance::with(['pats_kitting_info' => function($query){
                            $query->where('status', 1);
                            }])
                            ->where('issue_no', $request->issuance_no)
                            ->get();

        return DataTables::of($issuance_details)
            ->addColumn('raw_action', function($row){
                $result = "<center>";

                if($row->pats_kitting_info == null){
                    $result .= '<button class="aSubKitIssuance btn btn-xs btn-primary" style="padding: 1px 5px;" type="button" kit-issuance-id="' . $row->id . '" issued-qty="' . $row->issued_qty . '" issuance-no="' . $row->issue_no . '"  po-no="' . $row->po . '" item="' . $row->item . '" item-desc="' . $row->item_desc . '" detail-id="' . $row->detailid . '" lot-no="' . $row->lot_no . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-keyboard="false" title="Sub Kit"><i class="fa fa-list"></i></button>';
                }
                else{
                    $result .= '<button class="aViewSubKitIssuance btn btn-xs btn-success" style="padding: 1px 5px;" type="button" pats-kitting-id="' . $row->pats_kitting_info->id . '" pats-kitting-status="' . $row->pats_kitting_info->status . '" kit-issuance-id="' . $row->id . '" issued-qty="' . $row->issued_qty . '" issuance-no="' . $row->issue_no . '"  po-no="' . $row->po . '" item="' . $row->item . '" item-desc="' . $row->item_desc . '" detail-id="' . $row->detailid . '" lot-no="' . $row->lot_no . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-keyboard="false" title="View Sub Kit Details"><i class="fa fa-file-alt"></i></button>';
                }

                $result .= "</center>";
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
    }

    public function view_sub_kitting_by_kitting_id(Request $request){
        date_default_timezone_set('Asia/Manila');

        $sub_kittings = SubKitting::where('pats_kitting_id', $request->pats_kitting_id)
                            ->get();

        return DataTables::of($sub_kittings)
            ->addColumn('raw_action', function($row){
                $result = "<center>";

                $result .= '<button class="aPrintSubKit btn btn-xs btn-primary" style="padding: 1px 5px;" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-keyboard="false" sub-kit-desc="' . $row->sub_kit_desc . '" sub-kit-id="' . $row->id . '"  title="Print"><i class="fa fa-qrcode"></i></button>';

                $result .= "</center>";
                return $result;
            })
            ->addColumn('raw_qrcode', function($row){
                $result = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate(explode(' | ', $row->sub_kit_desc)[4] . ' | ' . explode(' | ', $row->sub_kit_desc)[5] . ' | ' . explode(' | ', $row->sub_kit_desc)[9]);

                $result = "data:image/png;base64," . base64_encode($result);
                return $result;
            })
            ->rawColumns(['raw_action', 'raw_qrcode'])
            ->make(true);
    }

    public function save_sub_kitting(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();
        // return $data;

        $validator = Validator::make($data, [
            'kit_issuance_id' => ['required'],
            'po_no' => ['required'],
            'issuance_no' => ['required'],
            'item' => ['required'],
            'item_desc' => ['required'],
            'sub_kit_qty' => ['required'],
            'sub_kit_qtys' => ['required'],
            'descriptions' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                $kitting_id = Kitting::insertGetId([
                    'kit_issuance_id' => $request->kit_issuance_id,
                    'po_no' => $request->po_no,
                    'issue_no' => $request->issuance_no,
                    'item' => $request->item,
                    'item_desc' => $request->item_desc,
                    'sub_kit_qty' => $request->sub_kit_qty,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                ]);

                for($index = 0; $index < count($request->sub_kit_qtys); $index++){
                    SubKitting::insert([
                        'pats_kitting_id' => $kitting_id,
                        'sub_kit_desc' => $request->descriptions[$index],
                        'sub_kit_qty' => $request->sub_kit_qtys[$index],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0", "exception" => $e]);
            }
        }
    }

    public function edit_sub_kitting(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();
        // return $data;

        $validator = Validator::make($data, [
            // 'ekit_issuance_id' => ['required'],
            // 'epo_no' => ['required'],
            // 'eissuance_no' => ['required'],
            // 'eitem' => ['required'],
            // 'eitem_desc' => ['required'],
            // 'esub_kit_qty' => ['required'],
            'esub_kit_qtys' => ['required'],
            'edescriptions' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{

                Kitting::where('id', $request->e_id)
                ->increment('status', 1,
                [
                    'status' => 2,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                SubKitting::where('pats_kitting_id', $request->e_id)
                ->increment('status', 1,
                [
                    'status' => 2,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $ekitting_id = Kitting::insertGetId([
                    'kit_issuance_id' => $request->ekit_issuance_id,
                    'po_no' => $request->epo_no,
                    'issue_no' => $request->eissuance_no,
                    'item' => $request->eitem,
                    'item_desc' => $request->eitem_desc,
                    'sub_kit_qty' => $request->esub_kit_qty,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                ]);

                for($index = 0; $index < count($request->esub_kit_qtys); $index++){
                    SubKitting::insert([
                        'pats_kitting_id' => $ekitting_id,
                        'sub_kit_desc' => $request->edescriptions[$index],
                        'sub_kit_qty' => $request->esub_kit_qtys[$index],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0", "exception" => $e]);
            }
        }
    }

    public function generate_sub_kit_qrcode(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = SubKitting::where('id', $request->id)
            ->first();
        // $data = SubKitting::with(['materialKitting', => function($query){
        //     $query->where('status', 1);
        // }])
        // ->where('id', $request->id)
        // ->first();
        
        $po_qrcode = "";
        $lot_no_qrcode = "";
        $item_code_qrcode = "";

        // return response()->json(['data' => $data, 'po_qrcode' => $po_qrcode, 'lot_no_qrcode' => $lot_no_qrcode]);

        if($data != null){
            $po_qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate(explode(' | ', $data->sub_kit_desc)[2]);
            
            $lot_no_qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate(explode(' | ', $data->sub_kit_desc)[4] . ' | ' . explode(' | ', $data->sub_kit_desc)[5] . ' | ' . explode(' | ', $data->sub_kit_desc)[9]);

            $item_code_qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate(explode(' | ', $data->sub_kit_desc)[5]);

            $po_qrcode = "data:image/png;base64," . base64_encode($po_qrcode);

            $lot_no_qrcode = "data:image/png;base64," . base64_encode($lot_no_qrcode);

            $item_code_qrcode = "data:image/png;base64," . base64_encode($item_code_qrcode);
        }

        return response()->json(['data' => $data, 'po_qrcode' => $po_qrcode, 'lot_no_qrcode' => $lot_no_qrcode, 'item_code_qrcode' => $item_code_qrcode]);
    }
}