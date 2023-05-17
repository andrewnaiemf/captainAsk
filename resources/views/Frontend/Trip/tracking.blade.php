<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>captain ask tracking</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

        <style>
            html, body {
              height: 100%;
            }
            #map-container {
              height: calc(100% - 56px);
            }
            .captain .card{
                background: #1F1F1F;
                border-radius: 15px;
                margin: 20px 50px;
                color: #fff
            }
            .captain .card .captain_detais .name{
                font-size:  20px ;
                font-weight: 600;
                margin: 0
            }
            .captain .card .captain_detais .rating{
                background-color:#F9F9F9;
                width: fit-content;
                padding: 2px 10px;
                border-radius: 7px;
                display: flex;
                align-items: center;
            }
            .profile{
                display: flex;
                justify-content: center;
                padding-top: 15px !important;
            }
            .points{
                background: #124491;
                border-radius: 50%;
                padding: 0px 5px;
                font-size: 13px;
                display: inline-flex;
            }
            #map-container {
                height: calc(100% - 56px);
                min-height: 300px;
            }

            #pageContent {
                display: none;
            }

        </style>
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

        <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>


    </head>
    <body class="d-flex flex-column">
        <div id="spinner">
            <!-- Your spinner HTML goes here -->
          </div>

         <header class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand m-0" href="#">
                <img src="{{ asset('Frontend/images/Option-2.png') }}" alt="Logo" height="80" width="80">
            </a>
            <div class="ml-auto">
                <a href="#"><img src="{{ asset('Frontend/images/google.png') }}" alt="Google App" height="40"></a>
                <a href="#"><img src="{{ asset('Frontend/images/app-store.png') }}" alt="Apple App" height="40"></a>
            </div>
        </header>


        <div id="pageContent">

            <div class="captain">
                <div class="card row pl-3 ">
                    <div class="col-lg-6  col-md-6 col-sm-12 p-3" style="    padding-bottom: 0 !important;">
                        <div class="row m-0" style="flex-wrap: nowrap">
                            <div class="col-sm-3 p-0 profile">
                                <img id="captain_image" style="border-radius: 50%; height: 50px; width:50px" src="https://greatives.eu/themes/osmosis/wp-content/uploads/2014/11/Businessman-pointing-copy-space.jpg"  alt="">
                            </div>
                            <div class="col-md-9 col-sm-9 p-0">
                                <div class="row m-0" style="flex-wrap: nowrap" >
                                    <div class="captain_detais m-0">
                                        <p class="name" id="cptain_name"> captain name </p>
                                        <div class="rating" >
                                            <img src="{{ asset('Frontend/images/star.png') }}" alt="" style="margin-right: 5px; width:20px">
                                            <span id="rating" style="color: #1F1F1F;font-size: 18px; font-weight: 700;"> 5.9 </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  m-0 mt-2"  >
                                    <div class="car_detais m-0">
                                        <p class="m-0"> Black BYD F3 </p>
                                        <p>  <span style="margin-right: 20px"> 3491 A H J </span>    <span> ح هـ ا ٣٤٩١ </span> </p>
                                    </div>
                                </div>
                                <div class="row  m-0 ">
                                    <ul style="list-style: none; padding:0">
                                        <li style="margin-bottom: 10px;">
                                            <span class="points"> A </span>
                                            <span id="from_address"> Hosary </span>
                                        </li>

                                        <li>
                                            <span class="points"> B </span>
                                            <span id="to-address"> zamalek </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div id="map-container" class="container-fluid">
                <div id="map" style="height: 100%;"></div>
            </div>

        </div>


        <footer class="footer mt-auto bg-light">
            <div class="container text-center">
                <span class="text-muted">&copy; 2024. Powered by Captain Ask.</span>
            </div>
        </footer>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVe0Ej8NKkTMLYIF_J0iz0Ci8kwFAUG9g&callback=initMap"></script>
        <script>
                var trip = <?php echo json_encode($tripData); ?>;
                $('#from_address').html(trip.start_address)
                $('#from_address').html(trip.end_address)

                $(window).load(function() {
                    // Hide the spinner
                    $('#spinner').hide();

                    // Show the page content
                    $('#pageContent').show();
                });
        </script>

        <script src="{{asset('Frontend/Js/Tracking/firebase.js')}}"></script>
    </body>
</html>
