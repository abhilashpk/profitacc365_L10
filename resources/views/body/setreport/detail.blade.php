@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Set Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-shield"></i> Administration
                    </a>
                </li>
                <li>
                    <a href="#">Set Report</a>
                </li>
                
            </ol>
			
        </section>
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Report - {{$reports[0]->report_name}}
                        </h3>
                        
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
							<div class="pull-right">
								<a href="{{ url('set_report/'.$reports[0]->id) }}" class="btn btn-warning"><i class="fa fa-fw fa-refresh"></i> Refresh</a>
								
								<button type="button" class="btn btn-primary btn-add-row" >
									<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add more
								 </button>
							 </div>
								<input type="hidden" id="rid" name="rid" value="{{$reports[0]->id}}"/>
                                <table class="table table-striped" id="formTable">
                                    <thead>
                                    <tr>
										<th>Button Name</th>
										<th>File Name</th>
										<th>Default</th>
										<th>Save</th>
										<th>Delete</th>
										<th>Design</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($reports as $row)
                                    <tr>
										<td><input type="text" class="form-control txname" name="name" value="{{$row->name}}"/>
											<input type="hidden" class="txid" name="id" value="{{$row->rid}}"/>
										</td>
										<td>
											<select class="form-control select2 selview" style="width:100%" name="file_name">
												<option value="">Select Template...</option>
												@foreach ($files as $file)
												<?php $chkd = ($row->print_name == $file)?'selected':'';	?>
												<option value="{{ $file }}" {{$chkd}}>{{ $file }}</option>
												@endforeach
											</select>
										</td>
										<td class="opt"><input type="radio" class="opdft" name="is_default" <?php if($row->is_default==1) echo 'checked';?>/> </td>
										<td><button class="btn btn-primary btn-xs save"><i class="fa fa-fw fa-floppy-o"></i></button>
										</td>
										<td class="del"><?php if($row->is_default==0) { ?> <button class="btn btn-danger btn-xs delete">
											<span class="glyphicon glyphicon-trash"></span></button><?php } ?>
										</td>
                                        @php $stimulsoft_v = config('app.stimulsoft_ver'); @endphp
                                        @if($stimulsoft_v==2)
										<td><a href="{{ URL::to('designer/'.$row->rid) }}" target="_blank" class="btn btn-warning btn-xs design">
											<span class="glyphicon glyphicon-wrench"></span></a>
										</td>
                                        @else
                                        <td><a href="{{ url('design/'.$row->rid) }}" target="_blank" class="btn btn-warning btn-xs design">
											<span class="glyphicon glyphicon-wrench"></span></a>
										</td>
                                        @endif
                                    </tr>
									@endforeach
                                   
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
            <!--main content-->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<!-- end of page level js -->
<script>

$(document).on('click', '.btn-add-row', function(e) 
{ 
   e.preventDefault();
   var table = $('#formTable');
   var clonedRow = $('tbody tr:first').clone();
   clonedRow.find('input').val('');
   clonedRow.find('.del').html('<button class="btn btn-danger btn-xs delete"><span class="glyphicon glyphicon-trash"></span></button>');
   clonedRow.find('.opt').html('<input type="radio" id="dflt_" name="is_default"/>');
   //newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
   table.append(clonedRow);
       
});

$(document).on('click', '.save', function(e)  {
    var name = $(this).closest("tr").find(".txname").val();
    var id = $(this).closest("tr").find(".txid").val();
    var file = $(this).closest("tr").find(".selview option:selected").val(); //console.log('nm '+file); 
    var opt = ($(this).closest("tr").find(".opdft").is(":checked"))?1:0; //console.log('opt '+opt); 
	var rid = $('#rid').val();
	
	$.ajax({
		url: "{{ url('set_report/update/') }}",
		type: 'get',
		data: 'file='+file+'&id='+id+'&name='+name+'&opt='+opt+'&rid='+rid,
		success: function(data) {
			alert('Print format has been updated successfully.')
			
			return true;
		}
	})
	
});

$(document).on('click', '.delete', function(e)  { 
  e.preventDefault();
  var con = confirm('Are you sure delete this print format?');
	if(con==true) {
		var id = $(this).closest("tr").find(".txid").val(); console.log('opt '+id); 
		$.ajax({
			url: "{{ url('set_report/delete/') }}/"+id,
			type: 'get',
			//data: 'id='+id,
			success: function(data) {
				alert('Print format has been deleted successfully.')
				return true;
			}
		})
		$(this).closest('tr').remove();
	}
});

$(document).on('click', '.design', function(e)  { 
  e.preventDefault();
	var id = $(this).closest("tr").find(".txid").val();
	$.ajax({
		url: "{{ url('set_report/save/') }}/"+id,
		type: 'get',
		success: function(data) {
			return true;
		}
	})
    @if($stimulsoft_v==2)
        window.open("{{ url('designer') }}", "_blank")
    @else
	    window.open("{{ url('design') }}", "_blank")
    @endif
});

</script>

@stop
