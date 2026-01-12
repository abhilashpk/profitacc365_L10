@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-table/css/bootstrap-table.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap_tables.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<style>
	 input.b {
             visibility: hidden;
         }
		#datepickers-container { z-index: 999999 !important; }
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
       <section class="content-header">
            <!--section starts-->
            <h1>
                Customer Desk
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i>Sales CRM
                    </a>
                </li>
                <li>
                    <a href="#">Customer Desk</a>
                </li>
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Customer Follow Up on {{date('d-m-Y',strtotime($date))}}
                        </h3>
                       
                    </div>
                    <div class="panel-body">
                        <table id="table4" data-toolbar="#toolbar" data-search="true" data-show-refresh="false"
                                   data-show-toggle="true" data-show-columns="true" data-show-export="true"
                                   data-detail-view="true" data-detail-formatter="detailFormatter"
                                   data-minimum-count-columns="2" data-show-pagination-switch="true"
                                   data-pagination="true" data-id-field="id" data-page-list="[10, 20,40,ALL]"
                                   data-show-footer="false" data-height="503">
                                <thead>
                                <tr>
                                    <th data-field="Company Name" data-sortable="true">Company Name</th>
                                    <th data-field="Follow" data-sortable="false">Follow</th>
                                    <th data-field="Phone" data-sortable="true">Phone</th>
									<th data-field="E-mail" data-sortable="true">E-mail</th>
									<th data-field="Product" data-sortable="true">Product</th>
									 <!--<th data-field="Stcatus" data-sortable="false">Status</th>-->
                                    <th data-field="Remarks" data-sortable="true" style="width:65%;">Remarks</th>
                                    <th data-field="Status" data-sortable="false">Status</th>
                                    <th data-field="Action" data-sortable="false">Action</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    <input type="text" class="b" id="date_h"   value="{{date('d-m-Y',strtotime($date))}}"/>
								
							<?php $i = 0;?>
								@foreach($docrow as $k => $row)
							<?php $i++;?>
                                <tr>
                                    <td class="clk" data-name="{{$row->customer_id}}">{{$row->master_name}} <input type="hidden" value="{{$row->customer_id}}" id="rid"></td>
                                    <td  data-name="{{$row->customer_id}}"><p><a href='' data-toggle="modal" data-target="#followup_modal" class='btn btn-primary btn-xs popUp' data-id="{{$row->id}}" data-prnid="{{$row->parent_id}}" data-cstid="{{$row->customer_id}}" data-pid="{{$row->product_id}}"><i class='fa fa-fw fa-retweet'></i> Follow Up</a> 
