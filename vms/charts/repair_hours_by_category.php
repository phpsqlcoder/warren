
<?php
include('../config.php');

ob_start();
session_start();


if(!isset($_GET['startd'])){
  $start=date('2018-05-28');
  $end=date('Y-m-d');
}
else{
  $start=$_GET['startd'];
  $end=$_GET['endd'];
}

$cond = " and dateStart>='".$start."' and dateEnd<='".$end." 23:59:59'";
$now = strtotime($end); // or your date as well
$your_date = strtotime($start);
$datediff = $now - $your_date;
$diff=floor($datediff / (60 * 60 * 24));

$iq=sqlsrv_query($conn,"select isScheduled,sum(trepair_hours) as total from downtime where tdowntime>0 and active=1 ".$cond." group by isScheduled");
$idata='';
while($i=sqlsrv_fetch_array($iq)){
	$idata.=' {
                 "label": "'.($i['isScheduled']==1 ? 'Preventive':'Breakdown').'",
                 "value": "'.number_format($i['total'],2).'"
              },';
}
$idata=rtrim($idata,",");


?>
<!DOCTYPE html>


<script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
<script type="text/javascript" src="http://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js?cacheBust=56"></script>
<script type="text/javascript">
 FusionCharts.ready(function(){
      var revenueChart = new FusionCharts({
        "type": "pie2d",
        "renderAt": "chartContainer",
        "width": "100%",
        "height": "400",
        "dataFormat": "json",
        "dataSource": {
          "chart": {
              "caption": "",
              "subCaption": "",
              "showpercentvalues": "1",
           },
          "data": [
              <?php echo $idata;?>
           ]
        }
    });

    revenueChart.render();
})
</script>
														
<div class="portlet-body" id="chartContainer">									
	<img src="../metronic/assets/admin/layout/img/loading.gif" alt="loading"/>								
</div>		

</body>
</html>