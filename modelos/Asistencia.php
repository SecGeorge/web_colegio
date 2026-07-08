<?php

require "../config/Conexion.php";
class Asistencia{

public function __construct(){

}

public function insertar($kind_id,$date_at,$alumn_id,$team_id){
	$sql="INSERT INTO assistance (kind_id,date_at,alumn_id,team_id) VALUES ('$kind_id','$date_at','$alumn_id','$team_id')";
	return ejecutarConsulta($sql);
}

public function editar($id,$kind_id,$date_at,$alumn_id,$team_id){
	$sql="UPDATE assistance SET kind_id='$kind_id',date_at='$date_at',alumn_id='$alumn_id',team_id='$team_id'
	WHERE id='$id'";
	return ejecutarConsulta($sql);
}

public function verificar($date_at,$alumn_id,$team_id){
	$sql="SELECT * FROM assistance WHERE date_at='$date_at' AND alumn_id='$alumn_id' AND team_id='$team_id'";
	return ejecutarConsultaSimpleFila($sql);
}

public function desactivar($id){
	$sql="UPDATE assistance SET condicion='0' WHERE id='$id'";
	return ejecutarConsulta($sql);
}
public function activar($id){
	$sql="UPDATE assistance SET condicion='1' WHERE id='$id'";
	return ejecutarConsulta($sql);
}

public function mostrar($id){
	$sql="SELECT * FROM assistance WHERE id='$id'";
	return ejecutarConsultaSimpleFila($sql);
}

public function listar(){
	$sql="SELECT * FROM assistance";
	return ejecutarConsulta($sql);
}

public function historial($team_id){
	$sql="SELECT date_at, SUM(kind_id=1) AS presentes, SUM(kind_id=2) AS tardes, SUM(kind_id=3) AS faltas, COUNT(*) AS total FROM assistance WHERE team_id='$team_id' GROUP BY date_at ORDER BY date_at DESC";
	return ejecutarConsulta($sql);
}

public function select(){
	$sql="SELECT * FROM assistance WHERE condicion=1";
	return ejecutarConsulta($sql);
}
}

 ?>
