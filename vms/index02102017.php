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
	$cond.=" and location='".$_GET['s_location']."'";
}
if(strlen($_GET['s_type'])>1){
	$cond.=" and type='".$_GET['s_type']."'";
}
if(strlen($_GET['s_name'])>1){
	$cond.=" and name='".$_GET['s_name']."'";
}

function computemins($s,$e){	
	$from_time = strtotime($s);
	$to_time = strtotime($e);
	return round(abs($to_time - $from_time) / 60,0);
}
//echo computemins("2016-12-06 10:45","2016-12-06 23:59");die();


function interval( $startDate , $endDate, $type ){
	if($type=='Weekly'){
		if(date('D', strtotime($startDate)) === 'Mon') {
			$startDate=$startDate;
		}
		else{
			$startDate=date('Y-m-d', strtotime('last Monday', strtotime($startDate)));
		}
	}
	if($type=='Monthly'){
		if(date('j', strtotime($startDate)) === '1') {
			$startDate=$startDate;
		}
		else{
			$startDate=$startDate;
		}
	}
  $startDate = strtotime( $startDate );
  $endDate   = strtotime( $endDate );

 // New Variables
  $currDate  = $startDate;
  $dayArray  = array();

	 // Loop until we have the Array
	if($type=='Daily'){
	  do{
	    $dayArray[] = date( 'Y-m-d' , $currDate );
	    $currDate = strtotime( '+1 day' , $currDate );
	  } while( $currDate<=$endDate );
	}
	if($type=='Weekly'){
	  do{
	    $dayArray[] = date( 'Y-m-d' , $currDate );
	    $currDate = strtotime( '+1 week' , $currDate );
	  } while( $currDate<=$endDate );
	}
	if($type=='Monthly'){
	  do{
	    $dayArray[] = date( 'Y-m-d' , $currDate );
	    $currDate = strtotime( '+1 month' , $currDate );
	  } while( $currDate<=$endDate );
	}
	 // Return the Array
	  return $dayArray;
}

