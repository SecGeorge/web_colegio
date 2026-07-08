<?php
require "../config/Conexion.php";
class Cursos{

	public function __construct(){

	}

	public function insertar($name,$team_id,$idusuario){
		$prof = ($idusuario!=="" && $idusuario!="0") ? "'".$idusuario."'" : "NULL";
		$sql="INSERT INTO block (name,team_id,idusuario) VALUES ('$name','$team_id',$prof)";
		return ejecutarConsulta($sql);
	}

	public function editar($id,$name,$idusuario){
		$prof = ($idusuario!=="" && $idusuario!="0") ? "'".$idusuario."'" : "NULL";
		$sql="UPDATE block SET name='$name', idusuario=$prof WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function asignar($id,$idusuario){
		$prof = ($idusuario!=="" && $idusuario!="0") ? "'".$idusuario."'" : "NULL";
		$sql="UPDATE block SET idusuario=$prof WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id){
		$sql="UPDATE block SET condicion='0' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}
	public function activar($id){
		$sql="UPDATE block SET condicion='1' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($id){
		$sql="SELECT * FROM block WHERE id='$id'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listar($team_id){
		$sql="SELECT id, name,team_id FROM block WHERE team_id='$team_id'";
		return ejecutarConsulta($sql);
	}

	public function listarAdmin($team_id){
		$sql="SELECT b.id,b.name,b.team_id,b.idusuario,u.nombre AS profesor FROM block b LEFT JOIN usuario u ON b.idusuario=u.idusuario WHERE b.team_id='$team_id' ORDER BY b.name";
		return ejecutarConsulta($sql);
	}

	public function listarPorProfesor($team_id,$idusuario){
		$sql="SELECT id,name,team_id FROM block WHERE team_id='$team_id' AND idusuario='$idusuario' ORDER BY name";
		return ejecutarConsulta($sql);
	}

	public function gradosProfesor($idusuario){
		$sql="SELECT DISTINCT t.idgrupo,t.nombre,t.favorito,t.idusuario FROM team t INNER JOIN block b ON b.team_id=t.idgrupo WHERE b.idusuario='$idusuario' ORDER BY t.idgrupo";
		return ejecutarConsulta($sql);
	}

	public function accesoGrado($idusuario,$team_id){
		$sql="SELECT COUNT(*) AS n FROM block WHERE team_id='$team_id' AND idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function verficar_curso($team_id){
		$sql="SELECT id,name, team_id FROM block  WHERE team_id='$team_id'";
		return ejecutarConsulta($sql);
	}

	public function listarc_nota(){
		$sql="SELECT * FROM block";
		return ejecutarConsulta($sql);
	}

	public function select(){
		$sql="SELECT * FROM block WHERE condicion=1";
		return ejecutarConsulta($sql);
	}
}
?>
