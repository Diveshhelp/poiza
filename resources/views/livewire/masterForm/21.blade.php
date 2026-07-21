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
									<div class="card-body">
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
										<table class="w-100" border="1">
											<tr style="background-color:#e6dcdc;">
												<th class="text-center" width="60%"><b>Check Points</b></th>
												<th class="text-center" width="20%"><b>Comments</b></th>
												<th class="text-center" width="10%"><b>UID</b></th>
												<th class="text-center" width="10%"><b>Date/& Time</b></th>
											</tr>
											<tr>
												<?php $common="one";?>
												<td class="p-7">
													<input type="checkbox" name="{{$common}}[checkbox]"  <?php if(isset($decodedDAta[$common]['checkbox']) && $decodedDAta[$common]['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													1. DOCUMENT RECEIVED FOR NEW PF & ESI NO
												</td>
												<td class="p-7">

													<textarea rows="1"  name="{{$common}}[comment]" class="w-100">{{($decodedDAta[$common]['comment'])??''}}</textarea>
												</td>
												<td class="p-7">
													{{($decodedDAta[$common]['user_name'])??''}}
												</td>
												<td class="p-7">
													<?php 
													if(isset($decodedDAta[$common]['date_time']) && $decodedDAta[$common]['date_time']!=""){
														$timestamp =$decodedDAta[$common]['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
												</td>

												<input type="hidden" value="{{($decodedDAta[$common]['user_name'])??''}}" name="{{$common}}[savedby]">
											</tr>

											<tr>
												<?php $common="two";?>
												<td class="p-7">
													<input type="checkbox" name="two[checkbox]"  <?php if(isset($decodedDAta['two']['checkbox']) && $decodedDAta['two']['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													2. ESTABLISHMENT FOLDER DEVELOP IN RAJKOT FOLDER</td>
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
													3. ALL MASTER DOC. SCAN & SAVE IN RAJKOT / ESTABLISHMENT FOLDER
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
													4. PREPARE SEPRATE FILE & FILE ALL DOCUMENTS
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
													5. OPEN SHRAM SUVIDHA PORTAL & CREATE USER ID<input style="width:120px;"  type="text" name="five[5_unam]" value="{{($decodedDAta['five']['5_unam'])??''}}"> PASSWORD<input  style="width:120px;"  type="text" name="five[5_pass]" value="{{($decodedDAta['five']['5_pass'])??''}}">
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
													6. APPLIED IN PF & ESI REGISTRATION
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
													7. OPEN ESTABLISHMENT IN OUR SOFTWARE WITH PF NO IN MENU NO. 2;11;1
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
													8. SELECT MUSTER & WAGE REGISTER FORMAT IN MENU NO. 2;12;1 & 2</td>
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
													9. INSERT ALLOWANCE & DEDUCTION HEAD IN 2;25 SCR NO 14 TO 17</td>
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
													10. INSERT PF ID <input style="width:120px;"  type="text" name="ten[10_unam]" value="{{($decodedDAta['ten']['10_unam'])??''}}">  & PASSWORD <input  style="width:120px;"  type="text" name="ten[10_pass]" value="{{($decodedDAta['ten']['10_pass'])??''}}"> IN 2;25 SCR NO 18. 
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
													11. INSERT MOBILE NUMBER OF CONTACT PERSON IN MENU NO 2:25  SCR NO : 2 <input style="width:120px;"  type="text" name="eleven[mobile_number]" value="{{($decodedDAta['eleven']['mobile_number'])??''}}"> 
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
													12. ALLOTE FILE NO IN 2;25 SCR 1 DISP 4</td>
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
													<input type="checkbox" name="fifty[checkbox]"  <?php if(isset($decodedDAta['fifty']['checkbox']) && $decodedDAta['fifty']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													13. CREATE REPORT MASTER IN 16;5</td>
													<td class="p-7">
														<textarea rows="1"  name="fifty[comment]" class="w-100">{{($decodedDAta['fifty']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['fifty']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['fifty']['date_time']) && $decodedDAta['fifty']['date_time']!=""){
														$timestamp =$decodedDAta['fifty']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['fifty']['user_name'])??''}}" name="fifty[savedby]">
											</tr>


											<tr>
												<td class="p-7">
													<input type="checkbox" name="thirteen[checkbox]"  <?php if(isset($decodedDAta['thirteen']['checkbox']) && $decodedDAta['thirteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													14. CREATE REPORT MASTER IN 16;5</td>
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
													15. IF ESTABLISHMENT IS PERTAINING TO ANY BRANCH THEN ADD REMARKS FOR MAIL IN MENU NO. 16;5</td>
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
													16. DO DIGITAL INWARD ENTRY IN DIGITAL MANAGEMENT IN 16;20</td>
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
<tr>
												<td class="p-7">
													<input type="checkbox" name="sixteen[checkbox]"  <?php if(isset($decodedDAta['sixteen']['checkbox']) && $decodedDAta['sixteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													17. ESTABLISHMENT MAIL ID SAVE IN GMAIL AND ASSIGN LABLE AS PER THE ALPHABATES OF THE COMPANY & SAVE MAIL ID IN OUR SOFTWAR IN 2;25 SCR NO 5</td>
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
													<input type="hidden" value="{{($decodedDAta['sixteen']['user_name'])??''}}" name="sixteen[savedby]">
											</tr>

<tr>
												<td class="p-7">
													<input type="checkbox" name="seventeen[checkbox]"  <?php if(isset($decodedDAta['seventeen']['checkbox']) && $decodedDAta['seventeen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													18. ESTABLISHMENT TRANSFER FROM DUMMY TO ORIGINAL SOFTWARE</td>
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
													<input type="checkbox" name="eighteen[checkbox]"  <?php if(isset($decodedDAta['eighteen']['checkbox']) && $decodedDAta['eighteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													19. INSERT BILL ENTRY IN EXCELL SHEET SAVED IN THE LABLE OF DHIRUBHAI</td>
													<td class="p-7">
														<textarea rows="1"  name="eighteen[comment]" class="w-100">{{($decodedDAta['eighteen']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['eighteen']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['eighteen']['date_time']) && $decodedDAta['eighteen']['date_time']!=""){
														$timestamp =$decodedDAta['eighteen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['eighteen']['user_name'])??''}}" name="eighteen[savedby]">
											</tr>


											<tr>
												<td class="p-7">
													<input type="checkbox" name="nineteen[checkbox]"  <?php if(isset($decodedDAta['nineteen']['checkbox']) && $decodedDAta['nineteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													20. ADD GST NO IN 2,25 SCR - 21 & DO ENTRY OF RUBBER STAMP IN SCR - 6</td>
													<td class="p-7">
														<textarea rows="1"  name="nineteen[comment]" class="w-100">{{($decodedDAta['nineteen']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['nineteen']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['nineteen']['date_time']) && $decodedDAta['nineteen']['date_time']!=""){
														$timestamp =$decodedDAta['nineteen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['nineteen']['user_name'])??''}}" name="nineteen[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="twenty[checkbox]"  <?php if(isset($decodedDAta['twenty']['checkbox']) && $decodedDAta['twenty']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													21. ANN/SUB. Fees 
													<input style="width:60px;" type="text" name="twenty[ann_fees]" value="{{($decodedDAta['twenty']['ann_fees'])??''}}">/
													<input style="width:60px;" type="text" name="twenty[ann_fees2]" value="{{($decodedDAta['twenty']['ann_fees2'])??''}}">, Reg Fees PF
													<input style="width:60px;" type="text" name="twenty[reg_fees]" value="{{($decodedDAta['twenty']['reg_fees'])??''}}">, ESI
													<input style="width:60px;" type="text" name="twenty[esi_fees]" value="{{($decodedDAta['twenty']['esi_fees'])??''}}">, PT
													<input style="width:60px;" type="text" name="twenty[pt_fees]" value="{{($decodedDAta['twenty']['pt_fees'])??''}}">& UPDATE IT IN 2.25 SCR NO.29 IN SOFTWARE
												</td>
													<td class="p-7">
														<textarea rows="1"  name="twenty[comment]" class="w-100">{{($decodedDAta['twenty']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twenty']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twenty']['date_time']) && $decodedDAta['twenty']['date_time']!=""){
														$timestamp =$decodedDAta['twenty']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twenty']['user_name'])??''}}" name="twenty[savedby]">
											</tr>


												<tr>
												<td class="p-7">
													<input type="checkbox" name="twentyone[checkbox]"  <?php if(isset($decodedDAta['twentyone']['checkbox']) && $decodedDAta['twentyone']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													22. DOWNLOAD PF & ESI CODE LETTER, SAVE TO RAJKOT FOLDER & MAIL TO ESTABLISHMENT.</td>
													<td class="p-7">
														<textarea rows="1"  name="twentyone[comment]" class="w-100">{{($decodedDAta['twentyone']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentyone']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentyone']['date_time']) && $decodedDAta['twentyone']['date_time']!=""){
														$timestamp =$decodedDAta['twentyone']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentyone']['user_name'])??''}}" name="twentyone[savedby]">
											</tr>

											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentytwo[checkbox]"  <?php if(isset($decodedDAta['twentytwo']['checkbox']) && $decodedDAta['twentytwo']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													23. DESIGN EXCEL STRUCTURE FOR UPLOAD DATA</td>
													<td class="p-7">
														<textarea rows="1"  name="twentytwo[comment]" class="w-100">{{($decodedDAta['twentytwo']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentytwo']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentytwo']['date_time']) && $decodedDAta['twentytwo']['date_time']!=""){
														$timestamp =$decodedDAta['twentytwo']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentytwo']['user_name'])??''}}" name="twentytwo[savedby]">
											</tr>


											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentythree[checkbox]"  <?php if(isset($decodedDAta['twentythree']['checkbox']) && $decodedDAta['twentythree']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													24. FILING SYSTEM MAIL TO ESTABLISHMENT	(√)	PT
													<input style="width:60px;" type="text" name="twentythree[pt_23]" value="{{($decodedDAta['twentythree']['pt_23'])??''}}">PF
													<input style="width:60px;" type="text" name="twentythree[pf_23]" value="{{($decodedDAta['twentythree']['pf_23'])??''}}">ESI
													<input style="width:60px;" type="text" name="twentythree[esi_23]" value="{{($decodedDAta['twentythree']['esi_23'])??''}}"></td>
													<td class="p-7">
														<textarea rows="1"  name="twentythree[comment]" class="w-100">{{($decodedDAta['twentythree']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentythree']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentythree']['date_time']) && $decodedDAta['twentythree']['date_time']!=""){
														$timestamp =$decodedDAta['twentythree']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentythree']['user_name'])??''}}" name="twentythree[savedby]">
											</tr>

											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentyFOUR[checkbox]"  <?php if(isset($decodedDAta['twentyFOUR']['checkbox']) && $decodedDAta['twentyFOUR']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													25. SAVE SHARM SUVIDHA LOGIN ID & PASSWORD TO OUR SOFTWARE IN 2;25 SCR NO 20</td>
													<td class="p-7">
														<textarea rows="1"  name="twentyFOUR[comment]" class="w-100">{{($decodedDAta['twentyFOUR']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentyFOUR']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentyFOUR']['date_time']) && $decodedDAta['twentyFOUR']['date_time']!=""){
														$timestamp =$decodedDAta['twentyFOUR']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentyFOUR']['user_name'])??''}}" name="twentyFOUR[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentyfive[checkbox]"  <?php if(isset($decodedDAta['twentyfive']['checkbox']) && $decodedDAta['twentyfive']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													26. CHANGE PF CODE IN 2,25 SCR - 30 IF THE CODE SAVE IN THIS MENU IS NOT SIMILAR TO EPF CODE NO.</td>
													<td class="p-7">
														<textarea rows="1"  name="twentyfive[comment]" class="w-100">{{($decodedDAta['twentyfive']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentyfive']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentyfive']['date_time']) && $decodedDAta['twentyfive']['date_time']!=""){
														$timestamp =$decodedDAta['twentyfive']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentyfive']['user_name'])??''}}" name="twentyfive[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentySIX[checkbox]"  <?php if(isset($decodedDAta['twentySIX']['checkbox']) && $decodedDAta['twentySIX']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													27. PREPARE SPECIMEN SIGNATURE & DIGITAL ACTIVATION PDF & ARRANGE AS PER LETTER HEAD</td>
													<td class="p-7">
														<textarea rows="1"  name="twentySIX[comment]" class="w-100">{{($decodedDAta['twentySIX']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentySIX']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentySIX']['date_time']) && $decodedDAta['twentySIX']['date_time']!=""){
														$timestamp =$decodedDAta['twentySIX']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentySIX']['user_name'])??''}}" name="twentySIX[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentySEVEN[checkbox]"  <?php if(isset($decodedDAta['twentySEVEN']['checkbox']) && $decodedDAta['twentySEVEN']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													28. MAIL THIS ABOVE 2 PDF & CALL TO PARTY AND EXPLAIN</td>
													<td class="p-7">
														<textarea rows="1"  name="twentySEVEN[comment]" class="w-100">{{($decodedDAta['twentySEVEN']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentySEVEN']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentySEVEN']['date_time']) && $decodedDAta['twentySEVEN']['date_time']!=""){
														$timestamp =$decodedDAta['twentySEVEN']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentySEVEN']['user_name'])??''}}" name="twentySEVEN[savedby]">
											</tr>
										
										<tr>
												<td class="p-7">
													<input type="checkbox" name="thirtyfour[checkbox]"  <?php if(isset($decodedDAta['thirtyfour']['checkbox']) && $decodedDAta['thirtyfour']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													29. CREATE TICKET FOR DSC ACTIVATION PROCESS WITH THE HELP OF JUHI MEDAM</td>
													<td class="p-7">
														<textarea rows="1"  name="thirtyfour[comment]" class="w-100">{{($decodedDAta['thirtyfour']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['thirtyfour']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['thirtyfour']['date_time']) && $decodedDAta['thirtyfour']['date_time']!=""){
														$timestamp =$decodedDAta['thirtyfour']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['thirtyfour']['user_name'])??''}}" name="thirtyfour[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentyeight[checkbox]"  <?php if(isset($decodedDAta['twentyeight']['checkbox']) && $decodedDAta['twentyeight']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													30. INSERT ESI ID
													<input style="width:120px;" type="text" name="twentyeight[pt_23]" value="{{($decodedDAta['twentyeight']['pt_23'])??''}}"> & PASSWORD
													<input style="width:120px;" type="text" name="twentyeight[pf_23]" value="{{($decodedDAta['twentyeight']['pf_23'])??''}}"> IN 2;25 SCR NO. 19
													
													<td class="p-7">
														<textarea rows="1"  name="twentyeight[comment]" class="w-100">{{($decodedDAta['twentyeight']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentyeight']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentyeight']['date_time']) && $decodedDAta['twentyeight']['date_time']!=""){
														$timestamp =$decodedDAta['twentyeight']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentyeight']['user_name'])??''}}" name="twentyeight[savedby]">
											</tr>
											
											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentynine[checkbox]"  <?php if(isset($decodedDAta['twentynine']['checkbox']) && $decodedDAta['twentynine']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													31. INSERT ESI NO. IN 2;25 SCR NO 9</td>
													<td class="p-7">
														<textarea rows="1"  name="twentynine[comment]" class="w-100">{{($decodedDAta['twentynine']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentynine']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentynine']['date_time']) && $decodedDAta['twentynine']['date_time']!=""){
														$timestamp =$decodedDAta['twentynine']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentynine']['user_name'])??''}}" name="twentynine[savedby]">
											</tr>
												<tr>
												<td class="p-7">
													<input type="checkbox" name="thirty[checkbox]"  <?php if(isset($decodedDAta['thirty']['checkbox']) && $decodedDAta['thirty']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													32. GENERATE ALL TIC WITH IN 10 DAYS FROM DATE OF COVERAGE AND DOWNLOAD ALL TIC AND SAVE IN TO RAJKOT FOLDER</td>
													<td class="p-7">
														<textarea rows="1"  name="thirty[comment]" class="w-100">{{($decodedDAta['thirty']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['thirty']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['thirty']['date_time']) && $decodedDAta['thirty']['date_time']!=""){
														$timestamp =$decodedDAta['thirty']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['thirty']['user_name'])??''}}" name="thirty[savedby]">
											</tr>
												<tr>
												<td class="p-7">
													<input type="checkbox" name="thirtyone[checkbox]"  <?php if(isset($decodedDAta['thirtyone']['checkbox']) && $decodedDAta['thirtyone']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													33. UPDATE DIGITAL INWARD ENTRY IN THE ORIGINAL SOFTWARE IN MENU NO. 16;20;1</td>
													<td class="p-7">
														<textarea rows="1"  name="thirtyone[comment]" class="w-100">{{($decodedDAta['thirtyone']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['thirtyone']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['thirtyone']['date_time']) && $decodedDAta['thirtyone']['date_time']!=""){
														$timestamp =$decodedDAta['thirtyone']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['thirtyone']['user_name'])??''}}" name="thirtyone[savedby]">
											</tr>
							            	<tr>
												<td class="p-7">
													<input type="checkbox" name="thirtytwo[checkbox]"  <?php if(isset($decodedDAta['thirtytwo']['checkbox']) && $decodedDAta['thirtytwo']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													34. MARK THIS TICEKT AS CLOSED</td>
													<td class="p-7">
														<textarea rows="1"  name="thirtytwo[comment]" class="w-100">{{($decodedDAta['thirtytwo']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['thirtytwo']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['thirtytwo']['date_time']) && $decodedDAta['thirtytwo']['date_time']!=""){
														$timestamp =$decodedDAta['thirtytwo']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['thirtytwo']['user_name'])??''}}" name="thirtytwo[savedby]">
											</tr>
							
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
	
	});

</script>
@stop