  <?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('index');
})->name('login');

Route::get('/login', function () {
    return view('index');
})->name('login');

Route::get('/admin', function () {
    return view('admin_login');
})->name('admin');

// ADMINISTRATOR MODULE
Route::get('/change_pass_view', function () {
    return view('change_password');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/blank', function () {
    return view('blank');
})->name('blank');

Route::get('/user', function () {
    return view('user');
})->name('user');

Route::get('/machine', function () {
    return view('machine');
})->name('machine');

Route::get('/material', function () {
    return view('material');
})->name('material');

Route::get('/ophr', function () {
    return view('ophr');
})->name('ophr');

Route::get('/ddr', function () {
    return view('ddr');
})->name('ddr');

Route::get('/mode_of_defect', function () {
    // return view('mode_of_defect');
    return view('mode_of_defect_test'); // mdr 6/9/2025
})->name('mode_of_defect');

// Route::get('/mode_of_defect_test', function () {
//     return view('mode_of_defect_test');
// })->name('mode_of_defect_test');


Route::get('/kitting', function () {
    return view('kitting');
})->name('kitting');

Route::get('/materialissuance_test', function () {
    return view('materialissuance_test');
})->name('materialissuance_test');

// Route::get('/cn_lines', function () {
//     return view('cn_lines');
// })->name('cn_lines');

Route::get('/assembly_line', function () {
    return view('assembly_line');
})->name('assembly_line');

Route::get('/device', function () {
    return view('device');
})->name('device');

Route::get('/station', function () {
    return view('station');
})->name('station');

Route::get('/materialprocess', function () {
    return view('materialprocess');
})->name('materialprocess');

Route::get('/materialprocess_new', function () {
    return view('materialprocess_new');
})->name('materialprocess_new');

Route::get('/materialprocess_test', function () {
    return view('materialprocess_test');
})->name('materialprocess_test');

Route::get('/processtimeline', function () {
    return view('processtimeline');
})->name('processtimeline');


//PRELIMINARY PACKING
Route::get('/packingoperator', function () {
    return view('packingoperator');
})->name('packingoperator');

Route::get('/packinginspector', function () {
    return view('packinginspector');
})->name('packinginspector');

//PRELIMINARY PACKING
Route::get('/shippingoperator', function () {
    return view('shippingoperator');
})->name('shippingoperator');

Route::get('/shippinginspector', function () {
    return view('shippinginspector');
})->name('shippinginspector');

Route::get('/prod_runcard_test', function () {
return view('prod_runcard_test');
})->name('prod_runcard_test');
Route::get('/prod_runcard', function () {
    return view('prod_runcard');
})->name('prod_runcard');

Route::get('/prod_runcard_automatic', function () {
    return view('prod_runcard_automatic');
})->name('prod_runcard_automatic');

Route::get('/prod_runcard_manual', function () {
    return view('prod_runcard_manual');
})->name('prod_runcard_manual');

Route::get('/prod_runcard_new', function () { // Live
    return view('prod_runcard_manual_live'); // 02202023 deployed LIVE
})->name('prod_runcard_new');
Route::get('/prod_runcard_new_migz', function () { // Live
    return view('prod_runcard_manual_live_migz'); // 02202023 deployed LIVE
})->name('prod_runcard_new_migz');
Route::get('/prod_runcard_manual_search', function () { // Live
    return view('prod_runcard_manual_search'); // 02202023 deployed LIVE
})->name('prod_runcard_manual_search');

Route::get('/prod_runcard_with_defect', function () { // mdr runcard - test
    return view('prod_runcard_manual_with_defect');
})->name('prod_runcard_with_defect');

Route::get('/prod_runcard_bnew', function () { // Live
    return view('prod_runcard_091925'); // 02202023 deployed LIVE
})->name('prod_runcard_bnew');

Route::get('/prod_runcard_migz', function () { // Live
    return view('prod_runcard_manual_migz'); // 02202023 deployed LIVE
})->name('prod_runcard_migz');

Route::get('/runcard_offline', function () {
    return view('runcard_offline');
})->name('runcard_offline');

Route::get('/prod_runcard_new2', function () {
    return view('prod_runcard_manual2');
})->name('prod_runcard_new2');

Route::get('/prod_runcard_new_test', function () { // For testing ONLY
    // return view('prod_runcard_manual_test_1'); // For testing ONLY
    return view('prod_runcard_manual_test'); // For testing ONLYupdated 02202023
})->name('prod_runcard_new_test');

Route::get('/prod_runcard_manual_rev1', function () {
    return view('prod_runcard_manual_rev1');
})->name('prod_runcard_manual_rev1');

Route::get('/defect_escalation', function () {
    // return view('defect_escalation'); // old live
    return view('defect_escalation_rev2'); // live 05282023
})->name('defect_escalation');

// Route::get('/defect_escalation_darren', function () {
//     return view('defect_escalation_darren');
// })->name('defect_escalation_darren');
Route::get('/defect_escalation_v2', function (Request $request) { // live on prod
    $po = $request->query('po');
    $device = $request->query('device');
    $code = $request->query('code');
    $qty = $request->query('qty');

    return view('defect_escalation2', compact('po','device', 'code', 'qty'));
})->name('defect_escalation_v2');



Route::get('/defect_escalation_v2_old', function (Request $request) { //
    $po = $request->query('po');
    $device = $request->query('device');
    $code = $request->query('code');
    $qty = $request->query('qty');

    return view('defect_escalation_darren', compact('po','device', 'code', 'qty'));
})->name('defect_escalation_v2_for_testing'); // defect V3 - testing

Route::get('/defect_escalation_migz', function () {
    return view('defect_escalation_rev2_migz');
})->name('defect_escalation_migz');

Route::get('/defect_escalation_test', function () {
    return view('defect_escalation_rev2'); // for testing na
})->name('defect_escalation_test');


Route::get('/warehouse', function () {
    return view('warehouse');
})->name('warehouse');

Route::get('/rapid_acdcs', function () {
    return view('rapid_acdcs');
})->name('rapid_acdcs');

Route::get('/exportshippingrecord', function () {
    return view('exportshippingrecord');
})->name('exportshippingrecord');

Route::get('/packingandshipment', function () {
    return view('packingandshipment');
})->name('packingandshipment');

//FEBRUARY 27, 2020 NEW VIEWS
Route::get('/prod_preliminary_inspection', function () {
    return view('prod_preliminary_inspection');
})->name('prod_preliminary_inspection');

Route::get('/oqc_preliminary_inspection', function () {
    return view('oqc_preliminary_inspection');
})->name('oqc_preliminary_inspection');

Route::get('/packingseeder', function () {
    return view('packingseeder');
})->name('packingseeder');



//OQC LOT APPLICATION FOR TS
Route::get('/oqclotapplication_ts', function () {
    return view('oqclotapplication_ts');
})->name('oqclotapplication_ts');

Route::get('/oqcvir_ts', function () {
    return view('oqcvir_ts');
})->name('oqcvir_ts');

Route::get('/qc_packing_inspection', function () {
    return view('qc_packing_inspection');
})->name('qc_packing_inspection');

Route::get('/qc_shipping_inspection', function () {
    return view('qc_shipping_inspection');
})->name('qc_shipping_inspection');


//OQC LOT APPLICATION NEW 1/29/2021
Route::get('/oqclotapplication_new', function () {
    return view('oqclotapplication_new');
})->name('oqclotapplication_new');

//OQC VIR NEW 2/27/2021
Route::get('/oqcvir_new', function () {
    return view('oqcvir_new');
})->name('oqcvir_new');

//PACKING INSPECTION NEW 3/1/2021
Route::get('/packinginspection_new',function(){
    return view('packinginspection_new');
})->name('packinginspection_new');

Route::get('/pilotrun1',function(){
    return view('pilotrun1');
})->name('pilotrun1');

Route::get('/pilotrun2',function(){
    return view('pilotrun2');
})->name('pilotrun2');

Route::get('/pilotrun2_test',function(){
    return view('pilotrun2_test');
})->name('pilotrun2_test');

Route::get('/pilotrun3',function(){
    return view('pilotrun3');
})->name('pilotrun3');

Route::get('/pilotrun4',function(){
    return view('pilotrun4');
})->name('pilotrun4');

Route::get('/pilotrun4',function(){
    return view('pilotrun4');
})->name('pilotrun4');

