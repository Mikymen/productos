<div class="container" style="background-color: #eaeaea; margin-top:20px; padding:15px;">
  <div class="col-md-offset-4 col-md-4">
  <div class="loginLogo">
                <img  src="assets/img/logo.png" />
            </div>
      <form class="form-signin" action="AuthController/LoginAdmin" method="post">
        <h3 class="form-signin-heading text-center">Administrador del sistema</h3>
        <label for="inputEmail" class="sr-only">Usuario</label>
        <input type="text" name='username' id="inputEmail" class="form-control" placeholder="Usuario" required autofocus>
        <label for="inputPassword" class="sr-only">Contraseña</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Contraseña" required>
        <!-- <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div> -->
        <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
      </form>
      <div>
          <h4 style="color:green">Demo version</h4>
          <ul>
              <li>User: miguel</li>
              <li>Password: 123</li>
          </ul>
          <ul>
              <li>User: admin</li>
              <li>Password: 123</li>
          </ul>
          
      </div>
      <div class="text-right" style="margin-top:30px">
            <a href ="<?php echo base_url() ?>"> Switch to client version </a>
        </div>
  </div>
    
</div> 