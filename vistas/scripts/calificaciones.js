var tabla;

function init(){
	var  team_id = $("#idgrupo").val();
   listar();

   $.post("../ajax/cursos.php?op=selectCursos",{idgrupo:team_id}, function(r){
   	$("#curso").html(r);
   	$('#curso').selectpicker('refresh');
   });

      $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })

}

$("#curso").change(function(){
	var idcurso=$("#curso").val();
	$("#idcurso").val(idcurso);
   listar();

});

function verificar(id){
	var idcurso = $("#idcurso").val();

	$.post("../ajax/calificaciones.php?op=verificar",{alumn_id:id, idcurso:idcurso},
		function(data,status)
		{
				data=JSON.parse(data);
				if(data==null && $("#idcurso").val()!=0){

					$("#getCodeModal").modal('show');
				 	$.post("../ajax/alumnos.php?op=mostrar",{idalumno : id},
						function(data,status)
						{
						data=JSON.parse(data);
						$("#alumn_id").val(data.id);
						});
				}else if(data=!null && $("#idcurso").val()!=0){
					 $("#getCodeModal").modal('show');
				 	$.post("../ajax/calificaciones.php?op=verificar",{alumn_id:id, idcurso:idcurso},
					function(data,status)
					{
						data=JSON.parse(data);
						$("#idcalificacion").val(data.id);
						$("#alumn_id").val(data.alumn_id);
						$("#valor").val(data.val);
						$("#idcurso").val(data.block_id);
					});

				}else if($("#idcurso").val()==0){
					bootbox.alert('Seleciona un curso');
					}
		})
	limpiar();

}

function limpiar(){
	$("#idcalificacion").val("");
	$("#alumn_id").val("");
	$("#valor").val("");
	$("#curso").selectpicker('refresh');
	$('#getCodeModal').modal('hide')
}

function listar(){
		var  team_id = $("#idgrupo").val();
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
			url:'../ajax/calificaciones.php?op=listar',
			data:{idgrupo:team_id},
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
     $("#btnGuardar").prop("disabled",false);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/calificaciones.php?op=guardaryeditar",
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

init();  