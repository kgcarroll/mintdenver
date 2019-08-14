(function () {
  "use strict";
  var activeClass = "active",
      activeCategory,
      categoriesContainer = $("#categories"),
      categoriesElement = "li",
      currentCategory,
      currentImageContainer,
      currentImageIndex = 0,
      currentLightboxIndex,
      galleryJSON,
      imageClass = "image",
      imageWidth = 0,
      imageContainer = $("#image-container"),
      imagesDivClass = "category-images",
      isMobileWidth = false,
      lightboxElement,
      lightboxMaxHeight = 0,
      nextButton = $("#next-image"),
      prevButton = $("#prev-image");

  function preloadImages(images,callback){
    for (var i = 0; i < images.length; i++){
      for (var j = 0; j < images[i].images.length; j++){
        var img = new Image();
        img.src = images[i].images[j].url;
        var img2 = new Image();
        img2.src = images[i].images[j].thumb;
      }
    }
    if (typeof callback != "undefined"){
      callback();
    }


  function addEvents(){
      nextButton.on("click",function(){
        incrementImage(1);
      });
      prevButton.on("click",function(){
        incrementImage(-1);
      });
      imageContainer.swipe({
        swipeLeft:function(e){
            incrementImage(1);
        },
        swipeRight:function(e){
            incrementImage(-1);
        },
        allowPageScroll:"vertical"
    });
  }

  function updateImageWidth(){
    imageWidth=imageContainer.width();
    imageContainer.find("."+imageClass).width(imageWidth);
  }


  function incrementImage(inc){
    var nextIndex = currentImageIndex + inc,
      finalIndex = currentCategory.images.length - 1;
    if (nextIndex < 0){
      nextIndex = finalIndex;
    } else if(nextIndex > finalIndex){
      nextIndex = 0;
    }
    currentImageIndex = nextIndex;
    currentImageContainer.find("." + activeClass).removeClass(activeClass);
    currentImageContainer.find("." + imageClass).eq(nextIndex).addClass(activeClass);
  }

  function incrementLightbox(inc){
    var nextIndex = currentLightboxIndex + inc,
      finalIndex = currentCategory.images.length - 1;
    if (nextIndex < 0){
      nextIndex = finalIndex;
    } else if(nextIndex > finalIndex){
      nextIndex = 0;
    }
    currentLightboxIndex = nextIndex;
    openLightbox(currentCategory.images[nextIndex]);
  }

  function closeLightbox(){
    lightboxElement.fadeOut();
  }

  function createLightbox(){
    var lightboxWrapper = $(document.createElement("div")),
        lightboxBackground = $(document.createElement("div")),
        lightboxContainer = $(document.createElement("div")),
        lightbox = $(document.createElement("div")),
        lightboxImg = $(document.createElement("img")),
        lightboxCaption = $(document.createElement("div")),
        lightboxClose = $(document.createElement("span")),
        lightboxNext = $(document.createElement("button")),
        nextIcon = $(document.createElement("i")),
        prevIcon = $(document.createElement("i")),
        lightboxPrev = $(document.createElement("button"));

    lightboxWrapper.attr({id:"lightbox-wrapper"});
    lightboxContainer.attr({id:"lightbox-container"});
    lightboxBackground.attr({id:"lightbox-background"});
    lightbox.attr({id:"lightbox"});
    lightboxCaption.attr({id:"lightbox-caption"});
    lightboxClose.attr({id:"lightbox-close"});
    lightboxClose.addClass('icon-close');
    // lightboxClose.html("X");

    // Build Next/Previous buttons
    nextIcon.addClass("fa fa-angle-right ease");
    prevIcon.addClass("fa fa-angle-left ease");
    lightboxNext.attr({id:"lightbox-next"});
    lightboxPrev.attr({id:"lightbox-prev"});
    lightboxNext.append(nextIcon);
    lightboxPrev.append(prevIcon);

    lightboxImg.css("max-height",lightboxMaxHeight);
    lightbox.append(lightboxImg,lightboxCaption,lightboxNext,lightboxPrev,lightboxClose);
    lightboxContainer.append(lightbox);
    lightboxWrapper.append(lightboxContainer,lightboxBackground);

    lightboxWrapper.fadeIn();

    lightboxBackground.on({
      "click":function(){
        closeLightbox();
      }
    });

    lightboxClose.on({
      "click":function(){
        closeLightbox();
      }
    });

    lightboxNext.on({
      "click":function(){
        incrementLightbox(1);
      }
    });

    lightboxPrev.on({
      "click":function(){
        incrementLightbox(-1);
      }
    });

    lightboxElement = lightboxWrapper;

    $("body").append(lightboxWrapper);
  }

  function openLightbox(image){
    if (typeof lightboxElement == "undefined"){
      createLightbox();
    } else if(lightboxElement.not(":visible")){
      lightboxElement.fadeIn();
    }
    // Set image source
    lightboxElement.find("img").attr({src:image.url,alt:image.alt});
    // Set caption 
    if (image.caption != ""){
      lightboxElement.find("#lightbox-caption").html(image.caption).show();
    } else{
      lightboxElement.find("#lightbox-caption").hide();
    }
    // Vertically center lightbox
    var lightboxContainer = lightboxElement.find("#lightbox-container");
    var targetT = ($(window).height() - lightboxContainer.height()) / 2;
    lightboxContainer.css("top",targetT);
  }

  function makeThumb(image,index){
      var imageCaptionDiv = $(document.createElement("div")),
          imageDiv = $(document.createElement("div")),
          imageDivClass = (index > 0) ? imageClass : imageClass + " " + activeClass,
          imageElement = $(document.createElement("img")),
          thumbElement = $(document.createElement("img"));

      imageDiv.addClass(imageDivClass);
      imageDiv.data("index",index);
      imageElement.attr({src:image.url,alt:image.alt});
      imageElement.addClass("full-image");
      thumbElement.attr({src:image.thumb,alt:image.alt});
      thumbElement.addClass("thumb-image");
      imageDiv.append(thumbElement,imageElement);
      imageDiv.on({
        "click":function(){

        // Don't open lightbox on mobile
        if (!isMobileWidth){
          currentLightboxIndex = $(this).data("index");
            openLightbox(image);
          }
        }
      });
      return imageDiv;
  }

  function populateImages(images){
    var imagesDiv = $(document.createElement("div"));
    imagesDiv.addClass(imagesDivClass + " " + activeClass);
    for (var i = 0; i < images.length; i++){
      var image = makeThumb(images[i], i);
      imagesDiv.append(image);
    }
    var prevCategory = currentImageContainer;
    currentImageContainer = imagesDiv;
    imageContainer.append(imagesDiv);
    if (typeof prevCategory != "undefined"){
      prevCategory.removeClass(activeClass).remove();
    }
  }

  function showCategoryImages(catElement,catObject){
    categoriesContainer.find(categoriesElement).removeClass(activeClass);
    catElement.addClass(activeClass);
    currentCategory = catObject;
    populateImages(catObject.images);
  }

  function formatCategoryName(name){
    return name.replace(/\s+/g, '-').toLowerCase();
  }

  function makeCategory(category,isActive){
    var categoryDiv = $(document.createElement("div")),
        categoryElement = $(document.createElement(categoriesElement));
    if (isActive){
      showCategoryImages(categoryElement,category);
    }
    categoryDiv.html(category.category);
    categoryDiv.addClass("text");
    categoryElement.append(categoryDiv);

    categoryElement.on({
      "click":function(){
        if (currentCategory.category != category.category){
          showCategoryImages($(this),category);
          document.location.hash = formatCategoryName(category.category);
        }
      }
    });
    return categoryElement;
  }

  function populateCategories(categories){
    var isActive = true;
    for (var i = 0; i < categories.length; i++){
      isActive = (typeof activeCategory != "undefined" && activeCategory == formatCategoryName(categories[i].category)) ? true : isActive;
      var category = makeCategory(categories[i], isActive);
      categoriesContainer.append(category);
      isActive = false;
    }
  }

  function init(){
    $.ajax({
      cache:false,
      url:templateURL + "/JSON/gallery.json",
      dataType:"json",
      success:function(data){
        galleryJSON = data;
        var callback=function(){
          populateCategories(galleryJSON);
        }
        preloadImages(galleryJSON,callback);
      }
    });
  }

  function determineMobileWidth(){
    isMobileWidth = ($(window).width() < 1024);  // Not tablet size!
  }

  function determineLightboxMaxHeight(){
    lightboxMaxHeight = $(window).height() * 0.8;
    if (typeof lightboxElement != "undefined"){
      lightboxElement.find("img").css("max-height", lightboxMaxHeight);
    }
  }

  $(document).ready(function(){
    determineMobileWidth();
    determineLightboxMaxHeight();
    init();
    addEvents();
    if (document.location.hash.length > 0){
      activeCategory = document.location.hash.substring(1);
    }
  });

  $(document).keydown(function(e) {
    switch(e.which) {
      case 37: // Left
        incrementImage(-1);
        break;
      case 39: // Right
        incrementImage(1);
        break;
      default: return; // Exit this handler for other keys
    }
    e.preventDefault(); // Prevent the default action (scroll / move caret)
  });

  $(window).on({
    resize:function(){
      determineMobileWidth();
      determineLightboxMaxHeight();
    }
  });
}());