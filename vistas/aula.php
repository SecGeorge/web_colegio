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
  require_once "../modelos/Cursos.php";
  require_once "../modelos/Folder.php";
  require_once "../modelos/Documento.php";

  $grupos = new Grupos();
  $g = $grupos->mostrar_grupo($idgrupo)->fetch_object();
  $nombre_grupo = $g->nombre;

  $cursosM = new Cursos();
  $folderM = new Folder();
  $docM = new Documento();

  $arbol = array();
  $rc = $cursosM->listar($idgrupo);
  while ($cu = $rc->fetch_object()) {
    $fs = array();
    $rf = $folderM->listar($cu->id);
    while ($fo = $rf->fetch_object()) {
      $ds = array();
      $rd = $docM->listar($fo->id);
      while ($d = $rd->fetch_object()) { $ds[] = $d; }
      $fs[] = array('f' => $fo, 'docs' => $ds);
    }
    $arbol[] = array('c' => $cu, 'folders' => $fs);
  }

  function tv_icono($nombre) {
    $partes = explode('.', $nombre);
    $ext = strtolower(end($partes));
    $map = array(
      'pdf'=>'fa-file-pdf-o tv-pdf','doc'=>'fa-file-word-o tv-word','docx'=>'fa-file-word-o tv-word',
      'xls'=>'fa-file-excel-o tv-excel','xlsx'=>'fa-file-excel-o tv-excel',
      'ppt'=>'fa-file-powerpoint-o tv-ppt','pptx'=>'fa-file-powerpoint-o tv-ppt',
      'jpg'=>'fa-file-image-o tv-img','jpeg'=>'fa-file-image-o tv-img','png'=>'fa-file-image-o tv-img','gif'=>'fa-file-image-o tv-img',
      'zip'=>'fa-file-archive-o tv-zip','rar'=>'fa-file-archive-o tv-zip','txt'=>'fa-file-text-o tv-txt'
    );
    return isset($map[$ext]) ? $map[$ext] : 'fa-file-o tv-file';
  }
 ?>
