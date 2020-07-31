<?php
include("../config.php");

// $empdata='';
$mydropdown='';
$mytextbox='';
$row='';

$serverNameAgusan = "172.16.20.42\agusan_db";
$connectionInfoAgusan = array( "Database"=>"PMC-AGUSAN-NEW", "UID"=>"sa", "PWD"=>"@Temp123!" );
$connAgusan = sqlsrv_connect( $serverNameAgusan, $connectionInfoAgusan);

session_start();
$url = "unit.php";
$msg = '';
if(!$_SESSION['esdvms_username']){
	header("location:../login.php");
}


$agusandept_option = "<option value=''> - Select Department -</option>";
$agusandept_qry = sqlsrv_query($connAgusan,"SELECT DeptDesc FROM HRDepartment ORDER BY DeptDesc ASC");
while($agusandept = sqlsrv_fetch_array($agusandept_qry)){
	$agusandept_option.="<option value='".$agusandept['DeptDesc']."'>".$agusandept['DeptDesc']."</option>";
}

$mechanic_options = "<option value=''> - Select -</option>";
$mechanic_qry = sqlsrv_query($conn,"select * from mechanics");
while($mechanics = sqlsrv_fetch_array($mechanic_qry)){
	$mechanic_options.="<option value='".$mechanics['name']."'>".$mechanics['name']."</option>";
}

