<?php 
function getAvailabilityPerVehicle($id,$start,$end){
	// $serverName = "172.16.20.43";
	$serverName = "172.16.20.28";
	$connectionInfo = array( "Database"=>"vmsdb20200718", "UID"=>"sa", "PWD"=>"@Temp123!" );
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	$r=sqlsrv_fetch_array(sqlsrv_query($conn,"select sum(mins) as tmin from downtimeflatdata where date>='".$start."' and date<='".$end."' and unitId='".$id."'"));
	$intervalss  = abs(strtotime($start) - strtotime($end));
	$minuted   = round($intervalss / 60);
	$houred = $minuted / 60;
	$downtime = $r['tmin'] / 60;
	$availability = $houred - $downtime;

	return $availability;
}

function computemins($s,$e){	
	$from_time = strtotime($s);
	$to_time = strtotime($e);
	return round(abs($to_time - $from_time) / 60,0);
}

function interval( $startDate , $endDate, $type ){
	if($type=='Weekly'){
		if(date('D', strtotime($startDate)) === 'Mon') {
			$startDate=$startDate;
		}
		else{
			$startDate=date('Y-m-d', strtotime('last Monday', strtotime($startDate)));
		}
	}
	if($type=='Monthly'){
		if(date('j', strtotime($startDate)) === '1') {
			$startDate=$startDate;
		}
		else{
			$startDate=$startDate;
		}
	}
	$startDate = strtotime( $startDate );
	$endDate   = strtotime( $endDate );


	$currDate  = $startDate;
	$dayArray  = array();


	if($type=='Daily'){
		do{
			$dayArray[] = date( 'Y-m-d' , $currDate );
			$currDate = strtotime( '+1 day' , $currDate );
		} while( $currDate<=$endDate );
	}
	if($type=='Weekly'){
		do{
			$dayArray[] = date( 'Y-m-d' , $currDate );
			$currDate = strtotime( '+1 week' , $currDate );
		} while( $currDate<=$endDate );
	}
	if($type=='Monthly'){
		do{
			$dayArray[] = date( 'Y-m-d' , $currDate );
			$currDate = strtotime( '+1 month' , $currDate );
		} while( $currDate<=$endDate );
	}

	return $dayArray;
}


##################### USER MAINTENANCE ##########################
	function get_all_users(){
		global $conn;
		$query = "SELECT * FROM users WHERE isdepartment = 0";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function lock_selected_user($id){
		global $conn;
		$query = "UPDATE users SET isLocked = 1 WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function unlock_selected_user($id){
		global $conn;
		$query = "UPDATE users SET isLocked = 0 WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function deactivate_selected_user($id){
		global $conn;
		$query = "UPDATE users SET active = 0 WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function activate_selected_user($id){
		global $conn;
		$query = "UPDATE users SET active = 1 WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function update_user($domain,$fname,$role,$dept,$id){
		global $conn;
		$query = "UPDATE users SET domain = '$domain', fullname = '$fname', role = '$role', dept = '$dept' WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function add_user($domain,$fname,$role,$dept,$isLocked,$active){
		global $conn;
		$query = "INSERT INTO users (domain,fullname,role,dept,isLocked,active) VALUES ('$domain','$fname','$role','$dept','$isLocked','$active') ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	##################### DEPARTMENT USER MAINTENANCE ##########################
	function get_all_dusers(){
		global $conn;
		$query = "SELECT * FROM users WHERE isdepartment = 1";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function lock_selected_duser($id){
		global $conn;
		$query = "UPDATE users SET isLocked = 1 WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function unlock_selected_duser($id){
		global $conn;
		$query = "UPDATE users SET isLocked = 0 WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function deactivate_selected_duser($id){
		global $conn;
		$query = "UPDATE users SET active = 0 WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function activate_selected_duser($id){
		global $conn;
		$query = "UPDATE users SET active = 1 WHERE id = '$id' ";
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function update_duser($domain,$fname,$role,$dept,$id,$department,$password){
		global $conn;
		$query = "UPDATE users SET domain = '$dept', fullname = '$dept', role = '$role', dept = '$dept', dpassword='$password' WHERE id = '$id' ";		
		$result = sqlsrv_query($conn, $query);
		return $result;
	}

	function add_duser($domain,$fname,$role,$dept,$isLocked,$active,$department,$password){
		global $conn;
		$query = "INSERT INTO users (domain,fullname,role,dept,isLocked,active,isdepartment,dpassword) VALUES ('$dept','$dept','$role','$dept','$isLocked','$active','$department','$password') ";
		$result = sqlsrv_query($conn, $query);
		return $result;				
	}

##################### USER MAINTENANCE ##########################

	function add_history($request_id,$remarks){
		global $conn;
		$insert = sqlsrv_query($conn,"insert into request_logs (request_id,action) values ('".$request_id."','".$remarks." ON ".date('Y-m-d h:i A')."')");
	}

	function get_all_fuel_type(){
		global $conn;
		$query = "SELECT * FROM fuel_types";
		$result = sqlsrv_query($conn,$query);
		return $result;

	}

	function get_selected_fuel_type($id){
		global $conn;
		$query = "SELECT * FROM fuel_types WHERE id = '$id' ";
		$result = sqlsrv_query($conn,$query);
		return $result;

	}
?>