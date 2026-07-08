<?php
require "../config/Conexion.php";
class Folder{

	public function __construct(){

	}

	public function insertar($name,$block_id,$user_id){
		$sql="INSERT INTO folder (name,block_id,user_id,is_active,created_at) VALUES ('$name','$block_id','$user_id','1',NOW())";
		return ejecutarConsulta($sql);
	}

	public function editar($id,$name){
		$sql="UPDATE folder SET name='$name' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id){
		$sql="UPDATE folder SET is_active='0' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function activar($id){
		$sql="UPDATE folder SET is_active='1' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($id){
		$sql="SELECT * FROM folder WHERE id='$id'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listar($block_id){
		$sql="SELECT f.id,f.name,f.is_active,(SELECT COUNT(*) FROM document d WHERE d.folder_id=f.id) AS docs FROM folder f WHERE f.block_id='$block_id' ORDER BY f.id DESC";
		return ejecutarConsulta($sql);
	}

	public function cabecera($id){
		$sql="SELECT f.id,f.name,f.block_id,f.is_active,b.name AS curso,b.team_id,t.nombre AS grado FROM folder f INNER JOIN block b ON f.block_id=b.id INNER JOIN team t ON b.team_id=t.idgrupo WHERE f.id='$id'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarActivas($block_id){
		$sql="SELECT id,name FROM folder WHERE block_id='$block_id' AND is_active='1' ORDER BY name ASC";
		return ejecutarConsulta($sql);
	}
}
?>
