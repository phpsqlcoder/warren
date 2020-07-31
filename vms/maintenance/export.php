<?php
include("../config.php");
session_start();

if(!$_SESSION['esdvms_username']){
	header("location:../login.php");
}

$table='';
if(isset($_GET['act'])){
	if($_GET['act']=='unit'){
		$data = '<tr>
						<td>#</td>
						<td>Name</td>
						<td>Type</td>
						<td>Required Availability Hours</td>
					</tr>';
		$seq = 0;
		$sql = sqlsrv_query($conn,"select * from unit where active=1");
		while($r = sqlsrv_fetch_array($sql)){
			$seq++;
			$data.='
				<tr>
					<td>'.$seq.'</td>
					<td>'.$r['name'].'</td>
					<td>'.$r['type'].'</td>
					<td>'.number_format($r['required_availability_hours'],2).'</td>
				</tr>
			';
		}
		$table = $data;
	}

	if($_GET['act']=='mechanic'){

		$data = '<tr>
					<td>#</td>
					<td>Name</td>						
				</tr>';
		$seq = 0;
		$sql = sqlsrv_query($conn,"select * from mechanics where active=1");
		while($r = sqlsrv_fetch_array($sql)){
			$seq++;
			$data.='
				<tr>
					<td>'.$seq.'</td>
					<td>'.$r['name'].'</td>					
				</tr>
			';
		}
		$table = $data;

	}


	if($_GET['act']=='assigned'){

		$data = '<tr>
					<td>#</td>
					<td>Name</td>						
				</tr>';
		$seq = 0;
		$sql = sqlsrv_query($conn,"select * from assigned where active=1");
		while($r = sqlsrv_fetch_array($sql)){
			$seq++;
			$data.='
				<tr>
					<td>'.$seq.'</td>
					<td>'.$r['name'].'</td>					
				</tr>
			';
		}
		$table = $data;

	}

	if($_GET['act']=='status'){

		$data = '<tr>
					<td>#</td>
					<td>Name</td>						
				</tr>';
		$seq = 0;
		$sql = sqlsrv_query($conn,"select * from unit_status where active=1");
		while($r = sqlsrv_fetch_array($sql)){
			$seq++;
			$data.='
				<tr>
					<td>'.$seq.'</td>
					<td>'.$r['status'].'</td>					
				</tr>
			';
		}
		$table = $data;

	}

	if($_GET['act']=='repair_preventive'){

		$data = '<tr>
					<td>#</td>
					<td>Name</td>						
				</tr>';
		$seq = 0;
		$sql = sqlsrv_query($conn,"select * from repair_preventive where active=1");
		while($r = sqlsrv_fetch_array($sql)){
			$seq++;
			$data.='
				<tr>
					<td>'.$seq.'</td>
					<td>'.$r['name'].'</td>					
				</tr>
			';
		}
		$table = $data;

	}

	if($_GET['act']=='repair_breakdown'){

		$data = '<tr>
					<td>#</td>
					<td>Name</td>						
				</tr>';
		$seq = 0;
		$sql = sqlsrv_query($conn,"select * from repair_breakdown where active=1");
		while($r = sqlsrv_fetch_array($sql)){
			$seq++;
			$data.='
				<tr>
					<td>'.$seq.'</td>
					<td>'.$r['name'].'</td>					
				</tr>
			';
		}
		$table = $data;

	}

	if($_GET['act']=='raw_data'){

		$data = '<tr>
                   <th>ID</th>
                   <th>Unit</th>
                   <th>Category</th>
                   <th>Status</th>
                   <th>Reported</th> 
                   <th>Start</th>
                   <th>End</th>   
                   <th>Assigned To</th>                                    
                   <th>Remarks</th>
                   <th>Type</th>
                   <th>Work Order</th>
                   <th>Repair Type</th>
                   <th>Work Details</th>
                   <th>Mechanics</th>
                   <th>From 12 AM</th>
                   <th>From 7 AM</th>
                   <th>Repair Days</th>
                   <th>Repair Hours</th>
                   <th>Shop Days</th>
                   <th>Shop Hours</th>
                   <th>Man Hours</th>
                   <th>Required Daily Availability</th>
                   <th>Downtime</th>
                   <th>Added By</th>
                   <th>Added Date</th>                                
                </tr>';
		 $lq=sqlsrv_query($conn,"select CONVERT(VARCHAR(19),d.dateStart) as ds,CONVERT(VARCHAR(19),d.dateEnd) as de,CONVERT(VARCHAR(19),d.reportedDate) as reported,CONVERT(VARCHAR(19),d.addedDate) as added,d.*,u.name as uni,u.type                                      
	          from downtime d left join unit u on u.id=d.unitId where ((d.dateStart>='".$_GET['startDate']."' and d.dateEnd<='".$_GET['endDate']." 23:59:59') OR (d.dateEnd>='".$_GET['startDate']."' and d.dateEnd<='".$_GET['endDate']." 23:59:59')) and d.active=1 order by d.id desc");
	       while($l=sqlsrv_fetch_array($lq)){      
	          $type = "";
	          if($l['isScheduled']==1){
	             $type="Corrective/PM";
	          }
	          if($l['isScheduled']==1){
	             $type="Breakdown";
	          }
	          $data.='
	             <tr>
	                <td>'.$l['id'].'</td>
	                <td>'.$l['uni'].'</td>
	                <td>'.$l['type'].'</td>
	                <td>'.$l['status'].'</td>
	                <td>'.date('Y-m-d',strtotime($l['reported'])).'</td>
	                <td>'.$l['ds'].'</td>
	                <td>'.$l['de'].'</td>
	                <td>'.$l['assignedTo'].'</td>
	                <td>'.$l['remarks'].'</td>
	                <td>'.$type.'</td>
	                <td>'.$l['workOrder'].'</td>
	                <td>'.$l['repairType'].'</td>
	                <td>'.$l['workDetails'].'</td>
	                <td>'.str_replace("|", ", ", $l['mechanics']).'</td>
	                <td>'.$l['from12'].'</td>                                                  
	                <td>'.$l['from7'].'</td>
	                <td>'.$l['trepair_days'].'</td>
	                <td>'.$l['trepair_hours'].'</td>
	                <td>'.$l['shop_days'].'</td>
	                <td>'.$l['shop_hours'].'</td>
	                <td>'.$l['man_hours'].'</td>
	                <td>'.$l['required_daily_availability'].'</td>
	                <td>'.$l['tdowntime'].'</td>
	                <td>'.$l['addedBy'].'</td>
	                <td>'.date('Y-m-d H:i:s',strtotime($l['added'])).'</td>
	               
	             </tr>
	          ';
	       }
		$table = $data;

	}

	
}
echo '<table id="maintenance_excel" width="100%" style="font-family:Arial;font-size:12px;">';
echo $table;
echo '</table>';

?>
<script src="../metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="../js/excel/src/jquery.table2excel.js"></script>
<script>
	jQuery(document).ready(function() {
    	
        exportToExcel('#maintenance_excel');

    });   
	function exportToExcel(table){
		jQuery(table).table2excel({
			name: "VMS",
		    filename: "VMS" //do not include extension
		}); 
	}
</script>