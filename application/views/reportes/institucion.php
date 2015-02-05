<div class="contenido row">
	<div class="col-xs-10 col-xs-offset-1">
		<!--10 columnas-->
		<div class="contenido-mundo">
			<img src="<?php echo base_url('images/mundo.png'); ?>">
		</div>
		<div class="contenido-titulo"><?php echo $sede->evento; ?></div>
		<table class="table table-condensed table-striped">
			<tbody>
				<tr>
					<th>#</th>
					<th>Institución</th>
					<th>Registrados</th>
				</tr>
    			<?php
				$i = 1;
				
				foreach ( $datos as $dato ) {
				?>
    			<tr>
					<th><?php echo $i; ?></th>
					<td><?php echo $dato->institucion; ?></td>
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