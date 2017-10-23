$(function() {
    // $('#listadoPedidoExternodetalle').empty();
	 $('#myModal').on('hidden.bs.modal', function (e) {
  // do something...
   $('#listadoPedidoExternodetalle').empty();
     });

	 $('body').on("click", "button[name=botoncito]", function (){
    //alert($(this).attr("value"));
	xa = $(this).attr("value");
	$.ajax({
                type: "POST",
                dataType: "json",
                url: "../CDatos/Pedidos_Externos/phpAjaxPedidosExt.inc.php",
                data: { idPed: xa, accion:'Ver'},
                success: function(data){
                     // $('#listadoPedidoExternodetalle').empty();
                     //$("#pop1").append(data.contenido);
                            $('#listadoPedidoExternodetalle').empty();
                        	$('#listadoPedidoExternodetalle').append(data.contenido);
                    

                },
                error: function (Data) {
                    alert('x: ' + Data);
                }
              });

});
	
});
	 
	 
  