@auth
  @php
  // echo Auth::user()->position;
  // exit();
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
{{-- @endauth --}}

{{-- @auth --}}
  @extends($layout)

  @section('title', 'Dashboard')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
              <li class="breadcrumb-item active">Dashboard</li>
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
              @if(Auth::user()->user_level_id == 1 || Auth::user()->user_level_id == 2)
                <div class="card-header">
                  <h3 class="card-title">ADMINISTRATOR MODULES</h3>
                </div>

                <!-- Start Page Content -->
                <div class="card-body">
                  <div class="row">
                    @if((Auth::user()->user_level_id == 1 || 2))
                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3 id="h3TotalNoOfUsers">0</h3>

                            <p>User</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-users"></i>
                          </div>
                          <a href="{{ route('user') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3 id="h3TotalNoOfStations">0</h3>

                            <p>Station / Process</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-object-group"></i>
                          </div>
                          <a href="{{ route('station') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <!-- <h3>0<sup style="font-size: 20px">%</sup></h3> -->
                            <h3 id="h3TotalNoOfMachines">0</h3>
                            <p>Machine</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-hdd"></i>
                          </div>
                          <a href="{{ route('machine') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <!-- ./col -->

                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <!-- <h3>0<sup style="font-size: 20px">%</sup></h3> -->
                            <h3 id="h3TotalNoOfMachines">0</h3>
                            <p>Mode of Defect</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-hdd"></i>
                          </div>
                          <a href="{{ route('mode_of_defect') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <!-- ./col -->

                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3 id="h3AssemblyLines">0</h3>

                            <p>Assembly Lines</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-list"></i>
                          </div>
                          <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <!-- ./col -->

                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3 id="h3MaterialProcess">0</h3>

                            <p>Process Matrix</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-list-ol"></i>
                          </div>
                          <a href="{{ route('materialprocess') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <!-- ./col -->
                    @endif

                   <!--  <div class="col-lg-3 col-6">
                      <div class="small-box bg-success">
                        <div class="inner">
                          <h3 id="h3TotalNoOfProcessTimeLine">0</h3>
                          <p>Process Timeline</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="{{ route('machine') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div> -->
                    <!-- ./col -->

                  </div>
                  <!-- /.row -->
                </div>
                <!-- !-- End Page Content -->
              @endif
              
              <div class="card-header">
                <h3 class="card-title">PRODUCTION MODULES</h3>
              </div>
              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3 id="">0</h3>

                        <p>Material Issuance</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-file-alt"></i>
                      </div>
                      <a href="{{ route('materialissuancealias') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3 id="h3TotalNoOfStations">0</h3>

                        <p>Parts Preparatory</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-clipboard-check"></i>
                      </div>
                      <a href="{{ route('partspreparatoryalias') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <!-- <h3>0<sup style="font-size: 20px">%</sup></h3> -->
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>Production / <i>Runcard</i></p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-cogs"></i>
                      </div>
                      <a href="{{ route('prod_runcard_new') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <!-- ./col -->

                  <!-- nessa -->
                 <!--  <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                      <div class="inner">
                        <h3 id="#">0</h3>

                        <p>Production / <i>OQC Lot Application</i></p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-cogs"></i>
                      </div>
                      <a href="{{ route('oqclotapp') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div> 

                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                      <div class="inner">
                        <h3 id="#">0</h3>

                        <p>OQC</i></p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-microscope"></i>
                      </div>
                      <a href="{{ route('oqclvisualinspection') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3 id="h3TotalNoOfShipOp">0</h3>

                        <p>Production Preliminary Packing Inspection</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-box"></i>
                      </div>
                      <a href="{{route('prod_preliminary_inspection')}}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3 id="h3TotalNoOfShipIns">0</h3>

                        <p>OQC Preliminary Packing Inspection</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-truck"></i>
                      </div>
                      <a href="{{route('oqc_preliminary_inspection')}}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  -->
                </div>
                <!-- /.row -->

                <!-- <center>
                  <iframe width="653" height="490" src="https://www.youtube.com/embed/KWhajPXrhDs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </center> -->
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
      CountUserByStatForDashboard(1);
    });
  </script>
  @endsection
{{-- @endauth --}}