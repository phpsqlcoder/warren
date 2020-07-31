<?php
include("config.php");
session_start();

 if(!$_SESSION['esdvms_username']){
   	header("location:login.php");
   }

$mechanic_options = "<option value=''> - Select -</option>";
$mechanic_option2 = '';
$mechanic_qry = sqlsrv_query($conn,"select * from mechanics");
while($mechanics = sqlsrv_fetch_array($mechanic_qry)){
	$mechanic_options.="<option value='".$mechanics['name']."'>".$mechanics['name']."</option>";
	$mechanic_option2.='"'.$mechanics['name'].'",';
}
$mechanic_option2 = rtrim($mechanic_option2,',');
if(isset($_GET['act'])){
	if($_GET['act']=='submitdowntime'){
		
		$mechanics = str_replace(",", "|", $_POST['mechanics']);

		$_POST['startd'] = str_replace("T", " ", $_POST['startd']);
		$_POST['endd'] = str_replace("T", " ", $_POST['endd']);
		if(!isset($_POST['from12'])){
			$_POST['from12'] = 0;
			$_POST['from7'] = 0;
			$_POST['trepair_days'] = 0;
			$_POST['trepair_hours'] = 0;
			$_POST['shop_days'] = 0;
			$_POST['shop_hours'] = 0;
			$_POST['man_hours'] = 0;
			$_POST['downtime'] = 0;
			$_POST['required_daily_availability'] = 0;
		}

		$repair_type = ($_POST['dtype']==1 ? $_POST['repairType1'] : $_POST['repairType2']);

		$insert="INSERT INTO [dbo].[downtime]
					([dateStart]
					,[dateEnd]
					,[remarks]
					,[addedBy]
					,[addedDate]
					,[unitId]
					,[isScheduled]
					,[downtimeCategory]
					,[workOrder]
					,[mechanics]
					,[repairType]
					,[workDetails]
					,[reportedDate]
					,[status]
					,[from12]
					,[from7]
					,[trepair_days]
					,[trepair_hours]
					,[shop_days]
					,[shop_hours]
					,[man_hours]
					,[required_daily_availability]
					,[tdowntime]
					,assignedTo
				)
				VALUES(
				'".$_POST['startd']."','".$_POST['endd']."','".htmlentities($_POST['remarks'])."','".$_SESSION['esdvms_username']."','".date('Y-m-d h:i:s')."','".$_POST['unit']."','".$_POST['dtype']."','".$_POST['dcategory']."',
				'".htmlentities($_POST['work_order'])."','".$mechanics."','".$repair_type."','".htmlentities($_POST['work_details'])."','".$_POST['reported_date']."','".$_POST['status']."'
				,'".$_POST['from12']."','".$_POST['from7']."','".$_POST['trepair_days']."','".$_POST['trepair_hours']."','".$_POST['shop_days']."','".$_POST['shop_hours']."','".$_POST['man_hours']."','".$_POST['required_daily_availability']."','".$_POST['downtime']."','".$_POST['assigned_to']."'
				)";

			$resource=sqlsrv_query($conn, $insert);    	

	}
	header("location:downtime_add.php?msg=success");
}
?>
<html>
	<head>
		<title>Add Downtime</title>
		<link href="google.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
		<!-- END GLOBAL MANDATORY STYLES -->

		
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/select2/select2.css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css"/>


		<!-- BEGIN THEME STYLES -->
		<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
		<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
	</head>
	<body style="background-color:white;">
		<?php if(isset($_GET['msg'])) {
          echo '<div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <span class="fa fa-check-square"></span><strong> Success!</strong> Record has been added.
             </div>';
       }?>
		<form method="post" id="downtimeform" action="downtime_add.php?act=submitdowntime">
			<div class="modal-header">
				<h4 class="modal-title"><b>Input Downtime Details</b></h4>
			</div>
			<div class="modal-body">
				<div class="form-group" style="height:500px">
					<div class="row">
						<div class="col-md-6">

							<div class="row">
								<div class="col-md-12 margin-bottom-10">
									<label class="control-label col-md-3">Unit</label>
									<div class="col-md-9">
										<select class="form-control" name="unit" id="unit" required>
											<option value=""> - Select Unit -</option>

											<?php
											$uq=sqlsrv_query($conn,"select * from unit order by type,name");
											while($u=sqlsrv_fetch_array($uq)){
												echo '<option value="'.$u['id'].'">'.$u['type'].' '.$u['name'];
											}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10">
									<label class="control-label col-md-3">Work Order</label>
									<div class="col-md-9">
										<input type="text" size="16" name="work_order" id="work_order" class="form-control" required="required">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10">                                
									<label class="control-label col-md-3">Assigned To:</label>
									<div class="col-md-9">
										<select class="form-control" name="assigned_to" id="assigned_to">
											<option value=""> - Select -</option>
											<?php
											$uq=sqlsrv_query($conn,"select * from assigned order by name");
											while($u=sqlsrv_fetch_array($uq)){
												echo '<option value="'.$u['name'].'">'.$u['name'];
											}
											?>                              
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10">   
									<label class="control-label col-md-3">Downtime Category</label>
									<div class="col-md-9">
										<select class="form-control" name="dcategory">
											<option value=""> - Select Category -</option>
											<option value="ACCIDENT">Accident</option>
											<option value="CORRECTIVE MAINTENANCE">Corrective Maintenance</option> 
											<option value="PREVENTIVE MAINTENANCE">Preventive Maintenance</option>                                                                     
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10">   
									<label class="control-label col-md-3">Downtime Type</label>
									<div class="col-md-9">
										<select class="form-control" name="dtype" id="dtype" onchange="dtypeChanged();">
											<option value=""> - Select Type -</option>
											<option value="1">Scheduled Downtime (Corrective/PM)</option>
											<option value="2">Unscheduled Downtime (Breakdown)</option>                                                                     
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">

									<div id="rt1" style="display:none;">
										<label class="control-label col-md-3">Repair Type</label>
										<div class="col-md-9">
											<select class="form-control" name="repairType1" id="repairType">
												<option value=""> - Select Type -</option>
												<option value="Inspections">Inspections</option>
												<option value="Repair and Replace">Repair and Replace</option>
												<option value="Service and Lube">Service and Lube</option>                                      
											</select>
										</div>                                       
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">

									<div id="rt2" style="display:none;">
										<label class="control-label col-md-3">Repair Type</label>
										<div class="col-md-9">
											<select class="form-control" name="repairType2" id="repairType">
												<option value=""> - Select Type -</option>
												<option value="Brake System">Brake System</option>
												<option value="Clutch System">Clutch System</option>
												<option value="Engine System">Engine System</option> 
												<option value="Primary Function">Primary Function</option>
												<option value="Transmission System">Transmission System</option>                                     
											</select>
										</div>                                          
									</div>
								</div>
							</div>



							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 

									<label class="control-label col-md-3">Work Details:</label>
									<div class="col-md-9">
										<textarea class="form-control" rows="5" name="work_details" placeholder="Work Details"></textarea>                       
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 
									<label class="control-label col-md-3">Remarks:</label>
									<div class="col-md-9">
										<textarea class="form-control" rows="5" onkeyup="mech();" name="remarks" placeholder="Remarks"></textarea>                      
									</div>

								</div>
							</div>
							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 

									<label class="control-label col-md-3">Mechanics:</label>
									<div class="col-md-9">
										<input type="hidden" id="select2_sample5" name="mechanics" class="form-control select2">
          
									</div>
									<div class="col-md-12" id="crewdiv">

									</div>
								</div>
							</div>						
						</div>

						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 
									<label class="control-label col-md-3">Status</label>
									<div class="col-md-9">
										<select class="form-control" name="status" id="status" required>
											<option value=""> - Current Status -</option>

											<?php
											$uq=sqlsrv_query($conn,"select * from unit_status order by status");
											while($u=sqlsrv_fetch_array($uq)){
												echo '<option value="'.$u['status'].'">'.$u['status'];
											}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 
									<label class="control-label col-md-3">Start</label>
									<div class="col-md-9">
										<div class="input-group">
											<input class="form-control" onchange="checkdates('startd')" type="datetime-local" id="startd" name="startd" />                                               

										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 
									<label class="control-label col-md-3">End</label>
									<div class="col-md-9">
										<div class="input-group">
											<input class="form-control" onchange="checkdates('endd')" type="datetime-local" id="endd" name="endd" /> 

										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 
									<label class="control-label col-md-3">Reported</label>
									<div class="col-md-9">
										<div class="input-group">
											<input type="date" size="16" name="reported_date" id="reported_date" class="form-control">

										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 margin-bottom-10" id="result"> 

								</div>
							</div>
						</div>
					</div> 		

				</div>
				<input type="hidden" name="olr_url" value="<?php echo $_SERVER['QUERY_STRING'];?>">
				<br><br>
			</div>
			<div class="modal-footer" id="footermode">
				<button type="button" class="btn default" onclick="window.close();">Cancel</button>
				<input type="submit" class="btn blue" value="Save">
			</div>
		</form>
	</body>
	<script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	
	<script src="metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
	<script type="text/javascript" src="metronic/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
	<script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>


	<script src="metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="<?php echo $url;?>js/notifications.js"></script>
    <script src="<?php echo $url;?>js/comments.js"></script> 
	<script>
         jQuery(document).ready(function() {    
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            ComponentsDropdowns.init();
           
         });
      </script>
      <script>

      	var ComponentsDropdowns = function () {

		    var handleSelect2 = function () {
		        $("#select2_sample5").select2({
		            tags: [<?php echo $mechanic_option2;?>]
		        });

		    }
		    return {
		        //main function to initiate the module
		        init: function () {            
		            handleSelect2();
		        }
		    };

		}();
      </script>
	<script>
		
         function hasValue(elem) {
             return $(elem).filter(function() { return $(this).val(); }).length > 0;
         }

         function dtypeChanged(){
            var dtype = $('#dtype').val();
               $('#rt1').hide();
               $('#rt2').hide();
            if(dtype == 1 || dtype == 2){               
               $('#rt'+dtype).fadeIn("slow",pulsate_now('#repairType'));
              
            }
         }

         function pulsate_now(x){
            $(x).pulsate({
               color: "#399bc3",
               repeat: false
            });
         }         

         $("#downtimeform :input").change(function() {

            if ($("#startd").val() != "" && $("#endd").val() != "" && $("#reported_date").val() != "" && $("#unit").val() != "" ){
               calculate();
            }
         });

         function calculate(){
         	$( "#result" ).html('');
            $.ajax({
               method: "POST",
               url: "ajax.php?act=calculate",
               data: { mechanics: $('#select2_sample5').val(), startd: $('#startd').val(), endd: $('#endd').val(), reported_date: $('#reported_date').val(), unit: $('#unit').val()}
            })
            .done(function( html ) {
               $( "#result" ).html( html );
            });
         }

         function checkdates(x){
            if(x=='startd'){    // Auto Change Reported Date           
               if($('#reported_date').val()==''){
                  var currentTime = new Date($('#startd').val());
                  var month = ("0" + (currentTime.getMonth()+1)).slice(-2);
                  var date = ("0" + currentTime.getDate()).slice(-2);
                  var year = currentTime.getFullYear();
                  //alert();
                  $('#reported_date').val(year + '-' + month + '-' + date);
                  //$('#reporoted_date').val(a.getDate());
               }
            }
            if ($("#startd").val() != "" && $("#endd").val() != ""){               
               var start = new Date($('#startd').val());
               var end = new Date($('#endd').val());
               if (start > end) {               
                  alert("End Date should be greater than Start Date!");
                  $("#"+x).val('');
               }
             
            }
         }
      </script>
</html>