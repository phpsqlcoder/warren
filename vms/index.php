<?php
   include("config.php");
   session_start();
   if(!$_SESSION['esdvms_username']){
   	header("location:login.php");
   }
   
   if(isset($_GET['delete'])){
   	$delete=sqlsrv_query($conn,"delete from downtime where id='".$_GET['id']."'");
   	$delete2=sqlsrv_query($conn,"delete from downtimeFlatData where downtimeId='".$_GET['id']."'");
   	header('location:index.php');
   }
   
   if(!isset($_GET['startDate'])){
   	$_GET['endDate']=date('Y-m-d');
   	$_GET['startDate']=date('Y-m-d',strtotime("-29 days"));
   }

   $cond="";
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

      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/select2/select2.css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>


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
               
              
            
              
               <form method="get" act="index.php">
                  <div class="row">
                     <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title">
                           Vehicle <small>Downtime Records</small>                          
                             
                        </h3>
                        <ul class="page-breadcrumb breadcrumb">
                           <li>							
                              <a href="#">Range:</a>							
                           </li>
                           <li>			
                              <input type="date" name="startDate" value="<?php echo $_GET['startDate'];?>">
                           </li>
                           <li id="typelist">							
                              <input type="date" name="endDate" value="<?php echo $_GET['endDate'];?>">			 											
                           </li>                          
                           <li>
                              <input type="submit" class="btn green btn-sm" value="Go">
                              <a href="index.php" class="btn purple btn-sm" style="color:white;">Reset</a>
                           </li>
                           <li class="pull-right" style="position:relative;top:5px;">
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
               <div class="clearfix">
               </div>

               <div class="row ">                  
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet box blue-steel">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-download"></i>Recent Downtime Logs
                           </div>
                        </div>
                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-hover" id="sample_1" style="font-size:11px;">
                              <thead>
                                 <tr>
                                    <th>ID</th>
                                    <th>Unit</th>
                                    <th>Start</th>
                                    <th>End</th>                                       
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php

                                    $lq=sqlsrv_query($conn,"select CONVERT(VARCHAR(19),d.dateStart) as ds,CONVERT(VARCHAR(19),d.dateEnd) as de,d.*,u.name as uni,u.type                                      
                                    	from downtime d left join unit u on u.id=d.unitId where ((d.dateStart>='".$_GET['startDate']."' and d.dateEnd<='".$_GET['endDate']." 23:59:59') OR (d.dateEnd>='".$_GET['startDate']."' and d.dateEnd<='".$_GET['endDate']." 23:59:59')) order by d.id desc");
                                    while($l=sqlsrv_fetch_array($lq)){	                                             
                                    	echo '
                                    		<tr>
                                    			<td>'.$l['id'].'</td>
                                    			<td>'.$l['uni'].' ('.$l['type'].')</td>
                                    			<td>'.$l['ds'].'</td>
                                    			<td>'.$l['de'].'</td>                                          			
                                    			<td style="width:100px;"><a href="#" class="btn purple btn-xs" onclick=\'window.open("downtime_edit.php?id='.$l['id'].'","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=700"); return false;\';><i class="fa fa-edit"></i></a>
                                    				<a href="#" class="btn red btn-xs deletedl" data="'.$l['id'].'"><i class="fa fa-minus-circle"></i></a>
                                    			</td>
                                    		</tr>
                                    	';
                                    }
                                    ?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet box  green-haze">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>Repair Hours by Category
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/repair_hours_by_category.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="400" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>                  
               </div>
               <div class="clearfix">
               </div>

               <div class="row">
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>MTD % Availability Due to Breakdown: Light Vehicle
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/mtd_availability_due_to_breakdown_light.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>MTD % Availability Due to Breakdown: Medium Vehicle
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/mtd_availability_due_to_breakdown_medium.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div> 
               </div>

               <div class="row">
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>Repair Hours by Repair Type
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/repair_hours_by_repair_type.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>MTD % Availability Due to Breakdown: Heavy Vehicle
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/mtd_availability_due_to_breakdown_heavy.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>Man Hours Distribution
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/man_hours_distribution.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>MTD % Availability Due to Breakdown: Motorcycle
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/mtd_availability_due_to_breakdown_motorcycle.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>                  
               </div>

               <div class="row">
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>Top 10 Repair Hours: Light Vehicle
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/repair_hours_light_vehicle.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>Top 10 Repair Hours: Medium Vehicle
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/repair_hours_medium_vehicle.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>                  
               </div>

               <div class="row">
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>Top 10 Repair Hours: Heavy Equipment
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/repair_hours_heavy_vehicle.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="portlet solid grey-cararra bordered">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-check-square-o"></i>Top 10 Repair Hours: Motorcycle
                           </div>
                        </div>
                        <div class="portlet-body">
                          <iframe src="charts/repair_hours_motorcycle.php?startd=<?php echo $_GET['startDate'];?>&endd=<?php echo $_GET['endDate'];?>" style="overflow : hidden;" frameborder="0" width="100%" scrolling="no" height="200" style="-webkit-transform:scale(1.1);-moz-transform-scale(1.1);"></iframe>
                        </div>
                     </div>
                  </div>                  
               </div>
            </div>
         </div>
         <!-- END CONTENT -->
      </div>
      <!-- END CONTAINER -->
      <!-- BEGIN FOOTER -->
      <div class="page-footer">
         <div class="page-footer-inner">
            <?php echo date('Y'); ?> &copy; PMC - ICT.
         </div>
         <div class="page-footer-tools">
            <span class="go-top">
            <i class="fa fa-angle-up"></i>
            </span>
         </div>
      </div>
   <!-- Scripts -->
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

      <script src="metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>

      <script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
      <script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

      <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/pages/scripts/table-managed.js"></script>
      <script src="<?php echo $url;?>js/notifications.js"></script>
      <script src="<?php echo $url;?>js/comments.js"></script> 
      <script>
         jQuery(document).ready(function() {    
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            TableManaged.init();
         });
      </script>
      <script>
         var TableManaged = function () {

            var initTable1 = function () {

                 var table = $('#sample_1');

                 // begin first table
                 table.dataTable({
                     "columns": [{
                         "orderable": true
                     }, {
                         "orderable": true
                     }, {
                         "orderable": true                    
                     }, {
                         "orderable": true
                     }, {
                         "orderable": false
                     }],
                     "lengthMenu": [
                         [5, 15, 20, -1],
                         [5, 15, 20, "All"] // change per page values here
                     ],
                     // set the initial value
                     "pageLength": 5,            
                     "pagingType": "bootstrap_full_number",
                     "language": {
                         "lengthMenu": "  _MENU_ records",
                         "paginate": {
                             "previous":"Prev",
                             "next": "Next",
                             "last": "Last",
                             "first": "First"
                         }
                     },
                     "columnDefs": [{  // set default column settings
                         'orderable': false,
                         'targets': [0]
                     }, {
                         "searchable": false,
                         "targets": [0]
                     }],
                     "order": [
                         [0, "desc"]
                     ] // set first column as a default sort by asc
                 });

                 var tableWrapper = jQuery('#sample_1_wrapper');

                 table.find('.group-checkable').change(function () {
                     var set = jQuery(this).attr("data-set");
                     var checked = jQuery(this).is(":checked");
                     jQuery(set).each(function () {
                         if (checked) {
                             $(this).attr("checked", true);
                             $(this).parents('tr').addClass("active");
                         } else {
                             $(this).attr("checked", false);
                             $(this).parents('tr').removeClass("active");
                         }
                     });
                     jQuery.uniform.update(set);
                 });

                 table.on('change', 'tbody tr .checkboxes', function () {
                     $(this).parents('tr').toggleClass("active");
                 });

                 tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
             }

             return {
                 //main function to initiate the module
                 init: function () {
                     if (!jQuery().dataTable) {
                         return;
                     }
                     initTable1();
                 }
             };

         }();
      </script>
      
      
      <!-- END JAVASCRIPTS -->
   </body>
   <!-- END BODY -->
</html>