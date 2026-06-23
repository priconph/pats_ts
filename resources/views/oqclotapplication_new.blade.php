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
                <h3 class="card-title">Search P.O.</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                 <div class="col-sm-3">
                    <label>PO Number</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btn_search_POno" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="text" class="form-control" id="id_po_no" readonly="">
                    </div>
                  </div>

                  <div class="col-sm-3">
                    <label>Device Name</label>
                      <input type="text" class="form-control" id="id_device_name" readonly="">
                  </div>
                  <div class="col-sm-3">
                    <label>PO Qty</label>
                      <input type="text" class="form-control" id="id_po_qty" readonly="">
                  </div>
                </div>
              </div>
                <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->

            <div class="card card-primary">

              <div class="card-header">
                <h3 class="card-title">Lot Applications</h3>

                <div class="float-sm-right">
                  <button type="button" class="btn btn-sm btn-info" id="btnAddRuncards" data-toggle="modal" data-target="#modalAddLotAppRuncards" disabled><i class="fa fa-plus"></i> Add OQC Lot Application Runcards</button>
                </div>
              </div>

                <div class="card-body">
                  <div class="table-responsive dt-responsive">
                      <table id="tbl_oqclotapp" class="table table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <th>Lot Application</th>
                              <th>Lot Quantity</th>
                              <th>Lot Applied By</th>
                              <th>Status</th>
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

<!---------------------------MODAL SCAN PO------------------------------>
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
<!---------------------------END OF MODAL SCAN PO------------------------------>


<div class="modal fade" id="modalAddLotAppRuncards" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Runcards for OQC Lot Application</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

    <form id="formAddLotAppRuncards" method="post">
      @csrf

      <div class="modal-body">
        <div class="row">
          <div class="col-sm-4">
            <label>Current PO Number</label>
            <input type="text" class="form-control" id="current_po_num" name="current_po_num" readonly>
          </div>

          <div class="col-sm-4">
            <label>Series Name</label>
            <input type="text" class="form-control" id="current_series_name" name="current_series_name" readonly>
          </div>

          <div class="col-sm-4">
            <label>Total Lot Quantity</label>
            <input type="text" class="form-control" id="current_lot_qty" name="current_lot_qty" readonly>
          </div>
        </div>

        <br>

        <div class="row">
          <div class="col-sm-12">
            <button type="button" class="btn btn-sm btn-info btn_search_runcard_no" data-toggle="modal" data-target="#modalScan_RuncardNo"><i class="fa fa-qrcode"></i> Scan Runcard QR Code</button>

              <button type="button" class="btn btn-sm btn-primary btn_search_rework_no" data-toggle="modal" data-target="#modalScan_ReworkNo"><i class="fa fa-qrcode"></i> Scan Rework/DE QR Code</button>

              <div class="float-sm-right">
                 <button type="button" class="btn btn-sm btn-success" id="btnBulkRuncards" data-toggle="modal" data-target="#modalBulkRuncards"><i class="fa fa-list"></i> Add Runcards/Rework by List</button>
              </div>
          </div>
        </div>

        <br>

        <div class="row">
          <div class="col-sm-12">

            <!--table for runcards-->
            <div class="table-responsive dt-responsive">
                <table id="tbl_oqclotapp_runcards" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Runcard No</th>
                        <th>Type</th>
                        <th>Output Quantity</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>

        <br>

        <div class="row">
          <div class="col-sm-4">

          </div>

          <div class="col-sm-4">
            <div class="float-sm-right">
              <p><strong>Total Output Qty: </strong></p>
            </div>
          </div>

          <div class="col-sm-4">
            <input type="text" class="form-control" id="total_lot_qty" name="total_lot_qty" value="0" readonly>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm mr-auto" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-sm" id="btn_submit_oqclotapp_runcards" disabled>Submit</button>
        
      </div>

    </div>
  </div>
</div>

<!---------------------------MODAL SCAN EMPLOYEE ID TO SAVE RUNCARDS------------------------------>
  <div class="modal fade" id="modalScan_saveRuncards" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_employee_id" name="txt_employee_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

</form>
<!---------------------------END OF MODAL SCAN EMPLOYEE ID TO SAVE RUNCARDS------------------------------>

<!---------------------------MODAL SCAN RUNCARD------------------------------>
  <div class="modal fade" id="modalScan_RuncardNo" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan the Runcard Number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_runcard_number" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>
<!---------------------------END OF MODAL SCAN RUNCARD------------------------------>

<!---------------------------MODAL SCAN REWORK------------------------------>
  <div class="modal fade" id="modalScan_ReworkNo" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan the Rework/DE Number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_rework_number" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>
<!---------------------------END OF MODAL SCAN REWORK------------------------------>



