<!-- @php $layout = 'layouts.super_user_layout'; @endphp -->
@auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 4){
      $layout = 'layouts.fvi_layout';
    }
    else if(Auth::user()->user_level_id == 5){
      $layout = 'layouts.oqc_layout';
    }
    else if(Auth::user()->user_level_id == 6){
      $layout = 'layouts.packing_layout';
    }
    else if(Auth::user()->user_level_id == 7){
      $layout = 'layouts.clerk_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

@section('title', 'Export Report')

@section('content_page')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Export Parts Lot Record</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Export Parts Lot Record</li>
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
              <h3 class="card-title">Export Parts Lot Record</h3>
            </div>

            <!-- Start Page Content -->
            <div class="card-body">

              <div class="row">
                
                <div class="col-sm-5">
                  <label>Series Name:</label>
                   <div class="input-group input-group-sm mb-3 series_select">
                    <select class="form-control form-control-sm" id="id_series_name" name="name_series_name">
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-2">

                  <label>Start Date:</label>
                  <div class="input-group input-group-sm mb-3">
                    <input type="date" class="form-control form-control-sm" id="id_from" name="name_from">
                  </div>

                </div>

                 <div class="col-sm-3">

                  <label>End Date:</label>
                  <div class="input-group input-group-sm mb-3">
                    <input type="date" class="form-control form-control-sm" id="id_to" name="name_to">

                    <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="btn_export_report" title="Click to Export Report"><i class="fa fa-file"></i> Export Record</button>
                    </div>

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
</div>
<!-- /.content-wrapper -->
@endsection

@section('js_content')
<script type="text/javascript">
  $(document).ready(function () {
    bsCustomFileInput.init();

    $('#id_series_name').select2({
      theme: "bootstrap4",
    });

  });

  GetCboDevicesByStat($("#id_series_name"), 1);

$(document).on('click','#btn_export_report',function(e){

  var data = {
    'start_date'  : $('#id_from').val(),
    'end_date'    : $('#id_to').val(),
    'series_name' : $('#id_series_name').val()
  }

  // window.open('export_packing_and_shipment_record?series_name='+ $('#id_series_name').val() + '&start_date=' + $('#id_from').val() + '&end_date=' + $('#id_to').val() , '_blank  ');
  window.open('export_parts_lot_record?series_name='+ $('#id_series_name').val() + '&start_date=' + $('#id_from').val() + '&end_date=' + $('#id_to').val() , '_blank  ');

  });


</script>
@endsection
@endauth