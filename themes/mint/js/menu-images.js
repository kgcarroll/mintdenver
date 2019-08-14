(function () {
  "use strict";

  var images = null; // menu image data from JSON.


  function img_switcher() {
    // Trigger background when any of the a's inside of .menu-item are hovered
    $('.nav-inner-wrap .menu-item a').mouseover(function() {

      // Removes active class
      $('#menu-background div').removeClass('active');

      // Split up the URL to get the slug (this matches the image name).
      var slug = $(this).attr('href').split('/');

      // Compare the page-slug to the image name.
      $('#menu-background div.'+slug[3]).addClass('active');
    });

    // Trigger background when any of the a's inside of .menu-item are hovered
    $('.nav-inner-wrap .menu-item a').mouseout(function() {
      // Removes active class
      $('#menu-background div').removeClass('active');
    });

  }

  function create_img_div(image){

    // Step 4: Create a div
    var imgDiv = $(document.createElement('div'));

    // Step 5: Add Class to div.
    imgDiv.addClass('menu-img');
    imgDiv.addClass('ease');
    imgDiv.addClass(image.title);

    // Step 6: Add attributes (read: dynamic background image.)
    imgDiv.attr('style','background-image: url('+image.url+')');

    // Give me my element to be used in step 7.
    return imgDiv;
  }

  function loop_images(images) {
    for(var i=0; i<images.length; i++) {

      // Step 3: Run a function.
      var menuImage = create_img_div(images[i]);
      
      // Step 7: Append to background image container.
      $('#menu-background').append(menuImage);
    }
  }

  function init(){
    $.ajax({
      cache:false,
      url:templateURL + "/JSON/menu-images.json",
      dataType:"json",
      success:function(data){
        images = data;

        // Do step 2.
        loop_images(images);

        // Switcher
        img_switcher();
      }
    });
  }

  // Do stuff on document ready
  $(document).ready(function(){

    // Do step 1.
    init();

  });


}());