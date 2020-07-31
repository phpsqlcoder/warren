<?php
include('../config.php');
include('functions.php');

if(isset($_POST['dispatch'])){
   $id = $_POST['id'];
   $r = get_details_of_selected_dispatch($id);
   $d = get_dispatch_details($r['request_id']);

   $driver = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from drivers where id='".$r['driver_id']."'"));

   $trip = $r['tripTicket'];

   $dest = $r['destination'];
   $ex            = explode('|',$dest);
   $origin        = $ex[0];
   $destination   = $ex[1];

   $readonly  = ($r['isPrinted'] == 1) ? 'readonly' : '' ;
   $isPrinted = ($r['isPrinted'] == 1) ? '' : 'readonly';

$history = sqlsrv_fetch_array(sqlsrv_query($conn,"select top 1 odometer_end from dispatch where unitId='".$r['unitId']."' and Status = 'Completed' order by id desc"));
}

 $available_units = '<option value="'.$r['id'].'|'.$r['type'].'">'.$r['type'].'</option>';
            $unavailable_units = '';
            $in_used_units = '';
            $units = sqlsrv_query($conn,"select * from unit");
            while($unit = sqlsrv_fetch_array($units)){

               $check_if_down = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from downtime where status<>'CANCELLED' and unitId='".$unit['id']."'
                  and '".$d['date_needed']->format('Y-m-d h:i:s')."' between dateStart and dateEnd
                  "));
             

               if($check_if_down['id']){
                  $unavailable_units .= '<option disabled value="'.$unit['id'].'|'.$unit['name'].'">'.$unit['name'].' - '.$check_if_down['repairType'].'</option>';
               }
               else {
                  $check_if_no_booking = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from dispatch where status not in ('Cancelled','Completed','Closed') and unitId='".$unit['id']."'
                  and '".$d['date_needed']->format('Y-m-d h:i:s')."' between dateStart and ISNULL(dateEnd,DATEADD(hour, ".$hour_allowance.", dateStart))
                  "));                                                     
                
                  if($check_if_no_booking['id']){
                     $in_used_units .= '<option disabled value="'.$unit['id'].'|'.$unit['name'].'">'.$unit['name'].'  ('.$check_if_no_booking['tripTicket'].')</option>';
                  }
                  else{       
                     $hhh = sqlsrv_fetch_array(sqlsrv_query($conn,"select top 1 odometer_end from dispatch where unitId='".$unit['id']."' and id<>'".$r['id']."' and Status<>'Cancelled' order by odometer_end desc"));                                               
                     $available_units .= '<option value="'.$unit['id'].'|'.$unit['name'].'|'.$unit['vehicle_code'].'" title="'.$hhh['odometer_end'].'">'.$unit['name'].'</option>';
                  }
               }
               
            }
                                             
?>

