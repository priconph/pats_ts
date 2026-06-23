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

@section('title', 'OQC Lot Application')

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
          <h1>OQC Lot Application</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">OQC Lot Application</li>
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
              <h3 class="card-title">OQC Lot Application</h3>
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
                <h3 class="card-title">Application of Lot (Responsible: FVI Operator)</h3>
              </div>

                <div class="card-body">
                  <div class="table-responsive dt-responsive">
                      <table id="tbl_oqclotapp" class="table table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <th>Status</th>
                              <th>Submission</th>
                              <th>Lot # / Batch #</th>
                              <th>Lot Qty</th>
                              <th style="background-color:#17a2b8; color: white;">Lot Applied By</th>
                              <th style="background-color:#ffc107">Prodn. Supv.</th>
                              <th style="background-color:#ffc107">OQC Supv.</th>
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

  <div class="modal fade" id="modalAddApplication">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add OQC Lot Application</h4>
        </div>

        <form id="formOQCLotApp" method="post">
          @csrf

        <div class="modal-body">

          <div class="row">

            <div class="col-sm-4 p-2">

              <input type="hidden" id="id_submission" name="name_submission" readonly>

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
                    <span class="input-group-text w-100" id="basic-addon1">Assembly Line</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_assemblyLine" name="name_assembly_line" readonly>
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

            </div>

            <div class="col-sm-4 p-2">

              <div class="row">
                <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Lot/Batch Number</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_lotBatchNo" name="name_lotbatch_no" readonly>
                </div>
              </div>
              </div>

              <div class="row">
                <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Lot Quantity</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_lotQuantity" name="name_lot_quantity" readonly>
                </div>
              </div>
              </div>

              <div class="row">
                <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Device Classification</span>
                  </div>
                  <select class="form-control form-control-sm" id="id_device_classification" name="name_device_classification">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>Automotive</option>
                    <option value='2'>Regular</option>
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
                    <span class="input-group-text w-100" id="basic-addon1">Application Date/Time</span>
                  </div>
                  <input type="datetime-local" class="form-control form-control-sm" id="id_applicationDateTime" name="name_application_datetime">
                </div>
              </div>
              </div>

              <div class="row">
                <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                  </div>
                  <textarea class="form-control form-control-sm" id="id_remarks" name="name_remarks" rows='3'></textarea>
                </div>
              </div>
            </div>
            </div>

          </div>

          <hr>

          <div class="table-responsive dt-responsive">
              <table id="tbl_oqclotapp_history" class="table table-bordered table-striped table-hover" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>Submission</th>
                      <th>Application Date/Time</th>
                      <th>Lot Applied By</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
              </table> 
          </div>
         
        </div>

        <div class="modal-footer">
          <button type="button" id="id_btn_close" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="button" id="id_btn_AddOQCLotApp" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
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

  </form> <!--FORM OQC LOT APPLICATION-->


  <!--MODAL FOR OQC LOT APP STICKER--->
    <div class="modal fade" id="modal_LotApp_QRcode">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> Generate QR Code</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <center>
              <div class="row">
                <!-- PO 1 -->
                <div class="col-sm-6">
                  <img src="" id="img_barcode_PO" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_no_PO"></label> <br>
                  <label id="lbl_device_name_PO"></label> <br>
                </div>

                <!-- Lot/batch# 1-->
                <div class="col-sm-6">
                  <img src="" id="img_barcode_lotno1" style="max-width: 200px;">
                  <br>
                  <label id="lbl_device_name"></label> <br>
                  <label id="lbl_po_no"></label> <br>
                  <label id="lbl_lot_batch_no"></label> <br>
<!--                   <label id="lbl_reel_lot_no"></label> <br> -->
                  <label id="lbl_lot_qty"></label>
<!--                   <label id="lbl_output_qty1"></label> <br> -->
<!--                   <label id="lbl_sticker_page_no"></label> -->
                </div>

                <!-- PO 2 -->
<!--                 <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_PO2" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_no_PO2"></label> <br>
                  <label id="lbl_device_name_PO2"></label> <br>
                </div> -->

                <!-- Lot/batch# 2-->
<!--                 <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_lotno2" style="max-width: 200px;">
                  <br>
                  <label id="lbl_device_name2"></label> <br>
                  <label id="lbl_po_no2"></label> <br>
                  <label id="lbl_lot_batch_no2"></label> <br>
                  <label id="lbl_reel_lot_no2"></label> <br>
                  <label id="lbl_lot_qty2"></label> /
                  <label id="lbl_output_qty2"></label> <br>
                  <label id="lbl_sticker_page_no2"></label>
                </div> -->

                <!-- A Drawing -->