<div class="modal fade" id="modalOqcLotApplication" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-edit"></i> OQC Lot Application</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <form id="formOqcLotApplication" method="post">
      @csrf

      <div class="modal-body">

        <div class="row">
          <div class="col-sm-12">
            <div class="float-sm-right">
               <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalViewRuncards" id="btnViewRuncards"><i class="fa fa-list"></i> View Runcards</button>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <label>OQC Lot Application ID</label>
            <input type="text" class="form-control" id="lotapp_id" name="lotapp_id" readonly>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <label>PO Number</label>
            <input type="text" class="form-control" id="lotapp_po_num" name="lotapp_po_num" readonly>
          </div>

           <div class="col-sm-6">
            <label>Series Name</label>
            <input type="text" class="form-control" id="lotapp_series_name" name="lotapp_series_name" readonly>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <label>Total Lot Quantity</label>
            <input type="text" class="form-control" id="lotapp_lot_qty" name="lotapp_lot_qty" readonly>
          </div>

           <div class="col-sm-6">
            <label>Application Date/Time</label>
            <input type="datetime-local" class="form-control" id="lotapp_datetime" name="lotapp_datetime">
          </div>
        </div>

        <hr>

        <div class="row">
          <div class="col-sm-6">
            <label>Device Classification</label>
            <select class="form-control" id="lotapp_device_type" name="lotapp_device_type">
              <option selected disabled>-- Select One --</option>
              <option value="1">Automotive</option>
              <option value="2">Regular</option>
            </select>
          </div>

          <div class="col-sm-6">
            <label>Assembly Line</label>
            <select class="form-control sel-assembly-lines" id="lotapp_assembly_line" name="lotapp_assembly_line">
              <option selected disabled>-- Select One --</option>
            </select>
          </div>
        </div>

        <br>

        <div class="row">
          <div class="col-sm-12">
            <button type="button" class="btn btn-sm btn-primary" id="btnAddFvo"><i class="fa fa-barcode"></i> Add Final Visual Operator</button>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
              <!--table for fvo-->
            <div class="table-responsive dt-responsive">
                <table id="tbl_oqclotapp_fvo" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Employee ID</th>
                        <th>Full Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>

      </div>

      <div class="modal-footer">
         <button type="button" class="btn btn-danger btn-sm mr-auto" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-sm" id="btn_submit_oqclotapp">Submit</button>
      </div>

    </div>
  </div>  
</div>

<!---------------------------ADD FVO INSPECTOR------------>
  <div class="modal fade" id="modalScan_SaveLotApp" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_applied_by_id" name="txt_applied_by_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

</form>

<!---------------------------ADD FVO INSPECTOR------------>
  <div class="modal fade" id="modalScan_FVO" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_fvo_id" name="txt_fvo_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>


<div class="modal fade" id="modalViewRuncards">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
         <h4 class="modal-title"><i class="fa fa-list"></i> Runcards For Application</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <div class="modal-body">

        <div class="row">
          <div class="col-sm-4">
            <label>PO Number</label>
            <input type="text" class="form-control" id="editRuncardPoNo" name="editRuncardPoNo" readonly>
          </div>

          <div class="col-sm-4">
            <label>OQC Lot Application</label>
            <input type="text" class="form-control" id="editRuncardOqcLotApp" name="editRuncardOqcLotApp" readonly>
          </div>

          <div class="col-sm-4">
            <label>Total Lot Quantity</label>
            <input type="text" class="form-control" id="editRuncardLotQty" name="editRuncardLotQty" readonly>
          </div>
        </div>

        <hr>

        <div class="row">
          <div class="col-sm-12">
            <!--table for runcards-->
            <div class="table-responsive dt-responsive">
                <label>Current Runcards for Application</label>
                <table id="tbl_oqclotapp_view_runcards" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Runcard</th>
                        <th>Type</th>
                        <th>Lot Quantity</th>
                        <th>Remarks</th>
                        <th>Created At</th>
                        <th>Edit Status</th>
                        <th>Edit Action</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>

        <hr>

        <label>Additional Runcards to be Added</label>

        <div class="row">
          <div class="col-sm-12">
            <button type="button" class="btn btn-sm btn-info btn_search_addtional_runcard_no" data-toggle="modal" data-target="#modalScan_AdditionalRuncardNo"><i class="fa fa-qrcode"></i> Scan Runcard QR Code</button>

            <button type="button" class="btn btn-sm btn-primary btn_search_addtional_rework_no" data-toggle="modal" data-target="#modalScan_AdditionalReworkNo"><i class="fa fa-qrcode"></i> Scan Rework/DE QR Code</button>
          </div>
        </div>

        <br>

        <div class="row">
          <div class="col-sm-12">
            <!--table for runcards-->
            <div class="table-responsive dt-responsive">
                
                <table id="tbl_oqclotapp_additional_runcards" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Runcard No</th>
                        <th>Output Quantity</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>

      </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm mr-auto" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success btn-sm" id="btn_save_edit_runcards">Submit</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalScan_SaveEditRuncards" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_edit_runcard_by_id" name="txt_edit_runcard_by_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
 </div>


<!---------------------------MODAL SCAN RUNCARD------------------------------>
  <div class="modal fade" id="modalScan_AdditionalRuncardNo" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan the Runcard Number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_additional_runcard_number" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>
<!---------------------------END OF MODAL SCAN RUNCARD------------------------------>

<!---------------------------MODAL SCAN REWORK------------------------------>
<div class="modal fade" id="modalScan_AdditionalReworkNo" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan the Rework/DE Number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_additional_rework_number" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
 </div>
<!---------------------------END OF MODAL SCAN REWORK------------------------------>



<!--MODAL FOR OQC LOT APP STICKER--->
  <div class="modal fade" id="modal_LotApp_QRcode">
    <div class="modal-dialog modal-md">
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
                  <img id="img_barcode_PO" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_no_PO"></label> <br>
                  <label id="lbl_device_name_PO"></label> <br>
                </div>

                <!-- Lot/batch# 1-->
                <div class="col-sm-6">
                  <img id="img_barcode_lotno1" style="max-width: 200px;">
                  <br>
                  <label id="lbl_lot_batch_no"></label> <br>
                  <label id="lbl_lot_qty"></label>
                </div>


              </div>
            </center>
        </div>
        <div class="modal-footer">
            <button type="button" id="btn_print_barcode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


