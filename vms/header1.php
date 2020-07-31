<?php 
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$full_url = explode("/", $actual_link);
$url = ''; 
if($full_url[4] == 'utilization'){
	$url = '../';
}
?>
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a href="index.php" style="color:white;">
				<br>ECS Vehicle Request
			</a>
		</div>

		
		<div class="hor-menu hidden-sm hidden-xs">
			<ul class="nav navbar-nav">	
				<li class="classic-menu-dropdown">
					<a href="home.php">
					<i class="fa fa-home"></i> Home
					</a>
				</li>
				<li class="mega-menu-dropdown">
					<a data-hover="dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
					<i class="icon-speedometer"></i> Vehicle Downtime <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
						<li>
							<!-- Content container to add padding -->
							<div class="mega-menu-content">
								<div class="row">
									<ul class="col-md-6 mega-menu-submenu">
										<li>
											<h3>Downtime</h3>
										</li>
										<li>
											<a href="<?php echo $url;?>index.php">
											<i class="fa fa-tasks"></i> Dashboard
											</a>
										</li>
										<li>
											<a href="<?php echo $url;?>downtimes.php">
											<i class="fa fa-list-alt"></i> Downtime List
											</a>
										</li>
										<li>
											<a href="#" onclick='window.open("<?php echo $url;?>downtime_add.php","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=700");'>
											<i class="fa fa-plus"></i> Add Downtime
											</a>
										</li>
										
									</ul>
									<ul class="col-md-6 mega-menu-submenu">
										<li>
											<h3>Reports</h3>
										</li>
										<li><a href="#" onclick='window.open("maintenance/export.php?act=raw_data&startDate=2018-01-01&endDate=<?php echo date('Y-m-d');?>","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500");'>Downtime Report</a></li>
										
									</ul>
									
								</div>
							</div>
						</li>
					</ul>
				</li>
				<li><a>|</a></li>	
				<li class="mega-menu-dropdown">
					<a data-hover="dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
					<i class="fa fa-automobile"></i> Vehicle Utilization <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
						<li>
							<!-- Content container to add padding -->
							<div class="mega-menu-content">
								<div class="row">
									<ul class="col-md-6 mega-menu-submenu">
										<li>
											<h3>Utilization</h3>
										</li>
										<li>
											<a href="<?php echo $url;?>utilization/index.php">
											<i class="fa fa-tasks"></i> Dashboard
											</a>
										</li>
										<li>
											<a href="<?php echo $url;?>utilization/request_list.php">
											<i class="fa fa-list-alt"></i> Request List
											</a>
										</li>
										<li>
											<a href="#" onclick='window.open("<?php echo $url;?>utilization/request_add.php","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=700");'>
											<i class="fa fa-plus"></i> Add Request
											</a>
										</li>
										
									</ul>
									<ul class="col-md-6 mega-menu-submenu">
										<li>
											<h3>Reports</h3>
										</li>									
										<li><a href="<?php echo $url;?>dispatch-per-department-report.php"><i class="fa fa-building-o"></i> Dispatch Distribution per Department</a></li>
										<li><a href="<?php echo $url;?>vehicles-no-dispatches-report.php"><i class="fa fa-send"></i> Top Vehicles by number of Dispatches</a></li>
										<li><a href="<?php echo $url;?>vehicles-distance-travelled-report.php"><i class="fa fa-truck"></i> Top Vehicles by Distance Travelled</a></li>
										<li><a href="<?php echo $url;?>destinations-report.php"><i class="fa fa-globe"></i> Top Frequent Destinations</a></li>
									</ul>
									
								</div>
							</div>
						</li>
					</ul>
				</li>

				<li><a>|</a></li>
				<li class="mega-menu-dropdown">
					<a data-hover="dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
					<i class="fa fa-cogs"></i> System Maintenance <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
						<li>
							<!-- Content container to add padding -->
							<div class="mega-menu-content">
								<div class="row">
									<ul class="col-md-6 mega-menu-submenu">
										<li><a href="<?php echo $url;?>maintenance.php"><i class="fa fa-truck"></i> Vehicle Maintenance</a></li>
			          					<li><a href="<?php echo $url;?>user-maintenance.php"><i class="fa fa-user"></i> User Maintenance</a></li>
									</ul>
									
								</div>
							</div>
						</li>
					</ul>
				</li>			

				<!-- <li class="classic-menu-dropdown">
					<a href="#" onclick='window.open("maintenance/export.php?act=raw_data&startDate=2018-01-01&endDate=<?php echo date('Y-m-d');?>","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500");'>
					<i class="fa fa-file-text-o"></i> Reports
					</a>
				</li> -->

				
								
			</ul>
		</div>
	
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">				
				<!-- BEGIN USER LOGIN DROPDOWN -->
				<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<i class="icon-bell"></i>
					<span class="badge badge-default" id="notification_total">
					0 </span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<p>
								 You have 14 new notifications
							</p>
						</li>
						<li>
							<ul class="dropdown-menu-list scroller" style="height: 250px;">
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-success">
									<i class="fa fa-plus"></i>
									</span>
									New user registered. <span class="time">
									Just now </span>
									</a>
								</li>
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-danger">
									<i class="fa fa-bolt"></i>
									</span>
									Server #12 overloaded. <span class="time">
									15 mins </span>
									</a>
								</li>
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-warning">
									<i class="fa fa-bell-o"></i>
									</span>
									Server #2 not responding. <span class="time">
									22 mins </span>
									</a>
								</li>
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-info">
									<i class="fa fa-bullhorn"></i>
									</span>
									Application error. <span class="time">
									40 mins </span>
									</a>
								</li>
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-danger">
									<i class="fa fa-bolt"></i>
									</span>
									Database overloaded 68%. <span class="time">
									2 hrs </span>
									</a>
								</li>
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-danger">
									<i class="fa fa-bolt"></i>
									</span>
									2 user IP blocked. <span class="time">
									5 hrs </span>
									</a>
								</li>
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-warning">
									<i class="fa fa-bell-o"></i>
									</span>
									Storage Server #4 not responding. <span class="time">
									45 mins </span>
									</a>
								</li>
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-info">
									<i class="fa fa-bullhorn"></i>
									</span>
									System Error. <span class="time">
									55 mins </span>
									</a>
								</li>
								<li>
									<a href="#">
									<span class="label label-sm label-icon label-danger">
									<i class="fa fa-bolt"></i>
									</span>
									Database overloaded 68%. <span class="time">
									2 hrs </span>
									</a>
								</li>
							</ul>
						</li>
						<li class="external">
							<a href="#">
							See all notifications <i class="m-icon-swapright"></i>
							</a>
						</li>
					</ul>
				</li>
				<li class="dropdown dropdown-quick-sidebar-toggler" style="color:white;">
					<br><?php echo $_SESSION['esdvms_username']; ?>
					<input type="hidden" value="<?php echo $_SESSION['esdvms_username']; ?>" name="esdvms_username" id="esdvms_username">
				</li>
				<!-- END USER LOGIN DROPDOWN -->
				<!-- BEGIN QUICK SIDEBAR TOGGLER -->
				<li class="dropdown">
					<a href="<?php echo $url;?>login.php?act=logout" class="dropdown-toggle">
					<i class="icon-logout"></i>
					</a>
				</li>
				<!-- END QUICK SIDEBAR TOGGLER -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
<div class="page-quick-sidebar-wrapper">
	<div class="page-quick-sidebar">
		<div class="nav-justified">	
			Messages 
			<a class="dropdown-quick-sidebar-toggler pull-right" href="#">Close</a>
			<div class="tab-content">
				<div class="tab-pane active page-quick-sidebar-alerts" id="quick_sidebar_tab_1">
					<div class="page-quick-sidebar-alerts-list">
						<div id="msg_chatarea"></div>
						<div id="msg_contents"></div>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>
<script src="<?php echo $url;?>js/notifications.js"></script>
<!-- END QUICK SIDEBAR -->




