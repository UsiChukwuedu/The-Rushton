var amenitiesmap = {
  map: null,
  bounds: null,
  markers: [],
  infoWindow: null,
  mapCenterLat: 43.6545624,
  mapCenterLng: -79.4473932,

  init: function () {
    amenitiesmap.bounds = new google.maps.LatLngBounds();

    var initialMapCenter = {
      lat: amenitiesmap.mapCenterLat,
      lng: amenitiesmap.mapCenterLng,
    };

    var mapStyles = [
      {
        elementType: "geometry",
        stylers: [
          {
            color: "#bacec7",
          },
        ],
      },
      {
        elementType: "labels.icon",
        stylers: [
          {
            visibility: "off",
          },
        ],
      },
      {
        elementType: "labels.text.fill",
        stylers: [
          {
            color: "#2d6e5d",
          },
        ],
      },
      {
        elementType: "labels.text.stroke",
        stylers: [
          {
            color: "#f5f5f5",
          },
        ],
      },
      {
        featureType: "administrative.land_parcel",
        elementType: "labels.text.fill",
        stylers: [
          {
            color: "#bdbdbd",
          },
        ],
      },
      {
        featureType: "landscape",
        elementType: "labels",
        stylers: [
          {
            visibility: "off",
          },
        ],
      },
      {
        featureType: "poi",
        elementType: "geometry",
        stylers: [
          {
            visibility: "off",
          },
        ],
      },
      {
        featureType: "poi",
        elementType: "labels",
        stylers: [
          {
            visibility: "off",
          },
        ],
      },
      {
        featureType: "poi.business",
        elementType: "labels",
        stylers: [
          {
            visibility: "off",
          },
        ],
      },
      {
        featureType: "poi.park",
        elementType: "geometry",
        stylers: [
          {
            color: "#83afa4",
          },
          {
            visibility: "on",
          },
        ],
      },
      {
        featureType: "poi.park",
        elementType: "labels",
        stylers: [
          {
            visibility: "off",
          },
        ],
      },
      {
        featureType: "poi.park",
        elementType: "labels.text.fill",
        stylers: [
          {
            color: "#2d6e5d",
          },
        ],
      },
      {
        featureType: "road",
        elementType: "geometry",
        stylers: [
          {
            color: "#a3bdb4",
          },
          {
            weight: 1,
          },
        ],
      },
      {
        featureType: "road",
        elementType: "labels.text.fill",
        stylers: [
          {
            color: "#ffffff",
          },
        ],
      },
      {
        featureType: "road",
        elementType: "labels.text.stroke",
        stylers: [
          {
            color: "#75998d",
          },
          {
            visibility: "on",
          },
        ],
      },
      {
        featureType: "road.highway",
        elementType: "labels.text.fill",
        stylers: [
          {
            color: "#616161",
          },
        ],
      },
      {
        featureType: "road.local",
        elementType: "labels",
        stylers: [
          {
            visibility: "off",
          },
        ],
      },
      {
        featureType: "transit.line",
        elementType: "geometry",
        stylers: [
          {
            color: "#8db0a4",
          },
        ],
      },
      {
        featureType: "transit.station",
        elementType: "geometry",
        stylers: [
          {
            color: "#eeeeee",
          },
        ],
      },
      {
        featureType: "water",
        elementType: "geometry",
        stylers: [
          {
            color: "#c9c9c9",
          },
        ],
      },
      {
        featureType: "water",
        elementType: "labels.text.fill",
        stylers: [
          {
            color: "#9e9e9e",
          },
        ],
      },
      {
        featureType: "administrative.neighborhood",
        elementType: "labels",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "administrative.land_parcel",
        elementType: "labels",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "administrative.locality",
        elementType: "labels",
        stylers: [{ visibility: "off" }],
      },
    ];

    amenitiesmap.map = new google.maps.Map(document.getElementById("map"), {
      zoom: 14,
      center: initialMapCenter,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      scrollwheel: false,
      mapTypeControl: false,
      streetViewControl: false,
      panControl: true,
      zoomControl: true,
      styles: mapStyles,
    });

    amenitiesmap.infoWindow = new google.maps.InfoWindow();

    $(".map-nav-list-item .button").click(function (e) {
      amenitiesmap.clearMarkers();
      $(".map-nav-list-item.active").first().removeClass("active");
      $(e.target).parent().addClass("active");
      const amenityName = $(this).attr("data-amenity-name");
      $(".mobile-map-nav").val(amenityName);
      amenitiesmap.drawMarker(amenityName);
    });

    $(".mobile-map-nav").change(function (e) {
      amenitiesmap.clearMarkers();
      const amenityName = e.currentTarget.value;
      $(".map-nav-list-item.active").first().removeClass("active");
      $(`.button[data-amenity-name='${amenityName}']`)
        .first()
        .parent()
        .addClass("active");
      amenitiesmap.drawMarker(amenityName);
    });

    $(document).on("click", ".marker-list-item", function (e) {
      markerId = $(this).data("marker-id");
      google.maps.event.trigger(amenitiesmap.markers[markerId], "click");
    });

    var latlng = new google.maps.LatLng(
      parseFloat(amenitiesmap.mapCenterLat),
      parseFloat(amenitiesmap.mapCenterLng)
    );

    var icon = {
      url:
        site_url + "/wp-content/themes/sterlingjunction/img/sj-map-marker.svg",
      scaledSize: new google.maps.Size(80, 91), // scaled size
      origin: new google.maps.Point(0, 0), // origin
      anchor: new google.maps.Point(72, 64), // anchor \]\\
    };

    var markerSymp = new google.maps.Marker({
      map: amenitiesmap.map,
      position: latlng,
      icon: icon,
      zIndex: 8999,
    });
    amenitiesmap.drawMarker("all");
  },

  drawMarker: function (name) {
    amenitiesmap.bounds = new google.maps.LatLngBounds();
    let list = [];
    if (name === "all") {
      list = Object.values(markersList).flat();
    } else {
      list = markersList[name];
    }
    $.each(list, function (i, item) {
      var latlng = new google.maps.LatLng(
        parseFloat(list[i]["lat"]),
        parseFloat(list[i]["lng"])
      );
      amenitiesmap.createMarker(latlng, list[i]["name"]);
      amenitiesmap.bounds.extend(latlng);
    });

    var latlng = new google.maps.LatLng(
      parseFloat(amenitiesmap.mapCenterLat),
      parseFloat(amenitiesmap.mapCenterLng)
    );
    amenitiesmap.bounds.extend(latlng);

    amenitiesmap.map.fitBounds(amenitiesmap.bounds, 20);
  },

  createMarker: function (latlng, name) {
    var html = '<div class="amenitiy-map-marker">' + name + "</div>";
    var marker = new google.maps.Marker({
      map: amenitiesmap.map,
      position: latlng,
      zIndex: 9999,
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 8,
        strokeWeight: 1,
        strokeColor: "#8a8a8a",
        fillColor: "#000",
        fillOpacity: 1,
      },
    });

    google.maps.event.addListener(marker, "click", function () {
      amenitiesmap.infoWindow.setContent(html);
      amenitiesmap.infoWindow.open(amenitiesmap.map, marker);
    });
    amenitiesmap.markers.push(marker);
  },

  clearMarkers: function () {
    amenitiesmap.infoWindow.close();
    for (var i = 0; i < amenitiesmap.markers.length; i++) {
      amenitiesmap.markers[i].setMap(null);
    }
    amenitiesmap.markers.length = 0;
  },
};

$(window).load(function () {
  amenitiesmap.init();
});
