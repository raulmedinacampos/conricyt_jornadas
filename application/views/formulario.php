<script type="text/javascript" src="<?php echo base_url('scripts/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/typeahead.bundle.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/formulario.js'); ?>"></script>
<script type="text/javascript">
function obtenerImagen() {
	$.post("<?php echo base_url('captcha'); ?>", '', function(data) {
		$("#img-captcha").attr("src", "<?php echo base_url('captcha/getImage').'/'; ?>"+data+"/"+Math.random());
		$("#oculto").val(data);
	});
}

function agregarQuitarCampos() {
	$("#perfil").change(function() {
		if($("#perfil option:selected").text() == "Otro") {
			var elemento = '<?php
								echo '<div class="form-group otro_perfil">';
								$attr = array(
											 'class'	=>	'col-md-4 control-label otro_perfil'
											 );
								echo form_label('Especifica el perfil:', '', $attr);
								echo '<div class="col-md-8">';
								$attr = array(
											 'id'		=>	'otro_perfil',
											 'name'		=>	'otro_perfil',
											 'class'	=>	'otro_perfil form-control'
											 );
								echo form_input($attr);
								echo '</div>';
								echo '</div>'; ?>';
			var contenedor = $("#perfil").parentsUntil(".form-group").parent();
			contenedor.after(elemento);
		} else {
			$(".otro_perfil").remove();
		}
	});
	
	$("#institucion").change(function() {
		if($("#institucion option:selected").text() == "Otra") {
			var elemento = '<?php
								echo '<div class="form-group otro_perfil">';
								$attr = array(
											 'class'	=>	'col-md-4 control-label otra_institucion'
											 );
								echo form_label('Escribe tu institución:', '', $attr);
								echo '<div class="col-md-8">';
								$attr = array(
											 'id'		=>	'otra_institucion',
											 'name'		=>	'otra_institucion',
											 'class'	=>	'otra_institucion form-control'
											 );
								echo form_input($attr);
								echo '</div>';
								echo '</div>'; ?>';
			var contenedor = $("#institucion").parentsUntil(".form-group").parent();
			contenedor.after(elemento);
		} else {
			$(".otra_institucion").remove();
		}
	});
}
</script>

<div class="contenido row">
  <div class="col-xs-10 col-xs-offset-1">
    <!--10 columnas-->
    <div class="contenido-mundo" ><img src="<?php echo base_url('images/mundo.png'); ?>"></div>
    <div class="contenido-titulo">Formulario de registro</div>
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Lee cuidadosamente las indicaciones antes de iniciar tu registro</h3>
      </div>
      <div class="panel-body text-justify">
      <p>Para realizar tu registro a las <em>Jornadas de Capacitación 2015</em> debes llenar el siguiente formulario. Si te registraste para asistir a la última edición del Seminario <em>Entre Pares 2014</em>, ya contamos con tus datos, verifica que la información precargada sea correcta o ayúdanos actualizándola.</p>
      <p>Es importante que verifiques tus datos antes de finalizar, pues de la misma manera en que llenes los campos de nombre y apellidos aparecerán en la constancia de asistencia que podrás obtener posterior a las capacitaciones. Ten presente que el correo que ingreses en tu registro será el mismo que utilices para acceder a las evaluaciones durante las capacitaciones.</p>
      <p><strong>¿Cómo me registro?</strong></p>
      <ol>
        <li>Selecciona la región</li>
        <li>Se te mostrarán las instituciones sedes pertenecientes a esa región, selecciona la de tu interés</li>
        <li>Elige las capacitaciones de las editoriales a las que deseas asistir</li>
        <li>Al término de tu registro recibirás por correo electrónico un comprobante de confirmación</li>
      </ol>
      </div>
    </div>
    
<div class="col-md-8 col-md-offset-2">

<?php
$attr = array(
	'id'	=>	'formRegistro',
	'name'	=>	'formRegistro',
	'class'	=>	'form-horizontal'
);

echo form_open(base_url('registro/registrarDatos'), $attr);

echo '<div class="camposOcultos">';
echo '<div class="form-group">';
echo form_label('Nombre:', '', array('class' => 'col-md-4 control-label'));
$attr = array(
	'id'	=>	'nombre',
	'name'	=>	'nombre',
	'class'	=>	'form-control'
);
echo '<div class="col-md-8">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Apellido paterno:', '', array('class' => 'col-md-4 control-label'));
$attr = array(
	'id'	=>	'ap_paterno',
	'name'	=>	'ap_paterno',
	'class'	=>	'form-control'
);
echo '<div class="col-md-8">';
echo form_input($attr);

