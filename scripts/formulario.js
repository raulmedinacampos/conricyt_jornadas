// Variable para guardar los recursos seleccionados
var recursosSelect = "";
var textoSede = "";
var textoRecursos = "";

// Inicializar
function inicializar() {
	$("input").val("");
	$("#enviar").val("Enviar");
	$("select").prop('selectedIndex', 0);
	$(".camposOcultos").css("display", "none");
	
	$("#btn_captcha").click(function(e) {
		obtenerImagen();
	});
}
// Inhabilita la opción de copiar y pegar en la confirmación del correo
function evitarCopyPaste() {
	$("#correo_conf").bind("cut copy paste",function(e) {
		e.preventDefault();
	});
}

// Autocompleta el nombre de la institución de adscripción
function autocompletarInstitucion() {
	var instituciones = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		limit: 50,
		prefetch: {
			ttl: 0,
			url: '/scripts/instituciones.json',
			filter: function(list) {
				return $.map(list, function(institucion) { return { name: institucion }; });
			}
		}
	});
	
	instituciones.initialize();
	
	$('#institucion').typeahead(
		{
			hint: true,
			highlight: true,
			minLength: 1
		},
		{
			name: 'instituciones',
			displayKey: 'name',
			source: instituciones.ttAdapter()
		}
	);
}

function validarApellidos() {
	$("#chkApPaterno, #chkApMaterno").click(function() {
		if($(this).is(":checked")) {
			$(this).parents("div").children(".form-control").attr("disabled", "disabled");
			$("#chkApPaterno, #chkApMaterno").not($(this)).attr("disabled", "disabled");
			
			if($(this).parents(".form-group").hasClass("has-error")) {
				$(this).parents(".form-group").removeClass("has-error").addClass("has-success");
			}
		} else {
			$(this).parents("div").children(".form-control").removeAttr("disabled");
			$("#chkApPaterno, #chkApMaterno").not($(this)).removeAttr("disabled");
			
			if($(this).parents(".form-group").hasClass("has-success") && !$(this).parents("div").children(".form-control").val()) {
				$(this).parents(".form-group").removeClass("has-success").addClass("has-error");
			}
		}
	});
}

