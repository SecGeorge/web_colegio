<?php
require "../config/Conexion.php";
class AlumnoAcceso{

	public function __construct(){

	}

	public function verificar($login,$clave){
		$sql="SELECT a.id,a.name,a.lastname,a.image,at.team_id,t.nombre AS grado FROM alumn a INNER JOIN alumn_team at ON a.id=at.alumn_id INNER JOIN team t ON at.team_id=t.idgrupo WHERE a.login='$login' AND a.clave='$clave' AND a.is_active='1' LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function cursos($team_id){
		$sql="SELECT id,name FROM block WHERE team_id='$team_id' ORDER BY name ASC";
		return ejecutarConsulta($sql);
	}

	public function carpetas($block_id){
		$sql="SELECT id,name FROM folder WHERE block_id='$block_id' AND is_active='1' ORDER BY name ASC";
		return ejecutarConsulta($sql);
	}

	public function documentos($folder_id){
		$sql="SELECT id,title,filename,originalname FROM document WHERE folder_id='$folder_id' AND is_active='1' ORDER BY id DESC";
		return ejecutarConsulta($sql);
	}

	public function profesor($team_id){
		$sql="SELECT u.nombre,u.cargo,u.imagen,u.email FROM team t INNER JOIN usuario u ON t.idusuario=u.idusuario WHERE t.idgrupo='$team_id'";
		return ejecutarConsultaSimpleFila($sql);
	}
}
?>
