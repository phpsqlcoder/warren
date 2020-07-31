<?php
   include("config.php");
   session_start();
   if(!$_SESSION['esdvms_username']){
   	header("location:login.php");
   }

   $mechanic_options = "<option value=''> - Select -</option>";
   $mechanic_qry = sqlsrv_query($conn,"select * from mechanics");
   while($mechanics = sqlsrv_fetch_array($mechanic_qry)){
      $mechanic_options.="<option value='".$mechanics['name']."'>".$mechanics['name']."</option>";
   }

   if(isset($_GET['delete'])){
   	$delete=sqlsrv_query($conn,"delete from downtime where id='".$_GET['id']."'");
   	$delete2=sqlsrv_query($conn,"delete from downtimeFlatData where downtimeId='".$_GET['id']."'");
   	header('location:index.php');
   }
   
   if(!isset($_GET['startDate'])){
   	$_GET['endDate']=date('Y-m-d');
   	$_GET['startDate']=date('Y-m-d',strtotime("-29 days"));
   	$_GET['datetype']="Weekly";	
   }
   if(!isset($_GET['s_equipment'])){
   	$_GET['s_equipment']='';
   }
   if(!isset($_GET['s_type'])){
   	$_GET['s_type']='';
   }
   if(!isset($_GET['s_id'])){
   	$_GET['s_id']='';
   }
   
   
   
   $cond="";
   if(strlen($_GET['s_equipment'])>1){
   	$cond.=" and equipment='".$_GET['s_equipment']."'";
   }
   if(strlen($_GET['s_type'])>1){
   	$cond.=" and type='".$_GET['s_type']."'";
   }
   if(strlen($_GET['s_id'])>1){
   	$cond.=" and id='".$_GET['s_id']."'";
   }
   
   function computemins($s,$e){	
   	$from_time = strtotime($s);
   	$to_time = strtotime($e);
   	return round(abs($to_time - $from_time) / 60,0);
   }
   //echo computemins("2016-12-06 10:45","2016-12-06 23:59");die();
   
   
   function interval( $startDate , $endDate, $type ){
      if($type=='Weekly'){
         if(date('D', strtotime($startDate)) === 'Mon') {
            $startDate=$startDate;
         }
         else{
            $startDate=date('Y-m-d', strtotime('last Monday', strtotime($startDate)));
         }
      }
      if($type=='Monthly'){
         if(date('j', strtotime($startDate)) === '1') {
            $startDate=$startDate;
         }
         else{
            $startDate=$startDate;
         }
      }
      $startDate = strtotime( $startDate );
      $endDate   = strtotime( $endDate );


      $currDate  = $startDate;
      $dayArray  = array();


      if($type=='Daily'){
         do{
            $dayArray[] = date( 'Y-m-d' , $currDate );
            $currDate = strtotime( '+1 day' , $currDate );
         } while( $currDate<=$endDate );
      }
      if($type=='Weekly'){
         do{
            $dayArray[] = date( 'Y-m-d' , $currDate );
            $currDate = strtotime( '+1 week' , $currDate );
         } while( $currDate<=$endDate );
      }
      if($type=='Monthly'){
         do{
            $dayArray[] = date( 'Y-m-d' , $currDate );
            $currDate = strtotime( '+1 month' , $currDate );
         } while( $currDate<=$endDate );
      }

      return $dayArray;
   }
   
   if(isset($_GET['act'])){
   	if($_GET['act']=='submitdowntime'){
         $mechanics = '';
   		for($x=1;$x<=$_POST['no_crew'];$x++){
            $mechanics.=$_POST['mechanic'.$x]."|";
         }
         $mechanics = rtrim($mechanics,"|");
         $_POST['startd'] = str_replace("T", " ", $_POST['startd']);
         $_POST['endd'] = str_replace("T", " ", $_POST['endd']);
   		$insert="INSERT INTO [dbo].[downtime]
           ([dateStart]
           ,[dateEnd]
           ,[remarks]
           ,[addedBy]
           ,[addedDate]
           ,[unitId]
           ,[isScheduled]
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
           )
           VALUES(
           '".$_POST['startd']."','".$_POST['endd']."','".$_POST['remarks']."','".$_SESSION['esdvms_username']."','".date('Y-m-d h:i:s')."','".$_POST['unit']."','".$_POST['dtype']."',
           '".$_POST['work_order']."','".$mechanics."','".$_POST['repairType']."','".$_POST['work_details']."','".$_POST['reported_date']."','".$_POST['status']."'
           ,'".$_POST['from12']."','".$_POST['from7']."','".$_POST['trepair_days']."','".$_POST['trepair_hours']."','".$_POST['shop_days']."','".$_POST['shop_hours']."','".$_POST['man_hours']."','".$_POST['required_daily_availability']."','".$_POST['downtime']."'
            )";
           
   		$resource=sqlsrv_query($conn, $insert);    	
   
   	}
   	if($_GET['act']=='newunit'){
   		$insert_unit=sqlsrv_query($conn,"insert into unit (location,brand,model,type,equipment,plateNo,engineNo,chassisNo,odometer,avNo,driver,color) 
            VALUES ('".$_POST['alocation']."','".$_POST['abrand']."','".$_POST['amodel']."','".$_POST['atype']."','".$_POST['aequipment']."','".$_POST['aplate']."','".$_POST['aengine']."','".$_POST['achassis']."','".$_POST['aodometer']."','".$_POST['aav']."','".$_POST['adriver']."','".$_POST['acolor']."')");
   	}
   	//header("location:index.php?msg=success&".$_POST['olr_url']);
   }

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
      <link href="google.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
      <!-- END GLOBAL MANDATORY STYLES -->
      <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
      <link href="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
      <link href="metronic/assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/clockface/css/clockface.css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
      <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>
      <link href="metronic/assets/global/plugins/nouislider/jquery.nouislider.css" rel="stylesheet" type="text/css"/>
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
         #dashboard_div {padding-left:340px; }
         #dashboard_div	table { border-collapse:separate;
         /*border-top: 3px solid; */
         }
         #dashboard_div    td, th {
         margin:0;
         /*  border:3px solid grey;
         border-top-width:0px;*/
         white-space:nowrap;
         }
         
         #dashboard_div   .headcol {
         position:absolute;
         width:28em;
         left:28px;
         top:auto;
         border-right: 0px none;
         /* border-top-width:3px; 
         margin-top:-3px; compensate for top border*/
         background-color: white;
         }
         #dashboard_div    .headcol:before {content:'';}
         #dashboard_div    .long { background:yellow; letter-spacing:1em; }
      </style>
   </head>
   <body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
      <!-- BEGIN HEADER -->
      <?php include("header.php");?>
      <div class="clearfix"></div>
      <!-- BEGIN CONTAINER -->
      <div class="page-container">
         <!-- BEGIN CONTENT -->
         <div class="page-content-wrapper">
            <div class="page-content">
               <?php if(isset($_GET['msg'])) {
                  echo '<div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Success!</strong> New Unit has been added.
                     </div>';
               }?>
               <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
               <div class="modal fade bs-modal-lg" id="inputdowntime" tabindex="-1" role="inputdowntime" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                        <form method="post" id="downtimeform" action="index.php?act=submitdowntime">
                           <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                              <h4 class="modal-title">Input Downtime Details</h4>
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
                                       <div class="col-md-12">                                
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
                                       <div class="col-md-12">   
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
                                                <select class="form-control" name="repairType" id="repairType">
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
                                                <select class="form-control" name="repairType" id="repairType">
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
                                             <textarea class="form-control" rows="5" name="remarks" placeholder="Remarks"></textarea>                      
                                          </div>

                                       </div>
                                       </div>

                                       <div class="row">
                                       <div class="col-md-12 margin-bottom-10"> 

                                          <label class="control-label col-md-3">No. Crew:</label>
                                          <div class="col-md-9">
                                                <input type="number" min="0" value="0" size="16" name="no_crew" id="no_crew" class="form-control" onchange="crewChanged();">             
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
                           </div>
                           <div class="modal-footer" id="footermode">
                              <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                              <input type="submit" class="btn blue" value="Save">
                           </div>
                        </form>
                     </div>
                     <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
               </div>
               <!-- /.modal -->
               <div class="modal fade bs-modal-lg" id="munit" tabindex="-1" role="munit" aria-hidden="true">
                  <div class="modal-dialog">
                     <form method="post" action="index.php?act=newunit">
                        <div class="modal-content">
                           <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                              <h4 class="modal-title">Add New Unit</h4>
                           </div>
                           <?php
	                           $brandlist = '';
	                           $brandq = sqlsrv_query($conn,"select distinct brand from unit order by brand");
	                           while($brandr = sqlsrv_fetch_array($brandq)){
	                           		$brandlist .= '<li><a href="#" onclick=\'$("#abrand").val("'.$brandr['brand'].'")\'>'.$brandr['brand'].'</a></li>';
	                           }

	                           $locationlist = '';
	                           $locationq = sqlsrv_query($conn,"select distinct location from unit order by location");
	                           while($locationr = sqlsrv_fetch_array($locationq)){
	                           		$locationlist .= '<li><a href="#" onclick=\'$("#alocation").val("'.$locationr['location'].'")\'>'.$locationr['location'].'</a></li>';
	                           }

                              $typelist = '';
                              $typeq = sqlsrv_query($conn,"select distinct type from unit order by type");
                              while($typer = sqlsrv_fetch_array($typeq)){
                                    $typelist .= '<li><a href="#" onclick=\'$("#atype").val("'.$typer['type'].'")\'>'.$typer['type'].'</a></li>';
                              }
                           ?>
                           <div class="modal-body">                          
									<div class="form-body">										
										<div class="form-group">
			                        <label class="col-md-3 control-label">Location</label>
											<div class="input-group col-md-9">
												<div class="input-group-btn">
													<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown">Select <i class="fa fa-angle-down"></i></button>
													<ul class="dropdown-menu" style="max-height: 250px;overflow-y:scroll;">
														<?php echo $locationlist; ?>
													</ul>
												</div>								
												<input type="text" name="alocation" id="alocation" class="form-control" placeholder=" Or Enter new Location">
											</div>
										</div>

										<div class="form-group">
			                        <label class="col-md-3 control-label">Brand</label>
											<div class="input-group col-md-9">
												<div class="input-group-btn">
													<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown">Select <i class="fa fa-angle-down"></i></button>
													<ul class="dropdown-menu" style="max-height: 250px;overflow-y:scroll;">
														<?php echo $brandlist; ?>
													</ul>
												</div>								
												<input type="text" name="abrand" id="abrand" class="form-control" placeholder=" Or Enter new Brand">
											</div>
										</div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">Equipment</label>
                                 <div class="input-group col-md-9">
                                    <select name="aequipment" id="aequipment" required="required" class="form-control">
                                       <option value="">- Select Equipment -</option>
                                       <option value="Motorcycle">Motorcycle</option>
                                       <option value="Light Vehicle">Light Vehicle</option>
                                       <option value="Medium Vehicle">Medium Vehicle</option>
                                       <option value="Heavy Equipment">Heavy Equipment</option>                                       
                                    </select> 
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">Type</label>
                                 <div class="input-group col-md-9">
                                    <div class="input-group-btn">
                                       <button type="button" class="btn green dropdown-toggle" data-toggle="dropdown">Select <i class="fa fa-angle-down"></i></button>
                                       <ul class="dropdown-menu" style="max-height: 250px;overflow-y:scroll;">
                                          <?php echo $typelist; ?>
                                       </ul>
                                    </div>                        
                                    <input type="text" name="atype" id="atype" class="form-control" placeholder=" Or Enter new Type">
                                 </div>
                              </div>
										<div class="form-group">
											<label class="col-md-3 control-label">Model</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="amodel" id="amodel" placeholder="Model">
											</div>
										</div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">Plate no.</label>
                                 <div class="col-md-9">
                                    <input type="text" class="form-control" name="aplate" id="aplate" placeholder="Plate number">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">Engine no.</label>
                                 <div class="col-md-9">
                                    <input type="text" class="form-control" name="aengine" id="aengine" placeholder="Engine number">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">Chassis no.</label>
                                 <div class="col-md-9">
                                    <input type="text" class="form-control" name="achassis" id="achassis" placeholder="Chassis number">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">AV no.</label>
                                 <div class="col-md-9">
                                    <input type="text" class="form-control" name="aav" id="aav" placeholder="AV number">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">Color</label>
                                 <div class="col-md-9">
                                    <input type="text" class="form-control" name="acolor" id="acolor" placeholder="Color">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">Odometer</label>
                                 <div class="col-md-9">
                                    <input type="text" class="form-control" name="aodometer" id="aodometer" placeholder="Odometer">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label">Driver</label>
                                 <div class="col-md-9">
                                    <input type="text" class="form-control" name="adriver" id="adriver" placeholder="Driver">
                                 </div>
                              </div>
									</div>			
                              <input type="hidden" name="olr_url" value="<?php echo $_SERVER['QUERY_STRING'];?>">
                              <br>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                              <input type="submit" class="btn blue" value="Save">
                           </div>
                        </div>                      
                     </form>
                  </div>
                  <!-- /.modal-dialog -->
               </div>
               <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
               <!-- /.modal -->
               <!-- BEGIN PAGE HEADER-->
               <form method="get" act="index.php">
                  <div class="row">
                     <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title">
                           Vehicle <small>Downtime Records</small>                          
                           <div class="pull-right">
                              <a class="btn yellow" data-toggle="modal" href="#inputdowntime">Add Downtime</a>                              
                              <a href="downtime_list.php" class="btn purple">Downtime List</a>
                              <a href="" class="btn green hide" data-toggle="modal" href="#tunit">Add Target</a>
                           </div>   
                        </h3>
                        <ul class="page-breadcrumb breadcrumb">
                           <li>							
                              <a href="#">Filters:</a>							
                           </li>
                           <li>			
                              <input type="hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
                              <input type="hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">		
                              <input type="hidden" name="datetype" value="<?php echo $_GET['datetype'];?>">						
                              <select class="form-control input-sm" name="s_equipment" id="s_equipment" onchange="changefilters();">
                              <?php
                                 echo '<option value="" selected="selected"> - Equipment -';
                                 $uq=sqlsrv_query($conn,"select distinct equipment from unit order by equipment");
                                 while($u=sqlsrv_fetch_array($uq)){
                                 $select='';
                                 if(isset($_GET['s_equipment'])){
                                 	if($u['equipment']==$_GET['s_equipment']){
                                 		$select=' selected="selected"';
                                 	}
                                 }
                                 echo '<option value="'.$u['equipment'].'" '.$select.'>'.$u['equipment'];				 						
                                 }
                                 
                                 ?>
                              </select>							
                           </li>
                           <li id="typelist">							
                              <select class="form-control input-sm" name="s_type" id="s_type" onchange="changefilters();">
                              <?php
                                 echo '<option value="" selected="selected"> - Type -';
                                 $uq=sqlsrv_query($conn,"select distinct type from unit order by type");
                                 while($u=sqlsrv_fetch_array($uq)){
                                 $select='';
                                 if(isset($_GET['s_type'])){
                                 	if($u['type']==$_GET['s_type'] && $u['type']!=''){
                                 		$select=' selected="selected"';
                                 	}
                                 }
                                 echo '<option value="'.$u['type'].'" '.$select.'>'.$u['type'];
                                 }
                                 
                                 ?>
                              </select>				 											
                           </li>
                           <li id="unitlist">
                              <select class="form-control input-sm" name="s_id" id="s_id">
                              <?php
                                 echo '<option value="" selected="selected"> - Vehicle -';
                                 $uq=sqlsrv_query($conn,"select distinct id,brand,model,plateNo,location from unit order by brand,model,plateNo,location");
                                 while($u=sqlsrv_fetch_array($uq)){
                                 $select='';
                                 if(isset($_GET['s_id'])){
                                 	if($u['id']==$_GET['s_id']){
                                 		$select=' selected="selected"';
                                 	}
                                 }
                                 echo '<option value="'.$u['id'].'" '.$select.'>'.$u['brand'].' '.$u['model'].' '.$u['plateNo'].' '.$u['location'];
                                 }
                                 
                                 ?>
                              </select>
                           </li>
                           <li>
                              <input type="submit" class="btn green btn-sm" value="Go">
                              <a href="index.php" class="btn purple btn-sm" style="color:white;">Reset</a>
                           </li>
                           <li class="pull-right" style="position:relative;top:5px;">
                              <div id="dashboard-report-range" class="dashboard-date-range tooltips" data-placement="top" data-original-title="Change dashboard date range">
                                 <i class="icon-calendar"></i>
                                 <span></span>
                                 <i class="fa fa-angle-down"></i>
                              </div>
                           </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                     </div>
                  </div>
               </form>
               <input type="hidden" name="hiddenstart" id="hiddenstart" value="<?php echo $_GET['startDate'];?>">
               <input type="hidden" name="hiddenend" id="hiddenend" value="<?php echo $_GET['endDate'];?>">
               <input type="hidden" name="hiddens_equipment" id="hiddens_equipment" value="<?php echo $_GET['s_equipment'];?>">
               <input type="hidden" name="hiddens_id" id="hiddens_id" value="<?php echo $_GET['s_id'];?>">
               <input type="hidden" name="hiddens_type" id="hiddens_type" value="<?php echo $_GET['s_type'];?>">
                         
              
              
               <div class="clearfix">
               </div>
               <div class="row ">
                  <div class="col-md-12 col-sm-12">
                     <div class="portlet box blue-steel">
                        <div class="portlet-title">
                           <div class="caption">
                              <i class="fa fa-bell-o"></i>Recent Downtime Logs
                           </div>
                        </div>
                        <div class="portlet-body">
                           <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                              <ul class="feeds">
                                 <table class="table">
                                    <thead>
                                       <tr>
                                          <th>#</th>
                                          <th>Unit</th>
                                          <th>Start</th>
                                          <th>End</th>
                                       
                                          <th>Edit</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php 
                                          $ldata='';
                                          $seq = 0;
                                          $lt=sqlsrv_query($conn,"select count(d.id) as totald from downtime d");
                                          $seq=$lt['totald'];
                                          $lq=sqlsrv_query($conn,"select CONVERT(VARCHAR(19),d.dateStart) as ds,CONVERT(VARCHAR(19),d.dateEnd) as de,d.*,u.name as uni,u.type                                      
                                          	from downtime d left join unit u on u.id=d.unitId order by d.id desc");
                                          while($l=sqlsrv_fetch_array($lq)){	
                                             $seq++;										
                                          	echo '
                                          		<tr>
                                          			<td>'.$seq.'</td>
                                          			<td>'.$l['uni'].' ('.$l['type'].')</td>
                                          			<td>'.$l['ds'].'</td>
                                          			<td>'.$l['de'].'</td>
                                          			
                                          			<td style="width:100px;"><a href="#" class="btn purple btn-sm" onclick=\'window.open("downtime_edit.php?id='.$l['id'].'","displayWindow","toolbar=no,scrollbars=yes,width=910,height=600"); return false;\';><i class="fa fa-edit"></i></a>
                                          				<a href="#" class="btn red btn-sm deletedl" data="'.$l['id'].'"><i class="fa fa-minus-circle"></i></a>
                                          			</td>
                                          		</tr>
                                          	';
                                          	//$seq--;
                                          }
                                          ?>
                                    </tbody>
                                 </table>
                              </ul>
                           </div>
                           <div class="scroller-footer">
                              <div class="btn-arrow-link pull-right">
                                 <a href="downtime_list.php" target="_blank">See All Records</a>
                                 <i class="icon-arrow-right"></i>
                              </div>
                           </div>
                        </div>
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
            <?php echo date('Y'); ?> &copy; PMC - ICT.
         </div>
         <div class="page-footer-tools">
            <span class="go-top">
            <i class="fa fa-angle-up"></i>
            </span>
         </div>
      </div>
   <!-- Scripts -->
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
      <!-- END CORE PLUGINS -->
      <!-- BEGIN PAGE LEVEL PLUGINS -->
      <script src="metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
      <script src="metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
      <script src="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
      <script src="metronic/assets/global/plugins/gritter/js/jquery.gritter.js" type="text/javascript"></script>
      <!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
      <script src="metronic/assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
      <script src="metronic/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.js" type="text/javascript"></script>
      <script src="metronic/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
      <script src="metronic/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/clockface/js/clockface.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
      <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
      <script src="metronic/assets/global/plugins/nouislider/jquery.nouislider.min.js"></script>
      <!-- END PAGE LEVEL PLUGINS -->
      <!-- BEGIN PAGE LEVEL SCRIPTS -->
      <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
      <script src="metronic/assets/admin/pages/scripts/components-pickers.js"></script>
   <!-- Scripts -->
      <!-- 
         <script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
         	END PAGE LEVEL SCRIPTS -->
      <script>
         jQuery(document).ready(function() {    
           Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            Index.init();
            Index.initDashboardDaterange();   
            ComponentsPickers.init();
            
         });
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
         function crewChanged(){
            $('#crewdiv').html('');
            var crews = $('#no_crew').val();
            for(x=1;x<=crews;x++){
               $('#crewdiv').append("<div style='color:black;font-size:12px;font-weight:bold;' class='col-md-12 pull-right'>Mechanic "+x+": <select class='form-control' name='mechanic"+x+"'><?php echo $mechanic_options;?></select></div>");
            }
         }

         $("#downtimeform :input").change(function() {
            if ($("#no_crew").val() > 0 && $("#startd").val() != "" && $("#endd").val() != "" && $("#reported_date").val() != "" && $("#unit").val() != "" ){
               calculate();
            }
         });

         function calculate(){
            $.ajax({
               method: "POST",
               url: "ajax.php?act=calculate",
               data: { no_crew: $('#no_crew').val(), startd: $('#startd').val(), endd: $('#endd').val(), reported_date: $('#reported_date').val(), unit: $('#unit').val()}
            })
            .done(function( html ) {
               $( "#result" ).html( html );
            });
         }

         function checkdates(x){
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