<?php

/*=============================================
Config de la paginación
 =============================================*/

$endAt = 12;

if (isset($routesArray[1]) && !empty($routesArray[1])) {

    $startAt = ($routesArray[1] - 1) * $endAt;
    $currentPage = $routesArray[1];
} else {

    $startAt = 0;
    $currentPage = 1;
}

/*=============================================
Traemos publicaciones relacionadas con categorias
=============================================*/

$url = "relations?rel=publications,publicategories&type=publication,publicategory&linkTo=url_publicategory&equalTo=" . $routesArray[0] . "&select=id_publication";
$totalPublications = CurlController::request($url, $method, $fields);

if ($totalPublications->status == 200) {

    $totalPublications = $totalPublications->total;

    if ($startAt > $totalPublications) {

        echo '<script>
	      window.location = "/404";
	    </script>';
    }

    $select = "id_publication,name_publication,url_publication,description_publication,date_created_publication";
    $url = "relations?rel=publications,publicategories&type=publication,publicategory&linkTo=url_publicategory&equalTo=" . $routesArray[0] . "&select=" . $select . "&startAt=" . $startAt . "&endAt=" . $endAt . "&orderBy=id_publication&orderMode=DESC";
    $method = "GET";
    $fields = array();

    $publications = CurlController::request($url, $method, $fields)->results;
} else {

    /*=============================================
	Traemos publications relacionados con subcategorias
	=============================================*/

    $url = "relations?rel=publications,publisubcategories&type=publication,publisubcategory&linkTo=url_publisubcategory&equalTo=" . $routesArray[0] . "&select=id_publication";
    $totalPublications = CurlController::request($url, $method, $fields);

    if ($totalPublications->status == 200) {

        $totalPublications = $totalPublications->total;

        if ($startAt > $totalPublications) {

            echo '<script>
		      window.location = "/404";
		    </script>';
        }

        $select = "id_publication,name_publication,url_publication,description_publication,date_created_publication";
        $url = "relations?rel=publications,publisubcategories&type=publication,publisubcategory&linkTo=url_publisubcategory&equalTo=" . $routesArray[0] . "&select=" . $select . "&startAt=" . $startAt . "&endAt=" . $endAt . "&orderBy=id_publication&orderMode=DESC";
        $method = "GET";
        $fields = array();

        $publications = CurlController::request($url, $method, $fields)->results;
    } else {

        /*=============================================
		Traemos las ultimas publicaciones
		=============================================*/

        if ($routesArray[0] == "last-publications") {

            $url = "relations?rel=publivariants,publications&type=publivariant,publication&select=id_publication";
            $totalPublications = CurlController::request($url, $method, $fields);

            if ($totalPublications->status == 200) {

                $totalPublications = $totalPublications->total;

                if ($startAt > $totalPublications) {

                    echo '<script>
				      window.location = "/404";
				    </script>';
                }

                $select = "id_publication,name_publication,url_publication,type_publivariant,media_publivariant,date_created_publication,description_publication";
                $url = "relations?rel=publivariants,publications&type=publivariant,publication&startAt=" . $startAt . "&endAt=" . $endAt . "&orderBy=id_publivariant&orderMode=DESC&select=" . $select;
                $method = "GET";
                $fields = array();

                $publications = CurlController::request($url, $method, $fields)->results;
                $publications[0]->check_publivariant = "yes";
            } else {

                echo '<script>
			      window.location = "/no-found";
			    </script>';
            }
        } else {

            /*=============================================
            Filtro de búsqueda
            =============================================*/

            $linkTo = ["name_publication", "keywords_publication", "name_publicategory", "keywords_publicategory", "name_publisubcategory", "keywords_publisubcategory"];
            $totalSearch = 0;

            foreach ($linkTo as $key => $value) {

                $totalSearch++;

                $url = "relations?rel=publications,publisubcategories,publicategories&type=publication,publisubcategory,publicategory&linkTo=" . $value . "&search=" . $routesArray[0] . "&select=id_publication";
                $totalPublications = CurlController::request($url, $method, $fields);

                if ($totalPublications->status == 200) {

                    $totalPublications = $totalPublications->total;

                    if ($startAt > $totalPublications) {

                        echo '<script>
				      window.location = "/404";
				    </script>';
                    }


                    $select = "id_publication,name_publication,url_publication,description_publication,date_created_publication";
                    $url = "relations?rel=publications,publisubcategories,publicategories&type=publication,publisubcategory,publicategory&linkTo=" . $value . "&search=" . $routesArray[0] . "&select=" . $select . "&startAt=" . $startAt . "&endAt=" . $endAt . "&orderBy=id_publication&orderMode=DESC";
                    $publications = CurlController::request($url, $method, $fields)->results;

                    break;
                }
            }

            if ($totalSearch == count($linkTo)) {

                /*=============================================
				Anulamos ingreso al catálogo
				=============================================*/

                echo '<script>
			      window.location = "/no-found";
			    </script>';
            }
        }
    }
}

/*=============================================
Traemos la primera variante de los publications y si existen favoritos para ese publication
=============================================*/

if (!empty($publications)) {

    foreach ($publications as $key => $value) {

        /*=============================================
		Traemos la primera variante
		=============================================*/

        if (!isset($publications[0]->check_publivariant)) {

            $select = "type_publivariant,media_publivariant";
            $url = "publivariants?linkTo=id_publication_publivariant&equalTo=" . $value->id_publication . "&select=" . $select;
            $publivariant = CurlController::request($url, $method, $fields)->results[0];

            $publications[$key]->type_publivariant = $publivariant->type_publivariant;
            $publications[$key]->media_publivariant = $publivariant->media_publivariant;
        }
    }
}

?>



