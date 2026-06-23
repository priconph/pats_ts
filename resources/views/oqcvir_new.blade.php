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
                              <th>Lot Application</th>
                              <th>Lot Numbers</th>
                              <th>Lot Qty</th>
                              <th>Inspected By</th>
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

  <div class="modal fade" id="modalStartInspection">
    <div class="modal-dialog modal-md">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Start Visual Inspection</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>

        <form id="formStartInspection" method="post">
          @csrf

        <div class="modal-body">

          <div class="row">
            <div class="col">
              <h5><strong>Are you sure you want to start the Inspection?</strong></h5>
            </div>
          </div>

          <input type="hidden" class="form-control form-control-sm" id="id_start_hidden_id" name="name_start_hidden_id" readonly>

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
                  <span class="input-group-text w-100" id="basic-addon1">Lot Application Number</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="id_start_lotapp" name="name_start_lotapp" readonly>
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

  </form> <!--END OF FORM START INSPECTION-->



<div class="modal fade" id="modalOQCVIR">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Add OQC Visual Inspection Result</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

<div class="modal-body">
  
    <div class="row">
      <div class="col-sm-12">
        <p><i class="fa fa-info-circle"></i> OQC Visual Inspection Result</p>
      </div>
    </div>

        <form id="formAddOQCVIR" method="post"> <!--FORM ADD OQC VIR-->
          @csrf

           <input type="hidden" class="form-control form-control-sm" id="id_lotapp_hidden_id" name="name_lotapp_hidden_id" readonly>

        <div class="row">
          <div class="col-sm-4 p-2">
            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Current PO Number</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_current_pono" name="name_po_no" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Lot Application Number</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_lotapp_no" name="name_lotapp_no" readonly>
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
                  <input type="number" min="0" class="form-control form-control-sm" id="id_ok_qty" name="name_ok_qty" disabled>
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
                      <button type="button" class="btn btn-primary" id="btn_search_inspector_id" data-toggle="modal" data-trigger="#mdl_inspector_name" title="Click to Scan ID"><i class="fa fa-qrcode"></i></button>
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

    <!-- Modal -->
  <div class="modal fade" id="mdl_inspector_name" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
            <input type="text" id="inspector_employee_number" name="inspector_employee_number" class="hidden_scanner_input" autocomplete="off">
          </div>
  
        </div>
      </div>
    </div>
    <!-- /.Modal -->


<div class="modal fade" id="modalBulkRuncards" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

       <div class="modal-header">
        <h4 class="modal-title">Available Runcards for Application</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

       <div class="modal-body">
        
      </div>

       <div class="modal-footer">
        
      </div>

    </div>
  </div>
</div>

<!--VIEW APPLICATION-->
<div class="modal fade" id="modalViewApplication">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
         <h4 class="modal-title">View Application</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="view_hidden_id">
        <input type="hidden" id="view_hidden_lotapp_id">

         <div class="row">
          <div class="col">

            <label><i class="fa fa-microscope"></i> Inspection Details</label>
            
            <div class="table-responsive dt-responsive">
                <table id="tbl_view_oqcvir" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Insp. Start Time</th>
                        <th>OQC Sample Size</th>
                        <th>OK/NG</th>
                        <th>Use of Template</th>
                        <th>YD Requirement</th>
                        <th>CSH Coating</th>
                        <th>Accessory Requirement</th>
                        <th>C.O.C. Requirement</th>
                        <th>Inspector</th>
                        <th>Remarks</th>
                        <th>Judgement</th>
                        <th>Inspection</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>


         <div class="row">
          <div class="col">

            <label><i class="fa fa-info-circle"></i> Application Details</label>
            
            <div class="table-responsive dt-responsive">
                <table id="tbl_view_application" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Submission</th>
                        <th>OQC Lot Application</th>
                        <th>Total Lot Quantity</th>
                        <th>Device Classification</th>
                        <th>Assembly Line</th>
                        <th>FVO</th>
                        <th>Application Date/Time</th>
                        <th>Applied By</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>



        <div class="row">
          <div class="col">

            <label><i class="fa fa-file"></i> Applied Runcards/Reworks/Defect Escalation</label>
            
            <div class="table-responsive dt-responsive">
                <table id="tbl_view_runcards" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Runcard Number</th>
                        <th>Item Type</th>
                        <th>Lot Quantity</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>


      </div>

    </div>
  </div>
