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

<?php
date_default_timezone_set('Asia/Manila');
?>

@auth
@extends($layout)

@section('title', 'Defect Escalation')

@section('content_page')
<style type="text/css">
.hidden_scanner_input{
position: fixed;
/*bottom: 0;
left: 0;*/
opacity: 0;
}

textarea{
resize: none;


.modal-xl-custom{
width: 95%!important;
min-width: 90%!important;
}

#mdl_runcard_number_scanner {
z-index: 2001;
}

#mdlAddMaterial {
    z-index: 2002;
}

#modalLotNoScanner {
    z-index: 2003;
}*/

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
        <h1>Defect Escalation v2</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
        {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> --}}
        {{-- <li class="breadcrumb-item">Production</li>
        <li class="breadcrumb-item active">Runcard</li> --}}
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
                            <button type="button" class="btn btn-primary btnSearchPoNo" title="Scan PO Code">
                                <i class="fa fa-qrcode"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control" id="txt_po_number_lbl" value="{{ $po ?? '' }}" readonly>
                    </div>
                </div>

                <div class="col-sm-3">
                    <label>Device Name</label>
                    <input type="text" class="form-control" id="txt_device_name_lbl" value="{{ $device ?? '' }}" readonly>
                </div>

                <div class="col-sm-3">
                    <label>Device Code</label>
                    <input type="text" class="form-control" id="txt_device_code_lbl" value="{{ $code ?? '' }}" readonly>
                </div>

                <div class="col-sm-3">
                    <label>PO Qty</label>
                    <input type="text" class="form-control" id="txt_po_qty_lbl" value="{{ $qty ?? '' }}" readonly>
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
                    <h3 class="card-title">List of Defect Escalations</h3>
                </div>

                <!-- Start Page Content -->
                <div class="card-body">
                    <div class="row">
                    <div class="col-12">
                        <div class="float-right">
                        {{-- <button class="btn btn-primary btn-sm" id="btnShowAddProdRuncard" disabled><i class="fa fa-plus"></i> Add New</button --}}
                        <button class="btn btn-primary btn-sm" id="btnShowAddProdRuncard"><i class="fa fa-plus"></i> Add New</button>
                        </div>
                        <br><br>
                        <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover" id="tblDefectEscalation" style="min-width: 900px;">
                            <thead>
                            <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Defect Escalation Ctrl #</th>
                                <th>Runcard #</th>
                                <th>Defect Classification</th>
                                <th>Defect Type</th>
                                <th>In-Charge</th>
                                <th>Position</th>
                                <th>Shift</th>
                                <th>Station</th>
                                <th>Date / Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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

    <!-- Modal Scan PO -->
<div class="modal fade" id="modalScanPoNumber" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
            <span id="scanPOTransLotBody">Please scan the PO code.</span>
            <br>
            <br>
            <h1><i id="scanPOTransLotIcon" class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            {{-- <input type="text" id="txtSearchPoNumber" class="hidden_scanner_input"> --}}
            <input type="text" id="txtSearchPoNumber">
        </div>
        </div>
    </div>
</div>
    <!-- /.Modal -->

