<?php
class Cargar_imss extends CI_Controller {
	public function index() {
		$this->load->database();
		
		$this->db = $this->load->database('default', TRUE);
		
		$query_total = "SELECT COUNT(*) AS total FROM imss_pre WHERE estatus = 3";
		$result_total = $this->db->query($query_total);
		$total = $result_total->row();
		echo "<h3>Faltan $total->total usuarios por cargar</h3>";
		
		$query = "SELECT * FROM imss_pre WHERE estatus = 3 ORDER BY id_usuario LIMIT 1";
		$result = $this->db->query($query);
		
		foreach($result->result() as $row) {
			$time = date('Y-m-d H:i:s');
			$capacitaciones = array(1, 2, 3, 4, 5, 6, 9, 10, 14, 16, 20, 26, 30, 34, 35, 41, 42, 43);
			//$capacitaciones = array(2, 3, 4, 5, 6, 9, 10, 14, 16, 20, 26, 30, 34, 35, 41, 42, 43);
			$query_usuario = "INSERT INTO usuario VALUES(NULL, '$time', NULL, '$row->folio', '$row->username', 
					'$row->password', '$row->nombre', '$row->ap_paterno', '$row->ap_materno', '$row->sexo', 
					'$row->institucion', '$row->telefono', $row->entidad, $row->id_perfil, 0, NULL, NULL, 
					'$row->correo', NULL, NULL, 1)";
			//$query_usuario = "SELECT id_usuario FROM usuario WHERE correo = '$row->correo'";
			$result_usuario = $this->db->query($query_usuario);
			echo $query_usuario."<br/>";
			$id_usuario = $this->db->insert_id();
			//$id_usuario = $result_usuario->row();
			//$id_usuario = $id_usuario->id_usuario;
			//$id_usuario = 3433;
			
			/*$query_actualizar = "UPDATE usuario SET fecha_modificacion = '$time', username = '$row->username', password = '$row->password' WHERE id_usuario = $id_usuario";
			$this->db->query($query_actualizar);
			echo $query_actualizar."<br />";*/
			
			$query_programa = "INSERT INTO usuario_programa VALUES($id_usuario, 2, '$time', 0, 2)";
			$this->db->query($query_programa);
			echo $query_programa."<br/>";
			
			$query_evento = "INSERT INTO usuario_evento VALUES($id_usuario, 90)";
			$this->db->query($query_evento);
			echo $query_evento."<br/>";
			
			foreach($capacitaciones as $capacitacion) {
				$query_capacitacion = "INSERT INTO usuario_recurso VALUES($id_usuario, $capacitacion)";
				$this->db->query($query_capacitacion);
				echo $query_capacitacion."<br/>";
			}
			
			/* Se insertan los registros en Moodle */
			$this->db = $this->load->database('moodle', TRUE);
			
			$query_usr_moodle = "INSERT INTO mdl_user(auth, confirmed, mnethostid, username, password, 
					firstname, lastname, email, emailstop, city, country, lang, timezone, 
					firstaccess, lastaccess, lastlogin, currentlogin, descriptionformat, 
					mailformat, maildigest, maildisplay, htmleditor, autosubscribe, 
					trackforums, timecreated, timemodified, trustbitmask) 
					VALUES('manual', 1, 1, '$row->username', '".md5($row->password)."', 
					'$row->nombre', '$row->ap_paterno $row->ap_materno', '$row->correo', 0, 'México', 'MX', 'es_mx', '99', 
					0, 0, 0, 0, 1,
					1, 0, 2, 1, 1,
					0, '$time', '$time', 0)";
			//$query_usr_moodle = "SELECT id FROM mdl_user WHERE email = '$row->correo'";
			//$query_usr_moodle = "SELECT id FROM mdl_user WHERE email = 'drjp_bono@hotmail.com'";
			$result_usr_moodle = $this->db->query($query_usr_moodle);
			echo $query_usr_moodle."<br/>";
			$id_usr_moodle = $this->db->insert_id();
			//$id_usr_moodle = $result_usr_moodle->row();
			//$id_usr_moodle = $id_usr_moodle->id;
			
			//$query_actualizar_usr_moodle = "UPDATE mdl_user SET firstname = '$row->nombre', lastname = '".trim($row->ap_paterno." ".$row->ap_materno)."', username = '$row->username', password = '".md5($row->password)."' WHERE id = $id_usr_moodle";
			//$this->db->query($query_actualizar_usr_moodle);
			//echo $query_actualizar_usr_moodle."<br/>";
			
			$query_curso = "SELECT id FROM mdl_course WHERE shortname = 'inr'";
			$result_curso = $this->db->query($query_curso);
			$id_curso = $result_curso->row();
			//$id_curso = new stdClass();
			//$id_curso->id = 2;
			
			$query_contexto = "SELECT id FROM mdl_context WHERE instanceid = '$id_curso->id' AND contextlevel = 50";
			$result_contexto = $this->db->query($query_contexto);
			$id_contexto = $result_contexto->row();
			
			$query_enrol = "SELECT id FROM mdl_enrol WHERE courseid = '$id_curso->id' AND enrol = 'manual'";
			$result_enrol = $this->db->query($query_enrol);
			$id_enrol = $result_enrol->row();
			
			$query_matricular = "INSERT INTO mdl_user_enrolments(status, enrolid, userid, timestart, timeend, 
					modifierid, timecreated, timemodified) VALUES(0, $id_enrol->id, $id_usr_moodle, '$time', 0,
					2, '$time', '$time')";
			$this->db->query($query_matricular);
			echo $query_matricular."<br/>";
			
			$query_role = "INSERT INTO mdl_role_assignments(roleid, contextid, userid, timemodified, modifierid, 
					component, itemid, sortorder) VALUES(5, $id_contexto->id, $id_usr_moodle, '$time', 2, 
					'', 0, 0)";
			$this->db->query($query_role);
			echo $query_role."<br/>";
			
			$this->db = $this->load->database('default', TRUE);
			
			$query_estatus = "UPDATE imss_pre SET estatus = 1 WHERE id_usuario = $row->id_usuario";
			$this->db->query($query_estatus);
			echo $query_estatus."<br/><br/>";
		}
	}
}
?>