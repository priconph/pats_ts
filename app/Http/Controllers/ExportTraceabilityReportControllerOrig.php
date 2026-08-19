<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Device;
use App\Model\Dlabel;
use App\Model\PartsPrep;
use App\Model\DlabelBoxes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\DefectEscalation;
use App\Model\ProductionRuncard;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportTraceability;

use App\Model\WBSSakidashiIssuance;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\ProductionRuncardTsPpts;
use App\Exports\ExportTraceabilityOrig;
use App\Model\ProductionRuncardStation;
use App\Model\WBSSakidashiIssuanceItem;
use App\Exports\ExportTraceabilityReport;
use Illuminate\Support\Facades\Validator;
use App\Model\ProductionRuncardStationTsPpts;
use App\Model\ProductionRuncardStationMaterial;
use App\Model\ProductionRuncardStationOperator;
use App\Exports\ExportTraceabilityReport_dynamic;
use App\Model\WBSKitIssuance;//tbl_wbs_material_kitting
use App\Model\MaterialIssuanceSubSystem;//tbl_wbs_material_kitting

// use App\Model\ProdPrelimInspection;

class ExportTraceabilityReportControllerOrig extends Controller{

    //TEST 09/18/23

    public function export_traceability_report_data(Request $request,$po_number,$lot_no_from,$lot_no_to,$shipment_date_live)
    {

        // return $shipment_date_live;

        // if($lot_no_from > $lot_no_to){
        //     return redirect()->back() ->with('alert', 'LOT TO CANNOT BE LESS THAN THE LOT FROM!');
        // }

        // return gettype($po_number);
        if(str_contains($po_number,'ES')){
            $po_number_susbtr = substr($po_number,5);
            // return 'qwe';

            if($lot_no_from <= 9 ){
                $po_lot_from = $po_number_susbtr." LOT-00".$lot_no_from;
            }else if ($lot_no_from >= 10 && $lot_no_from <= 99){
                $po_lot_from = $po_number_susbtr." LOT-0".$lot_no_from;
            }else if ($lot_no_from >= 100){
                $po_lot_from = $po_number_susbtr." LOT-".$lot_no_from;
            }

            if($lot_no_to <= 9 ){
                $po_lot_to = $po_number_susbtr." LOT-00".$lot_no_to;
            }else if ($lot_no_to >= 10 && $lot_no_to <= 99 ){
                $po_lot_to = $po_number_susbtr." LOT-0".$lot_no_to;
            }
            else if ($lot_no_to >= 100 ){
                $po_lot_to = $po_number_susbtr." LOT- ".$lot_no_to;
            }

        }else{
            $po_number_susbtr = substr($po_number,5,5);
            // return $po_number_susbtr;

            if($lot_no_from <= 9 ){
                $po_lot_from = $po_number_susbtr." LOT-00".$lot_no_from;
            }else if ($lot_no_from >= 10 && $lot_no_from <= 99){
                $po_lot_from = $po_number_susbtr." LOT-0".$lot_no_from;
            }else if ($lot_no_from >= 100){
                $po_lot_from = $po_number_susbtr." LOT-".$lot_no_from;
            }

            if($lot_no_to <= 9 ){
                $po_lot_to = $po_number_susbtr." LOT-00".$lot_no_to;
            }else if ($lot_no_to >= 10 && $lot_no_to <= 99 ){
                $po_lot_to = $po_number_susbtr." LOT-0".$lot_no_to;
            }
            else if ($lot_no_to >= 100 ){
                $po_lot_to = $po_number_susbtr." LOT- ".$lot_no_to;
            }

        }

        // return $po_lot_from;



        // return $po_lot_from . ' '. $po_lot_to;

        $prodn_runcard_tsppts = ProductionRuncardTsPpts::with(['prod_runcard_station_details' => function($query){
            $query->where('status', 1);
        }])
        ->where('po_no', $po_number)
        ->where('deleted_at', null)
        ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
        ->get();

        // return $prodn_runcard_tsppts;

        /**
         * Data checker before matching to other tables
         * Return "useful" error to debug correctly and avoid katangahan darren!!!
         */
        // if(!count($prodn_runcard_tsppts) > 0){
        //     return redirect()->back() ->with('alert', 'Variable Name: $prodn_runcard_tsppts, not found any data');
        // }

        $first = $prodn_runcard_tsppts[0];
        $last = $prodn_runcard_tsppts[count($prodn_runcard_tsppts)-1];

        // return [$first,$last];

        // return $prodn_runcard_tsppts;


        $materialprocess = CommonController::getInstance()->getMatrixByPoNo(['po_no' => $po_number_susbtr, 'name' => $prodn_runcard_tsppts[0]->device_name, 'barcode' => '', 'get_or_first' => '']);
        // $materialprocess = Device::where('name', $prodn_runcard_tsppts[0]->device_name)
        // // ->where('status', 1)
        // ->get();

        // return $materialprocess;

        // $dlabel_tsppts =

        $tempo_array = [
            "runcard_no" => [],
        ];
        for ($i=0; $i <count($prodn_runcard_tsppts) ; $i++) {
            // array_push($tempo_array, $prodn_runcard_tsppts[$i]->prod_runcard_station_details);
            for ($j=0; $j <count($prodn_runcard_tsppts[$i]->prod_runcard_station_details) ; $j++) {

                array_push($tempo_array['runcard_no'], $prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no);

            }
        }

        // return $prodn_runcard_tsppts;
        // return $tempo_array;


        $runcard_no = substr($po_number,0,10);

        $prodn_runcard = ProductionRuncard::with('prod_runcard_station_many_details.prod_runcard_station_material_details')
        ->whereIn('runcard_no', $tempo_array['runcard_no'])
        ->get();

        // return $prodn_runcard;


        $imploded = "'".implode("','", $tempo_array['runcard_no'])."'";

        $material_name = DB::connection('mysql')->select(' SELECT DISTINCT(c.material_name) FROM `production_runcards` AS a INNER JOIN `production_runcard_stations2` AS b ON a.id = b.production_runcard_id INNER JOIN `prod_runcard_station_materials` AS c ON b.id = c.production_runcard_station_id WHERE a.runcard_no IN ('.$imploded.')');

        $parts_prep = PartsPrep::where('runcard_po',$runcard_no)
        ->where('wbs_table',2)
        ->get();

        // return $material_name;

        $tempo_array_id = [
            "parts_prep_wbs_kits_issuance_id" => [],
        ];

        for ($i=0; $i <count($parts_prep); $i++) {
            # code...
            // $parts_prep_wbs_kits_issuance_id = $parts_prep[$i]->wbs_kit_issuance_id;
            array_push($tempo_array_id['parts_prep_wbs_kits_issuance_id'], $parts_prep[$i]->wbs_kit_issuance_id);
        }

        // return $parts_prep;

        $sakidashi_issuance = WBSSakidashiIssuance::where('po_no', $po_number)
        // ->where('id',$parts_prep_wbs_kits_issuance_id)
        ->whereIn('id', $tempo_array_id['parts_prep_wbs_kits_issuance_id'])
        ->get();

        $tempo_array_issuance = [
            "issuance_no" => [],
        ];

        for ($x=0; $x <count($sakidashi_issuance); $x++) {
            array_push($tempo_array_issuance['issuance_no'], $sakidashi_issuance[$x]->issuance_no);
        }

        $sakidashi_issuance_item = WBSSakidashiIssuanceItem::whereIn('issuance_no', $tempo_array_issuance['issuance_no'])
        ->get();

        // return $sakidashi_issuance;

        $dlabel = Dlabel::where('po_no',$po_number)
        ->where('shipment_date', $shipment_date_live)
        ->get();

        // return $dlabel;

        $dlabel_id = [
            "dlabel_id" => [],
        ];

        for ($q=0; $q <count($dlabel); $q++) {
            array_push($dlabel_id['dlabel_id'], $dlabel[$q]->id);
        }

        // return $dlabel_id;

        $dlabel_boxes = DlabelBoxes::whereIn('d_label_id',$dlabel_id['dlabel_id'])
        ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
        ->get();

        // return $dlabel_boxes;

        $prodn_runcard_array = [
            "id" => []
        ];

        for ($n=0; $n <count($prodn_runcard) ; $n++) {
            array_push($prodn_runcard_array['id'], $prodn_runcard[$n]->id);
        }

        $operator_details = ProductionRuncardStationOperator::with(['infoHeadCheckedby'])
        ->whereIn('production_runcard_id',$prodn_runcard_array['id'])
        // ->groupBy('production_runcard_id')
        ->get();

        $operator_details = collect($operator_details)->groupBy('production_runcard_id');

        $defect_escalation = DefectEscalation::
        with('rework_station_many_details')
        ->where('po_no', $po_number)
        // ->whereNotNull('runcard_no')
        ->get();

        // return $defect_escalation;

        ob_end_clean();
        ob_start();
        return Excel::download(new ExportTraceabilityReport($prodn_runcard,$prodn_runcard_tsppts,$material_name,$sakidashi_issuance_item,$dlabel,$dlabel_boxes,$materialprocess,$operator_details,$defect_escalation,$shipment_date_live), 'PATS generated.xlsx');

    }

