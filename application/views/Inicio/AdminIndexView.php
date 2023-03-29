<style>
    #solicitudesSeccion,
    #clientesSeccion,
    #cuentaSeccion,
    #productosSeccion,
    #laboratoriosSeccion {
        display: none;
    }

    .tabla-productos {
        font-size: 9.6pt;
    }
</style>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="navbar-brand">
                <img style="margin-top: -10px;" src="assets/img/pharmaquick.png" />
            </div>

        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-5">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#solicitudes">Solicitudes</a>
                </li>
                <li>
                    <a href="#clientes">Farmacias</a>
                </li>
                <li>
                    <a href="#productos">Productos</a>
                </li>
                <li>
                    <a href="#laboratorios">Laboratorios</a>
                </li>
            </ul>
            </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $nombre ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#cuenta">Mi cuenta</a></li>
                        <!-- <li role="separator" class="divider"></li>
                <li><a href="#">Opciones</a></li> -->
                    </ul>
                </li>
                <li><a href="<?php echo base_url() ?>AuthController/LogOutAdmin" class="navbar-link">Cerrar Sesión</a>
                <li>

            </ul>
        </div>
    </div>
</nav>





<div class="container" id="solicitudesSeccion">
    <h3>Historial de solicitudes</h3>
    <div class="table-responsive col-md-offset-1 col-md-10">
        <table class="table table-striped">
            <tr>
                <th>Fecha de solicitud</th>
                <th>Cliente</th>
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
                <td data-bind="text:cliente"></td>
                <td data-bind="text:laboratorio"></td>
                <!-- <td data-bind="text:precioReal    "></td>
                <td data-bind="text:descuentoTotal"></td> -->
                <td data-bind="text:cantidad"></td>
                <td data-bind="text:precioFinal"></td>
                <td><button data-bind="click: $root.VerDetalleSolicitud" type="button" class="btn btn-sm btn-primary">Detalles <span class="glyphicon glyphicon-eye-open"></span></button></td>
            </tr>
            <!-- /ko -->
        </table>
        <div id="btnPaginacionSolicitudes"></div>
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
                    <h5 data-bind="html:'<b>CLIENTE:</b> ' + cliente"></h5>
                    <h5 data-bind="html:'<b>LABORATORIO:</b> ' + laboratorio"></h5>
                    <h5 data-bind="html:'<b>Fecha y hora de pedido:</b> ' + fechaSolicitud"></h5>
                    <h5 data-bind="html:'<b>Tipo de pago:</b> '+ tipoPago"></h5>
                    <table class="table table-condensed table">
                        <tr>
                            <th>Codigo</th>
                            <th>Descripción</th>
                            <th>Precio real (Bs.)</th>
                            <th>Descuento (%)</th>
                            <th>Monto de descuento (Bs.)</th>
                            <th>Cantidad </th>
                            <th>Monto total (Bs.)</th>

                        </tr>
                        <!-- ko foreach:detalle -->
                        <tr>
                            <td data-bind="text:codigo"></td>
                            <td data-bind="html:producto"></td>
                            <td data-bind="text:precioReal"></td>
                            <td data-bind="text:descuento">
                            <td data-bind="text:precioFinal"></td>
                            <td data-bind="text:cantidad"></td>
                            <td data-bind="text:total"></td>


                            </td>
                        </tr>
                        <!-- /ko -->
                        <tr >
                            <td>TOTAL:</td>
                            <td colspan="5" >
                                <p>Otros descuentos</p>
                                <p style="font-weight:bold" data-bind="text:$root.sumaTotalLiteral"></p>
                            </td>
                            <!-- <td data-bind="text:cantidadTotal"></td> -->
                            <td>
                                <p data-bind="text:'- ' + descuentoGeneral"></p>
                                <p style="font-weight:bold"  data-bind="text:precioFinal"></p>
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
</div>
<br>




<div class="container" id="cuentaSeccion">
    <div class="row">
        <div class="col-md-offset-2 col-md-8" data-bind="with:datosCuenta">
            <b>Nombre del administrador: </b>
            <p data-bind="text:nombre"></p>
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