Route::get('/pilotrun5',function(){
    return view('pilotrun5');
})->name('pilotrun5');

/* FROM PPTS*/
Route::get('/casemark_sticker', function () {
    return view('casemark_sticker');
})->name('casemark_sticker');

Route::get('/visual_inspection_ppts', function () {
    return view('visual_inspection_ppts');
})->name('visual_inspection_ppts');

Route::get('/oqclotapp_ppts', function () {
    return view('oqclotapp_ppts');
})->name('oqclotapp_ppts');

Route::get('/overall_ppts', function () {
    return view('overall_ppts');
})->name('overall_ppts');

Route::get('/oqc_insp_ppts', function () {
    return view('oqc_insp_ppts');
})->name('oqc_insp_ppts');

Route::get('/pack_conf_ppts', function () {
    return view('pack_conf_ppts');
})->name('pack_conf_ppts');

Route::get('/prel_pack_ppts', function () {
    return view('prel_pack_ppts');
})->name('prel_pack_ppts');

Route::get('/supv_val_ppts', function () {
    return view('supv_val_ppts');
})->name('supv_val_ppts');

Route::get('/final_opt_qc_insp_ppts', function () {
    return view('final_opt_qc_insp_ppts');
})->name('final_opt_qc_insp_ppts');

Route::get('/final_opt_qc_web_ppts', function () {
    return view('final_opt_qc_web_ppts');
})->name('final_opt_qc_web_ppts');

Route::get('/final_qc_web_ppts', function () {
    return view('final_qc_web_ppts');
})->name('final_qc_web_ppts');

Route::get('/final_traffic_qc_insp_ppts', function () {
    return view('final_traffic_qc_insp_ppts');
})->name('final_traffic_qc_insp_ppts');

Route::get('/drawing_ref', function () {
    return view('drawing_ref');
})->name('drawing_ref');


/* EXCEL REPORT */

Route::get('/traceability_export', function () {
    return view('traceability_export');
})->name('traceability_export');

Route::get('/traceability_export_rev1', function () {
    return view('traceability_export_rev1');
})->name('traceability_export_rev1');


Route::get('/export_traceability/{po_number_test}/{lot_no_from}/{lot_no_to}/{shipment_date_test}', 'ExportTraceabilityReportControllerOrig@export_traceability');
Route::get('/export_traceability_1/{po_number_test}/{lot_no_from}/{lot_no_to}/{shipment_date_test}', 'ExportTraceabilityReportControllerOrig@export_traceability_1');
Route::get('/export_traceability_test', 'ExportTraceabilityReportControllerOrig@export_traceability_test');
Route::get('/get_lot_no_list', 'ExportTraceabilityReportControllerOrig@get_lot_no_list'); //test
Route::get('/get_lot_no_list_1', 'ExportTraceabilityReportControllerOrig@get_lot_no_list_1'); //test

// #LIVE

// Route::get('/export_traceability_report_data/{po_number}/{lot_no_from}/{lot_no_to}/{shipment_date_live}', 'ExportTraceabilityReportControllerOrig@export_traceability_report_data');
// Route::get('/get_lot_no_list_test', 'ExportTraceabilityReportControllerOrig@get_lot_no_list_test');

//EXPORT DYNAMIC DATA
// Route::get('/export_traceability/{po_number}/{lot_no_from}/{lot_no_to}/{shipment_date}', 'ExportTraceabilityReportController@export_traceability');

// COMMON CONTROLLER
Route::get('/generate_qrcode', 'CommonController@generate_qrcode')->name('generate_qrcode');

Route::get('/check_session', 'UserController@check_session')->name('check_session'); // JD



// USER CONTROLLER
Route::post('/sign_in', 'UserController@sign_in')->name('sign_in');
Route::post('/sign_out', 'UserController@sign_out')->name('sign_out');
Route::post('/change_pass', 'UserController@change_pass')->name('change_pass');
Route::post('/change_user_stat', 'UserController@change_user_stat')->name('change_user_stat');
Route::get('/view_users', 'UserController@view_users');
Route::post('/add_user', 'UserController@add_user');
Route::get('/get_user_by_id', 'UserController@get_user_by_id');
Route::get('/get_user_by_employee_no', 'UserController@get_user_by_employee_no');
Route::get('/get_user_list', 'UserController@get_user_list');
Route::get('/get_user_list_for_defect', 'UserController@get_user_list_for_defect'); // mdr
Route::get('/get_user_list_for_person_in_charge', 'UserController@get_user_list_for_person_in_charge'); // mdr
Route::get('/get_user_by_batch', 'UserController@get_user_by_batch');
Route::get('/get_user_by_stat', 'UserController@get_user_by_stat');
Route::get('/get_user_by_emp_id', 'UserController@get_user_by_emp_id');
Route::get('/get_verification_user', 'UserController@get_verification_user'); // mdr
Route::get('/get_verification_user_test', 'UserController@get_verification_user_test'); // mdr
Route::get('/get_reworker_user', 'UserController@get_reworker_user'); // mdr
Route::get('/get_2nd_verification_user', 'UserController@get_2nd_verification_user'); // mdr
Route::get('/get_final_disposition_user', 'UserController@get_final_disposition_user'); // mdr
Route::get('/get_qc_verification_user', 'UserController@get_qc_verification_user'); // mdr
Route::post('/edit_user', 'UserController@edit_user');
Route::post('/reset_password', 'UserController@reset_password');
Route::get('/generate_user_qrcode', 'UserController@generate_user_qrcode');
Route::post('/import_user', 'UserController@import_user');
Route::get('/check_session', 'UserController@checkSession')->name('check_session'); // added by -JD
Route::post('/sign_in_admin', 'UserController@sign_in_admin')->name('sign_in_admin'); // added by -JD

// MACHINE CONTROLLER
Route::post('/change_machine_stat', 'MachineController@change_machine_stat')->name('change_machine_stat');
Route::get('/view_machines', 'MachineController@view_machines');
Route::post('/add_machine', 'MachineController@add_machine');
Route::get('/get_machine_by_id', 'MachineController@get_machine_by_id');
Route::post('/edit_machine', 'MachineController@edit_machine');
Route::post('/import_machine', 'MachineController@import_machine');
Route::get('/get_machines', 'MachineController@get_machines');
Route::get('/get_machines_for_runcard_station', 'MachineController@get_machines_for_runcard_station');
Route::get('/get_machines_test', 'MachineController@get_machines_test');
Route::get('/get_machine_by_batch', 'MachineController@get_machine_by_batch');

// MATERIAL CONTROLLER
Route::post('/change_material_stat', 'MaterialController@change_material_stat')->name('change_material_stat');
Route::get('/view_materials', 'MaterialController@view_materials');
Route::post('/add_material', 'MaterialController@add_material');
Route::get('/get_material_by_id', 'MaterialController@get_material_by_id');
Route::get('/get_material_by_stat', 'MaterialController@get_material_by_stat');
Route::post('/edit_material', 'MaterialController@edit_material');
Route::post('/import_material', 'MaterialController@import_material');
Route::get('/get_materials', 'MaterialController@get_materials');
Route::get('/get_materials_by_id', 'MaterialController@get_materials_by_id');
Route::get('/get_material_by_batch', 'MaterialController@get_material_by_batch');
Route::get('/getMaterialsFromRecieving', 'MaterialController@getMaterialsFromRecieving');
// Route::get('/getMaterialsFromRecieving_test', 'MaterialController@getMaterialsFromRecieving_test'); // Change for live
Route::get('/getMaterialListByDeviceName', 'MaterialController@getMaterialListByDeviceName');
Route::get('/getMaterialListByProcess', 'MaterialController@getMaterialListByProcess');
Route::post('/save_process_flow_material', 'MaterialController@save_process_flow_material');


// MODE OF DEFECT CONTROLLER
Route::post('/change_mod_stat', 'ModeOfDefectController@change_mod_stat')->name('change_mod_stat');
Route::get('/view_mods', 'ModeOfDefectController@view_mods');
Route::get('/view_mods_test', 'ModeOfDefectController@view_mods_test'); //mdr
Route::post('/add_mod', 'ModeOfDefectController@add_mod');
Route::post('/add_mod_test', 'ModeOfDefectController@add_mod_test');//mdr
Route::get('/get_mod_by_id', 'ModeOfDefectController@get_mod_by_id');
Route::post('/edit_mod', 'ModeOfDefectController@edit_mod');
Route::post('/import_mod', 'ModeOfDefectController@import_mod');
Route::get('/get_mods', 'ModeOfDefectController@get_mods');
// Route::get('/get_mods_multiple', 'ModeOfDefectController@get_mods_multiple');
Route::get('/get_mod_by_batch', 'ModeOfDefectController@get_mod_by_batch');
Route::get('/get_mods_for_dd', 'ModeOfDefectController@get_mods_for_dd');