    // TEST 09/18/23
    public function get_lot_no_list_test(Request $request){
        $data = $request->all();

        $dlabel = Dlabel::where('po_no',$request->po_number)
        ->where('shipment_date', $request->shipment_date_live)
        ->get();

        $dlabel_id = [
            "dlabel_id" => [],
        ];

        // return $dlabel;
        if(count($dlabel) > 0){
            for ($q=0; $q <count($dlabel); $q++) {
                array_push($dlabel_id['dlabel_id'], $dlabel[$q]->id);
            }

            $dlabel_boxes = DlabelBoxes::whereIn('d_label_id',$dlabel_id['dlabel_id'])
            ->orderBy('lot_id', 'ASC')
            // ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
            ->get();
        }else{
            return "no data";
        }

        // return $dlabel_boxes;

        $start = $dlabel_boxes[0];
        $end = $dlabel_boxes[count($dlabel_boxes)-1];

        return response()->json(['lot_no_start' => $start, 'lot_no_end' => $end]);
    }


    // LIVE 09/18/23
    public function get_lot_no_list(Request $request){
        $data = $request->all();

        // return $data;

        $dlabel = Dlabel::where('po_no',$request->po_number_test)
        ->where('shipment_date', $request->shipment_date_test)
        ->get();

        // return $dlabel;

        $dlabel_id = [
            "dlabel_id" => [],
        ];

        // return $dlabel;

        if(count($dlabel) > 0){
            for ($q=0; $q <count($dlabel); $q++) {
                array_push($dlabel_id['dlabel_id'], $dlabel[$q]->id);
            }

            $dlabel_boxes = DlabelBoxes::whereIn('d_label_id',$dlabel_id['dlabel_id'])
            ->orderBy('lot_id', 'ASC')
            // ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
            ->get();
        }else{
            return "no data";
        }

        // return $dlabel_boxes;

        $start = $dlabel_boxes[0];
        $end = $dlabel_boxes[count($dlabel_boxes)-1];

        return response()->json(['lot_no_start' => $start, 'lot_no_end' => $end]);
    }
    // LIVE 09/18/23
    public function get_lot_no_list_1(Request $request){ //nmodify

        if($request->shipment_date_test == ''){
            $prodn_runcard_tsppts = ProductionRuncardTsPpts::with(['oqc_lot_app_details' => function($query){
                $query->where('status', 2);
            },
            'prod_runcard_station_details' => function($query){
                $query->where('status', 1); // change from 1 to 4 10/2/2023
            }])
            ->where('status', 4)
            ->where('po_no', $request->po_number_test)
            ->where('deleted_at', null)
            ->get();
           if ($prodn_runcard_tsppts->isNotEmpty()) {
                // 3. Used sortBy() on the collection to find start and end
                $start = $prodn_runcard_tsppts->sortBy('id')->first();
                $end   = $prodn_runcard_tsppts->sortByDesc('id')->first();
                return response()->json([
                    'isSuccess' => 'true',
                    'lot_no_start' => $start,
                    'lot_no_end'   => $end
                ]);
            }
        }
        // 1. Use pluck() to get IDs directly instead of a manual loop
        $dlabelIds = Dlabel::where('po_no', $request
        ->po_number_test);
        if($request->shipment_date_test != ''){
            $dlabelIds->where('shipment_date', $request->shipment_date_test);
        }
        $dlabelIds = $dlabelIds->pluck('id');

        // 2. Efficient check for empty results
        if ($dlabelIds->isEmpty()) {
            return response()->json(['isSuccess' => 'false', 'message' => 'No data found', 'lot_no_start' => '',
            'lot_no_end'   => '']);
        }

        // 3. Create a base query (do not use ->get() yet)
        $boxesQuery = DlabelBoxes::whereIn('d_label_id', $dlabelIds);

        // 4. Fetch ONLY the two records we need from the database
        // This prevents memory issues if there are thousands of boxes
        $start = (clone $boxesQuery)->orderBy('lot_id', 'ASC')->first();
        $end   = (clone $boxesQuery)->orderBy('lot_id', 'DESC')->first();

        return response()->json([
            'isSuccess' => 'true',
            'lot_no_start' => $start,
            'lot_no_end'   => $end
        ]);
        $data = $request->all();
        return $dlabel = Dlabel::where('po_no',$request->po_number_test)
        ->get();

        $dlabel_id = [
            "dlabel_id" => [],
        ];
        if(count($dlabel) > 0){
            for ($q=0; $q <count($dlabel); $q++) {
                array_push($dlabel_id['dlabel_id'], $dlabel[$q]->id);
            }

            $dlabel_boxes = DlabelBoxes::whereIn('d_label_id',$dlabel_id['dlabel_id'])
            ->orderBy('lot_id', 'ASC')
            ->get();
        }else{
            return response()->json(['isSuccess','false'],500);
        }

        $start = $dlabel_boxes[0];
        $end = $dlabel_boxes[count($dlabel_boxes)-1];

        return response()->json(['lot_no_start' => $start, 'lot_no_end' => $end]);
    }

