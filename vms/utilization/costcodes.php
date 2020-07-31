<?php
include("../config.php");
$code = $_GET['code'];
$c = sqlsrv_fetch_array(sqlsrv_query($conn,"select count(*) as total From costcodes where [FULL JOB CODE]='".$code."'"));
if($c['total']){
	$rs = 1;
}
else{
	$rs = 0;
}
echo $rs;

?>


