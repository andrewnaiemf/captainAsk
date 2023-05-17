// tripid
var currentURL = window.location.pathname;
var segments = currentURL.split("/");
var tripId = segments[segments.length - 1];
var map;
var marker

initMap();

// Google Maps API initialization
function initMap() {

    var startPoint = { lat: Number(trip.start_lat), lng:  Number(trip.start_lng) };
    var endPoint = { lat:  Number(trip.end_lat), lng:  Number(trip.end_lng) };

    var mapOptions = {
        center: startPoint, // Default map center
        zoom: 15 // Default zoom level
    };

    map = new google.maps.Map(document.getElementById('map'), mapOptions);

    // Draw route between start and end points
    var directionsService = new google.maps.DirectionsService();
    var directionsDisplay = new google.maps.DirectionsRenderer();

    var routeOptions = {
        origin: startPoint,
        destination: endPoint,
        travelMode: 'DRIVING'
    };

    directionsService.route(routeOptions, function(result, status) {
        if (status === 'OK') {
        directionsDisplay.setDirections(result);
        }
    });

    directionsDisplay.setMap(map);

}


fetch('../../../captainask-7c9a8706dac8.json')
  .then(response => response.json())
  .then(serviceAccount => {

    // Initialize Firebase
    const firebaseConfig = {
        apiKey: "AIzaSyC6Ebda5VwrV2SGryqYVEEe58ptneKS62A",
        authDomain: "captainask.firebaseapp.com",
        databaseURL: "https://captainask-default-rtdb.firebaseio.com",
        projectId: "captainask",
        storageBucket: "captainask.appspot.com",
        messagingSenderId: "1017276689298",
        appId: "1:1017276689298:web:5eb98f106e1f33ae80ef4b",
        measurementId: "G-KJBK29V2B2"
    };



    firebase.initializeApp(firebaseConfig);
    var firestore = firebase.firestore();

    // Initialize the map and retrieve trip data
    initializeMap(firestore);


}).catch(error => {
    console.log('Error loading JSON file:', error);
});



// Call the function to load the Google Maps API

// Initialize the map
function initializeMap(firestore) {

    getTripData(firestore, map);
}


function getTripData(firestore, map) {

    if(trip.status == 'Finished'){
    //     alert('This trip is finished')
    // }else{
    //     var status = ['Started' ,'Accepted'];

    //     if ( status.includes(trip.status) ) {

        var tripRef = firestore.collection('trips').doc(tripId);
        var captainsRef = tripRef.collection('captains');
        captainsRef.get().then(function(querySnapshot) {
            querySnapshot.forEach(function(doc) {
                // Access individual captain document data
                var captainData = doc.data();
                $('#cptain_name').html(captainData.name)
                $('#rating').html(captainData.rate)
                $('#captain_image').attr('src','http://captainask.com/storage/'+captainData.image_url)
                setCaptainCarIcon(firestore, captainData.id ,map);
                listenForCaptainLocation(firestore, captainData.id, map);

            });
        })
        // .catch(function(error) {
        //     console.log('Error getting captains collection:', error);
        // });

        // }
        // else {
        // alert('Invalid request')
        // }
    }
}

function setCaptainCarIcon(firestore,  captain_id, map){

    // Define the captain images based on service_id

    var domain = window.location.host;

     var captainImages = {
        service_id_1: domain+'/Frontend/images/ServiceIcon/ride.png',
        service_id_2: domain+'/Frontend/images/ServiceIcon/big-ride.png',
        service_id_3: domain+'/Frontend/images/ServiceIcon/market.png',
        service_id_4: domain+'/Frontend/images/ServiceIcon/package.png',
        service_id_5: domain+'/Frontend/images/ServiceIcon/winch.png',
    };

    // Get the trip data, assuming you have the trip object with service_id
    var trip_data = {
        service_id: 'service_id_'+trip.service_id,
        // Other trip properties
    };

    // Set the captain's point image based on service_id
    var captainImage = captainImages[trip_data.service_id];

    // Get captain location
    getCaptainData(firestore, captain_id)
    .then(function(captainPoint) {
        console.log('Captain Point:', captainPoint,map);

       // Assuming you have the coordinates of the new marker
       marker = new google.maps.Marker({
            position: captainPoint,
            map: map,
            icon: {
                url: 'http://'+captainImage,
                scaledSize: new google.maps.Size(50, 50), // Adjust the size as needed
              },
            });
    })
    .catch(function(error) {
        console.log('Error getting captain data:', error);
    });
}


// Retrieve trip data from Firebase
function getCaptainData(firestore, captain_id) {
    return new Promise((resolve, reject) => {
        var collRef = firestore.collection('locations');
        collRef
          .where('id', '==', captain_id)
          .get()
          .then(function(querySnapshot) {
            querySnapshot.forEach(function(doc) {
              // Document data
              var documentData = doc.data();

              resolve({ lat: Number(documentData.lat), lng: Number(documentData.lng) });
            });
          })
          .catch(function(error) {
            reject(error);
          });
      });
}

// Listen for real-time updates on the captain's location
function listenForCaptainLocation(firestore, captainId, map) {

    var collRef = firestore.collection('locations');
    var query  = collRef.where('id', '==', captainId).get();

        query .then(function(querySnapshot) {

                querySnapshot.forEach(function(doc) {
                var documentData = doc.data();

                // Create a document reference for the captain
                var captainRef = collRef.doc(doc.id);

                captainRef.onSnapshot(function(snapshot) {
                var updatedDocumentData = snapshot.data();
                // Process the updated document data here
                console.log(updatedDocumentData);

                // Update the marker position and map
                var updatedMarkerPosition = { lat: updatedDocumentData.lat, lng: updatedDocumentData.lng };
                marker.setPosition(updatedMarkerPosition);

                if (map && map.panTo) {
                    // map.panTo(updatedMarkerPosition);
                }

            });
        });
    }).catch(function(error) {
    // Handle the error here
    console.log('Error getting captain data:', error);
    });

}
