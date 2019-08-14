(function() {
  "use strict";

  var bounds = null, // Google maps bounds for fitting all POIs on map
      categories = null, // Categories data from JSON
      categories_container = $("#categories"),
      map_marker = null, // Default map marker info from JSON
      infoWindow = null, // Opened infoWindow
      map = null, // Google maps element
      mobile_category_label = $("#mobile-label"),
      mobile_category_overlay = $("#mobile-category-overlay"),
      property = null, // Property info from JSON
      property_coordinates = null, // Property LatLng
      isMobileWidth = false;

  function checkMobileWidth(){
    isMobileWidth = ($(window).width() <= 768);
  }

  function fit_map_to_bounds(){
    map.fitBounds(bounds); // Fit map to show all visible POIs

    // Zoom out if map has just single POI.
    var listener = google.maps.event.addListener(map, "idle", function() {
      if (map.getZoom() > 18) map.setZoom(18);
      google.maps.event.removeListener(listener);
    });
  }

  function init_categories(){
    for (var i in categories){
      map_marker = categories[i].marker;  // Assign categoty marker(s);
      var category = build_category_elements(categories[i]);
      categories[i].element = category;
      build_category_pois(categories[i]);
      categories_container.append(category);
    }
    fit_map_to_bounds();
  }

  function build_category_elements(cat){
    var cat_li = $(document.createElement("li")),
        cat_span = $(document.createElement("span"));
    cat_span.addClass('ease');
    cat_li.addClass('ease');
    cat_span.html(cat.name);
    cat_li.append(cat_span);
    cat_span.on({
        click:function(){
          show_category(cat);
          if (mobile_category_overlay.is(":visible")){
            mobile_category_overlay.trigger("click");
            mobile_category_label.slideUp();
          }
          if(isMobileWidth) {
            categories_container.slideToggle();
          }
        }
    });
    return cat_li;
  }

  function build_category_pois(cat){
    for (var i in cat.pois){
      var poi = build_poi(cat.pois[i]);
      bounds.extend(poi.position);
      cat.pois[i].marker = poi;
    }
  }

  function build_poi(poi){
    var marker_icon = null;
    if (map_marker){
      marker_icon = {
        url: map_marker.url,
        size: new google.maps.Size(map_marker.width, map_marker.height),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(map_marker.width / 2, map_marker.height / 2)
      }
    }
    var coordinates = new google.maps.LatLng(poi.lat,poi.lng),
        map_link = '<div class="website"><a href="http://www.' + poi.url + '" target="_blank">'+ poi.url +'</a></div>',
        directions_link = '<div class="directions"><a href="http://maps.google.com/?q=' + poi.address + '" target="_blank">Directions</a></div>',
        description = (poi.description !== "") ? '<div class="description-container">' + poi.description + '</div>' : '',
        infowindow = new google.maps.InfoWindow({
          content: '<div class="info-window"><div class="name"><strong>' + poi.name + '</strong></div><div class="address">' + poi.address.replace(", ","<br />") + '</div>' + map_link + directions_link + '</div>'
        }),
        marker = new google.maps.Marker({
            icon: marker_icon,
            infoWindow: infowindow,
            map: map,
            position: coordinates,
            title: poi.name
        });
    google.maps.event.addListener(marker, 'click', function(event) {
      if (infoWindow){
        infoWindow.close();
      }
      marker.infoWindow.open(map, marker);
      infoWindow = marker.infoWindow;
    });

    return marker;
  }

  function show_category(cat){
    // Reset bounds
    bounds = new google.maps.LatLngBounds(property_coordinates);

    // Loop through categories, hiding all POIs except those in the current category
    for (var i in categories){
      var set_map = (categories[i].name != cat.name) ? null : map, // check if this is the current category, hide all POIs if not, otherwise show POIs
          category_class = (categories[i].name != cat.name) ? '' : 'active'; // set active class for current category
          

      if (typeof categories[i].element != "undefined"){
        categories[i].element.removeClass();
        if (category_class != ''){
          categories[i].element.addClass(category_class);
        }
      }
      for (var j in categories[i].pois){
        if (typeof categories[i].pois[j].marker != "undefined"){
          categories[i].pois[j].marker.setMap(set_map);
          if (set_map){
            bounds.extend(categories[i].pois[j].marker.position);
          }
        }
      }
    }

    fit_map_to_bounds();
  }

  function init_property_marker(){
    if (property){
      property_coordinates = new google.maps.LatLng(property.lat,property.lng);
      var property_icon = {
            url: property.property_map_marker.url,
            size: new google.maps.Size(property.property_map_marker.width,property.property_map_marker.height),
            origin: new google.maps.Point(0,0),
            anchor: new google.maps.Point(property.property_map_marker.width/2,property.property_map_marker.height/2)
          },
      map_link = '<a href="http://maps.google.com/?q=' + property.address + ', ' + property.city + ', ' + property.state + ' ' + property.zip + '" target="_blank">Directions</a>',
      property_infowindow = new google.maps.InfoWindow({
        content: '<div class="info-window"><div class="name"><strong>' + property.property_name + '</strong></div><div class="address">' + property.address.replace(", ","<br />") + ',<br />' + property.city + ', ' + property.state + ' ' + property.zip + '</div>' + map_link + '</div>'
      }),
      property_marker = new google.maps.Marker({
        icon: property_icon,
        infoWindow: property_infowindow,
        map: map,
        position: property_coordinates,
        title: property.property_name
      });
      google.maps.event.addListener(property_marker, 'click', function(event) {
        if (infoWindow){
          infoWindow.close();
        }
        property_marker.infoWindow.open(map, property_marker);
        infoWindow = property_marker.infoWindow;
      });
      bounds = new google.maps.LatLngBounds(property_coordinates);
      init_categories();
      return property_marker;
    }
  }

  function init_map(){
    var coordinates = null;
    if (property) {
      coordinates = new google.maps.LatLng(property.lat, property.lng);
    }
    var MY_MAPTYPE_ID = 'custom_style',
        map_style = [{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#7dcdcd"}]}],
    
    map_options = {
      center: coordinates,
      zoom: 14,
      scrollwheel:false,
      streetViewControl:false,
      zoomControlOptions:{
        style:google.maps.ZoomControlStyle.SMALL,
        position:google.maps.ControlPosition.LEFT_CENTER
      },
      mapTypeId: MY_MAPTYPE_ID,
      draggable: true
    },
    custom_map_type = new google.maps.StyledMapType(map_style, {
      name:MY_MAPTYPE_ID
    });
    map = new google.maps.Map(document.getElementById('map'),map_options);
    map.mapTypes.set(MY_MAPTYPE_ID, custom_map_type);
    init_property_marker();
  }

  function init(){
    $.ajax({
      cache:false,
      url:templateURL + "/JSON/neighborhood.json",
      dataType:"json",
      success:function(data){
        categories = data.categories;
        console.log(data);
        property = data.property;

        //
        // Create "All" category from other categories
        //
        // var allCategories = {
        //   name: "All",
        //   pois:[]
        // };

        // // Loop through data and get existing category pois, and push them to into "allCategories" array.
        // for (var i=0; i<categories.length; i++){
        //   for (var j=0; j<categories[i].pois.length; j++){
        //     allCategories.pois.push(categories[i].pois[j]);
        //   }
        // }
        // categories.unshift(allCategories);
        // 
        // End of "All" categories addition.
        //

        init_map();
      }
    });

    mobile_category_label.on("click",function(){
      categories_container.slideToggle();
    });

    if(isMobileWidth) {
      categories_container.css('display', 'block');
    }

  }

  $(document).ready(function(){
    init();
    checkMobileWidth();
  });

  // On Window resize
  $(window).on({
    resize:function(){
      checkMobileWidth();
    }
  })

})();