<div class="container" id="clientesSeccion">
    <h3>Lista de farmacias (clientes)</h3>
    <p class="text-center">
        <button data-bind="click:$root.ModalNuevoCliente" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Agregar nueva farmacia</button>

    </p>
    <div class="table-responsive col-md-offset-1 col-md-10">
        <table class="table table-striped table-hover">
            <tr>
                <th>Razón social</th>
                <th>Código</th>
                <th>Nombre de usuario</th>
                <th>Correo electrónico</th>
                <th>Región</th>
                <th style="min-width: 130px;"> </th>
            </tr>
            <!-- ko foreach:clientesLista -->
            <tr>
                <td data-bind="text:cliente"></td>
                <td data-bind="text:codigo"></td>
                <td data-bind="text:usuario"></td>
                <td data-bind="text:email"></td>
                <td data-bind="text:nombreRegion"></td>
                <td><button type="button" class="btn btn-sm btn-warning" data-bind="click:$root.ModalEditarCliente"> Editar <span class="glyphicon glyphicon-pencil"></span></button>
                    <button type="button" class="btn btn-sm btn-danger" data-bind="click:$root.ModalEliminarCliente"> <span class="glyphicon glyphicon-trash"></span></button>
                </td>
            </tr>
            <!-- /ko -->
        </table>
        <div id="btnPaginacionClientes"></div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modal_cliente" data-bind="with:Cliente">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" data-bind="text:titulo"> </h4>
                </div>
                <div class="modal-body">
                    <p>Los campos con (*) son obligatorios</p>
                    <div class="container-fluid">
                        <div class="col-md-offset-2 col-md-8">
                            <h4>Debera llenar los campos para la información de la farmacia:</h4>
                            <div class="form-group col-md-9">
                                <label for="ra">Razón social/nombre *</label>
                                <input type="text" data-bind="value:nombre" class="form-control" id="ra" placeholder="Ingrese aqui el nombre de la farmacia">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="lblcod">Codigo</label>
                                <input type="text" data-bind="value:codigo" class="form-control" id="lblcod">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="usuario">Nombre de usuario *</label>
                                <input type="text" data-bind="value:usuario" class="form-control" id="usuario" placeholder="Ingrese aqui el nombre de usuario para el sistema">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lblregion">Región *</label>
                                <select class="form-control" data-bind="options: $root.lista_regiones(), optionsText:'nombre', optionsValue:'idRegion', optionsCaption:'Seleccione una región', value: region" id="lblregion"></select>


                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Correo electrónico</label>
                                <input type="text" data-bind="value:email" class="form-control" id="email" placeholder="Ingrese aqui el correo electrónico de la farmacia">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="password">Contraseña *</label>
                                <input type="password" data-bind="value:password" class="form-control" id="password" placeholder="Ingrese aqui su contraseña">
                                <!-- <sub>Deje en blanco el campo si no desea cambiar su contraseña</sub> -->
                            </div>
                            <!-- <div data-bind="visible:$root.avisoCuenta()==1" class="alert alert-success" role="alert">Se ha guardado el usuario, es necesario volver a iniciar sesión para aplicar los cambios</div>
                            <div data-bind="visible:$root.avisoCuenta()==2" class="alert alert-danger" role="alert">Ocurrio un error al guardar el usuario</div> -->


                        </div>
                        <p data-bind="text:mensajeModalCliente()" class="col-md-12 text-center" style="color:red;font-weight:bold;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bind="click:GuardarCliente" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar_cliente" data-bind="with:Cliente">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Confirmar eliminación </h4>
                </div>
                <div class="modal-body">
                    <p>Desea eliminar permanentemente el cliente?</p>
                    <strong data-bind="text:nombre"></strong>
                    <p>Este proceso no se podra deshacer</p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bind="click:EliminarCliente" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" id="productosSeccion">
    <div class="row">

        <div class="col-md-6">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="idseleclabo" class="col-sm-5 control-label">Laboratorio:</label>
                    <div class="col-sm-7">
                        <select class="form-control" data-bind="options: lista_laboratorios, optionsText:'nombre', optionsValue:'idLaboratorio', optionsCaption:'Seleccione un laboratorio', value: selectLaboratorio" id="idseleclabo"></select>
                        <!-- <input type="text" class="form-control" id="idseleclabo" placeholder="Código" data-bind="value:codigo"> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="selecregpag" class="col-sm-5 control-label">Registros por página:</label>
                    <div class="col-sm-7">
                        <select id="selecregpag" style="width:80px;" class="form-control" data-bind="options: lista_paginas, value: selecTamPag"></select>
                    </div>
                </div>
            </div>

        </div>
        <p class="text-center col-md-10">
            <button data-bind="click:$root.ModalNuevoProducto, visible:selectLaboratorio() != null" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Agregar nuevo producto</button>

        </p>
        <p class="text-center col-md-2">
            <button class="btn btn-default" data-toggle="modal" data-target="#modalDescGeneral"><span class="glyphicon glyphicon-wrench"></span> Descuento general</button>

        </p>

        <table class="table table-striped tabla-productos">
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Forma</th>
                <th><sub>Concentración</sub></th>
                <th><sub>Presentación1</sub></th>
                <th><sub>Presentación2</sub></th>
                <th><sub>Precio Real(Bs.)</sub></th>
                <th><sub>Descuento(%)</sub></th>
                <th><sub>Precio final(Bs.)</sub></th>
                <th> </th>
            </tr>
            <!-- ko foreach:productosLista -->
            <tr>
                <td data-bind="text:codigo"></td>
                <td data-bind="text:producto"></td>
                <td data-bind="text:forma"></td>
                <td data-bind="text:concentracion"></td>
                <td data-bind="text:presentacion1"></td>
                <td data-bind="text:presentacion2"></td>
                <td data-bind="text:precioReal"></td>
                <td data-bind="text:descuento"></td>
                <td data-bind="text:precioFinal"></td>

                <td style="width:90px"><button type="button" class="btn btn-sm btn-warning" data-bind="click:$root.ModalEditarProducto"> <span class="glyphicon glyphicon-pencil"></span></button>
                    <button type="button" class="btn btn-sm btn-danger" data-bind="click:$root.ModalEliminarProducto"> <span class="glyphicon glyphicon-trash"></span></button>
                </td>
            </tr>
            <!-- /ko -->
        </table>
        <div id="btnPaginacionProductos"></div>
    </div>




    <div class="modal fade" tabindex="-1" role="dialog" id="modal_producto" data-bind="with:ProductoActual">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" data-bind="text:titulo"> </h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <p>Los campos con (*) son obligatorios</p>
                        <h4 style="font-weight:bold; text-align:center">Información de producto</h4>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="idcodigo" class="col-sm-4 control-label">Código *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="idcodigo" placeholder="Código" data-bind="value:codigo">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="idproducto" class="col-sm-4 control-label">Producto *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="idproducto" placeholder="Nombre del producto" data-bind="value:producto">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="idforma" class="col-sm-4 control-label">Forma</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="idforma" placeholder="Forma" data-bind="value:forma">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="idconcentracion" class="col-sm-4 control-label">Concentración</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="idconcentracion" placeholder="Concentración" data-bind="value:concentracion">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="idpresentacion1" class="col-sm-4 control-label">Presentación 1</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="idpresentacion1" placeholder="Presentación 1" data-bind="value:presentacion1">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="idpresentacion2" class="col-sm-4 control-label">Presentación 2</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="idpresentacion2" placeholder="Presentación 2" data-bind="value:presentacion2">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="idcodprodlab" class="col-sm-4 control-label">Código producto de laboratorio</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="idcodprodlab" placeholder="Código producto de laboratorio" data-bind="value:codprodlab">
                                </div>
                            </div>

                            <hr />
                            <h4 style="font-weight:bold; text-align:center">Precio y descuentos</h4>
                            <div class="form-vertical">
                                <div class="form-group ">
                                    <label for="idprecioreal" class="col-sm-6 control-label">Precio base (Bs.)*</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" style="max-width:100px;" id="idprecioreal" placeholder="Precio real" data-bind="value:precioReal, valueUpdate: 'afterkeydown'">
                                    </div>
                                </div>

                                <table class="table table-striped table-condensed">
                                    <tr>
                                        <th>Descuento (%)</th>
                                        <th>Condiciones</th>
                                    </tr>
                                    <tr>
                                        <td>Fijo
                                            <input type="text" class="form-control" placeholder="número" data-bind="value: descuento, valueUpdate: 'afterkeydown'" style="max-width:90px;">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Monto compra
                                            <input type="text" class="form-control" placeholder="número" data-bind="value: descuento, valueUpdate: 'afterkeydown'" style="max-width:90px;">
                                        </td>
                                        <td>
                                            Cuando el monto es mayor a Bs.:
                                            <input type="text" class="form-control" placeholder="monto compra" data-bind="value: descuento, valueUpdate: 'afterkeydown'" style="max-width:90px;">

                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td>

                                            Temporada
                                            <input type="text" class="form-control" placeholder="número" style="max-width:90px;">

                                        </td>
                                        <td>
                                            <div style="width:50%; display:inline-block;">
                                                Fecha inicio:
                                                <div class="input-group date" data-provide="datepicker" style="max-width:150px;">
                                                    <input type="text" class="form-control">
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="width:48%; display:inline-block;">
                                                Fecha fin:
                                                <div class="input-group date" data-provide="datepicker" style="max-width:150px;">
                                                    <input type="text" class="form-control">
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Vencimiento
                                            <input type="text" class="form-control" placeholder="número" style="max-width:90px;">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Variable

                                            <input type="text" class="form-control" placeholder="número"  style="max-width:90px;">
                                        </td>

                                        <td>
                                            Motivo
                                            <input type="text" class="form-control" placeholder="descripción">
                                        </td>
                                    </tr>
                                </table>
                                <!-- <div class="form-group ">
                                    <label for="lblDescuento" class="col-sm-6 control-label" >Descuento fijo(%)*</label>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" id="lblDescuento" placeholder="Precio real" data-bind="value: descuento, valueUpdate: 'afterkeydown'">
                                    </div>
                                </div> -->

                            </div>
                            <div class="form-group">
                                <label style="font-size: 14pt;" for="lblPrecioFinal" class="col-sm-6 control-label">Precio final (Bs.)</label>
                                <div class="col-sm-4">
                                    <input type="text" readonly class="form-control input-lg" id="lblPrecioFinal" placeholder="Precio real" data-bind="value:(precioReal() -(precioReal() * (descuento()/100 ))).toFixed(2)">
                                </div>
                            </div>

                        </div>
                        <p data-bind="text:mensajeModalCliente()" class="col-md-12 text-center" style="color:red;font-weight:bold;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bind="click:GuardarProducto" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar_producto" data-bind="with:ProductoActual">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Confirmar eliminación </h4>
                </div>
                <div class="modal-body">
                    <p>Desea eliminar permanentemente el producto?</p>
                    <strong data-bind="text:producto"></strong>
                    <p>Este proceso no se podra deshacer</p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bind="click:EliminarProducto" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDescGeneral" tabindex="-1" role="dialog" aria-labelledby="modalDescGeneral">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"> Descuento general</h4>
                </div>
                <div class="modal-body">
                    <h4>Aqui puede editar el descuento general en base al monto de compra y pago en efectivo</h4>
                    <table class="table table-striped">
                        <tr>
                            <td>
                                <h3>1</h3>
                            </td>
                            <td>Descuento por pago en efectivo (%):
                                <input type="text" class="form-control" data-bind="value: descuentoEfectivo, valueUpdate: 'afterkeydown'" style="max-width:90px;">
                            </td>
                            <td></td><td></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>2</h3>
                            </td>
                            <td>
                                Cuando el monto es igual o mayor a Bs.:
                                <input type="text" class="form-control" placeholder="monto compra" data-bind="value: descuentoMontoBs, valueUpdate: 'afterkeydown'" style="max-width:160px;">

                            </td>
                            <td>aplicar descuento de (%):
                                <input type="text" class="form-control" data-bind="value: descuentoMonto, valueUpdate: 'afterkeydown'" style="max-width:90px;">
                            </td>
                            <td>resultado:
                                <input type="text" readonly class="form-control" data-bind="value: descuentoMontoBs()- (descuentoMontoBs() * (descuentoMonto() / 100))" style="max-width:90px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" data-bind="click:GuardarDescuentoGeneral">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" id="laboratoriosSeccion">
    <div class="row">
        <h4 class="text-center">Habilitar laboratorios por región</h4>
        <div class="col-md-12">

            <div class="form-horizontal">
                <div class="form-group">
                    <div class="tableFixHead">
                        <!-- <select class="form-control" data-bind="options: lista_laboratorios, optionsText:'nombre', optionsValue:'idLaboratorio', optionsCaption:'Seleccione un laboratorio', value: selectLaboratorio" ></select> -->

                        <table class="table table-striped tabla-productos table-fixed table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <!-- <th>Telefonos</th>
                                <th>Logo</th>
                                <th><sub>E-mail</sub></th>
                                <th><sub>Activo</sub></th> -->
                                    <th><sub>La Paz</sub></th>
                                    <th><sub>El Alto</sub></th>
                                    <th><sub>Cochabamba</sub></th>
                                    <th><sub>Quillacollo</sub></th>
                                    <th><sub>Santa Cruz</sub></th>
                                    <th><sub>Montero</sub></th>
                                    <th><sub>Oruro</sub></th>
                                    <th><sub>Potosí</sub></th>
                                    <th><sub>Chuquisaca</sub></th>
                                    <th><sub>Tarija</sub></th>
                                    <th><sub>Beni</sub></th>
                                    <th><sub>Pando</sub></th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- ko foreach:lista_laboratorios -->
                                <tr>
                                    <td data-bind="text:nombre"></td>
                                    <!-- <td data-bind="text:telefonos"></td>
                                <td data-bind="text:logo"></td>
                                <td data-bind="text:email"></td>
                                <td data-bind="text:activo"></td> -->
                                    <!-- <td data-bind="text:LaPaz"></td>
                                <td data-bind="text:Cochabamba"></td>
                                <td data-bind="text:SantaCruz"></td>
                                <td data-bind="text:Oruro"></td>
                                <td data-bind="text:Potosi"></td>
                                <td data-bind="text:Chuquisaca"></td>
                                <td data-bind="text:Tarija"></td>
                                <td data-bind="text:Beni"></td>
                                <td data-bind="text:Pando"></td> -->

                                    <td><input type="checkbox" data-bind="checked: LaPaz" /></td>
                                    <td><input type="checkbox" data-bind="checked: ElAlto" /></td>
                                    <td><input type="checkbox" data-bind="checked: Cochabamba" /></td>
                                    <td><input type="checkbox" data-bind="checked: Quillacollo" /></td>
                                    <td><input type="checkbox" data-bind="checked: SantaCruz" /></td>
                                    <td><input type="checkbox" data-bind="checked: Montero" /></td>
                                    <td><input type="checkbox" data-bind="checked: Oruro" /></td>
                                    <td><input type="checkbox" data-bind="checked: Potosi" /></td>
                                    <td><input type="checkbox" data-bind="checked: Chuquisaca" /></td>
                                    <td><input type="checkbox" data-bind="checked: Tarija" /></td>
                                    <td><input type="checkbox" data-bind="checked: Beni" /></td>
                                    <td><input type="checkbox" data-bind="checked: Pando" /></td>


                                    <!-- <td style="width:90px"><button type="button" class="btn btn-sm btn-warning" data-bind="click:$root.ModalEditarProducto"> <span class="glyphicon glyphicon-pencil"></span></button>
                                <button type="button" class="btn btn-sm btn-danger" data-bind="click:$root.ModalEliminarProducto"> <span class="glyphicon glyphicon-trash"></span></button> -->
                                    </td>
                                </tr>
                                <!-- /ko -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <p class="col-md-12 text-center">
            <button class="btn btn-primary" data-bind="click:GuardarRegiones">Guardar cambios</button>
        </p>
    </div>
</div>

<script>
    //GLOBALES:
    regiones = <?php echo $regiones ?>;
</script>