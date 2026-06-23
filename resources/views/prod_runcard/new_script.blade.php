<script type="text/javascript">	

		// -JD
		// function getProcessFlowByDeviceName(cboElement, deviceName) {
		// 	let response
		//     let result = '<option value="0" selected disabled> -- Select Flow -- </option>';
		//     $.ajax({
		//         url: 'getProcessFlowByDeviceName',
		//         method: 'get',
        // 		async: false,
		//         data: {
		//           deviceName: deviceName
		//         },
		//         dataType: 'json',
		//         beforeSend: function(){
		//             result = '<option value="0" selected disabled> -- Loading -- </option>';
		//             cboElement.html(result);
		//         },
		//         success: function(JsonObject){
		//             result = '';
		//             if(JsonObject['data'].length > 0){
		//                 result = '<option value="0" selected disabled> -- Select Flow -- </option>';
		//                 for(let index = 0; index < JsonObject['data'].length; index++){
		//                     result += '<option value="' + JsonObject['data'][index].process_flow_id + '">' + JsonObject['data'][index]['substation'].name + '</option>';
		//                 }
		//             }
		//             else{
		//                 result = '<option value="0" selected disabled> -- No record found -- </option>';
		//             }

		//             cboElement.html(result);
		//             response = 1
		//         },
		//         error: function(data, xhr, status){
		//             result = '<option value="0" selected disabled> -- Reload Again -- </option>';
		//             cboElement.html(result);
		//             response = 1
		//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
		//         }
		//     });
		//     while(!response){}
		//     return response
		// }

		// Orig before 09-14-2022
		// function getProcessFlowByDeviceName(cboElement, deviceName) {
		// 	let response
		//     let result = '<option value="0" selected disabled> -- Select Flow -- </option>';
		//     $.ajax({
		//         url: 'getProcessFlowByDeviceName',
		//         method: 'get',
        // 		async: false,
		//         data: {
		//           deviceName: deviceName
		//         },
		//         dataType: 'json',
		//         beforeSend: function(){
		//             result = '<option value="0" selected disabled> -- Loading -- </option>';
		//             cboElement.html(result);
		//         },
		//         success: function(JsonObject){
		//             result = '';
		//             if(JsonObject['data'].length > 0){
		//                 result = '<option value="0" selected disabled> -- Select Flow -- </option>';
		//                 for(let index = 0; index < JsonObject['data'].length; index++){
		//                     result += '<option value="' + JsonObject['data'][index].id + '">' + JsonObject['data'][index]["process_flow"].name + '</option>';
		//                 }
		//             }
		//             else{
		//                 result = '<option value="0" selected disabled> -- No record found -- </option>';
		//             }

		//             cboElement.html(result);
		//             response = 1
		//         },
		//         error: function(data, xhr, status){
		//             result = '<option value="0" selected disabled> -- Reload Again -- </option>';
		//             cboElement.html(result);
		//             response = 1
		//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
		//         }
		//     });
		//     while(!response){}
		//     return response
		// }

		/** 
		 * Latest 09-14-2022
		 * -JD
		*/
		function getProcessFlowByDeviceName(cboElement, deviceName) {
			let response
		    let result = '<option value="0" selected disabled> -- Select Flow -- </option>';
		    $.ajax({
		        url: 'getProcessFlowByDeviceName',
		        method: 'get',
        		async: false,
		        data: {
		          deviceName: deviceName
		        },
		        dataType: 'json',
		        beforeSend: function(){
		            result = '<option value="0" selected disabled> -- Loading -- </option>';
		            cboElement.html(result);
		        },
		        success: function(JsonObject){
		            result = '';
		            if(JsonObject['data'].length > 0){
		                result = '<option value="0" selected disabled> -- Select Flow -- </option>';
		                for(let index = 0; index < JsonObject['data'].length; index++){
		                    result += '<option value="' + JsonObject['data'][index]["process_flow"].id + '">' + JsonObject['data'][index]["process_flow"].name + '</option>';
		                }
		            }
		            else{
		                result = '<option value="0" selected disabled> -- No record found -- </option>';
		            }

		            cboElement.html(result);
		            response = 1
		        },
		        error: function(data, xhr, status){
		            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
		            cboElement.html(result);
		            response = 1
		            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
		        }
		    });
		    while(!response){}
		    return response
		}


		function getMaterialListByDeviceName(cboElement, deviceName) {
			// console.log('juskooosssssss');
			let response
		    let result = '<option value="0" selected disabled> -- Select Material -- </option>';
		    $.ajax({
		        url: 'getMaterialListByDeviceName',
		        method: 'get',
        		async: false,
		        data: {
		          deviceName: deviceName
		        },
		        dataType: 'json',
		        beforeSend: function(){
		            result = '<option value="0" selected disabled> -- Loading List -- </option>';
		            cboElement.html(result);
					return;
		        },
		        success: function(JsonObject){
		            result = '';
		            if(JsonObject['materialList'].length > 0){
		            	console.log('materialList', JsonObject['materialList']);
		                result = '<option value="0" selected disabled> -- Select Material -- </option>';
		                for(let index = 0; index < JsonObject['materialList'].length; index++){
		                    result += '<option value="' + JsonObject['materialList'][index] + '">' + JsonObject['materialList'][index] + '</option>';
		                }
		            }
		            else{
		                result = '<option value="0" selected disabled> -- No Material\'s record found -- </option>';
		            }

		            cboElement.html(result);
		            response = 1
					return;
		        },
		        error: function(data, xhr, status){
		            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
		            cboElement.html(result);
		            response = 1
					return;
		            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
		        }
		    });
		    while(!response){}
		    return response
		}

		function getProcessFlowStepByDeviceName(cboElement, deviceName) {
			let response
		    let result = '<option value="0" selected disabled> -- Select Step -- </option>';
		    $.ajax({
		        url: 'getProcessFlowByDeviceName',
		        method: 'get',
        		async: false,
		        data: {
		          deviceName: deviceName
		        },
		        dataType: 'json',
		        beforeSend: function(){
		            result = '<option value="0" selected disabled> -- Loading -- </option>';
		            cboElement.html(result);
		        },
		        success: function(JsonObject){
		            result = '';
		            if(JsonObject['data'].length > 0){
		                result = '<option value="0" selected disabled> -- Select Step -- </option>';
		                for(let index = 0; index < JsonObject['data'].length; index++){
		                    result += '<option value="' + JsonObject['data'][index].id + '">' + JsonObject['data'][index].step + '</option>';
		                }
		            }
		            else{
		                result = '<option value="0" selected disabled> -- No record found -- </option>';
		            }

		            cboElement.html(result);
		            response = 1
		        },
		        error: function(data, xhr, status){
		            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
		            cboElement.html(result);
		            response = 1
		            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
		        }
		    });
		    while(!response)
		    return response
		}

		const getNextStepNumber = (runcardId, deviceName) => {
			let returnValue
		    $.ajax({
		        url: 'getNextStepNumber',
		        method: 'get',
        		async: false,
		        data: {
		        	runcardId,
		          	deviceName
		        },
		        dataType: 'json',
		        success: function(JsonObject){
		            returnValue = {...JsonObject}
		        },
		        error: function(data, xhr, status){
		            returnValue = { id: 0, value: 0 }
		        }
		    });
		    while(!returnValue){}
		    return returnValue
		}

	$(function(){
		// GetCboSubStationByStat($(".selSubStation"), 1);
		// GetCboMaterialByStat($(".selMaterial"), 1);

		$(document).on('click','#btnAddProcess', function(e){
			// alert('wew');
			$('#frm_edit_prod_runcard_station_details')[0].reset();
			// $('#mdl_edit_prod_runcard_station_details').modal('show');
			$('#txt_prod_runcard_station_id_query').val(null);
			getProcessFlowByDeviceName($(".selSubStation"), $("#txt_device_name_lbl").val())
			  // getProcessFlowStepByDeviceName($("#txt_edit_prod_runcard_station_step"), $("#txt_device_name_lbl").val())
			getMaterialListByDeviceName($(".selMaterial"), $("#txt_device_name_lbl").val())
			// lineWithDefect = $('#txtAssemblyLineId').val();
			let poNumber = $('#txt_po_number_lbl').val();  
			$('#txtPoNumberWithDefect').val(poNumber);
			let assemblyLine = $('#txtAssemblyLineId option:selected').text();  
			let assemblyLineId = $('#txtAssemblyLineId').val();
			// console.log('assemblyLine',assemblyLine);
			let lineWithDefect = assemblyLine.replace(/^Line\s*\d+\s*\(|\)$/g, '');
			// console.log('lineWithDefect',lineWithDefect);
			$('#txtLineWithDefect').val(lineWithDefect);
			$('#txtLineWithDefectId').val(assemblyLineId);



			const { id, value } = getNextStepNumber($("#txt_prod_runcard_id_query").val(), $("#txt_device_name_lbl").val())
			// console.log( id, value )
			if( id == 0 ) {
				toastr.error(value);
			} else {
				$('#mdl_edit_prod_runcard_station_details').modal('show');
			}

			$("#process_flow_step_number").val(value)
			$("#process_flow_id").val(id)

	        var data_arr = [];
	        data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
	        $("#txt_edit_prod_runcard_operator").val("0").trigger('change');

	        $("#tblEditProdRunStaMOD tbody").html('');
	        $("#pRCStatTotNoOfNG").css({color: 'green'});
	        $("#pRCStatTotNoOfNG").text('0');

	        let lastStepNum1 = $('#tbl_prod_runcard_stations tr:last').find('td:eq(0)').html();
	        let lastStepNum = $('#tbl_prod_runcard_stations tr:last').find('td:eq(1)').html();

	        console.log(lastStepNum1);
	        let lastOutputQty = $('#tbl_prod_runcard_stations tr:last').find('td:eq(8)').html();
	        let lastInputQty = $('#tbl_prod_runcard_stations tr:last').find('td:eq(7)').html();

	        if(lastStepNum1 == 'No data available in table'){
	        	lastStepNum = 1;
	        	// lastOutputQty = $("#txt_lot_qty").val();
	        	lastOutputQty = '';
	        }
	        else{
	        	lastStepNum = parseInt(lastStepNum) + 1;
	        }

	        let ProcessStep = $('#tbl_prod_runcard_stations tr:last').find('td:eq(1)').html();
			console.log(ProcessStep);

			// alert(ProcessStep)
			// if (ProcessStep == 'Printing / Curing' || ProcessStep == '#ME Installation'){
			if (ProcessStep == 1 || ProcessStep == 2 || ProcessStep == null ){
				$('#txt_edit_prod_runcard_station_input').attr('readonly', false); 
			}else{
				$('#txt_edit_prod_runcard_station_input').attr('readonly', true); 
			}

	        // $("#btnAddMaterial").trigger('click');
	        // $("#txt_edit_prod_runcard_station_step").val(lastStepNum);
	        $("#txt_edit_prod_runcard_station_input").val(lastOutputQty);

	        $("#txt_edit_prod_runcard_station_output").val(0);
	        $("#txt_edit_prod_runcard_station_ng").val(0);
	        $("#txt_edit_prod_runcard_substation").val(0).trigger('change');
	        $("#txt_edit_prod_runcard_operator").val(0).trigger('change');
	        $("#txt_edit_prod_runcard_station_machine").val(0).trigger('change');

	        $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
      	});

      	$(document).on('click','#btnDEAddProcess', function(e){
			// alert('wew');
			  $('#mdl_edit_prod_runcard_station_details').modal('show');
	        var data_arr = [];
	        data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
	        $("#txt_edit_prod_runcard_operator").val("0").trigger('change');

	        $("#tblEditProdRunStaMOD tbody").html('');
	        $("#pRCStatTotNoOfNG").css({color: 'green'});
	        $("#pRCStatTotNoOfNG").text('0');

	        let lastStepNum1 = $('#tbl_prod_runcard_stations tr:last').find('td:eq(0)').html();
	        let lastStepNum = $('#tbl_prod_runcard_stations tr:last').find('td:eq(1)').html();

	        console.log(lastStepNum1);
	        let lastOutputQty = $('#tbl_prod_runcard_stations tr:last').find('td:eq(7)').html();
	        let lastInputQty = $('#tbl_prod_runcard_stations tr:last').find('td:eq(8)').html();

	        if(lastStepNum1 == 'No data available in table'){
	        	lastStepNum1 = 1;
	        	lastOutputQty = $("#txt_lot_qty").val();
	        }
	        else{
	        	lastStepNum1 = parseInt(lastStepNum1) + 1;
	        }
	        console.log(lastStepNum1);
	        $("#txt_edit_prod_runcard_station_step").val(lastStepNum1);
	        $("#txt_edit_prod_runcard_station_input").val(lastOutputQty);
	        $("#txt_edit_prod_runcard_station_output").val(0);
	        $("#txt_edit_prod_runcard_station_ng").val(0);
	        $("#txt_edit_prod_runcard_substation").val(0).trigger('change');
	        $("#txt_edit_prod_runcard_operator").val(0).trigger('change');
	        $("#txt_edit_prod_runcard_station_machine").val(0).trigger('change');

	        $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
      	});

      	// $("#btnAddMaterial").click(function(){
      	// 	$("#mdlSaveMaterial").modal('show');
      	// 	$("#frmSaveMaterial")[0].reset();
      	// 	// 
      	// });

      	$("#frmSaveMaterial").submit(function(e){
      		e.preventDefault();
      	});

      	$("#btnSaveMaterial").click(function(){
      		$('#txt_employee_number_scanner').val('');
        	$('#mdl_employee_number_scanner').attr('data-formid','save_material').modal('show');
      	});
	});

</script>