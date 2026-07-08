<?php
session_start();
if (!isset($_SESSION['alumno_id'])) {
  header("Location: login_alumno.html");
  exit();
}
require_once "../modelos/AlumnoAcceso.php";
$acceso = new AlumnoAcceso();
$team_id = $_SESSION['alumno_team'];
$profesor = $acceso->profesor($team_id);

$data = array();
$rc = $acceso->cursos($team_id);
while ($curso = $rc->fetch_object()) {
  $carpetas = array();
  $rf = $acceso->carpetas($curso->id);
  while ($carpeta = $rf->fetch_object()) {
    $docs = array();
    $rd = $acceso->documentos($carpeta->id);
    while ($doc = $rd->fetch_object()) { $docs[] = $doc; }
    $carpetas[] = array('folder' => $carpeta, 'docs' => $docs);
  }
  $data[] = array('curso' => $curso, 'carpetas' => $carpetas);
}

$partesNombre = explode(' ', trim($_SESSION['alumno_nombre']));
$primerNombre = $partesNombre[0];

function icono_archivo($nombre) {
  $partes = explode('.', $nombre);
  $ext = strtolower(end($partes));
  $map = array(
    'pdf' => 'fa-file-pdf-o icon-pdf',
    'doc' => 'fa-file-word-o icon-word', 'docx' => 'fa-file-word-o icon-word',
    'xls' => 'fa-file-excel-o icon-excel', 'xlsx' => 'fa-file-excel-o icon-excel',
    'ppt' => 'fa-file-powerpoint-o icon-ppt', 'pptx' => 'fa-file-powerpoint-o icon-ppt',
    'jpg' => 'fa-file-image-o icon-img', 'jpeg' => 'fa-file-image-o icon-img',
    'png' => 'fa-file-image-o icon-img', 'gif' => 'fa-file-image-o icon-img',
    'zip' => 'fa-file-archive-o icon-zip', 'rar' => 'fa-file-archive-o icon-zip',
    'txt' => 'fa-file-text-o icon-txt'
  );
  return isset($map[$ext]) ? $map[$ext] : 'fa-file-o icon-file';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Aula Virtual - <?php echo $_SESSION['alumno_grado']; ?></title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <link rel="stylesheet" href="../public/css/font-awesome.css">
  <link rel="shortcut icon" href="../public/img/mg.ico">
  <style>
    *{box-sizing:border-box;}
    body{margin:0;background:#eef4fb;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:#26324a;}
    a{text-decoration:none;}
    .side{position:fixed;top:0;left:0;bottom:0;width:264px;background:linear-gradient(180deg,#0f6cbf 0%,#0c589f 100%);color:#fff;display:flex;flex-direction:column;overflow-y:auto;z-index:30;}
    .side-brand{padding:20px 20px 4px;font-size:19px;font-weight:800;display:flex;align-items:center;gap:10px;}
    .side-brand .fa{color:#ffd24d;font-size:22px;}
    .side-grade{padding:0 20px 16px;font-size:12.5px;opacity:.85;border-bottom:1px solid rgba(255,255,255,.15);}
    .side-user{display:flex;align-items:center;gap:12px;margin:12px 14px 10px;padding:14px;background:rgba(255,255,255,.14);border-radius:14px;border-bottom:1px solid rgba(255,255,255,.12);}
    .side-user .su-ava{width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.26);display:flex;align-items:center;justify-content:center;font-size:23px;flex:0 0 48px;}
    .side-user .su-hi{font-size:12px;opacity:.85;line-height:1;}
    .side-user .su-name{font-size:18px;font-weight:800;line-height:1.15;}
    .side-user .su-grade{font-size:11px;opacity:.82;margin-top:4px;}
    .side-section{padding:16px 20px 6px;font-size:11px;text-transform:uppercase;letter-spacing:1px;font-weight:700;color:rgba(255,255,255,.65);}
    .side-course{display:flex;align-items:center;gap:11px;margin:2px 12px;padding:11px 14px;border-radius:10px;color:#eaf3fc;font-size:14px;font-weight:600;cursor:pointer;transition:.13s;}
    .side-course .fa{width:20px;text-align:center;opacity:.9;}
    .side-course:hover{background:rgba(255,255,255,.14);color:#fff;}
    .side-course.active{background:#fff;color:#0f6cbf;box-shadow:0 3px 8px rgba(0,0,0,.18);}
    .side-course .sc-badge{margin-left:auto;font-size:11px;font-weight:700;background:rgba(255,255,255,.22);padding:2px 8px;border-radius:11px;}
    .side-course.active .sc-badge{background:#e8f2fb;color:#0f6cbf;}
    .side-foot{margin-top:auto;padding:14px 16px 18px;border-top:1px solid rgba(255,255,255,.15);}
    .side-prof{display:flex;align-items:center;gap:10px;margin-bottom:12px;}
    .side-prof img{width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.55);background:#fff;flex:0 0 42px;}
    .side-prof .sp-name{font-size:13px;font-weight:700;line-height:1.2;}
    .side-prof .sp-role{font-size:11px;opacity:.82;}
    .side-out{display:flex;align-items:center;justify-content:center;gap:8px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.35);color:#fff;padding:10px;border-radius:10px;font-size:13px;font-weight:700;transition:.13s;}
    .side-out:hover{background:#fff;color:#0f6cbf;}
    .content{margin-left:264px;min-height:100vh;}
    .topbar{background:#fff;height:60px;display:flex;align-items:center;justify-content:space-between;padding:0 26px;border-bottom:1px solid #e3e9f2;position:sticky;top:0;z-index:20;box-shadow:0 1px 4px rgba(20,30,60,.04);}
    .topbar .tb-title{font-size:15px;font-weight:700;color:#0f6cbf;}
    .topbar .tb-user{display:flex;align-items:center;gap:10px;font-size:14px;color:#374357;font-weight:600;}
    .topbar .tb-user .tb-ava{width:34px;height:34px;border-radius:50%;background:#e8f2fb;color:#0f6cbf;display:flex;align-items:center;justify-content:center;}
    .main{max-width:1400px;margin:0 auto;padding:26px 40px 46px;}
    .hero{background:linear-gradient(120deg,#0f6cbf 0%,#2a93e0 100%);color:#fff;border-radius:20px;padding:26px 30px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:22px;box-shadow:0 10px 26px rgba(15,108,191,.28);}
    .hero h1{margin:0 0 6px;font-size:24px;font-weight:800;}
    .hero p{margin:0;opacity:.93;font-size:14.5px;max-width:540px;}
    .hero .hero-ico{font-size:58px;opacity:.9;flex:0 0 auto;}
    .av-search{position:relative;margin-bottom:20px;}
    .av-search input{width:100%;padding:13px 16px 13px 44px;border:1px solid #d5e0ee;border-radius:14px;font-size:14px;background:#fff;outline:none;transition:.15s;box-shadow:0 2px 6px rgba(20,30,60,.04);}
    .av-search input:focus{border-color:#2a93e0;box-shadow:0 0 0 4px rgba(15,108,191,.14);}
    .av-search .fa{position:absolute;left:16px;top:14px;color:#98a2b3;}
    .curso{background:#fff;border:1px solid #e4ebf5;border-radius:16px;margin-bottom:16px;overflow:hidden;box-shadow:0 2px 10px rgba(20,30,60,.05);transition:.15s;scroll-margin-top:76px;}
    .curso:hover{box-shadow:0 8px 22px rgba(20,30,60,.10);}
    .curso-head{display:flex;align-items:center;gap:14px;padding:18px 20px;cursor:pointer;user-select:none;}
    .curso-head .c-ico{width:48px;height:48px;border-radius:14px;background:#e8f2fb;color:#0f6cbf;display:flex;align-items:center;justify-content:center;font-size:21px;flex:0 0 48px;}
    .curso-head .c-name{flex:1 1 auto;font-size:16px;font-weight:800;color:#26324a;text-transform:uppercase;letter-spacing:.3px;}
    .curso-head .c-count{font-size:12px;font-weight:700;color:#0f6cbf;background:#e8f2fb;padding:5px 13px;border-radius:20px;white-space:nowrap;}
    .curso-head .c-chev{color:#b7c0cf;transition:transform .2s;font-size:17px;}
    .curso-head.collapsed .c-chev{transform:rotate(-90deg);}
    .curso-body{padding:2px 18px 18px;}
    .folder{border:1px solid #eceef4;border-radius:13px;margin-top:12px;overflow:hidden;background:#fcfcfe;}
    .folder-head{display:flex;align-items:center;gap:12px;padding:13px 15px;cursor:pointer;user-select:none;transition:.13s;}
    .folder-head:hover{background:#f2f7fd;}
    .folder-head .f-ico{width:36px;height:36px;border-radius:11px;background:#fff2d4;color:#e0a010;display:flex;align-items:center;justify-content:center;font-size:16px;flex:0 0 36px;}
    .folder-head .f-name{flex:1 1 auto;font-weight:700;font-size:14px;color:#374357;}
    .folder-head .c-count{font-size:11.5px;font-weight:700;color:#7a8699;background:#eef1f6;padding:4px 11px;border-radius:16px;white-space:nowrap;}
    .folder-head .c-chev{color:#b7c0cf;transition:transform .2s;}
    .folder-head.collapsed .c-chev{transform:rotate(-90deg);}
    .doc{display:flex;align-items:center;gap:14px;padding:12px 15px;border-top:1px solid #eef0f5;color:#26324a;transition:.12s;}
    .doc:hover{background:#f2f7ff;}
    .doc .d-ico{width:42px;height:42px;border-radius:11px;background:#f1f4f9;display:flex;align-items:center;justify-content:center;font-size:19px;flex:0 0 42px;}
    .doc .d-body{flex:1 1 auto;min-width:0;}
    .doc .d-title{font-weight:700;font-size:14px;}
    .doc .d-file{font-size:12px;color:#98a2b3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .doc .d-dl{background:#e8f2fb;color:#0f6cbf;font-size:12.5px;font-weight:700;padding:8px 16px;border-radius:20px;white-space:nowrap;transition:.13s;}
    .doc:hover .d-dl{background:#0f6cbf;color:#fff;}
    .icon-pdf{color:#e2483d;}.icon-word{color:#2b579a;}.icon-excel{color:#217346;}.icon-ppt{color:#d24726;}.icon-img{color:#7a4bc9;}.icon-zip{color:#a5732b;}.icon-txt{color:#5a5a5a;}.icon-file{color:#6b6b6b;}
    .empty{padding:14px 16px;color:#a2abba;font-size:13px;font-style:italic;}
    .no-content{background:#fff;border:1px dashed #cfdaeb;border-radius:16px;padding:44px;text-align:center;color:#a2abba;}
    .no-content .fa{font-size:44px;color:#cdd8e8;margin-bottom:12px;}
    @media(max-width:860px){
      .side{position:static;width:100%;height:auto;}
      .content{margin-left:0;}
      .hero{flex-direction:column;text-align:center;}.hero .hero-ico{display:none;}
    }
  </style>
</head>
<body>
  <aside class="side">
    <div class="side-brand"><i class="fa fa-graduation-cap"></i> Aula Virtual</div>
    <div class="side-user">
      <div class="su-ava"><i class="fa fa-user"></i></div>
      <div>
        <div class="su-hi">¡Hola,</div>
        <div class="su-name"><?php echo $primerNombre; ?>!</div>
        <div class="su-grade"><i class="fa fa-map-marker"></i> <?php echo $_SESSION['alumno_grado']; ?></div>
      </div>
    </div>

    <div class="side-section">Mis Cursos</div>
    <?php if (count($data) == 0): ?>
      <div style="padding:6px 20px;font-size:13px;opacity:.8;">Sin cursos aun.</div>
    <?php else: ?>
      <?php foreach ($data as $item): $c = $item['curso']; ?>
        <a class="side-course" id="side<?php echo $c->id; ?>" onclick="irCurso(<?php echo $c->id; ?>)">
          <i class="fa fa-book"></i> <span><?php echo $c->name; ?></span>
          <span class="sc-badge"><?php echo count($item['carpetas']); ?></span>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="side-foot">
      <?php if ($profesor): ?>
      <div class="side-prof">
        <img src="../files/usuarios/<?php echo $profesor['imagen']; ?>" alt="" onerror="this.src='../files/articulos/anonymous.png'">
        <div>
          <div class="sp-name"><?php echo $profesor['nombre']; ?></div>
          <div class="sp-role"><?php echo $profesor['cargo'] ? $profesor['cargo'] : 'Profesor'; ?></div>
        </div>
      </div>
      <?php endif; ?>
      <a class="side-out" href="../ajax/alumno.php?op=salir"><i class="fa fa-sign-out"></i> Cerrar sesion</a>
    </div>
  </aside>

  <div class="content">
    <div class="topbar">
      <div class="tb-title"><i class="fa fa-book"></i> Contenido del curso</div>
      <div class="tb-user">
        <span class="tb-ava"><i class="fa fa-user"></i></span>
        <?php echo $_SESSION['alumno_nombre']; ?>
      </div>
    </div>

    <div class="main">
      <div class="av-search">
        <i class="fa fa-search"></i>
        <input type="text" id="buscador" placeholder="Buscar curso, carpeta o documento...">
      </div>

      <div id="listado">
      <?php if (count($data) == 0): ?>
        <div class="no-content"><i class="fa fa-inbox"></i><br>Todavia no hay cursos disponibles para tu grado.</div>
      <?php else: ?>
        <?php foreach ($data as $item): $curso = $item['curso']; $carpetas = $item['carpetas']; ?>
          <div class="curso item" id="card<?php echo $curso->id; ?>" data-nombre="<?php echo strtolower($curso->name); ?>">
            <div class="curso-head" data-toggle="collapse" data-target="#curso<?php echo $curso->id; ?>" aria-expanded="true">
              <span class="c-ico"><i class="fa fa-book"></i></span>
              <span class="c-name"><?php echo $curso->name; ?></span>
              <span class="c-count"><?php echo count($carpetas); ?> carpeta<?php echo count($carpetas)==1?'':'s'; ?></span>
              <i class="fa fa-chevron-down c-chev"></i>
            </div>
            <div id="curso<?php echo $curso->id; ?>" class="curso-body collapse in">
              <?php if (count($carpetas) == 0): ?>
                <div class="empty">Este curso aun no tiene carpetas publicadas.</div>
              <?php else: ?>
                <?php foreach ($carpetas as $c): $folder = $c['folder']; $docs = $c['docs']; ?>
                  <div class="folder item" data-nombre="<?php echo strtolower($folder->name); ?>">
                    <div class="folder-head collapsed" data-toggle="collapse" data-target="#folder<?php echo $folder->id; ?>" aria-expanded="false">
                      <span class="f-ico"><i class="fa fa-folder"></i></span>
                      <span class="f-name"><?php echo $folder->name; ?></span>
                      <span class="c-count"><?php echo count($docs); ?> doc<?php echo count($docs)==1?'':'s'; ?></span>
                      <i class="fa fa-chevron-down c-chev"></i>
                    </div>
                    <div id="folder<?php echo $folder->id; ?>" class="collapse">
                      <?php if (count($docs) == 0): ?>
                        <div class="empty">Sin documentos disponibles.</div>
                      <?php else: ?>
                        <?php foreach ($docs as $doc): ?>
                          <a class="doc item" data-nombre="<?php echo strtolower($doc->title.' '.$doc->originalname); ?>" href="../files/documentos/<?php echo $doc->filename; ?>" target="_blank" download="<?php echo $doc->originalname; ?>">
                            <span class="d-ico"><i class="fa <?php echo icono_archivo($doc->originalname); ?>"></i></span>
                            <span class="d-body">
                              <span class="d-title"><?php echo $doc->title; ?></span>
                              <span class="d-file"><?php echo $doc->originalname; ?></span>
                            </span>
                            <span class="d-dl"><i class="fa fa-download"></i> Descargar</span>
                          </a>
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
      </div>
      <div id="sinresultados" class="no-content" style="display:none;"><i class="fa fa-search"></i><br>No se encontraron resultados.</div>
    </div>
  </div>

  <script src="../public/js/jquery-3.1.1.min.js"></script>
  <script src="../public/js/bootstrap.min.js"></script>
  <script>
    function irCurso(cid){
      var el = document.getElementById('card'+cid);
      if(el){ el.scrollIntoView({behavior:'smooth', block:'start'}); }
      $('.side-course').removeClass('active');
      $('#side'+cid).addClass('active');
      $('#curso'+cid).addClass('in');
    }

    $("#buscador").on("keyup", function(){
      var q = $(this).val().toLowerCase().trim();
      if (q === ""){ $(".item").show(); $("#sinresultados").hide(); return; }
      var visibles = 0;
      $(".curso").each(function(){
        var curso = $(this);
        var matchCurso = curso.data("nombre").indexOf(q) !== -1;
        var algo = matchCurso;
        curso.find(".folder").each(function(){
          var folder = $(this);
          var matchFolder = folder.data("nombre").indexOf(q) !== -1;
          var algoFolder = matchFolder;
          folder.find(".doc").each(function(){
            var d = $(this).data("nombre").indexOf(q) !== -1;
            $(this).toggle(matchFolder || matchCurso || d);
            if (d) algoFolder = true;
          });
          folder.toggle(matchCurso || algoFolder);
          if (matchCurso || algoFolder) algo = true;
        });
        curso.toggle(algo);
        if (algo) visibles++;
      });
      $("#sinresultados").toggle(visibles === 0);
    });
  </script>
</body>
</html>
