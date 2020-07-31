<?php
include("../config.php");
session_start();
if(!isset($_GET['act'])){
	echo "die";
	die();
}

if($_GET['act']=="get_request_details"){
	$r = sqlsrv_fetch_array(sqlsrv_query($conn,"select *,convert(nvarchar(MAX), date_needed, 20) as need from vehicle_request where id='".$_POST['id']."'"));
	$i = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from request_other_info where request_id='".$_POST['id']."'"));
	$r['request_id'] = $i['request_id'];
	$r['contact_person'] = $i['contact_person'];
	$r['designation'] = $i['designation'];
	$r['dept'] = $i['dept'];
	$r['contact_no'] = $i['contact_no'];
	$r['delivery_site'] = $i['delivery_site'];
	$r['other_instructions'] = $i['other_instructions'];
	$r['pickup_dept'] = $i['pickup_dept'];
	$r['pickup_location'] = $i['pickup_location'];
	$r['need'] = date('Y-m-d H:i',strtotime($r['need']));
	echo json_encode($r);
}
?>