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
													1. DOCUMENT RECEIVED FOR NEW PF NO

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
													2. ESTABLISHMENT FOLDER DEVELOP IN RAJKOT FOLDER

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
													3. ALL MASTER DOC. SCAN & SAVE IN RAJKOT / ESTABLISHMENT FOLDER. IF NOT RECEIVED THAN COLLECT FROM ESTABLISHMENT
													<BR>
													     I. GST CERTIFICATE <input style="width:120px;"  type="text" name="two_one[mobile_number]" value="{{($decodedDAta['two_one']['mobile_number'])??''}}"><BR> 
													    II. CONTACT PERSON <input style="width:120px;"  type="text" name="two_two[mobile_number]" value="{{($decodedDAta['two_two']['mobile_number'])??''}}"> NAME AND NUMBER <input style="width:120px;"  type="text" name="two_three[mobile_number]" value="{{($decodedDAta['two_three']['mobile_number'])??''}}"><BR>
													   III. MAIL ID <input style="width:120px;"  type="text" name="two_four[mobile_number]" value="{{($decodedDAta['two_four']['mobile_number'])??''}}"><BR> 
													    IV. MASTER DOCUMENTS <input style="width:120px;"  type="text" name="two_nine[mobile_number]" value="{{($decodedDAta['two_nine']['mobile_number'])??''}}">
													
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
													6. OPEN SHRAM SUVIDHA PORTAL & CREATE USER ID <input style="width:120px;"  type="text" name="six_one[mobile_number]" value="{{($decodedDAta['six_one']['mobile_number'])??''}}">PASSWORD<input style="width:120px;"  type="text" name="six_two[mobile_number]" value="{{($decodedDAta['six_two']['mobile_number'])??''}}">& APPLIED IN PF & ESI REGISTRATION

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
											<?php /* 
                                             <tr>
												<td class="p-7"><input type="checkbox" name="seven[checkbox]"  <?php if(isset($decodedDAta['seven']['checkbox']) && $decodedDAta['seven']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													7. APPLIED IN PF & ESI REGISTRATION

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
                                            */ ?>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="eight[checkbox]"  <?php if(isset($decodedDAta['eight']['checkbox']) && $decodedDAta['eight']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													7. DOWNLOAD PF CODE LETTER, SAVE TO RAJKOT FOLDER & MAIL TO ESTABLISHMENT.
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
												<td class="p-7"><input type="checkbox" name="ten[checkbox]"  <?php if(isset($decodedDAta['ten']['checkbox']) && $decodedDAta['ten']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													8. GENERATE ALL TIC WITH IN 10 DAYS FROM DATE OF COVERAGE AND DOWNLOAD ALL TIC AND SAVE IN TO RAJKOT FOLDER

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
													9. OPEN ESTABLISHMENT IN OUR SOFTWARE FROM LOGIN DPA WITH ALL AVAILABLE DATA

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
												<td class="p-7"><input type="checkbox" name="nine[checkbox]" <?php if(isset($decodedDAta['nine']['checkbox']) && $decodedDAta['nine']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													10. UPDATE MAIL ID IN COMPANY MASTER, IF EST IS PERTAINING TO ANY BRANCH THEN ADD MAIL ID OF BRANCH IN CC


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
													11. UPDATE GST CERTIFICATE NO. IN COMPANY MASTER

 

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
													12. SELECT MUSTER & WAGE REGISTER  AND WAGE SLIP FORMAT 

</td></td>
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
													13. SELECT HEAD OF ALLOWANCE AND DEDCUTION
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
													14. SAVE IN COMPANY MASTER SHRAMSUVIDHA ID<input style="width:120px;"  type="text" name="two_one1[mobile_number1]" value="{{($decodedDAta['two_one1']['mobile_number1'])??''}}">& PW<input style="width:120px;"  type="text" name="two_one2[mobile_number2]" value="{{($decodedDAta['two_one2']['mobile_number2'])??''}}">AND PF ID<input style="width:120px;"  type="text" name="two_one3[mobile_number3]" value="{{($decodedDAta['two_one3']['mobile_number3'])??''}}">& PW<input style="width:120px;"  type="text" name="two_one4[mobile_number4]" value="{{($decodedDAta['two_one4']['mobile_number4'])??''}}">AND ESI ID<input style="width:120px;"  type="text" name="two_one5[mobile_number5]" value="{{($decodedDAta['two_one5']['mobile_number5'])??''}}">& PW<input style="width:120px;"  type="text" name="two_one6[mobile_number6]" value="{{($decodedDAta['two_one6']['mobile_number6'])??''}}">

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
													15. UPDATE OTHER ALL DETAILS IN COMPANY MASTER AS PER THE DOCUMENTS AND DATA AVAILABLE WITH US


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
													16. UPDATE FEES AND BILL FIRM NAME

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
													17. ADD CONTACT NO WITH EST NAME IN jignashanumbers2@gmail.com

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
													18. MAIL ID SAVE IN GMAIL rajconrjt@gmail.com AND ASSIGN LABLE AS PER THE ALPHABATES OF THE COMPANY

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
													19. IF DSC IS WITH US THEN UPDATE THE INWARD ENTRY IN SOFTWARE AND CREATE A TICKIT FOR DSC ACTIVATION

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
													20. UPDATE REGISTRATION ENTRY IN SPREAD SHEET AVAILABLE IN GMAIL>DHIRUBHAI LABLE


 
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
													21. CREATE/UPDATE REPORT MASTER
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
													22. INSERT MOBILE NUMBER OF CONTACT PERSON IN COMPANY MASTER UNDER HEAD CONTACT PERSON 1 CONTACT NO. 1

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
													23. INSERT BELOW DETAILS FOR BILLING IN ADDRESS MASTER<br>
	                                              I.   TO & CC EMAIL ID, BILLING DURATION, DISCRIPTION<BR>
                                                  II.  INSERT RESPONSIBLE & WHATSPP PERSON NAME AND MOBILE NO.<BR>
												  III. IN CASE OF BRANCH ALSO ADD BRANCH MOBILE NO. IN RESPONSIBLE & WHATSAPP DETAILS




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
												24. DESIGN EXCEL SALARY STRUCUTRE AS PER THE REQUIREMENT OF THE COMPANY


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
												25. SEND THE WORK QUOTATION


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
													26. MARK THIS TICKIT CLOSE


                                                                       
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
                                                  <?php /* 
											<tr>
												<td class="p-7">
													<input type="checkbox" name="twentySEVEN[checkbox]"  <?php if(isset($decodedDAta['twentySEVEN']['checkbox']) && $decodedDAta['twentySEVEN']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													28. SELECT MUSTER & WAGE REGISTER FORMAT IF IN REPORT MASTER BOTH REPORT SELECTED UNDER THE HEAD "PAY ROLL REGULAR WORK"

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
													29. SELECT HEAD OF MUSTER AND WAGE REIGSTER IN COMPANY MASTER
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
													30. SELECT FORMATE OF WAGE SLIP 

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
													31.DESIGN EXCEL SALARY STRUCUTRE AS PER THE REQUIREMENT OF THE COMPANY
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
													32.UPDATE REGISTRATION ENTRY IN SPREAD SHEET AVAILABLE IN GMAIL>DHIRUBHAI LABLE
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
													33.SEND THE WORK QUOTATION 
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
													34.CREATE REPORT MASTER

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
													35.IF DSC IS ACTIVE AND NOT WITH US THEN UPDATE DSC INWARD AND OUTWARD ENTRY IN MENU NO. 16;20;1 WITH REMARKS " ONLY FOR VALIDITY RECORD PURPOSE, DSC IS NOT WITH US"</td>
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
											
											<tr>
												<td class="p-7">
													<input type="checkbox" name="thitysix[checkbox]"  <?php if(isset($decodedDAta['thitysix']['checkbox']) && $decodedDAta['thitysix']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													36.MARK THIS TICKIT CLOSE

</td>
													<td class="p-7">
														<textarea rows="1"  name="thitysix[comment]" class="w-100">{{($decodedDAta['thitysix']['comment'])??''}}</textarea>
													</td>
													<td class="p-7">
														{{($decodedDAta['thitysix']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['thitysix']['date_time']) && $decodedDAta['thitysix']['date_time']!=""){
														$timestamp =$decodedDAta['thitysix']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['thitysix']['user_name'])??''}}" name="thitysix[savedby]">
											</tr> 
											*/ ?>
											
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