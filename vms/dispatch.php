<?php
   include("config.php");
   session_start();
   if(!$_SESSION['esdvms_username']){
   	header("location:login.php");
   }

   if(!isset($_GET['display1'])){
      $_GET['display1'] = 'column2d';
   }
    if(!isset($_GET['display2'])){
      $_GET['display2'] = 'bar2d';
   }

   if(isset($_GET['delete'])){
   	$delete=sqlsrv_query($conn,"delete from dispatch where id='".$_GET['id']."'");
   	$delete2=sqlsrv_query($conn,"delete from dispatchFlatData where downtimeId='".$_GET['id']."'");
   	header('location:dispatch.php');
   }
   
   if(!isset($_GET['start'])){
   	$_GET['end']=date('Y-m-d');
   	$_GET['start']=date('Y-m-d',strtotime("-29 days"));
   }
   
   $date_url = "&start=".$_GET['start']."&end=".$_GET['end'];

   function computemins($s,$e){	
   	$from_time = strtotime($s);
   	$to_time = strtotime($e);
   	return round(abs($to_time - $from_time) / 60,0);
   }
   
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
   	if($_GET['act']=='submitdispatch'){ 
   		$insert="INSERT INTO [dispatch]
           ([unitId]
           ,[type]
           ,[deptId]
           ,[dateStart]
           ,[dateEnd]
           ,[purpose]
           ,[addedBy]
           ,[addedDate])
   			VALUES('".$_POST['unit']."','".$_POST['dispatchtype']."','".$_POST['dept']."','".$_POST['startd']."','".$_POST['endd']."','".$_POST['purpose']."','".$_SESSION['esdvms_username']."','".date('Y-m-d h:i:s')."'); SELECT SCOPE_IDENTITY()";
           
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
            $ins=sqlsrv_query($conn,"insert into dispatchFlatData (date,mins,unitId,type,deptId,purpose,dispatchId)
               VALUES('".$value."','".$arr[$key]."','".$_POST['unit']."','".$_POST['dispatchtype']."','".$_POST['dept']."','".$_POST['purpose']."','".$lastins."')");
   			$totalmi+=$arr[$key];
   		}
   	}  
   	header("location:dispatch.php");
   }
   
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
         #dashboard_div {padding-left:340px; }
         #dashboard_div	table { border-collapse:separate;
         /*border-top: 3px solid; */
         }
         #dashboard_div    td, th {
         margin:0;
         /*  border:3px solid grey;
         border-top-width:0px;*/
         white-space:nowrap;
         }
         /*#dashboard_div    div {
         width: 100%;
         overflow-x:scroll;  
         margin-left:0em;
         overflow-y:visible;
         padding-bottom:1px;
         }*/
         #dashboard_div   .headcol {
         position:absolute;
         width:28em;
         left:28px;
         top:auto;
         border-right: 0px none;
         /* border-top-width:3px; 
         margin-top:-3px; compensate for top border*/
         background-color: white;
         }
         #dashboard_div    .headcol:before {content:'';}
         #dashboard_div    .long { background:yellow; letter-spacing:1em; }
      </style>
   </head>
   <body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
      <!-- BEGIN HEADER -->
      <?php include("header.php");?>
      <div class="clearfix"></div>
      <!-- BEGIN CONTAINER -->
      <div class="page-container">
         <!-- BEGIN CONTENT -->
         <div class="page-content-wrapper">
            <div class="page-content">
               <div class="modal fade bs-modal-lg" id="inputdispatch" tabindex="-1" role="inputdispatch" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <form method="post" id="dispatchform" action="dispatch.php?act=submitdispatch" onsubmit="return checkinput();">
                           <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                              <h4 class="modal-title">Input Dispatch Details</h4>
                           </div>
                           <div class="modal-body" style="height:300px">
                              <table>
                                 <tr>
                                    <td>Unit</td>
                                    <td>
                                       <select class="form-control" name="unit" id="unit" required>
                                          <option value=""> - Select Unit -</option>                    
                                          <?php
                                          $uq=sqlsrv_query($conn,"select * from unit order by equipment,brand,model,plateNo,location");
                                          while($u=sqlsrv_fetch_array($uq)){
                                             echo '<option value="'.$u['id'].'">'.$u['equipment'].' '.$u['brand'].' '.$u['model'].' '.$u['avNo'].' '.$u['location'];
                                          }
                                          ?>
                                       </select>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Remarks<br>(Optional)</td>
                                    <td>
                                       <textarea name="purpose" class="form-control" id="purpose" cols="50" rows="3"></textarea>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Type</td>
                                    <td>
                                       <select class="form-control" name="dispatchtype" id="dispatchtype" onchange="
                                       var va = document.getElementById('dispatchtype').value;
                                       if(va=='Variable'){
                                          document.getElementById('deptdiv').style.visibility = 'visible';
                                          document.getElementById('startddiv').style.visibility = 'visible';
                                          document.getElementById('endddiv').style.visibility = 'visible';                                   
                                       }
                                       else {
                                          document.getElementById('deptdiv').style.visibility = 'hidden';
                                          document.getElementById('startddiv').style.visibility = 'hidden';
                                          document.getElementById('endddiv').style.visibility = 'hidden';
                                       }
                                       " required>
                                       <option value="Fixed">Fixed</option>                      
                                       <option value="Variable">Variable</option>                      
                                    </select>
                                 </td>
                              </tr>

                              <tr required style="visibility:hidden" id="deptdiv">
                                 <td>Dept</td>
                                 <td>
                                    <select class="form-control" name="dept" id="dept">
                                       <option value=""> - Select Dept -</option>                    
                                       <?php
                                       $dq=sqlsrv_query($conn,"select * from department order by name");
                                       while($d=sqlsrv_fetch_array($dq)){
                                          echo '<option value="'.$d['id'].'">'.$d['name'].'</option>';
                                       }
                                       ?>
                                    </select>
                                 </td>
                              </tr>
                              <tr style="visibility:hidden" id="startddiv">
                                 <td>Start</td>
                                 <td>
                                    <div class="input-group date form_datetime col-md-12">
                                       <input type="text" size="16" name="startd" id="startd" readonly class="form-control">
                                       <span class="input-group-btn">
                                          <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                       </span>
                                    </div>
                                 </td>
                              </tr>
                              <tr style="visibility:hidden" id="endddiv">
                                 <td>End</td>
                                 <td>
                                    <div class="input-group date form_datetime col-md-12">
                                       <input type="text" size="16" name="endd" id="endd" readonly class="form-control">
                                       <span class="input-group-btn">
                                          <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                       </span>
                                    </div>
                                 </td>
                              </tr>

                           </table>                                 
                        </div>
                        <div class="modal-footer" id="footermode">
                           <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                           <input type="submit" class="btn blue" value="Save">
                        </div>
                     </form>
                  </div>
                  <!-- /.modal-content -->
               </div>
            </div>
               <form method="get" act="dispatch.php">
                  <input type="hidden" name="display1" id="display1" value="<?php echo $_GET['display1'];?>">
                  <input type="hidden" name="display2" id="display2" value="<?php echo $_GET['display2'];?>">
                  <div class="row">
                     <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title">
                           Vehicle <small>Dispatch Records</small>
                           <div class="pull-right">
                              <a class="btn purple" data-toggle="modal" href="#inputdispatch">Add Dispatch Records</a>
                           </div>   
                        </h3>
                        <ul class="page-breadcrumb breadcrumb">
                           <li>Date Range:</li>                           
                           <li class="pull-center" style="position:relative;top:5px;">
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
               <input type="hidden" name="hiddenstart" id="hiddenstart" value="<?php echo $_GET['start'];?>">
               <input type="hidden" name="hiddenend" id="hiddenend" value="<?php echo $_GET['end'];?>">
               
               <div class="clearfix"></div>
               <div class="row">
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet light">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="icon-bar-chart font-green-sharp hide"></i>
                              <span class="caption-subject font-green-sharp bold uppercase">Utilization per Dept</span>
                              <span class="caption-helper"><?php echo date('F d',strtotime($_GET['start']));?> to <?php echo date('F d Y',strtotime($_GET['end']));?></span>
                           </div>
                           <div class="actions">
                              
                           </div>
                        </div>
                        <div class="portlet-body">
                           <iframe id="iframe1" src="dispatch-reports/per-dept.php?displaytype=iframe&unit=&display=column2d<?php echo $date_url;?>" frameborder="0" width="100%" style="height:350px;"></iframe>
                           <div class="scroller-footer">
                              <div class="btn-arrow-link pull-right">
                                 <a href="dispatch-reports/per-dept-main.php?aa=go<?php echo $date_url;?>" target="_blank">Explore more</a>
                                 <i class="icon-arrow-right"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet light">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="icon-bar-chart font-green-sharp hide"></i>
                              <span class="caption-subject font-green-sharp bold uppercase">Utilization per Vehicle</span>
                              <span class="caption-helper"><?php echo date('F d',strtotime($_GET['start']));?> to <?php echo date('F d Y',strtotime($_GET['end']));?></span>
                           </div>
                           <div class="actions">
                              
                           </div>
                        </div>
                        <div class="portlet-body">
                           <iframe id="iframe1" src="dispatch-reports/per-vehicle.php?displaytype=iframe&unit=&display=bar2d<?php echo $date_url;?>" frameborder="0" width="100%" style="height:350px;"></iframe>
                           <div class="scroller-footer">
                              <div class="btn-arrow-link pull-right">
                                 <a href="dispatch-reports/per-vehicle-main.php?aa=go<?php echo $date_url;?>" target="_blank">Explore more</a>
                                 <i class="icon-arrow-right"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="clearfix"></div>
               <div class="row">
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet light">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="icon-bar-chart font-green-sharp hide"></i>
                              <span class="caption-subject font-green-sharp bold uppercase">Utilization per Vehicle Type</span>
                              <span class="caption-helper"><?php echo date('F d',strtotime($_GET['start']));?> to <?php echo date('F d Y',strtotime($_GET['end']));?></span>
                           </div>
                           <div class="actions">
                              
                           </div>
                        </div>
                        <div class="portlet-body">
                           <iframe id="iframe1" src="dispatch-reports/per-type.php?displaytype=iframe&unit=&display=pie2d<?php echo $date_url;?>" frameborder="0" width="100%" style="height:350px;"></iframe>
                           <div class="scroller-footer">
                              <div class="btn-arrow-link pull-right">
                                 <a href="dispatch-reports/per-type-main.php?aa=go<?php echo $date_url;?>" target="_blank">Explore more</a>
                                 <i class="icon-arrow-right"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet box blue-steel">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-bell-o"></i>Recent Dispatch Records
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
                                          <th>Dept</th>
                                          <th>Start</th>
                                          <th>End</th>
                                          <th>Remarks</th>
                                          <th>Edit</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php 
                                          $seq=0;
                                          $lq=sqlsrv_query($conn,"select dep.name as dept,CONVERT(VARCHAR(19),d.dateStart) as ds,CONVERT(VARCHAR(19),d.dateEnd) as de,d.*,u.equipment as uni,u.brand,u.model, 
                                             u.plateNo
                                             from dispatch d left join unit u on u.id=d.unitId
                                                 left join department dep on dep.id=d.deptId                    
                                              order by d.id desc");
                                          while($l=sqlsrv_fetch_array($lq)){

                                             $seq++;                                
                                             echo '
                                                <tr>
                                                   <td>'.$seq.'</td>
                                                   <td>'.$l['uni'].' '.$l['brand'].' '.$l['model'].' '.$l['plateNo'].'</td>
                                                   <td>'.$l['dept'].'</td>
                                                   <td>'.$l['ds'].'</td>
                                                   <td>'.$l['de'].'</td>
                                                   <td>'.$l['purpose'].'</td>
                                                   <td style="width:100px;"><a href="#" class="btn purple btn-sm" onclick=\'window.open("dispatch_edit.php?id='.$l['id'].'","displayWindow","toolbar=no,scrollbars=yes,width=910,height=600"); return false;\';><i class="fa fa-edit"></i></a>
                                                      <a href="#" class="btn red btn-sm deletedl" data="'.$l['id'].'"><i class="fa fa-minus-circle"></i></a>
                                                   </td>
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
               <div class="row ">
                  
               </div>
               <div class="clearfix"></div>
            </div>
         </div>
         <!-- END CONTENT -->
      </div>
      <!-- END CONTAINER -->
      <!-- BEGIN FOOTER -->
      <div class="page-footer">
         <div class="page-footer-inner">
            2017 &copy; Developed by Jundrie.
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
      <script src="metronic/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/clockface/js/clockface.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
      <script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

      <!-- END PAGE LEVEL PLUGINS -->
      <!-- BEGIN PAGE LEVEL SCRIPTS -->
      <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/pages/scripts/components-pickers.js"></script>
      <script src="<?php echo $url;?>js/notifications.js"></script>
      <script src="<?php echo $url;?>js/comments.js"></script>  
      <!-- 
         <script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
         	END PAGE LEVEL SCRIPTS -->
      <script>
         jQuery(document).ready(function() {
            $('#deptdiv').prop('required', false);
            $('#startddiv').prop('required', false);
            $('#endddiv').prop('required', false);    
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            $('[data-toggle="popover"]').popover(); 
            Index.init();
            Index.initDashboardDaterange();   
            ComponentsPickers.init();
     
          
         
              $('.deletedl').click(function(){    	
            
                var x = $(this).attr('data');
                bootbox.confirm("Are you sure you want to delete this record?", function(result) {
                    if(result){
                      window.location = "dispatch.php?delete=delete&id="+x;
                  }
              }); 
            });
         });
      </script>

      <script>
         function loadIframe(iframeName, url) {
             var $iframe = $('#' + iframeName);
             if ( $iframe.length ) {
                 $iframe.attr('src',url);   
                 return false;
             }
             return true;
         }
         function changefilters(){
         	 $.ajax({
         		  method: "POST",
         		  url: "ajax.php?act=changefilters",
         		  data: { s_equipment: $('#s_equipment').val(), s_type: $('#s_type').val(), s_id: $('#s_id').val()}
         		})
         	  .done(function( html ) {
         	  	changefiltertype();
         	    $( "#unitlist" ).html( html );
         	  });
         }
         function changefiltertype(){
         	 $.ajax({
         		  method: "POST",
         		  url: "ajax.php?act=changefilterstype",
         		  data: { s_equipment: $('#s_equipment').val(), s_type: $('#s_type').val(), s_id: $('#s_id').val()}
         		})
         	  .done(function( html ) {
         	    $( "#typelist" ).html( html );
         	  });
         }

         function refresh_all(){
         	var datetype=$('input[name=datetype]:checked').val();
         	var start=$('#hiddenstart').val();
         	var end=$('#hiddenend').val();
         	var s_equipment=$('#hiddens_equipment').val();
         	var s_type=$('#hiddens_type').val();
         	var s_id=$('#hiddens_id').val();
         	window.location.href = "dispatch.php?start="+start+"&end="+end+"&datetype="+datetype+"&s_equipment="+s_equipment+"&s_type="+s_type+"&s_id="+s_id;
         	//alert(datetype);
         }
         function checkinput(){	
         	if ($("#dispatchtype").val() == "Variable"){
               if($("#startd").val()!='' && $("#endd").val()!='' && $("#dept").val()!=''){
                  $('#dispatchform').submit();
               }
               else{
                  alert("Dept, start and end date are required!");
                  return false;
               }               
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