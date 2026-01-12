<!DOCTYPE html>
<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>
        @section('title')
            Profit ACC 365 - ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
@yield('header_styles')
<!-- end of global css -->
</head>
<body class="skin-coreplus">
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<!-- header logo: style can be found in header-->
<header class="header">
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="index " class="logo">
			<!--<h2 class="text-center">NumakPro ERP</h2>-->
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <img src="{{asset('assets/numaklogo-small.jpg')}}" alt="logo"/>
        </a>
        <!-- Header Navbar: style can be found in header-->
        <!-- Sidebar toggle button-->
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button"> 
			<i class="fa fa-fw fa-bars"></i>
            </a>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
				
                <!-- User Account: style can be found in dropdown-->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle padding-user" data-toggle="dropdown">
                        <img src="{{asset('assets/img/authors/avatar.jpg')}}" width="35"
                             class="img-circle img-responsive pull-left"
                             height="35" alt="User Image">
                        <div class="riot">
                            <div>
							{{Session::get('company')}} &#x276F;
							{{Auth::user()->name}} 
                                <span>
                                        <i class="caret"></i>
                                    </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('assets/img/authors/avatar.jpg')}}" class="img-circle" alt="User Image">
                            <p> {{Auth::user()->name}}</p>
                        </li>
                        <!-- Menu Body -->
                        
                        <li role="presentation"></li>
                       
                        <li role="presentation" class="divider"></li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ URL :: to('users/reset/password') }}">
									<i class="fa fa-fw fa-unlock"></i>
                                    Password Reset
                                </a>
                            </div>
                            <div class="pull-left">
                                <a href="#" 
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-fw fa-sign-out"></i>
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar-->
        <section class="sidebar">
            <div id="menu" role="navigation">
                <div class="nav_profile">
                    <div class="media profile-left">
                       
                    </div>
                </div>
                <ul class="navigation">
					 <li {!! (Request::is('dashboard') ? 'class="active"' : '') !!}>
                        <a href="{{ URL::to('dashboard') }}">
                            <i class="menu-icon fa fa-fw fa-home"></i>
                            <span class="mm-text ">Dashboard</span> 
                        </a>
                    </li>
				@if(auth()->user()->can('ld-create'))
				  @can('ld-list')
                   <li {!! ( Request::is('leads') || Request::is('leads/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="{{ URL::to('leads') }}">
                        <i class="fa fa-fw fa-briefcase"></i>
                            <span>Leads</span>
                        </a>
					</li>
					@endcan
				@endif
              
                    @if(auth()->user()->can('ac-category-list') || auth()->user()->can('ac-group-list') || auth()->user()->can('ac-master-list') || auth()->user()->can('ac-enquiry-list') )
                    <li {!! ( Request::is('account') || Request::is('accategory/*') || Request::is('accategory') || Request::is('acgroup') || Request::is('acgroup/*') || Request::is('account_master') || Request::is('account_master/*') || Request::is('account_enquiry') || Request::is('account_enquiry/reconciliation') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                            <a href="#">
                            <i class="fa fa-fw fa-briefcase"></i>
                                <span>Accounts</span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @can('ac-category-list')
                                <li {!! (Request::is('accategory') || Request::is('accategory/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('accategory') }}">
                                        <i class="fa fa-fw fa-ticket"></i> Account Category
                                    </a>
                                </li>
                            @endcan
                            
                            @can('ac-group-list')
                                <li {!! (Request::is('acgroup') || Request::is('acgroup/*')? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('acgroup') }}">
                                        <i class="fa fa-fw fa-tasks"></i> Account Group
                                    </a>
                                </li>
                                @endcan
							
                                @can('ac-master-list')
                                <li {!! (Request::is('account_master') || Request::is('account_master/*')? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('account_master') }}">
                                        <i class="fa fa-fw fa-book"></i> Account Master
                                    </a>
                                </li>
                                @endcan
                                                            
                                @can('ac-enquiry-list')
                                <li {!! (Request::is('account_enquiry') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('account_enquiry') }}">
                                        <i class="fa fa-fw fa-question-circle"></i> Account Enquiry  
                                    </a>
                                </li>
                                @endcan
							
                                @can('address-list')
                                <li {!! (Request::is('account_enquiry/address') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('account_enquiry/address') }}">
                                        <i class="fa fa-fw fa-question-circle"></i> Address List  
                                    </a>
                                </li>
                                @endcan

                                @can('ac-reconciliation')
                                <li {!! (Request::is('account_enquiry/reconciliation') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('account_enquiry/reconciliation') }}">
                                        <i class="fa fa-fw fa-question-circle"></i> Reconciliation 
                                    </a>
                                </li>
                                @endcan
                                
                        </ul>
                    </li>
					@endif

                    
                   
                            

				   @if(auth()->user()->can('job-list') || auth()->user()->can('jobtype-list') || auth()->user()->can('docdept-list') || auth()->user()->can('sman-list') || auth()->user()->can('dept-list') || auth()->user()->can('bank-list') || auth()->user()->can('crncy-list') || auth()->user()->can('area-list') || auth()->user()->can('loc-list') || auth()->user()->can('con-list') || auth()->user()->can('term-list') || auth()->user()->can('vat-list') || auth()->user()->can('vehicle-list') || auth()->user()->can('head-foot-list')) 
				   <li {!! ( Request::is('jobmaster') || Request::is('jobmaster/*') || Request::is('jobtype') || Request::is('salesman') || Request::is('salesman/*') || Request::is('bank') || Request::is('bank/*') || Request::is('currency') || Request::is('currency/*') || Request::is('area') || Request::is('area/*') || Request::is('location') || Request::is('location/*') || Request::is('country') || Request::is('country/*') || Request::is('department') || Request::is('department/*') || Request::is('terms') || Request::is('terms/*') || Request::is('header_footer') || Request::is('header_footer/*') ||Request::is('template_name') || Request::is('template_name/*') || Request::is('vat_master') || Request::is('vat_master/*') || Request::is('machine') || Request::is('machine/*') || Request::is('paper') || Request::is('paper/*') || Request::is('contract_type') || Request::is('contract_type/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-fw fa-wrench"></i> 
                            <span>Maintenance</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
							@can('job-list')
							<li {!! (Request::is('jobmaster') || Request::is('jobmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('jobmaster') }}">
                                    <i class="fa fa-fw fa-book"></i> Job Master
                                </a>
                            </li>
							@endcan

                           
                            @can('pb-list')
                            <li {!! (Request::is('jobmaster/viewbudget') || Request::is('jobmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('jobmaster/viewbudget') }}">
                                    <i class="fa fa-fw fa-bell-o custom"></i> Project Budgeting
                                </a>
                            </li>
                            @endcan
                            

							@can('jobtype-list')
                            <li {!! (Request::is('jobtype') || Request::is('jobtype/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('jobtype') }}">
                                    <i class="fa fa-fw fa-credit-card"></i> Job Type
                                </a>
                            </li>
                            @endcan
							
							@can('docdept-list')
                            <li {!! (Request::is('doctype') || Request::is('doctype/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('doctype') }}">
                                    <i class="fa fa-fw fa-credit-card"></i> Document Department
                                </a>
                            </li>
                            @endcan
							
							@can('sman-list')
							<li {!! (Request::is('salesman') || Request::is('salesman/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('salesman') }}">
                                    <i class="fa fa-fw fa-male"></i> Salesman
                                </a>
                            </li>
							@endcan
							
							@can('dept-list')
							<li {!! (Request::is('department') || Request::is('department/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('department') }}">
                                    <i class="fa fa-fw fa-puzzle-piece"></i> Department
                                </a>
                            </li>
							@endcan
							
							@can('bank-list')
                            <li {!! (Request::is('bank') || Request::is('bank/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('bank') }}">
                                    <i class="fa fa-fw fa-credit-card"></i> Bank
                                </a>
                            </li>
                            @endcan
							
							@can('crncy-list')
							<li {!! (Request::is('currency') || Request::is('currency/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('currency') }}">
                                    <i class="fa fa-fw fa-money"></i> Currency
                                </a>
                            </li>
							@endcan
							
							@can('area-list')
							<li {!! (Request::is('area') || Request::is('area/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('area') }}">
                                    <i class="fa fa-fw fa-road"></i> Area
                                </a>
                            </li>
							@endcan
							
							@can('loc-list')
							<li {!! (Request::is('location') || Request::is('location/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('location') }}">
                                    <i class="fa fa-fw fa-map-marker"></i> Location
                                </a>
                            </li>
							@endcan
							
							@can('con-list')
							<li {!! (Request::is('country') || Request::is('country/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('country') }}">
                                    <i class="fa fa-fw fa-flag"></i> Country
                                </a>
                            </li>
							@endcan
							
							@can('term-list')
							 <li {!! (Request::is('terms') || Request::is('terms/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('terms') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Terms
                                </a>
                            </li>
							@endcan

                            @can('tn-list')
							 <li {!! (Request::is('template_name') || Request::is('template_name/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('template_name') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Email Template 
                                </a>
                            </li>
							@endcan
							
							@can('head-foot-list')
							<li {!! (Request::is('header_footer') || Request::is('header_footer/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('header_footer') }}">
                                    <i class="fa fa-fw fa-retweet"></i> Header/Footer
                                </a>
                            </li>
							@endcan
							
							@can('vat-list')
							<li {!! (Request::is('vat_master') || Request::is('vat_master/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('vat_master') }}">
                                    <i class="fa fa-fw fa-sort-alpha-desc"></i> Vat Master
                                </a>
                            </li>
							@endcan
							
							@can('vehicle-list')
							<li {!! (Request::is('vehicle') || Request::is('vehicle/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('vehicle') }}">
                                    <i class="fa fa-fw fa-truck"></i> Vehicle
                                </a>
                            </li>
							@endcan

							@can('mn-list')
							<li {!! (Request::is('machine') || Request::is('machine/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('machine') }}">
                                    <i class="fa fa-fw fa-print"></i> Machine
                                </a>
                            </li>
							@endcan
                            @can('ppr-list')
							<li {!! (Request::is('paper') || Request::is('paper/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('paper') }}">
                                    <i class="fa fa-fw fa-file-o"></i> Paper
                                </a>
                            </li>
                            @endcan
                            
                                @can('ctyp-list')
                                <li {!! (Request::is('contract_type') || Request::is('contract_type/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('contract_type') }}">
                                        <i class="fa fa-fw fa-bars"></i> Contract Type
                                    </a>
                                </li>
                                @endcan
							
                                @can('pm-list')
                            <li {!! (Request::is('package_master') || Request::is('package_master/*') ? 'class="active"' : '') !!}>
                                        <a href="{{ URL::to('package_master') }}">
                                            <i class="fa fa-fw fa-truck"></i> Package Master
                                        </a>
                                    </li>
                            @endcan  
                            </ul>
                        </li>
                        @endif

                    



                    @if(auth()->user()->can('crm-list') || auth()->user()->can('crm-list')) 
				 
                   <li {!! ( Request::is('customerleads') || Request::is('customerleads/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-bookmark custom"></i> 
                            <span>&nbsp Sales CRM</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                        @can('crm-list')

                            <li {!! (Request::is('customerleads/add') || Request::is('customerleads/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('customerleads/add') }}">
                                    <i class="fa fa-fw fa-bell-o custom"></i> Add New
                                </a>
                            </li>
							<li {!! (Request::is('customerleads/customertype') || Request::is('customerleads/customertype/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('customerleads/customertype') }}">
                                    <i class="fa fa-fw fa-bell-o custom"></i> Customer Type
                                </a>
                            </li>
                        </ul>
                        </li>
                        <li {!! ( Request::is('customerleads') || Request::is('customerleads/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                      
                        <a href="#">
                        <i class="fa fa-twitter-square"></i> 
                            <span>&nbsp Status</span>
                            <span class="fa arrow"></span>
                        </a>

                        <ul class="sub-menu">
                            <li {!! (Request::is('customerleads/customer') || Request::is('customerleads/customer/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('customerleads/customer') }}">
                                    <i class="fa fa-fw fa-bell-o custom"></i> Customer
                                </a>
                            </li>
							 <li {!! (Request::is('customerleads/enquirystatus') || Request::is('customerleads/enquirystatus/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('customerleads/enquirystatus') }}">
                                    <i class="fa fa-fw fa-bell-o custom"></i> Enquiry
                                </a>
                            </li>
                            <li {!! (Request::is('customerleads/prospective') || Request::is('customerleads/prospective/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('customerleads/prospective') }}">
                                    <i class="fa fa-fw fa-bell-o custom"></i> Prospective
                                </a>
                            </li>
                            <li {!! (Request::is('customerleads/archive') || Request::is('customerleads/archive/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('customerleads/archive') }}">
                                    <i class="fa fa-fw fa-bell-o custom"></i>Archive
                                </a>
                                </li>
                                @endcan
                        </ul>

                    </li> 
				



                    <li {!! ( Request::is('customerleads') || Request::is('customerleads/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                      
                      <a href="#">
                      <i class="fa fa-twitter-square"></i> 
                          <span>&nbsp Data Transfer</span>
                          <span class="fa arrow"></span>
                      </a>

                      <ul class="sub-menu">
                      @can('crm-list')
                          <li {!! (Request::is('customerleads/data_transfer') || Request::is('customerleads/data_transfer/*') ? 'class="active"' : '') !!}>
                              <a href="{{ URL::to('customerleads/data_transfer') }}">
                                  <i class="fa fa-fw fa-gift"></i> Transfer
                              </a>
                          </li>
                         
                          @endcan 
                      </ul>

                  </li> 
              	@endif


                  @if(auth()->user()->can('bd-list') || auth()->user()->can('bd-list') )
					
                    <li {!! ( Request::is('manual_journal') || Request::is('manual_journal/*') || Request::is('contra_type') || Request::is('contra_type/*') ||  Request::is('buildingmaster') || Request::is('buildingmaster/*') || Request::is('manual_journal') || Request::is('manual_journal/*') ||  Request::is('flatmaster') || Request::is('flatmaster/*')||  Request::is('contractbuilding') || Request::is('contractbuilding/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-bookmark custom"></i> 
                            <span>&nbsp Real Estate</span>
                            <span class="fa arrow"></span>
                        </a>
                         
                        <ul class="sub-menu">
                        @can('bd-list') 
                            <li {!! (Request::is('buildingmaster') || Request::is('buildingmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('buildingmaster') }}">
                                    <i class="fa fa-fw fa-gift"></i> Building Master
                                </a>
                            </li>
                            
                           
							<li {!! (Request::is('flatmaster') || Request::is('flatmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('flatmaster') }}">
                                    <i class="fa fa-fw fa-sort-numeric-asc"></i> Flat Master
                                </a>
                            </li>

                           
                              <li {!! (Request::is('duration') || Request::is('duration/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('duration') }}">
                                    <i class="fa fa-fw fa-gavel"></i> Duration
                                </a>
                            </li>
 
                            <li {!! (Request::is('contra_type') || Request::is('contra_type/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contra_type') }}">
                                    <i class="fa fa-fw fa-square"></i> Contract Type
                                </a>
                            </li> 
                           
							<li {!! (Request::is('contractbuilding') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contractbuilding') }}">
                                    <i class="fa fa-fw fa-square"></i> Contract
                                </a>
                            </li> 
                         
							<li {!! (Request::is('contractbuilding/enquiry') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contractbuilding/enquiry') }}">
                                    <i class="fa fa-fw fa-square"></i> Enquiry
                                </a>
                            </li>
                            <li {!! (Request::is('contractbuilding/expiry') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contractbuilding/expiry') }}">
                                    <i class="fa fa-fw fa-square"></i> Contract Expiry
                                </a>
                            </li>
                           
							<li {!! (Request::is('contractbuilding/history') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contractbuilding/history') }}">
                                    <i class="fa fa-fw fa-square"></i> History
                                </a>
                            </li>
							
							<li {!! (Request::is('contractbuilding/closed') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contractbuilding/closed') }}">
                                    <i class="fa fa-fw fa-square"></i> Closed
                                </a>
                            </li>
                          
                            <li {!! (Request::is('manual_journal') || Request::is('manual_journal/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('manual_journal') }}">
                                <i class="fa fa-fw fa-circle"></i> Manual Journal
                                </a>
                            </li>
                            @endcan

                        </ul>
                        </li>
                    	@endif

                         @if(auth()->user()->can('cce-list') || auth()->user()->can('cce-list') )
					
                    <li {!! ( Request::is('tenantenquiry') || Request::is('tenantenquiry/*') ||  Request::is('contract-connection') || Request::is('contract-connection/*') || Request::is('buildingmaster') || Request::is('buildingmaster/*') ||   Request::is('flatmaster') || Request::is('flatmaster/*')||  Request::is('tenantmaster') || Request::is('tenantmaster/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-bookmark custom"></i> 
                            <span>&nbsp Contract-Connection Entry</span>
                            <span class="fa arrow"></span>
                        </a>
                         
                        <ul class="sub-menu">
                        @can('cce-build-list') 
                            <li {!! (Request::is('buildingmaster') || Request::is('buildingmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('buildingmaster') }}">
                                    <i class="fa fa-fw fa-gift"></i> Building Master
                                </a>
                            </li>
                           @endcan
                        @can('cce-flat-list') 
							<li {!! (Request::is('flatmaster') || Request::is('flatmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('flatmaster') }}">
                                    <i class="fa fa-fw fa-sort-numeric-asc"></i> Flat Master
                                </a>
                            </li>
                            @endcan
							@can('cce-tenant-list') 
                            <li {!! (Request::is('tenantmaster') || Request::is('tenantmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('tenantmaster') }}">
                                    <i class="fa fa-fw fa-square"></i> Tenant Master
                                </a>
                            </li>
                            @endcan
                            @can('cce-type-list') 
							<li {!! (Request::is('contra_type/list-settings') || Request::is('contra_type/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contra_type/list-settings') }}">
                                    <i class="fa fa-fw fa-square"></i> Contract Type
                                </a>
                            </li> 
							@endcan
							@can('cce-enquiry-list') 
                             <li {!! (Request::is('tenantenquiry') || Request::is('tenantenquiry/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('tenantenquiry') }}">
                                    <i class="fa fa-fw fa-square"></i> Enquiry
                                </a>
                            </li>
							@endcan
							@can('cco-list') 
							 <li {!! (Request::is('contract-connection') || Request::is('contract-connection/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contract-connection') }}">
                                    <i class="fa fa-fw fa-square"></i> Contract Connection
                                </a>
                            </li>
							@endcan
							@can('cmr-list')
							<li {!! (Request::is('contract-connection/reading-list') || Request::is('contract-connection/contract-connection/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contract-connection/reading-list') }}">
                                    <i class="fa fa-fw fa-square"></i> Meeter Reading
                                </a>
                            </li>
							<li {!! (Request::is('contract-connection/disconnection-list') || Request::is('contract-connection/disconnection-connection/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contract-connection/disconnection-list') }}">
                                    <i class="fa fa-fw fa-square"></i> Disconnection
                                </a>
                            </li>
                            @endcan

                        </ul>
                        </li>
                    	@endif

                 @if(auth()->user()->can('cae-list') || auth()->user()->can('cae-dspch-list') )
				<li {!! ( Request::is('consignee') || Request::is('consignee/*') || Request::is('shipper') || Request::is('shipper/*') ||  Request::is('collection_type') || Request::is('collection_type/*') || Request::is('delivery_type') || Request::is('delivery_type/*') ||  Request::is('destination_type') || Request::is('destination_type/*') || Request::is('cargounit') || Request::is('cargounit/*')||  Request::is('cargo_vehicle') || Request::is('cargo_status/*')|| Request::is('cargo_status') || Request::is('cargo_vehicle/*')||Request::is('cargo_salesman') || Request::is('cargo_salesman/*')||  Request::is('cargo_receipt') || Request::is('cargo_receipt/*')||  Request::is('cargo_waybill') || Request::is('cargo_waybill/*')||  Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/*')||  Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                         <a href="#">
                        <i class="fa fa-bookmark custom"></i> 
                            <span>&nbsp Cargo Entry</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                         @can('cae-list') 
						 @if(Auth::user()->location_id!=0)
						<li {!! (Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/waybills') || Request::is('cargo_despatchbill/viewbills/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_despatchbill/waybills') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Way Bills
                                </a>
                        </li>
						@else
                         <ul class="sub-menu">
                         <li {!! ( Request::is('consignee') || Request::is('consignee/*') || Request::is('shipper') || Request::is('shipper/*') ||  Request::is('collection_type') || Request::is('collection_type/*') || Request::is('delivery_type') || Request::is('delivery_type/*') ||  Request::is('destination_type') || Request::is('destination_type/*') || Request::is('cargounit') || Request::is('cargounit/*')||  Request::is('cargo_vehicle') || Request::is('cargo_status/*')|| Request::is('cargo_status') || Request::is('cargo_vehicle/*')||Request::is('cargo_salesman') || Request::is('cargo_salesman/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                           <a href="#">
                        <i class="fa fa-bookmark custom"></i> 
                            <span>&nbsp Masters</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu sub-submenu">
                        <li {!! (Request::is('consignee') || Request::is('consignee/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('consignee') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Consignee
                                </a>
                        </li>
                        <li {!! (Request::is('shipper') || Request::is('shipper/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('shipper') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Shipper
                                </a>
                        </li>
                        <li {!! (Request::is('collection_type') || Request::is('collection_type/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('collection_type') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Collection Type
                                </a>
                        </li>
                        <li {!! (Request::is('cargounit') || Request::is('cargounit/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargounit') }}">
                                    <i class="fa fa-fw fa-sort-numeric-asc"></i>Cargo Unit
                                </a>
                        </li>

                        <li {!! (Request::is('cargo_vehicle') || Request::is('cargo_vehicle/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_vehicle') }}">
                                   <i class="fa fa-fw fa-truck"></i>Cargo Vehicle
                                </a>
                        </li>    
                        <li {!! (Request::is('delivery_type') || Request::is('delivery_type/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('delivery_type') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Delivery Type
                                </a>
                        </li>
                        <li {!! (Request::is('destination_type') || Request::is('destination_type/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('destination_type') }}">
                                    <i class="fa fa-fw fa-map-marker"></i> Destination
                                </a>
                        </li>
                         <li {!! (Request::is('cargo_salesman') || Request::is('cargo_salesman/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_salesman') }}">
                                    <i class="fa fa-fw fa-male"></i> Cargo Salesman
                                </a>
                        </li>
                         <li {!! (Request::is('cargo_status') || Request::is('cargo_status/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_status') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Status
                                </a>
                        </li>
                        </ul>
                        </li>
                        </ul>
                        
                        <ul class="sub-menu">
                        <li {!! (Request::is('cargo_receipt') || Request::is('cargo_receipt/*')||  Request::is('cargo_waybill') || Request::is('cargo_waybill/*')||  Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/*')||  Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                         <a href="#">
                        <i class="fa fa-bookmark custom"></i> 
                            <span>&nbsp View</span>
                            <span class="fa arrow"></span>
                        </a>
                         <ul class="sub-menu sub-submenu">

                         <li {!! (Request::is('cargo_receipt') || Request::is('cargo_receipt/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_receipt') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Recepit
                                </a>
                        </li>
                         <li {!! (Request::is('cargo_waybill') || Request::is('cargo_waybill/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_waybill') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Waybill
                                </a>
                        </li>
                        <li {!! (Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/add') || Request::is('cargo_despatchbill/edit/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_despatchbill') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Despatch Report
                                </a>
                        </li>
                         </ul>
                        </li>
                        </ul>
                    <ul class="sub-menu">
                        <li {!! (Request::is('cargo_receipt') || Request::is('cargo_receipt/*')||  Request::is('cargo_waybill') || Request::is('cargo_waybill/*')||  Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/*')||  Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                         <a href="#">
                        <i class="fa fa-bookmark custom"></i> 
                            <span>&nbsp Create New</span>
                            <span class="fa arrow"></span>
                        </a>
                         <ul class="sub-menu sub-submenu">
                         <li {!! (Request::is('cargo_receipt') || Request::is('cargo_receipt/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_receipt/add') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Recepit
                                </a>
                        </li>
                         <li {!! (Request::is('cargo_waybill') || Request::is('cargo_waybill/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_waybill/add') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Waybill
                                </a>
                        </li>
                        <li {!! (Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/add') || Request::is('cargo_despatchbill/edit/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_despatchbill/add') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Despatch Report
                                </a>
                        </li>
                        </ul>
                        </li>
                        </ul> 
						 <li {!! (Request::is('cargo_despatchbill') || Request::is('cargo_despatchbill/list') || Request::is('cargo_despatchbill/view/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_despatchbill/list') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Despatch Report Status
                                </a>
                        </li>
                        <li {!! (Request::is('cargo_despatchbill/report') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_despatchbill/report') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Report
                                </a>
                        </li>
						@endif
                        @endcan
						
						@can('cae-dspch-list') 
						 <li {!! (Request::is('cargo_despatchbill/list')  ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cargo_despatchbill/list') }}">
                                    <i class="fa fa-fw fa-pencil-square-o"></i> Cargo Despatch List
                                </a>
                        </li>
						@endcan
                        </ul> 
                  </li>      
                @endif
           
            @if(auth()->user()->can('re-list') || auth()->user()->can('re-list') )
               <li {!! (Request::is('rental_report') || Request::is('rental_report/*') || Request::is('rental_sales') || Request::is('rental_sales/*') || Request::is('rental_driver') || Request::is('rental_driver/*') || Request::is('rental_supplierdriver') || Request::is('rental_supplierdriver/*')|| Request::is('rental_customerdriver') || Request::is('rental_customerdriver/*') || Request::is('purchase_rental') || Request::is('purchase_rental/*')|| Request::is('rental_sales') || Request::is('rental_sales/*')|| Request::is('itemmaster') || Request::is('itemmaster/*')|| Request::is('unit') || Request::is('unit/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                <a href="#">
                        <i class="fa fa-bookmark custom"></i> 
                            <span>&nbsp Rental Entry</span>
                            <span class="fa arrow"></span>
                </a>
                 <ul class="sub-menu">
                        @can('re-list') 
                         <li {!! (Request::is('rental_driver') || Request::is('rental_driver/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('rental_driver') }}">
                                   <i class="fa fa-fw fa-truck"></i>Driver
                                </a>
                        </li>   
                      <!--  <li {!! (Request::is('rental_supplierdriver') || Request::is('rental_supplierdriver/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('rental_supplierdriver') }}">
                                   <i class="fa fa-fw fa-truck"></i>Supplier's Driver
                                </a>
                        </li>  
                        <li {!! (Request::is('rental_customerdriver') || Request::is('rental_customerdriver/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('rental_customerdriver') }}">
                                   <i class="fa fa-fw fa-truck"></i>Customer's Driver
                                </a>
                        </li> -->
                        <li {!! (Request::is('itemmaster') || Request::is('itemmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('itemmaster') }}">
                                    <i class="fa fa-fw fa-shopping-cart"></i> Car Master
                                </a>
                            </li> 
                        
                        <li {!! (Request::is('unit') || Request::is('unit/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('unit') }}">
                                    <i class="fa fa-fw fa-sort-numeric-asc"></i> Unit
                                </a>
                            </li>    
                         <li {!! (Request::is('purchase_rental') || Request::is('purchase_rental/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('purchase_rental') }}">
                                   <i class="fa fa-fw fa-pencil-square-o"></i>Purchase
                                </a>
                        </li>      
						<li {!! (Request::is('rental_sales') || Request::is('rental_sales/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('rental_sales') }}">
                                   <i class="fa fa-fw fa-pencil-square-o"></i>Sales
                                </a>
                       </li>   
						<li {!! (Request::is('rental_report') || Request::is('rental_report/*') ? 'class="active"' : '') !!}>
							<a href="{{ URL::to('rental_report') }}">
							   <i class="fa fa-fw fa-magnet"></i> Rental Report
							</a>
						</li>
                        @endcan  
                    </ul>  
               </li>
            @endif


					@if(auth()->user()->can('job-assigned-list') || auth()->user()->can('job-working-list') || auth()->user()->can('job-completed-list') || auth()->user()->can('job-approved-list'))
					<li {!! (Request::is('job_order') || Request::is('job_order/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-fw fa-building-o"></i>
                            <span>Job Order</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">

							@can('job-assigned-list')
							<li {!! (Request::is('job_order') || Request::is('job_order/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('job_order/Assigned') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Assigned Jobs
                                </a>
                            </li>
							@endcan
							
							@can('job-working-list')
							<li {!! (Request::is('job_order') || Request::is('job_order/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('job_order/Working') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Working Jobs
                                </a>
                            </li>
							@endcan
							
							@can('job-completed-list')
							<li {!! (Request::is('job_order') || Request::is('job_order/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('job_order/Completed') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Completed Jobs
                                </a>
                            </li>
							@endcan
							
							@can('job-approved-list')
							<li {!! (Request::is('job_order') || Request::is('job_order/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('job_order/Approved') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Approved Jobs
                                </a>
                            </li>
							@endcan
                                @can('job-report-list')
                                <li {!! (Request::is('job_order/report') || Request::is('job_order/report/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('job_order/report') }}">
                                        <i class="fa fa-fw fa-list-alt"></i> Job Report
                                    </a>
                                </li>
                                @endcan
						</ul>
					</li>
				@endif
                 @if(auth()->user()->can('soj-list') || auth()->user()->can('soj-list') || auth()->user()->can('soj-list-tech'))
				 <li {!! (Request::is('crm_template') || Request::is('crm_template/*') || Request::is('item_template') || Request::is('item_template/*') || Request::is('sales_order_booking') || Request::is('sales_order_booking/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
						<a href="#">
                        <i class="fa fa-fw fa-building-o"></i>
                            <span>Job Order Manager</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
							@can('soj-list') 
                            <li {!! (Request::is('sales_order_booking') || Request::is('sales_order_booking/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_order_booking') }}">
                                    <i class="fa fa-fw fa-circle"></i> Sales Order
                                </a>
                            </li>
							
                            <li {!! (Request::is('item_template') || Request::is('item_template/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('item_template') }}">
                                    <i class="fa fa-fw fa-circle-o"></i> Item Template
                                </a>
                            </li>
							
							<li {!! (Request::is('crm_template') || Request::is('crm_template/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('crm_template') }}">
                                    <i class="fa fa-fw fa-circle-o"></i> CRM Template
                                </a>
                            </li>
							@endcan
							@can('soj-list-tech') 
							<li {!! (Request::is('sales_order_booking/list') || Request::is('sales_order_booking/list/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_order_booking/list') }}">
                                    <i class="fa fa-fw fa-circle"></i> Sales Order List
                                </a>
                            </li>
							 @endcan
							 
							  <li {!! (Request::is('rental_driver') || Request::is('rental_driver/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('rental_driver') }}">
                                   <i class="fa fa-fw fa-truck"></i>Driver
                                </a>
							</li> 
							 
							 <li {!! (Request::is('sales_order_booking/assign') || Request::is('sales_order_booking/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_order_booking/assign') }}">
                                    <i class="fa fa-fw fa-circle-o"></i> Assign Driver
                                </a>
                            </li>
						</ul>
				 </li>
				@endif				
               

	@if(auth()->user()->can('it-group-list') || auth()->user()->can('it-subgroup-list') || auth()->user()->can('it-category-list') || auth()->user()->can('it-subcategory-list') || auth()->user()->can('unit-list') || auth()->user()->can('item-list') || auth()->user()->can('item-enquiry-list') || auth()->user()->can('po-list') || auth()->user()->can('pi-list') || auth()->user()->can('pr-list') || auth()->user()->can('qs-list') || auth()->user()->can('so-list') ||auth()->user()->can('pfi-list') || auth()->user()->can('do-list') || auth()->user()->can('si-list') || auth()->user()->can('sr-list') || auth()->user()->can('gin-list') || auth()->user()->can('gr-list') || auth()->user()->can('jbe-list') || auth()->user()->can('jbo-list') || auth()->user()->can('jbi-list') || auth()->user()->can('loc-tran-list') || auth()->user()->can('stock-trin-list') || auth()->user()->can('stock-trout-list')|| auth()->user()->can('job-order-list')||auth()->user()->can('srl-list'))
                    <li {!! (Request::is('contract') || Request::is('contract/*') || Request::is('group') || Request::is('group/*') || Request::is('subgroup') || Request::is('subgroup/*') ||Request::is('proforma_invoice') || Request::is('proforma_invoice/*') || Request::is('category') || Request::is('category/*') || Request::is('unit') || Request::is('unit/*') || Request::is('subcategory') || Request::is('subcategory/*') || Request::is('itemmaster') || Request::is('itemmaster/*') || Request::is('purchase_order') || Request::is('purchase_order/*') || Request::is('quotation') || Request::is('quotation/*') || Request::is('suppliers_do') || Request::is('suppliers_do/*') || Request::is('purchase_invoice') || Request::is('purchase_invoice/*') || Request::is('purchase_return') || Request::is('purchase_return/*') || Request::is('sales_rental/*') || Request::is('sales_rental') || Request::is('quotation_sales/*') || Request::is('quotation_sales') || Request::is('quotation_rental/*') || Request::is('quotation_rental') || Request::is('sales_order/*') || Request::is('sales_order') ||  Request::is('customers_do') || Request::is('customers_do/*') || Request::is('sales_invoice') || Request::is('sales_invoice/*') || Request::is('sales_return/*') || Request::is('sales_return') || Request::is('itemenquiry') || Request::is('itemenquiry/*') || Request::is('goods_issued') || Request::is('goods_issued/*') || Request::is('goods_return') || Request::is('goods_return/*') || Request::is('job_estimate') || Request::is('job_estimate/*') || Request::is('job_order') || Request::is('job_order/*') || Request::is('job_invoice') || Request::is('job_invoice/*') || Request::is('location_transfer') || Request::is('location_transfer/*') || Request::is('stock_transferin/*') || Request::is('stock_transferin') || Request::is('stock_transferout/*') || Request::is('stock_transferout') || Request::is('customer_enquiry') || Request::is('customer_enquiry/*') || Request::is('production') || Request::is('production/*') || Request::is('manufacture') || Request::is('manufacture/*') || Request::is('material_requisition') || Request::is('material_requisition/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-fw fa-building-o"></i>
                            <span>Inventory</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
							
							@can('it-group-list')
                            <li {!! (Request::is('group') || Request::is('group/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('group') }}">
                                    <i class="fa fa-fw fa-circle"></i> Group
                                </a>
                            </li>
							@endcan
							
							@can('it-subgroup-list')
                            <li {!! (Request::is('subgroup') || Request::is('subgroup/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('subgroup') }}">
                                    <i class="fa fa-fw fa-circle-o"></i> Sub Group
                                </a>
                            </li>
							@endcan
							
							@can('it-category-list')
                            <li {!! (Request::is('category') || Request::is('category/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('category') }}">
                                   <i class="fa fa-fw fa-square"></i> Category
                                </a>
                            </li>
							@endcan
							
							@can('it-subcategory-list')
                            <li {!! (Request::is('subcategory') || Request::is('subcategory/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('subcategory') }}">
                                    <i class="fa fa-fw fa-square-o"></i> Sub Category
                                </a>
                            </li>
							@endcan
							
							@can('unit-list')
							<li {!! (Request::is('unit') || Request::is('unit/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('unit') }}">
                                    <i class="fa fa-fw fa-sort-numeric-asc"></i> Unit
                                </a>
                            </li>
							@endcan
							
							@can('item-list')
							<li {!! (Request::is('itemmaster') || Request::is('itemmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('itemmaster') }}">
                                    <i class="fa fa-fw fa-shopping-cart"></i> Item Master
                                </a>
                            </li>
							@endcan
							
							@can('item-list')
							<li {!! (Request::is('itemenquiry') || Request::is('itemenquiry/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('itemenquiry') }}">
                                    <i class="fa fa-fw fa-question"></i> Item Enquiry 
                                </a>
                            </li>
							@endcan
							
							
                            @can('mr-list')
                            <li {!! (Request::is('material_requisition') || Request::is('material_requisition/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('material_requisition') }}">
                                    <i class="fa fa-fw fa-file-text-o"></i> 
									@php echo (Session::get('pur_enquiry')==1)?'Purchase Enquiry':'Material Requisition'; @endphp
                                </a>
                            </li>
                            @endcan
							
							@can('qp-list')
							<li {!! (Request::is('quotation') || Request::is('quotation/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('quotation') }}">
                                    <i class="fa fa-fw fa-external-link"></i> Quotation  Purchase
                                </a>
                            </li>
                            @endcan
							
                            @can('po-list')
                            <li {!! (Request::is('purchase_order') || Request::is('purchase_order/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('purchase_order') }}">
                                    <i class="fa fa-fw fa-gavel"></i> Purchase Order
                                </a>
                            </li>
                            @endcan
							
							@can('grn-list')
							<li {!! (Request::is('suppliers_do') || Request::is('suppliers_do/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('suppliers_do') }}">
                                    <i class="fa fa-fw fa-truck"></i> Goods Receipt Note
                                </a>
                            </li>
							@endcan
							
							@can('pi-list')
							<li {!! (Request::is('purchase_invoice') || Request::is('purchase_invoice/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('purchase_invoice') }}">
                                    <i class="glyphicon glyphicon-list-alt"></i> Purchase Invoice
                                </a>
                            </li>
							@endcan
							
							@can('pr-list')
							<li {!! (Request::is('purchase_return') || Request::is('purchase_return/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('purchase_return') }}">
                                    <i class="fa fa-fw fa-reply-all"></i> Purchase Return
                                </a>
                            </li>
							@endcan
							
							@can('ce-list')
							<li {!! (Request::is('customer_enquiry') || Request::is('customer_enquiry/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('customer_enquiry') }}">
                                    <i class="fa fa-fw fa-stack-exchange"></i> Customer Enquiry
                                </a>
                            </li>
							@endcan
							
							@can('qs-list')
							<li {!! (Request::is('quotation_sales') || Request::is('quotation_sales/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('quotation_sales') }}">
                                    <i class="fa fa-fw fa-stack-exchange"></i> Quotation Sales
                                </a>
                            </li>
							@endcan
							
                            @can('qrl-list')
							<li {!! (Request::is('quotation_rental') || Request::is('quotation_rental/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('quotation_rental') }}">
                                    <i class="fa fa-fw fa-sort-numeric-asc"></i> Quotation Rental
                                </a>
                            </li>
                            @endcan
							
                            <!-- <li {!! (Request::is('quotation_rental') || Request::is('quotation_rental/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('quotation_rental') }}">
                                    <i class="fa fa-fw fa-sort-numeric-asc"></i> Quotation Rental
                                </a>
                            </li> -->
                            

							@can('so-list')
							<li {!! (Request::is('sales_order') || Request::is('sales_order/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_order') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Sales Order
                                </a>
                            </li>
                           
							@endcan
								@can('so-work')
							 <li {!! (Request::is('sales_order/work_order') || Request::is('sales_order/work_order/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_order/work_order') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Work Order
                                </a>
                            </li>
                            
                            <li {!! (Request::is('sales_order/jobindex') || Request::is('sales_order/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('job_report/jobindex') }}">
                                   <i class="fa fa-fw fa-book"></i> Sales Order View
                                </a>
                            </li>
                            @endcan
								@can('pfi-list')
							<li {!! (Request::is('proforma_invoice') || Request::is('proforma_invoice/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('proforma_invoice') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Proforma Invoice
                                </a>
                            </li>
							@endcan
							
							@can('do-list')
							<li {!! (Request::is('customers_do') || Request::is('customers_do/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('customers_do') }}">
                                    <i class="fa fa-fw fa-gift"></i> 
									@php echo (Session::get('trip_entry')==1)?'Daily Entry':'Delivery Order'; @endphp
                                </a>
                            </li>
							@endcan
														
							@can('si-list')
							<li {!! (Request::is('sales_invoice') || Request::is('sales_invoice/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_invoice') }}">
                                    <i class="fa fa-fw fa-calendar"></i> Sales Invoice
                                </a>
                            </li>
							@endcan
                            @can('pl-list')
                            <li {!! (Request::is('packing_list') || Request::is('packing_list/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('packing_list') }}">
                                    <i class="fa fa-fw fa-calendar"></i> Packing List
                                </a>
                            </li>
                            @endcan
                            @can('srl-list')
                            <li {!! (Request::is('sales_rental') || Request::is('sales_rental/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_rental') }}">
                                    <i class="glyphicon glyphicon-list-alt"></i> Sales Rental
                                </a>
                            </li>
                            @endcan 
							
							<!-- <li {!! (Request::is('sales_rental') || Request::is('sales_rental/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_rental') }}">
                                    <i class="glyphicon glyphicon-list-alt"></i> Sales Rental
                                </a>
                            </li> -->
							
							@can('sr-list')
							<li {!! (Request::is('sales_return') || Request::is('sales_return/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_return') }}">
                                    <i class="fa fa-fw fa-share"></i> Sales Return
                                </a>
                            </li>
							@endcan

                            @can('stock-trin-list')
                            <li {!! (Request::is('stock_transferin') || Request::is('stock_transferin/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('stock_transferin') }}">
                                    <i class="fa fa-fw fa-share"></i> Stock Transfer in
                                </a>
                            </li>
                            @endcan
                            
                            @can('stock-trout-list')
                            <li {!! (Request::is('stock_transferout') || Request::is('stock_transferout/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('stock_transferout') }}">
                                    <i class="fa fa-fw fa-reply"></i> Stock Transfer out
                                </a>
                            </li>
                            @endcan
														
							@can('gin-list')
							<li {!! (Request::is('goods_issued') || Request::is('goods_issued/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('goods_issued') }}">
                                    <i class="fa fa-fw fa-file-text-o"></i> Goods Issued Note
                                </a>
                            </li>
							@endcan
							
							@can('gr-list')
							<li {!! (Request::is('goods_return') || Request::is('goods_return/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('goods_return') }}">
                                    <i class="fa fa-fw fa-sign-in"></i> Goods Return
                                </a>
                            </li>
							@endcan

                            @can('prod-list')
                            <li {!! (Request::is('production') || Request::is('production/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('production') }}">
                                    <i class="fa fa-fw fa-gift"></i> Production Order
                                </a>
                            </li>
                            @endcan

                            @can('mv-list')
                            <li {!! (Request::is('manufacture') || Request::is('manufacture/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('manufacture') }}">
                                    <i class="fa fa-fw fa-lightbulb-o"></i> Manufacture Voucher
                                </a>
                            </li>
                            @endcan
							
							@if(Session::get('mod_jo_to_je')==0)
							
    							@can('job-estimate-list')
    							<li {!! (Request::is('job_estimate') || Request::is('job_estimate/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('job_estimate') }}">
                                        <i class="fa fa-fw fa-stack-exchange"></i> Job Estimate
                                    </a>
                                </li>
    							@endcan
    							
    							@can('job-order-list')
    							<li {!! (Request::is('job_order') || Request::is('job_order/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('job_order') }}">
                                        <i class="fa fa-fw fa-list-alt"></i> Job Order
                                    </a>
                                </li>
    							@endcan
							@else
    							@can('job-order-list')
    							<li {!! (Request::is('job_order') || Request::is('job_order/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('job_order') }}">
                                        <i class="fa fa-fw fa-list-alt"></i> Job Order
                                    </a>
                                </li>
    							@endcan
    							@can('job-estimate-list')
    							<li {!! (Request::is('job_estimate') || Request::is('job_estimate/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('job_estimate') }}">
                                        <i class="fa fa-fw fa-stack-exchange"></i> Job Estimate
                                    </a>
                                </li>
    							@endcan
							
							@endif
							
							
							@can('job-invoice-list')
							<li {!! (Request::is('job_invoice') || Request::is('job_invoice/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('job_invoice') }}">
                                    <i class="fa fa-fw fa-calendar"></i> Job Invoice
                                </a>
                            </li>
							@endcan
							
							@can('loc-tran-list')
							<li {!! (Request::is('location_transfer') || Request::is('location_transfer/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('location_transfer') }}">
                                    <i class="fa fa-fw fa-share-square-o"></i> Location Transfer
                                </a>
                            </li>
							@endcan
							
						
							
                            @can('cm-list')
							<li {!! (Request::is('contract') || Request::is('contract/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('contract') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Contract
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
					@endif
					
					@if(auth()->user()->can('jv-list') || auth()->user()->can('vp-list') || auth()->user()->can('vs-list') || auth()->user()->can('rv-list') || auth()->user()->can('pv-list') || auth()->user()->can('as-list') || auth()->user()->can('pc-list'))
					<li {!! ( Request::is('contra_voucher') || Request::is('contra_voucher/*') || Request::is('credit_note') || Request::is('credit_note/*') || Request::is('customer_receipt') || Request::is('customer_receipt/*') ||Request::is('receipt_voucher') || Request::is('receipt_voucher/*') || Request::is('supplier_payment') || Request::is('supplier_payment/*') || Request::is('payment_voucher') || Request::is('payment_voucher/*') || Request::is('other_receipt') || Request::is('other_receipt/*') || Request::is('supplier_payment') || Request::is('supplier_payment/*') || Request::is('other_payment') || Request::is('other_payment/*') || Request::is('pdc_received') || Request::is('pdc_issued') || Request::is('journal') || Request::is('journal/*') || Request::is('pettycash') || Request::is('pettycash/*') || Request::is('advance_set') || Request::is('advance_set/*') || Request::is('purchase_voucher') || Request::is('purchase_voucher/*') || Request::is('sales_voucher') || Request::is('sales_voucher/*') || Request::is('pdc_received/*') || Request::is('pdc_issued/*')|| Request::is('purchase_split') || Request::is('purchase_split/*') || Request::is('sales_split') || Request::is('sales_split/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-fw fa-retweet"></i>
                            <span>Transaction</span>
                            <span class="fa arrow"></span>
                        </a>
						
						@if(auth()->user()->can('jv-list') || auth()->user()->can('vp-list') || auth()->user()->can('vs-list') || auth()->user()->can('rv-list') || auth()->user()->can('pv-list') || auth()->user()->can('as-list') || auth()->user()->can('pc-list'))
                        <ul class="sub-menu">
							<li {!! (Request::is('contra_voucher') || Request::is('contra_voucher/*') || Request::is('credit_note') || Request::is('credit_note/*') || Request::is('customer_receipt') || Request::is('customer_receipt/*') ||Request::is('receipt_voucher') || Request::is('receipt_voucher/*') || Request::is('other_receipt') || Request::is('other_receipt/*') || Request::is('supplier_payment') || Request::is('supplier_payment/*') || Request::is('payment_voucher') || Request::is('payment_voucher/*') || Request::is('other_payment') || Request::is('other_payment/*') || Request::is('journal') || Request::is('journal/*') || Request::is('pettycash') || Request::is('pettycash/*') || Request::is('advance_set') || Request::is('advance_set/*') || Request::is('purchase_voucher') || Request::is('purchase_voucher/*') || Request::is('sales_voucher') || Request::is('sales_voucher/*')? 'class="active"' : '') !!}>
                                <a href="#">
                                    <i class="fa fa-fw fa-keyboard-o"></i> Vouchers Entry <span class="fa arrow"></span>
                                </a>
								<ul class="sub-menu sub-submenu">
									@can('jv-list')
									<li {!! (Request::is('journal') || Request::is('journal/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('journal') }}">
											<i class="fa fa-fw fa-folder-o"></i> Journal
										</a>
									</li>
									@endcan
										@can('rv-list')
									<li {!! (Request::is('receipt_voucher') || Request::is('receipt_voucher/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('receipt_voucher') }}">
											<i class="fa fa-fw fa-male"></i> Receipt Voucher
										</a>
									</li>
									@endcan
									
										@can('pv-list')
									<li {!! (Request::is('payment_voucher') || Request::is('payment_voucher/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('payment_voucher') }}">
											<i class="fa fa-fw fa-suitcase"></i> Payment Voucher
										</a>
									</li>
									@endcan
									
									@can('vp-list')
									<li {!! (Request::is('purchase_voucher') || Request::is('purchase_voucher/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('purchase_voucher') }}">
											<i class="glyphicon glyphicon-list-alt"></i> Purchase Voucher
										</a>
									</li>
									@endcan
									
									@can('vs-list')
									<li {!! (Request::is('sales_voucher') || Request::is('sales_voucher/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('sales_voucher') }}">
											<i class="fa fa-fw fa-calendar"></i>  Sales Voucher
										</a>
									</li>
									@endcan
									
									@can('rv-list')
									<li {!! (Request::is('customer_receipt') || Request::is('customer_receipt/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('customer_receipt') }}">
											<i class="fa fa-fw fa-male"></i> Customer Receipt
										</a>
									</li>
									@endcan
									<!--<li {!! (Request::is('other_receipt') || Request::is('other_receipt/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('other_receipt') }}">
											<i class="fa fa-fw fa-qrcode"></i> Other Receipt
										</a>
									</li>-->
									
									@can('pv-list')
									<li {!! (Request::is('supplier_payment') || Request::is('supplier_payment/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('supplier_payment') }}">
											<i class="fa fa-fw fa-suitcase"></i> Supplier Payment
										</a>
									</li>
									@endcan
                                    	@can('cv-list')
                                    <li {!! (Request::is('contra_voucher') || Request::is('contra_voucher/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('contra_voucher') }}">
											<i class="fa fa-fw fa-suitcase"></i> Contra Voucher
										</a>
									</li>
                                     @endcan
                                    @can('prc-list')
									<li {!! (Request::is('cheque_details') || Request::is('cheque_details/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('cheque_details') }}">
											<i class="fa fa-fw fa-suitcase"></i> Print Cheque
										</a>
									</li>
									@endcan
									<!-- <li {!! (Request::is('cheque_details') || Request::is('cheque_details/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('cheque_details') }}">
											<i class="fa fa-fw fa-suitcase"></i> Print Cheque
										</a>
									</li> -->
									<!--<li {!! (Request::is('other_payment') || Request::is('other_payment/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('other_payment') }}">
											<i class="fa fa-fw fa-money"></i> Other Payment
										</a>
									</li>-->
									@can('as-list')
									<li {!! (Request::is('advance_set') || Request::is('advance_set/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('advance_set/add') }}">
											<i class="fa fa-fw fa-eraser"></i> Advance Set Off
										</a>
									</li>
									@endcan
									
									@can('pc-list')
									<li {!! (Request::is('pettycash') || Request::is('pettycash/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('pettycash') }}">
											<i class="fa fa-fw fa-money"></i> Petty Cash
										</a>
									</li>
									@endcan
									
									@can('cn-list')
									<li {!! (Request::is('credit_note') || Request::is('credit_note/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('credit_note') }}">
											<i class="fa fa-fw fa-money"></i> Credit Note
										</a>
									</li>
									@endcan
									
									@can('dn-list')
									<li {!! (Request::is('debit_note') || Request::is('debit_note/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('debit_note') }}">
											<i class="fa fa-fw fa-money"></i> Debit Note
										</a>
									</li>
									@endcan
									
								  @can('ps-list')
							     <li {!! (Request::is('purchase_split') || Request::is('purchase_split/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('purchase_split') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Purchase Split
                                </a>
                                </li>
                                <li {!! (Request::is('purchase_split') || Request::is('purchase_split/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('purchase_split_return') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Purchase Split Return
                                </a>
                                </li>
							      @endcan
							
							    @can('ss-list')
							   <li {!! (Request::is('sales_split') || Request::is('sales_split/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_split') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Sales Split
                                </a>
                               </li>
                                <li {!! (Request::is('sales_split') || Request::is('sales_split/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_split_return') }}">
                                    <i class="fa fa-fw fa-list-alt"></i> Sales Split Return
                                </a>
                               </li>
							   @endcan

                                    @can('mjv-list')
                                    <li {!! (Request::is('manual_journal') || Request::is('manual_journal/*') ? 'class="active"' : '') !!}>
										<a href="{{ URL::to('manual_journal') }}">
                                            <i class="fa fa-fw fa-folder-o"></i> Manual Journal
										</a>
									</li>
                                    @endcan
								</ul>
                            </li>
                        </ul>
						@endif
						
						@if(auth()->user()->can('pdr-list') || auth()->user()->can('pdi-list'))
						<ul class="sub-menu">
							<li {!! (Request::is('pdc_received') || Request::is('pdc_issued') || Request::is('pdc_received/*') || Request::is('pdc_issued/*') ? 'class="active"' : '') !!}>
                                <a href="#">
                                    <i class="fa fa-fw fa-envelope"></i> Post Dated Cheque <span class="fa arrow"></span>
                                </a>
								<ul class="sub-menu sub-submenu">
									@can('pdr-list')
									<li {!! (Request::is('pdc_received') || Request::is('pdc_received/*')? 'class="active"' : '') !!}>
										<a href="{{ URL::to('pdc_received') }}">
											<i class="glyphicon glyphicon-save"></i> PDC Received
										</a>
									</li>
									@endcan
									
									@can('pdi-list')
									<li {!! (Request::is('pdc_issued') || Request::is('pdc_issued/*')? 'class="active"' : '') !!}>
										<a href="{{ URL::to('pdc_issued') }}">
											<i class="glyphicon glyphicon-open"></i> PDC Issued
										</a>
									</li>
									@endcan
								</ul>
							</li>
						</ul>
						@endif
                    </li>
					@endif
					
					@if(auth()->user()->can('ms-view'))
					<li {!! (Request::is('ms_workenquiry') || Request::is('ms_workenquiry/*') || Request::is('ms_reports') || Request::is('ms_reports/*') || Request::is('ms_workorder') || Request::is('ms_workorder/*') || Request::is('ms_jobmaster') || Request::is('ms_jobmaster/*') ||  Request::is('ms_worktype') || Request::is('ms_worktype/*') || Request::is('ms_technician') || Request::is('ms_technician/*') || Request::is('ms_area') || Request::is('ms_area/*') || Request::is('ms_customer') || Request::is('ms_customer/*') || Request::is('ms_location') || Request::is('ms_location/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-fw fa-cogs"></i>
                            <span>Maintenance System</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
							@can('ms-customer')
							<li {!! (Request::is('ms_customer') || Request::is('ms_customer/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_customer') }}">
                                    <i class="fa fa-fw fa-users"></i> Customers
                                </a>
                            </li>
							@endcan
							
							@can('ms-area')
							<li {!! (Request::is('ms_area') || Request::is('ms_area/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_area') }}">
                                    <i class="fa fa-fw fa-map-marker"></i> Area
                                </a>
                            </li>
							@endcan
							
							<!--<li {!! (Request::is('ms_location') || Request::is('ms_location/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_location') }}">
                                    <i class="fa fa-fw fa-crosshairs"></i> Location
                                </a>
                            </li>-->
							
							@can('ms-technician')
							<li {!! (Request::is('ms_technician') || Request::is('ms_technician/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_technician') }}">
                                    <i class="fa fa-fw fa-male"></i> Technician
                                </a>
                            </li>
							@endcan
							
							@can('ms-worktype')
							<li {!! (Request::is('ms_worktype') || Request::is('ms_worktype/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_worktype') }}">
                                    <i class="fa fa-fw fa-magic"></i> Work Type
                                </a>
                            </li>
							@endcan
							
							@can('ms-projects')
							<li {!! (Request::is('ms_jobmaster') || Request::is('ms_jobmaster/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_jobmaster') }}">
                                    <i class="fa fa-fw fa-tasks"></i> Projects
                                </a>
                            </li>
							@endcan
							
							@can('ms-enquiry')
							<li {!! (Request::is('ms_workenquiry') || Request::is('ms_workenquiry/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_workenquiry') }}">
                                    <i class="fa fa-fw fa-tag"></i> Work Enquiry
                                </a>
                            </li>
							@endcan
							
							@can('ms-order')
							<li {!! (Request::is('ms_workorder') || Request::is('ms_workorder/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_workorder') }}">
                                    <i class="fa fa-fw fa-tags"></i> Work Order
                                </a>
                            </li>
							@endcan
							
							@can('ms-reports')
							<li {!! (Request::is('ms_reports') || Request::is('ms_reports/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ms_reports') }}">
                                    <i class="fa fa-fw fa-sun-o"></i> Reports
                                </a>
                            </li>
							@endcan
						</ul>
					</li>
					@endif
					
					@if(auth()->user()->can('trial-bal') || auth()->user()->can('p-and-l') || auth()->user()->can('b-sheet') || auth()->user()->can('vchr-report') || auth()->user()->can('qty-report') || auth()->user()->can('stock-ldgr') || auth()->user()->can('profit-anl') || auth()->user()->can('vat-report') || auth()->user()->can('job-report') || auth()->user()->can('ledger-mnt'))
					<li {!! ( Request::is('batch_report') || Request::is('batch_report/*') || Request::is('account_reports') || Request::is('account_reports/*') || Request::is('daily_report') || Request::is('daily_report/*') || Request::is('voucherwise_report') || Request::is('voucherwise_report/*') || Request::is('trial_balance2') || Request::is('profit_loss2') || Request::is('profit_loss2/*') || Request::is('balancesheet2') || Request::is('trial_balance2/*') || Request::is('balancesheet2/*') || Request::is('purchase_report') || Request::is('purchase_report/*') || Request::is('sales_report') || Request::is('sales_report/*') || Request::is('quantity_report') || Request::is('quantity_report/*') || Request::is('stock_ledger') || Request::is('stock_ledger/*') || Request::is('profit_analysis') || Request::is('profit_analysis/*') || Request::is('vat_report') || Request::is('vat_report/*') || Request::is('job_report') || Request::is('job_report/*') || Request::is('ledger_moments') || Request::is('ledger_moments/*') || Request::is('pdc_report') || Request::is('pdc_report/*')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="glyphicon glyphicon-folder-close"></i>
                            <span>Reports</span>
                            <span class="fa arrow"></span>
                        </a>
						<ul class="sub-menu">
							@can('vchr-report')
							<li {!! (Request::is('voucherwise_report') || Request::is('voucherwise_report/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucherwise_report') }}">
                                    <i class="glyphicon glyphicon-align-justify"></i> Voucherwise Report 
                                </a>
                            </li>
							@endcan
							
							@can('stmnt')
							<li {!! (Request::is('account_enquiry') || Request::is('account_enquiry/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('account_enquiry') }}">
                                    <i class="fa fa-fw fa-question-circle"></i> Account Statement 
                                </a>
                            </li>
							@endcan
							
						
							
							@can('trial-bal')
							<li {!! (Request::is('trial_balance2') || Request::is('trial_balance2/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('trial_balance2') }}">
                                   <i class="fa fa-fw fa-compass"></i> Trial Balance 
                                </a>
                            </li>
							@endcan
							
							@can('p-and-l')
							<li {!! (Request::is('profit_loss2') || Request::is('profit_loss2/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('profit_loss2') }}">
                                   <i class="fa fa-fw fa-folder-o"></i> Profit & Loss 
                                </a>
                            </li>
							@endcan
							
							@can('b-sheet')
							<li {!! (Request::is('balancesheet2') || Request::is('balancesheet2/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('balancesheet2') }}">
                                   <i class="fa fa-fw fa-film"></i> Balance Sheet 
                                </a>
                            </li>
							@endcan
							
							@can('purchase-report')
							<li {!! (Request::is('purchase_report') || Request::is('purchase_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('purchase_report') }}">
                                   <i class="fa fa-fw fa-folder-open-o"></i> Purchase Report
                                </a>
                            </li>
							@endcan
							
							@can('sales-report')
							<li {!! (Request::is('sales_report') || Request::is('sales_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sales_report') }}">
                                   <i class="fa fa-fw fa-hdd-o"></i> Sales Report
                                </a>
                            </li>
							@endcan
							
							@can('qty-report')
							<li {!! (Request::is('quantity_report') || Request::is('quantity_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('quantity_report') }}">
                                   <i class="fa fa-fw fa-flask"></i> Quantity Report
                                </a>
                            </li>
							@endcan
							
							@can('stock-ldgr')
							<li {!! (Request::is('stock_ledger') || Request::is('stock_ledger/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('stock_ledger') }}">
                                   <i class="fa fa-fw fa-magnet"></i> Stock Ledger
                                </a>
                            </li>
							@endcan
							
							@can('itembatch-report')
							<li {!! (Request::is('batch_report') || Request::is('batch_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('batch_report') }}">
                                   <i class="fa fa-fw fa-magnet"></i> Item Batch
                                </a>
                            </li>
                            @endcan
                            
							@can('stock-ldgr')
							<li {!! (Request::is('stock_transaction') || Request::is('stock_transaction/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('stock_transaction') }}">
                                   <i class="fa fa-fw fa-magnet"></i> Stock Transaction
                                </a>
                            </li>
							@endcan
							
							@can('stock-ldgr')
							<li {!! (Request::is('stock_movement') || Request::is('stock_movement/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('stock_movement') }}">
                                   <i class="fa fa-fw fa-magnet"></i> Stock Movement
                                </a>
                            </li>
							@endcan
							
							@can('profit-anl')
							<li {!! (Request::is('profit_analysis') || Request::is('profit_analysis/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('profit_analysis') }}">
                                   <i class="fa fa-fw fa-lemon-o"></i> Profit Analysis
                                </a>
                            </li>
							@endcan
							
								@can('cash-in-hand')
							<li {!! (Request::is('cash_inhand') || Request::is('cash_inhand/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('cash_inhand') }}">
                                   <i class="fa fa-fw fa-compass"></i> Cash In Hand 
                                </a>
                            </li>
							@endcan
							
							@can('vat-report')
							<li {!! (Request::is('vat_report') || Request::is('vat_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('vat_report') }}">
                                   <i class="fa fa-fw fa-magic"></i> Vat Report
                                </a>
                            </li>
							@endcan

							@can('daily-report')
							<li {!! (Request::is('daily_report') || Request::is('daily_report/*') ? 'class="active"' : '') !!}>
								<a href="{{ URL::to('daily_report') }}">
								<i class="glyphicon glyphicon-list-alt"></i> Daily Report
								</a>
							</li>
							@endcan

							@can('job-report')
							<li {!! (Request::is('job_report') || Request::is('job_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('job_report') }}">
                                   <i class="fa fa-fw fa-book"></i> Job Report
                                </a>
                            </li>
                            @endcan
                            
                            @can('vehwise-report')
                            <li {!! (Request::is('job_report/vehicleindex') || Request::is('job_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('job_report/vehicleindex') }}">
                                   <i class="fa fa-fw fa-book"></i> Vehicle wise Report
                                </a>
                            </li>
							@endcan
							
							@can('ledger-mnt')
							<li {!! (Request::is('ledger_moments') || Request::is('ledger_moments/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('ledger_moments') }}">
                                   <i class="fa fa-fw fa-hdd-o"></i> Ledger Moments
                                </a>
                            </li>
							@endcan
							
							@can('custsup-report')
							<li {!! (Request::is('account_reports') || Request::is('account_reports/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('account_reports') }}">
                                   <i class="fa fa-fw fa-hdd-o"></i> Customer/Supplierwise
                                </a>
                            </li>
							@endcan
							
							@can('trans-report')
							<li {!! (Request::is('transaction_list') || Request::is('transaction_list/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('transaction_list') }}">
                                   <i class="fa fa-fw fa-hdd-o"></i> Transaction List
                                </a>
                            </li>
							@endcan
							
							@can('pdc-report')
							<li {!! (Request::is('pdc_report') || Request::is('pdc_report/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('pdc_report') }}">
                                   <i class="fa fa-fw fa-compass"></i> PDC Report 
                                </a>
                            </li>
							@endcan
				             @can('jobwiseorder-report')
							<li {!! (Request::is('jobprocess_report') || Request::is('jobprocess_report/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('jobprocess_report') }}">
                                   <i class="fa fa-fw fa-compass"></i> Jobwise Order Processing 
                                </a>
                            </li>
							@endcan
							<!--<li {!! (Request::is('document_report') || Request::is('document_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('document_report') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i> Document Report
                                </a>
                            </li>
							
							<li {!! (Request::is('voucherwise_report/pisi_report') || Request::is('voucherwise_report/pisi_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucherwise_report/pisi_report') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i> PC & SI Report
                                </a>
                            </li>
							
							<li {!! (Request::is('voucherwise_report/pisi_jobwise') || Request::is('voucherwise_report/pisi_jobwise/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucherwise_report/pisi_jobwise') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i> PC & SI Jobwise Report
                                </a>
                            </li>
							
							<li {!! (Request::is('voucherwise_report/pisirtn_report') || Request::is('voucherwise_report/pisirtn_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucherwise_report/pisirtn_report') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i> PC & SI Retention Report
                                </a>
                            </li>
							
							<li {!! (Request::is('voucherwise_report/pisirtn_jobwise') || Request::is('voucherwise_report/pisirtn_jobwise/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucherwise_report/pisirtn_jobwise') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i> PC & SI Jobwise Retention Report
                                </a>
                            </li>
							
							<li {!! (Request::is('voucherwise_report/pisirv_report') || Request::is('voucherwise_report/pisirv_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucherwise_report/pisirv_report') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i> PC & SI RV Report
                                </a>
                            </li>
							
							<li {!! (Request::is('voucherwise_report/pisirv_jobwise') || Request::is('voucherwise_report/pisirv_jobwise/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucherwise_report/pisirv_jobwise') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i> PC & SI Jobwise RV Report
                                </a>
                            </li>
							
							<li {!! (Request::is('voucherwise_report/pisi_summary') || Request::is('voucherwise_report/pisi_summary/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucherwise_report/pisi_summary') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i> PC & SI Summary Report
                                </a>
                            </li>
							-->
							
						</ul>
					</li>
					@endif
@if(auth()->user()->can('company-view') || auth()->user()->can('ac-setting-list') || auth()->user()->can('oac-setting-update') || auth()->user()->can('voucher-number') || auth()->user()->can('sys-parameter') || auth()->user()->can('utility') || auth()->user()->can('log-details') || auth()->user()->can('backup') || auth()->user()->can('year-ending'))
					<li {!! ( Request::is('company') || Request::is('sysparameter') || Request::is('account_setting') || Request::is('account_setting/*') || Request::is('utilities') || Request::is('roles') || Request::is('roles/*') || Request::is('logdetails') || Request::is('logdetails/*') || Request::is('backup') || Request::is('other_account_setting') || Request::is('voucher_numbers') || Request::is('year_ending') || Request::is('design') || Request::is('design/*') || Request::is('set_report')? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-fw fa-shield"></i>
                            <span>Administration</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
							
							@can('rolem-list')
							<li {!! (Request::is('roles') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('roles') }}">
                                    <i class="fa fa-fw fa-key"></i> Role Management
                                </a>
                            </li>
							@endcan
							
							 @can('company-view') 
							<li {!! (Request::is('company') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('company') }}">
                                    <i class="glyphicon glyphicon-subtitles"></i> Company
                                </a>
                            </li>
						@endcan 
							
							@can('ac-setting-list')
							<li {!! (Request::is('account_setting') || Request::is('account_setting/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('account_setting') }}">
                                    <i class="fa fa-fw fa-certificate"></i> Account Settings
                                </a>
                            </li>
							@endcan
							
							@can('oac-setting-update')
							<li {!! (Request::is('other_account_setting')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('other_account_setting') }}">
                                    <i class="fa fa-fw fa-bullseye"></i> Other Account Settings
                                </a>
                            </li>
							@endcan

                                @can('daily-report-setting')
                                <li {!! (Request::is('daily_report_setting')? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('daily_report_setting') }}">
                                        <i class="fa fa-fw fa-bullseye"></i> Daily Report Settings
                                    </a>
                                </li>
                                @endcan

							@can('voucher-number')
							<li {!! (Request::is('voucher_numbers')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('voucher_numbers') }}">
                                   <i class="fa fa-fw fa-dot-circle-o"></i> Voucher Numbers
                                </a>
                            </li>
							@endcan
							
							@can('sys-parameter')
                            <li {!! (Request::is('sysparameter') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sysparameter') }}">
                                    <i class="fa fa-fw fa-laptop"></i> System Parameters
                                </a>
                            </li>
							@endcan
							
							@can('utility')
							<li {!! (Request::is('utilities') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('utilities') }}">
                                    <i class="fa fa-fw fa-gavel"></i> Utilities
                                </a>
                            </li>
							<li {!! (Request::is('tools') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('tools') }}">
                                    <i class="fa fa-fw fa-gavel"></i> Tools
                                </a>
                            </li>
							@endcan
							
							@can('log-details')
							<li {!! (Request::is('logdetails') || Request::is('logdetails/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('logdetails') }}">
                                    <i class="fa fa-fw fa-desktop"></i> Log Details
                                </a>
                            </li>
							@endcan
							
							@can('backup')
							<li {!! (Request::is('backup') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('backup') }}">
                                    <i class="fa fa-fw fa-floppy-o"></i> Backup
                                </a>
                            </li>
							@endcan
							
							@can('year-ending')
							<li {!! (Request::is('year_ending') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('year_ending') }}">
                                    <i class="fa fa-fw fa-anchor"></i> Year Ending Wizard
                                </a>
                            </li>
							@endcan
							
							@can('entry-form')
							<li {!! (Request::is('forms') || Request::is('forms/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('forms') }}">
                                    <i class="fa fa-fw fa-columns"></i> Entry Forms
                                </a>
                            </li>
							@endcan
							
							@can('design-report')
                            @php $stimulsoft_v = config('app.stimulsoft_ver'); @endphp
                            @if($stimulsoft_v==2)
							<li {!! (Request::is('design') || Request::is('design/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('designer') }}" target="_blank">
                                    <i class="fa fa-fw fa-film"></i> Design Report
                                </a>
                            </li>
                            @else
                            <li {!! (Request::is('design') || Request::is('design/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('design') }}" target="_blank">
                                    <i class="fa fa-fw fa-film"></i> Design Report
                                </a>
                            </li>
                            @endif
							@endcan
							
							@can('set-report')
							<li {!! (Request::is('set_report') || Request::is('set_report/*')? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('set_report') }}">
                                    <i class="fa fa-fw fa-crosshairs"></i> Set Report
                                </a>
                            </li>
							@endcan
							
                        </ul>
                    </li>
                    @endif
					
					
					@if(auth()->user()->can('emp-list') || auth()->user()->can('doc-list') || auth()->user()->can('ast-list'))
					<li {!! ( Request::is('document_master') || Request::is('document_master/*') || Request::is('employee') ||Request::is('division/*') || Request::is('employee/*') || Request::is('assets_issued') || Request::is('assets_issued/*') || Request::is('document_report/*') || Request::is('employee_document') || Request::is('employee_document/*') || Request::is('employee_report') || Request::is('employee_report/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="glyphicon glyphicon-tower"></i> 
                            <span>HR Management</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">

                            @can('dm-list')
                            <li {!! (Request::is('division') || Request::is('division/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('division') }}">
                                    <i class="fa fa-university"></i> Division Master
                                </a>
                            </li>
                            @endcan

							@can('emp-list')
							<li {!! (Request::is('emp_category') || Request::is('emp_category/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('emp_category') }}">
                                    <i class="fa fa-fw fa-users"></i> Employee Category
                                </a>
                            </li>
							
							<li {!! (Request::is('employee') || Request::is('employee/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('employee') }}">
                                    <i class="fa fa-fw fa-users"></i> Employee
                                </a>
                            </li>
							@endcan
							
							@can('edoc-list')
							<li {!! (Request::is('employee_document') || Request::is('employee_document/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('employee_document') }}">
                                   <i class="fa fa-fw fa-envelope"></i> Employee Document 
                                </a>
                            </li>
							@endcan
							
							@can('emp-report')
							<li {!! (Request::is('employee_report') || Request::is('employee_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('employee_report') }}">
                                    <i class="glyphicon glyphicon-th"></i> Employee Report 
                                </a>
                            </li>
							@endcan
							
							@can('doc-report')
							<li {!! (Request::is('document_report/search_form') || Request::is('document_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('document_report/search_form') }}">
                                   <i class="glyphicon glyphicon-list-alt"></i>  Document Report 
                                </a>
                            </li>
							@endcan
							
							@can('doc-list')
							<li {!! (Request::is('document_master') || Request::is('document_master/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('document_master') }}">
                                   <i class="glyphicon glyphicon-file"></i> Document Master 
                                </a>
                            </li>
							@endcan
							
							@can('ast-list')
							<li {!! (Request::is('assets_issued') || Request::is('assets_issued/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('assets_issued') }}">
                                   <i class="fa fa-fw fa-tablet"></i> Assets Issued 
                                </a>
                            </li>
							@endcan
                        </ul>
                    </li>
					@endif
					
					
					@if(auth()->user()->can('wage-list') || auth()->user()->can('pay-roll-report') || auth()->user()->can('pay-slip'))
					<li {!! ( Request::is('wage_entry') || Request::is('wage_entry/*') || Request::is('payroll_report') || Request::is('payroll_report/*') || Request::is('emp_report') || Request::is('emp_report/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="fa fa-fw fa-money"></i>
                            <span>Payroll</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            
                            @can('timesheet-entry')
                            	<li {!! (Request::is('wage_entry/timesheet') || Request::is('wage_entry/timesheet/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('wage_entry/timesheet') }}">
                                  <i class="fa fa-fw fa-keyboard-o"></i> Time Sheet Entry
                                </a>
                            </li>
                            	@endcan
                            	@can('timesheet_edit')
                            <li {!! (Request::is('wage_entry/timesheet/edit') || Request::is('wage_entry/timesheet/edit/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('wage_entry/timesheet/edit') }}">
                                  <i class="fa fa-fw fa-keyboard-o"></i> Time Sheet Edit
                                </a>
                            </li>
                            	@endcan
                            @can('timesheet_view')
                            <li {!! (Request::is('wage_entry/timesheet/view') || Request::is('wage_entry/timesheet/view/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('wage_entry/timesheet/view') }}">
                                  <i class="fa fa-fw fa-keyboard-o"></i> Time Sheet View
                                </a>
                            </li>
                            	@endcan
                            @can('leave_entry')	
                            <li {!! (Request::is('wage_entry/timesheet/leave') || Request::is('wage_entry/timesheet/leave/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('wage_entry/timesheet/leave') }}">
                                  <i class="fa fa-fw fa-keyboard-o"></i> Leave Entry
                                </a>
                            </li>
								@endcan
							@can('wage-list')
							<li {!! (Request::is('wage_entry') || Request::is('wage_entry/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('wage_entry') }}">
                                  <i class="fa fa-fw fa-keyboard-o"></i> Wage Entry
                                </a>
                            </li>
							@endcan
							
							@can('timesheet_report')
							<li {!! (Request::is('timesheet_report/') || Request::is('timesheet_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('timesheet_report') }}">
                                   <i class="glyphicon glyphicon-compressed"></i> Timesheet Report 
                                </a>
                            </li>
                            @endcan
                            @can('timesheet-payroll')
                            <li {!! (Request::is('timesheet_report/payroll') || Request::is('timesheet_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('timesheet_report/payroll') }}">
                                   <i class="glyphicon glyphicon-compressed"></i> Timesheet Payroll Report 
                                </a>
                            </li>
							@endcan
							@can('pay-slip')
							<li {!! (Request::is('pay_slip') || Request::is('pay_slip/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('pay_slip') }}">
                                   <i class="glyphicon glyphicon-floppy-saved"></i> Pay Slip 
                                </a>
                            </li>
							@endcan
							
							@can('job-report-prol')
							<li {!! (Request::is('payroll_report/job') || Request::is('payroll_report/*') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('payroll_report/job') }}">
                                   <i class="glyphicon glyphicon-compressed"></i> Job Report 
                                </a>
                            </li>
							@endcan
							
                                @can('pay-roll-report')
                                <li {!! (Request::is('payroll_report') || Request::is('payroll_report/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('payroll_report') }}">
                                    <i class="glyphicon glyphicon-book"></i> Payroll Report 
                                    </a>
                                </li>
                                @endcan

                                @can('wps-report-prol')
                                <li {!! (Request::is('emp_report') || Request::is('emp_report/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ URL::to('emp_report') }}">
                                    <i class="glyphicon glyphicon-book"></i> WPS Report 
                                    </a>
                                </li>
                                @endcan

                        </ul>
                    </li>
					@endif
					
					@if(auth()->user()->can('user-list'))
					<li {!! ( Request::is('users') || Request::is('users/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="glyphicon glyphicon-user"></i> 
                            <span>User Management</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
							@can('user-list')
							<li {!! (Request::is('users') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('users') }}">
                                    <i class="fa fa-fw fa-group"></i> Users
                                </a>
                            </li>
							@endcan
                        </ul>
                    </li>
                   @endif
				   
					@if(auth()->user()->can('data-backup') || auth()->user()->can('data-remove') || auth()->user()->can('items') || auth()->user()->can('cust-sup'))
					<li {!! ( Request::is('backup') || Request::is('data-remove') || Request::is('importdata/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                        <a href="#">
                        <i class="glyphicon glyphicon-hdd"></i> 
                            <span>Data Management</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
							
							@can('data-backup')
							<li {!! (Request::is('backup') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('backup') }}">
                                    <i class="fa fa-fw fa-floppy-o"></i> Data Backup
                                </a>
                            </li>
							@endcan
							
							@can('data-remove')
							<li {!! (Request::is('data_remove') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('data_remove') }}">
                                    <i class="glyphicon glyphicon-log-out"></i> Data Remove 
                                </a>
                            </li>
							@endcan
							
							@can('items')
							<li {!! (Request::is('importdata/items') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/items') }}">
                                    <i class="fa fa-fw fa-glass"></i> Items 
                                </a>
                            </li>
                            
                            <li {!! (Request::is('importdata/tallyitems') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/tallyitems') }}">
                                    <i class="fa fa-fw fa-glass"></i> Physical Stock Update 
                                </a>
                            </li>
							@endcan
							
							@can('cust-sup')
							<li {!! (Request::is('importdata/accounts_master') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/accounts_master') }}">
                                    <i class="fa fa-fw fa-users"></i> Accounts
                                </a>
                            </li>
							<li {!! (Request::is('importdata/accounts') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/accounts') }}">
                                    <i class="fa fa-fw fa-users"></i> Customer/Supplier
                                </a>
                            </li>
							<li {!! (Request::is('importdata/opn-balance') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/opn-balance') }}">
                                    <i class="fa fa-fw fa-users"></i> Opening Balance Cust
                                </a>
                            </li>
                            <li {!! (Request::is('importdata/opn-balance-sup') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/opn-balance-sup') }}">
                                    <i class="fa fa-fw fa-users"></i> Opening Balance Sup
                                </a>
                            </li>
                            <li {!! (Request::is('importdata/cust-vehicle') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/cust-vehicle') }}">
                                    <i class="fa fa-fw fa-users"></i> Customer Vehicle
                                </a>
                            </li>
                            <li {!! (Request::is('importdata/jobmaster') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/jobmaster') }}">
                                    <i class="fa fa-fw fa-users"></i> Jobmaster
                                </a>
                            </li>
                            <li {!! (Request::is('importdata/joborder') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('importdata/joborder') }}">
                                    <i class="fa fa-fw fa-users"></i> JobOrder
                                </a>
                            </li>
							@endcan
                        </ul>
                    </li>
                   @endif
					
                </ul>
                <!-- / .navigation -->
            </div>
            <!-- menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
    <aside class="right-side">


        <!-- Content -->
        @yield('content')

    </aside>
    <!-- page wrapper-->
</div>
<!-- wrapper-->
<!-- global js -->
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>

</html>
