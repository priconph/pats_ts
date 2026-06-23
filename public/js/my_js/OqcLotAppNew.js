function LoadApplicationDetails(application_id)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };


  $.ajax({
    url: "retrieve_lotapp_details",
    method: "get",
    data: 
    { 
      oqc_lotapp_id: application_id
    },
    dataType: "json",
    beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          let id = JsonObject['lotapp_details'][0].id;
          let oqc_lotapp_id = JsonObject['lotapp_details'][0].oqc_lotapp_id;

          $('#view_hidden_id').val(id);
          $('#view_hidden_lotapp_id').val(oqc_lotapp_id);


          dt_view_application.draw();
          dt_view_runcards.draw();

          dt_view_oqcvir.draw();

        }
      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
  });
}


function GetTotalOutputQty(array_runcard)
{
	let total_output_qty = 0;

	for(let i = 0; i < array_runcard.length; i++)
	{
		total_output_qty += array_runcard[i].output_qty;
	}

	$('#total_lot_qty').val(total_output_qty);
}

function DisableSubmitRuncardApplication(array_runcard)
{
  if(array_runcard.length != 0)
  {
    $('#btn_submit_oqclotapp_runcards').removeAttr('disabled');
  }
  else
  {
    $('#btn_submit_oqclotapp_runcards').prop('disabled','disabled');
  }
}

function RemoveRuncard(array_runcard, runcard_id)
{
	toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

  for(let i = 0; i < array_runcard.length; i++)
  {
    if(array_runcard[i]['runcard_id'] == runcard_id)
    {
      array_runcard.splice(i,1);
    }
  }

  dt_oqclotapp_runcards.draw();
  DisableSubmitRuncardApplication(array_runcard);
  GetTotalOutputQty(array_runcard);
  toastr.info('Removed Runcard!');
}

