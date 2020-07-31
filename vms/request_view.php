<?php 
session_start();
include("config.php");

$list='';
$r = sqlsrv_fetch_array(sqlsrv_query($conn,"select *,CONVERT(VARCHAR(19),addedAt) as added,CONVERT(VARCHAR(19),date_needed) as needed from vehicle_request where id = '".$_GET['id']."'"));
$i = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from request_other_info where request_id = '".$_GET['id']."'"));


########### Dispatch Records ####################
$vehicles = sqlsrv_query($conn,"select d.*,u.type,u.name as vehicle,dr.driver_name,CONVERT(VARCHAR(19),d.dateStart) as datestarted from dispatch d left join unit u on u.id=d.unitId left join drivers dr on dr.id=d.driver_id where d.request_id='".$_GET['id']."'");
while($v = sqlsrv_fetch_array($vehicles)){
	$type = 'car';
	if($v['type'] == 'Light Vehicle'){
		$type = 'car';
	}
	elseif($v['type'] == 'Medium Vehicle'){
		$type = 'truck';
	}
	elseif($v['type'] == 'Heavy Equipment'){
		$type = 'download';
	}
	elseif($v['type'] == 'Motorcycle'){
		$type = 'wheelchair';
	}

	/*$color = 'blue';
	if($v['isPrinted'] == 1){
		$color = 'yellow';
	} else { 
		if ($v['Status'] == 'Cancelled'){
			$color = 'red';
		} else if ($v['Status'] == 'Closed') {
			$color = 'green';
		} else if ($v['Status'] == 'Completed') {
			$color = 'green';
		} else {
			$color = 'blue';
		}
	}*/
	if ($v['Status'] == 'Completed' || $v['Status'] == 'Cancelled') {
      	if($v['Status'] == 'Completed')
        	$color = 'green';
      	else
        	$color = 'red';
   	}
   	else {
      $isClosable = 0;
      	if($v['Status'] == 'Closed'){
        	$color = 'red';
      	} else {
        	if($v['isPrinted'] == 1)
        	$color = 'yellow';
      	else
        	$color = 'blue';
      	}
   	} 

	$fueltype = '';
	if($v['fuel_added_type']){
		$fueltype = '('.$v['fuel_added_type'].')';
	}
	$list.='
		<a href="#" class="btn '.$color.'">
			<span>TN #: '.$v['tripTicket'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vehicle: '.$v['vehicle'].' </span>
			<em>Schedule: '.date('F d Y h:i A',strtotime($v['datestarted'])).'</em>
			<em title="driver"><i class="fa fa-user"></i> '.$v['driver_name'].' </em>
			<em><i class="fa fa-automobile"></i> '.$fueltype.'</em>
			<em><i class="fa fa-fire"></i> Requested Qty : '.$v['fuel_requested_qty'].' '.$v['uom'].'&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp; Actual Qty : '.$v['fuel_added_qty'].' '.$v['uom'].'</em>
			<i class="fa fa-'.$type.' top-news-icon"></i>
		</a>
	';
}

############### Comments #######################
$msgs = '';
$comment_cntr = 0;
$comments = sqlsrv_query($conn,"select *,CONVERT(VARCHAR(19),AddedAt) as added from vehicle_request_comments where request_id='".$_GET['id']."' order by AddedAt ");
while($c = sqlsrv_fetch_array($comments)){
	$comment_cntr++;
	$msgs.='
			<tr>
				<td>'.$comment_cntr.'</td>
				<td>'.$c['username'].'</td>
				<td>'.date('F d Y h:i A',strtotime($c['added'])).'</td>
				<td>'.$c['comment'].'</td>
			</tr>
	';
}

############### Audit Trail #######################
$trails = '';
$cntr = 0;
$logs_query = sqlsrv_query($conn,"select * from request_logs where request_id='".$_GET['id']."' order by id desc");
while($logs = sqlsrv_fetch_array($logs_query)){
	$cntr++;
	$trails.='
			<tr>
				<td>'.$cntr.'</td>
				<td>'.$logs['action'].'</td>				
			</tr>
	';
}

/*$trails.='
	<tr>
		<td>10</td>
		<td>
			 Updated this request<br>
			 &nbsp;&nbsp;&nbsp;PURPOSE = from: <b>GOING TO B1</b> to: <b>GOING TO B2</b><br>
			 &nbsp;&nbsp;&nbsp;COSTCODE = from: <b>COSTCODE1</b> to: <b>COSTCODE2</b><br>
			 &nbsp;&nbsp;&nbsp;DEPT = from: <b>NEWDEPT</b> to: <b>NEWDEPT2</b><br> 
			 ON 2018-11-22 02:26 AM
		</td>
	</tr>
'
*/

?>
<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="utf-8"/>
<title>Vehicle | Monitoring</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>

