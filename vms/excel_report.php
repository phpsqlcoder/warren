<?php
include('config.php');
session_start();

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once 'excel/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
/*$spreadsheet->getProperties()->setCreator('ECS Application')
->setLastModifiedBy('ECS Application')
->setTitle('ECS Vehicle Request')
->setSubject('ECS Vehicle Request')
->setDescription('Request List from ECS Vehicle Request Application')
->setKeywords('php excel')
->setCategory('ECS');*/
$spreadsheet->setActiveSheetIndex(0);

$row = 1;

if (isset($_POST['per_dept'])) {
    $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $row, "Departments")
        ->setCellValueByColumnAndRow(2, $row, "Total");

    $dateFrom = $_POST['date_fr'] . ' 00:00:00.000';
    $dateto   = $_POST['date_to'] . ' 23:59:59.999';

    $perDept = sqlsrv_query($conn, "SELECT top(10) deptId,count(deptId) AS total FROM dispatch WHERE addedDate BETWEEN '" . $dateFrom . "' AND '" . $dateto . "' GROUP BY deptId ORDER BY total DESC");

    while ($pd = sqlsrv_fetch_array($perDept)) {

        $row++;
        $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow(1, $row, $pd['deptId'])
            ->setCellValueByColumnAndRow(2, $row, $pd['total']);
    }
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Dispatches per Department');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Dispatches per Department ' . date('Y-m-d H:i:s') . '.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');

    exit;
}

if (isset($_POST['no_dispatches'])) {
    $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $row, "Vehicle")
        ->setCellValueByColumnAndRow(2, $row, "Total");

    $dateFrom = $_POST['date_fr'] . ' 00:00:00.000';
    $dateTo   = $_POST['date_to'] . ' 23:59:59.999';

    $noDispatches = sqlsrv_query($conn, "SELECT TOP(10) type,count(type) AS total FROM dispatch WHERE addedDate BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "' GROUP BY type ORDER BY total DESC");

    while ($nd = sqlsrv_fetch_array($noDispatches)) {

        $row++;
        $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow(1, $row, $nd['type'])
            ->setCellValueByColumnAndRow(2, $row, $nd['total']);
    }
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Dispatches per Department');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Total Dispatches / Vehicle ' . date('Y-m-d H:i:s') . '.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');

    exit;
}

if (isset($_POST['distance_travel'])) {
    $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $row, "Vehicle")
        ->setCellValueByColumnAndRow(2, $row, "Odometer Start")
        ->setCellValueByColumnAndRow(3, $row, "Odometer End")
        ->setCellValueByColumnAndRow(4, $row, "No. of KM");

    $dateFrom = $_POST['date_fr'] . ' 00:00:00.000';
    $dateTo   = $_POST['date_to'] . ' 23:59:59.999';

    $distanceTravel = sqlsrv_query($conn, "SELECT TOP(10) type,odometer_start,odometer_end,odometer_end - odometer_start AS sub FROM dispatch WHERE addedDate BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "' ORDER BY sub DESC");

    while ($dt = sqlsrv_fetch_array($distanceTravel)) {

        $row++;
        $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow(1, $row, $dt['type'])
            ->setCellValueByColumnAndRow(2, $row, $dt['odometer_start'])
            ->setCellValueByColumnAndRow(3, $row, $dt['odometer_end'])
            ->setCellValueByColumnAndRow(4, $row, $dt['sub'] . ' KM');
    }
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Vehicle Distance Traveled');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Vehicle Distance Traveled ' . date('Y-m-d H:i:s') . '.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');

    exit;
}


if (isset($_POST['frequent_dest'])) {
    $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $row, "Destination")
        ->setCellValueByColumnAndRow(2, $row, "Total");

    $dateFrom = $_POST['date_fr'] . ' 00:00:00.000';
    $dateTo   = $_POST['date_to'] . ' 23:59:59.999';

    $destination = sqlsrv_query($conn, "SELECT TOP(10) SUBSTRING(destination,CHARINDEX('|',destination),LEN(destination)) AS dest, COUNT(destination) AS total FROM dispatch WHERE addedDate BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "' GROUP BY destination ORDER BY total DESC");

    while ($fd = sqlsrv_fetch_array($destination)) {

        $row++;
        $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow(1, $row, $fd['dest'])
            ->setCellValueByColumnAndRow(2, $row, $fd['total']);
    }
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Frequent Destination');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Frequent Destination ' . date('Y-m-d H:i:s') . '.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');

    exit;
}