<form action="" method="post" id="ddform">
   <div class="form-group col-md-12">
      <div class="col-md-12">
         <div style="height:45px;;" class="alert alert-success"><center><strong>Trip Ticket Form <?php echo $r['tripTicket'];?></strong></center></div>
      </div>
   </div>

   <div class="form-group col-md-12">
      <input type="hidden" name="requestor" value="<?= $d['name']; ?>">
     <input type="hidden" name="last_odo" id="last_odo" value="<?= $history['odometer_end']; ?>">

      <div class="col-md-4">
         <label class="control-label">Date Out<i class="font-red"> *</i></label>
         <?php 
            if ($r['isPrinted'] == 1) {
                ?>
                <input readonly type="text" class="form-control" value="<?= $r['dateStart']->format('Y-m-d h:i:s'); ?>">
                    <?php }
            else { ?>
               <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="date_out">
                  <div class="input-icon">
                     <i class="fa fa-calendar font-yellow"></i>
                     <input required class="form-control" size="16" type="text" value="<?= $r['dateStart']->format('Y-m-d h:i:s'); ?>" readonly>
                  </div>
                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                  <input type="hidden" name="date_out" id="date_out" value="<?= $r['dateStart']->format('Y-m-d h:i:s'); ?>" />
               </div>
            <?php }
         ?>
         
      </div>

      <div class="col-md-4">
         <label class="control-label">Date Needed<i class="font-red"> *</i></label>
         <input required type="text" name="app_date" class="form-control" id="dt_from" value="<?= $r['addedDate']->format('Y-m-d h:i:s'); ?>" readonly  />
      </div>

      <div class="col-md-4">
         <label class="control-label">Department<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-building-o font-yellow"></i>
            <input <?= $readonly; ?> id="dept" required type="text" class="form-control" name="deparment" value="<?= $r['deptId']; ?>" >
         </div>
      </div>

     
   </div>

   <div class="form-group col-md-12">
      
       <div class="col-md-4">
         <label class="control-label">Vehicle<i class="font-red"> *</i></label>
         <?php 
            if ($r['isPrinted'] == 1) {
                ?>
                  <input readonly type="text" class="form-control" value="<?= $r['type']; ?>">
                  <input readonly type="hidden" name="vehicle" class="form-control" value="<?= $r['unitId']; ?>|<?= $r['type']; ?>">
                    <?php }
            else { ?>
                <select required="required" name="vehicle" id="vcostcode" class="bs-select form-control" onchange="update_vehiclecostcode();">
                   <optgroup label="Available"><?php echo $available_units; ?></optgroup>
                   <optgroup label="In-use"><?php echo $in_used_units; ?></optgroup>
                   <optgroup label="Unavailable"><?php echo $unavailable_units; ?></optgroup>
                </select>
            <?php }
         ?>
      </div>


      <input type="text" class="form-control" name="ticket_no" value="<?= $r['tripTicket']; ?>" readonly style="display:none;">
   
      <div class="col-md-4">
         <label class="control-label">Vehicle Cost Code<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-tachometer font-yellow"></i>
            <input required type="text" class="form-control" name="cost_code" id="cost_code" value="<?= $r['vehicle_cost_code']; ?>">
         </div>
      </div>

      <div class="col-md-4">
         <label class="control-label">Driver<i class="font-red"> *</i></label>
         <?php 
            if ($r['isPrinted'] == 1) {
                ?>
                <input type="text" class="form-control" value="<?php echo $driver['driver_name']?>" readonly>
                    <?php }
            else { ?>
               <select required name= "driver" class="form-control">
                    <option>-- Select Driver --</option> 
                  <?php                  
                  $count = 0;
                  $result = sqlsrv_query($conn,"SELECT * FROM drivers where isActive is null OR isActive = 1 order by type");
                  while ($drow = sqlsrv_fetch_array($result)){
                     $count++; ?>
                     <?php if ($count > 0) {
                        $location = '';
                        if(strlen($drow['type'])>1){
                           $location = '('.$drow['type'].')';
                        }
                        ?>
                      
                        <option value="<?php echo $drow['id']; ?>" <?php if($r['driver_id']==$drow['id']) echo 'selected="selected"'; ?>><?php echo $drow['driver_name']." ".$location; ?></option>
                        <?php
                     } else {
                        ?>
                       
                        <?php
                     } 
                     ?>      
                  <?php } ?>
               </select>
            <?php }
         ?>
         
      </div>

      
   </div>

   <div class="form-group col-md-12">
      <div class="col-md-4">
         <label class="control-label">From<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-globe font-yellow"></i>
            <input <?= $readonly; ?> required type="text" class="form-control" name="origin" value="<?= $origin; ?>"> 
         </div>
      </div>

      <div class="col-md-4">
         <label class="control-label">To<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-globe font-yellow"></i>
            <input <?= $readonly; ?> required type="text" class="form-control" name="destination" value="<?= $destination; ?>"> 
         </div>
      </div>

      <div class="col-md-4">
         <label class="control-label">Odometer Start<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-tachometer font-yellow"></i>
            <input type="number" class="form-control text-right" name="odom_start" id="odom_start" value="<?= number_format($r['odometer_start'],2,'.',''); ?>">
            <span class="help-block" id="odo_help">Previous trip odometer: <?= number_format($history['odometer_end'],2); ?></span>
         </div>
      </div>
   </div>
   <div class="col-md-12">
      <div class="col-md-4">
         <div class="form-group multiple-form-group">
            <label>Passengers</label>
               <?php 
                  $ex =  explode('|',$r['passengers']);
                  foreach($ex as $pass) {
                     if($pass<>''){
                     // echo '<div class="form-group input-group input-icon">
                     // <i class="fa fa-user font-yellow"></i>
                     // <input type="text" class="form-control" name="passengers[]" value="'.$pass.'">
                     // <span class="input-group-btn"><button type="button" class="btn btn-danger btn-remove">Remove
                     // </button></span>
                     // </div>';
                        echo '<div class="alert alert-warning alert-dismissable">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                              '.$pass.'
                              <input type="hidden" class="form-control" name="passengers[]" value="'.$pass.'">
                           </div>';
                     }
                  }
               ?>
               <div class="form-group input-group input-icon">
                  <i class="fa fa-user font-yellow"></i>
                  <input type="text" class="form-control" name="passengers[]" placeholder="Passengers">
                  <span class="input-group-btn"><button type="button" class="btn btn-primary btn-add"><i class="fa fa-plus"></i>
                  </button></span>
               </div>
         </div>
      </div>

      <div class="col-md-8">
         <label class="control-label">Purpose<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-comment-o font-yellow"></i>
            <textarea <?= $readonly; ?> required name="purpose" class="form-control"><?php echo $r['purpose']; ?></textarea>
            
         </div>

      </div>
   </div>
   <div style="text-align: right; margin-top: 10px;" class="col-md-12">
      <input type="hidden" name="dispatch_edit">

      <button class="btn green" type="button" onclick="formsubmit();">
         <span class="fa fa-save"></span> Update 
      </button>

      <button class="btn yellow" type="button" onclick="$('#is_print').val(1); formsubmit();">
          <span class="fa fa-print"></span> Update & Print
      </button>

      <a class="btn red" href="dispatch_details.php?id=<?php echo urlencode($_GET['id']); ?>">
         <span class="fa fa-backward"></span> Cancel 
      </a>
   </div>
