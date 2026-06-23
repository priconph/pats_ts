@extends('layouts.admin_layout')

@section('title', 'Dashboard')
@section('content_page')
    <div class="content-wrapper">
        <!-- Main content -->
            <div class="container-fluid">
                {{-- <button class="btn btn-info bgfp" id="modalExportTraceabilityReport" data-toggle="modal" data-target="#modalExportReport"><i class="fas fa-file-download"></i> Export Traceability Report</button> --}}
                <button class="btn btn-info bgfp" id="modalExportTraceabilityTest" data-toggle="modal" data-target="#modalExportTraceabilityReportsTest"><i class="fas fa-file-download"></i> Export Traceability Report</button>
                
            </div>
            
            <div>
                <button style="margin-top: 60%" class="btn btn-info bgfp" id="modalExportTraceabilityLive" data-toggle="modal" data-target="#modalExportTraceabilityReportLive"><i class="fas fa-file-download"></i> Export Traceability Report Test</button>
                {{-- <button class="btn btn-info bgfp" id="modalExportTraceabilityReports" data-toggle="modal" data-target="#modalExportTraceability"><i class="fas fa-file-download"></i> Export Traceability Report Orig_before</button> --}}
            </div>

    </div>


           <!-- MODALS nmodify   Export Traceability Report-->
        <div class="modal fade" id="modalExportTraceabilityReportsTest">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title"><i class="fab fa-stack-overflow"></i> Export Report</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="form-group col-sm-6 flex-column">
                                        <label>PO # to be Extracted</label>
                                        <input type="text" name="search_po" id="searchPONumberIdTest">
                                    </div>

                                    <div class="form-group col-sm-6 flex-column">
                                        <label for="shipment_date">Shipment Date:</label>
                                        {{-- <input type="date" id="shipmentDateIdTest" name="shipment_date"> --}}
                                        <input type="text" class="form-control datetimepicker" name="date_received" id="txtViewDateReceived" autocomplete="off" placeholder="yyyy-mm-dd" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6 flex-column">
                                        <label id="">Lot No (from):</label>
                                        <label class="lotFromClasstest" id="lotNoFromIdtest"></label>
                                        <input type="text" name="lot_no_from" id="exportLotNoFromIdTest">
                                    </div>

                                    <div class="form-group col-sm-6 flex-column">
                                        <label>Lot No (to):</label>
                                        <label class="lotToClasstest" id="lotNoToIdtest"></label>
                                        <input type="text" name="lot_no_to" id="exportLotNoToIdTest">
                                    </div>
                                    <div>
                                        <input type="hidden" name="device_name" id="deviceNameId">
                                    </div>
                                </div>
                                <div class="text-danger">
                                    <p>NOTE: Please enter the PO number to enable the Export button</p>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnExportReport" class="btn btn-dark" disabled><i id="BtnExportReportIcon" class="fa fa-check"></i> Export</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

          <!-- MODALS -->
          <div class="modal fade" id="modalExportTraceabilityReportLive">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title"><i class="fab fa-stack-overflow"></i> Export Traceability Report</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="form-group col-sm-6 flex-column">
                                        <label>PO # to be Extracted</label>
                                        <input type="text" name="search_po_number" id="exportPONumberIDLive" autocomplete="off">
                                    </div>

                                    <div class="form-group col-sm-6 flex-column">
                                        <label for="shipment_date">Shipment Date:</label>
                                        <input type="text" class="form-control datetimepickerLive" name="shipmentDateLive" id="txtViewShipmentDateLive" autocomplete="off" placeholder="yyyy-mm-dd" aria-label="Default" aria-describedby="inputGroup-sizing-default">

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6 flex-column">
                                        <label id="">Lot No (from):</label>
                                        <label class="lotFromClass" id="lotNoFromId"></label>
                                        <input type="text" name="lot_no_from" id="exportLotNoFromIdLive">
                                    </div>

                                    <div class="form-group col-sm-6 flex-column">
                                        <label>Lot No (to):</label>
                                        <label class="lotToClass" id="lotNoToId"></label>
                                        <input type="text" name="lot_no_to" id="exportLotNoToIdLive">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnExportJannusReportId" class="btn btn-dark"><i id="btnExportJannusReportIdIcon" class="fa fa-check"></i> Export</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog --> 
        </div><!-- /.modal -->



