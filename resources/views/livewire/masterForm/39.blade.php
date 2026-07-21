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
													1. ESTABLISHMENT FOLDER DEVELOP IN RAJKOT FOLDER
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
													2. ALL MASTER DOC. SCAN & SAVE IN RAJKOT / ESTABLISHMENT FOLDER. IF NOT RECEIVED THAN COLLECT FROM ESTABLISHMENT
													<BR>
													     I. GST CERTIFICATE <input style="width:120px;"  type="text" name="two_one[mobile_number]" value="{{($decodedDAta['two_one']['mobile_number'])??''}}"><BR> 
													    II. CONTACT PERSON <input style="width:120px;"  type="text" name="two_two[mobile_number]" value="{{($decodedDAta['two_two']['mobile_number'])??''}}"> NAME AND NUMBER <input style="width:120px;"  type="text" name="two_three[mobile_number]" value="{{($decodedDAta['two_three']['mobile_number'])??''}}"><BR>
													   III. MAIL ID <input style="width:120px;"  type="text" name="two_four[mobile_number]" value="{{($decodedDAta['two_four']['mobile_number'])??''}}"><BR> 
													    IV. PF USER ID <input style="width:120px;"  type="text" name="two_five[mobile_number]" value="{{($decodedDAta['two_five']['mobile_number'])??''}}"> AND PASSWORD <input style="width:120px;"  type="text" name="two_six[mobile_number]" value="{{($decodedDAta['two_six']['mobile_number'])??''}}"><BR>
													     V. ESI USER ID <input style="width:120px;"  type="text" name="two_seven[mobile_number]" value="{{($decodedDAta['two_seven']['mobile_number'])??''}}"> AND PASSWORD <input style="width:120px;"  type="text" name="two_eight[mobile_number]" value="{{($decodedDAta['two_eight']['mobile_number'])??''}}"><BR>
													    VI. MASTER DOCUMENTS <input style="width:120px;"  type="text" name="two_nine[mobile_number]" value="{{($decodedDAta['two_nine']['mobile_number'])??''}}">
													
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
													3. IF REQUIRE THEN PREPARE SEPRATE FILE & FILE ALL DOCUMENTS
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
													4. PROVIDE THE RESPECTIVE FILE NO. AND UPDATE IT IN COMPANY MASTER
													
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
													5. LOGIN THE PF PORTAL WITH GIVEN ID AND PASSWORD AND TAKE FURTHER STEP
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
													6. INFORM TO AUTHORISED PERSON REGARDING CONTACT DETAIL AVAILABLE IN LOGIN>ESTABLISHMENT>CONTACT DETAILS
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
													7. AFTER COMPLETING ABOVE POINT, TAKE FURHTER CONFIRMATION ABOUT THE PASSWORD CHANGE AND UPDATE ON PORTAL AS WELL AS IN THE SOFTWARE 

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
													8. AFTER COMPLETING ABOVE POINT, TAKE FURHTER CONFIRMATION ABOUT THE PASSWORD CHANGE</td>
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
													9. UPDATE THE PF PASSWORD IN PF LOGIN PORTAL. NEW PASSWORD IS <input style="width:120px;"  type="text" name="nine_one[mobile_number]" value="{{($decodedDAta['nine_one']['mobile_number'])??''}}">
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
												<td class="p-7">
													<input type="checkbox" name="twelve[checkbox]"  <?php if(isset($decodedDAta['twelve']['checkbox']) && $decodedDAta['twelve']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													10. OPEN ESTABLISHMENT IN OUR SOFTWARE WITH HELP OF ESTABLISHMENT SEARCH LINK IS <BR>
													    I. USE LINK (https://unifiedportal-epfo.epfindia.gov.in/publicPortal/no-auth/misReport/home/loadEstSearchHome)<BR>
													   II. USE AVAILABLE ALL MASTER DOCUMENTS AND PF CODE LETTER<BR>
													  III. USE UPDATED LOGIN DETAILS 

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
													<input type="checkbox" name="thirtytwo[checkbox]"  <?php if(isset($decodedDAta['thirtytwo']['checkbox']) && $decodedDAta['thirtytwo']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													11. LOGIN THE ESI PORTAL WITH GIVEN ID AND PASSWORD </td></td>
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

											<tr>
												<td class="p-7">
													<input type="checkbox" name="thirteen[checkbox]"  <?php if(isset($decodedDAta['thirteen']['checkbox']) && $decodedDAta['thirteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													12. VERIFY THE EMPLOYER DETAILS AND CONFIRM WITH PARTY ABOUT MAIL ID AND CONTACT NO. </td>
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
													13. IF REQUIRE THEN UPDATE MAIL ID AND CONTACT NO. </td>
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
													<input type="checkbox" name="sixteen[checkbox]"  <?php if(isset($decodedDAta['sixteen']['checkbox']) && $decodedDAta['sixteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													14. UPDATE MAIL ID IN COMPANY MASTER, IF EST IS PERTAINING TO ANY BRANCH THEN ADD THAT BRANCH MAIL IN CC
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
													15. UPDATE GST CERTIFICATE NO. IN COMPANY MASTER
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
													16. SELECT MUSTER & WAGE REGISTER  AND WAGE SLIP FORMAT 
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
													17. SELECT HEAD OF MUSTER AND WAGE REIGSTER IN COMPANY MASTER
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
													18. UPDATE FEES AND BILL FIRM NAME

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
													19. UPDATE OTHER ALL DETAILS IN COMPANY MASTER AS PER THE DOCUMENTS AND DATA AVAILABLE WITH US
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
													20. ADD CONTACT NO. WITH ESTABLISHMENT NAME IN jignashanumbers2@gmail.com
</td>
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
													21. MAIL ID SAVE IN GMAIL"rajconrjt@gmail.com"" AND ASSIGN LABLE AS PER THE ALPHABATES OF THE COMPANY


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
												22. DOWNLOAD ACTIVE MEMBER SHEET AND EXITED MEMBER SHEET FROM EPFO

													</td>
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
												23. PREPARE EMPLOYEE MASTER IN OUR SOFTWARE OF ALL LIVE AND EXITED EMPLOYEES

													</td>
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
													24. UPLOAD SALARY DATA AS PER LAST MONTH'S ECR & ESI STATEMENT AND GIVE DUMMY RESIGN TO ALL REMAINING EMPLOYEES

                                                                       
													    </td>
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
													25. CREATE/UPDATE REPORT MASTER AS PER THE WORK OF ESTABLISHMENT

                                                    </td>
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
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentYEIGHT[checkbox]"  <?php if(isset($decodedDAta['twentYEIGHT']['checkbox']) && $decodedDAta['twentYEIGHT']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													26. VERIFY THE DSC ACTIVATION STATUS FROM PF LOGIN>ESTABLISHMENT>DSC/ESIGN>VIEW REGISTERED DSC<BR>
                                                I.  IF DSC IS WITH US THEN UPDATE THE INWARD ENTRY<BR>
												II. IF DSC IS NOT REGISTER THEN CREATE A TICKIT FOR DSC ACTIVATION<BR>
												III.IF DSC IS ACTIVE AND NOT WITH US THEN UPDATE DSC INWARD AND OUTWARD ENTRY IN MENU NO. 16;20;1 WITH REMARKS " ONLY FOR VALIDITY RECORD PURPOSE, DSC IS NOT WITH US"

</td>
													<td class="p-7">
														<textarea rows="1"  name="twentYEIGHT[comment]" class="w-100">{{($decodedDAta['twentYEIGHT']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['twentYEIGHT']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['twentYEIGHT']['date_time']) && $decodedDAta['twentYEIGHT']['date_time']!=""){
														$timestamp =$decodedDAta['twentYEIGHT']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twentYEIGHT']['user_name'])??''}}" name="twentYEIGHT[savedby]">
											</tr>
	                                        <tr>
												<td class="p-7">
													<input type="checkbox" name="twentynine[checkbox]"  <?php if(isset($decodedDAta['twentynine']['checkbox']) && $decodedDAta['twentynine']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													27. UPDATE WORK START FROM ENTRY IN SPREAD SHEET AVAILABLE IN GMAIL>DHIRUBHAI LABLE

</td>
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
													28.INSERT MOBILE NUMBER OF CONTACT PERSON IN COMPANY MASTER UNDER HEAD CONTACT PERSON 1 CONTACT NO. 1
</td>
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
													29.INSERT BELOW DETAILS FOR BILLING IN ADDRESS MASTER<BR>
                                                  I.  TO & CC EMAIL USED FOR BILLING PURPOSE<BR>
                                                  II. BILLING DURATION <BR>
                                                  III.BILLING DISCRIPTION<BR>
                                                  IV. RESPONSIBLE PERSON NAME & MOBILE USERD FOR BILLING PURPOSE

</td>
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
													<input type="checkbox" name="thitythree[checkbox]"  <?php if(isset($decodedDAta['thitythree']['checkbox']) && $decodedDAta['thitythree']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													30.DESIGN EXCEL SALARY STRUCUTRE AS PER THE REQUIREMENT OF THE COMPANY
</td>
													<td class="p-7">
														<textarea rows="1"  name="thitythree[comment]" class="w-100">{{($decodedDAta['thitythree']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['thitythree']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['thitythree']['date_time']) && $decodedDAta['thitythree']['date_time']!=""){
														$timestamp =$decodedDAta['thitythree']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['thitythree']['user_name'])??''}}" name="thitythree[savedby]">
											</tr>  
											
											<tr>
												<td class="p-7">
													<input type="checkbox" name="thityfour[checkbox]"  <?php if(isset($decodedDAta['thityfour']['checkbox']) && $decodedDAta['thityfour']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													31.SEND THE WORK QUOTATION
</td>
													<td class="p-7">
														<textarea rows="1"  name="thityfour[comment]" class="w-100">{{($decodedDAta['thityfour']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['thityfour']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['thityfour']['date_time']) && $decodedDAta['thityfour']['date_time']!=""){
														$timestamp =$decodedDAta['thityfour']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['thityfour']['user_name'])??''}}" name="thityfour[savedby]">
											</tr> 
											
											<tr>
												<td class="p-7">
													<input type="checkbox" name="thityfive[checkbox]"  <?php if(isset($decodedDAta['thityfive']['checkbox']) && $decodedDAta['thityfive']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													32.MARK THIS TICKIT CLOSE
</td>
													<td class="p-7">
														<textarea rows="1"  name="thityfive[comment]" class="w-100">{{($decodedDAta['thityfive']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['thityfive']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['thityfive']['date_time']) && $decodedDAta['thityfive']['date_time']!=""){
														$timestamp =$decodedDAta['thityfive']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['thityfive']['user_name'])??''}}" name="thityfive[savedby]">
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