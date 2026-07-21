@extends('layouts.afterlogin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
&nbsp;
	<?php $decodedDAta=	json_decode($TicketsData['json_data'],true);
	// echo $decodedDAta['one'][''checkbox''];
	// print_r($decodedDAta);exit;
	// foreach($decodedDAta as $k=>$v){
	// 	print_r($v[comment]);
	// }
	// exit;
	   
	$timezone=TIMEZONE;
	?>
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			@include('pages.messages')
			<div class="row">
				<!-- left column -->
				<div class="col-md-12">
					<!-- general form elements -->
					<div class="card card-secondary">
						<div class="card-header">
							<h3 class="card-title">Process Chart Details</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form action="{{ route('ticket.saveDynamicForm') }}" id="createProject" method="POST"
							enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="id" value="{{$TicketsData['id']}}">
							<input type="hidden" name="form_id" value="3">
							<div class="row">
								<div class="col-md-12">
                    <div class="card-body table-responsive">
										<table class="w-100">
											<tr>
												<td class="text-center"><h4><b>{{$TicketsData['establish_name']}}</b></h4></td>
											</tr>
											
										</table>
										<hr>
										<table class="w-100">
											<tr>
												<td class="text-left">{{$TicketsData['natureofwork']}}</td>
												<td class="text-right">Date : {{date('d/m/Y h:i A',strtotime($TicketsData['created_new_date']))}}</td>
											</tr>
										</table>
											<hr>
                        				<table id="employee-grid-35" border="1" class=" table-bordered table-striped responsive fixed-table-layout-user" >
											<thead>
												<tr style="background-color:#e6dcdc;">
													<th class="text-center" width="60%"><b>Check Points</b></th>
													<th class="text-center" width="20%"><b>Comments</b></th>
													<th class="text-center" width="10%"><b>UID</b></th>
													<th class="text-center" width="10%"><b>Date/& Time</b></th>
												</tr>
											</thead>
											<tbody>
											    <tr>
												<td class="p-7">
													<input type="checkbox" name="sixteen[checkbox]"  <?php if(isset($decodedDAta['sixteen']['checkbox']) && $decodedDAta['sixteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													1.  NAME OF THE AUTHORIZED PERSON WHOSE DSC IS EXPIRED
													<input style="width:260px;"  type="text" name="sixteen[1_eonm]" value="{{($decodedDAta['sixteen']['1_eonm'])??''}}">AND DSC EXPIRY DATE IS
													<input type="text"  name="sixteen[16_from]" class="onetime"  value="{{($decodedDAta['sixteen']['16_from'])??''}}">
												</td>
												<td class="p-7">

													<textarea rows="1"  name="sixteen[comment]" class="w-100">{{($decodedDAta['sixteen']['comment'])??''}}</textarea>
												</td>
												<td class="p-7">
													{{($decodedDAta['sixteen']['user_name'])??''}}
												</td>
												<td class="p-7">
													<?php 
													if(isset($decodedDAta['sixteen']['date_time']) && $decodedDAta['sixteen']['date_time']!=""){
														$timestamp =$decodedDAta['sixteen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
												</td>
												</tr>
												
												









.

<tr>
												<td class="p-7">
													<input type="checkbox" name="seventeen[checkbox]"  <?php if(isset($decodedDAta['seventeen']['checkbox']) && $decodedDAta['seventeen']['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													2.  TAKE FOLLOW UP TO GET RENEWED DIGITAL SIGNATURE FROM CLIENT.<P STYLE = "COLOR:RED"><B>IF CLIENT IS WILLING TO GET RENEW DSC FROM US THEN SEND CONTECT DETAILS OF ARSHI MEDAM MO. 7575099680 TO CLIENT</B></P>
														A. FIRST CALL FOR DSC SIGNATURE ON &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:120px;" type="text"  name="seventeen[17_1_from]" class="onetime"  value="{{($decodedDAta['seventeen']['17_1_from'])??''}}"> AND MAIL ON <input style="width:120px;"type="text"  name="seventeen[17_2_from]" class="onetime"  value="{{($decodedDAta['seventeen']['17_2_from'])??''}}"><BR>
														B. SECOND CALL FOR DSC SIGNATURE ON <input style="width:120px;" type="text"  name="seventeen[17_3_from]" class="onetime"  value="{{($decodedDAta['seventeen']['17_3_from'])??''}}"> AND MAIL ON <input style="width:120px;"type="text"  name="seventeen[17_4_from]" class="onetime"  value="{{($decodedDAta['seventeen']['17_4_from'])??''}}"><BR>
														C. THIRD CALL FOR DSC SIGNATURE ON &nbsp;&nbsp;&nbsp;&nbsp;<input style="width:120px;" type="text"  name="seventeen[17_5_from]" class="onetime"  value="{{($decodedDAta['seventeen']['17_5_from'])??''}}"> AND MAIL ON <input style="width:120px;"type="text"  name="seventeen[17_6_from]" class="onetime"  value="{{($decodedDAta['seventeen']['17_6_from'])??''}}"><BR>
														<P STYLE = "COLOR:BLUE"><B>IF RENEWAL PROCESS IS STILL NOT INITIATE BY CLIENT THE GIVE LAST REMINDER BY CALL AND MAIL WITH INFORMATION THAT  “NOW WE WILL INITIATE DSC ACTIVATION PROCESS WHEN YOU SEND DSC TO OUR OFFICE" </B></P>    
														D. FOURTH AND FINAL CALL FOR DSC SIGNATURE ON <input style="width:120px;" type="text"  name="seventeen[17_7_from]" class="onetime"  value="{{($decodedDAta['seventeen']['17_7_from'])??''}}"> AND MAIL ON <input style="width:120px;"type="text"  name="seventeen[17_8_from]" class="onetime"  value="{{($decodedDAta['seventeen']['17_8_from'])??''}}">            
												</td>
												<td class="p-7">

													<textarea rows="1"  name="seventeen[comment]" class="w-100">{{($decodedDAta['seventeen']['comment'])??''}}</textarea>
													
													
												
												</td>
												<td class="p-7">
													{{($decodedDAta['seventeen']['user_name'])??''}}
												</td>
												<td class="p-7">
													<?php 
													if(isset($decodedDAta['seventeen']['date_time']) && $decodedDAta['seventeen']['date_time']!=""){
														$timestamp =$decodedDAta['seventeen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
												</td>

												<input type="hidden" value="{{($decodedDAta['seventeen']['user_name'])??''}}" name="seventeen[savedby]">
												
											
											</tr>

												
												
												
											   










											<tr>
												<td class="p-7">
													<input type="checkbox" name="two[checkbox]"  <?php if(isset($decodedDAta['two']['checkbox']) && $decodedDAta['two']['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													2.  REGISTERE DSC IN UNIFIED PORTAL ON DATED 
													<input type="text"  name="two[2_from]" class="onetime"  value="{{($decodedDAta['two']['2_from'])??''}}">
													</td>
													<td class="p-7">

														<textarea rows="1"  type="text" name="two[comment]" class="w-100">{{($decodedDAta['two']['comment'])??''}}
														</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['two']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['two']['date_time']) && $decodedDAta['two']['date_time']!=""){
														$timestamp =$decodedDAta['two']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['two']['user_name'])??''}}" name="two[savedby]">

											</tr>
											<tr>	
												<td class="p-7">
													<input type="checkbox" name="three[checkbox]"  <?php if(isset($decodedDAta['three']['checkbox']) && $decodedDAta['three']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													3.  MAIL DSC ACTIVATION LETTER AND SPECIMEN CARD TO ESTABLISHMENT
														<td class="p-7">
														<textarea rows="1"  name="three[comment]" class="w-100">{{($decodedDAta['three']['comment'])??''}}</textarea></td>
													<td class="p-7">
														{{($decodedDAta['three']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['three']['date_time']) && $decodedDAta['three']['date_time']!=""){
														$timestamp =$decodedDAta['three']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['three']['user_name'])??''}}" name="three[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="four[checkbox]"  <?php if(isset($decodedDAta['four']['checkbox']) && $decodedDAta['four']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													4.  CALL TO ESTABLISHMENT AND EXPLAIN WHERE THEY SUPPOSE TO SIGN AND SEAL
												</td>
												<td class="p-7">
													<textarea rows="1"  name="four[comment]" class="w-100">{{($decodedDAta['four']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['four']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['four']['date_time']) && $decodedDAta['four']['date_time']!=""){
														$timestamp =$decodedDAta['four']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['four']['user_name'])??''}}" name="four[savedby]">
											</tr>
											<tr>
												<td class="p-7"><input type="checkbox" name="five[checkbox]"  <?php if(isset($decodedDAta['five']['checkbox']) && $decodedDAta['five']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													5.  UPLOAD SIGNED DSC ACTIVATION LETTER AND SPECIMAN CARD IN UNIFIED PORTAL DATED 
													<input type="text"  name="five[5_from]" class="onetime"  value="{{($decodedDAta['five']['5_from'])??''}}">
													</td>
												<td class="p-7">
													<textarea rows="1"  name="five[comment]" class="w-100">{{($decodedDAta['five']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['five']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['five']['date_time']) && $decodedDAta['five']['date_time']!=""){
														$timestamp =$decodedDAta['five']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['five']['user_name'])??''}}" name="five[savedby]">
											</tr>
											<tr>
												<td class="p-7"><input type="checkbox" name="six[checkbox]"  <?php if(isset($decodedDAta['six']['checkbox']) && $decodedDAta['six']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													6.  GET FOUR COPY OF DSC ACTIVATION LETTER AND SPECIMAN CARD IN HARD COPY
												</td>
												<td class="p-7">

														<textarea rows="1"  name="six[comment]" class="w-100">{{($decodedDAta['six']['comment'])??''}}</textarea>
														</td>
														<td class="p-7">
														{{($decodedDAta['six']['user_name'])??''}}
												</td>
												<td class="p-7">
													<?php 
													if(isset($decodedDAta['six']['date_time']) && $decodedDAta['six']['date_time']!=""){
														$timestamp =$decodedDAta['six']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
												</td>
												<input type="hidden" value="{{($decodedDAta['six']['user_name'])??''}}" name="six[savedby]">
											</tr>
											<tr>
												<td class="p-7"><input type="checkbox" name="seven[checkbox]"  <?php if(isset($decodedDAta['seven']['checkbox']) && $decodedDAta['seven']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													7.  INWARD 3 COPY DSC ACTIVATION LETTER AND SPECIMAN CARD TO EPF OFFICE DATED 
													<input type="text"  name="seven[7_from]" class="onetime"  value="{{($decodedDAta['seven']['7_from'])??''}}">
												</td>
												<td class="p-7">
													<textarea rows="1"  name="seven[comment]" class="w-100">{{($decodedDAta['seven']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['seven']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['seven']['date_time']) && $decodedDAta['seven']['date_time']!=""){
														$timestamp =$decodedDAta['seven']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['seven']['user_name'])??''}}" name="seven[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="eight[checkbox]"  <?php if(isset($decodedDAta['eight']['checkbox']) && $decodedDAta['eight']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													8.  MAIL RECEIVED COPY OF DSC ACTIVATION LETTER AND SPECIMAN CARD TO CONCERN EPF OFFICE AND CLIENT 
													</td>
													<td class="p-7">
														<textarea rows="1"  name="eight[comment]" class="w-100">{{($decodedDAta['eight']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['eight']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['eight']['date_time']) && $decodedDAta['eight']['date_time']!=""){
														$timestamp =$decodedDAta['eight']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['eight']['user_name'])??''}}" name="eight[savedby]">
											</tr>
											<tr>
												<td class="p-7"><input type="checkbox" name="nine[checkbox]" <?php if(isset($decodedDAta['nine']['checkbox']) && $decodedDAta['nine']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													9.  SEND 1ST REMINDER MAIL FOR DSC ACTIVATION AFTER 2 WORKING DAYS AFTER DATE OF INWARD.  DATED
													    <input type="text"  name="nine[9_from]" class="onetime"  value="{{($decodedDAta['nine']['9_from'])??''}}">
													</td>
													<td class="p-7">
														<textarea rows="1"  name="nine[comment]" class="w-100">{{($decodedDAta['nine']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['nine']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['nine']['date_time']) && $decodedDAta['nine']['date_time']!=""){
														$timestamp =$decodedDAta['nine']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['nine']['user_name'])??''}}" name="nine[savedby]">
											</tr>

											<tr>
												<td class="p-7"><input type="checkbox" name="ten[checkbox]"  <?php if(isset($decodedDAta['ten']['checkbox']) && $decodedDAta['ten']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
												    10. SEND 2ND REMINDER MAIL NEXT DAY. DATED 
												    <input type="text"  name="ten[10_from]" class="onetime"  value="{{($decodedDAta['ten']['10_from'])??''}}">
													</td>
												<td class="p-7">
													<textarea rows="1"  name="ten[comment]" class="w-100">{{($decodedDAta['ten']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['ten']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['ten']['date_time']) && $decodedDAta['ten']['date_time']!=""){
														$timestamp =$decodedDAta['ten']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['ten']['user_name'])??''}}" name="ten[savedby]">
											</tr>
											<tr>
												<td class="p-7"><input type="checkbox" name="eleven[checkbox]"  <?php if(isset($decodedDAta['eleven']['checkbox']) && $decodedDAta['eleven']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													11. SEND 3RD REMINDER MAIL NEXT DAY. DATED 
												    <input type="text"  name="elevan[11_from]" class="onetime"  value="{{($decodedDAta['elevan']['11_from'])??''}}">
													</td>
												<td class="p-7">
													<textarea rows="1"  name="eleven[comment]" class="w-100">{{($decodedDAta['eleven']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['eleven']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['eleven']['date_time']) && $decodedDAta['eleven']['date_time']!=""){
														$timestamp =$decodedDAta['eleven']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['eleven']['user_name'])??''}}" name="eleven[savedby]">
											</tr>

											<tr>
												<td class="p-7">
													<input type="checkbox" name="twelve[checkbox]"  <?php if(isset($decodedDAta['twelve']['checkbox']) && $decodedDAta['twelve']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													12. IF DSC IS NOT ACTIVE TILL DATE THEN FILE GRIEVANCE AT
													<a href="https://epfigms.gov.in/Grievance/GrievanceMaster" target="_blank">EPF GREVIANCE PORTAL</a> USING COMPANY’S CREDENTIALS. DATED 
												    <input type="text"  name="twelve[12_from]" class="onetime"  value="{{($decodedDAta['twelve']['12_from'])??''}}"> <P STYLE = "COLOR:RED"><B>[ CALL AURTHORIZED PERSON OF THE ESTABLISHMENT AND TELL HIM NOT TO DISCLOSE THE INVOLVEMENT OF RAJ CONSULTANCY REGARARING GREVIANCE MATTER IF THEY RECEIVED CALL FROM EPFO]</B></P>
												   
												    <?php if($decodedDAta['one']['1_eonm']!="" && $decodedDAta['two']['2_from']!="" && $decodedDAta['five']['5_from']!="" && $decodedDAta['seven']['7_from']!=""){?>
												     <BR><U>USE BELLOW CONTENT FOR GREVIANCE</BR></U>
												    <br>My grievance is regarding activation of digital signature of my establishment having the PF code <B><U>{{($decodedDAta['one']['1_eonm'])??''}}.</U></B> I have registered DSC in my employer portal on <U><B>{{($decodedDAta['two']['2_from'])??''}}</B></U> and the signed DSC letter with the specimen signature is uploaded on <B><U>{{($decodedDAta['five']['5_from'])??''}}.</U></B> I had also submitted it to the PF office on <B><U> {{($decodedDAta['seven']['7_from'])??''}}.</U></B> Even after completing all these processes and after giving the several reminders in mail, till date my DSC is not active. Due to this I can not approve the KYC details which are filed by my employees. You are requested to approve my request of DSC activation so that I can do my work related to PF smoothly. Thank you<br\>
												    <?php } ?>
													</td>
													<td class="p-7">
														<textarea rows="1"  name="twelve[comment]" class="w-100">{{($decodedDAta['twelve']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twelve']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twelve']['date_time']) && $decodedDAta['twelve']['date_time']!=""){
														$timestamp =$decodedDAta['twelve']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twelve']['user_name'])??''}}" name="twelve[savedby]">
											</tr>
                                            
                                            <tr>
												<td class="p-7">
													<input type="checkbox" name="thirteen[checkbox]"  <?php if(isset($decodedDAta['thirteen']['checkbox']) && $decodedDAta['thirteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													13.  SAVE GREVIANCE REGISTRATION NUMBER :
													<input style="width:160px;"  type="text" name="thirteen_ID[13_ID_eonm]" value="{{($decodedDAta['thirteen_ID']['13_ID_eonm'])??''}}"> PASSWORD
													<input style="width:130px;"  type="text" name="thirteen_PASS[13_PASS_eonm]" value="{{($decodedDAta['thirteen_PASS']['13_PASS_eonm'])??''}}">
													
												</td>
												<td class="p-7">

													<textarea rows="1"  name="thirteen[comment]" class="w-100">{{($decodedDAta['thirteen']['comment'])??''}}</textarea>
												</td>
												<td class="p-7">
													{{($decodedDAta['thirteen']['user_name'])??''}}
												</td>
												<td class="p-7">
													<?php 
													if(isset($decodedDAta['thirteen']['date_time']) && $decodedDAta['thirteen']['date_time']!=""){
														$timestamp =$decodedDAta['thirteen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
												</td>

												<input type="hidden" value="{{($decodedDAta['thirteen']['user_name'])??''}}" name="thirteen[savedby]">
											</tr>

											<tr>
												<td class="p-7">
													<input type="checkbox" name="fourteen[checkbox]"  <?php if(isset($decodedDAta['fourteen']['checkbox']) && $decodedDAta['fourteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													14. CHECK GREVIANCE OF DSC ACTIVATION STATUS AND GRIVIANCE STATUS EVERY DAY FOR 5 DAYS
													</td>
													<td class="p-7">
														<textarea rows="1"  name="fourteen[comment]" class="w-100">{{($decodedDAta['fourteen']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['fourteen']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['fourteen']['date_time']) && $decodedDAta['fourteen']['date_time']!=""){
														$timestamp =$decodedDAta['fourteen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['fourteen']['user_name'])??''}}" name="fourteen[savedby]">
											</tr>

											<tr>
												<td class="p-7">
													<input type="checkbox" name="fifteen[checkbox]"  <?php if(isset($decodedDAta['fifteen']['checkbox']) && $decodedDAta['fifteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													15. UPDATE THE FINAL DSC ACTIVATION STATUS IN MENU NO. 16;20;2 IN <B>RAJKOT OFFICE</B> SOFTWARE AND CLOSE THE TICKET</td>
													<td class="p-7">
														<textarea rows="1"  name="fifteen[comment]" class="w-100">{{($decodedDAta['fifteen']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['fifteen']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['fifteen']['date_time']) && $decodedDAta['fifteen']['date_time']!=""){
														$timestamp =$decodedDAta['fifteen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['fifteen']['user_name'])??''}}" name="fifteen[savedby]">
											</tr>
</tbody>
										   	</table>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="card-body">
										
								</div>
								</div>
								
							</div>
							
							<!-- /.card-body -->

							<div class="card-footer">
								<a href="{{ route('ticket.index') }}" class="btn btn-secondary">Cancel</a>
								<button type="submit" class="btn btn-primary float-right">Save Details</button>
							</div>
						</form>
					</div>
					<!-- /.card -->
				</div>
				<!-- /.card -->
			</div>
			<!--/.col (right) -->
		</div>
		<!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
.p-7{
	 padding: 7px;
}
input[type=text] {
	    margin: 2px 5px 1px 4px !important;

}
input[type=checkbox]{
    width: 30px;
    height: 21px;
    vertical-align: middle;
}
table th {
    position:sticky;
    top:0;
    z-index:1;
    border-top:0;
    background: #ededed;
}
</style>
@endsection
@section('page-js-script')
<script type="text/javascript">
	$(document).ready(function () {
		$("#task_title").focus();
		// $("#task_tigger_date").datepicker();
		$('.onetime').datepicker({
			dateFormat: 'dd/mm/yy',
			//minDate: new Date(),
			inline: true
    	});
	 	$('#employee-grid-35').DataTable({ 
	 		fixedHeader: {
            	header: true,
        	},
    	  "pageLength": 150,
    	    "ordering": false


        });
	});

</script>
@stop