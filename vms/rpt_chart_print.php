<?php
include("config.php");
session_start();
$st=date('Y-m-d');
$et=date('Y-m-d');
$tdata='';
if(isset($_GET['startdate'])){
	$st=$_GET['startdate'];
	$et=$_GET['enddate'];
	$intervalss  = abs(strtotime($st) - strtotime($et));
	$minuted   = round($intervalss / 60);
	
	$data='';
	$wdata='';
	$tots=0;
	$top=sqlsrv_fetch_array(sqlsrv_query($conn,"select  sum(f.mins) as ttmin from downtimeflatdata f right join unit u on u.id=f.unitId 
		where f.date>='".$_GET['startdate']."' and f.date<='".$_GET['enddate']."'"));
	$q=sqlsrv_query($conn,"select u.id as idd,u.name,sum(f.mins) as tmin from downtimeflatdata f right join unit u on u.id=f.unitId 
		where f.date>='".$_GET['startdate']."' and f.date<='".$_GET['enddate']."'
		GROUP BY u.id,u.name
		ORDER BY sum(f.mins) DESC");
	while($r=sqlsrv_fetch_array($q)){
		$tots+=$r['tmin'];
		$perc=($tots/$top['ttmin'])*100;
		$perc=100 - $perc;
		$mins=number_format(100 - $perc).'%';
		$wdata.=$r['idd'].',';
		$tdata.='<tr><td>'.$r['name'].'</td>
		<td>'.number_format($r['tmin']).' mins </td>
		<td>'.number_format($tots).'</td>
		<td>'.$mins.'</td>
		</tr>';
		$data.='
		{
            "label": "'.$r['name'].'",
            "value": "'.$r['tmin'].'"
        },
		';
		$data=rtrim($data,",");
	}
	$wdata=rtrim($wdata,",");
	//echo $wdata; echo "select * from unit where id not in (".$wdata.")"; die();
	$q=sqlsrv_query($conn,"select * from unit where id not in (".$wdata.")");
	while($r=sqlsrv_fetch_array($q)){		
		$tdata.='<tr><td>'.$r['name'].'</td><td>-</td><td>-</td></tr>';
	}
}
//die();
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

<link rel="shortcut icon" href="favicon.ico"/>
<style>
	.popover-title {
    color: black;
    
	}
	.popover-content {
	    color: black;
	   
	}
</style>

 <script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
<script type="text/javascript" src="http://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js?cacheBust=56"></script>

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
<body class="page-header-fixed page-full-width">
	<table width="100%" style="font-family:Arial;font-size:12px;">
		<tr>		
			<td colspan="5">
			 <div class="row">			        
		          <div id="chart-container"></div>			        
		      </div>
			</td>			
		</tr>									
		<tr style="font-weight:bold;color:blue;">
			<td>Unit</td>
			<td>Mins</td>
			<td>Accumulative Amount</td>
			<td>Cummulative %</td>
	 	</tr>								
		<?php echo $tdata;?>
				
			
			
		
	</table>
	
</body>
<script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->


<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	END PAGE LEVEL SCRIPTS -->
<script>
   
    FusionCharts.ready(function(){
    	FusionCharts.printManager.enabled(true);
	    var fusioncharts = new FusionCharts({
	    type: 'pareto3d',
	    renderAt: 'chart-container',
	    width: '100%',
	    height: '400',
	    dataFormat: 'json',
	    dataSource:{
		    "chart": {
		        "theme": "fint",
		        "caption": "Pareto Chart",
		        "subCaption": "<?php echo $st;?> to <?php echo $et;?>",
		        "xAxisName": "Units",
		        "pYAxisName": "Total Minutes",
		        "sYAxisname": "Cumulative Percentage",
		        "showValues": "0",
		        "showXAxisLine": "1",
		        "showLineValues": "1"
		    },
		    "data": [
		        <?php echo $data;?>
		    ]
		}
	    
	}
	);
	    fusioncharts.render();
	});

</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>