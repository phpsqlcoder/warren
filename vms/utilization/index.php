<?php
   include("../config.php");
   session_start();
   if(!$_SESSION['esdvms_username']){
      header("location:login.php");
   }

   
   if(isset($_GET['act'])){
     
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
      <link href="../google.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
      <!-- END GLOBAL MANDATORY STYLES -->

      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/select2/select2.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
      <!-- BEGIN PAGE LEVEL STYLES -->
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/clockface/css/clockface.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>


      <!-- BEGIN THEME STYLES -->
      <link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
      <link id="style_color" href="../metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
      <!-- END THEME STYLES -->
      <link rel="shortcut icon" href="favicon.ico"/>
      
   </head>
   <body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
      <!-- BEGIN HEADER -->
      <?php include("../header.php");?>
      <div class="clearfix"></div>
      <!-- BEGIN CONTAINER -->
      <div class="page-container">
         <!-- BEGIN CONTENT -->
         <div class="page-content-wrapper">
            <div class="page-content">
               
               <div class="modal fade" id="newrequest" tabindex="-1" role="newrequest" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <form method="post" action="request_list_requestor.php?act=submit">
                           <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                              <h4 class="modal-title">New Vehicle Request</h4>
                           </div>
                           <div class="modal-body" style="height:300px">
                              <input type="hidden" name="action" value="add">
                              <table>
                                 <tr>
                                    <td>Date Needed: </td>
                                    <td>
                                       <div class="input-group date form_datetime">
                                          <input type="text" size="16" readonly class="form-control" name="date_needed" required="required">
                                          <span class="input-group-btn">
                                          <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                          </span>
                                       </div>
                                       <br>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Chargeable Cost Code:</td>
                                    <td>
                                       <input type="text" name="costcode" class="form-control" placeholder="Cost Code"><br>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>From: </td>
                                    <td>
                                       <input type="text" name="from" class="form-control" placeholder="Origin"><br>
                                    </td>                                    
                                 </tr>
                                 <tr>
                                    <td>To:</td>
                                    <td>
                                       <input type="text" name="to" class="form-control" placeholder="Destination"><br>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Purpose</td>
                                    <td>
                                       <textarea name="purpose" class="form-control" id="purpose" cols="50" rows="3" required="required"></textarea>
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
                  </div>
               </div>
                  
               
                  <div class="row">
                     <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <a href="request_list.php" class="btn btn blue"><i class="fa fa-list-alt"></i> Request List</a>  
                        <br><br>                      
                     </div>
                  </div>
   
            <div class="row">
             <!-- <div class="col-md-10 col-md-offset-1 col-sm-12"> -->
            <div class="col-md-6 col-sm-12">
                <div class="portlet box red">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-bar-chart"></i> TOTAL DISPATCHES PER VEHICLES
                        </div>
                        <div class="tools">
                          <?php
                            $dt = date('y-m-d');
                            $dt1 = date("Y-m-01", strtotime($dt));
                            $dt2 = date("Y-m-t", strtotime($dt));
                          ?>
                            <a href="../vehicles-no-dispatches-report.php?start=<?php echo $dt1;?>&end=<?php echo $dt2; ?>" style="color:white;" target="_blank">
                                <i class="fa fa-file-o"></i>
                            </a>
                            <a href="" class="reload"></a>
                            <a href="javascript:;" class="collapse"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">                                    
                            <div class="col-md-12 col-sm-12">
                                <iframe style="height:418px;" src="vehicle-dispatches.php" width="100%" class="iframe" scrolling="yes" frameborder="0"></iframe>
                            </div>                                    
                        </div>                
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-10 col-md-offset-1 col-sm-12"> -->
            <div class="col-md-6 col-sm-12">
                <div class="portlet box red">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-pie-chart"></i> VEHICLES DISTANCE TRAVELLED
                        </div>
                        <div class="tools">
                            <a href="../vehicles-distance-travelled-report.php?start=<?php echo $dt1;?>&end=<?php echo $dt2; ?>" style="color:white;" target="_blank">
                                <i class="fa fa-file-o"></i>
                            </a>
                            <a href="" class="reload"></a>
                            <a href="javascript:;" class="collapse"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">                                    
                            <div class="col-md-12 col-sm-12">
                                <iframe style="height:418px;" src="vehicle-distance.php" width="100%" class="iframe" scrolling="yes" frameborder="0"></iframe>
                            </div>                                    
                        </div>                
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
             <!-- <div class="col-md-10 col-md-offset-1 col-sm-12"> -->
            <div class="col-md-6 col-sm-12">
                <div class="portlet box red">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-bar-chart"></i> DISPATCH DISTRIBUTION PER DEPARTMENT
                        </div>
                        <div class="tools">
                            <a href="../dispatch-per-department-report.php?start=<?php echo $dt1;?>&end=<?php echo $dt2; ?>" style="color:white;" target="_blank">
                                <i class="fa fa-file-o"></i>
                            </a>
                            <a href="" class="reload"></a>
                            <a href="javascript:;" class="collapse"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">                                    
                            <div class="col-md-12 col-sm-12">
                                <iframe style="height:418px;" src="dispatches-per-department.php" width="100%" class="iframe" scrolling="yes" frameborder="0"></iframe>
                            </div>                                    
                        </div>                
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-10 col-md-offset-1 col-sm-12"> -->
            <div class="col-md-6 col-sm-12">
                <div class="portlet box red">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-pie-chart"></i> FREQUENT DESTINATIONS
                        </div>
                        <div class="tools">
                            <a href="../destinations-report.php?start=<?php echo $dt1;?>&end=<?php echo $dt2; ?>" style="color:white;" target="_blank">
                                <i class="fa fa-file-o"></i>
                            </a>
                            <a href="" class="reload"></a>
                            <a href="javascript:;" class="collapse"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">                                    
                            <div class="col-md-12 col-sm-12">
                                <iframe style="height:418px;" src="frequent-destinations.php" width="100%" class="iframe" scrolling="yes" frameborder="0"></iframe>
                            </div>                                    
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
      <script src="../metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
      <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
      <script src="../metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
      <script src="../metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>



      <script type="text/javascript" src="../metronic/assets/global/plugins/select2/select2.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/clockface/js/clockface.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
      <script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

      
      <script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
      <script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
      <script src="../metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
      <script src="../js/excel/src/jquery.table2excel.js"></script>
      <script src="../metronic/assets/admin/pages/scripts/components-pickers.js"></script>
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
            ComponentsPickers.init();


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