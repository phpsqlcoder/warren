<?php 
	include("../config.php");

ob_start();
session_start();
  if(!$_SESSION['uname']){
        header("location:login.php");
}

	$msg = "";
	if(isset($_GET['action'])){
		$date_needed = date('Y-m-d H:i:s',strtotime(str_replace("T", " ", $_POST['date_needed'])));
		$insert = sqlsrv_query($conn,"insert into vehicle_request (name,dept,date_needed,purpose,email,costcode) 
			values ('".$_POST['name']."','".$_POST['dept']."','".$date_needed."','".$_POST['purpose']."','".$_POST['email']."','".$_POST['costcode']."')");
		header('location:request_form.php?msg=go');
		
	}
	if(isset($_GET['msg'])){
		$msg = '<div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <span class="fa fa-check-square"></span><strong> Success!</strong> Record has been submitted.
             </div>';
	}
?>
<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>Request Form</title>
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
	<!-- BEGIN THEME STYLES -->
	<link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
	<link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
	<link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
	<link id="style_color" href="../metronic/assets/admin/layout/css/themes/light.css" rel="stylesheet" type="text/css"/>
	<link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico"/>
</head>

<body>
	<div class="col-md-6">
		<div class="portlet box blue-hoki">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-file-o"></i>Request Form
				</div>

			</div>
			<div class="portlet-body">
				<div class="row" style="padding-left:20px;">
					<div class="col-md-12">
						<?php echo $msg; ?>
						<!-- BEGIN FORM-->
						<form action="request_form.php?action=save" method="post">
							<h3 class="form-section hide">Vehicle Request Form</h3>
							<p style="color:red;">
								Note: All fields are required
							</p>
							<div class="form-group">
								<div class="input-icon">
									<i class="fa fa-user"></i>
									<input type="text" class="form-control" placeholder="Name" name="name" value="<?php echo $_SESSION['esdvms_requestor_ename']; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<div class="input-icon">
									<i class="fa fa-envelope"></i>
									<input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $_SESSION['esdvms_requestor_username'].'@philsaga.com'; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<div class="input-icon">
									<i class="fa fa-th-list"></i>
									<input type="text" class="form-control" placeholder="Department" name="dept" value="<?php echo $_SESSION['esdvms_requestor_edept']; ?>" readonly>
								</div>
							</div>				
							<div class="form-group">
								<div class="input-icon">
									<i class="fa fa-file-code-o"></i>
									<input type="text" class="form-control" placeholder="Chargeability Cost Code" name="costcode">
								</div>
							</div>
							<div class="form-group">
								<div class="input-icon">
									<i class="fa fa-envelope"></i>
									<input type="datetime-local" class="form-control" placeholder="Date Needed" name="date_needed">
									<span class="help-block">Date and Time Needed </span>

								</div>
							</div>

							<div class="form-group">
								<div class="input-icon">
									<i class="fa fa-th-list"></i>
									<input type="text" class="form-control" placeholder="Department" name="origin">
								</div>
							</div>
							<div class="form-group">
								<textarea class="form-control" rows="3=6" placeholder="Purpose" name="purpose"></textarea>
							</div>
							<input type="submit" class="btn green" value="Submit">
							<input type="reset" class="btn btn-default" value="Reset">
						</form>
						<!-- END FORM-->
					</div>
				</div>
			</div>
		</div>
	

	<script src="../metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="../metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>


	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>