// Validación de formulario
function validarFormulario() {
	$("#btnEnviar").click(function() {
		if($("#formRegistro").valid()) {
			var controlEnvio = true;
			var confirmacion = '';
			confirmacion += "<ul>";
			confirmacion += "<li>Nombre: " + $("#nombre").val() + "</li>";
			confirmacion += "<li>Apellido paterno: " + $("#ap_paterno").val() + "</li>";
			confirmacion += "<li>Apellido materno: " + $("#ap_materno").val() + "</li>";
			confirmacion += "<li>Sexo: " + $("#sexo option:selected").text() + "</li>";
			if($("#otra_institucion").length) {
				confirmacion += "<li>Institución de procedencia: " + $("#otra_institucion").val() + "</li>";
			} else {
				confirmacion += "<li>Institución de procedencia: " + $("#institucion").val() + "</li>";
			}
			confirmacion += "<li>Entidad federativa: " + $("#entidad option:selected").text() + "</li>";
			if($("#otro_perfil").length) {
				confirmacion += "<li>Perfil: " + $("#otro_perfil").val() + "</li>";
			} else {
				confirmacion += "<li>Perfil: " + $("#perfil option:selected").text() + "</li>";
			}
			confirmacion += "<li>Teléfono: " + $("#telefono").val() + "</li>";
			confirmacion += "<li>Correo: " + $("#correo").val() + "</li>";
			confirmacion += "<li>Sede: " + textoSede + "</li>";
			confirmacion += "<li>Capacitaciones: ";
			confirmacion += "<ul>";
			confirmacion += textoRecursos;
			confirmacion += "</ul>";
			confirmacion += "</li>";
			confirmacion += "</ul>";
			
			$('#mensajesError .btn-primary').css("display", "inline");
			$('#mensajesError .btn-default').html("Regresar");
			$('#mensajesError .modal-title').html("Confirma la información capturada");
			$("#mensajesError .modal-body").html(confirmacion);
			$("#mensajesError").modal();
			
			$("#mensajesError .btn-primary").click(function() {
				$("#mensajesError .modal-body").html('<div class="text-center"><img src="images/loading.gif" /></div>');
				if(controlEnvio) {
					$.post(
						'registro/registrarDatos',
						$("#formRegistro").serialize(),
						function(data) {
							controlEnvio = false;
							$("#mensajesError").modal('hide');
							var texto = "";
							if(data == "duplicado") {
								$('#notificaciones .modal-title').html('Error');
								texto = "<p>Este usuario ya está registrado. " +
										"Verifica que los datos que estás ingresando sean correctos.</p>" +
										"<p>Si tienes alguna duda comunícate al teléfono " +
										"(55) 5322 7700 ext. 4020 a 4026</p>";
							} else if(data == "ok") {
								$('#notificaciones .modal-title').html('Registro completo');
								texto = "<p>El registro se ha realizado con éxito.</p>" +
										"<p>Recibirás por correo tu comprobante en PDF.</p>";
								$('#notificaciones .btn-primary').click(function() {
									$(location).prop("href", "jornadas");
								});
							} else {
								$('#notificaciones .modal-title').html('Registro');
								texto = "<p>Ha ocurrido un problema con tu registro.</p>" +
										"<p>Si tienes alguna duda comunícate al teléfono " +
										"(55) 5322 7700 ext. 4020 a 4026</p>";
							}
							
							$('#notificaciones .modal-body').html(texto);
							$('#notificaciones').modal();
						}
					);
				}
			});
		} else {
			$('#mensajesError .modal-title').html("Revisa la información registrada");
			$('#mensajesError .btn-primary').css("display", "none");
			$('#mensajesError .btn-default').html("Aceptar");
			$('#mensajesError').modal();
		}
	});	
	
	$("#formRegistro").validate({
		errorLabelContainer: "#mensajesError .modal-body ul",
		errorElement: 'li',
		onkeyup: false,
		ignore: "input[type='text']:hidden",
		rules: {
			nombre: {
				required: true
			},
			ap_paterno: {
				required: "#chkApPaterno:unchecked",
			},
			ap_materno: {
				required: "#chkApMaterno:unchecked",
			},
			sexo: "required",
			institucion: {
				required: true
			},
			otra_institucion: {
				required: true
			},
			entidad: "required",
			perfil: "required",
			otro_perfil: {
				required: true
			},
			telefono: {
				required: true
			},
			correo: {
				required: true,
				email: true
			},
			correo_conf: {
				required: true,
				equalTo: "#correo"
			},
			hdn_evento: "required",
			hdn_recursos: "required",
			captcha: {
				required: true,
				equalTo: "#oculto"
			}
		},
		messages: {
			nombre: "El nombre es obligatorio",
			ap_paterno: "El apellido paterno es obligatorio",
			ap_materno: "El apellido materno es obligatorio",
			sexo: "Falta seleccionar el sexo",
			institucion: "Selecciona tu institución de procedencia",
			otra_institucion: "Escribe tu institución",
			entidad: "Selecciona tu entidad federativa",
			perfil: "Selecciona tu perfil",
			otro_perfil: "Escribe tu perfil",
			telefono: "El teléfono es obligatorio",
			correo: "El correo es obligatorio",
			correo_conf: "El correo electrónico no coincide",
			hdn_evento: "Selecciona la sede a la cuál asistirás",
			hdn_recursos: "Selecciona las capacitaciones a las que asistirás",
			captcha: "Revisa que el texto escrito coincida con la imagen"
		},
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-group').removeClass('has-success');
		},
        unhighlight: function(element) {
            $(element).closest('.form-group').addClass('has-success ');
        	$(element).closest('.form-group').removeClass('has-error');
		}
	});
}

