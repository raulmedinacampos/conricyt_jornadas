<?php
class Reporte_model extends CI_Model {
	public function obtenerTotalUsuarios() {
		$this->db->select("COUNT(*) AS total");
		$this->db->from("usuario_programa up");
		$this->db->join("usuario u", "up.usuario = u.id_usuario");
		$this->db->where("up.programa", 2);
		$query = $this->db->get();
		
		$total = $query->row();
		return $total->total;
	}
	
	public function listarSedes() {
		$this->db->select("e.id_evento, e.evento");
		$this->db->from("evento e");
		$this->db->join("ubicacion_evento ue", "e.ubicacion_evento = ue.id_ubicacion_evento");
		$this->db->where("ue.programa", 2);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function obtenerRegistradosPorSede($sede) {
		$this->db->select("COUNT(*) AS total");
		$this->db->from("usuario_programa up");
		$this->db->join("usuario u", "up.usuario = u.id_usuario");
		$this->db->join("usuario_evento ue", "ue.usuario = u.id_usuario");
		$this->db->join("evento e", "e.id_evento = ue.evento");
		$this->db->where("e.id_evento", $sede);
		$this->db->where("up.programa", 2);
		$query = $this->db->get();
		
		$total = $query->row();
		return $total->total;
	}
}
?>