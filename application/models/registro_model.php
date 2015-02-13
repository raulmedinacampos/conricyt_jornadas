<?php
class Registro_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
	}
	
	public function getAuxSchedule() {
		$this->db->select('id, institucion, fecha');
		$this->db->from('aux_tabla');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getAuxScheduleByID($id) {
		$this->db->select('id, institucion, fecha, tipo');
		$this->db->from('aux_tabla');
		$this->db->where('id', $id);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getStates() {
		$this->db->select('id_entidad, entidad');
		$this->db->from('entidad');
		$this->db->where('estatus', 1);
		$this->db->order_by('entidad');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getProfiles() {
		$sql = "SELECT id_perfil, perfil FROM cat_perfil WHERE estatus = 1 ORDER BY FIELD(perfil, 'Otro'), perfil ASC";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getRegions() {
		$this->db->select('id_region, region');
		$this->db->from('region');
		$this->db->where('estatus', 1);
		$this->db->order_by('orden');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getEventsByRegion() {
		$this->db->select('e.id_evento, e.evento, ue.ubicacion, e.descripcion, e.inicio, e.fin, e.ubicacion_evento, i.tipo_institucion, en.region, e.estatus');
		$this->db->from('evento e');
		$this->db->join('ubicacion_evento ue', 'e.ubicacion_evento = ue.id_ubicacion_evento');
		$this->db->join('institucion i', 'ue.institucion = i.id_institucion');
		$this->db->join('entidad en', 'ue.entidad = en.id_entidad');
		$this->db->where('e.estatus >=', 1);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getInstitutions() {
		$this->db->select('id_institucion, institucion, siglas');
		$this->db->from('institucion');
		$this->db->where('estatus', 1);
		$this->db->order_by('institucion');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getInstitutionsType() {
		$this->db->select('e.region, cti.id_tipo_institucion, cti.tipo_institucion');
		$this->db->from('ubicacion_evento ue');
		$this->db->join('evento ev', 'ue.id_ubicacion_evento = ev.ubicacion_evento');
		$this->db->join('institucion i', 'ue.institucion = i.id_institucion');
		$this->db->join('entidad e', 'ue.entidad = e.id_entidad');
		$this->db->join('cat_tipo_institucion cti', 'i.tipo_institucion = cti.id_tipo_institucion');
		$this->db->where('ev.estatus >=', 1);
		$this->db->group_by('e.region, cti.id_tipo_institucion');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getFolio() {
		$str = "SELECT u.folio FROM usuario u JOIN usuario_programa up ON u.id_usuario = up.usuario JOIN programa p ON up.programa = p.id_programa WHERE up.estatus > 0 AND p.estatus = 1 AND p.programa = 'Jornadas de capacitación 2015' ORDER BY id_usuario DESC LIMIT 1";
		$query = $this->db->query($str);
			
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getUserByMail($mail) {
		$this->db->select('id_usuario, nombre, ap_paterno, ap_materno, sexo, institucion, entidad, id_perfil, telefono');
		$this->db->from('usuario');
		$this->db->where('estatus', 1);
		$this->db->where('correo', $mail);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getResourcesByEvent($id) {
		$this->db->select('r.id_recurso, r.recurso');
		$this->db->from('recurso r');
		$this->db->join('evento_recurso er', 'r.id_recurso = er.recurso');
		$this->db->where('r.estatus', 1);
		$this->db->where('er.evento', $id);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getRegisterByUser($usr) {
		$this->db->select('u.nombre, u.ap_paterno, u.ap_materno, u.correo, u.institucion, up.fecha_inscripcion');
		$this->db->from('usuario u');
		$this->db->join('usuario_programa up', 'u.id_usuario = up.usuario');
		$this->db->where('u.id_usuario', $usr);
		$this->db->where('up.programa', 2);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row_array();
		}
	}
	
	public function getEventByUser($usr) {
		$this->db->select('e.evento, e.descripcion');
		$this->db->from('evento e');
		$this->db->join('usuario_evento ue', 'e.id_evento = ue.evento');
		$this->db->join('ubicacion_evento ub', 'e.ubicacion_evento = ub.id_ubicacion_evento');
		$this->db->where('ue.usuario', $usr);
		$this->db->where('ub.programa', 2);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getCoursesByUser($usr) {
		$this->db->select('r.recurso');
		$this->db->from('usuario_recurso ur');
		$this->db->join('recurso r', 'ur.recurso = r.id_recurso');
		$this->db->where('ur.usuario', $usr);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function getShortnameByID($id) {
		$this->db->select('id_evento, abreviatura');
		$this->db->from('evento');
		$this->db->where('id_evento', $id);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getMoodleCourse($shortname) {
		$this->db = $this->load->database('moodle', TRUE);
		$this->db->select('id');
		$this->db->from('mdl_course');
		$this->db->where('shortname', $shortname);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getMoodleContext($instance) {
		$this->db = $this->load->database('moodle', TRUE);
		$this->db->select('id');
		$this->db->from('mdl_context');
		$this->db->where('instanceid', $instance);
		$this->db->where('contextlevel', 50);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getMoodleEnrol($course) {
		$this->db = $this->load->database('moodle', TRUE);
		$this->db->select('id');
		$this->db->from('mdl_enrol');
		$this->db->where('courseid', $course);
		$this->db->where('enrol', 'manual');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function checkUserInProgram($mail) {
		$this->db = $this->load->database('default', TRUE);
		$this->db->select('u.id_usuario');
		$this->db->from('usuario u');
		$this->db->join('usuario_programa up', 'u.id_usuario = up.usuario');
		$this->db->where('u.correo', $mail);
		$this->db->where('up.programa', 2);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function insertUser($data) {
		$this->db = $this->load->database('default', TRUE);
		$usr = '';
		if($this->db->insert('usuario', $data)) {
			$usr = $this->db->insert_id();
			return $usr;
		}
	}
	
	public function updateUser($id, $data) {
		$this->db = $this->load->database('default', TRUE);
		$this->db->where('id_usuario', $id);
		$this->db->update('usuario', $data);
	}
	
	public function insertUserProgram($data) {
		$this->db = $this->load->database('default', TRUE);
		$this->db->insert('usuario_programa', $data);
	}

	public function insertUserEvent($usuario, $evento) {
		$this->db = $this->load->database('default', TRUE);
		$this->db->set('usuario', $usuario);
		$this->db->set('evento', $evento);
		$this->db->insert('usuario_evento');
	}
	
	public function insertUserCourse($usuario, $recurso) {
		$this->db = $this->load->database('default', TRUE);
		$this->db->set('usuario', $usuario);
		$this->db->set('recurso', $recurso);
		
		if($this->db->insert('usuario_recurso')) {
			return true;
		}
	}
	
	public function checkMoodleUserExists($mail) {
		$this->db = $this->load->database('moodle', TRUE);
		$this->db->select('id');
		$this->db->from('mdl_user');
		$this->db->where('email', $mail);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function updateMoodleUser($id, $data) {
		$this->db = $this->load->database('moodle', TRUE);
		$this->db->where('id', $id);
		$this->db->update('mdl_user', $data);
	}
	
	public function insertMoodleUser($data) {
		$this->db = $this->load->database('moodle', TRUE);
		$usr = '';
		if($this->db->insert('mdl_user', $data)) {
			$usr = $this->db->insert_id();
			return $usr;
		}
	}
	
	public function insertUserEnrollments($data) {
		$this->db = $this->load->database('moodle', TRUE);
		if($this->db->insert('mdl_user_enrolments', $data)) {
			return true;
		}
	}
	
	public function insertRoleAssignments($data) {
		$this->db = $this->load->database('moodle', TRUE);
		if($this->db->insert('mdl_role_assignments', $data)) {
			return true;
		}
	}
}
?>