// // CN LINE CONTROLLER
// Route::post('/change_cn_line_stat', 'CNLineController@change_cn_line_stat')->name('change_cn_line_stat');
// Route::get('/view_cn_lines', 'CNLineController@view_cn_lines');
// Route::post('/add_cn_line', 'CNLineController@add_cn_line');
// Route::get('/get_cn_line_by_id', 'CNLineController@get_cn_line_by_id');
// Route::post('/edit_cn_line', 'CNLineController@edit_cn_line');

// ASSEMBLY LINE CONTROLLER
Route::post('/change_assembly_line_stat', 'AssemblyLineController@change_assembly_line_stat')->name('change_assembly_line_stat');
Route::get('/view_assembly_lines', 'AssemblyLineController@view_assembly_lines');
Route::post('/add_assembly_line', 'AssemblyLineController@add_assembly_line');
Route::get('/get_assembly_line_by_id', 'AssemblyLineController@get_assembly_line_by_id');
Route::post('/edit_assembly_line', 'AssemblyLineController@edit_assembly_line');
Route::get('/get_assembly_lines', 'AssemblyLineController@get_assembly_lines');
Route::post('/import_assembly_line', 'AssemblyLineController@import_assembly_line');

// DEVICE CONTROLLER
Route::post('/change_device_stat', 'DeviceController@change_device_stat')->name('change_device_stat');
Route::get('/view_devices', 'DeviceController@view_devices');
Route::get('/view_devices_test', 'DeviceController@view_devices_test'); // Change for live
Route::get('/getAllDevices', 'DeviceController@getAllDevices');
Route::post('/add_device', 'DeviceController@add_device');
Route::get('/get_device_by_id', 'DeviceController@get_device_by_id');
Route::post('/edit_device', 'DeviceController@edit_device');
Route::post('/import_packing_matrix', 'DeviceController@import_packing_matrix');

// 2022 06 25

Route::get('/checkProcessFlowIsSet', 'DeviceController@checkProcessFlowIsSet');
Route::get('/getProcessFlowByDeviceName', 'DeviceController@getProcessFlowByDeviceName');

Route::get('/getNextStepNumber', 'ProductionRuncardStationController@getNextStepNumber');

// 2022 06 25

// STATION CONTROLLER
Route::post('/change_station_stat', 'StationController@change_station_stat')->name('change_station_stat');
Route::get('/view_stations', 'StationController@view_stations');
Route::post('/add_station', 'StationController@add_station');
Route::get('/get_station_by_id', 'StationController@get_station_by_id');
Route::post('/edit_station', 'StationController@edit_station');
Route::get('/get_station_by_stat', 'StationController@get_station_by_stat');
Route::get('/get_stations_by_stat', 'StationController@get_stations_by_stat');

// SUB STATION CONTROLLER
Route::post('/change_sub_station_stat', 'SubStationController@change_sub_station_stat')->name('change_sub_station_stat');
// Route::get('/view_sub_stations_by_station_id', 'SubStationController@view_sub_stations_by_station_id');
Route::get('/view_sub_stations', 'SubStationController@view_sub_stations');
Route::post('/add_sub_station', 'SubStationController@add_sub_station');
Route::get('/get_sub_station_by_id', 'SubStationController@get_sub_station_by_id');
Route::post('/edit_sub_station', 'SubStationController@edit_sub_station');
Route::get('/get_sub_stations_by_stat', 'SubStationController@get_sub_stations_by_stat');
Route::get('/generate_sub_station_qrcode', 'SubStationController@generate_sub_station_qrcode');

// STATION SUB STATION CONTROLLER
Route::get('/view_station_sub_stations', 'StationSubStationController@view_station_sub_stations')->name('view_station_sub_stations');
Route::post('/add_station_sub_station', 'StationSubStationController@add_station_sub_station')->name('add_station_sub_station');
Route::get('/get_station_sub_stations_by_stat', 'StationSubStationController@get_station_sub_stations_by_stat')->name('get_station_sub_stations_by_stat');
Route::post('/change_station_sub_station_stat', 'StationSubStationController@change_station_sub_station_stat')->name('change_station_sub_station_stat');

// MATERIAL PROCESS CONTROLLER
Route::get('/view_material_process_by_device_id', 'MaterialProcessController@view_material_process_by_device_id');
Route::post('/add_material_process', 'MaterialProcessController@add_material_process');
Route::post('/edit_material_process', 'MaterialProcessController@edit_material_process');
Route::get('/get_mat_proc_by_id', 'MaterialProcessController@get_mat_proc_by_id');
Route::post('/change_material_process_stat', 'MaterialProcessController@change_material_process_stat');
Route::get('/test_material_process_sort', 'MaterialProcessController@test_material_process_sort');

// USER LEVEL CONTROLLER
Route::get('/get_user_levels', 'UserLevelController@get_user_levels');

// OQC CONTROLLER
Route::get('/oqclotapp', function () {
    return view('oqclotapp');
})->name('oqclotapp');

Route::get('/get_oqc_inspection_data_test', 'OQCLotAppController@get_oqc_inspection_data_test');
Route::get('/test_data', 'OQCLotAppController@test_data');

Route::get('/get_oqc_inspection_data', 'OQCLotAppController@get_oqc_inspection_data');
Route::get('/get_assy_lines', 'OQCLotAppController@get_assy_lines');
Route::get('/get_assy_lines', 'OQCLotAppController@get_assy_lines');
Route::get('/get_po_details', 'OQCLotAppController@get_po_details');
Route::post('/add_oqc_lot_app', 'OQCLotAppController@add_oqc_lot_app');
Route::post('/update_approved_prod', 'OQCLotAppController@update_approved_prod');
Route::post('/update_approved_oqc', 'OQCLotAppController@update_approved_oqc');
Route::get('/get_oqc_lot_app_data', 'OQCLotAppController@get_oqc_lot_app_data');
Route::get('/get_oqc_lot_app_data_summary', 'OQCLotAppController@get_oqc_lot_app_data_summary');
Route::get('/get_oqc_lot_app_details', 'OQCLotAppController@get_oqc_lot_app_details');
Route::get('/get_user_details_lotapp', 'OQCLotAppController@get_user_details_lotapp');
Route::get('/get_prod_user_details', 'OQCLotAppController@get_prod_user_details');
Route::get('/get_oqc_user_details', 'OQCLotAppController@get_oqc_user_details');
/*Route::get('/get_runcard_details', 'OQCLotAppController@get_runcard_details');*/
Route::get('/get_user_details_lotapp_arr', 'OQCLotAppController@get_user_details_lotapp_arr');
Route::get('/get_lot_app_by_id', 'OQCLotAppController@get_lot_app_by_id');
Route::get('/fn_get_lot_app_by_id', 'OQCLotAppController@fn_get_lot_app_by_id');

// OQC Visual Inspection CONTROLLER
Route::get('/oqclvisualinspection', function () {
    return view('oqclvisualinspection');
})->name('oqclvisualinspection');

Route::get('/get_oqc_lotapp_details', 'OQCVIRController@get_oqc_lotapp_details');
Route::get('/get_oqclot_details', 'OQCVIRController@get_oqclot_details');
Route::post('/add_oqc_vir', 'OQCVIRController@add_oqc_vir');
Route::get('/get_oqcvir_details', 'OQCVIRController@get_oqcvir_details');
Route::get('/get_user_details_vir', 'OQCVIRController@get_user_details_vir');
Route::get('/get_oqcvir_summary', 'OQCVIRController@get_oqcvir_summary');
Route::get('/get_oqclotapp_data', 'OQCVIRController@get_oqclotapp_data');
Route::get('/get_oqc_lotapp_data_summary', 'OQCVIRController@get_oqc_lotapp_data_summary');
Route::get('/get_insp_result_by_id', 'OQCVIRController@get_insp_result_by_id');

