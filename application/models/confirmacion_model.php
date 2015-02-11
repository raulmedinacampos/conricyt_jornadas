<?php
class Confirmacion_model extends CI_Model {
	public function listarUsuariosIMSS($estatus = "", $condiciones = "") {
		$this->db->select('u.id_usuario, u.nombre, u.ap_paterno, u.ap_materno, u.institucion, u.correo');
		$this->db->from('usuario u');
		$this->db->join('usuario_programa up', 'u.id_usuario = up.usuario');
		$this->db->join('usuario_evento ue', 'u.id_usuario = ue.usuario');
		$this->db->where('up.programa', 2);
		$this->db->where('ue.evento', 90);
		if($estatus) {
			$this->db->where('up.estatus', $estatus);
		}
		if($condiciones) {
			$this->db->where($condiciones);
		}
		$this->db->order_by('u.nombre, u.ap_paterno, u.ap_materno');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function actualizarEstatus($id, $estatus) {
		$this->db->set('estatus', $estatus);
		$this->db->where('usuario', $id);
		
		if($this->db->update('usuario_programa')) {
			return true;
		}
	}
}
?>