<?php
include("config.php");
include("charts/chart/fusioncharts.php");

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Vehicle Monitoring System</title>
    <link href="google.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->


    <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
    <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/select2/select2.css"/>
    <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css"/>


    <!-- BEGIN THEME STYLES -->
    <link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>

    <link href="metronic/datepicker/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

    <script src="charts/chart/js/fusioncharts.js"></script>
    <script src="charts/chart/js/themes1/fusioncharts.theme.fusion.js"></script>

    <script src="js/jquery.min.js"></script>


    <style type="text/css">
    @page 
    {
        size: auto;
        margin: 0;
    }
    @media print{
        #chart-1 {
            display: none;
        }
        #form {
            display: none;
        }
        #print-b {
            display: none;
        }
        #excel-b {
            display: none;
        }

    }

</style>
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
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title">
                            <i class="fa fa-certificate"></i> <small>&nbsp;&nbsp;List of Trip Tickets</small>                           
                        </h3>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12">

                        <!-- BEGIN SAMPLE FORM PORTLET-->
                        <div id="form" class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-list font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Generate Report</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->
                                    <div class="tab-pane active">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <form role="form" action="" method="GET">
                                                    <div class="col-md-2">
                                                        <label class="control-label">From <span class="font-red">*</span></label>
                                                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_from" data-link-format="yyyy-mm-dd">
                                                            <?php if (isset($_GET['start'])) {
                                                                ?> 
                                                                <div class="input-icon">
                                                                    <i class="fa fa-calendar font-yellow"></i>
                                                                    <input class="form-control" size="10" type="text" value="<?= $_GET['start']; ?>" readonly>
                                                                </div>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                                <input type="hidden" name="start" id="date_from" value="<?= $_GET['start']; ?>" />
                                                              <?php
                                                                } else {
                                                              ?>
                                                                <div class="input-icon">
                                                                    <i class="fa fa-calendar font-yellow"></i>
                                                                    <input class="form-control" size="10" type="text" value="" readonly>
                                                                </div>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                                <input type="hidden" name="start" id="date_from" value="" />
                                                              <?php
                                                                } 
                                                            ?>
                                                            
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-2">
                                                        <label class="control-label">To <span class="font-red">*</span></label>
                                                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_to" data-link-format="yyyy-mm-dd">
                                                            <?php if (isset($_GET['end'])) {
                                                                ?> 
                                                                <div class="input-icon">
                                                                    <i class="fa fa-calendar font-yellow"></i>
                                                                    <input class="form-control" size="10" type="text" value="<?= $_GET['end']; ?>" readonly>
                                                                </div>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                                <input type="hidden" name="end" id="date_to" value="<?= $_GET['end']; ?>" />
                                                              <?php
                                                                } else {
                                                              ?>
                                                                <div class="input-icon">
                                                                    <i class="fa fa-calendar font-yellow"></i>
                                                                    <input class="form-control" size="10" type="text" value="" readonly>
                                                                </div>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                                <input type="hidden" name="end" id="date_to" value="" />
                                                              <?php
                                                                } 
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="control-label">Driver</label>
                                                        <select name="driver" class="form-control">
                                                            <option value="">-- Select Driver --</option>
                                                            <?php                  
                                                            $count = 0;
                                                            $result = sqlsrv_query($conn,"SELECT * FROM drivers");

                                                            while ($drow = sqlsrv_fetch_array($result)){
                                                               $count++; ?>
                                                               <?php if ($count > 0) {
                                                                  ?> 
                                                                  <option <?php if(isset($_GET['driver'])) { if($_GET['driver']== $drow['id']){ echo "selected=\"selected\" "; } } else { echo ''; } ?> value="<?php echo $drow['id']; ?>"><?php echo $drow['driver_name']; ?></option>
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

                                                    <div class="col-md-2">
                                                        <label class="control-label">Vehicle</label>
                                                        <select name="unit" class="form-control">
                                                            <option value="">-- Select Vehicle --</option>
                                                            <?php                  
                                                            $count = 0;
                                                            $result = sqlsrv_query($conn,"SELECT * FROM unit");
                                                            while ($vrow = sqlsrv_fetch_array($result)){
                                                               $count++; ?>
                                                               <?php if ($count > 0) {
                                                                  ?> 
                                                                  <option <?php if(isset($_GET['unit'])) { if($_GET['unit']== $vrow['id']){ echo "selected=\"selected\" "; } } else { echo ''; } ?> value="<?php echo $vrow['id']; ?>"><?php echo $vrow['name']; ?></option>
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

                                                    <div class="col-md-2">
                                                        <label class="control-label">&nbsp;</label>
                                                        <button class="btn btn blue form-control" type="submit">
                                                            <span class="glyphicon glyphicon-refresh"></span> Generate
                                                        </button>
                                                    </div>
                                                </form>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END SAMPLE FORM PORTLET-->                                                                              
                    </div>                    
                </div> 

                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <i class="fa fa-tags font-dark"></i>
                                            <span class="caption-subject bold uppercase"> Trip Tickets</span>
                                        </div>
                                        <div class="actions">
                                            <ul class="list-inline">
                                                <li>
                                                    <form method="POST" action="excel_report.php">
                                                        <input type="hidden" name="date_from" value="<?php echo $_GET['start']; ?>">
                                                        <input type="hidden" name="date_to" value="<?php echo $_GET['end']; ?>">
                                                        <input type="hidden" name="driver" value="<?php echo $_GET['driver']; ?>">
                                                        <input type="hidden" name="unit" value="<?php echo $_GET['unit']; ?>">
                                                        <button type="submit" class="btn btn-circle blue" name="trip_tickets" id="excel-b"><i class="fa fa-file-excel-o"></i> Excel</button>
                                                    </form> 
                                                </li>
                                                <li>
                                                    <a class="btn btn-circle purple" id="print-b" href="javascript:window.print()"><span class="fa fa-print"></span> Print</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                                            <thead>
                                                <tr>
                                                    <th width="60px">Seq #</th>
                                                    <th>Ticket #</th>
                                                    <th>Driver</th>
                                                    <th>Vehicle</th>
                                                    <th>Purpose</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>    
                                                <?php 

                                                $cond="";
                                                $i = 1;

                                                if(isset($_GET['start'])){

                                                    if($_GET['driver'] != '' && $_GET['unit'] != ''){
                                                        $cond.=" and driver_id = '".$_GET['driver']."' and unitId = '".$_GET['unit']."' ";
                                                    } else {
                                                        if(isset($_GET['driver'])){
                                                            if($_GET['driver'] != ''){
                                                                $cond.=" and driver_id = '".$_GET['driver']."' ";
                                                            }  
                                                        } 
                                                        if(isset($_GET['unit'])){
                                                            if($_GET['unit'] != ''){
                                                                $cond.=" and unitId = '".$_GET['unit']."' ";
                                                            }  
                                                        }
                                                    }

                                                    $from = $_GET['start'].' 00:00:00.000';
                                                    $to   = $_GET['end'].' 23:59:59.999';

                                                    $query = sqlsrv_query($conn,"SELECT * from dispatch WHERE addedDate BETWEEN '$from' AND '$to' $cond ");

                                                    while ($u= sqlsrv_fetch_array($query)) {
                                                        $d = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM drivers WHERE id = '".$u['driver_id']."' "));
                                                        echo '<tr>
                                                        <td>'.$i++.'</td>
                                                        <td>'.strtoupper($u['tripTicket']).'</td>
                                                        <td>'.strtoupper($d['driver_name']).'</td>
                                                        <td>'.strtoupper($u['type']).'</td>
                                                        <td>'.strtoupper($u['purpose']).'</td>
                                                        <td>'.strtoupper($u['Status']).'</td>
                                                        </tr>';
                                                    }
                                                }
                                                ?>   
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

    <script type="text/javascript" src="metronic/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script> 
    <script src="<?php echo $url;?>js/notifications.js"></script>
    <script src="<?php echo $url;?>js/comments.js"></script>   
</body>
<script>

    jQuery(document).ready(function() {    
Metronic.init(); // init metronic core components
Layout.init(); // init current layout
ComponentsDropdowns.init();

});

    $('.form_date').datetimepicker({
      language:  'en',
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0
    });

</script>
<!-- END BODY -->
</html>