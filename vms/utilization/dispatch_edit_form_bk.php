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
}

 $available_units = '<option value="'.$r['id'].'|'.$r['type'].'">'.$r['type'].'</option>';
            $unavailable_units = '';
            $units = sqlsrv_query($conn,"select * from unit");
            while($unit = sqlsrv_fetch_array($units)){

               $check_if_down = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from downtime where status<>'CANCELLED' and unitId='".$unit['id']."'
                  and '".$d['date_needed']->format('Y-m-d h:i:s')."' between dateStart and dateEnd
                  "));
             

               if($check_if_down['id']){
                  $unavailable_units .= '<option disabled value="'.$unit['id'].'|'.$unit['name'].'">'.$unit['name'].' - '.$check_if_down['repairType'].'</option>';
               }
               else {
                  $check_if_no_booking = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from dispatch where status<>'Cancelled' and unitId='".$unit['id']."'
                  and '".$d['date_needed']->format('Y-m-d h:i:s')."' between dateStart and ISNULL(dateEnd,DATEADD(hour, ".$hour_allowance.", dateStart))
                  "));                                                     
                
                  if($check_if_no_booking['id']){
                     $unavailable_units .= '<option disabled value="'.$unit['id'].'|'.$unit['name'].'">'.$unit['name'].'  - '.$check_if_no_booking['tripTicket'].'</option>';
                  }
                  else{                                                      
                     $available_units .= '<option value="'.$unit['id'].'|'.$unit['name'].'">'.$unit['name'].'</option>';
                  }
               }
               
            }
                                             
?>