$attr = array(
	'id'	=>	'chkApPaterno',
	'name'	=>	'chkApPaterno',
	'value'	=>	'1'
);
echo '<span class="help-block">';
echo form_checkbox($attr);
echo ' Sin apellido paterno</span>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Apellido materno:', '', array('class' => 'col-md-4 control-label'));
$attr = array(
	'id'	=>	'ap_materno',
	'name'	=>	'ap_materno',
	'class'	=>	'form-control'
);
echo '<div class="col-md-8">';
echo form_input($attr);

$attr = array(
	'id'	=>	'chkApMaterno',
	'name'	=>	'chkApMaterno',
	'value'	=>	'1'
);
echo '<span class="help-block">';
echo form_checkbox($attr);
echo ' Sin apellido materno</span>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Sexo:', '', array('class' => 'col-md-4 control-label'));
$opc = array(
	''	=>	'Selecciona',
	'm'	=>	'Masculino',
	'f'	=>	'Femenino'
);
echo '<div class="col-md-8">';
echo form_dropdown('sexo', $opc, '', 'id="sexo" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Institución de procedencia:', '', array('class' => 'col-md-4 control-label'));
$opc = array('' => 'Selecciona');

foreach ($instituciones->result() as $institucion) {
	$opc[$institucion->institucion] = $institucion->institucion;
}

$opc['Otra'] = "Otra";

echo '<div id="scrollable-dropdown-menu" class="col-md-8">';
echo form_dropdown('institucion', $opc, '', 'id="institucion" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Entidad federativa:', '', array('class' => 'col-md-4 control-label'));
$opc = array('' => 'Selecciona');

foreach ($entidades->result() as $entidad) {
	$opc[$entidad->id_entidad] = $entidad->entidad;
}

echo '<div class="col-md-8">';
echo form_dropdown('entidad', $opc, '', 'id="entidad" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Perfil:', '', array('class' => 'col-md-4 control-label'));
$opc = array('' => 'Selecciona');

foreach ($perfiles->result() as $perfil) {
	$opc[$perfil->id_perfil] = $perfil->perfil;
}

echo '<div class="col-md-8">';
echo form_dropdown('perfil', $opc, '', 'id="perfil" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Teléfono:', '', array('class' => 'col-md-4 control-label'));
$attr = array(
		'id'	=>	'telefono',
		'name'	=>	'telefono',
		'class'	=>	'form-control'
);
echo '<div class="col-md-8">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '</div>';  // Cierra campos ocultos

echo '<div class="form-group">';
echo form_label('Correo:', '', array('class' => 'col-md-4 control-label'));
$attr = array(
		'id'	=>	'correo',
		'name'	=>	'correo',
		'class'	=>	'form-control'
);
echo '<div class="col-md-8">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Confirme su correo:', '', array('class' => 'col-md-4 control-label'));
$attr = array(
		'id'	=>	'correo_conf',
		'name'	=>	'correo_conf',
		'class'	=>	'form-control'
);
echo '<div class="col-md-8">';
echo form_input($attr);

$attr = array(
		'id'	=>	'hdn_evento',
		'name'	=>	'hdn_evento',
		'type'	=>	'hidden'
);
echo form_input($attr);

$attr = array(
		'id'	=>	'hdn_recursos',
		'name'	=>	'hdn_recursos',
		'type'	=>	'hidden'
);
echo form_input($attr);

$attr = array(
		'id'	=>	'hdn_usuario',
		'name'	=>	'hdn_usuario',
		'type'	=>	'hidden'
);
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="camposOcultos">';
?>
  <p><strong>Elige la región de la sede de tu interés. Solo puedes seleccionar una sede a la vez.</strong></p>
  <ul id="listaRegion" class="list-inline">
  <?php
  	foreach($regiones->result() as $region) {
		echo '<li><a href="#div-'.strtolower(str_replace(" ", "-", $region->region)).'">'.$region->region.'</a></li>';
	}
  ?>
  </ul>
