function nuevaCarpeta(idcurso){
	$("#tvTituloCarpeta").text("Nueva carpeta");
	$("#c_idfolder").val("");
	$("#c_idcurso").val(idcurso);
	$("#c_nombre").val("");
	$("#modalCarpeta").modal("show");
}

function editarCarpeta(id,nombre,idcurso){
	$("#tvTituloCarpeta").text("Renombrar carpeta");
	$("#c_idfolder").val(id);
	$("#c_idcurso").val(idcurso);
	$("#c_nombre").val(nombre);
	$("#modalCarpeta").modal("show");
}

function guardarCarpeta(){
	var nombre=$("#c_nombre").val().trim();
	if(nombre===""){ bootbox.alert("Escribe un nombre para la carpeta"); return; }
	var fd=new FormData();
	fd.append("idfolder",$("#c_idfolder").val());
	fd.append("idcurso",$("#c_idcurso").val());
	fd.append("nombre",nombre);
	$.ajax({
		url:"../ajax/folder.php?op=guardaryeditar",
		type:"POST", data:fd, contentType:false, processData:false,
		success:function(){ $("#modalCarpeta").modal("hide"); location.reload(); }
	});
}

function toggleCarpeta(id,activar){
	var op=activar==1?"activar":"desactivar";
	$.post("../ajax/folder.php?op="+op,{idfolder:id},function(){ location.reload(); });
}

function subirDocumento(idfolder){
	$("#d_idfolder").val(idfolder);
	$("#d_titulo").val("");
	$("#d_archivo").val("");
	$("#modalDocumento").modal("show");
}

function guardarDocumento(){
	var titulo=$("#d_titulo").val().trim();
	var archivo=$("#d_archivo")[0].files[0];
	if(titulo===""){ bootbox.alert("Escribe un titulo"); return; }
	if(!archivo){ bootbox.alert("Selecciona un archivo"); return; }
	$("#btnSubir").prop("disabled",true);
	var fd=new FormData();
	fd.append("idfolder",$("#d_idfolder").val());
	fd.append("titulo",titulo);
	fd.append("archivo",archivo);
	$.ajax({
		url:"../ajax/documento.php?op=guardaryeditar",
		type:"POST", data:fd, contentType:false, processData:false,
		success:function(datos){
			$("#btnSubir").prop("disabled",false);
			if(datos.replace(/\s/g,"")==="Documentosubidocorrectamente"){
				$("#modalDocumento").modal("hide");
				location.reload();
			}else{
				bootbox.alert(datos);
			}
		}
	});
}

function toggleDocumento(id,activar){
	var op=activar==1?"activar":"desactivar";
	$.post("../ajax/documento.php?op="+op,{iddocumento:id},function(){ location.reload(); });
}

function eliminarDocumento(id){
	bootbox.confirm("¿Eliminar definitivamente este documento?",function(r){
		if(r){ $.post("../ajax/documento.php?op=eliminar",{iddocumento:id},function(){ location.reload(); }); }
	});
}
