<html>
<head>
<style>

th,td {
	padding: 10px;
}

td {
	padding: 5px;
}

table, th, td {
  border: 1px solid black;
}

</style>
</head>
<body>
	<table>

		<thead>

			@php
				// Material Kitting
				$materials = [];
				$unique_materials = [];

				for($i = 0; $i < count($partsLot_records); $i++){

					if(count($partsLot_records[$i]->prod_runcard_material_list) > 0){

						if($partsLot_records[$i]->prod_runcard_material_list[0]->wbs_material_kitting != null){
							$materials[] = $partsLot_records[$i]->prod_runcard_material_list[0]->wbs_material_kitting->item_desc;
						}
					}
				}

				$unique_materials = array_unique($materials);

				// Sakidashi Issuance
				$sakidashi = [];
				$unique_sakidashi = [];

				for($i = 0; $i < count($partsLot_records); $i++){

					if(count($partsLot_records[$i]->prod_runcard_material_list) > 0){
						// echo $partsLot_records[$i]->prod_runcard_material_list[0] . '<br>';

						for($index2 = 0; $index2 < count($partsLot_records[$i]->prod_runcard_material_list); $index2++){
							if($partsLot_records[$i]->prod_runcard_material_list[$index2]->wbs_sakidashi_issuance != null){
								if($partsLot_records[$i]->prod_runcard_material_list[$index2]->wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item != null){
									$sakidashi[] = $partsLot_records[$i]->prod_runcard_material_list[$index2]->wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item_desc;
								}
							}
						}
					}
				}

				$unique_sakidashi = array_unique($sakidashi);

				$colspan_count = count($unique_sakidashi) + count($unique_materials);
			@endphp

			<tr>
				<th colspan="9" style="text-align: left; font-family:'Arial'; font-size: 20pt; padding: 10px 10px; margin: 10px 10px;" >
				{{ $device_name }} Parts Lot Management Record</th>
			</tr>

			<tr>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">Runcard No.</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">投入日 Production Date</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">Shift</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">数量 Qty</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">刻印 Print Lot</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">Product Drawing Rev.</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">CAV of # IN</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">识别表示 Special adoption document or any special instruction</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">Remarks</th>
				<th rowspan="2" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold; padding: 10px 10px; margin: 10px 10px;">班长 MH Name</th>
				<th colspan="{{ $colspan_count }}" style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#38CDFF; font-weight: bold;">Parts Name</th>
			</tr>

			<!--SECORD HEADER ROW-->
			@php
				// Material Kitting
				$materials = [];
				$unique_materials = [];

				for($i = 0; $i < count($partsLot_records); $i++){

					if(count($partsLot_records[$i]->prod_runcard_material_list) > 0){

						if($partsLot_records[$i]->prod_runcard_material_list[0]->wbs_material_kitting != null){
							$materials[] = $partsLot_records[$i]->prod_runcard_material_list[0]->wbs_material_kitting->item_desc;
						}
					}
				}

				$unique_materials = array_unique($materials);

				// Sakidashi Issuance
				$sakidashi = [];
				$unique_sakidashi = [];

				for($i = 0; $i < count($partsLot_records); $i++){

					if(count($partsLot_records[$i]->prod_runcard_material_list) > 0){
						// echo $partsLot_records[$i]->prod_runcard_material_list[0] . '<br>';

						for($index2 = 0; $index2 < count($partsLot_records[$i]->prod_runcard_material_list); $index2++){
							if($partsLot_records[$i]->prod_runcard_material_list[$index2]->wbs_sakidashi_issuance != null){
								if($partsLot_records[$i]->prod_runcard_material_list[$index2]->wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item != null){
									$sakidashi[] = $partsLot_records[$i]->prod_runcard_material_list[$index2]->wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item_desc;
								}
							}
						}
					}
				}

				$unique_sakidashi = array_unique($sakidashi);
			@endphp


			<tr>
			@foreach($unique_materials as $value)
			
				<th style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#70ecf9; font-weight: bold;">
					{{ $value }}
				</th>
			
			@endforeach

			@foreach($unique_sakidashi as $value)
			
				<th style="text-align: center; font-family:'Arial'; font-size:10pt; background-color:#70ecf9; font-weight: bold;">
					{{ $value }}
				</th>
			
			@endforeach
			</tr>

		</thead>

		<tbody>
			@for($i = 0; $i < count($partsLot_records); $i++)
				@php

					$shiftletter = '';

					$updated_at = \Carbon\Carbon::parse($partsLot_records[$i]->updated_at)->format('m/d/yy');
					$shift 		= \Carbon\Carbon::parse($partsLot_records[$i]->created_at)->format('H:i a');
					$starttime 	= "7:30 am";
					$endtime 	= "19:30 pm";

					$date1 = DateTime::createFromFormat('H:i a', $shift);
					$date2 = DateTime::createFromFormat('H:i a', $starttime);
					$date3 = DateTime::createFromFormat('H:i a', $endtime);

					if ($date1 >= $date2 && $date1 <= $date3){
					   $shiftletter = 'A';
					} else {
						$shiftletter = 'B';
					}

				@endphp

				<tr>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">{{ $partsLot_records[$i]->runcard_no }}</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">{{ $updated_at }}</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">{{ $shiftletter }}</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">{{ $partsLot_records[$i]->lot_qty }}</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">
						@if($partsLot_records[$i]->oqc_details != null)
							{{ $partsLot_records[$i]->oqc_details->print_lot }}
						@else
							---
						@endif
					</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">{{ $partsLot_records[$i]->a_drawing_rev }}</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">N/A</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">NONE</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">
						@if($partsLot_records[$i]->oqc_details != null)
							{{ $partsLot_records[$i]->oqc_details->remarks }}
						@else
							---
						@endif
					</td>
					<td style="text-align: center; font-family:'Arial'; font-size:10pt;">
						@if(count($partsLot_records[$i]->prod_runcard_material_list) > 0)
							@if($partsLot_records[$i]->prod_runcard_material_list[0]->wbs_material_kitting->parts_prep->user_details != null)
								{{ $partsLot_records[$i]->prod_runcard_material_list[0]->wbs_material_kitting->parts_prep->user_details->name }}
							@else
								---
							@endif
						@else
							---
						@endif
					</td>

					@foreach($unique_materials as $item)
						<td style="text-align: center; font-family:'Arial'; font-size:10pt;">
							@php
								$col_runcard_materials = collect($partsLot_records[$i]->prod_runcard_material_list)->where('wbs_material_kitting.item_desc', $item)->flatten(1)->pluck('wbs_material_kitting.lot_no');

								//$lot_no = $col_runcard_materials;

								$lot_no = implode(" / ", $col_runcard_materials->toArray());

								echo $lot_no;
							@endphp
						</td>
					@endforeach

					@foreach($unique_sakidashi as $item)
						<td style="text-align: center; font-family:'Arial'; font-size:10pt;">
							@php
								$col_runcard_sakidashi = collect($partsLot_records[$i]->prod_runcard_material_list)->where('wbs_sakidashi_issuance.tbl_wbs_sakidashi_issuance_item.item_desc', $item)->flatten(1)->pluck('wbs_sakidashi_issuance.tbl_wbs_sakidashi_issuance_item.lot_no');

								//$lot_no = $col_runcard_sakidashi;

								$lot_no = implode(" / ", $col_runcard_sakidashi->toArray());

								echo $lot_no;
							@endphp
						</td>
					@endforeach
				</tr>	

				@endfor

		</tbody>





	</table>
</body>
</html>