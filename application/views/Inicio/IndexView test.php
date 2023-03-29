<!-- <div class="container">
    <h2>
    <?php echo $titulo ?>
    </h2>
    <div data-bind="text:firstName"></div>

    La matriz es: <?= $lista ?>

    <ul data-bind="foreach:lista">
        <li data-bind="text:val"></li> 
    </ul>
<form method="POST" action="<?php echo base_url() ?>Inicio/SetLista">
    <select name="seleccion" data-bind="options:lista, optionsText:'val', optionsValue:'id', optionsCaption:'Seleccione una opción'"></select>
    <button >Enviar</button> -->

    

</form>
    <script>
        var listaJson=<?= $lista ?>;
    </script>
</div>

<!-- KO -->

<table border="1">
    <tr>
        <th>Codigo</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Marca</th>
        <th>Cantidad </th>
    </tr>
    <!-- ko foreach:productos -->
        <tr>
            <td data-bind="text:codigo"></td>
            <td data-bind="text:descripcion"></td>
            <td data-bind="text:precio"></td>
            <td data-bind="text:marca"></td>
            <td>
                <div data-bind="visible: !estaAgregado()" >
                    

                    <div class="input-group">
                    <input data-bind="value:cantidad" type="text" style="width:50px" class="form-control input-sm" value="1" />
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-primary"  data-bind="click: $root.AgregarProducto" >Añadir</button>
                        </span>
                    </div>

                </div>
                <div data-bind="visible:estaAgregado(), text:cantidad()+' Agregados'" >Agregado!</div>
            </td>
        </tr>
    <!-- /ko -->
</table>

<h3>Seleccionados:</h3>
<table border="1" data-bind="visible:seleccionados().length>0">
    <tr>
        <th>Codigo</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Marca</th>
        <th>Cantidad </th>
    </tr>
    <!-- ko foreach:seleccionados -->
    <tr>
            <td data-bind="text:codigo"></td>
            <td data-bind="text:descripcion"></td>
            <td data-bind="text:precio"></td>
            <td data-bind="text:marca"></td>
            <td>
                <input type="text" data-bind='value:cantidad,valueUpdate: "afterkeydown"' />
                <a style="cursor:pointer" data-bind="click: $root.QuitarProducto">Quitar</a>
            </td>
        </tr>
    <!-- /ko -->
</table>

<!-- <ul class="botonesEnlaces" data-bind="foreach:botonesEnlaces()">
    <li>
        <a data-bind="text:texto,attr:{href:enlace}"></a>
    </li>
</ul> -->



<div>
    <button type="button" class="btn btn-xs btn-primary">-</button>
    <span> </span>
    <button type="button" class="btn btn-xs btn-primary">+</button>
</div>