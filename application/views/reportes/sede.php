<table class="table table-condensed table-striped">
  <tbody>
    <tr>
      <th>#</th>
      <th>Sede</th>
      <th>Registrados</th>
    </tr>
    <?php
	$i = 1;
	
	foreach($datos as $dato) {
		$colorFila = ($dato->registrados < 20) ? "danger" : "";
	?>
    <tr class="<?php echo $colorFila; ?>">
      <th><?php echo $i; ?></td>
      <td><?php echo $dato->sede; ?></td>
      <td><?php echo $dato->registrados; ?></td>
    </tr>
    <?php
		$i++;
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