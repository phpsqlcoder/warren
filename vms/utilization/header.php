<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a href="index.php" style="color:white;">
				<br>Motorpool Monitoring
			</a>
		</div>
	<?php if(strtoupper($_SESSION['esdvms_role']) == 'ADMIN' ||  strtoupper($_SESSION['esdvms_role']) == 'APPROVER') { ?>	
		<div class="hor-menu hidden-sm hidden-xs">
			<ul class="nav navbar-nav">	
				<li class="classic-menu-dropdown">
					<a href="../home.php">
					<i class="fa fa-home"></i> Home
					</a>
				</li>
				<li><a>|</a></li>	
				<li class="classic-menu-dropdown">
					<a href="../index.php">
					<i class="fa fa-tasks"></i> Dashboard
					</a>
				</li>

				<li><a>|</a></li>	
				<li class="dropdown">
			        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cogs"></i> System Maintenance
			        <span class="caret"></span></a>
			        <ul class="dropdown-menu">
			          <li><a href="../maintenance.php"><i class="fa fa-truck"></i> Vehicle Maintenance</a></li>
			          <li><a href="../user-maintenance.php"><i class="fa fa-user"></i> User Maintenance</a></li>
			          
			        </ul>
		      	</li>

				<li><a>|</a></li>	
				<li class="classic-menu-dropdown">
					<a href="../downtimes.php">
					<i class="fa fa-list-alt"></i> Downtime List
					</a>
				</li>

				<li><a>|</a></li>	
				<li class="classic-menu-dropdown">
					<a href="#" onclick='window.open("downtime_add.php","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=700");'>
					<i class="fa fa-plus"></i> Add Downtime
					</a>
				</li>					

				<li><a>|</a></li>

				<li class="classic-menu-dropdown">
					<a href="#" onclick='window.open("maintenance/export.php?act=raw_data&startDate=2018-01-01&endDate=<?php echo date('Y-m-d');?>","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500");'>
					<i class="fa fa-file-text-o"></i> Reports
					</a>
				</li>

				<li><a>|</a></li>

				<li class="classic-menu-dropdown">
					<a href="#" onclick='window.open("maintenance/export.php?act=raw_data&startDate=2018-01-01&endDate=<?php echo date('Y-m-d');?>","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500");'>
					<i class="fa fa-fast-forward"></i> Utilization
					</a>
				</li>
				
								
			</ul>
		</div>

	<?php } elseif(strtoupper($_SESSION['esdvms_role']) == 'REQUESTOR' ) { ?>
		<div class="hor-menu hidden-sm hidden-xs">
			<ul class="nav navbar-nav">	
				<li class="classic-menu-dropdown">
					<a href="home.php">
					Home
					</a>
				</li>		
			</ul>
		</div>
	<?php } ?>
	
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">				
				<!-- BEGIN USER LOGIN DROPDOWN -->
				<li class="dropdown dropdown-quick-sidebar-toggler" style="color:white;">
					<br><?php echo $_SESSION['esdvms_username']; ?>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
				<!-- BEGIN QUICK SIDEBAR TOGGLER -->
				<li class="dropdown dropdown-quick-sidebar-toggler">
					<a href="../login.php?act=logout" class="dropdown-toggle">
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

