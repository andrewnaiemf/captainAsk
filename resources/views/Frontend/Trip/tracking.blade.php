<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>captain ask tracking</title>

        <link rel="icon" href="{{ asset('Frontend/images/Option-2.png') }}" type="image/png">
        <!-- Add the following meta tags for the website icon and other Open Graph properties -->
        <meta property="og:title" content="captain ask">
        <meta property="og:description" content="captain ask for trips">
        <meta property="og:image" content="{{ asset('Frontend/images/Option-2.png') }}">
        {{--  <meta property="og:url" content="http://www.yourwebsite.com">  --}}

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVe0Ej8NKkTMLYIF_J0iz0Ci8kwFAUG9g&callback=initMap"  defer></script>

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

            @media (min-width: 576px){
                .modal-dialog {
                    max-width: 400px !important;
                    margin: 1.75rem auto;
                }
            }
            .store_icons a img{
                max-width: 150px !important;
            }
            @media (max-width: 576px){

                .store_icons a img{
                    max-width: 120px !important;
                }
                .navbar-brand img{
                    width: 115px !important;

                }

                .captain .card {
                    margin: 20px ;
                }
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

        <!-- Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 50px;">
                <div class="modal-body text-center" style="    padding: 2rem;">
                    <img src="{{ asset('Frontend/images/right-arrow.png') }}" width="40" alt="" >
                    <p class="mt-2" style="color: #1F1F1F; font-size: 18px; font-weight: 700; ">The trip is <span id="Trip_status">Finished</span>.</p>
                    <a href="#" style="justify-content: center; display: flex;text-decoration: none;">
                        <div style="background: #20BF55; color: #FFFFFF; width: fit-content; padding: 5px 14px; margin: 0; border-radius: 13px;">
                            <p class="m-0"> Get Captain ASK</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        </div>

         <header class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand m-0" href="#">
                <img src="{{ asset('Frontend/images/Option-2.png') }}" alt="Logo" width="150">
            </a>
            <div class="store_icons ml-auto d-flex justify-content-between">
                <a href="#"><img src="{{ asset('Frontend/images/google.png') }}" alt="Google App" ></a>
                <a href="#"><img src="{{ asset('Frontend/images/app-store.png') }}" alt="Apple App"  ></a>
            </div>
        </header>


        <div id="pageContent">

            <div class="captain">
                <div class="card row pl-3 ">
                    <div class="col-lg-6  col-md-6 col-sm-12 p-3" style="    padding-bottom: 0 !important;">
                        <div class="row m-0" style="flex-wrap: nowrap">
                            <div class="col-md-2 col-2 p-0 profile mr-4">
                                <img id="captain_image" style="border-radius: 50%; height: 50px; width:50px" src="https://captainask.com/storage/default/default.png"  alt="">
                            </div>
                            <div class="col-md-10 col-10 p-0">
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
                                        <p class="m-0" id="car_model">
                                            <span  id="car_color">Black</span>
                                            <span id="car_model"> BYD F3</span>
                                        </p>
                                        <p>
                                            <span  id="car_english_number">
                                                3491
                                            </span>
                                            <span  class="mr-2" id="car_english_letters">
                                                A H J
                                            </span>
                                            <span  id="car_arabic_number">
                                                 ٣٤٩١
                                            </span>
                                            <span class="mr-1" id="car_arabic_letters">
                                                ا ح هـ
                                            </span>
                                        </p>
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
                                            <span id="to_address"> zamalek </span>
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
