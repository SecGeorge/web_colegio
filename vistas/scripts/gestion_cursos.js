var tabla;

function init(){
	mostrarform(false);
	cargarProfesores();
	listar();

	$("#formulario").on("submit",function(e){
		guardaryeditar(e);
	});

	$("#filtrogrado").on("change",function(){
		listar();
	});
}

function cargarProfesores(){
	$.get("../ajax/cursos.php?op=selectProfesores", function(data){
		$("#idprofesor").html(data);
	});
}

function limpiar(){
	$("#idcurso").val("");
	$("#nombre").val("");
	$("#idprofesor").val("0");
}

function mostrarform(flag){
	limpiar();
	if(flag){
		if($("#filtrogrado").val()=="0"){ bootbox.alert("Primero selecciona un grado."); return; }
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

function cancelarform(){
	limpiar();
	mostrarform(false);
}

function listar(){
	var idgrupo = $("#filtrogrado").val();
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons: [],
		"ajax":{
			url:'../ajax/cursos.php?op=listarAdmin',
			data:{idgrupo:idgrupo},
			type: "get",
			dataType : "json",
			error:function(e){ console.log(e.responseText); }
		},
		"bDestroy":true,
		"iDisplayLength":10,
		"columnDefs":[{ "targets":[2], "orderable":false }],
		"order":[]
	}).DataTable();
}

function guardaryeditar(e){
	e.preventDefault();
	$("#btnGuardar").prop("disabled",true);
	var formData=new FormData($("#formulario")[0]);
	formData.append("idgrupo", $("#filtrogrado").val());
	$.ajax({
		url: "../ajax/cursos.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function(datos){
			bootbox.alert(datos);
			mostrarform(false);
			tabla.ajax.reload();
		}
	});
}

function mostrar(id){
	$.post("../ajax/cursos.php?op=mostrar",{idcurso : id},
		function(data,status){
			data=JSON.parse(data);
			mostrarform(true);
			$("#nombre").val(data.name);
			$("#idcurso").val(data.id);
			$("#idprofesor").val(data.idusuario ? data.idusuario : "0");
		})
}

init();
