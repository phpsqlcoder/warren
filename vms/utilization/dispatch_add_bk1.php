<?php
include("../config.php");
include('functions.php');
session_start();

$ticket_no = refcode($_GET['id']);
$d = get_dispatch_details($_GET['id']);





$readonly = ($d['status'] == 'Cancelled') ? 'disabled' : '';


      $p = '';
      if(isset($_POST['dispatch'])) {

         $v_type    = $_POST['vehicle'];
         $ex        = explode('|',$v_type);
         $unit      = $ex[0];
         $type      = $ex[1];

         $dest_fr   = $_POST['origin'];
         $dest_to   = $_POST['destination'];

         $dest = $dest_fr.'-'.$dest_to;

         foreach(($_POST['passenger']) as $add_p) {
            $p .=  strtoupper($add_p."|");
            $passenger = rtrim($p,'|');
         }


            $add_dispatch = add_dispatch_details($_POST['deparment'],$type,$dest,$_POST['purpose'],$passenger,$_POST['odom_start'],$_POST['app_date'],$unit,$_POST['requestor'],$_POST['rid'], $_POST['date_out'],$_POST['driver']);

            if ($add_dispatch) {
               $successMSG = "Dispatch <b>Submitted</b>...";
            } else {
               $errorMSG = "Dispatch Details <b>Submission</b> Failed...";
            }

      }

      if(isset($_POST['cancel_vid'])) {
               $query_cancel = sqlsrv_query($conn,"UPDATE vehicle_request SET status = 'Cancelled', Cancelled_by = '".$_SESSION['esdvms_username']."' WHERE id = '".$_GET['id']."' ");

            if($query_cancel){
               $successMSG = 'Vehicle Request Status successfully changed into  <b>Cancelled</b>...';
            }else {
               $errorMSG = 'Vehicle Request <b>Cancellation</b> Failed...';
            }
         }

      if(isset($_POST['close_vid'])) {
               $query_cancel = sqlsrv_query($conn,"UPDATE vehicle_request SET status = 'Closed', Closed_by = '".$_SESSION['esdvms_username']."' WHERE id = '".$_GET['id']."' ");

            if($query_cancel){
               $successMSG = 'Vehicle Request Status successfully changed into  <b>Closed</b>...';
            }else {
               $errorMSG = 'Vehicle Request <b>Closing</b> Failed...';
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

   <link href="../metronic/datepicker/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

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
                  <h3 class="page-title">
                     <i class="fa fa-truck"></i> VEHICLE                         
                  </h3>
               </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
               <div class="col-md-12">

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
                  <div id="form" class="portlet light bordered">
                     <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                           <i class="fa fa-automobile font-red-sunglo"></i>
                           <span class="caption-subject bold uppercase"> Vehicle Dispatch Form Request # : <?php echo $d['refcode']; ?></span>
                        </div>
                     </div>
                     <div class="portlet-body">
                        <div class="tab-content">
                           <!-- PERSONAL INFO TAB -->
                           <div class="tab-pane active">
                              <div class="row">
                                 <div class="form-group col-md-12">
                                    <form role="form" action="" method="POST">

                                       <div class="form-group col-md-12">
                                          <input type="hidden" name="requestor" value="<?= $d['name']; ?>">
                                          <input type="hidden" name="rid" value="<?= $d['id']; ?>">
                                          <div class="alert alert-info"><center style="font-family: times;"><b>TRIP TICKET FORM</b><br><small style="color:red;">Note: Trip ticket number will be given after submission of this form</small></center></div>

                                          <div class="col-md-3">
                                             <label class="control-label">Date Out <i class="font-red"> *</i></label>
                                             <?php 
                                                if ($d['status'] == 'Cancelled') {
                                                    ?>
                                                      <div class="input-group col-md-12">
                                                         <div class="input-icon">
                                                            <i class="fa fa-calendar font-yellow"></i>
                                                            <input name="do" class="form-control" size="16" type="text" disabled>
                                                         </div>
                                                         <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                      </div>
                                                        <?php }
                                                else { ?>
                                                   <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="date_out">
                                                         <div class="input-icon">
                                                            <i class="fa fa-calendar font-yellow"></i>
                                                            <input name="do" class="form-control" size="16" type="text" value="" readonly>
                                                         </div>
                                                         <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                         <input type="hidden" name="date_out" id="date_out" value="" />
                                                      </div>
                                                <?php }
                                             ?>
                                          </div>
                                             

                                          <div class="col-md-3">
                                             <label class="control-label">Department <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-building-o font-yellow"></i>
                                                <input <?= $readonly; ?> type="text" class="form-control" name="deparment" value="<?= $d['dept']; ?>" >
                                             </div>
                                          </div>

                                          <div class="col-md-3">
                                             <label class="control-label">Vehicle <i class="font-red"> *</i></label>
                                             <select <?= $readonly; ?> required name="vehicle" class="form-control" >
                                                <?php                  
                                                $count = 0;
                                                $result = sqlsrv_query($conn,"SELECT name,id FROM unit");
                                                while ($row = sqlsrv_fetch_array($result)){
                                                   $count++; ?>
                                                   <?php if ($count > 1) {
                                                      ?> 
                                                      <option value="<?php echo $row['id'].'|'.$row['name']; ?>"><?php echo $row['name']; ?></option>
                                                      <?php
                                                   } else {
                                                      ?>
                                                      <option value="">-- Select Vehicle --</option>
                                                      <?php
                                                   } 
                                                   ?>      
                                                <?php } ?>
                                             </select>
                                          </div>

                                       </div>

                                       <div class="form-group col-md-12">
                                          <div class="col-md-3">
                                             <label class="control-label">Date Needed <i class="font-red"> *</i></label>
                                             <input required class="form-control" name="app_date" size="16" type="text" value="<?php echo date('Y-m-d'); ?>" readonly>
                                          </div>

                                          <div class="col-md-3">
                                             <label class="control-label">Driver <i class="font-red"> *</i></label>
                                             <select <?= $readonly; ?> required name="driver" class="form-control">
                                                <?php                  
                                                $count = 0;
                                                $result = sqlsrv_query($conn,"SELECT * FROM drivers");
                                                while ($drow = sqlsrv_fetch_array($result)){
                                                   $count++; ?>
                                                   <?php if ($count > 1) {
                                                      ?> 
                                                      <option value="<?php echo $drow['id']; ?>"><?php echo $drow['driver_name']; ?></option>
                                                      <?php
                                                   } else {
                                                      ?>
                                                      <option value="">-- Select Driver --</option>
                                                      <?php
                                                   } 
                                                   ?>      
                                                <?php } ?>
                                             </select>
                                          </div>

                                          <div class="col-md-3">
                                             <label class="control-label">From <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-globe font-yellow"></i>
                                                <input <?= $readonly; ?> required type="text" class="form-control" name="origin" placeholder="Origin" value="<?php echo $_SESSION['esdvms_dept']; ?>"> 
                                             </div>
                                          </div>

                                          <div class="col-md-3">
                                             <label class="control-label">To <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-globe font-yellow"></i>
                                                <input <?= $readonly; ?> required type="text" class="form-control" name="destination" placeholder="Destination" value="<?php echo $d['destination'] ?>"> 
                                             </div>
                                          </div>
                                       </div>

                                       <div class="form-group col-md-12">
                                          <div class="col-md-12">
                                             <label class="control-label">Purpose <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-comment-o font-yellow"></i>
                                                <textarea <?= $readonly; ?> required name="purpose" class="form-control"><?php echo $d['purpose'] ?></textarea>
                                             </div>

                                          </div>
                                       </div>

                                       <div class="form-group col-md-12">
                                          <div class="col-md-3">
                                             <label class="control-label">Odometer Start <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-tachometer font-yellow"></i>
                                                <input <?= $readonly; ?> required type="number" class="form-control" name="odom_start" placeholder="Odometer Start">
                                             </div>
                                          </div>

                                          <div class="col-md-6">
                                             <div class="form-group multiple-form-group">
                                                <label>Passengers</label>
                                                <div class="form-group input-group input-icon">
                                                   <i class="fa fa-users font-yellow"></i>
                                                   <input <?= $readonly; ?> required type="text" class="form-control" name="passenger[]" placeholder="Passengers">
                                                   <span class="input-group-btn"><button <?= $readonly; ?> type="button" class="btn btn-primary btn-add">Add
                                                   </button></span>
                                                </div>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="form-group col-md-12">
                                          <div class="alert alert-info"><center style="font-family: times;"><b>FUEL SLIP FORM</b></center>
                                          </div>
                                       </div>

                                       <div class="form-group col-md-12">
                                          <div class="col-md-3">
                                             <label class="control-label">Cost Code <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-tachometer font-yellow"></i>
                                                <input readonly type="number" class="form-control" name="cost_code" placeholder="Cost Code">
                                             </div>
                                          </div>

                                          <div class="col-md-3">
                                             <label class="control-label">RQ Number <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-tachometer font-yellow"></i>
                                                <input type="number" class="form-control" name="rq_num" placeholder="RQ Number">
                                             </div>
                                          </div>

                                       </div>

                                       <div class="form-group col-md-12">
                                          <div class="col-md-3">
                                             <label class="control-label">Fuel Type <i class="font-red"> *</i></label>
                                             <select required name="vehicle" class="form-control" >
                                                <option value="">-- Select Status --</option>
                                                <option value="">New Request</option>    
                                                <option value="">Waiting for Vehicle Availability</option>
                                                <option value="">Waiting for Driver Availability</option> 
                                                <option value="">On-Hold by Requestor</option>
                                                <option value="">In-Progress</option>
                                                <option value="">Cancelled</option>
                                                <option value="">Closed</option>                    
                                             </select>
                                          </div>
                                          <div class="col-md-3">
                                             <label class="control-label">Item Code <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-tachometer font-yellow"></i>
                                                <input type="number" class="form-control" name="item_code" placeholder="Item Code">
                                             </div>
                                          </div>
                                          <div class="col-md-3">
                                             <label class="control-label">Qty <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-tachometer font-yellow"></i>
                                                <input type="number" class="form-control" name="qty" placeholder="Quantity">
                                             </div>
                                          </div>
                                          <div class="col-md-3">
                                             <label class="control-label">UOM <i class="font-red"> *</i></label>
                                             <div class="input-icon">
                                                <i class="fa fa-tachometer font-yellow"></i>
                                                <input type="text" class="form-control" name="liter" placeholder="Unit of Measurement" value="Liter">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="form-group col-md-12">
                                          <!-- <button style="float: right;" type="button" class="btn default" onclick="history.back(-1)" >
                                             <span class="fa fa-backward"></span> Back
                                          </button> -->
                                          <?php 
                                                $status = '';
                                                $query = sqlsrv_query($conn,"select status from dispatch where request_id = '".$_GET['id']."' ");
                                                   while($row = sqlsrv_fetch_array($query)){
                                                        $status .= $row['status'].',';    
                                                   }
                                                   $text = explode(",", $status);
                                                   if(!in_array('In-Progress', $text)){
                                                      $button = '';
                                                   } else {
                                                      $button = 'disabled';
                                                   }
      
                                             ?>
                                          <a <?= $button; ?> style="float: right;" data-toggle='modal' class='btn yellow' href='#close-<?php echo $_GET['id']; ?>'><span class='glyphicon glyphicon-remove-circle'></span> Close
                                          </a>
                                          <a <?= $button; ?> style="float: right;" data-toggle='modal' class='btn red' href='#cancel-<?php echo $_GET['id']; ?>'><span class='glyphicon glyphicon-remove-circle'></span> Cancel
                                          </a>
                                          <button style="float: right;" type="button" class="btn green" >
                                             <span class="fa fa-edit"></span> Update
                                          </button>
                                          <button style="float: right;" class="btn btn-primary" type="submit" name="dispatch">
                                             <span class="glyphicon glyphicon-send"></span> Submit 
                                          </button>
                                       </div>
                                    </form>
                                    <!--- ### Modals ### -->
                                       <div class="modal fade" id="cancel-<?php echo $_GET['id']; ?>" tabindex="-1" role="basic" aria-hidden="true">
                                          <div class="modal-dialog">
                                             <form action="" method="POST">
                                                <div class="modal-content">
                                                   <div class="modal-header">
                                                      <input type="text" name="tid" value="<?php echo $_GET['id']; ?>">
                                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                      <h4 class="modal-title"><b>Confirmation</b></h4>
                                                   </div>
                                                      <div class="modal-body"> Are you sure you want to <b>Cancel</b> this Vehicle Request? </div>
                                                   <div class="modal-footer">
                                                      <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                                                      <button type="submit" name="cancel_vid" class="btn btn-circle blue"><span class="glyphicon glyphicon-remove-circle"></span> Yes</button>
                                                   </div>
                                                </div>
                                             </form>
                                          </div>
                                       </div>

                                       <div class="modal fade" id="close-<?php echo $_GET['id']; ?>" tabindex="-1" role="basic" aria-hidden="true">
                                          <div class="modal-dialog">
                                             <form action="" method="POST">
                                                <div class="modal-content">
                                                   <div class="modal-header">
                                                      <input type="text" name="tid" value="<?php echo $_GET['id']; ?>">
                                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                      <h4 class="modal-title"><b>Confirmation</b></h4>
                                                   </div>
                                                      <div class="modal-body"> Are you sure you want to <b>Close</b> this Vehicle Request? </div>
                                                   <div class="modal-footer">
                                                      <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                                                      <button type="submit" name="close_vid" class="btn btn-circle blue"><span class="glyphicon glyphicon-remove-circle"></span> Yes</button>
                                                   </div>
                                                </div>
                                             </form>
                                          </div>
                                       </div>
                                    <!--- ### Modals ### -->
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- END SAMPLE FORM PORTLET-->                                                                              
               </div>                    
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
<script>
   (function ($) {
      $(function () {

         var addFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
            var $formGroupClone = $formGroup.clone();
            $(this)
            .toggleClass('btn-default btn-add btn-danger btn-remove')
            .html('Remove');
            $formGroupClone.find('input').val('');
            $formGroupClone.insertAfter($formGroup);
         };

         var removeFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
            $formGroup.remove();
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

   $('.form_datetime').datetimepicker({
      language:  'en',
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      forceParse: 0,
      showMeridian: 1
   });

</script>

</html>