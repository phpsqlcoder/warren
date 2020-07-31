<?php 
	include("../config.php");
	$id=$_GET['id'];
	$r = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT r.room_num, CONVERT(varchar(23), h.reservationDateStart, 121) AS start, CONVERT(varchar(23), h.reservationDateEnd, 121) AS finish, h.* FROM booking_hotel h left join rooms r on r.id=h.room_id WHERE h.id='".$id."'"));
	$meals='<ul>';
	$transport='';
	$hasmeals=0;
	$hastransport=0;
	$mq = sqlsrv_query($conn,"select distinct CONVERT(varchar(23), date, 121) as date from booking_meal where booking_id='".$id."' order by date");
	while($md = sqlsrv_fetch_array($mq)){
		$hasmeals=1;
		$meals.='<li>'.$md['date'].'</li>';
	}
	$meals.='</ul>';

	$transport='<table style="margin-left:70px;">';
	$tq = sqlsrv_query($conn,"select CONVERT(VARCHAR,t.schedule,121) as sched,t.*,c.plate_number,d.name as driver_name FROM 
            booking_transport_passengers p 
            left join booking_transport t on t.id=p.transport_id
		left join driver d on d.id=t.driver_id 
		left join car c on c.id=t.car_id where p.booking_id='".$r['id']."'");
 

	while($t = sqlsrv_fetch_array($tq)){
		$hastransport=1;
		$transport.='<tr><td>Date:</td><td>'.date('M d, Y h:i A',strtotime($t['sched'])).'</td></tr>
					<tr><td>Driver:</td><td>'.$t['driver_name'].'</td></tr>
					<tr><td>Vehicle:</td><td>'.$t['plate_number'].'</td></tr>
					<tr><td>Destination:</td><td>'.$t['origin'].' to '.$t['destination'].'</td></tr>
		';
	}
	$transport.='</table>';
?>
<style>
	table {
		font-family: Sans-serif, Times, serif;
	}
	hr {
	    display: block;
	    height: 1px;
	    border: 0;
	    border-top: 1px solid #ccc;
	    margin: 1em 0;
	    padding: 0; 
	}


</style>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Booking Confirm</title>
<!--IMPORTANT: 
Before deploying this email template into your application make sure you convert all the css code in <style> tag using http://beaker.mailchimp.com/inline-css.
Chrome and other few mail clients do not support <style> tag so the above converter from mailchip will make sure that all the css code will be converted into inline css.
-->
<meta name="viewport" content="width=device-width"/>
<style type="text/css">
            /*********************************************************************
            Ink - Responsive Email Template Framework Based: http://zurb.com/ink/
            *********************************************************************/
            #outlook a { 
            padding:0; 
            } 
            body{ 
            width:100% !important; 
            min-width: 100%;
            -webkit-text-size-adjust:100%; 
            -ms-text-size-adjust:100%; 
            margin:0; 
            padding:0;
            }         
            img { 
            outline:none; 
            text-decoration:none; 
            -ms-interpolation-mode: bicubic;
            width: auto; 
            height: auto;
            max-width: 100%;
            float: left; 
            clear: both; 
            display: block;
            }
            @media screen and (min-width:0\0) {  
                  /* IE9 and IE10 rule sets go here */  
                  img.ie10-responsive { 
                        width: 100% !important;
                  }
            }  
            center {
            width: 100%;
            min-width: 580px;
            }
            a img { 
            border: none;
            }
            p {
            margin: 0 0 0 10px;
            }
            table {
            border-spacing: 0;
            border-collapse: collapse;
            }
            td { 
            word-break: break-word;
            -webkit-hyphens: auto;
            -moz-hyphens: auto;
            hyphens: auto;
            border-collapse: collapse !important; 
            }
            table, tr, td {
            padding: 0;
            vertical-align: top;
            text-align: left;
            }
            hr {
            color: #d9d9d9; 
            background-color: #d9d9d9; 
            height: 1px; 
            border: none;
            }
            /* Responsive Grid */
            table.body {
            height: 100%;
            width: 100%;
            }
            table.container {
            width: 580px;
            margin: 0 auto;
            text-align: inherit;
            }
            table.row { 
            padding: 0px; 
            width: 100%;
            position: relative;
            }
            table.container table.row {
            display: block;
            }
            td.wrapper {
            padding: 10px 20px 0px 0px;
            position: relative;
            }
            table.columns,
            table.column {
            margin: 0 auto;
            }
            table.columns td,
            table.column td {
            padding: 0px 0px 10px; 
            }
            table.columns td.sub-columns,
            table.column td.sub-columns,
            table.columns td.sub-column,
            table.column td.sub-column {
            padding-right: 10px;
            }
            td.sub-column, td.sub-columns {
            min-width: 0px;
            }
            table.row td.last,
            table.container td.last {
            padding-right: 0px;
            }
            table.one { width: 30px; }
            table.two { width: 80px; }
            table.three { width: 130px; }
            table.four { width: 180px; }
            table.five { width: 230px; }
            table.six { width: 280px; }
            table.seven { width: 330px; }
            table.eight { width: 380px; }
            table.nine { width: 430px; }
            table.ten { width: 480px; }
            table.eleven { width: 530px; }
            table.twelve { width: 580px; }
            table.one center { min-width: 30px; }
            table.two center { min-width: 80px; }
            table.three center { min-width: 130px; }
            table.four center { min-width: 180px; }
            table.five center { min-width: 230px; }
            table.six center { min-width: 280px; }
            table.seven center { min-width: 330px; }
            table.eight center { min-width: 380px; }
            table.nine center { min-width: 430px; }
            table.ten center { min-width: 480px; }
            table.eleven center { min-width: 530px; }
            table.twelve center { min-width: 580px; }
            table.one .panel center { min-width: 10px; }
            table.two .panel center { min-width: 60px; }
            table.three .panel center { min-width: 110px; }
            table.four .panel center { min-width: 160px; }
            table.five .panel center { min-width: 210px; }
            table.six .panel center { min-width: 260px; }
            table.seven .panel center { min-width: 310px; }
            table.eight .panel center { min-width: 360px; }
            table.nine .panel center { min-width: 410px; }
            table.ten .panel center { min-width: 460px; }
            table.eleven .panel center { min-width: 510px; }
            table.twelve .panel center { min-width: 560px; }
            .body .columns td.one,
            .body .column td.one { width: 8.333333%; }
            .body .columns td.two,
            .body .column td.two { width: 16.666666%; }
            .body .columns td.three,
            .body .column td.three { width: 25%; }
            .body .columns td.four,
            .body .column td.four { width: 33.333333%; }
            .body .columns td.five,
            .body .column td.five { width: 41.666666%; }
            .body .columns td.six,
            .body .column td.six { width: 50%; }
            .body .columns td.seven,
            .body .column td.seven { width: 58.333333%; }
            .body .columns td.eight,
            .body .column td.eight { width: 66.666666%; }
            .body .columns td.nine,
            .body .column td.nine { width: 75%; }
            .body .columns td.ten,
            .body .column td.ten { width: 83.333333%; }
            .body .columns td.eleven,
            .body .column td.eleven { width: 91.666666%; }
            .body .columns td.twelve,
            .body .column td.twelve { width: 100%; }
            td.offset-by-one { padding-left: 50px; }
            td.offset-by-two { padding-left: 100px; }
            td.offset-by-three { padding-left: 150px; }
            td.offset-by-four { padding-left: 200px; }
            td.offset-by-five { padding-left: 250px; }
            td.offset-by-six { padding-left: 300px; }
            td.offset-by-seven { padding-left: 350px; }
            td.offset-by-eight { padding-left: 400px; }
            td.offset-by-nine { padding-left: 450px; }
            td.offset-by-ten { padding-left: 500px; }
            td.offset-by-eleven { padding-left: 550px; }
            td.expander {
            visibility: hidden;
            width: 0px;
            padding: 0 !important;
            }
            /* Alignment & Visibility Classes */
            table.center, td.center {
            text-align: center;
            }
            h1.center,
            h2.center,
            h3.center,
            h4.center,
            h5.center,
            h6.center {
            text-align: center;
            }
            span.center {
            display: block;
            width: 100%;
            text-align: center;
            }
            img.center {
            margin: 0 auto;
            float: none;
            }
            /* Typography */
            body, table.body, h1, h2, h3, h4, h5, h6, p, td { 
            color: #222222;
            font-family: "Helvetica", "Arial", sans-serif; 
            font-weight: normal; 
            padding:0; 
            margin: 0;
            text-align: left; 
            line-height: 1.3;
            }
            h1, h2, h3, h4, h5, h6 {
            word-break: normal;
            }
            h1 {font-size: 40px;}
            h2 {font-size: 36px;}
            h3 {font-size: 32px;}
            h4 {font-size: 28px;}
            h5 {font-size: 24px;}
            h6 {font-size: 20px;}
            body, table.body, p, td {font-size: 14px;line-height:19px;}
            p.lead, p.lede, p.leed {
            font-size: 18px;
            line-height:21px;
            }
            p { 
            margin-bottom: 10px;
            }
            small {
            font-size: 10px;
            }
            a {
            color: #2ba6cb; 
            text-decoration: none;
            }
            a:hover { 
            color: #2795b6 !important;
            }
            a:active { 
            color: #2795b6 !important;
            }
            a:visited { 
            color: #2ba6cb !important;
            }
            h1 a, 
            h2 a, 
            h3 a, 
            h4 a, 
            h5 a, 
            h6 a {
            color: #2ba6cb;
            }
            h1 a:active, 
            h2 a:active,  
            h3 a:active, 
            h4 a:active, 
            h5 a:active, 
            h6 a:active { 
            color: #2ba6cb !important; 
            } 
            h1 a:visited, 
            h2 a:visited,  
            h3 a:visited, 
            h4 a:visited, 
            h5 a:visited, 
            h6 a:visited { 
            color: #2ba6cb !important; 
            } 
            /* Panels */
            .panel {
            background: #f2f2f2;
            border: 1px solid #d9d9d9;
            padding: 10px !important;
            }
            table.radius td {
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            }
            table.round td {
            -webkit-border-radius: 500px;
            -moz-border-radius: 500px;
            border-radius: 500px;
            }
            /* Outlook First */
            body.outlook p {
            display: inline !important;
            }
            /*  Media Queries */
            @media only screen and (max-width: 600px) {
            table[class="body"] img {
            width: auto !important;
            height: auto !important;
            }
            table[class="body"] center {
            min-width: 0 !important;
            }
            table[class="body"] .container {
            width: 95% !important;
            }
            table[class="body"] .row {
            width: 100% !important;
            display: block !important;
            }
            table[class="body"] .wrapper {
            display: block !important;
            padding-right: 0 !important;
            }
            table[class="body"] .columns,
            table[class="body"] .column {
            table-layout: fixed !important;
            float: none !important;
            width: 100% !important;
            padding-right: 0px !important;
            padding-left: 0px !important;
            display: block !important;
            }
            table[class="body"] .wrapper.first .columns,
            table[class="body"] .wrapper.first .column {
            display: table !important;
            }
            table[class="body"] table.columns td,
            table[class="body"] table.column td {
            width: 100% !important;
            }
            table[class="body"] .columns td.one,
            table[class="body"] .column td.one { width: 8.333333% !important; }
            table[class="body"] .columns td.two,
            table[class="body"] .column td.two { width: 16.666666% !important; }
            table[class="body"] .columns td.three,
            table[class="body"] .column td.three { width: 25% !important; }
            table[class="body"] .columns td.four,
            table[class="body"] .column td.four { width: 33.333333% !important; }
            table[class="body"] .columns td.five,
            table[class="body"] .column td.five { width: 41.666666% !important; }
            table[class="body"] .columns td.six,
            table[class="body"] .column td.six { width: 50% !important; }
            table[class="body"] .columns td.seven,
            table[class="body"] .column td.seven { width: 58.333333% !important; }
            table[class="body"] .columns td.eight,
            table[class="body"] .column td.eight { width: 66.666666% !important; }
            table[class="body"] .columns td.nine,
            table[class="body"] .column td.nine { width: 75% !important; }
            table[class="body"] .columns td.ten,
            table[class="body"] .column td.ten { width: 83.333333% !important; }
            table[class="body"] .columns td.eleven,
            table[class="body"] .column td.eleven { width: 91.666666% !important; }
            table[class="body"] .columns td.twelve,
            table[class="body"] .column td.twelve { width: 100% !important; }
            table[class="body"] td.offset-by-one,
            table[class="body"] td.offset-by-two,
            table[class="body"] td.offset-by-three,
            table[class="body"] td.offset-by-four,
            table[class="body"] td.offset-by-five,
            table[class="body"] td.offset-by-six,
            table[class="body"] td.offset-by-seven,
            table[class="body"] td.offset-by-eight,
            table[class="body"] td.offset-by-nine,
            table[class="body"] td.offset-by-ten,
            table[class="body"] td.offset-by-eleven {
            padding-left: 0 !important;
            }
            table[class="body"] table.columns td.expander {
            width: 1px !important;
            }
      }