<!--										<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('customerleads/getfollowup/'.$row->id) }}'">
										<span class="glyphicon glyphicon-eye-open"></span></button>-->
										 </p></td>
                                    <td class="clk" data-name="{{$row->customer_id}}">{{$row->vat_assign.'-'.$row->phone}}<br/>{{$row->vat_percentage.'-'.$row->fax}}</td>
									<td>{{$row->email}}<br/>{{$row->reference}}</td>
									<td class="clk" data-name="{{$row->customer_id}}">{{$products[$row->id]}}</td>
									<!--	<td class="clk" data-name="{{$row->customer_id}}">{{$row->status}}</td>-->
                                    <td class="clk" data-name="{{$row->customer_id}}"><b>{{$row->remark}} </b><br/> <i>Date:{{date('d-m-Y', strtotime($row->remark_date))}}</i></td>
						
						
						<?php
									if($row->status==1)
										$status = '<p class="btn btn-info btn-xs">Customer</p>';
									elseif($row->status==2)
										$status = '<p class="btn btn-primary btn-xs">Enquiry</p>';
									elseif($row->status==3)
										$status = '<p class="btn btn-warning btn-xs">Prospective</p>';
									elseif($row->status==4)
										$status = '<p class="btn btn-danger btn-xs">Archive</p>'; 
									?>
									<td>{!!$status!!}</td>
							<!--	<td>	
									<select class='form-control statusUpd' data-name="{{$row->customer_id}}" id='sts_{{$row->id}}'>
									
											<option   value="1" <?php // if($row->status==1) echo 'selected';?>>Customer</option>
											<option   value="2"  <?php //if($row->status==2) echo 'selected';?>>Enquiry</option>
											<option  value="3"  <?php // if($row->status==3) echo 'selected';?>>Prospective</option>
											
											<option   value="4"  <?php // if($row->status==4) echo 'selected';?>>Archive</option>
										</select>
								</td>-->
                                    <td  data-name="{{$row->customer_id}}"><p>
										<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('customerleads/getfollowup/'.$row->id) }}'">
										<span class="glyphicon glyphicon-eye-open"></span></button>
										 </p></td>
                                </tr>
                                @endforeach
                                </tbody>
                         </table>
                    </div>
                </div>
            </div>
        </div>
        
        </section>
		
		<div id="followup_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Follow Up Remark</h4>
					</div>
					<div class="modal-body" id="followupData">
						<div id="itemDtls">
						<form class="form-horizontal" role="form" method="POST" name="frmItem" id="frmItem">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="cstid" id="cstid">
							<input type="hidden" name="pid" id="pid">
							<input type="hidden" name="prntid" id="prntid">
							<input type="hidden" name="id" id="id">
							<input type="text" class="b" id="date_h"   value="{{date('d-m-Y',strtotime($date))}}"/>
								<div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Remarks</label>
									<div class="col-sm-7">
										<textarea id="remark" style="resize:none" class="form-control" name="remark" rows="4" cols="50"></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Next Followup</label>
									<div class="col-sm-7">
										<input type="text" class="form-control pull-right" autocomplete="off" id="next_date" name="next_date" data-language='en'/>
									</div>
								</div>
								
								 <!-- 	<div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Status</label>
									<div class="col-sm-7">
										<select id="status" class="form-control select2" style="width:100%" name="status">
											<option value="1">Customer</option>
											<option value="2">Enquiry</option>
											<option value="3">Prospective</option>
											<option value="4">Archive</option>
										</select>
									</div>
								</div>-->
								
								<div class="form-group">
									<label for="input-text" class="col-sm-5 control-label"></label>
									<div class="col-sm-7">
										<button type="button" class="btn btn-primary" id="saveIt">Save</button>
									</div>
								</div>
							 </form>	
							</div>
							<div id="sucessmsgItm"><br/>
								<div class="alert alert-success">
									<p>
										Remarks saved successfully..
									</p>
								</div>
							</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div> 
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/editable-table/js/mindmup-editabletable.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-table/js/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/tableExport.jquery.plugin/tableExport.min.js')}}"></script>
<script src="{{asset('assets/js/custom_js/bootstrap_tables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<!-- end of page level js -->
<script>
$(function() {
	
	$('#saveIt').on('click', function(e){
		
		var rm = $('#frmItem #remark').val();
		var dt = $('#frmItem #next_date').val();
		var st = $('#frmItem #status option:selected').val();
		var cid = $('#frmItem #cstid').val();
		var pid = $('#frmItem #pid').val();
		var pnid = $('#frmItem #prntid').val();
		var id = $('#frmItem #pid').val();
		var rowid = $('#frmItem #id').val();
		console.log(rowid);
		var date = $('#date_h').val(); 
		console.log(date);
		if(rm=="") {
			alert('Remark is required!');
			return false;
		} else if(dt=="") {
			alert('Next date is required!');
			return false;
			
		} else {				
			$.ajax({
				url: "{{ url('customerleads/ajax_save/') }}",
				type: 'get',
				data: 'id='+id+'&customer_id='+cid+'&remark='+rm+'&next_date='+dt+'&status='+st+'&product_id='+pid+'&parent_id='+pnid+'&date='+date+'&rowid='+rowid,
					success: function(data) { 
					    console.log(data);
						$('#sucessmsgItm').show();
						$('#itemDtls').hide();
						var url = "{{ url('customerleads/followups/') }}";
		                 location.href = url+'/'+date;
				}
			})
		}
	});
	
	$('#table4 tbody').on('click', '.clk', function () {
        var rid = $(this).data('name');//$('#rid').val(); console.log(rid);
        var date_hidden = $('#date_h').val(); 
		console.log(date_hidden);
        var url = "{{ url('customerleads/editdatefollow/') }}";
		location.href = url+'/'+rid+'/'+date_hidden;
        //var url = "{{ url('customerleads/edit/') }}";
		//location.href = url+'/'+rid;
    } );
});

$(document).on('change', '.statusUpd', function(e) {  

var res = this.id.split('_');
var id = res[1];
console.log('id '+id);
var cust =  $(this).attr('data-name');
console.log('cust '+cust);
var sts = $('#sts_'+id).val();
console.log('sts '+sts);
var date = $('#date_h').val(); 
console.log(date);
$.ajax({
	url: "{{ url('customerleads/set_status/') }}",
	type: 'get',
	data: 'id='+id+'&cust='+cust+'&sts='+sts+'&date='+date,
	success: function(data) {
		
		var url = "{{ url('customerleads/followups/') }}";
		location.href = url+'/'+date;
		alert('Status updated successfully.')
		
		return true;
	}
})

});
$(document).on('click', '.popUp', function(e)  { 
	
	$('#itemDtls').show();
	
	$('#sucessmsgItm').hide();
	
	$('#cstid').val($(this).data('cstid'));
	$('#pid').val($(this).data('pid'));
	$('#id').val($(this).data('id'));
	var pd = $(this).data('prnid');
	pd = (pd==0)?$(this).data('id'):$(this).data('prnid') ;
	$('#prntid').val(pd);
	$('#remark').val('');
	$('#next_date').val('');
	$('#status').val('1');
})

$('#next_date').datepicker( { 
	dateFormat: 'dd-mm-yyyy',
	autoClose: true
});
	
		
function funDelete(id) {
	var con = confirm('Are you sure delete this customer?');
	if(con==true) {
		var url = "{{ url('customerleads/delete/') }}";
		location.href = url+'/'+id;
	}
}
</script>

@stop