    // LIVE 09/18/23
    public function export_traceability(Request $request,$po_number,$lot_no_from,$lot_no_to,$shipment_date_test)
    {

        return $shipment_date_test;

        // if($lot_no_from > $lot_no_to){
        //     return redirect()->back() ->with('alert', 'LOT TO CANNOT BE LESS THAN THE LOT FROM!');
        // }

        // return gettype($po_number);
        if(str_contains($po_number,'ES')){
            $po_number_susbtr = substr($po_number,5);

            if($lot_no_from <= 9 ){
                $po_lot_from = $po_number_susbtr." LOT-00".$lot_no_from;
            }else if ($lot_no_from >= 10 && $lot_no_from <= 99){
                $po_lot_from = $po_number_susbtr." LOT-0".$lot_no_from;
            }else if ($lot_no_from >= 100){
                $po_lot_from = $po_number_susbtr." LOT-".$lot_no_from;
            }

            if($lot_no_to <= 9 ){
                $po_lot_to = $po_number_susbtr." LOT-00".$lot_no_to;
            }else if ($lot_no_to >= 10 && $lot_no_to <= 99 ){
                $po_lot_to = $po_number_susbtr." LOT-0".$lot_no_to;
            }
            else if ($lot_no_to >= 100 ){
                $po_lot_to = $po_number_susbtr." LOT-".$lot_no_to;
            }

        }else{
            // $po_number_susbtr = substr($po_number,5,5); //ORIG uncomment lang pag old format ng lot #
            $po_number_susbtr = substr($po_number,4,4); //07222025 by Nessa

            if($lot_no_from <= 9 ){
                // $po_lot_from = $po_number_susbtr." LOT-00".$lot_no_from; //ORIG uncomment lang pag old format ng lot #
                $po_lot_from = $po_number_susbtr."LOT-00".$lot_no_from; //07222025 by Nessa
            }else if ($lot_no_from >= 10 && $lot_no_from <= 99){
                $po_lot_from = $po_number_susbtr." LOT-0".$lot_no_from;
            }else if ($lot_no_from >= 100){
                $po_lot_from = $po_number_susbtr." LOT-".$lot_no_from;
            }

            if($lot_no_to <= 9 ){
                // $po_lot_to = $po_number_susbtr." LOT-00".$lot_no_to; //ORIG uncomment lang pag old format ng lot #
                $po_lot_to = $po_number_susbtr."LOT-00".$lot_no_to; //07222025 by Nessa
            }else if ($lot_no_to >= 10 && $lot_no_to <= 99 ){
                $po_lot_to = $po_number_susbtr." LOT-0".$lot_no_to;
            }
            else if ($lot_no_to >= 100 ){
                $po_lot_to = $po_number_susbtr." LOT-".$lot_no_to;
            }

        }

        // return $po_number_susbtr;


        return $dlabel = Dlabel::where('po_no',$po_number)
        ->where('shipment_date', $shipment_date_test)
        ->get();

        // return $dlabel;

        $dlabel_id = [
            "dlabel_id" => [],
        ];

        if(count($dlabel) > 0){
            for ($q=0; $q <count($dlabel); $q++) {
                array_push($dlabel_id['dlabel_id'], $dlabel[$q]->id);
            }
        }else{
            return "NO d_label data";
        }

        // return $dlabel_id;

        $dlabel_boxes = DlabelBoxes::whereIn('d_label_id',$dlabel_id['dlabel_id'])
        ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
        ->get();

        // return $dlabel_boxes;


        $prodn_runcard_tsppts = ProductionRuncardTsPpts::with(['prod_runcard_station_details' => function($query){
            $query->where('status', 1); // change from 1 to 4 10/2/2023
        }])
        ->where('status', 4)
        ->where('po_no', $po_number)
        ->where('deleted_at', null)
        ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
        ->get();

        // return $prodn_runcard_tsppts;

        $materialprocess = CommonController::getInstance()->getMatrixByPoNo([
            'po_no'=> $po_number,
            'name'=>  $prodn_runcard_tsppts[0]->device_name,
            'barcode'=> '',
            'get_or_first'=> '',
        ]);

        // $materialprocess = Device::where('name', $prodn_runcard_tsppts[0]->device_name)->where('status', 1)->get();

        // return $materialprocess;

        $tempo_array = [
            "runcard_no" => [],
        ];

        if(count($prodn_runcard_tsppts) > 0){
            for ($i=0; $i <count($prodn_runcard_tsppts) ; $i++) {
                for ($j=0; $j <count($prodn_runcard_tsppts[$i]->prod_runcard_station_details) ; $j++) {
                        array_push($tempo_array['runcard_no'], strtoupper($prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no));
                }
            }
        }else{
            return "no ts_ppts runcard details";
        }


        $runcard_no = substr($po_number,0,10);

        $prodn_runcard = ProductionRuncard::with('prod_runcard_station_many_details.prod_runcard_station_material_details')
        // ->where('prod_runcard_station_many_details.prod_runcard_station_material_details.status', 1)
        ->whereIn('runcard_no', $tempo_array['runcard_no'])
        // ->orWhereIn ('runcard_no', $de_runcard_no['de_runcard_no'])
        ->get();

        // return $prodn_runcard;

        // return $tempo_array['runcard_no'];


        $imploded = "'".implode("','", $tempo_array['runcard_no'])."'";

        $material_name = DB::connection('mysql')->select(' SELECT DISTINCT(c.material_name) FROM `production_runcards` AS a INNER JOIN `production_runcard_stations2` AS b ON a.id = b.production_runcard_id INNER JOIN `prod_runcard_station_materials` AS c ON b.id = c.production_runcard_station_id WHERE a.runcard_no IN ('.$imploded.')');

        // return response()->json(['jannus' => $material_name]);

        $parts_prep = PartsPrep::where('runcard_po',$runcard_no)
        ->where('wbs_table',2)
        ->get();

        // return $parts_prep;

        $tempo_array_id = [
            "parts_prep_wbs_kits_issuance_id" => [],
        ];

        if(count($parts_prep) > 0){
            for ($i=0; $i <count($parts_prep); $i++) {
                array_push($tempo_array_id['parts_prep_wbs_kits_issuance_id'], $parts_prep[$i]->wbs_kit_issuance_id);
            }
        }else{
            return "no parts_prep data";
        }

        // return $parts_prep;

        $sakidashi_issuance = WBSSakidashiIssuance::where('po_no', $po_number)
        // ->where('id',$parts_prep_wbs_kits_issuance_id)
        ->whereIn('id', $tempo_array_id['parts_prep_wbs_kits_issuance_id'])
        ->get();

        // return $sakidashi_issuance;

        $tempo_array_issuance = [
            "issuance_no" => [],
        ];

        if(count($sakidashi_issuance) > 0){
            for ($x=0; $x <count($sakidashi_issuance); $x++) {
                array_push($tempo_array_issuance['issuance_no'], $sakidashi_issuance[$x]->issuance_no);
            }
        }else{
            return "no sakidashi_issuance data";
        }


        $sakidashi_issuance_item = WBSSakidashiIssuanceItem::whereIn('issuance_no', $tempo_array_issuance['issuance_no'])
        ->get();

        // return $sakidashi_issuance_item;

        $prodn_runcard_array = [
            "id" => [],
            "defect_rework_number" => []
        ];

        if(count($prodn_runcard) > 0){
            for ($n=0; $n <count($prodn_runcard); $n++) {
                array_push($prodn_runcard_array['id'], $prodn_runcard[$n]->id);
                array_push($prodn_runcard_array['defect_rework_number'], $prodn_runcard[$n]->defect_rework_number);
            }
        }else{
            return "no runcard details for operator";
        }



        $operator_details = ProductionRuncardStationOperator::with(['infoHeadCheckedby'])
        ->whereIn('production_runcard_id',$prodn_runcard_array['id'])
        // ->groupBy('production_runcard_id')
        ->get();

        $operator_details = collect($operator_details)->groupBy('production_runcard_id');


        // return $operator_details;

        // $runcard_defect_no = $prodn_runcard_array['defect_rework_number'];
        // $runcard_defect_no = array_flip($runcard_defect_no);

        // $runcard_defect_no = array_change_key_case($runcard_defect_no,CASE_UPPER);
        // $runcard_defect_no = array_flip($runcard_defect_no);

        // return $runcard_defect_no;

        // return (array_change_key_case($prodn_runcard_array['defect_rework_number'],CASE_UPPER));

        // $defect_escalation = DefectEscalation::
        // with('defect_escalation_material_list')
        // ->where('po_no', $po_number)
        // ->whereIn('defect_escalation_no',$runcard_defect_no)
        // ->get();

        $defect_escalation = DefectEscalation::
        with([
            // 'defect_escalation_material_list',
            'rework_station_many_details',
            'rework_station_many_details.defect_materials',
            'rework_details',
            'rework_details.prod_runcard_station_materials'
            ])
        ->where('po_no', $po_number)
        // ->where('defect_escalation_no',$runcard_defect_no) // commented 10/9/23
        // ->where('defect_escalation_material_list.defect_escalation_id', $runcard_from_pats)
        ->get();

        // return $defect_escalation;

        // $defect_escalation_no = [
        //     "defect_escalation_no" => []
        // ];

        // if(count($defect_escalation) > 0){
        //     for ($s=0; $s <count($defect_escalation); $s++) {
        //         array_push($defect_escalation_no['defect_escalation_no'], $defect_escalation[$s]->defect_escalation_no);
        //     }
        // }else{
        //     return "no defect_escalation data";
        // }


        ob_end_clean();
        ob_start();
        return Excel::download(new ExportTraceabilityReport_dynamic($prodn_runcard,$prodn_runcard_tsppts,$material_name,$sakidashi_issuance_item,$dlabel,$dlabel_boxes,$materialprocess,$operator_details,$defect_escalation,$shipment_date_test), 'PATS generated.xlsx');
        // return Excel::download(new ExportTraceabilityReport_dynamic($prodn_runcard,$prodn_runcard_tsppts,$material_name,$sakidashi_issuance_item,$dlabel,$dlabel_boxes,$materialprocess,$operator_details,$defect_escalation), 'PATS generated.xlsx');

    }

