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

  @section('title', 'Accessory Tag Printing')

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
    #div_layout_1, #div_layout_2{
      transition: .5s;
    }
/*    .table th:first-child{
      width: 900px!important;
    }
*/
  .table{
    min-width: 600px;
  }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Accessory Tag Printing</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Accessory Tag Printing</li>
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
          <div class="col-12" id="div_layout_1">
            <!-- general form elements -->
            <div class="card card-primary">
              <!-- Start Page Content -->
              <h6 class="card-header"><i class="far fa-clock fa-sm"></i> Recent Print
                  <button class="btn btn-info btn-sm float-right" id="btn_create" type="button"><i class="fa fa-plus"></i> Create New</button>
              </h6>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_recent">
                        <thead>
                          <tr class="bg-light">
                            <!-- <th>Action</th> -->
                            <!-- <th>Status</th> -->
                            <th></th>
                            <th>PO</th>
                            <th>Device</th>
                            <th>Details</th>
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

    <!-- Modal -->
    <div class="modal fade" id="mdl_to_print" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Sticker Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-6">
                <form id="frm_sticker_details">
                  <div class="row mb-3" id="row_search_po">
                    <div class="col-12">
                      <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-info" id="btn_scan_po"><i class="fa fa-qrcode"></i></button>
                        </div>
                        <input type="search" class="form-control" placeholder="Scan PO Number" id="txt_search_po_number" form="" readonly><!-- value="450198990900010" -->
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">PO</span>
                        </div>
                        <input type="text" class="form-control" id="txt_print_po_no" name="txt_print_po_no" readonly>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Product name</span>
                        </div>
                        <input type="text" class="form-control" id="txt_print_device_name" name="txt_print_device_name" readonly>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100">Shipment Remarks</span>
                        </div>
                        <textarea class="form-control form-control-sm" rows="2" id="txt_print_po_remarks" name="txt_print_po_remarks"></textarea>
                      </div>
                    </div>
                    <div class="col-12 text-right">
                      <button type="button" class="btn btn-sm btn-success" id="btn_print_add">Save</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="row mb-2 mt-3">
              <div class="col-12">
                <div class="table-responsive border p-2" style="max-height: 400px;">
                  <table class="table table-sm table-bordered table-hover" id="tbl_accessories" style="min-width: auto;">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Item name</th>
                        <th>Qty</th>
                        <th>Usage</th>
                        <th>Counted by</th>
                        <th>Date counted</th>
                        <th>Details</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                  <div class="row mt-2">
                    <div class="col-6">
                      <button type="button" class="btn btn-sm btn-link" id="btn_add_accessory"><i class="fa fa-plus"></i> Add Accessories</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <dir class="col">
                <label class="font-weight-normal">
                  <input type="checkbox" name="ckb_print_accessory_tag" id="ckb_print_accessory_tag"> Print the box accessory tag
                </label>
              </dir>
            </div>
            <div class="row mb-2 mt-4">
              <div class="col-6">
                <div class="input-group input-group-sm mb-1">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100">Print Remarks</span>
                  </div>
                  <textarea class="form-control form-control-sm" rows="2" id="txt_print_remarks" name="txt_print_remarks"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4 pr-0">
                <select id="txt_printer_name" class="form-control form-control-sm" hidden>
                  <option value="">-- Please select printer --</option>
