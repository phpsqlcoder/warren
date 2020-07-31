<?php
include("config.php");
session_start();
$st=date('Y-m-d');
$et=date('Y-m-d');
$pla=' selected="selected"';
$plp='';
$pld='';
$tdata='';

if(isset($_GET['startdate'])){
	$st=$_GET['startdate'];
	$et=$_GET['enddate'];
	$intervalss  = abs(strtotime($st) - strtotime($et));
	$minuted   = round($intervalss / 60);
	$tdata='';
	$data='';
	$wdata='';
	$tots=0;
	$vc='';
	if($_GET['pl']==1){
		$vc=' and f.isScheduled=1';
		$pla='';
		$plp=' selected="selected"';
		$pld='';

	}
	elseif($_GET['pl']==2){
		$vc=' and f.isScheduled=0';
		$pla='';
		$plp='';
		$pld=' selected="selected"';

	}
	else{
		$pla=' selected="selected"';
		$plp='';
		$pld='';

	}
	$top=sqlsrv_fetch_array(sqlsrv_query($conn,"select  sum(f.mins) as ttmin from downtimeflatdata f right join unit u on u.id=f.unitId 
		where f.date>='".$_GET['startdate']."' and f.date<='".$_GET['enddate']."' ".$vc.""));
	$q=sqlsrv_query($conn,"select u.id as idd,u.name,sum(f.mins) as tmin from downtimeflatdata f right join unit u on u.id=f.unitId 
		where f.date>='".$_GET['startdate']."' and f.date<='".$_GET['enddate']."' ".$vc."
		GROUP BY u.id,u.name
		ORDER BY sum(f.mins) DESC");
	while($r=sqlsrv_fetch_array($q)){
		$tots+=$r['tmin'];
		$perc=($r['tmin']/$minuted)*100;		
		$mins=number_format(100 - $perc).'%';
		$wdata.=$r['idd'].',';
		$tdata.='<tr>
			<td>'.$r['name'].'</td>
			<td>'.number_format($r['tmin']).' mins </td>		
			<td>'.$mins.'</td>
		</tr>';
		
		$data=rtrim($data,",");

	}

}

?>


					<table width="100%" style="font-style:Arial;font-size:14px;">
									<tr>
										<td colspan="3" align="center" style="font-style:Arial;font-size:16px;font-weight:bold;"><br><br>Downtime Report</td>
									</tr>
									<tr>
										
										<td colspan="3" align="center"><?php echo date('F d Y',strtotime($st))." - ".date('F d Y',strtotime($et));?><br><br></td>
									</tr>			
								</tr>
									<tr style="font-weight:bold;color:blue;">
										<td>Unit</td>
										<td>Mins</td>
										
										<td>Availability %</td>
								 	</tr>	
								 	<tr><td colspan="3"><hr></td></tr>							
									<?php echo $tdata;?>
									<tr><td colspan="3"><hr></td></tr>
					</table>