<?php

ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';

if ($_SESSION['escritorio']==1) {
$user_id=$_SESSION["idusuario"];
  require_once "../modelos/Consultas.php";
  $consulta = new Consultas();
  $rsptav = $consulta->cantidadalumnos($user_id);
  $regv=$rsptav->fetch_object();
  $totalestudiantes=$regv->total_alumnos;
  $cap_almacen=3000;

 ?>
    <div class="content-wrapper">

    <section class="content">

      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="panel-body">
<style>
  .escritorio-head{margin:6px 4px 24px;}
  .escritorio-head h2{font-size:23px;font-weight:700;color:#2b3542;margin:0;}
  .escritorio-head p{color:#7a8797;font-size:14px;margin:4px 0 0;}
  .grado-card{background:var(--soft);border:1px solid rgba(0,0,0,.05);border-radius:18px;padding:24px;margin-bottom:26px;box-shadow:0 2px 8px rgba(0,0,0,.05);transition:.18s;min-height:198px;display:flex;flex-direction:column;}
  .grado-card:hover{transform:translateY(-5px);box-shadow:0 14px 30px rgba(0,0,0,.12);}
  .gc-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;}
  .gc-ico{width:58px;height:58px;border-radius:16px;background:#fff;color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:26px;box-shadow:0 3px 8px rgba(0,0,0,.07);}
  .gc-count{color:var(--accent);font-weight:800;font-size:26px;line-height:1;text-align:right;}
  .gc-count small{display:block;font-weight:600;font-size:11px;color:#8593a2;text-transform:uppercase;letter-spacing:.6px;margin-top:4px;}
  .gc-name{font-size:19px;font-weight:700;color:#2b3542;margin:0 0 auto;line-height:1.3;}
  .gc-btn{margin-top:18px;display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--accent);color:#fff;padding:11px 20px;border-radius:12px;text-decoration:none;font-weight:600;font-size:14px;transition:.15s;align-self:flex-start;box-shadow:0 4px 10px rgba(0,0,0,.08);}
  .gc-btn:hover{filter:brightness(.93);color:#fff;}
</style>
<div class="escritorio-head">
  <h2><i class="fa fa-graduation-cap" style="color:#3b82f6;"></i> Mis Grados</h2>
  <p>Selecciona un grado para ingresar a su aula virtual.</p>
</div>
<?php
$rspta=$consulta->gradosDeProfesor($user_id);
$temas = array(
  array('#3b82f6','#eef4ff'),
  array('#10b981','#e9fbf3'),
  array('#f59e0b','#fff6e6'),
  array('#8b5cf6','#f3effe'),
  array('#ec4899','#fdeef6'),
  array('#14b8a6','#e7faf7')
);
$idx=0;
while ($reg=$rspta->fetch_object()) {
  $idgrupo=$reg->idgrupo;
  $nombre_grupo=$reg->nombre;
  $tema=$temas[$idx % count($temas)];
  $idx++;
  $total=0;
  $rsptag=$consulta->cantidadPorGrado($idgrupo);
  while ($regrupo=$rsptag->fetch_object()) { $total=$regrupo->total_alumnos; }
  ?>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
    <div class="grado-card" style="--accent:<?php echo $tema[0]; ?>;--soft:<?php echo $tema[1]; ?>;">
      <div class="gc-top">
        <span class="gc-ico"><i class="fa fa-graduation-cap"></i></span>
        <span class="gc-count"><?php echo $total; ?><small>Alumnos</small></span>
      </div>
      <h3 class="gc-name"><?php echo $nombre_grupo; ?></h3>
      <a href="aula.php?idgrupo=<?php echo $idgrupo; ?>" class="gc-btn">Ingresar <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
<?php } ?>
<?php if ($idx==0): ?>
  <div class="col-md-12">
    <div style="background:#fff;border:1px dashed #cfd8e6;border-radius:16px;padding:40px;text-align:center;color:#8593a2;">
      <i class="fa fa-info-circle" style="font-size:38px;color:#cdd8e8;"></i>
      <p style="margin-top:12px;font-size:15px;">Aun no tienes grados ni cursos asignados. Pide al administrador que te asigne cursos.</p>
    </div>
  </div>
<?php endif; ?>

</div>

      </div>
      </div>
      </div>

    </section>

  </div>
<?php
}else{
 require 'noacceso.php';
}

require 'footer.php';
 ?>

 <?php
}

ob_end_flush();
  ?>

