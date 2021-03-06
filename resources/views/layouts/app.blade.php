<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title', 'Accueil') | {{ __("E-EVALUATION") }}</title>
  <link rel="website" href="{{ url('/') }}">
  {{ csrf_field() }}
  <base href="{{ url('/') }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="{{ asset('css/select2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css')}} ">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/jquery-bootstrap-scrolling-tabs@2.4.0/dist/jquery.scrolling-tabs.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link href="{{ App\Asset::path('app.css') }}" rel="stylesheet">

  <!-- jQuery 3 -->
  <script src="{{asset('js/jquery.min.js')}}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{asset('js/jquery-ui.min.js')}}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>

  @yield('style')

</head>
<body class="hold-transition skin-blue sidebar-mini fixed {{App\Setting::get('toggle_sidebar') == 1 ? 'sidebar-collapse':''}}">
<div class="spinner-wp">
  <!-- <i class="fa fa-refresh fa-spin fa-5x" aria-hidden="true"></i> -->
  <div class="looding">
    <div class="reloadDouble"></div>
  </div>
</div>
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="{{url('/')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>M</b>E</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">{!! __("<b>E</b>-évaluations") !!}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ App\User::avatar(Auth::user()->id) }}" class="img-circle" alt="User Image" width="19">
                <span class="hidden-xs">{{App\User::displayName()}}
                  (
                  @foreach(Auth::user()->roles as $role)
                    {{$role->name}}
                  @endforeach
                  )
                </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{ App\User::avatar(Auth::user()->id) }}" class="img-circle" alt="User Image">

                <p>
                  {{App\User::displayName()}}
                  @if(!Auth::user()->hasRole('ADMIN'))
                    <small>{{ (!empty(Auth::user()->function)) ? App\Fonction::findOrFail(Auth::user()->function)->title : '---' }}</small>
                  @endif
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left mb-sm-10">
                  <a href="{{url('/profile')}}" class="btn btn-info"><i class="fa fa-user"></i> {{ __("Profil") }}</a>
                </div>
                <div class="pull-md-right pull-sm-right">
                  <a href="{{ route('logout') }}" class="btn btn-info" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> {{ __("Déconnexion") }}</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                </div>
              </li>
            </ul>
          </li>
          <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> {{ __("Déconnexion") }}</a>
            <form id="logout-form-main-menu" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
          </li>
          <!-- disable control sidebar skin -->
          <li style="display: none;">
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <div class="home-logo">
        <a href="{{url('/')}}">
          <img src="{{ App\User::logo(Auth::user()->id) }}" alt="" class="img-responsive">
        </a>
      </div>
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ App\User::avatar(Auth::user()->id) }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{App\User::displayName()}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> {{ __("En ligne") }}</a>
        </div>
      </div>
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Recherche...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
          </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        @role(["ADMIN", "RH"])
        <li class="{{ Route::current()->getName() == 'dashboard' ? 'active' : '' }}">
          <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ __("Tableau de board") }}</span></a>
        </li>
        @endrole

        @role(["MANAGER", "COLLABORATEUR"])
        <li class="{{ Request::is('/') ? 'active' : '' }}">
          <a href="{{ route('home') }}"><i class="fa fa-comments"></i> <span>{{ __("Mes évaluations") }}</span></a>
        </li>
        @endrole

        @role(["ADMIN", "RH"])
        @php($isCampaign = in_array(Request::route()->getName(), ['entretiens', 'entretiens.calendar']))
        <li class="treeview {{ $isCampaign ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-comments"></i> <span>{{ __("Campagnes") }}</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu" style="{{ $isCampaign ? 'display: block;' : '' }}">
            <li class="{{ Route::current()->getName() == 'entretiens' ? 'active' : '' }}"><a href="{{ route('entretiens') }}"><i class="fa fa-list"></i> <span>{{ __("Liste") }}</span></a></li>
            <li class="{{ Request::is('entretiens/calendar') ? 'active' : '' }}"><a href="{{ url('entretiens/calendar') }}"><i class="fa fa-calendar"></i> <span>{{ __("Calendrier") }}</span></a></li>
          </ul>
        </li>
        @endrole

        @role(["ADMIN"])
        @php($isConfig = in_array(\Request::route()->getName(), ['general.settings', 'departments', 'functions', 'models', 'config.skills', 'config.emails', 'config.roles', 'teams', 'users', 'skills', 'surveys-list', 'objectifs', 'permissions']))
        <li class="treeview {{ $isConfig ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-gears"></i> <span>{{ __("Paramétrages") }}</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu" style="{{ $isConfig ? 'display: block;' : '' }}">
            <li class="{{ Request::is('config/settings/general') ? 'active' : '' }}">
              <a href="{{ url('config/settings/general') }}"><i class="fa fa-wrench"></i> {{ __("Champs éditables") }}</a>
            </li>
            <li class="{{ Request::is('users') ? 'active' : '' }}">
              <a href="{{ url('users') }}"><i class="fa fa-users"></i> <span>{{ __("Utilisateurs") }}</span></a>
            </li>
            <li class="{{ Request::is('config/surveys') ? 'active' : '' }}">
              <a href="{{ url('config/surveys') }}"><i class="fa fa-pencil"></i> <span>{{ __("Questionnaires") }}</span></a>
            </li>
            <li class="{{ Request::is('config/objectifs') ? 'active' : '' }}">
              <a href="{{ url('config/objectifs') }}"><i class="fa fa-signal"></i> {{ __("Objectifs") }}</a>
            </li>
            <li class="{{ Request::is('config/skills') ? 'active' : '' }}">
              <a href="{{ url('config/skills') }}"><i class="fa fa-graduation-cap"></i> {{ __("Fiches métiers") }}</a>
            </li>
          </ul>
        </li>
        @endrole

        @role(["ROOT"])
        @php($isRootConfig = in_array(Request::route()->getName(), ['languages', 'interface.translations', 'models', 'config.roles', 'permissions', 'formation-levels', 'experience-levels']))
        <li class="{{ Request::route()->getName() == 'companies' ? 'active' : '' }}">
          <a href="{{ route('companies') }}"><i class="fa fa-industry"></i> <span>{{ __("Comptes des sociétés") }}</span></a>
        </li>
        <li class="treeview {{ $isRootConfig ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-gears"></i> <span>{{ __("Configurations") }}</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu" style="{{ $isRootConfig ? 'display: block;' : '' }}">
            <li class="{{ Request::route()->getName() == 'languages' ? 'active' : '' }}">
              <a href="{{ route('languages') }}"><i class="fa fa-flag"></i> <span>{{ __("Langues") }}</span></a>
            </li>
            <li class="{{ Request::route()->getName() == 'interface.translations' ? 'active' : '' }}">
              <a href="{{ route('interface.translations') }}"><i class="fa fa-language"></i> <span>{{ __("Messages") }}</span></a>
            </li>
            <li class="{{ Request::route()->getName() == 'models' ? 'active' : '' }}">
              <a href="{{ route('models') }}"><i class="fa fa-list"></i> <span>{{ __("Modèles d'évaluations") }}</span></a>
            </li>
            <li class="{{ Request::route()->getName() == 'formation-levels' ? 'active' : '' }}">
              <a href="{{ route('formation-levels') }}"><i class="fa fa-graduation-cap"></i> <span>{{ __("Niveaux de formations") }}</span></a>
            </li>
            <li class="{{ Request::route()->getName() == 'experience-levels' ? 'active' : '' }}">
              <a href="{{ route('experience-levels') }}"><i class="fa fa-briefcase"></i> <span>{{ __("Niveaux d'expériences") }}</span></a>
            </li>
            <li class="{{ Request::route()->getName() == 'config.roles' ? 'active' : '' }}">
              <a href="{{ route('config.roles') }}"><i class="fa fa-user-secret"></i> {{ __("Rôles") }}</a>
            </li>
            <!--<li class="{{ Request::route()->getName() == 'permissions' ? 'active' : '' }}">
              <a href="{{ route('permissions') }}"><i class="fa fa-lock"></i> {{ __("Permissions") }}</a>-->
            </li>
          </ul>
        </li>
        @endrole

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header p-sm-10">
      <ol class="breadcrumb">
        <li><a href="{{ route('home') }}" class="text-blue"><i class="fa fa-home"></i> {{ __("Accueil") }}</a></li>
        @yield('breadcrumb')
      </ol>
    </section>
    @if(Session::has('danger') || Session::has('warning') || Session::has('success') || Session::has('info'))
      <div class="flush-alerts-section">
        <div class="container-fluid">
          <div class="row flash-message mb-10">
            <div class="col-md-12 pl-30 pr-30 p-sm-10">
              @foreach (['danger', 'warning', 'success', 'info'] as $msgType)
                @if($msgType == 'success') @php($icon = "fa-check-circle")
                @elseif($msgType == 'danger') @php($icon = "fa-times")
                @elseif($msgType == 'warning') @php($icon = "fa-warning")
                @else @php($icon = "fa-info-circle")
                @endif
                @if(Session::has($msgType))
                  <div class="chm-alerts alert alert-{{ $msgType }} alert-white rounded mt-20">
                    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
                    <div class="icon"><i class="fa {{ $icon }}"></i></div>
                    <span>{{ Session::get($msgType) }}</span>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        </div>
      </div>
    @endif
    @yield('content')
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>{{ __("Copyright") }} &copy; {{ date('Y') }} </strong> {{ __("Lycom Tous droits réservés.") }}
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab" style="display: none;"><i class="fa fa-home"></i></a>
      </li>

    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->

      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<div class="chm-float-alert"></div>

<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-slider/bootstrap-slider.min.js')}}"></script>
<script src="{{asset('js/admin.min.js')}}"></script>
<script src="{{asset('js/sweetalert2.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-bootstrap-scrolling-tabs@2.4.0/dist/jquery.scrolling-tabs.min.js"></script>
<script src="{{ App\Asset::path('app.js') }}"></script>
<script>
  $(document).ready(function () {
    $('#nav-tabs').scrollingTabs({
      scrollToTabEdge: true,
      enableSwiping: true
    });
  })
</script>

@yield('javascript')
</body>
</html>

