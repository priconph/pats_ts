<!DOCTYPE html>
<html>
<head>
    <title>PATS TS - Micron Notification</title>
</head>
<body>
    <p>Good Day!</p>
    <p>New Defect added on Defect Escalation Module</p>
    <p>PO No: <b>{{ $data[0]['defect_escalation']['po_no'] }}</p></b>
    <p>Defect Escalation No: <b>{{ $data[0]['defect_escalation']['defect_escalation_no'] }}</p></b>
    <p>Runcard No: <b>{{ $data[0]['defect_escalation']['runcard_no'] }}</p></b>
    <p>Mode of Defect: <b>{{ $data[0]['mode_of_defect_info']['name'] }}</p></b>
    <p>Unit No: <b>{{ $data[0]['unit_no'] }}</p></b>
    <p>Location of NG: <b>{{ $data[0]['location_of_ng'] }}</p></b>
    <p>NG Qty(PINS): <b>{{ $data[0]['ng_qty'] }}</p></b>
    <p>Operator / Technician Name: <b>{{ $data[0]['def_opt_info']['name'] }}</p></b>
    <p>Date: <b>{{ $data[0]['created_at'] }}</p></b>
    <br>
    <p><b>Note! </b>This is just a system generated email from Product Automated Traceability System TS - Micron. Please do not reply.</p>
    <p>Thank you!</p>
</body>
</html>
