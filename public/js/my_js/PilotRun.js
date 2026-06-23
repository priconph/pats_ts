function AddPilotRunA(){
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
        url: "add_pilotrun_Adetails",
        method: "post",
        data: $('#frm_pilotrun_Adetails').serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("#id_btn_save_pilotAIcon").addClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalPilotRunDetailsA").modal('hide');
            	$("#frm_pilotrun_Adetails")[0].reset();

                dt_pilot_run.draw();
                toastr.success('Data was succesfully saved!');
            }
            else{
                toastr.error('Saving Failed! Please check all fields. Put N/A if not applicable. Check all radio button.');

                if (JsonObject['error']['txt_assembly_type'] === undefined) {
                    $("#txt_assembly_type").removeClass('is-invalid');
                    $("#txt_assembly_type").attr('title', '');
                }
                else {
                    $("#txt_assembly_type").addClass('is-invalid');
                    $("#txt_assembly_type").attr('title', JsonObject['error']['txt_assembly_type']);
                }

                if (JsonObject['error']['hidden_AKLdrawing_pn'] === undefined) {
                    $("#txt_AKLdrawing_pn").removeClass('is-invalid');
                    $("#txt_AKLdrawing_pn").attr('title', '');
                }
                else {
                    $("#txt_AKLdrawing_pn").addClass('is-invalid');
                    $("#txt_AKLdrawing_pn").attr('title', JsonObject['error']['hidden_AKLdrawing_pn']);
                }

                if (JsonObject['error']['hidden_provi_req_pn'] === undefined) {
                    $("#txt_provi_req_pn").removeClass('is-invalid');
                    $("#txt_provi_req_pn").attr('title', '');
                }
                else {
                    $("#txt_provi_req_pn").addClass('is-invalid');
                    $("#txt_provi_req_pn").attr('title', JsonObject['error']['hidden_provi_req_pn']);
                }

                if (JsonObject['error']['hidden_assembly_type_pn'] === undefined) {
                    $("#txt_assembly_type_pn").removeClass('is-invalid');
                    $("#txt_assembly_type_pn").attr('title', '');
                }
                else {
                    $("#txt_assembly_type_pn").addClass('is-invalid');
                    $("#txt_assembly_type_pn").attr('title', JsonObject['error']['hidden_assembly_type_pn']);
                }

                if (JsonObject['error']['txt_with_accessory_req_no'] === undefined) {
                    $("#txt_with_accessory_req_no").removeClass('is-invalid');
                    $("#txt_with_accessory_req_no").attr('title', '');
                }
                else {
                    $("#txt_with_accessory_req_no").addClass('is-invalid');
                    $("#txt_with_accessory_req_no").attr('title', JsonObject['error']['txt_with_accessory_req_no']);
                }

                if (JsonObject['error']['txt_engr_confirmation'] === undefined) {
                    $("#txt_engr_confirmation").removeClass('is-invalid');
                    $("#txt_engr_confirmation").attr('title', '');
                }
                else {
                    $("#txt_engr_confirmation").addClass('is-invalid');
                    $("#txt_engr_confirmation").attr('title', JsonObject['error']['txt_engr_confirmation']);
                }

                if (JsonObject['error']['txt_engr_confirmation_due_date'] === undefined) {
                    $("#txt_engr_confirmation_due_date").removeClass('is-invalid');
                    $("#txt_engr_confirmation_due_date").attr('title', '');
                }
                else {
                    $("#txt_engr_confirmation_due_date").addClass('is-invalid');
                    $("#txt_engr_confirmation_due_date").attr('title', JsonObject['error']['txt_engr_confirmation_due_date']);
                }

                if (JsonObject['error']['pair_no'] === undefined) {
                    $("#txt_pair_no").removeClass('is-invalid');
                    $("#txt_pair_no").attr('title', '');
                }
                else {
                    $("#txt_pair_no").addClass('is-invalid');
                    $("#txt_pair_no").attr('title', JsonObject['error']['pair_no']);
                }

                if (JsonObject['error']['control_type'] === undefined) {
                    $("input[name='control_type']").removeClass('is-invalid');
                    $("input[name='control_type']").attr('title', '');
                }
                else {
                    $("input[name='control_type']").addClass('is-invalid');
                    $("input[name='control_type']").attr('title', JsonObject['error']['control_type']);
                }

                if (JsonObject['error']['special_control_type'] === undefined) {
                    $("input[name='special_control_type']").removeClass('is-invalid');
                    $("input[name='special_control_type']").attr('title', '');
                }
                else {
                    $("input[name='special_control_type']").addClass('is-invalid');
                    $("input[name='special_control_type']").attr('title', JsonObject['error']['special_control_type']);
                }

                if (JsonObject['error']['txt_AKLdrawing'] === undefined) {
                    $("#txt_AKLdrawing").removeClass('is-invalid');
                    $("#txt_AKLdrawing").attr('title', '');
                }
                else {
                    $("#txt_AKLdrawing").addClass('is-invalid');
                    $("#txt_AKLdrawing").attr('title', JsonObject['error']['txt_AKLdrawing']);
                }
                if (JsonObject['error']['txt_AKLdrawing_rev'] === undefined) {
                    $("#txt_AKLdrawing_rev").removeClass('is-invalid');
                    $("#txt_AKLdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_AKLdrawing_rev").addClass('is-invalid');
                    $("#txt_AKLdrawing_rev").attr('title', JsonObject['error']['txt_AKLdrawing_rev']);
                }
                if (JsonObject['error']['txt_AKLdrawing_page'] === undefined) {
                    $("#txt_AKLdrawing_page").removeClass('is-invalid');
                    $("#txt_AKLdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_AKLdrawing_page").addClass('is-invalid');
                    $("#txt_AKLdrawing_page").attr('title', JsonObject['error']['txt_AKLdrawing_page']);
                }

                if (JsonObject['error']['txt_GAGdrawing'] === undefined) {
                    $("#txt_GAGdrawing").removeClass('is-invalid');
                    $("#txt_GAGdrawing").attr('title', '');
                }
                else {
                    $("#txt_GAGdrawing").addClass('is-invalid');
                    $("#txt_GAGdrawing").attr('title', JsonObject['error']['txt_GAGdrawing']);
                }
                if (JsonObject['error']['txt_GAGdrawing_rev'] === undefined) {
                    $("#txt_GAGdrawing_rev").removeClass('is-invalid');
                    $("#txt_GAGdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_GAGdrawing_rev").addClass('is-invalid');
                    $("#txt_GAGdrawing_rev").attr('title', JsonObject['error']['txt_GAGdrawing_rev']);
                }
                if (JsonObject['error']['txt_GAGdrawing_page'] === undefined) {
                    $("#txt_GAGdrawing_page").removeClass('is-invalid');
                    $("#txt_GAGdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_GAGdrawing_page").addClass('is-invalid');
                    $("#txt_GAGdrawing_page").attr('title', JsonObject['error']['txt_GAGdrawing_page']);
                }

                if (JsonObject['error']['txt_PackDocdrawing'] === undefined) {
                    $("#txt_PackDocdrawing").removeClass('is-invalid');
                    $("#txt_PackDocdrawing").attr('title', '');
                }
                else {
                    $("#txt_PackDocdrawing").addClass('is-invalid');
                    $("#txt_PackDocdrawing").attr('title', JsonObject['error']['txt_PackDocdrawing']);
                }
                if (JsonObject['error']['txt_PackDocdrawing_rev'] === undefined) {
                    $("#txt_PackDocdrawing_rev").removeClass('is-invalid');
                    $("#txt_PackDocdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_PackDocdrawing_rev").addClass('is-invalid');
                    $("#txt_PackDocdrawing_rev").attr('title', JsonObject['error']['txt_PackDocdrawing_rev']);
                }
                if (JsonObject['error']['txt_PackDocdrawing_page'] === undefined) {
                    $("#txt_PackDocdrawing_page").removeClass('is-invalid');
                    $("#txt_PackDocdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_PackDocdrawing_page").addClass('is-invalid');
                    $("#txt_PackDocdrawing_page").attr('title', JsonObject['error']['txt_PackDocdrawing_page']);
                }

                if (JsonObject['error']['txt_PackSpecsdrawing'] === undefined) {
                    $("#txt_PackSpecsdrawing").removeClass('is-invalid');
                    $("#txt_PackSpecsdrawing").attr('title', '');
                }
                else {
                    $("#txt_PackSpecsdrawing").addClass('is-invalid');
                    $("#txt_PackSpecsdrawing").attr('title', JsonObject['error']['txt_PackSpecsdrawing']);
                }
                if (JsonObject['error']['txt_PackSpecsdrawing_rev'] === undefined) {
                    $("#txt_PackSpecsdrawing_rev").removeClass('is-invalid');
                    $("#txt_PackSpecsdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_PackSpecsdrawing_rev").addClass('is-invalid');
                    $("#txt_PackSpecsdrawing_rev").attr('title', JsonObject['error']['txt_PackSpecsdrawing_rev']);
                }
                if (JsonObject['error']['txt_PackSpecsdrawing_page'] === undefined) {
                    $("#txt_PackSpecsdrawing_page").removeClass('is-invalid');
                    $("#txt_PackSpecsdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_PackSpecsdrawing_page").addClass('is-invalid');
                    $("#txt_PackSpecsdrawing_page").attr('title', JsonObject['error']['txt_PackSpecsdrawing_page']);
                }

                if (JsonObject['error']['txt_OTUDdrawing'] === undefined) {
                    $("#txt_OTUDdrawing").removeClass('is-invalid');
                    $("#txt_OTUDdrawing").attr('title', '');
                }
                else {
                    $("#txt_OTUDdrawing").addClass('is-invalid');
                    $("#txt_OTUDdrawing").attr('title', JsonObject['error']['txt_OTUDdrawing']);
                }
                if (JsonObject['error']['txt_OTUDdrawing_rev'] === undefined) {
                    $("#txt_OTUDdrawing_rev").removeClass('is-invalid');
                    $("#txt_OTUDdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_OTUDdrawing_rev").addClass('is-invalid');
                    $("#txt_OTUDdrawing_rev").attr('title', JsonObject['error']['txt_OTUDdrawing_rev']);
                }
                if (JsonObject['error']['txt_OTUDdrawing_page'] === undefined) {
                    $("#txt_OTUDdrawing_page").removeClass('is-invalid');
                    $("#txt_OTUDdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_OTUDdrawing_page").addClass('is-invalid');
                    $("#txt_OTUDdrawing_page").attr('title', JsonObject['error']['txt_OTUDdrawing_page']);
                }

                if (JsonObject['error']['txt_WIDdrawing'] === undefined) {
                    $("#txt_WIDdrawing").removeClass('is-invalid');
                    $("#txt_WIDdrawing").attr('title', '');
                }
                else {
                    $("#txt_WIDdrawing").addClass('is-invalid');
                    $("#txt_WIDdrawing").attr('title', JsonObject['error']['txt_WIDdrawing']);
                }
                if (JsonObject['error']['txt_WIDdrawing_rev'] === undefined) {
                    $("#txt_WIDdrawing_rev").removeClass('is-invalid');
                    $("#txt_WIDdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_WIDdrawing_rev").addClass('is-invalid');
                    $("#txt_WIDdrawing_rev").attr('title', JsonObject['error']['txt_WIDdrawing_rev']);
                }
                if (JsonObject['error']['txt_WIDdrawing_page'] === undefined) {
                    $("#txt_WIDdrawing_page").removeClass('is-invalid');
                    $("#txt_WIDdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_WIDdrawing_page").addClass('is-invalid');
                    $("#txt_WIDdrawing_page").attr('title', JsonObject['error']['txt_WIDdrawing_page']);
                }

                if (JsonObject['error']['txt_parts_printing'] === undefined) {
                    $("#txt_parts_printing").removeClass('is-invalid');
                    $("#txt_parts_printing").attr('title', '');
                }
                else {
                    $("#txt_parts_printing").addClass('is-invalid');
                    $("#txt_parts_printing").attr('title', JsonObject['error']['txt_parts_printing']);
                }

                if (JsonObject['error']['txt_printing_req'] === undefined) {
                    $("#txt_printing_req").removeClass('is-invalid');
                    $("#txt_printing_req").attr('title', '');
                }
                else {
                    $("#txt_printing_req").addClass('is-invalid');
                    $("#txt_printing_req").attr('title', JsonObject['error']['txt_printing_req']);
                }

                if (JsonObject['error']['txt_char_printed'] === undefined) {
                    $("#txt_char_printed").removeClass('is-invalid');
                    $("#txt_char_printed").attr('title', '');
                }
                else {
                    $("#txt_char_printed").addClass('is-invalid');
                    $("#txt_char_printed").attr('title', JsonObject['error']['txt_char_printed']);
                }

                if (JsonObject['error']['txt_page_ref_AKLdrawing'] === undefined) {
                    $("#txt_page_ref_AKLdrawing").removeClass('is-invalid');
                    $("#txt_page_ref_AKLdrawing").attr('title', '');
                }
                else {
                    $("#txt_page_ref_AKLdrawing").addClass('is-invalid');
                    $("#txt_page_ref_AKLdrawing").attr('title', JsonObject['error']['txt_page_ref_AKLdrawing']);
                }

                if (JsonObject['error']['txt_page_ref_GAGdrawing'] === undefined) {
                    $("#txt_page_ref_GAGdrawing").removeClass('is-invalid');
                    $("#txt_page_ref_GAGdrawing").attr('title', '');
                }
                else {
                    $("#txt_page_ref_GAGdrawing").addClass('is-invalid');
                    $("#txt_page_ref_GAGdrawing").attr('title', JsonObject['error']['txt_page_ref_GAGdrawing']);
                }

                if (JsonObject['error']['txt_gauge_control_no'] === undefined) {
                    $("#txt_gauge_control_no").removeClass('is-invalid');
                    $("#txt_gauge_control_no").attr('title', '');
                }
                else {
                    $("#txt_gauge_control_no").addClass('is-invalid');
                    $("#txt_gauge_control_no").attr('title', JsonObject['error']['txt_gauge_control_no']);
                }

            }

            // $("#id_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iid_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        }
    }); // OK 01-25-2023
}
function AddPilotRunA(){
    // alert("test");
    // return;
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
        url: "add_pilotrun_Adetails",
        method: "post",
        data: $('#frm_pilotrun_Adetails').serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("#id_btn_save_pilotAIcon").addClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalPilotRunDetailsA").modal('hide');
            	$("#frm_pilotrun_Adetails")[0].reset();

                dt_pilot_run.draw();
                toastr.success('Data was succesfully saved!');
            }
            else{
                toastr.error('Saving Failed! Please check all fields. Put N/A if not applicable. Check all radio button.');

                if (JsonObject['error']['txt_assembly_type'] === undefined) {
                    $("#txt_assembly_type").removeClass('is-invalid');
                    $("#txt_assembly_type").attr('title', '');
                }
                else {
                    $("#txt_assembly_type").addClass('is-invalid');
                    $("#txt_assembly_type").attr('title', JsonObject['error']['txt_assembly_type']);
                }

                if (JsonObject['error']['hidden_AKLdrawing_pn'] === undefined) {
                    $("#txt_AKLdrawing_pn").removeClass('is-invalid');
                    $("#txt_AKLdrawing_pn").attr('title', '');
                }
                else {
                    $("#txt_AKLdrawing_pn").addClass('is-invalid');
                    $("#txt_AKLdrawing_pn").attr('title', JsonObject['error']['hidden_AKLdrawing_pn']);
                }

                if (JsonObject['error']['hidden_provi_req_pn'] === undefined) {
                    $("#txt_provi_req_pn").removeClass('is-invalid');
                    $("#txt_provi_req_pn").attr('title', '');
                }
                else {
                    $("#txt_provi_req_pn").addClass('is-invalid');
                    $("#txt_provi_req_pn").attr('title', JsonObject['error']['hidden_provi_req_pn']);
                }

                if (JsonObject['error']['hidden_assembly_type_pn'] === undefined) {
                    $("#txt_assembly_type_pn").removeClass('is-invalid');
                    $("#txt_assembly_type_pn").attr('title', '');
                }
                else {
                    $("#txt_assembly_type_pn").addClass('is-invalid');
                    $("#txt_assembly_type_pn").attr('title', JsonObject['error']['hidden_assembly_type_pn']);
                }

                if (JsonObject['error']['txt_with_accessory_req_no'] === undefined) {
                    $("#txt_with_accessory_req_no").removeClass('is-invalid');
                    $("#txt_with_accessory_req_no").attr('title', '');
                }
                else {
                    $("#txt_with_accessory_req_no").addClass('is-invalid');
                    $("#txt_with_accessory_req_no").attr('title', JsonObject['error']['txt_with_accessory_req_no']);
                }

                if (JsonObject['error']['txt_engr_confirmation'] === undefined) {
                    $("#txt_engr_confirmation").removeClass('is-invalid');
                    $("#txt_engr_confirmation").attr('title', '');
                }
                else {
                    $("#txt_engr_confirmation").addClass('is-invalid');
                    $("#txt_engr_confirmation").attr('title', JsonObject['error']['txt_engr_confirmation']);
                }

                if (JsonObject['error']['txt_engr_confirmation_due_date'] === undefined) {
                    $("#txt_engr_confirmation_due_date").removeClass('is-invalid');
                    $("#txt_engr_confirmation_due_date").attr('title', '');
                }
                else {
                    $("#txt_engr_confirmation_due_date").addClass('is-invalid');
                    $("#txt_engr_confirmation_due_date").attr('title', JsonObject['error']['txt_engr_confirmation_due_date']);
                }

                if (JsonObject['error']['pair_no'] === undefined) {
                    $("#txt_pair_no").removeClass('is-invalid');
                    $("#txt_pair_no").attr('title', '');
                }
                else {
                    $("#txt_pair_no").addClass('is-invalid');
                    $("#txt_pair_no").attr('title', JsonObject['error']['pair_no']);
                }

                if (JsonObject['error']['control_type'] === undefined) {
                    $("input[name='control_type']").removeClass('is-invalid');
                    $("input[name='control_type']").attr('title', '');
                }
                else {
                    $("input[name='control_type']").addClass('is-invalid');
                    $("input[name='control_type']").attr('title', JsonObject['error']['control_type']);
                }

                if (JsonObject['error']['special_control_type'] === undefined) {
                    $("input[name='special_control_type']").removeClass('is-invalid');
                    $("input[name='special_control_type']").attr('title', '');
                }
                else {
                    $("input[name='special_control_type']").addClass('is-invalid');
                    $("input[name='special_control_type']").attr('title', JsonObject['error']['special_control_type']);
                }

                if (JsonObject['error']['txt_AKLdrawing'] === undefined) {
                    $("#txt_AKLdrawing").removeClass('is-invalid');
                    $("#txt_AKLdrawing").attr('title', '');
                }
                else {
                    $("#txt_AKLdrawing").addClass('is-invalid');
                    $("#txt_AKLdrawing").attr('title', JsonObject['error']['txt_AKLdrawing']);
                }
                if (JsonObject['error']['txt_AKLdrawing_rev'] === undefined) {
                    $("#txt_AKLdrawing_rev").removeClass('is-invalid');
                    $("#txt_AKLdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_AKLdrawing_rev").addClass('is-invalid');
                    $("#txt_AKLdrawing_rev").attr('title', JsonObject['error']['txt_AKLdrawing_rev']);
                }
                if (JsonObject['error']['txt_AKLdrawing_page'] === undefined) {
                    $("#txt_AKLdrawing_page").removeClass('is-invalid');
                    $("#txt_AKLdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_AKLdrawing_page").addClass('is-invalid');
                    $("#txt_AKLdrawing_page").attr('title', JsonObject['error']['txt_AKLdrawing_page']);
                }

                if (JsonObject['error']['txt_GAGdrawing'] === undefined) {
                    $("#txt_GAGdrawing").removeClass('is-invalid');
                    $("#txt_GAGdrawing").attr('title', '');
                }
                else {
                    $("#txt_GAGdrawing").addClass('is-invalid');
                    $("#txt_GAGdrawing").attr('title', JsonObject['error']['txt_GAGdrawing']);
                }
                if (JsonObject['error']['txt_GAGdrawing_rev'] === undefined) {
                    $("#txt_GAGdrawing_rev").removeClass('is-invalid');
                    $("#txt_GAGdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_GAGdrawing_rev").addClass('is-invalid');
                    $("#txt_GAGdrawing_rev").attr('title', JsonObject['error']['txt_GAGdrawing_rev']);
                }
                if (JsonObject['error']['txt_GAGdrawing_page'] === undefined) {
                    $("#txt_GAGdrawing_page").removeClass('is-invalid');
                    $("#txt_GAGdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_GAGdrawing_page").addClass('is-invalid');
                    $("#txt_GAGdrawing_page").attr('title', JsonObject['error']['txt_GAGdrawing_page']);
                }

                if (JsonObject['error']['txt_PackDocdrawing'] === undefined) {
                    $("#txt_PackDocdrawing").removeClass('is-invalid');
                    $("#txt_PackDocdrawing").attr('title', '');
                }
                else {
                    $("#txt_PackDocdrawing").addClass('is-invalid');
                    $("#txt_PackDocdrawing").attr('title', JsonObject['error']['txt_PackDocdrawing']);
                }
                if (JsonObject['error']['txt_PackDocdrawing_rev'] === undefined) {
                    $("#txt_PackDocdrawing_rev").removeClass('is-invalid');
                    $("#txt_PackDocdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_PackDocdrawing_rev").addClass('is-invalid');
                    $("#txt_PackDocdrawing_rev").attr('title', JsonObject['error']['txt_PackDocdrawing_rev']);
                }
                if (JsonObject['error']['txt_PackDocdrawing_page'] === undefined) {
                    $("#txt_PackDocdrawing_page").removeClass('is-invalid');
                    $("#txt_PackDocdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_PackDocdrawing_page").addClass('is-invalid');
                    $("#txt_PackDocdrawing_page").attr('title', JsonObject['error']['txt_PackDocdrawing_page']);
                }

                if (JsonObject['error']['txt_PackSpecsdrawing'] === undefined) {
                    $("#txt_PackSpecsdrawing").removeClass('is-invalid');
                    $("#txt_PackSpecsdrawing").attr('title', '');
                }
                else {
                    $("#txt_PackSpecsdrawing").addClass('is-invalid');
                    $("#txt_PackSpecsdrawing").attr('title', JsonObject['error']['txt_PackSpecsdrawing']);
                }
                if (JsonObject['error']['txt_PackSpecsdrawing_rev'] === undefined) {
                    $("#txt_PackSpecsdrawing_rev").removeClass('is-invalid');
                    $("#txt_PackSpecsdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_PackSpecsdrawing_rev").addClass('is-invalid');
                    $("#txt_PackSpecsdrawing_rev").attr('title', JsonObject['error']['txt_PackSpecsdrawing_rev']);
                }
                if (JsonObject['error']['txt_PackSpecsdrawing_page'] === undefined) {
                    $("#txt_PackSpecsdrawing_page").removeClass('is-invalid');
                    $("#txt_PackSpecsdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_PackSpecsdrawing_page").addClass('is-invalid');
                    $("#txt_PackSpecsdrawing_page").attr('title', JsonObject['error']['txt_PackSpecsdrawing_page']);
                }

                if (JsonObject['error']['txt_OTUDdrawing'] === undefined) {
                    $("#txt_OTUDdrawing").removeClass('is-invalid');
                    $("#txt_OTUDdrawing").attr('title', '');
                }
                else {
                    $("#txt_OTUDdrawing").addClass('is-invalid');
                    $("#txt_OTUDdrawing").attr('title', JsonObject['error']['txt_OTUDdrawing']);
                }
                if (JsonObject['error']['txt_OTUDdrawing_rev'] === undefined) {
                    $("#txt_OTUDdrawing_rev").removeClass('is-invalid');
                    $("#txt_OTUDdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_OTUDdrawing_rev").addClass('is-invalid');
                    $("#txt_OTUDdrawing_rev").attr('title', JsonObject['error']['txt_OTUDdrawing_rev']);
                }
                if (JsonObject['error']['txt_OTUDdrawing_page'] === undefined) {
                    $("#txt_OTUDdrawing_page").removeClass('is-invalid');
                    $("#txt_OTUDdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_OTUDdrawing_page").addClass('is-invalid');
                    $("#txt_OTUDdrawing_page").attr('title', JsonObject['error']['txt_OTUDdrawing_page']);
                }

                if (JsonObject['error']['txt_WIDdrawing'] === undefined) {
                    $("#txt_WIDdrawing").removeClass('is-invalid');
                    $("#txt_WIDdrawing").attr('title', '');
                }
                else {
                    $("#txt_WIDdrawing").addClass('is-invalid');
                    $("#txt_WIDdrawing").attr('title', JsonObject['error']['txt_WIDdrawing']);
                }
                if (JsonObject['error']['txt_WIDdrawing_rev'] === undefined) {
                    $("#txt_WIDdrawing_rev").removeClass('is-invalid');
                    $("#txt_WIDdrawing_rev").attr('title', '');
                }
                else {
                    $("#txt_WIDdrawing_rev").addClass('is-invalid');
                    $("#txt_WIDdrawing_rev").attr('title', JsonObject['error']['txt_WIDdrawing_rev']);
                }
                if (JsonObject['error']['txt_WIDdrawing_page'] === undefined) {
                    $("#txt_WIDdrawing_page").removeClass('is-invalid');
                    $("#txt_WIDdrawing_page").attr('title', '');
                }
                else {
                    $("#txt_WIDdrawing_page").addClass('is-invalid');
                    $("#txt_WIDdrawing_page").attr('title', JsonObject['error']['txt_WIDdrawing_page']);
                }

                if (JsonObject['error']['txt_parts_printing'] === undefined) {
                    $("#txt_parts_printing").removeClass('is-invalid');
                    $("#txt_parts_printing").attr('title', '');
                }
                else {
                    $("#txt_parts_printing").addClass('is-invalid');
                    $("#txt_parts_printing").attr('title', JsonObject['error']['txt_parts_printing']);
                }

                if (JsonObject['error']['txt_printing_req'] === undefined) {
                    $("#txt_printing_req").removeClass('is-invalid');
                    $("#txt_printing_req").attr('title', '');
                }
                else {
                    $("#txt_printing_req").addClass('is-invalid');
                    $("#txt_printing_req").attr('title', JsonObject['error']['txt_printing_req']);
                }

                if (JsonObject['error']['txt_char_printed'] === undefined) {
                    $("#txt_char_printed").removeClass('is-invalid');
                    $("#txt_char_printed").attr('title', '');
                }
                else {
                    $("#txt_char_printed").addClass('is-invalid');
                    $("#txt_char_printed").attr('title', JsonObject['error']['txt_char_printed']);
                }

                if (JsonObject['error']['txt_page_ref_AKLdrawing'] === undefined) {
                    $("#txt_page_ref_AKLdrawing").removeClass('is-invalid');
                    $("#txt_page_ref_AKLdrawing").attr('title', '');
                }
                else {
                    $("#txt_page_ref_AKLdrawing").addClass('is-invalid');
                    $("#txt_page_ref_AKLdrawing").attr('title', JsonObject['error']['txt_page_ref_AKLdrawing']);
                }

                if (JsonObject['error']['txt_page_ref_GAGdrawing'] === undefined) {
                    $("#txt_page_ref_GAGdrawing").removeClass('is-invalid');
                    $("#txt_page_ref_GAGdrawing").attr('title', '');
                }
                else {
                    $("#txt_page_ref_GAGdrawing").addClass('is-invalid');
                    $("#txt_page_ref_GAGdrawing").attr('title', JsonObject['error']['txt_page_ref_GAGdrawing']);
                }

                if (JsonObject['error']['txt_gauge_control_no'] === undefined) {
                    $("#txt_gauge_control_no").removeClass('is-invalid');
                    $("#txt_gauge_control_no").attr('title', '');
                }
                else {
                    $("#txt_gauge_control_no").addClass('is-invalid');
                    $("#txt_gauge_control_no").attr('title', JsonObject['error']['txt_gauge_control_no']);
                }

            }

            // $("#id_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iid_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        }
    }); // OK 01-25-2023
}

