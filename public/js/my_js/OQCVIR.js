// Add AddOQCVIR
function AddOQCVIR(){
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
        url: "add_oqc_vir",
        method: "post",
        data: $('#formOQCVIR').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalOQCVIR").modal('hide');
            	$("#formOQCVIR")[0].reset();

            	dataTableOQCVIR.draw();
              toastr.success('Visual Inspection Result was succesfully saved!');
            } else if (JsonObject['result'] == '2'){
                toastr.error('Invalid or No registered Employee No. for inspector!');                
            } else if (JsonObject['result'] == '3'){
                toastr.error('Max of 3rd submission only!');                
            } else if (JsonObject['result'] == '4'){
              $("#modalOQCVIR").modal('hide');

              dataTableOQCVIR.draw();
              // toastr.error('Packing Code is ready to Print!');  
              // $("#modalAlertPackingCode").modal('show');   

              // console.log( $("#hidden_OQCLotApp_id").val() );
              get_insp_result_by_id ( $("#hidden_OQCLotApp_id").val() );

              $("#formOQCVIR")[0].reset();

            } else{
                toastr.error('Saving visual inspection result failed!');                
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}


//--------------------------FUNCTION FOR TS OQCVIR-------------------------------//
function ViewStartInspection(batch)
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
        url: "view_start_inspection",
        method: "get",
        data: 
        {
          batch: batch
        },
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){

          let device = $('#id_device_name').val();

          /*$('#id_start_hidden_id').val(JsonObject['oqclotapp'][0].id);*/
          $('#id_start_po').val(JsonObject['oqclotapp'][0].po_no);
          $('#id_start_lotbatch').val(JsonObject['oqclotapp'][0].runcard_no);
          $('#id_start_series').val(device);

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });

}

function ViewOQCInspectionTS(batch)
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
        url: "view_start_inspection",
        method: "get",
        data: 
        {
          batch: batch
        },
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){

          let device = $('#id_device_name').val();


          $('#id_currentPONo').val(JsonObject['oqclotapp'][0].po_no);
          $('#id_lotbatch_no').val(JsonObject['oqclotapp'][0].runcard_no);
          $('#id_seriesName').val(device);
          $('#id_totalLotQty').val(JsonObject['oqclotapp'][0].lot_qty);

          //$('#id_oqc_sample_size').attr('max',(JsonObject['oqclotapp'][0].lot_qty);

          $('#id_start_datetime').val(JsonObject['oqcvir'][0].insp_stime);

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });

}

function SubmitStartInspection()
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
        url: "add_start_visual_inspection",
        method: "post",
        data: $('#formStartVisualInspection').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            $('#formStartVisualInspection')[0].reset();
            $('#modalStartInspection').modal('hide');

            toastr.success('You can now proceed with the inspection!');

            dt_oqcvir.draw();
          }
          else
          {
            toastr.error('Saving Failed!');
          }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function SubmitOQCInspection()
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
        url: "submit_inspection_result",
        method: "post",
        data: $('#formAddOQCVIR').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){

          if(JsonObject['result'] == 1)
          {
            $('#formAddOQCVIR')[0].reset();
            $('#modalOQCVIR').modal('hide');

            toastr.success('Visual Inspection Result Submitted!');

            dt_oqcvir.draw();
          }
          else
          {
            toastr.error('Saving Failed!');
          }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}


//---------------------------------NEW OQC VIR FUNCTION 2/27 ------------------------

function LoadStartInspectionDetails(oqc_lotapp_id, device_name)
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

      url: "load_new_lotapp_details",
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
          let po_num = JsonObject['lotapp_details'][0].po_num;
          let lotapp_id = JsonObject['lotapp_details'][0].oqc_lotapp_id;
          let id = JsonObject['lotapp_details'][0].id;

          $('#id_start_hidden_id').val(id);
          $('#id_start_po').val(po_num);
          $('#id_start_lotapp').val(lotapp_id);
          $('#id_start_series').val(device_name);
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

function SubmitNewStartInspection()
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

      url: "submit_start_inspection",
      method: "post",
      data: $('#formStartInspection').serialize(),
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        {
          $('#modalStartInspection').modal('hide');
          $('#formStartInspection')[0].reset();

          dt_oqcvir.draw();
          toastr.info('Inspection Started!');
        }
        else if(JsonObject['result'] == 2)
        {
          toastr.error('Employee ID Not Found!');
        }
        else if(JsonObject['result'] == 3)
        {
          toastr.error('Employee is not an Inspector!');
        }

      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function LoadOqcDetails(oqc_lotapp_id, device_name)
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

      url: "load_new_lotapp_details",
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
          let po_num = JsonObject['lotapp_details'][0].po_num;
          let lotapp_id = JsonObject['lotapp_details'][0].oqc_lotapp_id;

          let total_lot_qty = 0;

          let objects = JsonObject['lotapp_details'][0].oqclotapp_runcard_details;
/*
          for(let i = 0; i < objects.length; i++)
          {
            total_lot_qty += JsonObject['lotapp_details'][0].oqclotapp_runcard_details[i].runcard_details.prod_runcard_station_many_details[0].qty_output;
          }
*/
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

          let start_time = JsonObject['lotapp_details'][0].oqcvir_details[0].created_at;


          $('#id_lotapp_hidden_id').val(JsonObject['lotapp_details'][0].id)
          $('#id_current_pono').val(po_num);
          $('#id_lotapp_no').val(lotapp_id);
          $('#id_seriesName').val(device_name);
          $('#id_totalLotQty').val(total_lot_qty);
          $('#id_start_datetime').val(start_time);

          $('#id_oqc_sample_size').prop('max',total_lot_qty);
          $('#id_ok_qty').prop('max',total_lot_qty);
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

function SearchInspector(employee_id)
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

      url: "search_inspector",
      method: "get",
      data:
      {
        employee_id: employee_id
      },
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

         if(JsonObject['result'] == 1)
        {
          let inspector_id = JsonObject['inspector_details'][0].id;
          let inspector_name = JsonObject['inspector_details'][0].name;

          $('#id_oqc_inspector_id').val(inspector_id);
          $('#id_oqc_inspector_name').val(inspector_name);

          toastr.info('Inspector Added!');
        }
        else if(JsonObject['result'] == 2)
        {
          toastr.error('Employee ID Not Found!');
        }
        else if(JsonObject['result'] == 3)
        {
          toastr.error('Employee is not an Inspector!');
        }

      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }

    });
}

function SubmitNewOQCVIR()
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

      url: "submit_oqc_vir",
      method: "post",
      data: $('#formAddOQCVIR').serialize(),
      dataType: "json",
      beforeSend: function(){
      //
      },
      success: function(JsonObject){

        if(JsonObject['result'] == 1)
        { 
          if($('#id_result').val() == '1')
          {
            toastr.success('Finished Inspection! Lots are ready for Packing');
          }
          else
          {
            toastr.warning('Finished Inspection! Will be waiting for 2nd submission from Production');
          }

          $('#modalOQCVIR').modal('hide');
          $('#formAddOQCVIR')[0].reset();

          dt_oqcvir.draw();
          
        }
        else if(JsonObject['result'] == 2)
        {
          toastr.error('Employee ID Not Found!');
        }
        else if(JsonObject['result'] == 3)
        {
          toastr.error('Employee is not an Inspector!');
        }
      },
      error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
}
