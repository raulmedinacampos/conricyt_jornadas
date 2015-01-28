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
					<td><?php echo $dato->registrados; ?></td>
				</tr>
    			<?php
					$i ++;
				}
				?>
  		</tbody>
			<tfoot>
				<tr class="info">
					<th colspan="2">Total</th>
					<th><?php echo $total; ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>