<?php
include("../config.php");

session_start();
if(!$_SESSION['esdvms_username']){
    header("../location:login.php");
}

$msg="";

if(isset($_GET['act'])){

    $insert=sqlsrv_query($conn,"INSERT INTO vehicle_request_comments([request_id]
      ,[username]
      ,[AddedAt]
      ,[comment])
      VALUES('".strtoupper($_GET['id'])."','".$_SESSION['esdvms_username']."',GETDATE(),'".$_POST['feedback']."')");
  
  header("location:comments.php?remark=success&id=".$_GET['id']);
    
}

?>
<!DOCTYPE html>

<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 3.1.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest (the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Head BEGIN -->
<head>
  <meta charset="utf-8">
  <title>Request Status Page</title>


  <link rel="shortcut icon" href="favicon.ico">


  <!-- Global styles START -->          
  <link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="../metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
  <!-- Page level plugin styles END -->

  <!-- Theme styles START -->
  <link href="../metronic/assets/global/css/components.css" rel="stylesheet">
  <link href="../metronic/assets/frontend/layout/css/style.css" rel="stylesheet">
  <link href="../metronic/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
  <link href="../metronic/assets/frontend/layout/css/themes/red.css" rel="stylesheet" id="style-color">
  <link href="../metronic/assets/frontend/layout/css/custom.css" rel="stylesheet">
  
</head>
<div class="main">
      <div class="container">
        
         <?php 
         if(isset($_GET['remark'])){ ?>
            <div class="alert alert-success">
              <button class="close" data-close="alert"></button>
              <strong>Success!</strong> Request has been added.
            </div>
            <?php } ?>
            <?php 
            echo $msg;
        ?>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40" align="center">
        
          <!-- BEGIN CONTENT -->
                    <div class="col-md-12 col-sm-12"><br><br>
                      <h1>Request Status List</h1><br><br>
                      <div class="content-page">
                        <div class="row">
                            
                          <div class="col-md-8 col-md-offset-2" align="center">
                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            
                        <div class="portlet box purple">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-file-o"></i>Request
                        </div>
                        <div class="col-md-12 col-sm-12">
                          <form action="comments.php?act=submitfeedback&id=<?php echo $_GET['id']; ?>" method="post">
                            <div class="form-group">
                              <label>We would like to hear your request details.</label>
                              <textarea class="form-control" rows="6" name="feedback" id="feedback" required></textarea>
                            </div>
                            <div class="form-group">
                              <div class="modal-footer">
                              <a href="request_list.php"><button type="button" class="btn default">Cancel</button></a>
                              <input type="submit" value="Submit" class="btn blue pull-right">
                              </div>                                                         
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="table-scrollable">
                          <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>
                               #
                            </th>
                            <th>
                              Request
                            </th>
                            <th>
                               Username
                            </th>  
                            <th>
                                Date Added
                            </th>

                          </tr>
                          </thead>
                          
                          <?php
                                $seq=0;
                                $pq=sqlsrv_query($conn,"SELECT * FROM vehicle_request_comments WHERE request_id='".$_GET['id']."' ORDER BY AddedAt DESC");
                                while($p=sqlsrv_fetch_array($pq)){
                                  $seq++;
                                  echo '
                                      <tr>
                                        <td>'.$seq.'</td>
                                        <td>'.$p['comment'].'</td>
                                        <td>'.$p['username'].'</td>
                                        <td>'.date_format($p['AddedAt'],'Y-m-d h:i A').'</td>
                                      </tr>
                                  ';
                                }
                          ?>

                          <tbody>
                          
                          </tbody>

                          </table>
                        </div>
                      </div>
                    </div>


                </div>
                  <!-- END SAMPLE TABLE PORTLET-->
                </div>

                
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
      </div>

    <script src="../metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="../metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="../metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
    <script src="../metronic/assets/frontend/layout/scripts/back-to-top.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
    <script src="../metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->

    <script src="../metronic/assets/frontend/layout/scripts/layout.js" type="text/javascript"></script>   
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>