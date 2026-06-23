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

  @section('title', 'QC Packing Inspection')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
 <style type="text/css">
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
  </style>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>QC Packing Inspection</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">QC Packing Inspection</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Search P.O.</h3>
              </div>
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
                          <input type="text" class="form-control" id="id_device_name" readonly="">
                      </div>
                      <div class="col-sm-2">
                        <label>P.O. Quantity</label>
                          <input type="text" class="form-control" id="id_po_qty" readonly="">
                      </div>

                  </div>
                  <br>
              </div>
              </div>
            </div>
          </div>
        </div>

     
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Current Packing Code List</h3> 

                <div class="float-sm-right">
                  <button type="button" class="btn btn-sm btn-success" id="btn_add_packing_confirmation" disabled><i class="fa fa-plus"></i> Add Packing Confirmation</button>
                </div>

              </div>

              <!-- Start Page Content -->
              <div class="card-body">
             
                <div class="table-responsive dt-responsive">
                  <table id="tbl_packing_inspection" class="table table-bordered table-hover" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Device Code</th>
                        <th>Total Lot Quantity</th>
                        <th>OQC Lot Applications</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>

                      </tbody>
                    </table>
                  </div>

                </div>
                <!-- !-- End Page Content -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.row -->
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

  <div class="modal fade" id="modalPackingConfirmation">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
           <h4 class="modal-title"><i class="fa fa-plus"></i> Add Packing Confirmation</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

        <form id="formPackingConfirmation" method="post">
        @csrf

        <div class="row">
          <div class="col-sm-4">
            <label>Current PO Number</label>
            <input type=text class="form-control" id="add_packing_confirmation_po_num" name="add_packing_confirmation_po_num" readonly>
          </div>

          <div class="col-sm-4">
            <label>Device Name</label>
            <input type=text class="form-control" id="add_packing_confirmation_device_name" name="add_packing_confirmation_device_name" readonly>
          </div>

          <div class="col-sm-4">
            <label>Total Packing Quantity</label>
            <input type=text class="form-control" id="add_packing_confirmation_pack_qty" name="add_packing_confirmation_pack_qty" readonly>
          </div>
        </div>

        <br>

        <div class="row">
          <div class="col-sm-12">

            <div class="card">
              <div class="card-header">

                <h5 class="card-title">Lots for Packing</h5>

                <div class="float-sm-right">
                  <button type="button" class="btn btn-sm btn-success" id="btn_add_packing_confirmation_lot"><i class="fa fa-qrcode"></i> Add Lot</button>
                </div>
              </div>

              <div class="card-body">
                 <!--TABLE OF SELECTED / SCANNED PO NUMBERS-->
                <div class="row">
                  <div class="col-sm-12">
                    <div class="table-responsive dt-responsive">
                      <table id="tbl_packing_confirmation_lots" class="table table-striped table-bordered table-xs nowrap" style="width: 100%; font-size: 80%;">
                        <thead>
                          <tr>
                            <th>Action</th>
                            <th>OQC Lot Application</th>
                            <th>Lot Quantity</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>            
                </div>
              </div>              
            </div>

          </div>
        </div>

        <br>

           <!--SELECT ONE-->
            <div class="row">
              <div class="col-sm-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100">Silica Gel / Anti-Rust</span>
                </div>
               
                <select class="form-control" id="anti_rust_inclusion" name="anti_rust_inclusion">
                  <option selected disabled>-- Select One --</option>
                  <option value="1">WITH</option>
                  <option value="2">WITHOUT</option>
                </select>

              </div>
              </div>
            </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
          <button type="button" class="btn btn-success" id="btn_confirm_packing_confirmation"> Confirm</button>
        </div>

      </div>
    </div>
  </div>

     <!--CONFIRMATION PACKING OPERATOR-->

  <div class="modal fade" id="modalScan_ConfirmationOperator" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan the Packing Operator ID.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_packing_operator" name="txt_search_packing_operator" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  </form> <!--END OF FORM PACKING OPERATOR-->


    <!--CONFIRMATION SCAN LOT NUMBER--->

  <div class="modal fade" id="modalScan_LotNumber" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan the Lot Number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_lot_number" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>


