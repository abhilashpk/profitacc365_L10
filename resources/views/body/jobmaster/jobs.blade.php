<div class="panel panel-success filterable" id="newJobList">
    
	<div class="panel-heading">
			<h3 class="panel-title">
				<i class="fa fa-fw fa-columns"></i> Job List
			</h3>
	</div> 
		<div class="panel-body">
		    <input type="hidden" name="num" id="num" value="{{$num}}">
			<div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createJob">Create Job</button>
				<div class="table-responsive m-t-10">
					
					 <table class="table horizontal_table table-striped" id="tblJobs">
						 <thead>
							<tr>
								<th>Job Code</th>
								<th>Job Name</th>
								<th>Customer Name</th>
						   </tr>
						</thead>
						<input type="hidden" name="num" id="num" value="{{$num}}">
						<tbody>
						   @foreach($jobs as $job)
							 <tr>
								<td><a href="" class="jobRow" data-id="{{$job['id']}}" data-name="{{$job['name']}}" data-cod="{{$job['code']}}" data-dismiss="modal">{{$job['code']}}</a></td>
								<td><a href="" class="jobRow" data-id="{{$job['id']}}" data-name="{{$job['name']}}" data-cod="{{$job['code']}}" data-dismiss="modal">{{$job['name']}}</a></td>
								<td>{{$job['master_name']}}</td>
							</tr>
							@endforeach
						 </tbody>
					</table>
				</div>
			</div>
		</div>
</div>

<div class="panel panel-success filterable" id="allJobList">
		
	<div class="panel-body">
		<div class="table-responsive m-t-10">
			<table class="table horizontal_table table-striped" id="tblallJobs"></table>
		</div>
	</div>

</div>

	<div class="panel panel-success filterable" id="newJobFrm">
		<div class="panel-heading">
			<h3 class="panel-title">
				<i class="fa fa-fw fa-columns"></i> New Job
			</h3>
			
		</div>
   
		<div class="panel-body"><button type="button" class="btn btn-primary listJob" >List Job</button>
			<div class="col-xs-10">
				<div id="jobaddressDtls">
				   <form class="form-horizontal" role="form" method="POST" name="frmJob" id="frmJob">
					  <input type="hidden" name="_token" value="{{ csrf_token() }}">
					   <hr/>
						 <div class="form-group">
							 <label for="input-text" class="col-sm-5 control-label">Job Code</label>
								 <div class="col-sm-7">
									 <input type="text" class="form-control" id="code" name="code" placeholder="Job Code">
								 </div>
						 </div>
						 <div class="form-group">
							 <label for="input-text" class="col-sm-5 control-label">Job Name</label>
								 <div class="col-sm-7">
									 <input type="text" class="form-control" id="name" name="name" placeholder="Job Name">
								</div>
						</div>
						<div class="form-group">
                                    <label for="input-text" class="col-sm-5 control-label">Customer</label>
                                    <div class="col-sm-7">
                                        <select id="customer_id" class="form-control select2" style="width:100%" name="customer_id">
												<option value="">Select Customer...</option>
												@foreach ($customers as $con)
												<option value="{{ $con->id }}">{{ $con->master_name }}</option>
												@endforeach
										</select>
                                    </div>
                        </div>
					
						<div class="form-group">
						<!--	 <label for="input-text" class="col-sm-5 control-label">Open Cost</label>-->
								 <div class="col-sm-7">
									<input type="hidden" class="form-control" id="open_cost" name="open_cost" placeholder="Open Cost">
								 </div>
					   </div>
	
					
						<div class="form-group">
							 <label for="input-text" class="col-sm-5 control-label"></label>
								 <div class="col-sm-7">
									 <button type="button" class="btn btn-primary" id="createnew">Create</button>
								</div>
						</div>
					 </form>
				</div>
				
				<div id="sucessmsgjob"><br/>
					<div class="alert alert-success">
						<p>
							Job created successfully. Click 'Select Job'.
						</p>
					</div>
					
					<a href="" class="btn btn-primary jobRow" id="jobuse" data-dismiss="modal">
							<span class="btn-label">
						</span> Select Job
					</a>
				</div>
			</div>
		</div>
		 </div>


<script>

$(function() {

    $('#newJobFrm').toggle();
    $('#allJobList').toggle();
    $('#sucessmsgjob').toggle();
    
    $('.createJob').on('click', function() {
        if( $('#allJobList').is(":visible") )
            $('#allJobList').toggle();
        if( $('#newJobList').is(":visible") )
            $('#newJobList').toggle();
        if( $('#sucessmsgjob').is(":visible") )
            $('#sucessmsgjob').toggle();
        
        if( $('#newJobFrm').is(":hidden") )
            $('#newJobFrm').toggle();

        $('.listJob').on('click', function() {
        if( $('#allJobList').is(":visible") )
            $('#allJobList').toggle();
        if( $('#newJobFrm').is(":visible") )
            $('#newJobFrm').toggle();
        
        if( $('#newJobList').is(":hidden") )
            $('#newJobList').toggle();

    });


   

    $('#createnew').on('click', function(e) { 
        
        var cd = $('#frmJob #code').val();
        var name = $('#frmJob #name').val();
        var oc = $('#frmJob #open_cost').val();
        var cid=$('#frmJob #customer_id').val();
      
        
        if(name=="") {
            alert('Job name is required!');
            return false;
        } else {        
            $('#jobaddressDtls').toggle();
            
            $.ajax({
                url: "{{ url('jobmaster/ajax_create/') }}",
                type: 'get',
                data: 'code='+cd+'&name='+name+'&customer_id='+cid+'&open_cost='+oc,
                success: function(data) { //console.log(data);
                    if(data > 0) {
                        $('#sucessmsgjob').toggle( function() {
                            $('#jobuse').attr("data-id",data);
                            $('#jobuse').attr("data-cod",cd);
                            $('#jobuse').attr("data-name",name);
                            $('#jobuse').attr("data-open_cost",oc);
                             $('#jobuse').attr("data-customer_id",cid);
                           
                        });
                    } else if(data == 0){
                        $('#jobaddressDtls').toggle();
                        alert('job name already exist!');
                        return false;
                    } else {
                        $('#jobaddressDtls').toggle();
                        alert('Something went wrong, Job failed to add!');
                        return false;
                    }
                }
            })
        }
    });


        
    });

     



    var dtInstance = $("#tblJobs").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"bSort" : false,
				"aoColumns": [null,null,null],
				//"scrollX": true,
            });
      
});
</script>