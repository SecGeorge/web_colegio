<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['grupos']==1) {

  $idfolder=$_GET['idfolder'];

  require_once "../modelos/Folder.php";
  $folder = new Folder();
  $cab=$folder->cabecera($idfolder);
  $nombre_carpeta=$cab['name'];
  $nombre_curso=$cab['curso'];
  $nombre_grado=$cab['grado'];
  $idgrupo=$cab['team_id'];
 ?>
    <div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Documentos <button class="btn btn-success" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i> Subir Documento</button></h1>
  <div class="box-tools pull-right">
    <a href="aula.php?idgrupo=<?php echo $idgrupo; ?>"><button class="btn btn-info"><i class="fa fa-arrow-circle-left"></i> Volver a Carpetas</button></a>
  </div>
</div>
<div class="box-body">
  <p><b>Grado:</b> <?php echo $nombre_grado; ?> &nbsp; <b>Curso:</b> <?php echo $nombre_curso; ?> &nbsp; <b>Carpeta:</b> <?php echo $nombre_carpeta; ?></p>
</div>
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Opciones</th>
      <th>Titulo</th>
      <th>Archivo</th>
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Opciones</th>
      <th>Titulo</th>
      <th>Archivo</th>
      <th>Estado</th>
    </tfoot>
  </table>
</div>
<div class="panel-body" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="idfolder" id="idfolder" value="<?php echo $idfolder; ?>">
    <input class="form-control" type="hidden" name="iddocumento" id="iddocumento">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Titulo(*):</label>
      <input class="form-control" type="text" name="titulo" id="titulo" maxlength="150" placeholder="Ej: Ficha de practica" required>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Archivo(*):</label>
      <input class="form-control" type="file" name="archivo" id="archivo">
      <small class="text-muted">PDF, Word, Excel, PPT, imagenes, zip.</small>
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
 <script src="scripts/documentos.js"></script>
 <?php
}
ob_end_flush();
  ?>