<div class="modal fade" id="modalPreliminaryInspection">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
         <h4 class="modal-title"><i class="fa fa-plus"></i> Add Packing Confirmation</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>

      <div class="modal-body">

        <form id="formPrelimInspection" method="post">
        @csrf


         <input type="hidden" class="form-control" id="packing_code_id" name="packing_code_id" readonly>
          <!--SELECT ONE-->
        <div class="row">

          <div class="col-sm-12">
            <div class="input-group mb-3">
            <div class="input-group-prepend w-50">
              <span class="input-group-text w-100">Packing Code</span>
            </div>
             
             <input type="text" class="form-control" id="packing_code" name="packing_code" readonly>

          </div>
          </div>

          <div class="col-sm-12">
            <div class="input-group mb-3">
            <div class="input-group-prepend w-50">
              <span class="input-group-text w-100">Packing Type</span>
            </div>
           
            <select class="form-control" id="packing_type" name="packing_type">
              <option selected disabled>-- Select One --</option>
              <option value="1">BOX</option>
              <option value="2">TRAY</option>
              <option value="3">CYLINDER</option>
              <option value="4">PALLET CASE</option>
            </select>

          </div>
          </div>

          <div class="col-sm-12">
            <div class="input-group mb-3">
            <div class="input-group-prepend w-50">
              <span class="input-group-text w-100">Unit Condition</span>
            </div>
           
            <select class="form-control" id="unit_condition" name="unit_condition">
              <option selected disabled>-- Select One --</option>
              <option value="1">TERMINAL MOUNTED ON ESAFOAM</option>
              <option value="2">TERMINAL DOWN</option>
              <option value="3">TERMINAL UP</option>
            </select>

          </div>
          </div>

          <div class="col-sm-12">
            <div class="input-group mb-3">
            <div class="input-group-prepend w-50">
              <span class="input-group-text w-100">Inspection Date/Time</span>
            </div>
             
             <input type="datetime-local" class="form-control" id="packing_inspection_datetime" name="packing_inspection_datetime" >

          </div>
          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
        <button type="button" class="btn btn-success" id="btn_confirm_prelim_inspection"> Confirm</button>
      </div>

    </div>
  </div>
