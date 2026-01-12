<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 360 - ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!--<link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>-->
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
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <img src="{{asset('assets/img/logo.png')}}" alt="logo"/>
        </a>
        <!-- Header Navbar: style can be found in header-->
        <!-- Sidebar toggle button-->
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button"> <i
                        class="fa fa-fw fa-bars"></i>
            </a>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i
                                class="fa fa-fw fa-envelope-o black"></i>
                        <span class="label label-success">2</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages table-striped">
                        <li class="dropdown-title">New Messages</li>
                        <li>
                            <a href="" class="message striped-col">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar7.jpg')}}"
                                     alt="avatar-image">

                                <div class="message-body"><strong>Ernest Kerry</strong>
                                    <br>
                                    Can we Meet?
                                    <br>
                                    <small>Just Now</small>
                                    <span class="label label-success label-mini msg-lable">New</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar6.jpg')}}"
                                     alt="avatar-image">

                                <div class="message-body"><strong>John</strong>
                                    <br>
                                    Dont forgot to call...
                                    <br>
                                    <small>5 minutes ago</small>
                                    <span class="label label-success label-mini msg-lable">New</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message striped-col">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar5.jpg')}}"
                                     alt="avatar-image">

                                <div class="message-body">
                                    <strong>Wilton Zeph</strong>
                                    <br>
                                    If there is anything else &hellip;
                                    <br>
                                    <small>14/10/2014 1:31 pm</small>
                                </div>

                            </a>
                        </li>
                        <li>
                            <a href="" class="message">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar1.jpg')}}"
                                     alt="avatar-image">
                                <div class="message-body">
                                    <strong>Jenny Kerry</strong>
                                    <br>
                                    Let me know when you free
                                    <br>
                                    <small>5 days ago</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message striped-col">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar.jpg')}}"
                                     alt="avatar-image">
                                <div class="message-body">
                                    <strong>Tony</strong>
                                    <br>
                                    Let me know when you free
                                    <br>
                                    <small>5 days ago</small>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer"><a href="#">View All messages</a></li>
                    </ul>

                </li>
                <!--tasks-->
                <li class="dropdown tasks-menu hidden-xs">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-fw fa-edit black"></i>
                        <span class="label label-primary">4</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li class="dropdown-title">You Have 4 Tasks</li>
                        <li>
                            <a href="" class="message striped-col">
                                Design some buttons
                                <small class="pull-right">20%</small>
                                <div class="message-body">
                                    <div class="progress progress-xs progress_task">
                                        <div class="progress-bar progress-bar-primary" style="width: 20%"
                                             role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                             aria-valuemax="100">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message">
                                Create a nice theme
                                <small class="pull-right">40%</small>
                                <div class="message-body">
                                    <div class="progress progress-xs progress_task">
                                        <div class="progress-bar progress-bar-success" style="width: 40%"
                                             role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                             aria-valuemax="100">
                                            <span class="sr-only">40% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message striped-col">
                                Some task I need to do
                                <small class="pull-right">60%</small>
                                <div class="message-body">
                                    <div class="progress progress-xs progress_task">
                                        <div class="progress-bar progress-bar-danger" style="width: 60%"
                                             role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                             aria-valuemax="100">
                                            <span class="sr-only">60% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message">
                                Make beautiful transitions
                                <small class="pull-right">80%</small>
                                <div class="message-body">
                                    <div class="progress progress-xs progress_task">
                                        <div class="progress-bar progress-bar-warning" style="width: 80%"
                                             role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                             aria-valuemax="100">
                                            <span class="sr-only">80% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer"><a href="#"> View All Tasks</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle toggle-right">
                        <i class="fa fa-fw fa-comments-o black"></i>
                        <span class="label label-danger">9</span>
                    </a>
                </li>
                <!-- Notifications: style can be found in dropdown-->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-fw fa-bell-o black"></i>
                        <span class="label label-warning">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li class="dropdown-title">You have 8 notifications</li>

                        <li>
                            <a href="" class="message icon-not striped-col">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar3.jpg')}}"
                                     alt="avatar-image">

                                <div class="message-body">
                                    <strong>John Doe</strong>
                                    <br>
                                    5 members joined today
                                    <br>
                                    <span class="noti-date">Just now</span>
                                </div>

                            </a>
                        </li>
                        <li>
                            <a href="" class="message icon-not">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar.jpg')}}"
                                     alt="avatar-image">
                                <div class="message-body">
                                    <strong>Tony</strong>
                                    <br>
                                    likes a photo of you
                                    <br>
                                    <span class="noti-date">5 min</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message icon-not striped-col">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar6.jpg')}}"
                                     alt="avatar-image">

                                <div class="message-body">
                                    <strong>John</strong>
                                    <br>
                                    Dont forgot to call...
                                    <br>
                                    <span class="noti-date">11 min</span>

                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message icon-not">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar1.jpg')}}"
                                     alt="avatar-image">
                                <div class="message-body">
                                    <strong>Jenny Kerry</strong>
                                    <br>
                                    Very long description here...
                                    <br>
                                    <span class="noti-date">1 Hour</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="message icon-not striped-col">
                                <img class="message-image img-circle" src="{{asset('assets/img/authors/avatar7.jpg')}}"
                                     alt="avatar-image">

                                <div class="message-body">
                                    <strong>Ernest Kerry</strong>
                                    <br>
                                    2 members joined today
                                    <br>
                                    <span class="noti-date">3 Days</span>

                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer"><a href="#"> View All Notifications</a></li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown-->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle padding-user" data-toggle="dropdown">
                        <img src="{{asset('assets/img/authors/avatar1.jpg')}}" width="35"
                             class="img-circle img-responsive pull-left"
                             height="35" alt="User Image">
                        <div class="riot">
                            <div>
                                Natali
                                <span>
                                        <i class="caret"></i>
                                    </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('assets/img/authors/avatar1.jpg')}}" class="img-circle" alt="User Image">
                            <p> Nataliapery</p>
                        </li>
                        <!-- Menu Body -->
                        <li class="p-t-3"><a href="{{ URL :: to('user_profile') }}"> <i class="fa fa-fw fa-user"></i> My
                                Profile </a>
                        </li>
                        <li role="presentation"></li>
                        <li><a href="{{ URL :: to('edit_user') }}"> <i class="fa fa-fw fa-gear"></i> Account Settings
                            </a></li>
                        <li role="presentation" class="divider"></li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ URL :: to('lockscreen') }}">
                                    <i class="fa fa-fw fa-lock"></i>
                                    Lock
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ URL :: to('login') }}">
                                    <i class="fa fa-fw fa-sign-out"></i>
                                    Logout
                                </a>
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
                        <a class="pull-left profile-thumb" href="#">
                            <img src="{{asset('assets/img/authors/avatar1.jpg')}}" class="img-circle" alt="User Image">
                        </a>
                        <div class="content-profile">
                            <h4 class="media-heading">
                                Nataliapery1
                            </h4>
                            <ul class="icon-list">
                                <li>
                                    <a href="{{ URL::to('users') }} ">
                                        <i class="fa fa-fw fa-user"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL::to('lockscreen') }} ">
                                        <i class="fa fa-fw fa-lock"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL::to('edit_user') }} ">
                                        <i class="fa fa-fw fa-gear"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL::to('login') }} ">
                                        <i class="fa fa-fw fa-sign-out"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="navigation">
                    <li {!! (Request::is('index')|| Request::is('/')? 'class="active"':"") !!}>
                        <a href="{{ URL::to('index') }} ">
                            <i class="menu-icon fa fa-fw fa-home"></i>
                            <span class="mm-text ">Dashboard V1</span>
                        </a>
                    </li>
                    <li {!! (Request::is('index2')? 'class="active"':"") !!}>
                        <a href="{{ URL::to('index2') }} ">
                            <i class="menu-icon fa fa-fw fa-tachometer"></i>
                            <span class="mm-text ">Dashboard V2</span>
                        </a>
                    </li>
                    <li {!! (Request::is('form_elements')|| Request::is('form_editors') || Request::is('form_validations')|| Request::is('form_layouts')|| Request::is('form_wizards')|| Request::is('complex_forms')|| Request::is('complex_forms2')|| Request::is('radio_checkboxes')|| Request::is('dropdowns')|| Request::is('datepicker')|| Request::is('advanceddate_pickers')|| Request::is('x-editable') ? 'class="active"' : '') !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-check-square"></i>
                            <span>Forms</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('form_elements') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('form_elements') }}">
                                    <i class="fa fa-fw fa-fire"></i>  
                                </a>
                            </li>
                            <li {!! (Request::is('form_editors') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('form_editors') }}">
                                    <i class="fa fa-fw fa-file-text-o"></i> Form Editors
                                </a>
                            </li>
                            <li {!! (Request::is('form_validations') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('form_validations') }}">
                                    <i class="fa fa-fw fa-warning"></i> Form Validations
                                </a>
                            </li>
                            <li {!! (Request::is('form_layouts') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('form_layouts') }}">
                                    <i class="fa fa-fw fa-fire"></i> Form Layouts
                                </a>
                            </li>
                            <li {!! (Request::is('form_wizards') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('form_wizards') }}">
                                    <i class="fa fa-fw fa-cog"></i> Form Wizards
                                </a>
                            </li>
                            <li {!! (Request::is('complex_forms') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('complex_forms') }}">
                                    <i class="fa fa-fw fa-newspaper-o"></i> Complex Forms
                                </a>
                            </li>
                            <li {!! (Request::is('complex_forms2') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('complex_forms2') }}">
                                    <i class="fa fa-fw fa-newspaper-o"></i> Complex Forms 2
                                </a>
                            </li>
                            <li {!! (Request::is('radio_checkboxes') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('radio_checkboxes') }}">
                                    <i class="fa fa-fw fa-check-square-o"></i> Radio and Checkbox
                                </a>
                            </li>
                            <li {!! (Request::is('dropdowns') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('dropdowns') }}">
                                    <i class="fa fa-fw fa-chevron-circle-down"></i> Drop Downs
                                </a>
                            </li>
                            <li {!! (Request::is('datepicker') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('datepicker') }}">
                                    <i class="fa fa-fw fa-calendar-o"></i> Date pickers
                                </a>
                            </li>
                            <li {!! (Request::is('advanceddate_pickers') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('advanceddate_pickers') }}">
                                    <i class="fa fa-fw fa-calendar"></i> Advanced Date pickers
                                </a>
                            </li>
                            <li {!! (Request::is('x-editable') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('x-editable') }}">
                                    <i class="fa fa-fw fa-eyedropper"></i> X-editable
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('general_components')|| Request::is('pickers') || Request::is('buttons')|| Request::is('tabs_accordions')|| Request::is('fonts')||Request::is('fontawesome_icons')||Request::is('simple_line_icons')||Request::is('glyphicons') || Request::is('grid_layout')|| Request::is('advanced_modals')|| Request::is('gridstack')|| Request::is('tags_input')|| Request::is('nestable_list')|| Request::is('sweet_alert')|| Request::is('toastr_notifications')|| Request::is('notifications')|| Request::is('session_timeout')|| Request::is('draggable_portlets') ? 'class="active"' : '') !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-desktop"></i>
                            <span>
                                    UI Features
                                </span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('general_components') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('general_components') }}">
                                    <i class="fa fa-fw fa-plug"></i> General Components
                                </a>
                            </li>
                            <li {!! (Request::is('pickers') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('pickers') }}">
                                    <i class="fa fa-fw fa-paint-brush"></i> Pickers
                                </a>
                            </li>
                            <li {!! (Request::is('buttons') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('buttons') }}">
                                    <i class="fa fa-fw fa-delicious"></i> Buttons
                                </a>
                            </li>
                            <li {!! (Request::is('tabs_accordions') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('tabs_accordions') }}">
                                    <i class="fa fa-fw fa-copy"></i> Tabs &amp; Accordions
                                </a>
                            </li>
                            <li {!! (Request::is('fonts')||Request::is('fontawesome_icons')||Request::is('simple_line_icons')||Request::is('glyphicons') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('fonts') }}">
                                    <i class="fa fa-fw fa-font"></i> Font Icons
                                </a>
                            </li>
                            <li {!! (Request::is('grid_layout') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('grid_layout') }}">
                                    <i class="fa fa-fw fa-columns"></i> Grid Layout
                                </a>
                            </li>
                            <li {!! (Request::is('advanced_modals') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('advanced_modals') }}">
                                    <i class="fa fa-fw fa-suitcase"></i> Advanced Modals
                                </a>
                            </li>
                            <li {!! (Request::is('gridstack') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('gridstack') }}">
                                    <i class="fa fa-fw fa-slack"></i> Grid Stack
                                </a>
                            </li>
                            <li {!! (Request::is('tags_input') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('tags_input') }}">
                                    <i class="fa fa-fw fa-tag"></i> Tags Input
                                </a>
                            </li>
                            <li {!! (Request::is('nestable_list') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('nestable_list') }}">
                                    <i class="fa fa-fw fa-navicon"></i> Nestable List
                                </a>
                            </li>
                            <li {!! (Request::is('sweet_alert') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('sweet_alert') }}">
                                    <i class="fa fa-fw fa-bell"></i> Sweet Alert
                                </a>
                            </li>
                            <li {!! (Request::is('toastr_notifications') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('toastr_notifications') }}">
                                    <i class="fa fa-fw fa-desktop"></i> Toastr Notifications
                                </a>
                            </li>
                            <li {!! (Request::is('notifications') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('notifications') }}">
                                    <i class="fa fa-fw fa-flag"></i> Notifications
                                </a>
                            </li>
                            <li {!! (Request::is('session_timeout') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('session_timeout') }}">
                                    <i class="fa fa-fw fa-rocket"></i> Session Timeout
                                </a>
                            </li>
                            <li {!! (Request::is('draggable_portlets') ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('draggable_portlets') }}">
                                    <i class="fa fa-fw fa-random"></i> Draggable Portlets
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is ('timeline')|| Request::is('transitions')? 'class="active"':"" )!!}>
                        <a href="#">
                            <i class="menu-icon fa fa-briefcase"></i>
                            <span>
                                    UI Components
                                </span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('timeline')? 'class="active"':"") !!}>
                                <a href="{{URL::to('timeline')}} ">
                                    <i class="fa fa-fw fa-clock-o"></i> Timeline
                                </a>
                            </li>
                            <li {!! (Request::is('transitions')? 'class="active"':"") !!}>
                                <a href="{{  URL::to('transitions') }} ">
                                    <i class="fa fa-fw fa-star-half-empty"></i> Transitions
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('simple_tables')|| Request::is('datatables') || Request::is('advanced_datatables')|| Request::is('responsive_datatables')|| Request::is('bootstrap_tables')? 'class="active"':"")!!}>
                        <a href="#"> <i class="menu-icon fa fa-table"></i>
                            <span>DataTables</span>
                            <span class="fa arrow">
                                </span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('simple_tables')? 'class="active"':"") !!}>
                                <a href="{{URL::to('simple_tables')}} ">
                                    <i class="fa fa-fw fa-tasks"></i> Simple tables
                                </a>
                            </li>
                            <li {!! (Request::is('datatables')? 'class="active"':"") !!}>
                                <a href="{{URL::to('datatables')}} ">
                                    <i class="fa fa-fw fa-database"></i> Data Tables
                                </a>
                            </li>
                            <li {!! (Request::is('advanced_datatables')? 'class="active"':"") !!}>
                                <a href="{{URL::to('advanced_datatables')}} ">
                                    <i class="fa fa-fw fa-table"></i> Advanced Tables
                                </a>
                            </li>
                            <li {!! (Request::is('responsive_datatables')? 'class="active"':"") !!}>
                                <a href="{{URL::to('responsive_datatables')}} ">
                                    <i class="fa fa-fw fa-table"></i> Responsive DataTables
                                </a>
                            </li>
                            <li {!! (Request::is('bootstrap_tables')? 'class="active"':"") !!}>
                                <a href="{{URL::to('bootstrap_tables')}} ">
                                    <i class="fa fa-fw fa-table"></i> Bootstrap Tables
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('flot_charts')||Request::is('nvd3_charts')||Request::is('circle_sliders')||Request::is('chartjs_charts')||Request::is('dimple_charts')||Request::is('amcharts')||Request::is('chartist')? 'class="active"':"") !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-bar-chart-o"></i>
                            <span>Charts</span> <span
                                    class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('flot_charts')? 'class="active"':"") !!}>
                                <a href="{{URL::to('flot_charts')}} ">
                                    <i class="fa fa-fw fa-area-chart"></i> Flot Charts
                                </a>
                            </li>
                            <li {!! (Request::is('nvd3_charts')? 'class="active"':"") !!}>
                                <a href="{{URL::to('nvd3_charts')}} ">
                                    <i class="fa fa-fw fa-line-chart"></i> NVD3 Charts
                                </a>
                            </li>
                            <li {!! (Request::is('circle_sliders')? 'class="active"':"") !!}>
                                <a href="{{URL::to('circle_sliders')}} ">
                                    <i class="fa fa-fw fa-sun-o"></i> Circle Sliders
                                </a>
                            </li>
                            <li {!! (Request::is('chartjs_charts')? 'class="active"':"") !!}>
                                <a href="{{URL::to('chartjs_charts')}} ">
                                    <i class="fa fa-fw fa-pie-chart"></i> Chartjs Charts
                                </a>
                            </li>
                            <li {!! (Request::is('dimple_charts')? 'class="active"':"") !!}>
                                <a href="{{URL::to('dimple_charts')}} ">
                                    <i class="fa fa-fw fa-area-chart"></i> Dimple Charts
                                </a>
                            </li>
                            <li {!! (Request::is('amcharts')? 'class="active"':"") !!}>
                                <a href="{{URL::to('amcharts')}} ">
                                    <i class="fa fa-fw fa-line-chart"></i> Amcharts
                                </a>
                            </li>
                            <li {!! (Request::is('chartist')? 'class="active"':"") !!}>
                                <a href="{{URL::to('chartist')}} ">
                                    <i class="fa fa-fw fa-bar-chart"></i> Chartist Charts
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('calendar')||Request::is('calendar2')? 'class="active"':"") !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-fw fa-calendar"></i>
                            <span>Calendar</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('calendar')? 'class="active"':"") !!}>
                                <a href="{{URL::to('calendar')}} ">
                                    <i class=" menu-icon fa fa-fw fa-calendar"></i>
                                    <span>Calendar</span>
                                    <small class="badge">7</small>
                                </a>
                            </li>
                            <li {!! (Request::is('calendar2')? 'class="active"':"") !!}>
                                <a href="{{URL::to('calendar2')}} ">
                                    <i class=" menu-icon fa fa-fw fa-calendar-o"></i>
                                    <span>Advanced Calendar</span>
                                    <small class="badge">6</small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('masonry_gallery')||Request::is('multiplefile_upload')||Request::is('dropify')||Request::is('image_hover')||Request::is('image_filter')||Request::is('image_magnifier')? 'class="active"':"") !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-fw fa-photo"></i>
                            <span>Gallery</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('masonry_gallery')? 'class="active"':"") !!}>
                                <a href="{{URL::to('masonry_gallery')}} ">
                                    <i class="fa fa-fw fa-file-image-o"></i> Masonry Gallery
                                </a>
                            </li>
                            <li {!! (Request::is('multiplefile_upload')? 'class="active"':"") !!}>
                                <a href="{{URL::to('multiplefile_upload')}} ">
                                    <i class="fa fa-fw fa-cloud-upload"></i> Multiple File Upload
                                </a>
                            </li>
                            <li {!! (Request::is('dropify')? 'class="active"':"") !!}>
                                <a href="{{URL::to('dropify')}} ">
                                    <i class="fa fa-fw fa-dropbox"></i> Dropify
                                </a>
                            </li>
                            <li {!! (Request::is('image_hover')? 'class="active"':"") !!}>
                                <a href="{{URL::to('image_hover')}} ">
                                    <i class="fa fa-file-image-o"></i> Image Hover
                                </a>
                            </li>
                            <li {!! (Request::is('image_filter')? 'class="active"':"") !!}>
                                <a href="{{URL::to('image_filter')}} ">
                                    <i class="fa fa-filter"></i> Image Filter
                                </a>
                            </li>
                            <li {!! (Request::is('image_magnifier')? 'class="active"':"") !!}>
                                <a href="{{URL::to('image_magnifier')}} ">
                                    <i class="fa  fa-search-plus"></i> Image Magnifier
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('users')||Request::is('addnew_user')||Request::is('user_profile')||Request::is('deleted_users')? 'class="active"':"") !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-fw fa-users"></i>
                            <span>Users</span> <span
                                    class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('users')? 'class="active"':"") !!}>
                                <a href="{{URL::to('users')}} ">
                                    <i class="fa fa-list" aria-hidden="true"></i> Users List
                                </a>
                            </li>
                            <li {!! (Request::is('addnew_user')? 'class="active"':"") !!}>
                                <a href="{{URL::to('addnew_user')}} ">
                                    <i class="fa fa-fw fa-user"></i> Add New User
                                </a>
                            </li>
                            <li {!! (Request::is('user_profile')? 'class="active"':"") !!}>
                                <a href="{{URL::to('user_profile')}} ">
                                    <i class="fa fa-fw fa-user-md"></i> View Profile
                                </a>
                            </li>
                            <li {!! (Request::is('deleted_users')? 'class="active"':"") !!}>
                                <a href="{{URL::to('deleted_users')}} ">
                                    <i class="fa fa-fw fa-times"></i> Deleted Users
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('google_maps')||Request::is('vector_maps')||Request::is('advanced_maps')? 'class="active"':"") !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-map-marker"></i>
                            <span>Maps</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('google_maps')? 'class="active"':"") !!}>
                                <a href="{{URL::to('google_maps')}} ">
                                    <i class="fa fa-fw fa-globe"></i> Google Maps
                                </a>
                            </li>
                            <li {!! (Request::is('vector_maps')? 'class="active"':"") !!}>
                                <a href="{{URL::to('vector_maps')}} ">
                                    <i class="fa fa-fw fa-map-marker"></i> Vector Maps
                                </a>
                            </li>
                            <li {!! (Request::is('advanced_maps')? 'class="active"':"") !!}>
                                <a href="{{URL::to('advanced_maps')}} ">
                                    <i class="fa fa-fw fa-location-arrow"></i> Advanced Maps
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('lockscreen')||Request::is('lockscreen2')||Request::is('invoice')||Request::is('blank')||Request::is('login')||Request::is('login2')||Request::is('register')||Request::is('register2')||Request::is('404')||Request::is('500')? 'class="active"':"") !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-files-o"></i>
                            <span>Pages</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('lockscreen')? 'class="active"':"") !!}>
                                <a href="{{URL::to('lockscreen')}} ">
                                    <i class="fa fa-fw fa-lock"></i> Lockscreen
                                </a>
                            </li>
                            <li {!! (Request::is('lockscreen2')? 'class="active"':"") !!}>
                                <a href="{{URL::to('lockscreen2')}} ">
                                    <i class="fa fa-fw fa-lock"></i> Lockscreen V2
                                </a>
                            </li>
                            <li {!! (Request::is('invoice')? 'class="active"':"") !!}>
                                <a href="{{URL::to('invoice')}} ">
                                    <i class="fa fa-fw fa-newspaper-o"></i> Invoice
                                </a>
                            </li>
                            <li {!! (Request::is('blank')? 'class="active"':"") !!}>
                                <a href="{{URL::to('blank')}} ">
                                    <i class="fa fa-fw fa-file-o"></i> Blank
                                </a>
                            </li>
                            <li {!! (Request::is('login')? 'class="active"':"") !!}>
                                <a href="{{URL::to('login')}} ">
                                    <i class="fa fa-fw fa-sign-in"></i> Login
                                </a>
                            </li>
                            <li {!! (Request::is('login2')? 'class="active"':"") !!}>
                                <a href="{{URL::to('login2')}} ">
                                    <i class="fa fa-fw fa-sign-in"></i> Login V2
                                </a>
                            </li>
                            <li {!! (Request::is('register')? 'class="active"':"") !!}>
                                <a href="{{URL::to('register')}} ">
                                    <i class="fa fa-fw fa-sign-in"></i> Register
                                </a>
                            </li>
                            <li {!! (Request::is('register2')? 'class="active"':"") !!}>
                                <a href="{{URL::to('register2')}} ">
                                    <i class="fa fa-fw fa-sign-in"></i> Register V2
                                </a>
                            </li>
                            <li {!! (Request::is('404')? 'class="active"':"") !!}>
                                <a href="{{URL::to('404')}} ">
                                    <i class="fa fa-fw fa-unlink"></i> 404 Error
                                </a>
                            </li>
                            <li {!! (Request::is('500')? 'class="active"':"") !!}>
                                <a href="{{URL::to('500')}} ">
                                    <i class="fa fa-fw fa-frown-o"></i> 500 Error
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('')||Request::is('')||Request::is('')||Request::is('')||Request::is('')? 'class="active"':"") !!}>
                        <a href="#">
                            <i class="menu-icon fa fa-th"></i>
                            <span>Layouts</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! (Request::is('menubarfold')? 'class="active"':"") !!}>
                                <a href="{{URL::to('menubarfold')}} ">
                                    <i class="fa fa-fw fa-list-alt"></i> Menubar Fold
                                </a>
                            </li>
                            <li {!! (Request::is('layout_horizontal_menu')? 'class="active"':"") !!}>
                                <a href="{{URL::to('layout_horizontal_menu')}} ">
                                    <i class="fa fa-fw fa-bars"></i> Horizontal Menu
                                </a>
                            </li>
                            <li {!! (Request::is('layout_footer')? 'class="active"':"") !!}>
                                <a href="{{URL::to('layout_footer')}} ">
                                    <i class="fa fa-fw fa-ellipsis-h"></i> Sticky Footer
                                </a>
                            </li>
                            <li {!! (Request::is('boxed')? 'class="active"':"") !!}>
                                <a href="{{URL::to('boxed')}} ">
                                    <i class="fa fa-fw fa-th-large"></i> Boxed Layout
                                </a>
                            </li>
                            <li {!! (Request::is('layout_fixed_header')? 'class="active"':"") !!}>
                                <a href="{{URL::to('layout_fixed_header')}} ">
                                    <i class="fa fa-fw fa-th-list"></i> Fixed Header
                                </a>
                            </li>
                            <li {!! (Request::is('layout_boxed_fixed_header')? 'class="active"':"") !!}>
                                <a href="{{URL::to('layout_boxed_fixed_header')}} ">
                                    <i class="fa fa-fw fa-th"></i> Boxed &amp; Fixed Header
                                </a>
                            </li>
                            <li {!! (Request::is('layout_fixed')? 'class="active"':"") !!}>
                                <a href="{{URL::to('layout_fixed')}} ">
                                    <i class="fa fa-fw fa-indent"></i> Fixed Header &amp; Menu
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-dropdown">
                        <a href="#">
                            <i class="menu-icon fa fa-sitemap"></i>
                            <span>
                                    Menu levels
                                </span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="#">
                                    <i class="fa fa-fw fa-sitemap"></i> Level 1
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu sub-submenu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-fw fa-sitemap"></i> Level 2
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-fw fa-sitemap"></i> Level 2
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-fw fa-sitemap"></i> Level 2
                                            <span class="fa arrow"></span>
                                        </a>
                                        <ul class="sub-menu sub-submenu">
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-fw fa-sitemap"></i> Level 3
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-fw fa-sitemap"></i> Level 3
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-fw fa-sitemap"></i> Level 3
                                                    <span class="fa arrow"></span>
                                                </a>
                                                <ul class="sub-menu sub-submenu">
                                                    <li>
                                                        <a href="#">
                                                            <i class="fa fa-fw fa-sitemap"></i> Level 4
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="fa fa-fw fa-sitemap"></i> Level 4
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="fa fa-fw fa-sitemap"></i> Level 4
                                                            <span class="fa arrow"></span>
                                                        </a>
                                                        <ul class="sub-menu sub-submenu">
                                                            <li>
                                                                <a href="#">
                                                                    <i class="fa fa-fw fa-sitemap"></i> Level 5
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <i class="fa fa-fw fa-sitemap"></i> Level 5
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <i class="fa fa-fw fa-sitemap"></i> Level 5
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-fw fa-sitemap"></i> Level 4
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-fw fa-sitemap"></i> Level 2
                                            <span class="fa arrow"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-fw fa-sitemap"></i> Level 1
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-fw fa-sitemap"></i> Level 1
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu sub-submenu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-fw fa-sitemap"></i> Level 2
                                            <span class="fa arrow"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-fw fa-sitemap"></i> Level 2
                                            <span class="fa arrow"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-fw fa-sitemap"></i> Level 2
                                            <span class="fa arrow"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
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