if(isset($_GET['act'])){
	if($_GET['act']=='submitdowntime'){
		$is_sched = (isset($_POST['isscheduled'])? '1' : '0');
		$insert="insert into downtime (dateStart,dateEnd,remarks,addedBy,addedDate,unitId,isScheduled)
			VALUES('".$_POST['startd']."','".$_POST['endd']."','".$_POST['remarks']."','".$_SESSION['esdvms_username']."','".date('Y-m-d h:i:s')."','".$_POST['unit']."','".$is_sched."'); SELECT SCOPE_IDENTITY()";
		$resource=sqlsrv_query($conn, $insert); 
		sqlsrv_next_result($resource); 
		sqlsrv_fetch($resource); 
		$lastins=sqlsrv_get_field($resource, 0); 
		$ns=$_POST['startd'];
		$ne=$_POST['endd'];
		//echo $lastins." - ".$ns." aa ".$ne."<br>";
		$arr=array();
		$arrd=array();
		$begin = date("Y-m-d",strtotime($ns));
		$end =date("Y-m-d",strtotime($ne));
		$begintime = date("H:i:s",strtotime($ns));
		$endtime = date("H:i:s",strtotime($ne));
		$date1=date_create($begin);
		$date2=date_create($end );		
		$diff=date_diff($date1,$date2);
		$dif=$diff->format("%a days");
		if($dif==0){
			$arr[0]=computemins($begin." ".$begintime,$end." ".$endtime);
			$arrd[0]=$begin;
		}
		elseif($dif==1){
			$arr[0]=computemins($begin." ".$begintime,$begin." 23:59:59");
			$arrd[0]=$begin;
			$arr[1]=computemins($end." 00:00:00",$end." ".$endtime);
			$arrd[1]=$end;
		}
		elseif($dif>1){
			$arr[0]=computemins($begin." ".$begintime,$begin." 23:59:59");
			$arrd[0]=$begin;			
			$newstart=strtotime(date('Y-m-d', strtotime($begin . ' +1 day')));
			$newend=strtotime(date('Y-m-d', strtotime($end . ' -1 day')));
			$m=0;
			for ( $i = $newstart; $i <= $newend; $i += 86400 ){
				$m++;
				$datelog=date('Y-m-d',$i);
				$arr[$m]=1440;
				$arrd[$m]=$datelog;
			}
			$m++;
			$arr[$m]=computemins($end." 00:00:00",$end." ".$endtime);
			$arrd[$m]=$end;
		}
		$totalmi=0;
		foreach ($arrd as $key => $value){
			//echo $value." = ".$arr[$key]."<br>";
			$ins=sqlsrv_query($conn,"insert into downtimeflatdata (date,mins,unitId,isScheduled,downtimeId,remarks)
				VALUES('".$value."','".$arr[$key]."','".$_POST['unit']."','".$is_sched."','".$lastins."','".$_POST['remarks']."')");
			$totalmi+=$arr[$key];
		}
		//echo $totalmi."<br>";		echo computemins($ns,$ne)."<br>";

	}
	if($_GET['act']=='newunit'){
		$insert_unit=sqlsrv_query($conn,"insert into unit (name,type,location) VALUES ('".$_POST['unit']."','".$_POST['category']."','".$_POST['location']."')");
	}
	header("location:index.php?".$_POST['olr_url']);
}
$total=0;
$header='<tr><td>Item #</td><td>&nbsp;</td>';
foreach (interval($_GET['startDate'],$_GET['endDate'],$_GET['datetype']) as $a){
	$total++;
	
	if($_GET['datetype']=='Weekly'){
		$header.='<td align="center">'.date('M d',strtotime($a)).' -<br>'.date('M d', strtotime("+6 days",strtotime($a))).'</td>';	
	}
	elseif($_GET['datetype']=='Daily'){
		$header.='<td align="center">'.date('M d',strtotime($a)).'</td>';																	
	}
	elseif($_GET['datetype']=='Monthly'){
		$header.='<td align="center">'.date('M d',strtotime($a)).' -<br>'.date('M t', strtotime($a)).'</td>';
	}	
	//echo $a."<br>";
}
$header.='</tr>';
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

<link href="metronic/assets/global/plugins/nouislider/jquery.nouislider.css" rel="stylesheet" type="text/css"/>


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
<!-- BEGIN HEADER -->



<?php include("header.php");?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade bs-modal-lg" id="inputdowntime" tabindex="-1" role="inputdowntime" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" id="downtimeform" action="index.php?act=submitdowntime">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Input Downtime Details</h4>
						</div>
						<div class="modal-body">							 
							 	<div class="form-group" style="height:200px">	
							 		
							 		<label class="control-label col-md-3">Unit</label>
							 		<div class="col-md-9">
							 			<select class="form-control" name="unit" id="unit" onchange="checkinput();">
							 				<?php
							 					$uq=sqlsrv_query($conn,"select * from unit order by name");
							 					while($u=sqlsrv_fetch_array($uq)){
							 						echo '<option value="'.$u['id'].'">'.$u['name'];
							 					}
							 				?>
							 			</select>
							 		</div>							
									<label class="control-label col-md-3">Start</label>
									<div class="col-md-9">
										<div class="input-group date form_datetime">
											<input type="text" size="16" name="startd" id="startd" readonly class="form-control" onchange="checkinput();">
											<span class="input-group-btn">
											<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>									
									</div>
									<br><br>
									<label class="control-label col-md-3">End</label>
									<div class="col-md-9">
										<div class="input-group date form_datetime">
											<input type="text" size="16" name="endd" id="endd" readonly class="form-control" onchange="checkinput();">
											<span class="input-group-btn">
											<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>									
									</div>
									<br><br>									
									<label class="control-label col-md-3">Remarks:</label>
									<div class="col-md-9">
										<textarea cols="53" rows="5" name="remarks"></textarea>								
									</div>
									
									<label class="col-md-12">
										<input name="isscheduled" type="checkbox"> Scheduled Downtime</label>
									<br><br>
								</div>							
							 <input type="hidden" name="olr_url" value="<?php echo $_SERVER['QUERY_STRING'];?>">
						</div>
							
							<div class="modal-footer" id="footermode">
								<button type="button" class="btn default" data-dismiss="modal">Cancel</button>
								<input type="submit" class="btn blue" value="Save">
							</div>
						
						</form>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->

			</div>

			<!-- /.modal -->
			<div class="modal fade bs-modal-lg" id="munit" tabindex="-1" role="munit" aria-hidden="true">
				<div class="modal-dialog">
					 <form method="post" action="index.php?act=newunit">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Add New Unit</h4>
						</div>
						<div class="modal-body">
							
							 	<div class="form-group">
									<label class="col-md-3 control-label">Description</label>
									<div class="col-md-9">
										<input type="text" class="form-control" name="unit" placeholder="Enter text">										
									</div>
								</div><BR>	<BR><BR>
								<div class="form-group">
									<label class="col-md-3 control-label">Category</label>
									<div class="col-md-9">
										<select class="form-control" name="category">
											<option value=""> - Select -
											<option value="ASELCO POWER MAIN INCOMER">ASELCO POWER MAIN INCOMER
											<option value="GENSET UNITS AND GENSET BREAKERS">GENSET UNITS AND GENSET BREAKERS
											<option value="FEEDER BREAKERS">FEEDER BREAKERS
										</select>							
									</div>
								</div><BR>	<BR>
								<div class="form-group">
									<label class="col-md-3 control-label">Location</label>
									<div class="col-md-9">
										<select class="form-control" name="location">
											<option value=""> - Select -
											<option value="MINE">MINE
											<option value="MILL">MILL											
										</select>								
									</div>
								</div><BR>						
							<input type="hidden" name="olr_url" value="<?php echo $_SERVER['QUERY_STRING'];?>">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">Cancel</button>
							<input type="submit" class="btn blue" value="Save">
						</div>
					</div>
					<!-- /.modal-content -->
					 </form>
				</div>
				<!-- /.modal-dialog -->
				
			</div>
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- /.modal -->
			
			<!-- BEGIN PAGE HEADER-->
			<form method="get" act="index.php">
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
					ESD <small>Availability Records</small>
					</h3>
					
					<ul class="page-breadcrumb breadcrumb">
						<li>							
							<a href="#">Filters:</a>							
						</li>
						<li>			
							<input type="hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
							<input type="hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">		
							<input type="hidden" name="datetype" value="<?php echo $_GET['datetype'];?>">						
							<select class="form-control input-sm" name="s_location" id="s_location" onchange="changefilters();">
				 				<?php
				 						echo '<option value="" selected="selected"> - Location -';
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
							<select class="form-control input-sm" name="s_type" id="s_type"onchange="changefilters();">
				 				<?php
				 						echo '<option value="" selected="selected"> - Category -';
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
						<li id="unitlist">
							<select class="form-control input-sm" name="s_name" id="s_name">
				 				<?php
				 						echo '<option value="" selected="selected"> - Unit -';
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
						<li class="pull-right">
							<div id="dashboard-report-range" class="dashboard-date-range tooltips" data-placement="top" data-original-title="Change dashboard date range">
								<i class="icon-calendar"></i>
								<span></span>
								<i class="fa fa-angle-down"></i>
							</div>
						</li>
					</ul>
					
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
				</form>
					<input type="hidden" name="hiddenstart" id="hiddenstart" value="<?php echo $_GET['startDate'];?>">
					<input type="hidden" name="hiddenend" id="hiddenend" value="<?php echo $_GET['endDate'];?>">
					<input type="hidden" name="hiddens_location" id="hiddens_location" value="<?php echo $_GET['s_location'];?>">
					<input type="hidden" name="hiddens_name" id="hiddens_name" value="<?php echo $_GET['s_name'];?>">
					<input type="hidden" name="hiddens_type" id="hiddens_type" value="<?php echo $_GET['s_type'];?>">
			<!-- END PAGE HEADER-->
			<?php
				$s1=sqlsrv_fetch_array(sqlsrv_query($conn,"select sum (d.mins) as tomins from downtimeflatdata d left join unit u on u.id=d.unitId where u.type='ASELCO POWER MAIN INCOMER' and d.date>='".$_GET['startDate']."' and d.date<='".$_GET['endDate']."'"));
				
				$s2=sqlsrv_fetch_array(sqlsrv_query($conn,"select sum (d.mins) as tomins from downtimeflatdata d left join unit u on u.id=d.unitId where u.type='FEEDER BREAKERS' and date>='".$_GET['startDate']."' and d.date>='".$_GET['startDate']."' and d.date<='".$_GET['endDate']."'"));
				$s3=sqlsrv_fetch_array(sqlsrv_query($conn,"select sum (d.mins) as tomins from downtimeflatdata d left join unit u on u.id=d.unitId where u.type='GENSET UNITS AND GENSET BREAKERS' and d.date>='".$_GET['startDate']."' and d.date<='".$_GET['endDate']."'"));

			?>
			<!-- BEGIN DASHBOARD STATS -->
			
	
			<div class="row">				
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat blue-madison">
						<div class="visual">
							<i class="fa fa-comments"></i>
						</div>
						<div class="details">
							<div class="number">
								<?php echo number_format($s1['tomins']);?> mins
							</div>
							<div class="desc">
								 ASELCO POWER MAIN INCOMER
							</div>
						</div>
						<a class="more" href="#">
						View more <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat red-intense">
						<div class="visual">
							<i class="fa fa-bar-chart-o"></i>
						</div>
						<div class="details">
							<div class="number">
								 <?php echo number_format($s2['tomins']);?> mins
							</div>
							<div class="desc">
								 FEEDER BREAKERS
							</div>
						</div>
						<a class="more" href="#">
						View more <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat green-haze">
						<div class="visual">
							<i class="fa fa-shopping-cart"></i>
						</div>
						<div class="details">
							<div class="number">
								 <?php echo number_format($s3['tomins']);?> mins
							</div>
							<div class="desc">
								 GENSET UNITS AND GENSET BREAKERS
							</div>
						</div>
						<a class="more" href="#">
						View more <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				
			</div>
			<div class="clearfix">
			</div>
			<div class="row">	
				<div class="col-md-12">										
							<div class="col-md-1"><input id="slider_2_input_startxx" type="text" class="form-control btn red-thunderbird white" value="0 %" name="fromxx" readonly="readonly"><input id="slider_2_input_start" type="hidden" class="form-control btn red-thunderbird white" name="from" readonly="readonly"></div>
							<div class="col-md-10"><div class="noUi-control noUi-danger" id="slider_2">	</div></div>
							<div class="col-md-1"><input id="slider_2_input_endxx" type="text" class="form-control btn green white" name="toxx" value="100 %" readonly="readonly"><input id="slider_2_input_end" type="hidden" class="form-control btn green white" name="to" readonly="readonly"></div>
				</div>
			</div>
			<!-- END DASHBOARD STATS -->
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet solid bordered grey-cararra">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-chart-o"></i>Monitoring
							</div>
							<div class="tools">
								<div class="btn-group" data-toggle="buttons">
									<label class="btn grey-steel btn-sm <?php if($_GET['datetype']=='Daily'){echo 'active'; }?>">
									<input type="radio" name="datetype" <?php if($_GET['datetype']=='Daily'){echo 'checked="checked"'; }?> class="toggle" value="Daily" id="option1" onchange="refresh_all();">Daily</label>
									<label class="btn grey-steel btn-sm <?php if($_GET['datetype']=='Weekly'){echo 'active'; }?>">
									<input type="radio" name="datetype" <?php if($_GET['datetype']=='Weekly'){echo 'checked="checked"'; }?> class="toggle" value="Weekly" id="option2" onchange="refresh_all();">Weekly</label>
									<label class="btn grey-steel btn-sm <?php if($_GET['datetype']=='Monthly'){echo 'active'; }?>">
									<input type="radio" name="datetype" <?php if($_GET['datetype']=='Monthly'){echo 'checked="checked"'; }?> class="toggle" value="Monthly" id="option3" onchange="refresh_all();">Monthly</label>
								</div>
							</div>
						</div>
						<div class="portlet-body" style="overflow: auto;">
							<?php
								$data='';
								$ucntr=0;
								$catq=sqlsrv_query($conn,"select distinct type from unit where id>0 ".$cond." ");
								while($cat=sqlsrv_fetch_array($catq)){
									$data.='<tr><td>&nbsp;</td><td style="font-weight:bold;color:blue;font-size:16px;" align="center">'.$cat['type'].'</td></tr>';
									$locq=sqlsrv_query($conn,"select distinct location from unit where type='".$cat['type']."' ".$cond."");
									while($loc=sqlsrv_fetch_array($locq)){
										$data.='<tr><td>&nbsp;</td><td align="center">'.$loc['location'].'</td></tr>';
										$uq=sqlsrv_query($conn,"select * from unit where type='".$cat['type']."' and location='".$loc['location']."' ".$cond."");
										while($u=sqlsrv_fetch_array($uq)){
											$ucntr++;
											$data.='<tr>
														<td align="center"><a href="#" onclick=\'window.open("unit_edit.php?id='.$u['id'].'","displayWindow","toolbar=no,scrollbars=yes,width=910,height=600"); return false;\';>'.$ucntr.'</a></td>
														<td align="center">'.$u['name'].'</td>';
														foreach(interval($_GET['startDate'],$_GET['endDate'],$_GET['datetype']) as $x){														
															if($_GET['datetype']=='Weekly'){
																$lastdate=date('Y-m-d', strtotime("+6 days",strtotime($x)));
																$intervalss  = abs(strtotime($x) - strtotime($lastdate));
																$minuted   = round($intervalss / 60) + 1440;
																$r=sqlsrv_fetch_array(sqlsrv_query($conn,"select sum(mins) as tmin from downtimeflatdata where date>='".$x."' and date<='".$lastdate."' and unitId='".$u['id']."'"));
																$allremarks='';
																$remq=sqlsrv_query($conn,"select distinct remarks from downtimeflatdata where date>='".$x."' and date<='".$lastdate."' and unitId='".$u['id']."'");
																while($rem=sqlsrv_fetch_array($remq)){
																	$allremarks.=$rem['remarks'].'';
																}
																
															}
															elseif($_GET['datetype']=='Daily'){
																$minuted   = 1440;
																$r=sqlsrv_fetch_array(sqlsrv_query($conn,"select sum(mins) as tmin from downtimeflatdata where date='".$x."' and unitId='".$u['id']."'"));																
																$allremarks='';
																$remq=sqlsrv_query($conn,"select distinct remarks from downtimeflatdata where date>='".$x."' and unitId='".$u['id']."'");
																while($rem=sqlsrv_fetch_array($remq)){
																	$allremarks.=$rem['remarks'].'';
																}
															}
															elseif($_GET['datetype']=='Monthly'){
																$lastdate=date('Y-m-t', strtotime($x));
																$intervalss  = abs(strtotime($x) - strtotime($lastdate));
																$minuted   = round($intervalss / 60);
																$r=sqlsrv_fetch_array(sqlsrv_query($conn,"select sum(mins) as tmin from downtimeflatdata where date>='".$x."' and date<='".$lastdate."' and unitId='".$u['id']."'"));
																$allremarks='';
																$remq=sqlsrv_query($conn,"select distinct remarks from downtimeflatdata where date>='".$x."' and date<='".$lastdate."' and unitId='".$u['id']."'");
																while($rem=sqlsrv_fetch_array($remq)){
																	$allremarks.=$rem['remarks'].'';
																}
															}															
																														
															if($r['tmin']>0){
																$perc=($r['tmin']/$minuted)*100;
																$mins=number_format($r['tmin']).' mins  ('. number_format(100 - $perc).'%)';
																$datamins=' class="tdtab" data="'.(100 - $perc).'"';
																$bgcolor="background-color:green;color:white;";
															}
															else{
																$bgcolor='background-color:green;color:black;';
																$mins='100%';
																$datamins=' class="tdtab" data="100"';
															}	
															$data.='<td style="'.$bgcolor.'" align="center" '.$datamins.'><a href="#" onclick="return false;" data-toggle="popover" title="'.$mins.'" data-content="'.$allremarks.'" data-trigger="hover" style="color:white;" data-placement="top">&nbsp;'.$mins.'</a></td>';
														}
											$data.='</tr>';
										}
									}
								}
							?>
							<table class="table table-bordered" style="font-size:12px;">
								<?php echo $header;?>
								<?php echo $data;?>
							</table>
						</div>
					</div>
					<!-- END PORTLET-->
				</div>				
			</div>
			<div class="clearfix">
			</div>
			<div class="row ">
				<div class="col-md-12 col-sm-12">
					<div class="portlet box blue-steel">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bell-o"></i>Recent Downtime Logs
							</div>
							
						</div>
						<div class="portlet-body">
							<div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
								<ul class="feeds">
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
										$lq=sqlsrv_query($conn,"select CONVERT(VARCHAR(19),d.dateStart) as ds,CONVERT(VARCHAR(19),d.dateEnd) as de,d.*,u.name as uni from downtime d left join unit u on u.id=d.unitId order by d.id desc");
										while($l=sqlsrv_fetch_array($lq)){											
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
								</ul>
							</div>
							<div class="scroller-footer">
								<div class="btn-arrow-link pull-right">
									<a href="downtime_list.php" target="_blank">See All Records</a>
									<i class="icon-arrow-right"></i>
								</div>
							</div>
						</div>
					</div>
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

<script src="metronic/assets/global/plugins/nouislider/jquery.nouislider.min.js"></script>


<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/components-pickers.js"></script>
<script src="metronic/assets/admin/pages/scripts/components-nouisliders.js"></script>


<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   $('[data-toggle="popover"]').popover(); 
   Index.init();
   Index.initDashboardDaterange();   
   ComponentsPickers.init();
   ComponentsNoUiSliders.init();
    $("#slider_2").trigger("change");
});
</script>
<script>
	var ComponentsNoUiSliders = function () {

    return {
        //main function to initiate the module
        init: function () {

            // slider 

          
            // slider 2
            $('#slider_2').noUiSlider({
                direction: (Metronic.isRTL() ? "rtl" : "ltr"),
                range: {
                    min: 0,
                    max: 100
                },
                start: [0, 100],
                handles: 2,
                connect: true,
                step: 1,
                serialization: {
                    lower: [
                        $.Link({
                            target: $("#slider_2_input_start"),
                            method: "val"
                        })
                    ],
                    upper: [
                        $.Link({
                            target: $("#slider_2_input_end"),
                            method: "val"
                        })
                    ]
                }

            });

            $('#slider_2').on('slide', function(){
            	$("#slider_2_input_startxx").val(parseInt($("#slider_2_input_start").val())+' %');
            	$("#slider_2_input_endxx").val(parseInt($("#slider_2_input_end").val())+' %');
            	var st=parseInt($("#slider_2_input_start").val());
				var en=parseInt($("#slider_2_input_end").val());				  				
				$( ".tdtab" ).each(function() {
				  var x=parseInt($( this ).attr( "data" ));
				  if(x<=st){
				  	$(this).css("background-color", "#D91E18");
				  	//alert(x + '-' + st);
				  }
				  if(x>=en){
				  	$(this).css("background-color", "#008000");
				  }
				  if(x>st && x<en){
				  	$(this).css("background-color", "#FF4500");
				  }
				  
				  
				});
			});

        }

    };

}();

