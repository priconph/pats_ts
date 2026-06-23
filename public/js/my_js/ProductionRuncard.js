function GetMaterialKitting(){
      $.ajax({
        url: "get_wbs_material_kitting",
        method: 'get',
        dataType: 'json',
        data: {
          po_number: currentPoNo
        },
        beforeSend: function(){
          boxing = "";
          assessment = "";
          aDrawing = "";
          aDrawingRev = "";
          gDrawing = "";
          gDrawingRev = "";
        },
        success: function(data){
            // alert(data['matrix'].length);
           if(data['matrix'].length === 0){
                    let notif_alert = ` <p style="font-size:50px; color:red;"><i class="fa fa-exclamation-triangle text-danger"></i> NOT SAVED!, Please check the Matrix!</p>
                    <p style="font-size:50px; color:red;"><strong>CALL THE ATTENTION OF IMMEDIATE SUPERVISOR</strong></p>`;
                    $('#alert_notif').html(notif_alert);
                    $('#modalScanEmployeeId').modal('show');  //enable this
                    $("#txt_po_number_lbl").val('');
                    $("#txt_device_name_lbl").val('');
                    $("#txt_device_code_lbl").val('');
                    $("#txt_po_qty_lbl").val('');
                    $("#txt_lot_qty").val("");
                    return;
            }
          if(data['material_kitting'] != null){
            $("#txt_po_number_lbl").val(data['material_kitting']['po_no']);
            $("#txt_device_name_lbl").val(data['material_kitting']['device_name']);
            $("#txt_device_code_lbl").val(data['material_kitting']['device_code']);
            $("#txt_po_qty_lbl").val(data['material_kitting']['po_qty']);

            /* 03172023*/
            $("#txt_lot_qty").val(data['matrix'][0]['ship_boxing']);

            boxing = data['matrix'][0]['ship_boxing'];

            // alert(data['matrix'][0]['ship_boxing'])
            // if(data['material_kitting']['matrix'] != null){
            //   $("#txt_lot_qty").val(data['matrix'][0]['ship_boxing']);
            //   //boxing = data['material_kitting']['device_info']['boxing'];


            //   // alert(boxing);
            // }
            // else{
            //   boxing = "";
            //   // $("#txt_lot_qty").val("");
            // }

            if(data['material_kitting']['assessment'] != null){
              assessment = data['material_kitting']['device_info']['assessment'];
            }
            else{
              assessment = "";
            }

            $("#txt_ct_supplier").val('YEC');

            // TEMP REMOVE Due to Request of operatr to fix it in "YEC"
            // if(data['material_kitting']['material_issuance_details'].length > 0){
            //     $("#txt_ct_supplier").val(data['material_kitting']['material_issuance_details'][0]['supplier']);
            // }
            // else{
            //     $("#txt_ct_supplier").val('');
            // }
            // ..TEMP REMOVE Due to Request of operatr to fix it in "YEC"

            // if(data['material_kitting']['documents_details'].length > 0){
            //   for(let index = 0; index < data['material_kitting']['documents_details'].length; index++){
            //     if(data['material_kitting']['documents_details'][index]['doc_no'].charAt(0).toUpperCase() == "A"){
            //       aDrawing = data['material_kitting']['documents_details'][index]['doc_no'];
            //       aDrawingRev = data['material_kitting']['documents_details'][index]['rev_no'];
            //     }
            //     else if(data['material_kitting']['documents_details'][index]['doc_no'].charAt(0).toUpperCase() == "G"){
            //       gDrawing = data['material_kitting']['documents_details'][index]['doc_no'];
            //       gDrawingRev = data['material_kitting']['documents_details'][index]['rev_no'];
            //     }
            //   }

            //   $("#txt_a_drawing_no").val(aDrawing);
            //   $("#txt_a_drawing_rev").val(aDrawingRev);
            //   $("#txt_g_drawing_no").val(gDrawing);
            //   $("#txt_g_drawing_rev").val(gDrawingRev);
            // }
            if(data['doc_a_drawing_query'].length > 0){
              aDrawing = data['doc_a_drawing_query'][0].doc_no;
              aDrawingRev = data['doc_a_drawing_query'][0].rev_no;
            }
            else{
              aDrawing = "";
              aDrawingRev = "";
            }
            $("#txt_a_drawing_no").val(aDrawing);
            $("#txt_a_drawing_rev").val(aDrawingRev);

            if(data['doc_g_drawing_query'].length > 0){
              gDrawing = data['doc_g_drawing_query'][0].doc_no;
              gDrawingRev = data['doc_g_drawing_query'][0].rev_no;
            }
            else{
              gDrawing = "";
              gDrawingRev = "";
            }
            $("#txt_g_drawing_no").val(gDrawing);
            $("#txt_g_drawing_rev").val(gDrawingRev);

            // materialKitTransferSlip = data['material_kitting']['issuance_no'];
            // $("#txt_material_transfer_slip_lbl").val(materialKitTransferSlip);
            // dt_materials.draw();

            // $("#txt_lot_qty").val(boxing);
          }
          else{
            // alert()
            // material_kitting

            $("#txt_po_number_lbl").val('');
            $("#txt_device_name_lbl").val('');
            $("#txt_device_code_lbl").val('');
            $("#txt_po_qty_lbl").val('');
            $("#txt_lot_qty").val("");
          }
        }
      });
    }



