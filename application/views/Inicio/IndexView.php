<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <!-- <div class="navbar-brand">Sistema de Solicitud de Productos</div>  -->
            <div class="navbar-brand">
                <img style="margin-top: -10px;" src="assets/img/pharmaquick.png" />
            </div>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-5">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#" data-bind="click:SeccionLaboratorios">Realizar pedido</a>
                </li>
                <!-- <li><a href="#">Link</a></li>             -->
            </ul>
            </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $nombre ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a data-bind="click:SeccionHistorial" href="#">Mis pedidos</a></li>
                        <li><a href="#" data-bind="click:SeccionCuenta">Mi cuenta</a></li>
                        <!-- <li role="separator" class="divider"></li>
                <li><a href="#">Opciones</a></li> -->
                    </ul>
                </li>
                <li><a href="<?php echo base_url() ?>AuthController/LogOutCliente" class="navbar-link">Cerrar Sesión</a>
                <li>

            </ul>
        </div>
    </div>
</nav>



<div class="container" data-bind="visible: seccion()=='laboratorios'">
    <div class="row">
        <h4 class="text-center">Hola! Desde aqui podra gestionar su pedido.</h4>
        <div class="jumbotron">
            <div class="titulo">Seleccione un laboratorio</div>
            <select style="visibility:hidden" id="select-laboratorios" placeholder="Escriba aqui..."></select>
        </div>


    </div>
