<?php
include("config.php");
session_start();
$id=$_GET['id'];
$msg='';

	if(isset($_GET['disable'])){
		$disable = sqlsrv_query($conn,"update unit set isDisabled=1 where id=".$_GET['id']."");
		$msg='<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
								<strong>Success!</strong> Vehicle status is now Inactive! 
							</div>';
	}
	if(isset($_GET['enable'])){
		$disable = sqlsrv_query($conn,"update unit set isDisabled=0 where id=".$_GET['id']."");
		$msg='<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
								<strong>Success!</strong> Vehicle status is now Active! 
							</div>';
	}

if(isset($_GET['act'])){
	if($_GET['act']=='editdowntime'){
	
		$upd=sqlsrv_query($conn,"update unit set 
			location='".$_POST['location']."',
			brand='".$_POST['brand']."',
			model='".$_POST['model']."',
			type='".$_POST['type']."',
			equipment='".$_POST['equipment']."',
			plateNo='".$_POST['plateNo']."',
			engineNo='".$_POST['engineNo']."',
			chassisNo='".$_POST['chassisNo']."',
			odometer='".$_POST['odometer']."',
			avNo='".$_POST['avNo']."',
			driver='".$_POST['driver']."',
			color='".$_POST['color']."'
			 where id=".$id."");
		$msg='<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
								<strong>Success!</strong> Equipment details has been updated! 
							</div>';
	}
	
}
$r=sqlsrv_fetch_array(sqlsrv_query($conn,"select * from unit where id='".$id."'"));
?>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>ESD | Monitoring</title>
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
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body style="background-color:white;">
<div class="page-container">
	<?php echo $msg;?>
<form method="post" action="unit_edit.php?act=editdowntime&id=<?php echo $id;?>">
	<div class="modal-header">	
		<h4 class="modal-title">Update Unit</h4> 
	<?php if($r['isDisabled']==0) {?>
		 <a href="unit_edit.php?disable=on&id=<?php echo $_GET['id'];?>" class="btn red btn-xs">Disable Vehicle</a>
	<?php } else { ?>

	<a href="unit_edit.php?enable=on&id=<?php echo $_GET['id'];?>" class="btn green btn-xs">Enable Vehicle</a>

	<?php } ?>
	</div>
	<div class="modal-body">
		<div class="row">
		<div class="col-md-12">							 
		 	<div class="form-group" style="height:200px">
			 	<table class="table">
					<tr>
						<td>Location</td>
						<td><input type="text" class="form-control" name="location" value="<?php echo $r['location'];?>"></td>
					</tr>
					<tr>
						<td>Brand</td>
						<td><input type="text" class="form-control" name="brand" value="<?php echo $r['brand'];?>">		</td>
					</tr>
					<tr>
						<td>Model</td>
						<td><input type="text" class="form-control" name="model" value="<?php echo $r['model'];?>">	</td>
					</tr>
					<tr>
						<td>Type</td>
						<td><input type="text" class="form-control" name="type" value="<?php echo $r['type'];?>">	</td>
					</tr>
					<tr>
						<td>Equipment</td>
						<td><input type="text" class="form-control" name="equipment" value="<?php echo $r['equipment'];?>">	</td>
					</tr>
					<tr>
						<td>Plate No</td>
						<td><input type="text" class="form-control" name="plateNo" value="<?php echo $r['plateNo'];?>">	</td>
					</tr>
					<tr>
						<td>Engine No</td>
						<td><input type="text" class="form-control" name="engineNo" value="<?php echo $r['engineNo'];?>">	</td>
					</tr>
					<tr>
						<td>Chassis No</td>
						<td><input type="text" class="form-control" name="chassisNo" value="<?php echo $r['chassisNo'];?>">	</td>
					</tr>
					<tr>
						<td>Odometer</td>
						<td><input type="text" class="form-control" name="odometer" value="<?php echo $r['odometer'];?>"></td>
					</tr>
					<tr>
						<td>AV No</td>
						<td><input type="text" class="form-control" name="avNo" value="<?php echo $r['avNo'];?>"></td>
					</tr>
					<tr>
						<td>Driver</td>
						<td><input type="text" class="form-control" name="driver" value="<?php echo $r['driver'];?>"></td>
					</tr>
					<tr>
						<td>Color</td>
						<td><input type="text" class="form-control" name="color" value="<?php echo $r['color'];?>">	</td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td align="right"><input type="submit" class="btn blue" value="Save">	</td>
					</tr>

				</table>		
				
			</div>	
		</div>
	</div>						

	</div>

	</form>
	</div>
</body>
<script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/gritter/js/jquery.gritter.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
<script src="metronic/assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>

<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/clockface/js/clockface.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/components-pickers.js"></script>
<script src="<?php echo $url;?>js/notifications.js"></script>
<script src="<?php echo $url;?>js/comments.js"></script> 
<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
   Layout.init(); // init current layout

   Index.init();

   Index.initDashboardDaterange();
   ComponentsPickers.init();

});
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>