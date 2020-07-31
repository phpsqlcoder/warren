<?php
$serverName = "172.16.20.43";
$connectionInfo = array( "Database"=>"Driver_Monitoring", "UID"=>"sa", "PWD"=>"@Temp123!" );
$conn = sqlsrv_connect($serverName, $connectionInfo);

$drivers = array();
$drivers_qry = sqlsrv_query($conn,"select * from tbl_driver_details where department in ('CIVIL WORKS','CIVIL WORKS & ROAD MAINTENANCE')");
while($r = sqlsrv_fetch_array($drivers_qry)){	
	$drivers[] = $r;
}
echo json_encode($drivers);
?>