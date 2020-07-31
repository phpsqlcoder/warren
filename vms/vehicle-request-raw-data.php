<?php
include("config.php");

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Vehicle Monitoring System</title>
    <link href="google.css" rel="stylesheet" type="text/css" />
    <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->


    <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.css" />
    <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/select2/select2.css" />
    <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css" />


    <!-- BEGIN THEME STYLES -->
    <link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css" />
    <link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css" />
    <link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" />
    <link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css" />

    <link href="metronic/datepicker/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <script src="js/jquery.min.js"></script>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
    <!-- BEGIN HEADER -->
    <?php include("header.php"); ?>
    <div class="clearfix"></div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">
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
                                                    <div class="col-md-3">
                                                        <label class="control-label">From <span class="font-red">*</span></label>
                                                        <?php
                                                        if (isset($_GET['start'])) {
                                                            ?>
                                                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_from" data-link-format="yyyy-mm-dd">
                                                                <div class="input-icon">
                                                                    <i class="fa fa-calendar font-yellow"></i>
                                                                    <input class="form-control" size="16" type="text" value="<?php echo $_GET['start']; ?>" readonly>
                                                                </div>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                                <input type="hidden" name="start" id="date_from" value="<?php echo $_GET['start']; ?>" />
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_from" data-link-format="yyyy-mm-dd">
                                                                <div class="input-icon">
                                                                    <i class="fa fa-calendar font-yellow"></i>
                                                                    <input class="form-control" size="16" type="text" value="" readonly>
                                                                </div>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                                <input type="hidden" name="start" id="date_from" value="" />
                                                            </div>

                                                        <?php }
                                                        ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="control-label">To <span class="font-red">*</span></label>
                                                        <?php
                                                        if (isset($_GET['start'])) {
                                                            ?>
                                                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_to" data-link-format="yyyy-mm-dd">
                                                                <div class="input-icon">
                                                                    <i class="fa fa-calendar font-yellow"></i>
                                                                    <input class="form-control" size="16" type="text" value="<?php echo $_GET['end']; ?>" readonly>
                                                                </div>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                                <input type="hidden" name="end" id="date_to" value="<?php echo $_GET['end']; ?>" />
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_to" data-link-format="yyyy-mm-dd">
                                                                <div class="input-icon">
                                                                    <i class="fa fa-calendar font-yellow"></i>
                                                                    <input class="form-control" size="16" type="text" value="" readonly>
                                                                </div>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                                <input type="hidden" name="end" id="date_to" value="" />
                                                            </div>

                                                        <?php }
                                                        ?>
                                                    </div>
                                                    <div class="col-md-3">
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
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption font-dark">
                                    <i class="fa fa-truck font-dark"></i>
                                    <span class="caption-subject bold uppercase"> Vehicle Request Raw Data</span>
                                </div>
                                <div class="actions">
                                    <ul class="list-inline">
                                        <li>
                                            <form method="POST" action="excel_report.php">
                                                <input type="hidden" name="date_fr" value="<?php echo $_GET['start']; ?>">
                                                <input type="hidden" name="date_to" value="<?php echo $_GET['end']; ?>">
                                                <button class="btn btn-circle blue" name="vehicle_request_raw_data" id="excel-b"><i class="fa fa-file-excel-o"></i> Excel</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th style="font-size:10px;">Request No. </th>
                                                <th style="font-size:10px;">Vehicle Cost Code</th>
                                                <th style="font-size:10px;">Cost Code </th>
                                                <th style="font-size:10px;">Dept </th>
                                                <th style="font-size:10px;">Date Needed </th>
                                                <th style="font-size:10px;">Time Needed </th>
                                                <th style="font-size:10px;">Date Requested </th>
                                                <th style="font-size:10px;">Time Requested </th>
                                                <th style="font-size:10px;">Purpose</th>
                                                <th style="font-size:10px;">Trip Ticket </th>
                                                <th style="font-size:10px;">Status </th>
                                                <th style="font-size:10px;">Vehicle </th>
                                                <th style="font-size:10px;">Fuel Added Qty </th>
                                                <th style="font-size:10px;">Driver </th>
                                                <th style="font-size:10px;">Passengers </th>
                                                <th style="font-size:10px;">Contact Person </th>
                                                <th style="font-size:10px;">Vehicle Date Out </th>
                                                <th style="font-size:10px;">Time Out </th>
                                                <th style="font-size:10px;">Vehicle Date Return </th>
                                                <th style="font-size:10px;">Time Returned </th>
                                                <th style="font-size:10px;">Distance Travelled </th>
                                                <th style="font-size:10px;">Odometer Start </th>
                                                <th style="font-size:10px;">Odometer End </th>
                                                <th style="font-size:10px;">Ave. Fuel Consumed </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($_GET['start'])) {

                                                $from = $_GET['start'] . ' 00:00:00.000';
                                                $to   = $_GET['end'] . ' 23:59:59.999';

                                                $query = sqlsrv_query($conn, "SELECT * FROM v_request_raw_data WHERE dateStart BETWEEN '$from' AND '$to' ORDER BY refcode DESC");

                                                while ($v = sqlsrv_fetch_array($query)) {
                                                    ?>
                                                    <tr style="font-size: 10px;">
                                                        <td style="width:80px;"><?= $v['refcode']; ?></td>
                                                        <td style="width:80px;"><?= $v['vehicle_cost_code']; ?></td>
                                                        <td style="width:80px;"><?= $v['costcode']; ?></td>
                                                        <td style="width:140px;"><?= $v['dept']; ?></td>
                                                        <td style="width:100px;"><?= ($v['date_needed']->format('m/d/Y') == '1970-01-01') ? '' : $v['date_needed']->format('m/d/Y'); ?></td>
                                                        <td style="width:80px;"><?= ($v['date_needed']->format('Y-m-d') == '1970-01-01') ? '' : $v['date_needed']->format('h:i A'); ?></td>
                                                        <td style="width:110px;"><?= $v['addedAt']->format('m/d/Y'); ?></td>
                                                        <td style="width:80px;"><?= $v['addedAt']->format('h:i A'); ?></td>
                                                        <td style="width:400px;"><?= $v['purpose']; ?></td>
                                                        <td style="width:90px;"><?= $v['tripTicket']; ?></td>
                                                        <td style="width:90px;"><?= $v['Status']; ?></td>
                                                        <td><?= $v['type']; ?></td>
                                                        <td><?= ($v['fuel_added_qty'] == '.00') ? '' : round($v['fuel_added_qty']) . ' L'; ?></td>
                                                        <td><?= $v['driver_name']; ?></td>
                                                        <td><?= $v['passengers']; ?></td>
                                                        <td><?= $v['contact_person']; ?></td>
                                                        <td style="width:80px;">
                                                            <?php
                                                                    if ($v['Status'] == 'Cancelled') {
                                                                        echo '<span style="font-size:10px;color:red;">CANCELLED</span>';
                                                                    } else {

                                                                        if ($v['dateStart']->format('m/d/Y') == '01/01/1900') {
                                                                            echo '';
                                                                        }
                                                                    }
                                                                    ?>
                                                        </td>
                                                        <td style="width:70px;">
                                                            <?php
                                                                    if ($v['Status'] == 'Cancelled') {
                                                                        echo '<span style="font-size:10px;color:red;"> N/A</span>';
                                                                    } else {
                                                                        echo $v['dateStart']->format('h:i A');
                                                                    }
                                                                    ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                    if ($v['Status'] == 'Cancelled') {
                                                                        echo '<span style="font-size:10px;color:red;"> N/A</span>';
                                                                    } else {
                                                                        if ($v['dateEnd'] == '') {
                                                                            echo '<span style="font-size:10px;color:red;">NOT YET RETURNED</span>';
                                                                        } else {
                                                                            echo $v['dateEnd']->format('m/d/Y');
                                                                        }
                                                                    }
                                                                    ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                    if ($v['Status'] == 'Cancelled') {
                                                                        echo '<span style="font-size:10px;color:red;"> N/A</span>';
                                                                    } else {
                                                                        if ($v['dateEnd'] == '') {
                                                                            echo '';
                                                                        } else {
                                                                            echo $v['dateEnd']->format('h:i A');
                                                                        }
                                                                    }
                                                                    ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                    if ($v['Status'] == 'Cancelled') {
                                                                        echo '<span style="font-size:10px;color:red;">CANCELLED</span>';
                                                                    } else {
                                                                        $total = ($v['odometer_end'] - $v['odometer_start']);
                                                                        echo number_format((float) $total, 4, '.', '') . ' KM';
                                                                    }
                                                                    ?>
                                                        </td>
                                                        <td style="width:80px;"><?= $v['odometer_start']; ?></td>
                                                        <td style="width:80px;"><?= $v['odometer_end']; ?></td>
                                                        <td>
                                                            <?php
                                                                    if ($v['odometer_start'] == '' || $v['odometer_end'] == '') {
                                                                        echo '';
                                                                    } else {
                                                                        $total1 = ($v['odometer_end'] - $v['odometer_start']);
                                                                        if ($total1 == '0.0000') {
                                                                            echo "";
                                                                        } else {
                                                                            if ($v['fuel_added_qty'] == '0.00') {
                                                                                echo '';
                                                                            } else {
                                                                                $total2 = ($v['odometer_end'] - $v['odometer_start']) / $v['fuel_added_qty'];
                                                                                echo number_format((float) $total2, 4, '.', '') . ' Km/Liter';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                        </td>
                                                    </tr>
                                            <?php }
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


    <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
    <script src="js/excel/src/jquery.table2excel.js"></script>
    <script type="text/javascript" src="metronic/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
</body>
<script>
    $('.form_date').datetimepicker({
        language: 'en',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
</script>
<!-- END BODY -->

</html>