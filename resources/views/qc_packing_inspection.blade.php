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


    <!--QC PACKING INSPECTION MODALS-->

    <div class="modal fade" id="modalAddPackingConfirmation">
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

        <!--CURRENT PO NUMBER-->
        <div class="row">
          <div class="col-sm-6">
            <label>Current PO Number</label>
            <input type=text class="form-control" id="add_packing_confirmation_po_num" name="add_packing_confirmation_po_num" readonly>
          </div>

          <div class="col-sm-6">
            <label>Device Name</label>
            <input type=text class="form-control" id="add_packing_confirmation_device_name" name="add_packing_confirmation_device_name" readonly>
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
                            <th>Lot Number</th>
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


          </form> <!--END OF FORM PACKING CONFIRMATION-->

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
            <button type="button" class="btn btn-success" id="btn_confirm_packing_confirmation"> Confirm</button>
          </div>


        </div>
      </div>
    </div>


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


  <!--MODAL FOR INSPECTION PROPER--->
  <div class="modal fade" id="modalPackingInspection">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-box-open"></i> Preliminary QC Packing Inspection</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form id="formAddPackingInspection" method="post">
        @csrf

        <div class="modal-body">
          <div class="row">
            <div class="col-sm-3">
              <label>Current PO Number</label>
              <input type="text" class="form-control" id="packing_inspection_po_num" name="packing_inspection_po_num" readonly>
            </div>

            <div class="col-sm-3">
              <label>Current Device Code</label>
              <input type="text" class="form-control" id="packing_inspection_device_code" name="packing_inspection_device_code" readonly>
            </div>

            <div class="col-sm-3">
              <label>Device Name</label>
              <input type="text" class="form-control" id="packing_inspection_device_name" name="packing_inspection_device_name" readonly>
            </div>

            <div class="col-sm-3">
              <label>Box Quantity</label>
              <input type="text" class="form-control" id="packing_inspection_box_qty" name="packing_inspection_box_qty" readonly>
            </div>

          </div>

          <br>

          <div class="row">
            <div class="col-sm-12">

              <div class="table-responsive dt-responsive">
                  <table id="tbl_inspection_lots" class="table table-bordered table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Lot/Batch Number</th>
                        <th>Quantity</th>
                        <th>Result</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>

            </div>
          </div>

          <br>

          <div class="row">
            <div class="col-sm-4">

              <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Packing Type</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_packing_type" name="add_packing_type">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>Box</option>
                    <option value='2'>Tray</option>
                    <option value='3'>Cylinder</option>
                    <option value='4'>Pallet Case</option>
                  </select>
                </div>


            </div>

            <div class="col-sm-4">

               <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Unit Condition</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_unit_condition" name="add_unit_condition">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>Terminal Mounted on Esafoam</option>
                    <option value='2'>Terminal Down</option>
                    <option value='3'>Terminal Up</option>
                  </select>
                </div>
              
            </div>

             <div class="col-sm-4">

              <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Inspection Date/Time</span>
                  </div>
                    
                  <input type="datetime-local" class="form-control form-control-sm" id="add_inspection_datetime" name="add_inspection_datetime"> 

                </div>

            </div>


          </div>

        </div>

        <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
            <button type="button" class="btn btn-success" id="btn_save_packing_inspection"> Save Inspection</button>
        </div>

      </div>
    </div>
  </div>
 
  <!--INSPECTOR ID SCAN--->

  <div class="modal fade" id="modalScan_InspectorEmpId" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan Employee ID
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_inspector_id" name="txt_search_inspector_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  </form> <!--END OF FORM ADD PACKING INSPECTION-->

  <!--FINAL QC PACKING INSPECTION-->
  <div class="modal fade" id="modalFinalQCPackingInspection">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-archive"></i> Final QC Packing Inspection</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <form id="formFinalPackingInspection" method="post">
        @csrf

        <div class="modal-body">

          <div class="row">
            <div class="col-sm-3">
              <label>Current PO Number</label>
              <input type="text" class="form-control" id="final_packing_inspection_po_num" name="final_packing_inspection_po_num" readonly>
            </div>

            <div class="col-sm-3">
              <label>Current Device Code</label>
              <input type="text" class="form-control" id="final_packing_inspection_device_code" name="final_packing_inspection_device_code" readonly>
            </div>

            <div class="col-sm-3">
              <label>Device Name</label>
              <input type="text" class="form-control" id="final_packing_inspection_device_name" name="final_packing_inspection_device_name" readonly>
            </div>

            <div class="col-sm-3">
              <label>Box Quantity</label>
              <input type="text" class="form-control" id="final_packing_inspection_box_qty" name="final_packing_inspection_box_qty" readonly>
            </div>

          </div>

          <br>

          <div class="row">
            <div class="col-sm-12">

              <div class="table-responsive dt-responsive">
                  <table id="tbl_final_inspection_lots" class="table table-bordered table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Lot/Batch Number</th>
                        <th>Quantity</th>
                        <th>Result</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>

            </div>
          </div>

          <br>

        <!--FINAL INSPECTION CHECKPOINTS-->
        <div class="row">

           <input type="hidden" id="final_packing_operator_id" name="final_packing_operator_id" readonly="">

          <div class="col-sm-4">
            <label>Packing Operator Conformance</label>
            <div class="input-group">
              <div class="input-group-prepend">
                  <button type="button" class="btn btn-primary btn-search-packing-operator" title="Click to Scan Packing Operator ID"><i class="fa fa-barcode"></i></button>
              </div>
              <input type="text" class="form-control" id="final_packing_operator" name="final_packing_operator" readonly="">
            </div>
          </div>

          <input type="hidden" id="final_packing_inspector_id" name="final_packing_inspector_id" readonly="">

          <div class="col-sm-4">
            <label>OQC Inspector Name</label>
            <div class="input-group">
              <div class="input-group-prepend">
                  <button type="button" class="btn btn-primary btn-search-packing-inspector" title="Click to Scan Packing Inspector ID"><i class="fa fa-barcode"></i></button>
              </div>
              <input type="text" class="form-control" id="final_packing_inspector_name" name="final_packing_inspector_name" readonly="">
            </div>
          </div>

           <div class="col-sm-4">
            <label>Final Inspector Date/Time</label>
            <input type="datetime-local" class="form-control" name="final_inspection_datetime" id="final_inspection_datetime">
          </div>

        </div>

        <br>

        <div class="row">
            <div class="col-sm-4">

               <div class="input-group mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Attachment of C.O.C.</span>
                  </div>
                  <select class="form-control" id="final_coc_attachment" name="final_coc_attachment">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>Yes</option>
                    <option value='2'>No</option>
                    <option value='3'>N/A</option>
                  </select>
                </div>

            </div>

            <div class="col-sm-4">

               <div class="input-group mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Result</span>
                  </div>
                  <select class="form-control" id="final_result" name="final_result">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>No Defect Found</option>
                    <option value='2'>With Defect Found / Details</option>
                  </select>
                </div>
              
            </div>

            <div class="col-sm-4">

               <div class="input-group mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                  </div>
                  
                <input type="text" class="form-control" rows="2" id="final_remarks" name="final_remarks">

                </div>
              
            </div>
        </div>


        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
          <button type="button" class="btn btn-success" id="btn_save_final_packing_inspection"> Save Inspection</button>
        </div>

      </div>
    </div>
  </div>

  <!--INSPECTOR ID SCAN--->

  <div class="modal fade" id="modalScan_FinalInspectorEmpId" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan Employee ID
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_final_inspector_id" name="txt_search_final_inspector_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

