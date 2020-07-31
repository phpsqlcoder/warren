<?php
ob_start();
include('config.php');
$data='';
$rq=sqlsrv_query($conn,"select * from coaster_feedback order by id DESC");
while($r=sqlsrv_fetch_array($rq)){
  $data.='<tr>
            <td>'.strtoupper($r['empid']).'</td>
            <td>'.$r['feedback'].'</td>
  </tr>';
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
  <title>Booking System</title>


  <link rel="shortcut icon" href="favicon.ico">


  <!-- Global styles START -->          
  <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
  <!-- Page level plugin styles END -->

  <!-- Theme styles START -->
  <link href="metronic/assets/global/css/components.css" rel="stylesheet">
  <link href="metronic/assets/frontend/layout/css/style.css" rel="stylesheet">
  <link href="metronic/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
  <link href="metronic/assets/frontend/layout/css/themes/red.css" rel="stylesheet" id="style-color">
  <link href="metronic/assets/frontend/layout/css/custom.css" rel="stylesheet">
  <style>
    .nav-tabs > li {
    float:none;
    display:inline-block;
    zoom:1;
    }

    .nav-tabs {
        text-align:center;
    }
    .footer {
        position: fixed;
        height: 50px;
        bottom: 0;
        width: 100%;
    }
  </style>
</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="corporate">
    
    <!-- BEGIN TOP BAR -->
    <div class="pre-header">
        <div class="container">
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                <div class="col-md-6 col-sm-6 additional-shop-info">
                    <ul class="list-unstyled list-inline">
                        <li><i class="fa fa-phone"></i><span>loc. 3113</span></li>
                        <li><i class="fa fa-envelope-o"></i><span>rsmacaraya@philsaga.com</span></li>
                    </ul>
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                <div class="col-md-6 col-sm-6 additional-nav">
                    <ul class="list-unstyled list-inline pull-right">
                        <li><a href="#">PMC Coaster Booking System</a></li>
                               
                    </ul>
                </div>
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->  
<?php include("header.php");?>
    <div class="main">
      <div class="container">
       
        <br><br>
     
         <div class="col-md-12 col-sm-12">
                          <form action="home.php?actfeed=submitfeedback" method="post">
                            <div class="form-group">
                              <label>We would like to hear your feedback, inquiries and comments.</label>
                              <textarea class="form-control" rows="6" name="feedback"></textarea>
                            </div>
                            <div class="form-group">
                              <label>ID #</label>
                              <input type="text" class="form-control" placeholder="Enter your Employee ID" name="empid"> 
                              <p class="help-block">
                                 Enter your employee ID so we could contact you about your inquiries.
                              </p>                             
                            </div>
                            <div class="form-group">
                              <input type="submit" value="Submit" class="btn blue">                                                         
                            </div>
                          </form>
                        </div>
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">           
            <div class="content-page">
                 <h1>Feedback Page</h1>
            </div>
             <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Feedback</th>                                        
                  </tr>
                </thead>
                <tbody>
                    <?php echo $data;?>
                </tbody>
            </table>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
      </div>
    </div>
</div></div>
    <!-- BEGIN FOOTER -->
    <div class="footer">
      <div class="container">
        <div class="row">
          <!-- BEGIN COPYRIGHT -->
          <div class="col-md-6 col-sm-6 padding-top-10">
            <?php echo date('Y'); ?> © PMC-ICT. ALL Rights Reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
          </div>
          <!-- END COPYRIGHT -->
          <!-- BEGIN PAYMENTS -->
          <div class="col-md-6 col-sm-6 padding-top-10">            
            <p class="pull-right">Developed by: <a class="gwapo" href="#" style="color:grey;text-decoration:none;font-size:10px;">Jundrie</a></p>
          </div>
          <!-- END PAYMENTS -->
        </div>
      </div>
    </div>

    <!-- END FOOTER -->

    <!-- Load javascripts at bottom, this will reduce page load time -->
    <!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->
    <!--[if lt IE 9]>
    <script src="metronic/assets/global/plugins/respond.min.js"></script>
    <![endif]--> 
    <script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
    <script src="metronic/assets/frontend/layout/scripts/back-to-top.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
    <script src="metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->

    <script src="metronic/assets/frontend/layout/scripts/layout.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();  
              jQuery(".gwapo").css('cursor','url(images/gwapo.PNG),auto');
       jQuery(".gwapo").css('cursor','url(images/gwapo.PNG),auto');     
       $('#topcontrol').hide();
                   });

    </script>
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>