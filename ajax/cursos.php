<?php
require_once "../modelos/Cursos.php";
if (strlen(session_id())<1)
	session_start();

$cursos=new Cursos();

$id=isset($_POST["idcurso"])? limpiarCadena($_POST["idcurso"]):"";
$name=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$team_id=isset($_POST["idgrupo"])? limpiarCadena($_POST["idgrupo"]):"";
$idprofesor=isset($_POST["idprofesor"])? limpiarCadena($_POST["idprofesor"]):"";

$esAdmin = isset($_SESSION['acceso']) && $_SESSION['acceso']==1;
$uid = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : 0;

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($id)) {
		$rspta=$cursos->insertar($name,$team_id,$idprofesor);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$cursos->editar($id,$name,$idprofesor);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;

	case 'asignar':
		$rspta=$cursos->asignar($id,$idprofesor);
		echo $rspta ? "Profesor asignado correctamente" : "No se pudo asignar el profesor";
		break;

	case 'desactivar':
		$rspta=$cursos->desactivar($id);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$cursos->activar($id);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;

	case 'mostrar':
		$rspta=$cursos->mostrar($id);
		echo json_encode($rspta);
		break;

    case 'listar':
        $team_id=$_REQUEST["idgrupo"];
		$rspta = $esAdmin ? $cursos->listar($team_id) : $cursos->listarPorProfesor($team_id,$uid);
		$data=Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>$reg->name
              );
		}
		$results=array(
             "sEcho"=>1,
             "iTotalRecords"=>count($data),
             "iTotalDisplayRecords"=>count($data),
             "aaData"=>$data);
		echo json_encode($results);
		break;

	case 'listarAdmin':
		$team_id=$_REQUEST["idgrupo"];
		$rspta=$cursos->listarAdmin($team_id);
		$data=Array();
		while ($reg=$rspta->fetch_object()) {
			$profesor = $reg->profesor ? '<span class="label label-info" style="font-size:12px;">'.$reg->profesor.'</span>' : '<span class="label label-default" style="font-size:12px;">Sin asignar</span>';
			$data[]=array(
				"0"=>$reg->name,
				"1"=>$profesor,
				"2"=>'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i> Editar</button>'
			);
		}
		$results=array("sEcho"=>1,"iTotalRecords"=>count($data),"iTotalDisplayRecords"=>count($data),"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'selectCursos':
        $team_id=$_REQUEST["idgrupo"];
		$rspta = $esAdmin ? $cursos->listar($team_id) : $cursos->listarPorProfesor($team_id,$uid);
		echo '<option value="0">seleccione...</option>';
		while ($reg = $rspta->fetch_object()) {
			echo '<option value='.$reg->id.'>'.$reg->name.'</option>';
		}
		break;

	case 'selectProfesores':
		require_once "../modelos/Usuario.php";
		$u=new Usuario();
		$rspta=$u->listarProfesores();
		echo '<option value="0">-- Sin asignar --</option>';
		while ($reg=$rspta->fetch_object()) {
			echo '<option value="'.$reg->idusuario.'">'.$reg->nombre.'</option>';
		}
		break;
}
?>