<!--                 <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_A_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_Adrawing"></label> <br>
                  <label id="lbl_device_name_Adrawing"></label> <br>
                  <label id="lbl_adrawing"></label> <br>
                </div> -->

                <!-- Inspection Standard -->
<!--                 <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_IS_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_isdrawing"></label> <br>
                  <label id="lbl_device_name_isdrawing"></label> <br>
                  <label id="lbl_isdrawing"></label> <br>
                </div> -->

                <!-- R Drawing -->
<!--                 <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_R_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_rdrawing"></label> <br>
                  <label id="lbl_device_name_rdrawing"></label> <br>
                  <label id="lbl_rdrawing"></label> <br>
                </div> -->

              </div>
            </center>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn_print_barcode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

@endsection

@section('js_content')
<script type="text/javascript">

let dt_oqclotapp;
let dt_oqclotapp_history;

  $(document).ready(function () {
    bsCustomFileInput.init();

    dt_oqclotapp_history = $('#tbl_oqclotapp_history').DataTable({
        "processing"    : false,
          "serverSide"  : true,
          "ajax"        : 
          {
            url: "load_oqclotapp_history_table",
              data: function (param){
                param.lot_batch_no = $("#id_lotBatchNo").val();
                }
          },

          "columns":[
            { "data" : "submission" },
            { "data" : "app_datetime" },
            { "data" : "lot_applied_by" },
            { "data" : "remarks" },
          ],

      });
    });

      dt_oqclotapp = $('#tbl_oqclotapp').DataTable({
          "processing"    : false,
          "serverSide"  : true,
          "ajax"        : 
          {
            url: "load_oqclotapp_table",
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
            { "data" : "lot_applied_by" },
            { "data" : "prod_supervisor" },
            { "data" : "oqc_supervisor" }
          ],

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

          dt_oqclotapp.draw();  

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
      let po_num = $(this).attr('po-num');

      //alert(batch);
      ViewOQCApplicationTS(batch, po_num);

    });

    //

      $(document).on('click','#id_btn_AddOQCLotApp',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formOQCLotApp').modal('show');
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

    $('#formOQCLotApp').on('submit', function(e){

      e.preventDefault();

      SubmitOQCApplicationTS();
    });

        //- Print lot
    $(document).on('click', '.btn_print_lot', function(){
      var id = $(this).val();

        var data = {
            "id"          : id
        }

        data = $.param(data);
        $.ajax({
            data        : data,
            type        : 'get',
            dataType    : 'json',
            url         : "get_lot_app_by_id",
            success     : function (data) {
              console.log(data);
                 
                $("#modal_LotApp_QRcode").modal('show');
                
                //- Lot/Batch# 1
                $("#img_barcode_lotno1").attr('src', data['lot_app_code']);
                $('#lbl_device_name').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );        
                $('#lbl_po_no').text( data['lot_app_by_id'][0]['po_num'] );        
                $('#lbl_lot_batch_no').text( data['lot_app_by_id'][0]['lot_batch_no'] );        
/*                $('#lbl_reel_lot_no').text( data['lot_app_by_id'][0]['reel_lot'] );   */     
                $('#lbl_lot_qty').text( data['lot_app_by_id'][0]['lot_qty'] );
                
/*                $("#img_barcode_lotno2").attr('src', data['lot_app_code']);
                $('#lbl_device_name2').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );        
                $('#lbl_po_no2').text( data['lot_app_by_id'][0]['po_no'] );        
                $('#lbl_lot_batch_no2').text( data['lot_app_by_id'][0]['lot_batch_no'] );*/        
              /*  $('#lbl_reel_lot_no2').text( data['lot_app_by_id'][0]['reel_lot'] );        
                $('#lbl_lot_qty2').text( data['lot_app_by_id'][0]['lot_qty'] );*/

                img_barcode_lotno   = data['lot_app_code'];    
                lbl_device_name     = data['lot_app_by_id'][0]['wbs_kitting']['device_name']; 
                lbl_po_no           = data['lot_app_by_id'][0]['po_num']; 
                lbl_lot_batch_no    = data['lot_app_by_id'][0]['lot_batch_no']; 

                //lbl_reel_lot_no     = data['lot_app_by_id'][0]['reel_lot']; 

                lbl_lot_qty         = data['lot_app_by_id'][0]['lot_qty']; 

/*                lbl_output_qty      = data['lot_app_by_id'][0]['output_qty']; */
                
/*                if ( lbl_output_qty < lbl_lot_qty){
                  // alert('not equal');
                  new_lot_qty1 = (120);
                  new_lot_qty2 = (lbl_output_qty - new_lot_qty1);
                  $('#lbl_output_qty1').text( new_lot_qty1 );
                  $('#lbl_output_qty2').text( new_lot_qty2 );

                }else if ( lbl_output_qty == lbl_lot_qty){
                  // alert('equal');
                    if ( lbl_lot_qty == 240){
                      // alert('240');
                      new_lot_qty1 = (lbl_output_qty / 2);
                      new_lot_qty2 = (lbl_output_qty / 2);
                      $('#lbl_output_qty1').text( new_lot_qty1 );
                      $('#lbl_output_qty2').text( new_lot_qty2 );
                      lbl_sticker_page_no = '1/2';
                      lbl_sticker_page_no2 = '2/2';
                      $('#lbl_sticker_page_no').text( '1/2' );
                      $('#lbl_sticker_page_no2').text( '2/2' );
                    }else{
                      // alert('750');
                      new_lot_qty1 = (lbl_output_qty);
                      new_lot_qty2 = (lbl_output_qty);
                      $('#lbl_output_qty1').text( new_lot_qty1 );
                      $('#lbl_output_qty2').text( new_lot_qty2 );
                      lbl_sticker_page_no = '1/1';
                      lbl_sticker_page_no2 = '1/1';
                      $('#lbl_sticker_page_no').text( '1/1' );
                      $('#lbl_sticker_page_no2').text( '1/1' );

                    }
                }*/

                //- PO
                $("#img_barcode_PO").attr('src', data['po_no']);
               
                $('#lbl_po_no_PO').text( data['lot_app_by_id'][0]['po_num']);        
                $('#lbl_device_name_PO').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );        
                 /*$("#img_barcode_PO2").attr('src', data['po_no']);*//*$('#lbl_po_no_PO2').text( data['lot_app_by_id'][0]['po_no'] );        
                $('#lbl_device_name_PO2').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] ); */       

                img_barcode_PO      = data['po_no'];    

/*                //- A Drawing
                $("#img_A_drawing").attr('src', data['A_drawing']);
                $('#lbl_adrawing').text( data['lot_app_by_id'][0]['Adrawing'] );  
                $('#lbl_po_Adrawing').text( data['lot_app_by_id'][0]['po_no'] );        
                $('#lbl_device_name_Adrawing').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );        

                img_A_drawing   = data['A_drawing'];    
                lbl_adrawing    = data['lot_app_by_id'][0]['Adrawing']; 

                //- Inspection Standard
                $("#img_IS_drawing").attr('src', data['IS_drawing']);
                $("#lbl_isdrawing").text( data['inspection_standard'][0]['doc_no']+'-'+data['inspection_standard'][0]['rev_no']);
                $('#lbl_po_isdrawing').text( data['lot_app_by_id'][0]['po_no'] );        
                $('#lbl_device_name_isdrawing').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );        

                img_IS_drawing   = data['IS_drawing'];    
                lbl_isdrawing    = data['inspection_standard'][0]['doc_no']+'-'+data['inspection_standard'][0]['rev_no'];

                //- R Drawing
                $("#img_R_drawing").attr('src', data['R_drawing']);
                $("#lbl_rdrawing").text( data['rdrawing'][0]['doc_no']+'-'+data['rdrawing'][0]['rev_no']);
                $('#lbl_po_rdrawing').text( data['lot_app_by_id'][0]['po_no'] );        
                $('#lbl_device_name_rdrawing').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );        

                img_R_drawing   = data['R_drawing'];    
                lbl_rdrawing    = data['rdrawing'][0]['doc_no']+'-'+data['rdrawing'][0]['rev_no'];*/


                $('#modal_LotApp_QRcode').modal({
                  backdrop: 'static',
                  keyboard: false, 
                  show: true
                });

                
            }, error    : function (data) {
            alert('ERROR: '+data);
            }
        });

      });

