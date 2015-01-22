<?php
	class Registro extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('registro_model', 'registro', TRUE);
			$this->load->helper('form');
		}
		
		public function index() {
			$data['instituciones'] = $this->registro->getInstitutions();
			$data['entidades'] = $this->registro->getStates();
			$data['perfiles'] = $this->registro->getProfiles();
			$data['regiones'] = $this->registro->getRegions();
			$data['tipo_instituciones'] = $this->registro->getInstitutionsType();
			$data['datos'] = $this->registro->getEventsByRegion();
			
			$this->load->view('header');
			$this->load->view('formulario', $data);
			$this->load->view('footer');
		}
		
		private function formatearFecha($fecha) {
			$meses = array("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
			list($anio, $mes, $dia) = explode("-", $fecha);
			$str_fecha = "México D.F. a ".$dia." de ".$meses[(int)$mes]." de ".$anio;
			return $str_fecha;
		}
		
		private function strtoupper_utf8($cadena) {
			$convertir_de = array(
				"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
				"v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë","e", "ì", "í", "î", "ï",
				"ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "?", "?", "?", "?", "?", "?", "?", "?",
				"?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?",
				"?", "?", "?", "?"
			);
			$convertir_a = array(
				"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
				"V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë","E", "Ì", "Í", "Î", "Ï",
				"Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "?", "?", "?", "?", "?", "?", "?", "?",
				"?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?",
				"?", "?", "?", "?"
			);
			return str_replace($convertir_de, $convertir_a, $cadena);
		}
		
		private function generarFolio($prefijo = "JC2015") {
			$anterior = $this->registro->getFolio();
			if(!$anterior) {
				$folio = $prefijo." - "."00001";
			} else {
				$anterior = $anterior->folio;
				list($pre, $consecutivo) = explode("-", $anterior);
				$consecutivo = (int) $consecutivo;
				$consecutivo++;
				$folio = $prefijo." - ".str_pad($consecutivo, 5, "0", STR_PAD_LEFT);
			}
			return $folio;
		}
		
		private function generarPassword($longitud = 8) {
			$caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password = substr(str_shuffle($caracteres), 0, $longitud); 
			return $password;
		}
		
		public function listarRecursos() {
			$recursos = "";
			$evento = $this->input->post('evento');
			
			if($recursos = $this->registro->getResourcesByEvent($evento)) {
				$recursos = $recursos->result();
			}
			
			echo json_encode($recursos);
		}
		
		public function consultarUsuario() {
			$correo = addslashes($this->input->post('correo'));			
			$usuario = $this->registro->getUserByMail($correo);
			
			if($usuario) {
				echo json_encode($usuario);
			}
		}
		
		private function crearComprobante($id_usuario) {
			$data = $this->registro->getRegisterByUser($id_usuario);
			$evento = $this->registro->getEventByUser($id_usuario);
			$capacitaciones = $this->registro->getCoursesByUser($id_usuario);
			
			list($fecha, $hora) = explode(" ", $data['fecha_inscripcion']);
			$fecha = $this->formatearFecha($fecha);
			$nombre = utf8_decode($data['nombre']);
			$ap_paterno = utf8_decode($data['ap_paterno']);
			$ap_materno = utf8_decode($data['ap_materno']);
			$nombre_completo = $this->strtoupper_utf8(trim($nombre." ".$ap_paterno." ".$ap_materno));
			$institucion = $this->strtoupper_utf8(utf8_decode(trim($data['institucion'])));
			
			if(strpos($evento->descripcion, ' y ') === true) {
				$periodo = 'los días '.$evento->descripcion;
			} else if(strpos($evento->descripcion, ' al ') === true) {
				$periodo = 'del '.$evento->descripcion;
			} else {
				$periodo = 'el día '.$evento->descripcion;
			}
		
			if(strpos($evento->evento, 'Universidad') !== false) {
				$artInstitucion = 'la';
			} else {
				$artInstitucion = 'el';
			}

			$header = '<p class="header"><img src="'.base_url().'images/header_pdf.jpg" /></p>';
			$footer = '<p class="footer"><strong>Oficina del Consorcio Nacional de Recursos de Información Científica y Tecnológica</strong><br />Av. Insurgentes Sur 1582, Col. Crédito Constructor, Del. Benito Juárez, C.P. 03940 México D.F. – Tel: 5322 7700 ext. 4020 a la 4026</p>';
			$html = '<div class="contenido">';
			$html .= '<p class="titulo2">Consorcio Nacional de Recursos de Información Científica y Tecnológica</p>';
			$html .= '<p class="fecha">'.$fecha.'</p>';
			$html .= '<p class="remitente">ESTIMADO(A) '.$nombre_completo.'<br />'.$institucion.'<br />P r e s e n t e:</p>';
			$html .= '<p>Tu inscripción a las Jornadas de Capacitación CONRICYT 2015 se ha completado con éxito. Durante las Capacitaciones deberás ingresar al correo con el que te registraste para tener acceso a las evaluaciones.</p>';
			$html .= '<p>Es necesario que realices las evaluaciones de las capacitaciones a las que asististe para obtener tu constancia de asistencia, la cual podrás descargar hasta el 31 de diciembre del presente año en el sitio web de las Jornadas de Capacitación.</p>';
			$html .= '<h4>http://jornadascapacitacion.conricyt.mx/</h4>';
			$html .= '<p><strong>Sede: '.utf8_decode($evento->evento).'</strong></p>';
			$html .= '<p><strong>Capacitaciones seleccionadas:</strong></p>';
			$html .= '<ul>';
			
			if($capacitaciones) {
				foreach($capacitaciones->result() as $capacitacion) {
					$html .= '<li>'.utf8_decode($capacitacion->recurso).'</li>';
				}
			} else {
				$html .= '<p>No hay capacitaciones seleccionadas</p>';
			}
			
			$html .= '</ul>';
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, base_url('css/pdf.css'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$stylesheet = curl_exec($ch);
			curl_close($ch);
				
			$this->load->library('pdf');
			$pdf = $this->pdf->load("c", "Letter", "", "", 20, 20, 45, 30, 10, 10);
			$pdf->SetHTMLHeader(utf8_encode($header));
			$pdf->SetHTMLFooter(utf8_encode($footer));
			$pdf->WriteHTML($stylesheet, 1);
			$pdf->WriteHTML(utf8_encode($html));
			
			$pdf->AddPage();
			$html = '<div class="contenido">';
			$html .= '<p class="titulo2">Consorcio Nacional de Recursos de Información Científica y Tecnológica</p>';
			$html .= '<p class="fecha">'.$fecha.'</p>';
			$html .= '<p class="remitente">ESTIMADO(A) '.$nombre_completo.'<br />'.$institucion.'<br />P r e s e n t e:</p>';
			$html .= '<p>El Consorcio Nacional de Recursos de Información Científica y Tecnológica tiene el agrado de invitarle cordialmente a las <em>Jornadas de Capacitación CONRICYT 2015</em>, cuyo objetivo central es fortalecer las habilidades informativas de los estudiantes, académicos, investigadores, bibliotecarios y referencistas, para ampliar, consolidar y facilitar el acceso a la información científica en formatos digitales.</p>';
			$html .= '<p>La Jornada se llevará a cabo '.$periodo.' en '.$artInstitucion.' '.utf8_decode($evento->evento).', cuyo programa de actividades podrás consultarlo en la página http://jornadascapacitacion.conricyt.mx/jornadas</p>';
			$html .= '<p class="firma">A t e n t a m e n t e,</p>';
			$html .= '<blockquote><p><img src="'.base_url('images/firma_pdf.jpg').'" /></p></blockquote>';
			$html .= '<p>Mtra. Margarita Ontiveros y Sánchez de la Barquera<br />Coordinadora General<br />Consorcio Nacional de Recursos de Información Científica y Tecnológica</p>';
			$html .= '</div>';
			$pdf->WriteHTML($stylesheet, 1);
			$pdf->WriteHTML(utf8_encode($html));

			//$pdf->Output();
			$contenido_pdf = $pdf->Output(utf8_encode('Comprobante de registro.pdf'), 'S');
			return $contenido_pdf;
		}
		
		private function enviarCorreo($id_usuario, $correo, $remitente, $asunto, $body) {
			$this->load->library('phpmailer');
				
			$this->phpmailer->IsSMTP();
			$this->phpmailer->SMTPDebug  = 0;
			$this->phpmailer->SMTPAuth   = true;					// activa autenticación
			$this->phpmailer->Host       = "smtp.gmail.com";		// servidor de correo
			//$this->phpmailer->Host       = "74.125.136.108";		// servidor de correo
			$this->phpmailer->Port       = 465;                    // puerto de salida que usa Gmail
			$this->phpmailer->SMTPSecure = 'ssl';					// protocolo de autenticación
			$this->phpmailer->Username   = "conricyt@gmail.com";
			$this->phpmailer->Password   = 'C0nR1c17p1x3l8lu3';
				
			$this->phpmailer->SetFrom('conricyt@gmail.com', 'CONRICyT');
			$this->phpmailer->AddReplyTo('no-replay@conacyt.mx', 'CONRICyT');
			$this->phpmailer->Subject    = utf8_encode($asunto);
			$this->phpmailer->AltBody    = utf8_encode($asunto);
				
			$this->phpmailer->MsgHTML($body);
				
			$this->phpmailer->AddAddress($correo, $remitente);
			
			$data = "";
			
			$this->phpmailer->AddStringAttachment($this->crearComprobante($id_usuario),'comprobante.pdf');
				
			$this->phpmailer->CharSet = 'UTF-8';
				
			if(!$this->phpmailer->Send()) {
				//echo "Error al enviar correo: " . $this->phpmailer->ErrorInfo;
			} else {
				//echo "Correo enviado";
			}
		}
		
		public function registrarDatos() {
			$this->load->library('cadena');
			
			$id_usuario = $this->input->post('hdn_usuario');
			
			$password = $this->generarPassword();
			
			$usuario['nombre'] = addslashes(Cadena::formatearNombre($this->input->post('nombre')));
			$usuario['ap_paterno'] = addslashes(Cadena::formatearNombre($this->input->post('ap_paterno')));
			$usuario['ap_materno'] = addslashes(Cadena::formatearNombre($this->input->post('ap_materno')));
			$usuario['sexo'] = $this->input->post('sexo');
			$usuario['institucion'] = $this->input->post('institucion');
			$aux_institucion = addslashes(Cadena::formatearNombre($this->input->post('otra_institucion')));
			if($aux_institucion) {
				$usuario['institucion'] = $aux_institucion;
			}
			$usuario['entidad'] = $this->input->post('entidad');
			$usuario['id_perfil'] = $this->input->post('perfil');
			$usuario['perfil'] = $this->input->post('otro_perfil');
			$usuario['telefono'] = $this->input->post('telefono');
			$usuario['correo'] = addslashes(strtolower(trim($this->input->post('correo'))));
			$usuario['username'] = $usuario['correo'];
			$usuario['password'] = $password;
			
			// Se valida que el usuario no se haya registrado previamente en este programa
			if($this->registro->checkUserInProgram($usuario['correo'])) {
				echo "duplicado";
				exit();
			}
			
			if($id_usuario) {
				// Actualiza registro
				$usuario['fecha_modificacion'] = date('Y-m-d H:i:s');
				$this->registro->updateUser($id_usuario, $usuario);
			} else {
				// Inserta nuevo usuario
				$usuario['folio'] = $this->generarFolio();
				$id_usuario = $this->registro->insertUser($usuario);
			}
			
			$id_usr_moodle = $this->registro->checkMoodleUserExists($usuario['correo']);
			
			$usrData['username'] = $usuario['correo'];
			$usrData['password'] = md5($password);
			$time = time();
			
			if(!$id_usr_moodle) {
				// Inserta usuario en Moodle
				$usrData['auth'] = 'manual';
				$usrData['confirmed'] = '1';
				$usrData['mnethostid'] = '1';
				$usrData['firstname'] = $usuario['nombre'];
				$usrData['lastname'] = trim($usuario['ap_paterno'].' '.$usuario['ap_materno']);
				$usrData['email'] = $usuario['correo'];
				$usrData['emailstop'] = '0';
				$usrData['city'] = 'México';
				$usrData['country'] = 'MX';
				$usrData['lang'] = 'es_mx';
				$usrData['timezone'] = '99';
				$usrData['firstaccess'] = '0';
				$usrData['lastaccess'] = '0';
				$usrData['lastlogin'] = '0';
				$usrData['currentlogin'] = '0';
				$usrData['descriptionformat'] = '1';
				$usrData['mailformat'] = '1';
				$usrData['maildigest'] = '0';
				$usrData['maildisplay'] = '2';
				$usrData['htmleditor'] = '1';
				$usrData['autosubscribe'] = '1';
				$usrData['trackforums'] = '0';
				$usrData['timecreated'] = $time;
				$usrData['timemodified'] = $time;
				$usrData['trustbitmask'] = '0';
				$id_usr_moodle = $this->registro->insertMoodleUser($usrData);
			} else {
				$id_usr_moodle = $id_usr_moodle->id;
				$this->registro->updateMoodleUser($id_usr_moodle, $usrData);
			}
			
			// Crea el registro del usuario y el programa
			$programa['usuario'] = $id_usuario;
			$programa['programa'] = 2;
			$programa['fecha_inscripcion'] = date('Y-m-d H:i:s');
			$this->registro->insertUserProgram($programa);
			
			// Inserta el registro del usuario y la capacitación
			$evento =  $this->input->post('hdn_evento');
			$this->registro->insertUserEvent($id_usuario, $evento);
			
			// Obtenemos datos de la sede seleccionada
			$datos_curso = $this->registro->getShortnameByID($evento);
			
			// Con los datos anteriores consultamos el curso en Moodle
			$curso_moodle = $this->registro->getMoodleCourse($datos_curso->abreviatura);
			
			// Obtenemos el contexto de Moodle
			$contexto = $this->registro->getMoodleContext($curso_moodle->id);
			
			// Consultamos la matricula registrada
			$enrol = $this->registro->getMoodleEnrol($curso_moodle->id);
			
			// Matricula al usuario
			$userEnrollment['status'] = 0;
			$userEnrollment['enrolid'] = $enrol->id;
			$userEnrollment['userid'] = $id_usr_moodle;
			$userEnrollment['timestart'] = $time;
			$userEnrollment['timeend'] = 0;
			$userEnrollment['modifierid'] = 2;
			$userEnrollment['timecreated'] = $time;
			$userEnrollment['timemodified'] = $time;
			
			$this->registro->insertUserEnrollments($userEnrollment);
			
			// Se asigna el rol
			$roleAssignment['roleid'] = 5;
			$roleAssignment['contextid'] = $contexto->id;
			$roleAssignment['userid'] = $id_usr_moodle;
			$roleAssignment['timemodified'] = $time;
			$roleAssignment['modifierid'] = 2;
			$roleAssignment['component'] = '';
			$roleAssignment['itemid'] = 0;
			$roleAssignment['sortorder'] = 0;
			
			$this->registro->insertRoleAssignments($roleAssignment);
			
			// Inserta los recursos por usuario
			$recursos = $this->input->post('hdn_recursos');
			$arrRecursos = explode("|", $recursos);
			
			foreach($arrRecursos as $val) {
				if($val) {
					$this->registro->insertUserCourse($id_usuario, $val);
				}
			}
			
			$remitente = trim($usuario['nombre']." ".$usuario['ap_paterno']." ".$usuario['ap_materno']);
			$asunto = "Comprobante de Jornadas de Capacitación 2015";
			$body = '<table width="100%" border="1" cellspacing="0" cellpadding="10" border="0" bordercolor="#FFFFFF"><tr><td bgcolor="#005199" align="center"><font size="4" face="Arial" color="#e0e0e0"><strong>Comprobante de registro a las Jornadas de Capacitaci&oacute;n 2015.</strong></font></td></tr></table>';
			$body .= '<br /><br /><p><font size="3" face="Arial" color="#006699"><strong>&iexcl;Hola, '.$remitente.'!</strong></font></p>';
			$body .= '<p><font size="3" face="Arial" color="#006699">&iexcl;Tu registro a las <em>Jornadas de Capacitaci&oacute;n 2015</em> se ha realizado con &eacute;xito!</font></p>';
			$body .= '<p><font size="3" face="Arial" color="#006699">Adjunto encontrar&aacute;s tu comprobante de registro, en el que se te indica la forma de realizar las evaluaciones y as&iacute; obtener la constancia de asistencia a las Jornadas. As&iacute; mismo, se enlistan las capacitaciones que seleccionaste.</p>';
			$body .= '<p><font size="3" face="Arial" color="#FF0000">En caso de alguna duda, por favor comun&iacute;cate al tel&eacute;fono (55) 5322 7700 ext. 4020 a 4026</font></p>';
			$body .= '<table width="100%" border="1" cellspacing="0" cellpadding="10" border="0" bordercolor="#FFFFFF"><tr><td bgcolor="#e0e0e0" align="center"><font size="3" face="Arial" color="#005199"><strong>Consejo Nacional de Ciencia y Tecnolog&iacute;a (CONACYT)</strong></font></td></tr></table>';
			
			$this->enviarCorreo($id_usuario, $usuario['correo'], $remitente, $asunto, $body);
			
			echo "ok";
		}
	}
?>