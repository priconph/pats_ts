function GetKittingInfoByIssuanceNo(issuanceNo){
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
        url: "get_kitting_info_by_issuance_no",
        method: "get",
        data: {
            issuance_no: issuanceNo,
        },
        dataType: "json",
        beforeSend: function(){
            $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
            // $("input[name='issuance_no']", $("#frmSearchKittingInfo")).val('');
            $("input[name='po_no']", $("#frmSearchKittingInfo")).val('');
            $("input[name='device_code']", $("#frmSearchKittingInfo")).val('');
            $("input[name='device_name']", $("#frmSearchKittingInfo")).val('');
            $("input[name='po_qty']", $("#frmSearchKittingInfo")).val('');
            $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val('');
            $("input[name='kit_no']", $("#frmSearchKittingInfo")).val('');
            $("input[name='prep_by']", $("#frmSearchKittingInfo")).val('');
            $("input[name='status']", $("#frmSearchKittingInfo")).val('');
            $("input[name='created_by']", $("#frmSearchKittingInfo")).val('');
            $("input[name='created_date']", $("#frmSearchKittingInfo")).val('');
            $("input[name='updated_by']", $("#frmSearchKittingInfo")).val('');
            $("input[name='updated_date']", $("#frmSearchKittingInfo")).val('');
        },
        success: function(data){
            let result = data['kitting_info'];
            // console.log(result);
            dtKittingDetails.draw();
            dtIssuanceDetails.draw();
            if(result != null){
                $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val(result['id']);
                $("input[name='po_no']", $("#frmSearchKittingInfo")).val(result['po_no']);
                $("input[name='device_code']", $("#frmSearchKittingInfo")).val(result['device_code']);
                $("input[name='device_name']", $("#frmSearchKittingInfo")).val(result['device_name']);
                $("input[name='po_qty']", $("#frmSearchKittingInfo")).val(result['po_qty']);
                $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val(result['kit_qty']);
                $("input[name='kit_no']", $("#frmSearchKittingInfo")).val(result['kit_no']);
                $("input[name='prep_by']", $("#frmSearchKittingInfo")).val(result['prepared_by']);
                $("input[name='status']", $("#frmSearchKittingInfo")).val(result['status']);
                $("input[name='created_by']", $("#frmSearchKittingInfo")).val(result['create_user']);
                $("input[name='created_date']", $("#frmSearchKittingInfo")).val(result['created_at']);
                $("input[name='updated_by']", $("#frmSearchKittingInfo")).val(result['update_user']);
                $("input[name='updated_date']", $("#frmSearchKittingInfo")).val(result['updated_at']);
            }
            else{
                $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
                $("input[name='po_no']", $("#frmSearchKittingInfo")).val('');
                $("input[name='device_code']", $("#frmSearchKittingInfo")).val('');
                $("input[name='device_name']", $("#frmSearchKittingInfo")).val('');
                $("input[name='po_qty']", $("#frmSearchKittingInfo")).val('');
                $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val('');
                $("input[name='kit_no']", $("#frmSearchKittingInfo")).val('');
                $("input[name='prep_by']", $("#frmSearchKittingInfo")).val('');
                $("input[name='status']", $("#frmSearchKittingInfo")).val('');
                $("input[name='created_by']", $("#frmSearchKittingInfo")).val('');
                $("input[name='created_date']", $("#frmSearchKittingInfo")).val('');
                $("input[name='updated_by']", $("#frmSearchKittingInfo")).val('');
                $("input[name='updated_date']", $("#frmSearchKittingInfo")).val('');
            }
        },
        error: function(data, xhr, status){
          $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
          // $("input[name='issuance_no']", $("#frmSearchKittingInfo")).val('');
          $("input[name='po_no']", $("#frmSearchKittingInfo")).val('');
          $("input[name='device_code']", $("#frmSearchKittingInfo")).val('');
          $("input[name='device_name']", $("#frmSearchKittingInfo")).val('');
          $("input[name='po_qty']", $("#frmSearchKittingInfo")).val('');
          $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val('');
          $("input[name='kit_no']", $("#frmSearchKittingInfo")).val('');
          $("input[name='prep_by']", $("#frmSearchKittingInfo")).val('');
          $("input[name='status']", $("#frmSearchKittingInfo")).val('');
          $("input[name='created_by']", $("#frmSearchKittingInfo")).val('');
          $("input[name='created_date']", $("#frmSearchKittingInfo")).val('');
          $("input[name='updated_by']", $("#frmSearchKittingInfo")).val('');
          $("input[name='updated_date']", $("#frmSearchKittingInfo")).val('');
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function SaveSubKitting(){
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
        url: "save_sub_kitting",
        method: "get",
        data: $("#frmSubKitIssuance").serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
            $("#btnSubKitIssuance").prop('disabled', true);
        },
        success: function(data){
            if(data['result'] == 1){
                $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val(data['result']);
                dtIssuanceDetails.draw();
                $("#mdlSubKitIssuance").modal('hide');
                toastr.success('Saving Success!');
            }
            else{
                // $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
                toastr.error('Saving Failedssss!');
            }
            $("#btnSubKitIssuance").prop('disabled', false);
        },
        error: function(data, xhr, status){
          // $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#btnSubKitIssuance").prop('disabled', false);
        }
    });
}

