<?php

class PubliCategoriesController
{

    /*=============================================
	Gestión Categorias
	=============================================*/

    public function publicategoryManage()
    {

        if (isset($_POST["name_publicategory"])) {

            echo '<script>

				fncMatPreloader("on");
				fncSweetAlert("loading", "", "");

			</script>';

            if (isset($_POST["idPubliCategory"])) {

                if (isset($_FILES['image_publicategory']["tmp_name"]) && !empty($_FILES['image_publicategory']["tmp_name"])) {

                    $image = $_FILES['image_publicategory'];
                    $folder = "assets/img/publicategories/" . $_POST["url_publicategory"];
                    $name = $_POST["url_publicategory"];
                    $width = 1000;
                    $height = 600;

                    $saveImagePubliCategory = TemplateController::saveImage($image, $folder, $name, $width, $height);
                } else {

                    $saveImagePubliCategory = $_POST["old_image_publicategory"];
                }

                $fields = "name_publicategory=" . trim(TemplateController::capitalize($_POST["name_publicategory"])) . "&url_publicategory=" . $_POST["url_publicategory"] . "&icon_publicategory=" . $_POST["icon_publicategory"] . "&image_publicategory=" . $saveImagePubliCategory . "&description_publicategory=" . trim($_POST["description_publicategory"]) . "&keywords_publicategory=" . strtolower($_POST["keywords_publicategory"]);

                $url = "publicategories?id=" . base64_decode($_POST["idPubliCategory"]) . "&nameId=id_publicategory&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "PUT";

                $updateData = CurlController::request($url, $method, $fields);

                if ($updateData->status == 200) {

                    echo '<script>

							fncMatPreloader("off");
							fncFormatInputs();

							fncSweetAlert("success","Sus datos han sido actualizados con éxito","/admin/publicategorias");
			
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

                if (isset($_FILES['image_publicategory']["tmp_name"]) && !empty($_FILES['image_publicategory']["tmp_name"])) {

                    $image = $_FILES['image_publicategory'];
                    $folder = "assets/img/publicategories/" . $_POST["url_publicategory"];
                    $name = $_POST["url_publicategory"];
                    $width = 1000;
                    $height = 600;

                    $saveImagePubliCategory = TemplateController::saveImage($image, $folder, $name, $width, $height);
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

                    "name_publicategory" => trim(TemplateController::capitalize($_POST["name_publicategory"])),
                    "url_publicategory" => $_POST["url_publicategory"],
                    "icon_publicategory" => $_POST["icon_publicategory"],
                    "image_publicategory" => $saveImagePubliCategory,
                    "description_publicategory" => trim($_POST["description_publicategory"]),
                    "keywords_publicategory" => strtolower($_POST["keywords_publicategory"]),
                    "date_created_publicategory" => date("Y-m-d")

                );

                $url = "publicategories?token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
                $method = "POST";

                $createData = CurlController::request($url, $method, $fields);

                if ($createData->status == 200) {

                    echo '<script>

								fncMatPreloader("off");
								fncFormatInputs();

								fncSweetAlert("success","Sus datos han sido creados con éxito","/admin/publicategorias");
				
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