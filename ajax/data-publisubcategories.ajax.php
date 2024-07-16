<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{

    public function data()
    {

        if (!empty($_POST)) {

            /*=============================================
            Capturando y organizando las variables POST de DT
            =============================================*/

            $draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 
            // echo '<pre>$draw '; print_r($draw); echo '</pre>';

            $orderByColumnIndex = $_POST["order"][0]["column"]; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)
            // echo '<pre>$orderByColumnIndex '; print_r($orderByColumnIndex); echo '</pre>';

            $orderBy = $_POST["columns"][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice
            // echo '<pre>$orderBy '; print_r($orderBy); echo '</pre>';

            $orderType = $_POST["order"][0]["dir"]; // Obtener el orden ASC o DESC
            // echo '<pre>$orderType '; print_r($orderType); echo '</pre>';

            $start = $_POST["start"]; //Indicador de primer registro de paginación.
            // echo '<pre>$start '; print_r($start); echo '</pre>';

            $length = $_POST["length"]; //Indicador de la longitud de la paginación.
            // echo '<pre>$length '; print_r($length); echo '</pre>';

            /*=============================================
            El total de registros de la data
            =============================================*/

            $url = "relations?rel=publisubcategories,publicategories&type=publisubcategory,publicategory&select=id_publisubcategory";
            $method = "GET";
            $fields = array();

            $response = CurlController::request($url, $method, $fields);

            if ($response->status == 200) {

                $totalData = $response->total;
            } else {

                echo '{
            		"Draw": 1,
					"recordsTotal": 0,
				    "recordsFiltered": 0,
				    "data":[]}';

                return;
            }

            $select = "id_publisubcategory,status_publisubcategory,name_publisubcategory,url_publisubcategory,image_publisubcategory,description_publisubcategory,keywords_publisubcategory,name_publicategory,products_publisubcategory,views_publisubcategory,date_updated_publisubcategory";

            /*=============================================
           	Búsqueda de datos
            =============================================*/

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["name_publisubcategory", "url_publisubcategory", "description_publisubcategory", "keywords_publisubcategory", "date_updated_publisubcategory,name_publicategory"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $url = "relations?rel=publisubcategories,publicategories&type=publisubcategory,publicategory&select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

                        $data = CurlController::request($url, $method, $fields)->results;

                        if ($data == "Not Found") {

                            $data = array();
                            $recordsFiltered = 0;
                        } else {

                            $recordsFiltered = count($data);
                            break;
                        }
                    }
                } else {

                    echo '{
            		"Draw": 1,
					"recordsTotal": 0,
				    "recordsFiltered": 0,
				    "data":[]}';

                    return;
                }
            } else {

                /*=============================================
	            Seleccionar datos
	            =============================================*/

                $url = "relations?rel=publisubcategories,publicategories&type=publisubcategory,publicategory&select=" . $select . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
                $data = CurlController::request($url, $method, $fields)->results;

                $recordsFiltered = $totalData;
            }

            /*=============================================
            Cuando la data viene vacía
            =============================================*/

            if (empty($data)) {

                echo '{
            		"Draw": 1,
					"recordsTotal": 0,
				    "recordsFiltered": 0,
				    "data":[]}';

                return;
            }

            /*=============================================
            Construimos el dato JSON a regresar
            =============================================*/

            $dataJson = '{
				"Draw": ' . intval($draw) . ',
				"recordsTotal": ' . $totalData . ',
				"recordsFiltered": ' . $recordsFiltered . ',
				"data": [';

            foreach ($data as $key => $value) {

                /*=============================================
            		STATUS
            		=============================================*/

                if ($value->status_publisubcategory == 1) {

                    $status_publisubcategory = "<input type='checkbox' data-size='mini' data-bootstrap-switch data-off-color='danger' data-on-color='dark' checked='true' idItem='" . base64_encode($value->id_publisubcategory) . "' table='publisubcategories' column='publisubcategory'>";
                } else {

                    $status_publisubcategory = "<input type='checkbox' data-size='mini' data-bootstrap-switch data-off-color='danger' data-on-color='dark' idItem='" . base64_encode($value->id_publisubcategory) . "' table='publisubcategories' column='publisubcategory'>";
                }

                /*=============================================
            		TEXTOS
            		=============================================*/

                $name_publisubcategory = $value->name_publisubcategory;


                $url_publisubcategory = "<a href='/" . $value->url_publisubcategory . "' target='_blank' class='badge badge-light px-3 py-1 border rounded-pill'>/" . $value->url_publisubcategory . "</a>";

                $image_publisubcategory =  "<img src='/views/assets/img/publisubcategories/" . $value->url_publisubcategory . "/" . $value->image_publisubcategory . "' class='img-thumbnail rounded'>";

                $description_publisubcategory = templateController::reduceText($value->description_publisubcategory, 25);

                $keywords_publisubcategory = "";

                $keywordsArray = explode(",", $value->keywords_publisubcategory);

                foreach ($keywordsArray as $index => $item) {

                    $keywords_publisubcategory .= "<span class='badge badge-primary rounded-pill px-3 py-1'>" . $item . "</span>";
                }

                $name_publicategory = $value->name_publicategory;

                $products_publisubcategory = $value->products_publisubcategory;

                $views_publisubcategory = "<span class='badge badge-warning rounded-pill px-3 py-1'><i class='fas fa-eye'></i> " . $value->views_publisubcategory . "</span>";

                $date_updated_publisubcategory = $value->date_updated_publisubcategory;

                $actions = "<div class='btn-group'>
									<a href='/admin/publisubcategorias/gestion?publisubcategory=" . base64_encode($value->id_publisubcategory) . "' class='btn bg-purple border-0 rounded-pill mr-2 btn-sm px-3'>
										<i class='fas fa-pencil-alt text-white'></i>
									</a>
									<button class='btn btn-dark border-0 rounded-pill mr-2 btn-sm px-3 deleteItem' rol='admin' table='publisubcategories' column='publisubcategory' idItem='" . base64_encode($value->id_publisubcategory) . "'>
										<i class='fas fa-trash-alt text-white'></i>
									</button>
								</div>";

                $actions = TemplateController::htmlClean($actions);

                $dataJson .= '{ 
						"id_publisubcategory":"' . ($start + $key + 1) . '",
						"status_publisubcategory":"' . $status_publisubcategory . '",
						"name_publisubcategory":"' . $name_publisubcategory . '",
						"url_publisubcategory":"' . $url_publisubcategory . '",
						"image_publisubcategory":"' . $image_publisubcategory . '",
						"description_publisubcategory":"' . $description_publisubcategory . '",
						"keywords_publisubcategory":"' . $keywords_publisubcategory . '",
						"name_publicategory":"' . $name_publicategory . '",
						"products_publisubcategory":"' . $products_publisubcategory . '",
						"views_publisubcategory":"' . $views_publisubcategory . '",
						"date_updated_publisubcategory":"' . $date_updated_publisubcategory . '",
						"actions":"' . $actions . '"
					},';
            }

            $dataJson = substr($dataJson, 0, -1); // este substr quita el último caracter de la cadena, que es una coma, para impedir que rompa la tabla

            $dataJson .= ']}';

            echo $dataJson;
        }
    }
}

/*=============================================
Activar función DataTable
=============================================*/

$data = new DatatableController();
$data->data();