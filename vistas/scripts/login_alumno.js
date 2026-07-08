$("#frmAcceso").on('submit',function(e)
{
    e.preventDefault();
    logina=$("#logina").val();
    clavea=$("#clavea").val();

    $.post("../ajax/alumno.php?op=verificar",
        {"logina":logina,"clavea":clavea},
        function(data)
    {
        if ($.trim(data)!="null" && $.trim(data)!="")
        {
            $(location).attr("href","aula_alumno.php");
        }
        else
        {
            bootbox.alert("Usuario y/o Password incorrectos");
        }
    });
})
