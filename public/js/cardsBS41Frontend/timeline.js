var this_frontend_home_url
var this_event_id
var this_csrf_token
var this_mapEvents
var this_default_latitude
var this_default_longitude
var this_event_name
var this_event_start_date
var this_event_end_date

var this_event_longitude
var this_event_latitude

function frontendTimeline(page, paramsArray) {  // constructor of frontend Timeline's editor - set all params from server
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
    this_mapEvents = paramsArray.mapEvents;
    this_default_latitude = paramsArray.default_latitude;
    this_default_longitude = paramsArray.default_longitude;
    this_event_name = paramsArray.event_name;
    this_event_start_date = paramsArray.event_start_date;
    this_event_end_date = paramsArray.event_end_date;
    this_event_longitude = paramsArray.event_longitude;
    this_event_latitude = paramsArray.event_latitude;

    if (page == "single_event") {
        this_event_id = paramsArray.event_id;
        // this.LoadTimelineItems()
    }
} // function frontendTimeline(Params) {  constructor of frontend Timeline's editor - set all params from server


frontendTimeline.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    // alert( "onFrontendPageInit  page::"+var_dump(page) )
    frontendInit()

    if (page == "single_event") {
        this.initSingleEventMap()
    }
    if (page == "events_listing") {
        this.initEventsListingMap()
    }
} // frontendTimeline.prototype.onFrontendPageInit= function(page) {



frontendTimeline.prototype.initSingleEventMap = function () {
    // console.log("typeof this_event_longitude::")
    // console.log( typeof this_event_longitude )
    // console.log("typeof this_event_latitude::")
    // console.log( typeof this_event_latitude )

    if ( typeof this_event_longitude != "undefined" && this_event_longitude != null && typeof this_event_latitude != "undefined" && this_event_latitude != null ) {
        $("#div_map_wrapper").css("display","block")

        var markerPosition = { lat: parseFloat(this_event_latitude), lng: parseFloat(this_event_longitude) };
        var map = new google.maps.Map(document.getElementById('div_map'), {
            zoom: 10,
            center: markerPosition,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID]
            },
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        var infowindow = new google.maps.InfoWindow({
            content: this_event_name + ": <strong>"+this_event_start_date+"-"+this_event_end_date+"</strong>"
        });

        var marker = new google.maps.Marker({
            position: markerPosition,
            draggable: false,
            map: map
        });

        google.maps.event.addListener(marker, 'mouseover', function() {
            infowindow.open(map,marker);
        });

    } // if ( typeof this_event_longitude != "undefined" && this_event_longitude != null && typeof this_event_latitude != "undefined" && this_event_latitude != null ) {

}  // frontendTimeline.prototype.initSingleEventMap = function () {


frontendTimeline.prototype.initEventsListingMap = function () {


    console.log("this_mapEvents::")
    console.log( this_mapEvents )


    console.log("this_default_latitude::")
    console.log( this_default_latitude )


    console.log("this_default_longitude::")
    console.log( this_default_longitude )


    // source https://codepen.io/ahmadawais/pen/NQdWQx?editors=1010
    var map = new google.maps.Map(document.getElementById('div_map'), {
        zoom: 12,
        center: new google.maps.LatLng(this_default_latitude, this_default_longitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var marker, i;

    for (i = 0; i < this_mapEvents.length; i++) {
        let infowindow = new google.maps.InfoWindow({
            content: this_mapEvents[i].event_name + ": <strong>" + this_mapEvents[i].event_start_date + "-" + this_mapEvents[i].event_end_date + "</strong>"
        });

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(this_mapEvents[i].longitude, this_mapEvents[i].latitude),
            map: map
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(this_mapEvents[i].location);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }



    return;




    console.log("this_mapEvents::")
    console.log( this_mapEvents )

    $("#div_map_wrapper").css("display","block")

    var map = new google.maps.Map(document.getElementById('div_map'), {
        zoom: 12,
        // center: markerPosition,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID]
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    });

    this_mapEvents.map((mapEvent,index) => {
        // console.log("mapEvent::")
        // console.log( mapEvent )
        //

        if ( typeof mapEvent.longitude != "undefined" && mapEvent.longitude != null && typeof mapEvent.latitude != "undefined" && mapEvent.latitude != null ) {
            console.log("INSIDE map::")
            console.log( map )

            let markerPosition = {lat: parseFloat(mapEvent.latitude), lng: parseFloat(mapEvent.longitude)};
            console.log("step -1 ::")

            let infowindow = new google.maps.InfoWindow({
                content: mapEvent.event_name + ": <strong>" + mapEvent.event_start_date + "-" + mapEvent.event_end_date + "</strong>"
            });
            console.log("step -2 ::")

            let marker = new google.maps.Marker({
                position: markerPosition,
                draggable: false,

                map: map
            });
            console.log("step -3 ::")
            google.maps.event.addListener(marker, 'mouseover', function () {
                infowindow.open(map, marker);
            });
            console.log("step -4 ::")
        } // if ( typeof mapEvent.longitude != "undefined" && mapEvent.longitude != null && typeof mapEvent.latitude != "undefined" && mapEvent.latitude != null ) {

    });


    return;

    console.log("typeof this_event_longitude::")
    console.log( typeof this_event_longitude )
    console.log("typeof this_event_latitude::")
    console.log( typeof this_event_latitude )

    if ( typeof this_event_longitude != "undefined" && this_event_longitude != null && typeof this_event_latitude != "undefined" && this_event_latitude != null ) {


        google.maps.event.addListener(marker, 'mouseover', function() {
            infowindow.open(map,marker);
        });

    }

}  // frontendTimeline.prototype.initEventsListingMap = function () {




