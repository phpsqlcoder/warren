<?php
include("config.php");
session_start();
$id=$_GET['id'];

$mechanic_options = "<option value=''> - Select -</option>";
$mechanic_option2 = '';
$mechanic_qry = sqlsrv_query($conn,"select * from mechanics");
while($mechanics = sqlsrv_fetch_array($mechanic_qry)){
	$mechanic_options.="<option value='".$mechanics['name']."'>".$mechanics['name']."</option>";
	$mechanic_option2.='"'.$mechanics['name'].'",';
}
$mechanic_option2 = rtrim($mechanic_option2,',');

if(isset($_GET['act'])){
	if($_GET['act']=='editdowntime'){
		
        $mechanics = str_replace(",", "|", $_POST['mechanics']);
         //die($_POST['work_order']);
        $_POST['startd'] = str_replace("T", " ", $_POST['startd']);
        $_POST['endd'] = str_replace("T", " ", $_POST['endd']);

        $repair_type = ($_POST['dtype']==1 ? $_POST['repairType1'] : $_POST['repairType2']);

		$upd=sqlsrv_query($conn,"update downtime set unitId = '".$_POST['unit']."', 
			dateStart= '".$_POST['startd']."',
			dateEnd='".$_POST['endd']."',
			remarks='".$_POST['remarks']."',
			workOrder='".htmlentities($_POST['work_order'])."',
			isScheduled='".$_POST['dtype']."',
			mechanics='".$mechanics."',
			repairType='".$repair_type."',
			workDetails='".htmlentities($_POST['work_details'])."',
			reportedDate='".$_POST['reported_date']."',
			status='".$_POST['status']."',
			from12='".$_POST['from12']."',
			from7='".$_POST['from7']."',
			trepair_days='".$_POST['trepair_days']."',
			trepair_hours='".$_POST['trepair_hours']."',
			shop_days='".$_POST['shop_days']."',
			shop_hours='".$_POST['shop_hours']."',
			man_hours='".$_POST['man_hours']."',
			required_daily_availability='".$_POST['required_daily_availability']."',
			tdowntime='".$_POST['downtime']."',
			assignedTo='".$_POST['assigned_to']."',
			downtimeCategory='".$_POST['dcategory']."'
			where id='".$id."'");

		echo '<div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <span class="fa fa-check-square"></span><strong> Success!</strong> Record has been edited.
             </div>';
		
	}
	
}
$r=sqlsrv_fetch_array(sqlsrv_query($conn,"select convert(varchar, reportedDate, 120)  as rd, CONVERT(VARCHAR(16),dateStart,120) as ds,CONVERT(VARCHAR(16),dateEnd,120) as de,* from downtime where id='".$id."'"));
$crews = str_replace("|", ",", $r['mechanics']); 
//echo $r['mechanics']." - ".count($crews);
?>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8"/>
	<title>ESD | Monitoring</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
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

	<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/clockface/css/clockface.css"/>
	<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
	<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
	<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
	<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
	<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>

	<!-- END PAGE LEVEL PLUGIN STYLES -->
	<!-- BEGIN PAGE STYLES -->
	<link href="metronic/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE STYLES -->
	<!-- BEGIN THEME STYLES -->
	<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
	<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
	<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>


	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico"/>
	<style>
	.popover-title {
		color: black;

	}
	.popover-content {
		color: black;

	}
</style>
</head>

<body style="background-color:white;">
	<div class="page-container">
		<form method="post" id="downtimeform" action="downtime_edit.php?act=editdowntime&id=<?php echo $id;?>">
			<div class="modal-header">
				<h4 class="modal-title"><b>Update Downtime Details</b></h4>
			</div>
			<div class="modal-body">
				<div class="form-group" style="height:600px">
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
												$select='';				 						
												if($u['id']==$r['unitId']){
													$select=' selected="selected"';
												}	
												echo '<option value="'.$u['id'].'" '.$select.'>'.$u['type'].' '.$u['name'];
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
										<input type="text" size="16" name="work_order" id="work_order" class="form-control" value="workOrder">
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
												$select='';				 						
												if($u['name']==$r['assignedTo']){
													$select=' selected="selected"';
												}	
												echo '<option value="'.$u['name'].'" '.$select.'>'.$u['name'];
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
											<option value=""> - Select Type -</option>
											<option value="ACCIDENT" <?php echo ($r['downtimeCategory'] == 'ACCIDENT' ? 'selected="selected"':'')?>>Accident</option>
											<option value="CORRECTIVE MAINTENANCE" <?php echo ($r['downtimeCategory'] == 'CORRECTIVE MAINTENANCE' ? 'selected="selected"':'')?>>Corrective Maintenance</option>
											<option value="PREVENTIVE MAINTENANCE" <?php echo ($r['downtimeCategory'] == 'PREVENTIVE MAINTENANCE' ? 'selected="selected"':'')?>>Preventive Maintenance</option>                                                                     
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
											<option value="1" <?php echo ($r['isScheduled']==1 ? 'selected="selected"':'')?>>Scheduled Downtime (Corrective/PM)</option>
											<option value="2" <?php echo ($r['isScheduled']==2 ? 'selected="selected"':'')?>>Unscheduled Downtime (Breakdown)</option>                                                                     
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div id="rt1" style="<?php echo ($r['isScheduled']==1 ? '':'display:none;')?>">
										<label class="control-label col-md-3">Repair Type <?php echo $r['repairType'];?></label>
										<div class="col-md-9">
											<select class="form-control" name="repairType1" id="repairType">
												<option value=""> - Select Type -</option>
												<option value="Inspections" <?php echo ($r['repairType']=='Inspections' ? 'selected="selected"':'')?>>Inspections</option>
												<option value="Repair and Replace" <?php echo ($r['repairType']=='Repair and Replace' ? 'selected="selected"':'')?>>Repair and Replace</option>
												<option value="Service and Lube" <?php echo ($r['repairType']=='Service and Lube' ? 'selected="selected"':'')?>>Service and Lube</option>                                      
											</select>
										</div>                                       
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">

									<div id="rt2" style="<?php echo ($r['isScheduled']==2 ? '':'display:none;')?>">
										<label class="control-label col-md-3">Repair Type</label>
										<div class="col-md-9">
											<select class="form-control" name="repairType2" id="repairType">
												<option value=""> - Select Type -</option>
												<option value="Brake System" <?php echo ($r['repairType']=='Brake System' ? 'selected="selected"':'')?>>Brake System</option>
												<option value="Clutch System" <?php echo ($r['repairType']=='Clutch System' ? 'selected="selected"':'')?>>Clutch System</option>
												<option value="Engine System" <?php echo ($r['repairType']=='Engine System' ? 'selected="selected"':'')?>>Engine System</option> 
												<option value="Primary Function" <?php echo ($r['repairType']=='Primary Function' ? 'selected="selected"':'')?>>Primary Function</option>
												<option value="Transmission System" <?php echo ($r['repairType']=='Transmission System' ? 'selected="selected"':'')?>>Transmission System</option>                                     
											</select>
										</div>                                          
									</div>
								</div>
							</div>



							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 

									<label class="control-label col-md-3">Work Details:</label>
									<div class="col-md-9">
										<textarea class="form-control" rows="5" name="work_details" placeholder="Work Details"><?php echo $r['workDetails']; ?></textarea>                       
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 
									<label class="control-label col-md-3">Remarks:</label>
									<div class="col-md-9">
										<textarea class="form-control" rows="5" name="remarks" placeholder="Remarks"><?php echo $r['remarks']; ?></textarea>                      
									</div>

								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 

									<label class="control-label col-md-3">Mechanics:</label>
									<div class="col-md-9">
										<input type="hidden" id="select2_sample5" name="mechanics" class="form-control select2" value="<?php echo $crews;?>">            
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
												$select='';				 						
												if($u['status']==$r['status']){
													$select=' selected="selected"';
												}
												echo '<option value="'.$u['status'].'" '.$select.'>'.$u['status'];
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
											<input class="form-control" onchange="checkdates('startd')" 
											value="<?php echo date('Y-m-d',strtotime($r['ds']))."T".date('H:i',strtotime($r['ds'])); ?>" type="datetime-local" id="startd" name="startd" />
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 
									<label class="control-label col-md-3">End</label>
									<div class="col-md-9">
										<div class="input-group">
											<input class="form-control" onchange="checkdates('endd')" value="<?php echo date('Y-m-d',strtotime($r['de']))."T".date('H:i',strtotime($r['de'])); ?>" type="datetime-local" id="endd" name="endd" /> 
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 margin-bottom-10"> 
									<label class="control-label col-md-3">Reported</label>
									<div class="col-md-9">
										<div class="input-group">
											<input type="date" size="16" name="reported_date" value="<?php echo date('Y-m-d',strtotime($r['rd'])); ?>" id="reported_date" class="form-control">
											
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

			</div>
			<div class="modal-footer" id="footermode">
				<button type="button" class="btn default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn blue" value="Update">
			</div>
		</form>
		
	</div>
</body>
<script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>

<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script src="<?php echo $url;?>metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="<?php echo $url;?>js/notifications.js"></script>
<script src="<?php echo $url;?>js/comments.js"></script> 

<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
END PAGE LEVEL SCRIPTS -->
<script>
	jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   ComponentsDropdowns.init();
   <?php
   if($r['from12']>0){
   	echo 'calculate();';
   }
   ?>
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
               $('#rt'+dtype).show();
            }
         }


         $("#downtimeform :input").change(function() {
            if ($("#startd").val() != "" && $("#endd").val() != "" && $("#reported_date").val() != "" && $("#unit").val() != "" ){
               calculate();
            }
         });

         function calculate(){
            $.ajax({
               method: "POST",
               url: "ajax.php?act=calculate",
               data: { mechanics: $('#select2_sample5').val(), startd: $('#startd').val(), endd: $('#endd').val(), reported_date: $('#reported_date').val(), unit: $('#unit').val()}
            })
            .done(function( html ) {
               $( "#result" ).html( html );
            });
         }

        $("#add_mechanic").click(function(e){
        	e.preventDefault();
		    var total = $('#no_crew').val();
         	var x = parseInt(total) + 1;
         	$('#crewdiv').append("<div style='color:black;font-size:12px;font-weight:bold;' class='col-md-12 pull-right'>Mechanic "+x+": <select class='form-control mechanic' name='mechanic"+x+"' onchange='calculate();' required><?php echo $mechanic_options;?></select></div>");
         	$('#no_crew').val(x);
         	calculate();
		});

		$('.mechanic').change(function(){
			calculate();
		});



         

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
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>