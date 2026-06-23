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

  @section('title', 'Pilot Run2')

  @section('content_page')
  <style type="text/css">
    .modal-xl-custom{
      width: 95%!important;
      min-width: 95%!important;
    }
    .modal-footer {
      justify-content: space-between;
    }  
  </style>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>B. Checking for Product Compliance (In-Charge: QC)</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Pilot Run</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Search PO Number</h3>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="col-sm-3">
                    <label>PO Number</label>
                    <div class="input-group">           
                      <input type="text" class="form-control" id="txt_po_number_lbl">
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <label>Series Name</label>
                    <input type="text" class="form-control" id="txt_device_name_lbl" readonly>
                  </div>
                  <div class="col-sm-2">
                    <label>Series Code</label>
                   <input type="text" class="form-control" id="txt_device_code_lbl" readonly>
                  </div>
                  <div class="col-sm-1">
                    <label>PO Qty</label>
                    <input type="text" class="form-control" id="txt_po_qty_lbl" readonly>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">B. Checking for Product Compliance Summary</h3>
                <button class="btn btn-primary btn-sm" style="float: right;" id="btnShowAddPilotRun"><i class="fa fa-plus"></i> Add Pilot Run</button>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tbl_prod_runcard" style="min-width: 900px;">
                      <thead>
                        <tr class="bg-light">
                        <th>Action</th>
                        <th>Status</th>
                        <th>Lot #</th>
                        <th>Remarks</th>
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
    </section>

    <div class="modal fade" id="modalPilotRunDetails" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-xl modal-xl-custom modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> B - Pilot Run Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>

          <form id="frm_pilotrun1_details" method="post">
          @csrf
            <div class="modal-body">
              
              <center>
                <h3>PILOT RUN EVALUATION REPORT</h3>
              </center>
              <!-- <button type="button" id="btn_edit_pilotrun_details_primary" class="btn btn-sm btn-link float-right"><i class="fa fa-edit"></i> Edit</button> -->

              <input type="hidden" name="employee_id" id="frm_edit_pilotrun_details_employee_id">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Series Name</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_series_name" name="txt_series_name" style="color: blue; font-weight: bold;" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Num</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_num" name="txt_po_num" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_qty" name="txt_po_qty" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Pair No/s.</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_pair_no" name="txt_pair_no" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Start Date/Time</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_start_datetime" name="txt_start_datetime" readonly="true" placeholder="Auto generated">
                    </div>                    
                  </div>
                </div>

                <div class="accordion" id="accordionMain">
                  <div class="card">
                    <div class="card-header" id="headingOne">
                      <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          A. MATERIAL / PRODUCT / PROCESS REQUIREMENT CHECKING (IN-CHARGE: PRODUCTION)
                        </button>
                      </h5>
                    </div>

                  <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionMain">
                    <div class="card-body">

                    <div class="col-sm-8">
                      TYPE OF CONTROL / QUALIFICATION / ACTIVITY TO PERFORM
                      
                      <div class="row">
                        <div class="col-sm-6">
                          <input type="radio" id="rdo_special_control" name="control_type" value="1"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;<b>Special Control (Conduct Pilot Run) - check items A to E)</b>
                          <div class="row">
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_control_1" name="special_control_type" value="1"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;New Series (with MD)
                            </div>
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_control_2" name="special_control_type" value="2"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;Long period of no production
                            </div>  
                          </div> 
                          <div class="row">
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_control_3" name="special_control_type" value="3"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;New Device (With MD)
                            </div> 
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_control_4" name="special_control_type" value="4"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;Manual Pulling
                            </div> 
                          </div> 
                          <div class="row">
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_control_5" name="special_control_type" value="5"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;New Product
                            </div> 
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_control_6" name="special_control_type" value="6"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;Others
                            </div> 
                          </div> 
                        </div>  

                        <div class="col-sm-6">
                          <b>Others</b>
                          <div class="row">
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_other_1" name="special_other_type" value="1"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;Toshiba/Kioxia Customer
                            </div> 
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_other_2" name="special_other_type" value="2"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;With Customer Claim
                            </div> 
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_other_3" name="special_other_type" value="3"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;Micron Customer
                            </div> 
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_other_4" name="special_other_type" value="4"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;With Lot-out
                            </div> 
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_other_5" name="special_other_type" value="5"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;YEU Customer
                            </div> 
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_other_6" name="special_other_type" value="6"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;Contribution of Yield off-target 
                            </div> 
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_other_7" name="special_other_type" value="7"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;AYE Customer
                            </div> 
                            <div class="col-sm-6">
                              <input type="radio" id="rdo_special_other_8" name="special_other_type" value="8"> 
                                &nbsp;&nbsp;&nbsp;&nbsp;With UD or Special Instructions
                            </div> 
                          </div>
                        </div>  
                      </div>  

                      <div class="row">
                        <div class="col-sm-8">
                          <input type="radio" id="rdo_regular_control" name="control_type" value="1"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;<b>Regular Control (Conduct Product qualification) - check items A, B, & E) <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(All Series other than specified above)</b>
                        </div>  
                      </div>      
                    </div>

                    <div class="col-sm-9">
                      <span class="badge badge-warning">
                          SAMPLE SIZE / DEFINITION: 
                      </span>
                      <br>
                          SPECIAL CONTROL: 5 PCS. / Shall wait for the qualification result prior mass production <br>
                          REGULAR CONTROL: 1 PC. / Can proceed to mass production even without waiting the qualification result
                    </div>
                    <br>

                    <div class="col-sm-12">
                      <span class="badge badge-primary">A.</span> <b> MATERIAL / PRODUCT / PROCESS REQUIREMENT CHECKING (IN-CHARGE: PRODUCTION) </b>
                      <br>
                      PRODUCT DRAWING VS. MATERIAL LIST / OTHER REQUIRED DOCUMENTS AND REFERENCES 
                      <br>
                    </div>

                    <div class="col-sm-12">
                      <span class="badge badge-warning">1.</span> PRODUCT DRAWING REVISION CHECKING 
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Adrawing">
                                      <i class="fa fa-file" title="View"></i>
                                    </button>
                                <span class="input-group-text w-100" id="basic-addon1">1.1 A / KL Drawing #</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_AKLdrawing" name="txt_AKLdrawing" readonly>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_AKLdrawing_rev" name="txt_AKLdrawing_rev" placeholder="REV" readonly>
                              <input type="text" class="form-control" id="txt_AKLdrawing_page" name="txt_AKLdrawing_page" placeholder="NO. OF PAGE">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_AKLdrawing_pn" name="txt_AKLdrawing_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_AKLdrawing_remarks" name="txt_AKLdrawing_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Adrawing">
                                      <i class="fa fa-file" title="View"></i>
                                    </button>
                                <span class="input-group-text w-100" id="basic-addon1">1.2 G / AG Drawing #</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_GAGdrawing" name="txt_GAGdrawing" readonly>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_GAGdrawing_rev" name="txt_GAGdrawing_rev" placeholder="REV" readonly>
                              <input type="text" class="form-control" id="txt_GAGdrawing_page" name="txt_GAGdrawing_page" placeholder="NO. OF PAGE">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_GAGdrawing_pn" name="txt_GAGdrawing_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_GAGdrawing_remarks" name="txt_GAGdrawing_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Adrawing">
                                      <i class="fa fa-file" title="View"></i>
                                    </button>
                                <span class="input-group-text w-100" id="basic-addon1">1.3 Packing Doc # (ex. PM,DJ,etc)</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_PackDocdrawing" name="txt_PackDocdrawing" readonly>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_PackDocdrawing_rev" name="txt_PackDocdrawing_rev" placeholder="REV" readonly>
                              <input type="text" class="form-control" id="txt_PackDocdrawing_page" name="txt_PackDocdrawing_page" placeholder="NO. OF PAGE">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_PackDocdrawing_pn" name="txt_PackDocdrawing_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_PackDocdrawing_remarks" name="txt_PackDocdrawing_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Adrawing">
                                      <i class="fa fa-file" title="View"></i>
                                    </button>
                                <span class="input-group-text w-100" id="basic-addon1">1.4 Packing Specs (MD Doc #)</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_PackSpecsdrawing" name="txt_PackSpecsdrawing" readonly>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_PackSpecsdrawing_rev" name="txt_PackSpecsdrawing_rev" placeholder="REV" readonly>
                              <input type="text" class="form-control" id="txt_PackSpecsdrawing_page" name="txt_PackSpecsdrawing_page" placeholder="NO. OF PAGE">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_PackSpecsdrawing_pn" name="txt_PackSpecsdrawing_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_PackSpecsdrawing_remarks" name="txt_PackSpecsdrawing_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Adrawing">
                                      <i class="fa fa-file" title="View"></i>
                                    </button>
                                <span class="input-group-text w-100" id="basic-addon1">1.5 OT/Urgent Direction #</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_OTUDdrawing" name="txt_OTUDdrawing" readonly>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_OTUDdrawing_rev" name="txt_OTUDdrawing_rev" placeholder="REV" readonly>
                              <input type="text" class="form-control" id="txt_OTUDdrawing_page" name="txt_OTUDdrawing_page" placeholder="NO. OF PAGE">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_OTUDdrawing_pn" name="txt_OTUDdrawing_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_OTUDdrawing_remarks" name="txt_OTUDdrawing_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Adrawing">
                                      <i class="fa fa-file" title="View"></i>
                                    </button>
                                <span class="input-group-text w-100" id="basic-addon1">1.6 Work Instruction # (For Provisional Insertion Only)</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_WIDdrawing" name="txt_WIDdrawing" readonly>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_WIDdrawing_rev" name="txt_WIDdrawing_rev" placeholder="REV" readonly>
                              <input type="text" class="form-control" id="txt_WIDdrawing_page" name="txt_WIDdrawing_page" placeholder="NO. OF PAGE">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_WIDdrawing_pn" name="txt_WIDdrawing_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_WIDdrawing_remarks" name="txt_WIDdrawing_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                    </div>
                    <br>

                    <div class="col-sm-12">
                      <span class="badge badge-warning">2.</span> PARTS CHECKING
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">2.1 Provisional Insertion Parts Requirement</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_provi_req_yes" name="rdo_provi_req" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;YES
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_provi_req_no" name="rdo_provi_req" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;NO
                          </div>  
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_provi_req_pn" name="txt_provi_req_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_provi_req_remarks" name="txt_provi_req_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">2.2 Assembly Parts Requirement</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_assmbly_req_yes" name="rdo_assmbly_req" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;YES
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_assmbly_req_no" name="rdo_assmbly_req" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;NO
                          </div>  
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_assmbly_req_pn" name="txt_assmbly_req_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_assmbly_req_remarks" name="txt_assmbly_req_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">2.3 With Accessory Parts Requirement</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_with_accessory_req_yes" name="rdo_with_accessory_req" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;YES
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_with_accessory_req_no" name="rdo_with_accessory_req" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;NO
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_with_accessory_req_no" name="txt_with_accessory_req_no" placeholder="NO. OF ACCESSORY">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_with_accessory_req_pn" name="txt_with_accessory_req_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_with_accessory_req_remarks" name="txt_with_accessory_req_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                    </div>
                    <br>

                    <div class="col-sm-12">
                      <span class="badge badge-warning">3.</span> PRODUCT / ASSEMBLY / PROCESS REQUIREMENT CHECKING
                      <div class="row">
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">3.1 Assembly Type</span>
                              </div>
                               <select class="form-control form-control-sm" id="txt_assembly_type" name="txt_assembly_type">
                                <option selected disabled>-- Choose One --</option>
                                <option value="0">Single</option>
                                <option value="1">Set</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_assembly_type_pn" name="txt_assembly_type_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-7">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_assembly_type_remarks" name="txt_assembly_type_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">3.2 Parts for Printing</span>
                              </div>
                              <input type="text" class="form-control" id="txt_parts_printing" name="txt_parts_printing">
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">No. of Printing Required</span>
                              </div>
                              <input type="text" class="form-control" id="txt_printing_req" name="txt_printing_req">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_assembly_type_pn" name="txt_assembly_type_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_assembly_type_remarks" name="txt_assembly_type_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                       <div class="row">
                          <div class="col-sm-3">
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">Character to be printed</span>
                              </div>
                              <input type="text" class="form-control" id="txt_char_printed" name="txt_char_printed">
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">Page of reference docs. where printing was indicated</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_page_ref_AKLdrawing" name="txt_page_ref_AKLdrawing" placeholder="A / KL Drawing">
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_page_ref_GAGdrawing" name="txt_page_ref_GAGdrawing" placeholder="G / AG Dawing">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">3.3 Gluing Requirement</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_gluing_req_with" name="rdo_gluing_req" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITH
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_gluing_req_without" name="rdo_gluing_req" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
                          </div>  
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_gluing_req_pn" name="txt_gluing_req_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_gluing_req_remarks" name="txt_gluing_req_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">3.4 Loctite Requirement</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_loctite_req_with" name="rdo_loctite_req" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITH
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_loctite_req_without" name="rdo_loctite_req" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
                          </div>  
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_loctite_req_pn" name="txt_loctite_req_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_loctite_req_remarks" name="txt_loctite_req_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">3.5 HVT Requirement</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_hvt_req_with" name="rdo_hvt_req" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITH
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_hvt_req_without" name="rdo_hvt_req" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
                          </div>  
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_hvt_req_pn" name="txt_hvt_req_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_hvt_req_remarks" name="txt_hvt_req_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">3.6 E-TEST Requirement</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_etest_req_with" name="rdo_etest_req" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITH
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_etest_req_without" name="rdo_etest_req" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
                          </div>  
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_etest_req_pn" name="txt_etest_req_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_etest_req_remarks" name="txt_etest_req_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">3.7 Gauge Requirement</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_gauge_req_with" name="rdo_gauge_req" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITH
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_gauge_req_without" name="rdo_gauge_req" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
                          </div>  
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_gauge_req_pn" name="txt_gauge_req_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_gauge_req_remarks" name="txt_gauge_req_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Gauge Control No.</span>
                            </div>
                            <input type="text" class="form-control" id="txt_gauge_control_no" name="txt_gauge_control_no">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100" id="basic-addon1">3.8 Need Planarity checking using gauge?</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_need_planarity_yes" name="rdo_need_planarity" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITH
                          </div>  
                          <div class="col-sm-1">
                            <input type="radio" id="rdo_need_planarity_no" name="rdo_need_planarity" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
                          </div>  
                          <div class="col-sm-2">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_need_planarity_pn" name="txt_need_planarity_pn" placeholder="CHECKED BY" readonly>
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_need_planarity_remarks" name="txt_need_planarity_remarks" placeholder="REMARKS">
                            </div>
                          </div>
                      </div>
                    </div>
                      
                    </div>
                  </div>
                  </div>
                </div>

                <!----------------------------------------------------B--------------------------------------------------------->
                <div class="col-sm-12">
                  <span class="badge badge-primary">B.</span> <b> CHECKING FOR PRODUCT COMPLIANCE (IN-CHARGE: QC)</b>
                  <br>
                  UNIT, N = 1 VS. PRODUCT DRAWING AND OTHER REFERENCES
                  <br>
                </div>
              
                <div class="row">
                  <div class="col-sm-6">
                    <b>CHECKPOINTS</b>
                    <div class="row">
                      <div class="col-sm-3">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-100">
                              <span class="input-group-text w-100" id="basic-addon1">1. TERMINAL LENGTH</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <input type="radio" id="rdo_term_length_ok" name="rdo_term_length" value="1"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;OK
                        </div>  
                        <div class="col-sm-1">
                          <input type="radio" id="rdo_term_length_ng" name="rdo_term_length" value="2"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;NG
                        </div>  
                        <div class="col-sm-1">
                          <input type="radio" id="rdo_term_length_na" name="rdo_term_length" value="3"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;NA
                        </div>  
                        <div class="col-sm-3">
                          <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" id="txt_term_length_pn" name="txt_term_length_pn" placeholder="NAME" readonly>
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_term_length_date" name="txt_term_length_date" placeholder="DATE/TIME">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-100">
                              <span class="input-group-text w-100" id="basic-addon1">2. MEASUREMENT RESULTS</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <input type="radio" id="rdo_measure_result_ok" name="rdo_measure_result" value="1"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;OK
                        </div>  
                        <div class="col-sm-1">
                          <input type="radio" id="rdo_measure_result_ng" name="rdo_measure_result" value="2"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;NG
                        </div>  
                        <div class="col-sm-1">
                          <input type="radio" id="rdo_measure_result_na" name="rdo_measure_result" value="3"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;NA
                        </div>  
                        <div class="col-sm-3">
                          <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" id="txt_measure_result_pn" name="txt_measure_result_pn" placeholder="NAME" readonly>
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_measure_result_date" name="txt_measure_result_date" placeholder="DATE/TIME">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="input-group input-group-sm mb-2">
                          <div class="input-group-prepend w-100">
                            <span class="input-group-text w-100" id="basic-addon1">3. DIMENSION</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <input type="radio" id="rdo_dimension_type_1" name="rdo_dimension_type" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;Sampling
                      </div>
                      <div class="col-sm-3">
                        <input type="radio" id="rdo_dimension_type_2" name="rdo_dimension_type" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;100% Measurement
                      </div>  
                      <div class="col-sm-3">
                        <input type="radio" id="rdo_dimension_type_3" name="rdo_dimension_type" value="3"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;With Special Requirement
                      </div>  
                      <div class="col-sm-1">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" id="txt_special_req" name="txt_special_req">
                        </div>
                      </div>  
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-100">
                              <span class="input-group-text w-100" id="basic-addon1">4. VISUAL INSPECTION RESULT</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <input type="radio" id="rdo_vir_ok" name="rdo_vir" value="1"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;OK
                        </div>  
                        <div class="col-sm-1">
                          <input type="radio" id="rdo_vir_ng" name="rdo_vir" value="2"> 
                            &nbsp;&nbsp;&nbsp;&nbsp;NG
                        </div>  
                        <div class="col-sm-7">
                          <div class="input-group input-group-sm mb-2">
                              <input type="text" class="form-control" id="txt_vir_remarks" name="txt_vir_remarks">
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <b>HIGHLIGHTS</b>
                    <textarea class="form-control form-control-sm" id="txt_highlights" name="txt_highlights" rows="7"></textarea>
                  </div>
                  <div class="col-sm-4">
                    <b>FEEDBACK REPORT ISSUANCE / CONTROL NO.</b>
                    <div class="row">
                      <div class="col-sm-3">
                        <input type="radio" id="rdo_ptis" name="rdo_feedback_report" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;PTIS
                      </div>  
                      <div class="col-sm-7">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" id="txt_ptis" name="txt_ptis">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <input type="radio" id="rdo_ptis" name="rdo_feedback_report" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;NG REPORT
                      </div>  
                      <div class="col-sm-7">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" id="txt_ng_report" name="txt_ng_report">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <input type="radio" id="rdo_ptis" name="rdo_feedback_report" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;SAR
                      </div>  
                      <div class="col-sm-7">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" id="txt_sar" name="txt_sar">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <input type="radio" id="rdo_ptis" name="rdo_feedback_report" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;OTHERS
                      </div>  
                      <div class="col-sm-7">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" id="txt_others" name="txt_others">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <div>
                <div class="col-sm-12">
                  <b> TERMS:</b> &nbsp;&nbsp;&nbsp;&nbsp;
                      <b>UD</b> - Urgent Directions &nbsp;&nbsp;&nbsp;&nbsp;
                      <b>PP</b> - Point Panel &nbsp;&nbsp;&nbsp;&nbsp;
                      <b>WI</b> - Work Instruction &nbsp;&nbsp;&nbsp;&nbsp;
                      <b>ORCTO</b> - Operations Record of Conformance for Training / Orientation
                </div>
                </div>
              <div class="actions">
                <button type="submit" id="id_btn_save" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
                <button type="button" id="id_btn_close" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
              </div>
            </div>

         </form>

        </div>
      </div>
    </div>



  </div>
  @endsection

  @section('js_content')
  <script type="text/javascript">
  
    $("#btnShowAddPilotRun").click(function(){
      // if($("#txt_po_number_lbl").val() == "" || $("#txt_po_number_lbl").val() == null){
        // toastr.warning('PO not found!');
      // }
      // else{

      //   $.ajax({
      //     url: "get_first_lot_data_by_po_no",
      //     method: "get",
      //     data: {
      //         po_no: $("#txt_po_number_lbl").val(),
      //         device_name: $("#txt_device_name_lbl").val(),
      //         po_qty: $("#txt_po_qty_lbl").val(),
      //         validate: true,
      //     },
      //     dataType: "json",
      //     success: function(data){

                $("#modalPilotRunDetails").modal('show');

      //     }

      //   })

      // }
    });

  </script>
  @endsection
@endauth