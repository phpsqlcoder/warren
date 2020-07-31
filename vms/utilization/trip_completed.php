<?php
include("../config.php");
session_start();

$r = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT * FROM dispatch WHERE tripTicket = '" . $_GET['id'] . "' "));
$v = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT * FROM vehicle_request WHERE id = '" . $r['request_id'] . "' "));
$u = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT name FROM unit WHERE id = '" . $r['unitId'] . "' "));
$d = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT driver_name FROM drivers WHERE id = '" . $r['driver_id'] . "' "));

$dest = explode('|', $r['destination']);
$from = $dest[0];
$to   = $dest[1];


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>Vehicle Monitoring System</title>
	<link href="google.css" rel="stylesheet" type="text/css" />
	<link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
	<link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
	<link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->


	<link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.css" />
	<link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css" />


	<!-- BEGIN THEME STYLES -->
	<link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css" />
	<link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css" />
	<link id="style_color" href="../metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" />
	<link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css" />

	<link href="../metronic/datepicker/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

	<script src="../js/jquery.min.js"></script>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
	<!-- BEGIN HEADER -->
	<?php include("../header.php"); ?>
	<div class="clearfix"></div>
	<!-- BEGIN CONTAINER -->
	<div class="page-container">
		<!-- BEGIN CONTENT -->
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<div class="breadcrumbs">
							<h3><i class="fa fa-truck"></i> TRIP DETAILS</h3>
							<ol class="breadcrumb">
								<li>
									<a href="../home.php"><i class="fa fa-home"></i> HOME</a>
								</li>
								<li>
									<a href="request_list.php"><i class="fa fa-list"></i> REQUEST LIST</a>
								</li>
								<li class="active"><i class="fa fa-tags"></i> TRIP DETAILS</li>
							</ol>
							<a style="float: right;" class="btn yellow" href="dispatch_printout.php?id=<?php echo urlencode($_GET['id']); ?> " target="_blank">
								<i class="fa fa-print"></i> Print
							</a>
							<br><br>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<!-- BEGIN EXAMPLE TABLE PORTLET-->
								<div class="portlet light bordered">
									<div class="portlet-title">
										<div class="caption font-dark">
											<i class="fa fa-truck font-dark"></i>
											<span class="caption-subject bold uppercase"> Status : Completed <?php echo $to;  ?></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 blog-tag-data">
											<h3>Trip Ticket - <?php echo $_GET['id']; ?></h3>

											<div class="row">
												<div class="col-md-8">
													<ul class="list-inline blog-tags">
														<li style="font-size: 15px;font-style: italic;">
															<i class="fa fa-tags"></i>
															<a href="#">
																<?= $v['name']; ?> </a>/
															<a href="#">
																<?= $v['dept']; ?> </a>

														</li>
													</ul>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="news-item-page">
														<table style="width:100%;font-size:17px;">
															<tr>
																<td width="200px;">Date Needed: </td>
																<td><?php echo $v['date_needed']->format('Y-m-d h:i:s A'); ?></td>

															</tr>
															<tr>
																<td>Purpose: </td>
																<td>&nbsp;
																	<blockquote class="hero">
																		<p><?php echo $r['purpose']; ?></p>
																	</blockquote>
																</td>
															</tr>
														</table>

														<h4>Trip Summary:</h4>
														<table style="font-size: 15px;" class="table table-condensed">
															<tr>
																<td width="190px;">Date Out : </td>
																<td><?= $r['dateStart']->format('Y-m-d h:i:s A'); ?></td>
															</tr>
															<tr>
																<td width="190px;">Vehicle :</td>
																<td><?= $u['name']; ?></td>
															</tr>
															<tr>
																<td width="190px;">Driver :</td>
																<td><?= $d['driver_name']; ?></td>
															</tr>
															<tr>
																<td width="190px;">Destination :</td>
																<td><?= '<b>From:</b>' . $from . '&nbsp;&nbsp;<b>To:</b>' . $to; ?></td>
															</tr>
															<tr>
																<td width="190px;">Purpose :</td>
																<td><?= $r['purpose']; ?></td>
															</tr>
															<tr>
																<td width="190px;">Passengers :</td>
																<td><?= $r['passengers']; ?></td>
															</tr>
														</table>
													</div>
												</div>
											</div>

											<hr>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<!-- BEGIN EXAMPLE TABLE PORTLET-->
								<div class="portlet light bordered">
									<div class="portlet-title">
										<div class="caption font-dark">
											<i class="fa fa-truck font-dark"></i>
											<span class="caption-subject bold uppercase"> Return & Fuel Slip Details TESTING</span>
										</div>
									</div>
									<div class="portlet-body">
										<table style="font-size: 17px;" class="table table-condensed">
											<tr>
												<td width="190px;"><strong><i class="fa fa-truck"></i> Return Trip Details</strong> </td>
												<td></td>
											</tr>
											<tr>
												<td width="190px;">Return Date & Time :</td>
												<td><?php if ($r['dateEnd'] == NULL) {
														echo '';
													} else {
														echo $r['dateEnd']->format('F d, Y h:i:s A');
													} ?>
												</td>
											</tr>
											<tr>
												<td width="190px;">Odometer Start :</td>
												<td><?= $r['odometer_start']; ?></td>
											</tr>
											<tr>
												<td width="190px;">Odometer End :</td>
												<td><?= $r['odometer_end']; ?></td>
											</tr>
											<tr>
												<td width="190px;">Distance Travelled :</td>
												<td><?= $r['odometer_end'] - $r['odometer_start'] . ' KM'; ?></td>
											</tr>
											<tr>
												<td width="190px;"><i class="fa fa-fire"></i> <strong>Fuel Details</strong></td>
												<td></td>
											</tr>
											<tr>
												<td width="190px;">Request Cost Code :</td>
												<td><?= $v['costcode']; ?></td>
											</tr>
											<tr>
												<td width="190px;">Vehicle Cost Code :</td>
												<td><?= $r['vehicle_cost_code']; ?></td>
											</tr>
											<tr>
												<td width="190px;">RQ # :</td>
												<td><?= $r['RQ']; ?></td>
											</tr>
											<tr>
												<td width="190px;">Fuel Type :</td>
												<td><?= $r['fuel_added_type']; ?></td>
											</tr>
											<tr>
												<td width="190px;">Item Code :</td>
												<td><?= $r['itemCode']; ?></td>
											</tr>
											<tr>
												<td width="190px;">Requested Qty :</td>
												<td><?= $r['fuel_requested_qty'] . ' ' . $r['uom']; ?></td>
											</tr>
											<tr>
												<td width="190px;">Actual Fuel Qty :</td>
												<td><?= $r['fuel_added_qty'] . ' ' . $r['uom']; ?></td>
											</tr>
											<tr>
												<td width="190px;">Average Fuel Consumed :</td>
												<td><?php $total = ($r['odometer_end'] - $r['odometer_start']) / $r['fuel_added_qty'];
													echo number_format((float) $total, 4, '.', '') . ' Km per Liter';
													?>
												</td>

											</tr>
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

	<script src="../metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>

	<script type="text/javascript" src="../metronic/assets/global/plugins/select2/select2.min.js"></script>
	<script type="text/javascript" src="../metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
	<script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
	<script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
	<script type="text/javascript" src="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>


	<script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
	<script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
	<script src="../metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
	<script src="../js/excel/src/jquery.table2excel.js"></script>
	<script type="text/javascript" src="../metronic/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
</body>
<script>
	jQuery(document).ready(function() {
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		ComponentsDropdowns.init();

	});
</script>
<!-- END BODY -->

</html>