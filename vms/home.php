<?php
include("config.php");
session_start();

if(!$_SESSION['esdvms_username']){
	header("location:login.php");
}

$total = sqlsrv_fetch_array(sqlsrv_query($conn,"select count(id) as total from downtime where active=1"));

?>
<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>VMS - PMC</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="metronic/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/admin/pages/css/login-soft.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES -->
	<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
	<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico"/>
	<style>

	#clock {
		position: absolute;
		top: 0px;
		right: 0px;
		color:white;
		margin-right: 10px;
	}

</style>
<script>
	function startTime() {
		var today = new Date();
		var h = today.getHours();
		var m = today.getMinutes();
		var s = today.getSeconds();
		m = checkTime(m);
		s = checkTime(s);
		document.getElementById('clock').innerHTML =
		"<?php echo date('F d Y');?> " + h + ":" + m + ":" + s;
		var t = setTimeout(startTime, 500);
	}
	function checkTime(i) {
if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
return i;
}
</script>
</head>

<body class="login" onload="startTime()">

	<div class="navbar navbar-trans navbar-fixed-top" role="navigation">
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="login.php?act=logout">
						<i style="color:orange;" class="icon-logout"></i>
						<strong style="color:orange"><?php echo strtoupper($_SESSION['esdvms_username']); ?></strong>
						<img style="height:20px;" src="images/logo_default.jpg">
					</a>
				</li>
				<li>&nbsp;</li>
			</ul>

		</div>
	</div>      
	<div style="margin-top:60px;margin-right:3px;padding:15px;"class="bg-blue bg-font-blue" id="clock"></div>    

	<!-- BEGIN LOGO -->
	<div class="logo"><br><br></div>

	<div class="menu-toggler sidebar-toggler"></div>

	<div class="content" style="width:56%;">
		<h1 style="color:white;text-align:center;">Downtime Monitoring</h1>
		<div class="row">
			<div class="col-md-12">
				<div class="tiles">
					<div class="tile bg-red selected" onclick="window.location.href='index.php'">
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-tachometer"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								Dashboard
							</div>
						</div>
					</div>

					<div class="tile bg-blue selected" onclick="window.location.href='downtimes.php'">
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-file-text-o"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								Downtime List
							</div>
						</div>
					</div>

					<div class="tile bg-yellow-lemon selected" onclick='window.open("downtime_add.php","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=700");'>
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-pencil-square-o"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								Add New
							</div>
						</div>
					</div>

					<div class="tile bg-purple selected" onclick="window.location.href='maintenance.php'">
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-cogs"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								System Maintenance
							</div>
						</div>
					</div>

					<div class="tile bg-green selected" onclick='window.open("maintenance/export.php?act=raw_data&startDate=2018-01-01&endDate=<?php echo date('Y-m-d');?>","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500");'>
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-bar-chart-o"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								Reports
							</div>
						</div>
					</div>

					<div class="tile bg-red-pink selected" onclick="window.location.href='utilization/index.php'">
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-cog"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								Utilization
							</div>
						</div>
					</div>

					<div class="tile selected" onclick='window.open("manual/approver/WelcometoVehicleMonitoringSystem.html","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500");'>
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-book"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								User Manual
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<br>
	<div class="content" style="width:56%;">
		<h1 style="color:white;text-align:center;">Utilization Monitoring</h1>

		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="tiles">
					<div class="tile bg-red selected" onclick="window.location.href='utilization/index.php'">
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-bar-chart-o"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								Dashboard
							</div>
						</div>
					</div>

					<div class="tile bg-blue selected" onclick="window.location.href='utilization/request_list.php'">
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-bars"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								Request List
							</div>
						</div>
					</div>


					<div class="tile bg-purple selected" onclick="window.location.href='utilization/request_list.php?addNewRequest=go'">
						<div class="corner"> </div>
						<div class="tile-body">
							<i class="fa fa-file-o"></i>
						</div>
						<div class="tile-object">
							<div class="name">
								Add New
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>


	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		<?php echo date('Y'); ?> &copy; Philsaga Mining Corp.
	</div>

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
	<script src="metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
	<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
	<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
	<script src="metronic/assets/admin/pages/scripts/login-soft.js" type="text/javascript"></script>

	<script src="hotkey/jquery.hotkeys.js"></script>
	<!-- END PAGE LEVEL SCRIPTS -->
	<script>
		jQuery(document).ready(function() {     
Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init() // init quick sidebar
Login.init();

// init background slide images
$.backstretch([
	"metronic/assets/admin/pages/media/bg/1.jpg",
	"metronic/assets/admin/pages/media/bg/2.jpg",
	"metronic/assets/admin/pages/media/bg/3.jpg",
	"metronic/assets/admin/pages/media/bg/4.jpg"
	], {
		fade: 1000,
		duration: 8000
	}
	);

$(document).bind('keyup', '1', dashboard);
$(document).bind('keyup', '2', downtime_list);
$(document).bind('keyup', '3', add_new);
$(document).bind('keyup', '4', maintenance);
$(document).bind('keyup', '5', reports);
$(document).bind('keyup', '6', user_manual);
$(document).bind('keyup', '7', logout);
});

		function dashboard(){
			window.location.href='index.php';
		}
		function downtime_list(){
			window.location.href='downtimes.php';
		}
		function add_new(){
			window.open("downtime_add.php","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=700");
		}
		function maintenance(){
			window.location.href='maintenance.php';
		}
		function reports(){
			window.open("maintenance/export.php?act=raw_data&startDate=2018-01-01&endDate=<?php echo date('Y-m-d');?>","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500");
		}
		function user_manual(){
			window.open("manual/user/WelcometoVehicleMonitoringSystem.html","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500");
		}
		function logout(){
			window.location.href='login.php?act=logout';
		}
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>