<!--                  <option value="ZDesigner ZT230-200dpi ZPL ZT220">CN2 - Packing - ZDesigner ZT230-200dpi ZPL ZT220</option>
                  <option value="ZDesigner ZT220-200dpi ZPL">CN1 - Packing - ZDesigner ZT220-200dpi ZPL</option> -->
                  <option value="ZDesigner ZT220-200dpi">PPC - ZDesigner ZT220-200dpi</option>
                </select>
              </div>
              <div class="col-2 text-right">
                <!-- <button type="button" class="btn btn-sm btn-warning" id="btn_re_print">Print for NG</button> -->
                <button type="button" class="btn btn-sm btn-info" id="btn_print">Print</button>
                <button type="button" class="btn btn-sm btn-info" id="btn_history" title="Pint History" hidden> <i class="fa fa-clock"></i> </button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" id="btn_delete_po" hidden>Delete</button>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->


    <!-- Modal -->
    <div class="modal fade" id="mdl_add_accessory" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Accessory</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="frm_add_accessory">
              <div class="row">
                <div class="col-12">
                  <div class="input-group input-group-sm mb-4">
                    <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100">Search item name</span>
                    </div>
                    <input type="search" class="form-control" name="txt_add_accessory_search_item" id="txt_add_accessory_search_item" list="txt_add_accessory_search_item_datalist">
                    <datalist id="txt_add_accessory_search_item_datalist"></datalist>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100">Item name</span>
                    </div>
                    <input type="text" class="form-control" name="txt_add_accessory_item_name" id="txt_add_accessory_item_name" readonly required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100">Quantity</span>
                    </div>
                    <input type="number" class="form-control" name="txt_add_accessory_qty" id="txt_add_accessory_qty" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="input-group input-group-sm mb-4">
                    <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100">Usage per socket</span>
                    </div>
                    <input type="number" class="form-control" name="txt_add_accessory_usage" id="txt_add_accessory_usage" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100">Counted by</span>
                    </div>
                    <select class="form-control" name="txt_add_accessory_counted_by" id="txt_add_accessory_counted_by" required>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="input-group input-group-sm mb-4">
                    <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100">Date</span>
                    </div>
                    <input type="date" class="form-control" name="txt_add_accessory_counted_date" id="txt_add_accessory_counted_date" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100">Remarks</span>
                    </div>
                    <textarea class="form-control" name="txt_add_accessory_remarks" id="txt_add_accessory_remarks"></textarea>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-success" id="btn_save_accessory">Save</button>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
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
    <div class="modal fade" id="mdl_yesno" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center">
              <div id="mdl_yesno_body">Needs parts prep?</div>
              <br>
              <button type="button" id="btn_yesno_yes" class="btn btn-sm btn-success w-25">Yes</button>
              <button type="button" id="btn_yesno_no" class="btn btn-sm btn-danger w-25">No</button>
            </div>
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
    <img hidden src="{{ asset('public/images/pricon-logo.png?v=2') }}">

    <textarea hidden id="txt_html_sticker_container_1">
      <!DOCTYPE html>
      <html>
      <head>
        <title></title>
        <style type="text/css">
          *{
            font-family: "arial narrow", sans-serif;
          }
          table, tr, td{
            border: 1px solid #111;
            border: none;
          }
          table{
            border-collapse: collapse;
            border-spacing: 0;
          }
          td,tr{
            height: 0px!important;
            padding: 0px!important;
            margin: 0px!important;
          }
          td{
            padding: 0px 8px!important;
          }
          .table-container{
            vertical-align: top;
            width: 600px;
            min-width: 600px;
            max-width: 600px;
            height: 300px;
            min-height: 300px;
            max-height: 300px;
            border: 1px solid #000;
          }
          .border-bottom{
            border-bottom: 1px solid #000;
          }
          .text-left{
            text-align: left;
          }
          .text-right{
            text-align: right;
          }
          .text-center{
            text-align: center;
          }
          .text-middle{
            vertical-align: middle;
          }
          .text-bottom{
            vertical-align: bottom;
          }
          .text-top{
            vertical-align: top;
          }
          .float-right{
            float: right;
          }
          .bold{
            font-weight: bold;
          }
          .h5{
            font-size: 1.1em;
          }
          /*----------------*/
          .table1{
            font-size: 1.15em;
          }
          .logo-left{
            height: 40px;
            margin-left: 1px;
          }
          /*----------------*/
          .table2{
            font-size: 1.1em;
          }
          .logo-center{
            height: 50px;
            margin: 0;
            padding: 0;
            margin-top: 1px;
          }
          /*----------------*/
          .table3{
            font-size: 1.1em;
          }
          tr.border,.border td{
            border: 1px solid #111;
          }
        </style>
      </head>
      <body id="container">
    </textarea>

    <textarea hidden id="txt_html_sticker_container_2">
      </body>
      </html>
    </textarea>

    <textarea hidden id="txt_html_box_container_1">
      <table class="table-container">
        <tr>
          <td>
            <table class="table2 text-middle" cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td colspan="6" class="text-center">
                    <img class="logo-center" style="background: blue;" src="{{ asset('public/images/pricon-logo.png?v=2') }}">
                  </td>
                </tr>
                <tr>
                  <td colspan="6" class="bold text-center h5">Accessory Tag</td>
                </tr>
                <tr>
                  <td style="height: 1px; width: 1%"></td>
                  <td style="height: 1px; width: 40%"></td>
                  <td style="height: 1px; width: 40%"></td>
                  <td style="height: 1px; width: 2%"></td>
                  <td style="height: 1px; width: 16%"></td>
                  <td style="height: 1px; width: 1%"></td>
                </tr>
    </textarea>

    <textarea hidden id="txt_html_box_container_2">
                <tr>
                  <td colspan="6"><small><i>This marked box should go with each MASTER box</i></small></td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </table>
    </textarea>

    <textarea hidden id="txt_html_plastic_container_1">
    </textarea>

    <textarea hidden id="txt_html_plastic_container_2">
    </textarea>



    <textarea hidden id="txt_sticker_details">
      <!DOCTYPE html>
      <html>
      <head>
        <title></title>
        <style type="text/css">
          *{
            font-family: "arial narrow", sans-serif;
          }
          table, tr, td{
            border: 1px solid #111;
            border: none;
          }
          table{
            border-collapse: collapse;
            border-spacing: 0;
          }
          td,tr{
            height: 0px!important;
            padding: 0px!important;
            margin: 0px!important;
          }
          td{
            padding: 0px 8px!important;
          }
          .table-container{
            vertical-align: top;
            width: 600px;
            min-width: 600px;
            max-width: 600px;
            height: 300px;
            min-height: 300px;
            max-height: 300px;
            border: 1px solid #000;
          }
          .border-bottom{
            border-bottom: 1px solid #000;
          }
          .text-left{
            text-align: left;
          }
          .text-right{
            text-align: right;
          }
          .text-center{
            text-align: center;
          }
          .text-middle{
            vertical-align: middle;
          }
          .text-bottom{
            vertical-align: bottom;
          }
          .text-top{
            vertical-align: top;
          }
          .float-right{
            float: right;
          }
          .bold{
            font-weight: bold;
          }
          .h5{
            font-size: 1.1em;
          }
          /*----------------*/
          .table1{
            font-size: 1.15em;
          }
          .logo-left{
            height: 40px;
            margin-left: 1px;
          }
          /*----------------*/
          .table2{
            font-size: 1.1em;
          }
          .logo-center{
            height: 50px;
            margin: 0;
            padding: 0;
            margin-top: 1px;
          }
          /*----------------*/
          .table3{
            font-size: 1.1em;
          }
          tr.border,.border td{
            border: 1px solid #111;
          }

        </style>
      </head>
      <body id="container">
        <?php
        // $device_name = "Series Name: WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        // // $device_name = "NP556-0161-007";//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        // $device_name = chunk_split($device_name,33,"^");
        // $device_name = explode('^', $device_name);
        // $device_name = implode('<br>', $device_name);
        ?>
        <!-- <table class="table-container">
          <tr>
            <td>
              <table class="table3 text-middle text-center" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td colspan="6" class="border-bottom text-left"><?php //echo $device_name; ?></td>
                  </tr>
                  <tr class="border">
                    <td colspan="6" class="bold text-center h5">Master Box Quantity Details</td>
                  </tr>
                  <tr class="border bold">
                    <td style="height: 1px; width: 20%">No. of Box From</td>
                    <td style="height: 1px; width: 20%">No. of Box To</td>
                    <td style="height: 1px; width: 20%">Total Box Qty</td>
                    <td style="height: 1px; width: 20%">Unit Qty per White Box</td>
                    <td style="height: 1px; width: 20%">QTY of UNITS</td>
                  </tr>
                  <tr class="border">
                    <td>1</td>
                    <td>23</td>
                    <td>23</td>
                    <td>15</td>
                    <td>360</td>
                  </tr>
                  <tr class="border">
                    <td>1</td>
                    <td>23</td>
                    <td>23</td>
                    <td>15</td>
                    <td>360</td>
                  </tr>
                  <tr class="border bold">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total QTY.>></td>
                    <td>360</td>
                  </tr>
                  <tr>
                    <td colspan="6"><small class="float-right">1/1</small></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </table> -->
        <br>
        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <?php
        $device_name = "WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        $device_name = "NP556-0161-007";//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        $device_name = chunk_split($device_name,32,"^");
        $device_name = explode('^', $device_name);
        $device_name = implode('<br>', $device_name);

        $accessory_name = "WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        $accessory_name = "CROSSED RECESSED PAN HEAD SCREW SUS M3.10";//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        $accessory_name = chunk_split($accessory_name,30,"^");
        $accessory_name = explode('^', $accessory_name);
        $accessory_name = implode('<br>', $accessory_name);
        ?>
        <table class="table-container">
          <tr>
            <td>
              <table class="table2 text-middle" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td colspan="6" class="text-center">
                      <img class="logo-center" style="background: blue;" src="{{ asset('public/images/pricon-logo.png?v=2') }}">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" class="bold text-center h5">Accessory Tag</td>
                  </tr>
                  <tr>
                    <td style="height: 1px; width: 1%"></td>
                    <td style="height: 1px; width: 40%"></td>
                    <td style="height: 1px; width: 40%"></td>
                    <td style="height: 1px; width: 2%"></td>
                    <td style="height: 1px; width: 16%"></td>
                    <td style="height: 1px; width: 1%"></td>
                  </tr>
                  <tr>
                    <td colspan="6" class="border-bottom">Product Name: <u class="bold"><?php echo $device_name; ?></u></td>
                  </tr>
                  <tr>
                    <td colspan="4" class="bold text-center h5">Accessory Name</td>
                    <td colspan="1" class="bold text-center h5">QTY</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="border-bottom">1. <?php echo $accessory_name; ?></td>
                    <td></td>
                    <td class="border-bottom text-center bold">360</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="border-bottom">2. <?php echo $accessory_name; ?></td>
                    <td></td>
                    <td class="border-bottom text-center bold">360</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="6"><small class="float-right">1/2</small></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </table>
        <br>
        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <table class="table-container">
          <tr>
            <td>
              <table class="table2 text-middle" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td style="height: 1px; width: 1%"></td>
                    <td style="height: 1px; width: 40%"></td>
                    <td style="height: 1px; width: 40%"></td>
                    <td style="height: 1px; width: 2%"></td>
                    <td style="height: 1px; width: 16%"></td>
                    <td style="height: 1px; width: 1%"></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="border-bottom">3. <?php echo $accessory_name; ?></td>
                    <td></td>
                    <td class="border-bottom text-center bold">360</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="border-bottom">4. <?php echo $accessory_name; ?></td>
                    <td></td>
                    <td class="border-bottom text-center bold">360</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="border-bottom">5. <?php echo $accessory_name; ?></td>
                    <td></td>
                    <td class="border-bottom text-center bold">360</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="border-bottom">6. <?php echo $accessory_name; ?></td>
                    <td></td>
                    <td class="border-bottom text-center bold">360</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="border-bottom">7. <?php echo $accessory_name; ?></td>
                    <td></td>
                    <td class="border-bottom text-center bold">360</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="6"><small><i>This marked box should go with each MASTER box</i></small> <small class="float-right">2/2</small></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </table>
        <br>
        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <?php
        // $device_name = "Product Name: WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        $device_name = "NP556-0161-007";//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        $device_name = chunk_split($device_name,38,"^");
        $device_name = explode('^', $device_name);
        $device_name = implode('<br>', $device_name);

        // $accessory_name = "Accessory Name: WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        $accessory_name = "CROSSED RECESSED PAN HEAD SCREW SUS M3.10";//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWM";
        $accessory_name = chunk_split($accessory_name,40,"^");
        $accessory_name = explode('^', $accessory_name);
        $accessory_name = implode('<br>', $accessory_name);
        $qty            = '&nbsp;&nbsp;'.'360'.'&nbsp;&nbsp;';
        $usage            = '&nbsp;&nbsp;'.'4'.'&nbsp;&nbsp;';
        ?>
        <table class="table-container">
          <tr>
            <td>
              <table class="table1 text-middle" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td colspan="2" class="text-bottom">
                      <img class="logo-left" src="{{ asset('public/images/pricon-logo.png?v=2') }}">
                    </td>
                    <td colspan="4" class="bold h5">Accessory Tag</td>
                  </tr>
                  <tr>
                    <td style="height: 1px; width: 16%"></td>
                    <td style="height: 1px; width: 16%"></td>
                    <td style="height: 1px; width: 16%"></td>
                    <td style="height: 1px; width: 16%"></td>
                    <td style="height: 1px; width: 16%"></td>
                    <td style="height: 1px; width: 16%"></td>
                  </tr>
                  <tr>
                    <td colspan="6"><u class="bold"><?php echo $device_name; ?></u></td>
                  </tr>
                  <tr>
                    <td colspan="6"><u class="bold"><?php echo $accessory_name; ?></u></td>
                  </tr>
                  <tr>
                    <td colspan="3">Quantity: <u class="bold"><?php echo $qty; ?></u></td>
                    <td colspan="3">Usage per Socket: <u class="bold"><?php echo $usage; ?></u></td>
                  </tr>
                  <tr>
                    <td colspan="6"></td>
                  </tr>
                  <tr>
                    <td colspan="6">Counted by/Date: <u class="bold">Hazel / 10/29/2020</u></td>
                  </tr>
                  <tr>
                    <td colspan="2">Checked by/Date: </td>
                  </tr>
                  <tr>
                    <td colspan="3" class="text-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Visual Optr.: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                    <td colspan="3" class="text-right">Prod. Supv.: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="text-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QC Inspector: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                    <td colspan="3" class="text-right">QC Supv.: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </table>
      </body>
      </html>
    </textarea>


    <!-- Modal -->
    <div class="modal fade" id="mdl_print_temp" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Sticker</h5>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <div>
                  table
                </div>           
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-sm btn-success" id="btn_print_proceed_temp">Print</button>
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
    let dt_batches,dt_lot_numbers,dt_subkitting,dt_parts_prep_stations,dt_sakidashi,dt_warehouse;
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
      //-----
      //-----
      //-----
      $('input').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          if( $(this).prop('type') == 'number' ){
            $(this).prop('placeholder','0');
            if (!this.hasAttribute("min")) {
              $(this).prop('min','1');
            }
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
      //-----
      //-----
      //-----

      bsCustomFileInput.init();
      //-----------------------


      function select_name_list(position_arr, element){
        $(element).html('');
        var data = {
          'position_arr' : position_arr,
        }
        $.ajax({
          'data'      : data,
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'select_accessory_name_list',
          success     : function(data){
            if ($.trim(data)){
              var html = '';
                html+='<option value="">';
                html+='--';
                html+='</option>';
              for (var i = 0; i < data.length; i++) {
                html+='<option value="'+data[i]['id']+'">';
                html+=data[i]['name'];
                html+='</option>';
              }
              $(element).html( html );
            }
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
      select_name_list('4', $('#txt_add_accessory_counted_by'));
      select_name_list('4', $('#txt_add_accessory_checked_by_vo'));
      select_name_list('5', $('#txt_add_accessory_checked_by_qi'));



      dt_recent      = $('#tbl_recent').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_datatable_accessory_recent_po",
            data: function (param){
            }
          },
          bAutoWidth: false,
          "columns":[
            // { "data" : "action", orderable:false, searchable:false, width: '20px' },
            // { "data" : "status", orderable:false, searchable:false },
            { "data" : "raw_data.action" , orderable:false, searchable:false, "width":"30px" },
            { "data" : "po_no" },
            { "data" : "device_name" },
            { "data" : "raw_data.details" },
            // { "data" : "received_dt" },
            // { "data" : "received_by" },
          ],
          // order: [[2, "asc"]],
          info: false,
          "rowCallback": function(row,data,index ){
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable

      $(document).on('click','#tbl_recent tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });
      //----------
      dt_accessories      = $('#tbl_accessories').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_datatable_accessory",
            data: function (param){
                param.td_id          = $('#mdl_to_print').attr('data-id');
            }
          },
          bAutoWidth: false,
          "columns":[
            { "data" : "raw_data.action", orderable:false, searchable:false, "width":"50px" },
            { "data" : "item_desc" },
            { "data" : "qty" },
            { "data" : "usage_qty" },
            { "data" : "raw_data.raw_counted_by" },
            { "data" : "counted_at" },
            { "data" : "raw_data.details" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          "rowCallback": function(row,data,index ){
            if( $(row).html().toLowerCase().indexOf('is_deleted')>0 ){
              $(row).addClass('table-danger');
            }
          },
      });//end of DataTable
      $(document).on('click','#tbl_accessories tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });
      //-----------------------
      //-----------------------
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
                case 'save_batch_passed':
                  save_batch_passed();
                break;
                //---
                case 'save_sub_kitting':
                  save_sub_kitting();
                break;

                default:
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
                case 'fn_scan_po_number':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_search_po_number').val(val);
                  search_po_number();
                break;

                default:
                break;
              }
            }            
          }//key
        }
      });

















