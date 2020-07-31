<?php
include('../config.php');

ob_start();
session_start();

$cond = "";
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



$iq=sqlsrv_query($conn,"select u.required_availability_hours,u.name,sum(tdowntime) as total from downtime d left join unit u on u.id=d.unitId
  where d.tdowntime>0 and d.active=1 and u.type='Medium Vehicle' ".$cond." group by u.required_availability_hours,u.name");
$idata='';
while($i=sqlsrv_fetch_array($iq)){
  $mtd = 100 - ($i['total']/($diff * $i['required_availability_hours']));
  $idata.=' {
                 "label": "'.$i['name'].'",
                 "value": "'.number_format($mtd,2).'"
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
        "type": "column2d",
        "renderAt": "chartContainer",
        "width": "100%",
        "height": "200",        
        "dataFormat": "json",
        "dataSource": {
          "chart": {
              "caption": "",
              "subCaption": "",
              "numbersuffix": "%",
              "yaxismaxvalue": "100",
              "theme": "fint",
              "palettecolors": "#0075c2",
              "alignCaptionWithCanvas": "0",
              "captionHorizontalPadding": "2",
              "captionOnTop": "0",
              "captionAlignment": "right"
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