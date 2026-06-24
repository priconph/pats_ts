<!-- jQuery -->
<script src="{{ asset('public/template/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('public/template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('public/template/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/template/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('public/template/dist/js/demo.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('public/template/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('public/template/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('public/template/plugins/select2/js/select2.full.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('public/template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('public/template/plugins/toastr/toastr.min.js') }}"></script>

<!-- Datepicker -->
<script src="{{ asset('public/js/bootstrap-datepicker.min.js') }}"></script>


<!-- Custom JS -->
<script src="{{ asset('public/js/my_js/UserLevel.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/User.js?0987654') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/Machine.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/Material.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/ModeOfDefect.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/Device.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/Station.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/SubStation.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/StationSubStation.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/MaterialProcess.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/PackingOperator.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/PackingInspector.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/ShippingOperator.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/ShippingInspector.js') }}?<?=time()?>"></script>
<!-- <script src="{{ asset('public/js/my_js/CNLine.js') }}?<?=time()?>"></script> -->
<script src="{{ asset('public/js/my_js/AssemblyLine.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/OQCLotApp.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/OQCVIR.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/ProductionRuncard.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/WBSMaterialKitting.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/WBSSakidashi.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/Common.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/ProdPrelimInspection.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/OQCPrelimInspection.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/Kitting.js') }}?<?=time()?>"></script>

<!--ADDED 1/29/2021-->
<script src="{{ asset('public/js/my_js/OqcLotAppNew.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/Rework.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/my_js/ProcessFlow.js') }}?<?=time()?>"></script>

<!--ADDED 06/16/2025-->
<script src="{{ asset('public/js/my_js/DefectEscalation.js') }}?<?=time()?>"></script>


<script src="{{ asset('public/js/my_js/PilotRun.js') }}?<?=time()?>"></script>
<script>
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
</script>
