<?php
include("../config.php");
include('functions.php');
session_start();


?>



<html>
<head>
   <title>Vehicle Monitoring System</title>
   <link href="google.css" rel="stylesheet" type="text/css"/>
   <link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
   <link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
   <link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
   <link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
   <link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
   <!-- END GLOBAL MANDATORY STYLES -->


   <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
   <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/select2/select2.css"/>
   <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css"/>


   <!-- BEGIN THEME STYLES -->
   <link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
   <link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
   <link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
   <link id="style_color" href="../metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
   <link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>

   <link href="../metronic/datepicker/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

   <script src="../js/jquery.min.js"></script>

</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
   <?php include('../header.php'); ?>
   <div class="clearfix"></div>
   <div class="page-container">
      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
         <div class="page-content">
            <div class="row">
               <div class="col-md-12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     <i class="fa fa-truck"></i> VEHICLE                         
                  </h3>
               </div>
            </div>
            <div class="clearfix"></div>
            <select id="products_Motor">
                <option value="showAll" selected="selected">Show All Fuel</option>
                <?php
                require_once '../config.php';
                
                $result = sqlsrv_query($conn,'SELECT * FROM fuel_types');
                
                while($row = sqlsrv_fetch_array($result))
                {
                ?>
                <option value="1"><?php echo $row['name']; ?></option>
                <?php
                }
                ?>
                </select> 

                <h3>Loading Drop Down Selection Using PHP</h3>
                  <hr />
                      
                  <div class="" id="display"></div>
                      
                  </div>
         </div>
      </div>
      <!-- END CONTENT -->
   </div>
   <div class="page-footer">
      <div class="page-footer-inner">
         VEHICLE MONITORING SYSTEM | <?php echo date('Y'); ?> &copy; PMC
      </div>
      <div class="page-footer-tools">
         <span class="go-top">
            <i class="fa fa-angle-up"></i>
         </span>
      </div>
   </div>


</body>
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
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="../metronic/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../metronic/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>

<script src="../metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

<script type="text/javascript" src="../metronic/datepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="../metronic/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="<?php echo $url;?>js/notifications.js"></script>
<script src="<?php echo $url;?>js/comments.js"></script> 


<script type="text/javascript">
$(document).ready(function()
{   

  $("#products_Motor").change(function()
  {       
    var id = $(this).find(":selected").val();

    var dataString = 'action='+ id;
        
    $.ajax
    ({
      url: 'test2.php',
      data: dataString,
      cache: false,
      success: function(r)
      {
        $("#display").html(r);
      } 
    });
  })
  // code to get all records from table via select box
});
</script>

</html>