<div class="container-fluid bg-light border">

    <div class="container clearfix">

        <div class="btn-group float-end <?php if (!empty($publications)) : ?> p-2 <?php else : ?> p-4 <?php endif ?>">

            <?php if (!empty($publications)) : ?>

            <button class="btn btn-default btnView bg-white" attr-type="grid" attr-index="6">

                <i class="fas fa-th fa-xs pe-1"></i>

                <span class="col-xs-0 float-end small mt-1">GRID</span>

            </button>

            <button class="btn btn-default btnView" attr-type="list" attr-index="6">

                <i class="fas fa-list fa-xs pe-1"></i>

                <span class="col-xs-0 float-end small mt-1">LIST</span>

            </button>

            <?php endif ?>

        </div>

    </div>

</div>


<div class="container-fluid bg-white">

    <div class="container">

        <!--=====================================
		Grid Preload
		======================================-->

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 pt-3 pb-4 preloadTrue">

            <?php for ($i = 0; $i < count($publications); $i++) : ?>

            <div class="col px-3 py-3">

                <div class="p-5 bg-preload" style="height: 285px;">
                    <div class="into-preload"></div>
                </div>

                <div class="p-3 bg-preload my-3">
                    <div class="into-preload"></div>
                </div>

                <div class="d-flex justify-content-between">

                    <div class="p-3 px-5 bg-preload">
                        <div class="into-preload"></div>
                    </div>

                    <div class="p-3 px-5 bg-preload">
                        <div class="into-preload"></div>
                    </div>

                </div>

            </div>

            <?php endfor ?>

        </div>

        <!-- GRID -->

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 pt-3 pb-4 grid-6 preloadFalse">

            <?php foreach ($publications as $key => $value) : ?>

            <div class="col px-3 py-3">

                <a href="/<?php echo $value->url_publication ?>">

                    <figure class="imgPublication">

                        <?php if ($value->type_publivariant == "gallery") : ?>

                        <img src="<?php echo $path ?>views/assets/img/publications/<?php echo $value->url_publication ?>/<?php echo json_decode($value->media_publivariant)[0] ?>"
                            class="img-fluid">

                        <?php else : $arrayYT = explode("/", $value->media_publivariant) ?>

                        <img src="http://img.youtube.com/vi/<?php echo end($arrayYT) ?>/maxresdefault.jpg"
                            class="img-fluid bg-light">

                        <?php endif ?>

                    </figure>

                    <h5><small class="text-uppercase text-muted"><?php echo $value->name_publication ?></small></h5>

                </a>

                <p class="small">

                    <?php

                        $date1 = new DateTime($value->date_created_publication);
                        $date2 = new DateTime(date("Y-m-d"));
                        $diff = $date1->diff($date2);

                        ?>

                    <?php if ($diff->days < 30) : ?>
                    <span class="badge badgeNew bg-warning text-uppercase text-white mt-1 p-2 badge-pill">Nuevo</span>
                    <?php endif ?>


                </p>

                <div class="clearfix">

                    <span class="float-end">

                        <div class="btn-group btn-group-sm">


                            <button type="button" class="btn btn-light border"
                                onclick="location.href='/<?php echo $value->url_publication ?>'">
                                <i class="fas fa-eye"></i>
                            </button>

                        </div>
                    </span>
                </div>

            </div>

            <?php endforeach ?>

        </div>

        <!-- LIST -->

        <div class="row list-6" style="display:none">

            <?php foreach ($publications as $key => $value) : ?>

            <div class="media border-bottom px-3 pt-4 pb-3 pb-lg-2">

                <a href="/<?php echo $value->url_publication ?>">

                    <figure class="imgPublication">

                        <?php if ($value->type_publivariant == "gallery") : ?>

                        <img src="<?php echo $path ?>views/assets/img/publications/<?php echo $value->url_publication ?>/<?php echo json_decode($value->media_publivariant)[0] ?>"
                            class="img-fluid" style="width:150px">

                        <?php else : $arrayYT = explode("/", $value->media_publivariant) ?>

                        <img src="http://img.youtube.com/vi/<?php echo end($arrayYT) ?>/maxresdefault.jpg"
                            class="img-fluid bg-light" style="width:150px">

                        <?php endif ?>

                    </figure>

                </a>

                <div class="media-body ps-3">

                    <a href="/<?php echo $value->url_publication ?>">
                        <h5><small class="text-uppercase text-muted"><?php echo $value->name_publication ?></small></h5>
                    </a>

                    <p class="small">

                        <?php

                            $date1 = new DateTime($value->date_created_publication);
                            $date2 = new DateTime(date("Y-m-d"));
                            $diff = $date1->diff($date2);

                            ?>

                        <?php if ($diff->days < 30) : ?>
                        <span
                            class="badge badgeNew bg-warning text-uppercase text-white mt-1 p-2 badge-pill">Nuevo</span>
                        <?php endif ?>

                    </p>

                    <p class="my-2"><?php echo $value->description_publication ?></p>

                    <div class="clearfix">

                        <span class="float-end">

                            <div class="btn-group btn-group-sm">

                                <!--============================================
									FAVORITOS
									============================================-->

                                <button type="button" class="btn btn-light border"
                                    onclick="location.href='/<?php echo $value->url_publication ?>'">
                                    <i class="fas fa-eye"></i>
                                </button>

                            </div>
                        </span>
                    </div>

                </div>

            </div>

            <?php endforeach ?>

        </div>

        <!-- PAGINACIÓN -->

        <div class="d-flex justify-content-center mt-3 mb-5">


            <div class="cont-pagination">

                <ul class="pagination" data-total-pages="<?php echo ceil($totalPublications / $endAt) ?>"
                    data-url-page="<?php echo $routesArray[0] ?>" data-current-page="<?php echo $currentPage ?>"></ul>

            </div>


        </div>

    </div>

</div>