<?php
namespace App\Exports;

// use App\Model\ShippingInspector;
// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;


use App\Model\DERework;
use App\Model\PartsPrep;
use App\Model\DefectEscalation;

use Illuminate\Support\Facades\DB;
use App\Model\WBSSakidashiIssuance;
use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Sheet;
Use \Maatwebsite\Excel\Sheet;
use App\Model\WBSSakidashiIssuanceItem;
// use Maatwebsite\Excel\Cell\DataType;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
// use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class ExportTraceabilityReport_dynamic implements FromView, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ShippingInspector::all();
    // }
    use Exportable;


    protected $prodn_runcard;
    protected $prodn_runcard_tsppts;
    protected $material_name;
    protected $sakidashi_issuance_item;
    protected $dlabel;
    protected $dlabel_boxes;
    protected $materialprocess;
    protected $operator_details;
    protected $defect_escalation;
    protected $shipment_date_test;
    protected $production_runcard_station_details;
    // protected $prodn_runcard_with_defect;

    // protected $device_name;

    function __construct($prodn_runcard,
    $prodn_runcard_tsppts,
    $material_name,
    $sakidashi_issuance_item,
    $dlabel,
    $dlabel_boxes,
    $materialprocess,
    $operator_details,
    $defect_escalation,
    $shipment_date_test,
    $production_runcard_station_details
    // $prodn_runcard_with_defect
    ){
        $this->prodn_runcard = $prodn_runcard;
        $this->prodn_runcard_tsppts = $prodn_runcard_tsppts;
        $this->material_name = $material_name;
        $this->sakidashi_issuance_item = $sakidashi_issuance_item;
        $this->dlabel = $dlabel;
        $this->dlabel_boxes = $dlabel_boxes;
        $this->materialprocess = $materialprocess;
        $this->operator_details = $operator_details;
        $this->defect_escalation = $defect_escalation;
        $this->shipment_date_test = $shipment_date_test;
        $this->production_runcard_station_details= $production_runcard_station_details;
        // $this->prodn_runcard_with_defect = $prodn_runcard_with_defect;
    }

    public function view(): View
    {
        return view('exports.export_traceability_report', [
            'pats_production_runcard' => $this->prodn_runcard,
            'ppts_production_runcard' => $this->prodn_runcard_tsppts,
            'material_name' => $this->material_name,
            'sakidashi_details' => $this->sakidashi_issuance_item,
            'dlabel' => $this->dlabel,
            'dlabel_boxes' => $this->dlabel_boxes,
            'material_process' => $this->materialprocess,
            'operator_details' => $this->operator_details
        ]);
    }
    public function title(): string{
        return "PATS";
    }

    public function registerEvents(): array
    {

        $prodn_runcard_details = $this->prodn_runcard;
        $prodn_runcard_tsppts_details = $this->prodn_runcard_tsppts;
        $material_name = $this->material_name;
        $sakidashi_issuance_item_data = $this->sakidashi_issuance_item;
        $dlabel_data = $this->dlabel;
        $dlabel_boxes_data = $this->dlabel_boxes;
        $materialprocess_data = $this->materialprocess;
        $operator_details = $this->operator_details;
        $defect_escalation_details = $this->defect_escalation;
        $shipment_date_select = $this->shipment_date_test;

         // $prodn_runcard_with_defect = $this->prodn_runcard_with_defect;

         $arial_font12_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  13,
                'bold'      =>  true,
                // 'color'      =>  'red',
                // 'italic'      =>  true
            )
        );

        $arial_font16_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  16,
                'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $arial_font12 = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  12,
                // 'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $hv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ],
        );

        $hlv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => Color::COLOR_WHITE], // White background
                'wrapText' => true,
            ],
        );

        $hrv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        );
        $styleBorderBottomThin= [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $styleBorderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $hlv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                // 'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );

        $hcv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );


    return [
        AfterSheet::class => function(AfterSheet $event) use(
            $material_name, $prodn_runcard_details, $prodn_runcard_tsppts_details,
            $dlabel_data, $dlabel_boxes_data, $sakidashi_issuance_item_data,
            $operator_details, $materialprocess_data, $styleBorderAll, $hlv_center,$hv_center
        ) {
            // 1. Column Setup
            $lastcol = Coordinate::stringFromColumnIndex(9 + count($material_name));
            foreach (range('A', $lastcol) as $col) {
                $event->sheet->getColumnDimension($col)->setWidth(18);
            }
            // 2. Static Headers
            $event->sheet->getColumnDimension('A')->setWidth(18);
            $event->sheet->getColumnDimension('B')->setWidth(18);
            $event->sheet->getColumnDimension('C')->setWidth(18);
            $event->sheet->getColumnDimension('D')->setWidth(18);
            $event->sheet->getColumnDimension('E')->setWidth(18);
            $event->sheet->getColumnDimension('F')->setWidth(18);
            $event->sheet->getColumnDimension('G')->setWidth(18);
            $event->sheet->getColumnDimension('H')->setWidth(18);
            $event->sheet->getColumnDimension('I')->setWidth(18);
            $event->sheet->getColumnDimension('J')->setWidth(18);
            $event->sheet->getColumnDimension('K')->setWidth(18);
            $event->sheet->getColumnDimension('L')->setWidth(18);
            $event->sheet->getColumnDimension('M')->setWidth(18);
            $event->sheet->getColumnDimension('N')->setWidth(18);
            $event->sheet->getColumnDimension('O')->setWidth(18);
            $event->sheet->getColumnDimension('P')->setWidth(18);
            $event->sheet->getColumnDimension('Q')->setWidth(18);
            $event->sheet->getColumnDimension('R')->setWidth(18);
            $event->sheet->getColumnDimension('S')->setWidth(18);
            $event->sheet->getColumnDimension('T')->setWidth(18);
            $event->sheet->getColumnDimension('U')->setWidth(18);
            $event->sheet->getColumnDimension('V')->setWidth(18);
            $event->sheet->getColumnDimension('W')->setWidth(18);
            $event->sheet->getColumnDimension('X')->setWidth(18);
            $event->sheet->getColumnDimension('Y')->setWidth(18);
            $event->sheet->getColumnDimension('Z')->setWidth(18);


            $event->sheet->getDelegate()->mergeCells('A1:C1');
            $event->sheet->getDelegate()->mergeCells('B2:C2');
            $event->sheet->getDelegate()->mergeCells('B3:C3');
            $event->sheet->getDelegate()->mergeCells('B4:C4');
            $event->sheet->getDelegate()->mergeCells('B5:C5');

            $lastcol = 'W';
            $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(17);
            $event->sheet->getStyle('B1:B6')->getFont()->setBold(true)->setSize(11);
            $event->sheet->getStyle('A7:'.$lastcol.'7')->getFont()->setBold(true)->setSize(11);
            $event->sheet->getDelegate()->getStyle('B1:B6')->applyFromArray($hlv_center);
            $event->sheet->getDelegate()->getStyle('A7:'.$lastcol.'7')->applyFromArray($hv_center);
            $event->sheet->getDelegate()->getStyle('A7:'.$lastcol.'7')->getAlignment()->setWrapText(true);

            $event->sheet->setCellValue('A1',"Product Lot Traceability Report");
            $event->sheet->setCellValue('A2',"PO Number");
            $event->sheet->setCellValue('B2',$prodn_runcard_tsppts_details[0]->po_no);
            $event->sheet->getDelegate()->getStyle('B2')->getNumberFormat()->setFormatCode('000000000000000');
            $event->sheet->setCellValue('A3',"Series Name");
            $event->sheet->setCellValue('B3',$prodn_runcard_tsppts_details[0]->device_name);
            $event->sheet->setCellValue('A4',"Pin Population");
            $event->sheet->setCellValue('B4',$materialprocess_data[0]->pin_popu);
            // $event->sheet->setCellValue('A5',"WW#");
            $event->sheet->setCellValue('A5',"Customer");
            // $event->sheet->setCellValue('D6',$materialprocess_data[0]->type);

            if($materialprocess_data[0]->type == 2 ){
                $event->sheet->setCellValue('B5',"MICRON");

            }else{
                $event->sheet->setCellValue('B5',"");

            }

            $event->sheet->setCellValue('F1',"Revision History");
            $event->sheet->setCellValue('F2',"Rev #");
            $event->sheet->setCellValue('F3',"00");
            $event->sheet->setCellValue('F4',"01");
            $event->sheet->setCellValue('F5',"02");

            $event->sheet->setCellValue('G2',"Date");
            $event->sheet->setCellValue('H2',"Description"); //nmodify

            $event->sheet->setCellValue('A7',"Input Date");
            $event->sheet->setCellValue('B7',"#CT Pair No");
            $event->sheet->setCellValue('C7',"CT Lot #");
            $event->sheet->setCellValue('D7',"CT Reel QTY");
            $event->sheet->setCellValue('E7',"SOCKET QTY");

            //NOTE IF NO SHIPMENT DATE SELECTED, EXPORT RUNCARD DETAILS
            // ELSE EXPORT RUNCARD WITH DLABEL
            if($this->shipment_date_test == ''){
                // echo 'true';
                // exit;
                    // 3. Dynamic Material Headers (Starting at F7)
                    $col_index = 6; // Column F
                    foreach ($material_name as $material) {
                        $cell =Coordinate::stringFromColumnIndex($col_index) . '7';
                        $event->sheet->setCellValue($cell, $material->material_name);
                        $col_index++;
                    }


                    // Define column letters for standard headers after dynamic materials
                    $col_run_no   = Coordinate::stringFromColumnIndex($col_index++);
                    $col_lot      = Coordinate::stringFromColumnIndex($col_index++);
                    // $col_box      =Coordinate::stringFromColumnIndex($col_index++);
                    $col_lotqty   = Coordinate::stringFromColumnIndex($col_index++);
                    // $col_shipdate =Coordinate::stringFromColumnIndex($col_index++);
                    $col_station  = Coordinate::stringFromColumnIndex($col_index++);
                    $col_operator = Coordinate::stringFromColumnIndex($col_index++);
                    $col_ww       = Coordinate::stringFromColumnIndex($col_index++);
                    $col_remarks  = Coordinate::stringFromColumnIndex($col_index++);


                    $event->sheet->setCellValue($col_run_no.'7', "Runcard_no");
                    $event->sheet->setCellValue($col_lot.'7', "Lot #");
                    // $event->sheet->setCellValue($col_box.'7', "Box #");
                    $event->sheet->setCellValue($col_lotqty.'7', "Lot Qty");
                    // $event->sheet->setCellValue($col_shipdate.'7', "Shipment Date");
                    $event->sheet->setCellValue($col_station.'7', "Station");
                    $event->sheet->setCellValue($col_operator.'7', "Operator/s");
                    $event->sheet->setCellValue($col_ww.'7', "Work Week");
                    $event->sheet->setCellValue($col_remarks.'7', "Remarks");

                    $start_row = 8;
                    $production_runcard_station_details = $this->production_runcard_station_details;
                    // 4. MAIN DATA LOOP (Runcard Centric)
                    foreach ($prodn_runcard_tsppts_details as $tsppts) {
                        $input_date = substr($tsppts->created_at, 0, 10);
                        $lot_no = $tsppts->lot_no;

                        foreach ($tsppts->prod_runcard_station_details as $station) {
                            // Find matching boxes for this specific runcard
                            $matching_boxes = collect($dlabel_boxes_data)->where('lot_no', $station->runcard_no);

                            // If no box exists, we still want to print 1 row for the runcard
                            $iterations = $matching_boxes->isEmpty() ? [null] : $matching_boxes;
                            foreach ($iterations as $box) {
                                // Basic Runcard Info
                                $event->sheet->setCellValue('A' . $start_row, $input_date);
                                $event->sheet->setCellValue($col_lot . $start_row, $lot_no);
                                $event->sheet->setCellValue($col_lotqty . $start_row, $station->qty_output);
                                $event->sheet->setCellValue($col_run_no . $start_row, $station->runcard_no);

                                // Traceability / Materials Logic (Optimized)
                                $runcard_main = collect($prodn_runcard_details)->firstWhere('runcard_no', $station->runcard_no);
                                if ($runcard_main) {
                                    $event->sheet->setCellValue($col_ww . $start_row, $runcard_main->work_week);
                                    $event->sheet->setCellValue($col_remarks . $start_row, $runcard_main->remarks);
                                    $event->sheet->setCellValue($col_station . $start_row,$this->station_name($production_runcard_station_details[$runcard_main->id]));
                                    // echo json_encode($this->station_name($production_runcard_station_details[$runcard_main->id]));
                                    $event->sheet->setCellValue($col_operator . $start_row, $this->operator_name($operator_details[$runcard_main->id], $sakidashi_issuance_item_data));
                                    $event->sheet->setCellValue($col_remarks . $start_row, $runcard_main->remarks);
                                    // Fill Material Columns (F to dynamic end)
                                    $m_col_idx = 6;
                                    foreach ($material_name as $m_name) {
                                        $m_cell = Coordinate::stringFromColumnIndex($m_col_idx) . $start_row;
                                        // Logic to find material lot # in station_many_details
                                        foreach ($runcard_main->prod_runcard_station_many_details as $many) {
                                            foreach ($many->prod_runcard_station_material_details as $mat) {
                                                if ($mat->material_name == $m_name->material_name) {
                                                    $event->sheet->setCellValue($m_cell, strtoupper($mat->lot_no));

                                                    // Auto-fill CT specific columns (B, C, D, E) if CT material found
                                                    if (str_contains($mat->material_name, 'CT')) {
                                                        $event->sheet->setCellValue('C'.$start_row, strtoupper($mat->lot_no));
                                                        $event->sheet->setCellValue('B'.$start_row, $this->get_ct_pair_no($sakidashi_issuance_item_data, $mat->lot_no));
                                                        $event->sheet->setCellValue('D'.$start_row, $this->get_pin_papulasyon($sakidashi_issuance_item_data, $mat->lot_no));
                                                        $event->sheet->setCellValue('E'.$start_row, $this->get_socket_qty($sakidashi_issuance_item_data, $mat->lot_no, $materialprocess_data[0]->pin_popu));
                                                    }
                                                }
                                            }
                                        }
                                        $m_col_idx++;
                                    }
                                }
                                // exit();
                                $start_row++;
                            }
                        }
                    }

                    // Apply Styles to the used range
                    $event->sheet->getStyle('A7:'.$lastcol.($start_row-1))->applyFromArray($styleBorderAll);
                    $event->sheet->getStyle('A7:'.$lastcol.($start_row-1))->applyFromArray($hv_center)->getAlignment()->setWrapText(true);
                    // $event->sheet->getStyle('A1:Z100')->applyFromArray($hv_center)->getAlignment()->setWrapText(true);
                    $event->sheet->getStyle('A7:'.$lastcol.'7')->getFont()->setBold(true);
            }else{
                // 67.
                // echo 'false';
                // exit;
                $start_col = 8;
                $test_col = 8;
                $test_col1 = 8;
                $column_start = 'F';
                $start_column = 'F';
                $start_column_start = 'F';
                $try_array = array();
                for ($i=0; $i<count($material_name); $i++) {
                    $event->sheet->setCellValue($column_start.'7', $material_name[$i]->material_name);
                    $column_start++;

                }
                // $test_col1++;

                $column_start1 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Lot #");
                $column_start++;

                $column_start2 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Box #");
                $column_start++;

                $column_start3 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Lot Qty");
                $column_start++;

                $column_start4 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Shipment Qty");
                $column_start++;

                $column_start5 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Shipment Date");
                $column_start++;

                $column_start6 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Operator/s");
                $column_start++;

                $column_start7 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Work Week");
                $column_start++;

                $column_start8 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Remarks");
                $column_start++;

                $column_start9 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Runcard_no");
                $column_start++;

                // $column_start10 = $column_start;
                // $event->sheet->setCellValue($column_start.'7',"Inspection Date");
                // $column_start++;
                // $column_start8 = $column_start;
                // $event->sheet->setCellValue($column_start.'7',"CT Material from defect");

                for ($i=0; $i <count($prodn_runcard_tsppts_details); $i++) {

                    $input_date = $prodn_runcard_tsppts_details[$i]->created_at;
                    $date_input = substr($input_date,0,10);
                    // $box_no = substr($prodn_runcard_tsppts_details[$i]->lot_no,-2);
                    $lot_no = $prodn_runcard_tsppts_details[$i]->lot_no;


                    for ($y=0; $y <count($prodn_runcard_tsppts_details[$i]->prod_runcard_station_details); $y++) {
                        // echo json_encode($prodn_runcard_tsppts_details);
                        // exit();
                        for ($c=0; $c <count($dlabel_data); $c++) {


                            for ($d=0; $d <count($dlabel_boxes_data); $d++) {
                                // if($prodn_runcard_tsppts_details[$m]->runcard_no == $dlabel_boxes_data[$d]->lot_no && $dlabel_boxes_data[$d]->d_label_id == $dlabel_data[$c]->id && $dlabel_date[$c]->shipment_date == $shipment_date_select  ){
                                if($prodn_runcard_tsppts_details[$i]->runcard_no == $dlabel_boxes_data[$d]->lot_no && $dlabel_boxes_data[$d]->d_label_id == $dlabel_data[$c]->id){

                                        $raw_box_no = $dlabel_boxes_data[$d]->package_no;
                                        $box_no = explode("/",$dlabel_boxes_data[$d]->package_no);

                                        $event->sheet->setCellValue($column_start4.$start_col, $dlabel_data[$c]->po_qty);
                                        $event->sheet->setCellValue($column_start5.$start_col, $dlabel_data[$c]->shipment_date);

                                        $event->sheet->setCellValue('A'.$start_col, $date_input);
                                        $event->sheet->setCellValue($column_start1.$start_col, $lot_no);
                                        $event->sheet->setCellValue($column_start2.$start_col, $box_no[0]);
                                        $event->sheet->setCellValue($column_start3.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->qty_output);

                                        $start_column_start = 'F';
                                        // $this->get_materials($material_name,$start_column_start,$start_col,$prodn_runcard_details,$prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);
                                        // echo $eto;
                                        // exit();
                                        for ($m=0; $m<count($material_name); $m++) {
                                            for ($u=0; $u <count($prodn_runcard_details); $u++) {
                                                for ($t=0; $t < count($prodn_runcard_details[$u]->prod_runcard_station_many_details) ; $t++) {
                                                    if(strtoupper($prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no) === strtoupper($prodn_runcard_details[$u]->runcard_no)){
                                                            if(strtoupper($prodn_runcard_details[$u]->defect_rework_number) == 'N/A' || $prodn_runcard_details[$u]->defect_rework_number == null){
                                                                // * OPERATOR NAME & WORKWEEK
                                                                $event->sheet->setCellValue($column_start7.$start_col, $prodn_runcard_details[$u]->work_week);
                                                                $event->sheet->setCellValue($column_start8.$start_col, $prodn_runcard_details[$u]->remarks);

                                                                    $event->sheet->setCellValue($column_start6.$start_col, $this->operator_name($operator_details[$prodn_runcard_details[$u]->id], $sakidashi_issuance_item_data));

                                                                    $event->sheet->setCellValue($column_start9.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);
                                                                    // dd($prodn_runcard_details[$u]->remarks);

                                                                    // $event->sheet->setCellValue($column_start8.$start_col, "without defect");
                                                                    for ($n=0; $n < count($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details); $n++) {

                                                                        if($material_name[$m]->material_name == $prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                            $event->sheet->setCellValue($start_column_start.$start_col, strtoupper($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->lot_no));
                                                                        }

                                                                        if (str_contains($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name,'CT')){
                                                                            $lot_number = $prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->lot_no;
                                                                            $event->sheet->setCellValue('C'.$start_col, StrToUpper($lot_number));

                                                                            $event->sheet->setCellValue('B'.$start_col, $this->get_ct_pair_no($sakidashi_issuance_item_data,$lot_number));
                                                                            $event->sheet->setCellValue('D'.$start_col, $this->get_pin_papulasyon($sakidashi_issuance_item_data,$lot_number));
                                                                            $event->sheet->setCellValue('E'.$start_col, $this->get_socket_qty($sakidashi_issuance_item_data,$lot_number,$materialprocess_data[0]->pin_popu));

                                                                        }
                                                                    }
                                                            }
                                                            else{
                                                                    $event->sheet->setCellValue($start_column_start.$start_col, $this->get_prod_runcard_with_defect($prodn_runcard_details[$u]->po_no, $prodn_runcard_details[$u]->defect_rework_number,$material_name[$m]->material_name));
                                                                    $ct_details = $this->get_ct_with_defect($prodn_runcard_details[$u]->po_no,$prodn_runcard_details[$u]->defect_rework_number,$material_name,$sakidashi_issuance_item_data,$materialprocess_data[0]->pin_popu,$operator_details[$prodn_runcard_details[$u]->id]);

                                                                    if(isset($ct_details['lot_number'])){
                                                                        $event->sheet->setCellValue('C'.$start_col, $ct_details['lot_number']);
                                                                    }

                                                                    if(isset($ct_details['pair_no'])){
                                                                        $event->sheet->setCellValue('B'.$start_col, $ct_details['pair_no']);
                                                                    }

                                                                    if(isset($ct_details['ct_reel_qty'])){
                                                                        $event->sheet->setCellValue('D'.$start_col, $ct_details['ct_reel_qty']);
                                                                    }

                                                                    if(isset($ct_details['socket_qty'])){
                                                                        $event->sheet->setCellValue('E'.$start_col, $ct_details['socket_qty']);
                                                                    }

                                                                        // $event->sheet->setCellValue('E'.$start_col, $ct_details['socket_qty']);
                                                                    //     $event->sheet->setCellValue($column_start9.$start_col, $this->get_defect_runcard_no($prodn_runcard_details[$u]->po_no, $prodn_runcard_details[$u]->defect_rework_number,$material_name[$m]->material_name));

                                                                    // $event->sheet->setCellValue('C'.$start_col, $ct_details);
                                                                    $event->sheet->setCellValue($column_start9.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);

                                                                    // $event->sheet->setCellValue($column_start9.$start_col, $ct_details['runcard_no']);

                                                                    // $event->sheet->setCellValue($column_start8.$start_col, "with defect");

                                                                $event->sheet->setCellValue($column_start6.$start_col, $this->operator_name($operator_details[$prodn_runcard_details[$u]->id], $sakidashi_issuance_item_data));
                                                                $event->sheet->setCellValue($column_start7.$start_col, $prodn_runcard_details[$u]->work_week);
                                                                $event->sheet->setCellValue($column_start8.$start_col, $prodn_runcard_details[$u]->remarks);

                                                            }
                                                        }
                                                    }
                                                }


                                            $start_column_start++;

                                        }

                                        $start_col++;
                                }

                            }
                        }
                    }

                }

                $end_col = $start_col - 1;
                $event->sheet->getDelegate()->getStyle('A7'.':'.$column_start8.'7')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A8'.':'.$column_start8.'8')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A9'.':'.'E'.$end_col)->applyFromArray($hv_center);

                $event->sheet->getDelegate()->getStyle('A8'.':'.$column_start8.$end_col)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A7'.':'.$column_start8.$end_col)->applyFromArray($styleBorderAll);
                $event->sheet->getDelegate()->getStyle($column_start1.'9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle($column_start4.'9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('N8'.':'.$column_start5.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('P8'.':'.$column_start5.$end_col)->applyFromArray($hv_center);
                // 67.
            }
        },
    ];
    }
    public function registerEventsRev1(): array
    {

        $prodn_runcard_details = $this->prodn_runcard;
        $prodn_runcard_tsppts_details = $this->prodn_runcard_tsppts;
        $material_name = $this->material_name;
        $sakidashi_issuance_item_data = $this->sakidashi_issuance_item;
        $dlabel_data = $this->dlabel;
        $dlabel_boxes_data = $this->dlabel_boxes;
        $materialprocess_data = $this->materialprocess;
        $operator_details = $this->operator_details;
        $defect_escalation_details = $this->defect_escalation;
        $shipment_date_select = $this->shipment_date_test;

         // $prodn_runcard_with_defect = $this->prodn_runcard_with_defect;

         $arial_font12_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  13,
                'bold'      =>  true,
                // 'color'      =>  'red',
                // 'italic'      =>  true
            )
        );

        $arial_font16_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  16,
                'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $arial_font12 = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  12,
                // 'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $hv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ],
        );

        $hlv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => Color::COLOR_WHITE], // White background
                'wrapText' => true,
            ],
        );

        $hrv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        );
        $styleBorderBottomThin= [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $styleBorderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $hlv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                // 'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );

        $hcv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );


    return [
        AfterSheet::class => function(AfterSheet $event) use(
            $material_name, $prodn_runcard_details, $prodn_runcard_tsppts_details,
            $dlabel_data, $dlabel_boxes_data, $sakidashi_issuance_item_data,
            $operator_details, $materialprocess_data, $styleBorderAll, $hlv_center,$hv_center
        ) {
            // 1. Column Setup
            $lastcol = Coordinate::stringFromColumnIndex(9 + count($material_name));
            foreach (range('A', $lastcol) as $col) {
                $event->sheet->getColumnDimension($col)->setWidth(18);
            }
            // 2. Static Headers
            $event->sheet->getColumnDimension('A')->setWidth(18);
            $event->sheet->getColumnDimension('B')->setWidth(18);
            $event->sheet->getColumnDimension('C')->setWidth(18);
            $event->sheet->getColumnDimension('D')->setWidth(18);
            $event->sheet->getColumnDimension('E')->setWidth(18);
            $event->sheet->getColumnDimension('F')->setWidth(18);
            $event->sheet->getColumnDimension('G')->setWidth(18);
            $event->sheet->getColumnDimension('H')->setWidth(18);
            $event->sheet->getColumnDimension('I')->setWidth(18);
            $event->sheet->getColumnDimension('J')->setWidth(18);
            $event->sheet->getColumnDimension('K')->setWidth(18);
            $event->sheet->getColumnDimension('L')->setWidth(18);
            $event->sheet->getColumnDimension('M')->setWidth(18);
            $event->sheet->getColumnDimension('N')->setWidth(18);
            $event->sheet->getColumnDimension('O')->setWidth(18);
            $event->sheet->getColumnDimension('P')->setWidth(18);
            $event->sheet->getColumnDimension('Q')->setWidth(18);
            $event->sheet->getColumnDimension('R')->setWidth(18);
            $event->sheet->getColumnDimension('S')->setWidth(18);
            $event->sheet->getColumnDimension('T')->setWidth(18);
            $event->sheet->getColumnDimension('U')->setWidth(18);
            $event->sheet->getColumnDimension('V')->setWidth(18);
            $event->sheet->getColumnDimension('W')->setWidth(18);
            $event->sheet->getColumnDimension('X')->setWidth(18);
            $event->sheet->getColumnDimension('Y')->setWidth(18);
            $event->sheet->getColumnDimension('Z')->setWidth(18);


            $event->sheet->getDelegate()->mergeCells('A1:C1');
            $event->sheet->getDelegate()->mergeCells('B2:C2');
            $event->sheet->getDelegate()->mergeCells('B3:C3');
            $event->sheet->getDelegate()->mergeCells('B4:C4');
            $event->sheet->getDelegate()->mergeCells('B5:C5');

            $lastcol = 'W';
            $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(17);
            $event->sheet->getStyle('B1:B6')->getFont()->setBold(true)->setSize(11);
            $event->sheet->getStyle('A7:'.$lastcol.'7')->getFont()->setBold(true)->setSize(11);
            $event->sheet->getDelegate()->getStyle('B1:B6')->applyFromArray($hlv_center);
            $event->sheet->getDelegate()->getStyle('A7:'.$lastcol.'7')->applyFromArray($hv_center);
            $event->sheet->getDelegate()->getStyle('A7:'.$lastcol.'7')->getAlignment()->setWrapText(true);

            $event->sheet->setCellValue('A1',"Product Lot Traceability Report");
            $event->sheet->setCellValue('A2',"PO Number");
            $event->sheet->setCellValue('B2',$prodn_runcard_tsppts_details[0]->po_no);
            $event->sheet->getDelegate()->getStyle('B2')->getNumberFormat()->setFormatCode('000000000000000');
            $event->sheet->setCellValue('A3',"Series Name");
            $event->sheet->setCellValue('B3',$prodn_runcard_tsppts_details[0]->device_name);
            $event->sheet->setCellValue('A4',"Pin Population");
            $event->sheet->setCellValue('B4',$materialprocess_data[0]->pin_popu);
            // $event->sheet->setCellValue('A5',"WW#");
            $event->sheet->setCellValue('A5',"Customer");
            // $event->sheet->setCellValue('D6',$materialprocess_data[0]->type);

            if($materialprocess_data[0]->type == 2 ){
                $event->sheet->setCellValue('B5',"MICRON");

            }else{
                $event->sheet->setCellValue('B5',"");

            }

            $event->sheet->setCellValue('F1',"Revision History");
            $event->sheet->setCellValue('F2',"Rev #");
            $event->sheet->setCellValue('F3',"00");
            $event->sheet->setCellValue('F4',"01");
            $event->sheet->setCellValue('F5',"02");

            $event->sheet->setCellValue('G2',"Date");
            $event->sheet->setCellValue('H2',"Description"); //nmodify

            $event->sheet->setCellValue('A7',"Input Date");
            $event->sheet->setCellValue('B7',"#CT Pair No");
            $event->sheet->setCellValue('C7',"CT Lot #");
            $event->sheet->setCellValue('D7',"CT Reel QTY");
            $event->sheet->setCellValue('E7',"SOCKET QTY");

            if($this->shipment_date_test == ''){
                // echo 'true';
                // exit;
                    // 3. Dynamic Material Headers (Starting at F7)
                    $col_index = 6; // Column F
                    foreach ($material_name as $material) {
                        $cell =Coordinate::stringFromColumnIndex($col_index) . '7';
                        $event->sheet->setCellValue($cell, $material->material_name);
                        $col_index++;
                    }


                    // Define column letters for standard headers after dynamic materials
                    $col_lot      = Coordinate::stringFromColumnIndex($col_index++);
                    // $col_box      =Coordinate::stringFromColumnIndex($col_index++);
                    $col_lotqty   = Coordinate::stringFromColumnIndex($col_index++);
                    // $col_shipdate =Coordinate::stringFromColumnIndex($col_index++);
                    $col_operator = Coordinate::stringFromColumnIndex($col_index++);
                    $col_station  = Coordinate::stringFromColumnIndex($col_index++);
                    $col_ww       = Coordinate::stringFromColumnIndex($col_index++);
                    $col_remarks  = Coordinate::stringFromColumnIndex($col_index++);
                    $col_run_no   = Coordinate::stringFromColumnIndex($col_index++);


                    $event->sheet->setCellValue($col_lot.'7', "Lot #");
                    // $event->sheet->setCellValue($col_box.'7', "Box #");
                    $event->sheet->setCellValue($col_lotqty.'7', "Lot Qty");
                    // $event->sheet->setCellValue($col_shipdate.'7', "Shipment Date");
                    $event->sheet->setCellValue($col_operator.'7', "Operator/s");
                    $event->sheet->setCellValue($col_station.'7', "Station");
                    $event->sheet->setCellValue($col_ww.'7', "Work Week");
                    $event->sheet->setCellValue($col_remarks.'7', "Remarks");
                    $event->sheet->setCellValue($col_run_no.'7', "Runcard_no");

                    $start_row = 8;
                    $production_runcard_station_details = $this->production_runcard_station_details;
                    // 4. MAIN DATA LOOP (Runcard Centric)
                    foreach ($prodn_runcard_tsppts_details as $tsppts) {
                        $input_date = substr($tsppts->created_at, 0, 10);
                        $lot_no = $tsppts->lot_no;

                        foreach ($tsppts->prod_runcard_station_details as $station) {
                            // Find matching boxes for this specific runcard
                            $matching_boxes = collect($dlabel_boxes_data)->where('lot_no', $station->runcard_no);

                            // If no box exists, we still want to print 1 row for the runcard
                            $iterations = $matching_boxes->isEmpty() ? [null] : $matching_boxes;
                            foreach ($iterations as $box) {
                                // Basic Runcard Info
                                $event->sheet->setCellValue('A' . $start_row, $input_date);
                                $event->sheet->setCellValue($col_lot . $start_row, $lot_no);
                                $event->sheet->setCellValue($col_lotqty . $start_row, $station->qty_output);
                                $event->sheet->setCellValue($col_run_no . $start_row, $station->runcard_no);

                                // Traceability / Materials Logic (Optimized)
                                $runcard_main = collect($prodn_runcard_details)->firstWhere('runcard_no', $station->runcard_no);
                                if ($runcard_main) {
                                    $event->sheet->setCellValue($col_ww . $start_row, $runcard_main->work_week);
                                    $event->sheet->setCellValue($col_remarks . $start_row, $runcard_main->remarks);
                                    $event->sheet->setCellValue($col_station . $start_row,$this->station_name($production_runcard_station_details[$runcard_main->id]));
                                    // echo json_encode($this->station_name($production_runcard_station_details[$runcard_main->id]));
                                    $event->sheet->setCellValue($col_operator . $start_row, $this->operator_name($operator_details[$runcard_main->id], $sakidashi_issuance_item_data));
                                    $event->sheet->setCellValue($col_remarks . $start_row, $runcard_main->remarks);
                                    // Fill Material Columns (F to dynamic end)
                                    $m_col_idx = 6;
                                    foreach ($material_name as $m_name) {
                                        $m_cell = Coordinate::stringFromColumnIndex($m_col_idx) . $start_row;
                                        // Logic to find material lot # in station_many_details
                                        foreach ($runcard_main->prod_runcard_station_many_details as $many) {
                                            foreach ($many->prod_runcard_station_material_details as $mat) {
                                                if ($mat->material_name == $m_name->material_name) {
                                                    $event->sheet->setCellValue($m_cell, strtoupper($mat->lot_no));

                                                    // Auto-fill CT specific columns (B, C, D, E) if CT material found
                                                    if (str_contains($mat->material_name, 'CT')) {
                                                        $event->sheet->setCellValue('C'.$start_row, strtoupper($mat->lot_no));
                                                        $event->sheet->setCellValue('B'.$start_row, $this->get_ct_pair_no($sakidashi_issuance_item_data, $mat->lot_no));
                                                        $event->sheet->setCellValue('D'.$start_row, $this->get_pin_papulasyon($sakidashi_issuance_item_data, $mat->lot_no));
                                                        $event->sheet->setCellValue('E'.$start_row, $this->get_socket_qty($sakidashi_issuance_item_data, $mat->lot_no, $materialprocess_data[0]->pin_popu));
                                                    }
                                                }
                                            }
                                        }
                                        $m_col_idx++;
                                    }
                                }
                                // exit();
                                $start_row++;
                            }
                        }
                    }

                    // Apply Styles to the used range
                    $event->sheet->getStyle('A7:'.$lastcol.($start_row-1))->applyFromArray($styleBorderAll);
                    $event->sheet->getStyle('A7:'.$lastcol.($start_row-1))->applyFromArray($hv_center)->getAlignment()->setWrapText(true);
                    // $event->sheet->getStyle('A1:Z100')->applyFromArray($hv_center)->getAlignment()->setWrapText(true);
                    $event->sheet->getStyle('A7:'.$lastcol.'7')->getFont()->setBold(true);
            }else{
                // 67.
                // echo 'false';
                // exit;
                $start_col = 8;
                $test_col = 8;
                $test_col1 = 8;
                $column_start = 'F';
                $start_column = 'F';
                $start_column_start = 'F';
                $try_array = array();
                for ($i=0; $i<count($material_name); $i++) {
                    $event->sheet->setCellValue($column_start.'7', $material_name[$i]->material_name);
                    $column_start++;

                }
                // $test_col1++;

                $column_start1 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Lot #");
                $column_start++;

                $column_start2 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Box #");
                $column_start++;

                $column_start3 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Lot Qty");
                $column_start++;

                $column_start4 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Shipment Qty");
                $column_start++;

                $column_start5 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Shipment Date");
                $column_start++;

                $column_start6 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Operator/s");
                $column_start++;

                $column_start7 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Work Week");
                $column_start++;

                $column_start8 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Remarks");
                $column_start++;

                $column_start9 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Runcard_no");
                $column_start++;

                // $column_start10 = $column_start;
                // $event->sheet->setCellValue($column_start.'7',"Inspection Date");
                // $column_start++;
                // $column_start8 = $column_start;
                // $event->sheet->setCellValue($column_start.'7',"CT Material from defect");

                for ($i=0; $i <count($prodn_runcard_tsppts_details); $i++) {

                    $input_date = $prodn_runcard_tsppts_details[$i]->created_at;
                    $date_input = substr($input_date,0,10);
                    // $box_no = substr($prodn_runcard_tsppts_details[$i]->lot_no,-2);
                    $lot_no = $prodn_runcard_tsppts_details[$i]->lot_no;


                    for ($y=0; $y <count($prodn_runcard_tsppts_details[$i]->prod_runcard_station_details); $y++) {
                        // echo json_encode($prodn_runcard_tsppts_details);
                        // exit();
                        for ($c=0; $c <count($dlabel_data); $c++) {


                            for ($d=0; $d <count($dlabel_boxes_data); $d++) {
                                // if($prodn_runcard_tsppts_details[$m]->runcard_no == $dlabel_boxes_data[$d]->lot_no && $dlabel_boxes_data[$d]->d_label_id == $dlabel_data[$c]->id && $dlabel_date[$c]->shipment_date == $shipment_date_select  ){
                                if($prodn_runcard_tsppts_details[$i]->runcard_no == $dlabel_boxes_data[$d]->lot_no && $dlabel_boxes_data[$d]->d_label_id == $dlabel_data[$c]->id){

                                        $raw_box_no = $dlabel_boxes_data[$d]->package_no;
                                        $box_no = explode("/",$dlabel_boxes_data[$d]->package_no);

                                        $event->sheet->setCellValue($column_start4.$start_col, $dlabel_data[$c]->po_qty);
                                        $event->sheet->setCellValue($column_start5.$start_col, $dlabel_data[$c]->shipment_date);

                                        $event->sheet->setCellValue('A'.$start_col, $date_input);
                                        $event->sheet->setCellValue($column_start1.$start_col, $lot_no);
                                        $event->sheet->setCellValue($column_start2.$start_col, $box_no[0]);
                                        $event->sheet->setCellValue($column_start3.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->qty_output);

                                        $start_column_start = 'F';
                                        // $this->get_materials($material_name,$start_column_start,$start_col,$prodn_runcard_details,$prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);
                                        // echo $eto;
                                        // exit();
                                        for ($m=0; $m<count($material_name); $m++) {
                                            for ($u=0; $u <count($prodn_runcard_details); $u++) {
                                                for ($t=0; $t < count($prodn_runcard_details[$u]->prod_runcard_station_many_details) ; $t++) {
                                                    if(strtoupper($prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no) === strtoupper($prodn_runcard_details[$u]->runcard_no)){
                                                            if(strtoupper($prodn_runcard_details[$u]->defect_rework_number) == 'N/A' || $prodn_runcard_details[$u]->defect_rework_number == null){
                                                                // * OPERATOR NAME & WORKWEEK
                                                                $event->sheet->setCellValue($column_start7.$start_col, $prodn_runcard_details[$u]->work_week);
                                                                $event->sheet->setCellValue($column_start8.$start_col, $prodn_runcard_details[$u]->remarks);

                                                                    $event->sheet->setCellValue($column_start6.$start_col, $this->operator_name($operator_details[$prodn_runcard_details[$u]->id], $sakidashi_issuance_item_data));

                                                                    $event->sheet->setCellValue($column_start9.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);
                                                                    // dd($prodn_runcard_details[$u]->remarks);

                                                                    // $event->sheet->setCellValue($column_start8.$start_col, "without defect");
                                                                    for ($n=0; $n < count($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details); $n++) {

                                                                        if($material_name[$m]->material_name == $prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                            $event->sheet->setCellValue($start_column_start.$start_col, strtoupper($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->lot_no));
                                                                        }

                                                                        if (str_contains($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name,'CT')){
                                                                            $lot_number = $prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->lot_no;
                                                                            $event->sheet->setCellValue('C'.$start_col, StrToUpper($lot_number));

                                                                            $event->sheet->setCellValue('B'.$start_col, $this->get_ct_pair_no($sakidashi_issuance_item_data,$lot_number));
                                                                            $event->sheet->setCellValue('D'.$start_col, $this->get_pin_papulasyon($sakidashi_issuance_item_data,$lot_number));
                                                                            $event->sheet->setCellValue('E'.$start_col, $this->get_socket_qty($sakidashi_issuance_item_data,$lot_number,$materialprocess_data[0]->pin_popu));

                                                                        }
                                                                    }
                                                            }
                                                            else{
                                                                    $event->sheet->setCellValue($start_column_start.$start_col, $this->get_prod_runcard_with_defect($prodn_runcard_details[$u]->po_no, $prodn_runcard_details[$u]->defect_rework_number,$material_name[$m]->material_name));
                                                                    $ct_details = $this->get_ct_with_defect($prodn_runcard_details[$u]->po_no,$prodn_runcard_details[$u]->defect_rework_number,$material_name,$sakidashi_issuance_item_data,$materialprocess_data[0]->pin_popu,$operator_details[$prodn_runcard_details[$u]->id]);

                                                                    if(isset($ct_details['lot_number'])){
                                                                        $event->sheet->setCellValue('C'.$start_col, $ct_details['lot_number']);
                                                                    }

                                                                    if(isset($ct_details['pair_no'])){
                                                                        $event->sheet->setCellValue('B'.$start_col, $ct_details['pair_no']);
                                                                    }

                                                                    if(isset($ct_details['ct_reel_qty'])){
                                                                        $event->sheet->setCellValue('D'.$start_col, $ct_details['ct_reel_qty']);
                                                                    }

                                                                    if(isset($ct_details['socket_qty'])){
                                                                        $event->sheet->setCellValue('E'.$start_col, $ct_details['socket_qty']);
                                                                    }

                                                                        // $event->sheet->setCellValue('E'.$start_col, $ct_details['socket_qty']);
                                                                    //     $event->sheet->setCellValue($column_start9.$start_col, $this->get_defect_runcard_no($prodn_runcard_details[$u]->po_no, $prodn_runcard_details[$u]->defect_rework_number,$material_name[$m]->material_name));

                                                                    // $event->sheet->setCellValue('C'.$start_col, $ct_details);
                                                                    $event->sheet->setCellValue($column_start9.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);

                                                                    // $event->sheet->setCellValue($column_start9.$start_col, $ct_details['runcard_no']);

                                                                    // $event->sheet->setCellValue($column_start8.$start_col, "with defect");

                                                                $event->sheet->setCellValue($column_start6.$start_col, $this->operator_name($operator_details[$prodn_runcard_details[$u]->id], $sakidashi_issuance_item_data));
                                                                $event->sheet->setCellValue($column_start7.$start_col, $prodn_runcard_details[$u]->work_week);
                                                                $event->sheet->setCellValue($column_start8.$start_col, $prodn_runcard_details[$u]->remarks);

                                                            }
                                                        }
                                                    }
                                                }


                                            $start_column_start++;

                                        }

                                        $start_col++;
                                }

                            }
                        }
                    }

                }

                $end_col = $start_col - 1;
                $event->sheet->getDelegate()->getStyle('A7'.':'.$column_start8.'7')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A8'.':'.$column_start8.'8')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A9'.':'.'E'.$end_col)->applyFromArray($hv_center);

                $event->sheet->getDelegate()->getStyle('A8'.':'.$column_start8.$end_col)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A7'.':'.$column_start8.$end_col)->applyFromArray($styleBorderAll);
                $event->sheet->getDelegate()->getStyle($column_start1.'9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle($column_start4.'9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('N8'.':'.$column_start5.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('P8'.':'.$column_start5.$end_col)->applyFromArray($hv_center);
                // 67.
            }
        },
    ];
    }
    public function registerEventsV1WithDLabel(): array
    {
        $prodn_runcard_details = $this->prodn_runcard;
        $prodn_runcard_tsppts_details = $this->prodn_runcard_tsppts;
        $material_name = $this->material_name;
        $sakidashi_issuance_item_data = $this->sakidashi_issuance_item;
        $dlabel_data = $this->dlabel;
        $dlabel_boxes_data = $this->dlabel_boxes;
        $materialprocess_data = $this->materialprocess;
        $operator_details = $this->operator_details;
        $defect_escalation_details = $this->defect_escalation;
        $shipment_date_select = $this->shipment_date_test;
        // $prodn_runcard_with_defect = $this->prodn_runcard_with_defect;

        $arial_font12_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  13,
                'bold'      =>  true,
                // 'color'      =>  'red',
                // 'italic'      =>  true
            )
        );

        $arial_font16_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  16,
                'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $arial_font12 = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  12,
                // 'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $hv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ]
        );

        $hlv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ]
        );

        $hrv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        );
        $styleBorderBottomThin= [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $styleBorderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $hlv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                // 'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );

        $hcv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );



        return [
            AfterSheet::class => function(AfterSheet $event) use(
                $arial_font12_bold,
                $hv_center,
                $hlv_center,
                $hrv_center,
                $styleBorderBottomThin,
                $styleBorderAll,
                $hlv_top,
                $hcv_top,
                $arial_font16_bold,
                $arial_font12,
                $prodn_runcard_details,
                $prodn_runcard_tsppts_details,
                $material_name,
                $sakidashi_issuance_item_data,
                $dlabel_data,
                $dlabel_boxes_data,
                $materialprocess_data,
                $operator_details,
                $defect_escalation_details,
                $shipment_date_select
            ) {



                // dd($shipment_date_select);
                $event->sheet->getColumnDimension('A')->setWidth(18);
                $event->sheet->getColumnDimension('B')->setWidth(18);
                $event->sheet->getColumnDimension('C')->setWidth(18);
                $event->sheet->getColumnDimension('D')->setWidth(18);
                $event->sheet->getColumnDimension('E')->setWidth(18);
                $event->sheet->getColumnDimension('F')->setWidth(18);
                $event->sheet->getColumnDimension('G')->setWidth(18);
                $event->sheet->getColumnDimension('H')->setWidth(18);
                $event->sheet->getColumnDimension('I')->setWidth(18);
                $event->sheet->getColumnDimension('J')->setWidth(18);
                $event->sheet->getColumnDimension('K')->setWidth(18);
                $event->sheet->getColumnDimension('L')->setWidth(18);
                $event->sheet->getColumnDimension('M')->setWidth(18);
                $event->sheet->getColumnDimension('N')->setWidth(18);
                $event->sheet->getColumnDimension('O')->setWidth(18);
                $event->sheet->getColumnDimension('P')->setWidth(18);
                $event->sheet->getColumnDimension('Q')->setWidth(18);
                $event->sheet->getColumnDimension('R')->setWidth(18);
                $event->sheet->getColumnDimension('S')->setWidth(18);
                $event->sheet->getColumnDimension('T')->setWidth(18);
                $event->sheet->getColumnDimension('U')->setWidth(18);
                $event->sheet->getColumnDimension('V')->setWidth(18);
                $event->sheet->getColumnDimension('W')->setWidth(18);
                $event->sheet->getColumnDimension('X')->setWidth(18);
                $event->sheet->getColumnDimension('Y')->setWidth(18);
                $event->sheet->getColumnDimension('Z')->setWidth(18);


                $event->sheet->getDelegate()->mergeCells('A1:C1');
                $event->sheet->getDelegate()->mergeCells('B2:C2');
                $event->sheet->getDelegate()->mergeCells('B3:C3');
                $event->sheet->getDelegate()->mergeCells('B4:C4');
                $event->sheet->getDelegate()->mergeCells('B5:C5');
                $lastcol = 'Z';

                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(17);
                $event->sheet->getStyle('B1:B6')->getFont()->setBold(true)->setSize(11);
                $event->sheet->getStyle('A7:'.$lastcol.'7')->getFont()->setBold(true)->setSize(11);
                $event->sheet->getDelegate()->getStyle('B1:B6')->applyFromArray($hlv_center);
                $event->sheet->getDelegate()->getStyle('A7:'.$lastcol.'7')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A7:'.$lastcol.'7')->getAlignment()->setWrapText(true);

                $event->sheet->setCellValue('A1',"Product Lot Traceability Report");
                $event->sheet->setCellValue('A2',"PO Number");
                $event->sheet->setCellValue('B2',$prodn_runcard_tsppts_details[0]->po_no);
                $event->sheet->getDelegate()->getStyle('B2')->getNumberFormat()->setFormatCode('000000000000000');
                $event->sheet->setCellValue('A3',"Series Name");
                $event->sheet->setCellValue('B3',$prodn_runcard_tsppts_details[0]->device_name);
                $event->sheet->setCellValue('A4',"Pin Population");
                $event->sheet->setCellValue('B4',$materialprocess_data[0]->pin_popu);
                // $event->sheet->setCellValue('A5',"WW#");
                $event->sheet->setCellValue('A5',"Customer");
                // $event->sheet->setCellValue('D6',$materialprocess_data[0]->type);

                if($materialprocess_data[0]->type == 2 ){
                    $event->sheet->setCellValue('B5',"MICRON");

                }else{
                    $event->sheet->setCellValue('B5',"");

                }

                $event->sheet->setCellValue('F1',"Revision History");
                $event->sheet->setCellValue('F2',"Rev #");
                $event->sheet->setCellValue('F3',"00");
                $event->sheet->setCellValue('F4',"01");
                $event->sheet->setCellValue('F5',"02");

                $event->sheet->setCellValue('G2',"Date");
                $event->sheet->setCellValue('H2',"Description");

                $event->sheet->setCellValue('A7',"Input Date");
                $event->sheet->setCellValue('B7',"#CT Pair No");
                $event->sheet->setCellValue('C7',"CT Lot #");
                $event->sheet->setCellValue('D7',"CT Reel QTY");
                $event->sheet->setCellValue('E7',"SOCKET QTY");

                $start_col = 8;
                $test_col = 8;
                $test_col1 = 8;
                $column_start = 'F';
                $start_column = 'F';
                $start_column_start = 'F';
                $try_array = array();
                for ($i=0; $i<count($material_name); $i++) {
                    $event->sheet->setCellValue($column_start.'7', $material_name[$i]->material_name);
                    $column_start++;

                }
                // $test_col1++;

                $column_start1 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Lot #");
                $column_start++;

                $column_start2 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Box #");
                $column_start++;

                $column_start3 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Lot Qty");
                $column_start++;

                $column_start4 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Shipment Qty");
                $column_start++;

                $column_start5 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Shipment Date");
                $column_start++;

                $column_start6 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Operator/s");
                $column_start++;

                $column_start7 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Work Week");
                $column_start++;

                $column_start8 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Remarks");
                $column_start++;

                $column_start9 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Runcard_no");
                $column_start++;
                // $column_start8 = $column_start;
                // $event->sheet->setCellValue($column_start.'7',"CT Material from defect");

                for ($i=0; $i <count($prodn_runcard_tsppts_details); $i++) {

                    $input_date = $prodn_runcard_tsppts_details[$i]->created_at;
                    $date_input = substr($input_date,0,10);
                    // $box_no = substr($prodn_runcard_tsppts_details[$i]->lot_no,-2);
                    $lot_no = $prodn_runcard_tsppts_details[$i]->lot_no;


                    for ($y=0; $y <count($prodn_runcard_tsppts_details[$i]->prod_runcard_station_details); $y++) {
                        // echo json_encode($prodn_runcard_tsppts_details);
                        // exit();
                        for ($c=0; $c <count($dlabel_data); $c++) {


                            for ($d=0; $d <count($dlabel_boxes_data); $d++) {
                                // if($prodn_runcard_tsppts_details[$m]->runcard_no == $dlabel_boxes_data[$d]->lot_no && $dlabel_boxes_data[$d]->d_label_id == $dlabel_data[$c]->id && $dlabel_date[$c]->shipment_date == $shipment_date_select  ){
                                if($prodn_runcard_tsppts_details[$i]->runcard_no == $dlabel_boxes_data[$d]->lot_no && $dlabel_boxes_data[$d]->d_label_id == $dlabel_data[$c]->id){

                                        $raw_box_no = $dlabel_boxes_data[$d]->package_no;
                                        $box_no = explode("/",$dlabel_boxes_data[$d]->package_no);

                                        $event->sheet->setCellValue($column_start4.$start_col, $dlabel_data[$c]->po_qty);
                                        $event->sheet->setCellValue($column_start5.$start_col, $dlabel_data[$c]->shipment_date);

                                        $event->sheet->setCellValue('A'.$start_col, $date_input);
                                        $event->sheet->setCellValue($column_start1.$start_col, $lot_no);
                                        $event->sheet->setCellValue($column_start2.$start_col, $box_no[0]);
                                        $event->sheet->setCellValue($column_start3.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->qty_output);

                                        $start_column_start = 'F';
                                        // $this->get_materials($material_name,$start_column_start,$start_col,$prodn_runcard_details,$prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);
                                        // echo $eto;
                                        // exit();
                                        for ($m=0; $m<count($material_name); $m++) {
                                            for ($u=0; $u <count($prodn_runcard_details); $u++) {
                                                for ($t=0; $t < count($prodn_runcard_details[$u]->prod_runcard_station_many_details) ; $t++) {
                                                    if(strtoupper($prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no) === strtoupper($prodn_runcard_details[$u]->runcard_no)){
                                                            if(strtoupper($prodn_runcard_details[$u]->defect_rework_number) == 'N/A' || $prodn_runcard_details[$u]->defect_rework_number == null){
                                                                // * OPERATOR NAME & WORKWEEK
                                                                $event->sheet->setCellValue($column_start7.$start_col, $prodn_runcard_details[$u]->work_week);
                                                                $event->sheet->setCellValue($column_start8.$start_col, $prodn_runcard_details[$u]->remarks);

                                                                    $event->sheet->setCellValue($column_start6.$start_col, $this->operator_name($operator_details[$prodn_runcard_details[$u]->id], $sakidashi_issuance_item_data));

                                                                    $event->sheet->setCellValue($column_start9.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);
                                                                    // dd($prodn_runcard_details[$u]->remarks);

                                                                    // $event->sheet->setCellValue($column_start8.$start_col, "without defect");
                                                                    for ($n=0; $n < count($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details); $n++) {

                                                                        if($material_name[$m]->material_name == $prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                            $event->sheet->setCellValue($start_column_start.$start_col, strtoupper($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->lot_no));
                                                                        }

                                                                        if (str_contains($prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name,'CT')){
                                                                            $lot_number = $prodn_runcard_details[$u]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->lot_no;
                                                                            $event->sheet->setCellValue('C'.$start_col, StrToUpper($lot_number));

                                                                            $event->sheet->setCellValue('B'.$start_col, $this->get_ct_pair_no($sakidashi_issuance_item_data,$lot_number));
                                                                            $event->sheet->setCellValue('D'.$start_col, $this->get_pin_papulasyon($sakidashi_issuance_item_data,$lot_number));
                                                                            $event->sheet->setCellValue('E'.$start_col, $this->get_socket_qty($sakidashi_issuance_item_data,$lot_number,$materialprocess_data[0]->pin_popu));

                                                                        }
                                                                    }
                                                            }
                                                            else{
                                                                    $event->sheet->setCellValue($start_column_start.$start_col, $this->get_prod_runcard_with_defect($prodn_runcard_details[$u]->po_no, $prodn_runcard_details[$u]->defect_rework_number,$material_name[$m]->material_name));
                                                                    $ct_details = $this->get_ct_with_defect($prodn_runcard_details[$u]->po_no,$prodn_runcard_details[$u]->defect_rework_number,$material_name,$sakidashi_issuance_item_data,$materialprocess_data[0]->pin_popu,$operator_details[$prodn_runcard_details[$u]->id]);

                                                                    if(isset($ct_details['lot_number'])){
                                                                        $event->sheet->setCellValue('C'.$start_col, $ct_details['lot_number']);
                                                                    }

                                                                    if(isset($ct_details['pair_no'])){
                                                                        $event->sheet->setCellValue('B'.$start_col, $ct_details['pair_no']);
                                                                    }

                                                                    if(isset($ct_details['ct_reel_qty'])){
                                                                        $event->sheet->setCellValue('D'.$start_col, $ct_details['ct_reel_qty']);
                                                                    }

                                                                    if(isset($ct_details['socket_qty'])){
                                                                        $event->sheet->setCellValue('E'.$start_col, $ct_details['socket_qty']);
                                                                    }

                                                                        // $event->sheet->setCellValue('E'.$start_col, $ct_details['socket_qty']);
                                                                    //     $event->sheet->setCellValue($column_start9.$start_col, $this->get_defect_runcard_no($prodn_runcard_details[$u]->po_no, $prodn_runcard_details[$u]->defect_rework_number,$material_name[$m]->material_name));

                                                                    // $event->sheet->setCellValue('C'.$start_col, $ct_details);
                                                                    $event->sheet->setCellValue($column_start9.$start_col, $prodn_runcard_tsppts_details[$i]->prod_runcard_station_details[$y]->runcard_no);

                                                                    // $event->sheet->setCellValue($column_start9.$start_col, $ct_details['runcard_no']);

                                                                    // $event->sheet->setCellValue($column_start8.$start_col, "with defect");

                                                                $event->sheet->setCellValue($column_start6.$start_col, $this->operator_name($operator_details[$prodn_runcard_details[$u]->id], $sakidashi_issuance_item_data));
                                                                $event->sheet->setCellValue($column_start7.$start_col, $prodn_runcard_details[$u]->work_week);
                                                                $event->sheet->setCellValue($column_start8.$start_col, $prodn_runcard_details[$u]->remarks);

                                                            }
                                                        }
                                                    }
                                                }


                                            $start_column_start++;

                                        }

                                        $start_col++;
                                }

                            }
                        }
                    }

                }

                $end_col = $start_col - 1;
                $event->sheet->getDelegate()->getStyle('A7'.':'.$column_start8.'7')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A8'.':'.$column_start8.'8')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A9'.':'.'E'.$end_col)->applyFromArray($hv_center);

                $event->sheet->getDelegate()->getStyle('A8'.':'.$column_start8.$end_col)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A7'.':'.$column_start8.$end_col)->applyFromArray($styleBorderAll);
                $event->sheet->getDelegate()->getStyle($column_start1.'9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle($column_start4.'9'.':'.$column_start8.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('N8'.':'.$column_start5.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('P8'.':'.$column_start5.$end_col)->applyFromArray($hv_center);

            },
        ];
    }
    public function get_ct_pair_no($sakidashi_issuance_item_data, $lot_number){

        for ($p=0; $p <count($sakidashi_issuance_item_data); $p++) {
            // $test .= $sakidashi_issuance_item_data[$p]->lot_no."<br>";
            if($sakidashi_issuance_item_data[$p]->lot_no == StrToUpper($lot_number)){
                return $sakidashi_issuance_item_data[$p]->pair_no;
            }
        }
    }

    public function get_pin_papulasyon($sakidashi_issuance_item_data, $lot_number){
        for ($p=0; $p <count($sakidashi_issuance_item_data); $p++) {
            // $test .= $sakidashi_issuance_item_data[$p]->lot_no."<br>";
            if($sakidashi_issuance_item_data[$p]->lot_no == StrToUpper($lot_number)){
                return $sakidashi_issuance_item_data[$p]->required_qty;
            }
        }
    }

    public function get_socket_qty($sakidashi_issuance_item_data,$lot_number,$materialprocess_data_pin_popu){
        for ($p=0; $p <count($sakidashi_issuance_item_data); $p++) {
            // $test .= $sakidashi_issuance_item_data[$p]->lot_no."<br>";
            if($sakidashi_issuance_item_data[$p]->lot_no == StrToUpper($lot_number)){
                if ($materialprocess_data_pin_popu != NULL){
                    return $sakidashi_issuance_item_data[$p]->required_qty / $materialprocess_data_pin_popu;

                }else{
                    return $sakidashi_issuance_item_data[$p]->required_qty;

                }
            }
        }
    }

    public function operator_name($operator_details, $sakidashi_issuance_item_data){
        $operator_names = "";
        for ($p=0; $p <count($sakidashi_issuance_item_data); $p++) {
            // $test .= $sakidashi_issuance_item_data[$p]->lot_no."<br>";
            for ($b=0; $b <count($operator_details); $b++) {
                $operator_names .= $operator_details[$b]->infoHeadCheckedby->name . PHP_EOL;
            }
            return $operator_names;

        }

    }
    public function station_name($arr_station_details){
        $station_details = "";
        // foreach ($arr_station_details as $key => $value_station_details) {
        //     $station_details .= $value_station_details->process_flow->process_name->name;
        //     return $station_details;
        // }
        // foreach ($arr_station_details as $key => $value_station_details) {
        //     $station_details .= $value_station_details;
        //     return $station_details;
        // }
        // foreach ($arr_station_details as $key => $value_station_details) {
        //     $station_details .= $value_station_details;
        // }
        foreach ($arr_station_details as $key => $value_station_details) {
            $station_details .= $value_station_details->process_flow->process_name->name;
        }
        return $station_details;
    }


    public function get_prod_runcard_with_defect($po_number,$runcard_no_from_pats,$material_name){
        $defect_escalation = DefectEscalation::
        with([
            // 'defect_escalation_material_list',
            'rework_station_many_details',
            'rework_station_many_details.defect_materials',
            'rework_details',
            'rework_details.prod_runcard_station_materials'
            ])
        ->where('po_no', $po_number)
        ->where('defect_escalation_no',$runcard_no_from_pats) // commented 10/9/23
        // ->where('defect_escalation_material_list.defect_escalation_id', $runcard_from_pats)
        ->first();

        // return $defect_escalation;
            // DEFECT OLD
        if(count($defect_escalation->rework_station_many_details[0]->defect_materials) > 0){
            for ($i=0; $i <count($defect_escalation->rework_station_many_details[0]->defect_materials); $i++) {

                if($defect_escalation->rework_station_many_details[0]->defect_materials[$i]->material == $material_name){
                    // return 'with new material defect '. $defect_escalation->rework_station_many_details[0]->defect_materials[$i]->lot_no;
                    // return 'with new material defect '.$material_name;
                    return $defect_escalation->rework_station_many_details[0]->defect_materials[$i]->lot_no;

                }else{
                    for ($x=0; $x <count($defect_escalation->rework_details[0]->prod_runcard_station_materials); $x++) {
                        if($defect_escalation->rework_details[0]->prod_runcard_station_materials[$x]->material_name == $material_name){
                            // return 'from orig '. $defect_escalation->rework_details[0]->prod_runcard_station_materials[$x]->lot_no;
                            // return 'from orig '.$material_name;
                            return $defect_escalation->rework_details[0]->prod_runcard_station_materials[$x]->lot_no;
                        }
                    }
                }
            }
        }
        else{
            for ($i=0; $i <count($defect_escalation->rework_details[0]->prod_runcard_station_materials); $i++) {
                if($defect_escalation->rework_details[0]->prod_runcard_station_materials[$i]->material_name == $material_name){
                    // return 'from orig1 '.$defect_escalation->rework_details[0]->prod_runcard_station_materials[$i]->lot_no;
                    // return 'from orig1 '. $material_name;
                    return $defect_escalation->rework_details[0]->prod_runcard_station_materials[$i]->lot_no;
                }
            }
        }

    }

    public function get_defect_runcard_no($po_number,$runcard_no_from_pats,$material_name){
        $defect_escalation = DefectEscalation::
        with([
            // 'defect_escalation_material_list',
            'rework_station_many_details',
            'rework_station_many_details.defect_materials',
            'rework_details',
            'rework_details.prod_runcard_station_materials'
            ])
        ->where('po_no', $po_number)
        ->where('defect_escalation_no',$runcard_no_from_pats) // commented 10/9/23
        // ->where('defect_escalation_material_list.defect_escalation_id', $runcard_from_pats)
        ->get();

        for ($i=0; $i < count($defect_escalation); $i++) {
            return $defect_escalation[$i]->runcard_no;
        }

        // return $defect_escalation[$];
    }

    public function get_ct_with_defect($po_number,$runcard_no_from_pats,$material_name, $sakidashi_issuance_item_data,$materialprocess_data_pin_popu,$operator_details){

        $ct_array = array();
        if(isset($runcard_no_from_pats)){
            $defect_escalation = DefectEscalation::
        with([
            // 'defect_escalation_material_list',
            'rework_station_many_details',
            'rework_station_many_details.defect_materials',
            'rework_details',
            'rework_details.prod_runcard_station_materials'
            ])
        ->where('po_no', $po_number)
        ->where('defect_escalation_no',$runcard_no_from_pats) // commented 10/9/23
        // ->where('defect_escalation_material_list.defect_escalation_id', $runcard_from_pats)
        ->first();

        if(count($defect_escalation->rework_station_many_details[0]->defect_materials) > 0){
            for ($i=0; $i <count($material_name); $i++){
                for ($j=0; $j < count($defect_escalation->rework_station_many_details[0]->defect_materials); $j++) {
                    if(str_contains($material_name[$i]->material_name, 'CT')){
                        if($defect_escalation->rework_station_many_details[0]->defect_materials[$j]->material == $material_name[$i]->material_name){
                            // return 'with new CT material defect '.$defect_escalation->rework_station_many_details[0]->defect_materials[$j]->lot_no;
                            // return 'with new CT material defect '.$defect_escalation->rework_station_many_details[0]->defect_materials[$j]->material;
                            $ct_array['lot_number'] = $defect_escalation->rework_station_many_details[0]->defect_materials[$j]->lot_no;
                        }
                        else{
                            for ($k=0; $k <count($defect_escalation->rework_details[0]->prod_runcard_station_materials); $k++) {
                                if(str_contains($defect_escalation->rework_details[0]->prod_runcard_station_materials[$k]->material_name, 'CT')){
                                    // return'from orig with non CT material defect '. $defect_escalation->rework_details[0]->prod_runcard_station_materials[$k]->lot_no;
                                    // return'from orig with non CT material defect '. $defect_escalation->rework_details[0]->prod_runcard_station_materials[$k]->material_name;
                                    $ct_array['lot_number'] = $defect_escalation->rework_details[0]->prod_runcard_station_materials[$k]->lot_no;

                                }
                            }
                        }
                    }
                }

            }
        }
        else{
            for ($i=0; $i <count($material_name); $i++){
                for ($k=0; $k <count($defect_escalation->rework_details[0]->prod_runcard_station_materials); $k++) {
                    if($defect_escalation->rework_details[0]->prod_runcard_station_materials[$k]->material_name == $material_name[$i]->material_name){
                        if(str_contains($material_name[$i]->material_name, 'CT')){

                            $ct_array['lot_number'] = $defect_escalation->rework_details[0]->prod_runcard_station_materials[$k]->lot_no;
                            // $ct_array['runcard_no'] = 'asd';
                            // return'from orig '. $defect_escalation->rework_details[0]->prod_runcard_station_materials[$k]->lot_no;
                            // return'from orig '. $defect_escalation->rework_details[0]->prod_runcard_station_materials[$k]->material_name;
                        }

                    }
                }
            }

        }

        if(isset($ct_array['lot_number'])){
            for ($p=0; $p <count($sakidashi_issuance_item_data); $p++) {

                if ($sakidashi_issuance_item_data[$p]->lot_no == StrToUpper($ct_array['lot_number'])) {
                    $ct_array['pair_no'] = $sakidashi_issuance_item_data[$p]->pair_no;
                    $ct_array['ct_reel_qty'] = $sakidashi_issuance_item_data[$p]->required_qty;
                }

                if($sakidashi_issuance_item_data[$p]->lot_no == StrToUpper($ct_array['lot_number'])){
                    if ($materialprocess_data_pin_popu != NULL){
                        $ct_array['socket_qty'] = $sakidashi_issuance_item_data[$p]->required_qty / $materialprocess_data_pin_popu;

                    }else{
                        $ct_array['socket_qty'] = $sakidashi_issuance_item_data[$p]->required_qty;
                    }
                }


            }
        }





    // return $ct_array['lot_number'];
            return $ct_array;
        }


        // return $defect_escalation;



    }
}


