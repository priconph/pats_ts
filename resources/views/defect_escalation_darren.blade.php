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
        <h1>Defect Escalation v3</h1>
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
                            {{-- <button class="btn btn-primary btn-sm" id="btnShowAddProdRuncard"><i class="fa fa-plus"></i> Add New</button> --}}
                            </div>
                            <br><br>
                            <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover" id="tblDefectEscalation" style="min-width: 900px;">
                                <thead>
                                <tr class="bg-light">
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Verification Status</th>
                                    <th>Defect Escalation Ctrl #</th>
                                    <th>Runcard #</th>
                                    <th>Defect Classification</th>
                                    <th>Defect Type</th>
                                    <th>Defect Description</th>
                                    <th>In-Charge</th>
                                    <th>Position</th>
                                    <th>Shift</th>
                                    <th>Station</th>
                                    {{-- <th>Defect Count</th> --}}
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
                            <a class="nav-link likod" id="back-tab" data-toggle="tab" href="#back" role="tab">Back</a>
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
                                <!-- Product Name -->
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Product Name</span>
                                        </div>
                                        <input type="text" class="form-control" name="product_name" id="txtProductName" readonly>
                                    </div>
                                </div>

                                <!-- PO Number -->
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">PO Number</span>
                                        </div>
                                        <input type="text" class="form-control" name="po_number" id="txtPONumber" readonly>
                                    </div>
                                </div>

                                <!-- Runcard Number -->
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Runcard Number</span>
                                        </div>
                                        {{-- <select class="form-control select2 select2bs4 selRuncardNo" id="txtRuncardNumber" name="runcard_no"></select> --}}
                                        <input type="text" class="form-control" name="runcard_no_with_defect" id="txtRuncardNumber" readonly>
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
                                                            <input type="text" class="form-control"   name="p_disposition_date" id="txtPDispositionDate" placeholder="Auto generated" readonly>

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
                                            IF REWORK, OPERATOR TO FILL IN
                                        </label>

                                    </div>

                                  <!-- Collapsible Content -->
                                  <div class="reworkHeaderContent p-4 border rounded bg-white shadow-sm" style="display:none;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Reworker Operator #1</span>
                                                </div>
                                                <input type="text" class="form-control" name="reworker_operator_1" id="txtReworkerOperator1" autocomplete="off" readonly>
                                                <input type="hidden" class="form-control" name="reworker_operator_id1" id="txtReworkerOperatorId1" autocomplete="off">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-primary btnScanReworker" data-type="R1" title="Scan Production ID">
                                                        <i class="fa fa-qrcode"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Reworker Operator #2</span>
                                                </div>
                                                <input type="text" class="form-control" name="reworker_operator_2" id="txtReworkerOperator2" autocomplete="off" readonly>
                                                <input type="hidden" class="form-control" name="reworker_operator_id2" id="txtReworkerOperatorId2" autocomplete="off">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-primary btnScanReworker" data-type="R2" title="Scan Production ID">
                                                        <i class="fa fa-qrcode"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Date</span>
                                                    </div>
                                                    <input type="date" class="form-control form-control-sm" name="rework_date" id="txtReworkDate">
                                                </div>
                                            </div>
                                        </div>

                                      <div class="col-md-6">
                                        <table class="table table-sm table-bordered text-center align-middle">
                                          <thead class="table-primary">
                                            <tr><th rowspan="2">UNIT #</th><th colspan="2">RESULT</th></tr>
                                            <tr><th>OK</th><th>SCRAP</th></tr>
                                          </thead>
                                          <tbody>
                                            <tr><td>1</td><td><input type="radio" name="rework1" value="1"></td><td><input type="radio" name="rework1" value="2"></td></tr>
                                            <tr><td>2</td><td><input type="radio" name="rework2" value="1"></td><td><input type="radio" name="rework2" value="2"></td></tr>
                                            <tr><td>3</td><td><input type="radio" name="rework3" value="1"></td><td><input type="radio" name="rework3" value="2"></td></tr>
                                            <tr><td>4</td><td><input type="radio" name="rework4" value="1"></td><td><input type="radio" name="rework4" value="2"></td></tr>
                                            <tr><td>5</td><td><input type="radio" name="rework5" value="1"></td><td><input type="radio" name="rework5" value="2"></td></tr>
                                          </tbody>
                                        </table>
                                      </div>

                                            <!-- Rework Activities -->
                                            <div class="col-md-8">
                                              <h6 class="fw-bold text-primary mb-3">
                                                <i class="bi bi-wrench-adjustable-circle me-2"></i> Rework Activities
                                              </h6>

                                              <div class="row">
                                                <div class="col-md-6">
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="1">
                                                    <label class="form-check-label">CT REPLACEMENT</label>
                                                  </div>
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="2">
                                                    <label class="form-check-label">PARTS DIS ASSEMBLY</label>
                                                  </div>
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="3">
                                                    <label class="form-check-label">RE-PRESSING</label>
                                                  </div>
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="4">
                                                    <label class="form-check-label">RE-PULLING</label>
                                                  </div>
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="5">
                                                    <label class="form-check-label">GLUING OF CT</label>
                                                  </div>
                                                </div>

                                                <div class="col-md-6">
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="6">
                                                    <label class="form-check-label">PARTS REPLACEMENT</label>
                                                  </div>
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="7">
                                                    <label class="form-check-label">ALIGNING OF CT</label>
                                                  </div>
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="8">
                                                    <label class="form-check-label">ALIGNING OF TERMINAL</label>
                                                  </div>
                                                  <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="rework_activity[]" value="9">
                                                    <label class="form-check-label">CLEANING</label>
                                                  </div>

                                                  <div class="form-check mb-1">
                                                    <input type="checkbox" class="form-check-input" id="txtOthers" name="rework_activity[]" value="10">
                                                    <label class="form-check-label" for="txtOthers">OTHERS</label>
                                                  </div>
                                                  <input type="text" class="form-control form-control-sm ms-4 mt-1"
                                                         name="rework_activity_others" id="txtReworkActivityOthers"
                                                         placeholder="Please specify..." style="max-width:300px;">
                                                </div>
                                              </div>
                                            </div>

                                            <!-- Replaced Parts -->
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-3">
                                                    <h6 class="fw-bold text-primary mb-0 me-2 d-flex align-items-center">
                                                      <i class="bi bi-box-seam me-2"></i> Replaced Parts
                                                    </h6>

                                                    <div class="d-flex align-items-center gap-1">
                                                      <button type="button"
                                                              class="btn btn-outline-primary btn-sm rounded-circle addReplaceParts"
                                                              style="width:25px; height:25px; padding:0; margin-left:5px;">
                                                        <i class="bi bi-plus">+</i>
                                                      </button>
                                                      <button type="button"
                                                              class="btn btn-outline-danger btn-sm rounded-circle removeReplaceParts"
                                                              style="width:25px; height:25px; padding:0; margin-left:5px;">
                                                        <i class="bi bi-x">X</i>
                                                      </button>
                                                    </div>
                                                </div>

                                                <div id="replacedPartsContainer">
                                                    <div class="replaced-part-row mb-2 border rounded p-2">
                                                        <div class="input-group input-group-sm mb-2">
                                                        <span class="input-group-text w-50">Part Name</span>
                                                        <input type="text" class="form-control" name="r_part_name[]" autocomplete="off">
                                                        </div>

                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text w-50">Lot No</span>
                                                            <input type="text" class="form-control" name="r_lot_no[]" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <!-- MPC + Activity + Parts -->
                                        <div class="col-md-4 text-start">
                                            <h6 class="fw-bold text-primary mb-3">
                                            <i class="bi bi-gear me-2"></i> MPC Check Using
                                            </h6>

                                            <div class="form-check ms-0">
                                            <input type="radio" class="form-check-input" name="mpc_check" value="1" id="mpc1">
                                            <label class="form-check-label" for="mpc1">TERMINAL GAUGE</label>
                                            </div>

                                            <div class="form-check ms-0">
                                            <input type="radio" class="form-check-input" name="mpc_check" value="2" id="mpc2">
                                            <label class="form-check-label" for="mpc2">DUMMY #LO</label>
                                            </div>

                                            <div class="form-check ms-0">
                                            <input type="radio" class="form-check-input" name="mpc_check" value="3" id="mpc3">
                                            <label class="form-check-label" for="mpc3">DUMMY #MO</label>
                                            </div>
                                        </div>

                                      <div class="col-md-4">
                                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-arrow-repeat me-2"></i> Activity After Rework</h6>
                                        <div class="form-check"><input type="radio" class="form-check-input" name="activity_after_rework" value="1"><label class="form-check-label">PRINTING</label></div>
                                        <div class="form-check"><input type="radio" class="form-check-input" name="activity_after_rework" value="2"><label class="form-check-label">GLUING</label></div>
                                        <div class="form-check"><input type="radio" class="form-check-input" name="activity_after_rework" value="3"><label class="form-check-label">CURING</label></div>
                                      </div>


                                    </div><!-- row -->
                                  </div><!-- content -->
                                </div>
                            </div>

                            <div class="row gHeaderClass" hidden>
                                <div class="col-sm-12">

                                    <div id="gToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                        <label class="fw-bold">
                                            <span id="gHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                            G. FINAL DISPOSITION - LINE RESUMPTION
                                        </label>
                                    </div>

                                    <!-- Collapsible Content -->
                                    <div class="content gHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                        <div class="row g-4">
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="f_dispostion_result"
                                                            id="txtDispostionResult1" value="1">
                                                        <label class="form-check-label" for="txtDispostionResult1">
                                                            Resume Production
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="f_dispostion_result"
                                                            id="txtDispostionResult2" value="2">
                                                        <label class="form-check-label" for="txtDispostionResult2">
                                                            Stop Production
                                                        </label>
                                                    </div>
                                                </div>

                                                    <!-- ENG'G MANAGER -->
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-25">
                                                        <span class="input-group-text w-100">Eng'g Manager</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="d_engr_manager" id="txtDEngrManagerUser" readonly>
                                                    <input type="hidden" name="d_engr_manager_id" id="txtDEngrManagerUserId">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary btnScanFinalDisposition" data-type="FEM" title="Scan Eng'g Manager ID">
                                                            <i class="fa fa-qrcode"></i>
                                                        </button>
                                                    </div>

                                                    <input type="text" class="form-control text-center" name="d_engr_manager_date" id="txtDEngrManagerDateId" placeholder="Date" readonly>
                                                </div>

                                                <!-- PROCESS ENG'G -->
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-25">
                                                        <span class="input-group-text w-100">Process Eng'g</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="d_p_engr" id="txtDPEngrUser" readonly>
                                                    <input type="hidden" name="d_p_engr_id" id="txtDPEngrUserId">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary btnScanFinalDisposition" data-type="FPE" title="Scan Process Eng'g ID">
                                                            <i class="fa fa-qrcode"></i>
                                                        </button>
                                                    </div>

                                                    <input type="text"  class="form-control text-center" name="d_p_engr_date" id="txtDPEngrDateId" placeholder="Date" readonly>

                                                </div>

                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Remarks</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="f_engr_dispostion_remarks" id="txtFDispositionRemarks">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="f_qc_dispostion_result"
                                                            id="txtQcDispostionResult1" value="1">
                                                        <label class="form-check-label" for="txtQcDispostionResult1">
                                                            Resume Production
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mx-4">
                                                        <input class="form-check-input" type="radio" name="f_qc_dispostion_result"
                                                            id="txtQcDispostionResult2" value="2">
                                                        <label class="form-check-label" for="txtQcDispostionResult2">
                                                            Stop Production
                                                        </label>
                                                    </div>
                                                </div>

                                                    <!-- QC MANAGER -->
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-25">
                                                        <span class="input-group-text w-100">QC Manager</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="d_qc_manager" id="txtDQCManagerUser" readonly>
                                                    <input type="hidden" name="d_qc_manager_id" id="txtDQCManagerUserId">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary btnScanFinalDisposition" data-type="FQCM" title="Scan QC Manager ID">
                                                            <i class="fa fa-qrcode"></i>
                                                        </button>
                                                    </div>

                                                    <input type="text" class="form-control text-center" name="d_qc_manager_date" id="txtQcManagerDateId" placeholder="Date" readonly>
                                                </div>

                                                <!-- QC Supervisor -->
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-25">
                                                        <span class="input-group-text w-100">QC Supervisor</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="d_qc" id="txtDQcUser" readonly>
                                                    <input type="hidden" name="d_qc_id" id="txtDQcUserId">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary btnScanFinalDisposition" data-type="DQS" title="Scan QC Supervisor ID">
                                                            <i class="fa fa-qrcode"></i>
                                                        </button>
                                                    </div>

                                                    <input type="text"  class="form-control text-center" name="d_qc_date" id="txtDQcDateId" placeholder="Date" readonly>

                                                </div>

                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Remarks</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="f_qc_dispostion_remarks" id="txtFQcDispositionRemarks">
                                                </div>
                                            </div>
                                        </div>

                                    </div><!-- content -->
                                </div>
                            </div>

                            <div class="row hHeaderClass" hidden>
                                <div class="col-sm-12">

                                    <div id="hToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                        <label class="fw-bold">
                                            <span id="hHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                            H. DEFINE & VERIFIY ROOT CAUSE & ESCAPEE CAUSE
                                        </label>
                                    </div>

                                    <!-- Collapsible Content -->
                                    <div class="content hHeaderContent" style=" display: none; padding: 10px; border: 1px solid #ddd;">
                                        <div class="row g-4">
                                            <div class="col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="txtRootCauseEscapee" class="fw-bold">Root & Escapee Cause</label>
                                                <textarea
                                                    class="form-control"
                                                    id="txtRootCauseEscapee"
                                                    name="root_cause_escapee"
                                                    rows="4"
                                                    placeholder="Enter root and escapee cause here..."
                                                ></textarea>
                                            </div>
                                        </div>
                                        </div>
                                    </div><!-- content -->
                                </div>
                            </div>

                            <div class="row iHeaderClass" hidden>
                                <div class="col-sm-12">

                                    <div id="iToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                        <label class="fw-bold">
                                            <span id="iHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                            I. CHOOSE & VERIFY PERMANENT CORRECTIVE/PREVENT ACTION FOR ROOT CAUSE & ESCAPEE CAUSE
                                        </label>
                                    </div>

                                    <!-- Collapsible Content -->
                                    <div class="content iHeaderContent" style="display: none; padding: 10px; border: 1px solid #ddd;">
                                        <button type="button" class="btn btn-primary addCapaRowBtn">Add Row</button><br><br>

                                        <!-- Template row -->
                                        <div class="row mb-3 capaRowTemplate">
                                            <div class="col-sm-4">
                                                <select class="form-control select2 select2bs4 selUser" name="capa_user[]">
                                                    <option value="0" disabled selected>-- Loading --</option>
                                                </select>

                                            </div>
                                            <div class="col-sm-4">
                                                <textarea
                                                    class="form-control"
                                                    id="txtCapaAction"
                                                    name="capa_action[]"
                                                    rows="1"
                                                    placeholder="Corrective/Preventive Action"
                                                ></textarea>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="date" name="capa_due_date[]" id="txtCapaDueDate" class="form-control">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-danger removeCapaRowBtn">X</button>
                                            </div>
                                        </div>
                                    </div><!-- content -->
                                </div>
                            </div>

                            <div class="row jHeaderClass" hidden>
                                <div class="col-sm-12">
                                    <div id="jToggleHeader" class="header" style="cursor: pointer; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd;">
                                        <label class="fw-bold">
                                            <span id="jHeaderIcon" style="float: left; font-weight: bold; font-family: Arial Black, sans-serif;">&#x25BA;</span>
                                            J. VERIFICATION & CONFIRMATION OF DERIVED ACTIONS (Refer to CAPA monitoring report)
                                        </label>
                                    </div>

                                    <!-- Collapsible Content -->
                                    <div class="content jHeaderContent" style="display: none; padding: 10px; border: 1px solid #ddd;">
                                        <div class="row g-4">
                                            <div class="col-sm-8">
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Action stated above already implemented?</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="action_stated" id="txtActionStated">
                                                </div>

                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Problem re-occur after implementation of action?</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="problem_reoccur" id="txtProblemReoccur">
                                                </div>

                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Are the derived actions documented?</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="derived_actions_documented" id="txtDerivedActionsDocumented">
                                                </div>

                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Result confirmation</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="result_confirmation" id="txtResultConfirmation">
                                                </div>
                                            </div>


                                            <div class="col-sm-4">
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100">Remarks</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="verification_remarks" id="txtVerificationRemarks">
                                                </div>
                                            </div>
                                        </div>

                                            <div class="row g-4">
                                                <div class="col-sm-4">
                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">QC Inspector</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="vc_inspector_user" id="txtVCInspectorUser" readonly>
                                                        <input type="hidden" name="vc_inspector_user_id" id="txtVCInspectorUserId">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-primary btnScanVC" data-type="VCIQC" title="Scan QC Inspector ID">
                                                                <i class="fa fa-qrcode"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date</span>
                                                        </div>
                                                        <input type="text" class="form-control text-center" name="vc_inspector_date" id="txtVcQcInspectorDateId" placeholder="Auto Generated" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">QC Supervisor</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="vc_supervisor_user" id="txtVCSupervisorUser" readonly>
                                                        <input type="hidden" name="vc_supervisor_user_id" id="txtVCSupervisorUserId">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-primary btnScanVC" data-type="VCQC" title="Scan QC Supervisor ID">
                                                                <i class="fa fa-qrcode"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date</span>
                                                        </div>
                                                        <input type="text" class="form-control text-center" name="vc_supervisor_date" id="txtVcQcSupervisorDateId" placeholder="Auto Generated" readonly>
                                                    </div>
                                                </div>


                                                <div class="col-sm-4">
                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">QC Manager</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="vc_manager_user" id="txtVCManagerUser" readonly>
                                                        <input type="hidden" name="vc_manager_user_id" id="txtVCManagerUserId">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-primary btnScanVC" data-type="VCQM" title="Scan QC Manager ID">
                                                                <i class="fa fa-qrcode"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="input-group input-group-sm mb-2">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100">Date</span>
                                                        </div>
                                                        <input type="text" class="form-control text-center" name="vc_manager_date" id="txtVcQcManagerDateId" placeholder="Auto Generated" readonly>
                                                    </div>
                                                </div>
                                            </div>


                                    </div><!-- content -->

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
                <input type="text" id="txtSearchProdId">
                <input type="hidden" id="txtSearchProdId"  name="production_user">
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

    <!-- Modal Scan PRODUCTION ID -->
    <div class="modal fade" id="modalScanReworkerId" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center text-secondary">
                        <span id="scanReworkerIdBody">Please Scan ID...</span>
                        <br>
                        <br>
                        <h1><i id="scanReworkerIdIcon" class="fa fa-qrcode fa-lg"></i></h1>
                    </div>
                    <input type="text" id="txtReworkerId">
                    <input type="hidden" id="txtReworkerId" value="1" name="reworker_user">
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal Scan Verification 2 ID -->
    <div class="modal fade" id="modalScanVerification2Id" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center text-secondary">
                        <span id="scanVerification2IdBody">Please Scan ID...</span>
                        <br>
                        <br>
                        <h1><i id="scanVerification2IdIcon" class="fa fa-qrcode fa-lg"></i></h1>
                    </div>
                    <input type="text" id="txtSearchVerification2">
                    <input type="hidden" id="txtSearchVerification2" name="verification2_user">
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal Scan Verification 2 ID -->
    <div class="modal fade" id="modalScanFinalDispositionId" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center text-secondary">
                        <span id="scanFInalDispositionIdBody">Please Scan ID...</span>
                        <br>
                        <br>
                        <h1><i id="scanFInalDispositionIdIcon" class="fa fa-qrcode fa-lg"></i></h1>
                    </div>
                    <input type="text" id="txtSearchFinalDispositionUserId" autocomplete="off">
                    <input type="hidden" id="txtSearchFinalDispositionUserId" name="final_disposition_user">
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal Scan Verification 2 ID -->
    <div class="modal fade" id="modalScanForVerificationOngoing" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center text-secondary">
                        <span id="scanForVerificationOngoingIdBody">Please Scan ID...</span>
                        <br>
                        <br>
                        <h1><i id="scanForVerificationOngoingIdIcon" class="fa fa-qrcode fa-lg"></i></h1>
                    </div>
                    <input type="text" id="txtSearchForVerificationOngoingId" autocomplete="off">
                    <input type="hidden" id="txtSearchForVerificationOngoingUserId" name="final_disposition_user">
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal Scan Verification 2 ID -->
    <div class="modal fade" id="modalScanVerificationAndConfirmation" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center text-secondary">
                        <span id="scanVerificationAndConfirmationIqcBody">Please Scan ID...</span>
                        <br>
                        <br>
                        <h1><i id="scanVerificationAndConfirmationIqcIcon" class="fa fa-qrcode fa-lg"></i></h1>
                    </div>
                    <input type="text" id="txtSearchForVerificationAndConfirmationId" autocomplete="off">
                    <input type="hidden" id="txtSearchForVerificationAndConfirmationUserId" name="vc_qc_inspector_id">
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal -->


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
    let deviceCode = '';
    let poQty = '';
    let isViewingDefect = false;

    let scanContext ='';
    let reworkerContext ='';
    let verification2Context ='';
    let finalDispositionContext ='';
    let qcContext ='';

    let currentPoNo = sessionStorage.getItem('po_number_for_defect_escalation');
    let currentDeviceName = sessionStorage.getItem('device_name_for_defect_escalation');
    let currentDeviceCode = sessionStorage.getItem('device_code_for_defect_escalation');
    let currentPoQty = sessionStorage.getItem('po_qty_for_defect_escalation');
    let currentDieNo = sessionStorage.getItem('die_no_for_defect_escalation');
    let currentLotNo = sessionStorage.getItem('pair_no_for_defect_escalation');

    if(currentPoNo != null){
        poNumber = currentPoNo;
        deviceName = currentDeviceName;
        deviceCode = currentDeviceCode;
        poQty = currentPoQty;
        dieNo = currentDieNo;
        lotNo = currentLotNo;

        $('#txt_po_number_lbl').val(poNumber);
        $('#txt_device_name_lbl').val(deviceName);
        $('#txt_device_code_lbl').val(deviceCode);
        $('#txt_po_qty_lbl').val(poQty);
        $('#txtPairNo').val(dieNo);
        $('#txtDMoldNo').val(lotNo);
        // $('#lblPoNumber').text(poNumber);
        sessionStorage.removeItem('po_number_for_defect_escalation');
        sessionStorage.removeItem('device_name_for_defect_escalation');
        sessionStorage.removeItem('device_code_for_defect_escalation');
        sessionStorage.removeItem('po_qty_for_defect_escalation');
        sessionStorage.removeItem('die_no_for_defect_escalation');
        sessionStorage.removeItem('pair_no_for_defect_escalation');
        getDefectCount(poNumber);
    }

    let defectEscalationDataTable = $("#tblDefectEscalation").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "get_defect_runcard_details_new",
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
            { data: "verification_status" },
            { data: "defect_ctrl" },
            { data: "runcard_no" },
            { data: "defect_classification" },
            { data: "type_of_defect" },
            { data: "detailed_description" },
            { data: "in_charge" },
            { data: "position" },
            { data: "shift" },
            { data: "station" },
            // { data: "defect_count" },
            { data: "datetime" }
        ]
    });

    GetUserListForDefect($("#txtSelectUser"));
    GetUserList($(".selectUser"));
    GetUserForPersonInCharge($("#txtSelectPersonInCharge"));
    GetAssemblyLinesForDefectEscalation($(".sel-assembly-lines"));

    $("#txtDefects").trigger('change');
    $("#txtSelectUser").trigger('change');
    $("#txtRuncardNumber").trigger('change');

    $(".btnSearchPoNo").click(function(){
        $("#txtSearchPoNumber").val('');
        $("#scanPOTransLotBody").text('Please Scan the PO code.');
        $('#modalScanPoNumber').attr('data-formid', 'search_po_number').modal('show');
    });

    $('#modalScanPoNumber').on('shown.bs.modal', function (e) {
        $('#txtSearchPoNumber').focus();
        $(document).on('keypress',function(e){
            // console.log('enter');
            poNumber = $('#txtSearchPoNumber').val();
            if( e.keyCode == 13){
                // console.log('poNumber', poNumber);
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
                                getDefectCount(poNumber);
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



    $(document).on('click', '.btn_view_defect_details, .btn_edit_defect_details', function () {
        isViewingDefect = true;
        const isViewing = $(this).hasClass('btn_view_defect_details'); // true = view, false = edit
        const defectId = $(this).data('defect-id');
        const defectStatus = $(this).data('status');
        const verificationStatus = $(this).data('verification');
        const detailedDescription = $(this).data('description');
        const occurence = $(this).data('occurence');

        let defectType = $(this).data('type');
        // console.log(typeof(defectType));

        let defectTypeTest = '';

        if(defectType.length > 1){
            defectTypeTest = defectType.split(",");

        }else{
            defectTypeTest = defectType;
        }

        // console.log(typeof(defectTypeTest));

        let isDefectType1 = false;
        let isDefectType2 = false;
        let isDefectType3 = false;
        let isDefectType4 = false;
        let isDefectType5 = false;

        // console.log(isDefectType2);


        if (Array.isArray(defectTypeTest)) {
            if(defectTypeTest.includes('1')){
                isDefectType1 = true;
            }else if(defectTypeTest.includes('2')){
                isDefectType2 = true;
            }else if(defectTypeTest.includes('3')){
                isDefectType3 = true;
            }else if(defectTypeTest.includes('4')){
                isDefectType4 = true;
            }else if(defectTypeTest.includes('5')){
                isDefectType5 = true;
            }
        }else{
            if(defectTypeTest == 1){
                isDefectType1 = true;
            }else if (defectTypeTest == 2 ){
                isDefectType2 = true;
            }else if (defectTypeTest == 3 ){
                isDefectType3 = true;
            }else if (defectTypeTest == 4 ){
                isDefectType3 = true;
            }else if (defectTypeTest == 4 ){
                isDefectType3 = true;
            }
        }


        $('#txtDefectId').val(defectId);
        $('#modalAddDefectEscalation').modal('show');


        if(defectStatus == 0 && (verificationStatus == '' || verificationStatus == null) && isViewing == false){
            if(isDefectType1 || isDefectType2 || isDefectType3 || isDefectType4 || isDefectType5){
                if(detailedDescription == null || detailedDescription == ''){
                    $('#modalScanForVerificationOngoing').modal('show');
                    $('.bHeaderClass').attr('hidden', true);
                }
            }
        }else{
            if(verificationStatus == 'VERIFIED' && occurence == 2 || occurence == 3 || isDefectType1){
                $('.bHeaderClass').attr('hidden', false);
            }

        }

        $.ajax({
            type: "GET",
            url: "view_defect_details_new",
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
                const reworkDetails = data.rework_details;
                const levelTwoVerificationDetails = data.de_level_two_verification_details;
                const finalDispositionDetails = data.final_disposition_details;
                const rootAndCauseEscapeeDetails = data.root_escapee_cause_details;
                const capaDetails = response.capaDetails;
                const capaUserDetails = response.capaUserDetails;
                const verificationAndConfirmationResults = data.verification_and_confirmation_details;

                const ctrlNumber = data.defect_ctrl_number;
                const ctrlText = data.defect_ctrl_txt;
                const defectCtrlNumber = ctrlText +'-'+ctrlNumber;

                $('#txtRuncardNumber').val(data.production_runcard.runcard_no);

                    const runcardId = data.prod_runcard_id;
                    $('#txtRuncardId').val(runcardId);
                    $('#txtRuncardStationId').val(data.prod_runcard_station_id);
                    $('#txtStationModId').val(data.prod_runcard_station_mod_id);
                    $('#txtLine').val(data.line_id).trigger('change').prop('disabled', isViewing);

                    // User select
                    if ($('#txtSelectUser option[value="' + userId + '"]').length === 0) {
                        $('#txtSelectUser').append(new Option(userName, userId, true, true));
                    }

                    $('#txtSelectUser').val(userId).trigger('change').prop('readonly', true, isViewing).css({
                        'pointer-events': 'none',
                        'background-color': '#e9ecef'  // optional: Bootstrap-like disabled look
                    });

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

                    let testClassification = data.defect_classification;
                    // console.log('defectClassification', typeof(testClassification));

                    // 1. Convert "2,4,6" into an array ["2", "4", "6"]
                    // We use split and handle cases where data might be null/empty
                    let selectedClasses = testClassification ? testClassification.toString().split(',') : [];

                    // 2. Define your classifications to loop through
                    let classifications = [1, 2, 3, 4, 5];

                    classifications.forEach(function(id) {
                        let isSelected = selectedClasses.includes(id.toString());

                        // Target each checkbox by ID (e.g., #txtDefectClassification1Id)
                        $("#txtDefectClassification" + id + "Id")
                            .prop('checked', isSelected)
                            .prop('disabled', isViewing && !isSelected);
                    });

                    // if(testClassification.includes('2')){

                    // }
                    // $("#txtDefectClassification1Id").prop('checked', data.defect_classification == 1).prop('disabled', isViewing && data.defect_classification != 1);
                    // $("#txtDefectClassification2Id").prop('checked', data.defect_classification == 2).prop('disabled', isViewing && data.defect_classification != 2);
                    // $("#txtDefectClassification3Id").prop('checked', data.defect_classification == 3).prop('disabled', isViewing && data.defect_classification != 3);
                    // $("#txtDefectClassification4Id").prop('checked', data.defect_classification == 4).prop('disabled', isViewing && data.defect_classification != 4);
                    // $("#txtDefectClassification5Id").prop('checked', data.defect_classification == 5).prop('disabled', isViewing && data.defect_classification != 5);




                    $('#txtDetailedDescription').val(data.detailed_description).prop('readonly', isViewing);

                    let primaryClass = testClassification[0];

                    if (primaryClass) {
                        let labelText = $('input[name="d_classification"][value="' + primaryClass + '"]').next('label').text();

                        // Update Label and Qty
                        $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');
                        $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                        // Update Visibility
                        $('.modeOfDefectClass, .criticalDefectClass').removeAttr('hidden');

                        if (primaryClass == 1) {
                            $('.otherDefectClass').attr('hidden', true);
                        } else {
                            $('.otherDefectClass').removeAttr('hidden');
                        }

                        // ✅ Handle the Multiple Select2 Dropdown
                        getModOfDefect($("#txtDefects"), primaryClass, function () {
                            // Split IDs like "6,10" into ["6", "10"]
                            let defectIds = (data.mod_id || "").toString().split(",").filter(Boolean);

                            $('#txtDefects')
                                .val(defectIds)      // Preselect the array
                                .trigger('change')   // Refresh Select2
                                .prop('disabled', isViewing);
                        });
                    }

                    // 3. Update the Checkboxes for ALL active classifications
                    [1, 2, 3, 4, 5].forEach(id => {
                        let isSelected = testClassification.includes(id.toString());
                        $("#txtDefectClassification" + id + "Id")
                            .prop('checked', isSelected)
                            .prop('disabled', isViewing && !isSelected);
                    });

                    // if (data.defect_classification == 1) {
                    //     $('.modeOfDefectClass').removeAttr('hidden');
                    //     $('.criticalDefectClass').removeAttr('hidden');
                    //     $('.otherDefectClass').attr('hidden', true);

                    //     // ✅ Pass a callback to preselect and disable the dropdown
                    //     // getModOfDefect($("#txtDefects"), 1, function () {
                    //     //     $('#txtDefects').val(data.mod_id).trigger('change').prop('disabled', isViewing);
                    //     // });

                    //     getModOfDefect($("#txtDefects"), 1, function () {
                    //         let defectIds = (data.mod_id || "").split(",");

                    //         $('#txtDefects')
                    //             .val(defectIds)      // preselect multiple values
                    //             .trigger('change')   // refresh Select2 UI
                    //             .prop('disabled', isViewing);
                    //     });

                    // } else if(data.defect_classification == 2) {
                    //     let labelText = $('input[name="d_classification"][value="2"]').next('label').text();
                    //     $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');

                    //     $('.modeOfDefectClass').removeAttr('hidden');
                    //     $('.criticalDefectClass').removeAttr('hidden');
                    //     $('.otherDefectClass').removeAttr('hidden');
                    //     $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                    //     getModOfDefect($("#txtDefects"), 2, function () {
                    //         let defectIds = (data.mod_id || "").split(",");

                    //         $('#txtDefects')
                    //             .val(defectIds)      // preselect multiple values
                    //             .trigger('change')   // refresh Select2 UI
                    //             .prop('disabled', isViewing);
                    //     });
                    // }else if(data.defect_classification == 3) {
                    //     let labelText = $('input[name="d_classification"][value="3"]').next('label').text();
                    //     $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');

                    //     $('.modeOfDefectClass').removeAttr('hidden');
                    //     $('.criticalDefectClass').removeAttr('hidden');
                    //     $('.otherDefectClass').removeAttr('hidden');
                    //     $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                    //     getModOfDefect($("#txtDefects"), 3, function () {
                    //         let defectIds = (data.mod_id || "").split(",");

                    //         $('#txtDefects')
                    //             .val(defectIds)      // preselect multiple values
                    //             .trigger('change')   // refresh Select2 UI
                    //             .prop('disabled', isViewing);
                    //     });
                    // }else if(data.defect_classification == 4) {
                    //     let labelText = $('input[name="d_classification"][value="4"]').next('label').text();
                    //     $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');

                    //     $('.modeOfDefectClass').removeAttr('hidden');
                    //     $('.criticalDefectClass').removeAttr('hidden');
                    //     $('.otherDefectClass').removeAttr('hidden');
                    //     $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                    //     getModOfDefect($("#txtDefects"), 4, function () {
                    //         let defectIds = (data.mod_id || "").split(",");

                    //         $('#txtDefects')
                    //             .val(defectIds)      // preselect multiple values
                    //             .trigger('change')   // refresh Select2 UI
                    //             .prop('disabled', isViewing);
                    //     });
                    // }else if(data.defect_classification == 5) {
                    //     let labelText = $('input[name="d_classification"][value="5"]').next('label').text();
                    //     $('#txtDefectQty').closest('.input-group').find('.input-group-text').text(labelText + ' QTY');

                    //     $('.modeOfDefectClass').removeAttr('hidden');
                    //     $('.criticalDefectClass').removeAttr('hidden');
                    //     $('.otherDefectClass').removeAttr('hidden');
                    //     $('#txtDefectQty').val(data.defect_qty).prop('readonly', isViewing);

                    //     getModOfDefect($("#txtDefects"), 5, function () {
                    //         let defectIds = (data.mod_id || "").split(",");

                    //         $('#txtDefects')
                    //             .val(defectIds)      // preselect multiple values
                    //             .trigger('change')   // refresh Select2 UI
                    //             .prop('disabled', isViewing);
                    //     });
                    // }

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

                    let pv = data.problem_verificaiton_details;

                            let pvIsCompelete = hasAll([
                                pv?.prod_verification_user,
                                pv?.vr_prod_result,
                                pv?.vr_prod_date,

                                pv?.engr_verification_user,
                                pv?.vr_engr_result,
                                pv?.vr_engr_date,

                                pv?.qc_verification_user,
                                pv?.vr_qc_result,
                                pv?.vr_qc_date,

                                pv?.d_prod_user,
                                pv?.d_prod_result,
                                pv?.d_prod_date,

                                pv?.d_engr_user,
                                pv?.d_engr_result,
                                pv?.d_engr_date,

                                pv?.d_qc_user,
                                pv?.d_qc_result,
                                pv?.d_qc_date,
                            ]);

                            let ia = data.immediate_action_details;

                            let iaIsComplete = hasAll([
                                ia?.at_man_action_details,
                                ia?.at_machine_action_details,
                                ia?.at_material_action_details,
                                ia?.at_method_action_details,
                                ia?.at_environment_action_details,

                                ia?.at_man_date,
                                ia?.at_machine_date,
                                ia?.at_material_date,
                                ia?.at_method_date,
                                ia?.at_environment_date,

                                ia?.at_man_user,
                                ia?.at_machine_user,
                                ia?.at_material_user,
                                ia?.at_method_user,
                                ia?.at_environment_user,
                            ]);

                            let ca = data.containment_action_details;

                            let caIsComplete = hasAll([
                                ca?.wip_total_qty,
                                ca?.wip_ok_qty,
                                ca?.wip_ng_qty,
                                ca?.wip_mod,
                                ca?.wip_remarks,

                                ca?.pf_total_qty,
                                ca?.pf_ok_qty,
                                ca?.pf_ng_qty,
                                ca?.pf_mod,
                                ca?.pf_remarks,

                                ca?.qc_total_qty,
                                ca?.qc_ok_qty,
                                ca?.qc_ng_qty,
                                ca?.qc_mod,
                                ca?.qc_remarks,

                                ca?.p_total_qty,
                                ca?.p_ok_qty,
                                ca?.p_ng_qty,
                                ca?.p_mod,
                                ca?.p_remarks,

                                ca?.s_total_qty,
                                ca?.s_ok_qty,
                                ca?.s_ng_qty,
                                ca?.s_mod,
                                ca?.s_remarks,
                            ]);

                            let rwd = reworkDetails;

                            let rwdIsComplete = hasAll([
                                rwd?.reworker_user_id1,
                                rwd?.reworker_user_id2,
                                rwd?.rework_date,
                                rwd?.rework_activity,
                                rwd?.unit1_result,
                                rwd?.rework_mpc_checking,
                                rwd?.activity_after_rework,
                                // rwd?.rework_replace_parts,
                                // rwd?.rework_lot_no,
                            ]);

                            let fd = finalDispositionDetails;

                            let fdIsComplete = hasAll([
                                fd?.f_disposition,
                                fd?.engr_manager_id,
                                fd?.process_engr_id,
                                fd?.f_disposition_engr_remarks,
                                fd?.f_disposition_qc,
                                fd?.qc_manager_id,
                                fd?.qc_supervisor_id,
                                fd?.f_disposition_qc_remarks,
                            ]);

                            let rcec = rootAndCauseEscapeeDetails;

                            let rcecIsComplete = hasAll([
                                rcec?.rc_ec,
                            ]);

                            let cd = capaDetails;

                            let cdIsComplete = capaDetails.every(item =>
                                hasAll([
                                    item?.de_capa,
                                    item?.de_pic_ids,
                                    item?.de_due_date,
                                ])

                            );


                    if(data.defect_classification == 1){
                        if(data.defect_status == 0 || data.defect_status == 1  || data.defect_status == 2|| data.defect_status == 3 || data.defect_status == 4 || data.defect_status == 5 || data.defect_status == 6 || data.defect_status == 7 || data.defect_status == 8){
                            $('.cHeaderClass').attr('hidden', !pvIsCompelete);
                            $('.dHeaderClass').attr('hidden', !iaIsComplete);
                            $('.backTabClass').attr('hidden', !caIsComplete);
                            $('.ifReworkHeaderClass').attr('hidden', !caIsComplete);
                            $('.gHeaderClass').attr('hidden', !rwdIsComplete);
                            $('.hHeaderClass').attr('hidden', !fdIsComplete);
                            $('.iHeaderClass').attr('hidden', !rcecIsComplete);
                            $('.jHeaderClass').attr('hidden', !cdIsComplete);

                            if(data.problem_verificaiton_details != null){
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

                            }

                            if(data.immediate_action_details != null){
                                const iad = data.immediate_action_details ?? {};
                                // ================= MAN =================
                                $('#txtActionDetailsMan')
                                    .val(iad.at_man_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenMan')
                                    .val(iad.at_man_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectMan = $('#txtSelectUserMan');
                                $.each(manUserDetails, function (id, name) {
                                    if ($selectMan.find(`option[value="${id}"]`).length === 0) {
                                        $selectMan.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectMan.val(Object.keys(manUserDetails)).trigger('change');

                                // ================= MACHINE =================
                                $('#txtActionDetailsMachine')
                                    .val(iad.at_machine_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenMachine')
                                    .val(iad.at_machine_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectMachine = $('#txtSelectUserMachine');
                                $.each(machineUserDetails, function (id, name) {
                                    if ($selectMachine.find(`option[value="${id}"]`).length === 0) {
                                        $selectMachine.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectMachine.val(Object.keys(machineUserDetails)).trigger('change');

                                // ================= MATERIAL =================
                                $('#txtActionDetailsMaterial')
                                    .val(iad.at_material_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenMaterial')
                                    .val(iad.at_material_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectMaterial = $('#txtSelectUserMaterial');
                                $.each(materialUserDetails, function (id, name) {
                                    if ($selectMaterial.find(`option[value="${id}"]`).length === 0) {
                                        $selectMaterial.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectMaterial.val(Object.keys(materialUserDetails)).trigger('change');

                                // ================= METHOD =================
                                $('#txtActionDetailsMethod')
                                    .val(iad.at_method_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenMethod')
                                    .val(iad.at_method_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectMethod = $('#txtSelectUserMethod');
                                $.each(methodUserDetails, function (id, name) {
                                    if ($selectMethod.find(`option[value="${id}"]`).length === 0) {
                                        $selectMethod.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectMethod.val(Object.keys(methodUserDetails)).trigger('change');

                                // ================= ENVIRONMENT =================
                                $('#txtActionDetailsEnvironment')
                                    .val(iad.at_environment_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenEnvironment')
                                    .val(iad.at_environment_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectEnvironment = $('#txtSelectUserEnvironment');
                                $.each(environmentUserDetails, function (id, name) {
                                    if ($selectEnvironment.find(`option[value="${id}"]`).length === 0) {
                                        $selectEnvironment.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectEnvironment.val(Object.keys(environmentUserDetails)).trigger('change');
                            }

                            if(data.containment_action_details != null){
                                const cad = data?.containment_action_details ?? {};
                                $('#txtWipTotalQty').val(cad.wip_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtWipOkQty').val(cad.wip_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtWipNgQty').val(cad.wip_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtWipMod').val(cad.wip_mod ?? '').prop('readonly', isViewing);
                                $('#txtWipRemarks').val(cad.wip_remarks ?? '').prop('readonly', isViewing);

                                $('#txtPfTotalQty').val(cad.pf_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtPfOkQty').val(cad.pf_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtPfNgQty').val(cad.pf_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtPfMod').val(cad.pf_mod ?? '').prop('readonly', isViewing);
                                $('#txtPfRemarks').val(cad.pf_remarks ?? '').prop('readonly', isViewing);

                                $('#txtOqcTotalQty').val(cad.qc_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtOqcOkQty').val(cad.qc_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtOqcNgQty').val(cad.qc_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtOqcMod').val(cad.qc_mod ?? '').prop('readonly', isViewing);
                                $('#txtOqcRemarks').val(cad.qc_remarks ?? '').prop('readonly', isViewing);

                                $('#txtPackingTotalQty').val(cad.p_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtPackingOkQty').val(cad.p_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtPackingNgQty').val(cad.p_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtPackingMod').val(cad.p_mod ?? '').prop('readonly', isViewing);
                                $('#txtPackingRemarks').val(cad.p_remarks ?? '').prop('readonly', isViewing);

                                $('#txtShippedTotalQty').val(cad.s_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtShippedOkQty').val(cad.s_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtShippedNgQty').val(cad.s_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtShippedMod').val(cad.s_mod ?? '').prop('readonly', isViewing);
                                $('#txtShippedRemarks').val(cad.s_remarks ?? '').prop('readonly', isViewing);
                            }

                            if(reworkDetails != null){
                                const rd = reworkDetails ?? {};

                                $("#txtOthers").on("change", function () {
                                    if (!isViewing) {
                                        $("#txtReworkActivityOthers")
                                            .prop("disabled", !this.checked)
                                            .val(this.checked ? $("#txtReworkActivityOthers").val() : '');
                                    }
                                });

                                // console.log('reworkDetails', rd);
                                // --- Operators ---
                                $('#txtReworkerOperator1').val(rd.reworker_operator1?.name ?? '');
                                $('#txtReworkerOperator2').val(rd.reworker_operator2?.name ?? '');
                                $('#txtReworkerOperatorId1').val(rd.reworker_user_id1 ?? '');
                                $('#txtReworkerOperatorId2').val(rd.reworker_user_id2 ?? '');
                                $('#txtReworkDate').val(rd.rework_date ?? '').prop('readonly', isViewing);

                                // --- Rework activity (normalize to array) ---
                                let selected = rd.rework_activity;

                                // console.log('selected', selected);

                                if (selected == null) {
                                    selected = [];
                                } else if (Array.isArray(selected)) {
                                    // ok
                                } else if (typeof selected === 'string') {
                                    try {
                                        const parsed = JSON.parse(selected);
                                        selected = Array.isArray(parsed) ? parsed : selected.split(',');
                                    } catch {
                                        selected = selected.split(',');
                                    }
                                } else {
                                    selected = [selected];
                                }

                                selected = selected.map(v => String(v).trim()).filter(Boolean);

                                // Uncheck all, then check selected
                                $("[name='rework_activity[]']").prop("checked", false);
                                selected.forEach(val => {
                                    $("[name='rework_activity[]'][value='" + val + "']").prop("checked", true);
                                });

                                // --- OTHERS ---
                                if (selected.includes('10')) {
                                    $("#txtOthers").prop("checked", true);
                                    $("#txtReworkActivityOthers")
                                        .prop("disabled", false)
                                        .val(rd.rework_act_others ?? '');
                                } else {
                                    $("#txtOthers").prop("checked", false);
                                    $("#txtReworkActivityOthers").prop("disabled", true).val('');
                                }

                                // --- Unit results (1–5) ---
                                for (let i = 1; i <= 5; i++) {
                                    const result = rd["unit" + i + "_result"];

                                    $("[name='rework" + i + "'][value='1']")
                                        .prop('checked', result == 1)
                                        .prop('disabled', isViewing && result != 1);

                                    $("[name='rework" + i + "'][value='2']")
                                        .prop('checked', result == 2)
                                        .prop('disabled', isViewing && result != 2);
                                }

                                // --- MPC checking ---
                                $("input[name='mpc_check']").each(function () {
                                    $(this)
                                        .prop('checked', $(this).val() == rd.rework_mpc_checking)
                                        .prop('disabled', isViewing && $(this).val() != rd.rework_mpc_checking);
                                });

                                // --- Activity after rework ---
                                $("input[name='activity_after_rework']").each(function () {
                                    $(this)
                                        .prop('checked', $(this).val() == rd.activity_after_rework)
                                        .prop('disabled', isViewing && $(this).val() != rd.activity_after_rework);
                                });

                                // --- Replaced parts ---
                                $('#replacedPartsContainer').empty();

                                let parts = rd.rework_replace_parts ?? [];
                                let lots  = rd.rework_lot_no ?? [];

                                if (typeof parts === 'string') parts = parts.split(',').map(p => p.trim());
                                if (typeof lots === 'string')  lots  = lots.split(',').map(l => l.trim());

                                if (parts.length === 0) parts = [''];
                                if (lots.length === 0)  lots  = [''];

                                parts.forEach((part, index) => {
                                    let lot = lots[index] ?? '';

                                    $('#replacedPartsContainer').append(`
                                        <div class="replaced-part-row mb-2 border rounded p-2">
                                            <div class="input-group input-group-sm mb-2">
                                                <span class="input-group-text w-50">Part Name</span>
                                                <input type="text" class="form-control" name="r_part_name[]" value="${part}" ${isViewing ? 'readonly' : ''}>
                                            </div>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text w-50">Lot No</span>
                                                <input type="text" class="form-control" name="r_lot_no[]" value="${lot}" ${isViewing ? 'readonly' : ''}>
                                            </div>
                                        </div>
                                    `);
                                });

                            }

                            if (finalDispositionDetails?.f_disposition != null) {

                                $("#txtDispostionResult1")
                                    .prop('checked', finalDispositionDetails.f_disposition == 1)
                                    .prop('disabled', isViewing && finalDispositionDetails.f_disposition != 1);

                                $("#txtDispostionResult2")
                                    .prop('checked', finalDispositionDetails.f_disposition == 2)
                                    .prop('disabled', isViewing && finalDispositionDetails.f_disposition != 2);

                                $('#txtDEngrManagerUser')
                                    .val(finalDispositionDetails.final_disposition_e_manager?.name ?? '')
                                    .prop('readonly', true);

                                $('#txtDEngrManagerUserId')
                                    .val(finalDispositionDetails.final_disposition_e_manager?.id ?? '')
                                    .prop('readonly', true);

                                $('#txtDEngrManagerDateId')
                                    .val(finalDispositionDetails.engr_manager_date ?? '');

                                $('#txtDPEngrUser')
                                    .val(finalDispositionDetails.final_disposition_e_process?.name ?? '')
                                    .prop('readonly', true);

                                $('#txtDPEngrUserId')
                                    .val(finalDispositionDetails.final_disposition_e_process?.id ?? '')
                                    .prop('readonly', true);

                                $('#txtDPEngrDateId')
                                    .val(finalDispositionDetails.process_engr_date ?? '');

                                $('#txtFDispositionRemarks')
                                    .val(finalDispositionDetails.f_disposition_engr_remarks ?? '')
                                    .prop('readonly', isViewing);


                                $("#txtQcDispostionResult1")
                                    .prop('checked', finalDispositionDetails.f_disposition_qc == 1)
                                    .prop('disabled', isViewing && finalDispositionDetails.f_disposition_qc != 1);

                                $("#txtQcDispostionResult2")
                                    .prop('checked', finalDispositionDetails.f_disposition_qc == 2)
                                    .prop('disabled', isViewing && finalDispositionDetails.f_disposition_qc != 2);

                                $('#txtDQCManagerUser')
                                    .val(finalDispositionDetails.final_disposition_qc_manager?.name ?? '')
                                    .prop('readonly', true);

                                $('#txtDQCManagerUserId')
                                    .val(finalDispositionDetails.final_disposition_qc_manager?.id ?? '')
                                    .prop('readonly', true);

                                $('#txtQcManagerDateId')
                                    .val(finalDispositionDetails.qc_manager_date ?? '');

                                $('#txtDQcUser')
                                    .val(finalDispositionDetails.final_disposition_qc_supervisor?.name ?? '')
                                    .prop('readonly', true);

                                $('#txtDQcUserId')
                                    .val(finalDispositionDetails.final_disposition_qc_supervisor?.id ?? '')
                                    .prop('readonly', true);

                                $('#txtDQcDateId')
                                    .val(finalDispositionDetails.qc_supervisor_date ?? '');

                                $('#txtFQcDispositionRemarks')
                                    .val(finalDispositionDetails.f_disposition_qc_remarks ?? '')
                                    .prop('readonly', isViewing);
                            }

                            if(rootAndCauseEscapeeDetails != null){
                                $('#txtRootCauseEscapee').val(rootAndCauseEscapeeDetails?.rc_ec ?? '').prop('readonly', isViewing);
                            }

                            if (capaDetails && capaDetails.length > 0) {
                                let parentContent = $('.iHeaderContent');
                                let templateRow = parentContent.find('.row.mb-3').first();

                                // Remove extra rows
                                parentContent.find('.row.mb-3').not(':first').remove();

                                // Loop through capaDetails
                                capaDetails.forEach((detail, index) => {
                                    // Clone template if not first
                                    let $row = index === 0 ? templateRow : templateRow.clone(false, false);

                                    // Clear/Set textarea and date
                                    $row.find('textarea').val(detail.de_capa);
                                    $row.find('input[type="date"]').val(detail.de_due_date);

                                    // Find select
                                    let $select = $row.find('.selUser');

                                    // Remove old Select2 wrappers
                                    $select.next('.select2-container').remove();

                                    // Clear options and add placeholder
                                    $select.empty().append('<option value="" disabled>-- Select User --</option>');

                                    // Populate options
                                    $.each(capaUserDetails, function(id, name) {
                                        $select.append(new Option(name, id));
                                    });

                                    // Remove ID to avoid duplicates
                                    $select.removeAttr('id');

                                    // Initialize Select2
                                    if ($.fn.select2) {
                                        $select.select2({
                                            width: '100%',
                                            theme: 'bootstrap4',
                                            placeholder: '-- Select User --',
                                            allowClear: true
                                        });
                                    }

                                    // Set selected user if exists
                                    if (detail.de_pic_id) {
                                        $select.val(String(detail.de_pic_id)).trigger('change');
                                    }

                                    // Append cloned row (skip first since it’s template)
                                    if (index > 0) {
                                        parentContent.append($row);
                                    }
                                });
                            }

                            if(verificationAndConfirmationResults != null){
                                $('#txtActionStated').val(verificationAndConfirmationResults?.action_stated ?? '').prop('readonly', isViewing);
                                $('#txtProblemReoccur').val(verificationAndConfirmationResults?.prodblem_reoccur ?? '').prop('readonly', isViewing);
                                $('#txtDerivedActionsDocumented').val(verificationAndConfirmationResults?.actions_documented ?? '').prop('readonly', isViewing);
                                $('#txtResultConfirmation').val(verificationAndConfirmationResults?.confirmation_result ?? '').prop('readonly', isViewing);
                                $('#txtVerificationRemarks').val(verificationAndConfirmationResults?.remarks ?? '').prop('readonly', isViewing);


                                if( verificationAndConfirmationResults.qc_inspector != null){
                                    $('#txtVCInspectorUser').val(verificationAndConfirmationResults.qc_inspector.name).prop('readonly' ,true);
                                    $('#txtVCInspectorUserId').val(verificationAndConfirmationResults.qc_inspector.id).prop('readonly' ,true);
                                    $('#txtVcQcInspectorDateId').val(verificationAndConfirmationResults?.qc_inspector_date ?? '').prop('readonly');
                                }

                                if( verificationAndConfirmationResults.qc_supervisor != null){
                                    $('#txtVCSupervisorUser').val(verificationAndConfirmationResults.qc_supervisor.name).prop('readonly' ,true);
                                    $('#txtVCSupervisorUserId').val(verificationAndConfirmationResults.qc_supervisor.id).prop('readonly' ,true);
                                    $('#txtVcQcSupervisorDateId').val(verificationAndConfirmationResults?.qc_supervisor_date ?? '').prop('readonly');
                                }

                                if( verificationAndConfirmationResults.qc_manager != null){
                                    $('#txtVCManagerUser').val(verificationAndConfirmationResults.qc_manager.name).prop('readonly' ,true);
                                    $('#txtVCManagerUserId').val(verificationAndConfirmationResults.qc_manager.id).prop('readonly' ,true);
                                    $('#txtVcQcManagerDateId').val(verificationAndConfirmationResults?.qc_manager_date ?? '').prop('readonly');
                                }


                            }

                        }

                    }

                    else if(data.de_occurence == 2 && (data.defect_classification == 2 || data.defect_classification == 3 ||data.defect_classification == 4 || data.defect_classification == 5)){
                        if(data.defect_status == 0 || data.defect_status == 1  || data.defect_status == 2|| data.defect_status == 3 || data.defect_status == 4 || data.defect_status == 5 || data.defect_status == 6 || data.defect_status == 7 || data.defect_status == 8){
                            $('.cHeaderClass').attr('hidden', !pvIsCompelete);
                            $('.dHeaderClass').attr('hidden', !iaIsComplete);
                            $('.backTabClass').attr('hidden', !caIsComplete);
                            $('.ifReworkHeaderClass').attr('hidden', !caIsComplete);
                            $('.gHeaderClass').attr('hidden', !rwdIsComplete);
                            $('.hHeaderClass').attr('hidden', !fdIsComplete);
                            $('.iHeaderClass').attr('hidden', !rcecIsComplete);
                            $('.jHeaderClass').attr('hidden', !cdIsComplete);

                            if(data.problem_verificaiton_details != null){
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

                            }

                            if(data.immediate_action_details != null){
                                const iad = data.immediate_action_details ?? {};
                                // ================= MAN =================
                                $('#txtActionDetailsMan')
                                    .val(iad.at_man_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenMan')
                                    .val(iad.at_man_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectMan = $('#txtSelectUserMan');
                                $.each(manUserDetails, function (id, name) {
                                    if ($selectMan.find(`option[value="${id}"]`).length === 0) {
                                        $selectMan.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectMan.val(Object.keys(manUserDetails)).trigger('change');

                                // ================= MACHINE =================
                                $('#txtActionDetailsMachine')
                                    .val(iad.at_machine_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenMachine')
                                    .val(iad.at_machine_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectMachine = $('#txtSelectUserMachine');
                                $.each(machineUserDetails, function (id, name) {
                                    if ($selectMachine.find(`option[value="${id}"]`).length === 0) {
                                        $selectMachine.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectMachine.val(Object.keys(machineUserDetails)).trigger('change');

                                // ================= MATERIAL =================
                                $('#txtActionDetailsMaterial')
                                    .val(iad.at_material_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenMaterial')
                                    .val(iad.at_material_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectMaterial = $('#txtSelectUserMaterial');
                                $.each(materialUserDetails, function (id, name) {
                                    if ($selectMaterial.find(`option[value="${id}"]`).length === 0) {
                                        $selectMaterial.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectMaterial.val(Object.keys(materialUserDetails)).trigger('change');

                                // ================= METHOD =================
                                $('#txtActionDetailsMethod')
                                    .val(iad.at_method_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenMethod')
                                    .val(iad.at_method_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectMethod = $('#txtSelectUserMethod');
                                $.each(methodUserDetails, function (id, name) {
                                    if ($selectMethod.find(`option[value="${id}"]`).length === 0) {
                                        $selectMethod.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectMethod.val(Object.keys(methodUserDetails)).trigger('change');

                                // ================= ENVIRONMENT =================
                                $('#txtActionDetailsEnvironment')
                                    .val(iad.at_environment_action_details ?? '')
                                    .prop('readonly', isViewing);

                                $('#txtWhenEnvironment')
                                    .val(iad.at_environment_date ?? '')
                                    .prop('readonly', isViewing);

                                let $selectEnvironment = $('#txtSelectUserEnvironment');
                                $.each(environmentUserDetails, function (id, name) {
                                    if ($selectEnvironment.find(`option[value="${id}"]`).length === 0) {
                                        $selectEnvironment.append(`<option value="${id}">${name}</option>`);
                                    }
                                });
                                $selectEnvironment.val(Object.keys(environmentUserDetails)).trigger('change');
                            }

                            if(data.containment_action_details != null){
                                const cad = data?.containment_action_details ?? {};
                                $('#txtWipTotalQty').val(cad.wip_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtWipOkQty').val(cad.wip_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtWipNgQty').val(cad.wip_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtWipMod').val(cad.wip_mod ?? '').prop('readonly', isViewing);
                                $('#txtWipRemarks').val(cad.wip_remarks ?? '').prop('readonly', isViewing);

                                $('#txtPfTotalQty').val(cad.pf_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtPfOkQty').val(cad.pf_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtPfNgQty').val(cad.pf_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtPfMod').val(cad.pf_mod ?? '').prop('readonly', isViewing);
                                $('#txtPfRemarks').val(cad.pf_remarks ?? '').prop('readonly', isViewing);

                                $('#txtOqcTotalQty').val(cad.qc_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtOqcOkQty').val(cad.qc_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtOqcNgQty').val(cad.qc_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtOqcMod').val(cad.qc_mod ?? '').prop('readonly', isViewing);
                                $('#txtOqcRemarks').val(cad.qc_remarks ?? '').prop('readonly', isViewing);

                                $('#txtPackingTotalQty').val(cad.p_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtPackingOkQty').val(cad.p_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtPackingNgQty').val(cad.p_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtPackingMod').val(cad.p_mod ?? '').prop('readonly', isViewing);
                                $('#txtPackingRemarks').val(cad.p_remarks ?? '').prop('readonly', isViewing);

                                $('#txtShippedTotalQty').val(cad.s_total_qty ?? '').prop('readonly', isViewing);
                                $('#txtShippedOkQty').val(cad.s_ok_qty ?? '').prop('readonly', isViewing);
                                $('#txtShippedNgQty').val(cad.s_ng_qty ?? '').prop('readonly', isViewing);
                                $('#txtShippedMod').val(cad.s_mod ?? '').prop('readonly', isViewing);
                                $('#txtShippedRemarks').val(cad.s_remarks ?? '').prop('readonly', isViewing);
                            }

                            if(reworkDetails != null){
                                const rd = reworkDetails ?? {};

                                $("#txtOthers").on("change", function () {
                                    if (!isViewing) {
                                        $("#txtReworkActivityOthers")
                                            .prop("disabled", !this.checked)
                                            .val(this.checked ? $("#txtReworkActivityOthers").val() : '');
                                    }
                                });

                                // console.log('reworkDetails', rd);
                                // --- Operators ---
                                $('#txtReworkerOperator1').val(rd.reworker_operator1?.name ?? '');
                                $('#txtReworkerOperator2').val(rd.reworker_operator2?.name ?? '');
                                $('#txtReworkerOperatorId1').val(rd.reworker_user_id1 ?? '');
                                $('#txtReworkerOperatorId2').val(rd.reworker_user_id2 ?? '');
                                $('#txtReworkDate').val(rd.rework_date ?? '').prop('readonly', isViewing);

                                // --- Rework activity (normalize to array) ---
                                let selected = rd.rework_activity;

                                // console.log('selected', selected);

                                if (selected == null) {
                                    selected = [];
                                } else if (Array.isArray(selected)) {
                                    // ok
                                } else if (typeof selected === 'string') {
                                    try {
                                        const parsed = JSON.parse(selected);
                                        selected = Array.isArray(parsed) ? parsed : selected.split(',');
                                    } catch {
                                        selected = selected.split(',');
                                    }
                                } else {
                                    selected = [selected];
                                }

                                selected = selected.map(v => String(v).trim()).filter(Boolean);

                                // Uncheck all, then check selected
                                $("[name='rework_activity[]']").prop("checked", false);
                                selected.forEach(val => {
                                    $("[name='rework_activity[]'][value='" + val + "']").prop("checked", true);
                                });

                                // --- OTHERS ---
                                if (selected.includes('10')) {
                                    $("#txtOthers").prop("checked", true);
                                    $("#txtReworkActivityOthers")
                                        .prop("disabled", false)
                                        .val(rd.rework_act_others ?? '');
                                } else {
                                    $("#txtOthers").prop("checked", false);
                                    $("#txtReworkActivityOthers").prop("disabled", true).val('');
                                }

                                // --- Unit results (1–5) ---
                                for (let i = 1; i <= 5; i++) {
                                    const result = rd["unit" + i + "_result"];

                                    $("[name='rework" + i + "'][value='1']")
                                        .prop('checked', result == 1)
                                        .prop('disabled', isViewing && result != 1);

                                    $("[name='rework" + i + "'][value='2']")
                                        .prop('checked', result == 2)
                                        .prop('disabled', isViewing && result != 2);
                                }

                                // --- MPC checking ---
                                $("input[name='mpc_check']").each(function () {
                                    $(this)
                                        .prop('checked', $(this).val() == rd.rework_mpc_checking)
                                        .prop('disabled', isViewing && $(this).val() != rd.rework_mpc_checking);
                                });

                                // --- Activity after rework ---
                                $("input[name='activity_after_rework']").each(function () {
                                    $(this)
                                        .prop('checked', $(this).val() == rd.activity_after_rework)
                                        .prop('disabled', isViewing && $(this).val() != rd.activity_after_rework);
                                });

                                // --- Replaced parts ---
                                $('#replacedPartsContainer').empty();

                                let parts = rd.rework_replace_parts ?? [];
                                let lots  = rd.rework_lot_no ?? [];

                                if (typeof parts === 'string') parts = parts.split(',').map(p => p.trim());
                                if (typeof lots === 'string')  lots  = lots.split(',').map(l => l.trim());

                                if (parts.length === 0) parts = [''];
                                if (lots.length === 0)  lots  = [''];

                                parts.forEach((part, index) => {
                                    let lot = lots[index] ?? '';

                                    $('#replacedPartsContainer').append(`
                                        <div class="replaced-part-row mb-2 border rounded p-2">
                                            <div class="input-group input-group-sm mb-2">
                                                <span class="input-group-text w-50">Part Name</span>
                                                <input type="text" class="form-control" name="r_part_name[]" value="${part}" ${isViewing ? 'readonly' : ''}>
                                            </div>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text w-50">Lot No</span>
                                                <input type="text" class="form-control" name="r_lot_no[]" value="${lot}" ${isViewing ? 'readonly' : ''}>
                                            </div>
                                        </div>
                                    `);
                                });

                            }

                            if (finalDispositionDetails?.f_disposition != null) {

                                $("#txtDispostionResult1")
                                    .prop('checked', finalDispositionDetails.f_disposition == 1)
                                    .prop('disabled', isViewing && finalDispositionDetails.f_disposition != 1);

                                $("#txtDispostionResult2")
                                    .prop('checked', finalDispositionDetails.f_disposition == 2)
                                    .prop('disabled', isViewing && finalDispositionDetails.f_disposition != 2);

                                $('#txtDEngrManagerUser')
                                    .val(finalDispositionDetails.final_disposition_e_manager?.name ?? '')
                                    .prop('readonly', true);

                                $('#txtDEngrManagerUserId')
                                    .val(finalDispositionDetails.final_disposition_e_manager?.id ?? '')
                                    .prop('readonly', true);

                                $('#txtDEngrManagerDateId')
                                    .val(finalDispositionDetails.engr_manager_date ?? '');

                                $('#txtDPEngrUser')
                                    .val(finalDispositionDetails.final_disposition_e_process?.name ?? '')
                                    .prop('readonly', true);

                                $('#txtDPEngrUserId')
                                    .val(finalDispositionDetails.final_disposition_e_process?.id ?? '')
                                    .prop('readonly', true);

                                $('#txtDPEngrDateId')
                                    .val(finalDispositionDetails.process_engr_date ?? '');

                                $('#txtFDispositionRemarks')
                                    .val(finalDispositionDetails.f_disposition_engr_remarks ?? '')
                                    .prop('readonly', isViewing);


                                $("#txtQcDispostionResult1")
                                    .prop('checked', finalDispositionDetails.f_disposition_qc == 1)
                                    .prop('disabled', isViewing && finalDispositionDetails.f_disposition_qc != 1);

                                $("#txtQcDispostionResult2")
                                    .prop('checked', finalDispositionDetails.f_disposition_qc == 2)
                                    .prop('disabled', isViewing && finalDispositionDetails.f_disposition_qc != 2);

                                $('#txtDQCManagerUser')
                                    .val(finalDispositionDetails.final_disposition_qc_manager?.name ?? '')
                                    .prop('readonly', true);

                                $('#txtDQCManagerUserId')
                                    .val(finalDispositionDetails.final_disposition_qc_manager?.id ?? '')
                                    .prop('readonly', true);

                                $('#txtQcManagerDateId')
                                    .val(finalDispositionDetails.qc_manager_date ?? '');

                                $('#txtDQcUser')
                                    .val(finalDispositionDetails.final_disposition_qc_supervisor?.name ?? '')
                                    .prop('readonly', true);

                                $('#txtDQcUserId')
                                    .val(finalDispositionDetails.final_disposition_qc_supervisor?.id ?? '')
                                    .prop('readonly', true);

                                $('#txtDQcDateId')
                                    .val(finalDispositionDetails.qc_supervisor_date ?? '');

                                $('#txtFQcDispositionRemarks')
                                    .val(finalDispositionDetails.f_disposition_qc_remarks ?? '')
                                    .prop('readonly', isViewing);
                            }

                            if(rootAndCauseEscapeeDetails != null){
                                $('#txtRootCauseEscapee').val(rootAndCauseEscapeeDetails?.rc_ec ?? '').prop('readonly', isViewing);
                            }

                            if (capaDetails && capaDetails.length > 0) {
                                let parentContent = $('.iHeaderContent');
                                let templateRow = parentContent.find('.row.mb-3').first();

                                // Remove extra rows
                                parentContent.find('.row.mb-3').not(':first').remove();

                                // Loop through capaDetails
                                capaDetails.forEach((detail, index) => {
                                    // Clone template if not first
                                    let $row = index === 0 ? templateRow : templateRow.clone(false, false);

                                    // Clear/Set textarea and date
                                    $row.find('textarea').val(detail.de_capa);
                                    $row.find('input[type="date"]').val(detail.de_due_date);

                                    // Find select
                                    let $select = $row.find('.selUser');

                                    // Remove old Select2 wrappers
                                    $select.next('.select2-container').remove();

                                    // Clear options and add placeholder
                                    $select.empty().append('<option value="" disabled>-- Select User --</option>');

                                    // Populate options
                                    $.each(capaUserDetails, function(id, name) {
                                        $select.append(new Option(name, id));
                                    });

                                    // Remove ID to avoid duplicates
                                    $select.removeAttr('id');

                                    // Initialize Select2
                                    if ($.fn.select2) {
                                        $select.select2({
                                            width: '100%',
                                            theme: 'bootstrap4',
                                            placeholder: '-- Select User --',
                                            allowClear: true
                                        });
                                    }

                                    // Set selected user if exists
                                    if (detail.de_pic_id) {
                                        $select.val(String(detail.de_pic_id)).trigger('change');
                                    }

                                    // Append cloned row (skip first since it’s template)
                                    if (index > 0) {
                                        parentContent.append($row);
                                    }
                                });
                            }

                            if(verificationAndConfirmationResults != null){
                                $('#txtActionStated').val(verificationAndConfirmationResults?.action_stated ?? '').prop('readonly', isViewing);
                                $('#txtProblemReoccur').val(verificationAndConfirmationResults?.prodblem_reoccur ?? '').prop('readonly', isViewing);
                                $('#txtDerivedActionsDocumented').val(verificationAndConfirmationResults?.actions_documented ?? '').prop('readonly', isViewing);
                                $('#txtResultConfirmation').val(verificationAndConfirmationResults?.confirmation_result ?? '').prop('readonly', isViewing);
                                $('#txtVerificationRemarks').val(verificationAndConfirmationResults?.remarks ?? '').prop('readonly', isViewing);


                                if( verificationAndConfirmationResults.qc_inspector != null){
                                    $('#txtVCInspectorUser').val(verificationAndConfirmationResults.qc_inspector.name).prop('readonly' ,true);
                                    $('#txtVCInspectorUserId').val(verificationAndConfirmationResults.qc_inspector.id).prop('readonly' ,true);
                                    $('#txtVcQcInspectorDateId').val(verificationAndConfirmationResults?.qc_inspector_date ?? '').prop('readonly');
                                }

                                if( verificationAndConfirmationResults.qc_supervisor != null){
                                    $('#txtVCSupervisorUser').val(verificationAndConfirmationResults.qc_supervisor.name).prop('readonly' ,true);
                                    $('#txtVCSupervisorUserId').val(verificationAndConfirmationResults.qc_supervisor.id).prop('readonly' ,true);
                                    $('#txtVcQcSupervisorDateId').val(verificationAndConfirmationResults?.qc_supervisor_date ?? '').prop('readonly');
                                }

                                if( verificationAndConfirmationResults.qc_manager != null){
                                    $('#txtVCManagerUser').val(verificationAndConfirmationResults.qc_manager.name).prop('readonly' ,true);
                                    $('#txtVCManagerUserId').val(verificationAndConfirmationResults.qc_manager.id).prop('readonly' ,true);
                                    $('#txtVcQcManagerDateId').val(verificationAndConfirmationResults?.qc_manager_date ?? '').prop('readonly');
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

    $(".btnScanVerificationId").click(function () {
        scanContext = $(this).data("type");
        $('#modalScanProductionId').modal('show');
        $("#txtSearchProdId").val('');
        $("#scanProdIdBody").text('Please Scan ID...');
    });

    $(".btnScanVerification2Id").click(function () {
        verification2Context = $(this).data("type");
        $('#modalScanVerification2Id').modal('show');
        $("#txtSearchVerification2").val('');
        $("#scanVerification2IdBody").text('Please Scan ID...');
    });

    $(".btnScanReworker").click(function () {
        reworkerContext = $(this).data("type");
        $('#modalScanReworkerId').modal('show');
        $("#txtReworkerId").val('');
        $("#scanReworkerIdBody").text('Please Scan ID...');
    });

    $('#modalScanForVerificationOngoing').off('shown.bs.modal').on('shown.bs.modal', function () {
        let defectIdForVerification = $('#txtDefectId').val();
        // console.log('defectIdForVerification', defectIdForVerification)

        $('#txtSearchForVerificationOngoingId').val('').focus();

        // remove previous keypress handler
        $(document).off('keypress.verificationScan');

        $(document).on('keypress.verificationScan', function (e) {

            if (e.which !== 13) return; // Enter only

            let verificationUser = $('#txtSearchForVerificationOngoingId').val().trim();
            if (!verificationUser) return;

            $.ajax({
                url: "get_verification_user_ongoing",
                type: "GET",
                data: {
                    scanned_verification_user: verificationUser,
                    defect_id : defectIdForVerification
                },
                success: function (response) {

                    if (response.success) {
                        // ✅ UPDATE STATUS IN UI (optional)
                        $('#currentVerificationStatus').text('VERIFIED');

                        // ✅ Close modal
                        $('#modalScanForVerificationOngoing').modal('hide');
                        $('#modalAddDefectEscalation').modal('hide');

                        defectEscalationDataTable.ajax.reload();
                        toastr.success('Verification successful');

                    } else {
                        toastr.error(response.message || 'Invalid user');
                    }
                },
                error: function () {
                    toastr.error('Error while verifying user');
                }
            });
        });
    });


    $('#modalScanReworkerId').on('shown.bs.modal', function(e){
        $('#txtReworkerId').focus();
        $(document).on('keypress', function(e){
            reworkerScannedId = $('#txtReworkerId').val();
            if(reworkerScannedId != ''){
                if(e.keyCode == 13){
                    $.ajax({
                        url: "get_reworker_user",
                        type: 'GET',
                        data: {
                            scanned_reworker_id: reworkerScannedId,
                            scan_context: reworkerContext
                        },
                        success: function(response) {
                            if (response.status === 'success') {

                                if (reworkerContext === 'R1') {
                                    $('#txtReworkerOperator1').val(response.user.name);
                                    $('#txtReworkerOperatorId1').val(response.user.id);
                                }else{
                                    $('#txtReworkerOperator2').val(response.user.name);
                                    $('#txtReworkerOperatorId2').val(response.user.id);
                                }

                                $('#modalScanReworkerId').modal('hide');
                            } else {
                                    alert(response.message + '. Please try again or contact the administrator.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while searching for the employee.');
                        }
                    });
                }
            }
        });
    });

    $(".btnScanFinalDisposition").click(function () {
        finalDispositionContext = $(this).data("type");
        $('#modalScanFinalDispositionId').modal('show');
        $("#txtSearchFinalDispositionUserId").val('');
        $("#scanFInalDispositionIdBody").text('Please Scan ID...');
    });

    $('#modalScanFinalDispositionId').on('shown.bs.modal', function(e){
        $('#txtSearchFinalDispositionUserId').focus();
        $(document).on('keypress', function(e){
            finalDispositionScannedId = $('#txtSearchFinalDispositionUserId').val();
            if(finalDispositionScannedId != ''){
                if(e.keyCode == 13){
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
                        url: "get_final_disposition_user",
                        type: 'GET',
                        data: {
                            scanned_final_disposition_user: finalDispositionScannedId,
                            scan_context: finalDispositionContext
                        },
                        success: function(response) {
                            if (response.status === 'success') {

                                // console.log('test');

                                if (finalDispositionContext === 'FEM') {
                                    $('#txtDEngrManagerUser').val(response.user.name);
                                    $('#txtDEngrManagerUserId').val(response.user.id);
                                    $('#txtDEngrManagerDateId').val(formattedDateTime);
                                }else if(finalDispositionContext === 'FPE'){
                                    $('#txtDPEngrUser').val(response.user.name);
                                    $('#txtDPEngrUserId').val(response.user.id);
                                    $('#txtDPEngrDateId').val(formattedDateTime);
                                }else if(finalDispositionContext === 'FQCM'){
                                    $('#txtDQCManagerUser').val(response.user.name);
                                    $('#txtDQCManagerUserId').val(response.user.id);
                                    $('#txtQcManagerDateId').val(formattedDateTime);
                                }else if(finalDispositionContext === 'DQS'){
                                    $('#txtDQcUser').val(response.user.name);
                                    $('#txtDQcUserId').val(response.user.id);
                                    $('#txtDQcDateId').val(formattedDateTime);
                                }

                                $('#modalScanFinalDispositionId').modal('hide');
                            // } else {
                            //         alert(response.message + '. Please try again or contact the administrator.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while searching for the employee.');
                        }
                    });
                }
            }
        });
    });

    $('#modalScanVerification2Id').on('shown.bs.modal', function(e){
        $('#txtSearchVerification2').focus();
        $(document).on('keypress', function(e){
            verification2ScannedId = $('#txtSearchVerification2').val();
            if(verification2ScannedId != ''){
                if(e.keyCode == 13){
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
                        url: "get_2nd_verification_user",
                        type: 'GET',
                        data: {
                            scanned_verification2: verification2ScannedId,
                            scan_context: verification2Context
                        },
                        success: function(response) {
                            if (response.status === 'success') {

                                // console.log('test');

                                if (verification2Context === 'P2') {
                                    $('#txtPLevelTwoUser').val(response.user.name);
                                    $('#txtPLevelTwoUserId').val(response.user.id);
                                    $('#txtPLevelTwoDate').val(formattedDateTime);
                                }else if(verification2Context === 'E2'){
                                    $('#txtELevelTwoUser').val(response.user.name);
                                    $('#txtELevelTwoUserId').val(response.user.id);
                                    $('#txtELevelTwoDate').val(formattedDateTime);
                                }else if(verification2Context === 'Q2'){
                                    $('#txtQcLevelTwoUser').val(response.user.name);
                                    $('#txtQcLevelTwoUserId').val(response.user.id);
                                    $('#txtQcLevelTwoDate').val(formattedDateTime);
                                }else if(verification2Context === 'PPC'){
                                    $('#txtPpcLevelTwoUser').val(response.user.name);
                                    $('#txtPpcLevelTwoUserId').val(response.user.id);
                                    $('#txtPpcLevelTwoDate').val(formattedDateTime);
                                }else if(verification2Context === 'TA'){
                                    $('#txtTaLevelTwoUser').val(response.user.name);
                                    $('#txtTaLevelTwoUserId').val(response.user.id);
                                    $('#txtTaLevelTwoDate').val(formattedDateTime);
                                }

                                $('#modalScanVerification2Id').modal('hide');
                            } else {
                                    alert(response.message + '. Please try again or contact the administrator.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while searching for the employee.');
                        }
                    });
                }
            }
        });
    });


    $('#modalScanProductionId').on('shown.bs.modal', function (e) {
        $('#txtSearchProdId').focus();
        $(document).on('keypress',function(e){
            // e.preventDefault();
            scannedId = $('#txtSearchProdId').val();

            if( e.keyCode == 13){
                if(scannedId != ''){
                    // console.log('scanContext', scanContext);
                    // console.log(scannedId);
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
                    // $(this).val('');

                } else {
                    alert('Please scan.');
                }
            }
        });
    });


    $(".btnScanVC").click(function () {
        qcContext = $(this).data("type");
        $('#modalScanVerificationAndConfirmation').modal('show');
        $("#txtSearchForVerificationAndConfirmationId").val('');
        $("#scanVerificationAndConfirmationIqcBody").text('Please Scan ID...');
    });

    $('#modalScanVerificationAndConfirmation').on('shown.bs.modal', function (e) {
        $('#txtSearchForVerificationAndConfirmationId').focus();
        $(document).on('keypress',function(e){
            // e.preventDefault();
            scannedId = $('#txtSearchForVerificationAndConfirmationId').val();

            if( e.keyCode == 13){
                if(scannedId != ''){
                    // console.log('qcContext', qcContext);
                    // console.log(scannedId);
                    const now = new Date();
                // Format as YYYY-MM-DD HH:mm:ss
                    const formattedDateTime = now.getFullYear() + '-' +
                    String(now.getMonth() + 1).padStart(2, '0') + '-' +
                    String(now.getDate()).padStart(2, '0') + ' ' +
                    String(now.getHours()).padStart(2, '0') + ':' +
                    String(now.getMinutes()).padStart(2, '0') + ':' +
                    String(now.getSeconds()).padStart(2, '0');

                    $.ajax({
                        url: "get_qc_verification_user",
                        type: 'GET',
                        data: {
                            scanned_id: scannedId,
                            scan_context: qcContext
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                if (qcContext === 'VCIQC') {
                                    $('#txtVCInspectorUser').val(response.user.name);
                                    $('#txtVCInspectorUserId').val(response.user.id);
                                    $('#txtVcQcInspectorDateId').val(formattedDateTime);
                                } else if (qcContext === 'VCQC') {
                                    $('#txtVCSupervisorUser').val(response.user.name);
                                    $('#txtVCSupervisorUserId').val(response.user.id);
                                    $('#txtVcQcSupervisorDateId').val(formattedDateTime);
                                } else if (qcContext === 'VCQM') {
                                    $('#txtVCManagerUser').val(response.user.name);
                                    $('#txtVCManagerUserId').val(response.user.id);
                                    $('#txtVcQcManagerDateId').val(formattedDateTime);
                                }
                                $('#modalScanVerificationAndConfirmation').modal('hide');

                            } else {
                                if(qcContext === 'VCIQC'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }else if(qcContext === 'VCQC'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }else if(qcContext === 'VCQM'){
                                    alert(response.message + '. Please try again or contact the administrator.');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while searching for the employee.');
                        }
                    });
                    // $(this).val('');

                } else {
                    alert('Please scan.');
                }
            }
        });
    });

    $('#btnShowAddProdRuncard').click(function(){
        // if($("#txt_po_number_lbl").val() == "" || $("#txt_po_number_lbl").val() == null){
        //         toastr.warning('PO not found!');
        // }else{
            $('#modalAddDefectEscalation').modal('show');
        // }

        // $('#txtModType').on('change', function () {
            const modType = $(this).val();
            const cboElement = $('#txtRuncardNumber');

            // if (modType === "2") {
                // console.log('MOD TYPE = 2');
                getRuncardWithDefect(cboElement, poNumber); // make sure poNumber is defined in scope
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
        $('.nav-tabs .nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');

        $('#front-tab').addClass('active');
        $('#front').addClass('show active');

        // Use delegated binding ONCE, outside modal open event
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

    // Initialize all existing rows
    $('.iHeaderContent .capaRowTemplate select.selCapaUser').each(function() {
        let $select = $(this);

        // Populate users
        GetUserForPersonInCharge($select);

        // Initialize Select2
        if ($.fn.select2 && !$select.hasClass('select2-hidden-accessible')) {
            $select.select2({
                theme: 'bootstrap4',
                placeholder: '-- Select User --',
                allowClear: true,
                width: '100%'
            });
        }
    });

    // Add new CAPA row
    $(document).on('click', '.addCapaRowBtn', function() {
        let $parent = $(this).closest('.iHeaderContent');
        let $template = $parent.find('.capaRowTemplate').first();

        // Clone the template row
        let $newRow = $template.clone(false, false);

        // Remove old Select2 wrappers if any
        $newRow.find('.select2-container').remove();

        // Clear input fields
        $newRow.find('textarea').val('');
        $newRow.find('input[type="date"]').val('');

        // Remove IDs to avoid duplicates
        $newRow.find('[id]').removeAttr('id');

        // Handle select elements
        $newRow.find('select.selCapaUser').each(function() {
            let $oldSelect = $(this);

            // Clear old options
            $oldSelect.empty().append('<option value="" disabled selected>-- Loading --</option>');

            // Populate options via your function
            GetUserForPersonInCharge($oldSelect);

            // Initialize Select2 safely
            if ($.fn.select2 && !$oldSelect.hasClass('select2-hidden-accessible')) {
                $oldSelect.select2({
                    theme: 'bootstrap4',
                    placeholder: '-- Select User --',
                    allowClear: true,
                    width: '100%'
                });
            }
        });

        // Append new row
        $parent.append($newRow);
    });

    // Remove CAPA row
    $(document).on('click', '.removeCapaRowBtn', function() {
        let $parent = $(this).closest('.iHeaderContent');
        let $rows = $parent.find('.capaRowTemplate');
        let $row = $(this).closest('.capaRowTemplate');

        if ($rows.length > 1) {
            // Destroy Select2 first to avoid dangling instances
            $row.find('select.selCapaUser').each(function() {
                let $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
            });
            $row.remove();
        } else {
            // Last row: just reset
            $row.find('select.selCapaUser').val(null).trigger('change');
            $row.find('textarea').val('');
            $row.find('input[type="date"]').val('');
        }
    });



        $('#txtRuncardNumber').on('change', function () {

            const modStationId = $(this).val();
            const $selectedOption = $('#txtRuncardNumber option:selected');

            const runcardId = $selectedOption.data('rid');
            const runcardStationId = $selectedOption.data('rsid');

            $('#txtStationModId').val(modStationId);
            $('#txtRuncardStationId').val(runcardStationId);
                $('#txtRuncardId').val(runcardId);
        });
    });

    $('#formAddDefectEscalation').submit(function (e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "add_defect_escalation_test",
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
                getModOfDefect($("#txtDefects"), defectClassification)
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

            }
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
        popup = window.open();
        let content = '';
        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';
        content += '.rotated {';
        content += 'width: 160px;';
        content += 'position: absolute;';
        content += 'left: 8.5px;';
        content += 'top: 12px;';
        content += '}';


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
        content += '</center>';
        content += '</td>';
        content += '</tr>';

        let date = new Date();

        content += '<tr>';
        content += '<td colspan="4" style="padding: 1px 1px;" cellspacing="0";>';
        content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 4px; margin 1px 1px;">';
        content += date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
        content += '</label>';
        content += '</center></td>';

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

    // Add new replaced part row
    $(document).on('click', '.addReplaceParts', function () {
        let newRow = `
        <div class="replaced-part-row mb-2">
            <div class="input-group input-group-sm mb-2">
            <span class="input-group-text w-50">Part Name</span>
            <input type="text" class="form-control" name="r_part_name[]" autocomplete="off">
            </div>
            <div class="input-group input-group-sm">
            <span class="input-group-text w-50">Lot No</span>
            <input type="text" class="form-control" name="r_lot_no[]" autocomplete="off">
            </div>
        </div>
        `;
        $('#replacedPartsContainer').append(newRow);
    });

    //  Remove last replaced part row (keep at least one)
    $(document).on('click', '.removeReplaceParts', function () {
        let rows = $('#replacedPartsContainer .replaced-part-row');
        if (rows.length > 1) {
        rows.last().remove();
        } else {
        alert('You must have at least one row.');
        }
    });

    $('#modalAddDefectEscalation').on('hidden.bs.modal', function () {
        resetDefectModal();
        // $('.bToggleHeader').slideUp(200);
        $('#bHeaderIcon').html('&#x25BA;');
        $('.bHeaderVerificationHeader').hide();
        $('.bHeaderDispositionHeader').hide();

        $('.bHeaderVerificationContent').slideUp(200);
        $('#bHeaderVerificationContentIcon').html('&#x25BA;');

        $('.bHeaderDispositionContent').slideUp(200);
        $('#bHeaderDispositionContentIcon').html('&#x25BA;');




        $('#cHeaderIcon').html('&#x25BA;');
        $('.cHeaderContent').slideUp(200);
        $('.dHeaderContent').slideUp(200);
        $('#dHeaderIcon').html('&#x25BA;');
        $('.eHeaderContent').slideUp(200);
        $('#eHeaderIcon').html('&#x25BA;');
        $('.fHeaderContent').slideUp(200);
        $('#fHeaderIcon').html('&#x25BA;');
        $('.gHeaderContent').slideUp(200);
        $('#gHeaderIcon').html('&#x25BA;');
        $('.hHeaderContent').slideUp(200);
        $('#hHeaderIcon').html('&#x25BA;');
        $('.iHeaderContent').slideUp(200);
        $('#iHeaderIcon').html('&#x25BA;');
        $('.jHeaderContent').slideUp(200);
        $('#jHeaderIcon').html('&#x25BA;');
    });

});

function hasAll(values) {
    return values.every(v => v !== null && v !== undefined && v !== '');
}

function resetDefectModal() {

    // Reset inputs
    $('#modalAddDefectEscalation').find('input, textarea').each(function () {
        $(this).val('');
        $(this).prop('checked', false);
        $(this).prop('readonly', false);
        $(this).prop('disabled', false);
    });

    // Reset hidden inputs
    $('#modalAddDefectEscalation').find('input[type="hidden"]').val('');

    // Hide conditional sections
    $('.cHeaderClass').attr('hidden', true);
    $('.dHeaderClass').attr('hidden', true);
    // $('.likod').attr('hidden', true);

}

</script>

@include('prod_runcard.new_script')
@endsection
@endauth
