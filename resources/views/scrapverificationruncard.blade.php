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

  @section('title', 'Scrap Verification Runcard')

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
            <h1>Scrap Verification Runcard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Scrap Verification Runcard</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content" style="display: none;">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Search P.O.</h3>
              </div>

              <!-- Start Page Content -->
<!--               <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info" id="btn_scan_po"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="search" class="form-control" placeholder="Scan PO Number" id="txt_search_po_number" value="450196479600010" readonly>
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
              </div> -->
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

                <div class="row">
                  <div class="col">
                    <h3 class="card-title">Runcards</h3>
                  </div>
                  <div class="col text-right">
                    <button type="button" class="btn btn-sm btn-info mb-2" id="btn_add_svr"><i class="fa fa-plus"></i> Add Runcard - WHS</button>
                    <button type="button" class="btn btn-sm btn-info mb-2" id="btn_add_svr_sakidashi"><i class="fa fa-plus"></i> Add Runcard - Sakidashi (SI)</button>
                  </div>
                </div>

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
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover" id="tbl_lot_numbers" style="min-width: 600px;">
                            <thead>
                              <tr class="bg-light">
                                <th></th>
                                <th>Status</th>
                                <th>PO</th>
                                <th>Product Line</th>
                                <th>Model</th>
                                <th>Other Info</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade my-2" id="sakidashi" role="tabpanel" aria-labelledby="sakidashi-tab">
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover" id="tbl_sakidashi" style="min-width: 600px;">
                            <thead>
                              <tr class="bg-light">
                                <th></th>
                                <th>Status</th>
                                <th>PO</th>
                                <th>Product Line</th>
                                <th>Model</th>
                                <th>Other Info</th>
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
            <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> Scrap Verification Runcard <span id="lbl_svr_status" class="badge badge-success font-weight-normal">For approval</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-6">
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info" id="btn_scan_po"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="search" class="form-control" placeholder="Scan PO Number" id="txt_search_po_number" value="450196479600010">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Product Line</span>
                      </div>
                      <select class="form-control form-control-sm" id="txt_svr_pl" name="txt_svr_pl" form="frm_edit_svr" required></select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Model</span>
                      </div>
                      <input id="txt_device_name" type="text" class="form-control" placeholder="---" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Date</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_svr_date" placeholder="(auto)" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO #</span>
                      </div>
                      <input id="txt_po_number_lbl" type="text" class="form-control" placeholder="---" form="frm_edit_svr" required readonly>
                    </div>
                  </div>
                </div>
              </div><!-- col -->
            </div>
            <div class="row">
              <div class="col-6">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100">Remarks</span>
                  </div>
                  <textarea class="form-control form-control-sm" rows="2" id="txt_svr_remarks" name="txt_svr_remarks"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 offset-9 text-right">
                <form id="frm_edit_svr" method="post">
                  <button type="button" class="btn btn-sm btn-success w-100" id="btn_save_svr">Save Runcard</button>
                </form>
              </div>
            </div>
            <hr class="row">
            <div class="row">
              <div class="col-3">
                <h5>Parts</h5>
              </div>
              <div class="col-3 offset-6 text-right">
                <button type="button" class="btn btn-sm btn-info w-100" id="btn_add_station_record"><i class="fa fa-plus"></i> Add Parts</button>
              </div>
            </div>
            <div class="row">
              <div class="col">                
                <div class="table-responsive mb-3">
                  <table class="table table-sm table-bordered table-hover" id="tbl_svr_parts" style="min-width: 600px;width: 100%;">
                    <thead class="text-center">
                      <tr class="bg-light">
                        <th rowspan="2"></th>
                        <th rowspan="2">Parts Description</th>
                        <th rowspan="2">MOD</th>
                        <th colspan="3">Quantity</th>
                        <th rowspan="2">Remarks</th>
                        <th rowspan="2">Other Info</th>
                      </tr>
                      <tr class="bg-light">
                        <th>Set-up NG</th>
                        <th>Prod'n NG</th>
                        <th>Mat'l NG</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div><!-- col -->
            </div>
            <div class="row">
              <div class="col-6">
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Prepared by</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_svr_prepared_by" placeholder="(auto)" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Verified by</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_svr_verified_by" placeholder="(auto)" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Verified at</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_svr_verified_at" placeholder="(auto)" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" form="frm_edit_svr" name="txt_wbs_table" id="txt_wbs_table">
            <input type="hidden" form="frm_edit_svr" name="txt_parts_preps_id_query" id="txt_parts_preps_id_query">
            <input type="hidden" form="frm_edit_svr" name="txt_wbs_kit_issuance_id_query" id="txt_wbs_kit_issuance_id_query">
            <button type="button" class="btn btn-sm btn-success" id="btn_svr_verified">Verified <i class="fa fa-check-circle"></i></button>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_edit_svr_items_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> Parts</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="frm_svr_items">
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Select Parts</span>
                    </div>
                    <select class="form-control form-control-sm" id="txt_svr_items_partname" name="txt_svr_items_partname" required></select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">MOD</span>
                    </div>
                    <select class="form-control form-control-sm" id="txt_svr_items_mod" name="txt_svr_items_mod" required></select>
                  </div>
                </div>
              </div>
              <h5>Quantity</h5>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Set-up NG</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_svr_items_setup_ng" name="txt_svr_items_setup_ng" min="0" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Prod'n NG</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_svr_items_prod_ng" name="txt_svr_items_prod_ng" min="0" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Mat'l NG</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_svr_items_mat_ng" name="txt_svr_items_mat_ng" min="0" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100">Remarks</span>
                    </div>
                    <textarea class="form-control form-control-sm" rows="2" id="txt_svr_items_remarks" name="txt_svr_items_remarks"></textarea>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-sm btn-success" id="btn_save_svr_items">Save</button>
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
  <!-- /.content-wrapper -->
  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dt_svr, dt_setup_stations, dt_svr_parts,dt_sakidashi,dt_warehouse;
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
      fn_select_list_svr_items_mod();
      //-----------------------
      $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){
          search_po_number($(this));
        }
      });
      //-----------------------
      dt_svr      = $('#tbl_lot_numbers').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_svr_datatable",
            data: function (param){
            }
          },
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false, width: '80px' },
            { "data" : "raw_status" },
            { "data" : 'raw_wbs_details.po' },
            { "data" : "raw_pl" },
            { "data" : "raw_wbs_details.device_name" },
            { "data" : "raw_record_info" },
          ],
          order: [[1, "asc"]],
          "rowCallback": function(row,data,index ){
            // if( $(row).html().toLowerCase().indexOf('returned')>0 ){
            //   $(row).addClass('table-warning');
            // }
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
            url: "select_svr_datatable_sakidashi",
            data: function (param){
            }
          },
          bAutoWidth: false,
          paging: false,
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false, width: '80px' },
            { "data" : "raw_status" },
            { "data" : 'raw_wbs_details.po' },
            { "data" : "raw_pl" },
            { "data" : "raw_wbs_details.device_name" },
            { "data" : "raw_record_info" },
          ],
          order: [[1, "asc"]],
          "rowCallback": function(row,data,index ){
            // if( $(row).html().toLowerCase().indexOf('done verification')>0 ){
            //   $(row).addClass('table-success');
            // }
            // if( $(row).html().toLowerCase().indexOf('returned')>0 ){
            //   $(row).addClass('table-warning');
            // }
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable
      //-----------------------
      //-----------------------
      //-----------------------
      $(document).on('click','table tr',function(e){
        $(this).closest('table').find('tr').removeClass('table-active');
        $(this).addClass('table-active');
      });

      //-----------------------

      $(document).on('click','#btn_add_svr',function(e){
        var data_arr = [];
        data_arr['svr_id']          = 0;
        data_arr['wbs_table']       = 1;
        fn_select_material_details(data_arr);
        $('#kitting-tab').click();
      });

      $(document).on('click','#tbl_lot_numbers .btn_dt_open',function(e){
        var data_arr = [];
        data_arr['svr_id']          = $(this).closest('tr').find('.col_dt_id').val();
        data_arr['wbs_table']       = 1;
        fn_select_material_details(data_arr);
      });

      $(document).on('click','#btn_add_svr_sakidashi',function(e){
        var data_arr = [];
        data_arr['svr_id']          = 0;
        data_arr['wbs_table']       = 2;
        fn_select_material_details(data_arr);
        $('#sakidashi-tab').click();
      });

      $(document).on('click','#tbl_sakidashi .btn_dt_open',function(e){
        var data_arr = [];
        data_arr['svr_id']          = $(this).closest('tr').find('.col_dt_id').val();
        data_arr['wbs_table']       = 2;
        fn_select_material_details(data_arr);
      });
      //-----------------------

      $(document).on('click','#tbl_lot_numbers .btn_dt_delete, #tbl_sakidashi .btn_dt_delete',function(e){
        if( confirm("Sure to delete? You will need to scan the Supervisor's ID") ){
          var formid = '';
          if( $(this).closest('table').prop('id')=='tbl_lot_numbers' ){
            formid = 'delete_svr';
          }
          else if( $(this).closest('table').prop('id')=='tbl_sakidashi' ){
            formid = 'delete_svr_sakidashi';
          }
          $('#txt_employee_number_scanner').val('');
          $('#mdl_employee_number_scanner').attr('data-formid',formid).modal('show');
        }
      });

      $(document).on('click','#tbl_svr_parts .btn_dt_delete',function(e){
        if( confirm("Sure to delete? You will need to scan the Supervisor's ID") ){
          $('#txt_employee_number_scanner').val('');
          $('#mdl_employee_number_scanner').attr('data-formid','delete_svr_item').modal('show');
        }
      });

      //-----------------------
      $(document).on('click','#btn_save_svr',function(e){
        var alert_msg = '';
        if( $('#txt_svr_pl').val() == 0 || $('#txt_svr_pl').val() == '' ){
          alert_msg = 'Please select Product Line.';
        }
        if( $('#txt_po_number_lbl').val() == '' ){
          alert_msg = 'Please select PO Number';
        }

        if(alert_msg){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
            return;
        }

        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_svr').modal('show');
      });

      $(document).on('click','#btn_save_svr_items',function(e){
        var alert_msg = '';
        if( $('#txt_svr_items_setup_ng').val() == 0 && $('#txt_svr_items_prod_ng').val() == 0 && $('#txt_svr_items_mat_ng').val() == 0 ){
          alert_msg = 'Please enter the NG quantity.';
        }
        if( $('#txt_svr_items_mod').val() == 0 || $('#txt_svr_items_mod').val() == '' ){
          alert_msg = 'Please select MOD.';
        }
        if( $('#txt_svr_items_partname').val() == 0 || $('#txt_svr_items_partname').val() == '' ){
          alert_msg = 'Please select Parts.';
        }
        if(alert_msg){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
            return;
        }
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_svr_items').modal('show');
      });

      $(document).on('click','#btn_svr_verified',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','update_svr_verified').modal('show');
      });


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
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_svr_items').modal('show');
      });
      //-----------------------
      dt_svr_parts = $('#tbl_svr_parts').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_datatable_svr_parts",
            data: function (param){
                param.svr_id          = $("#txt_parts_preps_id_query").val();
                // param.enable_edit                  = false;//prev true
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "item_desc" },
            { "data" : "raw_mod" },
            { "data" : "ng_qty_setup" },
            { "data" : "ng_qty_prod" },
            { "data" : "ng_qty_mat" },
            { "data" : "remarks" },
            { "data" : "raw_record_info" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
          "rowCallback": function(row,data,index ){
            // if( $('#mdl_edit_material_details').attr('data-status') == 1 ){
            //   if( $(row).find('.btn_edit_partsprep_station').prop('disabled') == false ){
            //     $(row).find('.btn_edit_partsprep_station').prop('disabled',false);
            //   }
            // }
            // if( $(row).find('td:eq(4)').text() ){
            //   $(row).find('.btn_edit_partsprep_station').prop('disabled',true);
            // }
            // else{
            //   $('#btn_edit_material_details_secondary').prop('disabled',true);
            // }
          },
          "drawCallback": function(row,data,index ){
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
                case 'fn_other':
                  //other scripts here, then call the function
                break;
                default:
                  if (typeof formid !== 'undefined') {
                    eval(formid + "()");
                  }
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
                case 'fn_scan_machine_code':
                  $('#txt_edit_partsprep_station_machine option').attr('selected',false);
                  var val = $('#txt_qrcode_scanner').val().toLowerCase();
                  $('#txt_edit_partsprep_station_machine option[data-code="'+val+'"]').attr('selected',true);
                break;

                case 'fn_scan_po_number':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_search_po_number').val(val);
                  search_po_number( $('#txt_search_po_number') );
                break;

                case 'fn_scan_transfer_slip':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_scan_material_lot').val(val);
                  dt_svr.ajax.reload();
                break;

                case 'fn_scan_sakidashi_lot':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_scan_sakidashi_lot').val(val);
                  dt_sakidashi.ajax.reload();
                break;

                default:
                  if (typeof formid !== 'undefined') {
                    eval(formid + "()");
                  }
                break;
              }
            }            
          }//key
        }
      });

      //-----------------------
      $('#frm_edit_svr').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize()+'&txt_employee_number_scanner='+$("#txt_employee_number_scanner").val()+'&_token={{ csrf_token() }}',
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_svr_details',
          success     : function(data){
            // toastr.success('Material Process was succesfully saved!');
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            //---
            if( $('#txt_wbs_table').val()==1 ){
              dt_svr.ajax.reload();
            }
            else if( $('#txt_wbs_table').val()==2 ){
              dt_sakidashi.ajax.reload();
            }
            //---
            var data_arr = [];
            data_arr['svr_id']          = data['last_id'];
            fn_select_material_details(data_arr);
            //---
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
        fn_select_list_svr_items_parts();
        $('#mdl_edit_svr_items_details input').val('');
        $('#mdl_edit_svr_items_details select').val('0');
        $('#mdl_edit_svr_items_details textarea').val('');

        $('#mdl_edit_svr_items_details').modal('show');
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
      //-----------------------
      $('#btn_edit_partsprep_station_add_ng').click(function(){
        partsprep_station_add_ng('',0,0);
      })

      $(document).on('click','.btn_edit_partsprep_station_remove_ng',function(){
        $(this).closest('.row').remove();
      });

      $('#frm_svr_items').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token={{ csrf_token() }}' + '&txt_parts_preps_id_query=' + $("#txt_parts_preps_id_query").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_svr_items_details',
          success     : function(data){
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            //---
            dt_svr_parts.ajax.reload();
            $('#mdl_edit_svr_items_details').modal('hide');
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
        $('#txt_po_number_lbl').val('');
        $('#txt_device_name').val('').attr('data-device_code','');
        $('#txt_wbs_kit_issuance_id_query').val('');
        var data = {
          'po_number' : $('#txt_search_po_number').val(),
          'wbs_table' : $('#txt_wbs_table').val(),
        }
        $.ajax({
          'data'      : data,
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'select_svr_po_details',
          success     : function(data){
            if ($.trim(data)){
              if( $('#txt_wbs_table').val()==1 ){
                $('#txt_po_number_lbl').val( data[0]['po'] );
                $('#txt_device_name').val( data[0]['kit_issuance']['device_name'] ).attr('data-device_code',data[0]['kit_issuance']['device_code']);
                $('#txt_wbs_kit_issuance_id_query').val( data[0]['id'] );
              }
              else if( $('#txt_wbs_table').val()==2 ){
                $('#txt_po_number_lbl').val( data[0]['po_no'] );
                $('#txt_device_name').val( data[0]['device_name'] ).attr('data-device_code',data[0]['device_code']);
                $('#txt_wbs_kit_issuance_id_query').val( data[0]['id'] );
              }
            }
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
    }
    function reset_material_details_primary(){
      $('#txt_search_po_number').val('');
      $('#txt_svr_pl').val('0');
      $('#txt_device_name').val('');
      $('#txt_svr_remarks').val('');
      $('#txt_svr_date').val('');
      $('#txt_po_number_lbl').val('');
      $('#txt_svr_prepared_by').val('');
      $('#txt_svr_verified_by').val('');
      $('#txt_svr_verified_at').val('');

      $('#txt_wbs_table').val('');
      $('#txt_wbs_kit_issuance_id_query').val('');
      $('#txt_parts_preps_id_query').val('');

      $('#lbl_svr_status').text('').removeClass('badge-secondary badge-success');

      readonly_material_details_primary(true);
    }
    function readonly_material_details_primary(v){
      $('#txt_svr_pl').prop('disabled',v);
      $('#txt_svr_remarks').prop('readonly',v);
      $('#txt_search_po_number').closest('.row').hide();

      $('#btn_save_svr').closest('.row').hide();
      $('#btn_add_station_record').prop('disabled',v);

      $('#btn_svr_verified').hide();
    }
    //---
    function fn_select_material_details(data_arr){
      reset_material_details_primary();
      if( data_arr['svr_id']==0 ){
        $('#txt_svr_pl').prop('disabled',false);
        $('#txt_svr_remarks').prop('readonly',false);
        $('#txt_search_po_number').closest('.row').show();

        $('#btn_save_svr').closest('.row').show();
        fn_select_list_svr_pl();
      }

      $('#txt_wbs_table').val( data_arr['wbs_table'] );
      $('#txt_parts_preps_id_query').val( data_arr['svr_id'] );

      var data = {
        'svr_id'     : data_arr['svr_id'],
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_svr_details',
        success     : function(data){
          if ($.trim(data)){
            // if( data[0]['user_position'] == 4 ){//operator
            //   $('#btn_add_station_record').prop('hidden',false);//user access
            // }
            //---
            $('#txt_parts_preps_id_query').val(data[0]['id']);
            $('#txt_wbs_table').val(data[0]['wbs_table']);

            if(data[0]['status']==0){
              $('#lbl_svr_status').text('For approval').addClass('badge-secondary');
              $('#btn_add_station_record').prop('disabled',false);
              var user_position = '{{ Auth::user()->position }}';
              if(user_position == 1 || user_position == 3){// 0-N/A,1-Prod Supervisor,2-QC Supervisor,3-Material Handler,4-Operator,5-Inspector
                $('#btn_svr_verified').show();
              }
            }
            else if(data[0]['status']==1){
              $('#lbl_svr_status').text('Verified').addClass('badge-success');
            }


 
            $('#txt_svr_pl').val(data[0]['product_line']);
            $('#txt_svr_pl').html('<option>'+data[0]['pl']+'</option>');

            $('#txt_svr_remarks').val(data[0]['remarks']);
            $('#txt_svr_date').val(data[0]['created_at']);
            $('#txt_svr_prepared_by').val(data[0]['raw_created_by']);
            $('#txt_svr_verified_by').val(data[0]['raw_verified_by']);
            $('#txt_svr_verified_at').val(data[0]['verified_at']);

            $('#txt_svr_prepared_by').val(data[0]['user_created_by']['name']);
            if( data[0]['user_verified_by'] ){
              $('#txt_svr_verified_by').val(data[0]['user_verified_by']['name']);
              $('#txt_svr_verified_at').val(data[0]['verified_at']);
            }

            $('#txt_device_name').val(data[0]['device_name']);
            $('#txt_po_number_lbl').val(data[0]['po']);
            if( data[0]['wbs_table'] == 1 ){//kit
            }
            else if( data[0]['wbs_table'] == 2 ){//sakidashi
            }
          }
          dt_svr_parts.ajax.reload();
          $('#mdl_edit_material_details').modal('show');
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }













    function fn_select_list_partsprep_station_machines(){
      var data = {
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_list_partsprep_station_machines',
        success     : function(data){
          if ($.trim(data)){
            var html = '<option value="0">---</option>';
            for (var i = 0; i < data.length; i++) {
              html+='<option value="'+data[i]['id']+'" data-code="'+(data[i]['barcode']).toLowerCase()+'">'+data[i]['name']+' '+data[i]['barcode']+'</option>';
            }
            $('#txt_edit_partsprep_station_machine').html(html);
          }
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });

    }

    function fn_select_list_svr_pl(){
      var data = {
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_list_svr_pl',
        success     : function(data){
          if ($.trim(data)){
            var html = '<option value="0">---</option>';
            for (var i = 0; i < data.length; i++) {
              html+='<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
             }
            $('#txt_svr_pl').html(html);
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

    function fn_select_list_svr_items_mod(){
      var data = {
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
            $('#txt_svr_items_mod').html(html);
          }
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });
    }


    function fn_select_list_svr_items_parts(){
      $('#txt_svr_items_partname').html('');
      var data = {
        'wbs_table'                : $('#txt_wbs_table').val(),
        'po'                       : $('#txt_po_number_lbl').val(),
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_list_svr_parts',
        success     : function(data){
          if ($.trim(data)){
            var html = '<option value="0">---</option>';
            for (var i = 0; i < data.length; i++) {
              if( $('#txt_wbs_table').val()==1 ){
                html+='<option value="'+data[i]['item_desc']+'">'+data[i]['item_desc']+'</option>';
              }
              else if( $('#txt_wbs_table').val()==2 ){
                html+='<option value="'+data[i]['tbl_wbs_sakidashi_issuance_item']['item_desc']+'">'+data[i]['tbl_wbs_sakidashi_issuance_item']['item_desc']+'</option>';
              }
            }
            $('#txt_svr_items_partname').html(html);
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
    function update_svr_verified(){
      $.ajax({
        'data'      : 'txt_parts_preps_id_query=' + $("#txt_parts_preps_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}',
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'update_svr_verified',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          //---
          if( $('#txt_wbs_table').val()==1 ){
            dt_svr.ajax.reload();
          }
          else if( $('#txt_wbs_table').val()==2 ){
            dt_sakidashi.ajax.reload();
          }
          //---
          var data_arr = [];
          data_arr['svr_id']     = $('#txt_parts_preps_id_query').val();
          fn_select_material_details(data_arr);
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }


    function delete_svr(){
      delete_svr_now( $('#tbl_lot_numbers tr.table-active .col_dt_id').val() );
    }

    function delete_svr_sakidashi(){
      delete_svr_now( $('#tbl_sakidashi tr.table-active .col_dt_id').val() );
    }

    function delete_svr_now(svr_id){
      $.ajax({
        'data'      : 'txt_parts_preps_id_query=' + svr_id+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}',
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'delete_svr',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          //---
          dt_svr.ajax.reload();
          dt_sakidashi.ajax.reload();
          //---
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }

    function delete_svr_item(){
      $.ajax({
        'data'      : 'rec_id=' + $('#tbl_svr_parts tr.table-active .col_dt_id').val() +'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}',
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'delete_svr_item',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          //---
          dt_svr_parts.ajax.reload();
          //---
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
    //-----------------------
  </script>
  @endsection
@endauth