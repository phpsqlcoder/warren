<?php

session_start();


require('fpdf.php');



class PDF extends FPDF
{
// Page header
    function Header()
    {
// Logo
        $this->Image('images.jpg',10,6,30);
// Arial bold 15
        $this->SetFont('Times','B',13);
// Move to the right
        $this->Cell(37);
        $this->Cell(60,25,'PHILSAGA MINING CORPORATION',0,1,'C');

        $this->Cell(27);
        $this->SetFont('Courier','',8);
        $this->Cell(60,-15,'Purok 1-A Bayugan, Rosario 3, Agusan Del Sur',0,0,'');
// Line break
        $this->Ln(10);
    }

// Page footer
    function Footer()
    {
        global $conn;
        $user = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT fullname FROM users WHERE domain = '".$_SESSION['esdvms_username']."' "));
        $d    = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT dr.driver_name FROM drivers as dr left join dispatch as di on dr.id = di.driver_id WHERE tripTicket = '".$_GET['id']."' "));
// Position at 1.5 cm from bottom
        $this->SetFont('Times','',10);
        $this->Cell(-95);
        $this->Cell(0,85,'Prepared By : '.$user['fullname'].'          Driver: '.strtoupper($d['driver_name']).'            Approved By: PHIL CARLO DAMASCO',0,1);
    

// Arial italic 8
        $this->SetFont('Arial','I',8);

// Page number
        $this->Cell(0,50,'Page '.$this->PageNo().'/{nb}',0,0,'R');

    }

