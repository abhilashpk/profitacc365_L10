<div class="panel panel-success filterable" id="newCustomerList">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-fw fa-columns"></i> Customer List
        </h3>
    </div>
    
    <div class="panel-body">
        <div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createCust">Create Customer</button>
         <button type="button" class="btn btn-primary viewall">View All</button>
            <table class="table horizontal_table table-striped" id="tableCustList">
                <thead>
                <tr>
                    <th>Account ID4</th>
                    <th>Account Name</th>
                    <th>Balance</th>
                    <th>Open Balance</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td><a href="" class="custRow" data-id="{{$customer->id}}" data-name="{{$customer->master_name}}" data-clbalance="{{number_format($customer->cl_balance,2)}}" data-pdc="{{number_format($customer->pdc_amount,2)}}" data-crlimit="{{number_format($customer->credit_limit,2)}}" data-groupid="{{$customer->account_group_id}}" data-trnno="{{$customer->vat_no}}" data-term="{{$customer->terms_id}}" data-dismiss="modal">{{$customer->account_id}}</a></td>
                    <td><a href="" class="custRow" data-id="{{$customer->id}}" data-name="{{$customer->master_name}}" data-clbalance="{{number_format($customer->cl_balance,2)}}" data-pdc="{{number_format($customer->pdc_amount,2)}}" data-crlimit="{{number_format($customer->credit_limit,2)}}" data-groupid="{{$customer->account_group_id}}" data-trnno="{{$customer->vat_no}}" data-term="{{$customer->terms_id}}" data-dismiss="modal">{{$customer->master_name}}</a></td>
                    <td>{{ number_format($customer->cl_balance, 2, '.', ',') }}</td>
                    <td>{{ number_format($customer->op_balance, 2, '.', ',') }}</td>
                </tr>
               @endforeach
                </tbody>
            </table>

        </div>
        
    </div>
</div>

<div class="panel panel-success filterable" id="allCustomerList">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-fw fa-columns"></i> All Account List
        </h3>
    </div>
    
    <div class="panel-body">
        <div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createCust">Create Customer</button>
         <button type="button" class="btn btn-primary listCust">List Customer</button>
         
            <table class="table horizontal_table table-striped" id="tableallCustList">
            </table>

        </div>
        
    </div>
</div>