<!-- Modal Add New Defect Escalation -->
<div class="modal fade" id="modalAddDefectEscalation" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAddDefectEscalationLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-cog"></i> Defect Escalation Slip</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formAddDefectEscalation">
                @csrf
                <div class="modal-body">

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="front-tab" data-toggle="tab" href="#front" role="tab">Front</a>
                        </li>
                        <li class="nav-item backTabClass" hidden>
                            <a class="nav-link" id="back-tab" data-toggle="tab" href="#back" role="tab">Back</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- FRONT TAB -->
                        <div class="tab-pane fade show active" id="front" role="tabpanel">

                            {{-- <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Defect Type</span>
                                        </div>
                                        <select name="mod_type" id="txtModType" class="form-control">
                                            <option disabled selected value="">-- Select Defect Type --</option>
                                            <option value="1">Material NG</option>
                                            <option value="2">Production NG</option>
                                        </select>
                                    </div>
                                </div>
                            </div>   --}}

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <input type="hidden" class="form-control" name="defect_id" id="txtDefectId">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Control Number</span>
                                        </div>
                                            <input type="text" class="form-control" name="defect_ctrl_number" id="txtDefectCtrlNumber" placeholder="Auto generated" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-30">
                                            <span class="input-group-text w-100">Shift</span>
                                        </div>
                                        <div class="form-control d-flex justify-content-around align-items-center">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="shift" id="shiftA" value="1">
                                                <label class="form-check-label" for="shiftA">A</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="shift" id="shiftB" value="2">
                                                <label class="form-check-label" for="shiftB">B</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group input-group-prepend w-25">
                                            <span class="input-group-text w-100" id="basic-addon1">Line</span>
                                        </div>
                                        {{-- <input type="text" name="line" id="txtLine" class="form-control"> --}}
                                        <select class="form-control select2 select2bs4 sel-assembly-lines" id="txtLine" name="line">
                                            <option value=""> N/A </option>
                                          </select>
                                        <input hidden type="text" name="line_t" id="txtLineT" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-30">
                                            <span class="input-group-text w-100" id="basic-addon1">Date</span>
                                        </div>
                                        <input type="text" name="date" id="txtDate" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-30">
                                            <span class="input-group-text w-100" id="basic-addon1">Time</span>
                                        </div>
                                        <input type="text" name="time" id="txtTime" class="form-control" readonly placeholder="Auto generated">
                                    </div>
                                </div>
                            </div>

                            <div class="header" style="border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                <label class="fw-bold">
                                    DEFECT ESCALATION
                                </label>
                            </div>

                            <div class="row mt-2">
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text w-100">Employee Name</span>
                                        </div>
                                        <select class="form-control select2 select2bs4 selUser" id="txtSelectUser" name="select_user">
                                            <option value="">Select user</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text w-100">Position</span>
                                        </div>
                                        <div class="form-control d-flex align-items-center flex-wrap gap-2" style="min-height: 38px;">
                                            <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="position" id="txtOperator" value="1" disabled>
                                                <label class="form-check-label" for="txtOperator">Operator</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="position" id="txtMaterialHandler" value="2" disabled>
                                                <label class="form-check-label" for="txtMaterialHandler">Material Handler</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="position" id="txtQCInspector" value="3" disabled>
                                                <label class="form-check-label" for="txtQCInspector">QC Inspector</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="position" id="txtTechnician" value="4" disabled>
                                                <label class="form-check-label" for="txtTechnician">Technician</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- PO Number -->
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">PO Number</span>
                                        </div>
                                        <input type="text" class="form-control" name="po_number" id="txtPONumber" readonly>
                                    </div>
                                </div>

                                  <!-- Product Name -->
                                <div class="col-sm-6">
                                  <div class="input-group input-group-sm mb-3">
                                      <div class="input-group-prepend w-50">
                                          <span class="input-group-text w-100">Product Name</span>
                                      </div>
                                      <input type="text" class="form-control" name="product_name" id="txtProductName" readonly>
                                  </div>
                                </div>
                            </div>

                            <div class="row">
                              <!-- Runcard Number -->
                              <div class="col-sm-12">
                                  <div class="input-group input-group-sm mb-3">
                                      <div class="input-group-prepend w-50">
                                          <span class="input-group-text w-100">Runcard Number</span>
                                      </div>
                                      <select class="form-control select2 select2bs4 selRuncardNo" id="txtRuncardNumber" name="runcard_no"></select>
                                      <input hidden type="text" class="form-control" name="runcard_station_mod_id" id="txtStationModId" readonly>
                                      <input hidden type="text" class="form-control" name="runcard_station_id" id="txtRuncardStationId" readonly>
                                      <input hidden type="text" class="form-control" name="runcard_id" id="txtRuncardId" readonly>
                                  </div>
                              </div>
                            </div>


                            <div class="row">
                                <!-- Lot # -->
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Lot #</span>
                                        </div>
                                        <input type="text" class="form-control" name="lot_no" id="txtLotNo" autocomplete="off">
                                    </div>
                                </div>

                                <!-- Pair # -->
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Pair #</span>
                                        </div>
                                        <input type="text" class="form-control" name="pair_no" id="txtPairNo" autocomplete="off">
                                    </div>
                                </div>

                                <!-- Die/Mold # -->
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Die/Mold #</span>
                                        </div>
                                        <input type="text" class="form-control" name="d_mold_no" id="txtDMoldNo" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Station Select -->
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Station</span>
                                        </div>
                                        <select name="station" id="txtStation" class="form-control">
                                            <option disabled selected value="">-- Select Station --</option>
                                            <option value="1">Parts Preparation</option>
                                            <option value="2">Machine</option>
                                            <option value="3">Assembly</option>
                                            <option value="4">Visual Inspection</option>
                                            <option value="5">Setup / Adj.</option>
                                            <option value="6">Qualification</option>
                                            <option value="7">Others</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Other Station Text Input (hidden by default) -->
                                <div class="col-sm-6" id="otherStationContainer" style="display: none;">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-40">
                                            <span class="input-group-text w-100">Station Description</span>
                                        </div>
                                        <input type="text" name="other_station" id="txtOtherStation" class="form-control" placeholder="Please specify">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <table id="areaTable" class="table table-bordered text-center align-middle" style="width: 100%; font-size: 14px;">
                                          <thead>
                                            <tr class="table-active">
                                              <th style="width: 20%;">Unit #</th>
                                              <th>1</th>
                                              <th>2</th>
                                              <th>3</th>
                                              <th>4</th>
                                              <th>5</th>
                                              <th style="width: 80px;"></th> <!-- column for buttons -->
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td><strong>Area</strong></td>
                                              <td><input type="text" name="area1[]" class="form-control area1"></td>
                                                <td><input type="text" name="area2[]" class="form-control area2"></td>
                                                <td><input type="text" name="area3[]" class="form-control area3"></td>
                                                <td><input type="text" name="area4[]" class="form-control area4"></td>
                                                <td><input type="text" name="area5[]" class="form-control area5"></td>
                                              <td class="align-middle">
                                                <button type="button" class="btn btn-outline-primary btn-sm rounded-circle addRowBtn" style="width:25px; height:25px; padding:0;">+</button>
                                                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle removeRowBtn" style="width:25px; height:25px; padding:0;">x</button>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="container col-sm-4 border border-secondary rounded p-2">
                                    <div class="form-group">
                                        <label>Legend:</label>
                                        <div class="row">
                                            <div class="col-6">Side 1 - S1</div>
                                            <div class="col-6">Side 4 - S4</div>
                                            <div class="col-6">Side 2 - S2</div>
                                            <div class="col-6">Center - C</div>
                                            <div class="col-6">Side 3 - S3</div>
                                            <div class="col-6">All Areas - AA</div>
                                            <div class="col-6">Top Area - TA</div>
                                            <div class="col-6">Bottom Area - BA</div>
                                            {{-- <div class="col-12">Terminal Area - TA</div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                        <label class="fw-bold">
                                            A. PROBLEM ENCOUNTERED (Indicate the problem encountered by ticking the below of box defect lists)
                                        </label>

                                    </div>
                                </div>
                            </div>

                            <!-- No. of Occurrence -->
                            <div class="row mb-3 mt-3">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend w-45">
                                            <span class="input-group-text w-100">No. of Occurrence</span>
                                        </div>
                                        <div class="form-control d-flex align-items-center flex-wrap gap-2" style="min-height: 38px;">
                                            <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="occurrence" id="txtOccurrence1Id" value="1">
                                                <label class="form-check-label" for="txtOccurrence1Id">1st</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="occurrence" id="txtOccurrence2Id" value="2">
                                                <label class="form-check-label" for="txtOccurrence2Id">2nd</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="occurrence" id="txtOccurrence3Id" value="3">
                                                <label class="form-check-label" for="txtOccurrence3Id">3rd</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Defect Classification -->
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend w-45">
                                            <span class="input-group-text w-100">Defect Classification</span>
                                        </div>
                                        <div class="form-control d-flex align-items-center flex-wrap gap-2" style="min-height: 38px;">
                                            <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="d_classification" id="txtDefectClassification1Id" value="1">
                                                <label class="form-check-label" for="txtDefectClassification1Id">CRITICAL</label>
                                                </div>
                                                <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="d_classification" id="txtDefectClassification2Id" value="2">
                                                <label class="form-check-label" for="txtDefectClassification2Id">NON-CRITICAL</label>
                                                </div>
                                                <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="d_classification" id="txtDefectClassification3Id" value="3">
                                                <label class="form-check-label" for="txtDefectClassification3Id">SET-UP / ADJ. / REPAIR</label>
                                                </div>
                                                <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="d_classification" id="txtDefectClassification4Id" value="4">
                                                <label class="form-check-label" for="txtDefectClassification4Id">AUTO INSERTER DEFECTS</label>
                                                </div>
                                                <div class="form-check form-check-inline mb-1">
                                                <input type="radio" class="form-check-input" name="d_classification" id="txtDefectClassification5Id" value="5">
                                                <label class="form-check-label" for="txtDefectClassification5Id">NG SAMPLES</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Mode of Defect -->
                                <div class="col-sm-6 modeOfDefectClass" hidden>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-40">
                                            <span class="input-group-text w-100">Mode of Defect</span>
                                        </div>
                                        <select class="form-control select2 select2bs4 selDefects" id="txtDefects" name="defect[]" multiple></select>
                                    </div>
                                </div>

                                <!-- Critical -->
                                <div class="col-sm-6 criticalDefectClass" hidden>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-40">
                                            <span class="input-group-text w-100">Detailed Description</span>
                                        </div>
                                        <input type="text" name="detailed_description" id="txtDetailedDescription" class="form-control" autocomplete="off">
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-sm-6">

                                </div>
                                <div class="col-sm-6 otherDefectClass" hidden>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-40">
                                            <span class="input-group-text w-100"></span>
                                        </div>
                                        <input type="text" name="defect_qty" id="txtDefectQty" class="form-control" autocomplete="off">
                                    </div>
                                </div>

                                <!-- Setup -->
                                {{-- <div class="col-sm-6 setupDefectClass" hidden>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-40">
                                            <span class="input-group-text w-100">Setup Qty</span>
                                        </div>
                                        <input type="text" name="setup_qty" id="txtSetupQty" class="form-control" autocomplete="off">
                                    </div>
                                </div>

                                <!-- Auto Inserter -->
                                <div class="col-sm-6 autoInserterDefectClass" hidden>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-40">
                                            <span class="input-group-text w-100">Auto Inserter Others</span>
                                        </div>
                                        <input type="text" name="auto_inserter_others" id="txtautoInserterOthers" class="form-control" autocomplete="off">
                                    </div>
                                </div>

                                <!-- NG Samples -->
                                <div class="col-sm-6 ngSamplesDefectClass" hidden>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-40">
                                            <span class="input-group-text w-100">NG Samples Others</span>
                                        </div>
                                        <input type="text" name="ng_samples_others" id="txtngSamplesOthers" class="form-control" autocomplete="off">
                                    </div>
                                </div> --}}
                            </div>

                            <div class="row bHeaderClass" hidden>
                            {{-- <div class="row bHeaderClass"> --}}
                                <div class="col-sm-12">
                                    <div id="bToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <span id="bHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                        <label class="fw-bold">
                                                B. LEVEL 1: PROBLEM VERIFICATION
                                                <small>(Verification shall be done by Production, Engineering & Line QC)</small>
                                            </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row bHeaderVerificationHeader" style="display: none;">
                                <div class="col-sm-12">
                                    <div id="bToggleVerificationContentHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                        <label class="fw-bold ml-5">
                                            <span id="bHeaderVerificationContentIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                            B.1. VERIFICATION RESULT
                                        </label>

                                    </div>

                                    <div class="content bHeaderVerificationContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                        <div class="row mb-3">
                                            <!-- Production -->
                                            <div class="col-sm-4">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="p_verification_result" id="txtPVerificationResult1" value="1">
                                                        <label class="form-check-label" for="txtPVerificationResult1">OK</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="p_verification_result" id="txtPVerificationResult2" value="2">
                                                        <label class="form-check-label" for="txtPVerificationResult2">NG</label>
                                                    </div>
                                                </div>
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Production</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="p_verification_user" id="txtPVerificationUser" autocomplete="off" readonly>
                                                    <input type="hidden" class="form-control" name="p_verification_user_id" id="txtPVerificationUserId" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary btnScanVerificationId" data-type="P" title="Scan Production ID">
                                                            <i class="fa fa-qrcode"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Date</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="p_verification_date" id="txtPVerificationDate" placeholder="Auto generated" readonly>
                                                </div>
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Remarks</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="p_verification_remarks" id="txtPVerificationRemarks" autocomplete="off">
                                                </div>
                                            </div>

                                            <!-- Engineering -->
                                            <div class="col-sm-4">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="e_verification_result" id="txtEVerificationResult1" value="1">
                                                        <label class="form-check-label" for="txtEVerificationResult1">OK</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="e_verification_result" id="txtEVerificationResult2" value="2">
                                                        <label class="form-check-label" for="txtEVerificationResult2">NG</label>
                                                    </div>
                                                </div>
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Engineering</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="e_verification_user" id="txtEVerificationUser" readonly>
                                                    <input type="hidden" class="form-control" name="e_verification_user_id" id="txtEVerificationUserId"  autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary btnScanVerificationId" data-type="E" title="Scan Engineering ID">
                                                            <i class="fa fa-qrcode"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Date</span>
                                                    </div>
                                                    {{-- <input type="date" class="form-control" name="e_verification_date"> --}}
                                                    <input type="text" class="form-control" name="e_verification_date" id="txtEVerificationDate" placeholder="Auto generated" readonly>

                                                </div>
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Remarks</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="e_verification_remarks" id="txtEVerificationRemarks" autocomplete="off">
                                                </div>
                                            </div>

                                            <!-- QC -->
                                            <div class="col-sm-4">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="q_verification_result" id="txtQVerificationResult1" value="1">
                                                        <label class="form-check-label" for="txtQVerificationResult1">OK</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="q_verification_result" id="txtQVerificationResult2" value="2">
                                                        <label class="form-check-label" for="txtQVerificationResult2">NG</label>
                                                    </div>
                                                </div>
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">QC</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="q_verification_user" id="txtQVerificationUser" readonly>
                                                    <input type="hidden" class="form-control" name="q_verification_user_id" id="txtQVerificationUserId"  autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary btnScanVerificationId" data-type="Q" title="Scan QC ID">
                                                            <i class="fa fa-qrcode"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Date</span>
                                                    </div>
                                                    {{-- <input type="date" class="form-control" name="q_verification_date"> --}}
                                                    <input type="text" class="form-control" name="q_verification_date" id="txtQVerificationDate" placeholder="Auto generated" readonly>

                                                </div>
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Remarks</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="q_verification_remarks" id="txtQVerificationRemarks" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--DISPOSITION -->
                            <div class="row bHeaderDispositionHeader" style="display: none;">
                                <div class="col-sm-12">
                                        <!-- B.1 Header -->
                                        <div id="bToggleDispositionContentHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <label class="fw-bold ml-5">
                                                <span id="bHeaderDispositionContentIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                                B.2. DISPOSITION
                                            </label>

                                        </div>

                                        <div class="content bHeaderDispositionContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                            <div class="row mb-3">
                                                <!-- Production -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="p_disposition" id="txtDisposition1" value="1">
                                                            <label class="form-check-label" for="txtDisposition1">Rework</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="p_disposition" id="txtDisposition2" value="2">
                                                            <label class="form-check-label" for="txtDisposition2">Scrap</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="p_disposition" id="txtDisposition3" value="3">
                                                            <label class="form-check-label" for="txtDisposition3">S.A.</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Production:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="p_disposition_user" id="txtPDispositionUser">
                                                            <input type="hidden" class="form-control" name="p_disposition_user_id" id="txtPDispositionUserID"  autocomplete="off">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-primary btnScanVerificationId" data-type="DP" title="Scan Prod ID">
                                                                    <i class="fa fa-qrcode"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Date:</span>
                                                            </div>
                                                            {{-- <input type="date" class="form-control" name="p_disposition_date"> --}}
                                                            <input type="text" class="form-control" name="p_disposition_date" id="txtPDispositionDate" placeholder="Auto generated" readonly>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Remarks:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="p_disposition_remarks" id="txtPDispositionRemarks" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Engineering -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="e_disposition" id="txtEDisposition1" value="1">
                                                            <label class="form-check-label" for="txtEDisposition1">Rework</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="e_disposition" id="txtEDisposition2" value="2">
                                                            <label class="form-check-label" for="txtEDisposition2">Scrap</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="e_disposition" id="txtEDisposition3" value="3">
                                                            <label class="form-check-label" for="txtEDisposition3">S.A.</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Engineering:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="e_disposition_user" id="txtEDispositionUser">
                                                            <input type="hidden" class="form-control" name="e_disposition_user_id" id="txtEDispositionUserID"  autocomplete="off">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-primary btnScanVerificationId" data-type="EP" title="Scan Engineering ID">
                                                                    <i class="fa fa-qrcode"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Date:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="e_disposition_date" id="txtEDispositionDate" placeholder="Auto generated" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Remarks:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="e_disposition_remarks" id="txtEDispositionRemarks" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- QC -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="q_disposition" id="txtQDisposition1" value="1">
                                                            <label class="form-check-label" for="txtQDisposition1">Rework</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="q_disposition" id="txtQDisposition2" value="2">
                                                            <label class="form-check-label" for="txtQDisposition2">Scrap</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="q_disposition" id="txtQDisposition3" value="3">
                                                            <label class="form-check-label" for="txtQDisposition3">S.A.</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">QC:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="q_disposition_user" id="txtQDispositionUser">
                                                            <input type="hidden" class="form-control" name="q_disposition_user_id" id="txtQDispositionUserID"  autocomplete="off">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-primary btnScanVerificationId" data-type="QP" title="Scan QC ID">
                                                                    <i class="fa fa-qrcode"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Date:</span>
                                                            </div>
                                                            {{-- <input type="date" class="form-control" name="q_disposition_date"> --}}
                                                            <input type="text" class="form-control" name="q_disposition_date" id="txtQDispositionDate" placeholder="Auto generated" readonly>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Remarks:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="q_disposition_remarks" id="txtQDispositionRemarks" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>

                            <div class="row cHeaderClass" hidden>
                            {{-- <div class="row cHeaderClass"> --}}
                                <div class="col-sm-12">
                                    <div id="cToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <label class="fw-bold">
                                                <span id="cHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                                C. IMMEDIATE ACTION
                                                <small>(shall be made within 24 hours)</small>
                                            </label>
                                    </div>
                                </div>
                            </div>

                            <div class="content cHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <table class="table table-bordered text-center align-middle" style="width: 100%; font-size: 14px;">
                                                    <thead>
                                                        <tr class="table-active">
                                                            <th style="width: 30%;">Action Taken</th>
                                                            <th>Action Details</th>
                                                            <th style="width: 10%;">When</th>
                                                            <th style="width: 20%;">Who</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: left; "><strong>1. MAN</strong> - re-orient, retrain & re-certify</td>
                                                            <td><input type="text" name="action_details_man" id="txtActionDetailsMan" class="form-control" autocomplete="off"></td>
                                                            <td><input type="date" name="when_man" id="txtWhenMan" class="form-control"></td>
                                                            {{-- <td><input type="text" name="who_man" class="form-control" autocomplete="off"></td> --}}
                                                            <td><select class="form-control select2 select2bs4 selectUser" id="txtSelectUserMan" name="select_user_man[]" multiple>
                                                                <option value="">Select user</option>
                                                            </select></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;"><strong>2. MACHINE</strong> - adjustment / repair</td>
                                                            <td><input type="text" name="action_details_machine" id="txtActionDetailsMachine" class="form-control" autocomplete="off"></td>
                                                            <td><input type="date" name="when_machine" id="txtWhenMachine" class="form-control"></td>
                                                            <td><select class="form-control select2 select2bs4 selectUser" id="txtSelectUserMachine" name="select_user_machine[]" multiple>
                                                                <option value="">Select user</option>
                                                            </select></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;"><strong>3. MATERIAL</strong> - change lot number</td>
                                                            <td><input type="text" name="action_details_material" id="txtActionDetailsMaterial" class="form-control" autocomplete="off"></td>
                                                            <td><input type="date" name="when_material" id="txtWhenMaterial" class="form-control"></td>
                                                            <td><select class="form-control select2 select2bs4 selectUser" id="txtSelectUserMaterial" name="select_user_material[]" multiple>
                                                                <option value="">Select user</option>
                                                            </select></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;"><strong>4. METHOD</strong> - re-validate process</td>
                                                            <td><input type="text" name="action_details_method" id="txtActionDetailsMethod" class="form-control" autocomplete="off"></td>
                                                            <td><input type="date" name="when_method" id="txtWhenMethod" class="form-control"></td>
                                                            <td><select class="form-control select2 select2bs4 selectUser" id="txtSelectUserMethod" name="select_user_method[]" multiple>
                                                                <option value="">Select user</option>
                                                            </select></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;"><strong>5. ENVIRONMENT</strong> - RT/RH</td>
                                                            <td><input type="text" name="action_details_environment" id="txtActionDetailsEnvironment" class="form-control" autocomplete="off"></td>
                                                            <td><input type="date" name="when_environment" id="txtWhenEnvironment" class="form-control"></td>
                                                            <td><select class="form-control select2 select2bs4 selectUser" id="txtSelectUserEnvironment" name="select_user_environment[]" multiple>
                                                                <option value="">Select user</option>
                                                            </select></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row dHeaderClass" hidden>
                            {{-- <div class="row dHeaderClass"> --}}
                                <div class="col-sm-12">
                                    <div id="dToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                        <label class="fw-bold">
                                            <span id="dHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                            D. CONTAINMENT ACTION
                                            <small>(IF NG)</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="content dHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <table class="table table-bordered text-center align-middle" style="width: 100%; font-size: 14px;">
                                                    <thead>
                                                        <tr class="table-active">
                                                            <th style="width: 20%;">CATEGORY</th>
                                                            <th style="width: 10%;">TOTAL QUANTITY</th>
                                                            <th style="width: 10%;">OK QUANTITY</th>
                                                            <th style="width: 10%;">NG QUANTITY</th>
                                                            <th style="width: 20%;">MODE OF DEFECT</th>
                                                            <th style="width: 20%;">REMARKS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>WIP</strong></td>
                                                            <td><input type="text" name="wip_total_qty" id="txtWipTotalQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="wip_ok_qty" id="txtWipOkQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="wip_ng_qty" id="txtWipNgQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="wip_mod" id="txtWipMod" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="wip_remarks" id="txtWipRemarks" class="form-control" autocomplete="off"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>PASSED FINAL</strong></td>
                                                            <td><input type="text" name="pf_total_qty" id="txtPfTotalQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="pf_ok_qty" id="txtPfOkQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="pf_ng_qty" id="txtPfNgQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="pf_mod" id="txtPfMod" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="pf_remarks" id="txtPfRemarks" class="form-control" autocomplete="off"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>OQC</strong></td>
                                                            <td><input type="text" name="oqc_total_qty" id="txtOqcTotalQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="oqc_ok_qty" id="txtOqcOkQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="oqc_ng_qty" id="txtOqcNgQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="oqc_mod" id="txtOqcMod" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="oqc_remarks" id="txtOqcRemarks" class="form-control" autocomplete="off"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>PACKING / FGS</strong></td>
                                                            <td><input type="text" name="packing_total_qty" id="txtPackingTotalQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="packing_ok_qty" id="txtPackingOkQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="packing_ng_qty" id="txtPackingNgQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="packing_mod" id="txtPackingMod" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="packing_remarks" id="txtPackingRemarks" class="form-control" autocomplete="off"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>SHIPPED-OUT</strong></td>
                                                            <td><input type="text" name="shipped_total_qty" id="txtShippedTotalQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="shipped_ok_qty" id="txtShippedOkQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="shipped_ng_qty" id="txtShippedNgQty" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="shipped_mod" id="txtShippedMod" class="form-control" autocomplete="off"></td>
                                                            <td><input type="text" name="shipped_remarks" id="txtShippedRemarks" class="form-control" autocomplete="off"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- BACK TAB -->
                        <div class="tab-pane fade" id="back" role="tabpanel">

                            <div class="row ifReworkHeaderClass" hidden>
                                <div class="col-sm-12">
                                    <div id="reworkToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                        <label class="fw-bold">
                                            <span id="reworkHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                            IF REWORK, OPERATOR TO FILL IN:
                                        </label>

                                    </div>

                                    <div class="content reworkHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Reworker Operator #1:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="reworker1" id="txtReworker1" autocomplete="off">
                                                        </div>

                                                        <div class="input-group input-group-sm mb-2">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Reworker Operator #2:</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="reworker2" id="txtReworker2" autocomplete="off">
                                                        </div>

                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">Date:</span>
                                                            </div>
                                                            <input type="date" class="form-control" name="rework_date" id="txtReworkDate">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <table class="table table-bordered text-center align-middle" style="width: 100%; font-size: 12px; margin-left: 0;">
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2" style="vertical-align: middle;">UNIT #</th>
                                                                    <th colspan="2">RESULT</th>
                                                                </tr>
                                                                <tr>
                                                                    <th>OK</th>
                                                                    <th>SCRAP</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td><input type="radio" name="rework1" id="txtRework1Result1"></td>
                                                                    <td><input type="radio" name="rework1" id="txtRework1Result2"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td><input type="radio" name="rework2" id="txtRework2Result1"></td>
                                                                    <td><input type="radio" name="rework2" id="txtRework2Result2"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td><input type="radio" name="rework3" id="txtRework3Result1"></td>
                                                                    <td><input type="radio" name="rework3" id="txtRework3Result2"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>4</td>
                                                                    <td><input type="radio" name="rework4" id="txtRework4Result1"></td>
                                                                    <td><input type="radio" name="rework4" id="txtRework4Result2"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>5</td>
                                                                    <td><input type="radio" name="rework5" id="txtRework5Result1"></td>
                                                                    <td><input type="radio" name="rework5" id="txtRework5Result2"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4" style="margin-top: -6rem">
                                                    <div class="form-group">
                                                        <label>REWORK ACTIVITY:</label>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtCtReplacement" value="1">
                                                            <label class="form-check-label" for="txtCtReplacement">CT REPLACEMENT</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtPartsDisAssembly" value="2">
                                                            <label class="form-check-label" for="txtPartsDisAssembly">PARTS DIS ASSEMBLY</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtRePressing" value="3">
                                                            <label class="form-check-label" for="txtRePressing">RE-PRESSING</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtRePulling" value="4">
                                                            <label class="form-check-label" for="txtRePulling">RE-PULLING</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtGluing" value="5">
                                                            <label class="form-check-label" for="txtGluing">GLUING OF CT</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-8" style="margin-top: -6rem">
                                                    <div class="form-group">
                                                        <label class="invisible"></label> <!-- Just to align with left column -->

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtPartsReplacement" value="6">
                                                            <label class="form-check-label" for="txtPartsReplacement">PARTS REPLACEMENT</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtAligningOfCt" value="7">
                                                            <label class="form-check-label" for="txtAligningOfCt">ALIGNING OF CT</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtAligningOfTerminal" value="8">
                                                            <label class="form-check-label" for="txtAligningOfTerminal">ALIGNING OF TERMINAL</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="rework_activity" id="txtCleaning" value="9">
                                                            <label class="form-check-label" for="txtCleaning">CLEANING</label>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check mr-4">
                                                                <input class="form-check-input" type="radio" name="rework_activity" id="txtCtInsertion" value="10">
                                                                <label class="form-check-label" for="txtCtInsertion">CT INSERTION</label>
                                                            </div>

                                                            <div class="form-check d-flex align-items-center" style="flex-grow: 1; margin-left: 1rem;">
                                                                <input class="form-check-input" type="radio" name="rework_activity" id="txtOthers" value="11">
                                                                <label class="form-check-label mr-2" for="txtOthers">OTHERS</label>
                                                                <input type="text" class="form-control" name="rework_activity_others" id="txtReworkActivityOthers" autocomplete="off" style="flex: 1; min-width: 150px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>MPC CHECK USING:</label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="mpc_check" id="txtMpcTerminalGauge" value="1">
                                                            <label class="form-check-label" for="txtMpcTerminalGauge">TERMINAL GAUGE</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="mpc_check" id="txtMpcDummyLo" value="2">
                                                            <label class="form-check-label" for="txtMpcDummyLo">DUMMY #LO</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="mpc_check" id="txtMpcDummyMo" value="3">
                                                            <label class="form-check-label" for="txtMpcDummyMo">DUMMY #MO</label>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>ACTIVITY AFTER REWORK:</label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="activity_after_rework" id="txtActiviyPrinting" value="1">
                                                            <label class="form-check-label" for="txtActiviyPrinting">PRINTING</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="activity_after_rework" id="txtActivityGluing" value="2">
                                                            <label class="form-check-label" for="txtActivityGluing">GLUING</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="activity_after_rework" id="txtActivityCuring" value="3">
                                                            <label class="form-check-label" for="txtActivityCuring">CURING</label>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>REPLACED PARTS</label>
                                                        <div class="input-group input-group-sm mb-3">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">PART NAME</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="r_part_name" id="txtReplacedPartName" autocomplete="off">
                                                        </div>

                                                        <div class="input-group input-group-sm mb-3">
                                                            <div class="input-group-prepend w-50">
                                                                <span class="input-group-text w-100">LOT NO</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="r_lot_no" id="txtReplacedLotNo" autocomplete="off">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                            </div>




                            {{-- E. Level 2 --}}
                                <div class="row eHeaderClass" hidden>
                                    <div class="col-sm-12">
                                        <!-- B.1 Header -->
                                        <div id="eToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <label class="fw-bold">
                                                <span id="eHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                                E. LEVEL II: PROBLEM VERIFICATION
                                                <small>(Verification shall be done by section head and technical adviser)</small>
                                            </label>

                                        </div>

                                        <div class="content eHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                            <div class="row mb-3">
                                                <!-- Production -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="p_level_two" id="txtPLevelTwo1" value="1">
                                                            <label class="form-check-label" for="txtPLevelTwo1">OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="p_level_two" id="txtPLevelTwo2" value="2">
                                                            <label class="form-check-label" for="txtPLevelTwo2">NG</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Production:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="p_level_two_user">
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="p_level_two_date">
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="p_level_two_remarks">
                                                    </div>
                                                </div>

                                                <!-- Engineering -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="e_level_two" id="txtELevelTwo1" value="1">
                                                            <label class="form-check-label" for="txtELevelTwo1">OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="e_level_two" id="txtELevelTwo2" value="2">
                                                            <label class="form-check-label" for="txtELevelTwo2">NG</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Engineering:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="e_level_two_user">
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="e_level_two_date">
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="e_level_two_remarks">
                                                    </div>
                                                </div>

                                                <!-- QC -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="q_level_two" id="txtQcLevelTwo1" value="1">
                                                            <label class="form-check-label" for="txtQcLevelTwo1">OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="q_level_two" id="txtQcLevelTwo2" value="2">
                                                            <label class="form-check-label" for="txtQcLevelTwo2">NG</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">QC:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="q_level_two_user">
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="q_level_two_date">
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="q_level_two_remarks">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 justify-content-center">
                                                <!-- PPC -->
                                                <div class="col-sm-6">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="ppc_level_two" id="txtPpcLevelTwo1" value="1">
                                                            <label class="form-check-label" for="txtPpcLevelTwo1">OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="ppc_level_two" id="txtPpcLevelTwo2" value="2">
                                                            <label class="form-check-label" for="txtPpcLevelTwo2">NG</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">PPC:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="ppc_level_two_user">
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="ppc_level_two_date">
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="ppc_level_two_remarks">
                                                    </div>
                                                </div>

                                                <!-- Technical Adviser -->
                                                <div class="col-sm-6">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="ta_level_two" id="txtTaLevelTwo1" value="1">
                                                            <label class="form-check-label" for="txtTaLevelTwo1">OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="ta_level_two" id="txtTaLevelTwo2" value="2">
                                                            <label class="form-check-label" for="txtTaLevelTwo2">NG</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Technical Adviser:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="ta_level_two_user">
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="ta_level_two_date">
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="ta_level_two_remarks">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                </div>
                            </div>

                            {{-- F. Level 3 --}}
                            <div class="row fHeaderClass" hidden>
                                <div class="col-sm-12">
                                        <!-- B.1 Header -->
                                        <div id="fToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <label class="fw-bold">
                                                <span id="fHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                                F. LEVEL III: PROBLEM VERIFICATION
                                                <small>(Verification shall be done by vice president & president)</small>
                                            </label>

                                        </div>

                                        <div class="content fHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                            <div class="row mb-3">
                                                <!-- PPC -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="ppc_level_three" id="txtPpcLevelThree1" value="1">
                                                            <label class="form-check-label" for="txtPpcLevelThree1">OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="ppc_level_three" id="txtPpcLevelThree2" value="2">
                                                            <label class="form-check-label" for="txtPpcLevelThree2">NG</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">PPC:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="ppc_level_three_user">
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="ppc_level_three_date">
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="ppc_level_three_remarks">
                                                    </div>
                                                </div>

                                                <!-- Operations -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="operations_level_three" id="txtOperationsLevelThree1" value="1">
                                                            <label class="form-check-label" for="txtOperationsLevelThree1">OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="operations_level_three" id="txtOperationsLevelThree2" value="2">
                                                            <label class="form-check-label" for="txtOperationsLevelThree2">NG</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Operations:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="operations_level_three_user">
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="operations_level_three_date">
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="operations_level_three_remarks">
                                                    </div>
                                                </div>

                                                <!-- President -->
                                                <div class="col-sm-4">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="president_level_three" id="txtPresidentLevelTwo1" value="1">
                                                            <label class="form-check-label" for="txtPresidentLevelTwo1">OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="president_level_three" id="txtPresidentLevelTwo2" value="2">
                                                            <label class="form-check-label" for="txtPresidentLevelTwo2">NG</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">President:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="president_level_three_user">
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="president_level_three_date">
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="president_level_three_remarks">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                </div>
                            </div>

                            {{-- G. FINAL DISPOSTION --}}
                            <div class="row gHeaderClass" hidden>
                                <div class="col-sm-12">
                                        <!-- B.1 Header -->
                                        <div id="gToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <label class="fw-bold">
                                                <span id="gHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                                G. FINAL DISPOSITION - LINE RESUMPTION
                                            </label>

                                        </div>

                                        <div class="content gHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="f_disposition" id="txtfDispositionResume" value="1">
                                                            <label class="form-check-label" for="txtfDispositionResume">RESUME PRODUCTION</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="f_disposition" id="txtfDispositionStop" value="2">
                                                            <label class="form-check-label" for="txtfDispositionStop">STOP PRODUCTION</label>
                                                        </div>
                                                    </div>

                                                    <!-- Engineering Manager -->
                                                    <div class="input-group input-group-sm mb-2 align-items-center">
                                                        <div class="input-group-prepend" style="min-width: 110px;">
                                                            <span class="input-group-text w-100">Eng'g Manager:</span>
                                                        </div>
                                                        <input type="text" class="form-control line-input" name="f_dispostion_em">
                                                        <div class="input-group-prepend ms-2" style="min-width: 50px;">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control line-input" name="f_dispostion_em_date" style="max-width: 160px;">
                                                    </div>


                                                    <!-- Process Engineering -->
                                                    <div class="input-group input-group-sm mb-2 align-items-center">
                                                        <div class="input-group-prepend" style="min-width: 110px;">
                                                            <span class="input-group-text w-100">Process Eng'g</span>
                                                        </div>
                                                        <input type="text" class="form-control line-input" name="f_dispostion_pe">
                                                        <div class="input-group-prepend ms-2" style="min-width: 50px;">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control line-input" name="f_dispostion_pe_date" style="max-width: 160px;">
                                                    </div>


                                                    <!-- Remarks -->
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="f_disposition_remarks">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="f_dispostion_qc" id="txtFDispositionQcResume" value="1">
                                                            <label class="form-check-label" for="txtFDispositionQcResume">RESUME PRODUCTION</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mx-3">
                                                            <input class="form-check-input" type="radio" name="f_dispostion_qc" id="txtFDispositionQcStop" value="2">
                                                            <label class="form-check-label" for="txtFDispositionQcStop">STOP PRODUCTION</label>
                                                        </div>
                                                    </div>

                                                    <!-- Engineering Manager -->
                                                    <div class="input-group input-group-sm mb-2 align-items-center">
                                                        <div class="input-group-prepend" style="min-width: 110px;">
                                                            <span class="input-group-text w-100">QC Manager:</span>
                                                        </div>
                                                        <input type="text" class="form-control line-input" name="f_dispostion_qcm">
                                                        <div class="input-group-prepend ms-2" style="min-width: 50px;">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control line-input" name="f_dispostion_qcm_date" style="max-width: 160px;">
                                                    </div>


                                                    <!-- Process Engineering -->
                                                    <div class="input-group input-group-sm mb-2 align-items-center">
                                                        <div class="input-group-prepend" style="min-width: 110px;">
                                                            <span class="input-group-text w-100">QC Supervisor</span>
                                                        </div>
                                                        <input type="text" class="form-control line-input" name="f_dispostion_qcs">
                                                        <div class="input-group-prepend ms-2" style="min-width: 50px;">
                                                            <span class="input-group-text w-100">Date:</span>
                                                        </div>
                                                        <input type="date" class="form-control line-input" name="f_dispostion_qcs_date" style="max-width: 160px;">
                                                    </div>


                                                    <!-- Remarks -->
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Remarks:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="f_dispostion_qcm_remarks">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 ml-2">
                                                <span><strong>NOTE:</strong> Only QC & Engineering Manager will provide final disposition</span>
                                            </div>

                                        </div>
                                </div>
                            </div>

                            {{-- H. DEFINE & VERIFY ROOT CAUSE & ESCAPE CAUSE --}}
                            <div class="row hHeaderClass" hidden>
                                <div class="col-sm-12">
                                        <div id="hToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <label class="fw-bold">
                                                <span id="hHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                                H. DEFINE & VERIFY ROOT CAUSE & ESCAPE CAUSE
                                            </label>

                                        </div>

                                        <div class="content hHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                            CONTENT HERE
                                        </div>
                                </div>
                            </div>

                            {{-- I. CHOOSE AND VERIFY --}}
                            <div class="row iHeaderClass" hidden>
                                <div class="col-sm-12">
                                        <div id="iToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <label class="fw-bold">
                                                <span id="iHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                                    I. CHOOSE & VERIFY PERMANENT CORRECTIVE/PREVENTIVE ACTION FOR ROOT CAUSE & ESCAPE CAUSE <br>
                                                    <small>(Responsible person, due date)</small>
                                            </label>
                                        </div>
                                        <div class="content iHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                            CONTENT HERE
                                        </div>
                                </div>
                            </div>

                            {{-- J. VERIFICATION & CONFRIMATION --}}
                            <div class="row jHeaderClass" hidden>
                                <div class="col-sm-12">
                                        <div id="jToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                            <label class="fw-bold">
                                                <span id="jHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                                    J. VERIFICATION & CONFIRMATION DERIVED ACTIONS
                                                    <small>(Refer to CAPA monitoring report)</small>
                                            </label>
                                        </div>
                                        <div class="content jHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                            CONTENT HERE
                                        </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" id="btnSaveDefectEscalation" class="btn btn-primary"><i id="iBtnSaveDefectEscalationIcon" class="fa fa-check"></i> Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
    <!-- /.modal -->

        <!-- Modal Scan PRODUCTION ID -->
<div class="modal fade" id="modalScanProductionId" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
            <span id="scanProdIdBody">Please Scan ID...</span>
            <br>
            <br>
            <h1><i id="scanProdIdIcon" class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            {{-- <input type="text" id="txtSearchPoNumber" class="hidden_scanner_input"> --}}
            <input type="text" id="txtSearchProdId">
        </div>
        </div>
    </div>
</div>
    <!-- /.Modal -->

    <div class="modal fade" id="modalGenDefectToPrint">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i class="fa fa-qrcode"></i> DEFECT ESCALATION QR STICKER</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <center>
                <div class="row">
                  <div class="col-sm-6">
                    <b>PO # | Product Name</b>
                      <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                                ->size(150)->errorCorrection('H')
                                ->generate('0')) !!}" id="imgGenDefectPoNoBarcode" style="max-width: 200px; min-width: 200px;">
                      <br>
                      <label id="lblDefectPoNo">...</label> <br>
                      <label id="lblRuncardProductName">...</label> <br>
                  </div>
                  <div class="col-sm-6">
                    <b>Defect Esc. Details</b>
                      <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                                ->size(150)->errorCorrection('H')
                                ->generate('0')) !!}" id="imgGenDefectCtrlNoBarcode" style="max-width: 200px; min-width: 200px;">
                      <br>
                      <label id="lblStation">...</label> <br>
                      <label id="lblDefectCtrlNo">...</label> <br>
                      <label id="lblDefectType">...</label> <br>
                  </div>
                </div>
              </center>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" id="btnPrintDefectBarcode" class="btn btn-primary"><i id="iBtnPrintDefectBarcodeIcon" class="fa fa-print"></i> Print</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>


