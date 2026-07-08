var tabla;
var tablaHist;

function init(){
   var now = new Date();
   var day =("0"+now.getDate()).slice(-2);
   var month=("0"+(now.getMonth()+1)).slice(-2);
   var hoy=now.getFullYear()+"-"+month+"-"+day;
   $("#fecha_sel").val(hoy);
   listar();
   listarHistorial();
   $("#fecha_sel").on("change",function(){
      tabla.ajax.reload(null,false);
   });
}

function verHoy(){
   var now = new Date();
   var day =("0"+now.getDate()).slice(-2);
   var month=("0"+(now.getMonth()+1)).slice(-2);
   var hoy=now.getFullYear()+"-"+month+"-"+day;
   $("#fecha_sel").val(hoy);
   tabla.ajax.reload(null,false);
}

function verFecha(fecha){
   $("#fecha_sel").val(fecha);
   tabla.ajax.reload(null,false);
   $('html,body').animate({scrollTop:0},300);
}

function listarHistorial(){
   var team_id = $("#idgrupo").val();
   tablaHist=$('#tblhistorial').dataTable({
      "aProcessing": true,
      "aServerSide": true,
      "ajax":{
         url:'../ajax/asistencia.php?op=historial',
         data:{idgrupo:team_id},
         type: "get",
         dataType : "json",
         error:function(e){ console.log(e.responseText); }
      },
      "bDestroy":true,
      "iDisplayLength":10,
      "columnDefs":[{ "targets":[5], "orderable":false }],
      "order":[]
   }).DataTable();
}

function verificar(id){

	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	var idgrupo = $("#idgrupo").val();

	$.post("../ajax/asistencia.php?op=verificar",{fecha_asistencia : today, alumn_id:id, idgrupo:idgrupo},
		function(data,status)
		{
				data=JSON.parse(data);
				if(data==null && $("#tipo_asistencia").val()!=""){

					 	$("#getCodeModal").modal('show');
					 	$.post("../ajax/alumnos.php?op=mostrar",{idalumno : id},
						function(data,status)
						{
							data=JSON.parse(data);
							$("#alumn_id").val(data.id);
						});

				}else if(data=!null && $("#tipo_asistencia").val()!=""){
					 $("#getCodeModal").modal('show');
				 	$.post("../ajax/asistencia.php?op=verificar",{fecha_asistencia : today, alumn_id:id, idgrupo:idgrupo},
					function(data,status)
					{
						data=JSON.parse(data);
						$("#idasistencia").val(data.id);
						$("#alumn_id").val(data.alumn_id);
						$("#tipo_asistencia").val(data.kind_id);
						$("#tipo_asistencia").selectpicker('refresh');
					});

				}else if($("#tipo_asistencia").val()==""){
					alert('borrar');
					}
		})
	limpiar();

}

function marcar(btn, alumnId, kindId){
	var $btn=$(btn);
	var $group=$btn.closest('.asis-group');
	var $prev=$group.find('.asis-btn.active');
	$group.find('.asis-btn').removeClass('active');
	$btn.addClass('active');
	var hoy = $("#fecha_sel").val();
	var idgrupo = $("#idgrupo").val();
	$.post("../ajax/asistencia.php?op=marcar",{tipo_asistencia:kindId, fecha_asistencia:hoy, alumn_id:alumnId, idgrupo:idgrupo})
		.done(function(r){
			if($.trim(r)!="ok"){
				$group.find('.asis-btn').removeClass('active');
				$prev.addClass('active');
				bootbox.alert("No se pudo guardar la asistencia.");
			}else if(typeof tablaHist!=="undefined"){
				tablaHist.ajax.reload(null,false);
			}
		})
		.fail(function(){
			$group.find('.asis-btn').removeClass('active');
			$prev.addClass('active');
			bootbox.alert("No se pudo guardar la asistencia. Intenta de nuevo.");
		});
}

function cerrarAsistencia(){
	bootbox.confirm("Se cerrara la asistencia de la fecha seleccionada. Los alumnos que sigan <b>Sin marcar</b> quedaran como <b>FALTA</b>. ¿Deseas continuar?", function(ok){
		if(!ok) return;
		var hoy = $("#fecha_sel").val();
		var idgrupo = $("#idgrupo").val();
		$.post("../ajax/asistencia.php?op=cerrar",{fecha_asistencia:hoy, idgrupo:idgrupo},
			function(r){
				var d=JSON.parse(r);
				tabla.ajax.reload(null,false);
				if(typeof tablaHist!=="undefined"){ tablaHist.ajax.reload(null,false); }
				bootbox.alert("<b>Asistencia cerrada</b><br><br><i class='fa fa-check' style='color:#18a558'></i> Presentes: "+d.presentes+"<br><i class='fa fa-clock-o' style='color:#e0902b'></i> Tardes: "+d.tardes+"<br><i class='fa fa-times' style='color:#e0453c'></i> Faltas: "+d.faltas);
			});
	});
}

function limpiar(){
	$("#idasistencia").val("");
	$("#alumn_id").val("");
	$("#tipo_asistencia").val("");
	$("#tipo_asistencia").selectpicker('refresh');

	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_asistencia").val(today);
	$('#getCodeModal').modal('hide')
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
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/asistencia.php?op=listar',
			data:function(d){ d.idgrupo=$("#idgrupo").val(); d.fecha=$("#fecha_sel").val(); },
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,
		"columnDefs":[{ "targets":[0,4,5], "orderable":false }],
		"order":[]
	}).DataTable();
}

function guardaryeditar(e){
     e.preventDefault();
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/alumnos.php?op=guardaryeditar",
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

     limpiar();
}

function guardaryeditar_asis(e){
     e.preventDefault();
     $("#btnGuardar_asis").prop("disabled",false);
     var formData=new FormData($("#formulario_asis")[0]);

     $.ajax({
     	url: "../ajax/asistencia.php?op=guardaryeditar",
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

     limpiar();
}

function mostrar(id){
	$.post("../ajax/alumnos.php?op=mostrar",{idalumno : id},
		function(data,status)
		{
			data=JSON.parse(data);
			alert(data.name);
			mostrarform(true);
			$("#nombre").val(data.name);
			$("#apellidos").val(data.lastname);
			$("#email").val(data.email);
			$("#direccion").val(data.address);
			$("#telefono").val(data.phone);
			$("#imagenmuestra").show();
			$("#imagenmuestra").attr("src","../files/articulos/"+data.image);
			$("#imagenactual").val(data.image);
			$("#idalumno").val(data.id);
		})
}

function desactivar(idalumno){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$.post("../ajax/alumnos.php?op=desactivar", {idalumno : idalumno}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idalumno){
	bootbox.confirm("¿Esta seguro de activar este dato?" , function(result){
		if (result) {
			$.post("../ajax/alumnos.php?op=activar" , {idalumno : idalumno}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

init();