<?php

namespace App\Http\Controllers;

use App\Exports\ExportTraceability;
use App\Exports\ExportTraceabilityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

use App\Model\MaterialIssuanceSubSystem;//tbl_wbs_material_kitting
use App\Model\WBSKitIssuance;//tbl_wbs_material_kitting
use App\Model\ProductionRuncardTsPpts;
use App\Model\ProductionRuncardStationTsPpts;
use App\Model\ProductionRuncard;
use App\Model\ProductionRuncardStationMaterial;

// use App\Model\ProdPrelimInspection;

class ExportTraceabilityReportControllerx extends Controller{
    public function export_traceability_report_data(Request $request,$po_number)
    {

        $prodn_runcard_tsppts = ProductionRuncardTsPpts::with(['prod_runcard_station_details'])
        ->where('po_no', $po_number)
        ->get();

        // return $prodn_runcard_tsppts;

        // $dlabel_tsppts =

        $tempo_array = [
            "runcard_no" => [],
            "lot_no" => [],
        ];
        for ($i=0; $i <count($prodn_runcard_tsppts) ; $i++) {
            // array_push($tempo_array, $prodn_runcard_tsppts[$i]->prod_runcard_station_details);
            for ($j=0; $j <count($prodn_runcard_tsppts[$i]->prod_runcard_station_details) ; $j++) {

                // if($prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no == "es2-205"){
                    // array_push($tempo_array, $prodnasdasdasd_runcard_tsppts[$i]->prod_runcard_station_details[$j]->production_runcard_id);
                    array_push($tempo_array['lot_no'], $prodn_runcard_tsppts[$i]->lot_no);
                    array_push($tempo_array['runcard_no'], $prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no);
                    // $tempo_array['runcard_no'] = $prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no;
                    // $tempo_array['lot_no'] = $prodn_runcard_tsppts[$i]->lot_no;
                // }
            }
        }

        // return($tempo_array);
        // return ;

        $prodn_runcard = ProductionRuncard::with('prod_runcard_station_many_details.prod_runcard_station_material_details')->whereIn('runcard_no', $tempo_array['runcard_no'])
        // ->whereIn('status', 1)
        // ->where('status',1)
        // ->distinct()
        ->get();

        $get_lot_no = collect($prodn_runcard);

        // $get_lot_no->duplicates('prod_runcard_station_many_details.prod_runcard_station_material_details');

        // return $get_lot_no;

        ob_end_clean();
        ob_start();
        return Excel::download(new ExportTraceability($prodn_runcard,$prodn_runcard_tsppts), 'PATS generated.xlsx');

    }

    public function export_traceability_report(Request $request,$po_number)
    {

        $prodn_runcard_tsppts = ProductionRuncardTsPpts::with(['prod_runcard_station_details'])
        ->where('po_no', $po_number)
        ->get();

        // return $prodn_runcard_tsppts;

        // $dlabel_tsppts =

        $tempo_array = [
            "runcard_no" => [],
            "lot_no" => [],
        ];
        for ($i=0; $i <count($prodn_runcard_tsppts) ; $i++) {
            // array_push($tempo_array, $prodn_runcard_tsppts[$i]->prod_runcard_station_details);
            for ($j=0; $j <count($prodn_runcard_tsppts[$i]->prod_runcard_station_details) ; $j++) {

                // if($prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no == "es2-205"){
                    // array_push($tempo_array, $prodnasdasdasd_runcard_tsppts[$i]->prod_runcard_station_details[$j]->production_runcard_id);
                    array_push($tempo_array['lot_no'], $prodn_runcard_tsppts[$i]->lot_no);
                    array_push($tempo_array['runcard_no'], $prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no);
                    // $tempo_array['runcard_no'] = $prodn_runcard_tsppts[$i]->prod_runcard_station_details[$j]->runcard_no;
                    // $tempo_array['lot_no'] = $prodn_runcard_tsppts[$i]->lot_no;
                // }
            }
        }

        // return($tempo_array);
        // return ;

        // $prodn_runcard = ProductionRuncard::with('prod_runcard_station_many_details.prod_runcard_station_material_details')->whereIn('runcard_no', $tempo_array['runcard_no'])
        // // ->whereIn('status', 1)
        // // ->where('status',1)
        // // ->distinct()
        // ->get();

        $prodn_runcard = ProductionRuncard::with('prod_runcard_station_many_details.prod_runcard_station_material_details')->whereIn('runcard_no', $tempo_array['runcard_no'])
        ->get();

        $imploded = "'".implode("','", $tempo_array['runcard_no'])."'";

        $material_name = DB::connection('mysql')->select(' SELECT DISTINCT(c.material_name) FROM `production_runcards` AS a INNER JOIN `production_runcard_stations2` AS b ON a.id = b.production_runcard_id INNER JOIN `prod_runcard_station_materials` AS c ON b.id = c.production_runcard_station_id WHERE a.runcard_no IN ('.$imploded.')');


        // return $prodn_runcard;

        ob_end_clean();
        ob_start();
        return Excel::download(new ExportTraceabilityReportx($prodn_runcard,$prodn_runcard_tsppts,$material_name), 'PATS generated.xlsx');

    }
}