<?php
	foreach($regiones->result() as $region) {
?>
	<div id="div-<?php echo strtolower(str_replace(" ", "-", $region->region)); ?>" class="region panel">
    	<div class="panel-heading">
          <h3 class="panel-title"><?php echo $region->region;?></h3>
        </div>
    	<div class="panel-body">
    	<?php
    	foreach($tipo_instituciones->result() as $tipo) {
			if($tipo->region == $region->id_region) {
				echo '<span class="tipo-institucion">'.$tipo->tipo_institucion."</span>";
    	
		    	foreach($datos->result() as $val) {
					list($fecha, $hora) = explode(" ", $val->fin);
					
					if($region->id_region == $val->region && $tipo->id_tipo_institucion == $val->tipo_institucion) {
						
						if($hora == '00:00:00' || $hora < '14:00:00') {
							echo '<div class="div-sede">';
				?>
						<p class="nombre-sede"><strong><?php echo $val->evento; ?></strong>
                        <?php
                        if($hora == '00:00:00' || $hora < '14:00:00') {
							if($hora == '00:00:00') {
								$claseFlecha = "regCursos";
							} else {
								$claseFlecha = "regCursosSin";
							}
							
							if($val->estatus == 1 || $fecha > date('Y-m-d')) {
								echo '<span class="'.$claseFlecha.'" data-evento="'.$val->id_evento.'"><img src="'.base_url('images/flecha.png').'" /></span>';
							}
							
							if($val->estatus == 2) {
								echo '<span class="regCursosSin" data-evento="'.$val->id_evento.'"><img src="'.base_url('images/boton_acceso.png').'" /></span>';
							}

							if($val->estatus == 3 || $fecha < date('Y-m-d')) {
								echo '<span class="regCursosSin" data-evento="'.$val->id_evento.'"><img src="'.base_url('images/boton_fecha.png').'" /></span>';
							}
                        }
                        ?>
                        </p>
						<p class="info-sede">
						<?php
								echo $val->descripcion;
								echo '</div>';
						}
						
						// Turno matutino
						if($hora == '13:15:00') {
							$id_turno = $val->id_evento;
							echo '<label>Turno matutino <input type="radio" id="turno_'.$val->id_evento.'" name="turno_'.$id_turno.'" value="'.$val->id_evento.'" data-evento="'.$val->id_evento.'" /></label>';
						}
						
						// Turno vespertino
						if($hora == '19:15:00') {
							echo '<label>Turno vespertino <input type="radio" id="turno_'.$val->id_evento.'" name="turno_'.$id_turno.'" value="'.$val->id_evento.'" data-evento="'.$val->id_evento.'" /></label>';
						}
						?>
						</p>
				<?php
					}
				}
			}
		}
    	?>
      </div>  <!-- Panel body -->
    </div>
<?php
	}
?>
<!-- Ventana modal de errores de validación -->
<div class="modal fade" id="mensajesError" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Revisa la información registrada</h4>
      </div>
      <div class="modal-body">
      	<ul></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Ventana modal donde se cargan los recursos -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Selecciona las capacitaciones a las que asistirás</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary">Finalizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Ventana modal de notificaciones -->
<div class="modal fade bs-example-modal-sm" id="notificaciones" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
          </div>
      </div>
  </div>
</div>
<?php
echo '<div class="form-group">';
echo form_label('Escribe el texto de la imagen', '', array('class' => 'col-md-4 control-label requerido'));

$attr = array(
		'id'	=>	'captcha',
		'name'	=>	'captcha',
		'class'	=>	'form-control'
);
echo '<div class="col-md-3">';
echo form_input($attr);

$attr = array(
		'id'	=>	'oculto',
		'name'	=>	'oculto',
		'type'	=>	'hidden'
);
echo form_input($attr);
echo '</div>';

echo '<div class="col-md-3">';
echo '<img id="img-captcha" src="'.base_url("captcha").'" />';
echo '</div>';

echo '<div class="col-md-2 text-right">';
$attr = array(
		'id'	=>	'btn_captcha',
		'name'	=>	'btn_captcha',
		'class'	=>	'btn btn-primary',
		'content' => '<span class="glyphicon glyphicon-refresh"></span>'
);
echo form_button($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<div class="col-md-offset-4 col-md-9">';

$attr = array(
		'id'		=>	'btnEnviar',
		'name'		=>	'btnEnviar',
		'class'		=>	'btn btn-primary',
		'content'	=>	'Enviar'
);
echo form_button($attr);
echo '</div>';
echo '</div>';

echo '</div>'; // Cierre div campos ocultos

echo form_close();
?>
</div>


  </div>
  <!-- Fin 10 columnas-->
</div>