if(isset($_GET['act'])){
	if($_GET['act']=='save'){
        		
		$department = ($_POST['check'] == 1) ? $_POST['department'] : $_POST['departmanual'];
		$vcode      = $_POST['vehicle_code'].'.'.$_POST['dept_code'].'.158';

		$insert = sqlsrv_query($conn,"insert into unit (name,type,required_availability_hours,active,dept,model,plateno,chassisno,engineno,color,vehicle_code ) VALUES ('".htmlentities($_POST['name'])."','".$_POST['unit_type']."','".$_POST['required_availability_hours']."','1','".$department."','".$_POST['model']."','".$_POST['plateno']."','".$_POST['chassisno']."','".$_POST['engineno']."','".$_POST['color']."','".$vcode."')		
		");

		header("location:".$url."?msg=SAVED");
	}

	if($_GET['act']=='edit'){

		$e = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from unit where id='".$_GET['id']."'"));

		$vcode = explode('.',$e['vehicle_code']);

		$depart = sqlsrv_query($connAgusan,"SELECT DeptDesc FROM HRDepartment WHERE DeptDesc ='".$e['dept']."'");
		$result = sqlsrv_fetch_array($depart); 
		$result_value=	"<option value='".$result['DeptDesc']."' selected>".$result['DeptDesc']."</option>";		
		$row = sqlsrv_has_rows($depart);
			if ($row==true)  {
	      		// echo '<BR \> FOUND ON HRIS '.$result['DeptDesc']; 
	      		$mydropdown ='<div class="col-md-4">	
																<select onchange="disableTextbox()" class="form-control input-large select2me" name="department" id="department" data-placeholder="Select Department">   
																			             											
																          '.$result_value.';
																          '.$agusandept_option.';                                                                                    
																         
																 </select> 
														        <input type="hidden" name="check" id="check">
															</div>
															<div class="col-md-5">													
																	<input onblur="disableDropdown()" class="form-control" value="" type="text" size="16" name="departmanual" id="departmanual" class="form-control" disabled>						
															</div>';
	      	}
	      	else
	      	{
	      		// echo '<BR \> NOT FOUND ON HRIS '.$e['dept'];
	      		$mytextbox='
	      		<div class="col-md-4">	
																<select onchange="disableTextbox()" class="form-control input-large select2me" name="department" id="department" data-placeholder="Select Department" disabled>   
																          '.$agusandept_option.';                                                                                    
																 </select> 
														        
														        <input type="hidden" name="check" id="check">
															</div>
															<div class="col-md-5">													
																	<input onblur="disableDropdown()" class="form-control" value="'.$e["dept"].'" type="text" size="16" name="departmanual" id="departmanual" class="form-control">						
															</div>
	      		';
	      	}

	}

	if($_GET['act']=='update'){

		$department = ($_POST['check'] == 1) ? $_POST['department'] : $_POST['departmanual'];
		$vcode      = $_POST['vehicle_code'].'.'.$_POST['dept_code'].'.158';

		$update = sqlsrv_query($conn,"update unit set name='".htmlentities($_POST['name'])."',type='".$_POST['unit_type']."',required_availability_hours='".$_POST['required_availability_hours']."',dept='".$department."',model='".$_POST['model']."',plateno='".$_POST['plateno']."',chassisno='".$_POST['chassisno']."',engineno='".$_POST['engineno']."',color='".$_POST['color']."',vehicle_code='".$vcode."' where id='".$_GET['id']."'");
		
		//header("location:".$url."?msg=UPDATED");

	}

	if($_GET['act']=='delete'){

		$update = sqlsrv_query($conn,"update unit set active='0' where id='".$_GET['id']."'");
		header("location:".$url."?msg=DELETED");

	}

	
}
?>
<html>
	<head>
		<title>Maintenance</title>
		<link href="../google.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
		<!-- END GLOBAL MANDATORY STYLES -->

		<link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/select2/select2.css"/>
		<link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/select2/select2.css"/>
    	<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css"/>


		<!-- BEGIN THEME STYLES -->
		<link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
		<link id="style_color" href="../metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
	</head>
	<body style="background-color:white;width:90%;">
		<?php if(isset($_GET['msg'])) {
          echo '<div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Successfully</strong> '.$_GET['msg'].' record!
             </div>';
       	}?>

    	<div class="row">
			<div class="col-md-12">
				<div class="portlet box blue tabbable">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-car"></i>Unit 
						</div>

					</div>

					<div class="portlet-body">
						<div class="tabbable portlet-tabs">
							
							<ul class="nav nav-tabs">
								
									
								
								<li class="">
									<a href="#portlet_tab_2" data-toggle="tab">
									Add New </a>
								</li>
								<li class="active">
									<a href="#portlet_tab_1" data-toggle="tab">
									List </a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="portlet_tab_1">
									<a href="#" class="btn green btn-xs" onclick='window.open("export.php?act=unit", "Maintenance", "width=800,height=600")'>Export to Excel</a>
									<?php 
										if(isset($_GET['act'])){
											if($_GET['act']=='edit'){
									?>
										<div class="portlet grey-gallery box">
											<div class="portlet-title">
												<div class="caption">
													<i class="fa fa-edit"></i>Update Unit
												</div>
											</div>
											<div class="portlet-body"><br><br>
												<form action="<?php echo $url; ?>?act=update&id=<?php echo $_GET['id'];?>" method="POST">
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Vehicle Cost Code</label>
															<div class="col-md-3">
														        <input type="text" class="form-control text-uppercase" name="vehicle_code" value="<?php echo $vcode[0]; ?>" maxlength="9">
															</div>
															<div class="col-md-3">													
																<input class="form-control" type="text" size="16" class="form-control text-uppercase" name="dept_code" value="<?php echo $vcode[1]; ?>" maxlength="5">													
															</div>
															<div class="col-md-3">													
																<input readonly class="form-control" type="text" size="16" class="form-control" value=".158">													
															</div>
														</div>
													</div>	
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Department</label>
															<?php 
															echo $mydropdown; 
															echo $mytextbox;
															?>
															
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Brand</label>
															<div class="col-md-9">
																<input type="text" size="16" name="name" id="name" class="form-control" value="<?php echo $e['name'];?>">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Model</label>
															<div class="col-md-9">
																<input type="text" size="16" name="model" id="model" value="<?php echo $e['model'] ?>" class="form-control">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Plate Number</label>
															<div class="col-md-9">
																<input type="text" size="16" name="plateno" id="plateno" value="<?php echo $e['plateno'] ?>" class="form-control">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Chassis Serial #</label>
															<div class="col-md-9">
																<input type="text" size="16" name="chassisno" id="chassisno" value="<?php echo $e['chassisno'] ?>" class="form-control">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Engine Serial #</label>
															<div class="col-md-9">
																<input type="text" size="16" name="engineno" id="engineno" value="<?php echo $e['engineno'] ?>" class="form-control">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Color</label>
															<div class="col-md-9">
																<input type="text" size="16" name="color" id="color" value="<?php echo $e['color'] ?>" class="form-control">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Type</label>
															<div class="col-md-9">
																<select class="form-control" name="unit_type" required="required">
																	<option value="">- Select -</option>
																	<option value="Light Vehicle" <?php echo ($e['type']=='Light Vehicle' ? 'selected="selected"':'')?>>Light Vehicle</option>
																	<option value="Medium Vehicle" <?php echo ($e['type']=='Medium Vehicle' ? 'selected="selected"':'')?>>Medium Vehicle</option>
																	<option value="Heavy Equipment" <?php echo ($e['type']=='Heavy Equipment' ? 'selected="selected"':'')?>>Heavy Equipment</option>
																	<option value="Motorcycle" <?php echo ($e['type']=='Motorcycle' ? 'selected="selected"':'')?>>Motorcycle</option>
																</select>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Required Availability Hours</label>
															<div class="col-md-9">
																<input type="number" size="16" name="required_availability_hours" value="<?php echo $e['required_availability_hours'];?>" step="0.01" id="required_availability_hours" class="form-control">
															</div>
														</div>
													</div>
													<div class="form-actions" style="margin-left:20px;">
														<button type="submit" class="btn btn-sm blue">Update</button>
														<a href="<?php echo $url; ?>" class="btn btn-sm default">Cancel</a>		<br><br>											
													</div>
												</form>
												
											</div>
										</div>
									<?php 
										}}
									?>

									<table class="table table-striped table-condensed" id="sample_1" style="font-size:12px;">
										<thead>
										<tr>
											<th>ID</th>
											<th>Vehicle Code</th>
											<th>Brand</th>
											<th>Model</th>
											<th>Plate No.</th>
											<th>Chassis No.</th>
											<th>Engine No.</th>
											<th>Color</th>
											<th>Department</th>
											<th>Type</th>
											<th>Required Availability Hrs</th>
											<th>Actions</th>
										</tr>
										</thead>
										<tbody>
									<?php 
										$q = sqlsrv_query($conn,"select * from unit where active=1 order by id desc");
										while($r = sqlsrv_fetch_array($q)){
											echo '<tr class="text-uppercase">
													<td>'.$r['id'].'</td>
													<td>'.$r['vehicle_code'].'</td>
													<td>'.$r['name'].'</td>
													<td>'.$r['model'].'</td>
													<td>'.$r['plateno'].'</td>
													<td>'.$r['chassisno'].'</td>
													<td>'.$r['engineno'].'</td>
													<td>'.$r['color'].'</td>
													<td>'.$r['dept'].'</td>
													<td>'.$r['type'].'</td>
													<td align="right">'.$r['required_availability_hours'].'</td>
													<td align="right">
														<a href="'.$url.'?act=edit&id='.$r['id'].'" class="btn green btn-xs"><i class="fa fa-edit"></i></a>
														<a href="#" onclick="deleted('.$r['id'].');" class="btn red btn-xs"><i class="fa fa-minus-circle"></i></a>
													</td>
												</tr>';
										}
									?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="portlet_tab_2">
									<h3>Add New</h3>
									<form action="<?php echo $url; ?>?act=save" method="POST">
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Vehicle Cost Code</label>

												<div class="col-md-3">
													<input class="form-control text-uppercase" type="text" name="vehicle_code" maxlength="9" />
												</div>
												<div class="col-md-3">													
													<input class="form-control text-uppercase" type="text" size="16" class="form-control" name="dept_code" maxlength="5">													
												</div>
												<div class="col-md-3">													
													<input readonly class="form-control" type="text" size="16" class="form-control" value=".158">													
												</div>
											</div>
										</div>	
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Department</label>
												<div class="col-md-4">													
													 <select onchange="disableTextbox()" class="form-control input-large select2me" name="department" id="department" data-placeholder="Select Department">   
											          <?php                                                                                           
											          echo $agusandept_option;                                                                
											          ?>
											        </select>
											        <input type="hidden" name="check" id="check">
												</div>
												<div class="col-md-5">													
													<input onblur="disableDropdown()" class="form-control" type="text" size="16" name="departmanual" id="departmanual" class="form-control">													
												</div>
											</div>
										</div>	
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Brand</label>
												<div class="col-md-9">
													<input type="text" size="16" name="name" id="name" class="form-control">
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Model</label>
												<div class="col-md-9">
													<input type="text" size="16" name="model" id="model" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Plate Number</label>
												<div class="col-md-9">
													<input type="text" size="16" name="plateno" id="plateno" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Chassis Serial #</label>
												<div class="col-md-9">
													<input type="text" size="16" name="chassisno" id="chassisno" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Engine Serial #</label>
												<div class="col-md-9">
													<input type="text" size="16" name="engineno" id="engineno" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Color</label>
												<div class="col-md-9">
													<input type="text" size="16" name="color" id="color" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Type</label>
												<div class="col-md-9">
													<select class="form-control" name="unit_type" required="required">
														<option value="">- Select -</option>
														<option value="Light Vehicle">Light Vehicle</option>
														<option value="Medium Vehicle">Medium Vehicle</option>
														<option value="Heavy Equipment">Heavy Equipment</option>
														<option value="Motorcycle">Motorcycle</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Required Availability Hours</label>
												<div class="col-md-9">
													<input type="number" size="16" name="required_availability_hours" value="0.00" step="0.01" id="required_availability_hours" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-actions" style="margin-left:20px;">
											<button type="submit" class="btn btn-sm blue">Save</button>
											<a href="#portlet_tab_1" data-toggle="tab" class="btn btn-sm default">Cancel</a>		<br><br>											
										</div>
									</form>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	
	</body>
	<script src="../metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	
	<script src="../metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
    <script type="text/javascript" src="metronic/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
	
	<script type="text/javascript" src="../metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

	<script src="../metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
	<script src="metronic/assets/admin/pages/scripts/components-dropdowns.js"></script>
    <script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
	 <script>
         jQuery(document).ready(function() {    
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            TableManaged.init();
            ComponentsDropdowns.init();    
            window.setTimeout(function() {
			    $(".alert").fadeTo(500, 0).slideUp(500, function(){
			        $(this).remove(); 
			    });
			}, 2000);
         });
      </script>
      <script>
         var TableManaged = function () {

            var initTable1 = function () {

                 var table = $('#sample_1');

                 // begin first table
                 table.dataTable({
                     "columns": [{
                         "orderable": true
                     }, {
                         "orderable": true
                     }, {
                         "orderable": true                    
                     }, {
                     "orderable": true
                     }, {
                     "orderable": true
                     }, {
                     "orderable": true
                     }, {
                     "orderable": true
                     }, {
                     "orderable": true
                     }, {
                     "orderable": true
                     }, {
                     "orderable": true
                     }, {
                         "orderable": false
                     }],
                     "lengthMenu": [
                         [5, 10, 20, -1],
                         [5, 10, 20, "All"] // change per page values here
                     ],
                     // set the initial value
                     "pageLength": 10,            
                     "pagingType": "bootstrap_full_number",
                     "language": {
                         "lengthMenu": "  _MENU_ records",
                         "paginate": {
                             "previous":"Prev",
                             "next": "Next",
                             "last": "Last",
                             "first": "First"
                         }
                     },
                     "columnDefs": [{  // set default column settings
                         'orderable': false,
                         'targets': [0]
                     }, {
                         "searchable": false,
                         "targets": [0]
                     }],
                     "order": [
                         [0, "desc"]
                     ] // set first column as a default sort by asc
                 });

                 var tableWrapper = jQuery('#sample_1_wrapper');

                 table.find('.group-checkable').change(function () {
                     var set = jQuery(this).attr("data-set");
                     var checked = jQuery(this).is(":checked");
                     jQuery(set).each(function () {
                         if (checked) {
                             $(this).attr("checked", true);
                             $(this).parents('tr').addClass("active");
                         } else {
                             $(this).attr("checked", false);
                             $(this).parents('tr').removeClass("active");
                         }
                     });
                     jQuery.uniform.update(set);
                 });

                 table.on('change', 'tbody tr .checkboxes', function () {
                     $(this).parents('tr').toggleClass("active");
                 });

                 tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
             }

             return {
                 //main function to initiate the module
                 init: function () {
                     if (!jQuery().dataTable) {
                         return;
                     }
                     initTable1();
                 }
             };

         }();
      </script>
      <script>
	 	function deleted(x){	 		
			var r = confirm("Are you sure you want to delete this record?");
			if (r == true) {
			    window.location = "<?php echo $url?>?act=delete&id="+x;
			} else {
			    return false;
			}
	 	}


		function disableTextbox() {
			if (document.getElementById("department").value != "SELECT") {
			document.getElementById("departmanual").disabled = true;
			document.getElementById("check").value = '1';
			}
			else {
			document.getElementById("departmanual").disabled = false;
			}
		}

		function disableDropdown() {
			if (document.getElementById("departmanual").value != '') {
			document.getElementById("department").disabled = true;
			document.getElementById("check").value = '2';
			}
			else {
			document.getElementById("department").disabled = false;
			}
		}





      </script>

	
</html>