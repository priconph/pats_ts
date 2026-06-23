// Add Material
function AddMaterial(){
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
        url: "add_material",
        method: "post",
        data: $('#formAddMaterial').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddMaterialIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddMaterial").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddMaterial").modal('hide');
            	$("#formAddMaterial")[0].reset();

            	dataTableMaterials.draw();
              toastr.success('Material was succesfully saved!');
            }
            else{
                toastr.error('Saving Material Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddMaterialName").removeClass('is-invalid');
                    $("#txtAddMaterialName").attr('title', '');
                }
                else{
                    $("#txtAddMaterialName").addClass('is-invalid');
                    $("#txtAddMaterialName").attr('title', JsonObject['error']['name']);
                }

                // if(JsonObject['error']['barcode'] === undefined){
                //     $("#txtAddMaterialBarcode").removeClass('is-invalid');
                //     $("#txtAddMaterialBarcode").attr('title', '');
                // }
                // else{
                //     $("#txtAddMaterialBarcode").addClass('is-invalid');
                //     $("#txtAddMaterialBarcode").attr('title', JsonObject['error']['barcode']);
                // }
            }

            $("#iBtnAddMaterialIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMaterial").removeAttr('disabled');
            $("#iBtnAddMaterialIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddMaterialIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMaterial").removeAttr('disabled');
            $("#iBtnAddMaterialIcon").addClass('fa fa-check');
        }
    });
}

// Edit Material
function GetMaterialByIdToEdit(materialId){
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
        url: "get_material_by_id",
        method: "get",
        data: {
            material_id: materialId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditMaterialName").val("");
            // $("#txtEditMaterialBarcode").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['material'];
            if(result.length > 0){
                $("#txtEditMaterialName").val(result[0].name);
                // $("#txtEditMaterialBarcode").val(result[0].barcode);
            }
            else{
                toastr.warning('No Material Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditMaterial(){
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
        url: "edit_material",
        method: "post",
        data: $('#formEditMaterial').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditMaterialIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditMaterial").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditMaterial").modal('hide');
                $("#formEditMaterial")[0].reset();

                dataTableMaterials.draw();
                toastr.success('Material was succesfully saved!');
            }
            else{
                toastr.error('Updating Material Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditMaterialName").removeClass('is-invalid');
                    $("#txtEditMaterialName").attr('title', '');
                }
                else{
                    $("#txtEditMaterialName").addClass('is-invalid');
                    $("#txtEditMaterialName").attr('title', JsonObject['error']['name']);
                }

                // if(JsonObject['error']['barcode'] === undefined){
                //     $("#txtEditMaterialBarcode").removeClass('is-invalid');
                //     $("#txtEditMaterialBarcode").attr('title', '');
                // }
                // else{
                //     $("#txtEditMaterialBarcode").addClass('is-invalid');
                //     $("#txtEditMaterialBarcode").attr('title', JsonObject['error']['barcode']);
                // }
            }

            $("#iBtnEditMaterialIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMaterial").removeAttr('disabled');
            $("#iBtnEditMaterialIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditMaterialIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMaterial").removeAttr('disabled');
            $("#iBtnEditMaterialIcon").addClass('fa fa-check');
        }
    });
}

// Change Material Status
function ChangeMaterialStatus(){
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
        url: "change_material_stat",
        method: "post",
        data: $('#formChangeMaterialStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeMaterialStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeMaterialStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeMaterialStat").modal('hide');
                $("#formChangeMaterialStat")[0].reset();

                dataTableMaterials.draw();

                if($("#txtChangeMaterialStatMaterialStat").val() == 1){
                    toastr.success('Material Activation Success!');
                }
                else{
                    toastr.success('Material Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeMaterialStatMaterialStat").val() == 1){
                    toastr.error('Material Activation Failed!');
                }
                else{
                    toastr.error('Material Deactivation Failed!');
                }
            }

            $("#iBtnChangeMaterialStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMaterialStat").removeAttr('disabled');
            $("#iBtnChangeMaterialStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeMaterialStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMaterialStat").removeAttr('disabled');
            $("#iBtnChangeMaterialStatIcon").addClass('fa fa-check');
        }
    });
}

