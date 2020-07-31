<?php
include('../config.php');
session_start();

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once '../excel/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('ECS Application')
    ->setLastModifiedBy('ECS Application')
    ->setTitle('ECS Vehicle Request')
    ->setSubject('ECS Vehicle Request')
    ->setDescription('Request List from ECS Vehicle Request Application')
    ->setKeywords('php excel')
    ->setCategory('ECS');
$spreadsheet->setActiveSheetIndex(0);

$row = 1;

$spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $row, "Request ID")
        ->setCellValueByColumnAndRow(2, $row, "Date Needed")
        ->setCellValueByColumnAndRow(3, $row, "Date Requested")
        ->setCellValueByColumnAndRow(4, $row, "Purpose")
        ->setCellValueByColumnAndRow(5, $row, "Status")
        ->setCellValueByColumnAndRow(6, $row, "Status Changed Date")
        ->setCellValueByColumnAndRow(7, $row, "Last Message")
        ->setCellValueByColumnAndRow(8, $row, "Trip Tickets")
        ;


$lq=sqlsrv_query($conn,"select CONVERT(VARCHAR(19),date_needed) as needed,CONVERT(VARCHAR(19),addedAt) as added,CONVERT(VARCHAR(19),lastStatusChanged) as lastchanged,* from vehicle_request order by id desc");
while($l=sqlsrv_fetch_array($lq)){    
    $comments = sqlsrv_fetch_array(sqlsrv_query($conn,"select top 1 * from vehicle_request_comments where request_id='".$l['id']."' order by id desc"));
    $trip_tickets = '';
    $tid = sqlsrv_query($conn,"SELECT tripTicket, isPrinted, Status FROM dispatch WHERE request_id = '".$l['id']."' ");
    while($t = sqlsrv_fetch_array($tid)){
       $trip_tickets.=$t['tripTicket'].',';
    }  
    $row++;
    $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $row, $l['refcode'])
        ->setCellValueByColumnAndRow(2, $row, date('Y-m-d h:i A',strtotime($l['needed'])))
        ->setCellValueByColumnAndRow(3, $row, date('Y-m-d h:i A',strtotime($l['added'])))
        ->setCellValueByColumnAndRow(4, $row, $l['purpose'])
        ->setCellValueByColumnAndRow(5, $row, $l['status'])
        ->setCellValueByColumnAndRow(6, $row, date('Y-m-d h:i A',strtotime($l['lastchanged'])))
        ->setCellValueByColumnAndRow(7, $row, $comments['comment'])
        ->setCellValueByColumnAndRow(8, $row, rtrim($trip_tickets,","))
        ;
}
// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Request List');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="RequestList'.date('YmdHis').'.xlsx"');
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