<!----------------------OTHER SUBMISSION----------------------------------->
  <div class="modal fade" id="modalLotAppOtherSubmission">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

 

        <div class="modal-header">
           <h4 class="modal-title"><i class="fa fa-tags"></i> OQC Lot Application: Other Submission</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
          <div class="row">

            <div class="col-sm-12">

            <p><i class="fa fa-list"></i> Submissions</p>

             <table id="tbl_oqclotapp_submissions" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                  <thead>
                    <tr>
                      <th>Submission</th>
                      <th>Application Date/Time</th>
                      <th>Final Visual Operators</th>
                    </tr>
                  </thead>
              </table> 

            </div>
          </div>

        <form id="formOtherSubmission" method="post">
        @csrf

            <div class="row">
              <div class="col-sm-12">
                <div class="float-sm-right">
                   <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalViewRuncardsSub" id="btnViewRuncardsSub"><i class="fa fa-list"></i> View Runcards</button>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-8">
                <label>OQC Lot Application ID</label>
                <input type="text" class="form-control" id="lotapp_id_sub" name="lotapp_id_sub" readonly>
              </div>

              <div class="col-sm-4">
                <label>Submission</label>
                <input type="text" class="form-control" id="lotapp_submission" name="lotapp_submission" readonly>
              </div>

            </div>

            <div class="row">
              <div class="col-sm-6">
                <label>PO Number</label>
                <input type="text" class="form-control" id="lotapp_po_num_sub" name="lotapp_po_num_sub" readonly>
              </div>

               <div class="col-sm-6">
                <label>Series Name</label>
                <input type="text" class="form-control" id="lotapp_series_name_sub" name="lotapp_series_name_sub" readonly>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <label>Total Lot Quantity</label>
                <input type="text" class="form-control" id="lotapp_lot_qty_sub" name="lotapp_lot_qty_sub" readonly>
              </div>

               <div class="col-sm-6">
                <label>Application Date/Time</label>
                <input type="datetime-local" class="form-control" id="lotapp_datetime_sub" name="lotapp_datetime_sub">
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="col-sm-6">
                <label>Device Classification</label>
                <select class="form-control" id="lotapp_device_type_sub" name="lotapp_device_type_sub">
                  <option selected disabled>-- Select One --</option>
                  <option value="1">Automotive</option>
                  <option value="2">Regular</option>
                </select>
              </div>

              <div class="col-sm-6">
                <label>Assembly Line</label>
                <select class="form-control sel-assembly-lines" id="lotapp_assembly_line_sub" name="lotapp_assembly_line_sub">
                  <option selected disabled>-- Select One --</option>
                </select>
              </div>
            </div>

            <br>

            <div class="row">
              <div class="col-sm-12">
                <button type="button" class="btn btn-sm btn-primary" id="btnAddFvoSub"><i class="fa fa-barcode"></i> Add Final Visual Operator</button>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                  <!--table for fvo-->
                <div class="table-responsive dt-responsive">
                    <table id="tbl_oqclotapp_fvo_sub" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                        <thead>
                          <tr>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                    </table> 
                </div>

              </div>
            </div>

        </div>




        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm mr-auto" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success btn-sm" id="btn_submit_other_submission">Submit</button>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="modalScan_SaveLotAppSub" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_applied_by_id_sub" name="txt_applied_by_id_sub" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  </form> <!--END OF FORM OTHER SUBMISSION--->

<!---------------------------ADD FVO INSPECTOR - OTHER SUBMISSION------------>
  <div class="modal fade" id="modalScan_FVO_sub" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_fvo_sub_id" name="txt_fvo_sub_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>


<!--ADD RUNCARDS/REWORKS ON OTHER SUBMISSIONS--->
<div class="modal fade" id="modalViewRuncardsSub">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
         <h4 class="modal-title"><i class="fa fa-list"></i> Runcards For Application (Other Submissions)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <div class="modal-body">

        <div class="row">
          <div class="col-sm-4">
            <label>PO Number</label>
            <input type="text" class="form-control" id="editRuncardPoNoSub" name="editRuncardPoNoSub" readonly>
          </div>

          <div class="col-sm-4">
            <label>OQC Lot Application</label>
            <input type="text" class="form-control" id="editRuncardOqcLotAppSub" name="editRuncardOqcLotAppSub" readonly>
          </div>

          <div class="col-sm-4">
            <label>Total Lot Quantity</label>
            <input type="text" class="form-control" id="editRuncardLotQtySub" name="editRuncardLotQtySub" readonly>
          </div>
        </div>

        <hr>

        <div class="row">
          <div class="col-sm-12">
            <!--table for runcards-->
            <div class="table-responsive dt-responsive">
                <label>Current Runcards for Application</label>
                <table id="tbl_oqclotapp_view_runcards_sub" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Runcard</th>
                        <th>Type</th>
                        <th>Lot Quantity</th>
                        <th>Remarks</th>
                        <th>From OQC Submission</th>
                        <th>Created At</th>
                        <th>Edit Status</th>
                        <th>Edit Action</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>

        <hr>

        <label>Additional Runcards to be Added</label>

        <div class="row">
          <div class="col-sm-12">
            <button type="button" class="btn btn-sm btn-info btn_search_addtional_runcard_no_sub" data-toggle="modal" data-target="#modalScan_AdditionalRuncardNoSub"><i class="fa fa-qrcode"></i> Scan Runcard QR Code</button>

            <button type="button" class="btn btn-sm btn-primary btn_search_addtional_rework_no_sub" data-toggle="modal" data-target="#modalScan_AdditionalReworkNoSub"><i class="fa fa-qrcode"></i> Scan Rework/DE QR Code</button>
          </div>
        </div>

        <br>

        <div class="row">
          <div class="col-sm-12">
            <!--table for runcards-->
            <div class="table-responsive dt-responsive">
                
                <table id="tbl_oqclotapp_additional_runcards_sub" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Runcard No</th>
                        <th>Output Quantity</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                </table> 
            </div>

          </div>
        </div>

      </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm mr-auto" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success btn-sm" id="btn_save_edit_runcards_sub">Submit</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalScan_SaveEditRuncardsSub" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_edit_runcard_by_id_sub" name="txt_edit_runcard_by_id_sub" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
 </div>



