<?php
ob_start();
include("../config.php");
include('functions.php');
session_start();
date_default_timezone_set('Asia/Manila');


?>
<html>

<head>
   <title>Vehicle Monitoring System</title>
   <link href="../google.css" rel="stylesheet" type="text/css" />
   <link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
   <link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
   <link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
   <link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
   <link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
   <!-- END GLOBAL MANDATORY STYLES -->


   <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.css" />
   <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/select2/select2.css" />
   <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css" />


   <!-- BEGIN THEME STYLES -->
   <link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css" />
   <link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
   <link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css" />
   <link id="style_color" href="../metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" />
   <link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css" />

   <link href="../metronic/datepicker/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

   <script src="../js/jquery.min.js"></script>

</head>

<body onload="isPrinted();" class="page-header-fixed page-quick-sidebar-over-content page-full-width">
   <?php include("../header.php"); ?>
   <div class="clearfix"></div>
   <div class="page-container">

      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
         <div class="page-content">
            <div class="row">
               <div class="col-md-12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <div class="breadcrumbs">
                     <h3><i class="fa fa-truck"></i> TRIP DETAILS</h3>
                     <ol class="breadcrumb">
                        <li>
                           <a href="../home.php"><i class="fa fa-home"></i> HOME</a>
                        </li>
                        <li>
                           <a href="request_list.php"><i class="fa fa-list"></i> REQUEST LIST</a>
                        </li>
                        <li>
                           <a href="dispatch_details.php?id=<?php echo urlencode($_GET['id']); ?>"><i class="fa fa-tags"></i> TRIP DETAILS</a>
                        </li>
                        <li class="active"><i class="fa fa-edit"></i> UPDATE TRIP DETAILS</li>
                     </ol>
                  </div>
               </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
               <div style="margin-left:150px;" class="col-md-10">

                  <?php
                  $p = '';
                  if (isset($_POST['dispatch_edit'])) {
                     $ticket    = $_POST['ticket_no'];
                     $dept      = $_POST['deparment'];

                     $v_type    = $_POST['vehicle'];
                     $ex        = explode('|', $v_type);
                     $unit      = $ex[0];
                     $type      = $ex[1];
                     $vcode     = $_POST['cost_code'];

                     $app_date  = $_POST['app_date'];

                     $origin    = $_POST['origin'];
                     $desti     = $_POST['destination'];
                     //$driver    = $_POST['driver'];
                     $dest      = $origin . '|' . $desti;
                     $purpose   = $_POST['purpose'];
                     //$date_out  = $_POST['date_out'];

                     foreach (($_POST['passengers']) as $edit_p) {
                        $p .=  strtoupper($edit_p . "|");
                        $pass = rtrim($p, '|');
                     }

                     //$passenger = $_POST['passenger'];

                     $odom_s    = $_POST['odom_start'];
                     //$user      = $_POST['requestor'];
                     $update_dispatch = update_dispatch_details($ticket, $dept, $type, $dest, $purpose, $pass, $odom_s, $app_date, $unit, $vcode);
                     // echo $_POST['vehicle'];


                     if ($update_dispatch) {
                        echo '<div class="alert alert-success alert-dismissable">
                                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                 <strong><span class="fa fa-check-square-o"></span> Success!</strong> Dispatch details <b>Updated</b>...
                              </div>';
                     } else {
                        echo '<div class="alert alert-danger alert-dismissable">
                                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                 <strong><span class="fa fa-warning"></span> Error!</strong> Dispatch <b>Updation</b> Failed...
                              </div>';
                     }

                     if($_POST['is_print']==1){
                        echo "<script type=\"text/javascript\">
                                window.open('dispatch_printout.php?id=".$_GET['id']."', '_blank')
                              </script>";
                     }
                  }

                  if (isset($_POST['return_edit'])) {
                     $request = $_POST['request_id'];
                     $odom_e = $_POST['odom_end'];
                     $odom_s = $_POST['odom_startn'];
                     //$fuel_c = $_POST['fuel_consu'];
                    
                     $ticket = $_POST['ticket_no'];

                     $rdate = explode(' ', $_POST['return_date']);
                     $return = $rdate[0] . ' ' . $_POST['return_time'];
                     $return_dt = date('Y-m-d H:i', strtotime($return));

                    
                     $numberOfTrips = $_POST['numberOfTrips'];
                     $close = $_SESSION['esdvms_username'];
                     $closed_at = date('Y-m-d h:i:s a');

                     $update_return = update_return_date($odom_e, $ticket, $return_dt,  $close, $closed_at, $numberOfTrips,$odom_s);

                     if ($update_return) {
                        sqlsrv_query($conn, "UPDATE dispatch SET Status = 'Completed' WHERE tripTicket = '" . $_GET['id'] . "' ");

                        $sql1 = "SELECT * FROM dispatch WHERE request_id = '" . $request . "' ";
                        $params1 = array();
                        $options1 =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
                        $stmt1 = sqlsrv_query($conn, $sql1, $params1, $options1);

                        $sql2 = "SELECT * FROM dispatch WHERE request_id = '" . $request . "' and status = 'Completed' ";
                        $params2 = array();
                        $options2 =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
                        $stmt2 = sqlsrv_query($conn, $sql2, $params2, $options2);

                        $row_count1 = sqlsrv_num_rows($stmt1);
                        $row_count2 = sqlsrv_num_rows($stmt2);

                        if ($row_count1 == $row_count2) {
                           sqlsrv_query($conn, "UPDATE vehicle_request SET status = 'Closed' WHERE id = '" . $request . "' ");
                        }

                        echo '<div class="alert alert-success alert-dismissable">
                                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                 <strong><span class="fa fa-check-square-o"></span> Success!</strong> Trip Ticket <b>Closed</b>...
                              </div>';
                     } else {
                        echo '<div class="alert alert-danger alert-dismissable">
                                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                 <strong><span class="fa fa-warning"></span> Error!</strong> Return Trip <b>Updation</b> Failed...
                              </div>';
                     }
                  }

                  ?>

                  <!-- BEGIN SAMPLE FORM PORTLET-->
                  <div class="portlet light bordered">
                     <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                           <i class="fa fa-automobile font-red-sunglo"></i>
                           <span class="caption-subject bold uppercase"> Update Dispatch Form</span>
                        </div>
                     </div>
                     <div class="portlet-body">
                        <div class="tab-content">
                           <!-- PERSONAL INFO TAB -->
                           <input type="hidden" id="tid" value="<?= $_GET['id']; ?>">
                           <div id="disptachTable" class="tab-pane active"></div>

                        </div>
                     </div>
                  </div>
                  <!-- END SAMPLE FORM PORTLET-->
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
            <?php echo date('Y'); ?> &copy; PMC
         </div>
         <div class="page-footer-tools">
            <span class="go-top">
               <i class="fa fa-angle-up"></i>
            </span>
         </div>
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
<script src="<?php echo $url; ?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

