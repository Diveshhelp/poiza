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
												<td class="p-7">
													<input type="checkbox" name="one[checkbox]"  <?php if(isset($decodedDAta['one']['checkbox']) && $decodedDAta['one']['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													1. NAME OF SSO SHRI 
													<input style="width:260px;"  type="text" name="one[1_eonm]" value="{{($decodedDAta['one']['1_eonm'])??''}}"> MOBILE NO.
													<input  style="width:160px;"  type="text" name="one[1_eomo]" value="{{($decodedDAta['one']['1_eomo'])??''}}">
												</td>
												<td class="p-7">

													<textarea rows="1"  name="one[comment]" class="w-100">{{($decodedDAta['one']['comment'])??''}}</textarea>
												</td>
												<td class="p-7">
													{{($decodedDAta['one']['user_name'])??''}}
												</td>
												<td class="p-7">
													<?php 
													if(isset($decodedDAta['one']['date_time']) && $decodedDAta['one']['date_time']!=""){
														$timestamp =$decodedDAta['one']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
												</td>

												<input type="hidden" value="{{($decodedDAta['one']['user_name'])??''}}" name="one[savedby]">
											</tr>

											<tr>
												<td class="p-7">
													<input type="checkbox" name="two[checkbox]"  <?php if(isset($decodedDAta['two']['checkbox']) && $decodedDAta['two']['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													2. INSPECTION TIME LIMIT DATE FROM 
													<input type="text"  name="two[2_from]" class="onetime"  value="{{($decodedDAta['two']['2_from'])??''}}">TO
													<input type="text"  name="two[2_to]" class="onetime"  value="{{($decodedDAta['two']['2_to'])??''}}">
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
													3. ESTABLISHMENT CONTECT PERSON NAME SHRI
													<input style="width:260px;"  type="text" name="three[3_con]" value="{{($decodedDAta['three']['3_con'])??''}}"> MOBILE NO.
													<input  style="width:160px;"  type="text" name="three[3_mob]" value="{{($decodedDAta['three']['3_mob'])??''}}">
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
													4. SEND ESTABLISHMENT CONTECT PERSON MOBILE NUMBER AND NAME TO ESI INSPECTOR & CALL HIM TO INFROM ABOUT ESI INSPECTION
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
													5. ESI USER ID<input style="width:160px;"  type="text" name="five[7_unam]" value="{{($decodedDAta['five']['7_unam'])??''}}"> PASSWORD<input  style="width:100px;"  type="text" name="five[7_pass]" value="{{($decodedDAta['five']['7_pass'])??''}}">
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
													6. PRINT EMPLOYER ADDRESS & BANK DETAILS ON LETTER HEAD OF THE ESTABLISHMENT
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
													7. GET LETTER HEAD A4 SIZE - 6 COPY & RUBBER STAMP
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
													8. GET COPY OF ESI INSPECTION VISIT NOTE (IF AVAILABLE)
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
													9. PREPARE EC STATEMENT & EMPLOYEE STRENGTH BEFORE COVERAGE DATE
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
													10. FOLLOW-UP FOR THE COLLECTION OF AUDIT REPORT, BALANCE SHEET, PROFIT & LOSS ACCOUNT AND 26AS, VOUCHERES, FOR THE INSPECTION PERIOD OF LAST 5 YEAR OR DATE OF SETUP WHICH EVER IS OLD.
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
												<td class="p-7">
													<input type="checkbox" name="fourteen[checkbox]"  <?php if(isset($decodedDAta['fourteen']['checkbox']) && $decodedDAta['fourteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													11. SAVE IT IN 1 RAJKOT FOLDER>REPLY FOLDER> ESI INSPECTION AND CREATE SHORTCUT KEY TO SIR COMMON FOLDER>INSPECTION FOLDER>COMPANY NAME FOLDER</td>
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
												<td class="p-7"><input type="checkbox" name="eleven[checkbox]"  <?php if(isset($decodedDAta['eleven']['checkbox']) && $decodedDAta['eleven']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													12. DATE OF SETUP 
													<input type="text"  name="elevan[11_setup]" class="onetime"  value="{{($decodedDAta['elevan']['11_setup'])??''}}"> ESI W.E.F. 
													<input type="text"  name="elevan[11_pf]" class="onetime"  value="{{($decodedDAta['elevan']['11_pf'])??''}}">
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
													13. LAST ESI INSPECTION
													<input type="text"  name="twelve[12_ins]" class="onetime"  value="{{($decodedDAta['twelve']['12_ins'])??''}}"> (IF AVAILABLE)
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
													<input type="checkbox" name="thirteen[checkbox]"  <?php if(isset($decodedDAta['thirteen']['checkbox']) && $decodedDAta['thirteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													14. INSPECTION PERIOD FROM 
													<input type="text"  name="thirteen[13_from]" class="onetime"  value="{{($decodedDAta['thirteen']['13_from'])??''}}"> TO
													<input type="text"  name="thirteen[13_to]" class="onetime"  value="{{($decodedDAta['thirteen']['13_to'])??''}}">
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
													<input type="checkbox" name="eighteen[checkbox]"  <?php if(isset($decodedDAta['eighteen']['checkbox']) && $decodedDAta['eighteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													15. INSERT BILL ENTRY IN <br>INSPECTION DUES 
													FROM<input style="width:160px;" type="text" name="eighteen1[18_fees]" value="{{($decodedDAta['eighteen1']['18_fees'])??''}}">
													TO<input style="width:160px;" type="text" name="eighteen2[18_fees]" value="{{($decodedDAta['eighteen2']['18_fees'])??''}}">,<br>INSPECTION  PERIOD  
													FROM<input style="width:160px;" type="text" name="eighteen3[18_fees]" value="{{($decodedDAta['eighteen3']['18_fees'])??''}}">
													TO<input style="width:160px;" type="text" name="eighteen47[18_fees]" value="{{($decodedDAta['eighteen4']['18_fees'])??''}}">.<br>WORK START 
													FROM <input style="width:160px;" type="text" name="eighteen5[18_fees]" value="{{($decodedDAta['eighteen5']['18_fees'])??''}}">
													TO<input style="width:160px;" type="text" name="eighteen6[18_fees]" value="{{($decodedDAta['eighteen6']['18_fees'])??''}}">
													</td>
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
													<input type="checkbox" name="twenty[checkbox]"  <?php if(isset($decodedDAta['twenty']['checkbox']) && $decodedDAta['twenty']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													16. INSPECTION IS OVER & VISIT NOTE RECEIVED FORM INSPECTOR ? &nbsp;&nbsp;&nbsp;
													 <input type="radio" id="yes" name="twenty[radio1]" value="Yes" <?php if(isset($decodedDAta['twenty']['radio1']) && $decodedDAta['twenty']['radio1']=="Yes") { echo "checked=checked";}?>>
                                                     <label for="html">Yes</label>&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" id="no" name="twenty[radio1]" value="No" <?php if(isset($decodedDAta['twenty']['radio1']) && $decodedDAta['twenty']['radio1']=="No") { echo "checked=checked";}?>>
                                                    <label for="css">No</label>
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
													17. WEATHER DUES ARE GENERATED BY INSPECTOR ? &nbsp;&nbsp;&nbsp;
													 <input type="radio" id="yes" name="twentyone[radio1]" value="Yes" <?php if(isset($decodedDAta['twentyone']['radio1']) && $decodedDAta['twentyone']['radio1']=="Yes") { echo "checked=checked";}?>>
                                                     <label for="html">Yes</label>&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" id="no" name="twentyone[radio1]" value="No" <?php if(isset($decodedDAta['twentyone']['radio1']) && $decodedDAta['twentyone']['radio1']=="No") { echo "checked=checked";}?>>
                                                    <label for="css">No</label>
													</td>
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
													18. IF DUES ARE GENERATED THAN TAKE FOLLOWUP FOR ESI PAYMENT AND PRINT ESI PAID RECEIPT</td>
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
													19. PREPARE REPLY FOR VISIT NOTE AND MAIL TO ESTABLISHMENT
													</td>
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
													20. SUBMIT REPLY LETTER TO CONCERN ESI OFFICE AND MAIL O/C COPY TO ESATBLISHMENT</td>
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
													21. DO ENTRY IN ESTABLISHMENT HISTORY IN MENU NO. 16;18</td>
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
													22. MARK THIS TICKET AS CLOSED</td>
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