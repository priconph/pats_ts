@extends('layouts.super_user_layout')

@section('title', 'OQC Visual Inspection')

@section('content_page')
 <style type="text/css">
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
    textarea{
      resize: none;
    }
    /*#mdl_edit_material_details>div{*/
      /*width: 2000px!important;*/
      /*min-width: 1400px!important;*/
    /*}*/

    .modal-xl-custom{
      width: 95%!important;
      min-width: 90%!important;
    }
  </style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>OQC Visual Inspection</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">OQC Visual Inspection</li>
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
              <h3 class="card-title">OQC Visual Inspection</h3>
            </div>

            <!-- Start Page Content -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-2">
                      <label>Search PO Number</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn_search_POno" title="Click to Scan PO Code"><i class="fa fa-qrcode"></i></button>
                        </div>
                        <input type="text" class="form-control" id="id_po_no" readonly="">
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <label>Device Name</label>
                        <input type="text" class="form-control" id="id_device_name" name="" readonly="">
                    </div>
                    <div class="col-sm-1">
                      <label>PO Qty</label>
                        <input type="text" class="form-control" id="id_po_qty" readonly="">
                    </div>
                  </div>
                  <br>
              </div>
              <!-- !-- End Page Content -->
          </div>
          <!-- /.card -->

           <div class="card card-primary">

              <div class="card-header">
                <h3 class="card-title">OQC Visual Inspection Result (Responsible: OQC Inspector)</h3>
              </div>

                <div class="card-body">
                  <div class="table-responsive dt-responsive">
                      <table id="tbl_oqcvir" class="table table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <th>Status</th>
                              <th>Lot App Sub</th>
                              <th>Lot # / Batch #</th>
                              <th>Lot Qty</th>
                              <th>Inspected By</th>
                              <th style="background-color:#ffc107">Packing Code</th>
                            </tr>
                          </thead>
                      </table> 
                  </div>
                </div>
              </div> 



        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!---------------------------MODALS------------------------------>
  <div class="modal fade" id="modalScan_PO" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
          <input type="text" id="txt_search_po_number" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>