    function WordWrap(&$text, $maxwidth)
    {
        $text = trim($text);
        if ($text==='')
            return 0;
        $space = $this->GetStringWidth(' ');
        $lines = explode("\n", $text);
        $text = '';
        $count = 0;

        foreach ($lines as $line)
        {
            $words = preg_split('/ +/', $line);
            $width = 0;

            foreach ($words as $word)
            {
                $wordwidth = $this->GetStringWidth($word);
                if ($wordwidth > $maxwidth)
                {
// Word is too long, we cut it
                    for($i=0; $i<strlen($word); $i++)
                    {
                        $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                        if($width + $wordwidth <= $maxwidth)
                        {
                            $width += $wordwidth;
                            $text .= substr($word, $i, 1);
                        }
                        else
                        {
                            $width = $wordwidth;
                            $text = rtrim($text)."\n".substr($word, $i, 1);
                            $count++;
                        }
                    }
                }
                elseif($width + $wordwidth <= $maxwidth)
                {
                    $width += $wordwidth + $space;
                    $text .= $word.' ';
                }
                else
                {
                    $width = $wordwidth + $space;
                    $text = rtrim($text)."\n".$word.' ';
                    $count++;
                }
            }
            $text = rtrim($text)."\n";
            $count++;
        }
        $text = rtrim($text);
        return $count;
    }

    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$start_x=$pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$cell_width = 95;  //define cell width
$cell_height =7;    //define cell height


$pdf->SetLineWidth(0.10);
$pdf->Cell(40);
$pdf->Line(2, 30,208,30);

$pdf->SetFont('Times','B',13);
$pdf->Cell(10);
$pdf->Cell(0,1,'TRIP TICKET FORM & FUEL SLIP FORM',0,1);

include('../config.php');

$r = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT di.*, dr.driver_name FROM dispatch as di left join drivers as dr on di.driver_id = dr.id WHERE tripTicket = '".$_GET['id']."' "));

if($r['dateEnd'] == NULL) {
    $return_date = '';
} else {
    $return_date = $r['dateEnd']->format('Y-m-d h:i A');
}


$c = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM vehicle_request WHERE id = '".$r['request_id']."' "));

if($c['date_needed'] == NULL) {
    $date_needed = '';
} else {
    $date_needed = $c['date_needed']->format('Y-m-d h:i:s A');
}

$user = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT fullname FROM users WHERE domain = '".$_SESSION['esdvms_username']."' "));

$p = sqlsrv_query($conn, "UPDATE dispatch SET isPrinted = 1 WHERE tripTicket = '".$r['tripTicket']."' ");


$pdf->SetFont('Times','',10);
$pdf->Cell(0,15,'Trip Ticket No : '.$r['tripTicket'],0,1,'C');



$width_cell=array(110,83);

$pdf->SetFont('Times','',11);

$start_x=$pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$cell_width = 95;  //define cell width
$cell_height= 6;    //define cell height

$pdf->Cell(15,0,'Reference Code:',0,1);

$pdf->MultiCell($cell_width,$cell_height,$c['refcode'],0,1); 
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

$pdf->MultiCell($cell_width,$cell_height,'Date Needed : '.$date_needed,0,1); 
$current_x+=$cell_width;                           
$pdf->SetXY($current_x, $current_y);

########################################

$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height; 

$pdf->MultiCell($cell_width,$cell_height,'Driver : '.strtoupper($r['driver_name']),0,1); 
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

$pdf->MultiCell($cell_width,$cell_height,'Date Out : '.$r['dateStart']->format('Y-m-d h:i:s A'),0,1); 
$current_x+=$cell_width;                           
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->MultiCell($cell_width,$cell_height,'Destination : '.strtoupper(str_replace('-','  -  ',$r['destination'])),0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

$pdf->MultiCell($cell_width,$cell_height,'Vehicle : '.$r['type'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height; 

$pdf->MultiCell(190,8,'Passengers : '.ucfirst(str_replace('|','   *   ',$r['passengers'])),0,1); 
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->MultiCell(190,10,'',0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->MultiCell(190,1,'Purpose : '.strtoupper($r['purpose']),0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->MultiCell(190,$cell_height,'',0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','B',12);
$pdf->SetXY(10,30);
$pdf->Cell(0,150,'RETURN SLIP FORM',0,1,'C');

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',11);
$pdf->Cell(15);
$pdf->MultiCell($cell_width,5,'Return Date & Time : '.$return_date,0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

$pdf->Cell(15);
$pdf->MultiCell($cell_width,5,'Odometer Start : '.$r['odometer_start'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',11);
$pdf->MultiCell($cell_width,1,'',0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

$pdf->Cell(15);
$pdf->MultiCell($cell_width,5,'Odometer End : '.$r['odometer_end'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);
$pdf->SetFont('Times','B',12);
$pdf->MultiCell(180,27,'                                                                       FUEL SLIP FORM',0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',11);

$pdf->Cell(15);
$pdf->MultiCell($cell_width,30,'Cost Code : '.$c['costcode'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

$pdf->Cell(15);
$pdf->MultiCell($cell_width,30,'RQ Number : '.$r['RQ'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',11);

$pdf->Cell(15);
$pdf->MultiCell($cell_width,30,'Fuel Type : '.$r['fuel_added_type'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

$pdf->Cell(15);
$pdf->MultiCell($cell_width,30,'Item Code : '.$r['itemCode'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

########################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',11);

$pdf->Cell(15);
$pdf->MultiCell($cell_width,30,'Qty : '.$r['fuel_added_qty'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

$pdf->Cell(15);
$pdf->MultiCell($cell_width,30,'UOM : '.$r['uom'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

###############################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',11);

$pdf->Cell(35);
$pdf->MultiCell(190,70,'                                                                                 '.$user['fullname'],0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

###############################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',11);

$pdf->Cell(35);
$pdf->MultiCell(190,57,'______________________                               ______________________',0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

###############################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',11);

$pdf->Cell(40);
$pdf->MultiCell(190,55,'Issued By (MCD)                                             Received By (Driver)',0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

###############################################
$pdf->Ln();
$current_x=$start_x;                      
$current_y+=$cell_height;                  

$pdf->SetXY($current_x, $current_y);

$pdf->SetFont('Times','',9);

$pdf->MultiCell(250,96,'DATE : _________________________             DATE: _____________________           DATE: ________________________________',0,1);
$current_x+=$cell_width;
$pdf->SetXY($current_x, $current_y);

###############################################

$pdf->SetLineWidth(0.5);
$pdf->SetDash(2,2);
$pdf->Rect(10,100,190,25);

$pdf->SetLineWidth(0.5);
$pdf->SetDash(2,2);
$pdf->Rect(10,130,190,60);

$pdf->Output();
?>