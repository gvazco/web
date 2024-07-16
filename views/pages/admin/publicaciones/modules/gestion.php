<?php

if (isset($_GET["publication"])) {

    $select = "id_publication,name_publication,url_publication,image_publication,description_publication,keywords_publication,id_publicategory_publication,id_publisubcategory_publication,name_publisubcategory,info_publication";
    $url = "relations?rel=publications,publisubcategories&type=publication,publisubcategory&linkTo=id_publication&equalTo=" . base64_decode($_GET["publication"]) . "&select=" . $select;
    $method = "GET";
    $fields = array();

    $publication = CurlController::request($url, $method, $fields);

    if ($publication->status == 200) {

        $publication = $publication->results[0];
    } else {

        $publication = null;
    }
} else {

    $publication = null;
}


?>


<div class="content pb-5">

    <div class="container">

        <div class="card">

            <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">

                <?php if (!empty($publication)) : ?>

                <input type="hidden" name="idPublication"
                    value="<?php echo base64_encode($publication->id_publication) ?>">

                <?php endif ?>

                <div class="card-header">

                    <div class="container">

                        <div class="row">

                            <div class="col-12 col-lg-6 text-center text-lg-left">

                                <h4 class="mt-3">Agregar Publicación</h4>

                            </div>

                            <div class="col-12 col-lg-6 mt-2 d-none d-lg-block">

                                <button type="submit"
                                    class="btn border-0 templateColor float-right py-2 px-3 btn-sm rounded-pill saveBtn">Guardar
                                    Información</button>

                                <a href="/admin/publicaciones"
                                    class="btn btn-default float-right py-2 px-3 btn-sm rounded-pill mr-2">Regresar</a>

                            </div>


                            <div class="col-12 text-center d-flex justify-content-center mt-2 d-block d-lg-none">

                                <div><a href="/admin/publicaciones"
                                        class="btn btn-default py-2 px-3 btn-sm rounded-pill mr-2">Regresar</a></div>

                                <div><button type="submit"
                                        class="btn border-0 templateColor py-2 px-3 btn-sm rounded-pill saveBtn">Guardar
                                        Información</button></div>

                            </div>

                        </div>
                    </div>

                </div>

                <div class="card-body">

                    <?php

                    require_once "controllers/publications.controller.php";
                    $manage = new publicationsController();
                    $manage->publicationManage();

                    ?>


                    <!--=====================================
					PRIMER BLOQUE
					======================================-->

                    <div class="row row-cols-1 row-cols-md-2">

                        <div class="col">

                            <div class="card">

                                <div class="card-body">

                                    <!--=====================================
									Seleccionar la categoría
									======================================-->

                                    <div class="form-group pb-3">

                                        <?php if (!empty($publication)) : ?>

                                        <input type="hidden" name="old_id_publicategory_publication"
                                            value="<?php echo base64_encode($publication->id_publicategory_publication) ?>">

                                        <?php endif ?>

                                        <label for="id_publicategory_publication">Seleccionar Categoría<sup
                                                class="text-danger">*</sup></label>

                                        <?php

                                        $url = "publicategories?select=id_publicategory,name_publicategory";
                                        $method = "GET";
                                        $fields = array();

                                        $categories = CurlController::request($url, $method, $fields);

                                        if ($categories->status == 200) {

                                            $categories = $categories->results;
                                        } else {

                                            $categories = array();
                                        }

                                        ?>

                                        <select class="custom-select" name="id_publicategory_publication"
                                            id="id_publicategory_publication" onchange="changePubliCategory(event)"
                                            required>

                                            <option value="">Selecciona Categoría</option>

                                            <?php foreach ($categories as $key => $value) : ?>

                                            <option value="<?php echo $value->id_publicategory ?>"
                                                <?php if (!empty($publication) && $publication->id_publicategory_publication == $value->id_publicategory) : ?>
                                                selected <?php endif ?>><?php echo $value->name_publicategory ?>
                                            </option>

                                            <?php endforeach ?>

                                        </select>

                                    </div>

                                    <!--=====================================
									Seleccionar la subcategoría
									======================================-->

                                    <div class="form-group pb-3">

                                        <?php if (!empty($publication)) : ?>

                                        <input type="hidden" name="old_id_publisubcategory_publication"
                                            value="<?php echo base64_encode($publication->id_publisubcategory_publication) ?>">

                                        <?php endif ?>

                                        <label for="id_publisubcategory_publication">Seleccionar Subcategoría<sup
                                                class="text-danger">*</sup></label>

                                        <select class="custom-select" name="id_publisubcategory_publication"
                                            id="id_publisubcategory_publication" required>

                                            <?php if (!empty($publication)) : ?>

                                            <option value="<?php echo $publication->id_publisubcategory_publication ?>">
                                                <?php echo $publication->name_publisubcategory ?></option>

                                            <?php else : ?>

                                            <option value="">Selecciona primero una Categoría</option>

                                            <?php endif ?>

                                        </select>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col">

                            <div class="card">

                                <div class="card-body">

                                    <!--=====================================
									Título del publicationo
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="name_publication">Título <sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <input type="text" class="form-control" placeholder="Ingresar el título"
                                            id="name_publication" name="name_publication"
                                            onchange="validateDataRepeat(event,'publication')"
                                            <?php if (!empty($publication)) : ?> readonly <?php endif ?>
                                            value="<?php if (!empty($publication)) : ?><?php echo $publication->name_publication ?><?php endif ?>"
                                            required>

                                        <div class="valid-feedback">Válido.</div>
                                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                                    </div>

                                    <!--=====================================
									URL del publicationo
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="url_publication">URL <sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <input type="text" class="form-control" id="url_publication"
                                            name="url_publication"
                                            value="<?php if (!empty($publication)) : ?><?php echo $publication->url_publication ?><?php endif ?>"
                                            readonly required>

                                        <div class="valid-feedback">Válido.</div>
                                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

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
									Descripción del publicationo
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="description_publication">Descripción<sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <textarea rows="9" class="form-control mb-3"
                                            placeholder="Ingresar la descripción" id="description_publication"
                                            name="description_publication" onchange="validateJS(event,'complete')"
                                            required><?php if (!empty($publication)) : ?><?php echo $publication->description_publication ?><?php endif ?></textarea>

                                        <div class="valid-feedback">Válido.</div>
                                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                                    </div>

                                    <!--=====================================
									Palabras claves del publication
									======================================-->

                                    <div class="form-group pb-3">

                                        <label for="keywords_publication">Palabras claves<sup
                                                class="text-danger font-weight-bold">*</sup></label>

                                        <input type="text" class="form-control tags-input" data-role="tagsinput"
                                            placeholder="Ingresar las palabras claves" id="keywords_publication"
                                            name="keywords_publication" onchange="validateJS(event,'complete-tags')"
                                            value="<?php if (!empty($publication)) : ?><?php echo $publication->keywords_publication ?><?php endif ?>"
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
									Imagen del publication
									======================================-->

                                    <div class="form-group pb-3 text-center">

                                        <label class="pb-3 float-left">Imagen del publicationo<sup
                                                class="text-danger">*</sup></label>

                                        <label for="image_publication">


                                            <?php if (!empty($publication)) : ?>

                                            <input type="hidden" value="<?php echo $publication->image_publication ?>"
                                                name="old_image_publication">

                                            <img src="/views/assets/img/publications/<?php echo $publication->url_publication ?>/<?php echo $publication->image_publication ?>"
                                                class="img-fluid changeImage">

                                            <?php else : ?>

                                            <img src="/views/assets/img/publications/default/default-image.jpg"
                                                class="img-fluid changeImage">

                                            <?php endif ?>


                                            <p class="help-block small mt-3">Dimensiones recomendadas: 1000 x 600
                                                pixeles | Peso Max. 2MB | Formato: PNG o JPG</p>

                                        </label>

                                        <div class="custom-file">

                                            <input type="file" class="custom-file-input" id="image_publication"
                                                name="image_publication" accept="image/*" maxSize="2000000"
                                                onchange="validateImageJS(event,'changeImage')"
                                                <?php if (empty($publication)) : ?> required <?php endif ?>>

                                            <div class="valid-feedback">Válido.</div>
                                            <div class="invalid-feedback">Por favor llena este campo correctamente.
                                            </div>

                                            <label class="custom-file-label" for="image_publication">Buscar
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

                                <div class="card-body">

                                    <div class="form-group mx-auto" style="max-width:700px">

                                        <!--=====================================
										Información del publication
										======================================-->

                                        <label for="info_publication">Información del publicationo<sup
                                                class="text-danger">*</sup></label>

                                        <textarea class="summernote" name="info_publication" id="info_publication"
                                            required>
							            <?php if (!empty($publication)) : ?>
							            	<?php echo $publication->info_publication ?>
							            <?php endif ?>
				
							            </textarea>

                                        <div class="valid-feedback">Válido.</div>
                                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                    <!--=====================================
					CUARTO BLOQUE
					======================================-->

                    <div class="row row-cols-1 pt-2 variantList">

                        <div class="col">

                            <div class="card">

                                <div class="card-body">

                                    <?php if (!empty($publication)) :  ?>


                                    <?php

                                        $url = "variants?linkTo=id_publication_variant&equalTo=" . $publication->id_publication;
                                        $method = "GET";
                                        $fields = array();

                                        $variants = CurlController::request($url, $method, $fields);

                                        if ($variants->status == 200) {

                                            $variants = $variants->results;
                                        } else {

                                            $variants = array();
                                        }

                                        ?>

                                    <?php else : $variants = array(); ?>



                                    <?php endif ?>

                                    <?php if (count($variants) > 0) : ?>

                                    <input type="hidden" name="totalPubliVariants"
                                        value="<?php echo count($variants) ?>">

                                    <?php foreach ($variants as $key => $value) : ?>

                                    <input type="hidden" class="idPubliVariant"
                                        name="idPubliVariant_<?php echo ($key + 1) ?>"
                                        value="<?php echo $value->id_publivariant ?>">

                                    <!--=====================================
											Variantes
											======================================-->

                                    <div class="card variantCount">

                                        <div class="card-body">

                                            <div class="form-group">

                                                <div class="d-flex justify-content-between">

                                                    <label for="info_publication">Variante <?php echo ($key + 1) ?><sup
                                                            class="text-danger">*</sup></label>

                                                    <?php if (($key + 1) == 1) : ?>

                                                    <div>
                                                        <button type="button"
                                                            class="btn btn-default btn-sm rounded-pill px-3 addVariant"><i
                                                                class="fas fa-plus fa-xs"></i> Agregar otra
                                                            variante</button>
                                                    </div>

                                                    <?php else : ?>

                                                    <div>
                                                        <button type="button"
                                                            class="btn btn-default btn-sm rounded-pill px-3 deleteVariant"
                                                            idVariant="<?php echo base64_encode($value->id_variant) ?>"><i
                                                                class="fas fa-times fa-xs"></i> Quitar esta
                                                            variante</button>
                                                    </div>


                                                    <?php endif ?>



                                                </div>

                                            </div>

                                            <div class="row row-cols-1 row-cols-md-2">

                                                <div class="col">

                                                    <!--=====================================
															Tipo de variante
															======================================-->

                                                    <div class="form-group">

                                                        <select class="custom-select"
                                                            name="type_variant_<?php echo ($key + 1) ?>"
                                                            onchange="changeVariant(event, <?php echo ($key + 1) ?>)">

                                                            <option <?php if ($value->type_variant == "gallery") : ?>
                                                                selected <?php endif ?> value="gallery">Galería de fotos
                                                            </option>
                                                            <option <?php if ($value->type_variant == "video") : ?>
                                                                selected <?php endif ?> value="video">Video</option>

                                                        </select>

                                                    </div>

                                                    <?php if ($value->type_variant == "gallery") : ?>

                                                    <!--=====================================
														        Galería del publicationo
														        ======================================-->

                                                    <div class="dropzone dropzone_<?php echo ($key + 1) ?> mb-3">

                                                        <!--=====================================
														        	Plugin Dropzone
														        	======================================-->

                                                        <?php foreach (json_decode($value->media_variant, true)  as $index => $item) : ?>

                                                        <div class="dz-preview dz-file-preview">

                                                            <div class="dz-image">

                                                                <img class="img-fluid"
                                                                    src="<?php echo "/views/assets/img/publications/" . $publication->url_publication . "/" . $item ?>">

                                                            </div>

                                                            <a class="dz-remove" data-dz-remove
                                                                remove="<?php echo $item ?>"
                                                                onclick="removeGallery(this, <?php echo ($key + 1) ?>)">Remove
                                                                file</a>

                                                        </div>

                                                        <?php endforeach ?>

                                                        <div class="dz-message">

                                                            Arrastra tus imágenes acá, tamaño máximo 400px * 450px

                                                        </div>

                                                    </div>

                                                    <input type="hidden"
                                                        name="galleryPublication_<?php echo ($key + 1) ?>"
                                                        class="galleryPublication_<?php echo ($key + 1) ?>">

                                                    <input type="hidden"
                                                        name="galleryOldPublication_<?php echo ($key + 1) ?>"
                                                        class="galleryOldPublication_<?php echo ($key + 1) ?>"
                                                        value='<?php echo $value->media_variant ?>'>

                                                    <input type="hidden"
                                                        name="deleteGalleryPublication_<?php echo ($key + 1) ?>"
                                                        class="deleteGalleryPublication_<?php echo ($key + 1) ?>"
                                                        value='[]'>

                                                    <!--=====================================
														        Insertar video Youtube
														        ======================================-->

                                                    <div class="input-group mb-3 inputVideo_<?php echo ($key + 1) ?>"
                                                        style="display:none">

                                                        <span class="input-group-text">
                                                            <i class="fas fa-clipboard-list"></i>
                                                        </span>

                                                        <input type="text" class="form-control"
                                                            name="videopublication_<?php echo ($key + 1) ?>"
                                                            placeholder="Ingresa la URL de YouTube"
                                                            onchange="changeVideo(event, <?php echo ($key + 1) ?>)">

                                                    </div>

                                                    <iframe width="100%" height="280" src="" frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                        allowfullscreen
                                                        class="mb-3 iframeYoutube_<?php echo ($key + 1) ?>"
                                                        style="display:none"></iframe>


                                                    <?php else : ?>

                                                    <!--=====================================
														        Insertar video Youtube
														        ======================================-->

                                                    <div class="input-group mb-3 inputVideo_<?php echo ($key + 1) ?>">

                                                        <span class="input-group-text">
                                                            <i class="fas fa-clipboard-list"></i>
                                                        </span>

                                                        <input type="text" class="form-control"
                                                            name="videopublication_<?php echo ($key + 1) ?>"
                                                            placeholder="Ingresa la URL de YouTube"
                                                            value="<?php echo $value->media_variant ?>"
                                                            onchange="changeVideo(event, <?php echo ($key + 1) ?>)">

                                                    </div>

                                                    <?php

                                                                $idYoutube = explode("/", $value->media_variant);
                                                                $idYoutube = end($idYoutube);


                                                                ?>

                                                    <iframe width="100%" height="280"
                                                        src="https://www.youtube.com/embed/<?php echo $idYoutube ?>"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                        allowfullscreen
                                                        class="mb-3 iframeYoutube_<?php echo ($key + 1) ?>"></iframe>

                                                    <!--=====================================
														        Galería del publicationo
														        ======================================-->

                                                    <div class="dropzone dropzone_<?php echo ($key + 1) ?> mb-3"
                                                        style="display:none">

                                                        <!--=====================================
														        	Plugin Dropzone
														        	======================================-->

                                                        <div class="dz-message">

                                                            Arrastra tus imágenes acá, tamaño máximo 400px * 450px

                                                        </div>

                                                    </div>

                                                    <input type="hidden"
                                                        name="gallerypublication_<?php echo ($key + 1) ?>"
                                                        class="gallerypublication_<?php echo ($key + 1) ?>"
                                                        style="display:none">

                                                    <?php endif ?>

                                                </div>

                                                <div class="col">

                                                    <!--=====================================
															Descripción de la variante
															======================================-->

                                                    <div class="input-group mb-3">

                                                        <span class="input-group-text">
                                                            <i class="fas fa-clipboard-list"></i>
                                                        </span>

                                                        <input type="text" class="form-control"
                                                            name="description_variant_<?php echo ($key + 1) ?>"
                                                            placeholder="Descripción: Color Negro, talla S, Material Goma"
                                                            value="<?php echo $value->description_variant ?>">

                                                    </div>


                                                </div>


                                            </div>

                                        </div>

                                    </div>

                                    <?php endforeach ?>

                                    <?php else : ?>

                                    <input type="hidden" name="totalVariants" value="1">

                                    <!--=====================================
										Variantes
										======================================-->

                                    <div class="form-group">

                                        <div class="d-flex justify-content-between">

                                            <label for="info_publication">Variante 1<sup
                                                    class="text-danger">*</sup></label>

                                            <div>
                                                <button type="button"
                                                    class="btn btn-default btn-sm rounded-pill px-3 addVariant">
                                                    <i class="fas fa-plus fa-xs"></i> Agregar otra variante
                                                </button>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row row-cols-1 row-cols-md-2">

                                        <div class="col">

                                            <!--=====================================
												Tipo de variante
												======================================-->

                                            <div class="form-group">

                                                <select class="custom-select" name="type_variant_1"
                                                    onchange="changeVariant(event, 1)">

                                                    <option value="gallery">Galería de fotos</option>
                                                    <option value="video">Video</option>

                                                </select>

                                            </div>

                                            <!--=====================================
										        Galería del publicationo
										        ======================================-->

                                            <div class="dropzone dropzone_1 mb-3">

                                                <!--=====================================
										        	Plugin Dropzone
										        	======================================-->

                                                <div class="dz-message">

                                                    Arrastra tus imágenes acá, tamaño máximo 400px * 450px

                                                </div>

                                            </div>

                                            <input type="hidden" name="gallerypublication_1"
                                                class="gallerypublication_1">

                                            <!--=====================================
										        Insertar video Youtube
										        ======================================-->

                                            <div class="input-group mb-3 inputVideo_1" style="display:none">

                                                <span class="input-group-text">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </span>

                                                <input type="text" class="form-control" name="videopublication_1"
                                                    placeholder="Ingresa la URL de YouTube"
                                                    onchange="changeVideo(event, 1)">

                                            </div>

                                            <iframe width="100%" height="280" src="" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen class="mb-3 iframeYoutube_1"
                                                style="display:none"></iframe>

                                        </div>

                                        <div class="col">

                                            <!--=====================================
												Descripción de la variante
												======================================-->

                                            <div class="input-group mb-3">

                                                <span class="input-group-text">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </span>

                                                <input type="text" class="form-control" name="description_variant_1"
                                                    placeholder="Descripción: Color Negro, talla S, Material Goma">

                                            </div>

                                            <!--=====================================
												Costo de la variante
												======================================-->

                                            <div class="input-group mb-3">

                                                <span class="input-group-text">
                                                    <i class="fas fa-hand-holding-usd"></i>
                                                </span>

                                                <input type="number" step="any" class="form-control"
                                                    name="cost_variant_1" placeholder="Costo de compra">

                                            </div>

                                            <!--=====================================
												Precio de la variante
												======================================-->

                                            <div class="input-group mb-3">

                                                <span class="input-group-text">
                                                    <i class="fas fa-funnel-dollar"></i>
                                                </span>

                                                <input type="number" step="any" class="form-control"
                                                    name="price_variant_1" placeholder="Precio de venta">

                                            </div>

                                            <!--=====================================
												Oferta de la variante
												======================================-->

                                            <div class="input-group mb-3">

                                                <span class="input-group-text">
                                                    <i class="fas fa-tag"></i>
                                                </span>

                                                <input type="number" step="any" class="form-control"
                                                    name="offer_variant_1" placeholder="Precio de descuento">

                                            </div>

                                            <!--=====================================
												Fin de Oferta de la variante
												======================================-->

                                            <div class="input-group mb-3">

                                                <span class="input-group-text">Fin del descuento</span>

                                                <input type="date" class="form-control" name="date_variant_1">

                                            </div>


                                            <!--=====================================
												Stock de la variante
												======================================-->

                                            <div class="input-group mb-3">

                                                <span class="input-group-text">
                                                    <i class="fas fa-list"></i>
                                                </span>

                                                <input type="number" class="form-control" name="stock_variant_1"
                                                    placeholder="Stock disponible">

                                            </div>



                                        </div>


                                    </div>

                                    <?php endif ?>


                                </div>

                            </div>


                        </div>


                    </div>

                    <!--=====================================
					QUINTO BLOQUE
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

                                                        <?php if (!empty($publication)) : ?>

                                                        <img src="/views/assets/img/publications/<?php echo $publication->url_publication ?>/<?php echo $publication->image_publication ?>"
                                                            class="img-fluid metaImg" style="width:100%">

                                                        <?php else : ?>

                                                        <img src="/views/assets/img/publications/default/default-image.jpg"
                                                            class="img-fluid metaImg" style="width:100%">

                                                        <?php endif ?>

                                                    </figure>

                                                    <!--=====================================
													Visor título
													======================================-->

                                                    <h6 class="text-left text-primary mb-1 metaTitle">

                                                        <?php if (!empty($publication)) : ?>
                                                        <?php echo $publication->name_publication ?>
                                                        <?php else : ?>
                                                        Lorem ipsum dolor sit
                                                        <?php endif ?>

                                                    </h6>

                                                    <!--=====================================
													Visor URL
													======================================-->

                                                    <p class="text-left text-success small mb-1">
                                                        <?php echo $path ?><span
                                                            class="metaURL"><?php if (!empty($publication)) : ?><?php echo $publication->url_publication ?><?php else : ?>lorem<?php endif ?></span>
                                                    </p>

                                                    <!--=====================================
													Visor Descripción
													======================================-->

                                                    <p class="text-left small mb-1 metaDescription">

                                                        <?php if (!empty($publication)) : ?>
                                                        <?php echo $publication->description_publication ?>
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
                                                        <?php if (!empty($publication)) : ?>
                                                        <?php echo $publication->keywords_publication ?>
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
                                    class="btn border-0 templateColor float-right py-2 px-3 btn-sm rounded-pill saveBtn">Guardar
                                    Información</button>

                                <a href="/admin/categorias"
                                    class="btn btn-default float-right py-2 px-3 btn-sm rounded-pill mr-2">Regresar</a>

                            </div>

                            <div class="col-12 text-center d-flex justify-content-center mt-2 d-block d-lg-none">

                                <div><a href="/admin/categorias"
                                        class="btn btn-default py-2 px-3 btn-sm rounded-pill mr-2">Regresar</a></div>

                                <div><button type="submit"
                                        class="btn border-0 templateColor py-2 px-3 btn-sm rounded-pill saveBtn">Guardar
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