//new
      $('#btn_create').click(function(){
        select_record('');
      });

      $(document).on('click','#tbl_recent .td_btn_open_details',function(e){
        var data_id          = $(this).closest('tr').find('.td_id').val();
        select_record(data_id);
      });

      function select_record(id){
        //clear
        $('#mdl_to_print').attr('data-id', 0 );

        $('#mdl_to_print input').val('');
        $('#mdl_to_print select').val('');
        $('#mdl_to_print textarea').val('');

        $('#txt_print_po_remarks').attr('readonly',false);
        $('#btn_print_add').show();
        $('#row_search_po').show();
        $('#btn_add_accessory').attr('disabled',true);

        $('#ckb_print_accessory_tag').attr('disabled',true);
        $('#btn_print').attr('disabled',true);
        $('#btn_history').attr('disabled',true);
        $('#btn_delete_po').attr('disabled',true);
        dt_accessories.ajax.reload();

        if(id!=''){
          var data = {
            'td_id'         : id,
          };
          $.ajax({
            'data'      : data,
            'type'      : 'get',
            'dataType'  : 'json',
            'url'       : 'select_accessory_details',
            success : function(data){
              if($.trim(data)){
                $('#txt_print_po_remarks').attr('readonly',true);
                $('#btn_print_add').hide();
                $('#row_search_po').hide();

                $('#btn_history').attr('disabled',false);
                if(!data[0]['deleted_at']){
                  $('#ckb_print_accessory_tag').attr('disabled',false);
                  $('#btn_add_accessory').attr('disabled', false);
                  $('#btn_print').attr('disabled',false);
                  $('#btn_delete_po').attr('disabled',false);
                }

                $('#mdl_to_print').attr('data-id', data[0]['id'] );

                $('#txt_print_po_no').val(data[0]['po_no']);
                $('#txt_print_device_name').val(data[0]['device_name']);
                $('#txt_print_po_remarks').val( data[0]['remarks'] );

                dt_accessories.ajax.reload();
              }
            }
          });
        }
        $('#mdl_to_print').modal('show');
      }

      $(document).on('click','#btn_scan_po',function(e){
        $('#txt_search_po_number').val('');
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_po_number').modal('show');
      });

      function search_po_number(){
        $('#txt_print_po_no').val('');
        var data = {
          'po_number' : $('#txt_search_po_number').val(),
        }
        $.ajax({
          'data'      : data,
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'select_po_details',
          success     : function(data){
            if ($.trim(data)){
              $('#txt_print_po_no').val( data[0]['po'] );
              $('#txt_print_device_name').val( data[0]['kit_issuance']['device_name'] );
            }
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      }

      $(document).on('click','#btn_print_add',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_sticker_details').modal('show');
      });

      $('#frm_sticker_details').submit(function(e){
        e.preventDefault();
        //---
        var alert_msg = '';
        if( !$('#txt_print_po_no').val() ){
          alert_msg = 'Please select PO first.';
        }
        if(alert_msg){
          show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
          return;
        }
        //---
        var data = {
          'data_id'                       : $('#mdl_to_print').attr('data-id'),
          '_token'                        : '{{csrf_token()}}',
          'txt_employee_number_scanner'   : $('#txt_employee_number_scanner').val(),
        };
        data = $.param(data) + "&" + $(this).serialize();
        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_accessory_tag',
          success : function(data){
            if($.trim(data)){
              show_alert(data['icon'],data['title'],data['body'],data['i']);
              if( data['i'] == 2 ){
                return;
              }
              //---
              //   select_recent_table();
              data_id = data['last_id'],
              select_record(data_id);
              dt_recent.ajax.reload();
            }
          }
        });
        //---
      });

      //-----------------------------
      //-----------------------------
      //-----------------------------


      $('#btn_add_accessory').click(function(){
        $('#mdl_add_accessory input').val('');
        $('#mdl_add_accessory select').val('');
        $('#mdl_add_accessory textarea').val('');

        $('#mdl_add_accessory').modal('show');
      });


      $('#txt_add_accessory_search_item').on('keyup',function(){
        $('#txt_add_accessory_search_item_datalist').html('');

        var data = {
          'device_name' : $('#txt_print_device_name').val(),
          'item_desc'   : $(this).val(),
        }
        $.ajax({
          'data'      : data,
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'select_accessory_wbs_item_list',
          success     : function(data){
            if ($.trim(data)){
              var html = '';
                html+='<option value="">';
                html+='--';
                html+='</option>';
              for (var i = 0; i < data.length; i++) {
                html+='<option value="'+data[i]['item_desc']+'">';
                html+=data[i]['item_desc'];
                html+='</option>';
              }
              $('#txt_add_accessory_search_item_datalist').html( html );
            }
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });

      $('#txt_add_accessory_search_item').on('change',function(){//add validation novs
        $('#txt_add_accessory_item_name').val('');
        $('#txt_add_accessory_item_name').val( $(this).val() );
      });


      $(document).on('click','#btn_save_accessory',function(e){
        var alert_msg = '';

        $('#frm_add_accessory').find('input,select').each(function(){
            if($(this).prop('required')){
              if( !$(this).val() ){
                alert_msg = 'Please fill all the required fields.';
              }
            }
        });
        if( !$('#txt_add_accessory_item_name').val() ){
          alert_msg = 'Please select Item first.';
        }
        if(alert_msg){
          show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
          return;
        }
        //---

        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_add_accessory').modal('show');
      });

      $('#frm_add_accessory').submit(function(e){
        e.preventDefault();
        var data = {
          'data_id'                       : $('#mdl_to_print').attr('data-id'),
          '_token'                        : '{{csrf_token()}}',
          'txt_employee_number_scanner'   : $('#txt_employee_number_scanner').val(),
        };
        data = $.param(data) + "&" + $(this).serialize();
        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_accessory_tag_item',
          success : function(data){
            if($.trim(data)){
              show_alert(data['icon'],data['title'],data['body'],data['i']);
              if( data['i'] == 2 ){
                return;
              }
              //---
              dt_accessories.ajax.reload();
              $('#mdl_add_accessory').modal('hide');
            }
          }
        });
        //---
      });



      //-----------------------------
      //-----------------------------
      //-----------------------------

      $('#tbl_accessories thead').on('change','.td_checkall',function(){
        var cval = $(this).prop('checked');
        $(this).closest('table').find('input[type="checkbox"]').not('[disabled]').prop('checked',cval);
      });




//-new


















      $('#btn_print').click(function(){
        $('#btn_print_proceed_temp').click();
        // $("#mdl_print_temp").modal('show');
        

      });





      $('#btn_print_proceed_temp').click(function(){
        popup = window.open();
        let content = '';
        content = $('#txt_sticker_details').val();
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
      });

































      $(document).on('click','.btn_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['wbs_table']       = 1;
        fn_select_material_details(data_arr);
      });
      $(document).on('click','.btn_sakidashi_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['wbs_table']       = 2;
        fn_select_material_details(data_arr);
      });
      $(document).on('click','.btn_warehouse_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['wbs_table']       = 3;
        fn_select_material_details(data_arr);
      });
      $(document).on('click','#btn_edit_material_details_primary',function(e){
        readonly_material_details_primary(false);
      });
      $(document).on('click','#btn_cancel_material_details_primary',function(e){
        readonly_material_details_primary(true);
      });
      $(document).on('click','#btn_edit_material_details_for_parts_prep_member',function(e){
        readonly_material_details_for_parts_prep(false);
      });
      $(document).on('click','#btn_cancel_material_details_primary_parts_prep',function(e){
        readonly_material_details_for_parts_prep(true);
      });
      $(document).on('click','#btn_edit_material_details_secondary',function(e){
        readonly_material_details_secondary(false);
      });
      $(document).on('click','#btn_cancel_material_details_secondary',function(e){
        readonly_material_details_secondary(true);
      });
      //-----------------------
      $(document).on('click','.btn_batch_passed',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_batch_passed').modal('show');
      });
      $(document).on('click','.btn_material_pass_details',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_lot_passed').modal('show');
      });
      //---
      $(document).on('click','.btn_material_fail_details',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_lot_failed').modal('show');
      });
      //---
      $(document).on('click','.btn_sakidashi_material_pass_details',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_sakidashi_lot_passed').modal('show');
      });
      $(document).on('click','.btn_sakidashi_material_fail_details',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_sakidashi_lot_failed').modal('show');
      });
      //---
      $(document).on('click','.btn_warehouse_material_pass_details',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_warehouse_lot_passed').modal('show');
      });
      $(document).on('click','.btn_warehouse_material_fail_details',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_warehouse_lot_failed').modal('show');
      });
      //---

      // $(document).on('click','#mdl_yesno #btn_yesno_yes',function(e){
      //   var formid = $("#mdl_yesno").attr('data-formid');
      //   $('#txt_employee_number_scanner').val('');
      //   $('#mdl_employee_number_scanner').attr('data-formid',formid).attr('data-yesno',1).modal('show');
      //   $('#mdl_yesno').attr('data-formid','').modal('hide');
      // });

      // $(document).on('click','#mdl_yesno #btn_yesno_no',function(e){
      //   var formid = $("#mdl_yesno").attr('data-formid');
      //   $('#txt_employee_number_scanner').val('');
      //   $('#mdl_employee_number_scanner').attr('data-formid',formid).attr('data-yesno',0).modal('show');
      //   $('#mdl_yesno').attr('data-formid','').modal('hide');
      // });
      //-----------------------
      $(document).on('click','#btn_scan_transfer_slip',function(e){
        $('#txt_scan_transfer_slip').val('');
        dt_batches.ajax.reload();
        dt_lot_numbers.ajax.reload();
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_transfer_slip').modal('show');
      });
      $(document).on('click','#btn_scan_sakidashi_lot',function(e){
        $('#txt_scan_sakidashi_lot').val('');
        dt_sakidashi.ajax.reload();
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_sakidashi_lot').modal('show');
      });
      //-----------------------
      $(document).on('click','#btn_save_material_details_primary',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details').modal('show');
      });
      $(document).on('click','#btn_save_sub_kitting',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_sub_kitting').modal('show');
      });
      //-----------------------
      dt_subkitting      = $('#tbl_subkitting').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "fn_view_subkitting",
            data: function (param){
                param.col_material_id             = $("#tbl_lot_numbers tbody tr.table-active").find('.col_material_id').val();
            }
          },
          
          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status" },
            { "data" : "sub_kit_desc" },
            { "data" : "sub_kit_qty" },
            { "data" : "received_dt" },
            { "data" : "received_by" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
          "rowCallback": function(row,data,index ){
            if( $(row).html().toLowerCase().indexOf('received')>0 ){
              $(row).addClass('table-success');
            }
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable
      //-----------------------
      dt_parts_prep_stations = $('#tbl_parts_prep_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_partspreparatory_stations",
            data: function (param){
                param.parts_prep_id_query          = $("#txt_parts_preps_id_query").val();
                param.enable_edit                  = false;
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "step_num" },
            { "data" : "station.name" },
            { "data" : "sub_station.name" },
            { "data" : "print_code" },
            { "data" : "raw_machine" },
            { "data" : "created_at" },
            { "data" : "raw_created_by" },
            { "data" : "qty_input" },
            { "data" : "qty_output" },
            { "data" : "qty_ng" },
            { "data" : "mod" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
          "rowCallback": function(row,data,index ){
          },
          "drawCallback": function(row,data,index ){
            if( dt_parts_prep_stations.rows().count() > 0 ){
              $('#btn_setup_stations').prop('disabled',true);
            }
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
                case 'save_batch_passed':
                  save_batch_passed();
                break;
                case 'save_lot_passed':
                  save_lot_passed();
                break;
                case 'save_lot_failed':
                  save_lot_failed();
                break;
                //---
                case 'save_sakidashi_lot_passed':
                  save_sakidashi_lot_passed();
                break;
                case 'save_sakidashi_lot_failed':
                  save_sakidashi_lot_failed();
                break;
                //---
                case 'save_warehouse_lot_passed':
                  save_warehouse_lot_passed();
                break;
                case 'save_warehouse_lot_failed':
                  save_warehouse_lot_failed();
                break;
                //---
                case 'save_sub_kitting':
                  save_sub_kitting();
                break;

                default:
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
                case 'fn_scan_po_number':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_search_po_number').val(val);
                  search_po_number( $('#txt_search_po_number') );
                break;

                case 'fn_scan_transfer_slip':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_scan_transfer_slip').val(val);
                  dt_batches.ajax.reload();
                break;

                case 'fn_scan_sakidashi_lot':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_scan_sakidashi_lot').val(val);
                  dt_sakidashi.ajax.reload();
                break;

                default:
                break;
              }
            }            
          }//key
        }
      });
      //-----------------------
      $('#frm_edit_material_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize()+'&txt_parts_preps_id_query='+$("#txt_parts_preps_id_query").val()+'&txt_wbs_kit_issuance_id_query='+$("#txt_wbs_kit_issuance_id_query").val()+'&txt_runcard_number='+$("#txt_runcard_number").val()+'&txt_employee_number_scanner='+$("#txt_employee_number_scanner").val()+'&_token={{ csrf_token() }}',
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_material_details',
          success     : function(data){
            // toastr.success('Material Process was succesfully saved!');
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            readonly_material_details_primary(true);
            //---
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['wbs_table']       = $('#txt_wbs_table').val();
            fn_select_material_details(data_arr);
            //---
            dt_lot_numbers.ajax.reload();
            dt_sakidashi.ajax.reload();
            // dt_warehouse.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });
      //-----------------------
      $(document).on('click','.btn_material_subkitting_details',function(){
        $('#mdl_subkitting').modal('show');
      });

      // $(document).on('click','.btn_material_pass_details',function(e){
      //   dt_subkitting.ajax.reload();
      //   $('#mdl_setup_stations').modal('show');
      // });

      $(document).on('click','#tbl_lot_numbers_filter input[type="search"]',function(){
        $(this).val('');
      });
      $(document).on('click','#tbl_sakidashi_filter input[type="search"]',function(){
        $(this).val('');
      });
      $(document).on('click','#btn_layout_1_maximize',function(){
        $('#div_layout_1').removeClass('col-md-11 col-md-8 col-md-4 col-md-1').addClass('col-md-11');
        $('#div_layout_2').removeClass('col-md-11 col-md-8 col-md-4 col-md-1').addClass('col-md-1');
        // if( $('#div_layout_1').hasClass('col-md-8') ){
        //   $('#div_layout_1').removeClass('col-md-8').addClass('col-md-11');
        //   $('#div_layout_2').removeClass('col-md-4').addClass('col-md-1');
        // }
        // else if( $('#div_layout_1').hasClass('col-md-11') ){
        //   $('#div_layout_1').removeClass('col-md-11').addClass('col-md-4');
        //   $('#div_layout_2').removeClass('col-md-1').addClass('col-md-8');
        // }
        // else{// if( $('#div_layout_1').hasClass('col-md-4') )
        //   $('#div_layout_1').removeClass('col-md-4').addClass('col-md-8');
        //   $('#div_layout_2').removeClass('col-md-8').addClass('col-md-4');
        // }
      });
      $(document).on('click','#btn_layout_2_maximize',function(){
        $('#div_layout_1').removeClass('col-md-11 col-md-8 col-md-4 col-md-1').addClass('col-md-1');
        $('#div_layout_2').removeClass('col-md-11 col-md-8 col-md-4 col-md-1').addClass('col-md-11');
      });


      $(document).on('change','#txt_setup_stations_checkall',function(){
        var cval = $(this).prop('checked');
        $('#tbl_subkitting tbody input[type="checkbox"]').not('[disabled]').prop('checked',cval);
      });


      //-----------------------
      //-----------------------
      //-----------------------
    });//doc
    //-----------------------
    //-----------------------
    //-----------------------

    function save_batch_passed(){
      var data = 'tbl_wbs_material_kitting_id=' + $("#tbl_batches tbody tr.table-active").find('.tbl_wbs_material_kitting_id').val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

      $.ajax({
        'data'      : data,
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'insert_materialissuance',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          dt_batches.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    //-----------------------
    function save_lot_passed(){
      var data = 'col_material_id=' + $("#tbl_lot_numbers tbody tr.table-active").find('.col_material_id').val()+'&col_material_po=' + $("#tbl_lot_numbers tbody tr.table-active").find('.col_material_po').val()+'&col_parts_preps_id=' + $("#tbl_lot_numbers tbody tr.table-active").find('.col_parts_preps_id').val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}'+'&status=1'+'&with_partsprep='+$("#tbl_lot_numbers tbody tr.table-active").find('.col_with_partsprep').val();
      $.ajax({
        'data'      : data,
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'insert_lot_pass_fail',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          dt_lot_numbers.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    //---
    function save_lot_failed(){
      var data = 'col_material_id=' + $("#tbl_lot_numbers tbody tr.table-active").find('.col_material_id').val()+'&col_material_po=' + $("#tbl_lot_numbers tbody tr.table-active").find('.col_material_po').val()+'&col_parts_preps_id=' + $("#tbl_lot_numbers tbody tr.table-active").find('.col_parts_preps_id').val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}'+'&status=2';
      $.ajax({
        'data'      : data,
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'insert_lot_pass_fail',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          dt_lot_numbers.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    //-----------------------
    function save_sakidashi_lot_passed(){
      var data = 'col_material_id=' + $("#tbl_sakidashi tbody tr.table-active").find('.col_material_id').val()+'&col_material_po=' + $("#tbl_sakidashi tbody tr.table-active").find('.col_material_po').val()+'&col_parts_preps_id=' + $("#tbl_sakidashi tbody tr.table-active").find('.col_parts_preps_id').val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}'+'&status=1'+'&with_partsprep='+$("#tbl_sakidashi tbody tr.table-active").find('.col_with_partsprep').val();
      $.ajax({
        'data'      : data,
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'insert_sakidashi_lot_pass_fail',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          dt_sakidashi.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    //---
    function save_sakidashi_lot_failed(){
      var data = 'col_material_id=' + $("#tbl_sakidashi tbody tr.table-active").find('.col_material_id').val()+'&col_material_po=' + $("#tbl_sakidashi tbody tr.table-active").find('.col_material_po').val()+'&col_parts_preps_id=' + $("#tbl_sakidashi tbody tr.table-active").find('.col_parts_preps_id').val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}'+'&status=2';
      $.ajax({
        'data'      : data,
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'insert_sakidashi_lot_pass_fail',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          dt_sakidashi.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    //-----------------------
    // function save_warehouse_lot_passed(){
    //   var data = 'col_material_id=' + $("#tbl_warehouse tbody tr.table-active").find('.col_material_id').val()+'&col_material_po=' + $("#tbl_warehouse tbody tr.table-active").find('.col_material_po').val()+'&col_parts_preps_id=' + $("#tbl_warehouse tbody tr.table-active").find('.col_parts_preps_id').val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}'+'&status=1'+'&with_partsprep='+$("#tbl_warehouse tbody tr.table-active").find('.col_with_partsprep').val();
    //   $.ajax({
    //     'data'      : data,
    //     'type'      : 'post',
    //     'dataType'  : 'json',
    //     'url'       : 'insert_warehouse_lot_pass_fail',
    //     success     : function(data){
    //       show_alert(data['icon'],data['title'],data['body'],data['i']);
    //       if( data['i'] == 2 ){
    //         return;
    //       }
    //       dt_warehouse.ajax.reload();
    //     },
    //     completed     : function(data){
    //     },
    //     error     : function(data){
    //     },
    //   });
    // }
    //---
    // function save_warehouse_lot_failed(){
    //   var data = 'col_material_id=' + $("#tbl_warehouse tbody tr.table-active").find('.col_material_id').val()+'&col_material_po=' + $("#tbl_warehouse tbody tr.table-active").find('.col_material_po').val()+'&col_parts_preps_id=' + $("#tbl_warehouse tbody tr.table-active").find('.col_parts_preps_id').val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}'+'&status=2';
    //   $.ajax({
    //     'data'      : data,
    //     'type'      : 'post',
    //     'dataType'  : 'json',
    //     'url'       : 'insert_warehouse_lot_pass_fail',
    //     success     : function(data){
    //       show_alert(data['icon'],data['title'],data['body'],data['i']);
    //       if( data['i'] == 2 ){
    //         return;
    //       }
    //       dt_warehouse.ajax.reload();
    //     },
    //     completed     : function(data){
    //     },
    //     error     : function(data){
    //     },
    //   });
    // }
    //-----------------------
    function reset_material_details_primary(){
      $('#txt_material_name').val('');
      $('#txt_use_for_device').val('');
      $('#txt_po_number_label').val('');
      $('#txt_assessment_number').val('');
      $('#txt_assessed_qty').val('');
      $('#txt_special_instruction').val('');
      $('#txt_material_remarks').val('');

      readonly_material_details_primary(true);
    }
    function readonly_material_details_primary(v){
      $('#txt_assessment_number').prop('readonly',v);
      $('#txt_special_instruction').prop('readonly',v);
      $('#txt_material_remarks').prop('readonly',v);

      $('#btn_save_material_details_primary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_primary').closest('.row').show();
      }
    }
    //---
    function reset_material_details_for_parts_prep(){
      $('#txt_lot_number').val('');
      $('#txt_drawing_number').val('');
      $('#txt_drawing_number_revision_number').val('');
      $('#txt_sgc_docs_number').val('');
      $('#txt_sgc_docs_number_revision_number').val('');
      $('#txt_other_docs_number').val('');
      $('#txt_parts_prep_remarks').val('');

      readonly_material_details_for_parts_prep(true);
    }
    function readonly_material_details_for_parts_prep(v){
      $('#txt_drawing_number_revision_number').prop('readonly',v);
      $('#txt_sgc_docs_number').prop('readonly',v);
      $('#txt_sgc_docs_number_revision_number').prop('readonly',v);
      $('#txt_other_docs_number').prop('readonly',v);
      $('#txt_parts_prep_remarks').prop('readonly',v);

      $('#btn_save_material_details_primary_parts_prep').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_primary_parts_prep').closest('.row').show();
      }
    }
    //---
    function reset_material_details_secondary(){
      $('#txt_discrepant_qty_sign').val('0');
      $('#txt_discrepant_qty').val('');
      $('#txt_analysis').val('');
      $('#txt_recount_ok').val('');
      $('#txt_recount_ng').val('');
      $('#txt_other_runcard_number').val('');

      readonly_material_details_secondary(true);
    }
    function readonly_material_details_secondary(v){
      $('#txt_discrepant_qty_sign').prop('disabled',v);
      $('#txt_discrepant_qty').prop('readonly',v);
      $('#txt_analysis').prop('readonly',v);
      $('#txt_recount_ok').prop('readonly',v);
      $('#txt_recount_ng').prop('readonly',v);
      $('#txt_other_runcard_number').prop('readonly',v);

      $('#btn_save_material_details_secondary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_secondary').closest('.row').show();
      }
    }
    //---
    function fn_select_material_details(data_arr){
      $('#btn_edit_material_details_primary').prop('hidden',true);//user access
      $('#btn_setup_stations').prop('hidden',true);//user access


      $('#btn_edit_material_details_primary').prop('disabled',true);
      $('#txt_wbs_table').val( data_arr['wbs_table'] );
      $('#txt_parts_preps_id_query').val('');
      $('#txt_wbs_kit_issuance_id_query').val('');
      $('#txt_wbs_kit_issuance_device_code_query').val('');
      $('#txt_wbs_kit_issuance_item_query').val('');
      $('#txt_wbs_kit_issuance_item_desc_query').val('');

      $('#txt_runcard_number').val('');

      $('#btn_edit_material_details_for_parts_prep_member').prop('disabled',true);
      $('#btn_edit_material_details_secondary').prop('disabled',true);
      $('#btn_setup_stations').prop('disabled',true);

      $('#txt_checked_by_prod').val('');
      $('#btn_approve_prod').prop('disabled',true);

      reset_material_details_primary();
      reset_material_details_for_parts_prep();
      reset_material_details_secondary();

      var data = {
        'material_id'         : data_arr['material_id'],
        'wbs_table'           : data_arr['wbs_table'],
        'device_name_query'   : $('#txt_device_name').val(),//for acdcs drawing only
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_partspreparatory_material_details',
        success     : function(data){
          if ($.trim(data)){
            if( data_arr['wbs_table'] == 1 ){//kit
              if( $.trim(data[0]['parts_preps']) ){
                if( data[0]['parts_preps'][0]['with_partsprep'] == 1 ){
                  if( data[0]['user_position'] == 3 ){//material handler 
                    $('#btn_edit_material_details_primary').prop('hidden',false);
                    $('#btn_setup_stations').prop('hidden',false);
                    if( data[0]['parts_preps'][0]['status'] == 1 ){
                      $('#btn_edit_material_details_primary').prop('disabled',false);
                    }
                    if( data[0]['parts_preps'][0]['status'] < 5 ){
                      $('#btn_setup_stations').prop('disabled',false);
                    }
                  }
                }
                $('#txt_parts_preps_id_query').val(data[0]['parts_preps'][0]['id']);

                var runcard_number  = data[0]['parts_preps'][0]['runcard_number'];
                var runcard_number = "" + runcard_number;
                var pad = "000";
                runcard_number      = pad.substring(runcard_number.length) + runcard_number;
                $('#txt_runcard_number').val( data[0]['parts_preps'][0]['runcard_po'] + '-' +runcard_number );

                $('#txt_assessment_number').val(data[0]['parts_preps'][0]['assess_num']);
                $('#txt_special_instruction').val(data[0]['parts_preps'][0]['special_instruction']);
                $('#txt_material_remarks').val(data[0]['parts_preps'][0]['remarks']);
                //---
                $('#txt_other_docs_number').val(data[0]['parts_preps'][0]['other_docs_num']);
                $('#txt_parts_prep_remarks').val(data[0]['parts_preps'][0]['parts_prep_remarks']);
                //---
                $('#txt_discrepant_qty_sign').val('+');
                if( data[0]['parts_preps'][0]['discrepant_qty'] < 0 ){
                  $('#txt_discrepant_qty_sign').val('-');
                }
                $('#txt_discrepant_qty').val( Math.abs( data[0]['parts_preps'][0]['discrepant_qty']?data[0]['parts_preps'][0]['discrepant_qty']:'+' ) );
                $('#txt_analysis').val(data[0]['parts_preps'][0]['analysis']);
                $('#txt_recount_ok').val(data[0]['parts_preps'][0]['recount_ok']);
                $('#txt_recount_ng').val(data[0]['parts_preps'][0]['recount_ng']);
                $('#txt_other_runcard_number').val(data[0]['parts_preps'][0]['other_runcard_number']);

                $('#txt_checked_by_prod').val(data[0]['parts_preps'][0]['raw_checked_by_prod']);
              }
              $('#txt_wbs_kit_issuance_id_query').val(data[0]['id']);
              $('#txt_wbs_kit_issuance_device_code_query').val(data[0]['kit_issuance']['device_code']);
              $('#txt_wbs_kit_issuance_item_query').val(data[0]['item']);
              $('#txt_wbs_kit_issuance_item_desc_query').val(data[0]['item_desc']);
              $('#txt_material_name').val(data[0]['item_desc']);
              $('#txt_lot_number').val(data[0]['lot_no']);
              $('#txt_po_number_label').val(data[0]['po']);
              $('#txt_use_for_device').val(data[0]['kit_issuance']['device_name']);
              $('#txt_assessed_qty').val(data[0]['kit_qty']);
              $('#txt_drawing_number').val(data[0]['drawing_no']);
              $('#txt_drawing_number_revision_number').val(data[0]['drawing_no_revision_no']);
              $('#txt_sgc_docs_number').val(data[0]['sgc_drawing_no']);
              $('#txt_sgc_docs_number_revision_number').val(data[0]['sgc_drawing_no_revision_no']);
            }
            else if( data_arr['wbs_table'] == 2 ){//sakidashi
              if( $.trim(data[0]['parts_preps']) ){
                if( data[0]['user_position'] == 3 ){//material handler 
                  $('#btn_edit_material_details_primary').prop('hidden',false);//user access
                  $('#btn_setup_stations').prop('hidden',false);//user access
                }
                if( data[0]['parts_preps'][0]['status'] == 1 ){
                  $('#btn_edit_material_details_primary').prop('disabled',false);
                }
                if( data[0]['parts_preps'][0]['status'] < 5 ){
                  $('#btn_setup_stations').prop('disabled',false);
                }
                $('#txt_parts_preps_id_query').val(data[0]['parts_preps'][0]['id']);

                var runcard_number  = data[0]['parts_preps'][0]['runcard_number'];
                var runcard_number = "" + runcard_number;
                var pad = "000";
                runcard_number      = pad.substring(runcard_number.length) + runcard_number;
                $('#txt_runcard_number').val( data[0]['parts_preps'][0]['runcard_po'] + '-' +runcard_number );

                $('#txt_assessment_number').val(data[0]['parts_preps'][0]['assess_num']);
                $('#txt_special_instruction').val(data[0]['parts_preps'][0]['special_instruction']);
                $('#txt_material_remarks').val(data[0]['parts_preps'][0]['remarks']);
                //---
                $('#txt_other_docs_number').val(data[0]['parts_preps'][0]['other_docs_num']);
                $('#txt_parts_prep_remarks').val(data[0]['parts_preps'][0]['parts_prep_remarks']);
                //---
                $('#txt_discrepant_qty_sign').val('+');
                if( data[0]['parts_preps'][0]['discrepant_qty'] < 0 ){
                  $('#txt_discrepant_qty_sign').val('-');
                }
                $('#txt_discrepant_qty').val( Math.abs( data[0]['parts_preps'][0]['discrepant_qty']?data[0]['parts_preps'][0]['discrepant_qty']:'+' ) );
                $('#txt_analysis').val(data[0]['parts_preps'][0]['analysis']);
                $('#txt_recount_ok').val(data[0]['parts_preps'][0]['recount_ok']);
                $('#txt_recount_ng').val(data[0]['parts_preps'][0]['recount_ng']);
                $('#txt_other_runcard_number').val(data[0]['parts_preps'][0]['other_runcard_number']);

                $('#txt_checked_by_prod').val(data[0]['parts_preps'][0]['raw_checked_by_prod']);
              }
              $('#txt_wbs_kit_issuance_id_query').val(data[0]['id']);
              $('#txt_wbs_kit_issuance_device_code_query').val(data[0]['device_code']);
              $('#txt_wbs_kit_issuance_item_query').val(data[0]['tbl_wbs_sakidashi_issuance_item']['item']);
              $('#txt_wbs_kit_issuance_item_desc_query').val(data[0]['tbl_wbs_sakidashi_issuance_item']['item_desc']);
              $('#txt_material_name').val(data[0]['tbl_wbs_sakidashi_issuance_item']['item_desc']);
              $('#txt_lot_number').val(data[0]['tbl_wbs_sakidashi_issuance_item']['lot_no']);
              $('#txt_po_number_label').val(data[0]['po_no']);
              $('#txt_use_for_device').val(data[0]['device_name']);
              $('#txt_assessed_qty').val(data[0]['tbl_wbs_sakidashi_issuance_item']['issued_qty']);
              $('#txt_drawing_number').val(data[0]['drawing_no']);
              $('#txt_drawing_number_revision_number').val(data[0]['drawing_no_revision_no']);
              $('#txt_sgc_docs_number').val(data[0]['sgc_drawing_no']);
              $('#txt_sgc_docs_number_revision_number').val(data[0]['sgc_drawing_no_revision_no']);
            }
            else if( data_arr['wbs_table'] == 3 ){//warehouse
              if( $.trim(data[0]['parts_preps']) ){
                if( data[0]['user_position'] == 3 ){//material handler 
                  $('#btn_edit_material_details_primary').prop('hidden',false);//user access
                  $('#btn_setup_stations').prop('hidden',false);//user access
                }
                if( data[0]['parts_preps'][0]['status'] == 1 ){
                  $('#btn_edit_material_details_primary').prop('disabled',false);
                }
                if( data[0]['parts_preps'][0]['status'] < 5 ){
                  $('#btn_setup_stations').prop('disabled',false);
                }
                $('#txt_parts_preps_id_query').val(data[0]['parts_preps'][0]['id']);

                var runcard_number  = data[0]['parts_preps'][0]['runcard_number'];
                var runcard_number = "" + runcard_number;
                var pad = "000";
                runcard_number      = pad.substring(runcard_number.length) + runcard_number;
                $('#txt_runcard_number').val( data[0]['parts_preps'][0]['runcard_po'] + '-' +runcard_number );

                $('#txt_assessment_number').val(data[0]['parts_preps'][0]['assess_num']);
                $('#txt_special_instruction').val(data[0]['parts_preps'][0]['special_instruction']);
                $('#txt_material_remarks').val(data[0]['parts_preps'][0]['remarks']);
                //---
                $('#txt_other_docs_number').val(data[0]['parts_preps'][0]['other_docs_num']);
                $('#txt_parts_prep_remarks').val(data[0]['parts_preps'][0]['parts_prep_remarks']);
                //---
                $('#txt_discrepant_qty_sign').val('+');
                if( data[0]['parts_preps'][0]['discrepant_qty'] < 0 ){
                  $('#txt_discrepant_qty_sign').val('-');
                }
                $('#txt_discrepant_qty').val( Math.abs( data[0]['parts_preps'][0]['discrepant_qty']?data[0]['parts_preps'][0]['discrepant_qty']:'+' ) );
                $('#txt_analysis').val(data[0]['parts_preps'][0]['analysis']);
                $('#txt_recount_ok').val(data[0]['parts_preps'][0]['recount_ok']);
                $('#txt_recount_ng').val(data[0]['parts_preps'][0]['recount_ng']);
                $('#txt_other_runcard_number').val(data[0]['parts_preps'][0]['other_runcard_number']);

                $('#txt_checked_by_prod').val(data[0]['parts_preps'][0]['raw_checked_by_prod']);
              }
              $('#txt_wbs_kit_issuance_id_query').val(data[0]['id']);
              $('#txt_wbs_kit_issuance_device_code_query').val( $('#txt_device_name').attr('data-device_code') );//?
              $('#txt_wbs_kit_issuance_item_query').val(data[0]['item']);
              $('#txt_wbs_kit_issuance_item_desc_query').val(data[0]['item_desc']);
              $('#txt_material_name').val(data[0]['item_desc']);
              $('#txt_lot_number').val(data[0]['lot_no']);
              $('#txt_po_number_label').val(data[0]['tbl_request_summary']['pono']);
              $('#txt_use_for_device').val( $('#txt_device_name').val() );//?
              $('#txt_assessed_qty').val(data[0]['tbl_request_detail']['issuedqty']);
              $('#txt_drawing_number').val(data[0]['drawing_no']);//?
              $('#txt_drawing_number_revision_number').val(data[0]['drawing_no_revision_no']);//?
              $('#txt_sgc_docs_number').val(data[0]['sgc_drawing_no']);
              $('#txt_sgc_docs_number_revision_number').val(data[0]['sgc_drawing_no_revision_no']);
            }
          }
          dt_parts_prep_stations.ajax.reload();
          $('#mdl_edit_material_details').modal('show');
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    //-----------------------
    function save_sub_kitting(){
      var arr_subkitting = [];
      var ctr = 0;
      $('#tbl_subkitting tbody .ckb_sub_kitting:checked').each(function(){
        arr_subkitting[ctr] = {
            'sub_kitting_id_arr' : $(this).closest('td').find('.tbl_sub_kitting_id').val(),
          };
        ctr++;
      });
      if( !jQuery.isEmptyObject(arr_subkitting) ){
        console.log(arr_subkitting);

        var data = {
          'txt_employee_number_scanner'   : $("#txt_employee_number_scanner").val(),
          '_token'                        : '{{ csrf_token() }}',
          'arr_subkitting'                : arr_subkitting,
          'status'                        : 1,
        }
        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_partsprep_subkitting',
          success     : function(data){
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            dt_lot_numbers.ajax.reload();
            dt_subkitting.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });

      }
      else{
        show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Nothing to save.',0);
      }
    };
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