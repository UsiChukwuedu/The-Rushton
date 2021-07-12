<div id="gMap"></div>
<script>
    var map;
    var pos;
    var isDraggable = $(document).outerWidth() > 1024 ? true : false;
    function initMap() {
        pos = {lat: <?php the_field('map_coordinates_latitude'); ?>, lng: <?php the_field('map_coordinates_longitude'); ?>};
        map = new google.maps.Map(document.getElementById('gMap'), {
        center: pos,
        scrollwheel: false,
        isDraggable: false,
        zoom: 13,
        styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]
        });
        // Icon
        var icon = {
            url: "<?php the_field('map_marker'); ?>",
            scaledSize: new google.maps.Size(50, 75)
        }
        // Marker
        var marker = new google.maps.Marker({
            position: pos,
            map: map,
            title: '',
            icon: icon
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQ8lQNzK3NW1lSdZ9OuaM-I2NaDxIChBo&amp;callback=initMap" async defer></script>