<?php
include("config.php");
session_start();
$st=date('Y-m-d');
$et=date('Y-m-d');
$pla=' selected="selected"';
$plp='';
$pld='';
$tdata='';

if(isset($_GET['startdate'])){
	$st=$_GET['startdate'];
	$et=$_GET['enddate'];
	$intervalss  = abs(strtotime($st) - strtotime($et));
	$minuted   = round($intervalss / 60);
	$tdata='';
	$data='';
	$wdata='';
	$tots=0;
	$vc='';
	if($_GET['pl']==1){
		$vc=' and f.isScheduled=1';
		$pla='';
		$plp=' selected="selected"';
		$pld='';

	}
	elseif($_GET['pl']==2){
		$vc=' and f.isScheduled=0';
		$pla='';
		$plp='';
		$pld=' selected="selected"';

	}
	else{
		$pla=' selected="selected"';
		$plp='';
		$pld='';

	}
	$top=sqlsrv_fetch_array(sqlsrv_query($conn,"select  sum(f.mins) as ttmin from downtimeflatdata f right join unit u on u.id=f.unitId 
		where f.date>='".$_GET['startdate']."' and f.date<='".$_GET['enddate']."' ".$vc.""));
	$q=sqlsrv_query($conn,"select u.id as idd,u.name,sum(f.mins) as tmin from downtimeflatdata f right join unit u on u.id=f.unitId 
		where f.date>='".$_GET['startdate']."' and f.date<='".$_GET['enddate']."' ".$vc."
		GROUP BY u.id,u.name
		ORDER BY sum(f.mins) DESC");
	while($r=sqlsrv_fetch_array($q)){
		$tots+=$r['tmin'];
		$perc=($r['tmin']/$minuted)*100;		
		$mins=number_format(100 - $perc).'%';
		$wdata.=$r['idd'].',';
		$tdata.='<tr>
			<td>'.$r['name'].'</td>
			<td>'.number_format($r['tmin']).' mins </td>		
			<td>'.$mins.'</td>
		</tr>';
		
		$data=rtrim($data,",");

	}

}
if(!isset($_GET['isexcel'])){
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
<body class="page-header-fixed page-full-width" style="height:400%;">

<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			 <form method="get">
				<div class="row">
				<div class="col-md-12">
				  <div class="row">
				    <div class="col-md-3">
				      <div class="form-group">
				        <label class="control-label col-md-3">Start</label>
				        <div class="col-md-9">
				          <div class="input-group date date-picker margin-bottom-5 col-md-12" data-date-format="yyyy-mm-dd">
				            <input type="text" class="form-control form-filter" readonly name="startdate" id="startdate" value="<?php echo $st;?>">
				            <span class="input-group-btn">
				            <button class="btn  default" type="button"><i class="fa fa-calendar"></i></button>
				            </span>
				          </div>            
				        </div>
				      </div>              
				    </div>
				    <div class="col-md-3">
				      <div class="form-group">
				        <label class="control-label col-md-3">End</label>
				        <div class="col-md-9">
				          <div class="input-group date date-picker margin-bottom-5 col-md-12" data-date-format="yyyy-mm-dd">
				            <input type="text" class="form-control form-filter" readonly name="enddate" id="enddate" value="<?php echo $et;?>">
				            <span class="input-group-btn">
				            <button class="btn  default" type="button"><i class="fa fa-calendar"></i></button>
				            </span>
				          </div>                  
				        </div>
				      </div>              
				    </div> 
				     <div class="col-md-6">
				        <table width="100%">
				            <tr>		
				            <td><select name="pl" style="font-style:Arial;font-size:14px;">
				            		<option value="1" <?php echo $plp;?>> Planned Only
				            		<option value="2" <?php echo $pld;?>> Breakdown Downtime Only
				            			<option value="3" <?php echo $pla;?>> All
				            	</select></td>		              
				              <td><input type="submit" class="btn purple" value="Generate"></td>
				            </tr>
				            <tr></tr>
				        </table>
				     </div>               
				  </div>          
				</div>
				</div>    

				</form>
			<div class="row">
				<div class="col-md-12">
					<table width="100%" style="font-style:Arial;font-size:14px;">
													
									<tr style="font-weight:bold;color:blue;">
										<td>Unit</td>
										<td>Mins</td>
										
										<td>Availability %</td>
								 	</tr>								
									<?php echo $tdata;?>
									<tr><td colspan="3"><hr></td></tr>
									<tr><td colspan="3"><a class="btn green" href="rpt_flatdata.php?isexcel=1&<?php echo $_SERVER['QUERY_STRING'];?>">Export to Excel</a>
										&nbsp;<a class="btn purple" onclick="window.open('rpt_flatdata_print.php?isexcel=1&<?php echo $_SERVER['QUERY_STRING'];?>')" href="#">Print</a></td>
										
									</tr>				
							
						
					</table>
				</div>
			</div>

		</div>
	</div>
</div>
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
<?php } 

else{
$filename =date('Ymdhis').".xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);

echo '<table width="100%" style="font-style:Arial;font-size:14px;">
													
									<tr style="font-weight:bold;color:blue;">
										<td>Unit</td>
										<td>Mins</td>
										
										<td>Availability %</td>
								 	</tr>								
									'.$tdata.'
							
						
					</table>';
}?>