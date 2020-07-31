<?php

// $conn_d['davao']['type'] = 'sqlsrv';
// $conn_d['davao']['host'] = '172.16.10.42\philsaga_db';
// $conn_d['davao']['name'] = 'PMC-DAVAO';
// $conn_d['davao']['uname'] = 'sa';
// $conn_d['davao']['pword'] = '@Temp123!';

//Added by AAG 01/31/2018 for 201 Records of Employees from Agusan
$conn_a['agusan']['type'] = 'sqlsrv';
$conn_a['agusan']['host'] = '172.16.20.42\agusan_db';
$conn_a['agusan']['name'] = 'PMC-AGUSAN-NEW';
$conn_a['agusan']['uname'] = 'sa';
$conn_a['agusan']['pword'] = '@Temp123!';
//$_GET['txt']= strval($_POST['query']);
$_GET['txt']= $_POST['search'];
$result = '';

$empdata=array();
$pdoempdata = new PDO($conn_a['agusan']['type'].":server=".$conn_a['agusan']['host'].";Database=".$conn_a['agusan']['name'], $conn_a['agusan']['uname'], $conn_a['agusan']['pword']);
$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $empdatasql = $pdoempdata->prepare("SELECT e.EmpID, e.FullName,p.positiondesc,d.DeptDesc FROM ViewHREmpMaster e left join hrposition p on p.PositionID=e.PositionID
//  left join hrdepartment d on d.deptid=e.deptid WHERE e.Active = 1 and e.FullName like '%".$_GET['txt']."%' ORDER BY e.FullName ASC");

$empdatasql = $pdoempdata->prepare("select DISTINCT(DeptDesc) from hrdepartment WHERE DeptDesc like '%".$_GET['txt']."%' order by DeptDesc asc");

$empdatasql->execute();
$empdatasql->setFetchMode(PDO::FETCH_ASSOC);
for($i=0; $rowempdata = $empdatasql->fetch(); $i++){   
	//$empdata[]='<option value="'.$rowempdata['EmpID'].'|'.$rowempdata['FullName'].'|DAVAO|'.$rowempdata['positiondesc'].'|'.$rowempdata['DeptDesc'].'">'.$rowempdata['FullName'].'</option>';       
 	$rowempdata['location_sy']='AGUSAN';  
	$empdata[]=$rowempdata['DeptDesc'];

	$result .= '<tr>'.			
			'<td>'.$rowempdata['DeptDesc'].'</td>'.
			'<td><a href="department-user-maintenance.php?dept='.urlencode($rowempdata['DeptDesc']).'" >Select</a></td>';
}

// $pdoempdata = new PDO($conn_d['davao']['type'].":server=".$conn_d['davao']['host'].";Database=".$conn_d['davao']['name'], $conn_d['davao']['uname'], $conn_d['davao']['pword']);
// $pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $empdatasql = $pdoempdata->prepare("select DISTINCT(DeptDesc) from hrdepartment WHERE DeptDesc like '%".$_GET['txt']."%' order by DeptDesc asc");

// $empdatasql->execute();
// $empdatasql->setFetchMode(PDO::FETCH_ASSOC);
// for($i=0; $rowempdata = $empdatasql->fetch(); $i++){     
//   //$empdata[]='<option value="'.$rowempdata['EmpID'].'|'.$rowempdata['FullName'].'|DAVAO|'.$rowempdata['positiondesc'].'|'.$rowempdata['DeptDesc'].'">'.$rowempdata['FullName'].'</option>';
//   	$rowempdata['location_sy']='DAVAO';     
// 	$empdata[]=$rowempdata['DeptDesc'];

// 	$result .= '<tr>'.			
// 			'<td>'.$rowempdata['DeptDesc'].'</td>'.
// 			'<td><a href="department-user-maintenance.php?dept='.urlencode($rowempdata['DeptDesc']).'" >Select</a></td>';
// }

	echo '<table class="table">'.
			'<thead>'.
				'<tr>'.					
					'<th>Department</th>'.
					'<th>Action</th>'.
			'</tr>'.
		'</thead>'.
		'<tbody>'.$result.'</tbody>'.
	'</table>';
?>