<!---------------------------MODAL SCAN RUNCARD------------------------------>
  <div class="modal fade" id="modalScan_AdditionalRuncardNoSub" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan the Runcard Number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_additional_runcard_number_sub" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>
<!---------------------------END OF MODAL SCAN RUNCARD------------------------------>

<!---------------------------MODAL SCAN REWORK------------------------------>
  <div class="modal fade" id="modalScan_AdditionalReworkNoSub" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan the Rework/DE Number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_additional_rework_number_sub" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>
<!---------------------------END OF MODAL SCAN REWORK------------------------------>


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

        <div class="row">
          <div class="col">
             <p><i class="fa fa-info-circle"></i>&nbsp;Select Multiple Runcards/Reworks for the Application</p>
          </div>
        </div>



         <div class="row">
          <div class="col">

            <label><i class="fa fa-file"></i> AVAILABLE RUNCARDS FOR APPLICATION</label>
            
            <div class="table-responsive dt-responsive">
                <table id="tbl_bulk_runcards" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Runcard</th>
                        <th>Lot Quantity</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                </table> 
            </div>


          </div>
        </div>

        <hr>

         <div class="row">
          <div class="col">

            <label><i class="fa fa-file"></i> AVAILABLE REWORK/DE FOR APPLICATION</label>
            
            <div class="table-responsive dt-responsive">
                <table id="tbl_bulk_rework" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 80%;">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Runcard</th>
                        <th>Lot Quantity</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                </table> 
            </div>


          </div>
        </div>


       
      </div>

      <div class="modal-footer">
         <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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

  let dt_oqclotapp;

  //from modal of add oqc lot app runcards
  let dt_oqclotapp_runcards;
  let arrRuncards = [];

  let arrEditRemoveRuncards = [];
  let arrEditAddRuncards = [];

  //table of fvo application
  let dt_oqclotapp_fvo;
  let arrFvo = [];

  let dt_oqclotapp_view_runcards;
  let dt_oqclotapp_additional_runcards;

  let dt_oqclotapp_submissions;
  let dt_oqclotapp_fvo_sub;
  let arrFvoSub = [];

  let dt_oqclotapp_view_runcards_sub;
  let dt_oqclotapp_additional_runcards_sub;

  let arrEditRemoveRuncardsSub = [];
  let arrEditAddRuncardsSub = [];

  //bulk
  let dt_bulk_runcards;
  let dt_bulk_rework;

  //view
  let dt_view_runcards;
  let dt_view_application;

  $(document).ready(function () {
    bsCustomFileInput.init();

    GetAssemblyLines($(".sel-assembly-lines"));

    dt_oqclotapp = $('#tbl_oqclotapp').DataTable({
        
        "processing" : true,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqclotapp_new_table",
          data: function(param)
          {
            param.po_num = $('#id_po_no').val();
          }
        },

        "columns":[
            { "data" : "action", orderable:false, searchable:false  },
            { "data" : "oqclotapp_id" },
            { "data" : "total_lot_qty" },
            { "data" : "applied_by" },
            { "data" : "status" },
        ],

    });


    //runcards from modal add application
    dt_oqclotapp_runcards = $('#tbl_oqclotapp_runcards').DataTable({

        "ordering": false,
        "pageLength" : 5,
        "info":     false,
        "searching": false,
        "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqclotapp_runcards_from_array",
          data: function(param)
          {
            param.arrRuncards = arrRuncards;
          }
        },

        "columns":[
            { "data" : "runcard_no" },
            { "data" : "type" },
            { "data" : "output_qty" },
            { "data" : "action", orderable:false, searchable:false  },
        ],
    });

     //runcards from modal add application
    dt_oqclotapp_fvo = $('#tbl_oqclotapp_fvo').DataTable({

        "paging" : false,
        "info":     false,
        "searching": false,
        "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqclotapp_fvo_from_array",
          data: function(param)
          {
            param.arrFvo = arrFvo;
          }
        },

        "columns":[
            { "data" : "fvo_employee_id" },
            { "data" : "fvo_name" },
            { "data" : "action", orderable:false, searchable:false  },
        ],
    });

    dt_oqclotapp_view_runcards = $('#tbl_oqclotapp_view_runcards').DataTable({

        "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqclotapp_runcards",
          data: function(param)
          {
            param.oqclotapp_id = $('#lotapp_id').val();
            param.array_remove_runcards = arrEditRemoveRuncards;
          }
        },

        "columns":[
            { "data" : "runcard_id" },
            { "data" : "item_type" },
            { "data" : "lot_qty" },
            { "data" : "remarks" },
            { "data" : "created_at" },
            { "data" : "edit_status" },
            { "data" : "edit_action" },
        ],
    });

    dt_oqclotapp_additional_runcards = $('#tbl_oqclotapp_additional_runcards').DataTable({

      "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqclotapp_additional_runcards",
          data: function(param)
          {
            param.array_add_runcards = arrEditAddRuncards;
          }
        },

        "columns":[
            { "data" : "runcard_no" },
            { "data" : "output_qty" },
            { "data" : "action", orderable:false, searchable:false  },

        ],
    });

    dt_bulk_runcards = $('#tbl_bulk_runcards').DataTable({

       "processing" : true,
        "serverSide" : true,
        "ajax":
        {
          url: "load_bulk_runcards",
          data: function(param)
          {
            param.po_num = $('#current_po_num').val();
            param.array_runcards = arrRuncards;
          }
        },

        "columns":[
            { "data" : "action", orderable:false, searchable:false  },
            { "data" : "runcard_no" },
            { "data" : "lot_qty" },
            { "data" : "remarks" },
        ],

         "columnDefs": [
            { "width": "10px", "targets": 0 }
          ]

    });

     dt_bulk_rework = $('#tbl_bulk_rework').DataTable({

       "processing" : true,
        "serverSide" : true,
        "ajax":
        {
          url: "load_bulk_reworks",
          data: function(param)
          {
            param.po_num = $('#current_po_num').val();
            param.array_runcards = arrRuncards;
          }
        },

        "columns":[
            { "data" : "action", orderable:false, searchable:false  },
            { "data" : "runcard_no" },
            { "data" : "lot_qty" },
            { "data" : "remarks" },
        ],

         "columnDefs": [
            { "width": "10px", "targets": 0 }
          ]

    });


    //-----------------------------2ND SUBMISSION TABLES-----------------------------

    dt_oqclotapp_submissions = $('#tbl_oqclotapp_submissions').DataTable({

        "paging" : false,
        "info":     false,
        "searching": false,
        "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqlotapp_history",
          data: function(param)
          {
            param.oqclotapp_id = $('#lotapp_id_sub').val();
          }
        },

        "columns":[
            { "data" : "submission" },
            { "data" : "application_datetime" },
            { "data" : "fvo" },
        ],

    });

    dt_oqclotapp_fvo_sub = $('#tbl_oqclotapp_fvo_sub').DataTable({

        "paging" : false,
        "info":     false,
        "searching": false,
        "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqclotapp_fvo_from_array",
          data: function(param)
          {
            param.arrFvo = arrFvoSub;
          }
        },

        "columns":[
            { "data" : "fvo_employee_id" },
            { "data" : "fvo_name" },
            { "data" : "action_sub", orderable:false, searchable:false  },
        ],
    });

    dt_oqclotapp_view_runcards_sub = $('#tbl_oqclotapp_view_runcards_sub').DataTable({

        "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqclotapp_runcards",
          data: function(param)
          {
            param.oqclotapp_id = $('#lotapp_id_sub').val();
            param.array_remove_runcards = arrEditRemoveRuncardsSub;
          }
        },

        "columns":[
            { "data" : "runcard_id" },
            { "data" : "item_type" },
            { "data" : "lot_qty" },
            { "data" : "remarks" },
            { "data" : "from_submission" },
            { "data" : "created_at" },
            { "data" : "edit_status_sub" },
            { "data" : "edit_action_sub" },
        ],
    });

    dt_oqclotapp_additional_runcards_sub = $('#tbl_oqclotapp_additional_runcards_sub').DataTable({

      "processing" : false,
        "serverSide" : true,
        "ajax":
        {
          url: "load_oqclotapp_additional_runcards",
          data: function(param)
          {
            param.array_add_runcards = arrEditAddRuncardsSub;
          }
        },

        "columns":[
            { "data" : "runcard_no" },
            { "data" : "output_qty" },
            { "data" : "action_sub", orderable:false, searchable:false  },

        ],
    });



    //------------------------end of 2ND SUBMISSION TABLES-----------------------------


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

  $('#btnViewRuncards').click(function(e){
    dt_oqclotapp_view_runcards.draw();
  })

  //SEARCH PO
    $(document).on('click','.btn_search_POno',function(e){
      $('#txt_search_po_number').val('');
      $('#modalScan_PO').attr('data-formid', '').modal('show');

      $('#id_po_no').val('');
      $('#id_device_name').val('');
      $('#id_po_qty').val('');
      $('#btnAddRuncards').prop('disabled','disabled');
      dt_oqclotapp.draw();
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
          beforeSend: function(){

            $('#id_po_no').val('-- Data Loading --');
            $('#id_device_name').val('-- Data Loading --');
            $('#id_po_qty').val('-- Data Loading --');

          },
          success : function(data){

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );

            $('#btnAddRuncards').removeAttr('disabled');
            dt_oqclotapp.draw();

          },error : function(data){

            $('#id_po_no').val('-- Data Error, Please Refresh --');
            $('#id_device_name').val('-- Data Error, Please Refresh --');
            $('#id_po_qty').val('-- Data Error, Please Refresh --');

          }

            }); 
        }
    });

    $('#btnAddRuncards').click(function(e){

        let current_po = $('#id_po_no').val();
        let current_series = $('#id_device_name').val();
        

        LoadDeviceDetails(current_po, current_series);
    });

      //SEARCH RUNCARD
    $(document).on('click','.btn_search_runcard_no',function(e){
      $('#txt_runcard_number').val('');
      $('#modalScan_RuncardNo').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_RuncardNo").data('bs.modal') || {})._isShown ){
        $('#txt_runcard_number').focus();

        if( e.keyCode == 13 && $('#txt_runcard_number').val() !='' && ($('#txt_runcard_number').val().length >= 4) ){

            $('#modalScan_RuncardNo').modal('hide');

            let runcard_no = $('#txt_runcard_number').val();
            let po_num = $('#current_po_num').val(); //pwede na to or yung text current po num
            let per_lot_qty = $('#current_lot_qty').val(); 

            ValidateOqcRuncard(arrRuncards, po_num, runcard_no, per_lot_qty);

            
          }
        }
    }); 

        //SEARCH REWORK
    $(document).on('click','.btn_search_rework_no',function(e){
      $('#txt_rework_number').val('');
      $('#modalScan_ReworkNo').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_ReworkNo").data('bs.modal') || {})._isShown ){
        $('#txt_rework_number').focus();

        if( e.keyCode == 13 && $('#txt_rework_number').val() !='' && ($('#txt_rework_number').val().length >= 4) ){

            $('#modalScan_ReworkNo').modal('hide');

            let rework_no = $('#txt_rework_number').val();
            let po_num = $('#current_po_num').val(); //pwede na to or yung text current po num
            let per_lot_qty = $('#current_lot_qty').val(); 

            ValidateOqcRework(arrRuncards, po_num, rework_no, per_lot_qty);

            
          }
        }
    }); 


  //remove from array
  $(document).on('click','.btn-remove-array-runcard',function(e){

    let runcard_id = $(this).attr('runcard-id');

     RemoveRuncard(arrRuncards,runcard_id);

  });


      //SEARCH RUNCARD
  $('#btn_submit_oqclotapp_runcards').click(function(e)
  {
      $('#txt_employee_id').val('');
      $('#modalScan_saveRuncards').modal('show');
  });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_saveRuncards").data('bs.modal') || {})._isShown ){
        $('#txt_employee_id').focus();

        if( e.keyCode == 13 && $('#txt_employee_id').val() !='' && ($('#txt_employee_id').val().length >= 4) ){

            $('#modalScan_saveRuncards').modal('hide');

            SubmitOqcRuncards(arrRuncards);
            
          }
        }
    }); 


  $(document).on('click','.btn-oqclotapp',function(e){

    let series_name = $('#id_device_name').val();
    let oqclotapp_id = $(this).attr('oqclotapp-id');
    let current_po = $('#id_po_no').val();

    RetrieveLotApplicationDetails(oqclotapp_id,series_name);


    LoadRuncardDeviceDetails(current_po, series_name);
  });