</style>
<style>
            /**************************************************************
            * Custom Styles *
            ***************************************************************/
            /***
            Reset & Typography
            ***/
            body {
            direction: ltr;
            background: #f6f8f1;
            }   
            a:hover {
            text-decoration: underline;
            }
            h1 {font-size: 34px;}
            h2 {font-size: 30px;}
            h3 {font-size: 26px;}
            h4 {font-size: 22px;}
            h5 {font-size: 18px;}
            h6 {font-size: 16px;}
            h4, h3, h2, h1 {
            display: block;
            margin: 5px 0 15px 0;
            }
            h7, h6, h5 {
            display: block;
            margin: 5px 0 5px 0 !important;
            }
            /***
            Buttons
            ***/
            .btn td {
            background: #e5e5e5 !important;
            border: 0;
            font-family: "Segoe UI", Helvetica, Arial, sans-serif;
            font-size: 14px;  
            padding: 7px 14px !important;
            color: #333333 !important;
            text-align: center;
            vertical-align: middle;
            }
            .btn td a {
            display: block;
            color: #fff;
            }
            .btn td a:hover,
            .btn td a:focus,
            .btn td a:active {
            color: #fff !important;
            text-decoration: none;
            }
            .btn td:hover, 
            .btn td:focus, 
            .btn td:active {  
            background: #d8d8d8 !important;
            }
            /*  Yellow */
            .btn.yellow td {
            background: #ffb848 !important;
            }
            .btn.yellow td:hover, 
            .btn.yellow td:focus, 
            .btn.yellow td:active { 
            background: #eca22e !important;
            }
            .btn.red td{
            background: #d84a38 !important;
            }
            .btn.red td:hover, 
            .btn.red td:focus, 
            .btn.red td:active {    
            background: #bb2413 !important;
            }
            .btn.green td {
            background: #35aa47 !important;
            }
            .btn.green td:hover, 
            .btn.green td:focus, 
            .btn.green td:active { 
            background: #1d943b !important;
            }
            /*  Blue */
            .btn.blue td {
            background: #4d90fe !important;
            }
            .btn.blue td:hover, 
            .btn.blue td:focus, 
            .btn.blue td:active {  
            background: #0362fd !important;
            }
            .template-label {
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
            }
            /***
            Note Panels
            ***/
            .note .panel {
            padding: 10px !important;
            background: #ECF8FF;
            border: 0;
            }
            /***
            Header
            ***/
            .page-header { 
            width: 100%;
            background: #ccfcd8;

            }
            /***
            Social Icons
            ***/
            .social-icons {
            float: right;
            }
            .social-icons td {
            padding: 0 2px !important;
            width: auto !important;
            }
            .social-icons td:last-child {
            padding-right: 0 !important;
            }
            .social-icons td img {
            max-width: none !important; 
            }
            /***
            Content
            ***/
            table.container.content > tbody > tr > td{
            background: #fff;  
            padding: 15px !important;
            }
            /***
            Footer
            ***/
            .page-footer  {
            width: 100%;
            background: #2f2f2f;
            }
            .page-footer td {
            vertical-align: middle;
            color: #fff;
            }
            /***
            Content devider
            ***/
            .devider {
            border-bottom: 1px solid #eee;
            margin: 15px -15px;
            display: block;
            }
            /***
            Media Item
            ***/
            .media-item img {
            display: block !important;
            float: none;
            margin-bottom: 10px;
            }
            .vertical-middle {
            padding-top: 0;
            padding-bottom: 0;
            vertical-align: middle;
            }
            /***
            Utils
            ***/
            .align-reverse {
            text-align: right;
            }
            .border {
            border: 1px solid red;
            }
            .hidden-mobile {
            display: block;
            }
            .visible-mobile {
            display: none;
            }
            @media only screen and (max-width: 600px) {
            /***
            Reset & Typography
            ***/
            body {
            background: #fff;  
            }
            h1 {font-size: 30px;}
            h2 {font-size: 26px;}
            h3 {font-size: 22px;}
            h4 {font-size: 20px;}
            h5 {font-size: 16px;}
            h6 {font-size: 14px;}
            /***
            Content
            ***/
            table.container.content > tbody > tr > td{
            padding: 0px !important;
            }
            table[class="body"] table.columns .social-icons td {
            width: auto !important;
            }
            /***
            Header
            ***/
            .page-header {
            padding: 10px !important;
            }
            /***
            Content devider
            ***/
            .devider {
            margin: 15px 0;
            }
            /***
            Media Item
            ***/
            .media-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0 !important;
            }
            /***
            Media Item
            ***/
            .hidden-mobile {
            display: none;
            }
            .visible-mobile {
            display: block;
            }
            }
