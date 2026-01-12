<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 - ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')
<style>
	table td { padding:10px; }
</style>
</head>
<body>
<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
<div>
    <aside class="right-side">
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                <div style="width:100%; !important; border:0px solid red;">
                    <div class="print">
						<center>
						<h1/>Manage Application</h1>
							<table border="0" style="width:70%;height:100%;" id="manage">
								<tr>
									<td align="center">
										<button type="button" onclick="funDisable()" 
														 class="btn btn-responsive button-alignment btn-danger"
														 data-toggle="button">
													<span style="color:#fff;" >
													Application Disable
												</span>
									</td>
								</tr>
								<tr>
									<td align="center">
										<button type="button" onclick="funEnable()" 
														 class="btn btn-responsive button-alignment btn-success"
														 data-toggle="button">
													<span style="color:#fff;" >
													Application Enable
												</span>
									</td>
								</tr>
							</table>
						</center>
                    </div>
                </div>
            </div>
        </section>
    </aside>
</div>

<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>

<script>
$(document).ready(function () {
	$('#manage').hide();
	let pswd = prompt('Please enter password'); 
	if(pswd=='profit@2020') { 
		$('#manage').show();
	} else {
		alert('Invalid password!');
		return false;
	}
});

function funDisable() { location.href="{{ url('disable') }}"; }
function funEnable() { location.href="{{ url('enable') }}"; }

</script>
</html>
