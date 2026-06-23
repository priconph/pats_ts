// function SaveProcessFlow(materialArray){
// 	var data = $.param({materialArray}) + '&' + frmSaveProcessFlow.serialize();
//     $.ajax({
//         url: 'save_process_flow',
//         method: 'post',
//         data: data,
//         dataType: 'json',
//         beforeSend() {
//             btnSaveProcessFlow.prop('disabled', true);
//             btnSaveProcessFlow.html('Saving...');
//             $(".input-error", frmSaveProcessFlow).text('');
//             $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
//             // cnfrmLoading.open();
//         },
//         success(data){
//             btnSaveProcessFlow.prop('disabled', false);
//             btnSaveProcessFlow.html('Save');
            
//             if( data["duplicateError"] != undefined ){
//                 toastr.error(data["duplicateError"]);
//                 return
//             }
//             // cnfrmLoading.close();
//             $("input[name='step']", frmSaveProcessFlow).focus();

//             if(data['auth'] == 1){
//                 if(data['result'] == 1){
//                     toastr.success('Record Saved!');
//                     frmSaveProcessFlow[0].reset();
//                     mdlSaveProcessFlow.modal('hide');
//                     $(".input-error", frmSaveProcessFlow).text('');
//                     $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
//                     dataTableMatProcess.draw();
//                 }
//                 else{
//                     toastr.error('Saving Failed!');
//                     if(data['error'] != null){
//                         if(data['error']['step'] != null){
//                             $("input[name='step']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='step']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['process'] != null){
//                             $("input[name='process']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='process']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['material_id'] != null){
//                             $("input[name='material_id']", frmSaveProcessFlow).addClass('is-invalid');
//                             console.log('addClass')
//                         }
//                         else{
//                             $("input[name='material_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                             console.log('removeClass')
//                         }

//                         if(data['error']['material_kitting_item'] != null){
//                             $("input[name='material_kitting_item']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='material_kitting_item']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['sakidashi_item'] != null){
//                             $("input[name='sakidashi_item']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='sakidashi_item']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['machine_id'] != null){
//                             $("input[name='machine_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='machine_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['a_shift_user_id'] != null){
//                             $("input[name='a_shift_user_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='a_shift_user_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['b_shift_user_id'] != null){
//                             $("input[name='b_shift_user_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='b_shift_user_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }
//                     }
//                 }
//             }
//             else{ // if session expired
//                 // cnfrmAutoLogin.open();
//                 toastr.error('Session Expired!');
//             }
//         },
//         error(xhr, data, status){
//             // cnfrmLoading.close();
//             btnSaveProcessFlow.prop('disabled', false);
//             btnSaveProcessFlow.html('Save');
//             toastr.error('Saving Failed!');
//         }
//     });
// }

// function SaveProcessFlow(){
// 	var data = frmSaveProcessFlow.serialize();
//     $.ajax({
//         url: 'save_process_flow',
//         method: 'post',
//         data: data,
//         dataType: 'json',
//         beforeSend() {
//             btnSaveProcessFlow.prop('disabled', true);
//             btnSaveProcessFlow.html('Saving...');
//             $(".input-error", frmSaveProcessFlow).text('');
//             $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
//             // cnfrmLoading.open();
//         },
//         success(data){
//             btnSaveProcessFlow.prop('disabled', false);
//             btnSaveProcessFlow.html('Save');
            
//             if( data["duplicateError"] != undefined ){
//                 toastr.error(data["duplicateError"]);
//                 return
//             }
//             // cnfrmLoading.close();
//             $("input[name='step']", frmSaveProcessFlow).focus();

//             if(data['auth'] == 1){
//                 if(data['result'] == 1){
//                     toastr.success('Record Saved!');
//                     frmSaveProcessFlow[0].reset();
//                     mdlSaveProcessFlow.modal('hide');
//                     $(".input-error", frmSaveProcessFlow).text('');
//                     $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
//                     dataTableMatProcess.draw();
//                 }
//                 else{
//                     toastr.error('Saving Failed!');
//                     if(data['error'] != null){
//                         if(data['error']['step'] != null){
//                             $("input[name='step']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='step']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['process'] != null){
//                             $("input[name='process']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='process']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['material_id'] != null){
//                             $("input[name='material_id']", frmSaveProcessFlow).addClass('is-invalid');
//                             console.log('addClass')
//                         }
//                         else{
//                             $("input[name='material_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                             console.log('removeClass')
//                         }