</style>
</head>
<body>
      <?php if(isset($_GET['remarks'])){ ?>
      <div class="panel">
             Email Successfully Sent!
      </div>
      <?php } ?>
<table class="body">
<tr>
	<td class="center" align="center" valign="top">
		<!-- BEGIN: Header -->
		<table class="page-header" align="center">
		<tr>
			<td class="center" align="center">
				<!-- BEGIN: Header Container -->
				<table class="container" align="center">
				<tr>
					<td>
						<table class="row ">
						<tr>
							<td class="wrapper vertical-middle">
								<table class="six columns">
								<tr>
									<td class="vertical-middle">										
										<img src="http://i65.tinypic.com/f4pb2t.png" style="height:100px !important;" border="0" alt=""/>									
									</td>
								</tr>
								</table>
							</td>
							<td class="wrapper vertical-middle last">
								<table>
									<tr><td style="font-weight: bold;">Date:</td><td> <?php echo date('Y-m-d');?></td></tr>
									<tr><td style="font-weight: bold;">Address:</td><td> C.P. Garcia Highway, Brgy. Sasa, </td></tr>
									<tr><td style="font-weight: bold;">&nbsp;</td><td>Buhangin District, Davao City 8000</td></tr>
									<tr><td style="font-weight: bold;">Tel Nos:</td><td> (082) 235-0045 to 47 loc 1128</td></tr>
								</table>
								<!-- BEGIN: Social Icons -->
								
								<!-- END: Social Icons -->
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<!-- END: Header Container -->
			</td>
		</tr>
		</table>
		<!-- END: Header -->
		<!-- BEGIN: Content -->
		<table class="container content" align="center">
		<tr>
			<td>
				<table class="row">
				<tr>
					<td class="wrapper last">
						<!-- BEGIN: Heading Content -->
						<table class="twelve columns">
						<tr>
							<td>
								<h3>Booking Confirmation</h3>
								
								<h5>Greetings from Philsaga Davao.</h5>
								<br>
								<p>
									This is to confirm your reservation as follows:
								</p>
								<table style="margin-left:20px;" width="100%">
									<tr>
										<td>Reference no:</td>
										<td style="font-weight: bold;font-size:18px;"><?php echo refcode($r['id'])?></td>
									</tr>
									<tr>
										<td>Name:</td>
										<td style="font-weight: bold;"><?php echo $r['guest_name']?></td>
									</tr>
									<tr>
										<td>Room:</td>
										<td style="font-weight: bold;"><?php echo $r['room_num']?></td>
									</tr>
									<tr>
										<td>Arrival:</td>
										<td style="font-weight: bold;"><?php echo date('M d, Y h:i A',strtotime($r['start']))?></td>
									</tr>
									<tr>
										<td>Departure:</td>
										<td style="font-weight: bold;"><?php echo date('M d, Y h:i A',strtotime($r['finish']))?></td>
									</tr>
									<tr>
										<td>Purpose:</td>
										<td style="font-weight: bold;"><?php echo $r['purpose']?></td>
									</tr>
									<?php if(strlen($r['preferences'])>=1){ ?>
									<tr>
										<td>Preferences:</td>
										<td style="font-weight: bold;"><?php echo $r['preferences']?></td>
									</tr>
									<?php } ?>
									<?php if($hasmeals==1){ ?>
										<tr>
											<td>Meals:</td>
											<td><?php echo $meals;?></td>
										</tr>
									<?php } ?>
								</table>
								<?php if($hastransport==1){ ?>
									<h5>Vehicle Booking:</h5>
									<?php echo $transport;?>									
								<?php } ?>
								
	


								
							</td>
							<td class="expander">
							</td>
						</tr>
						<tr>
							<td class="panel">
								 Please dont hesitate to <a href="tel:0822350045">call</a> or  <a href="mailto:<?php echo $incharge_email;?>?Subject=Inquiry%20About%20<?php echo refcode($r['id'])?>">email</a> us if you want to change any details of your booking.

							</td>
						</tr>
						</table>
						<!-- END: Heading Content -->
					</td>
				</tr>
				</table>
				<span class="devider">
				</span>
                        <h4>Send Email Form</h4>
                        <form action="confirmation_sendx.php?id=<?php echo $id;?>" method="post">
                              <table  width="100%" cellpadding="15" cellspacing="10">

                                    <tr>
                                          <td>Send to:</td><td><input type="text" style="width:100%" name="email" placeholder="input email address here." /></td>
                                    </tr>
                                    <tr>
                                          <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                          <td>CC:</td><td><input type="text" style="width:100%" name="ccemail" placeholder="CC" /></td>
                                    </tr>
                                    <tr>
                                          <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                          <td colspan="2" align="right">                                          
                                                <table class="btn yellow" align="right">
                                                      <tr>
                                                            <td>
                                                                  <input type="submit" value="Send Email">
                                                                
                                                            </td>
                                                      </tr>
                                                </table>
                                          </td>
                                    </tr>
                              </table>
                        </form>
				
				<!-- END: Latest Products List -->
			</td>
		</tr>
		</table>
		<!-- END: Content -->
		<!-- BEGIN: Footer -->
		<table class="page-footer" align="center">
		<tr>
			<td class="center" align="center">
				<table class="container" align="center">
				<tr>
					<td>
						<!-- BEGIN: Unsubscribet -->
						
						<!-- END: Unsubscribe -->
						<!-- BEGIN: Footer Panel -->
						<table class="row">
						<tr>
							<td class="wrapper">
								<table class="four columns">
								<tr>
									<td class="vertical-middle">
										 &copy; PMC - ICT <?php echo date('Y');?>
									</td>
								</tr>
								</table>
							</td>
							
						</tr>
						</table>
						<!-- END: Footer Panel List -->
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<!-- END: Footer -->
	</td>
</tr>
</table>
</body>
</html>