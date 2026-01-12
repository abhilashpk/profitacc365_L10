@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
	<link href="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="{{asset('assets/vendors/summernote/summernote.css')}}">
    <link href="{{asset('assets/vendors/trumbowyg/css/trumbowyg.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/form_editors.css')}}">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
            Cheque
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Maintenance
                    </a>
                </li>
                <li>
                    <a href="#">Cheque</a>
                </li>
                <li class="active">
                    Add New
                </li>
            </ol>
        </section>
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Cheque 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmMachine" id="frmMachine" action="{{url('cheque_details/save')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
	


                                    <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Cheque No</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="cheque_no" name="cheque_no" autocomplete="off"  placeholder="Cheque No">
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Cheq.Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" autocomplete="off" id="cheque_date" name="cheque_date" data-language='en'   readonly />
								   </div>
                                </div>
<!-- 



									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Cheque Date</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="cheque_date" name="cheque_date" autocomplete="off" placeholder="Cheque Date">
										</div>
									</div> -->
									

                                    <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Payee</label>
                                    <div class="col-sm-5">
                                    <input type="text" name="customer_name" id="customer_name" readonly value="{{old('customer_name')}}" class="form-control" data-toggle="modal" data-target="#customer_modal" placeholder="Customer">
											<input type="hidden" name="customer_id" id="customer_id" value="{{old('customer_id')}}">
										 </div>
                                         <label for="input-text" class="col-sm-1 control-label">Format</label>
                                         <div class="col-sm-4">
                                       
										<select id="select22" class="form-control pull-right select2"  name="bank_id"  style="width:100%">
											@foreach($banks as $row)
											<option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach
										</select>
                                        </div>
                                </div>


							       <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Amount Number</label>
										  <div class="col-sm-5">
                                          <input type="text" class="form-control" id="amount_number" name="amount_number" autocomplete="off" placeholder="amount number" onkeyup="update();">
                                          
                                   
											 </div>
                                             <label for="input-text" class="col-sm-1 control-label"></label>
                                    <div class="col-sm-4">
                                        
                                       <input type="checkbox" id="is_payee"  name="is_payee" class="form-control" value="1">A/C Payee
								   </div>
                                </div>
							 	
   						 <div class="form-group">
                                
                                             <label for="input-text" class="col-sm-2 control-label">Amount Words</label>
                                    <div class="col-sm-10">
                                        
                                        <input type="text" class="form-control pull-right" readonly autocomplete="off" id="amount_words" name="amount_words"  />
								   </div>
                                </div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label"></label>
										<div class="col-sm-10">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('cheque_details') }}" class="btn btn-danger">Cancel</a>
										</div>
									</div>
									
								 </form>
                            <div id="customer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Customer</h4>
                                        </div>
                                        <div class="modal-body" id="customerData">
														
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            






                     
                        </div>
                    </div>
                </div>
            </div>

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
     <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
        <!-- end of page level js -->

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/trumbowyg/js/trumbowyg.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/summernote/summernote.min.js')}}"></script>
<script src="{{asset('assets/js/custom_js/form_editors.js')}}" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>
<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>


<script>
"use strict";
$('#cheque_date').datepicker( { 
	dateFormat: 'dd-mm-yyyy',
	autoClose: true
});
function update() {
var num =document.getElementById('amount_number').value;
var splittedNum =num.toString().split('.')
var nonDecimal=splittedNum[0]
console.log('jjjj: '+nonDecimal);
var decimal=splittedNum[1]
console.log('jcxgvcgjjj: '+decimal);
if(isNaN(decimal) == true)
{
var values=price_in_words(Number(nonDecimal))+"and"+"  "+" Zero"+"Fils" +"  " + "Only"
}
else{
var values=price_in_words(Number(nonDecimal))+"and"+price_in_words(Number(decimal))+"Fils" +"  " + "Only"
}
console.log('value: '+values);
document.getElementById('amount_words').value = values;
}

function price_in_words(price) {
    console.log('price value: '+price);
  var sglDigit = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"],
    dblDigit = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"],
    tensPlace = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"],
    handle_tens = function(dgt, prevDgt) {
      return 0 == dgt ? "" : " " + (1 == dgt ? dblDigit[prevDgt] : tensPlace[dgt])
    },
    handle_utlc = function(dgt, nxtDgt, denom) {
      return (0 != dgt && 1 != nxtDgt ? " " + sglDigit[dgt] : "") + (0 != nxtDgt || dgt > 0 ? " " + denom : "")
    };

  var str = "",
    digitIdx = 0,
    digit = 0,
    nxtDigit = 0,
    words = [];
  if (price += "", isNaN(parseInt(price))) str = "";
  else if (parseInt(price) > 0 && price.length <= 10) {
    for (digitIdx = price.length - 1; digitIdx >= 0; digitIdx--) switch (digit = price[digitIdx] - 0, nxtDigit = digitIdx > 0 ? price[digitIdx - 1] - 0 : 0, price.length - digitIdx - 1) {
      case 0:
        words.push(handle_utlc(digit, nxtDigit, ""));
        break;
      case 1:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 2:
        words.push(0 != digit ? " " + sglDigit[digit] + " Hundred" + (0 != price[digitIdx + 1] && 0 != price[digitIdx + 2] ? " and" : "") : "");
        break;
      case 3:
        words.push(handle_utlc(digit, nxtDigit, "Thousand"));
        break;
      case 4:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 5:
        words.push(handle_utlc(digit, nxtDigit, "Lakh"));
        break;
      case 6:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 7:
        words.push(handle_utlc(digit, nxtDigit, "Crore"));
        break;
      case 8:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 9:
        words.push(0 != digit ? " " + sglDigit[digit] + " Hundred" + (0 != price[digitIdx + 1] || 0 != price[digitIdx + 2] ? " and" : " Crore") : "")
    }
    str = words.reverse().join("")
  } else str = "";
  return str

}




$(document).ready(function () {


    $("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Bank"
    });
  
	$("#select23").select2({
        theme: "bootstrap",
        placeholder: "Select Job"
    });



	var urlcode = "{{ url('machine/checkregno/') }}";
    $('#frmMachine').bootstrapValidator({
        fields: {
			cheque_no: {
                validators: {
                    notEmpty: {
                        message: 'cheque no is required and cannot be empty!'
                    }
                }
            },
			cheque_date: {
                validators: {
                    notEmpty: {
                        message: 'cheque date is required and cannot be empty!'
                    }
                }
            },
            amount_number: {
                validators: {
                    notEmpty: {
                        message: 'amount number is required and cannot be empty!'
                    }
                }
            },
			customer_name: {
                validators: {
                    notEmpty: {
                        message: 'customer name is required and cannot be empty!'
                    }
                }
            },
            bank_id: {
                validators: {
                    notEmpty: {
                        message: 'bank name is required and cannot be empty!'
                    }
                }
            }
			/* chasis_no: {
                validators: {
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('chasis_no').val()
                            };
                        },
                        message: 'The chasis no already exist!'
                    } 
                }
            }  */
          
        }
        
    }).on('reset', function (event) {
        $('#frmMachine').data('bootstrapValidator').resetForm();
    });
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	





	$(document).on('click', '.custRow', function(e) { //console.log($(this).attr("data-trnno"));
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
	});
});
</script>
@stop
