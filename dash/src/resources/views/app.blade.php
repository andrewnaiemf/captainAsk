<!DOCTYPE html>
<html lang="{{ app()->getLocale()=='ar'?'ar':'en' }}" dir="{{ app()->getLocale()=='ar'?'rtl':'' }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ $DASHBOARD_ICON }}">
    <link rel="icon" type="image/png" href="{{ $DASHBOARD_ICON }}">
    <title>{{ $title??$APP_NAME }}</title>
    <script type="text/javascript" src="{{ url('dashboard/assets/datatables/js/jquery.min.js') }}"></script>

    <!--  Fonts and icons  -->
    <link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/fonts/cairo/style.css') }}" />

    <!--fontawesome-free-6.2.0 Css Start-->
    <link rel="stylesheet" href="{{ url('dashboard/assets/fonts/fontawesome-free-6.2.0-web/css/all.min.css') }}"  />
    <!--fontawesome-free-6.2.0 Css End-->

    {{-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"> --}}

    <!-- CSS Files -->

    <link id="pagestyle" href="{{ url('dashboard/assets/css/bootstrap.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ url('dashboard/assets/css/material-dashboard.css?v=3.0.4') }}" rel="stylesheet" />

    @if(!empty($fields))
     <!-- Video.js base CSS -->

    <link href="{{ url('dashboard/assets/video.js-7.11.4/dist/video-js.min.css') }}" rel="stylesheet">

    <!-- Video Js Themes -->
    <link href="{{ url('dashboard/assets/video.js-7.11.4/theme/city.css') }}" rel="stylesheet">
    <link href="{{ url('dashboard/assets/video.js-7.11.4/theme/sea.css') }}" rel="stylesheet">
    <link href="{{ url('dashboard/assets/video.js-7.11.4/theme/fantasy.css') }}" rel="stylesheet">
    <link href="{{ url('dashboard/assets/video.js-7.11.4/theme/forest.css') }}" rel="stylesheet">

<!--select2 Start-->
  <link href="{{ url('dashboard/assets/select2-4-1-0/css/select2.min.css') }}" rel="stylesheet" />

@if(app()->getLocale() == 'ar')
<script src="{{ url('dashboard/assets/select2-4-1-0/js/i18n/ar.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/select2-4-1-0/css/select2-bootstrap-5-theme.rtl.min.css') }}">
@else
@if(app()->getLocale() != 'en')
<script src="{{ url('dashboard/assets/select2-4-1-0/js/i18n/'.app()->getLocale().'.js') }}"></script>
@endif
<link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/select2-4-1-0/css/select2-bootstrap-5-theme.min.css') }}">
@endif

<script src="{{ url('dashboard/assets/select2-4-1-0/js/select2.min.js') }}"></script>
<!--select2 End-->

    <script src="{{ url('dashboard/assets/video.js-7.11.4/dist/video.min.js') }}"></script>
    <!-- Video Js End -->

    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/super-build/ckeditor.js"></script>


    <link rel="stylesheet" href="{{ url('dashboard/assets/flatpicker/dist/flatpickr.min.css') }}">
    <script type="text/javascript" src="{{ url('dashboard/assets/flatpicker/dist/flatpickr.min.js') }}"></script>


<link rel="stylesheet" type="text/css" href="{{url('dashboard/assets/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('dashboard/assets/dropzone/min/basic.css')}}">
<script src="{{url('dashboard/assets/dropzone/min/dropzone.min.js')}}" type="text/javascript"></script>

    @endif


    <!-- system message and notifications -->
  @push('js')
  <script type="text/javascript" src="{{ url('dashboard/assets/toastr/toastr.min.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/toastr/toastr.min.css') }}">
<script type="text/javascript">
  $(document).ready(function(){
   toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "5000",
      "timeOut": "5000",
      // "extendedTimeOut": "1000",
      // "showEasing": "swing",
      // "hideEasing": "linear",
      // "showMethod": "fadeIn",
      // "hideMethod": "fadeOut"
    };
    @if(session()->has('success'))
    toastr.success("{{ session('success') }}");
    @endif
     @if(session()->has('error'))
    toastr.error("{{ session('error') }}");
    @endif
     @if(session()->has('danger'))
    toastr.error("{{ session('danger') }}");
    @endif
    @if(session()->has('warning'))
     toastr.warning("{{ session('warning') }}");
    @endif
    @if(session()->has('info'))
    toastr.info("{{ session('info') }}");
    @endif
  });
</script>
  @endpush
<!-- system message and notifications End-->
<!--fontawesome-free-6.2.0 -->
<!--fontawesome-free-6.2.0 Js Start-->
<script src="{{ url('dashboard/assets/fonts/fontawesome-free-6.2.0-web/js/all.min.js') }}" ></script>
<!--fontawesome-free-6.2.0 Js End-->

  </head>

  <body class="g-sidenav-show {{ app()->getLocale()=='ar'?'rtl':'' }} bg-gray-200">
    <div class="row">
    <div class="col-lg-3 d-none d-lg-block d-xl-block d-md-none d-sm-none d-xs-none">
     @include('dash::menu')
    </div>
    <div class="col-lg-9">
      {{-- position-relative max-height-vh-100 h-100 overflow-x-hidden --}}
    <main  class="main-content m-3 border-radius-lg {{ app()->getLocale() == 'ar'?'':'' }}  ">
      <!-- Navbar -->
      <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-5 d-block">
          <div class="collapse navbar-collapse mt-sm-0 mt-2 px-3" id="navbar">

            @include('dash::searchs')
            <ul class="navbar-nav
              {{ app()->getLocale()=='ar'?'me-auto ':'ms-md-auto' }}  ms-0">



              <!-- Notifications -->
              @include('dash::notifications')
               <!-- Notifications End -->


              <!-- resources_buttons -->
              @include('dash::add_resources_buttons')
              <!-- resources_buttons -->



              <li class="nav-item d-xl-none pe-4 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                  <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                  </div>
                </a>
              </li>

              @if(!empty($DASHBOARD_LANGUAGES) && count($DASHBOARD_LANGUAGES) > 1)
          <!-- DASHBOARD_LANGUAGES -->
          <li class="nav-item dropdown px-2 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-language fa-2x cursor-pointer"></i>
            </a>
            <ul class="dropdown-menu {{ app()->getLocale()!='ar'?'dropdown-menu-end':'' }} px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
              @foreach($DASHBOARD_LANGUAGES as $key=>$value)
              <li class="mb-2">
                <a class="dropdown-item border-radius-md" href="{{ url($DASHBOARD_PATH.'/change/language/'.$key) }}">
                  <div class="d-flex py-1">
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="text-sm font-weight-normal mb-1">
                      <span class="font-weight-bold">{{ $value }}</span>
                      </h6>
                    </div>
                  </div>
                </a>
              </li>
              @endforeach
            </ul>
          </li>
          <!-- DASHBOARD_LANGUAGES End -->
          @endif

              <li class="nav-item dropdown px-2 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-user fa-2x cursor-pointer"></i>
                  {{ auth()->guard('dash')->user()->name }}
                </a>
                <ul class="dropdown-menu {{ app()->getLocale()!='ar'?'dropdown-menu-end':'' }} px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                  <li class="mb-2 d-flex">
                    <a href="{{ url($DASHBOARD_PATH) }}/logout" lass="dropdown-item border-radius-md"  title="@lang('dash::dash.logout')">
                       <i class="fa b-1 fa-sign-out me-sm-1"></i> @lang('dash::dash.logout')
                      <span class="d-sm-inline d-none"></span>
                    </a>
                  </li>
                </ul>
              </li>





          {{-- <li class="nav-item px-2 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0">
              <i class="fa fa-cog fa-2x fixed-plugin-button-nav cursor-pointer"></i>
            </a>
          </li> --}}
        </ul>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 {{ app()->getLocale()=='ar'?'me-sm-6 me-5':'' }}">
          <li class="breadcrumb-item text-sm ps-2"><a class="opacity-5 text-dark" href="{{ url($DASHBOARD_PATH) }}">@lang('dash::dash.dashboard')</a></li>
          @if(!empty($breadcrumb) && is_array($breadcrumb) && count($breadcrumb) > 0)

            @foreach($breadcrumb as $bread)

              <li class="breadcrumb-item text-sm ps-2"><a class="opacity-5 text-dark" href="{{ $bread['url'] }}">{{ $bread['name'] }}</a></li>
            @endforeach
          @else
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $breadcrumb??$APP_NAME }}</li>
          @endif
        </ol>
        <h6 class="d-block font-weight-bolder mb-0">{{ $title??$APP_NAME }}   </h6>
      </nav>
    </div>
  </nav>
  <!-- End Navbar -->
  <div class="container-fluid py-4">
    <div class="row">
      @yield('content')
      {{-- Content or Small Cards --}}
    </div>
    <div class="row mt-4">
      {{-- Chart Cards --}}
    </div>






    <footer class="footer py-4  ">
      <div class="container-fluid" style="display: block">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-12 mb-lg-0 mb-4">
            {{--  <div class="copyright text-center text-sm text-muted {{ app()->getLocale() == 'ar'?'text-lg-end':'text-lg-start' }}">
              @if(!empty(config('dash.copyright')))

              <a href="{{config('dash.copyright.link')}}" class="font-weight-bold" target="_blank">{!! config('dash.copyright.copyright_text') !!}</a>

              @else
              © {{ date('Y') }},
              Dashboard <i class="fa fa-heart"></i> by
              <a href="https://phpdash.com/page/team" class="font-weight-bold" target="_blank">Dash , Mahmoud Ibrahim , Ahmed Mostafa , Hussein Mostafa , Enas ELlithy (V{{ Composer\InstalledVersions::getVersion('phpanonymous/dash') }})</a>
              @endif
            </div>  --}}
          </div>
          <div class="col-lg-12">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              {{-- <li class="nav-item">
                <a href="https://github.com/arabnewscms" class="nav-link text-muted" target="_blank">github</a>
              </li>
              <li class="nav-item">
                <a href="https://github.com/arabnewscms/dash" class="nav-link text-muted" target="_blank">Dash Repo</a>
              </li> --}}
            </ul>
          </div>
        </div>
      </div>
    </footer>
  </div>
</main>
{{-- @include('dash::UIConfigurator') --}}

<!--   Core JS Files   -->
<script src="{{ url('dashboard/assets/js/core/popper.min.js') }}"></script>
<script src="{{ url('dashboard/assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ url('dashboard/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ url('dashboard/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>



<script>
var win = navigator.platform.indexOf('Win') > -1;
if (win && document.querySelector('#sidenav-scrollbar')) {
var options = {
damping: '0.5'
}
Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
}



@if(!empty(request('relationField')))
@foreach(request('relationField') as $rk=>$rv)
$('.{{ $rk }}').val('{{ $rv }}').trigger('change');
@endforeach
@endif

</script>


<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ url('dashboard/assets/js/material-dashboard.js?v=3.0.4') }}"></script>
<script src="{{ url('dashboard/assets/js/dashboard_functions.js') }}"></script>
<script src="{{ url('dashboard/assets/js/jquery-3.5.1.js') }}"></script>

@if(app()->getLocale() == 'ar')
  <style type="text/css">
   .dt-buttons{
      display: none;
    }
  </style>
  @endif
<script src="{{ url('dashboard/assets/select2-4-1-0/js/select2.min.js') }}"></script>
@stack('js')
</body>
</html>

