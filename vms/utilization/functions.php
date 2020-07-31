<?php 

function refcode($x){
    $r = 'TN-';
    for($i = 1; $i<=(6 - strlen($x)); $i++){
        $r .= "0";
    }
    return $r.$x;
}

function get_dispatch_details($id){
    global $conn;

    $query = "SELECT * FROM vehicle_request WHERE id = '$id' ";
    $result = sqlsrv_fetch_array(sqlsrv_query($conn, $query));
    return $result;
}

function get_details_of_selected_dispatch($id){
    global $conn;

    $query = "SELECT * FROM dispatch WHERE tripTicket = '$id' ";
    $result = sqlsrv_fetch_array(sqlsrv_query($conn, $query));
    return $result;
}


function add_dispatch_details($dept,$type,$dest,$purpose,$passenger,$odom_s,$app_date,$unit,$user,$r_id, $date_out, $driver,$rq,$icode,$ftype,$uom,$req_qty,$costcode){
    global $conn;
    if($odom_s==''){
        $odom_s=0;
    }
    $query = "INSERT INTO dispatch (deptId,
    type,
    destination,
    purpose,
    passengers,
    odometer_start,
    dateStart,
    unitId,
    addedBy,
    addedDate,
    request_id,
    driver_id,
    Status,
    RQ,
    itemCode,
    fuel_added_type,
    uom,
    fuel_requested_qty,
    vehicle_cost_code
    ) 
    VALUES ('$dept','$type','$dest','$purpose','$passenger','$odom_s','$date_out','$unit','$user','".$app_date."','$r_id','".$driver."','In-Progress','$rq','$icode','$ftype','$uom','$req_qty','$costcode'); SELECT SCOPE_IDENTITY() ";

    $result = sqlsrv_query($conn, $query);
    sqlsrv_next_result($result); 
    sqlsrv_fetch($result); 
    $lastins=sqlsrv_get_field($result, 0); 
    $update_tripticket_no = sqlsrv_query($conn,"update dispatch set tripTicket='".refcode($lastins)."' where id='".$lastins."'");

    return $result;   
}



function update_dispatch_details($ticket,$dept,$type,$dest,$purpose,$pass,$odom_s,$app_date,$unit,$vcode){
/*function update_dispatch_details($form){*/
    global $conn;

    $query = "UPDATE dispatch SET 
    deptId           = '$dept',
    type             = '$type',
    destination      = '$dest',
    purpose          = '$purpose',
    passengers       = '$pass',
    odometer_start   = '$odom_s',
    dateStart        = '$app_date',
    vehicle_cost_code= '$vcode',
    unitId           = '$unit' 
    WHERE tripTicket = '$ticket'";
    $result = sqlsrv_query($conn, $query);
    return $result;
}

function update_return_date($odom_e,$ticket,$return_dt,$close,$closed_at,$numberOfTrips,$odom_s){
    global $conn;

    $query = "UPDATE dispatch SET 
    odometer_end      = '$odom_e',
    odometer_start      = '$odom_s',
   
    dateEnd           = '$return_dt',
    Closed_by         = '$close',
    Closed_at         = '$closed_at',
	numberOfTrips         = $numberOfTrips
    WHERE tripTicket  = '$ticket' ";
    $result = sqlsrv_query($conn, $query);
    return $result;
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

// New Variables
    $currDate  = $startDate;
    $dayArray  = array();

// Loop until we have the Array
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
// Return the Array
    return $dayArray;
}


?>