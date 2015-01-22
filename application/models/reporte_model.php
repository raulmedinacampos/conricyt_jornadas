<?php
class Reporte_model extends CI_Model {
	public function getTotalUsers() {
		$this->db->select("COUNT(*) AS total");
		$this->db->from("usuario_programa up");
		$this->db->join("usuario u", "up.usuario = u.id_usuario");
		$this->db->where("up.programa", 2);
		$query = $this->db->get();
		
		$total = $query->row();
		return $total->total;
	}
}
?>