<style>
  .tv-wrap{padding:16px 4px;}
  .tv-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:8px;}
  .tv-head h1{font-size:24px;font-weight:600;margin:0;color:#262626;}
  .tv-head .tv-grade{color:#0f6cbf;}
  .tv-hint{color:#6b6b6b;font-size:13px;margin:0 0 18px;}
  .tv-legend{font-size:12px;color:#8a8a8a;margin-left:8px;}
  .tv-badge{display:inline-block;font-size:11px;font-weight:600;padding:2px 9px;border-radius:11px;letter-spacing:.3px;text-transform:uppercase;}
  .tv-vis{background:#e4f4ea;color:#1c7a43;}
  .tv-hid{background:#efefef;color:#8a8a8a;}
  .tv-curso{background:#fff;border:1px solid #e3e3e3;border-radius:10px;margin-bottom:14px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,.04);}
  .tv-curso-head{display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fafbfc;border-bottom:1px solid #eee;}
  .tv-curso-head .ico{width:34px;height:34px;border-radius:8px;background:#e8f1fb;color:#0f6cbf;display:flex;align-items:center;justify-content:center;font-size:16px;flex:0 0 34px;}
  .tv-curso-head .name{flex:1 1 auto;font-size:16px;font-weight:600;text-transform:uppercase;}
  .tv-curso-body{padding:12px 16px 16px;}
  .tv-folder{border:1px solid #ececec;border-radius:8px;margin-top:12px;overflow:hidden;}
  .tv-folder:first-child{margin-top:0;}
  .tv-folder-head{display:flex;align-items:center;gap:10px;padding:11px 14px;background:#fafafa;cursor:pointer;}
  .tv-folder-head:hover{background:#f2f4f6;}
  .tv-folder-head .fa-folder-open{color:#f2b705;}
  .tv-folder-head .fname{flex:1 1 auto;font-weight:600;font-size:14px;}
  .tv-chev{color:#9a9a9a;transition:transform .2s;}
  .tv-folder-head.collapsed .tv-chev{transform:rotate(-90deg);}
  .tv-actions{display:flex;gap:6px;align-items:center;}
  .tv-doc{display:flex;align-items:center;gap:12px;padding:10px 14px;border-top:1px solid #f0f0f0;}
  .tv-doc .d-ico{font-size:19px;width:24px;text-align:center;}
  .tv-doc .d-body{flex:1 1 auto;min-width:0;}
  .tv-doc .d-title{font-weight:600;font-size:14px;}
  .tv-doc .d-file{font-size:12px;color:#8a8a8a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
  .tv-pdf{color:#e2483d;}.tv-word{color:#2b579a;}.tv-excel{color:#217346;}.tv-ppt{color:#d24726;}.tv-img{color:#7a4bc9;}.tv-zip{color:#a5732b;}.tv-txt{color:#5a5a5a;}.tv-file{color:#6b6b6b;}
  .tv-empty{padding:12px 14px;color:#9a9a9a;font-size:13px;font-style:italic;}
  .tv-nocurso{background:#fff;border:1px dashed #d6d6d6;border-radius:10px;padding:36px;text-align:center;color:#8a8a8a;}
  .tv-nocurso .fa{font-size:36px;color:#cfcfcf;margin-bottom:10px;}
  .btn-xs.tv-b{border-radius:6px;}
</style>
    <div class="content-wrapper">
    <section class="content tv-wrap">
      <?php $activo='aula'; require 'grupo_nav.php'; ?>
      <div class="tv-head">
        <div>
          <h1><i class="fa fa-folder-open" style="color:#f2b705;"></i> Aula Virtual</h1>
          <p class="tv-hint">Organiza el material por curso. <span class="tv-legend"><span class="tv-badge tv-vis">Visible</span> lo ve el alumno &nbsp; <span class="tv-badge tv-hid">Oculto</span> no lo ve.</span></p>
        </div>
      </div>

      <?php if (count($arbol)==0): ?>
        <div class="tv-nocurso"><i class="fa fa-book"></i><br>Este grado aun no tiene cursos. Crealos primero en <b>Cursos</b>.</div>
      <?php else: ?>
        <?php foreach ($arbol as $item): $cu=$item['c']; $folders=$item['folders']; ?>
          <div class="tv-curso">
            <div class="tv-curso-head">
              <span class="ico"><i class="fa fa-book"></i></span>
              <span class="name"><?php echo $cu->name; ?></span>
              <button class="btn btn-success btn-sm" onclick="nuevaCarpeta(<?php echo $cu->id; ?>)"><i class="fa fa-plus"></i> Nueva carpeta</button>
            </div>
            <div class="tv-curso-body">
              <?php if (count($folders)==0): ?>
                <div class="tv-empty">Sin carpetas. Crea la primera con "Nueva carpeta".</div>
              <?php else: ?>
                <?php foreach ($folders as $c): $fo=$c['f']; $docs=$c['docs']; ?>
                  <div class="tv-folder">
                    <div class="tv-folder-head collapsed" data-toggle="collapse" data-target="#tvf<?php echo $fo->id; ?>">
                      <i class="fa fa-folder-open"></i>
                      <span class="fname"><?php echo $fo->name; ?></span>
                      <?php if ($fo->is_active==1): ?><span class="tv-badge tv-vis">Visible</span><?php else: ?><span class="tv-badge tv-hid">Oculto</span><?php endif; ?>
                      <span class="tv-chev"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div id="tvf<?php echo $fo->id; ?>" class="collapse">
                      <div class="tv-doc" style="background:#fbfcfd;">
                        <div class="tv-actions" style="flex:1 1 auto;">
                          <button class="btn btn-primary btn-xs tv-b" onclick="subirDocumento(<?php echo $fo->id; ?>)"><i class="fa fa-upload"></i> Subir documento</button>
                          <button class="btn btn-default btn-xs tv-b" onclick="editarCarpeta(<?php echo $fo->id; ?>,'<?php echo addslashes($fo->name); ?>',<?php echo $cu->id; ?>)"><i class="fa fa-pencil"></i> Renombrar</button>
                          <?php if ($fo->is_active==1): ?>
                            <button class="btn btn-warning btn-xs tv-b" onclick="toggleCarpeta(<?php echo $fo->id; ?>,0)"><i class="fa fa-eye-slash"></i> Ocultar carpeta</button>
                          <?php else: ?>
                            <button class="btn btn-success btn-xs tv-b" onclick="toggleCarpeta(<?php echo $fo->id; ?>,1)"><i class="fa fa-eye"></i> Mostrar carpeta</button>
                          <?php endif; ?>
                        </div>
                      </div>
                      <?php if (count($docs)==0): ?>
                        <div class="tv-empty">Sin documentos en esta carpeta.</div>
                      <?php else: ?>
                        <?php foreach ($docs as $d): ?>
                          <div class="tv-doc">
                            <i class="fa <?php echo tv_icono($d->originalname); ?> d-ico"></i>
                            <span class="d-body">
                              <span class="d-title"><?php echo $d->title; ?></span>
                              <span class="d-file"><a href="../files/documentos/<?php echo $d->filename; ?>" target="_blank"><?php echo $d->originalname; ?></a></span>
                            </span>
                            <?php if ($d->is_active==1): ?><span class="tv-badge tv-vis">Visible</span><?php else: ?><span class="tv-badge tv-hid">Oculto</span><?php endif; ?>
                            <div class="tv-actions">
                              <?php if ($d->is_active==1): ?>
                                <button class="btn btn-warning btn-xs tv-b" onclick="toggleDocumento(<?php echo $d->id; ?>,0)" title="Ocultar"><i class="fa fa-eye-slash"></i></button>
                              <?php else: ?>
                                <button class="btn btn-success btn-xs tv-b" onclick="toggleDocumento(<?php echo $d->id; ?>,1)" title="Mostrar"><i class="fa fa-eye"></i></button>
                              <?php endif; ?>
                              <button class="btn btn-danger btn-xs tv-b" onclick="eliminarDocumento(<?php echo $d->id; ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </div>

  <div class="modal fade" id="modalCarpeta" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"><div class="modal-content">
      <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title" id="tvTituloCarpeta">Nueva carpeta</h4></div>
      <div class="modal-body">
        <input type="hidden" id="c_idfolder"><input type="hidden" id="c_idcurso">
        <div class="form-group"><label>Nombre de la carpeta</label><input class="form-control" id="c_nombre" maxlength="100" placeholder="Ej: Sesion 1 - Fracciones"></div>
      </div>
      <div class="modal-footer"><button class="btn btn-default" data-dismiss="modal">Cancelar</button><button class="btn btn-primary" onclick="guardarCarpeta()"><i class="fa fa-save"></i> Guardar</button></div>
    </div></div>
  </div>

  <div class="modal fade" id="modalDocumento" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"><div class="modal-content">
      <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Subir documento</h4></div>
      <div class="modal-body">
        <input type="hidden" id="d_idfolder">
        <div class="form-group"><label>Titulo</label><input class="form-control" id="d_titulo" maxlength="150" placeholder="Ej: Ficha de practica"></div>
        <div class="form-group"><label>Archivo</label><input type="file" id="d_archivo"><small class="text-muted">PDF, Word, Excel, PPT, imagenes, zip.</small></div>
      </div>
      <div class="modal-footer"><button class="btn btn-default" data-dismiss="modal">Cancelar</button><button class="btn btn-primary" id="btnSubir" onclick="guardarDocumento()"><i class="fa fa-upload"></i> Subir</button></div>
    </div></div>
  </div>
<?php
}else{
 require 'noacceso.php';
}
require 'footer.php';
 ?>
 <script src="scripts/aula.js"></script>
 <?php
}
ob_end_flush();
  ?>
