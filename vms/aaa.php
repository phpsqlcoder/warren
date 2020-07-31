<?php
$msg = '{"action":"Comment","id":"76","message":"test 3"}';
//$json = json_encode($msg);
$data = (array) json_decode($msg,true);
var_dump($data);
//var_dump(json_last_error());
$new_msg = '';

//foreach($data as $d => $x){
   //$new_msg .= $d['message'];
   //echo $x."<br>";
//}
echo $data['message'];
echo $new_msg;

die();
session_start();
$data['message'] = "Form token = ".$_POST['form_token']."   Session value = ".$_SESSION['review_form_token'];
echo json_encode($data);
die();



$errors     = array(); // array to hold validation errors
$data       = array(); // array to pass back data
$firstname  = $_POST['firstname'];
$lastname   = $_POST['lastname'];
$StarRating = $_POST['StarRating'];
$review     = $_POST['review'];
$captcha    = $_POST['captcha'];

// Form Validation...

if ($_POST['form_token'] !== $_SESSION['review_form_token']) {
   $errors['doublepost'] = 'You have already submitted your review, you can not resubmit. If you need to send another review, please reload the page.';
}
// return a response ===========================================================
// if there are any errors in our errors array, return a success boolean of false
if (!empty($errors)) {
   // if there are items in our errors array, return those errors
   $data['success'] = false;
   $data['errors']  = $errors;
  $data['message'] = "error s";
} else {
   // if there are no errors process our form, then return a message

   unset($_SESSION['review_form_token']);

   // mySQL inserting data...

   // show a message of success and provide a true success variable
   $data['success'] = true;
   $data['message'] = 'Success!';
}
// return all our data to an AJAX call
echo json_encode($data);
?>