</div>



@endsection

@section('js_content')
<script type="text/javascript">

  $(document).ready(function () {
    bsCustomFileInput.init();

    dt_oqcvir = $('#tbl_oqcvir').DataTable({
          "processing"    : false,
          "serverSide"  : true,
          "ajax"        : 
          {
            url: "load_new_oqcvir_table",
              data: function (param){
                param.po_num = $("#txt_search_po_number").val();
                }
          },

          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status" },
            { "data" : "lot_application" },
            { "data" : "lot_batch_no" },
            { "data" : "output_qty" },
            { "data" : "inspected_by" },
          ],

      });

     //VIEW TABLES
    dt_view_runcards = $('#tbl_view_runcards').DataTable({

        "info":     false,
        "processing" : true,
        "serverSide" : true,
         "ajax":
        {
          url: "load_oqclotapp_runcards",
          data: function(param)
          {
            param.oqclotapp_id = $('#view_hidden_lotapp_id').val();
          }
        },

        "columns":[
            { "data" : "runcard_id" },
            { "data" : "item_type" },
            { "data" : "lot_qty" },
            { "data" : "remarks" },
        ],

    });

    

     dt_view_oqcvir = $('#tbl_view_oqcvir').DataTable({

        "info":     false,
        "paging" : false,
        "searching": false,
        "processing" : true,
        "serverSide" : true,
        "ajax":
        {
          url: "load_single_oqcvir_table",
          data: function(param)
          {
            param.application_id = $('#view_hidden_id').val();
          }
        },

        "columns":[
           { "data" : "insp_stime" },
            { "data" : "sample_size" },
            { "data" : "ok_ng" },
            { "data" : "use_template" },
            { "data" : "yd_requirement" },
            { "data" : "csh_coating" },
            { "data" : "acc_req" },
            { "data" : "coc_req" }, 
            { "data" : "insp_name" },
            { "data" : "remarks" },   
            { "data" : "judgement" },
            { "data" : "insp_etime" }, 

        ],

    });

    dt_view_application = $('#tbl_view_application').DataTable({

        "info":     false,
        "paging" : false,
        "searching": false,
        "processing" : true,
        "serverSide" : true,
        "ajax":
        {
          url: "load_single_application_table",
          data: function(param)
          {
            param.application_id = $('#view_hidden_id').val();
          }
        },

        "columns":[
            { "data" : "submission" },
            { "data" : "lotapp" },
            { "data" : "total_lot_qty" },
            { "data" : "device_type" },
            { "data" : "assembly_line" },
            { "data" : "fvo" },
            { "data" : "application_datetime" },
            { "data" : "applied_by" },   

        ],

    });

  
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

    $(document).on('keypress','#txt_search_po_number',function(e){

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
          beforeSend: function(){

            $('#id_po_no').val('-- Data Loading --');
            $('#id_device_name').val('-- Data Loading --');
            $('#id_po_qty').val('-- Data Loading --');

          },
          success : function(data){

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );

          },error : function(data){

            $('#id_po_no').val('-- Data Error, Please Refresh --');
            $('#id_device_name').val('-- Data Error, Please Refresh --');
            $('#id_po_qty').val('-- Data Error, Please Refresh --');

          }

        }); 


        }
    });


$(document).on('click','.btn-start-inspection',function(){

  let oqc_lotapp_id = $(this).attr('oqc-lotapp-id');
  let device_name = $('#id_device_name').val();

  LoadStartInspectionDetails(oqc_lotapp_id,device_name);

});

//scan start inspection
$('#id_start_inspection').click(function(){

  $('#txt_employee_number_scanner_start').val('');
  $('#mdl_employee_number_scanner_start').modal('show');

});

$(document).on('keypress',function(e){
  if( ($("#mdl_employee_number_scanner_start").data('bs.modal') || {})._isShown ){
    $('#txt_employee_number_scanner_start').focus();

    if( e.keyCode == 13 && $('#txt_employee_number_scanner_start').val() !='' && ($('#txt_employee_number_scanner_start').val().length >= 4) ){
        $('#mdl_employee_number_scanner_start').modal('hide');

        e.preventDefault();
        $('#formStartInspection').submit();
      }
    }
}); 

