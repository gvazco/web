/*=============================================
Grid & List
=============================================*/

$(document).on("click", ".btnView", function () {
  var type = $(this).attr("attr-type");
  var btnType = $("[attr-type]");
  var index = $(this).attr("attr-index");

  if (type == "list") {
    $(".grid-" + index).hide();
    $(".list-" + index).show();
  }

  if (type == "grid") {
    $(".grid-" + index).show();
    $(".list-" + index).hide();
  }

  btnType.each(function (i) {
    if ($(btnType[i]).attr("attr-index") == index) {
      $(btnType[i]).removeClass("bg-white");
    }
  });

  $(this).addClass("bg-white");
});

/*=============================================
Paginación
=============================================*/

var target = $(".pagination");

if (target.length > 0) {
  target.each(function () {
    var el = $(this),
      totalPages = el.data("total-pages"),
      urlPage = el.data("url-page"),
      currentPage = el.data("current-page");

    el.twbsPagination({
      totalPages: totalPages,
      startPage: currentPage,
      visiblePages: 3,
      first: '<i class="fas fa-angle-double-left"></i>',
      last: '<i class="fas fa-angle-double-right"></i>',
      prev: '<i class="fas fa-angle-left"></i>',
      next: '<i class="fas fa-angle-right"></i>',
      onPageClick: function (event, page) {
        if (page == 1) {
          $(".page-item.first").css({ color: "#aaa" });
          $(".page-item.prev").css({ color: "#aaa" });
        }

        if (page == totalPages) {
          $(".page-item.next").css({ color: "#aaa" });
          $(".page-item.last").css({ color: "#aaa" });
        }
      },
    }).on("page", function (event, page) {
      window.location = "/" + urlPage + "/" + page;
    });
  });
}

/*=============================================
Función para buscar productos
=============================================*/

$(document).on("click", ".btnSearch", function () {
  var value = $(this)
    .parent()
    .parent()
    .children(".inputSearch")
    .val()
    .toLowerCase();

  value = value.replace(
    /[#\\;\\$\\&\\%\\=\\(\\)\\:\\,\\'\\"\\.\\¿\\¡\\!\\?\\]/g,
    ""
  );
  value = value.replace(/[ ]/g, "-");
  value = value.replace(/[á]/g, "a");
  value = value.replace(/[é]/g, "e");
  value = value.replace(/[í]/g, "i");
  value = value.replace(/[ó]/g, "o");
  value = value.replace(/[ú]/g, "u");
  value = value.replace(/[ñ]/g, "n");

  window.location = "/" + value;
});

/*=============================================
Función para buscar productos con tecla ENTER
=============================================*/

$(".inputSearch").keyup(function (event) {
  event.preventDefault();

  if (event.keyCode == 13 && $(".inputSearch").val() != "") {
    var value = $(".inputSearch").val().toLowerCase();

    value = value.replace(
      /[#\\;\\$\\&\\%\\=\\(\\)\\:\\,\\'\\"\\.\\¿\\¡\\!\\?\\]/g,
      ""
    );
    value = value.replace(/[ ]/g, "-");
    value = value.replace(/[á]/g, "a");
    value = value.replace(/[é]/g, "e");
    value = value.replace(/[í]/g, "i");
    value = value.replace(/[ó]/g, "o");
    value = value.replace(/[ú]/g, "u");
    value = value.replace(/[ñ]/g, "n");

    window.location = "/" + value;
  }
});
