<?php
include("config.php");
require_once('adLDAP/src/adLDAP.php');
$ermsg='';
if(isset($_GET['act'])){
	if($_GET['act']=='login'){
		
	// Start AD script - to check if you have access to AD	
		$adldap = new adLDAP();
		$authUser = $adldap->user()->authenticate("appadmin", "P@55w0rd");
		if ($authUser == true) {		  
		}
		else {		  
		  echo $adldap->getLastError();
		  die("There is an error connecting to domain server!");
		}
	// end AD checking


		$authUser = $adldap->authenticate($_POST['logname'], $_POST['logpword']); // Check if domain account and password is correct.
		if ($authUser == true) { // if correct
			
			// check if domain accout has access to system.
		    $qry=sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM users WHERE domain='".$_POST['logname']."' and active=1 and role in ('admin','approver')"));
			if($qry['id']){
				session_start();
				$_SESSION['esdvms_username']=$_POST['logname'];
				$_SESSION['esdvms_name']=$qry['fullname'];
				$_SESSION['esdvms_dept']=$qry['dept'];
				$_SESSION['esdvms_role']=$qry['role'];
				header("location:home.php");
			}
		    else{
		    	$ermsg="Your account has no access to this application!";
		    }
		}
		
		else { 
		  $ermsg="Invalid Account!";
		}


	}
	elseif($_GET['act']=='logout'){
		session_start();
		unset($_SESSION['esdvms_username']);
		header("location:login.php");
	}
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="utf-8"/>
<title>ECS Vehicle Request</title>
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

<link href="metronic/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/pages/css/login-soft.css" rel="stylesheet" type="text/css"/>

<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>

<body class="login">
	<?php if(isset($_GET['email'])){ ?>
		<div class="alert alert-success">
			<strong>Success!</strong> Your concern has been sent.
		</div>
	<?php } ?>
	<?php if($ermsg){ ?>
		<div class="alert alert-danger text-center">
			<strong>Error!</strong> <?php echo $ermsg; ?>
		</div>
	<?php } ?>
<div class="logo" style="font-size:30px;color:white;">
	Vehicle Monitoring System
</div>

<div class="menu-toggler sidebar-toggler">
</div>

<div class="content">

	<!-- BEGIN LOGIN FORM -->
	
	<form class="login-form" action="login.php?act=login" method="post">
		<h3 class="form-title">Login to your account</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Domain Account" name="logname" id="logname"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="logpword" id="logpword"/>
			</div>
		</div>
		<div class="form-actions">
			<label class="checkbox">
			<input type="checkbox" name="remember" value="1"/> Remember me </label>
			<button type="submit" class="btn blue pull-right">
			Login <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
		
		<div class="forget-password">
			<h4>Forgot your password ?</h4>
			<p>
				no worries, click <a href="javascript:;" id="forget-password">
				here </a>
				to reset your password.
			</p>
		</div>
		<div class="create-account">
			<p>
				Don't have an account yet ?&nbsp; <a href="javascript:;" id="noaccount">
				Request an account </a>
			</p>
		</div>
	</form>
	<!-- END LOGIN FORM -->
	<!-- BEGIN FORGOT PASSWORD FORM -->
	<form class="forget-form" action="email/confirmation_send.php" method="get">
		<h3>Forget Password ?</h3>
		<p>
			 Enter your fullname, domain account and e-mail address below to reset your password.
		</p>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Full Name</label>
			<div class="input-icon">
				<i class="fa fa-font"></i>
				<input class="form-control placeholder-no-fix" type="text" placeholder="Last, First Middle" name="name"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Domain Account</label>
			<div class="input-icon">
				<i class="fa fa-info"></i>
				<input class="form-control placeholder-no-fix" type="text" placeholder="Domain" name="domain"/>
			</div>
		</div>
		<div class="form-group">
			<div class="input-icon">
				<i class="fa fa-envelope"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="button" id="back-btn" class="btn">
			<i class="m-icon-swapleft"></i> Back </button>
			<button type="submit" class="btn blue pull-right">
			Submit <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
	</form>
	<!-- END FORGOT PASSWORD FORM -->
	
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 <?php echo date('Y'); ?>&copy; Philsaga Mining Corporation
</div>
<!-- END COPYRIGHT -->
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
<script src="metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/login-soft.js" type="text/javascript"></script>
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
		});
	</script>
	<script>
		jQuery('#noaccount').click(function () {
            jQuery('.login-form').hide();
            jQuery('.forget-form').show();
        });

	</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>