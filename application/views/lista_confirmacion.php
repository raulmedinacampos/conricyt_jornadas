<style type="text/css">
	.table td, .table th {font-size:12px;}
</style>
<script type="text/javascript">
	function confirmarUsuario() {
		$(".table a").click(function(e) {
			e.preventDefault();
			var id = $(this).data("id");
			$.post("<?php echo base_url('confirmacion/confirmar'); ?>",
					{id: id},
					function(data) {
						if(data == 1) {
							window.location.href = "<?php echo base_url('confirmacion/imss'); ?>"
						}
					}
			);
		});
	}

	function enviarFormulario() {
		$("#btnBuscar").click(function() {
			$("#formFiltros").submit();
		});
	}

	function limpiarFormulario() {
		$("#btnLimpiar").click(function() {
			$("#formFiltros input").each(function() {
				$(this).val(""); 
			});
		});
	}
	
	$(function() {
		confirmarUsuario();
		enviarFormulario();
		limpiarFormulario();
	});
</script>
<div class="contenido row">
  <div class="col-xs-10 col-xs-offset-1"> 
    <!--10 columnas-->
    <div class="contenido-mundo" ><img src="<?php echo base_url('images/mundo.png'); ?>"></div>
    <div class="contenido-titulo">Confirmación de usuarios</div>
    <div class="panel panel-primary">
      <div class="panel-body">
        <?php
        $attr = array(
        		'id'	=>	'formFiltros',
        		'name'	=>	'formFiltros',
        		'class'	=>	'form-horizontal'
        );
		echo form_open(base_url('confirmacion/imss'), $attr);
		
		echo '<div class="form-group">';
		echo form_label('Nombre:', '', array('class' => 'col-md-2 control-label'));
		
		$attr = array(
			'id'	=>	'nombre',
			'name'	=>	'nombre',
			'value'	=>	$nombre,
			'class'	=>	'form-control'
		);
		echo '<div class="col-md-4">';
		echo form_input($attr);
		echo '</div>';

		echo form_label('Apellido paterno:', '', array('class' => 'col-md-2 control-label'));
		$attr = array(
			'id'	=>	'ap_paterno',
			'name'	=>	'ap_paterno',
			'value'	=>	$ap_paterno,
			'class'	=>	'form-control'
		);
		echo '<div class="col-md-4">';
		echo form_input($attr);
		echo '</div>';
		echo '</div>';
		
		echo '<div class="form-group">';
		echo form_label('Apellido materno:', '', array('class' => 'col-md-2 control-label'));
		$attr = array(
			'id'	=>	'ap_materno',
			'name'	=>	'ap_materno',
			'value'	=>	$ap_materno,
			'class'	=>	'form-control'
		);
		echo '<div class="col-md-4">';
		echo form_input($attr);
		echo '</div>';
		
		echo form_label('Correo:', '', array('class' => 'col-md-2 control-label'));
		$attr = array(
			'id'	=>	'correo',
			'name'	=>	'correo',
			'value'	=>	$correo,
			'class'	=>	'form-control'
		);
		echo '<div class="col-md-4">';
		echo form_input($attr);
		echo '</div>';
		echo '</div>';
		
		echo '<div class="form-group">';
		echo '<div class="col-md-12">';
		$attr = array(
			'id'	=>	'btnBuscar',
			'name'	=>	'btnBuscar',
			'class'	=>	'btn btn-primary pull-right',
			'content'=>	'<span class="glyphicon glyphicon-search"></span> Buscar'
		);
		echo form_button($attr);
		$attr = array(
			'id'	=>	'btnLimpiar',
			'name'	=>	'btnLimpiar',
			'class'	=>	'btn btn-default pull-right',
			'style'	=>	'margin-right:20px;',
			'content'=>	'Limpiar'
		);
		echo form_button($attr);
		echo '</div>';
		echo '</div>';
		echo form_close();
		?>
      </div>
    </div>
    <div class="panel panel-warning">
      <div class="panel-heading">
        <h3 class="panel-title">Usuarios pendientes de confirmación</h3>
      </div>
      <table class="table table-striped table-condensed">
        <tbody>
          <tr>
            <th>#</th>
            <th>Nombre completo</th>
            <th>Institución</th>
            <th>Correo</th>
            <th>Confirmar</th>
          </tr>
          <?php
			$i = 1;
			if($pendientes) {
				foreach($pendientes->result() as $val) {
				?>
			  <tr>
				<th><?php echo $i; ?></th>
				<td><?php echo trim($val->nombre." ".$val->ap_paterno." ".$val->ap_materno); ?></td>
				<td><?php echo $val->institucion; ?></td>
				<td><?php echo $val->correo; ?></td>
                <td><a href="#" data-id="<?php echo $val->id_usuario; ?>"><span class="btn btn-info btn-xs">Confirmar asistencia</span></a></td>
		      </tr>
			  <?php
					$i++;
				}
			} else {
			?>
          <tr>
            <th colspan="5" class="text-center" style="font-size: 14px">No hay registros</th>
          </tr>
          <?php
			}
		  ?>
        </tbody>
      </table>
    </div>
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Usuarios confirmados</h3>
      </div>
      <table class="table table-striped table-condensed">
        <tbody>
          <tr>
            <th>#</th>
            <th>Nombre completo</th>
            <th>Institución</th>
            <th>Correo</th>
          </tr>
          <?php
			$i = 1;
			if($confirmados) {
				foreach($confirmados->result() as $val) {
				?>
			  <tr>
				<th><?php echo $i; ?></th>
				<td><?php echo trim($val->nombre." ".$val->ap_paterno." ".$val->ap_materno); ?></td>
				<td><?php echo $val->institucion; ?></td>
				<td><?php echo $val->correo; ?></td>
			  </tr>
			  <?php
					$i++;
				}
			} else {
			?>
          <tr>
            <th colspan="4" class="text-center" style="font-size: 14px">No hay registros</th>
          </tr>
          <?php
			}
		  ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Fin 10 columnas--> 
</div>