function EditSubKitting(){
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
        url: "edit_sub_kitting",
        method: "get",
        data: $("#frmSubKitIssuanceEdit").serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#btnSubKitIssuanceEdit").prop('disabled', true);
        },
        success: function(data){
            if(data['result'] == 1){
                $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val(data['result']);
                dtIssuanceDetails.draw();
                $("#mdlSubKitIssuanceEdit").modal('hide');
                toastr.success('Saving Success!');
            }
            else{
                toastr.error('Saving Failedssss!');
            }
            $("#btnSubKitIssuanceEdit").prop('disabled', false);
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#btnSubKitIssuanceEdit").prop('disabled', false);
        }
    });
}

EditSubKitting

function ViewPatsKittingById(patsKittingId){
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
        url: "get_kitting_by_id",
        method: "get",
        data: {
          kitting_id: patsKittingId,
        },
        dataType: "json",
        beforeSend: function(){
          $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val('');
        },
        success: function(data){
            if(data['kitting'].length > 0){
                $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val(data['kitting'][0]['sub_kit_qty']);
            }
            else{
                $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val('');
                toastr.error('No record found.');
            }
            // $("#btnSubKitIssuance").prop('disabled', false);
        },
        error: function(data, xhr, status){
          $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val('');
          // $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#btnSubKitIssuance").prop('disabled', false);
        }
    });
}

function ViewPatsKittingByIdEdit(patsKittingId){
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
        url: "get_kitting_by_id",
        method: "get",
        data: {
          kitting_id: patsKittingId,
        },
        dataType: "json",
        beforeSend: function(){
          $("input[name='esub_kit_qty']", $("#frmSubKitIssuanceEdit")).val('');
        },
        success: function(data){
            if(data['kitting'].length > 0){
                $("input[name='esub_kit_qty']", $("#frmSubKitIssuanceEdit")).val(data['kitting'][0]['sub_kit_qty']);
            }
            else{
                $("input[name='esub_kit_qty']", $("#frmSubKitIssuanceEdit")).val('');
                toastr.error('No record found.');
            }
        },
        error: function(data, xhr, status){
          $("input[name='esub_kit_qty']", $("#frmSubKitIssuanceEdit")).val('');
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#btnSubKitIssuanceEdit").prop('disabled', false);
        }
    });
}

function GenerateQRCodeKittingById(patsKittingId){
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
        url: "generate_sub_kit_qrcode",
        method: "get",
        data: {
          id: patsKittingId,
        },
        dataType: "json",
        beforeSend: function(){
          // $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val('');
            $("#mdlPrintSubKit").modal('hide');
        },
        success: function(data){
            if(data['data'] != null){
                $("#mdlPrintSubKit").modal('show');
                $("#imgPrintSubKitPONo").attr('src', data['po_qrcode']);
                $("#imgGenWHMatIssuIdBarcode").attr('src', data['lot_no_qrcode']);
                $("#imgGenWHMatIssuIdBarcode2").attr('src', data['item_code_qrcode']);
                $("#lblPrintSubKitPONo").html(data['data']['sub_kit_desc'].split(" | ")[2]);
                $("#lblPrintSubKitKitNo").html(data['data']['sub_kit_desc'].split(" | ")[0]);
                $("#lblPrintSubKitKitter").html(data['data']['sub_kit_desc'].split(" | ")[1]);
                $("#lblPrintSubKitLotNo").html(data['data']['sub_kit_desc'].split(" | ")[4]);
                $("#lblPrintSubKitItemCode").html(data['data']['sub_kit_desc'].split(" | ")[5]);
                $("#lblPrintSubKitItemDesc").html(data['data']['sub_kit_desc'].split(" | ")[6]);
                $("#lblPrintSubKitIssuedQty").html('QTY ' + data['data']['sub_kit_desc'].split(" | ")[3] + ' pc(s)');

                $("#txtSrcPrintSubKitPONo").val(data['po_qrcode']);
                $("#txtSrcGenWHMatIssuIdBarcode").val(data['lot_no_qrcode']);
                $("#txtSrcGenWHMatIssuIdBarcode2").val(data['item_code_qrcode']);
                $("#txtPrintSubKitPONo").val(data['data']['sub_kit_desc'].split(" | ")[2]);
                $("#txtPrintSubKitKitNo").val(data['data']['sub_kit_desc'].split(" | ")[0]);
                $("#txtPrintSubKitKitter").val(data['data']['sub_kit_desc'].split(" | ")[1]);
                $("#txtPrintSubKitLotNo").val(data['data']['sub_kit_desc'].split(" | ")[4]);
                $("#txtPrintSubKitCounter").val(data['data']['sub_kit_desc'].split(" | ")[9]);
                $("#txtPrintSubKitItemCode").val(data['data']['sub_kit_desc'].split(" | ")[5]);
                $("#txtPrintSubKitItemDesc").val(data['data']['sub_kit_desc'].split(" | ")[6]);
                $("#txtPrintSubKitIssuedQty").val(data['data']['sub_kit_desc'].split(" | ")[3]);
                $("#txtPrintSubKitSubKitQty").val(data['data']['sub_kit_qty']);
                // $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val(data['kitting'][0]['sub_kit_qty']);
            }
            else{
                // $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val('');
                toastr.error('No record found.');
            }
            // $("#btnSubKitIssuance").prop('disabled', false);
        },
        error: function(data, xhr, status){
          $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val('');
          $("#mdlPrintSubKit").modal('hide');
          // $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#btnSubKitIssuance").prop('disabled', false);
        }
    });
}