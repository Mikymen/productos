$(function() {

    function AppViewModel() {
        var self=this;
        self.firstName = "Bert";
        self.lista=listaJson; 
        self.productos=ko.observableArray();
        self.seleccionados=ko.observableArray();

        // $.getJSON("InicioController/GetLista", function(allData){
        //     var productosMapeados = $.map(allData,function(item){
        //         return new Producto(item);
        //     });
        //     self.productos(productosMapeados);
        // });
        self.AgregarProducto = function(producto){
            self.seleccionados.push(producto);
            // console.log(producto);
            producto.estaAgregado(true);
            
        }
        self.QuitarProducto = function(producto){
            self.seleccionados.remove(producto);
            producto.estaAgregado(false);
        }
        self.CargarItems=function(_pag,_tam){
            $.post("InicioController/GetLista", {clave:"Miguel"}, function(allData){
                var res=allData.res;
                var productosMapeados = $.map(res,function(item){
                    return new Producto(item);
                });
                self.productos(productosMapeados);

            },"json");
            
        }
        self.botonesEnlaces=ko.observableArray();        

        self.CargarItems();        
    }
    
    function Producto(data){
        self=this;
        this.id=data.id;
        this.codigo=data.codigo;
        this.descripcion=data.descripcion;
        this.precio=data.precio;
        this.marca=data.marca;
        this.cantidad=ko.observable(1);
        this.estaAgregado=ko.observable(false);
        this.desc=ko.computed(function(){
            return self.cantidad() +" agregados";
        },self);
    }
    function BotonPagina(texto, enlace){
        this.texto=texto;
        this.enlace=enlace;
    }
    // Activates knockout.js
    //ko.options.deferUpdates=true;
    ko.applyBindings(new AppViewModel());

    function pagination(c, m) {
        var current = c,
            last = m,
            delta = 2,
            left = current - delta,
            right = current + delta + 1,
            range = [],
            rangeWithDots = [],
            l;
    
        for (let i = 1; i <= last; i++) {
            if (i == 1 || i == last || i >= left && i < right) {
                range.push(i);
            }
        }
    
        for (let i of range) {
            if (l) {
                if (i - l === 2) {
                    rangeWithDots.push(l + 1);
                } else if (i - l !== 1) {
                    rangeWithDots.push('...');
                }
            }
            rangeWithDots.push(i);
            l = i;
        }
    
        return rangeWithDots;
    }

    // for (let i = 1, l = 20; i <= l; i=i+4)
    // console.log(pagination(i, l));
});


// var _throttleTimer = null;
// var _throttleDelay = 100;
// var $window = $(window);
// var $document = $(document);

// $document.ready(function () {

//     $window
//         .off('scroll', ScrollHandler)
//         .on('scroll', ScrollHandler);

// });

// function ScrollHandler(e) {
//     clearTimeout(_throttleTimer);
//     _throttleTimer = setTimeout(function () {
//         console.log('scroll');

//         if ($window.scrollTop() + $window.height() > $document.height() - 100) {
//             alert("near bottom!");
//         }

//     }, _throttleDelay);
// }