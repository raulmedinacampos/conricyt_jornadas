<?php
class Administracion extends CI_Controller {
	public function agregar() {
		
	}
	
	public function modificar() {
		
	}
	
	public function eliminar() {
		$this->load->database();
		
		$this->db = $this->load->database('default', TRUE);
		$correo = "enriqueta_velazquez@utcj.edu.mx";
		
		$query_usuario = "SELECT id_usuario FROM usuario WHERE correo = '$correo' AND estatus = 1";
		$result_usuario = $this->db->query($query_usuario);
		echo $query_usuario."<br/>";
		$id_usuario = $result_usuario->row();
		$id_usuario = $id_usuario->id_usuario;
		
		$query_inscrito_a = "SELECT COUNT(*) AS total FROM usuario_programa WHERE usuario = $id_usuario";
		$result_inscrito_a = $this->db->query($query_inscrito_a);
		$total = $result_inscrito_a->row();
		$total = $total->total;
		
		$query_programa = "DELETE FROM usuario_programa WHERE programa = 2 AND usuario = $id_usuario";
		echo $query_programa."<br/>";
		//$this->db->query($query_programa);
		
		$query_evento = "SELECT e.abreviatura, ue.evento FROM usuario_evento ue 
							JOIN evento e ON ue.evento = e.id_evento 
							JOIN ubicacion_evento ub ON e.ubicacion_evento = ub.id_ubicacion_evento 
							WHERE ub.programa = 2 AND ue.usuario = $id_usuario";
		$result_evento = $this->db->query($query_evento);
		$evento = $result_evento->row();
		echo $query_evento."<br/>";
		
		$query_evento = "DELETE FROM usuario_evento WHERE evento = $evento->evento AND usuario = $id_usuario";
		echo $query_evento."<br/>";
		//$this->db->query($query_evento);
		
		$query_recurso = "DELETE FROM usuario_recurso WHERE usuario = $id_usuario";
		echo $query_recurso."<br/>";
		//$this->db->query($query_recurso);
		
		if($total == 1) {
			$query_borrar = "DELETE FROM usuario WHERE id_usuario = $id_usuario";
			echo $query_borrar."<br/>";
			//$this->db->query($query_borrar);
		}
		
		$this->db = $this->load->database('moodle', TRUE);
		
		$query_usr_moodle = "SELECT id FROM mdl_user WHERE email = '$correo'";
		$result_usr_moodle = $this->db->query($query_usr_moodle);
		echo $query_usr_moodle."<br/>";
		$usuario_moodle = $result_usr_moodle->row();
		$usuario_moodle = $usuario_moodle->id;
		
		$query_curso = "SELECT id FROM mdl_course WHERE shortname = '$evento->abreviatura'";
		$result_curso = $this->db->query($query_curso);
		echo $query_curso."<br/>";
		$id_curso = $result_curso->row();
			
		$query_contexto = "SELECT id FROM mdl_context WHERE instanceid = '$id_curso->id' AND contextlevel = 50";
		$result_contexto = $this->db->query($query_contexto);
		echo $query_contexto."<br/>";
		$id_contexto = $result_contexto->row();
			
		$query_enrol = "SELECT id FROM mdl_enrol WHERE courseid = '$id_curso->id' AND enrol = 'manual'";
		$result_enrol = $this->db->query($query_enrol);
		echo $query_enrol."<br/>";
		$id_enrol = $result_enrol->row();
		
		$query_inscrito_m = "SELECT COUNT(*) AS total FROM mdl_user_enrolments WHERE userid = $usuario_moodle";
		$result_inscrito_m = $this->db->query($query_inscrito_m);
		$total_m = $result_inscrito_m->row();
		$total_m = $total_m->total;
		
		$query_matricular = "DELETE FROM mdl_user_enrolments WHERE enrolid = $id_enrol->id AND userid = $usuario_moodle";
		echo $query_matricular."<br/>";
		//$this->db->query($query_matricular);
		
		$query_rol = "DELETE FROM mdl_role_assignments WHERE contextid = $id_contexto->id AND userid = $usuario_moodle";
		echo $query_rol."<br/>";
		//$this->db->query($query_rol);
		
		if($total_m == 1) {
			$query_borrar = "DELETE FROM mdl_user WHERE id = $usuario_moodle";
			echo $query_borrar."<br/>";
			//$this->db->query($query_borrar);
		}
	}
}
?>