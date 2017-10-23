//inicio de la funcion jquery lista para escuchar cualquier evento
//TODO ESTO ES ANTES DE PRESIONAR EL BOTON CONTINUAR
$(function(){

//$('#wraper2').hide();  



//$('#nprod').autocomplete({source:'../CDatos/Movimientos/buscardetmov.php', minLength:2});


 


  $('#nprod').autocomplete({
    source:'../CDatos/Kardex/buscardetmov.php', 
    minLength:2,
    select:function(evt, ui)
    {
      // when a zipcode is selected, populate related fields in this form
     
      this.form.nserie.value = ui.item.nserie;
      this.form.idp.value = ui.item.idp;
      

    }
  });
//});

$('#nserie').autocomplete({
    source:'../CDatos/Kardex/buscardetmovserie.php', 
    minLength:2,
    select:function(evt, ui)
    {
      // when a zipcode is selected, populate related fields in this form
     
      this.form.nprod.value = ui.item.nprod;
      this.form.idp.value = ui.item.idp;
    

    }
  });


$('#mensajeborrar').hide();

    $( "#fecha" ).datepicker({
      currentText: "Now",
      dateFormat: "yy-mm-dd",
      showOn: "button",
      appendText: "(yyyy-mm-dd)",
      buttonImage: "../CPresentacion/images/calen.jpg",
      buttonImageOnly: true,
        firstDay: 1,
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames:
            ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort:
            ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
                "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
    });

     $( "#fechafin" ).datepicker({
      currentText: "Now",
      dateFormat: "yy-mm-dd",
      showOn: "button",
      appendText: "(yyyy-mm-dd)",
      buttonImage: "../CPresentacion/images/calen.jpg",
      buttonImageOnly: true,
         firstDay: 1,
         dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
         dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
         monthNames:
             ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                 "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
         monthNamesShort:
             ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
                 "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
    });
 
 


$("#fecha").datepicker().datepicker("setDate", new Date());
$("#fechafin").datepicker().datepicker("setDate", new Date());


//confirmacion

$('#buscark').on('click',function(){
var accion = 'KardexProducto'
var id_prod = $('#idp').val();
var fechainicio = $('#fecha').val();
var fechafin = $('#fechafin').val();





                                                              $.ajax({
                                                                                          type: "POST",
                                                                                          dataType: "json",
                                                                                          url: "../CDatos/Kardex/phpAjaxKardex.inc.php",
                                                                                          data: { accion: accion, id_prod: id_prod, fechainicio: fechainicio, fechafin: fechafin },                  
                                                                                          success: function(Data){

                                                                                                if(Data.respuesta == false){
                                                                                                      alert(Data.mensaje);
                                                                                                   }
                                                                                                else{

                                                                                   
                                                                                                          if($('#sinDatos').length){
                                                                                                            $('#sinDatos').remove();
                                                                                                          }

                                                                                                        $('#listaKarexOK').empty();

                                                                                                        $('#listaKarexOK').append(Data.contenido);
                                                                                                        $('#listaKarexOKpdf').append(Data.contenido);

                                                                                                 }  
                                                                                                

                                                                                          },
                                                                                          error: function (Data) {
                                                                                              alert('x: ' + Data);
                                                                                          }
                                                                            }); 





$.ajax({
                                                                                          type: "POST",
                                                                                          //dataType: "json",
                                                                                          url: "../CDatos/Kardex/phpAjaxKardexpdf.php",
                                                                                          //url: "../CDatos/class/class.php",
                                                                                          //data: { id_prod: id_prod, fechainicio: fechainicio, fechafin: fechafin },                  
                                                                                          data:'id_prod='+id_prod+'&fechainicio='+fechainicio+'&fechafin='+fechafin,
                                                                                          success: function(Data){

                                                                                                if(Data.respuesta == false){
                                                                                                      //alert(Data.mensaje);
                                                                                                   }
                                                                                                else{

                                                                                                          

                                                                                                 }  
                                                                                                

                                                                                          },
                                                                                          error: function (Data) {
                                                                                              alert('x: ' + Data);
                                                                                          }
                                                                                       }); 





                                                              


    
  });



$('#exp').on('click',function(){

var id_prod = $('#idp').val();
var fechainicio = $('#fecha').val();
var fechafin = $('#fechafin').val();

var a = 28;
$('#x').attr('href','../CDatos/Kardex/phpAjaxKardexpdf.php?valor='+parseInt(id_prod)+'&valor2='+fechainicio+'&valor3='+fechafin);

});


  
//Grabar Cabecera

    




          
});