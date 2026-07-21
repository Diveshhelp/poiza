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
						<form action="{{ route('ticket.saveform') }}" id="createProject" method="POST"
							enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="id" value="{{$TicketsData['id']}}">
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
													1. DOCUMENT RECEIVED AS PER CHECKLIST
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
													2. AS PER CHECK LIST MISSING DOCUMENT</td>
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
													3. LICENSE EXPIRY DATE 
													<input type="text"  name="three[3_from]" class="onetime"  value="{{($decodedDAta['three']['3_from'])??''}}">  
												</td>
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
													4. LAST APPLIED DATA : 
													TOTAL EMPLOYEE<input style="width:70px;" type="text" name="four[4_emp]" value="{{($decodedDAta['four']['4_emp'])??''}}">,
													REG. FEES PAID RS. <input style="width:70px;" type="text" name="four[4_reg]" value="{{($decodedDAta['four']['4_reg'])??''}}">,
													SECURITY DEPOSITE (EE*90) PAID RS. <input style="width:70px;" type="text" name="four[4_sec]" value="{{($decodedDAta['four']['4_sec'])??''}}">
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
												<td class="p-7">
													<input type="checkbox" name="five[checkbox]"  <?php if(isset($decodedDAta['five']['checkbox']) && $decodedDAta['five']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													5. AMENDMENT DATA : OLD EE.<input style="width:90px;" type="text" name="five[5_old_ee]" value="{{($decodedDAta['five']['5_old_ee'])??''}}">, 
													ADDITIONAL EE.<input style="width:90px;" type="text" name="five[5_add_ee]" value="{{($decodedDAta['five']['5_add_ee'])??''}}">,
 													TOTAL EE.<input style="width:90px;"  type="text" name="five[5_tot_ee]" value="{{($decodedDAta['five']['5_tot_ee'])??''}}">,
													NEW REG. DD RS.<input style="width:90px;"  type="text" name="five[5_reg_dd]" value="{{($decodedDAta['five']['5_reg_dd'])??''}}">, 
													NEW SEC. DEPOSITE (EE*90) DD RS.<input style="width:90px;"  type="text" name="five[5_sec_dep]" value="{{($decodedDAta['five']['5_sec_dep'])??''}}">,
													DD EXPENSE RS.<input style="width:90px;"  type="text" name="five[5_dd_exp]" value="{{($decodedDAta['five']['5_dd_exp'])??''}}">,
													CONSULTING FEES RS.<input style="width:230px;"  type="text" name="five[5_con_fee]" value="{{($decodedDAta['five']['5_con_fee'])??''}}">, 
													OTHER EXPENSE RS.<input style="width:190px;"  type="text" name="five[5_other_exp]" value="{{($decodedDAta['five']['5_other_exp'])??''}}">, 
													TOTAL RS.<input style="width:130px;"  type="text" name="five[5_total_rs]" value="{{($decodedDAta['five']['5_total_rs'])??''}}"> 
												</td>
												<td class="p-7"><textarea rows="1"  name="five[comment]" class="w-100">{{($decodedDAta['five']['comment'])??''}}</textarea>
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
													6. ABOVE FEES RECEIVED ? <Y/N><input type="text" name="six[6_y_n]" value="{{($decodedDAta['six']['6_y_n'])??''}}"></td>
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
													7. GENERATE DD SLIP IN FAVOUR OF "ASSISTANT LABOUR COMMISSIONER (C)" PAYABLE AT :
													<input type="text" name="six[7_dd_slip]" value="{{($decodedDAta['six']['7_dd_slip'])??''}}">
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
													8. GET DD AS PER APPLICATION FROM UNION BANK OF INDIA ONLY</td>
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
													9. PREPARE ENDORSEMENT LETTER FROM OUR SOFTWARE AT MENU NO. 11;3;1; & PRINT IN TWO COPY ON LETTER HEAD OF ESTABLISHMENT</td>
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
													<input type="checkbox" name="ten[checkbox]" <?php if(isset($decodedDAta['ten']['checkbox']) && $decodedDAta['ten']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													10. PREPARE TR-6 CHALLAN OF SECURITY DEPOSIT AND DIFFERENCE OF LICENSE FEES AS PER THE AMENDMENT OF LICENSE FROM MENU NO.11;3;2 & 3 AND PRINT BOTH TR-6 IN FOUR COPY</td>
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
												<td class="p-7"><input type="checkbox" name="eleven[checkbox]" <?php if(isset($decodedDAta['eleven']['checkbox']) && $decodedDAta['eleven']['checkbox']=="on") { echo "checked=checked"; echo " disabled"; }?>>
													11. CALL TO AUTHORISED PERSON FOR SIGNING THE DOCUMENTS AND PREPARE SET OF DOCUMENTS AS PER THE CHECK LIST.
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
												<td class="p-7"><input type="checkbox" name="twlve[checkbox]" <?php if(isset($decodedDAta['twlve']['checkbox']) && $decodedDAta['twlve']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													12. APPLICATION INWARD TO LABOUR COMM (C) OFFICE ON DT
													<input type="text"  name="twelve[12_from]" class="onetime"  value="{{($decodedDAta['twelve']['12_from'])??''}}"> 
													</td>
													<td class="p-7">
														<textarea rows="1"  name="twlve[comment]" class="w-100">{{($decodedDAta['twlve']['comment'])??''}}</textarea>
														</td>
														<td class="p-7">
														{{($decodedDAta['twlve']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['tharteen']['date_time']) && $decodedDAta['tharteen']['date_time']!=""){
														$timestamp =$decodedDAta['tharteen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['twlve']['user_name'])??''}}" name="twlve[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="tharteen[checkbox]" <?php if(isset($decodedDAta['tharteen']['checkbox']) && $decodedDAta['tharteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													13. O/C COPY MAIL TO ESTABLISHMENT</td>
													<td class="p-7"><textarea rows="1"  name="tharteen[comment]" class="w-100">{{($decodedDAta['tharteen']['comment'])??''}}</textarea>
												</td>
												<td class="p-7">
														{{($decodedDAta['tharteen']['user_name'])??''}}
													</td>
													<td class="p-7">
														<?php 
													if(isset($decodedDAta['tharteen']['date_time']) && $decodedDAta['tharteen']['date_time']!=""){
														$timestamp =$decodedDAta['tharteen']['date_time'];
														$date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, "UTC");
														$date->setTimezone($timezone);
														$NewDate= $date->format("Y-m-d H:i:s");
														echo date('d-m-Y h:i A',strtotime($NewDate));
														}
													?>
													</td>
													<input type="hidden" value="{{($decodedDAta['tharteen']['user_name'])??''}}" name="tharteen[savedby]">
											</tr>
											<tr>
												<td class="p-7">
													<input type="checkbox" name="fourteen[checkbox]" <?php if(isset($decodedDAta['fourteen']['checkbox']) && $decodedDAta['fourteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													14.  INSERT BILL ENTRY IN EXCELL SHEET SAVED IN THE LABLE OF DHIRUBHAI OF RS. 
													<input type="text" name="fourteen[14_llc_fees]" value="{{($decodedDAta['fourteen']['14_llc_fees'])??''}}"></td>
													<td class="p-7"><textarea rows="1"  name="fourteen[comment]" class="w-100">{{($decodedDAta['fourteen']['comment'])??''}}</textarea>
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
													<input type="checkbox" name="fifteen[checkbox]" <?php if(isset($decodedDAta['fifteen']['checkbox']) && $decodedDAta['fifteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													15. SEND APPLICATION NO., OWNER NAME, COMPANY NAME AND NO. OF EMPLOYEES TO SIR</td>
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
													<input type="checkbox" name="sixteen[checkbox]" <?php if(isset($decodedDAta['sixteen']['checkbox']) && $decodedDAta['sixteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													16. GET AMENDED LICENSE COPY FROM LABOUR OFFICE AND SCAN, SAVE & MAIL AMENDED LICENSE COPY IN FORM-VI TO ESTABLISHMENT</td>
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
														<tr>
												<td class="p-7">
													<input type="checkbox" name="seventeen[checkbox]" <?php if(isset($decodedDAta['seventeen']['checkbox']) && $decodedDAta['seventeen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													17. IF COMPANY IS NOT EXISTING IN OUR SOFTWARE THEN FOR BILL PURPOSE ADD IT IN ADDRESS MASTER</td>
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
													<input type="checkbox" name="eighteen[checkbox]" <?php if(isset($decodedDAta['eighteen']['checkbox']) && $decodedDAta['eighteen']['checkbox']=="on") { echo "checked=checked"; echo " disabled";}?>>
													18. MARK THIS TICKET AS CLOSE</td>
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
		// $('.repeate').datepicker({
		// 	dateFormat: 'dd/mm/yy'
    	// });
		$("#createProject").validate({
			rules: {
				task_title: {
					required: true,
					minlength: 2
				}
			},
			messages: {
				task_title: {
					required: "Please enter a task title",
					minlength: "Task title must consist of at least 2 characters"
				}
			}
		});

		$('#select_all').click(function() {
			$('#assign_to option').prop('selected', true);
			$("#select_all").focus();
			$("#remove_all").show();
			$("#select_all").hide();
		});
		$('#remove_all').click(function() {
			$('#assign_to option').prop('selected', false);
			$("#select_all").focus();
			$("#remove_all").hide();
			$("#select_all").show();
		});
	});
	$("#repeateId").hide();
	$("#remove_all").hide();
	function viewChange(){
		var task_type=$("#task_type").val();
		if(task_type=="o"){
			$("#repeateId").hide();
			
			$("#singleId").show();
		}else{
			$("#repeateId").show();
			$("#singleId").hide();
		}
	}
</script>
@stop