//SEARCH FVO-------------------------
  $('#btnAddFvo').click(function(e)
  {
      $('#txt_fvo_id').val('');
      $('#modalScan_FVO').modal('show');
  });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_FVO").data('bs.modal') || {})._isShown ){
        $('#txt_fvo_id').focus();

        if( e.keyCode == 13 && $('#txt_fvo_id').val() !='' && ($('#txt_fvo_id').val().length >= 4) ){

            $('#modalScan_FVO').modal('hide');
            let fvo_id = $('#txt_fvo_id').val();

            SearchFvo(fvo_id, arrFvo);
            
          }
        }
    }); 

  //remove from array
  $(document).on('click','.btn-remove-array-fvo',function(e){

    let fvo_user_id = $(this).attr('fvo-user-id');

     RemoveFvo(arrFvo,fvo_user_id);

  });


  //submit FVO
  $('#btn_submit_oqclotapp').click(function(e){
      $('#txt_applied_by_id').val('');
      $('#modalScan_SaveLotApp').modal('show');
  });

  $(document).on('keypress',function(e){
  if( ($("#modalScan_SaveLotApp").data('bs.modal') || {})._isShown ){
    $('#txt_applied_by_id').focus();

    if( e.keyCode == 13 && $('#txt_applied_by_id').val() !='' && ($('#txt_applied_by_id').val().length >= 4) ){

        $('#modalScan_SaveLotApp').modal('hide');

        CheckOqcLotApplication(arrFvo);
        
      }
    }
  }); 

