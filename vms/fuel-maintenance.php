<?php
  include("config.php");
  include('functions.php');

  if(isset($_GET['id'])) {
    $fuel = sqlsrv_fetch_array(get_selected_fuel_type($_GET['id']));
  }


   session_start();
   if(!$_SESSION['esdvms_username']){
   	header("location:login.php");
   }

   if (isset($_POST['deactivate'])) {
    $query = sqlsrv_query($conn,"UPDATE fuel_types SET isActive = 0 WHERE id = '".$_POST['id']."' ");
      if($query){
        $fuelSuccessMSG = 'Fuel Successfully <b>Deactivated</b>...';
      }
      else {
        $fuelErrorMSG = 'Fuel <b>Deactivation</b> failed...';
      }
   }

   if (isset($_POST['activate'])) {
    $query = sqlsrv_query($conn,"UPDATE fuel_types SET isActive = 1 WHERE id = '".$_POST['id']."' ");
      if($query){
        $fuelSuccessMSG = 'Fuel Successfully <b>Activated</b>...';
      }
      else {
        $fuelErrorMSG = 'Fuel <b>Activation</b> failed...';
      }
   }

   if (isset($_POST['e_fuel'])) {
    $query = sqlsrv_query($conn,"UPDATE fuel_types SET name = '".$_POST['ftype']."', code = '".$_POST['code']."' WHERE id = '".$_GET['id']."' ");
      if($query){
        $successMSG = 'Fuel Successfully <b>Updated</b>...';
      }
      else {
        $errorMSG = 'Fuel <b>Updation</b> failed...';
      }
   }

   if (isset($_POST['a_fuel'])) {
    $query = sqlsrv_query($conn,"INSERT INTO fuel_types (name,code,isActive) VALUES ('".$_POST['ftype']."', '".$_POST['code']."', 1 )");
      if($query){
        $successMSG = 'Fuel Successfully <b>Inserted</b>...';
      }
      else {
        $errorMSG = 'Fuel <b>Insertion</b> failed...';
      }
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
      <link href="google.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
      <!-- END GLOBAL MANDATORY STYLES -->

      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/select2/select2.css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css"/>
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
   </head>
   <body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
      <!-- BEGIN HEADER -->
      <?php include('header.php'); ?>
      <div class="clearfix"></div>
      <!-- BEGIN CONTAINER -->
      <div class="page-container">
         <!-- BEGIN CONTENT -->
         <div class="page-content-wrapper">
            <div class="page-content">
                                 <br><br><br><br>
               <div class="row">
                     <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title">
                           <i class="fa fa-cogs"></i> Fuel <small>Maintenance</small>                           
                        </h3>
                     </div>
               </div>
               <div class="clearfix"></div>
                                 <div class="row">
                                    <div class="col-md-6 ">

                                        <?php if (isset($successMSG)) {
                                          ?> 
                                          <div id="Success" class="alert alert-success alert-dismissable">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong><span class="fa fa-check-square-o"></span> Success!</strong> <?php echo $successMSG; ?>
                                          </div>
                                          <?php
                                        } else if(isset($errorMSG)) {
                                          ?>
                                           <div id="Error" class="alert alert-danger alert-dismissable">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong><span class="fa fa-warning"></span> Error!</strong> <?php echo $errorMSG; ?>
                                          </div>
                                          <?php
                                        } 
                                        ?>

                                        <!-- BEGIN SAMPLE FORM PORTLET-->
                                        <div class="portlet light bordered">
                                            <div class="portlet-title">
                                                <div class="caption font-red-sunglo">
                                                    <i class="fa fa-fire font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> Fuel Creation / Updation Form</span>
                                                </div>
                                          
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active">
                                                    <form role="form" action="" method="POST">
                                                        <div class="form-group col-md-12">
                                                                <div class="col-md-6">
                                                                    <label class="control-label">Fuel Type</label>
                                                                    <?php if (isset($_GET['id'])) {
                                                                        ?> 
                                                                        <input required type="text" placeholder="Fuel Type" name="ftype" value="<?= $fuel['name']; ?>" class="form-control" />
                                                                        <?php
                                                                      } else {
                                                                        ?>
                                                                         <input required type="text" placeholder="Fuel Type" name="ftype" class="form-control" />
                                                                        <?php
                                                                      } 
                                                                    ?>
                                                                      
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="control-label">Item Code</label>
                                                                    <?php if (isset($_GET['id'])) {
                                                                        ?> 
                                                                        <input required type="text" placeholder="Item Code" name="code" value="<?= $fuel['code']; ?>" class="form-control" />
                                                                        <?php
                                                                      } else {
                                                                        ?>
                                                                         <input required type="text" placeholder="Item Code" name="code" class="form-control" />
                                                                        <?php
                                                                      } 
                                                                    ?>
                                                                </div>
                                                            </div>


                                                            <div style="text-align: right;">
                                                                <?php 
                                                                    if(isset($_GET['id'])){ 
                                                                        ?>
                                                                     <a class="btn btn-circle red" href="fuel-maintenance.php"><i class="fa fa-backward"></i> Cancel</a>
                                                                    <button class="btn btn-circle blue" name="e_fuel" type="submit"><span class="glyphicon glyphicon-edit"></span> Update </button>
                                                                <?php } else { ?>
                                                                    <button class="btn btn-circle blue" type="submit" name="a_fuel">
                                                                        <span class="glyphicon glyphicon-send"></span> Submit 
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
                                    <div class="col-md-6 ">
                                       <div class="row">
                                    <div class="col-md-12">
                                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                        <?php if (isset($fuelSuccessMSG)) {
                                          ?> 
                                          <div id="success" class="alert alert-success alert-dismissable">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong><span class="fa fa-check-square-o"></span> Success!</strong> <?php echo $fuelSuccessMSG; ?>
                                          </div>
                                          <?php
                                        } else if(isset($fuelErrorMSG)) {
                                          ?>
                                           <div id="error" class="alert alert-danger alert-dismissable">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong><span class="fa fa-warning"></span> Error!</strong> <?php echo $fuelErrorMSG; ?>
                                          </div>
                                          <?php
                                        } 
                                        ?>

                                        <div class="portlet light bordered">
                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <i class="fa fa-users font-dark"></i>
                                                    <span class="caption-subject bold uppercase"> Fuel List</span>
                                                </div>
                                                <div class="tools"> </div>
                                            </div>
                                            <div class="portlet-body">
                                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                                    <thead>
                                                        <tr>
                                                            <th>Fuel Type</th>
                                                            <th>Item Code</th> 
                                                            <th width="170px"></th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Fuel Type</th>
                                                            <th>Item Code</th> 
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>    
                                                    <?php 
                                                    $query = get_all_fuel_type();
                                                        while ($f = sqlsrv_fetch_array($query)) {
                                                          $id = $f['id'];
                                                        ?>
                                                        <tr>                                                            
                                                            <td><?php echo $f['name']; ?></td>
                                                            <td><?php echo $f['code']; ?></td>
                                                            <td>
                                                                <a class="btn btn-circle btn-sm blue" href="fuel-maintenance.php?id=<?=$id;?>">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                                <?php if ($f['isActive']=='1'){ ?>
                                                                        <a class="btn btn-circle btn-sm green" data-toggle="modal" href="#inactive<?= $id ?>"> 
                                                                            <i class="fa fa-check"></i> Active
                                                                        </a>
                                                                  
                                                                <div class="modal fade" id="inactive<?php echo $id; ?>" tabindex="-1" role="basic" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <form action="" method="POST">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                    <h4 class="modal-title"><b>Confirmation</b></h4>
                                                                                </div>
                                                                                <div class="modal-body"> Are you sure you want to <b>Deactivate</b> this fuel? </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="deactivate" class="btn btn-circle red">Deactivate</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>

                                                                <?php } else { ?>
                                                                        <a class="btn btn-circle btn-sm red" data-toggle="modal" href="#active<?=$id;?>"> 
                                                                            <i class="fa fa-close"></i> Inactive  
                                                                        </a>
                                                                <div class="modal fade" id="active<?php echo $id; ?>" tabindex="-1" role="basic" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <form action="" method="POST">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                    <h4 class="modal-title"><b>Confirmation</b></h4>
                                                                                </div>
                                                                                <div class="modal-body"> Are you sure you want to <b>Activate</b> this fuel? </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="activate" class="btn btn-circle blue">Activate</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <?php  }?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>   
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div style="margin-top:300px;"></div>
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
      <script type="text/javascript">
        function showdept(x){
          
           var deptrec = x.split('|');
           // alert(x);
           // console.log(deptrec[1]);
           $('#depart_ment').val( deptrec[1] ); //this syntax is for id
           // $('input[name$=depart_ment]').val( deptrec[1] ); // this syntax is for name
         }

      </script>
   </body>
   <!-- END BODY -->
</html>