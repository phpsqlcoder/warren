<?php 
include("config.php");

function save($data){

}

function hasNotificationFor($user){
	$q = sqlsrv_query($conn,"select * from notifications where [to]='".$user."' and isNotified='0'");
	$notifications = '';
	while($r=sqlsrv_fetch_array($q)){
		
	}
}

?>