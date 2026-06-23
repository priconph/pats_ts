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

  @section('title', 'Warehouse Material Kitting')

  @section('content_page')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Warehouse Material Kitting</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Warehouse Material Kitting</li>
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

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">WBS Material Kitting & Issuance</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <form id="frmSearchKittingInfo">
                  <div class="row">

                      <div class="col-sm-4">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Issuance No.</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="kit_issuance_id" style="display: none;">
                              <!-- <input type="text" class="form-control form-control" name="issuance_no" value="WHS-1710-00396"> -->
                              <input type="text" class="form-control form-control" name="issuance_no" value="WHS-{{ date('y') }}{{ date('m') }}-" placeholder="Type and Press Enter" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">PO No.</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="po_no" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Device Code</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="device_code" id="device_code" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Device Name</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="device_name" id="device_name" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">PO Qty</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="po_qty" readonly="true">
                            </div>
                          </div>
                      </div>

                      <div class="col-sm-4">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kit Qty.</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="kit_qty" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kit No.</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="kit_no" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Prep. By</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="prep_by" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="status" readonly="true">
                            </div>
                          </div>
                      </div>

                      <div class="col-sm-4">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Created By</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="created_by" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Created Date</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="created_date" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Updated By</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="updated_by" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Updated Date</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="updated_date" readonly="true">
                            </div>
                          </div>
                      </div>

                    </div>
                  </form>

                  <br><br>
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-toggle="tab" href="#tabKitDetails" role="tab" aria-controls="tabKitDetails" aria-selected="true">Kit Details</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-toggle="tab" href="#tabIssuanceDetails" role="tab" aria-controls="tabIssuanceDetails" aria-selected="false">Issuance Details</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tabKitDetails" role="tabpanel">
                      <div class="table responsive">
                        <br>
                        <table id="tblKitDetails" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Detail ID</th>
                              <th>Item/Part No.</th>
                              <th>Item Description</th>
                              <th>Usage</th>
                              <th>Rqd Qty.</th>
                              <th>Kit Qty.</th>
                              <th>Issued Qty.</th>
                              <th>Location</th>
                              <th>Drawing No.</th>
                              <th>Supplier</th>
                              <th>Whse100</th>
                              <th>Whse102</th>
                            </tr>
                          </thead>
                        </table>
                      </div>

                    </div>

                    <div class="tab-pane fade" id="tabIssuanceDetails" role="tabpanel">
                      <div class="table responsive">
                        <br>
                        <table id="tblIssuanceDetails" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Detail ID</th>
                              <th>Item/Part No.</th>
                              <th>Item Description</th>
                              <th>Kit Qty.</th>
                              <th>Issued Qty.</th>
                              <th>Lot No.</th>
                              <th>Location</th>
                              <th>Remarks</th>
                              <th>Action</th>
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



          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- MODALS -->
  <div class="modal fade" id="modalAddKitting">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Kitting</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddKitting">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Kitting Name</label>
                    <input type="text" class="form-control" name="name" id="txtAddKittingName">
                </div>

                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtAddKittingBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddKitting" class="btn btn-primary"><i id="iBtnAddKittingIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditKitting">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Kitting</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditKitting">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="kitting_id" id="txtEditKittingId">
                <div class="form-group">
                  <label>Kitting Name</label>
                    <input type="text" class="form-control" name="name" id="txtEditKittingName">
                </div>

                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtEditKittingBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditKitting" class="btn btn-primary"><i id="iBtnEditKittingIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeKittingStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeKittingTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeKittingStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeKittingStatLabel">Are you sure to ?</label>
            <input type="hidden" name="kitting_id" placeholder="Kitting Id" id="txtChangeKittingStatKittingId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeKittingStatKittingStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeKittingStat" class="btn btn-primary"><i id="iBtnChangeKittingStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalImportKitting">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-file-excel"></i> Import Kitting</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formImportKitting" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>File</label>
                    <input type="file" class="form-control" name="import_file" id="fileImportKitting">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnImportKitting" class="btn btn-primary"><i id="iBtnImportKittingIcon" class="fa fa-check"></i> Import</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="mdlSubKitIssuance">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-list"></i> Sub Kitting</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="frmSubKitIssuance" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Issuance No</label>
                  <input type="text" class="form-control" name="kit_issuance_id" placeholder="Auto Generated" readonly="true" style="display: none;">
                  <input type="text" class="form-control" name="issuance_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>PO No</label>
                  <input type="text" class="form-control" name="po_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>


              <div class="col-sm-6">
                <div class="form-group">
                  <label>Item/Part No.</label>
                  <input type="text" class="form-control" name="item" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Item Description</label>
                  <input type="text" class="form-control" name="item_desc" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Lot No</label>
                  <input type="text" class="form-control" name="lot_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Detail ID</label>
                  <input type="text" class="form-control" name="detail_id" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Issued Qty</label>
                  <input type="number" class="form-control" name="issued_qty" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Sub Kit Quantity</label>
                  <input type="number" class="form-control" name="sub_kit_qty" placeholder="Enter Sub Kit Qty" min="1">
                </div>
              </div>

              <div class="col-sm-12 divSubKittingContent" id="divSubKittingContentAddNew">
                <div class="form-group">
                  <hr>
                  <table id="tblSubKitIssuance" style="width: 100%;">
                    <thead>
                      <tr>
                        <th style="width: 75%">Description</th>
                        <th style="width: 25%">Sub Kit Qty</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- <tr>
                        <td><input type="text" class="form-control form-control-sm" name="description[]" placeholder="Auto Generated" readonly="true"></td>
                        <td><input type="number" class="form-control form-control-sm" name="sub_kit_qty[]" placeholder="Auto Generated" readonly="true"></td>
                      </tr> -->
                    </tbody>
                  </table>  
                </div>
              </div>

              <div class="col-sm-12 divSubKittingContent" id="divSubKittingContentView" style="display: none;">
                <div class="form-group">
                  <hr>
                  <table id="tblViewSubKittings" class="table table-striped table-sm" style="width: 100%;">
                    <thead>
                      <tr>
                        <th style="width: 65%">Description</th>
                        <th style="width: 20%">Sub Kit Qty</th>
                        <th style="width: 15%">Action</th>
                        <th style="width: 15%">Raw QRCode</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- <tr>
                        <td><input type="text" class="form-control form-control-sm" name="description[]" placeholder="Auto Generated" readonly="true"></td>
                        <td><input type="number" class="form-control form-control-sm" name="sub_kit_qty[]" placeholder="Auto Generated" readonly="true"></td>
                      </tr> -->
                    </tbody>
                  </table>  
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnSubKitIssuance" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
            <!-- <button type="button" id="btnPrintAllSubKitIssuance" class="btn btn-success" style="display: none;"><i class="fa fa-print"></i> Print All</button> -->
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

   <div class="modal fade" id="mdlSubKitIssuanceEdit">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-list"></i> Edit Sub Kitting</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="frmSubKitIssuanceEdit" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <input type="hidden" class="form-control" name="e_id" id="txtEditId">
                  <input type="hidden" class="form-control" name="e_status" id="txtEditStatus">
                  <label>Issuance No</label>
                  <input type="text" class="form-control" name="ekit_issuance_id" placeholder="Auto Generated" readonly="true" style="display: none;">
                  <input type="text" class="form-control" name="eissuance_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>PO No</label>
                  <input type="text" class="form-control" name="epo_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>


              <div class="col-sm-6">
                <div class="form-group">
                  <label>Item/Part No.</label>
                  <input type="text" class="form-control" name="eitem" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Item Description</label>
                  <input type="text" class="form-control" name="eitem_desc" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Lot No</label>
                  <input type="text" class="form-control" name="elot_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Detail ID</label>
                  <input type="text" class="form-control" name="edetail_id" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Issued Qty</label>
                  <input type="number" class="form-control" name="eissued_qty" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Sub Kit Quantity</label>
                  <input type="number" class="form-control" name="esub_kit_qty" placeholder="Enter Sub Kit Qty" min="1">
                </div>
              </div>

              <div class="col-sm-12 divSubKittingContentEdit" id="divSubKittingContentViewEdit" style="display: none;">
                <div class="form-group">
                  <hr>
                  <table id="tblViewSubKittingsEdit" class="table table-striped table-sm" style="width: 100%;">
                    <thead>
                      <tr>
                        <th style="width: 65%">Description</th>
                        <th style="width: 20%">Sub Kit Qty</th>
                        <th style="width: 15%">Action</th>
                        <th style="width: 15%">Raw QRCode</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>  
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnSubKitIssuanceEdit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="mdlPrintSubKit">
    <div class="modal-dialog">
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
              <div class="col-sm-12">
                <input type="text" id="txtPrintSubKitPONo" style="display: none;">
                <input type="text" id="txtPrintSubKitKitNo" style="display: none;">
                <input type="text" id="txtPrintSubKitKitter" style="display: none;">
                <input type="text" id="txtPrintSubKitLotNo" style="display: none;">
                <input type="text" id="txtPrintSubKitCounter" style="display: none;">
                <input type="text" id="txtPrintSubKitIssuedQty" style="display: none;">
                <input type="text" id="txtPrintSubKitSubKitQty" style="display: none;">
                <input type="text" id="txtPrintSubKitItemCode" style="display: none;">
                <input type="text" id="txtPrintSubKitItemDesc" style="display: none;">
                <input type="text" id="txtSrcPrintSubKitPONo" style="display: none;">
                <input type="text" id="txtSrcGenWHMatIssuIdBarcode" style="display: none;">
                <input type="text" id="txtSrcGenWHMatIssuIdBarcode2" style="display: none;">
              </div>
              <div class="col-sm-12">
                <label id="lblDeviceName"></label> <br>
              </div>
              <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="imgPrintSubKitPONo" style="max-width: 150px; min-width: 150px;">
                  <br>
                  <label id="lblPrintSubKitPONo">...</label> <br>
                  <label id="lblPrintSubKitKitNo">...</label> - <label id="lblPrintSubKitKitter">...</label>
              </div>
              <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="imgGenWHMatIssuIdBarcode" style="max-width: 150px; min-width: 150px;">
                  <br>
                  <label id="lblPrintSubKitLotNo">...</label> <br>
                  <label id="lblPrintSubKitIssuedQty">...</label> <br>
              </div>
              <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="imgGenWHMatIssuIdBarcode2" style="max-width: 150px; min-width: 150px;">
                  <br>
                  <label id="lblPrintSubKitItemCode">...</label> <br>
                  <label id="lblPrintSubKitItemDesc">...</label> <br>
              </div>
            </div>
          </center>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" id="btnPrintWHMatIssuIdBarcode" class="btn btn-primary"><i id="iBtnPrintWHMatIssuIdBarcodeIcon" class="fa fa-print"></i> Print</button>
          <button type="button" id="btnPrintAllSubKitIssuance" class="btn btn-success" style="display: none;"><i class="fa fa-print"></i> Print All</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableKittings, dtKittingDetails, dtIssuanceDetails, dtSubKittings;
    let selectedPatsKittingId = 0;

    $(document).ready(function () {
      setTimeout(function() {
        $('.nav-link').find('.fa-bars').closest('a').click();
      }, 100);


      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

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

      $(document).on('click','#tblKittings tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      // dataTableKittings = $("#tblKittings").DataTable({
      //   "processing" : false,
      //     "serverSide" : true,
      //     "ajax" : {
      //       url: "view_kittings",
      //       // data: function (param){
      //       //     param.status = $("#selEmpStat").val();
      //       // }
      //     },
          
      //     "columns":[
      //       { "data" : "checkbox" },
      //       { "data" : "name" },
      //       { "data" : "barcode" },
      //       { "data" : "label1" },
      //       { "data" : "action1", orderable:false, searchable:false }
      //     ],
      //     "initComplete": function(settings, json) {
      //           // $(".chkKitting").each(function(index){
      //           //     if(arrSelectedKittings.includes($(this).attr('kitting-id'))){
      //           //         $(this).attr('checked', 'checked');
      //           //     }
      //           // });
      //     },
      //     "drawCallback": function( settings ) {
      //           // $(".chkKitting").each(function(index){
      //           //     if(arrSelectedKittings.includes($(this).attr('kitting-id'))){
      //           //         $(this).attr('checked', 'checked');
      //           //     }
      //           // });
      //       }
      //   });//end of dataTableKittings

        // console.log($("input[name='issuance_no']", $("#frmSearchKittingInfo")).val());

        dtKittingDetails = $("#tblKitDetails").DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "get_kitting_details_by_issuance_no",
            data: function (param){
                param.issuance_no = $("input[name='issuance_no']", $("#frmSearchKittingInfo")).val();
            }
          },
          
          "columns":[
            { "data" : "detailid" },
            { "data" : "item" },
            { "data" : "item_desc" },
            { "data" : "usage" },
            { "data" : "rqd_qty" },
            { "data" : "kit_qty" },
            { "data" : "issued_qty" },
            { "data" : "location" },
            { "data" : "drawing_no" },
            { "data" : "supplier" },
            { "data" : "whs100" },
            { "data" : "whs102" },
            // { "data" : "action1", orderable:false, searchable:false }
          ],
          "columnDefs": [
                {
                  "targets": [-1],
                  "data": null,
                  "defaultContent": "--"
                },
           ],
          "initComplete": function(settings, json) {
                // $(".chkKitting").each(function(index){
                //     if(arrSelectedKittings.includes($(this).attr('kitting-id'))){
                //         $(this).attr('checked', 'checked');
                //     }
                // });
          },
          "drawCallback": function( settings ) {
                // $(".chkKitting").each(function(index){
                //     if(arrSelectedKittings.includes($(this).attr('kitting-id'))){
                //         $(this).attr('checked', 'checked');
                //     }
                // });
            }
        });//end of dataTableKittings

        dtIssuanceDetails = $("#tblIssuanceDetails").DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "get_issuance_details_by_issuance_no",
            data: function (param){
                param.issuance_no = $("input[name='issuance_no']", $("#frmSearchKittingInfo")).val();
            }
          },
          
          "columns":[
            { "data" : "detailid" },
            { "data" : "item" },
            { "data" : "item_desc" },
            { "data" : "kit_qty" },
            { "data" : "issued_qty" },
            { "data" : "lot_no" },
            { "data" : "location" },
            { "data" : "remarks" },
            { "data" : "raw_action" },
            // { "data" : "action1", orderable:false, searchable:false }
          ],
          "columnDefs": [
                {
                  "targets": [-1],
                  "data": null,
                  "defaultContent": "--"
                },
           ],
          "initComplete": function(settings, json) {
                // $(".chkKitting").each(function(index){
                //     if(arrSelectedKittings.includes($(this).attr('kitting-id'))){
                //         $(this).attr('checked', 'checked');
                //     }
                // });
          },
          "drawCallback": function( settings ) {
                // $(".chkKitting").each(function(index){
                //     if(arrSelectedKittings.includes($(this).attr('kitting-id'))){
                //         $(this).attr('checked', 'checked');
                //     }
                // });
            }
        });//end of dataTableKittings

        // dtSubKittings = $("#tblViewSubKittings").DataTable({
        // "processing" : false,
        //   "serverSide" : true,
        //   "paging": false,
        //   "sorting": false,
        //   "searching": false,
        //   "ajax" : {
        //     url: "view_sub_kitting_by_kitting_id",
        //     data: function (param){
        //         param.pats_kitting_id = selectedPatsKittingId;
        //     }
        //   },
          
        //   "columns":[
        //     { "data" : "sub_kit_desc" },
        //     { "data" : "sub_kit_qty" },
        //     { "data" : "raw_action" },
        //     { "data" : "raw_qrcode", visible: false, },
        //   ],
        // });

        dtSubKittings = $("#tblViewSubKittingsEdit").DataTable({
        "processing" : false,
          "serverSide" : true,
          "paging": false,
          "sorting": false,
          "searching": false,
          "ajax" : {
            url: "view_sub_kitting_by_kitting_id",
            data: function (param){
                param.pats_kitting_id = selectedPatsKittingId;
            }
          },
          
          "columns":[
            { "data" : "sub_kit_desc" },
            { "data" : "sub_kit_qty" },
            { "data" : "raw_action" },
            { "data" : "raw_qrcode", visible: false, },
          ],
        });//end of dataTableKittings

        GetKittingInfoByIssuanceNo($("input[name='issuance_no']", $("#frmSearchKittingInfo")).val());

        // ADDED 2020-06-26
          $("#frmSearchKittingInfo").submit(function(e){
            e.preventDefault();
            GetKittingInfoByIssuanceNo($("input[name='issuance_no']", $("#frmSearchKittingInfo")).val());
          });

          // $("input[name='issuance_no']", $("#frmSearchKittingInfo")).keyup(function(e){
          //   GetKittingInfoByIssuanceNo($(this).val());
          //   // console.log($(this).val());
          // });

          $("input[name='issuance_no']", $("#frmSearchKittingInfo")).keypress(function(e){
            // console.log($(this).val());

            if(e.keyCode == 13){
              GetKittingInfoByIssuanceNo($(this).val());
              toastr.info('Loading data. Please wait...');
              $("input[name='po_no']", $("#frmSearchKittingInfo")).focus();
            }

          });

          /* Add */
          $("#tblIssuanceDetails").on('click', '.aSubKitIssuance', function(){
            let kitIssuanceId = $(this).attr('kit-issuance-id');
            let issuedQty = $(this).attr('issued-qty');
            let issuanceNo = $(this).attr('issuance-no');
            let detailId = $(this).attr('detail-id');
            let item = $(this).attr('item');
            let itemDesc = $(this).attr('item-desc');
            let lotNo = $(this).attr('lot-no');
            let poNo = $(this).attr('po-no');

            $("input[name='kit_issuance_id']", $("#frmSubKitIssuance")).val(kitIssuanceId);
            $("input[name='po_no']", $("#frmSubKitIssuance")).val(poNo);
            $("input[name='issuance_no']", $("#frmSubKitIssuance")).val(issuanceNo);
            $("input[name='detail_id']", $("#frmSubKitIssuance")).val(detailId);
            $("input[name='item']", $("#frmSubKitIssuance")).val(item);
            $("input[name='item_desc']", $("#frmSubKitIssuance")).val(itemDesc);
            $("input[name='lot_no']", $("#frmSubKitIssuance")).val(lotNo);
            $("input[name='issued_qty']", $("#frmSubKitIssuance")).val(issuedQty);
            $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val('');
            $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).prop('readonly', false);
            $(".divSubKittingContent").hide();
            $("#divSubKittingContentAddNew").show();
            
            $("#mdlSubKitIssuance").modal('show');
            $("#tblSubKitIssuance tbody").html('');
            $("#btnSubKitIssuance").show();
            $("#btnPrintAllSubKitIssuance").hide();
          });

          /* Edit */
          $("#tblIssuanceDetails").on('click', '.aViewSubKitIssuance', function(){
            let kitIssuanceId = $(this).attr('kit-issuance-id');
            let issuedQty = $(this).attr('issued-qty');
            let issuanceNo = $(this).attr('issuance-no');
            let detailId = $(this).attr('detail-id');
            let item = $(this).attr('item');
            let itemDesc = $(this).attr('item-desc');
            let lotNo = $(this).attr('lot-no');
            let poNo = $(this).attr('po-no');
            let patsKittingId = $(this).attr('pats-kitting-id');
            let patsKittingStatus = $(this).attr('pats-kitting-status');
            // alert(patsKittingStatus)

            $("#btnPrintAllSubKitIssuance").show();

            selectedPatsKittingId = patsKittingId;
            dtSubKittings.draw();
            
            $("input[name='e_id']", $("#frmSubKitIssuanceEdit")).val(patsKittingId);
            $("input[name='e_status']", $("#frmSubKitIssuanceEdit")).val(patsKittingStatus);
            $("input[name='ekit_issuance_id']", $("#frmSubKitIssuanceEdit")).val(kitIssuanceId);
            $("input[name='epo_no']", $("#frmSubKitIssuanceEdit")).val(poNo);
            $("input[name='eissuance_no']", $("#frmSubKitIssuanceEdit")).val(issuanceNo);
            $("input[name='edetail_id']", $("#frmSubKitIssuanceEdit")).val(detailId);
            $("input[name='eitem']", $("#frmSubKitIssuanceEdit")).val(item);
            $("input[name='eitem_desc']", $("#frmSubKitIssuanceEdit")).val(itemDesc);
            $("input[name='elot_no']", $("#frmSubKitIssuanceEdit")).val(lotNo);
            $("input[name='eissued_qty']", $("#frmSubKitIssuanceEdit")).val(issuedQty);
            $("input[name='eissued_qty']", $("#frmSubKitIssuanceEdit")).val(issuedQty);
            
            $("input[name='esub_kit_qty']", $("#frmSubKitIssuanceEdit")).val('');
            $("input[name='esub_kit_qty']", $("#frmSubKitIssuanceEdit")).prop('readonly', false);

            $(".divSubKittingContentEdit").hide();
            $("#divSubKittingContentViewEdit").show();

            
            $("#mdlSubKitIssuanceEdit").modal('show');

            ViewPatsKittingByIdEdit(patsKittingId);
          });

          $("#tblViewSubKittings").on('click', '.aPrintSubKit', function(){
            let subKitDesc = $(this).attr('sub-kit-desc');
            let subKitId = $(this).attr('sub-kit-id');
            let device_name = $("#device_name").val();
            $("#lblDeviceName").html(device_name);
            GenerateQRCodeKittingById(subKitId)
          });


          $("#tblViewSubKittingsEdit").on('click', '.aPrintSubKit', function(){
            let subKitDesc = $(this).attr('sub-kit-desc');
            let subKitId = $(this).attr('sub-kit-id');
            let device_name = $("#device_name").val();
            $("#lblDeviceName").html(device_name);
            GenerateQRCodeKittingById(subKitId)
          });

          $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).keyup(function(){
            let subKitQty = $(this).val();
            // alert(subKitQty)
            let issuedQty = $("input[name='issued_qty']", $("#frmSubKitIssuance")).val();
            let result = "";

            // let prefixDesc = $("input[name='kit_no']", $("#frmSubKitIssuance")).val(), who kit, po no, kit qty, lot no, item code, item desc, date - 1/5;

            let prefixDesc = $("input[name='kit_no']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='prep_by']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='po_no']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='lot_no']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='item']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='item_desc']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='issuance_no']", $("#frmSubKitIssuance")).val() + ' | ' +  new Date().getFullYear() + '/' + (parseInt(new Date().getMonth()) + 1) + '/' + new Date().getDate() + ' | ';

            if(subKitQty == "" || parseFloat(subKitQty) <= 0){
              // console.log('wew');
              $("#tblSubKitIssuance tbody").html(result);
            }
            else{
              if(parseFloat(subKitQty) > parseFloat(issuedQty)){
                toastr.warning('Invalid Sub Kit Qty');
                $("#tblSubKitIssuance tbody").html(result);
              }
              else{
                let noOfSubKit = Math.ceil(issuedQty / subKitQty);

                if(noOfSubKit > 0){
                  let rowSubKitQtyCounter = 0;
                  for(let index = 1; index <= noOfSubKit; index++){
                    let rowSubKitDesc = index + '/' + noOfSubKit;
                    let rowSubKitQty = (index * parseFloat(subKitQty));

                    rowSubKitQtyCounter += parseFloat(subKitQty);

                    let final = subKitQty;

                    if(parseFloat(rowSubKitQtyCounter) > parseFloat(issuedQty)){
                      final = parseFloat(issuedQty) - (parseFloat(rowSubKitQtyCounter) - parseFloat(subKitQty));
                    }

                    rowSubKitDesc = prefixDesc + rowSubKitDesc;

                    result += '<tr>';
                    // result += '<td><input type="text" class="form-control form-control-sm" name="descriptions[]" placeholder="Auto Generated" readonly="true" value="' + rowSubKitDesc + '"></td>';
                    // result += '<td><input type="number" class="form-control form-control-sm" name="sub_kit_qtys[]" placeholder="Auto Generated" readonly="true" value="' + final + '"></td>';

                    result += '<td><textarea class="form-control form-control-sm" name="descriptions[]" placeholder="Auto Generated" readonly="true" rows="4">' + rowSubKitDesc + '</textarea></td>';
                    result += '<td><textarea class="form-control form-control-sm" name="sub_kit_qtys[]" placeholder="Auto Generated" readonly="true" rows="4">' + final + '</textarea></td>';

                    result += '</tr>';
                  }
                }

              }
            }

            $("#tblSubKitIssuance tbody").html(result);
          });

          $("input[name='esub_kit_qty']", $("#frmSubKitIssuanceEdit")).keyup(function(){
            let subKitQty = $(this).val();
            let issuedQty = $("input[name='eissued_qty']", $("#frmSubKitIssuanceEdit")).val();
            let result = "";

            let prefixDesc = $("input[name='kit_no']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='prep_by']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='po_no']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='elot_no']", $("#frmSubKitIssuanceEdit")).val() + ' | ' + $("input[name='eitem']", $("#frmSubKitIssuanceEdit")).val() + ' | ' + $("input[name='eitem_desc']", $("#frmSubKitIssuanceEdit")).val() + ' | ' + $("input[name='eissuance_no']", $("#frmSubKitIssuanceEdit")).val() + ' | ' +  new Date().getFullYear() + '/' + (parseInt(new Date().getMonth()) + 1) + '/' + new Date().getDate() + ' | ';

            if(subKitQty == "" || parseFloat(subKitQty) <= 0){
              $("#tblViewSubKittingsEdit tbody").html(result);
            }
            else{
              if(parseFloat(subKitQty) > parseFloat(issuedQty)){
                toastr.warning('Invalid Sub Kit Qty');
                $("#tblViewSubKittingsEdit tbody").html(result);
              }
              else{
                let noOfSubKit = Math.ceil(issuedQty / subKitQty);

                if(noOfSubKit > 0){
                  let rowSubKitQtyCounter = 0;
                  for(let index = 1; index <= noOfSubKit; index++){
                    let rowSubKitDesc = index + '/' + noOfSubKit;
                    let rowSubKitQty = (index * parseFloat(subKitQty));

                    rowSubKitQtyCounter += parseFloat(subKitQty);

                    let final = subKitQty;

                    if(parseFloat(rowSubKitQtyCounter) > parseFloat(issuedQty)){
                      final = parseFloat(issuedQty) - (parseFloat(rowSubKitQtyCounter) - parseFloat(subKitQty));
                    }

                    rowSubKitDesc = prefixDesc + rowSubKitDesc;

                    result += '<tr>';
                    result += '<td><textarea class="form-control form-control-sm" name="edescriptions[]" placeholder="Auto Generated" readonly="true" rows="4">' + rowSubKitDesc + '</textarea></td>';
                    result += '<td><textarea class="form-control form-control-sm" name="esub_kit_qtys[]" placeholder="Auto Generated" readonly="true" rows="4">' + final + '</textarea></td>';

                    result += '</tr>';
                  }
                }

              }
            }

            $("#tblViewSubKittingsEdit tbody").html(result);
          });

          $("#frmSubKitIssuance").submit(function(e){
            e.preventDefault();
            SaveSubKitting();
          });

           $("#frmSubKitIssuanceEdit").submit(function(e){
            e.preventDefault();

            EditSubKitting();
          });

          $("#btnPrintWHMatIssuIdBarcode").click(function(){
            let data = dtSubKittings.data();
            let device_name = $("#device_name").val();
            PrintSubKitQrCodes(1, data, device_name);
          });

          $("#btnPrintAllSubKitIssuance").click(function(){
            let copies = dtSubKittings.data().count();
            let data = dtSubKittings.data();
            let device_name = $("#device_name").val();
            // alert(copies);
            PrintSubKitAllQrCodes(copies, data, device_name);
          });
        //----------

        function PrintSubKitQrCodes(copies, data, device_name){
          popup = window.open();
            // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
            let content = '';
            content += '<html>';
            content += '<head>';
              content += '<title></title>';
              content += '<style type="text/css">';
                // content += '.rotated {';
                //   // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
                //   // content += 'border: 2px solid black;';
                //   content += 'width: 160px;';
                //   content += 'position: absolute;';
                //   content += 'left: 8.5px;';
                //   content += 'top: 12px;';
                // content += '}';

                content += 'img {';
                  content += 'display: block;';
                //   content += 'width: 80%;';
                content += '}';

                content += 'table, tr, td {';
                  // content += 'border: 1px solid;';
                  content += 'padding-bottom: 0px;';
                  content += 'padding-top: 0px;';
                content += '}';
  
              content += '</style>';
            content += '</head>';
            content += '<body>';
                content += '<center>';
                // content += '<div class="rotated">';
              for(let index = 0; index < copies; index++){
                content += '<table style="width: 100%;margin-left: -5px;">';
                
                //---------------------
                content += '<tr>';
                content += '<td colspan="3" style="padding-top: 12px;" cellspacing="0">';
                content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' +device_name+ '</label>';
                content += '</center>';
                content += '</td>';
                content += '</tr>';
                //---------------------


                //---------------------
                content += '<tr>';
                content += '<td>';
                content += '<center>';
                content += '<img src="' + $("#txtSrcPrintSubKitPONo").val() + '" style="min-width: 60px; max-width: 60px;">';
                content += '</center>';
                content += '</td>';

                let lotNoSrc = $("#txtSrcGenWHMatIssuIdBarcode").val();
                if(data.length > 0){
                    lotNoSrc = data[index]['raw_qrcode'];
                }

                content += '<td>';
                content += '<center>';
                content += '<img src="' + lotNoSrc + '" style="min-width: 60px; max-width: 60px;" >';
                content += '</center>';
                content += '</td>';

                content += '<td>';
                content += '<center>';
                content += '<img src="' + $("#txtSrcGenWHMatIssuIdBarcode2").val() + '" style="min-width: 60px; max-width: 60px;">';
                content += '</center>';
                content += '</td>';
                content += '</tr>';
                //---------------------
                

                //---------------------
                content += '<tr>';
                content += '<td>';
                content += '<center>';
                content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $("#txtPrintSubKitPONo").val() + '<br> '+ $("#txtPrintSubKitKitNo").val() + ' - ' + $("#txtPrintSubKitKitter").val() + '</label>';
                content += '<br>';
                content += '</center>';
                content += '</td>';

                let kitCounter = $("#txtPrintSubKitCounter").val();
                if(data.length > 0){
                    kitCounter = data[index]['sub_kit_desc'].split(' | ')[9];
                }

                content += '<td>';
                content += '<center>';
                content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + kitCounter + '<br>' + $("#txtPrintSubKitLotNo").val() + '<br>QTY ';
                  if(data.length > 0){
                      content += data[index]['sub_kit_qty'];
                  }
                  else{
                    content += txtPrintSubKitSubKitQty;
                  }
                content += ' pc(s) ' + '</label>';
                content += '<br>';
                content += '</center>';
                content += '</td>';

                content += '<td>';
                content += '<center>';
                content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $("#txtPrintSubKitItemCode").val() + '<br> '+ $("#txtPrintSubKitItemDesc").val() + '</label>';
                content += '<br>';
                content += '</center>';
                content += '</td>';
                content += '</tr>';
                //---------------------


                //---------------------
                let date = new Date();

                content += '<tr>';
                content += '<td colspan="3" style="padding: 1px 1px;" cellspacing="0";>';
                content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px; margin 1px 1px;">';
                  content += date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
                content += '</label>';
                content += '</center></td>';

                content += '</tr>';
                //---------------------


                content += '</table>';
              }
                content += '</center>';
            content += '</body>';
            content += '</html>';
            popup.document.write(content);

            setTimeout(function(){ 
              popup.focus(); //required for IE
              popup.print();
              popup.close();
            }, 1000);
        }

        function PrintSubKitAllQrCodes(copies, data, device_name){
          popup = window.open();
            // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
            let content = '';
            content += '<html>';
            content += '<head>';
              content += '<title></title>';
              content += '<style type="text/css">';

              content += '@media print { .pagebreak { page-break-before: always; } }';

                content += 'img {';
                  content += 'display: block;';
                  content += 'margin-left: auto;';
                  content += 'margin-right: auto;';
                  // content += 'width: 80%;';
                content += '}';

                content += 'table, tr, td {';
                  // content += 'border: 1px solid;';
                  content += 'padding-bottom: 0px;';
                  content += 'padding-top: 0px;';
                content += '}';
  
              content += '</style>';
            content += '</head>';

            content += '<body>';
                content += '<center>';
              for(let index = 0; index < copies; index++){
                content += '<table style="width: 100%;">';
                
                //---------------------
                content += '<tr>';
                content += '<td colspan="3" style="padding-top: 15px;">';
                content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' +device_name+ '</label>';
                content += '</center>';
                content += '</td>';
                content += '</tr>';
                //---------------------


                //---------------------
                content += '<tr>';
                content += '<td>';
                content += '<center>';
                content += '<img src="' + $("#txtSrcPrintSubKitPONo").val() + '" style="min-width: 60px; max-width: 60px;">';
                content += '</center>';
                content += '</td>';

                let lotNoSrc = $("#txtSrcGenWHMatIssuIdBarcode").val();
                if(data.length > 0){
                    lotNoSrc = data[index]['raw_qrcode'];
                }

                content += '<td>';
                content += '<center>';
                content += '<img src="' + lotNoSrc + '" style="min-width: 60px; max-width: 60px;">';
                content += '</center>';
                content += '</td>';

                content += '<td>';
                content += '<center>';
                content += '<img src="' + $("#txtSrcGenWHMatIssuIdBarcode2").val() + '" style="min-width: 60px; max-width: 60px;">';
                content += '</center>';
                content += '</td>';
                content += '</tr>';
                //---------------------
                

                //---------------------
                content += '<tr>';
                content += '<td>';
                content += '<center>';
                content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $("#txtPrintSubKitPONo").val() + '<br> '+ $("#txtPrintSubKitKitNo").val() + ' - ' + $("#txtPrintSubKitKitter").val() + '</label>';
                content += '<br>';
                content += '</center>';
                content += '</td>';

                let kitCounter = $("#txtPrintSubKitCounter").val();
                if(data.length > 0){
                    kitCounter = data[index]['sub_kit_desc'].split(' | ')[9];
                }

                content += '<td>';
                content += '<center>';
                content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + kitCounter + '<br>' + $("#txtPrintSubKitLotNo").val() + '<br>QTY ';
                  if(data.length > 0){
                      content += data[index]['sub_kit_qty'];
                  }
                  else{
                    content += txtPrintSubKitSubKitQty;
                  }
                content += ' pc(s) ' + '</label>';
                content += '<br>';
                content += '</center>';
                content += '</td>';

                content += '<td>';
                content += '<center>';
                content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $("#txtPrintSubKitItemCode").val() + '<br> '+ $("#txtPrintSubKitItemDesc").val() + '</label>';
                content += '<br>';
                content += '</center>';
                content += '</td>';
                content += '</tr>';
                //---------------------


                //---------------------
                let date = new Date();

                content += '<tr>';
                content += '<td colspan="3" >';
                content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px; margin 1px 1px;">';
                  content += date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
                content += '</label>';
                content += '</center></td>';

                content += '</tr>';
                //---------------------


                content += '</table>';
                content += '<br>';
          

              if( index < copies-1 )
                content += '<div class="pagebreak"></div>';   

              }
                content += '</center>';

            content += '</body>';
            content += '</html>';
            popup.document.write(content);

            setTimeout(function(){ 
              popup.focus(); //required for IE
              popup.print();
              popup.close();
            }, 1000);
        }

      });
  </script>
  @endsection
@endauth