<link href="google.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="metronic/assets/admin/pages/css/news.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/pages/css/blog.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
<!-- BEGIN HEADER -->
<?php if(isset($_SESSION['esdvms_username'])){ include("header.php"); }?>
<?php //echo $_SESSION['esdvms_username']."xxxxx"; ?>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<div class="row">
				<div class="col-md-12">
					<div class="breadcrumbs">						
						<ol class="breadcrumb">
							<li>
								<a href="../home.php"><i class="fa fa-home"></i> HOME</a>
							</li>
							<li>
								<a href="request_list.php"><i class="fa fa-list"></i> REQUEST LIST</a>
							</li>
							<li class="active"><i class="fa fa-tags"></i> Summary</li>
						</ol>
						
					</div>
				</div>
			</div>
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12 news-page blog-page">
					<div class="row">
						<div class="col-md-8 blog-tag-data">
							<h3>Request Summary (<?php echo $r['refcode']; ?>)</h3>
							
							<div class="row">
								<div class="col-md-6">
									<ul class="list-inline blog-tags">
										<li>
											<i class="fa fa-tags"></i>
											<a href="#">
											<?php echo $r['name']; ?> </a>
											<a href="#">
											<?php echo $r['dept']; ?> </a>
											
										</li>
									</ul>
								</div>
								<div class="col-md-6 blog-tag-data-inner">
									<ul class="list-inline">
										<li>
											<i class="fa fa-calendar"></i>
											<a href="#" title="Added by <?php echo $r['addedBy']; ?>">
											<?php echo $r['added']; ?> </a>
										</li>
										<li>
											<i class="fa fa-comments"></i>
											<a href="#comment_table">
											<?php echo $comment_cntr; ?> Comments </a>
										</li>
									</ul>
								</div>
							</div>
							<div>
							<h3>Details</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Costcode:</label>
											<label class="control-label col-md-8"><?php echo $r['costcode']; ?></label>											
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Date Needed:</label>
											<label class="control-label col-md-8"><?php echo $r['needed']; ?></label>
										</div>
									</div>
								</div>								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Status:</label>
											<label class="control-label col-md-8"><?php echo $r['status']; ?></label>												
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Purpose:</label>
											<label class="control-label col-md-8"><?php echo $r['purpose']; ?></label>												
										</div>
									</div>
								</div>

								<h3>Pick-up Instructions</h3>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label col-md-3">Dept / Establishment:</label>
											<label class="control-label col-md-9"><?php echo $i['pickup_dept']; ?></label>												
										</div>
									</div>									
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label col-md-3">Location / Site / Address:</label>
											<label class="control-label col-md-9"><?php echo $i['pickup_location']; ?></label>												
										</div>
									</div>
								</div>

								<h3>Delivery Instructions</h3>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Contact Person:</label>
											<label class="control-label col-md-8"><?php echo $i['contact_person']; ?></label>												
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Designation:</label>
											<label class="control-label col-md-8"><?php echo $i['designation']; ?></label>												
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Department:</label>
											<label class="control-label col-md-8"><?php echo $i['dept']; ?></label>												
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Contact No:</label>
											<label class="control-label col-md-8"><?php echo $i['contact_no']; ?></label>												
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label col-md-3">Delivery Site:</label>
											<label class="control-label col-md-9"><?php echo $i['delivery_site']; ?></label>												
										</div>
									</div>									
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label col-md-3">Other Delivery Instructions:</label>
											<label class="control-label col-md-9"><?php echo $i['other_instructions']; ?></label>												
										</div>
									</div>									
								</div>
							</div>
							<hr>
							
						</div>
						<div class="col-md-4">
							<h3>Vehicle Dispatched</h3>
							<div class="top-news">
								<?php echo $list; ?>
							</div>
						
							<div class="space20">
							</div>
							
						</div>
					</div>
					<div class="row">
								<div class="col-md-6">
									<div class="portlet light ">
									    <div class="portlet-title">
									        <div class="caption font-red-sunglo">
									            <i class="fa fa-comments font-red-sunglo"></i>
									            <span class="caption-subject bold uppercase"> Comments</span>
									        </div>
									        <div class="actions">
									            <div class="btn-group">
									            </div>
									        </div>
									    </div>
									    <div class="portlet-body">
									    	<table class="table table-condensed table-striped" id="comment_table">
												<thead>
													<tr>
														<th>Seq</th>
														<th>Sender</th>
														<th>Date</th>
														<th>Comment</th>
													</tr>
												</thead>
												<tbody>
													<?php echo $msgs; ?>
												</tbody>
											</table>
									    </div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="portlet light ">
									    <div class="portlet-title">
									        <div class="caption font-red-sunglo">
									            <i class="fa fa-mail-reply-all font-red-sunglo"></i>
									            <span class="caption-subject bold uppercase"> Audit Trail</span>
									        </div>
									        <div class="actions">
									            <div class="btn-group">
									            </div>
									        </div>
									    </div>
									    <div class="portlet-body">
									    	<table class="table table-condensed">
												<thead>
													<tr>
														<th>Seq</th>															
														<th>Action</th>															
													</tr>
												</thead>
												<tbody>
													<?php echo $trails; ?>
												</tbody>
											</table>
									    </div>
									</div>
								</div>
							</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	
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

<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init() // init quick sidebar
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>