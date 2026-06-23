// Add MOD
function AddMOD(){
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
        url: "add_mod",
        method: "post",
        data: $('#formAddMOD').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddMODIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddMOD").modal('hide');
            	$("#formAddMOD")[0].reset();
              $("#txtAddMODName").removeClass('is-invalid');
              $("#txtAddMODBarcode").removeClass('is-invalid');

            	dataTableMODS.draw();
              toastr.success('Mode of Defect was succesfully saved!');
            }
            else{
                toastr.error('Saving Mode of Defect Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddMODName").removeClass('is-invalid');
                    $("#txtAddMODName").attr('title', '');
                }
                else{
                    $("#txtAddMODName").addClass('is-invalid');
                    $("#txtAddMODName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddMODBarcode").removeClass('is-invalid');
                    $("#txtAddMODBarcode").attr('title', '');
                }
                else{
                    $("#txtAddMODBarcode").addClass('is-invalid');
                    $("#txtAddMODBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['defect_classification'] === undefined){
                    $("#defectClassification").removeClass('is-invalid');
                    $("#defectClassification").attr('title', '');
                }
                else{
                    $("#defectClassification").addClass('is-invalid');
                    $("#defectClassification").attr('title', JsonObject['error']['defect_classification']);
                }
            }

            $("#iBtnAddMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").removeAttr('disabled');
            $("#iBtnAddMODIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").removeAttr('disabled');
            $("#iBtnAddMODIcon").addClass('fa fa-check');
        }
    });
}

function AddMODTest(){
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
        url: "add_mod_test",
        method: "post",
        data: $('#formAddMOD').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddMODIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddMOD").modal('hide');
            	$("#formAddMOD")[0].reset();
              $("#txtAddMODName").removeClass('is-invalid');
              $("#txtAddMODBarcode").removeClass('is-invalid');

            	dataTableMODS.draw();
              toastr.success('Mode of Defect was succesfully saved!');
            }
            else{
                toastr.error('Saving Mode of Defect Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddMODName").removeClass('is-invalid');
                    $("#txtAddMODName").attr('title', '');
                }
                else{
                    $("#txtAddMODName").addClass('is-invalid');
                    $("#txtAddMODName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddMODBarcode").removeClass('is-invalid');
                    $("#txtAddMODBarcode").attr('title', '');
                }
                else{
                    $("#txtAddMODBarcode").addClass('is-invalid');
                    $("#txtAddMODBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['defect_classification'] === undefined){
                    $("#defectClassification").removeClass('is-invalid');
                    $("#defectClassification").attr('title', '');
                }
                else{
                    $("#defectClassification").addClass('is-invalid');
                    $("#defectClassification").attr('title', JsonObject['error']['defect_classification']);
                }
            }

            $("#iBtnAddMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").removeAttr('disabled');
            $("#iBtnAddMODIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").removeAttr('disabled');
            $("#iBtnAddMODIcon").addClass('fa fa-check');
        }
    });
}

// Edit MOD
function GetMODByIdToEdit(modId){
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
        url: "get_mod_by_id",
        method: "get",
        data: {
            mod_id: modId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditMODName").val("");
            $("#txtEditMODBarcode").val("");
            $("#txtEditMODName").removeClass('is-invalid');
            $("#txtEditMODBarcode").removeClass('is-invalid');
        },
        success: function(JsonObject){
            let result = JsonObject['mod'];
            if(result.length > 0){
                $("#txtEditMODName").val(result[0].name);
                $("#txtEditMODBarcode").val(result[0].barcode);
                $("#txtEditdefectClassification").val(result[0].defect_classification);
            }
            else{
                toastr.warning('No Mode of Defect Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditMOD(){
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
        url: "edit_mod",
        method: "post",
        data: $('#formEditMOD').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditMODIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditMOD").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditMOD").modal('hide');
                $("#formEditMOD")[0].reset();

                $("#txtEditMODName").removeClass('is-invalid');
                $("#txtEditMODBarcode").removeClass('is-invalid');
                dataTableMODS.draw();
                toastr.success('MOD was succesfully saved!');
            }
            else{
                toastr.error('Updating Mode of Defect Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditMODName").removeClass('is-invalid');
                    $("#txtEditMODName").attr('title', '');
                }
                else{
                    $("#txtEditMODName").addClass('is-invalid');
                    $("#txtEditMODName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtEditMODName").removeClass('is-invalid');
                    $("#txtEditMODBarcode").attr('title', '');
                }
                else{
                    $("#txtEditMODName").attr('title', JsonObject['error']['barcode']);
                    $("#txtEditMODBarcode").addClass('is-invalid');
                }

                
                if(JsonObject['error']['edit_defect_classification'] === undefined){
                    $("#txtEditdefectClassification").removeClass('is-invalid');
                    $("#txtEditdefectClassification").attr('title', '');
                }
                else{
                    $("#txtEditdefectClassification").attr('title', JsonObject['error']['edit_defect_classification']);
                    $("#txtEditdefectClassification").addClass('is-invalid');
                }

            }

            $("#iBtnEditMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMOD").removeAttr('disabled');
            $("#iBtnEditMODIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMOD").removeAttr('disabled');
            $("#iBtnEditMODIcon").addClass('fa fa-check');
        }
    });
}

