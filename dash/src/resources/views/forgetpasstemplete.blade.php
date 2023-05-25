<!DOCTYPE html>
<html lang="{{ app()->getLocale()=='ar'?'ar':'en' }}" dir="{{ app()->getLocale()=='ar'?'rtl':'' }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('dashboard') }}/assets/img/dashboard-icon.png">
    <link rel="icon" type="image/png" href="{{ url(config('dash.DASHBOARD_ICON')) }}">
    <title>{{ __('dash::dash.login') }}</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Cairo:300,400,500,700,900|Cairo+Slab:400,700" />

    <!-- Font Awesome Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"  />
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ url('dashboard') }}/assets/css/material-dashboard.css?v=3.0.4" rel="stylesheet" />
  </head>
  <body class="bg-gray-200">
    <div class="container position-sticky z-index-sticky top-0">
      <div class="row">
        <div class="col-12">
          <!-- Navbar -->
          <!-- End Navbar -->
        </div>
      </div>
    </div>
    <main class="main-content  mt-0">
      <div class="page-header align-items-start min-vh-100" style="background-image: url('{{ url('dashboard/assets/img/bg.jpeg') }}');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">

            <div class="row justify-content-center align-items-center">
                <div class="col col-sm-6 align-self-center">
                    <div class="card">
                        <div class="card-header">
                            <strong>
                                {{ __('dash::dash.reset_password_box_title') }}
                            </strong>
                        </div><!--card-header-->

                        <div class="card-body">

                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-0 clearfix">
                                        <a style="background-color: #15c;
                                        color: #fff;
                                        padding: 3px 15px;
                                        text-decoration: none;" href="{{ route('captainAsk.resetPassword', $token) }}">  {{ __('dash::dash.reset_password_box_title_btn') }}  </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </main>
 <!--   Core JS Files   -->
    <script src="{{ url('dashboard') }}/assets/js/core/popper.min.js"></script>
    <script src="{{ url('dashboard') }}/assets/js/core/bootstrap.min.js"></script>
    <script src="{{ url('dashboard') }}/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ url('dashboard') }}/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
    damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    </script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" ></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ url('dashboard') }}/assets/js/material-dashboard.min.js?v=3.0.4"></script>
  </body>
</html>
