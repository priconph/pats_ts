<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
Use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheet\PackingAndShipmentSheet;

class ReportExport implements ShouldAutoSize, WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }

    use Exportable;

    protected $records;
    protected $type;
    protected $device_name;

    function __construct($type, $records, $device_name)
    {
        $this->records = $records;
        $this->type = $type;
        $this->device_name = $device_name;
    }

    //for multiple sheets
    public function sheets(): array
    {
        $sheets = [];

        if($this->type == 1){
        	$sheets[] = new PackingAndShipmentSheet($this->records, $this->device_name);
        }

        return $sheets;
    }
}