<div class="panel panel-success filterable" id="newCustomerFrm">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-fw fa-columns"></i> New Customer
        </h3>
        
    </div>
    <div class="panel-body">  <button type="button" class="btn btn-primary listCust">List Customer</button>
    <button type="button" class="btn btn-primary viewall">View All</button>
    
        <div class="col-xs-10">
            <div id="addressDtls">
            <form class="form-horizontal" role="form" method="POST" name="frmCustomer" id="frmCustomer">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" class="form-control" id="account_id" name="account_id">
            <input type="hidden" name="category" value="{{$category}}">
            <input type="hidden" name="department_id" id="department_id" value="{{$deptid}}">
                <hr/>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">Customer Name</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">Address</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address1, Address2">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">Contry</label>
                    <div class="col-sm-7">
                        <select id="country_id" class="form-control select2" style="width:100%" name="country_id">
                            <option value="">Select Country...</option>
                            @foreach ($country as $con)
                            <option value="{{ $con['id'] }}">{{ $con['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">Area</label>
                    <div class="col-sm-7">
                        <select id="area_id" class="form-control select2" style="width:100%" name="area_id">
                            <option value="">Select Area...</option>
                            @foreach ($area as $ar)
                            <option value="{{ $ar['id'] }}">{{ $ar['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">Phone</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">Email</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">Contact Person</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Contact Person">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">TRN No</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No.">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label">Department</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="custDpt" readonly placeholder="Department">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="input-text" class="col-sm-5 control-label"></label>
                    <div class="col-sm-7">
                        <button type="button" class="btn btn-primary" id="create">Create</button>
                    </div>
                </div>
             </form>
            </div>
            
            <div id="sucessmsg"><br/>
                <div class="alert alert-success">
                    <p>
                        Customer created successfully. Click 'Select Customer'.
                    </p>
                </div>
                
                <a href="" class="btn btn-primary custRow" id="cususe" data-dismiss="modal">
                        <span class="btn-label">
                    </span> Select Customer
                </a>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {

var dtInstance = $("#tableCustList").DataTable({
"processing": true,
"serverSide": true,
"ajax":{
    "url": "{{ url('packing_list/customer_list/') }}",
    "dataType": "json",
    "type": "POST",
    "data":{ _token: "{{csrf_token()}}", type: "CUSTOMER" }
},
"columns": [
{ "data": "account_id" },
{ "data": "master_name" },
{ "data": "cl_balance" },
{ "data": "op_balance" }
]	

});
});

$(function() {
$('#newCustomerFrm').toggle();
$('#allCustomerList').toggle();
$('#sucessmsg').toggle();

$('.createCust').on('click', function() {
if( $('#allCustomerList').is(":visible") )
$('#allCustomerList').toggle();
if( $('#newCustomerList').is(":visible") )
$('#newCustomerList').toggle();
if( $('#sucessmsg').is(":visible") )
$('#sucessmsg').toggle();

if( $('#newCustomerFrm').is(":hidden") )
$('#newCustomerFrm').toggle();

$('#custDpt').val(dptTxt);

});

$('.listCust').on('click', function() {
if( $('#allCustomerList').is(":visible") )
$('#allCustomerList').toggle();
if( $('#newCustomerFrm').is(":visible") )
$('#newCustomerFrm').toggle();

if( $('#newCustomerList').is(":hidden") )
$('#newCustomerList').toggle();

});

$('.viewall').on('click', function() {

if( $('#newCustomerList').is(":visible") )
$('#newCustomerList').toggle();
if( $('#newCustomerFrm').is(":visible") )
$('#newCustomerFrm').toggle();

if( $('#allCustomerList').is(":hidden") )
$('#allCustomerList').toggle();

var acurl = "{{ url('account_master/get_account_all/') }}";
var res = this.id.split('_');
var curNum = res[1]; 
$('#tableallCustList').load(acurl+'/1', function(result){ 
$('#myModal').modal({show:true});
});
});


$(document).on('click', '.accountRowall', function(e) { 

$('#customer_name').val( $(this).attr("data-name") );
$('#customer_id').val( $(this).attr("data-id") );
$('#dr_account_id').val( $(this).attr("data-id") );
});

$('#create').on('click', function(e) { 

var ac = $('#frmCustomer #account_id').val();
var name = $('#frmCustomer #customer_name').val();
var adrs = $('#frmCustomer #address').val();
var ar = $('#frmCustomer #area_id option:selected').val();
var cn = $('#frmCustomer #country_id option:selected').val();
var ph = $('#frmCustomer #phone').val();
var vt = $('#frmCustomer #vat_no').val();
var pn = $('#frmCustomer #contact_name').val();
var em = $('#frmCustomer #email').val();
var dpt = $('#frmCustomer #department_id').val();

if(name=="") {
alert('Customer name is required!');
return false;
} else {		
$('#addressDtls').toggle();

$.ajax({
url: "{{ url('account_master/ajax_create/') }}",
type: 'get',
data: 'account_id='+ac+'&master_name='+name+'&address='+adrs+'&area_id='+ar+'&country_id='+cn+'&phone='+ph+'&vat_no='+vt+'&category=CUSTOMER&contact_name='+pn+'&email='+em+'&department_id='+dpt,
success: function(data) { //console.log(data);
if(data > 0) {
    $('#sucessmsg').toggle( function() {
        $('#cususe').attr("data-id",data);
        $('#cususe').attr("data-name",name);
        $('#cususe').attr("data-new",true);
    });
} else if(data == 0){
    $('#addressDtls').toggle();
    alert('Customer name already exist!');
    return false;
} else {
    $('#addressDtls').toggle();
    alert('Something went wrong, Account failed to add!');
    return false;
}
}
})
}
});

/* $('#frmCustomer').on('submit',function(e){ 

e.preventDefault(e);
var name = $('#frmCustomer #customer_name').val();
if(name=="") {
alert('Customer name is required!');
return false;
} else {
$('#addressDtls').toggle();
    
$.ajax({
type:"POST",
url:"{{ url('account_master/ajax_create') }}",
data:$(this).serialize(),
dataType: "json",
success: function(data){
    //console.log(data);break;
    if(data) {
        
        $('#sucessmsg').toggle( function() {
            $('#cususe').attr("data-id",data);
            $('#cususe').attr("data-name",name);
            
        });
    } else {
        $('#addressDtls').toggle();
        //$('#sucessmsg').toggle();
        alert('Customer name already exist!');
        return false;
    }
},
error: function(data){
    //alert('Customer name already exist!');
}
})
}
}); */

var inputMapper = {
"name": 1,
"position": 2,
"office": 3,
"age": 4
};
/*	var dtInstance = $("#tableCustList").DataTable({
"lengthMenu": [10, 25, 50, "ALL"],
bLengthChange: false,
mark: true
});*/
$("input").on("input", function() {
var $this = $(this);
var val = $this.val();
var key = $this.attr("name");
dtInstance.columns(inputMapper[key] - 1).search(val).draw();
});
});
</script>