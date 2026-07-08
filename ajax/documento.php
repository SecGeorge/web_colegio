<?php
require_once "../modelos/Documento.php";
if (strlen(session_id())<1)
	session_start();

$documento = new Documento();

$id=isset($_POST["iddocumento"])? limpiarCadena($_POST["iddocumento"]):"";
$folder_id=isset($_POST["idfolder"])? limpiarCadena($_POST["idfolder"]):"";
$title=isset($_POST["titulo"])? limpiarCadena($_POST["titulo"]):"";

$permitidos=array("pdf","doc","docx","ppt","pptx","xls","xlsx","txt","jpg","jpeg","png","gif","zip","rar");

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if (empty($id)) {
			if (!isset($_FILES['archivo']) || !is_uploaded_file($_FILES['archivo']['tmp_name'])) {
				echo "Debe seleccionar un archivo";
				break;
			}
			$partes=explode(".", $_FILES["archivo"]["name"]);
			$ext=strtolower(end($partes));
			if (!in_array($ext, $permitidos)) {
				echo "Tipo de archivo no permitido";
				break;
			}
			$originalname=$_FILES["archivo"]["name"];
			$filename=round(microtime(true)).'.'.$ext;
			if (move_uploaded_file($_FILES["archivo"]["tmp_name"], "../files/documentos/".$filename)) {
				$rspta=$documento->insertar($folder_id,$title,$filename,$originalname);
				echo $rspta ? "Documento subido correctamente" : "No se pudo registrar el documento";
			}else{
				echo "No se pudo guardar el archivo";
			}
		}else{
			$rspta=$documento->editar($id,$title);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
		break;

	case 'desactivar':
		$rspta=$documento->desactivar($id);
		echo $rspta ? "Documento oculto para los alumnos" : "No se pudo ocultar el documento";
		break;

	case 'activar':
		$rspta=$documento->activar($id);
		echo $rspta ? "Documento visible para los alumnos" : "No se pudo mostrar el documento";
		break;

	case 'mostrar':
		$rspta=$documento->mostrar($id);
		echo json_encode($rspta);
		break;

	case 'eliminar':
		$reg=$documento->mostrar($id);
		if ($reg && isset($reg['filename']) && file_exists("../files/documentos/".$reg['filename'])) {
			unlink("../files/documentos/".$reg['filename']);
		}
		$rspta=$documento->eliminar($id);
		echo $rspta ? "Documento eliminado correctamente" : "No se pudo eliminar el documento";
		break;

	case 'listar':
		$folder_id=$_REQUEST["idfolder"];
		$rspta=$documento->listar($folder_id);
		$data=Array();
		while ($reg=$rspta->fetch_object()) {
			if ($reg->is_active==1) {
				$estado='<span class="label label-success">VISIBLE</span>';
				$toggle='<button class="btn btn-default btn-xs" onclick="desactivar('.$reg->id.')" title="Ocultar"><i class="fa fa-eye-slash"></i></button>';
			}else{
				$estado='<span class="label label-default">OCULTO</span>';
				$toggle='<button class="btn btn-success btn-xs" onclick="activar('.$reg->id.')" title="Mostrar"><i class="fa fa-eye"></i></button>';
			}
			$archivo='<a href="../files/documentos/'.$reg->filename.'" target="_blank" download="'.$reg->originalname.'"><i class="fa fa-download"></i> '.$reg->originalname.'</a>';
			$opciones='<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id.')" title="Editar"><i class="fa fa-pencil"></i></button> '.$toggle.' '.
				'<button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->id.')" title="Eliminar"><i class="fa fa-trash"></i></button>';
			$data[]=array(
				"0"=>$opciones,
				"1"=>$reg->title,
				"2"=>$archivo,
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
