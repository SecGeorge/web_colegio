<?php
require "../config/Conexion.php";
class Documento{

	public function __construct(){

	}

	public function insertar($folder_id,$title,$filename,$originalname){
		$sql="INSERT INTO document (folder_id,title,filename,originalname,is_active,created_at) VALUES ('$folder_id','$title','$filename','$originalname','1',NOW())";
		return ejecutarConsulta($sql);
	}

	public function editar($id,$title){
		$sql="UPDATE document SET title='$title' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id){
		$sql="UPDATE document SET is_active='0' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function activar($id){
		$sql="UPDATE document SET is_active='1' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($id){
		$sql="SELECT * FROM document WHERE id='$id'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function eliminar($id){
		$sql="DELETE FROM document WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function listar($folder_id){
		$sql="SELECT id,title,filename,originalname,is_active FROM document WHERE folder_id='$folder_id' ORDER BY id DESC";
		return ejecutarConsulta($sql);
	}

	public function listarActivos($folder_id){
		$sql="SELECT id,title,filename,originalname FROM document WHERE folder_id='$folder_id' AND is_active='1' ORDER BY id DESC";
		return ejecutarConsulta($sql);
	}
}
?>
