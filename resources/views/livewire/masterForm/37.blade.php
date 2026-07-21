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
													1. MASTER DOCUMENT RECEIVED FROM ESTABLISHMENT
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
													4. IF REQUIRE THEN PREPARE SEPRATE FILE & FILE ALL DOCUMENTS

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
													5. PROVIDE THE RESPECTIVE FILE NO. AND UPDATE IT IN COMPANY MASTER
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
													6. OPEN ESTABLISHMENT IN OUR SOFTWARE WITH AVAILABLE DATA

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
													7. INSERT MOBILE NUMBER OF CONTACT PERSON IN COMPANY MASTER UNDER HEAD CONTACT PERSON 1 CONTACT NO. 1
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
												<td class="p-7"><input type="checkbox" name="eight[checkbox]"  <?php if(isset($decodedDAta['eight']['checkbox']) && $decodedDAta['eight']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													8. UPDATE GST CERTIFICATE NO. IN COMPANY MASTER
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
												<td class="p-7"><input type="checkbox" name="nine[checkbox]"  <?php if(isset($decodedDAta['nine']['checkbox']) && $decodedDAta['nine']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													9. SELECT MUSTER & WAGE REGISTER FORMAT IF IN REPORT MASTER 

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
												<td class="p-7"><input type="checkbox" name="ten[checkbox]" <?php if(isset($decodedDAta['ten']['checkbox']) && $decodedDAta['ten']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													10. SELECT HEAD OF ALLOWANCE AND DEDCUTION
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
													<input type="checkbox" name="elevan[checkbox]"  <?php if(isset($decodedDAta['elevan']['checkbox']) && $decodedDAta['elevan']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													11. UPDATE FEES I.E. ANNUAL 
                                                  <input style="width:60px;" type="text" name="sixteen1[ann_fees1]" value="{{($decodedDAta['sixteen1']['ann_fees1'])??''}}">PER MONTH
                                                  <input style="width:60px;" type="text" name="sixteen2[ann_fees2]" value="{{($decodedDAta['sixteen2']['ann_fees2'])??''}}">IN COMPANY MASTER
</td>
													<td class="p-7">
														<textarea rows="1"  name="elevan[comment]" class="w-100">{{($decodedDAta['elevan']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['elevan']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['elevan']['date_time']) && $decodedDAta['elevan']['date_time']!=""){
														$timestamp =$decodedDAta['elevan']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['elevan']['user_name'])??''}}" name="elevan[savedby]">
											</tr>
											<tr>
												<td class="p-7"><input type="checkbox" name="twelve[checkbox]" <?php if(isset($decodedDAta['twelve']['checkbox']) && $decodedDAta['twelve']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													12. IF FACTORY LICENSE PROCESS BY US THEN SAVE THE IFP ID
													<input style="width:360px;"  type="text" name="thirteen_one[mobile_number]" value="{{($decodedDAta['thirteen_one']['mobile_number'])??''}}">, <br>& PASSWORD
													<input style="width:120px;"  type="text" name="thirteen_two[mobile_number]" value="{{($decodedDAta['thirteen_two']['mobile_number'])??''}}"> </td>
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
												<td class="p-7"><input type="checkbox" name="thirteen[checkbox]"  <?php if(isset($decodedDAta['thirteen']['checkbox']) && $decodedDAta['thirteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													13.  UPDATE OTHER ALL DETAILS IN COMPANY MASTER AS PER THE DOCUMENTS AND DATA AVAILABLE WITH US

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
													14. CREATE/UPDATE REPORT MASTER
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
													15.  MAIL ID SAVE IN GMAIL rajconrjt@gmail.com AND ASSIGN LABLE AS PER THE ALPHABATES OF THE COMPANY
</td>
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
													16. ADD CONTACT NO WITH EST NAME IN jignashanumbers2@gmail.com
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
													<input type="hidden" value="{{($decodedDAta['sixteen']['user_name'])??''}}" name="sixteen[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="seventeen[checkbox]"  <?php if(isset($decodedDAta['seventeen']['checkbox']) && $decodedDAta['seventeen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													17. INSERT BILL ENTRY IN EXCELL SHEET SAVED IN THE LABLE OF DHIRUBHAI
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
													<input type="checkbox" name="eighteen[checkbox]"  <?php if(isset($decodedDAta['eighteen']['checkbox']) && $decodedDAta['eighteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													
													18. DESIGN EXCEL STRUCTURE FOR UPLOAD DATA

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
													<input type="checkbox" name="nineteen[checkbox]"  <?php if(isset($decodedDAta['nineteen']['checkbox']) && $decodedDAta['nineteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													19. INSERT BELOW DETAILS FOR BILLING IN ADDRESS MASTER<BR>
TO & CC EMAIL ID<BR> BILLING DURATION<BR> DISCRIPTION <BR>
INSERT RESPONSIBLE & WHATSPP PERSON NAME AND MOBILE NO.<BR>
>>IN CASE OF BRANCH ALSO ADD BRANCH MOBILE NO. IN RESPONSIBLE & WHATSAPP DETAILS
</td>
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
													20. SEND THE WORK QUOTATION

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
													<input type="checkbox" name="twenty1[checkbox]"  <?php if(isset($decodedDAta['twenty1']['checkbox']) && $decodedDAta['twenty1']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													21. MARK THIS TICKIT CLOSE


													</td>
													<td class="p-7">
														<textarea rows="1"  name="twenty1[comment]" class="w-100">{{($decodedDAta['twenty1']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twenty1']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twenty1']['date_time']) && $decodedDAta['twenty1']['date_time']!=""){
														$timestamp =$decodedDAta['twenty1']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twenty1']['user_name'])??''}}" name="twenty1[savedby]">
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