@auth
  @php
    if(Auth::user()->position == 1){ 
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->position == 2){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->position == 3){
      $layout = 'layouts.mh_user_layout';
    }
    else if(Auth::user()->position == 4){
      $layout = 'layouts.production_user_layout';
    }
    else if(Auth::user()->position == 5){
      $layout = 'layouts.ipqc_user_layout';
    }
    else if(Auth::user()->position == 6){
      $layout = 'layouts.whse_layout';
    }
    else if(Auth::user()->position == 7){
      $layout = 'layouts.ppc_layout';
    }
    else if(Auth::user()->position == 8){
      $layout = 'layouts.clerk_layout';
    }
    else if(Auth::user()->position == 9){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->position == 10){
      $layout = 'layouts.oqc_layout';
    }
    else if(Auth::user()->position == 11){
      $layout = 'layouts.technician_user_layout';
    }
    else if(Auth::user()->position == 12){
      $layout = 'layouts.admin_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

  @section('title', 'Parts Preparatory')

  @section('content_page')
  <style type="text/css">
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
    textarea{
      resize: none;
    }
    #mdl_edit_material_details>div{
      /*width: 2000px!important;*/
      /*min-width: 1400px!important;*/
    }
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
            <h1>Parts Preparatory</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Parts Preparatory</li>
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
                <h3 class="card-title">Search PO Number</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info" id="btn_scan_po"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <!-- <input type="search" class="form-control" placeholder="Scan PO Number" id="txt_search_po_number" value="450196479600010" readonly> -->
                      <input type="search" class="form-control" placeholder="Scan PO Number" id="txt_search_po_number" readonly>
                    </div>
                  </div>
                  <div class="col-3 offset-3">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">PO No.</span>
                      </div>
                      <input id="txt_po_number_lbl" type="text" class="form-control" placeholder="---" readonly>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Device</span>
                      </div>
                      <input id="txt_device_name" type="text" class="form-control" placeholder="---" readonly>
                    </div>
                  </div>
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


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Lot Numbers</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="kitting-tab" data-toggle="tab" href="#kitting" role="tab" aria-controls="kitting" aria-selected="true">Material Kitting & Issuance
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="sakidashi-tab" data-toggle="tab" href="#sakidashi" role="tab" aria-controls="sakidashi" aria-selected="false">Sakidashi Issuance
                        </a>
                      </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active my-2" id="kitting" role="tabpanel" aria-labelledby="kitting-tab">
                        <div class="row">
                          <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" id="btn_scan_transfer_slip"><i class="fa fa-qrcode"></i></button>
                              </div>
                              <!-- <input type="search" class="form-control" placeholder="Scan Transfer Slip" id="txt_scan_material_lot" value="A171007" readonly> -->
                              <input type="search" class="form-control" placeholder="Scan Transfer Slip" id="txt_scan_material_lot" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover" id="tbl_lot_numbers" style="min-width: 600px;">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Lot #</th>
                                <th>Kit #</th>
                                <th>Transfer Slip</th>
                                <th>Item Code</th>
                                <th>Part Name</th>
                                <th>QTY</th>
                                <th>Created by</th>
                                <th>Created at</th>
<!--                                 <th>USG</th>
                                <th>RQD</th>
 -->


                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade my-2" id="sakidashi" role="tabpanel" aria-labelledby="sakidashi-tab">
                        <div class="row">
                          <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" id="btn_scan_sakidashi_lot"><i class="fa fa-qrcode"></i></button>
                              </div>
                              <input type="search" class="form-control" placeholder="Scan lot number" id="txt_scan_sakidashi_lot" readonly><!-- value="450198990900010" -->
                            </div>
                          </div>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover" id="tbl_sakidashi">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Ctrl No.</th>
                                <th>Item Code</th>
                                <th>Contact type</th>
                                <th>Lot #/Pair #</th>
                                <th>Device Code</th>
                                <th>Req issuance qty</th>
                                <th>Reel qty</th>
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
              <!-- !-- End Page Content -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_edit_material_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> Parts Prep - Runcard</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row" hidden>
              <div class="col text-right">
                <h4>Runcard #:<input type="text" id="txt_runcard_number" class="border-0 bg-transparent text-right" style="outline: none;width: 170px;" readonly></h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Device</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_use_for_device" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_number_label" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Lot #</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_lot_number" name="txt_lot_number" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                      </div>
                      <input type="number" class="form-control form-control-sm" id="txt_po_qty" readonly>
                    </div>
                  </div>
                </div>
                <div class="row" style="display: none;">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Kit Qty</span>
                      </div>
                      <input type="number" class="form-control form-control-sm" id="txt_kit_qty" readonly>
                    </div>
                  </div>
                </div>
              </div><!-- col -->
            </div>
            <div class="row">
              <div class="col">
                <h5>Station</h5>
              </div>
              <div class="col text-right">
                <button type="button" class="btn btn-sm btn-info" id="btn_add_station_record"><i class="fa fa-plus"></i> Add Record</button>
              </div>
            </div>
            <div class="row">
              <div class="col">                
                <div class="table-responsive">
                  <table class="table table-sm table-bordered table-hover" id="tbl_parts_prep_stations" style="min-width: 600px;width: 100%;">
                    <thead>
                      <tr class="bg-light">
