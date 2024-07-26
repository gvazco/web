<?php

require_once "../controllers/curl.controller.php";

class DeleteController
{

    public $token;
    public $table;
    public $id;
    public $nameId;

    public function ajaxDelete()
    {

        if ($this->table == "admins" && base64_decode($this->id) == "1") {

            echo "no-borrar";
            return;
        }

        if ($this->table == "categories") {

            $select = "url_category,image_category,subcategories_category";
            $url = "categories?linkTo=id_category&equalTo=" . base64_decode($this->id) . "&select=" . $select;
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            No Borrar categoría si tiene subcategorías vinculadas 
            =============================================*/

            if ($dataItem->subcategories_category > 0) {

                echo "no-borrar";
                return;
            }

            /*=============================================
            Borrar Imagen
            =============================================*/

            unlink("../views/assets/img/categories/" . $dataItem->url_category . "/" . $dataItem->image_category);

            /*=============================================
            Borrar Directorio
            =============================================*/

            rmdir("../views/assets/img/categories/" . $dataItem->url_category);
        }

        if ($this->table == "publicategories") {

            $select = "url_publlicategory,image_publlicategory,subcategories_publlicategory";
            $url = "publicategories?linkTo=id_publicategory&equalTo=" . base64_decode($this->id) . "&select=" . $select;
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            No Borrar categoría si tiene subcategorías vinculadas 
            =============================================*/

            if ($dataItem->publisubcategories_publicategory > 0) {

                echo "no-borrar";
                return;
            }

            /*=============================================
            Borrar Imagen
            =============================================*/

            unlink("../views/assets/img/publicategories/" . $dataItem->url_publicategory . "/" . $dataItem->image_publicategory);

            /*=============================================
            Borrar Directorio
            =============================================*/

            rmdir("../views/assets/img/publicategories/" . $dataItem->url_publicategory);
        }

        if ($this->table == "subcategories") {

            $select = "url_subcategory,image_subcategory,products_subcategory,id_category_subcategory";
            $url = "subcategories?linkTo=id_subcategory&equalTo=" . base64_decode($this->id) . "&select=" . $select;
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            No Borrar subcategoría si tiene productos vinculados 
            =============================================*/

            if ($dataItem->products_subcategory > 0) {

                echo "no-borrar";
                return;
            }

            /*=============================================
            Borrar Imagen
            =============================================*/

            unlink("../views/assets/img/subcategories/" . $dataItem->url_subcategory . "/" . $dataItem->image_subcategory);

            /*=============================================
            Borrar Directorio
            =============================================*/

            rmdir("../views/assets/img/subcategories/" . $dataItem->url_subcategory);

            /*=============================================
            Quitar subcategoria vinculado a categoria
            =============================================*/

            $url = "categories?equalTo=" . $dataItem->id_category_subcategory . "&linkTo=id_category&select=subcategories_category";
            $method = "GET";
            $fields = array();

            $subcategories_category = CurlController::request($url, $method, $fields)->results[0]->subcategories_category;

            $url = "categories?id=" . $dataItem->id_category_subcategory . "&nameId=id_category&token=" . $this->token . "&table=admins&suffix=admin";
            $method = "PUT";

            $fields = "subcategories_category=" . ($subcategories_category - 1);

            $updateCategory = CurlController::request($url, $method, $fields);
        }

        if ($this->table == "publisubcategories") {

            $select = "url_publisubcategory,image_publisubcategory,products_publisubcategory,id_publicategory_publisubcategory";
            $url = "publisubcategories?linkTo=id_publisubcategory&equalTo=" . base64_decode($this->id) . "&select=" . $select;
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            No Borrar subcategoría si tiene productos vinculados 
            =============================================*/

            if ($dataItem->publications_publisubcategory > 0) {

                echo "no-borrar";
                return;
            }

            /*=============================================
            Borrar Imagen
            =============================================*/

            unlink("../views/assets/img/publisubcategories/" . $dataItem->url_publisubcategory . "/" . $dataItem->image_publisubcategory);

            /*=============================================
            Borrar Directorio
            =============================================*/

            rmdir("../views/assets/img/publisubcategories/" . $dataItem->url_publisubcategory);

            /*=============================================
            Quitar subcategoria vinculado a categoria
            =============================================*/

            $url = "publicategories?equalTo=" . $dataItem->id_publicategory_publisubcategory . "&linkTo=id_publicategory&select=publisubcategories_publicategory";
            $method = "GET";
            $fields = array();

            $publisubcategories_publicategory = CurlController::request($url, $method, $fields)->results[0]->publisubcategories_publicategory;

            $url = "publicategories?id=" . $dataItem->id_publicategory_publisubcategory . "&nameId=id_publicategory&token=" . $this->token . "&table=admins&suffix=admin";
            $method = "PUT";

            $fields = "publisubcategories_publicategory=" . ($publisubcategories_publicategory - 1);

            $updatePubliCategory = CurlController::request($url, $method, $fields);
        }

        if ($this->table == "products") {

            $select = "url_product,image_product,id_category_product,id_subcategory_product";
            $url = "products?linkTo=id_product&equalTo=" . base64_decode($this->id) . "&select=" . $select;
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            Borrar Imagen
            =============================================*/

            unlink("../views/assets/img/products/" . $dataItem->url_product . "/" . $dataItem->image_product);

            /*=============================================
            Borrar Directorio
            =============================================*/

            rmdir("../views/assets/img/products/" . $dataItem->url_product);

            /*=============================================
            Quitar producto vinculado a categoria
            =============================================*/

            $url = "categories?equalTo=" . $dataItem->id_category_product . "&linkTo=id_category&select=products_category";
            $method = "GET";
            $fields = array();

            $products_category = CurlController::request($url, $method, $fields)->results[0]->products_category;

            $url = "categories?id=" . $dataItem->id_category_product . "&nameId=id_category&token=" . $this->token . "&table=admins&suffix=admin";
            $method = "PUT";

            $fields = "products_category=" . ($products_category - 1);

            $updateCategory = CurlController::request($url, $method, $fields);

            /*=============================================
            Quitar producto vinculado a subcategoria
            =============================================*/

            $url = "subcategories?equalTo=" . $dataItem->id_subcategory_product . "&linkTo=id_subcategory&select=products_subcategory";
            $method = "GET";
            $fields = array();

            $products_subcategory = CurlController::request($url, $method, $fields)->results[0]->products_subcategory;

            $url = "subcategories?id=" . $dataItem->id_subcategory_product . "&nameId=id_subcategory&token=" . $this->token . "&table=admins&suffix=admin";
            $method = "PUT";

            $fields = "products_subcategory=" . ($products_subcategory - 1);

            $updateSubcategory = CurlController::request($url, $method, $fields);
        }

        if ($this->table == "publications") {

            $select = "url_publication,image_publication,id_publicategory_publication,id_publisubcategory_publication";
            $url = "publications?linkTo=id_publication&equalTo=" . base64_decode($this->id) . "&select=" . $select;
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            Borrar Imagen
            =============================================*/

            unlink("../views/assets/img/publications/" . $dataItem->url_publication . "/" . $dataItem->image_publication);

            /*=============================================
            Borrar Directorio
            =============================================*/

            rmdir("../views/assets/img/publications/" . $dataItem->url_publication);

            /*=============================================
            Quitar publicacion vinculada a categoria
            =============================================*/

            $url = "publicategories?equalTo=" . $dataItem->id_publicategory_publication . "&linkTo=id_publicategory&select=publications_publicategory";
            $method = "GET";
            $fields = array();

            $publications_publicategory = CurlController::request($url, $method, $fields)->results[0]->publications_publicategory;

            $url = "publicategories?id=" . $dataItem->id_publicategory_publication . "&nameId=id_publicategory&token=" . $this->token . "&table=admins&suffix=admin";
            $method = "PUT";

            $fields = "publications_publicategory=" . ($publications_publicategory - 1);

            $updatePubliCategory = CurlController::request($url, $method, $fields);

            /*=============================================
            Quitar publicacion vinculada a subcategoria
            =============================================*/

            $url = "publisubcategories?equalTo=" . $dataItem->id_publisubcategory_publication . "&linkTo=id_publisubcategory&select=publications_publisubcategory";
            $method = "GET";
            $fields = array();

            $publications_publisubcategory = CurlController::request($url, $method, $fields)->results[0]->publications_publisubcategory;

            $url = "publisubcategories?id=" . $dataItem->id_publisubcategory_publication . "&nameId=id_publisubcategory&token=" . $this->token . "&table=admins&suffix=admin";
            $method = "PUT";

            $fields = "publications_publisubcategory=" . ($publications_publisubcategory - 1);

            $updatePubliSubcategory = CurlController::request($url, $method, $fields);
        }

        if ($this->table == "variants") {

            $select = "type_variant,media_variant,url_product";
            $url = "relations?rel=variants,products&type=variant,product&linkTo=id_variant&equalTo=" . base64_decode($this->id) . "&select=" . $select;
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            Borrar todas las Imagenes de la galería
            =============================================*/

            if ($dataItem->type_variant == "gallery") {

                foreach (json_decode($dataItem->media_variant) as $file) {

                    unlink('../views/assets/img/products/' . $dataItem->url_product . '/' . $file);
                }
            } elseif ($dataItem->type_variant == "ondemand") {

                foreach (json_decode($dataItem->media_variant) as $file) {

                    unlink('../views/assets/img/products/' . $dataItem->url_product . '/' . $file);
                }
            }
        }

        if ($this->table == "publivariants") {

            $select = "type_publivariant,media_publivariant,url_publication";
            $url = "relations?rel=variants,publications&type=variant,publication&linkTo=id_publivariant&equalTo=" . base64_decode($this->id) . "&select=" . $select;
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            Borrar todas las Imagenes de la galería
            =============================================*/

            if ($dataItem->type_publivariant == "gallery") {

                foreach (json_decode($dataItem->media_publivariant) as $file) {

                    unlink('../views/assets/img/publications/' . $dataItem->url_publication . '/' . $file);
                }
            }
        }

        $url = $this->table . "?id=" . base64_decode($this->id) . "&nameId=" . $this->nameId . "&token=" . $this->token . "&table=admins&suffix=admin";
        $method = "DELETE";
        $fields = array();

        $delete = CurlController::request($url, $method, $fields);

        echo $delete->status;
    }
}

if (isset($_POST["token"])) {

    $Delete = new DeleteController();
    $Delete->token = $_POST["token"];
    $Delete->table = $_POST["table"];
    $Delete->id = $_POST["id"];
    $Delete->nameId = $_POST["nameId"];
    $Delete->ajaxDelete();
}