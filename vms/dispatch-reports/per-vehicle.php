<?php
   include("../config.php");
   session_start();
   if(!$_SESSION['esdvms_username']){
   	header("location:../login.php");
   }
   $data='';
   $cdata='';
   $caption_unit = 'All Department';
   if(!isset($_GET['start'])){
      $_GET['end']=date('Y-m-d');
      $_GET['start']=date('Y-m-d',strtotime("-29 days"));
      $_GET['display']='bar2d';
      $_GET['dept']='';
      $_GET['unit']='';      
   }

   if(!isset($_GET['displaytype'])){
      $_GET['displaytype'] = 'full';
   }


   if(isset($_GET['start'])){
      $cond = '';
      $seq = 0;

      if(isset($_GET['unit']) && $_GET['unit']!=''){
         $cond .= " and u.id='".$_GET['unit']."'";         
      }
      if(isset($_GET['dept']) && $_GET['dept']!=''){
         $cond .= " and de.id='".$_GET['dept']."'";
         $depq = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from department where id='".$_GET['dept']."'"));
         $caption_unit = $depq['name'];

      }
      if(isset($_GET['start']) && $_GET['start']!=''){
         $cond .= " and d.date>='".$_GET['start']."'";
      }
      if(isset($_GET['end']) && $_GET['end']!=''){
         $cond .= " and d.date<='".$_GET['end']."'";
      }
      $qry = sqlsrv_query($conn,"select u.equipment,u.brand,u.model,u.avNo,u.location, sum(d.mins) as total from  dispatchflatdata d 
                                 right join unit u on u.id=d.unitId                               
                                 left join department de on de.id=d.deptId
                                 where u.id>0 ".$cond."
                                 group by u.equipment,u.brand,u.model,u.avNo,u.location
                                 order by sum(d.mins) DESC,u.equipment,u.brand,u.model,u.avNo,u.location");
      while($r = sqlsrv_fetch_array($qry)){
         $seq++;
         $data .= '<tr>
                     <td>'.$seq.'</td>
                     <td>'.$r['equipment'].' '.$r['brand'].' '.$r['model'].' '.$r['avNo'].' '.$r['location'].'</td>
                     <td>'.number_format(($r['total']/60),2).' hrs</td>
                  </tr>';
         $cdata.=' {
                 "label": "'.$r['brand'].' '.$r['avNo'].'",
                 "value": "'.number_format(($r['total']/60),2).'"
              },';
      }
   }

 
?>
<!DOCTYPE html>
<html lang="en">
   <head>

      <link href="google.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
      <!-- END GLOBAL MANDATORY STYLES -->
      <link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>


      <link rel="shortcut icon" href="favicon.ico"/>
      
      <script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
      <script type="text/javascript" src="http://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js?cacheBust=56"></script>
      <script type="text/javascript">
      FusionCharts.ready(function(){
            var revenueChart = new FusionCharts({
              "type": "<?php echo $_GET['display']; ?>",
              "renderAt": "chartContainer",
              "width": "90%",
              
              "dataFormat": "json",
              "dataSource": {
                "chart": {
                    "caption": "<?php echo $caption_unit; ?>",
                    "xAxisName": "Vehicle",
                    "yAxisName": "Hrs",
                    "rotatevalues": "0",
                    "theme": "fint"
                 },
                "data": [
                    <?php echo rtrim($cdata,",") ?>
                 ]
              }
          });
          revenueChart.render();
      })
      </script>
   </head>
   <body>
      <div class="page-container" style="width:90%;">
         <div class="page-content-wrapper">
            <div class="page-content">           
               <div class="row">
                  <div class="col-md-12 pull-right">
                     <div class="btn-group pull-right"> 
                        <a class="btn light btn-circle btn-sm" href="#" data-toggle="dropdown" data-hover="dropdown">
                        Department <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right" style="width:350px;height:300px;overflow: auto;overflow-x: hidden; ">    
                              <li><a  href="per-vehicle.php?displaytype=<?php echo $_GET['displaytype'];?>&display=<?php echo $_GET['display'];?>&start=<?php echo $_GET['start'];?>&end=<?php echo $_GET['end'];?>&unit=">All Department</a></li>                             
                              <?php
                                $uq=sqlsrv_query($conn,"select * from department order by name");
                                while($u=sqlsrv_fetch_array($uq)){ 
                               
                              ?>
                                 <li><a href="per-vehicle.php?displaytype=<?php echo $_GET['displaytype'];?>&display=<?php echo $_GET['display'];?>&start=<?php echo $_GET['start'];?>&end=<?php echo $_GET['end'];?>&dept=<?php echo $u['id']?>" style="font-size:10px;color:black;"><?php echo $u['name'].'</a>'; ?></li>
                              <?php
                                }
                              ?>
                        </ul>
                     </div>
                     <div class="btn-group pull-right">
                        <a class="btn light btn-circle btn-sm" href="#" data-toggle="dropdown" data-hover="dropdown">
                        Chart Type <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                           <li><a href="per-vehicle.php?displaytype=<?php echo $_GET['displaytype'];?>&display=pie2d&start=<?php echo $_GET['start'];?>&end=<?php echo $_GET['end'];?>&unit=<?php echo $_GET['unit'];?>">Pie </a></li>
                           <li><a href="per-vehicle.php?displaytype=<?php echo $_GET['displaytype'];?>&display=bar2d&start=<?php echo $_GET['start'];?>&end=<?php echo $_GET['end'];?>&unit=<?php echo $_GET['unit'];?>">Bar </a></li>
                           <li><a href="per-vehicle.php?displaytype=<?php echo $_GET['displaytype'];?>&display=column2d&start=<?php echo $_GET['start'];?>&end=<?php echo $_GET['end'];?>&unit=<?php echo $_GET['unit'];?>">Column </a></li>
                           <li class="divider"></li>
                           <li><a href="per-vehicle.php?displaytype=<?php echo $_GET['displaytype'];?>&display=list&start=<?php echo $_GET['start'];?>&end=<?php echo $_GET['end'];?>&unit=<?php echo $_GET['unit'];?>">List </a></li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                  <?php if($_GET['display']<>'list'){ ?>
                     <table width="100%">
                        <tr><td align="center"><div id="chartContainer">No Record for this Department</div></td></tr>
                     </table>
                  <?php } else { ?>
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Seq</th>
                              <th>Vehicle</th>
                              <th>Hours</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php echo $data; ?>
                        </tbody>
                     </table>
                  <?php } ?>
                  </div>                  
               </div>

            </div>
         </div>
      </div>

      <script src="../metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>

   </body>
</html>