//                         if(data['error']['material_kitting_item'] != null){
//                             $("input[name='material_kitting_item']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='material_kitting_item']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['sakidashi_item'] != null){
//                             $("input[name='sakidashi_item']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='sakidashi_item']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['machine_id'] != null){
//                             $("input[name='machine_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='machine_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['a_shift_user_id'] != null){
//                             $("input[name='a_shift_user_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='a_shift_user_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['b_shift_user_id'] != null){
//                             $("input[name='b_shift_user_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='b_shift_user_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }
//                     }
//                 }
//             }
//             else{ // if session expired
//                 // cnfrmAutoLogin.open();
//                 toastr.error('Session Expired!');
//             }
//         },
//         error(xhr, data, status){
//             // cnfrmLoading.close();
//             btnSaveProcessFlow.prop('disabled', false);
//             btnSaveProcessFlow.html('Save');
//             toastr.error('Saving Failed!');
//         }
//     });
// }

/**
 * Old code
 * 01-16-2023
 */
// function SaveProcessFlow(){
// 	var data = frmSaveProcessFlow.serialize();
//     $.ajax({
//         url: 'save_process_flow',
//         method: 'post',
//         data: data,
//         dataType: 'json',
//         beforeSend() {
//             btnSaveProcessFlow.prop('disabled', true);
//             btnSaveProcessFlow.html('Saving...');
//             $(".input-error", frmSaveProcessFlow).text('');
//             $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
//             // cnfrmLoading.open();
//         },
//         success(data){
//             btnSaveProcessFlow.prop('disabled', false);
//             btnSaveProcessFlow.html('Save');
            
//             if( data["duplicateError"] != undefined ){
//                 toastr.error(data["duplicateError"]);
//                 return
//             }
//             // cnfrmLoading.close();
//             $("input[name='step']", frmSaveProcessFlow).focus();

//             if(data['auth'] == 1){
//                 if(data['result'] == 1){
//                     toastr.success('Record Saved!');
//                     frmSaveProcessFlow[0].reset();
//                     mdlSaveProcessFlow.modal('hide');
//                     $(".input-error", frmSaveProcessFlow).text('');
//                     $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
//                     dataTableMatProcess.draw();
//                 }
//                 else{
//                     if(data['error'] == 'notEngineer'){
//                         toastr.error('User is not Engineer!', 'Saving Failed');
//                     }
//                     else if(data['error'] != null){
//                         toastr.error('Saving Failed!');
//                         if(data['error']['step'] != null){
//                             $("input[name='step']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='step']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['process'] != null){
//                             $("input[name='process']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='process']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['material_id'] != null){
//                             $("input[name='material_id']", frmSaveProcessFlow).addClass('is-invalid');
//                             console.log('addClass')
//                         }
//                         else{
//                             $("input[name='material_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                             console.log('removeClass')
//                         }

//                         if(data['error']['material_kitting_item'] != null){
//                             $("input[name='material_kitting_item']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='material_kitting_item']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['sakidashi_item'] != null){
//                             $("input[name='sakidashi_item']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='sakidashi_item']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['machine_id'] != null){
//                             $("input[name='machine_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='machine_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['a_shift_user_id'] != null){
//                             $("input[name='a_shift_user_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='a_shift_user_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }

//                         if(data['error']['b_shift_user_id'] != null){
//                             $("input[name='b_shift_user_id']", frmSaveProcessFlow).addClass('is-invalid');
//                         }
//                         else{
//                             $("input[name='b_shift_user_id']", frmSaveProcessFlow).removeClass('is-invalid');
//                         }
//                     }
//                 }
//             }
//             else{ // if session expired
//                 // cnfrmAutoLogin.open();
//                 toastr.error('Session Expired!');
//             }
//         },
//         error(xhr, data, status){
//             // cnfrmLoading.close();
//             btnSaveProcessFlow.prop('disabled', false);
//             btnSaveProcessFlow.html('Save');
//             toastr.error('Saving Failed!');
//         }
//     });
// }

