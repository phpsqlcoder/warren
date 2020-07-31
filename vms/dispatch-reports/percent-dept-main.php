<?php
   include("../config.php");
   include_once('../functions.php');



   session_start();
   if(!$_SESSION['esdvms_username']){
    header("location:../login.php");
   }
   $data='';
   $cdata='';

   $difference_array = array();
   $utilized_array = array();
   $caption_unit = 'All Vehicles';
   if(!isset($_GET['start'])){
      $_GET['end']=date('Y-m-d');
      $_GET['start']=date('Y-m-d',strtotime("-29 days"));
      $_GET['display']='stackedcolumn3d';
      $_GET['dept']='';
      $_GET['unit']='';      
   }

   if(!isset($_GET['displaytype'])){
      $_GET['displaytype'] = 'full';
   }
   if(!isset($_GET['display'])){
      $_GET['display'] = 'stackedcolumn3d';
   }
   if(!isset($_GET['unit'])){
      $_GET['unit'] = '';
   }
   if(!isset($_GET['dept'])){
      $_GET['dept'] = '';
   }
   //$sss = 'Jun 7 2017 1:05 AM';
   //$sse = 'Jun 23 2017 2:35 AM';
   /*$sss = '2017-06-07';
   $sse = '2017-06-23';
    $intervalss  = abs(strtotime($sss) - strtotime($sse));
    $minuted   = round($intervalss / 60);
    $houred = $minuted / 60;
    $downtime = 0/ 60;
    $availability = number_format(($houred - $downtime),2);
   echo $availability;
*/
   if(isset($_GET['start'])){
      $cond = '';
      $seq = 0;

      if(isset($_GET['unit']) && $_GET['unit']!=''){
         $cond .= " and u.id='".$_GET['unit']."'";
         $unitq = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from unit where id='".$_GET['unit']."'"));
         $caption_unit = $unitq['equipment'].' '.$unitq['brand'].' '.$unitq['model'].' '.$unitq['avNo'].' '.$unitq['location'];
      }
      if(isset($_GET['dept']) && $_GET['dept']!=''){
         $cond .= " and de.id='".$_GET['dept']."'";
      }
      if(isset($_GET['start']) && $_GET['start']!=''){
         $cond .= " and d.date>='".$_GET['start']."'";
      }
      if(isset($_GET['end']) && $_GET['end']!=''){
         $cond .= " and d.date<='".$_GET['end']."'";
      }
      $qry = sqlsrv_query($conn,"select de.id,de.name,sum(d.mins) as total from  dispatchflatdata d 
                                 right join department de on de.id=d.deptId                               
                                 left join unit u on u.id=d.unitId
                                 where de.id>0 ".$cond."
                                 group by de.name,de.id
                                 order by sum(d.mins) DESC, de.name");
  
      while($r = sqlsrv_fetch_array($qry)){
         $seq++;
         $total_availability = 0;
         $total_query = sqlsrv_query($conn,"select distinct u.id as id from  dispatchflatdata d 
                                 left join department de on de.id=d.deptId                               
                                 left join unit u on u.id=d.unitId
                                 where u.id>0 and de.id='".$r['id']."' ".$cond." "); 
               
  
         while($total_result = sqlsrv_fetch_array($total_query)){
            $availability = getAvailabilityPerVehicle($total_result['id'],$_GET['start'],$_GET['end']);
            $total_availability+=$availability;
         }
            $percent_available = (($r['total']/60)/$total_availability) * 100;
            $difference = $total_availability - ($r['total']/60);
               
         $data .= '<tr>
                     <td>'.$seq.'</td>
                     <td>'.$r['name'].'</td>
                     <td>'.number_format($total_availability,2).' hrs</td>
                     <td>'.number_format(($r['total']/60),2).' hrs</td>
                     <td><a href="#" onclick=\'viewdetails("'.$r['id'].'","'.$_GET['start'].'","'.$_GET['end'].'")\' class="btn purple btn-xs">View</a></td>
                  </tr>';
         $cdata.=' {
                 "label": "'.$r['name'].'",
                 "stepSkipped": false
              },';
          $difference_array[$seq] = number_format($difference, 2, '.', '');
          $utilized_array[$seq] = number_format(($r['total']/60), 2, '.', '');

      }
      $cutilized = '{
                      "seriesname": "Utilized",
                      "useroundedges": "1",
                      "color": "93F786",
                      "showvalues": "1",
                      "data": [';
      $cdifference = '{
                      "seriesname": "Other",
                      "useroundedges": "1",
                      "color": "E3E8CA",
                      "showvalues": "1",
                      "data": [';
      for($s=1;$s<=$seq;$s++){
        $cdifference .= '
                    {
                        "value": "'.$difference_array[$s].'"
                    },';
        $cutilized .= '
                    {
                        "value": "'.$utilized_array[$s].'"                        
                    },';
      }
      $cdifference = rtrim($cdifference,",");
      $cutilized = rtrim($cutilized,",");
      $cdifference .= ']
                  }';
      $cutilized .= ']
                  },';

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
                    "caption": "% Utilization per Dept",
                    "xAxisname": "Dept",
                    "yAxisName": "Availability (hrs)",  
                    "valueFontColor": "#000203",                 
                    "divLineDashed": "1",         
                    "showLegend": "1",
                    "useroundedges": "1",    
                    "showHoverEffect": "1",
                    "showLabels": "1",
                    "showValues": "1",
                    "showPercentValues": "1",
                    "showSum": "1",
                    "theme": "fint"
              },
              "categories": [
                  {
                      "category": [
                          <?php echo rtrim($cdata,",") ?>
                      ]
                  }
              ],
              "dataset": [
             
                 <?php echo $cutilized; ?>
                 <?php echo $cdifference; ?>
                 
              ]
              }
          });
          revenueChart.render();
      })
      </script>
   </head>
   <body style="width:95%;">
      <div class="page-container" style="margin-left:5%;">
         <div class="page-content-wrapper">
            <div class="page-content">
               <div class="row">
                  <div class="col-md-12">
                  <h2>Vehicle Utilization Per Dept</h2>
                     <form action="percent-dept-main.php" method="get">
                        <table width="100%">
                           <tr>                             
                              <td width="200">
                                 <select class="form-control input-sm" name="unit" id="unit">
                                   <option value=""> - Select Vehicle -</option>                    
                                   <?php
                                      $uq=sqlsrv_query($conn,"select * from unit order by equipment,brand,model,plateNo,location,avNo");
                                      while($u=sqlsrv_fetch_array($uq)){
                                       $selected = ($_GET['unit'] == $u['id'] ? 'selected="selected"' : '');
                                       echo '<option value="'.$u['id'].'" '.$selected.'>'.$u['equipment'].' '.$u['brand'].' '.$u['model'].' '.$u['avNo'].' '.$u['location'];
                                      }
                                      ?>
                                 </select>
                              </td>                               
                              <td width="200">
                                 <select class="form-control input-sm" name="dept" id="dept">
                                   <option value=""> - Select Dept -</option>                    
                                   <?php
                                      $uq=sqlsrv_query($conn,"select * from department order by name");
                                      while($u=sqlsrv_fetch_array($uq)){
                                       $selected = ($_GET['dept'] == $u['id'] ? 'selected="selected"' : '');
                                       echo '<option value="'.$u['id'].'" '.$selected.'>'.$u['name'];
                                      }
                                      ?>
                                 </select>
                              </td>                          
                              <td>
                                 <select name="display" class="form-control input-sm">                                  
                                    <option value="column2d" <?php if($_GET['display']=='column2d') echo 'selected="selected"';?>>Column</option>
                                    <option value="bar2d" <?php if($_GET['display']=='bar2d') echo 'selected="selected"';?>>Bar</option>
                                 </select>
                              </td>
                              <td align="right">Start:</td>
                              <td width="100"><input type="date" class="form-control input-sm" name="start" value="<?php echo $_GET['start']?>"></td>
                              <td align="right">End:</td>
                              <td width="100"><input type="date" class="form-control input-sm" name="end" value="<?php echo $_GET['end']?>"></td>                       
                              <td><input type="submit" value="Go" class="btn green btn-sm"></td>
                           </tr>
                        </table>
                     </form>
                  </div>                  
               </div>
               <br><br>              
               <div class="row">
                  <div class="col-md-6">
                    <div class="portlet box green-haze tasks-widget">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cube"></i>Graph
                        </div>
                      </div>
                      <div class="portlet-body">
                         <table width="100%">
                            <tr><td align="center"><div id="chartContainer">No Record for this vehicle</div></td></tr>
                         </table>
                      </div>
                    </div>
                     
                  </div>
                  <div class="col-md-6">
                    <div class="portlet box blue-steel tasks-widget">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-bars"></i>List <a href="#" onclick="exportexcel('jtable1');" class="btn green btn-xs"></a>  
                        </div>
                        <div class="actions">
                              <a href="javascript:;" class="btn btn-sm btn-success" onclick="exportexcel('jtable1');"><i class="fa fa-file-excel-o"></i> Export </a>
                        </div>
                      </div>
                      <div class="portlet-body" style="height:320px;">
                         <table class="table" id="jtable1">
                            <thead>
                               <tr>
                                  <th>Seq</th>
                                  <th>Dept</th>
                                  <th>Availability (hrs)</th>
                                  <th>Usage (hrs)</th>
                                  <th>Details</th>
                               </tr>
                            </thead>
                            <tbody>
                               <?php echo $data; ?>
                            </tbody>
                         </table>
                      </div>
                    </div>
                  </div>                  
               </div>              
               <div class="row">
                 <div class="col-md-12" id="detaillist">                   
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
      <script src="../js/excel/src/jquery.table2excel.js" type="text/javascript"></script>
      <script type="text/javascript">
        function viewdetails(a,b,c){
          //alert(c);
          jQuery.ajax({
            method: "POST",
            url: "../ajax.php?act=dis_dept",
            data: { id: a, start: b, end: c}
          })
          .done(function( html ) {
            jQuery("#detaillist").html( html );
          });
        }

        function exportexcel(x){
          jQuery("#"+x).table2excel({
            // exclude CSS class
            //exclude: ".noExl",
            name: "VMS",
            filename: "UtilizationPerDept_<?php echo date('his');?>" //do not include extension
          }); 
        }
      </script>
   </body>
</html>