<?php
   include("config.php");
   include('functions.php');

   session_start();
   if(!$_SESSION['esdvms_username']){
   	header("location:login.php");
   }

   if(isset($_GET['id'])){
      // user details
      $ud = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT dept,fullname FROM users WHERE id = '".$_GET['id']."' "));
   }

   if(!isset($_GET['startDate'])){
   	$_GET['endDate']=date('Y-m-d');
   	$_GET['startDate']="2018-01-01";
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
      <?php include("header.php");?>
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
                           <i class="fa fa-cogs"></i> User <small>Maintenance</small>                           
                        </h3>
                     </div>
               </div>
               <div class="clearfix"></div>
                                 <div class="row">
                                    <div class="col-md-4 ">
                                    <?php 

                                       if (isset($_POST['e_user'])) {
                                            $domain = $_POST['domain'];
                                            $fname  = $_POST['fname'];
                                            $role   = $_POST['u_role'];
                                            $dept   = $_POST['dept'];
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
                                            $dept     = $_POST['dept'];
                                            $role     = $_POST['u_role'];
                                            $isLocked = 0;
                                            $active   = 1;
                                          
                                            $check_duplication = sqlsrv_query($conn, "SELECT domain FROM users WHERE domain = '$domain' ");
                                           
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
                                                    <i class="fa fa-users font-red-sunglo"></i>
                                                    <span class="caption-subject bold uppercase"> User Form</span>
                                                </div>
                                                <a class="btn btn-sm blue pull-right" href="user-maintenance.php">Add New</a>
                                            </div>
                                            <div class="portlet-body form">
                                                <div class="row">
                                                    <div class="col-md-12 well">
                                                        <div class="form-group">
                                                            <input class="form-control" type="text" name="search" id="search" placeholder="Search Employee ( Last Name, First Name)"/>
                                                        </div>
                                                        <div id="result"></div>
                                                    </div>
                                                    <form action="" method="post">
                                                        <div class="col-md-12">

                                                            <?php if (isset($_GET['name']) || isset($_GET['id'])) { ?> 
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="control-label">Full Name</label>
                                                                        <?php 
                                                                            if(isset($_GET['name'])){
                                                                                $fname = $_GET['name'];
                                                                                echo "<input readonly type=\"text\" class=\"form-control\" name=\"fname\" value=\"$fname\"/>";
                                                                            } else if(isset($_GET['id'])){
                                                                                $n = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT fullname FROM users WHERE id = '".$_GET['id']."' "));
                                                                                $name=explode("|", $n['fullname']);
                                                                                $fname = $name[0];
                                                                                echo "<input readonly type=\"text\" class=\"form-control\" name=\"fname\" value=\"$fname\"/>";
                                                                            } else { ?>
                                                                                <input readonly type="text" placeholder="First Name" name="fname" class="form-control" />
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="control-label">Department</label>
                                                                        <?php 
                                                                            if(isset($_GET['dept'])){
                                                                                $dept = $_GET['dept'];
                                                                                echo "<input readonly type=\"text\" class=\"form-control\" name=\"dept\" value=\"$dept\"/>";
                                                                            } else if(isset($_GET['id'])){
                                                                                $d = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT dept FROM users WHERE id = '".$_GET['id']."' "));
                                                                                $dept = $d['dept'];
                                                                                echo "<input readonly type=\"text\" class=\"form-control\" name=\"dept\" value=\"$dept\"/>";
                                                                            } else { ?>
                                                                                <input readonly type="text" placeholder="Department" name="dept" class="form-control" />
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            <?php 
                                                                } else { ?>
                                                                
                                                            <?php } ?>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <?php 
                                                                        if(isset($_GET['id'])){
                                                                            $row = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT domain FROM users WHERE id = '".$_GET['id']."' "));
                                                                            $domain = $row['domain'];
                                                                            echo "<label class=\"control-label\">Domain</label>";
                                                                            echo "<input required type=\"text\" class=\"form-control\" name=\"domain\" value=\"$domain\"/>";
                                                                        } else if(isset($_GET['name'])) {
                                                                            echo "<label class=\"control-label\">Domain</label>";
                                                                            echo "<input required type=\"text\" class=\"form-control\" name=\"domain\"/>";
                                                                        }

                                                                     ?>
                                                                </div>
                                                                <div class="col-md-6">
                                                                        <?php 
                                                                            if(isset($_GET['id'])){
                                                                                $row = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT role FROM users WHERE id = '".$_GET['id']."' "));

                                                                                echo "<label class=\"control-label\">Role</label>";
                                                                                echo "<select required class=\"form-control\" name=\"u_role\">";

                                                                                if($row['role']== 'requestor') {
                                                                                echo "<option value=".$row['role'].">".strtoupper($row['role'])."</option>
                                                                                      <option value=\"approver\">APPROVER</option>
                                                                                      <option value=\"admin\">ADMIN</option> ";
                                                                                }
                                                                                else if($row['role']== 'approver') {
                                                                                echo "<option value=".$row['role'].">".strtoupper($row['role'])."</option>
                                                                                      <option value=\"admin\">ADMIN</option>
                                                                                      <option value=\"requestor\">REQUESTOR</option> ";
                                                                                }
                                                                                else if($row['role']== 'admin') {
                                                                                echo "<option value=".$row['role'].">".strtoupper($row['role'])."</option>
                                                                                      <option value=\"requestor\">REQUESTOR</option>
                                                                                      <option value=\"approver\">APPROVER</option> ";
                                                                                }
                                                                                echo "</select>";

                                                                            } else if(isset($_GET['name'])) {
                                                                                echo "<label class=\"control-label\">Role</label>";
                                                                                echo "<select required class=\"form-control\" name=\"u_role\">";
                                                                                    echo "<option value=\"\">-- Select Role --</option>
                                                                                            <option value=\"requestor\">REQUESTOR</option>
                                                                                            <option value=\"approver\">APPROVER</option>
                                                                                            <option value=\"admin\">ADMIN</option>";
                                                                                echo "</select>";

                                                                            }
                                                                        ?>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                                <?php if (isset($_GET['id'])) { ?> 
                                                                    <button class="btn purple pull-right" type="submit" name="e_user">
                                                                        <span class="glyphicon glyphicon-edit"></span> Update 
                                                                    </button>
                                                                <?php 
                                                                    } else { ?>
                                                                    <button class="btn blue pull-right" type="submit" name="a_user">
                                                                        <span class="glyphicon glyphicon-send"></span> Submit 
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </form>   
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div id="emp_tbl"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END SAMPLE FORM PORTLET-->                                                                              
                                    </div>
                                    <div class="col-md-8 ">
                                       <div class="row">
                                    <div class="col-md-12">
                                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                        <?php 
                                             if (isset($_POST['lock_user'])) {
                                                $id = $_POST['id'];

                                                $user = lock_selected_user($id);

                                                    if ($user) {
                                                        $successMSG = "User has been <b>Locked</b>... ";
                                                        echo "<script>
                                                                setTimeout(function(){ $('#success').fadeOut();
                                                                }, 3000 );
                                                              </script>";
                                                    } else {
                                                        $errorMSG = "User <b>Lock</b> Failed... ";
                                                        echo "<script>
                                                                setTimeout(function(){ $('#error').fadeOut();
                                                                }, 3000 );
                                                              </script>";
                                                    }
                                            } 

                                             if (isset($_POST['unlock_user'])) {
                                                $id = $_POST['id'];

                                                $user = unlock_selected_user($id);

                                                    if ($user) {
                                                        $successMSG = "User has been <b>Unlocked</b>...";
                                                        echo "<script>
                                                                setTimeout(function(){ $('#success').fadeOut();
                                                                }, 3000 );
                                                              </script>";
                                                    } else {
                                                        $errorMSG = "User <b>Unlock</b> Failed...";
                                                        echo "<script>
                                                                setTimeout(function(){ $('#error').fadeOut();
                                                                }, 3000 );
                                                              </script>";
                                                    }
                                            } 

                                             if (isset($_POST['activate'])) {
                                                $id = $_POST['id'];

                                                $user = activate_selected_user($id);

                                                    if ($user) {
                                                        $successMSG = "User <b>Activated</b> Successfully...";
                                                        echo "<script>
                                                                setTimeout(function(){ $('#success').fadeOut();
                                                                }, 3000 );
                                                              </script>"; 
                                                    } else {
                                                        $errorMSG = "User <b>Activation</b> Failed...";
                                                        echo "<script>
                                                                setTimeout(function(){ $('#error').fadeOut();
                                                                }, 3000 );
                                                              </script>"; 
                                                    }
                                            } 

                                             if (isset($_POST['deactivate'])) {
                                                $id = $_POST['id'];

                                                $user = deactivate_selected_user($id);

                                                    if ($user) {
                                                        $successMSG = "User <b>Deactivated</b> Successfully...";
                                                        echo "<script>
                                                                setTimeout(function(){ $('#success').fadeOut();
                                                                }, 3000 );
                                                              </script>"; 
                                                    } else {
                                                        $errorMSG = "User <b>Deactivation</b> Failed...";
                                                        echo "<script>
                                                                setTimeout(function(){ $('#error').fadeOut();
                                                                }, 3000 );
                                                              </script>"; 
                                                    }
                                            } 

                                        ?>
                                        <?php if (isset($successMSG)) {
                                          ?> 
                                          <div id="success" class="alert alert-success alert-dismissable">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong><span class="fa fa-check-square-o"></span> Success!</strong> <?php echo $successMSG; ?>
                                          </div>
                                          <?php
                                        } else if(isset($errorMSG)) {
                                          ?>
                                           <div id="error" class="alert alert-danger alert-dismissable">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong><span class="fa fa-warning"></span> Error!</strong> <?php echo $errorMSG; ?>
                                          </div>
                                          <?php
                                        } 
                                        ?>

                                        <div class="portlet light bordered">
                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <i class="fa fa-users font-dark"></i>
                                                    <span class="caption-subject bold uppercase"> Users List</span>
                                                </div>
                                                <div class="tools"> </div>
                                            </div>
                                            <div class="portlet-body">
                                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                                    <thead>
                                                        <tr>
                                                            <th>User</th>
                                                            <th>Domain</th> 
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>Is Lock</th>
                                                            <th width="170px"></th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>User</th>
                                                            <th>Domain</th> 
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>Is Lock</th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>    
                                                    <?php 
                                                    $query = get_all_users();
                                                        while ($u= sqlsrv_fetch_array($query)) {
                                                            $id = $u['id'];
                                                        ?>
                                                        <tr>                                                            
                                                            <td>
                                                              <?php  
                                                              $empdetails=explode("|", $u['fullname']);
                                                              $empname = $empdetails[0];
                                                              echo strtoupper($empname); 
                                                              ?>
                                                            </td>
                                                            <td><?php echo strtoupper($u['domain']); ?></td>
                                                            <td><?php echo strtoupper($u['dept']); ?></td>
                                                            <td><?php echo strtoupper($u['role']); ?></td>
                                                            <td><center>
                                                                <?php echo ($u['isLocked'] == 1) ? "<a title='Unlock User' data-toggle='modal' class='btn btn-circle btn-icon-only red' href='#unlock".$id."'><span class='fa fa-lock'></span></a>" : "<a title='Lock User' data-toggle='modal' class='btn btn-circle btn-icon-only green' href='#lock".$id."'><span class='fa fa-unlock'></span></a>"; ?>
                                                                </center>

                                                                <div class="modal fade" id="unlock<?php echo $id; ?>" tabindex="-1" role="basic" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <form action="" method="POST">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                    <h4 class="modal-title"><b>Confirmation</b></h4>
                                                                                </div>
                                                                                <div class="modal-body"> Are you sure you want to <b>Unlock</b> this user? </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="unlock_user" class="btn btn-circle green"><span class="fa fa-key"></span> Unlock</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="modal fade" id="lock<?php echo $id; ?>" tabindex="-1" role="basic" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <form action="" method="POST">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                    <h4 class="modal-title"><b>Confirmation</b></h4>
                                                                                </div>
                                                                                <div class="modal-body"> Are you sure you want to <b>Lock</b> this user? </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="lock_user" class="btn btn-circle red"><span class="fa fa-lock"></span> Lock</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-circle btn-sm blue" href="user-maintenance.php?id=<?=$id;?>">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                                <?php if ($u['active']=='1'){ ?>
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
                                                                                <div class="modal-body"> Are you sure you want to <b>Deactivate</b> this user? </div>
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
                                                                                <div class="modal-body"> Are you sure you want to <b>Activate</b> this user? </div>
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

         $(document).ready(function(){
            $('#search').on('keyup', function(){

                if($("#search").val() == ""){
                    $('#result').empty();
                } else {
                    var search = $("#search").val();
                    $.ajax({
                        url: 'search-hris-emp.php',
                        type: 'POST',
                        data: {
                          search: search
                        },
                        dataType: 'text',
                        success: function(data){
                            $("#result").html(data);
                        }
                    });
                }
            });
        });

      </script>

   </body>
   <!-- END BODY -->
</html>