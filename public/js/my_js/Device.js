// Add Device
function AddDevice(){
    const data = $('#formAddDevice').serialize();
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
        url: "add_device",
        method: "post",
        data: data,
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddDeviceIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                InsertToTSPPTSDeviceTable(data, JsonObject['created_by']);
            	$("#modalAddDevice").modal('hide');
            	$("#formAddDevice")[0].reset();

            	dataTableDevices.draw();
              toastr.success('Device was succesfully saved!');
            }
            else{
                toastr.error('Saving Device Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddDeviceName").removeClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', '');
                }
                else{
                    $("#txtAddDeviceName").addClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddDeviceBarcode").removeClass('is-invalid');
                    $("#txtAddDeviceBarcode").attr('title', '');
                }
                else{
                    $("#txtAddDeviceBarcode").addClass('is-invalid');
                    $("#txtAddDeviceBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['mrp_no'] === undefined){
                    $("#txtAddDeviceMRPNo").removeClass('is-invalid');
                    $("#txtAddDeviceMRPNo").attr('title', '');
                }
                else{
                    $("#txtAddDeviceMRPNo").addClass('is-invalid');
                    $("#txtAddDeviceMRPNo").attr('title', JsonObject['error']['mrp_no']);
                }

                if(JsonObject['error']['boxing'] === undefined){
                    $("#txtAddDeviceBoxing").removeClass('is-invalid');
                    $("#txtAddDeviceBoxing").attr('title', '');
                }
                else{
                    $("#txtAddDeviceBoxing").addClass('is-invalid');
                    $("#txtAddDeviceBoxing").attr('title', JsonObject['error']['boxing']);
                }

                if(JsonObject['error']['ship_boxing'] === undefined){
                    $("#txtAddDeviceShipBoxing").removeClass('is-invalid');
                    $("#txtAddDeviceShipBoxing").attr('title', '');
                }
                else{
                    $("#txtAddDeviceShipBoxing").addClass('is-invalid');
                    $("#txtAddDeviceShipBoxing").attr('title', JsonObject['error']['ship_boxing']);
                }

                if(JsonObject['error']['pin_popu'] === undefined){
                    $("#txtAddDevicePinPopu").removeClass('is-invalid');
                    $("#txtAddDevicePinPopu").attr('title', '');
                }
                else{
                    $("#txtAddDevicePinPopu").addClass('is-invalid');
                    $("#txtAddDevicePinPopu").attr('title', JsonObject['error']['pin_popu']);
                }
            }

            $("#iBtnAddDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").removeAttr('disabled');
            $("#iBtnAddDeviceIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").removeAttr('disabled');
            $("#iBtnAddDeviceIcon").addClass('fa fa-check');
        }
    });
}

function InsertToTSPPTSDeviceTable(serializedData, created_by = null){
    data = 'created_by='+ created_by + '&' + serializedData;
    console.log('data: ',data);
	$.ajax({
		'data'      : data,
		'type'      : 'get',
		'dataType'  : 'json',
		'url'       : 'http://rapidx/rapidx-api/api/insert_to_ts_ppts_device_table.php',
		success	: function(result){
		}
	});
}

function UpdateToTSPPTSDeviceTable(serializedData, last_updated_by, id){
    data = 'last_updated_by='+ last_updated_by + '&' + 'id='+ id + '&'+ serializedData;
    console.log('data: ',data);
	$.ajax({
		'data'      : data,
		'type'      : 'get',
		'dataType'  : 'json',
		'url'       : 'http://rapidx/rapidx-api/api/update_to_ts_ppts_device_table.php',
		success	: function(result){
		}
	});
}

