<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';

if ($_SESSION['grupos']==1) {

 ?>
    <div class="content-wrapper">
    <section class="content">
      <?php $activo='cursos'; require 'grupo_nav.php'; ?>

      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Cursos <?php if ($_SESSION['acceso']==1): ?><button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button><?php endif; ?></h1>
  <?php if ($_SESSION['acceso']!=1): ?><small class="text-muted" style="margin-left:6px;">Cursos asignados a ti en este grado</small><?php endif; ?>
  <div class="box-tools pull-right">
  <a href="../vistas/vista_grupo.php?idgrupo=<?php echo $_GET["idgrupo"] ?>"><button class="btn btn-info"><i class='fa fa-arrow-circle-left'></i> Volver</button></a>
  </div>
</div>
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Curso</th>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
<div class="panel-body" style="height: 400px;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Nombre</label>
      <input class="form-control" type="hidden" name="idcurso" id="idcurso">
      <input type="hidden" id="idgrupo" name="idgrupo" value="<?php echo $_GET["idgrupo"];?>">
      <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50" placeholder="Nombre" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

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
 <script src="scripts/cursos.js"></script>
 <?php
}

ob_end_flush();
  ?>