</div>

     <!--CONFIRMATION PACKING OPERATOR-->

  <div class="modal fade" id="modalScan_PrelimInspector" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan the Packing Operator ID.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_prelim_inspector" name="txt_search_prelim_inspector" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  </form> <!--FORM PRELIM INSPECTION--->

  <div class="modal fade" id="modalFinalInspection">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
           <h4 class="modal-title"><i class="fa fa-plus"></i> Final Packing Inspection</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form id="formFinalInspection" method="post">
        @csrf

        <div class="modal-body">

           <input type="hidden" class="form-control" id="final_packing_code_id" name="final_packing_code_id" readonly>

           <div class="row">
              <div class="col-sm-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100">Packing Code</span>
                </div>
                 
                 <input type="text" class="form-control" id="final_packing_code" name="final_packing_code" readonly>

              </div>
              </div>

              <div class="col-sm-12">
              <div class="input-group mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100">COC Attachment</span>
              </div>
             
              <select class="form-control" id="coc_attachment" name="coc_attachment">
                <option selected disabled>-- Select One --</option>
                <option value="1">YES</option>
                <option value="2">NO</option>
                <option value="3">N/A</option>
              </select>

            </div>
            </div>

            <div class="col-sm-12">
              <div class="input-group mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100">Result</span>
              </div>
             
              <select class="form-control" id="final_result" name="final_result">
                <option selected disabled>-- Select One --</option>
                <option value="1">NO DEFECT FOUND</option>
                <option value="2">WITH DEFECT FOUND</option>
              </select>

            </div>
            </div>

            <div class="col-sm-12">
              <div class="input-group mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100">Remarks</span>
              </div>
             
              <textarea class="form-control" id="final_remarks" name="final_remarks" rows="3" style="resize: none;"></textarea>

            </div>
            </div>

             <input type="hidden" class="form-control" id="packop_conformance_id" name="packop_conformance_id" readonly>

             <div class="col-sm-12">
              <div class="input-group mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100">Packing Operator Conformance</span>
              </div>
             
              <input type="text" class="form-control" id="packop_conformance_name" name="packop_conformance_name" readonly>

               <div class="input-group-append">
                <button type="button" title="Scan Packing Operator ID" class="btn btn-sm btn-primary" id="btn_add_packop_conformance"><i class="fa fa-qrcode"></i></button>
              </div>


            </div>
            </div>

             <div class="col-sm-12">
            <div class="input-group mb-3">
            <div class="input-group-prepend w-50">
              <span class="input-group-text w-100">Packing Inspection Date/Time</span>
            </div>
             
             <input type="datetime-local" class="form-control" id="final_inspection_datetime" name="final_inspection_datetime" >

          </div>
          </div>


          </div>

        </div>

        <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
           <button type="button" class="btn btn-success" id="btn_confirm_final_inspection"> Confirm</button>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="modalScan_FinalInspection" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan the OQC Inspector ID.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_final_inspection" name="txt_search_final_inspection" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  </form>

    <div class="modal fade" id="modalScan_PackopConformance" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan the Packing Operator ID.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_packop_conformance" name="txt_search_packop_conformance" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>


  @endsection

  @section('js_content')

  <script type="text/javascript">

  let arrayPackingConfirmationLots = [];
  let arrayPackingOperators = [];
  let arrayPackingInspectors = [];


  let dt_packing_inspection;
  let dt_packing_confirmation_lots;

   $(document).ready(function () {
      bsCustomFileInput.init();

      dt_packing_inspection = $('#tbl_packing_inspection').DataTable({

        "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_new_packinginspection_table",
          data: function(param)
          {
            param.po_num = $('#id_po_no').val();
          }
        },

        "columns":[
            { "data" : "action", orderable:false, searchable:false  },
            { "data" : "device_code" },
            { "data" : "total_lot_qty" },
            { "data" : "lotapps"},
            { "data" : "status"}        
        ],

      });


      dt_packing_confirmation_lots = $('#tbl_packing_confirmation_lots').DataTable({

        "ordering": false,
        "pageLength" : 5,
        "info":     false,
        "searching": false,
        "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_new_packing_confirmation_lots",
          data: function(param)
          {
            param.array_lots = arrayPackingConfirmationLots;
          }
        },

        "columns":[
            { "data" : "action", orderable:false, searchable:false  },
            { "data" : "lotapp_id" },
            { "data" : "lot_qty" },
            
        ],


      });

  });

      //SEARCH PO
    $(document).on('click','.btn_search_POno',function(e){
      $('#btn_add_packing_confirmation').attr('disabled','disabled');
      $('#id_po_no').val('');
      $('#id_device_name').val('');
      $('#id_po_qty').val('');
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


        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');


          var data = {
          'po'      : $('#txt_search_po_number').val()
          }

          data = $.param(data);
        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_po_details",
           beforeSend: function(){

            $('#id_po_no').val('-- Data Loading --');
            $('#id_device_name').val('-- Data Loading --');
            $('#id_po_qty').val('-- Data Loading --');

          },
          success : function(data){

            $('#btn_add_packing_confirmation').removeAttr('disabled');

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );

            dt_packing_inspection.draw();

          },error : function(data){

            $('#id_po_no').val('-- Data Error, Please Refresh --');
            $('#id_device_name').val('-- Data Error, Please Refresh --');
            $('#id_po_qty').val('-- Data Error, Please Refresh --');

          }

            }); 
        }
    });

    //lets go functions!
    //button functions
   $('#btn_add_packing_confirmation').click(function(e){

    let po_num = $('#id_po_no').val();
    let device_name = $('#id_device_name').val();

    $('#add_packing_confirmation_po_num').val(po_num);
    $('#add_packing_confirmation_device_name').val(device_name);

    $('#modalPackingConfirmation').modal('show');

    LoadPackingQuantity(device_name);

   });

   $('#btn_add_packing_confirmation_lot').click(function(e){
    $('#txt_search_lot_number').val('');
    $('#modalScan_LotNumber').attr('data-formid','').modal('show');
   });

   $(document).on('keypress',function(e){
      if( ($("#modalScan_LotNumber").data('bs.modal') || {})._isShown ){
        $('#txt_search_lot_number').focus();

        if( e.keyCode == 13 && $('#txt_search_lot_number').val() !='' && ($('#txt_search_lot_number').val().length >= 4) ){
            $('#modalScan_LotNumber').modal('hide');

            let po_num = $('#add_packing_confirmation_po_num').val();
            let lot_app = $('#txt_search_lot_number').val();
            let pack_qty = $('#add_packing_confirmation_pack_qty').val();

            SearchConfirmationLotApp(po_num, lot_app, pack_qty,arrayPackingConfirmationLots);
          }
        }
    }); 

   $(document).on('click','.btn-remove-packing-confirmation',function(e){

    let lotapp_id = $(this).attr('lotapp-id');

    RemovePackingConfirmationLot(arrayPackingConfirmationLots, lotapp_id);
   });


   $('#btn_confirm_packing_confirmation').click(function(){

    $('#txt_search_packing_operator').val('');
    $('#modalScan_ConfirmationOperator').modal('show');

   });

   $(document).on('keypress',function(e){
      if( ($("#modalScan_ConfirmationOperator").data('bs.modal') || {})._isShown ){
        $('#txt_search_packing_operator').focus();

        if( e.keyCode == 13 && $('#txt_search_packing_operator').val() !='' && ($('#txt_search_packing_operator').val().length >= 4) ){
            $('#modalScan_ConfirmationOperator').modal('hide');

            $('#formPackingConfirmation').submit();
          }
        }
    }); 

   $('#formPackingConfirmation').submit(function(e){

    e.preventDefault();

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

    let ctr_error = 0;

    if($('#anti_rust_inclusion').val() == null)
    { 
      $('#anti_rust_inclusion').addClass('is-invalid');
      ctr_error++;
    }
      
    if(ctr_error > 0)
    {
      toastr.error('There are some missed fields. Please check!');
    }
    else
    {
      if(arrayPackingConfirmationLots.length === 0)
      {
        toastr.error('There are no Lots added!');
      }
      else
      {
         SubmitConfirmationLots(arrayPackingConfirmationLots);
      }
    }

   });

   $('#anti_rust_inclusion').change(function(){
    $('#anti_rust_inclusion').removeClass('is-invalid');
   })

   $('#modalPackingConfirmation').on('hidden.bs.modal',function(){

      array_lots = [];
      $('#anti_rust_inclusion').removeClass('is-invalid');
      $('#formPackingConfirmation')[0].reset();

   });

   //load packing confirmation details
   $(document).on('click','.btn-preliminary-inspection',function(){

      let packing_id = $(this).attr('packing-id');

      LoadPreliminaryInspectionDetails(packing_id);
   });


   $('#btn_confirm_prelim_inspection').click(function(){

    $('#txt_search_prelim_inspector').val('');
    $('#modalScan_PrelimInspector').modal('show');

   });

   $(document).on('keypress',function(e){
      if( ($("#modalScan_PrelimInspector").data('bs.modal') || {})._isShown ){
        $('#txt_search_prelim_inspector').focus();

        if( e.keyCode == 13 && $('#txt_search_prelim_inspector').val() !='' && ($('#txt_search_prelim_inspector').val().length >= 4) ){
            $('#modalScan_PrelimInspector').modal('hide');

            $('#formPrelimInspection').submit();
          }
        }
    }); 

   $('#formPrelimInspection').submit(function(e){

    e.preventDefault();

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

    let ctr_error = 0;

    if($('#packing_type').val() == null)
    { 
      $('#packing_type').addClass('is-invalid');
      ctr_error++;
    }

    if($('#unit_condition').val() == null)
    {
      $('#unit_condition').addClass('is-invalid');
      ctr_error++;
    }

    if(!$('#packing_inspection_datetime').val())
    {
      $('#packing_inspection_datetime').addClass('is-invalid');
      ctr_error++;
    }

    if(ctr_error > 0)
    {
      toastr.error('There are some missed fields!');
    }
    else
    { 
      SubmitPrelimInspection2();
    }

   });

   $('#packing_type').change(function(){
    $('#packing_type').removeClass('is-invalid');
   });

    $('#unit_condition').change(function(){
    $('#unit_condition').removeClass('is-invalid');
   });

     $('#packing_inspection_datetime').change(function(){
    $('#packing_inspection_datetime').removeClass('is-invalid');
   });

     $('#modalPreliminaryInspection').on('hidden.bs.modal',function(){
      $('#formPrelimInspection')[0].reset();
      $('#packing_type').removeClass('is-invalid');
      $('#unit_condition').removeClass('is-invalid');
      $('#packing_inspection_datetime').removeClass('is-invalid');

     });

      //load packing confirmation details
   $(document).on('click','.btn-final-inspection',function(){

      let packing_id = $(this).attr('packing-id');

      LoadFinalPackingInspectionDetails2(packing_id);
   });


    $('#btn_add_packop_conformance').click(function(){

      $('#modalScan_PackopConformance').modal('show');
      $('#txt_search_packop_conformance').val('');

    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_PackopConformance").data('bs.modal') || {})._isShown ){
        $('#txt_search_packop_conformance').focus();

        if( e.keyCode == 13 && $('#txt_search_packop_conformance').val() !='' && ($('#txt_search_packop_conformance').val().length >= 4) ){
            $('#modalScan_PackopConformance').modal('hide');

            let operator_id = $('#txt_search_packop_conformance').val();

            ValidatePackopConformance(operator_id);
          }
        }
    }); 

    $('#btn_confirm_final_inspection').click(function(e){

      $('#modalScan_FinalInspection').modal('show');
      $('#txt_search_final_inspection').val('');

    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_FinalInspection").data('bs.modal') || {})._isShown ){
        $('#txt_search_final_inspection').focus();

        if( e.keyCode == 13 && $('#txt_search_final_inspection').val() !='' && ($('#txt_search_final_inspection').val().length >= 4) ){
            $('#modalScan_FinalInspection').modal('hide');

            $('#formFinalInspection').submit();
          }
        }
    }); 

    $('#formFinalInspection').submit(function(e){

      e.preventDefault();

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

    let ctr_error = 0;

    if($('#coc_attachment').val() == null)
    { 
      $('#coc_attachment').addClass('is-invalid');
      ctr_error++;
    }

    if($('#final_result').val() == null)
    {
      $('#final_result').addClass('is-invalid');
      ctr_error++;
    }

    if($('#packop_conformance_name').val() == '')
    {
      $('#packop_conformance_name').addClass('is-invalid');
      ctr_error++;
    }

    if(!$('#final_inspection_datetime').val())
    {
      $('#final_inspection_datetime').addClass('is-invalid');
      ctr_error++;
    }

    if($('#final_remarks').val() == '')
    {
      $('#final_remarks').addClass('is-invalid');
      ctr_error++;
    }

    if(ctr_error > 0)
    {
      toastr.error('There are some missed fields!');
    }
    else
    { 
      SubmitFinalPackingInspection2();
    }

    });

    $('#coc_attachment').change(function(){

      $('#coc_attachment').removeClass('is-invalid');

    });

    $('#final_result').change(function(){

      $('#final_result').removeClass('is-invalid');

    });

    $('#packop_conformance_name').change(function(){

      $('#packop_conformance_name').removeClass('is-invalid');

    });

     $('#final_inspection_datetime').change(function(){

      $('#final_inspection_datetime').removeClass('is-invalid');

    });

    $('#final_remarks').change(function(){

      $('#final_remarks').removeClass('is-invalid');

    });

    $('#modalFinalInspection').on('hidden.bs.modal',function(){

      $('#formFinalInspection')[0].reset();
      $('#coc_attachment').removeClass('is-invalid');
      $('#final_result').removeClass('is-invalid');
      $('#packop_conformance_name').removeClass('is-invalid');
      $('#final_inspection_datetime').removeClass('is-invalid');
      $('#final_remarks').removeClass('is-invalid');

    });


  </script>
  @endsection
@endauth