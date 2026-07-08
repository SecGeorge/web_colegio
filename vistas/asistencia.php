<?php

ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['grupos']==1) {

        $idgrupo=$_GET['idgrupo'];

  require_once "../modelos/Grupos.php";
  $grupos = new Grupos();
  $rspta = $grupos->mostrar_grupo($idgrupo);
  $reg=$rspta->fetch_object();
  $nombre_grupo=$reg->nombre;

 ?>
    <div class="content-wrapper">

    <section class="content">
      <?php $activo='asistencia'; require 'grupo_nav.php'; ?>

      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Registro de asistencia</h1>
  <div class="box-tools pull-right">
    <button class="btn btn-primary" onclick="cerrarAsistencia()"><i class="fa fa-lock"></i> Cerrar asistencia</button>
    <a href="../vistas/vista_grupo.php?idgrupo=<?php echo $idgrupo; ?>"><button class="btn btn-success"><i class='fa fa-arrow-circle-left'></i> Volver</button></a>
  </div>
</div>
<style>
  .asis-group{display:inline-flex;gap:6px;flex-wrap:wrap;}
  .asis-btn{border:1.5px solid #e1e5ea;background:#fff;color:#7a8797;border-radius:9px;padding:6px 13px;font-size:12px;font-weight:600;cursor:pointer;transition:.13s;line-height:1;}
  .asis-btn:hover{border-color:#c3cad2;background:#f6f8fa;color:#5a6b7b;}
  .asis-btn.active{color:#fff;box-shadow:0 2px 6px rgba(0,0,0,.15);}
  .asis-p.active{background:#18a558;border-color:#18a558;}
  .asis-t.active{background:#e0902b;border-color:#e0902b;}
  .asis-f.active{background:#e0453c;border-color:#e0453c;}
  .asis-pe.active{background:#2a7fd0;border-color:#2a7fd0;}
  .asis-leyenda{display:flex;flex-wrap:wrap;align-items:center;gap:16px;margin-top:12px;}
  .asis-leyenda .item{display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#6b7885;font-weight:600;}
  .asis-leyenda .dot{width:12px;height:12px;border-radius:50%;display:inline-block;}
</style>
<div class="box-body" style="padding-top:14px;">
  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
    <span style="font-weight:700;color:#0f6cbf;font-size:15px;"><i class="fa fa-calendar"></i> Asistencia del día:</span>
    <input type="date" id="fecha_sel" class="form-control" style="width:auto;display:inline-block;height:36px;">
    <button type="button" class="btn btn-default btn-sm" onclick="verHoy()"><i class="fa fa-calendar-check-o"></i> Hoy</button>
    <span style="color:#8a96a3;font-size:13px;">Elige una fecha para ver o editar su asistencia. Toca un botón para marcar (se guarda al instante).</span>
  </div>
  <div class="asis-leyenda">
    <span class="item"><span class="dot" style="background:#18a558;"></span> Presente</span>
    <span class="item"><span class="dot" style="background:#e0902b;"></span> Tarde</span>
    <span class="item"><span class="dot" style="background:#e0453c;"></span> Falta</span>
  </div>
</div>

<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Apellidos</th>
      <th>Telefono</th>
      <th>Asistencia</th>
      <th>Opciones</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Apellidos</th>
      <th>Telefono</th>
      <th>Asistencia</th>
      <th>Opciones</th>
    </tfoot>
  </table>
</div>

      </div>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-history" style="color:#0f6cbf;"></i> Historial de asistencias</h3>
        </div>
        <div class="panel-body table-responsive">
          <table id="tblhistorial" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th>Fecha</th>
              <th>Presentes</th>
              <th>Tardes</th>
              <th>Faltas</th>
              <th>Total</th>
              <th>Ver</th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      </div>
      </div>

    </section>

  </div>

  <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione...</h4>
        </div>
        <div class="modal-body">
  <form action="" name="formulario_asis" id="formulario_asis" method="POST">
      <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Descripcion(*):</label>
        <input type="hidden" id="idasistencia" name="idasistencia">
        <input type="hidden" id="alumn_id" name="alumn_id">
        <input type="hidden" id="fecha_asistencia" name="fecha_asistencia">
        <input type="hidden" id="idgrupo" name="idgrupo" value="<?php echo $_GET["idgrupo"];?>">
        <select class="form-control " id="tipo_asistencia"  name="tipo_asistencia">
          <option value="1"> Asistencia</option>
          <option value="2"> Tarde</option>
          <option value="3"> Falta</option>
          <option value="4"> Permiso</option>
        </select>

    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar_asis"><i class="fa fa-save"></i>  Guardar</button>
      <button class="btn btn-danger pull-right" data-dismiss="modal" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>

    </div>
        </form>
        </div>
        <div class="modal-footer">

        </div>
      </div>
    </div>
  </div>

<?php
}else{
 require 'noacceso.php';
}
require 'footer.php'
 ?>
 <script src="scripts/asistencia.js?v=<?php echo filemtime(__DIR__.'/scripts/asistencia.js'); ?>"></script>

 <?php
}

ob_end_flush();
  ?>