<div class="modal fade" id="modalOQCVIR">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Add OQC Visual Inspection Result</h4>
      </div>

      <div class="modal-body">

        <form id="formAddOQCVIR" method="post"> <!--FORM ADD OQC VIR-->
          @csrf

        <div class="row">
          <div class="col-sm-4 p-2">
            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Current PO Number</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_currentPONo" name="name_po_no" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Lot/Batch No</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_lotbatch_no" name="name_lotbatch_no" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Series Name</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_seriesName" name="name_series_name" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Total Lot Quantity</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_totalLotQty" name="name_totallot_qty" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OQC Sample Size</span>
                  </div>
                  <input type="number" min="0" class="form-control form-control-sm" id="id_oqc_sample_size" name="name_oqc_sample_size">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OK Quantity</span>
                  </div>
                  <input type="number" min="0" class="form-control form-control-sm" id="id_ok_qty" name="name_ok_qty">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">NG Quantity</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_ng_qty" name="name_ng_qty" readonly>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-4 p-2">
            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Use of Terminal / Template</span>
                  </div>
                  <select class="form-control form-control-sm" id="id_terminal" name="name_terminal">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>YES</option>
                    <option value='2'>NO</option>
                    <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
             <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">YD Label Requirement</span>
                  </div>
                  <select class="form-control form-control-sm" id="id_yd_label" name="name_yd_label">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>With</option>
                    <option value='2'>Without</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">CSH Coating</span>
                  </div>
                  <select class="form-control form-control-sm" id="id_csh_coating" name="name_csh_coating">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>YES</option>
                    <option value='2'>NO</option>
                    <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Accessory Requirement</span>
                  </div>
                  <select class="form-control form-control-sm" id="id_accessory_requirement" name="name_accessory_requirement">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>YES</option>
                    <option value='2'>NO</option>
                    <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

             <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">C.O.C Requirement</span>
                  </div>
                  <select class="form-control form-control-sm" id="id_coc_requirement" name="name_coc_requirement">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>YES</option>
                    <option value='2'>NO</option>
                    <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OQC Inspector Name</span>
                  </div>

                    <input type="hidden" id="id_oqc_inspector_id" name="name_oqc_inspector_id" readonly>

                   <input type="text" class="form-control form-control-sm" id="id_oqc_inspector_name" name="name_oqc_inspector_name" readonly>

                    <div class="input-group-append">
                      <button type="button" class="btn btn-primary btn_search_inspector_id" title="Click to Scan ID"><i class="fa fa-qrcode"></i></button>
                    </div>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Result</span>
                  </div>
                  <select class="form-control form-control-sm" id="id_result" name="name_result">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>NO DEFECT FOUND</option>
                    <option value='2'>WITH DEFECT FOUND</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-4 p-2">
          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Inspection Start Date/Time</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_start_datetime" name="name_start_datetime" readonly>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Inspection End Date/Time</span>
                  </div>
                  <input type="datetime-local" class="form-control form-control-sm" id="id_end_datetime" name="name_end_datetime">
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                  </div>
                  <textarea  class="form-control form-control-sm" id="id_remarks" name="name_remarks" rows='3'></textarea>
                </div>
            </div>
          </div>

        </div>

        </div>

        <hr>

        <div class="row">
          <div class="col-md-12">
             <div class="table-responsive dt-responsive">
                <table id="tbl_oqcvir_history" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 85%;">
                          <thead>
                            <tr>
                              <th>Inspection Start Date/Time</th>
                              <th>Inspection End Date/Time</th>
                              <th>Inspected By</th>
                              <th>Result</th>
                              <th>Total Lot Qty</th>
                              <th>OQC Sample Size</th>
                              <th>OK Qty / NG Qty</th>
                              <th>Remarks</th>
                            </tr>
                          </thead>
                  </table> 
             </div>
          </div>
        </div>

      </div>

      <div class="modal-footer">
          <button type="button" id="id_btn_close2" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="button" id="id_btn_add_oqcvir" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
      </div>
    </div>
  </div>
</div>

        <!-- Modal -->
  <div class="modal fade" id="mdl_employee_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <br><br>

              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner" name="employee_number_scanner" class="hidden_scanner_input">
          </div>
  
        </div>
      </div>
    </div>
    <!-- /.Modal -->

  </form><!--FORM ADD OQC VIR-->

<div class="modal fade" id="modalStartInspection" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Start Visual Inspection</h4>
      </div>

      <form id="formStartVisualInspection" method="post">
        @csrf

      <div class="modal-body">

        <div class="row">
          <div class="col">
            <h5><strong>Are you sure you want to start the Inspection?</strong></h5>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="input-group input-group-sm mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100" id="basic-addon1">Current PO Number</span>
              </div>
              <input type="text" class="form-control form-control-sm" id="id_start_po" name="name_start_po" readonly>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="input-group input-group-sm mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100" id="basic-addon1">Lot/Batch No</span>
              </div>
              <input type="text" class="form-control form-control-sm" id="id_start_lotbatch" name="name_start_lotbatch" readonly>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="input-group input-group-sm mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100" id="basic-addon1">Series Name</span>
              </div>
              <input type="text" class="form-control form-control-sm" id="id_start_series" name="name_start_series" readonly>
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="id_btn_close" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-xs"></i> NO</button>
          <button type="button" id="id_start_inspection" class="btn btn-success"><i class="fa fa-check fa-xs"></i> YES</button>
      </div>

    </div>
  </div>
</div>

        <!-- Modal -->
  <div class="modal fade" id="mdl_employee_number_scanner_start" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <br><br>

              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner_start" name="employee_number_scanner_start" class="hidden_scanner_input" autocomplete="off">
          </div>
  
        </div>
      </div>
    </div>
    <!-- /.Modal -->


  <div class="modal fade" id="modalScan_Inspector" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
            Please scan your ID.
            <br>
            <br>
            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="id_search_OQCInsName" class="hidden_scanner_input">
          </div>
        </div>
      </div>
  </div>


  </form>