function GetProductionRuncards(cboElement, status){
    let result = '<option value="0" selected> --- </option>';
    $.ajax({
        url: 'get_prod_runcards',
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
            if(JsonObject['prod_runcards'].length > 0){
                result = '<option value="0" selected> --- </option>';
                for(let index = 0; index < JsonObject['prod_runcards'].length; index++){
                    result += '<option value="' + JsonObject['prod_runcards'][index].id + '">' + JsonObject['prod_runcards'][index].runcard_no + '</option>';
                }
            }
            else{
                result = '<option value="0" selected> --- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected> --- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetWHMatIssuIdToPrint(matWHMatIssuId, qrCodeType){
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
        url: "fn_get_warehouse_view_batches_by_id",
        method: "get",
        data: {
            material_issuance_id: matWHMatIssuId,
            qr_code_type: qrCodeType,
        },
        dataType: "json",
        beforeSend: function(){
                $("#lblGenWHMatIssuPoNo").text('...');
                $("#lblGenWHMatIssuPoDevName").text('...');
                $("#lblGenWHMatIssuPoKitNo").text('...');
                $("#lblGenWHMatIssuPoKitQty").text('...');
                $("#lblGenWHMatIssuTransSlip").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['tbl_wbs_material_kitting'];
            let poQRCode = JsonObject['po_qrcode'];
            let whsSlipQRcode = JsonObject['whs_slip_qrcode'];

            if(result.length > 0){
                $("#modalGenWHMatIssuIdToPrint").modal('show');
                $("#imgGenWHMatIssuIdPoNoBarcode").attr('src', poQRCode);
                $("#imgGenWHMatIssuIdBarcode").attr('src', whsSlipQRcode);
                $("#lblGenWHMatIssuPoNo").text(result[0].po_no);
                $("#lblGenWHMatIssuPoDevName").text(result[0].device_name);
                $("#lblGenWHMatIssuPoKitNo").text(result[0].kit_no);
                $("#lblGenWHMatIssuPoKitQty").text(result[0].kit_qty);
                $("#lblGenWHMatIssuTransSlip").text(result[0].issuance_no);

                imgResultMatIssuePoNoQrCode = poQRCode;
                imgResultMatIssueQrCode = whsSlipQRcode;
                lblGenWHMatIssuPoNo = result[0].po_no;
                lblGenWHMatIssuPoDevName = result[0].device_name;
                lblGenWHMatIssuPoKitNo = result[0].kit_no;
                lblGenWHMatIssuPoKitQty = result[0].kit_qty;
                lblGenWHMatIssuTransSlip = result[0].issuance_no;
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

function GetWHSakIssuIdToPrint(matWHSakIssuId){
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
        url: "get_warehouse_sakidashi_view_batches_by_id",
        method: "get",
        data: {
            sakidashi_issuance_id: matWHSakIssuId
        },
        dataType: "json",
        beforeSend: function(){
                $("#lblGenWHSakIssuDeviceName").text('...');

                $("#lblGenWHSakIssuPoNo").text('...');
                $("#lblGenWHSakIssuPairNo").text('...');
                $("#lblGenWHSakIssuMH").text('...');

                $("#lblGenWHSakIssuTransSlip").text('...');
                $("#lblGenWHSakIssuLotNo").text('...');
                $("#lblGenWHSakIssuQty").text('...');

                $("#lblGenWHSakIssuPartCode").text('...');
                $("#lblGenWHSakIssuLotName").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['tbl_sakidashi_kitting'];
            let poQRCode = JsonObject['po_qrcode'];
            let LotNoQRcode = JsonObject['ctrl_no_qrcode'];
            let LotNameQRcode = JsonObject['lotname_qrcode'];

            if(result.length > 0){
                $("#modalGenWHSakIssuIdToPrint").modal('show');
                $("#imgGenWHSakIssuIdPoNoBarcode").attr('src', poQRCode);
                $("#imgGenWHSakIssuIdBarcode").attr('src', LotNoQRcode);
                $("#imgGenWHSakIssuIdLotNameBarcode").attr('src', LotNameQRcode);

                $("#lblGenWHSakIssuDeviceName").text(result[0].device_name);

                $("#lblGenWHSakIssuPoNo").text(result[0].po_no);
                $("#lblGenWHSakIssuPairNo").text(result[0].tbl_wbs_sakidashi_issuance_item.pair_no);
                $("#lblGenWHSakIssuMH").text(result[0].tbl_wbs_sakidashi_issuance_item.create_user);


                $("#lblGenWHSakIssuTransSlip").text(result[0].issuance_no);
                $("#lblGenWHSakIssuLotNo").text(result[0].tbl_wbs_sakidashi_issuance_item.lot_no);
                $("#lblGenWHSakIssuQty").text(result[0].tbl_wbs_sakidashi_issuance_item.issued_qty);

                $("#lblGenWHSakIssuPartCode").text(result[0].tbl_wbs_sakidashi_issuance_item.item);
                $("#lblGenWHSakIssuLotName").text(result[0].tbl_wbs_sakidashi_issuance_item.item_desc);


                imgResultSakIssuePoNoQrCode = poQRCode;
                imgResultSakIssueQrCode = LotNoQRcode;
                imgResultSakIssueLotNameQrCode = LotNameQRcode;

                lblGenWHSakIssuPoNo = result[0].po_no;
                lblGenWHSakIssuPairNo = result[0].tbl_wbs_sakidashi_issuance_item.pair_no;
                lblGenWHSakIssuMH = result[0].tbl_wbs_sakidashi_issuance_item.create_user;

                lblGenWHSakIssuCtrlNo = result[0].issuance_no;
                lblGenWHSakIssuLotNo = result[0].tbl_wbs_sakidashi_issuance_item.lot_no;
                lblGenWHSakIssuQty = result[0].tbl_wbs_sakidashi_issuance_item.issued_qty;

                lblGenWHSakIssuPartCode = result[0].tbl_wbs_sakidashi_issuance_item.item;
                lblGenWHSakIssuLotName = result[0].tbl_wbs_sakidashi_issuance_item.item_desc;
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

function GetRuncardNoToPrint(runcardId, runcardNo, runcardKitNo){
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
        url: "generate_prod_runcard_qrcode",
        method: "get",
        data: {
            runcard_id: runcardId,
            runcard_no: runcardNo,
            runcard_kit: runcardKitNo,
            // qr_code_type: qrCodeType,
        },
        dataType: "json",
        beforeSend: function(){
                // $("#lblGenWHMatIssuPoNo").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['data'];
            let poQRCode = JsonObject['po_qrcode'];
            let runcardQRCode = JsonObject['runcard_qrcode'];
            let device_name_print = JsonObject['device_name_print'];

            if(result != null){
                $("#modalGenRuncardToPrint").modal('show');
                $("#imgGenRuncardPoNoBarcode").attr('src', poQRCode);
                $("#imgGenRuncardBarcode").attr('src', runcardQRCode);

                $("#lblRuncardPoNo").text(result.po_no);
                $("#lblRuncardProductName").text(device_name_print);
                $("#lblRuncardNo").text(result.runcard_no);
                $("#lblRuncardKitNo").text(result.kit_number);
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

function GetAutoToPrint(runcardStationId){
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
        url: "generate_auto_sticker",
        method: "get",
        data: {
            runcardStationId: runcardStationId,
        },
        dataType: "json",
        beforeSend: function(){
          // $("#lblGenWHMatIssuPoNo").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['data'];
            let lbl = JsonObject['lbl'];
            let lbl2 = JsonObject['lbl2'];

            if(result != null){
                $("#modalAutoToPrint").modal('show');

                 $("#lblAutoDetails").html(lbl);

                // $("#lblDeviceName").text(device_name_print);
                // $("#lblPoNo").text(result.production_runcard_info['po_no']);
                // $("#lblCTName").text(result.prod_runcard_station_material_details[0]['material_name']);
                // $("#lblCTLotNum").text(result.prod_runcard_station_material_details[0]['lot_no']);
                // $("#lblPairNum").text(result.production_runcard_info['pair_no']);
                // $("#lblMachineNum").text(result.prod_runcard_station_machine_details[0]['machine_info']['machine_name']);
            }
            else{
                toastr.error('Sticker Generation Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetDefectEscalationNoToPrint(runcardId, runcardNo){
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
        url: "generate_defect_escalation_qrcode",
        method: "get",
        data: {
            runcard_id: runcardId,
            runcard_no: runcardNo,
            // qr_code_type: qrCodeType,
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
            let runcardQRCode = JsonObject['runcard_qrcode'];
            let device_name_print = JsonObject['device_name_print'];

            if(result != null){
                $("#modalGenRuncardToPrint").modal('show');
                $("#imgGenRuncardPoNoBarcode").attr('src', poQRCode);
                $("#imgGenRuncardBarcode").attr('src', runcardQRCode);

                $("#lblRuncardPoNo").text(result.po_no);
                $("#lblRuncardProductName").text(device_name_print);

                $("#lblRuncardNo").text(result.defect_escalation_no);

                $("#lblStation").text(result.station_sub_station_info.sub_station.name);

                $("#lblDefectMode1").text(result.rework_station_many_details[0].mode_of_defect_info.name);
                $("#lblDefectMode2").text(result.rework_station_many_details[1].mode_of_defect_info.name);
                $("#lblDefectMode3").text(result.rework_station_many_details[2].mode_of_defect_info.name);
                $("#lblDefectMode4").text(result.rework_station_many_details[3].mode_of_defect_info.name);
                $("#lblDefectMode5").text(result.rework_station_many_details[4].mode_of_defect_info.name);
                // $("#lblDefectMode1").text(result.rework_station_many_details[0].mode_of_defect_info.name +' ('+ result.rework_station_many_details[0].unit_no+')');
                // $("#lblDefectMode2").text(result.rework_station_many_details[1].mode_of_defect_info.name +' ('+ result.rework_station_many_details[1].unit_no+')');
                // $("#lblDefectMode3").text(result.rework_station_many_details[2].mode_of_defect_info.name +' ('+ result.rework_station_many_details[2].unit_no+')');
                // $("#lblDefectMode4").text(result.rework_station_many_details[3].mode_of_defect_info.name +' ('+ result.rework_station_many_details[3].unit_no+')');
                // $("#lblDefectMode5").text(result.rework_station_many_details[4].mode_of_defect_info.name +' ('+ result.rework_station_many_details[4].unit_no+')');
                // $("#lblRuncardPoDevName").text(result[0].device_name);
                // $("#lblRuncardPoKitNo").text(result[0].kit_no);
                // $("#lblRuncardPoKitQty").text(result[0].kit_qty);

                // imgResultMatIssuePoNoQrCode = poQRCode;
                // imgResultMatIssueQrCode = whsSlipQRcode;
                // lblRuncardPoNo = result[0].po_no;
                // lblRuncardPoDevName = result[0].device_name;
                // lblRuncardPoKitNo = result[0].kit_no;
                // lblRuncardPoKitQty = result[0].kit_qty;
                // lblRuncardTransSlip = result[0].issuance_no;
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

function GetPartsPrepWWToPrint(PartsPrepId, qrCodeType){
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
        url: "fn_get_parts_prep_by_id",
        method: "get",
        data: {
            id: PartsPrepId,
        },
        dataType: "json",
        beforeSend: function(){
                $("#lblGenPartsPrepWW").text('');
                $("#lblGenPartsPrepQty").text('');
                $("#lblGenPartsPrepOpt1").text('');
                $("#lblGenPartsPrepOpt2").text('');
        },
        success: function(JsonObject){
            let result = JsonObject['partsprepdata'];
            let user_details = JsonObject['user_details'];
            let opt1_qrcode = JsonObject['opt1_qrcode'];
            let opt2_qrcode = JsonObject['opt2_qrcode'];

            if(result.length > 0){
                $("#modalGenWWToPrint").modal('show');

                $("#imgGenOpt1Barcode").attr('src', opt1_qrcode);
                $("#imgGenOpt2Barcode").attr('src', opt2_qrcode);

                $("#lblGenPartsPrepWW").text(result[0].ww);
                $("#lblGenPartsPrepQty").text(result[0].qty_input);

                if (user_details.length > 1){
                  $("#lblGenPartsPrepOpt1").text(user_details[0][0].employee_id);
                  $("#lblGenPartsPrepOpt2").text(user_details[1][0].employee_id);
                  lblGenPartsPrepOpt2 = user_details[1][0].employee_id;
                }else{
                  $("#lblGenPartsPrepOpt1").text(user_details[0][0].employee_id);
                  lblGenPartsPrepOpt1 = user_details[0][0].employee_id;
                }

                lblGenPartsPrepWW = result[0].ww;
                lblGenPartsPrepQty = result[0].qty_input;



                imgGenOpt1Barcode = opt1_qrcode;
                imgGenOpt2Barcode = opt2_qrcode;
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
