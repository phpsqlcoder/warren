<?php
include("config.php");
session_start();

if(!isset($_GET['isexcel'])){
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
					<form action="rpt_masterlist.php?act=generate" method="post">
						<table width="100%">
							<tr>
								<td colspan="4" align="center"> <h1>Masterlist Report</h1> </td>
							</tr>
					  		<tr>
					  			<td>
					  				<select name="location" class="form-control">
							    		<option value="" selected="selected"> - All Locations- </option>
							    		<?php 
							    			$lfq = sqlsrv_query($conn,"select distinct location from unit order by location");
							    			while($lf = sqlsrv_fetch_array($lfq)){
							    				echo '<option value="'.$lf['location'].'">'.$lf['location'].'</option>';
							    			}
							    			if(isset($_GET['act'])){
							    				if($_POST['location']<>''){
							    					echo '<option value="'.$_POST['location'].'" selected="selected">'.$_POST['location'].'</option>';
							    				}
							    			}

							    		?>
							    	</select>
					  			</td>
					  			<td>
					  				<select name="type" class="form-control">
							    		<option value="" selected="selected"> - All Type- </option>
							    		<?php 
							    			$lfq = sqlsrv_query($conn,"select distinct type from unit order by type");
							    			while($lf = sqlsrv_fetch_array($lfq)){
							    				echo '<option value="'.$lf['type'].'">'.$lf['type'].'</option>';
							    			}
							    			if(isset($_GET['act'])){
							    				if($_POST['type']<>''){
							    					echo '<option value="'.$_POST['type'].'" selected="selected">'.$_POST['type'].'</option>';
							    				}
							    			}
							    		?>
							    	</select>
					  			</td>
					  			<td>
					  				<select name="equipment" class="form-control">
							    		<option value="" selected="selected"> - All Equipment- </option>
							    		<?php 
							    			$lfq = sqlsrv_query($conn,"select distinct equipment from unit order by equipment");
							    			while($lf = sqlsrv_fetch_array($lfq)){
							    				echo '<option value="'.$lf['equipment'].'">'.$lf['equipment'].'</option>';
							    			}
							    			if(isset($_GET['act'])){
							    				if($_POST['equipment']<>''){
							    					echo '<option value="'.$_POST['equipment'].'" selected="selected">'.$_POST['equipment'].'</option>';
							    				}
							    			}
							    		?>
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
					<table width="100%" style="font-family:arial;font-size:12px;">
						<thead>
							<tr align="left">
								<th>Seq</th>
								<th>Location</th>
								<th>Brand</th>
								<th>Model</th>
								<th>Type</th>
								<th>Equipment</th>
								<th>Plate No</th>	
								<th>Engine No</th>
								<th>Chassis No</th>
								<th>Odometer</th>
								<th>Av No</th>
								<th>Driver</th>
								<th>Color</th>											
							</tr>
						</thead>
						<tbody>
							<tr><td colspan="13"><hr></td></tr>
					<?php 
						$ldata='';
						$seq=0;
						$cond = '';
						if($_POST['location']<>''){
							$cond.=" and location='".$_POST['location']."'";
						}
						if($_POST['type']<>''){
							$cond.=" and type='".$_POST['type']."'";	
						}
						if($_POST['equipment']<>''){
							$cond.=" and equipment='".$_POST['equipment']."'";
						}
						$ctr1s=0;
						$lq=sqlsrv_query($conn," select * from unit where id>0 ".$cond."");
						while($l=sqlsrv_fetch_array($lq)){							
							$seq++;
							$ctr1s++;
  							if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F6F7F6';}
							echo '
								<tr style="background-color:'.$bgclr1s.'">
									<td>'.$seq.'</td>
									<td>'.$l['location'].'</td>
									<td>'.$l['brand'].'</td>
									<td>'.$l['model'].'</td>
									<td>'.$l['type'].'</td>
									<td>'.$l['equipment'].'</td>
									<td>'.$l['plateNo'].'</td>
									<td>'.$l['engineNo'].'</td>
									<td>'.$l['chassisNo'].'</td>
									<td>'.$l['odometer'].'</td>
									<td>'.$l['avNo'].'</td>
									<td>'.$l['driver'].'</td>
									<td>'.$l['color'].'</td>
								</tr>
							';
						}
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

<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
   Layout.init(); // init current layout


});
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php } 
?>