
    function initMap() {
        // Define the coordinates for South Cotabato, Philippines
        var southCotabato = { lat: 6.3174, lng: 124.8467 };

        // Create a map centered at South Cotabato
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 10,
            center: southCotabato
        });

        // Define locations and markers for tourist attractions
        var attractions = [
            { name: "Restaurant", location: { lat: 6.3794, lng: 124.8395 } },
            { name: "Mountain", location: { lat: 6.2833, lng: 124.8833 } },
            { name: "Swimming Pool", location: { lat: 6.3667, lng: 124.8000 } }
            // Add more attractions as needed
        ];

        // Add markers for each attraction
        attractions.forEach(function(attraction) {
            var marker = new google.maps.Marker({
                position: attraction.location,
                map: map,
                title: attraction.name
            });
        });
    }

