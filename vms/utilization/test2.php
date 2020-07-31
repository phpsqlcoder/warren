<?php
include('../config.php');

$action = $_REQUEST['action'];

if($action =="showAll"){
	
	$stmt = sqlsrv_query($conn,'SELECT * FROM fuel_types');
	
}else{
	
	$stmt = sqlsrv_query($conn,'SELECT * FROM fuel_types WHERE id= "'.$action.'" ');

}
?>

<div class="row">
<?php

while($row = sqlsrv_fetch_array($stmt)){


?>
<div class="col-xs-3">
	<div style="border-radius:10px; border:blue solid 1px; background:azure; color:blue; padding:22px;"><?php echo $row['name']; ?></div>
	<br />
</div>
<?php		
}
?>
</div>