<?php
require_once "../modelos/Folder.php";
if (strlen(session_id())<1)
	session_start();

$folder = new Folder();

$id=isset($_POST["idfolder"])? limpiarCadena($_POST["idfolder"]):"";
$name=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$block_id=isset($_POST["idcurso"])? limpiarCadena($_POST["idcurso"]):"";
$user_id=isset($_SESSION["idusuario"])? $_SESSION["idusuario"]:0;

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if (empty($block_id) || $block_id=="0") {
			echo "Debe seleccionar un curso";
			break;
		}
		if (empty($id)) {
			$rspta=$folder->insertar($name,$block_id,$user_id);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
		}else{
			$rspta=$folder->editar($id,$name);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
		break;

	case 'desactivar':
		$rspta=$folder->desactivar($id);
		echo $rspta ? "Carpeta oculta para los alumnos" : "No se pudo ocultar la carpeta";
		break;

	case 'activar':
		$rspta=$folder->activar($id);
		echo $rspta ? "Carpeta visible para los alumnos" : "No se pudo mostrar la carpeta";
		break;

	case 'mostrar':
		$rspta=$folder->mostrar($id);
		echo json_encode($rspta);
		break;

	case 'listar':
		$block_id=$_REQUEST["idcurso"];
		$rspta=$folder->listar($block_id);
		$data=Array();
		while ($reg=$rspta->fetch_object()) {
			if ($reg->is_active==1) {
				$estado='<span class="label label-success">VISIBLE</span>';
				$toggle='<button class="btn btn-default btn-xs" onclick="desactivar('.$reg->id.')" title="Ocultar"><i class="fa fa-eye-slash"></i></button>';
			}else{
				$estado='<span class="label label-default">OCULTA</span>';
				$toggle='<button class="btn btn-success btn-xs" onclick="activar('.$reg->id.')" title="Mostrar"><i class="fa fa-eye"></i></button>';
			}
			$opciones='<a href="documentos.php?idfolder='.$reg->id.'" class="btn btn-info btn-xs" title="Documentos"><i class="fa fa-folder-open"></i></a> '.
				'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id.')" title="Editar"><i class="fa fa-pencil"></i></button> '.$toggle;
			$data[]=array(
				"0"=>$opciones,
				"1"=>$reg->name,
				"2"=>$reg->docs,
				"3"=>$estado
			);
		}
		$results=array(
			"sEcho"=>1,
			"iTotalRecords"=>count($data),
			"iTotalDisplayRecords"=>count($data),
			"aaData"=>$data);
		echo json_encode($results);
		break;
}
?>
