<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a href="index.php" style="color:white;">
				<br>ESD Monitoring
			</a>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN HORIZANTAL MENU -->
		<!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
		<!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) sidebar menu below. So the horizontal menu has 2 seperate versions -->
		<div class="hor-menu hidden-sm hidden-xs">
			<ul class="nav navbar-nav">
				<!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->
				<li class="classic-menu-dropdown active">
					<a href="index.php">
					Dashboard <span class="selected">
					</span>
					</a>
				</li>
				<li>
					<a href="downtime_list.php">Downtime List</span>
					</a>				
				</li>	
				<li class="classic-menu-dropdown">
					<a data-hover="dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
					Inputs <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu pull-left">
						<li>
							<a data-toggle="modal" href="#inputdowntime">
							<i class="fa fa-bookmark-o"></i> Add Downtime </a>
						</li>
						<li>
							
							<a data-toggle="modal" href="#munit">
							<i class="fa fa-bookmark-o"></i>Add Unit</a>	
						</li>
											
					</ul>
				</li>
				<li class="classic-menu-dropdown">
					<a data-hover="dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
					Reports <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu pull-left">
						<li>
							<a href="rpt_downtimelist.php" target="blank">
							<i class="fa fa-bookmark-o"></i> Input List</a>
						</li>
						<li>
							<a href="rpt_flatdata.php" target="blank">
							<i class="fa fa-bookmark-o"></i> Downtime Report</a>
						</li>
						<li>
							<a href="rpt_chart.php" target="blank">
							<i class="fa fa-bookmark-o"></i> Chart Report</a>
						</li>
						<li style="display:none;">
							<a href="rpt_downtime.php" target="blank">
							<i class="fa fa-bookmark-o"></i> Downtime (Daily)</a>
						</li>
						<li style="display:none;">
							<a href="rpt_downtime.php" target="blank">
							<i class="fa fa-bookmark-o"></i> Downtime (Weekly)</a>
						</li>
						<li style="display:none;">
							<a href="rpt_downtime.php" target="blank">
							<i class="fa fa-bookmark-o"></i> Downtime (Monthly)</a>
						</li>
					</ul>
				</li>
								
			</ul>
		</div>
		<!-- END HORIZANTAL MENU -->
		<!-- BEGIN HEADER SEARCH BOX -->
		<!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
		
		<!-- END HEADER SEARCH BOX -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
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
					<a href="login.php?act=logout" class="dropdown-toggle">
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