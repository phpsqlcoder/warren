<?php
require_once('metronic/excel/OLEwriter.php');
require_once('metronic/excel/BIFFwriter.php');
require_once('metronic/excel/Worksheet.php');
require_once('metronic/excel/Workbook.php');
require_once('config.php');



function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}

if(isset($_POST['no_dispatches'])) {
	// Creating a workbook
	HeaderingExcel('No. of Dispatches.xls');
	$workbook = new excel("-");

	// Creating the first worksheet
	$worksheet1 =& $workbook->add_worksheet('Total Dispatches');
	$worksheet1->freeze_panes(1, 0);

	$worksheet1->set_column(0, 0, 25);
	$worksheet1->set_column(1, 1, 10);

	$worksheet1->write_string(0,0,"VEHICLE");
	$worksheet1->write_string(0,1,"TOTAL");

	$perDept = sqlsrv_query($conn,"SELECT TOP(10) type,count(type) AS total FROM dispatch WHERE addedDate BETWEEN '".$_POST['date_fr']."' AND '".$_POST['date_to']."' GROUP BY type ORDER BY total DESC");

	$j= 0;

	WHILE ($rows = sqlsrv_fetch_array($perDept)) { 
		$j  = $j+1;

		$type  = $rows['type'];
		$total = $rows['total'];

		$worksheet1->write_string($j,0,"$type");
		$worksheet1->write_string($j,1,"$total");
	}

	$workbook->close();

}


if(isset($_POST['per_dept'])) {
	// Creating a workbook
	HeaderingExcel('Per Department.xls');
	$workbook = new excel("-");

	// Creating the first worksheet
	$worksheet1 =& $workbook->add_worksheet('Dispatches Per Department');
	$worksheet1->freeze_panes(1, 0);

	$worksheet1->set_column(0, 0, 20);
	$worksheet1->set_column(1, 1, 10);

	$worksheet1->write_string(0,0,"DEPARTMENT");
	$worksheet1->write_string(0,1,"TOTAL");


	$perDept = sqlsrv_query($conn,"SELECT deptId,count(deptId) AS total FROM dispatch WHERE addedDate BETWEEN '".$_POST['date_fr']."' AND '".$_POST['date_to']."' GROUP BY deptId ORDER BY total DESC");

	$j= 0;

	WHILE ($rows = sqlsrv_fetch_array($perDept)) { 
		$j  = $j+1;

		$dept  = $rows['deptId'];
		$total = $rows['total'];

		$worksheet1->write_string($j,0,"$dept");
		$worksheet1->write_string($j,1,"$total");
	}

	$workbook->close();

}

if(isset($_POST['distance_travel'])) {
	// Creating a workbook
	HeaderingExcel('Distance Travelled.xls');
	$workbook = new excel("-");

	// Creating the first worksheet
	$worksheet1 =& $workbook->add_worksheet('Vehicle Distance Travelled');
	$worksheet1->freeze_panes(1, 0);

	$worksheet1->set_column(0, 0, 25);
	$worksheet1->set_column(1, 1, 20);
	$worksheet1->set_column(2, 2, 20);
	$worksheet1->set_column(3, 3, 20);

	$worksheet1->write_string(0,0,"VEHICLE");
	$worksheet1->write_string(0,1,"ODOMETER START");
	$worksheet1->write_string(0,2,"ODOMETER END");
	$worksheet1->write_string(0,3,"NO. OF KM");

	$perDept = sqlsrv_query($conn,"SELECT TOP(10) type,odometer_start,odometer_end,odometer_end - odometer_start AS sub FROM dispatch WHERE addedDate BETWEEN '".$_POST['date_fr']."' AND '".$_POST['date_to']."' ORDER BY sub DESC");

	$j= 0;

	WHILE ($rows = sqlsrv_fetch_array($perDept)) { 
		$j  = $j+1;

		$type    = $rows['type'];
		$odom_s  = $rows['odometer_start'];
		$odom_e  = $rows['odometer_end'];
		$total   = $rows['sub'];

		$worksheet1->write_string($j,0,"$type");
		$worksheet1->write_string($j,1,"$odom_s");
		$worksheet1->write_string($j,2,"$odom_e");
		$worksheet1->write_string($j,3,"$total");
	}

	$workbook->close();

}

if(isset($_POST['frequent_dest'])) {
	// Creating a workbook
	HeaderingExcel('Frequent Destination.xls');
	$workbook = new excel("-");

	// Creating the first worksheet
	$worksheet1 =& $workbook->add_worksheet('Frequent Destination');
	$worksheet1->freeze_panes(1, 0);

	$worksheet1->set_column(0, 0, 25);
	$worksheet1->set_column(1, 1, 20);


	$worksheet1->write_string(0,0,"DESTINATION");
	$worksheet1->write_string(0,1,"TOTAL");

	$perDept = sqlsrv_query($conn,"SELECT TOP(10) SUBSTRING(destination,CHARINDEX('-',destination),LEN(destination)) AS dest, COUNT(destination) AS total FROM dispatch WHERE addedDate BETWEEN '".$_POST['date_fr']."' AND '".$_POST['date_to']."' GROUP BY destination ORDER BY total DESC");

	$j= 0;

	WHILE ($rows = sqlsrv_fetch_array($perDept)) { 
		$j  = $j+1;

		$dest   = $rows['dest'];
		$total  = $rows['total'];


		$worksheet1->write_string($j,0,"$dest");
		$worksheet1->write_string($j,1,"$total");
	}

	$workbook->close();

}
?>