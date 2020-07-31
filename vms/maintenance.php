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

      <!-- BEGIN THEME STYLES -->
      <link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
      <link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
      <!-- END THEME STYLES -->
      <link rel="shortcut icon" href="favicon.ico"/>
      
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
               
               <div class="row">
                  <div class="col-md-12 col-sm-12">                     
                        <iframe src="maintenance/unit.php" style="overflow : hidden;" frameborder="0" width="110%" scrolling="auto" height="550"></iframe>                       
                  </div>
                  
               </div>

               <div class="row">
                  <div class="col-md-4 col-sm-4">
                          <iframe src="maintenance/mechanic.php" style="overflow : hidden;" frameborder="0" width="100%" scrolling="auto" height="500"></iframe>
                        
                  </div>
                  <div class="col-md-4 col-sm-4">
                     
                          <iframe src="maintenance/assigned.php" style="overflow : hidden;" frameborder="0" width="100%" scrolling="auto" height="500"></iframe>
                        
                  </div>
                  <div class="col-md-4 col-sm-4">
                     
                          <iframe src="maintenance/status.php" style="overflow : hidden;" frameborder="0" width="100%" scrolling="auto" height="500"></iframe>
                       
                  </div>                  
               </div>

               <div class="row">
                  <div class="col-md-6 col-sm-6">
                    
                          <iframe src="maintenance/repair_preventive.php" style="overflow : hidden;" frameborder="0" width="100%" scrolling="auto"  height="500"></iframe>
                        
                  </div>
                  <div class="col-md-6 col-sm-6">
                     
                          <iframe src="maintenance/repair_breakdown.php" style="overflow : hidden;" frameborder="0" width="100%" scrolling="auto"  height="500"></iframe>
                        
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
                         [1, "asc"]
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