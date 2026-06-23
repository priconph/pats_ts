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
  @endphp
@endauth

@auth
  @extends($layout)

@section('title', 'D Label Printing')

@section('content_page')
<!-- <link href="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" media="all"> -->

<style type="text/css">
	#iframe_d_label_printing{
		position: absolute;
		width: 100%;
		height: 900px;
/*		width: 100%!important;
		height: 100%!important;
*/		border: none;
	}
 </style>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<div class="container-fluid">
 			<div class="row mb-2">
 				<div class="col-sm-6">
 					<h1>WEB EDI Printing</h1>
 				</div>
 				<div class="col-sm-6">
 					<ol class="breadcrumb float-sm-right">
 						<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
 						<li class="breadcrumb-item active">WEB EDI Printing</li>
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
 				<div class="col-12">
 					<!-- general form elements -->
 					<div>
 						<!-- OLD -->
						<!-- <iframe id="iframe_d_label_printing" src="http://rapidx/dlabel_ts_ppts_rev3?en={{Auth::user()->employee_id}}&pos={{Auth::user()->position}}"></iframe> -->
						<!-- UPDATED as of April 3, 2025 -->
						{{-- <iframe id="iframe_d_label_printing" src="http://rapidx/dlabel_pats_ts_ppts?en={{Auth::user()->employee_id}}&pos={{Auth::user()->position}}"></iframe> --}}
						<iframe id="iframe_d_label_printing" src="http://rapidx/dlabel_ts_ppts_rev5?en={{Auth::user()->employee_id}}&pos={{Auth::user()->position}}"></iframe>
 					</div>
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

<!-- <script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script> -->
<!-- <script src="{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/js/deployJava.js') }}"></script> -->
<!-- <script type="text/javascript" src="dist/qz-print-free_1.8.0_src/qz-print/js/deployJava.js"></script>
<script type="text/javascript" src="dist/jquery/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="dist/jquery/jquery-ui-1.12.1/jquery-ui.min.js"></script>
-->


<script type="text/javascript">
//=================
//================= FOR IFRAME OF PACKING AND SHIPPING MODULE
var packingandshipping = 0;
<?php
  if(isset($_GET['packingandshipping'])){
   echo "packingandshipping = 1;";
  }
?>
if(packingandshipping==1){
  $('.main-sidebar').hide();
  $('.main-header').hide();
  $('.content-header .breadcrumb').hide();
  $('.main-footer').hide();
  $('.content-wrapper').removeClass('content-wrapper');
}
//=================

  $(document).ready(function(){
    setTimeout(function() {
      $('.nav-link').find('.fa-bars').closest('a').click();
    }, 100);
  });
</script>
@endsection
@endauth