if (isset($_POST['trip_tickets'])) {

    $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $row, "Ticket #")
        ->setCellValueByColumnAndRow(2, $row, "Driver")
        ->setCellValueByColumnAndRow(3, $row, "Vehicle")
        ->setCellValueByColumnAndRow(4, $row, "Purpose")
        ->setCellValueByColumnAndRow(5, $row, "Status");

    $from   = $_POST['date_from'] . ' 00:00:00.000';
    $to     = $_POST['date_to'] . ' 23:59:59.999';
    $driver = $_POST['driver'];
    $unit   = $_POST['unit'];

    if ($_POST['driver'] == '' && $_POST['unit'] == '') {

        $query = sqlsrv_query($conn, "SELECT * from dispatch WHERE addedDate BETWEEN '" . $from . "' AND '" . $to . "' ");
    } else {

        if ($_POST['driver'] > 0 && $_POST['unit'] > 0) {

            $query = sqlsrv_query($conn, "SELECT tripTicket, driver_id, type, purpose,Status, unitId FROM dispatch WHERE driver_id = '" . $driver . "' AND unitId = '" . $unit . "' AND addedDate BETWEEN '" . $from . "' AND '" . $to . "' ");
        } elseif ($_POST['driver'] > 0) {

            $query = sqlsrv_query($conn, "SELECT * from dispatch WHERE driver_id = '" . $driver . "' and addedDate BETWEEN '" . $from . "' AND '" . $to . "' ");
        } elseif ($_POST['unit'] > 0) {

            $query = sqlsrv_query($conn, "SELECT * from dispatch WHERE unitId = '" . $unit . "' and addedDate BETWEEN '" . $from . "' AND '" . $to . "' ");
        }
    }


    while ($u = sqlsrv_fetch_array($query)) {

        $d = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT * FROM drivers WHERE id = '" . $u['driver_id'] . "' "));

        $row++;
        $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow(1, $row, $u['tripTicket'])
            ->setCellValueByColumnAndRow(2, $row, $d['driver_name'])
            ->setCellValueByColumnAndRow(3, $row, $u['type'])
            ->setCellValueByColumnAndRow(4, $row, $u['purpose'])
            ->setCellValueByColumnAndRow(5, $row, $u['Status']);
    }



    $spreadsheet->getActiveSheet()->setTitle('Trip Tickets');

    $spreadsheet->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Trip Tickets ' . date('Y-m-d H:i:s') . '.xlsx"');
    header('Cache-Control: max-age=0');

    header('Cache-Control: max-age=1');


    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');

    exit;
}