function mostrarRegiones() {
	$("#listaRegion a").click(function(e) {
		e.preventDefault();
		
		var capa = $(this).attr("href");
		capa = capa.substring(1);
		capa = $("#"+capa);
		
		$(".region").each(function() {
			var divRegion = $(this);
			if(divRegion.attr("id") != capa.attr("id")) {
				divRegion.css("display", "none");
			}
		});
		
		capa.toggle();
	});
	
	// Se ajusta la posición del turno
	$(".div-sede").each(function() {
		var elementos = [];
		var capa = $(this);
		capa.nextUntil(".div-sede").each(function() {
			if(this.tagName == "LABEL") {
				elementos.push(this);
				this.remove();
			}
		});
		
		if(elementos.length > 0) {
			for(var i=0; i<elementos.length; i++) {
				capa.append(elementos[i]);
			}
		}
	});
}

function listarRecursos() {
	$('input[type="radio"]').click(function() {
		var elemento = $(this);
		if(elemento.is(":checked")) {
			var contenedor = elemento.parentsUntil(".div-sede").parent();
			var flecha = contenedor.children().children(".regCursosSin");
			
			if(flecha.hasClass("regCursosSin")) {
				flecha = contenedor.children().children(".regCursosSin");
			} else {
				flecha = contenedor.children().children(".regCursos");
			}
			
			flecha.data("evento", elemento.data("evento"));
			flecha.removeClass("regCursosSin").addClass("regCursos");
		}
	});
	
	$(".regCursos").click(function() {
		recursosSelect = $("#hdn_recursos").val();
		var pos = recursosSelect.length;
		recursosSelect = recursosSelect.substring(0, pos-1);
		var seleccionados = recursosSelect.split("|");
		var e = $(this).data("evento");
		textoSede = $(this).parent().text();
		
		$.post(
			'registro/listarRecursos',
			{'evento': e},
			function(data) {
				if(data == "null") {
					$('#myModal .modal-body').html("No hay recursos para seleccionar.");
				} else {
					var recursos = jQuery.parseJSON(data);
					$('#myModal .modal-body').html("");
					
					$.each(recursos, function(key, val) {
						var item = '<input type="checkbox" value="'+val.id_recurso+'" />';
						item += " "+val.recurso;
						$('#myModal .modal-body').append("<div>"+item+"</div>");
					});
					
					if($('#hdn_evento').val() == e) {
						$('#myModal .modal-body input[type="checkbox"]').each(function() {
							var chk = $(this);
							for(var i=0; i<seleccionados.length; i++) {
								if(chk.val() == seleccionados[i]) {
									chk.prop("checked", true);
								}
							}
						});
					}
					
					$('#myModal .modal-body').append('<input type="hidden" id="hdn_sede_aux" value="'+e+'" />');
				}
				
				$('#myModal').modal();
			}
		);
	});
	
	$("#myModal .modal-footer .btn-primary").click(function() {
		var recursosSeleccionados = "";
		var evento = $("#hdn_sede_aux").val();
		textoRecursos = "";
		
		$('.modal-body input').each(function() {
			var chk = $(this);
			
			if(chk.is(":checked")) {
				recursosSeleccionados += chk.val() + "|";
				textoRecursos += "<li>" + chk.parent().text() + "</li>";
			}
		});
		
		$("#hdn_recursos").val(recursosSeleccionados);
		$("#hdn_evento").val(evento);
		$('#myModal').modal('hide');
	});
}

function obtenerUsuario() {
	$("#correo_conf").keyup(function() {
		if($(this).val() != "" && $(this).val() == $("#correo").val() && ($("#correo").valid() == true)) {
			$.post(
				'registro/consultarUsuario',
				{'correo': $(this).val()},
				function(data) {
					var usr = jQuery.parseJSON(data);
					$("#hdn_usuario").val(usr.id_usuario);
					$("#nombre").val(usr.nombre);
					$("#ap_paterno").val(usr.ap_paterno);
					$("#ap_materno").val(usr.ap_materno);
					$("#sexo").val(usr.sexo);
					$("#institucion").val(usr.institucion);
					$("#entidad").val(usr.entidad);
					$("#perfil").val(usr.id_perfil);
					$("#telefono").val(usr.telefono);
				}
			);
			
			$(".camposOcultos").slideDown();
		}
	});
}

$(function() {
	inicializar();
	evitarCopyPaste();
	obtenerImagen();
	validarApellidos();
	validarFormulario();
	agregarQuitarCampos();
	mostrarRegiones();
	listarRecursos();
	obtenerUsuario();
});