function ValidateOqcRuncard(array_runcard, po_num, runcard_no, per_lot_qty)
{
	toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

    	url: "get_runcard_details",
    	method: "get",
    	data: 
    	{	
    		po_num: po_num,
    		runcard_no: runcard_no
    	},
    	dataType: "json",
    	beforeSend: function(){
        //
        },
        success: function(JsonObject){

        	if(JsonObject['result'] == 1)
        	{
        		let runcard_id = JsonObject['runcard_details'][0].id;
        		let runcard_no = JsonObject['runcard_details'][0].runcard_no;
        		let output_qty = JsonObject['runcard_details'][0].prod_runcard_station_many_details[0].qty_output;
            let validate_output_qty = 0;

            for(let i = 0; i < array_runcard.length; i++)
            {
              validate_output_qty += array_runcard[i].output_qty;
            }

            if(per_lot_qty > validate_output_qty) //if bigger than lot quantity
            {
              let runcard_details = {

                "runcard_id": runcard_id,
                "type": 1,
                "runcard_no": runcard_no,
                "output_qty": output_qty
              }

              if(array_runcard.some(runcard => runcard.runcard_id === runcard_details.runcard_id))
              {
                toastr.error('Runcard Details already added!');
              }
              else
              {
                array_runcard.push(runcard_details);
                dt_oqclotapp_runcards.draw();
                toastr.success('Runcard Details Added to Application!');

                GetTotalOutputQty(array_runcard);
              }       
            }
            else
            {
              toastr.error('Application will be more than Per Lot Quantity!');
            }        		
        	}
        	else
        	{
        		toastr.error('Runcard Details not Found in PO Number!');
        	}

          DisableSubmitRuncardApplication(array_runcard);          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function ValidateOqcRework(array_runcard, po_num, rework_no, per_lot_qty)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "get_rework_details",
      method: "get",
      data: 
      { 
        po_num: po_num,
        rework_no: rework_no
      },
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            let runcard_id = JsonObject['rework_details'][0].id;
            let runcard_no = JsonObject['rework_details'][0].defect_escalation_no;
            let output_qty = JsonObject['rework_details'][0].defect_escalation_station_many_details[0].qty_good;
            let validate_output_qty = 0;

            for(let i = 0; i < array_runcard.length; i++)
            {
              validate_output_qty += array_runcard[i].output_qty;
            }

            if(per_lot_qty > validate_output_qty) //if bigger than lot quantity
            {
              let runcard_details = {

                "runcard_id": runcard_id,
                "type": 2,
                "runcard_no": runcard_no,
                "output_qty": output_qty
              }

              if(array_runcard.some(runcard => runcard.runcard_id === runcard_details.runcard_id))
              {
                toastr.error('Rework Details already added!');
              }
              else
              {
                array_runcard.push(runcard_details);
                dt_oqclotapp_runcards.draw();
                toastr.success('Rework Details Added to Application!');

                GetTotalOutputQty(array_runcard);
              }       
            }
            else
            {
              toastr.error('Application will be more than Per Lot Quantity!');
            }           
          }
          else
          {
            toastr.error('Rework Details not Found in PO Number!');
          }

          DisableSubmitRuncardApplication(array_runcard);          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function LoadDeviceDetails(current_po, current_series)
{
	toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

    	url: "load_device_details",
    	method: "get",
    	data:
    	{
    		current_series: current_series
    	},
    	dataType: "json",
    	beforeSend: function(){
        //
        },
        success: function(JsonObject){

        	if(JsonObject['result'] == 1)
        	{
        		let lot_qty = JsonObject['device_details'][0].boxing;

            $('#current_po_num').val(current_po);
            $('#current_series_name').val(current_series);
            $('#current_lot_qty').val(lot_qty);

            dt_bulk_runcards.draw();
            dt_bulk_rework.draw();
        	}
        	else
        	{
        		toastr.error('Error loading PO Details!');
        	}          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function SubmitOqcRuncards(array_runcard)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "submit_oqc_runcards",
      method: "post",
      data: $('#formAddLotAppRuncards').serialize() + "&array_runcard=" + JSON.stringify(array_runcard),
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            $('#modalAddLotAppRuncards').modal('hide');
            $('#formAddLotAppRuncards')[0].reset();
            array_runcard = [];


            //draw main datatable here
            dt_oqclotapp_runcards.draw();
            dt_oqclotapp.draw();
            toastr.success('Runcards Added for Application! Please fill-up the details in the next step.');
          }
          else
          {
            toastr.error('Error Saving Runcards!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function RetrieveLotApplicationDetails(oqc_lotapp_id, series_name)
{
   toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "retrieve_lotapp_details",
      method: "get",
      data: 
      {
        oqc_lotapp_id: oqc_lotapp_id
      },
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            let lotapp_id = JsonObject['lotapp_details'][0].oqc_lotapp_id;
            let lotapp_po_num = JsonObject['lotapp_details'][0].po_num;
            let lot_qty = JsonObject['lotapp_quantity'];

            $('#lotapp_id').val(lotapp_id);
            $('#lotapp_po_num').val(lotapp_po_num);
            $('#lotapp_lot_qty').val(lot_qty);
            $('#lotapp_series_name').val(series_name);

            $('#editRuncardOqcLotApp').val(lotapp_id);
          }
          else
          {
            toastr.error('Error Loading Lot Application Details!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function RemoveFvo(array_fvo, fvo_user_id)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

  for(let i = 0; i < array_fvo.length; i++)
  {
    if(array_fvo[i]['fvo_user_id'] == fvo_user_id)
    {
      array_fvo.splice(i,1);
    }
  }

  dt_oqclotapp_fvo.draw();
  dt_oqclotapp_fvo_sub.draw();
  toastr.info('Removed FVO!');
}

function SearchFvo(fvo_id, array_fvo)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "search_user_details",
      method: "get",
      data: 
      {
        employee_id: fvo_id
      },
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            let fvo_user_id = JsonObject['user_details'][0].id;
            let fvo_employee_id = JsonObject['user_details'][0].employee_id;
            let fvo_name = JsonObject['user_details'][0].name;

            let fvo_details = 
            {
              "fvo_user_id" : fvo_user_id,
              "fvo_employee_id" : fvo_employee_id,
              "fvo_name" : fvo_name
            }

            if(array_fvo.some(fvo => fvo.fvo_user_id === fvo_details.fvo_user_id))
            {
              toastr.error('FVO Details already added!');
            }
            else
            {
              array_fvo.push(fvo_details);
              dt_oqclotapp_fvo.draw();
              toastr.success('Final Visual Operator Added!');
            }    

          }
          else
          {
            toastr.error('FVO Details not found!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function CheckOqcLotApplication(array_fvo)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    let oqc_validate = 0;

    let datetime = $('#lotapp_datetime').val();

    if(!datetime)
    {
      $('#lotapp_datetime').addClass('is-invalid');
      oqc_validate++;
    }
    else
    {
      $('#lotapp_datetime').removeClass('is-invalid');
    }

    if($('#lotapp_device_type').val() == null)
    {
      $('#lotapp_device_type').addClass('is-invalid');
      oqc_validate++;
    }
    else
    {
      $('#lotapp_device_type').removeClass('is-invalid');
    }

    if($('#lotapp_assembly_line').val() == null)
    {
      $('#lotapp_assembly_line').addClass('is-invalid');
      oqc_validate++;
    }
    else
    {
      $('#lotapp_assembly_line').removeClass('is-invalid');
    }

    if(oqc_validate > 0)
    {
      toastr.error('There are missed fields! Please enter data.');
    }
    else
    {
      if(!array_fvo.length)
      {
        toastr.error('It seems that there are no FVO yet. scan the employee ID!');
      }
      else
      {
        $('#formOqcLotApplication').submit();
      }
    }
}

function SubmitOqcLotApplication(array_fvo)
{
   toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

     $.ajax({

      url: "submit_oqc_lot_application",
      method: "post",
      data: $('#formOqcLotApplication').serialize() + "&array_fvo=" + JSON.stringify(array_fvo),
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            $('#modalOqcLotApplication').modal('hide');
            $('#formOqcLotApplication')[0].reset();
            array_fvo = [];

            //draw main datatable here
            dt_oqclotapp.draw();

            toastr.success('Lot Application Successful!');
          }
          else
          {
            toastr.error('Error Saving Application!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function ViewLotAppHistory(oqc_lotapp_id, series_name)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

     $.ajax({

      url: "retrieve_lotapp_details",
      method: "get",
      data: 
      {
        oqc_lotapp_id: oqc_lotapp_id
      },
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            let lotapp_id = JsonObject['lotapp_details'][0].oqc_lotapp_id;
            let lotapp_po_num = JsonObject['lotapp_details'][0].po_num;
            let lotapp_series_name = series_name;
            let lotapp_quantity = JsonObject['lotapp_quantity'];
            let lotapp_application_datetime = JsonObject['lotapp_details'][0].application_datetime;
            let lotapp_device_type = JsonObject['lotapp_details'][0].device_type;
            let lotapp_assembly_line = JsonObject['lotapp_details'][0].assembly_line_id;

            $('#lotapp_history_id').val(lotapp_id);
            $('#lotapp_history_po_num').val(lotapp_po_num);
            $('#lotapp_history_series_name').val(lotapp_series_name);
            $('#lotapp_history_lot_qty').val(lotapp_quantity);
            $('#lotapp_history_datetime').val(lotapp_application_datetime);
            $('#lotapp_history_device_type').val(lotapp_device_type);
            $('#lotapp_history_assembly_line').val(lotapp_assembly_line);

            dt_oqclotapp_history_runcards.draw();
            dt_oqclotapp_history_fvo.draw();
          }
          else
          {
            toastr.error('FVO Details not found!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function LoadQrCodeDetails(po_num, lotapp_id)
{
   $.ajax({

      url: "load_qr_code_details",
      method: "get",
      data: 
      {
        po_num: po_num,
        lotapp_id: lotapp_id
      },
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
             $("#img_barcode_PO").attr('src', JsonObject['po_num']);
             $("#img_barcode_lotno1").attr('src', JsonObject['lotapp_id']);
          }
          else
          {
              toastr.error('Error Loading Sticker Details!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function LoadStickerDetails(oqc_lotapp_id, device_name)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "retrieve_lotapp_details",
      method: "get",
      data: 
      {
        oqc_lotapp_id: oqc_lotapp_id
      },
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            let lotapp_id = JsonObject['lotapp_details'][0].oqc_lotapp_id;
            let lotapp_po_num = JsonObject['lotapp_details'][0].po_num;
            let lotapp_quantity = JsonObject['lotapp_quantity'];

            $('#lbl_po_no_PO').text(lotapp_po_num);
            $('#lbl_device_name_PO').text(device_name);

            $('#lbl_lot_batch_no').text(lotapp_id);
            $('#lbl_lot_qty').text(lotapp_quantity);

            LoadQrCodeDetails(lotapp_po_num, lotapp_id);
          }
          else
          {
              toastr.error('Error Loading Sticker Details!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function GenerateSticker2(image_ponum, image_lotapp, po_num, device_name, oqclotapp_id, lot_qty)
{
   popup = window.open();
        let content = '';
        
        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';

         content += 'td {';
        content += 'font-family: Arial';
        content += '}';

        
        content += '.rotated {';
        content += 'width: 180px;';
        content += 'position: absolute;';
        content += 'left: 15px;';
        content += '}';

        content += '.s {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 15px;';
        content += '}';

        content += '.s1 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 40px;';
        content += '}';

        content += '.s2 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 66px;';
        content += '}';

        content += '.s3 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 40px;';
        content += '}';

        content += '</style>';
        content += '</head>';
        content += '<body>';

        content += '<table style="height: 46px;" cellspacing="0" cellpadding="0"><tbody><tr style="height: 6px;"><td style="text-align: center; font-size: 5px; height: 6px; width: 293px;" colspan="2"><img src="public/images/pricon-logo.png" style=" min-width: 45px; max-width: 45px;"></td></tr><tr style="height: 18px;"><td style="border-right: 1px; text-align: center; height: 18px; width: 146px;"><img src="' + image_ponum  + '" style=" min-width: 45px; max-width: 45px;"></td><td style="text-align: center; height: 18px; width: 146px;"><img src="' + image_lotapp  + '" style="min-width: 45px; max-width: 45px;"></td></tr><tr style="height: 6px;"><td style="text-align: center; font-size: 5px; height: 6px; width: 146px; font-weight: bold;">' + po_num + '</td><td style="text-align: center; font-size: 5px; height: 6px; width: 146px; font-weight: bold;">' + oqclotapp_id + '</td></tr><tr style="height: 10px;"><td style="text-align: center; font-size: 5px; height: 10px; width: 146px; font-weight: bold;">' + device_name + '</td><td style="text-align: center; font-size: 5px; height: 10px; width: 146px; font-weight: bold;">' + lot_qty + '</td></tr><tr style="height: 6px;"><td style="text-align: center; font-size: 5px; height: 6px; width: 293px;" colspan="2">&nbsp</td><tr style="height: 6px;"><td style="text-align: center; font-size: 5px; height: 6px; width: 293px;" colspan="2">OQC Lot Application</td></tr><tr style="height: 6px;"><td style="text-align: center; font-size: 5px; height: 6px; width: 293px;" colspan="2">Generated by PATS TS</td></tr></tbody></table>';
       

        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
}


function GenerateSticker(image_ponum, image_lotapp, po_num, device_name, oqclotapp_id, lot_qty)
{
      popup = window.open();
        let content = '';
        
        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';

         content += '.centerticket {';
        content += 'width: 300px;';
        content += 'left: 50%;';
        content += '}';

        
        content += '.rotated {';
        content += 'width: 180px;';
        content += 'position: absolute;';
        content += 'left: 15px;';
        content += '}';

        content += '.s {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 15px;';
        content += '}';

        content += '.s1 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 40px;';
        content += '}';

        content += '.s2 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 66px;';
        content += '}';

        content += '.s3 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 40px;';
        content += '}';

        content += '</style>';
        content += '</head>';
        content += '<body>';

        content += '<table>';
        
        content += '<div class="centerticket">';
        //- 1st sticker QR
        content += '<div class="rotated">';

        content += '<tr>';
            content += '<td style="text-align: center;">';
            content += '<img src="' + image_ponum + '" style="min-width: 45px; max-width: 45px;">';
            content += '</td>';

            content += '<td>';
            content += '<div class="s"></div>';
            content += '</td>';

            content += '<td style="text-align: center;">';
            content += '<img src="' + image_lotapp + '" style="min-width: 43px; max-width: 43px;">';
            content += '</td>';
        content += '</tr>';
        content += '</div>';

        //- 1st QR details
        content += '<tr>';
            content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
            content += '<label style="font-weight: bold;">' + po_num + '</label>';
            content += '<br>';
            content += '<label>' + device_name + '</label>';
            content += '</td>';

            content += '<td>';
            content += '<div class="s1"></div>';
            content += '</td>';

            content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
            content += '<label>' + oqclotapp_id + '</label>';
            content += '<br>';
            content += '<label style="font-weight: bold;">' + lot_qty + '</label>';
            content += '<br>';
            content += '</td>';
        content += '</tr>';
        content += '</table>';     

        content += '</div>';

        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
}

function EditRemoveRuncard(array_runcard, runcard_id)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

      if(array_runcard.includes(runcard_id) == true)
      {
        toastr.error('Already in List!');
      }
      else
      {
        array_runcard.push(runcard_id);
        dt_oqclotapp_view_runcards.draw();
        dt_oqclotapp_view_runcards_sub.draw();
        toastr.warning('Runcard to be Removed after Saving!');
      }       
}

function EditCancelRemoveRuncard(array_runcard, runcard_id)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    for(let i = 0; i < array_runcard.length; i++)
    {
      if(array_runcard[i] == runcard_id)
      {
        array_runcard.splice(i,1);
      }
    }

    dt_oqclotapp_view_runcards.draw();
    dt_oqclotapp_view_runcards_sub.draw();
    toastr.warning('Cancelled Removal of Runcard!');
  }

function LoadRuncardDeviceDetails(current_po, current_series)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "load_device_details",
      method: "get",
      data:
      {
        current_series: current_series
      },
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            let lot_qty = JsonObject['device_details'][0].boxing;

            $('#editRuncardPoNo').val(current_po);
            $('#editRuncardLotQty').val(lot_qty);
          }
          else
          {
            toastr.error('Error loading PO Details!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function EditAddRuncard(runcard_no, po_num, oqc_lotapp_id, lot_qty, array_runcard)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

  $.ajax({

    url: "edit_add_runcard",
    method: "get",
    data:
    {
      runcard_no: runcard_no,
      po_num: po_num,
      oqc_lotapp_id: oqc_lotapp_id,
      lot_qty: lot_qty
    },
    dataType: "json",
    beforeSend: function(){
      //
    },
    success: function(JsonObject){

      if(JsonObject['result'] == 1)
      {
        let runcard_id = JsonObject['runcard_details'][0].id;
        let runcard_no = JsonObject['runcard_details'][0].runcard_no;
        let output_qty = JsonObject['runcard_details'][0].prod_runcard_station_many_details[0].qty_output;

         let runcard_details = {

          "runcard_id": runcard_id,
          "type" : 1,
          "runcard_no": runcard_no,
          "output_qty": output_qty
        }


        if(array_runcard.some(runcard => runcard.runcard_id === runcard_details.runcard_id))
        {
          toastr.error('Runcard Details already added!');
        }
        else
        {
          array_runcard.push(runcard_details);
          dt_oqclotapp_additional_runcards.draw();
          dt_oqclotapp_additional_runcards_sub.draw();
          toastr.success('Runcard Details Added to Application!');
        }       
      }
      else if(JsonObject['result'] == 2)
      {
        toastr.error('Runcard Already Added to an Application!');
      }
      else if(JsonObject['result'] == 3)
      {
        toastr.error('Runcard Details not Found!');
      }
      else
      {
        toastr.error('Error Loading Runcard Details!');
      }          
    },
    error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
    }


  });
}

function EditAddRework(rework_no, po_num, oqc_lotapp_id, lot_qty, array_runcard)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

  $.ajax({

    url: "edit_add_rework",
    method: "get",
    data:
    {
      rework_no: rework_no,
      po_num: po_num,
      oqc_lotapp_id: oqc_lotapp_id,
      lot_qty: lot_qty
    },
    dataType: "json",
    beforeSend: function(){
      //
    },
    success: function(JsonObject){

      if(JsonObject['result'] == 1)
      {
        let runcard_id = JsonObject['rework_details'][0].id;
        let runcard_no = JsonObject['rework_details'][0].defect_escalation_no;
        let output_qty = JsonObject['rework_details'][0].defect_escalation_station_many_details[0].qty_good;

         let runcard_details = {

          "runcard_id": runcard_id,
          "type" : 2,
          "runcard_no": runcard_no,
          "output_qty": output_qty
        }


        if(array_runcard.some(runcard => runcard.runcard_id === runcard_details.runcard_id))
        {
          toastr.error('Rework Details already added!');
        }
        else
        {
          array_runcard.push(runcard_details);
          dt_oqclotapp_additional_runcards.draw();
          toastr.success('Rework Details Added to Application!');
        }       
      }
      else if(JsonObject['result'] == 2)
      {
        toastr.error('Runcard Already Added to an Application!');
      }
      else if(JsonObject['result'] == 3)
      {
        toastr.error('Runcard Details not Found!');
      }
      else
      {
        toastr.error('Error Loading Runcard Details!');
      }          
    },
    error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
    }


  });
}

function EditRemoveAdditionalRuncard(array_runcard, runcard_id)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

  for(let i = 0; i < array_runcard.length; i++)
  {
    if(array_runcard[i]['runcard_id'] == runcard_id)
    {
      array_runcard.splice(i,1);
    }
  }

  dt_oqclotapp_additional_runcards.draw();
  dt_oqclotapp_additional_runcards_sub.draw();
  toastr.info('Removed Runcard!');
}

function SaveRuncardChanges(oqc_lotapp_id, emp_no, array_runcard_add, array_runcard_remove)
{
   toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "save_runcard_changes",
      method: "get",
      data:
      {
        oqc_lotapp_id: oqc_lotapp_id,
        emp_no: emp_no,
        array_runcard_add: array_runcard_add,
        array_runcard_remove: array_runcard_remove,
      },
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        { 
          $('#modalViewRuncards').modal('hide');
          array_runcard_remove = [];
          array_runcard_add = [];

          dt_oqclotapp_additional_runcards.draw();
          dt_oqclotapp_view_runcards.draw();

          //hide
          $('#modalViewRuncardsSub').modal('hide'); 

          toastr.success('Saving Runcard Changes Success!');
        }
        else if(JsonObject['result'] == 2)
        {
          toastr.error('Employee Details Not Found!');
        }
        else
        {
          toastr.error('Error Saving Changes!');
        }          
      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
}

//---------------------------------2ND SUBMISSION-----------------------------------
function LoadOtherSubmissionDetails(oqc_lotapp_id, po_num, series_name)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "retrieve_lotapp_details",
      method: "get",
      data:
      {
        oqc_lotapp_id: oqc_lotapp_id,
      },
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        { 
            let lotapp_id = JsonObject['lotapp_details'][0].oqc_lotapp_id;
            let lotapp_po_num = JsonObject['lotapp_details'][0].po_num;
            let lot_qty = JsonObject['lotapp_quantity'];
            let submission = JsonObject['lotapp_details'][0].submission + 1;
 

            $('#lotapp_submission').val(submission);
            $('#lotapp_id_sub').val(lotapp_id);
            $('#lotapp_po_num_sub').val(lotapp_po_num);  
            $('#lotapp_series_name_sub').val(series_name);
            $('#lotapp_lot_qty_sub').val(lot_qty);

            $('#editRuncardOqcLotAppSub').val(lotapp_id);      

            dt_oqclotapp_submissions.draw();
        }
        else
        {
          toastr.error('Error Loading Details!');
        }          
      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
}

function AddFvoOtherSubmission(oqc_lotapp_id, fvo_id, submission, array_fvo)
{
   toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "search_user_details",
      method: "get",
      data:
      {
        employee_id: fvo_id,
      },
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            let fvo_user_id = JsonObject['user_details'][0].id;
            let fvo_employee_id = JsonObject['user_details'][0].employee_id;
            let fvo_name = JsonObject['user_details'][0].name;

            let fvo_details = 
            {
              "fvo_user_id" : fvo_user_id,
              "fvo_employee_id" : fvo_employee_id,
              "fvo_name" : fvo_name,
              "submission": submission
            }

            if(array_fvo.some(fvo => fvo.fvo_user_id === fvo_details.fvo_user_id))
            {
              toastr.error('FVO Details already added!');
            }
            else
            {
              array_fvo.push(fvo_details);
              dt_oqclotapp_fvo_sub.draw();
              toastr.success('Final Visual Operator Added!');
            }    
        }
        else
        {
          toastr.error('Error Loading Details!');
        }          
      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
}

function LoadRuncardDeviceSubDetails(current_po, current_series, oqclotapp_id)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $.ajax({

      url: "load_device_details",
      method: "get",
      data:
      {
        current_series: current_series
      },
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            let lot_qty = JsonObject['device_details'][0].boxing;

            $('#editRuncardPoNoSub').val(current_po);
            $('#editRuncardLotQtySub').val(lot_qty);
          }
          else
          {
            toastr.error('Error loading PO Details!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function EditAddRuncardSub(runcard_no, po_num, oqc_lotapp_id, lot_qty, array_runcard)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

  $.ajax({

    url: "edit_add_runcard",
    method: "get",
    data:
    {
      runcard_no: runcard_no,
      po_num: po_num,
      oqc_lotapp_id: oqc_lotapp_id,
      lot_qty: lot_qty
    },
    dataType: "json",
    beforeSend: function(){
      //
    },
    success: function(JsonObject){

      if(JsonObject['result'] == 1)
      {
        let runcard_id = JsonObject['runcard_details'][0].id;
        let runcard_no = JsonObject['runcard_details'][0].runcard_no;
        let output_qty = JsonObject['runcard_details'][0].prod_runcard_station_many_details[0].qty_output;

         let runcard_details = {

          "runcard_id": runcard_id,
          "type" : 1,
          "runcard_no": runcard_no,
          "output_qty": output_qty
        }


        if(array_runcard.some(runcard => runcard.runcard_id === runcard_details.runcard_id))
        {
          toastr.error('Runcard Details already added!');
        }
        else
        {
          array_runcard.push(runcard_details);
          dt_oqclotapp_additional_runcards.draw();
          toastr.success('Runcard Details Added to Application!');
        }       
      }
      else if(JsonObject['result'] == 2)
      {
        toastr.error('Runcard Already Added to an Application!');
      }
      else if(JsonObject['result'] == 3)
      {
        toastr.error('Runcard Details not Found!');
      }
      else
      {
        toastr.error('Error Loading Runcard Details!');
      }          
    },
    error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
    }


  });
}

function EditAddReworkSub(rework_no, po_num, oqc_lotapp_id, lot_qty, array_runcard)
{
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

  $.ajax({

    url: "edit_add_rework",
    method: "get",
    data:
    {
      rework_no: rework_no,
      po_num: po_num,
      oqc_lotapp_id: oqc_lotapp_id,
      lot_qty: lot_qty
    },
    dataType: "json",
    beforeSend: function(){
      //
    },
    success: function(JsonObject){

      if(JsonObject['result'] == 1)
      {
        let runcard_id = JsonObject['rework_details'][0].id;
        let runcard_no = JsonObject['rework_details'][0].defect_escalation_no;
        let output_qty = JsonObject['rework_details'][0].defect_escalation_station_many_details[0].qty_good;

         let runcard_details = {

          "runcard_id": runcard_id,
          "type" : 2,
          "runcard_no": runcard_no,
          "output_qty": output_qty
        }


        if(array_runcard.some(runcard => runcard.runcard_id === runcard_details.runcard_id))
        {
          toastr.error('Rework Details already added!');
        }
        else
        {
          array_runcard.push(runcard_details);
          dt_oqclotapp_additional_runcards.draw();
          toastr.success('Rework Details Added to Application!');
        }       
      }
      else if(JsonObject['result'] == 2)
      {
        toastr.error('Runcard Already Added to an Application!');
      }
      else if(JsonObject['result'] == 3)
      {
        toastr.error('Runcard Details not Found!');
      }
      else
      {
        toastr.error('Error Loading Runcard Details!');
      }          
    },
    error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
    }


  });
}

function SubmitOtherSubmission(array_fvo)
{
   toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

     $.ajax({

      url: "submit_oqc_lot_application_sub",
      method: "post",
      data: $('#formOtherSubmission').serialize() + "&array_fvo=" + JSON.stringify(array_fvo),
      dataType: "json",
      beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            $('#modalLotAppOtherSubmission').modal('hide');
            $('#formOtherSubmission')[0].reset();
            array_fvo = [];

            //draw main datatable here
            dt_oqclotapp.draw();

            toastr.success('Lot Application Successful!');
          }
          else
          {
            toastr.error('Error Saving Application!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}