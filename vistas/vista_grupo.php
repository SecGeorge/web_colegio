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

      <div class="row">
        <div class="col-md-12">
      <?php $activo='alumnos'; require 'grupo_nav.php'; ?>
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title"><i class="fa fa-users" style="color:#0f6cbf;"></i> Alumnos del grado</h1>
  <div class="box-tools pull-right">
    <button class="btn btn-success" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i> Agregar Alumno</button>
  </div>
</div>

<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Apellidos</th>
      <th>Telefono</th>
      <th>Dirección</th>
      <th>Email</th>
      <th>Opciones</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Apellidos</th>
      <th>Telefono</th>
      <th>Dirección</th>
      <th>Email</th>
      <th>Opciones</th>
    </tfoot>
  </table>
</div>
<div class="panel-body" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Nombres(*):</label>
          <input type="hidden" id="idgrupo" name="idgrupo" value="<?php echo $_GET["idgrupo"];?>">
      <input class="form-control" type="hidden" name="idalumno" id="idalumno">
      <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Apellidos(*):</label>
            <input class="form-control" type="text" name="apellidos" id="apellidos" maxlength="100" placeholder="Nombre" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
    </div>
       <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Dirección(*)</label>
      <input class="form-control" type="text" name="direccion" id="direccion" placeholder="Dirección" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
    </div>
       <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Email(*)</label>
      <input class="form-control" type="email" name="email" id="email" maxlength="256" placeholder="ejemplo@ejemplo.com">
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Teléfono(*)</label>
      <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Dirección" required>
    </div>
        <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Imagen:</label>
      <input class="form-control" type="file" name="imagen" id="imagen">
      <input type="hidden" name="imagenactual" id="imagenactual">
      <img src="" alt="" width="150px" height="120" id="imagenmuestra">
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
require 'footer.php'
 ?>
 <script src="scripts/vista_grupo.js"></script>

 <?php
}

ob_end_flush();
  ?>