// Rapid ACDCS
Route::get('/get_acdcs_data', 'RapidACDCSController@get_acdcs_data');
Route::get('/get_docNo_details', 'RapidACDCSController@get_docNo_details');



// MATERIAL ISSUANCE CONTROLLER
Route::get('/materialissuance', 'MaterialIssuanceController@fn_view_material_issuance_page')->name('materialissuancealias');
Route::get('/view_batches', 'MaterialIssuanceController@fn_view_batches'); //nmodify
Route::get('/view_lot_numbers', 'MaterialIssuanceController@fn_view_lot_numbers');
Route::post('/insert_materialissuance', 'MaterialIssuanceController@fn_insert_materialissuance');
Route::get('/view_sakidashi_issuance', 'MaterialIssuanceController@fn_view_sakidashi_issuance');
Route::get('/view_warehouse_issuance', 'MaterialIssuanceController@fn_view_warehouse_issuance');
Route::get('/fn_view_subkitting', 'MaterialIssuanceController@fn_view_subkitting');
Route::get('/generate_lotno_qrcode', 'MaterialIssuanceController@generate_lotno_qrcode');
Route::post('/insert_partsprep_subkitting', 'MaterialIssuanceController@insert_partsprep_subkitting');



Route::get('/partspreparatory', 'PartsPreparatoryController@fn_view_parts_preparatory_page')->name('partspreparatoryalias');
Route::get('/select_partspreparatory_materials', 'PartsPreparatoryController@fn_view_materials');
Route::get('/view_sakidashi_parts_prep', 'PartsPreparatoryController@fn_view_sakidashi_parts_prep');
Route::get('/view_warehouse_parts_prep', 'PartsPreparatoryController@fn_view_warehouse_parts_prep');
Route::get('/select_partspreparatory_setup_stations', 'PartsPreparatoryController@fn_view_setup_stations');
Route::get('/select_partspreparatory_stations', 'PartsPreparatoryController@fn_view_partspreparatory_stations');
Route::get('/select_partspreparatory_station_details', 'PartsPreparatoryController@fn_select_partspreparatory_station_details');
Route::get('/select_partspreparatory_material_details', 'PartsPreparatoryController@fn_select_partspreparatory_material_details');
Route::post('/insert_lot_pass_fail', 'PartsPreparatoryController@fn_insert_lot_pass_fail');
Route::post('/insert_sakidashi_lot_pass_fail', 'PartsPreparatoryController@fn_insert_sakidashi_lot_pass_fail');
Route::post('/insert_warehouse_lot_pass_fail', 'PartsPreparatoryController@fn_insert_warehouse_lot_pass_fail');
Route::post('/insert_material_details', 'PartsPreparatoryController@fn_insert_material_details');
Route::post('/insert_material_details_parts_prep', 'PartsPreparatoryController@fn_insert_material_details_parts_prep');
Route::post('/insert_setup_stations', 'PartsPreparatoryController@fn_insert_setup_stations');
Route::post('/update_partsprep_station_details', 'PartsPreparatoryController@fn_update_partsprep_station_details');
Route::post('/update_material_details_secondary', 'PartsPreparatoryController@fn_update_material_details_secondary');
Route::post('/update_approval_prod', 'PartsPreparatoryController@fn_update_approval_prod');
Route::post('/update_approval_qc', 'PartsPreparatoryController@fn_update_approval_qc');
Route::get('/select_list_partsprep_station_stations', 'PartsPreparatoryController@fn_select_list_partsprep_station_stations');
Route::get('/get_stations', 'PartsPreparatoryController@get_stations');

 // 10062022 Nessa
Route::get('/select_list_partsprep_station_machines', 'PartsPreparatoryController@fn_select_list_partsprep_station_machines'); //09212022

Route::get('/select_list_runcard_numbers', 'PartsPreparatoryController@fn_select_list_runcard_numbers');
Route::get('/select_list_partsprep_station_mod', 'PartsPreparatoryController@fn_select_list_partsprep_station_mod');
Route::get('/select_po_details', 'PartsPreparatoryController@fn_select_po_details');

Route::get('/scrapverificationruncard', 'ScrapVerificationRuncardController@fn_view_scrapverificationruncard')->name('scrapverificationruncard');
Route::get('/select_svr_datatable', 'ScrapVerificationRuncardController@fn_select_svr_datatable');
Route::get('/select_svr_datatable_sakidashi', 'ScrapVerificationRuncardController@fn_select_svr_datatable_sakidashi');
Route::post('/insert_svr_details', 'ScrapVerificationRuncardController@fn_insert_svr_details');
Route::get('/select_svr_details', 'ScrapVerificationRuncardController@fn_select_svr_details');
Route::get('/select_list_svr_parts', 'ScrapVerificationRuncardController@fn_select_list_svr_parts');
Route::post('/insert_svr_items_details', 'ScrapVerificationRuncardController@fn_insert_svr_items_details');
Route::get('/select_list_svr_pl', 'ScrapVerificationRuncardController@fn_select_list_svr_pl');
Route::get('/select_datatable_svr_parts', 'ScrapVerificationRuncardController@fn_select_datatable_svr_parts');
Route::post('/update_svr_verified', 'ScrapVerificationRuncardController@fn_update_svr_verified');
Route::get('/select_svr_po_details', 'ScrapVerificationRuncardController@fn_select_svr_po_details');
Route::post('/delete_svr', 'ScrapVerificationRuncardController@fn_delete_svr');
Route::post('/delete_svr_item', 'ScrapVerificationRuncardController@fn_delete_svr_item');


Route::get('/fn_get_parts_prep_by_id', 'PartsPreparatoryController@fn_get_parts_prep_by_id');




//C3LABEL
Route::get('/c3labelprinting', 'C3LabelPrintingController@fn_view_c3labelprinting')->name('c3labelprintingalias');
Route::get('/c3labelprintingtest', 'C3LabelPrintingController@fn_view_c3labelprintingtest')->name('c3labelprintingaliastest');
Route::get('/select_c3_devices', 'C3LabelPrintingController@fn_select_c3_devices');
Route::get('/select_c3_label_content_to_print', 'C3LabelPrintingController@fn_select_c3_label_content_to_print');
Route::get('/select_c3_label_content_to_print_partial', 'C3LabelPrintingController@fn_select_c3_label_content_to_print_partial');
Route::post('/insert_c3_label_history', 'C3LabelPrintingController@fn_insert_c3_label_history');
Route::get('/select_c3_label_dt', 'C3LabelPrintingController@fn_select_c3_label_dt');
Route::get('/select_c3_label_content_to_reprint', 'C3LabelPrintingController@fn_select_c3_label_content_to_reprint');
Route::get('/select_c3_label_history_details_dt', 'C3LabelPrintingController@fn_select_c3_label_history_details_dt');
Route::post('/update_c3_label_history_details_receive', 'C3LabelPrintingController@fn_update_c3_label_history_details_receive');
Route::get('/select_to_receive_dt', 'C3LabelPrintingController@fn_select_to_receive_dt');

Route::get('/c3labelprintingforbox', 'C3LabelPrintingController@fn_view_c3labelprintingforbox')->name('c3labelprintingforboxalias');
Route::get('/select_packing_code_dt', 'C3LabelPrintingController@fn_select_packing_code_dt');
Route::get('/select_c3_label_content_to_print_accessories', 'C3LabelPrintingController@fn_select_c3_label_content_to_print_accessories');
Route::get('/select_c3_label_history_details_accessories_dt', 'C3LabelPrintingController@fn_select_c3_label_history_details_accessories_dt');




Route::get('/print_c3_label', 'DLABELPrintingController@fn_print_c3_label');

// ACCESSORY
Route::get('/accessory', 'AccessoryTagPrintingController@fn_view_c3labelprinting')->name('accessoryalias');
Route::get('/select_datatable_accessory_recent_po', 'AccessoryTagPrintingController@fn_select_datatable_accessory_recent_po');
Route::get('/select_datatable_accessory', 'AccessoryTagPrintingController@fn_select_datatable_accessory');
Route::get('/select_accessory_details', 'AccessoryTagPrintingController@fn_select_accessory_details');
Route::post('/insert_accessory_tag', 'AccessoryTagPrintingController@fn_insert_accessory_tag');
Route::get('/select_accessory_name_list', 'AccessoryTagPrintingController@fn_select_accessory_name_list');
Route::get('/select_accessory_wbs_item_list', 'AccessoryTagPrintingController@fn_select_accessory_wbs_item_list');
Route::post('/insert_accessory_tag_item', 'AccessoryTagPrintingController@fn_insert_accessory_tag_item');
Route::post('/delete_accessory_item', 'AccessoryTagPrintingController@fn_delete_accessory_item');