<!-- /.content-wrapper -->
@endsection

@section('js_content')

<style>
    table td, table th {
        padding: 4px !important;
        vertical-align: middle;
    }
    input[type="radio"] {
        transform: scale(0.9);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        display: block;
        max-height: 68px;
        overflow-y: auto;
    }
</style>

<!-- <script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script> -->
<script src="{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/js/deployJava.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2').select2();

    //Initialize Select2 ElementsW
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    let poNumber = '';
    let deviceName = '';
    let isViewingDefect = false;

    let defectEscalationDataTable = $("#tblDefectEscalation").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "get_defect_runcard_details",
            type: "GET",
            data: function (d) {
                let po = $('#txt_po_number_lbl').val();
                // console.log("Sending param.po_num:", po);
                d.po_num = po;
            }
        },
        columns: [
            { data: "action", orderable: false, searchable: false },
            { data: "status" },
            { data: "defect_ctrl" },
            { data: "runcard_no" },
            { data: "defect_classification" },
            { data: "type_of_defect" },
            { data: "in_charge" },
            { data: "position" },
            { data: "shift" },
            { data: "station" },
            { data: "datetime" }
        ]
    });

    GetUserListForDefect($("#txtSelectUser"));
    GetUserList($(".selectUser"));
    GetAssemblyLinesForDefectEscalation($(".sel-assembly-lines"));

    $("#txtDefects").trigger('change');
    $("#txtSelectUser").trigger('change');
    // $("#txtSelectUserMan").trigger('change');
    $("#txtRuncardNumber").trigger('change');

    $(".btnSearchPoNo").click(function(){
        $("#txtSearchPoNumber").val('');
        $("#scanPOTransLotBody").text('Please Scan the PO code.');
        $('#modalScanPoNumber').attr('data-formid', 'search_po_number').modal('show');
    });

    // $(".btnScanPVerificationId").click(function(){
    //     $("#txtSearchProdId").val('');
    //     $('#modalScanProductionId').modal('show');
    //     $("#scanProdIdBody").text('Please Scan ID...');
    //     // console.log(('scan operator id clicked'));
    // });

    let scanContext ='';

    $(".btnScanVerificationId").click(function () {
        scanContext = $(this).data("type");
        $("#txtSearchProdId").val('');
        $("#scanProdIdBody").text('Please Scan ID...');
        $('#modalScanProductionId').modal('show');
    });

    $('#modalScanProductionId').on('shown.bs.modal', function (e) {
        $('#txtSearchProdId').focus();
        $(document).on('keypress',function(e){
            scannedId = $('#txtSearchProdId').val();
            if( e.keyCode == 13){
                if(scannedId != ''){
                    // console.log('scanContext', scanContext);
                    const now = new Date();
                // Format as YYYY-MM-DD HH:mm:ss
                    const formattedDateTime = now.getFullYear() + '-' +
                    String(now.getMonth() + 1).padStart(2, '0') + '-' +
                    String(now.getDate()).padStart(2, '0') + ' ' +
                    String(now.getHours()).padStart(2, '0') + ':' +
                    String(now.getMinutes()).padStart(2, '0') + ':' +
                    String(now.getSeconds()).padStart(2, '0');

                    $.ajax({
                        url: "get_verification_user",
                        type: 'GET',
                        data: {
                            scanned_id: scannedId,
                            scan_context: scanContext
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                if (scanContext === 'P') {
                                    $('#txtPVerificationUser').val(response.user.name);
                                    $('#txtPVerificationUserId').val(response.user.id);
                                    $('#txtPVerificationDate').val(formattedDateTime);
                                } else if (scanContext === 'E') {
                                    $('#txtEVerificationUser').val(response.user.name);
                                    $('#txtEVerificationUserId').val(response.user.id);
                                    $('#txtEVerificationDate').val(formattedDateTime);
                                } else if (scanContext === 'Q') {
                                    $('#txtQVerificationUser').val(response.user.name);
                                    $('#txtQVerificationUserId').val(response.user.id);
                                    $('#txtQVerificationDate').val(formattedDateTime);
                                }else if (scanContext === 'DP') {
                                    $('#txtPDispositionUser').val(response.user.name);
                                    $('#txtPDispositionUserID').val(response.user.id);
                                    $('#txtPDispositionDate').val(formattedDateTime);
                                }else if (scanContext === 'EP') {
                                    $('#txtEDispositionUser').val(response.user.name);
                                    $('#txtEDispositionUserID').val(response.user.id);
                                    $('#txtEDispositionDate').val(formattedDateTime);
                                }else if (scanContext === 'QP') {
                                    $('#txtQDispositionUser').val(response.user.name);
                                    $('#txtQDispositionUserID').val(response.user.id);
                                    $('#txtQDispositionDate').val(formattedDateTime);
                                }else{

                                }
                                $('#modalScanProductionId').modal('hide');

                            } else {
                                if(scanContext === 'P'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }else if(scanContext === 'E'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }else if(scanContext === 'Q'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }else if(scanContext === 'DP'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }else if(scanContext === 'EP'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }else if(scanContext === 'QP'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }

                                // alert('Employee not found.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while searching for the employee.');
                        }
                    });


                } else {
                    alert('Please scan.');
                }
            }
        });
    });



    $('#modalScanPoNumber').on('shown.bs.modal', function (e) {
        $('#txtSearchPoNumber').focus();
        $(document).on('keypress',function(e){
            // console.log('enter');
            poNumber = $('#txtSearchPoNumber').val();
            if( e.keyCode == 13){
                console.log('poNumber', poNumber);
                if(poNumber != ''){
                    $.ajax({
                        url: "get_defect_po_details",
                        type: 'get',
                        data: {
                            _token: '{{ csrf_token() }}',
                            po_number: poNumber
                        },
                        success: function(data) {
                            if(data.status == 'success') {
                                $('#txt_po_number_lbl').val(data['data']['po_no']);
                                $('#txt_device_name_lbl').val(data['data']['device_name']);
                                $('#txt_device_code_lbl').val(data['data']['device_code']);
                                $('#txt_po_qty_lbl').val(data['data']['po_qty']);

                                defectEscalationDataTable.ajax.reload();
                                // Close the modal
                                $('#modalScanPoNumber').modal('hide');

                            } else {
                                alert('PO number not found. Please try again.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while searching for the PO number.');
                        }
                    });
                } else {
                    alert('Please scan a valid PO number.');
                }
            }
        });

    });

    $('#btnShowAddProdRuncard').click(function(){
        if($("#txt_po_number_lbl").val() == "" || $("#txt_po_number_lbl").val() == null){
                toastr.warning('PO not found!');
        }else{
            $('#modalAddDefectEscalation').modal('show');
        }

        // $('#txtModType').on('change', function () {
            const modType = $(this).val();
            const cboElement = $('#txtRuncardNumber');

            // if (modType === "2") {
                // console.log('MOD TYPE = 2');
                getRuncardWithDefectv2(cboElement, poNumber); // make sure poNumber is defined in scope
            // } else {
            //     // console.log('MOD TYPE != 2');
            //     $('#txtLotNo').prop('readonly', false);

            //     // Set dropdown to N/A
            //     let result = `<option value="">N/A</option>`;
            //     cboElement.html(result);
            //     cboElement.val("").trigger('change.select2');

            //     // Set all related fields to 'N/A'
            //     $('#txtPairNo').val('N/A');
            //     $('#txtDMoldNo').val('N/A');
            //     $('#txtLine').val('N/A');
            //     $('#txtLineId').val('N/A');

            //     // Optionally clear hidden fields
            //     $('#txtStationModId').val('');
            //     $('#txtRuncardStationId').val('');
            //     $('#txtRuncardId').val('');
            // }
        // });

        $('#txtPONumber').val($('#txt_po_number_lbl').val());
        $('#txtProductName').val($('#txt_device_name_lbl').val());

    });

    $('#txtLine').on('change', function () {
        var text = $(this).find("option:selected").text();
        $('#txtLineT').val(text);
    });


    $('#modalAddDefectEscalation').on('shown.bs.modal', function () {

        $(document).off('click', '.addRowBtn').on('click', '.addRowBtn', function () {
            let $tbody = $("#areaTable tbody");
            let row = `<tr>
                <td><strong>Area</strong></td>
                <td><input type="text" name="area1[]" class="form-control area1"></td>
                <td><input type="text" name="area2[]" class="form-control area2"></td>
                <td><input type="text" name="area3[]" class="form-control area3"></td>
                <td><input type="text" name="area4[]" class="form-control area4"></td>
                <td><input type="text" name="area5[]" class="form-control area5"></td>
                <td class="align-middle">
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-circle addRowBtn" style="width:25px; height:25px; padding:0;">+</button>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle removeRowBtn" style="width:25px; height:25px; padding:0;">x</button>
                </td>
            </tr>`;

            $tbody.append(row);
        });

        // Same for remove
        $(document).off('click', '.removeRowBtn').on('click', '.removeRowBtn', function () {
            $(this).closest('tr').remove();
        });
        $('#txtRuncardNumber').on('change', function () {
        //     if (isViewingDefect) return;

            const modStationId = $(this).val();
            const $selectedOption = $('#txtRuncardNumber option:selected');

            const runcardId = $selectedOption.data('rid');
            const runcardStationId = $selectedOption.data('rsid');
        //     const pairNo = $selectedOption.data('pair');
        //     const dieNo = $selectedOption.data('die');
        //     const lineId = $selectedOption.data('line');

        //     // Safety check to prevent AJAX if lineId is null/undefined
        //     if (!lineId) return;

            $('#txtStationModId').val(modStationId);
            $('#txtRuncardStationId').val(runcardStationId);
                $('#txtRuncardId').val(runcardId);
        //     $('#txtPairNo').val(pairNo);
        //     $('#txtDMoldNo').val(dieNo);
        //     $('#txtLotNo').val("N/A").prop('readonly', true);

        //     $.ajax({
        //         type: "GET",
        //         url: "get_assembly_line_with_defect",
        //         data: { assembly_line_id: lineId },
        //         dataType: "json",
        //         success: function (response) {
        //             $('#txtLine').val(response.name);
        //             $('#txtLineId').val(response.id);
        //         },
        //         error: function () {
        //             toastr.error('Error fetching assembly line information.');
        //             $('#txtLine').val('');
        //             $('#txtLineId').val('');
        //         }
        //     });
        });
    });



    document.getElementById('txtStation').addEventListener('change', function () {
        const otherStationContainer = document.getElementById('otherStationContainer');
        if (this.value === '7' || this.value === '3' || this.value === '4' || this.value === '2') {
            otherStationContainer.style.display = 'block';
        } else {
            otherStationContainer.style.display = 'none';
            document.getElementById('txtOtherStation').value = '';
        }
    });

    document.querySelectorAll('input[name="d_classification"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const selectedClassification = document.querySelector('input[name="d_classification"]:checked');

            let selectedText = $(this).next('label').text(); // get label text of selected radio
            $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(selectedText + ' Qty');

            if (selectedClassification) {
                const defectClassification = selectedClassification.value;
                if(defectClassification == 1){
                    $('.modeOfDefectClass').removeAttr('hidden');
                    $('.criticalDefectClass').removeAttr('hidden');
                    $('.otherDefectClass').attr('hidden', true);

                }
                else if(defectClassification == 2){
                    $('.modeOfDefectClass').removeAttr('hidden');
                    $('.otherDefectClass').removeAttr('hidden');
                    $('.criticalDefectClass').removeAttr('hidden');
                }
                else if(defectClassification == 3){
                    $('.modeOfDefectClass').removeAttr('hidden');
                    $('.criticalDefectClass').removeAttr('hidden');
                    $('.otherDefectClass').removeAttr('hidden');

                }
                else if(defectClassification == 4){
                    $('.modeOfDefectClass').removeAttr('hidden');
                    $('.criticalDefectClass').removeAttr('hidden');
                    $('.otherDefectClass').removeAttr('hidden');

                }
                else if(defectClassification == 5){
                    $('.modeOfDefectClass').removeAttr('hidden');
                    $('.criticalDefectClass').removeAttr('hidden');
                    $('.otherDefectClass').removeAttr('hidden');
                }

                getModOfDefect($("#txtDefects"), defectClassification)
            }
            // console.log(defectClassification);
        });
    });


    $('#txtSelectUser').change(function(){
        var userId = $(this).val();
        var userPosition = $('#txtSelectUser option:selected').data('position');

        if(userPosition == 4){
            $('#txtOperator').prop('checked', true);
            $('#txtOperator').prop('disabled', false);
            $('#txtMaterialHandler').prop('disabled', true);
            $('#txtQCInspector').prop('disabled', true);
            $('#txtTechnician').prop('disabled', true);
        }else if(userPosition == 3){
            $('#txtMaterialHandler').prop('checked', true);
            $('#txtMaterialHandler').prop('disabled', false);
            $('#txtOperator').prop('disabled', true);
            $('#txtQCInspector').prop('disabled', true);
            $('#txtTechnician').prop('disabled', true);

        }else if(userPosition == 10 || userPosition == 5){
            $('#txtQCInspector').prop('checked', true);
            $('#txtQCInspector').prop('disabled', false);
            $('#txtOperator').prop('disabled', true);
            $('#txtMaterialHandler').prop('disabled', true);
            $('#txtTechnician').prop('disabled', true);

        }else if(userPosition == 11){
            $('#txtTechnician').prop('checked', true);
            $('#txtTechnician').prop('disabled', false);
            $('#txtOperator').prop('disabled', true);
            $('#txtMaterialHandler').prop('disabled', true);
            $('#txtQCInspector').prop('disabled', true);
        }else{
            $('#txtOperator').prop('checked', false);
            $('#txtMaterialHandler').prop('checked', false);
            $('#txtQCInspector').prop('checked', false);
            $('#txtTechnician').prop('checked', false);

            $('#txtOperator').prop('disabled', false);
            $('#txtMaterialHandler').prop('disabled', false);
            $('#txtQCInspector').prop('disabled', false);
            $('#txtTechnician').prop('disabled', false);
        }

    });



    $('#formAddDefectEscalation').submit(function (e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "add_defect_escalation",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                // console.log(response['result']);
                if(response['result'] == 1){
                    toastr.success('Defect Escalation successfully added!');
                    $('#modalAddDefectEscalation').modal('hide');
                    $('#formAddDefectEscalation')[0].reset();
                    // $('.select2').val('').trigger('change');
                    defectEscalationDataTable.ajax.reload();
                }
            },
            error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    });

    $(document).on('click', '.btn_view_defect_details, .btn_edit_defect_details', function () {
        isViewingDefect = true;
        const isViewing = $(this).hasClass('btn_view_defect_details'); // true = view, false = edit
        const defectId = $(this).data('defect-id');

        $('#txtDefectId').val(defectId);
        $('#modalAddDefectEscalation').modal('show');

        $.ajax({
            type: "GET",
            url: "view_defect_details",
            data: { defect_id: defectId },
            dataType: "json",
            success: function (response) {
                if (response.status !== 'success') {
                    return alert('Defect not found.');
                }

                const data = response.data;
                const userId = data.de_user;
                const userName = data.operator_info.name;
                const manUserDetails = response.manUserDetails;
                const machineUserDetails = response.machineUserDetails;
                const materialUserDetails = response.materialUserDetails;
                const methodUserDetails = response.methodUserDetails;
                const environmentUserDetails = response.environmentUserDetails;

                // console.log('manUserDetails', manUserDetails)

                const ctrlNumber = data.defect_ctrl_number;
                const ctrlText = data.defect_ctrl_txt;
                const defectCtrlNumber = ctrlText +'-'+ctrlNumber;

                // $('#txtModType').val(data.mod_type).trigger('change').prop('disabled', true);

                // $('#txtModType').val(data.mod_type).trigger('change').prop('readonly', true).css({
                //     'pointer-events': 'none',
                //     'background-color': '#e9ecef'  // optional: Bootstrap-like disabled look
                // });
                // console.log('defect_classification', data.defect_classification);
                // if(data.mod_type == 2){
                    const runcardId = data.prod_runcard_id;
                    const runcardText = data.production_runcard.runcard_no;

                    if ($('#txtRuncardNumber option[value="' + runcardId + '"]').length === 0) {
                    $('#txtRuncardNumber').append(new Option(runcardText, runcardId, true, true));
                    }

                //     // Set the value and trigger 'change' event, disable the select
                    $('#txtRuncardNumber').val(runcardId).trigger('change').prop('disabled', true);


                    $('#txtRuncardNumber').val(runcardId).trigger('change').prop('readonly', true, isViewing).css({
                        'pointer-events': 'none',
                        'background-color': '#e9ecef'  // optional: Bootstrap-like disabled look
                    });

                //     // console.log('mod_id', data.mod_id);
                //     // Hidden fields
                    $('#txtRuncardId').val(runcardId);
                    $('#txtRuncardStationId').val(data.prod_runcard_station_id);
                    $('#txtStationModId').val(data.prod_runcard_station_mod_id);
                    // $('#txtLine').val(data.line_id);
                    $('#txtLine').val(data.line_id).trigger('change').prop('disabled', isViewing);
                //     $('#txtLine').val(data.assembly_line.name).prop('readonly', true);

                // }else{
                //     $('#txtRuncardNumber').html('<option value="">N/A</option>').val('').prop('disabled', true);
                //     $('#txtRuncardId').val('');
                //     $('#txtRuncardStationId').val('');
                //     $('#txtStationModId').val('');
                //     $('#txtLineId').val('');
                //     $('#txtLine').val('N/A').prop('readonly', true);
                //     $('#txtDefects').val('').prop('disabled', true);
                // }

                 // User select
                 if ($('#txtSelectUser option[value="' + userId + '"]').length === 0) {
                        $('#txtSelectUser').append(new Option(userName, userId, true, true));
                    }
                    // $('#txtSelectUser').val(userId).trigger('change').prop('disabled', isViewing);
                    $('#txtSelectUser').val(userId).trigger('change').prop('readonly', true, isViewing).css({
                        'pointer-events': 'none',
                        'background-color': '#e9ecef'  // optional: Bootstrap-like disabled look
                    });


                    // Fixed Readonly/Disabled fields

                    $('#txtDefectCtrlNumber').val(defectCtrlNumber).prop('readonly', true); // control number
                    $('#txtDate').val(data.de_date).prop('readonly', true); // date
                    $('#txtTime').val(data.de_time).prop('readonly', true); // time
                    $('#txtPairNo').val(data.de_pair_no).prop('readonly', isViewing); // pair no
                    $('#txtDMoldNo').val(data.de_dm_no).prop('readonly', isViewing); // die mold no
                    $('#txtLotNo').val(data.de_lot_no).prop('readonly', isViewing); // lot no

                    // Additional
                    $('#txtPONumber').val($('#txt_po_number_lbl').val());
                    $('#txtProductName').val($('#txt_device_name_lbl').val());

                    $('#txtStation').val(data.de_station).trigger('change').prop('disabled', isViewing);
                    if (data.de_station == 7 || data.de_station == 3 || data.de_station == 4 || data.de_station == 2) {
                        $('#otherStationContainer').show();
                        $('#txtOtherStation').val(data.de_other_station).prop('readonly', isViewing);
                    } else {
                        $('#otherStationContainer').hide();
                        $('#txtOtherStation').val('').prop('readonly', true);
                    }


                    // Shift radio
                    $("#shiftA").prop('checked', data.de_shift == 1).prop('disabled', isViewing && data.de_shift !== 1);
                    $("#shiftB").prop('checked', data.de_shift == 2).prop('disabled', isViewing && data.de_shift !== 2);

                    // Occurrence radio
                    $("#txtOccurrence1Id").prop('checked', data.de_occurence == 1).prop('disabled', isViewing && data.de_occurence != 1);
                    $("#txtOccurrence2Id").prop('checked', data.de_occurence == 2).prop('disabled', isViewing && data.de_occurence != 2);
                    $("#txtOccurrence3Id").prop('checked', data.de_occurence == 3).prop('disabled', isViewing && data.de_occurence != 3);

                    $("#txtDefectClassification1Id").prop('checked', data.defect_classification == 1).prop('disabled', isViewing && data.defect_classification != 1);
                    $("#txtDefectClassification2Id").prop('checked', data.defect_classification == 2).prop('disabled', isViewing && data.defect_classification != 2);
                    $("#txtDefectClassification3Id").prop('checked', data.defect_classification == 3).prop('disabled', isViewing && data.defect_classification != 3);
                    $("#txtDefectClassification4Id").prop('checked', data.defect_classification == 4).prop('disabled', isViewing && data.defect_classification != 4);
                    $("#txtDefectClassification5Id").prop('checked', data.defect_classification == 5).prop('disabled', isViewing && data.defect_classification != 5);


                    $('#txtDetailedDescription').val(data.detailed_description).prop('readonly', isViewing);

                    if (data.defect_classification == 1) {
                        $('.modeOfDefectClass').removeAttr('hidden');
                        $('.criticalDefectClass').removeAttr('hidden');
                        $('.otherDefectClass').attr('hidden', true);

                        // ✅ Pass a callback to preselect and disable the dropdown
                        // getModOfDefect($("#txtDefects"), 1, function () {
                        //     $('#txtDefects').val(data.mod_id).trigger('change').prop('disabled', isViewing);
                        // });

                        getModOfDefect($("#txtDefects"), 1, function () {
                            let defectIds = (data.mod_id || "").split(",");

                            $('#txtDefects')
                                .val(defectIds)      // preselect multiple values
                                .trigger('change')   // refresh Select2 UI
                                .prop('disabled', isViewing);
                        });

                    } else if(data.defect_classification == 2) {
                        let labelText = $('input[name="d_classification"][value="2"]').next('label').text();
                        $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');

                        $('.modeOfDefectClass').removeAttr('hidden');
                        $('.criticalDefectClass').removeAttr('hidden');
                        $('.otherDefectClass').removeAttr('hidden');
                        $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                        getModOfDefect($("#txtDefects"), 2, function () {
                            let defectIds = (data.mod_id || "").split(",");

                            $('#txtDefects')
                                .val(defectIds)      // preselect multiple values
                                .trigger('change')   // refresh Select2 UI
                                .prop('disabled', isViewing);
                        });
                    }else if(data.defect_classification == 3) {
                        let labelText = $('input[name="d_classification"][value="3"]').next('label').text();
                        $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');

                        $('.modeOfDefectClass').removeAttr('hidden');
                        $('.criticalDefectClass').removeAttr('hidden');
                        $('.otherDefectClass').removeAttr('hidden');
                        $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                        getModOfDefect($("#txtDefects"), 3, function () {
                            let defectIds = (data.mod_id || "").split(",");

                            $('#txtDefects')
                                .val(defectIds)      // preselect multiple values
                                .trigger('change')   // refresh Select2 UI
                                .prop('disabled', isViewing);
                        });
                    }else if(data.defect_classification == 4) {
                        // console.log('testttt');
                        let labelText = $('input[name="d_classification"][value="4"]').next('label').text();
                        $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');

                        $('.modeOfDefectClass').removeAttr('hidden');
                        $('.criticalDefectClass').removeAttr('hidden');
                        $('.otherDefectClass').removeAttr('hidden');
                        $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                        getModOfDefect($("#txtDefects"), 4, function () {
                            let defectIds = (data.mod_id || "").split(",");

                            $('#txtDefects')
                                .val(defectIds)      // preselect multiple values
                                .trigger('change')   // refresh Select2 UI
                                .prop('disabled', isViewing);
                        });
                    }else if(data.defect_classification == 5) {
                        let labelText = $('input[name="d_classification"][value="5"]').next('label').text();
                        $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');

                        $('.modeOfDefectClass').removeAttr('hidden');
                        $('.criticalDefectClass').removeAttr('hidden');
                        $('.otherDefectClass').removeAttr('hidden');
                        $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                        getModOfDefect($("#txtDefects"), 5, function () {
                            let defectIds = (data.mod_id || "").split(",");

                            $('#txtDefects')
                                .val(defectIds)      // preselect multiple values
                                .trigger('change')   // refresh Select2 UI
                                .prop('disabled', isViewing);
                        });
                    }

                   let areaCols = [
                        (data.area1 || "").split(","),
                        (data.area2 || "").split(","),
                        (data.area3 || "").split(","),
                        (data.area4 || "").split(","),
                        (data.area5 || "").split(",")
                    ];



                    // Find the max rows needed (longest column)
                    let maxRows = Math.max(...areaCols.map(col => col.length));

                    let $tbody = $("#areaTable tbody");
                    $tbody.empty();

                    for (let r = 0; r < maxRows; r++) {
                        let row = `<tr>
                            <td><strong>Area</strong></td>
                            <td><input type="text" name="area1[]" class="form-control area1" value="${areaCols[0][r] || ''}" ${isViewing ? 'readonly' : ''}></td>
                            <td><input type="text" name="area2[]" class="form-control area2" value="${areaCols[1][r] || ''}" ${isViewing ? 'readonly' : ''}></td>
                            <td><input type="text" name="area3[]" class="form-control area3" value="${areaCols[2][r] || ''}" ${isViewing ? 'readonly' : ''}></td>
                            <td><input type="text" name="area4[]" class="form-control area4" value="${areaCols[3][r] || ''}" ${isViewing ? 'readonly' : ''}></td>
                            <td><input type="text" name="area5[]" class="form-control area5" value="${areaCols[4][r] || ''}" ${isViewing ? 'readonly' : ''}></td>`;

                        if (!isViewing) {
                            row += `<td class="align-middle">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-circle addRowBtn" style="width:25px; height:25px; padding:0;">+</button>
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle removeRowBtn" style="width:25px; height:25px; padding:0;">x</button>
                                    </td>`;
                        } else {
                            row += `<td></td>`;
                        }

                        row += `</tr>`;
                        $tbody.append(row);
                    }

                    if(data.defect_classification == 1){
                        $('.bHeaderClass').removeAttr('hidden');

                        if(data.problem_verificaiton_details != null){
                            // verification result
                            if( data.problem_verificaiton_details.prod_verification_user != null){
                                $('#txtPVerificationUser').val(data.problem_verificaiton_details.prod_verification_user.name).prop('readonly' ,true);
                                $('#txtPVerificationUserId').val(data.problem_verificaiton_details.prod_verification_user.id).prop('readonly' ,true);
                                $("#txtPVerificationResult1").prop('checked', data.problem_verificaiton_details.vr_prod_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.vr_prod_result != 1);
                                $("#txtPVerificationResult2").prop('checked', data.problem_verificaiton_details.vr_prod_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.vr_prod_result != 2);
                                $('#txtPVerificationDate').val(data.problem_verificaiton_details.vr_prod_date).prop('readonly');
                                $('#txtPVerificationRemarks').val(data.problem_verificaiton_details.vr_prod_remarks).prop('readonly', isViewing);
                            }

                            if( data.problem_verificaiton_details.engr_verification_user != null){
                                $('#txtEVerificationUser').val(data.problem_verificaiton_details.engr_verification_user.name).prop('readonly' ,true);
                                $('#txtEVerificationUserId').val(data.problem_verificaiton_details.engr_verification_user.id).prop('readonly' ,true);
                                $("#txtEVerificationResult1").prop('checked', data.problem_verificaiton_details.vr_engr_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.vr_engr_result != 1);
                                $("#txtEVerificationResult2").prop('checked', data.problem_verificaiton_details.vr_engr_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.vr_engr_result != 2);
                                $('#txtEVerificationDate').val(data.problem_verificaiton_details.vr_engr_date).prop('readonly');
                                $('#txtEVerificationRemarks').val(data.problem_verificaiton_details.vr_engr_remarks).prop('readonly', isViewing);
                            }

                            if( data.problem_verificaiton_details.qc_verification_user != null){
                                $('#txtQVerificationUser').val(data.problem_verificaiton_details.qc_verification_user.name).prop('readonly' ,true);
                                $('#txtQVerificationUserId').val(data.problem_verificaiton_details.qc_verification_user.id).prop('readonly' ,true);
                                $("#txtQVerificationResult1").prop('checked', data.problem_verificaiton_details.vr_qc_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.vr_qc_result != 1);
                                $("#txtQVerificationResult2").prop('checked', data.problem_verificaiton_details.vr_qc_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.vr_qc_result != 2);
                                $('#txtQVerificationDate').val(data.problem_verificaiton_details.vr_qc_date).prop('readonly');
                                $('#txtQVerificationRemarks').val(data.problem_verificaiton_details.vr_qc_remarks).prop('readonly', isViewing);
                            }

                            //disposition result

                            if( data.problem_verificaiton_details.d_prod_user != null){
                                $('#txtPDispositionUser').val(data.problem_verificaiton_details.d_prod_user.name).prop('readonly' ,true);
                                $('#txtPDispositionUserID').val(data.problem_verificaiton_details.d_prod_user.id).prop('readonly' ,true);
                                $("#txtDisposition1").prop('checked', data.problem_verificaiton_details.d_prod_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.d_prod_result != 1);
                                $("#txtDisposition2").prop('checked', data.problem_verificaiton_details.d_prod_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.d_prod_result != 2);
                                $("#txtDisposition3").prop('checked', data.problem_verificaiton_details.d_prod_result == 3).prop('disabled', isViewing && data.problem_verificaiton_details.d_prod_result != 3);
                                $('#txtPDispositionDate').val(data.problem_verificaiton_details.d_prod_date).prop('readonly');
                                $('#txtPDispositionRemarks').val(data.problem_verificaiton_details.d_prod_remarks).prop('readonly', isViewing);
                            }

                            if( data.problem_verificaiton_details.d_engr_user != null){
                                $('#txtEDispositionUser').val(data.problem_verificaiton_details.d_engr_user.name).prop('readonly' ,true);
                                $('#txtEDispositionUserID').val(data.problem_verificaiton_details.d_engr_user.id).prop('readonly' ,true);
                                $("#txtEDisposition1").prop('checked', data.problem_verificaiton_details.d_engr_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.d_engr_result != 1);
                                $("#txtEDisposition2").prop('checked', data.problem_verificaiton_details.d_engr_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.d_engr_result != 2);
                                $("#txtEDisposition3").prop('checked', data.problem_verificaiton_details.d_engr_result == 3).prop('disabled', isViewing && data.problem_verificaiton_details.d_engr_result != 3);
                                $('#txtEDispositionDate').val(data.problem_verificaiton_details.d_engr_date).prop('readonly');
                                $('#txtEDispositionRemarks').val(data.problem_verificaiton_details.d_engr_remarks).prop('readonly', isViewing);
                            }

                            if( data.problem_verificaiton_details.d_qc_user != null){
                                $('#txtQDispositionUser').val(data.problem_verificaiton_details.d_qc_user.name).prop('readonly' ,true);
                                $('#txtQDispositionUserID').val(data.problem_verificaiton_details.d_qc_user.id).prop('readonly' ,true);
                                $("#txtQDisposition1").prop('checked', data.problem_verificaiton_details.d_qc_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.d_qc_result != 1);
                                $("#txtQDisposition2").prop('checked', data.problem_verificaiton_details.d_qc_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.d_qc_result != 2);
                                $("#txtQDisposition3").prop('checked', data.problem_verificaiton_details.d_qc_result == 3).prop('disabled', isViewing && data.problem_verificaiton_details.d_qc_result != 3);
                                $('#txtQDispositionDate').val(data.problem_verificaiton_details.d_qc_date).prop('readonly');
                                $('#txtQDispositionRemarks').val(data.problem_verificaiton_details.d_qc_remarks).prop('readonly', isViewing);
                            }

                            if(data.problem_verificaiton_details.prod_verification_user != null &&
                                data.problem_verificaiton_details.engr_verification_user != null &&
                                data.problem_verificaiton_details.qc_verification_user != null &&
                                data.problem_verificaiton_details.d_qc_user != null &&
                                data.problem_verificaiton_details.d_engr_user != null &&
                                data.problem_verificaiton_details.d_prod_user != null){

                                    console.log('iad', data.immediate_action_details);
                                    $('.cHeaderClass').removeAttr('hidden');
                                if(data.immediate_action_details){

                                    $('#txtActionDetailsMan').val(data.immediate_action_details.at_man_action_details).prop('readonly', isViewing);
                                    $('#txtWhenMan').val(data.immediate_action_details.at_man_date).prop('readonly', isViewing);
                                    let $selectMan = $('#txtSelectUserMan');

                                    $.each(manUserDetails, function(id, name) {
                                        if ($selectMan.find(`option[value="${id}"]`).length === 0) {
                                            $selectMan.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });

                                    $selectMan.val(Object.keys(manUserDetails)).trigger('change');

                                    $('#txtActionDetailsMachine').val(data.immediate_action_details.at_machine_action_details).prop('readonly', isViewing);
                                    $('#txtWhenMachine').val(data.immediate_action_details.at_machine_date).prop('readonly', isViewing);
                                    let $selectMachine = $('#txtSelectUserMachine');

                                    $.each(machineUserDetails, function(id, name) {
                                        if ($selectMachine.find(`option[value="${id}"]`).length === 0) {
                                            $selectMachine.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });

                                    $selectMachine.val(Object.keys(machineUserDetails)).trigger('change');

                                    $('#txtActionDetailsMaterial').val(data.immediate_action_details.at_material_action_details).prop('readonly', isViewing);
                                    $('#txtWhenMaterial').val(data.immediate_action_details.at_material_date).prop('readonly', isViewing);
                                    let $selectMaterial = $('#txtSelectUserMaterial');


                                    $.each(materialUserDetails, function(id, name) {
                                        if ($selectMaterial.find(`option[value="${id}"]`).length === 0) {
                                            $selectMaterial.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });

                                    $selectMaterial.val(Object.keys(materialUserDetails)).trigger('change');

                                    $('#txtActionDetailsMethod').val(data.immediate_action_details.at_method_action_details).prop('readonly', isViewing);
                                    $('#txtWhenMethod').val(data.immediate_action_details.at_method_date).prop('readonly', isViewing);
                                    let $selectMethod = $('#txtSelectUserMethod');


                                    $.each(methodUserDetails, function(id, name) {
                                        if ($selectMethod.find(`option[value="${id}"]`).length === 0) {
                                            $selectMethod.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });

                                    $selectMethod.val(Object.keys(methodUserDetails)).trigger('change');

                                    $('#txtActionDetailsEnvironment').val(data.immediate_action_details.at_environment_action_details).prop('readonly', isViewing);
                                    $('#txtWhenEnvironment').val(data.immediate_action_details.at_environment_date).prop('readonly', isViewing);
                                    let $selectEnvironment = $('#txtSelectUserEnvironment');


                                    $.each(environmentUserDetails, function(id, name) {
                                        if ($selectEnvironment.find(`option[value="${id}"]`).length === 0) {
                                            $selectEnvironment.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });


                                    $selectEnvironment.val(Object.keys(environmentUserDetails)).trigger('change');
                                    $('.dHeaderClass').removeAttr('hidden');
                                }

                                if(data.containment_action_details){
                                    $('#txtWipTotalQty').val(data.containment_action_details.wip_total_qty).prop('readonly', isViewing);
                                    $('#txtWipOkQty').val(data.containment_action_details.wip_ok_qty).prop('readonly', isViewing);
                                    $('#txtWipNgQty').val(data.containment_action_details.wip_ng_qty).prop('readonly', isViewing);
                                    $('#txtWipMod').val(data.containment_action_details.wip_mod).prop('readonly', isViewing);
                                    $('#txtWipRemarks').val(data.containment_action_details.wip_remarks).prop('readonly', isViewing);

                                    $('#txtPfTotalQty').val(data.containment_action_details.pf_total_qty).prop('readonly', isViewing);
                                    $('#txtPfOkQty').val(data.containment_action_details.pf_ok_qty).prop('readonly', isViewing);
                                    $('#txtPfNgQty').val(data.containment_action_details.pf_ng_qty).prop('readonly', isViewing);
                                    $('#txtPfMod').val(data.containment_action_details.pf_mod).prop('readonly', isViewing);
                                    $('#txtPfRemarks').val(data.containment_action_details.pf_remarks).prop('readonly', isViewing);

                                    $('#txtOqcTotalQty').val(data.containment_action_details.qc_total_qty).prop('readonly', isViewing);
                                    $('#txtOqcOkQty').val(data.containment_action_details.qc_ok_qty).prop('readonly', isViewing);
                                    $('#txtOqcNgQty').val(data.containment_action_details.qc_ng_qty).prop('readonly', isViewing);
                                    $('#txtOqcMod').val(data.containment_action_details.qc_mod).prop('readonly', isViewing);
                                    $('#txtOqcRemarks').val(data.containment_action_details.qc_remarks).prop('readonly', isViewing);

                                    $('#txtPackingTotalQty').val(data.containment_action_details.p_total_qty).prop('readonly', isViewing);
                                    $('#txtPackingOkQty').val(data.containment_action_details.p_ok_qty).prop('readonly', isViewing);
                                    $('#txtPackingNgQty').val(data.containment_action_details.p_ng_qty).prop('readonly', isViewing);
                                    $('#txtPackingMod').val(data.containment_action_details.p_mod).prop('readonly', isViewing);
                                    $('#txtPackingRemarks').val(data.containment_action_details.p_remarks).prop('readonly', isViewing);

                                    $('#txtShippedTotalQty').val(data.containment_action_details.s_total_qty).prop('readonly', isViewing);
                                    $('#txtShippedOkQty').val(data.containment_action_details.s_ok_qty).prop('readonly', isViewing);
                                    $('#txtShippedNgQty').val(data.containment_action_details.s_ng_qty).prop('readonly', isViewing);
                                    $('#txtShippedMod').val(data.containment_action_details.s_mod).prop('readonly', isViewing);
                                    $('#txtShippedRemarks').val(data.containment_action_details.s_remarks).prop('readonly', isViewing);

                                }

                            }

                        }
                    }
                    else if(data.defect_classification == 2 || data.defect_classification == 3 ||
                        data.defect_classification == 4 || data.defect_classification == 5 && data.de_occurence == 2){
                            $('.bHeaderClass').removeAttr('hidden');

                            if(data.problem_verificaiton_details != null){
                            // verification result
                            if( data.problem_verificaiton_details.prod_verification_user != null){
                                $('#txtPVerificationUser').val(data.problem_verificaiton_details.prod_verification_user.name).prop('readonly' ,true);
                                $('#txtPVerificationUserId').val(data.problem_verificaiton_details.prod_verification_user.id).prop('readonly' ,true);
                                $("#txtPVerificationResult1").prop('checked', data.problem_verificaiton_details.vr_prod_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.vr_prod_result != 1);
                                $("#txtPVerificationResult2").prop('checked', data.problem_verificaiton_details.vr_prod_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.vr_prod_result != 2);
                                $('#txtPVerificationDate').val(data.problem_verificaiton_details.vr_prod_date).prop('readonly');
                                $('#txtPVerificationRemarks').val(data.problem_verificaiton_details.vr_prod_remarks).prop('readonly', isViewing);
                            }

                            if( data.problem_verificaiton_details.engr_verification_user != null){
                                $('#txtEVerificationUser').val(data.problem_verificaiton_details.engr_verification_user.name).prop('readonly' ,true);
                                $('#txtEVerificationUserId').val(data.problem_verificaiton_details.engr_verification_user.id).prop('readonly' ,true);
                                $("#txtEVerificationResult1").prop('checked', data.problem_verificaiton_details.vr_engr_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.vr_engr_result != 1);
                                $("#txtEVerificationResult2").prop('checked', data.problem_verificaiton_details.vr_engr_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.vr_engr_result != 2);
                                $('#txtEVerificationDate').val(data.problem_verificaiton_details.vr_engr_date).prop('readonly');
                                $('#txtEVerificationRemarks').val(data.problem_verificaiton_details.vr_engr_remarks).prop('readonly', isViewing);
                            }

                            if( data.problem_verificaiton_details.qc_verification_user != null){
                                $('#txtQVerificationUser').val(data.problem_verificaiton_details.qc_verification_user.name).prop('readonly' ,true);
                                $('#txtQVerificationUserId').val(data.problem_verificaiton_details.qc_verification_user.id).prop('readonly' ,true);
                                $("#txtQVerificationResult1").prop('checked', data.problem_verificaiton_details.vr_qc_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.vr_qc_result != 1);
                                $("#txtQVerificationResult2").prop('checked', data.problem_verificaiton_details.vr_qc_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.vr_qc_result != 2);
                                $('#txtQVerificationDate').val(data.problem_verificaiton_details.vr_qc_date).prop('readonly');
                                $('#txtQVerificationRemarks').val(data.problem_verificaiton_details.vr_qc_remarks).prop('readonly', isViewing);
                            }

                            //disposition result

                            if( data.problem_verificaiton_details.d_prod_user != null){
                                $('#txtPDispositionUser').val(data.problem_verificaiton_details.d_prod_user.name).prop('readonly' ,true);
                                $('#txtPDispositionUserID').val(data.problem_verificaiton_details.d_prod_user.id).prop('readonly' ,true);
                                $("#txtDisposition1").prop('checked', data.problem_verificaiton_details.d_prod_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.d_prod_result != 1);
                                $("#txtDisposition2").prop('checked', data.problem_verificaiton_details.d_prod_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.d_prod_result != 2);
                                $("#txtDisposition3").prop('checked', data.problem_verificaiton_details.d_prod_result == 3).prop('disabled', isViewing && data.problem_verificaiton_details.d_prod_result != 3);
                                $('#txtPDispositionDate').val(data.problem_verificaiton_details.d_prod_date).prop('readonly');
                                $('#txtPDispositionRemarks').val(data.problem_verificaiton_details.d_prod_remarks).prop('readonly', isViewing);
                            }

                            if( data.problem_verificaiton_details.d_engr_user != null){
                                $('#txtEDispositionUser').val(data.problem_verificaiton_details.d_engr_user.name).prop('readonly' ,true);
                                $('#txtEDispositionUserID').val(data.problem_verificaiton_details.d_engr_user.id).prop('readonly' ,true);
                                $("#txtEDisposition1").prop('checked', data.problem_verificaiton_details.d_engr_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.d_engr_result != 1);
                                $("#txtEDisposition2").prop('checked', data.problem_verificaiton_details.d_engr_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.d_engr_result != 2);
                                $("#txtEDisposition3").prop('checked', data.problem_verificaiton_details.d_engr_result == 3).prop('disabled', isViewing && data.problem_verificaiton_details.d_engr_result != 3);
                                $('#txtEDispositionDate').val(data.problem_verificaiton_details.d_engr_date).prop('readonly');
                                $('#txtEDispositionRemarks').val(data.problem_verificaiton_details.d_engr_remarks).prop('readonly', isViewing);
                            }

                            if( data.problem_verificaiton_details.d_qc_user != null){
                                $('#txtQDispositionUser').val(data.problem_verificaiton_details.d_qc_user.name).prop('readonly' ,true);
                                $('#txtQDispositionUserID').val(data.problem_verificaiton_details.d_qc_user.id).prop('readonly' ,true);
                                $("#txtQDisposition1").prop('checked', data.problem_verificaiton_details.d_qc_result == 1).prop('disabled', isViewing && data.problem_verificaiton_details.d_qc_result != 1);
                                $("#txtQDisposition2").prop('checked', data.problem_verificaiton_details.d_qc_result == 2).prop('disabled', isViewing && data.problem_verificaiton_details.d_qc_result != 2);
                                $("#txtQDisposition3").prop('checked', data.problem_verificaiton_details.d_qc_result == 3).prop('disabled', isViewing && data.problem_verificaiton_details.d_qc_result != 3);
                                $('#txtQDispositionDate').val(data.problem_verificaiton_details.d_qc_date).prop('readonly');
                                $('#txtQDispositionRemarks').val(data.problem_verificaiton_details.d_qc_remarks).prop('readonly', isViewing);
                            }

                            if(data.problem_verificaiton_details.prod_verification_user != null &&
                                data.problem_verificaiton_details.engr_verification_user != null &&
                                data.problem_verificaiton_details.qc_verification_user != null &&
                                data.problem_verificaiton_details.d_qc_user != null &&
                                data.problem_verificaiton_details.d_engr_user != null &&
                                data.problem_verificaiton_details.d_prod_user != null){

                                $('.cHeaderClass').removeAttr('hidden');
                                if(data.immediate_action_details != null){

                                    $('#txtActionDetailsMan').val(data.immediate_action_details.at_man_action_details).prop('readonly', isViewing);
                                    $('#txtWhenMan').val(data.immediate_action_details.at_man_date).prop('readonly', isViewing);
                                    let $selectMan = $('#txtSelectUserMan');

                                    $.each(manUserDetails, function(id, name) {
                                        if ($selectMan.find(`option[value="${id}"]`).length === 0) {
                                            $selectMan.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });

                                    $selectMan.val(Object.keys(manUserDetails)).trigger('change');

                                    $('#txtActionDetailsMachine').val(data.immediate_action_details.at_machine_action_details).prop('readonly', isViewing);
                                    $('#txtWhenMachine').val(data.immediate_action_details.at_machine_date).prop('readonly', isViewing);
                                    let $selectMachine = $('#txtSelectUserMachine');

                                    $.each(machineUserDetails, function(id, name) {
                                        if ($selectMachine.find(`option[value="${id}"]`).length === 0) {
                                            $selectMachine.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });

                                    $selectMachine.val(Object.keys(machineUserDetails)).trigger('change');

                                    $('#txtActionDetailsMaterial').val(data.immediate_action_details.at_material_action_details).prop('readonly', isViewing);
                                    $('#txtWhenMaterial').val(data.immediate_action_details.at_material_date).prop('readonly', isViewing);
                                    let $selectMaterial = $('#txtSelectUserMaterial');


                                    $.each(materialUserDetails, function(id, name) {
                                        if ($selectMaterial.find(`option[value="${id}"]`).length === 0) {
                                            $selectMaterial.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });

                                    $selectMaterial.val(Object.keys(materialUserDetails)).trigger('change');

                                    $('#txtActionDetailsMethod').val(data.immediate_action_details.at_method_action_details).prop('readonly', isViewing);
                                    $('#txtWhenMethod').val(data.immediate_action_details.at_method_date).prop('readonly', isViewing);
                                    let $selectMethod = $('#txtSelectUserMethod');


                                    $.each(methodUserDetails, function(id, name) {
                                        if ($selectMethod.find(`option[value="${id}"]`).length === 0) {
                                            $selectMethod.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });

                                    $selectMethod.val(Object.keys(methodUserDetails)).trigger('change');

                                    $('#txtActionDetailsEnvironment').val(data.immediate_action_details.at_environment_action_details).prop('readonly', isViewing);
                                    $('#txtWhenEnvironment').val(data.immediate_action_details.at_environment_date).prop('readonly', isViewing);
                                    let $selectEnvironment = $('#txtSelectUserEnvironment');


                                    $.each(environmentUserDetails, function(id, name) {
                                        if ($selectEnvironment.find(`option[value="${id}"]`).length === 0) {
                                            $selectEnvironment.append(`<option value="${id}">${name}</option>`);
                                        }
                                    });


                                    $selectEnvironment.val(Object.keys(environmentUserDetails)).trigger('change');
                                    $('.dHeaderClass').removeAttr('hidden');
                                }

                                if(data.containment_action_details != null){
                                    $('#txtWipTotalQty').val(data.containment_action_details.wip_total_qty).prop('readonly', isViewing);
                                    $('#txtWipOkQty').val(data.containment_action_details.wip_ok_qty).prop('readonly', isViewing);
                                    $('#txtWipNgQty').val(data.containment_action_details.wip_ng_qty).prop('readonly', isViewing);
                                    $('#txtWipMod').val(data.containment_action_details.wip_mod).prop('readonly', isViewing);
                                    $('#txtWipRemarks').val(data.containment_action_details.wip_remarks).prop('readonly', isViewing);

                                    $('#txtPfTotalQty').val(data.containment_action_details.pf_total_qty).prop('readonly', isViewing);
                                    $('#txtPfOkQty').val(data.containment_action_details.pf_ok_qty).prop('readonly', isViewing);
                                    $('#txtPfNgQty').val(data.containment_action_details.pf_ng_qty).prop('readonly', isViewing);
                                    $('#txtPfMod').val(data.containment_action_details.pf_mod).prop('readonly', isViewing);
                                    $('#txtPfRemarks').val(data.containment_action_details.pf_remarks).prop('readonly', isViewing);

                                    $('#txtOqcTotalQty').val(data.containment_action_details.qc_total_qty).prop('readonly', isViewing);
                                    $('#txtOqcOkQty').val(data.containment_action_details.qc_ok_qty).prop('readonly', isViewing);
                                    $('#txtOqcNgQty').val(data.containment_action_details.qc_ng_qty).prop('readonly', isViewing);
                                    $('#txtOqcMod').val(data.containment_action_details.qc_mod).prop('readonly', isViewing);
                                    $('#txtOqcRemarks').val(data.containment_action_details.qc_remarks).prop('readonly', isViewing);

                                    $('#txtPackingTotalQty').val(data.containment_action_details.p_total_qty).prop('readonly', isViewing);
                                    $('#txtPackingOkQty').val(data.containment_action_details.p_ok_qty).prop('readonly', isViewing);
                                    $('#txtPackingNgQty').val(data.containment_action_details.p_ng_qty).prop('readonly', isViewing);
                                    $('#txtPackingMod').val(data.containment_action_details.p_mod).prop('readonly', isViewing);
                                    $('#txtPackingRemarks').val(data.containment_action_details.p_remarks).prop('readonly', isViewing);

                                    $('#txtShippedTotalQty').val(data.containment_action_details.s_total_qty).prop('readonly', isViewing);
                                    $('#txtShippedOkQty').val(data.containment_action_details.s_ok_qty).prop('readonly', isViewing);
                                    $('#txtShippedNgQty').val(data.containment_action_details.s_ng_qty).prop('readonly', isViewing);
                                    $('#txtShippedMod').val(data.containment_action_details.s_mod).prop('readonly', isViewing);
                                    $('#txtShippedRemarks').val(data.containment_action_details.s_remarks).prop('readonly', isViewing);

                                }

                            }

                        }

                    }else{
                            $('.bHeaderClass').attr('hidden', true);
                        }


                    $('#btnSaveDefectEscalation').prop('disabled', isViewing);


            },
            error: function () {
                alert('Error loading defect details.');
            }
        });
    });


    $('#modalAddDefectEscalation').on('hidden.bs.modal', function () {
        isViewingDefect = false; // Reset viewing state

        $('#txtPDispositionUserID').val('');
        $('#txtEDispositionUserID').val('');
        $('#txtQDispositionUserID').val('');
        $('#txtPVerificationUserId').val('');
        $('#txtEVerificationUserId').val('');
        $('#txtQVerificationUserId').val('');

        $('#formAddDefectEscalation')[0].reset();
        $('.select2').val('').trigger('change');
        $('#txtDefectId').val('');
        // $('#txtDefectType').attr('hidden', true).removeClass('text-danger text-warning');
        $('#btnSaveDefectEscalation').prop('disabled', false);
        $('#txtRuncardNumber').prop('disabled', false);
        $('#txtSelectUser').prop('disabled', false);
        $('#txtStation').prop('disabled', false);
        $('#txtLotNo').prop('readonly', false);
        $('#area1').prop('readonly', false);
        $('#area2').prop('readonly', false);
        $('#area3').prop('readonly', false);
        $('#area4').prop('readonly', false);
        $('#area5').prop('readonly', false);
        $('#txtDefects').prop('disabled', false);
        $('#shiftA').prop('disabled', false);
        $('#shiftB').prop('disabled', false);
        $('#txtOccurrence1Id').prop('disabled', false);
        $('#txtOccurrence2Id').prop('disabled', false);
        $('#txtOccurrence3Id').prop('disabled', false);
        // $('#txtModType').prop('disabled', false);
        $('#txtDetailedDescription').prop('readonly', false);

        $('.modeOfDefectClass').attr('hidden', true);
        $('.criticalDefectClass').attr('hidden', true);
        // $('.nonCriticalDefectClass').attr('hidden', true);
        // $('.setupDefectClass').attr('hidden', true);
        // $('.autoInserterDefectClass').attr('hidden', true);
        // $('.ngSamplesDefectClass').attr('hidden', true);

        $('#txtDefectClassification1Id').prop('disabled', false);
        $('#txtDefectClassification2Id').prop('disabled', false);
        $('#txtDefectClassification3Id').prop('disabled', false);
        $('#txtDefectClassification4Id').prop('disabled', false);
        $('#txtDefectClassification5Id').prop('disabled', false);

        $('.bHeaderClass').attr('hidden', true);
        $('.cHeaderClass').attr('hidden', true);
        $('.dHeaderClass').attr('hidden', true);


        $('.eHeaderContent').slideUp(200);
        $('.bToggleVerificationContentHeader').slideUp(200);
        $('.bHeaderVerificationContent').slideUp(200);
        $('.bToggleDispositionContentHeader').slideUp(200);
        $('.bHeaderDispositionContent').slideUp(200);
        $('.cHeaderContent').slideUp(200);
        $('.dHeaderContent').slideUp(200);


    });

    $('#bToggleHeader').on('click', function () {
        $('#bToggleVerificationContentHeader, #bToggleDispositionContentHeader').toggleClass('text-primary');

        $('.bHeaderVerificationHeader').slideToggle(200);
        $('.bHeaderDispositionHeader').slideToggle(200);
        const icon = $('#bHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#bToggleVerificationContentHeader').on('click', function () {
        $('.bHeaderVerificationContent').slideToggle(200);
        const icon = $('#bHeaderVerificationContentIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#bToggleDispositionContentHeader').on('click', function () {
        $('.bHeaderDispositionContent').slideToggle(200);
        const icon = $('#bHeaderDispositionContentIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#cToggleHeader').on('click', function () {
        $('.cHeaderContent').slideToggle(200);
        const icon = $('#cHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#dToggleHeader').on('click', function () {
        $('.dHeaderContent').slideToggle(200);
        const icon = $('#dHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#reworkToggleHeader').on('click', function () {
        $('.reworkHeaderContent').slideToggle(200);
        const icon = $('#reworkHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#eToggleHeader').on('click', function () {
        $('.eHeaderContent').slideToggle(200);
        const icon = $('#eHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#fToggleHeader').on('click', function () {
        $('.fHeaderContent').slideToggle(200);
        const icon = $('#fHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#gToggleHeader').on('click', function () {
        $('.gHeaderContent').slideToggle(200);
        const icon = $('#gHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#hToggleHeader').on('click', function () {
        $('.hHeaderContent').slideToggle(200);
        const icon = $('#hHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#iToggleHeader').on('click', function () {
        $('.iHeaderContent').slideToggle(200);
        const icon = $('#iHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $('#jToggleHeader').on('click', function () {
        $('.jHeaderContent').slideToggle(200);
        const icon = $('#jHeaderIcon');
        icon.html(icon.html() === '►' ? '▼' : '►');
    });

    $(document).on('click', '.btnPrintDefectCtrlNo', function(){
        let defectId = $(this).attr('defect-id');
        let defectCtrlNo = $(this).attr('defect-no');

        getDefectEscalationCtrlNoToPrint(defectId, defectCtrlNo);
    });


    $("#btnPrintDefectBarcode").click(function(){
        // PrintWHMatIssu();
          popup = window.open();
          // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
          let content = '';
          content += '<html>';
          content += '<head>';
            content += '<title></title>';
            content += '<style type="text/css">';
              content += '.rotated {';
                // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
                // content += 'border: 2px solid black;';
                content += 'width: 160px;';
                content += 'position: absolute;';
                content += 'left: 8.5px;';
                content += 'top: 12px;';
              content += '}';

              // content += 'td {';
              //   // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
              //   content += 'padding: 1px 1px;';
              //   content += 'margin: 1px 1px;';
              //   content += 'width: 70px;';
              // content += '}';

              content += '.vl {';
                content += 'border-right: 1px dashed black;';
                content += 'height: 60px;';
                content += 'float: right;';
              content += '}';

              content += '.vl1 {';
                content += 'border-right: 1px dashed black;';
                content += 'height: 10px;';
                content += 'float: right;';
              content += '}';

              content += '.title {';
                content += 'font-size: 6px;';
                content += 'float: center;';
                content += 'font-weight: bold;';
                content += 'font-family: arial;';
              content += '}';

            content += '</style>';
          content += '</head>';
          content += '<body>';
            content += '<center>';
            content += '<div class="rotated">';
            content += '<table>';
            content += '<tr>';
            content += '<td class="vl" style="width: 80px !important; padding: 1px 1px !important; margin: 1px 1px!important;">';
            content += '<center>';
            content += '<span class="title">PO # | Product Name</span>';
            content += '<img src="' + $("#imgGenDefectPoNoBarcode").attr('src') + '" style="min-width: 50px; max-width: 50px;">';
            content += '</center>';
            content += '</td>';

            content += '<td style="width: 150px !important; padding: 1px 1px !important; margin: 1px 1px!important;">';
            // content += '<td>';
            content += '<center>';
            content += '<span class="title">Defect Esc. Details</span>';
            content += '<img src="' + $("#imgGenDefectCtrlNoBarcode").attr('src') + '" style="min-width: 50px; max-width: 50px;">';
            content += '</center>';
            content += '</td>';
            content += '</tr>';

            content += '<tr>';
            content += '<td class="vl1" style="width: 80px !important; padding: 1px 1px !important; margin: 1px 1px!important;">';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 7px;">' + $("#lblDefectPoNo").text() + '</label><br>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $("#lblRuncardProductName").text() + '</label><br>';
            content += '</center>';
            content += '</td>';

            content += '<td style="width: 120px !important; padding: 1px 1px !important; margin: 1px 1px!important; ">';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 4px;">' + $("#lblStation").text() + ' </label>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 7px;">' + $("#lblDefectCtrlNo").text() + ' </label><br>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $("#lblDefectType").text() + '</label><br>'; //clark code 01/10/2026
            // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 4px;">' + $("#lblDefectMode1").text() + ' </label>';
            // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 4px;">' + $("#lblDefectMode2").text() + ' </label>';
            // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 4px;">' + $("#lblDefectMode3").text() + ' </label>';
            // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 4px;">' + $("#lblDefectMode4").text() + ' </label>';
            // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 4px;">' + $("#lblDefectMode5").text() + ' </label>';
            content += '</center>';
            content += '</td>';
            content += '</tr>';


             //---------------------
              let date = new Date();

              content += '<tr>';
              content += '<td colspan="4" style="padding: 1px 1px;" cellspacing="0";>';
              content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 4px; margin 1px 1px;">';
                content += date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
              content += '</label>';
              content += '</center></td>';

              content += '</tr>';
              //---------------------


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



});


</script>

@include('prod_runcard.new_script')
@endsection
@endauth
