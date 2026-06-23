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

  @section('title', 'Pilot Run4')

  @section('content_page')
  <style type="text/css">
    .modal-xl-custom{
      width: 90%!important;
      min-width: 90%!important;
    }
  </style>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>IV - Pilot Run</h1>
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
                    <label>Device Name</label>
                    <input type="text" class="form-control" id="txt_device_name_lbl" readonly>
                  </div>
                  <div class="col-sm-2">
                    <label>Device Code</label>
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
                <h3 class="card-title">IV - Pilot Run Summary (In-Charge: PRODUCTION / ENGINEERING / QC)</h3>
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

    <div class="modal fade" id="modalPilotRunDetails4" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-xl modal-xl-custom modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> IV - Pilot Run Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>

          <form id="frm_pilotrun4_details" method="post">
          @csrf
            <div class="modal-body">
              
              <center>
                <h3>PILOT RUN EVALUATION REPORT</h3>
              </center>
              <!-- <button type="button" id="btn_edit_pilotrun_details_primary" class="btn btn-sm btn-link float-right"><i class="fa fa-edit"></i> Edit</button> -->

              <input type="hidden" name="employee_id" id="frm_edit_pilotrun_details_employee_id">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_use_for_device" name="txt_use_for_device" style="color: blue; font-weight: bold;" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Num</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_number" name="txt_po_number" readonly>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_qty" name="txt_po_qty" readonly>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Pair No/s.</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_pair_no" name="txt_pair_no" readonly>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Type of Qualification</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_type_quali" name="txt_type_quali" readonly>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Start Date/Time</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_start_datetime" name="txt_start_datetime" readonly>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Finish Date/Time</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_finish_datetime" name="txt_finish_datetime" readonly>
                    </div>
                  </div>
                </div>
                
                <div class="accordion" id="accordionMain">
                  <div class="card">
                    <div class="card-header" id="headingOne">
                      <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          I. MATERIAL / PRODUCT / PROCESS REQUIREMENT CHECKING (IN-CHARGE: PRODUCTION)
                        </button>
                      </h5>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionMain">
                      <div class="card-body">
                        PRODUCT DRAWING VS. MATERIAL LIST / OTHER REQUIRED DOCUMENTS AND REFERENCES 

                        <div class="row">
                          <div class="col-sm-4">
                              <span class="badge badge-warning">1.</span> PRODUCT DRAWING REVISION CHECKING
                              <div class="row row_container">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Adrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                      <span class="input-group-text w-100">1.1 A / KL Drawing #</span>
                                    </div>
                                      <input type="text" class="form-control" id="txt_Adrawing" name="txt_Adrawing" readonly>
                                    <input type="text" value="N/A" class="form-control form-control-sm" id="txt_Adrawing_rev" name="txt_Adrawing_rev" readonly>
                                   </div>
                                </div>
                              </div>
                              <div class="row row_container">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Gdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                      <span class="input-group-text w-100">1.2 G / AG Drawing #</span>
                                    </div>
                                      <input type="text" class="form-control" id="txt_Gdrawing" name="txt_Gdrawing" readonly>
                                    <input type="text" value="N/A" class="form-control form-control-sm" id="txt_Gdrawing_rev" name="txt_Gdrawing_rev" readonly>
                                   </div>
                                </div>
                              </div>
                              <div class="row row_container">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Packdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                      <span class="input-group-text w-100">1.3 Packing Doc #</span>
                                    </div>
                                      <input type="text" class="form-control" id="txt_Packdrawing" name="txt_Packdrawing" readonly>
                                    <input type="text" value="N/A" class="form-control form-control-sm" id="txt_Packdrawing_rev" name="txt_Packdrawing_rev" readonly>
                                   </div>
                                </div>
                              </div>
                              <div class="row row_container">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_Packdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                      <span class="input-group-text w-100">1.4 Packing Specs</span>
                                    </div>
                                      <input type="text" class="form-control" id="txt_Packdrawing" name="txt_Packdrawing" readonly>
                                    <input type="text" value="N/A" class="form-control form-control-sm" id="txt_Packdrawing_rev" name="txt_Packdrawing_rev" readonly>
                                   </div>
                                </div>
                              </div>
                              <div class="row row_container">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_UDdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                      <span class="input-group-text w-100">1.5 TDI / Urgent Direction</span>
                                    </div>
                                      <input type="text" class="form-control" id="txt_UDdrawing" name="txt_UDdrawing" readonly>
                                    <input type="text" value="N/A" class="form-control form-control-sm" id="txt_UDdrawing_rev" name="txt_UDdrawing_rev" readonly>
                                   </div>
                                </div>
                              </div>
                              <div class="row">
                                 <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Production Name</span>
                                       <input type="hidden" id="add_prodn_name1" name="add_prodn_name1">
                                    </div>
                                    <input type="text" class="form-control" id="add_prodn_name1x" name="add_prodn_name1x" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                    </div>
                                    <textarea class="form-control form-control-sm" id="txt_remarks1" name="txt_remarks1" rows="3" readonly></textarea>
                                  </div>
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <span class="badge badge-warning">2.</span> ASSEMBLY REQUIREMENT CHECKING
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">2.1 Assembly</span>
                                    </div>
                                    <input type="text" class="form-control" id="txt_type_assembly" name="txt_type_assembly" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">2.2 Parts for Printing</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_parts_printing" name="txt_parts_printing" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">No. of Printing Required</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_printing_req" name="txt_printing_req" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Character to be printed</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_char_printed" name="txt_char_printed" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Page of Documents</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_pagerefdoc_adrawing" name="txt_pagerefdoc_adrawing" placeholder="A Drawing" readonly>
                                    <input type="text" class="form-control form-control-sm" id="txt_pagerefdoc_gdrawing" name="txt_pagerefdoc_gdrawing" placeholder="G Drawing" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">2.3 Gluing Requirement</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_gluing_req" name="txt_gluing_req" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">2.4 Loctite Requirement</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_loctite_req" name="txt_loctite_req" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">2.5 HVT Requirement</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_hvt_req" name="txt_hvt_req" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">2.6 E-TEST Requirement</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_etest_req" name="txt_etest_req" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                 <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Production Name</span>
                                       <input type="hidden" id="add_prodn_name2" name="add_prodn_name2">
                                    </div>
                                    <input type="text" class="form-control" id="add_prodn_name2x" name="add_prodn_name2x" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                    </div>
                                    <textarea class="form-control form-control-sm" id="txt_remarks2" name="txt_remarks2" rows="3" readonly></textarea>
                                  </div>
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <span class="badge badge-warning">3.</span> PARTS CHECKING
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">3.1 Provisional Insertion Parts Requirement</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_prov_ins_req" name="txt_prov_ins_req" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">3.2 Assembly Parts Requirement</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_assy_parts_req" name="txt_assy_parts_req"  readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">3.3 Accessory Parts Requirement</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_accessory_req" name="txt_accessory_req" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">No. of Accessories</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_no_accessory" name="txt_no_accessory" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                 <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Production Name</span>
                                       <input type="hidden" id="add_prodn_name3" name="add_prodn_name3">
                                    </div>
                                    <input type="text" class="form-control" id="add_prodn_name3x" name="add_prodn_name3x" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                    </div>
                                    <textarea class="form-control form-control-sm" id="txt_remarks3" name="txt_remarks3" rows="3" readonly></textarea>
                                  </div>
                                </div>
                              </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingTwo">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          II. PRODUCT / ASSEMBLY CHECKING AND POTEMTIAL ASSEMBLY FAILURE ANALYSIS (IN-CHARGE: PRODUCTION / ENGINEERING / QC)
                        </button>
                      </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionMain">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-3">
                            <span class="badge badge-warning">1.</span> NEED PLANARITY CHECKING USING GAUGE?
                          </div>  
                          <div class="col-sm-4">
                            <input type="radio" id="rdo_planarity_yes" name="product_type" value="1"> YES
                            <input type="radio" id="rdo_planarity_no" name="product_type" value="2"> NO
                          </div>

                          <div class="col-sm-12">
                            <span class="badge badge-warning">2.</span> IDENTIFY POTENTIAL CRITICAL FAILURES (CHECKING OF MATERIALS AND ASSEMBLY PROCESS VS. PRODUCT DRAWING AND OTHER REFERENCES)

                            <div class="table-responsive" style="max-height: 500px;">
                              <table class="table table-sm table-bordered table-hover small" id="tblAssemblyAnalysis" style="min-width: 900px;">  
                                <thead>
                                  <tr class="bg-light" align="center">
                                    <th rowspan="3">Action</th>
                                    <th colspan="2" style="text-align: center;"><b>IN-CHARGE: PRODUCTION / ENGINEERING</b></th>
                                    <th colspan="3" style="text-align: center;"><b>IN-CHARGE: PRODUCTION / ENGINEERING</b></th>
                                    <th colspan="3" style="text-align: center;"><b>IN-CHARGE: ENGINEERING</b></th>
                                    <th colspan="2" style="text-align: center;"><b>IN-CHARGE: QC</b></th>
                                  </tr>
                                  <tr class="bg-light" align="center">
                                    <th rowspan="2">POTENTIAL FAILURES</th>
                                    <th rowspan="2">AFFECTED PARTS</th>
                                    <th colspan="2">SHORT ACTIONS</th>
                                    <th rowspan="2">SHORT TERM DUE DATE</th>
                                    <th colspan="2">LONG TERM ACTIONS</th>
                                    <th rowspan="2">LONG TERM DUE DATE</th>
                                    <th colspan="2">LONG TERM ACTION VALIDATION</th>
                                  </tr>
                                  <tr class="bg-light" align="center">
                                    <th>INITIAL ACTION</th>
                                    <th>ORCTO</th>
                                    <th>TOOLS/JIGS</th>
                                    <th>POINT PANEL</th>
                                    <th>VALIDATED BY</th>
                                    <th>DATE</th>
                                  </tr>
                                </thead>
                              </table>  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingThree">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                          III. CHECKING FOR PRODUCT COMPLIANCE (IN-CHARGE: QC)
                        </button>
                      </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionMain">
                      <div class="card-body">

                        UNIT, N - 1 VS. PRODUCT DRAWING AND OTHER REFERENCES <br>
                        <div class="row">
                          <div class="col-sm-4">
                            <span class="badge badge-warning">1.</span> TERMINAL LENGTH
                            <input type="radio" id="rdo_terminal_ok1" name="terminal_length1" value="1"> OK
                            <input type="radio" id="rdo_terminal_ng1" name="terminal_length1" value="2"> NG
                            <input type="radio" id="rdo_terminal_na1" name="terminal_length1" value="3"> N/A
                            
                            <div class="col-sm-12">
                            <div class="row">
                               <div class="col">
                                <div class="input-group input-group-sm mb-2">
                                  <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">QC Name</span>
                                     <input type="hidden" id="add_qc_name1" name="add_qc_name1">
                                  </div>
                                  <input type="text" class="form-control" id="add_qc_name1x" name="add_qc_name1x" readonly>
                                </div>
                              </div>
                            </div>
                            </div>

                            <span class="badge badge-warning">2.</span> MEASUREMENTS RESULTS
                            <input type="radio" id="rdo_terminal_ok2" name="terminal_length2" value="1"> OK
                            <input type="radio" id="rdo_terminal_ng2" name="terminal_length2" value="2"> NG
                            <input type="radio" id="rdo_terminal_na2" name="terminal_length2" value="3"> N/A
                            
                            <div class="col-sm-12">
                            <div class="row">
                               <div class="col">
                                <div class="input-group input-group-sm mb-2">
                                  <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">QC Name</span>
                                     <input type="hidden" id="add_qc_name2" name="add_qc_name2">
                                  </div>
                                  <input type="text" class="form-control" id="add_qc_name2x" name="add_qc_name2x" readonly>
                                </div>
                              </div>
                            </div>
                            </div>
                          </div>

                          <div class="col-sm-8">
                            PRODUCT INSPECTION ITEMS

                          <div class="row">
                            <div class="col-sm-4">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                  <span class="input-group-text w-100" id="basic-addon1">Dimension</span>
                                </div>
                                  <input type="text" class="form-control" id="txt_dimension" name="txt_dimension" readonly>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                  <span class="input-group-text w-100" id="basic-addon1">Visual Inspection</span>
                                </div>
                                  <input type="text" class="form-control" id="txt_visual_insp" name="txt_visual_insp" readonly>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                  <span class="input-group-text w-100" id="basic-addon1">Assembly Test</span>
                                </div>
                                  <input type="text" class="form-control" id="txt_assy_test" name="txt_assy_test" readonly>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="input-group input-group-sm mb-2">
                                <textarea class="form-control form-control-sm" id="3_dimension_remarks" name="3_dimension_remarks" rows="2" readonly></textarea>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="input-group input-group-sm mb-2">
                                <textarea class="form-control form-control-sm" id="3_visual_insp_remarks" name="3_visual_insp_remarks" rows="2" readonly></textarea>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="input-group input-group-sm mb-2">
                                <textarea class="form-control form-control-sm" id="3_assy_test_remarks" name="3_assy_test_remarks" rows="2" readonly></textarea>
                              </div>
                            </div>
                          </div>
                          </div>
                          <div class="col-sm-4">
                            PTIS/NG REPORT ISSUANCE/CONTROL NO.
                            <div class="row">  
                              <div class="col-sm-12">
                                <div class="input-group input-group-sm mb-2">
                                  <textarea class="form-control form-control-sm" id="3_PTIS_control_num" name="3_PTIS_control_num" rows="2" readonly></textarea>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            HIGHLIGHTS
                            <div class="row">  
                              <div class="col-sm-12">
                                <div class="input-group input-group-sm mb-2">
                                  <textarea class="form-control form-control-sm" id="3_PTIS_control_num" name="3_PTIS_control_num" rows="2" readonly></textarea>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>

                      </div>
                    </div>
                  </div>

                </div>

                <div class="col-sm-12 border px-12">
                  <span class="badge badge-primary">IV.</span> PRODUCT / ASSEMBLY CHECKING (IN-CHARGE: PRODUCTION / ENGINEERING / QC) <br>
                  UNIT, N - 1 VS. PRODUCT DRAWING AND OTHER REFERENCES (INDICATE OK, NG, OR NOT APPLICABLE) <br>
                  NOTE: 1. Initial check shall be performed using half assembled product prior measurement qualification (check the orientation of parts being installed). <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  2. Final check shall be performed using fully assembled product.
                  <br>
                  <div class="row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-2">
                      <span class="badge badge-primary">PRODUCTION</span>
                    </div>
                    <div class="col-sm-2">
                      <span class="badge badge-primary">ENGINEERING</span>
                    </div>
                    <div class="col-sm-2">
                      <span class="badge badge-primary">QC</span>
                    </div>
                    <div class="col-sm-2">
                      <span class="badge badge-primary">REMARKS</span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">1. Pin Pattern</span>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_1p_ini_pat" name="txt_1p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_1p_fin_pat" name="txt_1p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_1e_ini_pat" name="txt_1e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_1e_fin_pat" name="txt_1e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_1q_ini_pat" name="txt_1q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_1q_fin_pat" name="txt_1q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_1_remarks" name="txt_1_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">2. Parts Correctness/Orientation</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_2p_ini_pat" name="txt_2p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_2p_fin_pat" name="txt_2p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_2e_ini_pat" name="txt_2e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_2e_fin_pat" name="txt_2e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_2q_ini_pat" name="txt_2q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_2q_fin_pat" name="txt_2q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_2_remarks" name="txt_2_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">3. Parts Completeness/Usage</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_3p_ini_pat" name="txt_3p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_3p_fin_pat" name="txt_3p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_3e_ini_pat" name="txt_3e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_3e_fin_pat" name="txt_3e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_3q_ini_pat" name="txt_3q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_3q_fin_pat" name="txt_3q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_3_remarks" name="txt_3_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">4. Correctness of Accessories</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_4p_ini_pat" name="txt_4p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_4p_fin_pat" name="txt_4p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_4e_ini_pat" name="txt_4e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_4e_fin_pat" name="txt_4e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_4q_ini_pat" name="txt_4q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_4q_fin_pat" name="txt_4q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_4_remarks" name="txt_4_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">5. Set Assembly Condition</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_5p_ini_pat" name="txt_5p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_5p_fin_pat" name="txt_5p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_5e_ini_pat" name="txt_5e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_5e_fin_pat" name="txt_5e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_5q_ini_pat" name="txt_5q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_5q_fin_pat" name="txt_5q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_5_remarks" name="txt_5_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">6. Printing Condition</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_6p_ini_pat" name="txt_6p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_6p_fin_pat" name="txt_6p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_6e_ini_pat" name="txt_6e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_6e_fin_pat" name="txt_6e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_6q_ini_pat" name="txt_6q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_6q_fin_pat" name="txt_6q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_6_remarks" name="txt_6_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">6.1 Actual Character Printed</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_61p_ini_pat" name="txt_61p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_61p_fin_pat" name="txt_61p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_61e_ini_pat" name="txt_61e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_61e_fin_pat" name="txt_61e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_61q_ini_pat" name="txt_61q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_61q_fin_pat" name="txt_61q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_61_remarks" name="txt_61_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">6.2 Reference Drawinng Page #</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_62p_ini_pat" name="txt_62p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_62p_fin_pat" name="txt_62p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_62e_ini_pat" name="txt_62e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_62e_fin_pat" name="txt_62e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_62q_ini_pat" name="txt_62q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_62q_fin_pat" name="txt_62q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_62_remarks" name="txt_62_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">7. Gluing Condition</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_7p_ini_pat" name="txt_7p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_7p_fin_pat" name="txt_7p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_7e_ini_pat" name="txt_7e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_7e_fin_pat" name="txt_7e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_7q_ini_pat" name="txt_7q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_7q_fin_pat" name="txt_7q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_7_remarks" name="txt_7_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">8. Loctite Application Condition</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_8p_ini_pat" name="txt_8p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_8p_fin_pat" name="txt_8p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_8e_ini_pat" name="txt_8e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_8e_fin_pat" name="txt_8e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_8q_ini_pat" name="txt_8q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_8q_fin_pat" name="txt_8q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_8_remarks" name="txt_8_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">9. Units Undergone HVT</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_9p_ini_pat" name="txt_9p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_9p_fin_pat" name="txt_9p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_9e_ini_pat" name="txt_9e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_9e_fin_pat" name="txt_9e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_9q_ini_pat" name="txt_9q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_9q_fin_pat" name="txt_9q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_9_remarks" name="txt_9_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">10. Units Undergone E-test</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_10p_ini_pat" name="txt_10p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_10p_fin_pat" name="txt_10p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_10e_ini_pat" name="txt_10e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_10e_fin_pat" name="txt_10e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_10q_ini_pat" name="txt_10q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_10q_fin_pat" name="txt_10q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_10_remarks" name="txt_10_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">11. Inspection using microscope with 20X magnification 
                          on all pilot run samples (TSB area) </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_11p_ini_pat" name="txt_11p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_11p_fin_pat" name="txt_11p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_11e_ini_pat" name="txt_11e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_11e_fin_pat" name="txt_11e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_11q_ini_pat" name="txt_11q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_11q_fin_pat" name="txt_11q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_11_remarks" name="txt_11_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">12. TDI Urgent Directions</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_12p_ini_pat" name="txt_12p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_12p_fin_pat" name="txt_12p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_12e_ini_pat" name="txt_12e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_12e_fin_pat" name="txt_12e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_12q_ini_pat" name="txt_12q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_12q_fin_pat" name="txt_12q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_12_remarks" name="txt_12_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">13. Packing Specs (MD Doc #)</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_13p_ini_pat" name="txt_13p_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_13p_fin_pat" name="txt_13p_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_13e_ini_pat" name="txt_13e_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_13e_fin_pat" name="txt_13e_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_13q_ini_pat" name="txt_13q_ini_pat" placeholder="Initial Check" readonly>
                        <input type="text" class="form-control" id="txt_13q_fin_pat" name="txt_13q_fin_pat" placeholder="Final Check" >
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_13_remarks" name="txt_13_remarks">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">Checked By</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_p_checkedby" name="txt_p_checkedby" readonly>
                        <input type="hidden" id="txt_pr_checkedby" name="txt_pr_checkedby">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-info btn-sm" id="btnSearchEmp" data-toggle="modal" data-target="#modalSearchEmp" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                        </div>                      
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_e_checkedby" name="txt_e_checkedby" readonly>
                        <input type="hidden" id="txt_ee_checkedby" name="txt_ee_checkedby">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-info btn-sm" id="btnSearchEmp" data-toggle="modal" data-target="#modalSearchEmp" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                        </div>                      
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_q_checkedby" name="txt_q_checkedby" readonly>
                        <input type="hidden" id="txt_qc_checkedby" name="txt_qc_checkedby">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-info btn-sm" id="btnSearchEmp" data-toggle="modal" data-target="#modalSearchEmp" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                        </div>                      
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">Date/Time</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_p_datetime" name="txt_p_datetime" placeholder="Auto generated" readonly>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_e_datetime" name="txt_e_datetime" placeholder="Auto generated" readonly>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" id="txt_q_datetime" name="txt_q_datetime" placeholder="Auto generated" readonly>
                      </div>
                    </div>
                  </div>
                  <br>

                  <div class="row">
                    <div class="col-sm-6">
                      ENGINEERING JUDGEMENT/DISPOSITION (Result of checkiung on Item II)
                      <br>
                      <input type="radio" id="rdo_ee_go" name="ee_judge" value="1"> GO on mass production <br>
                      <input type="radio" id="rdo_ee_hold" name="ee_judge" value="2"> HOLD, need to make actions
                      
                      <div class="row">
                         <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Name</span>
                               <input type="hidden" id="txt_ee_judg_name" name="txt_ee_judg_name">
                            </div>

                            <input type="text" class="form-control" id="txt_ee_judg_namex" name="txt_ee_judg_namex" readonly>
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-info btn-sm" id="btnSearchName" data-toggle="modal" data-target="#modalSearchName" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                         <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Date</span>
                            </div>
                            <input type="text" class="form-control" id="txt_ee_judg_datetime" name="txt_ee_judg_datetime" placeholder="Auto generated" readonly>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      QC JUDGEMENT/DISPOSITION/CONFORMANCE (Result of checkiung on Item II & III)
                      <br>
                      <input type="radio" id="rdo_qc_go" name="qc_judge" value="1"> GO on mass production <br>
                      <input type="radio" id="rdo_qc_hold" name="qc_judge" value="2"> HOLD, need to make actions
                      
                      <div class="row">
                         <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Name</span>
                               <input type="hidden" id="txt_qc_judg_name" name="txt_qc_judg_name">
                            </div>

                            <input type="text" class="form-control" id="txt_qc_judg_namex" name="txt_qc_judg_namex" readonly>
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-info btn-sm" id="btnSearchName" data-toggle="modal" data-target="#modalSearchName" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                         <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Date</span>
                            </div>
                            <input type="text" class="form-control" id="txt_qc_judg_datetime" name="txt_qc_judg_datetime" placeholder="Auto generated" readonly>
                          </div>
                        </div>
                      </div>


                    </div>

                  
                  </div>



              </div>


            </div>
            <div class="modal-footer">
              <button type="submit" id="id_btn_save_1" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
              <button type="button" id="id_btn_close_1" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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

                $("#modalPilotRunDetails4").modal('show');

      //     }

      //   })

      // }
    });

  </script>
  @endsection
@endauth