@endsection

<!--     {{-- JS CONTENT --}} -->
@section('js_content')
    <script type="text/javascript">


    // $('#btnExportTraceabilityReport').on('click', function(){
    //     let po_number = $('#exportPONumberId').val();

    //     window.location.href = `export_traceability_report/${po_number}`;
    //     $('#modalExportTraceability').modal('hide');

    // });

    $('.datetimepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        // format: 'yyyy-mm-dd',
                        forceParse: false, // prevent from clearing existing values from input when no date selected
                        autoclose: true, // autoclose date after selecting date
                        clearBtn: true, // include clear button
                        // daysOfWeekDisabled: [0, 6], // disabled weekends
                        todayHighlight: true,
                        // daysOfWeekHighlighted: [1,2,3,4,5],
                        // datesDisabled: disabledDays,
                    
    });

    $('.datetimepickerLive').datepicker({
                        format: 'yyyy-mm-dd',
                        // format: 'yyyy-mm-dd',
                        forceParse: false, // prevent from clearing existing values from input when no date selected
                        autoclose: true, // autoclose date after selecting date
                        clearBtn: true, // include clear button
                        // daysOfWeekDisabled: [0, 6], // disabled weekends
                        todayHighlight: true,
                        // daysOfWeekHighlighted: [1,2,3,4,5],
                        // datesDisabled: disabledDays,
                    
    });

    $(document).on('click','#modalExportTraceabilityTest',function(e){
        $('#searchPONumberIdTest').val("");
        $('#exportLotNoFromIdTest').val("");
        $('#exportLotNoToIdTest').val("");
        $('.datetimepicker').val('');
        $('#modalExportTraceabilityReportsTest').attr('data-formid', '').modal('show');
    });

    $(document).on('click','#modalExportTraceabilityLive',function(e){
        $('#exportPONumberIDLive').val("");
        $('#exportLotNoFromIdLive').val("");
        $('#exportLotNoToIdLive').val("");
        $('.datetimepickerLive').val('');
        $('#modalExportTraceabilityReportLive').attr('data-formid', '').modal('show');
    });

    

    $(document).on('keypress',function(e) {
        // event.preventDefault(); // to stop the form submission
            if(e.which == 13) {
                // alert('You pressed enter!');
                    $.ajax({
                    url: "get_lot_no_list_test",
                    type: "get",
                    data: {
                        po_number : $('#exportPONumberIDLive').val(),
                        shipment_date_live : $('.datetimepickerLive').val()
                    },
                    dataType: "json",
                    success: function (response) {

                    // console.log('qweeeeee');

                    if(response['lot_no_start'] != null && response['lot_no_end'] != null){
                        let lot_no_start = response['lot_no_start']['lot_no'];
                        let lot_no_end =   response['lot_no_end']['lot_no'];

                        $('#lotNoFromId').text(lot_no_start);
                        $('#lotNoToId').text(lot_no_end);

                        $('#btnExportJannusReportId').on('click', function(){
                            let po_number = $('#exportPONumberIDLive').val();
                            let lot_no_from = $('#exportLotNoFromIdLive').val();
                            let lot_no_to = $('#exportLotNoToIdLive').val();
                            let shipment_date_live = $('.datetimepickerLive').val();

                            // console.log(`${po_number} ${lot_no_from} ${lot_no_to} ${shipment_date_live}`)

                            let from_lot_no = lot_no_start.substr(11,14);
                            let to_lot_no = lot_no_end.substr(11,14);


                            // console.log('lot no start', lot_no_start)
                            // console.log('lot no end', lot_no_end)

                            // console.log('lot from:', from_lot_no);
                            // console.log('lot to:', to_lot_no);

                            console.log('input data from: ', $("#exportLotNoFromIdLive").val());
                            console.log('input data to: ', $("#exportLotNoToIdLive").val());
                            console.log('PO: ', $("#exportPONumberIDLive").val());
                            console.log('Shipment Date', $(".datetimepickerLive").val());

                            //0607023 - Nessa
                            // || $("#exportLotNoToId").val() > to_lot_no
                            // if ($("#exportLotNoFromId").val() < from_lot_no) {
                            //     alert('INPUT DATA IS INVALID PLEASE CHECK LOT NO RANGE')

                            // }
                            // // else if($("#exportLotNoToId").val() == to_lot_no){
                            // //     alert('asdjkasdhasdasjkd')
                            // // }
                            // else{
                                window.location.href = `export_traceability_report_data/${po_number}/${lot_no_from}/${lot_no_to}/${shipment_date_live}`;
                                $('#modalExportTraceabilityReportLive').modal('hide');
                                // $('input[type="text"]').val('');
                                // $('input[type="date"]').val('');
                                $('label[id*="lotNoFromId"]').text('');
                                $('label[id*="lotNoToId"]').text('');
                            // }

                        });
                    }else{
                        alert('No Data');
                    }
                }

                });
            }
    });

    $(document).on('keypress',function(e) {
        // console.log($('.datetimepicker').val())
        // event.preventDefault(); // to stop the form submission
            if(e.which == 13) {
                    $('#btnExportReport').attr('disabled',false);
                    $.ajax({
                    url: "get_lot_no_list",
                    type: "get",
                    data: {
                        po_number_test : $('#searchPONumberIdTest').val(),
                        shipment_date_test : $('.datetimepicker').val()
                    },
                    dataType: "json",
                    success: function (response) {

                    // console.log(po_number_test);
                    // console.log(shipment_date_test);

                    if(response['lot_no_start'] != null && response['lot_no_end'] != null){
                        let lot_no_start = response['lot_no_start']['lot_no'];
                        let lot_no_end =   response['lot_no_end']['lot_no'];


                        $('#lotNoFromIdtest').text(lot_no_start);
                        $('#lotNoToIdtest').text(lot_no_end);

                        $('#btnExportReport').on('click', function(){
                            let po_number_test = $('#searchPONumberIdTest').val();
                            let lot_no_from = $('#exportLotNoFromIdTest').val();
                            let lot_no_to = $('#exportLotNoToIdTest').val();
                            let shipment_date_test = $('.datetimepicker').val();

                            // let from_lot_no = lot_no_start.substr(11,14);
                            // let to_lot_no = lot_no_end.substr(11,14);


                            console.log('input data from: ', $("#exportLotNoFromIdTest").val());
                            console.log('input data to: ', $("#exportLotNoToIdTest").val());
                            console.log('PO: ', $("#searchPONumberIdTest").val());
                            console.log('PO: ', $(".datetimepicker").val());

                            //0607023 - Nessa
                            // || $("#exportLotNoToId").val() > to_lot_no
                            // if ($("#exportLotNoFromIdtest").val() < from_lot_no) {
                            //     alert('INPUT DATA IS INVALID PLEASE CHECK LOT NO RANGE')

                            // }
                            // else if($("#exportLotNoToId").val() == to_lot_no){
                            //     alert('asdjkasdhasdasjkd')
                            // }
                            // else{
                                // location.reload(true);
                                $('#modalExportTraceabilityReportsTest').modal('hide');

                                // $('input[type="text"]').val('');
                                // $('input[type="date"]').val("");
                                $('label[id="lotNoFromIdtest"]').text('');
                                $('label[id="lotNoToIdtest"]').text('');
                                // }
                                window.location.href = `export_traceability/${po_number_test}/${lot_no_from}/${lot_no_to}/${shipment_date_test}`;

                        });
                    }else{
                        alert('No Data');
                    }

                    }
                });
            }
    });


    </script>
@endsection

