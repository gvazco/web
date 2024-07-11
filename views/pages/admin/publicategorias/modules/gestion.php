<?php

if (isset($_GET["publicategory"])) {

    $select = "id_publicategory,name_publicategory,url_publicategory,icon_publicategory,image_publicategory,description_publicategory,keywords_publicategory";
    $url = "publicategories?linkTo=id_publicategory&equalTo=" . base64_decode($_GET["publicategory"]) . "&select=" . $select;
    $method = "GET";
    $fields = array();

    $publicategory = CurlController::request($url, $method, $fields);

    if ($publicategory->status == 200) {

        $publicategory = $publicategory->results[0];
    } else {

        $publicategory = null;
    }
} else {

    $publicategory = null;
}

?>


<div class="content pb-5">

    <div class="container">

        <div class="card">

            <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">

                <?php if (!empty($publicategory)) : ?>

                <input type="hidden" name="idPubliCategory"
                    value="<?php echo base64_encode($publicategory->id_publicategory) ?>">

                <?php endif ?>

                <div class="card-header">

                    <div class="container">

                        <div class="row">

                            <div class="col-12 col-lg-6 text-center text-lg-left">

                                <h4 class="mt-3">Agregar Categoría</h4>

                            </div>

                            <div class="col-12 col-lg-6 mt-2 d-none d-lg-block">

                                <button type="submit"
                                    class="btn border-0 templateColor float-right py-2 px-3 btn-sm rounded-pill">Guardar
                                    Información</button>

                                <a href="/admin/publicategorias"
                                    class="btn btn-default float-right py-2 px-3 btn-sm rounded-pill mr-2">Regresar</a>

                            </div>

                            <div class="col-12 text-center d-flex justify-content-center mt-2 d-block d-lg-none">

                                <div><a href="/admin/publicategorias"
                                        class="btn btn-default py-2 px-3 btn-sm rounded-pill mr-2">Regresar</a></div>

                                <div><button type="submit"
                                        class="btn border-0 templateColor py-2 px-3 btn-sm rounded-pill">Guardar
                                        Información</button></div>

                            </div>

                        </div>
                    </div>

                </div>

                <div class="card-body">

                    <?php

                    require_once "controllers/publicategories.controller.php";
                    $manage = new PubliCategoriesController();
                    $manage->publicategoryManage();

                    ?>


                    <!--=====================================
					PRIMER BLOQUE
					======================================-->

                    <div class="row row-cols-1">

                        <div class="col">

                            <div class="card">

                                <div class="card-body">

                                    <!--=====================================
									Título de la categoría
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="name_publicategory">Título <sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <input type="text" class="form-control" placeholder="Ingresar el título"
                                            id="name_publicategory" name="name_publicategory"
                                            onchange="validateDataRepeat(event,'publicategory')"
                                            <?php if (!empty($publicategory)) : ?> readonly <?php endif ?>
                                            value="<?php if (!empty($publicategory)) : ?><?php echo $publicategory->name_publicategory ?><?php endif ?>"
                                            required>

                                        <div class="valid-feedback">Válido.</div>
                                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                                    </div>

                                    <!--=====================================
									URL de la categoría
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="url_publicategory">URL <sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <input type="text" class="form-control" id="url_publicategory"
                                            name="url_publicategory"
                                            value="<?php if (!empty($publicategory)) : ?><?php echo $publicategory->url_publicategory ?><?php endif ?>"
                                            readonly required>

                                        <div class="valid-feedback">Válido.</div>
                                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                                    </div>

                                    <!--=====================================
									Icono de la categoría
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="icon_publicategory">Icono <sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <div class="input-group">

                                            <span class="input-group-text iconView">
                                                <i
                                                    class="<?php if (!empty($publicategory)) : ?><?php echo $publicategory->icon_publicategory ?><?php else : ?>fas fa-shopping-bag<?php endif ?>"></i>
                                            </span>

                                            <input type="text" class="form-control" id="icon_publicategory"
                                                name="icon_publicategory" onfocus="addIcon(event)"
                                                value="<?php if (!empty($publicategory)) : ?><?php echo $publicategory->icon_publicategory ?><?php else : ?>fas fa-shopping-bag<?php endif ?>"
                                                required>

                                            <div class="valid-feedback">Válido.</div>
                                            <div class="invalid-feedback">Por favor llena este campo correctamente.
                                            </div>

                                        </div>


                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!--=====================================
					SEGUNDO BLOQUE
					======================================-->

                    <div class="row row-cols-1 row-cols-md-2 pt-2">

                        <div class="col">

                            <div class="card">

                                <div class="card-body">

                                    <!--=====================================
									Descripción de la categoría
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="description_publicategory">Descripción<sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <textarea rows="9" class="form-control mb-3"
                                            placeholder="Ingresar la descripción" id="description_publicategory"
                                            name="description_publicategory" onchange="validateJS(event,'complete')"
                                            required><?php if (!empty($publicategory)) : ?><?php echo $publicategory->description_publicategory ?><?php endif ?></textarea>

                                        <div class="valid-feedback">Válido.</div>
                                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                                    </div>

                                    <!--=====================================
									Palabras claves de la categoría
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="keywords_publicategory">Palabras claves<sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <input type="text" class="form-control tags-input" data-role="tagsinput"
                                            placeholder="Ingresar las palabras claves" id="keywords_publicategory"
                                            name="keywords_publicategory" onchange="validateJS(event,'complete-tags')"
                                            value="<?php if (!empty($publicategory)) : ?><?php echo $publicategory->keywords_publicategory ?><?php endif ?>"
                                            required>

                                        <div class="valid-feedback">Válido.</div>
                                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col">

                            <div class="card">

                                <div class="card-body">

                                    <!--=====================================
									Imagen de la categoría
									======================================-->

                                    <div class="form-group pb-3 text-center">

                                        <label class="pb-3 float-left">Imagen de la Categoría<sup
                                                class="text-danger">*</sup></label>

                                        <label for="image_publicategory">


                                            <?php if (!empty($publicategory)) : ?>

                                            <input type="hidden"
                                                value="<?php echo $publicategory->image_publicategory ?>"
                                                name="old_image_publicategory">

                                            <img src="/views/assets/img/publicategories/<?php echo $publicategory->url_publicategory ?>/<?php echo $publicategory->image_publicategory ?>"
                                                class="img-fluid changeImage">

                                            <?php else : ?>

                                            <img src="/views/assets/img/publicategories/default/default-image.jpg"
                                                class="img-fluid changeImage">

                                            <?php endif ?>


                                            <p class="help-block small mt-3">Dimensiones recomendadas: 1000 x 600
                                                pixeles | Peso Max. 2MB | Formato: PNG o JPG</p>

                                        </label>

                                        <div class="custom-file">

                                            <input type="file" class="custom-file-input" id="image_publicategory"
                                                name="image_publicategory" accept="image/*" maxSize="2000000"
                                                onchange="validateImageJS(event,'changeImage')"
                                                <?php if (empty($publicategory)) : ?> required <?php endif ?>>

                                            <div class="valid-feedback">Válido.</div>
                                            <div class="invalid-feedback">Por favor llena este campo correctamente.
                                            </div>

                                            <label class="custom-file-label" for="image_publicategory">Buscar
                                                Archivo</label>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!--=====================================
					TERCER BLOQUE
					======================================-->

                    <div class="row row-cols-1 pt-2">

                        <div class="col">

                            <div class="card">

                                <div class="card-body col-md-6 offset-md-3">

                                    <!--=====================================
									Visor metadatos
									======================================-->

                                    <div class="form-group pb-3 text-center">

                                        <label>Visor Metadatos</label>

                                        <div class="d-flex justify-content-center">

                                            <div class="card">

                                                <div class="card-body">

                                                    <!--=====================================
													Visor imagen
													======================================-->

                                                    <figure class="mb-2">

                                                        <?php if (!empty($publicategory)) : ?>

                                                        <img src="/views/assets/img/publicategories/<?php echo $publicategory->url_publicategory ?>/<?php echo $publicategory->image_publicategory ?>"
                                                            class="img-fluid metaImg" style="width:100%">

                                                        <?php else : ?>

                                                        <img src="/views/assets/img/publicategories/default/default-image.jpg"
                                                            class="img-fluid metaImg" style="width:100%">

                                                        <?php endif ?>

                                                    </figure>

                                                    <!--=====================================
													Visor título
													======================================-->

                                                    <h6 class="text-left text-primary mb-1 metaTitle">

                                                        <?php if (!empty($publicategory)) : ?>
                                                        <?php echo $publicategory->name_publicategory ?>
                                                        <?php else : ?>
                                                        Lorem ipsum dolor sit
                                                        <?php endif ?>

                                                    </h6>

                                                    <!--=====================================
													Visor URL
													======================================-->

                                                    <p class="text-left text-success small mb-1">
                                                        <?php echo $path ?><span
                                                            class="metaURL"><?php if (!empty($publicategory)) : ?><?php echo $publicategory->url_publicategory ?><?php else : ?>lorem<?php endif ?></span>
                                                    </p>

                                                    <!--=====================================
													Visor Descripción
													======================================-->

                                                    <p class="text-left small mb-1 metaDescription">

                                                        <?php if (!empty($publicategory)) : ?>
                                                        <?php echo $publicategory->description_publicategory ?>
                                                        <?php else : ?>
                                                        Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                                                        Ducimus impedit ipsam obcaecati voluptas unde error quod odit ad
                                                        sapiente vitae.
                                                        <?php endif ?>
                                                    </p>

                                                    <!--=====================================
													Visor Palabras claves
													======================================-->

                                                    <p class="small text-left text-secondary metaTags">
                                                        <?php if (!empty($publicategory)) : ?>
                                                        <?php echo $publicategory->keywords_publicategory ?>
                                                        <?php else : ?>
                                                        lorem, ipsum, dolor, sit
                                                        <?php endif ?>
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer">

                    <div class="container">

                        <div class="row">

                            <div class="col-12 col-lg-6 text-center text-lg-left mt-lg-3">

                                <label class="font-weight-light"><sup class="text-danger">*</sup> Campos
                                    obligatorios</label>

                            </div>

                            <div class="col-12 col-lg-6 mt-2 d-none d-lg-block">

                                <button type="submit"
                                    class="btn border-0 templateColor float-right py-2 px-3 btn-sm rounded-pill">Guardar
                                    Información</button>

                                <a href="/admin/publicategorias"
                                    class="btn btn-default float-right py-2 px-3 btn-sm rounded-pill mr-2">Regresar</a>

                            </div>

                            <div class="col-12 text-center d-flex justify-content-center mt-2 d-block d-lg-none">

                                <div><a href="/admin/publicategorias"
                                        class="btn btn-default py-2 px-3 btn-sm rounded-pill mr-2">Regresar</a></div>

                                <div><button type="submit"
                                        class="btn border-0 templateColor py-2 px-3 btn-sm rounded-pill">Guardar
                                        Información</button></div>

                            </div>

                        </div>
                    </div>

                </div>


            </form>

        </div>

    </div>

</div>

<!--=====================================
Modal con librería de iconos
======================================-->

<div class="modal" id="myIcon">

    <div class="modal-dialog modal-lg modal-dialog-centered ">

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Cambiar Icono</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body mx-3">

                <input type="text" class="form-control mt-4 mb-3 myInputIcon" placeholder="Buscar Icono">

                <?php

                $data = file_get_contents($path . "views/assets/json/fontawesome.json");
                $icons = json_decode($data);

                ?>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 py-3"
                    style="overflow-y: scroll; overflow-x: hidden; height:500px">

                    <?php foreach ($icons as $key => $value) : ?>

                    <div class="col text-center py-4 btn btnChangeIcon" mode="<?php echo $value  ?>">
                        <i class="<?php echo $value ?> fa-2x"></i>
                    </div>

                    <?php endforeach ?>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">Salir</button>

            </div>

        </div>

    </div>

</div>