<script type="text/javascript" src="../metronic/datepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="../metronic/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="<?php echo $url; ?>js/notifications.js"></script>
<script src="<?php echo $url; ?>js/comments.js"></script>
<script>
   $(document).ready(function() {
      showDispatch();
   });

   function showDispatch() {
      $id = $('#tid').val();
      $.ajax({
         url: 'dispatch_edit_form.php',
         type: 'POST',
         async: false,
         data: {
            dispatch: 1,
            id: $id
         },
         success: function(response) {
            $('#disptachTable').html(response);
         }
      });
   }


   (function($) {
      $(function() {

         var addFormGroup = function(event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
            var $formGroupClone = $formGroup.clone();
            $(this)
               .toggleClass('btn-default btn-add btn-danger btn-remove')
               .html('x');
            $formGroupClone.find('input').val('');
            $formGroupClone.insertAfter($formGroup);
         };


         var removeFormGroup = function(event) {
            event.preventDefault();

            if (confirm('Are you sure you want to remove this passenger? ')) {
               var $formGroup = $(this).closest('.form-group');
               var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
               $formGroup.remove();
            } else {

            }
         };

         $(document).on('click', '.btn-add', addFormGroup);
         $(document).on('click', '.btn-remove', removeFormGroup);
      });
   })
   (jQuery);


   jQuery(document).ready(function() {
      Metronic.init(); // init metronic core components
      Layout.init(); // init current layout
      ComponentsDropdowns.init();

   });
</script>

</html>