    public function export_traceability_1(Request $request,$po_number,$lot_no_from,$lot_no_to,$shipment_date_test)
    {

        // return $shipment_date_test;

        // if($lot_no_from > $lot_no_to){
        //     return redirect()->back() ->with('alert', 'LOT TO CANNOT BE LESS THAN THE LOT FROM!');
        // }

        // return gettype($po_number);
        if(str_contains($po_number,'ES')){
            $po_number_susbtr = substr($po_number,5);

            if($lot_no_from <= 9 ){
                $po_lot_from = $po_number_susbtr." LOT-00".$lot_no_from;
            }else if ($lot_no_from >= 10 && $lot_no_from <= 99){
                $po_lot_from = $po_number_susbtr." LOT-0".$lot_no_from;
            }else if ($lot_no_from >= 100){
                $po_lot_from = $po_number_susbtr." LOT-".$lot_no_from;
            }

            if($lot_no_to <= 9 ){
                $po_lot_to = $po_number_susbtr." LOT-00".$lot_no_to;
            }else if ($lot_no_to >= 10 && $lot_no_to <= 99 ){
                $po_lot_to = $po_number_susbtr." LOT-0".$lot_no_to;
            }
            else if ($lot_no_to >= 100 ){
                $po_lot_to = $po_number_susbtr." LOT-".$lot_no_to;
            }

        }else{
            // $po_number_susbtr = substr($po_number,5,5); //ORIG uncomment lang pag old format ng lot #
            $po_number_susbtr = substr($po_number,4,4); //07222025 by Nessa

            if($lot_no_from <= 9 ){
                // $po_lot_from = $po_number_susbtr." LOT-00".$lot_no_from; //ORIG uncomment lang pag old format ng lot #
                $po_lot_from = $po_number_susbtr."LOT-00".$lot_no_from; //07222025 by Nessa
            }else if ($lot_no_from >= 10 && $lot_no_from <= 99){
                $po_lot_from = $po_number_susbtr." LOT-0".$lot_no_from;
            }else if ($lot_no_from >= 100){
                $po_lot_from = $po_number_susbtr." LOT-".$lot_no_from;
            }

            if($lot_no_to <= 9 ){
                // $po_lot_to = $po_number_susbtr." LOT-00".$lot_no_to; //ORIG uncomment lang pag old format ng lot #
                $po_lot_to = $po_number_susbtr."LOT-00".$lot_no_to; //07222025 by Nessa
            }else if ($lot_no_to >= 10 && $lot_no_to <= 99 ){
                $po_lot_to = $po_number_susbtr." LOT-0".$lot_no_to;
            }
            else if ($lot_no_to >= 100 ){
                $po_lot_to = $po_number_susbtr." LOT-".$lot_no_to;
            }

        }

        // return $po_number_susbtr;


        return $dlabel = Dlabel::where('po_no',$po_number)
        ->where('shipment_date', $shipment_date_test)
        ->get();

        // return $dlabel;

        $dlabel_id = [
            "dlabel_id" => [],
        ];

        if(count($dlabel) > 0){
            for ($q=0; $q <count($dlabel); $q++) {
                array_push($dlabel_id['dlabel_id'], $dlabel[$q]->id);
            }
        }else{
            return "NO d_label data";
        }

        // return $dlabel_id;

        $dlabel_boxes = DlabelBoxes::whereIn('d_label_id',$dlabel_id['dlabel_id'])
        ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
        ->get();

        // return $dlabel_boxes;


        $prodn_runcard_tsppts = ProductionRuncardTsPpts::with(['prod_runcard_station_details' => function($query){
            $query->where('status', 1); // change from 1 to 4 10/2/2023
        }])
        ->where('status', 4)
        ->where('po_no', $po_number)
        ->where('deleted_at', null)
        ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
        ->get();

        // return $prodn_runcard_tsppts;

           $materialprocess = CommonController::getInstance()->getMatrixByPoNo([
            'po_no'=> $prod_runcard->po_no,
            'name'=>  $prodn_runcard_tsppts[0]->device_name,
            'barcode'=> '',
            'get_or_first'=> '',
        ]);

        $materialprocess = CommonController::getInstance()->getMatrixByPoNo([
            'po_no'=> $po_number,
            'name'=>  $prodn_runcard_tsppts[0]->device_name,
            'barcode'=> '',
            'get_or_first'=> '',
        ]);

        // $materialprocess = Device::where('name', $prodn_runcard_tsppts[0]->device_name)
        // ->where('status', 1)
        // ->get();

        // return $materialprocess;

        $tempo_array = [
            "runcard_no" => [],
        ];

        if(count($prodn_runcard_tsppts) > 0){
            for ($i=0; $i <count($prodn_runcard_tsppts) ; $i++) {
                for ($j=0; $j <count($prodn_runcard_tsppts[$i]->prod_runcard_station_details) ; $j++) {
                        array_push($tempo_array['runcard_no'], strtoupper($prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no));
                }
            }
        }else{
            return "no ts_ppts runcard details";
        }


        $runcard_no = substr($po_number,0,10);

        $prodn_runcard = ProductionRuncard::with('prod_runcard_station_many_details.prod_runcard_station_material_details')
        // ->where('prod_runcard_station_many_details.prod_runcard_station_material_details.status', 1)
        ->whereIn('runcard_no', $tempo_array['runcard_no'])
        // ->orWhereIn ('runcard_no', $de_runcard_no['de_runcard_no'])
        ->get();

        // return $prodn_runcard;

        // return $tempo_array['runcard_no'];


        $imploded = "'".implode("','", $tempo_array['runcard_no'])."'";

        $material_name = DB::connection('mysql')->select(' SELECT DISTINCT(c.material_name) FROM `production_runcards` AS a INNER JOIN `production_runcard_stations2` AS b ON a.id = b.production_runcard_id INNER JOIN `prod_runcard_station_materials` AS c ON b.id = c.production_runcard_station_id WHERE a.runcard_no IN ('.$imploded.')');

        // return response()->json(['jannus' => $material_name]);

        $parts_prep = PartsPrep::where('runcard_po',$runcard_no)
        ->where('wbs_table',2)
        ->get();

        // return $parts_prep;

        $tempo_array_id = [
            "parts_prep_wbs_kits_issuance_id" => [],
        ];

        if(count($parts_prep) > 0){
            for ($i=0; $i <count($parts_prep); $i++) {
                array_push($tempo_array_id['parts_prep_wbs_kits_issuance_id'], $parts_prep[$i]->wbs_kit_issuance_id);
            }
        }else{
            return "no parts_prep data";
        }

        // return $parts_prep;

        $sakidashi_issuance = WBSSakidashiIssuance::where('po_no', $po_number)
        // ->where('id',$parts_prep_wbs_kits_issuance_id)
        ->whereIn('id', $tempo_array_id['parts_prep_wbs_kits_issuance_id'])
        ->get();

        // return $sakidashi_issuance;

        $tempo_array_issuance = [
            "issuance_no" => [],
        ];

        if(count($sakidashi_issuance) > 0){
            for ($x=0; $x <count($sakidashi_issuance); $x++) {
                array_push($tempo_array_issuance['issuance_no'], $sakidashi_issuance[$x]->issuance_no);
            }
        }else{
            return "no sakidashi_issuance data";
        }


        $sakidashi_issuance_item = WBSSakidashiIssuanceItem::whereIn('issuance_no', $tempo_array_issuance['issuance_no'])
        ->get();

        // return $sakidashi_issuance_item;

        $prodn_runcard_array = [
            "id" => [],
            "defect_rework_number" => []
        ];

        if(count($prodn_runcard) > 0){
            for ($n=0; $n <count($prodn_runcard); $n++) {
                array_push($prodn_runcard_array['id'], $prodn_runcard[$n]->id);
                array_push($prodn_runcard_array['defect_rework_number'], $prodn_runcard[$n]->defect_rework_number);
            }
        }else{
            return "no runcard details for operator";
        }



        $operator_details = ProductionRuncardStationOperator::with(['infoHeadCheckedby'])
        ->whereIn('production_runcard_id',$prodn_runcard_array['id'])
        // ->groupBy('production_runcard_id')
        ->get();

        $operator_details = collect($operator_details)->groupBy('production_runcard_id');


        // return $operator_details;

        // $runcard_defect_no = $prodn_runcard_array['defect_rework_number'];
        // $runcard_defect_no = array_flip($runcard_defect_no);

        // $runcard_defect_no = array_change_key_case($runcard_defect_no,CASE_UPPER);
        // $runcard_defect_no = array_flip($runcard_defect_no);

        // return $runcard_defect_no;

        // return (array_change_key_case($prodn_runcard_array['defect_rework_number'],CASE_UPPER));

        // $defect_escalation = DefectEscalation::
        // with('defect_escalation_material_list')
        // ->where('po_no', $po_number)
        // ->whereIn('defect_escalation_no',$runcard_defect_no)
        // ->get();

        $defect_escalation = DefectEscalation::
        with([
            // 'defect_escalation_material_list',
            'rework_station_many_details',
            'rework_station_many_details.defect_materials',
            'rework_details',
            'rework_details.prod_runcard_station_materials'
            ])
        ->where('po_no', $po_number)
        // ->where('defect_escalation_no',$runcard_defect_no) // commented 10/9/23
        // ->where('defect_escalation_material_list.defect_escalation_id', $runcard_from_pats)
        ->get();

        // return $defect_escalation;

        // $defect_escalation_no = [
        //     "defect_escalation_no" => []
        // ];

        // if(count($defect_escalation) > 0){
        //     for ($s=0; $s <count($defect_escalation); $s++) {
        //         array_push($defect_escalation_no['defect_escalation_no'], $defect_escalation[$s]->defect_escalation_no);
        //     }
        // }else{
        //     return "no defect_escalation data";
        // }


        ob_end_clean();
        ob_start();
        return Excel::download(new ExportTraceabilityReport_dynamic($prodn_runcard,$prodn_runcard_tsppts,$material_name,$sakidashi_issuance_item,$dlabel,$dlabel_boxes,$materialprocess,$operator_details,$defect_escalation,$shipment_date_test), 'PATS generated.xlsx');
        // return Excel::download(new ExportTraceabilityReport_dynamic($prodn_runcard,$prodn_runcard_tsppts,$material_name,$sakidashi_issuance_item,$dlabel,$dlabel_boxes,$materialprocess,$operator_details,$defect_escalation), 'PATS generated.xlsx');

    }
    public function export_traceability_test(Request $request)
    {
        $po_number = $request->po_number;
        $lot_no_from = $request->lot_no_from;
        $lot_no_to = $request->lot_no_to;
        $shipment_date_test = $request->shipment_date_test;
        if(str_contains($po_number,'ES')){
            $po_number_susbtr = substr($po_number,5);

            if($lot_no_from <= 9 ){
                $po_lot_from = $po_number_susbtr." LOT-00".$lot_no_from;
            }else if ($lot_no_from >= 10 && $lot_no_from <= 99){
                $po_lot_from = $po_number_susbtr." LOT-0".$lot_no_from;
            }else if ($lot_no_from >= 100){
                $po_lot_from = $po_number_susbtr." LOT-".$lot_no_from;
            }

            if($lot_no_to <= 9 ){
                $po_lot_to = $po_number_susbtr." LOT-00".$lot_no_to;
            }else if ($lot_no_to >= 10 && $lot_no_to <= 99 ){
                $po_lot_to = $po_number_susbtr." LOT-0".$lot_no_to;
            }
            else if ($lot_no_to >= 100 ){
                $po_lot_to = $po_number_susbtr." LOT-".$lot_no_to;
            }
        }else{
            // $po_number_susbtr = substr($po_number,5,5); //ORIG uncomment lang pag old format ng lot #
            $po_number_susbtr = substr($po_number,4,4); //07222025 by Nessa

            if($lot_no_from <= 9 ){
                // $po_lot_from = $po_number_susbtr." LOT-00".$lot_no_from; //ORIG uncomment lang pag old format ng lot #
                $po_lot_from = $po_number_susbtr."LOT-00".$lot_no_from; //07222025 by Nessa
            }else if ($lot_no_from >= 10 && $lot_no_from <= 99){
                $po_lot_from = $po_number_susbtr."LOT-0".$lot_no_from;
            }else if ($lot_no_from >= 100){
                $po_lot_from = $po_number_susbtr."LOT-".$lot_no_from;
            }

            if($lot_no_to <= 9 ){
                // $po_lot_to = $po_number_susbtr." LOT-00".$lot_no_to; //ORIG uncomment lang pag old format ng lot #
                $po_lot_to = $po_number_susbtr."LOT-00".$lot_no_to; //07222025 by Nessa
            }else if ($lot_no_to >= 10 && $lot_no_to <= 99 ){
                $po_lot_to = $po_number_susbtr."LOT-0".$lot_no_to;
            }
            else if ($lot_no_to >= 100 ){
                $po_lot_to = $po_number_susbtr."LOT-".$lot_no_to;
            }

        }


        $dlabel = Dlabel::where('po_no',$po_number);
        if($shipment_date_test != ''){
            $dlabel->where('shipment_date', $shipment_date_test);
        }

        $dlabel = $dlabel->get();
        $dlabel_id = [
            "dlabel_id" => [],
        ];

        if(count($dlabel) != 0){
            if(count($dlabel) > 0){
                for ($q=0; $q <count($dlabel); $q++) {
                    array_push($dlabel_id['dlabel_id'], $dlabel[$q]->id);
                }
            }else{
                return "NO d_label data";
            }

            $dlabel_boxes = DlabelBoxes::whereIn('d_label_id',$dlabel_id['dlabel_id'])
            ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
            ->get();
        }
        $prodn_runcard_tsppts = ProductionRuncardTsPpts::with(['prod_runcard_station_details' => function($query){
            $query->where('status', 1); // change from 1 to 4 10/2/2023
        }])
        ->where('status', 4)
        ->where('po_no', $po_number)
        ->where('deleted_at', null)
        ->whereBetween('lot_no', [$po_lot_from,$po_lot_to])
        ->get();


        // $materialprocess = Device::where('name', $prodn_runcard_tsppts[0]->device_name)
        // ->get();

         $materialprocess = CommonController::getInstance()->getMatrixByPoNo([
            'po_no'=> $po_number,
            'name'=>  $prodn_runcard_tsppts[0]->device_name,
            'barcode'=> '',
            'get_or_first'=> '',
        ]);

        $tempo_array = [
            "runcard_no" => [],
        ];

        if(count($prodn_runcard_tsppts) > 0){
            for ($i=0; $i <count($prodn_runcard_tsppts) ; $i++) {
                for ($j=0; $j <count($prodn_runcard_tsppts[$i]->prod_runcard_station_details) ; $j++) {
                        array_push($tempo_array['runcard_no'], strtoupper($prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no));
                }
            }
        }else{
            return "no ts_ppts runcard details";
        }


        $runcard_no = substr($po_number,0,10);

        $prodn_runcard = ProductionRuncard::with('prod_runcard_station_many_details.prod_runcard_station_material_details','material_process_info')
        ->whereIn('runcard_no', $tempo_array['runcard_no'])
        ->get();


        $imploded = "'".implode("','", $tempo_array['runcard_no'])."'";

        $material_name = DB::connection('mysql')->select(' SELECT DISTINCT(c.material_name) FROM `production_runcards` AS a INNER JOIN `production_runcard_stations2` AS b ON a.id = b.production_runcard_id INNER JOIN `prod_runcard_station_materials` AS c ON b.id = c.production_runcard_station_id WHERE a.runcard_no IN ('.$imploded.')');

        // return response()->json(['jannus' => $material_name]);

        $parts_prep = PartsPrep::where('runcard_po',$runcard_no)
        ->where('wbs_table',2)
        ->get();

        $tempo_array_id = [
            "parts_prep_wbs_kits_issuance_id" => [],
        ];

        if(count($parts_prep) > 0){
            for ($i=0; $i <count($parts_prep); $i++) {
                array_push($tempo_array_id['parts_prep_wbs_kits_issuance_id'], $parts_prep[$i]->wbs_kit_issuance_id);
            }
        }else{
            return "no parts_prep data";
        }

        $sakidashi_issuance = WBSSakidashiIssuance::where('po_no', $po_number)
        // ->where('id',$parts_prep_wbs_kits_issuance_id)
        ->whereIn('id', $tempo_array_id['parts_prep_wbs_kits_issuance_id'])
        ->get();


        $tempo_array_issuance = [
            "issuance_no" => [],
        ];

        if(count($sakidashi_issuance) > 0){
            for ($x=0; $x <count($sakidashi_issuance); $x++) {
                array_push($tempo_array_issuance['issuance_no'], $sakidashi_issuance[$x]->issuance_no);
            }
        }else{
            return "no sakidashi_issuance data";
        }


        $sakidashi_issuance_item = WBSSakidashiIssuanceItem::whereIn('issuance_no', $tempo_array_issuance['issuance_no'])
        ->get();


        $prodn_runcard_array = [
            "id" => [],
            "defect_rework_number" => []
        ];

        if(count($prodn_runcard) > 0){
            for ($n=0; $n <count($prodn_runcard); $n++) {
                array_push($prodn_runcard_array['id'], $prodn_runcard[$n]->id);
                array_push($prodn_runcard_array['defect_rework_number'], $prodn_runcard[$n]->defect_rework_number);
            }
        }else{
            return "no runcard details for operator";
        }



        $operator_details = ProductionRuncardStationOperator::with(['infoHeadCheckedby'])
        ->whereIn('production_runcard_id',$prodn_runcard_array['id'])
        ->get();

        $operator_details = collect($operator_details)->groupBy('production_runcard_id');

        $production_runcard_station = ProductionRuncardStation::with(['process_flow.process_name'])
        ->whereIn('production_runcard_id',$prodn_runcard_array['id'])
        ->get();

        $production_runcard_station_details = collect($production_runcard_station)->groupBy('production_runcard_id');

        $defect_escalation = DefectEscalation::
        with([
            // 'defect_escalation_material_list',
            'rework_station_many_details',
            'rework_station_many_details.defect_materials',
            'rework_details',
            'rework_details.prod_runcard_station_materials'
            ])
        ->where('po_no', $po_number)
        ->get();

        ob_end_clean();
        ob_start();

        return Excel::download(new ExportTraceabilityReport_dynamic($prodn_runcard,$prodn_runcard_tsppts,$material_name,$sakidashi_issuance_item,$dlabel??[],$dlabel_boxes??[],$materialprocess,$operator_details,$defect_escalation,$shipment_date_test,$production_runcard_station_details), 'PATS generated.xlsx');
    }

}
