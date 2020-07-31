<?php
include('../config.php');
require_once('../adLDAP/src/adLDAP.php');
require_once('../login/login_logs.php');
$ermsg='';
if(isset($_GET['act'])){
	if($_GET['act']=='login'){
		
	// Start AD script - to check if you have access to AD	
		$adldap = new adLDAP();
		echo check_if_locked($_POST['loginname']); // Add logs
		$authUser = $adldap->user()->authenticate("appadmin", "P@55w0rd");
		if ($authUser == true) {		  
		}
		else {		  
		  echo $adldap->getLastError();
		  die("There is an error connecting to domain server!");
		}
	// end AD checking

		$userlogin = LoginAttempt($_POST['loginname']); // Add Logs
		$authUser = $adldap->authenticate($_POST['loginname'], $_POST['loginpword']); // Check if domain account and password is correct.
		if ($authUser == true) { // if correct
			
			// check if domain accout has access to system.
		    $qry=sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM users WHERE domain='".$_POST['loginname']."' and active=1"));
			if($qry['id']){
				if($qry['role']=='requestor' || $qry['role']==strtoupper('requestor') || $qry['role'] == 'admin' || $qry['role'] =strtoupper('admin')){
					LoginSuccessful($userlogin); // Add Logs
					session_start();
					$_SESSION['esdvms_requestor_id']=$qry['id'];
					$_SESSION['esdvms_requestor_username']=$_POST['loginname'];
					$_SESSION['esdvms_requestor_ename']=$qry['fullname'];
					$_SESSION['esdvms_requestor_edept']=$qry['dept'];
					$_SESSION['esdvms_requestor_erole']=$qry['role'];
					header("location: index.php");
				}
				else
				{  
					app_user_error($userlogin); // Add Logs
					$ermsg="<font color='red' size='+1'>Your account has no access to this page!<br><br></font>";
				}
			}
		    else{
		    	app_user_error($userlogin); // Add Logs
		    	$ermsg="<font color='red' size='+1'>Your account has no access to this page!<br><br></font>";
		    }
		}
		
		else { 
			AD_user_error($userlogin); // Add Logs
		  	$ermsg="<font color='red' size='+1'>Invalid Account!<br><br></font>";
		}


	}
	elseif($_GET['act']=='logout'){
		session_start();
		unset($_SESSION['esdvms_requestor_username']);
		header("location:login.php");
	}
}
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
<title>Request Login Form</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="../metronic/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/admin/pages/css/login-soft.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="../metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
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
<body class="login" onload="$('#logname').focus();">
<!-- BEGIN LOGO -->
<div class="logo" style="width:550px;">
	
</div>
<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" action="login.php?act=login" method="post">
		<h3 class="form-title">Login using your domain</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
		</div>
		<?php echo $ermsg;?>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Domain name" name="loginname" id="loginname"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="loginpword" id="loginpword"/>
			</div>
		</div>
		<div class="form-actions">
			
			<button type="submit" class="btn green pull-right">
			Login <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
		
	
	</form>
	<!-- END LOGIN FORM -->
	
	<!-- END REGISTRATION FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 <?php echo date('Y');?> &copy; Philsaga Mining Corp.
</div>
<!-- END COPYRIGHT -->
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
<script src="../metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../metronic/assets/global/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../metronic/assets/admin/pages/scripts/login.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
		jQuery(document).ready(function() {     
		  Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init() // init quick sidebar
		  Login.init();
		});
	</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>