$('#formStartInspection').submit(function(e){
  e.preventDefault();
  SubmitNewStartInspection();
});

$(document).on('click','.btn-oqc-vir',function(e){

  let oqc_lotapp_id = $(this).attr('oqc-lotapp-id');
  let device_name = $('#id_device_name').val();

  LoadOqcDetails(oqc_lotapp_id, device_name);

});

function ComputeNGQuantity(sample_size, ok_qty)
{
  let ng_qty = 0;

  if(sample_size >= ok_qty)
  {
    ng_qty = sample_size - ok_qty;
    $('#id_ng_qty').val(ng_qty);
  }
  else
  {
    toastr.error('OK Quantity cannot be greater than Sample Size!');
    $('#id_ng_qty').val(0);
  }
}

$('#id_oqc_sample_size').on('keyup',function() { 

  if($('#id_oqc_sample_size').val() != '' && $('#id_oqc_sample_size').val() > 0)
  {
    $('#id_ok_qty').removeAttr('disabled');
  }
  else
  {
    $('#id_ok_qty').prop('disabled','disabled');
  }

});

$('#id_ok_qty').on('keyup',function(){

    let sample_size = $('#id_oqc_sample_size').val();
    let ok_qty = $('#id_ok_qty').val();

    ComputeNGQuantity(sample_size, ok_qty);

});

$('#btn_search_inspector_id').click(function(){

  $('#inspector_employee_number').val('');
  $('#mdl_inspector_name').modal('show');

});

$(document).on('keypress',function(e){
  if( ($("#mdl_inspector_name").data('bs.modal') || {})._isShown ){
    $('#inspector_employee_number').focus();

    if( e.keyCode == 13 && $('#inspector_employee_number').val() !='' && ($('#inspector_employee_number').val().length >= 4) ){
        $('#mdl_inspector_name').modal('hide');

        SearchInspector($('#inspector_employee_number').val());
      }
    }
}); 

$('#id_btn_add_oqcvir').click(function(){

  $('#mdl_employee_number_scanner').modal('show');
  $('#txt_employee_number_scanner').val('');

});

$(document).on('keypress',function(e){
  if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){
    $('#txt_employee_number_scanner').focus();

    if( e.keyCode == 13 && $('#txt_employee_number_scanner').val() !='' && ($('#txt_employee_number_scanner').val().length >= 4) ){
        $('#mdl_employee_number_scanner').modal('hide');

        $('#formAddOQCVIR').submit();
      }
    }
});

$('#formAddOQCVIR').submit(function(e){
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

    if($('#id_oqc_sample_size').val() == '')
    {
      $('#id_oqc_sample_size').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_ok_qty').val() == '')
    {
      $('#id_ok_qty').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_ng_qty').val() == '')
    {
      $('#id_ng_qty').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_terminal').val() == null)
    {
      $('#id_terminal').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_yd_label').val() == null)
    {
      $('#id_yd_label').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_csh_coating').val() == null)
    {
      $('#id_csh_coating').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_accessory_requirement').val() == null)
    {
      $('#id_accessory_requirement').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_coc_requirement').val() == null)
    {
      $('#id_coc_requirement').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_oqc_inspector_name').val() == '')
    {
      $('#id_oqc_inspector_name').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_end_datetime').val() == '')
    {
      $('#id_end_datetime').addClass('is-invalid');
      ctr_error++;
    }

    if($('#id_remarks').val() == '')
    {
      $('#id_remarks').addClass('is-invalid');
      ctr_error++;
    }

    if(ctr_error > 0)
    {
      toastr.error('There are some missed fields!');
    }
    else
    {
      if($('#id_oqc_sample_size').val() < $('#id_ok_qty').val())
      {
        toastr.error('OK quantity cannot be greater than Sample Size!');
      }
      else
      {
        SubmitNewOQCVIR();
      }
    }

});

$('#modalOQCVIR').on('hidden.bs.modal',function(){
  $('#formAddOQCVIR')[0].reset();
});

$('#modalStartInspection').on('hidden.bs.modal',function(){
  $('#formStartInspection')[0].reset();
});

$(document).on('click','.view-lot-application',function(){

    let application_id = $(this).attr('oqclotapp-id');

    LoadApplicationDetails(application_id)
});

</script>
@endsection
@endauth