function SearchConfirmationLotApp(po_num, lot_app, pack_qty, array_lots)
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

      url: "search_confirmation_lotapp",
      method: "get",
      data:
      {
        po_num: po_num,
        lot_app: lot_app,
      },
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        { 
          let total_lot_qty = 0;
          let runcard_objects;
          let rework_objects;
          
          let objects = JsonObject['lotapp_details'][0].oqclotapp_runcard_details;

          for($i = 0; $i < objects.length; $i++)
          {
              switch(JsonObject['lotapp_details'][0].oqclotapp_runcard_details[$i].item_type)
              {
                  case 1:
                  {
                     total_lot_qty += JsonObject['lotapp_details'][0].oqclotapp_runcard_details[$i].runcard_details.prod_runcard_station_many_details[0].qty_output;
                      break;
                  }
                  case 2:
                  {
                      total_lot_qty += JsonObject['lotapp_details'][0].oqclotapp_runcard_details[$i].rework_details.defect_escalation_station_many_details[0].qty_good;
                      break;
                  }
                  default:
                  {
                      total_lot_qty = 0;
                      break;
                  }
              }               
          }
          
          let lotapp_details = 
          {
            lotapp_id: JsonObject['lotapp_details'][0].id,
            lotapp_no: JsonObject['lotapp_details'][0].oqc_lotapp_id,
            lot_qty: total_lot_qty
          }


          if(array_lots.some(lots => lots.lotapp_id === lotapp_details.lotapp_id))
          {
            toastr.error('OQC Lot App Already Added!');
          }
          else
          {
            let total_in_array = 0;

            for(let n = 0; n < array_lots.length; n++)
            {
              total_in_array += array_lots[n]['lot_qty'];
            }

            if(total_in_array > pack_qty)
            {
              toastr.error('Lots are greater than boxing quantity!');
            }
            else
            {
              array_lots.push(lotapp_details);
              dt_packing_confirmation_lots.draw();
              toastr.success('Lots Added to Packing Inspection!');
            }
          }       

        } 
        else if(JsonObject['result'] == 2)
        {
          toastr.error('OQC Lot Application is Rejected!');
        }
        else
        {
          toastr.error('Lot Number Not Found!');
        }
      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
  });
}

function LoadPackingQuantity(current_series)
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
            let lot_qty = JsonObject['device_details'][0].ship_boxing;

            $('#add_packing_confirmation_pack_qty').val(lot_qty);
          }
          else
          {
            toastr.error('Error loading Device Details!');
          }          
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function RemovePackingConfirmationLot(array_lotapps, lotapp_id)
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

  for(let i = 0; i < array_lotapps.length; i++)
  {
    if(array_lotapps[i]['lotapp_id'] == lotapp_id)
    {
      array_lotapps.splice(i,1);
    }
  }

  dt_packing_confirmation_lots.draw();
  toastr.info('Removed Lot Application!');
}

function SubmitConfirmationLots(array_lots)
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

      url: "submit_confirmation_lots",
      method: "post",
      data: $('#formPackingConfirmation').serialize() + "&array_lots=" + JSON.stringify(array_lots),
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          $('#modalPackingConfirmation').modal('hide');
          $('#formPackingConfirmation')[0].reset();

          array_lots = [];

          dt_packing_inspection.draw();

          toastr.success('Packing Confirmation Saved!');
        }
        else
        {
          toastr.error('Error Saving Packing Confirmation!');
        }          
      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }



    });
}

function SubmitPrelimInspection2()
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

      url: "submit_prelim_inspection2",
      method: "post",
      data: $('#formPrelimInspection').serialize(),
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          $('#modalPreliminaryInspection').modal('hide');
          $('#formPrelimInspection')[0].reset();

          dt_packing_inspection.draw();

          toastr.success('Preliminary Inspection Saved!');
        }
        else if(JsonObject['result'] == 2)
        {
          toastr.error('Employee Details not Found!');
        }
        else
        {
          toastr.error('Error Saving Packing Confirmation!');
        }          
      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function LoadPreliminaryInspectionDetails(packing_id)
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

      url: "load_packing_inspection_details",
      method: "get",
      data:
      {
        packing_id: packing_id
      },
      dataType: "json",
       beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          let packing_id = JsonObject['packing_details'][0].id;
          let packing_code = JsonObject['packing_details'][0].packing_code;

          $('#packing_code_id').val(packing_id);
          $('#packing_code').val(packing_code);
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

function LoadFinalPackingInspectionDetails2(packing_id)
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

      url: "load_packing_inspection_details",
      method: "get",
      data:
      {
        packing_id: packing_id
      },
      dataType: "json",
       beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          let packing_id = JsonObject['packing_details'][0].id;
          let packing_code = JsonObject['packing_details'][0].packing_code;

          $('#final_packing_code_id').val(packing_id);
          $('#final_packing_code').val(packing_code);
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