function AddPilotRunB(){
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
        url: "add_pilotrun_Bdetails",
        method: "post",
        data: $('#frm_pilotrun_Bdetails').serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("#id_btn_save_pilotAIcon").addClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalPilotRunDetailsB").modal('hide');
                $("#frm_pilotrun_Bdetails")[0].reset();

                dt_pilot_run.draw();
              toastr.success('Data was succesfully saved!');
            }
            else{
                toastr.error('Saving Failed! Please check all fields. Put N/A if not applicable');

                // if (JsonObject['error']['input[type=radio][name=term_length]'] === undefined) {
                //     $("input[type=radio][name=term_length]").removeClass('is-invalid');
                //     $("input[type=radio][name=term_length]").attr('title', '');
                // }
                // else {
                //     $("input[type=radio][name=term_length]").addClass('is-invalid');
                //     $("input[type=radio][name=term_length]").attr('title', JsonObject['error']['input[type=radio][name=term_length]']);
                // }

                if (JsonObject['error']['hidden_term_length_pn'] === undefined) {
                    $("#txt_term_length_pn").removeClass('is-invalid');
                    $("#txt_term_length_pn").attr('title', '');
                }
                else {
                    $("#txt_term_length_pn").addClass('is-invalid');
                    $("#txt_term_length_pn").attr('title', JsonObject['error']['hidden_term_length_pn']);
                }

                if (JsonObject['error']['highlights'] === undefined) {
                    $("#txt_highlights").removeClass('is-invalid');
                    $("#txt_highlights").attr('title', '');
                }
                else {
                    $("#txt_highlights").addClass('is-invalid');
                    $("#txt_highlights").attr('title', JsonObject['error']['highlights']);
                }

                if (JsonObject['error']['feedback_report'] === undefined) {
                    $("input[type=radio][name=feedback_report]").removeClass('is-invalid');
                    $("input[type=radio][name=feedback_report]").attr('title', '');
                }
                else {
                    $("input[type=radio][name=feedback_report]").addClass('is-invalid');
                    $("input[type=radio][name=feedback_report]").attr('title', JsonObject['error']['feedback_report']);
                }

            }

            // $("#id_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iid_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        }
    });
}

