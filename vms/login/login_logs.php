<?php


function connect_to_db(){
	$serverName = "HOAPPSDEVSVR\DEV";
	$connectionInfo = array( "Database"=>"Driver_Monitoring", "UID"=>"sa", "PWD"=>"@Temp123!" );
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	return $conn;
}


function LoginAttempt($username){
	
	//require_once("config.php");
	$conn = connect_to_db();
	$insert = "insert into loginattempts (username,LastLogin,Attempts,computer) values ('".$username."','".date('Y-m-d h:i:s')."','0','".gethostbyaddr($_SERVER['REMOTE_ADDR'])."'); SELECT SCOPE_IDENTITY()";

	$resource = sqlsrv_query($conn,$insert);  
	sqlsrv_next_result($resource); 
	sqlsrv_fetch($resource); 
	$inserted=sqlsrv_get_field($resource, 0);

	return $inserted;

}

function LoginSuccessful($id){


	$conn = connect_to_db();
	$qry = sqlsrv_query($conn,"update loginattempts set status='1',statusName='Login Successfully',updateDate='".date('Y-m-d h:i:s')."' where id='".$id."'");  


}

function AD_user_error($id){

	count_login_error($id);

	$conn = connect_to_db();
	$qry = sqlsrv_query($conn,"update loginattempts set status='3',statusName='Invalid AD Account',updateDate='".date('Y-m-d h:i:s')."' where id='".$id."'");  

}

function app_user_error($id){

	count_login_error($id);

	$conn = connect_to_db();
	$qry = sqlsrv_query($conn,"update loginattempts set status='2',statusName='Account has no access',updateDate='".date('Y-m-d h:i:s')."' where id='".$id."'");  

}

function count_login_error($id){


	$conn = connect_to_db();
	$d = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from loginattempts where id='".$id."'"));

	$counter = 0;
	$qry = sqlsrv_query($conn,"select * from loginattempts where computer='".$d['computer']."' order by id desc");
	while($r = sqlsrv_fetch_array($qry)){
		if($r['status'] == 1){
			break;
		}
		else{
			$counter++;
		}
	}

	if($counter>=3){
		lock_computer($d['Username']);
	}
	
}

function lock_computer($username){


	$conn = connect_to_db();
	$user = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where username='".$username."'"));

	if($user['username']){
		date_default_timezone_set("Asia/Manila");
		$lock = sqlsrv_query($conn,"update users set isLocked='1',lockedOn='".date('Y-m-d H:i:s')."' where username='".$user['username']."'");
	}
	
}

function check_if_locked($username){


	$conn = connect_to_db();
	$user = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where username='".$username."'"));



	if($user['isLocked']==1){
		header("location: ../login/locked.php");
	}

}


?>