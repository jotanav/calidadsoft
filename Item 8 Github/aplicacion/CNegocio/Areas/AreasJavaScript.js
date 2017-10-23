//inicio de la funcion jquery lista para escuchar cualquier evento
//TODO ESTO ES ANTES DE PRESIONAR EL BOTON CONTINUAR
$(function(){

    
		// creación de ventana con formulario con jquery ui
		$('#agregarArea').dialog({
			autoOpen: false,
			modal:true,
			width:305,
			height:'auto',
			resizable: false,
			close:function(){
			$('#formArea fieldset > span').removeClass('error').empty();
			$('#formArea input[type="text"]').val('');
		    	$('#formArea select > option').removeAttr('selected');
                $('#id_area').val('0');

			}

		});
                
                

		// funcionalidad del botón que abre el formulario
		$('#goNuevoAreas').on('click',function(){
			// Asignamos valor a la variable acción
			$('#accion').val('addAreas');
                        $('#mensajeborrar p').hide();
			// Abrimos el Formulario
			$('#agregarArea').dialog({
				title:'Agregar Areas',
				autoOpen:true,
                                close:function(){
                                   $('#formArea fieldset > span').removeClass('error').empty();
                                   $('#formArea input[type="text"]').val('');
                                   $('#formArea select > option').removeAttr('selected');
                                   
                                   $('#id_area').val('0');
                                   
                                }
                                
			});
		});





		// Validar Formulario
		$('#formArea').validate({
		    submitHandler: function(){
		        
		        var str = $('#formArea').serialize();

		         //alert(str);

		        $.ajax({
		            beforeSend: function(){
                                //mostrar ajax loader antes de enviar los CDatos
		                $('#formArea .ajaxLoader').show();
		            },
		            cache: false,
		            type: "POST",
		            dataType: "json",
		            url:"../CDatos/Areas/phpAjaxAreas.inc.php",
		            data:str + "&id=" + Math.random(),
		            success: function(response){

		            	// Validar mensaje de error QUE VIENE DE PHPAJAXUSER.INC.PHP
		            	if(response.respuesta == false){
		            		alert(response.mensaje);
                                        $('#formArea .ajaxLoader').hide();
                                        $('#agregarArea').dialog('close');
		            	}
		            	else{


		            		// si es exitosa la operación
		                	$('#agregarArea').dialog('close');
                                          
		                	// alert(response.contenido);
		                	$('#formArea .ajaxLoader').hide();

		                	if($('#sinCDatos').length){
		                		$('#sinCDatos').remove();
		                	}

		                	// Validad tipo de acción
		                	if($('#accion').val() == 'editAreas'){
                                                //Borra la tabla antigua
		                		$('#listaAreasOK').empty();
		                	}

                             if($('#accion').val() == 'delAreas'){
                                                //Borra la tabla antigua
		                		$('#listaAreasOK').empty();
		                	}

		                	$('#listaAreasOK').append(response.contenido);
                                       //location.reload();
                                        
                                        
						}

		            	

		            },
		            error:function(){
                        
		                alert('ERROR GENERAL DEL SISTEMA, INTENTE MAS TARDE');
                               
		            }
		        });

		        return false;

		    },
		    errorPlacement: function(error, element) {
		        error.appendTo(element.prev("span").append());
		    }
		});



		// Edición de Registros
		$('body').on('click','#Editar a',function (e){
			
                    e.preventDefault();

			// alert($(this).attr('href'));

			// Valor de la acción
			$('#accion').val('editAreas');

			// Id Usuario
			$('#id_area').val($(this).attr('href'));

			// Llenar el formulario con los CDatos del registro seleccionado
			$('#codigo').val($(this).parent().parent().children('td:eq(0)').text());
			$('#nombre').val($(this).parent().parent().children('td:eq(1)').text());
			$('#anexo').val($(this).parent().parent().children('td:eq(2)').text());
			$('#piso').val($(this).parent().parent().children('td:eq(3)').text());
			$('#direccion').val($(this).parent().parent().children('td:eq(4)').text());
			$('#descripcion').val($(this).parent().parent().children('td:eq(5)').text());
			 $('#mensajeborrar p').hide();                        
       			
	                      
                        
          // Abrimos el Formulario
			$('#agregarArea').dialog({
				title:'Editar Areas',
				autoOpen:true,
                                close:function(){
                                   
                                   $('#formArea input[type="text"]').val('');
                                   $('#formArea select > option').removeAttr('selected');
                                   //$('#usr_permisos option[value='+""+']').attr('selected',true);
                                   //$('#usr_status option[value='+""+']').attr('selected',true);
                                   $('#id_area').val('0');//se cambio
                                }
                            
                         
			});

                            return false
		});
                
                
                //Borrar Registros
                
               
		$('body').on('click','#Borrar a',function (e){
			
                    e.preventDefault();

			// alert($(this).attr('href'));
                        $('#formArea input[type="text"]').hide();
                        $('#CDatosArea p').hide();
                        $('#mensajeborrar p').show(); 
                        
			// Valor de la acción
			$('#accion').val('delAreas');

			// Id Usuario
			$('#id_area').val($(this).attr('href'));

			
			
                       // Abrimos el Formulario
			 $( "#agregarArea" ).dialog({
                                resizable: false,
                                title:'Borrar Areas',
                                autoOpen:true,
                                close:function(){
                                   
                                   $('#formArea input[type="text"]').val('');
                                   $('#formArea select > option').removeAttr('selected');
                                   $('#id_area').val('0');
                                   
                                    $('#formArea input[type="text"]').show();
                                    $('#CDatosArea p').show();
                                    $('#mensajeborrar p').hide(); 
                                }
                               
                                
                            });

                            return false;
                            
                            
		});
                
                
           
});