// Change MOD Status
function ChangeMODStatus(){
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
        url: "change_mod_stat",
        method: "post",
        data: $('#formChangeMODStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeMODStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeMODStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeMODStat").modal('hide');
                $("#formChangeMODStat")[0].reset();

                dataTableMODS.draw();

                if($("#txtChangeMODStatMODStat").val() == 1){
                    toastr.success('Mode of Defect Activation Success!');
                }
                else{
                    toastr.success('Mode of Defect Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeMODStatMODStat").val() == 1){
                    toastr.error('Mode of Defect Activation Failed!');
                }
                else{
                    toastr.error('Mode of Defect Deactivation Failed!');
                }
            }

            $("#iBtnChangeMODStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMODStat").removeAttr('disabled');
            $("#iBtnChangeMODStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeMODStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMODStat").removeAttr('disabled');
            $("#iBtnChangeMODStatIcon").addClass('fa fa-check');
        }
    });
}

function GetCboMOD(cboElement){
    let result = '<option value="0" selected> N/A </option>';
    $.ajax({
        url: 'get_mods',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        // success: function(JsonObject){
        //     result = '';
        //     if(JsonObject['mods'].length > 0){
        //         result = '<option value="0"> N/A </option>';
        //         for(let index = 0; index < JsonObject['mods'].length; index++){
        //             result += '<option value="' + JsonObject['mods'][index].id + '" data-code="' + JsonObject['mods'][index].barcode + '">' + JsonObject['mods'][index].name + '</option>';
        //         }
        //     }
        //     else{
        //         result = '<option value="0" selected disabled> -- No record found -- </option>';
        //     }

        //     cboElement.html(result);
        // },
        // error: function(data, xhr, status){
        //     result = '<option value="0" selected disabled> -- Reload Again -- </option>';
        //     cboElement.html(result);
        //     console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        // }
        success: function (JsonObject) {
            let result = '';

            if (JsonObject['mods'].length > 0) {
                result = '<option value="0"> N/A </option>';

                for (let index = 0; index < JsonObject['mods'].length; index++) {
                    let mod = JsonObject['mods'][index];

                    // console.log('mod.classification',mod.defect_classification);
                    // include defect_classification in the option as data attribute
                    result += '<option value="' + mod.id + 
                            '" data-code="' + mod.barcode + 
                            '" data-defect="' + (mod.defect_classification || '') + '">' + 
                            mod.name + 
                            '</option>';
                }
            } else {
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function (data, xhr, status) {
            let result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
        
    });
}

function GetCboMOD2(cboElement, modId=null){
    // return;'
    let modIdArray = [];
    if(modId != null){
        for (let index = 0; index < modId.length; index++) {
        // const element = array[index];
        // console.log()
        // console.log('TamodId', modId[index].mod_id);
            modIdArray.push(modId[index].mod_id);
        
        }
    }
    // console.log(modIdArray);
    // return;
    
    let result = '<option value="" selected> N/A </option>';
    $.ajax({
        url: 'get_mods',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['mods'].length > 0){
                // result = '<option value=""> N/A </option>';
                for(let index = 0; index < JsonObject['mods'].length; index++){
                    let defectClassification = '';
                    let mod = JsonObject['mods'][index]; // Shorten reference for readability

                    if(mod.defect_classification == 1)      defectClassification = 'Critical'; 
                    else if(mod.defect_classification == 2) defectClassification = 'Non-Critical'; 
                    else if(mod.defect_classification == 3) defectClassification = 'Set-up'; 
                    else if(mod.defect_classification == 4) defectClassification = 'Auto Inserter'; 
                    else if(mod.defect_classification == 5) defectClassification = 'NG Samples'; 

                    // Add the classification to the text inside the option tag
                    result += `<option value="${mod.id}" data-defect="${mod.defect_classification}" data-code="${mod.barcode}">
                                ${mod.name} - (${defectClassification})
                            </option>`;                
                }
            }
            else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            

            cboElement.html(result);
            cboElement.val(modIdArray).trigger('change');
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

// function GetCriticalMOD(cboElement, defectClassification){
//     console.log('defectClassification', defectClassification);
//     let result = '<option value="0" disabled selected > ---- Select Defect ---- </option>';
//     $.ajax({
//         url: 'get_mods_for_dd',
//         method: 'get',
//         dataType: 'json',
//         beforeSend: function(){
//             result = '<option value="0" disabled selected > -- Loading -- </option>';
//             // $("#cboCriticalMOD").html(result);
//             cboElement.html(result);
//         },
//         success: function(JsonObject){
//             result = '';
//             // console.log('mods', JsonObject['mods']);
//             if(JsonObject['mods'].length > 0){
//                 result = '<option disabled selected value="0"> ---- Select Defect ---- </option>';
//                 for(let index = 0; index < JsonObject['mods'].length; index++){
//                     // result += '<option value="' + JsonObject['mods'][index].id + '" data-code="' + JsonObject['mods'][index].barcode + '">' + JsonObject['mods'][index].name +  '</option>';
//                     result += '<option ' +
//                     'value="' + JsonObject['mods'][index].id + '" ' +
//                     'data-code="' + JsonObject['mods'][index].barcode + '" ' +
//                     'data-defect_classification="' + JsonObject['mods'][index].defect_classification + '">' +
//                     JsonObject['mods'][index].name +
//                     '</option>';
//                 }
//             }
//             else{
//                 result = '<option value="0" selected disabled> -- No record found -- </option>';
//             }

//             // $("#cboCriticalMOD").html(result);
//             cboElement.html(result);
//         },
//         error: function(data, xhr, status){
//             result = '<option value="0" selected disabled> -- Reload Again -- </option>';
//             // $("#cboCriticalMOD").html(result);
//             cboElement.html(result);
//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }


// function GetCriticalMOD(cboElement, defectClassification){
//     console.log('defectClassification:', defectClassification);

//     let result = '<option value="0" disabled selected > ---- Select Defect ---- </option>';

//     $.ajax({
//         url: 'get_mods_for_dd',
//         method: 'get',
//         data: { classification: defectClassification }, // pass classification here
//         dataType: 'json',
//         beforeSend: function(){
//             cboElement.html('<option value="0" disabled selected > -- Loading -- </option>');
//         },
//         success: function(JsonObject){
//             if(JsonObject['mods'] && JsonObject['mods'].length > 0){
//                 result = '<option disabled selected value="0"> ---- Select Defect ---- </option>';
//                 for(let mod of JsonObject['mods']){
//                     result += `<option value="${mod.id}" data-code="${mod.barcode}" data-defect_classification="${mod.defect_classification}">
//                         ${mod.name}
//                     </option>`;
//                 }
//             } else {
//                 result = '<option value="0" selected disabled> -- No record found -- </option>';
//             }
//             cboElement.html(result);
//         },
//         error: function(data, xhr, status){
//             cboElement.html('<option value="0" selected disabled> -- Reload Again -- </option>');
//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }

function getModOfDefect(cboElement, defectClassification, callback = null) {
    // console.log('defectClassification:', defectClassification);

    let result = '<option value="0" disabled selected > ---- N/A ---- </option>';

    $.ajax({
        url: 'get_mods_for_dd',
        method: 'get',
        data: { classification: defectClassification },
        dataType: 'json',
        beforeSend: function () {
            cboElement.html('<option value="0" disabled selected > -- Loading -- </option>');
        },
        success: function (JsonObject) {
            if (JsonObject['mods'] && JsonObject['mods'].length > 0) {
                // 🔽 First option now "N/A"
                result = '<option value="">N/A</option>';
                for (let mod of JsonObject['mods']) {
                    result += `<option value="${mod.id}" data-code="${mod.barcode}" data-defect_classification="${mod.defect_classification}">
                        ${mod.name}
                    </option>`;
                }
            } else {
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }

            cboElement.html(result);

            // ✅ Trigger callback AFTER populating the dropdown
            if (typeof callback === 'function') {
                callback();
            }
        },
        error: function (data, xhr, status) {
            cboElement.html('<option value="0" selected disabled> -- Reload Again -- </option>');
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}