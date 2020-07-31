<?php
$serverName = "172.16.20.42\AGUSAN_DB";
$connectionInfo = array( "Database"=>"PMC-AGUSAN-NEW", "UID"=>"sa", "PWD"=>"@Temp123!" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$data='
<table width="100%" id="JundrieGwapo" style="font-family:Arial;font-size:10px;">
    <thead>
        <tr>
           <td>EmpID</td>
           <td>Name</td>
           <td>Dept</td>
           <td>Period</td>
           <td>Year</td>
           <td>Type</td>
           <td>Monthly</td>
           <td>Daily</td>
           <td>Status</td>
           <td>TaxID</td>
           <td>RegHrs</td>
           <td>RegPay</td>
           <td>LateHrs</td>
           <td>LatePay</td>
           <td>UTHrs</td>
           <td>UTPay</td>
           <td>AbsentHrs</td>
           <td>AbsentPay</td>
           <td>OTHrs</td>
           <td>OTPay</td>
           <td>NDHrs</td>
           <td>NDPay</td>
           <td>Total OE</td>
           <td>Total OD</td>
           <td>Loan</td>
           <td>Taxable Income</td>
           <td>WTax</td>
           <td>SSSEE</td>
           <td>SSSER</td>
           <td>PhilhEE</td>
           <td>PhilhER</td>
           <td>HDMFEE</td>
           <td>HDMFER</td>
           <td>Gross</td>
           <td>Total Deductions</td>
           <td>NetPay</td>';
            for($x=1;$x<=50;$x++) {
            $data.= '<td>OE'.$x.'</td>';
            } 
            for($x=1;$x<=50;$x++) {
            $data.=  '<td>OD'.$x.'</td>';
            } 
           
        $data.= '</tr>
    </thead>
    <tbody>';
      
        $s = sqlsrv_query($conn,"select * from PRSummaryH where PRYear='2018' and PeriodID in (
      'S01','S02','S03','S04','S05','S06','S07','S08','S09','S10','S11','S12'
      )");
        while($p = sqlsrv_fetch_object($s)){
          $e = sqlsrv_fetch_object(sqlsrv_query($conn,"select e.*,d.DeptDesc from viewhrempmaster e left join hrdepartment d on d.deptid=e.deptid where e.empid='".$p->EmpID."'"));
        $data.= '<tr>
                <td>'.$p->EmpID.'</td>
               <td>'.$e->FullName.'</td>
               <td>'.$e->DeptDesc.'</td>
               <td>'.$p->PeriodID.'</td>
               <td>'.$p->PRYear.'</td>
               <td>'.$p->PayrollRate.'</td>
               <td>'.$p->MonthlyRate.'</td>
               <td>'.$p->DailyRate.'</td>
               <td>'.$p->EmpStatus.'</td>
               <td>'.$p->TaxID.'</td>
               <td>'.$p->RegHrs.'</td>
               <td>'.$p->RegPay.'</td>
               <td>'.$p->LateHrs.'</td>
               <td>'.$p->LatePay.'</td>
               <td>'.$p->UTHrs.'</td>
               <td>'.$p->UTPay.'</td>
               <td>'.$p->AbsentHrs.'</td>
               <td>'.$p->AbsentPay.'</td>
               <td>'.$p->TotalOTHrs.'</td>
               <td>'.$p->TotalOTPay.'</td>
               <td>'.$p->NDHrs.'</td>
               <td>'.$p->NDPay.'</td>
               <td>'.$p->OtherEarn.'</td>
               <td>'.$p->OtherDeduct.'</td>
               <td>'.$p->LoanDeduct.'</td>
               <td>'.$p->TaxableIncome.'</td>
               <td>'.$p->WTax.'</td>
               <td>'.$p->SSSEE.'</td>
               <td>'.$p->SSSER.'</td>
               <td>'.$p->PhilHEE.'</td>
               <td>'.$p->PhilHER.'</td>
               <td>'.$p->PAGIBIGEE.'</td>
               <td>'.$p->PAGIBIGER.'</td>
               <td>'.$p->GrossPay.'</td>
               <td>'.$p->TotalDeduction.'</td>
               <td>'.$p->NetPay.'</td>
            ';
            $xx = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from PRSummaryExtH where empid='".$p->EmpID."' and pryear='".$p->PRYear."' and periodid='".$p->PeriodID."'"));
            for($x=1;$x<=50;$x++){
                $v = 'OE'.$x;
                if(strlen($x)==1){
                    $v = 'OE0'.$x;
                }
                
                $data.= '<td>'.$xx[$v].'</td>';    
            }
            for($x=1;$x<=50;$x++){
                    $v = 'OD'.$x;
                    if(strlen($x)==1){
                        $v = 'OD0'.$x;
                    }
                    
                    $data.= '<td>'.$xx[$v].'</td>';    
                }
            $data.= '</tr>';
           
          }
          
    $data.='</tbody>
  </table>';
  echo $data;