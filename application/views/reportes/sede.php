<style type="text/css">
	td:not(:first-of-type) {
		text-align: center;
	}
	
	td:last-of-type {
		padding-right: 25px !important;
		text-align: right;
	}
	
	td:first-of-type {
	    width: 350px !important;
	}
</style>

<div class="contenido row">
	<div class="col-xs-10 col-xs-offset-1">
		<!--10 columnas-->
		<div class="contenido-mundo">
			<img src="<?php echo base_url('images/mundo.png'); ?>">
		</div>
		<div class="contenido-titulo">Registros por institución</div>
		<table class="table table-condensed table-striped">
			<tbody>
				<tr>
					<th>#</th>
					<th>Sede</th>
					<th>Por instituciones</th>
					<th>Por editoriales</th>
					<th>Por perfil</th>
					<th>Datos registrados</th>
					<th>Registrados</th>
				</tr>
    			<?php
				$i = 1;
				
				foreach ( $datos as $dato ) {
					$colorFila = ($dato->registrados < 20) ? "danger" : "";
				?>
    			<tr class="<?php echo $colorFila; ?>">
					<th><?php echo $i; ?></th>
					<td><?php echo $dato->sede; ?></td>
					<td><a href="<?php echo base_url('reporte/por-institucion/'.$dato->id)?>">Ver reporte</a></td>
					<td><a href="<?php echo base_url('reporte/por-editorial/'.$dato->id)?>">Ver reporte</a></td>
					<td><a href="<?php echo base_url('reporte/por-perfil/'.$dato->id)?>">Ver reporte</a></td>
					<td><a href="<?php echo base_url('reporte/por-usuario/'.$dato->id)?>">Ver reporte</a></td>
					<td><?php echo $dato->registrados; ?></td>
				</tr>
    			<?php
					$i ++;
				}
				?>
  		</tbody>
			<tfoot>
				<tr class="info">
					<th colspan="6">Total</th>
					<th style="padding-right:25px; text-align:right;"><?php echo $total; ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>