function SubmitFinalPackingInspection2()
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

      url: "submit_final_packing_inspection2",
      method: "post",
      data: $('#formFinalInspection').serialize(),
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          $('#modalFinalInspection').modal('hide');
          $('#formFinalInspection')[0].reset();

          dt_packing_inspection.draw();

          toastr.success('Final Inspection Saved!');
        }
        else if(JsonObject['result'] == 2)
        {
          toastr.error('Employee Details not Found!');
        }
        else
        {
          toastr.error('Error Saving Packing Confirmation!');
        }          
      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function ValidatePackopConformance(operator_id)
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

      url: "load_packing_operator_details",
      method: "get",
      data:
      {
        operator_id: operator_id
      },
      dataType: "json",
       beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          let packop_id = JsonObject['packop_details'][0].id;
          let packop_name = JsonObject['packop_details'][0].name;

          $('#packop_conformance_id').val(packop_id);
          $('#packop_conformance_name').val(packop_name);
          $('#packop_conformance_name').removeClass('is-invalid');
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

//-----------------------------------------------------------------

function SearchPackingConfirmationLot(po_num, lot_number, arrPCL)
{
  //Alert
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
    url: "search_packing_confirmation_lot",
    method: "get",
    data:
    {
      po_num: po_num,
      lot_number: lot_number
    },
    dataType: "json",
    beforeSend: function(){

    },
    success: function(JsonObject){

      if(JsonObject['result'] == 1)
      {
        let lot_num = JsonObject['lot_details'][0].lot_batch_no;
        let lot_qty = JsonObject['lot_details'][0].oqclotapp_details.lot_qty;

        let lot_to_push = {

          arr_ctr: arrPCL.length,
          lot_num: lot_num,
          lot_qty: lot_qty

        }

        if(!arrPCL.includes(lot_to_push))
        {
          arrPCL.push(lot_to_push); 
        }

        console.log(arrPCL);

        dt_packing_confirmation_lots.draw();
      }
      else
      {
        toastr.error('Lot Number Not Found!');
      }
    },
    error: function(data, xhr, status){
      toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
    }
  });
}

function SubmitPackingConfirmation(arrLots)
{
  //Alert
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

      url: "submit_packing_confirmation",
      method: "post",
      data: $('#formPackingConfirmation').serialize() + "&arrLots=" + JSON.stringify(arrLots),
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          $('#formPackingConfirmation')[0].reset();
          $('#modalAddPackingConfirmation').modal('hide');

          dt_packing_inspection.draw();

          toastr.success('Master Packing Code Created!');

          arrLots = [];     
        }
        else
        {
          toastr.error('Packing Confirmation Failed!');
        }
        
      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function LoadPackingInspectionDetails(device_code, device_name)
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

      url: "load_packing_inspection_details",
      method: "get",
      data: 
      {
        device_code: device_code
      },
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          let po_num = JsonObject['inspection_details'][0].po_num;
          let device_code = JsonObject['inspection_details'][0].device_code;
          let box_qty = JsonObject['inspection_details'][0].total_lot_qty;

          $('#packing_inspection_po_num').val(po_num);
          $('#packing_inspection_device_code').val(device_code);
          $('#packing_inspection_device_name').val(device_name);
          $('#packing_inspection_box_qty').val(box_qty);

          dt_inspection_lots.draw();
        }
        else
        {
          toastr.error('Loading Failed!');
        }

      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function SubmitPackingInspection()
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

      url: "submit_packing_inspection",
      method: "post",
      data: $('#formAddPackingInspection').serialize(),
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
           $('#formAddPackingInspection')[0].reset();
           $('#modalPackingInspection').modal('hide');

           dt_packing_inspection.draw();
        }
        else if(JsonObject['result'] == 2)
        {
          toastr.error('ID Scanned is not OQC Inspector!');
        }
        else
        {
          toastr.error('Saving Error Failed!');
        }

      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function LoadFinalPackingInspectionDetails(device_code, device_name)
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

      url: "load_final_packing_inspection_details",
      method: "get",
      data: 
      {
        device_code: device_code
      },
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          let po_num = JsonObject['inspection_details'][0].po_num;
          let device_code = JsonObject['inspection_details'][0].device_code;
          let box_qty = JsonObject['inspection_details'][0].total_lot_qty;

          $('#final_packing_inspection_po_num').val(po_num);
          $('#final_packing_inspection_device_code').val(device_code);
          $('#final_packing_inspection_device_name').val(device_name);
          $('#final_packing_inspection_box_qty').val(box_qty);

          dt_final_inspection_lots.draw();
        }
        else
        {
          toastr.error('Loading Failed!');
        }

      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function LoadViewInspectionHistory(device_code,device_name)
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

      url: "load_final_packing_inspection_details",
      method: "get",
      data: 
      {
        device_code: device_code
      },
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          let po_num = JsonObject['inspection_details'][0].po_num;
          let device_code = JsonObject['inspection_details'][0].device_code;
          let box_qty = JsonObject['inspection_details'][0].total_lot_qty;

          $('#view_packing_inspection_po_num').val(po_num);
          $('#view_packing_inspection_device_code').val(device_code);
          $('#view_packing_inspection_device_name').val(device_name);
          $('#view_packing_inspection_box_qty').val(box_qty);

          dt_view_inspection_lots.draw();
        }
        else
        {
          toastr.error('Loading Failed!');
        }

      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function CheckPackingOperator(emp_id)
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

      url: "check_packing_operator",
      method: "get",
      data: 
      {
        emp_id: emp_id
      },
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          $('#final_packing_operator_id').val(JsonObject['packing_operator'][0].employee_id);
          $('#final_packing_operator').val(JsonObject['packing_operator'][0].name)

          toastr.success('Packing Operator Added!');
        }
        else
        {
          toastr.error('Packing Operator ID not found!');
        }

      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function SubmitFinalPackingInspection()
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

      url: "submit_final_packing_inspection",
      method: "post",
      data: $('#formFinalPackingInspection').serialize(),
      dataType: "json",
       beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          $('#modalFinalPackingInspection').modal('hide');
          $('#formFinalPackingInspection')[0].reset();

          toastr.success('Final QC Packing Inspection Complete!');

          dt_packing_inspection.draw();
        }
        else
        {
          toastr.error('Saving Error!');
        }

      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function CheckPackingInspector(emp_id)
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

      url: "check_packing_inspector",
      method: "get",
      data: 
      {
        emp_id: emp_id
      },
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          $('#final_packing_inspector_id').val(JsonObject['packing_inspector'][0].employee_id);
          $('#final_packing_inspector_name').val(JsonObject['packing_inspector'][0].name)

          toastr.success('Packing Inspector Added!');
        }
        else
        {
          toastr.error('Packing Inspector ID not found!');
        }

      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

