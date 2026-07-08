<?php
require_once "../modelos/Asistencia.php";
if (strlen(session_id())<1)
	session_start();
$asistencia=new Asistencia();

$id=isset($_POST["idasistencia"])? limpiarCadena($_POST["idasistencia"]):"";
$kind_id=isset($_POST["tipo_asistencia"])? limpiarCadena($_POST["tipo_asistencia"]):"";
$date_at=isset($_POST["fecha_asistencia"])? limpiarCadena($_POST["fecha_asistencia"]):"";
$alumn_id=isset($_POST["alumn_id"])? limpiarCadena($_POST["alumn_id"]):"";
$team_id=isset($_POST["idgrupo"])? limpiarCadena($_POST["idgrupo"]):"";
$user_id=$_SESSION["idusuario"];

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($id)) {
		$rspta=$asistencia->insertar($kind_id,$date_at,$alumn_id,$team_id);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$asistencia->editar($id,$kind_id,$date_at,$alumn_id,$team_id);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;

	case 'desactivar':
		$rspta=$asistencia->desactivar($id);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;

	case 'activar':
		$rspta=$asistencia->activar($id);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;

	case 'mostrar':
		$rspta=$asistencia->mostrar($id);
		echo json_encode($rspta);
	break;
	case 'verificar':

		$rspta=$asistencia->verificar($date_at,$alumn_id,$team_id);
		echo json_encode($rspta);
	break;
	case 'marcar':
		$existe=$asistencia->verificar($date_at,$alumn_id,$team_id);
		if ($existe) {
			$rspta=$asistencia->editar($existe['id'],$kind_id,$date_at,$alumn_id,$team_id);
		} else {
			$rspta=$asistencia->insertar($kind_id,$date_at,$alumn_id,$team_id);
		}
		echo $rspta ? "ok" : "error";
	break;
	case 'cerrar':
		require_once "../modelos/Alumnos.php";
		$alumnos=new Alumnos();
		$rs=$alumnos->listar($user_id,$team_id);
		$p=0;$t=0;$f=0;
		while ($a=$rs->fetch_object()) {
			$ex=$asistencia->verificar($date_at,$a->id,$team_id);
			if (!$ex) {
				$asistencia->insertar(3,$date_at,$a->id,$team_id);
				$f++;
			} else {
				if ($ex['kind_id']==1) { $p++; }
				elseif ($ex['kind_id']==2) { $t++; }
				else { $f++; }
			}
		}
		echo json_encode(array("presentes"=>$p,"tardes"=>$t,"faltas"=>$f));
	break;
	case 'historial':
		$team_id=$_REQUEST["idgrupo"];
		$rspta=$asistencia->historial($team_id);
		$data=Array();
		while ($r=$rspta->fetch_object()) {
			$fecha=date('d/m/Y', strtotime($r->date_at));
			$badge='display:inline-block;min-width:34px;text-align:center;padding:3px 10px;border-radius:11px;font-weight:700;font-size:12px;';
			$data[]=array(
				"0"=>'<b>'.$fecha.'</b>',
				"1"=>'<span style="'.$badge.'background:#e4f4ea;color:#18a558;">'.$r->presentes.'</span>',
				"2"=>'<span style="'.$badge.'background:#fff3d6;color:#c8890b;">'.$r->tardes.'</span>',
				"3"=>'<span style="'.$badge.'background:#fdecec;color:#e0453c;">'.$r->faltas.'</span>',
				"4"=>$r->total,
				"5"=>'<button type="button" class="btn btn-info btn-xs" onclick="verFecha(\''.$r->date_at.'\')"><i class="fa fa-eye"></i> Ver</button>'
			);
		}
		$results=array("sEcho"=>1,"iTotalRecords"=>count($data),"iTotalDisplayRecords"=>count($data),"aaData"=>$data);
		echo json_encode($results);
	break;

    case 'listar':
    			require_once "../modelos/Alumnos.php";
			$alumnos=new Alumnos();
        $team_id=$_REQUEST["idgrupo"];
		$rspta=$alumnos->listar($user_id,$team_id);
		$data=Array();
		$hoy=isset($_REQUEST["fecha"])? limpiarCadena($_REQUEST["fecha"]) : date('Y-m-d');

		while ($reg=$rspta->fetch_object()) {
			$asis=$asistencia->verificar($hoy,$reg->id,$team_id);
			$actual=$asis?intval($asis['kind_id']):0;
			$col4='<div class="asis-group">'.
				'<button type="button" class="asis-btn asis-p'.($actual==1?' active':'').'" onclick="marcar(this,'.$reg->id.',1)"><i class="fa fa-check"></i> Presente</button>'.
				'<button type="button" class="asis-btn asis-t'.($actual==2?' active':'').'" onclick="marcar(this,'.$reg->id.',2)"><i class="fa fa-clock-o"></i> Tarde</button>'.
				'<button type="button" class="asis-btn asis-f'.($actual==3?' active':'').'" onclick="marcar(this,'.$reg->id.',3)"><i class="fa fa-times"></i> Falta</button>'.
				'</div>';
			$data[]=array(
            "0"=>"<img src='../files/articulos/".$reg->image."' class='img-circle' style='width:38px;height:38px;object-fit:cover;border:1px solid #e3e3e3;'>",
            "1"=>$reg->name,
            "2"=>$reg->lastname,
            "3"=>$reg->phone,
            "4"=>$col4,
            "5"=>($reg->is_active)?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-warning btn-xs" onclick="mostrar_precios('.$reg->id.')">P</i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->id.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i></button>'.'<button class="btn btn-warning btn-xs" onclick="mostrar_precios('.$reg->id.')">P</i></button>'.' '.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->id.')"><i class="fa fa-check"></i></button>'
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