</div>
<div class="container" data-bind="visible: seccion()=='listaproductos'">
    <div class="row">
        <div class="col-md-12 text-center">
            <div style="margin-bottom:20px;">
                <button type="button" class="btn btn-lg btn-warning" data-bind="click: SeccionLaboratorios"><span class="glyphicon glyphicon-menu-left"></span> Elegir otro laboratorio</button>
            </div>
        </div>

        <div class="col-md-12" style="margin-bottom:20px;">
            <h4><strong>Laboratorio proveedor:</strong></h4>
            <div class="media media-logo">
                <div class="media-left media-middle">
                    <img class="media-object" style="height:64px;" data-bind="attr:{src:ActualLaboLogo()}, visible:ActualLaboLogo()!=null" alt="logo" title="...">

                </div>
                <div class="media-body">
                    <h4 data-bind="text:ActualLaboNombre()">
                        </h3>
                </div>
            </div>

            <!-- <h4><strong>Laboratorio proveedor:</strong><br></h4>
            <div class="row" style="margin-bottom:25px; ">
                <div class="col-md-6">
                
                    <h3 data-bind="text:ActualLaboNombre()"></h3>
                </div>
                <div class="col-md-6">
                    <img style="height:50px;" data-bind="attr:{src:ActualLaboLogo()}"  />
                </div>
            </div> -->
        </div>

        <div class="col-md-7">
            <div class="input-group">
                <input data-bind="value:criterioProducto, event:{keyup:KeyBuscarProducto}, valueUpdate: 'afterkeydown'" type="text" class="form-control" placeholder="Buscar...">
                <span class="input-group-btn">
                    <button data-bind="click:BuscarProducto" class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                <span class="input-group-btn">
                    <button data-bind="click:LimpiarBusqueda" class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </span>
            </div>
            <h4>Lista de productos:</h4>
            <!-- KO -->
            <table class="table table-striped table-condensed table table-bordered" data-bind="">
                <tr>
                    <!-- <th>Laboratorio</th> -->
                    <!-- <th>Codigo</th>                     -->
                    <th>Descripción</th>
                    <th>Precio unitario (Bs.)</th>
                    <th>Descuento (%)</th>
                    <th>Monto del descuento (Bs.)</th>
                    <th>Precio final unitario (Bs.)</th>
                    <th>Cantidad </th>
                </tr>
                <!-- ko foreach:productos -->
                <tr data-bind="style:{'background' : estaAgregado()? '#ddffdd'  : ''}, visible:visible()">
                    <!-- <td data-bind="text:marca"></td> -->
                    <!-- <td data-bind="text:codigo"></td> -->

                    <td data-bind="html:producto"></td>
                    <td data-bind="text:precioReal"></td>
                    <td data-bind="text:descuento"></td>
                    <td data-bind="text:descuento_precio"></td>
                    <td data-bind="text:precioFinal"></td>
                    <td>
                        <div data-bind="visible: !estaAgregado()">
                            <button class="btn btn-block btn-sm btn-success" data-bind="click: $root.AgregarProducto">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>

                            <!-- <div class="input-group">
                                <input data-bind="value:cantidad" type="text" style="width:50px" class="form-control input-sm" value="1" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-success"  data-bind="click: $root.AgregarProducto" >
                                        <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                </div> -->

                        </div>
                        <!-- <div data-bind="visible:estaAgregado(), text:cantidad()+' Agregados'" >Agregado!</div> -->
                        <div data-bind="visible:estaAgregado()">Agregado</div>
                    </td>
                </tr>
                <!-- /ko -->
            </table>
            <div id="btnPaginacion"></div>

        </div>
        <div class="col-md-5 nota-de-pedido-caja">
            <h4 class="nota-de-pedido">NOTA DE PEDIDO</h4>
            <div class="text-center" data-bind="visible:!hayPedidos()">
                <h4>Aun no tiene productos agregados</h4>
                Seleccione uno o varios productos de la lista
            </div>
            <div data-bind="visible:hayPedidos()">
                <table class="table table-condensed table table-bordered">
                    <tr>
                        <!-- <th>Codigo</th> -->
                        <!-- <th>Marca</th> -->
                        <th>Descripción</th>
                        <th>Precio (Bs.)</th>
                        <th>Cantidad </th>
                    </tr>
                    <!-- ko foreach:seleccionados -->
                    <tr>
                        <!-- <td data-bind="text:codigo"></td> -->
                        <!-- <td data-bind="text:marca"></td> -->
                        <td data-bind="html:producto    "></td>
                        <td data-bind="text:total"></td>
                        <td>
                            <div class="input-group">
                                <input class="form-control input-sm" style="width:50px" type="text" data-bind='value:cantidad,valueUpdate: "afterkeydown"' />
                                <span class="input-group-btn">
                                    <button class="btn btn-sm btn-danger" data-bind="click: $root.QuitarProducto"><span class="glyphicon glyphicon-trash"></span></button>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- /ko -->
                    <tr style="font-weight:bold">
                        <td>TOTAL:</td>
                        <td></td>
                        <td colspan="2" data-bind="text:sumaDetalle"></td>
                        <!-- <td data-bind="text:cantidadTotal"></td> -->
                    </tr>
                </table>
                <p class="text-center">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal_solicitud">Confirmar pedido</button>
                </p>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modal_solicitud">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirmar Pedido?</h4>
            </div>
            <div class="modal-body">
                <p>Se hara la solicitud de los siguientes productos:</p>
                <h4 data-bind="html:'<b>LABORATORIO:</b> ' + ActualLaboNombre()"></h4>
                <table class="table table-condensed table">
                    <tr>
                        <!-- <th>Codigo</th> -->
                        <th>Descripción</th>
                        <th>Precio real (Bs.)</th>
                        <th>Descuento (%)</th>
                        <th>Monto de descuento (Bs.)</th>
                        <th>Cantidad </th>
                        <th>Monto total (Bs.)</th>

                    </tr>
                    <!-- ko foreach:seleccionados -->
                    <tr>
                        <!-- <td data-bind="text:codigo"></td> -->
                        <td data-bind="html:producto"></td>
                        <td data-bind="text:precioReal"></td>
                        <td data-bind="text:descuento">
                        <td data-bind="text:precioFinal"></td>
                        <td data-bind="text:cantidad"></td>
                        <td data-bind="text:total"></td>

                    </tr>
                    <!-- /ko -->
                    <tr>
                        <td><strong>TOTAL:</strong></td>
                        <td colspan="4">
                            <p data-bind="text:'Descuento del '+ descuentoEfectivo()  +'% por pago al contado'"></p>
                            <p data-bind="text:'Descuento del '+ descuentoMonto() +'% por monto de compra igual o mayor a '+ descuentoMontoBs() +' Bs. '"></p>
                            <p style="font-weight:bold" data-bind="text:sumaTotalLiteral"></p>
                        </td>
                        <!-- <td data-bind="text:cantidadTotal"></td> -->
                        <td >
                            <p data-bind="text: '- ' + c_descuentoEfectivo()"></p>
                            <p data-bind="text: '- ' + c_descuentoMontoBs()"></p>
                            <p style="font-weight:bold; font-size:14pt;" data-bind="text:sumaTotal"></p>
                        </td>
                    </tr>
                </table>
                Tipo de pago:
                <input type="radio" id="tipo_efectivo" value="efectivo" checked="checked" name="tipo_pago" data-bind="checked: metodoPago" value="0" />
                <label for="tipo_efectivo">Contado</label>
                <input type="radio" id="tipo_credito" value="credito" name="tipo_pago" data-bind="checked: metodoPago" value="1" />
                <label for="tipo_credito">Crédito</label>
            </div>
            <div class="modal-footer">
                <span data-bind="visible:codigoRespuesta()==1 || codigoRespuesta()==2">
                    <div class="alert alert-success" role="alert">Se ha enviado correctamente los datos, puede ingresar <a href="#" data-dismiss="modal" data-bind="click:SeccionHistorial">aqui</a> para ver los pedidos realizados</div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </span>
                <div data-bind="visible:codigoRespuesta()==-1" class="alert alert-info" role="alert">Enviando solicitud...</div>
                <div data-bind="visible:codigoRespuesta()==3" class="alert alert-danger" role="alert">Ocurrio un error al enviar la solicitud</div>
                <span data-bind="visible:codigoRespuesta()==0 || codigoRespuesta()==3">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button data-bind="click:GuardarPedidos" type="button" class="btn btn-success">Enviar confirmación de pedido <span class="glyphicon glyphicon-send"></span></button>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_detalle_solicitud">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detalle del pedido</h4>
            </div>
            <div class="modal-body" data-bind="with:detalleSolicitud">
                <p>Se hizo la solicitud de los siguientes productos:</p>
                <h5 data-bind="html:'<b>LABORATORIO:</b> ' + laboratorio"></h5>
                <h5 data-bind="html:'<b>Fecha y hora de pedido:</b> ' + fechaSolicitud"></h5>
                <h5 data-bind="html:'<b>Tipo de pago:</b> '+ tipoPago"></h5>
                <table class="table table-condensed table">
                    <tr>
                        <!-- <th>Codigo</th> -->
                        <th>Descripción</th>
                        <th>Precio real (Bs.)</th>
                        <th>Descuento (%)</th>
                        <th>Monto de descuento (Bs.)</th>
                        <th>Cantidad </th>
                        <th>Monto total (Bs.)</th>

                    </tr>
                    <!-- ko foreach:detalle -->
                    <tr>
                        <!-- <td data-bind="text:codigo"></td> -->
                        <td data-bind="html:producto"></td>
                        <td data-bind="text:precioReal"></td>
                        <td data-bind="text:descuento">
                        <td data-bind="text:precioFinal"></td>
                        <td data-bind="text:cantidad"></td>
                        <td data-bind="text:total"></td>
                    </tr>
                    <!-- /ko -->
                    <tr >
                        <td>TOTAL:</td>
                        <td colspan="4">
                            <p>Descuentos generales</p>
                            <p style="font-weight:bold" data-bind="text:$root.sumaTotalLiteral"></p>

                        </td>
                        <!-- <td data-bind="text:cantidadTotal"></td> -->
                        <td>
                            <p data-bind="text: '- ' + descuentoGeneral"></p>
                            <p style="font-weight:bold; font-size:14pt;" data-bind="text:$root.sumaTotal"></p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="container" data-bind="visible: seccion()=='historial'">
    <h3>Mis pedidos</h3>
    <div class="table-responsive col-md-offset-1 col-md-10">
        <table class="table table-striped">
            <tr>
                <th>Fecha de solicitud</th>
                <th>Laboratorio</th>
                <!-- <th>Precio Real</th>
                <th>Descuento total</th>         -->
                <th>Cantidad de productos </th>
                <th>Precio total (Bs.) </th>
                <th> </th>
            </tr>
            <!-- ko foreach:productosHistorial -->
            <tr>
                <td style="width:180px;" data-bind="text:fechaSolicitud"></td>
                <td data-bind="text:laboratorio"></td>
                <!-- <td data-bind="text:precioReal    "></td>
                <td data-bind="text:descuentoTotal"></td> -->
                <td data-bind="text:cantidad"></td>
                <td data-bind="text:precioFinal"></td>
                <td><button data-bind="click: $root.VerDetalleSolicitud" type="button" class="btn btn-sm btn-primary">Detalles <span class="glyphicon glyphicon-eye-open"></span></button></td>
            </tr>
            <!-- /ko -->
            <!-- <tr style="font-weight:bold">
                <td>TOTAL:</td>
                <td ></td>
                <td data-bind="text:cantidadSumaTotal"></td>
                <td data-bind="text:precioSumaFinal"></td>
            </tr> -->
        </table>
        <div id="btnPaginacionHistorial"></div>
    </div>
