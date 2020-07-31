<?php
   include("config.php");
   session_start();
   if(!$_SESSION['esdvms_username']){
   	header("location:login.php");
   }
   
   if(isset($_GET['act'])){
      
      $update = sqlsrv_query($conn,"update downtime set active='0' where id='".$_GET['id']."'");

      header('location:downtimes.php');
   }
   if(!isset($_GET['startDate'])){
   	$_GET['endDate']=date('Y-m-d');
   	$_GET['startDate']="2018-01-01";
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
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
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
                              <a href="downtimes.php" class="btn purple btn-sm" style="color:white;">Reset</a>
                           </li>
                           
                        </ul>
                     </div>
                  </div>
               </form>

               <div class="clearfix">
               </div>

               <div class="row ">                  
                  <div class="col-md-12">
                     <div class="portlet box blue-steel">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-download"></i>Recent Downtime Logs
                           </div>
                           <div class="actions">
                              <div class="btn-group">
                                 
                                 <a class="btn default" href="#" data-toggle="dropdown">
                                 Columns <i class="fa fa-angle-down"></i>
                                 </a>
                                 
                                 <div id="sample_4_column_toggler" class="dropdown-menu hold-on-click dropdown-checkboxes pull-right">
                                    <label><input type="checkbox" checked data-column="0">ID</label>
                                    <label><input type="checkbox" checked data-column="1">Unit</label>
                                    <label><input type="checkbox" checked data-column="2">Category</label>
                                    <label><input type="checkbox" checked data-column="3">Status</label>
                                    <label><input type="checkbox" data-column="4">Reported</label>
                                    <label><input type="checkbox" checked data-column="5">Start</label>
                                    <label><input type="checkbox" checked data-column="6">End</label>
                                    <label><input type="checkbox" data-column="7">Assigned To</label>
                                    <label><input type="checkbox" data-column="8">Remarks</label>
                                    <label><input type="checkbox" data-column="9">Type</label>
                                    <label><input type="checkbox" data-column="10">Work Order</label>
                                    <label><input type="checkbox" data-column="11">Repair Type</label>
                                    <label><input type="checkbox" data-column="12">Work Details</label>
                                    <label><input type="checkbox" data-column="13">Mechanics</label>
                                    <label><input type="checkbox" data-column="14">From 12 AM</label>
                                    <label><input type="checkbox" data-column="15">From 7 AM</label>
                                    <label><input type="checkbox" data-column="16">Repair Days</label>
                                    <label><input type="checkbox" data-column="17">Repair Hours</label>
                                    <label><input type="checkbox" data-column="18">Shop Days</label>
                                    <label><input type="checkbox" data-column="19">Shop Hours</label>
                                    <label><input type="checkbox" data-column="20">Man Hours</label>
                                    <label><input type="checkbox" data-column="21">Required Daily Availability</label>
                                    <label><input type="checkbox" data-column="22">Downtime</label>
                                    <label><input type="checkbox" data-column="23">Added By</label>
                                    <label><input type="checkbox" data-column="24">Added Date</label>
                                    <label><input type="checkbox" checked data-column="25">Action</label>
                                 </div>
                                 <a class="btn green" href="#" style="margin-left: 10px;" onclick="exportToExcel('#sample_4')">
                                    Download
                                 </a>
                                 <a class="btn green" target="_blank" href="maintenance/export.php?act=raw_data&startDate=<?php echo $_GET['startDate']."&endDate=".$_GET['endDate'];?>" style="margin-left: 10px;">
                                    Raw Data 
                                 </a>
                              </div>
                           </div>

                        </div>
                        <div class="portlet-body">
                           <div class="table-scrollable">
                              <table style="font-size:11px;" class="table table-striped table-bordered table-hover" id="sample_4">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>Unit</th>
                                       <th>Category</th>
                                       <th>Status</th>
                                       <th>Reported</th> 
                                       <th>Start</th>
                                       <th>End</th>   
                                       <th>Assigned To</th>                                    
                                       <th>Remarks</th>
                                       <th>Type</th>
                                       <th>Work Order</th>
                                       <th>Repair Type</th>
                                       <th>Downtime Category</th>
                                       <th>Work Details</th>
                                       <th>Mechanics</th>
                                       <th>From 12 AM</th>
                                       <th>From 7 AM</th>
                                       <th>Repair Days</th>
                                       <th>Repair Hours</th>
                                       <th>Shop Days</th>
                                       <th>Shop Hours</th>
                                       <th>Man Hours</th>
                                       <th>Required Daily Availability</th>
                                       <th>Downtime</th>
                                       <th>Added By</th>
                                       <th>Added Date</th>                                   
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php

                                       $lq=sqlsrv_query($conn,"select CONVERT(VARCHAR(19),d.dateStart) as ds,CONVERT(VARCHAR(19),d.dateEnd) as de,CONVERT(VARCHAR(19),d.reportedDate) as reported,CONVERT(VARCHAR(19),d.addedDate) as added,d.*,u.name as uni,u.type                                      
                                          from downtime d left join unit u on u.id=d.unitId where ((d.dateStart>='".$_GET['startDate']."' and d.dateEnd<='".$_GET['endDate']." 23:59:59') OR (d.dateEnd>='".$_GET['startDate']."' and d.dateEnd<='".$_GET['endDate']." 23:59:59')) and d.active=1 order by d.id desc");
                                       while($l=sqlsrv_fetch_array($lq)){      
                                          $type = "";
                                          if($l['isScheduled']==1){
                                             $type="Corrective/PM";
                                          }
                                          if($l['isScheduled']==1){
                                             $type="Breakdown";
                                          }
                                          echo '
                                             <tr>
                                                <td>'.$l['id'].'</td>
                                                <td>'.$l['uni'].'</td>
                                                <td>'.$l['type'].'</td>
                                                <td>'.$l['status'].'</td>
                                                <td>'.date('Y-m-d',strtotime($l['reported'])).'</td>
                                                <td>'.$l['ds'].'</td>
                                                <td>'.$l['de'].'</td>
                                                <td>'.$l['assignedTo'].'</td>
                                                <td>'.$l['remarks'].'</td>
                                                <td>'.$type.'</td>
                                                <td>'.$l['workOrder'].'</td>
                                                <td>'.$l['repairType'].'</td>
                                                <td>'.$l['downtimeCategory'].'</td>
                                                <td>'.$l['workDetails'].'</td>
                                                <td>'.str_replace("|", ", ", $l['mechanics']).'</td>
                                                <td>'.$l['from12'].'</td>                                                  
                                                <td>'.$l['from7'].'</td>
                                                <td>'.$l['trepair_days'].'</td>
                                                <td>'.$l['trepair_hours'].'</td>
                                                <td>'.$l['shop_days'].'</td>
                                                <td>'.$l['shop_hours'].'</td>
                                                <td>'.$l['man_hours'].'</td>
                                                <td>'.$l['required_daily_availability'].'</td>
                                                <td>'.$l['tdowntime'].'</td>
                                                <td>'.$l['addedBy'].'</td>
                                                <td>'.date('Y-m-d H:i:s',strtotime($l['added'])).'</td>
                                                <td style="width:100px;"><a href="#" class="btn purple btn-xs" onclick=\'window.open("downtime_edit.php?id='.$l['id'].'","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=700"); return false;\';><i class="fa fa-edit"></i></a>
                                                   <a href="#" class="btn red btn-xs deletedl" onclick="deleted('.$l['id'].');"><i class="fa fa-minus-circle"></i></a>
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
                  </div>
                               
               </div>
               <div class="clearfix">
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
      <script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
      <script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

      
      <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
      <script src="js/excel/src/jquery.table2excel.js"></script>
      <script src="<?php echo $url;?>js/notifications.js"></script>
      <script src="<?php echo $url;?>js/comments.js"></script> 
      <script> 
         function exportToExcel(table){
            jQuery(table).table2excel({
               name: "VMS",
               filename: "VMS" //do not include extension
            }); 
         }
      </script>

      <script>
         jQuery(document).ready(function() {    
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            TableAdvanced.init();
            //exportToExcel('#maintenance_excel');
         });
      </script>
      <script>
         var TableAdvanced = function () {


            var initTable4 = function () {
               var table = $('#sample_4');


               var oTable = table.dataTable({
                  "columnDefs": [{
                     "orderable": false,
                     "targets": [0]
                  }],
                  "order": [
                  [0, 'desc']
                  ],
                  "lengthMenu": [
                  [5, 15, 20, -1],
                  [5, 15, 20, "All"] 
                  ],
                  "pageLength": 300,
                  });

var tableWrapper = $('#sample_4_wrapper');
var tableColumnToggler = $('#sample_4_column_toggler');

tableWrapper.find('.dataTables_length select').select2(); 
var hidden_fields = [4,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];
for (i = 0; i < hidden_fields.length; i++) {
    oTable.fnSetColumnVis(hidden_fields[i], false);
}

$('input[type="checkbox"]', tableColumnToggler).change(function () {
   /* Get the DataTables object again - this is not a recreation, just a get of the object */
   var iCol = parseInt($(this).attr("data-column"));
   var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
   //alert(iCol+' = '+bVis);
   oTable.fnSetColumnVis(iCol, (bVis ? false : true));
});
}



return {


   init: function () {

      if (!jQuery().dataTable) {
         return;
      }

      initTable4();
      
   }

};

}();
      </script>
     <script>
        function deleted(x){        
         var r = confirm("Are you sure you want to delete this record?");
         if (r == true) {
             window.location = "downtimes.php?act=delete&id="+x;
         } else {
             return false;
         }
      }
     </script>
      
      <!-- END JAVASCRIPTS -->
   </body>
   <!-- END BODY -->
</html>