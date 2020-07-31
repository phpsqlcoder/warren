<?php
include("../config.php");
include('functions.php');
session_start();
date_default_timezone_set('Asia/Manila');

$r = get_details_of_selected_dispatch($_GET['id']);
$driver = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from drivers where id='".$r['driver_id']."'"));
$dest = $r['destination'];
$ex            = explode('-',$dest);
$origin        = $ex[0];
$destination   = $ex[1];

$cancelled = ($r['Status'] == 'Cancelled' || $r['Status'] == 'Closed') ? 'none;' : '';

   if(isset($_POST['cancel_tid'])) {
      $date_cancelled = date('Y-m-d h:i:s a');

         $query = sqlsrv_query($conn,"UPDATE dispatch SET Status = 'Cancelled', Cancelled_by = '".$_SESSION['esdvms_username']."', Cancelled_at ='".$date_cancelled."' WHERE tripTicket = '".$_POST['tid']."' ");

      if($query){
         $successMSG = 'Trip Ticket Succesfully <b>Cancelled</b>...';
      }else {
         $errorMSG = 'Trip Ticket <b>Cancellation</b> Failed...';
      }
   }


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


   <script src="../js/jquery.min.js"></script>

</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
   <?php include("../header.php");?>
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
                        <li class="active"><i class="fa fa-tags"></i> TRIP DETAILS</li>
                    </ol>
                  </div>
               </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
               <div style="margin-left:150px;" class="col-md-10">

                  <?php 
                     if (isset($successMSG)) {
                         ?>
                         <div class="alert alert-success alert-dismissable">
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                 <span class="fa fa-check-square-o"></span><b> Success :</b> <?php echo $successMSG; ?>
                         </div>
                             <?php }
                     else if (isset($errorMSG)) { ?>
                        <div class="alert alert-danger alert-dismissable">
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                             <span class="fa fa-exclamation"></span><b> Error :</b> <?php echo $errorMSG; ?>
                         </div>
                     <?php }
                  ?>
                  <!-- BEGIN SAMPLE FORM PORTLET-->
                  <div class="portlet light bordered">
                     <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                           <i class="fa fa-truck font-red-sunglo"></i>
                           <span class="caption-subject uppercase"> <?= $r['tripTicket']; ?> Details</span><br>&nbsp;&nbsp;&nbsp;
                           <span style="font-size: 15px;color:black;" ><small>Status: <?= $r['Status']; ?></small></span>
                        </div>   

                        <div style="float:right;">
                           <a  class="btn yellow" href="dispatch_printout.php?id=<?php echo urlencode($_GET['id']); ?> "target="_blank">
                              <i class="fa fa-print"></i> Print
                           </a>

                           <a style="display:<?= $cancelled; ?>" class="btn blue" href="dispatch_edit.php?id=<?php echo urlencode($_GET['id']); ?> "><i class="fa fa-edit"></i> Edit/Update</a>
                           <a style="display:<?= $cancelled; ?>" data-toggle='modal' class='btn red' href='#cancel-<?php echo $_GET['id']; ?>'><span class='glyphicon glyphicon-remove-circle'></span> Cancel</a>

                           

                           <div class="modal fade" id="cancel-<?php echo $_GET['id']; ?>" tabindex="-1" role="basic" aria-hidden="true">
                              <div class="modal-dialog">
                                 <form action="" method="POST">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <input type="hidden" name="tid" value="<?php echo $_GET['id']; ?>">
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                          <h4 class="modal-title"><b>Confirmation</b></h4>
                                       </div>
                                          <div class="modal-body"> Are you sure you want to <b>Cancel</b> this Trip Ticket? </div>
                                       <div class="modal-footer">
                                          <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal"><i class="fa fa-backward"></i> Back</button>
                                          <button type="submit" name="cancel_tid" class="btn btn-circle blue"><span class="glyphicon glyphicon-remove-circle"></span> Confirm Cancel</button>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="portlet-body">
                        <div class="tab-content">
                           <!-- PERSONAL INFO TAB -->
                           <div class="tab-pane active">
                              <div class="row">
                                 <div class="form-group col-md-12">

                                    <div class="col-md-3">
                                       <label class="control-label">Date Out</label>
                                       <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="date_out">
                                          <div class="input-icon">
                                             <i class="fa fa-calendar font-yellow"></i>
                                             <input class="form-control" size="16" type="text" value="<?= $r['dateStart']->format('Y-m-d h:i:s'); ?>" readonly>
                                          </div>
                                          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                          <input type="hidden" name="date_out" id="date_out" value="<?= $r['dateStart']->format('Y-m-d h:i:s'); ?>" />
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Department</label>
                                       <div class="input-icon">
                                          <i class="fa fa-building-o font-yellow"></i>
                                          <input readonly type="text" class="form-control" value="<?= $r['deptId']; ?>" >
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Vehicle</label>     
                                       <input readonly class="form-control" type="text" value="<?= $r['type']; ?>">
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Trip & Ticket No.</label>
                                       <input type="text" class="form-control" value="<?= $r['tripTicket']; ?>" readonly>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Application Date</label>
                                       <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="dt_from">
                                          <div class="input-icon">
                                             <i class="fa fa-calendar font-yellow"></i>
                                             <input class="form-control" size="16" type="text" value="<?= $r['addedDate']->format('Y-m-d h:i:s'); ?>" readonly>
                                          </div>
                                          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                          <input type="hidden" name="app_date" id="dt_from" value="<?= $r['addedDate']->format('Y-m-d h:i:s'); ?>" />
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Driver</label>
                                       <input readonly type="text" class="form-control" value="<?php echo $driver['driver_name'];?>">

                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">From</label>
                                       <div class="input-icon">
                                          <i class="fa fa-globe font-yellow"></i>
                                          <input readonly type="text" class="form-control" value="<?= strtoupper($origin); ?>"> 
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">To</label>
                                       <div class="input-icon">
                                          <i class="fa fa-globe font-yellow"></i>
                                          <input readonly type="text" class="form-control" value="<?= strtoupper($destination); ?>"> 
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-12">
                                       <label class="control-label">Purpose</label>
                                       <div class="input-icon">
                                          <i class="fa fa-comment-o font-yellow"></i>
                                          <textarea readonly class="form-control"><?php echo strtoupper($r['purpose']); ?></textarea>
                                       </div>

                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-12">
                                       <div class="form-group multiple-form-group">
                                          <label>Passengers</label>
                                          <?php 
                                          $ex =  explode('|',$r['passengers']);
                                          
                                          echo '<ul class="list-inline">';
                                          foreach($ex as $pass) {
                                             echo '<li><input readonly class="form-control" type="text" value="'.$pass.'" /></li>';
                                          }
                                          echo '</ul>';
                                          ?>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="caption font-red-sunglo">
                                       <i class="fa fa-automobile font-red-sunglo"></i>
                                       <span class="caption-subject bold uppercase" style="font-size: 16px;"> Return Slip Form</span>
                                    </div>
                                    <hr>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Date Return</label>
                                       <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="date_return">
                                          <div class="input-icon">
                                             <i class="fa fa-calendar font-yellow"></i>
                                             <input class="form-control" size="16" type="text" value="<?= $r['odometer_end']; ?>" readonly>
                                          </div>
                                          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                          <input type="hidden" name="date_return" id="date_return" value="" />
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <label class="control-label">Odometer End</label>
                                       <div class="input-icon">
                                          <i class="fa fa-tachometer font-yellow"></i>
                                          <input readonly type="number" class="form-control" value="<?= $r['odometer_end']; ?>">
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Consumption</label>
                                       <div class="input-icon">
                                          <i class="fa fa-fire font-yellow"></i>
                                          <input readonly type="number" class="form-control" value="<?= $r['fuel_consumption']; ?>">
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Added</label>
                                       <div class="input-icon">
                                          <i class="fa fa-fire font-yellow"></i>
                                          <input readonly type="number" class="form-control" value="<?= $r['fuel_added_qty']; ?>">
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">UOM</label>
                                       <div class="input-icon">
                                          <i class="icon-calculator font-yellow"></i>
                                          <input readonly type="text" class="form-control" value="<?= $r['uom']; ?>">
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Type</label>
                                       <input readonly class="form-control" type="text" value="<?= strtoupper($r['fuel_added_type']); ?>">
                                    </div>
                                    <div style="margin-top: 180px;"></div>
                                 </div>
                              </div>
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
   <script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
   
   <script src="../metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
   <script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
   <script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>

   <script type="text/javascript" src="../metronic/datepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
   <script type="text/javascript" src="../metronic/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
   <script src="<?php echo $url;?>js/notifications.js"></script>
   <script src="<?php echo $url;?>js/comments.js"></script> 
   </html>