// Edit Device
function GetDeviceByIdToEdit(deviceId){
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
        url: "get_device_by_id",
        method: "get",
        data: {
            device_id: deviceId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditDeviceName").val("");
            $("#txtEditDeviceBarcode").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['device'];
            if(result.length > 0){
                $("#txtEditDeviceName").val(result[0].name);
                $("#textEditPoNo").val(result[0].po_no);
                $("#txtEditDeviceBarcode").val(result[0].barcode);
                $("#txtEditDeviceMRPNo").val(result[0].mrp_no);
                $("#txtEditDeviceBoxing").val(result[0].boxing);
                $("#txtEditDeviceShipBoxing").val(result[0].ship_boxing);
                $("#txtEditDevicePinPopu").val(result[0].pin_popu);
                $("#txtEditDeviceHuaweiPN").val(result[0].huawei_p_n);
                $("#txtEditDeviceLotNoMachineCode").val(result[0].lot_no_machine_code);
                $("#txtEditPOI").val(result[0].process);
                $("#txtEditType").val(result[0].type);
            }
            else{
                toastr.warning('No Device Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditDevice(){
    const data = $('#formEditDevice').serialize();
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
        url: "edit_device",
        method: "post",
        data: data,
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditDeviceIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                UpdateToTSPPTSDeviceTable(data, JsonObject['last_updated_by'], JsonObject['id']);
                $("#modalEditDevice").modal('hide');
                $("#formEditDevice")[0].reset();

                dataTableDevices.draw();
                toastr.success('Device was succesfully saved!');
            }
            else{
                toastr.error('Updating Device Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditDeviceName").removeClass('is-invalid');
                    $("#txtEditDeviceName").attr('title', '');
                }
                else{
                    $("#txtEditDeviceName").addClass('is-invalid');
                    $("#txtEditDeviceName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtEditDeviceBarcode").removeClass('is-invalid');
                    $("#txtEditDeviceBarcode").attr('title', '');
                }
                else{
                    $("#txtEditDeviceBarcode").addClass('is-invalid');
                    $("#txtEditDeviceBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['mrp_no'] === undefined){
                    $("#txtEditDeviceMRPNo").removeClass('is-invalid');
                    $("#txtEditDeviceMRPNo").attr('title', '');
                }
                else{
                    $("#txtEditDeviceMRPNo").addClass('is-invalid');
                    $("#txtEditDeviceMRPNo").attr('title', JsonObject['error']['mrp_no']);
                }

                if(JsonObject['error']['boxing'] === undefined){
                    $("#txtEditDeviceBoxing").removeClass('is-invalid');
                    $("#txtEditDeviceBoxing").attr('title', '');
                }
                else{
                    $("#txtEditDeviceBoxing").addClass('is-invalid');
                    $("#txtEditDeviceBoxing").attr('title', JsonObject['error']['boxing']);
                }

                if(JsonObject['error']['ship_boxing'] === undefined){
                    $("#txtEditDeviceShipBoxing").removeClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', '');
                }
                else{
                    $("#txtEditDeviceShipBoxing").addClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', JsonObject['error']['ship_boxing']);
                }

                if(JsonObject['error']['pin_popu'] === undefined){
                    $("#txtEditDevicePinPopu").removeClass('is-invalid');
                    $("#txtEditDevicePinPopu").attr('title', '');
                }
                else{
                    $("#txtEditDevicePinPopu").addClass('is-invalid');
                    $("#txtEditDevicePinPopu").attr('title', JsonObject['error']['pin_popu']);
                }
            }

            $("#iBtnEditDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").removeAttr('disabled');
            $("#iBtnEditDeviceIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").removeAttr('disabled');
            $("#iBtnEditDeviceIcon").addClass('fa fa-check');
        }
    });
}

// Change Device Status
function ChangeDeviceStatus(){
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
        url: "change_device_stat",
        method: "post",
        data: $('#formChangeDeviceStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeDeviceStat").modal('hide');
                $("#formChangeDeviceStat")[0].reset();

                dataTableDevices.draw();

                if($("#txtChangeDeviceStatDeviceStat").val() == 1){
                    toastr.success('Device Activation Success!');
                }
                else{
                    toastr.success('Device Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeDeviceStatDeviceStat").val() == 1){
                    toastr.error('Device Activation Failed!');
                }
                else{
                    toastr.error('Device Deactivation Failed!');
                }
            }

            $("#iBtnChangeDeviceStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").removeAttr('disabled');
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeDeviceStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").removeAttr('disabled');
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-check');
        }
    });
}
