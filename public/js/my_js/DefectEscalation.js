function getRuncardWithDefect(cboElement, poNumber) {
    let result = '<option value="0" disabled selected> -- Loading -- </option>';
        
    cboElement.html(result);

    $.ajax({
        url: 'get_runcard_with_defects',
        method: 'GET',
        data: { po_number: poNumber },
        dataType: 'json',
        success: function(JsonObject) {
            result = '<option value="0" disabled selected> -- Select Runcard -- </option>';
            result += '<option value="">N/A</option>'; // <-- Added this line

            for (let index = 0; index < JsonObject['data'].length; index++) {
                let item = JsonObject['data'][index];
                result += `<option value="${item.mod_station_id}" 
                                  data-rid="${item.runcard_id}" 
                                  data-rsid="${item.runcard_station_id}" 
                                  data-line="${item.assembly_line_id}" 
                                  data-pair="${item.pair_no}" 
                                  data-die="${item.die_no}" 
                                  data-qty="${item.qty}">
                                  ${item.runcard_no}
                           </option>`;
            }
            

            cboElement.html(result);
        },
        error: function() {
            cboElement.html('<option value="0" disabled selected> -- Error loading -- </option>');
        }
    });
}

function getRuncardWithDefectv2(cboElement, poNumber) {
    let result = '<option value="0" disabled selected> -- Loading -- </option>';
        
    cboElement.html(result);

    $.ajax({
        url: 'get_runcard_with_defects_v2',
        method: 'GET',
        data: { po_number: poNumber },
        dataType: 'json',
        success: function(JsonObject) {
            result = '<option value="0" disabled selected> -- Select Runcard -- </option>';
            result += '<option value="">N/A</option>'; // <-- Added this line

            
            for (let index = 0; index < JsonObject['data'].length; index++) {
                let item = JsonObject['data'][index];
                result += `<option value="${item.mod_station_id}" 
                                  data-rid="${item.runcard_id}" 
                                  data-rsid="${item.runcard_station_id}" 
                                  data-line="${item.assembly_line_id}" 
                                  data-pair="${item.pair_no}" 
                                  data-die="${item.die_no}" 
                                  data-qty="${item.mod_qty}">
                                  ${item.runcard_no} - ${item.defect_name}
                           </option>`;
            }
            

            cboElement.html(result);
        },
        error: function() {
            cboElement.html('<option value="0" disabled selected> -- Error loading -- </option>');
        }
    });
}



function GetAssemblyLinesForDefectEscalation(cboElement){
    let result = '<option value="0" selected disabled> --- </option>';
    $.ajax({
        url: 'get_assembly_lines_for_defect_escalation',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['assembly_lines'].length > 0){
                result = '<option value="0" selected disabled> --- </option>';
                for(let index = 0; index < JsonObject['assembly_lines'].length; index++){
                    result += '<option value="' + JsonObject['assembly_lines'][index].id + '">' + JsonObject['assembly_lines'][index].name + '</option>';
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

function getDefectEscalationCtrlNoToPrint(defectId, defectCtrlNo){
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
        url: "generate_defect_qrcode",
        method: "get",
        data: {
            defect_id: defectId,
            defect_ctrl_no: defectCtrlNo,
        },
        dataType: "json",
        beforeSend: function(){
                // $("#lblGenWHMatIssuPoNo").text('...');
                // $("#lblGenWHMatIssuPoDevName").text('...');
                // $("#lblRuncardPoKitNo").text('...');
                // $("#lblRuncardPoKitQty").text('...');
                // $("#lblRuncardTransSlip").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['data'];
            let poQRCode = JsonObject['po_qrcode'];
            let defectCtrlNoCode = JsonObject['defect_ctrl_qrcode'];
            let device_name_print = JsonObject['device_name_print'];

            if(result != null){
                $("#modalGenDefectToPrint").modal('show');
                $("#imgGenDefectPoNoBarcode").attr('src', poQRCode);
                $("#imgGenDefectCtrlNoBarcode").attr('src', defectCtrlNoCode);

                $("#lblDefectPoNo").text(result.po_number);
                $("#lblRuncardProductName").text(device_name_print);

                $("#lblDefectCtrlNo").text(result.defect_ctrl_txt + '-' + result.defect_ctrl_number);

                if(result.de_station == 1){
                    station = 'Parts Preparation'
                }else if(result.de_station == 2){
                    station = 'Machine'
                }else if(result.de_station == 3){
                    station = 'Assembly'
                }else if(result.de_station == 4){
                    station = 'Visual Inspection'
                }else if(result.de_station == 5){
                    station = 'Setup / Adj.'
                }else if(result.de_station == 6){
                    station = 'Qualification'
                }else if(result.de_station == 7){
                    station = 'Others'  
                }else{
                    station = 'N/A' 
                }

                $("#lblStation").text(station);
                
                getModOfDefect($("#lblDefectType"), result.defect_classification, function () {
                    let defectIds = (result.mod_id || "").split(",");
                
                    // Map each id to its option text (the defect name)
                    let defectNames = defectIds.map(id => {
                        return $("#lblDefectType option[value='"+ id +"']").text();
                    });
                
                    // Display joined names instead of IDs
                    $("#lblDefectType").text(defectNames.join(","));
                });
            }
            else{
                toastr.error('QR Code Generation Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function getDefectCount(poNumber){
   $.ajax({
        url: "get_defect_count",
        method: "get",
        data: {
            po_number: poNumber,
        },
        dataType: "json",
        beforeSend: function(){
                // $("#lblGenWHMatIssuPoNo").text('...');
                // $("#lblGenWHMatIssuPoDevName").text('...');
                // $("#lblRuncardPoKitNo").text('...');
                // $("#lblRuncardPoKitQty").text('...');
                // $("#lblRuncardTransSlip").text('...');
        },
        success: function(JsonObject){
            console.log('JsonObject',JsonObject['data']);
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}