var tabla;

function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e){
		guardaryeditar(e);
	})
}

function limpiar(){
	$("#iddocumento").val("");
	$("#titulo").val("");
	$("#archivo").val("");
}

function mostrarform(flag){
	limpiar();
	if(flag){
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
	var folder_id = $("#idfolder").val();
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons: [],
		"ajax":{
			url:'../ajax/documento.php?op=listar',
			data:{idfolder:folder_id},
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,
		"order":[[0,"desc"]]
	}).DataTable();
}

function guardaryeditar(e){
	e.preventDefault();
	$("#btnGuardar").prop("disabled",true);
	var formData=new FormData($("#formulario")[0]);
	$.ajax({
		url: "../ajax/documento.php?op=guardaryeditar",
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
	$.post("../ajax/documento.php?op=mostrar",{iddocumento : id},
		function(data,status){
			data=JSON.parse(data);
			mostrarform(true);
			$("#titulo").val(data.title);
			$("#iddocumento").val(data.id);
			$("#archivo").prop("required",false);
		})
}

function desactivar(id){
	bootbox.confirm("¿Ocultar este documento a los alumnos?", function(result){
		if (result) {
			$.post("../ajax/documento.php?op=desactivar", {iddocumento : id}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(id){
	bootbox.confirm("¿Mostrar este documento a los alumnos?", function(result){
		if (result) {
			$.post("../ajax/documento.php?op=activar", {iddocumento : id}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function eliminar(id){
	bootbox.confirm("¿Eliminar definitivamente este documento?", function(result){
		if (result) {
			$.post("../ajax/documento.php?op=eliminar", {iddocumento : id}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

init();
