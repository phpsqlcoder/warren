<?php
   include("../config.php");
   include("../functions.php");
   session_start();
   if(!$_SESSION['esdvms_username']){
      header("location:login.php");
   }
   
   
   if(isset($_GET['act'])){
      if($_GET['act'] == 'submit'){
         $type = $_POST['dtype'];
         if(strlen($_POST['dtype2'])>1){
            $type = $_POST['dtype2'];
         }
         $insert = sqlsrv_query($conn,"insert into drivers (driver_name, type) VALUES ('".$_POST['dname']."', '".$type."')");
         header("location: drivers.php?added=1");
      }
      if($_GET['act'] == 'update'){
         $id = $_POST['edid'];
         $type = $_POST['edtype'];
         if(strlen($_POST['edtype2'])>1){
            $type = $_POST['edtype2'];
         }
         $update = sqlsrv_query($conn,"update drivers set driver_name='".$_POST['edname']."', type='".$type."' where id='".$id."'");
         header("location: drivers.php?updated=1");
      }
      if($_GET['act'] == 'activate'){
         $id = $_GET['id'];
         $activate = sqlsrv_query($conn,"update drivers set isActive=null where id='".$id."'");
         header("location: drivers.php?activated=1");
      }
      if($_GET['act'] == 'deactivate'){
         $id = $_GET['id'];
         $activate = sqlsrv_query($conn,"update drivers set isActive=0 where id='".$id."'");
         header("location: drivers.php?deactivated=1");
      }
   }
 
   $data = '';
   $types = ['GSD Driver', 'Motor Pool Driver', 'Mine Driver'];
   $driver_qry = sqlsrv_query($conn,"select * from drivers");
   while($r = sqlsrv_fetch_array($driver_qry)){
      if(!in_array($r['type'],$types) && strlen($r['type'])>1){
         array_push($types,$r['type']);
      }
      $btn = '<a href="javascript:void(0)" class="btn btn-xs red" onclick="disable_driver('.$r['id'].')">Disable</a>';
      if($r['isActive']=='0'){
         $btn = '<a href="drivers.php?act=activate&id='.$r['id'].'" class="btn btn-xs blue">Enable</a>';
      }
      $data.='<tr>             
               <td>'.$r['driver_name'].'</td>     
               <td>'.$r['type'].'<input type="hidden" name="driver'.$r['id'].'" id="driver'.$r['id'].'" value="'.$r['id'].'|'.$r['driver_name'].'|'.$r['type'].'"></td>            
               <td>
                  <a href="#" onclick="edit('.$r['id'].')" class="btn btn-xs green" >Edit</a>
                  '.$btn.'
               </td>
      </tr>';
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
              
               
               <?php if(isset($_GET['added'])){ ?>
               <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <strong>Record Added</strong>
               </div>
               <?php 
               } 
                  if(isset($_GET['updated'])){ 
               ?>

               <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <strong>Record Updated</strong>
               </div>
               <?php } if(isset($_GET['activated'])){ 
               ?>

               <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <strong>Record Activated</strong>
               </div>
               <?php } if(isset($_GET['deactivated'])){ 
               ?>

               <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <strong>Record Deactivated</strong>
               </div>
               <?php } ?>
               
               <div class="modal fade" id="modal-add">
                  <div class="modal-dialog">
                     <div class="modal-content">
                     <form action="drivers.php?act=submit" method="POST" class="form-inline" role="form">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                           <h4 class="modal-title">New Driver</h4>
                        </div>
                        <div class="modal-body">
                              <div class="form-group">
                                 <label for="">Name:</label>
                                 <input type="text" class="form-control" id="dname" name="dname" placeholder="Last, First MI">
                              </div>  
                              <br><br>
                              <div class="form-group">
                                 <label for="">Type:&nbsp;</label>
                                 
                                 <select name="dtype" id="dtype" class="form-control" placeholder="Select..">
                                 <option value="">Select..</option>
                                    <?php 
                                       foreach($types as $type){
                                          echo '<option value="'.$type.'">'.$type.'</option>';
                                       }
                                    ?>
                                 </select>
                                 
                              </div>  
                              <div class="form-group">
                                 <label for=""> OR Add New: </label>
                                 <input type="text" class="form-control" id="dtype2" name="dtype2" placeholder="New Type">
                              </div> 
                              
                           
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                           <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                     </form>
                     </div>
                  </div>
               </div>
               <div class="modal fade" id="modal-edit">
                  <div class="modal-dialog">
                     <div class="modal-content">
                     <form action="drivers.php?act=update" method="POST" class="form-inline" role="form">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                           <h4 class="modal-title">Update Driver</h4>
                        </div>
                        <div class="modal-body">
                              <div class="form-group">
                                 <label for="">Name:</label>
                                 <input type="text" class="form-control" id="edname" required name="edname" placeholder="Last, First MI">
                                 <input type="hidden" value="" required name="edid" id="edid">
                              </div>  
                              <br><br>
                              <div class="form-group">
                                 <label for="">Type:&nbsp;</label>
                                 
                                 <select name="edtype" id="edtype" class="form-control" placeholder="Select..">
                                    <option value="">Select..</option>
                                    <?php 
                                       foreach($types as $type){
                                          echo '<option value="'.$type.'">'.$type.'</option>';
                                       }
                                    ?>
                                    
                                 </select>
                                 
                              </div>  
                              <div class="form-group">
                                 <label for=""> OR Add New: </label>
                                 <input type="text" class="form-control" id="edtype2" name="edtype2" placeholder="New Type">
                              </div> 
                              
                           
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                           <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                     </form>
                     </div>
                  </div>
               </div>
               <div class="row margin-bottom-10">
                  <div class="col-md-2 pull-left"><span style="font-size:15px;"><input type="checkbox" name="filter" id="filter"> Filter</span></div>
                 
                  <div class="col-md-10 text-right">
                     <div>
                        <a class="btn blue btn-sm" href="#" style="margin-left: 10px;" onclick="$('#modal-add').modal('show');">
                          <span class="fa fa-plus"></span> Add New
                        </a>
                        <a class="btn green btn-sm" href="excel_download_request.php" style="margin-left: 10px;">
                           <span class="fa fa-download"></span> Download List
                        </a>                       
                     </div> 
                  </div>
               </div>  
               <div class="row">                  
                  <div class="col-md-12">                     
                     <div class="clearfix"></div>
                     <table style="font-size:12px;" class="table table-striped table-bordered table-hover js-dynamitable">
                        <thead id="nofilter_head">                                    
                           <tr>
                              <th style="font-size:11px;">Name </th>
                              <th style="font-size:11px;">Type </th>                          
                              <th style="font-size:11px;">Action </th>                                    
                           </tr>                                  
                        </thead>
                        <thead id="filter_head" style="display:none;">
                           
                           <tr>
                              <th style="font-size:11px;">Name <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Type <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Action <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              </tr>
                           <tr>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>

                                                            
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              echo $data;
                           ?>
                        </tbody>
                     </table>

                     
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
            <?php echo date('Y'); ?> &copy; PMC
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
      <script src="../js/table/dynamitable.jquery.min.js"></script>

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
            ComponentsPickers.init();
           
         });
      </script>

<script>
   $("#filter").click( function(){
      if( $(this).is(':checked') ) {
         $('#filter_head').show();
         $('#nofilter_head').hide();
      }
      else{
         $('#nofilter_head').show();
         $('#filter_head').hide();
      }
   });
 
   function edit(x){
      var y = $('#driver'+x).val();
      
      var d = y.split("|");
      //alert(y);
      $('#edid').val(d[0]);
      $('#edname').val(d[1]);
      $('#edtype').val(d[2]);

      $('#modal-edit').modal('show');
   }

   function disable_driver(x){
      var r = confirm("Are you sure you want to disable this driver?");
      if (r == true) {
         window.location.href = "drivers.php?act=deactivate&id="+x;
      } else {
         return false;
      }
      
   }
</script>

   </body>
   <!-- END BODY -->
</html>