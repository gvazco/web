<?php

class PublicationsController
{

    /*=============================================
	Gestión Publicaciones
	=============================================*/

    public function publicationManage()
    {

        if (isset($_POST["name_publication"])) {

            echo '<script>

				fncMatPreloader("on");
				fncSweetAlert("loading", "", "");

			</script>';

            /*=============================================
			Edición Publicacion
			=============================================*/

            if (isset($_POST["idPublication"])) {

                if (isset($_FILES['image_publication']["tmp_name"]) && !empty($_FILES['image_publication']["tmp_name"])) {

                    $image = $_FILES['image_publication'];
                    $folder = "assets/img/publications/" . $_POST["url_publication"];
                    $name = $_POST["url_publication"];
                    $width = 1000;
                    $height = 600;

                    $saveImagePublication = TemplateController::saveImage($image, $folder, $name, $width, $height);
                } else {

                    $saveImagePublication = $_POST["old_image_publication"];
                }

                /*=============================================
				Mover todos los ficheros temporales al destino final
				=============================================*/

                if (is_dir('views/assets/img/temp')) {

                    $start = glob('views/assets/img/temp/*');

                    foreach ($start as $file) {

                        $archive = explode("/", $file);

                        copy($file, "views/assets/img/publications/" . $_POST["url_publication"] . "/" . $archive[count($archive) - 1]);

                        unlink($file);
                    }
                }

                $fields = "name_publication=" . trim(TemplateController::capitalize($_POST["name_publication"])) . "&url_publication=" . $_POST["url_publication"] . "&image_publication=" . $saveImagePublication . "&description_publication=" . trim($_POST["description_publication"]) . "&keywords_publication=" . strtolower($_POST["keywords_publication"]) . "&id_publicategory_publication=" . $_POST["id_publicategory_publication"] . "&id_publisubcategory_publication=" . $_POST["id_publisubcategory_publication"] . "&info_publication=" . urlencode(trim(str_replace('src="/views/assets/img/temp', 'src="/views/assets/img/publications/' . $_POST["url_publication"], $_POST["info_publication"])));

                $url = "publications?id=" . base64_decode($_POST["idPublication"]) . "&nameId=id_publication&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $updateData = CurlController::request($url, $method, $fields);

                /*=============================================
				Quitar publicacion vinculado a categoria
				=============================================*/

                $url = "publicategories?equalTo=" . base64_decode($_POST["old_id_publicategory_publication"]) . "&linkTo=id_publicategory&select=publications_publicategory";
                $method = "GET";
                $fields = array();

                $old_publications_publicategory = CurlController::request($url, $method, $fields)->results[0]->publications_publicategory;

                $url = "publicategories?id=" . base64_decode($_POST["old_id_publicategory_publication"]) . "&nameId=id_publicategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $fields = "publications_publicategory=" . ($old_publications_publicategory - 1);

                $updateOldPubliCategory = CurlController::request($url, $method, $fields);

                /*=============================================
				Agregar publicacion vinculado a categoria
				=============================================*/

                $url = "publicategories?equalTo=" . $_POST["id_publicategory_publication"] . "&linkTo=id_publicategory&select=publications_publicategory";
                $method = "GET";
                $fields = array();

                $publications_publicategory = CurlController::request($url, $method, $fields)->results[0]->publications_publicategory;

                $url = "publicategories?id=" . $_POST["id_publicategory_publication"] . "&nameId=id_publicategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $fields = "publications_publicategory=" . ($publications_publicategory + 1);

                $updatePubliCategory = CurlController::request($url, $method, $fields);

                /*=============================================
				Quitar publicacion vinculado a subcategoria
				=============================================*/

                $url = "publisubcategories?equalTo=" . base64_decode($_POST["old_id_publisubcategory_publication"]) . "&linkTo=id_publisubcategory&select=publications_publisubcategory";
                $method = "GET";
                $fields = array();

                $old_publications_publisubcategory = CurlController::request($url, $method, $fields)->results[0]->publications_publisubcategory;

                $url = "publisubcategories?id=" . base64_decode($_POST["old_id_publisubcategory_publication"]) . "&nameId=id_publisubcategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $fields = "publications_publisubcategory=" . ($old_publications_publisubcategory - 1);

                $updateOldPubliSubcategory = CurlController::request($url, $method, $fields);

                /*=============================================
				Agregar publicacion vinculado a subcategoria
				=============================================*/

                $url = "publisubcategories?equalTo=" . $_POST["id_publisubcategory_publication"] . "&linkTo=id_publisubcategory&select=publications_publisubcategory";
                $method = "GET";
                $fields = array();

                $publications_publisubcategory = CurlController::request($url, $method, $fields)->results[0]->publications_publisubcategory;

                $url = "publisubcategories?id=" . $_POST["id_publisubcategory_publication"] . "&nameId=id_publisubcategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $fields = "publications_publisubcategory=" . ($publications_publisubcategory + 1);

                $updatePubliSubcategory = CurlController::request($url, $method, $fields);

                /*=============================================
				Variantes
				=============================================*/

                $totalPubliVariants = $_POST["totalPubliVariants"];
                $countPubliVariant = 0;
                $readyPubliVariant = 0;

                for ($i = 1; $i <= $totalPubliVariants; $i++) {

                    $countPubliVariant++;

                    if ($_POST["type_publivariant_" . $i] == "gallery") {

                        $galleryPublication = array();
                        $galleryCount = 0;
                        $galleryOldCount = 0;

                        if (!empty($_POST["galleryPublication_" . $i])) {

                            foreach (json_decode($_POST["galleryPublication_" . $i], true) as $key => $value) {

                                $galleryCount++;

                                $image["tmp_name"] = $value["file"];
                                $image["type"] = $value["type"];
                                $image["mode"] = "base64";

                                $folder = "assets/img/publications/" . $_POST["url_publication"];
                                $name = mt_rand(10000, 99999);
                                $width = $value["width"];
                                $height = $value["height"];

                                $saveImageGallery  = TemplateController::saveImage($image, $folder, $name, $width, $height);

                                array_push($galleryPublication, $saveImageGallery);

                                if (count(json_decode($_POST["galleryPublication_" . $i], true)) == $galleryCount) {

                                    if ($_POST['galleryOldPublication_' . $i] != "[]") {

                                        foreach (json_decode($_POST['galleryOldPublication_' . $i], true)  as $index => $item) {

                                            $galleryOldCount++;
                                            array_push($galleryPublication, $item);

                                            if (count(json_decode($_POST['galleryOldPublication_' . $i], true)) == $galleryOldCount) {

                                                $media_publivariant = json_encode($galleryPublication);
                                            }
                                        }
                                    } else {

                                        $media_publivariant = json_encode($galleryPublication);
                                    }
                                }
                            }
                        } else {

                            /*=============================================
			 				Cuando no subimos imágenes nuevas
							=============================================*/

                            if ($_POST['galleryOldPublication_' . $i] != "[]") {

                                foreach (json_decode($_POST['galleryOldPublication_' . $i], true)  as $index => $item) {

                                    $galleryOldCount++;
                                    array_push($galleryPublication, $item);

                                    if (count(json_decode($_POST['galleryOldPublication_' . $i], true)) == $galleryOldCount) {

                                        $media_publivariant = json_encode($galleryPublication);
                                    }
                                }
                            }
                        }

                        /*=============================================
			 			Eliminamos archivos basura del servidor
						=============================================*/

                        if (!empty($_POST['deleteGalleryPublication_' . $i])) {

                            foreach (json_decode($_POST['deleteGalleryPublication_' . $i], true) as $key => $value) {

                                unlink("views/assets/img/publications/" . $_POST["url_publication"] . "/" . $value);
                            }
                        }
                    } else {

                        $media_publivariant = $_POST["videoPublication_" . $i];
                    }

                    /*=============================================
					Campos de las variantes
					=============================================*/

                    if (isset($_POST["idPubliVariant_" . $i])) {


                        $fields = "id_publication_publivariant=" . base64_decode($_POST["idPublication"]) . "&type_publivariant=" . $_POST["type_publivariant_" . $i] . "&media_publivariant=" . $media_publivariant . "&description_publivariant=" . $_POST["description_publivariant_" . $i];

                        $url = "publivariants?id=" . $_POST["idPubliVariant_" . $i] . "&nameId=id_publivariant&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                        $method = "PUT";


                        $editPubliVariant = CurlController::request($url, $method, $fields);
                    } else {

                        $fields = array(

                            "id_publication_publivariant" => base64_decode($_POST["idPublication"]),
                            "type_publivariant" => $_POST["type_publivariant_" . $i],
                            "media_publivariant" => $media_publivariant,
                            "description_publivariant" => $_POST["description_publivariant_" . $i],
                            "date_created_publivariant" => date("Y-m-d")

                        );


                        $url = "publivariants?token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                        $method = "POST";

                        $editPubliVariant = CurlController::request($url, $method, $fields);
                    }

                    if ($countPubliVariant == $totalPubliVariants) {

                        $readyPubliVariant = 200;
                    }
                }

                if (
                    $updateData->status == 200 &&
                    $readyPubliVariant == 200 &&
                    $updateOldPubliCategory->status == 200 &&
                    $updatePubliCategory->status == 200 &&
                    $updateOldPubliSubcategory->status == 200 &&
                    $updatePubliSubcategory->status == 200
                ) {

                    echo '<script>

							fncMatPreloader("off");
							fncFormatInputs();

							fncSweetAlert("success","Sus datos han sido actualizados con éxito","/admin/publications");
			
						</script>';
                } else {

                    if ($updateData->status == 303) {

                        echo '<script>

							fncFormatInputs();
							fncMatPreloader("off");
							fncSweetAlert("error","Token expirado, vuelva a iniciar sesión","/salir");

						</script>';
                    } else {

                        echo '<script>

							fncFormatInputs();
							fncMatPreloader("off");
							fncToastr("error","Ocurrió un error mientras se guardaban los datos, intente de nuevo");

						</script>';
                    }
                }


                /*=============================================
			Creación Publicacion
			=============================================*/
            } else {

                /*=============================================
				Validar y guardar la imagen
				=============================================*/

                if (isset($_FILES['image_publication']["tmp_name"]) && !empty($_FILES['image_publication']["tmp_name"])) {

                    $image = $_FILES['image_publication'];
                    $folder = "assets/img/publications/" . $_POST["url_publication"];
                    $name = $_POST["url_publication"];
                    $width = 1000;
                    $height = 600;

                    $saveImagePublication = TemplateController::saveImage($image, $folder, $name, $width, $height);
                } else {

                    echo '<script>

						fncFormatInputs();

						fncNotie(3, "El campo de imagen no puede ir vacío");

					</script>';

                    return;
                }

                /*=============================================
				Mover todos los ficheros temporales al destino final
				=============================================*/

                if (is_dir('views/assets/img/temp')) {

                    $start = glob('views/assets/img/temp/*');

                    foreach ($start as $file) {

                        $archive = explode("/", $file);

                        copy($file, "views/assets/img/publications/" . $_POST["url_publication"] . "/" . $archive[count($archive) - 1]);

                        unlink($file);
                    }
                }

                /*=============================================
				Validar y guardar la información de la categoría
				=============================================*/

                $fields = array(

                    "name_publication" => trim(TemplateController::capitalize($_POST["name_publication"])),
                    "url_publication" => $_POST["url_publication"],
                    "image_publication" => $saveImagePublication,
                    "description_publication" => trim($_POST["description_publication"]),
                    "keywords_publication" => strtolower($_POST["keywords_publication"]),
                    "id_publicategory_publication" => $_POST["id_publicategory_publication"],
                    "id_publisubcategory_publication" => $_POST["id_publisubcategory_publication"],
                    "info_publication" => trim(str_replace('src="/views/assets/img/temp', 'src="/views/assets/img/publications/' . $_POST["url_publication"], $_POST["info_publication"])),
                    "date_created_publication" => date("Y-m-d")

                );

                $url = "publications?token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "POST";

                $createData = CurlController::request($url, $method, $fields);

                /*=============================================
				Aumentar publicaciones vinculadas en categoría
				=============================================*/

                $url = "publicategories?equalTo=" . $_POST["id_publicategory_publication"] . "&linkTo=id_publicategory&select=publications_publicategory";
                $method = "GET";
                $fields = array();

                $publications_publicategory = CurlController::request($url, $method, $fields)->results[0]->publications_publicategory;


                $url = "publicategories?id=" . $_POST["id_publicategory_publication"] . "&nameId=id_publicategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";
                $fields = "publications_publicategory=" . ($publications_publicategory + 1);

                $updatePubliCategory = CurlController::request($url, $method, $fields);

                /*=============================================
				Aumentar publicaciones vinculadas en subcategoría
				=============================================*/

                $url = "publisubcategories?equalTo=" . $_POST["id_publisubcategory_publication"] . "&linkTo=id_publisubcategory&select=publications_publisubcategory";
                $method = "GET";
                $fields = array();

                $publications_publisubcategory = CurlController::request($url, $method, $fields)->results[0]->publications_publisubcategory;

                $url = "publisubcategories?id=" . $_POST["id_publisubcategory_publication"] . "&nameId=id_publisubcategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";
                $fields = "publications_publisubcategory=" . ($publications_publisubcategory + 1);

                $updatePubliSubcategory = CurlController::request($url, $method, $fields);

                /*=============================================
				Variantes
				=============================================*/

                $totalPubliVariants = $_POST["totalPubliVariants"];
                $countPubliVariant = 0;
                $readyPubliVariant = 0;

                for ($i = 1; $i <= $totalPubliVariants; $i++) {

                    $countPubliVariant++;

                    if ($_POST["type_publivariant_" . $i] == "gallery") {

                        $galleryPublication = array();
                        $galleryCount = 0;

                        if (!empty($_POST["galleryPublication_" . $i])) {

                            foreach (json_decode($_POST["galleryPublication_" . $i], true) as $key => $value) {

                                $galleryCount++;

                                $image["tmp_name"] = $value["file"];
                                $image["type"] = $value["type"];
                                $image["mode"] = "base64";

                                $folder = "assets/img/publications/" . $_POST["url_publication"];
                                $name = mt_rand(10000, 99999);
                                $width = $value["width"];
                                $height = $value["height"];

                                $saveImageGallery  = TemplateController::saveImage($image, $folder, $name, $width, $height);

                                array_push($galleryPublication, $saveImageGallery);

                                if (count(json_decode($_POST["galleryPublication_" . $i], true)) == $galleryCount) {

                                    $media_publivariant = json_encode($galleryPublication);
                                }
                            }
                        }
                    } else {

                        $media_publivariant = $_POST["videoPublication_" . $i];
                    }

                    /*=============================================
					Campos de las variantes
					=============================================*/

                    $fields = array(

                        "id_publication_publivariant" => $createData->results->lastId,
                        "type_publivariant" => $_POST["type_publivariant_" . $i],
                        "media_publivariant" => $media_publivariant,
                        "description_publivariant" => $_POST["description_publivariant_" . $i],
                        "date_created_publivariant" => date("Y-m-d")

                    );

                    $url = "publivariants?token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                    $method = "POST";

                    $createPubliVariant = CurlController::request($url, $method, $fields);

                    if ($countPubliVariant == $totalPubliVariants) {

                        $readyPubliVariant = 200;
                    }
                }

                if (
                    $createData->status == 200 &&
                    $readyPubliVariant == 200 &&
                    $updatePubliCategory->status == 200 &&
                    $updatePubliSubcategory->status == 200
                ) {

                    echo '<script>

								fncMatPreloader("off");
								fncFormatInputs();

								fncSweetAlert("success","Sus datos han sido creados con éxito","/admin/publicaciones");
				
							</script>';
                } else {

                    if ($createData->status == 303) {

                        echo '<script>

								fncFormatInputs();
								fncMatPreloader("off");
								fncSweetAlert("error","Token expirado, vuelva a iniciar sesión","/salir");

							</script>';
                    } else {

                        echo '<script>

							fncFormatInputs();
							fncMatPreloader("off");
							fncToastr("error","Ocurrió un error mientras se guardaban los datos, intente de nuevo");

						</script>';
                    }
                }
            }
        }
    }
}