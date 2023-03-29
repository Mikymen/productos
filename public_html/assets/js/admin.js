$(function(){
    contextSolicitudes= document.getElementById("solicitudesSeccion");
    contextClientes= document.getElementById("clientesSeccion");
    contextCuenta= document.getElementById("cuentaSeccion");
    contextProductos= document.getElementById("productosSeccion");
    contextLaboratorios= document.getElementById("laboratoriosSeccion");

    var seccionesIds=["solicitudesSeccion","clientesSeccion","cuentaSeccion","productosSeccion","laboratoriosSeccion"];
    var cambioSeccion=function(idSeccion){
        seccionesIds.forEach(function(val,index) {
            $("#"+val).css("display","none");            
        });
        $("#"+idSeccion).css("display","block");
        //console.log($("#"+idSeccion));
    }
    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $.fn.datepicker.defaults.language = "es";
    var fechaFormato = $($('.date')[0]).datepicker("getFormattedDate") ;   
    
    SolicitudesModel= function(){
        var self=this;
        self.detalleSolicitud=ko.observable();
        self.productos=ko.observable();
        self.seleccionados=ko.observableArray();
        self.productosHistorial=ko.observable();

        self.PaginacionHistorial=function(pagActual,paginas,tamPag){
            $("#btnPaginacionSolicitudes").pagination({
                items: paginas,
                itemsOnPage: tamPag,
                currentPage:pagActual,
                cssStyle: 'light-theme',
                onPageClick:function(pageNumber, event){
                    self.IniciarPaginacionHistorial(pageNumber, tamPag);
                },
                prevText:"Anterior",
                nextText:"Siguiente",

            });
        }
        self.IniciarPaginacionHistorial=function(_pag, _tam){
            $.post("InicioAdminController/GetHistorial", {clave:"Miguel",npagina:_pag, tamPag:_tam}, function(allData){
                self.productosHistorial(allData.res);
                //OJO deberia cargar solo una vez
                self.PaginacionHistorial(allData.npagina,  allData.paginas, allData.tamPag);

            },"json");
        };
        
        

        self.VerDetalleSolicitud=function(solicitud){            
            $.post("InicioAdminController/GetPedidos", {clave:"Miguel",idSolicitud:solicitud.idSolicitud, idCliente: solicitud.idCliente}, function(res){
                $("#modal_detalle_solicitud").modal("show");
                var productos=$.map(res,function(item){
                    return new Producto(item);
                });
                self.seleccionados(productos);
                solicitud.detalle=self.seleccionados();
                
                self.detalleSolicitud(solicitud);                

            },"json");  
        };
        self.sumaTotal=ko.computed(function(){
            var suma = 0;
            $.each(self.seleccionados(),function (i, item){
                suma += parseFloat(item.total());
            }); 
            return suma.toFixed(2);
        });
        self.cantidadTotal=ko.computed(function(){
            var suma = 0;
            $.each(self.seleccionados(),function (i, item){
                suma += parseInt(item.cantidad());
            }); 
            return suma;
        });
        self.sumaTotalLiteral=ko.computed(function(){
            return numeroALetras(self.sumaTotal(), {
                plural: 'BOLIVIANOS',
                singular: 'BOLIVIANO',
                centPlural: 'CENTAVOS',
                centSingular: 'CENTAVO'
              });
        });

        function Producto(data){
            var self=this;
            self.idProducto=data.idProducto;
            self.codigo=data.codigo;
            self.producto=data.producto;
            self.precioReal=data.precioReal;
            self.descuento=data.descuento+"%";
            self.precioFinal=data.precioFinal;
            self.descuentoGeneral=data.descuentoGeneral;
            self.laboratorio=data.laboratorio;
            self.cantidad=ko.observable(data.cantidad!=null?data.cantidad:1);
            self.estaAgregado=ko.observable(false);
            self.total=ko.computed(function(){
                return (self.cantidad()*self.precioFinal).toFixed(2);
            },this);
            self.descuento_precio=ko.computed(function(){
                return (self.precioReal - self.precioFinal).toFixed(2);
            });
            self.visible=ko.observable(true);
        }
        //iniciadores
        self.CargarSeccionSolicitudes=function(forzar=false){
            if(forzar || self.productosHistorial()==null ){
                self.IniciarPaginacionHistorial(1, 10);
            }
        }
    }

    ClientesModel = function(){
        var self=this;
        self.clientesLista = ko.observable();
        self.Cliente=ko.observable();
        self.pagActual=1;
        self.lista_regiones=ko.observable(regiones || null);
        //self.selectRegion=ko.observable();
        self.PaginacionClientes=function(pagActual,paginas,tamPag){
            $("#btnPaginacionClientes").pagination({
                items: paginas,
                itemsOnPage: tamPag,
                currentPage:pagActual,
                cssStyle: 'light-theme',
                onPageClick:function(pageNumber, event){
                    self.IniciarPaginacionClientes(pageNumber, tamPag);
                },
                prevText:"Anterior",
                nextText:"Siguiente",

            });
            self.pagActual=pagActual;
        };
        //cargar a la lista
        self.IniciarPaginacionClientes=function(_pag, _tam){
            $.post("InicioAdminController/GetListaClientes", {clave:"Miguel",npagina:_pag, tamPag:_tam}, function(allData){
                self.clientesLista(allData.res);
                //OJO deberia cargar solo una vez
                self.PaginacionClientes(allData.npagina,  allData.paginas, allData.tamPag);

            },"json");
        };


        //iniciar
        self.CargarSeccionClientes=function(forzar=false){
            if(forzar || self.clientesLista()==null ){
                self.IniciarPaginacionClientes(self.pagActual,10);
            }
        }
        self.ModalEditarCliente=function(model){
            //var self=this;
            console.log(model);
            var cliente= self.Cliente();
            cliente.modo="editar";
            cliente.idCliente(model.idCliente);
            cliente.nombre(model.cliente);
            cliente.codigo(model.codigo);
            cliente.usuario(model.usuario);
            cliente.password("      ");
            cliente.email(model.email || "");
            cliente.region(model.idRegion);
            cliente.titulo("Editar farmacia");
            $("#modal_cliente").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
        self.ModalNuevoCliente=function(){
            self.Cliente(new ClienteClass());
            $("#modal_cliente").modal({
                backdrop: 'static',
                keyboard: false
            });
        };
        self.ModalEliminarCliente=function(model){
            var cliente= self.Cliente();
            cliente.idCliente(model.idCliente);
            cliente.nombre(model.cliente);
            $("#modal_eliminar_cliente").modal("show");
        };
        
        self.Cliente=ko.observable(ClienteClass());
        function ClienteClass(){return {
            modo:"nuevo",
            idCliente:ko.observable(),
            nombre:ko.observable(""),
            codigo:ko.observable(""),
            usuario:ko.observable(""),
            password:ko.observable(""),
            email:ko.observable(""),
            region:ko.observable(),
            titulo:ko.observable("Nueva farmacia"),
            mensajeModalCliente:ko.observable(),
            GuardarCliente:function(){
                var mensaje=this.mensajeModalCliente;
                this.mensajeModalCliente(null);
                if(this.Validar()){
                    $.post("InicioAdminController/GuardarCliente", {
                        modo:this.modo,
                        idCliente:this.idCliente,
                        clave:"Miguel",
                        nombre:this.nombre(), 
                        codigo:this.codigo(), 
                        usuario:this.usuario(),
                        password:this.password,
                        email:this.email,
                        idRegion:this.region()
                    }, function(res){
                        switch(res.Respuesta){
                            case 1:
                                mensaje(null);
                                self.IniciarPaginacionClientes(self.pagActual,10);
                                $("#modal_cliente").modal("hide");
                            break;
                            case 0:
                                mensaje(res.Detalle);
                            break;
                        }
        
                    },"json");
                }else{
                    this.mensajeModalCliente("Debe llenar los campos obligatorios (*)");
                }
                
            },            
            EliminarCliente:function(){
                $.post("InicioAdminController/EliminarCliente", {
                    idCliente:this.idCliente,
                }, function(id){
                    if(id>0){
                        self.IniciarPaginacionClientes(self.pagActual,10);
                        $("#modal_eliminar_cliente").modal("hide");
                    }
    
                },"json");
            },
            Validar:function(){
                
               return this.region()!=null && this.nombre()!="" && this.password()!="" && this.usuario()!="";
            }
        }};

    }

    CuentasModel=function(){
        var self=this;
        self.datosCuenta=ko.observable();
        self.CargarSeccionCuenta=function(forzar=false){
            if(forzar || self.datosCuenta()==null ){
                $.post("InicioAdminController/GetCuenta", {clave:"Miguel"}, function(allData){
                    allData.password="            ";
                    self.datosCuenta(allData);
                },"json");
            }
             
        };
        self.avisoCuenta=ko.observable(0);
        self.GuardarCuenta=function(){
            self.avisoCuenta(0);
            $.post("InicioAdminController/GuardarCuenta", {clave:"Miguel", datos:self.datosCuenta()}, function(res){
                self.avisoCuenta(res);
            },"json"); 
        }
    };
    
    ProductosModel=function(){
        self=this;
        self.productosLista=ko.observable();
        self.productosTotales=ko.observableArray();
        self.tamPag=ko.observable();
        self.selecTamPag=ko.observable();
        self.idLaboActual=ko.observable();
        self.selectLaboratorio=ko.observable();
        self.pagActual;
        self.lista_paginas=[10,20,50,100];
        self.ProductoActual=ko.observable();
        self.lista_laboratorios=ko.observable();
        //self.productos=ko.observable();
        self.descuentoEfectivo=ko.observable();
        self.descuentoMonto=ko.observable();
        self.descuentoMontoBs=ko.observable();

        
        self.CargarPaginaProductos=function(_idLabo, _pag, _tam){
            //self.idLaboActual(_idLabo);
            if(_idLabo==null){
                self.productosLista([]);
                return false;
            } 
            if(self.productosTotales().length>0){
                self.IniciarPaginacion(_idLabo, _pag, _tam);
                
            }else{
                
                $.post("InicioAdminController/GetListaProductos", {idLaboratorio: _idLabo}, function(allData){
                    var res=allData;
                    var productosMapeados = $.map(res,function(item){
                        return new Producto(item)
                    });
                    self.productosTotales(productosMapeados);                    
                    self.IniciarPaginacion(_idLabo, _pag, _tam);                    
                    self.pagActual=_pag;
    
                },"json"); 
                    
            }
        }
        
        self.CargarListaLaboratorios=function(){
            $.post("InicioAdminController/GetLaboratoriosLista",  function(allLabos){
                self.lista_laboratorios(allLabos);

            },"json");
        }
        self.IniciarPaginacion=function(_idLabo, _pag, _tam){           
            
            //var nItems = 0;
            //self.idLaboActual(_idLabo);
            var itemsValidos=[];
            $.each(self.productosTotales(),function (i, item){
                if(item.visible()){
                    itemsValidos.push(item);
                }
            }); 
            while ((itemsValidos.length+_tam) < _tam * _pag){
                _pag--;
            }
            var offset = (_pag-1)*_tam;
            self.productosLista(itemsValidos.slice(offset,offset+_tam));
            self.Paginacion(_pag,  itemsValidos.length, _tam, _idLabo);
        }
        self.Paginacion=function(pagActual,paginas,tamPag, idLabo){
            // while ((paginas+tamPag) < tamPag*pagActual){
            //     pagActual--;
            // }
            $("#btnPaginacionProductos").pagination({
                items: paginas,
                itemsOnPage: tamPag,
                currentPage:pagActual,
                cssStyle: 'light-theme',
                onPageClick:function(pageNumber, event){
                    self.CargarPaginaProductos(idLabo, pageNumber, tamPag);
                },
                prevText:"Anterior",
                nextText:"Siguiente",

            });
            self.pagActual=pagActual;
        };          
        // self.cambioPag=ko.computed(function(){
        //     self.IniciarPaginacion(self.idLabo, self.pagActual, self.tamPag());
        // });
        self.selecTamPag.subscribe(function(selectTam){
            self.tamPag(selectTam);
            if(self.idLaboActual()!=null && self.pagActual!=null){
                self.IniciarPaginacion(self.idLabo, self.pagActual, selectTam);
            }
            
          });
        self.selectLaboratorio.subscribe(function(idLab){
            self.idLaboActual(idLab);
            self.productosTotales([]);
            self.CargarPaginaProductos(self.idLaboActual(), 1,self.tamPag());
        })
        Producto = function(data){
            var self=this;
            self.idProducto=data.idProducto;
            self.idLaboratorio=data.idLaboratorio;
            self.codigo=data.codigo;
            self.producto=data.producto;
            self.forma=data.forma;
            self.concentracion=data.concentracion;
            self.presentacion1=data.presentacion1;
            self.presentacion2=data.presentacion2;
            self.precioReal=data.precioReal;
            self.descuento=data.descuento;
            self.precioFinal=data.precioFinal;
            self.codprodlab=data.codigoProductoLaboratorio
            //self.laboratorio=data.laboratorio;
            self.visible=ko.observable(true);
        }
        ProductoClass = function(){
            return{
                modo:"nuevo",
                idProducto  : ko.observable(),
                idLaboratorio : ko.observable(),
                codigo : ko.observable(""),
                producto : ko.observable(""),
                forma : ko.observable(""),
                concentracion : ko.observable(""),
                presentacion1 : ko.observable(""),
                presentacion2 : ko.observable(""),
                precioReal : ko.observable("0"),
                descuento : ko.observable("0"),
                precioFinal : ko.observable(""),
                // precioFinal : ko.computed(function(){
                //     return this.precioReal() -(this.precioReal() * (this.descuento()/100 ));
                // },this),
                codprodlab : ko.observable(""),
                titulo:ko.observable("Nuevo producto"),
                mensajeModalCliente:ko.observable(),
                GuardarProducto:function(model){
                    var mensaje=this.mensajeModalCliente;
                    this.mensajeModalCliente(null);
                    if(this.Validar()){
                        $.post("InicioAdminController/GuardarProducto", {
                            modo:this.modo,
                            idProducto  : this.idProducto,
                            idLaboratorio : self.idLaboActual(),
                            codigo : this.codigo(),
                            producto : this.producto(),
                            forma : this.forma(),
                            concentracion : this.concentracion(),
                            presentacion1 : this.presentacion1(),
                            presentacion2 : this.presentacion2(),
                            precioReal : this.precioReal(),
                            descuento : this.descuento(),
                            precioFinal : this.precioFinal(),
                            codprodlab : this.codprodlab()
                        }, function(id){
                            if(id>0){
                                self.productosTotales([]);
                                self.CargarPaginaProductos( self.idLaboActual(), self.pagActual,self.tamPag());
                                //self.IniciarPaginacion(self.idLaboActual, self.pagActual,self.tamPag());
                                $("#modal_producto").modal("hide");
                            }
            
                        },"json");
                    }else{
                        this.mensajeModalCliente("Debe llenar los campos obligatorios (*)");
                    }
                    
                },
                EliminarProducto:function(model){
                    $.post("InicioAdminController/EliminarProducto", {
                        idProducto:this.idProducto,
                    }, function(id){
                        if(id>0){
                            self.productosTotales([]);
                            self.CargarPaginaProductos( self.idLaboActual(), self.pagActual,self.tamPag());
                            //self.IniciarPaginacion(self.pagActual,self.tamPag);
                            $("#modal_eliminar_producto").modal("hide");
                        }
        
                    },"json");
                },
                Validar:function(){
                    return this.codigo()!="" && this.producto()!="" && this.precioReal()!="" && this.descuento()!="";
                }
            }
        }

        self.CargarDescuentoEfectivo=function(){
            $.post("InicioAdminController/GetDescuentoEfectivo",  function(data){
                self.descuentoEfectivo(data.valor);

            },"json");
        }
        self.CargarDescuentoDesdeMonto=function(){
            $.post("InicioAdminController/GetDescuentoDesdeMonto",  function(data){
                self.descuentoMonto(data.valor);
                self.descuentoMontoBs(data.desdeMonto);

            },"json");
        }
        self.CargarSeccionProductos=function(forzar=false){
            self.CargarListaLaboratorios();
            self.CargarDescuentoEfectivo();
            self.CargarDescuentoDesdeMonto();
            // if(forzar || self.productosTotales().length==0 ){
            //     self.CargarPaginaProductos(self.idLaboActual(), 1,self.tamPag());
            // }
        }
        self.ModalNuevoProducto = function(){
            self.ProductoActual(new ProductoClass());
            $("#modal_producto").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
        self.ModalEditarProducto=function(model){
            //var producto= new ProductoClass();
            var producto= self.ProductoActual();
            producto.modo="editar";
            producto.idProducto(model.idProducto);
            producto.idLaboratorio(model.idLaboratorio);
            producto.codigo(model.codigo);
            producto.producto(model.producto);
            producto.forma(model.forma);
            producto.concentracion(model.concentracion);
            producto.presentacion1(model.presentacion1);
            producto.presentacion2(model.presentacion2);
            producto.precioReal(model.precioReal);
            producto.descuento(model.descuento);
            producto.precioFinal(model.precioFinal);
            producto.codprodlab(model.codprodlab);
            producto.titulo("Editar producto");
            //self.ProductoActual(producto);

            $("#modal_producto").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
        self.ModalEliminarProducto=function(model){
            var producto= self.ProductoActual();
            producto.idProducto(model.idProducto);
            producto.producto(model.producto);
            $("#modal_eliminar_producto").modal("show");
        }
        self.ProductoActual=ko.observable(ProductoClass());
        
        self.GuardarDescuentoGeneral=function(){
            $.post("InicioAdminController/GuardarDescuentoGeneral", {descuentoEfectivo: self.descuentoEfectivo, descuentoMonto:self.descuentoMonto, descuentoMontoBs:self.descuentoMontoBs}, function(data){
                console.log(data);
                if(data){
                    $("#modalDescGeneral").modal("hide");
                }
            },"json");
        }
    }

    LaboratoriosModel=function(){
        var self=this;
        self.lista_laboratorios=ko.observable();
        self.idLaboActual=ko.observable();
        self.selectLaboratorio=ko.observableArray();

        // self.CargarListaLaboratorios=function(){
        //     $.post("InicioAdminController/GetLaboratorios", function(allLabos){
        //         self.lista_laboratorios(allLabos);

        //     },"json");
        // }
        self.CargarListaLaboratorios=function(){
            $.post("InicioAdminController/GetListaLaboratorios", function(allLabos){
                //self.lista_laboratorios(allLabos);
                var arrayLabo=Array();
                $.each(allLabos,function(i,item){
                    var objLabo=new self.Laboratorio();
                    objLabo.nombre=item.nombre;
                    objLabo.idLaboratorio=item.idLaboratorio;
                    objLabo.LaPaz(item.LaPaz == 1);
                    objLabo.ElAlto(item.ElAlto == 1);
                    objLabo.Cochabamba(item.Cochabamba == 1);
                    objLabo.Quillacollo(item.Quillacollo == 1);
                    objLabo.SantaCruz(item.SantaCruz == 1);
                    objLabo.Montero(item.Montero == 1);
                    objLabo.Oruro(item.Oruro == 1);
                    objLabo.Potosi(item.Potosi == 1);
                    objLabo.Chuquisaca(item.Chuquisaca == 1);
                    objLabo.Tarija(item.Tarija == 1);
                    objLabo.Beni(item.Beni == 1);
                    objLabo.Pando(item.Pando == 1);
                    objLabo._editar=false;
                    arrayLabo.push(objLabo);
                });
                self.lista_laboratorios(arrayLabo);
            },"json");
        }

        self.CargarSeccionLaboratorios=function(forzar=false){
            if(forzar || self.lista_laboratorios()==null)
                self.CargarListaLaboratorios();n
        }
        self.Laboratorio=function(){
            var self=this;
            self.nombre;
            self.idLaboratorio;
            self.LaPaz=ko.observable();
            self.ElAlto=ko.observable();
            self.Cochabamba=ko.observable();    
            self.Quillacollo=ko.observable();        
            self.SantaCruz=ko.observable();
            self.Montero=ko.observable();
            self.Oruro=ko.observable();
            self.Potosi=ko.observable();
            self.Chuquisaca=ko.observable();
            self.Tarija=ko.observable();
            self.Beni=ko.observable();
            self.Pando=ko.observable();
            self._editar=false;

            self.LaPaz.subscribe(function(valor){self._editar=true;});
            self.ElAlto.subscribe(function(valor){self._editar=true;});
            self.Cochabamba.subscribe(function(valor){self._editar=true;});
            self.Quillacollo.subscribe(function(valor){self._editar=true;});
            self.SantaCruz.subscribe(function(valor){self._editar=true;});
            self.Montero.subscribe(function(valor){self._editar=true;});
            self.Oruro.subscribe(function(valor){self._editar=true;});
            self.Potosi.subscribe(function(valor){self._editar=true;});
            self.Chuquisaca.subscribe(function(valor){self._editar=true;});
            self.Tarija.subscribe(function(valor){self._editar=true;});
            self.Beni.subscribe(function(valor){self._editar=true;});
            self.Pando.subscribe(function(valor){self._editar=true;});
        }
        self.GuardarRegiones=function(){
            var jsonRegiones=Array();
            $.each(self.lista_laboratorios(),function(i,item){
                if(item._editar){
                    jsonRegiones.push([
                        // id:item.idLaboratorio,
                        // r:[
                        //     item.LaPaz()?1:0,
                        //     item.Cochabamba()?1:0,
                        //     item.SantaCruz()?1:0,
                        //     item.Oruro()?1:0,
                        //     item.Potosi()?1:0,
                        //     item.Chuquisaca()?1:0,
                        //     item.Tarija()?1:0,
                        //     item.Beni()?1:0,
                        //     item.Pando()?1:0,
                        // ]
                            item.idLaboratorio,                        
                            item.LaPaz()?1:0,
                            item.ElAlto()?1:0,
                            item.Cochabamba()?1:0,
                            item.Quillacollo()?1:0,
                            item.SantaCruz()?1:0,
                            item.Montero()?1:0,
                            item.Oruro()?1:0,
                            item.Potosi()?1:0,
                            item.Chuquisaca()?1:0,
                            item.Tarija()?1:0,
                            item.Beni()?1:0,
                            item.Pando()?1:0,
                        
                    ]);
                }
                
            });
            $.post("InicioAdminController/GuardarRegiones", {regiones: jsonRegiones}, function(data){
                console.log(data);
            },"json");
            //console.log(jsonRegiones);
        }
    }
    
    ko.applyBindings(new SolicitudesModel(), contextSolicitudes); 
    ko.applyBindings(new ClientesModel(), contextClientes); 
    ko.applyBindings(new CuentasModel(), contextCuenta); 
    ko.applyBindings(new ProductosModel(), contextProductos); 
    ko.applyBindings(new LaboratoriosModel(), contextLaboratorios); 

    Sammy(function() {
        
        this.get('solicitudes', function() {
            cambioSeccion("solicitudesSeccion");
            ko.contextFor(contextSolicitudes).$root.CargarSeccionSolicitudes();
        });        
        this.get('clientes', function() {
            cambioSeccion("clientesSeccion");
            ko.contextFor(contextClientes).$root.CargarSeccionClientes();
        });
        this.get('cuenta', function() {
            cambioSeccion("cuentaSeccion");
            ko.contextFor(contextCuenta).$root.CargarSeccionCuenta();
        });
        this.get('productos', function() {
            cambioSeccion("productosSeccion");
            ko.contextFor(contextProductos).$root.CargarSeccionProductos();
        });
        this.get('laboratorios', function() {
            cambioSeccion("laboratoriosSeccion");
            ko.contextFor(contextLaboratorios).$root.CargarSeccionLaboratorios();
        });

    }).run("#solicitudes");
    //ko.applyBindings(new AdminViewModel()); 
})