@endsection

@section('js_content')
<script type="text/javascript">

  let dt_oqcvir;
  let dt_oqcvir_history;

  $(document).ready(function () {
    bsCustomFileInput.init();

    dt_oqcvir = $('#tbl_oqcvir').DataTable({
          "processing"    : false,
          "serverSide"  : true,
          "ajax"        : 
          {
            url: "load_oqcvir_table",
              data: function (param){
                param.po_no = $("#txt_search_po_number").val();
                }
          },

          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status" },
            { "data" : "submission" },
            { "data" : "lot_batch_no" },
            { "data" : "output_qty" },
            { "data" : "inspected_by" },
            { "data" : "packing_code" },
          ],

      });

    dt_oqcvir_history = $('#tbl_oqcvir_history').DataTable();

  });

   //SEARCH PO
    $(document).on('click','.btn_search_POno',function(e){
      $('#txt_search_po_number').val('');
      $('#modalScan_PO').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_PO").data('bs.modal') || {})._isShown ){
        $('#txt_search_po_number').focus();

        if( e.keyCode == 13 && $('#txt_search_po_number').val() !='' && ($('#txt_search_po_number').val().length >= 4) ){
            $('#modalScan_PO').modal('hide');
          }
        }
    }); 

    $(document).on('keyup','#txt_search_po_number',function(e){
      $('.btn_search_pack_code').attr('disabled','disabled');

        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');

          dt_oqcvir.draw();  

          var data = {
          'po'      : $('#txt_search_po_number').val()
          }

          data = $.param(data);
        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_po_details",
          success : function(data){

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );

          },error : function(data){

          }

            }); 
        }
    });

    $(document).on('click','.btn_open_checkpoints', function(e){

      let batch = $(this).attr('lot-batch-no');

      //alert(batch);
      //ViewOQCApplicationTS(batch);
      ViewOQCInspectionTS(batch);
    });

    $(document).on('click','#id_btn_add_oqcvir',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formAddOQCVIR').modal('show');
    });

     $(document).on('keypress',function(e)
       {
        if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown )
        {
          $('#txt_employee_number_scanner').focus();

          if( e.keyCode == 13 && $('#txt_employee_number_scanner').val() !='' && ($('#txt_employee_number_scanner').val().length >= 4) )
            {
              $('#mdl_employee_number_scanner').modal('hide');

               var formid = $("#mdl_employee_number_scanner").attr('data-formid');

                if ( ( formid ).indexOf('#') > -1)
                {
                  $( formid ).submit();
                }

            }
          }
      });

    $('#formAddOQCVIR').on('submit', function(e){

      e.preventDefault();

      SubmitOQCInspection();
    });

    $(document).on('click','.btn_start_inspection', function(e){

      let batch = $(this).attr('lot-batch-no');

      //alert(batch);

      ViewStartInspection(batch);

    });

    $(document).on('click','#id_start_inspection',function(e){
        $('#txt_employee_number_scanner_start').val('');
        $('#mdl_employee_number_scanner_start').attr('data-formid','#formStartVisualInspection').modal('show');
    });

     $(document).on('keypress',function(e)
       {
        if( ($("#mdl_employee_number_scanner_start").data('bs.modal') || {})._isShown )
        {
          $('#txt_employee_number_scanner_start').focus();

          if( e.keyCode == 13 && $('#txt_employee_number_scanner_start').val() !='' && ($('#txt_employee_number_scanner_start').val().length >= 4) )
            {
              $('#mdl_employee_number_scanner_start').modal('hide');

               var formid = $("#mdl_employee_number_scanner_start").attr('data-formid');

                if ( ( formid ).indexOf('#') > -1)
                {
                  $( formid ).submit();
                }

            }
          }
      });

    $('#formStartVisualInspection').on('submit', function(e){

      e.preventDefault();

      SubmitStartInspection();
    });



</script>
@endsection