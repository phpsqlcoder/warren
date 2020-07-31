<?php
include("../config.php");
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
</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
   <?php include("header.php");?>
   <div class="clearfix"></div>
   <div class="page-container">
      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
         <div class="page-content">
            <br><br><br><br>
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
               <div class="col-md-10">
                  <?php 

                  if (isset($_POST['e_user'])) {
                     $domain = $_POST['domain'];
                     $fname  = $_POST['fname'];
                     $role   = $_POST['u_role'];
                     $dept   = $_POST['deparment'];
                     $id     = $_GET['id'];

                     $user = update_user($domain,$fname,$role,$dept,$id);

                     if ($user) {
                        $success = "User <b>Updated</b> Successfully...";
                        echo "<script>
                        setTimeout(function(){ $('#Success').fadeOut();
                        }, 3000 );
                        </script>";
                     } else {
                        $error = " User <b>Updation</b> Failed... ";
                        echo "<script>
                        setTimeout(function(){ $('#Error').fadeOut();
                        }, 3000 );
                        </script>";
                     }
                  }

                  if (isset($_POST['a_user'])) {
                     $domain   = $_POST['domain'];
                     $fname    = $_POST['fname'];
                     $role     = $_POST['u_role'];
                     $dept     = $_POST['deparment'];
                     $isLocked = 0;
                     $active   = 1;

                     $check_duplication = sqlsrv_query($conn, "SELECT * FROM users WHERE domain = '$domain' ");

                     $row_count = sqlsrv_has_rows($check_duplication);

                     if ($row_count >= 1)  {
                        $error = "Domain already Exist...";
                        echo "<script>
                        setTimeout(function(){ $('#Error').fadeOut();
                        }, 3000 );
                        </script>";  
                     } else {
                        $user = add_user($domain,$fname,$role,$dept,$isLocked,$active);

                        if ($user) {
                           $success = "User <b>Inserted</b> Successfully...";
                           echo "<script>
                           setTimeout(function(){ $('#Success').fadeOut();
                           }, 3000 );
                           </script>";
                        } else {
                           $error = " User <b>Insertion</b> Failed... ";
                           echo "<script>
                           setTimeout(function(){ $('#Error').fadeOut();
                           }, 3000 );
                           </script>";
                        }   
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
                           <span class="caption-subject bold uppercase"> Vehicle Dispart Form</span>
                        </div>

                     </div>
                     <div class="portlet-body">
                        <div class="tab-content">
                           <!-- PERSONAL INFO TAB -->
                           <div class="tab-pane active">
                              <form role="form" action="" method="POST">
                                 <div class="form-group col-md-12">
                                   <div class="col-md-3">
                                       <label class="control-label">Application Date</label>
                                       <input class="form-control" type="date" name="app_date">
                                    </div>

                                    <div class="col-md-6">
                                       <label class="control-label">Trip & Ticket No.</label>
                                       <input type="text" class="form-control" name="ticket_no" readonly>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                   <div class="col-md-3">
                                       <label class="control-label">Driver</label>
                                       <select class="form-control"></select>
                                    </div>

                                    <div class="col-md-6">
                                       <label class="control-label">Civil Work Officer</label>
                                       <input type="text" class="form-control" name="ticket_no">
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                   <div class="col-md-12">
                                       <label class="control-label">Purpose</label>
                                       <textarea class="form-control"></textarea>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                   <div class="col-md-3">
                                       <label class="control-label">Date Out</label>
                                       <input type="date" class="form-control" name="">
                                    </div>
                                    <div class="col-md-3">
                                       <label class="control-label">Time Out</label>
                                       <input type="time" class="form-control" name="">
                                    </div>
                                    <div class="col-md-3">
                                       <label class="control-label">Passengers</label>
                                       <input type="text" class="form-control" name="">
                                    </div>
                                 </div>
                                 
                                 <div class="form-group col-md-12">
                                    <div class="col-md-12">
                                       <hr>
                                       <center><h4 style="color:#1BBC9B"><b>Fuel Slip Form</b></h4></center>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Odometer Start</label>
                                       <input type="text" class="form-control" name="">
                                    </div>
                                    
                                    <div class="col-md-3">
                                       <label class="control-label">Odometer End</label>
                                       <input type="text" class="form-control" name="">
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Consumption</label>
                                       <input type="text" class="form-control" name="">
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Added</label>
                                       <input type="number" class="form-control" name="" placeholder="Qty">
                                    </div>
                                    
                                    <div class="col-md-3">
                                       <label class="control-label">UOM</label>
                                       <input type="text" class="form-control" name="" readonly>
                                    </div>

                                    <div class="col-md-3">
                                       <label class="control-label">Fuel Type</label>
                                       <select class="form-control">
                                          <option value="">-- Select --</option>
                                          <option value="diesel">DIESEL</option>
                                          <option value="gasoline">GASOLINE</option>
                                       </select>
                                    </div>
                                 </div>



                                    <div style="text-align: right;">
                                       <?php 
                                       if(isset($_GET['id'])){ 
                                          ?>
                                          <a class="btn btn-circle red" href="user-maintenance.php"><i class="fa fa-backward"></i> Cancel</a>
                                          <button class="btn btn-circle blue" name="e_user" type="submit"><span class="glyphicon glyphicon-edit"></span> Update </button>
                                       <?php } else { ?>
                                          <button type="button" class="btn btn-circle red" onclick="history.back(-1)" >
                                             <span class="fa fa-backward"></span> Cancel
                                          </button>
                                          <button class="btn btn-circle blue" type="submit" name="a_user">
                                             <span class="glyphicon glyphicon-send"></span> Dispatch 
                                          </button>
                                       <?php } ?>
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
      <div class="modal-footer" id="footermode">
         <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
         <input type="submit" class="btn blue" value="Save">
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
   <script src="<?php echo $url;?>js/notifications.js"></script>
   <script src="<?php echo $url;?>js/comments.js"></script> 
   <script>
      jQuery(document).ready(function() {    
Metronic.init(); // init metronic core components
Layout.init(); // init current layout
ComponentsDropdowns.init();

});
</script>
</html>