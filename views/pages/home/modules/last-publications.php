<?php

$select = "id_publication,name_publication,url_publication,type_publivariant,media_publivariant,date_created_publication,views_publication,description_publication";
$url = "relations?rel=publivariants,publications&type=publivariant,publication&startAt=0&endAt=4&orderMode=DESC&select=" . $select;
$method = "GET";
$fields = array();

$viewsPublications = CurlController::request($url, $method, $fields);


if ($viewsPublications->status == 200) {

    $viewsPublications = $viewsPublications->results;
} else {

    $viewsPublications = array();
}

if (count($viewsPublications) == 0) {

    return;
}



?>

<div class="container-fluid bg-light border">

    <div class="container clearfix">

        <div class="btn-group float-end p-2">

            <button class="btn btn-default btnView bg-white" attr-type="grid" attr-index="5">

                <i class="fas fa-th fa-xs pe-1"></i>

                <span class="col-xs-0 float-end small mt-1">GRID</span>

            </button>

            <button class="btn btn-default btnView" attr-type="list" attr-index="5">

                <i class="fas fa-list fa-xs pe-1"></i>

                <span class="col-xs-0 float-end small mt-1">LIST</span>

            </button>

        </div>

    </div>

</div>


<div class="container-fluid bg-white">

    <div class="container">

        <div class="clearfix pt-3 pb-0 px-2">

            <h4 class="float-start text-uppercase pt-2">Publicaciones más recientes</h4>

            <span class="float-end">

                <a href="/last-publications" class="btn btn-default templateColor">

                    <small>VER MÁS <i class="fas fa-chevron-right"></i></small>

                </a>

            </span>

        </div>

        <hr style="color:#666">

        <!-- GRID -->

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 pt-3 pb-4 grid-5">

            <?php foreach ($viewsPublications as $key => $value) : ?>

            <div class="col px-3 py-2 py-lg-0">

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

                    <span class="float-end">



                        <button type="button" class="btn btn-light border"
                            onclick="location.href='/<?php echo $value->url_publication ?>'">
                            <i class="fas fa-eye"></i>
                        </button>

                    </span>
                </p>

            </div>

            <?php endforeach ?>

        </div>

        <!-- LIST -->

        <div class="row list-5" style="display:none">

            <?php foreach ($viewsPublications as $key => $value) : ?>

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
                        <span class="float-end">



                            <button type="button" class="btn btn-light border"
                                onclick="location.href='/<?php echo $value->url_publication ?>'">
                                <i class="fas fa-eye"></i>
                            </button>


                        </span>


                    </p>

                    <p class="my-2"><?php echo $value->description_publication ?></p>


                </div>

            </div>

            <?php endforeach ?>

        </div>

    </div>

</div>