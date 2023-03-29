$(function() {

    function AppViewModel() {
        var self=this;
        self.seccion=ko.observable('laboratorios');
        self.laboratorios=ko.observableArray();
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
        self.hayPedidos=ko.computed(function(){
           return self.seleccionados().length>0;
        });
        self.select;
        self.CargarLista=function(laboratorio){
            var idLabo=laboratorio.idLaboratorio;
            
            self.CargarItems(idLabo, 1,10);
            self.ActualLabo(Number(idLabo));
            self.ActualLaboNombre(laboratorio.nombre);
            if(laboratorio.logo!=null){
                self.ActualLaboLogo('assets/img/logos/'+laboratorio.logo);
            }else{
                self.ActualLaboLogo(null);
            }
            
        }
        self.VolverALaboratorios=function(){
            
        };
        self.SeccionLaboratorios =  function(){
            self.seccion('laboratorios');
            self.ActualLabo(0);
            self.seleccionados([]);
            self.productos([]);
            self.productosTotales([]);
            self.LimpiarBusqueda();
            self.select[0].selectize.clear();
        };
        self.SeccionHistorial = function(){
            self.seccion('historial');
            self.IniciarPaginacionHistorial(1,10);
        };
        self.datosCuenta=ko.observable();
        self.SeccionCuenta=function(){
            self.seccion('cuenta');
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
        self.CargarLaboratorios=function(){
            $.post("InicioController/GetLaboratorios", {clave:"Miguel"}, function(allLabos){
                $('#select-laboratorios').css("visibility","block")
                self.select = $('#select-laboratorios').selectize({            
                    valueField: 'idLaboratorio',
                    labelField: 'nombre',
                    searchField: 'nombre',
                    options: allLabos,
                    onChange: function(e){
                        if(e==""){
                            return;
                        }
                        var laboratorio;
                        $.each(allLabos, function(i,item){
                           if(item.idLaboratorio==e){
                            laboratorio= item;
                            
                           }
                        })
                        self.CargarLista(laboratorio);
                    }
                });

            },"json");
        };
        
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
        
        self.CargarLaboratorios();
        self.CargarCuenta();

        self.GuardarPedidos=function(){
            self.codigoRespuesta(-1);
            $.post("InicioController/GuardarPedidos", {clave:"Miguel", solicitudes : self.seleccionados(), tipoPago:parseInt($('input[name=tipo_pago]:checked').val()) }, function(res){
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

