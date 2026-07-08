<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['acceso']==1) {

  require_once "../modelos/Grupos.php";
  $grupos = new Grupos();
  $rgrados = $grupos->listar();
 ?>
    <div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title"><i class="fa fa-th-large" style="color:#0f6cbf;"></i> Gestión de Cursos y Asignación</h1>
</div>
<div class="box-body">
  <p class="text-muted">Selecciona un grado, crea sus cursos y asigna un profesor a cada curso. Cada profesor solo verá los cursos que le asignes.</p>
  <div class="form-group col-lg-6 col-md-6 col-xs-12" style="padding-left:0;">
    <label>Grado:</label>
    <select id="filtrogrado" class="form-control">
      <option value="0">-- Selecciona un grado --</option>
      <?php while ($g = $rgrados->fetch_object()): ?>
        <option value="<?php echo $g->idgrupo; ?>"><?php echo $g->nombre; ?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="col-lg-6 col-md-6 col-xs-12" style="padding-top:24px;">
    <button class="btn btn-success" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i> Nuevo Curso</button>
  </div>
  <div class="clearfix"></div>
</div>
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Curso</th>
      <th>Profesor asignado</th>
      <th>Opciones</th>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
<div class="panel-body" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label>Nombre del curso(*):</label>
      <input class="form-control" type="hidden" name="idcurso" id="idcurso">
      <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Ej: MATEMATICA" onKeyUp="this.value=this.value.toUpperCase()" required>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label>Profesor:</label>
      <select name="idprofesor" id="idprofesor" class="form-control"></select>
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
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
 <script src="scripts/gestion_cursos.js?v=<?php echo filemtime(__DIR__.'/scripts/gestion_cursos.js'); ?>"></script>
 <?php
}
ob_end_flush();
  ?>
