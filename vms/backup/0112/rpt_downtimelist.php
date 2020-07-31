<?php
include("config.php");
ob_start();
session_start();
function computemins($s,$e){	
	$from_time = strtotime($s);
	$to_time = strtotime($e);
	return round(abs($to_time - $from_time) / 60,0);
}
//echo computemins("2016-12-06 10:45","2016-12-06 23:59");die();


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

$adqry='';
if(isset($_GET['start1'])){
	$adqry.=" where d.dateStart>='".$_GET['start1']."' OR d.dateEnd<='".$_GET['end1']."'";
}

?>
<table width="100%" style="font-family:arial;font-size:12px;">
	<tr>
		<td><font color="blue" size="+1">PHILSAGA MINING CORPORATION</font><br>
Purok 1-A, Bayugan 3, Rosario, Agusan del Sur<br><br></td>
		<td align="right" style="font-size:11px;" valign="top"><?php echo date('F d,Y');?></td>
	</tr>
</table>

<table width="100%">
	<tr>
		<td align="center"><font color="blue" size="+1">Downtime List</font><br>
As of: <?php echo date('F d,Y');?><br><br><br><br></td>
		
	</tr>
</table>
					<table width="100%" style="font-family:arial;font-size:12px;">
						<thead>
							<tr align="left">
								<th>Unit</th>
								<th>Start</th>
								<th>End</th>
								<th>Planned</th>
								<th>Total<br>Minutes</th>
								<th>Remarks</th>												
							</tr>
						</thead>
						<tbody>
							<tr><td colspan="6"><hr></td></tr>
					<?php 
						$ldata='';
						
						$lq=sqlsrv_query($conn,"select top 20 CONVERT(VARCHAR(19),d.dateStart) as ds,CONVERT(VARCHAR(19),d.dateEnd) as de,d.*,u.name as uni from downtime d left join unit u on u.id=d.unitId ".$adqry." order by d.id desc");
						while($l=sqlsrv_fetch_array($lq)){
							$is_sched = ($l['isScheduled']==1 ? 'Yes' : 'No');
							echo '
								<tr>
									<td>'.$l['uni'].'</td>
									<td>'.$l['ds'].'</td>
									<td>'.$l['de'].'</td>
									<td>'.$is_sched.'</td>
									<td>'.computemins($l['ds'],$l['de']).'</td>
									<td>'.$l['remarks'].'</td>
								</tr>
							';
						}
					?>
					</tbody>
					</table>				
	<br><br><br><br>
<table width="100%" style="font-family:Arial;font-size:12px;font-weight:bold;">
    <tr>
        <td>Prepared by:</td>
        <td>Checked by:</td>
        <td>Noted by:</td>
    </tr>
    <tr>
        <td>_______________________</td>
        <td>_______________________</td>
        <td>_______________________</td>
    </tr>
</table>