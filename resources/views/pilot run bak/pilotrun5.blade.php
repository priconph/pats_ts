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

  @section('title', 'Pilot Run1')

  @section('content_page')
  <style type="text/css">
    .modal-xl-custom{
      width: 60%!important;
      min-width: 60%!important;
    }
  </style>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>I - Pilot Run</h1>
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
                <h3 class="card-title">I - Pilot Run Summary (In-Charge: Production)</h3>
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
          <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> I - Pilot Run Details</h5>
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
                      <input type="text" class="form-control form-control-sm" id="txt_pair_no" name="txt_pair_no">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Type of Qualification</span>
                      </div>
                       <select class="form-control form-control-sm" id="txt_type_quali" name="txt_type_quali">
                        <option selected disabled>-- Choose One --</option>
                        <option value="0">Regular Control</option>
                        <option value="1">Special Control</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Start Date/Time</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_start_datetime" name="txt_start_datetime" readonly="true" placeholder="Auto generated">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Finish Date/Time</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_finish_datetime" name="txt_finish_datetime" readonly="true" placeholder="Auto generated">
                    </div>
                  </div>
                </div>
              

              <div class="col-sm-12">
                <span class="badge badge-primary">I.</span> MATERIAL / PRODUCT / PROCESS REQUIREMENT CHECKING (IN-CHARGE: PRODUCTION) <br>
                PRODUCT DRAWING VS. MATERIAL LIST / OTHER REQUIRED DOCUMENTS AND REFERENCES 
                <br>
                <br>
              
                  <div class="col-sm-12">
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
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn1" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                            </div>
                            <textarea class="form-control form-control-sm" id="txt_remarks1" name="txt_remarks1" rows="3" ></textarea>
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="col-sm-12">
                      <span class="badge badge-warning">2.</span> ASSEMBLY REQUIREMENT CHECKING
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">2.1 Assembly</span>
                            </div>
                             <select class="form-control form-control-sm" id="txt_type_assembly" name="txt_type_assembly">
                              <option selected disabled>-- Choose One --</option>
                              <option value="0">Single Assembly</option>
                              <option value="1">Set Assembly</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">2.2 Parts for Printing</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="txt_parts_printing" name="txt_parts_printing">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">No. of Printing Required</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="txt_printing_req" name="txt_printing_req">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Character to be printed</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="txt_char_printed" name="txt_char_printed">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Page of Documents</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="txt_pagerefdoc_adrawing" name="txt_pagerefdoc_adrawing" placeholder="A Drawing">
                            <input type="text" class="form-control form-control-sm" id="txt_pagerefdoc_gdrawing" name="txt_pagerefdoc_gdrawing" placeholder="G Drawing">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">2.3 Gluing Requirement</span>
                            </div>
                             <select class="form-control form-control-sm" id="txt_gluing_req" name="txt_gluing_req">
                              <option selected disabled>-- Choose One --</option>
                              <option value="0">With</option>
                              <option value="1">Without</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">2.4 Loctite Requirement</span>
                            </div>
                             <select class="form-control form-control-sm" id="txt_loctite_req" name="txt_loctite_req">
                              <option selected disabled>-- Choose One --</option>
                              <option value="0">With</option>
                              <option value="1">Without</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">2.5 HVT Requirement</span>
                            </div>
                             <select class="form-control form-control-sm" id="txt_hvt_req" name="txt_hvt_req">
                              <option selected disabled>-- Choose One --</option>
                              <option value="0">With</option>
                              <option value="1">Without</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">2.6 E-TEST Requirement</span>
                            </div>
                             <select class="form-control form-control-sm" id="txt_etest_req" name="txt_etest_req">
                              <option selected disabled>-- Choose One --</option>
                              <option value="0">With</option>
                              <option value="1">Without</option>
                            </select>
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
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn2" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                            </div>
                            <textarea class="form-control form-control-sm" id="txt_remarks2" name="txt_remarks2" rows="3"></textarea>
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="col-sm-12">
                      <span class="badge badge-warning">3.</span> PARTS CHECKING
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">3.1 Provisional Insertion Parts Requirement</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="txt_prov_ins_req" name="txt_prov_ins_req">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">3.2 Assembly Parts Requirement</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="txt_assy_parts_req" name="txt_assy_parts_req">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">3.3 Accessory Parts Requirement</span>
                            </div>
                             <select class="form-control form-control-sm" id="txt_accessory_req" name="txt_accessory_req">
                              <option selected disabled>-- Choose One --</option>
                              <option value="0">With</option>
                              <option value="1">Without</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">No. of Accessories</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="txt_no_accessory" name="txt_no_accessory">
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
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-info btn-sm" id="btnSearchProdn3" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                            </div>
                            <textarea class="form-control form-control-sm" id="txt_remarks3" name="txt_remarks3" rows="3" ></textarea>
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

                $("#modalPilotRunDetails").modal('show');

      //     }

      //   })

      // }
    });

  </script>
  @endsection
@endauth