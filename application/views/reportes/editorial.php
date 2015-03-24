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
					<th>Recurso</th>
					<th>Registrados</th>
				</tr>
    			<?php
				$i = 1;
				
				foreach ( $registros->result() as $registo ) {
				?>
    			<tr>
					<th><?php echo $i; ?></th>
					<td><?php echo $registo->recurso; ?></td>
					<td><?php echo $registo->total; ?></td>
				</tr>
    			<?php
					$i ++;
				}
				?>
  			</tbody>
		</table>
	</div>
</div>