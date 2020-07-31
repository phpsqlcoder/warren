<?php
include("config.php");
if($_GET['act']=="checkinput"){
	$ck=sqlsrv_fetch_array(sqlsrv_query($conn,"select * from downtime where
		unitId='".$_POST['unit']."' and (dateStart<='".$_POST['endd']."' AND dateEnd>='".$_POST['startd']."')"));
	if($ck['id']){
		echo "<div class='alert alert-danger'>
								<strong>Error!</strong> There is already an existing downtime record for these dates.
							</div>";		
	}
	else{
		echo "";
	}	

}
?>