// DLABEL PRINTING
Route::get('/dlabelprinting', 'DLABELPrintingController@fn_view_dlabelprinting')->name('dlabelprintingalias');
Route::get('/select_dlabel_content', 'DLABELPrintingController@fn_select_dlabel_content');
Route::get('/select_dlabel_content_to_print', 'DLABELPrintingController@fn_select_dlabel_content_to_print');
Route::post('/insert_dlabel_history', 'DLABELPrintingController@fn_insert_dlabel_history');
Route::get('/select_recent_print', 'DLABELPrintingController@fn_select_recent_print');
Route::get('/select_shipment_confirmation_dlabel', 'ShipmentConfirmationController@fn_select_shipment_confirmation_dlabel');
Route::post('/delete_dlabel_history', 'DLABELPrintingController@fn_delete_dlabel_history');


// DLABEL CHECKER
Route::get('/dlabelchecker', 'DLABELPrintingController@fn_dlabelchecker')->name('dlabelcheckeralias');
Route::post('/insert_d_label_checker_history', 'DLABELPrintingController@fn_insert_d_label_checker_history');
Route::get('/select_d_label_checker_history', 'DLABELPrintingController@fn_select_d_label_checker_history');
Route::get('/select_d_label_checker_details', 'DLABELPrintingController@fn_select_d_label_checker_details');


// MASTER BOX QUANTITY DETAILS
Route::get('/master_box_quantity_details', 'DLABELPrintingController@fn_view_dlabelprinting')->name('master_box_quantity_details');

//PPC - SHIPMENT CONFIRMATION
Route::get('/shipmentconfirmation', 'ShipmentConfirmationController@fn_view_shipmentconfirmation')->name('shipmentconfirmationalias');
Route::post('/upload_file_shipment_confirmation', 'ShipmentConfirmationController@fn_upload_file_shipment_confirmation');
Route::post('/insert_shipment_confirmation', 'ShipmentConfirmationController@fn_insert_shipment_confirmation');
Route::post('/delete_shipment_confirmation', 'ShipmentConfirmationController@fn_delete_shipment_confirmation');
Route::get('/select_shipment_confirmation', 'ShipmentConfirmationController@fn_select_shipment_confirmation');
Route::get('/select_shipment_confirmation_details', 'ShipmentConfirmationController@fn_select_shipment_confirmation_details');
Route::post('/update_shipment_confirmation', 'ShipmentConfirmationController@fn_update_shipment_confirmation');
Route::get('/select_shipment_confirmation_history', 'ShipmentConfirmationController@fn_select_shipment_confirmation_history');


// PACKING AND SHIPPING
Route::get('/packingandshipping', 'PackingAndShippingController@fn_packingandshipping')->name('packingandshippingalias');
// Route::get('/check_session_expired', 'DLABELPrintingController@fn_check_session_expired');


// PRODUCTION RUNCARD CONTROLLER
Route::get('/select_prod_runcard_materials', 'ProductionRuncardController@fn_view_materials');
Route::post('/save_material', 'ProductionRuncardController@save_material');
Route::get('/view_materials_by_runcard_id', 'ProductionRuncardController@view_materials_by_runcard_id');

Route::get('/get_prod_runcard_by_po', 'ProductionRuncardController@get_prod_runcard_by_po');
Route::get('/get_prod_runcard_by_po_per_runcard', 'ProductionRuncardController@get_prod_runcard_by_po_per_runcard');
Route::get('/get_prod_runcard_by_po_test', 'ProductionRuncardController@get_prod_runcard_by_po_test'); // Palitan para sa live
Route::get('/get_prod_runcard_by_po_test_new', 'ProductionRuncardController@get_prod_runcard_by_po_test_new'); // 10072022
Route::get('/checkPITSPilotRunByPONo', 'ProductionRuncardController@checkPITSPilotRunByPONo');

Route::get('/view_sakidashi_prod', 'ProductionRuncardController@fn_view_sakidashi_prod');
Route::get('/view_emboss_prod', 'ProductionRuncardController@fn_view_emboss_prod');
Route::get('/select_prod_runcard_setup_stations', 'ProductionRuncardController@fn_view_setup_stations');
Route::get('/select_prod_runcard_stations', 'ProductionRuncardController@fn_view_prod_runcard_stations');
Route::get('/select_prod_runcard_station_details', 'ProductionRuncardController@fn_select_prod_runcard_station_details');
Route::get('/select_prod_runcard_material_details', 'ProductionRuncardController@fn_select_prod_runcard_material_details');
Route::post('/insert_prod_runcard', 'ProductionRuncardController@fn_insert_prod_runcard');
Route::post('/update_prod_runcard_secondary', 'ProductionRuncardController@fn_update_prod_runcard_secondary');
Route::post('/insert_prod_runcard_setup_stations', 'ProductionRuncardController@fn_insert_setup_stations');
Route::post('/update_prod_runcard_station_details', 'ProductionRuncardController@fn_update_prod_runcard_station_details');
Route::get('/get_prod_runcards', 'ProductionRuncardController@fn_get_prod_runcards');
Route::post('/update_prod_runcard_approval_prod', 'ProductionRuncardController@fn_update_prod_runcard_approval_prod');
Route::post('/update_prod_runcard_approval_prod', 'ProductionRuncardController@fn_update_prod_runcard_approval_prod');
Route::post('/update_prod_runcard_approval_qc', 'ProductionRuncardController@fn_update_prod_runcard_approval_qc');
Route::get('/view_warehouse_sakidashi_issuance', 'ProductionRuncardController@fn_view_warehouse_sakidashi_issuance');
Route::get('/get_wbs_material_kitting', 'ProductionRuncardController@get_wbs_material_kitting');
Route::get('/get_wbs_sakidashi_issuance', 'ProductionRuncardController@get_wbs_sakidashi_issuance');
Route::get('/get_prod_runcard_by_id', 'ProductionRuncardController@get_prod_runcard_by_id');
Route::post('/save_prod_material_list', 'ProductionRuncardController@save_prod_material_list');
Route::post('/save_prod_emboss_material_list', 'ProductionRuncardController@save_prod_emboss_material_list');

Route::get('/get_wbs_material_kitting_rev', 'ProductionRuncardController_rev@get_wbs_material_kitting_rev'); //-792021


Route::get('/test_prod_runcard_station_step_num', 'ProductionRuncardController@test_prod_runcard_station_step_num');
Route::get('/view_ophrs', 'ProductionRuncardController@view_ophrs');
Route::get('/view_ddrs', 'ProductionRuncardController@view_ddrs');

Route::get('/testonly', 'PartsPreparatoryController@testonly');

// PRODUCTION RUNCARD CONTROLLER
Route::get('/select_prod_runcards_stations', 'ProductionRuncardController@select_prod_runcards_stations');

Route::get('/warehouse_view_batches', 'ProductionRuncardController@fn_warehouse_view_batches');
Route::get('/fn_get_warehouse_view_batches_by_id', 'ProductionRuncardController@fn_fn_get_warehouse_view_batches_by_id');
Route::get('/get_warehouse_sakidashi_view_batches_by_id', 'ProductionRuncardController@fn_get_warehouse_sakidashi_view_batches_by_id');
Route::get('/view_ng_summary', 'ProductionRuncardController@view_ng_summary');
Route::get('/get_mat_kit_issuance_by_lot_no', 'ProductionRuncardController@get_mat_kit_issuance_by_lot_no');
Route::get('/generate_prod_runcard_qrcode', 'ProductionRuncardController@generate_prod_runcard_qrcode');
Route::get('/generate_auto_sticker', 'ProductionRuncardController@generate_auto_sticker');

Route::get('/generate_defect_escalation_qrcode', 'DefectEscalationController@generate_defect_escalation_qrcode');