</form>



<!-- ############### Return Slip Form ############### -->

<div class="form-group col-md-12">
   <div class="col-md-12">
      <hr>
   </div>
   <div class="col-md-12">
      <div style="height:45px;" class="alert alert-info"><center><strong>Return Slip Form </strong></center></div>

   </div>
</div>

<?php 
   if ($r['Status'] == 'Closed') {
       ?>

      <div class="form-group col-md-12">
         <div class="col-md-3">
            <label class="control-label">Status :<i class="font-red"><?php if($r['Closed_by'] != '') { echo 'Closed'; } else { echo $r['Status']; } ?></i></label>
         </div>
      </div>

      <div class="form-group col-md-12">
         <div class="col-md-3">
            <label class="control-label">Return Date<i class="font-red"> *</i></label>
            <input required class="form-control" size="16" type="text" value="<?= $r['dateEnd']->format('Y-m-d h:i a'); ?>" readonly>
         </div>

         <div class="col-md-3">
            <label class="control-label">Odometer End<i class="font-red"> *</i></label>
            <div class="input-icon">
               <i class="fa fa-tachometer font-yellow"></i>
               <input readonly type="text" class="form-control" value="<?= $r['odometer_end']; ?>">
            </div>
         </div>

      </div>
     
   <div style="text-align:right;">
      <button disabled class="btn btn-circle blue" type="submit">
         <span class="glyphicon glyphicon-remove"></span> Close Trip Ticket 
      </button>
   </div>
           <?php }
   else { ?>
   <form method="post" action="" id="form_closing">
      <div class="form-group col-md-12">

         <input type="hidden" id="date_out" value="<?= $r['dateStart']->format('Y-m-d'); ?>">
         <input type="hidden" class="form-control" name="ticket_no" value="<?= $r['tripTicket']; ?>">
         <!-- Added 9/7/2019 -->
         <input type="hidden" name="request_id" value="<?= $r['request_id']; ?>">
         
         <div class="col-md-4">

            <input type="hidden" name="odom_startn" id="odom_startn" value="<?= $r['odometer_start']; ?>">
            <label class="control-label">Odometer End<i class="font-red"> *</i></label>
            <div class="input-icon">
               <i class="fa fa-tachometer font-yellow"></i>
               <input <?= $isPrinted; ?> type="text" class="form-control" id="odom_e" name="odom_end" value="<?php if($r['odometer_end']== ''){echo '';}else{ echo $r['odometer_end']; } ?>">
            </div>
         </div>
		 <div class="col-md-4">
            <label class="control-label">Number of Trips<i class="font-red"> *</i></label>
            <div class="input-icon">
               <i class="fa fa-exchange font-yellow"></i>
               <input <?= $isPrinted; ?> type="number" class="form-control text-right" min="1" id="numberOfTrips" name="numberOfTrips" value="<?php if($r['numberOfTrips']=='0' || strlen($r['numberOfTrips'])==0){echo '1';}else{ echo $r['numberOfTrips']; } ?>">
            </div>
         </div>
      </div>

      <div class="form-group col-md-12">
         <div class="col-md-4">
            <label class="control-label">Return Date<i class="font-red"> *</i></label>
            <div class="input-group">
               <div class="input-icon">
                  <i class="fa fa-calendar font-yellow"></i>
                  <input class="form-control" name="return_date" id="date_return" size="16" type="date" 
                  min="<?= $r['dateStart']->format('Y-m-d'); ?>" max="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" >
               </div>
               <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
               <input type="hidden" name="return_date" id="date_return" value="" min="" />
            </div>
         </div>

         <div class="col-md-4">
            <label class="control-label">Return Time<i class="font-red"> *</i></label>
         
            <div class="input-icon">
               <i class="fa fa-clock-o font-yellow"></i>
               <select <?= $isPrinted; ?> required="required" id="r_time" name="return_time" class="bs-select form-control">
                  <option value=''>-- Select Time --</option>		
                  <?php if(isset($r['dateEnd'])){ ?>
                  <option value='<?php echo $r['dateEnd']->format('H:i'); ?>' selected="selected"><?php echo $r['dateEnd']->format('H:i'); ?></option> 
                  <?php } ?>    
                  		
                  <optgroup label="AM">
                     <option value="24:00">12:00</option>
                     <option value="01:00">01:00</option>
                     <option value="02:00">02:00</option>
                     <option value="03:00">03:00</option>
                     <option value="04:00">04:00</option>
                     <option value="05:00">05:00</option>
                     <option value="06:00">06:00</option>
                     <option value="07:00">07:00</option>
                     <option value="08:00">08:00</option>
                     <option value="09:00">09:00</option>
                     <option value="10:00">10:00</option>
                     <option value="11:00">11:00</option>
                  </optgroup>
                  <optgroup label="PM">
                     <option value="12:00">12:00</option>
                     <option value="13:00">01:00</option>
                     <option value="14:00">02:00</option>
                     <option value="15:00">03:00</option>
                     <option value="16:00">04:00</option>
                     <option value="17:00">05:00</option>
                     <option value="18:00">06:00</option>
                     <option value="19:00">07:00</option>
                     <option value="20:00">08:00</option>
                     <option value="21:00">09:00</option>
                     <option value="22:00">10:00</option>
                     <option value="23:00">11:00</option>
                  </optgroup>

                </select>
            </div>
         </div>

         

      </div>

   <div style="text-align:right;">
      <button class="btn btn-circle blue" type="submit" id="btnClose" name="return_edit">
         <span class="glyphicon glyphicon-remove"></span> Close Trip Ticket 
      </button>
   </div>
</form>
   <?php }
