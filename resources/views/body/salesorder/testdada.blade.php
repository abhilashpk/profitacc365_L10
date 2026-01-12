	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<!--page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <!--end of page level css-->
	
<h3>T Data</h3>
@foreach($tdata as $data)
<li>{{ $data['name'] }}</li>
@endforeach
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-success filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-columns"></i> With Mark.js column Search
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <span>Name:</span>
                                    <input type="text" name="name" placeholder="Bradley..."
                                           class="form-control input-sm">
                                    <span>Office:</span>
                                    <input type="text" name="office" placeholder="London..."
                                           class="form-control input-sm">
                                </div>
                                <div class="col-xs-6">
                                    <span>Position:</span>
                                    <input type="text" name="position" placeholder="Software..."
                                           class="form-control input-sm">
                                    <span>E-mail:</span>
                                    <input type="text" name="age" placeholder="abc@xyzmail.com"
                                           class="form-control input-sm">
                                </div>
                            </div>
                            <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="table5">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>E-mail</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><a href="" class="nam" data-id="1" data-dismiss="modal">Clara</a></td>
                                        <td>Global Data Producer</td>
                                        <td>Clara_Cremin</td>
                                        <td>Clara65@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td><a href="" class="nam" data-id="2" data-toggle="modal" data-target="#grid_modal2">Flavio</a></td>
                                        <td>Senior Quality Consultant</td>
                                        <td>Flavio63</td>
                                        <td>Flavio31@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Carmela</td>
                                        <td>Direct Markets Architect</td>
                                        <td>Carmela_Grant69</td>
                                        <td>Carmela40@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Alexys</td>
                                        <td>Customer Usability Director</td>
                                        <td>Alexys7</td>
                                        <td>Alexys32@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Kamron</td>
                                        <td>National Assurance Associate</td>
                                        <td>Kamron.Grant33</td>
                                        <td>Kamron96@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Marilie</td>
                                        <td>Product Implementation Designer</td>
                                        <td>Marilie.Simonis60</td>
                                        <td>Marilie93@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Gardner</td>
                                        <td>Forward Tactics Associate</td>
                                        <td>Gardner.Larkin</td>
                                        <td>Gardner85@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Terrell</td>
                                        <td>Forward Mobility Executive</td>
                                        <td>Terrell.Hettinger</td>
                                        <td>Terrell_Hettinger@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Gino</td>
                                        <td>Dynamic Functionality Assistant</td>
                                        <td>Gino.Borer</td>
                                        <td>Gino21@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Deborah</td>
                                        <td>Product Marketing Developer</td>
                                        <td>Deborah82</td>
                                        <td>Deborah32@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Lucy</td>
                                        <td>District Research Orchestrator</td>
                                        <td>Lucy_Senger38</td>
                                        <td>Lucy_Senger@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rex</td>
                                        <td>International Solutions Coordinator</td>
                                        <td>Rex32</td>
                                        <td>Rex.Schroeder1@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Amir</td>
                                        <td>Lead Brand Officer</td>
                                        <td>Amir50</td>
                                        <td>Amir_Reynolds19@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Floy</td>
                                        <td>District Brand Specialist</td>
                                        <td>Floy90</td>
                                        <td>Floy.Murazik91@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Michelle</td>
                                        <td>Future Quality Representative</td>
                                        <td>Michelle_Barton</td>
                                        <td>Michelle.Barton52@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Ally</td>
                                        <td>Investor Operations Administrator</td>
                                        <td>Ally_White</td>
                                        <td>Ally22@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Maximillian</td>
                                        <td>Direct Accounts Administrator</td>
                                        <td>Maximillian_Zboncak</td>
                                        <td>Maximillian_Zboncak@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Tara</td>
                                        <td>Investor Implementation Analyst</td>
                                        <td>Tara78</td>
                                        <td>Tara_Green@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Matt</td>
                                        <td>Human Functionality Representative</td>
                                        <td>Matt42</td>
                                        <td>Matt46@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Fernando</td>
                                        <td>Direct Group Administrator</td>
                                        <td>Fernando.Schiller</td>
                                        <td>Fernando21@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Novella</td>
                                        <td>Product Mobility Specialist</td>
                                        <td>Novella_Padberg64</td>
                                        <td>Novella.Padberg26@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Branson</td>
                                        <td>Lead Implementation Facilitator</td>
                                        <td>Branson_Cormier66</td>
                                        <td>Branson47@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Ramiro</td>
                                        <td>District Communications Analyst</td>
                                        <td>Ramiro56</td>
                                        <td>Ramiro89@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Bert</td>
                                        <td>Customer Paradigm Designer</td>
                                        <td>Bert10</td>
                                        <td>Bert_Schinner8@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Archibald</td>
                                        <td>District Usability Planner</td>
                                        <td>Archibald87</td>
                                        <td>Archibald.Koss83@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Marquise</td>
                                        <td>Chief Mobility Architect</td>
                                        <td>Marquise.Schmitt64</td>
                                        <td>Marquise_Schmitt@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Fabian</td>
                                        <td>Product Research Director</td>
                                        <td>Fabian75</td>
                                        <td>Fabian_Daniel@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Samir</td>
                                        <td>Product Applications Officer</td>
                                        <td>Samir_Ortiz</td>
                                        <td>Samir.Ortiz@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Effie</td>
                                        <td>Product Brand Developer</td>
                                        <td>Effie_Luettgen</td>
                                        <td>Effie19@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Carleton</td>
                                        <td>Senior Directives Orchestrator</td>
                                        <td>Carleton5</td>
                                        <td>Carleton80@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Elisa</td>
                                        <td>Senior Accountability Director</td>
                                        <td>Elisa.Feest89</td>
                                        <td>Elisa.Feest@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Keven</td>
                                        <td>Chief Infrastructure Engineer</td>
                                        <td>Keven77</td>
                                        <td>Keven_Hayes27@hotmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Marcelino</td>
                                        <td>Dynamic Brand Architect</td>
                                        <td>Marcelino.Haag</td>
                                        <td>Marcelino_Haag45@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Lenore</td>
                                        <td>Future Mobility Orchestrator</td>
                                        <td>Lenore.Schroeder22</td>
                                        <td>Lenore.Schroeder@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Abbigail</td>
                                        <td>Future Identity Associate</td>
                                        <td>Abbigail_Fadel35</td>
                                        <td>Abbigail_Fadel30@yahoo.com</td>
                                    </tr>
                                    <tr>
                                        <td>Judah</td>
                                        <td>Senior Configuration Coordinator</td>
                                        <td>Judah3</td>
                                        <td>Judah_Schowalter99@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Jayce</td>
                                        <td>Chief Applications Supervisor</td>
                                        <td>Jayce_Rau</td>
                                        <td>Jayce.Rau@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Devin</td>
                                        <td>Future Web Director</td>
                                        <td>Devin7</td>
                                        <td>Devin76@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Grover</td>
                                        <td>Forward Infrastructure Specialist</td>
                                        <td>Grover88</td>
                                        <td>Grover_Barton35@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Zita</td>
                                        <td>International Optimization Analyst</td>
                                        <td>Zita.Lindgren</td>
                                        <td>Zita_Lindgren88@yahoo.com</td>
                                    </tr>
                                    </tbody>
                                </table>

                            <div id="grid_modal2" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal with grid arrangement</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">col-md-12
                                                    <div class="row">
                                                        <div class="col-md-6">col-md-6</div>
                                                        <div class="col-md-6">col-md-6</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
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
<!-- end of page level js -->
<script>
/* $(function() {
$(document).on('click', '.nam', function(e) 
    {
		//console.log('clara');
		$('voucher_no').val('clara');
		e.preventDefault();
	});
}); */
</script>