/*function SearchPackingOperator(emp_id)
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

      url: "search_packing_operator",
      method: "get",
      data: 
      {
        emp_id: emp_id
      },
      dataType: "json",
      beforeSend: function(){

      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          let packing_operator = JsonObject['packing_operator'][0].name;
          let packing_operator_id = JsonObject['packing_operator'][0].id;

          $('#final_packing_operator').val(po_num);
        }
        else
        {
          toastr.error('Packing Operator ID not found!');
        }

      },
      error: function(data, xhr, status){
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}*/

//---------------------------------------------------------------------

function SubmitPackin(){

  //Alert
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
        url: "submit_packin",
        method: "post",
        data: $('#formPackingInspector').serialize(),
        dataType: "json",
        beforeSend: function(){

        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalPackingInspector").modal('hide');
            	$("#formAddPackingInspection")[0].reset();

            	  dt_packinginspector.draw();
                toastr.success('Packing Inspector Form was succesfully saved!');
                
            }
            else if(JsonObject['result'] == 2)
            {
              toastr.error('Scanned ID Does not have OQC Stamp!');
            }


            else{
                toastr.error('Saving Packing Inspector Form Failed!');
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

  function RetrieveOQCName(oqclotapp_id, mat_sub)
  {
     $.ajax({
      url: "retrieve_oqc_name",
      method: "get",
      data: {

        oqclotapp_id: oqclotapp_id,
        mat_sub: mat_sub

      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
          if(JsonObject['oqcins'].length > 0) 
          {
              $("#id_OQCAcceptedBy").val(JsonObject['oqcins'][0].user_details.name);

              $("#id_oqc_name").val(JsonObject['oqcins'][0].user_details.id);
          }
      },
      error: function(data, xhr, status){
          console.log()
      }
    });  
   }

   function RetrievePackCodeFromPackop(po_num, batch, mat_sub, station)
  {
     $.ajax({
      url: "retrieve_pack_code_from_packop",
      method: "get",
      data: {

        po_num: po_num,
        batch: batch,
        mat_sub: mat_sub,
        station: station

      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
          if(JsonObject['packop'].length > 0) 
          {
              $("#id_packin_pack_code_no").val(JsonObject['packop'][0].pack_code_no);
              $('#id_link_packcode_packin').val(JsonObject['packop'][0].pack_code_no);
              $('#id_link_packcode_shipop').val(JsonObject['packop'][0].pack_code_no);
              $('#id_link_packcode_shipin').val(JsonObject['packop'][0].pack_code_no);

              if(station == 1)
              {
                PackinLinkToPackingCode(JsonObject['packop'][0].pack_code_no);
              }
              else if(station == 2)
              {
                ShipopLinkToPackingCode(JsonObject['packop'][0].pack_code_no);
              }
              else if(station == 3)
              {
                ShipinLinkToPackingCode(JsonObject['packop'][0].pack_code_no);
              }
          }
      },
      error: function(data, xhr, status){
          console.log()
      }
    });  
   }

  function RetrievePackopDetails(po_num, batch, mat_sub)
  {
    $.ajax({
      url: "retrieve_packop_details",
      method: "get",
      data: {
        po_num: po_num,
        batch: batch,
        mat_sub: mat_sub
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
          if(JsonObject['packop'].length > 0) 
          {
              $("#id_PackopPackingType").val(JsonObject['packop'][0].packop_packing_type);

              $("#id_PackopUnitCondition").val(JsonObject['packop'][0].packop_unit_condition);

              $("#id_myponum").val(JsonObject['packop'][0].po_no);
              $("#id_mybatch").val(JsonObject['packop'][0].lot_batch_no);
              $("#id_mysub").val(JsonObject['packop'][0].submission);

              $("#id_131").val(JsonObject['packop'][0].radio1_3_1);

              $("#id_132").val(JsonObject['packop'][0].radio1_3_2);

              $("#id_133").val(JsonObject['packop'][0].radio1_3_3);

              $("#id_134").val(JsonObject['packop'][0].radio1_3_4);

              $("#id_135").val(JsonObject['packop'][0].total_num_reels);
              $("#id_225").val(JsonObject['packop'][0].total_num_reels);

              $("#id_PackingCode").val(JsonObject['packop'][0].pack_code_no);
              $("#id_packin_pack_code_no").val(JsonObject['packop'][0].total_num_reels);
             
              $("#id_151").val(JsonObject['packop'][0].radio1_5_1);
              $("#id_152").val(JsonObject['packop'][0].radio1_5_2);
              $("#id_153").val(JsonObject['packop'][0].radio1_5_3);
              $("#id_154").val(JsonObject['packop'][0].radio1_5_4);
              $("#id_155").val(JsonObject['packop'][0].radio1_5_5);
              $("#id_156").val(JsonObject['packop'][0].radio1_5_6);

              $("#id_judgement").val(JsonObject['packop'][0].oqc_judgement);

              $("#id_PackopApprovedName").val(JsonObject['packop'][0].user_details.name);

              $("#id_PackopApprovedDate").val(JsonObject['packop'][0].updated_at);
          }
      },
      error: function(data, xhr, status){
          console.log()
      }
    });
  }

  function PackinLinkToPackingCode(pack_code_packin)
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
      url: "link_to_packing_code_packin",
      method: "get",
      data: {
        pack_code_packin: pack_code_packin
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
        if(JsonObject['result'] == 1)
        {
          $('#id_221').val(JsonObject['link_packin'].radio2_2_1);
          $('#id_222').val(JsonObject['link_packin'].radio2_2_2);
          $('#id_223').val(JsonObject['link_packin'].radio2_2_3);
          $('#id_224').val(JsonObject['link_packin'].radio2_2_4);

          $('#id_23').val(JsonObject['link_packin'].pac_man_doc_comp);    
          $('#id_24').val(JsonObject['link_packin'].accessories);

          $('#id_packin_pack_code_no').val(JsonObject['link_packin'].pack_code_no);

          $('#id_packin_judgement').val(JsonObject['link_packin'].oqc_judgement);

          //$('#id_link_packcode_packin').val('');

          //DISABLE IF LINKED
          $('#id_221').attr('readonly','readonly');
          $('#id_222').attr('readonly','readonly');
          $('#id_223').attr('readonly','readonly');
          $('#id_224').attr('readonly','readonly');
          $('#id_225').attr('readonly','readonly');
          $('#id_23').attr('readonly','readonly');    
          $('#id_24').attr('readonly','readonly');
          $('#id_link_packcode_packin').attr('readonly','readonly');


          $('#id_btn_link_packcode_packin').attr('disabled','disabled');

          //unclickable
          $('#id_221').css("pointer-events","none");
          $('#id_222').css("pointer-events","none");
          $('#id_223').css("pointer-events","none");
          $('#id_224').css("pointer-events","none");
          $('#id_225').css("pointer-events","none");
          $('#id_23').css("pointer-events","none");    
          $('#id_24').css("pointer-events","none");

          toastr.success('Packing Code <strong>' + pack_code_packin + '</strong> Data Linked! Scan your ID to Finish.');
          $('#btn_save_packin').click();
        }
        else if(JsonObject['result'] == 2)
        {
          //toastr.warning('Packing Code <strong>' + $pack + '</strong> is not available!');
        }
      },
     error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
  }