?>





<script>

   function update_vehiclecostcode(){
      var x = $('#vcostcode').val();
      var i = x.split("|");
      $('#cost_code').val(i[2]);
    
      var odo = $('option:selected', $('#vcostcode')).attr('title');
      $('#odo_help').html('Previous trip odometer: '+addCommas(odo));
      $('#last_odo').val(odo);
   }

   function addCommas(nStr)
   {
       nStr += '';
       x = nStr.split('.');
       x1 = x[0];
       x2 = x.length > 1 ? '.' + x[1] : '';
       var rgx = /(\d+)(\d{3})/;
       while (rgx.test(x1)) {
           x1 = x1.replace(rgx, '$1' + ',' + '$2');
       }
       return x1 + x2;
   }

   function formValidation(oEvent) { 

      oEvent = oEvent || window.event;

      var t1ck=true;
     
      if(document.getElementById("odom_e").value.length < 1 ) {
         t1ck=false; 
      }
      if(document.getElementById("r_time").value.length < 1 ) { 
         t1ck=false; 
      }
     /* if(document.getElementById("f_type").value.length < 1 ) { 
         t1ck=false; 
      }*/
     
      if(document.getElementById("date_return").value.length < 1 ) { 
         t1ck=false; 
      }

      if(t1ck){
         document.getElementById("btnClose").disabled = false; 
      }
      else{
         document.getElementById("btnClose").disabled = true; 
      }
   } 

   window.onload = function () { 

      var btnClose = document.getElementById("btnClose"); 

     
      /*var f_type = document.getElementById("f_type");*/
      var odom_e = document.getElementById("odom_e");
      var t_return = document.getElementById("r_time");
     
      var dt_return = document.getElementById("date_return");

      var t1ck=false;
      document.getElementById("btnClose").disabled = true;

         t_return.onclick  = formValidation;
         //f_type.onclick = formValidation;
         odom_e.onkeyup  = formValidation; 
         dt_return.change  = formValidation;
   }



      var todayDate = new Date().getDate();
      var dateOut = document.getElementById('date_out').value;
      var startD = new Date(new Date().setDate(todayDate));
      var endD   = new Date(dateOut);

      $('.form_date').datetimepicker({
         language:  'en',
         endDate : startD,
         startDate : endD,
         weekStart: 1,
         todayBtn:  1,
         autoclose: 1,
         todayHighlight: 1,
         startView: 2,
         minView: 2,
         forceParse: 0
       });

      $('.form_datetime').datetimepicker({
         language:  'en',
         weekStart: 7,
         todayBtn:  1,
         autoclose: 1,
         todayHighlight: 1,
         startView: 2,
         forceParse: 0,
         showMeridian: 1,
         minView: 0
      });

      function formsubmit(){
         var odoe = $('#last_odo').val();
         var odos = $('#odom_start').val();

         // if(parseFloat(odos) < parseFloat(odoe)){
         //    alert('Odometer start must be greater than previous odometer which is: '+$('#last_odo').val());
         //    return false;
         // } else {
         //    $('form#ddform').submit();
         // }
          $('form#ddform').submit();
      }

      $('#form_closing').on('submit', function() {
         var odoe = $('#odom_e').val();
         var odos = $('#odom_startn').val();

         // if(parseFloat(odoe) < parseFloat(odos)){
         //    alert('Odometer end must be greater than odometer start');
         //    return false;   
         // } else {
         //    return true;
         // }
            return true;
      });

      $('#odom_start').on('change', function(){
         $('#odom_startn').val($(this).val());
      });
</script>