//- Print Barcode
    $("#btn_print_barcode").click(function(){
      popup = window.open();
        let content = '';
        
        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';
        
        content += '.rotated {';
        content += 'width: 180px;';
        content += 'position: absolute;';
        content += 'left: 15px;';
        content += '}';

        content += '.s {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 15px;';
        content += '}';

        content += '.s1 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 40px;';
        content += '}';

        content += '.s2 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 66px;';
        content += '}';

        content += '.s3 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 40px;';
        content += '}';

        content += '</style>';
        content += '</head>';
        content += '<body>';

        content += '<table>';
        
        //- 1st sticker QR
        content += '<div class="rotated">';
        content += '<tr>';
            content += '<td style="text-align: center;">';
            content += '<img src="' + img_barcode_PO + '" style="min-width: 45px; max-width: 45px;">';
            content += '</td>';

            content += '<td>';
            content += '<div class="s"></div>';
            content += '</td>';

            content += '<td style="text-align: center;">';
            content += '<img src="' + img_barcode_lotno + '" style="min-width: 43px; max-width: 43px;">';
            content += '</td>';
        content += '</tr>';
        content += '</div>';

        //- 1st QR details
        content += '<tr>';
            content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
            content += '<label style="font-weight: bold;">' + lbl_po_no + '</label>';
            content += '<br>';
            content += '<label>' + lbl_device_name + '</label>';
            content += '</td>';

            content += '<td>';
            content += '<div class="s1"></div>';
            content += '</td>';

            content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
            content += '<label>' + lbl_device_name + '</label>'; 
            content += '<br>';
            content += '<label>' + lbl_po_no + '</label>';
            content += '<br>';
            content += '<label style="font-weight: bold;">' + lbl_lot_batch_no + '</label>';
            content += '<br>';
/*            content += '<label>' + lbl_reel_lot_no + '</label>';
            content += '<br>';*/
/*            content += '<label>' + lbl_lot_qty + "/" + new_lot_qty1 + '</label>';
            content += '<br>';
            content += '<label>' + lbl_sticker_page_no + '</label>';*/
            content += '</td>';
        content += '</tr>';
        
/*        content += '<div class="rotated">';*/
        //- 2nd sticker
/*        content += '<tr>';
            content += '<td style="width: 50%; text-align: center;">';
            content += '<img src="' + img_barcode_PO + '" style="min-width: 45px; max-width: 45px;">';
            content += '</td>';

            content += '<td>';
            content += '<div class="s"></div>';
            content += '</td>';

            content += '<td style="width: 50%; text-align: center;">';
            content += '<img src="' + img_barcode_lotno + '" style="min-width: 43px; max-width: 43px;">';
            content += '</td>';
        content += '</tr>';

        // //- lot_batch# 2
        content += '<tr>';
            content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
            content += '<label style="font-weight: bold;">' + lbl_po_no + '</label>';
            content += '<br>';
            content += '<label>' + lbl_device_name + '</label>';
            content += '</td>';

            content += '<td>';
            content += '<div class="s2"></div>';
            content += '</td>';

            content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
            content += '<label>' + lbl_device_name + '</label>';
            content += '<br>';
            content += '<label>' + lbl_po_no + '</label>';
            content += '<br>';
            content += '<label style="font-weight: bold;">' + lbl_lot_batch_no + '</label>';
            content += '<br>';
            content += '<label>' + lbl_reel_lot_no + '</label>';
            content += '<br>';
            content += '<label>' + lbl_lot_qty + "/" + new_lot_qty2 + '</label>';
            content += '<br>';
            content += '<label>' + lbl_sticker_page_no2 + '</label>';
            content += '</td>';
        content += '</tr>';
        content += '</div>';*/

        //- 3rd Drawing
        /*content += '<div class="rotated">';
            content += '<table>';
            content += '<tr>';
                content += '<td style="width: 30%; text-align: center;">';
                content += '<img src="' + img_A_drawing + '" style="min-width: 43px; max-width: 43px;">';
                content += '</td>';

                content += '<td>';
                content += '<div class="s"></div>';
                content += '</td>';

                content += '<td style="width: 30%; text-align: center;">';
                content += '<img src="' + img_IS_drawing + '" style="min-width: 43px; max-width: 43px;">';
                content += '</td>';

                content += '<td>';
                content += '<div class="s"></div>';
                content += '</td>';

                content += '<td style="width: 30%; text-align: center;">';
                content += '<img src="' + img_R_drawing + '" style="min-width: 43px; max-width: 43px;">';
                content += '</td>';
            content += '</tr>';

            content += '<tr>';
                content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
                content += '<label>' + lbl_device_name + '</label>';
                content += '<br>';
                content += '<label style="font-weight: bold;">' + lbl_adrawing + '</label>';
                content += '</td>';

                content += '<td>';
                content += '<div class="s3"></div>';
                content += '</td>';

                content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
                content += '<label>' + lbl_device_name + '</label>';
                content += '<br>';
                content += '<label style="font-weight: bold;">' + lbl_isdrawing + '</label>';
                content += '</td>';

                content += '<td>';
                content += '<div class="s3"></div>';
                content += '</td>';

                content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
                content += '<label>' + lbl_device_name + '</label>';
                content += '<br>';
                content += '<label style="font-weight: bold;">' + lbl_rdrawing + '</label>';
                content += '</td>';
            content += '</tr>';
            content += '</table>';
        content += '</div>';*/

        content += '</table>';     


        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
    });

</script>
@endsection
@endauth