$('#formOqcLotApplication').submit(function(e){
  e.preventDefault();
  SubmitOqcLotApplication(arrFvo);
});

$(document).on('click','.btn-oqclotapp-history', function(e){

  let oqclotapp_id = $(this).attr('oqclotapp-id');
  let series_name = $('#id_device_name').val();

  ViewLotAppHistory(oqclotapp_id, series_name);

});


//- Print Barcode
  $(document).on('click','.btn-generate-sticker',function(e){

      let oqc_lotapp_id = $(this).attr('oqclotapp-id');
      let device_name = $('#id_device_name').val();

      LoadStickerDetails(oqc_lotapp_id, device_name);
  });

  $('#btn_print_barcode').click(function(e){

      let image_ponum = $('#img_barcode_PO').attr('src');
      let image_lotapp = $('#img_barcode_lotno1').attr('src');

      let po_num =  $('#lbl_po_no_PO').text();
      let device_name = $('#lbl_device_name_PO').text();
      let oqclotapp_id = $('#lbl_lot_batch_no').text();
      let lot_qty = $('#lbl_lot_qty').text();

      GenerateSticker2(image_ponum, image_lotapp, po_num, device_name, oqclotapp_id, lot_qty);

  });

  $('#modalAddLotAppRuncards').on('hidden.bs.modal',function(){

    arrRuncards = [];
    dt_oqclotapp_runcards.draw();
    DisableSubmitRuncardApplication(arrRuncards);

  });

  //---------------------------------EDIT RUNCARD DETAILS--------------------------------
        //SEARCH RUNCARD
    $(document).on('click','.btn_search_addtional_runcard_no',function(e){
      $('#txt_additional_runcard_number').val('');
      $('#modalScan_AdditionalRuncardNo').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_AdditionalRuncardNo").data('bs.modal') || {})._isShown ){
        $('#txt_additional_runcard_number').focus();

        if( e.keyCode == 13 && $('#txt_additional_runcard_number').val() !='' && ($('#txt_additional_runcard_number').val().length >= 4) ){

            $('#modalScan_AdditionalRuncardNo').modal('hide');

            let runcard_no = $('#txt_additional_runcard_number').val();
            let po_num = $('#editRuncardPoNo').val(); //pwede na to or yung text current po n0
            let oqc_lotapp_id = $('#editRuncardOqcLotApp').val();
            let lot_qty = $('#editRuncardLotQty').val();
            
            EditAddRuncard(runcard_no, po_num, oqc_lotapp_id, lot_qty, arrEditAddRuncards);
          }
        }
    }); 

         //SEARCH RUNCARD
    $(document).on('click','.btn_search_addtional_rework_no',function(e){
      $('#txt_additional_rework_number').val('');
      $('#modalScan_AdditionalReworkNo').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_AdditionalReworkNo").data('bs.modal') || {})._isShown ){
        $('#txt_additional_rework_number').focus();

        if( e.keyCode == 13 && $('#txt_additional_rework_number').val() !='' && ($('#txt_additional_rework_number').val().length >= 4) ){

            $('#modalScan_AdditionalReworkNo').modal('hide');

            let rework_no = $('#txt_additional_rework_number').val();
            let po_num = $('#editRuncardPoNo').val(); //pwede na to or yung text current po n0
            let oqc_lotapp_id = $('#editRuncardOqcLotApp').val();
            let lot_qty = $('#editRuncardLotQty').val();
            
            EditAddRework(rework_no, po_num, oqc_lotapp_id, lot_qty, arrEditAddRuncards);
          }
        }
    }); 



  //****ACTUALLY ADDS TO ARRAY, FOR REMOVAL IF SUBMITTED***
  $(document).on('click','.btn-edit-remove-runcard',function(e){

    let runcard_id = $(this).attr('runcard-id');

    EditRemoveRuncard(arrEditRemoveRuncards,runcard_id);

  });

  $(document).on('click','.btn-cancel-remove-runcard',function(e){

    let runcard_id = $(this).attr('runcard-id');

    EditCancelRemoveRuncard(arrEditRemoveRuncards, runcard_id);

  });

  $(document).on('click','.btn-remove-additional-runcard',function(e){

    let runcard_id = $(this).attr('runcard-id');

    EditRemoveAdditionalRuncard(arrEditAddRuncards,runcard_id);

  });

  $('#btn_save_edit_runcards').click(function(){

    $('#modalScan_SaveEditRuncards').modal('show');
    $('#txt_edit_runcard_by_id').val('');

  });


  $(document).on('keypress',function(e){
    if( ($("#modalScan_SaveEditRuncards").data('bs.modal') || {})._isShown ){
      $('#txt_edit_runcard_by_id').focus();

      if( e.keyCode == 13 && $('#txt_edit_runcard_by_id').val() !='' && ($('#txt_edit_runcard_by_id').val().length >= 4) ){

          $('#modalScan_SaveEditRuncards').modal('hide');

          let oqc_lotapp_id = $('#editRuncardOqcLotApp').val();
          let saved_by = $('#txt_edit_runcard_by_id').val();

          SaveRuncardChanges(oqc_lotapp_id, saved_by, arrEditAddRuncards, arrEditRemoveRuncards);
        }
      }
    }); 

  $('#modalViewRuncards').on('hidden.bs.modal',function(){

    arrEditAddRuncards = [];
    arrEditRemoveRuncards = [];

    dt_oqclotapp_additional_runcards.draw();
    dt_oqclotapp_view_runcards.draw();

  });


