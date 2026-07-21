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
													1. AS PER THE CHECK LIST COLLET ALL DOCUMENTS FOR PT REGISTRATION
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
													2. AS PER CHECK LIST MISSING DOCUMENTS <br>1.
													<input style="width:250px;"  type="text" name="two_one[2_one]" value="{{($decodedDAta['two_one']['2_one'])??''}}">, 2. 
													<input  style="width:250px;"  type="text" name="two_two[2_two]" value="{{($decodedDAta['two_two']['2_two'])??''}}">, 3.
													<input  style="width:250px;"  type="text" name="two_three[2_three]" value="{{($decodedDAta['two_three']['2_three'])??''}}"><br> 4.
													<input  style="width:250px;"  type="text" name="two_four[2_four]" value="{{($decodedDAta['two_four']['2_four'])??''}}">, 5.
													<input  style="width:250px;"  type="text" name="two_five[2_five]" value="{{($decodedDAta['two_five']['2_five'])??''}}">, 6.
													<input  style="width:250px;"  type="text" name="two_six[2_six]" value="{{($decodedDAta['two_six']['2_six'])??''}}">
													
													
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
													3. PT REGISTRATION OF EMPLOYEE (PRC) W.E.F
													<input type="text"  name="three[3_from]" class="onetime"  value="{{($decodedDAta['three']['3_from'])??''}}"> <br>& ESTABLISHMENT (PEC) W.E.F
													<input type="text"  name="three[3_1_from]" class="onetime"  value="{{($decodedDAta['three']['3_1_from'])??''}}">
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
													4. FILL FORM FOR PT REGISTRATION – SAVE IN Z ACT> PT> GOVT. FORMATE >LATEST PT REGISTRATION FORM
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
													5. PREPARE THE CLARIFICATION LETTER IF THE ESTABLISHMENT REGISTRATION DATE AND EMPLOYEE REGISTRATION DATE FOR PROFESSIONAL TAX IS DIFFER</td>
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
													6. PRINT FORM – 5 AND PTS IN TWO COPIES FROM 
													<input type="text"  name="six[6_1_from]" class="onetime"  value="{{($decodedDAta['six']['6_1_from'])??''}}"> TO 
													<input type="text"  name="six[6_2_from]" class="onetime"  value="{{($decodedDAta['six']['6_2_from'])??''}}">. ONE IS FOR SUBMISSION AT WARD OFFICE & OTHER IS FOR ESTABLISHMENT.
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
													7. UPDATE PT REGISTRAION FEES ENTRY IN EXCEL SHEET SAVED IN THE LABLE OF DHIRUBHAI
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
													8. UPDATE PEC & PRC NO. IN MENU 13.1.1 IN SOFTWARE OF CONCERNED EMPLOYEE</td>
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
													9. SAVE PAID RECEIPTS AND PEC & PRC CERTIFICATE IN RAJKOT FOLDER
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
													10. IF PT WORK IS DONE IN DUMMY SOFTWARE THEN EXPORT THE DATA AND SAVE IN RAJKOT FOLDER</td>
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
													11. MAIL THE FOLLOWING DRAFTING TO ESTABLISHMENT <BR>
													    <B> SUBJECT: IMPORTANT : PT PAYMENT LINK & REGISTRATION NUMBER </B><BR>
                                                            Respected sir,<BR>
                                                            Please pay EMPLOYEE PT for every month before 15th date, follow the following link for payment using PRC No. ________ <BR>
                                                            https://www.rmc.gov.in/ProfessionTaxRCPayment <BR>
                                                            Please pay ESTABLISHMENT PT for every year after completion of current financial year with using PEC No.________, follow the following link for payment <BR>
                                                            https://www.rmcegov.gov.in/payonline/proftax.php  <BR>
                                                            SAVE THIS MAIL IN YOUR RECORD FOR FUTURE REFERENCE. <BR>
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
													12. CHANGE PT STATUS OF ALL EMPLOYEES FROM "NO" TO "YES" FROM MENU NO. 2;1;21;1 SCREEN 4</td>
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
													13. ADD PTS AND PT FORM-5 IN  REPORT MASTER FROM MENU NO. 16;5  </td>
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
													14. UPDATE CALANDER ENTRIY FROM MENU NO. 15.</td>
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
													15. MARK THIS TICKET AS CLOSE</td>
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