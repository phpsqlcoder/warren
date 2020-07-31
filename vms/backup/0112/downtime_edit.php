<?php
include("config.php");
session_start();
$id=$_GET['id'];
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
	if($_GET['act']=='editdowntime'){
		$delete=sqlsrv_query($conn,"delete from downtimeflatdata where downtimeId='".$id."'");
		$is_sched = (isset($_POST['isscheduled'])? '1' : '0');
		$upd=sqlsrv_query($conn,"update downtime set unitId = '".$_POST['unit']."', 
			dateStart= '".$_POST['startd']."',
			dateEnd='".$_POST['endd']."',
			remarks='".$_POST['remarks']."',
			isScheduled='".$is_sched."'
			where id='".$id."'");
		$lastins=$id; 
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
	}
	
}
$r=sqlsrv_fetch_array(sqlsrv_query($conn,"select CONVERT(VARCHAR(16),dateStart,120) as ds,CONVERT(VARCHAR(16),dateEnd,120) as de,* from downtime where id='".$id."'"));
?>
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
<body style="background-color:white;">
<div class="page-container">
<form method="post" action="downtime_edit.php?act=editdowntime&id=<?php echo $id;?>">
	<div class="modal-header">
	
		<h4 class="modal-title">Input Downtime Details</h4>
	</div>
	<div class="modal-body">							 
		 	<div class="form-group" style="height:200px">	
		 		<label class="control-label col-md-3">Unit</label>
		 		<div class="col-md-9">
		 			<select class="form-control" name="unit">
		 				<?php
		 					$uq=sqlsrv_query($conn,"select * from unit order by name");
		 					while($u=sqlsrv_fetch_array($uq)){
		 						$select='';				 						
	 							if($u['id']==$r['unitId']){
	 								$select=' selected="selected"';
	 							}				 										 					
		 						echo '<option value="'.$u['id'].'" '.$select.'>'.$u['name'];
		 					}
		 				?>
		 			</select>
		 		</div>							
				<label class="control-label col-md-3">Start</label>
				<div class="col-md-9">
					<div class="input-group date form_datetime">
						<input type="text" size="16" name="startd" readonly class="form-control" value="<?php echo $r['ds'];?>">
						<span class="input-group-btn">
						<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
						</span>
					</div>									
				</div>
				<br><br>
				<label class="control-label col-md-3">End</label>
				<div class="col-md-9">
					<div class="input-group date form_datetime">
						<input type="text" size="16" name="endd" readonly class="form-control" value="<?php echo $r['de'];?>">
						<span class="input-group-btn">
						<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
						</span>
					</div>									
				</div>
				<br><br>									
				<label class="control-label col-md-3">Remarks:</label>
				<div class="col-md-9">
					<textarea cols="53" rows="5" name="remarks"><?php echo $r['remarks'];?></textarea>								
				</div>
				
				<label class="col-md-12">
					<input name="isscheduled" type="checkbox" <?php if($r['isScheduled']==1){ echo 'checked="checked"';}?>> Scheduled Downtime</label>
				<br><br>
				<input type="submit" class="btn blue" value="Save">
			</div>							

	</div>

	</form>
	</div>
</body>
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

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/components-pickers.js"></script>

<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
   Layout.init(); // init current layout

   Index.init();

   Index.initDashboardDaterange();
   ComponentsPickers.init();

});
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>