function GetMaterialKittingList(cboElement){
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: 'get_wbs_kitting_details',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option disabled value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['kitting_details'].length > 0){
                for(let index = 0; index < JsonObject['kitting_details'].length; index++){
                    result += '<option data-code="' + JsonObject['kitting_details'][index].item + '" value="' + JsonObject['kitting_details'][index].item + '--' + JsonObject['kitting_details'][index].item_desc + '" ' + '>' + JsonObject['kitting_details'][index].item_desc + '</option>';
                }
            }
            else{
                result = '<option value=""> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetMaterialKittingListByPoNo(cboElement, poNo){
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: 'get_wbs_kitting_details_by_po_no',
        method: 'get',
        dataType: 'json',
        data: {
            po_no: poNo, 
        },
        beforeSend: function(){
            result = '<option disabled value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            let usage = '';
            let issuedQty = '';

            if(JsonObject['kitting_details'].length > 0){
                for(let index = 0; index < JsonObject['kitting_details'].length; index++){
                    result += '<option data-code="' + JsonObject['kitting_details'][index].item + '" item-desc="' + JsonObject['kitting_details'][index].item_desc + '" usage="' + JsonObject['kitting_details'][index].usage + '" issued-qty="' + JsonObject['kitting_details'][index].issued_qty + '" value="' + JsonObject['kitting_details'][index].item + '--' + JsonObject['kitting_details'][index].item_desc + '--' + JsonObject['kitting_details'][index].id + '" ' + '>' + JsonObject['kitting_details'][index].item_desc + '</option>';
                    if(index <= 0){
                        usage = JsonObject['kitting_details'][index].usage;
                        issuedQty = JsonObject['kitting_details'][index].issued_qty;
                    }
                }
            }
            else{
                result = '<option value=""> -- No record found -- </option>';
            }

            cboElement.html(result);


            $("input[name='quantity']", $("#frmSaveAccessory")).val(usage);
            $("input[name='usage_per_socket']", $("#frmSaveAccessory")).val(issuedQty);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}