function AddPilotRunC(){
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
        url: "add_pilotrun_Cdetails",
        method: "post",
        data: $('#frmSaveCriticalFailures').serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("#id_btn_save_pilotAIcon").addClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#mdlSaveCriticalFailures").modal('hide');
                $("#frmSaveCriticalFailures")[0].reset();

                dt_pilot_run.draw();
                dt_pilot_run_c.draw();
                toastr.success('Data was succesfully saved!');
            }
            else{
                toastr.error('Saving Failed! Please check all fields. Put N/A if not applicable');

                // if (JsonObject['error']['txt_parts_printing'] === undefined) {
                //     $("#txt_parts_printing").removeClass('is-invalid');
                //     $("#txt_parts_printing").attr('title', '');
                // }
                // else {
                //     $("#txt_parts_printing").addClass('is-invalid');
                //     $("#txt_parts_printing").attr('title', JsonObject['error']['txt_parts_printing']);
                // }

            }

            // $("#id_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iid_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        }
    });
}

function AddPilotRunD(){
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
        url: "add_pilotrun_Ddetails",
        method: "post",
        data: $('#frm_pilotrun_Ddetails').serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("#id_btn_save_pilotAIcon").addClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalPilotRunDetailsD").modal('hide');
                $("#frm_pilotrun_Ddetails")[0].reset();

                dt_pilot_run.draw();
                dt_pilot_run_c.draw();
                toastr.success('Data was succesfully saved!');
            }else if(JsonObject['result'] == 2){
                toastr.error(JsonObject.error);
            }
            else{
                toastr.error('Saving Failed! Please check all fields. Put N/A if not applicable');

                // if (JsonObject['error']['txt_parts_printing'] === undefined) {
                //     $("#txt_parts_printing").removeClass('is-invalid');
                //     $("#txt_parts_printing").attr('title', '');
                // }
                // else {
                //     $("#txt_parts_printing").addClass('is-invalid');
                //     $("#txt_parts_printing").attr('title', JsonObject['error']['txt_parts_printing']);
                // }

            }

            // $("#id_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iid_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        }
    });
}

