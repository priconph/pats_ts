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
    else if(Auth::user()->position == 13){
      $layout = 'layouts.packing_user_layout';
    }
    else if(Auth::user()->position == 14){
      $layout = 'layouts.traffic_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

@section('title', 'OQC')

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

  <!-- Content Header (Page header) -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  {{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Traffic/QC Inspection</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Traffic/QC Inspection</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}


  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->

            <!-- Start Page Content -->

            <?php 
              // $_SESSION["sample"] = "hahah";
              // echo $_SESSION["sample"];
            ?>

              <iframe style="border: none;" src="http://rapidx/pats_ts_ppts/finalpackinginspection_pts_trffic_qc" no-border height="850" width="1900"></iframe>

              <!-- !-- End Page Content -->

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
  $(document).ready(function(){
    setTimeout(function() {
      $('.nav-link').find('.fa-bars').closest('a').click();
    }, 100);
  });
</script>
@endsection
@endauth