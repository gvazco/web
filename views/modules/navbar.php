<?php 

/*=============================================
Categorías
=============================================*/

$select = "id_category,name_category,url_category,icon_category";
$url = "categories?select=".$select;
$method = "GET";
$fields = array();

$dataCategories = CurlController::request($url,$method,$fields);

if($dataCategories->status == 200){

  $dataCategories = $dataCategories->results;

}else{

   $dataCategories = array();
   

}

/*=============================================
Carrito de compras
=============================================*/

if(isset($_SESSION["user"])){

  $select = "id_cart,url_product,type_variant,media_variant,name_product,description_variant,quantity_cart,offer_variant,price_variant";
  $url = "relations?rel=carts,variants,products&type=cart,variant,product&linkTo=id_user_cart&equalTo=".$_SESSION["user"]->id_user."&select=".$select;
  $method = "GET";
  $fields = array();

  $carts = CurlController::request($url,$method,$fields);

  if($carts->status == 200){

    $carts = $carts->results;

  }else{

    $carts = array();    

  }

}else{

  $carts = array();  

}

?>



<!-- Navbar -->

<div class="container py-2 py-lg-4">

  <div class="row">
    
    <div class="col-12 col-lg-2 mt-1">
      
      <div class="d-flex justify-content-center">
        
        <a href="<?php echo $path ?>" class="navbar-brand">
          <img src="<?php echo $path ?>views/assets/img/template/<?php echo $template->id_template ?>/<?php echo $template->logo_template ?>" class="brand-image img-fluid py-3 px-5 p-lg-0 pe-lg-3">
        </a>

      </div>

    </div>

    <div class="col-12 col-lg-7 col-xl-8 mt-1 px-3 px-lg-0">
      
      <?php if (isset($_SESSION["admin"])): ?>

        <a class="nav-link float-start" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        
      <?php endif ?>
     
      <div class="dropdown px-1 float-start templateColor">

        <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle text-uppercase">
          <span class="d-lg-block d-none">Categorias<i class="ps-lg-2 fas fa-th-list"></i></span>
          <span class="d-lg-none d-block"><i class="fas fa-th-list"></i></span>

        </a>

        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">

          <?php foreach ($dataCategories as $key => $value): ?>

            <?php 

              $select = "name_subcategory,url_subcategory";
              $url = "subcategories?linkTo=id_category_subcategory&equalTo=".$value->id_category."&select=".$select;
              $method = "GET";
              $fields = array();

              $dataSubcategories = CurlController::request($url,$method,$fields);

              if($dataSubcategories->status == 200){

                $dataSubcategories = $dataSubcategories->results;

              }else{

                $dataSubcategories = array();
                
              }

           ?>

            <li class="dropdown-submenu dropdown-hover">

              <a id="dropdownSubMenu<?php echo $key ?>" href="/<?php echo $value->url_category ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle text-uppercase" onclick="redirect('/<?php echo $value->url_category ?>')"  >

                <i class="<?php echo $value->icon_category ?> pe-2 fa-xs"></i>  <?php echo $value->name_category ?>

              </a>

              <ul class="border-0 shadow py-3 ps-3 d-block d-lg-none">

                <?php foreach ($dataSubcategories as $index => $item): ?>

                  <li>
                    <a tabindex="-1" href="/<?php echo $item->url_subcategory ?>" class="dropdown-item"><?php echo $item->name_subcategory ?></a>
                  </li>
                    
                <?php endforeach ?>
 
              </ul>

              <ul aria-labelledby="dropdownSubMenu<?php echo $key ?>" class="dropdown-menu border-0 shadow menuSubcategory">

                <?php foreach ($dataSubcategories as $index => $item): ?>

                  <li>
                    <a tabindex="-1" href="/<?php echo $item->url_subcategory ?>" class="dropdown-item"><?php echo $item->name_subcategory ?></a>
                  </li>
                    
                <?php endforeach ?>
              
              </ul>

            </li>
            
          <?php endforeach ?>

        </ul>
      </div>

      <form class="form-inline">
        <div class="input-group input-group w-100 me-0 me-lg-4">
          <input class="form-control rounded-0 p-3 pe-5 inputSearch" type="search" placeholder="Buscar..." style="height:40px">
          <div class="input-group-append px-2 templateColor">
            <button class="btn btn-navbar text-white btnSearch" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>

    </div>

    <div class="col-12 col-lg-3 col-xl-2 mt-1 px-3 px-lg-0">
      
      <div class="my-2 my-lg-0 d-flex justify-content-center">
        
        <a href="/carrito">
          
          <button class="bt btn-default float-start rounded-0 border-0 py-2 px-3 templateColor">
            
            <i class="fa fa-shopping-cart"></i>

          </button>

        </a>

        <div class="small border float-start ps-2 pe-5 w-100">


          <?php 

          $shoppingBasket = 0;
          $totalShop = 0;

          if(!empty($carts)){

            foreach ($carts as $key => $value) {

              $shoppingBasket+=$value->quantity_cart;

              if($value->offer_variant > 0){

                $totalShop += $value->quantity_cart*$value->offer_variant;

              }else{

                $totalShop += $value->quantity_cart*$value->price_variant;

              }

            }
          
          }

          ?>
          
          TU CESTA <span id="shoppingBasket"><?php echo $shoppingBasket ?></span><br> USD $<span id="totalShop"><?php echo number_format($totalShop,2) ?></span>

        </div>

      </div>

    </div>

  </div>

</div>

<script>
  
  function redirect(value){

    window.location = value;
  }

  if(window.matchMedia("(max-width:768px)").matches){

    $(".menuSubcategory").remove();

  }

</script>

  <!-- /.navbar -->