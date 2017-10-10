<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Map with markers and table people</title>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<style>
    #map {
        height: 50%;
    }

    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
</style>
<body>
<div id="map"></div>
<hr>
<div class="container">
    <table id="people" class="table"></table>
</div>
</body>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZ43dwd9XvwI-_Htx9Ie6VtgsayHvUT7A&callback=initMap"></script>

<script>

    function initMap() {

        var urlApiPeople = '/map/people';
        var urlApiMarkers = '/map/markers';
        var urlMarker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|';
        var markerColorGreen = '008000';
        var distanceChangeColor = 10;
        
        var map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng('{{ $center->latitude }}', '{{ $center->longitude }}'),
            zoom: 10
        });

        var infoWindow = new google.maps.InfoWindow;

        var markers = {};
        var infoWindowOn = null;

        $.getJSON(urlApiMarkers, function (data) {

            $.each(data, function (key, item) {

                pinImage = null;

                if (item.distance < distanceChangeColor) {
                    var pinImage = new google.maps.MarkerImage(urlMarker + markerColorGreen,
                        new google.maps.Size(21, 34),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(10, 34));
                }

                var name = item.fio;
                var distance = 'Distance to Kremlin is ' + (parseInt(item.distance * 100)) / 100 + ' km.';
                var type = 'restaurant';

                var point = new google.maps.LatLng(
                    parseFloat(item.latitude),
                    parseFloat(item.longitude));

                var info = document.createElement('div');
                var strong = document.createElement('strong');

                strong.textContent = name;
                info.appendChild(strong);
                info.appendChild(document.createElement('br'));

                var text = document.createElement('text');
                text.textContent = distance;
                info.appendChild(text);

                var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    icon: pinImage
                });

                marker.addListener('click', function () {
                    infoWindow.setContent(info);
                    infoWindow.open(map, marker);
                    infoWindowOn = infoWindow;
                });

                markers[item.person_id] = marker;
            });
        });


        $.getJSON(urlApiPeople, function (data) {

            if (data) {
                var people = $('#people');
                people.append("<tr><th><label><input type='checkbox' id='checkbox-control' checked></label></th><th>Имя</th><th>Количество повторений имени</th></tr>");
                $.each(data, function (key, item) {
                    people.append("<tr><td><input name='"+ item.ids +"' class='person-unique' type='checkbox' checked></td><td>"+ item.name + "</td><td>" + item.repetitions + "</td></tr>");
                });
            }
        });

        var people = $('#people');

        people.on('click', '.person-unique', function () {

            var ids = this.name.split(','), checked = $(this).is(':checked');

            $.each(ids, function (key, person_id) {

                var marker = markers[person_id];

                if (checked) {
                    marker.setVisible(true);
                }
                else {
                    marker.setVisible(false);
                    infoWindowOff();
                }
            });
        });

        people.on('click', '#checkbox-control', function() {

            var inputCheckbox = $('#people, input:checkbox');

            if ($(this).is(':checked')) {
                inputCheckbox.prop('checked', true);
                markersSwitch(true);
            }
            else {
                inputCheckbox.prop('checked', false);
                markersSwitch(false);
            }
        });


        /**
         * @param isShow
         */
        function markersSwitch(isShow) {
            infoWindowOff();
            $.each(markers, function (id_person, marker) {
                marker.setVisible(isShow);
            });
        }

        function infoWindowOff() {
            if (infoWindowOn) infoWindowOn.close();
        }
    }

</script>