
<?php 
include('../config.php');
session_start();


$request = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT di.*, dr.driver_name FROM dispatch as di left join drivers as dr on di.driver_id = dr.id WHERE tripTicket = '".$_GET['id']."' "));

$p = sqlsrv_query($conn, "UPDATE dispatch SET isPrinted = 1 WHERE tripTicket = '".$request['tripTicket']."' ");

$vinfo = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM unit WHERE id = '".$request['unitId']."' ")); //r
$vrequest = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM vehicle_request WHERE id = '".$request['request_id']."' ")); //c
$other_info = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM request_other_info WHERE request_id = '".$vrequest['id']."' "));
$user = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT fullname FROM users WHERE domain = '".$_SESSION['esdvms_username']."' "));




if($vrequest['date_needed'] == NULL) {
    $date_needed = '';
} else {
    $date_needed = $vrequest['date_needed']->format('Y-m-d h:i:s A');
}

if($request['dateStart'] == NULL) {
    $date_start = '';
} else {
    $date_start = $request['dateStart']->format('Y-m-d h:i:s A');
}

$return_date = $request['dateEnd'] == NULL ? '____________________________' : $request['dateEnd']->format('Y-m-d h:i A');
$odo_end = $request['odometer_end'] == NULL ? '____________________________' : $request['odometer_end'];
// if($r['dateEnd'] == NULL) {
//     $return_date = '';
// } else {
//     $return_date = $r['dateEnd']->format('Y-m-d h:i A');
// }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Vehicle Monitoring System</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #5 for invoice sample" name="description" />
        <meta content="" name="author" /> 
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="../metronic/assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="../metronic/assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="../metronic/assets/pages/css/invoice.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="../metronic/assets/layouts/layout5/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="../metronic/assets/layouts/layout5/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
        <style>
            .borderless td, .borderless th {
               border: none !important;
            }
            @media print {
               .printBtn {
                  display: none !important;
               }
            }
        </style>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-md">
        <!-- BEGIN CONTAINER -->
        <div class="wrapper">
            <div class="container-fluid">
                <div class="page-content">
                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        <img src="images.jpg" style="height: 120px;" alt="">
                        <h3 style="font-family:'Times New Roman', Times, serif; margin-left: 110px;margin-top: -90px;"><strong>PHILSAGA MINING CORPORATION</strong></h3>
                        <a href="javascript:;" class="btn btn-lg btn-success pull-right printBtn" onclick="javascript:window.print();"><i class="fa fa-print"></i> Print</a>
                        <ul class="list-unstyled">
                            <li style="margin-left: 110px;">
                                Purok 1-A Bayugan 3, Rosario, Agusan Del Sur
                            </li>
                        </ul>
                    </div>
                    <!-- BEGIN SIDEBAR CONTENT LAYOUT -->
                    <div class="page-content-container">
                        <div class="page-content-row">
                            <div class="page-content-col">
                                <!-- BEGIN PAGE BASE CONTENT -->
                                <div class="invoice">
                                    <div class="row invoice-logo">
                                        <div class="col-xs-12">
                                            <center><h3 style="font-family:'Times New Roman', Times, serif"><strong>ENGINEERING & CONSTRUCTION SERVICES DIVISION</strong></h3></center>
                                            <center><h4 style="font-family:'Times New Roman', Times, serif"><strong>TRIP TICKET FORM & FUEL SLIP FORM</strong></h4></center>
                                            <center><strong>TRIP TICKET # : <?php echo $_GET['id']; ?></strong></center>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <ul class="list-unstyled">
                                                <li> Request # : <?php echo $vrequest['refcode']; ?></li>
                                                <li> Driver : <?php echo $request['driver_name']; ?> </li>
                                                <li> Vehicle : <?php echo $vinfo['name']; ?> </li>
                                            </ul>
                                        </div>
                                        <div class="col-xs-6">
                                            <ul class="list-unstyled">
                                                <li> Date Needed : <?php echo $date_needed; ?></li>
                                                <li> Date Out : <?php echo $date_start; ?> </li>
                                                <li> Plate # : <?php echo $vinfo['plateno']; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <ul class="list-unstyled">
                                                <li> Destination : <?php echo strtoupper(str_replace('|',' - ',$request['destination'])) ?></li>
                                                <li> Passengers :<?php echo ucfirst(str_replace('|','   *   ',$request['passengers'])) ?></li>
                                                <li> Purpose : <?php echo strtoupper($request['purpose']); ?> </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-xs-6">
                                            <ul class="list-unstyled">
                                                <li> Contact Person : <?php echo strtoupper($other_info['contact_person']); ?></li>
                                            </ul>
                                        </div>
                                          <div class="col-xs-6">
                                            <ul class="list-unstyled">
                                                <li> Contact # : <?php echo $other_info['contact_no']; ?></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                          <br>
                                             <table class="table borderless" style="border-top: 4px dotted black;">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" style="font-size: 20px;font-family:'Times New Roman', Times, serif"><br><center>RETURN SLIP FORM</center></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   <tr>
                                                      <td>Return Date & Time : <?php echo $return_date; ?></td>
                                                      <td>Odometer Start : <?php echo number_format($request['odometer_start'],2); ?></td>
                                                   </tr>
                                                    <tr>
                                                        <td>ECS Security Guard : ____________________________</td>
                                                        <td>Odometer End : <?php echo $odo_end; ?></td>
                                                    </tr>
                                                </tbody>
                                             </table>

                                             <table class="table borderless" style="display:none;border-top: 4px dotted black;">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" style="font-size: 20px;font-family:'Times New Roman', Times, serif"><center>FUEL SLIP FORM</center></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  <tr>
                                                    <td colspan="2">Request Cost Code : <?php echo $vrequest['costcode']; ?></td>
                                                  </tr>
                                                   <tr>
                                                      <td>Vehicle Cost Code : <?php echo $request['vehicle_cost_code']; ?></td>
                                                      <td>RQ # : <?php echo $request['RQ']; ?></td>
                                                   </tr>
                                                   <tr>
                                                      <td>Department : <?php echo $request['deptId']; ?></td>
                                                      <td>Item Code : <?php echo $request['itemCode']; ?></td>
                                                   </tr>
                                                   <tr>
                                                      <td>Fuel Type : <?php echo $request['fuel_added_type']; ?></td>
                                                      <td>UoM : <?php echo $request['uom']; ?></td>
                                                   </tr>
                                                   <tr>
                                                      <td>Requested Qty : <?php echo number_format($request['fuel_requested_qty'],2); ?></td>
                                                      <td>Acutal Qty : ____________________________</td>
                                                   </tr>
                                                   <tr>
                                                      <td><center><br>&nbsp;<br>__________________________________<br>ISSUED BY (MCD)</center></td>
                                                      <td><center><br><?php echo $request['driver_name']; ?><br>__________________________________<br>RECEIVED BY</center></td>
                                                   </tr>
                                                </tbody>
                                             </table>
        <br>                                     
                                             <table class="table borderless" style="border-top: 4px dotted black;">
                                                <tbody>
                                                  <tr><td colspan="3">&nbsp;</td></tr>
                                                   <tr>
                                                      <td>Dispatched By :</td>
                                                      <td>Driver :</td>
                                                      <td>Approved By :</td>
                                                   </tr>
                                                   <tr>
                                                      <td><center><?php echo $user['fullname']; ?><br>__________________________________<br><br>Date : ____________________________<br>( MM / DD / YYYY )</center></td>
                                                      <td><center><?php echo $request['driver_name']; ?><br>__________________________________<br><br>Date : ____________________________<br>( MM / DD / YYYY )</center></td>
                                                      <td><center>DAMASCO, PHIL CARLO<br>__________________________________<br><br>Date : ____________________________<br>( MM / DD / YYYY )</center></td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="pull-right">
                                             <strong>Confirmed / Accepted By:</strong>
                                             <br><br>
                                             <table>
                                                <tbody>
                                                   <tr>
                                                      <td>End-User: ________________________________<br><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name & Signature</center></td>
                                                   </tr>
                                                   <tr>
                                                      <td>Date : ________________________________<br><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( MM / DD / YYYY )</center></td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTAINER -->

        <!-- BEGIN CORE PLUGINS -->
        <script src="../metronic/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../metronic/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="../metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="../metronic/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="../metronic/assets/layouts/layout5/scripts/layout.min.js" type="text/javascript"></script>
        <script src="../metronic/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="../metronic/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>