//--------------------------------SECOND SUBMISSION ------------------------------------------

$(document).on('click','.btn-other-submission',function(){

  let series_name = $('#id_device_name').val();
  let oqclotapp_id = $(this).attr('oqclotapp-id');
  let current_po = $('#id_po_no').val();

  LoadOtherSubmissionDetails(oqclotapp_id, current_po, series_name);
  LoadRuncardDeviceSubDetails(current_po, series_name);

});

//SCAN FVO FOR SUBMISSION

$('#btnAddFvoSub').click(function(){

  $('#modalScan_FVO_sub').modal('show');
  $('#txt_fvo_sub_id').val('');

});

$(document).on('keypress',function(e){
    if( ($("#modalScan_FVO_sub").data('bs.modal') || {})._isShown ){
      $('#txt_fvo_sub_id').focus();

      if( e.keyCode == 13 && $('#txt_fvo_sub_id').val() !='' && ($('#txt_fvo_sub_id').val().length >= 4) ){

          $('#modalScan_FVO_sub').modal('hide');

          let oqc_lotapp_id = $('#lotapp_id_sub').val();
          let fvo_id = $('#txt_fvo_sub_id').val();
          let submission = $('#lotapp_submission').val();

          AddFvoOtherSubmission(oqc_lotapp_id, fvo_id, submission, arrFvoSub);
          
        }
      }
    }); 


 //remove from array
