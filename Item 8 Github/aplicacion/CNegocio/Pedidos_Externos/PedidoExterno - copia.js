$(function() {
     $('#listadoPedidoExternodetalle').empty();
     //$('#pop1').empty();
    var moveLeft = 0;
    var moveDown = 0;

    $('a.popper').hover(function(e) {
         // e.preventDefault();
        $('#listadoPedidoExternodetalle').empty();
        var target = '#' + ($(this).attr('data-popbox'));
        var xa = ($(this).attr('href'));
        $(target).show();
        moveLeft = $(this).outerWidth();
        moveDown = ($(target).outerHeight() / 2);
       //Cuando abre



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






    }, function() {
        var target = '#' + ($(this).attr('data-popbox'));
        $(target).hide();
        $('#listadoPedidoExternodetalle').empty();
       //cuando sales de la ventana
    });

    $('a.popper').mousemove(function(e) {
     // e.preventDefault();
        $('#listadoPedidoExternodetalle').empty();
      //cuando abre la ventana
        var target = '#' + ($(this).attr('data-popbox'));
       // $('#listadoPedidoExternodetalle').empty();


        leftD = e.pageX + parseInt(moveLeft);
        maxRight = leftD + $(target).outerWidth();
        windowLeft = $(window).width() - 40;
        windowRight = 0;
        maxLeft = e.pageX - (parseInt(moveLeft) + $(target).outerWidth() + 20);

        if(maxRight > windowLeft && maxLeft > windowRight)
        {
            leftD = maxLeft;
        }

        topD = e.pageY - parseInt(moveDown);
        maxBottom = parseInt(e.pageY + parseInt(moveDown) + 20);
        windowBottom = parseInt(parseInt($(document).scrollTop()) + parseInt($(window).height()));
        maxTop = topD;
        windowTop = parseInt($(document).scrollTop());
        if(maxBottom > windowBottom)
        {
            topD = windowBottom - $(target).outerHeight() - 20;
        } else if(maxTop < windowTop){
            topD = windowTop + 20;
        }

        $(target).css('top', topD).css('left', leftD);

          $('#listadoPedidoExternodetalle').empty();

    });

});