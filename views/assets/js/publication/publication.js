/*=============================================
FlexSlider
=============================================*/

function activateFlexSlider() {
  $("#carousel").flexslider({
    animation: "slide",
    controlNav: true,
    controlsContainer: false,
    directionNav: false,
    animationLoop: false,
    slideshow: true,
    itemWidth: 210,
    itemMargin: 5,
    asNavFor: "#slider",
  });

  $("#slider").flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    sync: "#carousel",
  });
}

activateFlexSlider();

/*=============================================
Cambiar variante
=============================================*/

$(document).on("change", ".changeVariant", function () {
  var variant = JSON.parse($(this).attr("variant"));
  // console.log("variant", variant);
  var url = $(this).attr("url");

  /*=============================================
    Cambiar la galeria de imagenes
    =============================================*/

  if (variant.type_publivariant == "gallery") {
    $(".blockQuantity").show();
    $(".pulseAnimation").parent().addClass("col-md-9");

    $(".blockMedia").html(`

			<div id="slider" class="flexslider" style="margin-bottom:-4px">
                <ul class="slides"></ul>
            </div>
            <div id="carousel" class="flexslider">
              <ul class="slides"></ul>
            </div>

		`);

    var count = 0;

    JSON.parse(variant.media_publivariant).forEach((e, i) => {
      count++;

      $("#slider .slides").append(`

				 <li><img src="/views/assets/img/products/${url}/${e}" class="img-thumbnail" /></li>

			 `);

      $("#carousel .slides").append(`

          <li><img src="/views/assets/img/products/${url}/${e}" class="img-thumbnail" /></li>

      `);

      if (JSON.parse(variant.media_publivariant).length == count) {
        activateFlexSlider();
      }
    });
  }

  /*=============================================
    Cambiar el video
    =============================================*/

  if (variant.type_publivariant == "video") {
    $(".blockQuantity").hide();
    $(".pulseAnimation").parent().removeClass("col-md-9");

    var idVideo = variant.media_publivariant.split("/").pop();

    $(".blockMedia").html(`

            <iframe width="100%" height="315" src="https://www.youtube.com/embed/${idVideo}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

        `);
  }
});

/*=============================================
Aplicar Sticky al bloque Media
=============================================*/

if (window.matchMedia("(min-width:768px)").matches) {
  var sticky = new Sticky(".blockMedia");
  var topMedia = $(".blockMedia").offset().top;

  $(window).scroll(function (event) {
    var scrollTop = $(window).scrollTop();

    var footerTop = $(".footerBlock").offset().top;

    var blockMedia = $(".blockMedia").height();

    if (scrollTop > footerTop - blockMedia) {
      $(".blockMedia")[0].sticky.active = false;

      $(".blockMedia").css({
        position: "relative",
        left: "0px",
        top: footerTop - (blockMedia + topMedia) + "px",
      });
    } else {
      $(".blockMedia")[0].sticky.active = true;
    }
  });
}
