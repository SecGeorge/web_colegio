 <?php
if (strlen(session_id())<1)
  session_start();
  ?>
 <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>MARIA GORETTI</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="../public/css/bootstrap.min.css">

    <link rel="stylesheet" href="../public/css/font-awesome.css">

    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">

    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/mg.ico">

    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">
    <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

    <style>
      .content{padding:20px 32px !important;}
      .box{border-radius:10px;border-top-width:3px;box-shadow:0 1px 3px rgba(0,0,0,.08);}
      .box-title{font-weight:600;}
      @media(max-width:768px){.content{padding:15px 12px !important;}}

      .skin-blue .main-sidebar,.skin-blue .left-side{background:#1b2530;}
      .sidebar-menu>li.header{color:#6f8296 !important;background:transparent !important;font-size:11px;font-weight:700;letter-spacing:1.2px;padding:16px 18px 6px;text-transform:uppercase;}
      .skin-blue .sidebar-menu>li>a{margin:3px 10px;padding:11px 14px;border-radius:8px;color:#c3ccd6;font-weight:500;border-left:none !important;transition:.15s;}
      .skin-blue .sidebar-menu>li>a>.fa{width:24px;font-size:15px;color:#8ea2b5;transition:.15s;}
      .skin-blue .sidebar-menu>li>a:hover{background:#26333f;color:#fff;}
      .skin-blue .sidebar-menu>li>a:hover>.fa{color:#fff;}
      .skin-blue .sidebar-menu>li.active>a,.skin-blue .sidebar-menu>li.active>a:hover{background:linear-gradient(90deg,#0f6cbf,#2a93e0);color:#fff;box-shadow:0 3px 8px rgba(15,108,191,.35);}
      .skin-blue .sidebar-menu>li.active>a>.fa{color:#fff;}
      .skin-blue .main-header .logo{background:#15507f;font-weight:600;}
      .skin-blue .main-header .logo:hover{background:#123f65;}
      .skin-blue .main-header .navbar{background:#0f6cbf;}

      .dataTables_wrapper{padding-top:4px;}
      .dataTables_wrapper .dataTables_length,.dataTables_wrapper .dataTables_filter{margin-bottom:14px;}
      .dataTables_wrapper .dataTables_length select,.dataTables_wrapper .dataTables_filter input{border:1px solid #d9dde2;border-radius:8px;padding:8px 12px;outline:none;background:#fff;transition:.15s;color:#333;}
      .dataTables_wrapper .dataTables_filter input{min-width:230px;margin-left:8px;}
      .dataTables_wrapper .dataTables_filter input:focus,.dataTables_wrapper .dataTables_length select:focus{border-color:#0f6cbf;box-shadow:0 0 0 3px rgba(15,108,191,.12);}
      .dataTables_wrapper .dataTables_filter,.dataTables_wrapper .dataTables_length{color:#6b7885;font-size:13px;}
      table.dataTable{border-collapse:separate !important;border-spacing:0;width:100% !important;margin:6px 0 !important;}
      table.dataTable,table.dataTable.table-bordered,table.dataTable.table-bordered>thead>tr>th,table.dataTable.table-bordered>tbody>tr>td,table.dataTable.table-bordered>tfoot>tr>th{border:none !important;}
      table.dataTable thead th{background:#f5f7fa;color:#5a6b7b;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;border:none !important;border-bottom:2px solid #e8ebef !important;padding:13px 14px;}
      table.dataTable thead th:first-child{border-top-left-radius:10px;}
      table.dataTable thead th:last-child{border-top-right-radius:10px;}
      table.dataTable tbody td{border:none !important;border-bottom:1px solid #f0f2f4 !important;padding:11px 14px;font-size:14px;color:#3a4149;vertical-align:middle;}
      table.dataTable.table-striped>tbody>tr:nth-of-type(odd)>td{background:#fbfcfd;}
      table.dataTable tbody tr:hover>td{background:#eef5fc !important;}
      table.dataTable tfoot{display:none;}
      .dataTables_wrapper .dataTables_info{color:#8a96a3;font-size:13px;padding-top:16px;}
      .dataTables_wrapper .dataTables_paginate{padding-top:12px;}
      .dataTables_wrapper .dataTables_paginate .paginate_button{border:none !important;border-radius:8px !important;padding:7px 13px !important;margin:0 2px !important;color:#4a5b6b !important;background:transparent !important;transition:.15s;}
      .dataTables_wrapper .dataTables_paginate .paginate_button:hover{background:#eef2f7 !important;color:#0f6cbf !important;box-shadow:none !important;border:none !important;}
      .dataTables_wrapper .dataTables_paginate .paginate_button.current,.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{background:linear-gradient(90deg,#0f6cbf,#2a93e0) !important;color:#fff !important;box-shadow:0 2px 6px rgba(15,108,191,.3);border:none !important;}
      .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover{color:#c2c9d1 !important;background:transparent !important;}
      .dt-buttons{margin-bottom:14px;}
      .dt-buttons .dt-button{background:#fff !important;border:1px solid #d9dde2 !important;border-radius:8px !important;color:#4a5b6b !important;padding:8px 15px !important;font-size:13px !important;font-weight:500;margin-right:6px;transition:.15s;box-shadow:none !important;text-shadow:none !important;}
      .dt-buttons .dt-button:hover{background:#0f6cbf !important;border-color:#0f6cbf !important;color:#fff !important;}
      .dt-buttons .dt-button span{color:inherit !important;}

      .navbar-nav>.user-menu>a>.user-image{border-radius:50%;border:2px solid rgba(255,255,255,.6);width:34px;height:34px;object-fit:cover;margin-top:-2px;}
      .navbar-nav>.user-menu>.dropdown-menu{width:262px;padding:0;border:none;border-radius:14px;box-shadow:0 10px 34px rgba(0,0,0,.20);overflow:hidden;margin-top:8px;}
      .um-header{background:linear-gradient(135deg,#0f6cbf,#2a93e0);padding:24px 16px 20px;text-align:center;color:#fff;}
      .um-header img{width:74px;height:74px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.85);box-shadow:0 4px 12px rgba(0,0,0,.2);background:#fff;}
      .um-header .um-name{font-size:16px;font-weight:700;margin:12px 0 4px;}
      .um-header .um-role{font-size:11px;font-weight:600;letter-spacing:.4px;text-transform:uppercase;background:rgba(255,255,255,.22);display:inline-block;padding:3px 13px;border-radius:12px;}
      .um-footer{display:flex;gap:10px;padding:14px;background:#fff;}
      .um-footer a{flex:1;display:flex;align-items:center;justify-content:center;gap:7px;padding:10px 0;border-radius:9px;font-weight:600;font-size:13px;text-decoration:none;transition:.15s;}
      .um-perfil{background:#eef2f7;color:#41566b;}
      .um-perfil:hover{background:#e2e8f0;color:#41566b;}
      .um-salir{background:#fdecec;color:#d9463e;}
      .um-salir:hover{background:#d9463e;color:#fff;}
    </style>

  </head>

<body class="hold-transition skin-blue sidebar-mini">

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      xfbml            : true,
      version          : 'v3.2'
    });
  };

  (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/es_LA/sdk/xfbml.customerchat.js';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-customerchat"
  attribution=setup_tool
  page_id="280144326139427"
  theme_color="#0084ff"
  logged_in_greeting="Hola! deseas compartir algún sistema o descargar ?"
  logged_out_greeting="Hola! deseas compartir algún sistema o descargar ?">
</div>
<div class="wrapper">

  <header class="main-header">

    <a href="escritorio.php" class="logo">

      <span class="logo-mini"><b>M</b> G</span>

      <span class="logo-lg"><b>MARIA</b> GORETTI</span>
    </a>

    <nav class="navbar navbar-static-top">

      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="" onerror="this.src='../files/articulos/anonymous.png'">
              <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="um-header">
                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" alt="" onerror="this.src='../files/articulos/anonymous.png'">
                <div class="um-name"><?php echo $_SESSION['nombre']; ?></div>
                <span class="um-role"><?php echo $_SESSION['cargo']; ?></span>
              </li>
              <li class="um-footer">
                <a href="#" class="um-perfil"><i class="fa fa-user"></i> Perfil</a>
                <a href="../ajax/usuario.php?op=salir" class="um-salir"><i class="fa fa-sign-out"></i> Salir</a>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">

    <section class="sidebar">

      <ul class="sidebar-menu" data-widget="tree">
       <?php $pagina = basename($_SERVER['PHP_SELF']); ?>
       <li class="header">Principal</li>
       <?php
if ($_SESSION['escritorio']==1) {
  echo '<li class="'.($pagina=='escritorio.php'?'active':'').'"><a href="escritorio.php"><i class="fa fa-th-large"></i> <span>Escritorio</span></a></li>';
}
        ?>

          <?php if(isset($_GET["idgrupo"])): $gid=$_GET["idgrupo"]; ?>
          <li class="header">Grado actual</li>
          <li class="<?php echo $pagina=='aula.php'?'active':''; ?>"><a href="aula.php?idgrupo=<?php echo $gid; ?>"><i class="fa fa-folder-open"></i> <span>Aula Virtual</span></a></li>
          <li class="<?php echo $pagina=='vista_grupo.php'?'active':''; ?>"><a href="vista_grupo.php?idgrupo=<?php echo $gid; ?>"><i class="fa fa-users"></i> <span>Alumnos</span></a></li>
          <li class="<?php echo $pagina=='asistencia.php'?'active':''; ?>"><a href="asistencia.php?idgrupo=<?php echo $gid; ?>"><i class="fa fa-check-square-o"></i> <span>Asistencia</span></a></li>
          <li class="<?php echo $pagina=='conducta.php'?'active':''; ?>"><a href="conducta.php?idgrupo=<?php echo $gid; ?>"><i class="fa fa-smile-o"></i> <span>Conducta</span></a></li>
          <li class="<?php echo $pagina=='calificaciones.php'?'active':''; ?>"><a href="calificaciones.php?idgrupo=<?php echo $gid; ?>"><i class="fa fa-tasks"></i> <span>Calificaciones</span></a></li>
          <li class="<?php echo $pagina=='cursos.php'?'active':''; ?>"><a href="cursos.php?idgrupo=<?php echo $gid; ?>"><i class="fa fa-th-large"></i> <span>Cursos</span></a></li>
          <li class="<?php echo $pagina=='listasis.php'?'active':''; ?>"><a href="listasis.php?idgrupo=<?php echo $gid; ?>"><i class="fa fa-list-alt"></i> <span>Listas</span></a></li>
          <?php endif; ?>

           <?php
if ($_SESSION['acceso']==1) {
  echo '<li class="header">Administracion</li>
        <li class="'.($pagina=='usuario.php'?'active':'').'"><a href="usuario.php"><i class="fa fa-user-md"></i> <span>Profesores</span></a></li>
        <li class="'.($pagina=='grupos.php'?'active':'').'"><a href="grupos.php"><i class="fa fa-graduation-cap"></i> <span>Grados</span></a></li>
        <li class="'.($pagina=='gestion_cursos.php'?'active':'').'"><a href="gestion_cursos.php"><i class="fa fa-th-large"></i> <span>Cursos</span></a></li>
        <li class="'.($pagina=='permiso.php'?'active':'').'"><a href="permiso.php"><i class="fa fa-key"></i> <span>Permisos</span></a></li>';
}
        ?>
      </ul>
    </section>
  </aside>