// AUTOMATIC PRODUCTION RUNCARD CONTROLLER
Route::get('/view_material_kitting_by_runcard', 'ProductionRuncardController@view_material_kitting_by_runcard');
Route::get('/view_sakidashi_by_runcard', 'ProductionRuncardController@view_sakidashi_by_runcard');
Route::get('/view_emboss_by_runcard', 'ProductionRuncardController@view_emboss_by_runcard');
Route::get('/scan_material_kitting_lot_no', 'ProductionRuncardController@scan_material_kitting_lot_no');
Route::get('/scan_sakidashi_issuance_lot_no', 'ProductionRuncardController@scan_sakidashi_issuance_lot_no');
Route::get('/scan_emboss_lot_no', 'ProductionRuncardController@scan_emboss_lot_no');
Route::get('/scan_employee_no', 'ProductionRuncardController@scan_employee_no');
Route::get('/view_prod_runcard_stations', 'ProductionRuncardController@view_prod_runcard_stations');
Route::post('/delete_station', 'ProductionRuncardController@delete_station');
Route::post('/edit_prod_runcard_station', 'ProductionRuncardController@edit_prod_runcard_station');
Route::post('/edit_prod_runcard_station_with_defect', 'ProductionRuncardController@edit_prod_runcard_station_with_defect'); // mdr 11/07/2025
Route::post('/edit_prod_runcard_station_test', 'ProductionRuncardController@edit_prod_runcard_station_test'); // Change for live
Route::post('/save_prod_runcard_station', 'ProductionRuncardController@save_prod_runcard_station');
Route::post('/delete_runcard_station', 'ProductionRuncardController@delete_runcard_station');

// MANUAL PRODUCTION RUNCARD CONTROLLER
Route::get('/get_submitted_prod_runcard_details', 'ProductionRuncardController@get_submitted_prod_runcard_details'); //nmodify
Route::post('/save_prod_runcard_details', 'ProductionRuncardController@save_prod_runcard_details');
Route::post('/save_prod_runcard_details_with_defect', 'ProductionRuncardController@save_prod_runcard_details_with_defect');
Route::post('/submit_to_oqc_lot_app', 'ProductionRuncardController@submit_to_oqc_lot_app');
Route::get('/view_manual_material_kitting_by_runcard', 'ProductionRuncardController@view_manual_material_kitting_by_runcard');
Route::get('/view_manual_sakidashi_by_runcard', 'ProductionRuncardController@view_manual_sakidashi_by_runcard');
Route::get('/view_manual_emboss_by_runcard', 'ProductionRuncardController@view_manual_emboss_by_runcard');
Route::post('/save_material_kitting_lot_issuance', 'ProductionRuncardController@save_material_kitting_lot_issuance');
Route::post('/save_sakidashi_lot_issuance', 'ProductionRuncardController@save_sakidashi_lot_issuance');
Route::post('/save_emboss_lot_issuance', 'ProductionRuncardController@save_emboss_lot_issuance');
Route::get('/get_ddr', 'ProductionRuncardController@get_ddr');
Route::get('/check_material_no', 'ProductionRuncardController@check_material_no'); // uncomment 01-27-2023 710PM
Route::get('/check_material_no_test', 'ProductionRuncardController@check_material_no_test'); // Change for live
Route::get('/check_material_no_LIVE', 'ProductionRuncardController@check_material_no_LIVE'); // Change for live
Route::get('/check_material_no_defect', 'ProductionRuncardController@check_material_no_defect');

Route::get('/get_first_lot_data_by_po_no', 'ProductionRuncardController_rev@get_first_lot_data_by_po_no');
Route::get('/checkAllProcessIsDone', 'ProductionRuncardController@checkAllProcessIsDone');


//OQC INSPECTOR CONTROLLER
Route::get('/oqc_view_runcards', 'OqcInspectorController@view_runcards');


// DEFECT ESCALATION CONTROLLER
Route::get('/get_defect_escalation_by_po', 'DefectEscalationController@get_defect_escalation_by_po');
Route::post('/save_defect_escalation_details', 'DefectEscalationController@save_defect_escalation_details');
Route::post('/save_defect_escalation_details_live', 'DefectEscalationController@save_defect_escalation_details_live');
Route::get('/get_defect_escalation_by_id', 'DefectEscalationController@get_defect_escalation_by_id');
Route::post('/defect_escalation_save_material', 'DefectEscalationController@defect_escalation_save_material');
Route::get('/view_materials_by_defect_escalation_id', 'DefectEscalationController@view_materials_by_defect_escalation_id');
Route::post('/edit_de_station', 'DefectEscalationController@edit_de_station');
Route::get('/view_de_stations', 'DefectEscalationController@view_de_stations');
Route::post('/submit_de_to_oqc_lot_app', 'DefectEscalationController@submit_de_to_oqc_lot_app');
Route::get('/view_de_stations', 'DefectEscalationController@view_de_stations');
//nmodify MIGZ
Route::get('/fn_is_prod_runcard_num_is_ng', 'DefectEscalationController@fn_is_prod_runcard_num_is_ng');
Route::get('/fn_search_defect_runcard_number', 'DefectEscalationController@fn_search_defect_runcard_number');


// REWORK
Route::post('/save_rework', 'DefectEscalationController@save_rework');
Route::post('/save_rework_live', 'DefectEscalationController@save_rework_live');
Route::post('/save_rework_verification', 'DefectEscalationController@save_rework_verification');
Route::get('/view_reworks', 'DefectEscalationController@view_reworks');
Route::get('/view_reworks2', 'DefectEscalationController@view_reworks2');
Route::get('/get_rework_by_id', 'DefectEscalationController@get_rework_by_id');
Route::get('/get_rework_by_id_live', 'DefectEscalationController@get_rework_by_id_live');

/**
 * Added by -JD
 * for Defect Escalation controller
 */
Route::post('/save_analysis', 'DefectEscalationController@save_analysis');
Route::get('/view_defect_escalation_analysis', 'DefectEscalationController@view_defect_escalation_analysis');
Route::get('/get_defect_escalation_analysis_by_id', 'DefectEscalationController@get_defect_escalation_analysis_by_id');




//------------------------------------------------------

Route::get('/packop_oqc_vir_details', 'PackingOperatorController@packop_oqc_vir_details');
Route::get('/packop_get_oqcvir_summary', 'PackingOperatorController@packop_get_oqcvir_summary');

//------------------------------------------------------

//PACKING OPERATOR CONTROLLER
Route::get('/packop_view_batches', 'PackingOperatorController@view_batches');
Route::post('/submit_packop', 'PackingOperatorController@submit_packop');
Route::get('/retrieve_oqc_details', 'PackingOperatorController@retrieve_oqc_details');
Route::get('/retrieve_packop_history', 'PackingOperatorController@retrieve_packop_history');
Route::get('/generate_packing_code', 'PackingOperatorController@generate_packing_code');
Route::get('/link_to_packing_code_packop', 'PackingOperatorController@link_to_packing_code_packop');


//PACKING INSPECTOR CONTROLLER
Route::get('/packin_view_batches', 'PackingInspectorController@view_batches');
Route::post('/submit_packin', 'PackingInspectorController@submit_packin');
Route::get('/retrieve_pack_code_from_packop', 'PackingInspectorController@retrieve_pack_code_from_packop');
Route::get('/link_to_packing_code_packin', 'PackingInspectorController@link_to_packing_code_packin');

Route::get('/retrieve_oqc_name', 'PackingInspectorController@retrieve_oqc_name');
Route::get('/retrieve_packin_history', 'PackingInspectorController@retrieve_packin_history');

//SHIPPING OPERATOR CONTROLLER
Route::get('/shipop_view_batches', 'ShippingOperatorController@view_batches');
Route::post('/submit_shipop', 'ShippingOperatorController@submit_shipop');
Route::get('/retrieve_shipop_history', 'ShippingOperatorController@retrieve_shipop_history');
Route::get('/link_to_packing_code_shipop', 'ShippingOperatorController@link_to_packing_code_shipop');

//SHIPPING INSPECTOR CONTROLLER
Route::get('/shipin_view_batches', 'ShippingInspectorController@view_batches');
Route::post('/submit_shipin', 'ShippingInspectorController@submit_shipin');
Route::get('/retrieve_shipin_history', 'ShippingInspectorController@retrieve_shipin_history');
Route::get('/link_to_packing_code_shipin', 'ShippingInspectorController@link_to_packing_code_shipin');

