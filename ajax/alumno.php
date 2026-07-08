<?php
require_once "../modelos/AlumnoAcceso.php";
if (strlen(session_id())<1)
	session_start();

$acceso = new AlumnoAcceso();

switch ($_GET["op"]) {
	case 'verificar':
		$login=limpiarCadena($_POST['logina']);
		$clave=hash("SHA256", $_POST['clavea']);
		$rspta=$acceso->verificar($login,$clave);
		$fetch=$rspta->fetch_object();
		if (isset($fetch)) {
			$_SESSION['alumno_id']=$fetch->id;
			$_SESSION['alumno_nombre']=$fetch->name.' '.$fetch->lastname;
			$_SESSION['alumno_imagen']=$fetch->image;
			$_SESSION['alumno_team']=$fetch->team_id;
			$_SESSION['alumno_grado']=$fetch->grado;
		}
		echo json_encode($fetch);
		break;

	case 'salir':
		unset($_SESSION['alumno_id']);
		unset($_SESSION['alumno_nombre']);
		unset($_SESSION['alumno_imagen']);
		unset($_SESSION['alumno_team']);
		unset($_SESSION['alumno_grado']);
		header("Location: ../vistas/login_alumno.html");
		break;
}
?>
