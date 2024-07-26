<?php

include "views/modules/spinner.php";

?>
<?php

$select = "id_publication,name_publication,url_publication,info_publication";
$url = "relations?rel=publivariants,publications&type=publivariant,publication&linkTo=url_publication&equalTo=" . $routesArray[0] . "&select=" . $select;
$method = "GET";
$fields = array();

$publication = CurlController::request($url, $method, $fields);

if ($publication->status == 200) {

    $publication = $publication->results[0];
} else {

    echo '<script>
  window.location = "/404";
</script>';
}

/*=============================================
Traemos las variantes de los productos
=============================================*/

if (!empty($publication)) {

    $select = "*";
    $url = "publivariants?linkTo=id_publication_publivariant&equalTo=" . $publication->id_publication . "&select=" . $select;
    $publivariants = CurlController::request($url, $method, $fields)->results;

    $publication->publivariants = $publivariants;
}

?>

<link rel="stylesheet" href="<?php echo $path ?>views/assets/css/publication/publication.css">

<div class="container-fluid bg-white">

    <hr style="color:#000">

    <div class="container py-4">

        <div class="row row-cols-1 row-cols-md-2">

            <!--=====================================
        Título Producto Móvil
        ======================================-->

            <h1 class="d-block d-md-none text-center">
                <?php echo $publication->name_publication ?><br>
                <?php for ($i = 0; $i < 5; $i++) : ?>
                <span class="text-warning">★</span>
                <?php endfor ?>
            </h1>


            <!--=====================================
        Bloque Galería o Video
        ======================================-->

            <div class="col">

                <figure class="blockMedia">

                    <?php if ($publication->publivariants[0]->type_publivariant == "gallery") : ?>

                    <div id="slider" class="flexslider" style="margin-bottom:-4px">

                        <ul class="slides">

                            <?php foreach (json_decode($publication->publivariants[0]->media_publivariant) as $key => $value) : ?>

                            <li>
                                <img src="/views/assets/img/publications/<?php echo $publication->url_publication ?>/<?php echo $value ?>"
                                    class="img-thumbnail">
                            </li>

                            <?php endforeach ?>

                        </ul>

                    </div>

                    <div id="carousel" class="flexslider d-none d-md-block">

                        <ul class="slides">

                            <?php foreach (json_decode($publication->publivariants[0]->media_publivariant) as $key => $value) : ?>

                            <li>
                                <img src="/views/assets/img/publications/<?php echo $publication->url_publication ?>/<?php echo $value ?>"
                                    class="img-thumbnail">
                            </li>

                            <?php endforeach ?>

                        </ul>

                    </div>

                    <?php else :  $video = explode("/", $publication->publivariants[0]->media_publivariant); ?>

                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?php echo end($video) ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>

                    <?php endif ?>

                </figure>

            </div>

            <!--=====================================
        Bloque info del producto
        ======================================-->

            <div class="col">

                <!--=====================================
            Título
            ======================================-->

                <h1 class="d-none d-md-block text-center">
                    <?php echo $publication->name_publication ?>
                    <br>
                    <?php for ($i = 0; $i < 5; $i++) : ?>

                    <span class="text-warning">★</span>

                    <?php endfor ?>
                </h1>




                <!--=====================================
            Variantes
            ======================================-->

                <?php if (count($publication->publivariants) > 1) : ?>

                <div class="my-4">

                    <?php foreach ($publication->publivariants as $key => $value) : ?>

                    <label class="form-check-label" for="radio_<?php echo $key ?>">

                        <h4 class="text-center border rounded-pill py-2 px-4 btn bg-light">

                            <div class="form-check font-weight-bold">

                                <input type="radio" class="form-check-input changeVariant"
                                    variant='<?php echo json_encode($publication->publivariants[$key]) ?>'
                                    url="<?php echo $publication->url_publication ?>" id="radio_<?php echo $key ?>"
                                    value="option_<?php echo $key ?>" name="optradio" <?php if ($key == 0) : ?> checked
                                    <?php endif ?>>
                                <?php echo $value->description_publivariant ?>

                            </div>


                        </h4>

                    </label>

                    <?php endforeach ?>

                </div>

                <?php endif ?>



                <!--=====================================
            Descripción del producto
            ======================================-->

                <div class="text-center">

                    <?php echo $publication->info_publication ?>

                </div>


            </div>


        </div>

    </div>


</div>

<script src="<?php echo $path ?>views/assets/js/publication/publication.js"></script>