<?php
include("../config.php");
include('functions.php');
session_start();

$ticket_no = refcode($_GET['id']);

$d = get_dispatch_details($_GET['id']);

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

   <link href="../metronic/datepicker/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
   <link href="../metronic/datepicker/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

   <script src="../js/jquery.min.js"></script>

</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
   <?php include("header.php");?>
   <div class="clearfix"></div>
   <div class="page-container">
      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
         <div class="page-content">
            <div class="row">
               <div class="col-md-12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     <i class="fa fa-truck"></i> Vehicle                           
                  </h3>
               </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
               <div style="margin-left:150px;" class="col-md-10">

                  <?php 
                  $p = '';
                  if (isset($_POST['dispatch'])) {
                     $ticket    = $_POST['ticket_no'];
                     $dept      = $_POST['deparment'];

                     $v_type    = $_POST['vehicle'];
                     $ex        = explode('|',$v_type);
                     $unit      = $ex[0];
                     $type      = $ex[1];

                     $app_date  = $_POST['app_date'];
                     //$driver    = $_POST['driver'];
                     $dest      = $_POST['destination'];
                     $purpose   = $_POST['purpose'];
                     //$date_out  = $_POST['date_out'];

                     foreach(($_POST['passenger']) as $add_p) {
                       $p .=  strtoupper($add_p."|");
                       $passenger = rtrim($p,'|');
                   }

                     //$passenger = $_POST['passenger'];

                     $odom_s    = $_POST['odom_start'];
                     $odom_e    = $_POST['odom_end'];
                     $fuel_c    = $_POST['fuel_consu'];
                     $fuel_a    = $_POST['fuel_add'];
                     //$unit      = $_POST['uom'];
                     $fuel_t    = $_POST['fuel_type'];
                     $user      = $_POST['requestor'];


                     $add_dispatch = add_dispatch_details($ticket,$dept,$type,$dest,$purpose,$passenger,$odom_s,$odom_e,$fuel_c,$fuel_a,$fuel_t,$app_date,$unit,$user);

                     if ($add_dispatch) {
                        $success = "Dispatch <b>Submitted</b>...";
                        echo "<script>
                        setTimeout(function(){ $('#Success').fadeOut();
                        }, 4000 );
                        </script>";
                     } else {
                        $error = "Dispatch Details <b>Submission</b> Failed...";
                        echo "<script>
                        setTimeout(function(){ $('#Error').fadeOut();
                        }, 4000 );
                        </script>";
                     }
                  }

                  ?>

                  <?php if (isset($success)) {
                     ?> 
                     <div id="Success" class="alert alert-success alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><span class="fa fa-check-square-o"></span> Success!</strong> <?php echo $success; ?>
                     </div>
                     <?php
                  } else if(isset($error)) {
                     ?>
                     <div id="Error" class="alert alert-danger alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><span class="fa fa-warning"></span> Error!</strong> <?php echo $error; ?>
                     </div>
                     <?php
                  } 
                  ?>

                  <!-- BEGIN SAMPLE FORM PORTLET-->
                  <div class="portlet light bordered">
                     <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                           <i class="fa fa-automobile font-red-sunglo"></i>
                           <span class="caption-subject bold uppercase"> Vehicle Dispatch Form</span>
                        </div>

                     </div>
                     <div class="portlet-body">
                        <div class="tab-content">
                           <!-- PERSONAL INFO TAB -->
                           <div class="tab-pane active">
                              <form role="form" action="" method="POST">
                                 <div class="form-group col-md-12">
                                    <div class="col-md-12">
                                       <div style="height:45px;;" class="alert alert-success"><center><strong>Trip Ticket Form</strong></center></div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <input type="hidden" name="requestor" value="<?= $d['name']; ?>">

                                    <div class="col-md-3">
                                       <label class="control-label">Date Out</label>
                                       <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="date_out">
                                          <div class="input-icon">
                                             <i class="fa fa-calendar font-yellow"></i>
                                             <input class="form-control" size="16" type="text" value="" readonly>
                                          </div>
                                             <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                             <input type="hidden" name="date_out" id="date_out" value="" />
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Department</label>
                                       <div class="input-icon">
                                          <i class="fa fa-building-o font-yellow"></i>
                                          <input type="text" class="form-control" name="deparment" value="<?= $d['dept']; ?>" >
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Vehicle</label>
                                       <select required name="vehicle" class="form-control" >
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
                                                   <option>-- Select --</option>
                                                   <?php
                                                } 
                                                ?>      
                                          <?php } ?>
                                      </select>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Application Date</label>
                                       <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="dt_from">
                                          <div class="input-icon">
                                             <i class="fa fa-calendar font-yellow"></i>
                                             <input class="form-control" size="16" type="text" value="<?php echo date('Y-m-d'); ?>" readonly>
                                          </div>
                                             <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                             <input type="hidden" name="app_date" id="dt_from" value="<?php echo date('Y-m-d h:i:s'); ?>" />
                                       </div>

                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Driver</label>
                                       <select name= "driver" class="form-control">
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
                                                   <option>-- Select Driver --</option>
                                                   <?php
                                                } 
                                                ?>      
                                          <?php } ?>
                                       </select>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">From</label>
                                       <div class="input-icon">
                                          <i class="fa fa-globe font-yellow"></i>
                                          <input type="text" class="form-control" name="destination" placeholder="Destination" value="<?php echo $d['origin'] ?>"> 
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">To</label>
                                       <div class="input-icon">
                                          <i class="fa fa-globe font-yellow"></i>
                                          <input type="text" class="form-control" name="destination" placeholder="Destination" value="<?php echo $d['destination'] ?>"> 
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-12">
                                       <label class="control-label">Purpose</label>
                                       <div class="input-icon">
                                          <i class="fa fa-comment-o font-yellow"></i>
                                          <textarea name="purpose" class="form-control"><?php echo $d['purpose'] ?></textarea>
                                       </div>
                                       
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Odometer Start</label>
                                       <div class="input-icon">
                                          <i class="fa fa-tachometer font-yellow"></i>
                                          <input type="number" class="form-control" name="odom_start" placeholder="Odometer Start">
                                       </div>
                                    </div>

                                    <div class="col-md-6">
                                       <div class="form-group multiple-form-group">
                                          <label>Passengers</label>
                                          <div class="form-group input-group input-icon">
                                             <i class="fa fa-users font-yellow"></i>
                                             <input type="text" class="form-control" name="passenger[]" placeholder="Passengers">
                                                <span class="input-group-btn"><button type="button" class="btn btn-primary btn-add">Add
                                                </button></span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-12">
                                       <hr>
                                    </div>
                                    <div class="col-md-12">
                                       <div style="height:45px;" class="alert alert-info"><center><strong>Fuel Slip Form</strong></center></div>

                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Odometer Start</label>
                                       <div class="input-icon">
                                          <i class="fa fa-tachometer font-yellow"></i>
                                          <input type="number" class="form-control" name="odom_start" placeholder="Odometer Start">
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Odometer End</label>
                                       <div class="input-icon">
                                          <i class="fa fa-tachometer font-yellow"></i>
                                          <input type="number" class="form-control" name="odom_end" placeholder="Odometer End">
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Consumption</label>
                                        <div class="input-icon">
                                          <i class="fa fa-fire font-yellow"></i>
                                          <input type="number" class="form-control" name="fuel_consu" placeholder="Fuel Consumption">
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Added</label>
                                       <div class="input-icon">
                                          <i class="fa fa-fire font-yellow"></i>
                                          <input type="number" class="form-control" name="fuel_add" placeholder="Fuel Added">
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">UOM</label>
                                       <div class="input-icon">
                                          <i class="icon-calculator font-yellow"></i>
                                          <input type="text" class="form-control" name="uom" value="Liter">
                                       </div>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Type</label>
                                       <select name="fuel_type" class="form-control">
                                          <option value="">-- Select --</option>
                                          <option value="diesel">DIESEL</option>
                                          <option value="gasoline">GASOLINE</option>
                                       </select>
                                    </div>
                                 </div>



                                 <div style="text-align: right;">
                                       <button type="button" class="btn btn-circle red" onclick="history.back(-1)" >
                                          <span class="fa fa-backward"></span> Cancel
                                       </button>
                                       <button class="btn btn-circle blue" type="submit" name="dispatch">
                                          <span class="glyphicon glyphicon-send"></span> Dispatch 
                                       </button>
                                 </div>
                              </form>
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

<script src="../metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>

<script type="text/javascript" src="../metronic/datepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="../metronic/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>

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