<!--                         <th>Action</th>
                        <th>Step</th> -->
                        <th></th>
                        <th>Station</th>
                        <th>Operator</th>
                        <th>Date</th>
                        <th>WW</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Machine Code</th>
                        <th>Remarks</th>
                        <!-- <th>Note</th> -->
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div><!-- col -->
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="txt_wbs_table" id="txt_wbs_table">
            <input type="hidden" name="txt_parts_preps_id_query" id="txt_parts_preps_id_query">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_edit_partsprep_station_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-object-group text-info"></i> Stations</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="frm_edit_partsprep_station_details">
              <div class="row" hidden>
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Step</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_partsprep_station_step">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Stations</span>
                    </div>
                    <select class="form-control form-control-sm" id="txt_edit_partsprep_station_station" name="txt_edit_partsprep_station_station" required></select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Operator</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="txt_edit_prod_station_operator" name="txt_edit_prod_station_operator[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_add_runcard_operator_code"><i class="fa fa-qrcode"></i></button>
                    </div>
                    <!-- <input type="text" class="form-control form-control-sm" placeholder="Auto generated" readonly> -->
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Date</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" placeholder="Auto generated" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">WW</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_partsprep_station_ww" name="txt_edit_partsprep_station_ww"required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Input</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_partsprep_station_input" name="txt_edit_partsprep_station_input" min="1" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Output</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_partsprep_station_output" name="txt_edit_partsprep_station_output" min="0" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Machine</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_partsprep_station_machine" name="txt_edit_partsprep_station_machine" required>
                      <option value="N/A"> N/A </option>
                    </select>

<!--                     <select class="form-control form-control-sm" id="txt_edit_partsprep_station_machine" name="txt_edit_partsprep_station_machine" required></select>
 -->                    
                  <!--   <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_partsprep_station_machine" name="txt_edit_partsprep_station_machine[]" multiple="multiple">
                      <option value="N/A"> N/A </option>
                    </select> -->

