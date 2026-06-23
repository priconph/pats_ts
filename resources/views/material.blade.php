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

  @section('title', 'Material')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Material</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Material</li>
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
                <h3 class="card-title">Material</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <!-- <button class="btn btn-primary" data-keyboard="false" id="btnShowModalPrintBatchMaterial" disabled><i class="fa fa-print"></i> Print Batch QR Code (<span id="lblNoOfPrintBatchSelMaterial">0</span>)</button> -->

                    @if(Auth::user()->user_level_id == 1)
                      <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportMaterial" id="btnShowImport" title="Import Material"><i class="fa fa-file-excel"></i> Import</button> -->
                    @endif

                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddMaterial" id="btnShowAddMaterialModal"><i class="fa fa-plus"></i> Add Material</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblMaterials" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <!-- <th><center><input type="checkbox" name="check_all" id="chkSelAllMaterials"></center></th> -->
                          <th>Name</th>
                          <!-- <th>Barcode</th> -->
                          <th>Status</th>
                          <th>Action</th>
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

  <!-- MODALS -->
  <div class="modal fade" id="modalAddMaterial">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Material</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddMaterial">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Material Name</label>
                    <input type="text" class="form-control" name="name" id="txtAddMaterialName">
                </div>

                <!-- <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtAddMaterialBarcode">
                </div> -->
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddMaterial" class="btn btn-primary"><i id="iBtnAddMaterialIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditMaterial">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Material</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditMaterial">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="material_id" id="txtEditMaterialId">
                <div class="form-group">
                  <label>Material Name</label>
                    <input type="text" class="form-control" name="name" id="txtEditMaterialName">
                </div>

                <!-- <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtEditMaterialBarcode">
                </div> -->
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditMaterial" class="btn btn-primary"><i id="iBtnEditMaterialIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeMaterialStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeMaterialTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeMaterialStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeMaterialStatLabel">Are you sure to ?</label>
            <input type="hidden" name="material_id" placeholder="Material Id" id="txtChangeMaterialStatMaterialId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeMaterialStatMaterialStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeMaterialStat" class="btn btn-primary"><i id="iBtnChangeMaterialStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalImportMaterial">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-file-excel"></i> Import Material</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formImportMaterial" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>File</label>
                    <input type="file" class="form-control" name="import_file" id="fileImportMaterial">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnImportMaterial" class="btn btn-primary"><i id="iBtnImportMaterialIcon" class="fa fa-check"></i> Import</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableMaterials;
    let arrSelectedMaterials = [];

    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $(document).on('click','#tblMaterials tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      dataTableMaterials = $("#tblMaterials").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_materials",
            // data: function (param){
            //     param.status = $("#selEmpStat").val();
            // }
          },
          
          "columns":[
            // { "data" : "checkbox" },
            { "data" : "name" },
            // { "data" : "barcode" },
            { "data" : "label1" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
          "initComplete": function(settings, json) {
                $(".chkMaterial").each(function(index){
                    if(arrSelectedMaterials.includes($(this).attr('material-id'))){
                        $(this).attr('checked', 'checked');
                    }
                });
          },
          "drawCallback": function( settings ) {
                $(".chkMaterial").each(function(index){
                    if(arrSelectedMaterials.includes($(this).attr('material-id'))){
                        $(this).attr('checked', 'checked');
                    }
                });
            }
        });//end of dataTableMaterials

        // Add Material 
        $("#formAddMaterial").submit(function(event){
          event.preventDefault();
          AddMaterial();
        });

        $("#btnShowAddMaterialModal").click(function(){
          $("#txtAddMaterialName").removeClass('is-invalid');
          $("#txtAddMaterialName").attr('title', '');
          // $("#txtAddMaterialBarcode").removeClass('is-invalid');
          // $("#txtAddMaterialBarcode").attr('title', '');
        });

        // Edit Material
        $(document).on('click', '.aEditMaterial', function(){
          let materialId = $(this).attr('material-id');
          $("#txtEditMaterialId").val(materialId);
          GetMaterialByIdToEdit(materialId);
          $("#txtEditMaterialName").removeClass('is-invalid');
          $("#txtEditMaterialName").attr('title', '');
          // $("#txtEditMaterialBarcode").removeClass('is-invalid');
          // $("#txtEditMaterialBarcode").attr('title', '');
        });

        $("#formEditMaterial").submit(function(event){
          event.preventDefault();
          EditMaterial();
        });

        $("#btnShowModalPrintBatchMaterial").click(function(){
          PrintBatchMaterial(arrSelectedMaterials);
          // console.log(arrSelectedUsers);
        });

        $(document).on('click', '.chkMaterial', function(){
            let materialId = $(this).attr('material-id');

            if($(this).prop('checked')){
                // Checked
                if(!arrSelectedMaterials.includes(materialId)){
                    arrSelectedMaterials.push(materialId);
                }
            }
            else{  
                // Unchecked
                let index = arrSelectedMaterials.indexOf(materialId);
                arrSelectedMaterials.splice(index, 1);
            }
            $("#lblNoOfPrintBatchSelMaterial").text(arrSelectedMaterials.length);
            if(arrSelectedMaterials.length <= 0){
                $("#btnShowModalPrintBatchMaterial").prop('disabled', 'disabled');
                $("#btnSendTUVBatchEmail").prop('disabled', 'disabled');

            }
            else{
                $("#btnShowModalPrintBatchMaterial").removeAttr('disabled');
                $("#btnSendTUVBatchEmail").removeAttr('disabled');

            }
        });

        $("#chkSelAllMaterials").click(function(){
          if($(this).prop('checked')) {
              $(".chkMaterial").prop('checked', 'checked');
              $("#btnShowModalPrintBatchMaterial").removeAttr('disabled');
              $("#lblNoOfPrintBatchSelMaterial").text('All');
              arrSelectedMaterials = 0;
          }
          else{
              // $(".chkMaterial").removeAttr('checked');
              dataTableMaterials.draw();
              arrSelectedMaterials = [];
              $("#btnShowModalPrintBatchMaterial").prop('disabled', 'disabled');
              $("#lblNoOfPrintBatchSelMaterial").text('0');
          }
        });

        // Change Material Status
        $(document).on('click', '.aChangeMaterialStat', function(){
          let materialStat = $(this).attr('status');
          let materialId = $(this).attr('material-id');

          $("#txtChangeMaterialStatMaterialId").val(materialId);
          $("#txtChangeMaterialStatMaterialStat").val(materialStat);

          if(materialStat == 1){
            $("#lblChangeMaterialStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeMaterialTitle").html('<i class="fa fa-default"></i> Activate Material');
          }
          else{
            $("#lblChangeMaterialStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeMaterialTitle").html('<i class="fa fa-default"></i> Deactivate Material');
          }
        });

        $("#formChangeMaterialStat").submit(function(event){
          event.preventDefault();
          ChangeMaterialStatus();
        });

        $("#formImportMaterial").submit(function(event){
            event.preventDefault();
            $.ajax({
                url: 'import_material',
                method: 'post',
                data: new FormData(this),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    // alert('Loading...');
                },
                success: function(JsonObject){
                    if(JsonObject['result'] == 1){
                      toastr.success('Importing Success!');
                      dataTableMaterials.draw();
                      $("#modalImportMaterial").modal('hide');
                    }
                    else{
                      toastr.error('Importing Failed!');
                    }
                },
                error: function(data, xhr, status){
                    console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                }
            });
        });
      });
  </script>
  @endsection
@endauth