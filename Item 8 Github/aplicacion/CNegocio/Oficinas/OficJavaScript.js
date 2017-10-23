//inicio de la funcion jquery lista para escuchar cualquier evento
//TODO ESTO ES ANTES DE PRESIONAR EL BOTON CONTINUAR
$(function(){
               
                      
     
		// creación de ventana con formulario con jquery ui
		$('#agregarOficina').dialog({
			autoOpen: false,
			modal:true,
			width:600,
			height:'auto',
			resizable: false,
			close:function(){
			$('#formOficina fieldset > span').removeClass('error').empty();
			$('#formOficina input[type="text"]').val('');
		  $('#formOficina select > option').removeAttr('selected');
      $('#id_Oficina').val('0');

			}
                        
		});
                
                
                  //seleccionar el primer elemento del combo
              //$('#p_familia option[value=1]').attr('selected', 'selected');   
          		// funcionalidad del botón que abre el formulario
          		$('#of_codigoa option[value = "1"]').attr('selected', 'selected');

          		$('#goNuevoOficina').on('click',function(){
          			// Asignamos valor a la variable acción
          		$('#accion').val('addOfi');
              $('#mensajeborrar p').hide(); 
              $("#formOficina input:text").attr("size", "50");
			// Abrimos el Formulario
        			$('#agregarOficina').dialog({
        				title:'Agregar Oficina',
        				autoOpen:true,
                                        close:function(){
                                            //Borrar toda la data para que no se vea al abrise otra vez la ventana
                                           $('#formOficina fieldset > span').removeClass('error').empty();
                                           $('#formOficina input[type="text"]').val('');
                                           $('#formOficina select > option').removeAttr('selected');
                                           //$('#of_codigoa option[value="1"]').attr('selected', 'selected');
                                           $('#id_oficina').val('0');

                                        }
                                        
        			});
		});





		// Validar Formulario
		$('#formOficina').validate({
		    submitHandler: function(){
		        
		        var str = $('#formOficina').serialize();

		         //alert(str);

		        $.ajax({
		            beforeSend: function(){
                                //mostrar ajax loader antes de enviar los CDatos
		                $('#formOficina .ajaxLoader').show();
		            },
		            cache: false,
		            type: "POST",
		            dataType: "json",
		            url:"../CDatos/Oficinas/phpAjaxOfic.inc.php",
		            data:str + "&id=" + Math.random(),
		            success: function(response){

		            	// Validar mensaje de error QUE VIENE DE PHPAJAXUSER.INC.PHP
		            	if(response.respuesta == false){
		            		alert(response.mensaje);
                                        $('#formOficina .ajaxLoader').hide();
                                        $('#agregarOficina').dialog('close');
		            	}
		            	else{


		            		// si es exitosa la operación
		                	$('#agregarOficina').dialog('close');

                                          
		                	// alert(response.contenido);
		                	$('#formOficina .ajaxLoader').hide();
                                        
		                	if($('#sinCDatos').length){
		                		$('#sinCDatos').remove();
		                	}
		                	
		                	// Validad tipo de acción
		                	if($('#accion').val() == 'editOfi'){
                                                //Borra la tabla antigua
		                		$('#listaOficinaOk').empty();
		                	}
                                        
                     if($('#accion').val() == 'delOfi'){
                                                //Borra la tabla antigua
		                		$('#listaOficinaOk').empty();
		                	}

                      $('#listaOficinaOk').empty();
		                	$('#listaOficinaOk').append(response.contenido);

                                      
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



                       // Valor de la acción
			                 $('#accion').val('editOfi');

			// Id Usuario
			                 $('#id_Oficina').val($(this).attr('href'));

			// Llenar el formulario con los CDatos del registro seleccionado
			                  $('#of_codigo').val($(this).parent().parent().children('td:eq(0)').text());
		                    $('#of_nombre').val($(this).parent().parent().children('td:eq(2)').text());
                        $('#of_anexo').val($(this).parent().parent().children('td:eq(3)').text());
                        $('#of_piso').val($(this).parent().parent().children('td:eq(4)').text());
                        $('#of_descripcion').val($(this).parent().parent().children('td:eq(5)').text());
                       

                        
                        //mensaje de formulario borrar
			                  $('#mensajeborrar p').hide();                        
                        // Seleccionar Permisos
			
                         var oficinas = $(this).parent().parent().children('td:eq(1)').text();

                        var valorcombo='';        
                             valorcombo = $('#of_codigoa option').filter(function () { return $(this).html() == oficinas; }).val();
                            $('#of_codigoa').attr("value",valorcombo);
                            $('#of_codigoa option[value='+ valorcombo +']').attr('selected',true);
                          //alert($('#p_familia option:selected').val());
                
                        
                         
                         //OBTENER EL VALOR DE LA TABLA Y SETEARLO AL COMOBOX PARA LA EDICION
                           //familias
                           
                             
                                     
                                     
                         
                                                                             
                          
                                 
                                 
			
                       // Abrimos el Formulario
			$('#agregarOficina').dialog({
				title:'Editar Oficina',
				autoOpen:true,
                                close:function(){
                                   /*
                                   $('#formOficina input[type="text"]').val('');
                                   $('#formOficina select > option').removeAttr('selected');
                                   $('#cl_provincia option[value='+""+']').attr('selected',true);
                                   $('#cl_provincia select > option').removeAttr('selected');
                                                                                              
                                   $('#id_oficina').val('0');
                                   */
                                   $('#formOficina input[type="text"]').val('');
                                   $('#formOficina select > option').removeAttr('selected');

                                   $('#of_codigoa option[value='+""+']').attr('selected',true);

                                   $('#id_oficina').val('0');
                                   
                           
                                }
                            
                         
			});

                            return false
		});
                
             
                //Borrar Registros
                
               
		$('body').on('click','#Borrar a',function (e){
			
                    e.preventDefault();

			// alert($(this).attr('href'));
                        $('#formOficina input[type="text"]').hide();
                        $('#CDatosOficina p').hide();
                       $('#of_codigoa').hide();

                    
                        $('#mensajeborrar p').show(); 
                        
			// Valor de la acción
			$('#accion').val('delOfi');

			// Id Usuario
			$('#id_oficina').val($(this).attr('href'));

			
			
			 $( "#agregarOficina" ).dialog({
                                resizable: false,
                                width:380,
                                title:'Borrar Oficina',
                                autoOpen:true,
                                close:function(){
                                   
                                   $('#formOficina input[type="text"]').val('');
                                   $('#formOficina select > option').removeAttr('selected');
                                   //$('#formOficina option[value='+""+']').attr('selected',true);
                                   $('#id_oficina').val('0');
                                   
                                    $('#formOficina input[type="text"]').show();
                                    $('#CDatosOficina p').show();
                                    $('#of_codigoa').show();


                                    $('#CDatosOficina1').show();
                                   $('#mensajeborrar p').hide(); 
                                }
                               
                                
                            });

                            return false;
                            
                            
		});
             
              $("#of_codigoa option:first").attr('selected','selected');

              $("#of_codigoa").change(function () {
                    $("#of_codigoa option:selected").each(function () {
                    elegido=$(this).val();
                   
                 
                    });
                })
                 
});