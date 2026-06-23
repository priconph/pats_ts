<?php
namespace App\Exports;

// use App\Model\ShippingInspector;
// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Sheet;
Use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Cell\DataType;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
// use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class ExportTraceabilityReport implements FromView, WithEvents, WithTitle
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
    protected $shipment_date_live;

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
    $shipment_date_live
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
        $this->shipment_date_live = $shipment_date_live;
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
        $shipment_date_select = $this->shipment_date_live;

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

                

                // dd($prodn_runcard_details);
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
                // $event->sheet->setCellValue('F7',"#IN-A Lot No.");
                // $event->sheet->setCellValue('G7',"#IN-B Lot No.");
                // $event->sheet->setCellValue('H7',"#MO Lot No.");
                // $event->sheet->setCellValue('I7',"#CV Lot No.");
                // $event->sheet->setCellValue('J7',"#LO Lot No.");
                // $event->sheet->setCellValue('K7',"#CS724 Lot No.");
                // $event->sheet->setCellValue('L7',"#CS342 Lot No.");
                // $event->sheet->setCellValue('M7',"#ME Lot No.");
                // $event->sheet->setCellValue('N7',"Box Lot #");
                // $event->sheet->setCellValue('O7',"Lot Qty");
                // $event->sheet->setCellValue('P7',"Shipment Qty");
                // $event->sheet->setCellValue('Q7',"Shipment Date");
                // $event->sheet->getDelegate()->getStyle('A7:Q7')->applyFromArray($hv_center);
                // $event->sheet->getDelegate()->getStyle('A7:Q7')->getAlignment()->setWrapText(true);

                // $event->sheet->getDelegate()->getStyle('A8:E8')
                // ->getFill()
                // ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                // ->getStartColor()
                // ->setARGB('AAFF00');
                
                $start_col = 8;
                $test_col = 8;
                $column_start = 'F';
                $start_column = 'F';
                $try_array = array();
                // $col_start = 9;
                // $starttime = microtime(true);
                for ($i=0; $i<count($material_name); $i++) {
                    $event->sheet->setCellValue($column_start.'7', $material_name[$i]->material_name);

                    $column_start++;

                }
                // $endtime = microtime(true);
        
                // $duration = $endtime - $starttime; //calculates total time taken
                // dd($duration);
                $test = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Box Lot #");
                $column_start++;
                $test2 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Lot Qty");
                $column_start++;
                $test3 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Shipment Qty");
                $column_start++;
                $test4 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Shipment Date");
                $column_start++;
                $test5 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Operator/s");
                $column_start++;
                $test6 = $column_start;
                $event->sheet->setCellValue($column_start.'7',"Work Week");

                
                for ($m=0; $m < count($prodn_runcard_tsppts_details) ; $m++) {
                    $input_date = $prodn_runcard_tsppts_details[$m]->created_at;
                    $date_input = substr($input_date,0,10);
                    $box_no = substr($prodn_runcard_tsppts_details[$m]->lot_no,-2);

                    for ($y=0; $y <count($prodn_runcard_tsppts_details[$m]->prod_runcard_station_details); $y++) {

                        for ($c=0; $c <count($dlabel_data); $c++) {

                            for ($d=0; $d <count($dlabel_boxes_data) ; $d++) {
                                // if($prodn_runcard_tsppts_details[$m]->runcard_no == $dlabel_boxes_data[$d]->lot_no && $dlabel_boxes_data[$d]->d_label_id == $dlabel_data[$c]->id && $dlabel_date[$c]->shipment_date == $shipment_date_select  ){
                                if($prodn_runcard_tsppts_details[$m]->runcard_no == $dlabel_boxes_data[$d]->lot_no && $dlabel_boxes_data[$d]->d_label_id == $dlabel_data[$c]->id){
                                        $event->sheet->setCellValue($test4.$start_col, $dlabel_data[$c]->shipment_date);
                                        $event->sheet->setCellValue($test3.$start_col, $dlabel_data[$c]->po_qty);

                                        $event->sheet->setCellValue('A'.$start_col, $date_input);
                                        $event->sheet->setCellValue($test.$start_col, $box_no);
                                        $event->sheet->setCellValue($test2.$start_col, $prodn_runcard_tsppts_details[$m]->prod_runcard_station_details[$y]->qty_output);
                                        for ($x=0; $x <count($prodn_runcard_details); $x++) {

                                            for ($i=0; $i<count($material_name); $i++) {
                                            
                                                for ($t=0; $t <count($prodn_runcard_details[$x]->prod_runcard_station_many_details) ; $t++) {

                                                    for ($n=0; $n <count($prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details) ; $n++) {
                                                        if($prodn_runcard_tsppts_details[$m]->prod_runcard_station_details[$y]->runcard_no === $prodn_runcard_details[$x]->runcard_no){

                                                            $lot_number = $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->lot_no;
                                                            
                                                            if (str_contains($prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name,'CT')){
                                                                $event->sheet->setCellValue('C'.$start_col, StrToUpper($lot_number));
                                                                    // if

                                                                    for ($p=0; $p <count($sakidashi_issuance_item_data); $p++) {
                                                                        if($sakidashi_issuance_item_data[$p]->lot_no == StrToUpper($lot_number)){
                                                                            $event->sheet->setCellValue('B'.$start_col, $sakidashi_issuance_item_data[$p]->pair_no);
                                                                            $event->sheet->setCellValue('D'.$start_col, $sakidashi_issuance_item_data[$p]->required_qty);
                                                                            // $event->sheet->setCellValue('E'.$start_col, $sakidashi_issuance_item_data[$p]->required_qty / $materialprocess_data[0]->pin_popu);
                                                                            if ($materialprocess_data[0]->pin_popu != NULL){
                                                                                $event->sheet->setCellValue('E'.$start_col, $sakidashi_issuance_item_data[$p]->required_qty / $materialprocess_data[0]->pin_popu);

                                                                            }else{
                                                                                $event->sheet->setCellValue('E'.$start_col, $sakidashi_issuance_item_data[$p]->required_qty);

                                                                            }
                                                                        }
                                                                        $operator_names = "";
                                                                        for ($b=0; $b <count($operator_details[$prodn_runcard_details[$x]->id]); $b++) {

                                                                            $operator_names .= $operator_details[$prodn_runcard_details[$x]->id][$b]->infoHeadCheckedby->name . PHP_EOL;
                                                                        
                                                                        }
                                                                        $event->sheet->setCellValue($test5.$start_col, $operator_names);
                                                                        $event->sheet->setCellValue($test6.$start_col, $prodn_runcard_details[$x]->work_week);
                                                                        // $event->sheet->setCellValue($test7.$start_col, $prodn_runcard_details[$x]->runcard_no);

                                                                    }

                                                            }

                                                            if(count($material_name) == 4){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }


                                                            }

                                                            if(count($material_name) == 5){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }


                                                            }

                                                            if(count($material_name) == 6){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 7){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 8){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 9){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 10){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 11){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 12){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 13){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[12]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('R'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 14){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[12]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('R'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[13]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('S'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 15){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[12]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('R'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[13]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('S'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[14]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('T'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 16){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[12]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('R'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[13]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('S'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[14]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('T'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[15]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('U'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 17){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[12]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('R'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[13]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('S'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[14]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('T'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[15]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('U'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[16]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('V'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 18){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[12]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('R'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[13]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('S'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[14]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('T'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[15]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('U'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[16]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('V'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[17]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('W'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 19){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[12]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('R'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[13]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('S'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[14]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('T'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[15]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('U'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[16]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('V'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[17]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('W'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[18]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('X'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                            if(count($material_name) == 20){

                                                                if($material_name[0]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('F'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[1]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('G'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[2]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('H'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[3]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('I'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[4]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('J'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[5]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('K'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[6]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name ){
                                                                    $event->sheet->setCellValue('L'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[7]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('M'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[8]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('N'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[9]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('O'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[10]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('P'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[11]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Q'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[12]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('R'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[13]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('S'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[14]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('T'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[15]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('U'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[16]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('V'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[17]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('W'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[18]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('X'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }

                                                                if($material_name[19]->material_name == $prodn_runcard_details[$x]->prod_runcard_station_many_details[$t]->prod_runcard_station_material_details[$n]->material_name){
                                                                    $event->sheet->setCellValue('Y'.$start_col, StrToUpper($lot_number));
                                                                    // $start_column++;
                                                                }
                                                            }

                                                        }

                                                    }

                                                }
                                            }

                                        }
                                        $start_col++;
                                }
                            }
                        }

                    }
                    // $start_col++;
                }

                $end_col = $start_col - 1;
                $event->sheet->getDelegate()->getStyle('A7'.':'.$test6.'7')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A8'.':'.$test6.'8')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A9'.':'.$test6.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A9'.':'.'E'.$end_col)->applyFromArray($hv_center);
                // $event->sheet->getDelegate()->getStyle('A8:R8')->applyFromArray($hv_center);
                // $event->sheet->getDelegate()->getStyle('F'.$start_col.':Z'.$start_col)->applyFromArray($hv_center);

                // $event->sheet->getDelegate()->getStyle('F9'.':'.$column_start.$end_col)->applyFromArray($hlv_center);



                $event->sheet->getDelegate()->getStyle('A8'.':'.$test6.$end_col)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A7'.':'.$test6.$end_col)->applyFromArray($styleBorderAll);
                $event->sheet->getDelegate()->getStyle($test.'9'.':'.$test6.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle($test3.'9'.':'.$test6.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('N8'.':'.$test4.$end_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('P8'.':'.$test4.$end_col)->applyFromArray($hv_center);

            },
        ];
    }
}
