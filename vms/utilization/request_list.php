<?php
   include("../config.php");
   include("../functions.php");

   session_start();
   if(!$_SESSION['esdvms_username']){
      header("location:login.php");
   }

   

   $pageNumber = 1;
   $pageSize = 20;
   if(isset($_GET['pageNumber'])){
	$pageNumber = $_GET['pageNumber'];
   }
 

   
   
   

      if(isset($_GET['act']) == 'submit'){
         $request_dept = (strlen($_POST['dept_input']) > 0 ? $_POST['dept_input'] : $_POST['dept_select']);

         $insert = "insert into vehicle_request (name,dept,date_needed,purpose,costcode,addedBy,addedAt,status,lastStatusChanged,lastStatusChangedBy)
            values ('".$_SESSION['esdvms_username']."','".$request_dept."','".date('Y-m-d H:i:s',strtotime($_POST['date_needed']))."',
            '".$_POST['purpose']."','".$_POST['costcode']."','".$_SESSION['esdvms_username']."','".date('Y-m-d H:i:s')."',
            'New Request','".date('Y-m-d H:i:s')."','".$_SESSION['esdvms_username']."'); SELECT SCOPE_IDENTITY()
            "; 

         $resource = sqlsrv_query($conn, $insert); 
         sqlsrv_next_result($resource); 
         sqlsrv_next_result($resource);
         sqlsrv_fetch($resource); 

         $lastins=sqlsrv_get_field($resource, 0); 

   		if($_POST['di_deliverysite'] == 'Other'){
   			$insert = sqlsrv_query($conn,"insert into destinations (name) VALUES ('".$_POST['di_otherd']."')");
   			$_POST['di_deliverysite'] = $_POST['di_otherd'];
   		}

         $detail_insert = sqlsrv_query($conn,"insert into request_other_info ([request_id],[contact_person],[designation],[dept]
         ,[contact_no],[delivery_site],[other_instructions],[pickup_dept],[pickup_location]) VALUES 
         ('".$lastins."','".$_POST['di_contactperson']."','".$_POST['di_designation']."','".$_POST['di_dept']."',
         '".$_POST['di_contactno']."','".$_POST['di_deliverysite']."','".$_POST['di_instruction']."','".$_POST['pi_dept']."','".$_POST['pi_location']."')");

         $update_refcode = sqlsrv_query($conn,"update vehicle_request set refcode='".request_refcode($lastins)."' where id = '".$lastins."'");
         $logs = add_history($lastins,$_SESSION['esdvms_username']." Created this request");

         header("location: request_list.php");
      }

      if(isset($_GET['act']) == 'update'){

   		if($_POST['di_deliverysite_edit'] == 'Other'){
   			$insert = sqlsrv_query($conn,"insert into destinations (name) VALUES ('".$_POST['di_otherd_edit']."')");
   			$_POST['di_deliverysite_edit'] = $_POST['di_otherd_edit'];
   		}
         
         $old = sqlsrv_fetch_array(sqlsrv_query($conn,"select *,CONVERT(VARCHAR(19),date_needed) as needed from vehicle_request where id='".$_POST['id_edit']."'"));
         $old_other = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from request_other_info where request_id='".$_POST['id_edit']."'"));
         $request_dept = (strlen($_POST['dept_input_edit']) > 0 ? $_POST['dept_input_edit'] : $_POST['dept_select_edit']);
         $updates='';
         if($old['purpose'] != $_POST['purpose_edit']){
            $updates.='PURPOSE FROM: '.$old['purpose']." TO: ".$_POST['purpose_edit']."<br>";
         }
         if($old['costcode'] != $_POST['costcode_edit']){
            $updates.='COSTCODE FROM: '.$old['costcode']." TO: ".$_POST['costcode_edit']."<br>";
         }
         if($old['dept'] != $request_dept){
            $updates.='DEPT FROM: '.$old['dept']." TO: ".$request_dept."<br>";
         }
         if(date('Y-m-d H:i:s',strtotime($old['needed'])) != date('Y-m-d H:i:s',strtotime($_POST['date_needed_edit']))){
            $updates.='DATE NEEDED FROM: '.date('Y-m-d H:i:s',strtotime($old['needed']))." TO: ".date('Y-m-d H:i:s',strtotime($_POST['date_needed_edit']))."<br>";
         }
         if($old_other['contact_person'] != $_POST['di_contactperson_edit']){
            $updates.='&nbsp;&nbsp;&nbsp;&nbsp;Contact Person from: <b>'.strtoupper($old_other['contact_person'])." </b>to: <b>".strtoupper($_POST['di_contactperson_edit'])."</b><br>";
         }
         if($old_other['designation'] != $_POST['di_designation_edit']){
            $updates.='&nbsp;&nbsp;&nbsp;&nbsp;Designation from: <b>'.strtoupper($old_other['designation'])." </b>to: <b>".strtoupper($_POST['di_designation_edit'])."</b><br>";
         }
         if($old_other['dept'] != $_POST['di_dept_edit']){
            $updates.='&nbsp;&nbsp;&nbsp;&nbsp;Dept from: <b>'.strtoupper($old_other['dept'])." </b>to: <b>".strtoupper($_POST['di_dept_edit'])."</b><br>";
         }
         if($old_other['contact_no'] != $_POST['di_contactno_edit']){
            $updates.='&nbsp;&nbsp;&nbsp;&nbsp;Contact No from: <b>'.strtoupper($old_other['contact_no'])." </b>to: <b>".strtoupper($_POST['di_contactno_edit'])."</b><br>";
         }
         if($old_other['delivery_site'] != $_POST['di_deliverysite_edit']){
            $updates.='&nbsp;&nbsp;&nbsp;&nbsp;Delivery Site from: <b>'.strtoupper($old_other['delivery_site'])." </b>to: <b>".strtoupper($_POST['di_deliverysite_edit'])."</b><br>";
         }
         if($old_other['other_instructions'] != $_POST['di_instruction_edit']){
            $updates.='&nbsp;&nbsp;&nbsp;&nbsp;Delivery Instruction from: <b>'.strtoupper($old_other['other_instructions'])." </b>to: <b>".strtoupper($_POST['di_instruction_edit'])."</b><br>";
         }
         if($old_other['pickup_dept'] != $_POST['pi_dept_edit']){
            $updates.='&nbsp;&nbsp;&nbsp;&nbsp;Pickup Department from: <b>'.strtoupper($old_other['pickup_dept'])." </b>to: <b>".strtoupper($_POST['pi_dept_edit'])."</b><br>";
         }
         if($old_other['pickup_location'] != $_POST['pi_location_edit']){
            $updates.='&nbsp;&nbsp;&nbsp;&nbsp;Pickup Location from: <b>'.strtoupper($old_other['pickup_location'])." </b>to: <b>".strtoupper($_POST['pi_location_edit'])."</b><br>";
         }

         
         $update = sqlsrv_query($conn, "update vehicle_request set purpose='".$_POST['purpose_edit']."',
            date_needed='".date('Y-m-d H:i:s',strtotime($_POST['date_needed_edit']))."',
            costcode='".$_POST['costcode_edit']."',
            dept='".$request_dept."',
            updated_by='".$_SESSION['esdvms_username']."',
            updated_at='".date('Y-m-d H:i:s')."'
             where id='".$_POST['id_edit']."'");

         $update_other_info = sqlsrv_query($conn, "update request_other_info set contact_person='".$_POST['di_contactperson_edit']."',
            designation='".$_POST['di_designation_edit']."',
            dept='".$_POST['di_dept_edit']."',
            contact_no='".$_POST['di_contactno_edit']."',
            delivery_site='".$_POST['di_deliverysite_edit']."',
            other_instructions='".$_POST['di_instruction_edit']."',
            pickup_dept='".$_POST['pi_dept_edit']."',
            pickup_location='".$_POST['pi_location_edit']."'
            where request_id='".$_POST['id_edit']."'");
         
         $logs = add_history($_POST['id_edit'],$_SESSION['esdvms_username']." Updated this request<br>".$updates);
         header("location: request_list.php");
      }

      if(isset($_GET['act']) == 'cancel'){

         $update = sqlsrv_query($conn, "update vehicle_request set status='Cancelled', 
            Cancelled_by='".$_SESSION['esdvms_username']."',
            Cancelled_at='".date('Y-m-d H:i:s')."',
            updated_by='".$_SESSION['esdvms_username']."',
            updated_at='".date('Y-m-d H:i:s')."',
            lastStatusChanged='".date('Y-m-d H:i:s')."',
            lastStatusChangedBy='".$_SESSION['esdvms_username']."',
            isNotEditable='1'
            where id='".$_GET['id']."'");
         
         $logs = add_history($_GET['id'],$_SESSION['esdvms_username']." Cancelled this request<br>");
         header("location: request_list.php");
      }

   $dept_array = array();
   $user_dept = sqlsrv_query($conn,"select distinct dept from users where active=1 order by dept asc");
   while($user_d = sqlsrv_fetch_array($user_dept)){
      array_push($dept_array, $user_d['dept']);
   }
   $request_dept = sqlsrv_query($conn,"select distinct dept from vehicle_request order by dept asc");
   while($request_d = sqlsrv_fetch_array($request_dept)){
      array_push($dept_array, $request_d['dept']);
   }
   $dept = array_unique($dept_array,SORT_STRING);

   $old_site = "";
   $user_dept = sqlsrv_query($conn,"select name from destinations order by name");
   while($user_d = sqlsrv_fetch_array($user_dept)){
	$old_site.= '<option value="'.$user_d['name'].'">'.$user_d['name'].'</option>';
   }
   $old_site.= '<option value="Other">Other Destination</option>';

?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8"/>
      <title>Vehicle | Monitoring</title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
      <meta content="" name="description"/>
      <meta content="" name="author"/>
      <!-- BEGIN GLOBAL MANDATORY STYLES -->
      <link href="../google.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
      <!-- END GLOBAL MANDATORY STYLES -->

      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/select2/select2.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
      <!-- BEGIN PAGE LEVEL STYLES -->
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/clockface/css/clockface.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
      <link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>


      <!-- BEGIN THEME STYLES -->
      <link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
      <link id="style_color" href="../metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
      <link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
      <!-- END THEME STYLES -->
      <link rel="shortcut icon" href="favicon.ico"/>
      
   </head>
   <body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
   <div class="modal fade" id="ChangeStatusModal" tabindex="-1" role="ChangeStatusModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<input type="hidden" name="cs_id" id="cs_id">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Change Status for <span id="cs_refcode"></span></h4>
				</div>

				<div class="modal-body"> 
					<select class="form-control" name="cs_status" id="cs_status" required>                             
						<option value="Waiting for Vehicle Availability">Waiting for Vehicle Availability</option>
						<option value="Waiting for Driver Availability">Waiting for Driver Availability</option>
						<option value="On-Hold by Requester">On-Hold by Requester</option>
						<option value="Scheduled">Scheduled</option>
						<option value="In-progress">In-progress</option>
						<option value="Cancelled">Cancelled</option>
					</select>
					<br>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
					<button type="button" onclick="change_status();" class="btn btn-circle blue"><span class="fa fa-check"></span> Update Status</button>
				</div>

			</div>
		</div>
	</div>

	<div class="modal fade bs-modal-lg" id="newrequest" tabindex="-1" role="newrequest" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			<form autocomplete="off" method="post" action="request_list.php?act=submit" id="newrequest_form" onsubmit="return validate_dept();">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">New Vehicle Request</h4>
				</div>
				<div class="modal-body" style="height:850px">
					<input type="hidden" name="action" value="add">
					<table width="100%">
						<tr valign="middle">                                    
						<td align="center">                                       
							<table width="100%">
								<tr>
									<td>
									<select required name="dept_select" id="dept_select" class="form-control" placeholder="Select Dept">
											<option value="">Select Dept</option>
										<?php                                                       
											foreach ($dept as $key => $value) {
												echo '<option class="text-uppercase" value="'.$value.'">'.$value.'</option>';
											}
										?>
									</select>
									</td>
									<td><input type="text" name="dept_input" id="dept_input" class="form-control" placeholder="Or input new dept"></td>
								</tr>
							</table>
							<br>
						</td>                                    
						</tr> 
					</table>  
					<div class="row">
						<div class="portlet box blue col-md-10 col-md-offset-1">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-file-powerpoint-o"></i> Request Details
							</div>                                   
						</div>
						<div class="portlet-body form">
							<div class="form-horizontal" role="form">                           
								<div class="form-body">
									
									<div class="form-group">
									<label class="col-md-4 control-label">Date Needed <i class="font-red">*</i></label>
									<div class="col-md-8">
										<div class="input-group date form_datetime">
											<input type="text" size="16" id="datee" class="form-control" name="date_needed" required>
											<span class="input-group-btn">
											<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
									</div>
									<div class="form-group">
									<label class="col-md-4 control-label">Chargeable Cost Code <i class="font-red">*</i></label>
									<div class="col-md-8">
										<input type="text" name="costcode" id="costcode" class="form-control" placeholder="Cost Code" required>
									</div>
									</div>
									<div class="form-group">
									<label class="col-md-4 control-label">Purpose/Description of Work <i class="font-red">*</i></label>
									<div class="col-md-8">
										<textarea name="purpose" class="form-control" id="purpose" required></textarea>
									</div>
									</div>
									
								</div>
							</div>                                 
						</div>
						</div>
					</div>                              
					<div class="row">
						<div class="col-md-6">
						<div class="portlet box blue-hoki">
						<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-truck"></i> Delivery Instructions
								</div>                                   
						</div>
						<div class="portlet-body form">
								<div role="form">                           
									<div class="form-body">

									<div class="form-group">
									<label class="control-label">Contact Person <i class="font-red">*</i></label>                    
									<input type="text" id="di_contactpersonq" name="di_contactpersonq" class="typeahead form-control input-sm" required>   
									<input type="hidden" id="di_contactperson" name="di_contactperson" class="form-control input-sm">       
									</div>

									<div class="form-group">
									<label class="control-label">Contact No. / Office Tel No. <i class="font-red">*</i></label>                                                
									<input type="text" id="di_contactno" name="di_contactno" class="form-control input-sm" required>                                                
									</div> 

									<div class="form-group hide">
									<label class="control-label">Designation</label>                                                
									<input type="text" id="di_designation" name="di_designation" class="form-control input-sm">                                                
									</div>        

									<div class="form-group hide">
									<label class="control-label">Dept</label>                                                
									<input type="text" id="di_dept" name="di_dept" class="form-control input-sm">                                                
									</div> 

									<div class="form-group">
									<label class="control-label">Delivery Site</label>      
									<select name="di_deliverysite" id="di_deliverysite" class="form-control input-sm">
										<option value="">-- Select One --</option>
										<?php echo $old_site; ?>
									</select>
									<input type="text" id="di_otherd" name="di_otherd" class="form-control input-sm margin-top-10" style="display:none;" placeholder="Enter Other Destination">           
									
									</div>     

									<div class="form-group">
									<label class="control-label">Delivery Instruction</label>  
									<textarea name="di_instruction" id="di_instruction" class="form-control" required="required"></textarea>                                            
									</div>                             

									</div>
								</div>                                 
						</div>
						</div>
						</div>
						<div class="col-md-6">
						<div class="portlet box grey-gallery">
						<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-paper-plane-o"></i> Pickup Instructions
								</div>                                   
						</div>
						<div class="portlet-body form">
								<div role="form">                           
									<div class="form-body">
									<div class="form-group">
										<label class="control-label">Dept / Establishment (for outside of PMC)</label>                                                      
										<input type="text" id="pi_dept" name="pi_dept" class="form-control input-sm" placeholder="">                                                      
									</div>

									<div class="form-group">
										<label class="control-label">Location/Site/Address</label>                                                      
										<input type="text" id="pi_location" name="pi_location" class="form-control input-sm" placeholder="">                                                      
									</div>                                             

									</div>
								</div>                                 
						</div>
						</div>
						</div>
						
					</div>                               
				</div>
				<div class="modal-footer" id="footermode">
					<button type="button" class="btn default" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn blue" value="Save">
				</div>
			</form>
			</div>
		</div>
	</div>

	<div class="modal fade bs-modal-lg" id="editrequest" tabindex="-1" role="editrequest" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			<form method="post" action="request_list.php?act=update">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Update Request</h4>
				</div>
				<div class="modal-body" style="height:300px">
					<input type="hidden" name="id_edit" id="id_edit">
					<table width="100%">
						<tr valign="middle">
						<td>                                       
							<table width="100%">
								<tr>
									<td>
									<select name="dept_select_edit" id="dept_select_edit" class="form-control" placeholder="Select Dept">
											<option value="">Select Dept</option>
										<?php                                                       
											foreach ($dept as $key => $value) {
												echo '<option value="'.$value.'">'.$value.'</option>';
											}
										?>
									</select>
									</td>
									<td><input type="text" name="dept_input_edit" id="dept_input_edit" class="form-control" placeholder="Or input new dept"></td>
								</tr>
							</table>
							<br>
						</td>                                    
						</tr>                                   
					</table>      
					<div class="row">
						<div class="portlet box blue col-md-10 col-md-offset-1">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-file-powerpoint-o"></i> Request Details
							</div>                                   
						</div>
						<div class="portlet-body form">
							<div class="form-horizontal" role="form">                           
								<div class="form-body">
									<div class="form-group">
									<label class="col-md-4 control-label">Date Needed <i class="font-red">*</i></label>
									<div class="col-md-8">
										<div class="input-group date form_datetime">
											<input type="text" size="16" readonly class="form-control" name="date_needed_edit" id="date_needed_edit" required>
											<span class="input-group-btn">
											<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
									</div>
									<div class="form-group">
									<label class="col-md-4 control-label">Chargeable Cost Code <i class="font-red">*</i></label>
									<div class="col-md-8">
										<input type="text" name="costcode_edit" id="costcode_edit" class="form-control" placeholder="Cost Code" required>
									</div>
									</div>
									<div class="form-group">
									<label class="col-md-4 control-label">Purpose/Description of Work <i class="font-red">*</i></label>
									<div class="col-md-8">
										<textarea name="purpose_edit" class="form-control" id="purpose_edit" required></textarea>
									</div>
									</div>
									
								</div>
							</div>                                 
						</div>
						</div>
					</div>                              
					<div class="row">
						<div class="col-md-6">
						<div class="portlet box blue-hoki">
						<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-truck"></i> Delivery Instructions
								</div>                                   
						</div>
						<div class="portlet-body form">
								<div role="form">                           
									<div class="form-body">

									<div class="form-group">
									<label class="control-label">Contact Person <i class="font-red">*</i></label>                    
									<input type="text" id="di_contactperson_editq" name="di_contactperson_editq" class="form-control input-sm" placeholder="" required>  
									<input type="hidden" id="di_contactperson_edit" name="di_contactperson_edit" class="form-control input-sm" placeholder="">                         
									</div>

									<div class="form-group">
									<label class="control-label">Contact No. / Office Tel No. <i class="font-red">*</i></label>                                                
									<input type="text" id="di_contactno_edit" name="di_contactno_edit" class="form-control input-sm" placeholder="" required>                                                
									</div> 

									<div class="form-group">
									<label class="control-label">Designation</label>                                                
									<input type="text" id="di_designation_edit" name="di_designation_edit" class="form-control input-sm" placeholder="">                                                
									</div>        

									<div class="form-group">
									<label class="control-label">Dept</label>                                                
									<input type="text" id="di_dept_edit" name="di_dept_edit" class="form-control input-sm" placeholder="">                                                
									</div> 
								
									<div class="form-group">
									<label class="control-label">Delivery Site</label>      
									<select name="di_deliverysite_edit" id="di_deliverysite_edit" class="form-control input-sm">													
										<?php echo $old_site; ?>
									</select>
									<input type="text" id="di_otherd_edit" name="di_otherd_edit" class="form-control input-sm margin-top-10" style="display:none;" placeholder="Enter Other Destination">           
									
									</div>  

									<div class="form-group">
									<label class="control-label">Delivery Instruction</label>  
									<textarea name="di_instruction_edit" id="di_instruction_edit" class="form-control" required="required"></textarea>                                            
									</div>                             

									</div>
								</div>                                 
						</div>
						</div>
						</div>
						<div class="col-md-6">
						<div class="portlet box grey-gallery">
						<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-paper-plane-o"></i> Pickup Instructions
								</div>                                   
						</div>
						<div class="portlet-body form">
								<div role="form">                           
									<div class="form-body">
									<div class="form-group">
										<label class="control-label">Dept / Establishment (for outside of PMC)</label>                                                      
										<input type="text" id="pi_dept_edit" name="pi_dept_edit" class="form-control input-sm" placeholder="">                                                      
									</div>

									<div class="form-group">
										<label class="control-label">Location/Site/Address</label>                                                      
										<input type="text" id="pi_location_edit" name="pi_location_edit" class="form-control input-sm" placeholder="">                                                      
									</div>                                             

									</div>
								</div>                                 
						</div>
						
						</div>
						<div>
							<button type="button" class="btn default" data-dismiss="modal">Cancel</button>
							<input type="submit" class="btn blue" value="Save">
						</div>
						</div>
						
					</div>                           
				</div>
				<div class="modal-footer">
					
				</div>
			</form>
			</div>
		</div>
	</div>
      <!-- BEGIN HEADER -->
      <?php include("../header.php");?>
      <div class="clearfix"></div>
      <!-- BEGIN CONTAINER -->
      <div class="page-container">
         <!-- BEGIN CONTENT -->
         <div class="page-content-wrapper">
            <div class="page-content">

               
                  
               <form method="get" act="index.php">
                  <div class="row">
                     <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title">
                           Request List                                              
                        </h3>                        
                     </div>
                  </div>
               </form>
         
               <div class="clearfix">
               </div>
               <div class="row margin-bottom-10">
                  <div class="col-md-2 pull-left"><span style="font-size:15px;"><input type="checkbox" name="filter" id="filter"> Filter</span></div>
                  <div class="col-md-6 text-center">
                     Trip Ticket Legend: 
                     <span class="label label-primary">New Ticket</span>
                     <span class="label label-warning">Ticket Printed</span>
                     <span style="background-color:#35aa47;" class="label">Completed</span>
                     <span class="label label-danger">Cancelled</span>
                  </div>
                  <div class="col-md-4 text-right">
                     <div>
                        <a class="btn blue btn-sm" href="#" style="margin-left: 10px;" onclick="$('#newrequest').modal('show');">
                          <span class="fa fa-plus"></span> Add New
                        </a>
                        <a class="btn green btn-sm" href="excel_download_request.php" style="margin-left: 10px;">
                           <span class="fa fa-download"></span> Download List
                        </a>                       
                     </div> 
                  </div>
               </div>  
               <div class="row">                  
                  <div class="col-md-12">                     
                     <div class="clearfix"></div>
                     <table style="font-size:12px;" class="table table-striped table-bordered table-hover js-dynamitable">
                        <thead id="nofilter_head">                                    
                           <tr>
                              <th style="font-size:11px;">Request No. </th>
                              <th style="font-size:11px;">Dept </th>
                              <th style="font-size:11px;">Date Needed </th>
                              <th style="font-size:11px;">Date Requested </th>
                              <th style="font-size:11px;">Purpose</th>
                              <th style="font-size:11px;">Last Message </th>
                              <th style="font-size:11px;">Status </th>
                              <th style="font-size:11px;">Status Changed </th>
                              <th style="font-size:11px;">Trip Ticket </th>
                              <th style="font-size:11px;">Action </th>                                    
                           </tr>                                  
                        </thead>
                        <thead id="filter_head" style="display:none;">
                           
                           <tr>
                              <th style="font-size:11px;">Request No. <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                               <th style="font-size:11px;">Dept <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Date Needed <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Date Requested <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Purpose <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Last Message <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Status <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Status Changed <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Trip Ticket <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
                              <th style="font-size:11px;">Action <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>                                    
                           </tr>
                           <tr>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th>                                 
                                 <select class="js-filter form-control" style="font-size:12px;">
                                    <option value=""></option>
                                    <option value="New Request">New Request </option>
                                    <option value="Waiting for Vehicle Availability">Waiting for Vehicle Availability</option>
                                    <option value="Waiting for Driver Availability">Waiting for Driver Availability</option>
                                    <option value="On-Hold by Requester">On-Hold by Requester</option>
                                    <option value="In-progress">In-progress</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Closed">Closed</option>
                                    <option value="">Reset Filter</option>
                                 </select>
                              </th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                              <th><input class="js-filter  form-control input-sm" type="text" value=""></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php

                              $lq=sqlsrv_query($conn,"select CONVERT(VARCHAR(19),date_needed) as needed,CONVERT(VARCHAR(19),addedAt) as added,CONVERT(VARCHAR(19),lastStatusChanged) as lastchanged,*                                     
								 from vehicle_request order by id desc OFFSET (".$pageNumber."-1)*".$pageSize." ROWS FETCH NEXT ".$pageSize." ROWS ONLY");
                              while($l=sqlsrv_fetch_array($lq)){
                                 $r=sqlsrv_fetch_array(sqlsrv_query($conn,"select top 1 * from vehicle_request_comments where request_id='".$l['id']."' order by id desc"));


                                 $btn = '';
                                 if($l['isNotEditable'] == '0' || $l['isNotEditable'] == NULL ){
                                    $btn = '<a href="#" class="btn yellow btn-xs" title="Update Request" onclick="edit('.$l['id'].');"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="btn red btn-xs" title="Cancel Request" onclick="cancel('.$l['id'].');"><i class="fa fa-minus-circle"></i></a>';
                                 }
                                 $disable_addBtn = ($l['status'] == 'Cancelled' || $l['status'] == 'Closed') ? 'none' : '';
                                 $showChangeStatus=0;
                                 if($l['status']!='Cancelled' && $l['status']!='Closed'){ 
                                    $showChangeStatus=1;
                                 }
                                 if($_SESSION['esdvms_role']=='admin'){
                                    $showChangeStatus=1;
                                 }
                              ?>

                                    <tr>
                                       <td style="width:80px;"><a target="_blank" href="../request_view.php?id=<?php echo $l['id'];?>" ><?php echo $l['refcode']; ?></a></td>
                                       <td style="width:140px;"><?php echo $l['dept'] ?></td>
                                       <td style="width:140px;"><?php echo date('Y-m-d h:i A',strtotime($l['needed'])); ?></td>
                                       <td style="width:140px;"><?php echo date('Y-m-d h:i A',strtotime($l['added'])); ?></td>     
                                       <td><?php echo $l['purpose']; ?></td>       
                                       <td id="msg_status<?php echo $l['id'];?>"><?php echo $r['comment']; ?></td>       
                                       <td style="color:<?php if($l['status'] == 'Cancelled' || $l['status'] == 'Closed'){ echo 'red;';} ?>">
                                          <?php echo $l['status']; ?>
                                          <?php if($showChangeStatus == 1){ 

                                             ?>
                                          <a style="float: right;" href="javascript::void(0)" onclick="edit_status(<?php echo $l['id']; ?>,'<?php echo $l['status'];?>','<?php echo $l['refcode']?>');"  title="Change Status"><span class='fa fa-pencil'></span> </a>  <?php } ?>                                            
                                       </td>
                                       <td style="width:140px;" title="last changed by <?php echo $l['lastStatusChangedBy'] ?>"><?php echo date('Y-m-d h:i A',strtotime($l['lastchanged'])) ?></td>
                                       <td>
                                          <?php 
                                             $isClosable = 1;
                                             $tid = sqlsrv_query($conn,"SELECT * FROM dispatch WHERE request_id = '".$l['id']."' ");
                                                while($t = sqlsrv_fetch_array($tid)){
                                                   $did = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * from drivers WHERE id = '".$t['driver_id']."' "));
                                                   $tt = $t['tripTicket'];

                                                   if ($t['Status'] == 'Completed' || $t['Status'] == 'Cancelled') {

                                                      if($t['Status'] == 'Completed')
                                                         $color = 'green';
                                                      else
                                                         $color = 'red';

                                                   }
                                                   else{
                                                      $isClosable = 0;
                                                      if($t['Status'] == 'Closed'){
                                                         $color = 'red';
                                                      } else {
                                                         if($t['isPrinted'] == 1)
                                                         $color = 'yellow';
                                                      else
                                                         $color = 'blue';
                                                      }
                                                   } 
                                                   
                                                   if($t['Status'] == 'Completed'){
                                                      echo '<a target="_blank" href="trip_completed.php?id='.$tt.'" class="btn btn-xs '.$color.' popovers" data-container="body" data-trigger="hover" data-html="true" data-placement="top" data-original-title="'.$tt.'" 
                                                         data-content="
                                                                     <div>Vehicle: '.$t['type'].'</div>
                                                                     <div>Date Out: '.$t['dateStart']->format('F d, Y').'</div>
                                                                     <div>Driver: '.$did['driver_name'].'</div>">'.$t['tripTicket'].'</a>&nbsp;';
                                                   } else {
                                                      echo '<a target="_blank" href="dispatch_details.php?id='.$tt.'" class="btn btn-xs '.$color.' popovers" data-container="body" data-trigger="hover" data-html="true" data-placement="top" data-original-title="'.$tt.'" 
                                                         data-content="
                                                                     <div>Vehicle: '.$t['type'].'</div>
                                                                     <div>Date Out: '.$t['dateStart']->format('F d, Y').'</div>
                                                                     <div>Driver: '.$did['driver_name'].'</div>">'.$t['tripTicket'].'</a>&nbsp;';
                                                   }
                                                  
                                                }  
                                          ?>
                                         
                                       </td>                                           
                                       <td style="width:150px;"> 
                                          <input type="hidden" name="isClosable<?php echo $l['id'] ?>" id="isClosable<?php echo $l['id'] ?>" value="<?php echo $isClosable; ?>">
                                          <?php echo $btn; ?>                                                 
                                          <a style="display:<?= $disable_addBtn; ?>" href="dispatch_add.php?id=<?php echo urlencode($l['id']) ?>" class="btn green btn-xs" title="Add New Trip Ticket">
                                             <i class="fa fa-plus-square"></i>
                                          </a>

                                          <a href="#" class="btn purple btn-xs dropdown-quick-sidebar-toggler" title="Send Message" id="<?php echo $l['id'];?>">
                                             <i class="fa fa-comments-o"></i>
                                          </a>
                                       </td>
                                    </tr>
                              <?php
                              }
                          ?>
                        </tbody>
                     </table>
					<div class="pull-right">
						<p>
						<?php if($pageNumber>1){
							echo '<a class="btn blue" href="request_list.php?pageNumber='.($pageNumber-1).'"><< Previous </a>';
						} ?>
						
							<a class="btn blue" href="request_list.php?pageNumber=<?php echo $pageNumber+1; ?>">Next >> </a>
						</p>
					</div>
                     
                  </div>                   
               </div>
               <div class="clearfix">
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

      <script type="text/javascript" src="../metronic/assets/global/plugins/select2/select2.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/clockface/js/clockface.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
      <script type="text/javascript" src="../metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
      <script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

      
      <script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
      <script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
      <script src="../metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
      <script src="../js/excel/src/jquery.table2excel.js"></script>
      <script src="../metronic/assets/admin/pages/scripts/components-pickers.js"></script>
      <script src="<?php echo $url;?>js/notifications.js"></script>
      <script src="<?php echo $url;?>js/comments.js"></script>
      <script src="../js/table/dynamitable.jquery.min.js"></script>
	  <script src="../js/typeahead.js"></script>

      <script> 
         function exportToExcel(table){
            jQuery(table).table2excel({
               name: "VMS",
               filename: "VMS" //do not include extension
            }); 
         }
      </script>

      <script>
         jQuery(document).ready(function() {    
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            ComponentsPickers.init();
			$('#di_contactpersonq').typeahead({
				source: function (query, result) {
					//alert(result);
					$.ajax({
						url: "hris.php",
						data: 'query=' + query,            
						dataType: "json",
						type: "POST",
						success: function (data) {
							//console.log(x);
							
							result($.map(data, function (item) {
								return item;
							}));
							
						}
					});
				},
				updater: function(item) {
					var x = item.split(" - ");
					$('#di_contactperson').val(x[0]);
					$('#di_designation').val(x[1]);
					$('#di_dept').val(x[2]);
					return item;
				}
			});

			$('#di_contactperson_editq').typeahead({
				source: function (query, result) {
					//alert(result);
					$.ajax({
						url: "hris.php",
						data: 'query=' + query,            
						dataType: "json",
						type: "POST",
						success: function (data) {
							//console.log(x);
							
							result($.map(data, function (item) {
								return item;
							}));
							
						}
					});
				},
				updater: function(item) {
					var x = item.split(" - ");
					$('#di_contactperson_edit').val(x[0]);
					$('#di_designation_edit').val(x[1]);
					$('#di_dept_edit').val(x[2]);
					return item;
				}
			});
			
            <?php if(isset($_GET['addNewRequest'])) { ?>
               $('#newrequest').modal('show');
            <?php } ?>
         });
      </script>
<script>
   function edit_status(id,status,refcode){
      $('#cs_id').val(id);
      $('#cs_refcode').html(refcode);
      var isClosable = $('#isClosable'+id).val();
      if(isClosable == 0){
         $("#cs_status option[value='Closed']").remove();
      }
      else{
         $('#cs_status').append($('<option>', {
             value: 'Closed',
             text: 'Closed'
         }));
      }
      
      $('#cs_status option[value="'+status+'"]').attr("selected",true);
      
      $('#ChangeStatusModal').modal('show');
   }

   function change_status(){
      var id = $('#cs_id').val();
      var new_status = $('#cs_status').val();      
      $.ajax({
         method: "POST",
         url: "../ajax.php?act=update_request_status",
         data: { cs_id: id, cs_status: new_status}         
      })
      .done(function() {        
         $('#ChangeStatusModal').modal('hide');
         location.reload(); 
      });
   }

</script>
<script>
   $("#filter").click( function(){
      if( $(this).is(':checked') ) {
         $('#filter_head').show();
         $('#nofilter_head').hide();
      }
      else{
         $('#nofilter_head').show();
         $('#filter_head').hide();
      }
   });
   function validate_dept(){        
        if(!$('#dept_select').val() && !$('#dept_input').val()){
            alert('Please Select Dept or Input New Dept');
            $('#dept_input').focus();
            return false;
        }
        else{
            $("newrequest_form").submit();
        }
   };

   function cancel(x){        
      var r = confirm("Are you sure you want to cancel this request?");
      if (r == true) {
         window.location = "request_list.php?act=cancel&id="+x;
      } else {
         return false;
      }
   }

   function edit(x){

      $.ajax({
         method: "POST",
         url: "../ajax.php?act=get_request_details",
         data: { id: x}
      })
      .done(function( d ) {
      
         n = jQuery.parseJSON(d);
         $('#purpose_edit').val(n.purpose);
         $('#costcode_edit').val(n.costcode);
         $('#date_needed_edit').val(n.need);
         $('#id_edit').val(n.id);
         $('#dept_select_edit').val(n.dept);
         $('#di_contactperson_edit').val(n.contact_person);
		 $('#di_contactperson_editq').val(n.contact_person+' - '+n.designation+' - '+n.depti);
         $('#di_designation_edit').val(n.designation);
         $('#di_dept_edit').val(n.depti);
         $('#di_contactno_edit').val(n.contact_no);
         $('#di_deliverysite_edit').val(n.delivery_site);
         $('#di_instruction_edit').val(n.other_instructions);
         $('#pi_dept_edit').val(n.pickup_dept);
         $('#pi_location_edit').val(n.pickup_location);
         $('#editrequest').modal('show');
      });
   }

   $( "#datee").on('keypress', function(e){ e.preventDefault(); });

   $('#di_deliverysite').on('change', function() {
	 var xx = this.value;
	 if(xx == 'Other'){
		$("#di_otherd").prop('required',true);
		$("#di_deliverysite").prop('required',false);
		$('#di_otherd').show();
	 }
	 else{
		$("#di_otherd").prop('required',false);
		$("#di_deliverysite").prop('required',true);
		$('#di_otherd').hide();
	 }
	});

	$('#di_deliverysite_edit').on('change', function() {
	 var xx = this.value;
	 if(xx == 'Other'){
		$("#di_otherd_edit").prop('required',true);
		$("#di_deliverysite_edit").prop('required',false);
		$('#di_otherd_edit').show();
	 }
	 else{
		$("#di_otherd_edit").prop('required',false);
		$("#di_deliverysite_edit").prop('required',true);
		$('#di_otherd_edit').hide();
	 }
	});

	function costcode_check(x,y){
		$.ajax({
         method: "GET",
         url: "costcodes.php?code="+x
      })
      .done(function( d ) {      
         if(d == 0){
			 alert(x +' is not a valid Cost Code!');
			 $('#'+y).val('');
			 $('#'+y).focus();
		 }
      });
	}
</script>

   </body>
   <!-- END BODY -->
</html>