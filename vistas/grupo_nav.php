<?php
$gv_items = array(
  'aula'          => array('aula.php', 'fa-folder-open', 'Aula Virtual'),
  'alumnos'       => array('vista_grupo.php', 'fa-users', 'Alumnos'),
  'asistencia'    => array('asistencia.php', 'fa-check-square-o', 'Asistencia'),
  'conducta'      => array('conducta.php', 'fa-smile-o', 'Conducta'),
  'calificaciones'=> array('calificaciones.php', 'fa-tasks', 'Calificaciones'),
  'cursos'        => array('cursos.php', 'fa-th-large', 'Cursos'),
  'listas'        => array('listasis.php', 'fa-list-alt', 'Listas'),
);
$gv_activo = isset($activo) ? $activo : '';
$gv_gid = isset($idgrupo) ? $idgrupo : (isset($_GET['idgrupo']) ? $_GET['idgrupo'] : '');
$gv_esAdmin = isset($_SESSION['acceso']) && $_SESSION['acceso']==1;
if (!$gv_esAdmin && $gv_gid !== '') {
  require_once "../modelos/Cursos.php";
  $gv_cacc = new Cursos();
  $gv_acc = $gv_cacc->accesoGrado($_SESSION['idusuario'], $gv_gid);
  if (!$gv_acc || $gv_acc['n']==0) {
    header("Location: escritorio.php");
    exit();
  }
}
if (isset($nombre_grupo)) {
  $gv_nombre = $nombre_grupo;
} else {
  $gv_nombre = 'Grupo';
  if ($gv_gid !== '') {
    require_once "../modelos/Grupos.php";
    $gv_tmp = new Grupos();
    $gv_row = $gv_tmp->mostrar_grupo($gv_gid);
    if ($gv_row && ($gv_o = $gv_row->fetch_object())) { $gv_nombre = $gv_o->nombre; }
  }
}
?>
<style>
  .gv-header{margin:14px 0 22px;}
  .gv-banner{background:linear-gradient(120deg,#0f6cbf 0%,#2a93e0 100%);color:#fff;padding:20px 24px;border-radius:12px 12px 0 0;display:flex;align-items:center;gap:16px;box-shadow:0 3px 10px rgba(15,108,191,.25);}
  .gv-banner .gv-ico{width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:24px;flex:0 0 48px;}
  .gv-banner .gv-tt{flex:1 1 auto;min-width:0;}
  .gv-banner h2{margin:0;font-size:22px;font-weight:600;line-height:1.2;}
  .gv-banner small{display:block;opacity:.9;font-size:13px;margin-top:3px;}
  .gv-banner .gv-back{color:#fff;border:1px solid rgba(255,255,255,.5);padding:8px 16px;border-radius:22px;text-decoration:none;font-size:13px;white-space:nowrap;transition:.15s;}
  .gv-banner .gv-back:hover{background:rgba(255,255,255,.18);}
  .gv-nav{list-style:none;margin:0;padding:8px;display:flex;gap:4px;background:#fff;border:1px solid #e3e3e3;border-top:none;border-radius:0 0 12px 12px;overflow-x:auto;box-shadow:0 1px 3px rgba(0,0,0,.05);}
  .gv-nav::-webkit-scrollbar{height:6px;}
  .gv-nav::-webkit-scrollbar-thumb{background:#d0d0d0;border-radius:3px;}
  .gv-nav li a{display:flex;align-items:center;gap:8px;padding:10px 16px;border-radius:8px;color:#4a4a4a;text-decoration:none;font-size:14px;font-weight:500;white-space:nowrap;transition:.15s;}
  .gv-nav li a:hover{background:#eef5fc;color:#0f6cbf;}
  .gv-nav li.active a{background:#0f6cbf;color:#fff;box-shadow:0 2px 6px rgba(15,108,191,.3);}
  .gv-nav li a .fa{font-size:15px;}
  @media(max-width:600px){.gv-banner h2{font-size:18px;}.gv-banner .gv-back span{display:none;}}
</style>
<div class="gv-header">
  <div class="gv-banner">
    <span class="gv-ico"><i class="fa fa-graduation-cap"></i></span>
    <span class="gv-tt">
      <h2><?php echo $gv_nombre; ?></h2>
      <small>Panel del grado</small>
    </span>
    <a href="escritorio.php" class="gv-back"><i class="fa fa-th-large"></i> <span>Mis Grados</span></a>
  </div>
  <ul class="gv-nav">
    <?php foreach ($gv_items as $k => $it): ?>
      <li class="<?php echo $gv_activo==$k ? 'active' : ''; ?>">
        <a href="<?php echo $it[0]; ?>?idgrupo=<?php echo $gv_gid; ?>"><i class="fa <?php echo $it[1]; ?>"></i> <?php echo $it[2]; ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