<form action="" method="post">
   <div class="form-group col-md-12">
      <div class="col-md-12">
         <div style="height:45px;;" class="alert alert-success"><center><strong>Trip Ticket Form <?php echo $r['tripTicket'];?></strong></center></div>
      </div>
   </div>

   <div class="form-group col-md-12">
      <input type="hidden" name="requestor" value="<?= $d['name']; ?>">

      <div class="col-md-3">
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

      <div class="col-md-3">
         <label class="control-label">Department<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-building-o font-yellow"></i>
            <input <?= $readonly; ?> id="dept" required type="text" class="form-control" name="deparment" value="<?= $r['deptId']; ?>" >
         </div>
      </div>

      <div class="col-md-3">
         <label class="control-label">Vehicle<i class="font-red"> *</i></label>
         <?php 
            if ($r['isPrinted'] == 1) {
                ?>
                <input readonly type="text" class="form-control" value="<?= $r['type']; ?>">
                    <?php }
            else { ?>
                <select required="required" name="vehicle" class="bs-select form-control">
                   <optgroup label="Available"><?php echo $available_units; ?></optgroup>
                   <optgroup label="Unavailable"><?php echo $unavailable_units; ?></optgroup>
                </select>
            <?php }
         ?>

         
      </div>

      <div class="col-md-3">
         <label class="control-label">Trip Ticket No.</label>
         <input type="text" class="form-control" name="ticket_no" value="<?= $r['tripTicket']; ?>" readonly>
      </div>
   </div>

   <div class="form-group col-md-12">
      <div class="col-md-3">
         <label class="control-label">Date Needed<i class="font-red"> *</i></label>
         <input required type="text" name="app_date" class="form-control" id="dt_from" value="<?= $r['addedDate']->format('Y-m-d h:i:s'); ?>" readonly  />
  

      </div>

      <div class="col-md-3">
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
                  $result = sqlsrv_query($conn,"SELECT * FROM drivers");
                  while ($drow = sqlsrv_fetch_array($result)){
                     $count++; ?>
                     <?php if ($count > 0) {
                        ?>
                      
                        <option value="<?php echo $drow['id']; ?>"><?php echo $drow['driver_name']; ?></option>
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

      <div class="col-md-3">
         <label class="control-label">From<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-globe font-yellow"></i>
            <input <?= $readonly; ?> required type="text" class="form-control" name="origin" value="<?= $origin; ?>"> 
         </div>
      </div>

      <div class="col-md-3">
         <label class="control-label">To<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-globe font-yellow"></i>
            <input <?= $readonly; ?> required type="text" class="form-control" name="destination" value="<?= $destination; ?>"> 
         </div>
      </div>
   </div>

   <div class="form-group col-md-12">
      <div class="col-md-12">
         <label class="control-label">Purpose<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-comment-o font-yellow"></i>
            <textarea <?= $readonly; ?> required name="purpose" class="form-control"><?php echo $r['purpose']; ?></textarea>
         </div>

      </div>
   </div>

   <div class="form-group col-md-12">
      <div class="col-md-3">
         <label class="control-label">Odometer Start<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-tachometer font-yellow"></i>
            <input <?= $readonly; ?> required type="number" class="form-control" name="odom_start" value="<?= $r['odometer_start']; ?>">
         </div>
      </div>
   </div>

   <div class="form-group col-md-12">  
      <div class="col-md-12">
         <div class="form-group multiple-form-group">
            <label>Passengers</label>
            <?php 
               if ($r['isPrinted'] == 1) {
               ?>
                  <?php 
                  $ex =  explode('|',$r['passengers']);
                  
                  echo '<ul class="list-inline">';
                  foreach($ex as $pass) {
                     echo '<li><input readonly class="form-control" type="text" value="'.$pass.'" /></li>';
                  }
                  echo '</ul>';
                  ?>
            <?php }
               else { ?>
                  <?php 
                     $ex =  explode('|',$r['passengers']);
                     foreach($ex as $pass) {
                        echo '<div class="form-group input-group input-icon">
                        <i class="fa fa-user font-yellow"></i>
                        <input type="text" class="form-control" name="passengers[]" value="'.$pass.'">
                        <span class="input-group-btn"><button type="button" class="btn btn-danger btn-remove">Remove
                        </button></span>
                        </div>';
                     }
                  ?>
                  <div class="form-group input-group input-icon">
                     <i class="fa fa-user font-yellow"></i>
                     <input type="text" class="form-control" name="passengers[]" placeholder="Passengers">
                     <span class="input-group-btn"><button type="button" class="btn btn-primary btn-add">Add More
                     </button></span>
                  </div>
               <?php }
            ?>
         </div>
      </div>
   </div>
   <div style="text-align: right;" class="col-md-12">
      <?php 
         if ($r['isPrinted'] == 1) {
         ?>
            <button disabled class="btn btn-circle blue" type="submit">
               <span class="glyphicon glyphicon-edit"></span> Update 
            </button>
         <?php }
         else { ?>
            <button class="btn btn-circle blue" type="submit" name="dispatch_edit">
               <span class="glyphicon glyphicon-edit"></span> Update 
            </button>
         <?php }
         ?>
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

         <div class="col-md-3">
            <label class="control-label">Fuel Consumption<i class="font-red"> *</i></label>
            <div class="input-icon">
               <i class="fa fa-fire font-yellow"></i>
               <input readonly type="text" class="form-control" value="<?= $r['fuel_consumption']; ?>">
            </div>
         </div>
      </div>
      <div class="form-group col-md-12">
      <div class="col-md-3">
         <label class="control-label">UOM<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="icon-calculator font-yellow"></i>
            <input readonly type="text" class="form-control" value="<?= $r['uom']; ?>">
         </div>
      </div>

      <div class="col-md-3">
         <label class="control-label">Fuel Type<i class="font-red"> *</i></label>
         <input readonly type="text" class="form-control" value="<?= $r['fuel_added_type']; ?>">
      </div>

      <div class="col-md-3">
         <label class="control-label">Actual Fuel Added<i class="font-red"> *</i></label>
         <div class="input-icon">
            <i class="fa fa-fire font-yellow"></i>
            <input readonly type="text" class="form-control" value="<?= $r['fuel_added_qty']; ?>">
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
   <form method="post" action="">
      <div class="form-group col-md-12">

         <input type="hidden" id="date_out" value="<?= $r['dateStart']->format('Y-m-d'); ?>">

         <input type="hidden" class="form-control" name="ticket_no" value="<?= $r['tripTicket']; ?>">
         <div class="col-md-4">
            <label class="control-label">Return Date<i class="font-red"> *</i></label>
            <div class="input-group form_datetime date col-md-12" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="date_return" id="date_returned">
               <div class="input-icon">
                  <i class="fa fa-calendar font-yellow"></i>
                  <input required class="form-control" size="16" type="text" value="" readonly>
               </div>
               <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
               <input type="hidden" name="return_date" id="date_return" value="" />
            </div>
         </div>

         <div class="col-md-4">
            <label class="control-label">Odometer End<i class="font-red"> *</i></label>
            <div class="input-icon">
               <i class="fa fa-tachometer font-yellow"></i>
               <input <?= $isPrinted; ?> type="text" class="form-control" id="odom_e" name="odom_end" value="<?php if($r['odometer_end']== ''){echo '';}else{ echo $r['odometer_end']; } ?>">
            </div>
         </div>

         <div class="col-md-4">
            <label class="control-label">Fuel Type<i class="font-red"> *</i></label>
            <?php 
               if ($r['isPrinted'] == 1) {
               ?>
                  <select required id="f_type" name="fuel_type" class="form-control" onchange="update_itemcode();">
                     <?php                  
                        $count = 0;
                        $result = sqlsrv_query($conn,"SELECT * FROM fuel_types");
                        
                        while ($frow = sqlsrv_fetch_array($result)){                           
                           if($r['fuel_added_type'] == $frow['name']){
                              echo '<option value="'.$frow['name'].'" selected="selected">'.$frow['name'].'</option>';
                           }
                           else{
                              echo '<option value="'.$frow['name'].'">'.$frow['name'].'</option>';
                           }
                           
                        }
                     ?>
                  </select>
               <?php }
               else { ?>
                  <input readonly type="text" class="form-control" placeholder="Fuel Type">
               <?php }
            ?>
            
         </div>

      </div>

      <div class="form-group col-md-12">
         <div class="col-md-4">
            <label class="control-label">UOM<i class="font-red"> *</i></label>
            <div class="input-icon">
               <i class="icon-calculator font-yellow"></i>
               <input <?= $isPrinted; ?> type="text" class="form-control" id="uom" name="uom" value="Liter">
            </div>
         </div>

         <div class="col-md-4">
            <label class="control-label">Fuel Requested Qty<i class="font-red"> *</i></label>
            <input readonly type="text" class="form-control" value="<?= $r['fuel_requested_qty']; ?>">
         </div>

         <div class="col-md-4">
            <label class="control-label">Actual Fuel Added<i class="font-red"> *</i></label>
            <div class="input-icon">
               <i class="fa fa-fire font-yellow"></i>
               <input <?= $isPrinted; ?> required type="text" class="form-control" id="f_add" name="fuel_add" value="<?php if($r['fuel_added_qty']== ''){echo '';}else{ echo $r['fuel_added_qty']; } ?>">
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
function formValidation(oEvent) { 
   oEvent = oEvent || window.event;

   var t1ck=true;
   if(document.getElementById("f_add").value.length < 1 ) {
      t1ck=false; 
   }
   if(document.getElementById("odom_e").value.length < 1 ) {
      t1ck=false; 
   }
   if(document.getElementById("f_type").value.length < 1 ) { 
      t1ck=false; 
   }
  /* if(document.getElementById("f_consu").value.length < 1 ) { 
      t1ck=false; 
   }*/
   if(document.getElementById("uom").value.length < 1 ) { 
      t1ck=false; 
   }
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

   var f_add  = document.getElementById("f_add"); ; 
   var f_type = document.getElementById("f_type");
   var odom_e = document.getElementById("odom_e");
   var f_consu = document.getElementById("f_consu");
   var uom = document.getElementById("uom");
   var dt_return = document.getElementById("date_return");

   var t1ck=false;
   document.getElementById("btnClose").disabled = true;
      f_add.onkeyup  = formValidation;  
      f_type.onclick = formValidation;
      odom_e.onkeyup  = formValidation; 
      f_consu.onkeyup  = formValidation;
      uom.onkeyup  = formValidation;
      dt_return.change  = formValidation;
}

   var todayDate = new Date().getDate();
   var dateOut = document.getElementById('date_out').value;

   /*var tomorrow = new Date();
   tomorrow.setDate(tomorrow.getDate() + 1);*/

   var startD = new Date(new Date().setDate(todayDate));
   var endD   = new Date(dateOut);

   $('.form_datetime').datetimepicker({
      language:  'en',
      endDate : startD,
      startDate : endD,
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      forceParse: 0,
      showMeridian: 1
   });




</script>