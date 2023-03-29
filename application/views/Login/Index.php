<style>
body {
  margin: 0;
  padding: 0;
  background-color: #c3d1db;
  height: 100vh;
}
#login .container #login-row #login-column #login-box {
  margin-top: 50px;
  max-width: 600px;
  /*height: 320px;*/
  border: 1px solid #9C9C9C;
  background-color: #EAEAEA;
  border-radius: 10pt;
}
#login .container #login-row #login-column #login-box #login-form {
  padding: 20px;
}
#login .container #login-row #login-column #login-box #login-form #register-link {
  margin-top: -85px;
}
</style>

<div id="login">
        <!-- <h3 style="margin-top:30px; color:white" class="text-center text-white pt-5">SISTEMA DE SOLICITUD DE PRODUCTOS</h3> -->
        
        <div class="container">
            
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-offset-3 col-md-6">
                    <div id="login-box" class="col-md-12">
                    <!-- <div class="loginLogo">
                            <img  src="assets/img/pharmaquick.png" />
                        </div> -->
                        <form id="login-form" class="form" action="AuthController/LoginCliente" method="post">
                            <div class="text-center" >
                            <img style="width:280px"  src="assets/img/pharmaquick.png" />
                            </div>
                            <h3 class="text-center text-info">Acceso</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Usuario:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Contraseña:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <!-- <label for="remember-me" class="text-info"><span>Recordarme</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br> -->

                                <input type="submit" name="submit" class="btn btn-primary btn-block" value="Ingresar">
                            </div>
                            <!-- <div id="register-link" class="text-right">
                                <a href="#" class="text-info">Register here</a>
                            </div> -->
                        </form>
                        <div>
                            <h4 style="color:green">Demo version</h4>
                            <ul>
                                <li>User: VDH74714</li>
                                <li>Password: VDH74714</li>
                            </ul>
                            <ul>
                                <li>User: OFI92977</li>
                                <li>Password: OFI92977</li>
                            </ul>
                            <ul>
                                <li>User: VNN32463</li>
                                <li>Password: VNN32463</li>
                            </ul>
                            <ul>
                                <li>User: KHD58302</li>
                                <li>Password: KHD58302</li>
                            </ul>
                            
                        </div>
                        <div class="text-right">
                            <a href ="<?php echo base_url() ?>admin"> Switch to admin version </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>