$(document).on('click','.btn-remove-array-fvo-sub',function(e){

   let fvo_user_id = $(this).attr('fvo-user-id');
   RemoveFvo(arrFvoSub,fvo_user_id);

  });

 $('#btnViewRuncardsSub').click(function(e){
    dt_oqclotapp_view_runcards_sub.draw();
  })

 /****ACTUALLY ADDS TO ARRAY, FOR REMOVAL IF SUBMITTED***/
  $(document).on('click','.btn-edit-remove-runcard-sub',function(e){

    let runcard_id = $(this).attr('runcard-id');

    EditRemoveRuncard(arrEditRemoveRuncardsSub,runcard_id);

  });

  $(document).on('click','.btn-cancel-remove-runcard-sub',function(e){

    let runcard_id = $(this).attr('runcard-id');

    EditCancelRemoveRuncard(arrEditRemoveRuncardsSub, runcard_id);

  });

  $(document).on('click','.btn-remove-additional-runcard-sub',function(e){

    let runcard_id = $(this).attr('runcard-id');

    EditRemoveAdditionalRuncard(arrEditAddRuncardsSub,runcard_id);

  });

        //SEARCH RUNCARD
    $(document).on('click','.btn_search_addtional_runcard_no_sub',function(e){
      $('#txt_additional_runcard_number_sub').val('');
      $('#modalScan_AdditionalRuncardNoSub').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_AdditionalRuncardNoSub").data('bs.modal') || {})._isShown ){
        $('#txt_additional_runcard_number_sub').focus();

        if( e.keyCode == 13 && $('#txt_additional_runcard_number_sub').val() !='' && ($('#txt_additional_runcard_number_sub').val().length >= 4) ){

            $('#modalScan_AdditionalRuncardNoSub').modal('hide');

            let runcard_no = $('#txt_additional_runcard_number_sub').val();
            let po_num = $('#editRuncardPoNoSub').val(); //pwede na to or yung text current po n0
            let oqc_lotapp_id = $('#editRuncardOqcLotAppSub').val();
            let lot_qty = $('#editRuncardLotQtySub').val();
            
            EditAddRuncard(runcard_no, po_num, oqc_lotapp_id, lot_qty, arrEditAddRuncardsSub);
          }
        }
    }); 

         //SEARCH RUNCARD
    $(document).on('click','.btn_search_addtional_rework_no_sub',function(e){
      $('#txt_additional_rework_number_sub').val('');
      $('#modalScan_AdditionalReworkNoSub').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_AdditionalReworkNoSub").data('bs.modal') || {})._isShown ){
        $('#txt_additional_rework_number_sub').focus();

        if( e.keyCode == 13 && $('#txt_additional_rework_number_sub').val() !='' && ($('#txt_additional_rework_number_sub').val().length >= 4) ){

            $('#modalScan_AdditionalReworkNoSub').modal('hide');

            let rework_no = $('#txt_additional_rework_number_sub').val();
            let po_num = $('#editRuncardPoNoSub').val(); //pwede na to or yung text current po n0
            let oqc_lotapp_id = $('#editRuncardOqcLotAppSub').val();
            let lot_qty = $('#editRuncardLotQtySub').val();
            
            EditAddRework(rework_no, po_num, oqc_lotapp_id, lot_qty, arrEditAddRuncardsSub);
          }
        }
      });

    $('#btn_save_edit_runcards_sub').click(function(){

      $('#modalScan_SaveEditRuncardsSub').modal('show');
      $('#txt_edit_runcard_by_id_sub').val('');

    });


    $(document).on('keypress',function(e){
      if( ($("#modalScan_SaveEditRuncardsSub").data('bs.modal') || {})._isShown ){
        $('#txt_edit_runcard_by_id_sub').focus();

        if( e.keyCode == 13 && $('#txt_edit_runcard_by_id_sub').val() !='' && ($('#txt_edit_runcard_by_id_sub').val().length >= 4) ){

            $('#modalScan_SaveEditRuncardsSub').modal('hide');

            let oqc_lotapp_id = $('#editRuncardOqcLotAppSub').val();
            let saved_by = $('#txt_edit_runcard_by_id_sub').val();

            SaveRuncardChanges(oqc_lotapp_id, saved_by, arrEditAddRuncardsSub, arrEditRemoveRuncardsSub);
          }
        }
      }); 

    $('#modalViewRuncards').on('hidden.bs.modal',function(){

      arrEditAddRuncards = [];
      arrEditRemoveRuncards = [];

      dt_oqclotapp_additional_runcards.draw();
      dt_oqclotapp_view_runcards.draw();
    });

    $('#modalViewRuncardsSub').on('hidden.bs.modal',function(){

      arrEditAddRuncardsSub = [];
      arrEditRemoveRuncardsSub = [];

      dt_oqclotapp_additional_runcards_sub.draw();
      dt_oqclotapp_view_runcards_sub.draw();

    });

    $('#btn_submit_other_submission').click(function(){

      $('#txt_applied_by_id_sub').val('');
      $('#modalScan_SaveLotAppSub').modal('show');

    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_SaveLotAppSub").data('bs.modal') || {})._isShown ){
        $('#txt_applied_by_id_sub').focus();

        if( e.keyCode == 13 && $('#txt_applied_by_id_sub').val() !='' && ($('#txt_applied_by_id_sub').val().length >= 4) ){

              
          $('#modalScan_SaveLotAppSub').modal('hide');

            $('#formOtherSubmission').submit();

          }
        }
      }); 


    $('#formOtherSubmission').submit(function(e){

      e.preventDefault();

      SubmitOtherSubmission(arrFvoSub);
    });

    $(document).on('click', '.bulk-runcard', function(){

      let runcard_id = $(this).attr('runcard-id');
      let runcard_no = $(this).attr('runcard-no');
      let output_qty = $(this).attr('output-qty');

      if($(this).is(':checked'))
      {
        let runcard_details = {

          "runcard_id": runcard_id,
          "type" : 1,
          "runcard_no": runcard_no,
          "output_qty": parseInt(output_qty)
        }

        arrRuncards.push(runcard_details);
      }
      else
      {
         for(let i = 0; i < arrRuncards.length; i++)
         {
          if(arrRuncards[i]['runcard_id'] == runcard_id && arrRuncards[i]['type'] == 1)
          {
            arrRuncards.splice(i,1);
          }
        }
      }

      DisableSubmitRuncardApplication(arrRuncards);
      console.log(arrRuncards);

    });

    $('#btnBulkRuncards').click(function(){

        dt_bulk_runcards.draw();
        dt_bulk_rework.draw();

    });

    $('#modalBulkRuncards').on('hidden.bs.modal',function(){

      dt_oqclotapp_runcards.draw();
      GetTotalOutputQty(arrRuncards);

    });

     $(document).on('click', '.bulk-rework', function(){

      let runcard_id = $(this).attr('runcard-id');
      let runcard_no = $(this).attr('runcard-no');
      let output_qty = $(this).attr('output-qty');

      if($(this).is(':checked'))
      {
        let runcard_details = {

          "runcard_id": runcard_id,
          "type" : 2,
          "runcard_no": runcard_no,
          "output_qty": parseInt(output_qty)
        }

        arrRuncards.push(runcard_details);
      }
      else
      {
         for(let i = 0; i < arrRuncards.length; i++)
         {
          if(arrRuncards[i]['runcard_id'] == runcard_id && arrRuncards[i]['type'] == 2)
          {
            arrRuncards.splice(i,1);
          }
        }
      }

      DisableSubmitRuncardApplication(arrRuncards);
      console.log(arrRuncards);

    });


$(document).on('click','.view-lot-application',function(){

    let application_id = $(this).attr('oqclotapp-id');

    LoadApplicationDetails(application_id)
});

</script>
@endsection
@endauth