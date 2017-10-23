//inicio de la funcion jquery lista para escuchar cualquier evento
//TODO ESTO ES ANTES DE PRESIONAR EL BOTON CONTINUAR
$(function(){
               
                      
     
		// creación de ventana con formulario con jquery ui
		$('#agregarUsuario').dialog({
			autoOpen: false,
			modal:true,
			width:600,
			height:'auto',
			resizable: false,
			close:function(){
			$('#formUsuario fieldset > span').removeClass('error').empty();
			$('#formUsuario input[type="text"]').val('');
		  $('#formUsuario select > option').removeAttr('selected');
      $('#id_usuario').val('0');

			}
                        
		});
                


          	$('#goNuevoUsuario').on('click',function(){
          			// Asignamos valor a la variable acción
          	$('#accion').val('addUsu');
            $('#mensajeborrar p').hide(); 
            $("#formUsuario input:text").attr("size", "50");
			// Abrimos el Formulario
        			$('#agregarUsuario').dialog({
        				title:'Agregar Usuario',
        				autoOpen:true,
                                        close:function(){
                                           $('#formUsuario fieldset > span').removeClass('error').empty();
                                           $('#formUsuario input[type="text"]').val('');
                                           $('#formUsuario select > option').removeAttr('selected');
                                           $('#id_usuario').val('0');

                                        }
                                        
        			});
		});





		// Validar Formulario
		$('#formUsuario').validate({
		    submitHandler: function(){
		        
		        var str = $('#formUsuario').serialize();

		         //alert(str);

		        $.ajax({
		            beforeSend: function(){
                                //mostrar ajax loader antes de enviar los CDatos
		                $('#formUsuario .ajaxLoader').show();
		            },
		            cache: false,
		            type: "POST",
		            dataType: "json",
		            url:"../CDatos/Usuarios/phpAjaxUsu.inc.php",
		            data:str + "&id=" + Math.random(),
		            success: function(response){

		            	// Validar mensaje de error QUE VIENE DE PHPAJAXUSER.INC.PHP
		            	if(response.respuesta == false){
		            		alert(response.mensaje);
                                        $('#formUsuario .ajaxLoader').hide();
                                        $('#agregarUsuario').dialog('close');
		            	}
		            	else{

                                          
		                	// alert(response.contenido);
		                	$('#formUsuario .ajaxLoader').hide();
                                        
		                	if($('#sinCDatos').length){
		                		$('#sinCDatos').remove();
		                	}
		                	
		                	// Validad tipo de acción
		                	if($('#accion').val() == 'editUsu'){
                                                //Borra la tabla antigua
		                		$('#listaUsuarioOk').empty();
		                	}
                                        
                     if($('#accion').val() == 'delUsu'){
                                                //Borra la tabla antigua
		                		$('#listaUsuarioOk').empty();
		                	}

                      $('#listaUsuarioOk').empty();
		                	$('#listaUsuarioOk').append(response.contenido);

                                      
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
			 $('#accion').val('editUsu');

			// Id Usuario
			$('#id_usuario').val($(this).attr('href'));

			// Llenar el formulario con los CDatos del registro seleccionado
			$('#usu_codigo').val($(this).parent().parent().children('td:eq(0)').text());
			$('#usu_usuario').val($(this).parent().parent().children('td:eq(3)').text());
			$('#usu_passw').val($(this).parent().parent().children('td:eq(4)').text());
			$('#usu_ape_pa').val($(this).parent().parent().children('td:eq(5)').text());
			$('#usu_ape_ma').val($(this).parent().parent().children('td:eq(6)').text());
			$('#usu_nom').val($(this).parent().parent().children('td:eq(7)').text());
			$('#usu_dni').val($(this).parent().parent().children('td:eq(8)').text());
			$('#usu_direc').val($(this).parent().parent().children('td:eq(9)').text());
			$('#usu_cel').val($(this).parent().parent().children('td:eq(10)').text());
			$('#usu_te').val($(this).parent().parent().children('td:eq(11)').text());
                        
            //mensaje de formulario borrar
			 $('#mensajeborrar p').hide();   

                        // Seleccionar Permisos
		   				 var tusuario = $(this).parent().parent().children('td:eq(2)').text();
                         
                           var valorcombo='';        
                             valorcombo = $('#usu_tipousuario option').filter(function () { return $(this).html() == tusuario; }).val();
                         
                            $('#usu_tipousuario option[value='+ valorcombo +']').attr('selected',true);
                       
                     var toficina = $(this).parent().parent().children('td:eq(1)').text();
                         
                           var valorcombof='';        
                             valorcombof = $('#usu_tipooficina option').filter(function () { return $(this).html() == toficina; }).val();
                         
                            $('#usu_tipooficina option[value='+ valorcombof +']').attr('selected',true); 
       
                         
                                                                             
                          
                                 
                                 
			
                       // Abrimos el Formulario
			$('#agregarUsuario').dialog({
				title:'Editar Usuario',
				autoOpen:true,
                                close:function(){
                                   /*
                      
                                   */
                                   $('#formUsuario input[type="text"]').val('');
                                   $('#formUsuario select > option').removeAttr('selected');

                                   $('#usu_tipousuario option[value='+""+']').attr('selected',true);

                                   $('#id_usuario').val('0');
                                   
                           
                                }
                            
                         
			});

                            return false
		});
                
             
                //Borrar Registros
                
               
		$('body').on('click','#Borrar a',function (e){
			
                    e.preventDefault();

			// alert($(this).attr('href'));
                        $('#formUsuario input[type="text"]').hide();
                        $('#CDatosUsuario p').hide();
                        $('#CDatosUsuario').hide();
                        $('#usu_tipooficina').hide();

                        $('#CDatosUsuario1').show();
                        $('#btnAgregar').show();
                   
                        $('#mensajeborrar p').show(); 
                        
			// Valor de la acción
			$('#accion').val('delUsu');

			// Id Usuario
			$('#id_usuario').val($(this).attr('href'));

			
			
			 $( "#agregarUsuario" ).dialog({
                                resizable: false,
                                width:380,
                                title:'Borrar Usuario',
                                autoOpen:true,
                                close:function(){
                                   
                                   $('#formUsuario input[type="text"]').val('');
                                   $('#formUsuario select > option').removeAttr('selected');
                                   $('#id_usuario').val('0');
                                   
                                    $('#formUsuario1 input[type="text"]').show();
                                    $('#CDatosUsuario p').show();


                                    $('#CDatosUsuario1').show();
                                   $('#mensajeborrar p').hide(); 
                                }
                               
                                
                            });

                            return false;
                            
                            
		});
             
         
                 
});