@php $layout = 'layouts.super_user_layout'; @endphp
@auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 3){
      $layout = 'layouts.user_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

  @section('title', 'Daily Defect Report')

  @section('content_page')
  <style type="text/css">
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Daily Defect Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Daily Defect Report</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Filter DDR</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <form method="get" id="frmGenerateDDR">
                    <div class="row">
                        <div class="col-sm-3">
                          <label>Date</label>
                            <input type="date" class="form-control" id="dateSearchDate" name="date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-sm-3">
                          <label>PO #</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-primary btnSearchPONo" title="Scan PO No."><i class="fa fa-qrcode"></i></button>
                            </div>
                            <input type="text" class="form-control" id="txtSearchPONo" readonly="" name="po_no">
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <label>Device</label>
                            <input type="text" class="form-control" id="txtSearchDevice" readonly="" name="device">
                        </div>

                        <div class="col-sm-3">
                          <label>PO Quantity</label>
                            <input type="text" class="form-control" id="txtSearchPOQty" readonly="" name="po_qty">
                        </div>

                        <div class="col-sm-2">
                          <br>
                          <label>Employee No</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-primary btnScanEmpNo" action="1" title="Scan Employee No."><i class="fa fa-qrcode"></i></button>
                            </div>
                            <input type="hidden" class="form-control" id="txtScannedEmpNoUserId" readonly="" name="user_id">
                            <input type="text" class="form-control" id="txtScannedEmpNoEmpId" readonly="" name="employee_id">
                          </div>
                        </div>

                        <div class="col-sm-3">
                          <br>
                          <label>Operator Name</label>
                            <input type="text" class="form-control" id="txtScannedEmpNoOperatorName" readonly="" name="operator_name">
                        </div>
                        <div class="col-sm-2">
                          <br>
                          <label>Shift</label>
                            <select class="form-control" id="selSearchShift" name="shift">
                              <option value="1" selected="true">Shift A</option>
                              <option value="2">Shift B</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                          <br>
                          <label>Station</label>
                            <select class="form-control select2bs4 select2 selSubStation" id="selSearchStation" name="station">
                              <option value="" selected="true">Select Station</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                          <br>
                          <label>Input</label>
                            <input type="text" class="form-control" id="txtSearchInput" readonly="" name="qty_input">
                        </div>
                        <div class="col-sm-1">
                          <br>
                          <button type="submit" class="btn btn-primary" id="btnGenerateDDR" style="margin-top: 31px;">Generate</button>
                        </div>

                    </div>
                  </form>
              </div>
                <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Daily Defect Report</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-2">
                      <label>Total NG Qty</label>
                        <input type="input" class="form-control" id="txtTotalNGQty" name="total_ng_qty" readonly="true">
                    </div>

                    <div class="col-sm-2">
                      <label>Output</label>
                        <input type="input" class="form-control" id="txtOutputQty" name="qty_output" readonly="true">
                    </div>

                    <!-- <div class="col-sm-3">
                      <label>Checked By (Production)</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-primary btnScanEmpNo" action="2" title="Scan Employee No."><i class="fa fa-qrcode"></i></button>
                        </div>
                        <input type="hidden" class="form-control" id="txtScannedEmpNoCheckedById" readonly="" name="checked_by">
                        <input type="text" class="form-control" id="txtScannedEmpNoCheckedByEmpNoName" readonly="" name="employee_id">
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <label>Verified By (Production)</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-primary btnScanEmpNo" action="3" title="Scan Employee No."><i class="fa fa-qrcode"></i></button>
                        </div>
                        <input type="hidden" class="form-control" id="txtScannedEmpNoVerifiedById" readonly="" name="verified_by">
                        <input type="text" class="form-control" id="txtScannedEmpNoVerifiedByEmpNoName" readonly="" name="employee_id">
                      </div>
                    </div> -->

                    <!-- <div class="col-sm-1">
                      <button type="button" class="btn btn-primary" id="btnSaveDDR" style="margin-top: 31px;">Save</button>
                    </div> -->

                  </div>

                  <div class="table responsive">
                    <table id="tblDDR" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th rowspan="2">Mode of Defects</th>
                          <th rowspan="2">Quantity</th>
                          <th colspan="2" class="text-center">Type of NG</th>
                        </tr>
                        <tr>
                          <th>Mat'l. NG</th>
                          <th>Prod'n. NG</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
              </div>
              <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="mdlScanPoNo" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan the PO number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txtScanPoNo" class="hidden_scanner_input">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="mdlScanEmpNo" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <input type="text" id="txtScanEmpNoAction" class="hidden_scanner_input">
          <div class="text-center text-secondary">
          Please scan Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txtScanEmpNo" class="hidden_scanner_input">
        </div>
      </div>
    </div>
  </div>

  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableDDRs;
    let arrSelectedDDRs = [];

    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $(document).on('click','#tblDDR tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      GetCboSubStationByStat($(".selSubStation"), 1);

      dataTableDDRs = $("#tblDDR").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_ddrs",
            data: function (param){
                param.po_no = $("input[name='po_no']", $("#frmGenerateDDR")).val();
                param.user_id = $("input[name='user_id']", $("#frmGenerateDDR")).val();
                param.date = $("input[name='date']", $("#frmGenerateDDR")).val();
                param.shift = $("select[name='shift']", $("#frmGenerateDDR")).val();
                param.station = $("select[name='station']", $("#frmGenerateDDR")).val();
            }
          },
          
          "columns":[
            { "data" : "mod_details.name" },
            { "data" : "mod_qty" },
            { "data" : "mat_ng_qty" },
            { "data" : "prod_ng_qty" },
          ],

          paging: false,
          info: false,
          searching: false,
          pageLength: -1,
          "initComplete": function(settings, json) {
                // $(".chkOPHR").each(function(index){
                //     if(arrSelectedOPHRs.includes($(this).attr('ophr-id'))){
                //         $(this).attr('checked', 'checked');
                //     }
                // });
          },
          "drawCallback": function( settings ) {
                // $(".chkOPHR").each(function(index){
                //     if(arrSelectedOPHRs.includes($(this).attr('ophr-id'))){
                //         $(this).attr('checked', 'checked');
                //     }
                // });
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last = null;

                let totalInput = 0;
                let totalNGQty = 0;
                let totalOutput = 0;

                api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                    let data = api.row(i).data();

                    if(data['production_runcard_station_details'] != null){
                      totalInput += parseInt(data['production_runcard_station_details']['qty_input']);
                      totalNGQty += parseInt(data['mod_qty']);
                    }
                });

                totalOutput = totalInput - totalNGQty;

                // console.log(totalInput + ' - ' + totalNGQty + ' = ' + totalOutput);

                $("input[name='qty_input']", $("#frmGenerateDDR")).val(totalInput)

                $("#txtTotalNGQty").val(totalNGQty);
                $("#txtOutputQty").val(totalOutput);
            }
        });//end of dataTableDDRs


        $("#frmGenerateDDR").submit(function(e){
          e.preventDefault();
          dataTableDDRs.draw();
        });

        $(document).on('click','.btnSearchPONo',function(e){
          $('#txtScanPoNo').val('');
          $('#mdlScanPoNo').attr('data-formid', '').modal('show');
        });

        $(document).on('click','.btnScanEmpNo',function(e){
          $("#mdlScanEmpNo").modal('show');
          let action = $(this).attr('action');
          $('#txtScanEmpNo').val('');
          $('#txtScanEmpNoAction').val(action);
        });

        $(document).on('keypress',function(e){
          if( ($("#mdlScanPoNo").data('bs.modal') || {})._isShown ){
            $('#txtScanPoNo').focus();

            if( e.keyCode == 13 && $('#txtScanPoNo').val() !='' && ($('#txtScanPoNo').val().length >= 4) ){
              $('#mdlScanPoNo').modal('hide');
              $("#txtSearchPONo").val($('#txtScanPoNo').val());
              GetMaterialKitting($('#txtScanPoNo').val());
            }
          }

          else if(($("#mdlScanEmpNo").data('bs.modal') || {})._isShown ){
            $('#txtScanEmpNo').focus();

            if( e.keyCode == 13 && $('#txtScanEmpNo').val() !='' && ($('#txtScanEmpNo').val().length >= 4) ){
              $('#mdlScanEmpNo').modal('hide');

              GetDDRUserByEmpNo($('#txtScanEmpNo').val(), $('#txtScanEmpNoAction').val());

              // switch($('#txtScanEmpNoAction').val()){
              //   case '1': 
              //     // alert('Employee No');
              //     // GetDDRUserByEmpNo($('#txtScanEmpNo').val(), $('#txtScanEmpNoAction').val())
              //   break;

              //   case '2': 
              //     // alert('Checked By');
              //   break;

              //   case '3': 
              //     // alert('Verified By');
              //   break;

              //   default: {
              //     alert('No Action Selected.');
              //   }
              //   break;
              // }
              // alert($('#txtScanEmpNoAction').val());
              // $("#txtSearchPONo").val($('#txtScanEmpNo').val());
              // GetDDRUserByEmpNo($('#txtScanEmpNo').val());
            }
          }
        });

      });

    function GetMaterialKitting(po_no){
      $.ajax({
        url: "get_wbs_material_kitting",
        method: 'get',
        dataType: 'json',
        data: {
          po_number: po_no
        },
        beforeSend: function(){
          $("#txtSearchDevice").val("");
          $("#txtSearchPOQty").val("");
        },
        success: function(data){
          if(data['material_kitting'] != null){
            // $("#txt_po_number_lbl").val(data['material_kitting']['po_no']);
            $("#txtSearchDevice").val(data['material_kitting']['device_name']);
            $("#txtSearchPOQty").val(data['material_kitting']['po_qty']);
          }
          else{
            // $("#txt_po_number_lbl").val("");
            $("#txtSearchDevice").val("");
            $("#txtSearchPOQty").val("");
          }
        }
      });
    }

    function GetDDRUserByEmpNo(employee_id, action){
      $.ajax({
        url: "get_user_by_emp_id",
        method: 'get',
        dataType: 'json',
        data: {
          employee_id: employee_id
        },
        beforeSend: function(){
          if(action == 1){
            $("#txtScannedEmpNoUserId").val('');
            $("#txtScannedEmpNoEmpId").val('');
            $("#txtScannedEmpNoOperatorName").val('');
          }
        },
        success: function(data){
          if(action == 1){
            if(data['user'] != null){
              $("#txtScannedEmpNoUserId").val(data['user'].id);
              $("#txtScannedEmpNoEmpId").val(data['user'].employee_id);
              $("#txtScannedEmpNoOperatorName").val(data['user'].name);
            }
            else{
              $("#txtScannedEmpNoUserId").val('');
              $("#txtScannedEmpNoEmpId").val('');
              $("#txtScannedEmpNoOperatorName").val('');
            }
          }
          else if(action == 2){
            if(data['user'] != null){
              $("#txtScannedEmpNoCheckedById").val(data['user'].id);
              $("#txtScannedEmpNoCheckedByEmpNoName").val(data['user'].name + ' (' + data['user'].employee_id + ')');
            }
            else{
              $("#txtScannedEmpNoCheckedById").val('');
              $("#txtScannedEmpNoCheckedByEmpNoName").val('');
            }
          }
          else if(action == 3){
            if(data['user'] != null){
              $("#txtScannedEmpNoVerifiedById").val(data['user'].id);
              $("#txtScannedEmpNoVerifiedByEmpNoName").val(data['user'].name + ' (' + data['user'].employee_id + ')');
            }
            else{
              $("#txtScannedEmpNoVerifiedById").val('');
              $("#txtScannedEmpNoVerifiedByEmpNoName").val('');
            }
          }
        }
      });
    }
  </script>
  @endsection
@endauth