</script>
<script>
	function changefilters(){
		 $.ajax({
			  method: "POST",
			  url: "ajax.php?act=changefilters",
			  data: { s_location: $('#s_location').val(), s_type: $('#s_type').val(), s_name: $('#s_name').val()}
			})
		  .done(function( html ) {
		    $( "#unitlist" ).html( html );
		  });
	}
	function refresh_all(){
		var datetype=$('input[name=datetype]:checked').val();
		var start=$('#hiddenstart').val();
		var end=$('#hiddenend').val();
		var s_location=$('#hiddens_location').val();
		var s_type=$('#hiddens_type').val();
		var s_name=$('#hiddens_name').val();
		window.location.href = "index.php?startDate="+start+"&endDate="+end+"&datetype="+datetype+"&s_location="+s_location+"&s_type="+s_type+"&s_name="+s_name;
		//alert(datetype);
	}
	function checkinput(){	
		if ($("#unit").val() != "" && $("#startd").val() != "" && $("#endd").val() != "" ){
		 	 $.ajax({
			  method: "POST",
			  url: "ajax.php?act=checkinput",
			  data: { unit: $('#unit').val(), startd: $('#startd').val(), endd: $('#endd').val()}
			})
		 	/* .done(function( html ) {
			    alert();
			  });	*/
			    .done(function( html ) {			    			    	
				  	var rs = String(html);
				  	if(rs.length==0){		  	
				  		var n="<button type='button' class='btn default' data-dismiss='modal'>Cancel</button><input type='submit' class='btn blue' value='Save'>";
						$( "#footermode" ).html( n );	
				  	
				  	}
				  	else{
				   		$( "#footermode" ).html( html );				   	
				   	}
				  });	
		}
		else{
			//alert("no value!");
		}
	}
	function hasValue(elem) {
	    return $(elem).filter(function() { return $(this).val(); }).length > 0;
	}
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>