</div>
<br>

<div class="container" data-bind="visible: seccion()=='cuenta'">
    <div class="row">
        <div class="col-md-offset-2 col-md-8" data-bind="with:datosCuenta">
            <b>Nombre del cliente: </b>
            <p data-bind="text:cliente"></p>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="text" data-bind="value:email" class="form-control" id="email" placeholder="Ingrese aqui su correo electrónico">
            </div>
            <div class="form-group">
                <label for="usuario">Nombre de usuario</label>
                <input type="text" data-bind="value:usuario" class="form-control" id="usuario" placeholder="Ingrese aqui su nombre de usuario">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" data-bind="value:password" class="form-control" id="password" placeholder="Ingrese aqui su contraseña">
                <!-- <sub>Deje en blanco el campo si no desea cambiar su contraseña</sub> -->
            </div>
            <div data-bind="visible:$root.avisoCuenta()==1" class="alert alert-success" role="alert">Se ha guardado el usuario, es necesario volver a iniciar sesión para aplicar los cambios</div>
            <div data-bind="visible:$root.avisoCuenta()==2" class="alert alert-danger" role="alert">Ocurrio un error al guardar el usuario</div>

            <button type="button" data-bind="click:$root.GuardarCuenta" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</div>
<script>
    var mislaboratorios = <?php echo $laboratorios; ?>;
    var descuentoEfectivo = <?php echo $descuentoEfectivo; ?>;
    var descuentoMonto = <?php echo $descuentoMonto; ?>;
    var descuentoMontoBs = <?php echo $descuentoMontoBs; ?>;
</script>