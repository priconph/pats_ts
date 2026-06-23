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

  @section('title', 'Operators Production Hourly Report')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Operators Production Hourly Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Operators Production Hourly Report</li>
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
                  <form method="get" id="frmGenerateOPHR">
                    <div class="row">
                        <div class="col-sm-2">
                          <div class="form-group">
                            
                          <label>Operator</label>
                          <select class="form-control select2 select2bs4 selectUser" name="user_id" id="selGenEmpNo">
                            <option value="" selected="true">Select Here</option>
                          </select>
                          </div>
                          <!-- <div class="input-group">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-primary btnSearchEmpNo" title="Scan Employee No."><i class="fa fa-qrcode"></i></button>
                            </div>
                            <input type="hidden" class="form-control" id="txtSearchEmpID" readonly="" name="user_id">
                            <input type="text" class="form-control" id="txtSearchEmpNo" readonly="" name="employee_id">
                          </div> -->
                        </div>
                        <div class="col-sm-2">
                          <label>Date</label>
                            <input type="date" class="form-control" id="dateSearchDate" name="date">
                        </div>
                        <div class="col-sm-2">
                          <label>Shift</label>
                            <select class="form-control" id="selSearchShift" name="shift">
                              <option value="1" selected="true">Shift A</option>
                              <option value="2">Shift B</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                          <button type="submit" class="btn btn-primary" id="btnGenerateOPHR" style="margin-top: 31px;">Generate</button>
                        </div>

                    </div>
                  </form>
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
                <h3 class="card-title">Operators Production Hourly Report</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div class="table responsive">
                    <table id="tblOPHR" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Time</th>
                          <th>Device</th>
                          <th>PO #</th>
                          <th>Station</th>
                          <th>Output</th>
                        </tr>
                      </thead>
                    </table>
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
  </div>
  <!-- /.content-wrapper -->

  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableOPHRs;
    let arrSelectedOPHRs = [];

    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      GetUserList($(".selectUser"));

      $(document).on('click','#tblOPHR tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      dataTableOPHRs = $("#tblOPHR").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_ophrs",
            data: function (param){
                param.user_id = $("select[name='user_id']", $("#frmGenerateOPHR")).val();
                param.date = $("input[name='date']", $("#frmGenerateOPHR")).val();
                param.shift = $("select[name='shift']", $("#frmGenerateOPHR")).val();
            }
          },
          
          "columns":[
            // { "data" : "checkbox" },
            { "data" : "raw_time" },
            { "data" : "production_runcard_info.wbs_kitting.device_name" },
            { "data" : "production_runcard_info.po_no" },
            { "data" : "sub_station.name" },
            { "data" : "qty_output" },
          ],
          "initComplete": function(settings, json) {
                // $(".chkOPHR").each(function(index){
                //     if(arrSelectedOPHRs.includes($(this).attr('ophr-id'))){
                //         $(this).attr('checked', 'checked');
                //     }
                // });
          },
          "drawCallback": function( settings ) {
                // $(".chkOPHR").each(function(index){
                //     if(arrSelectedOPHRs.includes($(this).attr('ophr-id'))){
                //         $(this).attr('checked', 'checked');
                //     }
                // });
            }
        });//end of dataTableOPHRs


        $("#frmGenerateOPHR").submit(function(e){
          e.preventDefault();
          dataTableOPHRs.draw();
        });
      });
  </script>
  @endsection
@endauth