function SaveProcessFlow(){
	var data = frmSaveProcessFlow.serialize();
    $.ajax({
        url: 'save_process_flow',
        method: 'post',
        data: data,
        dataType: 'json',
        beforeSend() {
            btnSaveProcessFlow.prop('disabled', true);
            btnSaveProcessFlow.html('Saving...');
            $(".input-error", frmSaveProcessFlow).text('');
            $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            btnSaveProcessFlow.prop('disabled', false);
            btnSaveProcessFlow.html('Save');
            
            if( data["duplicateError"] != undefined ){
                toastr.error(data["duplicateError"]);
                return
            }
            // cnfrmLoading.close();
            $("input[name='step']", frmSaveProcessFlow).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSaveProcessFlow[0].reset();
                    mdlSaveProcessFlow.modal('hide');
                    $(".input-error", frmSaveProcessFlow).text('');
                    $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
                    dataTableMatProcess.draw();
                }
                else{
                    if(data['error'] == 'notEngineer'){
                        toastr.error('User is not Engineer!', 'Saving Failed');
                    }
                    else if(data['error'] != null){
                        toastr.error('Saving Failed!');
                        if(data['error']['step'] != null){
                            $("input[name='step']", frmSaveProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='step']", frmSaveProcessFlow).removeClass('is-invalid');
                        }

                        if(data['error']['process'] != null){
                            $("input[name='process']", frmSaveProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='process']", frmSaveProcessFlow).removeClass('is-invalid');
                        }

                        if(data['error']['material_id'] != null){
                            $("input[name='material_id']", frmSaveProcessFlow).addClass('is-invalid');
                            console.log('addClass')
                        }
                        else{
                            $("input[name='material_id']", frmSaveProcessFlow).removeClass('is-invalid');
                            console.log('removeClass')
                        }

                        if(data['error']['material_kitting_item'] != null){
                            $("input[name='material_kitting_item']", frmSaveProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='material_kitting_item']", frmSaveProcessFlow).removeClass('is-invalid');
                        }

                        if(data['error']['sakidashi_item'] != null){
                            $("input[name='sakidashi_item']", frmSaveProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='sakidashi_item']", frmSaveProcessFlow).removeClass('is-invalid');
                        }

                        if(data['error']['machine_id'] != null){
                            $("input[name='machine_id']", frmSaveProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='machine_id']", frmSaveProcessFlow).removeClass('is-invalid');
                        }

                        if(data['error']['a_shift_user_id'] != null){
                            $("input[name='a_shift_user_id']", frmSaveProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='a_shift_user_id']", frmSaveProcessFlow).removeClass('is-invalid');
                        }

                        if(data['error']['b_shift_user_id'] != null){
                            $("input[name='b_shift_user_id']", frmSaveProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='b_shift_user_id']", frmSaveProcessFlow).removeClass('is-invalid');
                        }
                    }
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
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

/**
 * Update for machine only
 * 01-16-2023
 * -JD
 */
function SaveProcessFlowMachine(){
    let frmSaveProcessFlowMachine = $('#frmSaveProcessFlowMachine');
    let mdlSaveProcessFlowMachine = $('#mdlSaveProcessFlowMachine');
    // selectEditMaterialProcessFlowMachine

	var data = frmSaveProcessFlowMachine.serialize();
    $.ajax({
        url: 'save_process_flow_machine',
        method: 'post',
        data: data,
        dataType: 'json',
        beforeSend() {
            btnSaveProcessFlow.prop('disabled', true);
            btnSaveProcessFlow.html('Saving...');
            $(".input-error", frmSaveProcessFlowMachine).text('');
            $(".form-control", frmSaveProcessFlowMachine).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            btnSaveProcessFlow.prop('disabled', false);
            btnSaveProcessFlow.html('Save');
            
            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSaveProcessFlowMachine[0].reset();
                    mdlSaveProcessFlowMachine.modal('hide');
                    $(".input-error", frmSaveProcessFlowMachine).text('');
                    $(".form-control", frmSaveProcessFlowMachine).removeClass('is-invalid');
                    dataTableMatProcess.draw();
                }
                else{
                    if(data['error'] == 'notEngineer'){
                        toastr.error('User is not Engineer!', 'Saving Failed');
                    }
                    else if(data['error'] != null){
                        toastr.error('Saving Failed!');

                        if(data['error']['machine_id'] != null){
                            $("input[name='machine_id']", frmSaveProcessFlowMachine).addClass('is-invalid');
                        }
                        else{
                            $("input[name='machine_id']", frmSaveProcessFlowMachine).removeClass('is-invalid');
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

function GetProcessFlowById(processFlowId){
    // let url = globalLink.replace('link', 'get_process_flow_by_id');
    // let login = globalLink.replace('link', 'login');
    $.ajax({
        url: 'get_process_flow_by_id',
        method: 'get',
        data: {
            process_flow_id: processFlowId
        },
        dataType: 'json',
        beforeSend() {
            btnSaveProcessFlow.prop('disabled', true);
            btnSaveProcessFlow.html('Saving...');
            $(".input-error", frmSaveProcessFlow).text('');
            $(".form-control", frmSaveProcessFlow).removeClass('is-invalid');
            // cnfrmLoading.open();
            // frmSaveProcessFlow[0].reset();
            $('input[name="process_flow_id"]', frmSaveProcessFlow).val('');
        },
        success(data){
            btnSaveProcessFlow.prop('disabled', false);
            btnSaveProcessFlow.html('Save');
            // cnfrmLoading.close();
            $("input[name='step']", frmSaveProcessFlow).focus();

            if(data['auth'] == 1){
                if(data['process_flow_info'] != null){
                    $("#mdlSaveProcessFlow").modal('show');
                    $('input[name="process_flow_id"]', frmSaveProcessFlow).val(data['process_flow_info']['id']);
                    $('input[name="step"]', frmSaveProcessFlow).val(data['process_flow_info']['step']);
                    $('input[name="process"]', frmSaveProcessFlow).val(data['process_flow_info']['process']);
                }
                else{
                    toastr.error('No record found.');
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
                toastr.error('Session Expired!');
            }
        },
        error(xhr, data, status){
            // cnfrmLoading.close();
            btnSaveProcessFlow.prop('disabled', false);
            btnSaveProcessFlow.html('Save');
            toastr.error('An error occured!');
        }
    });
}

function ImportProcessFlow(){
    // let url = globalLink.replace('link', 'save_process_flow');
    // let login = globalLink.replace('link', 'login');

    $.ajax({
        url: 'import_process_flow',
        method: 'post',
        data: new FormData(frmImportProcessFlow[0]),
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend() {
            btnImportProcessFlow.prop('disabled', true);
            btnImportProcessFlow.html('Importing...');
            $(".input-error", frmImportProcessFlow).text('');
            $(".form-control", frmImportProcessFlow).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            btnImportProcessFlow.prop('disabled', false);
            btnImportProcessFlow.html('Import');
            // cnfrmLoading.close();
            $("input[name='step']", frmImportProcessFlow).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Imported!');
                    frmImportProcessFlow[0].reset();
                    mdlImportProcessFlow.modal('hide');
                    $(".input-error", frmImportProcessFlow).text('');
                    $(".form-control", frmImportProcessFlow).removeClass('is-invalid');
                    dataTableMatProcess.draw();
                }
                else{
                    toastr.error('Importing Failed!');
                    if(data['error'] != null){
                        if(data['error']['step'] != null){
                            $("input[name='step']", frmImportProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='step']", frmImportProcessFlow).removeClass('is-invalid');
                        }

                        if(data['error']['process'] != null){
                            $("input[name='process']", frmImportProcessFlow).addClass('is-invalid');
                        }
                        else{
                            $("input[name='process']", frmImportProcessFlow).removeClass('is-invalid');
                        }
                    }
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
                toastr.error('Session Expired!');
            }
        },
        error(xhr, data, status){
            // cnfrmLoading.close();
            btnImportProcessFlow.prop('disabled', false);
            btnImportProcessFlow.html('Import');
            toastr.error('Importing Failed!');
        }
    });
}

function GetProcessFlow(cboElement, productLine){
    let result = '<option value="0" selected disabled> --- </option>';
    $.ajax({
        url: 'get_process_flows',
        method: 'get',
        dataType: 'json',
        data: {
            device_name: productLine,
        },
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
            $('#btnShowAddProcess').prop('disabled', true);
            arrProdProcessFlow = [];
        },
        success: function(JsonObject){
            result = '';
            $('#btnShowAddProcess').prop('disabled', false);
            if(JsonObject['data'].length > 0){
                // result = '<option value="0" selected disabled> --- </option>';
                for(let index = 0; index < JsonObject['data'].length; index++){
                    result += '<option value="' + JsonObject['data'][index].id + '" step-num="' + JsonObject['data'][index].step + '">' + JsonObject['data'][index].process + '</option>';
                    arrProdProcessFlow.push(JsonObject['data'][index]);
                }
            }
            else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
        },
        error: function(data, xhr, status){
            arrProdProcessFlow = [];
            $('#btnShowAddProcess').prop('disabled', true);
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            toastr.error('Process Flow not loaded!');
        }
    });
}

function ChangeProcessFlowStat(){
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
        url: "change_process_flow_stat",
        method: "post",
        data: $('#formChangeMatProcStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeMatProcStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeMatProcStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeMatProcStat").modal('hide');
                $("#formChangeMatProcStat")[0].reset();

                dataTableMatProcess.draw();

                if($("#txtChangeMatProcStatMatProcStat").val() == 1){
                    toastr.success('Material Process Activation Success!');
                }
                else{
                    toastr.success('Material Process Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeMatProcStatMatProcStat").val() == 1){
                    toastr.error('Material Process Activation Failed!');
                }
                else{
                    toastr.error('Material Process Deactivation Failed!');
                }
            }

            $("#iBtnChangeMatProcStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMatProcStat").removeAttr('disabled');
            $("#iBtnChangeMatProcStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeMatProcStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMatProcStat").removeAttr('disabled');
            $("#iBtnChangeMatProcStatIcon").addClass('fa fa-check');
        }
    });
}

function ProcessFlowForProd(processFlowId, status){
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
        url: "process_flow_for_prod",
        method: "get",
        data: {
            process_flow_id: processFlowId,
            status: status,
        },
        dataType: "json",
        beforeSend: function(){
            // $("#iBtnChangeMatProcStatIcon").addClass('fa fa-spinner fa-pulse');
            // $("#btnChangeMatProcStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                toastr.success('Saving Success!');
                dataTableMatProcess.draw();
            }
            else{
                toastr.error('Saving Failed!');
            }

            // $("#iBtnChangeMatProcStatIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#btnChangeMatProcStat").removeAttr('disabled');
            // $("#iBtnChangeMatProcStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iBtnChangeMatProcStatIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#btnChangeMatProcStat").removeAttr('disabled');
            // $("#iBtnChangeMatProcStatIcon").addClass('fa fa-check');
        }
    });
}


/**
 * Change approval status
 * 01-06-2023
 * JD
 */
function ChangeApprovalStatus(){
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
        url: "change_approval_status",
        method: "post",
        data: $('#formChangeApprovalStatus').serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("#iconChangeApprovalStatus").addClass('fa fa-spinner fa-pulse');
            // $("#buttonChangeApprovalStatus").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeApprovalStatus").modal('hide');
                $("#formChangeApprovalStatus")[0].reset();

                dataTableMatProcess.draw();
                if(JsonObject['approval_status'] == 4 || JsonObject['approval_status'] == 5){
                    toastr.success('Disapproved');
                }
                else{
                    toastr.success('Approved!');
                }
            }
            else{
                if(JsonObject['error']['approval_remarks'] != null){
                    $("#textApprovalRemarks").addClass('is-invalid');
                }
                else{
                    $("#textApprovalRemarks").removeClass('is-invalid');
                }
                toastr.error('Change Approval Status Failed!');
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

/**
 * Change approval status
 * 03-18-2024
 * JD
 */
function MaterialChangeApprovalStatus(){
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
        url: "material_change_approval_status",
        method: "post",
        data: $('#formMaterialChangeApprovalStatus').serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("#iconChangeApprovalStatus").addClass('fa fa-spinner fa-pulse');
            // $("#buttonChangeApprovalStatus").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalMaterialChangeApprovalStatus").modal('hide');
                $("#formMaterialChangeApprovalStatus")[0].reset();

                dataTableMatProcess.draw();
                if(JsonObject['material_approval_status'] == 4 || JsonObject['material_approval_status'] == 5){
                    toastr.success('Disapproved');
                }
                else{
                    toastr.success('Approved!');
                }
            }
            else{
                if(JsonObject['error']['approval_remarks'] != null){
                    $("#textApprovalRemarks").addClass('is-invalid');
                }
                else{
                    $("#textApprovalRemarks").removeClass('is-invalid');
                }
                toastr.error('Change Approval Status Failed!');
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}