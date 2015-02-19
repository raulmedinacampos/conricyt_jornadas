<?php
class Capacitacion_model extends CI_Model {
	public function getDataByMail($mail) {
		$this->db->select('id_usuario, username, password');
		$this->db->from('usuario');
		$this->db->where('correo', $mail);
		$this->db->where('estatus >', 0);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
}
?>