function PrintBatchMaterial(selectedMaterials){
  // console.log(selectedMaterials);
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
        url: "get_material_by_batch",
        method: "get",
        data: {
          material_id: selectedMaterials
        },
        dataType: "json",
        beforeSend: function(){
            // $("#iBtnEditUserIcon").addClass('fa fa-spinner fa-pulse');
            // $("#btnEditUser").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['materials'].length > 0){

                  popup = window.open();
                  let content = '';
                  content += '<html>';
                  content += '<head>';
                    content += '<title></title>';
                    content += '<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">';
                    content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>';
                    content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>';
                    content += '<style type="text/css">';
                      content += '.divBorder{';
                        content += 'border: 2px solid black;';
                              content += 'min-width: 225px;';
                              content += 'margin-top: 10px;';
                      content += '}';
                    content += '</style>';
                  content += '</head>';
                  content += '<body>';
                    content += '<div class="container-fluid">';
                      content += '<div class="row">';

                        for(let index = 1; index <= JsonObject['materials'].length; index++) {
                          content += '<div class="col-sm-4">';
                            content += '<div class="divBorder">';
                              // content += '<center>';
                                content += '<table>';
                                  content += '<tr>';
                                    content += '<td>';
                                      // content += '<center>';
                                        content += '<img src="' + JsonObject['qrcode'][index - 1] + '" style="max-width: 120px;">';
                                      // content += '</center>';
                                    content += '</td>';
                                    content += '<td>';
                                      content += '<label style="text-align: left; font-weight: bold; font-family: Arial; font-size: 18px;"></label>';
                                      content += '<br>';
                                      content += '<label style="text-align: left; font-family: Arial Narrow; font-size: 18px;">' + JsonObject['materials'][index - 1].name + '</label>';
                                    content += '</td>';
                                  content += '</tr>';
                                content += '</table>';
                              // content += '</center>';
                            content += '</div>';
                          content += '</div>';

                          // if(index % 3 == 0){
                          //   content += '<div class="col-sm-3">';
                          //   content += '</div>';
                          // }
                        }

                      content += '</div>';
                    content += '</div>';
                  content += '</body>';
                  content += '</html>';
                  popup.document.write(content);
                  // popup.focus(); //required for IE
                  // popup.print();
                  // popup.close();
            }
            else{
                // toastr.error('Failed!');
            }

            // $("#iBtnEditUserIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#btnEditUser").removeAttr('disabled');
            // $("#iBtnEditUserIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iBtnEditUserIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#btnEditUser").removeAttr('disabled');
            // $("#iBtnEditUserIcon").addClass('fa fa-check');
        }
    });
}

function GetCboMaterial(cboElement){
    let result = '<option value="" selected> N/A </option>';
    $.ajax({
        url: 'get_materials',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['materials'].length > 0){
                result = '<option value=""> N/A </option>';
                for(let index = 0; index < JsonObject['materials'].length; index++){
                    result += '<option value="' + JsonObject['materials'][index].id + '" >' + JsonObject['materials'][index].name + '</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetCboMaterialByStat(cboElement, status){
    // console.log('ako')
    let result = '<option value="0" selected disabled> -- Select Material -- </option>';
    $.ajax({
        url: 'get_material_by_stat',
        method: 'get',
        data: {
          status: status
        },
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['materials'].length > 0){
                result = '<option value="0" selected disabled> -- Select Material -- </option>';
                for(let index = 0; index < JsonObject['materials'].length; index++){
                    result += '<option value="' + JsonObject['materials'][index].id + '">' + JsonObject['materials'][index].name + '</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getMaterialKittingSakidashiList = (cboElement, deviceName, idAndValueForEdit=null) => {
    let result = '<option value="0" selected disabled> -- Select Material -- </option>';
    $.ajax({
        // url: 'getMaterialsFromRecieving_test', // 01-22-2023
        url: 'getMaterialsFromRecieving',
        method: 'get',
        data: {
          deviceName
        },
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['materials'].length > 0){
                result = '<option value="0" selected disabled> -- Select Material -- </option>';
                result = '<option value="0" >N/A</option>';
                for(let index = 0; index < JsonObject['materials'].length; index++){
                    result += '<option value="' + JsonObject['materials'][index].id + '" index="' + index + '">' + JsonObject['materials'][index].materialCode + ' - ' + JsonObject['materials'][index].material + '</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }

            cboElement.html(result);
            materialListData = JsonObject['materials']

            if( idAndValueForEdit != null )
              idAndValueForEdit.id.val(idAndValueForEdit.value).trigger('change')
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function SaveProcessFlowMaterial(materialArray){
    let frmSaveProcessFlowMaterial = $('#frmSaveProcessFlowMaterial');
    let mdlSaveProcessFlowMaterialCode = $('#mdlSaveProcessFlowMaterialCode');

    // var data = frmSaveProcessFlowMaterial.serialize(); // Old
    var data = $.param({materialArray}) + '&' + frmSaveProcessFlowMaterial.serialize();
    // console.log('data ',data)
    console.log('data ', data)
    $.ajax({
        url: 'save_process_flow_material',
        method: 'post',
        data: data,
        dataType: 'json',
        beforeSend() {
            btnSaveProcessFlow.prop('disabled', true);
            btnSaveProcessFlow.html('Saving...');
            $(".input-error", frmSaveProcessFlowMaterial).text('');
            $(".form-control", frmSaveProcessFlowMaterial).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            btnSaveProcessFlow.prop('disabled', false);
            btnSaveProcessFlow.html('Save');
            
            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSaveProcessFlowMaterial[0].reset();
                    mdlSaveProcessFlowMaterialCode.modal('hide');
                    $(".input-error", frmSaveProcessFlowMaterial).text('');
                    $(".form-control", frmSaveProcessFlowMaterial).removeClass('is-invalid');
                    dataTableMatProcess.draw();
                }
                else{
                    if(data['error'] == 'notProdn'){
                        toastr.error('User is not Production!', 'Saving Failed');
                    }
                    else if(data['error'] != null){
                        toastr.error('Saving Failed!');

                        if(data['error']['material_id'] != null){
                            $("input[name='material_id']", frmSaveProcessFlowMaterial).addClass('is-invalid');
                        }
                        else{
                            $("input[name='material_id']", frmSaveProcessFlowMaterial).removeClass('is-invalid');
                        }
                    }
                }
            }
            else{ // if session expired
                toastr.error('Session Expired!');
            }
        },
        error(xhr, data, status){
            // cnfrmLoading.close();
            btnSaveProcessFlow.prop('disabled', false);
            btnSaveProcessFlow.html('Save');
            toastr.error('Saving Failed!');
        }
    });
}