// WBS MATERIAL KITTING CONTROLLER
Route::get('/get_wbs_kitting_details', 'WBSMaterialKittingController@get_wbs_kitting_details');
Route::get('/get_wbs_kitting_details_by_po_no', 'WBSMaterialKittingController@get_wbs_kitting_details_by_po_no');

// WBS SAKIDASHI CONTROLLER
Route::get('/get_wbs_sakidashi_details', 'WBSSakidashiController@get_wbs_sakidashi_details');

// Additonal Routes - Laravel Test
Route::get('/generate_reel_lot_no', 'ProductionRuncardController@generate_reel_lot_no');

//NEW CONTROLLER FOR PACKIN
Route::get('/view_packop_history_by_packing_code', 'PackingInspectorController@view_packop_history_by_packing_code');
Route::get('/view_oqcvir_history_by_packing_code', 'PackingInspectorController@view_oqcvir_history_by_packing_code');
Route::get('/view_lotapp_history_by_packing_code', 'PackingInspectorController@view_lotapp_history_by_packing_code');
Route::get('/retrieve_c3_label_checker', 'PackingInspectorController@retrieve_c3_label_checker');
Route::get('/retrieve_c3_label_details', 'PackingInspectorController@retrieve_c3_label_details');

//NEW CONTROLLER FOR SHIPOP
Route::get('/view_packin_history_by_packing_code', 'ShippingOperatorController@view_packin_history_by_packing_code');

//NEW CONTROLLER FOR SHIPIN
Route::get('/view_shipop_history_by_packing_code', 'ShippingInspectorController@view_shipop_history_by_packing_code');
Route::get('/view_shipin_history_by_packing_code', 'ShippingInspectorController@view_shipin_history_by_packing_code');

//PACKING SEEDER
Route::post('/submit_seeder', 'ShippingInspectorController@submit_seeder');

Route::get('/export_packing_and_shipment_record', 'ShippingInspectorController@export_packing_and_shipment_record');

// Added by Anjo
Route::get('/excel_export_packing_and_shipment', 'ProductionRuncardController@excel_export_packing_and_shipment');

//PACKOP ADDITIONAL CONTROLLERS
Route::get('/packop_view_reel_lot_no','PackingOperatorController@packop_view_reel_lot_no');
Route::get('/get_reel_lot_count','PackingOperatorController@get_reel_lot_count');

Route::get('/search_device_to_export_report','ShippingInspectorController@search_device_to_export_report');

//prod prelim inspection controller
Route::get('/retrieve_reel_lot_no_from_packing_code','ProdPrelimInspectionController@retrieve_reel_lot_no_from_packing_code');
Route::get('/check_exist_pack_code','ProdPrelimInspectionController@check_exist_pack_code');
Route::get('/ppo_retrieve_r_drawing','ProdPrelimInspectionController@ppo_retrieve_r_drawing');
Route::get('/ppo_view_batches','ProdPrelimInspectionController@view_batches');
Route::get('/ppo_view_pack_code_history','ProdPrelimInspectionController@ppo_view_pack_code_history');
Route::get('/check_if_supervisor','ProdPrelimInspectionController@check_if_supervisor');

//oqc prelim inspection controller
Route::get('/oqc_view_batches','OQCPrelimInspectionController@view_batches');
Route::get('/oqc_view_history','OQCPrelimInspectionController@view_history');
Route::get('/oqc_check_exist_pack_code','OQCPrelimInspectionController@oqc_check_exist_pack_code');
Route::get('/oqc_retrieve_data_from_pack_code','OQCPrelimInspectionController@oqc_retrieve_data_from_pack_code');
Route::get('/oqc_check_if_supervisor','OQCPrelimInspectionController@oqc_check_if_supervisor');
Route::post('/submit_oqc_inspection','OQCPrelimInspectionController@submit_oqc_inspection');
Route::post('/submit_shipping_details','OQCPrelimInspectionController@submit_shipping_details');
Route::post('/submit_oqc_inspection_supervisor','OQCPrelimInspectionController@submit_oqc_inspection_supervisor');

//- 03042020
Route::get('/exportpartslotrecord', function () {
    return view('exportpartslotrecord');
})->name('exportpartslotrecord');

Route::get('/export_parts_lot_record', 'OQCLotAppController@export_parts_lot_record');


//TS OQC LOT APPLICATION ROUTES
Route::get('/load_oqclotapp_table', 'OQCLotApplicationTSController@load_oqclotapp_table');
Route::get('/load_oqclotapp_history_table', 'OQCLotApplicationTSController@load_oqclotapp_history_table');
Route::get('/view_oqclotapp_ts', 'OQCLotApplicationTSController@view_oqclotapp_ts');
Route::post('/add_oqc_lotapplication_ts', 'OQCLotApplicationTSController@add_oqc_lotapplication_ts');
Route::get('/get_lot_app_by_id','OQCLotApplicationTSController@get_lot_app_by_id');

//TS OQCVIR ROUTES
Route::get('/load_oqcvir_table', 'OQCVIRTSController@load_oqcvir_table');
Route::get('/view_start_inspection', 'OQCVIRTSController@view_start_inspection');
Route::post('/add_start_visual_inspection', 'OQCVIRTSController@add_start_visual_inspection');
Route::post('/submit_inspection_result', 'OQCVIRTSController@submit_inspection_result');

//QC PACKING INSPECITON
Route::get('/search_packing_confirmation_lot','OQCVIRTSController@search_packing_confirmation_lot');
Route::get('/load_packing_confirmation_lots','OQCVIRTSController@load_packing_confirmation_lots');
Route::get('/load_packing_inspection_table','OQCVIRTSController@load_packing_inspection_table');
Route::get('/load_packing_inspection_details','OQCVIRTSController@load_packing_inspection_details');
Route::get('/load_final_packing_inspection_details','OQCVIRTSController@load_final_packing_inspection_details');
Route::get('/load_packing_inspection_lots_table','OQCVIRTSController@load_packing_inspection_lots_table');
Route::post('/submit_packing_confirmation','OQCVIRTSController@submit_packing_confirmation');
Route::post('/submit_packing_inspection','OQCVIRTSController@submit_packing_inspection');
Route::get('/check_packing_operator','OQCVIRTSController@check_packing_operator');
Route::get('/check_packing_inspector','OQCVIRTSController@check_packing_inspector');
Route::post('/submit_final_packing_inspection','OQCVIRTSController@submit_final_packing_inspection');


// KITTING CONTROLLER
Route::post('/change_kitting_stat', 'KittingController@change_kitting_stat')->name('change_kitting_stat');
Route::get('/view_kittings', 'KittingController@view_kittings');
Route::post('/add_kitting', 'KittingController@add_kitting');
Route::get('/get_kitting_by_id', 'KittingController@get_kitting_by_id');
Route::post('/edit_kitting', 'KittingController@edit_kitting');
Route::get('/get_kittings', 'KittingController@get_kittings');
Route::get('/get_kitting_by_batch', 'KittingController@get_kitting_by_batch');
Route::get('/get_kitting_info_by_issuance_no', 'KittingController@get_kitting_info_by_issuance_no');
Route::get('/get_kitting_details_by_issuance_no', 'KittingController@get_kitting_details_by_issuance_no');
Route::get('/get_issuance_details_by_issuance_no', 'KittingController@get_issuance_details_by_issuance_no');
Route::get('/save_sub_kitting', 'KittingController@save_sub_kitting');
Route::get('/edit_sub_kitting', 'KittingController@edit_sub_kitting');

Route::get('/view_sub_kitting_by_kitting_id', 'KittingController@view_sub_kitting_by_kitting_id');
Route::get('/generate_sub_kit_qrcode', 'KittingController@generate_sub_kit_qrcode');

