$(function() {

    function AppViewModel() {
        var self=this;
        self.seccion=ko.observable('laboratorios');
        self.laboratorios=ko.observableArray(typeof mislaboratorios=="undefined"?null: mislaboratorios);
        self.productosTotales=ko.observableArray();
        self.productos=ko.observable();
        self.seleccionados=ko.observableArray();
        self.productosHistorial=ko.observable();
        self.ActualLabo=ko.observable(0);
        self.ActualLaboNombre=ko.observable();
        self.ActualLaboLogo=ko.observable();
        self.criterioProducto=ko.observable('');
        self.codigoRespuesta=ko.observable(0);
        self.detalleSolicitud=ko.observable();
        self.detalleEgreso=ko.observable();
        self.clientesLista=ko.observable();

        self.listoTablaClientes=ko.observable(false);
        self.hayPedidos=ko.computed(function(){
           return self.seleccionados().length>0;
        });
        self.select;
        self.CargarLista=function(idLaboratorio){
            
            location.hash = "/listaproductos/"+ idLaboratorio;
        }
        self.SeccionLaboratorios =  function(){
            location.hash = "/laboratorios"
        };
        self.SeccionHistorial = function(){
            location.hash = "/historial"
            
        };
        self.datosCuenta=ko.observable();
        self.SeccionCuenta=function(){
            location.hash = "/cuenta"            
        }
        self.SeccionClientes=function(){
            location.hash = "/clientes"            
        }
        self.CargarCuenta=function(){
            $.post("InicioController/GetCuenta", {clave:"Miguel"}, function(allData){
                allData.password="            ";
                self.datosCuenta(allData);
            },"json"); 
        }
        self.avisoCuenta=ko.observable(0);
        self.GuardarCuenta=function(){
            self.avisoCuenta(0);
            $.post("InicioController/GuardarCuenta", {clave:"Miguel", datos:self.datosCuenta()}, function(res){
                self.avisoCuenta(res);
            },"json"); 
        }
        self.BuscarProducto=function(){
            $.each(self.productosTotales(),function(index,item){
                item.visible(true);                                
            });        
            var buscar = self.criterioProducto().trim();
            if(buscar!=""){
                $.each(self.productosTotales(),function(index,item){
                    if(item.producto.toLowerCase().indexOf(buscar.toLowerCase())<0){
                        item.visible(false);
                    }
                });
                self.IniciarPaginacion(self.ActualLabo,1,10);
            }else{                
                self.IniciarPaginacion(self.ActualLabo,1,10);
            }
            
        }
        self.KeyBuscarProducto=function(data, event){
            if (event.keyCode==13) {
                self.BuscarProducto();
            }
            self.BuscarProducto();
            return true;
        }
        self.LimpiarBusqueda=function(){
            if(self.criterioProducto().trim()!=""){
                self.criterioProducto('');
                self.BuscarProducto();
            }
            
        }
        self.AgregarProducto = function(producto){
            self.seleccionados.push(producto);
            producto.estaAgregado(true);
            
        }
        self.QuitarProducto = function(producto){
            self.seleccionados.remove(producto);
            producto.estaAgregado(false);
        }
        //DESCUENTOS
        self.sumaDetalle=ko.computed(function(){
            var suma = 0;
            $.each(self.seleccionados(),function (i, item){
                suma += parseFloat(item.total());
            }); 
            return suma.toFixed(2);
        });
        self.descuentoEfectivo=ko.observable(descuentoEfectivo);
        self.descuentoMonto=ko.observable(descuentoMonto);
        self.descuentoMontoBs=ko.observable(descuentoMontoBs);
        self.metodoPago=ko.observable('efectivo');
        self.c_descuentoEfectivo=ko.computed(function(){
            if(self.metodoPago()=="efectivo")
              return parseFloat(((self.descuentoEfectivo()/100) * self.sumaDetalle())).toFixed(2);
            else
              return 0
        });
        self.c_descuentoMontoBs=ko.computed(function(){
          if( self.sumaDetalle() >= self.descuentoMontoBs()  )
            return parseFloat(((self.descuentoMonto()/100) * self.sumaDetalle())).toFixed(2);
          else
            return 0
          });
        
        self.sumaTotal=ko.computed(function(){
            var suma = 0;
            $.each(self.seleccionados(),function (i, item){
                suma += parseFloat(item.total());
            }); 
            suma -= self.c_descuentoEfectivo();
            suma -= self.c_descuentoMontoBs();
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
        // self.CargarLaboratorios=function(){
        //     $.post("InicioController/GetLaboratorios", {clave:"Miguel"}, function(allLabos){
        //         self.laboratorios(allLabos);
        //         $('#select-laboratorios').css("visibility","block");
        //         self.select = $('#select-laboratorios').selectize({            
        //             valueField: 'idLaboratorio',
        //             labelField: 'nombre',
        //             searchField: 'nombre',
        //             options: allLabos,
        //             onChange: function(e){
        //                 if(e==""){
        //                     return;
        //                 }
                        
        //                 self.CargarLista(e);
        //             }
        //         });

        //     },"json");
        // };
        
        self.CargarItems=function(_idLabo, _pag, _tam){
            if(self.productosTotales().length>0){
                self.IniciarPaginacion(_idLabo, _pag, _tam);
                
            }else{
                $.post("InicioController/GetLista", {clave:"Miguel", idLabo: _idLabo}, function(allData){
                    var res=allData.res;
                    var productosMapeados = $.map(res,function(item){
                        return new Producto(item)
                    });
                    self.productosTotales(productosMapeados);
                    
                    self.IniciarPaginacion(_idLabo, _pag, _tam);
    
                },"json"); 
            }
        }
        self.IniciarPaginacion=function(_idLabo, _pag, _tam){
            var offset = (_pag-1)*_tam;
            var nItems = 0;
            var itemsValidos=[];
            $.each(self.productosTotales(),function (i, item){
                if(item.visible()){
                    itemsValidos.push(item);
                }
            }); 
            self.productos(itemsValidos.slice(offset,offset+_tam));
            self.Paginacion(_pag,  itemsValidos.length, _tam, _idLabo);
        }
        self.Paginacion=function(pagActual,paginas,tamPag, idLabo){
            $("#btnPaginacion").pagination({
                items: paginas,
                itemsOnPage: tamPag,
                currentPage:pagActual,
                cssStyle: 'light-theme',
                onPageClick:function(pageNumber, event){
                    ko.contextFor(document.body).$root.CargarItems(idLabo, pageNumber, tamPag);
                },
                prevText:"Anterior",
                nextText:"Siguiente",

            });
            
        } 
        self.IniciarPaginacionHistorial=function(_pag, _tam){
            $.post("InicioController/GetHistorial", {clave:"Miguel",npagina:_pag, tamPag:_tam}, function(allData){
                self.productosHistorial(allData.res);
                //OJO deberia cargar solo una vez
                self.PaginacionHistorial(allData.npagina,  allData.paginas, allData.tamPag);

            },"json");
        }
        self.PaginacionHistorial=function(pagActual,paginas,tamPag){
            $("#btnPaginacionHistorial").pagination({
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
        
        if($("#select-laboratorios").length >0 ){
            self.select = $('#select-laboratorios').selectize({            
                valueField: 'idLaboratorio',
                labelField: 'nombre',
                searchField: 'nombre',
                options: mislaboratorios,
                onChange: function(e){
                    if(e==""){
                        return;
                    }
                    
                    self.CargarLista(e);
                }
            });
        }

        self.CargarCuenta();

        self.CargarTablaClientes=function(){
            $.post("InicioAdminController/GetClientes", {clave:"Miguel" }, function(res){
                //console.log(res.Respuesta);
                self.clientesLista(res);

            },"json");
        }

        self.GuardarPedidos=function(){
            self.codigoRespuesta(-1);
            $.post("InicioController/GuardarPedidos", {clave:"Miguel", solicitudes : self.seleccionados(), tipoPago:parseInt($('input[name=tipo_pago]:checked').val()), metodoPago: self.metodoPago() }, function(res){
                console.log(res.Respuesta);
                self.codigoRespuesta(res.Codigo);

            },"json");
        };
        self.VerDetalleSolicitud=function(solicitud){            
            $.post("InicioController/GetPedidos", {clave:"Miguel",idSolicitud:solicitud.idSolicitud, idCliente: solicitud.idCliente}, function(res){
                $("#modal_detalle_solicitud").modal("show");
                var productos=$.map(res,function(item){
                    return new Producto(item);
                });
                self.seleccionados(productos);
                solicitud.detalle=self.seleccionados();
                
                self.detalleSolicitud(solicitud);
                

            },"json");
        };
        if($("#adminLista").length >0 ){
            self.IniciarPaginacionHistorial(1, 10);
            self.seccion('historial');
        };

        $('#modal_solicitud').on('hidden.bs.modal', function (e) {
            self.codigoRespuesta(0);
          });
        
          

        //RUTAS
        Sammy(function() {
            // this.get('principal', function() {
            //     self.seccion('inicio');
            // });
            this.get('laboratorios', function() {
                self.seccion('laboratorios');
                self.ActualLabo(0);
                self.seleccionados([]);
                self.productos([]);
                self.productosTotales([]);
                self.LimpiarBusqueda();
                self.select[0].selectize.clear();
            });
            this.get('listaproductos/:idLaboratorio', function() {
                
                var id=this.params['idLaboratorio'];
                var laboratorio;
                $.each(self.laboratorios(), function(i,item){
                    if(item.idLaboratorio==id){
                    laboratorio= item;                    
                    }
                })
                var idLabo=laboratorio.idLaboratorio;            
                self.CargarItems(idLabo, 1,10);
                self.ActualLabo(Number(idLabo));
                self.ActualLaboNombre(laboratorio.nombre);
                if(laboratorio.logo!=null){
                    self.ActualLaboLogo('assets/img/logos/'+laboratorio.logo);
                }else{
                    self.ActualLaboLogo(null);
                }
                
                self.seccion('listaproductos');
            });
            this.get('historial', function() {
                self.seccion('historial');
                self.IniciarPaginacionHistorial(1,10);
            });
            this.get('cuenta', function() {
                self.seccion('cuenta');
            });
            // this.get('', function() {
            //      this.app.runRoute('get', '#/home')
            // });
            //perteneciente a ADMINISTRADOR
            this.get('clientes', function() {
                self.seccion('clientes');
                if(!self.listoTablaClientes()){
                    self.CargarTablaClientes();
                }
            });

        }).run();
        
    }
    
    function Producto(data){
        var self=this;
        self.idProducto=data.idProducto;
        self.codigo=data.codigo;
        self.producto=data.producto;
        self.precioReal=data.precioReal;
        self.descuento=data.descuento+"%";
        self.precioFinal=data.precioFinal;
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
    ko.applyBindings(new AppViewModel()); 
    
});

