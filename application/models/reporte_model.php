<?php
class Reporte_model extends CI_Model {
	public function obtenerTotalUsuarios() {
		$this->db->select("COUNT(*) AS total");
		$this->db->from("usuario_programa up");
		$this->db->join("usuario u", "up.usuario = u.id_usuario");
		$this->db->where("up.programa", 2);
		$this->db->where("up.estatus", 1);
		$query = $this->db->get();
		
		$total = $query->row();
		return $total->total;
	}
	
	public function obtenerTotalUsuariosPorSede($sede) {
		$this->db->select("COUNT(*) AS total");
		$this->db->from("usuario_programa up");
		$this->db->join("usuario u", "up.usuario = u.id_usuario");
		$this->db->join("usuario_evento ue", "u.id_usuario = ue.usuario");
		$this->db->where("ue.evento", $sede);
		$this->db->where("up.programa", 2);
		$this->db->where("up.estatus", 1);
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
	
	public function listarInstitucionesPorSede($sede) {
		$this->db->select("DISTINCT(u.institucion)");
		$this->db->from("usuario u");
		$this->db->join("usuario_programa up", "u.id_usuario = up.usuario");
		$this->db->join("usuario_evento ue", "u.id_usuario = ue.usuario");
		$this->db->where("ue.evento", $sede);
		$this->db->where("up.programa", 2);
		$this->db->where("up.estatus", 1);
		$this->db->order_by("u.institucion");
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
		$this->db->where("up.estatus", 1);
		$query = $this->db->get();
		
		$total = $query->row();
		return $total->total;
	}
	
	public function obtenerRegistradosPorInstitucionSede($institucion, $sede) {
		$this->db->select("COUNT(*) AS total");
		$this->db->from("usuario_programa up");
		$this->db->join("usuario u", "up.usuario = u.id_usuario");
		$this->db->join("usuario_evento ue", "ue.usuario = u.id_usuario");
		$this->db->join("evento e", "e.id_evento = ue.evento");
		$this->db->where("u.institucion", $institucion);
		$this->db->where("e.id_evento", $sede);
		$this->db->where("up.estatus", 1);
		$this->db->where("up.programa", 2);
		$query = $this->db->get();
	
		$total = $query->row();
		return $total->total;
	}
	
	public function consultarSedePorId($sede) {
		$this->db->select("id_evento, evento, descripcion, inicio, fin");
		$this->db->from("evento");
		$this->db->where("id_evento", $sede);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
}
?>