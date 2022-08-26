function v_hacerventa() {
  
}

jQuery(document).ready(function($) {

    $(document).on('click', '#cargarVenta', function() {
        $("#caja").css("display", "block");
    });

    $(document).on('click', '#bCerrarVenCaja', function() {
        $("#caja").css("display", "none");
    });

});

function TablaClientes(){
    ajaxMyDatatable({
        "table": $("#TablaClientes"), 
        "colums": [
            "Fecha",
            "Nombre",
            "Direccion",
            "Contacto",
            "Detalles",
            "Acciones"
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "clientes"
        }
    });
}