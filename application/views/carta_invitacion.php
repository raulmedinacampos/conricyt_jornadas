<script type="text/javascript" src="<?php echo base_url('scripts/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/carta.js'); ?>"></script>

<div class="contenido row">
  <div class="col-xs-10 col-xs-offset-1">
    <!--10 columnas-->
    <div class="contenido-mundo" ><img src="<?php echo base_url('images/mundo.png'); ?>"></div>
    <div class="contenido-titulo">Carta invitación</div>
    <div class="panel panel-primary">
      <div class="panel-body">
        <p>Si desea obtener una Carta Invitación para asistir a las <em>Jornadas de Capacitación CONRICYT 2015</em> debes llenar los siguientes campos y podrás descargarla.</p>
        <p>Es importante que verifiques que tus datos sean correctos, pues de la misma manera en que llenes los campos de nombre y apellido aparecerá en la Carta Invitación.</p>
        <p><strong>Nota: Este no es un preregistro,</strong> sólo funciona para descargar la Carta Invitación, en próximos días abriremos el registro.</p>
      </div>
    </div>
<?php
$attr = array(
	'id'	=>	'formCarta',
	'name'	=>	'formCarta',
	'target'=>	'_blank',
	'class'	=>	'form-horizontal'
	);
echo form_open(base_url('carta-invitacion/generarCarta'), $attr);

echo '<div class="form-group">';
echo form_label('Nombre completo:', '', array('class' => 'col-md-4 control-label'));
$attr = array(
	'id'	=>	'nombre',
	'name'	=>	'nombre',
	'class'	=>	'form-control'
	);
echo '<div class="col-md-6">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Institución:', '', array('class' => 'col-md-4 control-label'));
$attr = array(
	'id'	=>	'institucion',
	'name'	=>	'institucion',
	'class'	=>	'form-control'
	);
echo '<div class="col-md-6">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Selecciona sede y fecha:', '', array('class' => 'col-md-5 control-label'));
echo '</div>';

echo '<div class="form-group">';
echo '<div class="col-md-7 col-md-offset-3">';
echo '<table class="table table-striped table-condensed">';
echo '<tr><th>&nbsp;</th><th>Institución</th><th>Fecha</th></tr>';
foreach($sedes->result() as $sede) {
	$attr = array(
			'id'	=>	'rd_'.$sede->id,
			'name'	=>	'sede',
			'value'	=>	$sede->id
		);
	echo '<tr>';
	echo '<td>'.form_radio($attr).'</td><td>'.$sede->institucion.'</td><td>'.$sede->fecha.'</td>';
	echo '</tr>';
}
echo '</table>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<div class="text-center">';
$attr = array(
	'id'	=>	'btnEnviar',
	'content'=>	'Generar carta invitación',
	'class'	=>	'btn btn-primary'
	);
echo form_button($attr);
echo '</div>';
echo '</div>';

echo form_close();
?>
  </div>
  <!-- Fin 10 columnas-->
</div>