//NEW OQC LOT APP CONTROLLER
Route::get('load_oqclotapp_new_table','OqcLotappNewController@load_oqclotapp_new_table');
Route::get('get_runcard_details','OqcLotappNewController@get_runcard_details');
Route::get('get_rework_details','OqcLotappNewController@get_rework_details');
Route::get('load_oqclotapp_runcards_from_array','OqcLotappNewController@load_oqclotapp_runcards_from_array');
Route::get('load_current_po_details','OqcLotappNewController@load_current_po_details');
Route::get('load_device_details','OqcLotappNewController@load_device_details');
Route::post('submit_oqc_runcards','OqcLotappNewController@submit_oqc_runcards');
Route::get('search_user_details','OqcLotappNewController@search_user_details');
Route::get('load_oqclotapp_fvo_from_array','OqcLotappNewController@load_oqclotapp_fvo_from_array');
Route::get('retrieve_lotapp_details','OqcLotappNewController@retrieve_lotapp_details');
Route::post('submit_oqc_lot_application','OqcLotappNewController@submit_oqc_lot_application');
Route::get('load_oqclotapp_runcards','OqcLotappNewController@load_oqclotapp_runcards');
Route::get('load_oqclotapp_fvo','OqcLotappNewController@load_oqclotapp_fvo');
Route::get('load_qr_code_details','OqcLotappNewController@load_qr_code_details');
Route::get('load_oqclotapp_additional_runcards','OqcLotappNewController@load_oqclotapp_additional_runcards');
Route::get('edit_add_runcard','OqcLotappNewController@edit_add_runcard');
Route::get('edit_add_rework','OqcLotappNewController@edit_add_rework');
Route::get('save_runcard_changes','OqcLotappNewController@save_runcard_changes');
Route::get('load_oqlotapp_history','OqcLotappNewController@load_oqlotapp_history');
Route::post('submit_oqc_lot_application_sub','OqcLotappNewController@submit_oqc_lot_application_sub');

Route::get('load_bulk_runcards','OqcLotappNewController@load_bulk_runcards');
Route::get('load_bulk_reworks','OqcLotappNewController@load_bulk_reworks');
Route::get('load_single_application_table','OqcLotappNewController@load_single_application_table');

//NEW OQC VIR CONTROLLER
Route::get('/load_new_oqcvir_table','OQCVIRNewController@load_new_oqcvir_table');
Route::get('/load_new_lotapp_details','OQCVIRNewController@load_new_lotapp_details');
Route::post('/submit_start_inspection','OQCVIRNewController@submit_start_inspection');
Route::get('/search_inspector','OQCVIRNewController@search_inspector');
Route::post('/submit_oqc_vir','OQCVIRNewController@submit_oqc_vir');
Route::get('/load_single_oqcvir_table','OQCVIRNewController@load_single_oqcvir_table');

//NEW PACKING INSPECTION CONTROLLER
Route::get('/search_confirmation_lotapp','PackingInspectionNewController@search_confirmation_lotapp');
Route::get('/load_new_packing_confirmation_lots','PackingInspectionNewController@load_new_packing_confirmation_lots');
Route::post('/submit_confirmation_lots','PackingInspectionNewController@submit_confirmation_lots');
Route::get('/load_new_packinginspection_table','PackingInspectionNewController@load_new_packinginspection_table');
Route::get('/load_packing_inspection_details','PackingInspectionNewController@load_packing_inspection_details');
Route::post('/submit_prelim_inspection2','PackingInspectionNewController@submit_prelim_inspection2');
Route::get('/load_packing_operator_details','PackingInspectionNewController@load_packing_operator_details');
Route::post('/submit_final_packing_inspection2','PackingInspectionNewController@submit_final_packing_inspection2');

Route::get('/view_process_flow_by_device_id', 'ProcessFlowController@view_process_flow_by_device_id');
// Route::get('/view_process_flow_by_device_id_test', 'ProcessFlowController@view_process_flow_by_device_id_test'); // Change for live
Route::post('/save_process_flow', 'ProcessFlowController@save_process_flow');
// Route::post('/save_process_flow_test', 'ProcessFlowController@save_process_flow_test'); // Change for live
Route::post('/save_process_flow_machine', 'ProcessFlowController@save_process_flow_machine');
Route::get('/get_process_flow_by_id', 'ProcessFlowController@get_process_flow_by_id');
Route::post('/import_process_flow', 'ProcessFlowController@import_process_flow');
Route::get('/get_process_flows', 'ProcessFlowController@get_process_flows');
Route::post('/change_process_flow_stat', 'ProcessFlowController@change_process_flow_stat');
Route::post('/change_approval_status', 'ProcessFlowController@change_approval_status'); // 01-06-2023 -JD
Route::post('/material_change_approval_status', 'ProcessFlowController@material_change_approval_status'); // 03-18-2023 -JD
Route::get('/process_flow_for_prod', 'ProcessFlowController@process_flow_for_prod');

Route::get('/generateStepNumber', 'ProcessFlowController@generateStepNumber');



/* PILOT RUN */

Route::get('/get_data_by_po_no', 'PilotRunController@get_data_by_po_no');
Route::get('/get_pilotrun_by_po', 'PilotRunController@get_pilotrun_by_po');
Route::get('/get_pilotrun_by_id', 'PilotRunController@get_pilotrun_by_id');
Route::get('/get_pilotrun_b_details', 'PilotRunController@get_pilotrun_b_details');
Route::get('/view_pilotrun_c_details', 'PilotRunController@view_pilotrun_c_details');
Route::get('/get_pilotrun_c_details', 'PilotRunController@get_pilotrun_c_details');
Route::get('/get_pilotrun_d_details', 'PilotRunController@get_pilotrun_d_details');
Route::get('/get_pilotrun_e_details', 'PilotRunController@get_pilotrun_e_details');

Route::post('/add_pilotrun_Adetails', 'PilotRunController@add_pilotrun_Adetails');
Route::post('/add_pilotrun_Bdetails', 'PilotRunController@add_pilotrun_Bdetails');
Route::post('/add_pilotrun_Cdetails', 'PilotRunController@add_pilotrun_Cdetails');
Route::post('/add_pilotrun_Ddetails', 'PilotRunController@add_pilotrun_Ddetails');
Route::post('/add_pilotrun_Edetails', 'PilotRunController@add_pilotrun_Edetails');

Route::get('/get_pilotrun_toprint_by_id', 'PilotRunController@get_pilotrun_toprint_by_id');


/* DRAWING */
Route::get('/get_PackDocdrawing', 'DrawingNoController@get_PackDocdrawing');
Route::get('/get_PackSpecsdrawing', 'DrawingNoController@get_PackSpecsdrawing');
Route::get('/get_OTUDdrawing', 'DrawingNoController@get_OTUDdrawing');
Route::get('/get_WIDdrawing', 'DrawingNoController@get_WIDdrawing');

/* EMPLOYEE DETAILS */
Route::get('/employee_id_checker', 'UserController@employee_id_checker');
Route::get('/load_user_details', 'PilotRunController@load_user_details');


//MIGZ 2023
Route::get('/validate_employeee_id', 'UserController@validate_employeee_id');


//mdr
Route::get('/get_defect_po_details', 'DefectEscalationDarrenController@get_defect_po_details'); // for initial data (po, device name... etc)
Route::get('/get_runcard_with_defects', 'DefectEscalationDarrenController@get_runcard_with_defects'); // production runcard with defects for runcard_dropdown
Route::get('/get_assembly_line_with_defect', 'DefectEscalationDarrenController@get_assembly_line_with_defect'); // for lines
Route::get('/get_defect_runcard_details', 'DefectEscalationDarrenController@get_defect_runcard_details'); // for DataTables
Route::get('/get_defect_runcard_details_new', 'DefectEscalationDarrenController@get_defect_runcard_details_new'); // for DataTables
Route::get('/get_defect_ctrl', 'DefectEscalationDarrenController@get_defect_ctrl');
Route::post('/add_defect_escalation', 'DefectEscalationDarrenController@add_defect_escalation');
Route::post('/add_defect_escalation_test', 'DefectEscalationDarrenController@add_defect_escalation_test');
Route::get('/get_verification_user_ongoing', 'DefectEscalationDarrenController@get_verification_user_ongoing');

Route::get('/get_assembly_lines_for_defect_escalation', 'DefectEscalationDarrenController@get_assembly_lines_for_defect_escalation');

Route::get('/view_defect_details', 'DefectEscalationDarrenController@view_defect_details');
Route::get('/view_defect_details_new', 'DefectEscalationDarrenController@view_defect_details_new');
Route::get('/generate_defect_qrcode', 'DefectEscalationDarrenController@generate_defect_qrcode');


Route::get('/get_runcard_with_defects_v2', 'DefectEscalationV2Controller@get_runcard_with_defects_v2'); // production runcard with defects for runcard_dropdown

Route::get('/get_defect_count', 'DefectEscalationDarrenController@get_defect_count'); // production runcard with defects for runcard_dropdown
