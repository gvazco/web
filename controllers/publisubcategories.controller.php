<?php

class PubliSubcategoriesController
{

    /*=============================================
	Gestión PubliSubcategorias
	=============================================*/

    public function publisubcategoryManage()
    {

        if (isset($_POST["name_publisubcategory"])) {

            echo '<script>

				fncMatPreloader("on");
				fncSweetAlert("loading", "", "");

			</script>';

            if (isset($_POST["idPubliSubcategory"])) {

                if (isset($_FILES['image_publisubcategory']["tmp_name"]) && !empty($_FILES['image_publisubcategory']["tmp_name"])) {

                    $image = $_FILES['image_publisubcategory'];
                    $folder = "assets/img/publisubcategories/" . $_POST["url_publisubcategory"];
                    $name = $_POST["url_publisubcategory"];
                    $width = 1000;
                    $height = 600;

                    $saveImagePubliSubcategory = TemplateController::saveImage($image, $folder, $name, $width, $height);
                } else {

                    $saveImagePubliSubcategory = $_POST["old_image_publisubcategory"];
                }

                $fields = "name_publisubcategory=" . trim(TemplateController::capitalize($_POST["name_publisubcategory"])) . "&url_publisubcategory=" . $_POST["url_publisubcategory"] . "&image_publisubcategory=" . $saveImagePubliSubcategory . "&description_publisubcategory=" . trim($_POST["description_publisubcategory"]) . "&keywords_publisubcategory=" . strtolower($_POST["keywords_publisubcategory"]) . "&id_publicategory_publisubcategory=" . $_POST["id_publicategory_publisubcategory"];

                $url = "publisubcategories?id=" . base64_decode($_POST["idPubliSubcategory"]) . "&nameId=id_publisubcategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $updateData = CurlController::request($url, $method, $fields);

                /*=============================================
				Quitar subcategorías vinculadas a categoría
				=============================================*/

                $url = "publicategories?equalTo=" . base64_decode($_POST["old_id_publicategory_publisubcategory"]) . "&linkTo=id_publicategory&select=publisubcategories_publicategory";
                $method = "GET";
                $fields = array();

                $old_publisubcategories_publicategory = CurlController::request($url, $method, $fields)->results[0]->publisubcategories_publicategory;

                $url = "publicategories?id=" . base64_decode($_POST["old_id_publicategory_publisubcategory"]) . "&nameId=id_publicategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $fields = "publisubcategories_publicategory=" . ($old_publisubcategories_publicategory - 1);

                /*=============================================
				Agregar subcategorías vinculadas a categoría
				=============================================*/

                $updateOldPubliCategory = CurlController::request($url, $method, $fields);

                $url = "publicategories?equalTo=" . $_POST["id_publicategory_publisubcategory"] . "&linkTo=id_publicategory&select=publisubcategories_publicategory";
                $method = "GET";
                $fields = array();

                $publisubcategories_publicategory = CurlController::request($url, $method, $fields)->results[0]->publisubcategories_publicategory;

                $url = "publicategories?id=" . $_POST["id_publicategory_publisubcategory"] . "&nameId=id_publicategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $fields = "publisubcategories_publicategory=" . ($publisubcategories_publicategory + 1);

                $updatePubliCategory = CurlController::request($url, $method, $fields);

                if ($updateData->status == 200 && $updateOldPubliCategory->status == 200 && $updatePubliCategory->status == 200) {

                    echo '<script>

							fncMatPreloader("off");
							fncFormatInputs();

							fncSweetAlert("success","Sus datos han sido actualizados con éxito","/admin/publisubcategorias");
			
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
            } else {

                /*=============================================
				Validar y guardar la imagen
				=============================================*/

                if (isset($_FILES['image_publisubcategory']["tmp_name"]) && !empty($_FILES['image_publisubcategory']["tmp_name"])) {

                    $image = $_FILES['image_publisubcategory'];
                    $folder = "assets/img/publisubcategories/" . $_POST["url_publisubcategory"];
                    $name = $_POST["url_publisubcategory"];
                    $width = 1000;
                    $height = 600;

                    $saveImagePubliSubcategory = TemplateController::saveImage($image, $folder, $name, $width, $height);
                } else {

                    echo '<script>

						fncFormatInputs();

						fncNotie(3, "El campo de imagen no puede ir vacío");

					</script>';

                    return;
                }

                /*=============================================
				Validar y guardar la información de la categoría
				=============================================*/

                $fields = array(

                    "name_publisubcategory" => trim(TemplateController::capitalize($_POST["name_publisubcategory"])),
                    "url_publisubcategory" => $_POST["url_publisubcategory"],
                    "image_publisubcategory" => $saveImagePubliSubcategory,
                    "description_publisubcategory" => trim($_POST["description_publisubcategory"]),
                    "keywords_publisubcategory" => strtolower($_POST["keywords_publisubcategory"]),
                    "id_publicategory_publisubcategory" => $_POST["id_publicategory_publisubcategory"],
                    "date_created_publisubcategory" => date("Y-m-d")

                );

                $url = "publisubcategories?token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "POST";

                $createData = CurlController::request($url, $method, $fields);

                /*=============================================
				Aumentar subcategorías vinculadas a categorías
				=============================================*/

                $url = "publicategories?equalTo=" . $_POST["id_publicategory_publisubcategory"] . "&linkTo=id_publicategory&select=publisubcategories_publicategory";
                $method = "GET";
                $fields = array();

                $publisubcategories_publicategory = CurlController::request($url, $method, $fields)->results[0]->publisubcategories_publicategory;

                $url = "publicategories?id=" . $_POST["id_publicategory_publisubcategory"] . "&nameId=id_publicategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $fields = "publisubcategories_publicategory=" . ($publisubcategories_publicategory + 1);

                $updatePubliCategory = CurlController::request($url, $method, $fields);

                if ($createData->status == 200 && $updatePubliCategory->status == 200) {

                    echo '<script>

								fncMatPreloader("off");
								fncFormatInputs();

								fncSweetAlert("success","Sus datos han sido creados con éxito","/admin/publisubcategorias");
				
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