function AddPilotRunE(){
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
        url: "add_pilotrun_Edetails",
        method: "post",
        data: $('#frm_pilotrun_Edetails').serialize(),
        dataType: "json",
        beforeSend: function(){
            // $("#id_btn_save_pilotAIcon").addClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalPilotRunDetailsE").modal('hide');
                $("#frm_pilotrun_Edetails")[0].reset();

                dt_pilot_run.draw();
                dt_pilot_run_c.draw();
                toastr.success('Data was succesfully saved!');
            }
            else{
                toastr.error('Saving Failed! Please check all fields. Put N/A if not applicable');

                // if (JsonObject['error']['txt_parts_printing'] === undefined) {
                //     $("#txt_parts_printing").removeClass('is-invalid');
                //     $("#txt_parts_printing").attr('title', '');
                // }
                // else {
                //     $("#txt_parts_printing").addClass('is-invalid');
                //     $("#txt_parts_printing").attr('title', JsonObject['error']['txt_parts_printing']);
                // }

            }

            // $("#id_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iid_btn_save_pilotAIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#id_btn_save_pilotA").removeAttr('disabled');
            // $("#iid_btn_save_pilotAIcon").addClass('fa fa-check');
        }
    });
}

function GetPilotRunToPrint(PilotRunId){
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
        url: "get_pilotrun_toprint_by_id",
        method: "get",
        data: {
            pilotrun_id: PilotRunId
        },
        dataType: "json",
        beforeSend: function(){
                $("#lblGenPilotRunPoNo").text('...');
                $("#lblGenPilotRunSeriesName").text('...');
                $("#lblGenPilotRunQty").text('...');
                $("#lblGenPilotRunPairNo").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['data'];
            let poQRCode = JsonObject['po_qrcode'];
            let device_name_print = JsonObject['device_name_print'];

            if(result.length > 0){
                $("#modalGenPilotRunToPrint").modal('show');
                $("#imgGenPilotRunPoNoBarcode").attr('src', poQRCode);

                $("#lblGenPilotRunPoNo").text(result[0].po_no);
                // $("#lblGenPilotRunSeriesName").text(result[0].series_name);
                // $('#lblGenPilotRunSeriesName').attr('device_name_print', result['device_name_print'] );

                $("#lblGenPilotRunQty").text((result[0].po_qty).toLocaleString("en-US"));
                $("#lblGenPilotRunPairNo").text(result[0].pair_no);

                imgGenPilotRunPoNoBarcode = poQRCode;
                lblGenPilotRunPoNo = result[0].po_no;
                // lblGenPilotRunSeriesName = result[0].series_name;
                lblGenPilotRunSeriesName = device_name_print;
                $('#lblGenPilotRunSeriesName').text(lblGenPilotRunSeriesName);

                lblGenPilotRunQty = result[0].po_qty;
                lblGenPilotRunPairNo = result[0].pair_no;
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