</form> <!--END OF FORM FINAL PACKING INSPECTION-->

 <!--PACKING OPERATOR CONFORMANCE SCAN--->

  <div class="modal fade" id="modalScan_PackingOperatorConformance" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan Employee ID
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_packing_operator_conformance" name="txt_search_packing_operator_conformance" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  <!--PACKING OPERATOR CONFORMANCE SCAN--->

  <div class="modal fade" id="modalScan_FinalOQCInspectorName" data-formid="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please Scan Employee ID
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_final_oqc_inspector_id" name="txt_search_final_oqc_inspector_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalViewInspectionHistory">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
           <h4 class="modal-title"><i class="fa fa-plus"></i> View Items for Inspection</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </div>

        <div class="modal-body">

          <div class="row">
            
            <div class="col-sm-3">
              <label>Current PO Number</label>
              <input type="text" class="form-control" id="view_packing_inspection_po_num" name="view_packing_inspection_po_num" readonly>
            </div>

            <div class="col-sm-3">
              <label>Current Device Code</label>
              <input type="text" class="form-control" id="view_packing_inspection_device_code" name="view_packing_inspection_device_code" readonly>
            </div>

            <div class="col-sm-3">
              <label>Device Name</label>
              <input type="text" class="form-control" id="view_packing_inspection_device_name" name="view_packing_inspection_device_name" readonly>
            </div>

            <div class="col-sm-3">
              <label>Box Quantity</label>
              <input type="text" class="form-control" id="view_packing_inspection_box_qty" name="view_packing_inspection_box_qty" readonly>
            </div>

          </div>

          <br>

          <div class="row">
            <div class="col-sm-12">

              <div class="table-responsive dt-responsive">
                  <table id="tbl_view_inspection_lots" class="table table-bordered table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Lot/Batch Number</th>
                        <th>Quantity</th>
                        <th>Result</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>

            </div>
          </div>





        </div>

        <div class="modal-footer">
           <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"> Close</button>
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

  //located in modal packing confirmation
  let dt_packing_confirmation_lots;
  let dt_packing_inspection;
  let dt_inspection_lots;
  let dt_final_inspection_lots;

  let dt_view_inspection_lots;


   $(document).ready(function () {
      bsCustomFileInput.init();


  dt_packing_inspection = $('#tbl_packing_inspection').DataTable({

   "processing" : false,
   "serverSide" : true,

    "ajax" : {
        url: "load_packing_inspection_table",
        data: function (param){
            param.po_num = $('#id_po_no').val();
        }
      },
      
      "columns":[
        { "data" : "action" },
        { "data" : "device_code" },
        { "data" : "total_lot_qty" },
        { "data" : "status" },
      ],
  });

  dt_packing_confirmation_lots = $('#tbl_packing_confirmation_lots').DataTable({

      "paging":   false,
      "ordering": false,
      "info":     false,
      "searching": false,
      "processing" : false,
      "serverSide" : true,

      "ajax" : {
        url: "load_packing_confirmation_lots",
        data: function (param){
            param.packing_lots = arrayPackingConfirmationLots;
        }
      },
      
      "columns":[
        { "data" : "action" },
        { "data" : "lot_num" },
        { "data" : "lot_qty" },
      ],
  });

    dt_inspection_lots = $('#tbl_inspection_lots').DataTable({

      "paging":   false,
      "ordering": false,
      "info":     false,
      "searching": false,
      "processing" : false,
      "serverSide" : true,

      "ajax" : {
        url: "load_packing_inspection_lots_table",
        data: function (param){
            param.packing_code = $('#packing_inspection_device_code').val();
        }
      },
      
      "columns":[
        { "data" : "lot_batch_no" },
        { "data" : "quantity" },
        { "data" : "result" },
      ],

    });

    dt_final_inspection_lots = $('#tbl_final_inspection_lots').DataTable({

     "paging":   false,
      "ordering": false,
      "info":     false,
      "searching": false,
      "processing" : false,
      "serverSide" : true,

      "ajax" : {
        url: "load_packing_inspection_lots_table",
        data: function (param){
            param.packing_code = $('#final_packing_inspection_device_code').val();
        }
      },
      
      "columns":[
        { "data" : "lot_batch_no" },
        { "data" : "quantity" },
        { "data" : "result" },
      ],

    });

    dt_view_inspection_lots = $('#tbl_view_inspection_lots').DataTable({

     "paging":   false,
      "ordering": false,
      "info":     false,
      "searching": false,
      "processing" : false,
      "serverSide" : true,

      "ajax" : {
        url: "load_packing_inspection_lots_table",
        data: function (param){
            param.packing_code = $('#view_packing_inspection_device_code').val();
        }
      },
      
      "columns":[
        { "data" : "lot_batch_no" },
        { "data" : "quantity" },
        { "data" : "result" },
      ],

    });




  });

      //SEARCH PO
    $(document).on('click','.btn_search_POno',function(e){
      $('#btn_add_packing_confirmation').attr('disabled','disabled');
      $('#id_po_no').val('');
      $('#id_device_name').val('');
      $('#id_po_qty').val('');

      dt_packing_inspection.draw();

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
          success : function(data){

            $('#btn_add_packing_confirmation').removeAttr('disabled');

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );

            dt_packing_inspection.draw();

          },error : function(data){

          }

            }); 
        }
    });


   //button functions
   $('#btn_add_packing_confirmation').click(function(e){

    let po_num = $('#id_po_no').val();
    let device_name = $('#id_device_name').val();

    $('#add_packing_confirmation_po_num').val(po_num);
    $('#add_packing_confirmation_device_name').val(device_name);

    $('#modalAddPackingConfirmation').modal('show');
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
          }
        }
    }); 

   $(document).on('keyup','#txt_search_lot_number',function(e){

      if(e.keyCode == 13)
      {
        let po_num = $('#add_packing_confirmation_po_num').val();
        let lot_number = $('#txt_search_lot_number').val();
        SearchPackingConfirmationLot(po_num, lot_number, arrayPackingConfirmationLots);
      }

   });

   //create packing information
   $('#btn_confirm_packing_confirmation').click(function(e){

      $("#formPackingConfirmation").submit();

   });

   $('#formPackingConfirmation').on('submit',function(e){

      e.preventDefault();
      SubmitPackingConfirmation(arrayPackingConfirmationLots);

   });


   //click buttons on datatable
   $(document).on('click','.btn-add-packing-inspection',function(e){

    let device_code = $(this).attr('device-code');
    let device_name = $('#id_device_name').val();

    LoadPackingInspectionDetails(device_code, device_name);

   });

   $('#btn_save_packing_inspection').click(function(e){

      $('#txt_search_inspector_id').val('');
      $('#modalScan_InspectorEmpId').attr('data-formid', '#formAddPackingInspection').modal('show');

   });

      $(document).on('keypress',function(e){
      if( ($("#modalScan_InspectorEmpId").data('bs.modal') || {})._isShown ){
        $('#txt_search_inspector_id').focus();

        if( e.keyCode == 13 && $('#txt_search_inspector_id').val() !='' && ($('#txt_search_inspector_id').val().length >= 4) ){
            $('#modalScan_InspectorEmpId').modal('hide');


            //FORM SUBMIT
            var formid = $("#modalScan_InspectorEmpId").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1)
            {
              $( formid ).submit();
            }
          }
        }
    }); 

    $('#formAddPackingInspection').on('submit',function(e){

        e.preventDefault();

        SubmitPackingInspection();

    });

    //click buttons on datatable
   $(document).on('click','.btn-final-qc-packing-inspection',function(e){

    let device_code = $(this).attr('device-code');
    let device_name = $('#id_device_name').val();

    LoadFinalPackingInspectionDetails(device_code, device_name);

   });


  $('#btn_save_final_packing_inspection').click(function(e){

      $('#txt_search_final_inspector_id').val('');
      $('#modalScan_FinalInspectorEmpId').attr('data-formid', '#formFinalPackingInspection').modal('show');

  });

  $(document).on('keypress',function(e){
      if( ($("#modalScan_FinalInspectorEmpId").data('bs.modal') || {})._isShown ){
        $('#txt_search_final_inspector_id').focus();

        if( e.keyCode == 13 && $('#txt_search_final_inspector_id').val() !='' && ($('#txt_search_final_inspector_id').val().length >= 4) ){
            $('#modalScan_FinalInspectorEmpId').modal('hide');


            //FORM SUBMIT
            var formid = $("#modalScan_FinalInspectorEmpId").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1)
            {
              $( formid ).submit();
            }
          }
        }
    }); 

  $('#formFinalPackingInspection').on('submit',function(e){

    e.preventDefault();

    SubmitFinalPackingInspection();

  });


  $(document).on('click','.btn-search-packing-operator',function(e){

    $('#txt_search_packing_operator_conformance').val('');
    $('#modalScan_PackingOperatorConformance').modal('show');

  });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_PackingOperatorConformance").data('bs.modal') || {})._isShown ){
        $('#txt_search_packing_operator_conformance').focus();

        if( e.keyCode == 13 && $('#txt_search_packing_operator_conformance').val() !='' && ($('#txt_search_packing_operator_conformance').val().length >= 4) ){
            $('#modalScan_PackingOperatorConformance').modal('hide');

            let packing_operator = $('#txt_search_packing_operator_conformance').val();

            CheckPackingOperator(packing_operator);
          }
        }
    }); 



  $(document).on('click','.btn-search-packing-inspector',function(e){

    $('#txt_search_final_oqc_inspector_id').val('');
    $('#modalScan_FinalOQCInspectorName').modal('show');

  });

  $(document).on('keypress',function(e){
      if( ($("#modalScan_FinalOQCInspectorName").data('bs.modal') || {})._isShown ){
        $('#txt_search_final_oqc_inspector_id').focus();

        if( e.keyCode == 13 && $('#txt_search_final_oqc_inspector_id').val() !='' && ($('#txt_search_final_oqc_inspector_id').val().length >= 4) ){
            $('#modalScan_FinalOQCInspectorName').modal('hide');

            let packing_inspector = $('#txt_search_final_oqc_inspector_id').val();

            CheckPackingInspector(packing_inspector);
          }
        }
    }); 



  $('#modalPackingInspection').on('hidden.bs.modal', function(){

    $('#formAddPackingInspection')[0].reset();

  });

  $('#modalFinalQCPackingInspection').on('hidden.bs.modal', function(){

    $('#formFinalPackingInspection')[0].reset();

  });


  //view
  $(document).on('click','.btn-check-inspection-history',function(e){

    let device_code = $(this).attr('device-code');
    let device_name = $('#id_device_name').val();

    LoadViewInspectionHistory(device_code, device_name);

  });



  </script>
  @endsection
@endauth