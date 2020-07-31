
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
<title>PMC</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->

<link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="../metronic/assets/pages/css/coming-soon.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/layouts/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="../metronic/assets/layouts/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/layouts/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
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
<body>
<div class="container">
	<div class="row">
		<div class="col-md-12 coming-soon-header">
			<a class="brand" href="index.html">
			<img src="../metronic/assets/admin/layout/img/logo.png" alt="logo"/>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 coming-soon-content">
			<h1>System Locked</h1>
			<p>
				 You had tried and failed to login more than 3 times to this application. Your computer will be block for 2 hours.
				 Please contact ICT dept to reset your account.
			</p>
			<br>
			
			
		</div>
		<div class="col-md-6 coming-soon-countdown">
			<div id="defaultCountdown">
			</div>
		</div>
	</div>
	<!--/end row-->
	<div class="row">
		<div class="col-md-12 coming-soon-footer">
			 <?php echo date('Y');?> &copy; PMC - ICT. System and Application Team.
		</div>
	</div>
</div>
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../metronic/assets/global/plugins/respond.min.js"></script>
<script src="../metronic/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="../metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="../metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="../metronic/assets/global/plugins/countdown/jquery.countdown.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../metronic/assets/layouts/layout/scripts/layout.js" type="text/javascript"></script>


<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {     
  	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init() // init quick sidebar


  	// init background slide images
    $.backstretch([
            "../metronic/assets/admin/pages/media/bg/1.jpg",
            "../metronic/assets/admin/pages/media/bg/2.jpg",
            "../metronic/assets/admin/pages/media/bg/3.jpg",
    		"../metronic/assets/admin/pages/media/bg/4.jpg"
        ], {
        fade: 1000,
        duration: 10000
   	});
});


</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>