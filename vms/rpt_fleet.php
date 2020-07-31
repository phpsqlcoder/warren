<?php
include("config.php");
session_start();
function computemins($s,$e){	
	$from_time = strtotime($s);
	$to_time = strtotime($e);
	return round(abs($to_time - $from_time) / 60,0);
}
if(!isset($_GET['act'])){
	$_POST['startdate']='2017-06-01';
	$_POST['enddate']=date('Y-m-d');
	$_POST['data']='Per Vehicle';
	$_POST['filter']='All';
	$_POST['equipment']='All';
}
$intervalss  = abs(strtotime($_POST['startdate']) - strtotime($_POST['enddate']));
$minuted   = round(($intervalss / 60)/60);

?>
<html lang="en">

<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Vehicle | Monitoring</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="google.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
<link href="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="metronic/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>


<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
<style>
	.popover-title {
    color: black;
    
	}
	.popover-content {
	    color: black;
	   
	}
</style>
</head>

<body class="page-header-fixed page-full-width">

<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">		
			<div class="row">
				<div class="col-md-12">	
					<form action="rpt_fleet.php?act=generate" method="post">
						<table width="100%">
							<tr>
								<td colspan="4" align="center"> <h1>Fleet Availability Report</h1> </td>
							</tr>
							<tr>
								<td>Start Date</td>
								<td>End Date</td>
								<td>Data</td>
								<td>Equipment</td>
								<td>Filter</td>
							</tr>
					  		<tr>
					  			<td>
					  				<input type="date" name="startdate" class="form-control" max="<?php echo date('Y-m-d');?>" value="<?php echo $_POST['startdate']?>" required>
					  			</td>
					  			<td>
					  				<input type="date" name="enddate" class="form-control" max="<?php echo date('Y-m-d');?>" value="<?php echo $_POST['enddate']?>" required>
					  			</td>
					  			<td>
					  				<select name="data" class="form-control">
							    		<option value="Per Vehicle" <?php if($_POST['data']=='Per Vehicle') echo 'selected="selected"';?> > - Per Vehicle - </option>
							    		<option value="Per Type" <?php if($_POST['data']=='Per Type') echo 'selected="selected"';?>> - Per Type - </option>

							    	</select>
					  			</td>
					  			<td>
					  				<select name="equipment" class="form-control">
							    		<option value="All" <?php if($_POST['data']=='All') echo 'selected="selected"';?> > - All Equipment - </option>
							    		<?php 
							    			$eq = sqlsrv_query($conn,"select distinct equipment from unit order by equipment");
							    			while ($e = sqlsrv_fetch_array($eq)) {
							    				$sel = ($e['equipment'] == $_POST['equipment'] ? 'selected="selected"' : '');
							    				echo '<option value="'.$e['equipment'].'" '.$sel.'> '.$e['equipment'].' </option>';	
							    			}
							    		?>
							    		

							    	</select>
					  			</td>
					  			<td>
					  				<select name="filter" class="form-control">
							    		<option value="All" <?php if($_POST['filter']=='All') echo 'selected="selected"';?>> - All - </option>
							    		<option value="Planned Only" <?php if($_POST['filter']=='Planned Only') echo 'selected="selected"';?>> - Planned Only- </option>
							    		<option value="Unplanned Only" <?php if($_POST['filter']=='Unplanned Only') echo 'selected="selected"';?>> - Unplanned Only- </option>
							    	</select>
					  			</td>
					  			
					  			<td>
					  				<input type="submit" class="btn green" value="Generate">
					  			</td>
					  		</tr>					
					    </table>	
					</form>
				</div>          
			</div>
			<div class="row">
				<div class="col-md-12"><br><br>
				<?php if(isset($_GET['act'])=="generate"){  ?>
					<a href="#" onclick="exportexcel('table1')" class="btn green">Export to excel</a>
					<table width="100%" class="table table-condensed table-hover" style="font-size:12px;" id="table1">
						<thead>
							<tr align="left">
								<th>Seq</th>
								<th>Data</th>
								<th style="text-align:right;">Downtime Hours</th>
								<th style="text-align:right;">Availability (%)</th>																
							</tr>
						</thead>
						<tbody>
							
					<?php 
						$ldata='';
						$seq=0;
						$cond = '';
						$econd = '';
						if($_POST['filter']<>'All'){
							if($_POST['filter']=='Planned Only')
								$cond.=" and d.isScheduled='1'";							
							else
								$cond.=" and d.isScheduled<>'1'";	
						}	
						if($_POST['equipment']<>'All'){							
								$cond.=" and u.equipment='".$_POST['equipment']."'";
								$econd.=" and equipment='".$_POST['equipment']."'";
						}				
						if($_POST['data']=='Per Vehicle'){
							$lq=sqlsrv_query($conn,"select u.id as id,CONCAT(u.equipment,' ',u.brand,' ',u.model,' ',u.avNo,' ',u.location) as name,sum(d.mins) as tmin 
								from downtimeFlatData d right join unit u on u.id=d.unitId 
								where u.id>0 and d.date>='".$_POST['startdate']."' and d.date<='".$_POST['enddate']."' ".$cond."
								group by u.id,CONCAT(u.equipment,' ',u.brand,' ',u.model,' ',u.avNo,' ',u.location)
								order by sum(d.mins) DESC
								");		
										
						}
						if($_POST['data']=='Per Type'){
							$lq=sqlsrv_query($conn,"select u.type as id,u.type as name,sum(d.mins) as tmin from downtimeFlatData d right join unit u on u.id=d.unitId where d.date>='".$_POST['startdate']."' and d.date<='".$_POST['enddate']."' ".$cond."
								group by u.id,u.type
								order by sum(d.mins) DESC
								");
						}
						$arr = '';						
						$tots = 0;					
						while($l=sqlsrv_fetch_array($lq)){
							$hrs = $l['tmin']/60;
							$tots+=($l['tmin']/60);
							$perc=($hrs/$minuted)*100;		
							$mins=number_format(100 - $perc).' %';
							$arr.="'".$l['id']."',";
							$seq++;
							
							echo '
								<tr>
									<td>'.$seq.'</td>
									<td>'.$l['name'].'</td>
									<td align="right">'.number_format($hrs,2).'</td>	
									<td align="right">'.$mins.'</td>								
								</tr>
							';
						}
						
						// Display vehicles without downtime data
						
						if($_POST['data']=='Per Vehicle'){
							if(strlen($arr)==0){
								$arr ="0,";
							}
							$lqe=sqlsrv_query($conn,"select id as id,CONCAT(equipment,' ',brand,' ',model,' ',avNo,' ',location) as name
								from unit 
								where id not in (".rtrim($arr,',').") ".$econd."								
								order by CONCAT(equipment,' ',brand,' ',model,' ',avNo,' ',location)
								");		
							
									
						}
						if($_POST['data']=='Per Type'){
							if(strlen($arr)==0){
								$arr ="'xxxx',";
							}
							$lqe=sqlsrv_query($conn,"select distinct type as name
								from unit 
								where type not in (".rtrim($arr,',').")	".$econd."							
								order by type
								");		
							
						}

						while($e=sqlsrv_fetch_array($lqe)){	
							$seq++;
							echo '
								<tr>
									<td>'.$seq.'</td>
									<td>'.$e['name'].'</td>
									<td align="right">0.00</td>
									<td align="right">100 %</td>									
								</tr>
							';
						}

						$percent=($tots/($minuted * $seq))*100;		
						$overall=number_format(100 - $percent).' %';
						echo '
								<tr style="font-weight:bold;font-size:15px;color:blue;">									
									<td colspan="2">Overall</td>
									<td align="right">'.number_format($tots,2).' hrs</td>
									<td align="right">'.$overall.'</td>									
								</tr>
							';


					?>
					</tbody>
					</table>
				<?php } ?>
			</div>
		</div>
	</div>



</body>
<script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>


<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/components-pickers.js"></script>
 <script src="js/excel/src/jquery.table2excel.js" type="text/javascript"></script>
<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
   Layout.init(); // init current layout

    
});
</script>
<script>
	function exportexcel(x){
          jQuery("#"+x).table2excel({
            // exclude CSS class
            //exclude: ".noExl",
            name: "VMS",
            filename: "FleetAvailability_<?php echo date('his');?>" //do not include extension
          }); 
        }
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
