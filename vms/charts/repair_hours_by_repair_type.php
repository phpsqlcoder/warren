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



$iq=sqlsrv_query($conn,"select repairType,sum(trepair_hours) as total from downtime where tdowntime>0 and active=1 ".$cond." group by repairType");
//die("select repairType,sum(trepair_hours) as total from downtime where tdowntime>0 ".$cond." group by repairType");
$idata='';
while($i=sqlsrv_fetch_array($iq)){
  $idata.=' {
                 "label": "'.$i['repairType'].'",
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
        "type": "bar2d",
        "renderAt": "chartContainer",
        "width": "100%",
        "height": "200",        
        "dataFormat": "json",
        "dataSource": {
          "chart": {
              "caption": "",
              "subCaption": "",
              "numbersuffix": "",
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