if (isset($_POST['vehicle_request_raw_data'])) {

    $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $row, "Request #")
        ->setCellValueByColumnAndRow(2, $row, "Vehicle Cost Code")
        ->setCellValueByColumnAndRow(3, $row, "Cost Code")
        ->setCellValueByColumnAndRow(4, $row, "Dept")
        ->setCellValueByColumnAndRow(5, $row, "Date Needed")
        ->setCellValueByColumnAndRow(6, $row, "Time Needed")
        ->setCellValueByColumnAndRow(7, $row, "Date Requested")
        ->setCellValueByColumnAndRow(8, $row, "Time Requested")
        ->setCellValueByColumnAndRow(9, $row, "Purpose")
        ->setCellValueByColumnAndRow(10, $row, "Trip Ticket")
        ->setCellValueByColumnAndRow(11, $row, "Status")
        ->setCellValueByColumnAndRow(12, $row, "Vehicle")
        ->setCellValueByColumnAndRow(13, $row, "Fuel Added Qty")
        ->setCellValueByColumnAndRow(14, $row, "Driver")
        ->setCellValueByColumnAndRow(15, $row, "Passengers")
        ->setCellValueByColumnAndRow(16, $row, "Contact Person")
        ->setCellValueByColumnAndRow(17, $row, "Vehicle Date Out")
        ->setCellValueByColumnAndRow(18, $row, "Time Out")
        ->setCellValueByColumnAndRow(19, $row, "Vehicle Date Return")
        ->setCellValueByColumnAndRow(20, $row, "Time Returned")
        ->setCellValueByColumnAndRow(21, $row, "Distance Travelled")
        ->setCellValueByColumnAndRow(22, $row, "Odometer Start")
        ->setCellValueByColumnAndRow(23, $row, "Odometer End")
        ->setCellValueByColumnAndRow(24, $row, "Ave. Fuel Consumed");

    $from = $_POST['date_fr'] . ' 00:00:00.000';
    $to   = $_POST['date_to'] . ' 23:59:59.999';


    $lq = sqlsrv_query($conn, "SELECT * FROM v_request_raw_data WHERE dateStart BETWEEN '" . $from . "' AND '" . $to . "' ORDER BY refcode DESC");

    while ($v = sqlsrv_fetch_array($lq)) {

        // Vehicle Date Out
        if ($v['Status'] == 'Cancelled') {
            $dateOut = 'CANCELLED';
        } else {
            // $dateOut = $v['dateStart']->format('m/d/Y');
            if ($v['dateStart']->format('m/d/Y') == '01/01/1900') {
                $dateOut = '';
            } else {
                $dateOut = $v['dateStart']->format('m/d/Y');
            }
        }

        if ($v['Status'] == 'Cancelled') {
            $timeOut = 'N/A';
        } else {
            $timeOut = $v['dateStart']->format('h:i A');
        }
        // end

        // Vehicle Date Return
        if ($v['Status'] == 'Cancelled') {
            $dateReturn = 'N/A';
        } else {
            if ($v['dateEnd'] == '') {
                $dateReturn = 'NOT YET RETURNED';
            } else {
                $dateReturn = $v['dateEnd']->format('m/d/Y');
            }
        }

        if ($v['Status'] == 'Cancelled') {
            $timeReturn = 'N/A';
        } else {
            if ($v['dateEnd'] == '') {
                $timeReturn = '';
            } else {
                $timeReturn = $v['dateEnd']->format('h:i A');
            }
        }
        // end

        // Vehicle Distance Travelled
        if ($v['Status'] == 'Cancelled') {
            $distance = 'CANCELLED';
        } else {
            $total = ($v['odometer_end'] - $v['odometer_start']);
            $distance = number_format((float) $total, 4, '.', '');
        }
        // end

        // Average Fuel Consumed
        if ($v['odometer_start'] == '' || $v['odometer_end'] == '') {
            $average = '';
        } else {
            $total1 = ($v['odometer_end'] - $v['odometer_start']);
            if ($total1 == '0.0000') {
                $average = "";
            } else {
                if ($v['fuel_added_qty'] == '0.00') {
                    $average = '';
                } else {
                    $total2 = ($v['odometer_end'] - $v['odometer_start']) / $v['fuel_added_qty'];
                    $average = number_format((float) $total2, 4, '.', '') . ' Km/Liter';
                }
            }
        }
        // end

        $fuel = ($v['fuel_added_qty'] == '0.00') ? '' : round($v['fuel_added_qty']);

        $row++;
        $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow(1, $row, $v['refcode'])
            ->setCellValueByColumnAndRow(2, $row, $v['vehicle_cost_code'])
            ->setCellValueByColumnAndRow(3, $row, $v['costcode'])
            ->setCellValueByColumnAndRow(4, $row, $v['dept'])
            ->setCellValueByColumnAndRow(5, $row, $v['date_needed']->format('m/d/Y'))
            ->setCellValueByColumnAndRow(6, $row, $v['date_needed']->format('h:i A'))
            ->setCellValueByColumnAndRow(7, $row, $v['addedAt']->format('m/d/Y'))
            ->setCellValueByColumnAndRow(8, $row, $v['addedAt']->format('h:i A'))
            ->setCellValueByColumnAndRow(9, $row, $v['purpose'])
            ->setCellValueByColumnAndRow(10, $row, $v['tripTicket'])
            ->setCellValueByColumnAndRow(11, $row, $v['Status'])
            ->setCellValueByColumnAndRow(12, $row, $v['type'])
            ->setCellValueByColumnAndRow(13, $row, $fuel)
            ->setCellValueByColumnAndRow(14, $row, $v['driver_name'])
            ->setCellValueByColumnAndRow(15, $row, $v['passengers'])
            ->setCellValueByColumnAndRow(16, $row, $v['contact_person'])
            ->setCellValueByColumnAndRow(17, $row, $dateOut)
            ->setCellValueByColumnAndRow(18, $row, $timeOut)
            ->setCellValueByColumnAndRow(19, $row, $dateReturn)
            ->setCellValueByColumnAndRow(20, $row, $timeReturn)
            ->setCellValueByColumnAndRow(21, $row, $distance)
            ->setCellValueByColumnAndRow(22, $row, $v['odometer_start'])
            ->setCellValueByColumnAndRow(23, $row, $v['odometer_end'])
            ->setCellValueByColumnAndRow(24, $row, $average);
    }
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Vehicle Request List');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="VehicleRequestList' . date('YmdHis') . '.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
}
