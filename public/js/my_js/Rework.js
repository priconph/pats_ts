// VARIABLES

// FUNCTIONS
// Save Rework
function SaveRework(){
    // let url = globalLink.replace('link', 'save_rework');
    // let login = globalLink.replace('link', 'login');

    $.ajax({
        url: 'save_rework', // live
        // url: 'save_rework_live', // for live on-going modification
        method: 'post',
        data: frmSaveRework.serialize(),
        dataType: 'json',
        beforeSend() {
            btnSaveRework.prop('disabled', true);
            btnSaveRework.html('Saving...');
            $(".input-error", frmSaveRework).text('');
            $(".form-control", frmSaveRework).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            // cnfrmLoading.close();
            $("input[name='runcard_no']", frmSaveRework).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSaveRework[0].reset();
                    mdlSaveRework.modal('hide');
                    $(".input-error", frmSaveRework).text('');
                    $(".form-control", frmSaveRework).removeClass('is-invalid');
                    dtReworks.draw();
                }
                else{
                    toastr.error('Saving Failed!');
                    if(data['error'] != null){
                        if(data['error']['runcard_no'] != null){
                            $("input[name='runcard_no']", frmSaveRework).addClass('is-invalid');
                            $("input[name='unit_no']", frmSaveRework).addClass('is-invalid');
                            $("input[name='rework_qty']", frmSaveRework).addClass('is-invalid');
                            $("input[name='result_qty_ok']", frmSaveRework).addClass('is-invalid');
                            $("input[name='result_qty_scrap']", frmSaveRework).addClass('is-invalid');
                            $("input[name='rework_code']", frmSaveRework).addClass('is-invalid');
                            $("input[name='rework_code2']", frmSaveRework).addClass('is-invalid');
                            $("input[name='rework_activity']", frmSaveRework).addClass('is-invalid');
                        }
                        else{
                            $("input[name='unit_no']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='rework_qty']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='result_qty_ok']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='result_qty_scrap']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='rework_code']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='rework_code2']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='rework_activity']", frmSaveRework).removeClass('is-invalid');
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
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            toastr.error('Saving Failed!');
        }
    });
}

function SaveReworkDefect(materialArray){ // for live on-going modification
    // console.log(materialArray)
    $.ajax({
        url: 'save_rework_live', 
        method: 'post',
        data: $.param({materialArray}) + '&' + frmSaveRework.serialize(),
        dataType: 'json',
        beforeSend() {
            btnSaveRework.prop('disabled', true);
            btnSaveRework.html('Saving...');
            $(".input-error", frmSaveRework).text('');
            $(".form-control", frmSaveRework).removeClass('is-invalid');
        },
        success(data){
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSaveRework[0].reset();
                    mdlSaveRework.modal('hide');
                    $(".input-error", frmSaveRework).text('');
                    $(".form-control", frmSaveRework).removeClass('is-invalid');
                    dtReworks.draw();
                }
                else{
                    toastr.error('Saving Failed!');
                    if(data['error'] != null){
                        if(data['error']['unit_no'] != null){
                            $("input[name='unit_no']", frmSaveRework).addClass('is-invalid');
                            $("input[name='rework_qty']", frmSaveRework).addClass('is-invalid');
                            $("input[name='result_qty_ok']", frmSaveRework).addClass('is-invalid');
                            $("input[name='result_qty_scrap']", frmSaveRework).addClass('is-invalid');
                            $("input[name='rework_code']", frmSaveRework).addClass('is-invalid');
                            $("input[name='rework_activity']", frmSaveRework).addClass('is-invalid');
                        }
                        else{
                            $("input[name='unit_no']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='rework_qty']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='result_qty_ok']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='result_qty_scrap']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='rework_code']", frmSaveRework).removeClass('is-invalid');
                            $("input[name='rework_activity']", frmSaveRework).removeClass('is-invalid');
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
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            toastr.error('Saving Failed!');
        }
    });
}


function SaveReworkVerification(employeeNo, token){
    // let url = globalLink.replace('link', 'save_rework');
    // let login = globalLink.replace('link', 'login');

    $.ajax({
        url: 'save_rework_verification',
        method: 'post',
        data: {
            _token: token,
            rework_id: arrSelectedRework,
            employee_no: employeeNo,
            verification_type: $('select[name="verification_type"]', $("#frmSaveReworkVerification")).val(),
            verification_result: $('select[name="verification_result"]', $("#frmSaveReworkVerification")).val(),
            verification_remarks: $("input[name='verification_remarks']", $("#frmSaveReworkVerification")).val(),
        },
        dataType: 'json',
        beforeSend() {
            $('.btnSaveRework').prop('disabled', true);
            $('.btnSaveRework').html('Saving...');
            $(".input-error", $('#frmSaveReworkVerification')).text('');
            $(".form-control", $('#frmSaveReworkVerification')).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            $('.btnSaveRework').prop('disabled', false);
            $('.btnSaveRework').html('Save');
            // cnfrmLoading.close();
            $("input[name='unit_no']", $('#frmSaveReworkVerification')).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    $('#frmSaveReworkVerification')[0].reset();
                    $('#mdlSaveReworkVerification').modal('hide');
                    arrSelectedRework = [];
                    dtReworks.draw();
                }
                else if(data['result'] == 2){
                    toastr.warning('Invalid User!');
                }
                else{
                    toastr.error('Saving Failed!');
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
                toastr.error('Session Expired!');
            }
        },
        error(xhr, data, status){
            // cnfrmLoading.close();
            $('.btnSaveRework').prop('disabled', false);
            $('.btnSaveRework').html('Save');
            toastr.error('Saving Failed!');
        }
    });
}

function GetReworkById(reworkId){
    // let url = globalLink.replace('link', 'get_rework_by_id');
    // let login = globalLink.replace('link', 'login');

    $.ajax({
        url: 'get_rework_by_id', // live
        // url: 'get_rework_by_id_live', // for live on-going modification
        method: 'get',
        data: {
            rework_id: reworkId
        },
        dataType: 'json',
        beforeSend() {
            btnSaveRework.prop('disabled', true);
            btnSaveRework.html('Saving...');
            $(".input-error", frmSaveRework).text('');
            $(".form-control", frmSaveRework).removeClass('is-invalid');
            // cnfrmLoading.open();
            frmSaveRework[0].reset();
            $('input[name="rework_id"]', frmSaveRework).val('');
        },
        success(data){
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            // cnfrmLoading.close();
            $("input[name='description']", frmSaveRework).focus();

            if(data['auth'] == 1){
                if(data['rework_info'] != null){
                    $("#mdlSaveRework").modal('show');
                    $('input[name="rework_id"]', frmSaveRework).val(data['rework_info']['id']);
                    $('input[name="runcard_no"]', frmSaveRework).val(data['rework_info']['runcard_no']);
                    $('input[name="unit_no"]', frmSaveRework).val(data['rework_info']['unit_no']);
                    $('select[name="mode_of_defect_id"]', frmSaveRework).val(data['rework_info']['mode_of_defect_id']).trigger('change');
                    // $('select[name="location_of_ng"]', frmSaveRework).val(data['rework_info']['location_of_ng']).trigger('change');
                    
                    $('input[name="location_of_ng"]', frmSaveRework).val(data['rework_info']['location_of_ng']);
                    $('input[name="ng_qty"]', frmSaveRework).val(data['rework_info']['ng_qty']);
                    
                    $('select[name="linestop"]', frmSaveRework).val(data['rework_info']['linestop']);
                    $('select[name="processstop"]', frmSaveRework).val(data['rework_info']['processstop']);

                    $('select[name="scrap"]', frmSaveRework).val(data['rework_info']['scrap']);
                    $('select[name="for_rework"]', frmSaveRework).val(data['rework_info']['for_rework']);
                    $('select[name="for_verification"]', frmSaveRework).val(data['rework_info']['for_verification']);

                    $('select[name="prod"]', frmSaveRework).val(data['rework_info']['prod']);
                    $('select[name="engg"]', frmSaveRework).val(data['rework_info']['engg']);
                    $('select[name="qc"]', frmSaveRework).val(data['rework_info']['qc']);

                    $('input[name="rework_qty"]', frmSaveRework).val(data['rework_info']['rework_qty']);
                    $('input[name="result_qty_ok"]', frmSaveRework).val(data['rework_info']['result_qty_ok']);
                    $('input[name="result_qty_scrap"]', frmSaveRework).val(data['rework_info']['result_qty_scrap']);
                    // $('input[name="rework_code"]', frmSaveRework).val(data['rework_info']['rework_code']);
                    $('select[name="rework_code"]', frmSaveRework).val(data['rework_info']['rework_code']).trigger('change');
                    $('select[name="terminal_gauge"]', frmSaveRework).val(data['rework_info']['terminal_gauge']);
                    $('select[name="dummy_lo"]', frmSaveRework).val(data['rework_info']['dummy_lo']);
                    $('select[name="dummy_mo"]', frmSaveRework).val(data['rework_info']['dummy_mo']);
                    $('select[name="operator"]', frmSaveRework).val(data['rework_info']['operator']).trigger('change');
                    $('input[name="date"]', frmSaveRework).val(data['rework_info']['date']);
                    // $('input[name="activity_after_rwrk"]', frmSaveRework).val(data['rework_info']['activity_after_rwrk']);
                    $('select[name="activity_after_rwrk"]', frmSaveRework).val(data['rework_info']['activity_after_rwrk']).trigger('change');
                    $('input[name="material_lot_num"]', frmSaveRework).val(data['rework_info']['material_lot_num']);
                    $('input[name="material_name"]', frmSaveRework).val(data['rework_info']['material_name']);

                    if(data['rework_info']['prod'] == 3 && data['rework_info']['engg'] == 3 && data['rework_info']['qc'] == 3){
                        $('.divForRework').show();
                    }else if(data['rework_info']['prod'] == 3 && data['rework_info']['engg'] == 3){
                        $('.divForRework').show();
                    }else if(data['rework_info']['prod'] == 3 && data['rework_info']['qc'] == 3){
                        $('.divForRework').show();
                    }else if(data['rework_info']['engg'] == 3 && data['rework_info']['qc'] == 3){
                        $('.divForRework').show();
                    }
                    else{
                        $('.divForRework').hide();   
                    }
                }
                else{
                    // $('.divForRework').hide(); 
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
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            toastr.error('An error occured!');
        }
    });
}

function GetReworkDefectById(reworkId){

    $.ajax({
        url: 'get_rework_by_id_live', // for live on-going modification
        method: 'get',
        data: {
            rework_id: reworkId
        },
        dataType: 'json',
        beforeSend() {
            btnSaveRework.prop('disabled', true);
            btnSaveRework.html('Saving...');
            $(".input-error", frmSaveRework).text('');
            $(".form-control", frmSaveRework).removeClass('is-invalid');
            // cnfrmLoading.open();
            frmSaveRework[0].reset();
            $('input[name="rework_id"]', frmSaveRework).val('');
        },
        success(data){
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            // cnfrmLoading.close();
            $("input[name='description']", frmSaveRework).focus();

            if(data['auth'] == 1){
                if(data['rework_info'] != null){
                    $("#mdlSaveRework").modal('show');
                    $('input[name="rework_id"]', frmSaveRework).val(data['rework_info']['id']);
                    // $('input[name="runcard_no"]', frmSaveRework).val(data['rework_info']['runcard_no']);
                    $('input[name="unit_no"]', frmSaveRework).val(data['rework_info']['unit_no']);
                    $('select[name="mode_of_defect_id"]', frmSaveRework).val(data['rework_info']['mode_of_defect_id']).trigger('change');
                    $('select[name="location_of_ng"]', frmSaveRework).val(data['rework_info']['location_of_ng']).trigger('change');
                    
                    $('input[name="ng_qty"]', frmSaveRework).val(data['rework_info']['ng_qty']);
                    
                    $('select[name="linestop"]', frmSaveRework).val(data['rework_info']['linestop']);
                    $('select[name="processstop"]', frmSaveRework).val(data['rework_info']['processstop']);

                    $('select[name="scrap"]', frmSaveRework).val(data['rework_info']['scrap']);
                    $('select[name="for_rework"]', frmSaveRework).val(data['rework_info']['for_rework']);
                    $('select[name="for_verification"]', frmSaveRework).val(data['rework_info']['for_verification']);

                    $('select[name="prod"]', frmSaveRework).val(data['rework_info']['prod']);
                    $('select[name="engg"]', frmSaveRework).val(data['rework_info']['engg']);
                    $('select[name="qc"]', frmSaveRework).val(data['rework_info']['qc']);

                    $('input[name="rework_qty"]', frmSaveRework).val(data['rework_info']['rework_qty']);
                    $('input[name="result_qty_ok"]', frmSaveRework).val(data['rework_info']['result_qty_ok']);
                    $('input[name="result_qty_scrap"]', frmSaveRework).val(data['rework_info']['result_qty_scrap']);
                    // $('input[name="rework_code"]', frmSaveRework).val(data['rework_info']['rework_code']);
                    $('select[name="rework_code"]', frmSaveRework).val(data['rework_info']['rework_code']).trigger('change');
                    $('select[name="rework_code2"]', frmSaveRework).val(data['rework_info']['rework_code2']).trigger('change');
                    $('select[name="terminal_gauge"]', frmSaveRework).val(data['rework_info']['terminal_gauge']);
                    $('select[name="dummy_lo"]', frmSaveRework).val(data['rework_info']['dummy_lo']);
                    $('select[name="dummy_mo"]', frmSaveRework).val(data['rework_info']['dummy_mo']);
                    $('select[name="operator"]', frmSaveRework).val(data['rework_info']['operator']).trigger('change');
                    $('select[name="operator2"]', frmSaveRework).val(data['rework_info']['operator2']).trigger('change');
                    $('input[name="date"]', frmSaveRework).val(data['rework_info']['date']);
                    $('select[name="activity_after_rwrk"]', frmSaveRework).val(data['rework_info']['activity_after_rwrk']).trigger('change');
                    $('input[name="material_lot_num"]', frmSaveRework).val(data['rework_info']['material_lot_num']);
                    $('input[name="material_name"]', frmSaveRework).val(data['rework_info']['material_name']);

                    if(data['rework_info']['prod'] == 3 && data['rework_info']['engg'] == 3 && data['rework_info']['qc'] == 3){
                        $('.divForRework').show();
                    }else if(data['rework_info']['prod'] == 3 && data['rework_info']['engg'] == 3){
                        $('.divForRework').show();
                    }else if(data['rework_info']['prod'] == 3 && data['rework_info']['qc'] == 3){
                        $('.divForRework').show();
                    }else if(data['rework_info']['engg'] == 3 && data['rework_info']['qc'] == 3){
                        $('.divForRework').show();
                    }
                    else{
                        $('.divForRework').hide();   
                    }

                    if(data['rework_info']['defect_materials'].length > 0){
                        let reworkDefectMaterials = data['rework_info']['defect_materials'];
                        // console.table(prodRuncardStationMaterialDetails);
                        
                        i = reworkDefectMaterials.length; // i - variable at the top
                        for (let index = 0; index < reworkDefectMaterials.length; index++) {
                          let material_name = reworkDefectMaterials[index].material;
                          let lot_no = reworkDefectMaterials[index].lot_no;
                          let material_type = reworkDefectMaterials[index].type;
                          
                          materialArray.push([material_name, lot_no, material_type]); // materialArray - variable at the top
                          
                          var html = '';
                            html += '<tr>';
                              html += '<td>';
                                html += index+1;
                              html += '</td>';
                              html += '<td>';
                                html += lot_no;
                              html += '</td>';
                              html += '<td>';
                                html += material_name;
                              html += '</td>';
                              html += '<td>';
                                html += material_type;
                              html += '</td>';
                            html += '</tr>';
                            $('#tbl_materials_for_scanning tbody', $('#frmSaveRework')).append(html);
                          if(i > 0){
                            $('#removeLastMaterial').removeAttr('disabled');
                          }
                        }
                    }else{
                        console.log('removeLastMaterial disabled');
                        $('#removeLastMaterial').prop('disabled', true);//
                    }
                
                }
                else{
                    $('.divForRework').hide();   
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
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            toastr.error('An error occured!');
        }
    });
}

function ReworkAction(reworkId, action, status){
    let url = globalLink.replace('link', 'rework_action');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            _token: _token,
            rework_id: reworkId,
            action: action,
            status: status,
        },
        dataType: 'json',
        beforeSend() {
            cnfrmLoading.open();
        },
        success(data){
            cnfrmLoading.close();
            dtReworks.draw();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                }
                else{
                    toastr.error('Saving Failed!');
                }
            }
            else{ // if session expired
                cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
            cnfrmLoading.close();
            toastr.error('An error occured!');
        }
    });
}