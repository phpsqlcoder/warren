<?php
include("config.php");
session_start();
if(!$_SESSION['esdvms_username']){
	header("location:login.php");
}
if(!isset($_GET['startDate'])){
	$_GET['endDate']=date('Y-m-d');
	$_GET['startDate']=date('Y-m-d',strtotime("-29 days"));
	$_GET['datetype']="Weekly";	
}
if(!isset($_GET['s_location'])){
	$_GET['s_location']='';
}
if(!isset($_GET['s_type'])){
	$_GET['s_type']='';
}
if(!isset($_GET['s_name'])){
	$_GET['s_name']='';
}



$cond="";
if(strlen($_GET['s_location'])>1){
	$cond.=" and u.location='".$_GET['s_location']."'";
}
if(strlen($_GET['s_type'])>1){
	$cond.=" and u.type='".$_GET['s_type']."'";
}
if(strlen($_GET['s_name'])>1){
	$cond.=" and u.name='".$_GET['s_name']."'";
}

function computemins($s,$e){	
	$from_time = strtotime($s);
	$to_time = strtotime($e);
	return round(abs($to_time - $from_time) / 60,0);
}
//echo computemins("2016-12-06 10:45","2016-12-06 23:59");die();


//die();
?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 3.1.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
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
<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
<?php include("header.php");?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">

			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
					Downtime List
					</h3>
					<form method="get" act="index.php">
					<ul class="page-breadcrumb breadcrumb">
						<li>							
							<a href="#">Search:</a>							
						</li>
						<li>			
							<input type="hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
							<input type="hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">		
							<input type="hidden" name="datetype" value="<?php echo $_GET['datetype'];?>">						
							<select class="form-control input-sm" name="s_location">
				 				<?php
				 						echo '<option value="" selected="selected"> - Select Location -';
				 					$uq=sqlsrv_query($conn,"select distinct location from unit order by location");
				 					while($u=sqlsrv_fetch_array($uq)){
				 						$select='';
				 						if(isset($_GET['s_location'])){
				 							if($u['location']==$_GET['s_location']){
				 								$select=' selected="selected"';
				 							}
				 						}
				 						echo '<option value="'.$u['location'].'" '.$select.'>'.$u['location'];				 						
				 					}
				 						
				 				?>
				 			</select>							
						</li>
						<li>							
							<select class="form-control input-sm" name="s_type">
				 				<?php
				 						echo '<option value="" selected="selected"> - Select Category -';
				 					$uq=sqlsrv_query($conn,"select distinct type from unit order by type");
				 					while($u=sqlsrv_fetch_array($uq)){
				 						$select='';
				 						if(isset($_GET['s_type'])){
				 							if($u['type']==$_GET['s_type']){
				 								$select=' selected="selected"';
				 							}
				 						}
				 						echo '<option value="'.$u['type'].'" '.$select.'>'.$u['type'];
				 					}
				 					
				 				?>
				 			</select>				 											
						</li>
						<li>
							<select class="form-control input-sm" name="s_name">
				 				<?php
				 						echo '<option value="" selected="selected"> - Select Unit -';
				 					$uq=sqlsrv_query($conn,"select distinct name from unit order by name");
				 					while($u=sqlsrv_fetch_array($uq)){
				 						$select='';
				 						if(isset($_GET['s_name'])){
				 							if($u['name']==$_GET['s_name']){
				 								$select=' selected="selected"';
				 							}
				 						}
				 						echo '<option value="'.$u['name'].'" '.$select.'>'.$u['name'];
				 					}
				 						
				 				?>
				 			</select>
						</li>
						<li>
							<input type="submit" class="btn green btn-sm" value="Go">
						</li>				
						
					</ul>
						<input type="hidden" name="aaa" id="aaa" value="aa">
					</form>
					<input type="hidden" name="hiddenstart" id="hiddenstart" value="<?php echo $_GET['startDate'];?>">
					<input type="hidden" name="hiddenend" id="hiddenend" value="<?php echo $_GET['endDate'];?>">
					<input type="hidden" name="hiddens_location" id="hiddens_location" value="<?php echo $_GET['s_location'];?>">
					<input type="hidden" name="hiddens_name" id="hiddens_name" value="<?php echo $_GET['s_name'];?>">
					<input type="hidden" name="hiddens_type" id="hiddens_type" value="<?php echo $_GET['s_type'];?>">
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
		
			<div class="clearfix">
			</div>
		
			<div class="row ">
				<div class="col-md-12 col-sm-12">
					
									<table class="table">
										<thead>
											<tr>
												<th>#</th>
												<th>Unit</th>
												<th>Start</th>
												<th>End</th>
												<th>Remarks</th>	
												<th>Edit</th>											
											</tr>
										</thead>
										<tbody>
									<?php 
										$ldata='';
										$lt=sqlsrv_query($conn,"select count(d.id) as totald from downtime d");
										$seq=$lt['totald'];
										$seq=0;
										$lq=sqlsrv_query($conn,"select CONVERT(VARCHAR(19),d.dateStart) as ds,CONVERT(VARCHAR(19),d.dateEnd) as de,d.*,u.name as uni from downtime d 
											left join unit u on u.id=d.unitId where d.id>0 ".$cond." order by d.id desc");
										while($l=sqlsrv_fetch_array($lq)){	
											$seq++;										
											echo '
												<tr>
													<td>'.$seq.'</td>
													<td>'.$l['uni'].'</td>
													<td>'.$l['ds'].'</td>
													<td>'.$l['de'].'</td>
													<td>'.$l['remarks'].'</td>
													<td><a href="#" class="btn purple btn-sm" onclick=\'window.open("downtime_edit.php?id='.$l['id'].'","displayWindow","toolbar=no,scrollbars=yes,width=910,height=600"); return false;\';><i class="fa fa-edit"></i></a></td>
												</tr>
											';
											//$seq--;
										}
									?>
									</tbody>
									</table>						
							
				</div>				
			</div>
			<div class="clearfix">
			</div>			
		</div>
	</div>
	<!-- END CONTENT -->
	
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 2014 &copy; Metronic by keenthemes.
	</div>
	<div class="page-footer-tools">
		<span class="go-top">
		<i class="fa fa-angle-up"></i>
		</span>
	</div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="metronic/assets/global/plugins/respond.min.js"></script>
<script src="metronic/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
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
   $('[data-toggle="popover"]').popover(); 
//QuickSidebar.init() // init quick sidebar // initlayout and core plugins
   Index.init();
   //Index.initJQVMAP(); // init index page's custom scripts
   //Index.initCalendar(); // init index page's custom scripts
   //Index.initCharts(); // init index page's custom scripts
   //Index.initChat();
   //Index.initMiniCharts();
   Index.initDashboardDaterange();
   //Tasks.initDashboardWidget();
   ComponentsPickers.init();

});
</script>

</body>
<!-- END BODY -->
</html>