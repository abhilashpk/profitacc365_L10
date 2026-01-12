<?php if(Session::get('logo')=='') { ?>
	<b style="font-size:20px;">{{Session::get('company')}}</b><br/>
	<b style="font-size:15px;">Ph: {{Session::get('phone')}}, {{Session::get('address')}}</b>
<?php } else { ?>
	<img src="{{asset('assets/'.Session::get('logo').'')}}" width="20%" /><br/>
	<b style="font-size:20px;">{{Session::get('company')}}</b><br/>
	<b style="font-size:15px;">Ph: {{Session::get('phone')}}, {{Session::get('address')}}</b><br/> 
	<!--<span style="font-size:14px;">Ph: {{Session::get('phone')}}, {{Session::get('address')}}, TRN No: {{Session::get('vatno')}}</span>-->
<?php } ?>