<div class="container-fluid bg-dark small footerBlock">
  
  <div class="container py-5 text-light">
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
      
      <div class="col row">

        <div class="col-12 col-sm-4 col-md-3 col-lg-4">
          
          <h4 class="lead">
            
            <a href="#" class="text-uppercase">Ropa</a>

          </h4>

          <hr class="border-white">

          <ul>
            
            <li>
              <a href="#">Ropa para dama</a>
            </li>
            <li>
              <a href="#">Ropa para hombre</a>
            </li>
             <li>
              <a href="#">Ropa deportiva</a>
            </li>
            <li>
              <a href="#">Ropa infantil</a>
            </li>
          </ul>


        </div>

        <div class="col-12 col-sm-4 col-md-3 col-lg-4">
          
          <h4 class="lead">
            
            <a href="#" class="text-uppercase">Calzado</a>

          </h4>

          <hr class="border-white">

          <ul>
            
            <li>
              <a href="#">Calzado para dama</a>
            </li>
            <li>
              <a href="#">Calzado para hombre</a>
            </li>
             <li>
              <a href="#">Calzado deportivo</a>
            </li>
            <li>
              <a href="#">Calzado infantil</a>
            </li>
          </ul>


        </div>

        <div class="col-12 col-sm-4 col-md-3 col-lg-4">
          
          <h4 class="lead">
            
            <a href="#" class="text-uppercase">Tecnología</a>

          </h4>

          <hr class="border-white">

          <ul>
            
            <li>
              <a href="#">Calzado para dama</a>
            </li>
            <li>
              <a href="#">Calzado para hombre</a>
            </li>
             <li>
              <a href="#">Calzado deportivo</a>
            </li>
            <li>
              <a href="#">Calzado infantil</a>
            </li>
          </ul>


        </div>

        <div class="col-12 col-sm-4 col-md-3 col-lg-4">
          
          <h4 class="lead">
            
            <a href="#" class="text-uppercase">Cursos</a>

          </h4>

          <hr class="border-white">

          <ul>
            
            <li>
              <a href="#">Calzado para dama</a>
            </li>
            <li>
              <a href="#">Calzado para hombre</a>
            </li>
             <li>
              <a href="#">Calzado deportivo</a>
            </li>
            <li>
              <a href="#">Calzado infantil</a>
            </li>
          </ul>


        </div>

      </div>

      <div class="col my-3 my-lg-0 px-lg-5 text-light">
        
        <h1 class="lead small">Dudas e inquietudes, contáctenos en:</h1>

        <br>

        <h1 class="lead small">
          
            <i class="fa fa-phone-square pe-2"></i> (555) 555-55-55

            <br><br>

            <i class="fa fa-envelope pe-2"></i> soporte@tiendavirtual.com

            <br><br>

            <i class="fa fa-map-marker pe-2"></i> Calle 45F 82 - 31 Local 102

            <br><br>

            Bogotá | Colombia
        </h1>

        <iframe class="mt-3" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.061210342917!2d-75.60279258568647!3d6.255666795471985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e4429739f2122e9%3A0x4812b922f0ad8f18!2sCl.+45f+%2382-31%2C+Medell%C3%ADn%2C+Antioquia!5e0!3m2!1ses!2sco!4v1511900955540" width="100%" height="200" frameborder="0" style="border:0" allowfullscreen=""></iframe>

      </div>

      <div class="col small my-3 my-lg-0">
        
        <h4>RESUELVA SU INQUIETUD</h4>

        <form role="form" method="post">

          <input type="text" id="nombreContactenos" name="nombreContactenos" class="form-control" placeholder="Escriba su nombre" required> 

          <br>
            
          <input type="email" id="emailContactenos" name="emailContactenos" class=" form-control" placeholder="Escriba su correo electrónico" required>  

          <br>
                        
            <textarea id="mensajeContactenos" name="mensajeContactenos" class="form-control" placeholder="Escriba su mensaje" rows="5" required></textarea>

            <br>
      
            <input type="submit" value="Enviar" class="btn btn-default float-end border-0 templateColor">         

      </form>

      </div>

    </div>

  </div>



</div>


<!-- Main Footer -->
<footer class="main-footer topColor">

  <div class="container">
    <!-- To the right -->
    <div class="float-end">

      <div class="d-flex justify-content-center" style="line-height:0px">
          
          <?php foreach ($socials as $key => $value): ?>

            <div class="p-2">
              
              <a href="<?php echo $value->url_social ?>" target="_blank">
                
                <i class="<?php echo $value->icon_social ?> <?php echo $value->color_social ?>"></i>
              
              </a>

            </div>
            
          <?php endforeach ?>


        </div>


     
    </div>
    <!-- Default to the left -->
    <small>&copy; <?php echo date("Y") ?> Todos los derechos reservados. Sitio elaborado por la Compañía.</small>
  </div>
</footer>