<!--                     <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_machine_code"><i class="fa fa-qrcode"></i></button>
                    </div>
 -->                    
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                    </div>
                     <select class="form-control form-control-sm" id="txt_remarks" name="txt_remarks">
                      <option selected disabled>-- Choose One --</option>
                      <option value="1">LASER</option>
                      <option value="2">MARKEM</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100">Note <i>(Optional)</i></span>
                    </div>
                    <textarea class="form-control form-control-sm" rows="2" id="txt_edit_partsprep_station_remarks" name="txt_edit_partsprep_station_remarks"></textarea>
                  </div>
                </div>
              </div> -->
            </form>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="txt_partsprep_station_id_query" name="txt_partsprep_station_id_query" form="frm_edit_partsprep_station_details">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-sm btn-success" id="btn_save_partsprep_station_stations" form="frm_edit_partsprep_station_details">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_employee_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <h1><i class="fa fa-barcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_qrcode_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan the code.
              <br>
              <br>
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_qrcode_scanner" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-1">
            <h5 class="modal-title" id="mdl_alert_title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="mdl_alert_body">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->
  </div>

  <div class="modal fade" id="modalGenWWToPrint">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> Generate Sticker Label</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <center>
              <label>WW - </label>
              <label id="lblGenPartsPrepWW">...</label> <br>
              <label>QTY - </label>
              <label id="lblGenPartsPrepQty">...</label> <br>
              
              <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
              ->size(150)->errorCorrection('H')
              ->generate('0')) !!}" id="imgGenOpt1Barcode" style="max-width: 200px; min-width: 200px;">
              <label id="lblGenPartsPrepOpt1">...</label> <br>
              <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
              ->size(150)->errorCorrection('H')
              ->generate('0')) !!}" id="imgGenOpt2Barcode" style="max-width: 200px; min-width: 200px;">
              <label id="lblGenPartsPrepOpt2">...</label> <br>
              <br>


            </center>

        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" id="btnPrintPartsPrepWWBarcode" class="btn btn-primary"><i id="iBtnPrintPartsPrepWWBarcodeIcon" class="fa fa-print"></i> Print</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- /.content-wrapper -->
  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dt_lot_numbers, dt_setup_stations, dt_parts_prep_stations,dt_sakidashi,dt_warehouse;
    $(document).ready(function () {
      setTimeout(function() {
        $('.nav-link').find('.fa-bars').closest('a').click();
      }, 100);
  
      $(document).on('show.bs.modal', '.modal', function () {
          var zIndex = 1040 + (10 * $('.modal:visible').length);
          $(this).css('z-index', zIndex);
          setTimeout(function() {
              $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
          }, 0);
      });

      $('input').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          if( $(this).prop('type') == 'number' ){
            $(this).prop('placeholder','0');
          }
          if( $(this).prop('type') == 'text' ){
            $(this).prop('placeholder','---');
          }
        }
      });
      $('textarea').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          $(this).prop('placeholder','...');
        }
      });

      bsCustomFileInput.init();
      // fn_select_list_partsprep_station_machines();
      fn_select_list_partsprep_station_stations();
      // fn_select_list_partsprep_station_mod();
      //-----------------------
      // $(document).on('keyup','#txt_search_po_number',function(e){
      //   if( e.keyCode == 13 ){
      //     search_po_number($(this));
      //   }
      // });
      //-----------------------
      dt_lot_numbers      = $('#tbl_lot_numbers').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_partspreparatory_materials",
            data: function (param){
                param.po_number                       = $("#txt_search_po_number").val();
                param.txt_scan_material_lot          = $("#txt_scan_material_lot").val();
            }
          },
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false, width: '80px' },
            { "data" : "raw_status" },
            { "data" : "lot_no" },
            { "data" : "kit_issuance.kit_no" },
            { "data" : "issue_no" },
            { "data" : "item" },
            { "data" : "item_desc" },
            { "data" : "issued_qty" },
            { "data" : "kit_issuance.create_user" },
            { "data" : "kit_issuance.created_at" },
            // { "data" : "raw_usage" },
            // { "data" : "raw_rqd_qty" },
          ],
          paging: false,
          order: [[1, "asc"]],
          "rowCallback": function(row,data,index ){
            if( $(row).html().toLowerCase().indexOf('returned')>0 ){
              $(row).addClass('table-warning');
            }
            // else if( $(row).html().toLowerCase().indexOf('incomplete')>0 ){
            //   $(row).addClass('table-secondary');
            // }
            // else if( $(row).find('.col_parts_preps_id').val()!=0 ){
            //   $(row).addClass('table-success');
            // }
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable
      //-----------------------
      //-----------------------
      //-----------------------
      dt_sakidashi      = $('#tbl_sakidashi').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_sakidashi_parts_prep",
            data: function (param){
                param.po_number               = $("#txt_search_po_number").val();
                param.txt_scan_sakidashi_lot  = $("#txt_scan_sakidashi_lot").val();
            }
          },
          bAutoWidth: false,
          paging: false,
          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status" },
            { "data" : "issuance_no" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.item" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no" },
            { "data" : "device_code" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.required_qty" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.issued_qty" },
          ],
          order: [[1, "asc"]],
          "rowCallback": function(row,data,index ){
            if( $(row).html().toLowerCase().indexOf('done verification')>0 ){
              $(row).addClass('table-success');
            }
            if( $(row).html().toLowerCase().indexOf('returned')>0 ){
              $(row).addClass('table-warning');
            }
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable
      //-----------------------
      //-----------------------
      //-----------------------
      // dt_warehouse      = $('#tbl_warehouse').DataTable({
      //   "processing" : true,
      //     "serverSide" : true,
      //     "ajax" : {
      //       url: "view_warehouse_parts_prep",
      //       data: function (param){
      //           param.po_number = $("#txt_po_number_lbl").val();
      //           param.device_code = $("#txt_device_name").attr('data-device_code');
      //       }
      //     },
      //     bAutoWidth: false,
      //     paging: false,
      //     "columns":[
      //       { "data" : "action", orderable:false, searchable:false, width: '30px' },
      //       { "data" : "status" },
      //       { "data" : "issuance_no" },
      //       { "data" : "request_no" },
      //       { "data" : "tbl_request_summary.status" },
      //       { "data" : "tbl_request_summary.destination" },
      //       { "data" : "tbl_request_summary.line" },

      //       { "data" : "item" },
      //       { "data" : "item_desc" },

      //       { "data" : "tbl_request_detail.classification" },
      //       { "data" : "tbl_request_detail.issuedqty" },
      //       { "data" : "tbl_request_detail.requestqty" },
      //       { "data" : "tbl_request_detail.servedqty" },
      //       { "data" : "tbl_request_detail.requestedby" },
      //       { "data" : "tbl_request_detail.created_at" },
      //       { "data" : "tbl_request_detail.last_served_by" },
      //       { "data" : "tbl_request_detail.last_served_date" },
      //       { "data" : "tbl_request_detail.remarks" },
      //     ],
      //     order: [[1, "asc"]],
      //     "rowCallback": function(row,data,index ){
      //       if( $(row).html().toLowerCase().indexOf('done verification')>0 ){
      //         $(row).addClass('table-success');
      //       }
      //       if( $(row).html().toLowerCase().indexOf('returned')>0 ){
      //         $(row).addClass('table-warning');
      //       }
      //     },
      //     "drawCallback": function(row,data,index ){
      //     },
      // });//end of DataTable
      //-----------------------
      //-----------------------
      //-----------------------
      $(document).on('click','.btn_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['wbs_table']       = 1;
        fn_select_material_details(data_arr);
      });
      $(document).on('click','.btn_sakidashi_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['wbs_table']       = 2;
        fn_select_material_details(data_arr);
      });
      $(document).on('click','#btn_edit_material_details_primary',function(e){
        readonly_material_details_primary(false);
      });
      $(document).on('click','#btn_cancel_material_details_primary',function(e){
        readonly_material_details_primary(true);
      });
      $(document).on('click','#btn_edit_material_details_for_parts_prep_member',function(e){
        readonly_material_details_for_parts_prep(false);
      });
      $(document).on('click','#btn_cancel_material_details_primary_parts_prep',function(e){
        readonly_material_details_for_parts_prep(true);
      });
      $(document).on('click','#btn_edit_material_details_secondary',function(e){
        readonly_material_details_secondary(false);
        fn_select_list_runcard_numbers();
      });
      $(document).on('click','#btn_cancel_material_details_secondary',function(e){
        readonly_material_details_secondary(true);
      });
      //-----------------------
      $(document).on('click','#btn_save_material_details_primary',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details').modal('show');
      });
      $(document).on('click','#btn_save_material_details_primary_parts_prep',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details_for_parts_prep_member').modal('show');
      });
      $(document).on('click','#btn_save_setup_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_setup_stations').modal('show');
      });
      $(document).on('click','#btn_save_material_details_secondary',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details_secondary').modal('show');
      });
      $(document).on('click','#btn_approve_prod',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_approve_prod').modal('show');
      });
      $(document).on('click','#btn_save_partsprep_station_stations',function(e){
        var alert_msg = '';
        if( $('#txt_edit_partsprep_station_output').val() > $('#txt_edit_partsprep_station_input').val() ){
          alert_msg = 'Output quantity must be less than or equal to the Input quantity.';
        }
        if( $('#txt_edit_partsprep_station_machine').val() == 0 ){
          alert_msg = 'Please select machine.';
        }
        if( $('#txt_edit_partsprep_station_input').val() < 1 ){
          alert_msg = 'Input must be greater than 0.';
        }
        if( $('#txt_edit_partsprep_station_output').val() < 0 ){
          alert_msg = 'Output must be greater than or equal to 0.';
        }
        if( $('#txt_edit_partsprep_station_station').val() == 0 ){
          alert_msg = 'Please select station.';
        }

        if(alert_msg){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
            return;
        }

        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_partsprep_station_details').modal('show');
      });
      //-----------------------
      dt_parts_prep_stations = $('#tbl_parts_prep_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_partspreparatory_stations",
            data: function (param){
                param.parts_prep_id_query          = $("#txt_parts_preps_id_query").val();
                param.enable_edit                  = false;//prev true
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            // { "data" : "step_num" },
            { "data" : "sub_station.name" },
            { "data" : "operators_info" },
            { "data" : "created_at" },
            { "data" : "ww" },
            { "data" : "qty_input" },
            { "data" : "qty_output" },
            { "data" : "raw_machine" },
            { "data" : "remarks_lbl" },
            // { "data" : "raw_qty_ng" },
            // { "data" : "raw_mod" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
          "rowCallback": function(row,data,index ){
            if( $('#mdl_edit_material_details').attr('data-status') == 1 ){
              if( $(row).find('.btn_edit_partsprep_station').prop('disabled') == false ){
                $(row).find('.btn_edit_partsprep_station').prop('disabled',false);
              }
            }
            if( $(row).find('td:eq(4)').text() ){
              $(row).find('.btn_edit_partsprep_station').prop('disabled',true);
            }
            else{
              $('#btn_edit_material_details_secondary').prop('disabled',true);
            }
          },
          "drawCallback": function(row,data,index ){
            if( dt_parts_prep_stations.rows().count() < 1 ){
              $('#btn_edit_material_details_secondary').prop('disabled',true);
            }
          },
      });//end of DataTable
      //-----------------------
      $(document).on('keypress',function(e){
        if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){
          $('#txt_employee_number_scanner').focus();
          if( e.keyCode == 13 ){
            $('#mdl_employee_number_scanner').modal('hide');
            var formid = $("#mdl_employee_number_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'save_approve_prod':
                  save_approve_prod();
                break;

                default:
                break;
              }
            }
          }
        }
        else if( ($("#mdl_qrcode_scanner").data('bs.modal') || {})._isShown ){
          $('#txt_qrcode_scanner').focus();
          if( e.keyCode == 13 ){
            $('#mdl_qrcode_scanner').modal('hide');
            var formid = $("#mdl_qrcode_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                // case 'fn_scan_machine_code':
                //   $('#txt_edit_partsprep_station_machine option').attr('selected',false);
                //   var val = $('#txt_qrcode_scanner').val().toLowerCase();
                //   $('#txt_edit_partsprep_station_machine option[data-code="'+val+'"]').attr('selected',true);
                // break;

                case 'fn_scan_po_number':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_search_po_number').val(val);
                  search_po_number( $('#txt_search_po_number') );
                break;

                case 'fn_scan_transfer_slip':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_scan_material_lot').val(val);
                  dt_lot_numbers.ajax.reload();
                break;

                case 'fn_scan_sakidashi_lot':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_scan_sakidashi_lot').val(val);
                  dt_sakidashi.ajax.reload();
                break;

                default:
                break;
              }
            }            
          }//key
        }
      });
      //-----------------------
      $('#frm_edit_material_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize()+'&txt_parts_preps_id_query='+$("#txt_parts_preps_id_query").val()+'&txt_wbs_kit_issuance_id_query='+$("#txt_wbs_kit_issuance_id_query").val()+'&txt_runcard_number='+$("#txt_runcard_number").val()+'&txt_employee_number_scanner='+$("#txt_employee_number_scanner").val()+'&_token={{ csrf_token() }}',
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_material_details',
          success     : function(data){
            // toastr.success('Material Process was succesfully saved!');
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            readonly_material_details_primary(true);
            //---
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['wbs_table']       = $('#txt_wbs_table').val();
            fn_select_material_details(data_arr);
            //---
            dt_lot_numbers.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });

      $('#frm_edit_material_details_secondary').submit(function(e){
        e.preventDefault();

        var status = 5;
        if( $('#txt_discrepant_qty').val() > 0 ){
          status = 6;
          if( !$('#txt_analysis').val() ){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Analysis is required.',0);
            return;
          }
        }

        if( $('#txt_discrepant_qty_sign').val() == '-' ){
          if( !$('#txt_other_runcard_number').val() ){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Runcard number in Process #4 is required.',0);
            return;
          }
        }

        $.ajax({
          'data'      : $(this).serialize()+'&txt_parts_preps_id_query=' + $("#txt_parts_preps_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val()+'&status=' + status+'&_token={{ csrf_token() }}',
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_material_details_secondary',
          success     : function(data){
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            //---
            dt_parts_prep_stations.ajax.reload();
            dt_lot_numbers.ajax.reload();
            dt_sakidashi.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });

















      //-----------------------
      //-----------------------
      //-----------------------
      $('#mdl_edit_material_details').on('click','#btn_add_station_record',function(e){
        $('#mdl_edit_partsprep_station_details input').val('');
        $('#mdl_edit_partsprep_station_details select').val('0');
        $('#mdl_edit_partsprep_station_details textarea').val('');

        $('#mdl_edit_partsprep_station_details').modal('show');
      });




      $(document).on('click','.btn_edit_partsprep_station',function(e){
        var data_arr = [];
        data_arr['col_partsprep_station_id']     = $(this).closest('tr').find('.col_partsprep_station_id').val();
        // data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();
        fn_select_partsprep_station_details(data_arr);
      });
      //-----------------------
      $(document).on('click','#btn_scan_machine_code',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_machine_code').modal('show');
      });
      $(document).on('click','#btn_scan_po',function(e){
        $('#txt_search_po_number').val('');
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_po_number').modal('show');
      });
      $(document).on('click','#btn_scan_transfer_slip',function(e){
        $('#txt_scan_material_lot').val('');
        dt_lot_numbers.ajax.reload();
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_transfer_slip').modal('show');
      });
      $(document).on('click','#btn_scan_sakidashi_lot',function(e){
        $('#txt_scan_sakidashi_lot').val('');
        dt_sakidashi.ajax.reload();
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_sakidashi_lot').modal('show');
      });
      //-----------------------
      $('#btn_edit_partsprep_station_add_ng').click(function(){
        partsprep_station_add_ng('',0,0);
      })

      $(document).on('click','.btn_edit_partsprep_station_remove_ng',function(){
        $(this).closest('.row').remove();
      });

      $('#frm_edit_partsprep_station_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token={{ csrf_token() }}' + '&txt_parts_preps_id_query=' + $("#txt_parts_preps_id_query").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_partsprep_station_details',
          success     : function(data){
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            //---
            dt_lot_numbers.ajax.reload();
            dt_parts_prep_stations.ajax.reload();
            $('#mdl_edit_partsprep_station_details').modal('hide');
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });

      $(document).on('keyup change click','.txt_edit_partsprep_station_ng, .btn_edit_partsprep_station_remove_ng',function(e){
        var vin = $('#txt_edit_partsprep_station_input').val();
        var vng = 0;
        $('#row_ng .txt_edit_partsprep_station_ng').each(function(){
          vng = (vng*1)+($(this).val()*1);
        });

        var vout = vin-vng;
        $('#txt_edit_partsprep_station_output').val(vout);
      });




    });//doc
    //-----------------------
    //-----------------------
    //-----------------------
    function search_po_number(element){
        $('#txt_po_number').val('');
        $('#txt_device_name').val('').attr('data-device_code','');
        var data = {
          'po_number' : $('#txt_search_po_number').val(),
        }
        $.ajax({
          'data'      : data,
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'select_po_details',
          success     : function(data){
            if ($.trim(data)){
              $('#txt_po_number_lbl').val( data[0]['po'] );
              $('#txt_device_name').val( data[0]['kit_issuance']['device_name'] ).attr('data-device_code',data[0]['kit_issuance']['device_code']);
            }
            dt_lot_numbers.ajax.reload();
            dt_sakidashi.ajax.reload();
            // dt_warehouse.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
    }
    function reset_material_details_primary(){
      $('#txt_use_for_device').val('');
      $('#txt_po_number_label').val('');
      $('#txt_po_qty').val('');
      $('#txt_lot_number').val('');

      readonly_material_details_primary(true);
    }
    function readonly_material_details_primary(v){
      $('#txt_assessment_number').prop('readonly',v);
      $('#txt_special_instruction').prop('readonly',v);
      $('#txt_material_remarks').prop('readonly',v);

      $('#btn_save_material_details_primary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_primary').closest('.row').show();
      }
    }
    //---
    function reset_material_details_for_parts_prep(){
      $('#txt_lot_number').val('');
      $('#txt_drawing_number').val('');
      $('#txt_drawing_number_revision_number').val('');
      $('#txt_sgc_docs_number').val('');
      $('#txt_sgc_docs_number_revision_number').val('');
      $('#txt_other_docs_number').val('');
      $('#txt_parts_prep_remarks').val('');

      readonly_material_details_for_parts_prep(true);
    }
    function readonly_material_details_for_parts_prep(v){
      $('#txt_sgc_docs_number').prop('readonly',v);
      $('#txt_sgc_docs_number_revision_number').prop('readonly',v);
      $('#txt_other_docs_number').prop('readonly',v);
      $('#txt_parts_prep_remarks').prop('readonly',v);

      $('#btn_save_material_details_primary_parts_prep').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_primary_parts_prep').closest('.row').show();
      }
    }
    //---
    function reset_material_details_secondary(){
      $('#txt_discrepant_qty_sign').val('0');
      $('#txt_discrepant_qty').val('');
      $('#txt_analysis').val('');
      $('#txt_recount_ok').val('');
      $('#txt_recount_ng').val('');
      $('#txt_other_runcard_number').val('');

      readonly_material_details_secondary(true);
    }
    function readonly_material_details_secondary(v){
      $('#txt_discrepant_qty_sign').prop('disabled',v);
      $('#txt_discrepant_qty').prop('readonly',v);
      $('#txt_analysis').prop('readonly',v);
      $('#txt_recount_ok').prop('readonly',v);
      $('#txt_recount_ng').prop('readonly',v);
      $('#txt_other_runcard_number').prop('readonly',v);

      $('#btn_save_material_details_secondary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_secondary').closest('.row').show();
      }
    }
    //---
    function fn_select_material_details(data_arr){
      $('#btn_add_station_record').prop('hidden',true);

      $('#txt_wbs_table').val( data_arr['wbs_table'] );
      $('#txt_parts_preps_id_query').val('');
      $('#txt_runcard_number').val('');

      reset_material_details_primary();
      get_stations();

      var data = {
        'material_id'     : data_arr['material_id'],
        'wbs_table'       : data_arr['wbs_table'],
        'device_name_query'   : $('#txt_device_name').val(),//for acdcs drawing only
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_partspreparatory_material_details',
        success     : function(data){
          if ($.trim(data)){
            var runcard_number  = data[0]['parts_preps'][0]['runcard_number'];
            var runcard_number = "" + runcard_number;
            var pad = "000";
            runcard_number      = pad.substring(runcard_number.length) + runcard_number;
            $('#txt_runcard_number').val( data[0]['parts_preps'][0]['runcard_po'] + '' +runcard_number );
            //---
            if( data[0]['user_position'] == 4 ){//operator
              $('#btn_add_station_record').prop('hidden',false);//user access
            }
            //---
            $('#txt_parts_preps_id_query').val(data[0]['parts_preps'][0]['id']);
            if( data_arr['wbs_table'] == 1 ){//kit
              $('#txt_use_for_device').val(data[0]['kit_issuance']['device_name']);
              $('#txt_po_number_label').val(data[0]['po']);
              $('#txt_po_qty').val(data[0]['kit_issuance']['po_qty']);
              $('#txt_lot_number').val(data[0]['lot_no']);
            }
            else if( data_arr['wbs_table'] == 2 ){//sakidashi
              $('#txt_use_for_device').val(data[0]['device_name']);
              $('#txt_po_number_label').val(data[0]['po_no']);
              $('#txt_po_qty').val(data[0]['po_qty']);
              $('#txt_lot_number').val(data[0]['tbl_wbs_sakidashi_issuance_item']['lot_no']);
            }
          }
          dt_parts_prep_stations.ajax.reload();
          $('#mdl_edit_material_details').modal('show');
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }












    // 10062022 Nessa
    // function fn_select_list_partsprep_station_machines(){
    //   var data = {
    //     '_token'                   : '{{ csrf_token() }}',
    //   }
    //   $.ajax({
    //     'data'      : data,
    //     'type'      : 'get',
    //     'dataType'  : 'json',
    //     'url'       : 'select_list_partsprep_station_machines',
    //     success     : function(data){
    //       if ($.trim(data)){
    //         var html = '<option value="0">---</option>';
    //         // for (var i = 0; i < data.length; i++) {
    //           // html+='<option value="'+data[i]['id']+'" data-code="'+(data[i]['barcode']).toLowerCase()+'">'+data[i]['name']+' '+data[i]['barcode']+'</option>';
             
    //           // html+='<option value="'+data[i]['id']+'" data-code="'+data[i]['machine_code_number']+'">'+data[i]['machine_name']+' '+data[i]['machine_code_number']+'</option>';
    //           html+='<option value="639" data-code="TS-M-000054">Laser Printing Machine (LPM#01)</option>';
    //           html+='<option value="640" data-code="TS-M-000055">Laser Printing Machine (LPM#02)</option>';
    //           html+='<option value="641" data-code="TS-M-000056">Laser Printing Machine (LPM#04)</option>';
    //           html+='<option value="643" data-code="TS-M-000058">Laser Printing Machine (LPM#07)</option>';
    //           html+='<option value="18569" data-code="TS-M-001063">Laser Printing Machine No.08 (LPM#08)</option>';
    //         // }
    //         $('#txt_edit_partsprep_station_machine').html(html);
    //       }
    //     },
    //     completed     : function(data){

    //     },
    //     error     : function(data){

    //     },
    //   });

    // }

    function fn_select_list_partsprep_station_stations(){
      var data = {
        '_token'                   : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_list_partsprep_station_stations',
        success     : function(data){
          if ($.trim(data)){
            var html = '<option value="0">---</option>';
            for (var i = 0; i < data.length; i++) {
              html+='<option value="'+data[i]['station_sub_station']['sub_station']['id']+'" data-code="'+(data[i]['station_sub_station']['sub_station']['barcode']).toLowerCase()+'">'+data[i]['station_sub_station']['sub_station']['name']+'</option>';
             }
            // $('#txt_edit_partsprep_station_station').html(html);
          }
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });

    }

     function get_stations(){
      console.log('get_stations');
      var data = {
        '_token'                   : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'get_stations',
        success     : function(data){
          if ($.trim(data)){
            var html = '<option value="0">---</option>';
            // for (var i = 0; i < data.length; i++) {
              // html+='<option value="'+data[i]['stations']['id']+'" data-code="'+(data[i]['stations']['barcode']).toLowerCase()+'">'+data[i]['stations']['name']+'</option>';

              // html+='<option value="'+data[i]['id']+'" data-code="'+(data[i]['barcode']).toLowerCase()+'">'+data[i]['name']+'</option>';
               html+='<option value="1">Printing</option>';
               html+='<option value="2">Latch Prep</option>';
               html+='<option value="3">GP Prep</option>';
               html+='<option value="4">LB / GP Prep</option>';
               html+='<option value="5">Optical Prep</option>';
               html+='<option value="6">CS Installation</option>';
               html+='<option value="7">Acc Prep</option>';
               html+='<option value="8">CV Prep</option>';
 
             // }
            $('#txt_edit_partsprep_station_station').html(html);
          }
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });
    }

    function fn_select_list_runcard_numbers(){
      $('#dl_other_runcard_number').html('');
      var data = {
        '_token'                   : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_list_runcard_numbers',
        success     : function(data){
          var html = '';//<option value="0">---</option>';
          if ($.trim(data)){
            for (var i = 0; i < data.length; i++) {
              var runcard_number  = data[0]['runcard_number'];
              var runcard_number = "" + runcard_number;
              var pad = "000";
              runcard_number      = pad.substring(runcard_number.length) + runcard_number;
              runcard_number_str =  data[0]['runcard_po'] + '' +runcard_number;

              html+='<option>'+runcard_number_str+'</option>';
              // html+='<option value="'+data[i]['id']+'" data-code="'+data[i]['id']+'">'+runcard_number_str+'</option>';
            }
          }
          $('#dl_other_runcard_number').html(html);
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });

    }

    function fn_select_list_partsprep_station_mod(){
      var data = {
        '_token'                   : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_list_partsprep_station_mod',
        success     : function(data){
          if ($.trim(data)){
            var html = '<option value="0">---</option>';
            for (var i = 0; i < data.length; i++) {
              html+='<option value="'+data[i]['id']+'" data-code="'+(data[i]['barcode']).toLowerCase()+'">'+data[i]['name']+' '+data[i]['barcode']+'</option>';
            }
            $('#row_ng').attr('data-mod_options',html);
          }
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });

    }

    function partsprep_station_add_ng(qty,mod,is_readonly){
      var html    = '';
      html        += '<div class="row">';
      html        +=   '<div class="col">';
      html        +=     '<div class="input-group input-group-sm mb-3">';
      html        +=       '<div class="input-group-prepend w-25">';
      html        +=         '<span class="input-group-text w-100">NG qty</span>';
      html        +=       '</div>';
      html        +=       '<input '+((is_readonly)?'readonly':'')+' value="'+qty+'" type="number" class="form-control form-control-sm txt_edit_partsprep_station_ng" placeholder="0">';
      html        +=       '<label class="form-control form-control-sm" style="background-color: #eee;font-weight: normal;">MOD</label>';
      html        +=       '<select '+((is_readonly)?'disabled':'')+' class="form-control form-control-sm txt_edit_partsprep_station_mod">';
      html        +=       $('#row_ng').attr('data-mod_options');
      html        +=       '</select>';
      html        +=       '<div class="input-group-append">';
      html        +=         '<button '+((is_readonly)?'disabled':'')+' type="button" class="btn btn-danger bnt-sm btn_edit_partsprep_station_remove_ng"><i class="fa fa-times"></i></button>';
      html        +=       '</div>';
      html        +=     '</div>';
      html        +=   '</div>';
      html        += '</div>';

      $('#row_ng>div').append(html);
      $('#row_ng>div').find('.txt_edit_partsprep_station_mod:last').val(mod);
    }



    //-----------------------
    function save_approve_prod(){
      $.ajax({
        'data'      : 'txt_parts_preps_id_query=' + $("#txt_parts_preps_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() +'&status=7' + '&_token=' + '{{ csrf_token() }}',
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'update_approval_prod',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          //---
          var data_arr = [];
          data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
          data_arr['wbs_table']       = $('#txt_wbs_table').val();
          fn_select_material_details(data_arr);
          dt_lot_numbers.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    //-----------------------
    //-----------------------
    //-----------------------
    var alert_timeout = '';
    function hide_alert(){
      alert_timeout = setTimeout(function()
      {
        $('#mdl_alert').modal('hide');
      }, 2000);
    }
    function show_alert(icon,title,body,i){
      $('#mdl_alert #mdl_alert_title').html(icon+' '+title);
      $('#mdl_alert #mdl_alert_body').html(body);
      $('#mdl_alert').modal('show');

      if(i == 1){//if ok, auto hide modal
        clearTimeout(alert_timeout);
        hide_alert();
      }
    }
    
    GetCboMachine($(".selectMachine"));

    $(document).on('click', '.btn_print_ww_action', function(){
      let partsprepstationId = $(this).attr('partsprepstation-id');
      GetPartsPrepWWToPrint(partsprepstationId, 1);
    });

    let lblGenPartsPrepWW = "";
    let lblGenPartsPrepQty = "";
    let lblGenPartsPrepOpt1 = "";
    let lblGenPartsPrepOpt2 = "";

    $("#btnPrintPartsPrepWWBarcode").click(function(){
          popup = window.open();
          let content = '';
          content += '<html>';
          content += '<head>';
            content += '<title></title>';
            content += '<style type="text/css">';
              
              content += '.rotated {';
                // content += 'border: 0px solid black;';
                content += 'width: 100px;';
                content += 'position: absolute;';
                content += 'left: 17.5px;';
                content += 'top: 15px;';
              content += '}';

            content += '</style>';
          content += '</head>';
          content += '<body>';
            content += '<center>';
            content += '<div class="rotated">';
            content += '<table>';

              content += '<tr>';
                content += '<td>';
                  content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">WW - ' + lblGenPartsPrepWW + '</label><br>';
                content += '<img src="' + $("#imgGenOpt1Barcode").attr('src') + '" style="min-width: 50px; max-width: 50px;">';
                content += '</td>';
                content += '<td>';
                  content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">' + lblGenPartsPrepOpt1 + '</label>';
                content += '</td>';

                content += '<td>';
                  content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">QTY - ' + lblGenPartsPrepQty + '</label><br>';
                content += '<img src="' + $("#imgGenOpt2Barcode").attr('src') + '" style="min-width: 50px; max-width: 50px;">';
                content += '</td>';
                content += '<td>';
                  content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">' + lblGenPartsPrepOpt2 + '</label>';
                content += '</td>';
              content += '</tr>';

            content += '</table>';
            content += '</div>';
            content += '</center>';
          content += '</body>';
          content += '</html>';
          popup.document.write(content);
          popup.focus(); //required for IE
          popup.print();
          popup.close();
        });
    
    //-----------------------

    GetUserList($(".selectUser"));

    bsCustomFileInput.init();
    //Initialize Select2 Elements
    $('.select2').select2();

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });

  </script>
  @endsection
@endauth