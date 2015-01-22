/**** Carta invitación ****/

// Validación de formulario
function validarFormulario() {
	$("#btnEnviar").click(function() {
		//$("#formRegistro").valid();
		$("#formCarta").submit();
	});	
	
	$("#formCarta").validate({
		errorElement: 'span',
		onkeyup: false,
		rules: {
			nombre: {
				required: true
			},
			institucion: {
				required: true
			},
			sede: {
				required: true,
			}
		},
		messages: {
			nombre: "",
			institucion: "",
			sede: ""
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

$(function() {
	validarFormulario();
});