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

            $url = "publicategories?select=id_publicategory";
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

            $select = "id_publicategory,status_publicategory,name_publicategory,url_publicategory,image_publicategory,description_publicategory,keywords_publicategory,publisubcategories_publicategory,publications_publicategory,views_publicategory,date_updated_publicategory";

            /*=============================================
           	Búsqueda de datos
            =============================================*/

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["name_publicategory", "url_publicategory", "description_publicategory", "keywords_publicategory", "date_updated_publicategory"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $url = "publicategories?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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

                $url = "publicategories?select=" . $select . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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

                if ($value->status_publicategory == 1) {

                    $status_publicategory = "<input type='checkbox' data-size='mini' data-bootstrap-switch data-off-color='danger' data-on-color='dark' checked='true' idItem='" . base64_encode($value->id_publicategory) . "' table='publicategories' column='publicategory'>";
                } else {

                    $status_publicategory = "<input type='checkbox' data-size='mini' data-bootstrap-switch data-off-color='danger' data-on-color='dark' idItem='" . base64_encode($value->id_publicategory) . "' table='publicategories' column='publicategory'>";
                }

                /*=============================================
            		TEXTOS
            		=============================================*/

                $name_publicategory = $value->name_publicategory;


                $url_publicategory = "<a href='/" . $value->url_publicategory . "' target='_blank' class='badge badge-light px-3 py-1 border rounded-pill'>/" . $value->url_publicategory . "</a>";

                $image_publicategory =  "<img src='/views/assets/img/publicategories/" . $value->url_publicategory . "/" . $value->image_publicategory . "' class='img-thumbnail rounded'>";

                $description_publicategory = templateController::reduceText($value->description_publicategory, 25);

                $keywords_publicategory = "";

                $keywordsArray = explode(",", $value->keywords_publicategory);

                foreach ($keywordsArray as $index => $item) {

                    $keywords_publicategory .= "<span class='badge badge-primary rounded-pill px-3 py-1'>" . $item . "</span>";
                }

                $publisubcategories_publicategory = $value->publisubcategories_publicategory;

                $publications_publicategory = $value->publications_publicategory;

                $views_publicategory = "<span class='badge badge-warning rounded-pill px-3 py-1'><i class='fas fa-eye'></i> " . $value->views_publicategory . "</span>";

                $date_updated_publicategory = $value->date_updated_publicategory;

                $actions = "<div class='btn-group'>
									<a href='/admin/publicategorias/gestion?publicategory=" . base64_encode($value->id_publicategory) . "' class='btn bg-purple border-0 rounded-pill mr-2 btn-sm px-3'>
										<i class='fas fa-pencil-alt text-white'></i>
									</a>
									<button class='btn btn-dark border-0 rounded-pill mr-2 btn-sm px-3 deleteItem' rol='admin' table='publicategories' column='publicategory' idItem='" . base64_encode($value->id_publicategory) . "'>
										<i class='fas fa-trash-alt text-white'></i>
									</button>
								</div>";

                $actions = TemplateController::htmlClean($actions);

                $dataJson .= '{ 
						"id_publicategory":"' . ($start + $key + 1) . '",
						"status_publicategory":"' . $status_publicategory . '",
						"name_publicategory":"' . $name_publicategory . '",
						"url_publicategory":"' . $url_publicategory . '",
						"image_publicategory":"' . $image_publicategory . '",
						"description_publicategory":"' . $description_publicategory . '",
						"keywords_publicategory":"' . $keywords_publicategory . '",
						"publisubcategories_publicategory":"' . $publisubcategories_publicategory . '",
						"publications_publicategory":"' . $publications_publicategory . '",
						"views_publicategory":"' . $views_publicategory . '",
						"date_updated_publicategory":"' . $date_updated_publicategory . '",
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